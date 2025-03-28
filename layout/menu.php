<?php $page = $_GET['page'];


?>
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="index.php?page=Dashboard" class="logo logo-dark">
            <span class="logo-sm">
                <img src="assets/images/logo_MAA.png" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="assets/images/logo_MAAA.png" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="index.php?page=Dashboard" class="logo logo-light">
            <span class="logo-sm">
                <img src="assets/images/logo_MAA.png" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="assets/images/logo_MAAA.png" alt="" height="39">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>



    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>

            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= $page == 'Dashboard' ? 'active' : '' ?>" href="index.php?page=Dashboard">
                        <i class="ri-dashboard-2-line"></i> <span><?= $lang['dashboard'] ?? 'Dashboard' ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= $page == 'CashAdvance' ? 'active' : '' ?>" href="index.php?page=CashAdvance">
                        <i class="ri-money-dollar-circle-line"></i> <span><?= $lang['cash_advance'] ?? 'Cash Advance' ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link <?= $page == 'PettyCashOut' ? 'active' : '' ?>" href="index.php?page=PettyCashOut">
                        <i class="ri-wallet-3-line"></i> <span><?= $lang['petty_cash_out'] ?? 'Petty Cash Out' ?></span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link <?= $page == 'PettyCashIn' ? 'active' : '' ?>" href="index.php?page=PettyCashIn">
                        <i class="ri-wallet-3-line"></i> <span><?= $lang['petty_cash_in'] ?? 'Petty Cash In' ?></span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link <?= $page == 'CashOutExport' ? 'active' : '' ?>" href="index.php?page=CashOutExport">
                        <i class="ri-wallet-3-line"></i> <span><?= $lang['cash_out_export'] ?? 'Print Cash Out' ?></span>
                    </a>
                </li>


                <li class="nav-item">
                    <?php $pages = ['Karyawan']; ?>
                    <a class="nav-link menu-link <?= in_array($page, $pages) ? 'active' : '' ?>" href="#masterData" data-bs-toggle="collapse">
                        <i class="ri-database-2-line"></i> <span><?= $lang['master_data'] ?? 'Master Data' ?></span>
                    </a>
                    <div class="collapse <?= in_array($page, $pages) ? 'show' : '' ?> menu-dropdown" id="masterData">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="index.php?page=Karyawan" class="nav-link <?= $page == 'Karyawan' ? 'active' : '' ?>">
                                    <?= $lang['employee'] ?? 'employee' ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>



                <li class="nav-item">
                    <?php $userRolesPages = ['UserRoles', 'LokasiRoles', 'LokasiUserRoles']; ?>
                    <a class="nav-link menu-link <?= in_array($page, $userRolesPages) ? 'active' : '' ?>" href="#roles" data-bs-toggle="collapse">
                        <i class="ri-team-line"></i> <span><?= $lang['user_roles'] ?? 'User Roles' ?></span>
                    </a>
                    <div class="collapse <?= in_array($page, $userRolesPages) ? 'show' : '' ?> menu-dropdown" id="roles">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="index.php?page=UserRoles" class="nav-link <?= $page == 'UserRoles' ? 'active' : '' ?>">
                                    <i class="ri-user-settings-line"></i> <?= $lang['user_roles'] ?? 'User Roles' ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <?php if ($is_admin): ?>
                <?php endif; ?>

            </ul>


        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>