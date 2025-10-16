<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Dashboard - Analytics | Sneat - Bootstrap 5 HTML Admin Template - Pro</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= $tplPath ?>/assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="<?= $tplPath ?>/assets/vendor/fonts/boxicons.css" />
    <!-- <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> -->

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?= $tplPath ?>/assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?= $tplPath ?>/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?= $tplPath ?>/assets/css/demo.css" />
    <link rel="stylesheet" href="<?= $tplPath ?>/assets/css/app-logistics-dashboard.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?= $tplPath ?>/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <link rel="stylesheet" href="<?= $tplPath ?>/assets/vendor/libs/apex-charts/apex-charts.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="<?= $tplPath ?>/assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="<?= $tplPath ?>/assets/js/config.js"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="index.html" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <svg
                                width="25"
                                viewBox="0 0 25 42"
                                version="1.1"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink">
                                <defs>
                                    <path
                                        d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z"
                                        id="path-1"></path>
                                    <path
                                        d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z"
                                        id="path-3"></path>
                                    <path
                                        d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z"
                                        id="path-4"></path>
                                    <path
                                        d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z"
                                        id="path-5"></path>
                                </defs>
                                <g id="g-app-brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                                        <g id="Icon" transform="translate(27.000000, 15.000000)">
                                            <g id="Mask" transform="translate(0.000000, 8.000000)">
                                                <mask id="mask-2" fill="white">
                                                    <use xlink:href="#path-1"></use>
                                                </mask>
                                                <use fill="#696cff" xlink:href="#path-1"></use>
                                                <g id="Path-3" mask="url(#mask-2)">
                                                    <use fill="#696cff" xlink:href="#path-3"></use>
                                                    <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-3"></use>
                                                </g>
                                                <g id="Path-4" mask="url(#mask-2)">
                                                    <use fill="#696cff" xlink:href="#path-4"></use>
                                                    <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-4"></use>
                                                </g>
                                            </g>
                                            <g
                                                id="Triangle"
                                                transform="translate(19.000000, 11.000000) rotate(-300.000000) translate(-19.000000, -11.000000) ">
                                                <use fill="#696cff" xlink:href="#path-5"></use>
                                                <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-5"></use>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </span>
                        <span class="app-brand-text demo menu-text fw-bolder ms-2">texto</span>
                    </a>

                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left bx-sm align-middle"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    <!-- Dashboard -->
                    <li class="menu-item active">
                        <a href="javascript:void(0);" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div data-i18n="Analytics">Dashboard</div>
                        </a>
                    </li>

                    <!-- Layouts -->
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-layout"></i>
                            <div data-i18n="Layouts">Layouts</div>
                        </a>

                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Without menu">Without menu</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Without navbar">Without navbar</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Container">Container</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Fluid">Fluid</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Blank">Blank</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">Pages</span>
                    </li>
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-dock-top"></i>
                            <div data-i18n="Account Settings">Account Settings</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Account">Account</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Notifications">Notifications</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Connections">Connections</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-lock-open-alt"></i>
                            <div data-i18n="Authentications">Authentications</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link" target="">
                                    <div data-i18n="Basic">Login</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link" target="">
                                    <div data-i18n="Basic">Register</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link" target="">
                                    <div data-i18n="Basic">Forgot Password</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-cube-alt"></i>
                            <div data-i18n="Misc">Misc</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Error">Error</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Under Maintenance">Under Maintenance</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- Components -->
                    <li class="menu-header small text-uppercase"><span class="menu-header-text">Components</span></li>
                    <!-- Cards -->
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-collection"></i>
                            <div data-i18n="Basic">Cards</div>
                        </a>
                    </li>
                    <!-- User interface -->
                    <li class="menu-item">
                        <a href="javascript:void(0)" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-box"></i>
                            <div data-i18n="User interface">User interface</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Accordion">Accordion</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Alerts">Alerts</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Badges">Badges</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Buttons">Buttons</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Carousel">Carousel</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Collapse">Collapse</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Dropdowns">Dropdowns</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Footer">Footer</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="List Groups">List groups</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Modals">Modals</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Navbar">Navbar</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Offcanvas">Offcanvas</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Pagination &amp; Breadcrumbs">Pagination &amp; Breadcrumbs</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Progress">Progress</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Spinners">Spinners</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Tabs &amp; Pills">Tabs &amp; Pills</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Toasts">Toasts</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Tooltips & Popovers">Tooltips &amp; popovers</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Typography">Typography</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Extended components -->
                    <li class="menu-item">
                        <a href="javascript:void(0)" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-copy"></i>
                            <div data-i18n="Extended UI">Extended UI</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Perfect Scrollbar">Perfect scrollbar</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Text Divider">Text Divider</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-crown"></i>
                            <div data-i18n="Boxicons">Boxicons</div>
                        </a>
                    </li>

                    <!-- Forms & Tables -->
                    <li class="menu-header small text-uppercase"><span class="menu-header-text">Forms &amp; Tables</span></li>
                    <!-- Forms -->
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-detail"></i>
                            <div data-i18n="Form Elements">Form Elements</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Basic Inputs">Basic Inputs</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Input groups">Input groups</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-detail"></i>
                            <div data-i18n="Form Layouts">Form Layouts</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Vertical Form">Vertical Form</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link">
                                    <div data-i18n="Horizontal Form">Horizontal Form</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- Tables -->
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-table"></i>
                            <div data-i18n="Tables">Tables</div>
                        </a>
                    </li>
                    <!-- Misc -->
                    <li class="menu-header small text-uppercase"><span class="menu-header-text">Misc</span></li>
                    <li class="menu-item">
                        <a
                            href="javascript:void(0);"
                            target=""
                            class="menu-link">
                            <i class="menu-icon tf-icons bx bx-support"></i>
                            <div data-i18n="Support">Support</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a
                            href="javascript:void(0);"
                            target=""
                            class="menu-link">
                            <i class="menu-icon tf-icons bx bx-file"></i>
                            <div data-i18n="Documentation">Documentation</div>
                        </a>
                    </li>
                </ul>
            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <nav
                    class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <!-- Search -->
                        <div class="navbar-nav align-items-center">
                            <div class="nav-item d-flex align-items-center">
                                <i class="bx bx-search fs-4 lh-0"></i>
                                <input
                                    type="text"
                                    class="form-control border-0 shadow-none"
                                    placeholder="Search..."
                                    aria-label="Search..." />
                            </div>
                        </div>
                        <!-- /Search -->

                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- Place this tag where you want the button to render. -->
                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="<?= $tplPath ?>/assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="<?= $tplPath ?>/assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="fw-semibold d-block">John Doe</span>
                                                    <small class="text-muted">Admin</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="bx bx-user me-2"></i>
                                            <span class="align-middle">My Profile</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="bx bx-cog me-2"></i>
                                            <span class="align-middle">Settings</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <span class="d-flex align-items-center align-middle">
                                                <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
                                                <span class="flex-grow-1 align-middle">Billing</span>
                                                <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="bx bx-power-off me-2"></i>
                                            <span class="align-middle">Log Out</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->

                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="card h-100">
                            <div class="card-body row g-4 p-0">
                                <div class="col-md-6 card-separator">
                                    <div class="p-6">
                                        <div class="card-title d-flex align-items-start justify-content-between">
                                            <h5 class="mb-0">New Visitors</h5>
                                            <small>Last Week</small>
                                        </div>
                                        <div class="d-flex justify-content-between" style="position: relative;">
                                            <div class="mt-auto">
                                                <h3 class="mb-1">23%</h3>
                                                <small class="text-danger text-nowrap fw-medium"><i class="bx bx-down-arrow-alt"></i> -13.24%</small>
                                            </div>
                                            <div id="visitorsChart" style="min-height: 120px;">
                                                <div id="apexcharts8zkjzuc8" class="apexcharts-canvas apexcharts8zkjzuc8 apexcharts-theme-light" style="width: 200px; height: 120px;"><svg id="SvgjsSvg2216" width="200" height="120" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;">
                                                        <g id="SvgjsG2218" class="apexcharts-inner apexcharts-graphical" transform="translate(22, 5)">
                                                            <defs id="SvgjsDefs2217">
                                                                <linearGradient id="SvgjsLinearGradient2221" x1="0" y1="0" x2="0" y2="1">
                                                                    <stop id="SvgjsStop2222" stop-opacity="0.4" stop-color="rgba(216,227,240,0.4)" offset="0"></stop>
                                                                    <stop id="SvgjsStop2223" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop>
                                                                    <stop id="SvgjsStop2224" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop>
                                                                </linearGradient>
                                                                <clipPath id="gridRectMask8zkjzuc8">
                                                                    <rect id="SvgjsRect2226" width="172" height="89.348" x="-2" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                                                </clipPath>
                                                                <clipPath id="forecastMask8zkjzuc8"></clipPath>
                                                                <clipPath id="nonForecastMask8zkjzuc8"></clipPath>
                                                                <clipPath id="gridRectMarkerMask8zkjzuc8">
                                                                    <rect id="SvgjsRect2227" width="172" height="93.348" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                                                </clipPath>
                                                            </defs>
                                                            <rect id="SvgjsRect2225" width="14.4" height="89.348" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke-dasharray="3" fill="url(#SvgjsLinearGradient2221)" class="apexcharts-xcrosshairs" y2="89.348" filter="none" fill-opacity="0.9"></rect>
                                                            <g id="SvgjsG2246" class="apexcharts-xaxis" transform="translate(0, 0)">
                                                                <g id="SvgjsG2247" class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"><text id="SvgjsText2249" font-family="Helvetica, Arial, sans-serif" x="12" y="118.348" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a7acb2" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                                        <tspan id="SvgjsTspan2250">M</tspan>
                                                                        <title>M</title>
                                                                    </text><text id="SvgjsText2252" font-family="Helvetica, Arial, sans-serif" x="36" y="118.348" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a7acb2" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                                        <tspan id="SvgjsTspan2253">T</tspan>
                                                                        <title>T</title>
                                                                    </text><text id="SvgjsText2255" font-family="Helvetica, Arial, sans-serif" x="60" y="118.348" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a7acb2" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                                        <tspan id="SvgjsTspan2256">W</tspan>
                                                                        <title>W</title>
                                                                    </text><text id="SvgjsText2258" font-family="Helvetica, Arial, sans-serif" x="84" y="118.348" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a7acb2" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                                        <tspan id="SvgjsTspan2259">T</tspan>
                                                                        <title>T</title>
                                                                    </text><text id="SvgjsText2261" font-family="Helvetica, Arial, sans-serif" x="108" y="118.348" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a7acb2" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                                        <tspan id="SvgjsTspan2262">F</tspan>
                                                                        <title>F</title>
                                                                    </text><text id="SvgjsText2264" font-family="Helvetica, Arial, sans-serif" x="132" y="118.348" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a7acb2" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                                        <tspan id="SvgjsTspan2265">S</tspan>
                                                                        <title>S</title>
                                                                    </text><text id="SvgjsText2267" font-family="Helvetica, Arial, sans-serif" x="156" y="118.348" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a7acb2" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                                        <tspan id="SvgjsTspan2268">S</tspan>
                                                                        <title>S</title>
                                                                    </text></g>
                                                            </g>
                                                            <g id="SvgjsG2271" class="apexcharts-grid">
                                                                <g id="SvgjsG2272" class="apexcharts-gridlines-horizontal" style="display: none;">
                                                                    <line id="SvgjsLine2274" x1="0" y1="0" x2="168" y2="0" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                                    <line id="SvgjsLine2275" x1="0" y1="17.8696" x2="168" y2="17.8696" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                                    <line id="SvgjsLine2276" x1="0" y1="35.7392" x2="168" y2="35.7392" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                                    <line id="SvgjsLine2277" x1="0" y1="53.608799999999995" x2="168" y2="53.608799999999995" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                                    <line id="SvgjsLine2278" x1="0" y1="71.4784" x2="168" y2="71.4784" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                                    <line id="SvgjsLine2279" x1="0" y1="89.34799999999998" x2="168" y2="89.34799999999998" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                                </g>
                                                                <g id="SvgjsG2273" class="apexcharts-gridlines-vertical" style="display: none;"></g>
                                                                <line id="SvgjsLine2281" x1="0" y1="89.348" x2="168" y2="89.348" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line>
                                                                <line id="SvgjsLine2280" x1="0" y1="1" x2="0" y2="89.348" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line>
                                                            </g>
                                                            <g id="SvgjsG2228" class="apexcharts-bar-series apexcharts-plot-series">
                                                                <g id="SvgjsG2229" class="apexcharts-series" rel="1" seriesName="seriesx1" data:realIndex="0">
                                                                    <path id="SvgjsPath2233" d="M 4.8 80.348L 4.8 62.6088Q 4.8 53.6088 13.8 53.6088L 10.2 53.6088Q 19.2 53.6088 19.2 62.6088L 19.2 62.6088L 19.2 80.348Q 19.2 89.348 10.2 89.348L 13.8 89.348Q 4.8 89.348 4.8 80.348z" fill="#696cff29" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask8zkjzuc8)" pathTo="M 4.8 80.348L 4.8 62.6088Q 4.8 53.6088 13.8 53.6088L 10.2 53.6088Q 19.2 53.6088 19.2 62.6088L 19.2 62.6088L 19.2 80.348Q 19.2 89.348 10.2 89.348L 13.8 89.348Q 4.8 89.348 4.8 80.348z" pathFrom="M 4.8 80.348L 4.8 80.348L 19.2 80.348L 19.2 80.348L 19.2 80.348L 19.2 80.348L 19.2 80.348L 4.8 80.348" cy="53.6088" cx="28.8" j="0" val="40" barHeight="35.7392" barWidth="14.4"></path>
                                                                    <path id="SvgjsPath2235" d="M 28.8 80.348L 28.8 13.467399999999998Q 28.8 4.467399999999998 37.8 4.467399999999998L 34.2 4.467399999999998Q 43.2 4.467399999999998 43.2 13.467399999999998L 43.2 13.467399999999998L 43.2 80.348Q 43.2 89.348 34.2 89.348L 37.8 89.348Q 28.8 89.348 28.8 80.348z" fill="#696cff29" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask8zkjzuc8)" pathTo="M 28.8 80.348L 28.8 13.467399999999998Q 28.8 4.467399999999998 37.8 4.467399999999998L 34.2 4.467399999999998Q 43.2 4.467399999999998 43.2 13.467399999999998L 43.2 13.467399999999998L 43.2 80.348Q 43.2 89.348 34.2 89.348L 37.8 89.348Q 28.8 89.348 28.8 80.348z" pathFrom="M 28.8 80.348L 28.8 80.348L 43.2 80.348L 43.2 80.348L 43.2 80.348L 43.2 80.348L 43.2 80.348L 28.8 80.348" cy="4.467399999999998" cx="52.8" j="1" val="95" barHeight="84.8806" barWidth="14.4"></path>
                                                                    <path id="SvgjsPath2237" d="M 52.8 80.348L 52.8 44.739200000000004Q 52.8 35.739200000000004 61.8 35.739200000000004L 58.2 35.739200000000004Q 67.2 35.739200000000004 67.2 44.739200000000004L 67.2 44.739200000000004L 67.2 80.348Q 67.2 89.348 58.2 89.348L 61.8 89.348Q 52.8 89.348 52.8 80.348z" fill="#696cff29" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask8zkjzuc8)" pathTo="M 52.8 80.348L 52.8 44.739200000000004Q 52.8 35.739200000000004 61.8 35.739200000000004L 58.2 35.739200000000004Q 67.2 35.739200000000004 67.2 44.739200000000004L 67.2 44.739200000000004L 67.2 80.348Q 67.2 89.348 58.2 89.348L 61.8 89.348Q 52.8 89.348 52.8 80.348z" pathFrom="M 52.8 80.348L 52.8 80.348L 67.2 80.348L 67.2 80.348L 67.2 80.348L 67.2 80.348L 67.2 80.348L 52.8 80.348" cy="35.739200000000004" cx="76.8" j="2" val="60" barHeight="53.608799999999995" barWidth="14.4"></path>
                                                                    <path id="SvgjsPath2239" d="M 76.8 80.348L 76.8 58.141400000000004Q 76.8 49.141400000000004 85.8 49.141400000000004L 82.2 49.141400000000004Q 91.2 49.141400000000004 91.2 58.141400000000004L 91.2 58.141400000000004L 91.2 80.348Q 91.2 89.348 82.2 89.348L 85.8 89.348Q 76.8 89.348 76.8 80.348z" fill="#696cff29" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask8zkjzuc8)" pathTo="M 76.8 80.348L 76.8 58.141400000000004Q 76.8 49.141400000000004 85.8 49.141400000000004L 82.2 49.141400000000004Q 91.2 49.141400000000004 91.2 58.141400000000004L 91.2 58.141400000000004L 91.2 80.348Q 91.2 89.348 82.2 89.348L 85.8 89.348Q 76.8 89.348 76.8 80.348z" pathFrom="M 76.8 80.348L 76.8 80.348L 91.2 80.348L 91.2 80.348L 91.2 80.348L 91.2 80.348L 91.2 80.348L 76.8 80.348" cy="49.141400000000004" cx="100.8" j="3" val="45" barHeight="40.206599999999995" barWidth="14.4"></path>
                                                                    <path id="SvgjsPath2241" d="M 100.8 80.348L 100.8 17.93480000000001Q 100.8 8.93480000000001 109.8 8.93480000000001L 106.2 8.93480000000001Q 115.2 8.93480000000001 115.2 17.93480000000001L 115.2 17.93480000000001L 115.2 80.348Q 115.2 89.348 106.2 89.348L 109.8 89.348Q 100.8 89.348 100.8 80.348z" fill="#696cff29" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask8zkjzuc8)" pathTo="M 100.8 80.348L 100.8 17.93480000000001Q 100.8 8.93480000000001 109.8 8.93480000000001L 106.2 8.93480000000001Q 115.2 8.93480000000001 115.2 17.93480000000001L 115.2 17.93480000000001L 115.2 80.348Q 115.2 89.348 106.2 89.348L 109.8 89.348Q 100.8 89.348 100.8 80.348z" pathFrom="M 100.8 80.348L 100.8 80.348L 115.2 80.348L 115.2 80.348L 115.2 80.348L 115.2 80.348L 115.2 80.348L 100.8 80.348" cy="8.93480000000001" cx="124.8" j="4" val="90" barHeight="80.41319999999999" barWidth="14.4"></path>
                                                                    <path id="SvgjsPath2243" d="M 124.8 80.348L 124.8 53.674Q 124.8 44.674 133.8 44.674L 130.2 44.674Q 139.2 44.674 139.2 53.674L 139.2 53.674L 139.2 80.348Q 139.2 89.348 130.2 89.348L 133.8 89.348Q 124.8 89.348 124.8 80.348z" fill="rgba(105,108,255,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask8zkjzuc8)" pathTo="M 124.8 80.348L 124.8 53.674Q 124.8 44.674 133.8 44.674L 130.2 44.674Q 139.2 44.674 139.2 53.674L 139.2 53.674L 139.2 80.348Q 139.2 89.348 130.2 89.348L 133.8 89.348Q 124.8 89.348 124.8 80.348z" pathFrom="M 124.8 80.348L 124.8 80.348L 139.2 80.348L 139.2 80.348L 139.2 80.348L 139.2 80.348L 139.2 80.348L 124.8 80.348" cy="44.674" cx="148.8" j="5" val="50" barHeight="44.674" barWidth="14.4"></path>
                                                                    <path id="SvgjsPath2245" d="M 148.8 80.348L 148.8 31.337000000000003Q 148.8 22.337000000000003 157.8 22.337000000000003L 154.20000000000002 22.337000000000003Q 163.20000000000002 22.337000000000003 163.20000000000002 31.337000000000003L 163.20000000000002 31.337000000000003L 163.20000000000002 80.348Q 163.20000000000002 89.348 154.20000000000002 89.348L 157.8 89.348Q 148.8 89.348 148.8 80.348z" fill="#696cff29" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask8zkjzuc8)" pathTo="M 148.8 80.348L 148.8 31.337000000000003Q 148.8 22.337000000000003 157.8 22.337000000000003L 154.20000000000002 22.337000000000003Q 163.20000000000002 22.337000000000003 163.20000000000002 31.337000000000003L 163.20000000000002 31.337000000000003L 163.20000000000002 80.348Q 163.20000000000002 89.348 154.20000000000002 89.348L 157.8 89.348Q 148.8 89.348 148.8 80.348z" pathFrom="M 148.8 80.348L 148.8 80.348L 163.20000000000002 80.348L 163.20000000000002 80.348L 163.20000000000002 80.348L 163.20000000000002 80.348L 163.20000000000002 80.348L 148.8 80.348" cy="22.337000000000003" cx="172.8" j="6" val="75" barHeight="67.011" barWidth="14.4"></path>
                                                                    <g id="SvgjsG2231" class="apexcharts-bar-goals-markers" style="pointer-events: none">
                                                                        <g id="SvgjsG2232" className="apexcharts-bar-goals-groups"></g>
                                                                        <g id="SvgjsG2234" className="apexcharts-bar-goals-groups"></g>
                                                                        <g id="SvgjsG2236" className="apexcharts-bar-goals-groups"></g>
                                                                        <g id="SvgjsG2238" className="apexcharts-bar-goals-groups"></g>
                                                                        <g id="SvgjsG2240" className="apexcharts-bar-goals-groups"></g>
                                                                        <g id="SvgjsG2242" className="apexcharts-bar-goals-groups"></g>
                                                                        <g id="SvgjsG2244" className="apexcharts-bar-goals-groups"></g>
                                                                    </g>
                                                                </g>
                                                                <g id="SvgjsG2230" class="apexcharts-datalabels" data:realIndex="0"></g>
                                                            </g>
                                                            <line id="SvgjsLine2282" x1="0" y1="0" x2="168" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line>
                                                            <line id="SvgjsLine2283" x1="0" y1="0" x2="168" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line>
                                                            <g id="SvgjsG2284" class="apexcharts-yaxis-annotations"></g>
                                                            <g id="SvgjsG2285" class="apexcharts-xaxis-annotations"></g>
                                                            <g id="SvgjsG2286" class="apexcharts-point-annotations"></g>
                                                        </g>
                                                        <g id="SvgjsG2269" class="apexcharts-yaxis" rel="0" transform="translate(-8, 0)">
                                                            <g id="SvgjsG2270" class="apexcharts-yaxis-texts-g"></g>
                                                        </g>
                                                        <g id="SvgjsG2219" class="apexcharts-annotations"></g>
                                                    </svg>
                                                    <div class="apexcharts-legend" style="max-height: 60px;"></div>
                                                    <div class="apexcharts-tooltip apexcharts-theme-light">
                                                        <div class="apexcharts-tooltip-title" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div>
                                                        <div class="apexcharts-tooltip-series-group" style="order: 1;"><span class="apexcharts-tooltip-marker" style="background-color: rgba(105, 108, 255, 0.16);"></span>
                                                            <div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                                <div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div>
                                                                <div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div>
                                                                <div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light">
                                                        <div class="apexcharts-yaxistooltip-text"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="resize-triggers">
                                                <div class="expand-trigger">
                                                    <div style="width: 471px; height: 121px;"></div>
                                                </div>
                                                <div class="contract-trigger"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-6">
                                        <div class="card-title d-flex align-items-start justify-content-between">
                                            <h5 class="mb-0">Activity</h5>
                                            <small>Last Week</small>
                                        </div>
                                        <div class="d-flex justify-content-between" style="position: relative;">
                                            <div class="mt-auto">
                                                <h3 class="mb-1">82%</h3>
                                                <small class="text-success text-nowrap fw-medium"><i class="bx bx-up-arrow-alt"></i> 24.8%</small>
                                            </div>
                                            <div id="activityChart" style="min-height: 120px;">
                                                <div id="apexchartsr02d8g2r" class="apexcharts-canvas apexchartsr02d8g2r apexcharts-theme-light" style="width: 220px; height: 120px;"><svg id="SvgjsSvg2288" width="220" height="120" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg apexcharts-zoomable" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;">
                                                        <g id="SvgjsG2290" class="apexcharts-inner apexcharts-graphical" transform="translate(22, 10)">
                                                            <defs id="SvgjsDefs2289">
                                                                <clipPath id="gridRectMaskr02d8g2r">
                                                                    <rect id="SvgjsRect2295" width="194" height="82.348" x="-3" y="-1" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                                                </clipPath>
                                                                <clipPath id="forecastMaskr02d8g2r"></clipPath>
                                                                <clipPath id="nonForecastMaskr02d8g2r"></clipPath>
                                                                <clipPath id="gridRectMarkerMaskr02d8g2r">
                                                                    <rect id="SvgjsRect2296" width="192" height="84.348" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                                                </clipPath>
                                                                <linearGradient id="SvgjsLinearGradient2301" x1="0" y1="0" x2="0" y2="1">
                                                                    <stop id="SvgjsStop2302" stop-opacity="0.8" stop-color="rgba(113,221,55,0.8)" offset="0"></stop>
                                                                    <stop id="SvgjsStop2303" stop-opacity="0.25" stop-color="rgba(227,248,215,0.25)" offset="0.85"></stop>
                                                                    <stop id="SvgjsStop2304" stop-opacity="0.25" stop-color="rgba(227,248,215,0.25)" offset="1"></stop>
                                                                </linearGradient>
                                                            </defs>
                                                            <line id="SvgjsLine2294" x1="0" y1="0" x2="0" y2="80.348" stroke="#b6b6b6" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-xcrosshairs" x="0" y="0" width="1" height="80.348" fill="#b1b9c4" filter="none" fill-opacity="0.9" stroke-width="1"></line>
                                                            <g id="SvgjsG2307" class="apexcharts-xaxis" transform="translate(0, 0)">
                                                                <g id="SvgjsG2308" class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"><text id="SvgjsText2310" font-family="Helvetica, Arial, sans-serif" x="0" y="109.348" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a7acb2" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                                        <tspan id="SvgjsTspan2311">Mo</tspan>
                                                                        <title>Mo</title>
                                                                    </text><text id="SvgjsText2313" font-family="Helvetica, Arial, sans-serif" x="31.333333333333336" y="109.348" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a7acb2" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                                        <tspan id="SvgjsTspan2314">Tu</tspan>
                                                                        <title>Tu</title>
                                                                    </text><text id="SvgjsText2316" font-family="Helvetica, Arial, sans-serif" x="62.666666666666664" y="109.348" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a7acb2" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                                        <tspan id="SvgjsTspan2317">We</tspan>
                                                                        <title>We</title>
                                                                    </text><text id="SvgjsText2319" font-family="Helvetica, Arial, sans-serif" x="93.99999999999999" y="109.348" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a7acb2" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                                        <tspan id="SvgjsTspan2320">Th</tspan>
                                                                        <title>Th</title>
                                                                    </text><text id="SvgjsText2322" font-family="Helvetica, Arial, sans-serif" x="125.33333333333333" y="109.348" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a7acb2" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                                        <tspan id="SvgjsTspan2323">Fr</tspan>
                                                                        <title>Fr</title>
                                                                    </text><text id="SvgjsText2325" font-family="Helvetica, Arial, sans-serif" x="156.66666666666669" y="109.348" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a7acb2" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                                        <tspan id="SvgjsTspan2326">Sa</tspan>
                                                                        <title>Sa</title>
                                                                    </text><text id="SvgjsText2328" font-family="Helvetica, Arial, sans-serif" x="188.00000000000003" y="109.348" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a7acb2" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                                        <tspan id="SvgjsTspan2329">Su</tspan>
                                                                        <title>Su</title>
                                                                    </text></g>
                                                            </g>
                                                            <g id="SvgjsG2332" class="apexcharts-grid">
                                                                <g id="SvgjsG2333" class="apexcharts-gridlines-horizontal" style="display: none;">
                                                                    <line id="SvgjsLine2335" x1="0" y1="0" x2="188" y2="0" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                                    <line id="SvgjsLine2336" x1="0" y1="16.0696" x2="188" y2="16.0696" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                                    <line id="SvgjsLine2337" x1="0" y1="32.1392" x2="188" y2="32.1392" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                                    <line id="SvgjsLine2338" x1="0" y1="48.208800000000004" x2="188" y2="48.208800000000004" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                                    <line id="SvgjsLine2339" x1="0" y1="64.2784" x2="188" y2="64.2784" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                                    <line id="SvgjsLine2340" x1="0" y1="80.34800000000001" x2="188" y2="80.34800000000001" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                                </g>
                                                                <g id="SvgjsG2334" class="apexcharts-gridlines-vertical" style="display: none;"></g>
                                                                <line id="SvgjsLine2342" x1="0" y1="80.348" x2="188" y2="80.348" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line>
                                                                <line id="SvgjsLine2341" x1="0" y1="1" x2="0" y2="80.348" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line>
                                                            </g>
                                                            <g id="SvgjsG2297" class="apexcharts-area-series apexcharts-plot-series">
                                                                <g id="SvgjsG2298" class="apexcharts-series" seriesName="seriesx1" data:longestSeries="true" rel="1" data:realIndex="0">
                                                                    <path id="SvgjsPath2305" d="M 0 80.348L 0 66.2871C 10.966666666666667 66.2871 20.366666666666667 52.2262 31.333333333333336 52.2262C 42.300000000000004 52.2262 51.7 62.26969999999999 62.66666666666667 62.26969999999999C 73.63333333333334 62.26969999999999 83.03333333333335 16.069599999999994 94.00000000000001 16.069599999999994C 104.96666666666668 16.069599999999994 114.36666666666667 72.3132 125.33333333333334 72.3132C 136.3 72.3132 145.70000000000002 26.113100000000003 156.66666666666669 26.113100000000003C 167.63333333333335 26.113100000000003 177.03333333333336 46.2001 188.00000000000003 46.2001C 188.00000000000003 46.2001 188.00000000000003 46.2001 188.00000000000003 80.348M 188.00000000000003 46.2001z" fill="url(#SvgjsLinearGradient2301)" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-area" index="0" clip-path="url(#gridRectMaskr02d8g2r)" pathTo="M 0 80.348L 0 66.2871C 10.966666666666667 66.2871 20.366666666666667 52.2262 31.333333333333336 52.2262C 42.300000000000004 52.2262 51.7 62.26969999999999 62.66666666666667 62.26969999999999C 73.63333333333334 62.26969999999999 83.03333333333335 16.069599999999994 94.00000000000001 16.069599999999994C 104.96666666666668 16.069599999999994 114.36666666666667 72.3132 125.33333333333334 72.3132C 136.3 72.3132 145.70000000000002 26.113100000000003 156.66666666666669 26.113100000000003C 167.63333333333335 26.113100000000003 177.03333333333336 46.2001 188.00000000000003 46.2001C 188.00000000000003 46.2001 188.00000000000003 46.2001 188.00000000000003 80.348M 188.00000000000003 46.2001z" pathFrom="M -1 96.4176L -1 96.4176L 31.333333333333336 96.4176L 62.66666666666667 96.4176L 94.00000000000001 96.4176L 125.33333333333334 96.4176L 156.66666666666669 96.4176L 188.00000000000003 96.4176"></path>
                                                                    <path id="SvgjsPath2306" d="M 0 66.2871C 10.966666666666667 66.2871 20.366666666666667 52.2262 31.333333333333336 52.2262C 42.300000000000004 52.2262 51.7 62.26969999999999 62.66666666666667 62.26969999999999C 73.63333333333334 62.26969999999999 83.03333333333335 16.069599999999994 94.00000000000001 16.069599999999994C 104.96666666666668 16.069599999999994 114.36666666666667 72.3132 125.33333333333334 72.3132C 136.3 72.3132 145.70000000000002 26.113100000000003 156.66666666666669 26.113100000000003C 167.63333333333335 26.113100000000003 177.03333333333336 46.2001 188.00000000000003 46.2001" fill="none" fill-opacity="1" stroke="#71dd37" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-area" index="0" clip-path="url(#gridRectMaskr02d8g2r)" pathTo="M 0 66.2871C 10.966666666666667 66.2871 20.366666666666667 52.2262 31.333333333333336 52.2262C 42.300000000000004 52.2262 51.7 62.26969999999999 62.66666666666667 62.26969999999999C 73.63333333333334 62.26969999999999 83.03333333333335 16.069599999999994 94.00000000000001 16.069599999999994C 104.96666666666668 16.069599999999994 114.36666666666667 72.3132 125.33333333333334 72.3132C 136.3 72.3132 145.70000000000002 26.113100000000003 156.66666666666669 26.113100000000003C 167.63333333333335 26.113100000000003 177.03333333333336 46.2001 188.00000000000003 46.2001" pathFrom="M -1 96.4176L -1 96.4176L 31.333333333333336 96.4176L 62.66666666666667 96.4176L 94.00000000000001 96.4176L 125.33333333333334 96.4176L 156.66666666666669 96.4176L 188.00000000000003 96.4176"></path>
                                                                    <g id="SvgjsG2299" class="apexcharts-series-markers-wrap" data:realIndex="0">
                                                                        <g class="apexcharts-series-markers">
                                                                            <circle id="SvgjsCircle2348" r="0" cx="0" cy="0" class="apexcharts-marker wi6ww9dgy no-pointer-events" stroke="#ffffff" fill="#71dd37" fill-opacity="1" stroke-width="2" stroke-opacity="0.9" default-marker-size="0"></circle>
                                                                        </g>
                                                                    </g>
                                                                </g>
                                                                <g id="SvgjsG2300" class="apexcharts-datalabels" data:realIndex="0"></g>
                                                            </g>
                                                            <line id="SvgjsLine2343" x1="0" y1="0" x2="188" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line>
                                                            <line id="SvgjsLine2344" x1="0" y1="0" x2="188" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line>
                                                            <g id="SvgjsG2345" class="apexcharts-yaxis-annotations"></g>
                                                            <g id="SvgjsG2346" class="apexcharts-xaxis-annotations"></g>
                                                            <g id="SvgjsG2347" class="apexcharts-point-annotations"></g>
                                                            <rect id="SvgjsRect2349" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe" class="apexcharts-zoom-rect"></rect>
                                                            <rect id="SvgjsRect2350" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe" class="apexcharts-selection-rect"></rect>
                                                        </g>
                                                        <rect id="SvgjsRect2293" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"></rect>
                                                        <g id="SvgjsG2330" class="apexcharts-yaxis" rel="0" transform="translate(-8, 0)">
                                                            <g id="SvgjsG2331" class="apexcharts-yaxis-texts-g"></g>
                                                        </g>
                                                        <g id="SvgjsG2291" class="apexcharts-annotations"></g>
                                                    </svg>
                                                    <div class="apexcharts-legend" style="max-height: 60px;"></div>
                                                    <div class="apexcharts-tooltip apexcharts-theme-light">
                                                        <div class="apexcharts-tooltip-title" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div>
                                                        <div class="apexcharts-tooltip-series-group" style="order: 1;"><span class="apexcharts-tooltip-marker" style="background-color: rgb(113, 221, 55);"></span>
                                                            <div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                                <div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div>
                                                                <div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div>
                                                                <div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="apexcharts-xaxistooltip apexcharts-xaxistooltip-bottom apexcharts-theme-light">
                                                        <div class="apexcharts-xaxistooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div>
                                                    </div>
                                                    <div class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light">
                                                        <div class="apexcharts-yaxistooltip-text"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="resize-triggers">
                                                <div class="expand-trigger">
                                                    <div style="width: 472px; height: 121px;"></div>
                                                </div>
                                                <div class="contract-trigger"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-6">
                            <!-- Card Border Shadow -->
                            <div class="col-lg-3 col-sm-6">
                                <div class="card card-border-shadow-primary h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="avatar me-4">
                                                <span class="avatar-initial rounded bg-label-primary"><i class="bx bxs-truck bx-lg"></i></span>
                                            </div>
                                            <h4 class="mb-0">42</h4>
                                        </div>
                                        <p class="mb-2">On route vehicles</p>
                                        <p class="mb-0">
                                            <span class="text-heading fw-medium me-2">+18.2%</span>
                                            <span class="text-muted">than last week</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div class="card card-border-shadow-warning h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="avatar me-4">
                                                <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-error bx-lg"></i></span>
                                            </div>
                                            <h4 class="mb-0">8</h4>
                                        </div>
                                        <p class="mb-2">Vehicles with errors</p>
                                        <p class="mb-0">
                                            <span class="text-heading fw-medium me-2">-8.7%</span>
                                            <span class="text-muted">than last week</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div class="card card-border-shadow-danger h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="avatar me-4">
                                                <span class="avatar-initial rounded bg-label-danger"><i class="bx bx-git-repo-forked bx-lg"></i></span>
                                            </div>
                                            <h4 class="mb-0">27</h4>
                                        </div>
                                        <p class="mb-2">Deviated from route</p>
                                        <p class="mb-0">
                                            <span class="text-heading fw-medium me-2">+4.3%</span>
                                            <span class="text-muted">than last week</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <div class="card card-border-shadow-info h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="avatar me-4">
                                                <span class="avatar-initial rounded bg-label-info"><i class="bx bx-time-five bx-lg"></i></span>
                                            </div>
                                            <h4 class="mb-0">13</h4>
                                        </div>
                                        <p class="mb-2">Late vehicles</p>
                                        <p class="mb-0">
                                            <span class="text-heading fw-medium me-2">-2.5%</span>
                                            <span class="text-muted">than last week</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-lg-8 mb-4 order-0">
                                <div class="card">

                                    <div class="d-flex align-items-end row">
                                        <div class="col-sm-7">
                                            <div class="card-body">
                                                <h5 class="card-title text-primary">Congratulations John! 🎉</h5>
                                                <p class="mb-4">
                                                    You have done <span class="fw-bold">72%</span> more sales today. Check your new badge in
                                                    your profile.
                                                </p>

                                                <a href="javascript:;" class="btn btn-sm btn-outline-primary">View Badges</a>
                                            </div>
                                        </div>
                                        <div class="col-sm-5 text-center text-sm-left">
                                            <div class="card-body pb-0 px-0 px-md-4">
                                                <img
                                                    src="<?= $tplPath ?>/assets/img/illustrations/man-with-laptop-light.png"
                                                    height="140"
                                                    alt="View Badge User"
                                                    data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                                    data-app-light-img="illustrations/man-with-laptop-light.png" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 order-1">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-6 mb-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="card-title d-flex align-items-start justify-content-between">
                                                    <div class="avatar flex-shrink-0">
                                                        <img
                                                            src="<?= $tplPath ?>/assets/img/icons/unicons/chart-success.png"
                                                            alt="chart success"
                                                            class="rounded" />
                                                    </div>
                                                    <div class="dropdown">
                                                        <button
                                                            class="btn p-0"
                                                            type="button"
                                                            id="cardOpt3"
                                                            data-bs-toggle="dropdown"
                                                            aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="bx bx-dots-vertical-rounded"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                                            <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="fw-semibold d-block mb-1">Profit</span>
                                                <h3 class="card-title mb-2">$12,628</h3>
                                                <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +72.80%</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-6 mb-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="card-title d-flex align-items-start justify-content-between">
                                                    <div class="avatar flex-shrink-0">
                                                        <img
                                                            src="<?= $tplPath ?>/assets/img/icons/unicons/wallet-info.png"
                                                            alt="Credit Card"
                                                            class="rounded" />
                                                    </div>
                                                    <div class="dropdown">
                                                        <button
                                                            class="btn p-0"
                                                            type="button"
                                                            id="cardOpt6"
                                                            data-bs-toggle="dropdown"
                                                            aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="bx bx-dots-vertical-rounded"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                                                            <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <span>Sales</span>
                                                <h3 class="card-title text-nowrap mb-1">$4,679</h3>
                                                <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +28.42%</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Total Revenue -->
                            <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
                                <div class="card">
                                    <div class="row row-bordered g-0">
                                        <div class="col-md-8">
                                            <h5 class="card-header m-0 me-2 pb-3">Total Revenue</h5>
                                            <div id="totalRevenueChart" class="px-2"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <div class="dropdown">
                                                        <button
                                                            class="btn btn-sm btn-outline-primary dropdown-toggle"
                                                            type="button"
                                                            id="growthReportId"
                                                            data-bs-toggle="dropdown"
                                                            aria-haspopup="true"
                                                            aria-expanded="false">
                                                            2022
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="growthReportId">
                                                            <a class="dropdown-item" href="javascript:void(0);">2021</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">2020</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">2019</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="growthChart"></div>
                                            <div class="text-center fw-semibold pt-3 mb-2">62% Company Growth</div>

                                            <div class="d-flex px-xxl-4 px-lg-2 p-4 gap-xxl-3 gap-lg-1 gap-3 justify-content-between">
                                                <div class="d-flex">
                                                    <div class="me-2">
                                                        <span class="badge bg-label-primary p-2"><i class="bx bx-dollar text-primary"></i></span>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <small>2022</small>
                                                        <h6 class="mb-0">$32.5k</h6>
                                                    </div>
                                                </div>
                                                <div class="d-flex">
                                                    <div class="me-2">
                                                        <span class="badge bg-label-info p-2"><i class="bx bx-wallet text-info"></i></span>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <small>2021</small>
                                                        <h6 class="mb-0">$41.2k</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/ Total Revenue -->
                            <div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
                                <div class="row">
                                    <div class="col-6 mb-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="card-title d-flex align-items-start justify-content-between">
                                                    <div class="avatar flex-shrink-0">
                                                        <img src="<?= $tplPath ?>/assets/img/icons/unicons/paypal.png" alt="Credit Card" class="rounded" />
                                                    </div>
                                                    <div class="dropdown">
                                                        <button
                                                            class="btn p-0"
                                                            type="button"
                                                            id="cardOpt4"
                                                            data-bs-toggle="dropdown"
                                                            aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="bx bx-dots-vertical-rounded"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt4">
                                                            <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="d-block mb-1">Payments</span>
                                                <h3 class="card-title text-nowrap mb-2">$2,456</h3>
                                                <small class="text-danger fw-semibold"><i class="bx bx-down-arrow-alt"></i> -14.82%</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="card-title d-flex align-items-start justify-content-between">
                                                    <div class="avatar flex-shrink-0">
                                                        <img src="<?= $tplPath ?>/assets/img/icons/unicons/cc-primary.png" alt="Credit Card" class="rounded" />
                                                    </div>
                                                    <div class="dropdown">
                                                        <button
                                                            class="btn p-0"
                                                            type="button"
                                                            id="cardOpt1"
                                                            data-bs-toggle="dropdown"
                                                            aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="bx bx-dots-vertical-rounded"></i>
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="cardOpt1">
                                                            <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="fw-semibold d-block mb-1">Transactions</span>
                                                <h3 class="card-title mb-2">$14,857</h3>
                                                <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +28.14%</small>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- </div>
    <div class="row"> -->
                                    <div class="col-12 mb-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                                                    <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                                                        <div class="card-title">
                                                            <h5 class="text-nowrap mb-2">Profile Report</h5>
                                                            <span class="badge bg-label-warning rounded-pill">Year 2021</span>
                                                        </div>
                                                        <div class="mt-sm-auto">
                                                            <small class="text-success text-nowrap fw-semibold"><i class="bx bx-chevron-up"></i> 68.2%</small>
                                                            <h3 class="mb-0">$84,686k</h3>
                                                        </div>
                                                    </div>
                                                    <div id="profileReportChart"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Order Statistics -->
                            <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
                                <div class="card h-100">
                                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                                        <div class="card-title mb-0">
                                            <h5 class="m-0 me-2">Order Statistics</h5>
                                            <small class="text-muted">42.82k Total Sales</small>
                                        </div>
                                        <div class="dropdown">
                                            <button
                                                class="btn p-0"
                                                type="button"
                                                id="orederStatistics"
                                                data-bs-toggle="dropdown"
                                                aria-haspopup="true"
                                                aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="orederStatistics">
                                                <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                                                <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                                <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="d-flex flex-column align-items-center gap-1">
                                                <h2 class="mb-2">8,258</h2>
                                                <span>Total Orders</span>
                                            </div>
                                            <div id="orderStatisticsChart"></div>
                                        </div>
                                        <ul class="p-0 m-0">
                                            <li class="d-flex mb-4 pb-1">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-mobile-alt"></i></span>
                                                </div>
                                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <h6 class="mb-0">Electronic</h6>
                                                        <small class="text-muted">Mobile, Earbuds, TV</small>
                                                    </div>
                                                    <div class="user-progress">
                                                        <small class="fw-semibold">82.5k</small>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="d-flex mb-4 pb-1">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <span class="avatar-initial rounded bg-label-success"><i class="bx bx-closet"></i></span>
                                                </div>
                                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <h6 class="mb-0">Fashion</h6>
                                                        <small class="text-muted">T-shirt, Jeans, Shoes</small>
                                                    </div>
                                                    <div class="user-progress">
                                                        <small class="fw-semibold">23.8k</small>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="d-flex mb-4 pb-1">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <span class="avatar-initial rounded bg-label-info"><i class="bx bx-home-alt"></i></span>
                                                </div>
                                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <h6 class="mb-0">Decor</h6>
                                                        <small class="text-muted">Fine Art, Dining</small>
                                                    </div>
                                                    <div class="user-progress">
                                                        <small class="fw-semibold">849k</small>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="d-flex">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <span class="avatar-initial rounded bg-label-secondary"><i class="bx bx-football"></i></span>
                                                </div>
                                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <h6 class="mb-0">Sports</h6>
                                                        <small class="text-muted">Football, Cricket Kit</small>
                                                    </div>
                                                    <div class="user-progress">
                                                        <small class="fw-semibold">99</small>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!--/ Order Statistics -->

                            <!-- Expense Overview -->
                            <div class="col-md-6 col-lg-4 order-1 mb-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <ul class="nav nav-pills" role="tablist">
                                            <li class="nav-item">
                                                <button
                                                    type="button"
                                                    class="nav-link active"
                                                    role="tab"
                                                    data-bs-toggle="tab"
                                                    data-bs-target="#navs-tabs-line-card-income"
                                                    aria-controls="navs-tabs-line-card-income"
                                                    aria-selected="true">
                                                    Income
                                                </button>
                                            </li>
                                            <li class="nav-item">
                                                <button type="button" class="nav-link" role="tab">Expenses</button>
                                            </li>
                                            <li class="nav-item">
                                                <button type="button" class="nav-link" role="tab">Profit</button>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="card-body px-0">
                                        <div class="tab-content p-0">
                                            <div class="tab-pane fade show active" id="navs-tabs-line-card-income" role="tabpanel">
                                                <div class="d-flex p-4 pt-3">
                                                    <div class="avatar flex-shrink-0 me-3">
                                                        <img src="<?= $tplPath ?>/assets/img/icons/unicons/wallet.png" alt="User" />
                                                    </div>
                                                    <div>
                                                        <small class="text-muted d-block">Total Balance</small>
                                                        <div class="d-flex align-items-center">
                                                            <h6 class="mb-0 me-1">$459.10</h6>
                                                            <small class="text-success fw-semibold">
                                                                <i class="bx bx-chevron-up"></i>
                                                                42.9%
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="incomeChart"></div>
                                                <div class="d-flex justify-content-center pt-4 gap-2">
                                                    <div class="flex-shrink-0">
                                                        <div id="expensesOfWeek"></div>
                                                    </div>
                                                    <div>
                                                        <p class="mb-n1 mt-1">Expenses This Week</p>
                                                        <small class="text-muted">$39 less than last week</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/ Expense Overview -->

                            <!-- Transactions -->
                            <div class="col-md-6 col-lg-4 order-2 mb-4">
                                <div class="card h-100">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <h5 class="card-title m-0 me-2">Transactions</h5>
                                        <div class="dropdown">
                                            <button
                                                class="btn p-0"
                                                type="button"
                                                id="transactionID"
                                                data-bs-toggle="dropdown"
                                                aria-haspopup="true"
                                                aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionID">
                                                <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                                                <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                                                <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <ul class="p-0 m-0">
                                            <li class="d-flex mb-4 pb-1">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <img src="<?= $tplPath ?>/assets/img/icons/unicons/paypal.png" alt="User" class="rounded" />
                                                </div>
                                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <small class="text-muted d-block mb-1">Paypal</small>
                                                        <h6 class="mb-0">Send money</h6>
                                                    </div>
                                                    <div class="user-progress d-flex align-items-center gap-1">
                                                        <h6 class="mb-0">+82.6</h6>
                                                        <span class="text-muted">USD</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="d-flex mb-4 pb-1">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <img src="<?= $tplPath ?>/assets/img/icons/unicons/wallet.png" alt="User" class="rounded" />
                                                </div>
                                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <small class="text-muted d-block mb-1">Wallet</small>
                                                        <h6 class="mb-0">Mac'D</h6>
                                                    </div>
                                                    <div class="user-progress d-flex align-items-center gap-1">
                                                        <h6 class="mb-0">+270.69</h6>
                                                        <span class="text-muted">USD</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="d-flex mb-4 pb-1">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <img src="<?= $tplPath ?>/assets/img/icons/unicons/chart.png" alt="User" class="rounded" />
                                                </div>
                                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <small class="text-muted d-block mb-1">Transfer</small>
                                                        <h6 class="mb-0">Refund</h6>
                                                    </div>
                                                    <div class="user-progress d-flex align-items-center gap-1">
                                                        <h6 class="mb-0">+637.91</h6>
                                                        <span class="text-muted">USD</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="d-flex mb-4 pb-1">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <img src="<?= $tplPath ?>/assets/img/icons/unicons/cc-success.png" alt="User" class="rounded" />
                                                </div>
                                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <small class="text-muted d-block mb-1">Credit Card</small>
                                                        <h6 class="mb-0">Ordered Food</h6>
                                                    </div>
                                                    <div class="user-progress d-flex align-items-center gap-1">
                                                        <h6 class="mb-0">-838.71</h6>
                                                        <span class="text-muted">USD</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="d-flex mb-4 pb-1">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <img src="<?= $tplPath ?>/assets/img/icons/unicons/wallet.png" alt="User" class="rounded" />
                                                </div>
                                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <small class="text-muted d-block mb-1">Wallet</small>
                                                        <h6 class="mb-0">Starbucks</h6>
                                                    </div>
                                                    <div class="user-progress d-flex align-items-center gap-1">
                                                        <h6 class="mb-0">+203.33</h6>
                                                        <span class="text-muted">USD</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="d-flex">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <img src="<?= $tplPath ?>/assets/img/icons/unicons/cc-warning.png" alt="User" class="rounded" />
                                                </div>
                                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <small class="text-muted d-block mb-1">Mastercard</small>
                                                        <h6 class="mb-0">Ordered Food</h6>
                                                    </div>
                                                    <div class="user-progress d-flex align-items-center gap-1">
                                                        <h6 class="mb-0">-92.45</h6>
                                                        <span class="text-muted">USD</span>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!--/ Transactions -->
                        </div>
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                            <div class="mb-2 mb-md-0">
                                ©
                                <script>
                                    document.write(new Date().getFullYear());
                                </script>
                                diseñado y programado por
                                <a href="https://hardboxs.com/" target="_blank" class="footer-link fw-bolder">Hardboxs</a>
                            </div>
                            <div>
                                <a href="#" class="footer-link me-4" target="_blank">License</a>
                                <a href="#" target="_blank" class="footer-link me-4">Mas información</a>

                                <a
                                    href="#"
                                    target="_blank"
                                    class="footer-link me-4">Documentacion</a>

                                <a
                                    href="#"
                                    target="_blank"
                                    class="footer-link me-4">Support</a>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="<?= $tplPath ?>/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="<?= $tplPath ?>/assets/vendor/libs/popper/popper.js"></script>
    <script src="<?= $tplPath ?>/assets/vendor/js/bootstrap.js"></script>
    <script src="<?= $tplPath ?>/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="<?= $tplPath ?>/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="<?= $tplPath ?>/assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="<?= $tplPath ?>/assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="<?= $tplPath ?>/assets/js/dashboards-analytics.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>