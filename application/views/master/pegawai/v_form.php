<?php
$this->load->view('sistem/v_breadcrumb');
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            <?= $title[0] ?>
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                <?= $title[1] ?>
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <?= $this->session->flashdata('notif'); ?>
        </div>
        <div class="col-xs-12">
            <h3 class="lighter center block blue"><?= $title[1] ?></h3>
            <form id="validation-form" action="<?= site_url($action); ?>" name="form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">NIK :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <input <?= empty($edit['id_pegawai']) ? '':'readonly' ?> value="<?= $edit['nik'] ?>" type="text" name="nik" id="nik" placeholder="NIK" class="col-xs-12 col-sm-6 input-sm"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Nama Lengkap :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['nama'] ?>" type="text" name="nama" id="nama" placeholder="Nama Lengkap" class="col-xs-12 col-sm-6 input-sm"/>
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                    <div class="col-md-offset-4 col-md-5">
                        <button class="btn" type="reset">
                            <i class="ace-icon fa fa-undo bigger-110"></i>
                            Batal
                        </button>
                        &nbsp; &nbsp; &nbsp;
                        <button class="btn btn-success" name="simpan" id="simpan" type="submit">
                            <i class="ace-icon fa fa-check"></i>
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div><!-- /.row -->
</div><!-- /.page-content -->
<?php load_js(array("theme/aceadmin/assets/js/jquery.validate.js")); ?>
<script type="text/javascript">
    $(document).ready(function () {
        validateForm();
    });
    function validateForm() {
        jsfValidate("#validation-form", {
            nama: { required: true, minlength: 5 },
            nik: { required: true, digits:true, minlength: 5 }
        });
    }
</script>