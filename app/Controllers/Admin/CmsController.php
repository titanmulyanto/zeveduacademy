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
    }

    public function slider()
    {
        $data = [
            'title' => 'Kelola Slider Banner',
            'sliders' => $this->sliderModel->orderBy('urutan', 'ASC')->findAll()
        ];
        return view('admin/cms/slider', $data);
    }

    public function features()
    {
        $data = [
            'title' => 'Kelola Fitur Unggulan',
            'features' => $this->featureModel->findAll()
        ];
        return view('admin/cms/features', $data);
    }

    public function faq()
    {
        $data = [
            'title' => 'Kelola FAQ',
            'faqs' => $this->faqModel->findAll()
        ];
        return view('admin/cms/faq', $data);
    }

    public function testimoni()
    {
        $data = [
            'title' => 'Kelola Testimoni',
            'testimonials' => $this->testimoniModel->findAll()
        ];
        return view('admin/cms/testimoni', $data);
    }
}
