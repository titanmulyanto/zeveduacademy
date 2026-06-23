<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TransaksiModel;
use App\Models\ProdukModel;
use App\Models\UserModel;
use App\Models\KelasUserModel;
use App\Services\MidtransService;

/**
 * Payment Controller
 * Handle all payment-related operations including Midtrans integration
 */
class PaymentController extends BaseController
{
    protected $transaksiModel;
    protected $produkModel;
    protected $userModel;
    protected $kelasUserModel;
    protected $midtransService;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiModel();
        $this->produkModel = new ProdukModel();
        $this->userModel = new UserModel();
        $this->midtransService = new MidtransService();
    }

    /**
     * Initialize KelasUserModel dynamically since it might not exist yet
     */
    private function getKelasUserModel()
    {
        if (!isset($this->kelasUserModel)) {
            // Check if model exists
            $modelPath = APPPATH . 'Models/KelasUserModel.php';
            if (file_exists($modelPath)) {
                $this->kelasUserModel = new \App\Models\KelasUserModel();
            } else {
                // Return null or create dynamic query
                $this->kelasUserModel = null;
            }
        }
        return $this->kelasUserModel;
    }

    /**
     * Start payment process for a class
     *
     * @param int $produkId Product/Class ID
     */
    public function start($produkId)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $userId = session()->get('id_user');
        $produk = $this->produkModel->find($produkId);

        if (!$produk) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        // Check if user already has access
        $kelasUserModel = $this->getKelasUserModel();
        if ($kelasUserModel) {
            $hasAccess = $kelasUserModel->where('id_user', $userId)
                                       ->where('id_produk', $produkId)
                                       ->where('status_akses', 'aktif')
                                       ->first();
            if ($hasAccess) {
                return redirect()->to('/kelas/' . $produkId)->with('info', 'Anda sudah memiliki akses ke kelas ini.');
            }
        }

        // Check if product is free (harga_promo = 0)
        if ($produk['harga_promo'] == 0 || $produk['harga_awal'] == 0) {
            // Free class - direct access
            return $this->processFreeClass($userId, $produkId, $produk);
        }

        // Paid class - generate Midtrans token
        return $this->processPaidClass($userId, $produkId, $produk);
    }

    /**
     * Process free class enrollment
     */
    private function processFreeClass($userId, $produkId, $produk)
    {
        $kelasUserModel = $this->getKelasUserModel();

        // Create transaction record for tracking
        $transaksiData = [
            'id_user' => $userId,
            'id_produk' => $produkId,
            'kode_invoice' => $this->generateInvoiceCode(),
            'total_bayar' => 0,
            'status_pembayaran' => 'paid', // Free = langsung paid
            'tanggal_transaksi' => date('Y-m-d H:i:s')
        ];

        $this->transaksiModel->insert($transaksiData);

        // Grant access
        if ($kelasUserModel) {
            $kelasUserModel->insert([
                'id_user' => $userId,
                'id_produk' => $produkId,
                'status_akses' => 'aktif',
                'tanggal_aktif' => date('Y-m-d H:i:s'),
                'sumber_akses' => 'free'
            ]);
        }

        return redirect()->to('/kelas/' . $produkId)->with('success', 'Selamat! Anda berhasil mendaftar kelas gratis.');
    }

    /**
     * Process paid class - generate Midtrans Snap token
     */
    private function processPaidClass($userId, $produkId, $produk)
    {
        $user = $this->userModel->find($userId);

        // Generate order ID
        $orderId = $this->midtransService->generateOrderId();
        $grossAmount = (int) $produk['harga_promo'];

        // Prepare customer details
        $customerDetails = [
            'first_name' => $user['nama_lengkap'] ?? '',
            'email' => $user['email'] ?? '',
            'phone' => $user['no_whatsapp'] ?? ''
        ];

        // Prepare item details
        $itemDetails = [
            [
                'id' => $produkId,
                'price' => $grossAmount,
                'quantity' => 1,
                'name' => substr($produk['judul'], 0, 50)
            ]
        ];

        try {
            // Get Snap token from Midtrans
            $snapResult = $this->midtransService->getSnapToken([
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
                'customer_details' => $customerDetails,
                'item_details' => $itemDetails
            ]);

            if (isset($snapResult['token'])) {
                // Save transaction with snap_token
                $transaksiData = [
                    'id_user' => $userId,
                    'id_produk' => $produkId,
                    'kode_invoice' => $this->generateInvoiceCode(),
                    'total_bayar' => $grossAmount,
                    'status_pembayaran' => 'pending',
                    'midtrans_order_id' => $orderId,
                    'snap_token' => $snapResult['token'],
                    'tanggal_transaksi' => date('Y-m-d H:i:s')
                ];

                $this->transaksiModel->insert($transaksiData);

                // Return view with Snap token for payment
                $data = [
                    'title' => 'Pembayaran - ' . $produk['judul'],
                    'snapToken' => $snapResult['token'],
                    'clientKey' => $this->midtransService->getClientKey(),
                    'produk' => $produk,
                    'orderId' => $orderId
                ];

                return view('payment/checkout', $data);
            } else {
                return redirect()->back()->with('error', 'Gagal mendapatkan token pembayaran. ' . ($snapResult['status_message'] ?? ''));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Handle payment finish redirect from Midtrans
     */
    public function finish()
    {
        $orderId = $this->request->getGet('order_id');

        if (empty($orderId)) {
            return redirect()->to('/')->with('error', 'Order ID tidak ditemukan.');
        }

        try {
            // Get transaction status from Midtrans
            $status = $this->midtransService->getTransactionStatus($orderId);

            // Update local transaction
            $this->updateTransactionStatus($orderId, $status);

            // Get transaction data
            $transaksi = $this->transaksiModel->where('midtrans_order_id', $orderId)->first();

            if ($transaksi && $status['transaction_status'] == 'settlement') {
                // Payment successful - grant access
                $this->grantKelasAccess($transaksi['id_user'], $transaksi['id_produk']);
                return redirect()->to('/kelas/' . $transaksi['id_produk'])
                    ->with('success', 'Pembayaran berhasil! Selamat belajar.');
            }

            return redirect()->to('/')->with('info', 'Pembayaran sedang diproses. Status akan diupdate otomatis.');
        } catch (\Exception $e) {
            return redirect()->to('/')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Handle unfinish redirect
     */
    public function unfinish()
    {
        return redirect()->to('/')->with('warning', 'Pembayaran belum selesai. Silakan coba lagi.');
    }

    /**
     * Handle error redirect
     */
    public function error()
    {
        return redirect()->to('/')->with('error', 'Terjadi kesalahan dalam pembayaran. Silakan coba lagi.');
    }

    /**
     * Handle Midtrans webhook notification
     */
    public function notification()
    {
        // Get JSON notification from Midtrans
        $notification = json_decode(file_get_contents('php://input'), true);

        if (empty($notification)) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Invalid notification']);
        }

        try {
            // Verify and parse notification
            $notificationData = $this->midtransService->handleNotification($notification);

            // Update transaction
            $transaksi = $this->transaksiModel->where('midtrans_order_id', $notificationData['order_id'])->first();

            if ($transaksi) {
                // Update transaction status
                $this->transaksiModel->update($transaksi['id_transaksi'], [
                    'status_pembayaran' => $notificationData['status']
                ]);

                // If payment successful, grant access
                if ($notificationData['status'] == 'paid') {
                    $this->grantKelasAccess($transaksi['id_user'], $transaksi['id_produk']);
                }
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Notification processed'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Midtrans notification error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update transaction status from Midtrans response
     */
    private function updateTransactionStatus(string $orderId, array $status): void
    {
        $transaksi = $this->transaksiModel->where('midtrans_order_id', $orderId)->first();

        if ($transaksi) {
            $statusMap = [
                'capture' => 'paid',
                'settlement' => 'paid',
                'pending' => 'pending',
                'deny' => 'failed',
                'cancel' => 'failed',
                'expire' => 'failed'
            ];

            $newStatus = $statusMap[$status['transaction_status']] ?? 'pending';

            $this->transaksiModel->update($transaksi['id_transaksi'], [
                'status_pembayaran' => $newStatus
            ]);
        }
    }

    /**
     * Grant user access to a class
     */
    private function grantKelasAccess(int $userId, int $produkId): void
    {
        $kelasUserModel = $this->getKelasUserModel();

        if ($kelasUserModel) {
            // Check if already has access
            $existing = $kelasUserModel->where('id_user', $userId)
                                       ->where('id_produk', $produkId)
                                       ->first();

            if (!$existing) {
                $kelasUserModel->insert([
                    'id_user' => $userId,
                    'id_produk' => $produkId,
                    'status_akses' => 'aktif',
                    'tanggal_aktif' => date('Y-m-d H:i:s'),
                    'sumber_akses' => 'payment'
                ]);
            } else {
                // Update existing access
                $kelasUserModel->update($existing['id_kelas_user'], [
                    'status_akses' => 'aktif',
                    'tanggal_aktif' => date('Y-m-d H:i:s')
                ]);
            }
        }
    }

    /**
     * Generate invoice code
     */
    private function generateInvoiceCode(): string
    {
        return 'INV-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
    }
}