<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- BEGIN .wrapper -->
<div class="wrapper" style="padding: 20px 10px 10px 0px;">
    <div class="header-logo" style="margin-left: 0px">
        <a href="<?= site_url() ?>">
            <img width="90" class="lazyload blur-up" src="<?= load_file($app_session['logo']) ?>" alt="<?= ctk($app_session['judul']) ?>">
        </a>
    </div>
    <div class="header-menu" style="width: 60%">
        <ul class="load-responsive" rel="Top Menu" style="margin-top: -13px; margin-bottom: 15px;">
            <li><a href="#">Berjiwa dan Semangat Muda</a></li>
            <li><a href="#">Terdepan</a></li>
            <li><a href="#">Unggul</a></li>
            <li><a href="#">Berkemajuan</a></li>
        </ul>
        <h1 style="margin-bottom: 8px"><?= ctk($app_session['judul']) ?></h1>
        <strong style="font-size: 1.5em"><?= ctk($app_session['deskripsi']) ?></strong><br/>
        <span>Universitas Pendidikan Muhammadiyah (UNIMUDA) Sorong</span>
    </div>
    <div class="header-addons">
        <div class="header-weather">
            <span class="is-clock city"><?= format_date(date('Y-m-d H:i:s'),0) ?></span>
       </div>
        <div class="header-search">
            <form method="GET" action="<?= site_url('tag/all') ?>" name="form-search">
                <input type="text" required="" name="q" class="search-input" placeholder="Pencarian..">
                <input type="submit" value="Search" class="search-button" />
            </form>
        </div>
    </div>
    
<!-- END .wrapper -->
</div>

<div class="main-menu sticky">
    <!-- BEGIN .wrapper -->
    <div class="wrapper">
        <ul class="the-menu"></ul>
    <!-- END .wrapper -->
    </div>
</div>
<div class="secondary-menu">
    <!-- BEGIN .wrapper -->
    <div class="wrapper">
        <ul class="the-category"></ul>
    <!-- END .wrapper -->
    </div>
</div>