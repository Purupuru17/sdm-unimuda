<?php
$this->load->view('sistem/v_breadcrumb');
?>
<style>
    .select2-container{
        padding-left: 0px;
    }
    .select2-chosen{
        text-align: center;
    }
</style>
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
                <input value="<?= encode($edit['id_jabatan']) ?>" id="id" type="hidden"/>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Nama :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['nama_jabatan'] ?>" type="text" name="nama" id="nama" placeholder="Nama Jabatan" class="col-xs-12 col-sm-6" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Unit Kerja :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <input value="<?= encode($edit['unit_id']) ?>" type="hidden" name="unit" id="unit" class="width-100"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Penanggung Jawab (Atasan) :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <input value="<?= encode($edit['atasan']) ?>" type="hidden" name="atasan" id="atasan" class="width-100"/>
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

<?php 
    load_js(array(
        'theme/aceadmin/assets/js/jquery.validate.js',
        'theme/aceadmin/assets/js/select2.js',
    )); 
?>
<script type="text/javascript">
    const module = "<?= site_url($module) ?>";
    $(document).ready(function () {
        $(".select2").select2({allowClear: true});
        validateForm();
        getSelect();
    });
    function getSelect() {
        $("#unit").select2({
            placeholder: "-----> Pilih Opsi <-----",
            ajax: {
                url: module + "/ajax/type/list/source/unit",
                type: "POST",
                dataType: 'json',
                delay: 250,
                data: function (key) {
                    return { key: key };
                },
                results: function (data) {
                    return { results: data };
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    jsfRequest(module + "/ajax/type/list/source/unit?id=" + id, "GET")
                        .done(function(result) {
                            callback(result[0]);
                    });
                }
            }
        });
        $("#atasan").select2({
            placeholder: "-----> Pilih Opsi <-----",
            allowClear: true,
            ajax: {
                url: module + "/ajax/type/list/source/jabatan",
                type: "POST",
                dataType: 'json',
                delay: 250,
                data: function (key) {
                    return { key: key, id: $("#id").val() };
                },
                results: function (data) {
                    return { results: data };
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    jsfRequest(module + "/ajax/type/list/source/jabatan?id=" + id, "GET")
                        .done(function(result) {
                            callback(result[0]);
                    });
                }
            }
        });
    }
    function validateForm() {
        jsfValidate("#validation-form", {
            nama: { required: true, minlength: 5 },
            unit: { required: true },
            atasan: { }
        });
    }
</script>