<?php
$this->load->view('sistem/v_breadcrumb');
?>
<div class="page-content">
    <div class="page-header">
        <h1>
            <?= $title[0] ?>
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                <?= $user['fullname'] ?>
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <?= $this->session->flashdata('notif'); ?>
        </div>
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <div class="">
                <div id="user-profile-2" class="user-profile">
                    <div class="tabbable">
                        <ul class="nav nav-tabs padding-18">
                            <li class="active">
                                <a data-toggle="tab" href="#home">
                                    <i class="green ace-icon fa fa-user bigger-120"></i>
                                    Profil
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content no-border padding-24">
                            <div id="home" class="tab-pane in active">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-3 center">
                                        <span class="profile-picture">
                                            <img src="<?= load_file($user['foto_user'],1) ?>" class="img-responsive blur-up lazyload" />
                                        </span>
                                        <div class="space space-4"></div>
                                        <a class="btn btn-sm btn-block btn-primary btn-white">
                                            <i class="ace-icon fa fa-user green"></i>
                                            <span class="bolder"><?= $user['fullname'] ?></span>
                                        </a>
                                    </div><!-- /.col -->
                                    <div class="col-xs-12 col-sm-9">
                                        <h4 class="blue">
                                            <span class="middle"><?= $user['fullname'] ?></span>
                                            <?= is_online($user['last_login']) ?>
                                        </h4>

                                        <div class="profile-user-info profile-user-info-striped">
                                            <div class="profile-info-row">
                                                <div class="profile-info-name"> Group </div>
                                                <div class="profile-info-value">
                                                    <span><?= $user['nama_group'] ?></span>
                                                </div>
                                            </div>
                                            <div class="profile-info-row">
                                                <div class="profile-info-name">Username</div>
                                                <div class="profile-info-value">
                                                    <span><?= $user['username'] ?></span>
                                                </div>
                                            </div>
                                            <div class="profile-info-row">
                                                <div class="profile-info-name"> Email </div>
                                                <div class="profile-info-value">
                                                    <span><?= $user['email'] ?></span>
                                                </div>
                                            </div>
                                            <div class="profile-info-row">
                                                <div class="profile-info-name"> Status </div>
                                                <div class="profile-info-value">
                                                    <?= ($user['status_user'] == '0') ? '<span class="label label-danger arrowed arrowed-in-right">Tidak Aktif</span>' : '<span class="label label-success arrowed arrowed-in-right">Aktif</span>' ?>
                                                </div>
                                            </div>
                                            <div class="profile-info-row">
                                                <div class="profile-info-name"> Log </div>
                                                <div class="profile-info-value">
                                                    <span class="blue"><i class="ace-icon fa fa-user"></i> &nbsp;&nbsp;<?= $user['log_user'] ?></span><br/>
                                                    <span class="green"><i class="ace-icon fa fa-pencil"></i> &nbsp;&nbsp;<?= format_date($user['buat_user'],0) ?></span><br/>
                                                    <span class="orange"><i class="ace-icon fa fa-clock-o"></i> &nbsp;&nbsp;<?= format_date($user['update_user'],0) ?></span>
                                                </div>
                                            </div>
                                            <div class="profile-info-row">
                                                <div class="profile-info-name">
                                                    <i class="middle ace-icon fa fa-sign-in bigger-130 orange"></i>
                                                </div>
                                                <div class="profile-info-value">
                                                    <span><?= selisih_wkt($user['last_login']) ?><hr class="margin-5">
                                                    <?= $user['ip_user'] ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /.col -->

                                </div><!-- /.row -->
                                
                                <div class="space-12"></div>
                                
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12">
                                        <div class="widget-box transparent">
                                            <div class="widget-header widget-header-small">
                                                <div class="widget-toolbar">
                                                    <a href="#" data-action="collapse" class="orange2">
                                                        <i class="ace-icon fa fa-chevron-up bigger-110"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="widget-body">
                                                <div class="widget-main padding-2 table-responsive">
                                                    <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Waktu</th>
                                                                <th>Info</th>
                                                                <th>Agent</th>
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
                            </div><!-- /#home -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- PAGE CONTENT ENDS -->
        </div>
        <!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.page-content -->
<?php
load_js(array(
    'theme/aceadmin/assets/js/dataTables/jquery.dataTables.js',
    'theme/aceadmin/assets/js/dataTables/jquery.dataTables.bootstrap.js'
));
?>                
<script type="text/javascript">
    var table;
    table = $('#dynamic-table')
    .dataTable({
        bScrollCollapse: true,
        bAutoWidth: false,
        aaSorting: [],
        aoColumnDefs: [
            {
                bSortable: false,
                aTargets: [0]
            },
            {
                bSearchable: false,
                aTargets: [0]
            },
            {   sClass: "center", aTargets: [0, 1, 2, 3]}
        ],
        oLanguage: {
            sSearch: "Cari : ",
            sInfoEmpty: "Menampilkan dari 0 sampai 0 dari total 0 data",
            sInfo: "Menampilkan dari _START_ sampai _END_ dari total _TOTAL_ data",
            sLengthMenu: "Menampilkan _MENU_ data per halaman",
            sZeroRecords: "Maaf tidak ada data yang ditemukan",
            sInfoFiltered: "(Menyaring dari _MAX_ total data)"
        }
    });
    table.fnAdjustColumnSizing();
</script> 