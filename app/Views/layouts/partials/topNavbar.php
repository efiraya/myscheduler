<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
    <!-- ! Not required for layout-without-menu -->
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0  d-xl-none ">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
        <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

        <ul class="navbar-nav flex-row align-items-center ms-auto">       

            <!-- User -->
            <li class="nav-item">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    Hi <?= session()->get('full_name') ? esc(session()->get('full_name')) : 'Student Name'; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" style="min-width: auto;padding:5px;">
                    <li style="padding:5px;"><a href="/user/edit/<?= session('user_id')?>" class="btn btn-outline-primary btn-sm">Profile</a></li>
                    <li style="padding:5px;">
                        <form action="<?= route_to('logout') ?>" method="post" style="display: inline;">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
                        </form>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>

    <!-- Search Small Screens -->
    <div class="navbar-search-wrapper search-input-wrapper  d-none">
        <input type="text" class="form-control search-input container-xxl border-0" placeholder="Search..." aria-label="Search...">
        <i class="bx bx-x bx-sm search-toggler cursor-pointer"></i>
    </div>
    <!--  Brand demo (display only for navbar-full and hide on below xl) -->
</nav>