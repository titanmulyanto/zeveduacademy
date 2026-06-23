<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="breadcrumbs mb-6">
    <a href="<?= base_url('admin/sertifikat') ?>" class="text-primary">Kelola Sertifikat</a>
    <span class="text-base-content/30">/</span>
    <span>Preview</span>
</div>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Preview Template Sertifikat</h1>
    <a href="<?= base_url('admin/sertifikat') ?>" class="btn btn-ghost">
        <i class="ph ph-arrow-left mr-2"></i> Kembali
    </a>
</div>

<!-- Info about this template -->
<div class="alert alert-info mb-6">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    <div>
        <div class="font-bold">Template untuk: <?= esc($produk['judul'] ?? 'Kelas') ?></div>
        <div class="text-sm">Ukuran: 16:10 (1920x1200px) - Cetak A4</div>
    </div>
</div>

<!-- Certificate Preview -->
<div class="card bg-base-100 shadow border border-base-200">
    <div class="card-body">
        <div class="overflow-auto">
            <div class="certificate-preview" style="
                width: 800px;
                height: 500px;
                margin: 0 auto;
                background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
                border: 15px solid #c9a227;
                border-radius: 10px;
                padding: 40px;
                box-sizing: border-box;
                position: relative;
                text-align: center;
                color: white;
                font-family: Georgia, serif;
            ">
                <!-- Background Image if exists -->
                <?php if (!empty($template['background_image'])): ?>
                    <div style="
                        position: absolute;
                        top: 0; left: 0; right: 0; bottom: 0;
                        background-image: url('<?= $template['background_image'] ?>');
                        background-size: cover;
                        background-position: center;
                        opacity: 0.2;
                        border-radius: 5px;
                    "></div>
                <?php endif; ?>

                <div style="position: relative; z-index: 1;">
                    <!-- Certificate Number (pojok kanan atas) -->
                    <div style="position: absolute; top: 10px; right: 20px; font-size: 10px; color: #aaa;">
                        <?= $certNumber ?? 'ZVD/2026/05/001' ?>
                    </div>

                    <!-- Training Date (pojok kiri atas) -->
                    <div style="position: absolute; top: 10px; left: 20px; font-size: 10px; color: #aaa;">
                        <?= date('d F Y') ?>
                    </div>

                    <!-- CERTIFICATE Header -->
                    <div style="font-size: 48px; font-weight: bold; color: #c9a227; letter-spacing: 8px; margin-bottom: 10px;">
                        CERTIFICATE
                    </div>

                    <!-- This certifies that -->
                    <div style="font-size: 18px; margin-bottom: 20px; opacity: 0.9;">
                        This certifies that
                    </div>

                    <!-- Student Name (BOLD) -->
                    <div style="font-size: 32px; font-weight: bold; text-transform: uppercase; margin-bottom: 15px;">
                        Nama Student
                    </div>

                    <!-- Minutes text -->
                    <div style="font-size: 16px; margin-bottom: 10px;">
                        has successfully attended and completed
                    </div>
                    <div style="font-size: 18px; font-weight: bold;">
                        <span style="color: #c9a227;"><?= $totalMinutes ?? 120 ?></span> minutes online training program for
                    </div>

                    <!-- Course Title (BOLD) -->
                    <div style="font-size: 24px; font-weight: bold; margin: 20px 0;">
                        <?= esc($produk['judul'] ?? 'Judul Kelas') ?>
                    </div>

                    <!-- Instructor Name (TENGAH - vertikal & horizontal) -->
                    <div style="
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        text-align: center;
                        width: 100%;
                    ">
                        <div style="font-size: 16px; font-weight: bold;">
                            <?= esc($pemateri ?? 'Nama Admin Pemateri') ?>
                        </div>
                        <div style="font-size: 12px; opacity: 0.7; border-top: 1px solid #aaa; margin-top: 5px; padding-top: 5px;">
                            Instructor / Pemateri
                        </div>
                    </div>

                    <!-- Seal -->
                    <div style="
                        position: absolute;
                        bottom: 40px;
                        right: 80px;
                        width: 80px;
                        height: 80px;
                        background: #c9a227;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 10px;
                        color: white;
                    ">
                        ZVD<br>CERTIFIED
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-warning mt-6">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <span>Ini adalah preview. Elemen sertifikat akan disesuaikan saat student mengakses.</span>
        </div>

        <div class="card-actions mt-4">
            <a href="<?= base_url('admin/sertifikat') ?>" class="btn btn-ghost">Kembali</a>
            <label for="upload_replace_modal" class="btn btn-primary">
                <i class="ph ph-upload mr-2"></i> Ganti Template
            </label>
        </div>
    </div>
</div>

<!-- Modal Upload Replace -->
<input type="checkbox" id="upload_replace_modal" class="modal-toggle" />
<dialog id="upload_replace_modal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">Ganti Template Sertifikat</h3>
        <form action="<?= base_url('admin/sertifikat/upload') ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_produk" value="<?= $produk['id_produk'] ?? '' ?>" />
            <div class="form-control">
                <label class="label">
                    <span class="label-text">File Background (JPG/PNG)</span>
                </label>
                <input type="file" name="template" class="file-input file-input-bordered" accept="image/jpeg,image/png" required />
            </div>
            <div class="modal-action">
                <label for="upload_replace_modal" class="btn">Batal</label>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </form>
    </div>
    <label class="modal-backdrop" for="upload_replace_modal"></label>
</dialog>

<?= $this->endSection() ?>