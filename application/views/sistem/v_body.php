<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            $param = $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
            $meta = isset($meta) ? $meta : [];
            $meta_title_default = isset($breadcrumb) ? breadcrumb($breadcrumb, 'title') : ctk($app_session['deskripsi']);
            $meta_desc_default = ctk($app_session['deskripsi']);
            $meta_author_default = $app_session['judul'];
            $meta_url_default  = current_url() . $param;
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
        <?php
            load_css(array(
                'theme/aceadmin/assets/css/bootstrap.css',
                'theme/aceadmin/assets/css/font-awesome.css',
                'theme/aceadmin/assets/css/select2.css',
                'theme/aceadmin/assets/css/jquery.gritter.css',
                'theme/aceadmin/assets/css/datepicker.css',
                'theme/aceadmin/assets/css/colorpicker.css',
                'theme/aceadmin/assets/css/ace-fonts.css',
                'theme/aceadmin/assets/fonts/poppins/font.css?family=Poppins:300,400,500,600,700',
                
                'theme/aceadmin/puru.css',
                
                'theme/shop/css/font-awesome/css/font-awesome.min.css'
            ));
            load_js(array(
                'theme/aceadmin/assets/js/ace-extra.js',
                'theme/aceadmin/assets/js/jquery.js'
            ));
        ?>
        <link rel="stylesheet" href="<?= base_url('theme/aceadmin/assets/css/ace.css') ?>" class="ace-main-stylesheet" id="main-ace-style" />
    </head>
    <body class="no-skin">
        <!-- #section:basics/navbar.layout -->
        <?php
        $this->load->view('sistem/v_header');
        ?>
        <!-- /section:basics/navbar.layout -->
        <div class="main-container" id="main-container">
            <script type="text/javascript">
                try {
                    ace.settings.check('main-container', 'fixed');
                } catch (e) {
                }
            </script>
            <!-- #section:basics/sidebar -->
            <?php
            $this->load->view('sistem/v_sidebar');
            ?>
            <!-- /section:basics/sidebar -->
            <div class="main-content">
                <div class="main-content-inner">
                    <?= $content ?>
                </div>
            </div><!-- /.main-content -->
            <?php
            $this->load->view('sistem/v_footer');
            ?>
        </div><!-- /.main-container -->

        <?php
            load_js(array(
                'theme/aceadmin/assets/js/bootstrap.js',
                'theme/aceadmin/assets/js/jquery.gritter.js',
                'theme/aceadmin/assets/js/lazy/lazysizes.min.js',
                'theme/aceadmin/assets/js/bootbox.min.js',
                'theme/aceadmin/sweetalert.min.js',
                'theme/aceadmin/puru.js',
                
                'theme/aceadmin/assets/js/ace/elements.scroller.js',
                'theme/aceadmin/assets/js/ace/elements.colorpicker.js',
                'theme/aceadmin/assets/js/ace/elements.fileinput.js',
                'theme/aceadmin/assets/js/ace/elements.aside.js',
                'theme/aceadmin/assets/js/ace/ace.js',
                'theme/aceadmin/assets/js/ace/ace.ajax-content.js',
                'theme/aceadmin/assets/js/ace/ace.touch-drag.js',
                'theme/aceadmin/assets/js/ace/ace.sidebar.js',
                'theme/aceadmin/assets/js/ace/ace.sidebar-scroll-1.js',
                'theme/aceadmin/assets/js/ace/ace.submenu-hover.js',
                'theme/aceadmin/assets/js/ace/ace.widget-box.js',
                'theme/aceadmin/assets/js/ace/ace.settings.js',
                'theme/aceadmin/assets/js/ace/ace.settings-skin.js',
                'theme/aceadmin/assets/js/ace/ace.widget-on-reload.js'
            ));
        ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/UpUp/1.0.0/upup.min.js"></script>
        <script async type="text/javascript">
            $(document).on('click', '.unread', function() {
                jsfAutoload($(this).attr("id"));
            });
            $(function() {
                setTimeout(function () {
                    jsfAutoload();
                }, 5000);
                jsfTheme();
            });
            function jsfTheme() {
                const filesToCache = [
                    'theme/aceadmin/assets/css/bootstrap.css',
                    'theme/aceadmin/assets/css/font-awesome.css',
                    'theme/aceadmin/assets/css/select2.css',
                    'theme/aceadmin/assets/css/jquery.gritter.css',
                    'theme/aceadmin/assets/css/datepicker.css',
                    'theme/aceadmin/assets/css/colorpicker.css',
                    'theme/aceadmin/assets/css/ace-fonts.css',
                    'theme/aceadmin/assets/fonts/poppins/font.css?family=Poppins:300,400,500,600,700',
                    'theme/aceadmin/assets/css/ace.css',

                    'theme/aceadmin/puru.css',
                    'private/logo.png',

                    'theme/shop/css/font-awesome/css/font-awesome.min.css',

                    'theme/aceadmin/assets/js/ace-extra.js',
                    'theme/aceadmin/assets/js/jquery.js',

                    'theme/aceadmin/assets/js/bootstrap.js',
                    'theme/aceadmin/assets/js/jquery.gritter.js',
                    'theme/aceadmin/assets/js/lazy/lazysizes.min.js',
                    'theme/aceadmin/assets/js/bootbox.min.js',
                    'theme/aceadmin/sweetalert.min.js',
                    'theme/aceadmin/puru.js',

                    'theme/aceadmin/assets/js/dataTables/jquery.dataTables.js',
                    'theme/aceadmin/assets/js/dataTables/jquery.dataTables.bootstrap.js',

                    'theme/aceadmin/assets/js/ace/elements.scroller.js',
                    'theme/aceadmin/assets/js/ace/elements.colorpicker.js',
                    'theme/aceadmin/assets/js/ace/elements.fileinput.js',
                    'theme/aceadmin/assets/js/ace/elements.aside.js',
                    'theme/aceadmin/assets/js/ace/ace.js',
                    'theme/aceadmin/assets/js/ace/ace.ajax-content.js',
                    'theme/aceadmin/assets/js/ace/ace.touch-drag.js',
                    'theme/aceadmin/assets/js/ace/ace.sidebar.js',
                    'theme/aceadmin/assets/js/ace/ace.sidebar-scroll-1.js',
                    'theme/aceadmin/assets/js/ace/ace.submenu-hover.js',
                    'theme/aceadmin/assets/js/ace/ace.widget-box.js',
                    'theme/aceadmin/assets/js/ace/ace.settings.js',
                    'theme/aceadmin/assets/js/ace/ace.settings-skin.js',
                    'theme/aceadmin/assets/js/ace/ace.widget-on-reload.js'
                ];
                UpUp.start({
                    'cache-version': '<?= APP_VER ?>', 'content-url': '<?= site_url() ?>',
                    'content': 'No Internet Connection',
                    'service-worker-url': "<?= base_url('sw.js') ?>", 'assets': filesToCache
                });
                
                //template
                const skin_class = "<?= $app_theme['theme'] ?>";
                const navbar = "<?= $app_theme['navbar'] ?>";
                const sidebar = "<?= $app_theme['sidebar'] ?>";
                const bread = "<?= $app_theme['bread'] ?>";
                const container = "<?= $app_theme['container'] ?>";
                const hover = "<?= $app_theme['hover'] ?>";
                const compact = "<?= $app_theme['compact'] ?>";
                const horizontal = "<?= $app_theme['horizontal'] ?>";
                const altitem = "<?= $app_theme['altitem'] ?>";
                //navigation
                jsfNavigation(skin_class, navbar,sidebar,bread,container,hover,compact,horizontal,altitem);
            }
            function jsfAutoload(id = '') {
                (async function() {
                    try {
                        const site_url = "<?= site_url() ?>";
                        const rs = await jsfRequest(
                            site_url + "non_login/login/ajax/type/action/source/autoload","POST",
                            {
                                id: id,
                                page_url: window.location.href,
                                referrer: document.referrer,
                                page_name: window.location.pathname.replace(/^\/+|\/+$/g, ''),
                                query_string: window.location.search.replace(/^\?/, '')
                            }
                        );
                        if (rs.status) {
                            $("#li-notif").html(rs.html);
                        }
                        $("span#item-notif").html(rs.item);
                        $("span#new-notif").html(rs.item);
                    } catch (err) {
                        console.error("jsf_autoload : ", err);
                    }
                })();
            }
        </script>
    </body>
</html>