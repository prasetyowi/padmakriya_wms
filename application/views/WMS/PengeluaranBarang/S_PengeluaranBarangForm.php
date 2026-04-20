<script type="text/javascript">
	var ChannelCode = '';

	loadingBeforeReadyPage();

	$(document).ready(
		function() {
			$("#btnkonfirmasibarang").prop("disabled", true);
			$("#btnbatalbkb").prop("disabled", true);

			GetPengeluaranBarangForm();
			$("#slcnopb").select2();
			$('#slccheckerpb').select2();

		}
	);

	// function message(msg, msgtext, msgtype) {
	// 	Swal.fire({
	// 		title: msg,
	// 		html: msgtext,
	// 		icon: msgtype
	// 	});
	// }


	function GetPengeluaranBarangForm() {
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetFormPengeluaranBarangMenu') ?>",
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
		$("#tabledetailpbstandar > tbody").html('');
		$("#tabledetailpbbulk > tbody").html('');

		var Channel = JSON.parse(JSONChannel);

		var StatusC = Channel.AuthorityMenu[0].status_c;
		var StatusU = Channel.AuthorityMenu[0].status_u;
		var StatusD = Channel.AuthorityMenu[0].status_d;

		if (StatusC == 0) {
			$("#btnkonfirmasibarang").attr('style', 'display: none;');
		}
		if (StatusC == 0) {
			$("#btnsavebkb").attr('style', 'display: none;');
		}
		if (StatusC == 0) {
			$("#btnbatalbkb").attr('style', 'display: none;');
		}
		if (StatusC == 0) {
			$("#btnaddnewchannel").attr('style', 'display: none;');
		}

		$("#slcnopb").html('');
		$("#slctipepb").html('');
		$('#slccheckerpb').html('');

		if (Channel.NoPB != 0) {
			$("#slcnopb").append('<option value="">** Pilih NO PB **</option>');

			for (i = 0; i < Channel.NoPB.length; i++) {
				picking_list_id = Channel.NoPB[i].picking_list_id;
				picking_list_kode = Channel.NoPB[i].picking_list_kode;
				karyawan_nama = Channel.NoPB[i].karyawan_nama;
				$("#slcnopb").append('<option value="' + picking_list_id + '">' + picking_list_kode + ' - ' + karyawan_nama + '</option>');
			}
		}

		// if( Channel.TipeDO != 0 )
		// {	
		// 	for( i=0 ; i<Channel.TipeDO.length ; i++ )
		// 	{
		// 		tipe_delivery_order_id = Channel.TipeDO[i].tipe_delivery_order_id;
		// 		tipe_delivery_order_alias = Channel.TipeDO[i].tipe_delivery_order_alias;
		// 		$("#slctipepb").append( '<option value="'+ tipe_delivery_order_id +' || '+ tipe_delivery_order_alias +'">'+ tipe_delivery_order_alias +'</option>' );
		// 	}
		// }
	}

	function GetFormPengeluaranBarang(picking_list_id) {
		$("#loadingview").show();

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetFormPengeluaranBarang') ?>",
			data: "picking_list_id=" + picking_list_id,
			success: function(response) {
				if (response) {
					$('#tabledetailpbbulk').DataTable().clear().draw();
					$('#tabledetailpbstandar').DataTable().clear().draw();
					$('#tabledetailpbkirimulang').DataTable().clear().draw();
					$('#tabledetailpbcanvas').DataTable().clear().draw();
					ChFormPengeluaranBarang(response);
				}
			}
		});
	}

	function ChFormPengeluaranBarang(JSONChannel) {
		$("#tabledetailpbbulk > tbody").html('');
		$("#tabledetailpbstandar > tbody").html('');
		$("#slcgudang").html('');
		$("#slctipepb").html('');
		$("#slcstatusppb").html('');
		$('#slccheckerpb').html('');
		$("#txtcheckerpb").val('')
		$('#slcaddcheckerbkb').html('');

		var Channel = JSON.parse(JSONChannel);

		var StatusC = Channel.AuthorityMenu[0].status_c;
		var StatusU = Channel.AuthorityMenu[0].status_u;
		var StatusD = Channel.AuthorityMenu[0].status_d;

		if (StatusC == 0) {
			$("#btnkonfirmasibarang").attr('style', 'display: none;');
		}
		if (StatusC == 0) {
			$("#btnsavebkb").attr('style', 'display: none;');
		}
		if (StatusC == 0) {
			$("#btnbatalbkb").attr('style', 'display: none;');
		}
		if (StatusC == 0) {
			$("#btnaddnewchannel").attr('style', 'display: none;');
		}

		if (Channel.PengeluaranBarang != 0) {
			var picking_list_id = Channel.PengeluaranBarang[0].picking_list_id;
			var depo_id = Channel.PengeluaranBarang[0].depo_id;
			var depo_detail_id = Channel.PengeluaranBarang[0].depo_detail_id;
			var depo_detail_nama = Channel.PengeluaranBarang[0].depo_detail_nama;
			var delivery_order_batch_kode = Channel.PengeluaranBarang[0].delivery_order_batch_kode;
			var tipe_delivery_order_id = Channel.PengeluaranBarang[0].tipe_delivery_order_id;
			var picking_list_tipe = Channel.PengeluaranBarang[0].picking_list_tipe;
			var tipe_delivery_order_nama = Channel.PengeluaranBarang[0].tipe_delivery_order_nama;
			var karyawan_id = Channel.PengeluaranBarang[0].karyawan_id;
			var karyawan_nama = Channel.PengeluaranBarang[0].karyawan_nama;
			// var lastUpdated = Channel.PengeluaranBarang[0].picking_list_tgl_update;

			var NoPPB = Channel.NoPPB;

			$("#txtpickinglistid").val(picking_list_id);
			$("#txtnoppb").val(NoPPB);
			$("#txtdepoid").val(depo_id);
			$("#txtnofdjr").val(delivery_order_batch_kode);
			$("#slcgudang").append('<option value="' + depo_detail_id + '">' + depo_detail_nama + '</option>');
			$("#slctipepb").append('<option value="' + tipe_delivery_order_id + ' || ' + picking_list_tipe + '">' + picking_list_tipe + '</option>');
			$("#txtcheckerpb").val(Channel.PengeluaranBarang[0].karyawan_id + ' || ' + Channel.PengeluaranBarang[0].karyawan_nama);
			// $("#lastUpdated").val(lastUpdated);
			$("#slcaddcheckerbkb").append('<option value="' + karyawan_id + '">' + karyawan_nama + '</option>');
			// $("#slcstatusppb").append('<option value="Draft">Draft</option>');

			if (tipe_delivery_order_nama == "Bulk" || tipe_delivery_order_nama == "Flush Out") {
				$("#panelbkbbulk").show();
				$("#panelbkbstandar").hide();
				$("#panelbkbkirimulang").hide();
				$("#panelbkbcanvas").hide();

				$.ajax({
					type: 'POST',
					url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetFormPengeluaranBarangBulk') ?>",
					data: "picking_list_id=" + picking_list_id,
					success: function(response) {
						if (response) {
							ChFormPengeluaranBarangBulk(response);
							$("#loadingview").hide();
						}
					}
				});
			} else if (tipe_delivery_order_nama == "Standar") {
				$("#panelbkbbulk").hide();
				$("#panelbkbstandar").show();
				$("#panelbkbkirimulang").hide();
				$("#panelbkbcanvas").hide();

				$.ajax({
					type: 'POST',
					url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetFormPengeluaranBarangStandar') ?>",
					data: "picking_list_id=" + picking_list_id,
					success: function(response) {
						if (response) {
							ChFormPengeluaranBarangStandar(response);
							$("#loadingview").hide();
						}
					}
				});
			} else if (tipe_delivery_order_nama == "Reschedule") {
				$("#panelbkbbulk").hide();
				$("#panelbkbstandar").hide();
				$("#panelbkbkirimulang").show();
				$("#panelbkbcanvas").hide();

				$.ajax({
					type: 'POST',
					url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetFormPengeluaranBarangKirimUlang') ?>",
					data: "picking_list_id=" + picking_list_id,
					success: function(response) {
						if (response) {
							ChFormPengeluaranBarangKirimUlang(response);
							$("#loadingview").hide();
						}
					}
				});
			} else if (tipe_delivery_order_nama == "Canvas") {
				$("#panelbkbbulk").hide();
				$("#panelbkbstandar").hide();
				$("#panelbkbkirimulang").hide();
				$("#panelbkbcanvas").show();

				$.ajax({
					type: 'POST',
					url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetFormPengeluaranBarangCanvas') ?>",
					data: "picking_list_id=" + picking_list_id,
					success: function(response) {
						if (response) {
							ChFormPengeluaranBarangCanvas(response);
							$("#loadingview").hide();
						}
					}
				});
			} else if (tipe_delivery_order_nama == "Mix") {
				$("#panelbkbbulk").show();
				$("#panelbkbstandar").show();
				$("#panelbkbkirimulang").show();
				$("#panelbkbcanvas").hide();

				$.ajax({
					type: 'POST',
					url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetFormPengeluaranBarangMix') ?>",
					data: "picking_list_id=" + picking_list_id,
					success: function(response) {
						if (response) {
							ChFormPengeluaranBarangMix(response);
							$("#loadingview").hide();
						}
					}
				});
			}

			$("#loadingview").hide();
		} else {
			$("#loadingview").hide();
			ResetForm();
		}
	}

	function ChFormPengeluaranBarangBulk(JSONChannel) {
		$("#tabledetailpbbulk > tbody").html('');

		var Channel = JSON.parse(JSONChannel);

		// var karyawan_id_checker = Channel.Checker[0].karyawan_id;
		// var karyawan_nama_checker = Channel.Checker[0].karyawan_nama;

		// $("#txtcheckerpb").val(karyawan_id_checker + ' || ' + karyawan_nama_checker);

		// if (Channel.Checker != 0) {
		// 	for (i = 0; i < Channel.Checker.length; i++) {
		// 		karyawan_id_checker = Channel.Checker[i].karyawan_id;
		// 		karyawan_nama_checker = Channel.Checker[i].karyawan_nama;

		// 		$("#txtcheckerpb").val( karyawan_id_checker + ' || ' + karyawan_nama_checker );
		// 	}
		// }

		if (Channel.PengeluaranBarangBulk != 0) {
			var no = 1;

			if ($.fn.DataTable.isDataTable('#tabledetailpbbulk')) {
				$('#tabledetailpbbulk').DataTable().destroy();
			}

			$('#tabledetailpbbulk tbody').empty();

			for (i = 0; i < Channel.PengeluaranBarangBulk.length; i++) {
				var principal = Channel.PengeluaranBarangBulk[i].principal;
				var sku_kode = Channel.PengeluaranBarangBulk[i].sku_kode;
				var sku_nama_produk = Channel.PengeluaranBarangBulk[i].sku_nama_produk;
				var sku_kemasan = Channel.PengeluaranBarangBulk[i].sku_kemasan;
				var sku_satuan = Channel.PengeluaranBarangBulk[i].sku_satuan;
				var sku_qty_order = Channel.PengeluaranBarangBulk[i].sku_qty_order;
				var sku_stock_expired_date = Channel.PengeluaranBarangBulk[i].sku_stock_expired_date;
				var rak_nama = Channel.PengeluaranBarangBulk[i].rak_nama;
				var karyawan_id_checker = Channel.PengeluaranBarangBulk[i].karyawan_id;
				var karyawan_nama_checker = Channel.PengeluaranBarangBulk[i].karyawan_nama;

				var strmenu = '';

				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td>' + no + '</td>';
				strmenu = strmenu + '	<td>' + principal + '</td>';
				strmenu = strmenu + '	<td>' + sku_kode + '</td>';
				strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
				strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
				strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
				strmenu = strmenu + '	<td>' + Math.round(sku_qty_order) + '</td>';
				strmenu = strmenu + '	<td>' + sku_stock_expired_date + '</td>';
				strmenu = strmenu + '	<td>' + rak_nama + '</td>';
				strmenu = strmenu + '	<td></td>';
				strmenu = strmenu + '	<td></td>';
				// strmenu = strmenu + `	<td>
				// 													<input type="hidden" class="form-control" id="txtcheckerpb_${i}" value="${karyawan_id_checker}||${karyawan_nama_checker}"/>
				// 													${karyawan_nama_checker}
				// 											</td>`;
				strmenu = strmenu + '</tr>';

				$("#tabledetailpbbulk > tbody").append(strmenu);
				no++;
			}
			$('#tabledetailpbbulk').DataTable({
				"lengthMenu": [
					[10],
					[10]
				]
			});
		}
	}

	function ChFormPengeluaranBarangStandar(JSONChannel) {
		$("#tabledetailpbstandar > tbody").html('');

		var Channel = JSON.parse(JSONChannel);

		$('#slccheckerpb').append('<option value="">** Pilih Checker Gudang **</option>');
		if (Channel.Checker != 0) {
			for (i = 0; i < Channel.Checker.length; i++) {
				karyawan_id_checker = Channel.Checker[i].karyawan_id;
				karyawan_nama_checker = Channel.Checker[i].karyawan_nama;

				$("#slccheckerpb").append('<option value="' + karyawan_id_checker + ' || ' + karyawan_nama_checker + '">' + karyawan_nama_checker + '</option>');
			}
		}

		if (Channel.PengeluaranBarangStandar != 0) {
			var no = 1;

			if ($.fn.DataTable.isDataTable('#tabledetailpbstandar')) {
				$('#tabledetailpbstandar').DataTable().destroy();
			}

			$('#tabledetailpbstandar tbody').empty();

			for (i = 0; i < Channel.PengeluaranBarangStandar.length; i++) {
				var delivery_order_kode = Channel.PengeluaranBarangStandar[i].delivery_order_kode;
				var principal = Channel.PengeluaranBarangStandar[i].principal;
				var sku_kode = Channel.PengeluaranBarangStandar[i].sku_kode;
				var sku_nama_produk = Channel.PengeluaranBarangStandar[i].sku_nama_produk;
				var sku_kemasan = Channel.PengeluaranBarangStandar[i].sku_kemasan;
				var sku_satuan = Channel.PengeluaranBarangStandar[i].sku_satuan;
				var sku_qty_order = Channel.PengeluaranBarangStandar[i].sku_qty_order;
				var sku_stock_expired_date = Channel.PengeluaranBarangStandar[i].sku_stock_expired_date;
				// var rak_nama = Channel.PengeluaranBarangStandar[i].rak_nama;

				var strmenu = '';

				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td>' + no + '</td>';
				strmenu = strmenu + '	<td>' + delivery_order_kode + '</td>';
				strmenu = strmenu + '	<td>' + sku_kode + '</td>';
				strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
				strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
				strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
				strmenu = strmenu + '	<td>' + Math.round(sku_qty_order) + '</td>';
				strmenu = strmenu + '	<td>' + sku_stock_expired_date + '</td>';
				strmenu = strmenu + '	<td></td>';
				strmenu = strmenu + '	<td></td>';
				strmenu = strmenu + '	<td></td>';
				strmenu = strmenu + '</tr>';

				$("#tabledetailpbstandar > tbody").append(strmenu);
				no++;
			}

			$('#tabledetailpbstandar').DataTable({
				"lengthMenu": [
					[10],
					[10]
				]
			});
		}
	}

	function ChFormPengeluaranBarangKirimUlang(JSONChannel) {
		$("#tabledetailpbkirimulang > tbody").html('');

		var Channel = JSON.parse(JSONChannel);

		if (Channel.PengeluaranBarangKirimUlang != 0) {
			var no = 1;

			if ($.fn.DataTable.isDataTable('#tabledetailpbkirimulang')) {
				$('#tabledetailpbkirimulang').DataTable().destroy();
			}

			$('#tabledetailpbkirimulang tbody').empty();

			for (i = 0; i < Channel.PengeluaranBarangKirimUlang.length; i++) {
				var principal = Channel.PengeluaranBarangKirimUlang[i].principal;
				var sku_kode = Channel.PengeluaranBarangKirimUlang[i].sku_kode;
				var sku_nama_produk = Channel.PengeluaranBarangKirimUlang[i].sku_nama_produk;
				var sku_kemasan = Channel.PengeluaranBarangKirimUlang[i].sku_kemasan;
				var sku_satuan = Channel.PengeluaranBarangKirimUlang[i].sku_satuan;
				var sku_qty_order = Channel.PengeluaranBarangKirimUlang[i].sku_qty_order;
				var sku_stock_expired_date = Channel.PengeluaranBarangKirimUlang[i].sku_stock_expired_date;
				var rak_nama = Channel.PengeluaranBarangKirimUlang[i].rak_nama;
				var karyawan_id_checker = Channel.PengeluaranBarangKirimUlang[i].karyawan_id;
				var karyawan_nama_checker = Channel.PengeluaranBarangKirimUlang[i].karyawan_nama;

				var strmenu = '';

				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td>' + no + '</td>';
				strmenu = strmenu + '	<td>' + principal + '</td>';
				strmenu = strmenu + '	<td>' + sku_kode + '</td>';
				strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
				strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
				strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
				strmenu = strmenu + '	<td>' + Math.round(sku_qty_order) + '</td>';
				strmenu = strmenu + '	<td>' + sku_stock_expired_date + '</td>';
				strmenu = strmenu + '	<td>' + rak_nama + '</td>';
				strmenu = strmenu + '	<td></td>';
				strmenu = strmenu + '	<td></td>';
				// strmenu = strmenu + `	<td>
				// 													<input type="hidden" class="form-control" id="txtcheckerpb_${i}" value="${karyawan_id_checker}||${karyawan_nama_checker}"/>
				// 													${karyawan_nama_checker}
				// 											</td>`;
				strmenu = strmenu + '</tr>';

				$("#tabledetailpbkirimulang > tbody").append(strmenu);
				no++;
			}
			$('#tabledetailpbkirimulang').DataTable({
				"lengthMenu": [
					[10],
					[10]
				]
			});
		}
	}

	function ChFormPengeluaranBarangCanvas(JSONChannel) {
		$("#tabledetailpbcanvas > tbody").html('');

		var Channel = JSON.parse(JSONChannel);

		if (Channel.PengeluaranBarangCanvas != 0) {
			var no = 1;

			if ($.fn.DataTable.isDataTable('#tabledetailpbcanvas')) {
				$('#tabledetailpbcanvas').DataTable().destroy();
			}

			$('#tabledetailpbcanvas tbody').empty();

			for (i = 0; i < Channel.PengeluaranBarangCanvas.length; i++) {
				var principal = Channel.PengeluaranBarangCanvas[i].principal;
				var sku_kode = Channel.PengeluaranBarangCanvas[i].sku_kode;
				var sku_nama_produk = Channel.PengeluaranBarangCanvas[i].sku_nama_produk;
				var sku_kemasan = Channel.PengeluaranBarangCanvas[i].sku_kemasan;
				var sku_satuan = Channel.PengeluaranBarangCanvas[i].sku_satuan;
				var sku_qty_order = Channel.PengeluaranBarangCanvas[i].sku_qty_order;
				var sku_stock_expired_date = Channel.PengeluaranBarangCanvas[i].sku_stock_expired_date;
				var rak_nama = Channel.PengeluaranBarangCanvas[i].rak_nama;
				var karyawan_id_checker = Channel.PengeluaranBarangCanvas[i].karyawan_id;
				var karyawan_nama_checker = Channel.PengeluaranBarangCanvas[i].karyawan_nama;

				var strmenu = '';

				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td>' + no + '</td>';
				strmenu = strmenu + '	<td>' + principal + '</td>';
				strmenu = strmenu + '	<td>' + sku_kode + '</td>';
				strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
				strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
				strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
				strmenu = strmenu + '	<td>' + Math.round(sku_qty_order) + '</td>';
				strmenu = strmenu + '	<td>' + sku_stock_expired_date + '</td>';
				strmenu = strmenu + '	<td>' + rak_nama + '</td>';
				strmenu = strmenu + '	<td></td>';
				strmenu = strmenu + '	<td></td>';
				// strmenu = strmenu + `	<td>
				// 													<input type="hidden" class="form-control" id="txtcheckerpb_${i}" value="${karyawan_id_checker}||${karyawan_nama_checker}"/>
				// 													${karyawan_nama_checker}
				// 											</td>`;
				strmenu = strmenu + '</tr>';

				$("#tabledetailpbcanvas > tbody").append(strmenu);
				no++;
			}
			$('#tabledetailpbcanvas').DataTable({
				"lengthMenu": [
					[10],
					[10]
				]
			});
		}
	}

	function ChFormPengeluaranBarangMix(JSONChannel) {

		var Channel = JSON.parse(JSONChannel);

		if (Channel.PengeluaranBarangMix != 0) {

			for (i = 0; i < Channel.PengeluaranBarangMix.length; i++) {
				var picking_list_id = Channel.PengeluaranBarangMix[i].picking_list_id;
				var tipe_delivery_order_id = Channel.PengeluaranBarangMix[i].tipe_delivery_order_id;
				var tipe_delivery_order_nama = Channel.PengeluaranBarangMix[i].tipe_delivery_order_nama;

				if (tipe_delivery_order_nama == "Bulk") {

					$.ajax({
						type: 'POST',
						url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetFormPengeluaranBarangMixBulk') ?>",
						data: {
							picking_list_id: picking_list_id
						},
						success: function(response) {
							if (response) {
								ChFormPengeluaranBarangBulk(response);
							}
						}
					});

				} else if (tipe_delivery_order_nama == "Standar") {

					$.ajax({
						type: 'POST',
						url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetFormPengeluaranBarangMixStandar') ?>",
						data: {
							picking_list_id: picking_list_id
						},
						success: function(response) {
							if (response) {
								ChFormPengeluaranBarangStandar(response);
							}
						}
					});

				} else if (tipe_delivery_order_nama == "Reschedule") {

					$.ajax({
						type: 'POST',
						url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetFormPengeluaranBarangMixKirimUlang') ?>",
						data: {
							picking_list_id: picking_list_id
						},
						success: function(response) {
							if (response) {
								ChFormPengeluaranBarangKirimUlang(response);
							}
						}
					});

				}
			}

			$("#loadingview").hide();
		}
	}

	$("#btnback").click(
		function() {
			ResetForm();
			GetPengeluaranBarang();
		}
	);

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

		function ConfirmDilaksanakan() {
			var checkBox = document.getElementById("chkpb");

			if (checkBox.checked == true) {
				$("#slcstatusppb").html('');
				$("#slcstatusppb").append('<option value="Dilaksanakan">Dilaksanakan</option>');
				$("#btnkonfirmasibarang").prop("disabled", false);
				$("#btnbatalbkb").prop("disabled", true);
			} else {
				checkBox.checked = false;

				$("#slcstatusppb").html('');
				$("#slcstatusppb").append('<option value="Draft">Draft</option>');
				$("#btnkonfirmasibarang").prop("disabled", true);
				$("#btnbatalbkb").prop("disabled", false);
			}
		}

		$("#btnsavebkb").click(
			function() {
				$("#previewkonfirmasibkb").modal('show');
			}

		);

		$("#btnyeslaksanakanbkb").click(
			function() {

				var picking_list_id = $("#txtpickinglistid").val();
				var depo_id = $("#txtdepoid").val();
				// var depo_detail_id = $("#slcgudang").val();
				var picking_order_tanggal = $("#dateppb").val();
				var picking_order_kode = $("#txtnoppb").val();
				var picking_order_keterangan = $("#txtketerangan").val();
				var tipe_do_arr = $("#slctipepb").val().split(" || ");
				var picking_order_type = tipe_do_arr[0];
				var tipe_do = tipe_do_arr[1];
				var no_pb = $("#slcnopb").val();
				var picking_order_status = $("#slcstatusppb").val();
				var karyawan = "";
				// karyawanTemp = [];
				let ckhKaryawan = $("#slccheckerpb").val();
				// const lastUpdated = $("#lastUpdated").val();

				if (tipe_do == "Mix") {
					let countStandar = $("#tabledetailpbstandar > tbody tr").length;
					if (countStandar > 0) {
						if (ckhKaryawan === "") {
							message("Error!", "<span>Checker tidak boleh kosong</span>", "error");
							return false;
						} else {
							karyawan = ckhKaryawan;
						}
					}
				}

				if (tipe_do == "Standar") {
					if (ckhKaryawan === "") {
						message("Error!", "<span>Checker tidak boleh kosong</span>", "error");
						return false;
					} else {
						karyawan = ckhKaryawan;
					}
				}

				if (tipe_do == "Reschedule") {
					let countStandar = $("#tabledetailpbkirimulang > tbody tr").length;
					if (countStandar > 0) {
						if (ckhKaryawan === "") {
							message("Error!", "<span>Checker tidak boleh kosong</span>", "error");
							return false;
						} else {
							karyawan = ckhKaryawan;
						}
					}
				}

				if (tipe_do == "Canvas") {
					let countStandar = $("#tabledetailpbcanvas > tbody tr").length;
					if (countStandar > 0) {
						if (ckhKaryawan === "") {
							message("Error!", "<span>Checker tidak boleh kosong</span>", "error");
							return false;
						} else {
							karyawan = ckhKaryawan;
						}
					}
				}

				if (['Bulk', 'Reschedule', 'Canvas'].includes(tipe_do)) {
					karyawan = $("#txtcheckerpb").val()
				}

				// if (tipe_do == "Bulk" || tipe_do == "Mix") {
				// 	$("#tabledetailpbbulk > tbody tr").each(function(i, v) {
				// 		let checker = $(this).find("td:eq(11) input[type='hidden']").val().split("||");
				// 		const [karyawan_id, karyawan_nama, principle] = checker;
				// 		karyawanTemp.push({
				// 			karyawan_id,
				// 			karyawan_nama,
				// 			principle
				// 		});
				// 	})
				// 	// karyawan = $("#slccheckerpb").val();
				// }

				// let karyawan = karyawanTemp.reduce((unique, o) => {
				// 	if (!unique.some(obj => obj.karyawan_id === o.karyawan_id && obj.principle === o.principle)) {
				// 		unique.push(o);
				// 	}
				// 	return unique;
				// }, []);

				if (no_pb !== "") {

					messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Iya, Simpan', 'Tidak, Tutup').then((result) => {
						if (result.value == true) {
							requestAjax("<?= base_url('WMS/Distribusi/PengeluaranBarang/SaveAddNewPickingOrder'); ?>", {
								picking_list_id: picking_list_id,
								depo_id: depo_id,
								// depo_detail_id: depo_detail_id,
								picking_order_tanggal: picking_order_tanggal,
								picking_order_kode: picking_order_kode,
								picking_order_keterangan: picking_order_keterangan,
								picking_order_type: picking_order_type,
								tipe_do: tipe_do,
								picking_order_status: picking_order_status,
								karyawan: karyawan,
								no_pb: no_pb,
								bulk: $("#tabledetailpbbulk > tbody tr").length,
								standar: $("#tabledetailpbstandar > tbody tr").length,
								kirimUlang: $("#tabledetailpbkirimulang > tbody tr").length,
								canvas: $("#tabledetailpbcanvas > tbody tr").length,
								// lastUpdated
							}, "POST", "JSON", function(response) {
								if (response.status === 200) {
									if (response.generateKode.includes("PPB") == true) {
										message_topright("success", response.message);
										location.href = "<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/DetailPengeluaranBarangMenu/?picking_order_kode=" + response.generateKode;
										ResetForm();
									}
								}

								if (response.status === 401) return message_topright("error", response.message);
								if (response.status === 402) return message("Error!", response.message, 'error');
								// if (response.status === 400) return messageNotSameLastUpdated()
							})
						}
					});

					// $.ajax({
					// 	type: 'POST',
					// 	url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/SaveAddNewPickingOrder') ?>",
					// 	data: {
					// 		picking_list_id: picking_list_id,
					// 		depo_id: depo_id,
					// 		// depo_detail_id: depo_detail_id,
					// 		picking_order_tanggal: picking_order_tanggal,
					// 		picking_order_kode: picking_order_kode,
					// 		picking_order_keterangan: picking_order_keterangan,
					// 		picking_order_type: picking_order_type,
					// 		tipe_do: tipe_do,
					// 		picking_order_status: picking_order_status,
					// 		karyawan: karyawan,
					// 		no_pb: no_pb,
					// 		bulk: $("#tabledetailpbbulk > tbody tr").length,
					// 		standar: $("#tabledetailpbstandar > tbody tr").length,
					// 		kirimUlang: $("#tabledetailpbkirimulang > tbody tr").length,
					// 	},
					// 	success: function(response) {
					// 		$("#loadingadd").hide();
					// 		$("#btnsavebkb").prop("disabled", false);

					// 		console.log(response);
					// 		// if(response == 1)
					// 		if (response.includes("PPB") == true) {
					// 			var msg = 'Data berhasil ditambah';
					// 			var msgtype = 'success';

					// 			Swal.fire({
					// 				position: 'center',
					// 				icon: msgtype,
					// 				title: msg,
					// 				showConfirmButton: false,
					// 				timer: 1000
					// 			});

					// 			// location.href = "<?php echo base_url(); ?>Distribusi/PengeluaranBarang/DetailPengeluaranBarangMenu/?picking_order_kode=" + picking_order_kode;
					// 			location.href = "<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/DetailPengeluaranBarangMenu/?picking_order_kode=" + response;
					// 			ResetForm();
					// 		} else {
					// 			if (response == 2) {
					// 				var msg = '<span>Kode Channel sudah ada</span>';
					// 			} else if (response == 3) {
					// 				var msg = '<span>PPB sudah ada</span>';
					// 			} else if (response == 4) {
					// 				var msg = '<span>PB sudah ada PPB</span>';
					// 			} else {
					// 				var msg = response;
					// 			}


					// 			message("Error!", msg, "error");
					// 		}
					// 	},
					// 	error: function(xhr, ajaxOptions, thrownError) {

					// 		$("#loadingadd").hide();
					// 		$("#btnsavebkb").prop("disabled", false);
					// 	}
					// });

				} else {
					message("Error!", "<span>No PB Kosong</span>", "error");
					return false;
				}
			}
		);

		$("#btnnolaksanakanbkb").click(
			function() {
				$("#previewkonfirmasibkb").modal('hide');
			}

		);
	<?php
	}
	?>

	function ResetForm() {
		<?php
		if ($Menu_Access["U"] == 1) {
		?>
			$("#txtpickinglistid").val('');
			$("#txtnoppb").val('');
			$("#txtnofdjr").val('');
			$("#txtdepoid").val('');

			$("#slcgudang").prop('selectedIndex', 0);
			$("#slcnopb").prop('selectedIndex', 0);
			$("#slctipepb").prop('selectedIndex', 0);
			$("#slcstatusppb").html('');

			document.getElementById("chkpb").checked = false;

			$("#loadingview").hide();
			$("#loadingadd").hide();

			$("#btnkonfirmasibarang").prop("disabled", false);
			$("#btnsavebkb").prop("disabled", false);
			$("#btnbatalbkb").prop("disabled", false);

			$("#panelbkbbulk").hide();
			$("#panelbkbstandar").hide();

			$("#btnkonfirmasibarang").prop("disabled", true);

		<?php
		}
		?>

		<?php
		if ($Menu_Access["C"] == 1) {
		?>
			$("#txtpickinglistid").val('');
			$("#txtnoppb").val('');
			$("#txtnofdjr").val('');
			$("#txtdepoid").val('');

			$("#slcgudang").prop('selectedIndex', 0);
			$("#slcnopb").prop('selectedIndex', 0);
			$("#slctipepb").prop('selectedIndex', 0);
			$("#slcstatusppb").html('');

			document.getElementById("chkpb").checked = false;

			$("#loadingview").hide();
			$("#loadingadd").hide();

			$("#btnkonfirmasibarang").prop("disabled", false);
			$("#btnsavebkb").prop("disabled", false);
			$("#btnbatalbkb").prop("disabled", false);

			$("#panelbkbbulk").hide();
			$("#panelbkbstandar").hide();

			$("#btnkonfirmasibarang").prop("disabled", true);

		<?php
		}
		?>

	}
</script>