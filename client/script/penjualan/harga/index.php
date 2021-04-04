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
                        return "<h6 class=\"number_style\" id=\"het_" + row.uid + "\">" + number_format(row.het, 2, ".", ",") + "</h6>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<div class=\"row\">" +
                            "<div class=\"col-lg-2 text-right text-success\">S</div>" +
                            "<div class=\"col-lg-10\">" +
                            "<h6 class=\"number_style text-success\" id=\"hjs_" + row.uid + "\">" + number_format(((row.harga !== undefined && row.harga !== null) ? row.harga.harga_akhir_stokis : 0), 2, ".", ",") + "</h6>" +
                            "</div>" +
                            "<div class=\"col-lg-12\">" +
                            "<hr />" +
                            "</div>" +
                            "<div class=\"col-lg-2 text-right text-info\">M</div>" +
                            "<div class=\"col-lg-10\">" +
                            "<h6 class=\"number_style text-info\" id=\"hjm_" + row.uid + "\">" + number_format(((row.harga !== undefined && row.harga !== null) ? row.harga.harga_akhir_member : 0), 2, ".", ",") + "</h6>" +
                            "</div>" +
                            "</div>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<div class=\"row\">" +
                            "<div class=\"col-lg-2 text-right text-success\">S</div>" +
                            "<div class=\"col-lg-10\">" +
                            "<h6 class=\"number_style text-success\" id=\"dts_" + row.uid + "\">" + ((row.harga !== undefined && row.harga !== null) ? row.harga.discount_type_stokis : "P") + "</h6>" +
                            "</div>" +
                            "<div class=\"col-lg-12\">" +
                            "<hr />" +
                            "</div>" +
                            "<div class=\"col-lg-2 text-right text-info\">M</div>" +
                            "<div class=\"col-lg-10\">" +
                            "<h6 class=\"number_style text-info\" id=\"dtm_" + row.uid + "\">" + ((row.harga !== undefined && row.harga !== null) ? row.harga.discount_type_member : "P") + "</h6>" +
                            "</div>" +
                            "</div>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<div class=\"row\">" +
                            "<div class=\"col-lg-2 text-right text-success\">S</div>" +
                            "<div class=\"col-lg-10\">" +
                            "<h6 class=\"number_style text-success\" id=\"ds_" + row.uid + "\">" + number_format(((row.harga !== undefined && row.harga !== null) ? row.harga.discount_stokis : 0), 2, ".", ",") + "</h6>" +
                            "</div>" +
                            "<div class=\"col-lg-12\">" +
                            "<hr />" +
                            "</div>" +
                            "<div class=\"col-lg-2 text-right text-info\">M</div>" +
                            "<div class=\"col-lg-10\">" +
                            "<h6 class=\"number_style text-info\" id=\"dm_" + row.uid + "\">" + number_format(((row.harga !== undefined && row.harga !== null) ? row.harga.discount_member : 0), 2, ".", ",") + "</h6>" +
                            "</div>" +
                            "</div>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<div class=\"row\">" +
                            "<div class=\"col-lg-2 text-right text-success\">S</div>" +
                            "<div class=\"col-lg-10\">" +
                            "<h6 class=\"number_style text-success\" id=\"has_" + row.uid + "\">" + number_format(((row.harga !== undefined && row.harga !== null) ? row.harga.harga_akhir_stokis : 0), 2, ".", ",") + "</h6>" +
                            "</div>" +
                            "<div class=\"col-lg-12\">" +
                            "<hr />" +
                            "</div>" +
                            "<div class=\"col-lg-2 text-right text-info\">M</div>" +
                            "<div class=\"col-lg-10\">" +
                            "<h6 class=\"number_style text-info\" id=\"ham_" + row.uid + "\">" + number_format(((row.harga !== undefined && row.harga !== null) ? row.harga.harga_akhir_member : 0), 2, ".", ",") + "</h6>" +
                            "</div>" +
                            "</div>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<div class=\"row\">" +
                            "<div class=\"col-lg-2 text-right text-success\">S</div>" +
                            "<div class=\"col-lg-10\">" +
                            "<h6 class=\"number_style text-success\" id=\"hscb_" + row.uid + "\">" + number_format(((row.harga !== undefined && row.harga !== null) ? row.harga.stokis_cashback : 0), 2, ".", ",") + "</h6>" +
                            "</div>" +
                            "<div class=\"col-lg-12\">" +
                            "<hr />" +
                            "</div>" +
                            "<div class=\"col-lg-2 text-right text-info\">M</div>" +
                            "<div class=\"col-lg-10\">" +
                            "<h6 class=\"number_style text-info\" id=\"hmcb_" + row.uid + "\">" + number_format(((row.harga !== undefined && row.harga !== null) ? row.harga.member_cashback : 0), 2, ".", ",") + "</h6>" +
                            "</div>" +
                            "</div>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<div class=\"row\">" +
                            "<div class=\"col-lg-2 text-right text-success\">S</div>" +
                            "<div class=\"col-lg-10\">" +
                            "<h6 class=\"number_style text-success\" id=\"hsry_" + row.uid + "\">" + number_format(((row.harga !== undefined && row.harga !== null) ? row.harga.stokis_royalti : 0), 2, ".", ",") + "</h6>" +
                            "</div>" +
                            "<div class=\"col-lg-12\">" +
                            "<hr />" +
                            "</div>" +
                            "<div class=\"col-lg-2 text-right text-info\">M</div>" +
                            "<div class=\"col-lg-10\">" +
                            "<h6 class=\"number_style text-info\" id=\"hmry_" + row.uid + "\">" + number_format(((row.harga !== undefined && row.harga !== null) ? row.harga.member_royalti : 0), 2, ".", ",") + "</h6>" +
                            "</div>" +
                            "</div>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<div class=\"row\">" +
                            "<div class=\"col-lg-2 text-right text-success\">S</div>" +
                            "<div class=\"col-lg-10\">" +
                            "<h6 class=\"number_style text-success\" id=\"hsrw_" + row.uid + "\">" + number_format(((row.harga !== undefined && row.harga !== null) ? row.harga.stokis_reward : 0), 2, ".", ",") + "</h6>" +
                            "</div>" +
                            "<div class=\"col-lg-12\">" +
                            "<hr />" +
                            "</div>" +
                            "<div class=\"col-lg-2 text-right text-info\">M</div>" +
                            "<div class=\"col-lg-10\">" +
                            "<h6 class=\"number_style text-info\" id=\"hmrw_" + row.uid + "\">" + number_format(((row.harga !== undefined && row.harga !== null) ? row.harga.member_reward : 0), 2, ".", ",") + "</h6>" +
                            "</div>" +
                            "</div>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<div class=\"row\">" +
                            "<div class=\"col-lg-2 text-right text-success\">S</div>" +
                            "<div class=\"col-lg-10\">" +
                            "<h6 class=\"number_style text-success\" id=\"hsip_" + row.uid + "\">" + number_format(((row.harga !== undefined && row.harga !== null) ? row.harga.stokis_insentif_personal : 0), 2, ".", ",") + "</h6>" +
                            "</div>" +
                            "<div class=\"col-lg-12\">" +
                            "<hr />" +
                            "</div>" +
                            "<div class=\"col-lg-2 text-right text-info\">M</div>" +
                            "<div class=\"col-lg-10\">" +
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
            $("#het").val($("#het_" + uid).html());

            $("#txt_cashback_member").val($("#hmcb_" + uid).html());
            $("#txt_royalti_member").val($("#hmry_" + uid).html());
            $("#txt_reward_member").val($("#hmrw_" + uid).html());
            $("#txt_insentif_personal_member").val($("#hmip_" + uid).html());

            $("#txt_cashback_stokis").val($("#hscb_" + uid).html());
            $("#txt_royalti_stokis").val($("#hsry_" + uid).html());
            $("#txt_reward_stokis").val($("#hsrw_" + uid).html());
            $("#txt_insentif_personal_stokis").val($("#hsip_" + uid).html());

            $("#txt_harga_jual_stokis").val($("#hjs_" + uid).html());
            $("#txt_tipe_diskon_stokis").val($("#dts_" + uid).html()).select2();
            //$("#txt_tipe_diskon_stokis").trigger("change");
            $("#txt_diskon_stokis").val($("#ds_" + uid).html());
            $("#stokis_harga_akhir").html($("#has_" + uid).html());

            $("#txt_harga_jual_member").val($("#hjm_" + uid).html());
            $("#txt_tipe_diskon_member").val($("#dtm_" + uid).html()).select2();
            //$("#txt_tipe_diskon_member").trigger("change");
            $("#txt_diskon_member").val($("#dm_" + uid).html());
            $("#member_harga_akhir").html($("#ham_" + uid).html());




            $("#form-harga").modal("show");
        });

        $("#txt_harga_jual_member").on("keyup", function () {
            var m_hj = $("#txt_harga_jual_member").inputmask("unmaskedvalue");
            var m_dt = $("#txt_tipe_diskon_member").val();
            var m_d = $("#txt_diskon_member").inputmask("unmaskedvalue");
            var m_ha = 0;
            if(m_dt === "P") {
                m_ha = m_hj - (m_d / 100 * m_hj);
            } else if(m_dt === "N") {
                m_ha = m_hj
            } else {
                m_ha = m_hj - m_d;
            }
            $("#member_harga_akhir").html(number_format(m_ha, 2, ".", ","));
        });

        $("#txt_diskon_member").on("keyup", function () {
            var m_hj = $("#txt_harga_jual_member").inputmask("unmaskedvalue");
            var m_dt = $("#txt_tipe_diskon_member").val();
            var m_d = $("#txt_diskon_member").inputmask("unmaskedvalue");
            var m_ha = 0;
            if(m_dt === "P") {
                m_ha = m_hj - (m_d / 100 * m_hj);
            } else if(m_dt === "N") {
                m_ha = m_hj
            } else {
                m_ha = m_hj - m_d;
            }
            $("#member_harga_akhir").html(number_format(m_ha, 2, ".", ","));
        });

        $("#txt_tipe_diskon_member").on("change", function () {
            var m_hj = $("#txt_harga_jual_member").inputmask("unmaskedvalue");
            var m_dt = $("#txt_tipe_diskon_member").val();
            var m_d = $("#txt_diskon_member").inputmask("unmaskedvalue");
            var m_ha = 0;
            if(m_dt === "P") {
                m_ha = m_hj - (m_d / 100 * m_hj);
            } else if(m_dt === "N") {
                m_ha = m_hj
            } else {
                m_ha = m_hj - m_d;
            }
            $("#member_harga_akhir").html(number_format(m_ha, 2, ".", ","));
        });







        $("#txt_harga_jual_stokis").on("keyup", function () {
            var s_hj = $("#txt_harga_jual_stokis").inputmask("unmaskedvalue");
            var s_dt = $("#txt_tipe_diskon_stokis").val();
            var s_d = $("#txt_diskon_stokis").inputmask("unmaskedvalue");
            var s_ha = 0;
            if(s_dt === "P") {
                s_ha = s_hj - (s_d / 100 * s_hj);
            } else if(s_dt === "N") {
                s_ha = s_hj
            } else {
                s_ha = s_hj - s_d;
            }
            $("#stokis_harga_akhir").html(number_format(s_ha, 2, ".", ","));
        });

        $("#txt_diskon_stokis").on("keyup", function () {
            var s_hj = $("#txt_harga_jual_stokis").inputmask("unmaskedvalue");
            var s_dt = $("#txt_tipe_diskon_stokis").val();
            var s_d = $("#txt_diskon_stokis").inputmask("unmaskedvalue");
            var s_ha = 0;
            if(s_dt === "P") {
                s_ha = s_hj - (s_d / 100 * s_hj);
            } else if(s_dt === "N") {
                s_ha = s_hj
            } else {
                s_ha = s_hj - s_d;
            }
            $("#stokis_harga_akhir").html(number_format(s_ha, 2, ".", ","));
        });

        $("#txt_tipe_diskon_stokis").on("change", function () {
            var s_hj = $("#txt_harga_jual_stokis").inputmask("unmaskedvalue");
            var s_dt = $("#txt_tipe_diskon_stokis").val();
            var s_d = $("#txt_diskon_stokis").inputmask("unmaskedvalue");
            var s_ha = 0;
            if(s_dt === "P") {
                s_ha = s_hj - (s_d / 100 * s_hj);
            } else if(s_dt === "N") {
                s_ha = s_hj
            } else {
                s_ha = s_hj - s_d;
            }
            $("#stokis_harga_akhir").html(number_format(s_ha, 2, ".", ","));
        });

        $("#btnProsesHarga").click(function () {
            var m_cb = $("#txt_cashback_member").inputmask("unmaskedvalue");
            var m_ry = $("#txt_royalti_member").inputmask("unmaskedvalue");
            var m_rw = $("#txt_reward_member").inputmask("unmaskedvalue");
            var m_ip = $("#txt_insentif_personal_member").inputmask("unmaskedvalue");
            var m_hj = $("#txt_harga_jual_member").inputmask("unmaskedvalue");
            var m_dt = $("#txt_tipe_diskon_member").val();
            var m_d = $("#txt_diskon_member").inputmask("unmaskedvalue");
            var m_ha = 0;
            if(m_dt === "P") {
                m_ha = m_hj - (m_d / 100 * m_hj);
            } else if(m_dt === "N") {
                m_ha = m_hj
            } else {
                m_ha = m_hj - m_d;
            }

            var s_cb = $("#txt_cashback_stokis").inputmask("unmaskedvalue");
            var s_ry = $("#txt_royalti_stokis").inputmask("unmaskedvalue");
            var s_rw = $("#txt_reward_stokis").inputmask("unmaskedvalue");
            var s_ip = $("#txt_insentif_personal_stokis").inputmask("unmaskedvalue");
            var s_hj = $("#txt_harga_jual_stokis").inputmask("unmaskedvalue");
            var s_dt = $("#txt_tipe_diskon_stokis").val();
            var s_d = $("#txt_diskon_stokis").inputmask("unmaskedvalue");
            var s_ha = 0;
            if(s_dt === "P") {
                s_ha = s_hj - (s_d / 100 * s_hj);
            } else if(s_dt === "N") {
                s_ha = s_hj
            } else {
                s_ha = s_hj - s_d;
            }



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
                            m_hj: m_hj,
                            m_dt: m_dt,
                            m_d: m_d,
                            m_ha: m_ha,


                            s_cb: s_cb,
                            s_ry: s_ry,
                            s_rw: s_rw,
                            s_ip: s_ip,
                            s_hj: s_hj,
                            s_dt: s_dt,
                            s_d: s_d,
                            s_ha: s_ha
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
                                <div class="col-6 col-md-6 form-group">
                                    <label>HET</label>
                                    <input type="text" id="het" class="form-control" readonly />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">

                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-12">
                                    <table class="table table-bordered largeDataType">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th style="width: 50px;"></th>
                                                <th style="width: 90px">Harga Jual</th>
                                                <th style="width: 90px">Tipe Diskon</th>
                                                <th style="width: 90px">Diskon</th>
                                                <th style="width: 90px">Harga Akhir</th>
                                                <th style="width: 90px">Bonus Cashback</th>
                                                <th style="width: 90px">Bonus Royalti</th>
                                                <th style="width: 90px">Bonus Reward</th>
                                                <th style="width: 90px">Insentif Personal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="width: 10%;">Stokis</td>
                                                <td>
                                                    <input type="text" id="txt_harga_jual_stokis" class="form-control editorHarga" />
                                                </td>
                                                <td>
                                                    <select id="txt_tipe_diskon_stokis" class="form-control editorHarga">
                                                        <option value="P">Percentage</option>
                                                        <option value="A">Amount</option>
                                                        <option selected value="N">Tidak Ada</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" id="txt_diskon_stokis" class="form-control editorHarga" />
                                                </td>
                                                <td class="number_style" id="stokis_harga_akhir"></td>
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
                                                    <input type="text" id="txt_harga_jual_member" class="form-control editorHarga" />
                                                </td>
                                                <td>
                                                    <select id="txt_tipe_diskon_member" class="form-control editorHarga">
                                                        <option value="P">Percentage</option>
                                                        <option value="A">Amount</option>
                                                        <option selected value="N">Tidak Ada</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" id="txt_diskon_member" class="form-control editorHarga" />
                                                </td>
                                                <td class="number_style" id="member_harga_akhir"></td>
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