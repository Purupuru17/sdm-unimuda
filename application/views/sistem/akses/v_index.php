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
            <div class="widget-box widget-color-red">
                <div class="widget-header">
                    <h5 class="widget-title bigger lighter">
                        <i class="ace-icon fa fa-list"></i>
                        <?= $title[1] ?>
                    </h5>
                </div>
                <div class="widget-body">
                    <div class="widget-main padding-2 table-responsive">
                        <table id="index-table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Group</th>
                                    <th>Keterangan</th>
                                    <th>Super Admin</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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
    'theme/aceadmin/assets/js/dataTables/jquery.dataTables.bootstrap.js'
));
?>
<script type="text/javascript">
    const module = "<?= site_url($module) ?>";
    let indexTable;
    $(document).ready(function () {
        table_manager();
        load_index();
    });
    function load_index(){
        indexTable.loadData(
            module + "/ajax/type/table/source/index", {  },
            function (rs) {
                if (!rs.status) {
                    jsfNotif("Peringatan", rs.msg, 2);
                }
            }
        );
    }
    function table_manager() {
        indexTable = new DataTableManager("#index-table", {
            aoColumnDefs: [
                {bSortable: false, aTargets: [0,4]},
                {bSearchable: false, aTargets: [0,4]},
                {sClass: "center", aTargets: [0, 1, 2, 3]},
                {sClass: "center nowrap", aTargets: [4]}
            ]
        }).init();
    }
</script>                  
