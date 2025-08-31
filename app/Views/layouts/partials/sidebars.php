<!-- ! Hide app brand if navbar-full -->
<div class="app-brand demo">
    <a href="/dashboard" class="app-brand-link">
        <span class="app-brand-logo demo">
            <img src="<?= base_url('images/activities.png') ?>" alt="" width="40px;">
        </span>
        <span class="app-brand-text demo menu-text fw-bold ms-2" style="text-transform: none;font-size: 1.3rem !important;">
            <span>MY Scheduler</span>
        </span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
        <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
</div>

<div class="menu-inner-shadow"></div>
<ul class="menu-inner py-1">
    <li class="menu-item <?= ($_SERVER['REQUEST_URI'] == '/dashboard') ? 'active' : '' ?>">
        <a href="/dashboard" class="menu-link">
            <i class="menu-icon tf-icons bx bx-home-circle"></i>
            <div class="text-truncate">Dashboards</div>
        </a>
    </li>
    <li class="menu-item <?= ($_SERVER['REQUEST_URI'] == '/activity/input') ? 'active' : '' ?>">
        <a href="/activity/input" class="menu-link">
            <i class="menu-icon tf-icons bx bx-book"></i>
            <div class="text-truncate">Activity Input</div>
        </a>
    </li>
    <li class="menu-item <?= ($_SERVER['REQUEST_URI'] == '/activity/list') ? 'active' : '' ?>">
        <a href="/activity/list" class="menu-link">
            <i class="menu-icon tf-icons bx bx-book-open"></i>
            <div class="text-truncate">Activity List</div>
        </a>
    </li>
    <li class="menu-item <?= ($_SERVER['REQUEST_URI'] == '/calculation/result') ? 'active' : '' ?>">
        <a href="/calculation/result" class="menu-link">
            <i class="menu-icon tf-icons bx bx-box"></i>
            <div class="text-truncate">Calculation Result</div>
        </a>
    </li>
    <li class="menu-item <?= ($_SERVER['REQUEST_URI'] == '/activity/history') ? 'active' : '' ?>">
        <a href="/activity/history" class="menu-link">
            <i class="menu-icon tf-icons bx bx-calendar"></i>
            <div class="text-truncate">Activity History</div>
        </a>
    </li>
    <li class="menu-item <?= ($_SERVER['REQUEST_URI'] == '/help') ? 'active' : '' ?>">
        <a href="/help" class="menu-link">
            <i class="menu-icon tf-icons bx bx-help-circle"></i>
            <div class="text-truncate">Help</div>
        </a>
    </li>
</ul>