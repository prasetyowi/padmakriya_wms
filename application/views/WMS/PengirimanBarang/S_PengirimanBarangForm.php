<script type="text/javascript">
	var dataDoD = [];
	var draftSkuED = [];
	var dataSkuED = [];
	var countDO = 0;
	let scanKodeSKU = [];
	const html5QrCode = new Html5Qrcode("previewScanPallet");
	loadingBeforeReadyPage()
	// function message(msg, msgtext, msgtype) {
	// 	Swal.fire({
	// 		title: msg,
	// 		html: msgtext,
	// 		icon: msgtype
	// 	});
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
	// 		html: msg,
	// 	});
	// }


	$(document).ready(function() {
		// alert('aa')
		$('.select2').select2({
			width: '100%'
		});

		$('#viewStandar').hide();
		$('#viewBulk').hide();
		$('#viewReschedule').hide();
		$('#viewCanvas').hide();
		$("#TabelDetailBulk").DataTable();
		$("#TabelDetailStandar").DataTable()
		$("#TabelDetailReschedule").DataTable()
		$("#TabelDetailCanvas").DataTable()
		// $('#tableSKU').DataTable();

		// // Tambahkan opsi pada dropdown select untuk setiap kolom pada tabel
		// $('#TabelDetailBulk th').each(function(index) {
		//     var col_name = $(this).text();
		//     $('#colvis-select').append('<option value="' + index + '" selected>' + col_name + '</option>');
		// });

		// // Setiap kali dropdown select berubah
		// $('#colvis-select').change(function() {
		//     // Munculkan atau sembunyikan setiap kolom pada tabel
		//     $('#TabelDetailBulk th').each(function(index) {
		//         var show = $('#colvis-select option[value="' + index + '"]').is(':selected');
		//         $('#TabelDetailBulk td:nth-child(' + (index + 1) + ')').toggle(show);
		//         $(this).toggle(show);
		//     });
		// });
	});

	$('#saveData').click(function(e) {
		// header
		var ppb_id = $("#ppb_id").val();
		var no_batch_do = $("#no_batch_do").val();
		var keterangan = $("#keterangan").val();
		var tipe = $("#tipe").val();
		let dataBulk = [];
		let dataFlushOut = [];
		let dataStandar = [];
		let dataReschedule = [];
		let dataCanvas = [];
		var last_update = $("#last_update").val();

		var error = 0;

		if (tipe == 'Bulk') {
			const sku_id = $("input[name='sku_id_Bulk[]']").map(function() {
				return this.value;
			}).get();
			const qty_ambil_plan = $("input[name='qty_ambil_plan_Bulk[]']").map(function() {
				return this.value;
			}).get();
			const qty_ambil_aktual = $("input[name='qty_ambil_aktual_Bulk[]']").map(function() {
				return this.value;
			}).get();
			const qty_serah_terima = $("input[name='qty_serah_terima_Bulk[]']").map(function() {
				return this.value;
			}).get();
			const qty_serah_terima_rusak = $("input[name='qty_serah_terima_rusak_Bulk[]']").map(function() {
				return this.value;
			}).get();

			if (sku_id.length > 0) {
				for (let index = 0; index < sku_id.length; index++) {
					dataBulk.push({
						sku_id: sku_id[index],
						qty_ambil_plan: qty_ambil_plan[index],
						qty_ambil_aktual: qty_ambil_aktual[index],
						qty_serah_terima: qty_serah_terima[index],
						qty_serah_terima_rusak: qty_serah_terima_rusak[index],
					})
				}
			}

		} else if (tipe == 'Flush Out') {
			const sku_id = $("input[name='sku_id_FlushOut[]']").map(function() {
				return this.value;
			}).get();
			const qty_ambil_plan = $("input[name='qty_ambil_plan_FlushOut[]']").map(function() {
				return this.value;
			}).get();
			const qty_ambil_aktual = $("input[name='qty_ambil_aktual_FlushOut[]']").map(function() {
				return this.value;
			}).get();
			const qty_serah_terima = $("input[name='qty_serah_terima_FlushOut[]']").map(function() {
				return this.value;
			}).get();
			const qty_serah_terima_rusak = $("input[name='qty_serah_terima_rusak_FlushOut[]']").map(function() {
				return this.value;
			}).get();

			if (sku_id.length > 0) {
				for (let index = 0; index < sku_id.length; index++) {
					dataFlushOut.push({
						sku_id: sku_id[index],
						qty_ambil_plan: qty_ambil_plan[index],
						qty_ambil_aktual: qty_ambil_aktual[index],
						qty_serah_terima: qty_serah_terima[index],
						qty_serah_terima_rusak: qty_serah_terima_rusak[index],
					})
				}
			}

		} else if (tipe == 'Standar') {
			const do_id = $("input[name='do_id[]']").map(function() {
				return this.value;
			}).get();
			const qty_packed = $("input[name='qty_packed[]']").map(function() {
				if (parseInt(this.value) <= 0) {
					Swal.fire(
						'Info !',
						'Harap melakukan pengemasan terlebih dahulu !',
						'info'
					);
					error++;
					return false;
				}
				return this.value;
			}).get();
			const qty_packed_terima = $("input[name='qty_packed_terima[]']").map(function() {
				if (parseInt(this.value) <= 0) {
					alert("Nominal serah terima tidak boleh nol!")
					return false;
				}
				return this.value;
			}).get();

			if (do_id.length > 0) {
				for (let index = 0; index < do_id.length; index++) {
					dataStandar.push({
						do_id: do_id[index],
						qty_packed: qty_packed[index],
						qty_packed_terima: qty_packed_terima[index]
					})
				}
			}
		} else if (tipe == 'Reschedule') {
			const sku_id = $("input[name='sku_id_Reschedule[]']").map(function() {
				return this.value;
			}).get();
			const qty_ambil_plan = $("input[name='qty_ambil_plan_Reschedule[]']").map(function() {
				return this.value;
			}).get();
			const qty_ambil_aktual = $("input[name='qty_ambil_aktual_Reschedule[]']").map(function() {
				return this.value;
			}).get();
			const qty_serah_terima = $("input[name='qty_serah_terima_Reschedule[]']").map(function() {
				return this.value;
			}).get();
			const qty_serah_terima_rusak = $("input[name='qty_serah_terima_rusak_Reschedule[]']").map(function() {
				return this.value;
			}).get();

			if (sku_id.length > 0) {
				for (let index = 0; index < sku_id.length; index++) {
					dataReschedule.push({
						sku_id: sku_id[index],
						qty_ambil_plan: qty_ambil_plan[index],
						qty_ambil_aktual: qty_ambil_aktual[index],
						qty_serah_terima: qty_serah_terima[index],
						qty_serah_terima_rusak: qty_serah_terima_rusak[index],
					})
				}
			}

		} else if (tipe == 'Canvas') {
			const sku_id = $("input[name='sku_id_Canvas[]']").map(function() {
				return this.value;
			}).get();
			const qty_ambil_plan = $("input[name='qty_ambil_plan_Canvas[]']").map(function() {
				return this.value;
			}).get();
			const qty_ambil_aktual = $("input[name='qty_ambil_aktual_Canvas[]']").map(function() {
				return this.value;
			}).get();
			const qty_serah_terima = $("input[name='qty_serah_terima_Canvas[]']").map(function() {
				return this.value;
			}).get();
			const qty_serah_terima_rusak = $("input[name='qty_serah_terima_rusak_Canvas[]']").map(function() {
				return this.value;
			}).get();

			if (sku_id.length > 0) {
				for (let index = 0; index < sku_id.length; index++) {
					dataCanvas.push({
						sku_id: sku_id[index],
						qty_ambil_plan: qty_ambil_plan[index],
						qty_ambil_aktual: qty_ambil_aktual[index],
						qty_serah_terima: qty_serah_terima[index],
						qty_serah_terima_rusak: qty_serah_terima_rusak[index],
					})
				}
			}

		} else if (tipe == 'Mix') {

			const sku_id_bulk = $("input[name='sku_id_Bulk[]']").map(function() {
				return this.value;
			}).get();
			const qty_ambil_plan_bulk = $("input[name='qty_ambil_plan_Bulk[]']").map(function() {
				return this.value;
			}).get();
			const qty_ambil_aktual_bulk = $("input[name='qty_ambil_aktual_Bulk[]']").map(function() {
				return this.value;
			}).get();
			const qty_serah_terima_bulk = $("input[name='qty_serah_terima_Bulk[]']").map(function() {
				return this.value;
			}).get();
			const qty_serah_terima_rusak_bulk = $("input[name='qty_serah_terima_rusak_Bulk[]']").map(function() {
				return this.value;
			}).get();

			if (sku_id_bulk.length > 0) {
				for (let index = 0; index < sku_id_bulk.length; index++) {
					dataBulk.push({
						sku_id: sku_id_bulk[index],
						qty_ambil_plan: qty_ambil_plan_bulk[index],
						qty_ambil_aktual: qty_ambil_aktual_bulk[index],
						qty_serah_terima: qty_serah_terima_bulk[index],
						qty_serah_terima_rusak: qty_serah_terima_rusak_bulk[index],
					})
				}
			}

			const do_id = $("input[name='do_id[]']").map(function() {
				return this.value;
			}).get();
			const qty_packed = $("input[name='qty_packed[]']").map(function() {
				return this.value;
			}).get();
			const qty_packed_terima = $("input[name='qty_packed_terima[]']").map(function() {
				if (parseInt(this.value) <= 0) {
					alert("Nominal serah terima tidak boleh nol!")
					error++;
					return false;
				}
				return this.value;
			}).get();

			if (do_id.length > 0) {
				for (let index = 0; index < do_id.length; index++) {
					dataStandar.push({
						do_id: do_id[index],
						qty_packed: qty_packed[index],
						qty_packed_terima: qty_packed_terima[index]
					})
				}
			}

			const sku_id_reschedule = $("input[name='sku_id_Reschedule[]']").map(function() {
				return this.value;
			}).get();
			const qty_ambil_plan_reschedule = $("input[name='qty_ambil_plan_Reschedule[]']").map(function() {
				return this.value;
			}).get();
			const qty_ambil_aktual_reschedule = $("input[name='qty_ambil_aktual_Reschedule[]']").map(function() {
				return this.value;
			}).get();
			const qty_serah_terima_reschedule = $("input[name='qty_serah_terima_Reschedule[]']").map(function() {
				return this.value;
			}).get();
			const qty_serah_terima_rusak_reschedule = $("input[name='qty_serah_terima_rusak_Reschedule[]']").map(function() {
				return this.value;
			}).get();

			if (sku_id_reschedule.length > 0) {
				for (let index = 0; index < sku_id_reschedule.length; index++) {
					dataReschedule.push({
						sku_id: sku_id_reschedule[index],
						qty_ambil_plan: qty_ambil_plan_reschedule[index],
						qty_ambil_aktual: qty_ambil_aktual_reschedule[index],
						qty_serah_terima: qty_serah_terima_reschedule[index],
						qty_serah_terima_rusak: qty_serah_terima_rusak_reschedule[index],
					})
				}
			}
		}
		if (error > 0) {
			return false;
		}

		$("#saveData").prop('disabled', true);

		messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Iya, Simpan', 'Tidak, Tutup').then((
			result) => {
			if (result.value == true) {
				requestAjax("<?= base_url('WMS/Distribusi/PengirimanBarang/SavePengirimanBarang'); ?>", {
					ppb_id: ppb_id,
					no_batch_do: no_batch_do,
					keterangan: keterangan,
					tipe: tipe,
					keterangan: keterangan,
					last_update: last_update,
					// detail1
					dataBulk,
					// detail2
					dataStandar,
					// detail3
					dataReschedule,
					// detail4
					dataCanvas,
					// detail1
					dataFlushOut,
				}, "POST", "JSON", function(response) {
					if (response.status === 200) {
						// $("#lastUpdated").val(response.lastUpdatedNew);
						Swal.fire(
							'Success!',
							'Your file has been created.',
							'success'
						)
						setTimeout(function() {
							window.location.href =
								"<?= base_url('WMS/Distribusi/PengirimanBarang/PengirimanBarangEdit/') ?>" +
								response.serah_terima_kirim_id + "";
						}, 3000);
					}
					if (response.status === 400) return messageNotSameLastUpdated()
					if (response.status === 401) return message_topright('error', response.message)
				}, "#saveData")
			}
		});
	});

	function GetDataHeaderByDriver() {
		var karyawanId = $("#driver").val();
		$.ajax({
			type: 'GET',
			url: "<?= base_url('WMS/Distribusi/PengirimanBarang/GetPpbByDriver') ?>",
			data: {
				karyawanId: karyawanId,
			},
			success: function(response) {
				$("#ppb_id").empty();
				$("#ppb_id").append(`<option value="">-- PILIH NO PPB --</option>`);
				if (response.length > 0) {
					response.forEach((value, index) => {
						$("#ppb_id").append(`<option value="${value.picking_order_id}">${value.picking_order_kode}</option>`);
					})
				}
			}
		});
	}

	function GetDataHeader() {
		var ppb_id = $("#ppb_id").val();
		$("#no_batch_do option").remove()
		$.ajax({
			type: 'GET',
			url: "<?= base_url('WMS/Distribusi/PengirimanBarang/GetDoBatchByPickOrderId') ?>",
			data: {
				ppb_id: ppb_id,
			},
			success: function(response) {
				let data = response;
				$("#no_batch_do").append($("<option />").val(data.delivery_order_batch_id).text(data
					.delivery_order_batch_kode));
				$("#driver").val(data.karyawan_nama)
				$("#tipe").val(data.tipe_delivery_order_nama)
				$("#tipe_fdjr").html(data.tipe_delivery_order_alias)
				$("#last_update").val(data.picking_order_tgl_update)

				// if (data.tipe_delivery_order_nama == 'Bulk') {
				//     $('#viewBulk').show();
				//     $('#viewStandar').hide();
				// } 
				// else if (data.tipe_delivery_order_nama == 'Standar') {
				//     $('#viewStandar').show();
				//     $('#viewBulk').hide();
				// } 
				// else{
				//     $('#viewStandar').hide();
				//     $('#viewBulk').hide();
				// }
				getDetail()
			}
		});

	}

	function getDetail() {

		var ppb_id = $("#ppb_id").val();
		var tipe = $("#tipe").val();

		$("#TabelDetailBulk tbody").empty();
		$("#TabelDetailStandar tbody").empty();
		$("#TabelDetailReschedule tbody").empty();
		$("#TabelDetailCanvas tbody").empty();
		if (['Bulk', 'Reschedule', 'Canvas', 'Flush Out'].includes(tipe)) {
			if (tipe == 'Bulk') {
				$('#viewFlushOut').hide();
				$('#viewBulk').show();
				$('#viewStandar').hide();
				$('#viewReschedule').hide();
				$('#viewCanvas').hide();
			}

			if (tipe == 'Flush Out') {
				$('#viewFlushOut').show();
				$('#viewBulk').hide();
				$('#viewStandar').hide();
				$('#viewReschedule').hide();
				$('#viewCanvas').hide();
			}

			if (tipe == 'Reschedule') {
				$('#viewFlushOut').hide();
				$('#viewBulk').hide();
				$('#viewStandar').hide();
				$('#viewReschedule').show();
				$('#viewCanvas').hide();
			}

			if (tipe == 'Canvas') {
				$('#viewFlushOut').hide();
				$('#viewBulk').hide();
				$('#viewStandar').hide();
				$('#viewReschedule').hide();
				$('#viewCanvas').show();
			}

			$.ajax({
				type: 'GET',
				url: "<?= base_url('WMS/Distribusi/PengirimanBarang/GetDataDetail') ?>",
				data: {
					ppb_id: ppb_id,
					tipe
				},
				success: function(response) {

					let data = response;
					let no = 1;
					if (data.length > 0) {
						$.each(data, function(index, value) {

							let styleTr = "";
							if (scanKodeSKU.length != 0) {
								const findData = scanKodeSKU.find((item) => (item.sku_id === value
									.sku_id) && (item.type === type));
								if (typeof findData !== 'undefined') {
									styleTr = "background-color: #86efac; color:#0f172a";
								} else {
									styleTr = "";
								}
							}

							if (tipe == 'Flush Out') {
								$(`#TabelDetailFlushOut tbody`).append(`
									<tr style="${styleTr}">
										<td style='vertical-align:middle; ' >${no}</td>
										<td style='vertical-align:middle; ' >${value.principle_kode}<input type="hidden" class="form-control" name="sku_id_FlushOut[]" value="${value.sku_id}"></td>
										<td style='vertical-align:middle; ' >${value.sku_kode}</td>
										<td style='vertical-align:middle; ' >${value.sku_nama_produk}</td>
										<td style='vertical-align:middle; text-align: center; ' >${value.sku_kemasan}</td>
										<td style='vertical-align:middle; text-align: center; ' >${value.sku_satuan}</td>
										<td style='vertical-align:middle; text-align: center; ' hidden>${value.qty}<input type="hidden" class="form-control" name="qty_ambil_plan_FlushOut[]" value="${value.qty}"></td>
										<td style='vertical-align:middle; text-align: center; ' hidden>${value.qty_ambil}<input type="hidden" class="form-control" name="qty_ambil_aktual_FlushOut[]" id="qty_ambil_aktual-${index}" value="${value.qty_ambil}"></td>
										<td style='vertical-align:middle; ' ><input class="form-control" type="text" name="qty_serah_terima_FlushOut[]" id="qty_terima-${index}" onchange="cekQtyBulk('${index}')" value="0"</td>
										<td style='vertical-align:middle; ' ><input class="form-control" type="text" name="qty_serah_terima_rusak_FlushOut[]" id="qty_terima_rusak-${index}" onchange="cekQtyBulk('${index}')" value="0"</td>
									</tr>
								`);
							} else {
								$(`#TabelDetail${tipe} tbody`).append(`
									<tr style="${styleTr}">
										<td style='vertical-align:middle; ' >${no}</td>
										<td style='vertical-align:middle; ' >${value.principle_kode}<input type="hidden" class="form-control" name="sku_id_${tipe}[]" value="${value.sku_id}"></td>
										<td style='vertical-align:middle; ' >${value.sku_kode}</td>
										<td style='vertical-align:middle; ' >${value.sku_nama_produk}</td>
										<td style='vertical-align:middle; text-align: center; ' >${value.sku_kemasan}</td>
										<td style='vertical-align:middle; text-align: center; ' >${value.sku_satuan}</td>
										<td style='vertical-align:middle; text-align: center; ' hidden>${value.qty}<input type="hidden" class="form-control" name="qty_ambil_plan_${tipe}[]" value="${value.qty}"></td>
										<td style='vertical-align:middle; text-align: center; ' hidden>${value.qty_ambil}<input type="hidden" class="form-control" name="qty_ambil_aktual_${tipe}[]" id="qty_ambil_aktual-${index}" value="${value.qty_ambil}"></td>
										<td style='vertical-align:middle; ' ><input class="form-control" type="text" name="qty_serah_terima_${tipe}[]" id="qty_terima-${index}" onchange="cekQtyBulk('${index}')" value="0"</td>
										<td style='vertical-align:middle; ' ><input class="form-control" type="text" name="qty_serah_terima_rusak_${tipe}[]" id="qty_terima_rusak-${index}" onchange="cekQtyBulk('${index}')" value="0"</td>
									</tr>
								`);
							}

							// tableDO.row.add(tr[0]).draw()
							no++;
							// console.log(index);
						});
					} else {
						$(`#TabelDetail${tipe} tbody`).html('');
					}
				}
			});
		} else if (tipe == 'Standar') {
			$('#viewStandar').show();
			$('#viewBulk').hide();
			$('#viewReschedule').hide();
			$('#viewCanvas').hide();
			$.ajax({
				type: 'GET',
				url: "<?= base_url('WMS/Distribusi/PengirimanBarang/GetDataDetailStandar') ?>",
				data: {
					ppb_id: ppb_id,
				},
				success: function(response) {

					let data = response;
					let no = 1;
					if (data.length > 0) {
						$.each(data, function(index, value) {

							let styleTr = "";
							if (scanKodeSKU.length != 0) {
								const findData = scanKodeSKU.find((item) => (item.sku_id === value
									.sku_id) && (item.type === 'Standar'));
								if (typeof findData !== 'undefined') {
									styleTr = "background-color: #86efac; color:#0f172a";
								} else {
									styleTr = "";
								}
							}

							$('#TabelDetailStandar tbody').append(`
                                <tr style="${styleTr}">
                                    <td style='vertical-align:middle; text-align: center;' >${no}</td>
                                    <td style='vertical-align:middle; text-align: center;' >${value.delivery_order_kode}<input type="hidden" class="form-control" name="do_id[]" value="${value.delivery_order_id}"></td>
                                    <td style='vertical-align:middle; text-align: center;' >${value.delivery_order_kirim_nama}</td>
                                    <td style='vertical-align:middle; text-align: center;' >${value.delivery_order_kirim_alamat}</td>
                                    <td style='vertical-align:middle; text-align: center; ' hidden>${value.jumlah_packed}<input type="hidden" class="form-control" name="qty_packed[]" id="qty_packed-${index}" value="${value.jumlah_packed}"></td>
                                    <td style='vertical-align:middle; text-align: center;' ><input class="form-control" type="text" name="qty_packed_terima[]" id="qty_packed_terima-${index}" onchange="cekQtyStandar('${index}')></td>
                                    <td style='vertical-align:middle; text-align: center;' ><input class="form-control" type="checkbox" name="chkDetail[]" id="chkDetail" value="0"></td>
                                </tr>
                            `);

							// tableDO.row.add(tr[0]).draw()
							no++;
							// console.log(index);
						});
					} else {
						$("#TabelDetailStandar tbody").html('');
					}
				}
			});
		} else if (tipe == 'Mix') {
			$('#viewStandar').show();
			$('#viewBulk').show();
			$('#viewReschedule').show();
			$('#viewCanvas').hide();
			// ajax do bulka
			$.ajax({
				type: 'GET',
				url: "<?= base_url('WMS/Distribusi/PengirimanBarang/GetDataDetailBulk') ?>",
				data: {
					ppb_id: ppb_id,
				},
				success: function(response) {

					if (response.length === 0) {
						$('#viewBulk').hide();
					} else {
						let data = response;
						let no = 1;
						if (data.length > 0) {
							$.each(data, function(index, value) {

								let styleTr = "";
								if (scanKodeSKU.length != 0) {
									const findData = scanKodeSKU.find((item) => (item.sku_id === value
										.sku_id) && (item.type === 'Bulk'));
									if (typeof findData !== 'undefined') {
										styleTr = "background-color: #86efac; color:#0f172a";
									} else {
										styleTr = "";
									}
								}

								$('#TabelDetailBulk tbody').append(`
                                <tr style="${styleTr}">
                                    <td style='vertical-align:middle; ' >${no}</td>
                                    <td style='vertical-align:middle; ' >${value.principle_kode}<input type="hidden" class="form-control" name="sku_id_Bulk[]" value="${value.sku_id}"></td>
                                    <td style='vertical-align:middle; ' >${value.sku_kode}</td>
                                    <td style='vertical-align:middle; ' >${value.sku_nama_produk}</td>
                                    <td style='vertical-align:middle; text-align: center; ' >${value.sku_kemasan}</td>
                                    <td style='vertical-align:middle; text-align: center; ' >${value.sku_satuan}</td>
                                    <td style='vertical-align:middle; text-align: center; ' hidden>${value.qty}<input type="hidden" class="form-control" name="qty_ambil_plan_Bulk[]" value="${value.qty}"></td>
                                    <td style='vertical-align:middle; text-align: center; ' hidden>${value.qty_ambil}<input type="hidden" class="form-control" name="qty_ambil_aktual_Bulk[]" id="qty_ambil_aktual-${index}" value="${value.qty_ambil}"></td>
                                    <td style='vertical-align:middle; ' ><input class="form-control" type="text" name="qty_serah_terima_Bulk[]" id="qty_terima-${index}" onchange="cekQtyBulk('${index}')" value="0"></td>
                                    <td style='vertical-align:middle; ' ><input class="form-control" type="text" name="qty_serah_terima_rusak_Bulk[]" id="qty_terima_rusak-${index}" onchange="cekQtyBulk('${index}')" value="0"></td>
                                </tr>
                            `);

								// tableDO.row.add(tr[0]).draw()
								no++;
								// console.log(index);
							});
						} else {
							$("#TabelDetailBulk tbody").html('');
						}
					}
				}
			});
			// ajax do standar
			$.ajax({
				type: 'GET',
				url: "<?= base_url('WMS/Distribusi/PengirimanBarang/GetDataDetailStandar') ?>",
				data: {
					ppb_id: ppb_id,
				},
				success: function(response) {

					if (response.length === 0) {
						$('#viewStandar').hide();
					} else {
						let data = response;
						let no = 1;
						if (data.length > 0) {
							$.each(data, function(index, value) {

								let styleTr = "";
								if (scanKodeSKU.length != 0) {
									const findData = scanKodeSKU.find((item) => (item.sku_id === value
										.sku_id) && (item.type === 'Standar'));
									if (typeof findData !== 'undefined') {
										styleTr = "background-color: #86efac; color:#0f172a";
									} else {
										styleTr = "";
									}
								}

								$('#TabelDetailStandar tbody').append(`
                                <tr style="${styleTr}">
                                    <td style='vertical-align:middle; ' >${no}</td>
                                    <td style='vertical-align:middle; ' >${value.principle_kode}<input type="hidden" class="form-control" name="do_id[]" value="${value.delivery_order_id}"></td>
                                    <td style='vertical-align:middle; ' >${value.sku_kode}</td>
                                    <td style='vertical-align:middle; ' >${value.sku_nama_produk}</td>
                                    <td style='vertical-align:middle; ' >${value.sku_kemasan}</td>
                                    <td style='vertical-align:middle; ' >${value.sku_satuan}</td>
                                    <td style='vertical-align:middle; text-align: center; ' hidden>${value.jumlah_packed}<input type="hidden" class="form-control" name="qty_packed[]" id="qty_packed-${index}" value="${value.jumlah_packed}"></td>
                                    <td style='vertical-align:middle; ' ><input class="form-control" type="text" name="qty_packed_terima[]" id="qty_packed_terima-${index}" onchange="cekQtyStandar('${index}')></td>
                                    <td style='vertical-align:middle; ' ><input class="form-control" type="checkbox" name="chkDetail[]" id="chkDetail" value="0"></td>
                                </tr>
                            `);

								// tableDO.row.add(tr[0]).draw()
								no++;
								// console.log(index);
							});
						} else {
							$("#TabelDetailStandar tbody").html('');
						}
					}


				}
			});

			// ajax do Kirim Ulang
			$.ajax({
				type: 'GET',
				url: "<?= base_url('WMS/Distribusi/PengirimanBarang/GetDataDetailReschedule') ?>",
				data: {
					ppb_id: ppb_id,
				},
				success: function(response) {

					if (response.length === 0) {
						$('#viewReschedule').hide();
					} else {
						let data = response;
						let no = 1;
						if (data.length > 0) {
							$.each(data, function(index, value) {

								let styleTr = "";
								if (scanKodeSKU.length != 0) {
									const findData = scanKodeSKU.find((item) => (item.sku_id === value
										.sku_id) && (item.type === 'Reschedule'));
									if (typeof findData !== 'undefined') {
										styleTr = "background-color: #86efac; color:#0f172a";
									} else {
										styleTr = "";
									}
								}

								$('#TabelDetailReschedule tbody').append(`
                                <tr style="${styleTr}">
                                    <td style='vertical-align:middle; ' >${no}</td>
                                    <td style='vertical-align:middle; ' >${value.principle_kode}<input type="hidden" class="form-control" name="sku_id_Reschedule[]" value="${value.sku_id}"></td>
                                    <td style='vertical-align:middle; ' >${value.sku_kode}</td>
                                    <td style='vertical-align:middle; ' >${value.sku_nama_produk}</td>
                                    <td style='vertical-align:middle; text-align: center; ' >${value.sku_kemasan}</td>
                                    <td style='vertical-align:middle; text-align: center; ' >${value.sku_satuan}</td>
                                    <td style='vertical-align:middle; text-align: center; ' hidden>${value.qty}<input type="hidden" class="form-control" name="qty_ambil_plan_Reschedule[]" value="${value.qty}"></td>
                                    <td style='vertical-align:middle; text-align: center; ' hidden>${value.qty_ambil}<input type="hidden" class="form-control" name="qty_ambil_aktual_Reschedule[]" id="qty_ambil_aktual-${index}" value="${value.qty_ambil}"></td>
                                    <td style='vertical-align:middle; ' ><input class="form-control" type="text" name="qty_serah_terima_Reschedule[]" id="qty_terima-${index}" onchange="cekQtyBulk('${index}')" value="0"></td>
                                    <td style='vertical-align:middle; ' ><input class="form-control" type="text" name="qty_serah_terima_rusak_Reschedule[]" id="qty_terima_rusak-${index}" onchange="cekQtyBulk('${index}')" value="0"></td>
                                </tr>
                            `);

								// tableDO.row.add(tr[0]).draw()
								no++;
								// console.log(index);
							});
						} else {
							$("#TabelDetailReschedule tbody").html('');
						}
					}
				}
			});

		}

	}

	const changeScanPalletHandler = (e) => {
		if (e.target.checked) {
			$(`#input_pallet`).show();
			$(`#start_scan_pallet`).hide();

		} else {
			$(`#input_pallet`).hide();
			$(`#start_scan_pallet`).show();

		}
	}

	const inputScanPalletHandler = () => {
		var pengirimanBarangId = $("#ppb_id").val();
		var type = $("#tipe").val();
		$("#modalInputPallet").modal('show');

		const data = {
			pengirimanBarangId,
			type
		}

		$("#kodeSKu").attr("data-handler", JSON.stringify(data));

	}

	const changeInputPalletHandler = (event) => {
		const dataHandler = JSON.parse(event.currentTarget.getAttribute('data-handler'));

		const idPengiriman = dataHandler.pengirimanBarangId;
		const type = dataHandler.type;
		const value = event.currentTarget.value;

		requestDataToScan(idPengiriman, type, value)

	}

	const closeInputPalletHandler = () => {
		$("#modalInputPallet").modal('hide');
		$("#kodeSKu").val("");
	}

	const startScanPalletHandler = () => {
		var pengirimanBarangId = $("#ppb_id").val();
		var type = $("#tipe").val();
		Swal.fire({
			title: '<span ><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-ALERT-MEMUATKAMERA"></label></span>',
			timer: 1000,
			timerProgressBar: true,
			showConfirmButton: false,
			allowOutsideClick: false,
			didOpen: () => {
				Swal.showLoading();
				const b = Swal.getHtmlContainer().querySelector('b')
				timerInterval = setInterval(() => {
					b.textContent = Swal.getTimerLeft()
				}, 100)
			},
			willClose: () => {
				clearInterval(timerInterval)
			}
		}).then((result) => {
			/* Read more about handling dismissals below */
			if (result.dismiss === Swal.DismissReason.timer) {
				$("#modalScanPallet").modal('show');

				//handle succes scan
				const qrCodeSuccessCallback2 = (decodedText, decodedResult) => {
					let temp = decodedText;
					if (temp != "") {
						html5QrCode.pause();
						scan(decodedText);
					}
				};


				const scan = (decodedText) => {
					requestDataToScan(pengirimanBarangId, type, decodedText)
				}

				// atur kotak nng kini, set 0.sekian pngin jlok brpa persen
				const qrboxFunction2 = function(viewfinderWidth, viewfinderHeight) {
					let minEdgePercentage = 0.9; // 90%
					let minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
					let qrboxSize = Math.floor(minEdgeSize * minEdgePercentage);
					return {
						width: qrboxSize,
						height: qrboxSize
					};
				}

				const config2 = {
					fps: 10,
					qrbox: qrboxFunction2,
				};
				Html5Qrcode.getCameras().then(devices => {
					if (devices && devices.length) {
						$("#selectCameraPallet").empty();
						$.each(devices, function(i, v) {
							$("#selectCameraPallet").append(`
	                      <input class="checkbox-tools" type="radio" name="tools2" value="${v.id}" id="tool-${i}">
	                      <label class="for-checkbox-tools" for="tool-${i}">
	                          ${v.label}
	                      </label>
	                  `)
						});

						$("#selectCameraPallet :input[name='tools2']").each(function(i, v) {
							if (i == 0) {
								$(this).attr('checked', true);
							}
						});

						let cameraId2 = devices[0].id;
						// html5QrCode.start(devices[0]);
						$('input[name="tools2"]').on('click', function() {
							// alert($(this).val());
							html5QrCode.stop();
							html5QrCode.start({
								deviceId: {
									exact: $(this).val()
								}
							}, config2, qrCodeSuccessCallback2);
						});
						//start scan
						html5QrCode.start({
							deviceId: {
								exact: cameraId2
							}
						}, config2, qrCodeSuccessCallback2);

					}
				}).catch(err => {
					message("Error!", "Kamera tidak ditemukan", "error");
					$("#modalScanPallet").modal('hide');
				});
			}
		});
	}

	const requestDataToScan = async (idPengiriman, type, value) => {

		const url = "<?= base_url('WMS/Distribusi/PengirimanBarang/CheckScanKodeSKU') ?>";

		let postData = new FormData();
		postData.append('pengirimanBarangId', idPengiriman);
		postData.append('mode', 'insert');
		postData.append('type', type);
		postData.append('kode', value);

		const request = await fetch(url, {
			method: 'POST',
			body: postData
		});

		const response = await request.json();

		if (response.type == 200) {
			if (scanKodeSKU.length === 0) {
				scanKodeSKU.push({
					sku_id: response.data.sku_id,
					type
				});
				message('Success!', response.message, 'success');
				$(`#initDataAll`).fadeOut("slow", function() {
					$(this).hide();
				}).fadeIn("slow", function() {
					$(this).show();
					getDetail()
				});

			} else {
				const findData = scanKodeSKU.find((item) => (item.sku_id === response.data.sku_id) && (item.type ===
					type));
				if (typeof findData === 'undefined') {
					scanKodeSKU.push({
						sku_id: response.data.sku_id,
						type
					});
					message('Success!', response.message, 'success');
					$(`#initDataAll`).fadeOut("slow", function() {
						$(this).hide();
					}).fadeIn("slow", function() {
						$(this).show();
						getDetail()
					});
				} else {
					message('Info!', 'Kode SKU sudah discan', 'info');
				}
			}
		}

		if (response.type == 201) {
			message('Error!', response.message, 'error');
		}
	}

	const closeScanPalletHandler = () => {
		html5QrCode.stop();
		$("#modalScanPallet").modal('hide');
	}

	const handlerAutoCompleteSKU = (event, value) => {
		const dataku = event.currentTarget.getAttribute('data-handler');
		const requestReplace = dataku.replaceAll("\'", '"')
		const requestReplace2 = requestReplace.replaceAll(/\\/g, '')

		if (value != "") {
			fetch('<?= base_url('WMS/Distribusi/PengirimanBarang/getKodeAutoComplete?params='); ?>' + value)
				.then(response => response.json())
				.then((results) => {
					if (!results[0]) {
						document.getElementById('table-fixed').style.display = 'none';
					} else {
						let data = "";
						// console.log(results);
						results.forEach(function(e, i) {
							data += `
									<tr onclick="getNoSuratJalanEks(event, '${e.kode}', '${requestReplace2.replace(/"/g, '\\\'')}')" style="cursor:pointer">
											<td class="col-xs-1">${i + 1}.</td>
											<td class="col-xs-11">${e.kode}</td>
									</tr>
									`;
						})

						document.getElementById('konten-table').innerHTML = data;
						// console.log(data);
						document.getElementById('table-fixed').style.display = 'block';
					}
				});
		} else {
			document.getElementById('table-fixed').style.display = 'none';
		}
	}

	function getNoSuratJalanEks(event, data, dataRequest) {
		const requestReplace = dataRequest.replaceAll("\'", '"')
		const requestReplace2 = requestReplace.replaceAll(/\\/g, '')
		const dataFix = JSON.parse(requestReplace2);

		$("#kodeSKu").val(data);
		document.getElementById('table-fixed').style.display = 'none'

		// const idPengiriman = dataFix.pengirimanBarangId;
		// const type = dataFix.type;

		requestDataToScan(dataFix.pengirimanBarangId, dataFix.type, data)
	}
</script>