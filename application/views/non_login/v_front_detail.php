<div class="page-content">
    <div class="page-header">
        <a href="<?= site_url('login') ?>" class="btn btn-sm btn-primary btn-white btn-bold">
            <i class="ace-icon fa fa-home bigger-150 middle orange"></i>
            <span class="bigger-110">Masuk ke Sistem</span>

            <i class="icon-on-right ace-icon fa fa-arrow-right"></i>
        </a>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <div class="widget-box transparent">
                <div class="widget-header">
                    <h4 class="widget-title" style="color:<?= $detail['color_jenis'] ?>">
                        <i class="ace-icon <?= $detail['icon_jenis'] ?>"></i>
                        <?= $detail['judul_jenis'] ?>
                    </h4>
                    <div class="widget-toolbar">
                        <a href="#" data-action="collapse" class="">
                            <i class="ace-icon fa fa-chevron-up bigger-125"></i>
                        </a>
                    </div>
                    <div class="widget-toolbar no-border">
                        <div class="btn-group btn-overlap">
                            <a href="whatsapp://send?text=<?= site_url(uri_string()) ?>" 
                               class="btn btn-white btn-success btn-sm btn-bold">
                                <i class="fa fa-whatsapp bigger-120"></i>
                                Whatsapp
                            </a>
                            <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?= site_url(uri_string()) ?>" 
                               class="btn btn-white btn-primary btn-sm btn-bold">
                                <i class="fa fa-facebook bigger-120"></i>
                                Facebook
                            </a>
                            <a target="_blank" href="https://twitter.com/intent/tweet?text=<?= site_url(uri_string()) ?>" 
                               class="btn btn-white btn-info btn-sm btn-bold">
                                <i class="fa fa-twitter bigger-120"></i>
                                Twitter
                            </a>
                        </div>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="widget-main padding-6 no-padding-left no-padding-right">
                        <div class="col-xs-12 col-sm-4 center">
                            <span class="profile-picture">
                                <img src="<?= load_file($detail['foto_artikel']) ?>" id="avatar" class="img-responsive blur-up lazyload" />
                            </span>
                        </div><!-- /.col -->
                        <div class="col-xs-12 col-sm-8">
                            <span class="middle bolder bigger-150">
                                <?= $detail['judul_artikel'] ?>
                            </span>
                            <div id="user-profile-1" class="user-profile">
                                <?= $detail['isi_artikel'] ?>
                            </div>
                        </div><!-- /.col -->
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.row -->
</div><!-- /.page-content -->
<script type="text/javascript" async src="https://platform.twitter.com/widgets.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#sidebar").hide().removeClass('sidebar');
        $("#menu-toggler, .navbar-brand").hide();
    });
</script>