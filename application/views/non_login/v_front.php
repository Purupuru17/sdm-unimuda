<div class="page-content">
    <div class="center">
        <img class="blur-up lazyload" width="80" src="<?= load_file($app_session['logo']) ?>" />
        <img class="blur-up lazyload" width="83" src="<?= load_file('private/tut-wuri.png') ?>" />
        <img class="blur-up lazyload" width="200" src="<?= load_file('private/pddikti.png') ?>" />
        <div class="space-4"></div>
        <span class="bolder bigger-150 grey"><?= $app_session['deskripsi'] ?></span>
        <div class="space-4"></div>
        <a href="<?= site_url('login') ?>" class="btn btn-sm btn-primary btn-white btn-bold">
            <i class="ace-icon fa fa-home bigger-150 middle orange"></i>
            <span class="bigger-110">Masuk ke Sistem</span>

            <i class="icon-on-right ace-icon fa fa-arrow-right"></i>
        </a>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <div class="space-24"></div>
            <div class="widget-box transparent">
                <div class="widget-header">
                    <marquee>
                        <h4 class="widget-title lighter blue">
                            <i class="ace-icon fa fa-bullhorn"></i>
                            Informasi Penting! Mohon diperhatikan terlebih dahulu.
                        </h4>
                    </marquee>
                </div>
                <div class="widget-body">
                    <div class="widget-main padding-10 no-padding-left no-padding-right">
                        <div class="tabbable">
                            <ul class="nav nav-tabs padding-18">
                                <li class="">
                                    <a class="" data-toggle="tab" href="#tab0">
                                        <i class="ace-icon fa fa-newspaper-o purple"></i>
                                        Berita & Pengumuman
                                    </a>
                                </li>
                                <?php
                                $no = 1;
                                foreach ($page['data'] as $value) {
                                ?>
                                <li class="<?= $no == 1 ? 'active bolder':'' ?>">
                                    <a class="<?= $no == 1 ? 'red':''?>" data-toggle="tab" href="#tab<?=$no?>">
                                        <i class="ace-icon fa <?= $no == 1 ? 'fa-exclamation-circle bigger-130':'blue fa-star-half-o' ?> bigger-120"></i>
                                        <?= ctk($value['judul_page']) ?>
                                    </a>
                                </li>
                                <?php $no++; } ?>
                            </ul>
                            <div class="tab-content no-border padding-18">
                                <?php
                                $nor = 1;
                                foreach ($page['data'] as $value) {
                                ?>
                                <div id="tab<?=$nor?>" class="tab-pane <?= $nor == 1 ? 'in active':'' ?>">
                                    <?= ($value['isi_page']) ?>
                                </div>
                                <?php $nor++; } ?>
                                <div id="tab0" class="tab-pane">
                                    <ul class="ace-thumbnails clearfix">
                                        <?php
                                        foreach ($artikel['data'] as $val):
                                        ?>
                                        <li>
                                            <a target="_blank" href="<?= site_url('artikel/'.$val['slug_artikel']) ?>" title="<?= $val['judul_artikel'] ?>" data-rel="colorbox">
                                                <img class="blur-up lazyload" width="150" height="150" src="<?= load_file($val['foto_artikel']) ?>" />
                                                <div class="text">
                                                    <div class="inner"><?= $val['judul_artikel'] ?></div>
                                                </div>
                                            </a>
                                            <div class="tags">
                                                <span class="label-holder">
                                                    <span class="label" style="background-color:<?= $val['color_jenis'] ?>"><?= $val['judul_jenis'] ?></span>
                                                </span>
                                            </div>
                                        </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.row -->
</div><!-- /.page-content -->
<div id="modal-view" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="table-header web-color">
                    <button id="btn-close" type="button" class="close" aria-hidden="true">
                        <span class="white">&times;</span>
                    </button>
                    <div id="txt-title" align="center" class="bolder bigger-110">
                        <i class="ace-icon fa fa-bullhorn"></i>  &nbsp;Informasi Penting !
                    </div>
                </div>
            </div>
            <div class="modal-body padding-10 table-responsive">
                <div id="txt-view" style="height: 400px; overflow: visible; overflow-y: scroll;"></div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<input type="hidden" id="level" value="<?= $this->session->userdata('level') ?>">
<script type="text/javascript">
    $(document).ready(function () {
        $("#sidebar").hide().removeClass('sidebar');
        $("#menu-toggler, .navbar-brand").hide();
        if($("#level").val() !== '1'){
            $("#modal-view").modal({show: true,keyboard: false,backdrop: "static"});
            $("#txt-view").html($("#tab1").html() + '<div class="space-10"></div>');
        }
    });
    $("#btn-close, #modal-view").click(function () {
        $("#txt-title").html('<small><i class="fa fa-spinner fa-spin fa-fw fa-2x"></i> Informasi ini otomatis tertutup setelah 3 detik</small>');
        setInterval(function() {
            $("#modal-view").modal('hide');
        }, 3000);
    });
</script>