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
			url: "<?= base_url('WMS/OperationalExcellence/AnalisisHasilStockOpname/KomparasiStokFisikDanSistem/GetPrincipleByPerusahaan') ?>",
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

	function Get_komparasi_stok_fisik_dan_sistem() {

		if ($("#filter_perusahaan").val() == "") {
			var alert = GetLanguageByKode('CAPTION-ALERT-PERUSAHAANTIDAKDIPILIH');
			message_custom("Error", "error", alert);
			return false;
		}

		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/OperationalExcellence/AnalisisHasilStockOpname/KomparasiStokFisikDanSistem/Get_komparasi_stok_fisik_dan_sistem') ?>",
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

				$('#table_report_komparasi_stok_fisik_dan_sistem > tbody').html('');
				$('#table_report_komparasi_stok_fisik_dan_sistem > tbody').empty('');

				if (response.length != 0) {

					$('#table_report_komparasi_stok_fisik_dan_sistem').fadeOut("slow", function() {
						$(this).hide();


					}).fadeIn("slow", function() {
						$(this).show();

						if (response.length != 0) {

							$.each(response, function(i, v) {
								if (v.is_selisih == 0) {
									$("#table_report_komparasi_stok_fisik_dan_sistem > tbody").append(`
										<tr style="background-color:#B6FFA1">
											<td style="vertical-align: middle;text-align:left;">${i+1}</td>
											<td style="vertical-align: middle;text-align:left;">${v.client_wms_nama}</td>
											<td style="vertical-align: middle;text-align:left;">${v.principle_kode}</td>
											<td style="vertical-align: middle;text-align:right;"><button class="btn btn-info" onclick="Get_detail_komparasi_stok_fisik_dan_sistem('${v.tr_opname_plan_tanggal}','${v.principle_id}')">${v.sku_actual_qty_opname_comp}</button></td>
											<td style="vertical-align: middle;text-align:right;"><button class="btn btn-info" onclick="Get_detail_komparasi_stok_fisik_dan_sistem('${v.tr_opname_plan_tanggal}','${v.principle_id}')">${v.sku_qty_sistem_comp}</button></td>
											<td style="vertical-align: middle;text-align:left;">Tidak Selisih</td>
										</tr>
									`);
								} else {
									$("#table_report_komparasi_stok_fisik_dan_sistem > tbody").append(`
										<tr style="background-color:#F95454">
											<td style="vertical-align: middle;text-align:left;color:white;">${i+1}</td>
											<td style="vertical-align: middle;text-align:left;color:white;">${v.client_wms_nama}</td>
											<td style="vertical-align: middle;text-align:left;color:white;">${v.principle_kode}</td>
											<td style="vertical-align: middle;text-align:right;color:white;"><button class="btn btn-info" onclick="Get_detail_komparasi_stok_fisik_dan_sistem('${v.tr_opname_plan_tanggal}','${v.principle_id}')">${v.sku_actual_qty_opname_comp}</button></td>
											<td style="vertical-align: middle;text-align:right;color:white;"><button class="btn btn-info" onclick="Get_detail_komparasi_stok_fisik_dan_sistem('${v.tr_opname_plan_tanggal}','${v.principle_id}')">${v.sku_qty_sistem_comp}</button></td>
											<td style="vertical-align: middle;text-align:left;color:white;">Selisih</td>
										</tr>
									`);
								}
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

	function Get_detail_komparasi_stok_fisik_dan_sistem(tanggal, principle_id) {

		$("#modal_komparasi_stok_fisik_dan_sistem_detail").modal('show');

		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/OperationalExcellence/AnalisisHasilStockOpname/KomparasiStokFisikDanSistem/Get_detail_komparasi_stok_fisik_dan_sistem') ?>",
			data: {
				tanggal: tanggal,
				client_wms_id: $("#filter_perusahaan").val(),
				principle_id: principle_id
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

				if ($.fn.DataTable.isDataTable('#table_report_komparasi_stok_fisik_dan_sistem_detail')) {
					$('#table_report_komparasi_stok_fisik_dan_sistem_detail').DataTable().clear();
					$('#table_report_komparasi_stok_fisik_dan_sistem_detail').DataTable().destroy();
				}

				$('#table_report_komparasi_stok_fisik_dan_sistem_detail > tbody').html('');
				$('#table_report_komparasi_stok_fisik_dan_sistem_detail > tbody').empty('');

				if (response.length != 0) {

					$('#table_report_komparasi_stok_fisik_dan_sistem_detail').fadeOut("slow", function() {
						$(this).hide();

					}).fadeIn("slow", function() {
						$(this).show();

						if (response.length != 0) {

							$.each(response, function(i, v) {

								if (v.is_selisih == 0) {
									$("#table_report_komparasi_stok_fisik_dan_sistem_detail > tbody").append(`
										<tr>
											<td style="vertical-align: middle;text-align:left;">${i+1}</td>
											<td style="vertical-align: middle;text-align:left;">${v.tr_opname_plan_kode}</td>
											<td style="vertical-align: middle;text-align:left;">${v.client_wms_nama}</td>
											<td style="vertical-align: middle;text-align:left;">${v.tr_opname_plan_tanggal}</td>
											<td style="vertical-align: middle;text-align:left;">${v.tr_opname_plan_tgl_start}</td>
											<td style="vertical-align: middle;text-align:left;">${v.tr_opname_plan_tgl_end}</td>
											<td style="vertical-align: middle;text-align:left;">${v.karyawan_nama}</td>
											<td style="vertical-align: middle;text-align:left;">${v.tipe_stok}</td>
											<td style="vertical-align: middle;text-align:left;">${v.tipe_opname_nama}</td>
											<td style="vertical-align: middle;text-align:left;">${v.tr_opname_plan_status}</td>
											<td style="vertical-align: middle;text-align:left;">${v.principle_kode}</td>
											<td style="vertical-align: middle;text-align:left;">${v.sku_konversi_group}</td>
											<td style="vertical-align: middle;text-align:left;">${v.sku_nama_produk}</td>
											<td style="vertical-align: middle;text-align:left;">${v.sku_composite}</td>
											<td style="vertical-align: middle;text-align:left;">${v.sku_expired_date}</td>
											<td style="vertical-align: middle;text-align:right;">${v.sku_actual_qty_opname_comp}</td>
											<td style="vertical-align: middle;text-align:right;">${v.sku_qty_sistem_comp}</td>
											<td style="vertical-align: middle;text-align:left;"><span class="text-success">Tidak Selisih</span></td>
										</tr>
									`);
								} else {
									$("#table_report_komparasi_stok_fisik_dan_sistem_detail > tbody").append(`
										<tr>
											<td style="vertical-align: middle;text-align:left;">${i+1}</td>
											<td style="vertical-align: middle;text-align:left;">${v.tr_opname_plan_kode}</td>
											<td style="vertical-align: middle;text-align:left;">${v.client_wms_nama}</td>
											<td style="vertical-align: middle;text-align:left;">${v.tr_opname_plan_tanggal}</td>
											<td style="vertical-align: middle;text-align:left;">${v.tr_opname_plan_tgl_start}</td>
											<td style="vertical-align: middle;text-align:left;">${v.tr_opname_plan_tgl_end}</td>
											<td style="vertical-align: middle;text-align:left;">${v.karyawan_nama}</td>
											<td style="vertical-align: middle;text-align:left;">${v.tipe_stok}</td>
											<td style="vertical-align: middle;text-align:left;">${v.tipe_opname_nama}</td>
											<td style="vertical-align: middle;text-align:left;">${v.tr_opname_plan_status}</td>
											<td style="vertical-align: middle;text-align:left;">${v.principle_kode}</td>
											<td style="vertical-align: middle;text-align:left;">${v.sku_konversi_group}</td>
											<td style="vertical-align: middle;text-align:left;">${v.sku_nama_produk}</td>
											<td style="vertical-align: middle;text-align:left;">${v.sku_composite}</td>
											<td style="vertical-align: middle;text-align:left;">${v.sku_expired_date}</td>
											<td style="vertical-align: middle;text-align:right;">${v.sku_actual_qty_opname_comp}</td>
											<td style="vertical-align: middle;text-align:right;">${v.sku_qty_sistem_comp}</td>
											<td style="vertical-align: middle;text-align:left;"><span class="text-danger">Selisih</span></td>
										</tr>
									`);
								}
							});

							$("#table_report_komparasi_stok_fisik_dan_sistem_detail").DataTable({
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