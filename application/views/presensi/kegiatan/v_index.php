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
                    <label class="control-label col-xs-12 col-sm-2 no-padding-right">Pegawai :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <input type="hidden" name="pegawai" id="pegawai" class="width-100"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-2 no-padding-right">Jenis Pegawai :</label>
                    <div class="col-xs-12 col-sm-2">
                        <div class="clearfix">
                            <select class="select2 width-100" name="jenis" id="jenis" data-placeholder="---> Pilih Opsi <---">
                                <option value=""> </option>
                                <?php
                                foreach (load_array('jenis_pegawai') as $val) {
                                    echo '<option value="'.$val.'" >'.$val.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-2 no-padding-right">Status :</label>
                    <div class="col-xs-12 col-sm-2">
                        <div class="clearfix">
                            <select class="select2 width-100" name="status" id="status" data-placeholder="---> Pilih Opsi <---">
                                <option value=""> </option>
                                <?php
                                foreach (['TEPAT WAKTU', 'TERLAMBAT'] as $val) {
                                    echo '<option value="'.$val.'" >'.$val.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-2 no-padding-right">Validasi :</label>
                    <div class="col-xs-12 col-sm-2">
                        <div class="clearfix">
                            <select class="select2 width-100" name="valid" id="valid" data-placeholder="---> Pilih Opsi <---">
                                <option value=""></option>
                                <option value="1"> YA </option>
                                <option value="0"> TIDAK </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-2 no-padding-right">Range :</label>
                    <div class="col-xs-12 col-sm-2">
                        <div class="clearfix">
                            <div class="input-daterange input-group">
                                <input value="<?= date('Y-m-d') ?>" type="text" class="form-control" name="awal" id="awal" placeholder="Awal" />
                                <span class="input-group-addon">
                                    <i class="fa fa-exchange"></i>
                                </span>
                                <input value="<?= date('Y-m-d') ?>" type="text" class="form-control" name="akhir" id="akhir" placeholder="Akhir"/>
                            </div>
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
                            <button class="btn btn-white btn-success btn-bold" id="btn-validate">
                                <i class="fa fa-check bigger-110"></i> Validasi
                            </button>
                        </div>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="widget-main padding-2 table-responsive">
                        <table id="index-table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">
                                        <label class="pos-rel">
                                            <input type="checkbox" class="ace ace-checkbox-2"/>
                                            <span class="lbl"></span>
                                        </label>
                                    </th>
                                    <th>Pegawai</th>
                                    <th>Presensi</th>
                                    <th>Validasi & Status</th>
                                    <th>Agenda</th>
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
        'theme/aceadmin/assets/js/date-time/bootstrap-datepicker.js',
    ));
?>
<script type="text/javascript">
    const module = "<?= site_url($module) ?>";
    let indexTable;
    $(document).ready(function () {
        $(".select2").select2({allowClear: true});
        $(".input-daterange, #post_tgl").datepicker({
            autoclose: true, todayHighlight: true, format: 'yyyy-mm-dd'
        });
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
        bootbox.confirm({title: title, message: msg, 
            buttons: {
                cancel: {label: "<i class='ace-icon fa fa-times bigger-110'></i> Batal",className: "btn btn-sm"},
                confirm: {label: "<i class='ace-icon fa fa-trash-o bigger-110'></i> Hapus",className: "btn btn-sm btn-danger"}
            },
            callback: function(result) {
                if (result === true) {
                    var form = $('<form>', { method: 'POST', action: module + '/delete' });
                    form.append($('<input>', { type: 'hidden', name: 'id', value: id }));
                    $('body').append(form);form.submit();
                }
            }
        });
    });
    $(document.body).on("click", "#desc-btn", function(e) {
        e.preventDefault();
        const prop = $(this).attr("itemprop");
        bootbox.dialog({ message: `<p class="center">${prop}</p>`, backdrop: true, onEscape: true });
    });
    $("#index-table > thead > tr > th input[type=checkbox]").eq(0).on('click', function(){
        var $row = $("#index-table > tbody > tr > td:first-child input[type='checkbox']");
        if(!this.checked){
            $row.prop('checked', false).closest('tr').removeClass('success');  
        } else {
            $row.prop('checked', true).closest('tr').addClass('success');
        }
    });
    $("#index-table").on('click', 'td input[type=checkbox]' , function(){
        var $row = $(this).closest('tr');
        if(this.checked) $row.addClass('success');
        else $row.removeClass('success');
    });
    $("#btn-validate").click(function(e) {
        var rowcollection = indexTable.table.$("#is_select:checked", {"page": "all"});
        var id = "";
        var qty = 0;
        rowcollection.each(function(index, elem) {
            var checkbox_value = $(elem).val();
            id += checkbox_value + ',';
            qty++;
        });
        if(qty < 1){
            jsfNotif('Peringatan', 'Tidak ada data yang dipilih', 2, 'swal');
            return;
        }
        var title = "<h4 class='red center'><i class='ace-icon fa fa-exclamation-triangle red'></i> Peringatan !</h4>";
        var msg = "<p class='center grey bigger-120'><i class='ace-icon fa fa-hand-o-right blue'></i>" + 
                "<strong class='bigger-130 red'> " + qty + "</strong> <br> Data telah terpilih, klik Simpan untuk mengirim data!</p>";
        bootbox.confirm({title: title, message: msg, 
            buttons: {
                cancel: {
                    label: "<i class='ace-icon fa fa-times bigger-110'></i> Batal", className: "btn btn-sm"
                },
                confirm: {
                    label: "<i class='ace-icon fa fa-paper-plane bigger-110'></i> Simpan", className: "btn btn-sm btn-success"
                }
            },
            callback: function(result) {
                if (result === true) {
                    saveValidate(id);
                }
            }
        });
    });
</script>
<script type="text/javascript">
    function loadIndex()
    {
        indexTable = new DataTableManager("#index-table", {
            bServerSide: true,
            ajax: {
                url: module + "/ajax/type/table/source/index",
                type: "POST",
                data: function (val) {
                    val.pegawai = $("#pegawai").val();
                    val.jenis = $("#jenis").val();
                    val.status = $("#status").val();
                    val.valid = $("#valid").val();
                    val.awal = $("#awal").val();
                    val.akhir = $("#akhir").val();
                }
            },
            aoColumnDefs: [
                {bSortable: false, aTargets: [0,5]},
                {bSearchable: false, aTargets: [0,5]},
                {sClass: "center", aTargets: [0, 4]},
                {sClass: "center nowrap", aTargets: [1, 2, 3, 5]}
            ]
        }).init();
        $("#btn-search").click(function () {
            indexTable.reload();
        });
    }
    function saveValidate(id)
    {
        jsfRequest(module + "_do/ajax/type/action/source/validate", "POST",
        { 
            id: id
        }, { useLoading: true })
        .done(function(rs) {
            if (rs.status) {
                indexTable.reload();
                jsfNotif('Informasi', rs.msg, 1);
            } else {
                jsfNotif('Peringatan', rs.msg, 2, 'swal');
            }
        })
        .fail(function(err) {
            console.error("update:", err);
        });
    }
    function getSelect()
    {
        $("#pegawai").select2({
            placeholder: "-------> Pilih Opsi <-------",
            allowClear: true,
            ajax: {
                url: module + "/ajax/type/list/source/pegawai",
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
