<script>
	let global_id = $("#global_id_view").val();
	let global_sku_id = [];
	let global_data = [];
	loadingBeforeReadyPage()
	$(document).ready(function() {
		select2();

		$(document).on("input", ".numeric", function(event) {
			this.value = this.value.replace(/[^0-9]/g, '');
		});

		$.ajax({
			url: "<?= base_url('WMS/Pemusnahan/getDataKoreksiEditById'); ?>",
			type: "POST",
			data: {
				id: global_id
			},
			dataType: "JSON",
			async: false,
			success: function(response) {
				const header = response.header;
				const detail = response.detail;

				$("#koreksi_draft_id_view").val(header.draft_id);
				$("#gudang_id_view").val(header.gudang_id);
				$("#principle_id_view").val(header.principle_id);
				$("#keterangan_view").val(header.keterangan);
				$("#tipe_id_view").val(header.tipe_id);
				$("#checker_id_view").val(header.checker_id);

				$("#gudang_nama_view").val(header.gudang);

				$("#no_koreksi_view").val(header.kode);
				$("#tgl_view").val(header.tgl);
				$("#no_koreksi_draft_view").val(header.kode_draft);
				$("#gudang_asal_view").val(header.gudang);
				$("#principle_view").val(header.principle);
				$("#checker_view").val(header.checker);
				$("#tipe_transaksi_view").val(header.tipe);
				$("#status_view").val(header.status);

				$.each(detail, function(i, v) {
					global_data.push(v);
				})

				initDetailKoreksiDraft(detail, header.gudang, header.gudang_id);

				//check exist data in tr koreksi stok
				$.ajax({
					url: "<?= base_url('WMS/Pemusnahan/check_exist_in_tr_koreksi_edit') ?>",
					type: "POST",
					data: {
						id: header.id
					},
					dataType: "JSON",
					async: false,
					success: function(response) {}
				});


				// UpdateQtyAmbil(detail, header.gudang, header.gudang_id);
			}
		});
	});

	function initDetailKoreksiDraft(data, gudang, gudang_id) {
		$("#show_data_view").show();

		if ($.fn.DataTable.isDataTable('#table_data_tambah_koreksi_stok_view')) {
			$('#table_data_tambah_koreksi_stok_view').DataTable().destroy();
		}

		$("#table_data_tambah_koreksi_stok_view > tbody").empty();

		if (data.length != 0) {
			$.each(data, function(i, v) {
				$("#table_data_tambah_koreksi_stok_view > tbody").append(`
            <tr>
                <td class="text-center">${i + 1}</td>
                <td>${v.sku_kode} <input type="hidden" name="sku_stock_id_view" id="sku_stock_id_view" value="${v.id}"/></td>
                <td>${v.sku_nama} <input type="hidden" name="sku_id_view" id="sku_id_view" value="${v.sku_id}"/></td>
                <td class="text-center">${v.brand}</td>
                <td class="text-center">${v.sku_satuan}</td>
                <td class="text-center">${v.sku_kemasan}</td>
                <td class="text-center">${v.ed}</td>
                <td class="text-center">${v.qty_plan}</td>
                <td class="text-center">${v.qty_aktual == null ? 0 : v.qty_aktual}</td>
                <td class="text-center">
                    <button type="button" data-toggle="tooltip" data-placement="top" title="Pilih Pallet" data-id="${v.id}" data-gudang="${gudang}" data-gudang-id="${gudang_id}" data-ed="${v.ed}" data-sku-kode="${v.sku_kode}" data-sku-nama="${v.sku_nama}" class="btn btn-info pilihpallet_view" ><i class="fas fa-hand-point-up"></i></button> 
                </td>
            </tr>
        `);
			});
		} else {
			$("#table_data_tambah_koreksi_stok_view > tbody").html(`<tr><td colspan="9" class="text-center text-danger" name="CAPTION-DATAKOSONG">Data Kosong</td></tr>`);
		}

		$('#table_data_tambah_koreksi_stok_view').DataTable();
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

	$(document).on("click", ".pilihpallet_view", function() {
		let id = $(this).attr('data-id');
		let gudang_id = $(this).attr('data-gudang-id');
		let gudang = $(this).attr('data-gudang');
		let ed = $(this).attr('data-ed');
		let sku_kode = $(this).attr('data-sku-kode');
		let sku_nama = $(this).attr('data-sku-nama');
		let koreksi_draft_id = $("#koreksi_draft_id_view").val();

		$("#start_scan_view").attr('data-id', id);
		$("#start_scan_view").attr('data-gudang-id', gudang_id);
		$("#start_scan_view").attr('data-ed', ed);

		$("#input_manual_view").attr('data-id', id);
		$("#input_manual_view").attr('data-gudang-id', gudang_id);
		$("#input_manual_view").attr('data-ed', ed);


		$("#list_data_pilih_pallet_view").modal("show");
		$("#gudang_asal_pallet_view").val(gudang);
		$("#sku_kode_pallet_view").val(sku_kode);
		$("#sku_nama_pallet_view").val(sku_nama);
		$("#ed_pallet_view").val(ed);

		get_data(global_id, id, gudang_id, ed);

	});

	function get_data(global_id, id, gudang_id, ed) {
		$.ajax({
			url: "<?= base_url('WMS/Pemusnahan/getDataPemusnahanPallet'); ?>",
			type: "POST",
			data: {
				koreksi_id: global_id,
				id: id,
				gudang_id: gudang_id,
				ed: ed
			},
			dataType: "JSON",
			success: function(response) {
				$("#table_list_data_pilih_pallet_view > tbody").html('');

				$("#table_list_data_pilih_pallet_view tbody").empty();

				if (response.length > 0) {
					$.each(response, function(i, v) {

						$("#table_list_data_pilih_pallet_view > tbody").append(`
              <tr class="text-center">
                <td>${v.lokasi_rak}</td>
                <td>${v.lokasi_bin}</td>
                <td>${v.no_pallet}</td>
                <td>${v.ed}</td>
                <td>${v.qty_ambil}</td>
                <td>${v.sku_qty_plan_koreksi}</td>
              </tr>
          `);
					});

				} else {
					$("#table_list_data_pilih_pallet_view > tbody").html(`<tr><td colspan="7" class="text-center text-danger" name="CAPTION-DATAKOSONG">Data Kosong</td></tr>`);
				}

			}
		})
	}

	function trigger() {
		check_count_sku();

		let count_sku = $("#list_data_tambah_pemusnahan_stok_view > tbody tr").length;
		$("#list_data_tambah_pemusnahan_stok_view > tfoot tr #total_detail_sku_view").html("<strong><h4>Total " + count_sku + " SKU</h4></strong>");
	}

	function check_count_sku() {
		let total_sku = $("#list_data_tambah_pemusnahan_stok_view > tbody tr").length;
		if (total_sku == 0) {
			//hapus readonly perusahaan dan principle
			$("#gudang_koreksi_view").removeAttr("disabled");
			$("#koreksi_principle_view").removeAttr("disabled");
		} else {
			$("#gudang_koreksi_view").attr("disabled", "disabled");
			$("#koreksi_principle_view").attr("disabled", "disabled");
		}
	}

	$("#gudang_koreksi_view").on("change", function() {
		$("#pilih_sku_koreksi_view").attr("data-gudang-id", $(this).val());
		$("#pilih_sku_koreksi_view").attr("data-gudang-txt", $(this).children("option").filter(":selected").text());
	});

	$("#koreksi_principle_view").on("change", function() {
		let id = $(this).val();
		let txt = $(this).children("option").filter(":selected").text();
		$("#pilih_sku_koreksi_view").attr("data-principle-id", id);
		$("#pilih_sku_koreksi_view").attr("data-principle-txt", txt);
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/Pemusnahan/get_checker_by_principleId') ?>",
			data: {
				id: id
			},
			dataType: "JSON",
			async: false,
			success: function(response) {
				$("#koreksi_checker_view").empty();
				if (response.length > 0) {
					$("#koreksi_checker_view").append("<option value=''>--Pilih Checker--</option>")
					$.each(response, function(i, v) {
						$("#koreksi_checker_view").append(`<option value="${v.id}">${v.nama}</option>`);
					});
				} else {
					$("#koreksi_checker_view").empty();
				}
			}
		});
	});

	$("#koreksi_approval_view").on("change", function(e) {
		e.target.checked ? $("#koreksi_status_view").val($(this).val()) : $("#koreksi_status_view").val("Draft");
	});

	$(document).on("click", "#kembali_koreksi_view", function() {
		setTimeout(() => {
			Swal.fire({
				title: '<span ><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>',
				showConfirmButton: false,
				timer: 500
			});
			location.href = "<?= base_url('WMS/Pemusnahan/PemusnahanMenu') ?>";
		}, 500);
	})
</script>