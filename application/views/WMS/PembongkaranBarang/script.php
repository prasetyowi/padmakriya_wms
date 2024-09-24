<script type="text/javascript">
	var jumlah_sku = 0;
	var layanan = "";
	let arr_sku = [];
	let arr_header = [];
	let arr_detail = [];
	var cek_qty = 0;
	let cek_tipe_stock = 0;
	loadingBeforeReadyPage()
	$(document).ready(
		function() {
			$('.select2').select2();
			if ($('#filter-konversi-date').length > 0) {
				$('#filter-konversi-date').daterangepicker({
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
		}
	);

	function message_custom(titleType, iconType, htmlType) {
		Swal.fire({
			title: titleType,
			icon: iconType,
			html: htmlType,
		})
	}

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

	$("#btn-search-data-konversi").on("click", function() {
		$("#loadingviewkonversi").show();
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/PembongkaranBarang/GetKonversiSKUByFilter') ?>",
			data: {
				tgl: $("#filter-konversi-date").val(),
				perusahaan: $("#filter-perusahaan").val(),
				customer: $("#filter-customer").val(),
				status: $("#filter-status").val(),
				tipe: $("#filter-konversi-type").val()
			},
			dataType: "JSON",
			success: function(response) {

				$("#table_list_data_konversi > tbody").empty();

				if ($.fn.DataTable.isDataTable('#table_list_data_konversi')) {
					$('#table_list_data_konversi').DataTable().clear();
					$('#table_list_data_konversi').DataTable().destroy();
				}

				if (response != 0) {
					$.each(response, function(i, v) {
						if (v.tr_konversi_sku_status == "Completed") {
							$("#table_list_data_konversi > tbody").append(`
								<tr>
									<td class="text-center">${v.tr_konversi_sku_tanggal}</td>
									<td class="text-center">${v.tr_konversi_sku_kode}</td>
									<td class="text-center">${v.client_wms_nama}</td>
									<td class="text-center">${v.tipe_konversi_nama}</td>
									<td class="text-center">${v.tr_konversi_sku_status}</td>
									<td class="text-center">
                                        <a href="<?= base_url() ?>WMS/PembongkaranBarang/detail/?id=${v.tr_konversi_sku_id}" class="btn btn-success btn-small btn-delete-sku"><i class="fa fa-search"></i></a>
                                        <a href="<?= base_url() ?>WMS/PembongkaranBarang/cetak/?id=${v.tr_konversi_sku_id}" class="btn btn-danger btn-small" target="_blank"><i class="fas fa-print"></i></a>
                                    </td>
								</tr>
							`);
						} else {
							$("#table_list_data_konversi > tbody").append(`
								<tr>
									<td class="text-center">${v.tr_konversi_sku_tanggal}</td>
									<td class="text-center">${v.tr_konversi_sku_kode}</td>
									<td class="text-center">${v.client_wms_nama}</td>
									<td class="text-center">${v.tipe_konversi_nama}</td>
									<td class="text-center">${v.tr_konversi_sku_status}</td>
									<td class="text-center"><a href="<?= base_url() ?>WMS/PembongkaranBarang/edit/?id=${v.tr_konversi_sku_id}" class="btn btn-primary btn-small btn-delete-sku"><i class="fa fa-pencil"></i></a></td>
								</tr>
							`);
						}
					});
					$('#table_list_data_konversi').DataTable({
						'ordering': false
					});
				} else {
					$("#table_list_data_konversi > tbody").append(`
						<tr>
							<td colspan="6" class="text-danger text-center">Data Kosong</td>
						</tr>
					`);
				}

				$("#loadingviewkonversi").hide();
			}
		});
	});

	function GetSKUKonversi(index) {
		// GetSKUKonversi(tr_konversi_sku_detail_id, sku_id, sku_stock_expired_date, index, sku_kode, sku, kemasan, satuan, qty_plan, qty_result)

		var tr_konversi_sku_detail_id = $("#item-" + index + "-persetujuanpembongkarandetail-tr_konversi_sku_detail_id")
			.val();
		var sku_id = $("#item-" + index + "-persetujuanpembongkarandetail-sku_id").val();
		var sku_stock_expired_date = $("#item-" + index + "-persetujuanpembongkarandetail-sku_stock_expired_date").val();
		var sku_kode = $("#item-" + index + "-persetujuanpembongkarandetail-sku_kode").val();
		var sku = $("#item-" + index + "-persetujuanpembongkarandetail-sku_nama_produk").val();
		var kemasan = $("#item-" + index + "-persetujuanpembongkarandetail-sku_kemasan").val();
		var satuan = $("#item-" + index + "-persetujuanpembongkarandetail-sku_satuan").val();
		var qty_plan = $("#item-" + index + "-persetujuanpembongkarandetail-tr_konversi_sku_detail_qty_plan").val();
		var qty_result = $("#item-" + index + "-persetujuanpembongkarandetail-tr_konversi_sku_detail2_qty_result").val();
		var sku_stock_id = $("#item-" + index + "-persetujuanpembongkarandetail-sku_stock_id").val();

		$("#pembongkarandetail2-index").val('');
		$("#pembongkarandetail2-tr_konversi_sku_detail_id").val('');
		$("#pembongkarandetail2-sku_kode").val('');
		$("#pembongkarandetail2-sku_nama_produk").val('');
		$("#pembongkarandetail2-sku_stock_expired_date").val('');
		$("#pembongkarandetail2-sku_satuan").val('');
		$("#pembongkarandetail2-sku_kemasan").val('');
		$("#pembongkarandetail2-tr_konversi_sku_detail_qty_plan").val('');
		$("#pembongkarandetail2-tr_konversi_sku_detail_qty_result").val('');
		$("#pembongkarandetail2-tr_konversi_sku_detail_qty_sisa").val('');
		$("#pembongkarandetail2-sku_stock_id").val('');

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/PembongkaranBarang/GetKonversiDetailPallet2') ?>",
			data: {
				tr_konversi_sku_detail_id: tr_konversi_sku_detail_id
			},
			dataType: "JSON",
			success: function(response) {
				$("#table-konversi-detail > tbody").empty();

				if (response != 0) {
					$("#modal-konversi").modal('show');

					$.each(response, function(i, v) {
						$("#table-konversi-detail > tbody").append(`
							<tr>
								<td style="text-align: center; vertical-align: middle;">
									<input disabled type="checkbox" name="CheckboxKonversi" id="check_konversi_${i}" value="1" checked>
									<input type="hidden" id="pallet_id_${i}" value="${v.pallet_id}">
								</td>
								<td style="text-align: center; vertical-align: middle;">${v.depo_detail_nama}</td>
								<td style="text-align: center; vertical-align: middle;">${v.rak_lajur_nama}</td>
								<td style="text-align: center; vertical-align: middle;">${v.rak_lajur_detail_nama}</td>
								<td style="text-align: center; vertical-align: middle;">${v.pallet_kode}</td>
								<td style="text-align: center; vertical-align: middle;">
									<span id="item-${i}-konversiskudetail-sku_stock_ambil">${v.tr_konversi_sku_detail2_qty}</span>
								</td>
								<td style="text-align: center; vertical-align: middle;"><button type="button" class="btn btn-primary btn-sm" id="btn_form_konversi_${i}" onclick="GetFormKonversiSKUDetail2('${v.tr_konversi_sku_detail2_id}','${i}')"><span name="CAPTION-KONVERSI">Konversi</span></button></td>
							</tr>
						`);
					});

					$("#pembongkarandetail2-index").val(index);
					$("#pembongkarandetail2-tr_konversi_sku_detail_id").val(tr_konversi_sku_detail_id);
					$("#pembongkarandetail2-sku_kode").val(sku_kode);
					$("#pembongkarandetail2-sku_nama_produk").val(sku);
					$("#pembongkarandetail2-sku_stock_expired_date").val(sku_stock_expired_date);
					$("#pembongkarandetail2-sku_satuan").val(satuan);
					$("#pembongkarandetail2-sku_kemasan").val(kemasan);
					$("#pembongkarandetail2-tr_konversi_sku_detail_qty_plan").val(qty_plan);
					$("#pembongkarandetail2-tr_konversi_sku_detail_qty_result").val(qty_result);
					$("#pembongkarandetail2-tr_konversi_sku_detail_qty_sisa").val(qty_plan);
					$("#pembongkarandetail2-sku_stock_id").val(sku_stock_id);
				} else {
					$("#pembongkarandetail2-index").val('');
					$("#pembongkarandetail2-tr_konversi_sku_detail_id").val('');
					$("#pembongkarandetail2-sku_kode").val('');
					$("#pembongkarandetail2-sku_nama_produk").val('');
					$("#pembongkarandetail2-sku_stock_expired_date").val('');
					$("#pembongkarandetail2-sku_satuan").val('');
					$("#pembongkarandetail2-sku_kemasan").val('');
					$("#pembongkarandetail2-tr_konversi_sku_detail_qty_plan").val('');
					$("#pembongkarandetail2-tr_konversi_sku_detail_qty_result").val('');
					$("#pembongkarandetail2-sku_stock_id").val('');
					$("#modal-konversi").modal('hide');
				}
			}
		});

	}

	function GetFormKonversiSKUDetail2(tr_konversi_sku_detail2_id, index) {
		var pallet_id = $("#pallet_id_" + index).val();
		var tipe_konversi = $("#pembongkaran-tipe_konversi_id").text();
		var total_nilai = 0;

		$("#filter_pallet_id").val('');
		$("#filter_konversi_sku").val('');
		$("#filter_konversi_kemasan").val('');
		$("#filter_konversi_satuan").val('');
		$("#filter_index").val('');
		$("#filter_konversi_qty_plan").val('');
		$("#filter_konversi_qty_sisa").val('');

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/PembongkaranBarang/GetViewkonversiskudetail2') ?>",
			data: {
				tr_konversi_sku_detail2_id: tr_konversi_sku_detail2_id
			},
			dataType: "JSON",
			success: function(response) {
				$("#table-konversi-by-sku > tbody").empty();

				if (response != 0) {
					$("#modal-konversi").modal('hide');
					$("#modal-konversi-detail").modal('show');

					$.each(response.detail, function(i, v) {
						$("#table-konversi-by-sku > tbody").append(`
							<tr>
								<td style="text-align: center; vertical-align: middle;">
									<input type="checkbox" name="CheckboxKonversi" id="check_konversi_detail3_${i}" value="1" checked disabled>
								</td>
								<td width="20%" style="text-align: center; vertical-align: middle;">${v.sku_satuan}</td>
								<td width="20%" style="text-align: center; vertical-align: middle;">${v.tr_konversi_sku_detail2_qty}</td>
								<td width="20%" style="text-align: center; vertical-align: middle;">${v.tr_konversi_sku_detail2_qty_result}</td>
								<td width="30%" style="text-align: center; vertical-align: middle;">${v.pallet_kode_tujuan}</td>
							</tr>
						`);
					});

					$.each(response.header, function(i, v) {
						$("#filter_pallet_id").val(pallet_id);
						$("#filter_konversi_sku").val(v.sku_nama_produk);
						$("#filter_konversi_kemasan").val(v.sku_kemasan);
						$("#filter_konversi_satuan").val(v.sku_satuan);
						$("#filter_index").val(index);
						$("#filter_konversi_qty_plan").val(v.tr_konversi_sku_detail_qty_plan);
						$("#filter_konversi_qty_sisa").val(v.tr_konversi_sku_detail_qty_plan - v
							.tr_konversi_sku_detail2_qty);
					});

				} else {
					$("#filter_pallet_id").val('');
					$("#filter_konversi_sku").val('');
					$("#filter_konversi_kemasan").val('');
					$("#filter_konversi_satuan").val('');
					$("#filter_index").val('');
					$("#filter_konversi_qty_plan").val('');
					$("#filter_konversi_qty_sisa").val('');
					$("#modal-konversi").modal('show');
					$("#modal-konversi-detail").modal('hide');
				}
			}
		});
	}

	$("#btn_close_konversi_detail").on("click", function() {
		$("#modal-konversi").modal('show');
		$("#modal-konversi-detail").modal('hide');
	});
	//a
</script>