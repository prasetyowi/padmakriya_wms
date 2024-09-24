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
			// if ($('#filter_stock_tanggal').length > 0) {
			// 	$('#filter_stock_tanggal').daterangepicker({
			// 		'applyClass': 'btn-sm btn-success',
			// 		'cancelClass': 'btn-sm btn-default',
			// 		locale: {
			// 			"format": "DD/MM/YYYY",
			// 			applyLabel: 'Apply',
			// 			cancelLabel: 'Cancel',
			// 		},
			// 		'startDate': '<?= date("01-m-Y") ?>',
			// 		'endDate': '<?= date("t-m-Y") ?>'
			// 	});
			// }
			// if ($('#filter_stock_tanggal1').length > 0) {
			// 	$('#filter_stock_tanggal1').daterangepicker({
			// 		'applyClass': 'btn-sm btn-success',
			// 		'cancelClass': 'btn-sm btn-default',
			// 		locale: {
			// 			"format": "DD/MM/YYYY",
			// 			applyLabel: 'Apply',
			// 			cancelLabel: 'Cancel',
			// 		},
			// 		'startDate': '<?= date("01-m-Y") ?>',
			// 		'endDate': '<?= date("t-m-Y") ?>'
			// 	});
			// }

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
			$("#span_bulan").hide();
		} else if (tipe == "bulan") {
			$("#span_bulan").show();
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
			url: "<?= base_url('WMS/LaporanTransaksi/LaporanAgingStock/GetPrincipleByPerusahaan') ?>",
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

	const handlerFilterData = (event) => {

		var thead_str = "";
		var tbody_str = "";
		var tipe = $("#filter_tipe").val();

		if ($("#filter_perusahaan").val() == "") {
			var alert = GetLanguageByKode('CAPTION-ALERT-PERUSAHAANTIDAKDIPILIH');
			message_custom("Error", "error", alert);
			return false;
		}

		if ($("#filter_principle").val() == "") {
			var alert = GetLanguageByKode('CAPTION-ALERT-PRINCIPLETIDAKDIPILIH');
			message_custom("Error", "error", alert);
			return false;
		}

		if (parseInt($("#filter_tahun2").val()) - parseInt($("#filter_tahun").val()) > 1) {
			// var alert = GetLanguageByKode('CAPTION-ALERT-PRINCIPLETIDAKDIPILIH');
			var alert = "Range maksimal 2 tahun";
			message_custom("Error", "error", alert);
			return false;
		}

		if (parseInt($("#filter_bulan").val()) > parseInt($("#filter_bulan2").val())) {
			// var alert = GetLanguageByKode('CAPTION-ALERT-PRINCIPLETIDAKDIPILIH');
			var alert = "Range bulan tidak valid";
			message_custom("Error", "error", alert);
			return false;
		}

		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/LaporanTransaksi/LaporanAgingStock/Get_laporan_stock_aging') ?>",
			data: {
				tahun: $("#filter_tahun").val(),
				tahun2: $("#filter_tahun2").val(),
				bulan: $("#filter_bulan").val(),
				bulan2: $("#filter_bulan2").val(),
				client_wms_id: $("#filter_perusahaan").val(),
				depo_detail_id: "",
				// depo_detail_id: $("#filter_stock_gudang").val(),
				principle_id: $("#filter_principle").val(),
				tipe: tipe
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

				if ($.fn.DataTable.isDataTable('#table_laporan_stock_aging')) {
					$('#table_laporan_stock_aging').DataTable().clear();
					$('#table_laporan_stock_aging').DataTable().destroy();
				}

				$('#table_laporan_stock_aging > thead').empty('');
				$('#table_laporan_stock_aging > tbody').empty('');

				if (response.Kolom.length != 0) {

					$('#table_laporan_stock_aging').fadeOut("slow", function() {
						$(this).hide();

						thead_str +=
							`<tr style="background:#F0EBE3;">
								<th style="vertical-align: middle;text-align:center;" name="CAPTION-PRINCIPLE">Principle</th>
								<th style="vertical-align: middle;text-align:center;" name="CAPTION-KODESKU">Kode SKU</th>
								<th style="vertical-align: middle;text-align:center;" name="CAPTION-SKU">SKU</th>
								<th style="vertical-align: middle;text-align:center;" name="CAPTION-SKUSATUAN">SKU Satuan</th>`;

						if (tipe == "tahun") {

							$.each(response.Kolom, function(i, v) {
								thead_str += `<th style="vertical-align: middle;text-align:center;">${v.kolom.replaceAll("stock_qty_","")}</th>`;
							});

						} else if (tipe == "bulan") {

							$.each(response.Kolom, function(i, v) {
								thead_str += `<th style="vertical-align: middle;text-align:center;">${formatBulanKolom((v.kolom.replaceAll("stock_qty_","")).replaceAll("_","-")+"-01")}</th>`;
							});

						}

						thead_str += `</tr>`;

						$("#table_laporan_stock_aging > thead").append(thead_str);


					}).fadeIn("slow", function() {
						$(this).show();

						if (response.LaporanAgingStock.length != 0) {

							$.each(response.LaporanAgingStock, function(i, v) {
								tbody_str += `<tr>
									<td style="vertical-align: middle;text-align:center;">${v.principle}</td>
									<td style="vertical-align: middle;text-align:center;">${v.sku_konversi_group}</td>
									<td style="vertical-align: middle;text-align:center;">${v.sku_nama_produk}</td>
									<td style="vertical-align: middle;text-align:center;">${v.sku_satuan}</td>`;

								$.each(response.Kolom, function(i2, v2) {
									tbody_str += `<td style="vertical-align: middle;text-align:center;">${v[v2.kolom]}</td>`;
								});
							});

							$("#table_laporan_stock_aging > tbody").append(tbody_str);

							// console.log(tbody_str);

							$("#table_laporan_stock_aging").DataTable({
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

	function formatBulanKolom(tanggal) {
		var date = new Date(tanggal);
		var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
		var formattedDate = months[date.getMonth()] + " " + date.getFullYear();

		return formattedDate;
	}
</script>