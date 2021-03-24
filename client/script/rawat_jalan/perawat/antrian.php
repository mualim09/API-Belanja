<script type="text/javascript">
	$(function(){
		var allData = {};
		var uid_antrian = __PAGES__[3];
		var dataPasien = loadPasien(uid_antrian);

		var riwayat_bidan = dataPasien.asesmen_bidan;

		for(var bK in riwayat_bidan) {
		    var tanggal_partus = riwayat_bidan[bK].tanggal_partus;
		    var usia_kehamilan = riwayat_bidan[bK].usia_kehamilan;
            var tempat_partus = riwayat_bidan[bK].tempat_partus;
            var jenis_partus = riwayat_bidan[bK].jenis_partus;
            var penolong = riwayat_bidan[bK].penolong;
            var nifas = riwayat_bidan[bK].nifas;
            var jenkel_anak = riwayat_bidan[bK].jenkel_anak;
            var bb_anak = riwayat_bidan[bK].bb_anak;
            var keadaan_sekarang = riwayat_bidan[bK].keadaan_sekarang;
            var keterangan = riwayat_bidan[bK].keterangan;

            const d = new Date(tanggal_partus);

            $("#riwayat_hamil tbody").append(
                "<tr>" +
                "<td></td>" +
                "<td tanggal=\"" + tanggal_partus + "\">" + d.getDate() + " " + monthNames[d.getMonth()] + " " + d.getFullYear() + "</td>" +
                "<td>" + usia_kehamilan + "</td>" +
                "<td>" + tempat_partus + "</td>" +
                "<td>" + jenis_partus + "</td>" +
                "<td>" + penolong + "</td>" +
                "<td>" + nifas + "</td>" +
                "<td>" + jenkel_anak + "</td>" +
                "<td>" + bb_anak + "</td>" +
                "<td>" + keadaan_sekarang + "</td>" +
                "<td>" + keterangan + "</td>" +
                "<td><button class=\"btn btn-sm btn-danger hapusPartus\"><i class=\"fa fa-ban\"></i></button></td>" +
                "</tr>"
            );
        }

		rebasePartus();

		if(dataPasien.antrian.departemen !== __UIDFISIOTERAPI__) {
            $("#fisioterapi_nav").remove();
        }

        $(".tab-igd").remove();

        /*if(dataPasien.antrian.departemen !== __POLI_IGD__) {
            $(".tab-igd").remove();
        } else {
            //$(".tab-pane").removeClass("active");
            $(".tab-igd:nth-child(1)").addClass("active");
            $("#tab-assesment-awal-igd-1").addClass("active");
            $(".tab-irm").remove();
            $(".tab-biasa").remove();
        }*/



		$(".select2").select2({});

		loadTermSelectBox('riwayat_transfusi_golongan_darah', 4);

		$("input[type=\"radio\"][name=\"rokok_yes\"]").change(function() {
			if($(this).val() == "y") {
				$("#riwayat_merokok").removeAttr("disabled");
			} else {
				$("#riwayat_merokok").attr("disabled", "disabled");
			}
		});

		$("input[type=\"radio\"][name=\"miras_yes\"]").change(function() {
			if($(this).val() == "y") {
				$("#riwayat_miras").removeAttr("disabled");
			} else {
				$("#riwayat_miras").attr("disabled", "disabled");
			}
		});

		$("input[type=\"radio\"][name=\"obt_terlarang_yes\"]").change(function() {
			if($(this).val() == "y") {
				$("#riwayat_obt_terlarang").removeAttr("disabled");
			} else {
				$("#riwayat_obt_terlarang").attr("disabled", "disabled");
			}
		});

		$("#btn_riwayat_hamil").click(function() {
		    var tanggal_partus = $("#tgl_partus").val();
		    var usia_kehamilan = $("#usia_hamil").val();
		    var tempat_partus = $("#tempat_partus").val();
		    var jenis_partus = $("#jenis_partus").val();
		    var penolong = $("#penolong_partus").val();
		    var nifas = $("#nifas").val();
		    var jenkel_anak = $("#jenkel_anak").val();
		    var bb_anak = $("#bb_anak").val();
		    var keadaan_sekarang = $("#keadaan_anak").val();
		    var keterangan = $("#keterangan_anak").val();


		    if(
		        tanggal_partus !== ""
            ) {
		        const d = new Date(tanggal_partus);

                $("#riwayat_hamil tbody").append(
                    "<tr>" +
                    "<td></td>" +
                    "<td tanggal=\"" + tanggal_partus + "\">" + d.getDate() + " " + monthNames[d.getMonth()] + " " + d.getFullYear() + "</td>" +
                    "<td>" + usia_kehamilan + "</td>" +
                    "<td>" + tempat_partus + "</td>" +
                    "<td>" + jenis_partus + "</td>" +
                    "<td>" + penolong + "</td>" +
                    "<td>" + nifas + "</td>" +
                    "<td>" + jenkel_anak + "</td>" +
                    "<td>" + bb_anak + "</td>" +
                    "<td>" + keadaan_sekarang + "</td>" +
                    "<td>" + keterangan + "</td>" +
                    "<td><button class=\"btn btn-sm btn-danger hapusPartus\"><i class=\"fa fa-ban\"></i></button></td>" +
                    "</tr>"
                );

                $("#tgl_partus").val("");
                $("#usia_hamil").val("");
                $("#tempat_partus").val("");
                $("#jenis_partus").val("");
                $("#penolong_partus").val("");
                $("#nifas").val("");
                $("#jenkel_anak").val("");
                $("#bb_anak").val("");
                $("#keadaan_anak").val("");
                $("#keterangan_anak").val("");

                rebasePartus();
            }
        });

		function rebasePartus() {
		    $("#riwayat_hamil tbody tr").each(function(e) {
		        var id = parseInt(e) + 1;
		        $(this).attr("id", "partus_row_" + id);
		        $(this).find("td:eq(0)").html(id);
                $(this).find("td:eq(11) button").attr("id", "hapus_partus_" + id);
            });
        }

        $("body").on("click", ".hapusPartus", function () {
            var id = $(this).attr("id");
            id = id[id.length - 1];
            $("#partus_row_" + id).remove();
            rebasePartus();
        });

		$("#btnSelesai").on('click', function(){
			var btnSelesai = $(this);
			btnSelesai.attr('disabled', 'disabled');

            Swal.fire({
                title: "Simpan Asesmen Rawat?",
                showDenyButton: true,
                type: 'warning',
                confirmButtonText: `Ya`,
                confirmButtonColor: `#1297fb`,
                denyButtonText: `Batal`,
                denyButtonColor: `#ff2a2a`
            }).then((result) => {
                if (result.isConfirmed) {
                    $(".inputan").each(function(){
                        var value = $(this).val();

                        if (value != "" && value != null){
                            $this = $(this);
                            var name = $(this).attr("id");

                            if(name !== undefined) {

                                allData[name] = value;
                            } else {
                                //
                            }
                        }
                    });

                    $("input[type=checkbox]:not(:checked)").each(function(){
                        var name = $(this).attr("id");
                        allData[name] = null;
                    });

                    $("input[type=checkbox]:checked").each(function(){
                        var name = $(this).attr("id");
                        allData[name] = 1;
                    });

                    $("input[type=radio]:checked").each(function(){
                        var value = $(this).val();
                        if (value != ""){
                            var name = $(this).attr("name");
                            allData[name] = value;
                        }
                    });

                    var partusList = [];

                    $("#riwayat_hamil tbody tr").each(function(e) {
                        var tanggal_partus = $(this).find("td:eq(1)").attr("tanggal");
                        var usia_kehamilan = $(this).find("td:eq(2)").html();
                        var tempat_partus = $(this).find("td:eq(3)").html();
                        var jenis_partus = $(this).find("td:eq(4)").html();
                        var penolong = $(this).find("td:eq(5)").html();
                        var nifas = $(this).find("td:eq(6)").html();
                        var jenkel_anak = $(this).find("td:eq(7)").html();
                        var bb_anak = $(this).find("td:eq(8)").html();
                        var keadaan_sekarang = $(this).find("td:eq(9)").html();
                        var keterangan = $(this).find("td:eq(10)").html();

                        partusList.push({
                            tanggal: tanggal_partus,
                            usia: usia_kehamilan,
                            tempat: tempat_partus,
                            jenis: jenis_partus,
                            penolong: penolong,
                            nifas: nifas,
                            jenkel_anak: jenkel_anak,
                            bb_anak: bb_anak,
                            keadaan_sekarang: keadaan_sekarang,
                            keterangan: keterangan
                        });
                    });

                    allData["partus_list"] = partusList;

                    delete allData['riwayat_merokok_option'];
                    delete allData['riwayat_miras_option'];
                    delete allData['riwayat_obt_terlarang_option'];

                    $.ajax({
                        async: false,
                        url: __HOSTAPI__ + "/Asesmen",
                        data: {
                            request : "update_asesmen_rawat",
                            dataAntrian : dataPasien.antrian,
                            dataPasien: dataPasien.pasien,
                            dataObj : allData
                        },
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type: "POST",
                        success: function(response){
                            btnSelesai.removeAttr("disabled");
                            if(response.response_package.response_result > 0) {
                                //
                            } else {
                                //notification ("danger", "Gagal Simpan Data", 3000, "hasil_tambah_dev");
                            }
                            console.clear();
                            console.log(response.response_package);

                            location.href = __HOSTNAME__ + '/rawat_jalan/perawat';
                        },
                        error: function(response) {
                            btnSelesai.removeAttr("disabled");
                            console.log("Error : ");
                            console.log(response);
                        }
                    });
                } else {
                    btnSelesai.removeAttr("disabled");
                }
            });


			// /console.log(dataPasien.antrian);
		});

		$('.numberonly').keypress(function(event){
            if (event.which < 48 || event.which > 57) {
                event.preventDefault();
            }
        });

        $("#program_kb").on('change', function(){
        	let status = $(this).val();

        	disableElementSelectBox("jenis-kb", status);
        });

        $("#ginekologi_status").on('change', function(){
        	let status = $(this).val();
        	disableElementSelectBox("ginekologi", status);
        });

        $("#eliminasi_bak").on('change', function(){
        	let status = $(this).val();
        	disableLainnya("eliminasi_bak_lainnya", status, "Lainnya");
        });

        $("#komunikasi_bicara").on('change', function(){
        	let status = $(this).val();
        	disableLainnya("komunikasi_bicara_lainnya", status, "Lainnya");
        });

        $("#komunikasi_hambatan").on('change', function(){
        	let status = $(this).val();
        	disableLainnya("komunikasi_hambatan_lainnya", status, "Lainnya");
        });

        $("#komunikasi_kebutuhan_belajar").on('change', function(){
        	let status = $(this).val();
        	disableLainnya("komunikasi_kebutuhan_belajar_lainnya", status, "Lainnya");
        }); 

        $("input[name='cara_masuk']").on('change', function(){
        	let value = $(this).val();

        	disableLainnya('cara_masuk_lainnya', value, "Lainnya");
        });

        $("input[name='rujukan']").on('change', function(){
        	let value = $(this).val();

        	disableLainnya('ket_rujukan', value, 1);
        });
        
         $("input[name='kaji_resiko_ke_dokter']").on('change', function(){
        	let value = $(this).val();

        	disableLainnya('kaji_resiko_jam_dokter', value, 1);
        });

		$("input[name='riwayat_merokok_option']").on('change', function(){
        	let value = $(this).val();

        	disableLainnya('riwayat_merokok', value, "y");
        });

		$("input[name='riwayat_miras_option']").on('change', function(){
        	let value = $(this).val();

        	disableLainnya('riwayat_miras', value, "y");
        });

		$("input[name='riwayat_obt_terlarang_option']").on('change', function(){
        	let value = $(this).val();

        	disableLainnya('riwayat_obt_terlarang', value, "y");
        });













        $("#btnTambahTerapi").click(function() {
            initTerapis("ADD")
            return false;
        });

        function initTerapis(mode, data = {}) {
            if(mode === "ADD")
            {
                $("#target-judul-terapis").html("Tambah Terapi");
            } else {
                $("#target-judul-terapis").html("Detail Terapi");
            }

            $("#terapis_form_nama_pasien").html(((dataPasien.pasien.panggilan !== undefined || dataPasien.pasien.nama !== null) ? "" : dataPasien.pasien.panggilan) + " " + dataPasien.pasien.nama + "<span class=\"text-info\">[" + dataPasien.pasien.no_rm + "]</span>");
            $("#terapis_form_alamat_pasien").html(dataPasien.pasien.alamat);
            $("#terapis_form_jk_pasien").html(dataPasien.pasien.jenkel);
            $("#terapis_form_usia_pasien").html(dataPasien.pasien.usia + " tahun");
            $("#terapis_form_penjamin_pasien").html(dataPasien.antrian.nama_penjamin);
            $("#terapis_form_terapis").html(__MY_NAME__);
            $("#terapis_form_tanggal_lahir_pasien").html(dataPasien.pasien.tanggal_lahir);
            $("#terapis_form_telepon_pasien").html(dataPasien.pasien.kontak);
            $("#terapis_form_tanggal").html(__TODAY__);

            $("#form-terapis").modal("show");
        }

        var tableTerapi = $("#table-history-terapi").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            searching: false,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Fisioterapi",
                type: "POST",
                data: function(d) {
                    d.request = "history_terapi";
                    d.pasien = dataPasien.pasien.uid;
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var returnedData = [];
                    if(response === undefined || response.response_package === undefined) {
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
                searchPlaceholder: "Cari Program"
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["autonum"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["autonum"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["autonum"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["autonum"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["autonum"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["autonum"];
                    }
                }
            ]
        });

        $("#pj_pasien").prop("disabled", true).attr({
            "disabled": "disabled"
        });

        $("#btnSimpanTerapi").click(function() {
            Swal.fire({
                title: 'Tambah terapi?',
                showDenyButton: true,
                confirmButtonText: `Ya`,
                denyButtonText: `Batal`,
            }).then((result) => {
                if (result.isConfirmed) {
                    var kunjungan = dataPasien.antrian.kunjungan;
                    var antrian = dataPasien.antrian.uid;
                    var penjamin = dataPasien.antrian.uid_penjamin;
                    var pasien = dataPasien.antrian.uid_pasien;
                    var poli = dataPasien.antrian.departemen;
                    var dokter = dataPasien.antrian.dokter;

                    $.ajax({
                        url:__HOSTAPI__ + "/Fisioterapi",
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        data:{
                            request:'tambah_terapi',
                            kunjungan: kunjungan,
                            antrian: antrian,
                            penjamin: penjamin,
                            pasien: pasien,
                            poli: poli,
                            dokter: dokter
                        },
                        type:"POST",
                        success:function(response) {
                            if(response.response_package.response_result > 0) {
                                //
                            } else {
                                console.log(response);
                            }
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });

                } else if (result.isDenied) {
                    //Swal.fire('Changes are not saved', '', 'info')
                }
            });
            tableTerapi.ajax.reload();
            return false;
        });
	});

	function loadTermSelectBox(selector, id_term, selected = ""){
		$.ajax({
            url:__HOSTAPI__ + "/Terminologi/terminologi-items/" + id_term,
            type: "GET",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                var MetaData = response.response_package.response_data;

                if (MetaData != ""){
                	for(i = 0; i < MetaData.length; i++){
	                    var selection = document.createElement("OPTION");
	                    if(MetaData[i].id == selected) {
                            $(selection).attr("selected", "selected");
                        }

	                    $(selection).attr("value", MetaData[i].id).html(MetaData[i].nama);
	                    $("#" + selector).append(selection);
	                }
                }
                
            },
            error: function(response) {
                console.log(response);
            }
        });
	}

	function disableCheckboxChild(parent, child){
		if (parent.checked == true){
        	$("." + child).removeAttr("disabled");
        } else {
        	$("." + child).val("").attr("disabled",true);
        }
	}

	function disableElementSelectBox(selector, value){
    	let $this = $("." + selector);

    	if (value == 0 || value == ""){
    		$this.attr("disabled",true);
    		
    		if ($this.is(':checkbox')) {
    			$this.prop('checked',false);
    		}
    	} else {
    		$this.removeAttr("disabled");
    	}
	}

	function disableLainnya(child_selector, value, comparison_value){
		let $this = $("." + child_selector);

		if (value == comparison_value){
    		$this.removeAttr("disabled");
    	} else {
    		$this.attr("disabled",true);
    	}
	}

	function loadPasien(params) {
		var MetaData = null;

		if (params != ""){
			$.ajax({
				async: false,
	            url:__HOSTAPI__ + "/Asesmen/asesmen-rawat-detail/" + params,
	            type: "GET",
	            beforeSend: function(request) {
	                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
	            },
	            success: function(response){
	            	if (response.response_package != ""){
	            		MetaData = response.response_package;

                        var uidPasien = "";
		                $.each(MetaData.pasien, function(key, item){
		                    if(key === "uid")
                            {
                                uidPasien = item;
                            }
		                	$("#" + key).html(item).attr("uid_pasien", uidPasien);
		                });

		                $.each(MetaData.antrian, function(key, item){
		                    $("#" + key).val(item);
		                });

		                if (MetaData.antrian.penjamin != <?= json_encode(__UIDPENJAMINBPJS__) ?>) {
		                	$(".rujukan-bpjs").attr("hidden", true);
		                } else {
		                    //
		                }

						if (MetaData.pasien.id_jenkel == 2){
							$(".wanita").attr("hidden",true);
						} else {
							$(".pria").attr("hidden",true);
						}

						if (MetaData.asesmen_rawat != ""){

                            let cara_masuk = $("input[name='cara_masuk']").val();
		                	$.each(MetaData.asesmen_rawat, function(key, item) {
                                $("#" + key).val(item);
                                if(item !== null || item !== "" || item != "") {
                                    $("#" + key).removeAttr("disabled").prop("disabled", false);
                                } else {
                                    disableLainnya('cara_masuk_lainnya', cara_masuk, "Lainnya");
                                }
			                	checkedRadio(key, item);
			                	checkedCheckbox(key, item);
                                if(key == "riwayat_transfusi_golongan_darah") {
                                    loadTermSelectBox("riwayat_transfusi_golongan_darah", 4, item);
                                }
			                });
		                	
		                	let program_kb = $("#program_kb").val();
		                	if (program_kb == 0 || program_kb == "") {
								disableElementSelectBox('jenis-kb', program_kb);	                		
		                	}


		                	//disableLainnya('cara_masuk_lainnya', cara_masuk, "Lainnya");

		                	let rujukan = $("input[name='rujukan']").val();
		                	disableLainnya('ket_rujukan', rujukan, 1);

		                	if ($("#riwayat_keluarga_lainnya").is(':checked')) {
		                		$(".riwayat_keluarga_lainnya_ket").removeAttr("disabled");
		                	}

		                	if ($("#nyeri_lainnya").is(':checked')) {
		                		$(".nyeri_lainnya_ket").removeAttr("disabled");
		                	}
		                	
		                	let bicara = $("#komunikasi_bicara").val();
							disableLainnya('komunikasi_bicara_lainnya', bicara, "Lainnya");	                		
		                	
		                	let hambatan = $("#komunikasi_hambatan").val();		                	
							disableLainnya('komunikasi_hambatan_lainnya', hambatan, "Lainnya");

							let kebutuhan = $("#komunikasi_kebutuhan_belajar").val();		                	
							disableLainnya('komunikasi_kebutuhan_belajar_lainnya', kebutuhan, "Lainnya");

							let kaji_jam_ke_dokter = $("#kaji_resiko_ke_dokter").val();
		                	if (kaji_jam_ke_dokter == 0 || kaji_jam_ke_dokter == ""){
								disableElementSelectBox('kaji_resiko_jam_dokter', program_kb);	                		
		                	}

		                	if ($("#tatalaksana_siapkan_obat").is(':checked')) {
		                		$(".tatalaksana_siapkan_obat_ket").removeAttr("disabled");
		                	}

		                	if ($("#tatalaksana_beri_obat").is(':checked')) {
		                		$(".tatalaksana_beri_obat_ket").removeAttr("disabled");
		                	}

		                	if ($("#tatalaksana_konsul").is(':checked')) {
		                		$(".tatalaksana_konsul_ket").removeAttr("disabled");
		                	}

							if ($("#riwayat_merokok").val() != ""){
								let $this = $("input:radio[name='riwayat_merokok_option']");
								$this.val("y").prop('checked', true);

								$("#riwayat_merokok").removeAttr("disabled");
							}

							if ($("#riwayat_miras").val() != ""){
								let $this = $("input:radio[name='riwayat_miras_option']");
								$this.val("y").prop('checked', true);

								$("#riwayat_miras").removeAttr("disabled");
							}

							if ($("#riwayat_obt_terlarang").val() != ""){
								let $this = $("input:radio[name='riwayat_obt_terlarang_option']");
								$this.val("y").prop('checked', true);

								$("#riwayat_obt_terlarang").removeAttr("disabled");
							}
		                }
	            	}
	            },
	            error: function(response) {
	                console.log(response);
	            }
	        });
		}

		return MetaData;
	}

	function checkedRadio(name, value) {

		var $radios = $('input:radio[name=' + name +']');

		if ($radios != ""){
			if($radios.is(':checked') === false) {
				if (value != null && value != ""){
	       	 		$radios.filter('[value="'+ value +'"]').prop('checked', true);
	    		}
	    	}
		}
	}

	function checkedCheckbox(name, value){
		if (value == 1){
			$('input:checkbox[name='+ name +']').prop('checked', true);
		}
	}
</script>








<div id="form-terapis" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="target-judul-terapis"></h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-group">
                            <div class="card card-body">
                                <div class="d-flex flex-row">
                                    <div class="col-md-2">
                                        <center>
                                            <i class="material-icons icon-muted icon-30pt">account_circle</i>
                                        </center>
                                    </div>
                                    <div class="col-md-10">
                                        <b id="terapis_form_nama_pasien"></b>
                                        <br />
                                        <span id="terapis_form_jk_pasien"></span>
                                        <br />
                                        <span id="terapis_form_tanggal_lahir_pasien"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-body">
                                <div class="d-flex flex-row">
                                    <div class="col-md-12">
                                        <table class="table form-mode">
                                            <tr>
                                                <td>Usia</td>
                                                <td class="wrap_content">:</td>
                                                <td>
                                                    <b id="terapis_form_usia_pasien"></b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Alamat</td>
                                                <td>:</td>
                                                <td>
                                                    <span id="terapis_form_alamat_pasien"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Telepon</td>
                                                <td>:</td>
                                                <td>
                                                    <span id="terapis_form_telepon_pasien"></span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-body">
                                <div class="d-flex flex-row">
                                    <div class="col-md-12">
                                        <b>Penjamin</b>
                                        <h5 id="terapis_form_penjamin_pasien" class="text-success"></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-group">
                            <div class="card card-body">
                                <table class="table largeDataType form-mode">
                                    <!--<tr>
                                        <td class="wrap_content">
                                            Diagnosa Medis
                                        </td>
                                        <td class="wrap_content">:</td>
                                        <td id="terapis_form_diagnosa_medis"></td>
                                    </tr>
                                    <tr>
                                        <td class="wrap_content">
                                            Diagnosa Fungsi
                                        </td>
                                        <td class="wrap_content">:</td>
                                        <td id="terapis_form_diagnosa_fungsi"></td>
                                    </tr>-->
                                    <tr>
                                        <td class="wrap_content">
                                            Tanggal
                                        </td>
                                        <td class="wrap_content">:</td>
                                        <td id="terapis_form_tanggal"></td>
                                    </tr>
                                    <tr>
                                        <td class="wrap_content">
                                            Terapis
                                        </td>
                                        <td class="wrap_content">:</td>
                                        <td id="terapis_form_terapis"></td>
                                    </tr>
                                    <tr>
                                        <td class="wrap_content">
                                            Program Terapi
                                        </td>
                                        <td class="wrap_content">:</td>
                                        <td>
                                            <input type="text" class="form-control" id="terapis_form_program" />
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btnSimpanTerapi">
                    <i class="fa fa-save"></i> Tambah Terapi
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="fa fa-ban"></i> Kembali
                </button>
            </div>
        </div>
    </div>
</div>
