<script type="text/javascript">
	var index_pallet = 0;
	loadingBeforeReadyPage()
	$(document).ready(
		function() {
			$(".select2").select2();
			$('#filter_mutasi_draft_tgl_draft').daterangepicker({
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

			$("#mutasi_draft_principle").append('<option value="">** <label name="CAPTION-PILIHPRINCIPLE">Pilih Principle</label> **</option>');
			$("#filter_mutasi_draft_principle").append('<option value=""><label name="CAPTION-ALL">All</label></option>');
		}
	);

	function GetPrincipleHome(client_wms_id) {
		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/MutasiPalletDraft/getDataPrincipleByClientWmsId') ?>",
			data: {
				id: client_wms_id
			},
			dataType: "JSON",
			success: function(response) {
				$("#filter_mutasi_draft_principle").empty();
				let html = "";
				html += '<option value=""><label name="CAPTION-ALL">All</label></option>';
				$.each(response, function(i, v) {
					html += "<option value=" + v.id + ">" + v.nama + "</option>";
				});
				$("#filter_mutasi_draft_principle").append(html);

			}
		});
	}

	function GetPrinciple(client_wms_id) {
		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/MutasiPalletDraft/getDataPrincipleByClientWmsId') ?>",
			data: {
				id: client_wms_id
			},
			dataType: "JSON",
			success: function(response) {
				$("#mutasi_draft_principle").empty();
				let html = "";
				html += '<option value="">** <label name="CAPTION-PILIHPRINCIPLE">Pilih Principle</label> **</option>';
				$.each(response, function(i, v) {
					html += "<option value=" + v.id + ">" + v.nama + "</option>";
				});
				$("#mutasi_draft_principle").append(html);

			}
		});
	}

	$('#select-pallet').click(function(event) {
		if (this.checked) {
			// Iterate each checkbox
			$('[name="CheckboxPallet"]:checkbox').each(function() {
				this.checked = true;
			});
		} else {
			$('[name="CheckboxPallet"]:checkbox').each(function() {
				this.checked = false;
			});
		}
	});

	$('#mutasi_draft_approval').click(function(event) {
		if (this.checked) {
			$("#mutasi_draft_status").val("In Progress Approval");
		} else {
			$("#mutasi_draft_status").val("Draft");
		}
	});

	function GetPencarianMutasiPalletDraftTable() {
		$("#loadingview").show();
		let client_wms = $("#filter_mutasi_draft_perusahaan").val();
		let principle = $("#filter_mutasi_draft_principle").val();
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/MutasiPalletDraft/GetPencarianMutasiPalletDraftTable') ?>",
			data: {
				tanggal: $("#filter_mutasi_draft_tgl_draft").val(),
				id: $("#filter_no_mutasi_draft").val(),
				gudang_asal: $("#filter_gudang_asal_mutasi_draft").val(),
				gudang_tujuan: $("#filter_gudang_tujuan_mutasi_draft").val(),
				tipe: $("#filter_mutasi_draft_tipe_transaksi").val(),
				client_wms: client_wms,
				principle: principle,
				checker: $("#filter_mutasi_draft_checker").val(),
				status: $("#filter_mutasi_draft_status").val()
			},
			success: function(response) {
				if (response) {
					ChPencarianMutasiPalletDraftTable(response);
				}
			}
		});
	}

	function ChPencarianMutasiPalletDraftTable(JSONMutasiPallet) {
		$("#table_pencarian_mutasi_draft > tbody").html('');
		$("#loadingview").hide();

		var MutasiPallet = JSON.parse(JSONMutasiPallet);
		var no = 1;

		if (MutasiPallet.MutasiPalletDraft != 0) {
			if ($.fn.DataTable.isDataTable('#table_pencarian_mutasi_draft')) {
				$('#table_pencarian_mutasi_draft').DataTable().destroy();
			}

			$('#table_pencarian_mutasi_draft tbody').empty();

			for (i = 0; i < MutasiPallet.MutasiPalletDraft.length; i++) {
				var tr_mutasi_pallet_draft_id = MutasiPallet.MutasiPalletDraft[i].tr_mutasi_pallet_draft_id;
				var tr_mutasi_pallet_draft_kode = MutasiPallet.MutasiPalletDraft[i].tr_mutasi_pallet_draft_kode;
				var tr_mutasi_pallet_draft_tanggal = MutasiPallet.MutasiPalletDraft[i].tr_mutasi_pallet_draft_tanggal;
				var tr_mutasi_pallet_draft_tipe = MutasiPallet.MutasiPalletDraft[i].tr_mutasi_pallet_draft_tipe;
				var principle_id = MutasiPallet.MutasiPalletDraft[i].principle_id;
				var principle_kode = MutasiPallet.MutasiPalletDraft[i].principle_kode;
				var tr_mutasi_pallet_draft_nama_checker = MutasiPallet.MutasiPalletDraft[i].tr_mutasi_pallet_draft_nama_checker;
				var depo_detail_id_asal = MutasiPallet.MutasiPalletDraft[i].depo_detail_id_asal;
				var gudang_asal = MutasiPallet.MutasiPalletDraft[i].gudang_asal;
				var depo_detail_id_tujuan = MutasiPallet.MutasiPalletDraft[i].depo_detail_id_tujuan;
				var gudang_tujuan = MutasiPallet.MutasiPalletDraft[i].gudang_tujuan;
				var tr_mutasi_pallet_draft_status = MutasiPallet.MutasiPalletDraft[i].tr_mutasi_pallet_draft_status;


				var strmenu = '';

				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td class="text-center">' + tr_mutasi_pallet_draft_kode + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + tr_mutasi_pallet_draft_tanggal + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + tr_mutasi_pallet_draft_tipe + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + principle_kode + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + tr_mutasi_pallet_draft_nama_checker + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + gudang_asal + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + gudang_tujuan + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + tr_mutasi_pallet_draft_status + '</td>';
				strmenu = strmenu + '	<td class="text-center"><a href="<?= base_url(); ?>WMS/MutasiPalletDraft/MutasiPalletDraftEdit/?tr_mutasi_pallet_draft_id=' + tr_mutasi_pallet_draft_id + '" class="btn btn-primary" target="_blank"><i class="fa fa-pencil"></i></a></td>';
				strmenu = strmenu + '</tr>';
				no++;

				$("#table_pencarian_mutasi_draft > tbody").append(strmenu);
			}

			$("#loadingview").hide();

			$('#table_pencarian_mutasi_draft').DataTable({
				"lengthMenu": [
					[10],
					[10]
				]
			});
		} else {
			ResetForm();
		}
	}

	function GetPallet() {
		var gudang_asal = $("#gudang_asal_mutasi_draft").val();
		var perusahaan = $("#mutasi_draft_perusahaan").val();
		var principle = $("#mutasi_draft_principle").val();

		if (gudang_asal == "") {
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: 'Pilih Gudang Asal!'
			});
		} else if (perusahaan == "") {
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: 'Pilih Perusahaan!'
			});
		} else if (principle == "") {
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: 'Pilih Principle!'
			});
		} else {
			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/MutasiPalletDraft/GetPallet') ?>",
				data: {
					gudang_asal: $("#gudang_asal_mutasi_draft").val(),
					principle: $("#mutasi_draft_principle").val(),
					perusahaan: perusahaan
				},
				success: function(response) {
					console.log(JSON.parse(response));
					if (response) {
						$('#modal-pallet').modal('show');
						ChPallet(response);
					}
				}
			});
		}
	}

	function ChPallet(JSONMutasiPallet) {
		$("#data-table-pallet > tbody").html('');

		var MutasiPallet = JSON.parse(JSONMutasiPallet);
		var no = 1;

		// var perusahaan = MutasiPallet.Perusahaan[0].client_wms_nama;
		// var principle = MutasiPallet.Principle[0].principle_kode;
		var gudang = MutasiPallet.Gudang[0].depo_detail_nama;

		// $("#perusahaan_pallet").val(perusahaan);
		// $("#principle_pallet").val(principle);
		$("#gudang_asal_pallet").val(gudang);

		if (MutasiPallet.Pallet != 0) {
			if ($.fn.DataTable.isDataTable('#data-table-pallet')) {
				$('#data-table-pallet').DataTable().destroy();
			}

			$('#data-table-pallet tbody').empty();

			for (i = 0; i < MutasiPallet.Pallet.length; i++) {
				var pallet_id = MutasiPallet.Pallet[i].pallet_id;
				var pallet_kode = MutasiPallet.Pallet[i].pallet_kode;
				var rak_id = MutasiPallet.Pallet[i].rak_id;
				var rak_nama = MutasiPallet.Pallet[i].rak_nama;
				var rak_lajur_detail_id = MutasiPallet.Pallet[i].rak_lajur_detail_id;
				var rak_lajur_detail_nama = MutasiPallet.Pallet[i].rak_lajur_detail_nama;
				var pallet_jenis_id = MutasiPallet.Pallet[i].pallet_jenis_id;
				var pallet_jenis_nama = MutasiPallet.Pallet[i].pallet_jenis_nama;

				var strmenu = '';

				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td class="text-center">';
				strmenu = strmenu + '	<input type="checkbox" name="CheckboxPallet" id="chk_pallet_' + i + '" value="' + pallet_id + '">';
				strmenu = strmenu + '	<input type="hidden" id="pallet_kode' + i + '" value="' + pallet_kode + '">';
				strmenu = strmenu + '	<input type="hidden" id="rak_id' + i + '" value="' + rak_id + '">';
				strmenu = strmenu + '	<input type="hidden" id="rak_nama' + i + '" value="' + rak_nama + '">';
				strmenu = strmenu + '	<input type="hidden" id="rak_lajur_detail_id' + i + '" value="' + rak_lajur_detail_id + '">';
				strmenu = strmenu + '	<input type="hidden" id="rak_lajur_detail_nama' + i + '" value="' + rak_lajur_detail_nama + '">';
				strmenu = strmenu + '	<input type="hidden" id="pallet_jenis_id' + i + '" value="' + pallet_jenis_id + '">';
				strmenu = strmenu + '	<input type="hidden" id="pallet_jenis_nama' + i + '" value="' + pallet_jenis_nama + '">';
				strmenu = strmenu + '	</td>';
				strmenu = strmenu + '	<td class="text-center">' + rak_nama + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + rak_lajur_detail_nama + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + pallet_kode + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + pallet_jenis_nama + '</td>';
				strmenu = strmenu + '	<td class="text-center"><button class="btn btn-success" onclick="ViewDetailPallet(\'' + pallet_id + '\')"><i class="fa fa-search"></i></button></td>';
				strmenu = strmenu + '</tr>';
				no++;

				$("#data-table-pallet > tbody").append(strmenu);
			}

			$("#loadingview").hide();

			$('#data-table-pallet').DataTable({
				"lengthMenu": [
					[-1],
					["All"]
				],
				"ordering": false,
				"bInfo": false,
				"paging": false
			});
		} else {
			Swal.fire({
				icon: 'info',
				title: 'Info',
				text: 'Data Pallet Kosong'
			});

			$('#modal-pallet').modal('hide');
		}
	}

	$("#btn_pencarian_mutasi_pallet_draft").click(
		function() {
			GetPencarianMutasiPalletDraftTable();
		}
	);

	$("#btn_pilih_pallet_mutasi_draft").click(
		function() {
			GetPallet();
		}
	);

	$("#btn_save_mutasi_draft").click(
		function() {
			var jml_pallet = $("#txt_jml_pallet").val();
			var karyawan = $("#mutasi_draft_checker").val();
			var arr_karyawan = karyawan.split(" || ");

			let tr_mutasi_pallet_draft_id = $("#mutasi_pallet_draft_id").val();

			if (jml_pallet > 0) {
				Swal.fire({
					title: '<b>Apa anda yakin menyimpan data ini ?</b>',
					text: "Data yang sudah di ajukan approval tidak bisa diupdate",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					cancelButtonText: 'Tidak',
					confirmButtonText: 'Ya'
				}).then((result) => {
					if (result.value == true) {
						$("#loadingview").show();
						$.ajax({
							async: false,
							type: 'POST',
							url: "<?= base_url('WMS/MutasiPalletDraft/InsertMutasiPalletDraft') ?>",
							data: {
								tr_mutasi_pallet_draft_id: tr_mutasi_pallet_draft_id,
								distribusi_penerimaan_id: null,
								principle_id: $("#mutasi_draft_principle").val(),
								client_wms_id: $("#mutasi_draft_perusahaan").val(),
								tr_mutasi_pallet_draft_kode: null,
								tr_mutasi_pallet_draft_tanggal: $("#mutasi_draft_tanggal").val(),
								tr_mutasi_pallet_draft_tipe: $("#mutasi_draft_tipe_transaksi").val(),
								tr_mutasi_pallet_draft_keterangan: $("#mutasi_draft_keterangan").val(),
								tr_mutasi_pallet_draft_status: $("#mutasi_draft_status").val(),
								depo_id_asal: "<?= $this->session->userdata('depo_id') ?>",
								depo_detail_id_asal: $("#gudang_asal_mutasi_draft").val(),
								depo_id_tujuan: "<?= $this->session->userdata('depo_id') ?>",
								depo_detail_id_tujuan: $("#gudang_tujuan_mutasi_draft").val(),
								tr_mutasi_pallet_draft_tgl_create: "<?= date('Y-m-d') ?>",
								tr_mutasi_pallet_draft_who_create: "<?= $this->session->userdata('pengguna_username') ?>",
								tr_mutasi_pallet_draft_nama_checker: arr_karyawan[1],
								karyawan_id: arr_karyawan[0]
							},
							beforeSend: function() {
								Swal.fire({
									title: 'Loading ...',
									html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
									timerProgressBar: false,
									showConfirmButton: false,
									allowOutsideClick: false,
								});

								$("#btn_save_mutasi_draft").prop("disabled", true);
							},
							error: function(xhr) {
								$("#btn_save_mutasi_draft").prop("enabled", false);
								Swal.fire({
									title: 'Loading ...',
									html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
									timerProgressBar: false,
									showConfirmButton: false,
									allowOutsideClick: false,
									timer: 10
								});
							},
							success: function(response) {
								$("#btn_save_mutasi_draft").prop("enabled", false);
								Swal.fire({
									title: 'Loading ...',
									html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
									timerProgressBar: false,
									showConfirmButton: false,
									allowOutsideClick: false,
									timer: 10,
								});
								if (response == 1) {
									for (var x = 0; x < index_pallet; x++) {
										$.ajax({
											async: false,
											type: 'POST',
											url: "<?= base_url('WMS/MutasiPalletDraft/InsertMutasiPalletDetailDraft') ?>",
											data: {
												tr_mutasi_pallet_draft_id: tr_mutasi_pallet_draft_id,
												pallet_id: $("#pallet_id" + x).val(),
												rak_lajur_detail_id_asal: $("#rak_lajur_detail_id" + x).val()

											},
											success: function(response) {
												console.log(response);
											}
										});
									}

									$("#loadingview").hide();

									Swal.fire({
										icon: 'success',
										title: 'Success',
										text: 'Draft Mutasi Pallet Berhasil Dibuat',
										showConfirmButton: false,
										timer: 1000
									});


									location.href = "<?= base_url() ?>WMS/MutasiPalletDraft/MutasiPalletDraftEdit/?tr_mutasi_pallet_draft_id=" + tr_mutasi_pallet_draft_id;

								} else if (response == "Approval setting tidak ada") {

									for (var x = 0; x < index_pallet; x++) {
										$.ajax({
											async: false,
											type: 'POST',
											url: "<?= base_url('WMS/MutasiPalletDraft/InsertMutasiPalletDetailDraft') ?>",
											data: {
												tr_mutasi_pallet_draft_id: tr_mutasi_pallet_draft_id,
												pallet_id: $("#pallet_id" + x).val(),
												rak_lajur_detail_id_asal: $("#rak_lajur_detail_id" + x).val()
											},
											success: function(response) {
												// console.log(x + " - " + index_pallet);
												console.log(response);
											}
										});
									}

									var msg = response;
									var msgtype = 'error';

									Swal.fire({
										icon: msgtype,
										title: 'Error',
										text: msg
									});

									$("#loadingview").hide();
								} else if (response == 500) {
									var msg = 'Tanggal dokumen tidak boleh kurang dari atau sama dengan tanggal tutup gudang';
									var msgtype = 'error';

									Swal.fire({
										icon: msgtype,
										title: 'Error',
										text: msg
									});

									$("#loadingview").hide();
								} else {
									$("#loadingview").hide();

									if (response == 2) {
										var msg = 'Draft Mutasi Pallet  sudah ada';
									} else {
										var msg = response;
									}
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

			} else {
				Swal.fire({
					icon: 'error',
					title: 'Error',
					text: 'Pilih Pallet!'
				});
			}

		}
	);

	$("#btn_update_mutasi_draft").click(
		function() {
			var tr_mutasi_pallet_draft_id = $("#mutasi_pallet_draft_id").val();
			var jml_pallet = $("#txt_jml_pallet").val();
			var karyawan = $("#mutasi_draft_checker").val();
			var arr_karyawan = karyawan.split(" || ");

			index_pallet = index_pallet + $("#txt_index_pallet").val();

			if (jml_pallet > 0) {
				Swal.fire({
					title: '<b>Apa anda yakin menyimpan data ini ?</b>',
					text: "Data yang sudah di ajukan approval tidak bisa diupdate",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					cancelButtonText: 'Tidak',
					confirmButtonText: 'Ya'
				}).then((result) => {
					if (result.value == true) {
						// $("#loadingview").show();
						// $.ajax({
						// 	async: false,
						// 	type: 'POST',
						// 	url: "<?= base_url('WMS/MutasiPalletDraft/DeleteMutasiPalletDetailDraft') ?>",
						// 	data: {
						// 		tr_mutasi_pallet_draft_id: $("#mutasi_pallet_draft_id").val()
						// 	},
						// 	success: function(response) {
						// 		console.log(response);
						// 	}
						// });

						$.ajax({
							// async: false,
							type: 'POST',
							url: "<?= base_url('WMS/MutasiPalletDraft/UpdateMutasiPalletDraft') ?>",
							data: {
								tr_mutasi_pallet_draft_id: $("#mutasi_pallet_draft_id").val(),
								distribusi_penerimaan_id: null,
								principle_id: $("#mutasi_draft_principle").val(),
								client_wms_id: $("#mutasi_draft_perusahaan").val(),
								tr_mutasi_pallet_draft_kode: $("#mutasi_draft_no_draft").val(),
								tr_mutasi_pallet_draft_tanggal: $("#mutasi_draft_tanggal").val(),
								tr_mutasi_pallet_draft_tipe: $("#mutasi_draft_tipe_transaksi").val(),
								tr_mutasi_pallet_draft_keterangan: $("#mutasi_draft_keterangan").val(),
								tr_mutasi_pallet_draft_status: $("#mutasi_draft_status").val(),
								depo_id_asal: "<?= $this->session->userdata('depo_id') ?>",
								depo_detail_id_asal: $("#gudang_asal_mutasi_draft").val(),
								depo_id_tujuan: "<?= $this->session->userdata('depo_id') ?>",
								depo_detail_id_tujuan: $("#gudang_tujuan_mutasi_draft").val(),
								tr_mutasi_pallet_draft_tgl_create: "<?= date('Y-m-d') ?>",
								tr_mutasi_pallet_draft_who_create: "<?= $this->session->userdata('pengguna_username') ?>",
								tr_mutasi_pallet_draft_nama_checker: $("#mutasi_draft_checker").val(),
								tr_mutasi_pallet_draft_nama_checker: arr_karyawan[1],
								tgl_update_before: $("#tgl_update_before").val(),
								karyawan_id: arr_karyawan[0]
							},
							beforeSend: function() {
								Swal.fire({
									title: 'Loading ...',
									html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
									timerProgressBar: false,
									showConfirmButton: false,
									allowOutsideClick: false,
								});

								$("#edit_koreksi_draft_edit").prop("disabled", true);
							},
							success: function(response) {
								$("#btn_update_mutasi_draft").prop("disabled", false);
								Swal.fire({
									title: 'Loading ...',
									html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
									timerProgressBar: false,
									showConfirmButton: false,
									allowOutsideClick: false,
									timer: 10
								});

								if (response == 1) {
									for (var x = 0; x < index_pallet; x++) {
										$.ajax({
											async: false,
											type: 'POST',
											url: "<?= base_url('WMS/MutasiPalletDraft/UpdateMutasiPalletDetailDraft') ?>",
											data: {
												tr_mutasi_pallet_draft_id: $("#mutasi_pallet_draft_id").val(),
												pallet_id: $("#pallet_id" + x).val(),
												rak_lajur_detail_id_asal: $("#rak_lajur_detail_id" + x).val()
											},
											success: function(response) {
												// console.log(x + " - " + index_pallet);
												console.log(response);
											}
										});
									}

									$("#loadingview").hide();

									Swal.fire({
										icon: 'success',
										title: 'Success',
										text: 'Draft Mutasi Pallet Berhasil Dibuat',
										showConfirmButton: false,
										timer: 1000
									});

									location.href = "<?= base_url() ?>WMS/MutasiPalletDraft/MutasiPalletDraftEdit/?tr_mutasi_pallet_draft_id=" + tr_mutasi_pallet_draft_id;
								} else if (response == "Approval setting tidak ada") {

									for (var x = 0; x < index_pallet; x++) {
										$.ajax({
											async: false,
											type: 'POST',
											url: "<?= base_url('WMS/MutasiPalletDraft/UpdateMutasiPalletDetailDraft') ?>",
											data: {
												tr_mutasi_pallet_draft_id: $("#mutasi_pallet_draft_id").val(),
												pallet_id: $("#pallet_id" + x).val(),
												rak_lajur_detail_id_asal: $("#rak_lajur_detail_id" + x).val()
											},
											success: function(response) {
												// console.log(x + " - " + index_pallet);
												console.log(response);
											}
										});
									}

									var msg = response;
									var msgtype = 'error';

									Swal.fire({
										icon: msgtype,
										title: 'Error',
										text: msg
									});

									$("#loadingview").hide();
								} else if (response == 2) {
									messageNotSameLastUpdated()
									setTimeout(() => location.reload(), 5000)

								} else if (response == 500) {
									var msg = 'Tanggal dokumen tidak boleh kurang dari atau sama dengan tanggal tutup gudang';
									var msgtype = 'error';

									Swal.fire({
										icon: msgtype,
										title: 'Error',
										text: msg
									});

									$("#loadingview").hide();
								} else {
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

									$("#loadingview").hide();
								}
							},
							error: function(xhr) {
								$("#btn_update_mutasi_draft").prop("disabled", false);
								Swal.fire({
									title: 'Loading ...',
									html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
									timerProgressBar: false,
									showConfirmButton: false,
									allowOutsideClick: false,
									timer: 10
								});
							}
						});
						// for (var x = 0; x < index_pallet; x++) {
						// 	if ($("#pallet_id" + x).val() != "") {
						// 		$.ajax({
						// 			async: false,
						// 			type: 'POST',
						// 			url: "<?= base_url('WMS/MutasiPalletDraft/UpdateMutasiPalletDetailDraft') ?>",
						// 			data: {
						// 				tr_mutasi_pallet_draft_id: $("#mutasi_pallet_draft_id").val(),
						// 				pallet_id: $("#pallet_id" + x).val()
						// 			},
						// 			success: function(response) {
						// 				console.log(x + " - " + index_pallet);
						// 				console.log(response);
						// 			}
						// 		});
						// 	}
						// }
					}
				});
			} else {
				Swal.fire({
					icon: 'error',
					title: 'Error',
					text: 'Pilih Pallet!'
				});
			}

		}
	);

	$("#btn-choose-pallet-multi").click(
		function() {
			$('#data-table-pallet').DataTable().search('').draw();

			var jumlah = $('input[name="CheckboxPallet"]').length;
			var numberOfChecked = $('input[name="CheckboxPallet"]:checked').length;
			var no = 1;
			var jml_pallet = $("#txt_jml_pallet").val();

			if (numberOfChecked > 0) {
				for (var i = 0; i < jumlah; i++) {
					var checked = $('[id="chk_pallet_' + i + '"]:checked').length;

					var pallet_id = $("#chk_pallet_" + i).val();
					var pallet_kode = $("#pallet_kode" + i).val();
					var rak_id = $("#rak_id" + i).val();
					var rak_nama = $("#rak_nama" + i).val();
					var rak_lajur_detail_id = $("#rak_lajur_detail_id" + i).val();
					var rak_lajur_detail_nama = $("#rak_lajur_detail_nama" + i).val();
					var pallet_jenis_id = $("#pallet_jenis_id" + i).val();
					var pallet_jenis_nama = $("#pallet_jenis_nama" + i).val();


					if (checked > 0) {
						var strmenu = '';

						strmenu = strmenu + '<tr>';
						strmenu = strmenu + '	<td class="text-center">' + pallet_kode;
						strmenu = strmenu + '	<input type="hidden" id="pallet_id' + index_pallet + '" value="' + pallet_id + '">';
						strmenu = strmenu + '	<input type="hidden" id="pallet_kode' + index_pallet + '" value="' + pallet_kode + '">';
						strmenu = strmenu + '	<input type="hidden" id="rak_id' + index_pallet + '" value="' + rak_id + '">';
						strmenu = strmenu + '	<input type="hidden" id="rak_nama' + index_pallet + '" value="' + rak_nama + '">';
						strmenu = strmenu + '	<input type="hidden" id="rak_lajur_detail_id' + index_pallet + '" value="' + rak_lajur_detail_id + '">';
						strmenu = strmenu + '	<input type="hidden" id="rak_lajur_detail_nama' + index_pallet + '" value="' + rak_lajur_detail_nama + '">';
						strmenu = strmenu + '	<input type="hidden" id="pallet_jenis_id' + index_pallet + '" value="' + pallet_jenis_id + '">';
						strmenu = strmenu + '	<input type="hidden" id="pallet_jenis_nama' + index_pallet + '" value="' + pallet_jenis_nama + '">';
						strmenu = strmenu + '	</td>';
						strmenu = strmenu + '	<td class="text-center">' + pallet_jenis_nama + '</td>';
						strmenu = strmenu + '	<td class="text-center">' + rak_nama + '</td>';
						strmenu = strmenu + '	<td class="text-center">' + rak_lajur_detail_nama + '</td>';
						strmenu = strmenu + '	<td class="text-center"><button class="btn btn-success" onclick="ViewDetailPallet2(\'' + pallet_id + '\')"><i class="fa fa-search"></i></button><button class="btn btn-danger" onclick="DeletePallet(this)"><i class="fa fa-trash"></i></button></td>';
						strmenu = strmenu + '</tr>';
						no++;
						jml_pallet++;
						index_pallet++;

						$("#table_mutasi_draft > tbody").append(strmenu);

					}
				}

				$("#txt_jml_pallet").val(jml_pallet);

			} else {
				Swal.fire({
					icon: 'error',
					title: 'Error',
					text: 'Pilih Pallet!'
				});
			}
		}
	);

	$("#btnback").click(
		function() {
			ResetForm();
			GetMutasiPalletDraftMenu();
		}
	);

	$("#btn-back-detail-pallet").click(
		function() {
			$('#modal-pallet-detail').modal('hide');
			$('#modal-pallet').modal('show');
		}
	);

	$("#btn-back-detail-pallet-2").click(
		function() {
			$('#modal-pallet-detail-2').modal('hide');
		}
	);

	$("#btn_history_approval").click(
		function() {
			var dokumen_id = $("#mutasi_pallet_draft_id").val();
			$('#modalHistoryApproval').modal('show');

			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/Approval/getApprovalByDocumentId') ?>",
				data: {
					dokumen_id: dokumen_id
				},
				success: function(response) {

					let no = 1;
					let data = response;

					if ($.fn.DataTable.isDataTable('#tableHistoryApproval')) {
						$('#tableHistoryApproval').DataTable().destroy();
					}

					$("#tableHistoryApproval tbody").empty();
					$("#tableHistoryApproval tbody").html('');
					if (data.length > 0) {
						$.each(data, function() {
							if (this.approval_status == "Approved") {
								var color = "style='background-color:green;color:white'"
							} else {
								var color = "style='background-color:red;color:white'"
							}
							$("#txt_jenis_pengajuan").val(`${this.approval_reff_dokumen_jenis}`);

							$('#tableHistoryApproval tbody').append(`
                                <tr>
                                    <td style='vertical-align:middle; text-align: center; ' >${no}</td>
                                    <td style='vertical-align:middle; text-align: center;' >${this.tgl}</td>
                                    <td style='vertical-align:middle; text-align: center;' >${this.approval_reff_dokumen_kode}</td>
                                    <td style='vertical-align:middle; text-align: center;' ><a ${color} class="btn btn-md">${this.approval_status}<a/></td>
                                    <td style='vertical-align:middle; text-align: center;' >${this.karyawan_nama}</td>
                                    <td style='vertical-align:middle; text-align: center;' >${this.approval_keterangan}</td>
                                </tr>
                            `);
							no++;
						});
					} else {
						$("#tableHistoryApproval tbody").html('');
					}

					$('#tableHistoryApproval').DataTable({
						paging: false
					});

				}
			});
		}
	);

	function DeletePallet(row) {
		var jml_pallet = $("#txt_jml_pallet").val() - 1;

		var row = row.parentNode.parentNode;
		row.parentNode.removeChild(row);

		$("#txt_jml_pallet").val(jml_pallet);
	}

	function ViewPallet() {
		$("#table_mutasi_draft > tbody").empty();
		$("#txt_jml_pallet").val(0);

		if ($("#mutasi_draft_tipe_transaksi").val() != "") {
			GetGudangTujuan($("#mutasi_draft_tipe_transaksi").val());
		} else {
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: 'Pilih Transaksi!'
			});
		}
	}

	function ViewDetailPallet(pallet_id) {
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/MutasiPalletDraft/GetPalletDetail') ?>",
			data: {
				pallet_id: pallet_id
			},
			success: function(response) {
				if (response) {
					$('#modal-pallet-detail').modal('show');
					$('#modal-pallet').modal('hide');

					ChPalletDetail(response);
				}
			}
		});

	}

	function ViewDetailPallet2(pallet_id) {
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/MutasiPalletDraft/GetPalletDetail') ?>",
			data: {
				pallet_id: pallet_id
			},
			success: function(response) {
				if (response) {
					$('#modal-pallet-detail-2').modal('show');

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
		$("#pallet_detail_pallet_kode2").val(pallet_kode);
		$("#pallet_detail_jenis_pallet2").val(pallet_jenis_nama);

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

	function GetCheckerPrinciple(principle) {

		$("#mutasi_draft_checker").html('');
		$("#table_mutasi_draft > tbody").empty();
		$("#txt_jml_pallet").val(0);

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/MutasiPalletDraft/GetCheckerPrinciple') ?>",
			data: {
				principle: principle,
				client_wms_id: $("#mutasi_draft_perusahaan").val()
			},
			success: function(response) {
				let data = response;

				$("#mutasi_draft_checker").html('');
				if (data.length > 0) {
					$.each(data, function() {
						$("#mutasi_draft_checker").append(`<option value="${this.karyawan_id} || ${this.karyawan_nama}">${this.karyawan_nama}</option>`);
					});
				} else {
					$("#mutasi_draft_checker").html('');
				}
			}
		});

	}

	function GetGudangTujuan(tipe_mutasi) {

		$("#gudang_tujuan_mutasi_draft").html('');

		if (tipe_mutasi == "C02CC613-2FAB-49A9-9372-B27D9444FE93") {

			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/MutasiPalletDraft/GetGudangTujuanByTipe') ?>",
				data: {
					gudang_asal: $("#gudang_asal_mutasi_draft").val()
				},
				success: function(response) {
					let data = response;

					$("#gudang_tujuan_mutasi_draft").html('');
					if (data.length > 0) {
						$("#gudang_tujuan_mutasi_draft").append(`<option value="">** Pilih Gudang Tujuan **</option>`);
						$.each(data, function() {
							$("#gudang_tujuan_mutasi_draft").append(`<option value="${this.depo_detail_id}">${this.depo_detail_nama}</option>`);
						});
					} else {
						$("#gudang_tujuan_mutasi_draft").html('');
					}
				}
			});
		} else {
			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/MutasiPalletDraft/GetGudangTujuan') ?>",
				data: {
					gudangAsal: $("#gudang_asal_mutasi_draft").val()
				},
				success: function(response) {
					let data = response;

					$("#gudang_tujuan_mutasi_draft").html('');
					if (data.length > 0) {
						$("#gudang_tujuan_mutasi_draft").append(`<option value="">** Pilih Gudang Tujuan **</option>`);
						$.each(data, function() {
							$("#gudang_tujuan_mutasi_draft").append(`<option value="${this.depo_detail_id}">${this.depo_detail_nama}</option>`);
						});
					} else {
						$("#gudang_tujuan_mutasi_draft").html('');
					}
				}
			});
		}
	}

	function ResetForm() {
		<?php
		if ($Menu_Access["U"] == 1) {
		?>
			$("#txtupdateMutasiPalletnama").val('');
			$("#cbupdateMutasiPalletjenis").prop('selectedIndex', 0);
			$("#chupdateMutasiPalletsppbr").prop('checked', false);
			$("#chupdateMutasiPallettipeecomm").prop('checked', false);
			$("#chupdateMutasiPalletisactive").prop('checked', false);

			$("#loadingview").hide();
			$("#btnsaveupdatenewMutasiPallet").prop("disabled", false);
		<?php
		}
		?>

		<?php
		if ($Menu_Access["C"] == 1) {
		?>
			$("#txtMutasiPalletnama").val('');
			$("#cbMutasiPalletjenis").prop('selectedIndex', 0);
			$("#chMutasiPalletsppbr").prop('checked', false);
			$("#chMutasiPallettipeecomm").prop('checked', false);
			$("#chMutasiPalletisactive").prop('checked', false);

			$("#loadingview").hide();
			$("#btnsaveaddnewMutasiPallet").prop("disabled", false);
		<?php
		}
		?>

	}

	$(document).ready(function() {
		if ($('#filter_fdjr_date').length > 0) {
			$('#filter_fdjr_date').daterangepicker({
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

	});
</script>