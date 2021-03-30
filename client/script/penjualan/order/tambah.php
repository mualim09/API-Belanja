<script type="text/javascript">
    $(function () {

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
                                jenis_member: item.jenis_member
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
                        data = response.response_package.response_data[0];
                        recalculate_item(data, $("#order_customer option:selected").attr("jenis_member"), id);
                    },
                    error: function (response) {
                        //
                    }
                });
                return data;
            }
        }

        function recalculate_item(data, type, ID) {

            if(data !== undefined && data !== null) {
                console.log(data.harga.harga_akhir_stokis);
                console.log(data.harga.harga_akhir_member);
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
                        var data = response.response_package.response_data;
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.nama.toUpperCase(),
                                    id: item.uid,
                                    satuan_terkecil: item.satuan_terkecil.nama,
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
            }).addClass("form-control order_qty");

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

                $(this).removeClass("last_row").attr({
                    "id": "auto_order_" + id
                });

                $(this).find("td:eq(0)").html(id);

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
    });
</script>