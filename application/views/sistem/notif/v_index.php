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
                    <div class="widget-toolbar no-border">
                        <div class="btn-group btn-overlap">
                            <a href="<?= site_url($module.'/add') ?>" class="btn btn-white btn-primary btn-bold">
                                <i class="fa fa-plus-square bigger-120 blue"></i> Tambah Data
                            </a>
                            <button id="delete-all" class="btn btn-white btn-danger btn-bold">
                                <i class="fa fa-trash-o bigger-120 red"></i> Hapus Data
                            </button>
                        </div>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="widget-main padding-2 table-responsive">
                        <table id="index-table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>
                                        <label class="pos-rel">
                                            <input type="checkbox" class="ace"/>
                                            <span class="lbl"></span>
                                        </label>
                                    </th>
                                    <th>User</th>
                                    <th>Judul</th>
                                    <th width="30%">Pesan</th>
                                    <th>Waktu</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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
    
));
?>
<script type="text/javascript">
    const module = "<?= site_url($module) ?>";
    let indexTable;
    $(document).ready(function () {
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
    $(document).on('click', '#link-btn', function(){
        jsfAutoload($(this).attr("itemid"));
    });
    $(document.body).on("click", "#log-msg", function(event) {
        var json = JSON.parse(atob($(this).attr("itemname")));
        bootbox.dialog({title: `<h4 class="center">${$(this).attr("itemid")}</h4>`, 
            message: `<pre>${JSON.stringify(json, null, 4)}</pre>`, size: 'large', backdrop: true, onEscape: true});
    });
    $("#index-table > thead > tr > th input[type=checkbox]").eq(0).on('click', function(){
        var $row = $("#index-table > tbody > tr > td:first-child input[type='checkbox']");
        if(!this.checked){
            $row.prop('checked', false).closest('tr').removeClass('danger');
        } else {
            $row.prop('checked', true).closest('tr').addClass('danger');
        }
    });
    $("#index-table").on('click', 'td input[type=checkbox]' , function(){
        var $row = $(this).closest('tr');
        if(this.checked) $row.addClass('danger');
        else $row.removeClass('danger');
    });
    $("#delete-all").click(function(e) {
        var rowcollection = indexTable.table.$("#checkboxData:checked", {"page": "all"});
        var id = "";
        var qty = 0;
        rowcollection.each(function(index, elem) {
            var checkbox_value = $(elem).val();
            id += checkbox_value + ',';
            qty++;
        });
        if(id === ""){
            jsfNotif('Peringatan', 'Tidak ada data yang dipilih', 2);
            return;
        }
        var title = '<h4 class="red center"><i class="ace-icon fa fa-exclamation-triangle red"></i> Peringatan !</h4>';
        var msg = '<p class="center grey bigger-120"><i class="ace-icon fa fa-hand-o-right blue"></i>' + 
                ' <strong class="bigger-130 red"> ' + qty + '</strong><br/>Apakah anda yakin dengan data yang anda pilih ?</p>';
        bootbox.confirm({ title: title, message: msg, 
            buttons: {
                cancel: {
                    label: "<i class='ace-icon fa fa-times bigger-110'></i> Batal", className: "btn btn-sm"
                },
                confirm: {
                    label: "<i class='ace-icon fa fa-check bigger-110'></i> Kirim", className: "btn btn-sm btn-danger"
                }
            },
            callback: function(result) {
                if (result === true) {
                    delete_all(id);
                }
            }
        });
    });
</script>
<script type="text/javascript">
    function delete_all(id){
        jsfRequest(module + "/ajax/type/action/source/delete", "POST",
            {id: id}, {useLoading: true})
        .done(function(rs) {
            if (rs.status) {
                jsfNotif('Informasi', rs.msg, 1);
            }else{
                jsfNotif('Peringatan', rs.msg, 2);
            }
            indexTable.reload();
        })
        .fail(function(err) {
            console.error("delete:", err);
        });
    }
    function load_index() {
        indexTable = new DataTableManager("#index-table", {
            bServerSide: true,
            ajax: {
                url: module + "/ajax/type/table/source/index",
                type: "POST",
                data: function () {}
            },
            aoColumnDefs: [
                {bSortable: false, aTargets: [0,5]},
                {bSearchable: false, aTargets: [0,5]},
                {sClass: "center", aTargets: [0, 1, 2, 3, 4]},
                {sClass: "center nowrap", aTargets: [5]}
            ]
        }).init();
    }
</script>                  
