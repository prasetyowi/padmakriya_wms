<script>
	function message_custom(titleType, iconType, htmlType) {
		Swal.fire({
			title: titleType,
			icon: iconType,
			html: htmlType,
		});
	}

	$(document).ready(
		function() {
			if ($('#filter_tanggal').length > 0) {
				$('#filter_tanggal').daterangepicker({
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

			$(".select2").select2();
			$("#page2").hide();
			$('#table_detail').DataTable({
				responsive: true
			});
			$('#table_detail2').DataTable({
				responsive: true
			});
			// GetLaporanStockRekapData();
		}
	);

	function GetTipe() {
		let tipe = $("#filter_tipe").val();

		if (tipe == "tahun") {
			$("#span_tahun").show();
			$("#span_bulan").hide();
			$("#span_tanggal").hide();
		} else if (tipe == "bulan") {
			$("#span_tahun").show();
			$("#span_bulan").show();
			$("#span_tanggal").hide();
		} else if (tipe == "tanggal") {
			$("#span_tahun").hide();
			$("#span_bulan").hide();
			$("#span_tanggal").show();
		}
	}

	function GetPrincipleByPerusahaan() {

		if ($("#filter_perusahaan").val() == "") {
			var alert = GetLanguageByKode('CAPTION-ALERT-PERUSAHAANTIDAKDIPILIH');
			message_custom("Error", "error", alert);
			return false;
		}

		$.ajax({
			type: 'GET',
			url: "<?= base_url('WMS/OperationalExcellence/PenerimaanBarangSupplier/AnalisisPerformaBongkarMuat/GetPrincipleByPerusahaan') ?>",
			data: {
				perusahaan: $("#filter_perusahaan").val()
			},
			dataType: "JSON",
			success: function(response) {

				$("#filter_principle").html('');

				$("#filter_principle").append(`<option value=""><label name="CAPTION-PILIH">Pilih</label></option>`);

				if (response.length != 0) {

					$.each(response, function(i, v) {
						$("#filter_principle").append(`<option value="${v.principle_id}">${v.principle_kode}</option>`);
					});
				}
			}
		});
	}

	function Get_analisis_performa_bongkar_muat() {

		if ($("#filter_perusahaan").val() == "") {
			var alert = GetLanguageByKode('CAPTION-ALERT-PERUSAHAANTIDAKDIPILIH');
			message_custom("Error", "error", alert);
			return false;
		}

		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/OperationalExcellence/PenerimaanBarangSupplier/AnalisisPerformaBongkarMuat/Get_analisis_performa_bongkar_muat') ?>",
			data: {
				tanggal: $("#filter_tanggal").val(),
				client_wms_id: $("#filter_perusahaan").val(),
				principle_id: $("#filter_principle").val()
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

				$('#table_report_analisis_performa_bongkar_muat > tbody').html('');
				$('#table_report_analisis_performa_bongkar_muat > tbody').empty('');

				if (response.length != 0) {

					$('#table_report_analisis_performa_bongkar_muat').fadeOut("slow", function() {
						$(this).hide();


					}).fadeIn("slow", function() {
						$(this).show();

						if (response.length != 0) {

							$.each(response, function(i, v) {
								$("#table_report_analisis_performa_bongkar_muat > tbody").append(`
									<tr>
										<td style="vertical-align: middle;text-align:left;">${i+1}</td>
										<td style="vertical-align: middle;text-align:left;">${v.karyawan_nama}</td>
										<td style="vertical-align: middle;text-align:left;">${v.client_wms_nama}</td>
										<td style="vertical-align: middle;text-align:left;">${v.principle_kode}</td>
										<td style="vertical-align: middle;text-align:left;">${v.penerimaan_pembelian_tgl}</td>
										<td style="vertical-align: middle;text-align:left;">${v.penerimaan_pembelian_kode}</td>
										<td style="vertical-align: middle;text-align:right;"><button class="btn btn-link" onclick="Get_detail_analisis_performa_bongkar_muat_by_id('${v.penerimaan_pembelian_id}')">${v.sku_jumlah_barang_terima_comp_ctn}</button></td>
										<td style="vertical-align: middle;text-align:right;">${v.waktu_pengerjaan}</td>
										<td style="vertical-align: middle;text-align:right;"></td>
									</tr>
								`);
							});
						}
					});
				}
				// console.log(response);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				message("Error", "Error 500 Internal Server Connection Failure", "error");
			},
			complete: function() {
				Swal.close();
			}
		});

	}

	function Get_detail_analisis_performa_bongkar_muat_by_id(penerimaan_pembelian_id) {


		$("#modal_analisis_performa_bongkar_muat_detail").modal('show');

		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/OperationalExcellence/PenerimaanBarangSupplier/AnalisisPerformaBongkarMuat/Get_detail_analisis_performa_bongkar_muat_by_id') ?>",
			data: {
				penerimaan_pembelian_id: penerimaan_pembelian_id
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

				if ($.fn.DataTable.isDataTable('#table_report_analisis_performa_bongkar_muat_detail')) {
					$('#table_report_analisis_performa_bongkar_muat_detail').DataTable().clear();
					$('#table_report_analisis_performa_bongkar_muat_detail').DataTable().destroy();
				}

				$('#table_report_analisis_performa_bongkar_muat_detail > tbody').html('');
				$('#table_report_analisis_performa_bongkar_muat_detail > tbody').empty('');

				if (response.length != 0) {

					$('#table_report_analisis_performa_bongkar_muat_detail').fadeOut("slow", function() {
						$(this).hide();

					}).fadeIn("slow", function() {
						$(this).show();

						if (response.length != 0) {

							$.each(response, function(i, v) {
								$("#table_report_analisis_performa_bongkar_muat_detail > tbody").append(`
									<tr>
										<td style="vertical-align: middle;text-align:left;">${i+1}</td>
										<td style="vertical-align: middle;text-align:left;">${v.karyawan_nama}</td>
										<td style="vertical-align: middle;text-align:left;">${v.client_wms_nama}</td>
										<td style="vertical-align: middle;text-align:left;">${v.penerimaan_pembelian_kode}</td>
										<td style="vertical-align: middle;text-align:left;">${v.penerimaan_pembelian_tgl}</td>
										<td style="vertical-align: middle;text-align:left;">${v.principle_kode}</td>
										<td style="vertical-align: middle;text-align:left;">${v.sku_konversi_group}</td>
										<td style="vertical-align: middle;text-align:left;">${v.sku_nama_produk}</td>
										<td style="vertical-align: middle;text-align:left;">${v.sku_composite}</td>
										<td style="vertical-align: middle;text-align:left;">${v.sku_exp_date}</td>
										<td style="vertical-align: middle;text-align:right;">${v.sku_composite_qty}</td>
									</tr>
								`);
							});



							$("#table_report_analisis_performa_bongkar_muat_detail").DataTable({
								paging: false,
								ordering: false,
								info: false,
								searching: false,
								dom: "Blfrtip",
								buttons: [{
										extend: "print",
										text: '<i class="fa fa-print"></i> Print',
										className: 'btn btn-warning',
										customize: function(win) {

											var last = null;
											var current = null;
											var bod = [];

											var css = '@page { size: landscape; }',
												head = win.document.head || win.document.getElementsByTagName('head')[0],
												style = win.document.createElement('style');

											style.type = 'text/css';
											style.media = 'print';

											if (style.styleSheet) {
												style.styleSheet.cssText = css;
											} else {
												style.appendChild(win.document.createTextNode(css));
											}

											head.appendChild(style);
										}
									},
									{
										extend: 'excelHtml5',
										text: '<i class="fa fa-file-excel-o"></i> Excel',
										className: 'btn btn-success',
									},
									{
										extend: 'csvHtml5',
										text: '<i class="fa fa-file"></i> CSV',
										className: 'btn btn-info',
									},
									{
										extend: 'pdfHtml5',
										orientation: 'landscape',
										text: '<i class="fa fa-file-pdf-o"></i> PDF',
										className: 'btn btn-danger',
										pageSize: 'A4'
									}
								],
								lengthMenu: [
									[-1],
									['All'],
								],
							});
						}
					});
				}
				// console.log(response);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				message("Error", "Error 500 Internal Server Connection Failure", "error");
			},
			complete: function() {
				Swal.close();
			}
		});

	}
</script>