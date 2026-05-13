<div class="navbar bg-base-100 border-b border-base-200 sticky top-0 z-30">
    <div class="flex-none lg:hidden">
        <label for="admin-drawer" class="btn btn-square btn-ghost">
            <i class="ph ph-list text-2xl"></i>
        </label>
    </div>
    <div class="flex-1 px-2 mx-2">
        <span class="text-xl font-bold lg:hidden">ZevedU</span>
    </div>
    <div class="flex-none flex items-center gap-2 pr-4">
        <!-- Notifications -->
        <div class="dropdown dropdown-end">
            <button class="btn btn-ghost btn-circle">
                <div class="indicator">
                    <i class="ph ph-bell text-2xl"></i>
                    <span class="badge badge-xs badge-primary indicator-item"></span>
                </div>
            </button>
            <div tabindex="0" class="mt-3 z-[1] card card-compact dropdown-content w-52 bg-base-100 shadow">
                <div class="card-body">
                    <span class="font-bold text-lg">8 Notifications</span>
                    <span class="text-info">Check your alerts</span>
                    <div class="card-actions">
                        <button class="btn btn-primary btn-block">View all</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- User Profile -->
        <div class="dropdown dropdown-end">
            <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                <div class="w-10 rounded-full">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=0369A1&color=fff" alt="Profile" />
                </div>
            </label>
            <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                <li><a><i class="ph ph-user"></i> Profile</a></li>
                <li><a><i class="ph ph-gear"></i> Settings</a></li>
                <li><a class="text-error"><i class="ph ph-sign-out"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</div>
