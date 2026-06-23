<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="breadcrumbs mb-6">
    <a href="<?= base_url('admin/materi') ?>" class="text-primary">Manajemen Kurikulum</a>
    <span class="text-base-content/30">/</span>
    <span>Edit Kelas</span>
</div>

<h1 class="text-2xl font-bold mb-6">Edit Kelas</h1>

<div class="card bg-base-100 shadow border border-base-200">
    <div class="card-body">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error mb-4">
                <span><?= session()->getFlashdata('error') ?></span>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success mb-4">
                <span><?= session()->getFlashdata('success') ?></span>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('admin/materi/update/' . $produk['id_produk']) ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Left Column -->
                <div class="space-y-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Nama Kelas</span>
                        </label>
                        <input type="text" name="judul" value="<?= esc($produk['judul'] ?? '') ?>" class="input input-bordered" required />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Kategori</span>
                        </label>
                        <select name="id_kategori" class="select select-bordered" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php if (!empty($kategoriProduk)): ?>
                                <?php foreach ($kategoriProduk as $kat): ?>
                                    <option value="<?= $kat['id_kategori'] ?>" <?= ($produk['id_kategori'] ?? '') == $kat['id_kategori'] ? 'selected' : '' ?>>
                                        <?= esc($kat['nama_kategori']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="1">Umum</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Pemateri / Guru</span>
                        </label>
                        <select name="id_admin" class="select select-bordered">
                            <option value="">-- Pilih Pemateri --</option>
                            <?php if (!empty($admins)): ?>
                                <?php foreach ($admins as $admin): ?>
                                    <option value="<?= $admin['id_user'] ?>" <?= ($produk['id_admin'] ?? '') == $admin['id_user'] ? 'selected' : '' ?>>
                                        <?= esc($admin['nama_lengkap']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Harga Awal (Rp)</span>
                        </label>
                        <input type="number" name="harga_awal" value="<?= $produk['harga_awal'] ?? 0 ?>" class="input input-bordered" min="0" />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Harga Promo (Rp)</span>
                        </label>
                        <input type="number" name="harga_promo" value="<?= $produk['harga_promo'] ?? 0 ?>" class="input input-bordered" min="0" />
                        <?php if (($produk['harga_promo'] ?? 0) == 0): ?>
                            <label class="label">
                                <span class="label-text-alt text-success">Kelas ini GRATIS</span>
                            </label>
                        <?php endif; ?>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Gambar Kelas</span>
                        </label>
                        <?php if (!empty($produk['gambar'])): ?>
                            <div class="avatar mb-2">
                                <div class="w-24 rounded">
                                    <img src="<?= base_url($produk['gambar']) ?>" alt="Current" />
                                </div>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="gambar" class="file-input file-input-bordered w-full" accept="image/*" />
                        <label class="label">
                            <span class="label-text-alt">Kosongkan jika tidak ingin mengubah gambar</span>
                        </label>
                    </div>
                </div>

            </div>

            <div class="form-control mt-6">
                <label class="label">
                    <span class="label-text font-semibold">Deskripsi</span>
                </label>
                <textarea name="deskripsi" class="textarea textarea-bordered" rows="4"><?= esc($produk['deskripsi'] ?? '') ?></textarea>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <a href="<?= base_url('admin/materi') ?>" class="btn btn-ghost">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="ph ph-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>