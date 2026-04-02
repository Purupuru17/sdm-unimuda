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
        </div><!-- /.col -->
        <div class="col-xs-12">
            <form id="search-form" action="#" name="form" class="form-horizontal" method="POST">
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-1 no-padding-right">Group :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <select class="select2 width-100" name="group" id="group" data-placeholder="-----> Pilih Group <-----">
                                <option value=""> </option>
                                <?php
                                foreach ($group['data'] as $val) {
                                    echo '<option value="'.$val['id_group'].'">'.$val['nama_group'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-offset-1 col-md-4">
                        <button class="btn btn-primary btn-white btn-bold" name="cari" id="btn-search" type="button">
                            <i class="ace-icon fa fa-search-plus"></i>
                            Pencarian
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-xs-12">
            <div class="widget-box widget-color-red">
                <div class="widget-header">
                    <h5 class="widget-title bigger lighter">
                        <i class="ace-icon fa fa-list"></i>
                        <?= $title[1] ?>
                    </h5>
                    <div class="widget-toolbar no-border">
                        <div class="btn-group btn-overlap">
                            <a href="<?= site_url($module.'/add') ?>" class="btn btn-white btn-primary btn-bold">
                                <i class="fa fa-plus-square bigger-120 blue"></i> Tambah Data
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
                                    <th>Nama Lengkap</th>
                                    <th>Username</th>
                                    <th>Group</th>
                                    <th>Status</th>
                                    <th>Dibuat</th>
                                    <th width="20%">Terakhir Login</th>
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
    </div><!-- /.row -->
</div><!-- /.page-content -->
<?php
load_js(array(
    'theme/aceadmin/assets/js/dataTables/jquery.dataTables.js',
    'theme/aceadmin/assets/js/dataTables/jquery.dataTables.bootstrap.js',
    
    'theme/aceadmin/assets/js/select2.js'
));
?>          
<script type="text/javascript">
    const module = "<?= site_url($module) ?>";
    $(document).ready(function () {
        $(".select2").select2({allowClear: true});
        $(".select2-chosen").addClass("center");
        load_index();
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
    function load_index() {
        const indexTable = new DataTableManager("#index-table", {
            bServerSide: true,
            ajax: {
                url: module + "/ajax/type/table/source/index",
                type: "POST",
                data: function (val) {
                    val.group = $("#group").val();
                }
            },
            aoColumnDefs: [
                {bSortable: false, aTargets: [0,7]},
                {bSearchable: false, aTargets: [0,7]},
                {sClass: "center", aTargets: [0, 1, 2, 3, 4, 5, 6]},
                {sClass: "center nowrap", aTargets: [7]}
            ]
        }).init();
        // reload filter
        $("#btn-search").click(function () {
            indexTable.reload();
        });
    }
</script>