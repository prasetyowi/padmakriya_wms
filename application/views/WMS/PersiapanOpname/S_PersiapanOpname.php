<script type="text/javascript">
	let global_arr_rak = [];
	let globalRak = [];
	let arrNamaUtama = [];
	let asw = "";
	let arrDataRak = [];
	let arrEditDetail = [];
	let arrChecked = [];
	let arrLajurDetailId = [];
	let arrLajurDetailPalletId = [];
	let session_depo = '<?= $this->session->userdata('depo_id'); ?>';
	loadingBeforeReadyPage()
	$(document).ready(
		function() {

			// reset_form();
			select2();
			GetPersiapanOpname();
			get_principle();
			get_data_perusahaan();
			get_data_depo();
			get_data_penanggung_jawab();
			get_tipe_opname();
			getTipeStock();
		}
	);
	let globalIdEdit = "";

	$(document).on("click", "#btnshowtambahdata", function() {

		setTimeout(() => {
			Swal.fire({
				title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
				showConfirmButton: false,
				timer: 500
			});
			$("#divUtama").hide();
			$("#divTambah").show();
		}, 500);
		window.onbeforeunload = function(e) {
			e = e || window.event;

			// For IE and Firefox prior to version 4
			if (e) {
				e.returnValue = 'Any string';
			}

			// For Safari
			return 'Any string';
		};
	})
	$(document).on("click", "#kembali", function() {
		window.onbeforeunload = null
		reset_form();
		setTimeout(() => {
			Swal.fire({
				title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
				showConfirmButton: false,
				timer: 500
			});
			// location.href = "<?= base_url('WMS/KoreksiStokBarangDraft/KoreksiStokBarangDraftMenu') ?>";
			location.reload();
		}, 500);

	})

	// const message = (msg, msgtext, msgtype) => {
	// 	Swal.fire(msg, msgtext, msgtype);
	// }

	// function message_topright(type, msg) {
	// 	const Toast = Swal.mixin({
	// 		toast: true,
	// 		position: "top-end",
	// 		showConfirmButton: false,
	// 		timer: 3000,
	// 		didOpen: (toast) => {
	// 			toast.addEventListener("mouseenter", Swal.stopTimer);
	// 			toast.addEventListener("mouseleave", Swal.resumeTimer);
	// 		},
	// 	});

	// 	Toast.fire({
	// 		icon: type,
	// 		title: msg,
	// 	});
	// }


	function GetPersiapanOpname() {
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/PersiapanOpname/PersiapanOpnameMenu') ?>",
			//data: "Location="+ Location,
			success: function(response) {
				if (response) {
					ChPersiapanOpnameMenu(response);
				}
			}
		});

	}

	function ChPersiapanOpnameMenu() {

	}

	$(document).on('click', '#tambah-data', function() {
		window.onbeforeunload = null;
		// $("#divUtama").hide(500);
		// $("#divTambah").show(1000);

		let txtkddokumen = $('#txtkddokumen').val();
		let filterpenanggungjawab = $('#filterpenanggungjawab').val();
		let filter_date = $('#filter_date').val();
		let filterperusahaan = $('#filterperusahaan').val();
		let filterprinciple = $('#filterprinciple').val();
		let filterjenisstock = $('#filterjenisstock').val();
		let filtertipestockopname = $('#filtertipestockopname').val();
		// let txtfiltertipestockopname = $('#filtertipestockopname option:selected').text();
		let txtfiltertipestockopname = $('#filtertipestockopname option:selected').attr('data-id');
		let filterunitcabang = $('#filterunitcabang').val();
		let txtketerangan = $('#txtketerangan').val();
		let txtstatus = $('#txtstatus').val();
		let sess_who = '<?= $this->session->userdata('pengguna_username'); ?>';
		let tempdepo_detail_id = $('.update_gudang_tujuan_by_id');
		let depo_detail_id = "";
		$.each(tempdepo_detail_id, function() {
			if (this.checked) {
				depo_detail_id = this.getAttribute('data-depo-detail-id');
			}
		})

		let arr_chk = [];
		var checkboxes = $(".cbarea");
		for (var i = 0; i < checkboxes.length; i++) {
			if (checkboxes[i].checked == true /*&& !(checkboxes[i].disabled)*/ ) {
				arr_chk.push({
					rak_lajur_detail_id: checkboxes[i].value,
					principle_id: checkboxes[i].getAttribute('principle-id'),
					client_wms_id: checkboxes[i].getAttribute('client-wms-id')
				});
			}
		}

		let uniqueNames = [];
		$.each(arr_chk, function(i, el) {
			if ($.inArray(el, uniqueNames) === -1) uniqueNames.push(el);
		});

		arrLajurDetailPalletId = [];
		// $.ajax({
		//     url: "<?php echo base_url('WMS/PersiapanOpname/GetLajurDetailId'); ?>",
		//     data: {

		//         id: uniqueNames
		//     },
		//     async: false,
		//     type: "POST",
		//     dataType: "JSON",
		//     success: function(data) {
		//         $.each(data, function(idx, item) {
		//             arrLajurDetailPalletId.push(item.rak_lajur_detail_pallet_id);
		//         })
		//     }
		// });

		if (filterpenanggungjawab == "") {
			message('Error!', 'Penanggung Jawab Belum Dipilih, silahkan pilih terlebih dahulu!', 'error');
			return false;
		}

		if (filter_date == "") {
			message('Error!', 'Tanggal Belum Dipilih, silahkan pilih terlebih dahulu!', 'error');
			return false;
		}


		if (filterjenisstock == "") {
			message('Error!', 'Jenis Stock Kosong, silahkan pilih terlebih dahulu!', 'error');
			return false;
		}

		// if(filterjenisstock !== ""){
		//     if (filterjenisstock !== "Good Stock") {
		//         if (filterperusahaan == "") {
		//             message('Error!', 'Perusahaan Kosong, silahkan pilih terlebih dahulu!', 'error');
		//             return false;
		//         }

		//         if (filterprinciple == "") {
		//             message('Error!', 'Principle Kosong, silahkan pilih terlebih dahulu!', 'error');
		//             return false;
		//         }
		//     }
		// }
		if (filterperusahaan == "") {
			message('Error!', 'Perusahaan Kosong, silahkan pilih terlebih dahulu!', 'error');
			return false;
		}

		if (filterprinciple == "") {
			message('Error!', 'Principle Kosong, silahkan pilih terlebih dahulu!', 'error');
			return false;
		}

		if (filtertipestockopname == "") {
			message('Error!', 'Tipe Stock Kosong, silahkan pilih terlebih dahulu!', 'error');
			return false;
		}
		if (filterunitcabang == "") {
			message('Error!', 'Unit Cabang Kosong, silahkan pilih terlebih dahulu!', 'error');
			return false;
		}
		// if (txtketerangan == "") {
		//     message('Error!', 'Keterangan Kosong, silahkan pilih terlebih dahulu!', 'error');
		//     return false;
		// }
		if (txtstatus == "") {
			message('Error!', 'Status Kosong, silahkan pilih terlebih dahulu!', 'error');
			return false;
		}
		if (uniqueNames == "") {
			message('Error!', 'Detail Opname Kosong, silahkan pilih terlebih dahulu!', 'error');
			return false;
		}
		$.ajax({
			url: "<?php echo base_url('WMS/PersiapanOpname/InsertOpname'); ?>",
			data: {
				// tr_opname_plan_id: tr_opname_plan_id,
				depo_id: filterprinciple,
				depo_detail_id: depo_detail_id,
				tr_opname_plan_kode: txtkddokumen,
				tr_opname_plan_tanggal: filter_date,
				client_wms_id: filterperusahaan,
				karyawan_id_penanggungjawab: filterpenanggungjawab,
				principle_id: filterprinciple,
				tipe_stok: filterjenisstock,
				tipe_opname_id: filtertipestockopname,
				tr_opname_plan_keterangan: txtketerangan,
				tr_opname_plan_status: txtstatus,
				name_tipe_opname: txtfiltertipestockopname,
				// tr_opname_plan_tgl_create :,
				tr_opname_plan_who_create: sess_who,
				arr_chk: uniqueNames
			},
			type: "POST",
			dataType: "JSON",
			beforeSend: function() {
				//Alert memuat data
				Swal.fire({
					title: '<span ><i class="fa fa-spinner fa-spin"></i> Proses Menyimpan Data!</span> ',
					text: 'Mohon tunggu sebentar...',
					icon: 'warning',
					showCancelButton: false,
					showConfirmButton: false,
					allowOutsideClick: false
				})
			},
			success: function(data) {
				if (data == true) {
					message_topright("success", "Data berhasil disimpan");
					setTimeout(() => {
						location.reload();
					}, 500);
				} else {
					message_topright("error", "Data gagal disimpan");
				}
				reset_form();

			}
		});

	})

	function select2() {
		$(".select2").select2({
			width: "100%"
		});
	}
	$("#cbstatus").on("change", function(e) {
		e.target.checked ? $("#txtstatus").val($(this).val()) : $("#txtstatus").val("Draft");
	});
	$("#ecbstatus").on("change", function(e) {
		e.target.checked ? $("#etxtstatus").val($(this).val()) : $("#etxtstatus").val("Draft");
	});

	function getTipeStock() {
		$.ajax({
			url: "<?php echo base_url('WMS/PersiapanOpname/getTipeStock'); ?>",
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				$('#filterjenisstock').empty();
				$('#efilterjenisstock').empty();
				$('#filterjenisstock').append(
					'<option value=""><label name="CAPTION-JENISSTOCK">--Pilih Jenis Stock--</label></option>'
				);
				$('#efilterjenisstock').append(
					'<option value=""><label name="CAPTION-JENISSTOCK">--Pilih Jenis Stock--</label></option>'
				);

				$.each(data, function(i, v) {
					$('#filterjenisstock').append('<option value="' + v.tipe_stock_nama + '">' + v
						.tipe_stock_nama + '</option>');
					$('#efilterjenisstock').append('<option value="' + v.tipe_stock_nama + '">' + v
						.tipe_stock_nama + '</option>');
				});
			}
		});
	}

	function get_data_depo() {
		$.ajax({
			url: "<?php echo base_url('WMS/PersiapanOpname/getDataDepo'); ?>",
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				$('.depo').empty();
				// $('.depo').append('<option value=""><label name="CAPTION-PILIHDEPO">--Pilih Depo--</label></option>');

				$.each(data, function(i, v) {
					$('.depo').append('<option value="' + v.depo_id + '">' + v.depo_nama + '</option>');
				});
			}
		});
	}

	function get_tipe_opname() {
		$.ajax({
			url: "<?php echo base_url('WMS/PersiapanOpname/get_tipe_opname'); ?>",
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				$('#filtertipestockopname').empty();
				$('#filtertipestockopname').append(
					'<option value=""><label name="CAPTION-TIPEOPNAME">--Tipe Opname--</label></option>');
				$('#efiltertipestockopname').empty();
				$('#efiltertipestockopname').append(
					'<option value=""><label name="CAPTION-TIPEOPNAME">--Tipe Opname--</label></option>');

				$.each(data, function(i, v) {
					$('#filtertipestockopname').append('<option value="' + v.tipe_opname_id +
						'" data-id="' + v.tipe_opname_preffix + '">' + v.tipe_opname_nama +
						'</option>');
					$('#efiltertipestockopname').append('<option value="' + v.tipe_opname_id +
						'" data-id="' + v.tipe_opname_preffix + '">' + v.tipe_opname_nama +
						'</option>');
				});
			}
		});
	}

	function get_data_penanggung_jawab() {
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/PersiapanOpname/get_data_penanggung_jawab') ?>",
			dataType: "JSON",
			//data: "Location="+ Location,
			success: function(response) {
				if (response) {
					$('#filterpenanggungjawab').append(
						'<option value="">--<label name="CAPTION-CHECKER"></label></option>');
					$('#efilterpenanggungjawab').append(
						'<option value="">--<label name="CAPTION-CHECKER"></label></option>');

					$.each(response, function(i, v) {
						$('#filterpenanggungjawab').append('<option value="' + v.karyawan_id + '">' + v
							.karyawan_nama + '</option>');
						$('#efilterpenanggungjawab').append('<option value="' + v.karyawan_id + '">' + v
							.karyawan_nama + '</option>');
					});
				}
			}
		});

	}

	function get_data_perusahaan() {
		$.ajax({
			url: "<?php echo base_url('WMS/PersiapanOpname/get_data_perusahaan'); ?>",
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				$('.perusahaan').empty();
				$('#efilterperusahaan').empty();
				$('.perusahaan').append('<option value=""><label name="CAPTION-ALL">All</label></option>');
				$('#efilterperusahaan').append(
					'<option value="">--<label name="CAPTION-PERUSAHAAN">Pilih Perusahaan</label>--</option>'
				);

				$.each(data, function(i, v) {
					$('.perusahaan').append('<option value="' + v.client_wms_id + '">' + v
						.client_wms_nama + '</option>');
					$('#efilterperusahaan').append('<option value="' + v.client_wms_id + '">' + v
						.client_wms_nama + '</option>');
				});
			}
		});
	}

	function get_principle() {
		$('.principle').append(`
            <option value="">--<label name="CAPTION-PILIHPRINCIPLE">Pilih Principle</label>--</option>
        `);
		$('#principle_filter_sj').append(`
            <option value="">--<label name="CAPTION-PILIHPRINCIPLE">Pilih Principle</label>--</option>
        `);
	};

	$(".perusahaan").on("change", function() {
		$('#tabledetailareaopname tbody').empty();
		let id = $(this).val();
		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/PersiapanOpname/get_data_principle_by_client_wms_id') ?>",
			data: {
				id: id
			},
			dataType: "json",
			success: function(response) {
				$(".principle").empty();
				let html = "";
				html += '<option value="">All</option>';
				$.each(response, function(i, v) {
					html += "<option value=" + v.id + ">" + v.nama + "</option>";
				});
				$(".principle").append(html);
				$("#efilterprinciple").append(html).trigger('change');
			}
		});
		let dep = $("#filterprinciple").val();
		let client = $("#filterperusahaan").val();

		let tipe_stock = $('#filterjenisstock').val();

		let depo_detail_id = $("#filterprinciple").val();

		if (tipe_stock != "") {
			dep = '';
			client = '';
			depo_detail_id = '';
		}

		requestGetWidthAndLengthDepo(id, depo_detail_id, tipe_stock);

		if (tipe_stock == "" || dep == "" || client == "") {

			$("#shape-placeholder :input[name='tools']").attr('checked', false);
			$(".checkbox-tools").attr('data-active', false);
			$(".checkbox-tools").prop('disabled', true);
		} else {
			GetDataAreaByTipeStockFilter(tipe_stock, dep, client);
		}

		GetDataAreaByTipeStockFilter2(tipe_stock, "", "");
	});


	$(document).on("change", ".principle", function() {
		$('#tabledetailareaopname tbody').empty();
		// let tipe_stock = $(this).val();
		let id = $("#filterperusahaan").val();
		let depo_detail_id = $("#filterprinciple").val();
		let dep = $("#filterprinciple").val();
		let client = $("#filterperusahaan").val();

		let tipe_stock = $('#filterjenisstock').val();

		if (tipe_stock != "") {
			id = '';
			depo_detail_id = '';
			dep = '';
			client = '';
		}

		requestGetWidthAndLengthDepo(id, depo_detail_id, tipe_stock);

		// if (tipe_stock == "" || dep == "" || client == "") {

		// 	$("#shape-placeholder :input[name='tools']").attr('checked', false);
		// 	$(".checkbox-tools").attr('data-active', false);
		// 	$(".checkbox-tools").prop('disabled', true);

		// } else {
		GetDataAreaByTipeStockFilter(tipe_stock, dep, client);
		// }
	});

	$(document).on("change", ".jenisstock", function() {
		let dep = $("#filterprinciple").val();
		let client = $("#filterperusahaan").val();

		let tipe_stock = $(this).val();
		let id = $("#filterperusahaan").val();
		let depo_detail_id = $("#filterprinciple").val();
		$('#tabledetailareaopname tbody').empty();

		if (tipe_stock == 'Good Stock') {
			$("#showFiltered").show();
			dep = '';
			client = '';
			id = '';
			depo_detail_id = '';
		} else {
			$("#showFiltered").hide();
			dep = '';
			client = '';
			id = '';
			depo_detail_id = '';
		}

		requestGetWidthAndLengthDepo(id, depo_detail_id, tipe_stock);

		if (tipe_stock == "") {
			// reset_information_gudang();
			$("#shape-placeholder :input[name='tools']").attr('checked', true);
			$(".checkbox-tools").attr('data-active', false);
			$(".checkbox-tools").prop('disabled', true);

			GetDataAreaByTipeStockFilter(tipe_stock, dep, client);
		} else {
			GetDataAreaByTipeStockFilter(tipe_stock, dep, client);
		}
	});




	function requestGetWidthAndLengthDepo(id, depo_detail_id, tipe_stock) {
		$.ajax({
			url: "<?= base_url('WMS/PersiapanOpname/get_width_and_lenght_by_depo'); ?>",
			type: "POST",
			data: {
				id: session_depo,
			},
			dataType: "JSON",
			async: false,
			success: function(response) {

				ViewMapDepoDetailMenu(response, id, depo_detail_id, tipe_stock);

			}
		});
	}

	function ViewMapDepoDetailMenu(response, id, depo_detail_id, tipe_stock) {

		$("#divDepoDetail").html('');

		var strmenu = '';

		strmenu += '	<div id="Depo_Luas">';
		// strmenu += '	    <div style="width: 100%;border: solid black 1px; position: absolute;left:0;right:0;margin-left:auto;margin-right:auto;height: calc(100% - 100px);overflow-y: scroll;">';
		strmenu +=
			'	    <div style="width: 100%;border: solid black 1px; position: absolute;left:0;right:0;margin-left:auto;margin-right:auto;height: calc(80vh - 120px);;overflow-y: scroll;">';
		strmenu += '		    <div id="shape-placeholder">';
		strmenu += '		    </div>';
		strmenu += '		</div>';
		strmenu += '	</div>';

		$("#divDepoDetail").append(strmenu);


		// $("#shape-placeholder").attr('style', 'width: ' + response.depo_width + 'px; border: solid black 1px; position: absolute;left:0;right:0;margin-left:auto;margin-right:auto;');
		$("#Depo_Luas").attr('style', 'width: ' + response.depo_width + 'px; height: 650px;overflow: hidden;');

		GetDepoDetailMenu(id, depo_detail_id, tipe_stock);
	}

	function GetDepoDetailMenu(id, depo_detail_id, tipe_stock) {
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/PersiapanOpname/GetDepoDetailMenu') ?>",
			data: {
				id: session_depo
			},
			dataType: "JSON",
			async: false,
			success: function(response) {
				if (response) {
					ChDepoDetailMenu(response, id, depo_detail_id, tipe_stock);
				}
			}
		});
	}

	function ChDepoDetailMenu(response, id, depo_detail_id, tipe_stock) {

		if (response.length > 0) {
			$.each(response, function(i, v) {
				let fontstyle = 'font-weight: bold;';
				let borderstyle = 'border: solid black 1px;';
				let css = 'width: ' + v.depo_detail_width + 'px; height: ' + v.depo_detail_length +
					'px; color: black; ' + fontstyle + ' left: ' + v.depo_detail_x + 'px; top: ' + v.depo_detail_y +
					'px; ';

				$("#shape-placeholder").append(`
                    <input class="checkbox-tools update_gudang_tujuan_by_id" type="radio" name="tools" data-depo-detail-id="${v.depo_detail_id}" data-tipe="${tipe_stock}" id="tool-${i}">
                    <label class="for-checkbox-tools" style="${css}" for="tool-${i}">
                        ${v.depo_detail_nama}
                    </label>
                `);
			});

			$("#tipe_filter_gudang").attr('data-dp', depo_detail_id);
			// $(".checkbox-tools").attr('data-active', false);
			// $(".checkbox-tools").prop('disabled', true);


		}
	}
	// /////////////////////////////////////////////////////////////////////////////////////////////

	const GetDataAreaByTipeStockFilter = (tipe_stock, dep, client) => {

		$.ajax({
			url: "<?= base_url('WMS/PersiapanOpname/get_area_rak_gudang'); ?>",
			type: "POST",
			data: {
				tipe_stock: tipe_stock,
				// client_wms: `EFDFAE7C-9276-4B28-8C47-262ED2359E59`,
				// client_wms: $("#filterperusahaan").val(),
				client_wms: client,
				// principle: `446CE5C6-92B6-4A20-82DE-CF1F53EE084E`,
				// principle: $("#filterprinciple").val(),
				principle: dep,
				depo: session_depo
			},
			dataType: "JSON",
			success: function(response) {

				// $("#shape-placeholder :input[name='tools']").each(function(i, v) {
				//     let row = $(this);
				//     let id = row.attr('data-depo-detail-id');
				//     let tipe_stock_attr = row.attr('data-tipe');

				//     if ((dep == id) && (tipe_stock_filter.val() == tipe_stock_attr)) {
				//         row.attr('checked', true);
				//     } else {
				//         row.attr('checked', false);
				//     }
				// });

				if (response.length > 0) {
					$.each($(".checkbox-tools"), function(i, v) {
						let rowCheck = $(this);
						let depo_detail_id = rowCheck.attr('data-depo-detail-id');
						let tipe_stock_attr = rowCheck.attr('data-tipe');
						$.each(response, (index, value) => {
							if (value.depo_detail_id == depo_detail_id) {
								rowCheck.attr('data-active', true);
								rowCheck.prop('disabled', false);
								globalRak.push({
									depo_detail_id: value.depo_detail_id,
									rak_id: value.rak_id
								});

								if ((dep == depo_detail_id) && (tipe_stock ==
										tipe_stock_attr)) {
									rowCheck.prop('checked', true);
								} else {
									rowCheck.prop('checked', false);
								}
								return false;
							} else {
								rowCheck.attr('data-active', false);
								rowCheck.prop('disabled', true);
							}
						});

					});
				} else {
					// reset_information_gudang();
					$("#shape-placeholder :input[name='tools']").attr('checked', false);
					$(".checkbox-tools").attr('data-active', false);
					$(".checkbox-tools").prop('disabled', true);
				}
			}
		});
	}
	/////////////////////////////////////////////////////////////////////////////////////////

	$("#efilterperusahaan").on("change", function() {
		$('#edittabledetailareaopname tbody').empty();
		let id = $(this).val();

		if (id != "") {
			$.ajax({
				type: "POST",
				url: "<?= base_url('WMS/PersiapanOpname/get_data_principle_by_client_wms_id') ?>",
				data: {
					id: id
				},
				dataType: "json",
				async: false,
				success: function(response) {
					$(".principle").empty();
					$("#efilterprinciple").empty();

					let html = "";
					html += '<option value="">All</option>';
					$.each(response, function(i, v) {
						html += "<option value=" + v.id + ">" + v.nama + "</option>";
					});
					$(".principle").append(html);
					$("#efilterprinciple").append(html);
				}
			});
		} else {
			$(".principle").empty();
			$("#efilterprinciple").empty();

			let html = "";
			html += '<option value="">All</option>';
			$(".principle").append(html);
			$("#efilterprinciple").append(html);
		}

		let dep = $("#efilterprinciple").val();
		let client = $("#efilterperusahaan").val();

		let tipe_stock = $('#efilterjenisstock').val();

		let depo_detail_id = $("#efilterprinciple").val();

		if (tipe_stock != "") {
			id = '';
			depo_detail_id = '';
			dep = '';
			client = '';
		}

		requestGetWidthAndLengthDepo2(id, depo_detail_id, tipe_stock);

		if (tipe_stock == "" || dep == "" || client == "") {

			$("#shape-placeholder2 :input[name='tools']").attr('checked', false);
			$(".checkbox-tools").attr('data-active', false);
			$(".checkbox-tools").prop('disabled', true);
		} else {
			if (tipe_stock != "" || dep != "" || client != "") {
				GetDataAreaByTipeStockFilter2(tipe_stock, dep, client);
			}
		}

		GetDataAreaByTipeStockFilter2(tipe_stock, "", id);
	});


	$(document).on("change", "#efilterprinciple", function() {
		$('#edittabledetailareaopname tbody').empty();
		// let tipe_stock = $(this).val();
		let id = $("#efilterperusahaan").val();
		let depo_detail_id = $("#efilterprinciple").val();
		let dep = $("#efilterprinciple").val();
		let client = $("#efilterperusahaan").val();

		let tipe_stock = $('#efilterjenisstock').val();

		if (tipe_stock == 'Good Stock') {
			$("#eshowFiltered").show();
			id = '';
			depo_detail_id = '';
			dep = '';
			client = '';
		} else {
			$("#eshowFiltered").hide();
			id = '';
			depo_detail_id = '';
			dep = '';
			client = '';
		}

		requestGetWidthAndLengthDepo2(id, depo_detail_id, tipe_stock);

		// if (tipe_stock == "" || dep == "" || client == "") {

		// 	$("#shape-placeholder2 :input[name='tools']").attr('checked', false);
		// 	$(".checkbox-tools").attr('data-active', false);
		// 	$(".checkbox-tools").prop('disabled', true);

		// } else {
		// GetDataAreaByTipeStockFilter2(tipe_stock, dep, client);
		// if (tipe_stock != "" || dep != "" || client != "") {
		GetDataAreaByTipeStockFilter2(tipe_stock, dep, client);
		// }
		// }
	});

	$(document).on("change", "#efilterjenisstock", function() {
		$("#shape-placeholder2 :input[name='tools']").attr('checked', true);
		$(".checkbox-tools").attr('data-active', false);
		$(".checkbox-tools").prop('disabled', true);
		let dep = $("#efilterprinciple").val();
		let client = $("#efilterperusahaan").val();

		let tipe_stock = $(this).val();
		let id = $("#efilterperusahaan").val();
		let depo_detail_id = $("#efilterprinciple").val();
		$('#edittabledetailareaopname tbody').empty();
		requestGetWidthAndLengthDepo2(id, depo_detail_id, tipe_stock);

		if (tipe_stock == 'Good Stock') {
			$("#eshowFiltered").show();
			dep = '';
			client = '';
		} else {
			$("#eshowFiltered").hide();
			dep = '';
			client = '';
		}

		// if (tipe_stock == "" || dep == "" || client == "") {
		// 	// reset_information_gudang();
		// 	$("#shape-placeholder2 :input[name='tools']").attr('checked', true);
		// 	$(".checkbox-tools").attr('data-active', false);
		// 	$(".checkbox-tools").prop('disabled', true);

		// 	GetDataAreaByTipeStockFilter2(tipe_stock, dep, client);
		// } else {

		GetDataAreaByTipeStockFilter2(tipe_stock, dep, client);
		// }
	});

	function requestGetWidthAndLengthDepo2(id, depo_detail_id, tipe_stock) {
		$.ajax({
			url: "<?= base_url('WMS/PersiapanOpname/get_width_and_lenght_by_depo'); ?>",
			type: "POST",
			data: {
				id: session_depo,
			},
			dataType: "JSON",
			async: false,
			success: function(response) {

				ViewMapDepoDetailMenu2(response, id, depo_detail_id, tipe_stock);

			}
		});
	}

	function ViewMapDepoDetailMenu2(response, id, depo_detail_id, tipe_stock) {

		$("#divDepoDetail2").html('');

		var strmenu = '';

		strmenu += '	<div id="Depo_Luas2">';
		// strmenu += '	    <div style="width: 100%;border: solid black 1px; position: absolute;left:0;right:0;margin-left:auto;margin-right:auto;height: calc(100% - 100px);overflow-y: scroll;">';
		strmenu +=
			'	    <div style="width: 100%;border: solid black 1px; position: absolute;left:0;right:0;margin-left:auto;margin-right:auto;height: calc(80vh - 120px);;overflow-y: scroll;">';
		strmenu += '		    <div id="shape-placeholder2">';
		strmenu += '		    </div>';
		strmenu += '		</div>';
		strmenu += '	</div>';

		$("#divDepoDetail2").append(strmenu);


		// $("#shape-placeholder").attr('style', 'width: ' + response.depo_width + 'px; border: solid black 1px; position: absolute;left:0;right:0;margin-left:auto;margin-right:auto;');
		$("#Depo_Luas2").attr('style', 'width: ' + response.depo_width + 'px; height: 650px;overflow: hidden;');

		GetDepoDetailMenu2(id, depo_detail_id, tipe_stock);
	}

	function GetDepoDetailMenu2(id, depo_detail_id, tipe_stock) {
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/PersiapanOpname/GetDepoDetailMenu') ?>",
			data: {
				id: session_depo
			},
			dataType: "JSON",
			async: false,
			success: function(response) {
				if (response) {
					ChDepoDetailMenu2(response, id, depo_detail_id, tipe_stock);
				}
			}
		});
	}

	function ChDepoDetailMenu2(response, id, depo_detail_id, tipe_stock) {

		if (response.length > 0) {
			$.each(response, function(i, v) {
				let fontstyle = 'font-weight: bold;';
				let borderstyle = 'border: solid black 1px;';
				let css = 'width: ' + v.depo_detail_width + 'px; height: ' + v.depo_detail_length +
					'px; color: black; ' + fontstyle + ' left: ' + v.depo_detail_x + 'px; top: ' + v.depo_detail_y +
					'px; ';

				$("#shape-placeholder2").append(`
                    <input class="checkbox-tools update_gudang_tujuan_by_id" type="radio" name="tools" data-depo-detail-id="${v.depo_detail_id}" data-tipe="${tipe_stock}" id="tool-${i}">
                    <label class="for-checkbox-tools" style="${css}" for="tool-${i}">
                        ${v.depo_detail_nama}
                    </label>
                `);
			});

			$("#tipe_filter_gudang2").attr('data-dp', depo_detail_id);
			// $(".checkbox-tools").attr('data-active', false);
			// $(".checkbox-tools").prop('disabled', true);


		}
	}

	const GetDataAreaByTipeStockFilter2 = (tipe_stock, dep, client) => {
		$.ajax({
			url: "<?= base_url('WMS/PersiapanOpname/get_area_rak_gudang'); ?>",
			type: "POST",
			data: {
				tipe_stock: tipe_stock,
				// client_wms: `EFDFAE7C-9276-4B28-8C47-262ED2359E59`,
				// client_wms: $("#efilterperusahaan").val(),
				client_wms: client,
				// principle: `446CE5C6-92B6-4A20-82DE-CF1F53EE084E`,
				// principle: $("#efilterprinciple").val(),
				principle: dep,
				depo: session_depo
			},
			dataType: "JSON",
			success: function(response) {
				if (response.length > 0) {
					$.each($(".checkbox-tools"), function(i, v) {
						let rowCheck = $(this);
						let depo_detail_id = rowCheck.attr('data-depo-detail-id');
						let tipe_stock_attr = rowCheck.attr('data-tipe');
						$.each(response, (index, value) => {
							if (value.depo_detail_id == depo_detail_id) {

								rowCheck.attr('data-active', true);
								rowCheck.prop('disabled', false);
								globalRak.push({
									depo_detail_id: value.depo_detail_id,
									rak_id: value.rak_id
								});
								if (arrChecked.length > 0) {
									$.each(arrChecked, function(idx, item) {
										if (item == depo_detail_id) {
											rowCheck.prop('checked', true);
											let tipe_stock = $("#filterjenisstock")
												.val();
											let tipe_stock2 = $(
												"#efilterjenisstock").val();
											let client_id = $("#filterperusahaan")
												.val();
											let client_id2 = $("#efilterperusahaan")
												.val();
											let principle = $("#filterprinciple")
												.val();
											let principle2 = $("#efilterprinciple")
												.val();
											// let cok = tipe_stock == null ? tipe_stock2 : tipe_stock;
											// let depo_detail_id = $(this).attr('data-depo-detail-id');

											// $(".btn_pilih_gudang").attr("data-depo-detail", depo_detail_id);
											const rak_id = globalRak.find((value) =>
												value.depo_detail_id ==
												depo_detail_id);


											// console.log(arrEditDetail);

											$.ajax({
												url: "<?= base_url('WMS/PersiapanOpname/getDataDetailArea'); ?>",
												type: "POST",
												async: false,
												data: {
													depo_detail_id: depo_detail_id,
													rak_id: rak_id.rak_id,
													tipe_stock: tipe_stock ==
														"" ? tipe_stock2 : tipe_stock,
													client_id: client_id ==
														"" ? client_id2 : client_id,
													principle: principle ==
														"" ? principle2 : principle
												},
												dataType: "JSON",
												success: function(data) {
													if (data != null) {
														if (tipe_stock2 ==
															"") {
															GetDataByArea
																(data,
																	rak_id,
																	tipe_stock,
																	'tambah'
																);
														} else if (
															tipe_stock2 !=
															"") {
															GetDataByArea
																(data,
																	rak_id,
																	tipe_stock2,
																	'edit'
																);
														}
													}

												}
											});
											return false;
										} else {
											rowCheck.prop('checked', false);
										}
									})

								}

								// if ((dep == depo_detail_id) && (tipe_stock == tipe_stock_attr)) {
								//     rowCheck.prop('checked', true);
								// } else {
								//     rowCheck.prop('checked', false);
								// }
								return false;
							} else {
								rowCheck.attr('data-active', false);
								rowCheck.prop('disabled', true);
							}
						});

					});
				} else {
					// reset_information_gudang();
					$("#shape-placeholder2 :input[name='tools']").attr('checked', false);
					$(".checkbox-tools").attr('data-active', false);
					$(".checkbox-tools").prop('disabled', true);
				}
			}
		});

		// if (dep == "" || client == "" || tipe_stock == "") {
		//     return false;
		// } else {

		// }
	}


	$(document).on("click", "#tambah-data", function() {
		let area = $('input[name=""]:checked').val();
	})
	$(document).on("click", ".update_gudang_tujuan_by_id", function() {
		$('#tabledetailareaopname tbody').empty();
		$('#edittabledetailareaopname tbody').empty();

		let tipe_stock = $("#filterjenisstock").val();
		let tipe_stock2 = $("#efilterjenisstock").val();
		let client_id = $("#filterperusahaan").val();
		let client_id2 = $("#efilterperusahaan").val();
		let principle = $("#filterprinciple").val();
		let principle2 = $("#efilterprinciple").val();
		// let cok = tipe_stock == null ? tipe_stock2 : tipe_stock;
		let depo_detail_id = $(this).attr('data-depo-detail-id');

		if (tipe_stock != "") {
			if (tipe_stock != "Good Stock") {
				client_id = '';
				principle = '';
			}
		}


		$(".btn_pilih_gudang").attr("data-depo-detail", depo_detail_id);
		const rak_id = globalRak.find((value) => value.depo_detail_id == depo_detail_id);
		$.ajax({
			url: "<?= base_url('WMS/PersiapanOpname/getDataDetailArea'); ?>",
			type: "POST",
			async: false,
			data: {
				rak_id: rak_id.rak_id,
				depo_detail_id: depo_detail_id,
				tipe_stock: tipe_stock == "" ? tipe_stock2 : tipe_stock,
				// client_id: client_id == "" ? client_id2 : client_id,
				// principle: principle == "" ? principle2 : principle
				client_id: "",
				principle: ""
			},
			dataType: "JSON",
			success: function(data) {
				if (data != null) {
					if (tipe_stock2 == "") {
						GetDataByArea(data, rak_id, tipe_stock, 'tambah');
					} else if (tipe_stock2 != "") {
						GetDataByArea(data, rak_id, tipe_stock2, 'edit');
					}
				}

			}
		});


	});

	function GetDataByArea(data, rak_id, tipe_stock, mode) {

		$.ajax({
			url: "<?= base_url('WMS/PersiapanOpname/getNamaUtamaRak'); ?>",
			type: "POST",
			data: {
				rak_id: rak_id.rak_id,
				tipe_stock: tipe_stock
			},
			dataType: "JSON",
			async: false,
			success: function(response) {
				arrNamaUtama = [];
				$.each(response, function(i, o) {
					arrNamaUtama.push(o);
				})
			}
		});

		$.each(data, function(i, q) {
			qq = q.rak_lajur_detail_nama;
		})

		let numallot = 0
		let arrcball = [];
		$('#tabledetailareaopname > tbody').empty();
		$('#edittabledetailareaopname > tbody').empty();
		$.each(arrNamaUtama, function(i, v) {
			var k = 0;
			var random = Math.random() * 10000000000000000000;
			let strcontent = "";
			strcontent = strcontent + '<tr id="tr_' + random + '">';

			var raklajurnama = v.rak_lajur_detail_nama;
			let raklajur = v.rak_lajur_nama;
			var KeyVal = v.rak_lajur_detail_nama + ` ${i}`;
			global_arr_rak.push({
				raklajur
			})
			let rpl = raklajur.replace(/\s/g, '');
			let str_ = "";
			let str = "";


			const existData = data.find((item) => raklajur == item.rak_lajur_nama);
			if (typeof existData !== 'undefined') {
				str +=
					`<input type='checkbox' checked disabled class='cball cball_utama cball-filled check-item' ${raklajur} style='transform: scale(1.5)'  name='chk-data[]' id='chk-utama'  onchange="checkAllSJ(this,'${raklajur}','filled')" value='${raklajur}'/>`;
				strcontent = strcontent + '<input type="hidden" class="hdSKUInduk_ID" value="' + KeyVal + '" />';
				strcontent = strcontent + ` <td onclick="hshow('${rpl}','${i}')"><span>
                                                <button type="button" class="btn btn-primary btn-sm btncoex btncoex_${i}" style="border-radius: 100%;"><i class="fa fa-minus"></i></button>
                                                <label class="control-label btnSKUInduk_Nama"></label></span></td>`;
				strcontent = strcontent + ` <td class="text-center" >${str}</td>`;
				strcontent = strcontent + ` <td bgcolor="#EEEEEE"><strong>${v.rak_lajur_nama}</strong></td>`;
				strcontent = strcontent + ` <td bgcolor="#EEEEEE"><?php echo "&nbsp"; ?></td>`;
				strcontent = strcontent + ` <td bgcolor="#EEEEEE"><?php echo "&nbsp"; ?></td>`;
				strcontent = strcontent + ` <td bgcolor="#EEEEEE"><?php echo "&nbsp"; ?></td>`;
				strcontent = strcontent + ` <td bgcolor="#EEEEEE"><?php echo "&nbsp"; ?></td>`;
				strcontent = strcontent + ` <td bgcolor="#EEEEEE"><?php echo "&nbsp"; ?></td>`;
				strcontent = strcontent + ` <td bgcolor="#EEEEEE"><?php echo "&nbsp"; ?></td>`;
				strcontent = strcontent + '</tr>';
				let qq = '';

				let cbapp = "";
				$.each(data, function(i, q) {


					let text = q.rak_lajur_nama;
					let rpl = text.replace(/\s/g, '');
					asw = text.replace(" ", "");
					let cbk = "";
					let dis = "";
					let jml_pallet = "";

					if (q.status == "not filled") {
						dis = "disabled";
					} else if (q.status == "filled") {
						dis = "";
					} else {
						dis = "";
					}


					// // if (jQuery.inArray(arrTemp, arrEditDetail) !== -1) {
					// // 	cbk += "checked";
					// // } else {
					// // 	cbk += "";
					// // 	console.log('a');
					// // }
					// var arrTemp = arrEditDetail.find((item) => {
					// 	console.log(item);
					// });
					// // item.rak_lajur_detail_id === q.rak_lajur_detail_id && item.principle_id === q.principle_id && item.client_wms_id === q.client_wms_id

					if (arrEditDetail.length > 0) {
						$(arrEditDetail).each(function(i, v) {
							var rak_lajur_detail_id = v.rak_lajur_detail_id;
							var principle_idSKU = v.principle_id;
							var client_wms_idSKU = v.client_wms_id;

							if (rak_lajur_detail_id == q.rak_lajur_detail_id && principle_idSKU == q.principle_id && client_wms_idSKU == q.clientWMSIDSKU) {
								cbapp =
									`<input type='checkbox' class='cball cball-${q.status} cbarea check-item' ${cbk} ${str_} style='transform: scale(1.5)' name_rak='${q.rak_lajur_nama}' principle-id='${q.principle_id}' client-wms-id='${q.clientWMSIDSKU}' name='chk-data-${raklajur}' id='${q.rak_lajur_detail_id}' value="${q.rak_lajur_detail_id}" onchange="checkedRak(this,'${raklajur}','filled')" checked/>`;
								return false;
							} else {
								cbapp =
									`<input type='checkbox' class='cball cball-${q.status} cbarea check-item' ${cbk} ${str_} style='transform: scale(1.5)' name_rak='${q.rak_lajur_nama}' principle-id='${q.principle_id}' client-wms-id='${q.clientWMSIDSKU}' name='chk-data-${raklajur}' id='${q.rak_lajur_detail_id}' value="${q.rak_lajur_detail_id}" onchange="checkedRak(this,'${raklajur}','filled')"/>`;
							}
						});
					} else {
						cbapp =
							`<input type='checkbox' checked disabled class='cball cball-${q.status} cbarea check-item' ${cbk} ${str_} style='transform: scale(1.5)' name_rak='${q.rak_lajur_nama}' principle-id='${q.principle_id}' client-wms-id='${q.clientWMSIDSKU}' name='chk-data-${raklajur}' id='${q.rak_lajur_detail_id}' value="${q.rak_lajur_detail_id}" onchange="checkedRak(this,'${raklajur}','filled')"/>`;
					}

					if (q.jumlah_pallet == "NULL" || q.jumlah_pallet == "null" || q.jumlah_pallet ==
						"Null" || q.jumlah_pallet == null) {
						jml_pallet = "0";
					} else {
						jml_pallet = q.jumlah_pallet;
					}

					// cbapp = `<input type='checkbox' class='cball cball-${q.status} cbarea check-item' ${cbk} ${str_} style='transform: scale(1.5)' name_rak='${q.rak_lajur_nama}' name='chk-data-${raklajur}-${q.status}' id='${q.rak_lajur_detail_pallet_id}' value="${q.rak_lajur_detail_pallet_id}" onchange="checkedRak(this,'${raklajur}','filled')" ${dis}/>`
					// cbapp =
					// 	`<input type='checkbox' class='cball cball-${q.status} cbarea check-item' ${cbk} ${str_} style='transform: scale(1.5)' name_rak='${q.rak_lajur_nama}' principle-id='${q.principle_id}' client-wms-id='${q.clientWMSIDSKU}' name='chk-data-${raklajur}' id='${q.rak_lajur_detail_id}' value="${q.rak_lajur_detail_id}" onchange="checkedRak(this,'${raklajur}','filled')" ${q.bebas == 1 ? 'checked' : ''}/>`
					if (raklajur == q.rak_lajur_nama) {
						strcontent = strcontent + ` <tr class="chk-data-${rpl}">
                                                    <td></td>
                                                    <td>${cbapp}</td>
                                                    <td>${q.client_wms_nama}</td>
                                                    <td>${q.area}</td>
                                                    <td>${q.rak_lajur_detail_nama}</td>
                                                    <td>${q.principle_kode}</td>
                                                    <td>${q.status}</td>
                                                    <td>${q.rak_lajur_detail_tipe_stock}</td>
                                                    <td>${jml_pallet}</td>
                                                </tr>`
					}
				})
			}

			if (mode == 'tambah') {
				$('#tabledetailareaopname > tbody').append(strcontent);
			} else {
				$('#edittabledetailareaopname > tbody').append(strcontent);
			}


			k++;
		})
		var cball = $(".cball").attr('name_rak');
		var cballutama = $(".cball_utama").val();

		$(".cball").each(function() {
			if (this.checked) {
				arrcball.push(this.getAttribute('name_rak'))
			}
		});
		let cballuniq = [];
		$.each(arrcball, function(i, el) {
			if ($.inArray(el, cballuniq) === -1) cballuniq.push(el);
		});
		$(".cball_utama").each(function() {
			if (jQuery.inArray(this.value, cballuniq) != -1) {
				cbk = $(this).prop('checked', true);
			} else {
				$(this).prop('checked', false);
			}
		});

	}

	function hshow(id, i) {
		console.log(id, i);
		var fafa = $(".btncoex").eq(i).html();
		if (fafa == '<i class="fa fa-minus"></i>') {
			$(`.chk-data-${id}`).hide();
			$(`.btncoex_${i}`).html('<i class="fa fa-plus"></i>');
			// $(".btncoex").eq(i).removeClass('<i class="fa fa-minus"></i>').addClass('<i class="fa fa-plus"></i>');
		} else if (fafa == '<i class="fa fa-plus"></i>') {
			$(`.chk-data-${id}`).show();
			$(`.btncoex_${i}`).html('<i class="fa fa-minus"></i>');
		}
	}


	function checkAll(e, sts) {

		// var checkboxes = $(`.cball-${sts}`);
		var checkboxes = $(`.cball`);
		if (e.checked) {
			for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].type == 'checkbox') {
					checkboxes[i].checked = true;
				}
			}
		} else {
			for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].type == 'checkbox') {
					checkboxes[i].checked = false;
				}
			}
		}
	}

	function checkAllSJ(e, nmlajur, sts) {

		var checkboxes = $(`input[name='chk-data-${nmlajur}']`);
		// var checkboxes = $(`input[name='chk-data-${nmlajur}-${sts}']`);
		if (e.checked) {
			for (var i = 0; i < checkboxes.length; i++) {

				if (checkboxes[i].type == 'checkbox') {
					checkboxes[i].checked = true;
				}
			}
		} else {
			for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].type == 'checkbox') {
					checkboxes[i].checked = false;
				}
			}
		}
	}

	function checkedRak(e, nmlajur, sts) {
		var checkboxes = $("input[id='chk-utama']");
		let ivar = $(`input[name='chk-data-${nmlajur}']:checked`).length;
		if (ivar > 0) {
			for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].defaultValue == nmlajur) {
					// checkboxes[i].prop('checked', true);
					checkboxes[i].checked = true;
				}
			}
		} else if (ivar == 0) {
			for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].defaultValue == nmlajur) {
					// checkboxes[i].prop('checked', true);
					checkboxes[i].checked = false;
				}
			}
		}
	}

	function checkAllSJ2(e, nmlajur, sts) {
		var checkboxes = $("input[id='chk-utama']");

		if (e.checked) {
			// checkboxes.prop('checked', true);
			for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].type == 'checkbox' && checkboxes[i].defaultValue == nmlajur) {
					checkboxes[i].checked = true;
				}
			}
		} else {
			// checkboxes.prop('checked', false);
			for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].type == 'checkbox' && checkboxes[i].defaultValue == nmlajur) {
					checkboxes[i].checked = false;
				}
			}
		}
	}
	$('#search').keyup(function() {
		search_table($(this).val());
	});

	function search_table(value) {
		$('#tabledetailareaopname tr').each(function() {
			var found = 'false';
			$(this).each(function() {
				if ($(this).text().toLowerCase().indexOf(value.toLowerCase()) >= 0) {
					found = 'true';
				}
			});
			if (found == 'true') {
				$(this).show();
			} else {
				$(this).hide();
			}
		});
	}
	///////////////////////////////////////////////////////////////////////////////

	$(document).on("click", "#search_filter_data", function() {
		let tahun = $("#vfiltertahun").val();
		let month = $("#vfilterbulan").val();
		let perusahaan = $("#vfilterperusahaan").val();
		let principle = $("#vfilterprinciple").val();
		// let tipe_penerimaan = $("#tipe_penerimaan_filter").val();
		let status = $("#vfiltertipestock").val();

		$("#loadingsearch").show();
		$.ajax({
			url: "<?= base_url('WMS/PersiapanOpname/get_DataPersiapanOpname') ?>",
			type: "POST",
			data: {
				tahun: tahun,
				bulan: month,
				perusahaan: perusahaan,
				principle: principle,
				// tipe_penerimaan: tipe_penerimaan,
				status: status
			},
			async: false,
			dataType: "JSON",
			success: function(data) {
				$("#loadingsearch").hide();
				$("#list-data-form-search").show();
				let no = 1;
				if (data != null) {
					if ($.fn.DataTable.isDataTable('#listpersiapanopname')) {
						$('#listpersiapanopname').DataTable().destroy();
					}
					$('#listpersiapanopname > tbody').empty();
					$.each(data, function(i, v) {
						let str = "";
						let tp = "";
						if (v.tr_opname_plan_status == "In Progress Approval" || v
							.tr_opname_plan_status == "Approved" || v.tr_opname_plan_status ==
							'In Progress' || v.tr_opname_plan_status == "Completed" || v
							.tr_opname_plan_status == "Canceled") {
							str +=
								`<button class='btn btn-primary' title='Detail Data' onclick="ViewDataById('${v.tr_opname_plan_id}')"><i class='fas fa-eye'> </i></button>`;
						}
						if (v.tr_opname_plan_status == "Draft") {
							str +=
								`<button class='btn btn-warning form-control' title='Edit Data' onclick="EditDataById('${v.tr_opname_plan_id}')"><i class='fa fa-edit'></i></button>`;
							str +=
								`<button class='btn btn-danger form-control' title='Hapus Data' name_kodeplan = '${v.tr_opname_plan_kode}' onclick="HapusDataById('${v.tr_opname_plan_id}','${v.tr_opname_plan_kode}')"><i class='fa fa-xmark'></i></button>`;
						}
						if (v.tr_opname_plan_status == "In Progress") {
							str += '<a class="btn btn-success" target="_blank" href="<?php echo site_url('WMS/PersiapanOpname/print_serah_terima?id=') ?>' + this.tr_opname_plan_id + '"><i class="fas fa-print"> </i></a>'

						}
						// let tipeopname = v.tr_opname_plan_kode;
						// const tipeop = tipeopname.split("/");
						// if (tipeop[1] == "FOP") {
						// 	tp += "Full Opname";
						// }
						// if (tipeop[1] == "POP") {
						// 	tp += "Partial Opname";
						// }

						$("#listpersiapanopname > tbody").append(`
                  <tr>
                      <td class="text-center">${no++}</td>
                      <td class="text-center">${v.client_wms_nama == null ? 'All' : v.client_wms_nama}</td>
                      <td class="text-center">${v.principle_kode == null ? 'All' : v.principle_kode}</td>
                      <td class="text-center">${v.tipe_opname_nama}</td>
                      <td class="text-center">${v.tr_opname_plan_kode}</td>
                      <td class="text-center">${v.tgl}</td>
                      <td class="text-center">${v.tr_opname_plan_status}</td>
                      <td>${v.tipe_stok}</td>
                      <td class="text-center">
                          ${str}
                      </td>
                  </tr>
              `);
					});
				} else {
					$("#listpersiapanopname > tbody").html(
						`<tr><td colspan="6" class="text-center text-danger"><label name="CAPTION-DATAKOSONG">Data Kosong</label></td></tr>`
					);
				}

				$('#listpersiapanopname').DataTable({
					lengthMenu: [
						[10, 25, 50, 100, -1],
						[10, 25, 50, 100, 'All']
					],
				});

			}
		});


	});


	function EditDataById(ini) {
		window.onbeforeunload = function(e) {
			e = e || window.event;

			// For IE and Firefox prior to version 4
			if (e) {
				e.returnValue = 'Any string';
			}

			// For Safari
			return 'Any string';
		};
		globalIdEdit = "";
		globalIdEdit = ini;
		$.ajax({
			url: "<?= base_url('WMS/PersiapanOpname/getDataDetailByOpnameID'); ?>",
			type: "POST",
			data: {
				id: globalIdEdit
			},
			dataType: "JSON",
			// async: false,
			success: function(response) {
				arrEditDetail = [];
				$.each(response, function(i, o) {
					arrEditDetail.push({
						rak_lajur_detail_id: o.rak_lajur_detail_id,
						principle_id: o.principle_id,
						client_wms_id: o.client_wms_id,
					});
				})
			}
		});
		$.ajax({
			url: "<?= base_url('WMS/PersiapanOpname/getEditDataById') ?>",
			type: "POST",
			data: {
				id: ini,
			},
			async: false,
			dataType: "JSON",
			success: function(data) {
				let j = "";
				$.each(data, function(i, v) {
					if (v.tr_opname_plan_status == 'Draft') {
						let jenisstock = v.tr_opname_plan_kode;
						const myArray = jenisstock.split("/");

						j = v.principle_id;
						$('#efilterpenanggungjawab').prop('disabled', false);
						$('#efilterperusahaan').prop('disabled', false);
						$('#efilterprinciple').prop('disabled', false);
						// $('#efiltertipestockopname').prop('disabled', false);
						$('#efilterjenisstock').prop('disabled', false);
						$('#filter_date').prop('disabled', false);



						$('#etxtkddokumen').prop('disabled', false);
						$('#etxtketerangan').prop('disabled', false);
						let status = $('#etxtstatus').prop('disabled', false);

					}
					let jenisstock = v.tr_opname_plan_kode;
					const myArray = jenisstock.split("/");

					j = v.principle_id;
					$('#efilterpenanggungjawab').val(v.karyawan_id_penanggungjawab).change();
					// $('#efilterperusahaan').val(v.client_wms_id).trigger('change');
					// $('#efilterprinciple').val(v.principle_id).trigger('change');
					$('#efiltertipestockopname').val(v.tipe_opname_id).trigger('change');
					$('#efilterjenisstock').val(v.tipe_stok).change();
					$('#lastUpdateTgl').val(v.tr_opname_plan_tgl_update);

					if (v.tipe_stok == 'Good Stock') {
						$("#eshowFiltered").show();
						$('#efilterperusahaan').val(v.client_wms_id).trigger('change');
						$('#efilterprinciple').val(v.principle_id).trigger('change');
					} else {
						$("#eshowFiltered").hide();
					}

					$('#efilter_date').val(v.tgl);
					let cek = $('.update_gudang_tujuan_by_id');
					$.each(cek, function() {
						depo_detail_id = this.getAttribute('data-depo-detail-id');
						if (depo_detail_id == v.depo_detail_id) {
							arrChecked.push(v.depo_detail_id);
						}
					})
					// $.ajax({
					//     url: "<?php echo base_url('WMS/PersiapanOpname/GetLajurDetailPaletId'); ?>",
					//     data: {
					//         id: ini
					//     },
					//     async: false,
					//     type: "POST",
					//     dataType: "JSON",
					//     success: function(data) {
					//         $.each(data, function(idx, item) {
					//             arrLajurDetailId.push(item.rak_lajur_detail_id);
					//         })
					//     }
					// });


					$('#etxtkddokumen').val(v.tr_opname_plan_kode);
					$('#etxtketerangan').val(v.tr_opname_plan_keterangan);
					let status = $('#etxtstatus').val(v.tr_opname_plan_status);

				}) //end each


			}
		});
		GetDataAreaByTipeStockFilter2($('#efilterjenisstock').val(), "", $(".perusahaan").val());
		$("#divUtama").hide();
		$("#divEdit").fadeIn(1500);

		if ($("#etxtstatus").val() == "In Progress Approval") {
			$('#ecbstatus').prop('checked', true);
		}
		// let iki = $(".checkbox-tools:checked").length;
		// $.each($(".checkbox-tools:checked"), function() {
		//     let rowCheck = $(this).length;
		//     console.log(rowCheck);
		//     let depo_detail_id = rowCheck.attr('data-depo-detail-id');
		//     let tipe_stock_attr = rowCheck.attr('data-tipe');


		// });

	}

	$(document).on("click", "#edit_data", function() {
		window.onbeforeunload = null
		let etxtkddokumen = $('#etxtkddokumen').val();
		let efilterpenanggungjawab = $('#efilterpenanggungjawab').val();
		let efilter_date = $('#efilter_date').val();
		let efilterperusahaan = $('#efilterperusahaan').val();
		let efilterprinciple = $('#efilterprinciple').val();
		let efilterjenisstock = $('#efilterjenisstock').val();
		let efiltertipestockopname = $('#efiltertipestockopname').val();
		// let txtfiltertipestockopname = $('#filtertipestockopname option:selected').text();
		let etxtfiltertipestockopname = $('#efiltertipestockopname option:selected').attr('data-id');
		let efilterunitcabang = $('#efilterunitcabang').val();
		let etxtketerangan = $('#etxtketerangan').val();
		let etxtstatus = $('#etxtstatus').val();
		let lastUpdateTgl = $('#lastUpdateTgl').val();
		let esess_who = '<?= $this->session->userdata('pengguna_username'); ?>';
		// let edepo_detail_id = $('.update_gudang_tujuan_by_id').attr('data-depo-detail-id');
		let tempdepo_detail_id = $('.update_gudang_tujuan_by_id');
		let edepo_detail_id = "";
		$.each(tempdepo_detail_id, function() {
			if (this.checked) {
				edepo_detail_id = this.getAttribute('data-depo-detail-id');
			}
		})
		let arr_edit = [];
		var checkboxes = $(".cbarea");
		for (var i = 0; i < checkboxes.length; i++) {
			if (checkboxes[i].checked == true /*&& !(checkboxes[i].disabled)*/ ) {
				// arr_edit.push(checkboxes[i].value);
				arr_edit.push({
					rak_lajur_detail_id: checkboxes[i].value,
					principle_id: checkboxes[i].getAttribute('principle-id'),
					client_wms_id: checkboxes[i].getAttribute('client-wms-id')
				});

				// // agar data tidak double
				// var result = arr_edit.find(function(item) {
				// 	return (
				// 		item.rak_lajur_detail_id === checkboxes[i].value &&
				// 		item.principle_id === checkboxes[i].getAttribute('principle-id') &&
				// 		item.client_wms_id === checkboxes[i].getAttribute('client-wms-id')
				// 	);
				// });

				// if (result == 'undefined' || result == undefined) {
				// 	arr_edit.push({
				// 		rak_lajur_detail_id: checkboxes[i].value,
				// 		principle_id: checkboxes[i].getAttribute('principle-id'),
				// 		client_wms_id: checkboxes[i].getAttribute('client-wms-id')
				// 	});
				// }
			}
		}

		let uniqueNames = [];
		$.each(arr_edit, function(i, el) {
			if ($.inArray(el, uniqueNames) === -1) uniqueNames.push(el);
		});

		for (let i = 0; i < arr_edit.length; i++) {


		}
		arrLajurDetailPalletId = [];

		requestAjax("<?php echo base_url('WMS/PersiapanOpname/GetLajurDetailId'); ?>", {
			id: uniqueNames
		}, "POST", "JSON", function(data) {
			$.each(data, function(idx, item) {
				arrLajurDetailPalletId.push(item.rak_lajur_detail_pallet_id);
			})
		})

		console.log(arrLajurDetailPalletId);
		// return false;
		if (efilterpenanggungjawab == "") {
			message('Error!', 'Penanggung Jawab Belum Dipilih, silahkan pilih terlebih dahulu!', 'error');
			return false;
		}

		if (efilter_date == "") {
			message('Error!', 'Tanggal Belum Dipilih, silahkan pilih terlebih dahulu!', 'error');
			return false;
		}


		if (efilterjenisstock == "") {
			message('Error!', 'Jenis Stock Kosong, silahkan pilih terlebih dahulu!', 'error');
			return false;
		}

		// if (efilterjenisstock !== "") {
		//     if (efilterjenisstock !== "Good Stock") {
		//         if (efilterperusahaan == "") {
		//             message('Error!', 'Perusahaan Kosong, silahkan pilih terlebih dahulu!', 'error');
		//             return false;
		//         }

		//         if (efilterprinciple == "") {
		//             message('Error!', 'Principle Kosong, silahkan pilih terlebih dahulu!', 'error');
		//             return false;
		//         }
		//     }
		// }

		if (efiltertipestockopname == "") {
			message('Error!', 'Tipe Stock Kosong, silahkan pilih terlebih dahulu!', 'error');
			return false;
		}
		if (efilterunitcabang == "") {
			message('Error!', 'Unit Cabang Kosong, silahkan pilih terlebih dahulu!', 'error');
			return false;
		}
		if (etxtstatus == "") {
			message('Error!', 'Status Kosong, silahkan pilih terlebih dahulu!', 'error');
			return false;
		}
		if (uniqueNames == "") {
			message('Error!', 'Detail Opname Kosong, silahkan pilih terlebih dahulu!', 'error');
			return false;
		}
		requestAjax("<?php echo base_url('WMS/PersiapanOpname/UpdateOpname'); ?>", {
			tr_opname_plan_id: globalIdEdit,
			depo_id: efilterprinciple,
			depo_detail_id: edepo_detail_id,
			tr_opname_plan_kode: etxtkddokumen,
			tr_opname_plan_tanggal: efilter_date,
			client_wms_id: efilterperusahaan,
			karyawan_id_penanggungjawab: efilterpenanggungjawab,
			principle_id: efilterprinciple,
			tipe_stok: efilterjenisstock,
			tipe_opname_id: efiltertipestockopname,
			tr_opname_plan_keterangan: etxtketerangan,
			tr_opname_plan_status: etxtstatus,
			name_tipe_opname: etxtfiltertipestockopname,
			// tr_opname_plan_tgl_create :,
			tr_opname_plan_who_create: esess_who,
			arr_edit: uniqueNames,
			last_update_tgl: lastUpdateTgl
		}, "POST", "JSON", function(data) {
			if (data == 203) {
				return messageNotSameLastUpdated();
			}

			if (data == true) {
				message_topright("success", "Data berhasil Dirubah");
				setTimeout(() => {
					location.reload();
				}, 500);
			} else {
				message_topright("error", "Data gagal dirubah");
			}
			reset_form();
		})

	});


	function ViewDataById(ini) {
		globalIdEdit = "";
		globalIdEdit = ini;
		$.ajax({
			url: "<?= base_url('WMS/PersiapanOpname/getDataDetailByOpnameID'); ?>",
			type: "POST",
			data: {
				id: globalIdEdit
			},
			dataType: "JSON",
			// async: false,
			success: function(response) {
				arrEditDetail = [];
				$.each(response, function(i, o) {
					arrEditDetail.push(o.rak_lajur_detail_id);
				})
			}
		});
		$.ajax({
			url: "<?= base_url('WMS/PersiapanOpname/getEditDataById') ?>",
			type: "POST",
			data: {
				id: ini,
			},
			async: false,
			dataType: "JSON",
			success: function(data) {
				let j = "";
				$.each(data, function(i, v) {

					let jenisstock = v.tr_opname_plan_kode;
					const myArray = jenisstock.split("/");
					let tgl = v.tr_opname_plan_tgl_create;
					const tglsplit = tgl.split(" ");
					$('#efilter_date').prop('readonly', true);
					$('#ecbstatus').prop('disabled', true);

					$('#efilterpenanggungjawab').val(v.karyawan_id_penanggungjawab).change();
					$('#efiltertipestockopname').val(v.tipe_opname_id).trigger('change');
					$('#efilterjenisstock').val(v.tipe_stok).change();
					if (v.tipe_stok == 'Good Stock') {
						$("#eshowFiltered").show();
						$('#efilterperusahaan').val(v.client_wms_id).trigger('change');
						$('#efilterprinciple').val(v.principle_id).trigger('change');
					} else {
						$("#eshowFiltered").hide();
					}
					$('#efilter_date').val(v.tgl);

					$('#etxtkddokumen').val(v.tr_opname_plan_kode);
					$('#etxtketerangan').val(v.tr_opname_plan_keterangan);
					let status = $('#etxtstatus').val(v.tr_opname_plan_status);
					if (status != "Draft") {
						$('#ecbstatus').prop('checked', true);

					}

				})


			}
		});

		$.ajax({
			url: "<?= base_url('WMS/PersiapanOpname/GetDataViewByTrOpnameID') ?>",
			type: "POST",
			data: {
				id: ini
			},
			dataType: "JSON",
			beforeSend: function() {
				$("#viewtabledetailareaopname > tbody").html(
					`<tr><td colspan="8" class="text-center"><span ><i class="fa fa-spinner fa-spin"></i> Loading...</span></td></tr>`
				);
			},
			success: function(data) {
				$("#showfilterdata").show();
				let no = 1;
				if (data != null) {
					if ($.fn.DataTable.isDataTable('#viewtabledetailareaopname')) {
						$('#viewtabledetailareaopname').DataTable().destroy();
					}
					$('#viewtabledetailareaopname > tbody').empty();
					$.each(data, function(i, v) {
						$("#viewtabledetailareaopname > tbody").append(`
              <tr>
                  <td class="text-center">${no++}</td>
                  <td class="text-center">${v.depo_nama}</td>
                  <td>${v.depo_detail_nama}</td>
                  <td class="text-center">${v.rak_lajur_nama}</td>
                  <td class="text-center">${v.rak_lajur_detail_nama}</td>
                  
                  
              </tr>
          `);
					});
				} else {
					$("#viewtabledetailareaopname > tbody").html(
						`<tr><td colspan="8" class="text-center text-danger"><label name="CAPTION-DATAKOSONG">Data Kosong</label></td></tr>`
					);
				}

				$('#viewtabledetailareaopname').DataTable({
					lengthMenu: [
						[10, 25, 50, 100, -1],
						[10, 25, 50, 100, 'All']
					],
				});
			}
		});

		$("#divUtama").hide();
		$("#x_panel").hide();
		$("#x_panel-view").show();
		// $('#viewtabledetailareaopname').DataTable();

		$("#divEdit").fadeIn(1500);


		if ($("#etxtstatus").val() == "In Progress Approval") {
			$('#ecbstatus').prop('checked', true);
		}

	}

	function HapusDataById(ini, kd) {
		Swal.fire({
			title: "Apakah anda yakin?",
			text: "'" + kd + "'Akan dibatalkan!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Batalkan",
			cancelButtonText: "Tidak, Tutup"
		}).then((result) => {
			if (result.value == true) {
				$.ajax({
					url: "<?= base_url('WMS/PersiapanOpname/batalkan') ?>",
					type: "POST",
					data: {
						id: ini,
					},
					dataType: "JSON",
					success: function(data) {
						if (data == true) {
							message_topright("success", "Berhasil membatalkan");
							setTimeout(() => {
								location.reload();
							}, 200);
						} else {
							message_topright("error", "Gagal membatalkan");
						}
					},
				});
			}
		});
	}

	const reset_form = () => {

		$('#txtkddokumen').html('');
		$('#filterpenanggungjawab').html('');
		$('#filter_date').html('');
		$('#filterperusahaan').html('');
		$('#filterprinciple').html('');
		$('#filterjenisstock').html('');
		$('#filtertipestockopname').html('');
		$('#filtertipestockopname').html('');
		$('#filtertipestockopname').html('');
		$('#filterunitcabang').html('');
		$('#txtketerangan').html('');
		$('#txtstatus').html('');

		//
		$('#etxtkddokumen').html('');
		$('#efilterpenanggungjawab').html('');
		$('#efilter_date').html('');
		$('#efilterperusahaan').html('');
		$('#efilterprinciple').html('');
		$('#efilterjenisstock').html('');
		$('#efiltertipestockopname').html('');
		$('#efiltertipestockopname').html('');
		$('#efiltertipestockopname').html('');
		$('#efilterunitcabang').html('');
		$('#etxtketerangan').html('');
		$('#etxtstatus').html('');
	}

	const EditDataById2 = async (id) => {

		//request to get client wms id, surat jalan id and principle id
		const url = "<?= base_url('WMS/PersiapanOpname/getEditDataById') ?>";

		let postData = new FormData();
		postData.append('id', id);

		const request = await fetch(url, {
			method: 'POST',
			body: postData
		});

		const response = await request.json();

		let arrSjId = []
		$.each(response, function(i, v) {
			arrSjId.push(v.sj_id)
		})

		location.href = "<?= base_url('WMS/PersiapanOpname/add/?id=') ?>" + id + "&opnameid=" + encodeURI(arrSjId) +
			"&client=" + response[0].client_wms_id + "&principle=" + response[0].principle_id;

	}
</script>