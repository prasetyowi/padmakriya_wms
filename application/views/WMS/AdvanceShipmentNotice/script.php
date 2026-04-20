<script type="text/javascript">
	loadingBeforeReadyPage()
	$(document).ready(function() {
		select2();

		// if ($('#filter-sj-date').length > 0) {
		//     $('#filter-sj-date').daterangepicker({
		//         'applyClass': 'btn-sm btn-success',
		//         'cancelClass': 'btn-sm btn-default',
		//         locale: {
		//             "format": "DD/MM/YYYY",
		//             applyLabel: 'Apply',
		//             cancelLabel: 'Cancel',
		//         },
		//         'startDate': '<?= ("01-m-Y") ?>',
		//         'endDate': '<?= date("t-m-Y") ?>'
		//     });
		// }
	});

	$('#btnImportASN').on('click', function() {
		var principle_id = $('#principle').val();

		if (principle_id == "") {
			message("Error!", "Pilih Principle!", "error");
			return false;
		}

		var files = $('#file').val('');

		$('#modal-import-asn').modal('show');
	});

	$('#btnImportBatch').on('click', function() {
		var principle_id = $('#principle').val();

		if (principle_id == "") {
			message("Error!", "Pilih Principle!", "error");
			return false;
		}

		var files = $('#file').val('');

		$('#modal-import-batch').modal('show');
	});

	$('#btnSimpanUpload').on('click', function() {
		var principle_id = $('#principle').val();
		var files = $('#file').prop('files');

		if (files.length == 0) {
			message("Error!", "File Kosong!", "error");
		} else {
			var form_data = new FormData();

			for (let i = 0; i < files.length; i++) {
				form_data.append('files[]', files[i]);
			}

			form_data.append('principle_id', principle_id);

			$.ajax({
				type: "POST",
				url: "<?= base_url('WMS/AdvanceShipmentNotice/ImportASN') ?>",
				data: form_data,
				async: false,
				contentType: false,
				processData: false,
				dataType: "JSON",
				beforeSend: function() {
					//Alert memuat data
					Swal.fire({
						title: '<span ><i class="fa fa-spinner fa-spin"></i> Proses Upload!</span> ',
						text: 'Mohon tunggu sebentar...',
						icon: 'warning',
						showCancelButton: false,
						showConfirmButton: false,
						allowOutsideClick: false
					})
				},
				success: function(response) {
					if (response == 0) {
						message("Gagal!", "Data tidak sesuai format", "error");
					} else {
						message("Success!", "File berhasil di upload", "success");
					}

					$('#modal-import-asn').modal('hide');

					$("#table_data_asn > tbody").empty();

					if ($.fn.DataTable.isDataTable('#table_data_asn')) {
						$('#table_data_asn').DataTable().clear();
						$('#table_data_asn').DataTable().destroy();
					}

					var no = 1;
					if (response.penerimaan != 0) {
						$.each(response.penerimaan, function(i, v) {
							$("#table_data_asn > tbody").append(`
					<tr>
					<td width="5%" class="text-center">${no++}</td>
					<td width="15%" class="text-center" name="CAPTION-DEPONAMA">${v.depo_nama}
					</td>
					<td width="10%" class="text-center" >${v.shipment_number}</td>
                    <td width="10%" class="text-center" ><button onclick="clickListDOByShipment('${v.shipment_number}', '${v.principle_id}')" class="btn btn-primary">${v.do}</button></td>
					<td width="10%" class="text-center" >${v.qty}</td>
					<td width="10%" class="text-center" ></td>
					<td width="10%" class="text-center" >${v.penerimaan_surat_jalan_status}</td>
					<td width="10%" class="text-center" name="CAPTION-ACTION">
					<button onclick="clickGenerate('${v.shipment_number}', '${v.principle_id}', '${v.do}', '${v.qty}', '')" type="button" name="btn_generate" id="btn_generate" class="btn btn-primary"><i class="fa fa-play"></i>
                    Generate</button></td>
					<td width="20%" class="text-center" name="">${v.keterangan}</td>
					</tr>
					`)
						});
						$('#table_data_asn').DataTable({
							'ordering': false
						});
					} else {
						$("#table_data_asn > tbody").append(`
						<tr>
							<td colspan="9" class="text-danger text-center">Data Kosong</td>
						</tr>
					`);
					}

				}
			})
		}
	});

	$('#btnSimpanUploadBatch').on('click', function() {
		var principle_id = $('#principle').val();
		var files = $('#fileBatch').prop('files');

		if (files.length == 0) {
			message("Error!", "File Kosong!", "error");
		} else {
			var form_data = new FormData();

			for (let i = 0; i < files.length; i++) {
				form_data.append('files[]', files[i]);
			}

			form_data.append('principle_id', principle_id);

			$.ajax({
				type: "POST",
				url: "<?= base_url('WMS/AdvanceShipmentNotice/ImportBatch') ?>",
				data: form_data,
				async: false,
				contentType: false,
				processData: false,
				dataType: "JSON",
				beforeSend: function() {
					//Alert memuat data
					Swal.fire({
						title: '<span ><i class="fa fa-spinner fa-spin"></i> Proses Upload!</span> ',
						text: 'Mohon tunggu sebentar...',
						icon: 'warning',
						showCancelButton: false,
						showConfirmButton: false,
						allowOutsideClick: false
					})
				},
				success: function(response) {
					if (response == 0) {
						message("Gagal!", "Data tidak sesuai format", "error");
					} else {
						message("Success!", "File berhasil di upload", "success");
					}

					$('#modal-import-batch').modal('hide');

					$("#table_data_asn > tbody").empty();

					if ($.fn.DataTable.isDataTable('#table_data_asn')) {
						$('#table_data_asn').DataTable().clear();
						$('#table_data_asn').DataTable().destroy();
					}

					var no = 1;
					if (response.penerimaan != 0) {
						$.each(response.penerimaan, function(i, v) {
							$("#table_data_asn > tbody").append(`
					<tr>
					<td width="5%" class="text-center">${no++}</td>
					<td width="15%" class="text-center" name="CAPTION-DEPONAMA">${v.depo_nama}
					</td>
					<td width="10%" class="text-center" >${v.shipment_number}</td>
                    <td width="10%" class="text-center" ><button onclick="clickListDOByShipment('${v.shipment_number}', '${v.principle_id}')" class="btn btn-primary">${v.do}</button></td>
					<td width="10%" class="text-center" >${v.qty}</td>
					<td width="10%" class="text-center" ></td>
					<td width="10%" class="text-center" >${v.penerimaan_surat_jalan_status}</td>
					<td width="10%" class="text-center" name="CAPTION-ACTION">
					<button onclick="clickGenerate('${v.shipment_number}', '${v.principle_id}', '${v.do}', '${v.qty}', '')" type="button" name="btn_generate" id="btn_generate" class="btn btn-primary"><i class="fa fa-play"></i>
                    Generate</button></td>
					<td width="20%" class="text-center" name="">${v.keterangan}</td>
					</tr>
					`)
						});
						$('#table_data_asn').DataTable({
							'ordering': false
						});
					} else {
						$("#table_data_asn > tbody").append(`
						<tr>
							<td colspan="9" class="text-danger text-center">Data Kosong</td>
						</tr>
					`);
					}

				}
			})
		}
	})

	$('#btn-download-asn').click(function() {
		var principle_id = $('#principle').val();
		var no = 1;

		if (principle_id == "") {
			message("Error!", "Pilih Principle!", "error");
			return false;
		}

		// $('#loadingaction').show();

		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/AdvanceShipmentNotice/GetFileFTP') ?>",
			data: {
				principle_id: principle_id
			},
			beforeSend: function() {
				//Alert memuat data
				Swal.fire({
					title: '<span ><i class="fa fa-spinner fa-spin"></i> Memuat Data!</span> ',
					text: 'Mohon tunggu sebentar...',
					icon: 'warning',
					showCancelButton: false,
					showConfirmButton: false,
					allowOutsideClick: false
				})
			},
			dataType: "JSON",
			success: function(response) {

				if (response.type != undefined) {
					message("Error!", "" + response.message + "", "error");
				} else if (response.file != undefined) {
					message("Info!", "File Tidak Tersedia", "info");
				} else {
					message("Success!", "File Berhasil Di Download", "success");
				}

				$("#table_data_asn > tbody").empty();

				if ($.fn.DataTable.isDataTable('#table_data_asn')) {
					$('#table_data_asn').DataTable().clear();
					$('#table_data_asn').DataTable().destroy();
				}

				if (response.penerimaan != 0) {
					$.each(response.penerimaan, function(i, v) {
						$("#table_data_asn > tbody").append(`
					<tr>
					<td width="5%" class="text-center">${no++}</td>
					<td width="15%" class="text-center" name="CAPTION-DEPONAMA">${v.depo_nama}
					</td>
					<td width="10%" class="text-center" >${v.shipment_number}</td>
                    <td width="10%" class="text-center" ><button onclick="clickListDOByShipment('${v.shipment_number}', '${v.principle_id}')" class="btn btn-primary">${v.do}</button></td>
					<td width="10%" class="text-center" >${v.qty}</td>
					<td width="10%" class="text-center" >${v.tglUpdate == null ? '' : v.tglUpdate}</td>
					<td width="10%" class="text-center" >${v.penerimaan_surat_jalan_status}</td>
					<td width="10%" class="text-center" name="CAPTION-ACTION">
					<button onclick="clickGenerate('${v.shipment_number}', '${v.principle_id}', '${v.do}', '${v.qty}', '${v.tglUpdate == null ? '' : v.tglUpdate}')" type="button" name="btn_generate" id="btn_generate" class="btn btn-primary"><i class="fa fa-play"></i>
                    Generate</button></td>
					<td width="20%" class="text-center" name="">${v.keterangan}</td>
					</tr>
					`)
					});
					$('#table_data_asn').DataTable({
						'ordering': false
					});
				} else {
					$("#table_data_asn > tbody").append(`
						<tr>
							<td colspan="9" class="text-danger text-center">Data Kosong</td>
						</tr>
					`);
				}

				$('#loadingaction').hide();
			}
		})
	})

	// $('#btn-download-asn-batch').click(function() {
	//     var principle_id = $('#principle').val();
	//     var no = 1;

	//     if (principle_id == "") {
	//         message("Error!", "Pilih Principle!", "error");
	//         return false;
	//     }

	//     $('#loadingaction').show();

	//     $.ajax({
	//         type: "POST",
	//         url: "<?= base_url('WMS/AdvanceShipmentNotice/GetFileFTPBatch') ?>",
	//         data: {
	//             principle_id: principle_id
	//         },
	//         dataType: "JSON",
	//         success: function(response) {
	//             if (response.file != undefined) {
	//                 message("Info!", "File Tidak Tersedia", "info");
	//             } else {
	//                 message("Success!", "File Berhasil Di Download", "success");
	//             }

	//             $("#table_data_asn > tbody").empty();

	//             if ($.fn.DataTable.isDataTable('#table_data_asn')) {
	//                 $('#table_data_asn').DataTable().clear();
	//                 $('#table_data_asn').DataTable().destroy();
	//             }

	//             if (response.penerimaan != 0) {
	//                 $.each(response.penerimaan, function(i, v) {
	//                     $("#table_data_asn > tbody").append(`
	// 					<tr>
	// 					<td width="5%" class="text-center">${no++}</td>
	// 					<td width="20%" class="text-center" name="CAPTION-TANGGAL">${v.tgl}</td>
	// 					<td width="20%" class="text-center" name="CAPTION-PRINCIPLE">${v.principle_kode}
	// 					</td>
	// 					<td width="15%" class="text-center" name="CAPTION-DOASN">${v.penerimaan_surat_jalan_no_sj}</td>
	// 					<td width="10%" class="text-center" name="CAPTION-ACTION">
	// 					<button onclick="clickGenerate('${v.penerimaan_surat_jalan_temp_id}')" type="button" name="btn_generate" id="btn_generate" class="btn btn-primary"><i class="fa fa-play"></i>
	// 					Generate</button></td>
	// 					<td width="10%" class="text-center" name="">${v.status}</td>
	// 					<td width="20%" class="text-center" name="">${v.keterangan}</td>
	// 					</tr>
	// 					`)
	//                 });
	//                 $('#table_data_asn').DataTable({
	//                     'ordering': false
	//                 });
	//             } else {
	//                 $("#table_data_asn > tbody").append(`
	// 			<tr>
	// 				<td colspan="7" class="text-danger text-center">Data Kosong</td>
	// 			</tr>
	// 		`);
	//             }

	//             $('#loadingaction').hide();
	//         }
	//     })
	// })

	$('#btn-submit-filter').click(function() {
		var principle = $('#principle').val();
		// var tgl = $('#filter-sj-date').val();
		var no = 1;

		if (principle == "") {
			message("Error!", "Pilih Principle!", "error");
			return false;
		}

		$('#loadingaction').show();

		$.ajax({
			type: "POST",
			url: "<?= base_url("WMS/AdvanceShipmentNotice/GetDataFilter") ?>",
			data: {
				// tgl: tgl,
				principle: principle
			},
			dataType: 'JSON',
			success: function(response) {
				$("#table_data_asn > tbody").empty();

				if ($.fn.DataTable.isDataTable('#table_data_asn')) {
					$('#table_data_asn').DataTable().clear();
					$('#table_data_asn').DataTable().destroy();
				}

				if (response != 0) {

					$.each(response, function(i, v) {

						$("#table_data_asn > tbody").append(`
					<tr>
					<td width="5%" class="text-center">${no++}</td>
					<td width="15%" class="text-center" name="CAPTION-DEPONAMA">${v.depo_nama}
					</td>
					<td width="10%" class="text-center" >${v.shipment_number}</td>
                    <td width="10%" class="text-center" ><button onclick="clickListDOByShipment('${v.shipment_number}', '${v.principle_id}')" class="btn btn-primary">${v.do}</button></td>
					<td width="10%" class="text-center" >${v.qty}</td>
					<td width="10%" class="text-center" >${v.tglUpdate == null ? '' : v.tglUpdate}</td>
					<td width="10%" class="text-center" >${v.penerimaan_surat_jalan_status}</td>
					<td width="10%" class="text-center" name="CAPTION-ACTION">
					<button onclick="clickGenerate('${v.shipment_number}', '${v.principle_id}', '${v.do}', '${v.qty}', '${v.tglUpdate == null ? '' : v.tglUpdate}')" type="button" name="btn_generate" id="btn_generate" class="btn btn-primary"><i class="fa fa-play"></i>
                    Generate</button></td>
					<td width="20%" class="text-center" name="">${v.keterangan}</td>
					</tr>
					`)
					});
					$('#table_data_asn').DataTable({
						'ordering': false
					});
				} else {
					$("#table_data_asn > tbody").append(`
			<tr>
				<td colspan="9" class="text-danger text-center">Data Kosong</td>
			</tr>
		`);
				}

				$('#loadingaction').hide();
			}
		})
	})

	function clickGenerate(shipment_number, principle_id, jumlah_do, qty, tglUpdate) {

		Swal.fire({
			title: 'Validasi Shipment Number',
			input: 'text',
			inputAttributes: {
				autocapitalize: 'off',
				type: 'number',
				inputmode: 'numeric',
				pattern: '[0-9]*'
			},
			focusConfirm: true,
			showCancelButton: true,
			confirmButtonText: 'Periksa',
			cancelButtonText: 'Batal',
			showLoaderOnConfirm: true,
			preConfirm: (val_shipment) => {
				// Fungsi yang dijalankan ketika tombol "Simpan" ditekan

				if (val_shipment == shipment_number) {
					Swal.fire({
						title: 'Validasi Jumlah DO',
						input: 'text',
						inputAttributes: {
							autocapitalize: 'off',
							type: 'number',
							inputmode: 'numeric',
							pattern: '[0-9]*'
						},
						focusConfirm: true,
						showCancelButton: true,
						confirmButtonText: 'Lanjutkan',
						cancelButtonText: 'Batal',
						showLoaderOnConfirm: true,
						preConfirm: (val_jumlah_do) => {
							// Fungsi yang dijalankan ketika tombol "Simpan" ditekan

							if (val_jumlah_do == jumlah_do) {
								Swal.fire({
									title: 'Validasi Qty',
									input: 'text',
									inputAttributes: {
										autocapitalize: 'off',
										type: 'number',
										inputmode: 'numeric',
										pattern: '[0-9]*'
									},
									focusConfirm: true,
									showCancelButton: true,
									confirmButtonText: 'Submit',
									cancelButtonText: 'Batal',
									showLoaderOnConfirm: true,
									preConfirm: (val_qty) => {

										// Fungsi yang dijalankan ketika tombol "Simpan" ditekan
										if (val_qty == qty) {
											requestAjax("<?= base_url('WMS/AdvanceShipmentNotice/InsertPenerimaanSuratJalan') ?>", {
												shipment_number: shipment_number,
												principle_id: principle_id,
												tglLastUpdate: tglUpdate
											}, "POST", "JSON", function(response) {
												if (response == 201) {
													return messageNotSameLastUpdated();
												} else if (response == 0) {
													message("Error!", "Data gagal generate", "error");

													var principle = $('#principle').val();
													var tgl = $('#filter-sj-date').val();
													var no = 1;

													$.ajax({
														type: "POST",
														url: "<?= base_url("WMS/AdvanceShipmentNotice/GetDataFilter") ?>",
														data: {
															tgl: tgl,
															principle: principle
														},
														dataType: 'JSON',
														success: function(response) {
															$("#table_data_asn > tbody").empty();

															if ($.fn.DataTable.isDataTable('#table_data_asn')) {
																$('#table_data_asn').DataTable().clear();
																$('#table_data_asn').DataTable().destroy();
															}

															if (response != 0) {
																$.each(response, function(i, v) {

																	$("#table_data_asn > tbody").append(`
                                                                            <tr>
                                                                            <td width="5%" class="text-center">${no++}</td>
                                                                            <td width="15%" class="text-center" name="CAPTION-DEPONAMA">${v.depo_nama}
                                                                            </td>
                                                                            <td width="10%" class="text-center" >${v.shipment_number}</td>
                                                                            <td width="10%" class="text-center" ><button onclick="clickListDOByShipment('${v.shipment_number}', '${v.principle_id}')" class="btn btn-primary">${v.do}</button></td>
                                                                            <td width="10%" class="text-center" >${v.qty}</td>
                                                                            <td width="10%" class="text-center" >${v.tglUpdate == null ? '' : v.tglUpdate}</td>
                                                                            <td width="10%" class="text-center" >${v.penerimaan_surat_jalan_status}</td>
                                                                            <td width="10%" class="text-center" name="CAPTION-ACTION">
                                                                            <button onclick="clickGenerate('${v.shipment_number}', '${v.principle_id}', '${v.do}', '${v.qty}', '${v.tglUpdate == null ? '' : v.tglUpdate}')" type="button" name="btn_generate" id="btn_generate" class="btn btn-primary"><i class="fa fa-play"></i>
                                                                            Generate</button></td>
                                                                            <td width="20%" class="text-center" name="">${v.keterangan}</td>
                                                                            </tr>
                                                                        `)
																});

																$('#table_data_asn').DataTable({
																	'ordering': false
																});
															} else {
																$("#table_data_asn > tbody").append(`
                                                                        <tr>
                                                                            <td colspan="9" class="text-danger text-center">Data Kosong</td>
                                                                        </tr>
                                                                    `);
															}
														}
													})
												} else {
													message("Success!", "Data berhasil di generate", "success");

													var principle = $('#principle').val();
													var tgl = $('#filter-sj-date').val();
													var no = 1;

													$.ajax({
														type: "POST",
														url: "<?= base_url("WMS/AdvanceShipmentNotice/GetDataFilter") ?>",
														data: {
															tgl: tgl,
															principle: principle
														},
														dataType: 'JSON',
														success: function(response) {
															$("#table_data_asn > tbody").empty();

															if ($.fn.DataTable.isDataTable('#table_data_asn')) {
																$('#table_data_asn').DataTable().clear();
																$('#table_data_asn').DataTable().destroy();
															}

															if (response != 0) {
																$.each(response, function(i, v) {

																	$("#table_data_asn > tbody").append(`
                                                                            <tr>
                                                                            <td width="5%" class="text-center">${no++}</td>
                                                                            <td width="15%" class="text-center" name="CAPTION-DEPONAMA">${v.depo_nama}
                                                                            </td>
                                                                            <td width="10%" class="text-center" >${v.shipment_number}</td>
                                                                            <td width="10%" class="text-center" ><button onclick="clickListDOByShipment('${v.shipment_number}', '${v.principle_id}')" class="btn btn-primary">${v.do}</button></td>
                                                                            <td width="10%" class="text-center" >${v.qty}</td>
                                                                            <td width="10%" class="text-center" >${v.tglUpdate == null ? '' : v.tglUpdate}</td>
                                                                            <td width="10%" class="text-center" >${v.penerimaan_surat_jalan_status}</td>
                                                                            <td width="10%" class="text-center" name="CAPTION-ACTION">
                                                                            <button onclick="clickGenerate('${v.shipment_number}', '${v.principle_id}', '${v.do}', '${v.qty}', '${v.tglUpdate == null ? '' : v.tglUpdate}')" type="button" name="btn_generate" id="btn_generate" class="btn btn-primary"><i class="fa fa-play"></i>
                                                                            Generate</button></td>
                                                                            <td width="20%" class="text-center" name="">${v.keterangan}</td>
                                                                            </tr>
                                                                         `)
																});

																$('#table_data_asn').DataTable({
																	'ordering': false
																});
															} else {
																$("#table_data_asn > tbody").append(`
                                                                        <tr>
                                                                            <td colspan="9" class="text-danger text-center">Data Kosong</td>
                                                                        </tr>
                                                                    `);
															}
														}
													})
												}
											})
										} else {
											message("Gagal Generate!", "Qty Tidak Sama", "error");
										}
									},
									allowOutsideClick: false
								});
							} else {
								message("Gagal Generate!", "Jumlah DO Tidak Sama", "error");
							}
						},
						allowOutsideClick: false
					});
				} else {
					message("Gagal Generate!", "Shipment Number Tidak Sama", "error");
				}
			},
			allowOutsideClick: false
		});

		// setting input number swal-alert
		const inputNumber = document.querySelector('.swal2-input');
		inputNumber.addEventListener('keydown', function(event) {
			if (event.key === '.' || event.key === ',' || event.key === '-' || event.key === '+') {
				event.preventDefault();
			}
			const allowedKeys = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'Backspace', 'ArrowLeft', 'ArrowRight', 'Delete'];
			if (!allowedKeys.includes(event.key)) {
				if ((event.ctrlKey || event.metaKey) && (event.key === 'c' || event.key === 'v' || event.key === 'a')) {
					// allow copy and paste
					return;
				}
				event.preventDefault();
			}
		});
	}

	function clickListDOByShipment(shipment_number, principle_id) {
		$.ajax({
			type: "POST",
			url: "<?= base_url("WMS/AdvanceShipmentNotice/GetListDOByShipment") ?>",
			data: {
				shipment_number: shipment_number,
				principle_id: principle_id
			},
			dataType: "JSON",
			success: function(response) {
				$('#modal-listdoasn').modal('show');

				if ($.fn.DataTable.isDataTable('#table-listdoasn')) {
					$('#table-listdoasn').DataTable().clear();
					$('#table-listdoasn').DataTable().destroy();
				}

				$('#table-listdoasn > tbody').empty();

				if (response.length > 0) {
					var totalQty = 0;
					$.each(response, function(i, v) {
						totalQty = totalQty + v.sku_jumlah_barang;
						$('#table-listdoasn > tbody').append(`
                    <tr>
                    <td class="text-center">${v.shipment_number}</td>
                    <td class="text-center">${v.penerimaan_surat_jalan_no_sj}</td>
                    <td class="text-center">${v.sku_kode}</td>
                    <td class="text-center">${v.sku_nama_produk}</td>
                    <td class="text-center">${v.sku_jumlah_barang}</td>
                    </tr>
                    `)
					});

					$('#table-listdoasn > tbody').append(`
                    <tr>
                    <td colspan="4" class="text-center bg-success">Total Qty</td>
                    <td class="text-center bg-warning">${totalQty}</td>
                    </tr>
                    `);

					$('#table-listdoasn').DataTable({
						lengthMenu: [
							[50, 100, -1],
							[50, 100, 'All']
						]
					})
				} else {
					$('#table-listdoasn > tbody').html(`
                    <tr>
                    <td colspan="5" class="text-center text-danger">Data Kosong</td>
                    </tr>
                    `)
				}
			}
		})
	}

	function select2() {
		$(".select2").select2({
			width: "100%"
		});
	}

	// function message(msg, msgtext, msgtype) {
	//     Swal.fire(msg, msgtext, msgtype);
	// }
</script>