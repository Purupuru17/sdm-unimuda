<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main-content">
    <!-- BEGIN .main-page -->
    <div class="main-page right">

        <!-- BEGIN .single-block -->
        <div class="single-block">

            <!-- BEGIN .content-block -->
            <div class="content-block main right">

                <div class="block">
                    <div class="block-title">
                        <h2><?= $title ?></h2>
                    </div>
                    <div class="block-content">
                        <?php
                        foreach ($terbaru['data'] as $tb) {
                            ?>
                            <div class="article-big">
                                <div class="article-photo">
                                    <a href="<?= site_url('artikel/' . $tb['slug_artikel']) ?>" class="hover-effect">
                                        <img  width="210" class="lazyload blur-up" src="<?= load_file($tb['foto_artikel']) ?>" alt="<?= ctk($tb['judul_artikel']) ?>">
                                    </a>
                                </div>
                                <div class="article-content">
                                    <h2>
                                        <a href="<?= site_url('artikel/' . $tb['slug_artikel']) ?>"><?= $tb['judul_artikel'] ?></a>
                                        <a href="#" class="">
                                            <span class="marker" style="font-size:11px;background-color:<?= $tb['color_jenis'] ?>"><?= $tb['judul_jenis'] ?></span>
                                        </a>
                                    </h2>
                                    <span class="meta">
                                        <a href="#"><span class="icon-text">&#128100;</span><?= $tb['log_artikel'] ?></a>
                                        <a href="#"><span class="icon-text">&#128340;</span><?= format_date($tb['update_artikel'], 0) ?></a>
                                    </span>
                                    <p><?= limit_text($tb['isi_artikel'], 200) ?></p>
                                    <span class="meta">
                                        <a href="<?= site_url('artikel/' . $tb['slug_artikel']) ?>" class="more">
                                            Selengkapnya<span class="icon-text">&#9656;</span>
                                        </a>
                                    </span>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="pagination">
                            <?= $pagination ?>
                        </div>

                    </div>
                </div>

                <!-- END .content-block -->
            </div>

            <!-- END .single-block -->
        </div>

        <!-- END .main-page -->
    </div>

    <!-- BEGIN .main-sidebar -->
    <div class="main-sidebar left">

        <!-- BEGIN .content-block -->
        <div class="content-block">
            <div class="block">
                <h2 class="list-title" style="color: #dd8229;border-bottom: 2px solid #dd8229;">Artikel Populer</h2>
                <ul class="article-block the-berita"></ul>
                <a href="<?= site_url('tag/all') ?>" class="more">Lebih Banyak</a>
            </div>

            <!-- END .content-block -->
        </div>

        <!-- END .main-sidebar -->
    </div>
    <div class="clear-float"></div>
</div>
<script async type="text/javascript">
    $(document).ready(function () {
        //Populer
        $.ajax({
            url: module + "/ajax/type/list/source/artikel",
            type: 'POST', dataType: "json",
            data: { jenis: '', tipe: 'populer', order: 'RANDOM', limit: 10,
                [$('meta[name="csrf-token"]').attr('content')] : $('meta[name="csrf-token"]').attr('accesskey')
            },
            success: function (result) {
                $.each(result.data, function (index, value) {
                    $(".the-berita").append(`<li><div class="article-photo">
                        <a href="${value.slug}" class="hover-effect">
                            <img width="60" class="lazyload blur-up" src="${value.foto}" alt="${value.judul}">
                        </a>
                    </div>
                    <div class="article-content">
                        <h4><a href="${value.slug}">${value.judul}</a></h4>
                        <span class="meta">
                            <span class="marker" style="font-size:10px;background-color:${value.color}">${value.jenis}</span><br/>
                            <a href="#"><span class="icon-text">&#128340;</span>${value.update}</a>
                        </span>
                    </div></li>`);
                });
            },
            error: function (xhr, ajax, err) {
                console.log('Error : ' + xhr.responseText);
            }
        });
    });
</script>