<script type="text/javascript">
	let global_id = $('#id').val();
	let global_principle_id = $('#principle_id_edit').val();
	let global_get_tipe = $('#tipe_penerimaan_id_edit').attr('data-tipe').split(",");
	let global_data_detail = [];
	loadingBeforeReadyPage()
	$(document).ready(function() {
		select2();
		$(document).on("input", ".numeric", function(event) {
			this.value = this.value.replace(/[^\d.]+/g, '');
		});

		get_data_tipe_penerimaan_edit();
		get_data_perusahaan_edit();
		get_status_edit();
		get_principle_edit();

		//request get header surat jalan
		$.ajax({
			url: "<?php echo base_url('WMS/PenerimaanSuratJalan/get_data_edit_surat_jalan_header'); ?>",
			type: "POST",
			data: {
				id: global_id
			},
			dataType: "JSON",
			// async: false,
			success: function(data) {
				let tgl = data.penerimaan_surat_jalan_tgl.split(" ");

				$("#doc_batch_edit").val(data.penerimaan_surat_jalan_kode);
				$("#tgl_edit").val(tgl[0]);
				$("#perusahaan_edit").val(data.client_wms_id).trigger('change');
				$("#tipe_penerimaan_edit").val(data.penerimaan_tipe_id).trigger('change.select2');
				// if (data.penerimaan_tipe_id == "7E683187-445E-4044-87BF-58804C9E09DA") {
				//   $("#tipe_penerimaan_edit").val(data.penerimaan_tipe_id).trigger('change.select2');
				// } else {
				//   $("#tipe_penerimaan_edit").prop('value', data.penerimaan_tipe_id).trigger('change.select2');
				// }
				get_data_tipe_penerimaan2_edit();

				if (data.penerimaan_surat_jalan_attachment != null) {
					$('#show-file-edit').append(`
              <div class="col-md-6">
                  <a href="<?= base_url('assets/images/uploads/Surat-Jalan/') ?>${data.penerimaan_surat_jalan_attachment}" class="text-primary" id="file_upload_form_db" target="_BLANK" style="cursor:pointer">${data.penerimaan_surat_jalan_attachment}</a>
              </div>
              <div class="col-md-6">
                  <button type="button" data-toggle="tooltip" data-placement="top" title="hapus file" class="btndeletefileeditfromdb" style="border:none;background:transparent"><i class="fas fa-trash text-danger" style="cursor: pointer"></i></button>
              </div>
          `);
				}
				// $("#file_edit").val(data.penerimaan_surat_jalan_attachment).trigger('change');
				$("#status_edit").val(data.penerimaan_surat_jalan_status).trigger('change');
				let sjEks = data.penerimaan_surat_jalan_no_sj.split("-");
				if (data.penerimaan_surat_jalan_group_split != null) {
					$("#no_surat_jalan_edit").val(sjEks[0]);
					$("#no_surat_jalan_counter_edit").val(sjEks[1])
					$("#no_surat_jalan_edit").prop('readonly', true)
				} else {
					$("#no_surat_jalan_edit").val(data.penerimaan_surat_jalan_no_sj);
					$("#no_surat_jalan_edit").prop('readonly', false)
				}
				$("#keterangan_edit").val(data.penerimaan_surat_jalan_keterangan);
				$("#no_kendaraan_edit").val(data.penerimaan_surat_jalan_nopol);
			}
		});

		//request get detail surat jalan
		$.ajax({
			url: "<?php echo base_url('WMS/PenerimaanSuratJalan/get_data_edit_surat_jalan_detail'); ?>",
			type: "POST",
			data: {
				id: global_id
			},
			dataType: "JSON",
			// async: false,
			success: function(data) {
				append_edit_data_detail(data);
			}
		});
	});

	function formatRupiah(angka, prefix) {
		var number_string = angka.replace(/[^,\d]/g, "").toString(),
			split = number_string.split(","),
			sisa = split[0].length % 3,
			rupiah = split[0].substr(0, sisa),
			ribuan = split[0].substr(sisa).match(/\d{3}/gi);

		// tambahkan titik jika yang di input sudah menjadi angka ribuan

		if (ribuan) {
			separator = sisa ? "." : "";
			rupiah += separator + ribuan.join(".");
		}
		rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;

		return prefix == undefined ? rupiah : rupiah ? rupiah : "";

	}


	function select2() {
		$(".select2").select2({
			width: "100%"
		});
	}

	function check_count_sku() {
		let total_sku = $("#table_list_data_sku_edit > tbody tr").length;
		if (total_sku == 0) {
			//hapus readonly perusahaan dan principle
			$("#perusahaan_edit").removeAttr("disabled");
			$("#principle_edit").removeAttr("disabled");
		} else {
			$("#perusahaan_edit").attr("disabled", "disabled");
			$("#principle_edit").attr("disabled", "disabled");
		}
	}

	function get_data_perusahaan_edit() {
		$.ajax({
			url: "<?php echo base_url('WMS/PenerimaanSuratJalan/get_data_perusahaan'); ?>",
			type: "GET",
			dataType: "JSON",
			async: false,
			success: function(data) {
				$('#perusahaan_edit').empty();
				$('#perusahaan_edit').append('<option value="">--<label name="CAPTION-PILIHPERUSAHAAN">Pilih Perusahaan</label>--</option>');
				$.each(data, function(i, v) {
					$('#perusahaan_edit').append('<option value="' + v.client_wms_id + '">' + v.client_wms_nama + '</option>');
				});
			}
		});
	}

	//onchane untuk penyalur / principle edit
	$("#perusahaan_edit").on("change", function() {
		let id = $(this).val();
		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/PenerimaanSuratJalan/get_data_principle_by_client_wms_id_edit') ?>",
			data: {
				id: id,
				principle_id: global_principle_id
			},
			dataType: "json",
			// async: false,
			success: function(response) {
				$("#principle_edit").empty();
				$("#principle_edit").html(response).trigger('change');
			}
		});
	});

	function append_edit_data_detail(data) {
		$.each(data, function(i, v) {
			// let harga = v.sjd_harga == 0 ? 0 : Math.round(v.sjd_harga);
			// let diskon = v.sjd_diskon == 0 ? 0 : Math.round(v.sjd_diskon);
			// let value_diskon = v.sjd_value_diskon == 0 ? 0 : Math.round(v.sjd_value_diskon);
			// let harga_total = v.sjd_harga_total == 0 ? 0 : Math.round(v.sjd_harga_total);
			global_data_detail.push({
				sku_id: v.sku_id,
				tipe_id: v.tipe_id,
				qty: v.sjd_jumlah_barang
				// harga: Math.round(v.sku_harga_jual),
				// diskon: diskon,
				// value_diskon: value_diskon,
				// harga_total: harga_total
			});
			$("#table_list_data_sku_edit > tbody").append(`
          <tr>
              <td>${v.sku_kode} <input type="hidden" id="id_sku" value="${v.sku_id}"/></td>
              <td>${v.sku_nama_produk}</td>
              <td>${v.sku_kemasan}</td>
              <td>${v.sku_satuan}</td>
              <td>
                  <input type="date" class="form-control expired_date_edit" name="expired_date_edit" value="${v.ed}" id="expired_date_edit_${v.sku_id}" />
              </td>
              <td><input type="text" class="form-control" name="batch_no_edit" id="batch_no_edit" value="${v.batch_no}"/></td>
              <td>
                  <input type="text" class="form-control numeric" value="${v.sjd_jumlah_barang}" name="jumlah_barang_sku_edit" id="jumlah_barang_sku_edit" min="0"/>
              </td>
              <td>
                  <select class="form-control select2 tipe_penerimaan_sku_edit" name="tipe_penerimaan_sku_edit" id="tipe_penerimaan_sku_edit" required></select>
              </td>
              <td class="text-center"><button type="button" class="btndeleteskuedit" data-id="${v.sku_id}" style="border:none;background:transparent"><i class="fas fa-trash text-danger" style="cursor: pointer"></i></button></td>
          </tr>
      `);
		});
		select2();

		var dtToday = new Date();

		var month = dtToday.getMonth() + 1;
		var day = dtToday.getDate();
		var year = dtToday.getFullYear();
		if (month < 10)
			month = '0' + month.toString();
		if (day < 10)
			day = '0' + day.toString();

		var maxDate = year + '-' + month + '-' + day;
		$('.expired_date_edit').attr('min', maxDate);

		trigger_all_in_detail();

		let total_sku = $("#table_list_data_sku_edit > tbody tr").length;
		check_count_sku();
		$("#table_list_data_sku_edit > tfoot tr #total_sku_edit").html("<h4><strong>Total " + total_sku + " SKU</strong></h4>");

		// $("#tipe_penerimaan_id_edit").attr('data-tipe', arr_tipe_id);
	}

	function trigger_all_in_detail() {
		// let tot_jml = 0;
		$("#table_list_data_sku_edit > tbody tr").each(function(i, v) {

			let currentRow = $(this);
			let jml_barang = currentRow.find("td:eq(6) input[type='text']");
			let tipe = currentRow.find("td:eq(7) select");
			// let harga_jual = currentRow.find("td:eq(6)");
			// let diskon = currentRow.find("td:eq(7) input[type='text']");
			// let value_diskon = currentRow.find("td:eq(8) input[type='text']");
			// let bruto = currentRow.find("td:eq(9)");
			// let jml = currentRow.find("td:eq(10)");

			// let bruto_awal = parseFloat(harga_jual.text().split(".").join("")) * parseFloat((jml_barang.val() == "") ? 1 : (jml_barang.val() == 0) ? 1 : jml_barang.val());
			// let netto_awal = parseFloat(harga_jual.text().split(".").join("")) - parseFloat(value_diskon.val() == "" ? 0 : value_diskon.val());
			// bruto.text(formatRupiah(bruto_awal.toString()));
			// jml.text(formatRupiah(netto_awal.toString()));
			// tot_jml += jml.text());

			// jml_barang.change(function() {
			//   let jml_barang_val = jml_barang.val() == "" ? 1 : jml_barang.val() == 0 ? 1 : jml_barang.val();
			//   let harga_jual_val = harga_jual.text();
			//   let diskon_val = diskon.val() == "" ? 0 : diskon.val();
			//   let value_diskon_val = value_diskon.val() == "" ? 0 : value_diskon.val();

			//   let jml_akhir = harga_jual_val.split(".").join("") * jml_barang_val;
			//   let value_diskon_akhir = jml_akhir * diskon_val / 100;
			//   let total_akhir = jml_akhir - value_diskon_akhir;
			//   value_diskon.val(formatRupiah(value_diskon_akhir.toString()));
			//   bruto.text(formatRupiah(jml_akhir.toString()));
			//   jml.text(formatRupiah(total_akhir.toString()));
			//   hitung_total();
			// });

			//change kolom tipe
			tipe.change(function(e) {
				check_tipe(e);
				// let jml_barang_val = jml_barang.val() == "" ? 1 : jml_barang.val() == 0 ? 1 : jml_barang.val();
				// let harga_jual_val = harga_jual.text();
				// let jml_akhir = harga_jual_val.split(".").join("") * jml_barang_val;
				// let txt = $(this).children("option").filter(":selected");
				// let sku_id = currentRow.find("td:eq(0) input[type='hidden']").val();
				// if (txt.val() != "") {
				//   const newState = global_data_detail.map(obj =>
				//     obj.sku_id === sku_id ? {
				//       ...obj,
				//       tipe_id: txt.val()
				//     } : obj
				//   );
				//   global_data_detail = newState;
				// }

				// if (txt.text() == 'BONUS' || txt.text() == 'PENGGANTI KLAIM' || txt.text() == 'RETUR' || txt.text() == 'SAMPLE') {
				//   jml.text(0);
				//   bruto.text(0);
				//   diskon.val(0);
				//   value_diskon.val(0);
				//   harga_jual.text(0);
				//   diskon.attr('readonly', true);
				//   value_diskon.attr('readonly', true);
				// } else {
				//   let jml_barang_val = jml_barang.val() == "" ? 1 : jml_barang.val() == 0 ? 1 : jml_barang.val();
				//   let harga_jual_val = global_data_detail[i].harga; //nembak, belum dinamis
				//   let jml_akhir = harga_jual_val * jml_barang_val;
				//   let check_val_nan_diskon = Number.isNaN(global_data_detail[i].diskon) ? 0 : global_data_detail[i].diskon;
				//   let check_val_nan_valu_diskon = Number.isNaN(global_data_detail[i].value_diskon) ? 0 : global_data_detail[i].value_diskon;
				//   let setelah_diskon = jml_akhir - check_val_nan_valu_diskon;
				//   jml.text(formatRupiah(setelah_diskon.toString()));
				//   bruto.text(formatRupiah(jml_akhir.toString()));
				//   diskon.attr('readonly', false);
				//   value_diskon.attr('readonly', false);

				//   diskon.val(formatRupiah(check_val_nan_diskon.toString()));
				//   value_diskon.val(formatRupiah(check_val_nan_valu_diskon.toString()));
				//   harga_jual.text(formatRupiah(harga_jual_val.toString()));
				// }
				// hitung_total();
			});

			// //change kolom diskon
			// diskon.change(function() {
			//   let jml_barang_val = jml_barang.val() == "" ? 1 : jml_barang.val() == 0 ? 1 : jml_barang.val();
			//   // let diskon_val = diskon.val() == "" ? 0 : diskon.val();
			//   let harga_jual_txt = harga_jual.text();
			//   let jml_akhir = harga_jual_val.split(".").join("") * jml_barang_val;
			//   let value_diskon_val = (jml_akhir / 100) * diskon.val();
			//   let jml_val = jml_akhir - value_diskon_val;
			//   let sku_id = currentRow.find("td:eq(0) input[type='hidden']").val();
			//   const newState = global_data_detail.map(obj =>
			//     obj.sku_id === sku_id ? {
			//       ...obj,
			//       diskon: diskon.val(),
			//       value_diskon: value_diskon_val
			//     } : obj
			//   );
			//   global_data_detail = newState;
			//   value_diskon.val(formatRupiah(value_diskon_val.toString()));
			//   jml.text(formatRupiah(jml_val.toString()));
			//   bruto.text(formatRupiah(jml_akhir.toString()));
			//   hitung_total();
			// });

			// //change kolom value diskon
			// value_diskon.change(function() {
			//   let jml_barang_val = jml_barang.val() == "" ? 1 : jml_barang.val() == 0 ? 1 : jml_barang.val();
			//   let harga_jual_txt = harga_jual.text();
			//   let value_diskon_val = value_diskon.val() == "" ? 0 : value_diskon.val();
			//   let jml_akhir = harga_jual_val.split(".").join("") * jml_barang_val;
			//   let diskon_val = (value_diskon_val / jml_akhir) * 100;
			//   let jml_val = jml_akhir - value_diskon_val;
			//   let sku_id = currentRow.find("td:eq(0) input[type='hidden']").val();
			//   const newState = global_data_detail.map(obj =>
			//     obj.sku_id === sku_id ? {
			//       ...obj,
			//       value_diskon: diskon_val
			//     } : obj
			//   );
			//   global_data_detail = newState;
			//   diskon.val(formatRupiah(diskon_val.toString()));
			//   jml.text(formatRupiah(jml_val.toString()));
			//   bruto.text(formatRupiah(jml_akhir.toString()));
			//   hitung_total();
			// });

		});

		let total_sku = $("#table_list_data_sku_edit > tbody tr").length;
		check_count_sku();
		$("#table_list_data_sku_edit > tfoot tr #total_sku_edit").html("<h4><strong>Total " + total_sku + " SKU</strong></h4>");

		var arr_lokal_temp = [];
		$(document).on("click", ".btndeleteskuedit", function() {
			let id = $(this).attr("data-id");
			// $("#principle option:selected").attr('data-id', id).trigger('change');
			arr_lokal_temp.push(id);
			$("#pilih-sku-edit").attr('data-sku-id', arr_lokal_temp);
			// let items = new getDataLocalStorage();
			let item = global_data_detail.filter((value, index) => value.sku_id !== id);
			global_data_detail.length = 0;
			$.each(item, function(i, v) {
				global_data_detail.push({
					sku_id: v.sku_id,
					tipe_id: v.tipe_id,
					qty: v.qty,
					// harga: v.harga,
					// diskon: v.diskon,
					// value_diskon: v.value_diskon,
					// harga_total: v.harga_total
				})
			});
			$(this).parent().parent().remove();
			// hitung_total();
			let total_sku = $("#table_list_data_sku_edit > tbody tr").length;
			check_count_sku();
			$("#table_list_data_sku_edit > tfoot tr #total_sku_edit").html("<h4><strong>Total " + total_sku + " SKU</strong></h4>");
		});
	}

	function check_tipe(e) {
		let _1 = "";
		let sama = true;
		$("#table_list_data_sku_edit > tbody tr").each(function(i, v) {
			let currentRow = $(this);
			let tipe = $(this).find("td:eq(7) select").children("option").filter(":selected").val();
			if (tipe != "") {
				if (_1 == "") {
					_1 = tipe;
				} else {
					if (_1 != tipe) {
						//ganti value
						sama = false;
						$("#tipe_penerimaan_edit").val("7E683187-445E-4044-87BF-58804C9E09DA").trigger('change')
					}
				}
			}
		});

		if (sama == true) {
			if (_1 != "") {
				$("#tipe_penerimaan_edit").val(_1).trigger('change.select2');
			}
		}
	}

	// function hitung_total() {
	//   let tot_jml = 0;
	//   $("#table_list_data_sku_edit > tfoot tr #tot_jml_edit").html("")
	//   $("#table_list_data_sku_edit > tbody tr").each(function(i, v) {
	//     let currentRow = $(this);
	//     let jml = currentRow.find("td:eq(10)");
	//     tot_jml += parseFloat(jml.text().split(".").join(""));
	//   });

	//   $("#table_list_data_sku_edit > tfoot tr #tot_jml_edit").html("<p style='margin-left:20%'><strong>" + formatRupiah(tot_jml.toString()) + "</strong></p>");
	// }

	function get_data_tipe_penerimaan2_edit(id) {
		let id_ = $('#tipe_penerimaan_edit').val() != '' ? $('#tipe_penerimaan_edit').val() : id;
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanSuratJalan/get_tipe_penerimaan2') ?>",
			type: "GET",
			dataType: "JSON",
			// async: false,
			success: function(data) {
				$('.tipe_penerimaan_sku_edit').empty();
				let output = '';
				output += '<option value="">--Pilih Tipe</option>';
				$.each(data, function(i, v) {
					if (typeof id_ !== 'undefined') {
						if (id_ == "7E683187-445E-4044-87BF-58804C9E09DA") {
							output += '<option value="' + v.id + '">' + v.nama.toUpperCase() + '</option>';
						} else {
							if (id_ == v.id) {
								output += '<option value="' + v.id + '" selected>' + v.nama.toUpperCase() + '</option>';
							} else {
								output += '<option value="' + v.id + '">' + v.nama.toUpperCase() + '</option>';
							}
						}
					} else {
						output += '<option value="' + v.id + '">' + v.nama.toUpperCase() + '</option>';
					}
				});

				$('.tipe_penerimaan_sku_edit').append(output).trigger('change');

				if (id_ == "7E683187-445E-4044-87BF-58804C9E09DA") {
					$.each(global_data_detail, function(index, value) {
						$(".tipe_penerimaan_sku_edit").eq(index).val(value.tipe_id).trigger('change');
					});
				}
			}
		});
	}

	$(document).on("click", ".btn_pilih_sku_edit", function() {
		let arr_chk = [];
		var checkboxes = $("input[name='chk-data-edit[]']");
		arr_chk.length = 0;
		for (var i = 0; i < checkboxes.length; i++) {
			if (checkboxes[i].checked == true && !(checkboxes[i].disabled)) {
				// checkboxes[i].disabled = true;
				if ($(`#qtySku${checkboxes[i].value}`).val() == "") {
					message("Error!", "Sku yang dicentang, Qtynya tidak boleh kosong", "error");
					return false
				} else {
					arr_chk.push({
						id: checkboxes[i].value,
						qty: $(`#qtySku${checkboxes[i].value}`).val()
					});
				}
			}
		}

		if (arr_chk.length == 0) {
			message("Info!", "Pilih data yang akan dipilih", "info");
		} else {
			$.ajax({
				type: "POST",
				url: "<?= base_url('WMS/PenerimaanSuratJalan/get_sku_by_id'); ?>",
				data: {
					data: arr_chk
				},
				dataType: "json",
				// async: false,
				success: function(response) {
					// $("#table_list_data_sku > tbody").empty();
					append_list_data_setelah_pilih_sku(response, arr_chk)
					$('#table_list_data_pilih_sku_edit').dataTable().fnFilter('');
					$("#list_data_pilih_sku_edit").modal("hide");

					for (var i = 0; i < checkboxes.length; i++) {
						if (checkboxes[i].type == 'checkbox' && !(checkboxes[i].disabled)) {
							checkboxes[i].checked = false;
						}
					}

				}
			});
		}
	});

	function append_list_data_setelah_pilih_sku(response, arrSkuId) {
		$.each(response, function(i, v) {
			let harga = v.sku_harga_jual == 0 ? 0 : Math.ceil(v.sku_harga_jual);
			global_data_detail.push({
				sku_id: v.sku_id,
				tipe_id: null,
				qty: v.qty
				// harga: harga,
				// diskon: null,
				// value_diskon: null,
				// harga_total: null
			});
			$("#table_list_data_sku_edit > tbody").append(`
          <tr>
                <td>${v.sku_kode} <input type="hidden" id="id_sku" value="${v.sku_id}"/></td>
                <td>${v.sku_nama_produk}</td>
                <td>${v.sku_kemasan}</td>
                <td>${v.sku_satuan}</td>
                <td>
                    <input type="date" class="form-control expired_date_edit" name="expired_date_edit" id="expired_date_edit_${v.sku_id}" onkeydown="return false"/>
                </td>
                <td><input type="text" class="form-control" name="batch_no_edit" id="batch_no_edit"/></td>
                <td>
                    <input type="text" class="form-control numeric" name="jumlah_barang_sku_edit" id="jumlah_barang_sku_edit" min="0" value="${v.qty}"/>
                </td>
                <td>
                    <select class="form-control select2 tipe_penerimaan_sku_edit" name="tipe_penerimaan_sku_edit" id="tipe_penerimaan_sku_edit" required></select>
                </td>
                <td class="text-center"><button type="button" class="btndeleteskuedit" data-id="${v.sku_id}" style="border:none;background:transparent"><i class="fas fa-trash text-danger" style="cursor: pointer"></i></button></td>
          </tr>
      `);
			get_data_tipe_penerimaan3_edit();
		});


		Swal.fire({
			html: 'Apakah anda ingin menggunakan default expired date?',
			showCancelButton: true,
			confirmButtonText: 'Iya',
			cancelButtonText: `Tidak`,
			allowOutsideClick: false,
		}).then((result) => {
			if (result.value) {
				$.ajax({
					type: "POST",
					url: "<?= base_url('WMS/PenerimaanSuratJalan/checkMinimunExpiredDate'); ?>",
					data: {
						data: arrSkuId
					},
					dataType: "JSON",
					success: function(response) {
						$.each(response, function(i, v) {
							$(`#expired_date_edit_${v.sku_id}`).val(v.date);
						})
					}
				});
			}
		});

		var dtToday = new Date();

		var month = dtToday.getMonth() + 1;
		var day = dtToday.getDate();
		var year = dtToday.getFullYear();
		if (month < 10)
			month = '0' + month.toString();
		if (day < 10)
			day = '0' + day.toString();

		var maxDate = year + '-' + month + '-' + day;
		$('.expired_date_edit').attr('min', maxDate);

		select2();
		trigger_all_in_detail();
	}

	function get_data_tipe_penerimaan3_edit() {
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanSuratJalan/get_tipe_penerimaan2') ?>",
			type: "GET",
			dataType: "JSON",
			async: false,
			success: function(data) {
				let idx = $('.tipe_penerimaan_sku_edit').length - 1;
				let dataTipe = $('#tipe_penerimaan_edit').val();
				$('.tipe_penerimaan_sku_edit').eq(idx).empty();
				let output = '';
				output += '<option value="">--Pilih Tipe</option>';
				$.each(data, function(i, v) {
					if (dataTipe == v.id) {
						output += '<option value="' + v.id + '" selected>' + v.nama.toUpperCase() + '</option>';
					} else {
						output += '<option value="' + v.id + '">' + v.nama.toUpperCase() + '</option>';
					}

				});

				$('.tipe_penerimaan_sku_edit').eq(idx).append(output).trigger('change');
			}
		});
	}

	//onchange untuk tempo pembyaran edit
	$("#principle_edit").on("change", function() {
		let id = $(this).val();
		let txt = $("#principle_edit option:selected").text();

		$("#pilih-sku-edit").attr('data-id-principle', id);
		$("#pilih-sku-edit").attr('data-txt-principle', txt);
		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/PenerimaanSuratJalan/get_data_principle_by_principle_id') ?>",
			data: {
				id: id,
			},
			dataType: "json",
			async: false,
			success: function(response) {
				$("#sub_principle_edit").val(response.kode + " - " + response.nama);
				$("#tempo_pembayaran_edit").val(response.tempo != null ? response.tempo + " Hari" : "-");
				// $("#tempo_pembayaran_edit").val(response.tempo);
			}
		});

		append_data_pilih_sku(id, txt);
	});

	function append_data_pilih_sku(id, txt) {
		let id_perusahaan = $("#perusahaan_edit option:selected").val();
		let txt_perusahaan = $("#perusahaan_edit option:selected").text();

		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/PenerimaanSuratJalan/get_data_sku_by_principle_id') ?>",
			data: {
				id: id,
				client_wms_id: id_perusahaan
			},
			dataType: "json",
			success: function(response) {
				$("#list_data_pilih_sku_edit .modal-title").html(`<span><label name="CAPTION-LISTDATASKUDARIPERUSAHAAN">List Data SKU dari Perusahaan</label> <strong>${txt_perusahaan}</strong> <label name="CAPTION-DANPRINCIPLE">dan Principle</label> <strong>${txt}</strong></span>`);
				if (response != null) {
					if ($.fn.DataTable.isDataTable('#table_list_data_pilih_sku_edit')) {
						$('#table_list_data_pilih_sku_edit').DataTable().destroy();
					}
					$('#table_list_data_pilih_sku_edit tbody').empty();
					for (i = 0; i < response.length; i++) {
						let sku_id = response[i].sku_id;
						let sku_kode = response[i].sku_kode;
						let sku = response[i].sku;
						let sku_satuan = response[i].sku_satuan;
						let sku_kemasan = response[i].sku_kemasan;
						let sku_induk = response[i].sku_induk;
						let principle = response[i].principle;
						let brand = response[i].brand;
						var str = "";
						var str_ = "";
						let qty = ""

						$.each(global_data_detail, function(index, value) {
							if (value.sku_id == sku_id) {
								str_ += "disabled checked";
								qty += value.qty;
							} else {
								str_ += "";
								qty += "";
							}
						});

						str += `<input type="checkbox" class="form-control check-item-edit" name="chk-data-edit[]" style="transform: scale(1.5)" id="chk-data-edit[]" value="${sku_id}" onclick="handleChangeInputQty('${sku_id}', this)"/>`;

						var strmenu = '';

						strmenu += '<tr>';
						strmenu += '<td class="text-center">' + str + '</td>';
						strmenu += '<td>' + sku_induk + '</td>';
						strmenu += '<td>' + sku_kode + '</td>';
						strmenu += '<td>' + sku + '</td>';
						strmenu += '<td class="text-center">' + sku_kemasan + '</td>';
						strmenu += '<td class="text-center">' + sku_satuan + '</td>';
						strmenu += '<td>' + principle + '</td>';
						strmenu += '<td>' + brand + '</td>';
						strmenu += `<td>
                            <input type="text" class="form-control numeric qtySku" id="qtySku${sku_id}" readonly>
                        </td>`;
						strmenu += '</tr>';
						$("#table_list_data_pilih_sku_edit > tbody").append(strmenu);
					}
				} else {
					$("#table_list_data_pilih_sku_edit > tbody").html(`<tr><td colspan="9" class="text-center text-danger">Data Kosong</td></tr>`);
					// message('Info!', 'Data Kosong', 'info');
				}

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
			}
		});
	}

	function handleChangeInputQty(sku_id, event) {
		if (event.checked == true) {
			$(`#qtySku${sku_id}`).prop("readonly", false);
		} else {
			$(`#qtySku${sku_id}`).prop("readonly", true);
		}
	}

	//modal untuk pilih sku
	$("#pilih-sku-edit").on("click", function() {
		let id = $(this).attr('data-id-principle');
		let txt = $(this).attr('data-txt-principle');

		// let res = $(this).attr('data-sku-id');
		// let sku_id = "";
		// if (res != null) {
		//   sku_id = res.split(",")
		// }
		//get data principle by id in sku
		if (id == null) {
			message('Info!', 'Pilih principle terlebih dahulu', 'info');
			return false;
		} else {
			$("#list_data_pilih_sku_edit").modal("show");
			// check_delete(sku_id);
		}

	});

	// function check_delete(sku_id) {
	//   let input = $(".check-item-edit");
	//   if (sku_id != "") {
	//     input.each(function(i, v) {
	//       if (sku_id.includes($(this).val())) {
	//         $("#pilih-sku-edit").removeAttr('data-sku-id');
	//         $(this).attr({
	//           disabled: false,
	//           checked: false
	//         });
	//       }
	//     });
	//   }
	// }

	//function untuk checklist all pilih sku
	function checkAllSKUEdit(e) {
		var checkboxes = $("input[name='chk-data-edit[]']");
		if (e.checked) {
			for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].type == 'checkbox' && !(checkboxes[i].disabled)) {
					checkboxes[i].checked = true;
					$(".qtySku").prop('readonly', false);
				}
			}
		} else {
			for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].type == 'checkbox' && !(checkboxes[i].disabled)) {
					checkboxes[i].checked = false;
					$(".qtySku").prop('readonly', true);
				}
			}
		}
	}

	$("#tipe_penerimaan_edit").on("change", function() {
		// var items = new getDataLocalStorage();
		let txt = $(this).children("option").filter(":selected").text();
		let txt_id = $(this).val();

		if (txt_id != "7E683187-445E-4044-87BF-58804C9E09DA") {
			get_data_tipe_penerimaan2_edit(txt_id);
			$("#table_list_data_sku_edit > tbody tr").each(function(i, v) {
				let currentRow = $(this);
				let sku_id = currentRow.find("td:eq(0) input[type='hidden']").val();
				let jml_barang = currentRow.find("td:eq(6) input[type='text']");
				let tipe = currentRow.find("td:eq(7) select");
				// let harga_jual = currentRow.find("td:eq(6)");
				// let diskon = currentRow.find("td:eq(7) input[type='text']");
				// let value_diskon = currentRow.find("td:eq(8) input[type='text']");
				// let bruto = currentRow.find("td:eq(9)");
				// let jml = currentRow.find("td:eq(10)");

				// if (txt == 'BONUS' || txt == 'PENGGANTI KLAIM' || txt == 'RETUR' || txt == 'SAMPLE') {
				//   jml.text(0);
				//   bruto.text(0);
				//   diskon.val(0);
				//   value_diskon.val(0);
				//   harga_jual.text(0);
				//   diskon.attr('readonly', true);
				//   value_diskon.attr('readonly', true);
				// } else {
				//   let jml_barang_val = jml_barang.val() == "" ? 1 : jml_barang.val() == 0 ? 1 : jml_barang.val();
				//   // let harga_jual_val = items[i].harga);
				//   let harga_jual_val = global_data_detail[i].harga;
				//   let jml_akhir = harga_jual_val * jml_barang_val;
				//   let check_val_nan_diskon = Number.isNaN(global_data_detail[i].diskon) ? 0 : global_data_detail[i].diskon;
				//   let check_val_nan_valu_diskon = Number.isNaN(global_data_detail[i].value_diskon) ? 0 : global_data_detail[i].value_diskon;
				//   let setelah_diskon = jml_akhir - check_val_nan_valu_diskon;
				//   jml.text(formatRupiah(setelah_diskon.toString()));
				//   bruto.text(formatRupiah(jml_akhir.toString()));
				//   diskon.attr('readonly', false);
				//   value_diskon.attr('readonly', false);
				//   diskon.val(formatRupiah(check_val_nan_diskon.toString()));
				//   value_diskon.val(formatRupiah(check_val_nan_valu_diskon.toString()));
				//   harga_jual.text(formatRupiah(harga_jual_val.toString()));
				// }
			});
		}

	});

	$(document).on("keyup", "#no_surat_jalan_edit", function() {
		let id = global_id;
		let perusahaan = $("#perusahaan_edit").val();
		let principle = $("#principle_edit").val();
		let no_surat_jalan = $(this).val();
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanSuratJalan/check_duplicate_no_sj_by_id') ?>",
			type: "POST",
			dataType: "JSON",
			data: {
				id: id,
				perusahaan: perusahaan,
				principle: principle,
				no_sj: no_surat_jalan
			},
			success: function(response) {
				if (response.status == false) {
					message("Error!", response.message, "error");
					return false
				}
			}
		});
	})

	function get_data_tipe_penerimaan_edit() {
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanSuratJalan/get_tipe_penerimaan') ?>",
			type: "GET",
			dataType: "JSON",
			async: false,
			success: function(data) {
				$('#tipe_penerimaan_edit').empty();
				$('#tipe_penerimaan_edit').append('<option value="">--Pilih Tipe--</option>');
				$.each(data, function(i, v) {
					$('#tipe_penerimaan_edit').append('<option value="' + v.id + '">' + v.nama.toUpperCase() + '</option>');
				});
			}
		});
	}

	function get_principle_edit() {
		$('#principle_edit').append(`
            <option value="">--Pilih Principle--</option>
        `);
	};

	function get_status_edit() {
		$('#status_edit').append(`
            <option value="">--Pilih Status--</option>
            <option value="Open" selected>Open</option>
        `);
	};


	function reset_form_edit() {
		$("#table_list_data_sku_edit > tbody").empty();
		$("#perusahaan_edit").html("");
		$("#principle_edit").html("");
		$("#tipe_penerimaan_edit").html("");
		$("#sub_principle_edit").val("");
		$("#tempo_pembayaran_edit").val("");
		$("#status_edit").html("");
		$("#file_edit").val("");
		$("#no_surat_jalan_edit").val("");
		$("#keterangan_edit").val("");
		get_data_tipe_penerimaan_edit();
		get_status_edit();
		get_data_perusahaan_edit();
		get_principle_edit();
		$("#table_list_data_sku_edit > tfoot tr #tot_jml_edit").text("");
		// localStorage.clear();
	}

	$(document).on("click", "#kembali", function() {
		let file_db = $("#file_upload_form_db").text();
		let file = $('#file_edit').val();
		let isFileInDb = "";
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanSuratJalan/get_file_db_by_id') ?>",
			type: "POST",
			dataType: "JSON",
			data: {
				id: global_id,
			},
			async: false,
			success: function(response) {
				if (response == 1) {
					isFileInDb += 1;
				} else {
					isFileInDb += 0;
				}
			}
		});
		if (file_db == "") {
			if (file == "") {
				message("Error!", "File Attachment tidak boleh kosong, silahkan upload file terlebih dahulu", "error");
			} else {
				if (isFileInDb == "1") {
					notif_back();
				} else {
					message("Error!", "File Attachment di server kosong, silahkan upload file terlebih dahulu", "error");
				}
			}
		} else {
			if (isFileInDb == "1") {
				notif_back();
			} else {
				message("Error!", "File Attachment di server kosong, silahkan upload file terlebih dahulu", "error");
			}
		}
	});

	function notif_back() {
		Swal.fire({
			title: "Apakah anda yakin?",
			text: "Ingin kembali ke halaman utama!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Batalkan",
			cancelButtonText: "Tidak, Tutup"
		}).then((result) => {
			if (result.value == true) {
				location.href = "<?= base_url('WMS/PenerimaanSuratJalan/PenerimaanSuratJalanMenu') ?>";
				setTimeout(() => {
					reset_form_edit();
				}, 1000);
			}
		});
	}

	function previewFileEdit() {
		let file_db = $("#file_upload_form_db").text();
		if (file_db != "") {
			message("Info!", "jika ingin mengganti file, silahkan delete file yang ada terlebih dahulu!", "info");
			$("#file_edit").val("");
		} else {
			const file = document.querySelector('input[type=file]').files[0];
			const reader = new FileReader();

			$('#show-file-edit').empty();
			reader.addEventListener("load", function() {
				let result = reader.result.split(',');
				// convert image file to base64 string
				$('#show-file-edit').append(`
                <div class="col-md-6">
                    <a class="text-primary klik-saya-edit" style="cursor:pointer" data-url="${result[1]}" data-ex="${result[0]}">${file.name}</a>
                </div>
                <div class="col-md-6">
                    <button type="button" data-toggle="tooltip" data-placement="top" title="hapus file" class="btndeletefileedit" style="border:none;background:transparent"><i class="fas fa-trash text-danger" style="cursor: pointer"></i></button>
                </div>
            `);
				// preview.src = reader.result;
			}, false);

			if (file) {
				reader.readAsDataURL(file);
			}
		}

	}

	$(document).on('click', '.klik-saya-edit', function() {
		let url = $(this).data('url');
		let ex = $(this).data('ex');
		let pdfWindow = window.open("")
		pdfWindow.document.write(
			"<iframe width='100%' height='100%' src='" + ex + ", " +
			encodeURI(url) + "'></iframe>"
		)
	});

	$(document).on('click', '.btndeletefileedit', function() {
		$('#show-file-edit').empty();
		$("#file_edit").val("");
		// $(".file-upload-wrapper").attr("data-text", "Select your file!");
	});

	$(document).on('click', '.btndeletefileeditfromdb', function() {
		Swal.fire({
			title: "Apakah anda yakin?",
			text: "Ingin menghapus file yang ada!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Hapus",
			cancelButtonText: "Tidak, Tutup"
		}).then((result) => {
			if (result.value == true) {
				message_topright("success", "File berhasil dihapus!");
				$('#show-file-edit').empty();
				//request to update data in db
				// $.ajax({
				//   url: "<?= base_url('WMS/PenerimaanSuratJalan/delete_file_db') ?>",
				//   type: "POST",
				//   data: {
				//     id: global_id
				//   },
				//   success: function(data) {
				//     if (data == 1) {
				//       message_topright("success", "File berhasil dihapus!");
				//       $('#show-file-edit').empty();
				//     } else {
				//       message_topright("error", "File gagal dihapus!");
				//     }
				//   }
				// });
			}
		});
	});

	//modal close untuk pilih sku
	$(".btn_close_list_data_pilih_sku_edit").on("click", function() {
		$('#table_list_data_pilih_sku_edit').dataTable().fnFilter('');
		$("#list_data_pilih_sku_edit").modal("hide");
	});

	$(document).on('click', '#edit-data', function() {

		let file_db = $("#file_upload_form_db").text();
		let perusahaan = $('#perusahaan_edit').val();
		let principle = $('#principle_edit').val();
		let tipe_penerimaan = $('#tipe_penerimaan_edit').val();
		let status = $('#status_edit').val();
		let no_surat_jalan = $('#no_surat_jalan_edit').val();
		let no_surat_jalan_counter_edit = $('#no_surat_jalan_counter_edit').val();
		let no_kendaraan = $('#no_kendaraan_edit').val();
		let keterangan = $('#keterangan_edit').val();
		let lastUpdated = $('#lastUpdated').val();
		let file = $('#file_edit').val();
		let arr_sku_id = [];
		let arr_ed = [];
		let arr_batch_no = [];
		let arr_sku_tipe = [];
		let arr_jml_brng = [];
		// let arr_diskon = [];
		// let arr_value_diskon = [];
		// let arr_bruto = [];
		// let arr_netto = [];
		let arr_detail_psj = [];

		if (perusahaan == '') {
			message("Error!", "Perusahaan tidak boleh kosong", "error");
			$("#count_error_edit").val("1");
		} else if (principle == '') {
			message("Error!", "Principle tidak boleh kosong", "error");
			$("#count_error_edit").val("1");
		} else if (tipe_penerimaan == '') {
			message("Error!", "Tipe Penerimaan tidak boleh kosong", "error");
			$("#count_error_edit").val("1");
		} else if (status == '') {
			message("Error!", "Status tidak boleh kosong", "error");
			$("#count_error_edit").val("1");
		} else if (no_surat_jalan == '') {
			message("Error!", "No. Surat Jalan tidak boleh kosong", "error");
			$("#count_error_edit").val("1");
		} else if (no_kendaraan == '') {
			message("Error!", "No. Kendaraan tidak boleh kosong", "error");
			$("#count_error_edit").val("1");
		} else if (file_db == "") {
			if (file == "") {
				message("Error!", "File Attachment tidak boleh kosong", "error");
				$("#count_error_edit").val("1");
			} else {
				data(arr_sku_id, arr_ed, arr_batch_no, arr_sku_tipe, arr_jml_brng)
			}
		} else {
			data(arr_sku_id, arr_ed, arr_batch_no, arr_sku_tipe, arr_jml_brng)
		}


		let error = $("#count_error_edit");
		if (error.val() != 0) {
			return false;
		} else {

			if (arr_sku_id != null) {
				for (let index = 0; index < arr_sku_id.length; index++) {
					arr_detail_psj.push({
						'sku_id': arr_sku_id[index],
						'ed': arr_ed[index],
						'batch_no': arr_batch_no[index],
						'sku_tipe': arr_sku_tipe[index],
						'jml_brng': arr_jml_brng[index],
						// 'diskon': arr_diskon[index],
						// 'value_diskon': arr_value_diskon[index],
						// 'netto': arr_netto[index],
						// 'bruto': arr_bruto[index]
					});
				}
			}

			var json_arr = JSON.stringify(arr_detail_psj);
			let new_form = new FormData();
			const files = $('#file_edit')[0].files[0];
			if (file_db == "") {
				if (file != "") {
					new_form.append('file', files);
				}
			} else {
				new_form.append('file_db', file_db);
			}
			//tambahan aja
			new_form.append('id', global_id);
			new_form.append('tgl', $('#tgl_edit').val());
			new_form.append('perusahaan', perusahaan);
			new_form.append('principle', principle);
			new_form.append('tipe_penerimaan', tipe_penerimaan);
			new_form.append('status', status);
			new_form.append('no_surat_jalan', no_surat_jalan);
			new_form.append('no_surat_jalan_counter', no_surat_jalan_counter_edit);
			new_form.append('no_kendaraan', no_kendaraan);
			new_form.append('keterangan', keterangan);
			new_form.append('lastUpdated', lastUpdated);
			new_form.append('data_detail', json_arr);

			// Display the key/value pairs
			// for (var pair of new_form.entries()) {
			//   console.log(pair);
			// }

			messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Iya, Update', 'Tidak, Tutup').then((result) => {
				if (result.value == true) {
					requestAjax("<?= base_url('WMS/PenerimaanSuratJalan/update'); ?>", {
						dataForm: new_form
					}, "POST", "JSON", function(response) {
						if (response.status == true) {
							message_topright("success", response.message);
							setTimeout(() => {
								location.href = "<?= base_url('WMS/PenerimaanSuratJalan/PenerimaanSuratJalanMenu') ?>";
								reset_form_edit();
							}, 1500);
						}

						if (response.status == false) return message_topright("error", "Data gagal disimpan");
						if (response.status == 'not same updated') return messageNotSameLastUpdated()
					}, "#edit-data", "multipart-formdata")
				}
			});

			// Swal.fire({
			// 	title: "Apakah anda yakin?",
			// 	text: "Pastikan data yang ada edit sudah benar!",
			// 	icon: "warning",
			// 	showCancelButton: true,
			// 	confirmButtonColor: "#3085d6",
			// 	cancelButtonColor: "#d33",
			// 	confirmButtonText: "Ya, Hapus",
			// 	cancelButtonText: "Tidak, Tutup"
			// }).then((result) => {
			// 	if (result.value == true) {
			// 		$.ajax({
			// 			type: "POST",
			// 			url: "<?= base_url('WMS/PenerimaanSuratJalan/update'); ?>",
			// 			data: new_form,
			// 			contentType: false,
			// 			processData: false,
			// 			dataType: "json",
			// 			success: function(response) {
			// 				if (response.status == true) {
			// 					message_topright("success", response.message);
			// 					setTimeout(() => {
			// 						location.href = "<?= base_url('WMS/PenerimaanSuratJalan/PenerimaanSuratJalanMenu') ?>";
			// 						reset_form_edit();
			// 					}, 1500);
			// 				} else {
			// 					message_topright("error", response.message);
			// 				}
			// 			}
			// 		});
			// 	}
			// });

		}

		function data(arr_sku_id, arr_ed, arr_batch_no, arr_sku_tipe, arr_jml_brng) {
			$("#table_list_data_sku_edit > tbody tr").each(function(index, value) {
				let sku_id = $(this).find("td:eq(0) input[type='hidden']");
				let ed = $(this).find("td:eq(4) input[type='date']");
				let batch_no = $(this).find("td:eq(5) input[type='text']");
				let jml_brng = $(this).find("td:eq(6) input[type='text']");
				let sku_tipe = $(this).find("td:eq(7) select").children("option").filter(":selected");
				// let diskon = $(this).find("td:eq(7) input[type='text']");
				// let value_diskon = $(this).find("td:eq(8) input[type='text']");
				// let bruto = $(this).find("td:eq(9)");
				// let netto = $(this).find("td:eq(10)");

				if (ed.val() == '') {
					message('Error!', 'Tidak dapat simpan, Expired date tidak boleh kosong!', 'error');
					$("#count_error_edit").val("1");
					return false;
				} else if (jml_brng.val() == '') {
					message('Error!', 'Tidak dapat simpan, Jumlah barang tidak boleh kosong!', 'error');
					$("#count_error_edit").val("1");
					return false;
				} else {
					$("#count_error_edit").val("0");
					sku_id.map(function() {
						arr_sku_id.push($(this).val());
					}).get();

					ed.map(function() {
						arr_ed.push($(this).val());
					}).get();

					batch_no.map(function() {
						arr_batch_no.push($(this).val());
					}).get();

					sku_tipe.map(function() {
						arr_sku_tipe.push($(this).val());
					}).get();

					jml_brng.map(function() {
						arr_jml_brng.push($(this).val());
					}).get();

					// diskon.map(function() {
					//   arr_diskon.push($(this).val());
					// }).get();

					// value_diskon.map(function() {
					//   arr_value_diskon.push($(this).val());
					// }).get();

					// bruto.map(function() {
					//   arr_bruto.push($(this).text());
					// }).get();

					// netto.map(function() {
					//   arr_netto.push($(this).text());
					// }).get();
				}
			});
		}
	});
</script>