<script type="text/javascript">
    $(function () {

        /*loadWilayah("order_provinsi", "provinsi", "", "Provinsi");
        resetSelectBox("order_provinsi", "Provinsi");
        resetSelectBox("order_kabupaten", "Kabupaten");
        resetSelectBox("order_kecamatan", "Kecamatan");
        resetSelectBox("order_kelurahan", "Kelurahan");*/

        $("#order_provinsi").select2();
        $("#order_kabupaten").select2();
        $("#order_kecamatan").select2();
        $("#order_kelurahan").select2();
        $("#order_disc_type").select2();
        $("#order_disc").inputmask({
            alias: 'currency',
            rightAlign: true,
            placeholder: "0.00",
            prefix: "",
            autoGroup: false,
            digitsOptional: true
        }).addClass("form-control order_qty");

        $("#order_provinsi").on("change", function(){
            var id = $(this).val();

            loadWilayah("order_kabupaten", "kabupaten", id, "Kabupaten / Kota");
            resetSelectBox("order_kecamatan", "Kecamatan");
            resetSelectBox("order_kelurahan", "Kelurahan");
        });

        $("#order_kabupaten").on("change", function(){
            var id = $(this).val();

            loadWilayah("order_kecamatan", "kecamatan", id, "Kecamatan");
            resetSelectBox("order_kelurahan", "Kelurahan");
        });

        $("#order_kecamatan").on("change", function(){
            var id = $(this).val();

            loadWilayah("order_kelurahan", "kelurahan", id, "Kelurahan");
        });

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

                    if (MetaData != ""){
                        for(i = 0; i < MetaData.length; i++){
                            var selection = document.createElement("OPTION");

                            $(selection).attr("value", MetaData[i].id).html(MetaData[i].nama);

                            // autoSelect(selector, MetaData[i].id , params);
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

        $("#btn-samakan-customer").click(function() {
            $("#order_receiver").val($("#order_customer option:selected").text());
        });

        $("#order_customer").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function(){
                    return "Customer tidak ditemukan";
                }
            },
            ajax: {
                dataType: "json",
                headers:{
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/Membership/get_customer_select2",
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
                                text: item.nama.toUpperCase() + " - " + item.nik,
                                id: item.uid,
                                jenis_member: item.jenis_member,
                                alamat_domisili: item.alamat_domisili,
                                provinsi_domisili: item.provinsi_domisili,
                                kabupaten_domisili: item.kabupaten_domisili,
                                kecamatan_domisili: item.kecamatan_domisili,
                                kelurahan_domisili: item.kelurahan_domisili,
                            }
                        })
                    };
                }
            }
        }).addClass("form-control item-order").on("select2:select", function(e) {
            var data = e.params.data;

            $(this).find("option:selected").attr({
                "jenis_member": data.jenis_member
            });

            $("#order_delivery").val(data.alamat_domisili);
            $("#order_charge").val(data.alamat_domisili);


            loadWilayah("order_provinsi", "provinsi", data.provinsi_domisili, "Provinsi", data.provinsi_domisili);
            loadWilayah("order_kabupaten", "kabupaten", data.provinsi_domisili, "Kabupaten", data.kabupaten_domisili);
            loadWilayah("order_kecamatan", "kecamatan", data.kabupaten_domisili, "Kecamatan", data.kecamatan_domisili);
            loadWilayah("order_kelurahan", "kelurahan", data.kecamatan_domisili, "Kelurahan", data.kelurahan_domisili);

            $("#auto_produk tbody tr").each(function (e) {
                var id = $(this).attr("id").split("_");
                id = id[id.length - 1];
                get_item_detail($("#item_" + id).val(), id);
            });
        });

        $("body").on("keyup", ".order_qty", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            if(
                $("#auto_order_" + id).hasClass("last_row") &&
                $("#item_" + id).val() !== undefined && $("#item_" + id).val() !== null &&
                $("#qty_" + id).inputmask("unmaskedvalue") > 0
            ) {
                autoProduk();
            }

            get_item_detail($("#item_" + id).val(), id);
        });

        function get_item_detail(uid, id) {
            if(uid !== undefined && uid !== null) {
                var data;
                $.ajax({
                    async: true,
                    url: __HOSTAPI__ + "/Inventori/item_detail/" + uid,
                    type: "GET",
                    beforeSend: function(request) {
                        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                    },
                    success: function (response) {
                        //data = response.response_package.response_data[0];
                        data = response.response_data;
                        //console.log(response);
                        recalculate_item(data, $("#order_customer option:selected").attr("jenis_member"), id);
                    },
                    error: function (response) {
                        //
                    }
                });
                return data;
            }
        }

        function count_total() {
            var total_harga_jual = 0;
            var total_harga_cashback = 0;
            var total_harga_royalti = 0;
            var total_harga_reward = 0;
            var total_harga_insentif = 0;

            $("#auto_produk tbody tr").each(function () {
                total_harga_jual += parseFloat($(this).find("td:eq(3)").html().replaceAll(",",""));
                total_harga_cashback += parseFloat($(this).find("td:eq(4)").html().replaceAll(",",""));
                total_harga_royalti += parseFloat($(this).find("td:eq(5)").html().replaceAll(",",""));
                total_harga_reward += parseFloat($(this).find("td:eq(6)").html().replaceAll(",",""));
                total_harga_insentif += parseFloat($(this).find("td:eq(7)").html().replaceAll(",",""));
            });

            $("#order_total_belanja").html(number_format(total_harga_jual, 2, ".", ","));
            $("#order_total_cashback").html(number_format(total_harga_cashback, 2, ".", ","));
            $("#order_total_royalti").html(number_format(total_harga_royalti, 2, ".", ","));
            $("#order_total_reward").html(number_format(total_harga_reward, 2, ".", ","));
            $("#order_total_insentif").html(number_format(total_harga_insentif, 2, ".", ","));

            var disc_type = $("#order_disc_type").val();
            var disc = $("#order_disc").inputmask("unmaskedvalue");

            var grandtotal = 0;
            if(disc_type === "N") {
                grandtotal = total_harga_jual;
            } else if(disc_type === "P") {
                grandtotal = total_harga_jual - (disc / 100 * total_harga_jual);
            } else {
                grandtotal = total_harga_jual - disc;
            }

            $("#order_grand_total").html(number_format(grandtotal, 2, ".", ","));
        }

        $("#order_disc").on("keyup", function () {
            count_total();
        });

        $("#order_disc_type").on("change", function () {
            count_total();
        });

        function recalculate_item(data, type, ID) {

            if(data !== undefined && data !== null) {
                if(data.harga !== undefined && data.harga !== null) {
                    if(type === "S") {
                        $("#harga_jual_" + ID).html(number_format(($("#qty_" + ID).inputmask("unmaskedvalue") * parseFloat(data.harga.harga_akhir_stokis)), 2, ".", ","));
                        $("#cashback_" + ID).html(number_format(data.harga.stokis_cashback, 2, ".", ","));
                        $("#royalti_" + ID).html(number_format(data.harga.stokis_royalti, 2, ".", ","));
                        $("#reward_" + ID).html(number_format(data.harga.stokis_reward, 2, ".", ","));
                        $("#insentif_" + ID).html(number_format(data.harga.stokis_insentif_personal, 2, ".", ","));
                    } else {
                        $("#harga_jual_" + ID).html(number_format(($("#qty_" + ID).inputmask("unmaskedvalue") * parseFloat(data.harga.harga_akhir_member)), 2, ".", ","));
                        $("#cashback_" + ID).html(number_format(data.harga.member_cashback, 2, ".", ","));
                        $("#royalti_" + ID).html(number_format(data.harga.member_royalti, 2, ".", ","));
                        $("#reward_" + ID).html(number_format(data.harga.member_reward, 2, ".", ","));
                        $("#insentif_" + ID).html(number_format(data.harga.member_insentif_personal, 2, ".", ","));
                    }
                } else {
                    $("#harga_jual_" + ID).html("0.00");
                    $("#cashback_" + ID).html("0.00");
                    $("#royalti_" + ID).html("0.00");
                    $("#reward_" + ID).html("0.00");
                    $("#insentif_" + ID).html("0.00");
                    return false;
                }

                count_total();
            }
        }

        function autoProduk() {
            var newRow = document.createElement("TR");

            var newID = document.createElement("TD");
            var newCellProduk = document.createElement("TD");
            var newCellQty = document.createElement("TD");
            var newCellJual = document.createElement("TD");
            var newCellC = document.createElement("TD");
            var newCellRo = document.createElement("TD");
            var newCellRe = document.createElement("TD");
            var newCellInsentif = document.createElement("TD");
            var newCellAksi = document.createElement("TD");

            var newProduk = document.createElement("SELECT");
            var newQty = document.createElement("INPUT");
            var newDelete = document.createElement("BUTTON");

            $(newCellProduk).append(newProduk);
            $(newCellQty).append(newQty);

            $(newProduk).select2({
                minimumInputLength: 2,
                "language": {
                    "noResults": function(){
                        return "Barang tidak ditemukan";
                    }
                },
                ajax: {
                    dataType: "json",
                    headers:{
                        "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                        "Content-Type" : "application/json",
                    },
                    url:__HOSTAPI__ + "/Inventori/get_item_select2",
                    type: "GET",
                    data: function (term) {
                        return {
                            search:term.term
                        };
                    },
                    cache: true,
                    processResults: function (response) {

                        var data = response.response_data;
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.nama.toUpperCase(),
                                    id: item.uid,
                                    satuan_terkecil: (item.satuan_terkecil === null || item.satuan_terkecil === undefined) ? "-" : item.satuan_terkecil.nama,
                                    harga: item.harga
                                }
                            })
                        };
                    }
                }
            }).addClass("form-control item-order").on("select2:select", function(e) {
                var data = e.params.data;


                var id = $(this).attr("id").split("_");
                id = id[id.length - 1];

                //recalculate_item(, $("#order_customer option:selected").attr("jenis_member"), id);
                get_item_detail(data.id, id);

                if(
                    $("#auto_order_" + id).hasClass("last_row") &&
                    $("#item_" + id).val() !== undefined && $("#item_" + id).val() !== null &&
                    $("#qty_" + id).inputmask("unmaskedvalue") > 0
                ) {
                    autoProduk();
                }

            });

            $(newQty).inputmask({
                alias: 'currency',
                rightAlign: true,
                placeholder: "0.00",
                prefix: "",
                autoGroup: false,
                digitsOptional: true
            }).addClass("form-control order_qty").val(1).attr({
                "min": 1
            }).attr({
                "autocomplete": "off"
            });

            $(newCellAksi).append(newDelete);
            $(newDelete).html("<i class=\"fa fa-ban\"></i>").addClass("btn btn-sm btn-danger");
            $(newRow).append(newID);
            $(newRow).append(newCellProduk);
            $(newRow).append(newCellQty);
            $(newRow).append(newCellJual);
            $(newRow).append(newCellC);
            $(newRow).append(newCellRo);
            $(newRow).append(newCellRe);
            $(newRow).append(newCellInsentif);
            $(newRow).append(newCellAksi);

            $(newCellJual).html("0.00");
            $(newCellC).html("0.00");
            $(newCellRo).html("0.00");
            $(newCellRe).html("0.00");
            $(newCellInsentif).html("0.00");

            $(newCellJual).addClass("number_style");
            $(newCellC).addClass("number_style");
            $(newCellRo).addClass("number_style");
            $(newCellRe).addClass("number_style");
            $(newCellInsentif).addClass("number_style");

            $("#auto_produk tbody").append(newRow);
            rebase_produk();
            $(newRow).addClass("last_row");
        }

        function rebase_produk() {
            $("#auto_produk tbody tr").each(function (e) {
                var id = (e + 1);

                $(this).find("td:eq(0)").html(id);

                $(this).removeClass("last_row").attr({
                    "id": "auto_order_" + id
                });

                $(this).find("td:eq(1) select").attr({
                    "id": "item_" + id
                });

                $(this).find("td:eq(2) input").attr({
                    "id": "qty_" + id
                });

                $(this).find("td:eq(3)").attr({
                    "id": "harga_jual_" + id
                });

                $(this).find("td:eq(4)").attr({
                    "id": "cashback_" + id
                });

                $(this).find("td:eq(5)").attr({
                    "id": "royalti_" + id
                });

                $(this).find("td:eq(6)").attr({
                    "id": "reward_" + id
                });

                $(this).find("td:eq(7)").attr({
                    "id": "insentif_" + id
                });
            });
        }

        autoProduk();

        function warn_manager(identifier, status) {
            if(status) {
                $(identifier).html("<i class=\"fa fa-check-circle\"></i> OK").addClass("text-success").removeClass("text-danger");
            } else {
                $(identifier).html("<i class=\"fa fa-times-circle\"></i> Required").addClass("text-danger").removeClass("text-success");
            }
        }

        $("#btnOrder").click(function () {
            var customer = $("#order_customer").val();
            var penerima = $("#order_receiver").val();
            var kurir = $("#order_kurir").val();
            var provinsi = parseInt($("#order_provinsi").val());
            var kabupaten = parseInt($("#order_kabupaten").val());
            var kecamatan = parseInt($("#order_kecamatan").val());
            var kelurahan = parseInt($("#order_kelurahan").val());
            var alamat_billing = $("#order_charge").val();
            var alamat_antar = $("#order_delivery").val();
            var total_pre_discount = parseFloat($("#order_total_belanja").html().replaceAll(",",""));
            var disc_type = $("#order_disc_type").val();
            var disc = $("#order_disc").inputmask("unmaskedvalue");
            var total_after_discount = parseFloat($("#order_grand_total").html().replaceAll(",",""));
            var remark = $("#order_remark").val();
            var itemDetail = [];

            $("#auto_produk tbody tr").each(function () {
                if(!$(this).hasClass("last_row")) {
                    var produk = $(this).find("td:eq(1) select").val();
                    var qty = $(this).find("td:eq(2) input").inputmask("unmaskedvalue");
                    var total_per_item = $(this).find("td:eq(3)").html().replaceAll(",","");
                    var cashback = $(this).find("td:eq(4)").html().replaceAll(",","");
                    var royalti = $(this).find("td:eq(5)").html().replaceAll(",","");
                    var reward = $(this).find("td:eq(6)").html().replaceAll(",","");
                    var insentif = $(this).find("td:eq(7)").html().replaceAll(",","");

                    if(produk !== undefined && produk !== null && parseFloat(qty) > 0) {
                        itemDetail.push({
                            produk: produk,
                            qty: qty,
                            total: total_per_item,
                            cashback: cashback,
                            royalti: royalti,
                            reward: reward,
                            insentif: insentif
                        });

                    }
                }
            });

            if(
                customer !== undefined && customer !== null &&
                kurir !== "" &&
                provinsi > 0 &&
                kabupaten > 0 &&
                kecamatan > 0 &&
                kelurahan > 0 &&
                alamat_billing !== "" &&
                alamat_antar !== "" &&
                itemDetail.length > 0
            ) {
                Swal.fire({
                    title: "Order sudah sesuai?",
                    showDenyButton: true,
                    type: "warning",
                    confirmButtonText: "Ya",
                    confirmButtonColor: "#1297fb",
                    denyButtonText: "Cek Kembali",
                    denyButtonColor: "#ff2a2a"
                }).then((result) => {
                    if (result.isConfirmed) {


                        $.ajax({
                            async: false,
                            url: __HOSTAPI__ + "/Orders",
                            beforeSend: function (request) {
                                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                            },
                            type: "POST",
                            data: {
                                request: "tambah_order",
                                customer: customer,
                                penerima: penerima,
                                kurir: kurir,
                                provinsi: provinsi,
                                kabupaten: kabupaten,
                                kecamatan: kecamatan,
                                kelurahan: kelurahan,
                                alamat_antar: alamat_antar,
                                alamat_billing: alamat_billing,
                                total_pre_discount: total_pre_discount,
                                disc_type: disc_type,
                                disc: disc,
                                total_after_discount: total_after_discount,
                                remark: remark,
                                itemDetail: itemDetail
                            },
                            success: function (response) {
                                if(response.response_package.response_result > 0) {
                                    Swal.fire(
                                        "Order",
                                        "Berhasil ditambahkan",
                                        "success"
                                    ).then((result) => {
                                        location.href = __HOSTNAME__ + "/penjualan/order";
                                    });
                                } else {
                                    Swal.fire(
                                        "Order",
                                        "Gagal ditambahkan",
                                        "error"
                                    ).then((result) => {
                                        console.log(response);
                                    });
                                }
                            },
                            error: function (response) {
                                console.log(response);
                            }
                        });
                    }
                });
            } else {
                if(customer === undefined || customer === null) {
                    warn_manager("#warn_customer", false);
                } else {
                    warn_manager("#warn_customer", true);
                }

                if(kurir === "") {
                    warn_manager("#warn_kurir", false);
                } else {
                    warn_manager("#warn_kurir", true);
                }

                if(provinsi === 0 || isNaN(provinsi)) {
                    warn_manager("#warn_provinsi", false);
                } else {
                    warn_manager("#warn_provinsi", true);
                }

                if(kabupaten === 0 || isNaN(kabupaten)) {
                    warn_manager("#warn_kabupaten", false);
                } else {
                    warn_manager("#warn_kabupaten", true);
                }

                if(kecamatan === 0 || isNaN(kecamatan)) {
                    warn_manager("#warn_kecamatan", false);
                } else {
                    warn_manager("#warn_kecamatan", true);
                }

                if(kelurahan === 0 || isNaN(kelurahan)) {
                    warn_manager("#warn_kelurahan", false);
                } else {
                    warn_manager("#warn_kelurahan", true);
                }

                if(alamat_antar === "") {
                    warn_manager("#warn_delivery", false);
                } else {
                    warn_manager("#warn_delivery", true);
                }

                if(alamat_billing === "") {
                    warn_manager("#warn_billing", false);
                } else {
                    warn_manager("#warn_billing", true);
                }

                if(itemDetail.length === 0) {
                    warn_manager("#warn_item", false);
                } else {
                    warn_manager("#warn_item", true);
                }
            }
        });
    });
</script>