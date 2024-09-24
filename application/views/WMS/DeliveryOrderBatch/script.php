<script type="text/javascript">
	var jumlah_sku = 0;
	var layanan = "";
	let arr_sku = [];
	let arr_do = [];
	let arr_do_nama = [];
	let arr_del_do = [];
	let arr_list_do = [];
	var cek_qty = 0;
	var arrlatlong = [];
	var arrSumQtyCheckbox = [];
	var arrSumQtyCheckbox2 = [];
	var arr_fdjr_draft = [];
	var arr_do_draft = [];
	var arr = [];
	var totalPenguranganBerat = 0;
	var totalPenguranganVolume = 0;
	var totalPenambahanBerat = 0;
	var totalPenambahanVolume = 0;
	var table_list_do_batch = '';
	var do_batch_date_filter = '';
	var do_pengiriman_date_filter = '';
	var do_batch_number_filter = '';
	var tipe_delivery_order_filter = '';
	var tipe_pelayanan_filter = '';
	var pengemasan_filter = '';
	var area_filter = '';
	var status_filter = '';

	loadingBeforeReadyPage();

	// const initDataTableKaryawan = () => {
	// 	return $("#data-table-dodraft").DataTable({
	// 		columnDefs: [{
	// 			sortable: false,
	// 			targets: [0]
	// 		}],
	// 		lengthMenu: [
	// 			[-1],
	// 			["All"]
	// 		]
	// 	});
	// }

	$(document).ready(
		function() {
			// var str = "taman gading"
			// var encoded = encodeURIComponent(str);
			// alert(encoded);
			// return false;

			$('.select2').select2({
				width: '100%'
			});

			if ($('#filter-do-batch-date').length > 0) {
				$('#filter-do-batch-date').daterangepicker({
					'applyClass': 'btn-sm btn-success',
					'cancelClass': 'btn-sm btn-default',
					locale: {
						"format": "DD/MM/YYYY",
						applyLabel: 'Apply',
						cancelLabel: 'Cancel',
					},
					'startDate': '<?= date("01-m-Y") ?>',
					'endDate': '<?= date("t-m-Y") ?>'
				});
			}

			if ($('#filter-do-pengiriman-date').length > 0) {
				$('#filter-do-pengiriman-date').daterangepicker({
					'applyClass': 'btn-sm btn-success',
					'cancelClass': 'btn-sm btn-default',
					locale: {
						"format": "DD/MM/YYYY",
						applyLabel: 'Apply',
						cancelLabel: 'Cancel',
					},
					'startDate': '<?= date("01-m-Y") ?>',
					'endDate': '<?= date("t-m-Y") ?>'
				});
			}

			if ($('#filter-delivery_order_batch_tanggal_kirim').length > 0) {
				$('#filter-delivery_order_batch_tanggal_kirim').daterangepicker({
					'applyClass': 'btn-sm btn-success',
					'cancelClass': 'btn-sm btn-default',
					locale: {
						"format": "DD/MM/YYYY",
						applyLabel: 'Apply',
						cancelLabel: 'Cancel',
					},
					'startDate': '<?= date("01-m-Y") ?>',
					'endDate': '<?= date("t-m-Y") ?>'
				});
			}

			if ("<?= $this->uri->segment(4) ?>" == '' || "<?= $this->uri->segment(4) ?>" == 'index') {
				getListDOBatch();
			}

			if ($("#cek_confirm").val() == undefined) {
				$('#table-do-draft > tbody').sortable({
					disabled: false,
					axis: 'y',
					items: "> tr:not(:first)",
					forceHelperSize: true,
					update: function(event, ui) {
						var Newpos = ui.item.index();
						var RefID = $('tr').find('td:first').text().trim();

						arr_list_do = [];

						//alert("Position " + Newpos + "..... RefID: " + RefID);
						var NewPosition = 1;
						$("#table-do-draft tr:has(td)").each(function(idx) {
							var RefID = $(this).find("td:eq(0)").text();
							// var dod_id = $(this).find("td:eq(0) input[type='hidden']").val();
							// var dod_id = $(this).find("td:eq(1)").text().trim();
							// var NewPosition = $("tr").index(this);

							$(this).find("td:eq(0)").text(NewPosition++);


						});


					}
				}).disableSelection();

				// initTableSorter();
			};

			if ("<?= $this->uri->segment(4) ?>" == 'edit' || "<?= $this->uri->segment(4) ?>" == 'detail') {
				GetAreaByTgl();

				resetTablesorter();
			}

			$("#table-do-draft tr:has(td)").each(function(idx) {
				var dod_id = "'" + $(this).find("td:eq(12)").text().trim() + "'";
				arr_do.push(dod_id);
			});

			HeaderReadonly();

			dblclick();

			$('.selectpickersegment1').selectpicker('refresh');
			$('.selectpickersegment2').selectpicker('refresh');
			$('.selectpickersegment3').selectpicker('refresh');
		}
	);
	// $(window).on("load", function() {
	// 	console.log("tess");
	// 	Swal.fire({
	// 		title: '<div class="spinner-border" role="status"></div> <span>Loading ...</span>',
	// 		timerProgressBar: false,
	// 		showConfirmButton: false,
	// 		allowOutsideClick: false,
	// 		timer: 10
	// 	});
	// });

	$('#select_all').click(function() {
		$('#deliveryorderbatch-area_id option').prop('selected', true);
	});

	$('#select-all-do-draft').click(function(event) {
		if (this.checked) {
			// Iterate each checkbox
			$('[name="CheckboxDODraft"]:checkbox').each(function() {
				this.checked = true;
			});
		} else {
			$('[name="CheckboxDODraft"]:checkbox').each(function() {
				this.checked = false;
			});
		}
	});

	$(document).on('click', '[name="CheckboxFDJRDraft"]:checkbox', function() {
		$('[name="CheckboxFDJRDraft"]:checkbox').not(this).prop('checked', false);
	});

	$("#deliveryorderbatch-delivery_order_batch_tanggal_kirim").on("change", function() {
		getAreaFromSimulasi(arr);
		// console.log('wek');
		// var mode = "<?= $this->uri->segment(4) ?>";
		// var tgl_kirim = $('#deliveryorderbatch-delivery_order_batch_tanggal_kirim').val();

		// ddmmyyyy = tgl_kirim.split('/');
		// dd = ddmmyyyy[0];
		// mm = ddmmyyyy[1];
		// yyyy = ddmmyyyy[2];

		// tgl_kirim = yyyy + '-' + mm + '-' + dd;
		// $.ajax({
		// 	type: "POST",
		// 	url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetAreaByTgl') ?>",
		// 	data: {
		// 		tgl_kirim: tgl_kirim,
		// 		do_batch_id: mode == 'create' ? null : $('#do_batch_id').val(),
		// 		mode: mode
		// 	},
		// 	dataType: "JSON",
		// 	success: function(response) {
		// 		$("#deliveryorderbatch-area_id").empty();
		// 		// 	$("#deliveryorderbatch-area_id").append(`
		// 		// <option value="">**<label name="CAPTION-PILIH">Pilih</label>**</option>
		// 		// `);

		// 		if (response.length != 0) {
		// 			$.each(response, function(i, v) {
		// 				$("#deliveryorderbatch-area_id").append(`
		// 		<option value="${v.area_id}">${v.area_nama}</option>
		// 		`)
		// 			})
		// 		}

		// 		$('.selectpicker').selectpicker('refresh');
		// 	}
		// })
	})

	function GetAreaByTgl() {
		var tgl_kirim = $('#deliveryorderbatch-delivery_order_batch_tanggal_kirim').val();
		var area_id = $('#area_id').attr('data-area').split(',');
		var mode = "<?= $this->uri->segment(4) ?>";

		ddmmyyyy = tgl_kirim.split('/');
		dd = ddmmyyyy[0];
		mm = ddmmyyyy[1];
		yyyy = ddmmyyyy[2];

		tgl_kirim = yyyy + '-' + mm + '-' + dd;

		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetAreaByTgl') ?>",
			data: {
				tgl_kirim: tgl_kirim,
				do_batch_id: mode == 'create' ? null : $('#do_batch_id').val(),
				mode: mode
			},
			dataType: "JSON",
			success: function(response) {
				$("#deliveryorderbatch-area_id").empty();
				// 	$("#deliveryorderbatch-area_id").append(`
				// <option value="">**<label name="CAPTION-PILIH">Pilih</label>**</option>
				// `);

				if (response.length != 0) {
					$.each(response, function(i, v) {
						if (area_id.includes(v.area_id)) {
							$("#deliveryorderbatch-area_id").append(`<option value="${v.area_id}" selected>${v.area_nama}</option>`)
						} else {
							$("#deliveryorderbatch-area_id").append(`<option value="${v.area_id}" >${v.area_nama}</option>`)
						}
					})
				}

				$('.selectpicker').selectpicker('refresh');
			}
		})
	}

	$("#btn-optimasi-rute").on("click", function() {
		var arr_cek_do = [];
		var arr_cek_latlong = [];
		$("#table-do-draft tr:has(td)").each(function(idx) {
			// var alamat = $(this).find("td:eq(3)").text().trim();
			var alamat = $(this).find("td:eq(2)").text();
			var latitude = $(this).find("td:eq(7)").text().trim();
			var longitude = $(this).find("td:eq(8)").text().trim();

			arr_cek_do.push(alamat);
			arr_cek_latlong.push(latitude, longitude);
		});

		if (arr_cek_do.length == 0) {
			message("Error!", "Pilih DO terlebih dahulu!", "error");
			return false;
		}

		for (let i = 0; i < arr_cek_latlong.length; i++) {
			var cek_latlong = arr_cek_latlong[i];

			if (cek_latlong == '') {
				message("Error!", "Latitude dan Longitude tidak boleh kosong!", "error");
				return false;
			}

		}

		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/OptimasiRute') ?>",
			data: {
				do_temp_id: $("#do_temp_id").val(),
				depo_id: "<?= $this->session->userdata("depo_id") ?>"
			},
			beforeSend: function() {

				//Alert memuat data
				Swal.fire({
					title: '<span ><i class="fa fa-spinner fa-spin"></i> Memuat data!</span> ',
					text: 'Mohon tunggu sebentar...',
					icon: 'warning',
					showCancelButton: false,
					showConfirmButton: false,
					allowOutsideClick: false
				})
			},
			dataType: "JSON",
			success: function(response) {
				if (response.length > 0) {

					//Alert sukses memuat data
					Swal.fire({
						title: 'Sukses!',
						text: 'Berhasil memuat data.',
						icon: 'success',
						timer: 3000,
						showCancelButton: false,
						showConfirmButton: true
					})

					$("#table-do-draft > tbody").empty();
					// 	$("#table-do-draft > tbody").append(`
					// 	<tr>
					// 		<th class="text-center">Prioritas</th>
					// 		<th class="text-center">Nama</th>
					// 		<th class="text-center">Alamat</th>
					// 		<th class="text-center">Telp</th>
					// 		<th class="text-center">Jumlah DO</th>
					// 		<th class="text-center" hidden>Principle</th>
					// 		<th class="text-center">Kecamatan</th>
					// 		<th class="text-center">Area</th>
					// 		<th class="text-center">Latitude</th>
					// 		<th class="text-center">Longitude</th>
					// 		<th class="text-center">Berat(gram)</th>
					// 		<th class="text-center">Volume(cm<sup>3</sup>)</th>
					// 		<th class="text-center" hidden>Kirim Ulang</th>
					// 		<th class="text-center">Composite</th>
					// 		<th class="text-center">Action</th>
					// 	</tr>
					// `);

					// Get Kapasitas Berat dan Volume Armada
					var beratArmada = $("#deliveryorderbatch-kendaraan_berat_gr_max").val();
					var volumeArmada = $("#deliveryorderbatch-kendaraan_volume_cm3_max").val();

					var no = 0;
					var no2 = 0;
					var checkBerat2 = 0;
					var checkVolume2 = 0;
					var do_temp_id = $("#do_temp_id").val();
					$.each(response, function(i, v) {
						//cek null
						if (v.totalweight == null) {
							v.totalweight = 0;
						}
						if (v.totalvolume == null) {
							v.totalvolume = 0;
						}

						// cek table jika melebihi batas maka row warna merah
						checkBerat2 = parseInt(checkBerat2) + parseInt(v.totalweight);
						checkVolume2 = parseInt(checkVolume2) + parseInt(v.totalvolume);

						if (checkBerat2 > beratArmada || checkVolume2 > volumeArmada) {
							$("#table-do-draft > tbody").append(`
    				<tr id="row-${i}" class="ui-sortable-handle" style="background-color: #FF9999; color: black;">
    					<td class="text-center">
    						<span id="urutan-prioritas-${i}">${++no}</span>
    					</td>
						<input type="hidden" class="latitudelongitude" data-nama="${v.tsp_outlet_nama}" data-alamat="${v.alamat}"  data-lat="${v.tsp_outlet_lat}" data-long="${v.tsp_outlet_long}" data-prioritas="${++no2}"/>
    					<td class="text-center resetPrioritas">${v.tsp_outlet_nama}</td>
    					<td class="text-center resetPrioritas">${v.alamat}</td>
    					<td class="text-center resetPrioritas">${v.client_pt_telepon}</td>
						<td class="text-center resetPrioritas">${v.jumlahdo}</td>
						<td class="text-center resetPrioritas">${v.delivery_order_draft_kirim_kecamatan}</td>
						<td class="text-center resetPrioritas">${v.delivery_order_draft_kirim_area}</td>
    					<td class="text-center dblclkRow resetPrioritas" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_latitude" >${v.tsp_outlet_lat}</td>
    					<td class="text-center dblclkRow resetPrioritas" data-client-pt-id="${v.client_pt_id}"  data-mode="client_pt_longitude" >${v.tsp_outlet_long}</td>
    					<td class="text-center resetPrioritas">${parseInt(Math.round(v.totalweight))}</td>
    					<td class="text-center resetPrioritas">${parseInt(Math.round(v.totalvolume))}</td>
						<td class="text-center resetPrioritas">${v.composite}</td>
    					<td style="display: none">
    						${delivery_order_temp_id}
    					</td>
    					<td class="text-center">
    					<button class="btn btn-success btn-sm btn-view-detail-do" onclick="ViewDetailDO('${v.delivery_order_temp_id}','${v.tsp_outlet_nama}','${v.alamat}')"><i class="fa fa-eye"></i></button> `
								// <button class="btn btn-danger btn-sm btn-delete-do-draft" onclick="DeleteDODraft(this,${i},'${v.delivery_order_temp_id}', '${v.alamat}', '${v.tsp_outlet_nama}')"><i class="fa fa-trash"></i></button>
								`<button class="btn btn-warning btn-sm btn-location" onclick="GetLocation('${v.alamat}')"><i class="fa-sharp fa-solid fa-location-dot"></i></button></td>
						<td class="text-center"><input type="checkbox" style="transform: scale(1.5)" class="ChkboxDelOutlet" data-berat="${parseInt(v.totalweight)}" data-volume="${parseInt(v.totalvolume)}" name="ChkboxDelOutlet" id="ChkboxDelOutlet" onchange="ChkboxDelOutlet(this)" value="${v.delivery_order_temp_id}_${v.alamat}_${v.tsp_outlet_nama}"></td>
						</tr>
    			`);
						} else {
							$("#table-do-draft > tbody").append(`
    				<tr id="row-${i}" class="ui-sortable-handle">
    					<td class="text-center">
    						<span id="urutan-prioritas-${i}">${++no}</span>
    					</td>
    					<input type="hidden" class="latitudelongitude" data-nama="${v.tsp_outlet_nama}" data-alamat="${v.alamat}"  data-lat="${v.tsp_outlet_lat}" data-long="${v.tsp_outlet_long}" data-prioritas="${++no2}"/>
    					<td class="text-center resetPrioritas">${v.tsp_outlet_nama}</td>
    					<td class="text-center resetPrioritas">${v.alamat}</td>
    					<td class="text-center resetPrioritas">${v.client_pt_telepon}</td>
						<td class="text-center resetPrioritas">${v.jumlahdo}</td>
						<td class="text-center resetPrioritas">${v.delivery_order_draft_kirim_kecamatan}</td>
						<td class="text-center resetPrioritas">${v.delivery_order_draft_kirim_area}</td>
    					<td class="text-center resetPrioritas dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_latitude" >${v.tsp_outlet_lat}</td>
    					<td class="text-center resetPrioritas dblclkRow" data-client-pt-id="${v.client_pt_id}"  data-mode="client_pt_longitude" >${v.tsp_outlet_long}</td>
    					<td class="text-center resetPrioritas">${parseInt(Math.round(v.totalweight))}</td>
    					<td class="text-center resetPrioritas">${parseInt(Math.round(v.totalvolume))}</td>
						<td class="text-center resetPrioritas">${v.composite}</td>
    					<td style="display: none">
    						${v.delivery_order_temp_id}
    					</td>
    					<td class="text-center">
    					<button class="btn btn-success btn-sm btn-view-detail-do" onclick="ViewDetailDO('${v.delivery_order_temp_id}','${v.tsp_outlet_nama}','${v.alamat}')"><i class="fa fa-eye"></i></button>` +
								// <button class="btn btn-danger btn-sm btn-delete-do-draft" onclick="DeleteDODraft(this,${i},'${v.delivery_order_temp_id}', '${v.alamat}', '${v.tsp_outlet_nama}')"><i class="fa fa-trash"></i></button>
								`<button class="btn btn-warning btn-sm btn-location" onclick="GetLocation('${v.alamat}')"><i class="fa-sharp fa-solid fa-location-dot"></i></button></td>
						<td class="text-center"><input type="checkbox" style="transform: scale(1.5)" class="ChkboxDelOutlet" data-berat="${parseInt(v.totalweight)}" data-volume="${parseInt(v.totalvolume)}" name="ChkboxDelOutlet" id="ChkboxDelOutlet" onchange="ChkboxDelOutlet(this)" value="${v.delivery_order_temp_id}_${v.alamat}_${v.tsp_outlet_nama}"></td>
						</tr>
    			`);
						}
					})

					dblclick();
				} else {
					//Alert sukses memuat data
					Swal.fire({
						title: 'Gagal!',
						text: 'Gagal memuat data.',
						icon: 'error',
						timer: 3000,
						showCancelButton: false,
						showConfirmButton: true
					})
				}
			}
		})


	})

	// function message(msg, msgtext, msgtype) {
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

	$('#select-dodraft').click(function(event) {
		if (this.checked) {
			// Iterate each checkbox
			$('[name="CheckboxDODraft"]:checkbox').each(function() {
				this.checked = true;
			});
		} else {
			$('[name="CheckboxDODraft"]:checkbox').each(function() {
				this.checked = false;
			});
		}
	});

	$("#btn-choose-dodraft").on("click", function() {
		initDataDoDraft();
	});

	function getStringOfArrayString(data) {
		let string = "";

		data.forEach(datum => string += datum + ", ");

		return string.slice(0, -2);
	}

	// $("#btn-search-data-do-batch").on("click", function() {
	// 	$("#loadingviewdobatch").show();
	// 	$.ajax({
	// 		type: 'POST',
	// 		url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetDeliveryOrderBatchByFilter') ?>",
	// 		data: {
	// 			do_batch_date: $("#filter-do-batch-date").val(),
	// 			do_pengiriman_date: $("#filter-do-pengiriman-date").val(),
	// 			do_batch_number: $("#filter-do-batch-number").val(),
	// 			tipe_delivery_order: $("#filter-tipe-delivery").val(),
	// 			tipe_pelayanan: $("#filter-tipe-pelayanan").children("option").filter(":selected")
	// 				.text(),
	// 			pengemasan: $("#filter-pengemasan").val(),
	// 			area: $("#filter-area").val(),
	// 			status: $("#filter-status").val()
	// 		},
	// 		dataType: "JSON",
	// 		success: function(response) {
	// 			$("#loadingviewdobatch").hide();
	// 			$("#table_list_data_do_batch > tbody").empty();

	// 			if ($.fn.DataTable.isDataTable('#table_list_data_do_batch')) {
	// 				$('#table_list_data_do_batch').DataTable().clear();
	// 				$('#table_list_data_do_batch').DataTable().destroy();
	// 			}

	// 			if (response != 0) {
	// 				$.each(response, function(i, v) {
	// 					if (v.delivery_order_batch_status == "Draft") {
	// 						// <td class="text-center">${getStringOfArrayString(v.area)}</td>
	// 						$("#table_list_data_do_batch > tbody").append(`
	// 						<tr>
	// 							<td class="text-center">${v.delivery_order_batch_tanggal}</td>
	// 							<td class="text-center">${v.delivery_order_batch_tanggal_kirim}</td>
	// 							<td class="text-center">${v.delivery_order_batch_kode}</td>
	// 							<td class="text-center">${v.tipe_delivery_order_alias}</td>
	// 							<td class="text-center">${v.karyawan_nama}</td>
	// 							<td class="text-center"><a class="btn btn-link" onclick="viewArea('${v.area}')">View Area</a></td>
	// 							<td class="text-center">${v.delivery_order_batch_status}</td>
	// 							<td class="text-center">${v.berat_terpakai}</td>
	// 							<td class="text-center">${v.volume_terpakai}</td>
	// 							<td class="text-center">${v.total_outlet}</td>
	// 							<td class="text-center">${v.ritasi}</td>
	// 							<td class="text-center"><button class="btn btn-warning btn-sm" onclick="btnEdit('${v.delivery_order_batch_id}', '${v.delivery_order_batch_update_tgl}')"><i class="fa fa-pencil"></i></button></td>
	// 						<td></td>
	// 							</tr>
	// 					`);

	// 					} else if (v.delivery_order_batch_status == "in progress" || v
	// 						.delivery_order_batch_status == "in transit") {

	// 						$("#table_list_data_do_batch > tbody").append(`
	// 							<tr>
	// 								<td class="text-center">${v.delivery_order_batch_tanggal}</td>
	// 								<td class="text-center">${v.delivery_order_batch_tanggal_kirim}</td>
	// 								<td class="text-center">${v.delivery_order_batch_kode}</td>
	// 								<td class="text-center">${v.tipe_delivery_order_alias}</td>
	// 								<td class="text-center">${v.karyawan_nama}</td>
	// 								<td class="text-center"><a class="btn btn-link" onclick="viewArea('${v.area}')">View Area</a></td>
	// 								<td class="text-center">${v.delivery_order_batch_status}</td>
	// 								<td class="text-center">${v.berat_terpakai}</td>
	// 								<td class="text-center">${v.volume_terpakai}</td>
	// 								<td class="text-center">${v.total_outlet}</td>
	// 								<td class="text-center">${v.ritasi}</td>
	// 								<td class="text-center"><a href="<?= base_url() ?>WMS/Distribusi/DeliveryOrderBatch/detail/?id=${v.delivery_order_batch_id}" class="btn btn-primary btn-sm"><i class="fa fa-search"></i></td>
	// 								<td class="text-center"><button class="btn btn-warning btn-sm" onclick="btnReAssignmentDriver('${v.delivery_order_batch_id}', '${v.delivery_order_batch_update_tgl}')"><i class="fa fa-pencil"></i></button></td>
	// 							</tr>
	// 						`);
	// 					} else {
	// 						$("#table_list_data_do_batch > tbody").append(`
	//                 			<tr>
	//                 				<td class="text-center">${v.delivery_order_batch_tanggal}</td>
	//                 				<td class="text-center">${v.delivery_order_batch_tanggal_kirim}</td>
	//                 				<td class="text-center">${v.delivery_order_batch_kode}</td>
	//                 				<td class="text-center">${v.tipe_delivery_order_alias}</td>
	//                 				<td class="text-center">${v.karyawan_nama}</td>
	// 								<td class="text-center"><a class="btn btn-link" onclick="viewArea('${v.area}')">View Area</a></td>
	//                 				<td class="text-center">${v.delivery_order_batch_status}</td>
	// 								<td class="text-center">${v.berat_terpakai}</td>
	// 								<td class="text-center">${v.volume_terpakai}</td>
	// 								<td class="text-center">${v.total_outlet}</td>
	// 								<td class="text-center">${v.ritasi}</td>
	//                 				<td class="text-center"><a href="<?= base_url() ?>WMS/Distribusi/DeliveryOrderBatch/detail/?id=${v.delivery_order_batch_id}" class="btn btn-primary btn-sm"><i class="fa fa-search"></i><a href="<?= base_url() ?>WMS/Distribusi/DeliveryOrderBatch/print/?id=${v.delivery_order_batch_id}" target="_blank" class="btn btn-success btn-sm" title="Cetak FDJR"><i class="fa fa-book"></i>
	// 								<a href="<?= base_url() ?>WMS/Distribusi/DeliveryOrderBatch/print_by_do/?id=${v.delivery_order_batch_id}" target="_blank" class="btn btn-info btn-sm" title="Cetak By Do"><i class="fa fa-laptop"></i>
	// 								</td>
	// 								<td></td>
	// 								</tr>
	//                 		`);
	// 					}
	// 				});
	// 				$('#table_list_data_do_batch').DataTable({
	// 					'ordering': false
	// 				});

	// 			} else {
	// 				$("#table_list_data_do_batch > tbody").append(`
	// 							<tr>
	// 								<td colspan="13" class="text-danger text-center">
	// 									Data Kosong
	// 								</td>
	// 							</tr>
	// 				`);

	// 			}
	// 		}
	// 	});
	// });

	$("#btn-search-data-do-batch").on("click", function() {
		table_list_do_batch.ajax.reload()
	});

	function getListDOBatch() {
		do_batch_date_filter = $("#filter-do-batch-date").val();
		do_pengiriman_date_filter = $("#filter-do-pengiriman-date").val();
		do_batch_number_filter = $("#filter-do-batch-number").val();
		tipe_delivery_order_filter = $("#filter-tipe-delivery").val();
		tipe_pelayanan_filter = $("#filter-tipe-pelayanan").children("option").filter(":selected").text();
		pengemasan_filter = $("#filter-pengemasan").val();
		area_filter = $("#filter-area").val();
		status_filter = $("#filter-status").val();

		table_list_do_batch = $('#table_list_data_do_batch').DataTable({
			// "scrollX": true,
			'paging': true,
			'searching': true,
			'ordering': true,
			'order': [
				[0, 'asc']
			],
			'lengthMenu': [
				[50, 100, 150, -1], // -1 untuk menunjukkan opsi "All"
				[50, 100, 150, "All"]
			],
			'processing': true,
			'serverSide': true,
			// 'deferLoading': 0,
			'ajax': {
				url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetDeliveryOrderBatchByFilter') ?>",
				type: "POST",
				dataType: "json",
				data: function(data) {
					data.do_batch_date_filter = $("#filter-do-batch-date").val(),
						data.do_pengiriman_date_filter = $("#filter-do-pengiriman-date").val(),
						data.do_batch_number_filter = $("#filter-do-batch-number").val(),
						data.tipe_delivery_order_filter = $("#filter-tipe-delivery").val(),
						data.tipe_pelayanan_filter = $("#filter-tipe-pelayanan").children("option").filter(":selected").text(),
						data.pengemasan_filter = $("#filter-pengemasan").val(),
						data.area_filter = $("#filter-area").val(),
						data.status_filter = $("#filter-status").val()
				},
				beforeSend: function() {
					Swal.fire({
						title: 'Loading ...',
						html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
						timerProgressBar: false,
						showConfirmButton: false
					});
				},
				complete: function() {
					Swal.close();
				},
			},
			// "drawCallback": function(response) {
			// 	var resp = response.json;
			// 	console.log(resp);
			// 	arrayDetail = []
			// 	for (var i = 0; i < resp.data.length; i++) {

			// 		arrayDetail.push(resp.data[i]);
			// 	}

			// 	console.log(arrayDetail);
			// },
			'columns': [{
					data: 'delivery_order_batch_tanggal'
				},
				{
					data: 'delivery_order_batch_tanggal_kirim'
				},
				{
					data: 'karyawan_nama'
				},
				{
					data: 'kendaraan_nopol'
				},
				{
					data: 'delivery_order_batch_h_ritasi'
				},
				{
					render: function(data, type, row, meta) {
						var str = '';
						str += `${formatNumber(row.berat_max)}`

						return str;
					},
					data: 'berat_max'
				},
				{
					render: function(data, type, row, meta) {
						var str = '';
						str += `${formatNumber(row.volume_max)}`

						return str;
					},
					data: 'volume_max'
				},
				{
					render: function(data, type, row, meta) {
						var str = '';
						str += `${formatNumber(row.berat_terpakai)}`

						return str;
					},
					data: 'berat_terpakai'
				},
				{
					render: function(data, type, row, meta) {
						var str = '';
						str += `${formatNumber(row.volume_terpakai)}`

						return str;
					},
					data: 'volume_terpakai'
				},
				{
					render: function(data, type, row, meta) {
						var str = '';
						// str += ((row.berat_terpakai / row.berat_max) * 100).toFixed(2)
						str += (row.berat_terpakai / row.berat_max * 100).toFixed(2);
						// str += ((row.berat_terpakai / row.berat_max * 100 * 100) / 100).toFixed(2);

						return str + '%';
					},
					data: null
				},
				{
					render: function(data, type, row, meta) {
						var str = '';
						str += (row.volume_terpakai / row.volume_max * 100).toFixed(2)

						return str + '%';
					},
					data: null
				},
				{
					data: 'delivery_order_batch_kode'
				},
				{
					data: 'tipe_delivery_order_alias'
				},
				{
					render: function(data, type, row, meta) {
						var str = '';
						str += `<a class="btn-link" onclick="viewArea('${row.area}')">View Area</a>`

						return str;
					},
					data: null
				},
				{
					data: 'total_outlet'
				},
				{
					data: 'delivery_order_batch_status'
				},
				{
					render: function(data, type, row, meta) {
						var str = '';

						if (row.delivery_order_batch_status == "Draft") {
							str += `<button class="btn btn-warning btn-sm" onclick="btnEdit('${row.delivery_order_batch_id}', '${row.delivery_order_batch_update_tgl}')"><i class="fa fa-pencil"></i></button>`
						} else if (row.delivery_order_batch_status == "in progress" || row.delivery_order_batch_status == "in transit") {
							str += `<a href="<?= base_url() ?>WMS/Distribusi/DeliveryOrderBatch/detail/?id=${row.delivery_order_batch_id}" class="btn btn-primary btn-sm"><i class="fa fa-search"></i>`
						} else {
							str += `<a href="<?= base_url() ?>WMS/Distribusi/DeliveryOrderBatch/detail/?id=${row.delivery_order_batch_id}" class="btn btn-primary btn-sm"><i class="fa fa-search"></i><a href="<?= base_url() ?>WMS/Distribusi/DeliveryOrderBatch/print/?id=${row.delivery_order_batch_id}" target="_blank" class="btn btn-success btn-sm" title="Cetak FDJR"><i class="fa fa-book"></i><a href="<?= base_url() ?>WMS/Distribusi/DeliveryOrderBatch/print_by_do/?id=${row.delivery_order_batch_id}" target="_blank" class="btn btn-info btn-sm" title="Cetak By Do"><i class="fa fa-laptop"></i>`
						}

						return str;
					},
					data: null
				},
				{
					render: function(data, type, row, meta) {
						var str = '';

						if (row.delivery_order_batch_status == "Draft") {
							str += ``
						} else if (row.delivery_order_batch_status == "in progress" || row.delivery_order_batch_status == "in transit") {
							str += `<button class="btn btn-warning btn-sm" onclick="btnReAssignmentDriver('${row.delivery_order_batch_id}', '${row.delivery_order_batch_update_tgl}')"><i class="fa fa-pencil"></i></button>`
						} else {
							str += ``
						}

						return str;
					},
					data: null
				},
			],
			"rowsGroup": [
				0,
				1,
				2,
				3,
				4,
				5,
				6,
				7,
				8
			],
			"columnDefs": [{
					targets: 0,
					className: 'text-center'
				},
				{
					targets: 1,
					className: 'text-center'
				},
				{
					targets: 2,
					className: 'text-center'
				},
				{
					targets: 3,
					className: 'text-center'
				},
				{
					targets: 4,
					className: 'text-center'
				},
				{
					targets: 5,
					className: 'text-center'
				},
				{
					targets: 6,
					className: 'text-center'
				},
				{
					targets: 7,
					className: 'text-center'
				},
				{
					targets: 8,
					className: 'text-center'
				},
				{
					targets: 9,
					className: 'text-center'
				},
				{
					targets: 10,
					className: 'text-center'
				},
				{
					targets: 11,
					className: 'text-center',
					searchable: false
				},
				{
					targets: 12,
					className: 'text-center'
				},
				{
					targets: 13,
					className: 'text-center'
				},
				{
					targets: 14,
					className: 'text-center',
					searchable: false
				},
				{
					targets: 15,
					className: 'text-center',
					searchable: false
				},

			],
			initComplete: function() {
				parent_dt = $('#table_list_data_do_batch').closest('.dataTables_wrapper')
				parent_dt.find('.dataTables_filter').css('width', 'auto')
				var input = parent_dt.find('.dataTables_filter input').unbind(),
					self = this.api(),
					$searchButton = $('<button class="btn btn-flat btn-success btn-sm mb-0 mr-0 ml-5 btn-search-dt">')
					.html('<i class="fa fa-fw fa-search">')
					.click(function() {
						self.search(input.val()).draw();
					}),
					$clearButton = $('<button class="btn btn-flat btn-warning btn-sm mb-0 mr-0 ml-5 btn-reset-dt">')
					.html('<i class="fa fa-fw fa-recycle">')
					.click(function() {
						input.val('');
						$searchButton.click();

					})
				parent_dt.find('.dataTables_filter').append($searchButton, $clearButton);
				parent_dt.find('.dataTables_filter input').keypress(function(e) {
					var key = e.which;
					if (key == 13) {
						$searchButton.click();
						return false;
					}
				});
			},
		});
	}

	function viewArea(area) {
		$("#table-area > tbody").empty();

		var area = area.split(",");

		area.sort();

		area.forEach(function(v, i) {
			$("#table-area > tbody").append(`
			<tr>
				<td class="text-center">${i+1}</td>
				<td class="text-center">${v}</td>
			</tr>
			`);

		})

		$("#modalViewArea").modal('show');
	}

	function btnEdit(do_batch_id, do_batch_update_tgl) {
		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/checkLastUpdate') ?>",
			data: {
				do_batch_id: do_batch_id,
				do_batch_update_tgl: do_batch_update_tgl == 'null' ? '' : do_batch_update_tgl
			},
			dataType: "JSON",
			success: function(response) {
				if (response == 1) {
					location.href = "<?= base_url() ?>WMS/Distribusi/DeliveryOrderBatch/edit/?id=" + do_batch_id + "";
				} else {
					return messageNotSameLastUpdated();
				}

			}
		});
	}

	function btnReAssignmentDriver(do_batch_id, do_batch_update_tgl) {
		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/checkLastUpdate') ?>",
			data: {
				do_batch_id: do_batch_id,
				do_batch_update_tgl: do_batch_update_tgl == 'null' ? '' : do_batch_update_tgl
			},
			dataType: "JSON",
			success: function(response) {
				if (response == 1) {
					location.href = "<?= base_url() ?>WMS/Distribusi/DeliveryOrderBatch/reAssigmentDriver/?id=" + do_batch_id + "";
				} else {
					return messageNotSameLastUpdated();
				}

			}
		});
	}

	// Handle click on "Select all" control
	// $(document).on("click", ".btn-choose-do-draft-multi", function() {
	// 	// Get all rows with search applied

	// 	let table = initDataTableKaryawan();


	// 	let rows = table.rows({
	// 		'search': 'applied'
	// 	}).nodes();

	// 	// Check/uncheck checkboxes for all rows in the table
	// 	var valueCheckbox = $('input[name*="CheckboxDODraft"]', rows).map(function() {
	// 		if (this.checked == true && !this.disabled) {
	// 			return this.value;
	// 		}
	// 	});

	// 	console.log(valueCheckbox);
	// });

	$(document).on("click", ".btn-choose-do-draft-multi", function() {
		$('#data-table-dodraft').DataTable().search('').draw();
		// var jumlah = $('input[name="CheckboxDODraft"]').length;

		// tableDODraft.search( '' ).draw();
		// $('#data-table-dodraft input[type=search]').val('').change();
		// return false;
		var jumlah = $('input[name="CheckboxDODraft"]');
		var numberOfChecked = $('input[name="CheckboxDODraft"]:checked').length;
		var numberOfDisabled = $('input[name="CheckboxDODraft"]:disabled').length;
		var no = 0;
		var no2 = 0;
		jumlah_sku = numberOfChecked;

		var arr_check_temp = [];
		// arr_do = [];
		temp_do = '';
		arr_list_do = [];

		if (numberOfChecked != numberOfDisabled) {
			arr_do = [];
			arr_do_nama = [];

			for (var i = 0; i < jumlah.length; i++) {
				// var checked = $('[id="check-dodraft-' + i + '"]:checked').length;
				// var disabled = $('[id="check-dodraft-' + i + '"]:disabled').length;
				// var alamat_do = "'" + $("#check-dodraft-" + i).val() + "'";
				// if (checked > 0 && disabled == 0) {
				// 	arr_do.push(alamat_do);
				// }


				var checked = jumlah[i].checked;
				var disabled = jumlah[i].disabled;
				var alamat_do = "'" + jumlah[i].value + "'";
				var nama_do = "'" + jumlah[i].getAttribute('data-nama') + "'";
				// var alamat_do = "'" + $("#check-dodraft-" + i).val() + "'";


				if (checked > 0 && disabled == 0) {
					arr_do.push(alamat_do);
					arr_do_nama.push(nama_do);
				}
			}

			if (arr_do.length == 0) {
				Swal.fire({
					icon: 'error',
					title: 'Error',
					text: 'Pilih DO!'
				});
				return false;
			}

			// NEW (GET ID DO DRAFT DAN INSERT KE TABLE delivery_order_temp)
			var str_tipe_layanan = $("#deliveryorderbatch-delivery_order_batch_tipe_layanan_id").val();
			const areaSelected = $("#deliveryorderbatch-area_id").children("option").filter(":selected")
			const area = areaSelected.map(function() {
				return this.textContent.trim()
			}).get()
			const arr_tipe_layanan = str_tipe_layanan.split(" || ");
			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetDODraftByAlamat') ?>",
				data: {
					tgl: $("#deliveryorderbatch-delivery_order_batch_tanggal_kirim").val(),
					area: area,
					tipe: $("#deliveryorderbatch-tipe_delivery_order_id").val(),
					tipe_layanan: arr_tipe_layanan[2],
					do_temp_id: $("#do_temp_id").val(),
					do_batch_id: $("#do_batch_id").val(),
					alamat: arr_do,
					nama: arr_do_nama
				},
				dataType: "JSON",
				async: false,
				success: function(response) {
					if (response.length > 0) {
						arr_do = [];
						arr_do_nama = [];
						$("#table-summary-sku > tbody").empty();

						// CEK NO URUT DO
						var arr_check_no_urut = [];
						var checkBerat2 = 0;
						var checkVolume2 = 0;
						$('#table-do-draft tr:has(td)').each(function(idx) {
							var no_urut = $(this).find("td:eq(0)").text().trim();
							checkBerat2 = checkBerat2 + parseInt($(this).find("td:eq(9)")
								.text());
							checkVolume2 = checkVolume2 + parseInt($(this).find("td:eq(10)")
								.text());
							arr_check_no_urut.push(no_urut);
						});

						if (arr_check_no_urut == 0) {
							$("#table-do-draft > tbody").empty();
							// 	$("#table-do-draft > thead").append(`
							// 	<tr>
							// 		<th class="text-center">Prioritas</th>
							// 		<th class="text-center">Nama</th>
							// 		<th class="text-center">Alamat</th>
							// 		<th class="text-center">Telp</th>
							// 		<th class="text-center">Jumlah DO</th>
							// 		<th class="text-center" hidden>Principle</th>
							// 		<th class="text-center">Kecamatan</th>
							// 		<th class="text-center">Area</th>
							// 		<th class="text-center">Latitude</th>
							// 		<th class="text-center">Longitude</th>
							// 		<th class="text-center">Berat(gram)</th>
							// 		<th class="text-center">Volume(cm<sup>3</sup>)</th>
							// 		<th class="text-center" hidden>Kirim Ulang</th>
							// 		<th class="text-center">Composite</th>
							// 		<th class="text-center">Action</th>
							// 		<th class="text-center">Pilih Semua &nbsp; <input type="checkbox" style="transform: scale(1.5)" name="allDelOutlet" id="allDelOutlet" onchange="allDelOutlet(this.checked)" value="1"></th>
							// 	</tr>
							// `);
						}

						// Get Kapasitas Berat dan Volume Armada
						var beratArmada = $("#deliveryorderbatch-kendaraan_berat_gr_max").val();
						var volumeArmada = $("#deliveryorderbatch-kendaraan_volume_cm3_max").val();

						totalPenambahanBerat = 0;
						totalPenambahanVolume = 0;
						$.each(response, function(i, v) {
							//no max
							if (arr_check_no_urut.length > 0) {
								no = Math.max.apply(Math, arr_check_no_urut);
								no2 = Math.max.apply(Math, arr_check_no_urut);
							}
							temp_do = "'" + v.delivery_order_temp_id + "'";
							arr_do.push("'" + v.delivery_order_draft_kirim_alamat + "'");
							arr_do_nama.push("'" + v.delivery_order_draft_kirim_nama + "'");

							// cek table jika melebihi batas maka row warna merah
							checkBerat2 = checkBerat2 + parseInt(v.sku_weight);
							checkVolume2 = checkVolume2 + parseInt(v.sku_volume);

							totalPenambahanBerat = totalPenambahanBerat + parseInt(v.sku_weight);
							totalPenambahanVolume = totalPenambahanVolume + parseInt(v.sku_volume);

							if (checkBerat2 > beratArmada || checkVolume2 > volumeArmada) {
								$("#table-do-draft > tbody").append(`
									<tr id="row-${i}" class="ui-sortable-handle cek" style="background-color: #FF9999; color: black;">
										<td class="text-center">
											<span id="urutan-prioritas-${i}">${++no}</span>
										</td>
										<input type="hidden" class="latitudelongitude" data-nama="${v.delivery_order_draft_kirim_nama}" data-alamat="${v.delivery_order_draft_kirim_alamat}"  data-lat="${v.client_pt_latitude}" data-long="${v.client_pt_longitude}" data-prioritas="${++no2}"/>
										<td class="text-center resetPrioritas">${v.delivery_order_draft_kirim_nama}</td>
										<td class="text-center resetPrioritas">${v.delivery_order_draft_kirim_alamat}</td>
										<td class="text-center resetPrioritas">${v.delivery_order_draft_kirim_telp}</td>
										<td class="text-center resetPrioritas">${v.jumlah_do}</td>
										<td class="text-center resetPrioritas">${v.delivery_order_draft_kirim_kecamatan}</td>
										<td class="text-center resetPrioritas">${v.delivery_order_draft_kirim_area}</td>
										<td class="text-center resetPrioritas dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_latitude" >${v.client_pt_latitude}</td>
										<td class="text-center resetPrioritas dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_longitude" >${v.client_pt_longitude}</td>
										<td class="text-center resetPrioritas">${parseInt(v.sku_weight)}</td>
										<td class="text-center resetPrioritas">${parseInt(v.sku_volume)}</td>
										<td class="text-center resetPrioritas">${v.composite}</td>
										<td style="display: none">
											${v.delivery_order_temp_id}
										</td>
										<td class="text-center">
										<button class="btn  btn-success btn-sm btn-view-detail-do" onclick="ViewDetailDO('${v.delivery_order_temp_id}','${v.delivery_order_draft_kirim_nama}','${v.delivery_order_draft_kirim_alamat}')"><i class="fa fa-eye"></i></button>` +
									// <button class="btn btn-danger btn-sm btn-delete-do-draft" onclick="DeleteDODraft(this,${i},'${v.delivery_order_temp_id}', '${v.delivery_order_draft_kirim_alamat}','${v.delivery_order_draft_kirim_nama}')"><i class="fa fa-trash"></i></button>
									`<button class="btn btn-warning btn-sm btn-location" onclick="GetLocation('${v.delivery_order_draft_kirim_alamat}')"><i class="fa-sharp fa-solid fa-location-dot"></i></button></td>
										<td class="text-center"><input type="checkbox" style="transform: scale(1.5)" class="ChkboxDelOutlet" data-berat="${parseInt(v.sku_weight)}" data-volume="${parseInt(v.sku_volume)}" name="ChkboxDelOutlet" id="ChkboxDelOutlet" onchange="ChkboxDelOutlet(this)" value="${v.delivery_order_temp_id}_${v.delivery_order_draft_kirim_alamat}_${v.delivery_order_draft_kirim_nama}"></td>
									</tr>
							`);
							} else {
								$("#table-do-draft > tbody").append(`
									<tr id="row-${i}" class="ui-sortable-handle">
										<td class="text-center">
											<span id="urutan-prioritas-${i}">${++no}</span>
										</td>
										<input type="hidden" class="latitudelongitude" data-nama="${v.delivery_order_draft_kirim_nama}" data-alamat="${v.delivery_order_draft_kirim_alamat}"  data-lat="${v.client_pt_latitude}" data-long="${v.client_pt_longitude}" data-prioritas="${++no2}"/>
										<td class="text-center resetPrioritas">${v.delivery_order_draft_kirim_nama}</td>
										<td class="text-center resetPrioritas">${v.delivery_order_draft_kirim_alamat}</td>
										<td class="text-center resetPrioritas">${v.delivery_order_draft_kirim_telp}</td>
										<td class="text-center resetPrioritas">${v.jumlah_do}</td>
										<td class="text-center resetPrioritas">${v.delivery_order_draft_kirim_kecamatan}</td>
										<td class="text-center resetPrioritas">${v.delivery_order_draft_kirim_area}</td>
										<td class="text-center resetPrioritas dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_latitude" >${v.client_pt_latitude}</td>
										<td class="text-center resetPrioritas dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_longitude" >${v.client_pt_longitude}</td>
										<td class="text-center resetPrioritas">${parseInt(v.sku_weight)}</td>
										<td class="text-center resetPrioritas">${parseInt(v.sku_volume)}</td>
										<td class="text-center resetPrioritas">${v.composite}</td>
										<td style="display: none">
											${v.delivery_order_temp_id}
										</td>
										<td class="text-center">
										<button class="btn btn-success btn-sm btn-view-detail-do" onclick="ViewDetailDO('${v.delivery_order_temp_id}','${v.delivery_order_draft_kirim_nama}','${v.delivery_order_draft_kirim_alamat}')"><i class="fa fa-eye"></i></button>` +
									// <button class="btn btn-danger btn-sm btn-delete-do-draft" onclick="DeleteDODraft(this,${i},'${v.delivery_order_temp_id}', '${v.delivery_order_draft_kirim_alamat}','${v.delivery_order_draft_kirim_nama}')"><i class="fa fa-trash"></i></button>
									`<button class="btn btn-warning btn-sm btn-location" onclick="GetLocation('${v.delivery_order_draft_kirim_alamat}')"><i class="fa-sharp fa-solid fa-location-dot"></i></button></td>
										<td class="text-center"><input type="checkbox" style="transform: scale(1.5)" class="ChkboxDelOutlet" data-berat="${parseInt(v.sku_weight)}" data-volume="${parseInt(v.sku_volume)}" name="ChkboxDelOutlet" id="ChkboxDelOutlet" onchange="ChkboxDelOutlet(this)" value="${v.delivery_order_temp_id}_${v.delivery_order_draft_kirim_alamat}_${v.delivery_order_draft_kirim_nama}"></td>
										</tr>
								`);
							}
						});

						resetTablesorter();
					}
				}
			});

			resetSimulasiKapasitas();
			dblclick();

			// Get Alamat DO di view table
			arr_do = [];
			arr_do_nama = [];
			$('#table-do-draft tr:has(td)').each(function(idx) {
				// var alamat = "'" + $(this).find("td:eq(3)").text().trim() + "'";
				var alamat = "'" + $(this).find("td:eq(2)").text() + "'";
				var nama = "'" + $(this).find("td:eq(1)").text() + "'";
				arr_do.push(alamat);
				arr_do_nama.push(nama);
			})

			// GET ID DO_DRAFT BY BATCH
			$.ajax({
				async: false,
				type: 'POST',
				url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetIDDOByBatch') ?>",
				data: {
					alamat_do: arr_do,
					nama_do: arr_do_nama,
					temp_do: temp_do
				},
				dataType: "JSON",
				success: function(response) {
					arr_do = [];
					$.each(response, function(i, v) {
						arr_do.push("'" + v.delivery_order_draft_id + "'");
					});
				}
			});

			appendSKU(arr_do);

			getKapasitasTerpakai();

			ResetForm();
			HeaderReadonly();

			// $.ajax({
			// 	async: false,
			// 	type: 'POST',
			// 	url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetSelectedDODraftSKU') ?>",
			// 	data: {
			// 		do_id: arr_do
			// 	},
			// 	dataType: "JSON",
			// 	success: function(response) {

			// 		$.each(response.data, function(i, v) {
			// 			$("#table-summary-sku > tbody").append(`
			// 				<tr id="row-${i}" class="ui-sortable-handle">
			// 					<td class="text-center">
			// 						${v.sku_kode}
			// 						<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-sku_id" value="${i}" />
			// 					</td>
			// 					<td class="text-center">${v.sku_nama_produk}</td>
			// 					<td class="text-center">${v.sku_kemasan}</td>
			// 					<td class="text-center">${v.sku_satuan}</td>
			// 					<td class="text-center">${v.sku_qty}</td>
			// 					<td class="text-center">${v.tipe_stock_nama}</td>
			// 					<td class="text-center">${v.sku_request_expdate}</td>
			// 					<td class="text-center">${v.sku_filter_expdate}</td>
			// 					<td class="text-center">${v.sku_filter_expdatebulan}</td>
			// 					<td class="text-center">
			// 						<button class="btn btn-success btn-sm btn-view-sku" onclick="ViewSKU('${v.sku_id}', '${v.sku_kode}', '${v.sku_nama_produk}')"><i class="fa fa-eye"></i></button>
			// 					</td>
			// 				</tr>
			// 			`);
			// 		});

			// 		$("#totalComposite").text(response.composite);
			// 	}
			// });

			// $.ajax({
			// 	async: false,
			// 	type: 'POST',
			// 	url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetKapasitasTerpakai') ?>",
			// 	data: {
			// 		do_id: arr_do
			// 	},
			// 	dataType: "JSON",
			// 	success: function(response) {

			// 		$.each(response, function(i, v) {
			// 			$("#deliveryorderbatch-kendaraan_berat_gr_terpakai").val(parseInt(v
			// 				.sku_weight));
			// 			$("#deliveryorderbatch-kendaraan_volume_cm3_terpakai").val(parseInt(
			// 				v
			// 				.sku_volume));

			// 		});

			// 		presentaseKapasitasTerpakai();

			// 		selisihKapasitas();

			// 		CekKapasitasiKendaraan();
			// 	}
			// });

		} else {
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: 'Pilih DO!'
			});
		}
	});

	$("#select-dodraft").on("change", function() {
		if (this.checked) {
			$('[name="CheckboxDODraft"]:checkbox').each(function() {
				var alamat = "'" + this.value + "'";
				var nama = "'" + this.getAttribute('data-nama') + "'";
				arrSumQtyCheckbox.push(alamat);
				arrSumQtyCheckbox2.push(nama);
			});

			if (arrSumQtyCheckbox.length > 0) {
				// NEW (GET ID DO DRAFT DAN INSERT KE TABLE delivery_order_temp)
				var str_tipe_layanan = $("#deliveryorderbatch-delivery_order_batch_tipe_layanan_id").val();
				const areaSelected = $("#deliveryorderbatch-area_id").children("option").filter(":selected")
				const area = areaSelected.map(function() {
					return this.textContent.trim()
				}).get()
				const arr_tipe_layanan = str_tipe_layanan.split(" || ");

				$.ajax({
					type: 'POST',
					url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetDODraftByAlamat2') ?>",
					data: {
						tgl: $("#deliveryorderbatch-delivery_order_batch_tanggal_kirim").val(),
						area: area,
						tipe: $("#deliveryorderbatch-tipe_delivery_order_id").val(),
						tipe_layanan: arr_tipe_layanan[2],
						do_temp_id: $("#do_temp_id").val(),
						do_batch_id: $("#do_batch_id").val(),
						alamat: arrSumQtyCheckbox,
						nama: arrSumQtyCheckbox2
					},
					dataType: "JSON",
					success: function(response) {
						// $("#karton").text(response.karton);
						// $("#pack").text(response.pack);
						// $("#pcs").text(response.pcs);

						$("#composite").text(response);

						$("#composite").show();
					}
				});
			}
		} else {
			arrSumQtyCheckbox = [];
			// $("#karton").text(0);
			// $("#pack").text(0);
			// $("#pcs").text(0);

			$("#composite").hide();
		}
	})

	function sumQtyCheckbox(alamat, checked, tipe, nama, event) {

		if (tipe != 'Retur') {
			var cekCanvas = false;

			if (checked == true) {
				// $("#karton").text(0);
				// $("#pack").text(0);
				// $("#pcs").text(0);

				if (tipe == 'Canvas') {
					var numberOfDisabled = $('input[name="CheckboxDODraft"]:disabled').length;
					if (numberOfDisabled > 0) {
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: 'Hapus DO sebelumnya terlebih dahulu!'
						});

						cekCanvas = true;
						$(event).prop('checked', false);
					} else {
						arrSumQtyCheckbox = [];
						arrSumQtyCheckbox2 = [];

						$('input[name="CheckboxDODraft"]').not(event).prop('checked', false);
					}
				}

				var alamat = "'" + alamat + "'";
				var nama = "'" + nama + "'";
				arrSumQtyCheckbox.push(alamat);
				arrSumQtyCheckbox2.push(nama);
			} else {
				// $("#karton").text(0);
				// $("#pack").text(0);
				// $("#pcs").text(0);

				var index = arrSumQtyCheckbox.indexOf("'" + alamat + "'");
				var index2 = arrSumQtyCheckbox2.indexOf("'" + nama + "'");

				arrSumQtyCheckbox.splice(index, 1);
				arrSumQtyCheckbox2.splice(index2, 1);
			}

			if (cekCanvas == true) {
				return false;
			}

			if (arrSumQtyCheckbox.length > 0) {

				// NEW (GET ID DO DRAFT DAN INSERT KE TABLE delivery_order_temp)
				var str_tipe_layanan = $("#deliveryorderbatch-delivery_order_batch_tipe_layanan_id").val();
				const areaSelected = $("#deliveryorderbatch-area_id").children("option").filter(":selected")
				const area = areaSelected.map(function() {
					return this.textContent.trim()
				}).get()
				const arr_tipe_layanan = str_tipe_layanan.split(" || ");

				$.ajax({
					type: 'POST',
					url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetDODraftByAlamat2') ?>",
					data: {
						tgl: $("#deliveryorderbatch-delivery_order_batch_tanggal_kirim").val(),
						area: area,
						tipe: $("#deliveryorderbatch-tipe_delivery_order_id").val(),
						tipe_layanan: arr_tipe_layanan[2],
						do_temp_id: $("#do_temp_id").val(),
						do_batch_id: $("#do_batch_id").val(),
						alamat: arrSumQtyCheckbox,
						nama: arrSumQtyCheckbox2
					},
					dataType: "JSON",
					success: function(response) {
						// $("#karton").text(response.karton);
						// $("#pack").text(response.pack);
						// $("#pcs").text(response.pcs);

						$("#composite").text(response);

						$("#composite").show();
					}
				});
			} else {
				// $("#karton").text(0);
				// $("#pack").text(0);
				// $("#pcs").text(0);

				$("#composite").hide();
			}
		}

	}

	// function hitungQuantity() {
	// 	arrSumQtyCheckbox = [];
	// 	$('[name="CheckboxDODraft"]:checkbox').each(function() {
	// 		if (this.checked) {
	// 			var alamat = "'" + this.value + "'";
	// 			var tipe = "'" + this.getAttribute('data-tipe') + "'";

	// 			if (tipe != 'Retur') {
	// 				arrSumQtyCheckbox.push(alamat);
	// 			}
	// 		}
	// 	});

	// 	if (arrSumQtyCheckbox.length > 0) {
	// 		// NEW (GET ID DO DRAFT DAN INSERT KE TABLE delivery_order_temp)
	// 		var str_tipe_layanan = $("#deliveryorderbatch-delivery_order_batch_tipe_layanan_id").val();
	// 		const areaSelected = $("#deliveryorderbatch-area_id").children("option").filter(":selected")
	// 		const area = areaSelected.map(function() {
	// 			return this.textContent.trim()
	// 		}).get()
	// 		const arr_tipe_layanan = str_tipe_layanan.split(" || ");

	// 		$.ajax({
	// 			type: 'POST',
	// 			url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetDODraftByAlamat2') ?>",
	// 			data: {
	// 				tgl: $("#deliveryorderbatch-delivery_order_batch_tanggal_kirim").val(),
	// 				area: area,
	// 				tipe: $("#deliveryorderbatch-tipe_delivery_order_id").val(),
	// 				tipe_layanan: arr_tipe_layanan[2],
	// 				do_temp_id: $("#do_temp_id").val(),
	// 				do_batch_id: $("#do_batch_id").val(),
	// 				alamat: arrSumQtyCheckbox
	// 			},
	// 			beforeSend: function() {

	// 				//Alert memuat data
	// 				Swal.fire({
	// 					title: '<span><i class="fas fa-spinner fa-spin"></i></span> &nbsp;&nbsp;<span>Loading ...</span>',
	// 					timerProgressBar: false,
	// 					showConfirmButton: false,
	// 					allowOutsideClick: false,
	// 				});
	// 			},
	// 			dataType: "JSON",
	// 			success: function(response) {
	// 				swal.close();
	// 				$("#karton").text(response.karton);
	// 				$("#pack").text(response.pack);
	// 				$("#pcs").text(response.pcs);
	// 			}
	// 		});
	// 	} else {
	// 		$("#karton").text(0);
	// 		$("#pack").text(0);
	// 		$("#pcs").text(0);
	// 	}
	// }



	function initDataDoDraft() {
		arrSumQtyCheckbox = [];
		arrSumQtyCheckbox2 = [];
		$("#karton").text(0);
		$("#pack").text(0);
		$("#pcs").text(0);
		$("#composite").hide();

		var str_tipe_layanan = $("#deliveryorderbatch-delivery_order_batch_tipe_layanan_id").val();
		const areaSelected = $("#deliveryorderbatch-area_id").children("option").filter(":selected")
		const area = areaSelected.map(function() {
			return this.textContent.trim()
		}).get()

		const arr_tipe_layanan = str_tipe_layanan.split(" || ");

		if ($("#deliveryorderbatch-area_id").val() == "") {
			message("Error!", "Pilih Area!", "error");
			return false;
		} else if ($("#deliveryorderbatch-tipe_delivery_order_id").val() == "") {
			message("Error!", "Pilih Tipe!", "error");
			return false;
		} else if ($("#deliveryorderbatch-delivery_order_batch_tipe_layanan_id").val() == "") {
			message("Error!", "Pilih Tipe Layanan!", "error");
			return false;
		} else {
			// $(".label-service-type").html('');
			// $(".label-do-type").html('');
			// $(".label-area").html('');
			// $(".label-delivery-date").html('');

			$("#loadingdodraft").show();

			var arr_check_temp = [];
			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetDOTempByIDTemp') ?>",
				data: {
					temp_do: "'" + $("#do_temp_id").val() + "'"
				},
				dataType: "JSON",
				async: false,
				success: function(response) {
					if (response.length > 0) {
						$.each(response, function(i, v) {
							arr_check_temp.push({
								alamat: v.delivery_order_draft_kirim_alamat,
								nama: v.delivery_order_draft_kirim_nama
							});
						});
					}
				}
			})

			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetDODraftByFilter') ?>",
				data: {
					tgl: $("#deliveryorderbatch-delivery_order_batch_tanggal_kirim").val(),
					area: area,
					tipe: $("#deliveryorderbatch-tipe_delivery_order_id").val(),
					tipe_layanan: arr_tipe_layanan[2],
					kelas_jalan: $('#chk_kelas_jalan').prop('checked'),
					kendaraan: $('#chk_kelas_jalan').prop('checked') == true ? $('#deliveryorderbatch-kendaraan_id').val() : ''
				},
				beforeSend: function() {
					Swal.fire({
						title: '<span><i class="fas fa-spinner fa-spin"></i></span> &nbsp;&nbsp;<span>Loading ...</span>',
						timerProgressBar: false,
						showConfirmButton: false,
						allowOutsideClick: false,
					});
				},
				dataType: "JSON",
				// async: false,
				success: function(response) {
					$("#loadingsku").hide();

					// TIPE CANVAS
					var tipeCanvas = $("#deliveryorderbatch-tipe_delivery_order_id").val();
					if (tipeCanvas == 'B608AD49-2E8E-4289-8463-EC90DAAFB971') {
						$("#select-dodraft").prop('disabled', true);
					} else {
						$("#select-dodraft").prop('disabled', false);
					}

					if (response.length > 0) {
						$("#data-table-dodraft > tbody").empty();
						$("#data-table-dodraft > tbody").html();
						if ($.fn.dataTable.isDataTable('#data-table-dodraft')) {
							$('#data-table-dodraft').DataTable().clear();
							$('#data-table-dodraft').DataTable().destroy();
						}
						$(".label-service-type").html(arr_tipe_layanan[2]);
						$(".label-do-type").html($("#deliveryorderbatch-tipe_delivery_order_id").children("option").filter(":selected").text());
						$(".label-area").html($("#deliveryorderbatch-area_id").children("option").filter(":selected").text());
						$(".label-delivery-date").html($("#deliveryorderbatch-delivery_order_batch_tanggal_kirim").val());
						var no = 1;
						$.each(response, function(i, v) {
							var alamatTrim = v.delivery_order_draft_kirim_alamat.replace(/[!"#$%&'()*+,.\/:;<=>?@[\\\]^`{|}~/\s/g]/g, "");
							$("#data-table-dodraft > tbody").append(`
								<tr id="${alamatTrim}">
									<td width="5%" class="text-center">
										<input onchange="sumQtyCheckbox('${v.delivery_order_draft_kirim_alamat}', this.checked, '${v.tipe_delivery_order_nama}', '${v.delivery_order_draft_kirim_nama}', this)" data-tipe="${v.tipe_delivery_order_nama}" data-nama="${v.delivery_order_draft_kirim_nama}" type="checkbox" name="CheckboxDODraft" id="check-dodraft-${i}" value="${v.delivery_order_draft_kirim_alamat}">
									</td>
									<td width="10%" class="text-center">${i+1}</td>
									<td width="15%" class="text-center">${v.sales_order_no_po}</td>
									<td width="15%" class="text-center">${v.principle_kode}</td>
									<td width="15%" class="text-center">${v.delivery_order_draft_kirim_area}</td>
									<td width="15%" class="text-center">${v.delivery_order_draft_kirim_nama}</td>
									<td width="25%" class="text-center">${v.delivery_order_draft_kirim_alamat}</td>
									<td width="15%" class="text-center">${v.delivery_order_draft_kirim_kecamatan}</td>
									<td width="15%" class="text-center">${v.kelas_jalan_nama}</td>
									<td width="25%" class="text-center">${v.sku_weight}</td>
									<td width="25%" class="text-center">${v.sku_volume}</td>
									<td class="text-center">${v.tipe_delivery_order_nama}</td>
									<td class="text-center">${v.composite}</td>
								</tr>
                    		`);

						});

						// initDataTableKaryawan();
						$("#data-table-dodraft").DataTable({
							"paging": false,
							'lengthMenu': [
								["All"]
							]
						});

						cek_checkbox_sku(arr_check_temp);

					} else {
						$("#data-table-dodraft > tbody").html(
							`<tr><td colspan="12" class="text-center text-danger">Data Kosong</td></tr>`);
					}
				},
				complete: function(response) {
					Swal.close();
				}
			});

		}
	}

	$("#chk_kelas_jalan").on("click", function() {
		initDataDoDraft();

		// $('#modal-dodraft').fadeOut("slow", function() {
		// 	$(this).modal('hide');
		// }).fadeIn("slow", function() {
		// 	initDataDoDraft();
		// 	$(this).modal('show');
		// });
	})

	$("#btneditdobatch").on("click", function() {
		var cek_error = 0;
		var kap_weight_terpakai = parseInt($("#deliveryorderbatch-kendaraan_berat_gr_terpakai").val());
		var kap_vol_terpakai = parseInt($("#deliveryorderbatch-kendaraan_volume_cm3_terpakai").val());
		var kap_weight_max = parseInt($("#deliveryorderbatch-kendaraan_berat_gr_max").val());
		var kap_vol_max = parseInt($("#deliveryorderbatch-kendaraan_volume_cm3_max").val());
		// var tes = $("#deliveryorderbatch-karyawan_id").val();
		// var date = new Date();
		// var time = date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds();
		// document.write(time);

		// console.log(tes);
		// console.log(date);
		// return false;

		if (kap_weight_terpakai > kap_weight_max) {
			message("Warning!", "Melebihi Kapasitas Berat Kendaraan!", "error");
		}

		if (kap_vol_terpakai > kap_vol_max) {
			message("Warning!", "Melebihi Kapasitas Volume Kendaraan!", "error");
		}

		if (cek_error == 0) {
			cek_error = 0;

			messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Iya, Simpan', 'Tidak, Tutup').then((result) => {
				if (result.value == true) {
					requestAjax("<?= base_url('WMS/Distribusi/DeliveryOrderBatch/UpreAssigmentDriver'); ?>", {
						depo_id: "<?= $this->session->userdata('depo_id') ?>",
						delivery_order_batch_id: $("#delivery_order_batch_id").val(),
						kendaraan_id_awal: $("#kendaraan_id_awal").val(),
						karyawan_id_awal: $("#karyawan_id_awal").val(),
						reason: $("#reason").val(),
						delivery_order_batch_create_who: "<?= $this->session->userdata('pengguna_username') ?>",
						kendaraan_id_pengganti: $("#deliveryorderbatch-kendaraan_id")
							.val(),
						karyawan_id_pengganti: $("#deliveryorderbatch-karyawan_id").val(),
						kendaraan_volume_cm3_max: $(
								"#deliveryorderbatch-kendaraan_volume_cm3_max")
							.val(),
						kendaraan_berat_gr_max: $(
								"#deliveryorderbatch-kendaraan_berat_gr_max")
							.val(),
						tglLastUpdate: $('#tglLastUpdate').val() == 'null' ? '' : $('#tglLastUpdate').val()
					}, "POST", "JSON", function(data) {
						if (data == 400) {
							return messageNotSameLastUpdated();
						}

						if (data == 200) {
							message_topright("success",
								"Data berhasil disimpan");
							setTimeout(() => {
								location.href =
									"<?= base_url(); ?>WMS/Distribusi/DeliveryOrderBatch/";
							}, 500);
						} else {
							message_topright("error", "Data gagal disimpan");
							// var msg = data;
							// var msgtype = 'error';

							// //if (!window.__cfRLUnblockHandlers) return false;
							// new PNotify
							// 	({
							// 		title: 'Info',
							// 		text: msg,
							// 		type: msgtype,
							// 		styling: 'bootstrap3',
							// 		delay: 3000
							// 	});

						}
					});

					arr_list_do = [];
				}
			});

		} else {
			cek_error = 0;
			message("Error!", "Data Gagal Disimpan!", "error");
		}

	})

	$("#btnsavedobatch").on("click", function() {
		var cek_error = 0;
		var kap_weight_terpakai = parseInt($("#deliveryorderbatch-kendaraan_berat_gr_terpakai").val());
		var kap_vol_terpakai = parseInt($("#deliveryorderbatch-kendaraan_volume_cm3_terpakai").val());
		var kap_weight_max = parseInt($("#deliveryorderbatch-kendaraan_berat_gr_max").val());
		var kap_vol_max = parseInt($("#deliveryorderbatch-kendaraan_volume_cm3_max").val());

		var str_tipe_layanan = $("#deliveryorderbatch-delivery_order_batch_tipe_layanan_id").val();
		const areaSelected = $("#deliveryorderbatch-area_id").children("option").filter(":selected")
		const area = areaSelected.map(function() {
			return this.value
		}).get()
		const arr_tipe_layanan = str_tipe_layanan.split(" || ");

		if (area.length == 0) {
			message("Error!", 'Area tidak boleh kosong', 'error')
			return false;
		}

		// if ($("#deliveryorderbatch-area_id").val() == "") {
		// 	message("Error!", "Pilih Area!", "error");
		// 	cek_error++;
		// }

		// if ($("#deliveryorderbatch-tipe_delivery_order_id").val() == "") {
		// 	message("Error!", "Pilih Tipe!", "error");
		// 	cek_error++;
		// }

		// if ($("#deliveryorderbatch-delivery_order_batch_tipe_layanan_id").val() == "") {
		// 	message("Error!", "Pilih Tipe Layanan!", "error");
		// 	cek_error++;
		// }

		// if ($("#deliveryorderbatch-tipe_ekspedisi_id").val() == "") {
		// 	message("Error!", "Pilih Tipe Layanan!", "error");
		// 	cek_error++;
		// }

		// if ($("#deliveryorderbatch-tipe_ekspedisi_id").val() == "") {
		// 	message("Error!", "Pilih Tipe Layanan!", "error");
		// 	cek_error++;
		// }

		// if ($("#deliveryorderbatch-tipe_ekspedisi_id").val() == "") {
		// 	message("Error!", "Pilih Tipe Layanan!", "error");
		// 	cek_error++;
		// }

		if (kap_weight_terpakai > kap_weight_max) {
			message("Warning!", "Melebihi Kapasitas Berat Kendaraan!", "error");
		}

		if (kap_vol_terpakai > kap_vol_max) {
			message("Warning!", "Melebihi Kapasitas Volume Kendaraan!", "error");
		}

		// console.log(arr_do);
		// return false;

		if (cek_error == 0) {
			cek_error = 0;

			if (arr_do.length > 0) {

				var arr_do_temp = [];
				var arr_alamat_do = [];
				var arr_nama_do = [];
				var arr_del_id_temp = [];
				var temp_do = '';
				var beratBatch = 0;
				var volumeBatch = 0;

				$("#table-do-draft tr:has(td)").each(function(idx) {
					var RefID = "'" + $(this).find("td:eq(0)").text().trim() + "'";
					// var alamat_do = "'" + $(this).find("td:eq(3)").text().trim() + "'";
					var alamat_do = "'" + $(this).find("td:eq(2)").text() + "'";
					var nama_do = "'" + $(this).find("td:eq(1)").text() + "'";
					temp_do = "'" + $(this).find("td:eq(12)").text().trim() + "'";

					arr_alamat_do.push(alamat_do);
					arr_nama_do.push(nama_do);

					arr_do_temp.push({
						'alamat_do': alamat_do,
						'nama_do': nama_do,
						'no_urut': RefID
					});

					beratBatch += parseInt($(this).find("td:eq(9)").text())
					volumeBatch += parseInt($(this).find("td:eq(10)").text())

				});

				messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Iya, Simpan', 'Tidak, Tutup').then((result) => {
					if (result.value == true) {
						var batch = '';
						// GET ID DO By TEMP
						$.ajax({
							async: false,
							type: 'POST',
							url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetIDDOByBatchByNoUrut') ?>",
							data: {
								alamat_by_nourut: arr_do_temp,
								alamat_do: arr_alamat_do,
								nama_do: arr_nama_do,
								temp_do: temp_do
							},
							dataType: "JSON",
							success: function(response) {

								$.each(response, function(i, v) {
									arr_list_do.push({
										'delivery_order_draft_id': v
											.delivery_order_draft_id,
										'no_urut': v.no_urut
									});

									arr_del_id_temp.push("'" + v
										.delivery_order_draft_id + "'");

									batch = v.delivery_order_batch_id;
								});
							}
						});

						requestAjax("<?= base_url('WMS/Distribusi/DeliveryOrderBatch/insert_delivery_order_batch'); ?>", {
							unit_mandiri_id: "<?= $this->session->userdata('unit_mandiri_id') ?>",
							client_wms_id: "",
							depo_id: "<?= $this->session->userdata('depo_id') ?>",
							depo_detail_id: "15F0B95F-A469-4086-94E6-B73306A3CBAC",
							delivery_order_batch_ritasi: "",
							delivery_order_batch_tanggal: $(
									"#deliveryorderbatch-delivery_order_batch_tanggal"
								)
								.val(),
							delivery_order_batch_tanggal_kirim: $(
									"#deliveryorderbatch-delivery_order_batch_tanggal_kirim"
								)
								.val(),
							tipe_pengiriman_id: "",
							delivery_order_batch_tipe_layanan_id: arr_tipe_layanan[
								0],
							delivery_order_batch_tipe_layanan_no: arr_tipe_layanan[
								1],
							delivery_order_batch_tipe_layanan_nama: arr_tipe_layanan[
								2],
							tipe_delivery_order_id: $(
									"#deliveryorderbatch-tipe_delivery_order_id")
								.val(),
							delivery_order_batch_is_need_packing: 1,
							delivery_order_batch_create_who: "<?= $this->session->userdata('pengguna_username') ?>",
							delivery_order_batch_create_tgl: "",
							area_id: area,
							delivery_order_batch_status: $(
									"#deliveryorderbatch-delivery_order_batch_status"
								)
								.val(),
							kendaraan_id: $("#deliveryorderbatch-kendaraan_id")
								.val(),
							karyawan_id: $("#deliveryorderbatch-karyawan_id").val(),
							tipe_ekspedisi_id: $(
									"#deliveryorderbatch-tipe_ekspedisi_id")
								.val(),
							kendaraan_volume_cm3_max: $(
									"#deliveryorderbatch-kendaraan_volume_cm3_max")
								.val(),
							kendaraan_berat_gr_max: $(
									"#deliveryorderbatch-kendaraan_berat_gr_max")
								.val(),
							kendaraan_volume_cm3_terpakai: $(
									"#deliveryorderbatch-kendaraan_volume_cm3_terpakai"
								)
								.val(),
							kendaraan_berat_gr_terpakai: $(
									"#deliveryorderbatch-kendaraan_berat_gr_terpakai"
								)
								.val(),
							kendaraan_volume_cm3_sisa: $(
									"#selisihKapasitasVolume")
								.val(),
							kendaraan_berat_gr_sisa: $(
									"#selisihKapasitasBerat")
								.val(),
							ritasi: $(
									"#ritasi")
								.val(),
							kendaraan_km_awal: 0,
							kendaraan_km_akhir: 0,
							kendaraan_km_terpakai: 0,
							picking_list_id: "",
							picking_order_id: "",
							serah_terima_kirim_id: "",
							list_do: arr_list_do,
							beratBatch: beratBatch,
							volumeBatch: volumeBatch,
							batch: batch
						}, "POST", "JSON", function(data) {
							if (data == 400) {
								return messageNotSameLastUpdated();
							}

							if (data == 1) {
								// Hapus data do di tabel do_temp
								$.ajax({
									async: false,
									type: 'POST',
									url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/DeleteDOTempByAlamat') ?>",
									data: {
										do_id: arr_del_id_temp,
										temp_do: temp_do
									},
									dataType: "JSON",
									success: function(response) {
										if (response == 1) {
											row.parentNode.removeChild(row);
										}

									}
								});

								message_topright("success",
									"Data berhasil disimpan");
								setTimeout(() => {
									location.href =
										"<?= base_url(); ?>WMS/Distribusi/DeliveryOrderBatch/";
								}, 500);
							} else {
								message_topright("error", "Data gagal disimpan");
								// var msg = data;
								// var msgtype = 'error';

								// //if (!window.__cfRLUnblockHandlers) return false;
								// new PNotify
								// 	({
								// 		title: 'Info',
								// 		text: msg,
								// 		type: msgtype,
								// 		styling: 'bootstrap3',
								// 		delay: 3000
								// 	});

							}
						})
					}
				});

			} else {
				message("Pilih SKU!", "SKU belum dipilih", "error");
			}

		} else {
			cek_error = 0;
			message("Error!", "Data Gagal Disimpan!", "error");
		}

	});

	$("#btnupdatedobatch").on("click", function() {
		var cek_error = 0;
		var kap_weight_terpakai = parseInt($("#deliveryorderbatch-kendaraan_berat_gr_terpakai").val());
		var kap_vol_terpakai = parseInt($("#deliveryorderbatch-kendaraan_volume_cm3_terpakai").val());
		var kap_weight_max = parseInt($("#deliveryorderbatch-kendaraan_berat_gr_max").val());
		var kap_vol_max = parseInt($("#deliveryorderbatch-kendaraan_volume_cm3_max").val());

		var str_tipe_layanan = $("#deliveryorderbatch-delivery_order_batch_tipe_layanan_id").val();

		const areaSelected = $("#deliveryorderbatch-area_id").children("option").filter(":selected")
		const area = areaSelected.map(function() {
			return this.value
		}).get()

		const arr_tipe_layanan = str_tipe_layanan.split(" || ");

		if (area.length == 0) {
			message("Error!", 'Area tidak boleh kosong', 'error')
			return false;
		}

		// if ($("#deliveryorderbatch-area_id").val() == "") {
		// 	message("Error!", "Pilih Area!", "error");
		// 	cek_error++;
		// }

		// if ($("#deliveryorderbatch-tipe_delivery_order_id").val() == "") {
		// 	message("Error!", "Pilih Tipe!", "error");
		// 	cek_error++;
		// }

		// if ($("#deliveryorderbatch-delivery_order_batch_tipe_layanan_id").val() == "") {
		// 	message("Error!", "Pilih Tipe Layanan!", "error");
		// 	cek_error++;
		// }

		// if ($("#deliveryorderbatch-tipe_ekspedisi_id").val() == "") {
		// 	message("Error!", "Pilih Tipe Layanan!", "error");
		// 	cek_error++;
		// }

		// if ($("#deliveryorderbatch-tipe_ekspedisi_id").val() == "") {
		// 	message("Error!", "Pilih Tipe Layanan!", "error");
		// 	cek_error++;
		// }

		// if ($("#deliveryorderbatch-tipe_ekspedisi_id").val() == "") {
		// 	message("Error!", "Pilih Tipe Layanan!", "error");
		// 	cek_error++;
		// }

		if (kap_weight_terpakai > kap_weight_max) {
			message("Warning!", "Melebihi Kapasitas Berat Kendaraan!", "error");
		}

		if (kap_vol_terpakai > kap_vol_max) {
			message("Warning!", "Melebihi Kapasitas Volume Kendaraan!", "error");
		}

		if (cek_error == 0) {
			cek_error = 0;

			if (arr_do.length > 0) {

				var arr_do_temp = [];
				var arr_alamat_do = [];
				var arr_nama_do = [];
				var arr_del_id_temp = [];
				var beratBatch = 0;
				var volumeBatch = 0;

				$("#table-do-draft tr:has(td)").each(function(idx) {
					var RefID = "'" + $(this).find("td:eq(0)").text().trim() + "'";
					// var alamat_do = "'" + $(this).find("td:eq(3)").text().trim() + "'";
					var alamat_do = "'" + $(this).find("td:eq(2)").text() + "'";
					var nama_do = "'" + $(this).find("td:eq(1)").text() + "'";

					arr_alamat_do.push(alamat_do);
					arr_nama_do.push(nama_do);

					arr_do_temp.push({
						'alamat_do': alamat_do,
						'nama_do': nama_do,
						'no_urut': RefID
					});

					// arr_list_do.push({
					//     'delivery_order_draft_id': dod_id,
					//     'no_urut': RefID
					// });

					beratBatch += parseInt($(this).find("td:eq(9)").text())
					volumeBatch += parseInt($(this).find("td:eq(10)").text())

				});

				messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Iya, Simpan', 'Tidak, Tutup').then((result) => {
					if (result.value == true) {
						// GET ID DO By TEMP
						var batch = '';
						$.ajax({
							async: false,
							type: 'POST',
							url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetIDDOByBatchByNoUrut') ?>",
							data: {
								alamat_by_nourut: arr_do_temp,
								alamat_do: arr_alamat_do,
								nama_do: arr_nama_do,
								temp_do: "'" + $("#do_temp_id").val() + "'"
							},
							dataType: "JSON",
							success: function(response) {

								$.each(response, function(i, v) {
									arr_list_do.push({
										'delivery_order_draft_id': v
											.delivery_order_draft_id,
										'no_urut': v.no_urut
									});

									arr_del_id_temp.push("'" + v
										.delivery_order_draft_id + "'");
								});
							}
						});

						requestAjax("<?= base_url('WMS/Distribusi/DeliveryOrderBatch/update_delivery_order_batch'); ?>", {
							delivery_order_batch_id: $(
									"#deliveryorderbatch-delivery_order_batch_id")
								.val(),
							delivery_order_batch_kode: $(
									"#deliveryorderbatch-delivery_order_batch_kode")
								.val(),
							unit_mandiri_id: "<?= $this->session->userdata('unit_mandiri_id') ?>",
							client_wms_id: "",
							depo_id: "<?= $this->session->userdata('depo_id') ?>",
							depo_detail_id: "15F0B95F-A469-4086-94E6-B73306A3CBAC",
							delivery_order_batch_ritasi: "",
							delivery_order_batch_tanggal: $(
									"#deliveryorderbatch-delivery_order_batch_tanggal"
								)
								.val(),
							delivery_order_batch_tanggal_kirim: $(
									"#deliveryorderbatch-delivery_order_batch_tanggal_kirim"
								)
								.val(),
							tipe_pengiriman_id: "",
							delivery_order_batch_tipe_layanan_id: arr_tipe_layanan[
								0],
							delivery_order_batch_tipe_layanan_no: arr_tipe_layanan[
								1],
							delivery_order_batch_tipe_layanan_nama: arr_tipe_layanan[
								2],
							tipe_delivery_order_id: $(
									"#deliveryorderbatch-tipe_delivery_order_id")
								.val(),
							delivery_order_batch_is_need_packing: 1,
							delivery_order_batch_create_who: "<?= $this->session->userdata('pengguna_username') ?>",
							delivery_order_batch_create_tgl: "",
							area: area,
							delivery_order_batch_status: $(
									"#deliveryorderbatch-delivery_order_batch_status"
								)
								.val(),
							kendaraan_id: $("#deliveryorderbatch-kendaraan_id")
								.val(),
							karyawan_id: $("#deliveryorderbatch-karyawan_id").val(),
							tipe_ekspedisi_id: $(
									"#deliveryorderbatch-tipe_ekspedisi_id")
								.val(),
							kendaraan_volume_cm3_max: $(
									"#deliveryorderbatch-kendaraan_volume_cm3_max")
								.val(),
							kendaraan_berat_gr_max: $(
									"#deliveryorderbatch-kendaraan_berat_gr_max")
								.val(),
							kendaraan_volume_cm3_terpakai: $(
									"#deliveryorderbatch-kendaraan_volume_cm3_terpakai"
								)
								.val(),
							kendaraan_berat_gr_terpakai: $(
									"#deliveryorderbatch-kendaraan_berat_gr_terpakai"
								)
								.val(),
							kendaraan_volume_cm3_sisa: $(
									"#selisihKapasitasVolume")
								.val(),
							kendaraan_berat_gr_sisa: $(
									"#selisihKapasitasBerat")
								.val(),
							kendaraan_km_awal: 0,
							kendaraan_km_akhir: 0,
							kendaraan_km_terpakai: 0,
							picking_list_id: "",
							picking_order_id: "",
							serah_terima_kirim_id: "",
							list_do: arr_list_do,
							beratBatch: beratBatch,
							volumeBatch: volumeBatch,
							temp_do: $("#do_temp_id").val(),
							tglLastUpdate: $("#tglLastUpdate").val(),
							ritasiID: $("#ritasiID").val()
						}, "POST", "JSON", function(data) {
							if (data.status == 400) {
								return messageNotSameLastUpdated();
							}

							if (data.status == 200) {
								// Hapus data do di tabel do_temp
								$.ajax({
									async: false,
									type: 'POST',
									url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/DeleteDOTempByAlamat') ?>",
									data: {
										do_id: arr_del_id_temp,
										temp_do: "'" + $("#do_temp_id").val() +
											"'"
									},
									dataType: "JSON",
									success: function(response) {
										// alert(response);
										// return false;
										if (response == 1) {
											row.parentNode.removeChild(row);
										}

									}
								});

								message_topright("success",
									data.message);

								setTimeout(() => {
									location.href =
										"<?= base_url(); ?>WMS/Distribusi/DeliveryOrderBatch/edit/?id=" +
										$(
											"#deliveryorderbatch-delivery_order_batch_id"
										)
										.val();
								}, 2000);
							} else {
								message_topright("error", data.message);
								// var msg = data;
								// var msgtype = 'error';

								// //if (!window.__cfRLUnblockHandlers) return false;
								// new PNotify
								// 	({
								// 		title: 'Info',
								// 		text: msg,
								// 		type: msgtype,
								// 		styling: 'bootstrap3',
								// 		delay: 3000
								// 	});

							}
						})
					}
				});

			} else {
				message("Pilih SKU!", "SKU belum dipilih", "error");
			}

		} else {
			cek_error = 0;
			message("Error!", "Data Gagal Disimpan!", "error");
		}

	});

	$("#btnconfirmdobatch").on("click", function() {

		var cek_error = 0;
		var kap_weight_terpakai = parseInt($("#deliveryorderbatch-kendaraan_berat_gr_terpakai").val());
		var kap_vol_terpakai = parseInt($("#deliveryorderbatch-kendaraan_volume_cm3_terpakai").val());
		var kap_weight_max = parseInt($("#deliveryorderbatch-kendaraan_berat_gr_max").val());
		var kap_vol_max = parseInt($("#deliveryorderbatch-kendaraan_volume_cm3_max").val());

		var str_tipe_layanan = $("#deliveryorderbatch-delivery_order_batch_tipe_layanan_id").val();
		const areaSelected = $("#deliveryorderbatch-area_id").children("option").filter(":selected")
		const area = areaSelected.map(function() {
			return this.value
		}).get()
		const arr_tipe_layanan = str_tipe_layanan.split(" || ");

		if (area.length == 0) {
			message("Error!", 'Area tidak boleh kosong', 'error')
			return false;
		}

		// if ($("#deliveryorderbatch-area_id").val() == "") {
		// 	message("Error!", "Pilih Area!", "error");
		// 	cek_error++;
		// }

		// if ($("#deliveryorderbatch-tipe_delivery_order_id").val() == "") {
		// 	message("Error!", "Pilih Tipe!", "error");
		// 	cek_error++;
		// }

		// if ($("#deliveryorderbatch-delivery_order_batch_tipe_layanan_id").val() == "") {
		// 	message("Error!", "Pilih Tipe Layanan!", "error");
		// 	cek_error++;
		// }

		// if ($("#deliveryorderbatch-tipe_ekspedisi_id").val() == "") {
		// 	message("Error!", "Pilih Tipe Layanan!", "error");
		// 	cek_error++;
		// }

		// if ($("#deliveryorderbatch-tipe_ekspedisi_id").val() == "") {
		// 	message("Error!", "Pilih Tipe Layanan!", "error");
		// 	cek_error++;
		// }

		// if ($("#deliveryorderbatch-tipe_ekspedisi_id").val() == "") {
		// 	message("Error!", "Pilih Tipe Layanan!", "error");
		// 	cek_error++;
		// }

		if (kap_weight_terpakai > kap_weight_max) {
			message("Warning!", "Melebihi Kapasitas Berat Kendaraan!", "error");
		}

		if (kap_vol_terpakai > kap_vol_max) {
			message("Warning!", "Melebihi Kapasitas Volume Kendaraan!", "error");
		}

		if (cek_error == 0) {
			cek_error = 0;

			if (arr_do.length > 0) {

				var arr_do_temp = [];
				var arr_alamat_do = [];
				var arr_nama_do = [];
				var beratBatch = 0;
				var volumeBatch = 0;

				$("#table-do-draft tr:has(td)").each(function(idx) {
					var RefID = "'" + $(this).find("td:eq(0)").text().trim() + "'";
					// var alamat_do = "'" + $(this).find("td:eq(3)").text().trim() + "'";
					var alamat_do = "'" + $(this).find("td:eq(2)").text() + "'";
					var nama_do = "'" + $(this).find("td:eq(1)").text() + "'";

					arr_alamat_do.push(alamat_do);
					arr_nama_do.push(nama_do);

					arr_do_temp.push({
						'alamat_do': alamat_do,
						'nama_do': nama_do,
						'no_urut': RefID
					});

					beratBatch += parseInt($(this).find("td:eq(9)").text())
					volumeBatch += parseInt($(this).find("td:eq(10)").text())

				});

				messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Iya, Simpan', 'Tidak, Tutup').then((result) => {
					if (result.value == true) {
						$.ajax({
							async: false,
							type: 'POST',
							url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetIDDOByBatchByNoUrut') ?>",
							data: {
								alamat_by_nourut: arr_do_temp,
								alamat_do: arr_alamat_do,
								nama_do: arr_nama_do,
								temp_do: "'" + $("#do_temp_id").val() + "'"
							},
							dataType: "JSON",
							success: function(response) {

								$.each(response, function(i, v) {
									arr_list_do.push({
										'delivery_order_draft_id': v.delivery_order_draft_id,
										'no_urut': v.no_urut,
										'delivery_order_draft_tipe_pembayaran': v.delivery_order_draft_tipe_pembayaran,
										'delivery_order_draft_nominal_tunai': v.delivery_order_draft_nominal_tunai
									});
								});
							}
						});

						//ajax save data
						if (arr_del_do.length > 0) {
							$.ajax({
								async: false,
								type: 'POST',
								url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/DeleteDO') ?>",
								data: {
									arr_del_do: arr_del_do,
									tglLastUpdate: $("#tglLastUpdate").val(),
									delivery_order_batch_id: $("#deliveryorderbatch-delivery_order_batch_id")

								},
								dataType: "JSON",
								success: function(response) {
									if (response.status == 400) {
										return messageNotSameLastUpdated();
									}
								}
							});
						}

						requestAjax("<?= base_url('WMS/Distribusi/DeliveryOrderBatch/confirm_delivery_order_batch'); ?>", {
							delivery_order_batch_id: $(
									"#deliveryorderbatch-delivery_order_batch_id")
								.val(),
							delivery_order_batch_kode: $(
									"#deliveryorderbatch-delivery_order_batch_kode")
								.val(),
							unit_mandiri_id: "<?= $this->session->userdata('unit_mandiri_id') ?>",
							client_wms_id: "",
							depo_id: "<?= $this->session->userdata('depo_id') ?>",
							depo_detail_id: "15F0B95F-A469-4086-94E6-B73306A3CBAC",
							delivery_order_batch_ritasi: "",
							delivery_order_batch_tanggal: $(
									"#deliveryorderbatch-delivery_order_batch_tanggal"
								)
								.val(),
							delivery_order_batch_tanggal_kirim: $(
									"#deliveryorderbatch-delivery_order_batch_tanggal_kirim"
								)
								.val(),
							tipe_pengiriman_id: "",
							delivery_order_batch_tipe_layanan_id: arr_tipe_layanan[
								0],
							delivery_order_batch_tipe_layanan_no: arr_tipe_layanan[
								1],
							delivery_order_batch_tipe_layanan_nama: arr_tipe_layanan[
								2],
							tipe_delivery_order_id: $(
									"#deliveryorderbatch-tipe_delivery_order_id")
								.val(),
							delivery_order_batch_is_need_packing: 1,
							delivery_order_batch_create_who: "<?= $this->session->userdata('pengguna_username') ?>",
							delivery_order_batch_create_tgl: "",
							area: area,
							delivery_order_batch_status: "in progress",
							kendaraan_id: $("#deliveryorderbatch-kendaraan_id")
								.val(),
							karyawan_id: $("#deliveryorderbatch-karyawan_id").val(),
							tipe_ekspedisi_id: $(
									"#deliveryorderbatch-tipe_ekspedisi_id")
								.val(),
							kendaraan_volume_cm3_max: $(
									"#deliveryorderbatch-kendaraan_volume_cm3_max")
								.val(),
							kendaraan_berat_gr_max: $(
									"#deliveryorderbatch-kendaraan_berat_gr_max")
								.val(),
							kendaraan_volume_cm3_terpakai: $(
									"#deliveryorderbatch-kendaraan_volume_cm3_terpakai"
								)
								.val(),
							kendaraan_berat_gr_terpakai: $(
									"#deliveryorderbatch-kendaraan_berat_gr_terpakai"
								)
								.val(),
							kendaraan_volume_cm3_sisa: $(
									"#deliveryorderbatch-kendaraan_volume_cm3_sisa")
								.val(),
							kendaraan_berat_gr_sisa: $(
									"#deliveryorderbatch-kendaraan_berat_gr_sisa")
								.val(),
							kendaraan_km_awal: 0,
							kendaraan_km_akhir: 0,
							kendaraan_km_terpakai: 0,
							picking_list_id: "",
							picking_order_id: "",
							serah_terima_kirim_id: "",
							list_do: arr_list_do,
							beratBatch: beratBatch,
							volumeBatch: volumeBatch,
							ritasiID: $("#ritasiID").val(),
							tglLastUpdate: $("#tglLastUpdate").val(),
						}, "POST", "JSON", function(data) {
							if (data.status == 400) {
								return messageNotSameLastUpdated('WMS/Distribusi/DeliveryOrderBatch/index');
							}

							if (data.status == 200) {
								message_topright("success",
									data.message);

								ResetTemp();

								setTimeout(() => {
									location.href =
										"<?= base_url(); ?>WMS/Distribusi/DeliveryOrderBatch/detail/?id=" +
										$(
											"#deliveryorderbatch-delivery_order_batch_id"
										)
										.val();
								}, 2000);
							} else {
								// message_topright("error", "Data gagal disimpan");
								var msg = data.message;
								var msgtype = 'error';

								//if (!window.__cfRLUnblockHandlers) return false;
								new PNotify
									({
										title: 'Info',
										text: msg,
										type: msgtype,
										styling: 'bootstrap3',
										delay: 2000
									});

							}
						});

						arr_list_do = [];
						arr_del_do = [];
					} else {
						arr_list_do = [];
					}
				});

			} else {
				message("Pilih SKU!", "SKU belum dipilih", "error");
				return false;
			}

		} else {
			cek_error = 0;
			message("Error!", "Data Gagal Disimpan!", "error");
			return false;
		}

	});

	$("#btnrejectdodraft").on("click", function() {
		var dod_id = $("#deliveryorderdraft-delivery_order_draft_id").val();

		Swal.fire({
			title: "Apakah anda yakin membatalkan data ini?",
			text: "Data yang sudah dibatalkan tidak bisa diubah!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Simpan",
			cancelButtonText: "Tidak, Tutup"
		}).then((result) => {
			if (result.value == true) {
				//ajax save data
				$.ajax({
					async: false,
					url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/reject_delivery_order_draft'); ?>",
					type: "POST",
					data: {
						delivery_order_draft_id: dod_id
					},
					dataType: "JSON",
					success: function(data) {
						if (data == 1) {
							message_topright("success", "Data berhasil dibatalkan");
							setTimeout(() => {
								location.href =
									"<?= base_url(); ?>WMS/Distribusi/DeliveryOrderDraft/detail/?id=" +
									dod_id;
							}, 500);
						} else {
							message_topright("error", "Data gagal dibatalkan");
						}
					}
				});
			}
		});
	});

	$("#btnbatalkandobatch").on("click", function() {
		Swal.fire({
			title: "Apakah anda yakin membatalkan data ini?",
			text: "Data yang sudah dibatalkan akan hilang!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Simpan",
			cancelButtonText: "Tidak, Tutup"
		}).then((result) => {
			if (result.value == true) {
				//ajax save data
				requestAjax("<?= base_url('WMS/Distribusi/DeliveryOrderBatch/batalkan_do_batch'); ?>", {
					delivery_order_batch_id: $(
							"#deliveryorderbatch-delivery_order_batch_id")
						.val(),
					tglLastUpdate: $("#tglLastUpdate").val()
				}, "POST", "JSON", function(data) {
					if (data.status == 400) {
						return messageNotSameLastUpdated('WMS/Distribusi/DeliveryOrderBatch/index');
					}

					if (data.status == 200) {
						message_topright("success",
							data.message);

						ResetTemp();

						setTimeout(() => {
							location.href =
								"<?= base_url(); ?>WMS/Distribusi/DeliveryOrderBatch/index"
						}, 2000);
					} else {
						// message_topright("error", "Data gagal disimpan");
						var msg = data.message;
						var msgtype = 'error';

						//if (!window.__cfRLUnblockHandlers) return false;
						new PNotify
							({
								title: 'Info',
								text: msg,
								type: msgtype,
								styling: 'bootstrap3',
								delay: 2000
							});

					}
				});
			}
		});
	})

	function ViewDetailDO(ID_temp_do, nama_do, alamat_do) {
		// console.log(sku_nama_produk);
		$("#modal-do-draft-by-alamat").modal('show');

		$("#table-do-draft-by-alamat > tbody").empty();

		$("#nama_do").html('');
		$("#alamat_do").html('');

		$("#nama_do").append(nama_do);
		$("#alamat_do").append(alamat_do);

		$.ajax({
			async: false,
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetIDDOByBatch'); ?>",
			type: "POST",
			data: {
				temp_do: "'" + ID_temp_do + "'",
				alamat_do: ["'" + alamat_do + "'"],
				nama_do: ["'" + nama_do + "'"]
			},
			dataType: "JSON",
			success: function(response) {
				$.each(response, function(i, v) {
					// <td class="text-center">
					// <button class = "btn btn-danger btn-sm btn-delete-do-draft"
					// onclick = "DeleteDODraftByTemp(this,${i},'${v.delivery_order_temp_id}', '${v.delivery_order_draft_id}', '${v.delivery_order_draft_kirim_alamat}', '${v.delivery_order_batch_id}', '${v.delivery_order_draft_kirim_nama}')" > < i class = "fa fa-trash" > < /i></button > < /td>
					$("#table-do-draft-by-alamat > tbody").append(`
							<tr id="row-${i}" class="ui-sortable-handle">
								<td class="text-center">
									<input type="checkbox" name="CheckboxDetailDO" id="CheckboxDetailDO-${i}" class="CheckboxDetailDO" value="${v.delivery_order_temp_id}_${v.delivery_order_draft_id}_${v.delivery_order_draft_kirim_alamat}_${v.delivery_order_batch_id}_${v.delivery_order_draft_kirim_nama}">
								</td>
								<td class="text-center">${v.delivery_order_draft_tgl_buat_do}</td>
								<td class="text-center">${v.tgl_kirim}</td>
								<td class="text-center">${v.sales_order_no_po}</td>
								<td class="text-center">${v.principle_kode}</td>
								<td class="text-center">${v.delivery_order_draft_kode}</td>
								<td class="text-center">${v.tipe_delivery_order_alias}</td>
								<td class="text-center">${v.kirim_ulang == null ? 'Tidak' : 'Ya'}</td>
								<td class="text-center">${v.composite}</td>
							</tr>
						`);
				});
			}
		});
	}

	function GetLocation(alamat) {
		var vrbl_location = $('#vrbl_location').val();
		var urlAlamat = encodeURIComponent(alamat);

		if (vrbl_location == 'search') {
			window.open("https://www.google.com/maps/search/" + urlAlamat + "", "_blank");
		} else {
			window.open("https://www.google.com/maps/place/" + urlAlamat + "", "_blank");
		}
	}

	function OnlyViewDetailDO(ID_batch_do, nama_do, alamat_do) {
		// console.log(sku_nama_produk);
		$("#modal-do-by-alamat").modal('show');

		$("#table-do-by-alamat > tbody").empty();

		$("#nama_do").html('');
		$("#alamat_do").html('');

		$("#nama_do").append(nama_do);
		$("#alamat_do").append(alamat_do);

		$.ajax({
			async: false,
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetDetailIDDOByBatch'); ?>",
			type: "POST",
			data: {
				batch_do: "'" + ID_batch_do + "'",
				alamat_do: "'" + alamat_do + "'"
			},
			dataType: "JSON",
			success: function(response) {
				$.each(response, function(i, v) {
					$("#table-do-by-alamat > tbody").append(`
							<tr id="row-${i}" class="ui-sortable-handle">
								<td class="text-center">${v.delivery_order_tgl_buat_do}</td>
								<td class="text-center">${v.tgl_kirim}</td>
								<td class="text-center">${v.sales_order_no_po}</td>
								<td class="text-center">${v.principle_kode}</td>
								<td class="text-center">${v.delivery_order_kode}</td>
								<td class="text-center">${v.tipe_delivery_order_alias}</td>
								<td class="text-center">${v.kirim_ulang == null ? 'Tidak' : 'Ya'}</td>
								<td class="text-center">${v.composite}</td>
								</tr>
						`);
				});
			}
		});
	}

	function ViewSKU(sku_id, sku_kode, sku_nama_produk) {
		// console.log(sku_nama_produk);
		$("#modal-do-draft-sku").modal('show');

		$("#table-summary-sku-detail > tbody").empty();

		$("#kode-sku").html('');
		$("#sku-nama-produk").html('');

		$("#kode-sku").append(sku_kode);
		$("#sku-nama-produk").append(sku_nama_produk);

		$.ajax({
			async: false,
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetSelectedDODraftSKUDetail'); ?>",
			type: "POST",
			data: {
				sku_id: sku_id,
				dod_id: arr_do
			},
			dataType: "JSON",
			success: function(response) {
				$.each(response, function(i, v) {
					$("#table-summary-sku-detail > tbody").append(`
							<tr id="row-${i}" class="ui-sortable-handle">
								<td></td>
								<td class="text-center">${v.delivery_order_draft_tgl_buat_do}</td>
								<td class="text-center">${v.delivery_order_draft_kode}</td>
								<td class="text-center">${v.delivery_order_draft_kirim_nama}</td>
								<td class="text-center">${v.delivery_order_draft_kirim_alamat}, ${v.delivery_order_draft_kirim_telp}</td>
								<td class="text-center">${v.delivery_order_draft_tipe_pembayaran}</td>
								<td class="text-center">${v.delivery_order_draft_tipe_layanan}</td>
								<td class="text-center">${v.tipe_delivery_order_alias}</td>
								<td class="text-center">${v.sku_qty}</td>
							</tr>
						`);
				});
			}
		});
	}

	function ViewSKUDO(sku_id, sku_kode, sku_nama_produk) {
		// console.log(sku_nama_produk);
		$("#modal-do-draft-sku").modal('show');

		$("#table-summary-sku-detail > tbody").empty();

		$("#kode-sku").html('');
		$("#sku-nama-produk").html('');

		$("#kode-sku").append(sku_kode);
		$("#sku-nama-produk").append(sku_nama_produk);

		$.ajax({
			async: false,
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetSelectedDOSKUDetail'); ?>",
			type: "POST",
			data: {
				sku_id: sku_id,
				fdjr_id: $("#deliveryorderbatch-delivery_order_batch_id").val()
			},
			dataType: "JSON",
			success: function(response) {
				$.each(response, function(i, v) {
					$("#table-summary-sku-detail > tbody").append(`
							<tr id="row-${i}" class="ui-sortable-handle">
								<td>${v.delivery_order_no_urut_rute}</td>
								<td class="text-center">${v.delivery_order_tgl_buat_do}</td>
								<td class="text-center">${v.delivery_order_kode}</td>
								<td class="text-center">${v.delivery_order_kirim_nama}</td>
								<td class="text-center">${v.delivery_order_kirim_alamat}, ${v.delivery_order_kirim_telp}</td>
								<td class="text-center">${v.delivery_order_tipe_pembayaran}</td>
								<td class="text-center">${v.delivery_order_tipe_layanan}</td>
								<td class="text-center">${v.tipe_delivery_order_alias}</td>
								<td class="text-center">${v.sku_qty}</td>
							</tr>
						`);
				});
			}
		});
	}

	function GetKendaraan(kendaraan) {
		var temp_do = '';
		var no = 0;
		var no2 = 0;
		var arr_no_urut_do = [];
		arr_do = [];
		arr_do_nama = [];

		$.ajax({
			async: false,
			type: 'POST',
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetKapasitasKendaraan') ?>",
			data: {
				kendaraan: kendaraan
			},
			dataType: "JSON",
			success: function(response) {
				if (response != 0) {
					$.each(response, function(i, v) {
						$("#deliveryorderbatch-kendaraan_berat_gr_max").val(parseInt(v
							.kendaraan_kap_weight));
						$("#deliveryorderbatch-kendaraan_volume_cm3_max").val(parseInt(v
							.kendaraan_kap_vol));
						$("#deliveryorderbatch-kendaraan_km_awal").val(parseInt(v
							.kendaraan_km_awal));
						$("#deliveryorderbatch-kendaraan_km_akhir").val(parseInt(v
							.kendaraan_km_akhir));
					});
				} else {
					$("#deliveryorderbatch-kendaraan_berat_gr_max").val(0);
					$("#deliveryorderbatch-kendaraan_volume_cm3_max").val(0);
					$("#deliveryorderbatch-kendaraan_km_awal").val(0);
					$("#deliveryorderbatch-kendaraan_km_akhir").val(0);
				}

				// var beratMaksimal = $("#deliveryorderbatch-kendaraan_berat_gr_max").val();
				// var volumeMaksimal = $("#deliveryorderbatch-kendaraan_volume_cm3_max").val();
				// var beratTerpakai = $("#deliveryorderbatch-kendaraan_berat_gr_terpakai").val();
				// var volumeTerpakai = $("#deliveryorderbatch-kendaraan_volume_cm3_terpakai").val();

				// $("#selisihKapasitasBerat").val(parseInt(beratMaksimal) - parseInt(beratTerpakai));
				// $("#selisihKapasitasVolume").val(parseInt(volumeMaksimal) - parseInt(volumeTerpakai))

				// if (beratMaksimal == 0 && volumeMaksimal == 0) {
				// 	var presentaseBeratTerpakai = 0;
				// 	var presentaseVolumeTerpakai = 0;
				// } else {
				// 	var presentaseBeratTerpakai = Math.floor(beratTerpakai / beratMaksimal * 100 * 100) / 100 //rumus mengambil 2 angka dibelakang koma
				// 	var presentaseVolumeTerpakai = Math.floor(volumeTerpakai / volumeMaksimal * 100 * 100) / 100 //rumus mengambil 2 angka dibelakang koma
				// }

				// $("#deliveryorderbatch-presentase_kendaraan_berat_gr_terpakai").val(presentaseBeratTerpakai + '%');
				// $("#deliveryorderbatch-presentase_kendaraan_volume_cm3_terpakai").val(presentaseVolumeTerpakai + '%');

				// if (Math.round(presentaseBeratTerpakai) > 100) {
				// 	$("#deliveryorderbatch-presentase_kendaraan_berat_gr_terpakai").attr("style", "background-color: rgb(255, 153, 153); color: black;")
				// } else {
				// 	$("#deliveryorderbatch-presentase_kendaraan_berat_gr_terpakai").removeAttr("style");
				// }

				// if (Math.round(presentaseVolumeTerpakai) > 100) {
				// 	$("#deliveryorderbatch-presentase_kendaraan_volume_cm3_terpakai").attr("style", "background-color: rgb(255, 153, 153); color: black;")
				// } else {
				// 	$("#deliveryorderbatch-presentase_kendaraan_berat_gr_terpakai").removeAttr("style");
				// }

				presentaseKapasitasTerpakai();

				selisihKapasitas();

				$("#table-do-draft tr:has(td)").each(function(idx) {
					temp_do = "'" + $(this).find("td:eq(12)").text().trim() + "'";
					var no_urut = "'" + $(this).find("td:eq(0)").text().trim() + "'";
					// var alamat_do = "'" + $(this).find("td:eq(3)").text().trim() + "'";
					var alamat_do = "'" + $(this).find("td:eq(2)").text() + "'";
					var nama_do = "'" + $(this).find("td:eq(1)").text() + "'";

					arr_do.push(alamat_do);
					arr_do_nama.push(nama_do);
					arr_no_urut_do.push({
						'alamat_do': alamat_do,
						'nama_do': nama_do,
						'no_urut': no_urut
					});
				});

				resetSimulasiKapasitas();

				// cek table jika melebihi batas maka row warna merah
				if (arr_do.length > 0) {
					appendOutlet(temp_do, arr_do, arr_do_nama, arr_no_urut_do)

					// $.ajax({
					// 	async: false,
					// 	type: 'POST',
					// 	url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetDOTempByIDTempWithNoUrut') ?>",
					// 	data: {
					// 		temp_do: temp_do,
					// 		alamat_do: arr_do,
					// 		nama_do: arr_do_nama,
					// 		no_urut: arr_no_urut_do,
					// 	},
					// 	dataType: "JSON",
					// 	success: function(response) {
					// 		if (response.length > 0) {

					// 			$("#table-do-draft > tbody").empty();

					// 			$("#table-do-draft > tbody").append(`
					// 		<tr>
					// 			<th class="text-center">Prioritas</th>
					// 			<th class="text-center">Nama</th>
					// 			<th class="text-center">Alamat</th>
					// 			<th class="text-center">Telp</th>
					// 			<th class="text-center">Jumlah DO</th>
					// 			<th class="text-center" hidden>Principle</th>
					// 			<th class="text-center">Kecamatan</th>
					// 			<th class="text-center">Area</th>
					// 			<th class="text-center">Latitude</th>
					// 			<th class="text-center">Longitude</th>
					// 			<th class="text-center">Berat(gram)</th>
					// 			<th class="text-center">Volume(cm)</th>
					// 			<th class="text-center" hidden>Kirim Ulang</th>
					// 			<th class="text-center">Composite</th>
					// 			<th class="text-center">Action</th>
					// 		</tr>
					// 	`);

					// 			// Get Kapasitas Berat dan Volume Armada
					// 			var beratArmada = $("#deliveryorderbatch-kendaraan_berat_gr_max")
					// 				.val();
					// 			var volumeArmada = $("#deliveryorderbatch-kendaraan_volume_cm3_max")
					// 				.val();
					// 			var totalBeratDO = 0;
					// 			var totalVolumeDO = 0;

					// 			$.each(response, function(i, v) {
					// 				totalBeratDO = totalBeratDO + parseInt(v.sku_weight);
					// 				totalVolumeDO = totalVolumeDO + parseInt(v.sku_volume);

					// 				if (totalBeratDO > beratArmada || totalVolumeDO >
					// 					volumeArmada) {
					// 					$("#table-do-draft > tbody").append(`
					// 		<tr id="row-${i}" class="ui-sortable-handle" style="background-color: #FF9999; color: black;">
					// 			<td class="text-center">
					// 				<span id="urutan-prioritas-${i}">${++no}</span>
					// 			</td>
					// 			<td style="display: none">
					// 				${v.delivery_order_temp_id}
					// 			</td>
					// 			<input type="hidden" class="latitudelongitude" data-nama="${v.delivery_order_draft_kirim_nama}" data-alamat="${v.delivery_order_draft_kirim_alamat}"  data-lat="${v.client_pt_latitude}" data-long="${v.client_pt_longitude}" data-prioritas="${++no2}"/>
					// 			<td class="text-center">${v.delivery_order_draft_kirim_nama}</td>
					// 			<td class="text-center">${v.delivery_order_draft_kirim_alamat}</td>
					// 			<td class="text-center">${v.delivery_order_draft_kirim_telp}</td>
					// 			<td class="text-center">${v.jumlah_do}</td>
					// 			<td class="text-center" hidden>${v.principle_kode}</td>
					// 			<td class="text-center">${v.delivery_order_draft_kirim_kecamatan}</td>
					// 			<td class="text-center">${v.delivery_order_draft_kirim_area}</td>
					// 			<td class="text-center dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_latitude" >${v.client_pt_latitude}</td>
					// 			<td class="text-center dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_longitude" >${v.client_pt_longitude}</td>
					// 			<td class="text-center">${parseInt(v.sku_weight)}</td>
					// 			<td class="text-center">${parseInt(v.sku_volume)}</td>
					// 			<td class="text-center" hidden>${v.kirim_ulang == null ? '' : 'Kirim Ulang'}</td>
					// 			<td class="text-center">${v.composite}</td>
					// 			<td class="text-center">
					// 			<button class="btn btn-success btn-sm btn-view-detail-do" onclick="ViewDetailDO('${v.delivery_order_temp_id}','${v.delivery_order_draft_kirim_nama}','${v.delivery_order_draft_kirim_alamat}')"><i class="fa fa-eye"></i></button>
					// 			<button class="btn btn-danger btn-sm btn-delete-do-draft" onclick="DeleteDODraft(this,${i},'${v.delivery_order_temp_id}', '${v.delivery_order_draft_kirim_alamat}','${v.delivery_order_draft_kirim_nama}')"><i class="fa fa-trash"></i></button>
					// 			<button class="btn btn-warning btn-sm btn-location" onclick="GetLocation('${v.delivery_order_draft_kirim_alamat}')"><i class="fa-sharp fa-solid fa-location-dot"></i></button></td>
					// 		</tr>
					// 	`);
					// 				} else {
					// 					$("#table-do-draft > tbody").append(`
					// 		<tr id="row-${i}" class="ui-sortable-handle">
					// 			<td class="text-center">
					// 				<span id="urutan-prioritas-${i}">${++no}</span>
					// 			</td>
					// 			<td style="display: none">
					// 				${v.delivery_order_temp_id}
					// 			</td>
					// 			<input type="hidden" class="latitudelongitude" data-nama="${v.delivery_order_draft_kirim_nama}" data-alamat="${v.delivery_order_draft_kirim_alamat}"  data-lat="${v.client_pt_latitude}" data-long="${v.client_pt_longitude}" data-prioritas="${++no2}"/>
					// 			<td class="text-center">${v.delivery_order_draft_kirim_nama}</td>
					// 			<td class="text-center">${v.delivery_order_draft_kirim_alamat}</td>
					// 			<td class="text-center">${v.delivery_order_draft_kirim_telp}</td>
					// 			<td class="text-center">${v.jumlah_do}</td>
					// 			<td class="text-center" hidden>${v.principle_kode}</td>
					// 			<td class="text-center">${v.delivery_order_draft_kirim_kecamatan}</td>
					// 			<td class="text-center">${v.delivery_order_draft_kirim_area}</td>
					// 			<td class="text-center dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_latitude" >${v.client_pt_latitude}</td>
					// 			<td class="text-center dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_longitude" >${v.client_pt_longitude}</td>
					// 			<td class="text-center">${parseInt(v.sku_weight)}</td>
					// 			<td class="text-center">${parseInt(v.sku_volume)}</td>
					// 			<td class="text-center" hidden>${v.kirim_ulang == null ? '' : 'Kirim Ulang'}</td>
					// 			<td class="text-center">${v.composite}</td>
					// 			<td class="text-center">
					// 			<button class="btn btn-success btn-sm btn-view-detail-do" onclick="ViewDetailDO('${v.delivery_order_temp_id}','${v.delivery_order_draft_kirim_nama}','${v.delivery_order_draft_kirim_alamat}')"><i class="fa fa-eye"></i></button>
					// 			<button class="btn btn-danger btn-sm btn-delete-do-draft" onclick="DeleteDODraft(this,${i},'${v.delivery_order_temp_id}', '${v.delivery_order_draft_kirim_alamat}','${v.delivery_order_draft_kirim_nama}')"><i class="fa fa-trash"></i></button>
					// 			<button class="btn btn-warning btn-sm btn-location" onclick="GetLocation('${v.delivery_order_draft_kirim_alamat}')"><i class="fa-sharp fa-solid fa-location-dot"></i></button></td>
					// 		</tr>
					// 	`);
					// 				}
					// 			});

					// 		} else {
					// 			arr_do = [];
					// 			arr_do_nama = [];
					// 			$("#table-do-draft > tbody").empty();
					// 		}

					// 	}
					// });
				}

			}
		});

		CekKapasitasiKendaraan();
	}

	function LihatPeta() {

		var llatlong = $(".latitudelongitude").length;

		if (llatlong == 0) {
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: C_TIDAKADADATA
			});

			return false;
		}



		$("#panel-maps").show('slow')

		// return false;

		// return false;

		var defaultcoordinate = [];
		var arrMaps = [];

		var pembagi = 1;
		arrMaps.length = 0;
		for (i = 0; i < llatlong; i++) {
			var latitude = $(".latitudelongitude").eq(i).attr('data-lat');
			var longitude = $(".latitudelongitude").eq(i).attr('data-long');
			var nama = $(".latitudelongitude").eq(i).attr('data-nama');
			var prioritas = $(".latitudelongitude").eq(i).attr('data-prioritas');

			arrMaps.push({
				prioritas,
				latitude,
				longitude,
				nama
			})
			// pembagi = 1;
		}

		document.getElementById('init-maps-container').innerHTML = '<div id="datamapdobatch" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="height: 70vh;"></div>';

		var map = L.map('datamapdobatch')
		map.setView([arrMaps[0].latitude, arrMaps[0].longitude], 12);
		L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 19
		}).addTo(map);

		let message = arrMaps.map((item) => {
			return `<div>
                <h5 style="font-weight:700">Outlet : ${item.nama}</h5>
                <h6 style="font-weight:600">Prioritas ${item.prioritas}</h6>
            </div>`
		});

		// L.Routing.control({
		//     createMarker: function(waypointIndex, waypoint) {
		//         return L.marker(waypoint.latLng)
		//             .bindPopup(message[waypointIndex]).openPopup();
		//     },
		//     show: false,
		//     addWaypoints: false,
		//     draggableWaypoints: false,
		//     waypoints: arrMaps.map((item) => {
		//         return L.latLng(item.latitude, item.longitude)
		//     }),
		//     routeWhileDragging: false,
		//     // router: new L.Routing.OSRMv1({
		//     //     serviceUrl: '//router.project-osrm.org/viaroute'
		//     // })
		// }).addTo(map);

		arrMaps.map((item, index) => {
			return L.marker([item.latitude, item.longitude]).addTo(map);
			// return L.marker([item.latitude, item.longitude]).bindPopup(message[index]).openPopup().addTo(map);
		})

		arrMaps.map((item, index) => {
			return L.tooltip([item.latitude, item.longitude], {
					content: message[index],
					permanent: true,
					sticky: true
				})
				.addTo(map)
		})



		setTimeout(function() {
			map.invalidateSize()
		}, 500);



		// window.open("<?= base_url('WMS/Distribusi/DeliveryOrderBatch/lihat_map?datas=') ?>" + encodeURI(JSON.stringify(arrMaps)), '_blank');
		// return false;


		// defaultcoordinate = [latitude, longitude];

		// $("#modal-map").on('shown', function() {
		//     setTimeout(function() {
		//         map.invalidateSize()
		//     }, 500);
		// });

		// $("#modal-map").modal('show');
	}

	const handlerClosePanelMaps = () => {
		$("#init-maps-container").empty();
		$("#panel-maps").hide('slow')
	}

	// function closeMaps() {

	// }

	function checkMuatan() {
		var temp_do = '';
		var no = 0;
		var no2 = 0;
		var arr_no_urut_do = [];
		arr_do = [];
		arr_do_nama = [];

		$("#table-do-draft tr:has(td)").each(function(idx) {
			temp_do = "'" + $(this).find("td:eq(12)").text().trim() + "'";
			var no_urut = "'" + $(this).find("td:eq(0)").text().trim() + "'";
			// var alamat_do = "'" + $(this).find("td:eq(3)").text().trim() + "'";
			var alamat_do = "'" + $(this).find("td:eq(2)").text() + "'";
			var nama_do = "'" + $(this).find("td:eq(1)").text() + "'";

			arr_do.push(alamat_do);
			arr_do_nama.push(nama_do);
			arr_no_urut_do.push({
				'alamat_do': alamat_do,
				'nama_do': nama_do,
				'no_urut': no_urut
			});
		});

		if (arr_do.length == 0) {
			message("Error!", "Pilih DO terlebih dahulu!", "error");
			return false;
		}

		appendOutlet(temp_do, arr_do, arr_do_nama, arr_no_urut_do)

		// cek table jika melebihi batas maka row warna merah
		// $.ajax({
		// 	async: false,
		// 	type: 'POST',
		// 	url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetDOTempByIDTempWithNoUrut') ?>",
		// 	data: {
		// 		temp_do: temp_do,
		// 		alamat_do: arr_do,
		// 		nama_do: arr_do_nama,
		// 		no_urut: arr_no_urut_do,
		// 	},
		// 	dataType: "JSON",
		// 	success: function(response) {
		// 		if (response.length > 0) {

		// 			$("#table-do-draft > tbody").empty();

		// 			$("#table-do-draft > tbody").append(`
		//             		<tr>
		//             			<th class="text-center">Prioritas</th>
		//             			<th class="text-center">Nama</th>
		//             			<th class="text-center">Alamat</th>
		//             			<th class="text-center">Telp</th>
		// 						<th class="text-center">Jumlah DO</th>
		// 						<th class="text-center" hidden>Principle</th>
		// 						<th class="text-center">Kecamatan</th>
		// 						<th class="text-center">Area</th>
		//             			<th class="text-center">Latitude</th>
		//             			<th class="text-center">Longitude</th>
		//             			<th class="text-center">Berat(gram)</th>
		//             			<th class="text-center">Volume(cm<sup>3</sup>)</th>
		// 						<th class="text-center" hidden>Kirim Ulang</th>
		// 						<th class="text-center">Composite</th>
		//             			<th class="text-center">Action</th>
		//             		</tr>
		//             	`);

		// 			// Get Kapasitas Berat dan Volume Armada
		// 			var beratArmada = $("#deliveryorderbatch-kendaraan_berat_gr_max").val();
		// 			var volumeArmada = $("#deliveryorderbatch-kendaraan_volume_cm3_max")
		// 				.val();
		// 			var totalBeratDO = 0;
		// 			var totalVolumeDO = 0;

		// 			$.each(response, function(i, v) {
		// 				totalBeratDO = totalBeratDO + parseInt(v.sku_weight);
		// 				totalVolumeDO = totalVolumeDO + parseInt(v.sku_volume);

		// 				if (totalBeratDO > beratArmada || totalVolumeDO >
		// 					volumeArmada) {
		// 					$("#table-do-draft > tbody").append(`
		//     				<tr id="row-${i}" class="ui-sortable-handle" style="background-color: #FF9999; color: black;">
		//     					<td class="text-center">
		//     						<span id="urutan-prioritas-${i}">${++no}</span>
		//     					</td>
		//     					<td style="display: none">
		//     						${v.delivery_order_temp_id}
		//     					</td>
		// 						<input type="hidden" class="latitudelongitude" data-nama="${v.delivery_order_draft_kirim_nama}" data-alamat="${v.delivery_order_draft_kirim_alamat}"  data-lat="${v.client_pt_latitude}" data-long="${v.client_pt_longitude}" data-prioritas="${++no2}"/>
		//     					<td class="text-center">${v.delivery_order_draft_kirim_nama}</td>
		//     					<td class="text-center">${v.delivery_order_draft_kirim_alamat}</td>
		//     					<td class="text-center">${v.delivery_order_draft_kirim_telp}</td>
		// 						<td class="text-center">${v.jumlah_do}</td>
		// 						<td class="text-center" hidden>${v.principle_kode}</td>
		// 						<td class="text-center">${v.delivery_order_draft_kirim_kecamatan}</td>
		// 						<td class="text-center">${v.delivery_order_draft_kirim_area}</td>
		//     					<td class="text-center dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_latitude" >${v.client_pt_latitude}</td>
		//     					<td class="text-center dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_longitude" >${v.client_pt_longitude}</td>
		// 						<td class="text-center">${parseInt(v.sku_weight)}</td>
		// 						<td class="text-center">${parseInt(v.sku_volume)}</td>
		// 						<td class="text-center" hidden>${v.kirim_ulang == null ? '' : 'Kirim Ulang'}</td>
		// 						<td class="text-center">${v.composite}</td>
		//     					<td class="text-center">
		//     					<button class="btn btn-success btn-sm btn-view-detail-do" onclick="ViewDetailDO('${v.delivery_order_temp_id}','${v.delivery_order_draft_kirim_nama}','${v.delivery_order_draft_kirim_alamat}')"><i class="fa fa-eye"></i></button>
		//     					<button class="btn btn-danger btn-sm btn-delete-do-draft" onclick="DeleteDODraft(this,${i},'${v.delivery_order_temp_id}', '${v.delivery_order_draft_kirim_alamat}','${v.delivery_order_draft_kirim_nama}')"><i class="fa fa-trash"></i></button>
		// 						<button class="btn btn-warning btn-sm btn-location" onclick="GetLocation('${v.delivery_order_draft_kirim_alamat}')"><i class="fa-sharp fa-solid fa-location-dot"></i></button></td>
		//     				</tr>
		//     			`);
		// 				} else {
		// 					$("#table-do-draft > tbody").append(`
		//     				<tr id="row-${i}" class="ui-sortable-handle">
		//     					<td class="text-center">
		//     						<span id="urutan-prioritas-${i}">${++no}</span>
		//     					</td>
		//     					<td style="display: none">
		//     						${v.delivery_order_temp_id}
		//     					</td>
		// 						<input type="hidden" class="latitudelongitude" data-nama="${v.delivery_order_draft_kirim_nama}" data-alamat="${v.delivery_order_draft_kirim_alamat}"  data-lat="${v.client_pt_latitude}" data-long="${v.client_pt_longitude}" data-prioritas="${++no2}" />
		//     					<td class="text-center">${v.delivery_order_draft_kirim_nama}</td>
		//     					<td class="text-center">${v.delivery_order_draft_kirim_alamat}</td>
		//     					<td class="text-center">${v.delivery_order_draft_kirim_telp}</td>
		// 						<td class="text-center">${v.jumlah_do}</td>
		// 						<td class="text-center" hidden>${v.principle_kode}</td>
		// 						<td class="text-center">${v.delivery_order_draft_kirim_kecamatan}</td>
		// 						<td class="text-center">${v.delivery_order_draft_kirim_area}</td>
		//     					<td class="text-center dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_latitude" >${v.client_pt_latitude}</td>
		//     					<td class="text-center dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_longitude" >${v.client_pt_longitude}</td>
		// 						<td class="text-center">${parseInt(v.sku_weight)}</td>
		// 						<td class="text-center">${parseInt(v.sku_volume)}</td>
		// 						<td class="text-center" hidden>${v.kirim_ulang == null ? '' : 'Kirim Ulang'}</td>
		// 						<td class="text-center">${v.composite}</td>
		//     					<td class="text-center">
		//     					<button class="btn btn-success btn-sm btn-view-detail-do" onclick="ViewDetailDO('${v.delivery_order_temp_id}','${v.delivery_order_draft_kirim_nama}','${v.delivery_order_draft_kirim_alamat}')"><i class="fa fa-eye"></i></button>
		//     					<button class="btn btn-danger btn-sm btn-delete-do-draft" onclick="DeleteDODraft(this,${i},'${v.delivery_order_temp_id}', '${v.delivery_order_draft_kirim_alamat}','${v.delivery_order_draft_kirim_nama}')"><i class="fa fa-trash"></i></button>
		// 						<button class="btn btn-warning btn-sm btn-location" onclick="GetLocation('${v.delivery_order_draft_kirim_alamat}')"><i class="fa-sharp fa-solid fa-location-dot"></i></button></td>
		//     				</tr>
		//     			`);
		// 				}
		// 			});

		// 			// dblclick();

		// 		} else {
		// 			arr_do = [];
		// 			arr_do_nama = [];
		// 			$("#table-do-draft > tbody").empty();
		// 		}

		// 	}
		// });

	}

	// function DeleteDODraftByTemp(row, index, do_temp_id, do_id, alamat_do, batch_id, nama_do) {
	// 	var row = row.parentNode.parentNode;
	// 	var no = 0;
	// 	var no2 = 0;
	// 	Swal.fire({
	// 		title: "Apakah anda yakin?",
	// 		text: "Data yang dihapus akan hilang!",
	// 		icon: "warning",
	// 		showCancelButton: true,
	// 		confirmButtonColor: "#3085d6",
	// 		cancelButtonColor: "#d33",
	// 		confirmButtonText: "Ya, Simpan",
	// 		cancelButtonText: "Tidak, Tutup"
	// 	}).then((result) => {
	// 		if (result.value == true) {

	// 			$.ajax({
	// 				async: false,
	// 				type: 'POST',
	// 				url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/DeleteDOTempByAlamat') ?>",
	// 				data: {
	// 					do_id: ["'" + do_id + "'"],
	// 					temp_do: "'" + do_temp_id + "'",
	// 					batch_id: "'" + batch_id + "'"
	// 				},
	// 				dataType: "JSON",
	// 				success: function(response) {
	// 					if (response == 1) {
	// 						row.parentNode.removeChild(row);
	// 					}

	// 				}
	// 			});

	// 			var temp_do = '';
	// 			var arr_no_urut_do = [];
	// 			arr_do = [];
	// 			arr_do_nama = [];
	// 			$("#table-do-draft tr:has(td)").each(function(idx) {
	// 				temp_do = "'" + $(this).find("td:eq(12)").text().trim() + "'";
	// 				var no_urut = "'" + $(this).find("td:eq(0)").text().trim() + "'";
	// 				// var alamat_do = "'" + $(this).find("td:eq(3)").text().trim() + "'";
	// 				var nama_do = "'" + $(this).find("td:eq(1)").text() + "'";
	// 				var alamat_do = "'" + $(this).find("td:eq(2)").text() + "'";
	// 				arr_do.push(alamat_do);
	// 				arr_do_nama.push(nama_do);
	// 				arr_no_urut_do.push({
	// 					'alamat_do': alamat_do,
	// 					'nama_do': nama_do,
	// 					'no_urut': no_urut
	// 				});
	// 			});

	// 			appendOutlet(temp_do, arr_do, arr_do_nama, arr_no_urut_do);

	// 			// GET ID DO_DRAFT BY BATCH DI TABEL DELIVERY_ORDER_TEMP Menampilkan table
	// 			// cek table jika melebihi batas maka row warna merah
	// 			// $.ajax({
	// 			// 	async: false,
	// 			// 	type: 'POST',
	// 			// 	url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetDOTempByIDTempWithNoUrut') ?>",
	// 			// 	data: {
	// 			// 		temp_do: temp_do,
	// 			// 		alamat_do: arr_do,
	// 			// 		nama_do: arr_do_nama,
	// 			// 		no_urut: arr_no_urut_do,
	// 			// 	},
	// 			// 	dataType: "JSON",
	// 			// 	success: function(response) {
	// 			// 		if (response.length > 0) {

	// 			// 			$("#table-do-draft > tbody").empty();

	// 			// 			$("#table-do-draft > tbody").append(`
	// 			//     		<tr>
	// 			//     			<th class="text-center">Prioritas</th>
	// 			//     			<th class="text-center">Nama</th>
	// 			//     			<th class="text-center">Alamat</th>
	// 			//     			<th class="text-center">Telp</th>
	// 			// 				<th class="text-center">Jumlah DO</th>
	// 			// 				<th class="text-center" hidden>Principle</th>
	// 			// 				<th class="text-center">Kecamatan</th>
	// 			// 				<th class="text-center">Area</th>
	// 			//     			<th class="text-center">Latitude</th>
	// 			//     			<th class="text-center">Longitude</th>
	// 			//     			<th class="text-center">Berat(gram)</th>
	// 			//     			<th class="text-center">Volume(cm)</th>
	// 			// 				<th class="text-center" hidden>Kirim Ulang</th>
	// 			// 				<th class="text-center">Composite</th>
	// 			//     			<th class="text-center">Action</th>
	// 			//     		</tr>
	// 			//     	`);

	// 			// 			// Get Kapasitas Berat dan Volume Armada
	// 			// 			var beratArmada = $("#deliveryorderbatch-kendaraan_berat_gr_max").val();
	// 			// 			var volumeArmada = $("#deliveryorderbatch-kendaraan_volume_cm3_max")
	// 			// 				.val();
	// 			// 			var totalBeratDO = 0;
	// 			// 			var totalVolumeDO = 0;

	// 			// 			$.each(response, function(i, v) {
	// 			// 				totalBeratDO = totalBeratDO + parseInt(v.sku_weight);
	// 			// 				totalVolumeDO = totalVolumeDO + parseInt(v.sku_volume);

	// 			// 				if (totalBeratDO > beratArmada || totalVolumeDO >
	// 			// 					volumeArmada) {
	// 			// 					$("#table-do-draft > tbody").append(`
	// 			// 			<tr id="row-${i}" class="ui-sortable-handle" style="background-color: #FF9999; color: black;">
	// 			// 				<td class="text-center">
	// 			// 					<span id="urutan-prioritas-${i}">${++no}</span>
	// 			// 				</td>
	// 			// 				<td style="display: none">
	// 			// 					${v.delivery_order_temp_id}
	// 			// 				</td>
	// 			// 				<input type="hidden" class="latitudelongitude" data-nama="${v.delivery_order_draft_kirim_nama}" data-alamat="${v.delivery_order_draft_kirim_alamat}"  data-lat="${v.client_pt_latitude}" data-long="${v.client_pt_longitude}" data-prioritas="${++no2}"/>
	// 			// 				<td class="text-center">${v.delivery_order_draft_kirim_nama}</td>
	// 			// 				<td class="text-center">${v.delivery_order_draft_kirim_alamat}</td>
	// 			// 				<td class="text-center">${v.delivery_order_draft_kirim_telp}</td>
	// 			// 				<td class="text-center">${v.jumlah_do}</td>
	// 			// 				<td class="text-center" hidden>${v.principle_kode}</td>
	// 			// 				<td class="text-center">${v.delivery_order_draft_kirim_kecamatan}</td>
	// 			// 				<td class="text-center">${v.delivery_order_draft_kirim_area}</td>
	// 			// 				<td class="text-center dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_latitude" >${v.client_pt_latitude}</td>
	// 			// 				<td class="text-center dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_longitude" >${v.client_pt_longitude}</td>
	// 			// 				<td class="text-center">${parseInt(v.sku_weight)}</td>
	// 			// 				<td class="text-center">${parseInt(v.sku_volume)}</td>
	// 			// 				<td class="text-center" hidden>${v.kirim_ulang == null ? '' : 'Kirim Ulang'}</td>
	// 			// 				<td class="text-center">${v.composite}</td>
	// 			// 				<td class="text-center">
	// 			// 				<button class="btn btn-success btn-sm btn-view-detail-do" onclick="ViewDetailDO('${v.delivery_order_temp_id}','${v.delivery_order_draft_kirim_nama}','${v.delivery_order_draft_kirim_alamat}')"><i class="fa fa-eye"></i></button>
	// 			// 				<button class="btn btn-danger btn-sm btn-delete-do-draft" onclick="DeleteDODraft(this,${i},'${v.delivery_order_temp_id}', '${v.delivery_order_draft_kirim_alamat}','${v.delivery_order_draft_kirim_nama}')"><i class="fa fa-trash"></i></button>
	// 			// 				<button class="btn btn-warning btn-sm btn-location" onclick="GetLocation('${v.delivery_order_draft_kirim_alamat}')"><i class="fa-sharp fa-solid fa-location-dot"></i></button></td>
	// 			// 			</tr>
	// 			// 		`);
	// 			// 				} else {
	// 			// 					$("#table-do-draft > tbody").append(`
	// 			// 			<tr id="row-${i}" class="ui-sortable-handle">
	// 			// 				<td class="text-center">
	// 			// 					<span id="urutan-prioritas-${i}">${++no}</span>
	// 			// 				</td>
	// 			// 				<td style="display: none">
	// 			// 					${v.delivery_order_temp_id}
	// 			// 				</td>
	// 			// 				<input type="hidden" class="latitudelongitude" data-nama="${v.delivery_order_draft_kirim_nama}" data-alamat="${v.delivery_order_draft_kirim_alamat}"  data-lat="${v.client_pt_latitude}" data-long="${v.client_pt_longitude}" data-prioritas="${++no2}"/>
	// 			// 				<td class="text-center">${v.delivery_order_draft_kirim_nama}</td>
	// 			// 				<td class="text-center">${v.delivery_order_draft_kirim_alamat}</td>
	// 			// 				<td class="text-center">${v.delivery_order_draft_kirim_telp}</td>
	// 			// 				<td class="text-center">${v.jumlah_do}</td>
	// 			// 				<td class="text-center" hidden>${v.principle_kode}</td>
	// 			// 				<td class="text-center">${v.delivery_order_draft_kirim_kecamatan}</td>
	// 			// 				<td class="text-center">${v.delivery_order_draft_kirim_area}</td>
	// 			// 				<td class="text-center dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_latitude" >${v.client_pt_latitude}</td>
	// 			// 				<td class="text-center dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_longitude" >${v.client_pt_longitude}</td>
	// 			// 				<td class="text-center">${parseInt(v.sku_weight)}</td>
	// 			// 				<td class="text-center">${parseInt(v.sku_volume)}</td>
	// 			// 				<td class="text-center" hidden>${v.kirim_ulang == null ? '' : 'Kirim Ulang'}</td>
	// 			// 				<td class="text-center">${v.composite}</td>
	// 			// 				<td class="text-center">
	// 			// 				<button class="btn btn-success btn-sm btn-view-detail-do" onclick="ViewDetailDO('${v.delivery_order_temp_id}','${v.delivery_order_draft_kirim_nama}','${v.delivery_order_draft_kirim_alamat}')"><i class="fa fa-eye"></i></button>
	// 			// 				<button class="btn btn-danger btn-sm btn-delete-do-draft" onclick="DeleteDODraft(this,${i},'${v.delivery_order_temp_id}', '${v.delivery_order_draft_kirim_alamat}','${v.delivery_order_draft_kirim_nama}')"><i class="fa fa-trash"></i></button>
	// 			// 				<button class="btn btn-warning btn-sm btn-location" onclick="GetLocation('${v.delivery_order_draft_kirim_alamat}')"><i class="fa-sharp fa-solid fa-location-dot"></i></button></td>
	// 			// 			</tr>
	// 			// 		`);
	// 			// 				}
	// 			// 			});

	// 			// 		} else {
	// 			// 			arr_do = [];
	// 			// 			arr_do_nama = [];
	// 			// 			$("#table-do-draft > tbody").empty();
	// 			// 		}

	// 			// 	}
	// 			// });

	// 			// GET ID DO_DRAFT BY BATCH DI TABEL DELIVERY_ORDER_TEMP
	// 			$.ajax({
	// 				async: false,
	// 				type: 'POST',
	// 				url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetIDDOByBatch') ?>",
	// 				data: {
	// 					alamat_do: arr_do,
	// 					nama_do: arr_do_nama,
	// 					temp_do: temp_do
	// 				},
	// 				dataType: "JSON",
	// 				success: function(response) {
	// 					if (response.length > 0) {
	// 						arr_do = [];
	// 						$.each(response, function(i, v) {
	// 							arr_do.push("'" + v.delivery_order_draft_id + "'");
	// 						});

	// 					} else {
	// 						arr_do = [];
	// 						$("#table-do-draft > tbody").empty();
	// 					}

	// 				}
	// 			});

	// 			// console.log(arr_do.length);

	// 			$("#table-summary-sku > tbody").empty();

	// 			resetSimulasiKapasitas();

	// 			if (arr_do.length > 0) {

	// 				appendSKU(arr_do);

	// 				// $.ajax({
	// 				// 	async: false,
	// 				// 	type: 'POST',
	// 				// 	url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetSelectedDODraftSKU') ?>",
	// 				// 	data: {
	// 				// 		do_id: arr_do
	// 				// 	},
	// 				// 	dataType: "JSON",
	// 				// 	success: function(response) {

	// 				// 		$.each(response.data, function(i, v) {
	// 				// 			$("#table-summary-sku > tbody").append(`
	// 				// 		<tr id="row-${i}" class="ui-sortable-handle">
	// 				// 			<td class="text-center">
	// 				// 				${v.sku_kode}
	// 				// 				<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-sku_id" value="${i}" />
	// 				// 			</td>
	// 				// 			<td class="text-center">${v.sku_nama_produk}</td>
	// 				// 			<td class="text-center">${v.sku_kemasan}</td>
	// 				// 			<td class="text-center">${v.sku_satuan}</td>
	// 				// 			<td class="text-center">${v.sku_qty}</td>
	// 				// 			<td class="text-center">${v.tipe_stock_nama}</td>
	// 				// 			<td class="text-center">${v.sku_request_expdate}</td>
	// 				// 			<td class="text-center">${v.sku_filter_expdate}</td>
	// 				// 			<td class="text-center">${v.sku_filter_expdatebulan}</td>
	// 				// 			<td class="text-center">
	// 				// 				<button class="btn btn-success btn-sm btn-view-sku" onclick="ViewSKU('${v.sku_id}', '${v.sku_kode}', '${v.sku_nama_produk}')"><i class="fa fa-eye"></i></button>
	// 				// 			</td>
	// 				// 		</tr>
	// 				// 	`);
	// 				// 		});

	// 				// 		$("#totalComposite").text(response.composite);
	// 				// 	}
	// 				// });

	// 				// $.ajax({
	// 				// 	async: false,
	// 				// 	type: 'POST',
	// 				// 	url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetKapasitasTerpakai') ?>",
	// 				// 	data: {
	// 				// 		do_id: arr_do
	// 				// 	},
	// 				// 	dataType: "JSON",
	// 				// 	success: function(response) {

	// 				// 		$.each(response, function(i, v) {
	// 				// 			$("#deliveryorderbatch-kendaraan_berat_gr_terpakai")
	// 				// 				.val(
	// 				// 					parseInt(v.sku_weight));
	// 				// 			$("#deliveryorderbatch-kendaraan_volume_cm3_terpakai")
	// 				// 				.val(
	// 				// 					parseInt(v.sku_volume));
	// 				// 		});

	// 				// 		selisihKapasitas();

	// 				// 		presentaseBeratTerpakai();
	// 				// 	}
	// 				// });

	// 				getKapasitasTerpakai();

	// 			} else {
	// 				$("#deliveryorderbatch-kendaraan_berat_gr_terpakai").val(0);
	// 				$("#deliveryorderbatch-presentase_kendaraan_berat_gr_terpakai").val('0%');
	// 				$("#deliveryorderbatch-presentase_kendaraan_berat_gr_terpakai").removeAttr("style");

	// 				$("#deliveryorderbatch-kendaraan_volume_cm3_terpakai").val(0);
	// 				$("#deliveryorderbatch-presentase_kendaraan_volume_cm3_terpakai").val('0%');
	// 				$("#deliveryorderbatch-presentase_kendaraan_volume_cm3_terpakai").removeAttr("style");

	// 				selisihKapasitas();

	// 				$("#totalComposite").text(0);
	// 			}

	// 			ResetForm();
	// 			HeaderReadonly();
	// 			CekKapasitasiKendaraan();
	// 		}
	// 	});
	// }

	function DeleteDODraftByTemp() {
		Swal.fire({
			title: "Apakah anda yakin?",
			text: "Data yang dihapus akan hilang!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Simpan",
			cancelButtonText: "Tidak, Tutup"
		}).then((result) => {
			if (result.value == true) {
				var no = 0;
				var no2 = 0;
				var tempID = '';
				var arrDOID = [];

				$('.CheckboxDetailDO:checked').each(function() {
					$split = $(this).val().split('_');

					tempID = "'" + $split[0] + "'";
					arrDOID.push("'" + $split[1] + "'");

					$(this).parent().parent().remove();
				})
				// console.log(arrDOID);
				// return false;

				$.ajax({
					async: false,
					type: 'POST',
					url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/DeleteDOTempByAlamat') ?>",
					data: {
						do_id: arrDOID,
						temp_do: tempID,
						// batch_id: "'" + batch_id + "'"
					},
					dataType: "JSON",
					success: function(response) {
						if (response == 1) {
							message_topright("success", "Data berhasil dihapus");
						}

					}
				});

				var temp_do = '';
				var arr_no_urut_do = [];
				arr_do = [];
				arr_do_nama = [];
				$("#table-do-draft tr:has(td)").each(function(idx) {
					temp_do = "'" + $(this).find("td:eq(12)").text().trim() + "'";
					var no_urut = "'" + $(this).find("td:eq(0)").text().trim() + "'";
					// var alamat_do = "'" + $(this).find("td:eq(3)").text().trim() + "'";
					var nama_do = "'" + $(this).find("td:eq(1)").text() + "'";
					var alamat_do = "'" + $(this).find("td:eq(2)").text() + "'";
					arr_do.push(alamat_do);
					arr_do_nama.push(nama_do);
					arr_no_urut_do.push({
						'alamat_do': alamat_do,
						'nama_do': nama_do,
						'no_urut': no_urut
					});
				});

				appendOutlet(temp_do, arr_do, arr_do_nama, arr_no_urut_do);

				// GET ID DO_DRAFT BY BATCH DI TABEL DELIVERY_ORDER_TEMP Menampilkan table
				// cek table jika melebihi batas maka row warna merah
				// $.ajax({
				// 	async: false,
				// 	type: 'POST',
				// 	url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetDOTempByIDTempWithNoUrut') ?>",
				// 	data: {
				// 		temp_do: temp_do,
				// 		alamat_do: arr_do,
				// 		nama_do: arr_do_nama,
				// 		no_urut: arr_no_urut_do,
				// 	},
				// 	dataType: "JSON",
				// 	success: function(response) {
				// 		if (response.length > 0) {

				// 			$("#table-do-draft > tbody").empty();

				// 			$("#table-do-draft > tbody").append(`
				//     		<tr>
				//     			<th class="text-center">Prioritas</th>
				//     			<th class="text-center">Nama</th>
				//     			<th class="text-center">Alamat</th>
				//     			<th class="text-center">Telp</th>
				// 				<th class="text-center">Jumlah DO</th>
				// 				<th class="text-center" hidden>Principle</th>
				// 				<th class="text-center">Kecamatan</th>
				// 				<th class="text-center">Area</th>
				//     			<th class="text-center">Latitude</th>
				//     			<th class="text-center">Longitude</th>
				//     			<th class="text-center">Berat(gram)</th>
				//     			<th class="text-center">Volume(cm)</th>
				// 				<th class="text-center" hidden>Kirim Ulang</th>
				// 				<th class="text-center">Composite</th>
				//     			<th class="text-center">Action</th>
				//     		</tr>
				//     	`);

				// 			// Get Kapasitas Berat dan Volume Armada
				// 			var beratArmada = $("#deliveryorderbatch-kendaraan_berat_gr_max").val();
				// 			var volumeArmada = $("#deliveryorderbatch-kendaraan_volume_cm3_max")
				// 				.val();
				// 			var totalBeratDO = 0;
				// 			var totalVolumeDO = 0;

				// 			$.each(response, function(i, v) {
				// 				totalBeratDO = totalBeratDO + parseInt(v.sku_weight);
				// 				totalVolumeDO = totalVolumeDO + parseInt(v.sku_volume);

				// 				if (totalBeratDO > beratArmada || totalVolumeDO >
				// 					volumeArmada) {
				// 					$("#table-do-draft > tbody").append(`
				// 			<tr id="row-${i}" class="ui-sortable-handle" style="background-color: #FF9999; color: black;">
				// 				<td class="text-center">
				// 					<span id="urutan-prioritas-${i}">${++no}</span>
				// 				</td>
				// 				<td style="display: none">
				// 					${v.delivery_order_temp_id}
				// 				</td>
				// 				<input type="hidden" class="latitudelongitude" data-nama="${v.delivery_order_draft_kirim_nama}" data-alamat="${v.delivery_order_draft_kirim_alamat}"  data-lat="${v.client_pt_latitude}" data-long="${v.client_pt_longitude}" data-prioritas="${++no2}"/>
				// 				<td class="text-center">${v.delivery_order_draft_kirim_nama}</td>
				// 				<td class="text-center">${v.delivery_order_draft_kirim_alamat}</td>
				// 				<td class="text-center">${v.delivery_order_draft_kirim_telp}</td>
				// 				<td class="text-center">${v.jumlah_do}</td>
				// 				<td class="text-center" hidden>${v.principle_kode}</td>
				// 				<td class="text-center">${v.delivery_order_draft_kirim_kecamatan}</td>
				// 				<td class="text-center">${v.delivery_order_draft_kirim_area}</td>
				// 				<td class="text-center dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_latitude" >${v.client_pt_latitude}</td>
				// 				<td class="text-center dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_longitude" >${v.client_pt_longitude}</td>
				// 				<td class="text-center">${parseInt(v.sku_weight)}</td>
				// 				<td class="text-center">${parseInt(v.sku_volume)}</td>
				// 				<td class="text-center" hidden>${v.kirim_ulang == null ? '' : 'Kirim Ulang'}</td>
				// 				<td class="text-center">${v.composite}</td>
				// 				<td class="text-center">
				// 				<button class="btn btn-success btn-sm btn-view-detail-do" onclick="ViewDetailDO('${v.delivery_order_temp_id}','${v.delivery_order_draft_kirim_nama}','${v.delivery_order_draft_kirim_alamat}')"><i class="fa fa-eye"></i></button>
				// 				<button class="btn btn-danger btn-sm btn-delete-do-draft" onclick="DeleteDODraft(this,${i},'${v.delivery_order_temp_id}', '${v.delivery_order_draft_kirim_alamat}','${v.delivery_order_draft_kirim_nama}')"><i class="fa fa-trash"></i></button>
				// 				<button class="btn btn-warning btn-sm btn-location" onclick="GetLocation('${v.delivery_order_draft_kirim_alamat}')"><i class="fa-sharp fa-solid fa-location-dot"></i></button></td>
				// 			</tr>
				// 		`);
				// 				} else {
				// 					$("#table-do-draft > tbody").append(`
				// 			<tr id="row-${i}" class="ui-sortable-handle">
				// 				<td class="text-center">
				// 					<span id="urutan-prioritas-${i}">${++no}</span>
				// 				</td>
				// 				<td style="display: none">
				// 					${v.delivery_order_temp_id}
				// 				</td>
				// 				<input type="hidden" class="latitudelongitude" data-nama="${v.delivery_order_draft_kirim_nama}" data-alamat="${v.delivery_order_draft_kirim_alamat}"  data-lat="${v.client_pt_latitude}" data-long="${v.client_pt_longitude}" data-prioritas="${++no2}"/>
				// 				<td class="text-center">${v.delivery_order_draft_kirim_nama}</td>
				// 				<td class="text-center">${v.delivery_order_draft_kirim_alamat}</td>
				// 				<td class="text-center">${v.delivery_order_draft_kirim_telp}</td>
				// 				<td class="text-center">${v.jumlah_do}</td>
				// 				<td class="text-center" hidden>${v.principle_kode}</td>
				// 				<td class="text-center">${v.delivery_order_draft_kirim_kecamatan}</td>
				// 				<td class="text-center">${v.delivery_order_draft_kirim_area}</td>
				// 				<td class="text-center dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_latitude" >${v.client_pt_latitude}</td>
				// 				<td class="text-center dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_longitude" >${v.client_pt_longitude}</td>
				// 				<td class="text-center">${parseInt(v.sku_weight)}</td>
				// 				<td class="text-center">${parseInt(v.sku_volume)}</td>
				// 				<td class="text-center" hidden>${v.kirim_ulang == null ? '' : 'Kirim Ulang'}</td>
				// 				<td class="text-center">${v.composite}</td>
				// 				<td class="text-center">
				// 				<button class="btn btn-success btn-sm btn-view-detail-do" onclick="ViewDetailDO('${v.delivery_order_temp_id}','${v.delivery_order_draft_kirim_nama}','${v.delivery_order_draft_kirim_alamat}')"><i class="fa fa-eye"></i></button>
				// 				<button class="btn btn-danger btn-sm btn-delete-do-draft" onclick="DeleteDODraft(this,${i},'${v.delivery_order_temp_id}', '${v.delivery_order_draft_kirim_alamat}','${v.delivery_order_draft_kirim_nama}')"><i class="fa fa-trash"></i></button>
				// 				<button class="btn btn-warning btn-sm btn-location" onclick="GetLocation('${v.delivery_order_draft_kirim_alamat}')"><i class="fa-sharp fa-solid fa-location-dot"></i></button></td>
				// 			</tr>
				// 		`);
				// 				}
				// 			});

				// 		} else {
				// 			arr_do = [];
				// 			arr_do_nama = [];
				// 			$("#table-do-draft > tbody").empty();
				// 		}

				// 	}
				// });

				// GET ID DO_DRAFT BY BATCH DI TABEL DELIVERY_ORDER_TEMP
				$.ajax({
					async: false,
					type: 'POST',
					url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetIDDOByBatch') ?>",
					data: {
						alamat_do: arr_do,
						nama_do: arr_do_nama,
						temp_do: temp_do
					},
					dataType: "JSON",
					success: function(response) {
						if (response.length > 0) {
							arr_do = [];
							$.each(response, function(i, v) {
								arr_do.push("'" + v.delivery_order_draft_id + "'");
							});

						} else {
							arr_do = [];
							$("#table-do-draft > tbody").empty();
						}

					}
				});

				// console.log(arr_do.length);

				$("#table-summary-sku > tbody").empty();

				resetSimulasiKapasitas();

				if (arr_do.length > 0) {

					appendSKU(arr_do);

					// $.ajax({
					// 	async: false,
					// 	type: 'POST',
					// 	url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetSelectedDODraftSKU') ?>",
					// 	data: {
					// 		do_id: arr_do
					// 	},
					// 	dataType: "JSON",
					// 	success: function(response) {

					// 		$.each(response.data, function(i, v) {
					// 			$("#table-summary-sku > tbody").append(`
					// 		<tr id="row-${i}" class="ui-sortable-handle">
					// 			<td class="text-center">
					// 				${v.sku_kode}
					// 				<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-sku_id" value="${i}" />
					// 			</td>
					// 			<td class="text-center">${v.sku_nama_produk}</td>
					// 			<td class="text-center">${v.sku_kemasan}</td>
					// 			<td class="text-center">${v.sku_satuan}</td>
					// 			<td class="text-center">${v.sku_qty}</td>
					// 			<td class="text-center">${v.tipe_stock_nama}</td>
					// 			<td class="text-center">${v.sku_request_expdate}</td>
					// 			<td class="text-center">${v.sku_filter_expdate}</td>
					// 			<td class="text-center">${v.sku_filter_expdatebulan}</td>
					// 			<td class="text-center">
					// 				<button class="btn btn-success btn-sm btn-view-sku" onclick="ViewSKU('${v.sku_id}', '${v.sku_kode}', '${v.sku_nama_produk}')"><i class="fa fa-eye"></i></button>
					// 			</td>
					// 		</tr>
					// 	`);
					// 		});

					// 		$("#totalComposite").text(response.composite);
					// 	}
					// });

					// $.ajax({
					// 	async: false,
					// 	type: 'POST',
					// 	url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetKapasitasTerpakai') ?>",
					// 	data: {
					// 		do_id: arr_do
					// 	},
					// 	dataType: "JSON",
					// 	success: function(response) {

					// 		$.each(response, function(i, v) {
					// 			$("#deliveryorderbatch-kendaraan_berat_gr_terpakai")
					// 				.val(
					// 					parseInt(v.sku_weight));
					// 			$("#deliveryorderbatch-kendaraan_volume_cm3_terpakai")
					// 				.val(
					// 					parseInt(v.sku_volume));
					// 		});

					// 		selisihKapasitas();

					// 		presentaseBeratTerpakai();
					// 	}
					// });

					getKapasitasTerpakai();

				} else {
					$("#deliveryorderbatch-kendaraan_berat_gr_terpakai").val(0);
					$("#deliveryorderbatch-presentase_kendaraan_berat_gr_terpakai").val('0%');
					$("#deliveryorderbatch-presentase_kendaraan_berat_gr_terpakai").removeAttr("style");

					$("#deliveryorderbatch-kendaraan_volume_cm3_terpakai").val(0);
					$("#deliveryorderbatch-presentase_kendaraan_volume_cm3_terpakai").val('0%');
					$("#deliveryorderbatch-presentase_kendaraan_volume_cm3_terpakai").removeAttr("style");

					selisihKapasitas();

					$("#totalComposite").text(0);
				}

				ResetForm();
				HeaderReadonly();
				CekKapasitasiKendaraan();
			}
		});
	}

	function allCheckedDetailDO(event) {
		if (event.checked) {
			$(`.CheckboxDetailDO`).prop('checked', true);
		} else {
			$(`.CheckboxDetailDO`).prop('checked', false);
		}
	}

	function selisihKapasitas() {
		var beratMaksimal = $("#deliveryorderbatch-kendaraan_berat_gr_max").val();
		var volumeMaksimal = $("#deliveryorderbatch-kendaraan_volume_cm3_max").val();
		var beratTerpakai = $("#deliveryorderbatch-kendaraan_berat_gr_terpakai").val();
		var volumeTerpakai = $("#deliveryorderbatch-kendaraan_volume_cm3_terpakai").val();

		$("#selisihKapasitasBerat").val(parseInt(beratMaksimal) - parseInt(beratTerpakai));
		$("#selisihKapasitasVolume").val(parseInt(volumeMaksimal) - parseInt(volumeTerpakai));
	}

	function DeleteEditDO(row, index, do_temp_id, do_id, alamat_do) {
		var row = row.parentNode.parentNode;
		var no = 1;
		var no2 = 1;
		Swal.fire({
			title: "Apakah anda yakin?",
			text: "Data yang dihapus akan hilang!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Simpan",
			cancelButtonText: "Tidak, Tutup"
		}).then((result) => {
			if (result.value == true) {

				$.ajax({
					async: false,
					type: 'POST',
					url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/DeleteDOTempByAlamat') ?>",
					data: {
						do_id: ["'" + do_id + "'"],
						temp_do: "'" + do_temp_id + "'"
					},
					dataType: "JSON",
					success: function(response) {
						if (response == 1) {
							row.parentNode.removeChild(row);
						}

					}
				});

				var temp_do = '';
				arr_do = [];
				$("#table-do-draft tr:has(td)").each(function(idx) {
					temp_do = "'" + $(this).find("td:eq(12)").text().trim() + "'";
					// var alamat_do = "'" + $(this).find("td:eq(3)").text().trim() + "'";
					var alamat_do = "'" + $(this).find("td:eq(2)").text() + "'";
					arr_do.push(alamat_do);
				});

				// GET ID DO_DRAFT BY BATCH DI TABEL DELIVERY_ORDER_TEMP Menampilkan table
				$.ajax({
					async: false,
					type: 'POST',
					url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetDOTempByIDTemp') ?>",
					data: {
						temp_do: temp_do,
						alamat_do: arr_do
					},
					dataType: "JSON",
					success: function(response) {
						if (response.length > 0) {

							$("#table-do-draft > tbody").empty();

							// 	$("#table-do-draft > tbody").append(`
							// 	<tr>
							// 		<th class="text-center">Prioritas</th>
							// 		<th class="text-center">Nama</th>
							// 		<th class="text-center">Alamat</th>
							// 		<th class="text-center">Telp</th>
							// 		<th class="text-center">Jumlah DO</th>
							// 		<th class="text-center" hidden>Principle</th>
							// 		<th class="text-center">Kecamatan</th>
							// 		<th class="text-center">Area</th>
							// 		<th class="text-center">Latitude</th>
							// 		<th class="text-center">Longitude</th>
							// 		<th class="text-center" hidden>Kirim Ulang</th>
							// 		<th class="text-center">Composite</th>
							// 		<th class="text-center">Action</th>
							// 	</tr>
							// `);

							$.each(response, function(i, v) {
								$("#table-do-draft > tbody").append(`
							<tr id="row-${i}" class="ui-sortable-handle">
								<td class="text-center">
									<span id="urutan-prioritas-${i}">${no}</span>
								</td>
								<input type="hidden" class="latitudelongitude" data-nama="${v.delivery_order_draft_kirim_nama}" data-alamat="${v.delivery_order_draft_kirim_alamat}"  data-lat="${v.client_pt_latitude}" data-long="${v.client_pt_longitude}" data-prioritas="${no2}"/>
								<td class="text-center resetPrioritas">${v.delivery_order_draft_kirim_nama}</td>
								<td class="text-center resetPrioritas">${v.delivery_order_draft_kirim_alamat}</td>
								<td class="text-center resetPrioritas">${v.delivery_order_draft_kirim_telp}</td>
								<td class="text-center resetPrioritas">${v.jumlah_do}</td>
								<td class="text-center resetPrioritas">${v.delivery_order_draft_kirim_kecamatan}</td>
								<td class="text-center resetPrioritas">${v.delivery_order_draft_kirim_area}</td>
								<td class="text-center resetPrioritas dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_latitude" >${v.client_pt_latitude}</td>
								<td class="text-center resetPrioritas dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_longitude" >${v.client_pt_longitude}</td>
								<td class="text-center resetPrioritas">${parseInt(v.sku_weight)}</td>
								<td class="text-center resetPrioritas">${parseInt(v.sku_volume)}</td>
								<td class="text-center resetPrioritas">${v.composite}</td>
								<td style="display: none">
									${v.delivery_order_temp_id}
								</td>
								<td class="text-center">
								<button class="btn btn-success btn-sm btn-view-detail-do" onclick="ViewDetailDO('${v.delivery_order_temp_id}','${v.delivery_order_draft_kirim_nama}','${v.delivery_order_draft_kirim_alamat}')"><i class="fa fa-eye"></i></button>` +
									// <button class="btn btn-danger btn-sm btn-delete-do-draft" onclick="DeleteDODraft(this,${i},'${v.delivery_order_temp_id}', '${v.delivery_order_draft_kirim_alamat}','${v.delivery_order_draft_kirim_nama}')"><i class="fa fa-trash"></i></button>
									`<button class="btn btn-warning btn-sm btn-location" onclick="GetLocation('${v.delivery_order_draft_kirim_alamat}')"><i class="fa-sharp fa-solid fa-location-dot"></i></button></td>
								<td class="text-center"><input type="checkbox" style="transform: scale(1.5)" class="ChkboxDelOutlet" data-berat="${parseInt(v.sku_weight)}" data-volume="${parseInt(v.sku_volume)}" name="ChkboxDelOutlet" id="ChkboxDelOutlet" onchange="ChkboxDelOutlet(this)" value="${v.delivery_order_temp_id}_${v.delivery_order_draft_kirim_alamat}_${v.delivery_order_draft_kirim_nama}"></td>
								</tr>
						`);
								no++;
								no2++;
							});

						} else {
							arr_do = [];
							$("#table-do-draft > tbody").empty();
						}

					}
				});

				dblclick();

				// GET ID DO_DRAFT BY BATCH DI TABEL DELIVERY_ORDER_TEMP
				$.ajax({
					async: false,
					type: 'POST',
					url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetIDDOByBatch') ?>",
					data: {
						alamat_do: arr_do,
						temp_do: temp_do
					},
					dataType: "JSON",
					success: function(response) {
						if (response.length > 0) {
							arr_do = [];
							$.each(response, function(i, v) {
								arr_do.push("'" + v.delivery_order_draft_id + "'");
							});

						} else {
							arr_do = [];
							$("#table-do-draft > tbody").empty();
						}

					}
				});

				// console.log(arr_do.length);

				$("#table-summary-sku > tbody").empty();

				if (arr_do.length > 0) {

					appendSKU(arr_do);

					// $.ajax({
					// 	async: false,
					// 	type: 'POST',
					// 	url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetSelectedDODraftSKU') ?>",
					// 	data: {
					// 		do_id: arr_do
					// 	},
					// 	dataType: "JSON",
					// 	success: function(response) {

					// 		$.each(response, function(i, v) {
					// 			$("#table-summary-sku > tbody").append(`
					// 		<tr id="row-${i}" class="ui-sortable-handle">
					// 			<td class="text-center">
					// 				${v.sku_kode}
					// 				<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-sku_id" value="${i}" />
					// 			</td>
					// 			<td class="text-center">${v.sku_nama_produk}</td>
					// 			<td class="text-center">${v.sku_kemasan}</td>
					// 			<td class="text-center">${v.sku_satuan}</td>
					// 			<td class="text-center">${v.sku_qty}</td>
					// 			<td class="text-center">${v.tipe_stock_nama}</td>
					// 			<td class="text-center">${v.sku_request_expdate}</td>
					// 			<td class="text-center">${v.sku_filter_expdate}</td>
					// 			<td class="text-center">${v.sku_filter_expdatebulan}</td>
					// 			<td class="text-center">
					// 				<button class="btn btn-success btn-sm btn-view-sku" onclick="ViewSKU('${v.sku_id}', '${v.sku_kode}', '${v.sku_nama_produk}')"><i class="fa fa-eye"></i></button>
					// 			</td>
					// 		</tr>
					// 	`);
					// 		});
					// 	}
					// });

					// $.ajax({
					// 	async: false,
					// 	type: 'POST',
					// 	url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetKapasitasTerpakai') ?>",
					// 	data: {
					// 		do_id: arr_do
					// 	},
					// 	dataType: "JSON",
					// 	success: function(response) {

					// 		$.each(response, function(i, v) {
					// 			$("#deliveryorderbatch-kendaraan_berat_gr_terpakai")
					// 				.val(
					// 					parseInt(v.sku_weight));
					// 			$("#deliveryorderbatch-kendaraan_volume_cm3_terpakai")
					// 				.val(
					// 					parseInt(v.sku_volume));
					// 		});

					// 		selisihKapasitas();
					// 	}
					// });

					getKapasitasTerpakai();

				} else {
					$("#deliveryorderbatch-kendaraan_berat_gr_terpakai").val(0);

					$("#deliveryorderbatch-kendaraan_volume_cm3_terpakai").val(0);

					selisihKapasitas();
				}

				ResetForm();
				HeaderReadonly();
				CekKapasitasiKendaraan();
			}
		});
	}

	// function DeleteDODraft(row, index, temp_do, alamat_do, nama_do) {
	// 	var row = row.parentNode.parentNode;
	// 	Swal.fire({
	// 		title: "Apakah anda yakin?",
	// 		text: "Data yang dihapus akan hilang!",
	// 		icon: "warning",
	// 		showCancelButton: true,
	// 		confirmButtonColor: "#3085d6",
	// 		cancelButtonColor: "#d33",
	// 		confirmButtonText: "Ya, Simpan",
	// 		cancelButtonText: "Tidak, Tutup"
	// 	}).then((result) => {
	// 		if (result.value == true) {
	// 			// Menghapus do by alamat pada tabel delivery_order_temp
	// 			$.ajax({
	// 				async: false,
	// 				type: 'POST',
	// 				url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetIDDOByBatch') ?>",
	// 				data: {
	// 					alamat_do: ["'" + alamat_do + "'"],
	// 					nama_do: ["'" + nama_do + "'"],
	// 					temp_do: "'" + temp_do + "'"
	// 				},
	// 				dataType: "JSON",
	// 				success: function(response) {
	// 					arr_do = [];
	// 					$.each(response, function(i, v) {
	// 						arr_do.push("'" + v.delivery_order_draft_id + "'");
	// 					});
	// 				}
	// 			});

	// 			$.ajax({
	// 				async: false,
	// 				type: 'POST',
	// 				url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/DeleteDOTempByAlamat') ?>",
	// 				data: {
	// 					do_id: arr_do,
	// 					temp_do: "'" + temp_do + "'"
	// 				},
	// 				dataType: "JSON",
	// 				success: function(response) {
	// 					if (response == 1) {
	// 						row.parentNode.removeChild(row);
	// 					}

	// 				}
	// 			});

	// 			var arr_no_urut_do = [];
	// 			arr_do = [];
	// 			arr_do_nama = [];
	// 			$("#table-do-draft tr:has(td)").each(function(idx) {
	// 				var no_urut = "'" + $(this).find("td:eq(0)").text().trim() + "'";
	// 				// var alamat_do = "'" + $(this).find("td:eq(3)").text().trim() + "'";
	// 				var alamat_do = "'" + $(this).find("td:eq(3)").text() + "'";
	// 				var nama_do = "'" + $(this).find("td:eq(2)").text() + "'";
	// 				arr_do.push(alamat_do);
	// 				arr_do_nama.push(nama_do);
	// 				arr_no_urut_do.push({
	// 					'alamat_do': alamat_do,
	// 					'nama_do': nama_do,
	// 					'no_urut': no_urut
	// 				});
	// 			});

	// 			$("#table-summary-sku > tbody").empty();

	// 			if (arr_do.length > 0) {
	// 				$.ajax({
	// 					async: false,
	// 					type: 'POST',
	// 					url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetDOTempByIDTempWithNoUrut') ?>",
	// 					data: {
	// 						temp_do: "'" + temp_do + "'",
	// 						alamat_do: arr_do,
	// 						nama_do: arr_do_nama,
	// 						no_urut: arr_no_urut_do,
	// 					},
	// 					dataType: "JSON",
	// 					success: function(response) {
	// 						if (response.length > 0) {
	// 							var no = 0;
	// 							var no2 = 0;

	// 							$("#table-do-draft > tbody").empty();

	// 							$("#table-do-draft > tbody").append(`
	//                 		<tr>
	//                 			<th class="text-center">Prioritas</th>
	//                 			<th class="text-center">Nama</th>
	//                 			<th class="text-center">Alamat</th>
	//                 			<th class="text-center">Telp</th>
	// 							<th class="text-center">Jumlah DO</th>
	// 							<th class="text-center" hidden>Principle</th>
	// 							<th class="text-center">Kecamatan</th>
	// 							<th class="text-center">Area</th>
	//                 			<th class="text-center">Latitude</th>
	//                 			<th class="text-center">Longitude</th>
	//                 			<th class="text-center">Berat(gram)</th>
	//                 			<th class="text-center">Volume(cm<sup>3</sup>)</th>
	// 							<th class="text-center" hidden>Kirim Ulang</th>
	// 							<th class="text-center">Composite</th>
	//                 			<th class="text-center">Action</th>
	//                 		</tr>
	//                 	`);

	// 							// Get Kapasitas Berat dan Volume Armada
	// 							var beratArmada = $("#deliveryorderbatch-kendaraan_berat_gr_max").val();
	// 							var volumeArmada = $("#deliveryorderbatch-kendaraan_volume_cm3_max")
	// 								.val();
	// 							var totalBeratDO = 0;
	// 							var totalVolumeDO = 0;

	// 							$.each(response, function(i, v) {
	// 								totalBeratDO = totalBeratDO + parseInt(v.sku_weight);
	// 								totalVolumeDO = totalVolumeDO + parseInt(v.sku_volume);

	// 								if (totalBeratDO > beratArmada || totalVolumeDO >
	// 									volumeArmada) {
	// 									$("#table-do-draft > tbody").append(`
	//         				<tr id="row-${i}" class="ui-sortable-handle" style="background-color: #FF9999; color: black;">
	//         					<td class="text-center">
	//         						<span id="urutan-prioritas-${i}">${++no}</span>
	//         					</td>
	//         					<td style="display: none">
	//         						${v.delivery_order_temp_id}
	//         					</td>
	// 							<input type="hidden" class="latitudelongitude" data-nama="${v.delivery_order_draft_kirim_nama}" data-alamat="${v.delivery_order_draft_kirim_alamat}"  data-lat="${v.client_pt_latitude}" data-long="${v.client_pt_longitude}" data-prioritas="${++no2}"/>
	//         					<td class="text-center">${v.delivery_order_draft_kirim_nama}</td>
	//         					<td class="text-center">${v.delivery_order_draft_kirim_alamat}</td>
	//         					<td class="text-center">${v.delivery_order_draft_kirim_telp}</td>
	// 							<td class="text-center">${v.jumlah_do}</td>
	// 							<td class="text-center" hidden>${v.principle_kode}</td>
	// 							<td class="text-center">${v.delivery_order_draft_kirim_kecamatan}</td>
	// 							<td class="text-center">${v.delivery_order_draft_kirim_area}</td>
	//         					<td class="text-center dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_latitude" >${v.client_pt_latitude}</td>
	//         					<td class="text-center dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_longitude" >${v.client_pt_longitude}</td>
	// 							<td class="text-center">${parseInt(v.sku_weight)}</td>
	// 							<td class="text-center">${parseInt(v.sku_volume)}</td>
	// 							<td class="text-center" hidden>${v.kirim_ulang == null ? '' : 'Kirim Ulang'}</td>
	// 							<td class="text-center">${v.composite}</td>
	//         					<td class="text-center">
	//         					<button class="btn btn-success btn-sm btn-view-detail-do" onclick="ViewDetailDO('${v.delivery_order_temp_id}','${v.delivery_order_draft_kirim_nama}','${v.delivery_order_draft_kirim_alamat}')"><i class="fa fa-eye"></i></button>
	//         					<button class="btn btn-danger btn-sm btn-delete-do-draft" onclick="DeleteDODraft(this,${i},'${v.delivery_order_temp_id}', '${v.delivery_order_draft_kirim_alamat}','${v.delivery_order_draft_kirim_nama}')"><i class="fa fa-trash"></i></button>
	// 							<button class="btn btn-warning btn-sm btn-location" onclick="GetLocation('${v.delivery_order_draft_kirim_alamat}')"><i class="fa-sharp fa-solid fa-location-dot"></i></button></td>
	//         				</tr>
	//         			`);
	// 								} else {
	// 									$("#table-do-draft > tbody").append(`
	//         				<tr id="row-${i}" class="ui-sortable-handle">
	//         					<td class="text-center">
	//         						<span id="urutan-prioritas-${i}">${++no}</span>
	//         					</td>
	//         					<td style="display: none">
	//         						${v.delivery_order_temp_id}
	//         					</td>
	// 							<input type="hidden" class="latitudelongitude" data-nama="${v.delivery_order_draft_kirim_nama}" data-alamat="${v.delivery_order_draft_kirim_alamat}"  data-lat="${v.client_pt_latitude}" data-long="${v.client_pt_longitude}" data-prioritas="${++no2}"/>
	//         					<td class="text-center">${v.delivery_order_draft_kirim_nama}</td>
	//         					<td class="text-center">${v.delivery_order_draft_kirim_alamat}</td>
	//         					<td class="text-center">${v.delivery_order_draft_kirim_telp}</td>
	// 							<td class="text-center">${v.jumlah_do}</td>
	// 							<td class="text-center" hidden>${v.principle_kode}</td>
	// 							<td class="text-center">${v.delivery_order_draft_kirim_kecamatan}</td>
	// 							<td class="text-center">${v.delivery_order_draft_kirim_area}</td>
	//         					<td class="text-center dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_latitude" >${v.client_pt_latitude}</td>
	//         					<td class="text-center dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_longitude" >${v.client_pt_longitude}</td>
	// 							<td class="text-center">${parseInt(v.sku_weight)}</td>
	// 							<td class="text-center">${parseInt(v.sku_volume)}</td>
	// 							<td class="text-center" hidden>${v.kirim_ulang == null ? '' : 'Kirim Ulang'}</td>
	// 							<td class="text-center">${v.composite}</td>
	//         					<td class="text-center">
	//         					<button class="btn btn-success btn-sm btn-view-detail-do" onclick="ViewDetailDO('${v.delivery_order_temp_id}','${v.delivery_order_draft_kirim_nama}','${v.delivery_order_draft_kirim_alamat}')"><i class="fa fa-eye"></i></button>
	//         					<button class="btn btn-danger btn-sm btn-delete-do-draft" onclick="DeleteDODraft(this,${i},'${v.delivery_order_temp_id}', '${v.delivery_order_draft_kirim_alamat}','${v.delivery_order_draft_kirim_nama}')"><i class="fa fa-trash"></i></button>
	// 							<button class="btn btn-warning btn-sm btn-location" onclick="GetLocation('${v.delivery_order_draft_kirim_alamat}')"><i class="fa-sharp fa-solid fa-location-dot"></i></button></td>
	//         				</tr>
	//         			`);
	// 								}
	// 							});

	// 						} else {
	// 							arr_do = [];
	// 							arr_do_nama = [];
	// 							$("#table-do-draft > tbody").empty();
	// 						}

	// 					}
	// 				});

	// 				dblclick();

	// 				// GET ID DO_DRAFT BY BATCH DI TABEL DELIVERY_ORDER_TEMP
	// 				$.ajax({
	// 					async: false,
	// 					type: 'POST',
	// 					url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetIDDOByBatch') ?>",
	// 					data: {
	// 						alamat_do: arr_do,
	// 						nama_do: arr_do_nama,
	// 						temp_do: "'" + temp_do + "'"
	// 					},
	// 					dataType: "JSON",
	// 					success: function(response) {
	// 						arr_do = [];
	// 						$.each(response, function(i, v) {
	// 							arr_do.push("'" + v.delivery_order_draft_id + "'");
	// 						});
	// 					}
	// 				});

	// 				$.ajax({
	// 					async: false,
	// 					type: 'POST',
	// 					url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetSelectedDODraftSKU') ?>",
	// 					data: {
	// 						do_id: arr_do
	// 					},
	// 					dataType: "JSON",
	// 					success: function(response) {

	// 						$.each(response.data, function(i, v) {
	// 							$("#table-summary-sku > tbody").append(`
	// 						<tr id="row-${i}" class="ui-sortable-handle">
	// 							<td class="text-center">
	// 								${v.sku_kode}
	// 								<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-sku_id" value="${i}" />
	// 							</td>
	// 							<td class="text-center">${v.sku_nama_produk}</td>
	// 							<td class="text-center">${v.sku_kemasan}</td>
	// 							<td class="text-center">${v.sku_satuan}</td>
	// 							<td class="text-center">${v.sku_qty}</td>
	// 							<td class="text-center">${v.tipe_stock_nama}</td>
	// 							<td class="text-center">${v.sku_request_expdate}</td>
	// 							<td class="text-center">${v.sku_filter_expdate}</td>
	// 							<td class="text-center">${v.sku_filter_expdatebulan}</td>
	// 							<td class="text-center">
	// 								<button class="btn btn-success btn-sm btn-view-sku" onclick="ViewSKU('${v.sku_id}', '${v.sku_kode}', '${v.sku_nama_produk}')"><i class="fa fa-eye"></i></button>
	// 							</td>
	// 						</tr>
	// 					`);
	// 						});

	// 						$("#totalComposite").text(response.composite);
	// 					}
	// 				});

	// 				$.ajax({
	// 					async: false,
	// 					type: 'POST',
	// 					url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetKapasitasTerpakai') ?>",
	// 					data: {
	// 						do_id: arr_do
	// 					},
	// 					dataType: "JSON",
	// 					success: function(response) {

	// 						$.each(response, function(i, v) {
	// 							$("#deliveryorderbatch-kendaraan_berat_gr_terpakai")
	// 								.val(
	// 									parseInt(v.sku_weight));
	// 							$("#deliveryorderbatch-kendaraan_volume_cm3_terpakai")
	// 								.val(
	// 									parseInt(v.sku_volume));
	// 						});

	// 						selisihKapasitas();

	// 						var beratArmada = parseInt($("#deliveryorderbatch-kendaraan_berat_gr_max").val());
	// 						var volumeArmada = parseInt($("#deliveryorderbatch-kendaraan_volume_cm3_max").val());
	// 						var beratTerpakai = parseInt($("#deliveryorderbatch-kendaraan_berat_gr_terpakai").val());
	// 						var volumeTerpakai = parseInt($("#deliveryorderbatch-kendaraan_volume_cm3_terpakai").val());

	// 						if (beratArmada == 0 && volumeArmada == 0) {
	// 							var presentaseBeratTerpakai = 0;
	// 							var presentaseVolumeTerpakai = 0;
	// 						} else {
	// 							var presentaseBeratTerpakai = Math.floor(beratTerpakai / beratArmada * 100 * 100) / 100 //rumus mengambil 2 angka dibelakang koma
	// 							var presentaseVolumeTerpakai = Math.floor(volumeTerpakai / volumeArmada * 100 * 100) / 100 //rumus mengambil 2 angka dibelakang koma
	// 						}

	// 						$("#deliveryorderbatch-presentase_kendaraan_berat_gr_terpakai").val(presentaseBeratTerpakai + '%');
	// 						$("#deliveryorderbatch-presentase_kendaraan_volume_cm3_terpakai").val(presentaseVolumeTerpakai + '%');

	// 						if (Math.round(presentaseBeratTerpakai) > 100) {
	// 							$("#deliveryorderbatch-presentase_kendaraan_berat_gr_terpakai").attr("style", "background-color: rgb(255, 153, 153); color: black;")
	// 						} else {
	// 							$("#deliveryorderbatch-presentase_kendaraan_berat_gr_terpakai").removeAttr("style");
	// 						}

	// 						if (Math.round(presentaseVolumeTerpakai) > 100) {
	// 							$("#deliveryorderbatch-presentase_kendaraan_volume_cm3_terpakai").attr("style", "background-color: rgb(255, 153, 153); color: black;")
	// 						} else {
	// 							$("#deliveryorderbatch-presentase_kendaraan_berat_gr_terpakai").removeAttr("style");
	// 						}
	// 					}
	// 				});

	// 			} else {
	// 				$("#deliveryorderbatch-kendaraan_berat_gr_terpakai").val(0);
	// 				$("#deliveryorderbatch-presentase_kendaraan_volume_cm3_terpakai").val('0%');

	// 				$("#deliveryorderbatch-kendaraan_volume_cm3_terpakai").val(0);
	// 				$("#deliveryorderbatch-presentase_kendaraan_berat_gr_terpakai").val('0%');

	// 				$("#deliveryorderbatch-presentase_kendaraan_berat_gr_terpakai").removeAttr("style");
	// 				$("#deliveryorderbatch-presentase_kendaraan_berat_gr_terpakai").removeAttr("style");

	// 				selisihKapasitas();

	// 				$("#totalComposite").text(0);
	// 			}

	// 			ResetForm();
	// 			HeaderReadonly();
	// 			CekKapasitasiKendaraan();
	// 		}
	// 	});
	// }

	function DeleteEditDODraftByBatch(row, index, temp_do, alamat_do, batch_id) {
		var row = row.parentNode.parentNode;
		Swal.fire({
			title: "Apakah anda yakin?",
			text: "Data yang dihapus akan hilang!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Simpan",
			cancelButtonText: "Tidak, Tutup"
		}).then((result) => {
			if (result.value == true) {
				// Menghapus do by alamat pada tabel delivery_order_temp
				$.ajax({
					async: false,
					type: 'POST',
					url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetIDDOByBatch') ?>",
					data: {
						alamat_do: ["'" + alamat_do + "'"],
						temp_do: "'" + temp_do + "'"
					},
					dataType: "JSON",
					success: function(response) {
						arr_do = [];
						$.each(response, function(i, v) {
							arr_do.push("'" + v.delivery_order_draft_id + "'");
						});
					}
				});

				$.ajax({
					async: false,
					type: 'POST',
					url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/DeleteDOByAlamat') ?>",
					data: {
						do_id: arr_do,
						temp_do: "'" + temp_do + "'",
						batch_id: "'" + batch_id + "'"
					},
					dataType: "JSON",
					success: function(response) {
						if (response == 1) {
							row.parentNode.removeChild(row);
						}

					}
				});

				arr_do = [];

				$("#table-do-draft tr:has(td)").each(function(idx) {
					// var alamat_do = "'" + $(this).find("td:eq(3)").text().trim() + "'";
					var alamat_do = "'" + $(this).find("td:eq(2)").text() + "'";
					arr_do.push(alamat_do);
				});

				$("#table-summary-sku > tbody").empty();

				if (arr_do.length > 0) {
					// GET ID DO_DRAFT BY BATCH DI TABEL DELIVERY_ORDER_TEMP
					$.ajax({
						async: false,
						type: 'POST',
						url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetIDDOByBatch') ?>",
						data: {
							alamat_do: arr_do,
							temp_do: "'" + temp_do + "'"
						},
						dataType: "JSON",
						success: function(response) {
							arr_do = [];
							$.each(response, function(i, v) {
								arr_do.push("'" + v.delivery_order_draft_id + "'");
							});
						}
					});

					appendSKU(arr_do);

					// $.ajax({
					// 	async: false,
					// 	type: 'POST',
					// 	url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetSelectedDODraftSKU') ?>",
					// 	data: {
					// 		do_id: arr_do
					// 	},
					// 	dataType: "JSON",
					// 	success: function(response) {

					// 		$.each(response, function(i, v) {
					// 			$("#table-summary-sku > tbody").append(`
					// 		<tr id="row-${i}" class="ui-sortable-handle">
					// 			<td class="text-center">
					// 				${v.sku_kode}
					// 				<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-sku_id" value="${i}" />
					// 			</td>
					// 			<td class="text-center">${v.sku_nama_produk}</td>
					// 			<td class="text-center">${v.sku_kemasan}</td>
					// 			<td class="text-center">${v.sku_satuan}</td>
					// 			<td class="text-center">${v.sku_qty}</td>
					// 			<td class="text-center">${v.tipe_stock_nama}</td>
					// 			<td class="text-center">${v.sku_request_expdate}</td>
					// 			<td class="text-center">${v.sku_filter_expdate}</td>
					// 			<td class="text-center">${v.sku_filter_expdatebulan}</td>
					// 			<td class="text-center">
					// 				<button class="btn btn-success btn-sm btn-view-sku" onclick="ViewSKU('${v.sku_id}', '${v.sku_kode}', '${v.sku_nama_produk}')"><i class="fa fa-eye"></i></button>
					// 			</td>
					// 		</tr>
					// 	`);
					// 		});
					// 	}
					// });

					// $.ajax({
					// 	async: false,
					// 	type: 'POST',
					// 	url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetKapasitasTerpakai') ?>",
					// 	data: {
					// 		do_id: arr_do
					// 	},
					// 	dataType: "JSON",
					// 	success: function(response) {

					// 		$.each(response, function(i, v) {
					// 			$("#deliveryorderbatch-kendaraan_berat_gr_terpakai")
					// 				.val(
					// 					parseInt(v.sku_weight));
					// 			$("#deliveryorderbatch-kendaraan_volume_cm3_terpakai")
					// 				.val(
					// 					parseInt(v.sku_volume));
					// 		});

					// 		selisihKapasitas();
					// 	}
					// });

					getKapasitasTerpakai();

				} else {
					$("#deliveryorderbatch-kendaraan_berat_gr_terpakai").val(0);

					$("#deliveryorderbatch-kendaraan_volume_cm3_terpakai").val(0);

					selisihKapasitas();
				}

				ResetForm();
				HeaderReadonly();
				CekKapasitasiKendaraan();
			}
		});
	}

	function DeleteDO(row, index, fdjr_id, do_id) {
		var row = row.parentNode.parentNode;
		Swal.fire({
			title: "Apakah anda yakin?",
			text: "Data yang dihapus akan hilang!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Simpan",
			cancelButtonText: "Tidak, Tutup"
		}).then((result) => {
			if (result.value == true) {
				row.parentNode.removeChild(row);

				arr_del_do.push({
					'fdjr_id': fdjr_id,
					'do_id': do_id
				});

				// console.log(arr_del_do);

				// $.ajax({
				// 	async: false,
				// 	type: 'POST',
				// 	url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/DeleteDO') ?>",
				// 	data: {
				// 		fdjr_id: fdjr_id,
				// 		do_id: do_id
				// 	},
				// 	dataType: "JSON",
				// 	success: function(response) {}
				// });

				arr_do = [];

				$("#table-do-draft tr:has(td)").each(function(idx) {
					var dod_id = "'" + $(this).find("td:eq(12)").text().trim() + "'";
					arr_do.push(dod_id);
				});

				// console.log(arr_do.length);

				$("#table-summary-sku > tbody").empty();

				if (arr_do.length > 0) {

					appendSKU(arr_do);

					// $.ajax({
					// 	async: false,
					// 	type: 'POST',
					// 	url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetSelectedDODraftSKU') ?>",
					// 	data: {
					// 		do_id: arr_do
					// 	},
					// 	dataType: "JSON",
					// 	success: function(response) {

					// 		$.each(response, function(i, v) {
					// 			$("#table-summary-sku > tbody").append(`
					// 		<tr id="row-${i}" class="ui-sortable-handle">
					// 			<td class="text-center">
					// 				${v.sku_kode}
					// 				<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-sku_id" value="${i}" />
					// 			</td>
					// 			<td class="text-center">${v.sku_nama_produk}</td>
					// 			<td class="text-center">${v.sku_kemasan}</td>
					// 			<td class="text-center">${v.sku_satuan}</td>
					// 			<td class="text-center">${v.sku_qty}</td>
					// 			<td class="text-center">${v.sku_request_expdate}</td>
					// 			<td class="text-center">${v.sku_filter_expdate}</td>
					// 			<td class="text-center">${v.sku_filter_expdatebulan}</td>
					// 			<td class="text-center">
					// 				<button class="btn btn-success btn-sm btn-view-sku" onclick="ViewSKU('${v.sku_id}', '${v.sku_kode}', '${v.sku_nama_produk}')"><i class="fa fa-eye"></i></button>
					// 			</td>
					// 		</tr>
					// 	`);
					// 		});
					// 	}
					// });

					// $.ajax({
					// 	async: false,
					// 	type: 'POST',
					// 	url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetKapasitasTerpakai') ?>",
					// 	data: {
					// 		do_id: arr_do
					// 	},
					// 	dataType: "JSON",
					// 	success: function(response) {

					// 		$.each(response, function(i, v) {
					// 			$("#deliveryorderbatch-kendaraan_berat_gr_terpakai")
					// 				.val(
					// 					parseInt(v.sku_weight));
					// 			$("#deliveryorderbatch-kendaraan_volume_cm3_terpakai")
					// 				.val(
					// 					parseInt(v.sku_volume));
					// 		});

					// 		selisihKapasitas();
					// 	}
					// });

					getKapasitasTerpakai();

				} else {
					$("#deliveryorderbatch-kendaraan_berat_gr_terpakai").val(0);

					$("#deliveryorderbatch-kendaraan_volume_cm3_terpakai").val(0);

					selisihKapasitas();
				}

				ResetForm();
				HeaderReadonly();
				CekKapasitasiKendaraan();
			}
		});
	}

	function CekKapasitasiKendaraan() {

		var kap_weight_terpakai = parseInt($("#deliveryorderbatch-kendaraan_berat_gr_terpakai").val());
		var kap_vol_terpakai = parseInt($("#deliveryorderbatch-kendaraan_volume_cm3_terpakai").val());
		var kap_weight_max = parseInt($("#deliveryorderbatch-kendaraan_berat_gr_max").val());
		var kap_vol_max = parseInt($("#deliveryorderbatch-kendaraan_volume_cm3_max").val());

		// alert(kap_weight_terpakai + " " + kap_weight_max);
		if (kap_weight_terpakai > kap_weight_max) {
			$("#deliveryorderbatch-kendaraan_berat_gr_terpakai").css("background-color", "#FF9999");
			$("#deliveryorderbatch-kendaraan_berat_gr_terpakai").css("color", "black");

			message("Warning!", "Melebihi Kapasitas Berat Kendaraan!", "error");
		} else {
			$("#deliveryorderbatch-kendaraan_berat_gr_terpakai").css("background-color", "");
			$("#deliveryorderbatch-kendaraan_berat_gr_terpakai").css("color", "");
		}

		if (kap_vol_terpakai > kap_vol_max) {
			$("#deliveryorderbatch-kendaraan_volume_cm3_terpakai").css("background-color", "#FF9999");
			$("#deliveryorderbatch-kendaraan_volume_cm3_terpakai").css("color", "black");

			message("Warning!", "Melebihi Kapasitas Volume Kendaraan!", "error");
		} else {
			$("#deliveryorderbatch-kendaraan_volume_cm3_terpakai").css("background-color", "");
			$("#deliveryorderbatch-kendaraan_volume_cm3_terpakai").css("color", "");
		}
	}

	function HeaderReadonly() {
		if (arr_do.length > 0) {
			$("#deliveryorderbatch-delivery_order_batch_tanggal_kirim").attr("readonly", true);
			// $("#deliveryorderbatch-area_id").attr("disabled", true);
			$("#deliveryorderbatch-tipe_delivery_order_id").attr("disabled", true);
			$("#deliveryorderbatch-delivery_order_batch_tipe_layanan_id").attr("disabled", true);
			$("#deliveryorderbatch-tipe_ekspedisi_id").attr("disabled", true);
		} else {
			$("#deliveryorderbatch-delivery_order_batch_tanggal_kirim").attr("readonly", false);
			$("#deliveryorderbatch-area_id").attr("disabled", false);
			$("#deliveryorderbatch-tipe_delivery_order_id").attr("disabled", false);
			$("#deliveryorderbatch-delivery_order_batch_tipe_layanan_id").attr("disabled", false);
			$("#deliveryorderbatch-tipe_ekspedisi_id").attr("disabled", false);

		}
	}

	function
	ResetForm() {
		$("#select-dodraft").prop('checked', false);
	}

	//edit doubleclick pada row tabel
	function dblclick() {
		$(".dblclkRow").dblclick(function(e) {
			e.stopPropagation();

			var client_pt_id = $(this).attr('data-client-pt-id');
			var alamat_do = $(this).attr('data-alamat');
			var mode = $(this).attr('data-mode');
			var mode_do = $(this).attr('data-mode-do');
			var currentEle = $(e.target);
			var value = $(e.target).html();
			if (value == "null") {
				value = "";
			}

			if (alamat_do && mode_do) {
				alamat_do = alamat_do;
				mode_do = mode_do;
			} else {
				alamat_do = '';
				mode_do = '';
			}

			updateVal(currentEle, value, client_pt_id, alamat_do, mode, mode_do);
		});

		// mengubah menjadi edit input, textarea, number, date
		function updateVal(currentEle, value, client_pt_id, alamat_do, mode, mode_do) {

			$(currentEle).html('<input class="thVal" id="thVal" type="number" data-value="' + value.trim() + '" value="' +
				value.trim() +
				'" /><input class="client_pt_id" id="client_pt_id" type="hidden" value="' + client_pt_id +
				'" /> <input class="mode" id="mode" type="hidden" value="' + mode +
				'" /> <input class="mode_do" id="mode_do" type="hidden" value="' + mode_do +
				'" /> <input class="alamat_do" id="alamat_do" type="hidden" value="' + alamat_do + '" />');

			$(".thVal").focus();
		}

		$(document).click(function(e) {
			if (!$(e.target).closest('.thVal').length && !$(e.target).is('.thVal')) {
				if ($(".thVal") !== undefined) {
					if ($(".thVal").val() !== undefined && $(".thVal").val() !== '') {
						var client_pt_id = $(".client_pt_id").val();
						var alamat_do = $(".alamat_do").val();
						var latlong_text = $(".thVal").val();
						var mode = $(".mode").val();
						var mode_do = $(".mode_do").val();

						$.ajax({
							type: "POST",
							url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/UpdateLatLongDO') ?>",
							data: {
								client_pt_id: client_pt_id,
								alamat_do: alamat_do,
								latlong_text: latlong_text,
								mode: mode,
								mode_do: mode_do,
							},
							dataType: "JSON",
							success: function(response) {
								if (response == 1) {
									if ($(".thVal").val() == "") {
										$(".thVal").parent().html("null");
									} else {
										$(".thVal").parent().html($(".thVal").val());
									}
									$(".thVal").remove();
									message_topright("success", "Data berhasil disimpan");
								} else {
									message_topright("error", "Data gagal disimpan");
								}

							},
							error: function(xhr, ajaxOptions, thrownError) {
								alert(xhr.status);
								alert(thrownError);
							}
						});
					} else if ($('.thVal').val() == '') {
						var arr_cek_latlong = [];
						$("#table-do-draft tr:has(td)").each(function(idx) {
							var latitude = $(this).find("td:eq(7)").text().trim();
							var longitude = $(this).find("td:eq(8)").text().trim();

							arr_cek_latlong.push(latitude, longitude);
						});

						if (arr_cek_latlong.length > 0) {
							var value = $(".thVal").attr('data-value');
							$(".thVal").parent().html(value);
							message_topright("error", "Data gagal disimpan");
						}
					}
				}
			}
		});
	};

	function ResetTemp() {
		var temp_do = $("#do_temp_id").val();

		if (temp_do.length > 0) {
			$.ajax({
				async: false,
				type: 'POST',
				url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/DeleteDOTemp') ?>",
				data: {
					temp_do: "'" + temp_do + "'"
				},
				dataType: "JSON",
				success: function(response) {
					window.location.href = "<?= base_url("WMS/Distribusi/DeliveryOrderBatch/index") ?>"
				}
			});
		} else {
			window.location.href = "<?= base_url("WMS/Distribusi/DeliveryOrderBatch/index") ?>"
		}

	}

	function cek_checkbox_sku(arr_check_temp) {

		for (let i = 0; i < arr_check_temp.length; i++) {
			$("input[name='CheckboxDODraft'][value='" + arr_check_temp[i].alamat + "'][data-nama='" + arr_check_temp[i].nama + "']").prop('checked',
				true);
			$("input[name='CheckboxDODraft'][value='" + arr_check_temp[i].alamat + "'][data-nama='" + arr_check_temp[i].nama + "']").prop('disabled',
				true);

			var alamatTrim = arr_check_temp[i].alamat.replace(/[!"#$%&'()*+,.\/:;<=>?@[\\\]^`{|}~/\s/g]/g, "");
			$("#" + alamatTrim + "");
		}

		if (arr_check_temp.length > 0) {
			var no = 1;
			$("#data-table-dodraft tr:has(td)").each(function(idx) {
				var checked = $(this).find("td:eq(0) input[type='checkbox']").is(":checked");
				var disabled = $(this).find("td:eq(0) input[type='checkbox']").is(":disabled");

				if (checked == false && disabled == false) {
					$(this).find("td:eq(1)").text(no++);
				}
			});
		}

	}

	function viewAreaById(id) {

		$("#modalViewArea").modal('show');

		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetAreaByFDJRID') ?>",
			data: {
				id: id
			},
			beforeSend: function() {
				Swal.fire({
					title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading</span> ',
					text: 'Please Wait...',
					icon: 'info',
					showCancelButton: false,
					showConfirmButton: false,
					allowOutsideClick: false
				})
			},
			dataType: "JSON",
			success: function(response) {
				$("#table-area > tbody").empty();

				if (response.length > 0) {
					$.each(response, function(i, v) {
						$("#table-area > tbody").append(` <tr>
							<td class="text-center">${i+1}</td>
							<td class="text-center">${v.area_nama}</td>
						</tr> `);
					});
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				Swal.close();
			},
			complete: function(response) {
				Swal.close();
			}
		});
	}

	$("#btn-modal-fdjr-draft").on("click", function() {
		$("#modal-fdjr-draft").modal('show');
	});

	$("#btn-search-data-do-batch-draft").on("click", function() {
		GetDeliveryOrderByFDJRDraft();
	});

	function GetDeliveryOrderByFDJRDraft() {
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetDeliveryOrderByFDJRDraft') ?>",
			data: {
				delivery_order_batch_tanggal_kirim: $("#filter-delivery_order_batch_tanggal_kirim").val()
			},
			beforeSend: function() {
				Swal.fire({
					title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading</span> ',
					text: 'Please Wait...',
					icon: 'info',
					showCancelButton: false,
					showConfirmButton: false,
					allowOutsideClick: false
				})
			},
			dataType: "JSON",
			success: function(response) {
				if ($.fn.DataTable.isDataTable('#table_list_data_do_batch_draft')) {
					$('#table_list_data_do_batch_draft').DataTable().clear();
					$('#table_list_data_do_batch_draft').DataTable().destroy();
				}

				if ($.fn.DataTable.isDataTable('#table_list_data_do_draft')) {
					$('#table_list_data_do_draft').DataTable().clear();
					$('#table_list_data_do_draft').DataTable().destroy();
				}

				if (response != 0) {
					$.each(response.DO, function(i, v) {
						$("#table_list_data_do_draft > tbody").append(`
							<tr>
								<td class="text-center">
									<input type="checkbox" name="CheckboxDODraft" id="check-do-draft-${i}" value="${v.delivery_order_id}" OnClick="PushArrayDODraft('${i}','${v.delivery_order_id}')">
									<input type="hidden" id="do-draft-last-update-${i}" value="${v.delivery_order_update_tgl}">
								</td>
								<td class="text-center">${v.delivery_order_tgl_rencana_kirim}</td>
								<td class="text-center">${v.delivery_order_batch_kode}</td>
								<td class="text-center">${v.delivery_order_kode}</td>
								<td class="text-center">${v.sales_order_kode}</td>
								<td class="text-center">${v.sales_order_no_po}</td>
								<td class="text-center">${v.delivery_order_kirim_nama}</td>
								<td class="text-center">${v.delivery_order_kirim_alamat}</td>
								<td class="text-center">${v.tipe_delivery_order_alias}</td>
							</tr>
						`);
					});

					$.each(response.FDJR, function(i, v) {
						$("#table_list_data_do_batch_draft > tbody").append(`
							<tr>
								<td class="text-center">
									<input type="checkbox" name="CheckboxFDJRDraft" id="check-fdjr-draft-${i}" value="${v.delivery_order_batch_id}" OnClick="PushArrayFDJRDraft('${i}','${v.delivery_order_batch_id}')">
									<input type="hidden" id="fdjr-draft-last-update-${i}" value="${v.delivery_order_batch_update_tgl}">
									<input type="hidden" id="fdjr-draft-tanggal-kirim-${i}" value="${v.dtm_delivery_order_batch_tanggal_kirim}">
								</td>
								<td class="text-center">${v.delivery_order_batch_tanggal}</td>
								<td class="text-center">${v.delivery_order_batch_tanggal_kirim}</td>
								<td class="text-center">${v.delivery_order_batch_kode}</td>
								<td class="text-center">${v.tipe_delivery_order_alias}</td>
								<td class="text-center">${v.karyawan_nama}</td>
								<td class="text-center">${v.delivery_order_batch_tipe_layanan_nama}</td>
								<td class="text-center"><a class="btn btn-link" onclick="viewAreaById('${v.delivery_order_batch_id}')">View Area</a></td>
								<td class="text-center">${v.delivery_order_batch_status}</td>
							</tr>
						`);
					});

					$("#count_fdjr").val(response.FDJR.length);
					$("#count_do").val(response.DO.length);

					$('#table_list_data_do_draft').DataTable();
					$('#table_list_data_do_batch_draft').DataTable();

				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				Swal.close();
			},
			complete: function(response) {
				Swal.close();
			}
		});
	}

	function PushArrayFDJRDraft(idx, id) {
		arr_fdjr_draft = [];

		if ($('[id="check-fdjr-draft-' + idx + '"]:checked').length > 0) {
			arr_fdjr_draft.push(id);
		}
	}

	function PushArrayDODraft(idx, id) {
		var index = $.inArray(id, arr_do_draft);

		if ($('[id="check-do-draft-' + idx + '"]:checked').length > 0) {
			arr_do_draft.push(id);
		} else {
			if (index !== -1) {
				// Remove the element at the found index
				arr_do_draft.splice(index, 1);

			}

		}
	}

	$("#btn_simpan_fdjr_pindah").on("click", function() {
		arr_fdjr_draft = [];
		arr_do_draft = [];

		for (var idx = 0; idx < $("#count_fdjr").val(); idx++) {

			if ($('[id="check-fdjr-draft-' + idx + '"]:checked').length > 0) {
				arr_fdjr_draft.push({
					'delivery_order_batch_id': $("#check-fdjr-draft-" + idx).val(),
					'delivery_order_batch_tanggal_kirim': $("#fdjr-draft-tanggal-kirim-" + idx).val(),
					'delivery_order_batch_update_tgl': $("#fdjr-draft-last-update-" + idx).val(),
				});
			}
		}

		for (var idx = 0; idx < $("#count_do").val(); idx++) {
			if ($('[id="check-do-draft-' + idx + '"]:checked').length > 0) {
				arr_do_draft.push({
					'delivery_order_id': $("#check-do-draft-" + idx).val(),
					'delivery_order_update_tgl': $("#do-draft-last-update-" + idx).val(),
				});
			}
		}

		// $("#table_list_data_do_draft > tbody").each(function(index, element) {
		// 	$(this).find(':checkbox:checked').each(function(idx) {
		// 		arr_do_draft.push({
		// 			'delivery_order_id': $("#check-do-draft-" + idx).val(),
		// 			'delivery_order_update_tgl': $("#do-draft-last-update-" + idx).val(),
		// 		});
		// 	})
		// });

		// $("#table_list_data_do_batch_draft > tbody").each(function(index, element) {
		// 	$(this).find(':checkbox:checked').each(function(idx) {
		// 		arr_fdjr_draft.push({
		// 			'delivery_order_batch_id': $("#check-fdjr-draft-" + idx).val(),
		// 			'delivery_order_batch_tanggal_kirim': $("#fdjr-draft-tanggal-kirim-" + idx).val(),
		// 			'delivery_order_batch_update_tgl': $("#fdjr-draft-last-update-" + idx).val(),
		// 		});
		// 	})
		// });

		// console.log(arr_fdjr_draft);
		// console.log(arr_do_draft);

		if (arr_do_draft.length == 0) {
			let alert = GetLanguageByKode('CAPTION-ALERT-PILIHDO');
			message("Error!", alert, 'error');
			return false;
		}

		if (arr_fdjr_draft.length == 0) {
			let alert = GetLanguageByKode('CAPTION-ALERT-PILIHFDJR');
			message("Error!", alert, 'error');
			return false;
		}

		messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Iya, Simpan', 'Tidak, Tutup').then((
			result) => {
			if (result.value == true) {
				requestAjax(
					"<?= base_url('WMS/Distribusi/DeliveryOrderBatch/move_delivery_order') ?>", {
						arr_fdjr_draft: arr_fdjr_draft,
						arr_do_draft: arr_do_draft
					}, "POST", "JSON",
					function(response) {
						if (response == 200) {
							let alert = GetLanguageByKode('CAPTION-ALERT-DATABERHASILDISIMPAN');
							message("Success", alert, "success");

							setTimeout(() => {
								location.reload();
							}, 1000);
						} else if (data == 400) {
							return messageNotSameLastUpdated();
						} else {
							let alert = GetLanguageByKode('CAPTION-ALERT-DATAGAGALDISIMPAN');
							message("Error", alert, "error");
						}
					}, "#btn_simpan_fdjr_pindah")
			}
		});
	});

	function simulasiArea() {
		getTipeLayanan = $('#deliveryorderbatch-delivery_order_batch_tipe_layanan_id').val();
		getTipe = $('#deliveryorderbatch-tipe_delivery_order_id').val();
		getTanggalKirim = $('#deliveryorderbatch-delivery_order_batch_tanggal_kirim').val();

		if (getTipeLayanan == '') {
			message("Info!", "Pilih Tipe Layanan terlebih dahulu!", "warning");
			return false;
		} else if (getTipe == '') {
			message("Info!", "Pilih Tipe terlebih dahulu!", "warning");
			return false;
		}

		$('#data-table-simulasi-area > tbody').empty();
		$('#data-table-simulasi-area > tfoot').empty();
		$('.showKendaraan').hide();
		$('#simulasiTipeLayananID').val('1DF869BD-F69C-4897-9813-73449428CD6E || 0 || Delivery Only').trigger("change");
		$('#simulasiTipeDOID').val('BB403FA9-007F-42CD-87F0-92CEF7B40AAD').trigger("change");
		$('#simulasiTipeEkspedisiID').val('1F63E7C8-8600-4196-9E83-1964063B1710').trigger("change");
		// $('#simulasiKendaraanID').val('').trigger("change");
		// $('#simulasiMaxBerat').val(0);
		// $('#simulasiMaxVolume').val(0);
		$('#simulasiBeratTerpakai').val(0);
		$('#simulasiVolumeTerpakai').val(0);
		$('#simulasiBeratRetur').val(0);
		$('#simulasiVolumeRetur').val(0);
		$('#select-all-simulasi-area').prop('checked', false);

		var dateRangePicker = $('#simulasiTanggalKirim').data('daterangepicker');
		dateRangePicker.setStartDate(getTanggalKirim);
		dateRangePicker.setEndDate(getTanggalKirim);

		$('.selectpickersegment1').selectpicker('deselectAll');
		$('.selectpickersegment2').selectpicker('deselectAll');
		$('.selectpickersegment3').selectpicker('deselectAll');

		$('#modal-simulasiarea').modal('show');
	}

	function searchAreaSimulasi() {
		$('.showKendaraan').show();
		var tgl_kirim = $('#simulasiTanggalKirim').val();
		var tipe_layanan = $('#simulasiTipeLayananID').val().split('||');
		var tipe = $('#simulasiTipeDOID').val();
		var segmentasi1 = $('#segmentasi1').val();
		var segmentasi2 = $('#segmentasi2').val();
		var segmentasi3 = $('#segmentasi3').val();
		var mode = "<?= $this->uri->segment(4) ?>";

		$('#data-table-simulasi-area > tfoot').empty();

		ddmmyyyy = tgl_kirim.split('/');
		dd = ddmmyyyy[0];
		mm = ddmmyyyy[1];
		yyyy = ddmmyyyy[2];

		tgl_kirim = yyyy + '-' + mm + '-' + dd;

		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetAreaSimulasi') ?>",
			data: {
				tgl_kirim: tgl_kirim,
				tipe_layanan: tipe_layanan[2].trim(),
				tipe: tipe,
				segmentasi1: segmentasi1,
				segmentasi2: segmentasi2,
				segmentasi3: segmentasi3,
				mode: mode,
				delivery_order_batch_id: $('#deliveryorderbatch-delivery_order_batch_id').val() != 'undefined' ? $('#deliveryorderbatch-delivery_order_batch_id').val() : ''
			},
			dataType: "JSON",
			success: function(response) {
				$('#data-table-simulasi-area > tbody').empty();

				if (response.area_wilayah.length != 0) {
					$.each(response.area_wilayah, function(i, v) {
						var area_wilayah_parent = v.area_wilayah.replace("/", "");
						var str = `<input type='checkbox' style='transform: scale(1.5)' class="chkAllByAreaWilayah"  name='chkAllByAreaWilayah' id='chkAllByAreaWilayah'  onchange="chkAllByAreaWilayah(this,'${area_wilayah_parent}')" value='${area_wilayah_parent}'/>`
						var strContent = "";
						strContent = strContent + `<tr id="tr_${area_wilayah_parent}">`;
						strContent = strContent + `<td class="text-center"><button onclick="showHideData('${area_wilayah_parent}', this)" type="button" class="btn btn-primary btn-sm btnShowHide btnShowHide_${i}" style="border-radius: 100%;"><i class="fa fa-plus"></i></button></span></td>`;
						strContent = strContent + `<td class="text-center">${str}</td>`;
						strContent = strContent + `<td class="text-center" style="background-color: #EEEEEE"><strong>${area_wilayah_parent}</strong></td>`;
						strContent = strContent + `<td class="text-center" style="background-color: #EEEEEE"></td>`;
						strContent = strContent + `<td class="text-center" style="background-color: #EEEEEE"><strong>${v.jml_outlet}</strong></td>`;
						strContent = strContent + `<td class="text-center" style="background-color: #EEEEEE"><strong>${v.jml_do}</strong></td>`;
						strContent = strContent + `<td class="text-center" style="background-color: #EEEEEE"><strong>${formatNumber(v.berat)}</strong></td>`;
						strContent = strContent + `<td class="text-center" style="background-color: #EEEEEE"><strong>${formatNumber(v.volume)}</strong></td>`;
						strContent = strContent + `<td class="text-center" style="background-color: #EEEEEE"></td>`;
						strContent = strContent + `</tr>`;

						$.each(response.area, function(i, q) {
							var area_wilayah_children = q.area_wilayah.replace("/", "");
							if (area_wilayah_parent == area_wilayah_children) {
								var strDataArea = `<input type="checkbox" id="chkData_${area_wilayah_children}" name="chkData_${area_wilayah_children}" class="chkData_${area_wilayah_children} chkData" onchange="kapasitasTerpakai()" style="transform: scale(1.5)" value="${q.area_nama}" />`;
								strContent = strContent + `<tr class="trData_${area_wilayah_children} trStyle" hidden>`
								strContent = strContent + `<td></td>`
								strContent = strContent + `<td class="text-center">${strDataArea}</td>`
								strContent = strContent + `<td class="text-center">${area_wilayah_children}</td>`
								strContent = strContent + `<td class="text-center">${q.area_nama}</td>`
								strContent = strContent + `<td class="text-center">${q.jml_outlet}</td>`
								strContent = strContent + `<td class="text-center">${q.jml_do}</td>`
								strContent = strContent + `<td class="text-center">${formatNumber(q.sku_weight)}</td>`
								strContent = strContent + `<td class="text-center">${formatNumber(q.sku_volume)}</td>`
								strContent = strContent + `<td class="text-center">${q.tipe_delivery_order_nama}</td>`
								strContent = strContent + `</tr>`
							}
						})

						$('#data-table-simulasi-area > tbody').append(strContent);
					});

					strContent = "";
					strContent = strContent + `<tr class="bg-success">`;
					strContent = strContent + `<td colspan="4" class="text-center"><strong>TOTAL :</strong></td>`;
					strContent = strContent + `<td class="text-center"><strong>${response.jumlahOutlet}</strong></td>`;
					strContent = strContent + `<td class="text-center"><strong>${response.jumlahDO}</strong></td>`;
					strContent = strContent + `<td class="text-center"><strong>${response.berat}</strong></td>`;
					strContent = strContent + `<td class="text-center"><strong>${response.volume}</strong></td>`;
					strContent = strContent + `<td class="text-center"></td>`;
					strContent = strContent + `<tr>`;

					$('#data-table-simulasi-area > tfoot').append(formatNumber(strContent));

					resetTableSorterSimulasiArea();
				} else {
					$('#data-table-simulasi-area > tbody').append(`
					<tr>
					<td colspan="9" class="text-center"><strong>Data Kosong</strong></td>
					</tr>
					`);
				}


			}
		})
	}

	function showHideData(area_wilayah, event) {
		var chkButton = $(event).html();

		if (chkButton == '<i class="fa fa-plus"></i>') {
			$(`.trData_${area_wilayah}`).show();
			$(event).html('<i class="fa fa-minus"></i>');
		} else {
			$(`.trData_${area_wilayah}`).hide();
			$(event).html('<i class="fa fa-plus"></i>');
		}
	}

	function chkAllByAreaWilayah(event, area_wilayah) {
		if (event.checked) {
			$(`.chkData_${area_wilayah}`).prop('checked', true);
		} else {
			$(`.chkData_${area_wilayah}`).prop('checked', false);
		}

		kapasitasTerpakai();
	}

	function allCheckedArea(event) {
		if (event.checked) {
			$(`.chkAllByAreaWilayah`).prop('checked', true);
			$(`.chkData`).prop('checked', true);
		} else {
			$(`.chkAllByAreaWilayah`).prop('checked', false);
			$(`.chkData`).prop('checked', false);
		}

		kapasitasTerpakai();
	}

	function kapasitasTerpakai() {
		var beratKapasitasTerpakai = 0;
		var volumeKapasitasTerpakai = 0;
		var beratKapasitasTerpakaiRetur = 0;
		var volumeKapasitasTerpakaiRetur = 0;

		$('#data-table-simulasi-area > tbody > tr').each(function() {
			var checked = $(this).find('.chkData').prop('checked');
			var tipe = $(this).find('td:eq(8)').text();

			if (checked) {
				var berat = $(this).find('td:eq(6)').text().replace(/\./g, '');
				var volume = $(this).find('td:eq(7)').text().replace(/\./g, '');

				if (tipe == 'Retur') {
					beratKapasitasTerpakaiRetur = beratKapasitasTerpakaiRetur + parseInt(berat);
					volumeKapasitasTerpakaiRetur = volumeKapasitasTerpakaiRetur + parseInt(volume);
				} else {
					beratKapasitasTerpakai = beratKapasitasTerpakai + parseInt(berat);
					volumeKapasitasTerpakai = volumeKapasitasTerpakai + parseInt(volume);
				}
			}
		});

		$('#simulasiBeratTerpakai').val(formatNumber(beratKapasitasTerpakai));
		$('#simulasiVolumeTerpakai').val(formatNumber(volumeKapasitasTerpakai));
		$('#simulasiBeratRetur').val(formatNumber(beratKapasitasTerpakaiRetur));
		$('#simulasiVolumeRetur').val(formatNumber(volumeKapasitasTerpakaiRetur));
	}

	function getSimulasiMuatan(kendaraan) {
		if (kendaraan != '') {
			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetKapasitasKendaraan') ?>",
				data: {
					kendaraan: kendaraan
				},
				dataType: "JSON",
				success: function(response) {
					var berat = response[0].kendaraan_kap_weight;
					var volume = response[0].kendaraan_kap_vol;
					$("#simulasiMaxBerat").val(formatNumber(parseInt(berat)));
					$("#simulasiMaxVolume").val(formatNumber(parseInt(volume)));
				}
			});
		} else {
			$("#simulasiMaxBerat").val(0);
			$("#simulasiMaxVolume").val(0);
		}
	}

	function btnPilihSimulasiArea() {
		var simulasiTanggalKirim = $('#simulasiTanggalKirim').val();
		var simulasiTipeLayananID = $('#simulasiTipeLayananID').val();
		var simulasiTipeDOID = $('#simulasiTipeDOID').val();
		var simulasiKendaraanID = $('#simulasiKendaraanID').val();

		arr = [];
		$('#data-table-simulasi-area > tbody > tr').each(function() {
			var checked = $(this).find('.chkData');

			if (checked.prop('checked')) {
				arr.push(checked.val());
			}
		});

		if (simulasiTipeLayananID == '') {
			message("Error!", "Pilih Tipe Layanan Terlebih Dahulu!", "error");
			return false;
		} else if (simulasiTipeDOID == '') {
			message("Error!", "Pilih Tipe Terlebih Dahulu!", "error");
			return false;
		} else if (arr.length == 0) {
			message("Error!", "Pilih Area Simulasi Terlebih Dahulu!", "error");
			return false;
		}

		$('#deliveryorderbatch-delivery_order_batch_tipe_layanan_id').val(simulasiTipeLayananID);
		$('#deliveryorderbatch-tipe_delivery_order_id').val(simulasiTipeDOID);
		$('#deliveryorderbatch-kendaraan_id').val(simulasiKendaraanID).trigger('change');

		var dateRangePicker = $('#deliveryorderbatch-delivery_order_batch_tanggal_kirim').data('daterangepicker');
		dateRangePicker.setStartDate(simulasiTanggalKirim);
		dateRangePicker.setEndDate(simulasiTanggalKirim);

		// if (simulasiTanggalKirim == '<?= date('d/m/Y') ?>') {
		// 	getAreaFromSimulasi(arr);
		// }

		getAreaFromSimulasi(arr);

		$('#modal-simulasiarea').modal('hide');
	}

	function getAreaFromSimulasi(arr) {
		var mode = "<?= $this->uri->segment(4) ?>";
		var tgl_kirim = $('#deliveryorderbatch-delivery_order_batch_tanggal_kirim').val();

		ddmmyyyy = tgl_kirim.split('/');
		dd = ddmmyyyy[0];
		mm = ddmmyyyy[1];
		yyyy = ddmmyyyy[2];

		tgl_kirim = yyyy + '-' + mm + '-' + dd;

		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetAreaByTgl') ?>",
			data: {
				tgl_kirim: tgl_kirim,
				do_batch_id: mode == 'create' ? null : $('#do_batch_id').val(),
				mode: mode
			},
			dataType: "JSON",
			success: function(response) {
				$("#deliveryorderbatch-area_id").empty();

				if (response.length != 0) {
					$.each(response, function(i, v) {
						var selected = false;

						if (arr.length != 0) {
							selected = arr.some(function(item) {
								return item == v.area_nama;
							});
						} else {
							selected = false;
						}

						if (selected) {
							$("#deliveryorderbatch-area_id").append(`
					<option selected value="${v.area_id}">${v.area_nama}</option>
					`)
						} else {
							$("#deliveryorderbatch-area_id").append(`
					<option value="${v.area_id}">${v.area_nama}</option>
					`)
						}
					})
				}

				$('.selectpicker').selectpicker('refresh');
			}
		})
	}

	function changeSegmentasi(level) {
		var idSegment1 = $('#segmentasi1').val();
		var idSegment2 = $('#segmentasi2').val();
		var idSegment3 = $('#segmentasi3').val();
		var id = ''

		if (level == 1) {
			id = idSegment1
			$('#segmentasi2').html('');
			$('#segmentasi3').html('');
			$('.selectpickersegment2').selectpicker('refresh');
			$('.selectpickersegment3').selectpicker('refresh');

			if (idSegment1 != null) {
				initSegmentasi(id, level)
			}
		} else if (level == 2) {
			id = idSegment2
			$('#segmentasi3').html('');
			$('.selectpickersegment3').selectpicker('refresh');

			if (idSegment2 != null) {
				initSegmentasi(id, level)
			}
		}
	}

	function initSegmentasi(id, level) {
		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/getSegmentasiByID') ?>",
			data: {
				idSeg: id
			},
			dataType: 'JSON',
			success: function(response) {
				if (response != 0) {
					$.each(response, function(i, v) {
						if (level == 1) {
							$('#segmentasi2').append(`
							<option value="${v.client_pt_segmen_id}">${v.client_pt_segmen_nama}</option>
							`)

							$('.selectpickersegment2').selectpicker('refresh');
						} else {
							$('#segmentasi3').append(`
							<option value="${v.client_pt_segmen_id}">${v.client_pt_segmen_nama}</option>
							`)

							$('.selectpickersegment3').selectpicker('refresh');
						}
					})
				}
			}
		});
	}

	function formatNumber(num) {
		return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
	}

	function allDelOutlet(checked) {
		if (checked) {
			$('.ChkboxDelOutlet').prop('checked', true);
		} else {
			$('.ChkboxDelOutlet').prop('checked', false);
		}

		var lengthChecked = $('.ChkboxDelOutlet:checked').length
		$('#jml_outlet').html("(" + lengthChecked + ")");

		changeSimulasiPengurangan();
	}

	function ChkboxDelOutlet(e) {
		var lengthChecked = $('.ChkboxDelOutlet:checked').length
		$('#jml_outlet').html("(" + lengthChecked + ")");

		changeSimulasiPengurangan();
	}

	function changeSimulasiPengurangan() {
		var totalWeightReduction = 0;
		var totalVolumeReduction = 0;
		$('.ChkboxDelOutlet:checked').each(function() {
			totalWeightReduction += parseInt($(this).attr('data-berat'));
			totalVolumeReduction += parseInt($(this).attr('data-volume'));
		});

		if (totalWeightReduction == 0 && totalVolumeReduction == 0) {
			$('#pengurangan_terpakai_berat').text('')
			$('#pengurangan_terpakai_volume').text('')
			$('#pengurangan_presentase_berat').text('')
			$('#pengurangan_presentase_volume').text('')
			$('#pengurangan_selisih_berat').text('')
			$('#pengurangan_selisih_volume').text('')
		} else {
			$('#pengurangan_terpakai_berat').text('-' + totalWeightReduction)
			$('#pengurangan_terpakai_volume').text('-' + totalVolumeReduction)
			$('#pengurangan_terpakai_berat').attr('style', 'color:red;font-weight:bold;');
			$('#pengurangan_terpakai_volume').attr('style', 'color:red;font-weight:bold;');

			// presentase kapasitas terpakai
			var beratArmada = parseInt($("#deliveryorderbatch-kendaraan_berat_gr_max").val());
			var volumeArmada = parseInt($("#deliveryorderbatch-kendaraan_volume_cm3_max").val());

			if (beratArmada == 0 && volumeArmada == 0) {
				$('#pengurangan_presentase_berat').text('')
				$('#pengurangan_presentase_volume').text('')
				$('#pengurangan_selisih_berat').text('')
				$('#pengurangan_selisih_volume').text('')
			} else {
				// presentase kapasitas
				// var presentaseBeratTerpakai = Math.floor(totalWeightReduction / beratArmada * 100 * 100) / 100 //rumus mengambil 2 angka dibelakang koma
				var presentaseBeratTerpakai = Math.floor(totalWeightReduction / beratArmada * 100) //rumus mengambil 2 angka dibelakang koma
				// var presentaseVolumeTerpakai = Math.floor(totalVolumeReduction / volumeArmada * 100 * 100) / 100 //rumus mengambil 2 angka dibelakang koma
				var presentaseVolumeTerpakai = Math.floor(totalVolumeReduction / volumeArmada * 100) //rumus mengambil 2 angka dibelakang koma
				$('#pengurangan_presentase_berat').text('-' + presentaseBeratTerpakai + '%')
				$('#pengurangan_presentase_volume').text('-' + presentaseVolumeTerpakai + '%')

				// selisih kapasitas
				var selisihKapasitasBerat = $('#selisihKapasitasBerat').val();
				var selisihKapasitasVolume = $('#selisihKapasitasVolume').val();

				$("#pengurangan_selisih_berat").text((parseInt(selisihKapasitasBerat) + (parseInt(totalWeightReduction))));
				$("#pengurangan_selisih_volume").text((parseInt(selisihKapasitasVolume) + (parseInt(totalVolumeReduction))));

				// style
				$('#pengurangan_presentase_berat').attr('style', 'color:red;font-weight:bold;');
				$('#pengurangan_presentase_volume').attr('style', 'color:red;font-weight:bold;');
				$('#pengurangan_selisih_berat').attr('style', 'color:green;font-weight:bold;');
				$('#pengurangan_selisih_volume').attr('style', 'color:green;font-weight:bold;');
			}
		}
	}

	function hapusOutlet() {
		var alamat_do = [];
		var nama_do = [];
		var temp_do = '';
		$('.ChkboxDelOutlet').each(function(index, element) {
			if (element.checked == true) {
				var value = element.value.split('_');

				temp_do = "'" + value[0] + "'";
				alamat_do.push("'" + value[1] + "'");
				nama_do.push("'" + value[2] + "'");
			}
		});

		if (temp_do != '') {
			Swal.fire({
				title: "Apakah anda yakin?",
				text: "Data yang dihapus akan hilang!",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: "Ya, Simpan",
				cancelButtonText: "Tidak, Tutup"
			}).then((result) => {
				if (result.value == true) {
					totalPenguranganBerat = 0;
					totalPenguranganVolume = 0;
					$('.ChkboxDelOutlet:checked').each(function() {
						totalPenguranganBerat += parseInt($(this).attr('data-berat'));
						totalPenguranganVolume += parseInt($(this).attr('data-volume'));
					});

					$('#jml_outlet').html("(" + 0 + ")");

					// Menghapus do by alamat pada tabel delivery_order_temp
					$.ajax({
						async: false,
						type: 'POST',
						url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetIDDOByBatch') ?>",
						data: {
							alamat_do: alamat_do,
							nama_do: nama_do,
							temp_do: temp_do
						},
						dataType: "JSON",
						success: function(response) {
							arr_do = [];
							$.each(response, function(i, v) {
								arr_do.push("'" + v.delivery_order_draft_id + "'");
							});
						}
					});

					$.ajax({
						async: false,
						type: 'POST',
						url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/DeleteDOTempByAlamat') ?>",
						data: {
							do_id: arr_do,
							temp_do: temp_do
						},
						dataType: "JSON",
						success: function(response) {
							// if (response == 1) {
							// 	row.parentNode.removeChild(row);
							// }
						}
					});

					var arr_no_urut_do = [];
					arr_do = [];
					arr_do_nama = [];
					$("#table-do-draft tr:has(td)").each(function(idx) {
						var no_urut = "'" + $(this).find("td:eq(0)").text().trim() + "'";
						// var alamat_do = "'" + $(this).find("td:eq(3)").text().trim() + "'";
						var alamat_do = "'" + $(this).find("td:eq(2)").text() + "'";
						var nama_do = "'" + $(this).find("td:eq(1)").text() + "'";
						arr_do.push(alamat_do);
						arr_do_nama.push(nama_do);
						arr_no_urut_do.push({
							'alamat_do': alamat_do,
							'nama_do': nama_do,
							'no_urut': no_urut
						});
					});

					$("#table-summary-sku > tbody").empty();

					if (arr_do.length > 0) {
						appendOutlet(temp_do, arr_do, arr_do_nama, arr_no_urut_do)

						// $.ajax({
						// 	async: false,
						// 	type: 'POST',
						// 	url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetDOTempByIDTempWithNoUrut') ?>",
						// 	data: {
						// 		temp_do: temp_do,
						// 		alamat_do: arr_do,
						// 		nama_do: arr_do_nama,
						// 		no_urut: arr_no_urut_do,
						// 	},
						// 	dataType: "JSON",
						// 	success: function(response) {
						// 		if (response.length > 0) {
						// 			var no = 0;
						// 			var no2 = 0;

						// 			$("#table-do-draft > tbody").empty();

						// 			$("#table-do-draft > tbody").append(`
						// 	<tr>
						// 		<th class="text-center">Prioritas</th>
						// 		<th class="text-center">Nama</th>
						// 		<th class="text-center">Alamat</th>
						// 		<th class="text-center">Telp</th>
						// 		<th class="text-center">Jumlah DO</th>
						// 		<th class="text-center" hidden>Principle</th>
						// 		<th class="text-center">Kecamatan</th>
						// 		<th class="text-center">Area</th>
						// 		<th class="text-center">Latitude</th>
						// 		<th class="text-center">Longitude</th>
						// 		<th class="text-center">Berat(gram)</th>
						// 		<th class="text-center">Volume(cm<sup>3</sup>)</th>
						// 		<th class="text-center" hidden>Kirim Ulang</th>
						// 		<th class="text-center">Composite</th>
						// 		<th class="text-center">Action</th>
						// 		<th class="text-center">Pilih Semua <input type="checkbox" style="transform: scale(1.5)" name="allDelOutlet" id="allDelOutlet" onchange="allDelOutlet(this.checked)" value="1"></th>
						// 	</tr>
						// `);

						// 			// Get Kapasitas Berat dan Volume Armada
						// 			var beratArmada = $("#deliveryorderbatch-kendaraan_berat_gr_max").val();
						// 			var volumeArmada = $("#deliveryorderbatch-kendaraan_volume_cm3_max")
						// 				.val();
						// 			var totalBeratDO = 0;
						// 			var totalVolumeDO = 0;

						// 			$.each(response, function(i, v) {
						// 				totalBeratDO = totalBeratDO + parseInt(v.sku_weight);
						// 				totalVolumeDO = totalVolumeDO + parseInt(v.sku_volume);

						// 				if (totalBeratDO > beratArmada || totalVolumeDO >
						// 					volumeArmada) {
						// 					$("#table-do-draft > tbody").append(`
						// 	<tr id="row-${i}" class="ui-sortable-handle" style="background-color: #FF9999; color: black;">
						// 		<td class="text-center">
						// 			<span id="urutan-prioritas-${i}">${++no}</span>
						// 		</td>
						// 		<td style="display: none">
						// 			${v.delivery_order_temp_id}
						// 		</td>
						// 		<input type="hidden" class="latitudelongitude" data-nama="${v.delivery_order_draft_kirim_nama}" data-alamat="${v.delivery_order_draft_kirim_alamat}"  data-lat="${v.client_pt_latitude}" data-long="${v.client_pt_longitude}" data-prioritas="${++no2}"/>
						// 		<td class="text-center">${v.delivery_order_draft_kirim_nama}</td>
						// 		<td class="text-center">${v.delivery_order_draft_kirim_alamat}</td>
						// 		<td class="text-center">${v.delivery_order_draft_kirim_telp}</td>
						// 		<td class="text-center">${v.jumlah_do}</td>
						// 		<td class="text-center" hidden>${v.principle_kode}</td>
						// 		<td class="text-center">${v.delivery_order_draft_kirim_kecamatan}</td>
						// 		<td class="text-center">${v.delivery_order_draft_kirim_area}</td>
						// 		<td class="text-center dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_latitude" >${v.client_pt_latitude}</td>
						// 		<td class="text-center dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_longitude" >${v.client_pt_longitude}</td>
						// 		<td class="text-center">${parseInt(v.sku_weight)}</td>
						// 		<td class="text-center">${parseInt(v.sku_volume)}</td>
						// 		<td class="text-center" hidden>${v.kirim_ulang == null ? '' : 'Kirim Ulang'}</td>
						// 		<td class="text-center">${v.composite}</td>
						// 		<td class="text-center">
						// 		<button class="btn btn-success btn-sm btn-view-detail-do" onclick="ViewDetailDO('${v.delivery_order_temp_id}','${v.delivery_order_draft_kirim_nama}','${v.delivery_order_draft_kirim_alamat}')"><i class="fa fa-eye"></i></button>
						// 		<button class="btn btn-danger btn-sm btn-delete-do-draft" onclick="DeleteDODraft(this,${i},'${v.delivery_order_temp_id}', '${v.delivery_order_draft_kirim_alamat}','${v.delivery_order_draft_kirim_nama}')"><i class="fa fa-trash"></i></button>
						// 		<button class="btn btn-warning btn-sm btn-location" onclick="GetLocation('${v.delivery_order_draft_kirim_alamat}')"><i class="fa-sharp fa-solid fa-location-dot"></i></button></td>
						// 		<td class="text-center"><input type="checkbox" style="transform: scale(1.5)" class="ChkboxDelOutlet" name="ChkboxDelOutlet" id="ChkboxDelOutlet" onchange="ChkboxDelOutlet(this)" value="${v.delivery_order_temp_id}_${v.delivery_order_draft_kirim_alamat}_${v.delivery_order_draft_kirim_nama}"></td>
						// 	</tr>
						// `);
						// 				} else {
						// 					$("#table-do-draft > tbody").append(`
						// 	<tr id="row-${i}" class="ui-sortable-handle">
						// 		<td class="text-center">
						// 			<span id="urutan-prioritas-${i}">${++no}</span>
						// 		</td>
						// 		<td style="display: none">
						// 			${v.delivery_order_temp_id}
						// 		</td>
						// 		<input type="hidden" class="latitudelongitude" data-nama="${v.delivery_order_draft_kirim_nama}" data-alamat="${v.delivery_order_draft_kirim_alamat}"  data-lat="${v.client_pt_latitude}" data-long="${v.client_pt_longitude}" data-prioritas="${++no2}"/>
						// 		<td class="text-center">${v.delivery_order_draft_kirim_nama}</td>
						// 		<td class="text-center">${v.delivery_order_draft_kirim_alamat}</td>
						// 		<td class="text-center">${v.delivery_order_draft_kirim_telp}</td>
						// 		<td class="text-center">${v.jumlah_do}</td>
						// 		<td class="text-center" hidden>${v.principle_kode}</td>
						// 		<td class="text-center">${v.delivery_order_draft_kirim_kecamatan}</td>
						// 		<td class="text-center">${v.delivery_order_draft_kirim_area}</td>
						// 		<td class="text-center dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_latitude" >${v.client_pt_latitude}</td>
						// 		<td class="text-center dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_longitude" >${v.client_pt_longitude}</td>
						// 		<td class="text-center">${parseInt(v.sku_weight)}</td>
						// 		<td class="text-center">${parseInt(v.sku_volume)}</td>
						// 		<td class="text-center" hidden>${v.kirim_ulang == null ? '' : 'Kirim Ulang'}</td>
						// 		<td class="text-center">${v.composite}</td>
						// 		<td class="text-center">
						// 		<button class="btn btn-success btn-sm btn-view-detail-do" onclick="ViewDetailDO('${v.delivery_order_temp_id}','${v.delivery_order_draft_kirim_nama}','${v.delivery_order_draft_kirim_alamat}')"><i class="fa fa-eye"></i></button>
						// 		<button class="btn btn-danger btn-sm btn-delete-do-draft" onclick="DeleteDODraft(this,${i},'${v.delivery_order_temp_id}', '${v.delivery_order_draft_kirim_alamat}','${v.delivery_order_draft_kirim_nama}')"><i class="fa fa-trash"></i></button>
						// 		<button class="btn btn-warning btn-sm btn-location" onclick="GetLocation('${v.delivery_order_draft_kirim_alamat}')"><i class="fa-sharp fa-solid fa-location-dot"></i></button></td>			
						// 		<td class="text-center"><input type="checkbox" style="transform: scale(1.5)" class="ChkboxDelOutlet" name="ChkboxDelOutlet" id="ChkboxDelOutlet" onchange="ChkboxDelOutlet(this)" value="${v.delivery_order_temp_id}_${v.delivery_order_draft_kirim_alamat}_${v.delivery_order_draft_kirim_nama}"></td>
						// 		</tr>
						// `);
						// 				}
						// 			});

						// 		} else {
						// 			arr_do = [];
						// 			arr_do_nama = [];
						// 			$("#table-do-draft > tbody").empty();
						// 		}

						// 	}
						// });

						// GET ID DO_DRAFT BY BATCH DI TABEL DELIVERY_ORDER_TEMP
						$.ajax({
							async: false,
							type: 'POST',
							url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetIDDOByBatch') ?>",
							data: {
								alamat_do: arr_do,
								nama_do: arr_do_nama,
								temp_do: temp_do
							},
							dataType: "JSON",
							success: function(response) {
								arr_do = [];
								$.each(response, function(i, v) {
									arr_do.push("'" + v.delivery_order_draft_id + "'");
								});
							}
						});

						// if (arr_do.length == 0) {
						// 	changeSimulasiPengurangan();

						// 	$("#deliveryorderbatch-kendaraan_berat_gr_terpakai").val(0);
						// 	$("#deliveryorderbatch-presentase_kendaraan_volume_cm3_terpakai").val('0%');

						// 	$("#deliveryorderbatch-kendaraan_volume_cm3_terpakai").val(0);
						// 	$("#deliveryorderbatch-presentase_kendaraan_berat_gr_terpakai").val('0%');

						// 	$("#deliveryorderbatch-presentase_kendaraan_berat_gr_terpakai").removeAttr("style");
						// 	$("#deliveryorderbatch-presentase_kendaraan_volume_cm3_terpakai").removeAttr("style");

						// 	selisihKapasitas();

						// 	$("#totalComposite").text(0);

						// 	return false;
						// }

						if (arr_do.length != 0) {
							appendSKU(arr_do)
						} else {
							$("#totalComposite").text(0);
						}

						getKapasitasTerpakai();

						// $.ajax({
						// 	async: false,
						// 	type: 'POST',
						// 	url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetSelectedDODraftSKU') ?>",
						// 	data: {
						// 		do_id: arr_do
						// 	},
						// 	dataType: "JSON",
						// 	success: function(response) {

						// 		$.each(response.data, function(i, v) {
						// 			$("#table-summary-sku > tbody").append(`
						// 	<tr id="row-${i}" class="ui-sortable-handle">
						// 		<td class="text-center">
						// 			${v.sku_kode}
						// 			<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-sku_id" value="${i}" />
						// 		</td>
						// 		<td class="text-center">${v.sku_nama_produk}</td>
						// 		<td class="text-center">${v.sku_kemasan}</td>
						// 		<td class="text-center">${v.sku_satuan}</td>
						// 		<td class="text-center">${v.sku_qty}</td>
						// 		<td class="text-center">${v.tipe_stock_nama}</td>
						// 		<td class="text-center">${v.sku_request_expdate}</td>
						// 		<td class="text-center">${v.sku_filter_expdate}</td>
						// 		<td class="text-center">${v.sku_filter_expdatebulan}</td>
						// 		<td class="text-center">
						// 			<button class="btn btn-success btn-sm btn-view-sku" onclick="ViewSKU('${v.sku_id}', '${v.sku_kode}', '${v.sku_nama_produk}')"><i class="fa fa-eye"></i></button>
						// 		</td>
						// 	</tr>
						// `);
						// 		});

						// 		$("#totalComposite").text(response.composite);
						// 	}
						// });

						// $.ajax({
						// 	async: false,
						// 	type: 'POST',
						// 	url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetKapasitasTerpakai') ?>",
						// 	data: {
						// 		do_id: arr_do
						// 	},
						// 	dataType: "JSON",
						// 	success: function(response) {

						// 		$.each(response, function(i, v) {
						// 			$("#deliveryorderbatch-kendaraan_berat_gr_terpakai")
						// 				.val(
						// 					parseInt(v.sku_weight));
						// 			$("#deliveryorderbatch-kendaraan_volume_cm3_terpakai")
						// 				.val(
						// 					parseInt(v.sku_volume));
						// 		});

						// 		selisihKapasitas();

						// 		presentaseKapasitasTerpakai();
						// 	}
						// });

					} else {
						// $("#deliveryorderbatch-kendaraan_berat_gr_terpakai").val(0);
						// $("#deliveryorderbatch-presentase_kendaraan_volume_cm3_terpakai").val('0%');

						// $("#deliveryorderbatch-kendaraan_volume_cm3_terpakai").val(0);
						// $("#deliveryorderbatch-presentase_kendaraan_berat_gr_terpakai").val('0%');

						// $("#deliveryorderbatch-presentase_kendaraan_berat_gr_terpakai").removeAttr("style");
						// $("#deliveryorderbatch-presentase_kendaraan_volume_cm3_terpakai").removeAttr("style");

						// selisihKapasitas();

						getKapasitasTerpakai();

						$("#totalComposite").text(0);
					}

					ResetForm();
					HeaderReadonly();
					// CekKapasitasiKendaraan();
					// changeSimulasiPengurangan();
				}
			});
		} else {
			message("Error!", "Pilih Outlet terlebih dahulu!", "error");
			return false;
		}
	}

	function presentaseKapasitasTerpakai() {
		var beratArmada = parseInt($("#deliveryorderbatch-kendaraan_berat_gr_max").val());
		var volumeArmada = parseInt($("#deliveryorderbatch-kendaraan_volume_cm3_max").val());
		var beratTerpakai = parseInt($("#deliveryorderbatch-kendaraan_berat_gr_terpakai").val());
		var volumeTerpakai = parseInt($("#deliveryorderbatch-kendaraan_volume_cm3_terpakai").val());

		if (beratArmada == 0 && volumeArmada == 0) {
			var presentaseBeratTerpakai = 0;
			var presentaseVolumeTerpakai = 0;
		} else {
			// var presentaseBeratTerpakai = Math.floor(beratTerpakai / beratArmada * 100 * 100) / 100 //rumus mengambil 2 angka dibelakang koma
			var presentaseBeratTerpakai = Math.floor(beratTerpakai / beratArmada * 100) //rumus mengambil 2 angka dibelakang koma
			// var presentaseVolumeTerpakai = Math.floor(volumeTerpakai / volumeArmada * 100 * 100) / 100 //rumus mengambil 2 angka dibelakang koma
			var presentaseVolumeTerpakai = Math.floor(volumeTerpakai / volumeArmada * 100) //rumus mengambil 2 angka dibelakang koma
		}

		$("#deliveryorderbatch-presentase_kendaraan_berat_gr_terpakai").val(presentaseBeratTerpakai + '%');
		$("#deliveryorderbatch-presentase_kendaraan_volume_cm3_terpakai").val(presentaseVolumeTerpakai + '%');

		if (Math.round(presentaseBeratTerpakai) > 100) {
			$("#deliveryorderbatch-presentase_kendaraan_berat_gr_terpakai").attr("style", "background-color: rgb(255, 153, 153); color: black;")
		} else {
			$("#deliveryorderbatch-presentase_kendaraan_berat_gr_terpakai").removeAttr("style");
		}

		if (Math.round(presentaseVolumeTerpakai) > 100) {
			$("#deliveryorderbatch-presentase_kendaraan_volume_cm3_terpakai").attr("style", "background-color: rgb(255, 153, 153); color: black;")
		} else {
			$("#deliveryorderbatch-presentase_kendaraan_volume_cm3_terpakai").removeAttr("style");
		}
	}

	function appendOutlet(temp_do, arr_do, arr_do_nama, arr_no_urut_do) {
		$.ajax({
			async: false,
			type: 'POST',
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetDOTempByIDTempWithNoUrut') ?>",
			data: {
				temp_do: temp_do,
				alamat_do: arr_do,
				nama_do: arr_do_nama,
				no_urut: arr_no_urut_do,
			},
			dataType: "JSON",
			success: function(response) {
				if (response.length > 0) {
					var no = 0;
					var no2 = 0;

					$("#table-do-draft > tbody").empty();

					// $("#table-do-draft > tbody").append(`
					// 	<tr>
					// 		<th class="text-center">Prioritas</th>
					// 		<th class="text-center">Nama</th>
					// 		<th class="text-center">Alamat</th>
					// 		<th class="text-center">Telp</th>
					// 		<th class="text-center">Jumlah DO</th>
					// 		<th class="text-center" hidden>Principle</th>
					// 		<th class="text-center">Kecamatan</th>
					// 		<th class="text-center">Area</th>
					// 		<th class="text-center">Latitude</th>
					// 		<th class="text-center">Longitude</th>
					// 		<th class="text-center">Berat(gram)</th>
					// 		<th class="text-center">Volume(cm<sup>3</sup>)</th>
					// 		<th class="text-center" hidden>Kirim Ulang</th>
					// 		<th class="text-center">Composite</th>
					// 		<th class="text-center">Action</th>
					// 		<th class="text-center">Pilih Semua &nbsp; <input type="checkbox" style="transform: scale(1.5)" name="allDelOutlet" id="allDelOutlet" onchange="allDelOutlet(this.checked)" value="1"></th>
					// 	</tr>
					// `);

					// Get Kapasitas Berat dan Volume Armada
					var beratArmada = $("#deliveryorderbatch-kendaraan_berat_gr_max").val();
					var volumeArmada = $("#deliveryorderbatch-kendaraan_volume_cm3_max").val();
					var totalBeratDO = 0;
					var totalVolumeDO = 0;

					$.each(response, function(i, v) {
						totalBeratDO = totalBeratDO + parseInt(v.sku_weight);
						totalVolumeDO = totalVolumeDO + parseInt(v.sku_volume);

						if (totalBeratDO > beratArmada || totalVolumeDO > volumeArmada) {
							$("#table-do-draft > tbody").append(`
								<tr id="row-${i}" class="ui-sortable-handle" style="background-color: #FF9999; color: black;">
								<td class="text-center">
									<span id="urutan-prioritas-${i}">${++no}</span>
								</td>
								<input type="hidden" class="latitudelongitude" data-nama="${v.delivery_order_draft_kirim_nama}" data-alamat="${v.delivery_order_draft_kirim_alamat}"  data-lat="${v.client_pt_latitude}" data-long="${v.client_pt_longitude}" data-prioritas="${++no2}"/>
								<td class="text-center resetPrioritas">${v.delivery_order_draft_kirim_nama}</td>
								<td class="text-center resetPrioritas">${v.delivery_order_draft_kirim_alamat}</td>
								<td class="text-center resetPrioritas">${v.delivery_order_draft_kirim_telp}</td>
								<td class="text-center resetPrioritas">${v.jumlah_do}</td>
								<td class="text-center resetPrioritas">${v.delivery_order_draft_kirim_kecamatan}</td>
								<td class="text-center resetPrioritas">${v.delivery_order_draft_kirim_area}</td>
								<td class="text-center resetPrioritas dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_latitude" >${v.client_pt_latitude}</td>
								<td class="text-center resetPrioritas dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_longitude" >${v.client_pt_longitude}</td>
								<td class="text-center resetPrioritas">${parseInt(v.sku_weight)}</td>
								<td class="text-center resetPrioritas">${parseInt(v.sku_volume)}</td>
								<td class="text-center resetPrioritas">${v.composite}</td>
								<td style="display: none">
									${v.delivery_order_temp_id}
								</td>
								<td class="text-center">
								<button class="btn btn-success btn-sm btn-view-detail-do" onclick="ViewDetailDO('${v.delivery_order_temp_id}','${v.delivery_order_draft_kirim_nama}','${v.delivery_order_draft_kirim_alamat}')"><i class="fa fa-eye"></i></button>` +
								// <button class="btn btn-danger btn-sm btn-delete-do-draft" onclick="DeleteDODraft(this,${i},'${v.delivery_order_temp_id}', '${v.delivery_order_draft_kirim_alamat}','${v.delivery_order_draft_kirim_nama}')"><i class="fa fa-trash"></i></button>
								`<button class="btn btn-warning btn-sm btn-location" onclick="GetLocation('${v.delivery_order_draft_kirim_alamat}')"><i class="fa-sharp fa-solid fa-location-dot"></i></button></td>
								<td class="text-center"><input type="checkbox" style="transform: scale(1.5)" class="ChkboxDelOutlet" data-berat="${parseInt(v.sku_weight)}" data-volume="${parseInt(v.sku_volume)}" name="ChkboxDelOutlet" id="ChkboxDelOutlet" onchange="ChkboxDelOutlet(this)" value="${v.delivery_order_temp_id}_${v.delivery_order_draft_kirim_alamat}_${v.delivery_order_draft_kirim_nama}"></td>
								</tr>
						`);
						} else {
							$("#table-do-draft > tbody").append(`
								<tr id="row-${i}" class="ui-sortable-handle">
								<td class="text-center">
									<span id="urutan-prioritas-${i}">${++no}</span>
								</td>
								<input type="hidden" class="latitudelongitude" data-nama="${v.delivery_order_draft_kirim_nama}" data-alamat="${v.delivery_order_draft_kirim_alamat}"  data-lat="${v.client_pt_latitude}" data-long="${v.client_pt_longitude}" data-prioritas="${++no2}"/>
								<td class="text-center resetPrioritas">${v.delivery_order_draft_kirim_nama}</td>
								<td class="text-center resetPrioritas">${v.delivery_order_draft_kirim_alamat}</td>
								<td class="text-center resetPrioritas">${v.delivery_order_draft_kirim_telp}</td>
								<td class="text-center resetPrioritas">${v.jumlah_do}</td>
								<td class="text-center resetPrioritas">${v.delivery_order_draft_kirim_kecamatan}</td>
								<td class="text-center resetPrioritas">${v.delivery_order_draft_kirim_area}</td>
								<td class="text-center resetPrioritas dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_latitude" >${v.client_pt_latitude}</td>
								<td class="text-center resetPrioritas dblclkRow" data-client-pt-id="${v.client_pt_id}" data-mode="client_pt_longitude" >${v.client_pt_longitude}</td>
								<td class="text-center resetPrioritas">${parseInt(v.sku_weight)}</td>
								<td class="text-center resetPrioritas">${parseInt(v.sku_volume)}</td>
								<td class="text-center resetPrioritas">${v.composite}</td>
								<td style="display: none">
									${v.delivery_order_temp_id}
								</td>
								<td class="text-center">
								<button class="btn btn-success btn-sm btn-view-detail-do" onclick="ViewDetailDO('${v.delivery_order_temp_id}','${v.delivery_order_draft_kirim_nama}','${v.delivery_order_draft_kirim_alamat}')"><i class="fa fa-eye"></i></button>` +
								// <button class="btn btn-danger btn-sm btn-delete-do-draft" onclick="DeleteDODraft(this,${i},'${v.delivery_order_temp_id}', '${v.delivery_order_draft_kirim_alamat}','${v.delivery_order_draft_kirim_nama}')"><i class="fa fa-trash"></i></button>
								`<button class="btn btn-warning btn-sm btn-location" onclick="GetLocation('${v.delivery_order_draft_kirim_alamat}')"><i class="fa-sharp fa-solid fa-location-dot"></i></button></td>
								<td class="text-center"><input type="checkbox" style="transform: scale(1.5)" class="ChkboxDelOutlet" data-berat="${parseInt(v.sku_weight)}" data-volume="${parseInt(v.sku_volume)}" name="ChkboxDelOutlet" id="ChkboxDelOutlet" onchange="ChkboxDelOutlet(this)" value="${v.delivery_order_temp_id}_${v.delivery_order_draft_kirim_alamat}_${v.delivery_order_draft_kirim_nama}"></td>
								</tr>
							`);
						}
					});

				} else {
					arr_do = [];
					arr_do_nama = [];
					$("#table-do-draft > tbody").empty();
				}

			}
		});

		resetTablesorter();
		dblclick();
	}

	function appendSKU(arr_do) {
		$.ajax({
			async: false,
			type: 'POST',
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetSelectedDODraftSKU') ?>",
			data: {
				do_id: arr_do
			},
			dataType: "JSON",
			success: function(response) {

				$.each(response.data, function(i, v) {
					$("#table-summary-sku > tbody").append(`
							<tr id="row-${i}" class="ui-sortable-handle">
							<td class="text-center">${v.principle_kode}</td>
								<td class="text-center">
									${v.sku_kode}
									<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-sku_id" value="${i}" />
								</td>
								<td class="text-center">${v.sku_nama_produk}</td>
								<td class="text-center">${v.sku_kemasan}</td>
								<td class="text-center">${v.sku_satuan}</td>
								<td class="text-center">${v.sku_qty}</td>
								<td class="text-center">${v.tipe_stock_nama}</td>
								<td class="text-center">${v.sku_request_expdate}</td>
								<td class="text-center">${v.sku_filter_expdate}</td>
								<td class="text-center">${v.sku_filter_expdatebulan}</td>
								<td class="text-center">
									<button class="btn btn-success btn-sm btn-view-sku" onclick="ViewSKU('${v.sku_id}', '${v.sku_kode}', '${v.sku_nama_produk}')"><i class="fa fa-eye"></i></button>
								</td>
							</tr>
						`);
				});

				$("#totalComposite").text(response.composite);

				resetTablesorter();
			}
		});
	}

	function initTableSorter() {
		$("#table-do-draft th:eq(0)").data("sorter", false);
		$("#table-do-draft th:eq(13)").data("sorter", false);
		$("#table-do-draft th:eq(14)").data("sorter", false);

		$("#table-do-draft").tablesorter({
			theme: 'blue',
			sortReset: true,
			sortRestart: true
		});
	}


	function resetTablesorter() {
		// Hapus plugin sebelum menginisialisasi ulang
		$("#table-do-draft").trigger("destroy");

		$("#table-do-draft th:eq(0)").data("sorter", false);
		$("#table-do-draft th:eq(13)").data("sorter", false);
		$("#table-do-draft th:eq(14)").data("sorter", false);

		$("#table-summary-sku").trigger("destroy");

		$("#table-summary-sku th:eq(10)").data("sorter", false);

		$("#table-do-draft").tablesorter({
			theme: 'blue',
			sortReset: true,
			sortRestart: true
		});

		$("#table-summary-sku").tablesorter({
			theme: 'blue',
			sortReset: true,
			sortRestart: true
		});
	}

	function resetTableSorterSimulasiArea() {
		// Hapus plugin sebelum menginisialisasi ulang
		$("#data-table-simulasi-area").trigger("destroy");

		$("#data-table-simulasi-area th:eq(0)").data("sorter", false);
		$("#data-table-simulasi-area th:eq(1)").data("sorter", false);
		$("#data-table-simulasi-area th:eq(3)").data("sorter", false);
		$("#data-table-simulasi-area th:eq(8)").data("sorter", false);

		$("#data-table-simulasi-area").tablesorter({
			theme: 'blue',
			sortReset: true,
			sortRestart: true
		});

		$('.trStyle').removeAttr('style');
	}

	$('.resetPrioritas').on('click', function() {
		setTimeout(() => {
			$('#table-do-draft tbody tr').each(function(index) {
				$(this).find('td:eq(0)').text(index + 1);
			})
		}, 100);
	});

	function resetSimulasiKapasitas() {
		$('#pengurangan_terpakai_berat').text('')
		$('#pengurangan_terpakai_volume').text('')
		$('#pengurangan_presentase_berat').text('')
		$('#pengurangan_presentase_volume').text('')
		$('#pengurangan_selisih_berat').text('')
		$('#pengurangan_selisih_volume').text('')

		$('.ChkboxDelOutlet').prop('checked', false);
		$('#jml_outlet').text('(0)');
	}

	function getKapasitasTerpakai() {

		// $.ajax({
		// 	async: false,
		// 	type: 'POST',
		// 	url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/GetKapasitasTerpakai') ?>",
		// 	data: {
		// 		do_id: arr_do,
		// 		kendaraan_id: $('#deliveryorderbatch-kendaraan_id').val() != '' ? $('#deliveryorderbatch-kendaraan_id').val() : ''
		// 	},
		// 	dataType: "JSON",
		// 	success: function(response) {

		// 		// $.each(response, function(i, v) {
		// 		// $("#deliveryorderbatch-kendaraan_berat_gr_terpakai").val(parseInt(v
		// 		// 	.sku_weight));
		// 		// $("#deliveryorderbatch-kendaraan_volume_cm3_terpakai").val(parseInt(
		// 		// 	v
		// 		// 	.sku_volume));

		// 		// });

		// 		$("#deliveryorderbatch-kendaraan_berat_gr_terpakai").val(parseInt(response
		// 			.sku_weight));
		// 		$("#deliveryorderbatch-kendaraan_volume_cm3_terpakai").val(parseInt(
		// 			response
		// 			.sku_volume));

		// 		presentaseKapasitasTerpakai();

		// 		selisihKapasitas();

		// 		CekKapasitasiKendaraan();
		// 	}
		// });

		var kendaraan_berat_gr_terpakai = $("#deliveryorderbatch-kendaraan_berat_gr_terpakai").val()
		var kendaraan_volume_cm3_terpakai = $("#deliveryorderbatch-kendaraan_volume_cm3_terpakai").val()

		if (totalPenambahanBerat != 0 || totalPenambahanVolume != 0) {
			$("#deliveryorderbatch-kendaraan_berat_gr_terpakai").val(parseInt(kendaraan_berat_gr_terpakai) + parseInt(totalPenambahanBerat));
			$("#deliveryorderbatch-kendaraan_volume_cm3_terpakai").val(parseInt(kendaraan_volume_cm3_terpakai) + parseInt(totalPenambahanVolume));

			totalPenambahanBerat = 0
			totalPenambahanVolume = 0
		} else {
			$("#deliveryorderbatch-kendaraan_berat_gr_terpakai").val(parseInt(kendaraan_berat_gr_terpakai) - parseInt(totalPenguranganBerat));
			$("#deliveryorderbatch-kendaraan_volume_cm3_terpakai").val(parseInt(kendaraan_volume_cm3_terpakai) - parseInt(totalPenguranganVolume));

			totalPenguranganBerat = 0
			totalPenguranganVolume = 0
		}

		presentaseKapasitasTerpakai();

		selisihKapasitas();

		CekKapasitasiKendaraan();

		$('#pengurangan_terpakai_berat').text('')
		$('#pengurangan_terpakai_volume').text('')
		$('#pengurangan_presentase_berat').text('')
		$('#pengurangan_presentase_volume').text('')
		$('#pengurangan_selisih_berat').text('')
		$('#pengurangan_selisih_volume').text('')
	}

	function modalRitasi() {
		$('#filter-do-pengiriman-date-ritasi').val('<?= date('Y-m-d') ?>');
		$('#kendaraanRitasi').val('').trigger('change');
		$('#driverRitasi').val('').trigger('change');;
		$('#ritasi').val('');
		$('#table-ritasi > tbody').empty();
		$(`#btn_buat_baru_ritasi`).hide();

		$('#modalRitasi').modal('show');
	}

	$('#btn-search-data-ritasi').on('click', function() {
		var tgl_kirim = $('#filter-do-pengiriman-date-ritasi').val();
		var kendaraan = $('#kendaraanRitasi').val();
		var driver = $('#driverRitasi').val();
		var ritasi = $('#ritasi').val();

		if (tgl_kirim == "" || kendaraan == "" || ritasi == "" || driver == "") {
			message("Info!", "Tidak boleh ada yang kosong!", "info");
			return false;
		}

		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/getRitasi') ?>",
			data: {
				tgl_kirim: tgl_kirim,
				kendaraan: kendaraan,
				driver: driver,
				ritasi: ritasi
			},
			dataType: "JSON",
			success: function(response) {
				$('#table-ritasi > tbody').empty();

				if (response != 0) {
					$('#table-ritasi > tbody').append(`
					<tr>
						<td class="text-center">${response.tgl_kirim}</td>
						<td class="text-center">${response.kendaraan_nopol} - ${response.kendaraan_model}</td>
						<td class="text-center">${response.karyawan_nama}</td>
						<td class="text-center">${response.delivery_order_batch_h_ritasi}</td>
						<td class="text-center">${response.kendaraan_berat_gr_sisa}</td>
						<td class="text-center">${response.kendaraan_volume_cm3_sisa}</td>
						<td class="text-center"><button type="button" class="btn btn-success" onclick="pilihRitasi('${response.tgl_kirim}', '${response.kendaraan_id}', '${response.karyawan_id}', '${response.delivery_order_batch_h_ritasi}')"><i class="fas fa-check"></i> <label name="CAPTION-PILIH">Pilih</label></button></td>
					</tr>
					`);

					$(`#btn_buat_baru_ritasi`).hide();
				} else {
					$('#table-ritasi > tbody').append(`
					<tr>
						<td class="text-center" style="color:red;" colspan="6">Data Kosong</td>
					</tr>
					`);

					$(`#btn_buat_baru_ritasi`).show();
				}
			}
		})
	});

	$(`#btn_buat_baru_ritasi`).on('click', function() {
		var tgl_kirim = $('#filter-do-pengiriman-date-ritasi').val();
		var kendaraan = $('#kendaraanRitasi').val();
		var driver = $('#driverRitasi').val();
		var ritasi = $('#ritasi').val();

		window.location.href = "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/create?') ?>tgl_kirim=" + tgl_kirim + "&kendaraan=" + kendaraan + "&driver=" + driver + "&ritasi=" + ritasi + "&mode=" + 'new';
	})

	function pilihRitasi(tgl_kirim, kendaraan, driver, ritasi) {
		window.location.href = "<?= base_url('WMS/Distribusi/DeliveryOrderBatch/create?') ?>tgl_kirim=" + tgl_kirim + "&kendaraan=" + kendaraan + "&driver=" + driver + "&ritasi=" + ritasi + "&mode=" + 'last';
	}
</script>