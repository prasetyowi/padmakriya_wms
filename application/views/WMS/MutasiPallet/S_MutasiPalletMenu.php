<script type="text/javascript">
	loadingBeforeReadyPage()
	$(document).ready(
		function() {
			$(".select2").select2();
			$('#filter_mutasi_tgl').daterangepicker({
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

			$("#filter_mutasi_principle").append('<option value=""><label name="CAPTION-ALL">All</label></option>');
		}
	);

	function GetPrincipleHome(client_wms_id) {
		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/MutasiPallet/getDataPrincipleByClientWmsId') ?>",
			data: {
				id: client_wms_id
			},
			dataType: "JSON",
			success: function(response) {
				$("#filter_mutasi_principle").empty();
				let html = "";
				html += '<option value=""><label name="CAPTION-ALL">All</label></option>';
				$.each(response, function(i, v) {
					html += "<option value=" + v.id + ">" + v.nama + "</option>";
				});
				$("#filter_mutasi_principle").append(html);

			}
		});
	}

	$("#btn_pencarian_mutasi_pallet").click(
		function() {
			GetPencarianMutasiPalletTable();
		});

	function GetPencarianMutasiPalletTable() {
		$("#loadingview").show();
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/MutasiPallet/GetPencarianMutasiPalletTable') ?>",
			data: {
				tanggal: $("#filter_mutasi_tgl").val(),
				id: $("#filter_no_mutasi").val(),
				gudang_asal: $("#filter_gudang_asal_mutasi").val(),
				gudang_tujuan: $("#filter_gudang_tujuan_mutasi").val(),
				tipe: $("#filter_mutasi_tipe_transaksi").val(),
				client_wms: $("#filter_mutasi_perusahaan").val(),
				principle: $("#filter_mutasi_principle").val(),
				checker: $("#filter_mutasi_checker").val(),
				status: $("#filter_mutasi_status").val()
			},
			success: function(response) {
				if (response) {
					ChPencarianMutasiPalletTable(response);
				}
			}
		});
	}

	function ChPencarianMutasiPalletTable(JSONMutasiPallet) {
		$("#table_pencarian_mutasi > tbody").html('');
		$("#loadingview").hide();

		var MutasiPallet = JSON.parse(JSONMutasiPallet);
		var no = 1;

		if (MutasiPallet.MutasiPallet != 0) {
			if ($.fn.DataTable.isDataTable('#table_pencarian_mutasi')) {
				$('#table_pencarian_mutasi').DataTable().destroy();
			}

			$('#table_pencarian_mutasi tbody').empty();

			for (i = 0; i < MutasiPallet.MutasiPallet.length; i++) {
				var tr_mutasi_pallet_id = MutasiPallet.MutasiPallet[i].tr_mutasi_pallet_id;
				var tr_mutasi_pallet_kode = MutasiPallet.MutasiPallet[i].tr_mutasi_pallet_kode;
				var tr_mutasi_pallet_draft_id = MutasiPallet.MutasiPallet[i].tr_mutasi_pallet_draft_id;
				var tr_mutasi_pallet_draft_kode = MutasiPallet.MutasiPallet[i].tr_mutasi_pallet_draft_kode;
				var tr_mutasi_pallet_tanggal = MutasiPallet.MutasiPallet[i].tr_mutasi_pallet_tanggal;
				var tr_mutasi_pallet_tipe = MutasiPallet.MutasiPallet[i].tr_mutasi_pallet_tipe;
				var principle_id = MutasiPallet.MutasiPallet[i].principle_id;
				var principle_kode = MutasiPallet.MutasiPallet[i].principle_kode;
				var tr_mutasi_pallet_nama_checker = MutasiPallet.MutasiPallet[i].tr_mutasi_pallet_nama_checker;
				var depo_detail_id_asal = MutasiPallet.MutasiPallet[i].depo_detail_id_asal;
				var gudang_asal = MutasiPallet.MutasiPallet[i].gudang_asal;
				var depo_detail_id_tujuan = MutasiPallet.MutasiPallet[i].depo_detail_id_tujuan;
				var gudang_tujuan = MutasiPallet.MutasiPallet[i].gudang_tujuan;
				var tr_mutasi_pallet_status = MutasiPallet.MutasiPallet[i].tr_mutasi_pallet_status;


				var strmenu = '';

				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td class="text-center">' + tr_mutasi_pallet_kode + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + tr_mutasi_pallet_tanggal + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + tr_mutasi_pallet_draft_kode + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + tr_mutasi_pallet_tipe + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + principle_kode + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + tr_mutasi_pallet_nama_checker + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + gudang_asal + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + gudang_tujuan + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + tr_mutasi_pallet_status + '</td>';
				strmenu = strmenu + '	<td class="text-center"><a href="<?= base_url(); ?>WMS/MutasiPallet/MutasiPalletDetail/?tr_mutasi_pallet_id=' + tr_mutasi_pallet_id + '" class="btn btn-primary" target="_blank"><i class="fa fa-pencil"></i></a></td>';
				strmenu = strmenu + '</tr>';
				no++;

				$("#table_pencarian_mutasi > tbody").append(strmenu);
			}

			$("#loadingview").hide();

			$('#table_pencarian_mutasi').DataTable({
				"lengthMenu": [
					[10],
					[10]
				]
			});
		} else {
			$("#table_pencarian_mutasi > tbody").append(`<tr><td colspan="10" class="text-center text-danger">Data Kosong</td></tr>`);
		}
	}
</script>