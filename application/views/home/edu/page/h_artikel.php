<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main-content">

    <div class="full-width">

        <div class="article-title">
            <div class="share-block right">
                <div>
                    <div class="share-article left">
                        <span>Social media</span>
                        <strong>Share this article</strong>
                    </div>
                    <div class="left">
                        <script type="text/javascript" async src="https://platform.twitter.com/widgets.js"></script>
                        <a href="whatsapp://send?text=<?= site_url(uri_string()) ?>" class="custom-soc icon-text">WA</a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= site_url(uri_string()) ?>" target="_blank" class="custom-soc icon-text">&#62220;</a>
                        <a href="https://twitter.com/intent/tweet?text=<?= site_url(uri_string()) ?>" target="_blank" class="custom-soc icon-text">&#62217;</a>
                        <a href="#" class="custom-soc icon-text">&#62223;</a>
                    </div>
                    <div class="clear-float"></div>
                </div>
                <div>
                    <a href="#" class="small-button"><span class="icon-text">&#59158;</span>&nbsp;&nbsp;Print this article</a>
                    <a href="#" class="small-button"><span class="icon-text">&#9993;</span>&nbsp;&nbsp;Send e-mail</a>
                </div>
            </div>

            <h1>
                <?= $detail['judul_artikel'] ?>
                <span class="viewer" style="font-size: 15px"><i class="icon-text"></i>👁️️ <?= angka($detail['view_artikel']) ?></span>
            </h1>
            <div class="author">
                <span style="margin-right: 10px" class="left">
                    <img src="<?= load_file('private/logo.png') ?>" alt="" />
                </span>
                <div class="a-content">
                    <span> By <b><?= $detail['log_artikel'] ?></b></span>
                    <span class="meta"><?= format_date($detail['update_artikel'], 0) ?> 
                        <span class="tag" style="font-size:11px;background-color:<?= $detail['color_jenis'] ?>"><?= $detail['judul_jenis'] ?></span>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <!-- BEGIN .main-page -->
    <div class="main-page left">
        <!-- BEGIN .double-block -->
        <div class="single-block">
            <!-- BEGIN .content-block -->
            <div class="content-block main right">

                <div class="block">
                    <div class="block-content">

                        <p>
                            <span class="hover-effect <?= empty($detail['foto_artikel']) ? 'hide' : '' ?>">
                                <img class="lazyload blur-up" src="<?= load_file($detail['foto_artikel']) ?>" alt="<?= ctk($detail['judul_artikel']) ?>">
                            </span>
                        </p>

                        <div class="shortcode-content" style="font-size: 15px">
                            <?= $detail['isi_artikel'] ?>
                            <div class="article-title">
                                <div class="share-block right">
                                    <div>
                                        <div class="share-article left">
                                            <span>Social media</span>
                                            <strong>Share this article</strong>
                                        </div>
                                        <div class="left">
                                            <a href="whatsapp://send?text=<?= site_url(uri_string()) ?>" class="custom-soc icon-text">WA</a>
                                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= site_url(uri_string()) ?>" target="_blank" class="custom-soc icon-text">&#62220;</a>
                                            <a href="https://twitter.com/intent/tweet?text=<?= site_url(uri_string()) ?>" target="_blank" class="custom-soc icon-text">&#62217;</a>
                                            <a href="#" class="custom-soc icon-text">&#62223;</a>
                                        </div>
                                        <div class="clear-float"></div>
                                    </div>
                                    <div>
                                        <a href="#" class="small-button"><span class="icon-text">&#59158;</span>&nbsp;&nbsp;Print this article</a>
                                        <a href="#" class="small-button"><span class="icon-text">&#9993;</span>&nbsp;&nbsp;Send e-mail</a>
                                    </div>
                                </div>

                                <div class="author">
                                    <span style="margin-right: 10px" class="left">
                                        <img src="<?= load_file('private/logo.png') ?>" alt="" />
                                    </span>
                                    <div class="a-content">
                                        <span> By <b><?= $detail['log_artikel'] ?></b></span>
                                        <span class="meta"><?= format_date($detail['update_artikel'], 0) ?> </span>
                                    </div>
                                </div>

                                <div class="article-tags tag-cloud">
                                    <strong>TAGS :</strong>
                                    <a style="background-color:<?= $detail['color_jenis'] ?>" href="<?= site_url('tag/' . $detail['slug_jenis']) ?>"><?= $detail['judul_jenis'] ?></a>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                <!-- END .content-block -->
            </div>
            <!-- END .double-block -->
        </div>

        <!-- END .main-page -->
    </div>

    <!-- BEGIN .main-sidebar -->
    <div class="main-sidebar right">

        <!-- BEGIN .widget -->
        <div class="widget">
            <h3>Artikel Terbaru</h3>
            <div class="widget-comments">
                <ul class="the-berita"></ul>
            </div>
            <!-- END .widget -->
        </div>
        <!-- END .main-sidebar -->
    </div>

    <div class="clear-float"></div>

</div>
<script async type="text/javascript">
    $(document).ready(function () {
        //Terbaru
        $.ajax({
            url: module + "/ajax/type/list/source/artikel",
            type: 'POST', dataType: "json",
            data: { jenis: '', tipe: '', order: 'DESC', limit: 10,
                [$('meta[name="csrf-token"]').attr('content')] : $('meta[name="csrf-token"]').attr('accesskey')
            },
            success: function (result) {
                $.each(result.data, function (index, value) {
                    $(".the-berita").append(`<li><div class="comment-photo">
                        <a href="${value.slug}" class="hover-effect">
                            <img width="50" class="lazyload blur-up" src="${value.foto}" alt="${value.judul}">
                        </a>
                    </div>
                    <div class="comment-content">
                        <h3><a href="${value.slug}">${value.judul}</a></h3>
                        <p>${value.isi}</p>
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