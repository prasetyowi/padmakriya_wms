<script type="text/javascript">
	var ChannelCode = '';
	var arr_list_sku_stock = [];
	var last_num = 0;
	var cek_error = 0;

	function message_custom(titleType, iconType, htmlType) {
		Swal.fire({
			title: titleType,
			icon: iconType,
			html: htmlType,
		});
	}

	$(document).ready(function() {
		$(".select2").select2();
		Get_list_komparasi_lokasi_stock_pallet();
	});

	$("#btn_search_komparasi_lokasi_stock_pallet").click(function() {
		Get_list_komparasi_lokasi_stock_pallet();
	});

	function Get_list_komparasi_lokasi_stock_pallet() {

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/Maintenance/KomparasiLokasiStockPalletGlobal/Get_list_komparasi_lokasi_stock_pallet') ?>",
			data: {
				depo_detail_id: $("#filter_depo_detail_id").val(),
				pallet_kode: $("#filter_pallet_kode").val()
			},
			beforeSend: function() {
				Swal.fire({
					title: 'Loading ...',
					html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
					timerProgressBar: false,
					showConfirmButton: false
				});
			},
			dataType: "JSON",
			success: function(response) {

				$('#table_list_komparasi_lokasi_stock_pallet > tbody').empty('');

				if ($.fn.DataTable.isDataTable('#table_list_komparasi_lokasi_stock_pallet')) {
					$('#table_list_komparasi_lokasi_stock_pallet').DataTable().clear();
					$('#table_list_komparasi_lokasi_stock_pallet').DataTable().destroy();
				}

				if (response.length != 0) {
					$.each(response, function(i, v) {
						$("#table_list_komparasi_lokasi_stock_pallet > tbody").append(`
							<tr>
								<td class="text-center">${i+1}</td>
								<td class="text-left">${v.pallet_kode}</td>
								<td class="text-left">${v.rak_lajur_detail_nama}</td>
								<td class="text-left">${v.depodetailpallet}</td>
								<td class="text-left">${v.depodetailsummary}</td>
								<td class="text-left">${v.flag}</td>
								<td class="text-center">
									<button type="button" id="btn_edit_komparasi_lokasi_stock_pallet" class="btn btn-sm btn-warning" onclick="EditListKomparasiStockPallet('${v.pallet_id}')"><i class="fa fa-pencil"></i></button>
								</td>
							</tr>
						`);
					});

					$("#table_list_komparasi_lokasi_stock_pallet").DataTable({
						lengthMenu: [
							[50, 100, 200, -1],
							[50, 100, 200, 'All'],
						],
					});
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				message("Error", "Error 500 Internal Server Connection Failure", "error");
			},
			complete: function() {
				Swal.close();
			}
		});
	}

	function UpdateListSKUStock(index, input) {

		let total = 0;
		total = parseInt($("#item-" + index + "-KomparasiLokasiStockPalletGlobalDetail-sku_stock_qty").val()) - parseInt($("#item-" + index + "-KomparasiLokasiStockPalletGlobalDetail-sku_stock_ambil").val()) + parseInt($("#item-" + index + "-KomparasiLokasiStockPalletGlobalDetail-sku_stock_in").val()) - parseInt($("#item-" + index + "-KomparasiLokasiStockPalletGlobalDetail-sku_stock_out").val()) + parseInt($("#item-" + index + "-KomparasiLokasiStockPalletGlobalDetail-sku_stock_terima").val());

		$("#item-" + index + "-KomparasiLokasiStockPalletGlobalDetail-qty").val(total);

		arr_list_sku_stock[index] = ({
			'pallet_detail_id': $("#item-" + index + "-KomparasiLokasiStockPalletGlobalDetail-pallet_detail_id").val(),
			'pallet_id': $("#item-" + index + "-KomparasiLokasiStockPalletGlobalDetail-pallet_id").val(),
			'pallet_kode': $("#item-" + index + "-KomparasiLokasiStockPalletGlobalDetail-pallet_kode").val(),
			'pallet_id': $("#item-" + index + "-KomparasiLokasiStockPalletGlobalDetail-pallet_id").val(),
			'sku_stock_qty': $("#item-" + index + "-KomparasiLokasiStockPalletGlobalDetail-sku_stock_qty").val(),
			'sku_stock_ambil': $("#item-" + index + "-KomparasiLokasiStockPalletGlobalDetail-sku_stock_ambil").val(),
			'sku_stock_in': $("#item-" + index + "-KomparasiLokasiStockPalletGlobalDetail-sku_stock_in").val(),
			'sku_stock_out': $("#item-" + index + "-KomparasiLokasiStockPalletGlobalDetail-sku_stock_out").val(),
			'sku_stock_terima': $("#item-" + index + "-KomparasiLokasiStockPalletGlobalDetail-sku_stock_terima").val(),
			'qty': total
		});


	}

	function DeleteListSKUStock(tipe, index) {
		var arr_list_sku_stock_temp = [];

		arr_list_sku_stock[index] = "";
		arr_list_sku_stock_temp = arr_list_sku_stock;

		arr_list_sku_stock = [];

		$.each(arr_list_sku_stock_temp, function(i, v) {
			if (v != "") {
				arr_list_sku_stock.push(v);
			}
		});

		// console.log(arr_list_sku_stock);

		GetListSKUStock(tipe);
	}

	function GetListSKUStock() {

		$('#table_detail_komparasi_lokasi_stock_pallet_edit > tbody').empty('');

		if ($.fn.DataTable.isDataTable('#table_detail_komparasi_lokasi_stock_pallet_edit')) {
			$('#table_detail_komparasi_lokasi_stock_pallet_edit').DataTable().clear();
			$('#table_detail_komparasi_lokasi_stock_pallet_edit').DataTable().destroy();
		}

		if (arr_list_sku_stock.length != 0) {
			$.each(arr_list_sku_stock, function(i, v) {

				$("#table_detail_komparasi_lokasi_stock_pallet_edit > tbody").append(`
					<tr>
						<td class="text-left">
							${i+1}
							<input type="hidden" id="item-${i}-KomparasiLokasiStockPalletGlobalDetail-pallet_detail_id" class="form-control" autocomplete="off" value="${v.pallet_detail_id}">
							<input type="hidden" id="item-${i}-KomparasiLokasiStockPalletGlobalDetail-pallet_id" class="form-control" autocomplete="off" value="${v.pallet_id}">
							<input type="hidden" id="item-${i}-KomparasiLokasiStockPalletGlobalDetail-pallet_id" class="form-control" autocomplete="off" value="${v.pallet_id}">
						</td>
						<td class="text-left" style="width:25%;">
							<span id="item-${i}-KomparasiLokasiStockPalletGlobalDetail-pallet_kode">${v.pallet_kode}</span>
						</td>
						<td class="text-left" style="width:12%;">
							<input type="text" id="item-${i}-KomparasiLokasiStockPalletGlobalDetail-sku_stock_qty" class="form-control text-right" autocomplete="off" value="${v.sku_stock_qty}" onchange="UpdateListSKUStock('${i}',this)">
						</td>
						<td class="text-left" style="width:12%;">
							<input type="text" id="item-${i}-KomparasiLokasiStockPalletGlobalDetail-sku_stock_ambil" class="form-control text-right" autocomplete="off" value="${v.sku_stock_ambil}" onchange="UpdateListSKUStock('${i}',this)">
						</td>
						<td class="text-left" style="width:12%;">
							<input type="text" id="item-${i}-KomparasiLokasiStockPalletGlobalDetail-sku_stock_in" class="form-control text-right" autocomplete="off" value="${v.sku_stock_in}" onchange="UpdateListSKUStock('${i}',this)">
						</td>
						<td class="text-left" style="width:12%;">
							<input type="text" id="item-${i}-KomparasiLokasiStockPalletGlobalDetail-sku_stock_out" class="form-control text-right" autocomplete="off" value="${v.sku_stock_out}" onchange="UpdateListSKUStock('${i}',this)">
						</td>
						<td class="text-left" style="width:12%;">
							<input type="text" id="item-${i}-KomparasiLokasiStockPalletGlobalDetail-sku_stock_terima" class="form-control text-right" autocomplete="off" value="${v.sku_stock_terima}" onchange="UpdateListSKUStock('${i}',this)">
						</td>
						<td class="text-left" style="width:12%;">
							<input type="text" id="item-${i}-KomparasiLokasiStockPalletGlobalDetail-qty" class="form-control text-right" autocomplete="off" value="${v.qty}" disabled />
						</td>
					</tr>
				`);
			});

			$("#table_detail_komparasi_lokasi_stock_pallet_edit").DataTable({
				lengthMenu: [
					[50, 100, 200, -1],
					[50, 100, 200, 'All'],
				],
			});
		}
	}

	function EditListKomparasiStockPallet(pallet_id) {
		$("#modal_edit_komparasi_lokasi_stock_pallet").modal('show');

		arr_list_sku_stock = [];

		$.ajax({
			type: 'GET',
			url: "<?= base_url('WMS/Maintenance/KomparasiLokasiStockPalletGlobal/Get_komparasi_lokasi_stock_pallet_by_id') ?>",
			data: {
				pallet_id: pallet_id
			},
			beforeSend: function() {
				Swal.fire({
					title: 'Loading ...',
					html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
					timerProgressBar: false,
					showConfirmButton: false
				});
			},
			dataType: "JSON",
			success: function(response) {

				if (response.length != 0) {

					$.each(response, function(i, v) {

						$("#KomparasiLokasiStockPalletGlobal-pallet_id").val(v.pallet_id);
						$("#KomparasiLokasiStockPalletGlobal-pallet_kode").val(v.pallet_kode);
						$("#KomparasiLokasiStockPalletGlobal-depo_detail_id_pallet").val(v.iddepodetailpallet);
						$("#KomparasiLokasiStockPalletGlobal-depo_detail_id_summary").val(v.iddepodetailsummary);
						$("#KomparasiLokasiStockPalletGlobal-depo_detail_pallet").val(v.depodetailpallet);
						$("#KomparasiLokasiStockPalletGlobal-depo_detail_summary").val(v.depodetailsummary);
						$("#KomparasiLokasiStockPalletGlobal-hasil").val(v.flag);

						$('#KomparasiLokasiStockPalletGlobal-rak_lajur_detail').html('');

						$.ajax({
							type: 'GET',
							url: "<?= base_url('WMS/Maintenance/KomparasiLokasiStockPalletGlobal/Get_rak_lajur_detail_by_depo_detail_id') ?>",
							data: {
								depo_detail_id: v.iddepodetailsummary
							},
							dataType: "JSON",
							success: function(response) {

								if (response.length != 0) {

									$('#KomparasiLokasiStockPalletGlobal-rak_lajur_detail').append(`<option value=""><span name="CAPTION-PILIH">** Pilih **</span></option>`);

									$.each(response, function(i2, v2) {
										$('#KomparasiLokasiStockPalletGlobal-rak_lajur_detail').append(`<option value="${v2.rak_lajur_detail_id}" ${v2.rak_lajur_detail_id==v.rak_lajur_detail_id ? 'selected':'' }>${v2.rak_lajur_detail_nama}</option>`);
									});
								}
							},
							error: function(xhr, ajaxOptions, thrownError) {
								message("Error", "Error 500 Internal Server Connection Failure", "error");
							}
						});
					});
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				message("Error", "Error 500 Internal Server Connection Failure", "error");
			},
			complete: function() {
				Swal.close();
			}
		});

		setTimeout(() => {
			GetListSKUStock();
		}, 500);
	}

	$("#btn_update_pallet").on("click", function() {
		let total_rupiah_detail = 0;
		cek_error = 0;

		if ($("#KomparasiLokasiStockPalletGlobal-rak_lajur_detail").val() == "") {

			let alert = "Rak Lajur Detail Tidak Boleh Kosong";
			message_custom("Error", "error", alert);

			return false;
		}

		setTimeout(() => {

			Swal.fire({
				title: GetLanguageByKode('CAPTION-APAANDAYAKIN'),
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: GetLanguageByKode('CAPTION-LANJUT'),
				cancelButtonText: GetLanguageByKode('CAPTION-CLOSE')
			}).then((result) => {
				$.ajax({
					type: 'POST',
					url: "<?= base_url('WMS/Maintenance/KomparasiLokasiStockPalletGlobal/update_pallet') ?>",
					dataType: "JSON",
					data: {
						pallet_id: $("#KomparasiLokasiStockPalletGlobal-pallet_id").val(),
						rak_lajur_detail_id: $("#KomparasiLokasiStockPalletGlobal-rak_lajur_detail").val()
					},
					beforeSend: function() {
						Swal.fire({
							title: 'Loading ...',
							html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
							timerProgressBar: false,
							showConfirmButton: false
						});

						$("#btn_update_pallet").prop("disabled", true);
					},
					success: function(response) {
						if (response == 1) {
							let alert_tes = GetLanguageByKode("CAPTION-ALERT-DATABERHASILDISIMPAN");
							message_custom("Success", "success", alert_tes);

							setTimeout(() => {
								Get_list_komparasi_lokasi_stock_pallet();
								$("#modal_edit_komparasi_lokasi_stock_pallet").modal('hide');
							}, 500);
						} else {
							let alert_tes = GetLanguageByKode("CAPTION-ALERT-DATAGAGALDISIMPAN");
							message_custom("Error", "error", alert_tes);
						}

						$("#btn_update_pallet").prop("disabled", false);
					},
					error: function(xhr, ajaxOptions, thrownError) {
						message("Error", "Error 500 Internal Server Connection Failure", "error");
						$("#btn_update_pallet").prop("disabled", false);
					},
					complete: function() {
						$("#btn_update_pallet").prop("disabled", false);
						Swal.close();
					}
				});
			});

		}, 500);
	});

	<?php
	if ($Menu_Access["D"] == 1) {
	?>
	<?php
	}
	?>

	<?php
	if ($Menu_Access["U"] == 1) {
	?>

	<?php
	}
	?>

	<?php
	if ($Menu_Access["C"] == 1) {
	?>

	<?php
	}
	?>

	function ResetForm() {

		$('#KomparasiLokasiStockPalletGlobal-trap_id').val('');
		$('#KomparasiLokasiStockPalletGlobal-tanggal').val('<?= date('Y-m-d') ?>');
		$('#KomparasiLokasiStockPalletGlobal-tanggal_jatuh_tempo').val('<?= date('Y-m-d') ?>');
		$('#KomparasiLokasiStockPalletGlobal-jumlah').val(0);
		$('#KomparasiLokasiStockPalletGlobal-keterangan').val('');
		$('#KomparasiLokasiStockPalletGlobal-updtgl').val('');
		$('#KomparasiLokasiStockPalletGlobal-updwho').val('');

		$('#KomparasiLokasiStockPalletGlobal-supplier').val("").trigger('change');

		$('#KomparasiLokasiStockPalletGlobal-trap_id').val('');
		$('#KomparasiLokasiStockPalletGlobal-tanggal').val('');
		$('#KomparasiLokasiStockPalletGlobal-periode').val('');
		$('#KomparasiLokasiStockPalletGlobal-jenis_jurnal').val('');
		$('#KomparasiLokasiStockPalletGlobal-keterangan').val('');
		$('#KomparasiLokasiStockPalletGlobal-updtgl').val('');
		$('#KomparasiLokasiStockPalletGlobal-updwho').val('');
		$('#KomparasiLokasiStockPalletGlobal-msglseg').html('');

		$('#KomparasiLokasiStockPalletGlobal-client_wms_id').val("").trigger('change');

		$('#KomparasiLokasiStockPalletGlobal-trap_id-detail').val('');
		$('#KomparasiLokasiStockPalletGlobal-tanggal-detail').val('');
		$('#KomparasiLokasiStockPalletGlobal-periode-detail').val('');
		$('#KomparasiLokasiStockPalletGlobal-jenis_jurnal-detail').val('');
		$('#KomparasiLokasiStockPalletGlobal-keterangan-detail').val('');
		$('#KomparasiLokasiStockPalletGlobal-updtgl-detail').val('');
		$('#KomparasiLokasiStockPalletGlobal-updwho-detail').val('');
		$('#KomparasiLokasiStockPalletGlobal-msglseg-detail').val('');

		$('#KomparasiLokasiStockPalletGlobal-client_wms_id-detail').val("").trigger('change');

		$('#KomparasiLokasiStockPalletGlobal-trap_id-copy').val('');
		$('#KomparasiLokasiStockPalletGlobal-tanggal-copy').val('');
		$('#KomparasiLokasiStockPalletGlobal-periode-copy').val('');
		$('#KomparasiLokasiStockPalletGlobal-jenis_jurnal-copy').val('');
		$('#KomparasiLokasiStockPalletGlobal-keterangan-copy').val('');
		$('#KomparasiLokasiStockPalletGlobal-updtgl-copy').val('');
		$('#KomparasiLokasiStockPalletGlobal-updwho-copy').val('');
		$('#KomparasiLokasiStockPalletGlobal-msglseg-copy').val('');

		$('#KomparasiLokasiStockPalletGlobal-client_wms_id-copy').val("").trigger('change');

		$("#table_detail_komparasi_lokasi_stock_pallet_tambah > tbody").html('');
		$("#table_detail_komparasi_lokasi_stock_pallet_tambah > tbody").empty();

		$("#table_detail_komparasi_lokasi_stock_pallet_edit > tbody").html('');
		$("#table_detail_komparasi_lokasi_stock_pallet_edit > tbody").empty();

		$("#table_detail_komparasi_lokasi_stock_pallet_detail > tbody").html('');
		$("#table_detail_komparasi_lokasi_stock_pallet_detail > tbody").empty();

		$("#table_detail_komparasi_lokasi_stock_pallet_copy > tbody").html('');
		$("#table_detail_komparasi_lokasi_stock_pallet_copy > tbody").empty();

		$("#total_debet_tambah").html('');
		$("#total_kredit_tambah").html('');

		$("#total_debet_edit").html('');
		$("#total_kredit_edit").html('');

		$("#total_debet_copy").html('');
		$("#total_kredit_copy").html('');

		$("#total_debet_detail").html('');
		$("#total_kredit_detail").html('');

		$("#total_debet").html('');
		$("#total_kredit").html('');
		$("#total_selisih").html('');

		$("#total_debet_edit").html('');
		$("#total_kredit_edit").html('');
		$("#total_selisih_edit").html('');

		$("#total_debet_copy").html('');
		$("#total_kredit_copy").html('');
		$("#total_selisih_copy").html('');

		$("#total_debet_detail").html('');
		$("#total_kredit_detail").html('');
		$("#total_selisih_detail").html('');
	}
</script>