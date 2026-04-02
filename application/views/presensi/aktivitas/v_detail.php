<?php $this->load->view('sistem/v_breadcrumb'); ?>
<style>
    .profile-info-name{
        width: 160px;
    }
    th, td{
        text-align: center;
    }
</style>
<div class="page-content">
    <div class="page-header">
        <h1>
            <?= $title[1] ?>
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                <?= $title[0] ?>
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
                    <h4 class="widget-title lighter bolder"><?= $detail['nama_mhs'] ?></h4>
                    <div class="widget-toolbar">
                        <a href="#" data-action="collapse" class="orange2">
                            <i class="ace-icon fa fa-chevron-up bigger-125"></i>
                        </a>
                    </div>
                    <div class="widget-toolbar <?= (!$is_admin) ? 'hide' : '' ?>">
                        <div class="btn-group btn-overlap">
                            <a target="_blank" href="<?= site_url($module.'/edit/'. encode($detail['id_akm'])) ?>" 
                                class="btn btn-white btn-warning btn-sm btn-bold">
                                <i class="fa fa-pencil-square-o bigger-120"></i> Ubah Data
                            </a>
                            <button id="btn-akm" class="<?= ($detail['semester_id'] != $this->session->userdata('idsmt')) ? 'hide':'' ?> btn btn-white btn-success btn-sm btn-bold">
                                <i class="fa fa-paper-plane bigger-120"></i> Sinkron AKM
                            </button>
                        </div>
                        <select class="btn-xs center" name="status" id="status" data-placeholder="--> Pilih Status <--">
                            <option value=""> --> Pilih Status <-- </option>
                            <?php
                            foreach (load_array('st_akm') as $val) {
                                $selected = ($detail['status_akm'] == $val['id']) ? 'selected' : '';
                                echo '<option value="'.$val['id'].'" '.$selected.'>'.$val['txt'].'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="widget-toolbar no-border">
                        <div class="btn-group btn-overlap">
                            <a target="_blank" href="<?= site_url('master/mahasiswa/detail/'. encode($detail['id_mhs'])) ?>" 
                                class="btn btn-white btn-primary btn-sm btn-bold">
                                <i class="fa fa-list-ul bigger-120"></i> Riwayat KHS
                            </a>
                        </div>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="widget-main padding-6 no-padding-left no-padding-right">
                        <div id="user-profile-1" class="user-profile row">
                            <div class="col-xs-12 col-sm-6">
                                <div class="profile-user-info profile-user-info-striped">
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> NIM </div>
                                        <div class="profile-info-value">
                                            <span class="bolder blue"><?= $detail['nim'] ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Angkatan </div>
                                        <div class="profile-info-value">
                                            <span><?= $detail['angkatan'] ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Program Studi </div>
                                        <div class="profile-info-value">
                                            <span class="bolder"><?= $detail['nama_prodi'] ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Status </div>
                                        <div class="profile-info-value">
                                            <?= st_mhs($detail['status_mhs']) ?>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Jenis Kelamin </div>
                                        <div class="profile-info-value">
                                            <span><?= $detail['kelamin_mhs'] ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Agama </div>
                                        <div class="profile-info-value">
                                            <span><?= $detail['agama_mhs'] ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name bolder red">* Username </div>
                                        <div class="profile-info-value">
                                            <span class="bolder red"><?= element('username', $akun, '') ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name bolder red">* Password </div>
                                        <div class="profile-info-value">
                                            <span class="bolder red"><?= element('pass_mhs', $akun, '') ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Telepon </div>
                                        <div class="profile-info-value">
                                            <span><?= $detail['telepon_mhs'] ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Alamat </div>
                                        <div class="profile-info-value">
                                            <span><?= element('jalan', json_decode($detail['alamat_mhs'],true)) ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Sosial Media </div>
                                        <div class="profile-info-value">
                                            <span><?= element('sosmed_mhs', $akun, '') ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-4"></div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="profile-user-info profile-user-info-striped">
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Dosen PA </div>
                                        <div class="profile-info-value">
                                            <span class="bolder"><?= element('nama_dosen', $pa, '') ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> NIDN </div>
                                        <div class="profile-info-value">
                                            <span><?= element('nidn', $pa, '') ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-4"></div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="profile-user-info profile-user-info-striped">
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Semester </div>
                                        <div class="profile-info-value">
                                            <span class="bolder red"><?= is_periode($detail['semester_id']) ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Status AKM </div>
                                        <div class="profile-info-value">
                                            <span class="bolder bigger-110"><?= array_find($detail['status_akm'], load_array('st_akm')) ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Validasi KRS </div>
                                        <div class="profile-info-value">
                                            <span id="span-valid" class=""><?= st_aktif($detail['valid_akm'],1); ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Riwayat SKS</div>
                                        <div class="profile-info-value">
                                            <span class="bolder"><?= $detail['sks_total'] ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Riwayat IPK</div>
                                        <div class="profile-info-value">
                                            <span class="bolder blue bigger-120"><?= $detail['ipk_total'] ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> SKS Terambil</div>
                                        <div class="profile-info-value">
                                            <span class="bolder red bigger-120" id="span-sks"><?= $detail['sks_akm'] ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Mata Kuliah Terambil</div>
                                        <div class="profile-info-value">
                                            <span class="bolder" id="span-mk"><?= $detail['matkul_akm'] ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> UTS</div>
                                        <div class="profile-info-value">
                                            <span class=""><?= st_aktif($detail['valid_uts']) ?></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> UAS</div>
                                        <div class="profile-info-value">
                                            <span class=""><?= st_aktif($detail['valid_uas']) ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="tabbable">
                <ul class="nav nav-tabs padding-18">
                    <li class="active">
                        <a class="blue" data-toggle="tab" href="#tab1">
                            <i class="ace-icon fa fa-check-square-o bigger-120"></i>
                            KRS & KHS <?= is_periode($detail['semester_id']) ?>
                        </a>
                    </li>
                    <li class="<?= in_array($detail['status_akm'], array('M')) ? '':'hide' ?>">
                        <a class="grey" data-toggle="tab" href="#tab2">
                            <i class="ace-icon fa fa-graduation-cap bigger-120"></i>
                            Konversi MBKM : <?= $detail['program_mbkm'].' '.$detail['batch_mbkm'].' | '.$detail['tempat_mbkm'] ?>
                        </a>
                    </li>
                </ul>
                <div class="tab-content no-border padding-10">
                    <div id="tab1" class="tab-pane in active">
                        <div class="row">
                            <div class="col-xs-12">
                                <p id="rekap-spin" class="bigger-130 blue hide" align="center"><i class="fa fa-spinner fa-spin fa-fw fa-2x"></i> Loading . . .</p>
                                <div class="widget-box transparent">
                                    <div class="widget-header">
                                        <h5 class="widget-title"><i class="ace-icon fa fa-list-ol"></i> 
                                            Mata Kuliah
                                        </h5>
                                        <div class="widget-toolbar">
                                            <a href="#" id="span-log" data-action="settings" class="red">
                                                <i class="ace-icon fa fa-expand bigger-125"></i>
                                            </a>
                                            <a href="#" data-action="collapse" class="orange2">
                                                <i class="ace-icon fa fa-chevron-up bigger-125"></i>
                                            </a>
                                        </div>
                                        <div class="widget-toolbar no-border">
                                            <div class="btn-group btn-overlap">
                                                <button <?= (!$is_admin) && ($detail['valid_akm'] != '1') ? 'disabled=""' : '' ?> onclick="load_khs()" id="btn-khs" class="btn btn-white btn-primary btn-sm btn-bold">
                                                    <i class="fa fa-search-plus bigger-120"></i> Lihat Data
                                                </button>
                                                <button <?= $detail['valid_akm'] != '1' ? 'disabled=""' : '' ?> id="btn-print-krs" class="btn btn-white btn-warning btn-sm btn-bold">
                                                    <i class="fa fa-print bigger-120"></i> Cetak KRS
                                                </button>
                                                <button <?= $detail['valid_akm'] != '1' ? 'disabled=""' : '' ?> id="btn-print-khs" class="btn btn-white btn-success btn-sm btn-bold">
                                                    <i class="fa fa-print bigger-120"></i> Cetak KHS
                                                </button>
                                                <button <?= ($detail['valid_akm'] != '1' || $detail['valid_uts'] != '1') ? 'disabled=""' : '' ?> id="btn-print-uts" class="btn btn-white btn-default btn-sm btn-bold">
                                                    <i class="fa fa-print bigger-120"></i> Cetak Kartu UTS
                                                </button>
                                                <button <?= ($detail['valid_akm'] != '1' || $detail['valid_uas'] != '1') ? 'disabled=""' : '' ?> id="btn-print-uas" class="btn btn-white btn-danger btn-sm btn-bold">
                                                    <i class="fa fa-print bigger-120"></i> Cetak Kartu UAS
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="widget-body">
                                        <div class="widget-main padding-2 table-responsive">
                                            <table id="khs-table" class="table table-striped table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th colspan="5"></th>
                                                        <th colspan="3">Nilai</th>
                                                        <th colspan="2"></th>
                                                    </tr>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Kode MK</th>
                                                        <th>Mata Kuliah</th>
                                                        <th>Kelas</th>
                                                        <th>Bobot MK<br> (sks)</th>
                                                        <th>Huruf</th>
                                                        <th>Indeks</th>
                                                        <th>sks *<br> N.Indeks</th>
                                                        <th>Dosen</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="4">Jumlah</th>
                                                        <th id="txt-nsks" class="orange bigger-110">0</th>
                                                        <th colspan="2"></th>
                                                        <th id="txt-nindeks" class="grey bigger-110">0</th>
                                                        <th colspan="2"></th>
                                                    </tr>
                                                    <tr>
                                                        <th colspan="6">IP Semester</th>
                                                        <th colspan="4" id="txt-nipk" class="red bigger-120">0</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab2" class="tab-pane">
                        <div class="row">
                            <div class="col-xs-12">
                                <p id="two-spin" style="display: none" class="bigger-130 blue" align="center"><i class="fa fa-spinner fa-spin fa-fw fa-2x"></i> Loading . . .</p>
                                <div class="widget-box transparent">
                                    <div class="widget-header">
                                        <h5 class="widget-title"><i class="ace-icon fa fa-list-ol"></i> 
                                            Mata Kuliah
                                        </h5>
                                        <div class="widget-toolbar">
                                            <a href="#" data-action="collapse" class="orange2">
                                                <i class="ace-icon fa fa-chevron-up bigger-125"></i>
                                            </a>
                                        </div>
                                        <div class="widget-toolbar <?= (!$is_admin) ? 'hide' : '' ?>">
                                            <div class="btn-group btn-overlap">
                                                <button id="btn-aktiv" class="<?= ($detail['semester_id'] != $this->session->userdata('idsmt')) ? 'hide':'' ?> btn btn-white btn-success btn-sm btn-bold">
                                                    <i class="fa fa-paper-plane-o bigger-120"></i> Sinkron Aktivitas
                                                </button>
                                            </div>
                                            <select class="btn-xs center" name="jenis" id="jenis" data-placeholder="--> Pilih Jenis <--">
                                                <option value=""> --> Pilih Jenis Aktivitas <-- </option>
                                                <?php
                                                foreach (load_array('st_aktivitas') as $val) {
                                                    echo '<option value="'.encode($val['id']).'">'.$val['txt'].'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="widget-toolbar no-border">
                                            <div class="btn-group btn-overlap">
                                                <button onclick="load_mbkm()" id="btn-khs" class="btn btn-white btn-primary btn-sm btn-bold">
                                                    <i class="fa fa-search-plus bigger-120"></i> Lihat Data
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="widget-body">
                                        <div class="widget-main padding-2 table-responsive">
                                            <table id="mbkm-table" class="table table-striped table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th colspan="3">Mata Kuliah Asal / Uraian Kegiatan</th>
                                                        <th colspan="3">Mata Kuliah Konversi</th>
                                                    </tr>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Nama MK</th>
                                                        <th>SKS</th>
                                                        <th width="7%">Nilai</th>

                                                        <th>Nama MK</th>
                                                        <th>SKS</th>
                                                        <th width="7%">Nilai</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="2">Jumlah</th>
                                                        <th id="txt-asks" class="grey bigger-110">0</th>
                                                        <th colspan="2"></th>
                                                        <th id="txt-ksks" class="orange bigger-110">0</th>
                                                        <th></th>
                                                    </tr>
                                                </tfoot>
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
<div id="modal-view" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header no-padding">
                <div class="table-header">
                    <button id="btn-close" type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <span class="white">&times;</span>
                    </button>
                    <div id="span-title" align="center" class="bolder bigger-110"></div>
                </div>
            </div>
            <div class="modal-body no-padding table-responsive" style="height: 400px; overflow: visible; overflow-y: scroll;">
                <table id="rekap-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Pertemuan</th>
                            <th>Tanggal</th>
                            <th>Mode</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<form>
    <input value="<?= encode($detail['id_akm']) ?>" id="akm" type="hidden" >
</form>
<?php
load_js(array(
    'theme/aceadmin/assets/js/dataTables/jquery.dataTables.js',
    'theme/aceadmin/assets/js/dataTables/jquery.dataTables.bootstrap.js',
    'theme/aceadmin/assets/js/select2.js',
    
));
?>
<script type="text/javascript">
    const module = "<?= site_url($module) ?>";
    let khsTable,rekapTable,mbkmTable;
    
    $(document).ready(function () {
        $(".select2").select2({allowClear: true});
        $(".select2-chosen").addClass("center");
        khs_table();
        rekap_table();
        mbkm_table();
    });
    $(document.body).on("click", "#presensi-btn", function(event) {
        let id = $(this).attr("itemid");
        let name = $(this).attr("itemname");
        $("#span-title").html('Detail Presensi | ' + name);
        load_presensi(id);
    });
    $(document.body).on("click", "#ubah-btn", function(event) {
        let id = $(this).attr("itemid");
        let nilai = $("#nilai" + id).val();
        if(nilai === '' || nilai === null){
            jsfNotif('Peringatan', 'Input nilai terlebih dahulu', 2);
            return;
        }
        config_nilai(id, nilai);
        $(this).attr('disabled','disabled');
    });
    $(document.body).on("click", "#krs-btn", function(event) {
        sinkron_nilai($(this).attr("itemid"));
        $(this).attr('disabled','disabled');
    });
    $(document.body).on("click", "#khs-btn", function(event) {
        sinkron_nilai($(this).attr("itemid"),'khs');
        $(this).attr('disabled','disabled');
    });
    $(document.body).on("click", "#delete-btn", function(event) {
        let ths = $(this);
        let id = $(this).attr("itemid");
        let name = $(this).attr("itemprop");
        if(id === ""){
            jsfNotif('Peringatan', 'Tidak ada data yang terpilih', 2);
            return;
        }
        var title = "<h4 class='red center'><i class='ace-icon fa fa-exclamation-triangle red'></i> Peringatan !</h4>";
        var msg = "<p class='center grey bigger-120'><i class='ace-icon fa fa-hand-o-right blue'></i>" + 
                " Apakah anda yakin akan menghapus data Nilai <br/><b>" + name + "</b> ini ? </p>";
        bootbox.confirm({title: title,message: msg, buttons: {
                cancel: {label: "<i class='ace-icon fa fa-times bigger-110'></i> Batal",className: "btn btn-sm"},
                confirm: {label: "<i class='ace-icon fa fa-trash-o bigger-110'></i> Hapus",className: "btn btn-sm btn-danger"}
            },
            callback: function(result) {
                if (result === true) {
                    config_nilai(id);
                    ths.attr('disabled','disabled');
                }
            }
        });
    });
    $(document.body).on("click", "#remove-btn", function(event) {
        let ths = $(this);
        let id = $(this).attr("itemid");
        let name = $(this).attr("itemprop");
        if(id === ""){
            jsfNotif('Peringatan', 'Tidak ada data yang terpilih', 2);
            return;
        }
        var title = "<h4 class='red center'><i class='ace-icon fa fa-exclamation-triangle red'></i> Peringatan !</h4>";
        var msg = "<p class='center grey bigger-120'><i class='ace-icon fa fa-hand-o-right blue'></i>" + 
                " Apakah anda yakin akan menghapus data <br/>KRS Neofeeder <b>" + name + "</b> ? </p>";
        bootbox.confirm({title: title,message: msg, buttons: {
                cancel: {label: "<i class='ace-icon fa fa-times bigger-110'></i> Batal",className: "btn btn-sm"},
                confirm: {label: "<i class='ace-icon fa fa-trash-o bigger-110'></i> Hapus",className: "btn btn-sm btn-danger"}
            },
            callback: function(result) {
                if (result === true) {
                    sinkron_nilai(id,'delete');
                    ths.attr('disabled','disabled');
                }
            }
        });
    });
    $("#btn-akm").click(function () {
        let status = $("#status").val();
        if(status === '' || status === null){
            $('#status').focus().select();
            jsfNotif('Peringatan', 'Pilih Status dahulu', 2);
            return;
        }
        sinkron_akm();
    });
    $("#btn-aktiv").click(function () {
        let jenis = $("#jenis").val();
        if(jenis === '' || jenis === null){
            $('#jenis').focus().select();
            jsfNotif('Peringatan', 'Pilih Jenis Aktivitas dahulu', 2);
            return;
        }
        sinkron_aktivitas();
    });
    $("#btn-print-khs").click(function () {
        let id = $("#akm").val();
        window.open(module + "_do/cetak/" + id);
    });
    $("#btn-print-krs").click(function () {
        let id = $("#akm").val();
        window.open(module + "_do/cetak/" + id + "/krs");
    });
    $("#btn-print-uts").click(function () {
        let id = $("#akm").val();
        window.open(module + "_do/cetak/" + id + "/uts");
    });
    $("#btn-print-uas").click(function () {
        let id = $("#akm").val();
        window.open(module + "_do/cetak/" + id + "/uas");
    });
    $("#span-log").click(function () {
        if($(".sp-log").hasClass("hide")){
            $(".sp-log").removeClass("hide"); 
        }else{
            $(".sp-log").addClass("hide");
        }
    });
</script>
<script type="text/javascript">
    function load_khs() {
        $("#span-sks").html('');
        $("#span-mk").html('');
        $("#rekap-spin").removeClass("hide");
        $.ajax({
            url: module + "/ajax/type/table/source/khs",
            type: "POST",
            dataType: "json",
            data: {
                id: $("#akm").val()
            },
            success: function (rs) {
                khsTable.fnClearTable();
                if (rs.status) {
                    $.each(rs.data.table, function (index, value) {
                        khsTable.fnAddData(value);
                    });
                    $("#span-sks").html(rs.akm.qty_sks);
                    $("#span-mk").html(rs.akm.qty_mk);
                    $("#txt-nsks").html(rs.data.sks);
                    $("#txt-nindeks").html(rs.data.indeks);
                    $("#txt-nipk").html(rs.data.ipk);
                } else {
                    jsfNotif('Peringatan', rs.msg, 2);
                }
                khsTable.fnDraw();
                $("#rekap-spin").addClass("hide");
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $("#rekap-spin").addClass("hide");
                console.log('Error : ' + xhr.responseText);
            }
        });
    }
    function load_presensi(id) {
        var title = '<h4 class="blue center"><i class="ace-icon fa fa fa-spin fa-spinner"></i> Mohon tunggu . . . </h4>';
        var msg = '<p class="center red bigger-120"><i class="ace-icon fa fa-hand-o-right blue"></i>' +
                ' Jangan menutup atau me-refresh halaman ini, silahkan tunggu sampai peringatan ini tertutup sendiri. </p>';
        var progress = bootbox.dialog({title: title,message: msg,closeButton: false});
        $.ajax({
            url: module + "/ajax/type/table/source/presensi",
            type: "POST",
            dataType: "json",
            data: {
                id: id,
                akm: $("#akm").val()
            },
            success: function (rs) {
                progress.modal("hide");
                rekapTable.fnClearTable();
                if (rs.status) {
                    $.each(rs.data, function (index, value) {
                        rekapTable.fnAddData(value);
                    });
                    $("#modal-view").modal({show: true});
                } else {
                    jsfNotif('Peringatan', rs.msg, 2);
                }
                rekapTable.fnDraw();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                progress.modal("hide");
                console.log('Error : ' + xhr.responseText);
            }
        });
    }
    function load_mbkm() {
        $("#two-spin").show();
        $.ajax({
            url: module + "/ajax/type/table/source/mbkm",
            type: "POST",
            dataType: "json",
            data: {
                id: $("#akm").val()
            },
            success: function (rs) {
                mbkmTable.fnClearTable();
                if (rs.status) {
                    $.each(rs.data.table, function (index, value) {
                        mbkmTable.fnAddData(value);
                    });
                    $("#txt-asks").html(rs.data.asks);
                    $("#txt-ksks").html(rs.data.ksks);
                } else {
                    jsfNotif('Peringatan', rs.msg, 2);
                }
                mbkmTable.fnDraw();
                $("#two-spin").hide();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $("#two-spin").hide();
                console.log('Error : ' + xhr.responseText);
            }
        });
    }
    function config_nilai(id, nilai = null) {
        var title = '<h4 class="blue center"><i class="ace-icon fa fa fa-spin fa-spinner"></i> Mohon tunggu . . . </h4>';
        var msg = '<p class="center red bigger-120"><i class="ace-icon fa fa-hand-o-right blue"></i>' +
                ' Jangan menutup atau me-refresh halaman ini, silahkan tunggu sampai peringatan ini tertutup sendiri. </p>';
        var progress = bootbox.dialog({title: title,message: msg,closeButton: false});
        $.ajax({
            url: module + "_do/ajax/type/action/source/nilai",
            dataType: "json",
            type: "POST",
            data: {
                id: id,
                akm: $("#akm").val(),
                nilai : nilai
            },
            success: function (rs) {
                progress.modal("hide");
                if (rs.status) {
                    jsfNotif('Informasi', rs.msg, 1);
                } else {
                    jsfNotif('Peringatan', rs.msg, 2);
                }
                load_khs();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                progress.modal("hide");
                console.log('Error : ' + xhr.responseText);
            }
        });
    }
    function sinkron_nilai(id, mode = null) {
        var title = '<h4 class="blue center"><i class="ace-icon fa fa fa-spin fa-spinner"></i> Mohon tunggu . . . </h4>';
        var msg = '<p class="center red bigger-120"><i class="ace-icon fa fa-hand-o-right blue"></i>' +
                ' Jangan menutup atau me-refresh halaman ini, silahkan tunggu sampai peringatan ini tertutup sendiri. </p>';
        var progress = bootbox.dialog({title: title,message: msg,closeButton: false});
        $.ajax({
            url: module + "_do/ajax/type/action/source/sinkron",
            dataType: "json",
            type: "POST",
            data: {
                id: id,
                akm: $("#akm").val(),
                mode: mode
            },
            success: function (rs) {
                progress.modal("hide");
                if (rs.status) {
                    jsfNotif('Informasi', rs.msg, 1);
                } else {
                    jsfNotif('Peringatan', rs.msg, 2);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                progress.modal("hide");
                console.log('Error : ' + xhr.responseText);
            }
        });
    }
    function sinkron_akm() {
        var title = '<h4 class="blue center"><i class="ace-icon fa fa fa-spin fa-spinner"></i> Mohon tunggu . . . </h4>';
        var msg = '<p class="center red bigger-120"><i class="ace-icon fa fa-hand-o-right blue"></i>' +
                ' Jangan menutup atau me-refresh halaman ini, silahkan tunggu sampai peringatan ini tertutup sendiri. </p>';
        var progress = bootbox.dialog({title: title,message: msg,closeButton: false});
        $.ajax({
            url: module + "_do/ajax/type/action/source/akm",
            dataType: "json",
            type: "POST",
            data: {
                id: $("#akm").val(),
                status: $("#status").val()
            },
            success: function (rs) {
                progress.modal("hide");
                if (rs.status) {
                    jsfNotif('Informasi', rs.msg, 1);
                } else {
                    jsfNotif('Peringatan', rs.msg, 2);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                progress.modal("hide");
                console.log('Error : ' + xhr.responseText);
            }
        });
    }
    function sinkron_aktivitas() {
        var title = '<h4 class="blue center"><i class="ace-icon fa fa fa-spin fa-spinner"></i> Mohon tunggu . . . </h4>';
        var msg = '<p class="center red bigger-120"><i class="ace-icon fa fa-hand-o-right blue"></i>' +
                ' Jangan menutup atau me-refresh halaman ini, silahkan tunggu sampai peringatan ini tertutup sendiri. </p>';
        var progress = bootbox.dialog({title: title,message: msg,closeButton: false});
        $.ajax({
            url: module + "_do/ajax/type/action/source/aktivitas",
            dataType: "json",
            type: "POST",
            data: {
                id: $("#akm").val(),
                jenis: $("#jenis").val()
            },
            success: function (rs) {
                progress.modal("hide");
                if (rs.status) {
                    jsfNotif('Informasi', rs.msg, 1);
                } else {
                    jsfNotif('Peringatan', rs.msg, 2);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                progress.modal("hide");
                console.log('Error : ' + xhr.responseText);
            }
        });
    }
    function khs_table() {
        khsTable = $("#khs-table")
        .dataTable({
            iDisplayLength: 50,
            bScrollCollapse: true,
            bAutoWidth: false,
            aaSorting: [],
            aoColumnDefs: [
                {bSortable: false, aTargets: [0,9]},
                {bSearchable: false, aTargets: [0,9]},
                {sClass: "center", aTargets: [1, 2, 3, 4, 5, 6, 7, 8]},
                {sClass: "center nowrap", aTargets: [0,9]}
            ],
            oLanguage: {
                sSearch: "Cari : ",
                sInfoEmpty: "Menampilkan dari 0 sampai 0 dari total 0 data",
                sInfo: "Menampilkan dari _START_ sampai _END_ dari total _TOTAL_ data",
                sLengthMenu: "_MENU_ data per halaman",
                sZeroRecords: "Maaf tidak ada data yang ditemukan",
                sInfoFiltered: "(Menyaring dari _MAX_ total data)"
            }
        });
        khsTable.fnAdjustColumnSizing();
    }
    function rekap_table() {
        rekapTable = $("#rekap-table")
        .dataTable({
            iDisplayLength: 25,
            bScrollCollapse: true,
            bAutoWidth: false,
            aaSorting: [],
            aoColumnDefs: [
                {bSortable: false, aTargets: [0]},
                {bSearchable: false, aTargets: [0]},
                {sClass: "center", aTargets: [0,2,3,4]},
                {sClass: "center nowrap", aTargets: [1]}
            ],
            oLanguage: {
                sSearch: "Cari : ",
                sInfoEmpty: "Menampilkan dari 0 sampai 0 dari total 0 data",
                sInfo: "Menampilkan dari _START_ sampai _END_ dari total _TOTAL_ data",
                sLengthMenu: "_MENU_ data per halaman",
                sZeroRecords: "Maaf tidak ada data yang ditemukan",
                sInfoFiltered: "(Menyaring dari _MAX_ total data)"
            }
        });
        rekapTable.fnAdjustColumnSizing();
    }
    function mbkm_table() {
        mbkmTable = $("#mbkm-table")
        .dataTable({
            iDisplayLength: 50,
            bScrollCollapse: true,
            bAutoWidth: false,
            aaSorting: [],
            aoColumnDefs: [
                {bSortable: false, aTargets: [0]},
                {bSearchable: false, aTargets: [0]},
                {sClass: "center", aTargets: [1, 2, 3, 4, 5, 6]},
                {sClass: "center nowrap", aTargets: [0]}
            ],
            oLanguage: {
                sSearch: "Cari : ",
                sInfoEmpty: "Menampilkan dari 0 sampai 0 dari total 0 data",
                sInfo: "Menampilkan dari _START_ sampai _END_ dari total _TOTAL_ data",
                sLengthMenu: "_MENU_ data per halaman",
                sZeroRecords: "Maaf tidak ada data yang ditemukan",
                sInfoFiltered: "(Menyaring dari _MAX_ total data)"
            }
        });
        mbkmTable.fnAdjustColumnSizing();
    }
</script>