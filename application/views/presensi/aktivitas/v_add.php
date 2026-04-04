<?php $this->load->view('sistem/v_breadcrumb'); ?>
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
            <h3 class="lighter center block blue">
                Presensi Aktivitas Kerja <br>[ <span class="is-clock"><?= format_date(date('Y-m-d H:i:s'),0) ?></span> ]
            </h3>
            <div class="space-10"></div>
            <form id="validation-form" action="#" name="form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">
                        <button onclick="restartGPS()" id="btn-gps" disabled="disabled"
                            class="btn btn-bold btn-primary btn-white btn-sm" type="button">
                            <i class="ace-icon fa fa-map-marker bigger-120"></i>
                            GPS Lokasi
                        </button>
                    </label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="space-2"></div>
                        <span class="input-icon">
                            <input value="" type="text" name="latitude" id="latitude" placeholder="Latitude" class="input-medium bolder"/>
                        </span>
                        <span class="input-icon input-icon-right">
                            <input value="" type="text" name="longitude" id="longitude" placeholder="Longitude" class="input-medium bolder"/>
                        </span>
                    </div>
                    <span class="help-inline col-sm-8 col-sm-offset-4 col-xs-12">
                        <span id="alamat" class="middle red">Mengambil alamat ...</span>
                    </span>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right"></label>
                    <div class="col-xs-12 col-sm-6">
                        <div class="clearfix">
                            <input value="" type="text" name="lokasi" id="lokasi" class="col-xs-12 col-sm-6 bolder bigger-120" placeholder="Lokasi Presensi berdasarkan GPS" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right">
                        <button onclick="restartFoto()" id="btn-kamera"
                            class="btn btn-bold btn-warning btn-white btn-sm" type="button">
                            <i class="ace-icon fa fa-camera bigger-120"></i>
                            Buka Kamera
                        </button>
                    </label>
                    <div class="col-xs-12 col-sm-4">
                        <div class="clearfix">
                            <video id="video" autoplay playsinline class="img-thumbnail" style="display: none; max-width: 350px"></video>
                            <canvas id="canvas" style="display:none;"></canvas>
                            
                            <img id="preview" width="350" class="img-thumbnail" style="display: none">
                            <input type="hidden" name="foto" id="foto">
                        </div>
                        <div class="space-2"></div>
                        <button onclick="takeFoto()" style="display: none" id="btn-foto"
                            class="btn btn-bold btn-success btn-white btn-sm" type="button">
                            <i class="ace-icon fa fa-camera bigger-120"></i>
                            Ambil Foto
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-4 no-padding-right bolder">Status <span class="red">*</span> :</label>
                    <div class="col-xs-12 col-sm-7">
                        <div class="clearfix">
                            <label class="control-label">
                                <input name="status" value="1" type="radio" class="ace input-lg" />
                                <span class="lbl bolder blue bigger-120"> MASUK </span>
                            </label>&nbsp;&nbsp;&nbsp;
                            <label class="control-label">
                                <input name="status" value="2" type="radio" class="ace input-lg" />
                                <span class="lbl bolder orange bigger-120"> PULANG </span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                    <div class="col-sm-offset-4 col-sm-5">
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
    
    let video = document.getElementById('video');
    let streamGlobal;
    
    let watchID = null;
    let lokasiFix = false;
    let gpsTimeout = null;
    
    $(document).ready(function () {
        $(".select2").select2({allowClear: true});
        $(".select2-chosen").addClass("center");
        validateForm();
        startGPS();
    });
    $(document).on("focusin", "#latitude,#longitude,#lokasi", function() {
        $(this).prop('readonly', true);
    });
    $(document).on("focusout", "#latitude,#longitude,#lokasi", function() {
        $(this).prop('readonly', false); 
    });
</script>
<script type="text/javascript">
    // ================== GPS ==================
    function startGPS(){
        lokasiFix = false;
        
        $("#latitude, #longitude, #lokasi").val("");
        $("#btn-gps").attr("disabled", "disabled");
        $("#alamat").html("Mengaktifkan GPS ...");
        
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
                $("#alamat").html("GPS belum akurat. Silakan coba ulang.");
                jsfNotif("Peringatan", "GPS belum akurat. Silakan coba ulang.", 2, 'swal');
            }
        },10000);
    }
    function successGPS(pos){
        //SAMPLE
//        let lat = -1.1160209;
//        let lng = 131.2859550;
//        let accuracy = 10;
        
        let lat = pos.coords.latitude;
        let lng = pos.coords.longitude;
        let accuracy = pos.coords.accuracy;
        
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
    // ================== Ambil Foto ==================
    async function takeFoto() {
        
        const username = "<?= $this->session->userdata('name') ?>";
        const lokasi = $("#lokasi").val();
        
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        const maxWidth = 600;
        const scale = maxWidth / video.videoWidth;
        
        if(!video.videoWidth){
            jsfNotif('Peringatan', 'Foto tidak ditemukan', 2, 'swal');
            return;
        }
        if(!lokasi){
            jsfNotif('Peringatan', 'Lokasi tidak ditemukan', 2, 'swal');
            return;
        }
        canvas.width = maxWidth;
        canvas.height = video.videoHeight * scale;
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        // ===== Watermark =====
        ctx.fillStyle = "white";
        ctx.font = "16px Arial";
        ctx.fillText(username, 10, canvas.height - 60);
        ctx.fillText(new Date().toLocaleString(), 10, canvas.height - 40);
        ctx.fillText("Lokasi : " + lokasi, 10, canvas.height - 20);
        // ===== Compress =====
        let quality = 0.7;
        let dataURL = canvas.toDataURL('image/jpeg', quality);
        // ===== preview =====
        let preview = document.getElementById("preview");
        preview.src = dataURL;
        preview.style.display = "block";
        
        // Stop kamera
        streamGlobal.getTracks().forEach(track => track.stop());
        video.srcObject = null;
        // Set Foto
        $("#foto").val(dataURL);
        $("#video, #btn-foto").hide();
        $("#btn-kamera").show();
        jsfNotif('Informasi', 'Foto berhasil', 1);
    }
    function restartFoto() {
        $("#preview").hide();
        $("#video").show();
        $("#foto").val("");
        //Buka Kamera
        navigator.mediaDevices.getUserMedia({ 
            video: { facingMode: "user" }
        })
        .then(stream => {
            streamGlobal = stream; 
            video.srcObject = stream;
            $("#btn-foto").show();
            $("#btn-kamera").hide();
        })
        .catch(err => {
            jsfNotif('Peringatan', 'Kamera tidak dizinkan. Buka pengaturan akses pada Browser', 3, 'swal');
        });
    }   
</script>
<script type="text/javascript">
    function checkLocation(lat, lng) {
        jsfRequest(module + "/ajax/type/action/source/location", "POST",
            { 
                latitude: lat, longitude: lng
            }, {useLoading: true})
        .done(function(rs) {
            if (rs.status) {
                $("#lokasi").val(rs.data);
                
                $("#btn-gps").attr("disabled", "disabled");
                jsfNotif("Informasi", rs.msg, 1);
            } else {
                $("#btn-gps").removeAttr("disabled");
                jsfNotif('Peringatan', rs.msg, 2, 'swal');
            }
        })
        .fail(function(err) {
            console.error("load:", err);
        });
    }
    function validateForm(){
        jsfValidate("#validation-form", {
            rules: {
                status: { required: true },
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
                const dataURL = $("#foto").val();
                const error = validateImage(dataURL);
                if (error) {
                    jsfNotif('Peringatan', error, 2, 'swal');
                    return false;
                }
                const dataForm = $(formEl).serialize();
                jsfRequest(module + "/ajax/type/action/source/presensi", "POST",
                    dataForm,
                    { useLoading: true })
                .done(function(rs) {
                    if (rs.status) {
                        $(formEl).hide();
                        jsfNotif("Informasi", rs.msg, 1, 'swal');
                        
                        setTimeout(function () {
                            window.location.replace(module);
                        }, 3000);
                    } else {
                        jsfNotif('Peringatan', rs.msg, 2, 'swal');
                    }
                    bootbox.hideAll();
                })
                .fail(function(err) {
                    bootbox.hideAll();
                    console.error("load:", err);
                });
                return false;
            }
        });
    }
    function validateImage(dataURL) {
        if (!dataURL) return "Foto masih kosong";
        // cek format
        if (!dataURL.startsWith("data:image/")) {
            return "Format harus gambar";
        }
        // cek mime type (opsional)
        const allowed = ['image/jpeg', 'image/png', 'image/webp'];
        let mime = dataURL.split(';')[0].replace('data:', '');

        if (!allowed.includes(mime)) {
            return "Format Foto tidak diizinkan";
        }
        // cek ukuran (approx base64 → byte)
        let sizeInBytes = Math.round((dataURL.length * 3) / 4);
        let maxSize = 2 * 1024 * 1024; // 2MB

        if (sizeInBytes > maxSize) {
            return "Ukuran Foto terlalu besar (max 2MB)";
        }
        return null;
    }
</script>
