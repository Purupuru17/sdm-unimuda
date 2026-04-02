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
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Nama Menu :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <input value="<?= $menu['nama_menu'] ?>" type="text" name="nama" id="nama" class="col-xs-12  col-sm-6" placeholder="Nama Menu" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Module Menu :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <input value="<?= $menu['module_menu'] ?>" type="text" name="module" id="module" class="col-xs-12  col-sm-6" placeholder="Module Menu" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Jenis Menu :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <select class="select2 width-100" name="jenis" id="jenis" data-placeholder="-------> Pilih Jenis Menu <-------">
                                <option value="" <?= (is_null($menu['parent_menu'])) ? 'selected' : '' ; ?>>  </option>
                                <option value="0" <?= ($menu['parent_menu'] == '0') ? 'selected' : '' ; ?>>Menu Utama</option>
                                <option value="1" <?= (!(is_null($menu['parent_menu'])) && $menu['parent_menu'] != '0') ? 'selected' : '' ; ?> >Sub Menu</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group hide parent_related">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right"></label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <select class="select2 width-100" name="menu" id="menu" data-placeholder="-------> Pilih Menu <-------">
                                <option value=""> </option>
                                <?php
                                foreach ($parent['data'] as $val) {
                                    $selected = ($menu['parent_menu'] == $val['id_menu']) ? 'selected' : '';
                                    echo '<option value="'.$val['id_menu'].'"  '.$selected.'>'.$val['nama_menu'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group hide parent_related">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right"></label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <div class="checkbox">
                                <label>
                                    <input <?= ($aksi['index'] == 1) ? 'checked ' : ''?> value="1" name="lihat" type="checkbox" class="ace" />
                                    <span class="lbl"> Lihat</span>
                                </label>
                                <label>
                                    <input <?= ($aksi['add'] == 1) ? 'checked ' : ''?> value="1" name="tambah" type="checkbox" class="ace" />
                                    <span class="lbl"> Tambah</span>
                                </label>
                                <label>
                                    <input <?= ($aksi['edit'] == 1) ? 'checked ' : ''?> value="1" name="ubah" type="checkbox" class="ace" />
                                    <span class="lbl"> Ubah</span>
                                </label>
                                <label>
                                    <input <?= ($aksi['delete'] == 1) ? 'checked ' : ''?> value="1" name="hapus" type="checkbox" class="ace" />
                                    <span class="lbl"> Hapus</span>
                                </label>
                                <label>
                                    <input <?= ($aksi['detail'] == 1) ? 'checked ' : ''?> value="1" name="detail" type="checkbox" class="ace" />
                                    <span class="lbl"> Detail</span>
                                </label>
                                <label>
                                    <input <?= ($aksi['cetak'] == 1) ? 'checked ' : ''?> value="1" name="cetak" type="checkbox" class="ace" />
                                    <span class="lbl"> Cetak</span>
                                </label>
                                <label>
                                    <input <?= ($aksi['export'] == 1) ? 'checked ' : ''?> value="1" name="export" type="checkbox" class="ace" />
                                    <span class="lbl"> Export</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Icon Menu :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <input value="<?= $menu['icon_menu'] ?>" type="text" name="icon" id="icon" class="col-xs-12  col-sm-4" placeholder="Icon Menu" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Order Menu :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <input value="<?= $menu['order_menu'] ?>" type="number" name="order" id="icon" class="col-xs-12  col-sm-2" placeholder="Order Menu" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Tampilkan :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <label class="control-label">
                                <input <?= ($menu['status_menu'] == '1') ? 'checked' : '' ; ?> name="status" value="1" type="radio" class="ace" />
                                <span class="lbl"> YA </span>
                            </label>&nbsp;&nbsp;&nbsp;
                            <label class="control-label">
                                <input <?= ($menu['status_menu'] == '0') ? 'checked' : '' ; ?> name="status" value="0" type="radio" class="ace" />
                                <span class="lbl"> TIDAK </span>
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
        is_selected($("select#jenis").val());
        validate_form();
    });
    $("select#jenis").change(function(e){
        is_selected(this.value);
    });
    function is_selected(val) {
        if(val === '0' || val === '') {
            $('.parent_related').addClass('hide');
        } else {
            $('.parent_related').removeClass('hide');
        }
    }
    function validate_form() {
        jsfValidate("#validation-form", {
            nama: {
                required: true
            },
            module: {
                required: true
            },
            jenis: {
                required: true
            },
            order: {
                required: true
            },
            status: {
                required: true
            }
        });
    }
</script>                    
