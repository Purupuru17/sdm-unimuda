<?php
$this->load->view('sistem/v_breadcrumb');
load_css(array(
    "theme/aceadmin/assets/css/bootstrap-datetimepicker.css"
));
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
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Judul :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <input value="<?= $edit['judul_artikel'] ?>" type="text" name="judul" id="judul" class="col-xs-12  col-sm-6" placeholder="Judul Artikel" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Jenis :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <select class="select2 width-100" name="jenis" id="jenis" data-placeholder="-------> Pilih Jenis Artikel <-------">
                                <option value=""> </option>
                                <?php
                                foreach ($jenis['data'] as $val) {
                                    $selected = ($edit['jenis_id'] == $val['id_jenis']) ? 'selected' : '';
                                    echo '<option value="'.$val['id_jenis'].'"  '.$selected.'>'.$val['judul_jenis'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Tanggal :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <input value="<?= $edit['update_artikel'] ?>" id="tgl" name="tgl" type="text" class="date-time-picker col-xs-12  col-sm-4" placeholder="Tanggal Artikel" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Status :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <label class="control-label">
                                <input <?= ($edit['status_artikel'] == '1') ? 'checked' : '' ; ?> name="status" value="1" type="radio" class="ace" />
                                <span class="lbl"> Aktif</span>
                            </label>&nbsp;&nbsp;&nbsp;
                            <label class="control-label">
                                <input <?= ($edit['status_artikel'] == '0') ? 'checked' : '' ; ?> name="status" value="0" type="radio" class="ace" />
                                <span class="lbl"> Tidak Aktif</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Popular :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <label class="control-label">
                                <input <?= ($edit['is_popular'] == '1') ? 'checked' : '' ; ?> name="popular" value="1" type="radio" class="ace" />
                                <span class="lbl"> Ya</span>
                            </label>&nbsp;&nbsp;&nbsp;
                            <label class="control-label">
                                <input <?= ($edit['is_popular'] == '0') ? 'checked' : '' ; ?> name="popular" value="0" type="radio" class="ace" />
                                <span class="lbl"> Tidak</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Breaking News :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <label class="control-label">
                                <input <?= ($edit['is_breaking'] == '1') ? 'checked' : '' ; ?> name="breaking" value="1" type="radio" class="ace" />
                                <span class="lbl"> Ya</span>
                            </label>&nbsp;&nbsp;&nbsp;
                            <label class="control-label">
                                <input <?= ($edit['is_breaking'] == '0') ? 'checked' : '' ; ?> name="breaking" value="0" type="radio" class="ace" />
                                <span class="lbl"> Tidak</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-6 no-padding-right">Isi Konten</label>
                    <div class="col-xs-12 col-sm-12">
                        <div class="clearfix">
                            <textarea rows="10" cols="1" name="isi" id="isi" class="col-xs-12 col-sm-12"><?= ctk($edit['isi_artikel'],1) ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Foto :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <input value="<?= $edit['foto_artikel'] ?>" type="hidden" name="exfoto" id="exfoto" />
                            <input accept="image/*" value="" type="file" name="foto" id="foto" placeholder="Foto Artikel" class="col-xs-12  col-sm-6" />
                        </div>
                    </div>
                    <span class="help-inline col-xs-12 col-sm-2">
                        <span class="middle red">* Maksimal 1 MB</span>
                    </span>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right"></label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <img width="200" class="img-thumbnail img-preview" src="<?= load_file($edit['foto_artikel']) ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Terakhir diubah :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="well">
                            <span class="bigger-110 blue"><i class="ace-icon fa fa-user"></i> &nbsp;&nbsp;<?= $edit['log_artikel'] ?></span><br/>
                            <span class="bigger-110 orange"><i class="ace-icon fa fa-clock-o"></i> &nbsp;&nbsp;<?= format_date($edit['update_artikel'],0) ?></span>
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
        "theme/aceadmin/assets/js/select2.js",
        "theme/aceadmin/assets/js/date-time/moment.js",
        "theme/aceadmin/assets/js/date-time/bootstrap-datetimepicker.js",
        "theme/ckeditor/ckeditor.js",
        "theme/ckeditor/adapters/jquery.js",
    )); 
?>
<script type="text/javascript">
    jQuery(function($) {
        $(".select2").select2({allowClear: true})
            .on('change', function() {
                $(this).closest('form').validate().element($(this));
        });
        $('.date-time-picker').datetimepicker({
                format: 'YYYY-MM-DD HH:mm:ss'
            }).next().on(ace.click_event, function(){
                $(this).prev().focus();
        });
        var img_ext = ["jpg", "png", "jpeg", "PNG", "JPG"];
        $('#foto').ace_file_input({
            no_file: 'Pilih Foto Artikel...',
            no_icon: 'fa fa-file-image-o',
            icon_remove: 'fa fa-times',
            btn_choose: 'Pilih',
            btn_change: 'Ubah',
            allowExt: img_ext,
            maxSize: 1100000,
            before_change: function(files, dropped){
                var valid = false;
                if(files && files[0]) {
                  var reader = new FileReader();
                  reader.onload = function(e) {
                    $('.img-preview').attr('src', e.target.result);
                  };
                  reader.readAsDataURL(files[0]);
                  valid = true;
                } else {
                  $('.img-preview').attr('src','');
                }
                return valid;
            }
        }).on('file.error.ace', function(ev, info) {
            if(info.error_count['ext']) jsfNotif('Peringatan!', 'Format gambar harus berupa *.jpg, *.png', 3);
            if(info.error_count['size']) jsfNotif('Peringatan!', 'Ukuran gambar maksimal 1 MB', 3);
        });
        $('.remove').click(function (e) {
            $('.img-preview').attr('src','');
        });
        CKEDITOR.disableAutoInline = true;
        CKEDITOR.replace('isi', {
            uiColor: '#438eb9'
        });
    });
</script>
<script type="text/javascript">
    $('#validation-form').validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        ignore: "",
        rules: {
            judul: {
                required: true,
                minlength: 10
            },
            jenis: {
                required: true
            },
            tgl: {
                required: true,
                minlength: 15
            },
            status: {
                required: true
            },
            popular: {
                required: true
            },
            breaking: {
                required: true
            },
            isi: {
                required: true,
                minlength: 10
            }
        },
        messages: {
            judul: {
                required: "Kolom Judul Artikel harus diisi",
                minlength: "Panjang isi kolom minimal 10 karakter"
            },
            jenis: "Pilih Jenis Artikel terlebih dahulu",
            tgl: {
                required: "Kolom Tanggal Artikel harus diisi",
                minlength: "Panjang isi kolom minimal 15 karakter"
            },
            status: "Pilih Status Artikel terlebih dahulu",
            popular: "Pilih Popular Artikel terlebih dahulu",
            breaking: "Pilih Breaking News terlebih dahulu",
            isi: {
                required: "Kolom Isi Konten harus diisi",
                minlength: "Panjang isi kolom minimal 10 karakter"
            }
        },
        highlight: function(e) {
            $(e).closest('.form-group').removeClass('has-success').addClass('has-error');
        },
        success: function(e) {
            $(e).closest('.form-group').removeClass('has-error').addClass('has-success');
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
</script>
