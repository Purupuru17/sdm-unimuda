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
            <form id="search-form" action="<?= site_url($module.'_do/export') ?>" name="form" class="form-horizontal" method="POST">
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
                        <button class="btn btn-success btn-white btn-bold" name="export" id="btn-export" type="submit">
                            <i class="ace-icon fa fa-file-excel-o"></i>
                            Export
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-xs-12">
            <div class="space-4"></div>
            <div class="tabbable">
                <ul class="nav nav-tabs padding-10">
                    <li class="active">
                        <a data-toggle="tab" href="#satu" class="">
                            <i class="ace-icon fa fa-check-square-o bigger-120 blue"></i>
                            Presensi
                        </a>
                    </li>
                    <li class="">
                        <a data-toggle="tab" href="#dua" class="">
                            <i class="ace-icon fa fa-users bigger-120 green"></i>
                            Rekap Kehadiran
                        </a>
                    </li>
                </ul>
                <div class="tab-content no-border padding-10">
                    <div id="satu" class="tab-pane active in">
                        <div class="row">
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
                                                        <th>Pegawai</th>
                                                        <th>Tanggal</th>
                                                        <th>Masuk</th>
                                                        <th>Pulang</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="dua" class="tab-pane fade">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="widget-box widget-color-green2">
                                    <div class="widget-header">
                                        <h5 class="widget-title bigger lighter">
                                            <i class="ace-icon fa fa-list"></i>
                                            <?= $title[1] ?>
                                        </h5>
                                        <div class="widget-toolbar">
                                            <div class="btn-group btn-overlap">
                                                <button onclick="loadRekap()" class="btn btn-white btn-primary btn-bold">
                                                    <i class="fa fa-search-plus bigger-110"></i> Lihat Data
                                                </button>
                                            </div>
                                        </div>
                                        <div class="widget-toolbar no-border">
                                            <span class="badge badge-success smaller-70">TEPAT+PULANG</span>
                                            <span class="badge badge-info smaller-70">TERLAMBAT+PULANG</span>
                                            <span class="badge badge-warning smaller-70">TEPAT</span>
                                            <span class="badge badge-danger smaller-70">TERLAMBAT</span>
                                        </div>
                                    </div>
                                    <div class="widget-body">
                                        <p id="one-spin" style="display: none; margin-top: 10px;" class="bigger-130 blue" align="center">
                                            <i class="fa fa-spinner fa-spin fa-fw fa-2x"></i> Loading . . .
                                        </p>
                                        <div class="widget-main padding-2 table-responsive">
                                            <table id="rekap-table" class="table table-striped table-bordered table-hover">
                                                <thead>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.col -->
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
    let indexTable, rekapTable;
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
    $(document.body).on("click", "#imgMasuk-btn", function(e) {
        e.preventDefault();
        const id = $(this).attr("itemid");
        const title = "<h4 class='blue center'><i class='ace-icon fa fa-image'></i> Foto Masuk </h4>";
        const msg = `<p class="center"><img src="${id}" class="img-thumbnail" width="70%"/> </p>`;
        bootbox.dialog({ title: title, message: msg, backdrop: true, onEscape: true });
    });
    $(document.body).on("click", "#imgPulang-btn", function(e) {
        e.preventDefault();
        const id = $(this).attr("itemid");
        const title = "<h4 class='orange center'><i class='ace-icon fa fa-image'></i> Foto Pulang </h4>";
        const msg = `<p class="center"><img src="${id}" class="img-thumbnail" width="70%"/> </p>`;
        bootbox.dialog({ title: title, message: msg, backdrop: true, onEscape: true });
    });
    $(document.body).on("click", "#view-btn", function(e) {
        e.preventDefault();
        const status = $(this).attr("itemid");
        const wkmasuk = $(this).attr("itemwm");
        const wkpulang = $(this).attr("itemwp");
        const ftmasuk = $(this).attr("itemftm");
        const ftpulang = $(this).attr("itemftp");
        let html = `<table class="center bolder"><tr>`;
        if(wkmasuk){
            html += `<td>MASUK<br><img src="${ftmasuk}" class="img-thumbnail" width="90%"/><br>${wkmasuk}</td>`;
        }
        if(wkpulang){
            html += `<td>PULANG<br><img src="${ftpulang}" class="img-thumbnail" width="90%"/><br>${wkpulang}</td>`;
        }
        html += `</tr></table>`;
        if(status){
            bootbox.dialog({title: `<h4 class="center">${status}</h4>`, message: html, backdrop: true, onEscape: true}); 
        }
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
                    val.awal = $("#awal").val();
                    val.akhir = $("#akhir").val();
                }
            },
            aoColumnDefs: [
                {bSortable: false, aTargets: [0,6]},
                {bSearchable: false, aTargets: [0,6]},
                {sClass: "center", aTargets: [0, 3, 4, 5]},
                {sClass: "center nowrap", aTargets: [1,2,6]}
            ]
        }).init();
        $("#btn-search").click(function () {
            indexTable.reload();
        });
    }
    function loadRekap()
    {
        $("#one-spin").show();
        $('#rekap-table thead').html('');
        if ($.fn.dataTable.isDataTable('#rekap-table')) {
            $('#rekap-table').DataTable().clear().destroy();
        }
        jsfRequest(module + "/ajax/type/table/source/rekap", "POST",
        { 
            pegawai: $("#pegawai").val(), jenis: $("#jenis").val(),
            awal: $("#awal").val(), akhir : $("#akhir").val()
        }, { useLoading: true })
        .done(function(rs) {
            if (rs.status) {
                setTable(rs.data);
            } else {
                jsfNotif('Peringatan', rs.msg, 2, 'swal');
            }
            $("#one-spin").hide();
        })
        .fail(function(err) {
            console.error("load:", err);
        });
    }
    function setTable(res)
    {
        // ================= HEADER =================
        var trHead = `<tr><th width="5%">#</th><th width="15%">Nama</th>`;
        $.each(res.periode, function(i, tgl) {
            var date = new Date(tgl);
            var hari = date.toLocaleDateString('id-ID', { weekday: 'long' });
            var tgl = date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
            
            trHead += `<th>${tgl}<br><small>${hari}</small></th>`;
        });
        trHead += `<th width="10%">Kehadiran</th></tr>`;
        $('#rekap-table thead').html(trHead);
        // ================= LOAD =================
        rekapTable = $("#rekap-table").dataTable({
            bScrollCollapse: true,
            bAutoWidth: false, aaSorting: [],
            aoColumnDefs: [
                {
                    bSortable: false,
                    sClass: "center nowrap", aTargets: ["_all"]
                }, { bSearchable: true, aTargets: [1] }
            ]
        });
        $.each(res.table, function (index, value) {
            rekapTable.fnAddData(value);
        });
        rekapTable.fnAdjustColumnSizing();
        $('[data-rel="tooltip"]').tooltip({ placement: 'top' });
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
