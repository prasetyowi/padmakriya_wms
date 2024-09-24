<script>
	const urlSearchParams = new URLSearchParams(window.location.search);
	const trMutasiDepoId = Object.fromEntries(urlSearchParams.entries()).id;
	const mode = Object.fromEntries(urlSearchParams.entries()).mode;

	let mutasiAntarDepo = [];
	let mutasiAntarDepoDetail = [];
	let skuArrayOfChecked = [];

	// const html5QrCode = new Html5Qrcode("previewScan");

	loadingBeforeReadyPage();

	$(document).ready(function() {
		select2();
		if ($('#filterTanggalHomePage').length > 0) {
			$('#filterTanggalHomePage').daterangepicker({
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

		$(document).on("input", ".numeric", function(event) {
			this.value = this.value.replace(/[^\d.]+/g, '');
		});

		if (['edit', 'view'].includes(mode)) {
			return setTimeout(() => getDataMutasiDepo(), 1000)
		} else {
			// const counterData = $(".childMutasiAntarDepo").length + 1;

			mutasiAntarDepo.push({
				counter: 1,
				depoAsal: "<?= $this->session->userdata('depo_id') ?>",
				depoTujuan: null,
				gudangAsal: null,
				trMutasiDepoDetailId: null
			})
		}

		console.log(mutasiAntarDepo);

	})

	/** --------------------------------------- Untuk Global ------------------------------------------- */

	const select2 = () => {
		$(".select2").select2({
			width: "100%"
		});
	}

	const initDatatable = (table) => {
		$(table).DataTable();
	}

	const handlerBackToHome = () => {
		location.href = "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PersiapanMutasiAntarUnit/PersiapanMutasiAntarUnitMenu') ?>";
	}

	function getStringOfArrayString(data) {
		let string = "";

		data.forEach(datum => string += datum + ", ");

		return string.slice(0, -2);
	}

	/** Halaman Depan */

	const handlerFilterData = () => {
		$('#listDataFilter > tbody').empty();

		postData("<?= base_url('WMS/Transaksi/MutasiAntarUnit/PersiapanMutasiAntarUnit/getDataByFilter') ?>", {
			tanggal: $("#filterTanggalHomePage").val(),
			ekspedisi: $("#filterEkspedisiHomePage option:selected").val(),
			pengemudi: $("#filterPengemudiHomePage option:selected").val(),
			kendaraan: $("#filterKendaraanHomePage option:selected").val(),
			status: $("#filterStatusHomePage option:selected").val(),
		}, 'POST', function(response) {
			if (response) {
				response.map((value, index) => {
					let strAction = "";
					if (value.tr_mutasi_depo_status === 'Draft') {
						strAction += `<button class="btn btn-danger" onclick="handlerDeleteDataMutasiDepo('${value.tr_mutasi_depo_id}')"><i class="fas fa-trash"></i></button>`
						strAction += `<a href="<?= base_url('WMS/Transaksi/MutasiAntarUnit/PersiapanMutasiAntarUnit/form?id=') ?>${value.tr_mutasi_depo_id}&mode=edit" target="_BLANK" class="btn btn-warning"><i class="fas fa-pencil"></i></a>`
					} else {
						if (value.tr_mutasi_depo_status === 'Completed') strAction += `<a href="<?= base_url('WMS/Transaksi/MutasiAntarUnit/PersiapanMutasiAntarUnit/cetak/') ?>${value.tr_mutasi_depo_id}/multiple/null" target="_BLANK" class="btn btn-success"><i class="fas fa-print"></i></a>`
						strAction += `<a href="<?= base_url('WMS/Transaksi/MutasiAntarUnit/PersiapanMutasiAntarUnit/form?id=') ?>${value.tr_mutasi_depo_id}&mode=view" target="_BLANK" class="btn btn-primary"><i class="fas fa-eye"></i></a>`
					}

					$('#listDataFilter > tbody').append(`
							<tr class="text-center">
								<td>${index + 1}</td>
								<td>${value.tr_mutasi_depo_kode}</td>
								<td>${value.ekspedisi_nama}</td>
								<td>${value.karyawan_nama}</td>
								<td>${value.kendaraan_model} - ${value.kendaraan_nopol}</td>
								<td>${value.tr_mutasi_depo_status}</td>
								<td>${strAction}</td>
							</tr>
					`);
				})
			}
		}, '#btnsearchdata')

	}

	const handlerDeleteDataMutasiDepo = (trMutasiDepo) => {
		messageBoxBeforeRequest('Ingin hapus data?', 'Iya, Hapus', 'Tidak, Tutup').then((result) => {
			if (result.value == true) {
				postData("<?= base_url('WMS/Transaksi/MutasiAntarUnit/PersiapanMutasiAntarUnit/handlerDeleteDataMutasiDepo') ?>", {
					trMutasiDepo
				}, 'POST', function(response) {
					if (response.status === 200) {
						message_topright('success', response.message)
						setTimeout(() => handlerBackToHome(), 1000)
					}

					if (response.status === 401) return message_topright("error", response.message);
				})
			}
		});

	}

	/** End Halaman Depan */

	/******************************************************* Form *********************************************************/

	const handlerViewCahngeStatus = (event) => {
		event.currentTarget.checked ? $("#status").val('In Progress Approval') : $("#status").val('Draft')
	}

	const handlerAddMutasiAntarDepo = () => {
		const counterData = $(".childMutasiAntarDepo").length + 1;

		mutasiAntarDepo.push({
			counter: counterData,
			depoAsal: "<?= $this->session->userdata('depo_id') ?>",
			depoTujuan: null,
			gudangAsal: null,
			trMutasiDepoDetailId: null
		})

		appendMutasiDepo(counterData)

	}

	const appendMutasiDepo = (counterData) => {
		$("#initMutasiAntarDepo").append(`
				<div class="childMutasiAntarDepo" style="display: flex;align-items:center; padding: 7px; border:1px solid grey; border-radius: 5px; margin-bottom: 7px">
					<div style="width: 5%;">
						<h4 class="text-center numberChild">${counterData}</h4>
					</div>
					<div style="width: 85%;">
						<div class="row">
							<div class="col-xl-3 col-lg-3 col-md-3 col-xs-12">
								<div class="form-group">
									<label>Depo Asal</label>
									<select class="form-control select2" name="depoAsal" id="depoAsal${counterData}" required disabled>
										<option value="">--Pilih Depo Asal--</option>
										<?php if ($pages === 'form') { ?>
											<?php foreach ($depos['depoWithoutItSelf'] as $depo) : ?>
												<option value="<?= $depo->depo_id ?>" <?= $this->session->userdata('depo_id') === $depo->depo_id ? 'selected' : '' ?>><?= $depo->depo_nama ?></option>
											<?php endforeach; ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-xl-3 col-lg-3 col-md-3 col-xs-12">
								<div class="form-group">
									<label>Gudang Asal</label>
									<select class="form-control select2" name="gudangAsal" id="gudangAsal${counterData}" data-counter="${counterData}" onchange="handlerChangeDataGudangAsalMutasiAntarDepo(event)" required>
										<option value="">--Pilih Gudang Asal--</option>
										<?php if ($pages === 'form') { ?>
											<?php foreach ($warehouses as $warehouse) : ?>
												<option value="<?= $warehouse->depo_detail_id ?>"><?= $warehouse->depo_detail_nama ?></option>
											<?php endforeach; ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-xl-3 col-lg-3 col-md-3 col-xs-12">
								<div class="form-group">
									<label>Depo Tujuan</label>
									<select class="form-control select2" name="depoTujuan" id="depoTujuan${counterData}" data-counter="${counterData}" onchange="handlerChangeDataDepoTujuanMutasiAntarDepo(event)" required>
										<option value="">--Pilih Depo Tujuan--</option>
										<?php if ($pages === 'form') { ?>
											<?php foreach ($depos['depoWithItSelft'] as $depo) : ?>
												<option value="<?= $depo->depo_id ?>"><?= $depo->depo_nama ?></option>
											<?php endforeach; ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-xl-3 col-lg-3 col-md-3 col-xs-12">
								<?php if ($this->input->get('mode') !== 'view') { ?>
									<button class="btn btn-info btn-sm addSKUChild" data-counter="${counterData}" onclick="handlerAddDetailSKUMutasiAntarDepo(event, 'add')" style="margin-top: 23px;">Tambah SKU</button>
								<?php } else { ?>
									<button class="btn btn-success btn-sm cetakSKUChild" data-counter="${counterData}" onclick="handlerAddDetailSKUMutasiAntarDepo(event, 'cetak')" style="margin-top: 23px;">Cetak</button>

									<button class="btn btn-info btn-sm addSKUChild" data-counter="${counterData}" onclick="handlerAddDetailSKUMutasiAntarDepo(event, 'view')" style="margin-top: 23px;">View SKU</button>
								<?php } ?>
							</div>
							
						</div>
					</div>
				</div>
		`)

		select2();
	}

	const handlerChangeDataDepoTujuanMutasiAntarDepo = (event) => {
		const currentCounterRow = parseInt(event.currentTarget.getAttribute('data-counter'));
		const findIndexArray = mutasiAntarDepo.findIndex(value => value.counter === currentCounterRow);
		mutasiAntarDepo[findIndexArray]['depoTujuan'] = event.currentTarget.value
	}

	const handlerChangeDataGudangAsalMutasiAntarDepo = (event) => {
		const currentCounterRow = parseInt(event.currentTarget.getAttribute('data-counter'));
		const findIndexArray = mutasiAntarDepo.findIndex(value => value.counter === currentCounterRow);
		mutasiAntarDepo[findIndexArray]['gudangAsal'] = event.currentTarget.value
	}

	const handlerAddDetailSKUMutasiAntarDepo = (event, type) => {
		const currentCounterRow = parseInt(event.currentTarget.getAttribute('data-counter'));

		const findData = mutasiAntarDepo.find((item) => item.counter === currentCounterRow);

		if (!findData.gudangAsal) return message('Error!', 'Pilih gudang asal terlebih dahulu', 'error')

		if (type === 'add') {
			postData("<?= base_url('WMS/Transaksi/MutasiAntarUnit/PersiapanMutasiAntarUnit/getParamsForSkuData') ?>", findData, 'POST', function(response) {
				if (response) {
					$("#depoIdFilterSKU").val(response.depoId);
					$("#depoDetailIdFilterSKU").val(response.depoDetailId);

					$("#depoFilterSKU").val(response.depo);
					$("#depoDetailFilterSKU").val(response.depoDetail);

					$("#principleFilterSKU").empty();
					$("#principleBrandFilterSKU").empty();
					$("#skuIndukFilterSKU").empty();

					$("#principleFilterSKU").append(`<option value="all">--All--</option>`)
					if (response.principle) {
						response.principle.map((value) => {
							$("#principleFilterSKU").append(`<option value="${value.principle_id}">${value.principle_nama}</option>`)
						})
					}

					$("#principleBrandFilterSKU").append(`<option value="all">--All--</option>`)
					if (response.princpleBrand) {
						response.princpleBrand.map((value) => {
							$("#principleBrandFilterSKU").append(`<option value="${value.principle_brand_id}">${value.principle_brand_nama}</option>`)
						})
					}

					$("#skuIndukFilterSKU").append(`<option value="all">--All--</option>`)
					if (response.skuInduk) {
						response.skuInduk.map((value) => {
							$("#skuIndukFilterSKU").append(`<option value="${value.sku_induk_id}">${value.sku_induk_nama}</option>`)
						})
					}

				}
			})

			$("#modalAddSKU").modal('show')

			$(".handlerChoosenSKUInChecked").attr('data-counter', currentCounterRow)
			$(".handlerFilterSKUTambah").attr('data-counter', currentCounterRow)


			$("#tableListSKU tbody").empty();

			if ($.fn.DataTable.isDataTable('#tableListSKU')) {
				$('#tableListSKU').DataTable().clear();
				$('#tableListSKU').DataTable().destroy();
			}

			$('#tableListSKU').DataTable({
				"lengthMenu": [
					[-1],
					['All']
				]
			});
		}

		if (type === 'cetak') window.open(`<?= base_url('WMS/Transaksi/MutasiAntarUnit/PersiapanMutasiAntarUnit/cetak/') ?>${trMutasiDepoId}/single/${findData.trMutasiDepoDetailId}`, '_blank');

		$(".showDetailSKUMutasiAntarDepo").show('slow');
		$(".showDetailSKUMutasiAntarDepo .titleDetailMutasiAntarDepo").html(`SKU Detail No. ${currentCounterRow}`);

		initDataAppendDetailSKU(currentCounterRow);
	}

	const handlerFilterSKUTambah = (event) => {
		const currentCounterRow = parseInt(event.currentTarget.getAttribute('data-counter'));

		const depoId = $("#depoIdFilterSKU").val()
		const depoDetailId = $("#depoDetailIdFilterSKU").val()
		const principleId = $("#principleFilterSKU option:selected").val()
		const principleBrandId = $("#principleBrandFilterSKU option:selected").val()
		const skuIndukId = $("#skuIndukFilterSKU option:selected").val()
		const namaSku = $("#namaSkuFilterSKU").val()
		const kodeSkuWMS = $("#kodeSKUWMSFilterSKU").val()
		const kodeSkuPabrik = $("#kodeSKUPabrikFilterSKU").val()

		if ($.fn.DataTable.isDataTable('#tableListSKU')) {
			$('#tableListSKU').DataTable().clear();
			$('#tableListSKU').DataTable().destroy();
		}

		let table = $('#tableListSKU').DataTable({
			'bInfo': true,
			'pageLength': 10,
			'serverSide': true,
			'serverMethod': 'post',
			'ajax': {
				url: "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PersiapanMutasiAntarUnit/getDataSKUByParams') ?>",
				data: {
					depoId,
					depoDetailId,
					principleId,
					principleBrandId,
					skuIndukId,
					namaSku,
					kodeSkuWMS,
					kodeSkuPabrik
				}
			},
			'order': [
				[1, 'asc']
			],
			// 'fixedHeader': false,
			// 'scrollY': 500,
			// 'scrollX': true,
			// "pagingType": "simple_numbers",
			"lengthMenu": [
				[5, 10, 25, 50, 100, 500, 1000],
				[5, 10, 25, 50, 100, 500, 1000]
			],
			"pagingType": "simple",
			"language": {
				paginate: {
					previous: '<i class="fas fa-arrow-left"></i>',
					next: '<i class="fas fa-arrow-right"></i>'
				}
			},
			'columns': [{
					data: function(row, type, val, meta) {
						const findData = mutasiAntarDepoDetail.find((item) => item.counter === currentCounterRow && item.sku_id === row.sku_id && item.ed === row.ed);

						return `<input type="checkbox" name="CheckboxSKU" onchange="handlerChangeCheckedBySku(event, '${row.sku_id}', '${row.ed}')" class="check-item" style="transform: scale(1.5)" data-sku='${JSON.stringify({...row})}' ${findData ? 'checked' : ''} id="check-sku-${row.nomor}">`
					},
					orderable: false,
					className: 'text-center'
				},
				{
					data: 'sku_induk_nama'
				},
				{
					data: 'sku_kode'
				},
				{
					data: 'sku_nama_produk'
				},
				{
					data: 'sku_satuan'
				},
				{
					data: 'qtySistem',
					orderable: false,
				},
				{
					data: 'ed',
					orderable: false,
				},
				{
					data: function(row, type, val, meta) {
						const findData = mutasiAntarDepoDetail.find((item) => item.counter === currentCounterRow && item.sku_id === row.sku_id && item.ed === row.ed);

						return `<input type="text" class="form-control numeric qtyAmbilSKU" onchange="handlerChangeQtyAmbilBySku(event, '${row.sku_id}', '${row.ed}')" name="qtyAmbilSKU" id="qtyAmbilSKU${row.sku_id}${row.ed}" value="${findData ? (findData.qtyAmbil === null ? '' : findData.qtyAmbil) : ''}" ${findData ? '' : 'disabled'}/>`
					},
					orderable: false,
				},
			],
			drawCallback: function() {

				$('.paginate_button.next', this.api().table().container())
					.on('click', function() {
						const info = table.page.info();
						let totalFindChecked = 0;
						for (let index = (info.start + 1); index <= info.end; index++) {
							setTimeout(() => {
								const dataSKU = JSON.parse($(`#check-sku-${index}`).attr('data-sku'));


								const findData = mutasiAntarDepoDetail.find((item) => item.counter === currentCounterRow && item.sku_id === dataSKU.sku_id && item.ed === dataSKU.ed);

								if (findData) {
									totalFindChecked++
									$(`#check-sku-${index}`).prop('checked', true)
								}
							}, 500)

						}

						$(".paginate_button.next").prop('disabled', true);
						setTimeout(() => {
							totalFindChecked === info.length ? $('#select-sku').prop('checked', true) : $('#select-sku').prop('checked', false)
							$(".paginate_button.next").prop('disabled', false);
						}, 500)

					});
				$('.paginate_button.previous', this.api().table().container())
					.on('click', function() {
						const info = table.page.info();
						let totalFindChecked = 0;
						for (let index = (info.start + 1); index <= info.end; index++) {
							setTimeout(() => {
								const dataSKU = JSON.parse($(`#check-sku-${index}`).attr('data-sku'));

								const findData = mutasiAntarDepoDetail.find((item) => item.counter === currentCounterRow && item.sku_id === dataSKU.sku_id && item.ed === dataSKU.ed);

								if (findData) {
									totalFindChecked++
									$(`#check-sku-${index}`).prop('checked', true)
								}
							}, 500)
						}

						$(".paginate_button.next").prop('disabled', true);
						setTimeout(() => {
							totalFindChecked === info.length ? $('#select-sku').prop('checked', true) : $('#select-sku').prop('checked', false)
							$(".paginate_button.next").prop('disabled', false);
						}, 500)
					});
			}
		});



		// postData("<?= base_url('WMS/Transaksi/MutasiAntarUnit/PersiapanMutasiAntarUnit/getDataSKUByParams') ?>", {
		// 	depoId,
		// 	depoDetailId,
		// 	principleId,
		// 	principleBrandId,
		// 	skuIndukId,
		// 	namaSku,
		// 	kodeSkuWMS,
		// 	kodeSkuPabrik
		// }, 'POST', function(response) {
		// 	$("#tableListSKU tbody").empty();

		// 	if ($.fn.DataTable.isDataTable('#tableListSKU')) {
		// 		$('#tableListSKU').DataTable().clear();
		// 		$('#tableListSKU').DataTable().destroy();
		// 	}

		// 	if (response) {
		// 		response.map((value, index) => {

		// 			if (value.qtySistem > 0) {
		// 				const findData = mutasiAntarDepoDetail.find((item) => item.counter === currentCounterRow && item.sku_id === value.sku_id && item.ed === value.ed)

		// 				$("#tableListSKU tbody").append(`
		// 						<tr>
		// 								<td width="5%" class="text-center">
		// 									<input type="checkbox" name="CheckboxSKU" onchange="handlerChangeCheckedBySku(event, '${value.sku_id}', '${value.ed}')" class="check-item" data-sku='${JSON.stringify({...value})}' ${findData ? 'checked' : ''} id="check-sku-${index}">
		// 								</td>
		// 								<td width="10%" class="text-center">${value.sku_induk_nama}</td>
		// 								<td width="10%" class="text-center">${value.sku_kode}</td>
		// 								<td width="15%" class="text-center">${value.sku_nama_produk}</td>
		// 								<td width="10%" class="text-center">${value.sku_satuan}</td>
		// 								<td width="10%" class="text-center">${value.qtySistem}</td>
		// 								<td width="10%" class="text-center">${value.ed}</td>
		// 								<td width="15%" class="text-center">
		// 										<input type="text" class="form-control numeric qtyAmbilSKU" name="qtyAmbilSKU" id="qtyAmbilSKU${value.sku_id}${value.ed}" value="${findData ? findData.qtyAmbil : ''}" ${findData ? '' : 'disabled'}/>
		// 								</td>
		// 						</tr>
		// 				`)
		// 			}
		// 		})
		// 	}

		// 	$('#tableListSKU').DataTable({
		// 		"lengthMenu": [
		// 			[-1],
		// 			['All']
		// 		],
		// 		"ordering": false
		// 	});
		// })
	}

	const requestCheckScanKode = (kode, currentCounterRow, typeScan) => {
		postData("<?= base_url('WMS/Transaksi/MutasiAntarUnit/PersiapanMutasiAntarUnit/checkScan') ?>", {
			kode
		}, 'POST', function(response) {
			message(response.title, response.message, response.icon);

			if (response.status === 200) {

				typeScan === 'scan' ? $("#modalScan").modal('hide') : $("#modalInputManual").modal('hide');

				$("#tableListSKU tbody").empty();

				if ($.fn.DataTable.isDataTable('#tableListSKU')) {
					$('#tableListSKU').DataTable().clear();
					$('#tableListSKU').DataTable().destroy();
				}

				if (response.data) {
					response.data.map((value, index) => {

						const findData = mutasiAntarDepoDetail.find((item) => item.counter === parseInt(currentCounterRow) && item.sku_id === value.sku_id && item.ed === value.ed)


						$("#tableListSKU tbody").append(`
							<tr>
									<td width="5%" class="text-center">
										<input type="checkbox" name="CheckboxSKU" class="check-item" data-sku='${JSON.stringify({...value})}' ${findData ? 'checked' : ''} id="check-sku-${index}">
									</td>
									<td width="10%" class="text-center">${value.pallet_kode}</td>
									<td width="10%" class="text-center">${value.sku_kode}</td>
									<td width="15%" class="text-center">${value.sku_nama_produk}</td>
									<td width="10%" class="text-center">${value.sku_satuan}</td>
									<td width="10%" class="text-center">${value.qtySistem}</td>
									<td width="10%" class="text-center">${value.ed}</td>
									<td width="10%" class="text-center">${value.gudang}</td>
									<td width="15%" class="text-center">
											<input type="text" class="form-control numeric" name="qtyAmbilSKU" id="qtyAmbilSKU${value.sku_id}" value="${findData ? findData.qtyAmbil : ''}"/>
									</td>
							</tr>
					`)
					})
				}

				$('#tableListSKU').DataTable({
					"lengthMenu": [
						[-1],
						['All']
					],
					"ordering": false
				});
			}
		})
	}

	const handlerChangeViewScan = (event) => {
		if (event.target.checked) {
			$(`#inputScan`).show();
			$(`#startScan`).hide();
		} else {
			$(`#inputScan`).hide();
			$(`#startScan`).show();
		}
	}

	const handlerStartScan = (event) => {
		const currentCounterRow = parseInt(event.currentTarget.getAttribute('data-counter'));

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
					requestCheckScanKode(decodedText, currentCounterRow, 'scan')
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
					$("#modalScan").modal('show')

					if (devices && devices.length) {
						$("#selectCamera").empty();
						$.each(devices, function(i, v) {
							$("#selectCamera").append(`
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
					$("#modalScan").modal('hide')
				});
			}
		});
	}

	const closeScanPalletHandler = () => {
		html5QrCode.stop();
		$("#modalScan").modal('hide')
	}

	const handlerStartInput = (event) => {
		const currentCounterRow = parseInt(event.currentTarget.getAttribute('data-counter'));

		$("#modalInputManual").modal('show');
		$("#kodePallet").attr('data-counter', currentCounterRow);

	}

	const closeInputPalletHandler = () => {
		$("#modalInputManual").modal('hide');
		$("#kodePallet").val("");
	}

	const handlerGetKodePallet = (event, value) => {
		const currentCounterRow = parseInt(event.currentTarget.getAttribute('data-counter'));
		if (value != "") {
			fetch('<?= base_url('WMS/Transaksi/MutasiAntarUnit/PersiapanMutasiAntarUnit/getKodeAutoComplete?params='); ?>' + value)
				.then(response => response.json())
				.then((results) => {
					if (results) {
						let data = "";
						results.forEach(function(e, i) {
							data += `<tr style="cursor:pointer" onclick="handlerGetDataPalletByKode('${e.kode}', '${currentCounterRow}')">
													<td width="10%">${i + 1}.</td>
													<td width="90%">${e.kode}</td>
											</tr>`;
						})

						$('#konten-table').html(data);
						$('#table-fixed').css('display', 'block');
					} else {
						$('#table-fixed').css('display', 'none');
					}
				});
		} else {
			$('#table-fixed').css('display', 'none');
		}
	}

	const handlerGetDataPalletByKode = (kode, currentCounterRow) => {

		$("#kodePallet").val(kode);
		$('#table-fixed').css('display', 'none');

		requestCheckScanKode(kode, currentCounterRow, 'input')
	}

	const handlerSelectAllSKU = (event) => {
		const currentCounterRow = parseInt($(".handlerChoosenSKUInChecked").attr('data-counter'));

		if (event.currentTarget.checked) {
			$('[name="CheckboxSKU"]:checkbox').map(function() {
				if (!this.disabled) {
					const dataOfChecked = JSON.parse(this.getAttribute('data-sku'));
					const findData = mutasiAntarDepoDetail.find((item) => item.counter === parseInt(currentCounterRow) && item.sku_id === dataOfChecked.sku_id && item.ed === dataOfChecked.ed);
					if (!findData) mutasiAntarDepoDetail.push(({
						...dataOfChecked,
						counter: currentCounterRow,
						qtyAmbil: null,
						type: 'add'
					}))

					this.checked = true
					$("input[name*='qtyAmbilSKU']").prop('disabled', false)
				};
			});
		} else {
			$('[name="CheckboxSKU"]:checkbox').map(function() {
				if (!this.disabled) {

					const dataOfChecked = JSON.parse(this.getAttribute('data-sku'));
					const findDataIndex = mutasiAntarDepoDetail.findIndex((item) => item.counter === parseInt(currentCounterRow) && item.sku_id === dataOfChecked.sku_id && item.ed === dataOfChecked.ed);
					if (findDataIndex > -1) mutasiAntarDepoDetail.splice(findDataIndex, 1)

					this.checked = false
					$("input[name*='qtyAmbilSKU']").prop('disabled', true)
				};
			});
		}
	}

	const handlerChangeCheckedBySku = (event, skuId, ed) => {
		const currentCounterRow = parseInt($(".handlerChoosenSKUInChecked").attr('data-counter'));
		const dataSKU = JSON.parse(event.currentTarget.getAttribute('data-sku'));

		if (event.currentTarget.checked) {
			const findData = mutasiAntarDepoDetail.find((item) => item.counter === currentCounterRow && item.sku_id === dataSKU.sku_id && item.ed === dataSKU.ed);
			if (!findData) mutasiAntarDepoDetail.push(({
				...dataSKU,
				counter: currentCounterRow,
				qtyAmbil: null,
				type: 'add'
			}))
			$(`#qtyAmbilSKU${skuId}${ed}`).prop('disabled', false)
		} else {
			const findDataIndex = mutasiAntarDepoDetail.findIndex((item) => item.counter === currentCounterRow && item.sku_id === dataSKU.sku_id && item.ed === dataSKU.ed);
			if (findDataIndex > -1) mutasiAntarDepoDetail.splice(findDataIndex, 1)
			$(`#qtyAmbilSKU${skuId}${ed}`).prop('disabled', true)
		}
	}

	const _objectWithoutProperties = (obj, keys) => {
		let target = {};
		for (let i in obj) {
			if (keys.indexOf(i) >= 0) continue;
			if (!Object.prototype.hasOwnProperty.call(obj, i)) continue;
			target[i] = obj[i];
		}
		return target;
	}

	const handlerChangeQtyAmbilBySku = (event, skuId, ed) => {
		const currentCounterRow = parseInt($(".handlerChoosenSKUInChecked").attr('data-counter'));
		const qtyAmbil = event.currentTarget.value === '' || event.currentTarget.value === 0 ? null : parseInt(event.currentTarget.value);
		const findDataIndex = mutasiAntarDepoDetail.findIndex((item) => item.counter === currentCounterRow && item.sku_id === skuId && item.ed === ed);
		mutasiAntarDepoDetail[findDataIndex]['qtyAmbil'] = qtyAmbil;
	}

	const handlerChoosenSKUInChecked = (event) => {
		const currentCounterRow = parseInt(event.currentTarget.getAttribute('data-counter'));

		let error = 0;

		if (mutasiAntarDepoDetail.length === 0) return message('Error', 'Pilih minimal 1 data SKU', 'error');

		const datasCheckedNew = mutasiAntarDepoDetail.map((value) => {
			// const qtyAmbil = $(`#qtyAmbilSKU${value.sku_id}${value.ed}`).val() == '' ? 0 : parseInt($(`#qtyAmbilSKU${value.sku_id}${value.ed}`).val());
			const qtyAmbil = value.qtyAmbil === null ? 0 : value.qtyAmbil;
			if (qtyAmbil === 0) {
				message('Error', `SKU <strong>${value.sku_kode} - ${value.sku_nama_produk}</strong> qty ambil masih kosong`, 'error')
				error++
				return false;
			}

			if (qtyAmbil > value.qtySistem) {
				message('Error', `SKU <strong>${value.sku_kode} - ${value.sku_nama_produk}</strong> qty ambil melebihi qty yang ada`, 'error')
				error++
				return false;
			}

			const exceptTypeInObject = _objectWithoutProperties(value, ["type"]);

			return {
				...exceptTypeInObject,
				type: 'fix'
			};
		})

		// const dataSKUChecked = $('[name="CheckboxSKU"]:checkbox').map(function() {
		// 	if (this.checked) {
		// 		const datas = JSON.parse(this.getAttribute('data-sku'))
		// 		const qtyAmbil = $(`#qtyAmbilSKU${datas.sku_id}${datas.ed}`).val() == '' ? 0 : parseInt($(`#qtyAmbilSKU${datas.sku_id}${datas.ed}`).val());
		// 		if (qtyAmbil === 0) {
		// 			message('Error', `SKU <strong>${datas.sku_kode} - ${datas.sku_nama_produk}</strong> qty ambil masih kosong`, 'error')
		// 			error++
		// 			return false;
		// 		}

		// 		if (qtyAmbil > datas.qtySistem) {
		// 			message('Error', `SKU <strong>${datas.sku_kode} - ${datas.sku_nama_produk}</strong> qty ambil melebihi qty yang ada`, 'error')
		// 			error++
		// 			return false;
		// 		}

		// 		return {
		// 			...datas,
		// 			qtyAmbil
		// 		};
		// 	}
		// }).get();

		if (error > 0) return false;


		const filteredDataMutasiAntarDepoDetail = mutasiAntarDepoDetail.filter((item) => item.counter !== currentCounterRow);
		mutasiAntarDepoDetail = [];
		filteredDataMutasiAntarDepoDetail.map((value) => mutasiAntarDepoDetail.push({
			...value
		}))

		const dataSKU = datasCheckedNew.map((itemChecked) => {

			const pushToArray = {
				...itemChecked
			}

			if (mutasiAntarDepoDetail.length === 0) {
				mutasiAntarDepoDetail.push(pushToArray)
			} else {
				const findDataSKU = mutasiAntarDepoDetail.find((item) => item.counter === parseInt(currentCounterRow) && item.sku_id === itemChecked.sku_id && item.ed === itemChecked.ed);
				if (!findDataSKU) {
					mutasiAntarDepoDetail.push(pushToArray)
				} else {
					const findIndexDataSKU = mutasiAntarDepoDetail.findIndex((item) => item.counter === parseInt(currentCounterRow) && item.sku_id === itemChecked.sku_id && item.ed === itemChecked.ed);
					mutasiAntarDepoDetail[findIndexDataSKU]['qtyAmbil'] = itemChecked.qtyAmbil
				}
			}
		})

		$('#modalAddSKU').modal('hide');
		$('#tableListSKU > tbody').empty();

		initDataAppendDetailSKU(currentCounterRow);

	}

	const handlerCloseSKUInChecked = (event) => {
		const currentCounterRow = parseInt($(".handlerChoosenSKUInChecked").attr('data-counter'));
		console.log('sebelum', mutasiAntarDepoDetail);

		const newData = mutasiAntarDepoDetail.filter((item) => item.type === 'fix')
		mutasiAntarDepoDetail = [];
		newData.map((value) => mutasiAntarDepoDetail.push({
			...value
		}))

		console.log('sesudah', mutasiAntarDepoDetail);

		initDataAppendDetailSKU(currentCounterRow);
	}

	const initDataAppendDetailSKU = (currentCounterRow) => {
		const filteredDataMutasiAntarDepoDetail = mutasiAntarDepoDetail.filter((item) => item.counter === parseInt(currentCounterRow));

		$("#initDetailSKUMutasiAntarDepo tbody").empty();

		if (filteredDataMutasiAntarDepoDetail) {
			filteredDataMutasiAntarDepoDetail.map((value, index) => {
				$("#initDetailSKUMutasiAntarDepo tbody").append(`
							<tr>
									<td width="5%" class="text-center">${index + 1}</td>
									<td width="10%" class="text-center">${value.sku_kode}</td>
									<td width="15%" class="text-center">${value.sku_nama_produk}</td>
									<td width="10%" class="text-center">${value.sku_satuan}</td>
									<td width="10%" class="text-center">
										<input type="text" class="custom-form-control numeric bg-transparent border-0" id="qtyAmbilDetail${currentCounterRow}${value.sku_id}${value.ed}" disabled value="${value.qtyAmbil}"/>
									</td>
									<td width="10%" class="text-center">${value.ed}</td>
									<?php if ($this->input->get('mode') !== 'view') { ?>
										<td width="10%" class="text-center">
												<i class="fas fa-times text-danger btnCancelDetailSKUMutasiDepo${currentCounterRow}${value.sku_id}${value.ed}" onclick="handlerCancelDetailSKUMutasiDepo('${currentCounterRow}', '${value.sku_id}', '${value.ed}')" style="cursor: pointer;font-size: 1.2em;margin-right:5px;display:none"></i>
												<i class="fas fa-save text-success btnSaveDetailSKUMutasiDepo${currentCounterRow}${value.sku_id}${value.ed}" onclick="handlerSimpanDetailSKUMutasiDepo('${currentCounterRow}', '${value.sku_id}', '${value.ed}')" style="cursor: pointer;font-size: 1.2em;margin-right:5px;display:none"></i>
												<i class="fas fa-pencil text-warning btnEditDetailSKUMutasiDepo${currentCounterRow}${value.sku_id}${value.ed}" onclick="handlerEditDetailSKUMutasiDepo('${currentCounterRow}', '${value.sku_id}', '${value.ed}')" style="cursor: pointer;font-size: 1.2em;margin-right:5px"></i>
												<i class="fas fa-trash text-danger" onclick="handlerDeleteDetailSKUMutasiDepo('${currentCounterRow}', '${value.sku_id}', '${value.ed}')" style="cursor: pointer;font-size: 1.2em"></i>
										</td>
									<?php } ?>
							</tr>
					`)
			})
		}
	}

	const handlerSimpanDetailSKUMutasiDepo = (currentCounterRow, sku_id, ed) => {
		const newWtyAmbil = $(`#qtyAmbilDetail${currentCounterRow}${sku_id}${ed}`).val() === '' ? 0 : parseInt($(`#qtyAmbilDetail${currentCounterRow}${sku_id}${ed}`).val());

		if (newWtyAmbil === 0) return message('Error!', 'Qty ambil tidak boleh kosong atau 0', 'error');

		const findDataSKU = mutasiAntarDepoDetail.find((item) => item.counter === parseInt(currentCounterRow) && item.sku_id === sku_id && item.ed === ed);

		const findIndexDataSKU = mutasiAntarDepoDetail.findIndex((item) => item.counter === parseInt(currentCounterRow) && item.sku_id === sku_id && item.ed === ed);

		if (newWtyAmbil > findDataSKU.qtySistem) return message('Error!', `Qty Ambil melebih Qty Sistem, Qty Sistem ${findDataSKU.qtySistem}`, 'error');

		mutasiAntarDepoDetail[findIndexDataSKU]['qtyAmbil'] = newWtyAmbil

		message_topright("success", 'Qty berhasil diperbarui')
		initDataAppendDetailSKU(currentCounterRow)

	}

	const handlerCancelDetailSKUMutasiDepo = (currentCounterRow, sku_id, ed) => {

		const findDataSKU = mutasiAntarDepoDetail.find((item) => item.counter === parseInt(currentCounterRow) && item.sku_id === sku_id && item.ed === ed);

		$(`#qtyAmbilDetail${currentCounterRow}${sku_id}${ed}`).addClass("border-0");
		$(`#qtyAmbilDetail${currentCounterRow}${sku_id}${ed}`).attr("disabled");

		$(`#qtyAmbilDetail${currentCounterRow}${sku_id}${ed}`).val(findDataSKU.qtyAmbil);


		$(`.btnCancelDetailSKUMutasiDepo${currentCounterRow}${sku_id}${ed}`).hide();
		$(`.btnSaveDetailSKUMutasiDepo${currentCounterRow}${sku_id}${ed}`).hide();
		$(`.btnEditDetailSKUMutasiDepo${currentCounterRow}${sku_id}${ed}`).show();

	}

	const handlerEditDetailSKUMutasiDepo = (currentCounterRow, sku_id, ed) => {
		$(`#qtyAmbilDetail${currentCounterRow}${sku_id}${ed}`).removeClass("border-0");
		$(`#qtyAmbilDetail${currentCounterRow}${sku_id}${ed}`).removeAttr("disabled");


		$(`.btnCancelDetailSKUMutasiDepo${currentCounterRow}${sku_id}${ed}`).show();
		$(`.btnSaveDetailSKUMutasiDepo${currentCounterRow}${sku_id}${ed}`).show();
		$(`.btnEditDetailSKUMutasiDepo${currentCounterRow}${sku_id}${ed}`).hide();

	}

	const handlerDeleteDetailSKUMutasiDepo = (currentCounterRow, sku_id, ed) => {
		const findIndexDataSKU = mutasiAntarDepoDetail.findIndex((item) => item.counter === parseInt(currentCounterRow) && item.sku_id === sku_id && item.ed === ed);
		if (findIndexDataSKU > -1) mutasiAntarDepoDetail.splice(findIndexDataSKU, 1);

		initDataAppendDetailSKU(currentCounterRow)
	}


	$(document).on('click', '.deleteRowChildMutasiAntarDepo', function() {
		const currentCounterRow = parseInt($(this).attr('data-counter'));

		const filteredDataMutasiAntarDepo = mutasiAntarDepo.filter((item) => item.counter !== currentCounterRow);
		mutasiAntarDepo = [];
		filteredDataMutasiAntarDepo.map((value) => mutasiAntarDepo.push({
			...value
		}))

		const filteredDataMutasiAntarDepoDetail = mutasiAntarDepoDetail.filter((item) => item.counter !== currentCounterRow);
		mutasiAntarDepoDetail = [];
		filteredDataMutasiAntarDepoDetail.map((value) => mutasiAntarDepoDetail.push({
			...value
		}))

		$(this).parent().parent().remove();

		$(".showDetailSKUMutasiAntarDepo").hide('slow');

		$(".childMutasiAntarDepo").each(function(index, value) {
			$(this).find('.numberChild').html(index + 1)
			$(this).find('select[name*="depoTujuan"]').attr('data-counter', index + 1)
			$(this).find('select[name*="gudangAsal"]').attr('data-counter', index + 1)
			$(this).find('.addSKUChild').attr('data-counter', index + 1)
			$(this).find('.deleteRowChildMutasiAntarDepo').attr('data-counter', index + 1)
		})
	})

	const handlerSave = (event) => {
		const kodeDokumen = $("#kodeDokumen").val();
		const ekspedisi = $("#ekspedisi option:selected").val();
		const pengemudi = $("#pengemudi option:selected").val();
		const kendaraan = $("#kendaraan option:selected").val();
		const status = $("#status").val();
		const keterangan = $("#keterangan").val();
		const lastUpdated = $("#lastUpdated").val();

		if (ekspedisi === '') return message('Error!', 'Ekspedisi tidak boleh kosong', 'error');
		if (pengemudi === '') return message('Error!', 'Pengemudi tidak boleh kosong', 'error');
		if (kendaraan === '') return message('Error!', 'Kendaraan tidak boleh kosong', 'error');
		if (mutasiAntarDepo.length === 0) return message('Error!', 'Data Mutasi Antar Depo masih kosong', 'error');

		let mutationsThatHaveNotAddedSku = []

		mutationsThatHaveNotAddedSku = [];

		const getCounterMutasiDepo = mutasiAntarDepo.map((value, index) => value.counter);

		const getCounterMutasiDepoDetail = mutasiAntarDepoDetail.map((value, index) => value.counter);
		const newCounterMutasiDepoDetail = [...new Set(getCounterMutasiDepoDetail)];

		getCounterMutasiDepo.map((parent) => {
			const findNotItMutasi = newCounterMutasiDepoDetail.find((child) => parent === child)
			if (!findNotItMutasi) mutationsThatHaveNotAddedSku.push(parent)
		})

		if (mutationsThatHaveNotAddedSku.length !== 0) return message('Error!', `Mutasi No. <strong>${mutationsThatHaveNotAddedSku.toString()}</strong> masih belum ditambahkan SKU`, 'error')

		messageBoxBeforeRequest('Ingin Simpan data?', 'Iya, Simpan', 'Tidak, Tutup').then((result) => {
			if (result.value == true) {
				postData("<?= base_url('WMS/Transaksi/MutasiAntarUnit/PersiapanMutasiAntarUnit/saveData') ?>", {
					trMutasiDepoId: trMutasiDepoId ? trMutasiDepoId : "",
					kodeDokumen,
					ekspedisi,
					pengemudi,
					kendaraan,
					status,
					keterangan,
					mutasiAntarDepo,
					mutasiAntarDepoDetail,
					lastUpdated
				}, 'POST', function(response) {
					if (response.status === 200) {
						message_topright('success', response.message)
						setTimeout(() => handlerBackToHome(), 1000)
					}

					if (response.status === 401) return message_topright("error", response.message);
					if (response.status === 400) return messageNotSameLastUpdated()
				})
			}
		});

	}


	/******************************************************* Form  *********************************************************/

	/******************************************************* Get Data  *********************************************************/

	const getDataMutasiDepo = () => {
		postData("<?= base_url('WMS/Transaksi/MutasiAntarUnit/PersiapanMutasiAntarUnit/getDataMutasiDepo') ?>", {
			trMutasiDepoId
		}, 'POST', function(response) {

			/** Header */
			$("#lastUpdated").val(response.mutasiDepo.tr_mutasi_depo_tgl_update)

			$("#kodeDokumen").val(response.mutasiDepo.tr_mutasi_depo_kode)
			$("#ekspedisi").val(response.mutasiDepo.ekspedisi_id).trigger('change')
			$("#pengemudi").val(response.mutasiDepo.karyawan_id).trigger('change')
			$("#kendaraan").val(response.mutasiDepo.kendaraan_id).trigger('change')
			$("#keterangan").val(response.mutasiDepo.tr_mutasi_depo_keterangan).trigger('change')
			$("#status").val(response.mutasiDepo.tr_mutasi_depo_status)

			$('#initMutasiAntarDepo').empty();

			/** Detail Mutasi depo */
			response.mutasiDepoDetail.map((value, index) => {
				const counterData = index + 1;

				mutasiAntarDepo.push({
					counter: counterData,
					depoAsal: value.depo_id_asal,
					depoTujuan: value.depo_id_tujuan,
					gudangAsal: value.depo_detail_id_asal,
					trMutasiDepoDetailId: value.tr_mutasi_depo_detail_id
				})

				appendMutasiDepo(counterData)

				setTimeout(() => {
					$(`#depoAsal${counterData}`).val(value.depo_id_asal).trigger('change')
					$(`#gudangAsal${counterData}`).val(value.depo_detail_id_asal).trigger('change')
					$(`#depoTujuan${counterData}`).val(value.depo_id_tujuan).trigger('change')
				}, 500)


				/** Detail 2 Mutasi depo */
				if (response.mutasiDepoDetail2) {
					response.mutasiDepoDetail2.map((childMutasiDepo, idx) => {
						if (value.tr_mutasi_depo_detail_id === childMutasiDepo.tr_mutasi_depo_detail_id) {
							mutasiAntarDepoDetail.push({
								sku_stock_id: childMutasiDepo.sku_stock_id,
								sku_induk_nama: childMutasiDepo.sku_induk_nama,
								sku_id: childMutasiDepo.sku_id,
								sku_kode: childMutasiDepo.sku_kode,
								sku_nama_produk: childMutasiDepo.sku_nama_produk,
								sku_kemasan: childMutasiDepo.sku_kemasan,
								sku_satuan: childMutasiDepo.sku_satuan,
								ed: childMutasiDepo.ed,
								qtySistem: childMutasiDepo.qtySistem,
								qtyAmbil: childMutasiDepo.qtyAmbil,
								counter: counterData,
								type: 'fix'
							})
						}
					})
				}
			})

			/** Hidden and disabled */
			if (response.mutasiDepo.tr_mutasi_depo_status !== 'Draft') {

				['In Progress Picking'].includes(response.mutasiDepo.tr_mutasi_depo_status) ? $('.cetakSKUChild').hide() : $('.cetakSKUChild').show();

				$("#ekspedisi").prop('disabled', true);
				$("#pengemudi").prop('disabled', true);
				$("#kendaraan").prop('disabled', true);
				$("#keterangan").prop('disabled', true);
				$("#chckStatus").attr({
					disabled: 'disabled',
					checked: 'checked'
				})

				$('select[name*="depoAsal"]').prop('disabled', true)
				$('select[name*="gudangAsal"]').prop('disabled', true)
				$('select[name*="depoTujuan"]').prop('disabled', true)
			}
		})
	}

	/******************************************************* End Get Data  *********************************************************/
</script>