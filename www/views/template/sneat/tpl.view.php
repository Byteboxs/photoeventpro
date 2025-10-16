<!DOCTYPE html>
<html lang="es" class="light-style layout-navbar-fixed layout-compact layout-menu-fixed layout-menu-collapsed" dir="ltr" data-theme="theme-default" data-assets-path="<?= $tplPath ?>/assets/" data-template="vertical-menu-template" data-style="dark">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Demo</title>
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <link rel="canonical" href="">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= $tplPath ?>/assets/img/favicon/favicon.ico" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="<?= $tplPath ?>/assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="<?= $tplPath ?>/assets/vendor/fonts/fontawesome/css/all.min.css" />
    <!-- <link rel="stylesheet" href="<?= $tplPath ?>/assets/vendor/fonts/flag-icons.css" /> -->

    <!-- Core CSS light-->
    <!-- <link rel="stylesheet" href="<?= $tplPath ?>/assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?= $tplPath ?>/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" /> -->
    <!-- Core CSS dark-->
    <link rel="stylesheet" href="<?= $tplPath ?>/assets/vendor/css/core-dark.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?= $tplPath ?>/assets/vendor/css/theme-default-dark.css" class="template-customizer-theme-css" />

    <link rel="stylesheet" href="<?= $tplPath ?>/assets/css/demo.css" />
    <link rel="stylesheet" href="<?= $tplPath ?>/assets/css/style.css" />
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?= $tplPath ?>/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <!-- <link rel="stylesheet" href="<?= $tplPath ?>/assets/vendor/libs/typeahead-js/typeahead.css" /> -->
    <link rel="stylesheet" href="<?= $tplPath ?>/assets/vendor/libs/apex-charts/apex-charts.css" />
    <!-- Page CSS -->
    <!-- Helpers -->
    <script src="<?= $tplPath ?>/assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <!-- <script src="<?= $tplPath ?>/assets/vendor/js/template-customizer.js"></script> -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="<?= $tplPath ?>/assets/js/config.js"></script>
</head>

<body>
    <div id="trash" data-valor-base64="<?= base64_encode(APP_DIRECTORY == '/' ? '' : APP_DIRECTORY)  ?>"></div>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            {{menu}}
            <div class="layout-page">
                {{navbar}}
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        {{breadcrumb}}
                        <div id="message"></div>
                        {{content}}
                    </div>
                    {{footer}}
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
        <div class="drag-target"></div>
    </div>
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="<?= app\core\Application::$BASE_URL . APP_DIRECTORY ?>/public/static/js/toolbox.js"></script>
    <script src="<?= app\core\Application::$BASE_URL . APP_DIRECTORY ?>/public/static/js/ImageDragAndDropHandler.js"></script>
    <script src="<?= app\core\Application::$BASE_URL . APP_DIRECTORY ?>/public/static/js/FieldValidator.js"></script>
    <script src="<?= app\core\Application::$BASE_URL . APP_DIRECTORY ?>/public/static/js/FormDataExtractor.js"></script>
    <script src="<?= app\core\Application::$BASE_URL . APP_DIRECTORY ?>/public/static/js/FormEventHandler.js"></script>
    <script src="<?= app\core\Application::$BASE_URL . APP_DIRECTORY ?>/public/static/js/ModernAjaxHandler.js"></script>
    <script src="<?= app\core\Application::$BASE_URL . APP_DIRECTORY ?>/public/static/js/Base64Decoder.js"></script>
    <script src="<?= app\core\Application::$BASE_URL . APP_DIRECTORY ?>/public/static/js/FileDragAndDropHandler.js"></script>
    <script src="<?= app\core\Application::$BASE_URL . APP_DIRECTORY ?>/public/static/js/AlbumManager.js"></script>
    <script src="<?= $tplPath ?>/assets/vendor/libs/jquery/jquery.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.7.1.slim.js" integrity="sha256-UgvvN8vBkgO0luPSUl2s8TIlOSYRoGFAX4jlCIm9Adc=" crossorigin="anonymous"></script> -->
    <script src="<?= $tplPath ?>/assets/vendor/libs/popper/popper.js"></script>
    <script src="<?= $tplPath ?>/assets/vendor/js/bootstrap.js"></script>
    <script src="<?= $tplPath ?>/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="<?= $tplPath ?>/assets/vendor/libs/hammer/hammer.js"></script>
    <!-- <script src="<?= $tplPath ?>/assets/vendor/libs/i18n/i18n.js"></script> -->
    <script src="<?= $tplPath ?>/assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="<?= $tplPath ?>/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->
    <!-- Vendors JS -->
    <script src="<?= $tplPath ?>/assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="<?= $tplPath ?>/assets/vendor/js/main.js"></script>
    <!-- Page JS -->
</body>

</html>