<?php
$this->load->view('sistem/v_breadcrumb');
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            <?= $title[0] ?>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <?= $this->session->flashdata('notif'); ?>
        </div>
        <div class="col-xs-12 center <?= ($this->session->userdata('groupid') != '4') ? 'hide':'' ?>">
            <h4 class="header smaller orange">Presensi Kehadiran</h4>
            <p></p>
            <a href="<?= site_url('presensi/aktivitas/add') ?>" class="btn btn-success btn-app radius-4">
                <i class="ace-icon fa fa-exchange bigger-230"></i>
                Aktivitas
                <span class="badge badge-danger">&nbsp;<i class="fa fa-map-marker"></i>&nbsp;</span>
            </a>
            <a href="<?= site_url('presensi/kegiatan/add') ?>" class="btn btn-app btn-primary no-radius">
                <i class="ace-icon fa fa-flag-checkered bigger-230"></i>
                Kegiatan
                <span class="badge badge-purple"><i class="fa fa-users"></i></span>
            </a>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.page-content -->