<script>
	let global_sku_id = [];
	loadingBeforeReadyPage()
	$(document).ready(function() {
		select2();

		$(document).on("input", ".numeric", function(event) {
			this.value = this.value.replace(/[^0-9]/g, '');
		});

		let count_sku = $("#list_data_tambah_pemusnahan_stok > tbody tr").length;
		$("#list_data_tambah_pemusnahan_stok > tfoot tr #total_detail_sku").html("<strong><h4>Total " + count_sku + " SKU</h4></strong>");
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

	function check_count_sku() {
		let total_sku = $("#list_data_tambah_pemusnahan_stok > tbody tr").length;
		if (total_sku == 0) {
			//hapus readonly perusahaan dan principle
			$("#gudang_pemusnahan_draft").removeAttr("disabled");
			$("#pemusnahan_draft_principle").removeAttr("disabled");
		} else {
			$("#gudang_pemusnahan_draft").attr("disabled", "disabled");
			$("#pemusnahan_draft_principle").attr("disabled", "disabled");
		}
	}

	$("#gudang_pemusnahan_draft").on("change", function() {
		$("#pilih_sku_pemusnahan_draft").attr("data-gudang-id", $(this).val());
		$("#pilih_sku_pemusnahan_draft").attr("data-gudang-txt", $(this).children("option").filter(":selected").text());
	});

	$("#pemusnahan_draft_principle").on("change", function() {
		let id = $(this).val();
		let txt = $(this).children("option").filter(":selected").text();
		$("#pilih_sku_pemusnahan_draft").attr("data-principle-id", id);
		$("#pilih_sku_pemusnahan_draft").attr("data-principle-txt", txt);
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/PemusnahanDraft/get_checker_by_principleId') ?>",
			data: {
				id: id
			},
			success: function(response) {
				let data = JSON.parse(response);
				$("#pemusnahan_draft_checker").empty();
				if (data.length > 0) {
					$("#pemusnahan_draft_checker").append("<option value=''>--Pilih Checker--</option>")
					$.each(data, function(i, v) {
						$("#pemusnahan_draft_checker").append(`<option value="${v.id}">${v.nama}</option>`);
					});
				} else {
					$("#pemusnahan_draft_checker").empty();
				}
			}
		});
	});

	$("#pemusnahan_draft_kendaraan").on("change", function() {
		let kendaraan_model = $(this).val();
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/PemusnahanDraft/get_nopol_by_kendaraan_model') ?>",
			data: {
				kendaraan_model: kendaraan_model
			},
			success: function(response) {
				let data = JSON.parse(response);
				$("#pemusnahan_draft_nopol").empty();
				if (data.length > 0) {
					$("#pemusnahan_draft_nopol").append("<option value=''>--Pilih No Polisi--</option>")
					$.each(data, function(i, v) {
						$("#pemusnahan_draft_nopol").append(`<option value="${v.kendaraan_id}">${v.kendaraan_nopol}</option>`);
					});
				} else {
					$("#pemusnahan_draft_nopol").empty();
				}
			}
		});
	});

	$("#pemusnahan_draft_approval").on("change", function(e) {
		e.target.checked ? $("#pemusnahan_draft_status").val($(this).val()) : $("#pemusnahan_draft_status").val("Draft");
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

	$(document).on("click", ".btn_pilih_sku", function() {
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
				url: "<?= base_url('WMS/PemusnahanDraft/get_data_sku_by_id') ?>",
				type: "POST",
				data: {
					id: arr_chk,
				},
				dataType: "JSON",
				success: function(data) {
					append_list_data_setelah_pilih_sku(data);
					$("#list_data_pilih_sku").modal("hide");
					// append_list_data_setelah_pilih_sku(data)
				}
			});
		}
	});

	function append_list_data_setelah_pilih_sku(data) {
		// $("#list_data_tambah_pemusnahan_stok > tbody").empty();
		if (data.length != 0) {
			$.each(data, function(i, v) {
				$("#list_data_tambah_pemusnahan_stok > tbody").append(`
            <tr>
                <td>${v.sku_kode} <input type="hidden" name="sku_stock_id" id="sku_stock_id" value="${v.id}"/></td>
                <td>${v.sku} <input type="hidden" name="sku_id" id="sku_id" value="${v.sku_id}"/></td>
                <td class="text-center">${v.brand}</td>
                <td class="text-center">${v.satuan}</td>
                <td class="text-center">${v.kemasan}</td>
                <td class="text-center">${v.ed}</td>
                <td class="text-center">${v.qty_available}</td>
                <td class="text-center">
                  <input type="text" class="form-control numeric" name="qty_plan" id="qty_plan" onchange="CheckQtyPlan('${v.sku_id}','${v.ed}',this)"/>
                </td>
                <td class="text-center">
                    <button type="button" data-toggle="tooltip" data-placement="top" title="hapus" data-id="${v.id}" class="deletesku" style="border:none;background:transparent"><i class="fas fa-trash text-danger" style="cursor: pointer"></i></button> 
                </td>
            </tr>
        `);
			});
		} else {
			$("#list_data_tambah_pemusnahan_stok > tbody").html(`<tr><td colspan="9" class="text-center text-danger">Data Kosong</td></tr>`);
		}

		check_count_sku();

		let count_sku = $("#list_data_tambah_pemusnahan_stok > tbody tr").length;
		$("#list_data_tambah_pemusnahan_stok > tfoot tr #total_detail_sku").html("<strong><h4>Total " + count_sku + " SKU</h4></strong>");


		//btn delete
		var arr_lokal_temp = [];
		$(document).on("click", ".deletesku", function() {
			let id = $(this).attr("data-id");

			arr_lokal_temp.push(id);
			$("#pilih_sku_pemusnahan_draft").attr('data-sku-id', arr_lokal_temp);
			$("#search_filter_data_sku").attr('data-sku-id', arr_lokal_temp);
			$(this).parent().parent().remove();

			check_count_sku();

			let count_sku = $("#list_data_tambah_pemusnahan_stok > tbody tr").length;
			$("#list_data_tambah_pemusnahan_stok > tfoot tr #total_detail_sku").html("<strong><h4>Total " + count_sku + " SKU</h4></strong>");
		});
	}

	function CheckQtyPlan(sku_id, ed, qty) {
		$("#list_data_tambah_pemusnahan_stok > tbody tr").each(function(i, v) {
			let table_sku_id = $(this).find("td:eq(1) input[type='hidden']").val();
			let qty_available = $(this).find("td:eq(6)").text();
			let ed_table = $(this).find("td:eq(5)").text();
			if ((table_sku_id == sku_id) && (ed == ed_table)) {
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
					$("#pilih_sku_pemusnahan_draft").removeAttr('data-sku-id');
					$("#search_filter_data_sku").removeAttr('data-sku-id');
					$(this).attr({
						disabled: false,
						checked: false
					});
				}
			});
		}

		let count_sku = $("#list_data_tambah_pemusnahan_stok > tbody tr").length;
		$("#list_data_tambah_pemusnahan_stok > tfoot tr #total_detail_sku").html("<strong><h4>Total " + count_sku + " SKU</h4></strong>");
	}

	$("#pilih_sku_pemusnahan_draft").on("click", function() {
		let depo_id = $("#depo").attr('data-id');
		let depo = $("#depo").val();
		let gudang = $(this).attr('data-gudang-id');
		let gudang_txt = $(this).attr('data-gudang-txt');
		let principle = $(this).attr('data-principle-id');
		let principle_txt = $(this).attr('data-principle-txt');

		let brand = $("#brand_sku").val();
		let sku_induk = $("#sku_induk_sku").val();
		let nama_sku = $("#nama_sku").val();
		let sku_kode_wms = $("#kode_wms_sku").val();
		let sku_kode_pabrik = $("#kode_pabrik_sku").val();

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
			$("#list_data_pilih_sku").modal('show');
			$("#depo_sku").attr('depo-sku-id', depo_id);
			$("#depo_sku").val(depo);
			$("#gudang_sku").attr('gudang-sku-id', gudang);
			$("#gudang_sku").val(gudang_txt);
			$("#principle_sku").attr('principle-sku-id', principle);
			$("#principle_sku").val(principle_txt);

			check_delete(sku_id);
			check_count_sku();

			//ajax to get brand and sku induk
			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/PemusnahanDraft/get_BrandAndSKUInduk') ?>",
				data: {
					principle_id: principle
				},
				dataType: "JSON",
				async: false,
				success: function(response) {

					$("#brand_sku").empty();
					$("#sku_induk_sku").empty();

					if (response.brand.length > 0) {
						$("#brand_sku").append(`<option value="">--Pilih Brand--</option>`);
						$.each(response.brand, function(i, v) {
							$("#brand_sku").append(`<option value="${v.id}">${v.nama}</option>`)
						});
					} else {
						$("#brand_sku").html("");
					}

					if (response.sku_induk.length > 0) {
						$("#sku_induk_sku").append(`<option value="">--Pilih SKU Induk--</option>`);
						$.each(response.sku_induk, function(i, v) {
							$("#sku_induk_sku").append(`<option value="${v.id}">${v.nama}</option>`);
						});
					} else {
						$("#sku_induk_sku").html("");
					}
				}
			});

			data_sku(depo_id, gudang, principle, brand, sku_induk, nama_sku, sku_kode_wms, sku_kode_pabrik);
		}
	});

	$(document).on("click", "#search_filter_data_sku", function() {
		let depo = $("#depo_sku").attr('depo-sku-id');
		let gudang = $("#gudang_sku").attr('gudang-sku-id');
		let principle = $("#principle_sku").attr('principle-sku-id');
		let brand = $("#brand_sku").val();
		let sku_induk = $("#sku_induk_sku").val();
		let nama_sku = $("#nama_sku").val();
		let sku_kode_wms = $("#kode_wms_sku").val();
		let sku_kode_pabrik = $("#kode_pabrik_sku").val();

		let res = $(this).attr('data-sku-id');
		let sku_id = "";
		if (res != null) {
			sku_id = res.split(",")
		}

		$("#loadingsearchsku").show();

		check_delete(sku_id);
		data_sku(depo, gudang, principle, brand, sku_induk, nama_sku, sku_kode_wms, sku_kode_pabrik);
	});

	function data_sku(depo, gudang, principle, brand, sku_induk, nama_sku, sku_kode_wms, sku_kode_pabrik) {
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/PemusnahanDraft/search_filter_chosen_sku') ?>",
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
				$("#loadingsearchsku").hide();
				if (response.length > 0) {
					if ($.fn.DataTable.isDataTable('#table_list_data_pilih_sku')) {
						$('#table_list_data_pilih_sku').DataTable().destroy();
					}
					$("#table_list_data_pilih_sku > tbody").empty();

					$.each(response, function(i, v) {
						let str = "";
						if (global_sku_id != '') {
							if (global_sku_id.includes(v.id)) {
								str += "<input type='checkbox' class='form-control check-item' disabled checked name='chk-data[]' id='chk-data[]' value='" + v.id + "'/>";
							} else {
								if (v.qty_available == 0) {
									str += "<input type='checkbox' class='form-control check-item' disabled checked name='chk-data[]' id='chk-data[]' value='" + v.id + "'/>";
								} else {
									str += "<input type='checkbox' class='form-control check-item' name='chk-data[]' id='chk-data[]' value='" + v.id + "'/>";
								}
							}
						} else {
							str += "<input type='checkbox' class='form-control check-item' name='chk-data[]' id='chk-data[]' value='" + v.id + "'/>";
						}

						$("#table_list_data_pilih_sku > tbody").append(`
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

					$('#table_list_data_pilih_sku').DataTable({
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
					$("#table_list_data_pilih_sku > tbody").html(`<tr><td colspan="9" class="text-center text-danger" name="CAPTION-DATAKOSONG">Data Kosong</td></tr>`);
				}
			}
		})
	}

	$(document).on("click", ".btn_close_list_data_pilih_sku", function() {
		$("#list_data_pilih_sku").modal('hide');
	});

	$(document).on("click", "#simpan_pemusnahan_draft", function() {
		let tgl = $("#pemusnahan_draft_tgl");
		let gudang = $("#gudang_pemusnahan_draft");
		let principle = $("#pemusnahan_draft_principle");
		let checker = $("#pemusnahan_draft_checker").children("option").filter(":selected");
		let ekspedisi = $("#pemusnahan_draft_ekspedisi").children("option").filter(":selected");
		let driver = $("#pemusnahan_draft_driver").children("option").filter(":selected");
		let kendaraan = $("#pemusnahan_draft_kendaraan").children("option").filter(":selected");
		let nopol = $("#pemusnahan_draft_nopol").children("option").filter(":selected");
		let tipe = $("#pemusnahan_draft_tipe_transaksi");
		let tipetransaksitext = $("#pemusnahan_draft_tipe_transaksi").children("option").filter(":selected");
		let status = $("#pemusnahan_draft_status");
		let keterangan = $("#pemusnahan_draft_keterangan");
		let arr_sku_stock_id = [];
		let arr_sku_id = [];
		let arr_qty_plan = [];
		let arr_detail = [];
		let driver2 = $('#pemusnahan_draft_driver2').val()
		let kendaraan2 = $('#divpemusnahan_draft_kendaraan2').val()
		let nopol2 = $('#pemusnahan_draft_nopol2').val()

		let valueekspedisi = '';
		let valuedriver = '';
		let valuekendaraan = '';
		let valuenopol = '';
		if (tipetransaksitext.text() == "Pemusnahan") {
			valueekspedisi = '';
			valuedriver = driver.text();
			valuekendaraan = kendaraan.text();
			valuenopol = nopol.text();
		}
		if (tipetransaksitext.text() == 'Retur Supplier') {
			valueekspedisi = ekspedisi.val();
			valuedriver = driver2;
			valuekendaraan = kendaraan2;
			valuenopol = nopol2;
		}
		if (gudang.val() == "") {
			message("Error!", "Gudang tidak boleh kosong", "error");
			$("#count_error").val("1");
			return false;
		} else if (principle.val() == "") {
			message("Error!", "Principle tidak boleh kosong", "error");
			$("#count_error").val("1");
			return false;
		} else if (checker.val() == "") {
			message("Error!", "Checker tidak boleh kosong", "error");
			$("#count_error").val("1");
			return false;
		} else if (tipe.val() == "") {
			message("Error!", "Tipe Transaksi tidak boleh kosong", "error");
			$("#count_error").val("1");
			return false;
		} else {
			let count_pallet = $("#list_data_tambah_pemusnahan_stok > tbody tr").length;
			if (count_pallet == 0) {
				message("Error!", "SKU masih kosong, silahkan tambah sku terlebih dahulu", "error");
				$("#count_error").val("1");
				return false;
			} else {
				$("#list_data_tambah_pemusnahan_stok > tbody tr").each(function() {
					let sku_stock_id = $(this).find("td:eq(0) input[type='hidden']");
					let sku_id = $(this).find("td:eq(1) input[type='hidden']");
					let qty_plan = $(this).find("td:eq(7) input[type='text']");
					if (qty_plan.val() == "") {
						message("Error!", "Qty Plan pemusnahan tidak boleh kosong", "error");
						$("#count_error").val("1");
						return false;
					} else {
						$("#count_error").val("0");

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

		let error = $("#count_error");
		if (error.val() != 0) {
			// return false;

			console.log(error.val());
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

			Swal.fire({
				title: "Apakah anda yakin?",
				text: "Pastikan data yang sudah anda input benar!",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: "Ya, Simpan",
				cancelButtonText: "Tidak, Tutup"
			}).then((result) => {
				if (result.value == true) {
					$.ajax({
						url: "<?= base_url('WMS/PemusnahanDraft/save_data_pemusnahan_draft') ?>",
						type: "POST",
						data: {
							tgl: tgl.val(),
							gudang: gudang.val(),
							principle: principle.val(),
							checker: checker.text(),
							ekspedisi: valueekspedisi,
							driver: valuedriver,
							kendaraan: valuekendaraan,
							nopol: valuenopol,
							tipe: tipe.val(),
							status: status.val(),
							keterangan: keterangan.val(),
							detail: arr_detail
						},
						dataType: "JSON",
						success: function(data) {
							if (data == 1) {
								message_topright("success", "Data berhasil disimpan");
								setTimeout(() => {
									location.href = "<?= base_url('WMS/PemusnahanDraft/PemusnahanDraftMenu') ?>";
								}, 500);
							} else {
								message_topright("error", "Data gagal disimpan");
							}
						},
					});
				}
			});
		}

	});

	$(document).on("click", "#kembali_pemusnahan_draft", function() {
		setTimeout(() => {
			Swal.fire({
				title: '<span ><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>',
				showConfirmButton: false,
				timer: 500
			});
			location.href = "<?= base_url('WMS/PemusnahanDraft/PemusnahanDraftMenu') ?>";
		}, 500);
	})

	// **new
	$(document).on('change', '#pemusnahan_draft_tipe_transaksi', function() {
		if ($("#pemusnahan_draft_tipe_transaksi").children("option").filter(":selected").text() == 'Pemusnahan') {
			$('#divhideshow').fadeIn()
			$('#divpemusnahan_draft_ekspedisi').hide()
			$('#divpemusnahan_draft_driver').fadeIn()
			$('#divpemusnahan_draft_kendaraan').fadeIn()
			$('#divpemusnahan_draft_nopol').fadeIn()


			$('#divpemusnahan_draft_driver2').hide()
			$('#divdivpemusnahan_draft_kendaraan2').hide()
			$('#divpemusnahan_draft_nopol2').hide()
		}
		if ($("#pemusnahan_draft_tipe_transaksi").children("option").filter(":selected").text() == 'Retur Supplier') {
			$('#divhideshow').fadeIn()
			$('#divpemusnahan_draft_ekspedisi').fadeIn()
			$('#divpemusnahan_draft_driver').hide()
			$('#divpemusnahan_draft_kendaraan').hide()
			$('#divpemusnahan_draft_nopol').hide()

			$('#divpemusnahan_draft_driver2').fadeIn()
			$('#divdivpemusnahan_draft_kendaraan2').hide()
			$('#divpemusnahan_draft_nopol2').fadeIn()
		}
	})
</script>