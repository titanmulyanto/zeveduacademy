<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Pembayaran' ?></title>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= $clientKey ?? '' ?>"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .payment-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 500px;
            width: 100%;
            overflow: hidden;
        }
        .payment-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .payment-header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .payment-header p {
            opacity: 0.9;
            font-size: 14px;
        }
        .payment-body {
            padding: 30px;
        }
        .order-details {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .order-details h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .order-item.total {
            font-weight: bold;
            color: #667eea;
            font-size: 18px;
            padding-top: 15px;
            margin-top: 10px;
        }
        .btn-pay {
            width: 100%;
            padding: 15px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }
        .btn-pay:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        .btn-cancel {
            width: 100%;
            padding: 12px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            cursor: pointer;
            margin-top: 10px;
            text-decoration: none;
            display: block;
            text-align: center;
        }
        .btn-cancel:hover {
            background: #c82333;
        }
        .security-note {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 12px;
        }
        .security-note i {
            color: #28a745;
        }
        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }
        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <div class="payment-header">
            <h1>Pembayaran Kelas</h1>
            <p>ZevEdu Academy</p>
        </div>
        <div class="payment-body">
            <div class="order-details">
                <h3>Detail Pesanan</h3>
                <div class="order-item">
                    <span>Produk</span>
                    <span><?= esc($produk['judul'] ?? 'Kelas') ?></span>
                </div>
                <div class="order-item">
                    <span>Order ID</span>
                    <span><?= esc($orderId ?? '') ?></span>
                </div>
                <div class="order-item total">
                    <span>Total Bayar</span>
                    <span>Rp <?= number_format($produk['harga_promo'] ?? 0, 0, ',', '.') ?></span>
                </div>
            </div>

            <button id="pay-button" class="btn-pay">
                Bayar Sekarang
            </button>
            <a href="<?= base_url('/') ?>" class="btn-cancel">Batalkan</a>

            <div class="loading" id="loading">
                <div class="spinner"></div>
                <p>Memproses pembayaran...</p>
            </div>

            <div class="security-note">
                <i class="fas fa-lock"></i> Pembayaran aman dengan Midtrans
            </div>
        </div>
    </div>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var payButton = document.getElementById('pay-button');
            var loading = document.getElementById('loading');

            payButton.addEventListener('click', function() {
                payButton.disabled = true;
                loading.style.display = 'block';

                snap.pay('<?= $snapToken ?? '' ?>', {
                    onSuccess: function(result) {
                        // Payment successful
                        window.location.href = '<?= base_url('/payment/finish') ?>?order_id=' + result.order_id;
                    },
                    onPending: function(result) {
                        // Payment pending
                        window.location.href = '<?= base_url('/payment/finish') ?>?order_id=' + result.order_id;
                    },
                    onError: function(result) {
                        // Payment error
                        alert('Terjadi kesalahan: ' + (result.status_message || 'Silakan coba lagi'));
                        payButton.disabled = false;
                        loading.style.display = 'none';
                    },
                    onClose: function() {
                        // Popup closed without payment
                        payButton.disabled = false;
                        loading.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>