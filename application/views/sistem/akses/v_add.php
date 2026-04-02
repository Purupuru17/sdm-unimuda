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
            <form id="validation-form" action="<?= site_url($action); ?>" name="form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="group" name="group" value="<?= encode($group['id_group']) ?>">
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">User Aplikasi :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <input required="" type="hidden" name="user" id="user" class="width-100 text-center"/>
                        </div>
                    </div>
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
        <div class="col-xs-12">
            <div class="widget-box transparent">
                <div class="widget-body">
                    <div class="widget-main padding-2 table-responsive">
                        <table id="index-table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Lengkap</th>
                                    <th>Username</th>
                                    <th>Group Role</th>
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
    'theme/aceadmin/assets/js/dataTables/jquery.dataTables.bootstrap.js',
    
    'theme/aceadmin/assets/js/select2.js',
    'theme/aceadmin/assets/js/jquery.validate.js'
));
?>
<script type="text/javascript">
    const module = "<?= site_url($module) ?>";
    let indexTable;
    $(document).ready(function() {
        $(".select2").select2({allowClear: true});
        validate_form();
        table_manager();
        load_select();
        load_index();
    });
    $(document.body).on("click", "#delete-btn", function(e) {
        e.preventDefault();
        var id = $(this).attr("itemid");
        var user = $(this).attr("itemprop");
        var title = "<h4 class='red center'><i class='ace-icon fa fa-exclamation-triangle red'></i> Peringatan !</h4>";
        var msg = "<p class='center grey bigger-120'><i class='ace-icon fa fa-hand-o-right blue'></i>" + 
                " Apakah anda yakin akan menghapus data ini ? </p>";
        bootbox.confirm({ title: title, message: msg, 
            buttons: {
                cancel: { label: "<i class='ace-icon fa fa-times bigger-110'></i> Batal", className: "btn btn-sm" },
                confirm: { label: "<i class='ace-icon fa fa-trash-o bigger-110'></i> Hapus", className: "btn btn-sm btn-danger"}
            },
            callback: function(result) {
                if (result === true) {
                    var form = $('<form>', {  method: 'POST', action: module + '/delete' });
                    form.append($('<input>', { type: 'hidden', name: 'id', value: id }));
                    form.append($('<input>', { type: 'hidden', name: 'user', value: user }));
                    $('body').append(form); form.submit();
                }
            }
        });
    });
    function load_select() {
        $("#user").select2({
            placeholder: "-------> Pilih User Aplikasi <-------",
            minimumInputLength: 3,
            ajax: {
                url: module + "/ajax/type/list/source/user",
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
    function load_index(){
        indexTable.loadData(
            module + "/ajax/type/table/source/user", { id : $("#group").val()  },
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
    function validate_form() {
        jsfValidate("#validation-form", {
            user: { required: true }
        });
    }
</script>                   
