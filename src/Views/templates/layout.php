<!DOCTYPE html>
<html lang="<?= $__locale ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $page_title ?? 'Dashboard' ?> | <?= $__app_name ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/fontawesome-free/css/all.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= herauth_asset_url('vendor/adminlte') ?>/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/flag-icon-css/css/flag-icon.min.css">
    
    <?= $this->renderSection('css') ?>
</head>

<body class="hold-transition light-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper" id="appVue">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__wobble" src="<?= herauth_asset_url('img/favicon.ico') ?>" alt="<?= $__app_name ?>Logo" height="60" width="60">
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#">
                        <?php $locale_img = herauth_locale_img($__locale);?>
                        <img src="<?= herauth_asset_url('vendor/adminlte/plugins/flag-icon-css/flags/1x1/'. $locale_img . ".svg") ?>">
                        <i class="fas fa-angle-down ml-1"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                        <?php foreach ($__locale_list as $locale) :
                            $locale_img = herauth_locale_img($locale);
                        ?>
                            <a href="<?= herauth_set_locale($locale) ?>" class="dropdown-item mb-1">
                                <img src="<?= herauth_asset_url('vendor/adminlte/plugins/flag-icon-css/flags/1x1/' . $locale_img . ".svg") ?>" style="height:20px">
                                <?= herauth_locale_text($locale); ?>
                            </a>
                        <?php endforeach ?>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fas fa-user"></i>
                        <i class="fas fa-angle-down"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="<?= herauth_base_locale_url('profil') ?>" class="dropdown-item">
                            <i class="nav-icon fas fa-user"></i> <?= lang('Label.profil') ?>
                        </a>
                        <a href="<?= herauth_base_locale_url('logout') ?>" class="dropdown-item bg-red">
                            <i class="nav-icon fas fa-power-off"></i> <?= lang('Label.logout') ?>
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="<?= herauth_base_locale_url('') ?>" class="brand-link">
                <img src="<?= herauth_asset_url('img/favicon.ico') ?>" alt="<?= $__app_name ?> Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light"><?= $__app_name ?></span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="<?= herauth_asset_url('vendor/adminlte') ?>/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block"><?= $_account->profil->name ?></a>
                    </div>
                </div>
                <?php
                $url = str_replace(herauth_base_locale_url(), '', $url);
                ?>
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent text-sm" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                        <li class="nav-item">
                            <a href="<?= herauth_base_locale_url('') ?>" class="nav-link <?= in_array($url, ['', '/dashboard']) ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    <?= lang("Label.dashboard") ?>
                                </p>
                            </a>
                        </li>
                        <?php if($_account->inGroup(['superadmin'])):?>
                        <?php
                        $list_master_data = [
                            [
                                'name' => lang("Label.group"),
                                'this_url' =>  'group'
                            ],
                            [
                                'name' => lang("Label.permission"),
                                'this_url' =>  'permission'
                            ],
                            [
                                'name' => lang("Label.client.text"),
                                'this_url' =>  'client'
                            ],
                            [
                                'name' => lang("Label.account"),
                                'this_url' =>  'account'
                            ],
                        ];
                        ?>
                        <li class="nav-item menuMaster <?= in_array(str_replace('/master/', '', $url), array_column($list_master_data, 'this_url')) ? 'menu-open' : '' ?>">
                            <a href="#" class="nav-link menuMasterLink <?= in_array(str_replace('/master/', '', $url), array_column($list_master_data, 'this_url')) ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-copy"></i>
                                <p>
                                    <?= lang("Label.datatable.data") ?>
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <?php
                                $menu_open = false;
                                foreach ($list_master_data as $master_data) :
                                    if (!$menu_open && strpos($url, '/master/' . $master_data['this_url']) !== false) {
                                        $menu_open = true;
                                    }
                                ?>
                                    <li class="nav-item">
                                        <a href="<?= herauth_base_locale_url('master/' . $master_data['this_url']) ?>" class="nav-link <?= strpos($url, '/master/' . $master_data['this_url']) !== false ? 'active' : '' ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p><?= $master_data['name'] ?></p>
                                        </a>
                                    </li>
                                <?php endforeach;
                                ?>
                            </ul>
                        </li>
                        <?php endif?>
                        <li class="nav-item">
                            <a href="<?= herauth_base_locale_url('request_log') ?>" class="nav-link <?= in_array($url, ['/request_log']) ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-folder-open"></i>
                                <p>
                                    <?= lang("Label.requestLog") ?>
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <h1 class="m-0"><?= $page_title ?? 'Dashboard' ?></h1>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <?php $this->renderSection('content') ?>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; <?= date("Y") ?> <a href="<?= herauth_base_url("admin") ?>"><?= $__app_name ?></a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0.0
            </div>
        </footer>
    </div>
    <?php $this->renderSection('modal') ?>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= herauth_asset_url('vendor/adminlte') ?>/dist/js/adminlte.js"></script>

    <!-- PAGE PLUGINS -->
    <!-- jQuery Mapael -->
    <script src="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
    <script src="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/raphael/raphael.min.js"></script>
    <script src="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="<?= herauth_asset_url('vendor/axios') ?>/axios.min.js"></script>
    <script src="<?= herauth_asset_url('vendor/adminlte/plugins/moment/moment-with-locales.min.js') ?>"></script>
    <script src="<?= herauth_asset_url('vendor/vuejs') ?>/vue.js"></script>
    <script src="<?= herauth_asset_url('lang') ?>/lang.js"></script>
    <script>
        herlangjsSetLocaleSupport(<?= json_encode($__locale_list ?? []) ?>);
        herlangjsSetPathLocale("<?= config("Herauth")->herauthLangJsUrl ?? '' ?>");
        herlangjsSetLocale("<?= $__locale ?? 'id' ?>");
    </script>
    <script>
        const axiosValid = axios.create({
            validateStatus: () => true,
        })
    </script>
    <script>
        var dataVue = function() {
            return {}
        }
        var createdVue = function() {}
        var watchsVue = {}
        var methodsVue = {}
        var computedVue = {}
        var filtersVue = {}

        function toLocaleDate(date, format = 'LL') {
            moment.locale("<?= $__locale ?? 'id' ?>")
            return moment(date).format(format)
        }

        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // tambahkan titik jika yang di input sudah menjadi angka ribuan
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }
    </script>
    <?php $this->renderSection('js') ?>
    <script>
        vue = new Vue({
            el: '#appVue',
            data: dataVue,
            watch: watchsVue,
            computed: computedVue,
            created: createdVue,
            filters: filtersVue,
            methods: methodsVue
        })
    </script>
    <?php
    if ($menu_open) :
    ?>
        <script>
            document.querySelector('.menuMaster').classList.add('menu-open')
            document.querySelector('.menuMaster .menuMasterLink').classList.add('active')
        </script>
    <?php endif ?>
</body>

</html>