<script type="text/javascript">
	var ChannelCode = '';
	loadingBeforeReadyPage()
	$(document).ready(
		function() {
			GetSettlementDetailMenu();
		}
	);

	function GetSettlementDetailMenu() {
		var delivery_order_batch_id = $("#delivery_order_batch_id").val();
		var sku_id = $("#sku_id").val();
		var status = $("#filter_status").val();

		if (status == "Cocok") {
			$("#btn_proses_do_retur").hide();
		} else {
			$("#btn_proses_do_retur").show();
		}

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/SuratTugasPengiriman/GetSettlementDetailMenu') ?>",
			data: {
				delivery_order_batch_id: delivery_order_batch_id,
				sku_id: sku_id
			},
			success: function(response) {
				if (response) {
					ChSettlementDetailMenu(response);
				}
			}
		});
	}

	function ChSettlementDetailMenu(JSONChannel) {
		$("#tableskumenu > tbody").html('');

		var Channel = JSON.parse(JSONChannel);
		var no = 1;

		if (Channel.Settlement != 0) {
			if ($.fn.DataTable.isDataTable('#tableskumenu')) {
				$('#tableskumenu').DataTable().destroy();
			}

			$('#tableskumenu tbody').empty();

			for (i = 0; i < Channel.Settlement.length; i++) {
				var idx = Channel.Settlement[i].idx;
				var groupurut = Channel.Settlement[i].groupurut;
				var skuid = Channel.Settlement[i].skuid;
				var skunama = Channel.Settlement[i].skunama;
				var documentid = Channel.Settlement[i].documentid;
				var tgl = Channel.Settlement[i].tgl;
				var documentno = Channel.Settlement[i].documentno;
				var documentjenis = Channel.Settlement[i].documentjenis;
				var documentstatus = Channel.Settlement[i].documentstatus;
				var qty = Channel.Settlement[i].qty;
				var qtykumulatif = Channel.Settlement[i].qtykumulatif;
				var suggestion = Channel.Settlement[i].suggestion;

				var strmenu = '';

				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td>' + idx + '</td>';
				strmenu = strmenu + '	<td>' + tgl + '</td>';
				if (documentjenis == "BKB") {
					strmenu = strmenu + '	<td><button type="button" id="btn_document_no' + i + '" class="btn btn-link" onclick="ViewDetailBKB(\'' + documentno + '\',\'' + skuid + '\')">' + documentno + '</button></td>';
				} else if (documentjenis == "BTB") {
					strmenu = strmenu + '	<td><button type="button" id="btn_document_no' + i + '" class="btn btn-link" onclick="ViewDetailBTB(\'' + documentno + '\')">' + documentno + '</button></td>';

				} else if (documentjenis == "DO") {
					strmenu = strmenu + '	<td><button type="button" id="btn_document_no' + i + '" class="btn btn-link" onclick="ViewDetailDO(\'' + documentno + '\',\'' + skuid + '\')">' + documentno + '</button></td>';

				} else if (documentjenis == "DOR") {
					strmenu = strmenu + '	<td><button type="button" id="btn_document_no' + i + '" class="btn btn-link" onclick="ViewDetailDOR(\'' + documentno + '\')">' + documentno + '</button></td>';

				}
				strmenu = strmenu + '	<td>' + documentjenis + '</td>';
				strmenu = strmenu + '	<td>' + documentstatus + '</td>';
				strmenu = strmenu + '	<td>' + Math.round(qty) + '</td>';
				strmenu = strmenu + '	<td>' + Math.round(qtykumulatif) + '</td>';
				strmenu = strmenu + '	<td>' + suggestion + '</td>';
				strmenu = strmenu + '</tr>';
				no++;

				$("#tableskumenu > tbody").append(strmenu);
			}

			$("#loadingview").hide();

			$('#tableskumenu').DataTable({
				"lengthMenu": [
					[25],
					[25]
				],
				"ordering": false
			});
		} else {
			$('#tableskumenu').DataTable().clear().draw();
			// ResetForm();
		}
	}

	function ViewDetailBKB(documentno, sku_id) {
		$("#loadingbkb").show();
		$("#previewdetailbkb").modal('show');

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/SuratTugasPengiriman/GetDetailBKB') ?>",
			data: {
				documentno: documentno,
				sku_id: sku_id
			},
			success: function(response) {
				if (response) {
					ChDetailBKB(response);
				}
			}
		});
	}

	function ChDetailBKB(JSONChannel) {
		$("#tabledetailbkb > tbody").html('');

		var Channel = JSON.parse(JSONChannel);

		if (Channel.BKB != 0) {
			var picking_order_id = Channel.BKB[0].picking_order_id;
			var picking_order_kode = Channel.BKB[0].picking_order_kode;
			var picking_order_aktual_h_id = Channel.BKB[0].picking_order_aktual_h_id;
			var picking_order_aktual_kode = Channel.BKB[0].picking_order_aktual_kode;
			var karyawan_id = Channel.BKB[0].karyawan_id;
			var karyawan_nama = Channel.BKB[0].karyawan_nama;

			$("#picking_order_kode").val(picking_order_kode);
			$("#picking_order_aktual_kode").val(picking_order_aktual_kode);
			$("#karyawan_nama").val(karyawan_nama);

			if ($.fn.DataTable.isDataTable('#tabledetailbkb')) {
				$('#tabledetailbkb').DataTable().destroy();
			}

			$('#tabledetailbkb tbody').empty();

			for (i = 0; i < Channel.BKB.length; i++) {

				var delivery_order_kode = Channel.BKB[i].delivery_order_kode;
				var principal = Channel.BKB[i].principal;
				var brand = Channel.BKB[i].brand;
				var sku_id = Channel.BKB[i].sku_id;
				var sku_stock_qty_ambil = Channel.BKB[i].sku_stock_qty_ambil;
				var sku_kode = Channel.BKB[i].sku_kode;
				var sku_nama_produk = Channel.BKB[i].sku_nama_produk;
				var sku_kemasan = Channel.BKB[i].sku_kemasan;
				var sku_satuan = Channel.BKB[i].sku_satuan;
				var karyawan_nama = Channel.BKB[i].karyawan_nama;
				var picking_order_plan_id = Channel.BKB[i].picking_order_plan_id;
				var delivery_order_id = Channel.BKB[i].delivery_order_id;
				var sku_stock_id = Channel.BKB[i].sku_stock_id;
				var sku_stock_expired_date = Channel.BKB[i].sku_stock_expired_date;
				var sku_stock_expired_date_plan = Channel.BKB[i].sku_stock_expired_date_plan;
				var sku_stock_qty_ambil_plan = Channel.BKB[i].sku_stock_qty_ambil_plan;

				var strmenu = '';

				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td>' + delivery_order_kode + '</td>';
				strmenu = strmenu + '	<td>' + sku_kode + '</td>';
				strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
				strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
				strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
				strmenu = strmenu + '	<td>' + sku_stock_qty_ambil_plan + '</td>';
				strmenu = strmenu + '	<td>' + sku_stock_expired_date_plan + '</td>';
				strmenu = strmenu + '	<td>' + sku_stock_qty_ambil + '</td>';
				strmenu = strmenu + '	<td>' + sku_stock_expired_date + '</td>';
				strmenu = strmenu + '</tr>';

				$("#tabledetailbkb > tbody").append(strmenu);
			}

			$("#loadingbkb").hide();
			$('#tabledetailbkb').DataTable({
				"lengthMenu": [
					[10],
					[10]
				],
				"paging": false,
				"ordering": false,
				"info": false,
				"searching": false
			});
		}

	}

	function ViewDetailBTB(documentno) {
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/SuratTugasPengiriman/GetSettlementDetailMenu') ?>",
			data: {
				documentno: documentno
			},
			success: function(response) {
				if (response) {
					ChSettlementDetailMenu(response);
				}
			}
		});
	}

	function ViewDetailDO(documentno, sku_id) {
		$("#loadingbkb").show();
		$("#previewdetaildo").modal('show');

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/SuratTugasPengiriman/GetDetailDO') ?>",
			data: {
				documentno: documentno,
				sku_id: sku_id
			},
			success: function(response) {
				if (response) {
					ChDetailDo(response);
				}
			}
		});
	}

	function ChDetailDo(JSONChannel) {
		$("#tabledodetail > tbody").html('');

		$("#delivery_order_id").html('');
		$("#delivery_order_kode").html('');
		$("#tipe_delivery_order_id").html('');
		$("#tipe_delivery_order_nama").html('');
		$("#delivery_order_status").html('');
		$("#delivery_order_tgl_buat_do").html('');
		$("#delivery_order_tgl_expired_do").html('');
		$("#delivery_order_tgl_surat_jalan").html('');
		$("#delivery_order_tgl_rencana_kirim").html('');
		$("#client_wms_nama").html('');
		$("#client_wms_alamat").html('');
		$("#delivery_order_keterangan").html('');
		$("#delivery_order_tipe_pembayaran").html('');
		$("#delivery_order_tipe_layanan").html('');
		$("#delivery_order_kirim_nama").html('');
		$("#delivery_order_kirim_alamat").html('');
		$("#delivery_order_kirim_area").html('');
		$("#delivery_order_ambil_nama").html('');
		$("#delivery_order_ambil_alamat").html('');
		$("#delivery_order_ambil_area").html('');

		var Channel = JSON.parse(JSONChannel);

		if (Channel.HeaderDO != 0) {
			var delivery_order_id = Channel.HeaderDO[0].delivery_order_id;
			var delivery_order_kode = Channel.HeaderDO[0].delivery_order_kode;
			var tipe_delivery_order_id = Channel.HeaderDO[0].tipe_delivery_order_id;
			var tipe_delivery_order_alias = Channel.HeaderDO[0].tipe_delivery_order_alias;
			var delivery_order_status = Channel.HeaderDO[0].delivery_order_status;
			var delivery_order_tgl_buat_do = Channel.HeaderDO[0].delivery_order_tgl_buat_do;
			var delivery_order_tgl_expired_do = Channel.HeaderDO[0].delivery_order_tgl_expired_do;
			var delivery_order_tgl_surat_jalan = Channel.HeaderDO[0].delivery_order_tgl_surat_jalan;
			var delivery_order_tgl_rencana_kirim = Channel.HeaderDO[0].delivery_order_tgl_rencana_kirim;
			var client_wms_nama = Channel.HeaderDO[0].client_wms_nama;
			var client_wms_alamat = Channel.HeaderDO[0].client_wms_alamat;
			var delivery_order_keterangan = Channel.HeaderDO[0].delivery_order_keterangan;
			var delivery_order_tipe_pembayaran = Channel.HeaderDO[0].delivery_order_tipe_pembayaran;
			var delivery_order_tipe_layanan = Channel.HeaderDO[0].delivery_order_tipe_layanan;
			var delivery_order_kirim_nama = Channel.HeaderDO[0].delivery_order_kirim_nama;
			var delivery_order_kirim_alamat = Channel.HeaderDO[0].delivery_order_kirim_alamat;
			var delivery_order_kirim_area = Channel.HeaderDO[0].delivery_order_kirim_area;
			var delivery_order_ambil_nama = Channel.HeaderDO[0].delivery_order_ambil_nama;
			var delivery_order_ambil_alamat = Channel.HeaderDO[0].delivery_order_ambil_alamat;
			var delivery_order_ambil_area = Channel.HeaderDO[0].delivery_order_ambil_area;

			if (delivery_order_ambil_nama == "") {
				$("#panel-factory").hide();
			}

			$("#delivery_order_id").append(delivery_order_id);
			$("#delivery_order_kode").append(delivery_order_kode);
			$("#tipe_delivery_order_id").append(tipe_delivery_order_id);
			$("#tipe_delivery_order_nama").append(tipe_delivery_order_alias);
			$("#delivery_order_status").append(delivery_order_status);
			$("#delivery_order_tgl_buat_do").append(delivery_order_tgl_buat_do);
			$("#delivery_order_tgl_expired_do").append(delivery_order_tgl_expired_do);
			$("#delivery_order_tgl_surat_jalan").append(delivery_order_tgl_surat_jalan);
			$("#delivery_order_tgl_rencana_kirim").append(delivery_order_tgl_rencana_kirim);
			$("#client_wms_nama").append(client_wms_nama);
			$("#client_wms_alamat").append(client_wms_alamat);
			$("#delivery_order_keterangan").append(delivery_order_keterangan);
			$("#delivery_order_tipe_pembayaran").append(delivery_order_tipe_pembayaran);
			$("#delivery_order_tipe_layanan").append(delivery_order_tipe_layanan);
			$("#delivery_order_kirim_nama").append(delivery_order_kirim_nama);
			$("#delivery_order_kirim_alamat").append(delivery_order_kirim_alamat);
			$("#delivery_order_kirim_area").append(delivery_order_kirim_area);
			$("#delivery_order_ambil_nama").append(delivery_order_ambil_nama);
			$("#delivery_order_ambil_alamat").append(delivery_order_ambil_alamat);
			$("#delivery_order_ambil_area").append(delivery_order_ambil_area);

			if ($.fn.DataTable.isDataTable('#tabledodetail')) {
				$('#tabledodetail').DataTable().destroy();
			}

			$('#tabledodetail tbody').empty();

			for (i = 0; i < Channel.DetailDO.length; i++) {
				var sku_id = Channel.DetailDO[i].sku_id;
				var sku_kode = Channel.DetailDO[i].sku_kode;
				var sku_nama_produk = Channel.DetailDO[i].sku_nama_produk;
				var sku_kemasan = Channel.DetailDO[i].sku_kemasan;
				var sku_satuan = Channel.DetailDO[i].sku_satuan;
				var sku_request_expdate = Channel.DetailDO[i].sku_request_expdate;
				var sku_keterangan = Channel.DetailDO[i].sku_keterangan;
				var sku_qty = Channel.DetailDO[i].sku_qty;
				var sku_qty_kirim = Channel.DetailDO[i].sku_qty_kirim;

				var strmenu = '';

				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td>' + sku_kode + '</td>';
				strmenu = strmenu + '	<td></td>';
				strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
				strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
				strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
				strmenu = strmenu + '	<td>' + sku_request_expdate + '</td>';
				strmenu = strmenu + '	<td>' + sku_keterangan + '</td>';
				if (sku_qty == sku_qty_kirim) {

					strmenu = strmenu + '	<td><input type="text" style="width:80%;" class="form-control" name="txt_sku_qty' + i + '" id="txt_sku_qty' + i + '" value="' + sku_qty + '" readonly onchange="UpdateDOSkuQty(\'' + delivery_order_id + '\',\'' + sku_id + '\', this.value,\'' + sku_qty_kirim + '\',\'' + i + '\')"></td>';
				} else {

					strmenu = strmenu + '	<td><input type="text" style="width:80%;" class="form-control" name="txt_sku_qty' + i + '" id="txt_sku_qty' + i + '" value="' + sku_qty + '" onchange="UpdateDOSkuQty(\'' + delivery_order_id + '\',\'' + sku_id + '\', this.value,\'' + sku_qty_kirim + '\',\'' + i + '\')"></td>';
				}
				strmenu = strmenu + '	<td>' + sku_qty_kirim + '</td>';
				strmenu = strmenu + '</tr>';

				$("#tabledodetail > tbody").append(strmenu);
			}

			$("#loadingbkb").hide();
			$('#tabledodetail').DataTable({
				"lengthMenu": [
					[10],
					[10]
				],
				"paging": false,
				"ordering": false,
				"info": false,
				"searching": false
			});
		}

	}

	function ViewDetailDOR(documentno) {
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/SuratTugasPengiriman/GetSettlementDetailMenu') ?>",
			data: {
				documentno: documentno
			},
			success: function(response) {
				if (response) {
					ChSettlementDetailMenu(response);
				}
			}
		});
	}

	function UpdateDOSkuQty(delivery_order_id, sku_id, sku_qty, sku_qty_kirim, i) {
		if (sku_qty > 0) {

			if (sku_qty <= sku_qty_kirim) {
				$.ajax({
					async: false,
					type: 'POST',
					url: "<?= base_url('WMS/SuratTugasPengiriman/UpdateDOSkuQty') ?>",
					data: {
						delivery_order_id: delivery_order_id,
						sku_id: sku_id,
						sku_qty: sku_qty
					},
					success: function(response) {
						if (response == 1) {
							var msg = '<span name="CAPTION-ALERT-SKUSUCCESSUDATE">SKU Qty Berhasil Diupdate</span>';
							var msgtype = 'success';

							//if (!window.__cfRLUnblockHandlers) return false;
							Swal.fire({
								position: 'center',
								icon: msgtype,
								title: msg,
								timer: 1000
							});

						} else {
							if (response == 0) {
								var msg = '<span name="CAPTION-ALERT-SKUGAGALUDATE">SKU Qty Gagal Diupdate</span>';
							} else {
								var msg = response;
							}
							var msgtype = 'error';

							// if (!window.__cfRLUnblockHandlers) return false;
							Swal.fire({
								icon: msgtype,
								title: 'Error',
								text: msg
							});

						}
					}
				})
			} else {
				var msg = '<span name="CAPTION-ALERT-SKUQTYMELEBIHIQTYKIRIM">SKU Qty Melebihi SKU Qty Kirim</span>';
				var msgtype = 'error';

				// if (!window.__cfRLUnblockHandlers) return false;
				Swal.fire({
					icon: msgtype,
					title: 'Error',
					text: msg
				});

				$("#txt_sku_qty" + i).val(sku_qty_kirim);
			}
		} else {
			var msg = '<span name="CAPTION-ALERT-SKUQTYTIDAKBOLEH0">SKU Qty Tidak Boleh 0</span>';
			var msgtype = 'error';

			// if (!window.__cfRLUnblockHandlers) return false;
			Swal.fire({
				icon: msgtype,
				title: 'Error',
				text: msg
			});

			$("#txt_sku_qty" + i).val(0);
		}
	}

	$("#btnback").click(
		function() {
			GetSettlementDetailMenu();
		}
	);

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