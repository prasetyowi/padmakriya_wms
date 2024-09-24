<script type="text/javascript">
	var ChannelCode = '';
	var skuDeliverytable;

	var no_pallet = 1;
	var index_pallet = 0;
	var index_detail_pallet = 0;
	var arr_index_pallet = [];
	var arr_index_detail_pallet = [];
	var arr_sku_id = [];
	var arr_pallet_id = [];
	var arr_penerimaan_tipe = [];
	var arr_list_penerimaan_tipe = ['retur'];

	const html5QrCode = new Html5Qrcode("preview");
	let timerInterval
	loadingBeforeReadyPage()

	$('#select-sku').click(function(event) {
		if (this.checked) {
			// Iterate each checkbox
			$('[name="CheckboxSKU"]:checkbox').each(function() {
				this.checked = true;
			});
		} else {
			$('[name="CheckboxSKU"]:checkbox').each(function() {
				this.checked = false;
			});
		}
	});

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

	$(document).ready(
		function() {

			GetBTBFormMenu();
			$(".select2").select2({
				width: '100%'
			});
		}
	);

	function GetBTBFormMenu() {
		var delivery_order_batch_id = $("#delivery_order_batch_id").val();
		$("#loadingview").show();

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/SuratTugasPengiriman/check_sisa_btb_by_fdjr') ?>",
			data: "delivery_order_batch_id=" + delivery_order_batch_id,
			success: function(response) {
				$("#loadingview").hide();

				if (response == 0) {
					Swal.fire({
						position: 'center',
						icon: 'error',
						title: '<span name="CAPTION-ALERT-BTBSUDAHDIBUAT">BTB Sudah dibuat!',
						timer: 500
					});

					setTimeout(() => {
						window.location.href =
							"<?php echo base_url() ?>WMS/SuratTugasPengiriman/ClosingPengirimanMenu/?delivery_order_batch_id=" +
							delivery_order_batch_id;
					}, 1000);
				} else {
					$.ajax({
						type: 'POST',
						url: "<?= base_url('WMS/SuratTugasPengiriman/GetBTBFormMenu') ?>",
						data: "delivery_order_batch_id=" + delivery_order_batch_id,
						success: function(response) {
							if (response) {
								ChBTBFormMenu(response);
							}
						}
					});
				}
			}
		});
	}

	function ChBTBFormMenu(JSONChannel) {
		$("#tabledoretur > tbody").html('');
		$("#tableterkirimsebagian > tbody").html('');
		$("#tablegagalkirim > tbody").html('');

		$("#filter_gudang_penerima_do_retur").html('');
		$("#filter_gudang_penerima_terkirim_sebagian").html('');
		$("#filter_gudang_penerima_gagal_kirim").html('');
		$("#filter_gudang_penerima_barang_titipan").html('');

		var Channel = JSON.parse(JSONChannel);

		var delivery_order_batch_id = Channel.BTBHeader[0].delivery_order_batch_id;
		var delivery_order_batch_kode = Channel.BTBHeader[0].delivery_order_batch_kode;
		var karyawan_id = Channel.BTBHeader[0].karyawan_id;
		var karyawan_nama = Channel.BTBHeader[0].karyawan_nama;
		var check_pallet = Channel.BTBHeader[0].pallet_id;

		// if (check_pallet != null) {
		// 	// $("#btnsavebtb").hide();
		// 	$("#btnpenerimaanperpalletdoretur").hide();
		// } else {
		// 	initDataTablePallet();
		// }

		$('#filter_fdjr_no').val(delivery_order_batch_kode);
		$('#filter_fdjr_driver').append('<option value="' + karyawan_id + '">' + karyawan_nama + '</option>');

		if (Channel.GudangPenerima != 0) {
			for (i = 0; i < Channel.GudangPenerima.length; i++) {
				var depo_detail_id = Channel.GudangPenerima[i].depo_detail_id;
				var depo_detail_nama = Channel.GudangPenerima[i].depo_detail_nama;

				$("#filter_gudang_penerima_do_retur").append('<option value="' + depo_detail_id + '">' + depo_detail_nama +
					'</option>');
				$("#filter_gudang_penerima_terkirim_sebagian").append('<option value="' + depo_detail_id + '">' +
					depo_detail_nama + '</option>');
				$("#filter_gudang_penerima_gagal_kirim").append('<option value="' + depo_detail_id + '">' +
					depo_detail_nama + '</option>');
				$("#filter_gudang_penerima_barang_titipan").append('<option value="' + depo_detail_id + '">' +
					depo_detail_nama + '</option>');

			}
		}

		// if (Channel.BTBHeader != 0) {
		// 	var no = 1;

		// 	if ($.fn.DataTable.isDataTable('#tablepalletdoretur')) {
		// 		$('#tablepalletdoretur').DataTable().destroy();
		// 	}

		// 	$('#tablepalletdoretur tbody').empty();

		// 	for (i = 0; i < Channel.BTBHeader.length; i++) {
		// 		var pallet_id = Channel.BTBHeader[i].pallet_id;
		// 		var pallet_kode = Channel.BTBHeader[i].pallet_kode;
		// 		var pallet_jenis_nama = Channel.BTBHeader[i].pallet_jenis_nama;

		// 		var strmenu = '';

		// 		if (pallet_id != null) {

		// 			strmenu = strmenu + '<tr>';
		// 			strmenu = strmenu + '	<td>' + pallet_kode + '</td>';
		// 			strmenu = strmenu + '	<td>' + pallet_jenis_nama + '</td>';
		// 			strmenu = strmenu + '	<td class="text-center"><button type="button" class="btn btn-sm btn-primary" id="btn-detail-pallet-do-retur" onClick="ViewPalletDetail(\'' + pallet_id + '\')"><i class="fa fa-pencil"></i></button></td>';
		// 			strmenu = strmenu + '</tr>';

		// 			no++;

		// 			$("#tablepalletdoretur > tbody").append(strmenu);

		// 		}
		// 	}
		// }

		$("#loadingview").hide();
	}

	function GetBTBDetailByPrinciple() {
		var delivery_order_batch_id = $("#delivery_order_batch_id").val();
		var perusahaan = $("#filter_perusahaan").val();
		var principle = $("#filter_principle").val();

		$("#loadingview").show();

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/SuratTugasPengiriman/GetBTBDetailByPrinciple') ?>",
			data: {
				delivery_order_batch_id: delivery_order_batch_id,
				perusahaan: perusahaan,
				principle: principle

			},
			success: function(response) {
				if (response) {
					ChBTBDetailByPrinciple(response);
				}
			}
		});

	}

	function ChBTBDetailByPrinciple(JSONChannel) {

		$("#loadingview").show();

		var Channel = JSON.parse(JSONChannel);

		if (Channel.BTBDoRetur != 0) {
			var no = 1;

			if ($.fn.DataTable.isDataTable('#tabledoretur')) {
				$('#tabledoretur').DataTable().destroy();
			}

			$('#tabledoretur tbody').empty();

			for (i = 0; i < Channel.BTBDoRetur.length; i++) {
				var delivery_order_batch_id = Channel.BTBDoRetur[i].delivery_order_batch_id;
				var delivery_order_batch_kode = Channel.BTBDoRetur[i].delivery_order_batch_kode;
				var delivery_order_id = Channel.BTBDoRetur[i].delivery_order_id;
				var delivery_order_kode = Channel.BTBDoRetur[i].delivery_order_kode;
				var delivery_order_tgl_aktual_kirim = Channel.BTBDoRetur[i].delivery_order_tgl_aktual_kirim;
				var delivery_order_kirim_nama = Channel.BTBDoRetur[i].delivery_order_kirim_nama;
				var principle = Channel.BTBDoRetur[i].principle;
				var brand = Channel.BTBDoRetur[i].brand;
				var sku_id = Channel.BTBDoRetur[i].sku_id;
				var sku_kode = Channel.BTBDoRetur[i].sku_kode;
				var sku_nama_produk = Channel.BTBDoRetur[i].sku_nama_produk;
				var sku_kemasan = Channel.BTBDoRetur[i].sku_kemasan;
				var sku_satuan = Channel.BTBDoRetur[i].sku_satuan;
				var sku_qty = Math.round(Channel.BTBDoRetur[i].sku_qty);
				var sku_qty_kirim = Math.round(Channel.BTBDoRetur[i].sku_qty_kirim);
				var sisa_jumlah_terima = Math.round(Channel.BTBDoRetur[i].sisa_jumlah_terima);
				var karyawan_id = Channel.BTBDoRetur[i].karyawan_id;
				var karyawan_nama = Channel.BTBDoRetur[i].karyawan_nama;
				var tipe_delivery_order_alias = Channel.BTBDoRetur[i].tipe_delivery_order_alias;

				var strmenu = '';

				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td>' + no + '</td>';
				strmenu = strmenu + '	<td>' + delivery_order_kode + '</td>';
				strmenu = strmenu + '	<td>' + delivery_order_kirim_nama + '</td>';
				strmenu = strmenu + '	<td>' + principle + '</td>';
				strmenu = strmenu + '	<td>' + sku_kode + '</td>';
				strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
				strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
				strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
				strmenu = strmenu + '	<td>' + sku_qty + '</td>';
				strmenu = strmenu + '	<td>' + sisa_jumlah_terima + '</td>';
				strmenu = strmenu + '</tr>';

				no++;

				$("#tabledoretur > tbody").append(strmenu);
			}

			// $('#tabledoretur').DataTable({
			// 	"lengthMenu": [
			// 		[5],
			// 		[5]
			// 	],
			// 	"ordering": false,
			// 	"search": false,
			// 	"info": false
			// });
		} else {
			$('#tabledoretur tbody').empty();
		}

		if (Channel.BTBTerkirimSebagian != 0) {
			var no = 1;

			if ($.fn.DataTable.isDataTable('#tableterkirimsebagian')) {
				$('#tableterkirimsebagian').DataTable().destroy();
			}

			$('#tableterkirimsebagian tbody').empty();

			for (i = 0; i < Channel.BTBTerkirimSebagian.length; i++) {
				var delivery_order_batch_id = Channel.BTBTerkirimSebagian[i].delivery_order_batch_id;
				var delivery_order_batch_kode = Channel.BTBTerkirimSebagian[i].delivery_order_batch_kode;
				var delivery_order_id = Channel.BTBTerkirimSebagian[i].delivery_order_id;
				var delivery_order_kode = Channel.BTBTerkirimSebagian[i].delivery_order_kode;
				var delivery_order_tgl_aktual_kirim = Channel.BTBTerkirimSebagian[i].delivery_order_tgl_aktual_kirim;
				var delivery_order_kirim_nama = Channel.BTBTerkirimSebagian[i].delivery_order_kirim_nama;
				var principle = Channel.BTBTerkirimSebagian[i].principle;
				var brand = Channel.BTBTerkirimSebagian[i].brand;
				var sku_id = Channel.BTBTerkirimSebagian[i].sku_id;
				var sku_kode = Channel.BTBTerkirimSebagian[i].sku_kode;
				var sku_nama_produk = Channel.BTBTerkirimSebagian[i].sku_nama_produk;
				var sku_kemasan = Channel.BTBTerkirimSebagian[i].sku_kemasan;
				var sku_satuan = Channel.BTBTerkirimSebagian[i].sku_satuan;
				var sku_qty = Math.round(Channel.BTBTerkirimSebagian[i].sku_qty);
				var sku_qty_kirim = Math.round(Channel.BTBTerkirimSebagian[i].sku_qty_kirim);
				var sisa_jumlah_terima = Math.round(Channel.BTBTerkirimSebagian[i].sisa_jumlah_terima);
				var karyawan_id = Channel.BTBTerkirimSebagian[i].karyawan_id;
				var karyawan_nama = Channel.BTBTerkirimSebagian[i].karyawan_nama;
				var tipe_delivery_order_alias = Channel.BTBTerkirimSebagian[i].tipe_delivery_order_alias;

				var strmenu = '';

				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td>' + no + '</td>';
				strmenu = strmenu + '	<td>' + delivery_order_kode + '</td>';
				strmenu = strmenu + '	<td>' + delivery_order_kirim_nama + '</td>';
				strmenu = strmenu + '	<td>' + principle + '</td>';
				strmenu = strmenu + '	<td>' + sku_kode + '</td>';
				strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
				strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
				strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
				strmenu = strmenu + '	<td>' + sku_qty + '</td>';
				strmenu = strmenu + '	<td>' + sisa_jumlah_terima + '</td>';
				strmenu = strmenu + '</tr>';

				no++;

				$("#tableterkirimsebagian > tbody").append(strmenu);
			}

			// $('#tableterkirimsebagian').DataTable({
			// 	"lengthMenu": [
			// 		[5],
			// 		[5]
			// 	],
			// 	"ordering": false,
			// 	"search": false,
			// 	"info": false
			// });
		} else {
			$('#tableterkirimsebagian tbody').empty();
		}

		if (Channel.BTBGagal != 0) {
			var no = 1;

			if ($.fn.DataTable.isDataTable('#tablegagalkirim')) {
				$('#tablegagalkirim').DataTable().destroy();
			}

			$('#tablegagalkirim tbody').empty();

			for (i = 0; i < Channel.BTBGagal.length; i++) {
				var delivery_order_batch_id = Channel.BTBGagal[i].delivery_order_batch_id;
				var delivery_order_batch_kode = Channel.BTBGagal[i].delivery_order_batch_kode;
				var delivery_order_id = Channel.BTBGagal[i].delivery_order_id;
				var delivery_order_kode = Channel.BTBGagal[i].delivery_order_kode;
				var delivery_order_tgl_aktual_kirim = Channel.BTBGagal[i].delivery_order_tgl_aktual_kirim;
				var delivery_order_kirim_nama = Channel.BTBGagal[i].delivery_order_kirim_nama;
				var principle = Channel.BTBGagal[i].principle;
				var brand = Channel.BTBGagal[i].brand;
				var sku_id = Channel.BTBGagal[i].sku_id;
				var sku_kode = Channel.BTBGagal[i].sku_kode;
				var sku_nama_produk = Channel.BTBGagal[i].sku_nama_produk;
				var sku_kemasan = Channel.BTBGagal[i].sku_kemasan;
				var sku_satuan = Channel.BTBGagal[i].sku_satuan;
				var sku_qty = Math.round(Channel.BTBGagal[i].sku_qty);
				var sku_qty_kirim = Math.round(Channel.BTBGagal[i].sku_qty_kirim);
				var sisa_jumlah_terima = Math.round(Channel.BTBGagal[i].sisa_jumlah_terima);
				var karyawan_id = Channel.BTBGagal[i].karyawan_id;
				var karyawan_nama = Channel.BTBGagal[i].karyawan_nama;
				var tipe_delivery_order_alias = Channel.BTBGagal[i].tipe_delivery_order_alias;

				var strmenu = '';

				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td>' + no + '</td>';
				strmenu = strmenu + '	<td>' + delivery_order_kode + '</td>';
				strmenu = strmenu + '	<td>' + delivery_order_kirim_nama + '</td>';
				strmenu = strmenu + '	<td>' + principle + '</td>';
				strmenu = strmenu + '	<td>' + sku_kode + '</td>';
				strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
				strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
				strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
				strmenu = strmenu + '	<td>' + sku_qty + '</td>';
				strmenu = strmenu + '	<td>' + sisa_jumlah_terima + '</td>';
				strmenu = strmenu + '</tr>';

				no++;

				$("#tablegagalkirim > tbody").append(strmenu);
			}

			// $('#tablegagalkirim').DataTable({
			// 	"lengthMenu": [
			// 		[5],
			// 		[5]
			// 	],
			// 	"ordering": false,
			// 	"search": false,
			// 	"info": false
			// });
		} else {
			$('#tablegagalkirim tbody').empty();

		}

		$("#loadingview").hide();
	}

	function ViewSlcReason(i) {
		var checkBox = document.getElementById("chk_do_gagal_" + i);
		var delivery_order_batch_id = $("#delivery_order_batch_id").val();

		if (checkBox.checked == true) {
			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/SuratTugasPengiriman/GetReason') ?>",
				dataType: "json",
				success: function(response) {
					if (response) {
						$("#slc_reason" + i).html('');
						if (response.Reason != 0) {
							for (x = 0; x < response.Reason.length; x++) {
								reason_id = response.Reason[x].reason_id;
								reason_keterangan = response.Reason[x].reason_keterangan;

								$("#slc_reason" + i).append('<option value="' + reason_id + '">' +
									reason_keterangan + '</option>');
							}
						}

						$("#slc_reason" + i).select2();
					}
				}
			});

		} else {
			$("#slc_reason" + i).html('');
			$("#slc_reason" + i).append('<option value=""></option>');

		}

	}

	function ViewAllSlcReason() {
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/SuratTugasPengiriman/GetReason') ?>",
			dataType: "json",
			success: function(response) {
				if (response) {
					$('[name="slcreason"]').html('');
					if (response.Reason != 0) {
						for (x = 0; x < response.Reason.length; x++) {
							reason_id = response.Reason[x].reason_id;
							reason_keterangan = response.Reason[x].reason_keterangan;

							$('[name="slcreason"]').append('<option value="' + reason_id + '">' +
								reason_keterangan + '</option>');
						}
					}

					$('[name="slcreason"]').select2();
				}
			}
		});

	}

	function ViewFormTerkirimSebagian(delivery_order_id, i) {
		var checkBox = document.getElementById("chk_do_terkirim_sebagian_" + i);

		if (checkBox.checked == true) {

			$('#previewformdoterkirimsebagian').modal('show');
			$('#loadingview').show();
			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/SuratTugasPengiriman/GetBTBDoReturByDO') ?>",
				data: "delivery_order_id=" + delivery_order_id,
				success: function(response) {
					if (response) {
						ChTerkirimSebagianMenu(response);
					}
				}
			});
		}

	}

	function ChTerkirimSebagianMenu(JSONChannel) {
		$("#tabledetaildo > tbody").html('');

		var Channel = JSON.parse(JSONChannel);
		var no = 1;

		var delivery_order_id = Channel.DeliveryOrder[0].delivery_order_id;
		var delivery_order_kode = Channel.DeliveryOrder[0].delivery_order_kode

		$("#delivery_order_id").val(delivery_order_id);
		$("#delivery_order_kode").val(delivery_order_kode);
		$("#txt_jumlah_do").val(Channel.DeliveryOrder.length);

		if (Channel.DeliveryOrder != 0) {
			if ($.fn.DataTable.isDataTable('#tabledetaildo')) {
				$('#tabledetaildo').DataTable().destroy();
			}

			$('#tabledetaildo tbody').empty();

			for (i = 0; i < Channel.DeliveryOrder.length; i++) {
				var delivery_order_detail_id = Channel.DeliveryOrder[i].delivery_order_detail_id;
				var sku_id = Channel.DeliveryOrder[i].sku_id;
				var principle = Channel.DeliveryOrder[i].principle;
				var brand = Channel.DeliveryOrder[i].brand;
				var sku_kode = Channel.DeliveryOrder[i].sku_kode;
				var sku_nama_produk = Channel.DeliveryOrder[i].sku_nama_produk;
				var sku_kemasan = Channel.DeliveryOrder[i].sku_kemasan;
				var sku_satuan = Channel.DeliveryOrder[i].sku_satuan;
				var sku_qty = Channel.DeliveryOrder[i].sku_qty;
				var sku_qty_kirim = Channel.DeliveryOrder[i].sku_qty_kirim;

				var strmenu = '';

				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td>' + principle + '</td>';
				strmenu = strmenu + '	<td>' + brand + '</td>';
				strmenu = strmenu + '	<td><input type="hidden" id="delivery_order_detail_id' + i +
					'" name="delivery_order_detail_id' + i + '" value="' + delivery_order_detail_id + '"></td>';
				strmenu = strmenu + '	<td style="width:20%;">' + sku_nama_produk + '</td>';
				strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
				strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
				strmenu = strmenu + '	<td>' + Math.round(sku_qty) + '</td>';
				strmenu = strmenu + '	<td><input type="text" style="width:70%;" class="form-control" id="txt_qty_kirim' +
					i + '" name="txt_qty_kirim' + i + '" value="0"></td>';
				strmenu = strmenu + '	<td style="width:20%;"><select id="slc_reason_detail' + i +
					'" class="form-control" style="width:100%"></select></td>';
				strmenu = strmenu + '</tr>';

				no++;

				$("#tabledetaildo > tbody").append(strmenu);
			}

			for (i = 0; i < Channel.DeliveryOrder.length; i++) {

				$("#slc_reason_detail" + i).html('');

				$("#slc_reason_detail" + i).append('<option value=""></option>');
				if (Channel.Reason != 0) {
					for (x = 0; x < Channel.Reason.length; x++) {
						reason_id = Channel.Reason[x].reason_id;
						reason_keterangan = Channel.Reason[x].reason_keterangan;

						$("#slc_reason_detail" + i).append('<option value="' + reason_id + '">' + reason_keterangan +
							'</option>');
					}
				}

				$("#slc_reason_detail" + i).select2();
			}

			$("#loadingview").hide();

			$('#tabledetaildo').DataTable({
				"lengthMenu": [
					[25],
					[25]
				],
				"ordering": false
			});
		} else {
			$('#tabledetaildo').DataTable().clear().draw();
			// ResetForm();
		}

	}

	$("#btnsavebtb").click(
		function() {
			var delivery_order_batch_id = $("#delivery_order_batch_id").val();
			var client_wms_id = $("#filter_perusahaan").val();
			var principle_id = $("#filter_principle").val();
			var karyawan_id = $("#filter_checker").val();
			// var penerimaan_tipe = $("#fdjr_type").val();
			var success = 0;
			var penerimaan_penjualan_keterangan = "";
			arr_penerimaan_tipe = [];

			let error = false;

			$("select[name*='kondisiBarang']").map(function() {
				if (this.value == "") {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'Kondisi barang tidak boleh kosong!'
					});

					error = true;
					return false;
				}
			});

			if (error) return false;


			const kondisiBarang = $("select[name*='kondisiBarang']").map(function() {
				const splitData = this.value.split(',')
				return {
					delivery_order_id: splitData[1],
					kondisiBarang: splitData[0],
				}
			}).get();

			var result = arr_list_penerimaan_tipe.reduce((unique, o) => {
				if (!unique.some(obj => obj === o)) {
					unique.push(o);
				}
				return unique;
			}, []);

			arr_list_penerimaan_tipe = result;

			// console.log(arr_list_penerimaan_tipe);

			for (var i = 0; i < arr_list_penerimaan_tipe.length; i++) {
				if (arr_list_penerimaan_tipe[i] == "retur") {
					arr_penerimaan_tipe.push({
						'tipe': "retur",
						'depo_detail_id': $("#filter_gudang_penerima_do_retur").val(),
						'penerimaan_tipe_id': "BDCFCBE1-52CF-404F-84B5-A19F0918CA8D"
					});
				} else if (arr_list_penerimaan_tipe[i] == "terkirim sebagian") {
					arr_penerimaan_tipe.push({
						'tipe': "terkirim sebagian",
						'depo_detail_id': $("#filter_gudang_penerima_terkirim_sebagian").val(),
						'penerimaan_tipe_id': "29F5A94F-C55E-4BA0-B3EE-57A93327735F"
					});
				} else if (arr_list_penerimaan_tipe[i] == "tidak terkirim") {
					arr_penerimaan_tipe.push({
						'tipe': "tidak terkirim",
						'depo_detail_id': $("#filter_gudang_penerima_gagal_kirim").val(),
						'penerimaan_tipe_id': "79F2522A-CEA5-4FF8-BC79-C0B28808877D"
					});
				} else if (arr_list_penerimaan_tipe[i] == "titipan") {
					arr_penerimaan_tipe.push({
						'tipe': "titipan",
						'depo_detail_id': $("#filter_gudang_penerima_barang_titipan").val(),
						'penerimaan_tipe_id': "A9F3967E-94ED-4761-B385-4770FEEC229A"
					});
				}
			}

			// console.log(arr_penerimaan_tipe);

			Swal.fire({
				title: '<b>APA ANDA YAKIN?</b>',
				text: "BTB yang sudah diibuat tidak bisa diubah",
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
						url: "<?= base_url('WMS/SuratTugasPengiriman/InsertPenerimaanPenjualan') ?>",
						data: {
							delivery_order_batch_id: delivery_order_batch_id,
							karyawan_id: karyawan_id,
							client_wms_id: client_wms_id,
							principle_id: principle_id,
							penerimaan_penjualan_keterangan: penerimaan_penjualan_keterangan,
							arr_penerimaan_tipe: arr_penerimaan_tipe,
							kondisiBarang
						},
						success: function(response) {
							if (response) {
								if (response == 1) {
									$("#loadingview").hide();

									var msg = 'Data berhasil disimpan';
									var msgtype = 'success';

									//if (!window.__cfRLUnblockHandlers) return false;
									Swal.fire({
										position: 'center',
										icon: msgtype,
										title: msg,
										timer: 1000
									});

									window.location.reload();

									// window.location.href = "<?php echo base_url() ?>SuratTugasPengiriman/ClosingPengirimanMenu/?delivery_order_batch_id=" + delivery_order_batch_id;

									// window.close();

									// GetBTBFormMenu();
								} else {
									if (response == 2) {
										var msg = 'BTB Sudah Ada!';

										Swal.fire({
											icon: 'error',
											title: 'Error',
											text: msg
										});

									} else if (response == 3) {
										var msg = 'Jumlah SKU Pallet Masih Kosong!';

										Swal.fire({
											icon: 'error',
											title: 'Error',
											text: msg
										});

									} else if (response == 4) {
										var msg = 'FDJR Sudah Memiliki Pallet!';

										Swal.fire({
											icon: 'error',
											title: 'Error',
											text: msg
										});

									} else if (response == 5) {
										var msg = 'Tambah SKU Master';

										Swal.fire({
											icon: 'error',
											title: 'SKU Tidak Ada!',
											text: msg
										});

									} else {
										var msg = response;
										var msgtype = 'error';

										// if (!window.__cfRLUnblockHandlers) return false;
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
									$("#loadingview").hide();
									// console.log(msg);
								}
							}
						}
					});


					// for (var i = 0; i < arr_penerimaan_tipe.length; i++) {
					// 	penerimaan_tipe = arr_penerimaan_tipe[i];

					// 	if (penerimaan_tipe == "retur") {
					// 		depo_detail_id = $("#filter_gudang_penerima_do_retur").val();
					// 		penerimaan_tipe_id = "BDCFCBE1-52CF-404F-84B5-A19F0918CA8D";
					// 	} else if (penerimaan_tipe == "terkirim sebagian") {
					// 		depo_detail_id = $("#filter_gudang_penerima_terkirim_sebagian").val();
					// 		penerimaan_tipe_id = "29F5A94F-C55E-4BA0-B3EE-57A93327735F";
					// 	} else if (penerimaan_tipe == "tidak terkirim") {
					// 		depo_detail_id = $("#filter_gudang_penerima_gagal_kirim").val();
					// 		penerimaan_tipe_id = "79F2522A-CEA5-4FF8-BC79-C0B28808877D";
					// 	} else if (penerimaan_tipe == "titipan") {
					// 		depo_detail_id = $("#filter_gudang_penerima_barang_titipan").val();
					// 		penerimaan_tipe_id = "A9F3967E-94ED-4761-B385-4770FEEC229A";
					// 	}

					// 	$.ajax({
					// 		async: false,
					// 		type: 'POST',
					// 		url: "<?= base_url('WMS/SuratTugasPengiriman/InsertPenerimaanPenjualan') ?>",
					// 		data: {
					// 			delivery_order_batch_id: delivery_order_batch_id,
					// 			karyawan_id: karyawan_id,
					// 			client_wms_id: client_wms_id,
					// 			principle_id: principle_id,
					// 			penerimaan_tipe_id: penerimaan_tipe_id,
					// 			depo_detail_id: depo_detail_id,
					// 			penerimaan_penjualan_keterangan: penerimaan_penjualan_keterangan
					// 		},
					// 		success: function(response) {
					// 			if (response) {
					// 				if (response == 1) {
					// 					success += 1;
					// 					$("#loadingview").hide();

					// 					var msg = 'Data berhasil disimpan';
					// 					var msgtype = 'success';

					// 					//if (!window.__cfRLUnblockHandlers) return false;
					// 					Swal.fire({
					// 						position: 'center',
					// 						icon: msgtype,
					// 						title: msg,
					// 						timer: 1000
					// 					});

					// 					// window.location.href = "<?php echo base_url() ?>SuratTugasPengiriman/ClosingPengirimanMenu/?delivery_order_batch_id=" + delivery_order_batch_id;

					// 					// window.close();

					// 					// GetBTBFormMenu();
					// 				} else {
					// 					if (response == 2) {
					// 						var msg = 'BTB Sudah Ada!';

					// 						Swal.fire({
					// 							icon: 'error',
					// 							title: 'Error',
					// 							text: msg
					// 						});

					// 					} else if (response == 3) {
					// 						var msg = 'Jumlah SKU Pallet Masih Kosong!';

					// 						Swal.fire({
					// 							icon: 'error',
					// 							title: 'Error',
					// 							text: msg
					// 						});

					// 					} else if (response == 4) {
					// 						var msg = 'FDJR Sudah Memiliki Pallet!';

					// 						Swal.fire({
					// 							icon: 'error',
					// 							title: 'Error',
					// 							text: msg
					// 						});

					// 					} else if (response == 5) {
					// 						var msg = 'Tambah SKU Master';

					// 						Swal.fire({
					// 							icon: 'error',
					// 							title: 'SKU Tidak Ada!',
					// 							text: msg
					// 						});

					// 					} else {
					// 						var msg = response;
					// 						var msgtype = 'error';

					// 						// if (!window.__cfRLUnblockHandlers) return false;
					// 						new PNotify
					// 							({
					// 								title: 'Error',
					// 								text: msg,
					// 								type: msgtype,
					// 								styling: 'bootstrap3',
					// 								delay: 3000,
					// 								stack: stack_center
					// 							});
					// 					}
					// 					$("#loadingview").hide();
					// 					// console.log(msg);
					// 				}
					// 			}
					// 		}
					// 	});

					// }

					// setTimeout(() => {
					// 	if (success > 0) {

					// 		$("#loadingview").hide();

					// 		// var msg = 'Data berhasil disimpan';
					// 		// var msgtype = 'success';

					// 		// //if (!window.__cfRLUnblockHandlers) return false;
					// 		// Swal.fire({
					// 		// 	position: 'center',
					// 		// 	icon: msgtype,
					// 		// 	title: msg,
					// 		// 	timer: 1000
					// 		// });

					// 		// window.location.reload();

					// 		$.ajax({
					// 			async: false,
					// 			type: 'POST',
					// 			url: "<?= base_url('WMS/SuratTugasPengiriman/InsertPallet') ?>",
					// 			data: {
					// 				delivery_order_batch_id: delivery_order_batch_id
					// 			},
					// 			success: function(response) {
					// 				var msg = 'Data berhasil disimpan';
					// 				var msgtype = 'success';

					// 				//if (!window.__cfRLUnblockHandlers) return false;
					// 				Swal.fire({
					// 					position: 'center',
					// 					icon: msgtype,
					// 					title: msg,
					// 					timer: 1000
					// 				});

					// 				window.location.reload();
					// 			}
					// 		});

					// 	} else {
					// 		var msg = 'BTB Gagal Disimpan!';

					// 		Swal.fire({
					// 			icon: 'error',
					// 			title: 'Error',
					// 			text: msg
					// 		});

					// 	}
					// }, 1000);
				}
			});
		}
	);

	$("#btn-tambah-sku-fdjr").click(
		function() {
			var delivery_order_batch_id = $('#delivery_order_batch_id').val();
			var pallet_id = $('#pallet_id').val();
			var fdjr_type = $('#fdjr_type').val();

			if (fdjr_type == "retur") {

				$("#loadingview").show();
				$.ajax({
					async: false,
					type: 'POST',
					url: "<?= base_url('WMS/SuratTugasPengiriman/InsertPalletDetailTempDoRetur') ?>",
					data: {
						pallet_id: pallet_id,
						delivery_order_batch_id: delivery_order_batch_id
					},
					success: function(response) {
						if (response) {
							if (response == 1) {
								$("#loadingview").hide();
								console.log('success');
								initDataTablePalletDetail(pallet_id);
							} else {
								if (response == 0) {
									var msg = 'Tidak Ada Barang Terima Barang DO Retur';
								} else {
									var msg = response;
								}
								var msgtype = 'error';

								// if (!window.__cfRLUnblockHandlers) return false;
								Swal.fire({
									icon: msgtype,
									title: 'Error',
									text: msg
								});

								$("#loadingview").hide();
								initDataTablePalletDetail(pallet_id);

								// console.log(msg);
							}
						}
					}
				});

			} else if (fdjr_type == "terkirim sebagian") {
				$("#loadingview").show();
				$.ajax({
					async: false,
					type: 'POST',
					url: "<?= base_url('WMS/SuratTugasPengiriman/InsertPalletDetailTempTerkirimSebagian') ?>",
					data: {
						pallet_id: pallet_id,
						delivery_order_batch_id: delivery_order_batch_id
					},
					success: function(response) {
						if (response) {
							if (response == 1) {
								console.log('success');
								$("#loadingview").hide();
								initDataTablePalletDetail(pallet_id);
							} else {
								if (response == 0) {
									var msg = 'Tidak Ada Barang Terima Barang Terkirim Sebagian';
								} else {
									var msg = response;
								}
								var msgtype = 'error';

								// if (!window.__cfRLUnblockHandlers) return false;
								Swal.fire({
									icon: msgtype,
									title: 'Error',
									text: msg
								});

								$("#loadingview").hide();
								initDataTablePalletDetail(pallet_id);

								// console.log(msg);
							}
						}
					}
				});
			} else if (fdjr_type == "tidak terkirim") {
				$("#loadingview").show();
				$.ajax({
					async: false,
					type: 'POST',
					url: "<?= base_url('WMS/SuratTugasPengiriman/InsertPalletDetailTempGagal') ?>",
					data: {
						pallet_id: pallet_id,
						delivery_order_batch_id: delivery_order_batch_id
					},
					success: function(response) {
						if (response) {
							if (response == 1) {
								console.log('success');
								$("#loadingview").hide();
								initDataTablePalletDetail(pallet_id);
							} else {
								if (response == 0) {
									var msg = 'Tidak Ada Barang Terima Barang Gagal Kirim';
								} else {
									var msg = response;
								}
								var msgtype = 'error';

								// if (!window.__cfRLUnblockHandlers) return false;
								Swal.fire({
									icon: msgtype,
									title: 'Error',
									text: msg
								});

								$("#loadingview").hide();
								initDataTablePalletDetail(pallet_id);

								// console.log(msg);
							}
						}
					}
				});

			}
		}
	);

	$("#btnpenerimaanperpalletdoretur").click(
		function() {
			var delivery_order_batch_id = $("#delivery_order_batch_id").val();
			$("#loadingview").show();

			$.ajax({
				async: false,
				type: 'POST',
				url: "<?= base_url('WMS/SuratTugasPengiriman/InsertPalletTemp') ?>",
				data: {
					delivery_order_batch_id: delivery_order_batch_id
				},
				dataType: "JSON",
				success: function(data) {
					// arr_pallet_id.push(data.pallet_id);
					initDataPallet();
					// console.log(arr_pallet_id);
				}
			});
		}
	);

	function ChGetPalletTable(JSONChannel) {
		$("#tablepalletdoretur > tbody").html('');

		var Channel = JSON.parse(JSONChannel);
		var no = 1;

		if (Channel.Pallet != 0) {
			if ($.fn.DataTable.isDataTable('#tablepalletdoretur')) {
				$('#tablepalletdoretur').DataTable().destroy();
			}

			$('#tablepalletdoretur tbody').empty();

			$.each(Channel.Pallet, function(i, v) {
				$("#tablepalletdoretur > tbody").append(`
							<tr id="row-${i}">
								<td style="display: none">
									<input type="hidden" id="item-${i}-penerimaanpenjualandetail2-pallet_id" value="${v.pallet_id}"/>
								</td>
								<td class="text-center">${v.pallet_kode}</td>
								<td class="text-center">${v.pallet_jenis_nama}</td>
								<td class="text-center"><button type="button" title="tambah sku" class="btn btn-primary" id="btn-detail-pallet-do-retur" style="border:none;background:transparent" onClick="ViewPalletDoRetur('${v.pallet_id}')"><i class="fa fa-plus text-primary"></i></button><button type="button" class="btn btn-danger" title="hapus pallet" style="border:none;background:transparent" onclick="DeletePallet('${v.pallet_id}')"><i class="fa fa-trash text-danger"></i></button></td>
							</tr>
					`);
			});

			// for (i = 0; i < Channel.Pallet.length; i++) {
			// 	var pallet_id = Channel.Pallet[i].pallet_id;
			// 	var pallet_jenis_id = Channel.Pallet[i].pallet_jenis_id;
			// 	var depo_id = Channel.Pallet[i].depo_id;
			// 	var rak_lajur_detail_id = Channel.Pallet[i].rak_lajur_detail_id;
			// 	var pallet_kode = Channel.Pallet[i].pallet_kode;
			// 	var pallet_tanggal_create = Channel.Pallet[i].pallet_tanggal_create;
			// 	var pallet_who_create = Channel.Pallet[i].pallet_who_create;
			// 	var pallet_is_aktif = Channel.Pallet[i].pallet_is_aktif;

			// 	var strmenu = '';

			// 	strmenu = strmenu + '<tr>';
			// 	strmenu = strmenu + '	<td><input type="text" class="form-control" value="auto" readonly></td>';
			// 	strmenu = strmenu + '	<td><select class="form-control select-jenis-pallet" id="jenis_pallet_do_retur_' + i + '" onChange="UpdatePallet(\'' + pallet_id + '\', this.value)"></select></td>';
			// 	strmenu = strmenu + '	<td class="text-center"><button type="button" title="tambah sku" class="btn btn-primary" id="btn-detail-pallet-do-retur" style="border:none;background:transparent" onClick="ViewPalletDoRetur(\'' + pallet_id + '\')"><i class="fa fa-plus text-primary"></i></button><button type="button" class="btn btn-danger" title="hapus pallet" style="border:none;background:transparent" onclick="DeletePallet(\'' + pallet_id + '\')"><i class="fa fa-trash text-danger"></i></button></td>';
			// 	strmenu = strmenu + '</tr>';

			// 	no++;

			// 	$("#tablepalletdoretur > tbody").append(strmenu);

			// }

			// for (i = 0; i < Channel.Pallet.length; i++) {
			// 	var pallet_jenis_id = Channel.Pallet[i].pallet_jenis_id;

			// 	$("#jenis_pallet_do_retur_" + i).html('');

			// 	$("#jenis_pallet_do_retur_" + i).append('<option value="">** Select **</option>');
			// 	if (Channel.JenisPallet != 0) {
			// 		for (x = 0; x < Channel.JenisPallet.length; x++) {
			// 			slc_pallet_jenis_id = Channel.JenisPallet[x].pallet_jenis_id;
			// 			slc_pallet_jenis_nama = Channel.JenisPallet[x].pallet_jenis_nama;

			// 			if (pallet_jenis_id == slc_pallet_jenis_id) {
			// 				$("#jenis_pallet_do_retur_" + i).append('<option value="' + slc_pallet_jenis_id + '" selected>' + slc_pallet_jenis_nama + '</option>');
			// 			} else {
			// 				$("#jenis_pallet_do_retur_" + i).append('<option value="' + slc_pallet_jenis_id + '">' + slc_pallet_jenis_nama + '</option>');
			// 			}
			// 		}
			// 	}

			// 	$("#jenis_pallet_do_retur_" + i).select2();
			// }

			// $('#tablepalletdoretur').DataTable({
			// 	"lengthMenu": [
			// 		[5],
			// 		[5]
			// 	],
			// 	"ordering": false,
			// 	"searching": false,
			// 	"bInfo": false
			// });
		}

	}

	function ChGetPalletDetailTable(JSONChannel) {
		$("#tabledetailpalletdoretur > tbody").html('');

		var Channel = JSON.parse(JSONChannel);
		var no = 1;
		var depo_detail_id = "";
		var penerimaan_tipe = $("#fdjr_type").val();

		if (penerimaan_tipe == "retur") {
			depo_detail_id = $("#filter_gudang_penerima_do_retur").val();
		} else if (penerimaan_tipe == "terkirim sebagian") {
			depo_detail_id = $("#filter_gudang_penerima_terkirim_sebagian").val();
		} else if (penerimaan_tipe == "tidak terkirim") {
			depo_detail_id = $("#filter_gudang_penerima_gagal_kirim").val();
		} else if (penerimaan_tipe == "titipan") {
			depo_detail_id = $("#filter_gudang_penerima_barang_titipan").val();
		}

		if (Channel.Pallet != 0) {
			if ($.fn.DataTable.isDataTable('#tabledetailpalletdoretur')) {
				$('#tabledetailpalletdoretur').DataTable().destroy();
			}

			$('#tabledetailpalletdoretur tbody').empty();

			for (i = 0; i < Channel.PalletDetail.length; i++) {
				var delivery_order_batch_id = Channel.PalletDetail[i].delivery_order_batch_id;
				var pallet_id = Channel.PalletDetail[i].pallet_id;
				var pallet_detail_id = Channel.PalletDetail[i].pallet_detail_id;
				var principle = Channel.PalletDetail[i].principle;
				var brand = Channel.PalletDetail[i].brand;
				var sku_id = Channel.PalletDetail[i].sku_id;
				var sku_kode = Channel.PalletDetail[i].sku_kode;
				var sku_induk_id = Channel.PalletDetail[i].sku_induk_id;
				var sku_nama_produk = Channel.PalletDetail[i].sku_nama_produk;
				var sku_kemasan = Channel.PalletDetail[i].sku_kemasan;
				var sku_satuan = Channel.PalletDetail[i].sku_satuan;
				var sku_stock_id = Channel.PalletDetail[i].sku_stock_id;
				var sku_stock_expired_date = Channel.PalletDetail[i].sku_stock_expired_date;
				var sku_stock_qty = Channel.PalletDetail[i].sku_stock_qty;
				var penerimaan_tipe_nama = Channel.PalletDetail[i].penerimaan_tipe_nama;

				var strmenu = '';

				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td>' + principle + '</td>';
				strmenu = strmenu + '	<td>' + sku_kode + '</td>';
				strmenu = strmenu + '	<td style="width:20%;">' + sku_nama_produk + '</td>';
				strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
				strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
				// strmenu = strmenu + '	<td style="width:20%;"><select class="form-control" id="slcskuexpireddate' + i + '" onchange="UpdateSkuExpDatePallet(\'' + pallet_detail_id + '\',this.value)"></select></td>';
				// strmenu = strmenu + '	<td style="width:20%;"><input type="date" class="form-control" id="slcskuexpireddate' + i + '" value="' + sku_stock_expired_date + '" onchange="UpdateSkuExpDatePallet(\'' + pallet_detail_id + '\',\'' + sku_id + '\',this.value)"></td>';
				if (penerimaan_tipe_nama == "titipan") {
					strmenu = strmenu +
						'	<td style="width:20%;"><input type="date" class="form-control" id="slcskuexpireddate' + i +
						'" value="' + sku_stock_expired_date + '" onchange="UpdateSkuExpDatePallet(\'' + pallet_detail_id +
						'\',\'' + sku_id + '\',this.value,\'' + depo_detail_id + '\')"></td>';
				} else {
					strmenu = strmenu + '	<td style="width:20%;"><select class="form-control" id="slcskuexpireddate' + i +
						'" onchange="UpdateSkuExpDatePallet(\'' + pallet_detail_id + '\',\'' + sku_id + '\',this.value,\'' +
						depo_detail_id + '\')"></select></td>';
				}
				strmenu = strmenu +
					'	<td style="width:15%;"><input type="text" style="width:100%;" class="form-control" id="jumlah_barang' +
					i + '" value="' + sku_stock_qty + '" onchange="UpdateQtySKUPallet(\'' + delivery_order_batch_id +
					'\',\'' + pallet_detail_id + '\',\'' + sku_id + '\',this.value,\'' + penerimaan_tipe_nama + '\', ' + i +
					')"></td>';
				strmenu = strmenu + '	<td>' + penerimaan_tipe_nama + '</td>';
				strmenu = strmenu +
					'	<td><button type="button" class="btn btn-sm btn-danger" onclick="DeletePalletDetail(\'' + pallet_id +
					'\',\'' + pallet_detail_id + '\')"><i class="fa fa-trash"></i></button></td>';
				strmenu = strmenu + '</tr>';

				no++;

				$("#tabledetailpalletdoretur > tbody").append(strmenu);

				if (i == (Channel.PalletDetail.length - 1)) {
					for (j = 0; j < Channel.PalletDetail.length; j++) {
						var pallet_id_j = Channel.PalletDetail[j].pallet_id;
						var pallet_detail_id_j = Channel.PalletDetail[j].pallet_detail_id;
						var sku_id_j = Channel.PalletDetail[j].sku_id;
						var sku_stock_id_j = Channel.PalletDetail[j].sku_stock_id;

						$.ajax({
							async: false,
							type: 'POST',
							url: "<?= base_url('WMS/SuratTugasPengiriman/GetSKUExpiredDate') ?>",
							data: "sku_id=" + sku_id_j,
							dataType: "json",
							success: function(response) {
								if (response) {
									if (response.ExpiredDate != 0) {
										$("#slcskuexpireddate" + j).append(
											'<option value="">** Pilih SKU Exp **</option>');
										for (x = 0; x < response.ExpiredDate.length; x++) {
											sku_stock_id_slc = response.ExpiredDate[x].sku_stock_id;
											sku_id_slc = response.ExpiredDate[x].sku_id;
											sku_stock_expired_date_slc = response.ExpiredDate[x]
												.sku_stock_expired_date;

											// if (sku_stock_id_slc == sku_stock_id_j) {
											// 	$("#slcskuexpireddate" + j).append('<option value="' + sku_stock_id_slc + ' || ' + sku_stock_expired_date_slc + '" selected>' + sku_stock_expired_date_slc + '</option>');
											// } else {
											// 	$("#slcskuexpireddate" + j).append('<option value="' + sku_stock_id_slc + ' || ' + sku_stock_expired_date_slc + '">' + sku_stock_expired_date_slc + '</option>');
											// }

											if (sku_stock_id_slc == sku_stock_id_j) {
												$("#slcskuexpireddate" + j).append('<option value="' +
													sku_stock_expired_date_slc + '" selected>' +
													sku_stock_expired_date_slc + '</option>');
												UpdateSkuExpDatePallet(pallet_detail_id_j, sku_id_j,
													sku_stock_expired_date_slc, depo_detail_id);
											} else {
												$("#slcskuexpireddate" + j).append('<option value="' +
													sku_stock_expired_date_slc + '">' +
													sku_stock_expired_date_slc + '</option>');
											}
										}
									}
								}
							}
						});
					}
				}

			}

			// $('#tabledetailpalletdoretur').DataTable({
			// 	"lengthMenu": [
			// 		[5],
			// 		[5]
			// 	],
			// 	"ordering": false,
			// 	"searching": false,
			// 	"scrollX": false
			// });
		} else {
			$('#tabledetailpalletdoretur').DataTable().clear().draw();
			// ResetForm();
		}

	}

	function GetPalletDetailTable(JSONChannel) {
		$("#tabledetailpalletdoretur > tbody").html('');
		// $("#btn-tambah-sku-fdjr").hide();
		$("#btn-view-sku-fdjr").hide();

		var Channel = JSON.parse(JSONChannel);
		var no = 1;

		if (Channel.Pallet != 0) {
			if ($.fn.DataTable.isDataTable('#tabledetailpalletdoretur')) {
				$('#tabledetailpalletdoretur').DataTable().destroy();
			}

			$('#tabledetailpalletdoretur tbody').empty();

			for (i = 0; i < Channel.PalletDetail.length; i++) {
				var pallet_id = Channel.PalletDetail[i].pallet_id;
				var pallet_detail_id = Channel.PalletDetail[i].pallet_detail_id;
				var principle = Channel.PalletDetail[i].principle;
				var brand = Channel.PalletDetail[i].brand;
				var sku_id = Channel.PalletDetail[i].sku_id;
				var sku_kode = Channel.PalletDetail[i].sku_kode;
				var sku_induk_id = Channel.PalletDetail[i].sku_induk_id;
				var sku_nama_produk = Channel.PalletDetail[i].sku_nama_produk;
				var sku_kemasan = Channel.PalletDetail[i].sku_kemasan;
				var sku_satuan = Channel.PalletDetail[i].sku_satuan;
				var sku_stock_id = Channel.PalletDetail[i].sku_stock_id;
				var sku_stock_expired_date = Channel.PalletDetail[i].sku_stock_expired_date;
				var sku_stock_qty = Channel.PalletDetail[i].sku_stock_qty;
				var penerimaan_tipe_nama = Channel.PalletDetail[i].penerimaan_tipe_nama;

				var strmenu = '';

				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td>' + principle + '</td>';
				strmenu = strmenu + '	<td>' + sku_kode + '</td>';
				strmenu = strmenu + '	<td style="width:20%;">' + sku_nama_produk + '</td>';
				strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
				strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
				strmenu = strmenu + '	<td style="width:20%;">' + sku_stock_expired_date + '</td>';
				strmenu = strmenu + '	<td style="width:15%;">' + sku_stock_qty + '</td>';
				strmenu = strmenu + '	<td style="width:15%;">' + penerimaan_tipe_nama + '</td>';
				strmenu = strmenu +
					'	<td><button type="button" class="btn btn-sm btn-danger" onclick="DeletePalletDetail(\'' + pallet_id +
					'\',\'' + pallet_detail_id + '\')"><i class="fa fa-trash"></i></button></td>';
				strmenu = strmenu + '</tr>';

				no++;

				$("#tabledetailpalletdoretur > tbody").append(strmenu);

			}

			// $('#tabledetailpalletdoretur').DataTable({
			// 	"lengthMenu": [
			// 		[5],
			// 		[5]
			// 	],
			// 	"ordering": false,
			// 	"searching": false,
			// 	"scrollX": false
			// });
		} else {
			$('#tabledetailpalletdoretur').DataTable().clear().draw();
			// ResetForm();
		}

	}

	function ViewPalletDoRetur(pallet_id) {
		$("#viewdetailpalletdoretur").show();
		// $("#viewmodaldetailpallet").modal('show');
		$('#pallet_id').val(pallet_id);

		initDataTablePalletDetail(pallet_id);
		initDataTableSKU();
	}

	function ViewPalletDetail(pallet_id) {
		$("#viewdetailpalletdoretur").show();
		// $("#viewmodaldetailpallet").modal('show');
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/SuratTugasPengiriman/GetPalletDetailTable') ?>",
			data: {
				pallet_id: pallet_id
			},
			success: function(response) {
				if (response) {
					GetPalletDetailTable(response);
				}
			}
		});
	}

	function UpdatePallet(pallet_id, pallet_jenis_id) {
		$.ajax({
			async: false,
			type: 'POST',
			url: "<?= base_url('WMS/SuratTugasPengiriman/UpdatePalletTemp') ?>",
			data: {
				pallet_id: pallet_id,
				pallet_jenis_id: pallet_jenis_id
			},
			success: function(response) {
				if (response) {
					if (response == 1) {
						console.log('success');
					} else {
						if (response == 0) {
							var msg = 'Data Gagal Disimpan';
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

						// console.log(msg);
					}
				}
			}
		});
	}

	function UpdateQtySKUPallet(delivery_order_batch_id, pallet_detail_id, sku_id, sku_stock_qty, penerimaan_tipe_nama, i) {
		$.ajax({
			async: false,
			type: 'POST',
			url: "<?= base_url('WMS/SuratTugasPengiriman/UpdateQtySKUPalletTemp') ?>",
			data: {
				delivery_order_batch_id: delivery_order_batch_id,
				pallet_detail_id: pallet_detail_id,
				sku_id: sku_id,
				sku_stock_qty: sku_stock_qty,
				penerimaan_tipe_nama: penerimaan_tipe_nama
			},
			success: function(response) {
				if (response) {
					if (response == 1) {
						console.log('success');
					} else {
						if (response == 0) {
							var msg = 'Data Gagal Disimpan';
						} else if (response == 2) {

							$("#jumlah_barang" + i).val(0);

							var msg = 'Qty Pallet Melebihi DO';
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

						// console.log(msg);
					}
				}
			}
		});
	}

	// function UpdateSkuExpDatePallet(pallet_detail_id, sku_stock_id) {
	// 	var arr_sku_stock_id = sku_stock_id.split(" || ");

	// 	$.ajax({
	// 		async: false,
	// 		type: 'POST',
	// 		url: "<?= base_url('SuratTugasPengiriman/UpdateSkuExpDatePalletTemp') ?>",
	// 		data: {
	// 			pallet_detail_id: pallet_detail_id,
	// 			sku_stock_id: arr_sku_stock_id[0],
	// 			sku_stock_expired_date: arr_sku_stock_id[1]
	// 		},
	// 		success: function(response) {
	// 			if (response) {
	// 				if (response == 1) {
	// 					console.log('success');
	// 				} else {
	// 					if (response == 0) {
	// 						var msg = 'Data Gagal Disimpan';
	// 					} else {
	// 						var msg = response;
	// 					}
	// 					var msgtype = 'error';

	// 					//if (!window.__cfRLUnblockHandlers) return false;
	// 					new PNotify
	// 						({
	// 							title: 'Error',
	// 							text: msg,
	// 							type: msgtype,
	// 							styling: 'bootstrap3',
	// 							delay: 3000,
	// 							stack: stack_center
	// 						});

	// 					// console.log(msg);
	// 				}
	// 			}
	// 		}
	// 	});
	// }

	function UpdateSkuExpDatePallet(pallet_detail_id, sku_id, sku_stock_expired_date, depo_detail_id) {
		// var arr_sku_stock_id = sku_stock_id.split(" || ");

		$.ajax({
			async: false,
			type: 'POST',
			url: "<?= base_url('WMS/SuratTugasPengiriman/UpdateSkuExpDatePalletTemp') ?>",
			data: {
				pallet_detail_id: pallet_detail_id,
				sku_id: sku_id,
				sku_stock_expired_date: sku_stock_expired_date,
				depo_detail_id: depo_detail_id
			},
			success: function(response) {
				if (response) {
					if (response == 1) {
						console.log('success');
					} else {
						if (response == 0) {
							var msg = 'Data Gagal Disimpan';
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

						// console.log(msg);
					}
				}
			}
		});
	}

	function DeletePallet(pallet_id) {
		Swal.fire({
			title: 'Apa anda yakin ingin menghapus data pallet ?',
			text: "",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			cancelButtonText: 'Tidak',
			confirmButtonText: 'Ya'
		}).then((result) => {
			if (result.value == true) {
				$.ajax({
					async: false,
					type: 'POST',
					url: "<?= base_url('WMS/SuratTugasPengiriman/DeletePalletTemp') ?>",
					data: {
						pallet_id: pallet_id
					},
					success: function(response) {
						if (response) {
							if (response == 1) {
								console.log('success');
								initDataTablePallet();
								initDataTablePalletDetail(pallet_id);
								$("#viewdetailpalletdoretur").hide();
							} else {
								if (response == 0) {
									var msg = 'Data Gagal Dihapus';
								} else {
									var msg = response;
								}
								var msgtype = 'error';

								//if (!window.__cfRLUnblockHandlers) return false;
								// new PNotify
								// 	({
								// 		title: 'Error',
								// 		text: msg,
								// 		type: msgtype,
								// 		styling: 'bootstrap3',
								// 		delay: 3000,
								// 		stack: stack_center
								// 	});

								console.log(msg);
							}
						}
					}
				});
			}
		});
	}

	function DeletePalletDetail(pallet_id, pallet_detail_id) {
		$.ajax({
			async: false,
			type: 'POST',
			url: "<?= base_url('WMS/SuratTugasPengiriman/DeletePalletDetailTemp') ?>",
			data: {
				pallet_detail_id: pallet_detail_id
			},
			success: function(response) {
				if (response) {
					if (response == 1) {
						console.log('success');
						initDataTablePalletDetail(pallet_id);
					} else {
						if (response == 0) {
							var msg = 'Data Gagal Dihapus';
						} else {
							var msg = response;
						}
						var msgtype = 'error';

						//if (!window.__cfRLUnblockHandlers) return false;
						// new PNotify
						// 	({
						// 		title: 'Error',
						// 		text: msg,
						// 		type: msgtype,
						// 		styling: 'bootstrap3',
						// 		delay: 3000,
						// 		stack: stack_center
						// 	});

						console.log(msg);
					}
				}
			}
		});
	}

	function DeletePalletDoRetur(rowid) {
		var row = document.getElementById(rowid);
		var table = row.parentNode;
		while (table && table.tagName != 'TABLE')
			table = table.parentNode;
		if (!table)
			return;
		table.deleteRow(row.rowIndex);
	}

	$("#btnpenerimaanperterkirimsebagian").click(
		function() {
			$('#viewpalletterkirimsebagian').show();

		}
	);


	$("#btnpenerimaanperpalletgagalkirim").click(
		function() {
			$('#viewpalletgagalkirim').show();

		}
	);

	$("#btnpenerimaanperpalletbarangtitipan").click(
		function() {
			$('#viewpalletbarangtitipan').show();

		}
	);

	$("#btnback").click(
		function() {
			$('#previewformdoterkirimsebagian').modal('hide');
		}
	);

	$("#btnback").click(
		function() {
			$('#previewformdoterkirimsebagian').modal('hide');
		}
	);

	$("#btn-search-sku").click(function() {
		initDataTableSKU();
	});

	$("#btn-choose-sku-multi").click(function() {
		var pallet_id = $("#pallet_id").val();
		var jumlah = $('input[name="CheckboxSKU"]').length;
		var numberOfChecked = $('input[name="CheckboxSKU"]:checked').length;

		for (var i = 0; i < jumlah; i++) {
			if (numberOfChecked > 0) {
				var checked = $('[id="chk_sku_' + i + '"]:checked').length;
				var arr_sku_id = $("#chk_sku_" + i).val();

				const sku_id = arr_sku_id.split(" || ");

				if (checked > 0) {
					$.ajax({
						async: false,
						type: 'POST',
						url: "<?= base_url('WMS/SuratTugasPengiriman/InsertPalletDetailTemp') ?>",
						data: {
							pallet_id: pallet_id,
							sku_id: sku_id[0],
							penerimaan_tipe_id: 'A9F3967E-94ED-4761-B385-4770FEEC229A'
						},
						success: function(response) {
							if (response) {
								if (response == 1) {
									console.log('success');
									initDataTablePalletDetail(pallet_id);
								} else {
									if (response == 0) {
										var msg = 'Data Gagal Disimpan';
									} else if (response == 2) {
										var msg = 'SKU ' + sku_id[1] + ' Sudah Ada di Pallet';
									} else {
										var msg = response;
									}
									var msgtype = 'error';

									// if (!window.__cfRLUnblockHandlers) return false;
									new PNotify
										({
											title: 'Error',
											text: msg,
											type: msgtype,
											styling: 'bootstrap3',
											delay: 3000,
											stack: stack_center
										});

									initDataTablePalletDetail(pallet_id);

									// console.log(msg);
								}
							}
						}
					});
				}
			} else {
				Swal.fire({
					icon: 'error',
					title: 'Error',
					text: 'Pilih SKU!'
				});
			}
		}
	});

	function initDataTablePallet() {
		var delivery_order_batch_id = $("#delivery_order_batch_id").val();

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/SuratTugasPengiriman/GetPallet') ?>",
			data: {
				delivery_order_batch_id: delivery_order_batch_id
			},
			success: function(response) {
				if (response) {
					ChGetPalletTable(response);
				}
			}
		});
	}

	function initDataTablePalletDetail(pallet_id) {
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/SuratTugasPengiriman/GetPalletDetail') ?>",
			data: {
				pallet_id: pallet_id
			},
			success: function(response) {
				if (response) {
					ChGetPalletDetailTable(response);
				}
			}
		});
	}

	function initDataTableSKU() {
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/SuratTugasPengiriman/GetSKU') ?>",
			data: {
				sku_induk: $("#filter-sku-induk").val(),
				sku: $("#filter-sku-nama-produk").val(),
				principle: $("#filter_principle").val(),
				brand: $("#filter-brand").val()
			},
			success: function(response) {
				if (response) {
					ChDataTableSKU(response);
				}
			}
		});
	}

	function ChDataTableSKU(JSONChannel) {
		$("#data-table-sku > tbody").html('');

		var Channel = JSON.parse(JSONChannel);
		var no = 1;

		if (Channel.sku != 0) {
			if ($.fn.DataTable.isDataTable('#data-table-sku')) {
				$('#data-table-sku').DataTable().destroy();
			}

			$('#data-table-sku tbody').empty();

			for (i = 0; i < Channel.sku.length; i++) {
				var principle = Channel.sku[i].principle;
				var brand = Channel.sku[i].brand;
				var sku_id = Channel.sku[i].sku_id;
				var sku_kode = Channel.sku[i].sku_kode;
				var sku_induk_nama = Channel.sku[i].sku_induk_nama;
				var sku_nama_produk = Channel.sku[i].sku_nama_produk;
				var sku_kemasan = Channel.sku[i].sku_kemasan;
				var sku_satuan = Channel.sku[i].sku_satuan;

				var strmenu = '';

				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td><input type="checkbox" name="CheckboxSKU" id="chk_sku_' + i + '" value="' +
					sku_id + ' || ' + sku_nama_produk + '"></td>';
				strmenu = strmenu + '	<td><input type="hidden" id="principle' + i + '" value="' + principle + '">' +
					principle + '</td>';
				strmenu = strmenu + '	<td><input type="hidden" id="brand' + i + '" value="' + brand + '">' + brand +
					'</td>';
				strmenu = strmenu + '	<td><input type="hidden" id="sku_induk_nama' + i + '" value="' + sku_induk_nama +
					'">' + sku_induk_nama + '</td>';
				strmenu = strmenu + '	<td><input type="hidden" id="sku_kode' + i + '" value="' + sku_kode + '">' +
					sku_kode + '</td>';
				strmenu = strmenu + '	<td style="width:20%;"><input type="hidden" id="sku_nama_produk' + i + '" value="' +
					sku_nama_produk + '">' + sku_nama_produk + '</td>';
				strmenu = strmenu + '	<td><input type="hidden" id="sku_kemasan' + i + '" value="' + sku_kemasan + '">' +
					sku_kemasan + '</td>';
				strmenu = strmenu + '	<td><input type="hidden" id="sku_satuan' + i + '" value="' + sku_satuan + '">' +
					sku_satuan + '</td>';
				strmenu = strmenu + '</tr>';

				no++;

				$("#data-table-sku > tbody").append(strmenu);
			}

			// $('#data-table-sku').DataTable({
			// 	"lengthMenu": [
			// 		[-1],
			// 		["All"]
			// 	],
			// 	"ordering": false,
			// 	"searching": false
			// });
		} else {
			$('#data-table-sku').DataTable().clear().draw();
			// ResetForm();
		}

	}

	function ChangeTypeFDJR(type) {
		$("#fdjr_type").val(type);

		arr_list_penerimaan_tipe.push(type);
	}

	function ResetForm() {
		<?php
		if ($Menu_Access["U"] == 1) {
		?>
			$("#loadingview").hide();
			$("#btnsaveterkirimsebagian").prop("disabled", false);
		<?php
		}
		?>

		<?php
		if ($Menu_Access["C"] == 1) {
		?>
			$("#loadingview").hide();
			$("#btnsaveterkirimsebagian").prop("disabled", false);
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

		if ($(".data-table-sku").length > 0) {
			initDataTableSKU();
		}

	});

	$(document).on("click", ".btn-choose-sku-multi", function() {
		// console.log(table.column(0).checkboxes.selected());
		var selectedIds = skuDeliverytable.column(0).checkboxes.selected();
		var stringOfIds = "";
		$.each(selectedIds, function(index, value) {
			stringOfIds += value + ',';
		});
		$.post('<?= site_url('DeliveryOrderDraft/ajaxrequest') ?>', {
			'mode': 'getSkuByIds',
			'id': stringOfIds
		}, function(response) {
			$.each(response, function(index, value) {
				addRow(value);
			});
		}, 'json');
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

	$(document).on("click", "#input_manual", function() {
		$("#input_manual").hide();
		$("#close_input").show();
		$("#preview_input_manual").show();
		$("#start_scan").attr("disabled", true);
	});

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
				url: "<?= base_url('WMS/MutasiPallet/check_kode_pallet'); ?>",
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
							initDataPallet();
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
	});

	$(document).on("click", "#stop_scan", function() {
		html5QrCode2.stop();
		$("#modal_scan").modal('hide');
	});

	$(document).on("click", "#check_kode", () => {
		let barcode = $("#kode_barcode_notone").val();

		if (barcode == "") {
			message("Error!", "Kode Pallet tidak boleh kosong", "error");
			return false;
		} else {
			$.ajax({
				url: "<?= base_url('WMS/SuratTugasPengiriman/check_kode_pallet'); ?>",
				type: "POST",
				data: {
					kode_pallet: barcode
				},
				dataType: "JSON",
				beforeSend: () => {
					$("#loading_cek_manual").show();
				},
				success: function(data) {
					$("#txtpreviewscan").val(data.kode);
					if (data.type == 200) {
						message("Success!", data.message, "success");
						$("#kode_barcode_notone").val("");
						// arr_pallet_id.push(data.pallet_id);
						// console.log(arr_pallet_id);

						$.ajax({
							async: false,
							type: 'POST',
							url: "<?= base_url('WMS/SuratTugasPengiriman/InsertPalletTemp2') ?>",
							data: {
								delivery_order_batch_id: $("#delivery_order_batch_id").val(),
								pallet_id: data.pallet_id
							},
							dataType: "JSON",
							success: function(data) {

							}
						});

						$('#tablepalletdoretur').fadeOut("slow", function() {
							$(this).hide();

						}).fadeIn("slow", function() {
							$(this).show();
							initDataPallet();
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

	function initDataPallet() {

		// var result = arr_pallet_id.reduce((unique, o) => {
		// 	if (!unique.some(obj => obj === o)) {
		// 		unique.push(o);
		// 	}
		// 	return unique;
		// }, []);

		// arr_pallet_id = result;

		// console.log(arr_pallet_id);

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/SuratTugasPengiriman/get_pallet_by_arr_id') ?>",
			data: {
				delivery_order_batch_id: $("#delivery_order_batch_id").val()
				// pallet_id: arr_pallet_id
			},
			dataType: "JSON",
			success: function(response) {
				$('#tablepalletdoretur > tbody').empty();
				$.each(response, function(i, v) {
					$("#tablepalletdoretur > tbody").append(`
							<tr id="row-${i}">
								<td style="display: none">
									<input type="hidden" id="item-${i}-penerimaanpenjualandetail2-pallet_id" value="${v.pallet_id}"/>
								</td>
								<td class="text-center">${v.pallet_kode}</td>
								<td class="text-center">${v.pallet_jenis_nama}</td>
								<td class="text-center"><button type="button" title="tambah sku" class="btn btn-primary" id="btn-detail-pallet-do-retur" style="border:none;background:transparent" onClick="ViewPalletDoRetur('${v.pallet_id}')"><i class="fa fa-plus text-primary"></i></button><button type="button" class="btn btn-danger" title="hapus pallet" style="border:none;background:transparent" onclick="DeletePallet('${v.pallet_id}')"><i class="fa fa-trash text-danger"></i></button></td>
							</tr>
					`);
				});
			}
		});
	}

	function GetPrincipleByPerusahaan(perusahaan) {

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/SuratTugasPengiriman/GetPrincipleByPerusahaan') ?>",
			data: {
				perusahaan: perusahaan
			},
			dataType: "JSON",
			success: function(response) {

				$("#filter_principle").html('');

				$("#filter_principle").append(
					'<option value=""><span name="CAPTION-PILIH">** Pilih **</span></option>');

				$.each(response, function(i, v) {
					$("#filter_principle").append(
						`<option value="${v.principle_id}">${v.principle_kode}</option>`);
				});
			}
		});
	}

	function GetCheckerByPrinciple(principle) {

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/SuratTugasPengiriman/GetCheckerByPrinciple') ?>",
			data: {
				perusahaan: $("#filter_perusahaan").val(),
				principle: principle
			},
			dataType: "JSON",
			success: function(response) {

				$("#filter_checker").html('');

				$("#filter_checker").append(
					'<option value=""><span name="CAPTION-PILIH">** Pilih **</span></option>');

				$.each(response, function(i, v) {
					$("#filter_checker").append(
						`<option value="${v.karyawan_id}">${v.karyawan_nama}</option>`);
				});
			}
		});
	}

	$("#filter_principle").on('change', function() {
		GetBTBDetailByPrinciple();
	});

	const handlerScanInputManual = (event, value, type) => {
		// const idx = type == 'notone' ? null : event.currentTarget.getAttribute('data-idx');
		if (value != "") {
			fetch('<?= base_url('WMS/PenerimaanRetur/getKodeAutoComplete?params='); ?>' + value + `&type=${type}`)
				.then(response => response.json())
				.then((results) => {
					if (!results[0]) {
						$(`#table-fixed-${type}`).css('display', 'none')
					} else {
						let data = "";
						// console.log(results);
						results.forEach(function(e, i) {
							data += `
									<tr onclick="getNoSuratJalanEks('${e.kode}', '${type}')" style="cursor:pointer">
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


	function getNoSuratJalanEks(data, type) {
		$(`#kode_barcode_${type}`).val(data);
		$(`#table-fixed-${type}`).css('display', 'none')
		if (type == 'notone') {
			$("#check_kode").click()
		}
		// if (type == 'one') {
		// 	var depo_detail_id = $("#penerimaanpenjualan-depo_detail_id").val();

		// 	$("#pallet-rak_lajur_detail_id_" + idx).html('');
		// 	$.ajax({
		// 		async: false,
		// 		url: "<?= base_url('WMS/PenerimaanRetur/check_rak_lajur_detail'); ?>",
		// 		type: "POST",
		// 		data: {
		// 			depo_detail_id: depo_detail_id,
		// 			pallet_id: $("#item-" + idx + "-penerimaanpenjualandetail2-pallet_id").val(),
		// 			kode: data
		// 		},
		// 		dataType: "JSON",
		// 		success: function(response) {
		// 			$("#txtpreviewscan2").val(response.kode);
		// 			$("#pallet-rak_lajur_detail_id_" + idx).append(response.kode);

		// 			if (response.type == 200) {
		// 				Swal.fire("Success!", response.message, "success").then(function(result) {
		// 					if (result.value == true) {
		// 						$("#manual_input_rak").modal('hide');
		// 						$("#kode_barcode_notone").val('')
		// 					}
		// 				});
		// 				$('#tablepallet').fadeOut("slow", function() {
		// 					$(this).hide();
		// 				}).fadeIn("slow", function() {
		// 					$(this).show();
		// 					initresponsePallet();
		// 				});
		// 			} else if (response.type == 201) {
		// 				message("Error!", response.message, "error");
		// 			} else {
		// 				message("Info!", response.message, "info");
		// 			}
		// 		},
		// 	});
		// }
	}
</script>