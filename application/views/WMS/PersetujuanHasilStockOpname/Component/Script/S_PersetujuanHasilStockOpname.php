<script>
	const urlSearchParams = new URLSearchParams(window.location.search);
	const idOpname = Object.fromEntries(urlSearchParams.entries()).id;
	loadingBeforeReadyPage()
	$(document).ready(function() {
		$(".select2").select2({
			width: "100%"
		});

		$(".select22").select2({
			width: "100%",
			placeholder: 'Press CTRL+A for selecr or unselect all options'
		});

		$(document).on("input", ".numeric", function(event) {
			this.value = this.value.replace(/[^\d.]+/g, '');
		});

		// resetUrlPushState();



		if (typeof idOpname !== 'undefined') {
			$("#kode_dokumen").val(idOpname).trigger('change')
		}

		getDataDokumenOpname('adjustment');
		disabledSaveWhenNotCheckedRevisi();

		$("#showApproval").show('slow')

	})

	/** --------------------------------------- Untuk Global ------------------------------------------- */

	$('.select22[multiple]').siblings('.select2-container').append('<span class="select-all"></span>');

	$(document).on('click', '.select-all', function(e) {
		selectAllSelect2($(this).siblings('.selection').find('.select2-search__field'));
	});

	$(document).on("keyup", ".select2-search__field", function(e) {
		var eventObj = window.event ? event : e;
		if (eventObj.keyCode === 65 && eventObj.ctrlKey)
			selectAllSelect2($(this));
	});

	const removeField = () => {
		$("#penanggung_jawab").val("");
		$("#tgl").val("");
		$("#perusahaan").val("");
		$("#principle").val("");
		$("#jenis_stock").val("");
		$("#tipe_stock_opname").val("");
		$("#unit_cabang").val("");
		$("#area_opname").val("");
		$("#status").val("");
		$("#sectionDataDetailOpname").hide('slow');
	}

	const removeFieldCompare = () => {
		// $("#areaSelected").val("").trigger('change');
		$("#kode_dokumen_compare").val("").trigger('change');
		$("#init-data-compare-list").hide('slow');
	}

	function selectAllSelect2(that) {

		var selectAll = true;
		var existUnselected = false;
		var item = $(that.parents("span[class*='select2-container']").siblings('.select22[multiple]'));

		item.find("option").each(function(k, v) {
			if (!$(v).prop('selected')) {
				existUnselected = true;
				return false;
			}
		});

		selectAll = existUnselected ? selectAll : !selectAll;

		item.find("option").prop('selected', selectAll);
		item.trigger('change');
	}

	const chkCompareOrApproval = (event) => {
		if (event.currentTarget.value == "0") {
			removeFieldCompare();
			removeField()
			$(`#showApproval`).fadeOut("slow", () => $(this).hide()).fadeIn("slow", () => $(this).show());
			$("#showCompare").hide('slow')
			$("#btnConfirmApprovalOpname").prop('disabled', false)
			$("#btnConfirmApprovalOpname").attr('mode', '0')
			$("#btnSaveApprovalOpname").prop('disabled', false)
			$("#lengtDataCompare").val("0")

			// $("#titleMenu").attr('name', "CAPTION-ADJUSMENTSTOCK");

			getDataDokumenOpname('adjustment')
			$("#btnSaveApprovalOpname").hide();
			$("#btnCetakApprovalOpname").hide();
			disabledSaveWhenNotCheckedRevisi();
		} else if (event.currentTarget.value == "1") {
			$("#showApproval").hide('slow');
			$("#showCompare").show('slow');
			resetUrlPushState()
			// $("#sectionDataDetailOpname").empty();
			$("#btnSaveApprovalOpname").show();
			$("#btnCetakApprovalOpname").hide();
			disabledSaveWhenNotCheckedRevisi()
			$("#sectionDataDetailOpname").hide('slow');
			$("#kode_dokumen").val("").trigger('change');
			$("#btnConfirmApprovalOpname").prop('disabled', true)
			$("#btnConfirmApprovalOpname").attr('mode', '1')
			// $("#areaSelected").val("").trigger('change');

			// $("#titleMenu").attr('name', "CAPTION-COMPARE");
			removeField()
		} else {
			removeFieldCompare();
			removeField()

			$(`#showApproval`).fadeOut("slow", () => $(this).hide()).fadeIn("slow", () => $(this).show());
			$("#showCompare").hide('slow')
			$("#btnConfirmApprovalOpname").prop('disabled', false)
			$("#btnConfirmApprovalOpname").attr('mode', '2')
			$("#btnSaveApprovalOpname").prop('disabled', false)
			$("#lengtDataCompare").val("0")

			// $("#titleMenu").attr('name', "CAPTION-123007000");

			$("#btnSaveApprovalOpname").hide();
			$("#btnConfirmApprovalOpname").show();
			$("#kode_dokumen").val() == "" ? $("#btnCetakApprovalOpname").hide() : $("#btnCetakApprovalOpname").show();

			getDataDokumenOpname('result')

		}

		// Translate();
	}

	// const message = (msg, msgtext, msgtype) => {
	// 	Swal.fire(msg, msgtext, msgtype);
	// }

	// const message_topright = (type, msg) => {
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

	const getDataDokumenOpname = (type) => {
		postDataRequest('<?= base_url('WMS/PersetujuanHasilStockOpname/getDataDokumenOpnameKode') ?>', {
				type
			}, 'POST')
			.then((response) => {
				$("#kode_dokumen").empty();
				if (response) {
					$("#kode_dokumen").append(`<option value="">--Pilih Kode Dokumen--</option>`)
					response.map((item) => {
						$("#kode_dokumen").append(`<option value="${item.id}">${item.kode} (${item.status})</option>`)
					})
				}
			});
	}

	const disabledSaveWhenNotCheckedRevisi = () => {

		$(".chkCompareOrApproval").each(function() {
			if ($(this).is(":checked")) {
				if ($(this).val() == "0") {
					if ($("#kode_dokumen").val() == "") {
						$("#btnSaveApprovalOpname").prop('disabled', true);
					} else {
						let count = 0;
						$(".revisiPalletOpname").each(function() {
							let rowRevisiFor = $(this);
							if (rowRevisiFor.is(":checked") == true) {
								count++;
							}
						})

						if (count > 0) {
							$("#btnSaveApprovalOpname").prop('disabled', false);
						} else {
							$("#btnSaveApprovalOpname").prop('disabled', true);
						}
					}
				}

				if ($(this).val() == "1") {
					$("#lengtDataCompare").val()
					if ($("#lengtDataCompare").val() == "0") {
						$("#btnSaveApprovalOpname").prop('disabled', true);
					} else {
						$("#btnSaveApprovalOpname").prop('disabled', false);
					}
				}
			}

		})
	}

	/** --------------------------------------- End Untuk Global ------------------------------------------- */

	const handlerShowDataDetailOpnameSelisih = (event) => {
		$("#initDetailOpname").empty();

		let chkType = "0";

		$(".chkCompareOrApproval").each(function() {
			if ($(this).is(":checked") == true) {
				if ($(this).val() == "1") chkType = "1";
				if ($(this).val() == "2") chkType = "2";
			}
		});

		if (event.currentTarget.checked == true) {
			postDataRequest('<?= base_url('WMS/PersetujuanHasilStockOpname/getDataOpnameByKode') ?>', {
					value: $("#kode_dokumen").val(),
					type: "selisih"
				}, 'POST')
				.then((response) => {
					initDataOpnameDetail(response.detail, chkType)
				});
			disabledSaveWhenNotCheckedRevisi();
		} else {
			postDataRequest('<?= base_url('WMS/PersetujuanHasilStockOpname/getDataOpnameByKode') ?>', {
					value: $("#kode_dokumen").val(),
					type: null
				}, 'POST')
				.then((response) => {
					initDataOpnameDetail(response.detail, chkType)
				});
			disabledSaveWhenNotCheckedRevisi();
		}

	}

	const handlerShowDataDetailOpnameSelisihBySKU = (event) => {
		$("#initDataOpnameDetailBySku").empty();

		if (event.currentTarget.checked == true) {
			postDataRequest('<?= base_url('WMS/PersetujuanHasilStockOpname/getDataDetailOpnameBySKU') ?>', {
					opnameId: $("#kode_dokumen").val(),
					type: "selisih"
				}, 'POST')
				.then((response) => {
					$("#modalOpnameBySKU").modal('show');
					initDataOpnameDetailBySKU(response.detail)
				})
		} else {
			postDataRequest('<?= base_url('WMS/PersetujuanHasilStockOpname/getDataDetailOpnameBySKU') ?>', {
					opnameId: $("#kode_dokumen").val(),
					type: null
				}, 'POST')
				.then((response) => {
					$("#modalOpnameBySKU").modal('show');
					initDataOpnameDetailBySKU(response.detail)
				})
		}

	}

	const handlerGetDataOpnameByKode = (value) => {

		let chkType = "0";

		$(".chkCompareOrApproval").each(function() {
			if ($(this).is(":checked") == true) {
				if ($(this).val() == "1") chkType = "1";
				if ($(this).val() == "2") chkType = "2";
			}
		})

		if (chkType == "0" || chkType == "2") {
			if (value == "") {
				message("Error!", "Kode Dokumen tidak boleh kosong!", "error")
				removeField();
				resetUrlPushState();
				disabledSaveWhenNotCheckedRevisi();
				return false
			} else {

				//master path - original path
				path = window.location.pathname;

				$("#btnCetakApprovalOpname").attr({
					href: "<?= base_url('WMS/PersetujuanHasilStockOpname/cetakDataApproveOpname?') ?>" + `id=${value}`,
					target: "_BLANK"
				});

				//Redirectss original path to this
				window.history.pushState(null, null, path + `?id=${value}`);

				postDataRequest('<?= base_url('WMS/PersetujuanHasilStockOpname/getDataOpnameByKode') ?>', {
						value,
						type: null
					}, 'POST')
					.then((response) => {
						$("#sectionDataDetailOpname").show('slow');
						$("#lastUpdate").val(response.lastUpdate.tgl);

						initDataOpnameHeader(response.header)
						initDataOpnameDetail(response.detail, chkType);

						disabledSaveWhenNotCheckedRevisi();
					});
			}
		}


	}

	const initDataOpnameHeader = (header) => {
		$("#penanggung_jawab").val(header.penanggung_jawab);
		$("#tgl").val(header.tanggal);
		$("#perusahaan").val(header.perusahaan);
		$("#principle").val(header.principle);
		$("#jenis_stock").val(header.jenis_stock);
		$("#tipe_stock_opname").val(header.tipe_stock_opname);
		$("#unit_cabang").val(header.unit_cabang);
		$("#area_opname").val(header.area_opname);
		$("#status").val(header.status);

		if (header.status == 'Completed') {
			$('#btnConfirmApprovalOpname').prop('disabled', true)
			$("#btnCetakApprovalOpname").show();
		} else {
			$('#btnConfirmApprovalOpname').prop('disabled', false)
			$("#btnCetakApprovalOpname").hide();
		}
	}

	const initDataOpnameDetail = (detail, type) => {

		let html = "";

		$(`#initDetailOpname`).empty();

		$.each(detail, function(index, value) {
			html += `<div style="margin-top:10px;margin-bottom:10px">`
			if (type == '0') {
				html += `<div style="margin:10px 10px 10px 0px">
									<h4 style="display:inline;font-weight:700"><strong>${index}</strong></h4>
									<input type="checkbox" style="display:inline;margin-left:10px" class="check-item revisiPalletOpname" ${value.status == "In Progress" ? "disabled" : ""} id="revisiPalletOpname_${value.opname_detail2_id}_${value.pallet_id}" onchange="handlerRevisiPalletDetailOpname('${value.opname_detail2_id}','${value.pallet_id}', event)">
									<span style="font-size:13px;font-weight:600;display:inline" name="CAPTION-REVISI">Revisi?</span>

									<div style="display:inline;margin-left:10px;">
										<input type="radio" id="checker_${value.opname_detail2_id}_${value.pallet_id}" name="revision_${value.opname_detail2_id}_${value.pallet_id}" value="checker" style="display:none" class="showRevisiFor_${value.opname_detail2_id}_${value.pallet_id} showRevisiFor" onchange="handlerRevisiPalletDetailOpnameFor('${value.opname_detail2_id}','${value.pallet_id}', event)">
										<label for="checker_${value.opname_detail2_id}_${value.pallet_id}" style="display:none" class="showRevisiFor_${value.opname_detail2_id}_${value.pallet_id}">Checker</label>
										<input type="radio" id="supervisor_${value.opname_detail2_id}_${value.pallet_id}" name="revision_${value.opname_detail2_id}_${value.pallet_id}" value="supervisor" style="display:none" class="showRevisiFor_${value.opname_detail2_id}_${value.pallet_id} showRevisiFor" onchange="handlerRevisiPalletDetailOpnameFor('${value.opname_detail2_id}','${value.pallet_id}', event)">
										<label for="supervisor_${value.opname_detail2_id}_${value.pallet_id}" style="display:none" class="showRevisiFor_${value.opname_detail2_id}_${value.pallet_id}">Supervisor</label>
									</div>`;
				if (value.status == "In Progress") {
					html += '<span class="badge" style="display:inline;background:#dc2626;color:white"><i class="fas fa-spinner" id="loading-spinner"></i> <label name="CAPTION-MASIHPROSESREVISICHECKER">Masih di proses revisi checker</label></span>';
				}
				html += `</div>`;
			}

			html += `<div class="table-responsive">
            <table class="table table-striped table-bordered" width="100%" id="initTableDetailOpname_${value.opname_detail2_id}_${value.pallet_id}">
            <thead>
                <tr class="text-center">
                  <td width="10%"><strong><label name="CAPTION-KODERAK">Kode Rak</label></strong></td>
                  <td width="10%"><strong><label name="CAPTION-KODELOKASI">Kode Lokasi</label></strong></td>
                  <td width="10%"><strong><label name="CAPTION-SKUKODE">Kode SKU</label></strong></td>
                  <td width="15%"><strong><label name="CAPTION-SKUNAMA">Nama SKU</label></strong></td>
                  <td width="8%"><strong><label name="CAPTION-EXPDATE">Exp Date</label></strong></td>
                  <td width="8%"><strong><label name="CAPTION-KEMASAN">Kemasan</label></strong></td>
                  <td width="8%"><strong><label name="CAPTION-SKUSATUAN">Satuan</label></strong></td>
                  <td width="8%"><strong><label name="CAPTION-QTYSISTEM">Qty Sistem</label></strong></td>
                  <td width="8%"><strong><label name="CAPTION-QTYAKTUAL">Qty Aktual</label></strong></td>
                  <td width="8%"><strong><label name="CAPTION-DEVIASI">Deviasi</label></strong></td>
                  <td width="8%"><strong><label>Batch No</label></strong></td>
                </tr>
            </thead>
            <tbody>`;

			$.each(value.data, function(i, v) {
				html += "<tr>";
				if (i == 0) {
					html += `<td style="vertical-align: middle" class="text-center" rowspan="${value.data.length}">${value.kode_rak}</td>
                  <td style="vertical-align: middle" class="text-center" rowspan="${value.data.length}">${value.area}</td>`;

				}

				html += ` <td class="text-center">${v.kode_sku} <input type="hidden" value="${v.opname_detail3_id}"/></td>
                      <td class="text-center">${v.nama_sku}</td>
                      <td class="text-center">${v.exp_date}</td>
                      <td class="text-center">${v.kemasan}</td>
                      <td class="text-center">${v.satuan}</td>
                      <td class="text-center">${v.qty_sistem}</td>
                      <td class="text-center">
                        <input type="text" name="qtyAktualOpname" class="form-control numeric qtyAktualOpname_${value.opname_detail2_id}_${value.pallet_id}" value="${v.qty_aktual}" disabled/>
                      </td>
                      <td class="text-center">${v.deviasi}</td>
                      <td class="text-center">${v.sku_batch_no}</td>
                </tr>`

			})

			html += `     </tbody>
            </table>
          </div>`;

			html += '</div>';
		})

		$(`#initDetailOpname`).fadeOut("slow", function() {
			$(this).hide();
			Swal.fire({
				title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
				showConfirmButton: false,
				timer: 500
			});
		}).fadeIn("slow", function() {
			$(this).show();
			$(`#initDetailOpname`).append(html)
			Translate();
		});

	}

	const handlerRevisiPalletDetailOpname = (opnameDetail2Id, palletId, event) => {
		if (event.currentTarget.checked == true) {
			$(`.showRevisiFor_${opnameDetail2Id}_${palletId}`).fadeIn("slow", function() {
				$(this).prop('checked', false);
				$(`.qtyAktualOpname_${opnameDetail2Id}_${palletId}`).prop("disabled", true);
				$(this).show();
			});

			$('#btnSaveApprovalOpname').show();
			$('#btnConfirmApprovalOpname').hide();
			disabledSaveWhenNotCheckedRevisi();
		} else {
			$(`.showRevisiFor_${opnameDetail2Id}_${palletId}`).fadeOut("slow", function() {
				$(this).prop('checked', false);
				$(`.qtyAktualOpname_${opnameDetail2Id}_${palletId}`).prop("disabled", true);
				$(this).hide();
			});

			$('#btnSaveApprovalOpname').hide();
			$('#btnConfirmApprovalOpname').show();
			disabledSaveWhenNotCheckedRevisi();
		}

	}

	const handlerRevisiPalletDetailOpnameFor = (opnameDetail2Id, palletId, event) => {
		if (event.currentTarget.value == "supervisor") {
			$(`.qtyAktualOpname_${opnameDetail2Id}_${palletId}`).prop("disabled", false);
		} else {
			$(`.qtyAktualOpname_${opnameDetail2Id}_${palletId}`).prop("disabled", true);
		}
	}

	const handlerCetakListSKU = () => {
		if ($("#checkSelisih").is(":checked")) {
			window.open("<?= base_url('WMS/PersetujuanHasilStockOpname/cetakDataOpnameBySKU?id=') ?>" + $("#kode_dokumen").val() + "&type=selisih", "_blank");
		} else {
			window.open("<?= base_url('WMS/PersetujuanHasilStockOpname/cetakDataOpnameBySKU?id=') ?>" + $("#kode_dokumen").val() + "&type=", "_blank");
		}
	}

	const handlerExportListSKU = () => {
		if ($("#checkSelisih").is(":checked")) {
			window.location.href = "<?= base_url('WMS/PersetujuanHasilStockOpname/exportDataOpnameBySKU?id=') ?>" + $("#kode_dokumen").val() + "&type=selisih"
		} else {
			window.location.href = "<?= base_url('WMS/PersetujuanHasilStockOpname/exportDataOpnameBySKU?id=') ?>" + $("#kode_dokumen").val() + "&type="
		}
	}

	const handlerGetDataDetailOpnameBySKU = () => {
		postDataRequest('<?= base_url('WMS/PersetujuanHasilStockOpname/getDataDetailOpnameBySKU') ?>', {
				opnameId: $("#kode_dokumen").val(),
				type: null
			}, 'POST')
			.then((response) => {
				$("#modalOpnameBySKU").modal('show');
				initDataOpnameDetailBySKU(response.detail)
			})
	}

	const initDataOpnameDetailBySKU = (response) => {
		let html = "";

		$(`#initDataOpnameDetailBySku`).empty();

		$.each(response, function(index, value) {
			html += `<div style="margin-top:10px;margin-bottom:10px">
        <div style="margin:10px 10px 10px 0px">
          <h5 style="display:inline;font-weight:600"><strong>${index},</strong></h5>
          <h5 style="display:inline;font-weight:600"><strong>${value.sku_nama_produk},</strong></h5>
          <h5 style="display:inline;font-weight:600"><strong>${value.sku_expired_date},</strong></h5>
          <h5 style="display:inline;font-weight:600"><strong>${value.sku_kemasan},</strong></h5>
          <h5 style="display:inline;font-weight:600"><strong>${value.sku_satuan},</strong></h5>
          <h5 style="display:inline;font-weight:600"><strong>Total Qty Sistem : ${value.tot_qty_sistem},</strong></h5>
          <h5 style="display:inline;font-weight:600"><strong>Total Qty Aktual ${value.tot_qty_aktual},</strong></h5>
          <h5 style="display:inline;font-weight:600"><strong>Total Deviasi ${value.tot_deviasi}</strong></h5>
        </div>`;
			html += `<div class="table-responsive">
        <table class="table table-striped table-bordered" width="100%">
        <thead>
            <tr class="text-center">
              <td width="10%"><strong><label name="CAPTION-KODERAK">Kode Rak</label></strong></td>
              <td width="10%"><strong><label name="CAPTION-KODELOKASI">Kode Lokasi</label></strong></td>
              <td width="10%"><strong><label name="CAPTION-KODEPALLET">Kode Pallet</label></strong></td>
              <td width="8%"><strong><label name="CAPTION-QTYSISTEM">Qty Sistem</label></strong></td>
              <td width="8%"><strong><label name="CAPTION-QTYAKTUAL">Qty Aktual</label></strong></td>
              <td width="8%"><strong><label name="CAPTION-DEVIASI">Deviasi</label></strong></td>
            </tr>
        </thead>
        <tbody>`;

			$.each(value.data, function(i, v) {
				html += "<tr>";
				if (i == 0) {
					html += `<td style="vertical-align: middle" class="text-center" rowspan="${value.data.length}">${value.rak_lajur_detail_nama}</td>
              <td style="vertical-align: middle" class="text-center" rowspan="${value.data.length}">${value.area}</td>`;

				}

				html += ` <td class="text-center">${v.pallet_kode}</td>
                  <td class="text-center">${v.sku_qty_sistem}</td>
                  <td class="text-center">${v.sku_actual_qty_opname}</td>
                  <td class="text-center">${v.deviasi}</td>
            </tr>`

			})

			html += `     </tbody>
        </table>
      </div>`;

			html += '</div>';
		})

		$(`#modalOpnameBySKU`).fadeOut("slow", function() {
			$(this).modal('hide');
			Swal.fire({
				title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
				showConfirmButton: false,
				timer: 500
			});
		}).fadeIn("slow", function() {
			$(this).modal('show');
			$(`#initDataOpnameDetailBySku`).append(html)
			Translate();
		});

	}

	const handlerSaveDataApproveOpname = () => {

		$(".chkCompareOrApproval").each(function() {
			if ($(this).is(":checked")) {
				if ($(this).val() == "0") {
					saveRequestApproval()
				}

				if ($(this).val() == "1") {
					saveRequestCompare()
				}
			}

		})
	}

	const saveRequestApproval = () => {
		if ($("#kode_dokumen").val() == "") {
			message("Error!", "Gagal simpan, silahkan pilih Kode Dokumen terlebih dahulu", "error")
			return false;
		} else {
			if ($(".revisiPalletOpname:checked").length > 0) {
				let error = false;
				let arrDataChecker = [];
				arrDataChecker = []

				let arrIdTempDetail3 = [];
				let arrAktualQty = [];
				let arrDataSupervisor = [];
				arrDataSupervisor = [];


				$(".revisiPalletOpname").each(function() {
					let rowRevisi = $(this);
					if (rowRevisi.is(":checked") == true) {
						let attrId = rowRevisi.attr('id').split('_');
						let opnameDetail2Id = attrId[1];
						let palletId = attrId[2];

						let isCheckedRevisionFor = $(`.showRevisiFor_${opnameDetail2Id}_${palletId}`);
						if (isCheckedRevisionFor.is(":checked") !== true) {
							message("Error!", "Gagal simpan, ada revisi untuk yang belum terpilih!", "error")
							error = true;
						}

					}
				})

				$('input[name="qtyAktualOpname"]').filter(function() {
					if (!this.disabled == true) {
						if (this.value == "") {
							message("Error!", "Gagal simpan, Qty Aktual tidak boleh kosong!", "error")
							error = true;
						}
					}
				})

				$(".showRevisiFor").each(function() {
					let rowRevisiFor = $(this);
					if (rowRevisiFor.is(":checked") == true) {
						if (rowRevisiFor.val() == "checker") {
							let attrId = rowRevisiFor.attr('id').split('_');
							arrDataChecker.push({
								opnameDetail2Id: attrId[1],
								palletId: attrId[2]
							})
						} else {
							let attrId = rowRevisiFor.attr('id').split('_');
							$(`#initTableDetailOpname_${attrId[1]}_${attrId[2]} > tbody tr`).each(function(index, value) {
								let rowTable = $(this)

								let idDetail3Temp = "";
								let aktualQty = "";
								if (index == 0) {
									idDetail3Temp = rowTable.find("td:eq(2) input[type='hidden']");
									aktualQty = rowTable.find("td:eq(8) input[type='text']");
								} else {
									idDetail3Temp = rowTable.find("td:eq(0) input[type='hidden']");
									aktualQty = rowTable.find("td:eq(6) input[type='text']");
								}

								idDetail3Temp.map(function() {
									arrIdTempDetail3.push(this.value);
								}).get();

								aktualQty.map(function() {
									arrAktualQty.push(this.value);
								}).get();
							});
						}
					}
				})

				if (error == true) return false;

				for (let index = 0; index < arrIdTempDetail3.length; index++) {
					arrDataSupervisor.push({
						'id_temp3': arrIdTempDetail3[index],
						'aktual_qty': arrAktualQty[index],
					});
				}

				Swal.fire({
					title: "Apakah anda yakin?",
					text: "Ingin simpan data opname?",
					icon: "warning",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Ya, Yakin",
					cancelButtonText: "Tidak, Tutup"
				}).then((result) => {
					if (result.value == true) {
						postDataRequest('<?= base_url('WMS/PersetujuanHasilStockOpname/saveDataApprovalOpname') ?>', {
								opnameId: $("#kode_dokumen").val(),
								dataChecker: arrDataChecker,
								dataSupervisor: arrDataSupervisor
							}, 'POST')
							.then((response) => {
								if (response == true) {
									message_topright('success', 'Data berhasil disimpan');
									setTimeout(() => {
										Swal.fire({
											title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
											showConfirmButton: false,
											timer: 1000
										});
									}, 1000);

									$("#kode_dokumen").val($("#kode_dokumen").val()).trigger('change')

								}
								if (response == false) message_topright('error', 'Data gagal disimpan');

								$('#btnConfirmApprovalOpname').show();
								$('#btnSaveApprovalOpname').hide();
							})
					}
				});

			} else {
				//save langsung tanpa pengecekan apa"
				console.log('save langsung');
			}
		}
	}

	const saveRequestCompare = () => {

		let error = false;
		let arrPalletId = [];
		let arrRakLajurDetailId = [];
		let arrSkuId = [];
		let arrSkuStockId = [];
		let arrExpDate = [];
		let arrQty = [];
		let mappingData = [];

		// $(".expDate_").each(function() {
		//   if ($(this).val() == "") {
		//     message("Error!", "Gagal simpan, Expired Date Final kosong", "error")
		//     error = true;
		//     return false;
		//   }
		//   error = false
		// })

		// $(".qtyCompare_").each(function() {
		//   if ($(this).val() == "") {
		//     message("Error!", "Gagal simpan, Qty Final kosong", "error")
		//     error = true;
		//     return false;
		//   }
		//   error = false
		// })

		if (error) return false;

		$(".initDataDetailCompare tbody tr").each(function() {
			let palletId = $(this).find("td:eq(0) input[type='hidden']")
			let rakLajurDetailId = $(this).find("td:eq(1) input[type='hidden']")
			let skuId = $(this).find("td:eq(2) input[type='hidden']")
			let skuStockId = $(this).find("td:eq(3) input[type='hidden']")
			let expDate = $(this).find("td:nth-last-child(2) input[type='date']")
			let qty = $(this).find("td:last input[type='text']")

			palletId.map(function() {
				arrPalletId.push($(this).val());
			}).get();
			rakLajurDetailId.map(function() {
				arrRakLajurDetailId.push($(this).val());
			}).get();
			skuId.map(function() {
				arrSkuId.push($(this).val());
			}).get();
			skuStockId.map(function() {
				arrSkuStockId.push($(this).val());
			}).get();
			expDate.map(function() {
				arrExpDate.push($(this).val());
			}).get();
			qty.map(function() {
				arrQty.push($(this).val());
			}).get();
		})

		if (arrPalletId.length > 0) {
			for (let index = 0; index < arrPalletId.length; index++) {
				mappingData.push({
					'palletId': arrPalletId[index],
					'rakLajurDetailId': arrRakLajurDetailId[index],
					'skuId': arrSkuId[index],
					'skuStockId': arrSkuStockId[index],
					'expDate': arrExpDate[index],
					'qty': arrQty[index],
				})
			}
		}

		Swal.fire({
			title: "Apakah anda yakin?",
			text: "Ingin simpan data opname hasil compare?",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Yakin",
			cancelButtonText: "Tidak, Tutup"
		}).then((result) => {
			if (result.value == true) {
				$.ajax({
					type: "POST",
					url: "<?= base_url('WMS/PersetujuanHasilStockOpname/saveDataApprovalOpnameByCompare') ?>",
					data: {
						opnameId: $("#kode_dokumen_compare").val(),
						mappingData
					},
					dataType: "json",
					success: function(response) {
						if (response == true) {
							message_topright('success', 'Data berhasil disimpan');
							setTimeout(() => {
								Swal.fire({
									title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
									showConfirmButton: false,
									timer: 1000
								});
								location.reload();
							}, 1000);

						}
						if (response == false) message_topright('error', 'Data gagal disimpan');
					}
				});
			}
		});

	}

	const handlerKonfimasiDataApproveOpname = () => {
		if ($("#kode_dokumen").val() == "") {
			message("Error!", "Gagal konfirmasi, silahkan pilih Kode Dokumen terlebih dahulu", "error")
			return false;
		}

		if ($("#status").val() == "In Progress Revision") {
			message("Error!", `Gagal konfirmasi, Opaname dengan kode <strong>${$("#kode_dokumen").children("option").filter(":selected").text()}</strong> masih dalam proses`, "error")
			return false;
		}

		Swal.fire({
			title: "Apakah anda yakin?",
			text: "Ingin konfirmasi data opname?",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Yakin",
			cancelButtonText: "Tidak, Tutup"
		}).then((result) => {
			if (result.value == true) {
				postDataRequest('<?= base_url('WMS/PersetujuanHasilStockOpname/konfirmasiDataApprovalOpname') ?>', {
						opnameId: $("#kode_dokumen").val(),
						last_update: $("#lastUpdate").val(),
						mode: $("#btnConfirmApprovalOpname").attr("mode")
					}, 'POST')
					.then((response) => {
						if (response == 203) {
							return messageNotSameLastUpdated();
						}

						if (response == true) {
							message_topright('success', 'Data berhasil dikonfirmasi');
							setTimeout(() => {
								Swal.fire({
									title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
									showConfirmButton: false,
									timer: 1000
								});
							}, 1000);

							resetUrlPushState();
							location.reload();

						}
						if (response == false) message_topright('error', 'Data gagal dikonfirmasi');
					})
			}
		});
	}

	// const handlerCetakDataApproveOpname = (event) => {
	// 	postDataRequest('<?= base_url('WMS/PersetujuanHasilStockOpname/cetakDataApproveOpname') ?>', {
	// 			value: event.currentTarget.getAttribute('data-id'),
	// 			type: null
	// 		}, 'POST')
	// 		.then((response) => {
	// 			console.log(response);
	// 		})
	// }

	/** For Compare */

	const handlerGetKodeDokumenArr = (value) => {
		postDataRequest('<?= base_url('WMS/PersetujuanHasilStockOpname/getKodeDokumenOpnameCompare') ?>', {
				value
			}, 'POST')
			.then((response) => {
				$("#kode_dokumen_compare").empty();
				$("#init-data-compare-list").hide('slow');
				disabledSaveWhenNotCheckedRevisi();
				$("#lengtDataCompare").val(0);
				if ($("#lengtDataCompare").val() == "0") {
					$("#btnSaveApprovalOpname").prop('disabled', true);
				}
				if (response.length > 0) {
					$.each(response, function(i, v) {
						$("#kode_dokumen_compare").append(`
                <option value="${v.id}">${v.kode}</option>
            `)
					})
				}

			}).catch((error) => {
				message('Error!', 'Error ' + error, 'error')
			})
	}

	const handlerCompare = () => {
		if ($("#kode_dokumen_compare").val() == null) {
			message("Error!", "Pilih kode dokumen terlebih dahulu!", "error")
			return false;
		}

		if ($("#kode_dokumen_compare").val().length < 2) {
			message("Error!", "Minimal 2 kode dokumen", "error")
			return false;
		}

		if ($("#kode_dokumen_compare").val().length > 3) {
			message("Error!", "Maksimal 3 kode dokumen", "error")
			return false;
		}

		postDataRequest('<?= base_url('WMS/PersetujuanHasilStockOpname/getCompareOpname') ?>', {
				opnameId: $("#kode_dokumen_compare").val()
			}, 'POST')
			.then((response) => {
				initDataCompareList(response);
				disabledSaveWhenNotCheckedRevisi()

			}).catch((error) => {
				message('Error!', 'Error ' + error, 'error')
			})
	}

	const initDataCompareList = (response) => {

		let html = "";
		$("#lengtDataCompare").val(Object.keys(response).length)

		$(`#init-data-compare-list`).empty();

		$.each(response, function(index, value) {

			html += `<div style="margin-top:10px;margin-bottom:10px">
            <div style="margin:10px 10px 10px 0px">
              <h4 style="display:inline;font-weight:700">Kode Pallet : ${index} | Lokasi : ${value.rak_lajur_detail_nama} | Area : ${value.depo_detail_nama}</h4>
            </div>`;
			html += `<div class="table-responsive">
            <table class="table table-striped table-bordered initDataDetailCompare" width="100%">
            <thead class="bg-primary">
                <tr class="text-center">
                  <td width="6%" rowspan="2" style="vertical-align: middle"><strong><label name="CAPTION-SKUKODE">Kode SKU</label></strong></td>
                  <td width="10%" rowspan="2" style="vertical-align: middle"><strong><label name="CAPTION-SKUNAMA">Nama SKU</label></strong></td>
                  <td width="8%" rowspan="2" style="vertical-align: middle"><strong><label name="CAPTION-KEMASAN">Kemasan</label></strong></td>
                  <td width="8%" rowspan="2" style="vertical-align: middle"><strong><label name="CAPTION-SKUSATUAN">Satuan</label></strong></td>
                  <td width="20%" colspan="3" style="vertical-align: middle"><strong>${value.namadokumen1}</strong></td>
                  <td width="20%" colspan="3" style="vertical-align: middle"><strong>${value.namadokumen2}</strong></td>`;
			if (value.namadokumen3 != null) {
				html += `<td width="20%" colspan="3" style="vertical-align: middle"><strong>${value.namadokumen3}</strong></td>`;
			}
			html += `<td width="8%" rowspan="2" style="vertical-align: middle"><strong><label>Status</label></strong></td>
               <td width="10%" colspan="2" style="vertical-align: middle"><strong>Final</strong></td>
                </tr>
                <tr class="text-center">
                  <td>Exp Date</td>
                  <td>Qty</td>
                  <td>Action</td>
                  <td>Exp Date</td>
                  <td>Qty</td>
                  <td>Action</td>`;
			if (value.namadokumen3 != null) {
				html += `<td>Exp Date</td>
                  <td>Qty</td>
                  <td>Action</td>`;
			}

			html += `     <td width="8%">Exp Date</td>
                    <td width="8%">Qty</td>
                  </tr>
                </thead>
            <tbody>`;

			$.each(value.data, function(i, v) {

				html += "<tr>";
				// if (i == 0) {
				//   html += `<td style="vertical-align: middle" class="text-center" rowspan="${value.data.length}">${value.rak_lajur_detail_nama}</td>
				//           <td style="vertical-align: middle" class="text-center" rowspan="${value.data.length}">${value.depo_detail_nama}</td>`;
				// }

				html += ` <td class="text-center">${v.sku_kode} <input type="hidden" value="${value.pallet_id}"/></td>
                      <td class="text-center">${v.sku_nama_produk} <input type="hidden" value="${value.rak_lajur_detail_id}"/></td>
                      <td class="text-center">${v.sku_kemasan} <input type="hidden" value="${v.sku_id}"/></td>
                      <td class="text-center">${v.sku_satuan} <input type="hidden" value="${v.sku_stock_id}"/></td>

                      <td class="text-center">${v.sku_expired_date1 == null ? '' : v.sku_expired_date1}</td>
                      <td class="text-center">${v.dokumen1 == null ? '' : v.dokumen1}</td>
                      <td class="text-center">
                          <input type="checkbox" id="chk1_${index.replaceAll("/", "")}_${i}" class="form-control" style="transform:scale(0.5)" onchange="handlerFinalCompare1(event, '${index}', '${i}', '${v.sku_expired_date1}', '${v.dokumen1}')"/>
                      </td>

                      <td class="text-center">${v.sku_expired_date2 == null ? '' : v.sku_expired_date2}</td>
                      <td class="text-center">${v.dokumen2 == null ? '' : v.dokumen2}</td>
                      <td class="text-center">
                          <input type="checkbox" id="chk2_${index.replaceAll("/", "")}_${i}" class="form-control" style="transform:scale(0.5)" onchange="handlerFinalCompare2(event, '${index}', '${i}', '${v.sku_expired_date2}', '${v.dokumen2}')"/>
                      </td>
                      
                      `;
				if (value.namadokumen3 != null) {
					html += `<td class="text-center">${v.sku_expired_date3 == null ? '' : v.sku_expired_date3}</td>`;
					html += `<td class="text-center">${v.dokumen3 == null ? '' : v.dokumen3}</td>`;
					html += `<td class="text-center"><input type="checkbox" id="chk3_${index.replaceAll("/", "")}_${i}" class="form-control" style="transform:scale(0.5)" onchange="handlerFinalCompare3(event, '${index}', '${i}', '${v.sku_expired_date3}', '${v.dokumen3}')"/></td>`;
				}
				html += `<td class="text-center">${v.status == "beda" ? '<span class="badge" style="display:inline;background:#dc2626;color:white">Beda</span>' : '<span class="badge" style="display:inline;background:#22c55e;color:white">Sama</span>'}</td>
                 <td class="text-center">
                    <input type="date" class="form-control expDate_" id="expDate_${index.replaceAll("/", "")}_${i}" style="width:125px" onchange="handlerEscapeExpDate('${index}', '${i}')"/>
                 </td>
                 <td class="text-center">
                    <input type="text" class="form-control numeric qtyCompare_" id="qtyCompare_${index.replaceAll("/", "")}_${i}" style="width:70px" onchange="handlerEscapeQtyCompare('${index}', '${i}')"/>
                 </td>
                </tr>`

			})

			html += `     </tbody>
            </table>
          </div>`;

			html += '</div>';
		})

		$(`#init-data-compare-list`).fadeOut("slow", function() {
			$(this).hide();
			Swal.fire({
				title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
				showConfirmButton: false,
				timer: 500
			});
		}).fadeIn("slow", function() {
			$(this).show();
			$(`#init-data-compare-list`).append(html)
			Translate();
		});

	}

	/** Checkbox Dokumen 1 or  First */

	const replaceCheckedFalse1 = (kodePallet, idx) => {
		$(`#chk2_${kodePallet.replaceAll("/", "")}_${idx}`).prop('checked', false)
		$(`#chk3_${kodePallet.replaceAll("/", "")}_${idx}`).prop('checked', false)

		$(`#expDate_${kodePallet.replaceAll("/", "")}_${idx}`).val("")
		$(`#qtyCompare_${kodePallet.replaceAll("/", "")}_${idx}`).val("")
	}

	const handlerFinalCompare1 = (event, kodePallet, idx, expDate, qty) => {
		if (event.currentTarget.checked) {
			if (expDate == 'null') {
				replaceCheckedFalse1(kodePallet, idx)
				$(`#chk1_${kodePallet.replaceAll("/", "")}_${idx}`).prop('checked', false)
				message('Error!', 'Exp Date kosong / null', 'error')
				return false
			}
			if (qty == 'null') {
				replaceCheckedFalse1(kodePallet, idx)
				$(`#chk1_${kodePallet.replaceAll("/", "")}_${idx}`).prop('checked', false)
				message('Error!', 'Qty kosong / null', 'error')
				return false
			}
			replaceCheckedFalse1(kodePallet, idx)

			$(`#expDate_${kodePallet.replaceAll("/", "")}_${idx}`).val(expDate)
			$(`#qtyCompare_${kodePallet.replaceAll("/", "")}_${idx}`).val(qty)
		} else {
			replaceCheckedFalse1(kodePallet, idx)
		}
	}

	/** End Checkbox Dokumen 1 or  First */

	/** Checkbox Dokumen 2 or  First */

	const replaceCheckedFalse2 = (kodePallet, idx) => {
		$(`#chk1_${kodePallet.replaceAll("/", "")}_${idx}`).prop('checked', false)
		$(`#chk3_${kodePallet.replaceAll("/", "")}_${idx}`).prop('checked', false)

		$(`#expDate_${kodePallet.replaceAll("/", "")}_${idx}`).val("")
		$(`#qtyCompare_${kodePallet.replaceAll("/", "")}_${idx}`).val("")
	}

	const handlerFinalCompare2 = (event, kodePallet, idx, expDate, qty) => {
		if (event.currentTarget.checked) {
			if (expDate == 'null') {
				replaceCheckedFalse2(kodePallet, idx)
				$(`#chk2_${kodePallet.replaceAll("/", "")}_${idx}`).prop('checked', false)
				message('Error!', 'Exp Date kosong / null', 'error')
				return false
			}
			if (qty == 'null') {
				replaceCheckedFalse2(kodePallet, idx)
				$(`#chk2_${kodePallet.replaceAll("/", "")}_${idx}`).prop('checked', false)
				message('Error!', 'Qty kosong / null', 'error')
				return false
			}

			replaceCheckedFalse2(kodePallet, idx)

			$(`#expDate_${kodePallet.replaceAll("/", "")}_${idx}`).val(expDate)
			$(`#qtyCompare_${kodePallet.replaceAll("/", "")}_${idx}`).val(qty)
		} else {
			replaceCheckedFalse2(kodePallet, idx)
		}
	}

	/** End Checkbox Dokumen 2 or  First */

	/** Checkbox Dokumen 3 or  First */

	const replaceCheckedFalse3 = (kodePallet, idx) => {
		$(`#chk1_${kodePallet.replaceAll("/", "")}_${idx}`).prop('checked', false)
		$(`#chk2_${kodePallet.replaceAll("/", "")}_${idx}`).prop('checked', false)

		$(`#expDate_${kodePallet.replaceAll("/", "")}_${idx}`).val("")
		$(`#qtyCompare_${kodePallet.replaceAll("/", "")}_${idx}`).val("")
	}

	const handlerFinalCompare3 = (event, kodePallet, idx, expDate, qty) => {
		if (event.currentTarget.checked) {
			if (expDate == 'null') {
				replaceCheckedFalse3(kodePallet, idx)
				$(`#chk3_${kodePallet.replaceAll("/", "")}_${idx}`).prop('checked', false)
				message('Error!', 'Exp Date kosong / null', 'error')
				return false
			}
			if (qty == 'null') {
				replaceCheckedFalse3(kodePallet, idx)
				$(`#chk3_${kodePallet.replaceAll("/", "")}_${idx}`).prop('checked', false)
				message('Error!', 'Qty kosong / null', 'error')
				return false
			}

			replaceCheckedFalse3(kodePallet, idx)

			$(`#expDate_${kodePallet.replaceAll("/", "")}_${idx}`).val(expDate)
			$(`#qtyCompare_${kodePallet.replaceAll("/", "")}_${idx}`).val(parseInt(qty))
		} else {
			replaceCheckedFalse3(kodePallet, idx)
		}
	}

	/** End Checkbox Dokumen 3 or  First */


	const handlerEscapeExpDate = (kodePallet, idx) => {
		$(`#chk1_${kodePallet.replaceAll("/", "")}_${idx}`).prop('checked', false)
		$(`#chk2_${kodePallet.replaceAll("/", "")}_${idx}`).prop('checked', false)
		$(`#chk3_${kodePallet.replaceAll("/", "")}_${idx}`).prop('checked', false)
	}

	const handlerEscapeQtyCompare = (kodePallet, idx) => {
		$(`#chk1_${kodePallet.replaceAll("/", "")}_${idx}`).prop('checked', false)
		$(`#chk2_${kodePallet.replaceAll("/", "")}_${idx}`).prop('checked', false)
		$(`#chk3_${kodePallet.replaceAll("/", "")}_${idx}`).prop('checked', false)
	}


	/** End For Compare */
</script>