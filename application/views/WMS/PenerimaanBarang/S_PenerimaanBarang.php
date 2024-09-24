<script type="text/javascript">
	let global_id = $("#global_id").val();
	let session_depo = $("#session_depo").val();
	let global_data = [];
	let global_arr_rak = [];
	let dataScanSku = [];
	let dataScanSkuTemp = [];
	let arrManeh = [];
	let arrPalletIsConfirm = [];
	const urlSearchParams = new URLSearchParams(window.location.search);
	const penerimaanBarangId = Object.fromEntries(urlSearchParams.entries()).id;
	const suratJalanId = Object.fromEntries(urlSearchParams.entries()).surat_jalan_id;
	const client_id = Object.fromEntries(urlSearchParams.entries()).client;
	const principle_id = Object.fromEntries(urlSearchParams.entries()).principle;

	const html5QrCode = new Html5Qrcode("preview_pallet");
	const html5QrCode2 = new Html5Qrcode("preview_sku");
	loadingBeforeReadyPage()
	$(document).ready(function() {
		select2();
		$(document).on("input", ".numeric", function(event) {
			this.value = this.value.replace(/[^0-9]/g, '');
		});
		// $("#simpan-data").attr("disabled", true);
		// getKodePenerimanSuratJalan();

		get_data_pallet();

		get_data_surat_jalan_detail(penerimaanBarangId);

		check_sisa();


		// check_terima();

		get_perusahaan()
		get_principle()

		$('#show_isi_sku_pallet').fadeOut("slow", function() {
			$(this).hide();
		});

		get_sku_in_pallet_temp();

		checkPalletIsreadyInPbd2();

		chkAndDisableButtonSave();
	});

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

	const select2 = () => {
		$(".select2").select2({
			width: "100%"
		});
	}

	const checkPalletIsreadyInPbd2 = () => {
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanBarang/checkPalletIsreadyInPbd2'); ?>",
			type: "POST",
			data: {
				penerimaanbarangId: penerimaanBarangId
			},
			dataType: "JSON",
			async: false,
			success: function(response) {

				arrPalletIsConfirm = [];

				$.each(response, function(i, v) {
					arrPalletIsConfirm.push({
						pallet_id: v.pallet_id,
						pallet_kode: v.pallet_kode
					});
				});
			}
		});
		// const url = "<?= base_url('WMS/PenerimaanBarang/checkPalletIsreadyInPbd2') ?>";

		// let postData = new FormData();
		// postData.append('penerimaanbarangId', penerimaanBarangId);

		// const request = await fetch(url, {
		//     method: 'POST',
		//     body: postData
		// });

		// const response = await request.json();

		// arrPalletIsConfirm = [];

		// $.each(response, function(i, v) {
		//     arrPalletIsConfirm.push({
		//         pallet_id: v.pallet_id,
		//         pallet_kode: v.pallet_kode
		//     });
		// });
	}

	const chkAndDisableButtonSave = () => {
		let lengthPallet = $("#data_pallet > tbody tr").length;
		let lengthPalleConfirm = arrPalletIsConfirm.length

		if (lengthPallet == 0) {
			//disable button
			$('#simpan-data').prop('disabled', true);
		} else {
			if (lengthPallet == lengthPalleConfirm) {
				$('#simpan-data').prop('disabled', false);
			} else {
				$('#simpan-data').prop('disabled', true);
			}
		}
	}

	function get_sku_in_pallet_temp() {
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanBarang/get_sku_in_pallet_temp'); ?>",
			type: "POST",
			data: {
				id: suratJalanId.split(','),
			},
			dataType: "JSON",
			success: function(data) {
				global_data = [];
				if (data != null) {
					$.each(data, (i, v) => {
						global_data.push({
							id: v.sku_id,
							ed: v.ed
						});
					})
				}
			}
		});
	}

	function get_perusahaan() {
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanBarang/get_perusahaan'); ?>",
			type: "POST",
			data: {
				id: client_id,
			},
			dataType: "JSON",
			success: function(data) {
				$("#perusahaan_filter_gudang").empty();
				$("#perusahaan_filter_gudang").append(`<option value="${data.id}">${data.nama}</option>`);
			}
		});
	}

	function get_principle() {
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanBarang/get_principle'); ?>",
			type: "POST",
			data: {
				id: principle_id,
			},
			dataType: "JSON",
			success: function(data) {
				$("#principle_filter_gudang").empty();
				$("#principle_filter_gudang").append(`<option value="${data.id}">${data.nama}</option>`);
			}
		});
	}

	// const global_pallet_id = [];

	function get_data_pallet() {
		let arr_pallet = [];
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanBarang/get_data_palet'); ?>",
			type: "POST",
			data: {
				id: suratJalanId.split(',')
			},
			dataType: "JSON",
			async: false,
			success: function(data) {
				$("#data_pallet > tbody").empty();
				const karyawanId = $("#KaryawanId").val();
				const levelKaryawan = $("#levelKaryawanLogin").val();
				if (data.data.length > 0) {
					$.each(data.data, function(i, v) {
						arr_pallet.push(v.id)
						// $("#global_pallet_id").attr();

						let disabled = "";
						let disabled2 = "";
						let cursor = "";
						let cursor2 = "";
						let bgStyle = "";
						let is_chosen = "";


						if (arrPalletIsConfirm.length != 0) {
							const findData = arrPalletIsConfirm.find((item) => item.pallet_id === v.id);
							if (typeof findData !== 'undefined') {
								disabled += "disabled";
								disabled2 += "";
								cursor2 += "";
								cursor += "no-drop";
								bgStyle += "background-color: #a5f3fc; color:#0f172a";
								// isScan++;
							} else {
								if (levelKaryawan == '0C2CC2B3-B26C-4249-88BE-77BD0BA61C41') {
									if (karyawanId != v.karyawan_id) {
										disabled += "disabled";
										cursor += "no-drop";
										disabled2 += "disabled";
										cursor2 += "no-drop";
									} else {
										disabled += "";
										cursor += "pointer";
										disabled2 += "";
										cursor2 += "pointer";
									}
								} else {
									disabled += "";
									cursor += "pointer";
									disabled2 += "";
									cursor2 += "pointer";
								}

								bgStyle += "";
							}
						} else {
							if (levelKaryawan == '0C2CC2B3-B26C-4249-88BE-77BD0BA61C41') {
								if (karyawanId != v.karyawan_id) {
									disabled += "disabled";
									cursor += "no-drop";
									disabled2 += "disabled";
									cursor2 += "no-drop";
								} else {
									disabled += "";
									cursor += "pointer";
									disabled2 += "";
									cursor2 += "pointer";
								}
							} else {
								disabled += "";
								cursor += "pointer";
								disabled2 += "";
								cursor2 += "pointer";
							}
							disabled += "";
							bgStyle += "";
						}


						if (v.flag == 1) {
							is_chosen += "<i class='fas fa-check text-success'></i>";
						} else {
							is_chosen += "<i class='fas fa-xmark text-danger'></i>";
						}
						$("#data_pallet > tbody").append(
							`
                            <tr style="${bgStyle}">
                                <td class="text-center">${i + 1}</td>
                                <td>
                                    <input type="text" class="form-control" value="${v.pallet_kode}" readonly/>
                                    <input type="hidden" class="form-control" name="id_pallet[]" id="id_pallet" value="${v.id}"/>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-primary chosen_warehouse" data-id="${v.id}" data-depo-detail-id="${v.depo_detail_id}" data-tipe-stock="${v.tipe_stock}" ${disabled}> <i class="fas fa-warehouse"></i> ${v.depo_detail_nama == null ? 'Pilih Lokasi' : v.depo_detail_nama}</button>
                                    ${is_chosen}
                                    <input type="hidden" class="form-control" name="gudang_area[]" id="gudang_area" value="${v.flag}"/>
                                </td>
								<td class="text-center">${v.pallet_who_create}</td>
                                <td class="text-center">
                                    <button type="button" data-toggle="tooltip" data-placement="top" title="hapus" data-id="` +
							v.id +
							`" data-counter="${i + 1}" class="delete" style="border:none;background:transparent" ${disabled}><i class="fas fa-trash text-danger" style="cursor: ${cursor}"></i></button>

                                    <button type="button" data-toggle="tooltip" data-placement="top" title="isi sku" data-id="` +
							v.id +
							`" class="isi_sku" data-counter="${i + 1}" style="border:none;background:transparent" ${disabled2}><i class="fas fa-plus text-primary" style="cursor: ${cursor2}"></i></button>

                                    <button type="button" data-toggle="tooltip" data-placement="top" title="konfirmasi pallet" data-id="` +
							v.id + `"  data-counter="${i + 1}" style="border:none;background:transparent" onclick="handlerKonfirmasipallet('${v.id}', '${v.flag}', '${v.karyawan_id}')" ${disabled}><i class="fas fa-check text-success" style="cursor: ${cursor}"></i></button>
                                </td>
                            </tr>
                        `);
					});

				}

				select2();

				let tot_pallet = $("#data_pallet > tbody tr").length;
				$("#data_pallet > tfoot tr #total_pallet").html("<strong><h4>Total " + tot_pallet +
					" Pallet</h4></strong>");
			}
		});

		$("#global_pallet_id").attr("data-pallet", JSON.stringify(arr_pallet));
	}

	$(document).on("click", ".chosen_warehouse", function() {
		let id = $(this).attr('data-id');
		let depo_detail_id = $(this).attr('data-depo-detail-id');
		let tipe_stock = $(this).attr('data-tipe-stock');
		$("#modal_pilih_gudang").modal('show');

		$(".btn_pilih_gudang").attr("data-id", id);
		$(".btn_pilih_gudang").attr("data-depo", depo_detail_id);
		$(".btn_pilih_gudang").attr("data-tipe-stock", tipe_stock);

		requestGetWidthAndLengthDepo(id, depo_detail_id, tipe_stock);
	});

	function requestGetWidthAndLengthDepo(id, depo_detail_id, tipe_stock) {
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanBarang/get_width_and_lenght_by_depo'); ?>",
			type: "POST",
			data: {
				id: session_depo,
			},
			dataType: "JSON",
			async: false,
			success: function(response) {
				ViewMapDepoDetailMenu(response, id, depo_detail_id, tipe_stock);
			}
		});
	}

	function ViewMapDepoDetailMenu(response, id, depo_detail_id, tipe_stock) {

		$("#divDepoDetail").html('');

		var strmenu = '';

		strmenu += '	<div id="Depo_Luas">';
		strmenu +=
			'	    <div style="width: 100%;border: solid white 1px; position: absolute;left:0;right:0;margin-left:auto;margin-right:auto;height: 600px;">';
		strmenu += '		    <div id="shape-placeholder">';
		strmenu += '		    </div>';
		strmenu += '		</div>';
		strmenu += '	</div>';

		$("#divDepoDetail").append(strmenu);

		// $("#shape-placeholder").attr('style', 'width: ' + response.depo_width + 'px; border: solid black 1px; position: absolute;left:0;right:0;margin-left:auto;margin-right:auto;');
		$("#Depo_Luas").attr('style', 'width: ' + response.depo_width + 'px; height: ' + response.depo_length + 'px;');
		// $("#Depo_Luas").attr('style', 'width: ' + response.depo_width + 'px; height: 450px;');

		GetDepoDetailMenu(id, depo_detail_id, tipe_stock);
	}

	function GetDepoDetailMenu(id, depo_detail_id, tipe_stock) {
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/PenerimaanBarang/GetDepoDetailMenu') ?>",
			data: {
				id: session_depo
			},
			dataType: "JSON",
			async: false,
			success: function(response) {
				if (response) {
					ChDepoDetailMenu(response, id, depo_detail_id, tipe_stock);
				}
			}
		});
	}

	function ChDepoDetailMenu(response, id, depo_detail_id, tipe_stock) {

		if (response.length > 0) {
			$.each(response, function(i, v) {
				let fontstyle = 'font-weight: bold;';
				let borderstyle = 'border: solid black 1px;';
				let css = 'width: ' + v.depo_detail_width + 'px; height: ' + v.depo_detail_length + 'px; color: ' +
					v.depo_detail_warna_font + '; ' + fontstyle + ' left: ' + v.depo_detail_x + 'px; top: ' + v
					.depo_detail_y + 'px; ';

				$("#shape-placeholder").append(`
                    <input class="checkbox-tools update_gudang_tujuan_by_id" type="radio" name="tools" data-depo-detail-id="${v.depo_detail_id}" data-tipe="${tipe_stock}" id="tool-${i}">
                    <label class="for-checkbox-tools" style="${css}" for="tool-${i}">
                        ${v.depo_detail_nama}
                    </label>
                `);
			});

			$("#tipe_filter_gudang").attr('data-dp', depo_detail_id);


			$(".checkbox-tools").attr('data-active', false);
			$(".checkbox-tools").prop('disabled', true);
		}
	}

	$(document).on("change", "#tipe_filter_gudang", function() {
		let tipe_stock = $(this).val();
		let dep = $(this).attr('data-dp');
		if (tipe_stock == "") {
			reset_information_gudang();

			$("#shape-placeholder :input[name='tools']").attr('checked', false);
			$(".checkbox-tools").attr('data-active', false);
			$(".checkbox-tools").prop('disabled', true);

			GetDataAreaByTipeStockFilter(tipe_stock, dep);
		} else {
			reset_information_gudang();
			// check_checked_gudang(row, depo_detail_id, id);
			GetDataAreaByTipeStockFilter(tipe_stock, dep);
		}
	});

	const GetDataAreaByTipeStockFilter = (tipe_stock, dep) => {
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanBarang/get_area_rak_gudang'); ?>",
			type: "POST",
			data: {
				tipe_stock: tipe_stock,
				client_wms: client_id,
				principle: principle_id,
				depo: session_depo
			},
			dataType: "JSON",
			success: function(response) {

				// $("#shape-placeholder :input[name='tools']").each(function(i, v) {
				//     let row = $(this);
				//     let id = row.attr('data-depo-detail-id');
				//     let tipe_stock_attr = row.attr('data-tipe');

				//     if ((dep == id) && (tipe_stock_filter.val() == tipe_stock_attr)) {
				//         row.attr('checked', true);
				//     } else {
				//         row.attr('checked', false);
				//     }
				// });

				if (response.length > 0) {
					$.each($(".checkbox-tools"), function(i, v) {
						let rowCheck = $(this);
						let depo_detail_id = rowCheck.attr('data-depo-detail-id');
						let tipe_stock_attr = rowCheck.attr('data-tipe');
						$.each(response, (index, value) => {
							if (value.depo_detail_id == depo_detail_id) {
								rowCheck.attr('data-active', true);
								rowCheck.prop('disabled', false);
								global_arr_rak.push({
									depo_detail_id: value.depo_detail_id,
									rak_id: value.rak_id
								});

								if ((dep == depo_detail_id) && (tipe_stock ==
										tipe_stock_attr)) {
									rowCheck.prop('checked', true);
								} else {
									rowCheck.prop('checked', false);
								}
								return false;
							} else {
								rowCheck.attr('data-active', false);
								rowCheck.prop('disabled', true);
							}
						});

					});
				} else {
					reset_information_gudang();
					$("#shape-placeholder :input[name='tools']").attr('checked', false);
					$(".checkbox-tools").attr('data-active', false);
					$(".checkbox-tools").prop('disabled', true);
				}
			}
		});
	}

	// function change_tipe_filter(row, depo_detail_id, id) {

	// }

	const check_checked_gudang = (row, depo_detail_id, id, tipe_stock_filter, tipe_stock) => {
		if ((row != null) && (depo_detail_id != null) && (id != null) && (tipe_stock_filter != null) && (tipe_stock !=
				null)) {
			if ((depo_detail_id == id) && (tipe_stock_filter == tipe_stock)) {
				row.attr('checked', true);
			}
		}
	}



	$(document).on("click", ".update_gudang_tujuan_by_id", function() {
		let tipe_stock = $("#tipe_filter_gudang").val();
		let depo_detail_id = $(this).attr('data-depo-detail-id');
		$(".btn_pilih_gudang").attr("data-depo-detail", depo_detail_id);
		const rak_id = global_arr_rak.find((value) => value.depo_detail_id == depo_detail_id);
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanBarang/get_data_rak_by_id'); ?>",
			type: "POST",
			data: {
				rak_id: rak_id.rak_id,
				tipe_stock: tipe_stock
			},
			dataType: "JSON",
			success: function(data) {
				reset_information_gudang();

				$("#lokasi_gudang").html(data.depo_detail_nama);
				$("#pt_gudang").html(data.client_wms_nama);
				$("#principle_gudang").html(data.principle_nama);
				$("#tipe_stock_gudang").html(data.rak_lajur_detail_tipe_stock);
				$("#jumlah_rak_gudang").html(data.jumlah_rak);
				$("#jumlah_rak_terisi_gudang").html(data.jumlah_terisi);
				$("#jumlah_rak_kosong_gudang").html(data.jumlah_kosong);
			}
		});
	});

	const reset_information_gudang = () => {
		$("#lokasi_gudang").html('');
		$("#pt_gudang").html('');
		$("#principle_gudang").html('');
		$("#tipe_stock_gudang").html('');
		$("#jumlah_rak_gudang").html('');
		$("#jumlah_rak_terisi_gudang").html('');
		$("#jumlah_rak_kosong_gudang").html('');
	}

	$(document).on("click", ".btn_pilih_gudang", function() {
		let id = $(this).attr('data-id');
		let depo = $(this).attr('data-depo');
		let depo_detail_id = $(this).attr('data-depo-detail');
		let tipe_stock = $("#tipe_filter_gudang").val();
		const lastUpdated = $("#lastUpdated").val();
		let chk = $("#shape-placeholder :input[name='tools']:checked").length;
		if (chk == 0) {
			message('Error!', 'Tidak ada gudang yg dipilih, silahkan pilih terlebih dahulu!', 'error');
			return false;
		} else {

			requestAjax("<?= base_url('WMS/PenerimaanBarang/update_gudang_tujuan_by_id'); ?>", {
				id: id,
				gudang_tujuan_id: depo_detail_id,
				tipe_stock: tipe_stock,
				penerimaanBarangId,
				lastUpdated
			}, "POST", "JSON", function(response) {
				if (response.status === 200) {
					$("#lastUpdated").val(response.lastUpdatedNew);
					requestGetWidthAndLengthDepo(id, depo);

					$('#data_pallet').fadeOut("slow", function() {
						$(this).hide();
					}).fadeIn("slow", function() {
						$(this).show();
						get_data_pallet();
					});

					message_topright('success', 'Gudang berhasil dipilih');
					$("#modal_pilih_gudang").modal('hide');
					reset_information_gudang();
					$("#tipe_filter_gudang").val("").trigger('change');
				}
				if (response.status === 400) return messageNotSameLastUpdated()
				if (response.status === 401) return message_topright('error', 'something wrong')
			})

			// $.ajax({
			// 	url: "<?= base_url('WMS/PenerimaanBarang/update_gudang_tujuan_by_id'); ?>",
			// 	type: "POST",
			// 	data: {
			// 		id: id,
			// 		gudang_tujuan_id: depo_detail_id,
			// 		tipe_stock: tipe_stock
			// 	},
			// 	dataType: "JSON",
			// 	success: function(data) {

			// 	}
			// });


		}
	});

	$(document).on("click", ".btn_close_list_pilih_gudang", function() {
		$("#modal_pilih_gudang").modal('hide');
		reset_information_gudang();
		$("#tipe_filter_gudang").val("").trigger('change');

	});

	let counter = 0;
	$(document).on("click", ".isi_sku", function() {
		let id = $(this).attr('data-id');
		let is_count = $(this).attr('data-counter');
		counter++;
		if (counter == 1) {
			show_isi_sku(id, is_count);
		} else if (counter > 1) {
			show_isi_sku(id, is_count);
			// let count_in = 0;
			// $("#list_detail_pallet > tbody tr").each(function(i, v) {
			//     let first_element = $(this).find("td:eq(0)");
			//     if (first_element.text() != "Data Kosong") {
			//         let ed = $(this).find("td:eq(6) input[type='date']");
			//         let qty = $(this).find("td:eq(10) input[type='text']");
			//         if (ed.val() == "") {
			//             message('Error!', 'Silahkan isi Expired Date terlebih dahulu jika ingin pindah isi pallet', 'error');
			//             return false;
			//         } else if (qty.val() == 0) {
			//             message('Error!', 'Silahkan isi Jumlah barang terlebih dahulu jika ingin pindah isi pallet', 'error');
			//             return false;
			//         } else {
			//             count_in = 1;
			//         }
			//     } else {
			//         count_in = 1;
			//     }
			// });

			// if (count_in == 1) {
			//     show_isi_sku(id, is_count);
			// }
		} else {
			show_isi_sku(id, is_count);
		}
	});

	function show_isi_sku(id, is_count) {
		$('#show_isi_sku_pallet').fadeOut("slow", function() {
			$(this).hide();
			$("#list_detail_pallet > tbody").html(
				`<tr><td colspan="12" class="text-center"><span ><i class="fa fa-spinner fa-spin"></i> Loading...</span></td></tr>`
			);
		}).fadeIn("slow", function() {
			$(this).show();
			append_list_data_setelah_pilih_sku(id, is_count);
			count_tot_detail_pallet();
		});

		if (arrPalletIsConfirm.length != 0) {
			const findData = arrPalletIsConfirm.find((item) => item.pallet_id === id);
			if (typeof findData !== 'undefined') {
				$("#add-sku").prop('disabled', true);
			} else {
				$("#add-sku").prop('disabled', false);
			}
		} else {
			$("#add-sku").prop('disabled', false);
		}

		$("#add-sku").attr('data-pallet-id', id);
		$(".btn_pilih_sku").attr("data-counter", is_count);
	}

	$(document).on("click", "#add-sku", function() {
		let id = $(this).attr('data-pallet-id');
		$("#modal_pilih_sku").modal("show");
		$(".btn_pilih_sku").attr("data-pallet-id", id);
	});

	const handleChangeListSKU = (value) => {

		const type = value === 0 ? "pallet" : value === 1 ? "sku" : "";
		const checkboxes = $(`input[name='chk-data-${type}[]']`);

		if (value == 0) {

			$("#showTableListPilihSku").show();
			$("#showScanbarcodeSKU").hide();
			$(".btn_pilih_sku").attr("data-type", "pallet");

			for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].type == 'checkbox' && !(checkboxes[i].disabled)) {
					checkboxes[i].checked = false;
				}
			}


			if ($.fn.DataTable.isDataTable('#table_list_pilih_sku')) {
				$('#table_list_pilih_sku').DataTable().destroy();
			}
			$("#table_list_pilih_sku > tbody").empty();
			$("#tablelistdatadetailsj > tbody tr").each(function(i, v) {
				let currentRow = $(this);
				// let sjd_id = currentRow.find("td:eq(0) input[type='hidden']").val();
				let sku_id = currentRow.find("td:eq(1) input[type='hidden']").val();
				let sku_kode = currentRow.find("td:eq(1)").text();
				let sku = currentRow.find("td:eq(2)").text();
				let kemasan = currentRow.find("td:eq(3)").text();
				let satuan = currentRow.find("td:eq(4)").text();
				let batch_no = currentRow.find("td:eq(5)").text();
				let ed = currentRow.find("td:eq(6)").text();
				let jumlah_barang = currentRow.find("td:eq(7)").text();
				let terima = currentRow.find("td:eq(8)").text();
				let sisa = currentRow.find("td:eq(9)").text();
				// let readonly = "";
				// if (sisa == 0) {
				//     readonly += "disabled checked";
				// } else {
				//     readonly += "";
				// }
				$("#table_list_pilih_sku > tbody").append(`
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" class="form-control check-item" name="chk-data-pallet[]" style="transform: scale(1.5)" id="chk-data-pallet[]" value="${sku_id},${ed}, ${jumlah_barang}, ${batch_no}"/>
                        </td>
                        <td>${sku_kode}</td>
                        <td>${sku}</td>
                        <td class="text-center">${kemasan}</td>
                        <td class="text-center">${satuan}</td>
                        <td class="text-center">${batch_no}</td>
                        <td class="text-center">${ed}</td>
                        <td class="text-center">${jumlah_barang}</td>
                        <td class="text-center">${sisa}</td>
                    </tr>
                `);
			});

			$('#table_list_pilih_sku').DataTable({
				columnDefs: [{
					sortable: false,
					targets: [0, 1, 2, 3, 4, 5, 6, 7, 8]
				}],
				lengthMenu: [
					[-1],
					['All']
				],
			});
		}

		if (value == 1) {
			$("#showTableListPilihSku").hide();
			$("#showScanbarcodeSKU").show();

			$(".btn_pilih_sku").attr("data-type", "sku");
		}

		if (value == 2) {
			$("#showTableListPilihSku").hide();
			$("#showScanbarcodeSKU").hide();
			$("#showListDataSku").prop('checked', false);
			$("#showbarcodeListSKu").prop('checked', false);
		}
	}

	const closeCanceKonversi = () => {
		$("#modal_konversi").modal('hide');
		$("#tableKonversiHasil > tbody").empty();
	}

	$(document).on("click", ".btn_close_list_pilih_sku", function() {
		$("#modal_pilih_sku").modal("hide");
		$("#showTableListPilihSku").hide();
		$("#showScanbarcodeSKU").hide();
		$("#showListDataSku").prop('checked', false);
		$("#showbarcodeListSKu").prop('checked', false);
	});

	$(document).on("click", ".delete", function() {
		let id = $(this).attr('data-id');
		const lastUpdated = $("#lastUpdated").val();
		let is_count = $(this).attr('data-counter') == 1 ? 1 : $(this).attr('data-counter') - 1;
		let delete_row = $(this).parent().parent();

		messageBoxBeforeRequest('Jika menghapus data ini, maka data yang berkaitan dengan data ini akan terhapus',
			'Ya, Hapus', 'Tidak, Tutup').then((result) => {
			if (result.value == true) {
				requestAjax("<?= base_url('WMS/PenerimaanBarang/delete_pallet'); ?>", {
					id: id,
					penerimaanBarangId,
					lastUpdated
				}, "POST", "JSON", function(response) {

					if (response.status === 200) {
						$("#lastUpdated").val(response.lastUpdatedNew);
						message_topright('success', response.message);
						delete_row.remove();
						get_data_pallet();
						get_data_surat_jalan_detail(penerimaanBarangId);
						chkAndDisableButtonSave()
						checkPalletIsreadyInPbd2();
						counter--;
						let count_pallet = $("#data_pallet > tbody tr").length;
						if (count_pallet != 0) {
							$('#show_isi_sku_pallet').fadeOut("slow", function() {
								$(this).hide();
							}).fadeIn("slow", function() {
								$(this).show();
								let idx = $("#data_pallet > tbody tr").length - 1;
								let pallet = $(".isi_sku").eq(idx).attr('data-id');
								show_isi_sku(pallet, is_count);
								get_sku_in_pallet_temp();

							});
						} else {
							$('#show_isi_sku_pallet').fadeOut("slow", function() {
								$(this).hide();
							});
						}
					}
					if (response.status === 400) return messageNotSameLastUpdated()
					if (response.status === 401) return message_topright('error', response.message)
				}, ".delete")
			}
		});

		// Swal.fire({
		// 	title: "Apakah anda yakin?",
		// 	text: "Jika menghapus data ini, maka data yang berkaitan dengan data ini akan terhapus",
		// 	icon: "warning",
		// 	showCancelButton: true,
		// 	confirmButtonColor: "#3085d6",
		// 	cancelButtonColor: "#d33",
		// 	confirmButtonText: "Ya, Hapus",
		// 	cancelButtonText: "Tidak, Tutup"
		// }).then((result) => {
		// 	if (result.value == true) {
		// 		$.ajax({
		// 			url: "<?= base_url('WMS/PenerimaanBarang/delete_pallet'); ?>",
		// 			type: "POST",
		// 			data: {
		// 				id: id
		// 			},
		// 			dataType: "JSON",
		// 			success: function(data) {
		// 				if (data.status == true) {
		// 					message_topright('error', data.message);
		// 				} else {
		// 					message_topright('success', data.message);
		// 					delete_row.remove();
		// 					get_data_pallet();
		// 					get_data_surat_jalan_detail(penerimaanBarangId);
		// 					chkAndDisableButtonSave()
		// 					checkPalletIsreadyInPbd2();
		// 					counter--;
		// 					let count_pallet = $("#data_pallet > tbody tr").length;
		// 					if (count_pallet != 0) {
		// 						$('#show_isi_sku_pallet').fadeOut("slow", function() {
		// 							$(this).hide();
		// 						}).fadeIn("slow", function() {
		// 							$(this).show();
		// 							let idx = $("#data_pallet > tbody tr").length - 1;
		// 							let pallet = $(".isi_sku").eq(idx).attr('data-id');
		// 							show_isi_sku(pallet, is_count);
		// 							get_sku_in_pallet_temp();

		// 						});
		// 					} else {
		// 						$('#show_isi_sku_pallet').fadeOut("slow", function() {
		// 							$(this).hide();
		// 						});
		// 					}

		// 				}
		// 			}
		// 		});
		// 	}
		// });
	});

	function checkAllSKU(e, type) {
		var checkboxes = $(`input[name='chk-data-${type}[]']`);
		if (e.checked) {
			for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].type == 'checkbox' && !(checkboxes[i].disabled)) {
					checkboxes[i].checked = true;
				}
			}
		} else {
			for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].type == 'checkbox' && !(checkboxes[i].disabled)) {
					checkboxes[i].checked = false;
				}
			}
		}
	}

	$(document).on("click", ".btn_pilih_sku", function() {
		let pallet_id = $(this).attr('data-pallet-id');
		let is_count = $(this).attr('data-counter');
		let type = $(this).attr('data-type');
		let arr_chk = [];
		var checkboxes = $(`input[name='chk-data-${type}[]']`);
		for (var i = 0; i < checkboxes.length; i++) {
			if (checkboxes[i].checked == true && !(checkboxes[i].disabled)) {
				let data = checkboxes[i].value.split(',');
				arr_chk.push({
					sku_id: data[0],
					ed: data[1],
					jumlah_barang: data[2].replace(/\s/g, ''),
					batch_no: data[3] == "null" ? null : data[3].replace(/\s/g, '')
				});

			}
		}

		if (arr_chk.length == 0) {
			message("Info!", "Pilih data yang akan dipilih", "info");
		} else {
			$.ajax({
				url: "<?= base_url('WMS/PenerimaanBarang/save_data_to_pallet_detail_temp') ?>",
				type: "POST",
				data: {
					data: arr_chk,
					sj_id: suratJalanId.split(','),
					penerimaanBarangId,
					pallet_id: pallet_id,
				},
				dataType: "JSON",
				success: function(data) {
					if (data == true) {
						append_list_data_setelah_pilih_sku(pallet_id, is_count);
						get_sku_in_pallet_temp();
						$("#modal_pilih_sku").modal("hide");

						for (var i = 0; i < checkboxes.length; i++) {
							if (checkboxes[i].type == 'checkbox' && !(checkboxes[i].disabled)) {
								checkboxes[i].checked = false;
							}
						}

						handleChangeListSKU(2);
					}
				}
			});
		}
	});


	function append_list_data_setelah_pilih_sku(pallet_id, is_count) {
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanBarang/get_surat_jalan_detail_by_arrId'); ?>",
			type: "POST",
			data: {
				id: pallet_id
			},
			dataType: "JSON",
			async: false,
			success: function(data) {
				$(".detail_pallet_ke").empty();
				$(".detail_pallet_ke").append(`Pallet urutan ke ${is_count}`);
				$("#list_detail_pallet > tbody").empty();


				let disabled = "";
				let cursor = "";
				let bgStyle = "";

				if (arrPalletIsConfirm.length != 0) {
					const findData = arrPalletIsConfirm.find((item) => item.pallet_id === pallet_id);
					if (typeof findData !== 'undefined') {
						disabled += "disabled";
						cursor += "no-drop";
						bgStyle += "background-color: #a5f3fc; color:#0f172a";
						// isScan++;
					} else {
						disabled += "";
						cursor += "";
						bgStyle += "";
					}
				} else {
					disabled += "";
					cursor += "";
					bgStyle += "";
				}

				if (data.length != 0) {
					$.each(data, function(i, v) {
						let expired_date = v.expired_date == null ? v.sku_exp_date : v.expired_date;
						let qty = v.qty;

						let jmlh_barang = ""
						let jmlh_sisa = ""

						$("#tablelistdatadetailsj > tbody tr").each(function() {
							let sku_id = $(this).find("td:eq(1) input[type='hidden']").val();
							let ed = $(this).find("td:eq(6)").text();
							let jml_barang_table = $(this).find("td:eq(7)").text();
							let jml_terima_table = $(this).find("td:eq(8)").text();
							let jml_sisa_table = $(this).find("td:eq(9)").text();

							if ((v.sku_id === sku_id) && (v.sku_exp_date === ed) && (v
									.jumlah_barang == jml_barang_table)) {
								jmlh_barang = jml_barang_table
								jmlh_sisa = jml_sisa_table
							}

						});



						$("#list_detail_pallet > tbody").append(
							`
                            <tr style="${bgStyle}">
                                <td>${v.principle} <input type="hidden" class="form-control" name="pallet_detail_id" id="pallet_detail_id" value="` +
							v
							.pallet_detail_id + `"/></td>
                                <td>${v.sku_kode}</td>
                                <td>${v.sku_nama_produk}</td>
                                <td class="text-center">${v.sku_kemasan}</td>
                                <td class="text-center">${v.sku_satuan}</td>
                                <td>
                                    <input type="date" class="form-control exp_date_sj" id="exp_date_sj" name="exp_date_sj" value="${v.sku_exp_date}" disabled/>
                                </td>
                                <td>
                                    <input type="date" class="form-control exp_date" id="exp_date" name="exp_date" value="${expired_date}" ${disabled} onkeydown="return false;"/>
                                </td>
                                <td class="text-center">${jmlh_barang}</td>
                                <td class="text-center">${qty}</td>
                                <td class="text-center">${jmlh_sisa}</td>
                                <td>
                                    <input type="text" class="form-control qty_detail_pallet numeric" id="qty_detail_pallet" name="qty_detail_pallet" value="${qty}" onchange="UpdateQtySku('${pallet_id}','${v.pallet_detail_id}','${v.sku_id}', '${v.sku_exp_date}', '${is_count}', '${v.batch_no}',this)" ${disabled}/>
                                </td>
                                <td class="text-center">
                                    <button type="button" data-toggle="tooltip" data-placement="top" data-pallet-id="${pallet_id}" data-counter="${is_count}" data-id="${v.pallet_detail_id}" title="hapus" class="delete_detail" style="border:none;background:transparent" ${disabled}><i class="fas fa-trash text-danger" style="cursor: ${cursor}"></i></button> 
                                </td>
                            </tr>
                        `);

					});
				} else {
					$("#list_detail_pallet > tbody").html(
						`<tr><td colspan="12" class="text-center text-danger">Data Kosong</td></tr>`);
				}
				select2();
				count_tot_detail_pallet();

				var dtToday = new Date();

				var month = dtToday.getMonth() + 1;
				var day = dtToday.getDate();
				var year = dtToday.getFullYear();
				if (month < 10)
					month = '0' + month.toString();
				if (day < 10)
					day = '0' + day.toString();

				var maxDate = year + '-' + month + '-' + day;
				$('.exp_date').attr('min', maxDate);

				update_pallet_detail_by_id();
			}
		});
	}

	const handleChangeActualExpDate = async (value, sku_id, ed_awal) => {
		const url = "<?= base_url('WMS/PenerimaanBarang/checkMinimunExpiredDate') ?>";

		let postData = new FormData();
		postData.append('ed_request', value);
		postData.append('sku_id', sku_id);

		const request = await fetch(url, {
			method: 'POST',
			body: postData
		});

		const response = await request.json();

		if (response.type === 201) {
			message('Error!', response.message, 'error');
			$('.exp_date').val(ed_awal);
		}
	}

	function count_tot_detail_pallet() {
		let no = 0;
		$("#list_detail_pallet > tbody tr").each(function(i, v) {
			let first_element = $(this).find("td:eq(0)");
			if (first_element.text() != "Data Kosong") {
				no++;
			} else {
				no = 0;
			}
		});
		if (no > 0) {
			$(".span-example").show('slow');
		} else {
			$(".span-example").hide('slow');
		}
		$("#list_detail_pallet > tfoot tr #total_detail_pallet").html("<strong><h4>Total " + no + " SKU</h4></strong>");
	}

	function UpdateQtySku(pallet_id, pallet_detail_id, sku_id, sku_exp_date, is_count, batch_no, qty) {
		ajax_request_update_qty_sku(pallet_id, pallet_detail_id, sku_id, sku_exp_date, is_count, batch_no, qty);
	}

	function update_pallet_detail_by_id() {
		$("#list_detail_pallet > tbody tr").each(function(i, v) {
			let currentRow = $(this);
			let id = currentRow.find("td:eq(0) input[type='hidden']").val();
			let exp_date = currentRow.find("td:eq(6) input[type='date']");
			// let tipe = currentRow.find("td:eq(7) select");
			let qty = currentRow.find("td:eq(10) input[type='text']");

			//check data ed in pallet detail temp
			requestAjax("<?= base_url('WMS/PenerimaanBarang/check_data_ed_in_pallet_detail_temp'); ?>", {
				id: id
			}, "POST", "JSON", function(data) {
				if (data != null) {
					if (data.ed == null) {
						requestAjax(
							"<?= base_url('WMS/PenerimaanBarang/update_ed_pallet_detail_by_id'); ?>", {
								id: data.id,
								ed: exp_date.val(),
							}, "POST", "JSON",
							function(response) {
								get_sku_in_pallet_temp();
							})
					} else {
						//update expired date di tabel pallet_detail_temp
						exp_date.change(function() {
							let exp_date_val = $(this).val();
							requestAjax(
								"<?= base_url('WMS/PenerimaanBarang/update_ed_pallet_detail_by_id'); ?>", {
									id: data.id,
									ed: exp_date_val,
								}, "POST", "JSON",
								function(response) {
									get_sku_in_pallet_temp();
								})
						});
					}
				}
			})

			// $.ajax({
			// 	url: "<?= base_url('WMS/PenerimaanBarang/check_data_ed_in_pallet_detail_temp'); ?>",
			// 	type: "POST",
			// 	data: {
			// 		id: id
			// 	},
			// 	dataType: "JSON",
			// 	success: function(data) {
			// 		if (data != null) {
			// 			if (data.ed == null) {
			// 				$.ajax({
			// 					url: "<?= base_url('WMS/PenerimaanBarang/update_ed_pallet_detail_by_id'); ?>",
			// 					type: "POST",
			// 					data: {
			// 						id: data.id,
			// 						ed: exp_date.val()
			// 					},
			// 					dataType: "JSON",
			// 					success: function(data) {
			// 						get_sku_in_pallet_temp();
			// 					}
			// 				});
			// 			} else {
			// 				//update expired date di tabel pallet_detail_temp
			// 				exp_date.change(function() {
			// 					let exp_date_val = $(this).val();
			// 					$.ajax({
			// 						url: "<?= base_url('WMS/PenerimaanBarang/update_ed_pallet_detail_by_id'); ?>",
			// 						type: "POST",
			// 						data: {
			// 							id: data.id,
			// 							ed: exp_date_val
			// 						},
			// 						dataType: "JSON",
			// 						success: function(data) {
			// 							get_sku_in_pallet_temp();
			// 						}
			// 					});
			// 				});
			// 			}
			// 		}
			// 	}
			// });

			//update penerimaan tipe id di tabel pallet_detail_temp
			// tipe.change(function() {
			//     let tipe_id = $(this).children("option").filter(":selected").val();
			//     $.ajax({
			//         url: "<?= base_url('WMS/PenerimaanBarang/update_tipe_pallet_detail_by_id'); ?>",
			//         type: "POST",
			//         data: {
			//             id: id,
			//             tipe_id: tipe_id
			//         },
			//         dataType: "JSON",
			//         success: function(data) {
			//             get_sku_in_pallet_temp();
			//         }
			//     });
			// });
		});
	}

	$(document).on("click", ".delete_detail", function() {
		let pallet_id = $(this).attr('data-pallet-id');
		let is_count = $(this).attr('data-counter');
		let id = $(this).attr('data-id');
		Swal.fire({
			title: "Apakah anda yakin?",
			text: "Ingin delete data detail pallet!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Hapus",
			cancelButtonText: "Tidak, Tutup"
		}).then((result) => {
			if (result.value == true) {
				$.ajax({
					url: "<?= base_url('WMS/PenerimaanBarang/delete_pallet_detail'); ?>",
					type: "POST",
					data: {
						id: id
					},
					dataType: "JSON",
					success: function(data) {
						if (data.status == true) {
							message_topright('error', data.message);
						} else {
							message_topright('success', data.message);
							$(this).parent().parent().remove();
							append_list_data_setelah_pilih_sku(pallet_id, is_count);
							get_sku_in_pallet_temp();
							$('#showfilterdata').fadeOut("slow", function() {
								$(this).hide();
								$("#tablelistdatadetailsj > tbody").html(
									`<tr><td colspan="10" class="text-center"><span ><i class="fa fa-spinner fa-spin"></i> Loading...</span></td></tr>`
								);
							}).fadeIn("slow", function() {
								$(this).show();
								get_data_surat_jalan_detail(penerimaanBarangId)
							});
						}
					}
				});
			}
		});
	});

	// function count_pallet() {
	//     let count_pallet = $("#data_pallet > tbody tr").length;
	//     if (count_pallet == 0) {
	//         //hapus readonly perusahaan dan principle
	//         $("#doc_fob").removeAttr("disabled");
	//     } else {
	//         $("#doc_fob").attr("disabled", "disabled");
	//     }
	// }

	function get_data_surat_jalan_detail(penerimaanBarangId) {
		let pallet_id = $("#global_pallet_id").attr('data-pallet');
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanBarang/get_surat_jalan_detail') ?>",
			type: "POST",
			data: {
				penerimaanBarangId,
				pallet_id: JSON.parse(pallet_id)
			},
			dataType: "JSON",
			async: false,
			beforeSend: function() {
				$("#tablelistdatadetailsj > tbody").html(
					`<tr><td colspan="10" class="text-center"><span ><i class="fa fa-spinner fa-spin"></i> Loading...</span></td></tr>`
				);
			},
			success: function(data) {
				$("#showfilterdata").show();
				if (data.datadetail.length > 0) {
					$("#showAlertNoAccess").hide();
					$("#tambah_pallet").show();
					if ($.fn.DataTable.isDataTable('#tablelistdatadetailsj')) {
						$('#tablelistdatadetailsj').DataTable().destroy();
					}
					$('#tablelistdatadetailsj > tbody').empty();
					dataScanSku = [];
					let html = "";
					$.each(data.datadetail, function(i, v) {
						dataScanSku.push(v);

						html += `<tr>
														<td class="text-center">${i + 1}</td>
														<td class="text-center">${v.sku_kode} <input type="hidden" class="form-control" name="sku_id[]" value="${v.sku_id}"/></td>
														<td>${v.sku_nama_produk}</td>
														<td class="text-center">${v.sku_kemasan}</td>
														<td class="text-center">${v.sku_satuan}</td>
														<td class="text-center">${v.batch_no == null ? '' : v.batch_no}</td>
														<td class="text-center">${v.sj_ed}</td>
														<td class="text-center">${v.sjd_jumlah_barang}</td>
														<td class="text-center">${v.jumlah_terima}</td>
														<td class="text-center">${v.sisa}</td>
												</tr>`;
					});

					$("#tablelistdatadetailsj > tbody").append(html)

					select2()
				} else {
					$("#tablelistdatadetailsj > tbody").html(
						`<tr><td colspan="10" class="text-center text-danger">Data Kosong</td></tr>`);

					const levelKaryawan = $("#levelKaryawanLogin").val();
					console.log('level', levelKaryawan);
					if (levelKaryawan == "0C2CC2B3-B26C-4249-88BE-77BD0BA61C41") {
						$("#showAlertNoAccess").show();
						$("#tambah_pallet").hide();
					}

				}

				$('#tablelistdatadetailsj').DataTable({
					columnDefs: [{
						sortable: false,
						targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
					}],
					"searching": false,
					lengthMenu: [
						[-1],
						['All']
					],
				});

				//cek jika sisa masih ada maka tidak bisa di simpan
				check_sisa();
			}
		});


	}

	const handlerUpdateCheckerBySku = (id, value) => {
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanBarang/updateCheckerBySku'); ?>",
			type: "POST",
			data: {
				id,
				value
			},
			dataType: "JSON",
			success: function(response) {}
		});
	}

	function check_sisa() {
		let sisa_temp = 0;
		$("#tablelistdatadetailsj > tbody tr").each(function(i, v) {
			let sisa = $(this).find("td:eq(9)").text();
			sisa_temp += parseInt(sisa);
		});
		if (sisa_temp > 0) {
			// $("#simpan-data").attr("disabled", "disabled");
			$("#add-row").removeAttr("disabled");
		} else {
			// $("#simpan-data").removeAttr("disabled");
			$("#add-row").attr("disabled", "disabled");
		}
	}

	// function check_terima() {
	//     let terima_temp = 0;
	//     $("#tablelistdatadetailsj > tbody tr").each(function() {
	//         let terima = $(this).find("td:eq(7)").text();
	//         terima_temp += parseInt(terima);
	//     });

	//     // console.log(terima_temp);
	//     if (terima_temp > 0) {
	//         // $("#simpan-data").attr("disabled", "disabled");
	//         $("#tambah_pallet").click();
	//         setTimeout(() => {
	//             $(".isi_sku").first().click();
	//         }, 1000);
	//     }
	// }

	let counter_add_pallet = 0;
	$(document).on("click", "#tambah_pallet", function() {
		counter_add_pallet++;
		if (counter_add_pallet == 1) {
			$('#show_add_pallet').fadeOut("slow", function() {
				$(this).hide();
				$('#show_isi_sku_pallet').hide();
				$("#data_pallet > tbody").html(
					`<tr><td colspan="5" class="text-center"><span ><i class="fa fa-spinner fa-spin"></i> Loading...</span></td></tr>`
				);
			}).fadeIn("slow", function() {
				$(this).show();
				setTimeout(() => {
					$(this)[0].scrollIntoView({
						behavior: "smooth",
					});
				}, 500);
				get_data_pallet();
			});
			$(this).attr("disabled", true);
		}


	});

	function handleCheckScan(type, e) {
		if (e.target.checked) {
			$(`#start_scan_${type}`).hide();
			$(`#input_manual_${type}`).show();
			$(`#stop_scan_${type}`).hide();
			$(`#preview_${type}`).hide();
			$(`#txtpreviewscan_${type}`).val("");
			$(`#select_kamera_${type}`).empty();
		} else {
			$(`#start_scan_${type}`).show();
			$(`#input_manual_${type}`).hide();
			$(`#close_input_${type}`).hide();
			$(`#preview_input_manual_${type}`).hide();
			$(`#kode_barcode_${type}`).val("");
			$(`#txtpreviewscan_${type}`).val("");
		}
	}

	function handleStartScan(type) {
		$(`#start_scan_${type}`).hide();
		$(`#stop_scan_${type}`).show();

		let chkTypeScan = type === "pallet" ? html5QrCode : html5QrCode2

		Swal.fire({
			title: '<span ><i class="fa fa-spinner fa-spin"></i> Memuat Kamera</span>',
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
				$(`#preview_${type}`).show();

				//handle succes scan
				const qrCodeSuccessCallback = (decodedText, decodedResult) => {
					let temp = decodedText;
					if (temp != "") {

						chkTypeScan.pause()
						requestScan(type, decodedText, 'scan')
					}
				};

				// atur kotak nng kini, set 0.sekian pngin jlok brpa persen
				const qrboxFunction = function(viewfinderWidth, viewfinderHeight) {
					let minEdgePercentage = 0.9; // 90%
					let minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
					let qrboxSize = Math.floor(minEdgeSize * minEdgePercentage);
					return {
						width: qrboxSize,
						height: qrboxSize
					};
				}

				const config = {
					fps: 10,
					qrbox: qrboxFunction,
					// rememberLastUsedCamera: true,
					// Only support camera scan type.
					supportedScanTypes: [Html5Qrcode.SCAN_TYPE_CAMERA]
				};

				Html5Qrcode.getCameras().then(devices => {
					if (devices && devices.length) {
						$.each(devices, function(i, v) {
							$(`#select_kamera_${type}`).append(`
                                <input class="checkbox-tools" type="radio" name="tools" value="${v.id}" id="tool-${i}">
                                <label class="for-checkbox-tools" for="tool-${i}">
                                    ${v.label}
                                </label>
                            `)
						});

						$(`#select_kamera_${type} :input[name='tools']`).each(function(i, v) {
							if (i == 0) {
								$(this).attr('checked', true);
							}
						});

						let cameraId = devices[0].id;
						// html5QrCode.start(devices[0]);
						$('input[name="tools"]').on('click', function() {
							// alert($(this).val());
							chkTypeScan.stop();
							chkTypeScan.start({
								deviceId: {
									exact: $(this).val()
								}
							}, config, qrCodeSuccessCallback);
						});
						//start scan
						chkTypeScan.start({
							deviceId: {
								exact: cameraId
							}
						}, config, qrCodeSuccessCallback);

					}
				}).catch(err => {
					message("Error!", "Kamera tidak ditemukan", "error");
					$(`#start_scan_${type}`).show();
					$(`#stop_scan_${type}`).hide();
				});
			}
		});
	}

	const requestScan = async (type, decodedText, mode) => {

		const url = "<?= base_url('WMS/PenerimaanBarang/checkKodePalletAndInsertToTemp') ?>";

		let chkType = type === "pallet" ? html5QrCode.resume() : html5QrCode2.resume();

		let postData = new FormData();
		postData.append('surat_jalan_id', suratJalanId);
		postData.append('client_id', client_id);
		postData.append('principle_id', principle_id);
		postData.append('kode', decodedText);
		postData.append('type', type);
		postData.append('mode', mode);

		const request = await fetch(url, {
			method: 'POST',
			body: postData
		});

		const response = await request.json();

		// if (type === "pallet") {
		$(`#txtpreviewscan_${type}`).val(response.kode);
		if (response.type == 200) {
			Swal.fire("Success!", response.message, "success").then(function(result) {
				if (result.value == true) {
					chkType
					chkAndDisableButtonSave()
				}
			});

			type === "pallet" ? get_data_pallet() : get_data_sku(response.data);

		} else if (response.type == 201) {
			Swal.fire("Error!", response.message, "error").then(function(result) {
				if (result.value == true) {
					chkType
				}
			});
		} else {
			Swal.fire("Info!", response.message, "info").then(function(result) {
				if (result.value == true) {
					chkType
				}
			});
		}
		// }
	}

	function handleInputManual(type) {
		$(`#input_manual_${type}`).hide();
		$(`#close_input_${type}`).show();
		$(`#preview_input_manual_${type}`).show();
	}

	function handleCheckKode(type) {
		let kode = $(`#kode_barcode_${type}`).val();

		if (type === "pallet") {
			if (kode == "") {
				message("Error!", "Kode Pallet tidak boleh kosong", "error");
				return false;
			} else {
				// handlereRquestToCheckKodePallet(kode, type, 'input_manual');
				fetch('<?= base_url('WMS/PenerimaanBarang/getKodeAutoComplete?params='); ?>' + kode +
						`&type=newPallet&prosesId=${''}&principleId=${''}`)
					.then(response => response.json())
					.then((results) => {
						if (results.length == 0) {
							message("Error!", "Data tidak ada", "error");
							return false;
						}

						if (!results[0]) {
							// document.getElementById('table-fixed').style.display = 'none';
							$(`#table-fixed-${type}`).css('display', 'none')
						} else {
							let data = "";
							// console.log(results);

							if (type == "addScanSku") {
								results.forEach(function(e, i) {
									data += `
												<tr onclick="getNoSuratJalanEks('${e.id}')" style="cursor:pointer; background-color:${e.sku_satuan_warna}">
													<td >${i + 1}.</td>
													<td >${e.kode}</td>
													<td >${e.nama}</td>
												</tr>
												`;
								})
							} else if (type == "pallet") {
								results.forEach(function(e, i) {
									data += `
												<tr onclick="getNoSuratJalanEks('${e.kode}','${type}','${e.is_aktif}')" style="cursor:pointer">
													<td class="col-xs-1">${i + 1}.</td>
													<td class="col-xs-11">${e.kode} | ${e.is_aktif == 0 ? 'Tidak Aktif' : 'Aktif'}</td>
												</tr>
												`;
								})
							} else {
								results.forEach(function(e, i) {
									data += `
												<tr onclick="getNoSuratJalanEks('${e.kode}')" style="cursor:pointer">
														<td class="col-xs-1">${i + 1}.</td>
														<td class="col-xs-11">${e.kode}</td>
												</tr>
												`;
								})
							}

							// document.getElementById(`konten-table-${type}`).innerHTML = data;
							// // console.log(data);
							// document.getElementById(`table-fixed-${type}`).style.display = 'block';
							$(`#konten-table-${type}`).html(data);
							// console.log(data);
							$(`#table-fixed-${type}`).css('display', 'block')
						}
					});
			}
		}

		if (type === "sku") {
			if (kode == "") {
				message("Error!", "Kode Pallet tidak boleh kosong", "error");
				return false;
			} else {
				handlereRquestToCheckKodePallet(kode, type, 'input_manual');
			}
		}


	}

	function handleChecKodeByEnter(e, kode, type) {
		if (type === "pallet") {
			if (e.keyCode == 13) {
				if (kode == "") {
					message("Error!", "Kode Pallet tidak boleh kosong", "error");
					return false;
				} else {
					handlereRquestToCheckKodePallet(kode, type, 'input_manual');
				}
			}
		}

		if (type === "sku") {
			if (e.keyCode == 13) {
				if (kode == "") {
					message("Error!", "Kode SKU tidak boleh kosong", "error");
					return false;
				} else {
					handlereRquestToCheckKodePallet(kode, type, 'input_manual');
				}
			}
		}

	}

	function handlereRquestToCheckKodePallet(kode, type, mode = null, aktif = 0) {
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanBarang/checkKodePalletAndInsertToTemp'); ?>",
			type: "POST",
			data: {
				surat_jalan_id: suratJalanId,
				client_id,
				principle_id,
				kode,
				type,
				mode,
				aktif
			},
			dataType: "JSON",
			beforeSend: () => {
				$(`#loading_cek_manual_${type}`).show();
			},
			success: function(data) {
				if (type === "pallet") {
					$(`#txtpreviewscan_${type}`).val(data.kode);
					if (data.type == 200) {
						message("Success!", data.message, "success");
						$(`#kode_barcode_${type}`).val("");
						get_data_pallet();
						chkAndDisableButtonSave()
					} else if (data.type == 201) {
						message("Error!", data.message, "error");
					} else {
						message("Info!", data.message, "info");
					}
				}

				if (type === "sku") {
					$(`#txtpreviewscan_${type}`).val(data.kode);
					if (data.type == 200) {
						message("Success!", data.message, "success");
						$(`#kode_barcode_${type}`).val("");
						get_data_sku(data.data)
						chkAndDisableButtonSave()

					} else if (data.type == 201) {
						message("Error!", data.message, "error");
					} else {
						message("Info!", data.message, "info");
					}
				}

			},
			complete: () => {
				$(`#loading_cek_manual_${type}`).hide();
			},
		});
	}

	function get_data_sku(data) {

		dataScanSkuTemp = []
		$.each(data, function(i, v) {
			dataScanSku.filter((res) => {
				if ((res.sku_id === v.sku_id) && (res.sj_ed === v.sku_exp_date)) {
					dataScanSkuTemp.push({
						...res
					})
				} else {
					dataScanSkuTemp.push({
						jumlah_terima: 0,
						sisa: 0,
						sj_ed: v.sku_exp_date,
						sjd_jumlah_barang: 0,
						sku_id: v.sku_id,
						sku_kemasan: v.sku_kemasan,
						sku_kode: v.sku_kode,
						sku_nama_produk: v.sku_nama_produk,
						sku_satuan: v.sku_satuan,
					})
				}
			});
		});

		let result = dataScanSkuTemp.reduce((unique, o) => {
			if (!unique.some(obj => obj.sku_id === o.sku_id && obj.sj_ed === o.sj_ed)) {
				unique.push(o);
			}
			return unique;
		}, []);

		arrManeh = []
		$.each(result, function(index, value) {
			arrManeh.push(value);
		})

		$("#showTableListScanSku").show();

		initTableListScanSku(result);

	}

	function ajax_request_update_qty_sku(pallet_id, pallet_detail_id, sku_id, sku_exp_date, is_count, batch_no, qty) {


		if (dataScanSkuTemp.length > 0) {
			const findIndexData = dataScanSkuTemp.findIndex((item) => (item.sku_id == sku_id) && (item.sj_ed ==
				sku_exp_date));
			const dat = dataScanSkuTemp[findIndexData]['sjd_jumlah_barang'];
			dataScanSkuTemp[findIndexData]['sisa'] = parseInt(dat) - parseInt(qty.value);
		}

		let result = arrManeh.reduce((unique, o) => {
			if (!unique.some(obj => obj.sku_id === o.sku_id && obj.sj_ed === o.sj_ed)) {
				unique.push(o);
			}
			return unique;
		}, []);

		$.ajax({
			url: "<?= base_url('WMS/PenerimaanBarang/update_qty_pallet_detail_by_id'); ?>",
			type: "POST",
			data: {
				id: pallet_detail_id,
				qty: qty.value
			},
			dataType: "JSON",
			async: false,
			success: function(data) {
				$('#showfilterdata').fadeOut("slow", function() {
					$(this).hide();
					$("#tablelistdatadetailsj > tbody").html(
						`<tr><td colspan="10" class="text-center"><span ><i class="fa fa-spinner fa-spin"></i> Loading...</span></td></tr>`
					);
					$(".qty_detail_pallet").attr('readonly', true);
				}).fadeIn("slow", function() {
					$(this).show();
					get_data_surat_jalan_detail(penerimaanBarangId);
					$(".qty_detail_pallet").attr('readonly', false);
					// swal.close();
				});

				$('#tablelistdatadetailsj').fadeOut("slow", function() {
					$(this).hide();
				}).fadeIn("slow", function() {
					get_data_surat_jalan_detail(penerimaanBarangId);
					$(this).show();
				});

				$('#show_isi_sku_pallet').fadeOut("slow", function() {
					$(this).hide();
				}).fadeIn("slow", function() {
					$(this).show();
					get_data_surat_jalan_detail(penerimaanBarangId);
					append_list_data_setelah_pilih_sku(pallet_id, is_count)
				});

				if (dataScanSkuTemp.length > 0) {
					$('#showTableListScanSku').fadeOut("slow", function() {
						$(this).hide();
					}).fadeIn("slow", function() {
						initTableListScanSku(result);
						$(this).show();
					});
				}
			},
		});
	}

	function initTableListScanSku(data) {
		if ($.fn.DataTable.isDataTable('#table_list_scan_sku')) {
			$('#table_list_scan_sku').DataTable().destroy();
		}
		$("#table_list_scan_sku > tbody").empty();

		$.each(data, function(i, v) {

			// let readonly = "";
			// if (v.sisa == 0) {
			//     readonly += "disabled checked";
			// } else {
			//     readonly += "";
			// }

			$("#table_list_scan_sku > tbody").append(`
                <tr>
                    <td class="text-center">
                        <input type="checkbox" class="form-control check-item" name="chk-data-sku[]" style="transform: scale(1.5)" id="chk-data-sku[]" value="${v.sku_id},${v.sj_ed}, ${v.sjd_jumlah_barang}, ${v.batch_no}"/>
                    </td>
                    <td>${v.sku_kode}</td>
                    <td>${v.sku_nama_produk}</td>
                    <td class="text-center">${v.sku_kemasan}</td>
                    <td class="text-center">${v.sku_satuan}</td>
                    <td class="text-center">${v.sj_ed}</td>
                    <td class="text-center">${v.sjd_jumlah_barang}</td>
                    <td class="text-center">${v.sisa}</td>
                </tr>
            `);
		});

		$('#table_list_scan_sku').DataTable({
			columnDefs: [{
				sortable: false,
				targets: [0, 1, 2, 3, 4, 5, 6, 7]
			}],
			lengthMenu: [
				[-1],
				['All']
			],
		});
	}

	function handleStopScan(type) {
		remove_stop_scan(type);
	}

	function handleStopInput(type) {
		remove_close_input(type);
	}

	function remove_stop_scan(type) {

		$(`#start_scan_${type}`).show();
		$(`#stop_scan_${type}`).hide();
		$(`#preview_${type}`).hide();
		$(`#txtpreviewscan_${type}`).val("");
		$(`#select_kamera_${type}`).empty();
		type === "pallet" ? html5QrCode.stop() : html5QrCode2.stop();

	}

	function remove_close_input(type) {
		$(`#input_manual_${type}`).show();
		$(`#close_input_${type}`).hide();
		$(`#preview_input_manual_${type}`).hide();
		$(`#kode_barcode_${type}`).val("");
		$(`#txtpreviewscan_${type}`).val("");
	}

	const handleUpdateService = (value) => {
		const gudangPenerima = $("#gudang_penerima").val();
		const lastUpdated = $("#lastUpdated").val();
		requestAjax("<?= base_url('WMS/PenerimaanBarang/requestUpdateHeaderService'); ?>", {
			penerimaanBarangId,
			gudangPenerima,
			jasaPengangkut: value,
			lastUpdated
		}, "POST", "JSON", function(response) {
			if (response.status === 200) $("#lastUpdated").val(response.lastUpdatedNew);
			if (response.status === 400) return messageNotSameLastUpdated()
			if (response.status === 401) return message_topright('error', 'something wrong')
		})
	}

	const handleUpdateVehicle = (value) => {
		const gudangPenerima = $("#gudang_penerima").val();
		const lastUpdated = $("#lastUpdated").val();
		requestAjax("<?= base_url('WMS/PenerimaanBarang/requestUpdateHeaderVehicle'); ?>", {
			penerimaanBarangId,
			gudangPenerima,
			kendaraan: value,
			lastUpdated
		}, "POST", "JSON", function(response) {
			if (response.status === 200) $("#lastUpdated").val(response.lastUpdatedNew);
			if (response.status === 400) return messageNotSameLastUpdated()
			if (response.status === 401) return message_topright('error', 'something wrong')
		})
	}

	const handleUpdateDriver = (value) => {
		const gudangPenerima = $("#gudang_penerima").val();
		const lastUpdated = $("#lastUpdated").val();
		requestAjax("<?= base_url('WMS/PenerimaanBarang/requestUpdateHeaderDriver'); ?>", {
			penerimaanBarangId,
			gudangPenerima,
			namaPengemudi: value,
			lastUpdated
		}, "POST", "JSON", function(response) {
			if (response.status === 200) $("#lastUpdated").val(response.lastUpdatedNew);
			if (response.status === 400) return messageNotSameLastUpdated()
			if (response.status === 401) return message_topright('error', 'something wrong')
		})
	}

	const handleUpdateChecker = (event) => {
		// const checker = $("#checker").map();
		const checker = $("#checker").children("option").filter(":selected").map(function() {
			return this.value;
		}).get();
		const gudangPenerima = $("#gudang_penerima").val();
		const lastUpdated = $("#lastUpdated").val();

		requestAjax("<?= base_url('WMS/PenerimaanBarang/requestUpdateHeaderChecker'); ?>", {
			penerimaanBarangId,
			gudangPenerima,
			checker: checker,
			lastUpdated
		}, "POST", "JSON", function(response) {
			if (response.status === 200) $("#lastUpdated").val(response.lastUpdatedNew);
			if (response.status === 400) return messageNotSameLastUpdated()
			if (response.status === 401) return message_topright('error', 'something wrong')
		})
	}

	const handleUpdateKeterangan = (value) => {
		const gudangPenerima = $("#gudang_penerima").val();
		const lastUpdated = $("#lastUpdated").val();

		requestAjax("<?= base_url('WMS/PenerimaanBarang/requestUpdateHeaderketerangan'); ?>", {
			penerimaanBarangId,
			gudangPenerima,
			keterangan: value,
			lastUpdated
		}, "POST", "JSON", function(response) {
			if (response.status === 200) $("#lastUpdated").val(response.lastUpdatedNew);
			if (response.status === 400) return messageNotSameLastUpdated()
			if (response.status === 401) return message_topright('error', 'something wrong')
		})
	}

	const handlerKonfirmasipallet = (pallet_id, flag, karyawan_id) => {
		let tgl = $("#tgl").val();
		let gudang_penerima = $("#gudang_penerima").val();
		// let checker = $("#checker");
		// let checkerText = $("#checker").children("option").filter(":selected").text();
		let keterangan = $("#keterangan").val();
		const lastUpdated = $("#lastUpdated").val();

		// if (checker.val() == "") {
		//     message("Error!", "checker tidak boleh kosong, silahkan update data checker dahulu!", "error");
		//     return false;
		// } else 

		if (flag == 0) {
			message("Error!", "Lokasi Tujuan tidak boleh kosong!", "error");
			return false;
		} else {
			messageBoxBeforeRequest('Pallet akan dikonfirmasi!', 'Ya, Konfirmasi', 'Tidak, Tutup').then((result) => {
				if (result.value == true) {
					requestAjax("<?= base_url('WMS/PenerimaanBarang/konfirmasiDataPerPallet'); ?>", {
						penerimaanBarangId,
						client_id,
						tgl,
						gudang_penerima,
						keterangan,
						pallet_id,
						principle_id,
						karyawan_id,
						lastUpdated
					}, "POST", "JSON", function(response) {
						if (response.status === 200) {
							$("#lastUpdated").val(response.lastUpdatedNew);
							message_topright("success", response.message);
							checkPalletIsreadyInPbd2();
							chkAndDisableButtonSave()
							setTimeout(() => {
								location.reload();
							}, 500);
						}
						if (response.status === 400) return messageNotSameLastUpdated()
						if (response.status === 401) return message_topright('error', response.message)
					})
				}
			});
			// Swal.fire({
			// 	title: "Apakah anda yakin?",
			// 	text: "Pallet akan dikonfirmasi!",
			// 	icon: "warning",
			// 	showCancelButton: true,
			// 	confirmButtonColor: "#3085d6",
			// 	cancelButtonColor: "#d33",
			// 	confirmButtonText: "Ya, Simpan",
			// 	cancelButtonText: "Tidak, Tutup"
			// }).then((response) => {
			// 	if (response.value == true) {
			// 		$.ajax({
			// 			url: "<?= base_url('WMS/PenerimaanBarang/konfirmasiDataPerPallet') ?>",
			// 			type: "POST",
			// 			data: {
			// 				penerimaanBarangId,
			// 				client_id,
			// 				tgl,
			// 				gudang_penerima,
			// 				keterangan,
			// 				pallet_id,
			// 				principle_id,
			// 				karyawan_id
			// 			},
			// 			dataType: "JSON",
			// 			success: function(response) {
			// 				if (response == 1) {
			// 					message_topright("success", "Pallet berhasil dikonfirmasi");
			// 					checkPalletIsreadyInPbd2();
			// 					chkAndDisableButtonSave()
			// 					setTimeout(() => {
			// 						location.reload();
			// 					}, 500);
			// 				} else {
			// 					message("Error!", "Pallet berhasil dikonfirmasi", "error");
			// 					return false;
			// 				}
			// 			},
			// 		});
			// 	}
			// });

		}
	}

	$(document).on("click", "#batalkan", function() {
		Swal.fire({
			title: "Apakah anda yakin?",
			text: "Jika dibatalkan, data yang telah diisi akan ke hilang!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Batalkan",
			cancelButtonText: "Tidak, Tutup"
		}).then((result) => {
			if (result.value == true) {
				$.ajax({
					url: "<?= base_url('WMS/PenerimaanBarang/batalkan2') ?>",
					type: "POST",
					data: {
						id: suratJalanId.split(','),
					},
					dataType: "JSON",
					success: function(data) {
						if (data == 1) {
							message_topright("success", "Berhasil membatalkan");
							setTimeout(() => {
								location.href =
									"<?= base_url('WMS/PenerimaanBarang/PenerimaanBarangMenu') ?>";
							}, 500);
						} else {
							message_topright("error", "Gagal membatalkan");
						}
					},
				});
			}
		});
	});

	const handleSaveAndback = () => {
		setTimeout(() => {
			Swal.fire({
				title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
				showConfirmButton: false,
				timer: 600
			});
			location.href = "<?= base_url('WMS/PenerimaanBarang/PenerimaanBarangMenu') ?>"
		}, 500);
	}

	const handlerAutoCompleteScan = (event, value, type) => {
		if (value != "") {
			fetch('<?= base_url('WMS/PenerimaanBarang/getKodeAutoComplete?params='); ?>' + value + `&type=${type}`)
				.then(response => response.json())
				.then((results) => {
					if (!results[0]) {
						$(`#table-fixed-${type}`).css('display', 'none')
						// document.getElementById('table-fixed').style.display = 'none';
					} else {
						let data = "";
						// console.log(results);
						results.forEach(function(e, i) {
							data += `
									<tr onclick="getNoSuratJalanEks('${e.kode}', '${type}')" style="cursor:pointer">
											<td width="10%">${i + 1}.</td>
											<td width="90%">${e.kode}</td>
									</tr>
									`;
						})

						$(`#konten-table-${type}`).html(data);
						// console.log(data);
						$(`#table-fixed-${type}`).css('display', 'block')
					}
				});
		} else {
			$(`#table-fixed-${type}`).css('display', 'none')
		}
	}

	function getNoSuratJalanEks(data, type, aktif = 0) {
		if (type == 'pallet' || type == 'newPallet') {
			const split = data.split('/');
			$("#kode_barcode_auto").val(split[1] + "/" + split[2]);
			data = split[1] + "/" + split[2];

		}
		$(`#kode_barcode_${type}`).val(data);
		$(`#table-fixed-${type}`).css('display', 'none')


		handlereRquestToCheckKodePallet(data, type, 'input_manual', aktif);
	}
</script>