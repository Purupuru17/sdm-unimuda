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
                <div class="form-group <?= ($this->session->userdata('level') != '1') ? 'hide':'' ?>">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right">Tanggal :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <input value="<?= $edit['tgl_libur'] ?>" type="text" name="tanggal" id="tanggal" class="col-xs-12 col-sm-6 date-picker" placeholder="Tanggal" />
                        </div>
                    </div>
                    <span class="help-inline col-xs-6 col-md-offset-5">
                        <span class="middle blue bolder" id="txt-tgl"><?= format_date($edit['tgl_libur'],1) ?></span>
                    </span>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right">Catatan :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <input value="<?= $edit['catat_libur'] ?>" type="text" name="catatan" id="catatan" placeholder="Catatan" class="col-xs-12 col-sm-6"/>
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
        "theme/aceadmin/assets/js/jquery.validate.js",
        "theme/aceadmin/assets/js/date-time/bootstrap-datepicker.js"
    )); 
?>
<script type="text/javascript">
    const bulan = new Array(null,"Januari", "Februari", "Maret", "April", "Mei", "Juni", 
        "Juli", "Agustus", "September", "Oktober", "November", "Desember");
    $(document).ready(function () {
        $(".date-picker").datepicker({
            format: 'yyyy-mm-dd', autoclose: true,
            todayHighlight: true,clearBtn: true
        });
        validateForm();
    });
    $("#tanggal").change(function () {
        let tgl = this.value.split("-");
        $("#txt-tgl").html(tgl[2]+' '+bulan[parseInt(tgl[1])]+' '+tgl[0]);
    });
    function validateForm() {
        jsfValidate("#validation-form", {
            tanggal: { required: true, date: true, minlength: 5 },
            catatan: { required: true, minlength: 5 }
        });
    }
</script>