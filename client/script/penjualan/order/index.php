<script type="text/javascript">
    $(function () {
        var orderData = $("#table-order").DataTable({
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
                    d.request = "get_item_back_end";
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
                searchPlaceholder: "Nomor Invoice"
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["autonum"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        var kategoriObat = "";
                        for(var kategoriObatKey in row["kategori_obat"]) {
                            if(row["kategori_obat"][kategoriObatKey].kategori != null) {
                                kategoriObat += "<span style=\"margin: 5px;\" class=\"badge badge-info\">" + row["kategori_obat"][kategoriObatKey].kategori + "</span>";
                            }
                        }

                        return 		"<div class=\"row\">" +
                            "<div class=\"col-md-2\">" +
                            "<center><img style=\"border-radius: 5px;\" src=\"" + __HOST__ + row.image + "\" width=\"60\" height=\"60\" /></center>" +
                            "</div>" +
                            "<div class=\"col-md-10\">" +
                            "<b><i>" + ((row["kode_barang"] == undefined) ? "[KODE_BARANG]" : row["kode_barang"].toUpperCase()) + "</i></b><br />" +
                            "<h5>" + row["nama"].toUpperCase() + "</h5>" +
                            kategoriObat +
                            "</div>" +
                            "</div>";
                    }
                },
                /*{
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span id=\"nama_" + row["uid"] + "\">" + row["kode_barang"].toUpperCase() + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span id=\"nama_" + row["uid"] + "\">" + row["nama"].toUpperCase() + "</span>";
                    }
                },*/
                {
                    "data" : null, render: function(data, type, row, meta) {
                        if(row["kategori"] == undefined) {
                            return "-";
                        } else {
                            return "<span id=\"nama_" + row["uid"] + "\">" + row["kategori"].nama.toUpperCase() + "</span>";
                        }
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<a href=\"" + __HOSTNAME__ + "/master/inventori/edit/" + row["uid"] + "\" class=\"btn btn-info btn-sm\">" +
                            "<i class=\"fa fa-pencil-alt\"></i> Edit" +
                            "</a>" +
                            "<button id=\"gudang_delete_" + row['uid'] + "\" class=\"btn btn-danger btn-sm btn-delete-gudang\">" +
                            "<i class=\"fa fa-trash\"></i> Hapus" +
                            "</button>" +
                            "</div>";
                    }
                }
            ]
        });
    });
</script>