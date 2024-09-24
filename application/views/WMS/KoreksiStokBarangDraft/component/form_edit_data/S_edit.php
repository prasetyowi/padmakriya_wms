<script>
	let global_id = $("#global_id_edit").val();
	let global_sku_id = [];
	let global_data_detail = [];
	loadingBeforeReadyPage()
	$(document).ready(function() {
		select2();
		getDataKoreksiDraftById();

		$(document).on("input", ".numeric", function(event) {
			this.value = this.value.replace(/[^0-9]/g, '');
		});

		let count_sku = $("#list_data_tambah_koreksi_stok_edit > tbody tr").length;
		$("#list_data_tambah_koreksi_stok_edit > tfoot tr #total_detail_sku_edit").html("<strong><h4>Total " + count_sku + " SKU</h4></strong>");
	});

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


	function select2() {
		$(".select2").select2({
			width: "100%"
		});
	}

	function getDataKoreksiDraftById() {
		$.ajax({
			url: "<?= base_url('WMS/KoreksiStokBarangDraft/getDataKoreksiDraftById'); ?>",
			type: "POST",
			data: {
				id: global_id
			},
			dataType: "JSON",
			success: function(response) {
				const header = response.header;
				const detail = response.detail;
				const tipeDokumen = response.tipeDokumen;
				$("#no_koreksi_draft_edit").val(header.kode);
				$("#koreksi_draft_tgl_edit").val(header.tgl);
				$("#gudang_koreksi_draft_edit").val(header.gudang_id).trigger('change');
				$("#koreksi_draft_principle_edit").val(header.principle_id).trigger('change');
				$("#koreksi_draft_checker_edit").val(header.checker_id).trigger('change');
				$("#koreksi_draft_tipe_transaksi_edit").val(header.tipe_id).trigger('change');
				// get_checker(header.checker_id);
				$("#koreksi_draft_status_edit").val(header.status);
				$("#koreksi_draft_keterangan_edit").val(header.keterangan);

				var tipe_dokumen = tipeDokumen == null ? '' : tipeDokumen.table_name + '-' + tipeDokumen.table_name_kode + '-' + tipeDokumen.tipe_dokumen_nama;
				$("#koreksi_draft_tipe_dokumen").val(tipe_dokumen).trigger('change');
				$("#koreksi_draft_no_referensi_dokumen").val(header.no_referensi_dokumen);

				$("#last_updated").val(header.tr_koreksi_stok_draft_tgl_update);
				header.status == "Draft" ? $("#koreksi_draft_approval_edit").prop('checked', false) : $("#koreksi_draft_approval_edit").prop('checked', true);

				if (header.attachment != null) {
					$('#hide-file').append(`
					<a href="<?= base_url('assets/images/uploads/KoreksiStokBarang/') ?>${header.attachment}" target="_blank">${header.attachment}</a>
					`)
				}

				initDataDetail(detail);
			}
		})
	}

	function initDataDetail(data) {
		if (data.length != 0) {
			$.each(data, function(i, v) {
				global_data_detail.push({
					id: v.id,
				});
				$("#list_data_tambah_koreksi_stok_edit > tbody").append(`
            <tr>
                <td>${v.sku_kode} <input type="hidden" name="sku_stock_id" id="sku_stock_id" value="${v.id}"/></td>
                <td>${v.sku_nama} <input type="hidden" name="sku_id" id="sku_id" value="${v.sku_id}"/></td>
                <td class="text-center">${v.brand}</td>
                <td class="text-center">${v.sku_satuan}</td>
                <td class="text-center">${v.sku_kemasan}</td>
                <td class="text-center">${v.ed}</td>
                <td class="text-center">${v.qty_available}</td>
                <td class="text-center">
                  <input type="text" class="form-control numeric" name="qty_plan" id="qty_plan" value="${v.qty_plan}" onchange="CheckQtyPlan('${v.sku_id}',this)"/>
                </td>
                <td class="text-center">
                    <button type="button" data-toggle="tooltip" data-placement="top" title="hapus" data-id="${v.id}" class="deletesku" style="border:none;background:transparent"><i class="fas fa-trash text-danger" style="cursor: pointer"></i></button> 
                </td>
            </tr>
        `);
			});
		} else {
			$("#list_data_tambah_koreksi_stok_edit > tbody").html(`<tr><td colspan="9" class="text-center text-danger">Data Kosong</td></tr>`);
		}

		trigger();
	}

	function trigger() {
		check_count_sku();

		let count_sku = $("#list_data_tambah_koreksi_stok_edit > tbody tr").length;
		$("#list_data_tambah_koreksi_stok_edit > tfoot tr #total_detail_sku_edit").html("<strong><h4>Total " + count_sku + " SKU</h4></strong>");


		//btn delete
		var arr_lokal_temp = [];
		$(document).on("click", ".deletesku", function() {
			let id = $(this).attr("data-id");

			arr_lokal_temp.push(id);
			$("#pilih_sku_koreksi_draft_edit").attr('data-sku-id', arr_lokal_temp);
			$("#search_filter_data_sku_edit").attr('data-sku-id', arr_lokal_temp);

			let item = global_data_detail.filter((value, index) => value.id !== id);
			global_data_detail.length = 0;
			$.each(item, function(i, v) {
				global_data_detail.push({
					id: v.id,
				})
			});

			$(this).parent().parent().remove();

			check_count_sku();

			let count_sku = $("#list_data_tambah_koreksi_stok_edit > tbody tr").length;
			$("#list_data_tambah_koreksi_stok_edit > tfoot tr #total_detail_sku_edit").html("<strong><h4>Total " + count_sku + " SKU</h4></strong>");
		});
	}

	function check_count_sku() {
		let total_sku = $("#list_data_tambah_koreksi_stok_edit > tbody tr").length;
		if (total_sku == 0) {
			//hapus readonly perusahaan dan principle
			$("#gudang_koreksi_draft_edit").removeAttr("disabled");
			$("#koreksi_draft_principle_edit").removeAttr("disabled");
		} else {
			$("#gudang_koreksi_draft_edit").attr("disabled", "disabled");
			$("#koreksi_draft_principle_edit").attr("disabled", "disabled");
		}
	}

	$("#gudang_koreksi_draft_edit").on("change", function() {
		$("#pilih_sku_koreksi_draft_edit").attr("data-gudang-id", $(this).val());
		$("#pilih_sku_koreksi_draft_edit").attr("data-gudang-txt", $(this).children("option").filter(":selected").text());
	});

	$("#koreksi_draft_principle_edit").on("change", function() {
		let id = $(this).val();
		let txt = $(this).children("option").filter(":selected").text();
		$("#pilih_sku_koreksi_draft_edit").attr("data-principle-id", id);
		$("#pilih_sku_koreksi_draft_edit").attr("data-principle-txt", txt);
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/KoreksiStokBarangDraft/get_checker_by_principleId') ?>",
			data: {
				id: id
			},
			dataType: "JSON",
			async: false,
			success: function(response) {
				$("#koreksi_draft_checker_edit").empty();
				if (response.length > 0) {
					$("#koreksi_draft_checker_edit").append("<option value=''>--Pilih Checker--</option>")
					$.each(response, function(i, v) {
						$("#koreksi_draft_checker_edit").append(`<option value="${v.id}">${v.nama}</option>`);
					});
				} else {
					$("#koreksi_draft_checker_edit").empty();
				}
			}
		});
	});

	$("#koreksi_draft_approval_edit").on("change", function(e) {
		e.target.checked ? $("#koreksi_draft_status_edit").val($(this).val()) : $("#koreksi_draft_status_edit").val("Draft");
	});

	function checkAllSKU(e) {
		var checkboxes = $("input[name='chk-data[]']");
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

	$(document).on("click", ".btn_pilih_sku_edit", function() {
		let arr_chk = [];
		var checkboxes = $("input[name='chk-data[]']");
		for (var i = 0; i < checkboxes.length; i++) {
			if (checkboxes[i].checked == true && !(checkboxes[i].disabled)) {
				checkboxes[i].disabled = true;
				arr_chk.push(checkboxes[i].value);
				global_sku_id.push(checkboxes[i].value);
			}
		}

		if (arr_chk.length == 0) {
			message("Error!", "Pilih data yang akan dipilih", "error");
		} else {
			$.ajax({
				url: "<?= base_url('WMS/KoreksiStokBarangDraft/get_data_sku_by_id') ?>",
				type: "POST",
				data: {
					id: arr_chk,
				},
				dataType: "JSON",
				success: function(data) {
					append_list_data_setelah_pilih_sku(data);
					$("#list_data_pilih_sku_edit").modal("hide");
					// append_list_data_setelah_pilih_sku(data)
				}
			});
		}
	});

	function append_list_data_setelah_pilih_sku(data) {
		// $("#list_data_tambah_koreksi_stok > tbody").empty();
		if (data.length != 0) {
			$.each(data, function(i, v) {
				$("#list_data_tambah_koreksi_stok_edit > tbody").append(`
            <tr>
                <td>${v.sku_kode} <input type="hidden" name="sku_stock_id" id="sku_stock_id" value="${v.id}"/></td>
                <td>${v.sku} <input type="hidden" name="sku_id" id="sku_id" value="${v.sku_id}"/></td>
                <td class="text-center">${v.brand}</td>
                <td class="text-center">${v.satuan}</td>
                <td class="text-center">${v.kemasan}</td>
                <td class="text-center">${v.ed}</td>
                <td class="text-center">${v.qty_available}</td>
                <td class="text-center">
                  <input type="text" class="form-control numeric" name="qty_plan" id="qty_plan" onchange="CheckQtyPlan('${v.sku_id}',this)"/>
                </td>
                <td class="text-center">
                    <button type="button" data-toggle="tooltip" data-placement="top" title="hapus" data-id="${v.id}" class="deletesku" style="border:none;background:transparent"><i class="fas fa-trash text-danger" style="cursor: pointer"></i></button> 
                </td>
            </tr>
        `);
			});
		} else {
			$("#list_data_tambah_koreksi_stok_edit > tbody").html(`<tr><td colspan="9" class="text-center text-danger">Data Kosong</td></tr>`);
		}

		trigger()
	}

	function CheckQtyPlan(sku_id, qty) {
		$("#list_data_tambah_koreksi_stok_edit > tbody tr").each(function(i, v) {
			let table_sku_id = $(this).find("td:eq(1) input[type='hidden']").val();
			let qty_available = $(this).find("td:eq(6)").text();
			if (table_sku_id == sku_id) {
				if (parseInt(qty.value) > parseInt(qty_available)) {
					message('Error!', 'Qty Plan melebihi Qty Available, Qty Available dalam SKU <strong>' + parseInt(qty_available) + '</strong>', 'error');
					qty.value = '';
					return false;
				}
			}
		});
	}

	function check_delete(sku_id) {
		let input = $(".check-item");
		if (sku_id != "") {

			const newArr = global_sku_id.filter(value => !sku_id.includes(value));
			global_sku_id.length = 0;
			newArr.forEach(function(v, i) {
				global_sku_id.push(v);
			})

			input.each(function(i, v) {
				if (sku_id.includes($(this).val())) {
					$("#pilih_sku_koreksi_draft_edit").removeAttr('data-sku-id');
					$("#search_filter_data_sku_edit").removeAttr('data-sku-id');
					$(this).attr({
						disabled: false,
						checked: false
					});
				}
			});
		}

		let count_sku = $("#list_data_tambah_koreksi_stok_edit > tbody tr").length;
		$("#list_data_tambah_koreksi_stok_edit > tfoot tr #total_detail_sku_edit").html("<strong><h4>Total " + count_sku + " SKU</h4></strong>");
	}

	$("#pilih_sku_koreksi_draft_edit").on("click", function() {
		let depo_id = $("#depo_edit").attr('data-id');
		let depo = $("#depo_edit").val();
		let gudang = $(this).attr('data-gudang-id');
		let gudang_txt = $(this).attr('data-gudang-txt');
		let principle = $(this).attr('data-principle-id');
		let principle_txt = $(this).attr('data-principle-txt');

		let brand = $("#brand_sku_edit").val();
		let sku_induk = $("#sku_induk_sku_edit").val();
		let nama_sku = $("#nama_sku_edit").val();
		let sku_kode_wms = $("#kode_wms_sku_edit").val();
		let sku_kode_pabrik = $("#kode_pabrik_sku_edit").val();

		let res = $(this).attr('data-sku-id');
		let sku_id = "";
		if (res != null) {
			sku_id = res.split(",")
		}

		if (gudang == undefined) {
			message("Error!", "Pilih Gudang terlebih dahulu", "error");
			return false;
		} else if (principle == undefined) {
			message("Error!", "Pilih Principle terlebih dahulu", "error");
			return false;
		} else {
			$("#list_data_pilih_sku_edit").modal('show');
			$("#depo_sku_edit").attr('depo-sku-id', depo_id);
			$("#depo_sku_edit").val(depo);
			$("#gudang_sku_edit").attr('gudang-sku-id', gudang);
			$("#gudang_sku_edit").val(gudang_txt);
			$("#principle_sku_edit").attr('principle-sku-id', principle);
			$("#principle_sku_edit").val(principle_txt);

			check_delete(sku_id);
			check_count_sku();

			//ajax to get brand and sku induk
			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/KoreksiStokBarangDraft/get_BrandAndSKUInduk') ?>",
				data: {
					principle_id: principle
				},
				dataType: "JSON",
				async: false,
				success: function(response) {

					$("#brand_sku_edit").empty();
					$("#sku_induk_sku_edit").empty();

					if (response.brand.length > 0) {
						$("#brand_sku_edit").append(`<option value="">--Pilih Brand--</option>`);
						$.each(response.brand, function(i, v) {
							$("#brand_sku_edit").append(`<option value="${v.id}">${v.nama}</option>`)
						});
					} else {
						$("#brand_sku_edit").html("");
					}

					if (response.sku_induk.length > 0) {
						$("#sku_induk_sku_edit").append(`<option value="">--Pilih SKU Induk--</option>`);
						$.each(response.sku_induk, function(i, v) {
							$("#sku_induk_sku_edit").append(`<option value="${v.id}">${v.nama}</option>`);
						});
					} else {
						$("#sku_induk_sku_edit").html("");
					}
				}
			});

			data_sku(depo_id, gudang, principle, brand, sku_induk, nama_sku, sku_kode_wms, sku_kode_pabrik);
		}
	});

	$(document).on("click", "#search_filter_data_sku_edit", function() {
		let depo = $("#depo_sku_edit").attr('depo-sku-id');
		let gudang = $("#gudang_sku_edit").attr('gudang-sku-id');
		let principle = $("#principle_sku_edit").attr('principle-sku-id');
		let brand = $("#brand_sku_edit").val();
		let sku_induk = $("#sku_induk_sku_edit").val();
		let nama_sku = $("#nama_sku_edit").val();
		let sku_kode_wms = $("#kode_wms_sku_edit").val();
		let sku_kode_pabrik = $("#kode_pabrik_sku_edit").val();

		let res = $(this).attr('data-sku-id');
		let sku_id = "";
		if (res != null) {
			sku_id = res.split(",")
		}

		$("#loadingsearchsku_edit").show();

		check_delete(sku_id);
		data_sku(depo, gudang, principle, brand, sku_induk, nama_sku, sku_kode_wms, sku_kode_pabrik);
	});

	function data_sku(depo, gudang, principle, brand, sku_induk, nama_sku, sku_kode_wms, sku_kode_pabrik) {
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/KoreksiStokBarangDraft/search_filter_chosen_sku') ?>",
			data: {
				depo: depo,
				gudang: gudang,
				principle: principle,
				brand: brand,
				sku_induk: sku_induk,
				nama_sku: nama_sku,
				sku_kode_wms: sku_kode_wms,
				sku_kode_pabrik: sku_kode_pabrik
			},
			dataType: "JSON",
			async: false,
			success: function(response) {
				$("#loadingsearchsku_edit").hide();
				if (response.length > 0) {
					if ($.fn.DataTable.isDataTable('#table_list_data_pilih_sku_edit')) {
						$('#table_list_data_pilih_sku_edit').DataTable().destroy();
					}
					$("#table_list_data_pilih_sku_edit > tbody").empty();

					$.each(response, function(i, v) {
						let str = "";
						var str_ = "";

						$.each(global_data_detail, function(index, value) {
							if (value.id == v.id) {
								str_ += "disabled checked";
							} else {
								str_ += "";
							}
						});

						if (global_sku_id != '') {
							if (global_sku_id.includes(v.id)) {
								str += "<input type='checkbox' class='form-control check-item' disabled checked name='chk-data[]' id='chk-data[]' value='" + v.id + "'/>";
							} else {
								if (v.qty_available == 0) {
									str += "<input type='checkbox' class='form-control check-item' disabled checked name='chk-data[]' id='chk-data[]' value='" + v.id + "'/>";
								} else {
									str += "<input type='checkbox' class='form-control check-item' " + str_ + "  name='chk-data[]' id='chk-data[]' value='" + v.id + "'/>";
								}
							}
						} else {
							str += "<input type='checkbox' class='form-control check-item' " + str_ + " name='chk-data[]' id='chk-data[]' value='" + v.id + "'/>";
						}

						$("#table_list_data_pilih_sku_edit > tbody").append(`
                <tr>
                    <td width="5%" class="text-center">
                        ${str}
                    </td>
                    <td width="15%">${v.sku_induk}</td>
                    <td width="25%">${v.sku}</td>
                    <td width="8%" class="text-center">${v.kemasan}</td>
                    <td width="8%" class="text-center">${v.satuan}</td>
                    <td width="10%" class="text-center">${v.principle}</td>
                    <td width="10%" class="text-center">${v.brand}</td>
                    <td width="8%" class="text-center">${v.ed}</td>
                    <td width="10%" class="text-center">${v.qty_available}</td>
                </tr>
            `);
					});

					$('#table_list_data_pilih_sku_edit').DataTable({
						columnDefs: [{
							sortable: false,
							targets: [0, 1, 2, 3, 4, 5, 6, 7, 8]
						}],
						lengthMenu: [
							[-1],
							['All']
						],
					});
				} else {
					$("#table_list_data_pilih_sku_edit > tbody").html(`<tr><td colspan="9" class="text-center text-danger">Data Kosong</td></tr>`);
				}
			}
		})
	}

	$(document).on("click", ".btn_close_list_data_pilih_sku_edit", function() {
		$("#list_data_pilih_sku_edit").modal('hide');
	});

	$(document).on("click", "#edit_koreksi_draft_edit", function() {
		let gudang = $("#gudang_koreksi_draft_edit");
		let principle = $("#koreksi_draft_principle_edit");
		let checker = $("#koreksi_draft_checker_edit").children("option").filter(":selected");
		let tipe = $("#koreksi_draft_tipe_transaksi_edit");
		let status = $("#koreksi_draft_status_edit");
		let keterangan = $("#koreksi_draft_keterangan_edit");
		let kode = $("#no_koreksi_draft_edit").val();
		let arr_sku_stock_id = [];
		let arr_sku_id = [];
		let arr_qty_plan = [];
		let arr_detail = [];

		if (gudang.val() == "") {
			message("Error!", "Gudang tidak boleh kosong", "error");
			$("#count_error_edit").val("1");
			return false;
		} else if (principle.val() == "") {
			message("Error!", "Principle tidak boleh kosong", "error");
			$("#count_error_edit").val("1");
			return false;
		} else if (checker.val() == "") {
			message("Error!", "Checker tidak boleh kosong", "error");
			$("#count_error_edit").val("1");
			return false;
		} else if (tipe.val() == "") {
			message("Error!", "Tipe Transaksi tidak boleh kosong", "error");
			$("#count_error_edit").val("1");
			return false;
		} else {
			let count_pallet = $("#list_data_tambah_koreksi_stok_edit > tbody tr").length;
			if (count_pallet == 0) {
				message("Error!", "SKU masih kosong, silahkan tambah sku terlebih dahulu", "error");
				$("#count_error_edit").val("1");
				return false;
			} else {
				$("#list_data_tambah_koreksi_stok_edit > tbody tr").each(function() {
					let sku_stock_id = $(this).find("td:eq(0) input[type='hidden']");
					let sku_id = $(this).find("td:eq(1) input[type='hidden']");
					let qty_plan = $(this).find("td:eq(7) input[type='text']");
					if (qty_plan.val() == "") {
						message("Error!", "Qty Plan Koreksi tidak boleh kosong", "error");
						$("#count_error_edit").val("1");
						return false;
					} else {
						$("#count_error_edit").val("0");

						sku_stock_id.map(function() {
							arr_sku_stock_id.push($(this).val());
						}).get();

						sku_id.map(function() {
							arr_sku_id.push($(this).val());
						}).get();

						qty_plan.map(function() {
							arr_qty_plan.push($(this).val());
						}).get();
					}
				});
			}
		}

		let error = $("#count_error_edit");
		if (error.val() != 0) {
			return false;
		} else {
			if (arr_sku_id != null) {
				for (let index = 0; index < arr_sku_id.length; index++) {
					arr_detail.push({
						'sku_id': arr_sku_id[index],
						'sku_stock_id': arr_sku_stock_id[index],
						'qty_plan': arr_qty_plan[index]
					});
				}
			}

			messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Iya, Edit', 'Tidak, Tutup').then((result) => {
				if (result.value == true) {
					var json_arr = JSON.stringify(arr_detail);
					var form_data = new FormData();
					var file_data = $('#file').prop('files')[0];
					var tipeDokumen = $('#koreksi_draft_tipe_dokumen').val().split('-')
					form_data.append('file', file_data);
					form_data.append('id', global_id);
					form_data.append('gudang', gudang.val());
					form_data.append('principle', principle.val());
					form_data.append('checker', checker.text());
					form_data.append('tipe', tipe.val());
					form_data.append('status', status.val());
					form_data.append('keterangan', keterangan.val());
					form_data.append('kode', kode);
					form_data.append('detail', json_arr);
					form_data.append('lastUpdated', $("#last_updated").val());
					form_data.append('tipeDokumen', tipeDokumen[2]);
					form_data.append('noReferensiDokumen', $('#koreksi_draft_no_referensi_dokumen').val());

					$.ajax({
						url: "<?= base_url('WMS/KoreksiStokBarangDraft/edit_data_koreksi_draft') ?>",
						type: "POST",
						// data: {
						//     tgl: tgl.val(),
						//     gudang: gudang.val(),
						//     principle: principle.val(),
						//     checker: checker.text(),
						//     tipe: tipe.val(),
						//     status: status.val(),
						//     keterangan: keterangan.val(),
						//     detail: arr_detail
						// },
						data: form_data,
						contentType: false,
						processData: false,
						async: false,
						beforeSend: function() {
							Swal.fire({
								title: 'Loading ...',
								html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
								timerProgressBar: false,
								showConfirmButton: false,
								allowOutsideClick: false,
							});

							$("#edit_koreksi_draft_edit").prop("disabled", true);
						},
						dataType: "JSON",
						success: function(response) {
							$("#edit_koreksi_draft_edit").prop("disabled", false);
							Swal.fire({
								title: 'Loading ...',
								html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
								timerProgressBar: false,
								showConfirmButton: false,
								allowOutsideClick: false,
								timer: 10
							});
							if (response == 1) {
								message_topright("success", "Data berhasil disimpan");
								setTimeout(() => {
									location.href = "<?= base_url('WMS/KoreksiStokBarangDraft/KoreksiStokBarangDraftMenu') ?>";
								}, 500);
							} else if (response == 3) {
								message("Error!", "Data gagal disimpan! Ukuran file maks 1mb", "error");
							} else if (response == 4) {
								message("Error!", "Data gagal disimpan! File Attactment tidak sesuai ketentuan", "error");
							} else if (response == 5) {
								message("Error!", "Data gagal disimpan! Terjadi kesalahan pada server", "error");
							}

							if (response == 0) return message_topright("error", "Data gagal disimpan");
							if (response == 2) return messageNotSameLastUpdated()
						},
						error: function(xhr) {
							$("#edit_koreksi_draft_edit").prop("disabled", false);
							Swal.fire({
								title: 'Loading ...',
								html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
								timerProgressBar: false,
								showConfirmButton: false,
								allowOutsideClick: false,
								timer: 10
							});
						},
					});

					// requestAjax("<?= base_url('WMS/KoreksiStokBarangDraft/edit_data_koreksi_draft'); ?>", {
					// 	// id: global_id,
					// 	// gudang: gudang.val(),
					// 	// principle: principle.val(),
					// 	// checker: checker.text(),
					// 	// tipe: tipe.val(),
					// 	// status: status.val(),
					// 	// keterangan: keterangan.val(),
					// 	// kode: kode,
					// 	// detail: arr_detail,
					// 	// lastUpdated: $("#last_updated").val()
					// 	data: form_data,
					// 	contentType: false,
					// 	processData: false,
					// 	async: false,
					// }, "POST", "JSON", function(response) {
					// 	if (response == 1) {
					// 		message_topright("success", "Data berhasil disimpan");
					// 		setTimeout(() => {
					// 			location.href = "<?= base_url('WMS/KoreksiStokBarangDraft/KoreksiStokBarangDraftMenu') ?>";
					// 		}, 500);
					// 	} else if (response == 3) {
					// 		message("Error!", "Data gagal disimpan! Ukuran file maks 1mb", "error");
					// 	} else if (response == 4) {
					// 		message("Error!", "Data gagal disimpan! File Attactment tidak sesuai ketentuan", "error");
					// 	} else if (response == 5) {
					// 		message("Error!", "Data gagal disimpan! Terjadi kesalahan pada server", "error");
					// 	}

					// 	if (response == 0) return message_topright("error", "Data gagal disimpan");
					// 	if (response == 2) return messageNotSameLastUpdated()
					// }, "#edit_koreksi_draft_edit")
				}
			});

			// Swal.fire({
			// 	title: "Apakah anda yakin?",
			// 	text: "Pastikan data yang sudah anda input benar!",
			// 	icon: "warning",
			// 	showCancelButton: true,
			// 	confirmButtonColor: "#3085d6",
			// 	cancelButtonColor: "#d33",
			// 	confirmButtonText: "Ya, Edit",
			// 	cancelButtonText: "Tidak, Tutup"
			// }).then((result) => {
			// 	if (result.value == true) {
			// 		$.ajax({
			// 			url: "<?= base_url('WMS/KoreksiStokBarangDraft/edit_data_koreksi_draft') ?>",
			// 			type: "POST",
			// 			data: {
			// 				id: global_id,
			// 				gudang: gudang.val(),
			// 				principle: principle.val(),
			// 				checker: checker.text(),
			// 				tipe: tipe.val(),
			// 				status: status.val(),
			// 				keterangan: keterangan.val(),
			// 				kode: kode,
			// 				detail: arr_detail,
			// 				lastUpdated: $("#last_updated").val()
			// 			},
			// 			beforeSend: function() {
			// 				Swal.fire({
			// 					title: 'Loading ...',
			// 					html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
			// 					timerProgressBar: false,
			// 					showConfirmButton: false,
			// 					allowOutsideClick: false,
			// 				});

			// 				$("#edit_koreksi_draft_edit").prop("disabled", true);
			// 			},
			// 			dataType: "JSON",
			// 			success: function(data) {
			// 				$("#edit_koreksi_draft_edit").prop("disabled", false);
			// 				Swal.fire({
			// 					title: 'Loading ...',
			// 					html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
			// 					timerProgressBar: false,
			// 					showConfirmButton: false,
			// 					allowOutsideClick: false,
			// 					timer: 10
			// 				});
			// 				if (data == 1) {
			// 					message_topright("success", "Data berhasil disimpan");
			// 					setTimeout(() => {
			// 						location.href = "<?= base_url('WMS/KoreksiStokBarangDraft/KoreksiStokBarangDraftMenu') ?>";
			// 					}, 500);
			// 				}

			// 				if (data == 0) return message_topright("error", "Data gagal disimpan");
			// 				if (data == 2) messageNotSameLastUpdated()
			// 			},
			// 			error: function(xhr) {
			// 				$("#edit_koreksi_draft_edit").prop("disabled", false);
			// 				Swal.fire({
			// 					title: 'Loading ...',
			// 					html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
			// 					timerProgressBar: false,
			// 					showConfirmButton: false,
			// 					allowOutsideClick: false,
			// 					timer: 10
			// 				});
			// 			},
			// 		});
			// 	}
			// });
		}

	});

	$(document).on("click", "#kembali_koreksi_draft_edit", function() {
		setTimeout(() => {
			Swal.fire({
				title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
				showConfirmButton: false,
				timer: 500
			});
			location.href = "<?= base_url('WMS/KoreksiStokBarangDraft/KoreksiStokBarangDraftMenu') ?>";
		}, 500);
	})

	function getReferensiDokumen(value) {
		$('#konten-table').empty();

		if (value != '') {
			$('#koreksi_draft_no_referensi_dokumen').val('');
			$('#koreksi_draft_no_referensi_dokumen').attr('data-tipe-dokumen', value);
			$('#koreksi_draft_no_referensi_dokumen').prop('disabled', false);
		} else {
			$('#koreksi_draft_no_referensi_dokumen').val('');
			$('#koreksi_draft_no_referensi_dokumen').attr('data-tipe-dokumen', value);
			$('#koreksi_draft_no_referensi_dokumen').prop('disabled', true);
		}
	}

	function autoCompleteReferensiDokumen(event) {
		var value = event.value;
		var tableDatabase = $(event).attr('data-tipe-dokumen').split('-');

		$('#konten-table').empty();

		if (value != '') {

			$.ajax({
				async: false,
				type: 'POST',
				url: "<?= base_url('WMS/KoreksiStokBarangDraft/autoCompleteReferensiDokumen') ?>",
				data: {
					reffDokumen: value,
					tableName: tableDatabase[0],
					tableNameKode: tableDatabase[1],
					tipeDokumen: tableDatabase[2],
				},
				dataType: "JSON",
				success: function(response) {

					if (response.length > 0) {
						$.each(response, function(i, v) {
							$('#konten-table').append(`
                            <tr onclick="getReferensiDokumenByKode('${v.kode}')" style="cursor:pointer">
										<td class="col-xs-11">${v.kode}</td>
								</tr>
                            `)
						})
					}
				}
			});
		}
	}

	function getReferensiDokumenByKode(kode) {
		message("Success!", "Referensi Dokumen Berhasil Dipilih", "success");

		$('#koreksi_draft_no_referensi_dokumen').val(kode);
		$('#konten-table').empty();
	}

	function previewFile() {
		const file = document.querySelector('input[type=file]').files[0];
		const reader = new FileReader();

		$('#show-file').empty();
		$('#hide-file').hide();
		reader.addEventListener("load", function() {
			let result = reader.result.split(',');
			$('#show-file').append(`
                    <a class="text-primary klik-saya" style="cursor:pointer" data-url="${result[1]}" data-ex="${result[0]}">${file.name}</a>
                    <button type="button" data-toggle="tooltip" data-placement="top" title="hapus file" class="btndeletefile" style="border:none;background:transparent"><i class="fas fa-trash text-danger" style="cursor: pointer"></i></button>
				`);
		}, false);

		if (file) {
			reader.readAsDataURL(file);
		}
	}

	$(document).on('click', '.btndeletefile', function() {
		$('#show-file').empty();
		$("#file").val("");
		// $(".file-upload-wrapper").attr("data-text", "Select your file!");
	});
</script>