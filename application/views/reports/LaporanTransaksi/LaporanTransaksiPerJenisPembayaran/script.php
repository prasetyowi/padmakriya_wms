<script type="text/javascript">
	var index_pallet = 0;

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
			$("#page2").hide();
			// $('#table_detail').DataTable({
			// 	responsive: true,
			// 	searching: false,
			// 	ordering: false,
			// });
			// GetLaporanStockRekapData();
		}
	);


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

	$("#btncariAll").click(
		function() {
			GetLaporanStockRekapData()
			let timerInterval
			Swal.fire({
				title: 'Loading...',
				html: 'Please Wait..',
				timer: 3000,
				timerProgressBar: true,
				allowOutsideClick: false,
				showCancelButton: false, // Disable the "Cancel" button
				showConfirmButton: false,
				didOpen: () => {
					Swal.showLoading()
					const b = Swal.getHtmlContainer().querySelector('b')
					timerInterval = setInterval(() => {
						b.textContent = Swal.getTimerLeft()
					}, 2000)

				},
				willClose: () => {
					clearInterval(timerInterval)

				}
			}).then((result) => {
				if (result.dismiss === Swal.DismissReason.timer) {
					console.log('succes');
				}
			})

		})

	function GetLaporanStockRekapData() {
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/LaporanTransaksi/LaporanTransaksiPerJenisPembayaran/GetDetail') ?>",
			data: {
				principle: $('#filter_principle option:selected').val(),
				client_wms: $('#filter_perusahaan option:selected').val(),
				tanggal_kirim: $('#filter_tanggal_kirim').val(),
				driver: $('#filter_driver').val()
			},
			dataType: "JSON",
			async: false,
			success: function(response) {

				$('#table_detail > tbody').html();
				$('#table_detail > tbody').empty();

				// if ($.fn.DataTable.isDataTable('#table_detail')) {
				// 	$('#table_detail').DataTable().clear();
				// 	$('#table_detail').DataTable().destroy();
				// }

				if (response.length > 0) {
					$.each(response, function(i, v) {

						var delivery_order_nominal_tunai = v.delivery_order_nominal_tunai == ".0000" ? "0" : formatRupiahCurr(parseInt(v.delivery_order_nominal_tunai));
						var delivery_order_jumlah_bayar = v.delivery_order_jumlah_bayar == ".0000" ? "0" : formatRupiahCurr(parseInt(v.delivery_order_jumlah_bayar));

						if (v.principle_kode === null && v.karyawan_nama === null) {
							$("#table_detail > tbody").append(`
								<tr style="background:#430A5D;color:white;font-weight: bold;">
									<td style="vertical-align: middle;text-align:center;">Grand Total</td>
									<td style="vertical-align: middle;text-align:center;"></td>
									<td style="vertical-align: middle;text-align:right;">
										<button class="btn btn-link" onclick="GetDetailLaporan('${v.karyawan_nama}','')"><font style="color:white;font-weight: bold;">${delivery_order_nominal_tunai}</font></button>
									</td>
									<td style="vertical-align: middle;text-align:right;">
										<button class="btn btn-link" onclick="GetDetailLaporan('','')"><font style="color:white;font-weight: bold;">${delivery_order_jumlah_bayar}</font></button>
									</td>
								</tr>
							`);
						} else if (v.principle_kode === null) {
							$("#table_detail > tbody").append(`
								<tr style="background:#5755FE;color:white;font-weight: bold;">
									<td style="vertical-align: middle;text-align:center;"></td>
									<td style="vertical-align: middle;text-align:center;">Total ${v.karyawan_nama}</td>
									<td style="vertical-align: middle;text-align:right;">
										<button class="btn btn-link" onclick="GetDetailLaporan('${v.karyawan_nama}','')"><font style="color:white;font-weight: bold;">${delivery_order_nominal_tunai}</font></button>
									</td>
									<td style="vertical-align: middle;text-align:right;">
										<button class="btn btn-link" onclick="GetDetailLaporan('${v.karyawan_nama}','')"><font style="color:white;font-weight: bold;">${delivery_order_jumlah_bayar}</font></button>
									</td>
								</tr>
							`);

						} else {
							$("#table_detail > tbody").append(`
								<tr>
									<td style="vertical-align: middle;text-align:center;">${v.karyawan_nama}</td>
									<td style="vertical-align: middle;text-align:center;">${v.principle_kode}</td>
									<td style="vertical-align: middle;text-align:right;">
										<button class="btn btn-link" onclick="GetDetailLaporan('${v.karyawan_nama}','${v.principle_kode}')">${delivery_order_nominal_tunai}</button>
									</td>
									<td style="vertical-align: middle;text-align:right;">
										<button class="btn btn-link" onclick="GetDetailLaporan('${v.karyawan_nama}','${v.principle_kode}')">${delivery_order_jumlah_bayar}</button>
									</td>
								</tr>
							`);
						}
					});

				} else {
					message_topright("error", "Data Kosong")
				}
			}
		});
	}

	function GetDetailLaporan(karyawan_nama, principle_kode) {

		$("#modal_detail_laporan").modal('show');

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/LaporanTransaksi/LaporanTransaksiPerJenisPembayaran/GetDetailLaporan') ?>",
			data: {
				principle: principle_kode,
				client_wms: $('#filter_perusahaan option:selected').val(),
				tanggal_kirim: $('#filter_tanggal_kirim').val(),
				driver: karyawan_nama
			},
			dataType: "JSON",
			async: false,
			success: function(response) {

				$('#table_detail_laporan > tbody').html();
				$('#table_detail_laporan > tbody').empty();

				if ($.fn.DataTable.isDataTable('#table_detail_laporan')) {
					$('#table_detail_laporan').DataTable().clear();
					$('#table_detail_laporan').DataTable().destroy();
				}

				if (response.length > 0) {
					$.each(response, function(i, v) {

						var delivery_order_nominal_tunai = v.delivery_order_nominal_tunai == ".0000" ? "0" : formatRupiahCurr(parseInt(v.delivery_order_nominal_tunai));
						var delivery_order_jumlah_bayar = v.delivery_order_jumlah_bayar == ".0000" ? "0" : formatRupiahCurr(parseInt(v.delivery_order_jumlah_bayar));

						$("#table_detail_laporan > tbody").append(`
							<tr>
								<td class="text-center">${i+1}</td>
								<td class="text-left">${v.delivery_order_kode}</td>
								<td class="text-left">${v.delivery_order_tgl_aktual_kirim}</td>
								<td class="text-left">${v.delivery_order_tgl_expired_do}</td>
								<td class="text-left">${v.karyawan_nama}</td>
								<td class="text-left">${v.delivery_order_kirim_nama}</td>
								<td class="text-left">${v.delivery_order_kirim_alamat}</td>
								<td class="text-left">${v.delivery_order_kirim_kelurahan}</td>
								<td class="text-left">${v.delivery_order_kirim_kecamatan}</td>
								<td class="text-left">${v.delivery_order_kirim_kota}</td>
								<td class="text-left">${v.delivery_order_ambil_provinsi}</td>
								<td class="text-left">${v.principle_kode}</td>
								<td class="text-right">${delivery_order_nominal_tunai}</td>
								<td class="text-left">${v.tipe_pembayaran_nama}</td>
								<td class="text-right">${delivery_order_jumlah_bayar}</td>
							</tr>
						`);
					});

					$("#table_detail_laporan").DataTable({
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
							[50, 100, 200, -1],
							[50, 100, 200, 'All'],
						],
					});

				} else {
					message_topright("error", "Data Kosong")
				}
			}
		});
	}
</script>