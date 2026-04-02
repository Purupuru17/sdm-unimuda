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
                        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Foto</th>
                                    <th width="40%">Judul</th>
                                    <th>Jenis</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $no = 1;
                                foreach ($galeri['data'] as $row) {
                            ?>
                                <tr>
                                    <td><?= ctk($no); ?></td>
                                    <td>
                                        <img class="lazyload blur-up" style="height: 70px" src="<?= load_file($row['foto_galeri']) ?>" class="img-thumbnail"/>
                                    </td>
                                    <td><?= ctk($row['judul_galeri']); ?></td>
                                    <td>
                                        <?= ($row['jenis_galeri'] == '0') ? '<span class="label label-warning label-white arrowed">Upload File</span>' : '<span class="label label-info label-white arrowed">Link Youtube</span>' ?>
                                    </td>
                                    <td>
                                        <?= ($row['status_galeri'] == '0') ? '<span class="label label-danger label-white arrowed">Tidak Aktif</span>' : '<span class="label label-success label-white arrowed">Aktif</span>' ?>
                                    </td>
                                    <td><?= format_date($row['update_galeri'], 2); ?></td>
                                    <td nowrap>
                                        <div class="action-buttons">
                                            <a href="<?= site_url($module .'/edit/'. encode($row['id_galeri'])) ?>" class="tooltip-warning btn btn-white btn-warning btn-sm" data-rel="tooltip" title="Ubah Data">
                                                    <span class="orange"><i class="ace-icon fa fa-pencil-square-o bigger-130"></i></span>
                                                </a>
                                            <a href="#" name="<?= encode($row['id_galeri']) ?>" itemprop="<?= $row['judul_galeri'] ?>" id="delete-btn" class="tooltip-error btn btn-white btn-danger btn-sm" data-rel="tooltip" title="Hapus Data">
                                                <span class="red"><i class="ace-icon fa fa-trash-o bigger-130"></i></span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                                $no++;
                                }
                            ?>
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
        
    ));
?>
<script type="text/javascript">
    $(document.body).on("click", "#delete-btn", function(event) {
        var id = $(this).attr("name");
        var name = $(this).attr("itemprop");
        var title = "<h4 class='red center'><i class='ace-icon fa fa-exclamation-triangle red'></i>" + 
                " Peringatan !</h4>";
        var msg = "<p class='center grey bigger-120'><i class='ace-icon fa fa-hand-o-right blue'></i>" + 
                " Apakah anda yakin akan menghapus data <br/><b>" + name + "</b> ? </p>";
        bootbox.confirm({
            title: title,
            message: msg, 
            buttons: {
                cancel: {
                    label: "<i class='ace-icon fa fa-times bigger-110'></i> Batal",
                    className: "btn btn-sm"
                },
                confirm: {
                    label: "<i class='ace-icon fa fa-trash-o bigger-110'></i> Hapus",
                    className: "btn btn-sm btn-danger"
                }
            },
            callback: function(result) {
                if (result === true) {
                    window.location.replace("<?= site_url($module . '/delete/'); ?>" + id);
                }
            }
        });
    });
</script>
<script type="text/javascript">
    var table;
    $(document).ready(function() {
        $('[data-rel="tooltip"]').tooltip({placement: 'top'});
        table = $('#dynamic-table')
            .dataTable({
                bScrollCollapse: true,
                bAutoWidth: false,
                aaSorting: [],
                aoColumnDefs: [
                {
                    bSortable: false,
                    aTargets: [0,6]
                },
                {
                    bSearchable: false,
                    aTargets: [0,6]
                },
                {"sClass": "center", aTargets: [0,1,2,3,4,5,6]}
            ],
            "oLanguage": {
                "sSearch": "Cari : ",
                "sInfoEmpty": "Menampilkan dari 0 sampai 0 dari total 0 data",
                "sInfo": "Menampilkan dari _START_ sampai _END_ dari total _TOTAL_ data",
                "sLengthMenu": "_MENU_ data per halaman",
                "sZeroRecords": "Maaf tidak ada data yang ditemukan",
                "sInfoFiltered": "(Menyaring dari _MAX_ total data)"
            }
        });
        table.fnAdjustColumnSizing();
    });
</script>                  
