<script type="text/javascript">
	var index_pallet = 0;
	// var global_id = $("#mutasi_draft_no").val();

	const html5QrCode = new Html5Qrcode("preview");
	const html5QrCode2 = new Html5Qrcode("preview_by_one");
	let timerInterval
	loadingBeforeReadyPage()
	$(document).ready(
		function() {
			$(".select2").select2();
			<?php if ($act == "MutasiPalletDetail") { ?>
				$("#table_mutasi_pallet > tbody tr").each(function(i, v) {
					let check = $(this).find("td:eq(7) input[type='checkbox']");
					// let check = $(this).find("td:eq(7) input[id='check_scan_'" + i + "]");
					let scan = $(this).find("td:eq(7) button[name='start_scan_by_one']");
					let input = $(this).find("td:eq(7) button[name='input_rak']");
					check.change(function(e) {
						if (e.target.checked) {
							input.show();
							scan.hide();
						} else {
							input.hide();
							scan.show();
						}
					});
				});
			<?php } ?>

		}
	);

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

	function GetMutasiDraft(tr_mutasi_pallet_draft_id) {

		// global_id = tr_mutasi_pallet_draft_id;
		$("#global_id").val(tr_mutasi_pallet_draft_id)


		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/MutasiPallet/GetMutasiDraft') ?>",
			data: {
				tr_mutasi_pallet_draft_id: tr_mutasi_pallet_draft_id
			},
			success: function(response) {
				if (response) {
					ChMutasiDraft(response);
				}
			}
		});
	}

	function ChMutasiDraft(JSONMutasiPallet) {
		$("#table_mutasi_pallet > tbody").empty();

		var MutasiPallet = JSON.parse(JSONMutasiPallet);
		var no = 1;

		var tr_mutasi_pallet_draft_id = MutasiPallet.MutasiPalletDraft[0].tr_mutasi_pallet_draft_id;
		var tr_mutasi_pallet_draft_kode = MutasiPallet.MutasiPalletDraft[0].tr_mutasi_pallet_draft_kode;
		var tr_mutasi_pallet_draft_tanggal = MutasiPallet.MutasiPalletDraft[0].tr_mutasi_pallet_draft_tanggal;
		var depo_detail_id_asal = MutasiPallet.MutasiPalletDraft[0].depo_detail_id_asal;
		var gudang_asal = MutasiPallet.MutasiPalletDraft[0].gudang_asal;
		var depo_detail_id_tujuan = MutasiPallet.MutasiPalletDraft[0].depo_detail_id_tujuan;
		var gudang_tujuan = MutasiPallet.MutasiPalletDraft[0].gudang_tujuan;
		var client_wms_nama = MutasiPallet.MutasiPalletDraft[0].client_wms_nama;
		var principle_nama = MutasiPallet.MutasiPalletDraft[0].principle_nama;
		var tr_mutasi_pallet_draft_nama_checker = MutasiPallet.MutasiPalletDraft[0].tr_mutasi_pallet_draft_nama_checker;
		var tr_mutasi_pallet_draft_tipe = MutasiPallet.MutasiPalletDraft[0].tr_mutasi_pallet_draft_tipe;
		var tr_mutasi_pallet_draft_status = MutasiPallet.MutasiPalletDraft[0].tr_mutasi_pallet_draft_status;
		var tr_mutasi_pallet_draft_tgl_update = MutasiPallet.MutasiPalletDraft[0].tr_mutasi_pallet_draft_tgl_update;


		$("#principle_pallet").val(tr_mutasi_pallet_draft_kode);
		$("#gudang_asal_mutasi").append('<option value="' + depo_detail_id_asal + '">' + gudang_asal + '</option>');
		$("#gudang_tujuan_mutasi").append('<option value="' + depo_detail_id_tujuan + '">' + gudang_tujuan + '</option>');
		$("#mutasi_perusahaan").val(client_wms_nama);
		$("#mutasi_principle").val(principle_nama);
		$("#mutasi_checker").val(tr_mutasi_pallet_draft_nama_checker);
		$("#mutasi_tipe_transaksi").val(tr_mutasi_pallet_draft_tipe);
		$("#lastUpdated").val(tr_mutasi_pallet_draft_tgl_update);

		if (MutasiPallet.Pallet != 0) {
			// if ($.fn.DataTable.isDataTable('#table_mutasi_pallet')) {
			// 	$('#table_mutasi_pallet').DataTable().destroy();
			// }

			$('#table_mutasi_pallet > tbody').empty();

			for (i = 0; i < MutasiPallet.Pallet.length; i++) {
				var tr_mutasi_pallet_detail_draft_id = MutasiPallet.Pallet[i].tr_mutasi_pallet_detail_draft_id;
				var tr_mutasi_pallet_draft_id = MutasiPallet.Pallet[i].tr_mutasi_pallet_draft_id;
				var pallet_id = MutasiPallet.Pallet[i].pallet_id;
				var pallet_kode = MutasiPallet.Pallet[i].pallet_kode;
				var pallet_jenis_id = MutasiPallet.Pallet[i].pallet_jenis_id;
				var pallet_jenis_nama = MutasiPallet.Pallet[i].pallet_jenis_nama;
				var pallet_is_aktif = MutasiPallet.Pallet[i].pallet_is_aktif;
				var rak_id = MutasiPallet.Pallet[i].rak_id;
				var rak_lajur_nama = MutasiPallet.Pallet[i].rak_lajur_nama;
				var rak_lajur_detail_id = MutasiPallet.Pallet[i].rak_lajur_detail_id;
				var rak_lajur_detail_nama = MutasiPallet.Pallet[i].rak_lajur_detail_nama;
				var rak_lajur_detail_id_tujuan = MutasiPallet.Pallet[i].rak_lajur_detail_id_tujuan;
				var rak_lajur_detail_tujuan_nama = MutasiPallet.Pallet[i].rak_lajur_detail_tujuan_nama;
				var status = MutasiPallet.Pallet[i].is_valid;
				var rak = "";

				if (rak_lajur_nama == "null") {
					rak += `<input type="text" class="form-control" name="rak_jalur" id="rak_jalur" disabled placeholder="Lokasi Bin Tujuan"/>`;
				} else {
					rak += `<input type="text" class="form-control" name="rak_jalur" id="rak_jalur" value="` + rak_lajur_nama + `" disabled/>`;
				}

				if (status == 0) {
					status = "<span class='btn btn-danger' style='cursor: context-menu;padding:0px'>Tidak Valid</span>";
				} else if (status == 1) {
					status = "<span class='btn btn-success' style='cursor: context-menu;padding:0px'>Valid </span>";
				} else {
					status = "<span class='btn btn-danger' style='cursor: context-menu;padding:0px'>Belum Divalidasi</span>";
				}

				$("#table_mutasi_pallet > tbody").append(`
					<tr>
						<td class="text-center">${no++} <input type="hidden" class="form-control" name="id_detail[]" id="id_detail" value="` + tr_mutasi_pallet_detail_draft_id + `"/></td>
						<td class="text-center">${pallet_kode}</td>
						<td class="text-center">${pallet_jenis_nama}</td>
						<td class="text-center">${status}</td>
						<td class="text-center">${rak}</td>
						<td class="text-center">${rak_lajur_detail_nama}</td>
						<td class="text-center"><div id="rak_tujuan_${i}">${rak_lajur_detail_tujuan_nama}</div></td>
						<td class="text-center">
							<div class="row">
								<div class="head-switch">
									<div class="switch-holder">
										<div class="switch-toggle">
											<input type="checkbox" id="check_scan_${i}" class="check_scan">
											<label for="check_scan_${i}"></label>
										</div>
										<div class="switch-label">
											<button type="button" class="btn btn-info start_scan_by_one" name="start_scan_by_one" data-id="${pallet_id}" data-idx="${i}" data-detail-id="${tr_mutasi_pallet_detail_draft_id}"> <i class="fas fa-qrcode"></i> Scan</button>
											<button type="button" class="btn btn-warning input_rak" name="input_rak" data-id="${pallet_id}" data-idx="${i}" data-detail-id="${tr_mutasi_pallet_detail_draft_id}" style="display:none"> <i class="fas fa-keyboard"></i> Input</button>
										</div>
									</div>
								</div>
							</div>
						</td>
						<td class="text-center">
							<button type="button" data-toggle="tooltip" data-placement="top" data-id="` + tr_mutasi_pallet_detail_draft_id + `" title="detail pallet" class="btn btn-primary detail_pallet" onclick="ViewDetailPallet('${pallet_id}')"><i class="fas fa-eye"></i> Detail</button>
						</td>
					</tr>
				`);

				$("#table_mutasi_pallet > tbody tr").each(function(i, v) {
					let check = $(this).find("td:eq(7) input[type='checkbox']");
					// let check = $(this).find("td:eq(7) input[id='check_scan_'" + i + "]");
					let scan = $(this).find("td:eq(7) button[name='start_scan_by_one']");
					let input = $(this).find("td:eq(7) button[name='input_rak']");
					check.change(function(e) {
						if (e.target.checked) {
							input.show();
							scan.hide();
						} else {
							input.hide();
							scan.show();
						}
					});
				});
			}

			$("#loadingview").hide();

			$('#table_mutasi_pallet').DataTable({
				destroy: true,
				columnDefs: [{
					sortable: false,
					targets: [0, 1, 2, 3, 4, 5, 6, 7]
				}],
				lengthMenu: [
					[-1],
					['All']
				],
				"bInfo": false,
				"paging": false,
				"searching": false
			});

		}
	}

	function reload_pallet() {
		let idd = $("#global_id").val();

		<?php if ($act == "MutasiPalletDetail") { ?>
			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/MutasiPallet/ReloadPallet') ?>",
				data: {
					tr_mutasi_pallet_draft_id: idd,
					act: "update"
				},
				success: function(response) {
					if (response) {
						ChReloadPallet(response);
					}
				}
			});
		<?php } else { ?>
			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/MutasiPallet/ReloadPallet') ?>",
				data: {
					tr_mutasi_pallet_draft_id: idd,
					act: "add"
				},
				success: function(response) {
					if (response) {
						ChReloadPallet(response);
					}
				}
			});
		<?php } ?>

	}

	function ChReloadPallet(JSONMutasiPallet) {
		// $("#table_mutasi_pallet > tbody").empty();

		var MutasiPallet = JSON.parse(JSONMutasiPallet);
		console.log(MutasiPallet.Pallet);



		if (MutasiPallet.Pallet.length != 0) {
			// if ($.fn.DataTable.isDataTable('#table_mutasi_pallet')) {
			// 	$('#table_mutasi_pallet').DataTable().destroy();
			// }

			console.log('awdawd');

			$('#table_mutasi_pallet tbody').empty();
			$.each(MutasiPallet.Pallet, function(i, v) {
				let tr_mutasi_pallet_detail_draft_id = v.tr_mutasi_pallet_detail_draft_id;
				let tr_mutasi_pallet_draft_id = v.tr_mutasi_pallet_draft_id;
				let pallet_id = v.pallet_id;
				let pallet_kode = v.pallet_kode;
				let pallet_jenis_id = v.pallet_jenis_id;
				let pallet_jenis_nama = v.pallet_jenis_nama;
				let pallet_is_aktif = v.pallet_is_aktif;
				let rak_id = v.rak_id;
				let rak_lajur_nama = v.rak_lajur_nama;
				let rak_lajur_detail_id = v.rak_lajur_detail_id;
				let rak_lajur_detail_nama = v.rak_lajur_detail_nama;
				let rak_lajur_detail_id_tujuan = v.rak_lajur_detail_id_tujuan;
				let rak_lajur_detail_tujuan_nama = v.rak_lajur_detail_tujuan_nama;
				let status = v.is_valid;
				let rak = "";

				console.log({
					tr_mutasi_pallet_detail_draft_id,
					tr_mutasi_pallet_draft_id,
					pallet_id,
					pallet_kode,
					pallet_jenis_id,
					pallet_jenis_nama,
					pallet_is_aktif,
					rak_id,
					rak_lajur_nama,
					rak_lajur_detail_id,
					rak_lajur_detail_nama,
					rak_lajur_detail_id_tujuan,
					status
				});

				if (rak_lajur_nama == "null") {
					rak += `<input type="text" class="form-control" name="rak_jalur" id="rak_jalur" disabled placeholder="Lokasi Bin Tujuan"/>`;
				} else {
					rak += `<input type="text" class="form-control" name="rak_jalur" id="rak_jalur" value="` + rak_lajur_nama + `" disabled/>`;
				}

				if (status == 0) {
					status = "<span class='btn btn-danger' style='cursor: context-menu;padding:0px'>Tidak Valid</span>";
				} else if (status == 1) {
					status = "<span class='btn btn-success' style='cursor: context-menu;padding:0px'>Valid </span>";
				} else {
					status = "<span class='btn btn-danger' style='cursor: context-menu;padding:0px'>Belum Divalidasi</span>";
				}

				$("#table_mutasi_pallet > tbody").append(`
					<tr>
						<td class="text-center">${i + 1} <input type="hidden" class="form-control" name="id_detail[]" id="id_detail" value="` + tr_mutasi_pallet_detail_draft_id + `"/></td>
						<td class="text-center">${pallet_kode}</td>
						<td class="text-center">${pallet_jenis_nama}</td>
						<td class="text-center">${status}</td>
						<td class="text-center">${rak}</td>
						<td class="text-center">${rak_lajur_detail_nama}</td>
						<td class="text-center"><div id="rak_tujuan_${i}">${rak_lajur_detail_tujuan_nama}</div></td>
						<td class="text-center">
							<div class="row">
								<div class="head-switch">
									<div class="switch-holder">
										<div class="switch-toggle">
											<input type="checkbox" id="check_scan_${i}" class="check_scan">
											<label for="check_scan_${i}"></label>
										</div>
										<div class="switch-label">
											<button type="button" class="btn btn-info start_scan_by_one" name="start_scan_by_one" data-id="${pallet_id}" data-idx="${i}" data-detail-id="${tr_mutasi_pallet_detail_draft_id}"> <i class="fas fa-qrcode"></i> Scan</button>
											<button type="button" class="btn btn-warning input_rak" name="input_rak" data-id="${pallet_id}" data-idx="${i}" data-detail-id="${tr_mutasi_pallet_detail_draft_id}" style="display:none"> <i class="fas fa-keyboard"></i> Input</button>
										</div>
									</div>
								</div>
							</div>
						</td>
						<td class="text-center">
							<button type="button" data-toggle="tooltip" data-placement="top" data-id="` + tr_mutasi_pallet_detail_draft_id + `" title="detail pallet" class="btn btn-primary detail_pallet" onclick="ViewDetailPallet('${pallet_id}')"><i class="fas fa-eye"></i> Detail</button>
						</td>
					</tr>
				`);

				$("#table_mutasi_pallet > tbody tr").each(function(i, v) {
					let check = $(this).find("td:eq(7) input[type='checkbox']");
					// let check = $(this).find("td:eq(7) input[id='check_scan_'" + i + "]");
					let scan = $(this).find("td:eq(7) button[name='start_scan_by_one']");
					let input = $(this).find("td:eq(7) button[name='input_rak']");
					check.change(function(e) {
						if (e.target.checked) {
							input.show();
							scan.hide();
						} else {
							input.hide();
							scan.show();
						}
					});
				});
			})


			$("#loadingview").hide();

			$('#table_mutasi_pallet').DataTable();

			// {
			// 	"lengthMenu": [
			// 		[-1],
			// 		["All"]
			// 	],
			// 	"ordering": false,
			// 	"bInfo": false,
			// 	"paging": false,
			// }
		}
	}

	$("#btn_save_mutasi").click(
		function() {

			// global_id = $("#mutasi_draft_no").val();
			let idd = $("#global_id").val()

			if (idd != "") {
				$("#table_mutasi_pallet > tbody tr").each(function() {
					let is_valid = $(this).find("td:eq(3)").text().replace(/\s+/g, '');
					let rak = $(this).find("td:eq(4) input[type='text']").val();
					if (is_valid != "Valid") {
						message("Error!", "Pallet masih ada yang belum tervalidasi, silahkan cek terlebih dahulu!", "error");
						return false;
					} else if (rak == "") {
						message("Error!", "Lokasi Bin Tujuan tidak boleh kosong!", "error");
						return false;
					} else {
						Swal.fire({
							title: "Apakah anda yakin?",
							text: "Pastikan data yang sudah anda input benar!",
							icon: "warning",
							showCancelButton: true,
							confirmButtonColor: "#3085d6",
							cancelButtonColor: "#d33",
							confirmButtonText: "Ya, Simpan",
							cancelButtonText: "Tidak, Tutup"
						}).then((result) => {
							if (result.value == true) {
								// $("#loadingview").show();
								//ajax save data

								messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Iya, Simpan', 'Tidak, Tutup').then((result) => {
									if (result.value == true) {
										requestAjax("<?= base_url('WMS/MutasiPallet/InsertMutasiPallet'); ?>", {
											tr_mutasi_pallet_draft_id: $("#mutasi_draft_no").val(),
											tipe_mutasi: $("#mutasi_tipe_transaksi").val(),
											mutasi_status: $("#mutasi_status").val(),
											mutasi_keterangan: $("#mutasi_keterangan").val(),
											lastUpdated: $("#lastUpdated").val()
										}, "POST", "JSON", function(response) {
											if (response == 1) {
												message_topright("success", "Data berhasil disimpan");
												setTimeout(() => {
													// location.reload();
													location.href = "<?= base_url() ?>WMS/MutasiPallet/MutasiPalletMenu";
												}, 800);

											}
											if (response == 0) return message_topright("error", "Data gagal disimpan");
											if (response == 2) return messageNotSameLastUpdated()
										}, "#btn_save_mutasi")
									}
								});
								// $.ajax({
								// 	async: false,
								// 	type: 'POST',
								// 	url: "<?= base_url('WMS/MutasiPallet/InsertMutasiPallet') ?>",
								// 	data: {
								// 		tr_mutasi_pallet_draft_id: $("#mutasi_draft_no").val(),
								// 		tipe_mutasi: $("#mutasi_tipe_transaksi").val(),
								// 		mutasi_status: $("#mutasi_status").val(),
								// 		mutasi_keterangan: $("#mutasi_keterangan").val(),
								// 		lastUpdated: $("#lastUpdated").val()
								// 	},
								// 	success: function(response) {
								// 		if (response == 1) {
								// 			$("#loadingview").hide();

								// 			Swal.fire({
								// 				icon: 'success',
								// 				title: 'Success',
								// 				text: 'Mutasi Pallet Berhasil Dibuat',
								// 				showConfirmButton: false,
								// 				timer: 1000
								// 			});

								// 			location.href = "<?= base_url() ?>WMS/MutasiPallet/MutasiPalletMenu";

								// 		} else {
								// 			$("#loadingview").hide();

								// 			var msg = response;
								// 			var msgtype = 'error';

								// 			//if (!window.__cfRLUnblockHandlers) return false;
								// 			new PNotify
								// 				({
								// 					title: 'Error',
								// 					text: msg,
								// 					type: msgtype,
								// 					styling: 'bootstrap3',
								// 					delay: 3000,
								// 					stack: stack_center
								// 				});
								// 		}
								// 	}

								// });
							}
						});

					}
				});

			} else {
				$("#loadingview").hide();
				message("Error!", "Pilih No Mutasi Draft", "error");
			}

		});

	$("#btn_update_mutasi").click(
		function() {

			// global_id = $("#mutasi_draft_no").val();
			let idd = $("#mutasi_draft_no").val();

			if (idd != "") {
				$("#table_mutasi_pallet > tbody tr").each(function() {
					let is_valid = $(this).find("td:eq(3)").text().replace(/\s+/g, '');
					let rak = $(this).find("td:eq(4) input[type='text']").val();
					if (is_valid != "Valid") {
						message("Error!", "Pallet masih ada yang belum tervalidasi, silahkan cek terlebih dahulu!", "error");
						return false;
					} else if (rak == "") {
						message("Error!", "Lokasi Bin Tujuan tidak boleh kosong!", "error");
						return false;
					} else {

						messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Iya, Simpan', 'Tidak, Tutup').then((result) => {
							if (result.value == true) {
								requestAjax("<?= base_url('WMS/MutasiPallet/UpdateMutasiPallet'); ?>", {
									tr_mutasi_pallet_draft_id: $("#mutasi_draft_no").val(),
									tr_mutasi_pallet_id: $("#mutasi_no").val(),
									tipe_mutasi: $("#mutasi_tipe_transaksi").val(),
									mutasi_status: $("#mutasi_status").val(),
									mutasi_keterangan: $("#mutasi_keterangan").val(),
									lastUpdated: $("#lastUpdated").val()
								}, "POST", "JSON", function(response) {
									if (response == 1) {
										message_topright("success", "Data berhasil disimpan");
										setTimeout(() => {
											// location.reload();
											location.href = "<?= base_url() ?>WMS/MutasiPallet/MutasiPalletMenu";
										}, 1000);

									}
									if (response == 0) return message_topright("error", "Data gagal disimpan");
									if (response == 2) return messageNotSameLastUpdated()
								}, "#btn_update_mutasi")
							}
						});
						/*
						Swal.fire({
							title: "Apakah anda yakin?",
							text: "Pastikan data yang sudah anda input benar!",
							icon: "warning",
							showCancelButton: true,
							confirmButtonColor: "#3085d6",
							cancelButtonColor: "#d33",
							confirmButtonText: "Ya, Simpan",
							cancelButtonText: "Tidak, Tutup"
						}).then((result) => {
							if (result.value == true) {
								
								$("#loadingview").show();
								//ajax save data
								$.ajax({
									async: false,
									type: 'POST',
									url: "<?= base_url('WMS/MutasiPallet/UpdateMutasiPallet') ?>",
									data: {
										tr_mutasi_pallet_draft_id: $("#mutasi_draft_no").val(),
										tr_mutasi_pallet_id: $("#mutasi_no").val(),
										tipe_mutasi: $("#mutasi_tipe_transaksi").val(),
										mutasi_status: $("#mutasi_status").val(),
										mutasi_keterangan: $("#mutasi_keterangan").val()
									},
									success: function(response) {
										if (response == 1) {
											$("#loadingview").hide();

											Swal.fire({
												icon: 'success',
												title: 'Success',
												text: 'Mutasi Pallet Berhasil Dibuat',
												showConfirmButton: false,
												timer: 1000
											});

											location.href = "<?= base_url() ?>WMS/MutasiPallet/MutasiPalletMenu";

										} else {
											$("#loadingview").hide();

											var msg = response;
											var msgtype = 'error';

											//if (!window.__cfRLUnblockHandlers) return false;
											new PNotify
												({
													title: 'Error',
													text: msg,
													type: msgtype,
													styling: 'bootstrap3',
													delay: 3000,
													stack: stack_center
												});
										}
									}

								});
							}
						});
						*/

					}
				});

			} else {
				$("#loadingview").hide();
				message("Error!", "Pilih No Mutasi Draft", "error");
			}

		});

	$("#btn_konfirmasi_mutasi").click(
		function() {

			// $("#loadingview").show();

			// global_id = $("#mutasi_draft_no").val();
			let idd = $("#mutasi_draft_no").val();
			var mutasi_no = $("#mutasi_no").val();
			mutasi_tipe_transaksi = $("#mutasi_tipe_transaksi").val();

			if (idd != "") {

				if (mutasi_no != "") {
					messageBoxBeforeRequest('Data yang sudah dikonfirmasi tidak dapat diganti!!', 'Iya, Simpan', 'Tidak, Tutup').then((result) => {
						if (result.value == true) {
							requestAjax("<?= base_url('WMS/MutasiPallet/KonfirmasiMutasiPalet'); ?>", {
								tr_mutasi_pallet_draft_id: idd,
								tr_mutasi_pallet_id: mutasi_no,
								tipe_mutasi: mutasi_tipe_transaksi,
								lastUpdated: $("#lastUpdated").val()
							}, "POST", "JSON", function(data) {
								if (data.type == 200) {
									Swal.fire("Success!", data.message, "success").then(function(result) {
										$("#loadingview").hide();
										setTimeout(() => {
											// location.reload();
											location.href = "<?= base_url() ?>WMS/MutasiPallet/MutasiPalletMenu";
										}, 1000);
									});
								}
								if (data.type == 201) {
									Swal.fire("Error!", data.message, "error").then(function(result) {
										$("#loadingview").hide();
									});
									// message("Error!", data.message, "error");
								}
								// if (response == 0) return message_topright("error", "Data gagal disimpan");
								if (data.type == 2) return messageNotSameLastUpdated()
							}, "#btn_konfirmasi_mutasi")
						}
					});
					/*
										Swal.fire({
											title: "Apakah anda yakin?",
											text: "Data yang sudah dikonfirmasi tidak dapat diganti!",
											icon: "warning",
											showCancelButton: true,
											confirmButtonColor: "#3085d6",
											cancelButtonColor: "#d33",
											confirmButtonText: "Ya, Simpan",
											cancelButtonText: "Tidak, Tutup"
										}).then((result) => {
											if (result.value == true) {
												$("#loadingview").show();
												//ajax save data
												$.ajax({
													async: false,
													type: 'POST',
													url: "<?= base_url('WMS/MutasiPallet/KonfirmasiMutasiPalet') ?>",
													data: {
														tr_mutasi_pallet_draft_id: idd,
														tr_mutasi_pallet_id: mutasi_no,
														tipe_mutasi: mutasi_tipe_transaksi
													},
													dataType: "JSON",
													success: function(data) {
														if (data.type == 200) {
															Swal.fire("Success!", data.message, "success").then(function(result) {
																$("#loadingview").hide();
																location.href = "<?= base_url() ?>WMS/MutasiPallet/MutasiPalletMenu";
															});
														} else if (data.type == 201) {
															Swal.fire("Error!", data.message, "error").then(function(result) {
																$("#loadingview").hide();
															});
															// message("Error!", data.message, "error");
														} else {
															Swal.fire("Info!", data.message, "err").then(function(result) {
																$("#loadingview").hide();
															});
														}
													}
												});
											}
										});
					*/
				} else {
					// $("#loadingview").hide();
					message("Error!", "Kode Mutasi Ini Belum Disimpan!", "error");
					$("#loadingview").hide();
				}

			} else {
				$("#loadingview").hide();
				message("Error!", "Pilih No Mutasi Draft", "error");
			}

		});

	function ViewDetailPallet(pallet_id) {
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/MutasiPallet/GetPalletDetail') ?>",
			data: {
				pallet_id: pallet_id
			},
			success: function(response) {
				if (response) {
					$('#modal-pallet-detail').modal('show');
					ChPalletDetail(response);
				}
			}
		});

	}

	function ChPalletDetail(JSONMutasiPallet) {
		$("#data-table-pallet-detail > tbody").html('');

		var MutasiPallet = JSON.parse(JSONMutasiPallet);
		var no = 1;

		var pallet_id = MutasiPallet.PalletDetail[0].pallet_id;
		var pallet_kode = MutasiPallet.PalletDetail[0].pallet_kode;
		var pallet_jenis_id = MutasiPallet.PalletDetail[0].pallet_jenis_id;
		var pallet_jenis_nama = MutasiPallet.PalletDetail[0].pallet_jenis_nama;

		$("#pallet_detail_pallet_kode").val(pallet_kode);
		$("#pallet_detail_jenis_pallet").val(pallet_jenis_nama);

		if (MutasiPallet.Pallet != 0) {
			if ($.fn.DataTable.isDataTable('#data-table-pallet-detail')) {
				$('#data-table-pallet-detail').DataTable().destroy();
			}

			$('#data-table-pallet-detail tbody').empty();

			for (i = 0; i < MutasiPallet.PalletDetail.length; i++) {
				var pallet_id = MutasiPallet.PalletDetail[i].pallet_id;
				var pallet_kode = MutasiPallet.PalletDetail[i].pallet_kode;
				var pallet_jenis_id = MutasiPallet.PalletDetail[i].pallet_jenis_id;
				var pallet_jenis_nama = MutasiPallet.PalletDetail[i].pallet_jenis_nama;
				var sku_id = MutasiPallet.PalletDetail[i].sku_id;
				var sku_kode = MutasiPallet.PalletDetail[i].sku_kode;
				var principle_kode = MutasiPallet.PalletDetail[i].principle_kode;
				var sku_nama_produk = MutasiPallet.PalletDetail[i].sku_nama_produk;
				var sku_kemasan = MutasiPallet.PalletDetail[i].sku_kemasan;
				var sku_satuan = MutasiPallet.PalletDetail[i].sku_satuan;
				var sku_stock_expired_date = MutasiPallet.PalletDetail[i].sku_stock_expired_date;
				var penerimaan_tipe_nama = MutasiPallet.PalletDetail[i].penerimaan_tipe_nama;
				var sku_stock_qty = MutasiPallet.PalletDetail[i].sku_stock_qty;

				var strmenu = '';

				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td class="text-center">' + no + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + principle_kode + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + sku_kode + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + sku_nama_produk + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + sku_kemasan + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + sku_satuan + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + sku_stock_expired_date + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + penerimaan_tipe_nama + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + sku_stock_qty + '</td>';
				strmenu = strmenu + '</tr>';
				no++;

				$("#data-table-pallet-detail > tbody").append(strmenu);
			}

			$("#loadingview").hide();

			$('#data-table-pallet-detail').DataTable({
				"lengthMenu": [
					[-1],
					["All"]
				],
				"ordering": false,
				"bInfo": false,
				"paging": false
			});
		}
	}

	$(document).on("click", "#input_manual", function() {
		$("#input_manual").hide();
		$("#close_input").show();
		$("#preview_input_manual").show();
		$("#start_scan").attr("disabled", true);
	});

	function previewFile() {
		const file = document.querySelector('input[id=myFileInput]').files[0];
		const reader = new FileReader();

		$('#show-file').empty();
		reader.addEventListener("load", function() {
			$('#show-file').append(`
                <a href="${reader.result}" data-lightbox="image-1">
                <img src="${reader.result}" style="cursor: pointer" class="img-fluid" width="300" height="350" />
                </a>
            `);
			// preview.src = reader.result;
		}, false);

		if (file) {
			reader.readAsDataURL(file);
		}
	}

	$(document).on("click", "#check_kode", () => {
		let idd = $("#global_id").val()
		let barcode = $("#kode_barcode_notone").val();
		let attachment = $("#myFileInput")

		if (barcode == "") {
			message("Error!", "Kode Pallet tidak boleh kosong", "error");
			return false;
		} else if (attachment.val() == "") {
			message("Error!", "Bukti cek fisik tidak boleh kosong", "error");
			return false;
		} else {
			let new_form = new FormData();
			let files = attachment[0].files[0];
			new_form.append('id', idd);
			new_form.append('kode_pallet', barcode);
			new_form.append('file', files);

			$.ajax({
				url: "<?= base_url('WMS/MutasiPallet/check_kode_pallet_by_no_mutasi'); ?>",
				type: "POST",
				data: new_form,
				contentType: false,
				processData: false,
				dataType: "JSON",
				beforeSend: () => {
					$("#loading_cek_manual").show();
				},
				success: function(data) {
					$("#txtpreviewscan").val(data.kode);
					if (data.type == 200) {
						message("Success!", data.message, "success");
						$("#kode_barcode_notone").val("");
						$('#myFileInput').val("");
						$('#show-file').empty();
						$('#table_mutasi_pallet').fadeOut("slow", function() {
							$(this).hide();

						}).fadeIn("slow", function() {
							$(this).show();
							reload_pallet();
						});


					} else if (data.type == 201) {
						message("Error!", data.message, "error");

					} else {
						message("Info!", data.message, "info");

					}
				},
				complete: () => {
					$("#loading_cek_manual").hide();
				},
			});
		}
	})

	$(document).on("click", "#close_input", function() {
		remove_close_input();
	});

	function remove_close_input() {
		$("#input_manual").show();
		$("#close_input").hide();
		$("#preview_input_manual").hide();
		$("#kode_barcode_notone").val("");
		$("#txtpreviewscan").val("");
		$('#myFileInput').val("");
		$('#show-file').empty();
		$("#start_scan").attr("disabled", false);
	}

	$(document).on("click", "#start_scan", function() {
		let idd = $("#global_id").val()
		Swal.fire({
			title: 'Memuat Kamera',
			html: '<span><i class="fa fa-spinner fa-spin"></i></span>',
			timer: 2000,
			timerProgressBar: true,
			showConfirmButton: false,
			allowOutsideClick: false,
			didOpen: () => {
				Swal.showLoading()
				const b = Swal.getHtmlContainer().querySelector('b')
				timerInterval = setInterval(() => {
					b.textContent = Swal.getTimerLeft()
				}, 100)
			},
			willClose: () => {
				clearInterval(timerInterval)
			}
		}).then((result) => {
			/* Read more about handling dismissals below */
			if (result.dismiss === Swal.DismissReason.timer) {
				console.log('I was closed by the timer')
			}
		})

		if (idd != "") {
			$("#start_scan").hide();
			$("#stop_scan").show();
			$("#preview").show();
			$("#input_manual").attr("disabled", true);

			//handle succes scan
			const qrCodeSuccessCallback = (decodedText, decodedResult) => {
				let temp = decodedText;
				if (temp != "") {
					html5QrCode.pause();
					scan(decodedText);
				}

				// console.log(decodedText)
			};

			const scan = (decodedText) => {

				//check ada apa kosong di tabel distribusi_penerimaan_detail_temp
				//jika ada update statusnya jadi 1

				$.ajax({
					url: "<?= base_url('WMS/MutasiPallet/check_kode_pallet_by_no_mutasi'); ?>",
					type: "POST",
					data: {
						id: idd,
						kode_pallet: decodedText
					},
					dataType: "JSON",
					success: function(data) {
						$("#txtpreviewscan").val(data.kode);
						if (data.type == 200) {
							Swal.fire("Success!", data.message, "success").then(function(result) {
								if (result.value == true) {
									html5QrCode.resume();

								}
							});
							$('#table_mutasi_pallet').fadeOut("slow", function() {
								$(this).hide();

							}).fadeIn("slow", function() {
								$(this).show();
								reload_pallet();
							});
						} else if (data.type == 201) {
							Swal.fire("Error!", data.message, "error").then(function(result) {
								if (result.value == true) {
									html5QrCode.resume();

								}
							});
							// message("Error!", data.message, "error");
						} else {
							Swal.fire("Info!", data.message, "info").then(function(result) {
								if (result.value == true) {
									html5QrCode.resume();

								}
							});
						}
					},
				});
			}

			// atur kotak nng kini, set 0.sekian pngin jlok brpa persen
			const qrboxFunction = function(viewfinderWidth, viewfinderHeight) {
				let minEdgePercentage = 0.9; // 90%
				let minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
				let qrboxSize = Math.floor(minEdgeSize * minEdgePercentage);
				return {
					width: qrboxSize,
					height: qrboxSize
				};
			}

			const config = {
				fps: 10,
				qrbox: qrboxFunction,
				// rememberLastUsedCamera: true,
				// Only support camera scan type.
				supportedScanTypes: [Html5Qrcode.SCAN_TYPE_CAMERA]
			};
			Html5Qrcode.getCameras().then(devices => {
				if (devices && devices.length) {
					$.each(devices, function(i, v) {
						$("#select_kamera").append(`
                        <input class="checkbox-tools" type="radio" name="tools" value="${v.id}" id="tool-${i}">
                        <label class="for-checkbox-tools" for="tool-${i}">
                            ${v.label}
                        </label>
                    `)
					});

					$("#select_kamera :input[name='tools']").each(function(i, v) {
						if (i == 0) {
							$(this).attr('checked', true);
						}
					});

					let cameraId = devices[0].id;
					// html5QrCode.start(devices[0]);
					$('input[name="tools"]').on('click', function() {
						// alert($(this).val());
						html5QrCode.stop();
						html5QrCode.start({
							deviceId: {
								exact: $(this).val()
							}
						}, config, qrCodeSuccessCallback);
					});
					//start scan
					html5QrCode.start({
						deviceId: {
							exact: cameraId
						}
					}, config, qrCodeSuccessCallback);

				}
			}).catch(err => {
				message("Error!", "Kamera tidak ditemukan", "error");
				$("#start_scan").show();
				$("#stop_scan").hide();
				$("#input_manual").attr("disabled", false);
			});


		} else {
			message("Error!", "Pilih No Mutasi Draft", "error");
		}
	});

	$(document).on("click", "#stop_scan", function() {
		remove_stop_scan();
	});

	$(document).on("click", ".start_scan_by_one", function() {
		let idd = $("#global_id").val()
		if (idd != "") {
			const index = $(this).attr('data-idx');
			const pallet_id = $(this).attr('data-id');
			const tr_detail_id = $(this).attr('data-detail-id');
			// const gudang_tujuan = $("#gudang_tujuan_mutasi").val();
			const gudang_tujuan = $("#gudang_tujuan_mutasi option:selected").val();
			$("#modal_scan").modal('show');

			$("#rak_tujuan_" + index).html('');

			Swal.fire({
				title: 'Memuat Kamera',
				html: '<span ><i class="fa fa-spinner fa-spin"></i></span>',
				timer: 2000,
				timerProgressBar: true,
				showConfirmButton: false,
				allowOutsideClick: false,
				didOpen: () => {
					Swal.showLoading()
					const b = Swal.getHtmlContainer().querySelector('b')
					timerInterval = setInterval(() => {
						b.textContent = Swal.getTimerLeft()
					}, 100)
				},
				willClose: () => {
					clearInterval(timerInterval)
				}
			}).then((result) => {
				/* Read more about handling dismissals below */
				if (result.dismiss === Swal.DismissReason.timer) {
					console.log('I was closed by the timer')
				}
			})

			//handle succes scan
			const qrCodeSuccessCallback2 = (decodedText, decodedResult) => {
				let temp = decodedText;
				if (temp != "") {
					html5QrCode2.pause();
					scan2(decodedText);
				}
			};

			const scan2 = (decodedText) => {
				// alert(pallet_id + " - " + gudang_tujuan)
				<?php if ($act == "MutasiPalletDetail") { ?>
					$.ajax({
						url: "<?= base_url('WMS/MutasiPallet/check_rak_lajur_detail'); ?>",
						type: "POST",
						data: {
							tr_mutasi_pallet_draft_id: $("#mutasi_draft_no").val(),
							pallet_id: pallet_id,
							gudang_tujuan: gudang_tujuan,
							kode: decodedText,
							tr_mutasi_pallet_detail_draft_id: tr_detail_id,
							act: "update"
						},
						dataType: "JSON",
						success: function(data) {
							$("#txtpreviewscan2").val(data.kode);
							$("#rak_tujuan_" + index).append(data.kode);

							if (data.type == 200) {
								Swal.fire("Success!", data.message, "success").then(function(result) {
									if (result.value == true) {
										html5QrCode2.stop();
										$("#modal_scan").modal('hide');

									}
								});
								$('#table_mutasi_pallet').fadeOut("slow", function() {
									$(this).hide();

								}).fadeIn("slow", function() {
									$(this).show();
									reload_pallet();
								});
							} else if (data.type == 201) {
								Swal.fire("Error!", data.message, "error").then(function(result) {
									if (result.value == true) {
										html5QrCode2.resume();

									}
								});
							} else {
								Swal.fire("Info!", data.message, "info").then(function(result) {
									if (result.value == true) {
										html5QrCode2.resume();

									}
								});
								$('#table_mutasi_pallet').fadeOut("slow", function() {
									$(this).hide();

								}).fadeIn("slow", function() {
									$(this).show();

								});
							}
						},
					});
				<?php } else { ?>
					$.ajax({
						url: "<?= base_url('WMS/MutasiPallet/check_rak_lajur_detail'); ?>",
						type: "POST",
						data: {
							tr_mutasi_pallet_draft_id: $("#mutasi_draft_no").val(),
							pallet_id: pallet_id,
							gudang_tujuan: gudang_tujuan,
							kode: decodedText,
							tr_mutasi_pallet_detail_draft_id: tr_detail_id,
							act: "add"
						},
						dataType: "JSON",
						success: function(data) {
							$("#txtpreviewscan2").val(data.kode);
							$("#rak_tujuan_" + index).append(data.kode);

							if (data.type == 200) {
								Swal.fire("Success!", data.message, "success").then(function(result) {
									if (result.value == true) {
										html5QrCode2.stop();
										$("#modal_scan").modal('hide');

									}
								});
								$('#table_mutasi_pallet').fadeOut("slow", function() {
									$(this).hide();

								}).fadeIn("slow", function() {
									$(this).show();
									reload_pallet();
								});
							} else if (data.type == 201) {
								Swal.fire("Error!", data.message, "error").then(function(result) {
									if (result.value == true) {
										html5QrCode2.resume();

									}
								});
							} else {
								Swal.fire("Info!", data.message, "info").then(function(result) {
									if (result.value == true) {
										html5QrCode2.resume();

									}
								});
								$('#table_mutasi_pallet').fadeOut("slow", function() {
									$(this).hide();

								}).fadeIn("slow", function() {
									$(this).show();

								});
							}
						},
					});
				<?php } ?>
			}

			// atur kotak nng kini, set 0.sekian pngin jlok brpa persen
			const qrboxFunction2 = function(viewfinderWidth, viewfinderHeight) {
				let minEdgePercentage = 0.9; // 90%
				let minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
				let qrboxSize = Math.floor(minEdgeSize * minEdgePercentage);
				return {
					width: qrboxSize,
					height: qrboxSize
				};
			}

			const config2 = {
				fps: 10,
				qrbox: qrboxFunction2,
			};
			Html5Qrcode.getCameras().then(devices => {
				if (devices && devices.length) {
					$("#select_kamera_by_one").empty();
					$.each(devices, function(i, v) {
						$("#select_kamera_by_one").append(`
                        <input class="checkbox-tools" type="radio" name="tools2" value="${v.id}" id="tool-${i}">
                        <label class="for-checkbox-tools" for="tool-${i}">
                            ${v.label}
                        </label>
                    `)
					});

					$("#select_kamera_by_one :input[name='tools2']").each(function(i, v) {
						if (i == 0) {
							$(this).attr('checked', true);
						}
					});

					let cameraId2 = devices[0].id;
					// html5QrCode.start(devices[0]);
					$('input[name="tools2"]').on('click', function() {
						// alert($(this).val());
						html5QrCode2.stop();
						html5QrCode2.start({
							deviceId: {
								exact: $(this).val()
							}
						}, config2, qrCodeSuccessCallback2);
					});
					//start scan
					html5QrCode2.start({
						deviceId: {
							exact: cameraId2
						}
					}, config2, qrCodeSuccessCallback2);

				}
			}).catch(err => {
				message("Error!", "Kamera tidak ditemukan", "error");
				$("#modal_scan").modal('hide');
			});

		} else {
			message("Error!", "Pilih No Mutasi Draft", "error");
		}

	});

	$(document).on("click", ".stop_scan_by_one", function() {
		html5QrCode2.stop();
		$("#modal_scan").modal('hide');
	});

	$(document).on("click", "#close_input", function() {
		remove_close_input();
	});

	function remove_stop_scan() {
		$("#start_scan").show();
		$("#stop_scan").hide();
		$("#preview").hide();
		$("#txtpreviewscan").val("");
		$("#select_kamera").empty();
		$("#input_manual").attr("disabled", false);
		html5QrCode.stop();
	}

	function remove_close_input() {
		$("#input_manual").show();
		$("#close_input").hide();
		$("#preview_input_manual").hide();
		$("#kode_barcode_notone").val("");
		$("#txtpreviewscan").val("");
		$('#myFileInput').val("");
		$('#show-file').empty();
		$("#start_scan").attr("disabled", false);
	}

	$(document).on("click", ".input_rak", function() {
		// $("#nama_rak").attr("data-id", $(this).attr('data-id'));
		// $("#nama_rak").attr("data-idx", $(this).attr('data-idx'));
		// $("#nama_rak").attr("data-detail-id", $(this).attr('data-detail-id'));
		$(".nama_rak").attr("data-id", $(this).attr('data-id'));
		$(".nama_rak").attr("data-idx", $(this).attr('data-idx'));
		$(".nama_rak").attr("data-detail-id", $(this).attr('data-detail-id'));
		$("#manual_input_rak").modal('show');
	});

	// $(document).on("change", "#nama_rak", function() {
	// 	let rak_val = $(this);
	// 	const idx = $(this).attr('data-idx');
	// 	const pallet_id = $(this).attr('data-id');
	// 	const tr_detail_id = $(this).attr('data-detail-id');
	// 	const gudang_tujuan = $("#gudang_tujuan_mutasi").val();
	// 	// const gudang_asal = $("#gudang_asal_mutasi").val();

	// 	// console.log(gudang_asal);

	// 	$("#rak_tujuan_" + idx).html('');

	// 	<?php if ($act == "MutasiPalletDetail") { ?>
	// 		$.ajax({
	// 			async: false,
	// 			url: "<?= base_url('WMS/MutasiPallet/check_rak_lajur_detail'); ?>",
	// 			type: "POST",
	// 			data: {
	// 				tr_mutasi_pallet_draft_id: $("#mutasi_draft_no").val(),
	// 				pallet_id: pallet_id,
	// 				gudang_tujuan: gudang_tujuan,
	// 				tr_mutasi_pallet_detail_draft_id: tr_detail_id,
	// 				kode: rak_val.val(),
	// 				act: "update"
	// 			},
	// 			dataType: "JSON",
	// 			success: function(data) {
	// 				$("#txtpreviewscan2").val(data.kode);
	// 				$("#rak_tujuan_" + idx).append(data.kode);

	// 				if (data.type == 200) {
	// 					Swal.fire("Success!", data.message, "success").then(function(result) {
	// 						if (result.value == true) {
	// 							$("#manual_input_rak").modal('hide');
	// 							rak_val.val("");
	// 						}
	// 					});
	// 					$('#table_mutasi_pallet').fadeOut("slow", function() {
	// 						$(this).hide();
	// 					}).fadeIn("slow", function() {
	// 						$(this).show();
	// 						reload_pallet();
	// 					});
	// 				} else if (data.type == 201) {
	// 					message("Error!", data.message, "error");
	// 				} else {
	// 					message("Info!", data.message, "info");
	// 				}
	// 			},
	// 		});
	// 	<?php } else { ?>
	// 		$.ajax({
	// 			async: false,
	// 			url: "<?= base_url('WMS/MutasiPallet/check_rak_lajur_detail'); ?>",
	// 			type: "POST",
	// 			data: {
	// 				tr_mutasi_pallet_draft_id: $("#mutasi_draft_no").val(),
	// 				pallet_id: pallet_id,
	// 				gudang_tujuan: gudang_tujuan,
	// 				tr_mutasi_pallet_detail_draft_id: tr_detail_id,
	// 				kode: rak_val.val(),
	// 				act: "add"
	// 			},
	// 			dataType: "JSON",
	// 			success: function(data) {
	// 				$("#txtpreviewscan2").val(data.kode);
	// 				$("#rak_tujuan_" + idx).append(data.kode);

	// 				if (data.type == 200) {
	// 					Swal.fire("Success!", data.message, "success").then(function(result) {
	// 						if (result.value == true) {
	// 							$("#manual_input_rak").modal('hide');
	// 							rak_val.val("");
	// 						}
	// 					});
	// 					$('#table_mutasi_pallet').fadeOut("slow", function() {
	// 						$(this).hide();
	// 					}).fadeIn("slow", function() {
	// 						$(this).show();
	// 						reload_pallet();
	// 					});
	// 				} else if (data.type == 201) {
	// 					message("Error!", data.message, "error");
	// 				} else {
	// 					message("Info!", data.message, "info");
	// 				}
	// 			},
	// 		});
	// 	<?php } ?>
	// });

	$(document).on("click", ".tutup_input_manual", function() {
		$("#manual_input_rak").modal('hide');
		// $("#nama_rak").val("");
		$(".nama_rak").val("");
	});

	$(document).on("change", "#check_scan", function(e) {
		if (e.target.checked) {
			$("#start_scan").hide();
			$("#input_manual").show();
			$("#stop_scan").hide();
			$("#preview").hide();
			$("#txtpreviewscan").val("");
			$("#select_kamera").empty();
		} else {
			$("#start_scan").show();
			$("#input_manual").hide();
			$("#close_input").hide();
			$("#preview_input_manual").hide();
			$("#kode_barcode_notone").val("");
			$("#txtpreviewscan").val("");
			$('#myFileInput').val("");
			$('#show-file').empty();
		}
	});

	const handlerScanInputManual = (event, value, type) => {
		const pallet_id = type == 'notone' ? null : event.currentTarget.getAttribute('data-id');
		const idx = type == 'notone' ? null : event.currentTarget.getAttribute('data-idx')
		const tr_detail_id = type == 'notone' ? null : event.currentTarget.getAttribute('data-detail-id')
		if (value != "") {
			fetch('<?= base_url('WMS/MutasiPallet/getKodeAutoComplete?params='); ?>' + value + `&type=${type}`)
				.then(response => response.json())
				.then((results) => {
					if (!results[0]) {
						$(`#table-fixed-${type}`).css('display', 'none')
					} else {
						let data = "";
						// console.log(results);
						results.forEach(function(e, i) {
							data += `
									<tr onclick="getNoSuratJalanEks('${e.kode}', '${type}', '${pallet_id}', '${idx}', '${tr_detail_id}')" style="cursor:pointer">
											<td class="col-xs-1">${i + 1}.</td>
											<td class="col-xs-11">${e.kode}</td>
									</tr>
									`;
						})

						$(`#konten-table-${type}`).html(data)
						// console.log(data);
						$(`#table-fixed-${type}`).css('display', 'block')
					}
				});
		} else {
			$(`#table-fixed-${type}`).css('display', 'none');
		}
	}


	function getNoSuratJalanEks(data, type, pallet_id, idx, tr_detail_id) {
		$(`#kode_barcode_${type}`).val(data);
		$(`#table-fixed-${type}`).css('display', 'none')
		if (type == 'notone') {
			$("#check_kode").click()
		}
		if (type == 'one') {

			$("#rak_tujuan_" + idx).html('');

			// const gudang_tujuan = $("#gudang_tujuan").val();
			const gudang_tujuan = $("#gudang_tujuan_mutasi option:selected").val();
			const pageUrl = "<?= $act; ?>"
			$.ajax({
				async: false,
				url: "<?= base_url('WMS/MutasiPallet/check_rak_lajur_detail'); ?>",
				type: "POST",
				data: {
					tr_mutasi_pallet_draft_id: $("#mutasi_draft_no").val(),
					pallet_id: pallet_id,
					gudang_tujuan: gudang_tujuan,
					tr_mutasi_pallet_detail_draft_id: tr_detail_id,
					kode: data,
					act: pageUrl == "MutasiPalletDetail" ? 'update' : 'add'
				},
				dataType: "JSON",
				success: function(data) {

					if (data.type == 200) {
						$("#txtpreviewscan2").val(data.kode);
						$("#rak_tujuan_" + idx).append(data.kode);
						Swal.fire("Success!", data.message, "success").then(function(result) {
							if (result.value == true) {
								$("#manual_input_rak").modal('hide');
								rak_val.val("");
							}
						});
						$('#table_mutasi_pallet').fadeOut("slow", function() {
							$(this).hide();
						}).fadeIn("slow", function() {
							$(this).show();
							reload_pallet();
						});
					} else if (data.type == 201) {
						message("Error!", data.message, "error");
					} else {
						message("Info!", data.message, "info");
					}
				},
			});
		}
	}
</script>