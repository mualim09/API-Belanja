<script type="text/javascript">
    $(function () {
        function getDateRange(target) {
            var rangeKwitansi = $(target).val().split(" to ");
            if(rangeKwitansi.length > 1) {
                return rangeKwitansi;
            } else {
                return [rangeKwitansi, rangeKwitansi];
            }
        }

        $("#range_order").change(function() {
            if(
                !Array.isArray(getDateRange("#range_order")[0]) &&
                !Array.isArray(getDateRange("#range_order")[1])
            ) {
                orderData.ajax.reload();
            }
        });

        var orderData = $("#table-order").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Orders",
                type: "POST",
                data: function(d) {
                    d.request = "get_order_backend";
                    d.status = "N";
                    d.from = getDateRange("#range_order")[0];
                    d.to = getDateRange("#range_order")[1];
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var returnedData = [];
                    if(response === undefined || response.response_package === undefined || response.response_package.response_data === undefined) {
                        returnedData = [];
                    } else {
                        returnedData = response.response_package.response_data;
                    }

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;

                    return returnedData;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Nomor Invoice"
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h6 class=\"wrap_content\">" + ((row.source === 'A') ? "<i class=\"fa fa-tablet-alt\"></i>" : "<i class=\"fa fa-desktop\"></i>") + " " + row.nomor_invoice + "</h6>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<a href=\"mailto:" + row.customer.email + "\"><h5><i class=\"fa fa-envelope\"></i> " + row.customer.nama + "</h5></a><br />" +
                            "<div style=\"padding-left: 20px;\">" +
                            "<i class=\"fa fa-phone\"></i> " + row.customer.kontak_telp + "<br />" +
                            "<i class=\"fa fa-comment\"></i> " + row.customer.kontak_whatsapp + " (WA)" +
                            "</div>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return (row.customer.jenis_member === "M") ? "Member" : "Stokis";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        var parseStatus = "";
                        if(row.status === "N") {
                            parseStatus = "<span class=\"text-info\"><i class=\"fa fa-info-circle\"></i> Baru</span>";
                        } else if(row.status === "P") {
                            parseStatus = "<span class=\"text-warning\"><i class=\"fa fa-truck\"></i> Sedang Diantar</span>";
                        } else if(row.status === "R") {
                            parseStatus = "<span class=\"text-success\"><i class=\"fa fa-boxes\"></i> Diterima</span>";
                        } else {
                            parseStatus = "<span class=\"text-muted\"><i class=\"fa fa-check-circle\"></i> Selesai</span>";
                        }

                        return parseStatus;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<b class=\"number_style wrap_content\">" + number_format(row.total_after_disc, 2 , ".", ",") + "</b>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h6 class=\"wrap_content\">" + row.tanggal_order + "</h6>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<button class=\"btn btn-info detailOrder\" id=\"detail_" + row.uid + "\"><i class=\"fa fa-wrench\"></i></button>";
                    }
                }
            ]
        });

        $("body").on("click", ".detailOrder", function () {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/Orders/detail/" + id,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "GET",
                success: function(response) {
                    var data = response.response_package.response_data[0];
                    console.log(data);

                    var parseStatus = "";
                    if(data.status === "N") {
                        parseStatus = "<span class=\"text-info\"><i class=\"fa fa-info-circle\"></i> Baru</span>";
                    } else if(data.status === "P") {
                        parseStatus = "<span class=\"text-warning\"><i class=\"fa fa-truck\"></i> Sedang Diantar</span>";
                    } else if(data.status === "R") {
                        parseStatus = "<span class=\"text-success\"><i class=\"fa fa-boxes\"></i> Diterima</span>";
                    } else {
                        parseStatus = "<span class=\"text-muted\"><i class=\"fa fa-check-circle\"></i> Selesai</span>";
                    }

                    $("#nama_customer").html(data.customer.nama);
                    $("#jenis_member").html(((data.jenis_member === "M") ? "Member" : "Stokis"));
                    $("#invoice_tanggal").html(data.tanggal_order);
                    $("#invoice_nomor").html(data.nomor_invoice);
                    $("#alamat_antar").html(data.alamat_antar);
                    $("#alamat_tagih").html(data.alamat_billing);
                    $("#invoice_kurir").html(data.kurir);
                    $("#invoice_status").html(parseStatus);
                    $("#invoice_total").html(number_format(data.total_after_disc, 2, ".", ","));
                    $("#invoice_remark").html(data.remark);
                    $("#order_total_belanja").html(number_format(data.total_pre_disc, 2, ".", ","));
                    $("#order_grand_total").html(number_format(data.total_after_disc, 2 ,".", ","));

                    if(data.disc_type === "N") {
                        $("#invoice_disc_type").html("DISCOUNT");
                        $("#invoice_disc").html("-");
                    } else if(data.disc_type === "P") {
                        $("#invoice_disc_type").html("DISCOUNT");
                        $("#invoice_disc").html(data.disc + "%");
                    } else {
                        $("#invoice_disc_type").html("DISCOUNT");
                        $("#invoice_disc").html("(" + data.disc + ")");
                    }


                    var cashback = 0;
                    var royalti = 0;
                    var reward = 0;
                    var insentif = 0;
                    $("#auto_produk tbody").html("");
                    for(var a in data.detail) {
                        var id = parseInt(a) + 1;
                        $("#auto_produk tbody").append("" +
                            "<tr>" +
                            "<td>" + id + "</td>" +
                            "<td>" + data.detail[a].barang.nama + "</td>" +
                            "<td class=\"number_style\">" + data.detail[a].qty + "</td>" +
                            "<td class=\"number_style\">" + data.detail[a].harga + "</td>" +
                            "<td class=\"number_style\">" + number_format(data.detail[a].cashback, 2, ".", ",") + "</td>" +
                            "<td class=\"number_style\">"  + number_format(data.detail[a].royalti, 2, ".", ",") + "</td>" +
                            "<td class=\"number_style\">" + number_format(data.detail[a].reward, 2, ".", ",") + "</td>" +
                            "<td class=\"number_style\">" + number_format(data.detail[a].insentif_personal, 2, ".", ",") + "</td>" +
                            "</tr>");

                        cashback += parseFloat(data.detail[a].cashback);
                        royalti += parseFloat(data.detail[a].royalti);
                        reward += parseFloat(data.detail[a].reward);
                        insentif += parseFloat(data.detail[a].insentif_personal);
                    }

                    $("#order_total_cashback").html(number_format(cashback, 2, ".", ","));
                    $("#order_total_royalti").html(number_format(royalti, 2, ".", ","));
                    $("#order_total_reward").html(number_format(reward, 2, ".", ","));
                    $("#order_total_insentif").html(number_format(insentif, 2, ".", ","));

                    $("#modal-order").modal("show");
                },
                error: function (response) {
                    //
                }
            });

        });
    });
</script>



<div id="modal-order" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    Detail Order
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card card-form">
                    <div class="row no-gutters">
                        <div class="col-lg-4 card-body">
                            <p>
                                <strong class="headings-color" id="nama_customer"></strong>
                                <br />
                                <small><b id="jenis_member"></b></small>
                            </p>
                            Antar:<br />
                            <p class="text-muted" id="alamat_antar"></p>
                            Tagih:<br />
                            <p class="text-muted" id="alamat_tagih"></p>
                        </div>
                        <div class="col-lg-8 card-body">
                            <div class="form-row">
                                <div class="col-4">
                                    <label for="">Nomor Invoice</label><br />
                                    <b id="invoice_nomor"></b>
                                </div>
                                <div class="col-4">
                                    <label for="">Kurir</label><br />
                                    <b id="invoice_kurir"></b>
                                </div>
                                <div class="col-4">
                                    <label for="">Tanggal</label><br />
                                    <b id="invoice_tanggal"></b>
                                </div>
                            </div>
                            <br />
                            <div class="form-row">
                                <div class="col-4">
                                    <label for="">Total Invoice</label><br />
                                    <h4 class="text-info" id="invoice_total"></h4>
                                </div>
                                <div class="col-4">
                                    <label for="">Status</label><br />
                                    <b id="invoice_status"></b>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-form">
                    <div class="row no-gutters">
                        <div class="col-lg-12 card-body">
                            <table class="table table-bordered largeDataType" id="auto_produk">
                                <thead class="thead-dark">
                                <tr>
                                    <th rowspan="2" class="wrap_content">No</th>
                                    <th rowspan="2" style="width: 250px;">Produk</th>
                                    <th rowspan="2" style="width: 80px;">Qty</th>
                                    <th rowspan="2" style="width: 100px;">Harga Jual</th>
                                    <th colspan="4">Bonus</th>
                                </tr>
                                <tr>
                                    <th style="width: 100px;">Cashback</th>
                                    <th style="width: 100px;">Royalti</th>
                                    <th style="width: 100px;">Reward</th>
                                    <th style="width: 100px;">Insentif</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot style="background: #fafafa">
                                <tr>
                                    <td colspan="3" class="text-right">
                                        <b>TOTAL (Pre Disc.)</b>
                                    </td>
                                    <td class="number_style" id="order_total_belanja">0.00</td>
                                    <td class="number_style" id="order_total_cashback">0.00</td>
                                    <td class="number_style" id="order_total_royalti">0.00</td>
                                    <td class="number_style" id="order_total_reward">0.00</td>
                                    <td class="number_style" id="order_total_insentif">0.00</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right">
                                        <b id="invoice_disc_type">DISCOUNT</b>
                                    </td>
                                    <td class="text-right">
                                        <b id="invoice_disc" class="text-right"></b>
                                    </td>
                                    <td colspan="4" rowspan="2">
                                        <b>Remark:</b>
                                        <p style="min-height: 100px;" id="invoice_remark"></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right">
                                        <b>Grand TOTAL</b>
                                    </td>
                                    <td class="number_style" id="order_grand_total" style="font-size: 14pt">0.00</td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!--button class="btn btn-success" id="btnProsesSEP">
                    <i class="fa fa-check"></i> Tambah
                </button-->

                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>