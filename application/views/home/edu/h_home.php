<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main-content">

    <!-- BEGIN .main-page -->
    <div class="main-page left">

        <!-- BEGIN .double-block -->
        <div class="double-block">

            <!-- BEGIN .content-block -->
            <div class="content-block main right">

                <div class="block">
                    <div class="block-title">
                        <h2>Artikel Terbaru</h2>
                    </div>
                    <div class="block-content the-berita"></div>
                </div>
                <div class="block" style="display: none">
                    <div class="block-title">
                        <h2>Social Media</h2>
                    </div>
                    <div class="block-content">
                        <div class="article-big" align="center">
                            <div class="fb-page" data-href="https://www.facebook.com/unimudasorong/" data-tabs="timeline" data-width="500" data-height="400" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="false">
                                <blockquote cite="https://www.facebook.com/unimudasorong/" class="fb-xfbml-parse-ignore">
                                    <a href="https://www.facebook.com/unimudasorong/">Unimuda Sorong</a>
                                </blockquote>
                            </div>
                        </div>
                        <div class="article-big">
                            <a class="twitter-timeline" href="https://twitter.com/unimudasorong" data-chrome="nofooter noborders" data-height="400">
                                Tweets by @unimudasorong
                            </a>
                        </div>
                    </div>
                </div>
                <!-- END .content-block -->
            </div>

            <!-- BEGIN .content-block -->
            <div class="content-block left">
                <div class="block">
                    <h2 class="list-title" style="color: #d40000;border-bottom: 2px solid #d40000;">Pengumuman</h2>
                    <ul class="article-block the-pengumuman"></ul>
                    <a href="<?= site_url('tag/pengumuman') ?>" class="more">Lebih Banyak</a>
                </div>
                <div class="block">
                    <h2 class="list-title" style="color: #18ab00;border-bottom: 2px solid #18ab00;">Kegiatan</h2>
                    <ul class="article-block the-kegiatan"></ul>
                    <a href="<?= site_url('tag/kegiatan') ?>" class="more">Lebih Banyak</a>
                </div>
                <div class="block">
                    <h2 class="list-title" style="color: #2277c6;border-bottom: 2px solid #2277c6;">Kutipan</h2>
                    <ul class="article-list the-kutipan"></ul>
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
            <h3>Pengunjung</h3>
            <div class="widget-social">
                <div class="social-bar">
                    <a href="#" class="social-icon"><i class="number today"></i><span class="google">Hari Ini</span></a>
                    <a href="#" class="social-icon"><i class="number yesterday"></i><span class="twitter">Kemarin</span></a>
                    <a href="#" class="social-icon"><i class="number week"></i><span class="linkedin">Minggu Ini</span></a>
                    <a href="#" class="social-icon"><i class="number month"></i><span class="facebook">Bulan Ini</span></a>
                    
                </div>
            </div>
            <!-- END .widget -->
        </div>
        <div class="widget-2 widget">					
            <h3>Akses Informasi</h3>
            <div class="widget-articles">
                <ul>
                    <li>
                        <div class="article-photo">
                            <a href="http://portal.unimudasorong.ac.id/" target="_blank" class="hover-effect delegate" title="Portal UNIMUDA">
                                <span class="cover" style="font-size:20px;"><i></i>
                                    <img width="60" height="60" class="image-border blur-up lazyload" src="<?= load_file($app_session['logo']) ?>" alt="<?= $app_session['judul'] ?>">
                                </span>									
                            </a>
                        </div>
                        <div class="article-content">
                            <a href="http://portal.unimudasorong.ac.id/" target="_blank" >
                                <h2><strong>PORTAL UNIMUDA</strong></h2>
                                Layanan Data Universitas Pendidikan Muhammadiyah (UNIMUDA) Sorong
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="widget">
            <h3>Galeri Foto</h3>
            <div class="latest-galleries the-galeri"></div>
            <!-- END .widget -->
        </div>
        <div class="widget">
            <h3>Video</h3>
            <div class="latest-galleries the-video"></div>
            <!-- END .widget -->
        </div>
        <!-- END .main-sidebar -->
    </div>

    <div class="clear-float"></div>

</div>
<script async type="text/javascript">
    $(document).ready(function() {
        //Slide
        $.ajax({
            url: module + "/ajax/type/list/source/artikel",
            type: 'POST', dataType: "json",
            data: { jenis: '', tipe: 'header', order: 'DESC', limit: 5,
                [$('meta[name="csrf-token"]').attr('content')] : $('meta[name="csrf-token"]').attr('accesskey')
            },
            success: function (result) {
                $.each(result.data, function (index, value) {
                    $(".the-slide").append(`<li><div id="slide" class="featured-block">
                        <div class="article-content">
                            <h2><a href="${value.slug}">${value.judul}</a></h2>
                            <span class="meta"><a href="#"><span class="icon-text">🕔 </span>${value.update}</a></span>
                        </div>
                        <div class="article-photo">
                            <a href="${value.slug}" class="delegate"><span class="cover" style="font-size:40px">
                                <img id="img-slide" class="lazyload blur-up" src="${value.foto}">
                            </span></a>
                        </div>
                    </div></li>`);
                });
            },
            error: function (xhr, ajax, err) {
                console.log('Error : ' + xhr.responseText);
            }
        });
        //Pengumuman
        $.ajax({
            url: module + "/ajax/type/list/source/artikel",
            type: 'POST', dataType: "json",
            data: { jenis: 'pengumuman', tipe: '', order: 'DESC', limit: 5,
                [$('meta[name="csrf-token"]').attr('content')] : $('meta[name="csrf-token"]').attr('accesskey')
            },
            success: function (result) {
                $.each(result.data, function (index, value) {
                    $(".the-pengumuman").append(`<li><div class="article-photo">
                        <a href="${value.slug}" class="hover-effect">
                            <img width="60" class="lazyload blur-up" src="${value.foto}" alt="${value.judul}">
                        </a>
                    </div>
                    <div class="article-content">
                        <h4><a href="${value.slug}">${value.judul}</a>
                            <a href="${value.slug}" class="h-comment"><span class="fb-comments-count" data-href="${value.slug}"></span></a>
                        </h4>
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
        //Kegiatan
        $.ajax({
            url: module + "/ajax/type/list/source/artikel",
            type: 'POST', dataType: "json",
            data: { jenis: 'kegiatan', tipe: '', order: 'DESC', limit: 5,
                [$('meta[name="csrf-token"]').attr('content')] : $('meta[name="csrf-token"]').attr('accesskey')
            },
            success: function (result) {
                $.each(result.data, function (index, value) {
                    $(".the-kegiatan").append(`<li><div class="article-photo">
                        <a href="${value.slug}" class="hover-effect">
                            <img width="60" class="lazyload blur-up" src="${value.foto}" alt="${value.judul}">
                        </a>
                    </div>
                    <div class="article-content">
                        <h4><a href="${value.slug}">${value.judul}</a>
                            <a href="${value.slug}" class="h-comment"><span class="fb-comments-count" data-href="${value.slug}"></span></a>
                        </h4>
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
        //Berita
        $.ajax({
            url: module + "/ajax/type/list/source/artikel",
            type: 'POST', dataType: "json",
            data: { jenis: 'berita', tipe: '', order: 'DESC', limit: 10,
                [$('meta[name="csrf-token"]').attr('content')] : $('meta[name="csrf-token"]').attr('accesskey')
            },
            success: function (result) {
                $.each(result.data, function (index, value) {
                    $(".the-berita").append(`<div class="article-big">
                        <div class="article-photo"><a href="${value.slug}" class="hover-effect">
                            <img width="210" class="lazyload blur-up" src="${value.foto}" alt="${value.judul}"></a>
                        </div>
                        <div class="article-content">
                            <h2><a href="${value.slug}">${value.judul}</a>
                                <a href="${value.slug}" class="h-comment"><span class="fb-comments-count" data-href="${value.slug}"></span></a>
                            </h2>
                            <span class="marker" style="font-size:10px;background-color:${value.color}">${value.jenis}</span><br/>
                            <span class="meta">
                                <a href="#"><span class="icon-text">&#128100;</span>${value.log}</a>
                                <a href="#"><span class="icon-text">&#128340;</span>${value.update}</a>
                                <a href="#"><span class="icon-text">👁️</span>${value.view}</a>
                            </span><p>${value.isi}</p>
                            <span class="meta">
                                <a href="${value.slug}" class="more">Selengkapnya<span class="icon-text">&#9656;</span></a>
                            </span>
                        </div></div>`);
                });
            },
            error: function (xhr, ajax, err) {
                console.log('Error : ' + xhr.responseText);
            }
        });
        //Galeri
        $.ajax({
            url: module + "/ajax/type/list/source/galeri",
            type: 'POST', dataType: "json",
            data: { jenis: '0', order: 'DESC', limit: 5,
                [$('meta[name="csrf-token"]').attr('content')] : $('meta[name="csrf-token"]').attr('accesskey')
            },
            success: function (result) {
                $.each(result.data, function (index, value) {
                    $(".the-galeri").append(`<div class="gallery-widget">
                        <div class="gallery-photo" rel="hover-parent">
                            <ul><li><a href="${value.slug}" class="hover-effect">
                                <img width="60" class="lazyload blur-up" src="${value.foto}" alt="${value.judul}">
                            </a></li></ul>
                        </div>
                        <div class="gallery-content">
                            <h4 style="margin-bottom:0px"><a href="${value.slug}">${value.judul}</a></h4>
                            <span class="meta"><span class="right"></span>
                            <a href="#"><span class="icon-text">&#128340;</span>${value.update}</a>
                        </span></div></div>`);
                });
            },
            error: function (xhr, ajax, err) {
                console.log('Error : ' + xhr.responseText);
            }
        });
        //Video
        $.ajax({
            url: module + "/ajax/type/list/source/galeri",
            type: 'POST', dataType: "json",
            data: { jenis: '1', order: 'RANDOM', limit: 3,
                [$('meta[name="csrf-token"]').attr('content')] : $('meta[name="csrf-token"]').attr('accesskey')
            },
            success: function (result) {
                $.each(result.data, function (index, value) {
                    $(".the-video").append(`<div class="gallery-widget">
                        <div class="gallery-photo" rel="hover-parent">
                            <iframe src="${value.foto}" frameborder="0" allowfullscreen></iframe>
                        </div>
                        <div class="gallery-content">
                            <h4 style="margin-bottom:0px"><a href="#">${value.judul}</a></h4>
                            <span class="meta"><span class="right"></span>
                            <a href="#"><span class="icon-text">&#128340;</span>${value.update}</a>
                        </span></div></div>`);
                });
            },
            error: function (xhr, ajax, err) {
                console.log('Error : ' + xhr.responseText);
            }
        });
        //Kutipan
        $.ajax({
            url: module + "/ajax/type/list/source/kutip",
            type: 'POST', dataType: "json",
            data: { order: 'RANDOM', limit: 3,
                [$('meta[name="csrf-token"]').attr('content')] : $('meta[name="csrf-token"]').attr('accesskey')
            },
            success: function (result) {
                $.each(result.data, function (index, value) {
                    $(".the-kutipan").append(`<li><div class="comment-content">
                        <h3>${value.oleh}</h3>
                        <blockquote><p>${value.quote}</p></blockquote>
                    </div></li>`);
                });
            },
            error: function (xhr, ajax, err) {
                console.log('Error : ' + xhr.responseText);
            }
        });
        //Visitor
        $.ajax({
            url: module + "/ajax/type/list/source/visitor",
            type: 'POST', dataType: "json",
            data: {
                [$('meta[name="csrf-token"]').attr('content')] : $('meta[name="csrf-token"]').attr('accesskey')
            },
            success: function (result) {
                $(".today").html(result.data.vtoday);$(".yesterday").html(result.data.vyesterday);
                $(".week").html(result.data.vweek);$(".month").html(result.data.vmonth);
            },
            error: function (xhr, ajax, err) {
                console.log('Error : ' + xhr.responseText);
            }
        });
    });
</script>