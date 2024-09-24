<script>
	const urlSearchParams = new URLSearchParams(window.location.search);
	const pickingValidationId = Object.fromEntries(urlSearchParams.entries()).id;
	const fdjrId = Object.fromEntries(urlSearchParams.entries()).fdjr;
	const ppbId = Object.fromEntries(urlSearchParams.entries()).ppb;
	const mode = Object.fromEntries(urlSearchParams.entries()).mode;

	let arrTempProsesValidasi = [];
	let arrTempProsesData = [];
	let arrDataDetailTemp = [];
	let arrDataKoreksiDoTemp = [];
	let arrDataKoreksiDoTemp2 = [];
	let arrTempProsesValidasiForDO = [];

	let arrSKUFirstSame = [];

	let arrDropDOdiSKU = [];
	loadingBeforeReadyPage()
	$(document).ready(function() {
		$(".select2").select2({
			width: "100%"
		});

		$('#select-do').click(function(event) {
			if (this.checked) {
				// Iterate each checkbox
				$('[name="CheckboxDO"]:checkbox').each(function() {
					this.checked = true;
				});
			} else {
				$('[name="CheckboxDO"]:checkbox').each(function() {
					this.checked = false;
				});
			}
		});

		$(document).on("input", ".numeric", function(event) {
			this.value = this.value.replace(/[^\d.]+/g, '');
		});

		$('#vfiltertqanggalrange').daterangepicker({
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

		// resetUrlPushState();
		$("#listDataValidasiBarang").DataTable()

		$("#initDataDetailbarang").DataTable()

		$("#initDataKoreksiDO").DataTable()

		$("#initDataDropKoreksiDO").DataTable()

		if (typeof pickingValidationId !== 'undefined') {

			getDataPickingValidationEdit();

			$("#noFDJR").val(fdjrId).trigger('change')
			setTimeout(() => $("#noPPB").val(ppbId).change(), 1500)
			handlerGetDataProsesValidasi(ppbId)
			$("#noFDJR").prop('disabled', true)
			$("#noPPB").prop('disabled', true)

			if (typeof mode !== 'undefined') {
				$("#title-menu").html("View Validasi Pengeluaran Barang");
				$("#btnConfirmProsesValidasi").hide();
				$("#btnCompletedProsesValidasi").hide();
				$("#btnCetakProsesValidasi").show();
			} else {
				$("#title-menu").html("Edit Validasi Pengeluaran Barang");
				$("#btnConfirmProsesValidasi").show();
				$("#btnCompletedProsesValidasi").show();
				$("#btnCetakProsesValidasi").hide();
			}

		} else {
			$("#title-menu").html("Form Validasi Pengeluaran Barang");
			$("#btnConfirmProsesValidasi").hide();
			$("#btnCompletedProsesValidasi").hide();
		}

		<?php if (isset($act) && $act == "form_by_picking_order") { ?>
			handlerGetDataProsesValidasi('<?= $picking_order_id ?>');
		<?php } ?>

	})

	/** --------------------------------------- Untuk Global ------------------------------------------- */


	// const message = (msg, msgtext, msgtype) => {
	//   Swal.fire(msg, msgtext, msgtype);
	// }

	// const message_topright = (type, msg) => {
	//   const Toast = Swal.mixin({
	//     toast: true,
	//     position: "top-end",
	//     showConfirmButton: false,
	//     timer: 3000,
	//     didOpen: (toast) => {
	//       toast.addEventListener("mouseenter", Swal.stopTimer);
	//       toast.addEventListener("mouseleave", Swal.resumeTimer);
	//     },
	//   });

	//   Toast.fire({
	//     icon: type,
	//     title: msg,
	//   });
	// }

	const resetUrlPushState = () => {
		let url = window.location.href;
		if (url.indexOf("?") != -1) {
			let resUrl = url.split("?");

			if (typeof window.history.pushState == 'function') {
				window.history.pushState({}, "Hide", resUrl[0]);
			}
		}
	}

	async function postDataRequest(url = '', data = {}, type) {
		if (type == "GET") {
			const response = await fetch(url);

			if (!response.ok) return response
			return response.json();
		} else {
			const response = await fetch(url, {
				method: type,
				headers: {
					'Content-Type': 'application/json'
				},
				body: JSON.stringify(data)
			});

			if (!response.ok) return response
			return response.json();
		}
	}


	/** --------------------------------------- End Untuk Global ------------------------------------------- */

	const handlerFilterDataPickingValidation = () => {
		$("#loadingsearch").show();

		postDataRequest("<?= base_url('WMS/Distribusi/ValidasiPengeluaranBarang/getFlterDataPickingValidation') ?>", {
			tanggal: $("#vfiltertqanggalrange").val(),
			kode: $("#vkodePickingValidation option:selected").val(),
			status: $("#vstatus option:selected").val()
		}, 'POST').then((response) => {
			$("#loadingsearch").hide();
			if ($.fn.DataTable.isDataTable('#listDataValidasiBarang')) {
				$('#listDataValidasiBarang').DataTable().destroy();
			}

			$("#listDataValidasiBarang tbody").empty();

			if (response.data.length > 0) {

				$.each(response.data, function(i, v) {

					let str = '';

					if (v.picking_validation_status == "Draft") {
						str += `<button type="button" class="btn btn-danger btn-sm" id="btnDeleteDataPickingValidation" onclick="handlerDeleteDataPickingValidation(event, '${v.picking_validation_id}', '${v.lastUpdated}')">Delete</button>`;
						str += `<button type="button" class="btn btn-warning btn-sm" id="btnEditDataPickingValidation" onclick="handlerEditDataPickingValidation(event, '${v.picking_validation_id}', '${v.delivery_order_batch_id}', '${v.picking_order_id}')">Edit</button>`;
						if (response.status[i].generateNotDone === 0) {
							if (response.status[i].status === 0) str += `<button class="btn btn-success btn-sm" onclick="handlerConfirmationFront('${v.picking_validation_id}', '${v.delivery_order_batch_id}', '${v.picking_order_id}', '${v.serah_terima_kirim_id}','${v.lastUpdated}')"><span name="CAPTION-KONFIRMASI">Konfirmasi</span></button>`;
						}

					}

					if (v.picking_validation_status == "In Progress Confirmation") {
						str += `<button type="button" class="btn btn-warning btn-sm" id="btnEditDataPickingValidation" onclick="handlerEditDataPickingValidation(event, '${v.picking_validation_id}', '${v.delivery_order_batch_id}', '${v.picking_order_id}')">Edit</button>`;

						if (response.status[i].generateNotDone === 0) {
							if (response.status[i].status === 0) str += `<button class="btn btn-success btn-sm" onclick="handlerConfirmationFront('${v.picking_validation_id}', '${v.delivery_order_batch_id}', '${v.picking_order_id}', '${v.serah_terima_kirim_id}','${v.lastUpdated}')"><span name="CAPTION-KONFIRMASI">Konfirmasi</span></button>`;
						}
					}

					if (v.picking_validation_status === "Confirmation Done") {
						str += `<button type="button" class="btn btn-info btn-sm" id="btnViewDataPickingValidation" onclick="handlerViewDataPickingValidation(event, '${v.picking_validation_id}', '${v.delivery_order_batch_id}', '${v.picking_order_id}')">View</button>`;
					}

					if (v.picking_validation_status == "in transit validation") {
						str += `<a href="<?= base_url() ?>WMS/Distribusi/ValidasiPengeluaranBarang/form_by_picking_order/?picking_order_id=${v.picking_order_id}&delivery_order_batch_id=${v.delivery_order_batch_id}" class="btn btn-primary btn-sm" target="_blank"><span name="CAPTION-TAMBAHDATA">Tambah Data</span></a>`;
						if (response.status[i].status === 0) str += `<button class="btn btn-success btn-sm" onclick="handlerConfirmationFront('${v.picking_validation_id}', '${v.delivery_order_batch_id}', '${v.picking_order_id}', '${v.serah_terima_kirim_id}','${v.lastUpdated}')"><span name="CAPTION-KONFIRMASI">Konfirmasi</span></button>`;

					}

					$("#listDataValidasiBarang tbody").append(`
              <tr class="text-center">
                  <td>${i + 1}</td>
                  <td>${v.tanggal}</td>
                  <td>${v.picking_validation_kode}</td>
                  <td>${v.delivery_order_batch_kode}</td>
                  <td>${v.picking_order_kode}</td>
                  <td>${v.serah_terima_kirim_kode}</td>
                  <td>${v.picking_validation_status}</td>
                  <td>
										<span class="badge" style="background: ${response.status[i].status > 0 ? 'red' : 'green'}">${response.status[i].status > 0 ? 'Tidak Cocok' : 'Cocok'}</span><br>
										<p>${response.status[i].generateNotDone > 0 ? 'Ada data yang belum digenerate' : ''}</p>
									</td>
                  <td>${str}</td>
              </tr>
          `)
				})

			}

			$("#listDataValidasiBarang").DataTable()

		})
	}

	const handlerEditDataPickingValidation = (event, pickingValidationId, deliveryOrderBatchId, pickingOrderId) => {
		location.href = "<?= base_url('WMS/Distribusi/ValidasiPengeluaranBarang/form?id=') ?>" + pickingValidationId + "&fdjr=" + deliveryOrderBatchId + "&ppb=" + pickingOrderId
	}

	const handlerViewDataPickingValidation = (event, pickingValidationId, deliveryOrderBatchId, pickingOrderId) => {
		location.href = "<?= base_url('WMS/Distribusi/ValidasiPengeluaranBarang/form?id=') ?>" + pickingValidationId + "&fdjr=" + deliveryOrderBatchId + "&ppb=" + pickingOrderId + "&mode=view"
	}

	const handlerDeleteDataPickingValidation = (event, pickingValidationId, lastUpdated) => {

		messageBoxBeforeRequest('Ingin hapus data validasi?', 'Iya, Hapus', 'Tidak, Tutup').then((result) => {
			if (result.value == true) {
				postData("<?= base_url('WMS/Distribusi/ValidasiPengeluaranBarang/deleteDataPickingValidation') ?>", {
					pickingValidationId,
					lastUpdated
				}, 'POST', function(response) {
					if (response.status === 200) {
						message_topright('success', response.message);
						setTimeout(() => location.reload(), 1000);
					}

					if (response.status === 401) return message_topright("error", response.message);
					if (response.status === 400) return messageNotSameLastUpdated()
				})
			}
		});

		// Swal.fire({
		// 	title: "Apakah anda yakin?",
		// 	text: "Ingin simpan data opname?",
		// 	icon: "warning",
		// 	showCancelButton: true,
		// 	confirmButtonColor: "#3085d6",
		// 	cancelButtonColor: "#d33",
		// 	confirmButtonText: "Ya, Yakin",
		// 	cancelButtonText: "Tidak, Tutup"
		// }).then((result) => {
		// 	if (result.value == true) {
		// 		postDataRequest("<?= base_url('WMS/Distribusi/ValidasiPengeluaranBarang/deleteDataPickingValidation') ?>", {
		// 			pickingValidationId
		// 		}, 'POST').then((response) => {
		// 			if (response == true) {
		// 				message_topright('success', 'Data berhasil dihapus');
		// 				setTimeout(() => location.reload(), 1000);
		// 			}
		// 			if (response == false) message_topright('error', 'Data gagal dihapus');
		// 		})
		// 	}
		// });

	}

	const handlerGetDataByFDJR = (fdjrId) => {
		if (fdjrId == "") {
			message('Error!', 'Pilih No Surat Tugas Pengiriman terlebih dahulu!', 'error')
			return false;
		}
		Swal.showLoading()
		postDataRequest("<?= base_url('WMS/Distribusi/ValidasiPengeluaranBarang/getDataFdjrById') ?>", {
			fdjrId,
			pickingValidationId: typeof pickingValidationId !== 'undefined' ? pickingValidationId : null
		}, 'POST').then((response) => {

			if (response) {
				let html = "";
				$("#noPPB").empty();
				html += `<option value"">--Pilih No. PPB--</option>`
				response.map((item) => {
					html += `<option value="${item.picking_order_id}">${item.picking_order_kode}</option>`
				})

				$("#noPPB").append(html)
			}
			swal.close();

		})
	}

	const handlerGetDataProsesValidasi = (noPPB) => {

		const fdjr = $("#noFDJR").children("option").filter(":selected").val();

		if (fdjr == "") {
			message('Error!', 'Pilih No Surat Tugas Pengiriman terlebih dahulu!', 'error')
			return false;
		}

		if (noPPB == "") {
			message('Error!', 'Pilih No PPB terlebih dahulu!', 'error')
			return false;
		}
		// Swal.showLoading()
		postDataRequest("<?= base_url('WMS/Distribusi/ValidasiPengeluaranBarang/handlerGetDataProsesValidasi') ?>", {
			fdjr,
			noPPB
		}, 'POST').then((response) => {
			arrDataDetailTemp = []

			if (response.dataDetailBarang.length > 0) {
				$.each(response.dataDetailBarang, function(i, v) {
					arrDataDetailTemp.push({
						...v
					})
				})
			}

			arrDataKoreksiDoTemp = []
			arrDataKoreksiDoTemp2 = []

			if (response.dataDetailDO.length > 0) {
				$.each(response.dataDetailDO, function(i, v) {
					arrDataKoreksiDoTemp.push({
						noFDJR: $("#noFDJR").val(),
						deliveryOrderId: v.delivery_order_id,
						prioritas: v.delivery_order_no_urut_rute,
					})

					arrDataKoreksiDoTemp2.push({
						...v
					})
				})
			}

			initDataDetailBarang(response.dataDetailBarang)
			initDataKoreksiDO(response.dataDetailDO)
			swal.close();
		})
	}

	const initDataDetailBarang = (response) => {

		if ($.fn.DataTable.isDataTable('#initDataDetailbarang')) {
			$('#initDataDetailbarang').DataTable().destroy();
		}

		$("#initDataDetailbarang tbody").empty();

		if (response.length > 0) {

			$("#driver").val(response[0].driver);
			$("#pickingOrderId").val(response[0].picking_order_id);
			$("#serahTerimaKirimId").val(response[0].serah_terima_kirim_id);
			$("#tglPengiriman").val(response[0].tgl);
			// $("#noPPB").val(response[0].picking_order_id);
			$("#noSerahTerima").val(response[0].serah_terima_kirim_kode);

			arrSKUFirstSame = [];
			$.each(response, function(i, v) {

				const qty_order = v.qty_order == null ? 0 : v.qty_order;
				const qty_ambil = v.qty_ambil == null ? 0 : v.qty_ambil;
				const serah_terima = v.serah_terima == null ? 0 : v.serah_terima;
				const serah_terima_rusak = v.serah_terima_rusak == null ? 0 : v.serah_terima_rusak;


				const dataProsesValidasi = JSON.stringify({
					picking_order_id: v.picking_order_id,
					picking_order_plan_id: v.picking_order_plan_id,
					sku_id: v.sku_id,
					sku_kode: v.sku_kode,
					sku_nama_produk: v.sku_nama_produk,
					sku_kemasan: v.sku_kemasan,
					sku_satuan: v.sku_satuan,
					qty_order: qty_order,
					qty_ambil: qty_ambil,
					serah_terima: serah_terima,
					serah_terima_rusak: serah_terima_rusak,
					depo_detail_nama: v.depo_detail_nama,
					delivery_order_id: null,
					sku_stock_id: v.sku_stock_id,
					sku_expdate: v.sku_expdate,
				})

				let styleTr = "";
				if (parseInt(qty_order) !== parseInt(qty_ambil) || parseInt(qty_order) !== (parseInt(serah_terima) + parseInt(serah_terima_rusak)) || parseInt(qty_ambil) !== parseInt(serah_terima)) {
					styleTr = "background-color: #f87171; color:#0f172a";
					if (typeof pickingValidationId === 'undefined') {
						arrSKUFirstSame.push({
							picking_order_id: v.picking_order_id,
							picking_order_plan_id: v.picking_order_plan_id,
							sku_id: v.sku_id,
							sku_kode: v.sku_kode,
							sku_nama_produk: v.sku_nama_produk,
							sku_kemasan: v.sku_kemasan,
							sku_satuan: v.sku_satuan,
							qty_order: 0,
							qty_ambil: qty_ambil,
							serah_terima: serah_terima,
							serah_terima_rusak: serah_terima_rusak,
							depo_detail_nama: v.depo_detail_nama,
							delivery_order_id: null,
							sku_stock_id: v.sku_stock_id,
							sku_expdate: v.sku_expdate,
						})
					}
				} else {
					styleTr = "background-color: #4ade80; color:#0f172a";
					if (typeof pickingValidationId === 'undefined') {
						arrSKUFirstSame.push({
							picking_order_id: v.picking_order_id,
							picking_order_plan_id: v.picking_order_plan_id,
							sku_id: v.sku_id,
							sku_kode: v.sku_kode,
							sku_nama_produk: v.sku_nama_produk,
							sku_kemasan: v.sku_kemasan,
							sku_satuan: v.sku_satuan,
							qty_order: qty_order,
							qty_ambil: qty_ambil,
							serah_terima: serah_terima,
							serah_terima_rusak: serah_terima_rusak,
							depo_detail_nama: v.depo_detail_nama,
							delivery_order_id: null,
							sku_stock_id: v.sku_stock_id,
							sku_expdate: v.sku_expdate,
						})
					}
				}

				if (parseInt(serah_terima_rusak) > 0) {
					styleTr = "background-color: #f87171; color:#0f172a";
				}

				let newStyle = "";
				const findData = arrTempProsesData.find((item) => (item.noFDJR === $("#noFDJR").val()) && (item.pickingOrderPlanId === v.picking_order_plan_id) && (item.skuIdDetailBarang === v.sku_id));

				if (typeof findData !== 'undefined') {
					if (findData.qtyValidasiBagus !== 0) {
						const qtyValidasi = parseInt(findData.qtyValidasiBagus) + parseInt(findData.qtyValidasiRusak)
						// if (qtyValidasi !== parseInt(qty_order) || qtyValidasi !== parseInt(qty_ambil) || qtyValidasi !== (parseInt(serah_terima) + parseInt(serah_terima_rusak))) {
						if (qtyValidasi !== parseInt(qty_ambil)) {
							newStyle += "background-color: #f87171; color:#0f172a";
						} else {
							newStyle += "background-color: #4ade80; color:#0f172a";
						}
					} else {
						newStyle += "background-color: #f87171; color:#0f172a";
					}
				} else {
					newStyle += "";
				}

				const newParsing = dataProsesValidasi.replace(/\"/g, '\\\'')

				let strMode = "";
				if (typeof mode === 'undefined') {
					strMode += `<td>
                        <button type="button" class="btn btn-primary btn-sm btnProsesValidasiBarang" ${$("#txtstatus").val() == "Draft" ? "" : "disabled"} id="btnProsesValidasiBarang${i}" onclick="handlerProsesValidasiBarang(event, '${newParsing}')">Proses</button>
                    </td>`
				}

				$("#initDataDetailbarang tbody").append(`
              <tr class="text-center" style="${newStyle == "" ? styleTr : newStyle}">
                  <td>${i + 1}</td>
                  <td>${v.principal}</td>
                  <td>${v.sku_kode}</td>
                  <td>${v.sku_nama_produk}</td>
                  <td>${v.sku_kemasan}</td>
                  <td>${v.sku_satuan}</td>
                  ${strMode}
              </tr>
          `)

				$(`#btnProsesValidasiBarang${i}`).attr('data-prosesValidasi', dataProsesValidasi)
				$(`#btnViewDetailBarang${i}`).attr('data-prosesValidasi', dataProsesValidasi)
			})

		} else {
			$("#driver").val('');
			$("#tglPengiriman").val('');
			// $("#noPPB").val('');
		}
		$("#initDataDetailbarang").DataTable()
		if (typeof pickingValidationId === 'undefined') {
			if (arrSKUFirstSame.length > 0) {
				arrSKUFirstSame.forEach(element => {

					const filterProsesValidasi = arrTempProsesValidasi.find((item) => (item.noFDJR === $("#noFDJR").val()) && (item.skuId === element.sku_id) && (item.qty === 0) && (item.qtyBagus === element.qty_order) && (item.qtyRusak === 0) && (item.type === 'remove'));

					const findProsesData = arrTempProsesData.find((item) => (item.noFDJR === $("#noFDJR").val()) && (item.pickingOrderPlanId === element.picking_order_plan_id) && (item.skuIdDetailBarang === element.sku_id));

					if (typeof findProsesData === 'undefined') {
						arrTempProsesData.push({
							noFDJR: $("#noFDJR").val(),
							pickingOrderPlanId: element.picking_order_plan_id,
							skuIdDetailBarang: element.sku_id,
							qtyValidasiBagus: element.qty_order,
							qtyValidasiRusak: 0
						});
					}

					if (typeof filterProsesValidasi === 'undefined') {
						arrTempProsesValidasi.push({
							noFDJR: $("#noFDJR").val(),
							skuStockId: element.sku_stock_id,
							skuExpDate: element.sku_expdate,
							deliveryOrderId: element.delivery_order_id,
							// depoDetailNama: element.depo_detail_nama,
							skuId: element.sku_id,
							skuKode: element.sku_kode,
							skuNamaProduk: element.sku_nama_produk,
							skuKemasan: element.sku_kemasan,
							skuSatuan: element.sku_satuan,
							qty: 0,
							qtyBagus: element.qty_order,
							qtyRusak: 0,
							status: null,
							type: 'remove'
						});
					}

					postDataRequest("<?= base_url('WMS/Distribusi/ValidasiPengeluaranBarang/getDetailBarangDetailBySkuId') ?>", {
						fdjrId: $('#noFDJR').val(),
						noPPB: typeof ppbId === 'undefined' ? $('#noPPB').val() : ppbId,
						skuId: element.sku_id
					}, 'POST').then((response) => {

						if (response.length > 0) {

							let awal = parseInt(element.qty_order);
							let arrTemp = [];
							arrTemp = [];
							response.forEach((item) => {
								let qty = 0;

								if (awal <= parseInt(item.jumlah_order)) {
									if (awal <= 0) {
										//nilai minus
										qty = parseInt(item.jumlah_order)
									} else {
										//kurangi qty order denga awal
										qty = parseInt(item.jumlah_order) - awal
									}
								}

								awal -= parseInt(item.jumlah_order);



								arrTemp.push({
									doId: item.delivery_order_id,
									prioritas: item.delivery_order_no_urut_rute,
									akumulasi: qty > 0 ? qty : undefined
								})

							})

							arrTempProsesValidasiForDO.push({
								noFDJR: $("#noFDJR").val(),
								skuStockId: element.sku_stock_id,
								skuExpDate: element.sku_expdate,
								skuId: element.sku_id,
								qtyBagus: element.qty_order,
								qtyRusak: 0,
								type: 'remove',
								data: arrTemp
							});

						}
					})


					// postDataRequest("<?= base_url('WMS/Distribusi/ValidasiPengeluaranBarang/getAkumulasiDoByProsedure') ?>", {
					//   fdjrId: $('#noFDJR').val(),
					//   noPPB: $('#noPPB').val(),
					//   skuId: element.sku_id,
					//   value: element.qty_order
					// }, 'POST').then((response) => {
					//   if (response) {
					//     let arrTemp = [];
					//     arrTemp = [];
					//     $.each(response, function(i, v) {
					//       arrTemp.push({
					//         doId: v.delivery_order_id,
					//         prioritas: v.no_urut_rute,
					//         akumulasi: v.idx2 == 1 ? Math.abs(v.kumulatif) : undefined
					//       })
					//     })
					//     arrTempProsesValidasiForDO.push({
					//       noFDJR: $("#noFDJR").val(),
					//       skuStockId: element.sku_stock_id,
					//       skuExpDate: element.sku_expdate,
					//       skuId: element.sku_id,
					//       qtyBagus: element.qty_order,
					//       qtyRusak: 0,
					//       type: 'remove',
					//       data: arrTemp
					//     });
					//   }
					// })

				});

			}
		}
	}

	const initDataKoreksiDO = (response) => {

		if (response.length > 0) {
			if ($.fn.DataTable.isDataTable('#initDataKoreksiDO')) {
				$('#initDataKoreksiDO').DataTable().destroy();
			}

			$("#initDataKoreksiDO tbody").empty();
			$.each(response, function(i, v) {
				const dataActionKoreksiDO = JSON.stringify({
					delivery_order_id: v.delivery_order_id,
					delivery_order_kode: v.delivery_order_kode,
				})

				let strMode = "";

				const filter = arrDropDOdiSKU.find((item) => item.noFDJR === $("#noFDJR").val() && item.deliveryOrderId === v.delivery_order_id);
				if (typeof filter !== 'undefined') {
					if (filter.type == 'semua') {
						strMode += "Drop DO";
					} else {
						strMode += "Drop per SKU";
					}
				} else {
					strMode += "-"
				}

				// if (typeof mode === 'undefined') {
				//   strMode += `<td>
				//                 <button type="button" class="btn btn-primary btn-sm" id="btnActionKoreksiDO${i}" ${$("#txtstatus").val() == "Draft" ? "" : "disabled"} onclick="handlerDropDetailBarang(event, '${v.delivery_order_id}', 'drop di depan')">Drop</button>
				//             </td>`
				// }

				$("#initDataKoreksiDO tbody").append(`
              <tr class="text-center">
                  <td><input type="checkbox" name="CheckboxDO" id="check-do-${i}" value="${v.delivery_order_id}"></td>
                  <td>${v.delivery_order_no_urut_rute}</td>
                  <td>${v.delivery_order_kode}</td>
                  <td>${v.delivery_order_kirim_nama}</td>
                  <td>${v.delivery_order_kirim_alamat}</td>
                  <td>${v.segment1 == null ? '-' : v.segment1}</td>
                  <td>${v.segment2 == null ? '-' : v.segment2}</td>
                  <td>${v.segment3 == null ? '-' : v.segment3}</td>
                  <td>${strMode}</td>
              </tr>
          `)

				$(`#btnActionKoreksiDO${i}`).attr('data-actionKoreksi', dataActionKoreksiDO)
			})

			$("#initDataKoreksiDO").DataTable()
		}
	}

	const handlerProsesValidasiBarang = (event, dataRequest) => {
		// const dataProsesValidasi = JSON.parse(event.currentTarget.getAttribute('data-prosesValidasi'));
		const requestReplace = dataRequest.replaceAll("\'", '"')
		const requestReplace2 = requestReplace.replaceAll(/\\/g, '')
		const dataProsesValidasi = JSON.parse(requestReplace2);

		$("#modalProsesValidasi").modal('show')

		// $("#depoDetailNama").val(dataProsesValidasi.depo_detail_nama)
		$("#skuId").val(dataProsesValidasi.sku_id)
		$("#skuStockId").val(dataProsesValidasi.sku_stock_id)
		$("#skuExpDate").val(dataProsesValidasi.sku_expdate)

		$("#kodeSKU").val(dataProsesValidasi.sku_kode)
		$("#namaSKU").val(dataProsesValidasi.sku_nama_produk)
		$("#kemasanSKU").val(dataProsesValidasi.sku_kemasan)
		$("#satuanSKU").val(dataProsesValidasi.sku_satuan)

		$("#initDataDetailProsesValidasi > tbody").empty();

		let qtyBagus = "";
		let qtyRusak = "";

		if (typeof pickingValidationId !== 'undefined') {
			const findData = arrTempProsesData.find((item) => (item.noFDJR === $("#noFDJR").val()) && (item.skuIdDetailBarang === dataProsesValidasi.sku_id));
			if (typeof findData !== 'undefined') {
				qtyBagus += findData.qtyValidasiBagus;
				qtyRusak += findData.qtyValidasiRusak;
			} else {
				qtyBagus += "";
				qtyRusak += "";
			}
		} else {
			const findData = arrTempProsesValidasi.find((item) => (item.type !== 'drop') && (item.noFDJR === $("#noFDJR").val()) && (item.skuId === dataProsesValidasi.sku_id));
			if (typeof findData !== 'undefined') {
				qtyBagus += findData.qtyBagus;
				qtyRusak += findData.qtyRusak;
			} else {
				qtyBagus += "";
				qtyRusak += "";
			}
		}

		const qtyOrder = dataProsesValidasi.qty_order == null ? 0 : dataProsesValidasi.qty_order;

		$("#initDataDetailProsesValidasi > tbody").append(`
        <tr class="text-center">
            <td>
              <input type="hidden" id="skuStockIdDetail" value="${dataProsesValidasi.sku_stock_id}"/>
              <input type="hidden" id="skuExpDateDetail" value="${dataProsesValidasi.sku_expdate}"/>
              <input type="hidden" id="deliveryOrderIdDetail" value="${dataProsesValidasi.delivery_order_id}"/>
              <input type="hidden" id="pickingOrderPlanId" value="${dataProsesValidasi.picking_order_plan_id}"/>
              <input type="hidden" id="skuIdDetailBarang" value="${dataProsesValidasi.sku_id}"/>
              <input type="text" class="form-control numeric" id="qtyOrderProsesValidasi" value="${qtyOrder}" disabled/>
            </td>
            <td><input type="text" class="form-control numeric" id="qtyAmbilProsesValidasi" value="${dataProsesValidasi.qty_ambil == null ? 0 : dataProsesValidasi.qty_ambil}" disabled/></td>
            <td><input type="text" class="form-control numeric" id="serahTerimaProsesValidasi" value="${dataProsesValidasi.serah_terima == null ? 0 : dataProsesValidasi.serah_terima}" disabled/></td>
            <td><input type="text" class="form-control numeric" id="serahTerimaRusakProsesValidasi" value="${dataProsesValidasi.serah_terima_rusak == null ? 0 : dataProsesValidasi.serah_terima_rusak}" disabled/></td>
            <td><input type="text" class="form-control numeric" onchange="handlerAkumulasiSKUtoDO(this.value, '${dataProsesValidasi.sku_id}')" id="qtyValidasiBagusProsesValidasi" value="${qtyBagus == "" ? 0 : parseInt(qtyBagus)}"/></td>
            <td><input type="text" class="form-control numeric" id="qtyValidasiRusakProsesValidasi" value="${qtyRusak == "" ? 0 : parseInt(qtyRusak)}"/></td>
        </tr>
    `)

		let qtyDropDiSKU = [];
		qtyDropDiSKU = [];

		postDataRequest("<?= base_url('WMS/Distribusi/ValidasiPengeluaranBarang/getDetailBarangDetailBySkuId') ?>", {
			fdjrId: $('#noFDJR').val(),
			noPPB: typeof ppbId === 'undefined' ? $('#noPPB').val() : ppbId,
			skuId: dataProsesValidasi.sku_id
		}, 'POST').then((response) => {

			console.log({
				drop: arrDropDOdiSKU,
				skuValidasi: arrTempProsesValidasi
			});

			if (response.length > 0) {

				$("#initDetailDOValidasi").show();

				if ($.fn.DataTable.isDataTable('#initViewDataDetailBarang')) {
					$('#initViewDataDetailBarang').DataTable().destroy();
				}
				$("#initViewDataDetailBarang tbody").empty();

				let result = arrTempProsesValidasiForDO.reduce((unique, o) => {
					if (!unique.some(obj => obj.noFDJR === o.noFDJR && obj.skuId === o.skuId)) {
						unique.push(o);
					}
					return unique;
				}, []);


				const findData = result.find((item) => (item.noFDJR === $("#noFDJR").val()) && (item.skuId === dataProsesValidasi.sku_id));

				$.each(response, function(i, v) {

					let akumulasi = "";
					let status = "";
					let promo = "";
					let dsbld = "";

					if (v.is_promo == 1) {
						promo += `<span class="badge" style="background: green">Ya</span>`;
						dsbld += `disabled`;
					}
					if (v.is_promo == null || v.is_promo == 0) {
						promo += `<span class="badge" style="background: red">Tidak</span>`;
						dsbld += ``;
					}

					if (typeof findData !== 'undefined') {
						if (findData.skuId == dataProsesValidasi.sku_id) {
							findData.data.map((data) => {
								if (data.doId == v.delivery_order_id) {
									let acc = "";
									if (data.akumulasi !== undefined) {
										acc += -data.akumulasi
									}

									if (parseInt(acc) < 0) {
										akumulasi += `${Math.abs(acc)} <input type="hidden" value="${Math.abs(acc)}">`;
										status += `<span class="badge" style="background: red">Kurang</span>`
									}
									if (parseInt(acc) >= 0 || acc == "") {
										// akumulasi += `${acc} <input type="hidden" value="${acc}">`
										status += `<span class="badge" style="background: green">Cukup</span>`
									}
								}
								// else {
								//   status += `<span class="badge" style="background: green">Cukup</span>`
								// }
							})
						}
					} else {
						if (typeof pickingValidationId !== 'undefined') {
							status += `<span class="badge" style="background: green">Cukup</span>`
						}
					}

					let dropcihuy = false;
					let sebagiancihuy = false;
					let qtySebagiab = "";
					let qtyJmlOrder = "";

					const findDropDataDropSemua = arrDropDOdiSKU.find((item) => (item.noFDJR === $("#noFDJR").val()) && (item.deliveryOrderId === v.delivery_order_id) && (item.type === 'semua'))
					if (typeof findDropDataDropSemua !== 'undefined') {
						findDropDataDropSemua.data.map((data) => {
							if (data.skuId == dataProsesValidasi.sku_id) {
								qtyDropDiSKU.push(data.qty);

							}
						})
						dropcihuy = true;
					} else {
						dropcihuy = false;
					}

					const findDropDataDropSebagian = arrDropDOdiSKU.find((item) => (item.noFDJR === $("#noFDJR").val()) && (item.deliveryOrderId === v.delivery_order_id) && (item.skuId === $("#skuId").val()) && (item.type === 'sebagian'))
					if (typeof findDropDataDropSebagian !== 'undefined') {
						findDropDataDropSebagian.data.map((data) => {
							if (data.skuId == dataProsesValidasi.sku_id) {
								qtyDropDiSKU.push(data.qty);
								qtySebagiab += data.qty
							}
						})
						sebagiancihuy = true;
					} else {
						sebagiancihuy = false;
					}

					if ((dropcihuy == true) && (sebagiancihuy == false)) {
						qtyJmlOrder += 0
					} else if ((dropcihuy == false) && (sebagiancihuy == true)) {
						qtyJmlOrder += parseInt(v.jumlah_order) - parseInt(qtySebagiab);
					} else {
						qtyJmlOrder += v.jumlah_order
					}

					$("#initViewDataDetailBarang tbody").append(`
              <tr class="text-center">
                  <td>${v.delivery_order_no_urut_rute} <input type="hidden" class="form-control" value="${v.delivery_order_id}"></td>
                  <td>${v.delivery_order_kode}</td>
                  <td>${v.delivery_order_kirim_nama}</td>
                  <td>${v.delivery_order_kirim_alamat}</td>
                  <td>${v.segment1}</td>
                  <td>${v.segment2}</td>
                  <td>${v.segment3}</td>
                  <td>${qtyJmlOrder}</td>
                  <td>${akumulasi}</td>
                  <td>${status}</td>
                  <td>${promo}</td>
                  <td>
                    <input type="checkbox" class="form-control" id="chkDropQtyDOPerSKU${v.delivery_order_id}" ${sebagiancihuy == false ? '' : 'checked'} ${dropcihuy == false ? '' : 'disabled'} style="transform: scale(1.5)" onchange="handlerDropQtyDOPerSKU('${v.delivery_order_id}', event, '${requestReplace2.replace(/"/g, '\\\'')}')"/>&nbsp;&nbsp;
                    <input type="text" class="form-control numeric" id="dropQtyDOPerSKU${v.delivery_order_id}" onchange="handlerInputDropQtyDOPerSKU(event, '${v.delivery_order_id}', '${requestReplace2.replace(/"/g, '\\\'')}', '${v.jumlah_order}')" disabled value="${sebagiancihuy == false ? '' : qtySebagiab}"/>
                  </td>
                  <td>
                    <input type="checkbox" class="form-control" id="chkDropQtyDOperDO${v.delivery_order_id}" style="transform: scale(1.5)" ${dropcihuy == false ? '' : 'checked'} ${sebagiancihuy == false ? '' : 'disabled'} onchange="handlerDropQtyDOPerDO('${v.delivery_order_id}', event, '${requestReplace2.replace(/"/g, '\\\'')}')" data-drop="${dropcihuy == false ? 0 : 1}"/>&nbsp;&nbsp;
                    <button type="button" class="btn btn-primary btn-sm btnDropDOPerDO" id="dropQtyDOperDO${v.delivery_order_id}" disabled onclick="handlerDropDetailBarang(event, '${v.delivery_order_id}', 'drop di sku')">Drop</button>
                  </td>
              </tr>
          `)
				})

				const sumArray = qtyDropDiSKU.reduce((acc, currentValue, idx) => {
					return acc + currentValue;
				}, 0)

				$("#qtyOrderProsesValidasi").val(qtyOrder - sumArray)

				$(".btnDropDOPerDO").attr('data-actionKoreksi', JSON.stringify(dataProsesValidasi))

				$("#initViewDataDetailBarang").DataTable({
					columnDefs: [{
						sortable: false,
						targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
					}],
					lengthMenu: [
						[-1],
						['All']
					],
				})
			}
		})
	}

	const handlerDropQtyDOPerSKU = (doId, event, dataRequest) => {
		if (event.currentTarget.checked) {
			$(`#dropQtyDOPerSKU${doId}`).prop('disabled', false);
			$(`#chkDropQtyDOperDO${doId}`).prop('checked', false);
			$(`#chkDropQtyDOperDO${doId}`).prop('disabled', true);
		} else {
			if ($(`#dropQtyDOPerSKU${doId}`).val() !== "") {
				Swal.fire({
					title: "Apakah anda yakin?",
					text: "ingin hapus drop qty sebagian?!",
					icon: "warning",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Ya, Yakin",
					cancelButtonText: "Tidak, Tutup"
				}).then((result) => {
					if (result.value == true) {
						const findIndexData = arrDropDOdiSKU.findIndex((item) => item.noFDJR === $("#noFDJR").val() && item.skuId === $("#skuId").val() && item.deliveryOrderId === doId && item.type === 'sebagian');
						if (findIndexData > -1) { // only splice array when item is found
							arrDropDOdiSKU.splice(findIndexData, 1); // 2nd parameter means remove one item only
						}

						$(`#dropQtyDOPerSKU${doId}`).prop('disabled', true);
						$(`#dropQtyDOPerSKU${doId}`).val('');
						$(`#chkDropQtyDOperDO${doId}`).prop('disabled', false);
						$(`#chkDropQtyDOPerSKU${doId}`).prop('checked', false);

						$('#modalProsesValidasi').fadeOut("slow", function() {
							$(this).hide();
						}).fadeIn("slow", function() {
							$(this).show();
							handlerProsesValidasiBarang(event, dataRequest)
						});

						$('#initDataKoreksiDO').fadeOut("slow", function() {
							$(this).hide();
						}).fadeIn("slow", function() {
							$(this).show();
							initDataKoreksiDO(arrDataKoreksiDoTemp2)
						});

					}

					if (typeof result.value === 'undefined') {
						$(`#chkDropQtyDOPerSKU${doId}`).prop('checked', true);
					}
				});
			} else {
				$(`#dropQtyDOPerSKU${doId}`).prop('disabled', true);
				$(`#dropQtyDOPerSKU${doId}`).val('');
				$(`#chkDropQtyDOperDO${doId}`).prop('disabled', false);

			}
		}
	}

	const handlerDropQtyDOPerDO = (doId, event, dataRequest) => {

		if (event.currentTarget.checked) {
			$(`#dropQtyDOperDO${doId}`).prop('disabled', false);
			$(`#chkDropQtyDOPerSKU${doId}`).prop('checked', false);
			$(`#chkDropQtyDOPerSKU${doId}`).prop('disabled', true);
		} else {

			if (event.currentTarget.getAttribute('data-drop') == 1) {
				Swal.fire({
					title: "Apakah anda yakin?",
					text: "ingin hapus drop do all?!",
					icon: "warning",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Ya, Yakin",
					cancelButtonText: "Tidak, Tutup"
				}).then((result) => {
					if (result.value == true) {
						const findIndexData = arrDropDOdiSKU.findIndex((item) => item.noFDJR === $("#noFDJR").val() && item.deliveryOrderId === doId && item.type === 'semua');
						if (findIndexData > -1) { // only splice array when item is found
							arrDropDOdiSKU.splice(findIndexData, 1); // 2nd parameter means remove one item only
						}

						$(`#dropQtyDOperDO${doId}`).prop('disabled', true);
						$(`#chkDropQtyDOperDO${doId}`).prop('checked', false);

						$('#modalProsesValidasi').fadeOut("slow", function() {
							$(this).hide();
						}).fadeIn("slow", function() {
							$(this).show();
							handlerProsesValidasiBarang(event, dataRequest)
						});

						$('#initDataKoreksiDO').fadeOut("slow", function() {
							$(this).hide();
						}).fadeIn("slow", function() {
							$(this).show();
							initDataKoreksiDO(arrDataKoreksiDoTemp2)
						});

					}

					if (typeof result.value === 'undefined') {
						$(`#chkDropQtyDOperDO${doId}`).prop('checked', true);
					}
				});
			} else {
				$(`#dropQtyDOperDO${doId}`).prop('disabled', true);
				$(`#chkDropQtyDOPerSKU${doId}`).prop('disabled', false);
			}


		}
	}

	const handlerAkumulasiSKUtoDO = (value, skuId) => {

		if (value == "") {
			message("Error!", "Nilai qty validasi tidak boleh kosong, jika kosong isi angka 0!", 'error')
			return false
		}

		let awal = parseInt(value);

		$("#initViewDataDetailBarang > tbody tr").each(function(i, v) {
			let doId = $(this).find("td:eq(0) input[type='hidden']").val();
			let qtyOrder = parseInt($(this).find("td:eq(7)").html());
			let akumulasi = $(this).find("td:eq(8)");
			let status = $(this).find("td:eq(9)");

			let qty = 0;

			if (awal <= qtyOrder) {
				if (awal <= 0) {
					//nilai minus
					qty = qtyOrder
				} else {
					//kurangi qty order denga awal
					qty = qtyOrder - awal
				}
			}

			awal -= qtyOrder;

			if (qty > 0) {
				akumulasi.html(qty)
				status.html(`<span class="badge" style="background:red">Kurang</span>`)

			}

			if (qty == 0) {
				akumulasi.html(``)
				status.html(`<span class="badge" style="background:green">Cukup</span>`)
			}

		})

		// postDataRequest("<?= base_url('WMS/Distribusi/ValidasiPengeluaranBarang/getAkumulasiDoByProsedure') ?>", {
		//   fdjrId: $('#noFDJR').val(),
		//   noPPB: $('#noPPB').val(),
		//   skuId,
		//   value
		// }, 'POST').then((response) => {
		//   console.log(response);

		//   $("#initViewDataDetailBarang > tbody tr").each(function(i, v) {
		//     let doId = $(this).find("td:eq(0) input[type='hidden']").val();
		//     let qtyOrder = $(this).find("td:eq(7)").html();
		//     let akumulasi = $(this).find("td:eq(8)");
		//     let status = $(this).find("td:eq(9)");

		//     akumulasi.html('')
		//     const findData = response.find((item) => item.delivery_order_id === doId)
		//     if (typeof findData !== 'undefined') {
		//       if (findData.kumulatif < 0) {
		//         if (findData.idx2 == 1) {
		//           akumulasi.html(`${Math.abs(findData.kumulatif)} <input type="hidden" value="${Math.abs(findData.kumulatif)}">`)
		//         } else {
		//           akumulasi.html(`${findData.jumlah_order} <input type="hidden" value="${findData.jumlah_order}">`)
		//         }

		//         status.html(`<span class="badge" style="background:red">Kurang</span>`)
		//       }
		//       if (findData.kumulatif >= 0) {
		//         akumulasi.html(``)
		//         status.html(`<span class="badge" style="background:green">Cukup</span>`)
		//       }
		//     }
		//   })
		// })
	}

	const handlerProsesValidasi = (event) => {

		const qtyOrder = parseInt($('#qtyOrderProsesValidasi').val() == "" ? 0 : $('#qtyOrderProsesValidasi').val());
		const qtyAmbil = parseInt($('#qtyAmbilProsesValidasi').val() == "" ? 0 : $('#qtyAmbilProsesValidasi').val());
		const qtyValidasiBagus = parseInt($('#qtyValidasiBagusProsesValidasi').val() == "" ? 0 : $('#qtyValidasiBagusProsesValidasi').val());
		const qtyValidasiRusak = parseInt($('#qtyValidasiRusakProsesValidasi').val() == "" ? 0 : $('#qtyValidasiRusakProsesValidasi').val());

		const pickingOrderPlanId = $("#pickingOrderPlanId").val();
		const skuIdDetailBarang = $("#skuIdDetailBarang").val();

		const skuKurang = qtyValidasiBagus - qtyOrder;
		const kondisi1 = qtyOrder - qtyAmbil
		const kondisi2 = qtyOrder - (qtyValidasiBagus + qtyValidasiRusak)
		// const skuSalahInput = qtyAmbil - (qtyValidasiBagus + qtyValidasiRusak);

		if ($('#qtyValidasiBagusProsesValidasi').val() == "" || $('#qtyValidasiRusakProsesValidasi').val() == "") {
			message("Error!", "Nilai qty validasi tidak boleh kosong, jika kosong isi angka 0!", 'error')
			return false
		}

		const qtyValidasi = JSON.stringify({
			qtyValidasiBagus,
			qtyValidasiRusak
		})

		// if (qtyValidasiBagus > 0) {

		// }

		if (skuKurang < 0) {
			pushDataToArray(JSON.stringify({
				qty: Math.abs(skuKurang),
				qtyValidasiBagus,
				qtyValidasiRusak
			}), 'sku kurang')

			pushDataArrayForDO(qtyValidasi, 'sku kurang');

		}

		if (skuKurang > 0) {
			pushDataToArray(JSON.stringify({
				qty: Math.abs(skuKurang),
				qtyValidasiBagus,
				qtyValidasiRusak
			}), 'drop')

			pushDataArrayForDO(qtyValidasi, 'drop');
		}

		if (skuKurang == 0) {
			pushDataToArray(JSON.stringify({
				qty: Math.abs(skuKurang),
				qtyValidasiBagus,
				qtyValidasiRusak
			}), 'remove')

			pushDataArrayForDO(qtyValidasi, 'remove');
		}

		//kondisi awal untuk salah input
		// if (skuSalahInput < 0 || skuSalahInput > 0) {
		//   pushDataToArray(JSON.stringify({
		//     qty: Math.abs(skuSalahInput),
		//     qtyValidasiBagus,
		//     qtyValidasiRusak
		//   }), 'sku salah input')

		//   pushDataArrayForDO(qtyValidasi, 'sku salah input');
		// }

		if (kondisi1 != kondisi2) {
			pushDataToArray(JSON.stringify({
				qty: Math.abs((qtyValidasiBagus + qtyValidasiRusak) - qtyAmbil),
				qtyValidasiBagus,
				qtyValidasiRusak
			}), 'sku salah input')

			pushDataArrayForDO(qtyValidasi, 'sku salah input');
		}

		if (qtyValidasiRusak != "" || qtyValidasiRusak != 0) {
			pushDataToArray(JSON.stringify({
				qty: Math.abs(qtyValidasiRusak),
				qtyValidasiBagus,
				qtyValidasiRusak
			}), 'sku rusak')

			pushDataArrayForDO(qtyValidasi, 'sku rusak');
		}

		if (qtyValidasiBagus == 0) {
			const filterDataProsesValidasi = arrTempProsesValidasi.findIndex((item) => (item.noFDJR === $("#noFDJR").val()) && (item.skuId === $("#skuId").val()));
			if (filterDataProsesValidasi > -1) { // only splice array when item is found
				arrTempProsesValidasi.splice(filterDataProsesValidasi, 1); // 2nd parameter means remove one item only
			}
			// filterDataProsesValidasi.map((item) => arrTempProsesValidasi.push(...item));

			const filterDataProsesValidasiForDO = arrTempProsesValidasiForDO.findIndex((item) => (item.noFDJR === $("#noFDJR").val()) && (item.skuId === $("#skuId").val()));
			if (filterDataProsesValidasiForDO > -1) { // only splice array when item is found
				arrTempProsesValidasiForDO.splice(filterDataProsesValidasiForDO, 1); // 2nd parameter means remove one item only
			}
			// filterDataProsesValidasiForDO.map((item) => arrTempProsesValidasiForDO.push(...item));
		}

		if (arrTempProsesData.length === 0) {
			arrTempProsesData.push({
				noFDJR: $("#noFDJR").val(),
				pickingOrderPlanId,
				skuIdDetailBarang,
				qtyValidasiBagus,
				qtyValidasiRusak
			});
		} else {
			const findData = arrTempProsesData.find((item) => (item.noFDJR === $("#noFDJR").val()) && (item.pickingOrderPlanId === pickingOrderPlanId) && (item.skuIdDetailBarang === skuIdDetailBarang));
			if (typeof findData === 'undefined') {
				arrTempProsesData.push({
					noFDJR: $("#noFDJR").val(),
					pickingOrderPlanId,
					skuIdDetailBarang,
					qtyValidasiBagus,
					qtyValidasiRusak
				});
			} else {
				const findIndexData = arrTempProsesData.findIndex((item) => (item.noFDJR === $("#noFDJR").val()) && (item.pickingOrderPlanId === pickingOrderPlanId) && (item.skuIdDetailBarang === skuIdDetailBarang));
				arrTempProsesData[findIndexData]['qtyValidasiBagus'] = qtyValidasiBagus;
				arrTempProsesData[findIndexData]['qtyValidasiRusak'] = qtyValidasiRusak;

			}
		}

		if (arrTempProsesValidasi.length > 0) {

			const filterData1 = arrTempProsesValidasi.map((item, index) => {
				if ((item.skuId === $("#skuId").val()) && (item.skuStockId === $("#skuStockIdDetail").val())) {
					return (item.qtyBagus != qtyValidasiBagus) || (item.qtyRusak != qtyValidasiRusak) ? index : ''
				}
			}).filter((data) => typeof data !== 'undefined').filter(String);

			// const filterData1 = arrTempProsesValidasi.map((item, index) => (item.skuId === $("#skuId").val()) && (item.skuStockId === $("#skuStockIdDetail").val()) && (item.qtyBagus != qtyValidasiBagus) || (item.qtyRusak != qtyValidasiRusak) ? index : '').filter(String);
			// console.log('index', filterData1);

			for (let i = filterData1.length - 1; i >= 0; i--) {
				arrTempProsesValidasi.splice(filterData1[i], 1)
			}

			// filterData1.map((val) =>  arrTempProsesValidasi.splice(val, 1));

			// const yayaya = arrTempProsesValidasi.reduce((unique, o) => {
			//   if (!unique.some(obj => (obj.noFDJR === o.noFDJR) && (obj.qty === o.qty) && (obj.qtyBagus === o.qtyBagus) && (obj.qtyRusak === o.qtyRusak) && (obj.skuId === o.skuId) && (obj.type === o.type))) {
			//     unique.push(o);
			//   }
			//   return unique;
			// }, [])

			// arrTempProsesValidasi = [];
			// yayaya.map((item) => {
			//   arrTempProsesValidasi.push({
			//     ...item
			//   })
			// });

			// console.log('awda', arrTempProsesValidasi);

		}

		if (arrTempProsesValidasiForDO.length > 0) {


			const filterData1 = arrTempProsesValidasiForDO.map((item, index) => {
				if ((item.skuId === $("#skuId").val()) && (item.skuStockId === $("#skuStockIdDetail").val())) {
					return (item.qtyBagus != qtyValidasiBagus) || (item.qtyRusak != qtyValidasiRusak) ? index : ''
				}
			}).filter((data) => typeof data !== 'undefined').filter(String);

			// const filterData1 = arrTempProsesValidasiForDO.map((item, index) => (item.skuId === $("#skuId").val()) && (item.skuStockId === $("#skuStockIdDetail").val()) && (item.qtyBagus != qtyValidasiBagus) || (item.qtyRusak != qtyValidasiRusak) ? index : '').filter(String);

			for (let i = filterData1.length - 1; i >= 0; i--) {
				arrTempProsesValidasiForDO.splice(filterData1[i], 1)
			}
			// filterData1.map((val) => arrTempProsesValidasiForDO.splice(val, 1));
		}

		$('#initDataDetailbarang').fadeOut("slow", function() {
			$(this).hide();
		}).fadeIn("slow", function() {
			$(this).show();
			initDataDetailBarang(arrDataDetailTemp)
		});

		$('#showDataSKUSalahInput').fadeOut("slow", function() {
			$(this).hide();
		}).fadeIn("slow", function() {
			$(this).show();
			showDataSKUSalahInput()
			const filterData = arrTempProsesValidasi.filter((item) => item.type === 'sku salah input');
			if (filterData.length == 0) {
				$('#showDataSKUSalahInput').hide();
			}
		});

		$('#showDataSKUYangKurang').fadeOut("slow", function() {
			$(this).hide();
		}).fadeIn("slow", function() {
			$(this).show();
			showDataSKUYangKurang()

			const filterData = arrTempProsesValidasi.filter((item) => item.type === 'sku kurang');
			if (filterData.length == 0) {
				$('#showDataSKUYangKurang').hide();
			}
		});

		$('#showDataSKUKembaliKeLokasi').fadeOut("slow", function() {
			$(this).hide();
		}).fadeIn("slow", function() {
			$(this).show();
			initDataSKUKembaliKeLokasi()

			const dataResult = dataSKUKembaliKeLokasi();
			if (Object.keys(dataResult).length == 0) {
				$('#showDataSKUKembaliKeLokasi').hide();
			}
		});



		message('Success!', 'data berhasil disimpan', 'success');
		$("#modalProsesValidasi").modal('hide')

	}

	const pushDataToArray = (data, typeData) => {
		const dataQty = JSON.parse(data);

		// if (typeData == "sku kurang") {
		//   const filterData = arrTempProsesValidasi.filter((item) => (item.type !== 'drop') && (item.qtyBagus != dataQty.qtyValidasiBagus));
		//   arrTempProsesValidasi = []
		//   filterData.map((newData) => {
		//     return arrTempProsesValidasi.push({
		//       ...newData
		//     })
		//   });
		// }

		// if (typeData == "drop") {
		//   const filterData = arrTempProsesValidasi.filter((item) => (item.type !== 'sku kurang') && (item.qtyBagus != dataQty.qtyValidasiBagus));
		//   arrTempProsesValidasi = []
		//   filterData.map((newData) => {
		//     return arrTempProsesValidasi.push({
		//       ...newData
		//     })
		//   });
		// }

		// if (typeData == "remove") {
		//   const filterData = arrTempProsesValidasi.filter((item) => (item.skuId !== $("#skuId").val()) && (item.skuStockId !== $("#skuStockIdDetail").val()) && (item.type !== 'sku kurang' || item.type !== 'drop'));
		//   arrTempProsesValidasi = []
		//   filterData.map((newData) => {
		//     return arrTempProsesValidasi.push({
		//       ...newData
		//     })
		//   });

		//   const filter = arrTempProsesValidasiForDO.filter((item) => (item.skuId !== $("#skuId").val()) && (item.skuStockId !== $("#skuStockIdDetail").val()));
		//   arrTempProsesValidasiForDO = []
		//   filter.map((newData) => {
		//     return arrTempProsesValidasiForDO.push({
		//       ...newData
		//     })
		//   });
		// }

		if (dataQty.qty !== 0) {
			pushData(dataQty, typeData)
		} else {
			pushData(dataQty, typeData)
		}
	}

	const pushData = (dataQty, typeData) => {
		if (arrTempProsesValidasi.length === 0) {
			arrTempProsesValidasi.push({
				noFDJR: $("#noFDJR").val(),
				skuStockId: $('#skuStockIdDetail').val(),
				skuExpDate: $('#skuExpDateDetail').val(),
				deliveryOrderId: $('#deliveryOrderIdDetail').val(),
				// depoDetailNama: $("#depoDetailNama").val(),
				skuId: $("#skuId").val(),
				skuKode: $("#kodeSKU").val(),
				skuNamaProduk: $("#namaSKU").val(),
				skuKemasan: $("#kemasanSKU").val(),
				skuSatuan: $("#satuanSKU").val(),
				qty: dataQty.qty,
				qtyBagus: dataQty.qtyValidasiBagus,
				qtyRusak: dataQty.qtyValidasiRusak,
				status: null,
				type: typeData
			});
		} else {
			const findData = arrTempProsesValidasi.find((item) => (item.type === typeData) && (item.noFDJR === $("#noFDJR").val()) && (item.skuId === $("#skuId").val()));
			if (typeof findData === 'undefined') {
				arrTempProsesValidasi.push({
					noFDJR: $("#noFDJR").val(),
					skuStockId: $('#skuStockIdDetail').val(),
					skuExpDate: $('#skuExpDateDetail').val(),
					deliveryOrderId: $('#deliveryOrderIdDetail').val(),
					// depoDetailNama: $("#depoDetailNama").val(),
					skuId: $("#skuId").val(),
					skuKode: $("#kodeSKU").val(),
					skuNamaProduk: $("#namaSKU").val(),
					skuKemasan: $("#kemasanSKU").val(),
					skuSatuan: $("#satuanSKU").val(),
					qty: dataQty.qty,
					qtyBagus: dataQty.qtyValidasiBagus,
					qtyRusak: dataQty.qtyValidasiRusak,
					status: null,
					type: typeData
				});
			} else {
				const findIndexData = arrTempProsesValidasi.findIndex((item) => (item.type === typeData) && (item.noFDJR === $("#noFDJR").val()) && (item.skuId === $("#skuId").val()));
				arrTempProsesValidasi[findIndexData]['qty'] = dataQty.qty;
				arrTempProsesValidasi[findIndexData]['qtyBagus'] = dataQty.qtyValidasiBagus;
				arrTempProsesValidasi[findIndexData]['qtyRusak'] = dataQty.qtyValidasiRusak;
			}

		}

		if (typeData === 'remove') {
			const findIndexData = arrTempProsesValidasi.findIndex((item) => (item.type === 'sku kurang' || item.type === 'drop') && (item.noFDJR === $("#noFDJR").val()) && (item.skuId === $("#skuId").val()));
			if (findIndexData > -1) { // only splice array when item is found
				arrTempProsesValidasi.splice(findIndexData, 1); // 2nd parameter means remove one item only
			}
			// if (findIndexData !== -1) {
			//   arrTempProsesValidasi[findIndexData]['qty'] = dataQty.qty;
			//   arrTempProsesValidasi[findIndexData]['qtyBagus'] = dataQty.qtyValidasiBagus;
			//   arrTempProsesValidasi[findIndexData]['qtyRusak'] = dataQty.qtyValidasiRusak;
			// }
		}
	}

	const pushDataArrayForDO = (data, type) => {
		const dataQty = JSON.parse(data);
		let arrTemp = [];

		$("#initViewDataDetailBarang > tbody tr").each(function() {
			let doId = $(this).find("td:eq(0) input[type='hidden']").val();
			let prioritas = $(this).find("td:eq(0)").html();
			let akumulasi = $(this).find("td:eq(8)").html();

			arrTemp.push({
				doId,
				prioritas: prioritas.split(" ")[0],
				akumulasi: akumulasi.split(" ")[0]
			})
		})

		if (arrTempProsesValidasiForDO.length === 0) {
			arrTempProsesValidasiForDO.push({
				noFDJR: $("#noFDJR").val(),
				skuStockId: $('#skuStockIdDetail').val(),
				skuExpDate: $('#skuExpDateDetail').val(),
				skuId: $("#skuId").val(),
				qtyBagus: dataQty.qtyValidasiBagus,
				qtyRusak: dataQty.qtyValidasiRusak,
				type,
				data: arrTemp
			});
		} else {
			const findData = arrTempProsesValidasiForDO.find((item) => (item.type === type) && (item.noFDJR === $("#noFDJR").val()) && (item.skuId === $("#skuId").val()));
			if (typeof findData === 'undefined') {
				arrTempProsesValidasiForDO.push({
					noFDJR: $("#noFDJR").val(),
					skuStockId: $('#skuStockIdDetail').val(),
					skuExpDate: $('#skuExpDateDetail').val(),
					skuId: $("#skuId").val(),
					qtyBagus: dataQty.qtyValidasiBagus,
					qtyRusak: dataQty.qtyValidasiRusak,
					type,
					data: arrTemp
				});
			} else {
				const findIndexData = arrTempProsesValidasiForDO.findIndex((item) => (item.type === type) && (item.noFDJR === $("#noFDJR").val()) && (item.skuId === $("#skuId").val()));
				if (findIndexData > -1) { // only splice array when item is found
					arrTempProsesValidasiForDO.splice(findIndexData, 1); // 2nd parameter means remove one item only
				}
				// arrTempProsesValidasiForDO[findIndexData]['qtyBagus'] = dataQty.qtyValidasiBagus;
				// arrTempProsesValidasiForDO[findIndexData]['qtyRusak'] = dataQty.qtyValidasiRusak;
				// arrTempProsesValidasiForDO[findIndexData]['data'] = arrTemp;
			}
		}
	}

	const handlerDropDetailBarang = (event, doId, type) => {
		const dataDropDetailBarang = JSON.parse(event.currentTarget.getAttribute('data-actionKoreksi'));
		// console.log('asu', event.currentTarget.getAttribute('data-actionKoreksi'));
		const newParsing = event.currentTarget.getAttribute('data-actionKoreksi').replace(/\"/g, '\\\'')
		// console.log('asu sak marine', newParsing);
		$(".btn_pilih_sku").attr('data-request', newParsing)

		$("#modalDropKoreksiDO").modal('show')

		postDataRequest("<?= base_url('WMS/Distribusi/ValidasiPengeluaranBarang/getDetailDropKoreksiDObyId') ?>", {
			delivery_order_id: doId
		}, 'POST').then((response) => {

			if ($.fn.DataTable.isDataTable('#initDataDropKoreksiDO')) {
				$('#initDataDropKoreksiDO').DataTable().destroy();
			}

			$("#initDataDropKoreksiDO tbody").empty();

			if (response.length > 0) {

				$.each(response, function(i, v) {

					let qtyDrop = "";
					const findData = arrTempProsesValidasi.find((item) => (item.type === 'drop') && (item.noFDJR === $("#noFDJR").val()) && (item.skuId === v.sku_id));
					if (typeof findData !== 'undefined') {
						qtyDrop += findData.qty;
					} else {
						qtyDrop += "";
					}

					let dsbld = "";
					let chckd = "";
					let qtyDropSKU = ""
					if (type == 'drop di sku') {
						dsbld += 'disabled'
						chckd += "checked";
						qtyDropSKU += v.qty
					} else {
						dsbld += ''
						chckd += "";
						qtyDropSKU += ''
					}

					$("#initDataDropKoreksiDO tbody").append(`
              <tr class="text-center">
                  <td>
                    <input type="hidden" id="skuStockId${v.sku_id}" value="${v.sku_stock_id}"/>
                    <input type="hidden" id="skuExpDate${v.sku_id}" value="${v.sku_expdate}"/>
                    <input type="hidden" id="deliveryOrderId${v.sku_id}" value="${doId}">
                    <input type="hidden" id="skuKode${v.sku_id}" value="${v.sku_kode}">
                    <input type="hidden" id="skuNamaProduk${v.sku_id}" value="${v.sku_nama_produk}">
                    <input type="hidden" id="skuKemasan${v.sku_id}" value="${v.sku_kemasan}">
                    <input type="hidden" id="skuSatuan${v.sku_id}" value="${v.sku_satuan}">
                    <input type="checkbox" class="form-control check-item" name="chk-data[]" style="transform: scale(1.5)" id="chk-data[]" value="${v.sku_id}" onclick="handleChangeInputQty('${v.sku_id}', event)" ${chckd} ${dsbld}/>
                  </td>
                  <td>${v.principal}</td>
                  <td>${v.sku_kode}</td>
                  <td>${v.sku_nama_produk}</td>
                  <td>${v.sku_kemasan}</td>
                  <td>${v.sku_satuan}</td>
                  <td>${v.qty}</td>
                  <td><input type="text" class="form-control numeric qtySku" id="qtySku${v.sku_id}" ${dsbld} onchange="handlerQuantityQtyDrop('${v.sku_id}', '${v.qty}', this.value)" value="${qtyDropSKU != "" ? qtyDropSKU : qtyDrop == "" ? "" : qtyDrop}"></td>
              </tr>
          `)
				})
				if (type == 'drop di sku') {
					$("#check-all-pilih-sku").prop('checked', true);
					$("#check-all-pilih-sku").prop('disabled', true);
					$(".btn_pilih_sku").attr('data-type', 'drop di sku');
				} else {
					$("#check-all-pilih-sku").prop('checked', false);
					$("#check-all-pilih-sku").prop('disabled', false);
					$(".btn_pilih_sku").attr('data-type', 'drop di depan');
				}

			}
			$("#initDataDropKoreksiDO").DataTable({
				lengthMenu: [
					[-1],
					['All']
				],
				columnDefs: [{
					sortable: false,
					targets: [0, 1, 2, 3, 4, 5, 6, 7]
				}],
			})
		})
	}

	const checkAllSKU = (event) => {
		const checkboxes = $("input[name='chk-data[]']");
		if (event.currentTarget.checked) {
			for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].type == 'checkbox' && !(checkboxes[i].disabled)) {
					checkboxes[i].checked = true;
					$(".qtySku").prop('disabled', false);
					$(".qtySku").val('');
				}
			}
		} else {
			for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].type == 'checkbox' && !(checkboxes[i].disabled)) {
					checkboxes[i].checked = false;
					$(".qtySku").prop('disabled', true);
					$(".qtySku").val('');
				}
			}
		}
	}

	const handleChangeInputQty = (sku_id, event) => {
		if (event.currentTarget.checked == true) {
			$(`#qtySku${sku_id}`).prop("disabled", false);
			$(".qtySku").val('');
		} else {
			$(`#qtySku${sku_id}`).prop("disabled", true);
			$(".qtySku").val('');
		}
	}

	const handlerQuantityQtyDrop = (sku_id, qty, qtyDrop) => {
		if (qtyDrop !== "") {
			if (parseInt(qtyDrop) > parseInt(qty)) {
				message('Error!', 'Qty drop melebihi jumlah qty', 'error')
				return false;
			}
		}
	}

	const handlerInputDropQtyDOPerSKU = (event, doId, dataRequest, jmlOrder) => {
		const qtyDrop = event.currentTarget.value == "" ? 0 : parseInt(event.currentTarget.value);


		if (qtyDrop == 0 || qtyDrop == "") {
			message('Error!', 'Qty yang akan di drop minimal 1', 'error')
			return false;
		}

		if (qtyDrop > parseInt(jmlOrder)) {
			message('Error!', 'Qty yang akan di drop tidak boleh melebihi jumlah order', 'error')
			return false;
		}

		Swal.fire({
			title: "Apakah anda yakin?",
			text: "Untuk drop qty sebagian?!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Yakin",
			cancelButtonText: "Tidak, Tutup"
		}).then((result) => {
			if (result.value == true) {
				const dataDropdiSku = [{
					skuStockId: $(`#skuStockId`).val(),
					skuExpDate: $(`#skuExpDate`).val(),
					skuId: $("#skuId").val(),
					skuKode: $(`#kodeSKU`).val(),
					skuNamaProduk: $(`#namaSKU`).val(),
					skuKemasan: $(`#kemasanSKU`).val(),
					skuSatuan: $(`#satuanSKU`).val(),
					qty: parseInt(qtyDrop)
				}]


				if (arrDropDOdiSKU.length === 0) {
					arrDropDOdiSKU.push({
						noFDJR: $("#noFDJR").val(),
						deliveryOrderId: doId,
						skuId: $("#skuId").val(),
						type: 'sebagian',
						status: null,
						data: dataDropdiSku
					});
				} else {
					const findIndexData = arrDropDOdiSKU.findIndex((item) => (item.noFDJR !== $("#noFDJR").val()) && (item.deliveryOrderId !== doId) && (item.skuId === $("#skuId").val()) && (item.type === 'semua'));
					if (findIndexData > -1) { // only splice array when item is found
						arrDropDOdiSKU.splice(findIndexData, 1); // 2nd parameter means remove one item only
					}

					const findData = arrDropDOdiSKU.find((item) => (item.noFDJR === $("#noFDJR").val()) && (item.deliveryOrderId === doId) && (item.skuId === $("#skuId").val()) && (item.type === 'sebagian'));
					if (typeof findData === 'undefined') {
						arrDropDOdiSKU.push({
							noFDJR: $("#noFDJR").val(),
							deliveryOrderId: doId,
							skuId: $("#skuId").val(),
							type: 'sebagian',
							status: null,
							data: dataDropdiSku
						});
					} else {
						const findIndexParent = arrDropDOdiSKU.findIndex((item) => (item.noFDJR === $("#noFDJR").val()) && (item.deliveryOrderId === doId) && (item.skuId === $("#skuId").val()) && (item.type === 'sebagian'));
						const findIndexChild = findData.data.findIndex((item) => (item.skuId === $("#skuId").val()));
						const findSkuId = findData.data.find((data) => data.skuId === $("#skuId").val());

						if (typeof findSkuId === 'undefined') {
							const getArrayByIndex = arrDropDOdiSKU[findIndexParent]['data'].map((items) => items)
							getArrayByIndex.push(Object.assign({}, ...dataDropdiSku))
							arrDropDOdiSKU[findIndexParent]['data'] = getArrayByIndex
						} else {
							arrDropDOdiSKU[findIndexParent]['data'][findIndexChild]['qty'] = qtyDrop
						}
					}
				}

				message('Success!', 'drop do sebagian berhasil', 'success');
				// $("#modalDropKoreksiDO").modal('hide')
				$('#modalProsesValidasi').fadeOut("slow", function() {
					$(this).hide();
				}).fadeIn("slow", function() {
					$(this).show();
					handlerProsesValidasiBarang(event, dataRequest)
					// console.log('awd', arrDropDOdiSKU);
					// initDataSKUKembaliKeLokasi()
				});

				$('#initDataKoreksiDO').fadeOut("slow", function() {
					$(this).hide();
				}).fadeIn("slow", function() {
					$(this).show();
					initDataKoreksiDO(arrDataKoreksiDoTemp2)
				});
			}
		});
	}

	$(document).on("click", ".btn_pilih_sku", function() {
		const type = $(this).attr('data-type');
		const dataRequest = $(this).attr('data-request');
		const fdjr = $("#noFDJR").children("option").filter(":selected").val();
		const noPPB = $("#noPPB").children("option").filter(":selected").val();
		// console.log(dataRequest);
		// const newParsing = dataRequest.replace(/\"/g, '\\\'')

		let dataDropdiSKu = [];
		// dataDropdiSKu = [];

		var checkboxes = $("input[name='chk-data[]']");
		for (var i = 0; i < checkboxes.length; i++) {
			if (checkboxes[i].checked == true) {
				// checkboxes[i].disabled = true;

				if (type == 'drop di sku') {
					dataDropdiSKu.push({
						skuStockId: $(`#skuStockId${checkboxes[i].value}`).val(),
						skuExpDate: $(`#skuExpDate${checkboxes[i].value}`).val(),
						skuId: checkboxes[i].value,
						skuKode: $(`#skuKode${checkboxes[i].value}`).val(),
						skuNamaProduk: $(`#skuNamaProduk${checkboxes[i].value}`).val(),
						skuKemasan: $(`#skuKemasan${checkboxes[i].value}`).val(),
						skuSatuan: $(`#skuSatuan${checkboxes[i].value}`).val(),
						qty: parseInt($(`#qtySku${checkboxes[i].value}`).val())
					})

				}

				if (type == 'drop di depan') {
					if ($(`#qtySku${checkboxes[i].value}`).val() == "") {
						message("Error!", "Sku yang dicentang, Qty drop tidak boleh kosong", "error");
						return false
					} else {
						if (arrTempProsesValidasi.length === 0) {
							arrTempProsesValidasi.push({
								noFDJR: $("#noFDJR").val(),
								skuStockId: $('#skuStockId').val(),
								skuExpDate: $('#skuExpDate').val(),
								deliveryOrderId: $("#deliveryOrderId").val(),
								// depoDetailNama: $("#depoDetailNama").val(),
								skuId: checkboxes[i].value,
								skuKode: $("#skuKode").val(),
								skuNamaProduk: $("#skuNamaProduk").val(),
								skuKemasan: $("#skuKemasan").val(),
								skuSatuan: $("#skuSatuan").val(),
								qty: parseInt($(`#qtySku${checkboxes[i].value}`).val()),
								qtyBagus: 0,
								qtyRusak: 0,
								status: null,
								type: 'drop'
							});
						} else {
							const findData = arrTempProsesValidasi.find((item) => (item.type === 'drop') && (item.noFDJR === $("#noFDJR").val()) && (item.skuId === checkboxes[i].value));
							if (typeof findData === 'undefined') {
								arrTempProsesValidasi.push({
									noFDJR: $("#noFDJR").val(),
									skuStockId: $('#skuStockId').val(),
									skuExpDate: $('#skuExpDate').val(),
									deliveryOrderId: $("#deliveryOrderId").val(),
									// depoDetailNama: $("#depoDetailNama").val(),
									skuId: checkboxes[i].value,
									skuKode: $("#skuKode").val(),
									skuNamaProduk: $("#skuNamaProduk").val(),
									skuKemasan: $("#skuKemasan").val(),
									skuSatuan: $("#skuSatuan").val(),
									qty: parseInt($(`#qtySku${checkboxes[i].value}`).val()),
									qtyBagus: 0,
									qtyRusak: 0,
									status: null,
									type: 'drop'
								});
							} else {
								const findIndexData = arrTempProsesValidasi.findIndex((item) => (item.type === 'drop') && (item.noFDJR === $("#noFDJR").val()) && (item.skuId === checkboxes[i].value));
								arrTempProsesValidasi[findIndexData]['qty'] = parseInt($(`#qtySku${checkboxes[i].value}`).val());
							}
						}

						message('Success!', 'drop do berhasil di konfirmasi', 'success');
						$("#modalDropKoreksiDO").modal('hide')
						$('#showDataSKUKembaliKeLokasi').fadeOut("slow", function() {
							$(this).hide();
						}).fadeIn("slow", function() {
							$(this).show();
							initDataSKUKembaliKeLokasi()
						});

					}
				}
			}
		}

		if (type == 'drop di sku') {
			if (arrDropDOdiSKU.length === 0) {
				arrDropDOdiSKU.push({
					noFDJR: $("#noFDJR").val(),
					deliveryOrderId: $(`#deliveryOrderId${$("#skuId").val()}`).val(),
					skuId: $("#skuId").val(),
					type: 'semua',
					status: null,
					data: dataDropdiSKu
				});
			} else {

				const findIndexData = arrDropDOdiSKU.findIndex((item) => (item.noFDJR === $("#noFDJR").val()) && (item.deliveryOrderId === $(`#deliveryOrderId${$("#skuId").val()}`).val()) && (item.skuId === $("#skuId").val()) && (item.type === 'sebagian'));
				if (findIndexData > -1) { // only splice array when item is found
					arrDropDOdiSKU.splice(findIndexData, 1); // 2nd parameter means remove one item only
				}


				const findData = arrDropDOdiSKU.find((item) => (item.noFDJR === $("#noFDJR").val()) && (item.deliveryOrderId === $(`#deliveryOrderId${$("#skuId").val()}`).val()) && (item.type === 'semua'));
				if (typeof findData === 'undefined') {
					arrDropDOdiSKU.push({
						noFDJR: $("#noFDJR").val(),
						deliveryOrderId: $(`#deliveryOrderId${$("#skuId").val()}`).val(),
						skuId: $("#skuId").val(),
						type: 'semua',
						status: null,
						data: dataDropdiSKu
					});
				}
			}

			message('Success!', 'drop do berhasil di konfirmasi', 'success');
			$("#modalDropKoreksiDO").modal('hide')
			$('#modalProsesValidasi').fadeOut("slow", function() {
				$(this).hide();
			}).fadeIn("slow", function() {
				$(this).show();
				handlerProsesValidasiBarang(event, dataRequest)
				// console.log('awd', arrDropDOdiSKU);
				// initDataSKUKembaliKeLokasi()
			});

			$('#initDataKoreksiDO').fadeOut("slow", function() {
				$(this).hide();
			}).fadeIn("slow", function() {
				$(this).show();
				initDataKoreksiDO(arrDataKoreksiDoTemp2)
			});
		}
	});

	const dataSKUKembaliKeLokasi = () => {
		let arrFixSKUKembali = [];
		// const filterDataDrop = arrTempProsesValidasi.filter((item) => item.type === 'drop').map((data) => arrFixSKUKembali.push({
		//   ...data
		// }));

		const fdjr = $("#noFDJR").children("option").filter(":selected").val();
		const noPPB = $("#noPPB").children("option").filter(":selected").val();


		const findDropDataDropSemua = arrDropDOdiSKU.filter((item) => (item.noFDJR === $("#noFDJR").val()) && (item.type === 'semua'))
		const findDropDataDropSebagian = arrDropDOdiSKU.filter((item) => (item.noFDJR === $("#noFDJR").val()) && (item.type === 'sebagian'));

		findDropDataDropSemua.map((item) => {
			item.data.map((data) => {
				arrFixSKUKembali.push({
					noFDJR: fdjr,
					skuStockId: data.skuStockId,
					skuExpDate: data.skuExpDate,
					deliveryOrderId: item.deliveryOrderId,
					skuId: data.skuId,
					skuKode: data.skuKode,
					skuNamaProduk: data.skuNamaProduk,
					skuKemasan: data.skuKemasan,
					skuSatuan: data.skuSatuan,
					qty: data.qty,
					qtyBagus: 0,
					qtyRusak: 0,
					status: item.status,
					type: 'semua',
					isTypeBack: 'drop',
				});
			})
		})

		findDropDataDropSebagian.map((item) => {
			item.data.map((data) => {
				arrFixSKUKembali.push({
					noFDJR: fdjr,
					skuStockId: data.skuStockId,
					skuExpDate: data.skuExpDate,
					deliveryOrderId: item.deliveryOrderId,
					skuId: data.skuId,
					skuKode: data.skuKode,
					skuNamaProduk: data.skuNamaProduk,
					skuKemasan: data.skuKemasan,
					skuSatuan: data.skuSatuan,
					qty: data.qty,
					qtyBagus: 0,
					qtyRusak: 0,
					status: item.status,
					type: 'sebagian',
					isTypeBack: 'drop',
				});
			})
		})

		// console.log(arrFixSKUKembali);

		const filterDataRusak = arrTempProsesValidasi.filter((item) => item.type === 'sku rusak').map((data) => arrFixSKUKembali.push({
			...data,
			isTypeBack: 'rusak'
		}));

		const result = {}
		// const newData = arrFixSKUKembali.map((item) => {

		//   if (item.isTypeBack == 'drop') {
		//     return ({
		//       ...item
		//     })
		//   } else {
		//     return ({
		//       ...item
		//     })
		//   }
		// })

		const filter = arrFixSKUKembali.filter((item) => item.noFDJR === $("#noFDJR").val() && item.isTypeBack === 'drop');
		const filterNonDrop = arrFixSKUKembali.filter((item) => item.noFDJR === $("#noFDJR").val() && item.isTypeBack !== 'drop')

		if (filter.length > 0) {
			const sumDropQty = filter.reduce((acc, val) => {
				return acc + val.qty
			}, 0)

			filterNonDrop.map((data) => {
				if (data.qtyRusak > sumDropQty) {
					if (data.skuId in result) {
						result[data.skuId].qty += data.qty
					} else {
						result[data.skuId] = data
					}
				} else {
					filter.forEach(data => {
						if (data.skuId in result) {
							result[data.skuId].qty += data.qty
						} else {
							result[data.skuId] = data
						}
					})
				}
			})

		} else {
			filterNonDrop.forEach(data => {
				if (data.skuId in result) {
					result[data.skuId].qty += data.qty
				} else {
					result[data.skuId] = data
				}
			})
		}

		// const result = arrFixSKUKembali.reduce((unique, o) => {
		//   if (!unique.some(obj => obj.skuId === o.skuId && obj.isTypeBack === o.isTypeBack && obj.deliveryOrderId === o.deliveryOrderId)) {
		//     unique.push(o);
		//   }
		//   return unique;
		// }, []);


		// console.log(' ', result);

		return result;
	}

	const initDataSKUKembaliKeLokasi = () => {
		// $("#showDataSKUKembaliKeLokasi").show()
		const dataResult = dataSKUKembaliKeLokasi();

		if ($.fn.DataTable.isDataTable('#initDataSKUKembaliKeLokasi')) {
			$('#initDataSKUKembaliKeLokasi').DataTable().destroy();
		}

		$("#initDataSKUKembaliKeLokasi tbody").empty();
		if (Object.keys(dataResult).length > 0) {
			$.each(dataResult, function(i, v) {
				$("#initDataSKUKembaliKeLokasi tbody").append(`
            <tr class="text-center">
                <td>
                  <input type="hidden" class="skuStockIdDataKembali" value="${v.skuStockId}">
                  <input type="hidden" class="skuExpDateDataKembali" value="${v.skuExpDate}">
                  <input type="hidden" class="deliveryOrderIdDataKembali" value="${v.deliveryOrderId}">
                  <input type="hidden" class="skuIdDataKembali" value="${v.skuId}">
                  <input type="hidden" class="qtyDataKembali" value="${v.qty}">
                  <input type="hidden" class="typeDataKembali" value="${v.isTypeBack}">
                  <input type="hidden" class="typeDropDataKembali" value="${v.type}">
                  ${v.skuKode}
                </td>
                <td>${v.skuNamaProduk}</td>
                <td>${v.skuKemasan}</td>
                <td>${v.skuSatuan}</td>
                <td>${v.qty}</td>
                <td>${v.status == null ? '<span class="badge" style="background: red">Belum generate</span>' : `<span class="badge" style="background: green">${v.status}</span>`}</td>
            </tr>
        `)
			})
		}
		$("#initDataSKUKembaliKeLokasi").DataTable()

	}

	const showDataSKUYangKurang = () => {
		// $("#showDataSKUYangKurang").show()

		const filterData = arrTempProsesValidasi.filter((item) => item.type === 'sku kurang');

		if ($.fn.DataTable.isDataTable('#initDataSKUYangKurang')) {
			$('#initDataSKUYangKurang').DataTable().destroy();
		}

		$("#initDataSKUYangKurang tbody").empty();
		if (filterData.length > 0) {
			$.each(filterData, function(i, v) {
				$("#initDataSKUYangKurang tbody").append(`
            <tr class="text-center">
                <td>
                  <input type="hidden" class="skuStockIdDataKurang" value="${v.skuStockId}">
                  <input type="hidden" class="skuExpDateDataKurang" value="${v.skuExpDate}">
                  <input type="hidden" class="deliveryOrderIdDataKurang" value="${v.deliveryOrderId}">
                  <input type="hidden" class="skuIdDataKurang" value="${v.skuId}">
                  <input type="hidden" class="qtyDataKurang" value="${v.qty}">
                  ${v.skuKode}
                </td>
                <td>${v.skuNamaProduk}</td>
                <td>${v.skuKemasan}</td>
                <td>${v.skuSatuan}</td>
                <td>${v.qty}</td>
                <td>${v.status == null ? '<span class="badge" style="background: red">Belum generate</span>' : `<span class="badge" style="background: green">${v.status}</span>`}</td>
            </tr>
        `)
			})
		}
		$("#initDataSKUYangKurang").DataTable()

	}

	const showDataSKUSalahInput = () => {
		// $("#showDataSKUKembaliKeLokasi").show()

		const filterData = arrTempProsesValidasi.filter((item) => item.type === 'sku salah input');

		if ($.fn.DataTable.isDataTable('#initDataSKUSalahInput')) {
			$('#initDataSKUSalahInput').DataTable().destroy();
		}

		$("#initDataSKUSalahInput tbody").empty();
		if (filterData.length > 0) {
			$.each(filterData, function(i, v) {
				$("#initDataSKUSalahInput tbody").append(`
            <tr class="text-center">
                <td>
                  <input type="hidden" class="skuStockIdDataSalahInput" value="${v.skuStockId}">
                  <input type="hidden" class="skuExpDateDataSalahInput" value="${v.skuExpDate}">
                  <input type="hidden" class="deliveryOrderIdDataSalahInput" value="${v.deliveryOrderId}">
                  <input type="hidden" class="skuIdDataSalahInput" value="${v.skuId}">
                  <input type="hidden" class="qtyDataSalahInput" value="${v.qty}">
                  ${v.skuKode}
                </td>
                <td>${v.skuNamaProduk}</td>
                <td>${v.skuKemasan}</td>
                <td>${v.skuSatuan}</td>
                <td>${v.qty}</td>
                <td>${v.status == null ? '<span class="badge" style="background: red">Belum generate</span>' : `<span class="badge" style="background: green">${v.status}</span>`}</td>
            </tr>
        `)
			})
		}
		$("#initDataSKUSalahInput").DataTable()

	}

	const handlerSaveDataProsesValidasi = () => {

		const deliveryOrderBatchId = $('#noFDJR').val();
		const pickingOrderId = $('#pickingOrderId').val();
		const serahTerimaKirimId = $('#serahTerimaKirimId').val();
		const keterangan = $('#txtketerangan').val();
		const status = $('#txtstatus').val();
		const lastUpdated = $("#lastUpdated").val();

		if (deliveryOrderBatchId === "") {
			message('Error!', 'No. FDJR tidak boleh kosong', 'error')
			return false;
		}

		if (arrTempProsesData.length == 0) {
			message('Error!', 'Proses Validasi masih kosong, silahkan proses data terlebih dahulu', 'error')
			return false;
		}

		// if (arrDataDetailTemp.length !== arrTempProsesData.length) {
		//   message('Error!', 'Data detail masih ada yg belum di proses validasi', 'error')
		//   return false;
		// }

		const pickingValidationDetail = arrDataDetailTemp.map((item) => {

			const serah_terima = item.serah_terima == null ? 0 : item.serah_terima;
			const serah_terima_rusak = item.serah_terima_rusak == null ? 0 : item.serah_terima_rusak;

			const data = {
				noFDJR: $("#noFDJR").val(),
				pickingOrderPlanId: item.picking_order_plan_id,
				skuId: item.sku_id,
				qtyOrder: item.qty_order,
				qtyAmbil: item.qty_ambil,
				qtyTerimaBagus: serah_terima,
				qtyTerimaRusak: serah_terima_rusak,
				qtyValidasiBagus: 0,
				qtyValidasiRusak: 0
			}
			return data
		})

		arrTempProsesData.map((item) => {
			const findIndex = pickingValidationDetail.findIndex((data) => (data.noFDJR === $("#noFDJR").val()) && (data.pickingOrderPlanId === item.pickingOrderPlanId) && (data.skuId === item.skuIdDetailBarang));

			pickingValidationDetail[findIndex]['qtyValidasiBagus'] = item.qtyValidasiBagus;
			pickingValidationDetail[findIndex]['qtyValidasiRusak'] = item.qtyValidasiRusak;
		})


		let pickingValidationDetail3 = [];
		let pickingValidationDetail4 = [];
		let pickingValidationDetail5 = [];

		/** picking validation untuk sku kurang */

		const skuStockIdDataKurang = $(".skuStockIdDataKurang").map(function() {
			return this.value
		}).get();

		const skuExpDateDataKurang = $(".skuExpDateDataKurang").map(function() {
			return this.value
		}).get();

		const deliveryOrderIdDataKurang = $(".deliveryOrderIdDataKurang").map(function() {
			return this.value
		}).get();

		const skuIdDataKurang = $(".skuIdDataKurang").map(function() {
			return this.value
		}).get();

		const qtyDataKurang = $(".qtyDataKurang").map(function() {
			return this.value
		}).get();

		/** picking validation untuk sku kurang */

		/** picking validation untuk barang kembali ke lokasi */

		const skuStockIdDataKembali = $(".skuStockIdDataKembali").map(function() {
			return this.value
		}).get();

		const skuExpDateDataKembali = $(".skuExpDateDataKembali").map(function() {
			return this.value
		}).get();

		const deliveryOrderIdDataKembali = $(".deliveryOrderIdDataKembali").map(function() {
			return this.value
		}).get();

		const skuIdDataKembali = $(".skuIdDataKembali").map(function() {
			return this.value
		}).get();

		const qtyDataKembali = $(".qtyDataKembali").map(function() {
			return this.value
		}).get();

		const typeDataKembali = $(".typeDataKembali").map(function() {
			return this.value
		}).get();

		const typeDropDataKembali = $(".typeDropDataKembali").map(function() {
			return this.value
		}).get();

		/** picking validation untuk barang kembali ke lokasi */

		/** picking validation untuk sku salah input */

		const skuStockIdDataSalahInput = $(".skuStockIdDataSalahInput").map(function() {
			return this.value
		}).get();

		const skuExpDateDataSalahInput = $(".skuExpDateDataSalahInput").map(function() {
			return this.value
		}).get();

		const deliveryOrderIdDataSalahInput = $(".deliveryOrderIdDataSalahInput").map(function() {
			return this.value
		}).get();

		const skuIdDataSalahInput = $(".skuIdDataSalahInput").map(function() {
			return this.value
		}).get();

		const qtyDataSalahInput = $(".qtyDataSalahInput").map(function() {
			return this.value
		}).get();

		/** picking validation untuk sku salah input */


		if (skuIdDataKurang.length > 0) {
			for (let index = 0; index < skuIdDataKurang.length; index++) {
				pickingValidationDetail3.push({
					skuStockId: skuStockIdDataKurang[index],
					skuExpDate: skuExpDateDataKurang[index],
					deliveryOrderId: deliveryOrderIdDataKurang[index],
					skuId: skuIdDataKurang[index],
					qty: qtyDataKurang[index],
				})
			}
		}

		if (skuStockIdDataKembali.length > 0) {
			for (let index = 0; index < skuStockIdDataKembali.length; index++) {
				pickingValidationDetail4.push({
					skuStockId: skuStockIdDataKembali[index],
					skuExpDate: skuExpDateDataKembali[index],
					deliveryOrderId: deliveryOrderIdDataKembali[index],
					skuId: skuIdDataKembali[index],
					qty: qtyDataKembali[index],
					type: typeDataKembali[index],
					typeDrop: typeDropDataKembali[index],
				})
			}
		}

		if (skuIdDataSalahInput.length > 0) {
			for (let index = 0; index < skuIdDataSalahInput.length; index++) {
				pickingValidationDetail5.push({
					skuStockId: skuStockIdDataSalahInput[index],
					skuExpDate: skuExpDateDataSalahInput[index],
					deliveryOrderId: deliveryOrderIdDataSalahInput[index],
					skuId: skuIdDataSalahInput[index],
					qty: qtyDataSalahInput[index],
				})
			}
		}

		let pickingDetail3DetailforDO = []
		pickingDetail3DetailforDO = [];

		arrTempProsesValidasiForDO.map((item) => {
			const td = item.data.map((data) => {
				return pickingDetail3DetailforDO.push({
					doId: data.doId,
					skuId: item.skuId,
					prioritas: data.prioritas,
					akumulasi: data.akumulasi
				})
			})
		})

		const fixDetail3ForDO = pickingDetail3DetailforDO.filter((item) => typeof item.akumulasi !== 'undefined').reduce((unique, o) => {
			if (!unique.some(obj => obj.doId === o.doId && obj.skuId === o.skuId)) {
				unique.push(o);
			}
			return unique;
		}, []);

		messageBoxBeforeRequest('Untuk simpan data validasi!', 'Iya, Simpan', 'Tidak, Tutup').then((result) => {
			if (result.value == true) {
				postData("<?= base_url('WMS/Distribusi/ValidasiPengeluaranBarang/saveDataProsesValidasi') ?>", {
					pickingValidationId: typeof pickingValidationId !== 'undefined' ? pickingValidationId : "",
					deliveryOrderBatchId,
					pickingOrderId,
					serahTerimaKirimId,
					keterangan,
					status,
					pickingValidationDetail,
					pickingValidationDetail2: arrDataKoreksiDoTemp,
					pickingValidationDetail3,
					pickingValidationDetail3ForDO: fixDetail3ForDO,
					pickingValidationDetail4,
					pickingValidationDetail5,
					arrDropDOdiSKU,
					lastUpdated
				}, 'POST', function(response) {
					if (response.status === 200) {
						$("#lastUpdated").val(response.lastUpdatedNew);
						message_topright('success', response.message)
						if (typeof pickingValidationId === 'undefined') {
							path = window.location.pathname.split('/').filter((item) => item !== "");
							window.history.pushState(null, null, `/${path[0]}/${path[1]}/${path[2]}/${path[3]}/form` + `?id=${response.pickingValidationId}&fdjr=${deliveryOrderBatchId}&ppb=${pickingOrderId}`);
						}
						setTimeout(() => location.reload(), 500)
					}

					if (response.status === 401) return message_topright("error", response.message);
					if (response.status === 400) return messageNotSameLastUpdated()
				})
			}
		});
	}

	const handlerCetakDataProsesValidasi = () => {

		var arr_checkbox_do = [];

		const deliveryOrderBatchId = $('#noFDJR').val();
		const pickingOrderId = $('#pickingOrderId').val();

		if (deliveryOrderBatchId === "") {
			message('Error!', 'No. FDJR tidak boleh kosong', 'error')
			return false;
		}

		if (pickingOrderId === "") {
			message('Error!', 'No. Penerimaan Pengeluaran Barang', 'error')
			return false;
		}

		$("#initDataKoreksiDO > tbody tr").each(function(idx) {
			var checked = $('[id="check-do-' + idx + '"]:checked').length;
			var do_id = "'" + $("#check-do-" + idx).val() + "'";

			if (checked > 0) {
				arr_checkbox_do.push(do_id);
			}
		});

		if (arr_checkbox_do.length > 0) {
			setTimeout(() => {
				Swal.fire({
					title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
					showConfirmButton: false,
					timer: 1000
				});

				location.href = "<?= base_url() ?>WMS/Distribusi/ValidasiPengeluaranBarang/CetakValidasiPengeluaranBarang/?fdjr=" + deliveryOrderBatchId + "&ppb=" + pickingOrderId + "&do_id=" + arr_checkbox_do;
			}, 1000);
		} else {
			var alert_tes = GetLanguageByKode("CAPTION-ALERT-CHECKBOXDOHRSDIPILIH");
			message("Error", alert_tes, "error");
		}

	}

	const handlerKonfimasiDataProsesValidasi = () => {
		const lastUpdated = $("#lastUpdated").val();
		messageBoxBeforeRequest('Data yang akan dikonfirmasi tidak dapat diubah!', 'Iya, Konfirmasi', 'Tidak, Tutup').then((result) => {
			if (result.value == true) {
				postData("<?= base_url('WMS/Distribusi/ValidasiPengeluaranBarang/konfirmasiDataProsesValidasi') ?>", {
					pickingValidationId: typeof pickingValidationId !== 'undefined' ? pickingValidationId : "",
					lastUpdated
				}, 'POST', function(response) {
					if (response.status === 200) {
						$("#lastUpdated").val(response.lastUpdatedNew);
						message_topright('success', 'Konfirmasi data berhasil')
						setTimeout(() => location.reload(), 500)
					}

					if (response.status === 401) return message_topright("error", response.message);
					if (response.status === 400) return messageNotSameLastUpdated()
				})
			}
		});
	}

	const handlerCompletedDataProsesValidasi = () => {
		const lastUpdated = $("#lastUpdated").val();
		messageBoxBeforeRequest('Data yang akan dikonfirmasi tidak dapat diubah!', 'Iya, Konfirmasi', 'Tidak, Tutup').then((result) => {
			if (result.value == true) {
				postData("<?= base_url('WMS/Distribusi/ValidasiPengeluaranBarang/konfirmasiSelesaiDataProsesValidasi') ?>", {
					pickingValidationId: typeof pickingValidationId !== 'undefined' ? pickingValidationId : "",
					lastUpdated
				}, 'POST', function(response) {
					if (response.status === 200) {
						$("#lastUpdated").val(response.lastUpdatedNew);
						message_topright('success', response.message)
						setTimeout(() => {
							Swal.fire({
								title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
								showConfirmButton: false,
								timer: 1000
							});
							location.href = "<?= base_url('WMS/Distribusi/ValidasiPengeluaranBarang/ValidasiPengeluaranBarangMenu') ?>"
						}, 1000);
					}

					if (response.status === 401 || response.status === 402) return message_topright("error", response.message);
					if (response.status === 400) return messageNotSameLastUpdated()
				})
			}
		});
	}

	const handlerConfirmationFront = (pickingValidationId, deliveryOrderBatchId, pickingOrderId, serahTerimaKirimId, lastUpdated) => {
		messageBoxBeforeRequest('Data yang akan dikonfirmasi tidak dapat diubah!', 'Iya, Konfirmasi', 'Tidak, Tutup').then((result) => {
			if (result.value == true) {
				postData("<?= base_url('WMS/Distribusi/ValidasiPengeluaranBarang/konfirmasiSelesaiDepan') ?>", {
					pickingValidationId,
					deliveryOrderBatchId,
					pickingOrderId,
					serahTerimaKirimId,
					lastUpdated
				}, 'POST', function(response) {
					if (response.status === 200) {
						message_topright('success', response.message)
						setTimeout(() => {
							Swal.fire({
								title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
								showConfirmButton: false,
								timer: 1000
							});
							location.reload()
						}, 1000);
					}

					if (response.status === 401 || response.status === 402) return message_topright("error", response.message);
					if (response.status === 400) return messageNotSameLastUpdated()
				})
			}
		});
	}

	const handlergeneratePickingList = () => {
		const lastUpdated = $("#lastUpdated").val();
		messageBoxBeforeRequest('Data yang akan digenerate tidak dapat diubah!', 'Iya, Generate', 'Tidak, Tutup').then((result) => {
			if (result.value == true) {
				postData("<?= base_url('WMS/Distribusi/ValidasiPengeluaranBarang/generatePickingList') ?>", {
					pickingValidationId,
					lastUpdated
				}, 'POST', function(response) {
					if (response.status === 200) {
						$("#lastUpdated").val(response.lastUpdatedNew);
						message_topright('success', response.message)
						setTimeout(() => location.reload(), 500)
					}

					if (response.status === 401) return message_topright("error", response.message);
					if (response.status === 400) return messageNotSameLastUpdated()
				})
			}
		});
	}

	const handlergenerateKoreksiBKB = () => {
		const lastUpdated = $("#lastUpdated").val();
		messageBoxBeforeRequest('Data yang akan digenerate tidak dapat diubah!', 'Iya, Generate', 'Tidak, Tutup').then((result) => {
			if (result.value == true) {
				postData("<?= base_url('WMS/Distribusi/ValidasiPengeluaranBarang/generateKoreksiBKB') ?>", {
					pickingValidationId,
					lastUpdated
				}, 'POST', function(response) {
					if (response.status === 200) {
						$("#lastUpdated").val(response.lastUpdatedNew);
						message_topright('success', response.message)
						setTimeout(() => location.reload(), 500)
					}

					if (response.status === 401) return message_topright("error", response.message);
					if (response.status === 400) return messageNotSameLastUpdated()
				})
			}
		});
	}

	const handlerGenerateKembaliLokasi = () => {
		const lastUpdated = $("#lastUpdated").val();
		messageBoxBeforeRequest('Data yang akan digenerate tidak dapat diubah!', 'Iya, Generate', 'Tidak, Tutup').then((result) => {
			if (result.value == true) {
				postData("<?= base_url('WMS/Distribusi/ValidasiPengeluaranBarang/generateKembaliLokasi') ?>", {
					pickingValidationId,
					lastUpdated
				}, 'POST', function(response) {
					if (response.status === 200) {
						$("#lastUpdated").val(response.lastUpdatedNew);
						message_topright('success', response.message)
						setTimeout(() => location.reload(), 500)
					}

					if (response.status === 401) return message_topright("error", response.message);
					if (response.status === 400) return messageNotSameLastUpdated()
				})
			}
		});

	}

	const getDataPickingValidationEdit = () => {
		postDataRequest("<?= base_url('WMS/Distribusi/ValidasiPengeluaranBarang/getDataPickingValidationEdit') ?>", {
			pickingValidationId
		}, 'POST').then((response) => {

			/********* Hidden Button *******/
			if (response.header.picking_validation_status == "Draft") {
				$("#btnConfirmProsesValidasi").show();
				$("#btnSaveProsesValidasi").show();
				$("#btnCompletedProsesValidasi").hide();


				$("#txtketerangan").prop('disabled', false)
				$(".generateKembaliLokasi").hide();
				$(".btnGeneratePickingList").hide();
				$(".btnGenerateKoreksiBKB").hide();
			} else {
				$("#btnConfirmProsesValidasi").hide();
				$("#btnSaveProsesValidasi").hide();
				$("#txtketerangan").prop('disabled', true)

				if (typeof mode !== 'undefined') {
					$("#btnCompletedProsesValidasi").hide();
					$(".generateKembaliLokasi").hide();
					$(".btnGeneratePickingList").hide();
					$(".btnGenerateKoreksiBKB").hide();
				} else {
					$("#btnCompletedProsesValidasi").show();
					$(".generateKembaliLokasi").show();
					$(".btnGeneratePickingList").show();
					$(".btnGenerateKoreksiBKB").show();
				}


			}

			if (response.header.is_generate_back_location == 1) {
				$(".generateKembaliLokasi").prop('disabled', true);
			} else {
				$(".generateKembaliLokasi").prop('disabled', false);
			}

			if (response.header.is_generate_picking_list == 1) {
				$(".btnGeneratePickingList").prop('disabled', true);
			} else {
				$(".btnGeneratePickingList").prop('disabled', false);
			}

			if (response.header.is_generate_koreksi_bkb == 1) {
				$(".btnGenerateKoreksiBKB").prop('disabled', true);
			} else {
				$(".btnGenerateKoreksiBKB").prop('disabled', false);
			}
			/********* Hidden Button *******/


			/********* Header *******/
			$("#txtketerangan").val(response.header.picking_validation_keterangan)
			$("#txtstatus").val(response.header.picking_validation_status)
			$("#lastUpdated").val(response.header.picking_validation_tgl_update)
			// $("#noPPB").val(response.header.picking_order_id).trigger('change')
			/********* Header *******/

			/********* Detail *******/
			$.each(response.detail, function(i, v) {
				arrTempProsesData.push({
					noFDJR: v.delivery_order_batch_id,
					pickingOrderPlanId: v.picking_order_plan_id,
					skuIdDetailBarang: v.sku_id,
					qtyValidasiBagus: v.qty_validasi_bagus,
					qtyValidasiRusak: v.qty_validasi_rusak
				});
			})
			/********* Detail *******/

			/********* Detail 2 *******/
			$.each(response.detail2, function(i, v) {
				arrDataKoreksiDoTemp.push({
					noFDJR: v.delivery_order_batch_id,
					deliveryOrderId: v.delivery_order_id,
					prioritas: v.delivery_order_no_urut_rute,
				})
			})
			/********* Detail 2 *******/

			/********* Detail 3 *******/
			$.each(response.detail3, function(i, v) {
				arrTempProsesValidasi.push({
					noFDJR: v.delivery_order_batch_id,
					skuStockId: v.sku_stock_id,
					skuExpDate: v.sku_expdate,
					deliveryOrderId: null,
					// depoDetailNama: null,
					skuId: v.sku_id,
					skuKode: v.sku_kode,
					skuNamaProduk: v.sku_nama_produk,
					skuKemasan: v.sku_kemasan,
					skuSatuan: v.sku_satuan,
					qty: v.sku_qty_plan_ambil,
					status: v.status,
					type: 'sku kurang'
				});
			})

			$.each(response.detail3forDO, function(i, v) {
				arrTempProsesValidasiForDO.push({
					noFDJR: v.delivery_order_batch_id,
					skuStockId: v.sku_stock_id,
					skuExpDate: v.sku_expdate,
					skuId: v.sku_id,
					data: [{
						doId: v.delivery_order_id,
						prioritas: v.delivery_order_no_urut_rute,
						akumulasi: v.sku_qty_plan_ambil
					}]
				});
			})

			$('#showDataSKUYangKurang').fadeOut("slow", function() {
				$(this).hide();
			}).fadeIn("slow", function() {
				$(this).show();
				const filterData = arrTempProsesValidasi.filter((item) => item.type === 'sku kurang');
				if (filterData.length == 0) {
					$('#showDataSKUYangKurang').hide();
				} else {
					showDataSKUYangKurang()

				}
			});
			/********* Detail 3 *******/

			/********* Detail 4 *******/
			$.each(response.detail4, function(i, v) {
				if (v.is_type_picking_validation == "rusak") {
					arrTempProsesValidasi.push({
						noFDJR: v.delivery_order_batch_id,
						skuStockId: v.sku_stock_id,
						skuExpDate: v.sku_expdate,
						deliveryOrderId: null,
						// depoDetailNama: null,
						skuId: v.sku_id,
						skuKode: v.sku_kode,
						skuNamaProduk: v.sku_nama_produk,
						skuKemasan: v.sku_kemasan,
						skuSatuan: v.sku_satuan,
						qty: v.sku_qty_plan_ambil,
						status: v.status,
						type: 'sku rusak',
						isTypeBack: v.is_type_picking_validation,
					});
				}

				// if (v.is_type_picking_validation == "drop") {
				//   arrDropDOdiSKU.push({
				//     noFDJR: v.delivery_order_batch_id,
				//     deliveryOrderId: v.delivery_oder_id,
				//     skuId: v.sku_id,
				//     type: v.is_type_drop_picking_validation,
				//     status: v.status,
				//     data: [{
				//       skuStockId: v.sku_stock_id,
				//       skuExpDate: v.sku_expdate,
				//       skuId: v.sku_id,
				//       skuKode: v.sku_kode,
				//       skuNamaProduk: v.sku_nama_produk,
				//       skuKemasan: v.sku_kemasan,
				//       skuSatuan: v.sku_satuan,
				//       qty: parseInt(v.sku_qty_plan_ambil),
				//     }]
				//   })
				// }
			})



			$('#showDataSKUKembaliKeLokasi').fadeOut("slow", function() {
				$(this).hide();
			}).fadeIn("slow", function() {
				$(this).show();
				initDataSKUKembaliKeLokasi()

				const dataResult = dataSKUKembaliKeLokasi();
				if (Object.keys(dataResult).length == 0) {
					$('#showDataSKUKembaliKeLokasi').hide();
				}
			});
			/********* Detail 5 *******/

			$.each(response.detail5, function(i, v) {
				arrTempProsesValidasi.push({
					noFDJR: v.delivery_order_batch_id,
					skuStockId: v.sku_stock_id,
					skuExpDate: v.sku_expdate,
					deliveryOrderId: null,
					// depoDetailNama: null,
					skuId: v.sku_id,
					skuKode: v.sku_kode,
					skuNamaProduk: v.sku_nama_produk,
					skuKemasan: v.sku_kemasan,
					skuSatuan: v.sku_satuan,
					qty: v.sku_qty_plan_ambil,
					status: v.status,
					type: 'sku salah input'
				});
			})

			$('#showDataSKUSalahInput').fadeOut("slow", function() {
				$(this).hide();
			}).fadeIn("slow", function() {
				$(this).show();
				const filterData = arrTempProsesValidasi.filter((item) => item.type === 'sku salah input');
				if (filterData.length == 0) {
					$('#showDataSKUSalahInput').hide();
				} else {
					showDataSKUSalahInput()

				}
				// const filterData = arrTempProsesValidasi.filter((item) => item.type === 'sku salah input');
				// if (filterData.length == 0) {
				//   $('#showDataSKUSalahInput').hide();
				// }
			});
			/********* Detail 5 *******/

			/********* Detail 6 *******/

			//filter type by sebagian
			response.detail6.filter((item) => item.type == 'sebagian')
				.map((data, index) => {
					arrDropDOdiSKU.push({
						noFDJR: data.delivery_order_batch_id,
						deliveryOrderId: data.delivery_order_id,
						skuId: data.parent_sku_id_is_drop,
						type: data.type,
						status: null,
						data: [{
							skuStockId: data.sku_stock_id,
							skuExpDate: data.sku_expdate,
							skuId: data.sku_id,
							skuKode: data.sku_kode,
							skuNamaProduk: data.sku_nama_produk,
							skuKemasan: data.sku_kemasan,
							skuSatuan: data.sku_satuan,
							qty: parseInt(data.qty),
						}]
					})
				})

			//filter type by semua
			const filterBySemua = response.detail6.filter((item) => item.type == 'semua')
				.reduce((acc, value) => {
					acc[value.parent_sku_id_is_drop] = acc[value.parent_sku_id_is_drop] || [];
					acc[value.parent_sku_id_is_drop].push(value);
					return acc;
				}, Object.create(null));

			Object.keys(filterBySemua).forEach((value) => {

				let tempArray = [];

				filterBySemua[value].map((item) => {
					tempArray.push({
						skuStockId: item.sku_stock_id,
						skuExpDate: item.sku_expdate,
						skuId: item.sku_id,
						skuKode: item.sku_kode,
						skuNamaProduk: item.sku_nama_produk,
						skuKemasan: item.sku_kemasan,
						skuSatuan: item.sku_satuan,
						qty: parseInt(item.qty),
					});

				});

				arrDropDOdiSKU.push({
					noFDJR: filterBySemua[value][0].delivery_order_batch_id,
					deliveryOrderId: filterBySemua[value][0].delivery_order_id,
					skuId: filterBySemua[value][0].parent_sku_id_is_drop,
					type: filterBySemua[value][0].type,
					status: null,
					data: tempArray
				})

			});
			/********* Detail 6 *******/

		})
	}
</script>