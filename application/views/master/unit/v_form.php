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
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right">Nama :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['nama_unit'] ?>" type="text" name="nama" id="nama" placeholder="Nama Unit" class="col-xs-12 col-sm-6" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right">Kode :</label>
                    <div class="col-xs-12 col-sm-2">
                        <div class="clearfix">
                            <input value="<?= $edit['kode_unit'] ?>" type="text" name="kode" id="kode" placeholder="Kode Unit" class="col-xs-12 col-sm-6"/>
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                    <div class="col-md-offset-5 col-md-5">
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

<?php 
    load_js(array(
        "theme/aceadmin/assets/js/jquery.validate.js"
    )); 
?>
<script type="text/javascript">
    $(document).ready(function () {
        validateForm();
    });
    function validateForm() {
        jsfValidate("#validation-form", {
            nama: { required: true, minlength: 5 },
            kode: { digits: true, minlength: 4, maxlength: 5 }
        });
    }
</script>