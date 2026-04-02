<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div id="navbar" class="navbar navbar-default web-color">
    <script type="text/javascript">
        try {
            ace.settings.check('navbar', 'fixed')
        } catch (e) {
        }
    </script>

    <div class="navbar-container" id="navbar-container">
        <!-- #section:basics/sidebar.mobile.toggle -->
        <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
            <span class="sr-only">Toggle sidebar</span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>
        </button>

        <!-- /section:basics/sidebar.mobile.toggle -->
        <div class="navbar-header pull-left">
            <!-- #section:basics/navbar.layout.brand -->
            <a href="<?= site_url() ?>" class="navbar-brand">
                <span class="bolder"><?= $app_session['judul'] ?></span>
            </a>
        </div>
        <!-- #section:basics/navbar.dropdown -->
        <div class="navbar-buttons navbar-header pull-right" role="navigation">
            <ul class="nav ace-nav">
                <li class="transparent hidden-xs hidden-sm">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="ace-icon fa fa-calendar"></i>
                        <span class="is-clock"><?= format_date(date('Y-m-d H:i:s'),0) ?></span>
                    </a>
                </li>
                <li class="<?= ($this->session->userdata('level') == '1') ? '' : 'hide' ?> transparent hidden-xs hidden-sm">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="ace-icon fa fa-bell icon-animated-bell"></i>
                        <span id="item-notif" class="badge badge-important">0</span>
                    </a>
                    <ul class="dropdown-menu-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
                        <li class="dropdown-content">
                            <ul id="li-notif" class="dropdown-menu dropdown-navbar">
                                
                            </ul>
                        </li>
                        <li class="dropdown-header">
                            <a href="#" class="center">
                                <span id="new-notif">0</span> pemberitahuan baru &nbsp;<i class="fa fa-arrow-right"></i>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="transparent" id="menu-login">
                    <?php
                    if(!$this->session->userdata('logged')){
                        echo '<a href="'.site_url('non_login/login').'">
                            <img class="nav-user-photo" src="'.load_file($this->session->userdata('foto'),1).'" alt="Profil" />
                            <span class="user-info">
                                <small>Selamat datang,</small>
                                <i class="ace-icon fa fa-lock"></i> Login
                            </span>
                        </a>';
                    }else{
                    ?>
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                        <img class="nav-user-photo" src="<?= load_file($this->session->userdata('foto'),1) ?>" alt="Profil" />
                        <span class="user-info">
                            <small>Selamat datang,</small>
                            <?= $this->session->userdata('name'); ?>
                        </span>

                        <i class="ace-icon fa fa-caret-down"></i>
                    </a>

                    <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                        <li>
                            <a href="<?= site_url('sistem/akun'); ?>">
                                <i class="ace-icon fa fa-user"></i>
                                Akun Saya
                            </a>
                        </li>
                        <li>
                            <a href="<?= site_url('sistem/password'); ?>">
                                <i class="ace-icon fa fa-lock"></i>
                                Ubah Password
                            </a>
                        </li>
                        <li class="divider"></li>
                        <?php
                            foreach ($this->session->userdata('group_role') as $role) {
                                echo $this->session->userdata('groupid') == $role['group_id'] ? '' : '<li>
                                    <a href="'. site_url('non_login/login/changed/'. encode($role['group_id']).'/'.encode($role['level'])).'">
                                        <i class="ace-icon fa fa-users"></i>
                                        As '.$role['nama_group'].'
                                    </a>
                                </li>';
                            }
                        ?>
                        <li class="divider"></li>
                        <li>
                            <a href="<?= site_url('non_login/login/logout'); ?>">
                                <i class="ace-icon fa fa-power-off"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                    <?php } ?>
                </li>

                <!-- /section:basics/navbar.user_menu -->
            </ul>
        </div>

        <!-- /section:basics/navbar.dropdown -->
    </div><!-- /.navbar-container -->
</div>
