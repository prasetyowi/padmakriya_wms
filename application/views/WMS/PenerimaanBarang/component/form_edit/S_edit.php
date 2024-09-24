<script type="text/javascript">
	const penerimaan_pembelian_id = $("#penerimaan_pembelian_id").val();
	const session_depo = $("#session_depo_edit").val();
	const client_wms_id = $("#client_wms_id").val();
	const principle_id = $("#principle_id").val();
	let global_data = [];
	loadingBeforeReadyPage()
	$(document).ready(function() {
		select2();

		get_sj_id();

		let params = JSON.parse($("#sj_id").val());

		get_data_pallet();

		get_data_surat_jalan_detail(params);

		check_sisa();

		get_perusahaan();

		get_principle();

		get_sku_in_pallet_temp(params);

	});

	function get_sj_id() {

		let arr_sj_id = [];
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanBarang/get_sj_id') ?>",
			type: "POST",
			data: {
				id: penerimaan_pembelian_id
			},
			dataType: "JSON",
			async: false,
			success: function(data) {
				if (data.length > 0) {
					$.each(data, (index, value) => {
						arr_sj_id.push(value.id);
					})
				}
			}
		});

		$("#sj_id").val(JSON.stringify(arr_sj_id));
	}

	function get_sku_in_pallet_temp(params) {
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanBarang/get_sku_in_pallet_temp'); ?>",
			type: "POST",
			data: {
				id: params
			},
			dataType: "JSON",
			success: function(data) {
				if (data != null) {
					$.each(data, (i, v) => {
						global_data.push(v.sku_id);
					})
				}
			}
		});
	}

	// function message(msg, msgtext, msgtype) {
	//   Swal.fire(msg, msgtext, msgtype);
	// }

	// function message_topright(type, msg) {
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


	function select2() {
		$(".select2").select2({
			width: "100%"
		});
	}

	// function message(msg, msgtext, msgtype) {
	// 	Swal.fire(msg, msgtext, msgtype);
	// }

	function get_perusahaan() {
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanBarang/get_perusahaan'); ?>",
			type: "POST",
			data: {
				id: client_wms_id,
			},
			dataType: "JSON",
			success: function(data) {
				$("#perusahaan_filter_gudang_edit").empty();
				$("#perusahaan_filter_gudang_edit").append(`<option value="${data.id}">${data.nama}</option>`);
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
				$("#principle_filter_gudang_edit").empty();
				$("#principle_filter_gudang_edit").append(`<option value="${data.id}">${data.nama}</option>`);
			}
		});
	}

	$(document).on("change", "#tipe_filter_gudang_edit", function() {
		let tipe_stock = $(this).val();
		if (id != "") {
			$.ajax({
				url: "<?= base_url('WMS/PenerimaanBarang/get_area_rak_gudang'); ?>",
				type: "POST",
				data: {
					tipe_stock: tipe_stock,
					client_wms: client_wms_id,
					principle: principle_id
				},
				dataType: "JSON",
				success: function(data) {
					console.log(data);
				}
			});
		}
	});

	function get_data_pallet() {
		let params = JSON.parse($("#sj_id").val());
		// let id = $("#tambah_pallet").attr('data-id');
		let arr_pallet = [];
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanBarang/get_data_palet'); ?>",
			type: "POST",
			data: {
				id: params
			},
			dataType: "JSON",
			async: false,
			success: function(data) {
				// console.log(data);
				$("#data_pallet_edit > tbody").empty();
				if (data.data.length > 0) {
					$.each(data.data, function(i, v) {
						arr_pallet.push(v.id)
						// $("#global_pallet_id").attr();
						let is_chosen = "";
						if (v.flag == 1) {
							is_chosen += "<i class='fas fa-check text-success'></i>";
						} else {
							is_chosen += "<i class='fas fa-xmark text-danger'></i>";
						}
						$("#data_pallet_edit > tbody").append(`
                <tr>
                    <td class="text-center">${i + 1}</td>
                    <td>
                        <input type="text" class="form-control" value="Auto" readonly/>
                        <input type="hidden" class="form-control" name="id_pallet[]" id="id_pallet" value="` + v.id + `"/>
                    </td>
                    <td>
                        <select class="form-control select2 jenis_palet" id="jenis_palet_` + i + `" name="jenis_palet"></select>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-primary chosen_warehouse_edit" data-id="${v.id}" data-depo-detail-id="${v.depo_detail_id}"> <i class="fas fa-warehouse"></i> Pilih Gudang</button>
                        ${is_chosen}
                        <input type="hidden" class="form-control" name="gudang_area[]" id="gudang_area" value="${v.flag}"/>
                    </td>
                    <td class="text-center">
                        <button type="button" data-toggle="tooltip" data-placement="top" title="hapus" data-id="` + v.id + `" data-counter="${i + 1}" class="delete_edit" style="border:none;background:transparent"><i class="fas fa-trash text-danger" style="cursor: pointer"></i>
                        </button>

                        <button type="button" data-toggle="tooltip" data-placement="top" title="isi sku" data-id="` + v.id + `" data-counter="${i + 1}" class="isi_sku_edit" style="border:none;background:transparent"><i class="fas fa-plus text-primary" style="cursor: pointer"></i></button>
                    </td>
                </tr>
            `);
					});

				}

				select2();

				let tot_pallet = $("#data_pallet_edit > tbody tr").length;
				$("#data_pallet_edit > tfoot tr #total_pallet_edit").html("<strong><h4>Total " + tot_pallet + " Pallet</h4></strong>");

				for (i = 0; i < data.data.length; i++) {
					var pallet_jenis_id = data.data[i].jenis_id;


					$("#jenis_palet_" + i).empty();

					$("#jenis_palet_" + i).append('<option value="">--Pilih Jenis--</option>');
					if (data.pallet != 0) {
						for (x = 0; x < data.pallet.length; x++) {
							slc_pallet_jenis_id = data.pallet[x].id;
							slc_pallet_jenis_nama = data.pallet[x].nama;
							if (pallet_jenis_id == slc_pallet_jenis_id) {
								$("#jenis_palet_" + i).append('<option value="' + slc_pallet_jenis_id + '" selected>' + slc_pallet_jenis_nama + '</option>');
							} else {
								$("#jenis_palet_" + i).append('<option value="' + slc_pallet_jenis_id + '">' + slc_pallet_jenis_nama + '</option>');
							}

						}
					}
				}

				update_jenis_pallet_by_id();

				// update_gudang_tujuan_by_id();
			}
		});


		$("#global_pallet_id_edit").attr("data-pallet", JSON.stringify(arr_pallet));
	}

	function update_jenis_pallet_by_id() {
		$("#data_pallet_edit > tbody tr").each(function(i, v) {
			let currentRow = $(this);
			let id = currentRow.find("td:eq(1) input[type='hidden']").val();
			let jenis_palet = currentRow.find("td:eq(2) select");
			jenis_palet.change(function() {
				let jenis_palet_id = $(this).children("option").filter(":selected").val();
				$.ajax({
					url: "<?= base_url('WMS/PenerimaanBarang/update_jenis_pallet_by_id'); ?>",
					type: "POST",
					data: {
						id: id,
						jenis_palet_id: jenis_palet_id
					},
					dataType: "JSON",
					success: function(data) {}
				});
			});
		});
	}

	$(document).on("click", ".chosen_warehouse_edit", function() {
		let id = $(this).attr('data-id');
		let depo_detail_id = $(this).attr('data-depo-detail-id');
		$("#modal_pilih_gudang_edit").modal('show');

		$(".btn_pilih_gudang_edit").attr("data-id", id);
		$(".btn_pilih_gudang_edit").attr("data-depo", depo_detail_id);

		requestGetWidthAndLengthDepo(id, depo_detail_id);
	});

	function requestGetWidthAndLengthDepo(id, depo_detail_id) {
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanBarang/get_width_and_lenght_by_depo'); ?>",
			type: "POST",
			data: {
				id: session_depo,
			},
			dataType: "JSON",
			async: false,
			success: function(response) {
				ViewMapDepoDetailMenu(response, id, depo_detail_id);
			}
		});
	}

	function ViewMapDepoDetailMenu(response, id, depo_detail_id) {

		$("#divDepoDetail_edit").html('');

		var strmenu = '';

		strmenu += '	<div id="Depo_Luas_edit">';
		strmenu += '	    <div style="width: ' + response.depo_width + 'px;border: solid black 1px; position: absolute;left:0;right:0;margin-left:auto;margin-right:auto;height: 450px;">';
		strmenu += '		    <div id="shape-placeholder_edit">';
		strmenu += '		    </div>';
		strmenu += '		</div>';
		strmenu += '	</div>';

		$("#divDepoDetail_edit").append(strmenu);

		// $("#shape-placeholder").attr('style', 'width: ' + response.depo_width + 'px; border: solid black 1px; position: absolute;left:0;right:0;margin-left:auto;margin-right:auto;');
		$("#Depo_Luas_edit").attr('style', 'width: ' + response.depo_width + 'px; height: 450px;');

		GetDepoDetailMenu(id, depo_detail_id);
	}

	function GetDepoDetailMenu(id, depo_detail_id) {
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
					ChDepoDetailMenu(response, id, depo_detail_id);
				}
			}
		});
	}

	function ChDepoDetailMenu(response, id, depo_detail_id) {

		if (response.length > 0) {
			$.each(response, function(i, v) {
				let fontstyle = 'font-weight: bold;';
				let borderstyle = 'border: solid black 1px;';
				let css = 'width: ' + v.depo_detail_width + 'px; height: ' + v.depo_detail_length + 'px; color: ' + v.depo_detail_warna_font + '; ' + fontstyle + ' left: ' + v.depo_detail_x + 'px; top: ' + v.depo_detail_y + 'px; ';

				$("#shape-placeholder_edit").append(`
            <input class="checkbox-tools update_gudang_tujuan_by_id_edit" type="radio" name="tools" data-depo-detail-id="${v.depo_detail_id}" data-id="${id}" id="tool-${i}">
            <label class="for-checkbox-tools" style="${css}" for="tool-${i}">
                ${v.depo_detail_nama}
            </label>
        `);
			});

			$("#shape-placeholder_edit :input[name='tools']").each(function(i, v) {
				let id = $(this).attr('data-depo-detail-id');
				if (depo_detail_id == id) {
					$(this).attr('checked', true);
				}
			});
		}
	}

	$(document).on("click", ".update_gudang_tujuan_by_id_edit", function() {
		let depo_detail_id = $(this).attr('data-depo-detail-id');
		let id = $(this).attr('data-id');
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanBarang/update_gudang_tujuan_by_id'); ?>",
			type: "POST",
			data: {
				id: id,
				gudang_tujuan_id: depo_detail_id
			},
			dataType: "JSON",
			success: function(data) {}
		});
	})

	$(document).on("click", ".btn_pilih_gudang_edit", function() {
		let id = $(this).attr('data-id');
		let depo = $(this).attr('data-depo');
		let chk = $("#shape-placeholder_edit :input[name='tools']:checked").length;
		if (chk == 0) {
			message('Error!', 'Tidak ada gudang yg dipilih, silahkan pilih terlebih dahulu!', 'error');
			return false;
		} else {
			$("#modal_pilih_gudang_edit").modal('hide');
			requestGetWidthAndLengthDepo(id, depo);

			$('#data_pallet_edit').fadeOut("slow", function() {
				$(this).hide();
			}).fadeIn("slow", function() {
				$(this).show();
				get_data_pallet();
			});

			message_topright('success', 'Gudang berhasil dipilih');
		}
	});

	$(document).on("click", ".btn_close_list_pilih_gudang_edit", function() {
		$("#modal_pilih_gudang_edit").modal('hide');
	});

	let counter = 0;
	$(document).on("click", ".isi_sku_edit", function() {
		let id = $(this).attr('data-id');
		let is_count = $(this).attr('data-counter');
		counter++;
		if (counter == 1) {
			show_isi_sku(id, is_count);
		} else if (counter > 1) {
			let count_in = 0;
			$("#list_detail_pallet_edit > tbody tr").each(function(i, v) {
				let first_element = $(this).find("td:eq(0)");
				if (first_element.text() != "Data Kosong") {
					let ed = $(this).find("td:eq(6) input[type='date']");
					let qty = $(this).find("td:eq(11) input[type='text']");
					if (ed.val() == "") {
						message('Error!', 'Silahkan isi Expired Date terlebih dahulu jika ingin pindah isi pallet', 'error');
						return false;
					} else if (qty.val() == 0) {
						message('Error!', 'Silahkan isi Jumlah barang terlebih dahulu jika ingin pindah isi pallet', 'error');
						return false;
					} else {
						count_in = 1;
					}
				} else {
					count_in = 1;
				}
			});

			if (count_in == 1) {
				show_isi_sku(id, is_count);
			}
		} else {
			show_isi_sku(id, is_count);
		}
	});

	function show_isi_sku(id, is_count) {
		$('#show_isi_sku_pallet_edit').fadeOut("slow", function() {
			$(this).hide();
			$("#list_detail_pallet_edit > tbody").html(`<tr><td colspan="13" class="text-center"><span ><i class="fa fa-spinner fa-spin"></i> Loading...</span></td></tr>`);
		}).fadeIn("slow", function() {
			$(this).show();
			append_list_data_setelah_pilih_sku(id, is_count);
			count_tot_detail_pallet();
		});
		$("#add-sku-edit").attr('data-pallet-id', id);
		$(".btn_pilih_sku_edit").attr("data-counter", is_count);
	}

	$(document).on("click", "#add-sku-edit", function() {
		let id = $(this).attr('data-pallet-id');
		$("#list_detail_pallet_edit > tbody tr").each(function(i, v) {
			let first_element = $(this).find("td:eq(0)");
			if (first_element.text() != "Data Kosong") {
				let ed = $(this).find("td:eq(6) input[type='date']");
				let tipe = $(this).find("td:eq(7) select").children("option").filter(":selected");
				let qty = $(this).find("td:eq(11) input[type='text']");
				if (ed.val() == "") {
					message('Error!', 'Silahkan isi Expired Date terlebih dahulu jika ingin menambah sku', 'error');
					return false;
				} else if (tipe.val() == '') {
					message('Error!', 'Silahkan isi Tipe terlebih dahulu jika ingin menambah sku', 'error');
					return false;
				} else if (qty.val() == 0) {
					message('Error!', 'Silahkan isi Jumlah barang terlebih dahulu jika ingin menambah sku', 'error');
					return false;
				} else {
					show_add_sku(id);
				}
			} else {
				show_add_sku(id);
			}
		});
	});

	function show_add_sku(id) {
		$("#modal_pilih_sku_edit").modal("show");
		$(".btn_pilih_sku_edit").attr("data-pallet-id", id);
		let count = $("#tablelistdatadetailsjedit > tbody tr").length;
		if (count != 0) {
			if ($.fn.DataTable.isDataTable('#table_list_pilih_sku_edit')) {
				$('#table_list_pilih_sku_edit').DataTable().destroy();
			}
			$("#table_list_pilih_sku_edit > tbody").empty();
			$("#tablelistdatadetailsjedit > tbody tr").each(function(i, v) {
				let currentRow = $(this);
				let sjd_id = currentRow.find("td:eq(0) input[type='hidden']").val();
				let sku_id = currentRow.find("td:eq(1) input[type='hidden']").val();
				let sku_kode = currentRow.find("td:eq(1)").text();
				let sku = currentRow.find("td:eq(2)").text();
				let kemasan = currentRow.find("td:eq(3)").text();
				let satuan = currentRow.find("td:eq(4)").text();
				let tipe = currentRow.find("td:eq(5)").text();
				let tipe_id = currentRow.find("td:eq(5) input[type='hidden']").val();
				let ed = currentRow.find("td:eq(6)").text();
				let jumlah_barang = currentRow.find("td:eq(7)").text();
				let sisa = currentRow.find("td:eq(9)").text();
				let readonly = "";
				if (sisa == 0) {
					readonly += "disabled checked";
				} else {
					readonly += "";
				}
				$("#table_list_pilih_sku_edit > tbody").append(`
          <tr>
              <td width="5%" class="text-center">
                  <input type="checkbox" class="form-control check-item-edit" ${readonly} name="chk-data-edit[]" style="transform: scale(1.5)" id="chk-data-edit[]" value="${sku_id}"/>
              </td>
              <td width="10%">${sku_kode}</td>
              <td width="25%">${sku}</td>
              <td width="10%" class="text-center">${kemasan}</td>
              <td width="10%" class="text-center">${satuan}</td>
              <td class="text-center">${ed}</td>
              <td width="10%" class="text-center">${tipe} <input type="hidden" class="form-control" value="${tipe_id}"/></td>
              <td width="10%" class="text-center">${jumlah_barang}</td>
              <td width="7%" class="text-center">${sisa}</td>
          </tr>
      `);
			});

			$('#table_list_pilih_sku_edit').DataTable({
				columnDefs: [{
					sortable: false,
					targets: [0, 1, 2, 3, 4, 5, 6, 7]
				}],
				lengthMenu: [
					[-1],
					['All']
				],
			});
		} else {
			$("#table_list_pilih_sku_edit > tbody").html(`<tr><td colspan="8" class="text-center text-danger">Data Kosong</td></tr>`);
		}
	}

	$(document).on("click", ".btn_close_list_pilih_sku_edit", function() {
		$("#modal_pilih_sku_edit").modal("hide");
	});

	$(document).on("click", ".delete_edit", function() {
		let params = JSON.parse($("#sj_id").val());
		let id = $(this).attr('data-id');
		let is_count = $(this).attr('data-counter');
		// let doc_fob = $("#tambah_pallet").attr('data-id');
		let delete_row = $(this).parent().parent();
		Swal.fire({
			title: "Apakah anda yakin?",
			text: "Jika menghapus data ini, maka data yang berkaitan dengan data ini akan terhapus",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Hapus",
			cancelButtonText: "Tidak, Tutup"
		}).then((result) => {
			if (result.value == true) {
				$.ajax({
					url: "<?= base_url('WMS/PenerimaanBarang/delete_pallet'); ?>",
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
							delete_row.remove();
							get_data_pallet();
							get_data_surat_jalan_detail(params);
							counter--;
							let count_pallet = $("#data_pallet_edit > tbody tr").length;
							if (count_pallet != 0) {
								$('#show_isi_sku_pallet_edit').fadeOut("slow", function() {
									$(this).hide();
								}).fadeIn("slow", function() {
									$(this).show();
									let idx = $("#data_pallet_edit > tbody tr").length - 1;
									let pallet = $(".isi_sku_edit").eq(idx).attr('data-id');
									show_isi_sku(pallet, is_count - 1);
									get_sku_in_pallet_temp(params);
								});
							} else {
								$('#show_isi_sku_pallet_edit').fadeOut("slow", function() {
									$(this).hide();
								});
							}

						}
					}
				});
			}
		});
	});

	function checkAllSKUEdit(e) {
		var checkboxes = $("input[name='chk-data-edit[]']");
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
		let params = JSON.parse($("#sj_id").val());
		let pallet_id = $(this).attr('data-pallet-id');
		let is_count = $(this).attr('data-counter');
		let arr_chk = [];
		var checkboxes = $("input[name='chk-data-edit[]']");
		for (var i = 0; i < checkboxes.length; i++) {
			if (checkboxes[i].checked == true && !(checkboxes[i].disabled)) {
				arr_chk.push(checkboxes[i].value);
			}
		}

		if (arr_chk.length == 0) {
			message("Info!", "Pilih data yang akan dipilih", "info");
		} else {
			$.ajax({
				url: "<?= base_url('WMS/PenerimaanBarang/save_data_to_pallet_detail_temp') ?>",
				type: "POST",
				data: {
					sku_id: arr_chk,
					sj_id: params,
					pallet_id: pallet_id,
				},
				dataType: "JSON",
				success: function(data) {
					if (data == true) {
						append_list_data_setelah_pilih_sku(pallet_id, is_count);
						get_sku_in_pallet_temp(params);
						$("#modal_pilih_sku_edit").modal("hide");
					}
				}
			});
		}
	});

	function append_list_data_setelah_pilih_sku(pallet_id, is_count) {
		let params = JSON.parse($("#sj_id").val());
		// let id = $("#tambah_pallet").attr('data-id');
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanBarang/get_surat_jalan_detail_by_arrId'); ?>",
			type: "POST",
			data: {
				id: pallet_id
			},
			dataType: "JSON",
			async: false,
			success: function(data) {
				$(".detail_pallet_ke_edit").empty();
				$(".detail_pallet_ke_edit").append(`Pallet urutan ke ${is_count}`);
				$("#list_detail_pallet_edit > tbody").empty();
				if (data.length != 0) {
					$.each(data, function(i, v) {
						let expired_date = v.expired_date == null ? v.sku_exp_date : v.expired_date;
						let qty = v.qty == null ? "" : v.qty;

						let jmlh_barang = ""
						let jmlh_sisa = ""

						$("#tablelistdatadetailsjedit > tbody tr").each(function() {
							let sku_id = $(this).find("td:eq(1) input[type='hidden']").val();
							let ed = $(this).find("td:eq(6)").text();
							let jml_barang_table = $(this).find("td:eq(7)").text();
							let jml_terima_table = $(this).find("td:eq(8)").text();
							let jml_sisa_table = $(this).find("td:eq(9)").text();

							if ((v.sku_id === sku_id) && (expired_date === ed)) {
								jmlh_barang = jml_barang_table
								jmlh_sisa = jml_sisa_table
							}

						});

						$("#list_detail_pallet_edit > tbody").append(`
                <tr>
                    <td>${v.principle} <input type="hidden" class="form-control" name="pallet_detail_id" id="pallet_detail_id" value="` + v.pallet_detail_id + `"/></td>
                    <td>${v.sku_kode}</td>
                    <td>${v.sku_nama_produk}</td>
                    <td class="text-center">${v.sku_kemasan}</td>
                    <td class="text-center">${v.sku_satuan}</td>
                    <td>
                        <input type="date" class="form-control exp_date_sj_edit" id="exp_date_sj_edit" name="exp_date_sj_edit" value="${v.sku_exp_date}" readonly/>
                    </td>
                    <td>
                        <input type="date" class="form-control exp_date" id="exp_date" name="exp_date" value="${expired_date}"/>
                    </td>
                    <td class="text-center">${v.tipe_nama}</td>
                    <td class="text-center">${jmlh_barang}</td>
                    <td class="text-center">${qty}</td>
                    <td class="text-center">${jmlh_sisa}</td>
                    <td>
                        <input type="text" class="form-control qty_detail_pallet_edit numeric" id="qty_detail_pallet_edit" name="qty_detail_pallet_edit" value="${qty}" onchange="UpdateQtySkuEdit('${pallet_id}','${v.pallet_detail_id}','${v.sku_id}', '${is_count}',this)"/>
                    </td>
                    <td class="text-center">
                        <button type="button" data-toggle="tooltip" data-placement="top" data-pallet-id="${pallet_id}" data-counter="${is_count}" data-id="${v.pallet_detail_id}" title="hapus" class="delete_detail_edit" style="border:none;background:transparent"><i class="fas fa-trash text-danger" style="cursor: pointer"></i></button> 
                    </td>
                </tr>
            `);
					});
				} else {
					$("#list_detail_pallet_edit > tbody").html(`<tr><td colspan="13" class="text-center text-danger">Data Kosong</td></tr>`);
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

	function count_tot_detail_pallet() {
		let no = 0;
		$("#list_detail_pallet_edit > tbody tr").each(function(i, v) {
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
		$("#list_detail_pallet_edit > tfoot tr #total_detail_pallet_edit").html("<strong><h4>Total " + no + " SKU</h4></strong>");
	}

	function UpdateQtySkuEdit(pallet_id, pallet_detail_id, sku_id, qty, is_count) {
		if (qty.value == 0 || qty.value == "") {
			ajax_request_update_qty_sku(pallet_id, pallet_detail_id, qty, is_count);
		} else {
			$("#tablelistdatadetailsjedit > tbody tr").each(function(i, v) {
				let table_sku_id = $(this).find("td:eq(1) input[type='hidden']").val();
				let sisa = $(this).find("td:eq(9)").text();
				if (table_sku_id == sku_id) {
					if (parseInt(qty.value) > parseInt(sisa)) {
						message('Error!', 'Jumlah barang melebihi sisa, sisa jumlah barang dalam SKU <strong>' + parseInt(sisa) + '</strong>', 'error');
						qty.value = '';
						return false;
					} else {
						ajax_request_update_qty_sku(pallet_id, pallet_detail_id, qty, is_count);
					}
				}
			});
		}
	}

	function ajax_request_update_qty_sku(pallet_id, pallet_detail_id, qty, is_count) {
		let params = JSON.parse($("#sj_id").val());
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanBarang/update_qty_pallet_detail_by_id'); ?>",
			type: "POST",
			data: {
				id: pallet_detail_id,
				qty: qty.value
			},
			dataType: "JSON",
			success: function(data) {
				$('#showfilterdataedit').fadeOut("slow", function() {
					$(this).hide();
					$("#tablelistdatadetailsjedit > tbody").html(`<tr><td colspan="10" class="text-center"><span ><i class="fa fa-spinner fa-spin"></i> Loading...</span></td></tr>`);
					$(".qty_detail_pallet_edit").attr('readonly', true);
					// let timerInterval
					// Swal.fire({
					//   title: '<span ><i class="fa fa-spinner fa-spin"></i> Tunggu sebentar...</span>',
					//   showConfirmButton: false,
					//   timer: 1200
					// });
				}).fadeIn("slow", function() {
					$(this).show();
					get_data_surat_jalan_detail(params);
					$(".qty_detail_pallet_edit").attr('readonly', false);
					// swal.close();
				});

				$('#tablelistdatadetailsjedit').fadeOut("slow", function() {
					$(this).hide();
				}).fadeIn("slow", function() {
					get_data_surat_jalan_detail(params);
					$(this).show();
				});

				$('#show_isi_sku_pallet_edit').fadeOut("slow", function() {
					$(this).hide();
					get_data_surat_jalan_detail(params);
				}).fadeIn("slow", function() {
					$(this).show();
					get_data_surat_jalan_detail(params);
					append_list_data_setelah_pilih_sku(pallet_id, is_count)
				});
			},
		});
	}

	function update_pallet_detail_by_id() {
		$("#list_detail_pallet_edit > tbody tr").each(function(i, v) {
			let currentRow = $(this);
			let id = currentRow.find("td:eq(0) input[type='hidden']").val();
			let exp_date = currentRow.find("td:eq(6) input[type='date']");
			let tipe = currentRow.find("td:eq(7) select");
			let qty = currentRow.find("td:eq(11) input[type='text']");

			//update expired date di tabel pallet_detail_temp
			exp_date.change(function() {
				let exp_date_val = $(this).val();
				$.ajax({
					url: "<?= base_url('WMS/PenerimaanBarang/update_ed_pallet_detail_by_id'); ?>",
					type: "POST",
					data: {
						id: id,
						ed: exp_date_val
					},
					dataType: "JSON",
					success: function(data) {}
				});
			});

			//update penerimaan tipe id di tabel pallet_detail_temp
			tipe.change(function() {
				let tipe_id = $(this).children("option").filter(":selected").val();
				$.ajax({
					url: "<?= base_url('WMS/PenerimaanBarang/update_tipe_pallet_detail_by_id'); ?>",
					type: "POST",
					data: {
						id: id,
						tipe_id: tipe_id
					},
					dataType: "JSON",
					success: function(data) {}
				});
			});
		});
	}

	$(document).on("click", ".delete_detail_edit", function() {
		let params = JSON.parse($("#sj_id").val());
		let pallet_id = $(this).attr('data-pallet-id');
		let is_count = $(this).attr('data-counter');
		// let do_id = $("#tambah_pallet").attr('data-id');
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
							get_sku_in_pallet_temp(params);
							$('#showfilterdataedit').fadeOut("slow", function() {
								$(this).hide();
								$("#tablelistdatadetailsjedit > tbody").html(`<tr><td colspan="10" class="text-center"><span ><i class="fa fa-spinner fa-spin"></i> Loading...</span></td></tr>`);
							}).fadeIn("slow", function() {
								$(this).show();
								get_data_surat_jalan_detail(params)
							});
						}
					}
				});
			}
		});
	});

	function get_data_surat_jalan_detail(params) {
		let pallet_id = $("#global_pallet_id_edit").attr('data-pallet');
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanBarang/get_surat_jalan_detail') ?>",
			type: "POST",
			data: {
				id: params,
				pallet_id: JSON.parse(pallet_id)
			},
			dataType: "JSON",
			beforeSend: function() {
				$("#tablelistdatadetailsjedit > tbody").html(`<tr><td colspan="10" class="text-center"><span ><i class="fa fa-spinner fa-spin"></i> Loading...</span></td></tr>`);
			},
			success: function(data) {
				$("#showfilterdataedit").show();
				if (data != null) {
					if ($.fn.DataTable.isDataTable('#tablelistdatadetailsjedit')) {
						$('#tablelistdatadetailsjedit').DataTable().destroy();
					}
					$('#tablelistdatadetailsjedit > tbody').empty();
					$.each(data, function(i, v) {
						$("#tablelistdatadetailsjedit > tbody").append(`
                <tr>
                    <td class="text-center">${i + 1}</td>
                    <td class="text-center">${v.sku_kode} <input type="hidden" class="form-control" name="sku_id[]" value="${v.sku_id}"/></td>
                    <td>${v.sku_nama_produk}</td>
                    <td class="text-center">${v.sku_kemasan}</td>
                    <td class="text-center">${v.sku_satuan}</td>
                    <td class="text-center">${v.tipe} <input type="hidden" class="form-control" name="tipe_id[]" value="${v.tipe_id}"/></td>
                    <td class="text-center">${v.sj_ed}</td>
                    <td class="text-center">${v.sjd_jumlah_barang} <input type="hidden" class="form-control" name="jml_barang_real[]" value="${v.sjd_jumlah_barang}"/></td>
                    <td class="text-center">${v.jumlah_terima}</td>
                    <td class="text-center">${v.sjd_jumlah_barang - v.jumlah_terima}</td>
                </tr>
            `);
					});
				} else {
					$("#tablelistdatadetailsjedit > tbody").html(`<tr><td colspan="10" class="text-center text-danger">Data Kosong</td></tr>`);
				}

				$('#tablelistdatadetailsjedit').DataTable({
					lengthMenu: [
						[50, 100, -1],
						[50, 100, 'All']
					],
				});

				//cek jika sisa masih ada maka tidak bisa di simpan
				check_sisa();
			}
		});

	}

	function check_sisa() {
		let sisa_temp = 0;
		$("#tablelistdatadetailsjedit > tbody tr").each(function(i, v) {
			let sisa = $(this).find("td:eq(9)").text();
			sisa_temp += parseInt(sisa);
		});
		if (sisa_temp > 0) {
			// $("#simpan-data").attr("disabled", "disabled");
			$("#add-row-edit").removeAttr("disabled");
		} else {
			// $("#simpan-data").removeAttr("disabled");
			$("#add-row-edit").attr("disabled", "disabled");
		}
	}

	let counter_add_pallet = 0;
	$(document).on("click", "#tambah_pallet_edit", function() {
		counter_add_pallet++;
		if (counter_add_pallet == 1) {
			$('#show_add_pallet_edit').fadeOut("slow", function() {
				$(this).hide();
				$('#show_isi_sku_pallet_edit').hide();
				$("#data_pallet_edit > tbody").html(`<tr><td colspan="5" class="text-center"><span ><i class="fa fa-spinner fa-spin"></i> Loading...</span></td></tr>`);
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

		// count_pallet();

	});

	$(document).on("click", "#add-row-edit", function() {
		let params = JSON.parse($("#sj_id").val());
		// let id = $("#tambah_pallet").attr('data-id');
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanBarang/save_data_pallet') ?>",
			type: "POST",
			data: {
				id: params
			},
			dataType: "JSON",
			success: function(data) {
				if (data) {
					get_data_pallet();
				}
			}
		});
		// count_pallet();
	});

	$(document).on("click", "#edit-data", function() {
		let params = JSON.parse($("#sj_id").val());
		let keterangan = $("#keterangan_edit");
		let arr_sku_id = [];
		let arr_tipe_id = [];
		let arr_ed = [];
		let arr_jml_barang = [];
		let arr_jml_terima = [];
		let arr_detail_btb = [];

		let count_pallet = $("#data_pallet_edit > tbody tr").length;
		if (count_pallet == 0) {
			$("#error_save_data_edit").val("0");
			message("Error!", "Pallet masih kosong, silahkan tambah pallet terlebih dahulu", "error");
			return false;
		} else {
			$("#data_pallet_edit > tbody tr").each(function() {
				let jenis = $(this).find("td:eq(2) select").children("option").filter(":selected");
				let gudang_area = $(this).find("td:eq(3) input[type='hidden']");
				if (jenis.val() == "") {
					message("Error!", "Jenis Pallet tidak boleh kosong", "error");
					$("#error_save_data_edit").val("0");
					return false;
				} else if (gudang_area.val() == "null") {
					message("Error!", "Gudang Tujuan tidak boleh kosong", "error");
					$("#error_save_data_edit").val("0");
					return false;
				} else {
					$("#list_detail_pallet_edit > tbody tr").each(function() {
						let ed = $(this).find("td:eq(6) input[type='date']");
						let tipe = $(this).find("td:eq(7) select").children("option").filter(":selected");
						let jml_barang = $(this).find("td:eq(11) input[type='text']");
						if (ed.val() == "") {
							message("Error!", "Expired Date tidak boleh kosong", "error");
							$("#error_save_data_edit").val("0");
							return false;
						} else if (tipe.val() == "") {
							message("Error!", "Tipe tidak boleh kosong", "error");
							$("#error_save_data_edit").val("0");
							return false;
						} else if (jml_barang.val() == 0) {
							message("Error!", "Jumlah Barang tidak boleh kosong", "error");
							$("#error_save_data_edit").val("0");
							return false;
						} else {
							$("#error_save_data_edit").val("1");
						}
					});
				}
			});

			$("#tablelistdatadetailsjedit > tbody tr").each(function() {
				let sku_id = $(this).find("td:eq(1) input[type='hidden']");
				let tipe_id = $(this).find("td:eq(5) input[type='hidden']");
				let ed = $(this).find("td:eq(6)");
				let jml_barang = $(this).find("td:eq(7) input[type='hidden']");
				let jml_terima = $(this).find("td:eq(8)");

				global_data.filter((value) => {
					if (value == sku_id.val()) {
						sku_id.map(function() {
							arr_sku_id.push($(this).val());
						}).get();

						tipe_id.map(function() {
							arr_tipe_id.push($(this).val());
						}).get();

						ed.map(function() {
							arr_ed.push($(this).text());
						}).get();

						jml_barang.map(function() {
							arr_jml_barang.push($(this).val());
						}).get();

						jml_terima.map(function() {
							arr_jml_terima.push($(this).text());
						}).get();
					}
				});


			});
		}

		let error = $("#error_save_data_edit").val();
		if (error == 0) {
			return false;
		} else {
			if (arr_sku_id != null) {
				for (let index = 0; index < arr_sku_id.length; index++) {
					arr_detail_btb.push({
						'sku_id': arr_sku_id[index],
						'tipe_id': arr_tipe_id[index],
						'ed': arr_ed[index],
						'jml_barang': arr_jml_barang[index],
						'jml_terima': arr_jml_terima[index]
					});
				}
			}

			// Swal.fire({
			//   title: "Apakah anda yakin?",
			//   text: "Pastikan data yang sudah anda input benar!",
			//   icon: "warning",
			//   showCancelButton: true,
			//   confirmButtonColor: "#3085d6",
			//   cancelButtonColor: "#d33",
			//   confirmButtonText: "Ya, Edit",
			//   cancelButtonText: "Tidak, Tutup"
			// }).then((result) => {
			//   if (result.value == true) {
			//     $.ajax({
			//       url: "<?= base_url('WMS/PenerimaanBarang/edit_data_penerimaan') ?>",
			//       type: "POST",
			//       data: {
			//         pb_id: penerimaan_pembelian_id,
			//         doc_fob: params,
			//         client_id: client_wms_id,
			//         keterangan: keterangan.val(),
			//         detail_btb: arr_detail_btb
			//       },
			//       dataType: "JSON",
			//       success: function(data) {
			//         if (data == 1) {
			//           message_topright("success", "Data berhasil diedit");
			//           setTimeout(() => {
			//             location.href = "<?= base_url('WMS/PenerimaanBarang/PenerimaanBarangMenu') ?>";
			//           }, 500);
			//         } else {
			//           message_topright("error", "Data gagal diedit");
			//         }
			//       },
			//     });
			//   }
			// });
		}
	});

	$(document).on("click", "#kembali-edit", function() {
		setTimeout(() => {
			Swal.fire({
				title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
				showConfirmButton: false,
				timer: 500
			});
			location.href = "<?= base_url('WMS/PenerimaanBarang/PenerimaanBarangMenu') ?>";
		}, 500);
	})
</script>