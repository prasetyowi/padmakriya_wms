<script>
	loadingBeforeReadyPage()
	let global_id = $("#global_id_view").val();

	$(document).ready(function() {
		select2();

		$(document).on("input", ".numeric", function(event) {
			this.value = this.value.replace(/[^0-9]/g, '');
		});

		$.ajax({
			url: "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PengambilanBarangMutasiAntarUnit/getDataKoreksiViewById'); ?>",
			type: "POST",
			data: {
				id: global_id
			},
			dataType: "JSON",
			async: false,
			success: function(response) {
				const header = response.header;
				const detail = response.detail;

				$("#koreksi_stok_id_view").val(header.tr_mutasi_depo_id);
				$("#gudang_id_view").val(header.depo_detail_id);


				$("#gudang_nama_view").val(header.gudangAsal);
				$("#tgl_view").val(header.tgl);

				$("#no_koreksi_draft_view").val(header.tr_mutasi_depo_kode);
				$("#depo_asal_view").val(header.depoAsal);
				$("#gudang_asal_view").val(header.gudangAsal);
				$("#depo_tujuan_view").val(header.depoTujuan);
				$("#ekspedisi_view").val(`${header.ekspedisi_kode} - ${header.ekspedisi_nama}`);
				$("#pengemudi_view").val(header.karyawan_nama);
				$("#kendaraan_view").val(`${header.kendaraan_model} - ${header.kendaraan_nopol}`);
				$("#status_view").val(header.tr_mutasi_depo_status);
				$("#keteranganPersiapan_view").val(header.tr_mutasi_depo_keterangan == null ? '' : header.tr_mutasi_depo_keterangan);
				$("#keterangan_view").val(header.tr_mutasi_depo_detail_3_keterangan == null ? '' : header.tr_mutasi_depo_detail_3_keterangan);

				initDetailKoreksiDraft(detail, header.gudangAsal, header.depo_detail_id);
			}
		});
	});

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
                <td>${v.sku_kode}</td>
                <td>${v.sku_nama}</td>
                <td class="text-center">${v.brand}</td>
                <td class="text-center">${v.sku_satuan}</td>
                <td class="text-center">${v.sku_kemasan}</td>
                <td class="text-center">${v.ed}</td>
                <td class="text-center">${v.qty_plan}</td>
                <td class="text-center">${v.qty_aktual == null ? 0 : v.qty_aktual}</td>
                <td class="text-center">
                    <button type="button" data-toggle="tooltip" data-placement="top" title="Lihat detail" data-id="${v.id}" data-gudang="${gudang}" data-gudang-id="${gudang_id}" data-ed="${v.ed}" data-sku-kode="${v.sku_kode}" data-sku-nama="${v.sku_nama}" class="btn btn-info pilihpallet_view" ><i class="fas fa-eye"></i></button> 
                </td>
            </tr>
        `);
			});
		} else {
			$("#table_data_tambah_koreksi_stok_view > tbody").html(`<tr><td colspan="9" class="text-center text-danger" name="CAPTION-DATAKOSONG">Data Kosong</td></tr>`);
		}

		$('#table_data_tambah_koreksi_stok_view').DataTable();
	}

	$(document).on("click", ".pilihpallet_view", function() {
		let id = $(this).attr('data-id');
		let gudang_id = $(this).attr('data-gudang-id');
		let gudang = $(this).attr('data-gudang');
		let ed = $(this).attr('data-ed');
		let sku_kode = $(this).attr('data-sku-kode');
		let sku_nama = $(this).attr('data-sku-nama');

		$("#list_data_pilih_pallet_view").modal("show");
		$("#gudang_asal_pallet_view").val(gudang);
		$("#sku_kode_pallet_view").val(sku_kode);
		$("#sku_nama_pallet_view").val(sku_nama);
		$("#ed_pallet_view").val(ed);

		get_data(id, gudang_id, ed);
	});

	function get_data(id, gudang_id, ed) {
		let koreksi_stok_id = $("#koreksi_stok_id_view").val();
		console.log(koreksi_stok_id);
		$.ajax({
			url: "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PengambilanBarangMutasiAntarUnit/getDataPalletBySkuStockId_view'); ?>",
			type: "POST",
			data: {
				id: id,
				gudang_id: gudang_id,
				ed: ed,
				koreksi_stok_id: koreksi_stok_id
			},
			dataType: "JSON",
			async: false,
			success: function(response) {
				if (response.length > 0) {
					if ($.fn.DataTable.isDataTable('#table_list_data_pilih_pallet_view')) {
						$('#table_list_data_pilih_pallet_view').DataTable().destroy();
					}

					$("#table_list_data_pilih_pallet_view > tbody").empty();
					$.each(response, function(i, v) {
						let str = "";
						let is_scan = "";
						let is_value = 0;
						str += `<input type='checkbox' class='form-control check-item_view' style="transform:scale(1.5)" disabled checked name='chk-data_view' id='chk-data'/>`;
						is_value = v.qty_ambil == null ? 0 : v.qty_ambil;

						$("#table_list_data_pilih_pallet_view > tbody").append(`
              <tr class="text-center">
                <td>${str}</td>
                <td>${v.lokasi_rak}</td>
                <td>${v.lokasi_bin}</td>
                <td>${v.no_pallet}</td>
                <td>${v.ed}</td>
                <td><input type='text' class='form-control numeric' ${is_scan} name='qty_ambil[]' id='qty_ambil[]' readonly value='${is_value}'/></td>
              </tr>
          `);
					});

					$('#table_list_data_pilih_pallet_view').DataTable({
						columnDefs: [{
							sortable: false,
							targets: [0, 1, 2, 3, 4]
						}],
						lengthMenu: [
							[-1],
							['All']
						],
					});
				} else {
					$("#table_list_data_pilih_pallet_view > tbody").html(`<tr><td colspan="6" class="text-center text-danger">Data Kosong</td></tr>`);
				}

			}
		})
	}

	$(document).on("click", ".btn_close_list_data_pilih_pallet_view", function() {
		$("#list_data_pilih_pallet_view").modal("hide");
		$("#check_scan_view").attr('checked', false).trigger('change');
	});

	$(document).on("click", "#kembali_koreksi_view", function() {
		setTimeout(() => {
			Swal.fire({
				title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
				showConfirmButton: false,
				timer: 500
			});
			location.href = "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PengambilanBarangMutasiAntarUnit/PengambilanBarangMutasiAntarUnitMenu') ?>";
		}, 500);
	})
</script>