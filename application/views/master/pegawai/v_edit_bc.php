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
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Nama & Gelar :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['nama_gelar'] ?>" type="text" name="gelar" id="gelar" placeholder="Nama & Gelar" class="col-xs-12 col-sm-6 input-sm"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">NIDN :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['nidn'] ?>" type="text" name="nidn" id="nidn" placeholder="NIDN" class="col-xs-12 col-sm-6 input-sm"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">NUPTK :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['nuptk'] ?>" type="text" name="nuptk" id="nuptk" placeholder="NUPTK" class="col-xs-12 col-sm-6 input-sm"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Jenis Kelamin :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['jenis_kelamin'] ?>" type="text" name="kelamin" id="kelamin" placeholder="Jenis Kelamin" class="col-xs-12 col-sm-6 input-sm"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Tempat Lahir :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['tempat_lahir'] ?>" type="text" name="tempat" id="tempat" placeholder="Tempat Lahir" class="col-xs-12 col-sm-6 input-sm"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Tanggal Lahir :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['tgl_lahir'] ?>" type="text" name="lahir" id="lahir" placeholder="Tanggal Lahir" class="col-xs-12 col-sm-6 input-sm"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Agama :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['agama'] ?>" type="text" name="agama" id="agama" placeholder="Agama" class="col-xs-12 col-sm-6 input-sm"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Telepon :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['telepon'] ?>" type="text" name="telepon" id="telepon" placeholder="Telepon" class="col-xs-12 col-sm-6 input-sm"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Email :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['email'] ?>" type="email" name="email" id="email" placeholder="Email" class="col-xs-12 col-sm-6 input-sm"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Pendidikan :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['pendidikan'] ?>" type="text" name="pendidikan" id="pendidikan" placeholder="Pendidikan" class="col-xs-12 col-sm-6 input-sm"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Status Nikah :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['status_nikah'] ?>" type="text" name="nikah" id="nikah" placeholder="Status Nikah" class="col-xs-12 col-sm-6 input-sm"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Jumlah Anak :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['jumlah_anak'] ?>" type="number" name="anak" id="anak" placeholder="Jumlah Anak" class="col-xs-12 col-sm-6 input-sm"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Tanggal Pegawai :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['tgl_pegawai'] ?>" type="text" name="tanggal" id="tanggal" placeholder="Tanggal Pegawai" class="col-xs-12 col-sm-6 input-sm"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Status Pegawai :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['status_pegawai'] ?>" type="text" name="status" id="status" placeholder="Status Pegawai" class="col-xs-12 col-sm-6 input-sm"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Jenis Pegawai :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['jenis_pegawai'] ?>" type="text" name="jenis" id="jenis" placeholder="Jenis Pegawai" class="col-xs-12 col-sm-6 input-sm"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Pangkat :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['pangkat'] ?>" type="text" name="pangkat" id="pangkat" placeholder="Pangkat" class="col-xs-12 col-sm-6 input-sm"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Akademik :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['akademik'] ?>" type="text" name="akademik" id="akademik" placeholder="Akademik" class="col-xs-12 col-sm-6 input-sm"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Unit :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['unit_id'] ?>" type="text" name="unit" id="unit" placeholder="Unit" class="col-xs-12 col-sm-6 input-sm"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Jabatan :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['jabatan_id'] ?>" type="text" name="jabatan" id="jabatan" placeholder="Jabatan" class="col-xs-12 col-sm-6 input-sm"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Unit Jabatan :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="<?= $edit['jabatan_unit_id'] ?>" type="text" name="jabatan_unit" id="jabatan_unit" placeholder="Unit Jabatan" class="col-xs-12 col-sm-6 input-sm"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Alamat :</label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <textarea name="alamat" id="alamat" placeholder="Alamat" class="col-xs-12 col-sm-6 input-sm"><?= $edit['alamat'] ?></textarea>
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
            nama: { required: true, minlength: 3 },
            nik: { required: true, digits: true, minlength: 16, maxlength: 16 },
            gelar: { required: true, minlength: 5 },
            nidn: { digits: true, minlength: 5 },
            nuptk: { digits: true, minlength: 5 },
            kelamin: { required: true },
            tempat: { required: true, minlength: 3 },
            lahir: { required: true, minlength: 5 },
            agama: { required: true },
            telepon: { required: true, digits: true, minlength: 10 },
            email: { email: true },
            pendidikan: { minlength: 5 },
            anak: { digits: true },
            tanggal: { minlength: 5 },
            alamat: { minlength: 5 }
        });
    }
</script>