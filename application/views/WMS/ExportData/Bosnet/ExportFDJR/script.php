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
			if ($('#filter_tanggal_kirim').length > 0) {
				$('#filter_tanggal_kirim').daterangepicker({
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
			// GetLaporanStockRekapData();
		}
	);

	const handlerFilterData = (event) => {

		var thead_str = "";
		var tbody_str = "";
		var tipe = $("#filter_tipe").val();

		if ($("#filter_pengemudi").val() == "") {
			// var alert = GetLanguageByKode('CAPTION-ALERT-PENGEMUDITIDAKDIPILIH');
			var alert = "Pengemudi Tidak Dipilih";
			message_custom("Error", "error", alert);
			return false;
		}

		if ($("#filter_gudang").val() == "") {
			// var alert = GetLanguageByKode('CAPTION-ALERT-PENGEMUDITIDAKDIPILIH');
			var alert = "Gudang Tidak Dipilih";
			message_custom("Error", "error", alert);
			return false;
		}

		if ($("#filter_rit").val() == "") {
			// var alert = GetLanguageByKode('CAPTION-ALERT-PENGEMUDITIDAKDIPILIH');
			var alert = "Rit Tidak Boleh Kosong";
			message_custom("Error", "error", alert);
			return false;
		}

		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/ExportData/Bosnet/Get_bosnet_do_by_filter') ?>",
			data: {
				tanggal: $("#filter_tanggal_kirim").val(),
				karyawan_eksternal_id: $("#filter_pengemudi").val(),
				gudang: $("#filter_gudang").val(),
				rit: $("#filter_rit").val()
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

				if ($.fn.DataTable.isDataTable('#table_export_fdjr')) {
					$('#table_export_fdjr').DataTable().clear();
					$('#table_export_fdjr').DataTable().destroy();
				}

				$('#table_export_fdjr > tbody').empty('');

				$('#table_export_fdjr').fadeOut("slow", function() {
					$(this).hide();

				}).fadeIn("slow", function() {
					$(this).show();

					if (response.length != 0) {

						$.each(response, function(i, v) {
							$("#table_export_fdjr > tbody").append(`
								<tr>
									<td style="vertical-align: middle;text-align:center;">${i+1}</td>
									<td style="vertical-align: middle;text-align:center;">${v.sales_eksternal_id}</td>
									<td style="vertical-align: middle;text-align:center;">${v.delivery_order_batch_tanggal_kirim}</td>
									<td style="vertical-align: middle;text-align:center;">${v.sales_order_no_po}</td>
									<td style="vertical-align: middle;text-align:center;">${v.do_id}</td>
									<td style="vertical-align: middle;text-align:center;">${v.kendaraan_nopol}</td>
									<td style="vertical-align: middle;text-align:center;">${v.rit}</td>
									<td style="vertical-align: middle;text-align:center;">${v.gudang}</td>
								</tr>
							`);
						});

						// console.log(tbody_str);

						$("#table_export_fdjr").DataTable({
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