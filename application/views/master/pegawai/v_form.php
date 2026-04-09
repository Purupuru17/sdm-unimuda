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
                <div class="social-or-login center">
                    <span class="red">Data Diri (*)</span>
                </div>
                <div class="space-6"></div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">NIK :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <input <?= empty($edit['id_pegawai']) ? '':'readonly' ?> value="<?= $edit['nik'] ?>" type="text" name="nik" id="nik" placeholder="NIK" class="col-xs-12 col-sm-6"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Nama Lengkap :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['nama'] ?>" type="text" name="nama" id="nama" placeholder="Nama Lengkap" class="col-xs-12 col-sm-6"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Nama & Gelar :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['nama_gelar'] ?>" type="text" name="gelar" id="gelar" placeholder="Nama beserta Gelar" class="col-xs-12 col-sm-6"/>
                        </div>
                    </div>
                </div>
                <div class="social-or-login center">
                    <span class="red">Data SDM (*)</span>
                </div>
                <div class="space-6"></div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Tanggal Masuk (SK Pegawai) :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <input value="<?= $edit['tgl_pegawai'] ?>" type="text" name="tanggal" id="tanggal" placeholder="Tanggal SK Pegawai" class="date-picker col-xs-12 col-sm-6"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Jenis Pegawai :</label>
                    <div class="col-xs-12 col-sm-2">
                        <div class="clearfix">
                            <select class="select2 width-100" name="jenis" id="jenis" data-placeholder="----> Pilih Opsi <----">
                                <option value=""> </option>
                                <?php
                                foreach (load_array('jenis_pegawai') as $val) {
                                    $selected = ($edit['jenis_pegawai'] == $val) ? 'selected' : '';
                                    echo '<option value="'.$val.'" '.$selected.'>'.$val.'</option>';
                                }
                                ?>
                            </select>
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
                <div class="social-or-login center">
                    <span class="blue">Data Dosen (Tenaga Pengajar)</span>
                </div>
                <div class="space-6"></div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">NIDN :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <input value="<?= $edit['nidn'] ?>" type="text" name="nidn" id="nidn" placeholder="NIDN" class="col-xs-12 col-sm-6"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">NUPTK :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <input value="<?= $edit['nuptk'] ?>" type="text" name="nuptk" id="nuptk" placeholder="NUPTK" class="col-xs-12 col-sm-6"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Status Pegawai :</label>
                    <div class="col-xs-12 col-sm-2">
                        <div class="clearfix">
                            <select class="select2 width-100" name="status" id="status" data-placeholder="----> Pilih Opsi <----">
                                <option value=""> </option>
                                <?php
                                foreach (load_array('status_pegawai') as $val) {
                                    $selected = ($edit['status_pegawai'] == $val) ? 'selected' : '';
                                    echo '<option value="'.$val.'" '.$selected.'>'.$val.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Pangkat/Golongan :</label>
                    <div class="col-xs-12 col-sm-2">
                        <div class="clearfix">
                            <select class="select2 width-100" name="pangkat" id="pangkat" data-placeholder="----> Pilih Opsi <----">
                                <option value=""> </option>
                                <?php
                                foreach (load_array('pangkat') as $val) {
                                    $selected = ($edit['pangkat'] == $val) ? 'selected' : '';
                                    echo '<option value="'.$val.'" '.$selected.'>'.$val.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Jabatan Fungsional :</label>
                    <div class="col-xs-12 col-sm-2">
                        <div class="clearfix">
                            <select class="select2 width-100" name="akademik" id="akademik" data-placeholder="----> Pilih Opsi <----">
                                <option value=""> </option>
                                <?php
                                foreach (load_array('fungsional') as $val) {
                                    $selected = ($edit['akademik'] == $val) ? 'selected' : '';
                                    echo '<option value="'.$val.'" '.$selected.'>'.$val.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Jabatan Struktural :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <input value="<?= encode($edit['jabatan_id']) ?>" type="hidden" name="jabatan" id="jabatan" class="width-100"/>
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
        'theme/aceadmin/assets/js/date-time/bootstrap-datepicker.js'
    )); 
?>
<script type="text/javascript">
    const module = "<?= site_url($module) ?>";
    const bulan = new Array(null,"Januari", "Februari", "Maret", "April", "Mei", "Juni", 
        "Juli", "Agustus", "September", "Oktober", "November", "Desember");
        
    $(document).ready(function () {
        $(".select2").select2({allowClear: true});
        $(".date-picker").datepicker({
            format: 'yyyy-mm-dd', autoclose: true,
            todayHighlight: true,clearBtn: true
        });
        validateForm();
        getSelect();
    });
    function getSelect() {
        $("#unit").select2({
            placeholder: "-----> Pilih Opsi <-----",
            //minimumInputLength: 3,
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
                            console.log(result[0]);
                    });
                }
            }
        });
        $("#jabatan").select2({
            placeholder: "-----> Pilih Opsi <-----",
            //minimumInputLength: 3,
            ajax: {
                url: module + "/ajax/type/list/source/jabatan",
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
            nik: { required: true, digits: true, minlength: 16, maxlength: 16 },
            nama: { required: true, minlength: 3 },
            gelar: { required: true, minlength: 5 },
            tanggal: { required: true, date: true, minlength: 5 },
            jenis: { required: true },
            unit: { required: true },
            nidn: { digits: true, minlength: 5},
            nuptk: { digits: true, minlength: 5},
            status: {  },
            pangkat: {  },
            akademik: {  },
            jabatan: {  },
            jabatan_unit: { }
        });
    }
</script>