<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SliderBannerModel;
use App\Models\FeatureSectionModel;
use App\Models\FaqModel;
use App\Models\TestimoniModel;

class CmsController extends BaseController
{
    protected $sliderModel;
    protected $featureModel;
    protected $faqModel;
    protected $testimoniModel;

    public function __construct()
    {
        $this->sliderModel = new SliderBannerModel();
        $this->featureModel = new FeatureSectionModel();
        $this->faqModel = new FaqModel();
        $this->testimoniModel = new TestimoniModel();
        helper(['form', 'url']);
    }

    // ==================== SLIDER BANNER ====================
    // Note: Upload gambar rasio 1:1 (contoh: 1080x1080px)

    public function slider()
    {
        $data = [
            'title' => 'Kelola Slider Banner',
            'sliders' => $this->sliderModel->orderBy('urutan', 'ASC')->findAll()
        ];
        return view('admin/cms/slider', $data);
    }

    public function storeSlider()
    {
        $file = $this->request->getFile('gambar');
        $urutan = $this->request->getPost('urutan');

        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid.');
        }

        // Validate image type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            return redirect()->back()->with('error', 'Hanya file JPG, PNG, WEBP yang diizinkan.');
        }

        // Create directory if not exists
        $uploadPath = FCPATH . 'uploads/cms/slider/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $newName = 'slider_' . time() . '_' . $file->getRandomName();
        $file->move($uploadPath, $newName);

        $this->sliderModel->insert([
            'gambar' => 'cms/slider/' . $newName,
            'urutan' => $urutan ?? 1
        ]);

        return redirect()->to('/admin/cms/slider')->with('success', 'Slider berhasil ditambahkan.');
    }

    public function updateSlider($id)
    {
        $slider = $this->sliderModel->find($id);
        if (!$slider) {
            return redirect()->back()->with('error', 'Slider tidak ditemukan.');
        }

        $data = [
            'urutan' => $this->request->getPost('urutan') ?? 1
        ];

        $file = $this->request->getFile('gambar');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if (in_array($file->getMimeType(), $allowedTypes)) {
                $newName = 'slider_' . time() . '_' . $file->getRandomName();
                $file->move(FCPATH . 'uploads/cms/slider/', $newName);
                $data['gambar'] = 'cms/slider/' . $newName;
            }
        }

        $this->sliderModel->update($id, $data);
        return redirect()->to('/admin/cms/slider')->with('success', 'Slider berhasil diupdate.');
    }

    public function deleteSlider($id)
    {
        $slider = $this->sliderModel->find($id);
        if ($slider) {
            $this->sliderModel->delete($id);
        }
        return redirect()->to('/admin/cms/slider')->with('success', 'Slider berhasil dihapus.');
    }

    public function reorderSlider()
    {
        $orders = $this->request->getPost('urutan');

        if ($orders) {
            foreach ($orders as $id => $urutan) {
                $this->sliderModel->update($id, ['urutan' => $urutan]);
            }
        }

        return redirect()->to('/admin/cms/slider')->with('success', 'Urutan slider berhasil diupdate.');
    }

    // ==================== FEATURE SECTION ====================
    // Note: Upload gambar rasio 4:3 (contoh: 800x600px)

    public function features()
    {
        $data = [
            'title' => 'Kelola Fitur Unggulan',
            'features' => $this->featureModel->orderBy('id_feature', 'DESC')->findAll()
        ];
        return view('admin/cms/features', $data);
    }

    public function storeFeature()
    {
        $file = $this->request->getFile('gambar');
        $judul = $this->request->getPost('judul');
        $deskripsi = $this->request->getPost('deskripsi');

        $gambarPath = '';
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if (in_array($file->getMimeType(), $allowedTypes)) {
                $uploadPath = FCPATH . 'uploads/cms/features/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                $newName = 'feature_' . time() . '_' . $file->getRandomName();
                $file->move($uploadPath, $newName);
                $gambarPath = 'cms/features/' . $newName;
            }
        }

        $this->featureModel->insert([
            'judul' => $judul,
            'deskripsi' => $deskripsi,
            'gambar' => $gambarPath
        ]);

        return redirect()->to('/admin/cms/features')->with('success', 'Fitur berhasil ditambahkan.');
    }

    public function updateFeature($id)
    {
        $feature = $this->featureModel->find($id);
        if (!$feature) {
            return redirect()->back()->with('error', 'Fitur tidak ditemukan.');
        }

        $data = [
            'judul' => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi')
        ];

        $file = $this->request->getFile('gambar');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if (in_array($file->getMimeType(), $allowedTypes)) {
                $uploadPath = FCPATH . 'uploads/cms/features/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                $newName = 'feature_' . time() . '_' . $file->getRandomName();
                $file->move($uploadPath, $newName);
                $data['gambar'] = 'cms/features/' . $newName;
            }
        }

        $this->featureModel->update($id, $data);
        return redirect()->to('/admin/cms/features')->with('success', 'Fitur berhasil diupdate.');
    }

    public function deleteFeature($id)
    {
        $this->featureModel->delete($id);
        return redirect()->to('/admin/cms/features')->with('success', 'Fitur berhasil dihapus.');
    }

    // ==================== FAQ ====================

    public function faq()
    {
        $data = [
            'title' => 'Kelola FAQ',
            'faqs' => $this->faqModel->orderBy('id_faq', 'DESC')->findAll()
        ];
        return view('admin/cms/faq', $data);
    }

    public function storeFaq()
    {
        $this->faqModel->insert([
            'pertanyaan' => $this->request->getPost('pertanyaan'),
            'jawaban'    => $this->request->getPost('jawaban')
        ]);
        return redirect()->to('/admin/cms/faq')->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function updateFaq($id)
    {
        $this->faqModel->update($id, [
            'pertanyaan' => $this->request->getPost('pertanyaan'),
            'jawaban'    => $this->request->getPost('jawaban')
        ]);
        return redirect()->to('/admin/cms/faq')->with('success', 'FAQ berhasil diupdate.');
    }

    public function deleteFaq($id)
    {
        $this->faqModel->delete($id);
        return redirect()->to('/admin/cms/faq')->with('success', 'FAQ berhasil dihapus.');
    }

    // ==================== TESTIMONI ====================
    // Rating: bilangan bulat 1-10
    // Note: Upload foto rasio 1:1

    public function testimoni()
    {
        $data = [
            'title' => 'Kelola Testimoni',
            'testimonials' => $this->testimoniModel->orderBy('id_testimoni', 'DESC')->findAll()
        ];
        return view('admin/cms/testimoni', $data);
    }

    public function storeTestimoni()
    {
        $file = $this->request->getFile('foto_profil');
        $nama = $this->request->getPost('nama');
        $deskripsi = $this->request->getPost('deskripsi');
        $rating = (int) $this->request->getPost('rating');

        // Validate rating 1-10
        if ($rating < 1 || $rating > 10) {
            $rating = 5; // Default
        }

        $fotoPath = '';
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if (in_array($file->getMimeType(), $allowedTypes)) {
                $uploadPath = FCPATH . 'uploads/cms/testimoni/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                $newName = 'testi_' . time() . '_' . $file->getRandomName();
                $file->move($uploadPath, $newName);
                $fotoPath = 'cms/testimoni/' . $newName;
            }
        } else {
            // Default avatar
            $fotoPath = 'https://ui-avatars.com/api/?name=' . urlencode($nama ?? 'U') . '&size=200&background=random';
        }

        $this->testimoniModel->insert([
            'id_user' => session()->get('id_user') ?? null, // Admin yang input
            'id_produk' => null, // Tidak terikat produk tertentu
            'nama' => $nama,
            'deskripsi' => $deskripsi,
            'rating' => $rating,
            'foto_profil' => $fotoPath
        ]);

        return redirect()->to('/admin/cms/testimoni')->with('success', 'Testimoni berhasil ditambahkan.');
    }

    public function updateTestimoni($id)
    {
        $testimoni = $this->testimoniModel->find($id);
        if (!$testimoni) {
            return redirect()->back()->with('error', 'Testimoni tidak ditemukan.');
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'rating' => (int) $this->request->getPost('rating') ?? 5
        ];

        // Validate rating
        if ($data['rating'] < 1 || $data['rating'] > 10) {
            $data['rating'] = 5;
        }

        $file = $this->request->getFile('foto_profil');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if (in_array($file->getMimeType(), $allowedTypes)) {
                $uploadPath = FCPATH . 'uploads/cms/testimoni/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                $newName = 'testi_' . time() . '_' . $file->getRandomName();
                $file->move($uploadPath, $newName);
                $data['foto_profil'] = 'cms/testimoni/' . $newName;
            }
        }

        $this->testimoniModel->update($id, $data);
        return redirect()->to('/admin/cms/testimoni')->with('success', 'Testimoni berhasil diupdate.');
    }

    public function deleteTestimoni($id)
    {
        $this->testimoniModel->delete($id);
        return redirect()->to('/admin/cms/testimoni')->with('success', 'Testimoni berhasil dihapus.');
    }
}