<?php
$this->load->view('sistem/v_breadcrumb');
?>
<style>
    th, td {
        text-align: center;
    }
    .break-word {
        white-space: normal !important;
        word-wrap: break-word;
        word-break: break-all;
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
        <div class="col-xs-12 col-sm-12">
            <div class="widget-box transparent">
                <div class="widget-header">
                    <h5 class="widget-title">
                        <i class="ace-icon fa fa-cloud-download bigger-120"></i>
                        Backup & Log Data
                    </h5>
                    <div class="widget-toolbar">
                        <a href="#" data-action="collapse" class="orange2">
                            <i class="ace-icon fa fa-chevron-up bigger-110"></i>
                        </a>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="widget-main table-responsive">
                        <div>
                            <label for="table_db">
                                Backup Database
                            </label>
                            <div class="input-group">
                                <input value="yk_site_log" id="table_db" placeholder="Tabel Database" class="form-control" type="text" id="form-field-mask-1">
                                <span class="input-group-btn">
                                    <button id="backup-btn" class="btn btn-sm btn-success" type="button">
                                        <i class="ace-icon fa fa-download bigger-110"></i>
                                        Go!
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="space space-6"></div>
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>File</th>
                                    <th>Info</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach (directory_map($log_path) as $value) {
                                    $info = get_file_info($log_path.'/'.$value);
                                    $size = round($info['size']/1000,2).' KB';
                                    $date = format_date(date("Y-m-d H:i:s", $info['date']),2);
                                ?>
                                <tr>
                                    <td><?= $no; ?></td>
                                    <td>
                                        <a href="<?= base_url($log_path.$info['name']) ?>" target="_blank" class="bolder blue"><?= $value ?></a>
                                    </td>
                                    <td><?= $size.' <small>('.$date.')</small>' ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="<?= site_url($module.'/delete/'.encode($value)) ?>" 
                                                class="tooltip-error btn btn-white btn-danger btn-mini btn-round" data-rel="tooltip" title="Hapus Data">
                                                <span class="red"><i class="ace-icon fa fa-trash bigger-120"></i></span>
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
        <div class="col-xs-12">
            <h3 class="lighter center block blue"><?= $title[1] ?></h3>
            <form id="validation-form" action="<?= site_url($action . encode($app_session['id_aplikasi'])); ?>" name="form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Judul Aplikasi :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <input value="<?= $app_session['judul'] ?>" type="text" name="judul" id="judul" class="col-xs-12  col-sm-6" placeholder="Judul Aplikasi" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Deskripsi :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <textarea cols="1" rows="3" name="deskrip" id="deskrip" class="col-xs-12  col-sm-6" placeholder="Deskripsi"><?= $app_session['deskripsi'] ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Hak Cipta :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <input value="<?= $app_session['cipta'] ?>" type="text" name="cipta" id="cipta" class="col-xs-12  col-sm-6" placeholder="Hak Cipta" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Tema Admin :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <label class="control-label">
                                <input <?= ($app_theme['theme'] == 'no-skin') ? 'checked' : ''; ?> name="theme" value="no-skin" type="radio" class="ace" />
                                <span class="lbl"> Biru</span>
                            </label>&nbsp;&nbsp;
                            <label class="control-label">
                                <input <?= ($app_theme['theme'] == 'skin-1') ? 'checked' : ''; ?> name="theme" value="skin-1" type="radio" class="ace" />
                                <span class="lbl"> Hitam</span>
                            </label>&nbsp;&nbsp;
                            <label class="control-label">
                                <input <?= ($app_theme['theme'] == 'skin-2') ? 'checked' : ''; ?> name="theme" value="skin-2" type="radio" class="ace" />
                                <span class="lbl"> Pink</span>
                            </label>&nbsp;&nbsp;
                            <label class="control-label">
                                <input <?= ($app_theme['theme'] == 'skin-3') ? 'checked' : ''; ?> name="theme" value="skin-3" type="radio" class="ace" />
                                <span class="lbl"> Putih</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Background Login :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <label class="control-label">
                                <input <?= ($app_theme['login'] == '1') ? 'checked' : ''; ?> name="login" value="1" type="radio" class="ace" />
                                <span class="lbl"> Dark</span>
                            </label>&nbsp;&nbsp;
                            <label class="control-label">
                                <input <?= ($app_theme['login'] == '2') ? 'checked' : ''; ?> name="login" value="2" type="radio" class="ace" />
                                <span class="lbl"> Blur</span>
                            </label>&nbsp;&nbsp;
                            <label class="control-label">
                                <input <?= ($app_theme['login'] == '3') ? 'checked' : ''; ?> name="login" value="3" type="radio" class="ace" />
                                <span class="lbl"> Light</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group <?= ($is_admin) ? '' : 'hide' ?>">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Pengaturan Tambahan :</label>
                    <div class="col-xs-12 col-sm-5">
                        <div class="clearfix">
                            <div class="checkbox">
                                <label>
                                    <input <?= ($app_theme['navbar'] == 1) ? 'checked ' : '' ?> value="1" name="navbar" type="checkbox" class="ace" />
                                    <span class="lbl"> Fixed Navbar</span>
                                </label>
                                <label>
                                    <input <?= ($app_theme['sidebar'] == 1) ? 'checked ' : '' ?> value="1" name="sidebar" type="checkbox" class="ace" />
                                    <span class="lbl"> Fixed Sidebar</span>
                                </label>
                                <label>
                                    <input <?= ($app_theme['bread'] == 1) ? 'checked ' : '' ?> value="1" name="bread" type="checkbox" class="ace" />
                                    <span class="lbl"> Fixed Breadcrumbs</span>
                                </label>
                                <label>
                                    <input <?= ($app_theme['container'] == 1) ? 'checked ' : '' ?> value="1" name="container" type="checkbox" class="ace" />
                                    <span class="lbl"> Inside Container</span>
                                </label>
                                <label>
                                    <input <?= ($app_theme['hover'] == 1) ? 'checked ' : '' ?> value="1" name="hover" type="checkbox" class="ace" />
                                    <span class="lbl"> Submenu Hover</span>
                                </label>
                                <label>
                                    <input <?= ($app_theme['compact'] == 1) ? 'checked ' : '' ?> value="1" name="compact" type="checkbox" class="ace" />
                                    <span class="lbl"> Compact Sidebar</span>
                                </label>
                                <label>
                                    <input <?= ($app_theme['horizontal'] == 1) ? 'checked ' : '' ?> value="1" name="horizontal" type="checkbox" class="ace" />
                                    <span class="lbl"> Horizontal Sidebar</span>
                                </label>
                                <label>
                                    <input <?= ($app_theme['altitem'] == 1) ? 'checked ' : '' ?> value="1" name="altitem" type="checkbox" class="ace" />
                                    <span class="lbl"> Alt. Active Item</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Tema Website :</label>
                    <div class="col-xs-12 col-sm-1">
                        <div class="clearfix">
                            <div class="bootstrap-colorpicker">
                                <input name="webcolor" value="<?= $app_theme['webcolor'] ?>" id="website" type="text" class="input-small color-pick" placeholder="Warna Utama" />
                            </div>
                        </div>
                    </div>
                    <span class="help-inline col-xs-12 col-sm-2">
                        <span class="middle red">* Warna Utama</span>
                    </span>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right"></label>
                    <div class="col-xs-12 col-sm-1">
                        <div class="clearfix">
                            <div class="bootstrap-colorpicker">
                                <input name="webcolor_other" value="<?= $app_theme['webcolor_other'] ?>" id="website_dua" type="text" class="input-small color-pick" placeholder="Warna Kedua" />
                            </div>
                        </div>
                    </div>
                    <span class="help-inline col-xs-12 col-sm-2">
                        <span class="middle red">* Warna Kedua</span>
                    </span>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Logo Aplikasi :</label>
                    <div class="col-xs-12 col-sm-3">
                        <div class="clearfix">
                            <input value="<?= ($app_session['logo']) ?>" type="hidden" name="exfoto" id="exfoto" />
                            <input value="" accept="image/*" type="file" name="foto" id="foto" placeholder="Foto" class="col-xs-12  col-sm-6" />
                        </div>
                    </div>
                    <span class="help-inline col-xs-12 col-sm-2">
                        <span class="middle red">* Maksimal 1 MB</span>
                    </span>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right"></label>
                    <div class="col-xs-12 col-sm-4">
                        <img class="img-thumbnail" src="<?= load_file($app_session['logo']) ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">Terakhir diubah :</label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="well">
                            <span class="bigger-110 blue"><i class="ace-icon fa fa-user"></i> &nbsp;&nbsp;<?= $app_session['log'] ?></span>
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
        <?php $this->load->view('sistem/aplikasi/v_visitor'); ?>
    </div><!-- /.row -->
</div><!-- /.page-content -->
<?php
load_js(array(
    "theme/aceadmin/assets/js/dataTables/jquery.dataTables.js",
    "theme/aceadmin/assets/js/dataTables/jquery.dataTables.bootstrap.js",    
    "theme/aceadmin/assets/js/jquery.validate.js",
    "theme/aceadmin/assets/js/bootstrap-colorpicker.js",
    "theme/aceadmin/assets/js/date-time/bootstrap-datepicker.js",
    'theme/aceadmin/highcharts.js'
));
?>
<script type="text/javascript">
    const module = "<?= site_url($module) ?>";
    $(document).ready(function() {
        $('.color-pick').colorpicker();
        $('.input-daterange').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        });
        $('#foto').ace_file_input({
            no_file: 'Plih Foto ...',
            no_icon: 'fa fa-file-image-o',
            icon_remove: 'fa fa-times',
            btn_choose: 'Pilih',
            btn_change: 'Ubah',
            droppable: false,
            onchange: null,
            allowExt: ["jpg", "png", "jpeg", "PNG", "JPG"],
            maxSize: 1100000
        }).on('file.error.ace', function(ev, info) {
            if(info.error_count['ext']) jsfNotif('Peringatan!', 'Format gambar harus berupa *.jpg, *.png', 3);
            if(info.error_count['size']) jsfNotif('Peringatan!', 'Ukuran gambar maksimal 1 MB', 3);
        });
        validate_form();
        load_index();
    });
    $("#backup-btn").click(function () {
        window.open(module + "/add/" + $("#table_db").val());
    });
</script>
<script type="text/javascript">
    function load_index() {
        const indexTable = new DataTableManager("#index-table", {
            bServerSide: true,
            ajax: {
                url: module + "/ajax/type/table/source/visitor",
                type: "POST",
                data: function (val) {
                    val.awal = $("#awal").val();
                    val.akhir = $("#akhir").val();
                }
            },
            aoColumnDefs: [
                {bSortable: false, aTargets: [0]},
                {bSearchable: false, aTargets: [0]},
                {sClass: "center", aTargets: [1, 3, 4, 5, 6]},
                {sClass: "center nowrap", aTargets: [0,2]}
            ]
        }).init();
        // reload filter
        $("#btn-search").click(function () {
            indexTable.reload();
            load_grafik();
        });
    }
    function load_grafik() {
        jsfRequest(module + "/ajax/type/table/source/grafik", "POST", 
            $("#grafik-form").serialize(), {
            useSpinner: "#one-spin",
            successCb: function(rs) {
                var visit = [];
                var akses = [];
                rs.data.map((obj) => {
                    visit.push([obj.day, obj.visit]);
                    akses.push([obj.day, obj.akses]);
                });
                chart.series[1].update({ data: visit});
                chart.series[0].update({ data: akses});
                chart.setTitle(
                    {text: "Statistik Pengunjung [ Total : " + rs.total + " Orang ]"}
                );
                chart.redraw();
                $("#txt-total").html(rs.total + ' Orang');
            },
            errorCb: function(err) {
                console.error("grafik:", err);
            }
        });
    }
    function validate_form() {
        jsfValidate("#validation-form", {
            judul: {
                required: true,
                minlength: 5
            },
            cipta: {
                required: true,
                minlength: 5
            },
            deskrip: {
                required: true,
                minlength: 5
            },
            website: {
                required: true
            },
            website_dua: {
                required: true
            },
            tema: {
                required: true
            },
            back: {
                required: true
            }
        });
    }
    const options = {
        chart: {
            type: 'areaspline',
            zoomType: 'x',
            events: {
                //load: load_grafik()
            }
        },
        subtitle: {
            text: 'Website <?= $app_session['judul'] ?>'
        },
        xAxis: {
            type: 'category',
            tickmarkPlacement: 'on',
            title: {
                enabled: true
            }
        },
        yAxis: {
            title: {
                text: 'Jumlah'
            }
        },
        tooltip: {
            crosshairs: true,
            shared: true
        },
        legend: {
            align: 'center',
            verticalAlign: 'top',
            borderWidth: 0
        },
        plotOptions: {
            areaspline: {
                fillOpacity: 0.5,
                marker: {
                    radius: 4,
                    lineWidth: 1
                },
                dataLabels: {
                    enabled: true
                }
            }
        },
        series: [{
                name: 'Akses ',
                data: []
            },{
                name: 'Pengunjung (Orang) ',
                marker: {
                    symbol: 'square'
                },
                data: []
            }]
    };
    const chart = Highcharts.chart('container', options); 
</script>                    
