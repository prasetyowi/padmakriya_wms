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
			url: "<?= base_url('WMS/PersetujuanPembongkaranBarang/GetKonversiSKUByFilter') ?>",
			data: {
				tgl: $("#filter-konversi-date").val(),
				perusahaan: $("#filter-perusahaan").val(),
				customer: $("#filter-customer").val(),
				status: $("#filter-status").val(),
				tipe: $("#filter-konversi-type").val(),
				principle: $("#filter-principle").val()
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
						if (v.tr_konversi_sku_status == "Approved") {
							$("#table_list_data_konversi > tbody").append(`
							<tr>
								<td class="text-center">${v.tr_konversi_sku_tanggal}</td>
								<td class="text-center">${v.tr_konversi_sku_kode}</td>
								<td class="text-center">${v.client_wms_nama}</td>
								<td class="text-center">${v.principle_kode}</td>
								<td class="text-center">${v.tipe_konversi_nama}</td>
								<td class="text-center">${v.tr_konversi_sku_status}</td>
								<td class="text-center"><a href="<?= base_url() ?>WMS/PersetujuanPembongkaranBarang/detail/?id=${v.tr_konversi_sku_id}" class="btn btn-success btn-small btn-delete-sku"><i class="fa fa-search"></i></a></td>
							</tr>
						`);
						} else if (v.tr_konversi_sku_status == "Rejected") {
							$("#table_list_data_konversi > tbody").append(`
							<tr>
								<td class="text-center">${v.tr_konversi_sku_tanggal}</td>
								<td class="text-center">${v.tr_konversi_sku_kode}</td>
								<td class="text-center">${v.client_wms_nama}</td>
								<td class="text-center">${v.principle_kode}</td>
								<td class="text-center">${v.tipe_konversi_nama}</td>
								<td class="text-center">${v.tr_konversi_sku_status}</td>
								<td class="text-center"><a href="<?= base_url() ?>WMS/PersetujuanPembongkaranBarang/detail/?id=${v.tr_konversi_sku_id}" class="btn btn-danger btn-small btn-delete-sku"><i class="fa fa-search"></i></a></td>
							</tr>
						`);
						} else if (v.tr_konversi_sku_status == "In Progress Approval") {
							$("#table_list_data_konversi > tbody").append(`
							<tr>
								<td class="text-center">${v.tr_konversi_sku_tanggal}</td>
								<td class="text-center">${v.tr_konversi_sku_kode}</td>
								<td class="text-center">${v.client_wms_nama}</td>
								<td class="text-center">${v.principle_kode}</td>
								<td class="text-center">${v.tipe_konversi_nama}</td>
								<td class="text-center">${v.tr_konversi_sku_status}</td>
								<td class="text-center"><a href="<?= base_url() ?>WMS/PersetujuanPembongkaranBarang/detail/?id=${v.tr_konversi_sku_id}" class="btn btn-danger btn-small btn-delete-sku"><i class="fa fa-search"></i></a></td>
							</tr>
						`);
						} else if (v.tr_konversi_sku_status == "Completed") {
							$("#table_list_data_konversi > tbody").append(`
							<tr>
								<td class="text-center">${v.tr_konversi_sku_tanggal}</td>
								<td class="text-center">${v.tr_konversi_sku_kode}</td>
								<td class="text-center">${v.client_wms_nama}</td>
								<td class="text-center">${v.principle_kode}</td>
								<td class="text-center">${v.tipe_konversi_nama}</td>
								<td class="text-center">${v.tr_konversi_sku_status}</td>
								<td class="text-center"><a href="<?= base_url() ?>WMS/PersetujuanPembongkaranBarang/detail/?id=${v.tr_konversi_sku_id}" class="btn btn-danger btn-small btn-delete-sku"><i class="fa fa-search"></i></a></td>
							</tr>
						`);
						} else if (v.tr_konversi_sku_status == "In Progress") {
							$("#table_list_data_konversi > tbody").append(`
							<tr>
								<td class="text-center">${v.tr_konversi_sku_tanggal}</td>
								<td class="text-center">${v.tr_konversi_sku_kode}</td>
								<td class="text-center">${v.client_wms_nama}</td>
								<td class="text-center">${v.principle_kode}</td>
								<td class="text-center">${v.tipe_konversi_nama}</td>
								<td class="text-center">${v.tr_konversi_sku_status}</td>
								<td class="text-center"><a href="<?= base_url() ?>WMS/PersetujuanPembongkaranBarang/detail/?id=${v.tr_konversi_sku_id}" class="btn btn-danger btn-small btn-delete-sku"><i class="fa fa-search"></i></a></td>
							</tr>
						`);
						} else {
							$("#table_list_data_konversi > tbody").append(`
							<tr>
								<td class="text-center">${v.tr_konversi_sku_tanggal}</td>
								<td class="text-center">${v.tr_konversi_sku_kode}</td>
								<td class="text-center">${v.client_wms_nama}</td>
								<td class="text-center">${v.principle_kode}</td>
								<td class="text-center">${v.tipe_konversi_nama}</td>
								<td class="text-center">${v.tr_konversi_sku_status}</td>
								<td class="text-center"><a href="<?= base_url() ?>WMS/PersetujuanPembongkaranBarang/edit/?id=${v.tr_konversi_sku_id}" class="btn btn-primary btn-small btn-delete-sku"><i class="fa fa-pencil"></i></a></td>
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

	function GetSKUKonversi(sku_stock_id, index, sku, kemasan, satuan, tr_konversi_sku_detail_id) {
		var sku_qty = $("#item-" + index + "-persetujuanpembongkarandetail-tr_konversi_sku_detail_qty_plan").val();
		var total_nilai = 0;

		$("#filter_konversi_sku").val('');
		$("#filter_konversi_kemasan").val('');
		$("#filter_konversi_satuan").val('');
		$("#filter_konversi_qty_plan").val('');
		$("#filter_konversi_qty_sisa").val('');
		$("#filter_konversi_sku_stock_id").val('');

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/PersetujuanPembongkaranBarang/GetSKUKonversiBySKU2') ?>",
			data: {
				tr_konversi_sku_detail_id: tr_konversi_sku_detail_id
			},
			dataType: "JSON",
			success: function(response) {
				$("#table-konversi > tbody").empty();

				if (response != 0) {
					$("#modal-konversi").modal('show');

					$.each(response, function(i, v) {
						$("#table-konversi > tbody").append(`
							<tr>
								<td width="30%" style="text-align: center; vertical-align: middle;">${v.sku_satuan}</td>
								<td width="30%" style="text-align: center; vertical-align: middle;">
									<input readonly type="number" id="item-${i}-konversiskudetail2-tr_konversi_sku_detail2_qty" class="form-control input-sm" value="${v.tr_konversi_sku_detail2_qty}" onchange="GetHasilKonversi(${i},'${v.sku_konversi_faktor}','${v.sku_konversi_faktor_param}','${v.konversi_operator}')" />
								</td>
								<td width="30%" style="text-align: center; vertical-align: middle;"><span id="item-${i}-konversiskudetail2-tr_konversi_sku_detail2_qty_result">${v.tr_konversi_sku_detail2_qty_result}</span></td>
							</tr>
						`);
						total_nilai += v.tr_konversi_sku_detail2_qty;
					});

					$("#filter_konversi_sku").val(sku);
					$("#filter_konversi_kemasan").val(kemasan);
					$("#filter_konversi_satuan").val(satuan);
					$("#filter_konversi_qty_plan").val(sku_qty);
					$("#filter_konversi_qty_sisa").val(sku_qty - total_nilai);
					$("#filter_konversi_sku_stock_id").val(sku_stock_id);
				}
			}
		});
	}
</script>