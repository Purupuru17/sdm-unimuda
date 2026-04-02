<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            $param = $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
            $meta = isset($meta) ? $meta : [];
            $meta_title_default = $app_session['judul'] .' | '.ctk($app_session['deskripsi']);
            $meta_desc_default = ctk($app_session['deskripsi']);
            $meta_author_default = $app_session['judul'];
            $meta_url_default  = current_url() . $param;
            $meta_img_default  = base_url($app_session['logo']);  
        ?>
        <!-- Basic page needs ============================================ -->
        <title><?= element('title', $meta, $meta_title_default); ?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, shrink-to-fit=no">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        
        <!-- SEARCH ENGINE -->
        <meta name="keywords" content="<?php echo ctk(element('title', $meta, $meta_title_default)); ?>" />
        <meta name="description" content='<?php echo ctk(element('description', $meta, $meta_desc_default)); ?>'>
        <meta name="author" content="<?php echo element('author', $meta, $meta_author_default); ?>">
        <link rel="canonical" href="<?php echo element('url', $meta, $meta_url_default); ?>">
        <link rel="amphtml" href="<?php echo element('amp_url', $meta); ?>">
        <meta name="rating" content="general">
        
        <!-- Favicon -->
        <link rel="shortcut icon" href="<?= load_file('private/logo.png') ?>">
        
        <?php
        load_css(array(
            'theme/aceadmin/assets/css/bootstrap.css',
            'theme/aceadmin/assets/css/font-awesome.css',
            'theme/aceadmin/assets/css/ace-fonts.css',
            'theme/aceadmin/assets/css/ace-rtl.css',
            'theme/aceadmin/puru.css',
            'theme/aceadmin/assets/css/jquery.gritter.css',
        ));
        load_js(array(
            'theme/aceadmin/assets/js/ace-extra.js',
            'theme/aceadmin/assets/js/jquery.js',
            'theme/aceadmin/assets/js/bootstrap.js',
            'theme/aceadmin/assets/js/ace/elements.fileinput.js',
            'theme/aceadmin/assets/js/ace/ace.js',
            'theme/aceadmin/assets/js/jquery.gritter.js',
            'theme/aceadmin/assets/js/lazy/lazysizes.min.js',
        ));
        ?>
        <!-- ace styles -->
        <link rel="stylesheet" href="<?= base_url('theme/aceadmin/assets/css/ace.css') ?>" class="ace-main-stylesheet" id="main-ace-style" />

    </head>

    <body class="login-layout">
        <div class="main-container">
            <div class="main-content">
                <?= $content ?>
            </div><!-- /.main-content -->
        </div><!-- /.main-container -->
        <script type="text/javascript">
            if ('ontouchstart' in document.documentElement)
                document.write("<script src='<?= base_url('theme/aceadmin/assets/js/jquery.mobile.custom.js') ?>'>" + "<" + "/script>");
            
            $(document).ready(function() {
                var login = "<?= $app_theme['login'] ?>";
                if (login === "1") {
                    $('body').attr('class', 'login-layout');
                    $('#id-text2').attr('class', 'white');
                    $('#id-company-text').attr('class', 'blue');
                } else if (login === "2") {
                    $('body').attr('class', 'login-layout blur-login');
                    $('#id-text2').attr('class', 'white');
                    $('#id-company-text').attr('class', 'light-blue');
                } else {
                    $('body').attr('class', 'login-layout light-login');
                    $('#id-text2').attr('class', 'red');
                    $('#id-company-text').attr('class', 'blue');
                }
            });
        </script>
    </body>
</html>
