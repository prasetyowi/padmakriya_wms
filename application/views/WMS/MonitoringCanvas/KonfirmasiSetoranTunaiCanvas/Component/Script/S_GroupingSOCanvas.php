<script>
	let dataSOChoose = []

	$(document).ready(function() {
		select2();
		initDatatable('#listDataFilter')
		if ($('#filter_tgl_request').length > 0) {
			$('#filter_tgl_request').daterangepicker({
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

		if ("<?= $this->input->get('mode') ?>" === 'edit') {
			$('.title-form').html('Edit Data');
			checkTempCanvasGrouping();
		} else {
			$('.title-form').html('View Data');
			$('.btn-konfirmasi').hide();
			$('.btn-simpan').hide()

			fetch('<?= base_url('FAS/OperasionalCanvas/GroupingSOCanvas/checkTempCanvasGrouping/') . $this->uri->segment(5) . '/view' ?>')
				.then(response => response.json())
				.then((results) => {
					if (results.length > 0) {
						results.map((value) => dataSOChoose.push({
							...value
						}))
						initDataAppendDetailSO();
						initSummaryDetailDataSOChoose();
					}
				});
		}

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

	const message = (msg, msgtext, msgtype) => {
		Swal.fire(msg, msgtext, msgtype);
	}

	const message_topright = (type, msg) => {
		const Toast = Swal.mixin({
			toast: true,
			position: "top-end",
			showConfirmButton: false,
			timer: 3000,
			didOpen: (toast) => {
				toast.addEventListener("mouseenter", Swal.stopTimer);
				toast.addEventListener("mouseleave", Swal.resumeTimer);
			},
		});

		Toast.fire({
			icon: type,
			title: msg,
		});
	}

	const resetUrlPushState = () => {
		let url = window.location.href;
		if (url.indexOf("?") != -1) {
			let resUrl = url.split("?");

			if (typeof window.history.pushState == 'function') {
				window.history.pushState({}, "Hide", resUrl[0]);
			}
		}
	}

	const handlerBackToHome = () => {
		location.href = "<?= base_url('FAS/OperasionalCanvas/GroupingSOCanvas/GroupingSOCanvasMenu') ?>";
	}

	const checkTempCanvasGrouping = () => {
		fetch('<?= base_url('FAS/OperasionalCanvas/GroupingSOCanvas/checkTempCanvasGrouping/') . $this->uri->segment(5) . '/edit' ?>')
			.then(response => response.json())
			.then((results) => {
				if (results.length > 0) {
					$('.btn-konfirmasi').prop('disabled', false)
					results.map((value) => dataSOChoose.push({
						...value
					}))
					initDataAppendDetailSO();
					initSummaryDetailDataSOChoose();

				} else {
					$('.btn-konfirmasi').prop('disabled', true)
				}
			});
	}

	/** Halaman Depan */

	function getStringOfArrayString(data) {
		let string = "";

		data.forEach(datum => string += datum + ", ");

		return string.slice(0, -2);
	}

	const handlerFilterData = () => {
		$('#listDataFilter > tbody').empty();

		postData("<?= base_url('FAS/OperasionalCanvas/GroupingSOCanvas/getDataByFilter') ?>", {
			tanggal: $("#filter_tgl_request").val(),
			canvasId: $("#kodeCanvasId").attr('data-canvasId'),
			perusahaan: $("#perusahaan option:selected").val(),
			sales: $("#sales option:selected").val(),
			status: $("#status option:selected").val()
		}, 'POST', function(response) {
			if (response.length > 0) {
				if ($.fn.DataTable.isDataTable('#listDataFilter')) {
					$('#listDataFilter').DataTable().clear();
					$('#listDataFilter').DataTable().destroy();
				}
				response.map((value, index) => {

					let strAction = '';

					if (value.canvas_status === 'Approved') {
						strAction += `<button class='btn btn-warning btn-sm' title='Edit Data' onclick="handlerEditCanvas('${value.canvas_id}')"><i class='fas fa-pencil'> </i></button>`
					} else if (value.canvas_status === 'in progress') {
						strAction += `<button class='btn btn-primary btn-sm' title='View Data' onclick="handlerViewCanvas('${value.canvas_id}')"><i class='fas fa-eye'> </i></button>`
					} else {
						strAction += `-`
					}

					$('#listDataFilter > tbody').append(`
                <tr class="text-center">
                    <td>${index + 1}</td>
                    <td>${value.canvas_kode}</td>
                    <td>${value.canvas_requestdate}</td>
                    <td>${value.client_wms_nama}</td>
                    <td>${value.sales}</td>
                    <td>${value.canvas_status}</td>
                    <td>${strAction}</td>
                </tr>
            `)
				})

				initDatatable('#listDataFilter')
			} else {
				$('#listDataFilter > tbody').append(`<tr><td colspan=7"><h4 class="text-center text-danger">Data not found</h4></td></tr>`)
			}

		}, '#btnsearchdata')

	}

	/** End Halaman Depan */

	const handlerGetKodeCanvas = (event) => {
		const stateValue = event.currentTarget.value;

		if (stateValue !== '') {
			fetch('<?= base_url('FAS/OperasionalCanvas/GroupingSOCanvas/getKodeAutoComplete?params=') ?>' + stateValue)
				.then(response => response.json())
				.then((results) => {
					if (!results) {
						$('#table-fixed').css('display', 'none');
					} else {
						$('#konten-table').empty();
						results.map((value, index) => {
							$('#konten-table').append(`
									<tr onclick="handlerCanvasIdByKode('${value.canvas_id}', '${value.canvas_kode}')" style="cursor:pointer">
												<td width="10%">${index + 1}.</td>
												<td width="90%">${value.canvas_kode}</td>
									</tr>`);
						})
						$('#table-fixed').css('display', 'block');
					}
				});
		} else {
			$('#table-fixed').css('display', 'none');
		}
	}

	const handlerCanvasIdByKode = (canvasId, canvasKode) => {
		$("#kodeCanvasId").attr('data-canvasId', canvasId);
		$("#kodeCanvasId").val(canvasKode);
		$('#table-fixed').css('display', 'none');
	}


	/** Handler Edit */
	const handlerEditCanvas = (canvasId) => {
		window.open("<?= base_url('FAS/OperasionalCanvas/GroupingSOCanvas/form/') ?>" + canvasId + `?mode=edit`, '_blank');
	}

	const handlerAddSO = () => {
		$('#modal-addSo').modal('show');

		postData("<?= base_url('FAS/OperasionalCanvas/GroupingSOCanvas/getBrandByClientWms/') ?>" + $("#client_wms_id").val(), {}, 'GET', function(response) {
			$('#brandFilter').empty();
			$('#brandFilter').append(`<option value="all">--All--</option>`);
			if (response.length > 0) {
				response.map((value) => {
					$('#brandFilter').append(`<option value="${value.id}">${value.brand}</option>`);
				});
			}
		})

		setTimeout(() => requestGetDataSO(), 1000)

	}


	const requestGetDataSO = () => {
		$('#table-list-so > tbody').empty();

		if ($.fn.DataTable.isDataTable('#table-list-so')) {
			$('#table-list-so').DataTable().clear();
			$('#table-list-so').DataTable().destroy();
		}

		postData("<?= base_url('FAS/OperasionalCanvas/GroupingSOCanvas/getDataSOByTypeCanvas') ?>", {
			clientWmsId: $("#client_wms_id").val(),
			principleBrandId: $("#brandFilter option:selected").val()
		}, 'POST', function(response) {
			if (response.length > 0) {

				response.map((value, index) => {

					const findDataSo = dataSOChoose.find((item) => item.sales_order_id === value.sales_order_id);

					$("#table-list-so > tbody").append(`
								<tr>
										<td width="5%" class="text-center"><input type="checkbox" name="CheckboxSO" ${typeof findDataSo !== 'undefined' ? 'checked disabled' : ''} data-so='${JSON.stringify({...value})}' id="check-so-${index}" value="${value.sales_order_id}"></td>
										<td width="10%" class="text-center">${value.tanggal}</td>
                    <td width="10%" class="text-center">${value.sales_order_kode}</td>
                    <td width="15%" class="text-center">${value.sales_order_no_po}</td>
                    <td width="15%" class="text-center">${value.customer}</td>
                    <td width="20%" class="text-center">${value.alamat}</td>
                    <td width="10%" class="text-center">${value.sales_order_keterangan}</td>
								</tr>
						`);
				})

				$('#table-list-so').DataTable({
					columnDefs: [{
						sortable: false,
						targets: [0, 1, 2, 3, 4, 5, 6]
					}],
					lengthMenu: [
						[-1],
						['All']
					],
				})
			} else {
				$('#table-list-so > tbody').append(`<tr><td colspan=7"><h4 class="text-center text-danger">Data not found</h4></td></tr>`)
			}

		})
	}

	const handlerSelectAllSO = (event) => {
		if (event.currentTarget.checked) {
			$('[name="CheckboxSO"]:checkbox').map(function() {
				if (!this.disabled) this.checked = true;
			});
		} else {
			$('[name="CheckboxSO"]:checkbox').map(function() {
				if (!this.disabled) this.checked = false;
			});
		}
	}

	const handlerChoosenSOInChecked = () => {
		const dataSOChecked = $('[name="CheckboxSO"]:checkbox').map(function() {
			if (!this.disabled && this.checked) {
				return JSON.parse(this.getAttribute('data-so'));
			}
		}).get();

		if (dataSOChecked.length === 0) return message('Error', 'Pilih minimal 1 data SO', 'error');

		const dataSo = dataSOChecked.map((itemChecked) => {
			const findDataSO = dataSOChoose.find((data) => data.sales_order_id === itemChecked.sales_order_id);
			if (typeof findDataSO === 'undefined') dataSOChoose.push({
				...itemChecked
			})
		})

		$('#modal-addSo').modal('hide');
		$('#table-list-so > tbody').empty();

		initDataAppendDetailSO();
		initSummaryDetailDataSOChoose();

	}

	const handlerDeleteSoData = (salesOrderId) => {
		const newDataSoArray = dataSOChoose.filter((item) => item.sales_order_id !== salesOrderId);
		dataSOChoose = [];
		newDataSoArray.map((data) => {
			dataSOChoose.push({
				...data
			})
		})

		initDataAppendDetailSO();
		initSummaryDetailDataSOChoose();

	}

	const initDataAppendDetailSO = () => {

		$('#table-so-choose > tbody').empty();

		// if ($.fn.DataTable.isDataTable('#table-list-choose')) {
		// 	$('#table-list-choose').DataTable().clear();
		// 	$('#table-list-choose').DataTable().destroy();
		// }

		dataSOChoose.map((value, index) => {
			let rowAction = '';
			if ("<?= $this->input->get('mode') ?>" === 'edit') rowAction += `<td width="10%" class="text-center"><button class="btn btn-danger btn-sm" onclick="handlerDeleteSoData('${value.sales_order_id}')"><i class="fa fa-trash"></i></button></td>`
			$("#table-so-choose > tbody").append(`
				<tr>
						<td width="5%" class="text-center">${index + 1}</td>
						<td width="10%" class="text-center">${value.tanggal}</td>
						<td width="10%" class="text-center">${value.sales_order_kode}</td>
						<td width="15%" class="text-center">${value.sales_order_no_po}</td>
						<td width="15%" class="text-center">${value.customer}</td>
						<td width="20%" class="text-center">${value.alamat}</td>
						<td width="10%" class="text-center">${value.sales_order_keterangan}</td>
						${rowAction}
				</tr>
		`);
		})

		// $('#table-so-choose').DataTable();
	}

	const initSummaryDetailDataSOChoose = () => {
		const salesOrderId = dataSOChoose.map((item) => item.sales_order_id);

		postData("<?= base_url('FAS/OperasionalCanvas/GroupingSOCanvas/summaryDetailDataSOChoose') ?>", {
			canvasId: "<?= $this->uri->segment(5) ?>",
			salesOrderId,
		}, 'POST', function(response) {
			$("#table-so-summary > tbody").empty();
			if (response.length > 0) {

				response.map((value, index) => {

					$("#table-so-summary > tbody").append(`
							<tr>
									<td width="5%" class="text-center">${index + 1}</td>
									<td width="10%" class="text-center">${value.sku_kode}</td>
									<td width="10%" class="text-center">${value.sku_nama}</td>
									<td width="15%" class="text-center">${value.sku_kemasan}</td>
									<td width="15%" class="text-center">${value.sku_satuan}</td>
									<td width="20%" class="text-center">${value.sku_qty_canvas}</td>
									<td width="10%" class="text-center">${value.sku_qty_so}</td>
									<td width="10%" class="text-center">${value.sisa}</td>
							</tr>
					`);
				})
			}
		})
	}

	/** End Handler Edit */


	/** Handler Save */
	const handlerSave = () => {
		if (dataSOChoose.length === 0) return message('Error!', 'Data SO masih kosong!', 'error');

		messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Iya, Simpan', 'Tidak, Tutup').then((result) => {
			if (result.value == true) {
				postData("<?= base_url('FAS/OperasionalCanvas/GroupingSOCanvas/saveData') ?>", {
					canvasId: "<?= $this->uri->segment(5) ?>",
					dataSO: dataSOChoose.map((item) => item.sales_order_id),
					lastUpdated: $("#lastUpdated").val(),
				}, 'POST', function(response) {
					if (response.status === 200) {
						message_topright('success', response.message)
						setTimeout(() => {
							Swal.fire({
								title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
								showConfirmButton: false,
								timer: 1000
							});
							location.reload();
						}, 1000);
					}

					if (response.status === 401) return message_topright("error", response.message);
					if (response.status === 400) return messageNotSameLastUpdated()
				})
			}
		});
	}
	/** Handler End Save */

	/** Handler Confirmation */
	const handlerConfirmation = () => {
		messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Iya, Konfirmasi', 'Tidak, Tutup').then((result) => {
			if (result.value == true) {
				postData("<?= base_url('FAS/OperasionalCanvas/GroupingSOCanvas/confirmationData') ?>", {
					canvasId: "<?= $this->uri->segment(5) ?>",
					dataSO: dataSOChoose.map((item) => item.sales_order_id),
					lastUpdated: $("#lastUpdated").val(),
				}, 'POST', function(response) {
					if (response.status === 200) {
						message_topright('success', response.message)
						setTimeout(() => {
							Swal.fire({
								title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
								showConfirmButton: false,
								timer: 1000
							});
							handlerBackToHome();
						}, 1000);
					}

					if (response.status === 401) return message_topright("error", response.message);
					if (response.status === 400) return messageNotSameLastUpdated()
				})
			}
		});
	}
	/** Handler End Confirmation */

	/** Handler View */
	const handlerViewCanvas = (canvasId) => {
		window.open("<?= base_url('FAS/OperasionalCanvas/GroupingSOCanvas/form/') ?>" + canvasId + `?mode=view`, '_blank');
	}

	/** End Handler View */
</script>