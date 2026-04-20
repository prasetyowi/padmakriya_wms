<script type="text/javascript">
	var ChannelCode = '';
	loadingBeforeReadyPage()
	$(document).ready(
		function() {
			GetSettlementMenu();
		}
	);

	function GetSettlementMenu() {
		var delivery_order_batch_id = $("#delivery_order_batch_id").val();

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/SuratTugasPengiriman/GetSettlementMenu') ?>",
			data: "delivery_order_batch_id=" + delivery_order_batch_id,
			success: function(response) {
				if (response) {
					ChSettlementMenu(response);
				}
			}
		});
	}

	function ChSettlementMenu(JSONChannel) {
		$("#tablesettlementmenu > tbody").html('');

		var Channel = JSON.parse(JSONChannel);
		var no = 1;

		var delivery_order_batch_id = Channel.SettlementHeader[0].delivery_order_batch_id;
		var delivery_order_batch_kode = Channel.SettlementHeader[0].delivery_order_batch_kode;
		var karyawan_id = Channel.SettlementHeader[0].karyawan_id;
		var karyawan_nama = Channel.SettlementHeader[0].karyawan_nama;
		var count_settlement = Math.round(Channel.SettlementHeader[0].jumlah);

		$("#filter_fdjr_no").val(delivery_order_batch_kode);
		$("#txt_driver_fdjr").val(karyawan_nama);
		$("#txt_jumlah").val(Channel.Settlement.length);

		if (count_settlement > 0) {
			// $("#btn_simpan_settlement").hide();
			$("#btn_simpan_settlement").prop("disabled", true);
		} else {
			// $("#btn_simpan_settlement").show();
			$("#btn_simpan_settlement").prop("disabled", false);
		}

		if (Channel.Settlement != 0) {
			if ($.fn.DataTable.isDataTable('#tablesettlementmenu')) {
				$('#tablesettlementmenu').DataTable().destroy();
			}

			$('#tablesettlementmenu tbody').empty();

			for (i = 0; i < Channel.Settlement.length; i++) {
				var sku_id = Channel.Settlement[i].sku_id;
				var principle = Channel.Settlement[i].principle;
				var brand = Channel.Settlement[i].brand;
				var sku_kode = Channel.Settlement[i].sku_kode;
				var sku_nama = Channel.Settlement[i].sku_nama;
				var sku_kemasan = Channel.Settlement[i].sku_kemasan;
				var sku_satuan = Channel.Settlement[i].sku_satuan;
				var totalqtydo = Channel.Settlement[i].totalqtydo;
				var totalqtyinout = Channel.Settlement[i].totalqtyinout;
				var totalqtyorido = Channel.Settlement[i].totalqtyorido;
				var deviasi = Channel.Settlement[i].deviasi;
				var statussettlement = Channel.Settlement[i].statussettlement;
				var keterangan = Channel.Settlement[i].keterangan;

				var strmenu = '';

				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td>' + no + '</td>';
				strmenu = strmenu + '	<td>' + principle + '</td>';
				strmenu = strmenu + '	<td>' + brand + '</td>';
				strmenu = strmenu + '	<td>' + sku_kode + '</td>';
				strmenu = strmenu + '	<td>' + sku_nama + '</td>';
				strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
				strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
				strmenu = strmenu + '	<td>' + Math.round(totalqtyorido) + '</td>';
				strmenu = strmenu + '	<td>' + Math.round(totalqtydo) + '</td>';
				strmenu = strmenu + '	<td>' + Math.round(totalqtyinout) + '</td>';
				strmenu = strmenu + '	<td>' + Math.round(deviasi) + '</td>';
				if (statussettlement == "Cocok") {
					strmenu = strmenu + '	<td class="text-success"><input type="hidden" id="status_settlement_' + i + '" name="status_settlement_' + i + '" value="' + statussettlement + '">' + statussettlement + '</td>';
				} else if (statussettlement == "Tidak Cocok") {
					strmenu = strmenu + '	<td class="text-danger"><input type="hidden" id="status_settlement_' + i + '" name="status_settlement_' + i + '" value="' + statussettlement + '">' + statussettlement + '</td>';
				}

				if (keterangan == null) {
					strmenu = strmenu + '	<td><a href="<?php echo base_url(); ?>WMS/SuratTugasPengiriman/DetailSettlementMenu/?delivery_order_batch_id=' + delivery_order_batch_id + '&sku_id=' + sku_id + '&sku_kode=' + sku_kode + '&sku_nama=' + sku_nama + '&sku_kemasan=' + sku_kemasan + '&sku_satuan=' + sku_satuan + '&qty_do=' + totalqtydo + '&qty_aktual=' + totalqtyinout + '&status=' + statussettlement + '" class="btn btn-link" target="_blank"><span name="CAPTION-LIHAT">Lihat</span></a></td>';
				} else {
					strmenu = strmenu + '	<td><a href="<?php echo base_url(); ?>WMS/SuratTugasPengiriman/DetailSettlementMenu/?delivery_order_batch_id=' + delivery_order_batch_id + '&sku_id=' + sku_id + '&sku_kode=' + sku_kode + '&sku_nama=' + sku_nama + '&sku_kemasan=' + sku_kemasan + '&sku_satuan=' + sku_satuan + '&qty_do=' + totalqtydo + '&qty_aktual=' + totalqtyinout + '&status=' + statussettlement + '" class="btn btn-link" target="_blank"><span name="CAPTION-LIHAT">Lihat</span></a></td>';
				}
				// strmenu = strmenu + '	<td>'+ keterangan +'</td>';
				// strmenu = strmenu + '	<td><a href="<?php echo base_url(); ?>SuratTugasPengiriman/SettlementMenu/?delivery_order_batch_id=' + delivery_order_batch_id +'&sku_id=' + sku_id +'&sku_kode=' + sku_kode +'&sku_nama=' + sku_nama +'&sku_kemasan=' + sku_kemasan +'&sku_satuan=' + sku_satuan +'&qty_do=' + Math.round(totalqtydo) +'&qty_aktual=' + Math.round(totalqtyinout) +'&status=' + statussettlement + '" class="btn btn-link" target="_blank"><span name="CAPTION-LIHAT">Lihat</span></a></td>';
				strmenu = strmenu + '</tr>';
				no++;

				$("#tablesettlementmenu > tbody").append(strmenu);
			}

			$("#loadingview").hide();

			$('#tablesettlementmenu').DataTable({
				"lengthMenu": [
					[50],
					[50]
				]
			});
		} else {
			$('#tablesettlementmenu').DataTable().clear().draw();
			// ResetForm();
		}
	}

	$("#btn_simpan_settlement").click(
		function() {
			$("#loadingview").show();
			$("#btn_simpan_settlement").prop("disabled", true);

			var delivery_order_batch_id = $("#delivery_order_batch_id").val();
			var jumlah = $("#txt_jumlah").val();
			var counter = 0;

			for (var i = 0; i < jumlah; i++) {
				var statussettlement = $("#status_settlement_" + i).val();

				if (statussettlement == "Cocok") {
					counter++;
				}
			}

			if (counter == jumlah) {
				$.ajax({
					async: false,
					type: 'POST',
					url: "<?= base_url('WMS/SuratTugasPengiriman/InsertSettlement') ?>",
					data: {
						delivery_order_batch_id: delivery_order_batch_id,
						statussettlement: 'Cocok'
					},
					success: function(response) {
						if (response) {
							if (response == 1) {

								var msg = 'Data berhasil disimpan';
								var msgtype = 'success';

								//if (!window.__cfRLUnblockHandlers) return false;
								Swal.fire({
									position: 'center',
									icon: msgtype,
									title: msg,
									showConfirmButton: false,
									timer: 1000
								});

								window.location.reload();

								// ResetForm();
							} else {
								if (response == 2) {
									var msg = 'Kode Channel sudah ada';
								} else if (response == 3) {
									var msg = 'Nama Channel sudah ada';
								} else {
									var msg = response;
								}
								var msgtype = 'error';

								//if (!window.__cfRLUnblockHandlers) return false;
								new PNotify
									({
										title: 'Error',
										text: msg,
										type: msgtype,
										styling: 'bootstrap3',
										delay: 3000,
										stack: stack_center
									});

								ResetForm();
							}
						}
					}
				});
			} else {
				var msg = 'Settlement Ada Yang Tidak Cocok!';
				var msgtype = 'error';

				//if (!window.__cfRLUnblockHandlers) return false;
				Swal.fire({
					icon: msgtype,
					title: 'Oops...',
					text: msg
				});

				ResetForm();
			}
		}
	);

	$("#btnback").click(
		function() {
			ResetForm();
			GetSettlementMenu();
		}
	);

	function ResetForm() {
		<?php
		if ($Menu_Access["U"] == 1) {
		?>
			$("#loadingview").hide();
			$("#btn_simpan_settlement").prop("disabled", false);
		<?php
		}
		?>

		<?php
		if ($Menu_Access["C"] == 1) {
		?>
			$("#loadingview").hide();
			$("#btn_simpan_settlement").prop("disabled", false);
		<?php
		}
		?>

	}

	$(document).ready(function() {
		if ($('#filter_fdjr_date').length > 0) {
			$('#filter_fdjr_date').daterangepicker({
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
		}

	});
</script>