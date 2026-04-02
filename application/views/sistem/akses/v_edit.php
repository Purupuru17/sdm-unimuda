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
            <div class="widget-box transparent">
                <div class="widget-header">
                    <h5 class="widget-title bigger lighter">
                        <i class="ace-icon fa fa-windows"></i>
                        Daftar Menu
                    </h5>
                </div>
                <div class="widget-body">
                    <div class="widget-main padding-2 table-responsive">
                        <form action="<?= site_url($action); ?>" name="form" method="POST" enctype="multipart/form-data">
                            <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="center">&nbsp;#&nbsp;</th>
                                        <th class="center">Nama Menu</th>
                                        <th class="center">Parent Menu</th>
                                        <th class="center">Module Menu</th>
                                        <th class="center" width="50%">Hak Akses</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 0;
                                    for ($i; $i < sizeof($akses); $i++) {
                                    ?>
                                    <tr>
                                        <td class="center"><?= $i + 1; ?></td>
                                        <td class="center"><?= $akses[$i]['nama_menu'] ?> </td>
                                        <td class="center"><?= ($akses[$i]['parent_menu'] == '0') ?  'Menu Utama' : 'Sub Menu'; ?></td>
                                        <td class="center"><?= $akses[$i]['module_menu'] ?> </td>
                                        <td class="center" nowrap>
                                            <div class="form-group">
                                                <label>
                                                    <input name="lihat<?= $i ?>" <?= $akses[$i]['index'] == 1 ? 'checked="checked" ' : '' ?> type="checkbox" class="ace" />
                                                    <span class="lbl"> Lihat</span>
                                                </label>
                                                <label>
                                                    <input name="tambah<?= $i ?>" <?= $akses[$i]['add'] == 1 ? 'checked="checked" ' : '' ?> <?= $akses[$i]['parent_menu'] == 0 ? 'disabled="disabled"' : '' ?>  type="checkbox" class="ace" />
                                                    <span class="lbl"> Tambah</span>
                                                </label>
                                                <label>
                                                    <input name="ubah<?= $i ?>" <?= $akses[$i]['edit'] == 1 ? 'checked="checked" ' : '' ?> <?= $akses[$i]['parent_menu'] == 0 ? 'disabled="disabled"' : '' ?> type="checkbox" class="ace" />
                                                    <span class="lbl"> Ubah</span>
                                                </label>
                                                <label>
                                                    <input name="hapus<?= $i ?>" <?= $akses[$i]['delete'] == 1 ? 'checked="checked" ' : '' ?> <?= $akses[$i]['parent_menu'] == 0 ? 'disabled="disabled"' : '' ?> type="checkbox" class="ace" />
                                                    <span class="lbl"> Hapus</span>
                                                </label>
                                                <label>
                                                    <input name="detail<?= $i ?>" <?= $akses[$i]['detail'] == 1 ? 'checked="checked" ' : '' ?> <?= $akses[$i]['parent_menu'] == 0 ? 'disabled="disabled"' : '' ?> type="checkbox" class="ace" />
                                                    <span class="lbl"> Detail</span>
                                                </label>
                                                <label>
                                                    <input name="cetak<?= $i ?>" <?= $akses[$i]['cetak'] == 1 ? 'checked="checked" ' : '' ?> <?= $akses[$i]['parent_menu'] == 0 ? 'disabled="disabled"' : '' ?> type="checkbox" class="ace" />
                                                    <span class="lbl"> Cetak</span>
                                                </label>
                                                <label>
                                                    <input name="export<?= $i ?>" <?= $akses[$i]['export'] == 1 ? 'checked="checked" ' : '' ?> <?= $akses[$i]['parent_menu'] == 0 ? 'disabled="disabled"' : '' ?> type="checkbox" class="ace" />
                                                    <span class="lbl"> Export</span>
                                                </label>
                                            </div>

                                            <input type="hidden" name="id_menu<?= $i ?>" value="<?= $akses[$i]['id_menu'] ?>" />
                                        </td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <input type="hidden" name="total" value="<?= sizeof($akses) ?>" />
                            <hr class="hr dotted">
                            <div class="clearfix form-group">
                                <div class="col-md-offset-5 col-md-7">
                                    <button  type="reset" class="btn btn-default">
                                        <i class="ace-icon fa fa-undo bigger-110"></i>
                                        Reset
                                    </button>
                                    &nbsp; &nbsp; &nbsp;
                                    <button class="btn btn-success" name="simpan" type="submit">
                                        <i class="ace-icon fa fa-check bigger-110"></i>
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