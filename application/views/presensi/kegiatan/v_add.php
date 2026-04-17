<?php $this->load->view('sistem/v_breadcrumb'); ?>
<div class="page-content">
    <div class="page-header hide">
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
            <h3 class="lighter center block blue">
                Presensi Pengajian & Kegiatan <br>[ <small class="is-clock"><?= format_date(date('Y-m-d H:i:s'),0) ?></small> ]
            </h3>
            <div class="space-10"></div>
            <form id="validation-form" action="#" name="form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right"></label>
                    <div class="col-xs-12 col-sm-5">
                        <div class="clearfix">
                            <select class="select2 width-100 bolder" name="agenda" id="agenda" data-placeholder="-----> Pilih Agenda Kegiatan <-----">
                                <option value=""> </option>
                                <?php
                                foreach ($agenda['data'] as $val) {
                                    $text = $val['jenis_agenda'].' ('.format_date($val['waktu_agenda'],0).')';
                                    echo '<option data-title="'.ctk(limit_text($val['judul_agenda'], 200), true).'" value="'.encode($val['id_agenda']).'">'.$text.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <span class="help-inline col-xs-12 col-sm-7 col-sm-offset-3 center">
                        <span class="middle blue bolder" id="txt-agenda"></span>
                    </span>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right">
                        <button onclick="restartGPS()" id="btn-gps" 
                            class="btn btn-bold btn-danger btn-white btn-sm" type="button">
                            <i class="ace-icon fa fa-map-marker bigger-120"></i>
                            GPS Lokasi
                        </button>
                    </label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="space-2"></div>
                        <div class="clearfix">
                            <input value="" type="text" name="lokasi" id="lokasi" class="col-xs-12 col-sm-6 bolder bigger-110" placeholder="??? (GPS Lokasi Presensi)" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-5 no-padding-right"></label>
                    <div class="col-xs-12 col-sm-6">
                        <span class="input-icon">
                            <input value="" type="text" name="latitude" id="latitude" placeholder="Latitude" class="input-medium input-sm smaller-90"/>
                        </span>
                        <span class="input-icon input-icon-right">
                            <input value="" type="text" name="longitude" id="longitude" placeholder="Longitude" class="input-medium input-sm smaller-90"/>
                        </span>
                        <div class="space-2"></div>
                    </div>
                    <span class="help-inline col-sm-7 col-sm-offset-5 col-xs-12">
                        <span id="alamat" class="middle blue"></span>
                    </span>
                </div>
                <div class="clearfix form-actions">
                    <div class="col-sm-offset-5 col-sm-5">
                        <button class="btn btn-success" name="simpan" type="submit">
                            <i class="ace-icon fa fa-paper-plane"></i>
                            Simpan Presensi
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.page-content -->
<?php
load_js(array(
    'theme/aceadmin/assets/js/select2.js',
    'theme/aceadmin/assets/js/jquery.validate.js'
));
?>
<script type="text/javascript">
    const module = "<?= site_url($module) ?>";
    
    let watchID = null;
    let lokasiFix = false;
    let gpsTimeout = null;
    
    $(document).ready(function () {
        $(".select2").select2({allowClear: true});
        $(".select2-chosen").addClass("center");
        validateForm();
    });
    $(document).on("focusin", "#latitude,#longitude,#lokasi", function() {
        $(this).prop('readonly', true);
    });
    $(document).on("focusout", "#latitude,#longitude,#lokasi", function() {
        $(this).prop('readonly', false); 
    });
    $("#agenda").on('change', function () {
        const selected = $(this).find(':selected');
        const text = selected.text();
        const title = selected.data('title');
        
        $("#txt-agenda").html("");
        if(title){
            restartGPS();
            $("#txt-agenda").html(text + '<hr class="space-2"><small>' + title + '</small>');
        }
        $("#latitude, #longitude, #lokasi").val("");
        $("#btn-gps").removeAttr("disabled");
    });
</script>
<script type="text/javascript">
    // ================== GPS ==================
    function startGPS(){
        lokasiFix = false;
        
        const agenda = $("#agenda").val();
        if(!agenda){
            jsfNotif("Peringatan", "Pilih Agenda Kegiatan dahulu", 2, 'swal');
            return;
        }
        $("#latitude, #longitude, #lokasi").val("");
        $("#btn-gps").attr("disabled", "disabled");
        $("#alamat").html(`<i class="fa fa-spinner fa-spin fa-fw fa-2x"></i> Mengaktifkan GPS ...`);
        
        if (!navigator.geolocation){
            jsfNotif("Peringatan", "Browser tidak mendukung. Aktifkan pengaturan GPS", 3, 'swal');
            return;
        }
        watchID = navigator.geolocation.watchPosition(
            successGPS,
            errorGPS,
            {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 0
            }
        );
        // timeout 20 detik
        gpsTimeout = setTimeout(function(){
            if(!lokasiFix){
                navigator.geolocation.clearWatch(watchID);
                
                $("#btn-gps").removeAttr("disabled");
                $("#alamat").html(`GPS tidak ditemukan. Silahkan coba ulang dengan klik tombol <b class="red">GPS Lokasi</b>`);
                jsfNotif("Peringatan", "GPS tidak ditemukan. Silahkan coba ulang.", 2, 'swal');
            }
        },10000);
    }
    function successGPS(pos){
        //SAMPLE
        let lat = -1.1166459318;
        let lng = 131.2857774324;
        let accuracy = 10;
        
//        let lat = pos.coords.latitude;
//        let lng = pos.coords.longitude;
//        let accuracy = pos.coords.accuracy;
        
        $("#alamat").html(`<i class="fa fa-spinner fa-spin fa-fw fa-2x"></i> 
            Mencari lokasi akurat, tunggu sebentar . . . (`+Math.round(accuracy)+` meter)`);
        if(accuracy > 30){
            return;
        }
        if(lokasiFix) return;
        lokasiFix = true;

        clearTimeout(gpsTimeout);
        navigator.geolocation.clearWatch(watchID);

        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(res => res.json())
            .then(data => {
                if (data.display_name) {
                    $("#alamat").html(data.display_name);
                } else {
                    $("#alamat").html(lat + ", " + lng);
                }
            })
            .catch(() => {
                $("#alamat").html(lat + ", " + lng);
            });
       
        $("#latitude").val(lat);
        $("#longitude").val(lng);
        // AJAX LOCATION
        setTimeout(function () {
            checkLocation(lat, lng);
        }, 1000);
    }
    function errorGPS(err) {
        $("#btn-gps").removeAttr("disabled");
        
        $("#alamat").html("GPS Error : " + err.message);
        jsfNotif("Peringatan", "GPS Error : " + err.message, 3, 'swal');
    }
    function restartGPS() {
        if (watchID) {
            navigator.geolocation.clearWatch(watchID);
        }
        clearTimeout(gpsTimeout);
        startGPS();
    }
</script>
<script type="text/javascript">
    function checkLocation(lat, lng) {
        jsfRequest(module + "_do/ajax/type/action/source/location", "POST",
            { 
                latitude: lat, longitude: lng, id: $("#agenda").val()
            }, {useLoading: true})
        .done(function(rs) {
            if (rs.status) {
                $("#lokasi").val(rs.data);
                
                $("#btn-gps").attr("disabled", "disabled");
                jsfNotif("Informasi", rs.msg, 1);
            } else {
                $("#lokasi").val("");
                $("#btn-gps").removeAttr("disabled");
                jsfNotif('Peringatan', rs.msg, 2, 'swal');
            }
        })
        .fail(function(err) {
            console.error("load:", err);
        });
    }
    function savePresensi(formEl){
        const dataForm = $(formEl).serialize();
        
        jsfRequest(module + "_do/ajax/type/action/source/presensi", "POST",
            dataForm,
            { useLoading: true })
        .done(function(rs) {
            if (rs.status) {
                $(formEl).hide();
                jsfNotif("Informasi", rs.msg, 1, 'swal');

                setTimeout(function () {
                    window.location.replace(module);
                }, 2000);
            } else {
                jsfNotif('Peringatan', rs.msg, 2, 'swal');
            }
            bootbox.hideAll();
        })
        .fail(function(err) {
            bootbox.hideAll();
            console.error("load:", err);
        });
    }
    function validateForm(){
        jsfValidate("#validation-form", {
            rules: {
                agenda: { required: true },
                lokasi: { required: true },
                latitude: {
                    required: true
                },
                longitude: {
                    required: true
                }
            },
            loadingDialog: false,
            onValid: function(formEl) {
                const title = `<h4 class="red center"><i class="ace-icon fa fa-exclamation-triangle red"></i> Peringatan !</h4>`;
                const msg = `<p class="center grey bigger-120"><i class="ace-icon fa fa-hand-o-right blue"></i>
                     Apakah anda yakin akan menyimpan data ? </p>`;
                bootbox.confirm({ title: title, message: msg, 
                    buttons: {
                        cancel: { label: "<i class='ace-icon fa fa-times bigger-110'></i> Batal", className: "btn btn-sm" },
                        confirm: { label: "<i class='ace-icon fa fa-paper-plane bigger-110'></i> Simpan", className: "btn btn-sm btn-success" }
                    },
                    callback: function(result) {
                        document.activeElement.blur();
                        if (result === true) {
                            savePresensi(formEl);
                        }
                    }
                });
                return false;
            }
        });
    }
</script>
