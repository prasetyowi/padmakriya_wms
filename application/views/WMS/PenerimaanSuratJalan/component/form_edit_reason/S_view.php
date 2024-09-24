<script type="text/javascript">
	let global_id = $('#id').val();
	let global_principle_id = $('#principle_id_view').val();
	let global_reason_detail_id = $('#reason_detail_id').val();
	let global_data_detail = [];
	loadingBeforeReadyPage()
	$(document).ready(function() {
		select2();
		$(document).on("input", ".numeric", function(event) {
			this.value = this.value.replace(/[^\d.]+/g, '');
		});

		get_data_tipe_penerimaan_view();
		get_data_perusahaan_view();
		get_principle_view();
		getDataReason();

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
				$("#doc_batch_view").val(data.penerimaan_surat_jalan_kode);
				$("#tgl_view").val(tgl[0]);
				$("#perusahaan_view").val(data.client_wms_id).trigger('change');
				if (data.reason_surat_jalan_id != null) {
					$("#reason").val(data.reason_surat_jalan_id).trigger('change');
				}
				$("#tipe_penerimaan_view").val(data.penerimaan_tipe_id).trigger('change.select2');
				get_data_tipe_penerimaan2_view();

				if (data.penerimaan_surat_jalan_attachment != null) {
					$('#show-file-view').append(`
              <div style="margin-left:10px">
                <a href="<?= base_url('assets/images/uploads/Surat-Jalan/') ?>${data.penerimaan_surat_jalan_attachment}" class="text-primary" id="file_upload_form_db" target="_BLANK" style="cursor:pointer">${data.penerimaan_surat_jalan_attachment}</a>
              </div>
          `);
				}
				// $("#file_view").val(data.penerimaan_surat_jalan_attachment).trigger('change');
				$("#status_view").val(data.penerimaan_surat_jalan_status);
				$("#no_surat_jalan_view").val(data.penerimaan_surat_jalan_no_sj);
				$("#keterangan_view").val(data.penerimaan_surat_jalan_keterangan);
				$("#no_kendaraan_view").val(data.penerimaan_surat_jalan_nopol);
			}
		});

		//request get detail surat jalan
		$.ajax({
			url: "<?php echo base_url('WMS/PenerimaanSuratJalan/getDataDetailSuratjalanKonversi'); ?>",
			type: "POST",
			data: {
				id: global_id
			},
			dataType: "JSON",
			// async: false,
			success: function(data) {
				append_view_data_detail(data);
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

	function check_count_sku() {
		let total_sku = $("#table_list_data_sku_view > tbody tr").length;
		if (total_sku == 0) {
			//hapus readonly perusahaan dan principle
			$("#perusahaan_view").removeAttr("disabled");
			$("#principle_view").removeAttr("disabled");
		} else {
			$("#perusahaan_view").attr("disabled", "disabled");
			$("#principle_view").attr("disabled", "disabled");
		}
	}

	function get_data_perusahaan_view() {
		$.ajax({
			url: "<?php echo base_url('WMS/PenerimaanSuratJalan/get_data_perusahaan'); ?>",
			type: "GET",
			dataType: "JSON",
			async: false,
			success: function(data) {
				$('#perusahaan_view').empty();
				$('#perusahaan_view').append('<option value="">--Pilih Perusahaan--</option>');
				$.each(data, function(i, v) {
					$('#perusahaan_view').append('<option value="' + v.client_wms_id + '">' + v.client_wms_nama + '</option>');
				});
			}
		});
	}

	//onchane untuk penyalur / principle edit
	$("#perusahaan_view").on("change", function() {
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
				$("#principle_view").empty();
				$("#principle_view").html(response).trigger('change');
			}
		});
	});

	function getDataReason() {
		$.ajax({
			url: "<?php echo base_url('WMS/PenerimaanSuratJalan/getDatareason'); ?>",
			type: "GET",
			dataType: "JSON",
			async: false,
			success: function(data) {
				$('#reason').empty();
				$('#reason').append('<option value="">--Pilih Reason--</option>');
				$.each(data, function(i, v) {
					$('#reason').append('<option value="' + v.reason_surat_jalan_id + '">' + v.keterangan + '</option>');
				});
			}
		});
	}

	$("#reason").on("change", function() {
		let id = $(this).val();
		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/PenerimaanSuratJalan/getDataReasonDetail') ?>",
			data: {
				id,
				global_reason_detail_id
			},
			dataType: "json",
			async: false,
			success: function(response) {
				$("#reason_detail").empty();
				$("#reason_detail").html(response).trigger('change');
			}
		});
	});

	function append_view_data_detail(data) {
		$.each(data, function(i, v) {
			global_data_detail.push({
				sku_id: v.sku_id,
				tipe_id: v.tipe_id,
			});
			$("#table_list_data_sku_view > tbody").append(`
          <tr>
              <td>${v.sku_kode} <input type="hidden" id="id_sku" value="${v.sku_id}"/></td>
              <td>${v.sku_nama_produk} <input type="hidden" id="id_sjd" value="${v.sjd_id}"/></td>
              <td>${v.sku_kemasan}</td>
              <td>${v.sku_satuan}</td>
              <td>
              <input type="date" class="form-control expired_date_view" name="expired_date_view" value="${v.ed}" id="expired_date_view" readonly/>
              </td>
              <td>
                  <select class="form-control select2 tipe_penerimaan_sku_view" name="tipe_penerimaan_sku_view" id="tipe_penerimaan_sku_view" required disabled></select>
              </td>
              <td>
                <input type="text" class="form-control numeric" value="${v.sjd_jumlah_barang}" name="jumlah_barang_sku_view" id="jumlah_barang_sku_view" min="0" readonly/>
              </td>
              <td>
                <input type="text" class="form-control numeric" value="${v.sku_jumlah_terima}" name="jumlah_barang_terima_sku_view" id="jumlah_barang_terima_sku_view" min="0" readonly/>
              </td>
              <td>
                <input type="text" class="form-control numeric" value="${v.sisa}" name="jumlah_barang_sisa" id="jumlah_barang_sisa" readonly/>
              </td>
              <td>
                <input type="text" class="form-control" name="detail_reason_barang" id="detail_reason_barang" value="${v.reason_surat_jalan_detail_sku}"/>
              </td>
          </tr>
      `);
		});
		select2();

		trigger_all_in_detail();

		let total_sku = $("#table_list_data_sku_view > tbody tr").length;
		check_count_sku();
		$("#table_list_data_sku_view > tfoot tr #total_sku_view").html("<h4><strong>Total " + total_sku + " SKU</strong></h4>");

		// $("#tipe_penerimaan_id_view").attr('data-tipe', arr_tipe_id);
	}

	function trigger_all_in_detail() {
		// let tot_jml = 0;
		$("#table_list_data_sku_view > tbody tr").each(function(i, v) {

			let currentRow = $(this);
			let jml_barang = currentRow.find("td:eq(5) input[type='text']");
			let tipe = currentRow.find("td:eq(6) select");

			//change kolom tipe
			tipe.change(function(e) {
				check_tipe(e);
			});

		});

		let total_sku = $("#table_list_data_sku_view > tbody tr").length;
		check_count_sku();
		$("#table_list_data_sku_view > tfoot tr #total_sku_view").html("<h4><strong>Total " + total_sku + " SKU</strong></h4>");
	}

	function check_tipe(e) {
		let _1 = "";
		let sama = true;
		$("#table_list_data_sku_view > tbody tr").each(function(i, v) {
			let currentRow = $(this);
			let tipe = $(this).find("td:eq(6) select").children("option").filter(":selected").val();
			if (tipe != "") {
				if (_1 == "") {
					_1 = tipe;
				} else {
					if (_1 != tipe) {
						//ganti value
						sama = false;
						$("#tipe_penerimaan_view").val("7E683187-445E-4044-87BF-58804C9E09DA").trigger('change')
					}
				}
			}
		});

		if (sama == true) {
			if (_1 != "") {
				$("#tipe_penerimaan_view").val(_1).trigger('change.select2');
			}
		}
	}

	function get_data_tipe_penerimaan2_view(id) {
		let id_ = $('#tipe_penerimaan_view').val() != '' ? $('#tipe_penerimaan_view').val() : id;
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanSuratJalan/get_tipe_penerimaan2') ?>",
			type: "GET",
			dataType: "JSON",
			// async: false,
			success: function(data) {
				$('.tipe_penerimaan_sku_view').empty();
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

				$('.tipe_penerimaan_sku_view').append(output).trigger('change');

				if (id_ == "7E683187-445E-4044-87BF-58804C9E09DA") {
					$.each(global_data_detail, function(index, value) {
						$(".tipe_penerimaan_sku_view").eq(index).val(value.tipe_id).trigger('change');
					});
				}
			}
		});
	}

	//onchange untuk tempo pembyaran edit
	$("#principle_view").on("change", function() {
		let id = $(this).val();
		let txt = $("#principle_view option:selected").text();

		$("#pilih-sku-view").attr('data-id-principle', id);
		$("#pilih-sku-view").attr('data-txt-principle', txt);
		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/PenerimaanSuratJalan/get_data_principle_by_principle_id') ?>",
			data: {
				id: id,
			},
			dataType: "json",
			async: false,
			success: function(response) {
				$("#sub_principle_view").val(response.kode + " - " + response.nama);
				$("#tempo_pembayaran_view").val(response.tempo != null ? response.tempo + " Hari" : "-");
				// $("#tempo_pembayaran_view").val(response.tempo);
			}
		});
	});

	$("#tipe_penerimaan_view").on("change", function() {
		// var items = new getDataLocalStorage();
		let txt = $(this).children("option").filter(":selected").text();
		let txt_id = $(this).val();

		if (txt_id != "7E683187-445E-4044-87BF-58804C9E09DA") {
			get_data_tipe_penerimaan2_view(txt_id);
			$("#table_list_data_sku_view > tbody tr").each(function(i, v) {
				let currentRow = $(this);
				let sku_id = currentRow.find("td:eq(0) input[type='hidden']").val();
				let jml_barang = currentRow.find("td:eq(5) input[type='text']");
				let tipe = currentRow.find("td:eq(6) select");
			});
		}

	});

	function get_data_tipe_penerimaan_view() {
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanSuratJalan/get_tipe_penerimaan') ?>",
			type: "GET",
			dataType: "JSON",
			async: false,
			success: function(data) {
				$('#tipe_penerimaan_view').empty();
				$('#tipe_penerimaan_view').append('<option value="">--<label name="CAPTION-PILIHTIPE">Pilih Tipe--</option>');
				$.each(data, function(i, v) {
					$('#tipe_penerimaan_view').append('<option value="' + v.id + '">' + v.nama.toUpperCase() + '</option>');
				});
			}
		});
	}

	function get_principle_view() {
		$('#principle_view').append(`
            <option value="">--<label name="CAPTION-PILIHPRINCIPLE">Pilih Principle</label>--</option>
        `);
	};


	function reset_form_view() {
		$("#table_list_data_sku_view > tbody").empty();
		$("#perusahaan_view").html("");
		$("#principle_view").html("");
		$("#tipe_penerimaan_view").html("");
		$("#sub_principle_view").val("");
		$("#tempo_pembayaran_view").val("");
		$("#status_view").html("");
		$("#file_view").val("");
		$("#no_surat_jalan_view").val("");
		$("#keterangan_view").val("");
		get_data_tipe_penerimaan_view();
		get_data_perusahaan_view();
		get_principle_view();
		$("#table_list_data_sku_view > tfoot tr #tot_jml_view").text("");
		// localStorage.clear();
	}

	$(document).on("click", "#kembali_view", function() {
		notif_back();
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
					reset_form_view();
				}, 1000);
			}
		});

	}

	//modal close untuk pilih sku
	$(".btn_close_list_data_pilih_sku_view").on("click", function() {
		$("#list_data_pilih_sku_view").modal("hide");
	});

	const handleSaveReason = () => {
		let reason = $('#reason').val();
		let reasonDetail = $('#reason_detail').val();
		let arrSjdId = [];
		let arrReasonBarang = [];
		let arrDetailSjd = [];

		if (reason == '') {
			message("Error!", "Reason tidak boleh kosong", "error");
			$("#count_error_reason").val("1");
			return false;
		} else if (reasonDetail == '') {
			message("Error!", "Reason Detail tidak boleh kosong", "error");
			$("#count_error_reason").val("1");
			return false;
		} else {
			$("#count_error_reason").val("0");
			$("#table_list_data_sku_view > tbody tr").each(function(index, value) {
				let sjdId = $(this).find("td:eq(1) input[type='hidden']");
				let reasonDetail = $(this).find("td:eq(8) input[type='text']");

				sjdId.map(function() {
					arrSjdId.push($(this).val());
				}).get();

				reasonDetail.map(function() {
					arrReasonBarang.push($(this).val());
				}).get();
			});
		}


		let error = $("#count_error_reason");
		if (error.val() != 0) {
			return false;
		} else {

			if (arrSjdId != null) {
				for (let index = 0; index < arrSjdId.length; index++) {
					arrDetailSjd.push({
						'sjd_id': arrSjdId[index],
						'reasonDetail': arrReasonBarang[index],
					});
				}
			}

			$.ajax({
				type: "POST",
				url: "<?= base_url('WMS/PenerimaanSuratJalan/updateReason'); ?>",
				data: {
					global_id,
					reason,
					reasonDetail,
					arrDetailSjd
				},
				dataType: "JSON",
				success: function(response) {
					if (response) {
						message_topright("success", 'Data berhasil disimpan');
						setTimeout(() => {
							location.href = "<?= base_url('WMS/PenerimaanSuratJalan/PenerimaanSuratJalanMenu') ?>";
						}, 500);
					} else {
						message_topright("error", 'Data gagal disimpan');
					}
				}
			});
		}
	}
</script>