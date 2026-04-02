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
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Nama Lengkap :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <input type="text" name="nama" id="nama" class="col-xs-12  col-sm-6" placeholder="Nama Lengkap" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Username :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <input type="text" name="username" id="username" class="col-xs-12  col-sm-6" placeholder="Username" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Password :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <input type="password" name="password" id="password" class="col-xs-12  col-sm-6" placeholder="Password" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Konfirmasi Password :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <input type="password" name="confirm" id="confirm" class="col-xs-12  col-sm-6" placeholder="Konfirmasi Password" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Group :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <select class="select2 width-100" name="group" id="group" data-placeholder="-------> Pilih Group <-------">
                                <option value="">  </option>
                                <?php
                                foreach ($group['data'] as $val) {
                                    echo '<option value="'.encode($val['id_group']).'">'.$val['nama_group'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Status :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <label class="control-label">
                                <input name="status" value="1" type="radio" class="ace" />
                                <span class="lbl"> AKTIF</span>
                            </label>&nbsp;&nbsp;&nbsp;
                            <label class="control-label">
                                <input name="status" value="0" type="radio" class="ace" />
                                <span class="lbl"> TIDAK AKTIF</span>
                            </label>
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

<?php load_js(array("theme/aceadmin/assets/js/jquery.validate.js","theme/aceadmin/assets/js/select2.js")); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $(".select2").select2({allowClear: true});
        $(".select2-chosen").addClass("center");
        validate_form();
    });
    function validate_form() {
        jsfValidate("#validation-form", {
            nama: {
                required: true,
                minlength: 5
            },
            username: {
                required: true,
                minlength: 5
            },
            password: {
                required: true,
                minlength: 5
            },
            confirm: {
                required: true,
                minlength: 5,
                equalTo: "#password"
            },
            group: {
                required: true
            },
            status: {
                required: true
            }
        });
    }
</script>                   
