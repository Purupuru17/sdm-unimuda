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
            <div id="user-profile-1" class="user-profile row">
                <div class="col-xs-12 col-sm-3 center">
                    <div>
                        <!-- #section:pages/profile.picture -->
                        <span class="profile-picture">
                            <div align="center">
                                <img src="<?= load_file($user['foto_user'],1) ?>" id="avatar" class="img-responsive blur-up lazyload" />
                            </div>
                        </span>
                        <!-- /section:pages/profile.picture -->
                        <div class="space-4"></div>
                        <div class="width-80 label label-info label-xlg arrowed-in arrowed-in-right">
                            <div class="inline position-relative">
                                <span class="white"><?= $user['fullname'] ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="space-6"></div>
                    <!-- #section:pages/profile.contact -->
                    <div class="profile-contact-info">
                        <div class="profile-contact-links align-left">
                            <span  class="btn btn-sm btn-link">
                                <i class="ace-icon fa fa-user bigger-120 green"></i>
                                <?= $user['username'] ?>
                            </span><br/>
                            <span  class="btn btn-sm btn-link">
                                <i class="ace-icon fa fa-envelope bigger-120 orange2"></i>
                                <?= $user['email'] ?>
                            </span><br/>
                            <span  class="btn btn-sm btn-link">
                                <i class="ace-icon fa fa-lightbulb-o bigger-120 blue"></i>
                                <?= ($user['status_user'] == '1') ? '<span class="label label-success arrowed-in-right arrowed">AKTIF</span>' : '<span class="arrowed label label-danger arrowed-in-right">TIDAK AKTIF</span>' ?>
                            </span><br/>
                            <div class="hr hr6 dotted"></div>
                            <span  class="btn btn-sm btn-link">
                                <i class="ace-icon fa fa-pencil bigger-120 green"></i>
                                <?= format_date($user['buat_user']) ?>
                            </span><br/>
                            <span  class="btn btn-sm btn-link">
                                <i class="ace-icon fa fa-pencil-square-o bigger-120 orange"></i>
                                <?= selisih_wkt($user['update_user']) ?>
                            </span><br/>
                            <span  class="btn btn-sm btn-link">
                                <i class="ace-icon fa fa-sign-in bigger-130 red"></i>
                                <?= selisih_wkt($user['last_login']) ?>
                            </span><br/>
                            <span  class="btn btn-sm btn-link">
                                <i class="ace-icon fa fa-laptop bigger-120 purple"></i>
                                <?= $user['ip_user'] ?>
                            </span>
                        </div>
                        <div class="space-6"></div>
                    </div>
                    <!-- /section:pages/profile.contact -->
                </div>
                <div class="col-xs-12 col-sm-9">
                    <!-- #section:pages/profile.info -->
                    <div class="profile-user-info profile-user-info-striped padding-20">
                        <h3 class="lighter center block orange">Ubah Pengaturan Akun</h3>
                        
                        <form  id="validation-form" action="<?= site_url($action); ?>" name="form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label class="control-label col-xs-12 col-sm-4 no-padding-right">Fullname :</label>
                                <div class="col-xs-12 col-sm-8">
                                    <div class="clearfix">
                                        <input value="<?= $user['fullname']; ?>" type="text" name="nama" id="nama" class="col-xs-12  col-sm-6" placeholder="Nama Lengkap" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-xs-12 col-sm-4 no-padding-right">Username :</label>
                                <div class="col-xs-12 col-sm-8">
                                    <div class="clearfix">
                                        <input value="<?= $user['username']; ?>" type="text" name="username" id="username" placeholder="Username" class="col-xs-12  col-sm-6" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-xs-12 col-sm-4 no-padding-right">Email :</label>
                                <div class="col-xs-12 col-sm-8">
                                    <div class="clearfix">
                                        <input value="<?= $user['email']; ?>" type="text" name="email" id="email" placeholder="Email" class="col-xs-12  col-sm-6" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-xs-12 col-sm-4 no-padding-right">Foto Profil :</label>
                                <div class="col-xs-12 col-sm-5">
                                    <div class="clearfix">
                                        <input value="<?= $user['foto_user'] ?>" type="hidden" name="exfoto" id="exfoto" />
                                        <input value="" accept="image/*" type="file" name="foto" id="foto" placeholder="Foto" class="col-xs-12  col-sm-6" />
                                    </div>
                                </div>
                                <span class="help-inline col-xs-12 col-sm-3">
                                    <span class="middle red">* Maksimal 1 MB </span>
                                </span>
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
                </div>
            </div>
        </div>
    </div><!-- /.row -->
</div><!-- /.page-content -->

<?php load_js(array("theme/aceadmin/assets/js/jquery.validate.js")); ?>

<script type="text/javascript">
    $(document).ready(function() {
        var img_ext = ["jpg", "png", "jpeg", "PNG", "JPG"];
        $("#foto").ace_file_input({
            no_file: 'Plih Foto ...',
            no_icon: 'fa fa-file-image-o',
            icon_remove: 'fa fa-times',
            btn_choose: 'Pilih',
            btn_change: 'Ubah',
            droppable: false,
            onchange: null,
            allowExt: img_ext,
            maxSize: 1100000
        }).on('file.error.ace', function(ev, info) {
            if(info.error_count['ext']) jsfNotif('Peringatan', 'Format gambar harus berupa *.jpg, *.png', 3);
            if(info.error_count['size']) jsfNotif('Peringatan', 'Ukuran gambar maksimal 1 MB', 3);
        });
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
            email: {
                required: true,
                email: true
            }
        });
    }
</script> 