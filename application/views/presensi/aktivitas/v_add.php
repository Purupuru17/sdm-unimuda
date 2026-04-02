<?php $this->load->view('sistem/v_breadcrumb'); ?>
<style>
    video { border-radius:10px; width:100%; max-width:350px; }
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
            <h3 class="lighter center block blue"><?= $title[0] ?></h3>
            <form id="validation-form" action="#" name="form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                
                
                <video id="video" autoplay playsinline></video>
                <canvas id="canvas" style="display:none;"></canvas>
                
                <img id="preview_foto" style="margin-top:10px; max-width:300px; display:none; border-radius:10px;">

                <textarea id="alamat" readonly placeholder="Mengambil alamat..."></textarea>

                <input type="text" id="latitude">
                <input type="text" id="longitude">
                <input type="text" id="foto_base64">

                <br>
                <button onclick="startFoto()" type="button">Ambil Foto</button>
                <button onclick="restartFoto()" type="button">Ulang Foto</button>
                <button id="btnMaps" onclick="bukaMaps()" type="button">Google Maps</button>
                <button onclick="kirim()" type="button">Kirim Presensi</button>
                <button id="btnRetryGPS" onclick="restartGPS()" style="display:none;" type="button">Ambil Ulang GPS</button>
                
                
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
        //load_select();
        //validate_form();
        startGPS();
    });
    $(document).on("focusin", "#sks,#ipk", function() {
        $(this).prop('readonly', true);
    });
    $(document).on("focusout", "#sks,#ipk", function() {
        $(this).prop('readonly', false); 
    });
    $("#mhs").change(function () {
        let data = $("#mhs").select2('data');
        $("#txt-mhs").html(data.text);
        if(data.status){
            $("#txt-status").addClass("hide");
            $(".form-actions").removeClass("hide"); 
        }else{
            $("#txt-status").removeClass("hide");
            $(".form-actions").addClass("hide"); 
        }
        $("#sks,#ipk").val('');
        $(".is-akm").html('');
    });
</script>
<script type="text/javascript">
    // ================== GPS ==================

    function startGPS(){

        lokasiFix = false;

        document.getElementById("btnRetryGPS").style.display = "none";
        document.getElementById("alamat").value = "Mengaktifkan GPS...";

        if (!navigator.geolocation){
            alert("Browser tidak mendukung GPS");
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

                document.getElementById("alamat").value =
                    "GPS belum akurat. Silakan coba ulang.";

                document.getElementById("btnRetryGPS").style.display = "inline-block";

            }

        },10000);

    }

    function successGPS(pos){

        let lat = pos.coords.latitude;
        let lng = pos.coords.longitude;
        let accuracy = pos.coords.accuracy;

        document.getElementById("alamat").value =
            "Mencari lokasi akurat... ("+Math.round(accuracy)+" meter)";

        if(accuracy > 30){
            return;
        }

        if(lokasiFix) return;
        lokasiFix = true;

        clearTimeout(gpsTimeout);
        navigator.geolocation.clearWatch(watchID);

        document.getElementById("latitude").value = lat;
        document.getElementById("longitude").value = lng;

        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                    .then(res => res.json())
                    .then(data => {

                        if (data.display_name) {
                            document.getElementById("alamat").value = data.display_name;
                        } else {
                            document.getElementById("alamat").value = lat + ", " + lng;
                        }

                    })
                    .catch(() => {
                        document.getElementById("alamat").value = lat + ", " + lng;
                    });

    }

    function errorGPS(err) {

        document.getElementById("alamat").value =
                "GPS error: " + err.message;

        document.getElementById("btnRetryGPS").style.display = "inline-block";

    }

    function restartGPS() {

        if (watchID) {
            navigator.geolocation.clearWatch(watchID);
        }

        clearTimeout(gpsTimeout);

        startGPS();

    }

    // start saat halaman load
    

    // ================== Ambil Foto ==================
    function startFoto() {

        const username = "<?= $this->session->userdata('name') ?>";
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');

        const maxWidth = 600;
        const scale = maxWidth / video.videoWidth;
        
        const latitude = $("#latitude").val();
        const longitude = $("#longitude").val();
        
        if(!video){
            jsfNotif('Peringatan', 'Foto tidak ditemukan', 2, 'swal');
            return;
        }
        if(!latitude || !longitude){
//            jsfNotif('Peringatan', 'Lokasi tidak ditemukan', 2, 'swal');
//            return;
        }
        
        canvas.width = maxWidth;
        canvas.height = video.videoHeight * scale;

        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        // ===== Watermark =====
        ctx.fillStyle = "white";
        ctx.font = "16px Arial";
        ctx.fillText(username, 10, canvas.height - 60);
        ctx.fillText(new Date().toLocaleString(), 10, canvas.height - 40);
        ctx.fillText("GPS : " + latitude + ", " + longitude, 10, canvas.height - 20);

        // ===== Compress =====
        let quality = 0.7;
        let dataURL = canvas.toDataURL('image/jpeg', quality);

        $("#foto_base64").val(dataURL);

        // ===== preview =====
        let preview = document.getElementById("preview_foto");
        preview.src = dataURL;
        preview.style.display = "block";
        console.log("Ukuran:", Math.round(dataURL.length / 1024), "KB");

        // Stop kamera
        streamGlobal.getTracks().forEach(track => track.stop());
//        video.srcObject = null;
    }

    function restartFoto() {
        $("#preview_foto").hide();
        $("#foto_base64").val("");
        //Buka Kamera
        navigator.mediaDevices.getUserMedia({ 
            video: { facingMode: "user" }
        })
        .then(stream => {
            streamGlobal = stream; video.srcObject = stream;
        })
        .catch(err => {
            jsfNotif('Peringatan', 'Kamera tidak dizinkan. Buka pengaturan akses pada Browser', 3, 'swal');
        });
    }
</script>
<script type="text/javascript">
//    function load_akm() {
//        let id = $("#mhs").val();
//        let selected = $("#mhs").select2('data');
//        
//        if(id === '' || id === null){
//            $("#mhs").select2('open');
//            jsfNotif('Peringatan', 'Masukkan NIM/Nama Mahasiswa dahulu', 2);
//            return;
//        }
//        if(!selected.status){
//            jsfNotif('Peringatan', 'Status Mahasiswa pada PDDikti : TIDAK AKTIF !', 2);
//            return;
//        }
//        jsfRequest(module + "/ajax/type/list/source/akm", "POST",
//            { id: id }, {useLoading: true})
//        .done(function(rs) {
//            if (rs.status) {
//                $("#sks").val(rs.data.sks);
//                $("#ipk").val(rs.data.ipk);
//                $(".is-akm").html(rs.data.akm);
//            } else {
//                $("#sks, #ipk").val('');
//                $(".is-akm").html('');
//                jsfNotif('Peringatan', rs.msg, 2);
//            }
//        })
//        .fail(function(err) {
//            console.error("load:", err);
//        });
//    }
//    function load_select() {
//        $("#mhs").select2({
//            placeholder: "Masukkan NIM/Nama Mahasiswa",
//            minimumInputLength: 3,
//            ajax: {
//                url: module + "/ajax/type/list/source/nim",
//                type: "POST",
//                dataType: 'json',
//                delay: 250,
//                data: function (key) {
//                    return { key: key };
//                },
//                results: function (data) {
//                    return { results: data };
//                },
//                cache: true
//            },
//            initSelection: function(element, callback) {
//                var id = $(element).val();
//                if (id !== "") {
//                    $.ajax(module + "/ajax/type/list/source/nim?id=" + id, {
//                        dataType: "json"
//                    }).done(function(data) { 
//                        callback(data[0]);
//                        $("#txt-mhs").html(data[0].text);
//                    });
//                }
//            }
//        });
//    }
//    function validate_form(){
//        jsfValidate("#validation-form", {
//            mhs: { required: true },
//            periode: { required: true },
//            sks: {
//                required: true, digits: true,
//                min:0, max:200
//            },
//            ipk: {
//                required: true, number: true,
//                min:0, max:4
//            },
//            status: {
//                required: {
//                    depends: function(e) {
//                        return $.trim($("#idmhs").val()) !== "";
//                    }
//                }
//            },
//            rpl: {
//                required: {
//                    depends: function(e) {
//                        return $.trim($("#status").val()) === "RPL";
//                    }
//                }
//            },
//            'akm_check[]': { 
//                //required: true
//            },
//            'akm_select[]': { 
//                //required: true
//            },
//            note: {
//                minlength: 5
//            }
//        });
//    }
</script>