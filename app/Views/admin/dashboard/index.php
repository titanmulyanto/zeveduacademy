<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="stats shadow bg-base-100 border border-base-200">
        <div class="stat">
            <div class="stat-figure text-primary">
                <i class="ph ph-users text-3xl"></i>
            </div>
            <div class="stat-title text-xs font-semibold uppercase tracking-wider">Total Students</div>
            <div class="stat-value text-primary">2,560</div>
            <div class="stat-desc">Jan 1st - Feb 1st</div>
        </div>
    </div>
    
    <div class="stats shadow bg-base-100 border border-base-200">
        <div class="stat">
            <div class="stat-figure text-secondary">
                <i class="ph ph-hand-coins text-3xl"></i>
            </div>
            <div class="stat-title text-xs font-semibold uppercase tracking-wider">Total Transactions</div>
            <div class="stat-value text-secondary">120M</div>
            <div class="stat-desc text-secondary">↗︎ 40 (14%)</div>
        </div>
    </div>
    
    <div class="stats shadow bg-base-100 border border-base-200">
        <div class="stat">
            <div class="stat-figure text-accent">
                <i class="ph ph-certificate text-3xl"></i>
            </div>
            <div class="stat-title text-xs font-semibold uppercase tracking-wider">Certificates Issued</div>
            <div class="stat-value text-accent">1,200</div>
            <div class="stat-desc">90% completion rate</div>
        </div>
    </div>

    <div class="stats shadow bg-base-100 border border-base-200">
        <div class="stat">
            <div class="stat-figure text-neutral">
                <i class="ph ph-hard-drive text-3xl"></i>
            </div>
            <div class="stat-title text-xs font-semibold uppercase tracking-wider">Storage Usage</div>
            <div class="stat-value text-sm"><?= $storage['used'] ?> / <?= $storage['limit'] ?></div>
            <div class="stat-desc">
                <progress class="progress progress-primary w-full" value="<?= $storage['percent'] ?>" max="100"></progress>
                <div class="text-[10px] mt-1 text-right"><?= $storage['percent'] ?>% used</div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Activity -->
    <div class="card bg-base-100 shadow border border-base-200">
        <div class="card-body">
            <h2 class="card-title text-lg mb-4">Recent Activity</h2>
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Action</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar">
                                        <div class="mask mask-squircle w-10 h-10">
                                            <img src="https://ui-avatars.com/api/?name=John+Doe" />
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold">John Doe</div>
                                        <div class="text-xs opacity-50">Student</div>
                                    </div>
                                </div>
                            </td>
                            <td>Bought "UI/UX Masterclass"</td>
                            <td>2 mins ago</td>
                        </tr>
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar">
                                        <div class="mask mask-squircle w-10 h-10">
                                            <img src="https://ui-avatars.com/api/?name=Jane+Smith" />
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold">Jane Smith</div>
                                        <div class="text-xs opacity-50">Student</div>
                                    </div>
                                </div>
                            </td>
                            <td>Completed Certification Exam</td>
                            <td>15 mins ago</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card bg-primary text-primary-content shadow-xl">
        <div class="card-body">
            <h2 class="card-title">Quick Actions</h2>
            <p>Ready to manage your academy today?</p>
            <div class="card-actions mt-4">
                <button class="btn btn-secondary">Add New Class</button>
                <button class="btn btn-ghost border-white">User Management</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
