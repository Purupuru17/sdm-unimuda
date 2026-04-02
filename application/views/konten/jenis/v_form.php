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
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Jenis Artikel :</label>
                    <div class="col-xs-12 col-sm-8">
                        <div class="clearfix">
                            <input value="<?= $edit['judul_jenis'] ?>" type="text" name="judul" id="judul" class="col-xs-12  col-sm-6" placeholder="Jenis Artikel" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Warna Background :</label>
                    <div class="col-xs-12 col-sm-8">
                        <div class="clearfix">
                            <!-- <div class="bootstrap-colorpicker"> -->
                            <input name="color" value="<?= $edit['color_jenis'] ?>" id="color" type="text" class="col-xs-12  col-sm-3 color-pick" placeholder="Warna Background" />
                            <!-- </div> -->
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Icon :</label>
                    <div class="col-xs-12 col-sm-8">
                        <div class="clearfix">
                            <input value="<?= $edit['icon_jenis'] ?>" type="text" name="icon" id="icon" class="col-xs-12  col-sm-6" placeholder="Icon" />
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                    <div class="col-md-offset-5 col-md-4">
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
        "theme/aceadmin/assets/js/jquery.validate.js",
        "theme/aceadmin/assets/js/bootstrap-colorpicker.js"
    )); 
?>
<script type="text/javascript">
    $('#validation-form').validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        ignore: "",
        rules: {
            judul: {
                required: true,
                minlength: 5
            },
            color: {
                required: true
            },
            icon: {
                required: true
            }
        },
        messages: {
            judul: {
                required: "Kolom Jenis Artikel harus diisi",
                minlength: "Panjang isi kolom minimal 5 karakter"
            },
            color: {
                required: "Kolom Warna Background harus diisi"
            },
            icon: {
                required: "Kolom Icon harus diisi"
            }
        },
        highlight: function(e) {
            $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
        },
        success: function(e) {
            $(e).closest('.form-group').removeClass('has-error').addClass('has-info');
            $(e).remove();
        },
        errorPlacement: function(error, element) {
            if (element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                var controls = element.closest('div[class*="col-"]');
                if (controls.find(':checkbox,:radio').length > 1)
                    controls.append(error);
                else
                    error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
            }
            else if (element.is('.select2')) {
                error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
            }
            else if (element.is('.chosen-select')) {
                error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
            }
            else
                error.insertAfter(element.parent());
        },
        invalidHandler: function(form) {
        }
    });
    $(document).ready(function() {
        $('.color-pick').colorpicker();
        
    });
</script>
