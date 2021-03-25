<script type="text/javascript">
    $(function () {

        //get detail customer
        var UID = __PAGES__[2];
        $.ajax({
            async: false,
            url: __HOSTAPI__ + "/Membership/detail/" + UID,
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            type: "GET",
            success: function(response) {
                if(
                    response.response_package !== undefined &&
                    response.response_package.response_data !== undefined &&
                    response.response_package.response_data.length > 0) {
                    var data = response.response_package.response_data[0];


                    $("#txt_nik").val(data.nik).inputmask('9999-9999-9999-9999').on("keyup", function () {
                        var nik_check = $(this).inputmask("unmaskedvalue");

                        if(nik_check.length < 16) {
                            $("#checker_nik").html("<i class=\"fa fa-times-circle\"></i> Panjang Harus 16 karakter").removeClass("text-success").addClass("text-danger");
                        } else {
                            $.ajax({
                                async: false,
                                url: __HOSTAPI__ + "/Membership",
                                beforeSend: function(request) {
                                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                                },
                                type: "POST",
                                data: {
                                    request: "check_nik",
                                    nik: nik_check
                                },
                                success: function(response) {
                                    if(response.response_package.response_result > 0) {
                                        $("#checker_nik").html("<i class=\"fa fa-times-circle\"></i> NIK sudah terdaftar").removeClass("text-success").addClass("text-danger");
                                    } else {
                                        $("#checker_nik").html("<i class=\"fa fa-check-circle\"></i> Validasi Unik").removeClass("text-danger").addClass("text-success");
                                    }
                                },
                                error: function(response) {
                                    console.log(response);
                                }
                            });
                        }
                    });

                    $("#txt_bank_nomor_rekening").val(data.nomor_rekening).inputmask('99999999999999999999');
                    $("#txt_nama").val(data.nama);
                    $("#txt_email").val(data.email);
                    $("#txt_tempat_lahir").val(data.tempat_lahir);
                    $("#txt_tanggal_lahir").val(data.tanggal_lahir);
                    $("#txt_telp").val(data.kontak_telp);
                    $("#txt_wa").val(data.kontak_whatsapp);
                    $("#txt_alamat_domisili").val(data.alamat_domisili);
                    $("#txt_rt").val(data.rt);
                    $("#txt_rw").val(data.rw);
                    $("#txt_domisili_kodepos").val(data.kode_pos_domisili);
                    $("#txt_ktp_kodepos").val(data.kode_pos);
                    $("#txt_npwp").val(data.npwp);
                    $("#txt_ktp_alamat").val(data.alamat_ktp);
                    $("#txt_ahli_waris_nama").val(data.nama_ahli_waris);
                    $("#txt_ahli_waris_hubungan").val(data.hubungan_ahli_waris);
                    $("#txt_ahli_waris_wa").val(data.kontak_whatsapp_ahli_waris);
                    $("#txt_ahli_waris_telp").val(data.kontak_telp_ahli_waris);
                    $("#txt_bank_atas_nama").val(data.nama_pemilik_rekening);
                    $("#txt_patokan").val(data.patokan);

                    loadWilayah('txt_domisili_provinsi', 'provinsi', data.provinsi_domisili, 'Provinsi', data.provinsi_domisili);
                    loadWilayah('txt_domisili_kabupaten', 'kabupaten', data.provinsi_domisili, 'Kabupaten', data.kabupaten_domisili);
                    loadWilayah('txt_domisili_kecamatan', 'kecamatan', data.kabupaten_domisili, 'Kecamatan', data.kecamatan_domisili);
                    loadWilayah('txt_domisili_kelurahan', 'kelurahan', data.kecamatan_domisili, 'Kelurahan', data.kelurahan_domisili);

                    loadWilayah('txt_ktp_provinsi', 'provinsi', data.provinsi, 'Provinsi', data.provinsi);
                    loadWilayah('txt_ktp_kabupaten', 'kabupaten', data.provinsi, 'Kabupaten', data.kabupaten);
                    loadWilayah('txt_ktp_kecamatan', 'kecamatan', data.kabupaten, 'Kecamatan', data.kecamatan);
                    loadWilayah('txt_ktp_kelurahan', 'kelurahan', data.kecamatan, 'Kelurahan', data.kelurahan);




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
                    $("#txt_mentor").append("<option value=\"" + data.mentor.uid + "\">" + data.mentor.nama + "</option>");
                    $("#txt_mentor").select2("data", {id: data.mentor.uid, text: data.mentor.nama});
                    $("#txt_mentor").trigger("change");

                    $("#txt_bank").select2({
                        minimumInputLength: 2,
                        "language": {
                            "noResults": function(){
                                return "Bank tidak ditemukan";
                            }
                        },
                        placeholder:"Cari Bank",
                        ajax: {
                            dataType: "json",
                            headers:{
                                "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                                "Content-Type" : "application/json",
                            },
                            url:__HOSTAPI__ + "/Bank/get_bank_select2",
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
                                            text: item.kode_transaksi + " - " + item.nama,
                                            id: item.uid
                                        }
                                    })
                                };
                            }
                        }
                    });
                    $("#txt_bank").append("<option value=\"" + data.bank.uid + "\">" + data.bank.kode_transaksi + " - " + data.bank.nama + "</option>");
                    $("#txt_bank").select2("data", {id: data.bank.uid, text: data.bank.kode_transaksi + " - " + data.bank.nama});
                    $("#txt_bank").trigger("change");














                    $("#txt_ktp_provinsi").select2();
                    $("#txt_ktp_kabupaten").select2();
                    $("#txt_ktp_kecamatan").select2();
                    $("#txt_ktp_kelurahan").select2();



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
                } else {
                    Swal.fire(
                        "Customer Management",
                        "Customer tidak ditemukan",
                        "error"
                    ).then((result) => {
                        console.log(response);
                    });
                }
            },
            error: function(response) {
                console.log(response);
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


        $("#upload-image").change(function(){
            readURL(this, basic);
        });

        $("#btn_save_data").click(function () {
            simpanData(UID);
        });

        $("#btn_save_data_stay").click(function () {
            simpanData(UID, true);
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


        function loadWilayah(selector, parent, id, name, selected = ""){

            resetSelectBox(selector, name);

            $.ajax({
                url:__HOSTAPI__ + "/Wilayah/"+ parent +"/" + id,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var MetaData = response.response_package.response_data;

                    if (MetaData !== undefined && MetaData !== null){
                        for(i = 0; i < MetaData.length; i++){
                            var selection = document.createElement("OPTION");

                            $(selection).attr("value", MetaData[i].id).html(MetaData[i].nama);
                            if(MetaData[i].id === selected) {
                                $(selection).attr("selected", "selected");
                            }

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

        function simpanData(UID, stay) {
            console.clear();
            var nik = $("#txt_nik").inputmask("unmaskedvalue");
            var nama = $("#txt_nama").val();
            var jenis = $("#txt_jenis_customer").val();
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
            var dom_kodepos = $("#txt_domisili_kodepos").val();
            var rt = $("#txt_rt").val();
            var rw = $("#txt_rw").val();
            var npwp = $("#txt_npwp").inputmask("unmaskedvalue");
            var mentor = $("#txt_mentor").val();
            var patokan = $("#txt_patokan").val();
            var ktp_prov = $("#txt_ktp_provinsi").val();
            var ktp_kab = $("#txt_ktp_kabupaten").val();
            var ktp_kec = $("#txt_ktp_kecamatan").val();
            var ktp_kel = $("#txt_ktp_kelurahan").val();
            var ktp_alamat = $("#txt_ktp_alamat").val();
            var ktp_kodepos = $("#txt_ktp_kodepos").val();

            var ahli_waris_nama = $("#txt_ahli_waris_nama").val();
            var ahli_waris_hubungan = $("#txt_ahli_waris_hubungan").val();
            var ahli_waris_telp = $("#txt_ahli_waris_telp").val();
            var ahli_waris_wa = $("#txt_ahli_waris_wa").val();

            var bank = $("#txt_bank").val();
            var bank_norek = $("#txt_bank_nomor_rekening").inputmask("unmaskedvalue");
            var bank_an = $("#txt_bank_atas_nama").val();
            if(
                nik !== "" &&
                nama !== "" &&
                email !== "" &&
                telp !== "" &&
                wa !== "" &&
                mentor !== "" &&
                bank !== "" &&
                bank_an !== "" &&
                bank_norek !== ""
            ) {
                $("#checker_nik").html("<i class=\"fa fa-check-circle\"></i>").removeClass("text-danger").addClass("text-success");
                $("#checker_nama").html("<i class=\"fa fa-check-circle\"></i>").removeClass("text-danger").addClass("text-success");
                $("#checker_email").html("<i class=\"fa fa-check-circle\"></i>").removeClass("text-danger").addClass("text-success");
                $("#checker_telp").html("<i class=\"fa fa-check-circle\"></i>").removeClass("text-danger").addClass("text-success");
                $("#checker_wa").html("<i class=\"fa fa-check-circle\"></i>").removeClass("text-danger").addClass("text-success");
                Swal.fire({
                    title: "Tambah Customer Baru",
                    showDenyButton: true,
                    type: "warning",
                    confirmButtonText: "Ya",
                    confirmButtonColor: "#1297fb",
                    denyButtonText: "Tidak",
                    denyButtonColor: "#ff2a2a"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            async: false,
                            url: __HOSTAPI__ + "/Membership",
                            beforeSend: function(request) {
                                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                            },
                            type: "POST",
                            data: {
                                request: "edit_customer",
                                uid: UID,
                                nik: nik,
                                nama: nama,
                                tempat_lahir: tempat_lahir,
                                tanggal_lahir: tanggal_lahir,
                                jenis_member: jenis,
                                email: email,
                                kontak_telp: telp,
                                kontak_whatsapp: wa,
                                rt: rt,
                                rw: rw,
                                provinsi: ktp_prov,
                                kabupaten: ktp_kab,
                                kecamatan: ktp_kec,
                                kelurahan: ktp_kel,
                                kode_pos: ktp_kodepos,
                                alamat_ktp: ktp_alamat,
                                provinsi_domisili: dom_prov,
                                kabupaten_domisili: dom_kab,
                                kecamatan_domisili: dom_kec,
                                kelurahan_domisili: dom_kel,
                                alamat_domisili: dom_alamat,
                                kode_pos_domisili: dom_kodepos,
                                mentor: mentor,
                                nomor_rekening: bank_norek,
                                bank: bank,
                                nama_pemilik_rekening: bank_an,
                                nama_ahli_waris: ahli_waris_nama,
                                hubungan_ahli_waris: ahli_waris_hubungan,
                                kontak_telp_ahli_waris: ahli_waris_telp,
                                kontak_whatsapp_ahli_waris: ahli_waris_wa,
                                npwp: npwp,
                                patokan: patokan
                            },
                            success: function(response) {
                                if(response.response_package.response_result > 0) {
                                    Swal.fire(
                                        "Customer Management",
                                        "Customer berhasil diedit",
                                        "success"
                                    ).then((result) => {
                                        if(!stay) {
                                            location.href = __HOSTNAME__ + "/customer";
                                        }
                                    });
                                } else {
                                    Swal.fire(
                                        "Customer Management",
                                        "Customer gagal diedit",
                                        "error"
                                    ).then((result) => {
                                        console.log(response);
                                    });
                                }
                            },
                            error: function(response) {
                                console.log(response);
                            }
                        });
                    }
                });
            } else {
                if(nik === "") {
                    $("#checker_nik").html("<i class=\"fa fa-times-circle\"></i> NIK wajib isi</b>").removeClass("text-success").addClass("text-danger");
                } else {
                    $("#checker_nik").html("<i class=\"fa fa-check-circle\"></i>").removeClass("text-danger").addClass("text-success");
                }

                if(nama === "") {
                    $("#checker_nama").html("<i class=\"fa fa-times-circle\"></i> Nama wajib isi</b>").removeClass("text-success").addClass("text-danger");
                } else {
                    $("#checker_nama").html("<i class=\"fa fa-check-circle\"></i>").removeClass("text-danger").addClass("text-success");
                }

                if(email === "") {
                    $("#checker_email").html("<i class=\"fa fa-times-circle\"></i> Email wajib isi</b>").removeClass("text-success").addClass("text-danger");
                } else {
                    $("#checker_email").html("<i class=\"fa fa-check-circle\"></i>").removeClass("text-danger").addClass("text-success");
                }

                if(telp === "") {
                    $("#checker_telp").html("<i class=\"fa fa-times-circle\"></i> Nomor telepon wajib isi</b>").removeClass("text-success").addClass("text-danger");
                } else {
                    $("#checker_telp").html("<i class=\"fa fa-check-circle\"></i>").removeClass("text-danger").addClass("text-success");
                }

                if(wa === "") {
                    $("#checker_wa").html("<i class=\"fa fa-times-circle\"></i> Nomor Whatsapp wajib isi</b>").removeClass("text-success").addClass("text-danger");
                } else {
                    $("#checker_wa").html("<i class=\"fa fa-check-circle\"></i>").removeClass("text-danger").addClass("text-success");
                }

                if(mentor === "" || mentor === null || mentor === undefined) {
                    $("#checker_mentor").html("<i class=\"fa fa-times-circle\"></i> Pilih mentor</b>").removeClass("text-success").addClass("text-danger");
                } else {
                    $("#checker_mentor").html("<i class=\"fa fa-check-circle\"></i>").removeClass("text-danger").addClass("text-success");
                }

                if(bank === "" || bank === null || bank === undefined) {
                    $("#checker_bank").html("<i class=\"fa fa-times-circle\"></i> Pilih bank</b>").removeClass("text-success").addClass("text-danger");
                } else {
                    $("#checker_bank").html("<i class=\"fa fa-check-circle\"></i>").removeClass("text-danger").addClass("text-success");
                }

                if(bank_an === "") {
                    $("#checker_bank_an").html("<i class=\"fa fa-times-circle\"></i> Nama wajib isi</b>").removeClass("text-success").addClass("text-danger");
                } else {
                    $("#checker_bank_an").html("<i class=\"fa fa-check-circle\"></i>").removeClass("text-danger").addClass("text-success");
                }

                if(bank_norek === "") {
                    $("#checker_bank_norek").html("<i class=\"fa fa-times-circle\"></i> Nomor wajib isi</b>").removeClass("text-success").addClass("text-danger");
                } else {
                    $("#checker_bank_norek").html("<i class=\"fa fa-check-circle\"></i>").removeClass("text-danger").addClass("text-success");
                }
            }


            /*console.log(nik);
            console.log(nama);
            console.log(jenis);
            console.log(email);
            console.log(tempat_lahir);
            console.log(tanggal_lahir);
            console.log(telp);
            console.log(wa);
            console.log(dom_prov);
            console.log(dom_kab);
            console.log(dom_kec);
            console.log(dom_kel);
            console.log(dom_alamat);
            console.log(dom_kodepos);
            console.log(rt);
            console.log(rw);
            console.log(mentor);
            console.log(ktp_prov);
            console.log(ktp_kab);
            console.log(ktp_kec);
            console.log(ktp_kel);
            console.log(ktp_alamat);
            console.log(ktp_kodepos);
            console.log(ahli_waris_nama);
            console.log(ahli_waris_hubungan);
            console.log(ahli_waris_telp);
            console.log(ahli_waris_wa);
            console.log(bank);
            console.log(bank_norek);
            console.log(bank_an);*/

        }
    });
</script>