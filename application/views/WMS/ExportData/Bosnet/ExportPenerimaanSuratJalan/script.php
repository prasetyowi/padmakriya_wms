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
			// GetLaporanStockRekapData();
		}
	);

	const handlerFilterData = (event) => {

		if ($("#filter_principle").val() == "") {
			var alert = GetLanguageByKode('CAPTION-ALERT-PRINCIPLETIDAKDIPILIH');
			// var alert = "Pengemudi Tidak Dipilih";
			message_custom("Error", "error", alert);
			return false;
		}

		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/ExportData/Bosnet/Get_bosnet_penerimaan_surat_jalan_by_filter') ?>",
			data: {
				tanggal: $("#filter_tanggal").val(),
				principle_id: $("#filter_principle").val(),
				principle_kode: $("#filter_principle :selected").text()
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

				if ($.fn.DataTable.isDataTable('#table_export_penerimaan_surat_jalan')) {
					$('#table_export_penerimaan_surat_jalan').DataTable().clear();
					$('#table_export_penerimaan_surat_jalan').DataTable().destroy();
				}

				$('#table_export_penerimaan_surat_jalan > tbody').empty('');

				$('#table_export_penerimaan_surat_jalan').fadeOut("slow", function() {
					$(this).hide();

				}).fadeIn("slow", function() {
					$(this).show();

					if (response.length != 0) {

						$.each(response, function(i, v) {
							$("#table_export_penerimaan_surat_jalan > tbody").append(`
								<tr>
									<td style="vertical-align: middle;text-align:center;">${i+1}</td>
									<td style="vertical-align: middle;text-align:center;">${v.principle_kode}</td>
									<td style="vertical-align: middle;text-align:center;">${v.principle_nama}</td>
									<td style="vertical-align: middle;text-align:center;">${v.sku_konversi_group}</td>
									<td style="vertical-align: middle;text-align:center;">${v.sku_nama_produk}</td>
									<td style="vertical-align: middle;text-align:center;">${v.penerimaan_surat_jalan_tgl}</td>
									<td style="vertical-align: middle;text-align:center;">${v.penerimaan_surat_jalan_no_sj}</td>
									<td style="vertical-align: middle;text-align:center;">${v.sku_jumlah_barang}</td>
									<td style="vertical-align: middle;text-align:center;">${v.sku_harga}</td>
									<td style="vertical-align: middle;text-align:center;">${v.depo_eksternal_kode}</td>
								</tr>
							`);
						});

						// console.log(tbody_str);

						$("#table_export_penerimaan_surat_jalan").DataTable({
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