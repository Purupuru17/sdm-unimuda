<?php
$this->load->view('sistem/v_breadcrumb');
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            <?= $this->session->userdata('name') ?>
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
            <h3 class="lighter center block orange"><?= $title[1] ?></h3>
            <form id="validation-form" action="<?= site_url($action); ?>" name="form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Password Lama :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <input type="password" name="lama" id="lama" class="col-xs-12  col-sm-6" placeholder="Password Lama" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Password Baru :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <input type="password" name="baru" id="baru" class="col-xs-12  col-sm-6" placeholder="Password Baru" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Konfirmasi Password Baru :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <input type="password" name="confirm" id="confirm" class="col-xs-12  col-sm-6" placeholder="Konfirmasi Password" />
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                    <div class="col-md-offset-4 col-md-4">
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
        validate_form();
    });
    function validate_form() {
        jsfValidate("#validation-form", {
            lama: {
                required: true,
                minlength: 5
            },
            baru: {
                required: true,
                minlength: 5
            },
            confirm: {
                required: true,
                minlength: 5,
                equalTo: "#baru"
            }
        });
    }
</script>                    
