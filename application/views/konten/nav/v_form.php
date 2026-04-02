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
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right">Jenis :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <select class="select2 width-100" name="jenis" id="jenis" data-placeholder="-------> Pilih Jenis Menu <-------">
                                <option value="" <?= (is_null($edit['parent_nav'])) ? 'selected' : ''; ?>>  </option>
                                <option value="0" <?= ($edit['parent_nav'] == '0') ? 'selected' : ''; ?>>Menu Utama</option>
                                <option value="1" <?= (!(is_null($edit['parent_nav'])) && $edit['parent_nav'] != '0') ? 'selected' : ''; ?> >Sub Menu</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group hide parent_related">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right"></label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <select class="select2 width-100" name="pilihan" id="pilihan" data-placeholder="-------> Pilih Menu Utama <-------">
                                <option value=""> </option>
                                <?php
                                foreach ($parent['data'] as $val) {
                                    $selected = ($edit['parent_nav'] == $val['id_nav']) ? 'selected' : '';
                                    echo '<option value="' . $val['id_nav'] . '"  ' . $selected . '>' . $val['judul_nav'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right">Judul :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <input value="<?= $edit['judul_nav'] ?>" type="text" name="judul" id="judul" class="col-xs-12  col-sm-6" placeholder="Judul Menu" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right">URL :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <input value="<?= $edit['url_nav'] ?>" type="text" name="url" id="url" class="col-xs-12  col-sm-12 scrollable" placeholder="URL Halaman" />
                        </div>
                    </div>
                    <span class="help-inline col-xs-12 col-sm-2">
                        <span class="middle red">* Ketik <b>pages</b> untuk opsi</span>
                    </span>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right">Tipe URL :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <label class="control-label">
                                <input <?= ($edit['link_nav'] == '0') ? 'checked' : ''; ?> name="link" value="0" type="radio" class="ace" />
                                <span class="lbl"> Internal</span>
                            </label>&nbsp;&nbsp;&nbsp;
                            <label class="control-label">
                                <input <?= ($edit['link_nav'] == '1') ? 'checked' : ''; ?> name="link" value="1" type="radio" class="ace" />
                                <span class="lbl"> Eksternal</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right">Status :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <label class="control-label">
                                <input <?= ($edit['status_nav'] == '1') ? 'checked' : ''; ?> name="status" value="1" type="radio" class="ace" />
                                <span class="lbl"> Aktif</span>
                            </label>&nbsp;&nbsp;&nbsp;
                            <label class="control-label">
                                <input <?= ($edit['status_nav'] == '0') ? 'checked' : ''; ?> name="status" value="0" type="radio" class="ace" />
                                <span class="lbl"> Tidak Aktif</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right">Order :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <input value="<?= $edit['order_nav'] ?>" type="number" name="order" id="icon" class="col-xs-12  col-sm-3" placeholder="Order Menu" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right">Icon :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <input value="<?= $edit['icon_nav'] ?>" type="text" name="icon" id="icon" class="col-xs-12  col-sm-3" placeholder="Icon Menu" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right">Terakhir diubah :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="well">
                            <span class="bigger-110 blue"><i class="ace-icon fa fa-user"></i> &nbsp;&nbsp;<?= $edit['log_nav'] ?></span><br/>
                            <span class="bigger-110 orange"><i class="ace-icon fa fa-clock-o"></i> &nbsp;&nbsp;<?= format_date($edit['update_nav'], 0) ?></span>
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
    "theme/aceadmin/assets/js/typeahead.jquery.js"
));
?>

<script type="text/javascript">
    jQuery(function ($) {
        $(".select2").select2({allowClear: true})
                .on('change', function () {
                    $(this).closest('form').validate().element($(this));
                });

        if ($('select#jenis').val() === '0' || $('select#jenis').val() === '') {
            $('.parent_related').addClass('hide');
        } else {
            $('.parent_related').removeClass('hide');
        }
        var page = <?= json_encode(array_column($pages, 'slug')) ?>;
        var substringMatcher = function (strs) {
            return function findMatches(q, cb) {
                var matches, substrRegex;
                // an array that will be populated with substring matches
                matches = [];
                // regex used to determine if a string contains the substring `q`
                substrRegex = new RegExp(q, 'i');
                // iterate through the pool of strings and for any string that
                // contains the substring `q`, add it to the `matches` array
                $.each(strs, function (i, str) {
                    if (substrRegex.test(str)) {
                        // the typeahead jQuery plugin expects suggestions to a
                        // JavaScript object, refer to typeahead docs for more info
                        matches.push({value: str});
                    }
                });

                cb(matches);
            };
        };
        $('input#url').typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            name: 'page',
            displayKey: 'value',
            source: substringMatcher(page)
        });
    });
</script>
<script type="text/javascript">
    $('select#jenis').change(function () {
        if ($('select[name="jenis"] option:selected').val() === '0' || $('select[name="jenis"] option:selected').val() === '') {
            $('.parent_related').addClass('hide');
        } else {
            $('.parent_related').removeClass('hide');
        }
    });
    $('#validation-form').validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        ignore: "",
        rules: {
            jenis: {
                required: true
            },
            judul: {
                required: true,
                minlength: 5
            },
            url: {
                required: true,
                minlength: 5
            },
            status: {
                required: true
            },
            link: {
                required: true
            },
            order: {
                required: true,
                digits: true,
                min: 1
            }
        },
        messages: {
            jenis: "Kolom Jenis Menu harus diisi",
            judul: {
                required: "Kolom Judul Menu harus diisi",
                minlength: "Panjang isi kolom minimal 5 karakter"
            },
            url: {
                required: "Kolom URL Menu harus diisi",
                minlength: "Panjang isi kolom minimal 5 karakter"
            },
            status: "Pilih Status Menu terlebih dahulu",
            link: "Pilih Tipe URL terlebih dahulu",
            order: {
                required: "Kolom Order Menu harus diisi",
                digits: "Harga harus berupa angka bulat"
            }

        },
        highlight: function (e) {
            $(e).closest('.form-group').removeClass('has-success').addClass('has-error');
        },
        success: function (e) {
            $(e).closest('.form-group').removeClass('has-error').addClass('has-success');
            $(e).remove();
        },
        errorPlacement: function (error, element) {
            if (element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                var controls = element.closest('div[class*="col-"]');
                if (controls.find(':checkbox,:radio').length > 1)
                    controls.append(error);
                else
                    error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
            } else if (element.is('.select2')) {
                error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
            } else if (element.is('.chosen-select')) {
                error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
            } else
                error.insertAfter(element.parent());
        },
        invalidHandler: function (form) {
        }
    });
</script>
