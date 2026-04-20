<script>
	$(document).ready(function() {
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
	});


	const handlerFilterData = () => {
		const filter_tanggal = $('#filter_tanggal').val();
		const filter_divisi = $('#filter_divisi').val();

		if (filter_divisi == "") {
			message("Info", "Mohon pilih divisi terlebih dahulu", "info");
			return false;
		}


		// 1. Hancurkan DataTable jika sudah ada
		if ($.fn.DataTable.isDataTable('#table_list_aktivitas_karyawan')) {
			$('#table_list_aktivitas_karyawan').DataTable().clear().destroy();
			$('#table-head').empty();
			$('#table-body').empty();
		}

		// 2. Ajax untuk mendapatkan Header dan Data Dinamis
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/LaporanTransaksi/LaporanAktivitasPengguna/Get_report_aktivitas_karyawan') ?>",
			data: {
				filter_tanggal: filter_tanggal,
				filter_divisi: filter_divisi,
			},
			dataType: "JSON",
			beforeSend: function() {
				Swal.fire({
					title: 'Loading ...',
					html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
					showConfirmButton: false,
					allowOutsideClick: false,
					allowEscapeKey: false,
					allowEnterKey: false,
				});
			},
			success: function(response) {
				let headHtml = '';
				let bodyHtml = '';

				if (response.length > 0) {
					// Generate Header Dinamis berdasarkan key object pertama
					const columns = Object.keys(response[0]);
					headHtml += '<tr class="bg-primary">';
					columns.forEach(col => {
						let label = col.replace(/_/g, ' ').toUpperCase();
						if (label != "KARYAWAN ID" && label != "KARYAWAN DIVISI ID") {
							headHtml += `<th class="text-center" style="color:white;">${label}</th>`;
						}
					});
					headHtml += '</tr>';

					// Generate Baris Data Dinamis
					response.forEach((row, i) => {
						let karyawan_id = "";
						let karyawan_nama = "";
						let karyawan_divisi_id = "";
						let karyawan_divisi_nama = "";

						bodyHtml += '<tr>';
						columns.forEach(col => {
							let val = row[col] === null ? "" : row[col];
							let tipeBersih = col.replace(/qty_pcs_|qty_|doc_/gi, '').replace(/_/g, ' ').trim().toUpperCase();

							// Logika Format Khusus (Contoh: format angka untuk kolom tertentu)
							if (col.toLowerCase().includes('qty') || col.toLowerCase().includes('stock')) {
								val = !isNaN(val) && val !== "" ? parseFloat(val).toLocaleString('id-ID') : val;
							}

							if (tipeBersih != "KARYAWAN ID" && tipeBersih != "KARYAWAN DIVISI ID") {
								if (tipeBersih == "KARYAWAN NAMA") {
									karyawan_nama = val;
									bodyHtml += `<td class="text-left">${val}</td>`;
								} else if (tipeBersih == "KARYAWAN DIVISI NAMA") {
									karyawan_divisi_nama = val;
									bodyHtml += `<td class="text-left">${val}</td>`;
								} else {
									bodyHtml += `<td class="text-right">
										<button class="btn btn-link" onclick="handlerFilterDataDetail('${karyawan_id}','${karyawan_nama}','${karyawan_divisi_id}','${karyawan_divisi_nama}','${tipeBersih}')">${val}
									</td>`;
								}
							} else {
								if (tipeBersih == "KARYAWAN ID") {
									karyawan_id = val;
								} else if (tipeBersih == "KARYAWAN DIVISI ID") {
									karyawan_divisi_id = val;
								}
							}
						});
						bodyHtml += '</tr>';
					});

					$('#table-head').html(headHtml);
					$('#table-body').html(bodyHtml);

					// 3. Inisialisasi DataTable setelah HTML terbentuk
					$('#table_list_aktivitas_karyawan').DataTable({
						scrollX: true,
						dom: "Blfrtip",
						lengthMenu: [
							[50, 100, 200, 1000],
							[50, 100, 200, 1000]
						],
						buttons: [{
								extend: "print",
								text: '<i class="fa fa-print"></i> Print',
								className: 'btn btn-warning'
							},
							{
								extend: 'excelHtml5',
								text: '<i class="fa fa-file-excel-o"></i> Excel',
								className: 'btn btn-success'
							},
							{
								extend: 'pdfHtml5',
								text: '<i class="fa fa-file-pdf-o"></i> PDF',
								className: 'btn btn-danger',
								orientation: 'landscape'
							}
						],
						fixedColumns: {
							leftColumns: 0 // Sesuaikan berapa kolom yang mau dikunci
						}
					});
				} else {
					$('#table-body').html('<tr><td colspan="100%" class="text-center">Data tidak ditemukan</td></tr>');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				message("Error", "Error 500 Internal Server Connection Failure", "error");
			},
			complete: function() {
				Swal.close();
			}
		});
	};

	const handlerFilterDataDetail = (karyawan_id, karyawan_nama, karyawan_divisi_id, karyawan_divisi_nama, tipe) => {
		const prefixTipe = tipe.replace(/ /g, '_').toLowerCase();
		const prefixModal = "modal_detail_" + prefixTipe;
		const prefixTable = "table_detail_" + prefixTipe;

		$(`#karyawan_nama_${prefixTipe}`).val(karyawan_nama);
		$(`#divisi_${prefixTipe}`).val(karyawan_divisi_nama);

		$(`#${prefixModal}`).modal('show');

		if (tipe == "FDJR") {
			if ($.fn.DataTable.isDataTable(`#${prefixTable}`)) {
				$(`#${prefixTable}`).DataTable().clear().destroy();
			}
			$(`#${prefixTable} tbody`).empty();

			$(`#${prefixTable}`).DataTable({
				"scrollX": true,
				"paging": true,
				"searching": true,
				"ordering": true,
				"processing": true,
				"serverSide": true,
				"lengthMenu": [
					[50, 100, 150, 1000],
					[50, 100, 150, 1000]
				],
				"ajax": {
					url: "<?= base_url('WMS/LaporanTransaksi/LaporanAktivitasPengguna/Get_report_aktivitas_karyawan_detail') ?>",
					type: "POST",
					dataType: "json",
					data: function(d) {
						// Gunakan selector jQuery jika filter_tanggal adalah id input
						d.filter_tanggal = $('#filter_tanggal').val() || '';
						d.karyawan_id = karyawan_id;
						d.tipe = tipe;
					},
					beforeSend: function() {
						Swal.fire({
							title: 'Loading ...',
							html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
							showConfirmButton: false,
							allowOutsideClick: false
						});
					},
					complete: function() {
						Swal.close();
					},
				},
				"columns": [{
						data: null,
						render: (data, type, row, meta) => meta.row + 1
					},
					{
						data: 'delivery_order_batch_kode'
					},
					{
						data: 'delivery_order_batch_tanggal_kirim'
					},
					{
						data: 'delivery_order_kode'
					},
					{
						data: 'sku_kode'
					},
					{
						data: 'sku_nama_produk'
					},
					{
						data: 'QtyPcs',
						render: (data, type, row) => {
							let total = parseFloat(data) || 0;
							return total === 0 ? "0" : total.toFixed(0);
						}
					},
					{
						data: 'QtyCtn',
						render: (data, type, row) => {
							let total = parseFloat(data) || 0;
							return total === 0 ? "0.00" : total.toFixed(4);
						}
					},
					{
						data: 'QtyKirimPcs',
						render: (data, type, row) => {
							let total = parseFloat(data) || 0;
							return total === 0 ? "0" : total.toFixed(0);
						}
					},
					{
						data: 'QtyKirimCtn',
						render: (data, type, row) => {
							let total = parseFloat(data) || 0;
							return total === 0 ? "0.00" : total.toFixed(4);
						}
					},
					{
						data: null,
						render: (data, type, row) => (parseFloat(row.QtyPcs) || 0) - (parseFloat(row.QtyKirimPcs) || 0).toFixed(0)
					},
					{
						data: null,
						render: (data, type, row) => (parseFloat(row.QtyCtn) || 0) - (parseFloat(row.QtyKirimCtn) || 0)
					},
					{
						data: null,
						render: (data, type, row) => {
							let total = parseFloat(row.QtyPcs) || 0;
							let kirim = parseFloat(row.QtyKirimPcs) || 0;
							return total === 0 ? "0.00%" : ((kirim / total) * 100).toFixed(2) + "%";
						}
					},
				],
				"columnDefs": [{
						targets: 0,
						className: 'text-center',
						searchable: false,
						orderable: false
					},
					{
						targets: [1, 2, 3, 4, 5],
						className: 'text-left'
					},
					{
						targets: [6, 7, 8, 9],
						className: 'text-right'
					},
					{
						targets: 10,
						className: 'text-right',
						searchable: false,
						orderable: false // Hasil kalkulasi sebaiknya tidak bisa di-sort server-side
					},
					{
						targets: 11,
						className: 'text-right',
						searchable: false,
						orderable: false
					},
					{
						targets: 12,
						className: 'text-right',
						searchable: false,
						orderable: false
					}
				],
				dom: "Blfrtip",
				buttons: [{
					text: '<i class="fa fa-file-excel-o"></i> Excel',
					className: 'btn btn-success',
					action: function(e, dt, node, config) {
						const filter_tgl = $('#filter_tanggal').val() || '';
						const cari = dt.search();

						// Buat URL
						const url = "<?= base_url('WMS/LaporanTransaksi/LaporanAktivitasPengguna/ExportExcelDetail') ?>?" +
							"karyawan_id=" + karyawan_id +
							"&tipe=" + tipe +
							"&filter_tanggal=" + filter_tgl +
							"&search=" + encodeURIComponent(cari);

						Swal.fire({
							title: 'Memproses Export...',
							text: 'Harap tunggu sebentar.',
							allowOutsideClick: false,
							didOpen: () => {
								Swal.showLoading();
							}
						});

						// Gunakan Fetch API untuk menangani Blob dan Error
						fetch(url)
							.then(async response => {
								if (!response.ok) {
									// Jika error, ambil pesan JSON-nya
									const errorData = await response.json();
									throw new Error(errorData.message || "Terjadi kesalahan sistem.");
								}
								return response.blob();
							})
							.then(blob => {
								// Jika sukses, buat link download manual
								const urlBlob = window.URL.createObjectURL(blob);
								const a = document.createElement('a');
								a.href = urlBlob;
								a.download = "Laporan_Detail_" + tipe + ".xlsx";
								document.body.appendChild(a);
								a.click();
								a.remove();
								Swal.close();
							})
							.catch(error => {
								Swal.fire({
									icon: 'error',
									title: 'Export Gagal',
									text: error.message
								});
							});
					}
				}],
				initComplete: function() {
					var parent_dt = $(`#${prefixTable}`).closest('.dataTables_wrapper');
					var input = parent_dt.find('.dataTables_filter input').unbind();
					var self = this.api();

					var $searchButton = $('<button class="btn btn-flat btn-success btn-sm mb-0 mr-0 ml-5 btn-search-dt">')
						.html('<i class="fa fa-fw fa-search">')
						.click(() => self.search(input.val()).draw());

					var $clearButton = $('<button class="btn btn-flat btn-warning btn-sm mb-0 mr-0 ml-5 btn-reset-dt">')
						.html('<i class="fa fa-fw fa-recycle">')
						.click(() => {
							input.val('');
							$searchButton.click();
						});

					parent_dt.find('.dataTables_filter').append($searchButton, $clearButton);

					input.keypress(function(e) {
						if (e.which == 13) {
							$searchButton.click();
							return false;
						}
					});
				},
			});
		} else if (tipe == "BKB") {
			if ($.fn.DataTable.isDataTable(`#${prefixTable}`)) {
				$(`#${prefixTable}`).DataTable().clear().destroy();
			}
			$(`#${prefixTable} tbody`).empty();

			$(`#${prefixTable}`).DataTable({
				"scrollX": true,
				"paging": true,
				"searching": true,
				"ordering": true,
				"processing": true,
				"serverSide": true,
				"lengthMenu": [
					[50, 100, 150, 1000],
					[50, 100, 150, 1000]
				],
				"ajax": {
					url: "<?= base_url('WMS/LaporanTransaksi/LaporanAktivitasPengguna/Get_report_aktivitas_karyawan_detail') ?>",
					type: "POST",
					dataType: "json",
					data: function(d) {
						// Gunakan selector jQuery jika filter_tanggal adalah id input
						d.filter_tanggal = $('#filter_tanggal').val() || '';
						d.karyawan_id = karyawan_id;
						d.tipe = tipe;
					},
					beforeSend: function() {
						Swal.fire({
							title: 'Loading ...',
							html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
							showConfirmButton: false,
							allowOutsideClick: false
						});
					},
					complete: function() {
						Swal.close();
					},
				},
				"columns": [{
						data: null,
						render: (data, type, row, meta) => meta.row + 1
					},
					{
						data: 'NoDokumen'
					},
					{
						data: 'Tanggal'
					},
					{
						data: 'sku_kode'
					},
					{
						data: 'sku_nama_produk'
					},
					{
						data: 'sku_stock_expired_date'
					},
					{
						data: 'QtyPlanPcs',
						render: (data, type, row) => {
							let total = parseFloat(data) || 0;
							return total === 0 ? "0" : total.toFixed(0);
						}
					},
					{
						data: 'QtyPlanCtn',
						render: (data, type, row) => {
							let total = parseFloat(data) || 0;
							return total === 0 ? "0.00" : total.toFixed(4);
						}
					},
					{
						data: 'QtyAmbilPcs',
						render: (data, type, row) => {
							let total = parseFloat(data) || 0;
							return total === 0 ? "0" : total.toFixed(0);
						}
					},
					{
						data: 'QtyAmbilCtn',
						render: (data, type, row) => {
							let total = parseFloat(data) || 0;
							return total === 0 ? "0.00" : total.toFixed(4);
						}
					},
				],
				"columnDefs": [{
						targets: 0,
						className: 'text-center',
						searchable: false,
						orderable: false
					},
					{
						targets: [1, 2, 3, 4, 5],
						className: 'text-left'
					},
					{
						targets: [6, 7, 8, 9],
						className: 'text-right'
					},
				],
				dom: "Blfrtip",
				buttons: [{
					text: '<i class="fa fa-file-excel-o"></i> Excel',
					className: 'btn btn-success',
					action: function(e, dt, node, config) {
						const filter_tgl = $('#filter_tanggal').val() || '';
						const cari = dt.search();

						// Buat URL
						const url = "<?= base_url('WMS/LaporanTransaksi/LaporanAktivitasPengguna/ExportExcelDetail') ?>?" +
							"karyawan_id=" + karyawan_id +
							"&tipe=" + tipe +
							"&filter_tanggal=" + filter_tgl +
							"&search=" + encodeURIComponent(cari);

						Swal.fire({
							title: 'Memproses Export...',
							text: 'Harap tunggu sebentar.',
							allowOutsideClick: false,
							didOpen: () => {
								Swal.showLoading();
							}
						});

						// Gunakan Fetch API untuk menangani Blob dan Error
						fetch(url)
							.then(async response => {
								if (!response.ok) {
									// Jika error, ambil pesan JSON-nya
									const errorData = await response.json();
									throw new Error(errorData.message || "Terjadi kesalahan sistem.");
								}
								return response.blob();
							})
							.then(blob => {
								// Jika sukses, buat link download manual
								const urlBlob = window.URL.createObjectURL(blob);
								const a = document.createElement('a');
								a.href = urlBlob;
								a.download = "Laporan_Detail_" + tipe + ".xlsx";
								document.body.appendChild(a);
								a.click();
								a.remove();
								Swal.close();
							})
							.catch(error => {
								Swal.fire({
									icon: 'error',
									title: 'Export Gagal',
									text: error.message
								});
							});
					}
				}],
				initComplete: function() {
					var parent_dt = $(`#${prefixTable}`).closest('.dataTables_wrapper');
					var input = parent_dt.find('.dataTables_filter input').unbind();
					var self = this.api();

					var $searchButton = $('<button class="btn btn-flat btn-success btn-sm mb-0 mr-0 ml-5 btn-search-dt">')
						.html('<i class="fa fa-fw fa-search">')
						.click(() => self.search(input.val()).draw());

					var $clearButton = $('<button class="btn btn-flat btn-warning btn-sm mb-0 mr-0 ml-5 btn-reset-dt">')
						.html('<i class="fa fa-fw fa-recycle">')
						.click(() => {
							input.val('');
							$searchButton.click();
						});

					parent_dt.find('.dataTables_filter').append($searchButton, $clearButton);

					input.keypress(function(e) {
						if (e.which == 10) {
							$searchButton.click();
							return false;
						}
					});
				},
			});
		} else if (tipe == "PB") {
			if ($.fn.DataTable.isDataTable(`#${prefixTable}`)) {
				$(`#${prefixTable}`).DataTable().clear().destroy();
			}
			$(`#${prefixTable} tbody`).empty();

			$(`#${prefixTable}`).DataTable({
				"scrollX": true,
				"paging": true,
				"searching": true,
				"ordering": true,
				"processing": true,
				"serverSide": true,
				"lengthMenu": [
					[50, 100, 150, 1000],
					[50, 100, 150, 1000]
				],
				"ajax": {
					url: "<?= base_url('WMS/LaporanTransaksi/LaporanAktivitasPengguna/Get_report_aktivitas_karyawan_detail') ?>",
					type: "POST",
					dataType: "json",
					data: function(d) {
						// Gunakan selector jQuery jika filter_tanggal adalah id input
						d.filter_tanggal = $('#filter_tanggal').val() || '';
						d.karyawan_id = karyawan_id;
						d.tipe = tipe;
					},
					beforeSend: function() {
						Swal.fire({
							title: 'Loading ...',
							html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
							showConfirmButton: false,
							allowOutsideClick: false
						});
					},
					complete: function() {
						Swal.close();
					},
				},
				"columns": [{
						data: null,
						render: (data, type, row, meta) => meta.row + 1
					},
					{
						data: 'NoDokumen'
					},
					{
						data: 'Tanggal'
					},
					{
						data: 'sku_kode'
					},
					{
						data: 'sku_nama_produk'
					},
					{
						data: 'sku_exp_date'
					},
					{
						data: 'QtyPcs',
						render: (data, type, row) => {
							let total = parseFloat(data) || 0;
							return total === 0 ? "0" : total.toFixed(0);
						}
					},
					{
						data: 'QtyCtn',
						render: (data, type, row) => {
							let total = parseFloat(data) || 0;
							return total === 0 ? "0.00" : total.toFixed(4);
						}
					},
					{
						data: 'QtyTerimaPcs',
						render: (data, type, row) => {
							let total = parseFloat(data) || 0;
							return total === 0 ? "0" : total.toFixed(0);
						}
					},
					{
						data: 'QtyTerimaCtn',
						render: (data, type, row) => {
							let total = parseFloat(data) || 0;
							return total === 0 ? "0.00" : total.toFixed(4);
						}
					},
				],
				"columnDefs": [{
						targets: 0,
						className: 'text-center',
						searchable: false,
						orderable: false
					},
					{
						targets: [1, 2, 3, 4, 5],
						className: 'text-left'
					},
					{
						targets: [6, 7, 8, 9],
						className: 'text-right'
					},
				],
				dom: "Blfrtip",
				buttons: [{
					text: '<i class="fa fa-file-excel-o"></i> Excel',
					className: 'btn btn-success',
					action: function(e, dt, node, config) {
						const filter_tgl = $('#filter_tanggal').val() || '';
						const cari = dt.search();

						// Buat URL
						const url = "<?= base_url('WMS/LaporanTransaksi/LaporanAktivitasPengguna/ExportExcelDetail') ?>?" +
							"karyawan_id=" + karyawan_id +
							"&tipe=" + tipe +
							"&filter_tanggal=" + filter_tgl +
							"&search=" + encodeURIComponent(cari);

						Swal.fire({
							title: 'Memproses Export...',
							text: 'Harap tunggu sebentar.',
							allowOutsideClick: false,
							didOpen: () => {
								Swal.showLoading();
							}
						});

						// Gunakan Fetch API untuk menangani Blob dan Error
						fetch(url)
							.then(async response => {
								if (!response.ok) {
									// Jika error, ambil pesan JSON-nya
									const errorData = await response.json();
									throw new Error(errorData.message || "Terjadi kesalahan sistem.");
								}
								return response.blob();
							})
							.then(blob => {
								// Jika sukses, buat link download manual
								const urlBlob = window.URL.createObjectURL(blob);
								const a = document.createElement('a');
								a.href = urlBlob;
								a.download = "Laporan_Detail_" + tipe + ".xlsx";
								document.body.appendChild(a);
								a.click();
								a.remove();
								Swal.close();
							})
							.catch(error => {
								Swal.fire({
									icon: 'error',
									title: 'Export Gagal',
									text: error.message
								});
							});
					}
				}],
				initComplete: function() {
					var parent_dt = $(`#${prefixTable}`).closest('.dataTables_wrapper');
					var input = parent_dt.find('.dataTables_filter input').unbind();
					var self = this.api();

					var $searchButton = $('<button class="btn btn-flat btn-success btn-sm mb-0 mr-0 ml-5 btn-search-dt">')
						.html('<i class="fa fa-fw fa-search">')
						.click(() => self.search(input.val()).draw());

					var $clearButton = $('<button class="btn btn-flat btn-warning btn-sm mb-0 mr-0 ml-5 btn-reset-dt">')
						.html('<i class="fa fa-fw fa-recycle">')
						.click(() => {
							input.val('');
							$searchButton.click();
						});

					parent_dt.find('.dataTables_filter').append($searchButton, $clearButton);

					input.keypress(function(e) {
						if (e.which == 10) {
							$searchButton.click();
							return false;
						}
					});
				},
			});
		} else if (tipe == "BTB RETUR") {
			if ($.fn.DataTable.isDataTable(`#${prefixTable}`)) {
				$(`#${prefixTable}`).DataTable().clear().destroy();
			}
			$(`#${prefixTable} tbody`).empty();

			$(`#${prefixTable}`).DataTable({
				"scrollX": true,
				"paging": true,
				"searching": true,
				"ordering": true,
				"processing": true,
				"serverSide": true,
				"lengthMenu": [
					[50, 100, 150, 1000],
					[50, 100, 150, 1000]
				],
				"ajax": {
					url: "<?= base_url('WMS/LaporanTransaksi/LaporanAktivitasPengguna/Get_report_aktivitas_karyawan_detail') ?>",
					type: "POST",
					dataType: "json",
					data: function(d) {
						// Gunakan selector jQuery jika filter_tanggal adalah id input
						d.filter_tanggal = $('#filter_tanggal').val() || '';
						d.karyawan_id = karyawan_id;
						d.tipe = tipe;
					},
					beforeSend: function() {
						Swal.fire({
							title: 'Loading ...',
							html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
							showConfirmButton: false,
							allowOutsideClick: false
						});
					},
					complete: function() {
						Swal.close();
					},
				},
				"columns": [{
						data: null,
						render: (data, type, row, meta) => meta.row + 1
					},
					{
						data: 'NoDokumen'
					},
					{
						data: 'Tanggal'
					},
					{
						data: 'sku_kode'
					},
					{
						data: 'sku_nama_produk'
					},
					{
						data: 'sku_expired_date'
					},
					{
						data: 'QtyPcs',
						render: (data, type, row) => {
							let total = parseFloat(data) || 0;
							return total === 0 ? "0" : total.toFixed(0);
						}
					},
					{
						data: 'QtyCtn',
						render: (data, type, row) => {
							let total = parseFloat(data) || 0;
							return total === 0 ? "0.00" : total.toFixed(4);
						}
					},
					{
						data: 'QtyTerimaPcs',
						render: (data, type, row) => {
							let total = parseFloat(data) || 0;
							return total === 0 ? "0" : total.toFixed(0);
						}
					},
					{
						data: 'QtyTerimaCtn',
						render: (data, type, row) => {
							let total = parseFloat(data) || 0;
							return total === 0 ? "0.00" : total.toFixed(4);
						}
					},
				],
				"columnDefs": [{
						targets: 0,
						className: 'text-center',
						searchable: false,
						orderable: false
					},
					{
						targets: [1, 2, 3, 4, 5],
						className: 'text-left'
					},
					{
						targets: [6, 7, 8, 9],
						className: 'text-right'
					},
				],
				dom: "Blfrtip",
				buttons: [{
					text: '<i class="fa fa-file-excel-o"></i> Excel',
					className: 'btn btn-success',
					action: function(e, dt, node, config) {
						const filter_tgl = $('#filter_tanggal').val() || '';
						const cari = dt.search();

						// Buat URL
						const url = "<?= base_url('WMS/LaporanTransaksi/LaporanAktivitasPengguna/ExportExcelDetail') ?>?" +
							"karyawan_id=" + karyawan_id +
							"&tipe=" + tipe +
							"&filter_tanggal=" + filter_tgl +
							"&search=" + encodeURIComponent(cari);

						Swal.fire({
							title: 'Memproses Export...',
							text: 'Harap tunggu sebentar.',
							allowOutsideClick: false,
							didOpen: () => {
								Swal.showLoading();
							}
						});

						// Gunakan Fetch API untuk menangani Blob dan Error
						fetch(url)
							.then(async response => {
								if (!response.ok) {
									// Jika error, ambil pesan JSON-nya
									const errorData = await response.json();
									throw new Error(errorData.message || "Terjadi kesalahan sistem.");
								}
								return response.blob();
							})
							.then(blob => {
								// Jika sukses, buat link download manual
								const urlBlob = window.URL.createObjectURL(blob);
								const a = document.createElement('a');
								a.href = urlBlob;
								a.download = "Laporan_Detail_" + tipe + ".xlsx";
								document.body.appendChild(a);
								a.click();
								a.remove();
								Swal.close();
							})
							.catch(error => {
								Swal.fire({
									icon: 'error',
									title: 'Export Gagal',
									text: error.message
								});
							});
					}
				}],
				initComplete: function() {
					var parent_dt = $(`#${prefixTable}`).closest('.dataTables_wrapper');
					var input = parent_dt.find('.dataTables_filter input').unbind();
					var self = this.api();

					var $searchButton = $('<button class="btn btn-flat btn-success btn-sm mb-0 mr-0 ml-5 btn-search-dt">')
						.html('<i class="fa fa-fw fa-search">')
						.click(() => self.search(input.val()).draw());

					var $clearButton = $('<button class="btn btn-flat btn-warning btn-sm mb-0 mr-0 ml-5 btn-reset-dt">')
						.html('<i class="fa fa-fw fa-recycle">')
						.click(() => {
							input.val('');
							$searchButton.click();
						});

					parent_dt.find('.dataTables_filter').append($searchButton, $clearButton);

					input.keypress(function(e) {
						if (e.which == 10) {
							$searchButton.click();
							return false;
						}
					});
				},
			});
		} else if (tipe == "STOK OPNAM") {
			if ($.fn.DataTable.isDataTable(`#${prefixTable}`)) {
				$(`#${prefixTable}`).DataTable().clear().destroy();
			}
			$(`#${prefixTable} tbody`).empty();

			$(`#${prefixTable}`).DataTable({
				"scrollX": true,
				"paging": true,
				"searching": true,
				"ordering": true,
				"processing": true,
				"serverSide": true,
				"lengthMenu": [
					[50, 100, 150, 1000],
					[50, 100, 150, 1000]
				],
				"ajax": {
					url: "<?= base_url('WMS/LaporanTransaksi/LaporanAktivitasPengguna/Get_report_aktivitas_karyawan_detail') ?>",
					type: "POST",
					dataType: "json",
					data: function(d) {
						// Gunakan selector jQuery jika filter_tanggal adalah id input
						d.filter_tanggal = $('#filter_tanggal').val() || '';
						d.karyawan_id = karyawan_id;
						d.tipe = tipe;
					},
					beforeSend: function() {
						Swal.fire({
							title: 'Loading ...',
							html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
							showConfirmButton: false,
							allowOutsideClick: false
						});
					},
					complete: function() {
						Swal.close();
					},
				},
				"columns": [{
						data: null,
						render: (data, type, row, meta) => meta.row + 1
					},
					{
						data: 'NoDokumen'
					},
					{
						data: 'Tanggal'
					},
					{
						data: 'sku_kode'
					},
					{
						data: 'sku_nama_produk'
					},
					{
						data: 'sku_expired_date'
					},
					{
						data: 'QtyAktualPcs',
						render: (data, type, row) => {
							let total = parseFloat(data) || 0;
							return total === 0 ? "0" : total.toFixed(0);
						}
					},
					{
						data: 'QtyAktualCtn',
						render: (data, type, row) => {
							let total = parseFloat(data) || 0;
							return total === 0 ? "0.00" : total.toFixed(4);
						}
					},
					{
						data: 'QtySistemPcs',
						render: (data, type, row) => {
							let total = parseFloat(data) || 0;
							return total === 0 ? "0" : total.toFixed(0);
						}
					},
					{
						data: 'QtySistemCtn',
						render: (data, type, row) => {
							let total = parseFloat(data) || 0;
							return total === 0 ? "0.00" : total.toFixed(4);
						}
					},
				],
				"columnDefs": [{
						targets: 0,
						className: 'text-center',
						searchable: false,
						orderable: false
					},
					{
						targets: [1, 2, 3, 4, 5],
						className: 'text-left'
					},
					{
						targets: [6, 7, 8, 9],
						className: 'text-right'
					},
				],
				dom: "Blfrtip",
				buttons: [{
					text: '<i class="fa fa-file-excel-o"></i> Excel',
					className: 'btn btn-success',
					action: function(e, dt, node, config) {
						const filter_tgl = $('#filter_tanggal').val() || '';
						const cari = dt.search();

						// Buat URL
						const url = "<?= base_url('WMS/LaporanTransaksi/LaporanAktivitasPengguna/ExportExcelDetail') ?>?" +
							"karyawan_id=" + karyawan_id +
							"&tipe=" + tipe +
							"&filter_tanggal=" + filter_tgl +
							"&search=" + encodeURIComponent(cari);

						Swal.fire({
							title: 'Memproses Export...',
							text: 'Harap tunggu sebentar.',
							allowOutsideClick: false,
							didOpen: () => {
								Swal.showLoading();
							}
						});

						// Gunakan Fetch API untuk menangani Blob dan Error
						fetch(url)
							.then(async response => {
								if (!response.ok) {
									// Jika error, ambil pesan JSON-nya
									const errorData = await response.json();
									throw new Error(errorData.message || "Terjadi kesalahan sistem.");
								}
								return response.blob();
							})
							.then(blob => {
								// Jika sukses, buat link download manual
								const urlBlob = window.URL.createObjectURL(blob);
								const a = document.createElement('a');
								a.href = urlBlob;
								a.download = "Laporan_Detail_" + tipe + ".xlsx";
								document.body.appendChild(a);
								a.click();
								a.remove();
								Swal.close();
							})
							.catch(error => {
								Swal.fire({
									icon: 'error',
									title: 'Export Gagal',
									text: error.message
								});
							});
					}
				}],
				initComplete: function() {
					var parent_dt = $(`#${prefixTable}`).closest('.dataTables_wrapper');
					var input = parent_dt.find('.dataTables_filter input').unbind();
					var self = this.api();

					var $searchButton = $('<button class="btn btn-flat btn-success btn-sm mb-0 mr-0 ml-5 btn-search-dt">')
						.html('<i class="fa fa-fw fa-search">')
						.click(() => self.search(input.val()).draw());

					var $clearButton = $('<button class="btn btn-flat btn-warning btn-sm mb-0 mr-0 ml-5 btn-reset-dt">')
						.html('<i class="fa fa-fw fa-recycle">')
						.click(() => {
							input.val('');
							$searchButton.click();
						});

					parent_dt.find('.dataTables_filter').append($searchButton, $clearButton);

					input.keypress(function(e) {
						if (e.which == 10) {
							$searchButton.click();
							return false;
						}
					});
				},
			});
		} else if (tipe == "KOREKSI STOK") {
			if ($.fn.DataTable.isDataTable(`#${prefixTable}`)) {
				$(`#${prefixTable}`).DataTable().clear().destroy();
			}
			$(`#${prefixTable} tbody`).empty();

			$(`#${prefixTable}`).DataTable({
				"scrollX": true,
				"paging": true,
				"searching": true,
				"ordering": true,
				"processing": true,
				"serverSide": true,
				"lengthMenu": [
					[50, 100, 150, 1000],
					[50, 100, 150, 1000]
				],
				"ajax": {
					url: "<?= base_url('WMS/LaporanTransaksi/LaporanAktivitasPengguna/Get_report_aktivitas_karyawan_detail') ?>",
					type: "POST",
					dataType: "json",
					data: function(d) {
						// Gunakan selector jQuery jika filter_tanggal adalah id input
						d.filter_tanggal = $('#filter_tanggal').val() || '';
						d.karyawan_id = karyawan_id;
						d.tipe = tipe;
					},
					beforeSend: function() {
						Swal.fire({
							title: 'Loading ...',
							html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
							showConfirmButton: false,
							allowOutsideClick: false
						});
					},
					complete: function() {
						Swal.close();
					},
				},
				"columns": [{
						data: null,
						render: (data, type, row, meta) => meta.row + 1
					},
					{
						data: 'NoDokumen'
					},
					{
						data: 'Tanggal'
					},
					{
						data: 'sku_kode'
					},
					{
						data: 'sku_nama_produk'
					},
					{
						data: 'sku_stock_expired_date'
					},
					{
						data: 'QtyPlanPcs',
						render: (data, type, row) => {
							let total = parseFloat(data) || 0;
							return total === 0 ? "0" : total.toFixed(0);
						}
					},
					{
						data: 'QtyPlanCtn',
						render: (data, type, row) => {
							let total = parseFloat(data) || 0;
							return total === 0 ? "0.00" : total.toFixed(4);
						}
					},
					{
						data: 'QtyAktualPcs',
						render: (data, type, row) => {
							let total = parseFloat(data) || 0;
							return total === 0 ? "0" : total.toFixed(0);
						}
					},
					{
						data: 'QtyAktualCtn',
						render: (data, type, row) => {
							let total = parseFloat(data) || 0;
							return total === 0 ? "0.00" : total.toFixed(4);
						}
					},
				],
				"columnDefs": [{
						targets: 0,
						className: 'text-center',
						searchable: false,
						orderable: false
					},
					{
						targets: [1, 2, 3, 4, 5],
						className: 'text-left'
					},
					{
						targets: [6, 7, 8, 9],
						className: 'text-right'
					},
				],
				dom: "Blfrtip",
				buttons: [{
					text: '<i class="fa fa-file-excel-o"></i> Excel',
					className: 'btn btn-success',
					action: function(e, dt, node, config) {
						const filter_tgl = $('#filter_tanggal').val() || '';
						const cari = dt.search();

						// Buat URL
						const url = "<?= base_url('WMS/LaporanTransaksi/LaporanAktivitasPengguna/ExportExcelDetail') ?>?" +
							"karyawan_id=" + karyawan_id +
							"&tipe=" + tipe +
							"&filter_tanggal=" + filter_tgl +
							"&search=" + encodeURIComponent(cari);

						Swal.fire({
							title: 'Memproses Export...',
							text: 'Harap tunggu sebentar.',
							allowOutsideClick: false,
							didOpen: () => {
								Swal.showLoading();
							}
						});

						// Gunakan Fetch API untuk menangani Blob dan Error
						fetch(url)
							.then(async response => {
								if (!response.ok) {
									// Jika error, ambil pesan JSON-nya
									const errorData = await response.json();
									throw new Error(errorData.message || "Terjadi kesalahan sistem.");
								}
								return response.blob();
							})
							.then(blob => {
								// Jika sukses, buat link download manual
								const urlBlob = window.URL.createObjectURL(blob);
								const a = document.createElement('a');
								a.href = urlBlob;
								a.download = "Laporan_Detail_" + tipe + ".xlsx";
								document.body.appendChild(a);
								a.click();
								a.remove();
								Swal.close();
							})
							.catch(error => {
								Swal.fire({
									icon: 'error',
									title: 'Export Gagal',
									text: error.message
								});
							});
					}
				}],
				initComplete: function() {
					var parent_dt = $(`#${prefixTable}`).closest('.dataTables_wrapper');
					var input = parent_dt.find('.dataTables_filter input').unbind();
					var self = this.api();

					var $searchButton = $('<button class="btn btn-flat btn-success btn-sm mb-0 mr-0 ml-5 btn-search-dt">')
						.html('<i class="fa fa-fw fa-search">')
						.click(() => self.search(input.val()).draw());

					var $clearButton = $('<button class="btn btn-flat btn-warning btn-sm mb-0 mr-0 ml-5 btn-reset-dt">')
						.html('<i class="fa fa-fw fa-recycle">')
						.click(() => {
							input.val('');
							$searchButton.click();
						});

					parent_dt.find('.dataTables_filter').append($searchButton, $clearButton);

					input.keypress(function(e) {
						if (e.which == 10) {
							$searchButton.click();
							return false;
						}
					});
				},
			});
		} else if (tipe == "MUTASI STOK") {
			if ($.fn.DataTable.isDataTable(`#${prefixTable}`)) {
				$(`#${prefixTable}`).DataTable().clear().destroy();
			}
			$(`#${prefixTable} tbody`).empty();

			$(`#${prefixTable}`).DataTable({
				"scrollX": true,
				"paging": true,
				"searching": true,
				"ordering": true,
				"processing": true,
				"serverSide": true,
				"lengthMenu": [
					[50, 100, 150, 1000],
					[50, 100, 150, 1000]
				],
				"ajax": {
					url: "<?= base_url('WMS/LaporanTransaksi/LaporanAktivitasPengguna/Get_report_aktivitas_karyawan_detail') ?>",
					type: "POST",
					dataType: "json",
					data: function(d) {
						// Gunakan selector jQuery jika filter_tanggal adalah id input
						d.filter_tanggal = $('#filter_tanggal').val() || '';
						d.karyawan_id = karyawan_id;
						d.tipe = tipe;
					},
					beforeSend: function() {
						Swal.fire({
							title: 'Loading ...',
							html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
							showConfirmButton: false,
							allowOutsideClick: false
						});
					},
					complete: function() {
						Swal.close();
					},
				},
				"columns": [{
						data: null,
						render: (data, type, row, meta) => meta.row + 1
					},
					{
						data: 'NoDokumen'
					},
					{
						data: 'Tanggal'
					},
					{
						data: 'sku_kode'
					},
					{
						data: 'sku_nama_produk'
					},
					{
						data: 'sku_stock_expired_date'
					},
					{
						data: 'QtyPcs',
						render: (data, type, row) => {
							let total = parseFloat(data) || 0;
							return total === 0 ? "0" : total.toFixed(0);
						}
					},
					{
						data: 'QtyCtn',
						render: (data, type, row) => {
							let total = parseFloat(data) || 0;
							return total === 0 ? "0.00" : total.toFixed(4);
						}
					},
				],
				"columnDefs": [{
						targets: 0,
						className: 'text-center',
						searchable: false,
						orderable: false
					},
					{
						targets: [1, 2, 3, 4, 5],
						className: 'text-left'
					},
					{
						targets: [6, 7],
						className: 'text-right'
					},
				],
				dom: "Blfrtip",
				buttons: [{
					text: '<i class="fa fa-file-excel-o"></i> Excel',
					className: 'btn btn-success',
					action: function(e, dt, node, config) {
						const filter_tgl = $('#filter_tanggal').val() || '';
						const cari = dt.search();

						// Buat URL
						const url = "<?= base_url('WMS/LaporanTransaksi/LaporanAktivitasPengguna/ExportExcelDetail') ?>?" +
							"karyawan_id=" + karyawan_id +
							"&tipe=" + tipe +
							"&filter_tanggal=" + filter_tgl +
							"&search=" + encodeURIComponent(cari);

						Swal.fire({
							title: 'Memproses Export...',
							text: 'Harap tunggu sebentar.',
							allowOutsideClick: false,
							didOpen: () => {
								Swal.showLoading();
							}
						});

						// Gunakan Fetch API untuk menangani Blob dan Error
						fetch(url)
							.then(async response => {
								if (!response.ok) {
									// Jika error, ambil pesan JSON-nya
									const errorData = await response.json();
									throw new Error(errorData.message || "Terjadi kesalahan sistem.");
								}
								return response.blob();
							})
							.then(blob => {
								// Jika sukses, buat link download manual
								const urlBlob = window.URL.createObjectURL(blob);
								const a = document.createElement('a');
								a.href = urlBlob;
								a.download = "Laporan_Detail_" + tipe + ".xlsx";
								document.body.appendChild(a);
								a.click();
								a.remove();
								Swal.close();
							})
							.catch(error => {
								Swal.fire({
									icon: 'error',
									title: 'Export Gagal',
									text: error.message
								});
							});
					}
				}],
				initComplete: function() {
					var parent_dt = $(`#${prefixTable}`).closest('.dataTables_wrapper');
					var input = parent_dt.find('.dataTables_filter input').unbind();
					var self = this.api();

					var $searchButton = $('<button class="btn btn-flat btn-success btn-sm mb-0 mr-0 ml-5 btn-search-dt">')
						.html('<i class="fa fa-fw fa-search">')
						.click(() => self.search(input.val()).draw());

					var $clearButton = $('<button class="btn btn-flat btn-warning btn-sm mb-0 mr-0 ml-5 btn-reset-dt">')
						.html('<i class="fa fa-fw fa-recycle">')
						.click(() => {
							input.val('');
							$searchButton.click();
						});

					parent_dt.find('.dataTables_filter').append($searchButton, $clearButton);

					input.keypress(function(e) {
						if (e.which == 10) {
							$searchButton.click();
							return false;
						}
					});
				},
			});
		} else if (tipe == "MUTASI PALLET") {
			if ($.fn.DataTable.isDataTable(`#${prefixTable}`)) {
				$(`#${prefixTable}`).DataTable().clear().destroy();
			}
			$(`#${prefixTable} tbody`).empty();

			$(`#${prefixTable}`).DataTable({
				"scrollX": true,
				"paging": true,
				"searching": true,
				"ordering": true,
				"processing": true,
				"serverSide": true,
				"lengthMenu": [
					[50, 100, 150, 1000],
					[50, 100, 150, 1000]
				],
				"ajax": {
					url: "<?= base_url('WMS/LaporanTransaksi/LaporanAktivitasPengguna/Get_report_aktivitas_karyawan_detail') ?>",
					type: "POST",
					dataType: "json",
					data: function(d) {
						// Gunakan selector jQuery jika filter_tanggal adalah id input
						d.filter_tanggal = $('#filter_tanggal').val() || '';
						d.karyawan_id = karyawan_id;
						d.tipe = tipe;
					},
					beforeSend: function() {
						Swal.fire({
							title: 'Loading ...',
							html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
							showConfirmButton: false,
							allowOutsideClick: false
						});
					},
					complete: function() {
						Swal.close();
					},
				},
				"columns": [{
						data: null,
						render: (data, type, row, meta) => meta.row + 1
					},
					{
						data: 'NoDokumen'
					},
					{
						data: 'Tanggal'
					},
					{
						data: 'pallet_kode'
					},
					{
						data: 'sku_kode'
					},
					{
						data: 'sku_nama_produk'
					},
					{
						data: 'sku_stock_expired_date'
					},
					{
						data: 'QtyPcs',
						render: (data, type, row) => {
							let total = parseFloat(data) || 0;
							return total === 0 ? "0" : total.toFixed(0);
						}
					},
					{
						data: 'QtyCtn',
						render: (data, type, row) => {
							let total = parseFloat(data) || 0;
							return total === 0 ? "0.00" : total.toFixed(4);
						}
					},
				],
				"columnDefs": [{
						targets: 0,
						className: 'text-center',
						searchable: false,
						orderable: false
					},
					{
						targets: [1, 2, 3, 4, 5, 6],
						className: 'text-left'
					},
					{
						targets: [7, 8],
						className: 'text-right'
					},
				],
				dom: "Blfrtip",
				buttons: [{
					text: '<i class="fa fa-file-excel-o"></i> Excel',
					className: 'btn btn-success',
					action: function(e, dt, node, config) {
						const filter_tgl = $('#filter_tanggal').val() || '';
						const cari = dt.search();

						// Buat URL
						const url = "<?= base_url('WMS/LaporanTransaksi/LaporanAktivitasPengguna/ExportExcelDetail') ?>?" +
							"karyawan_id=" + karyawan_id +
							"&tipe=" + tipe +
							"&filter_tanggal=" + filter_tgl +
							"&search=" + encodeURIComponent(cari);

						Swal.fire({
							title: 'Memproses Export...',
							text: 'Harap tunggu sebentar.',
							allowOutsideClick: false,
							didOpen: () => {
								Swal.showLoading();
							}
						});

						// Gunakan Fetch API untuk menangani Blob dan Error
						fetch(url)
							.then(async response => {
								if (!response.ok) {
									// Jika error, ambil pesan JSON-nya
									const errorData = await response.json();
									throw new Error(errorData.message || "Terjadi kesalahan sistem.");
								}
								return response.blob();
							})
							.then(blob => {
								// Jika sukses, buat link download manual
								const urlBlob = window.URL.createObjectURL(blob);
								const a = document.createElement('a');
								a.href = urlBlob;
								a.download = "Laporan_Detail_" + tipe + ".xlsx";
								document.body.appendChild(a);
								a.click();
								a.remove();
								Swal.close();
							})
							.catch(error => {
								Swal.fire({
									icon: 'error',
									title: 'Export Gagal',
									text: error.message
								});
							});
					}
				}],
				initComplete: function() {
					var parent_dt = $(`#${prefixTable}`).closest('.dataTables_wrapper');
					var input = parent_dt.find('.dataTables_filter input').unbind();
					var self = this.api();

					var $searchButton = $('<button class="btn btn-flat btn-success btn-sm mb-0 mr-0 ml-5 btn-search-dt">')
						.html('<i class="fa fa-fw fa-search">')
						.click(() => self.search(input.val()).draw());

					var $clearButton = $('<button class="btn btn-flat btn-warning btn-sm mb-0 mr-0 ml-5 btn-reset-dt">')
						.html('<i class="fa fa-fw fa-recycle">')
						.click(() => {
							input.val('');
							$searchButton.click();
						});

					parent_dt.find('.dataTables_filter').append($searchButton, $clearButton);

					input.keypress(function(e) {
						if (e.which == 10) {
							$searchButton.click();
							return false;
						}
					});
				},
			});
		}
	};
</script>