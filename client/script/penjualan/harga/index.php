<script type="text/javascript">
    $(function () {
        $("#txt_tanggal").datepicker({
            onSelect: function(dateText) {
                tableHarga.ajax.reload();
            }
        }).datepicker("setDate", new Date());

        var tableHarga = $("#tableHarga").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Inventori",
                type: "POST",
                data: function(d) {
                    d.request = "get_item_price";
                    d.tanggal = $("#txt_tanggal").val().replaceAll("/", "-");
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var returnedData = [];
                    if(response == undefined || response.response_package == undefined) {
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
                searchPlaceholder: "Cari Nama Barang"
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<b id=\"nama_" + row.uid + "\">" + row.nama.toUpperCase() + "</b>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h6 class=\"number_style\">" + number_format(row.het, 2, ".", ",") + "</h6>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<div class=\"row\">" +
                            "<div class=\"col-lg-6 text-right text-success\">S</div>" +
                            "<div class=\"col-lg-6\">" +
                            "<h6 class=\"number_style text-success\" id=\"hscb_" + row.uid + "\">" + number_format(((row.harga !== undefined && row.harga !== null) ? row.harga.stokis_cashback : 0), 2, ".", ",") + "</h6>" +
                            "</div>" +
                            "<div class=\"col-lg-6 text-right text-info\">M</div>" +
                            "<div class=\"col-lg-6\">" +
                            "<h6 class=\"number_style text-info\" id=\"hmcb_" + row.uid + "\">" + number_format(((row.harga !== undefined && row.harga !== null) ? row.harga.member_cashback : 0), 2, ".", ",") + "</h6>" +
                            "</div>" +
                            "</div>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<div class=\"row\">" +
                            "<div class=\"col-lg-6 text-right text-success\">S</div>" +
                            "<div class=\"col-lg-6\">" +
                            "<h6 class=\"number_style text-success\" id=\"hsry_" + row.uid + "\">" + number_format(((row.harga !== undefined && row.harga !== null) ? row.harga.stokis_royalti : 0), 2, ".", ",") + "</h6>" +
                            "</div>" +
                            "<div class=\"col-lg-6 text-right text-info\">M</div>" +
                            "<div class=\"col-lg-6\">" +
                            "<h6 class=\"number_style text-info\" id=\"hmry_" + row.uid + "\">" + number_format(((row.harga !== undefined && row.harga !== null) ? row.harga.member_royalti : 0), 2, ".", ",") + "</h6>" +
                            "</div>" +
                            "</div>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<div class=\"row\">" +
                            "<div class=\"col-lg-6 text-right text-success\">S</div>" +
                            "<div class=\"col-lg-6\">" +
                            "<h6 class=\"number_style text-success\" id=\"hsrw_" + row.uid + "\">" + number_format(((row.harga !== undefined && row.harga !== null) ? row.harga.stokis_reward : 0), 2, ".", ",") + "</h6>" +
                            "</div>" +
                            "<div class=\"col-lg-6 text-right text-info\">M</div>" +
                            "<div class=\"col-lg-6\">" +
                            "<h6 class=\"number_style text-info\" id=\"hmrw_" + row.uid + "\">" + number_format(((row.harga !== undefined && row.harga !== null) ? row.harga.member_reward : 0), 2, ".", ",") + "</h6>" +
                            "</div>" +
                            "</div>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<div class=\"row\">" +
                            "<div class=\"col-lg-6 text-right text-success\">S</div>" +
                            "<div class=\"col-lg-6\">" +
                            "<h6 class=\"number_style text-success\" id=\"hsip_" + row.uid + "\">" + number_format(((row.harga !== undefined && row.harga !== null) ? row.harga.stokis_insentif_personal : 0), 2, ".", ",") + "</h6>" +
                            "</div>" +
                            "<div class=\"col-lg-6 text-right text-info\">M</div>" +
                            "<div class=\"col-lg-6\">" +
                            "<h6 class=\"number_style text-info\" id=\"hmip_" + row.uid + "\">" + number_format(((row.harga !== undefined && row.harga !== null) ? row.harga.member_insentif_personal : 0), 2, ".", ",") + "</h6>" +
                            "</div>" +
                            "</div>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        if(row.allow_edit) {
                            return "<button class=\"btn btn-info btn-edit-harga\" id=\"edit_harga_" + row.uid + "\"><i class=\"fa fa-pencil-alt\"></i></button>";
                        } else {
                            return "<h6 class=\"text-danger text-center\"><i class=\"fa fa-ban text-red\"></i></h6>";
                        }
                    }
                }
            ]
        });

        $(".editorHarga").inputmask({
            alias: 'currency',
            rightAlign: true,
            placeholder: "0.00",
            prefix: "",
            autoGroup: false,
            digitsOptional: true
        });

        var targetProduk;

        $("body").on("click", ".btn-edit-harga", function () {
            var uid = $(this).attr("id").split("_");
            uid = uid[uid.length - 1];
            targetProduk = uid;

            $("#nama_produk").val($("#nama_" + uid).html());
            $("#tanggal_harga").val($("#txt_tanggal").val());

            $("#txt_cashback_member").val(parseInt($("#hmcb_" + uid).html()));
            $("#txt_royalti_member").val(parseInt($("#hmry_" + uid).html()));
            $("#txt_reward_member").val(parseInt($("#hmrw_" + uid).html()));
            $("#txt_insentif_personal_member").val(parseInt($("#hmip_" + uid).html()));

            $("#txt_cashback_stokis").val(parseInt($("#hscb_" + uid).html()));
            $("#txt_royalti_stokis").val(parseInt($("#hsry_" + uid).html()));
            $("#txt_reward_stokis").val(parseInt($("#hsrw_" + uid).html()));
            $("#txt_insentif_personal_stokis").val(parseInt($("#hsip_" + uid).html()));

            $("#form-harga").modal("show");
        });

        $("#btnProsesHarga").click(function () {
            var m_cb = $("#txt_cashback_member").inputmask("unmaskedvalue");
            var m_ry = $("#txt_royalti_member").inputmask("unmaskedvalue");
            var m_rw = $("#txt_reward_member").inputmask("unmaskedvalue");
            var m_ip = $("#txt_insentif_personal_member").inputmask("unmaskedvalue");

            var s_cb = $("#txt_cashback_stokis").inputmask("unmaskedvalue");
            var s_ry = $("#txt_royalti_stokis").inputmask("unmaskedvalue");
            var s_rw = $("#txt_reward_stokis").inputmask("unmaskedvalue");
            var s_ip = $("#txt_insentif_personal_stokis").inputmask("unmaskedvalue");

            Swal.fire({
                title: "Data sudah benar?",
                showDenyButton: true,
                confirmButtonText: "Sudah",
                denyButtonText: "Belum",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        async: false,
                        url: __HOSTAPI__ + "/Inventori",
                        data: {
                            request: "update_data_harga",
                            produk: targetProduk,
                            tanggal: $("#txt_tanggal").val().replaceAll("/", "-"),
                            m_cb: m_cb,
                            m_ry: m_ry,
                            m_rw: m_rw,
                            m_ip: m_ip,

                            s_cb: s_cb,
                            s_ry: s_ry,
                            s_rw: s_rw,
                            s_ip: s_ip
                        },
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type: "POST",
                        success: function(response) {
                            if(response.response_package.response_result > 0) {
                                Swal.fire(
                                    "Bonus Harga",
                                    "Bonus berhasil diproses",
                                    "success"
                                ).then((result) => {
                                    tableHarga.ajax.reload();
                                    $("#form-harga").modal("hide");
                                });
                            }
                            else {
                                Swal.fire(
                                    "Bonus Harga",
                                    "Bonus gagal diproses",
                                    "error"
                                ).then((result) => {
                                    //
                                });
                            }
                        },
                        error: function(response) {
                            console.clear();
                            console.log(response);
                        }
                    });

                }
            });
        });
    });
</script>



<div id="form-harga" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Manajemen Harga</h5>
            </div>
            <div class="modal-body">
                <div class="card card-form">
                    <div class="row no-gutters">
                        <div class="col-lg-12 card-body">
                            <div class="form-row">
                                <div class="col-6 col-md-6 form-group">
                                    <label>Produk</label>
                                    <input type="text" id="nama_produk" class="form-control" readonly />
                                </div>
                                <div class="col-6 col-md-6 form-group">
                                    <label>Tanggal</label>
                                    <input type="text" id="tanggal_harga" class="form-control" readonly />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-12">
                                    <table class="table table-bordered largeDataType">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th style="width: 30%;"></th>
                                                <th style="width: 100px">Bonus Cashback</th>
                                                <th style="width: 100px">Bonus Royalti</th>
                                                <th style="width: 100px">Bonus Reward</th>
                                                <th style="width: 100px">Insentif Personal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="width: 30%;">Stokis</td>
                                                <td>
                                                    <input type="text" id="txt_cashback_stokis" class="form-control editorHarga" />
                                                </td>
                                                <td>
                                                    <input type="text" id="txt_royalti_stokis" class="form-control editorHarga" />
                                                </td>
                                                <td>
                                                    <input type="text" id="txt_reward_stokis" class="form-control editorHarga" />
                                                </td>
                                                <td>
                                                    <input type="text" id="txt_insentif_personal_stokis" class="form-control editorHarga" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Member</td>
                                                <td>
                                                    <input type="text" id="txt_cashback_member" class="form-control editorHarga" />
                                                </td>
                                                <td>
                                                    <input type="text" id="txt_royalti_member" class="form-control editorHarga" />
                                                </td>
                                                <td>
                                                    <input type="text" id="txt_reward_member" class="form-control editorHarga" />
                                                </td>
                                                <td>
                                                    <input type="text" id="txt_insentif_personal_member" class="form-control editorHarga" />
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btnProsesHarga">
                    <i class="fa fa-save"></i> Simpan Harga
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>