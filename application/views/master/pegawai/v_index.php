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
            <form id="search-form" action="<?= site_url($module.'/export') ?>" name="form" class="form-horizontal" method="POST">
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-2 no-padding-right">Jenis :</label>
                    <div class="col-xs-12 col-sm-2">
                        <div class="clearfix">
                            <select class="select2 width-100" name="jenis" id="jenis" data-placeholder="----> Pilih Opsi <----">
                                <option value=""> </option>
                                <?php
                                foreach (load_array('jenis_pegawai') as $val) {
                                    echo '<option value="'.$val.'">'.$val.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-2 no-padding-right">Unit Kerja :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <input type="hidden" name="unit" id="unit" class="width-100"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-2 no-padding-right">Jabatan Fungsional :</label>
                    <div class="col-xs-12 col-sm-2">
                        <div class="clearfix">
                            <select class="select2 width-100" name="akademik" id="akademik" data-placeholder="----> Pilih Opsi <----">
                                <option value=""> </option>
                                <?php
                                foreach (load_array('fungsional') as $val) {
                                    echo '<option value="'.$val.'">'.$val.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-6">
                        <button class="btn btn-primary btn-white btn-bold" name="cari" id="btn-search" type="button">
                            <i class="ace-icon fa fa-search-plus"></i>
                            Pencarian
                        </button>
                        <button class="btn btn-success btn-white btn-bold hide" name="export" id="btn-export" type="submit">
                            <i class="ace-icon fa fa-file-excel-o"></i>
                            Export
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-xs-12">
            <div class="widget-box widget-color-blue2">
                <div class="widget-header">
                    <h5 class="widget-title bigger lighter">
                        <i class="ace-icon fa fa-list"></i>
                        <?= $title[1] ?>
                    </h5>
                    <div class="widget-toolbar no-border">
                        <div class="btn-group btn-overlap">
                            <a href="<?= site_url($module.'/add') ?>" class="btn btn-white btn-primary btn-bold">
                                <i class="fa fa-plus-square bigger-110 blue"></i> Tambah Data
                            </a>
                        </div>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="widget-main padding-2 table-responsive">
                        <table id="index-table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Unit Kerja</th>
                                    <th>Jabatan</th>
                                    <th>NUPTK/NIDN</th>
                                    <th>Fungsional<br><small>Masa Kerja</small></th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.page-content -->
         
<?php
load_js(array(
    'theme/aceadmin/assets/js/dataTables/jquery.dataTables.js',
    'theme/aceadmin/assets/js/dataTables/jquery.dataTables.bootstrap.js',
    'theme/aceadmin/assets/js/select2.js',
));
?>
<script type="text/javascript">
    const module = "<?= site_url($module) ?>";
    
    let indexTable;
    $(document).ready(function () {
        $(".select2").select2({allowClear: true});
        loadIndex();
        getSelect();
    });
    $(document.body).on("click", "#delete-btn", function(e) {
        e.preventDefault();
        var id = $(this).attr("itemid");
        var name = $(this).attr("itemprop");
        var title = "<h4 class='red center'><i class='ace-icon fa fa-exclamation-triangle red'></i> Peringatan !</h4>";
        var msg = "<p class='center grey bigger-120'><i class='ace-icon fa fa-hand-o-right blue'></i>" + 
                " Apakah anda yakin akan menghapus data <br/><b>" + name + "</b> ? </p>";
        bootbox.confirm({ title: title, message: msg, 
            buttons: {
                cancel: { label: "<i class='ace-icon fa fa-times bigger-110'></i> Batal", className: "btn btn-sm" },
                confirm: { label: "<i class='ace-icon fa fa-trash-o bigger-110'></i> Hapus", className: "btn btn-sm btn-danger"}
            },
            callback: function(result) {
                if (result === true) {
                    var form = $('<form>', {  method: 'POST', action: module + '/delete' });
                    form.append($('<input>', { type: 'hidden', name: 'id', value: id }));
                    $('body').append(form); form.submit();
                }
            }
        });
    });
    $(document.body).on("click", "#akun-btn", function(e) {
        e.preventDefault();
        var id = $(this).attr("itemid");
        var name = $(this).attr("itemprop");
        var title = "<h4 class='green center'><i class='ace-icon fa fa-user-secret'></i> Buat Akun </h4>";
        var msg = "<p class='center grey bigger-120'><i class='ace-icon fa fa-hand-o-right blue'></i>" + 
                " Apakah anda yakin akan membuat Akun : <br/><b>" + name + "</b> ? </p>";
        bootbox.confirm({ title: title, message: msg, 
            buttons: {
                cancel: { label: "<i class='ace-icon fa fa-times bigger-110'></i> Batal", className: "btn btn-sm" },
                confirm: { label: "<i class='ace-icon fa fa-check bigger-110'></i> Ya, Buat", className: "btn btn-sm btn-success"}
            },
            callback: function(result) {
                if (result === true) {
                    window.location.replace(module + "_do/add/" + id);
                }
            }
        });
    });
    function loadIndex() {
        const indexTable = new DataTableManager("#index-table", {
            bServerSide: true,
            ajax: {
                url: module + "/ajax/type/table/source/index",
                type: "POST",
                data: function (val) {
                    val.jenis = $("#jenis").val();
                    val.unit = $("#unit").val();
                    val.akademik = $("#akademik").val();
                }
            },
            aoColumnDefs: [
                {bSortable: false, aTargets: [0,6]},
                {bSearchable: false, aTargets: [0,6]},
                {sClass: "center", aTargets: [0, 1, 2, 3, 4, 5]},
                {sClass: "center nowrap", aTargets: [6]}
            ]
        }).init();
        $("#btn-search").click(function () {
            indexTable.reload();
        });
    }
    function getSelect() {
        $("#unit").select2({
            placeholder: "-----> Pilih Opsi <-----",
            allowClear: true,
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
            }
        });
    }
</script>     