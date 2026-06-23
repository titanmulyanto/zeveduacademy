<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public routes
$routes->get('/', 'Landing::index');
$routes->get('login', 'AuthController::index');
$routes->get('register', 'AuthController::register');
$routes->post('auth/login', 'AuthController::login');
$routes->post('auth/register', 'AuthController::registerProcess');
$routes->get('auth/logout', 'AuthController::logout');
$routes->get('produk/(:num)', 'ProdukController::detail/$1');

// Debug routes (remove in production)
$routes->get('debug-video', 'DebugController::video');
$routes->get('debug-chat', 'DebugController::chat');

// Payment routes (Midtrans integration)
$routes->post('payment/start/(:num)', 'PaymentController::start/$1');
$routes->get('payment/finish', 'PaymentController::finish');
$routes->get('payment/unfinish', 'PaymentController::unfinish');
$routes->get('payment/error', 'PaymentController::error');
$routes->post('payment/notification', 'PaymentController::notification');

// Public Sertifikat routes (locked/unlocked logic)
$routes->get('sertifikat/show/(:num)/(:num)', 'SertifikatController::show/$1/$2');
$routes->get('sertifikat/download/(:num)/(:num)', 'SertifikatController::download/$1/$2');
$routes->get('sertifikat/status/(:num)/(:num)', 'SertifikatController::checkStatus/$1/$2');

// Student routes (must be logged in as student)
$routes->group('student', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'StudentController::index');
    $routes->get('profile', 'StudentController::profile');
    $routes->post('update-profile', 'StudentController::updateProfile');
    $routes->post('change-password', 'StudentController::changePassword');
    $routes->get('kelas/(:num)', 'StudentController::kelas/$1');
    $routes->get('video/(:num)', 'StudentController::watchVideo/$1');
    $routes->post('update-progress', 'StudentController::updateProgress');
    $routes->post('mark-complete', 'StudentController::markComplete');
    $routes->get('exam/(:num)', 'StudentController::exam/$1');
    $routes->post('submit-exam', 'StudentController::submitExam');
    $routes->get('exam-result/(:num)', 'StudentController::examResult/$1');
    $routes->post('rate-exam', 'StudentController::rateExam');
    $routes->get('retry-exam/(:num)', 'StudentController::retryExam/$1');
    $routes->post('send-chat', 'StudentController::sendChat');
    $routes->get('sertifikat/(:num)', 'StudentController::sertifikat/$1');
    $routes->get('sertifikat', 'StudentController::sertifikatList');
});

// ==================== ADMIN ROUTES ====================

// SUPER ADMIN ONLY routes (Dashboard, Users, Transaksi, CMS, Sertifikat)
$routes->group('admin', ['filter' => 'role:super_admin'], function($routes) {
    $routes->get('dashboard', '\App\Controllers\Admin\DashboardController::index');
    $routes->get('dashboard/statistics', '\App\Controllers\Admin\DashboardController::statistics');
    $routes->get('dashboard/uploaded-files', '\App\Controllers\Admin\DashboardController::uploadedFiles');

    $routes->get('users', '\App\Controllers\Admin\UserController::index');
    $routes->post('users', '\App\Controllers\Admin\UserController::store');
    $routes->get('users/edit/(:num)', '\App\Controllers\Admin\UserController::edit/$1');
    $routes->post('users/update/(:num)', '\App\Controllers\Admin\UserController::update/$1');
    $routes->get('users/delete/(:num)', '\App\Controllers\Admin\UserController::delete/$1');
    $routes->get('users/view/(:num)', '\App\Controllers\Admin\UserController::view/$1');
    $routes->get('transactions', '\App\Controllers\Admin\UserController::transactions');
    $routes->post('users/add-bonus', '\App\Controllers\Admin\UserController::addBonusStudent');

    // Sertifikat (Super Admin only - Kelola admin/pemateri & template)
    $routes->get('sertifikat', '\App\Controllers\Admin\SertifikatController::index');
    $routes->get('sertifikat/students/(:num)', '\App\Controllers\Admin\SertifikatController::students/$1');
    $routes->get('sertifikat/view-user-certificates/(:num)', '\App\Controllers\Admin\SertifikatController::viewUserCertificates/$1');
    $routes->post('sertifikat/issue-certificate/(:num)', '\App\Controllers\Admin\SertifikatController::issueCertificate/$1');
    $routes->post('sertifikat/add-student/(:num)', '\App\Controllers\Admin\SertifikatController::addStudent/$1');
    $routes->post('sertifikat/create-student/(:num)', '\App\Controllers\Admin\SertifikatController::createStudent/$1');
    $routes->post('sertifikat/enroll-multiple', '\App\Controllers\Admin\SertifikatController::enrollMultiple');
    $routes->post('sertifikat/upload', '\App\Controllers\Admin\SertifikatController::upload');
    $routes->get('sertifikat/preview/(:num)', '\App\Controllers\Admin\SertifikatController::preview/$1');
    $routes->get('sertifikat/delete/(:num)', '\App\Controllers\Admin\SertifikatController::delete/$1');
    $routes->post('sertifikat/add-admin', '\App\Controllers\Admin\SertifikatController::addAdmin');
    $routes->get('sertifikat/remove-admin/(:num)', '\App\Controllers\Admin\SertifikatController::removeAdmin/$1');

    // Kategori Materi (Super Admin only)
    $routes->get('kategori-materi', '\App\Controllers\Admin\KategoriMateriController::index');
    $routes->post('kategori-materi/store', '\App\Controllers\Admin\KategoriMateriController::store');
    $routes->post('kategori-materi/update/(:num)', '\App\Controllers\Admin\KategoriMateriController::update/$1');
    $routes->get('kategori-materi/delete/(:num)', '\App\Controllers\Admin\KategoriMateriController::delete/$1');
    $routes->get('kategori-materi/get-by-produk/(:num)', '\App\Controllers\Admin\KategoriMateriController::getByProduk/$1');

    // CMS Landing Page (Super Admin only)
    $routes->group('cms', function($routes) {
        $routes->get('slider', '\App\Controllers\Admin\CmsController::slider');
        $routes->post('slider', '\App\Controllers\Admin\CmsController::storeSlider');
        $routes->post('slider/update/(:num)', '\App\Controllers\Admin\CmsController::updateSlider/$1');
        $routes->get('slider/delete/(:num)', '\App\Controllers\Admin\CmsController::deleteSlider/$1');
        $routes->post('slider/reorder', '\App\Controllers\Admin\CmsController::reorderSlider');

        $routes->get('features', '\App\Controllers\Admin\CmsController::features');
        $routes->post('features', '\App\Controllers\Admin\CmsController::storeFeature');
        $routes->post('features/update/(:num)', '\App\Controllers\Admin\CmsController::updateFeature/$1');
        $routes->get('features/delete/(:num)', '\App\Controllers\Admin\CmsController::deleteFeature/$1');

        $routes->get('faq', '\App\Controllers\Admin\CmsController::faq');
        $routes->post('faq', '\App\Controllers\Admin\CmsController::storeFaq');
        $routes->post('faq/update/(:num)', '\App\Controllers\Admin\CmsController::updateFaq/$1');
        $routes->get('faq/delete/(:num)', '\App\Controllers\Admin\CmsController::deleteFaq/$1');

        $routes->get('testimoni', '\App\Controllers\Admin\CmsController::testimoni');
        $routes->post('testimoni', '\App\Controllers\Admin\CmsController::storeTestimoni');
        $routes->post('testimoni/update/(:num)', '\App\Controllers\Admin\CmsController::updateTestimoni/$1');
        $routes->get('testimoni/delete/(:num)', '\App\Controllers\Admin\CmsController::deleteTestimoni/$1');
    });
});

// ==================== ADMIN & SUPER ADMIN ROUTES ====================
// Both Admin and Super Admin can access Materi & Ujian
$routes->group('admin', ['filter' => 'role:admin,super_admin'], function($routes) {
    // Kategori Produk (Super Admin only for create/delete)
    $routes->get('kategori', '\App\Controllers\Admin\KategoriProdukController::index');
    $routes->post('kategori/store', '\App\Controllers\Admin\KategoriProdukController::store');
    $routes->post('kategori/update/(:num)', '\App\Controllers\Admin\KategoriProdukController::update/$1');
    $routes->get('kategori/delete/(:num)', '\App\Controllers\Admin\KategoriProdukController::delete/$1');

    // Materi Management
    $routes->get('materi', '\App\Controllers\Admin\MateriController::index');
    $routes->get('materi/create', '\App\Controllers\Admin\MateriController::create');
    $routes->post('materi/store', '\App\Controllers\Admin\MateriController::store');
    $routes->get('materi/edit/(:num)', '\App\Controllers\Admin\MateriController::edit/$1');
    $routes->post('materi/update/(:num)', '\App\Controllers\Admin\MateriController::update/$1');
    $routes->get('materi/delete/(:num)', '\App\Controllers\Admin\MateriController::delete/$1');
    $routes->post('materi/store-kategori', '\App\Controllers\Admin\MateriController::storeKategori');
    $routes->post('materi/update-kategori/(:num)', '\App\Controllers\Admin\MateriController::updateKategori/$1');
    $routes->get('materi/delete-kategori/(:num)', '\App\Controllers\Admin\MateriController::deleteKategori/$1');
    $routes->post('materi/store-video', '\App\Controllers\Admin\MateriController::storeVideo');
    $routes->post('materi/update-video/(:num)', '\App\Controllers\Admin\MateriController::updateVideo/$1');
    $routes->get('materi/delete-video/(:num)', '\App\Controllers\Admin\MateriController::deleteVideo/$1');
    $routes->post('materi/store-materi', '\App\Controllers\Admin\MateriController::storeMateri');
    $routes->post('materi/update-materi/(:num)', '\App\Controllers\Admin\MateriController::updateMateri/$1');
    $routes->get('materi/delete-materi/(:num)', '\App\Controllers\Admin\MateriController::deleteMateri/$1');
    $routes->post('materi/send-chat', '\App\Controllers\Admin\MateriController::sendChat');
    $routes->get('materi/delete-chat/(:num)', '\App\Controllers\Admin\MateriController::deleteChat/$1');
    $routes->get('materi/detail/(:num)', '\App\Controllers\Admin\MateriController::detail/$1');

    // Ujian Management
    $routes->get('ujian', '\App\Controllers\Admin\UjianController::index');
    $routes->get('ujian/create', '\App\Controllers\Admin\UjianController::create');
    $routes->get('ujian/create/(:num)', '\App\Controllers\Admin\UjianController::create/$1');
    $routes->post('ujian', '\App\Controllers\Admin\UjianController::store');
    $routes->get('ujian/edit/(:num)', '\App\Controllers\Admin\UjianController::edit/$1');
    $routes->post('ujian/update/(:num)', '\App\Controllers\Admin\UjianController::update/$1');
    $routes->get('ujian/delete/(:num)', '\App\Controllers\Admin\UjianController::delete/$1');
    $routes->get('ujian/questions/(:num)', '\App\Controllers\Admin\UjianController::questions/$1');
    $routes->post('ujian/store-question', '\App\Controllers\Admin\UjianController::storeQuestion');
    $routes->post('ujian/update-question/(:num)', '\App\Controllers\Admin\UjianController::updateQuestion/$1');
    $routes->get('ujian/delete-question/(:num)', '\App\Controllers\Admin\UjianController::deleteQuestion/$1');
    $routes->get('ujian/results', '\App\Controllers\Admin\UjianController::results');
    $routes->get('ujian/view-result/(:num)', '\App\Controllers\Admin\UjianController::viewResult/$1');
});