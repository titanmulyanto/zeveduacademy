<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
    <h1 class="text-2xl font-bold">Manajemen User</h1>
    
    <div class="flex gap-2">
        <select class="select select-bordered w-full max-w-xs" onchange="window.location.href='?role='+this.value">
            <option value="">Semua Peran</option>
            <option value="student" <?= $roleFilter == 'student' ? 'selected' : '' ?>>Student</option>
            <option value="admin" <?= $roleFilter == 'admin' ? 'selected' : '' ?>>Admin</option>
            <option value="super_admin" <?= $roleFilter == 'super_admin' ? 'selected' : '' ?>>Super Admin</option>
        </select>
        <button class="btn btn-primary" onclick="add_user_modal.showModal()">
            <i class="ph ph-plus"></i> Tambah User
        </button>
    </div>
</div>

<div class="card bg-base-100 shadow border border-base-200">
    <div class="card-body p-0">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Peran</th>
                        <th>Instansi</th>
                        <th>WhatsApp</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-8 opacity-50">Tidak ada data user ditemukan.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($users as $user): ?>
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar">
                                        <div class="mask mask-squircle w-10 h-10">
                                            <img src="<?= $user['foto_profil'] ?? 'https://ui-avatars.com/api/?name='.urlencode($user['nama_lengkap']) ?>" />
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold"><?= $user['nama_lengkap'] ?></div>
                                        <div class="text-xs opacity-50"><?= $user['email'] ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if($user['role'] == 'super_admin'): ?>
                                    <span class="badge badge-error badge-sm text-white">Super Admin</span>
                                <?php elseif($user['role'] == 'admin'): ?>
                                    <span class="badge badge-warning badge-sm">Admin</span>
                                <?php else: ?>
                                    <span class="badge badge-info badge-sm">Student</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $user['instansi'] ?? '-' ?></td>
                            <td><?= $user['no_whatsapp'] ?? '-' ?></td>
                            <td>
                                <div class="flex justify-center gap-2">
                                    <button class="btn btn-square btn-ghost btn-sm text-primary">
                                        <i class="ph ph-note-pencil text-xl"></i>
                                    </button>
                                    <button class="btn btn-square btn-ghost btn-sm text-error">
                                        <i class="ph ph-trash text-xl"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah User -->
<dialog id="add_user_modal" class="modal">
    <div class="modal-box w-11/12 max-w-2xl">
        <h3 class="font-bold text-lg mb-4">Tambah User Baru</h3>
        <form action="#" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="form-control">
                <label class="label"><span class="label-text">Nama Lengkap</span></label>
                <input type="text" placeholder="Masukkan nama" class="input input-bordered" required />
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text">Email</span></label>
                <input type="email" placeholder="email@example.com" class="input input-bordered" required />
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text">Password</span></label>
                <input type="password" placeholder="********" class="input input-bordered" required />
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text">Role</span></label>
                <select class="select select-bordered">
                    <option value="student">Student</option>
                    <option value="admin">Admin (Pemateri)</option>
                    <option value="super_admin">Super Admin</option>
                </select>
            </div>
            <div class="form-control col-span-full">
                <label class="label"><span class="label-text">Alamat</span></label>
                <textarea class="textarea textarea-bordered h-24" placeholder="Alamat lengkap"></textarea>
            </div>
            
            <div class="modal-action col-span-full">
                <form method="dialog">
                    <button class="btn">Batal</button>
                </form>
                <button type="submit" class="btn btn-primary">Simpan User</button>
            </div>
        </form>
    </div>
</dialog>

<?= $this->endSection() ?>
