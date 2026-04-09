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
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Jenis :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <select class="select2 width-100" name="jenis" id="jenis" data-placeholder="----> Pilih Jenis <----">
                                <option value=""> </option>
                                <?php
                                foreach (['PENGAJIAN','RAPAT','KUNJUNGAN','KEGIATAN'] as $val) {
                                    $selected = (element('jenis_agenda', $edit) == $val) ? 'selected' : '';
                                    echo '<option value="'.$val.'"  '.$selected.'>'.$val.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Agenda :</label>
                    <div class="col-xs-12 col-sm-8">
                        <div class="clearfix">
                            <textarea rows="6" cols="1" name="judul" id="judul" placeholder="Deskripsi Kegiatan" class="col-xs-12 col-sm-6"><?= $edit['judul_agenda'] ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Lokasi :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <select class="select2 width-100" name="lokasi" id="lokasi" data-placeholder="-----> Pilih Opsi <-----">
                                <option value=""> </option>
                                <?php
                                foreach ($lokasi['data'] as $val) {
                                    $selected = ($edit['lokasi_id'] == $val['id_lokasi']) ? 'selected' : '';
                                    echo '<option value="'.encode($val['id_lokasi']).'"  '.$selected.'>'.$val['nama_lokasi'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Petugas :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <input value="<?= element('petugas_agenda', $edit) ?>" type="text" name="petugas" id="petugas" class="col-xs-12 col-sm-6" placeholder="Petugas" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Waktu :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <input value="<?= element('waktu_agenda', $edit) ?>" type="text" name="waktu" id="waktu" class="col-xs-12 col-sm-6 date-time-picker" placeholder="Waktu Pelaksanaan" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Maks. Keterlambatan :</label>
                    <div class="col-xs-12 col-sm-2">
                        <div class="clearfix">
                            <input value="<?= element('limit_agenda', $edit) ?>" type="number" name="limit" id="limit" class="col-xs-12 col-sm-6" placeholder="? Menit" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Status :</label>
                    <div class="col-xs-12 col-sm-8">
                        <div class="clearfix">
                            <label class="control-label">
                                <input <?= ($edit['status_agenda'] == '1') ? 'checked' : ''; ?> name="status" value="1" type="radio" class="ace" />
                                <span class="lbl"> AKTIF </span>
                            </label>&nbsp;&nbsp;&nbsp;
                            <label class="control-label">
                                <input <?= ($edit['status_agenda'] == '0') ? 'checked' : ''; ?> name="status" value="0" type="radio" class="ace" />
                                <span class="lbl"> TIDAK AKTIF </span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Presensi :</label>
                    <div class="col-xs-12 col-sm-8">
                        <div class="clearfix">
                            <label class="control-label">
                                <input <?= ($edit['is_open'] == '1') ? 'checked' : ''; ?> name="presensi" value="1" type="radio" class="ace" />
                                <span class="lbl"> AKTIF </span>
                            </label>&nbsp;&nbsp;&nbsp;
                            <label class="control-label">
                                <input <?= ($edit['is_open'] == '0') ? 'checked' : ''; ?> name="presensi" value="0" type="radio" class="ace" />
                                <span class="lbl"> TIDAK AKTIF </span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Terakhir diubah :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="well">
                            <span class="bigger-110 blue"><i class="ace-icon fa fa-user"></i> &nbsp;&nbsp;<?= $edit['log_agenda'] ?></span><br/>
                            <span class="bigger-110 orange"><i class="ace-icon fa fa-clock-o"></i> &nbsp;&nbsp;<?= format_date($edit['update_agenda'],0) ?></span>
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
        "theme/aceadmin/assets/js/jquery.validate.js",
        "theme/aceadmin/assets/js/select2.js",
        "theme/aceadmin/assets/js/date-time/moment.js",
        "theme/aceadmin/assets/js/date-time/bootstrap-datetimepicker.js",
    )); 
?>
<script type="text/javascript">
    const module = "<?= site_url($module) ?>";
    $(document).ready(function () {
        $(".select2").select2({allowClear: true});
        $(".select2-chosen").addClass("center");
        $(".date-time-picker").datetimepicker({
                format: 'YYYY-MM-DD HH:mm:ss'
            }).next().on(ace.click_event, function(){
                $(this).prev().focus();
        });
        validateForm();
    });
    function validateForm() {
        jsfValidate("#validation-form", {
            jenis: {
                required: true
            },
            judul: {
                required: true,
                minlength: 5
            },
            lokasi: {
                required: true
            },
            petugas: {
                required: true,
                minlength: 5
            },
            waktu: {
                required: true,
                minlength: 5
            },
            limit: {
                required: true,
                digits: true,
                min:1,
                max:300
            },
            status: {
                required: true
            },
            presensi: {
                required: true
            }
        });
    }
</script>