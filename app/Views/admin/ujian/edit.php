<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="breadcrumbs mb-6">
    <a href="<?= base_url('admin/materi') ?>" class="text-primary">Materi & Tes</a>
    <span class="text-base-content/30">/</span>
    <span>Edit Ujian</span>
</div>

<h1 class="text-2xl font-bold mb-6">✏️ Edit Konfigurasi Ujian</h1>

<div class="card bg-base-100 shadow border border-base-200">
    <div class="card-body">
        <form action="<?= base_url('admin/ujian/update/' . $ujian['id_ujian']) ?>" method="POST">
            <?= csrf_field() ?>

            <div class="alert alert-info mb-4">
                <i class="ph ph-info"></i>
                <span>Edit konfigurasi ujian untuk kelas ini</span>
            </div>

            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Nama Ujian</span>
                </label>
                <input type="text" name="judul_ujian" value="<?= esc($ujian['judul_ujian'] ?? 'Ujian Sertifikasi') ?>" class="input input-bordered" required />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Durasi (menit)</span>
                    </label>
                    <input type="number" name="durasi_menit" value="<?= $ujian['durasi_menit'] ?? 60 ?>" class="input input-bordered" min="1" required />
                    <label class="label">
                        <span class="label-text-alt">Waktu ujian</span>
                    </label>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Nilai Minimal Lulus</span>
                    </label>
                    <input type="number" name="nilai_minimal_lulus" value="<?= $ujian['nilai_minimal_lulus'] ?? 70 ?>" class="input input-bordered" min="0" max="100" required />
                    <label class="label">
                        <span class="label-text-alt">0-100</span>
                    </label>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Min. Progress (%)</span>
                    </label>
                    <input type="number" name="minimal_progress_persen" value="<?= $ujian['minimal_progress_persen'] ?? 100 ?>" class="input input-bordered" min="0" max="100" required />
                    <label class="label">
                        <span class="label-text-alt text-warning">Progress materi harus selesai</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <a href="<?= base_url('admin/materi/detail/' . $ujian['id_produk']) ?>" class="btn btn-ghost">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="ph ph-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>