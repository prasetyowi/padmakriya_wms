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
			url: "<?= base_url('WMS/OperationalExcellence/PenerimaanBarangSupplier/AnalisisJumlahPenerimaan/GetPrincipleByPerusahaan') ?>",
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

		// if ($("#filter_principle").val() == "") {
		// 	var alert = GetLanguageByKode('CAPTION-ALERT-PRINCIPLETIDAKDIPILIH');
		// 	message_custom("Error", "error", alert);
		// 	return false;
		// }

		if (parseInt($("#filter_tahun2").val()) - parseInt($("#filter_tahun").val()) > 1) {
			// var alert = GetLanguageByKode('CAPTION-ALERT-PRINCIPLETIDAKDIPILIH');
			var alert = "Range maksimal 2 tahun";
			message_custom("Error", "error", alert);
			return false;
		}

		// if (parseInt($("#filter_bulan").val()) > parseInt($("#filter_bulan2").val())) {
		// 	// var alert = GetLanguageByKode('CAPTION-ALERT-PRINCIPLETIDAKDIPILIH');
		// 	var alert = "Range bulan tidak valid";
		// 	message_custom("Error", "error", alert);
		// 	return false;
		// }

		// Ambil nilai bulan dan tahun dari form (misalnya dengan ID input)
		const monthStart = parseInt(document.getElementById("filter_bulan").value, 10);
		const yearStart = parseInt(document.getElementById("filter_tahun").value, 10);
		const monthEnd = parseInt(document.getElementById("filter_bulan2").value, 10);
		const yearEnd = parseInt(document.getElementById("filter_tahun2").value, 10);

		if (!isValidDateRange(monthStart, yearStart, monthEnd, yearEnd)) {
			var alert = "Range tanggal tidak valid: bulan dan tahun akhir tidak boleh lebih awal dari bulan dan tahun mulai.";

			message_custom("Error", "error", alert);
			return false; // Prevent form submission
		}

		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/OperationalExcellence/PenerimaanBarangSupplier/AnalisisJumlahPenerimaan/Exec_report_analisis_jumlah_penerimaan') ?>",
			data: {
				tahun: $("#filter_tahun").val(),
				tahun2: $("#filter_tahun2").val(),
				bulan: $("#filter_bulan").val(),
				bulan2: $("#filter_bulan2").val(),
				tanggal: $("#filter_tanggal").val(),
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

				if ($.fn.DataTable.isDataTable('#table_report_analisis_jumlah_penerimaan')) {
					$('#table_report_analisis_jumlah_penerimaan').DataTable().clear();
					$('#table_report_analisis_jumlah_penerimaan').DataTable().destroy();
				}

				$('#table_report_analisis_jumlah_penerimaan > thead').empty('');
				$('#table_report_analisis_jumlah_penerimaan > tbody').empty('');

				if (response.Kolom.length != 0) {

					$('#table_report_analisis_jumlah_penerimaan').fadeOut("slow", function() {
						$(this).hide();

						thead_str +=
							`<tr style="background:#F0EBE3;">
								<th class="text-center" name="CAPTION-NOPOLKENDARAAN">Nopol Kendaraan</th>
								<th class="text-center" name="CAPTION-PERUSAHAAN">Perusahaan</th>
								<th class="text-center" name="CAPTION-PRINCIPLE">Principle</th>`;

						if (tipe == "tahun") {

							$.each(response.Kolom, function(i, v) {
								thead_str += `<th style="vertical-align: middle;text-align:center;">${v.kolom.replaceAll("sku_jumlah_barang_terima_comp_ctn_","")}</th>`;
							});

						} else if (tipe == "bulan") {

							$.each(response.Kolom, function(i, v) {
								thead_str += `<th style="vertical-align: middle;text-align:center;">${formatBulanKolom((v.kolom.replaceAll("sku_jumlah_barang_terima_comp_ctn_","")).replaceAll("_","-")+"-01", tipe)}</th>`;
							});

						} else if (tipe == "tanggal") {

							$.each(response.Kolom, function(i, v) {
								thead_str += `<th style="vertical-align: middle;text-align:center;">${formatBulanKolom((v.kolom.replaceAll("sku_jumlah_barang_terima_comp_ctn_","")).replaceAll("_","-"), tipe)}</th>`;
							});

						}

						thead_str += `</tr>`;

						$("#table_report_analisis_jumlah_penerimaan > thead").append(thead_str);


					}).fadeIn("slow", function() {
						$(this).show();

						if (response.AnalisisJumlahPenerimaan.length != 0) {

							$.each(response.AnalisisJumlahPenerimaan, function(i, v) {
								tbody_str += `<tr>
									<td style="vertical-align: middle;text-align:left;">${v.penerimaan_surat_jalan_nopol}</td>
									<td style="vertical-align: middle;text-align:left;">${v.client_wms_nama}</td>
									<td style="vertical-align: middle;text-align:left;">${v.principle_kode}</td>`;

								$.each(response.Kolom, function(i2, v2) {
									tbody_str += `
											<td style="vertical-align: middle;text-align:right;">
												<button class="btn btn-link" onclick="Get_detail_analisis_jumlah_penerimaan('${v.penerimaan_surat_jalan_nopol}','${v2.kolom}')">${v[v2.kolom]}
											</td>`;
								});
							});

							$("#table_report_analisis_jumlah_penerimaan > tbody").append(tbody_str);

							// console.log(tbody_str);

							$("#table_report_analisis_jumlah_penerimaan").DataTable({
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

				// Konfigurasi Chart.js
				if (tipe == "tahun") {
					var labels = [...new Set(response.ChartAnalisisJumlahPenerimaan.map(item => item.tahun))];
					var dataByPrinciple = {};
					response.ChartAnalisisJumlahPenerimaan.forEach(item => {
						if (!dataByPrinciple[item.principle]) {
							dataByPrinciple[item.principle] = {};
						}
						dataByPrinciple[item.principle][item.tahun] = item.jumlah_penerimaan;
					});

					// Warna solid untuk setiap principle
					var solidColors = [
						'rgb(54, 162, 235)',
						'rgb(255, 99, 132)',
						'rgb(255, 206, 86)',
						'rgb(75, 192, 192)'
					];

					var datasets = [];
					var colorIndex = 0;
					for (var principle in dataByPrinciple) {
						var dataPoints = labels.map(tahun => {
							return dataByPrinciple[principle][tahun] || 0;
						});

						datasets.push({
							label: principle,
							data: dataPoints,
							backgroundColor: solidColors[colorIndex % solidColors.length],
							borderColor: solidColors[colorIndex % solidColors.length],
							borderWidth: 1
						});
						colorIndex++;
					}

					var ctx = document.getElementById('chart_report_analisis_jumlah_penerimaan').getContext('2d');
					if (window.penerimaanChart) {
						window.penerimaanChart.destroy();
					}

					window.penerimaanChart = new Chart(ctx, {
						type: 'bar',
						data: {
							labels: labels,
							datasets: datasets
						},
						options: {
							scales: {
								x: {
									title: {
										display: true,
										text: 'Waktu' // Misalnya: "Bulan"
									}
								},
								y: {
									beginAtZero: true,
									title: {
										display: true,
										text: 'Penerimaan (Ctn)' // Satuan "dalam ribu unit"
									}
								}
							}
						}
					});
				} else if (tipe == "bulan") {
					var labels = [...new Set(response.ChartAnalisisJumlahPenerimaan.map(item => item.bulan))];
					var dataByPrinciple = {};
					response.ChartAnalisisJumlahPenerimaan.forEach(item => {
						if (!dataByPrinciple[item.principle]) {
							dataByPrinciple[item.principle] = {};
						}
						dataByPrinciple[item.principle][item.bulan] = item.jumlah_penerimaan;
					});

					// Warna solid untuk setiap principle
					var solidColors = [
						'rgb(54, 162, 235)',
						'rgb(255, 99, 132)',
						'rgb(255, 206, 86)',
						'rgb(75, 192, 192)'
					];

					var datasets = [];
					var colorIndex = 0;
					for (var principle in dataByPrinciple) {
						var dataPoints = labels.map(bulan => {
							return dataByPrinciple[principle][bulan] || 0;
						});

						datasets.push({
							label: principle,
							data: dataPoints,
							backgroundColor: solidColors[colorIndex % solidColors.length],
							borderColor: solidColors[colorIndex % solidColors.length],
							borderWidth: 1
						});
						colorIndex++;
					}

					var ctx = document.getElementById('chart_report_analisis_jumlah_penerimaan').getContext('2d');
					if (window.penerimaanChart) {
						window.penerimaanChart.destroy();
					}

					window.penerimaanChart = new Chart(ctx, {
						type: 'bar',
						data: {
							labels: labels,
							datasets: datasets
						},
						options: {
							scales: {
								x: {
									title: {
										display: true,
										text: 'Waktu' // Misalnya: "Bulan"
									}
								},
								y: {
									beginAtZero: true,
									title: {
										display: true,
										text: 'Penerimaan (Ctn)' // Satuan "dalam ribu unit"
									}
								}
							}
						}
					});

				} else if (tipe == "tanggal") {
					var labels = [...new Set(response.ChartAnalisisJumlahPenerimaan.map(item => item.tanggal))];
					var dataByPrinciple = {};
					response.ChartAnalisisJumlahPenerimaan.forEach(item => {
						if (!dataByPrinciple[item.principle]) {
							dataByPrinciple[item.principle] = {};
						}
						dataByPrinciple[item.principle][item.tanggal] = item.jumlah_penerimaan;
					});

					// Warna solid untuk setiap principle
					var solidColors = [
						'rgb(54, 162, 235)',
						'rgb(255, 99, 132)',
						'rgb(255, 206, 86)',
						'rgb(75, 192, 192)'
					];

					var datasets = [];
					var colorIndex = 0;
					for (var principle in dataByPrinciple) {
						var dataPoints = labels.map(tanggal => {
							return dataByPrinciple[principle][tanggal] || 0;
						});

						datasets.push({
							label: principle,
							data: dataPoints,
							backgroundColor: solidColors[colorIndex % solidColors.length],
							borderColor: solidColors[colorIndex % solidColors.length],
							borderWidth: 1
						});
						colorIndex++;
					}

					var ctx = document.getElementById('chart_report_analisis_jumlah_penerimaan').getContext('2d');
					if (window.penerimaanChart) {
						window.penerimaanChart.destroy();
					}

					window.penerimaanChart = new Chart(ctx, {
						type: 'bar',
						data: {
							labels: labels,
							datasets: datasets
						},
						options: {
							scales: {
								x: {
									title: {
										display: true,
										text: 'Waktu' // Misalnya: "Bulan"
									}
								},
								y: {
									beginAtZero: true,
									title: {
										display: true,
										text: 'Penerimaan (Ctn)' // Satuan "dalam ribu unit"
									}
								}
							}
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

	function formatBulanKolom(tanggal, tipe) {
		var date = new Date(tanggal);
		var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
		var formattedDate = "";

		if (tipe == "bulan") {
			formattedDate = months[date.getMonth()] + " " + date.getFullYear();

		} else if (tipe == "tanggal") {
			formattedDate = ("0" + date.getDate()).slice(-2) + " " + months[date.getMonth()] + " " + date.getFullYear();

		}

		return formattedDate;
	}

	function Get_detail_analisis_jumlah_penerimaan(nopol, time) {

		var perusahaan = $("#filter_perusahaan").val();
		var principle = $("#filter_principle").val();
		var tipe = $("#filter_tipe").val();
		var tahun = "";
		var bulan = "";
		var tanggal = "";

		time = (time.replaceAll("sku_jumlah_barang_terima_comp_ctn_", "")).replaceAll("_", "-");
		arr_time = time.split("-");

		tahun = arr_time[0];
		bulan = arr_time[1];
		tanggal = time;

		$("#modal_detail_analisis_jumlah_penerimaan").modal('show');

		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/OperationalExcellence/PenerimaanBarangSupplier/AnalisisJumlahPenerimaan/Get_detail_analisis_jumlah_penerimaan') ?>",
			data: {
				tahun: tahun,
				bulan: bulan,
				tanggal: tanggal,
				client_wms_id: perusahaan,
				principle_id: principle,
				nopol: nopol,
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

				if (tipe == "tahun") {

					$("#span_detail_tahun").show();
					$("#span_detail_bulan").hide();
					$("#span_detail_tanggal").hide();

					if ($.fn.DataTable.isDataTable('#table_report_analisis_jumlah_penerimaan_detail_tahun')) {
						$('#table_report_analisis_jumlah_penerimaan_detail_tahun').DataTable().clear();
						$('#table_report_analisis_jumlah_penerimaan_detail_tahun').DataTable().destroy();
					}

					$('#table_report_analisis_jumlah_penerimaan_detail_tahun > tbody').empty('');

					if (response.length != 0) {

						$('#table_report_analisis_jumlah_penerimaan_detail_tahun').fadeOut("slow", function() {
							$(this).hide();

						}).fadeIn("slow", function() {
							$(this).show();

							if (response.length != 0) {

								$.each(response, function(i, v) {

									$("#table_report_analisis_jumlah_penerimaan_detail_tahun > tbody").append(`
										<tr>
											<td style="vertical-align: middle;text-align:left;">${v.tahun}</td>
											<td style="vertical-align: middle;text-align:left;">${v.penerimaan_surat_jalan_nopol}</td>
											<td style="vertical-align: middle;text-align:left;">${v.client_wms_nama}</td>
											<td style="vertical-align: middle;text-align:left;">${v.principle_kode}</td>
											<td style="vertical-align: middle;text-align:right;">
												<button class="btn btn-link" onclick="Get_detail_analisis_jumlah_penerimaan_by_tipe('${v.tahun}','','','${v.penerimaan_surat_jalan_nopol}','ditolak')">${v.sku_jumlah_barang_terima_comp_ctn_ditolak}</button>
											</td>
											<td style="vertical-align: middle;text-align:right;">
												<button class="btn btn-link" onclick="Get_detail_analisis_jumlah_penerimaan_by_tipe('${v.tahun}','','','${v.penerimaan_surat_jalan_nopol}','pecah')">${v.sku_jumlah_barang_terima_comp_ctn_pecah}</button>
											</td>
											<td style="vertical-align: middle;text-align:right;">
												<button class="btn btn-link" onclick="Get_detail_analisis_jumlah_penerimaan_by_tipe('${v.tahun}','','','${v.penerimaan_surat_jalan_nopol}','klaim')">${v.sku_jumlah_barang_terima_comp_ctn_klaim}</button>
											</td>
											<td style="vertical-align: middle;text-align:right;">
												<button class="btn btn-link" onclick="Get_detail_analisis_jumlah_penerimaan_by_tipe('${v.tahun}','','','${v.penerimaan_surat_jalan_nopol}','retur')">${v.sku_jumlah_barang_terima_comp_ctn_retur}</button>
											</td>
											<td style="vertical-align: middle;text-align:right;">
												<button class="btn btn-link" onclick="Get_detail_analisis_jumlah_penerimaan_by_tipe('${v.tahun}','','','${v.penerimaan_surat_jalan_nopol}','lain_lain')">${v.sku_jumlah_barang_terima_comp_ctn}</button>
											</td>
										</tr>
									`);
								});

								// console.log(tbody_str);

								$("#table_report_analisis_jumlah_penerimaan_detail_tahun").DataTable({
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

				} else if (tipe == "bulan") {

					$("#span_detail_tahun").hide();
					$("#span_detail_bulan").show();
					$("#span_detail_tanggal").hide();

					if ($.fn.DataTable.isDataTable('#table_report_analisis_jumlah_penerimaan_detail_bulan')) {
						$('#table_report_analisis_jumlah_penerimaan_detail_bulan').DataTable().clear();
						$('#table_report_analisis_jumlah_penerimaan_detail_bulan').DataTable().destroy();
					}

					$('#table_report_analisis_jumlah_penerimaan_detail_bulan > tbody').empty('');

					if (response.length != 0) {

						$('#table_report_analisis_jumlah_penerimaan_detail_bulan').fadeOut("slow", function() {
							$(this).hide();

						}).fadeIn("slow", function() {
							$(this).show();

							if (response.length != 0) {

								$.each(response, function(i, v) {

									$("#table_report_analisis_jumlah_penerimaan_detail_bulan > tbody").append(`
										<tr>
											<td style="vertical-align: middle;text-align:left;">${v.tahun}</td>
											<td style="vertical-align: middle;text-align:left;">${v.bulan}</td>
											<td style="vertical-align: middle;text-align:left;">${v.penerimaan_surat_jalan_nopol}</td>
											<td style="vertical-align: middle;text-align:left;">${v.client_wms_nama}</td>
											<td style="vertical-align: middle;text-align:left;">${v.principle_kode}</td>
											<td style="vertical-align: middle;text-align:right;">
												<button class="btn btn-link" onclick="Get_detail_analisis_jumlah_penerimaan_by_tipe('${v.tahun}','${v.bulan}','','${v.penerimaan_surat_jalan_nopol}','ditolak')">${v.sku_jumlah_barang_terima_comp_ctn_ditolak}</button>
											</td>
											<td style="vertical-align: middle;text-align:right;">
												<button class="btn btn-link" onclick="Get_detail_analisis_jumlah_penerimaan_by_tipe('${v.tahun}','${v.bulan}','','${v.penerimaan_surat_jalan_nopol}','pecah')">${v.sku_jumlah_barang_terima_comp_ctn_pecah}</button>
											</td>
											<td style="vertical-align: middle;text-align:right;">
												<button class="btn btn-link" onclick="Get_detail_analisis_jumlah_penerimaan_by_tipe('${v.tahun}','${v.bulan}','','${v.penerimaan_surat_jalan_nopol}','klaim')">${v.sku_jumlah_barang_terima_comp_ctn_klaim}</button>
											</td>
											<td style="vertical-align: middle;text-align:right;">
												<button class="btn btn-link" onclick="Get_detail_analisis_jumlah_penerimaan_by_tipe('${v.tahun}','${v.bulan}','','${v.penerimaan_surat_jalan_nopol}','retur')">${v.sku_jumlah_barang_terima_comp_ctn_retur}</button>
											</td>
											<td style="vertical-align: middle;text-align:right;">
												<button class="btn btn-link" onclick="Get_detail_analisis_jumlah_penerimaan_by_tipe('${v.tahun}','${v.bulan}','','${v.penerimaan_surat_jalan_nopol}','lain_lain')">${v.sku_jumlah_barang_terima_comp_ctn}</button>
											</td>
										</tr>
									`);
								});

								// console.log(tbody_str);

								$("#table_report_analisis_jumlah_penerimaan_detail_bulan").DataTable({
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

				} else if (tipe == "tanggal") {

					$("#span_detail_tahun").hide();
					$("#span_detail_bulan").hide();
					$("#span_detail_tanggal").show();

					if ($.fn.DataTable.isDataTable('#table_report_analisis_jumlah_penerimaan_detail_tanggal')) {
						$('#table_report_analisis_jumlah_penerimaan_detail_tanggal').DataTable().clear();
						$('#table_report_analisis_jumlah_penerimaan_detail_tanggal').DataTable().destroy();
					}

					$('#table_report_analisis_jumlah_penerimaan_detail_tanggal > tbody').empty('');

					if (response.length != 0) {

						$('#table_report_analisis_jumlah_penerimaan_detail_tanggal').fadeOut("slow", function() {
							$(this).hide();

						}).fadeIn("slow", function() {
							$(this).show();

							if (response.length != 0) {

								$.each(response, function(i, v) {

									$("#table_report_analisis_jumlah_penerimaan_detail_tanggal > tbody").append(`
										<tr>
											<td style="vertical-align: middle;text-align:left;">${v.tanggal}</td>
											<td style="vertical-align: middle;text-align:left;">${v.penerimaan_surat_jalan_nopol}</td>
											<td style="vertical-align: middle;text-align:left;">${v.client_wms_nama}</td>
											<td style="vertical-align: middle;text-align:left;">${v.principle_kode}</td>
											<td style="vertical-align: middle;text-align:right;">
												<button class="btn btn-link" onclick="Get_detail_analisis_jumlah_penerimaan_by_tipe('','','${v.tanggal}','${v.penerimaan_surat_jalan_nopol}','ditolak')">${v.sku_jumlah_barang_terima_comp_ctn_ditolak}</button>
											</td>
											<td style="vertical-align: middle;text-align:right;">
												<button class="btn btn-link" onclick="Get_detail_analisis_jumlah_penerimaan_by_tipe('','','${v.tanggal}','${v.penerimaan_surat_jalan_nopol}','pecah')">${v.sku_jumlah_barang_terima_comp_ctn_pecah}</button>
											</td>
											<td style="vertical-align: middle;text-align:right;">
												<button class="btn btn-link" onclick="Get_detail_analisis_jumlah_penerimaan_by_tipe('','','${v.tanggal}','${v.penerimaan_surat_jalan_nopol}','klaim')">${v.sku_jumlah_barang_terima_comp_ctn_klaim}</button>
											</td>
											<td style="vertical-align: middle;text-align:right;">
												<button class="btn btn-link" onclick="Get_detail_analisis_jumlah_penerimaan_by_tipe('','','${v.tanggal}','${v.penerimaan_surat_jalan_nopol}','retur')">${v.sku_jumlah_barang_terima_comp_ctn_retur}</button>
											</td>
											<td style="vertical-align: middle;text-align:right;">
												<button class="btn btn-link" onclick="Get_detail_analisis_jumlah_penerimaan_by_tipe('','','${v.tanggal}','${v.penerimaan_surat_jalan_nopol}','lain_lain')">${v.sku_jumlah_barang_terima_comp_ctn}</button>
											</td>
										</tr>
									`);
								});

								// console.log(tbody_str);

								$("#table_report_analisis_jumlah_penerimaan_detail_tanggal").DataTable({
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

	function Get_detail_analisis_jumlah_penerimaan_by_tipe(tahun, bulan, tanggal, nopol, reason) {

		$("#modal_detail_analisis_jumlah_penerimaan_by_tipe").modal('show');

		var perusahaan = $("#filter_perusahaan").val();
		var principle = $("#filter_principle").val();
		var tipe = $("#filter_tipe").val();

		if ($.fn.DataTable.isDataTable('#table_report_analisis_jumlah_penerimaan_detail_by_tipe')) {
			$('#table_report_analisis_jumlah_penerimaan_detail_by_tipe').DataTable().destroy();
		}

		var table = $('#table_report_analisis_jumlah_penerimaan_detail_by_tipe').DataTable({
			"processing": true,
			"serverSide": true,
			"order": [],
			"ajax": {
				"url": "<?= base_url('WMS/OperationalExcellence/PenerimaanBarangSupplier/AnalisisJumlahPenerimaan/Get_detail_analisis_jumlah_penerimaan_by_tipe') ?>",
				"type": "POST",
				"data": function(data) {
					data.filter_tahun = tahun;
					data.filter_bulan = bulan;
					data.filter_tanggal = tanggal;
					data.filter_nopol = nopol;
					data.filter_perusahaan = perusahaan;
					data.filter_principle = principle;
					data.filter_tipe = tipe;
					data.filter_reason = reason;
				}
			},
			"dom": "Blfrtip",
			"buttons": [{
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
			"columnDefs": [{
					"orderable": false,
					"targets": [0, 9]
				} // Ganti 0 dengan indeks kolom yang ingin di-nonaktifkan sorting-nya
			]
		});

		table.ajax.reload();

	}
</script>