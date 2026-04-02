<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Basic page needs ============================================ -->
        <?php
            $param = $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
            $meta = isset($meta) ? $meta : [];
            $meta_title_default = $app_session['judul'];
            $meta_desc_default = $app_session['deskripsi'];
            $meta_author_default = $app_session['cipta'];
            $meta_url_default = current_url() . $param;
            $meta_img_default  = base_url($app_session['logo']);
        ?>
        <title><?php echo element('title', $meta, $meta_title_default).' | '.$app_session['judul']; ?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="Copyright" content="<?php echo element('author', $meta, $meta_author_default); ?>" />
        
        <!-- Mobile on Android -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, shrink-to-fit=no">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="theme-color" content="<?= $app_theme['webcolor'] ?>" />
        <meta name="application-name" content="<?php echo element('title', $meta, $meta_title_default).' | '.$app_session['judul']; ?>">
        <meta name="msapplication-navbutton-color" content="<?= $app_theme['webcolor'] ?>">   
        <!-- Mobile on iOS -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="<?= $app_theme['webcolor'] ?>">
        <meta name="apple-mobile-web-app-title" content="<?php echo element('title', $meta, $meta_title_default).' | '.$app_session['judul']; ?>">   
        
        <link rel="shortcut icon" type="image/x-icon" href="<?= load_file('private/logo.png') ?>"/>  
        <link rel="manifest" href="<?= base_url('manifest.json') ?>">
        <link rel="canonical" href="<?php echo element('url', $meta, $meta_url_default); ?>">
        <link rel="amphtml" href="<?php echo element('amp_url', $meta, $meta_url_default); ?>">
        
        <!-- SEARCH ENGINE -->
        <meta name="keywords" content="<?php echo element('title', $meta, $meta_title_default).' | '.$app_session['judul']; ?>" />
        <meta name="description" content="<?php echo limit_text(element('description', $meta, $meta_desc_default),200); ?>">
        <meta name="author" content="<?php echo element('author', $meta, $meta_author_default); ?>">
        <meta name="rating" content="general">
        
        <meta itemprop="name" content="<?php echo element('title', $meta, $meta_title_default).' | '.$app_session['judul']; ?>" />
        <meta itemprop="description" content="<?php echo limit_text(element('description', $meta, $meta_desc_default),200); ?>" />
        <meta itemprop="image" content="<?php echo element('thumbnail', $meta, $meta_img_default); ?>" />

        <!-- FACEBOOK META -  Change what to your own FB-->
        <meta property="fb:app_id" content="MY_FB_ID">
        <meta property="fb:pages" content="MY_FB_FAGE_ID" />
        <meta property="og:title" content="<?php echo element('title', $meta, $meta_title_default).' | '.$app_session['judul']; ?>">
        <meta property="og:type" content="article">
        <meta property="og:url" content="<?php echo element('url', $meta, $meta_url_default); ?>">
        <meta property="og:site_name" content="<?php echo element('author', $meta, $meta_author_default); ?>">
        <meta property="og:image" content="<?php echo element('thumbnail', $meta, $meta_img_default); ?>" >
        <meta property="og:description" content="<?php echo limit_text(element('description', $meta, $meta_desc_default),200); ?>">
        
        <meta property="article:publisher" content="<?php echo element('author', $meta, $meta_author_default); ?>">
        <meta property="article:author" content="<?php echo element('author', $meta, $meta_author_default); ?>">
        <meta property="article:tag" content="<?php echo element('title', $meta, $meta_title_default).' | '.$app_session['judul']; ?>">

        <!-- TWITTER META - Change what to your own twitter-->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:description" content="<?php echo limit_text(element('description', $meta, $meta_desc_default),200); ?>">
        <meta name="twitter:site" content="<?php echo element('author', $meta, $meta_author_default); ?>">
        <meta name="twitter:creator" content="@my_twitter">
        <meta name="twitter:title" content="<?php echo element('title', $meta, $meta_title_default).' | '.$app_session['judul']; ?>">
        <meta name="twitter:image:src" content="<?php echo element('thumbnail', $meta, $meta_img_default); ?>"> 
        <meta name="twitter:domain" content="<?php echo element('url', $meta, $meta_url_default); ?>" />
        
        <meta name="csrf-token" content="<?= $this->security->get_csrf_token_name() ?>" accesskey="<?= $this->security->get_csrf_hash() ?>">
        <?php
        load_css(array(
            "theme/aceadmin/puru.css",
            "theme/aceadmin/assets/fonts/poppins/font.css?family=Poppins:300,400,500,600,700",
            
            "theme/edu/css/reset.css",
            "theme/edu/css/main-stylesheet.css",
            "theme/edu/css/shortcode.css",
            "theme/edu/css/fonts.css",
            "theme/edu/css/colors.css",
            "theme/edu/css/responsive.css",
            "theme/edu/css/ppg.css"
        ));
        load_js(array(
            "theme/aceadmin/assets/js/lazy/lazysizes.min.js",
            "theme/edu/jscript/jquery-latest.min.js",
            "theme/edu/jscript/theme-scripts.js"
        ));
        ?>
        <!-- Web Font -->
        <style type="text/css">
            body { font-family:'CircularStd' !important; font-size: 13px !important;}
            .hide { display: none}
            .main-menu .the-menu li:hover > ul { display: block; z-index: 201; }
        </style>
    </head>

    <!-- BEGIN body -->
    <body>

        <!-- BEGIN .boxed -->
        <div class="boxed">

            <!-- BEGIN .header -->
            <div class="header">
                <?php $this->load->view('home/'.APP_THEME.'/h_header'); ?>
            </div>

            <!-- BEGIN .content -->
            <div class="content">
                
                <div id="loader-wrapper">
                    <div id="loader"></div>
                    <div class="loader-section section-left"></div>
                    <div class="loader-section section-right"></div>
                </div>
                <!-- BEGIN .wrapper -->
                <div class="wrapper">

                    <div class="breaking-news">
                        <span class="the-title">Breaking News</span>
                        <ul class="the-breaking"></ul>
                    </div>
                    <style>
                        #my-slide .slide_container{
                            height: 570px !important;
                        }
                        #my-slide.breaking-news:after{
                            position:initial !important;
                        }
                        #slide.featured-block .article-content {
                            background: rgba(0,0,0,0.6);
                            position: absolute;
                            z-index: 1;
                            color: #fff;
                            width: auto;
                            bottom: auto;
                            top:20px;
                        }
                        #img-slide{
                            width: 1260px; 
                        }
                        @media only screen and (max-width: 600px) {
                            #my-slide .slide_container{
                                height: 270px !important;
                            }
                            #img-slide{
                                width: 500px; 
                            }
                            .header-logo{
                                text-align: center;
                            }
                        }
                    </style>
                    <div id="my-slide" class="breaking-news img" style="background: none;display:<?= ($this->uri->segment(1) === '' || is_null($this->uri->segment(1))) ? 'block' : 'none' ?>">
                        <ul class="the-slide"></ul>
                    </div>
                    <?= $content ?>
                </div>
            </div>

            <!-- BEGIN .footer -->
            <div class="footer">
                <?php $this->load->view('home/'.APP_THEME.'/h_footer'); ?>
            </div>

            <!-- END .boxed -->
        </div>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/UpUp/1.0.0/upup.min.js"></script>
        <script async type="text/javascript">
            const module = "<?= site_url($module) ?>";
            $(document).ready(function() {
                jsfAutoload();
                jsfTheme();
            });
            function jsfAutoload() {
                //Visitor
                $.ajax({
                    url: "<?= site_url() ?>" + 
                        "non_login/login/ajax/type/action/source/autoload",
                    type: 'POST', dataType: "json",
                    data: {
                        id: '',
                        page_url: window.location.href,
                        referrer: document.referrer,
                        page_name: window.location.pathname.replace(/^\/+|\/+$/g, ''),
                        query_string: window.location.search.replace(/^\?/, '')
                    },
                    success: function (rs) {
                        console.log('Visited')
                    },
                    error: function (xhr, ajax, err) {
                        console.log('Error : ' + xhr.responseText);
                    }
                });
                //Navigasi Menu
                $.ajax({
                    url: module + "/ajax/type/list/source/menu",
                    type: 'POST', dataType: "json",
                    data: {
                        [$('meta[name="csrf-token"]').attr('content')] : $('meta[name="csrf-token"]').attr('accesskey')
                    },
                    success: function (rs) {
                        //Menu
                        let menuHTML = '<li><a href="<?= site_url() ?>">Beranda</a></li>';
                        const parentMenus = rs.data.filter(menu => menu.parent_nav == 0);
                        parentMenus.forEach(parentMenu => {
                            const subMenus = rs.data.filter(menu => menu.parent_nav == parentMenu.id_nav);
                            if (subMenus.length > 0) {
                                menuHTML += `<li><a href="${parentMenu.url_nav}"><span>${parentMenu.judul_nav}</span></a>
                                        <ul>${subMenus.map(subMenu => `<li><a href="${subMenu.url_nav}">${subMenu.judul_nav}</a></li>`).join('')}</ul>
                                    </li>`;
                            } else {
                                menuHTML += `<li><a href="${parentMenu.url_nav}">${parentMenu.judul_nav}</a></li>`;
                            }
                        });
                        $(".the-menu").html(menuHTML);
                        $("div.themenumobile > ul").html(menuHTML);
                    },
                    error: function (xhr, ajax, err) {
                        console.log('Error : ' + xhr.responseText);
                    }
                });
                //Breaking News
                $.ajax({
                    url: module + "/ajax/type/list/source/artikel",
                    type: 'POST', dataType: "json",
                    data: { jenis: '', tipe: 'header', order: '', limit: 10,
                        [$('meta[name="csrf-token"]').attr('content')] : $('meta[name="csrf-token"]').attr('accesskey')
                    },
                    success: function (result) {
                        $.each(result.data, function (index, value) {
                            $(".the-breaking").append(`<li><a href="${value.slug}">${value.judul}</a></li>`);
                        });
                    },
                    error: function (xhr, ajax, err) {
                        console.log('Error : ' + xhr.responseText);
                    }
                });
                //Jenis
                $.ajax({
                    url: module + "/ajax/type/list/source/jenis",
                    type: 'POST', dataType: "json",
                    data: {
                        [$('meta[name="csrf-token"]').attr('content')] : $('meta[name="csrf-token"]').attr('accesskey')
                    },
                    success: function (result) {
                        $.each(result.data, function (index, value) {
                            $(".the-category").append(`<li><a href="${value.slug_jenis}">${value.judul_jenis} (${value.total_artikel})</a></li>`);
                        });
                    },
                    error: function (xhr, ajax, err) {
                        console.log('Error : ' + xhr.responseText);
                    }
                });
            }
            function jsfTheme() {
                const color_utama = "<?= $app_theme['webcolor'] ?>";
                const color_kedua = "<?= $app_theme['webcolor_other'] ?>";
                const filesToCache = [
                    "theme/aceadmin/puru.css",
                    "theme/aceadmin/assets/fonts/poppins/font.css?family=Poppins:300,400,500,600,700",
                    "theme/aceadmin/assets/js/lazy/lazysizes.min.js",

                    "theme/edu/css/reset.css",
                    "theme/edu/css/main-stylesheet.css",
                    "theme/edu/css/shortcode.css",
                    "theme/edu/css/fonts.css",
                    "theme/edu/css/colors.css",
                    "theme/edu/css/responsive.css",
                    "theme/edu/css/ppg.css",
                    "theme/edu/jscript/jquery-latest.min.js",
                    "theme/edu/jscript/theme-scripts.js"
                ];
                UpUp.start({
                    'cache-version': '<?= APP_VER ?>', 'content-url': '<?= site_url() ?>',
                    'content': 'No Internet Connection',
                    'service-worker-url': "<?= base_url('sw.js') ?>", 'assets': filesToCache
                });
                //Theme
                $("body, .header, .footer, #loader-wrapper .loader-section").css("background", color_utama);
                setTimeout(function () {
                    $('body').addClass('loaded');
                    
                    $(".breaking-news .the-title, .widget > h3, .button, .hover-effect, .block-title, #wp-calendar td#today, .small-button, #writecomment p input[type=submit]").css("background-color", color_kedua);
                    $(".widget .meta a, .mobile-menu, .viewer").css("color", color_kedua);
                    $(".main-menu.sticky").css("background", color_kedua);
                    $("h2 > a, h4 > a, h3 > a, a > h2").hover(function(){
                        $(this).css("color", color_kedua);
                        }, function(){
                        $(this).css("color", "#000");
                    });
                }, 1000);
                
                //Waktu
                setInterval(function () {
                    now = new Date();
                    if (now.getTimezoneOffset() == 0)
                        (a = now.getTime() + (7 * 60 * 60 * 1000))
                    else
                        (a = now.getTime());
                    now.setTime(a);
                    var tahun = now.getFullYear()
                    var hari = now.getDay()
                    var bulan = now.getMonth()
                    var tanggal = now.getDate()
                    var hariarray = new Array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu")
                    var bulanarray = new Array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember")
                
                    var waktu = hariarray[hari] + ", " + tanggal + " " + bulanarray[bulan] + " " + tahun + " | " + (((now.getHours() < 10) ? "0" : "") + now.getHours() + ":" + ((now.getMinutes() < 10) ? "0" : "") + now.getMinutes() + ":" + ((now.getSeconds() < 10) ? "0" : "") + now.getSeconds() + (" WIT "));
                    $(".city").html(waktu);
                }, 1000);
            }
        </script>
    </body>
</html>
