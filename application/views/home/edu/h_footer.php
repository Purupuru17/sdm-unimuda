<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- BEGIN .wrapper -->
<div class="wrapper" style="border: none;padding-bottom: 0px;padding-top: 10px">
    <a href="<?= site_url() ?>" class="logo-footer" style="margin-right: 10px;">
        <img width="50" class="lazyload blur-up" src="<?= load_file($app_session['logo']) ?>" alt="<?= ctk($app_session['judul']) ?>">
    </a>
    <div style="font-size: 10px; color: #fff; margin-top: 5px;line-height: normal">
        <strong style="font-size: 1.5em"><?= ctk($app_session['deskripsi']) ?></strong><br/>
        <strong>Jl. Kh. Ahmad Dahlan No.01, Mariyat Pantai, Aimas, 
        Kabupaten Sorong, Papua Barat - 98418</strong>
        <br/>email : info@unimudasorong.ac.id
        <!--<br/>phone : +62 811-4831-212-->
        <p class="right">&copy; <?= APP_VER ?> Copyright 
            <a  style="color: #fff" href="https://unimudasorong.ac.id/" target="_blank" class=""><b> UNIMUDA Sorong</b></a>
            | <small>{elapsed_time} detik ~ {memory_usage}</small>
        </p>
    </div>
    <!-- END .wrapper -->
</div>
