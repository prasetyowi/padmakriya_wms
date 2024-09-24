<script type="text/javascript">
	var ChannelCode = '';

	loadingBeforeReadyPage();

	$(document).ready(
		function() {
			GetPengeluaranBarangMenu();
			$('#slcaddcheckerbkbbulk').select2();
			$('#slcaddcheckerbkbstandar').select2();
			$('#slctipepb, #slcstatuspb').select2();

			if ($('#datecreateppb').length > 0) {
				$('#datecreateppb').daterangepicker({
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
		}
	);


	function GetPengeluaranBarangMenu() {
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetPengeluaranBarangMenu') ?>",
			//data: "Location="+ Location,
			success: function(response) {
				if (response) {
					ChPengeluaranBarangMenu(response);
				}
			}
		});
	}

	//var DTABLE;

	function ChPengeluaranBarangMenu(JSONChannel) {
		$("#tablebkbmenu > tbody").html('');
		$("#slctipepb").html('');

		var Channel = JSON.parse(JSONChannel);

		var StatusC = Channel.AuthorityMenu[0].status_c;
		var StatusU = Channel.AuthorityMenu[0].status_u;
		var StatusD = Channel.AuthorityMenu[0].status_d;

		if (StatusC == 0) {
			$("#btnaddnewbkbstandar").attr('style', 'display: none;');
		}
		if (StatusC == 0) {
			$("#btnaddnewbkbbulk").attr('style', 'display: none;');
		}

		if (Channel.TipePB != 0) {
			$("#slctipepb").append('<option value="all">All</option>');

			for (i = 0; i < Channel.TipePB.length; i++) {
				tipe_delivery_order_id = Channel.TipePB[i].tipe_delivery_order_id;
				tipe_delivery_order_alias = Channel.TipePB[i].tipe_delivery_order_alias;
				$("#slctipepb").append('<option value="\'' + tipe_delivery_order_id + '\'">' + tipe_delivery_order_alias + '</option>');
			}
		}
	}

	$("#btnviewbkb").click(
		function() {
			var Tgl_PPB = $("#datecreateppb").val();
			var No_PPB = $("#txtnoppb").val();
			var No_FDJR = $("#txtnofdjr").val();
			var No_PB = $("#txtnopb").val();
			var Tipe_PB = $('#slctipepb').val();
			var Status_PB = $('#slcstatuspb').val();

			$("#loadingview").show();

			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetPengeluaranBarangTable') ?>",
				data: {
					Tgl_PPB: Tgl_PPB,
					No_PPB: No_PPB,
					No_FDJR: No_FDJR,
					No_PB: No_PB,
					Tipe_PB: Tipe_PB,
					Status_PB
				},
				dataType: "JSON",
				success: function(response) {
					if (response) {
						$('#tablebkbmenu').DataTable().clear().draw();
						ChPengeluaranBarangTable(response);
					}
				}
			});
		}
	);

	function getStringOfArrayString(data) {
		let string = "";

		if (data.length == 0) {
			return string;
		} else {
			data.forEach(datum => string += datum + ", ");

			return string.slice(0, -2);
		}

	}

	function ChPengeluaranBarangTable(JSONChannel) {
		$("#tablebkbmenu > tbody").html('');

		var Channel = JSONChannel;

		if (Channel.PengeluaranBarang != 0) {
			if ($.fn.DataTable.isDataTable('#tablebkbmenu')) {
				$('#tablebkbmenu').DataTable().destroy();
			}

			$('#tablebkbmenu tbody').empty();

			for (i = 0; i < Channel.PengeluaranBarang.length; i++) {
				var picking_order_kode = Channel.PengeluaranBarang[i].picking_order_kode;
				var picking_order_tanggal = Channel.PengeluaranBarang[i].picking_order_tanggal;
				var delivery_order_batch_kode = Channel.PengeluaranBarang[i].delivery_order_batch_kode;
				var delivery_order_batch_tanggal_kirim = Channel.PengeluaranBarang[i].delivery_order_batch_tanggal_kirim;
				var picking_list_kode = Channel.PengeluaranBarang[i].picking_list_kode;
				var picking_list_tgl_kirim = Channel.PengeluaranBarang[i].picking_list_tgl_kirim;
				var picking_list_tipe = Channel.PengeluaranBarang[i].picking_list_tipe;
				var picking_order_plan_tipe = Channel.PengeluaranBarang[i].picking_order_plan_tipe;
				var depo_nama = Channel.PengeluaranBarang[i].depo_nama;
				var picking_order_status = Channel.PengeluaranBarang[i].picking_order_status;
				var karyawan_nama = Channel.PengeluaranBarang[i].karyawan_nama;
				var kendaraan_nopol = Channel.PengeluaranBarang[i].kendaraan_nopol;
				var area = Channel.PengeluaranBarang[i].area;
				// console.log(area);
				var strmenu = '';

				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td class="text-center">' + picking_order_kode + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + picking_order_tanggal + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + delivery_order_batch_kode + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + delivery_order_batch_tanggal_kirim + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + karyawan_nama + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + kendaraan_nopol + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + picking_list_kode + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + picking_list_tgl_kirim + '</td>';
				strmenu = strmenu + '	<td class="text-center">' + picking_order_plan_tipe + '</td>';
				// strmenu = strmenu + '	<td>' + getStringOfArrayString(area) + '</td>';
				strmenu = strmenu + '	<td class="text-center"><a class="btn btn-link" onclick="viewArea(\'' + area + '\')">View Area</a></td>';
				strmenu = strmenu + '	<td class="text-center">' + picking_order_status + '</td>';
				strmenu = strmenu + '	<td class="text-center"><a href="<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/DetailPengeluaranBarangMenu/?picking_order_kode=' + picking_order_kode + '" class="btn btn-link" target="_blank">Lihat Detail</a></td>';
				strmenu = strmenu + '</tr>';

				$("#tablebkbmenu > tbody").append(strmenu);
			}

			$("#loadingview").hide();

			$('#tablebkbmenu').DataTable({
				"lengthMenu": [
					[50],
					[50]
				]
			});
		} else {
			ResetForm();
		}
	}

	$("#btnback").click(
		function() {
			ResetForm();
			GetPengeluaranBarangMenu();
		}
	);

	function viewArea(area) {
		$("#table-area > tbody").empty();

		var area = area.split(",");

		area.sort();

		area.forEach(function(v, i) {
			$("#table-area > tbody").append(`
			<tr>
				<td class="text-center">${i+1}</td>
				<td class="text-center">${v}</td>
			</tr>
			`);

		})

		$("#modalViewArea").modal('show');
	}

	<?php
	if ($Menu_Access["D"] == 1) {
	?>
	<?php
	}
	?>

	<?php
	if ($Menu_Access["U"] == 1) {
	?>

	<?php
	}
	?>

	<?php
	if ($Menu_Access["C"] == 1) {
	?>
		$("#btnaddnewbkbstandar").removeAttr('style');
		$("#btnaddnewbkbbulk").removeAttr('style');

		// Add New Channel
		$("#btnaddnewbkbstandar").click(
			function() {
				ResetForm();
				$("#previewformbkbstandar").modal('show');
			}
		);

		// Add New Channel
		$("#btnaddnewbkbbulk").click(
			function() {
				ResetForm();
				$("#previewformbkbbulk").modal('show');
			}
		);

		// Add New Channel
		$("#btnaddbkbstandar").click(
			function() {
				ResetForm();
				$("#previewformbkbstandar").modal('hide');
				$("#previewaddformbkbstandar").modal('show');
			}
		);

		// Add New Channel
		$("#btnaddbkbbulk").click(
			function() {
				ResetForm();
				$("#previewformbkbbulk").modal('hide');
				$("#previewaddformbkbbulk").modal('show');
			}
		);

		// Add New Channel
		$("#btnbackaddbkbstandar").click(
			function() {
				ResetForm();
				$("#previewformbkbstandar").modal('show');
				$("#previewaddformbkbstandar").modal('hide');
			}
		);

		// Add New Channel
		$("#btnbackaddbkbbulk").click(
			function() {
				ResetForm();
				$("#previewformbkbbulk").modal('show');
				$("#previewaddformbkbbulk").modal('hide');
			}
		);

	<?php
	}
	?>

	function ResetForm() {
		<?php
		if ($Menu_Access["U"] == 1) {
		?>
			$("#txtupdatechannelnama").val('');
			$("#cbupdatechanneljenis").prop('selectedIndex', 0);
			$("#chupdatechannelsppbr").prop('checked', false);
			$("#chupdatechanneltipeecomm").prop('checked', false);
			$("#chupdatechannelisactive").prop('checked', false);

			$("#loadingview").hide();
			$("#btnsaveupdatenewchannel").prop("disabled", false);
		<?php
		}
		?>

		<?php
		if ($Menu_Access["C"] == 1) {
		?>
			$("#txtchannelnama").val('');
			$("#cbchanneljenis").prop('selectedIndex', 0);
			$("#chchannelsppbr").prop('checked', false);
			$("#chchanneltipeecomm").prop('checked', false);
			$("#chchannelisactive").prop('checked', false);

			$("#loadingview").hide();
			$("#btnsaveaddnewchannel").prop("disabled", false);
		<?php
		}
		?>

	}
</script>