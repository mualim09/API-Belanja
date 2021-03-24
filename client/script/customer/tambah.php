<script type="text/javascript">
    $(function () {

        $('#txt_nik').inputmask('9999-9999-9999-9999');
        /*$("#txt_tanggal_lahir").datepicker({
            dateFormat: 'DD, dd MM yy',
            autoclose: true
        }).datepicker("setDate", new Date());*/
        /*$("#txt_tanggal_lahir").datepicker({
            autoclose: true
        });*/

        loadWilayah('txt_domisili_provinsi', 'provinsi', '', 'Provinsi');
        resetSelectBox('txt_domisili_provinsi', "Provinsi");
        resetSelectBox('txt_domisili_kabupaten', "Kabupaten");
        resetSelectBox('txt_domisili_kecamatan', "Kecamatan");
        resetSelectBox('txt_domisili_kelurahan', "Kelurahan");

        $("#txt_domisili_provinsi").select2();
        $("#txt_domisili_kabupaten").select2();
        $("#txt_domisili_kecamatan").select2();
        $("#txt_domisili_kelurahan").select2();
        $("#txt_jenis_customer").select2();
        $("#txt_mentor").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function(){
                    return "Mentor tidak ditemukan";
                }
            },
            placeholder:"Cari Mentor",
            ajax: {
                dataType: "json",
                headers:{
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/Pegawai/get_all_mentor_select2",
                type: "GET",
                data: function (term) {
                    return {
                        search:term.term
                    };
                },
                cache: true,
                processResults: function (response) {
                    var data = response.response_package.response_data;

                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.nama,
                                id: item.uid
                            }
                        })
                    };
                }
            }
        });

        $("#txt_domisili_provinsi").on('change', function(){
            var id = $(this).val();

            loadWilayah('txt_domisili_kabupaten', 'kabupaten', id, 'Kabupaten / Kota');
            resetSelectBox('txt_domisili_kecamatan', "Kecamatan");
            resetSelectBox('txt_domisili_kelurahan', "Kelurahan");
        });

        $("#txt_domisili_kabupaten").on('change', function(){
            var id = $(this).val();

            loadWilayah('txt_domisili_kecamatan', 'kecamatan', id, 'Kecamatan');
            resetSelectBox('txt_domisili_kelurahan', "Kelurahan");
        });

        $("#txt_domisili_kecamatan").on('change', function(){
            var id = $(this).val();

            loadWilayah('txt_domisili_kelurahan', 'kelurahan', id, "Kelurahan");
        });










        loadWilayah('txt_ktp_provinsi', 'provinsi', '', 'Provinsi');
        resetSelectBox('txt_ktp_provinsi', "Provinsi");
        resetSelectBox('txt_ktp_kabupaten', "Kabupaten");
        resetSelectBox('txt_ktp_kecamatan', "Kecamatan");
        resetSelectBox('txt_ktp_kelurahan', "Kelurahan");

        $("#txt_ktp_provinsi").select2();
        $("#txt_ktp_kabupaten").select2();
        $("#txt_ktp_kecamatan").select2();
        $("#txt_ktp_kelurahan").select2();

        $("#txt_ktp_provinsi").on('change', function(){
            var id = $(this).val();

            loadWilayah('txt_ktp_kabupaten', 'kabupaten', id, 'Kabupaten / Kota');
            resetSelectBox('txt_ktp_kecamatan', "Kecamatan");
            resetSelectBox('txt_ktp_kelurahan', "Kelurahan");
        });

        $("#txt_ktp_kabupaten").on('change', function(){
            var id = $(this).val();

            loadWilayah('txt_ktp_kecamatan', 'kecamatan', id, 'Kecamatan');
            resetSelectBox('txt_ktp_kelurahan', "Kelurahan");
        });

        $("#txt_ktp_kecamatan").on('change', function(){
            var id = $(this).val();

            loadWilayah('txt_ktp_kelurahan', 'kelurahan', id, "Kelurahan");
        });

        var targetCropper = $("#image-uploader");
        var basic = targetCropper.croppie({
            enforceBoundary:false,
            viewport: {
                width: 220,
                height: 220
            },
        });

        basic.croppie("bind", {
            zoom: .5,
            url: __HOST__ + "/client/template/assets/images/avatar/demi.png"
        });

        $("#upload-image").change(function(){
            readURL(this, basic);
        });

        $("#btn_save_data").click(function () {
            simpanData();
        });

        function readURL(input, cropper) {
            var url = input.value;
            var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
            if (input.files && input.files[0]&& (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
                var reader = new FileReader();

                reader.onload = function (e) {

                    cropper.croppie('bind', {
                        url: e.target.result
                    });
                    //$('#imageLoader').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
            else{
                //$('#img').attr('src', '/assets/no_preview.png');
            }
        }


        function loadWilayah(selector, parent, id, name){

            resetSelectBox(selector, name);

            $.ajax({
                url:__HOSTAPI__ + "/Wilayah/"+ parent +"/" + id,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var MetaData = response.response_package.response_data;

                    if (MetaData != ""){
                        for(i = 0; i < MetaData.length; i++){
                            var selection = document.createElement("OPTION");

                            $(selection).attr("value", MetaData[i].id).html(MetaData[i].nama);

                            // autoSelect(selector, MetaData[i].id , params);

                            $("#" + selector).append(selection);
                        }
                    }

                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function resetSelectBox(selector, name){
            $("#"+ selector +" option").remove();
            var opti_null = "<option value='' selected disabled>Pilih "+ name +" </option>";
            $("#" + selector).append(opti_null);
        }

        function simpanData() {
            var nik = $("#txt_nik").val();
            var nama = $("#txt_nama").val();
            var jenis = $("txt_jenis_customer").val();
            var email = $("#txt_email").val();
            var tempat_lahir = $("#txt_tempat_lahir").val();
            var tanggal_lahir = $("#txt_tanggal_lahir").val();
            var telp = $("#txt_telp").val();
            var wa = $("#txt_wa").val();
            var dom_prov = $("#txt_domisili_provinsi").val();
            var dom_kab = $("#txt_domisili_kabupaten").val();
            var dom_kec = $("#txt_domisili_kecamatan").val();
            var dom_kel = $("#txt_domisili_kelurahan").val();
            var dom_alamat = $("#txt_alamat_domisili").val();
            var dom_kodepos = $("#txt_domisili_kodepos");
            var rt = $("#txt_rt").val();
            var rw = $("#txt_rw").val();

            var mentor = $("#txt_mentor").val();

            var ktp_prov = $("#txt_ktp_provinsi").val();
            var ktp_kab = $("#txt_ktp_kabupaten").val();
            var ktp_kec = $("#txt_ktp_kecamatan").val();
            var ktp_kel = $("#txt_ktp_kelurahan").val();
            var ktp_alamat = $("#txt_ktp_alamat").val();
            var ktp_kodepos = $("#txt_ktp_kodepos");

            var ahli_waris_nama = $("#txt_ahli_waris_nama").val();
            var ahli_waris_hubungan = $("#txt_ahli_waris_hubungan").val();
            var ahli_waris_telp = $("#txt_ahli_waris_telp").val();
            var ahli_waris_wa = $("#txt_ahli_waris_wa").val();

            var bank = $("#txt_bank");
            var bank_an = $("#txt_bank_atas_nama").val();
        }
    });
</script>