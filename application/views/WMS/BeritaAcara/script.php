<script type="text/javascript">
	loadingBeforeReadyPage()
	$(document).ready(function() {
		select2();

		if ($('#filter-sj-date').length > 0) {
			$('#filter-sj-date').daterangepicker({
				'applyClass': 'btn-sm btn-success',
				'cancelClass': 'btn-sm btn-default',
				locale: {
					"format": "DD/MM/YYYY",
					applyLabel: 'Apply',
					cancelLabel: 'Cancel',
				},
				'startDate': '<?= ("01-m-Y") ?>',
				'endDate': '<?= date("t-m-Y") ?>'
			});
		}
	})

	function GetDataFilter() {
		var principle = $('#principle').val();
		var tgl = $('#filter-sj-date').val();
		var no = 1;

		if (principle == "") {
			message("Error!", "Pilih Principle!", "error");
			return false;
		}

		$('#loadingaction').show();

		$.ajax({
			type: "POST",
			url: "<?= base_url("WMS/BeritaAcara/GetDataFilter") ?>",
			data: {
				tgl: tgl,
				principle: principle
			},
			dataType: 'JSON',
			success: function(response) {
				$("#table_data_asn > tbody").empty();

				if ($.fn.DataTable.isDataTable('#table_data_asn')) {
					$('#table_data_asn').DataTable().clear();
					$('#table_data_asn').DataTable().destroy();
				}

				if (response != 0) {
					$.each(response, function(i, v) {
						var button = '';
						var tglUpdate = v.penerimaan_surat_jalan_tgl_update == null ? '' : v.penerimaan_surat_jalan_tgl_update;

						if (v.penerimaan_surat_jalan_status == 'Completed' || v.penerimaan_surat_jalan_status == 'Complete' || v.penerimaan_surat_jalan_status == 'Closed' || v.penerimaan_surat_jalan_status == 'Close') {
							if (v.principle_use_asn == 1) {
								if (v.is_status == 0 || v.is_status == null) {
									button = '<button class="btn btn-warning" title="Periksa" onclick="btn_periksa(\'' + v.penerimaan_surat_jalan_id + '\', \'' + v.penerimaan_surat_jalan_no_sj + '\', \'' + tglUpdate + '\')" data-penerimaan-surat-jalan-id="' + v.penerimaan_surat_jalan_id + '" name="btn_periksa" id="btn_periksa"><i class="fa fa-search"></i></button>'
									button += '<button style="display: none;" class="btn btn-success" title="Export Berita Acara" onclick="btn_export_berita_acara(\'' + v.penerimaan_surat_jalan_id + '\', \'' + v.penerimaan_surat_jalan_no_sj + '\')" data-penerimaan-surat-jalan-id="' + v.penerimaan_surat_jalan_id + '" name="btn_export_berita_acara" id="btn_export_berita_acara"><i class="fa-solid fa-file-export"></i></button>'
									button += '<button  style="display: none;" name="btn_export_bosnet_pod" id="btn_export_bosnet_pod" type="button" onclick="btn_export(\'' + v.penerimaan_surat_jalan_id + '\')" data-penerimaan-surat-jalan-id="' + v.penerimaan_surat_jalan_id + '" title="View Export Bosnet dan POD" class="btn btn-primary"><i class="fa-solid fa-eye"></i></button>'
								} else if (v.is_status == 1) {
									button = '<button class="btn btn-success" title="Export Berita Acara" onclick="btn_export_berita_acara(\'' + v.penerimaan_surat_jalan_id + '\', \'' + v.penerimaan_surat_jalan_no_sj + '\')" data-penerimaan-surat-jalan-id="' + v.penerimaan_surat_jalan_id + '" name="btn_export_berita_acara" id="btn_export_berita_acara"><i class="fa-solid fa-file-export"></i></button>'
									button += '<button type="button" id="export_bosnet_pod" onclick="btn_export(\'' + v.penerimaan_surat_jalan_id + '\')" title="View Export Bosnet dan POD" class="btn btn-primary"><i class="fa-solid fa-eye"></i></button>'
								}
							} else {
								if (v.is_status == 0 || v.is_status == null) {
									button = '<button class="btn btn-warning" title="Periksa" onclick="btn_periksa(\'' + v.penerimaan_surat_jalan_id + '\', \'' + v.penerimaan_surat_jalan_no_sj + '\', \'' + tglUpdate + '\')" data-penerimaan-surat-jalan-id="' + v.penerimaan_surat_jalan_id + '" name="btn_periksa" id="btn_periksa"><i class="fa fa-search"></i></button>'
									button += '<button style="display: none;" class="btn btn-success" title="Export Berita Acara" onclick="btn_export_berita_acara(\'' + v.penerimaan_surat_jalan_id + '\', \'' + v.penerimaan_surat_jalan_no_sj + '\')" data-penerimaan-surat-jalan-id="' + v.penerimaan_surat_jalan_id + '" name="btn_export_berita_acara" id="btn_export_berita_acara"><i class="fa-solid fa-file-export"></i></button>'
									button += '<button  style="display: none;" name="btn_export_bosnet_pod" id="btn_export_bosnet_pod" type="button" onclick="btn_export(\'' + v.penerimaan_surat_jalan_id + '\')" data-penerimaan-surat-jalan-id="' + v.penerimaan_surat_jalan_id + '" title="View Export Bosnet dan POD" class="btn btn-primary"><i class="fa-solid fa-eye"></i></button>'
								} else if (v.is_status == 1) {
									button = '<button class="btn btn-success" title="Export Berita Acara" onclick="btn_export_berita_acara(\'' + v.penerimaan_surat_jalan_id + '\', \'' + v.penerimaan_surat_jalan_no_sj + '\')" data-penerimaan-surat-jalan-id="' + v.penerimaan_surat_jalan_id + '" name="btn_export_berita_acara" id="btn_export_berita_acara"><i class="fa-solid fa-file-export"></i></button>'
									button += '<button type="button" id="export_bosnet_pod" onclick="btn_export(\'' + v.penerimaan_surat_jalan_id + '\')" title="View Export Bosnet dan POD" class="btn btn-primary"><i class="fa-solid fa-eye"></i></button>'
								}
							}
						} else {
							button = '<button class="btn btn-warning" disabled title="Periksa" onclick="btn_periksa(\'' + v.penerimaan_surat_jalan_id + '\', \'' + v.penerimaan_surat_jalan_no_sj + '\', \'' + tglUpdate + '\')" data-penerimaan-surat-jalan-id="' + v.penerimaan_surat_jalan_id + '" name="btn_periksa" id="btn_periksa"><i class="fa fa-search"></i></button>'
						}

						$("#table_data_asn > tbody").append(`
					        <tr>
                                <td width="5%" class="text-center">${no++}</td>
                                <td width="20%" class="text-center" >${v.tgl}</td>
                                <td width="20%" class="text-center" >${v.penerimaan_surat_jalan_kode}</td>
                                <td width="20%" class="text-center" >${v.penerimaan_surat_jalan_no_sj}</td>
                                <td width="20%" class="text-center" >${v.penerimaan_surat_jalan_status}</td>
                                <td width="15%" class="text-center" >
                                ${button}
                                </td>
					        </tr>
					    `);
					});

					$('#table_data_asn').DataTable({
						'ordering': false
					});

				} else {
					$("#table_data_asn > tbody").append(`
                        <tr>
                            <td colspan="7" class="text-danger text-center">Data Kosong</td>
                        </tr>
                    `);
				}

				$('#loadingaction').hide();
			}
		})
	};

	function btn_cek_selisih_sku(penerimaan_surat_jalan_id, penerimaan_surat_jalan_no_sj) {
		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/BeritaAcara/GetDataSelisihSKUDetailOri') ?>",
			data: {
				penerimaan_surat_jalan_id: penerimaan_surat_jalan_id
			},
			dataType: "JSON",
			success: function(response) {
				if (response.length > 0) {

					$('#modal-listskudetailori').modal('show');

					if ($.fn.DataTable.isDataTable('#table-listsku')) {
						$('#table-listsku').DataTable().clear();
						$('#table-listsku').DataTable().destroy();
					}

					$('#table-listsku > tbody').empty();

					$.each(response, function(i, v) {
						$('#table-listsku > tbody').append(`
                                <tr>
                                <td class="text-center">${penerimaan_surat_jalan_no_sj}</td>
                                <td class="text-center">${v.sku_konversi_group}</td>
                                <td class="text-center">${v.sku_nama_produk}</td>
                                <td class="text-center">${v.sku_jumlah_barang} <input type="hidden" onkeypress="return hanyaAngka(event)" type="text" id="jumlah_barang" class="form-control" size="5" name="jumlah_barang" value="${v.sku_jumlah_barang}" /></td>
                                <td class="text-center">${v.sku_jumlah_barang_terima} <input type="hidden" onkeypress="return hanyaAngka(event)" type="text" id="terima" class="form-control" size="5" name="terima" value="${v.sku_jumlah_barang_terima}" /></td>
                                <td class="text-center">
                                    <input onkeypress="return hanyaAngka(event)" type="text" id="rusak" class="form-control" size="5" name="rusak" value="" placeholder="0" />
                                </td>
                                <td class="text-center">
                                    <input onkeypress="return hanyaAngka(event)" type="text" id="barang_tertinggal" class="form-control" size="5" name="barang_tertinggal" value="" placeholder="0" />
                                </td>
                                <td class="text-center">
                                    <input onkeypress="return hanyaAngka(event)" type="text" id="kardus_rusak" class="form-control" size="5" name="kardus_rusak" value="" placeholder="0" />
                                </td>
                                <td class="text-center">
                                    <input type="text" id="total" class="form-control" size="5" name="total" value="${v.sku_jumlah_barang_terima}" readonly />
                                </td>
                                <td class="text-center">
                                    <input type="text" id="sumz" class="form-control" size="5" name="sumz" value="0" readonly />
                                </td>
                                </tr>
                        `)
					})

					$('#table-listsku').DataTable({
						lengthMenu: [
							[-1],
							["All"]
						]
					});

					$('#terima, #rusak, #barang_tertinggal, #kardus_rusak').keyup(function() {
						// var sum = 0;
						$('#table-listsku > tbody > tr').each(function() {
							var terima = parseInt($(this).find('#terima').val());
							var rusak = parseInt($(this).find('#rusak').val());
							var barang_tertinggal = parseInt($(this).find('#barang_tertinggal').val());
							var kardus_rusak = parseInt($(this).find('#kardus_rusak').val());

							terima = isNaN(terima) ? 0 : terima;
							rusak = isNaN(rusak) ? 0 : rusak;
							barang_tertinggal = isNaN(barang_tertinggal) ? 0 : barang_tertinggal;
							kardus_rusak = isNaN(kardus_rusak) ? 0 : kardus_rusak;

							$(this).find('#total').val(terima + rusak + barang_tertinggal);
							$(this).find('#sumz').val(rusak + barang_tertinggal + kardus_rusak);

						})
					});

					$('#btn_simpan_list_sku').attr('data-penerimaan-surat-jalan-id', penerimaan_surat_jalan_id);
				} else {
					window.open('<?= base_url('WMS/BeritaAcara/ExportPDF/') ?>' + penerimaan_surat_jalan_id);
				}
			}
		})
	}

	function btn_periksa(penerimaan_surat_jalan_id, penerimaan_surat_jalan_no_sj, tglUpdate) {

		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/BeritaAcara/GetDataSelisihSKUDetailTemp2') ?>",
			data: {
				penerimaan_surat_jalan_id: penerimaan_surat_jalan_id
			},
			dataType: "JSON",
			success: function(response) {
				if (response.length > 0) {

					$('#modal-listskudetailori').modal('show');

					if ($.fn.DataTable.isDataTable('#table-listsku')) {
						$('#table-listsku').DataTable().clear();
						$('#table-listsku').DataTable().destroy();
					}

					$('#table-listsku > tbody').empty();

					$.each(response, function(i, v) {
						$('#table-listsku > tbody').append(`
                                <tr>
                                <td class="text-center">${penerimaan_surat_jalan_no_sj}</td>
                                <td class="text-center">${v.sku_kode}</td>
                                <td class="text-center">${v.sku_nama_produk}</td>
                                <td class="text-center">${v.sku_jumlah_barang} <input type="hidden" onkeypress="return hanyaAngka(event)" type="text" id="jumlah_barang" class="form-control" size="5" name="jumlah_barang" value="${v.sku_jumlah_barang}" /></td>
                                <td class="text-center">${v.terima} <input type="hidden" onkeypress="return hanyaAngka(event)" type="text" id="terima" class="form-control" size="5" name="terima" value="${v.terima}" /></td>
                                <td class="text-center">
                                    <input onkeypress="return hanyaAngka(event)" type="text" id="rusak" class="form-control" size="5" name="rusak" value="${v.rusak}" placeholder="0" />
                                </td>
                                <td class="text-center">
                                    <input onkeypress="return hanyaAngka(event)" type="text" id="barang_tertinggal" class="form-control" size="5" name="barang_tertinggal" value="${v.barang_tertinggal}" placeholder="0" />
                                </td>
                                <td class="text-center">
                                    <input onkeypress="return hanyaAngka(event)" type="text" id="kardus_rusak" class="form-control" size="5" name="kardus_rusak" value="${v.kardus_rusak}" placeholder="0" />
                                </td>
                                <td class="text-center">
                                    <input type="text" id="total" class="form-control" size="5" name="total" value="${v.total}" readonly />
                                </td>
                                <td class="text-center">
                                    <input type="text" id="sumz" class="form-control" size="5" name="sumz" value="${v.sumz}" readonly />
                                </td>
                                </tr>
                        `)
					})

					$('#table-listsku').DataTable({
						lengthMenu: [
							[-1],
							["All"]
						]
					});

					$('#terima, #rusak, #barang_tertinggal, #kardus_rusak').keyup(function() {
						// var sum = 0;
						$('#table-listsku > tbody > tr').each(function() {
							var terima = parseInt($(this).find('#terima').val());
							var rusak = parseInt($(this).find('#rusak').val());
							var barang_tertinggal = parseInt($(this).find('#barang_tertinggal').val());
							var kardus_rusak = parseInt($(this).find('#kardus_rusak').val());

							terima = isNaN(terima) ? 0 : terima;
							rusak = isNaN(rusak) ? 0 : rusak;
							barang_tertinggal = isNaN(barang_tertinggal) ? 0 : barang_tertinggal;
							kardus_rusak = isNaN(kardus_rusak) ? 0 : kardus_rusak;

							$(this).find('#total').val(terima + rusak + barang_tertinggal);
							$(this).find('#sumz').val(rusak + barang_tertinggal + kardus_rusak);

						})
					});

					$('#btn_simpan_list_sku').attr('data-penerimaan-surat-jalan-id', penerimaan_surat_jalan_id);
					$('#btn_simpan_list_sku').attr('data-tgl-update', tglUpdate == 'null' ? '' : tglUpdate);
				} else {
					$("button[data-penerimaan-surat-jalan-id='" + penerimaan_surat_jalan_id + "'][name='btn_periksa']").css('display', 'none');
					$("button[data-penerimaan-surat-jalan-id='" + penerimaan_surat_jalan_id + "'][name='btn_export_berita_acara']").show();
					$("button[data-penerimaan-surat-jalan-id='" + penerimaan_surat_jalan_id + "'][name='btn_export_bosnet_pod']").show();
				}
			}
		})
	}

	function simpansku() {
		var penerimaan_surat_jalan_id = $('#btn_simpan_list_sku').attr('data-penerimaan-surat-jalan-id');
		var tglUpdate = $('#btn_simpan_list_sku').attr('data-tgl-update');
		var boolean = true;
		var arr_cek_alert = [];
		var arr_sku = [];
		$('#table-listsku > tbody > tr').each(function() {
			var sku_kode = $(this).find('td:eq(1)').text();
			var terima = $(this).find('#terima').val();
			var jumlah_barang = $(this).find('#jumlah_barang').val();
			var rusak = $(this).find('#rusak').val();
			var barang_tertinggal = $(this).find('#barang_tertinggal').val();
			var kardus_rusak = $(this).find('#kardus_rusak').val();
			var total = $(this).find('#total').val();
			var sumz = $(this).find('#sumz').val();

			if (jumlah_barang != total) {
				arr_cek_alert.push(sku_kode);
				boolean = false;
			} else {
				if (rusak == '') {
					rusak = '0';
				}
				if (barang_tertinggal == '') {
					barang_tertinggal = '0';
				}
				if (kardus_rusak == '') {
					kardus_rusak = '0';
				}
			}

			arr_sku.push({
				penerimaan_surat_jalan_id: penerimaan_surat_jalan_id,
				sku_kode: sku_kode,
				terima: terima,
				rusak: rusak,
				barang_tertinggal: barang_tertinggal,
				kardus_rusak: kardus_rusak,
				total: total,
				sumz: sumz
			});
		});

		if (boolean == true) {
			requestAjax("<?= base_url('WMS/BeritaAcara/UpdatePSJDetailTemp2') ?>", {
				sku: arr_sku,
				penerimaan_surat_jalan_id: penerimaan_surat_jalan_id,
				tglUpdate: tglUpdate
			}, "POST", "JSON", function(response) {
				if (response == 400) {
					return messageNotSameLastUpdated();
				}

				if (response.status == 200) {
					$('#modal-listskudetailori').modal('hide');
					Swal.fire({
						title: "Success!",
						text: response.message,
						icon: "success",
						showConfirmButton: false,
						timer: 1000
					}).then(() => {
						GetDataFilter();
					})
				} else {
					message('Error!', response.message, 'error')
				}
			})

		} else {
			var implode_sku = arr_cek_alert.join(', ');
			message('Gagal!', 'Total terima tidak sama dengan total untuk SKU \n <b>' + implode_sku + '</b>', 'error')
		}
	}

	function btn_export_berita_acara(penerimaan_surat_jalan_id, penerimaan_surat_jalan_no_sj) {
		window.open('<?= base_url('WMS/BeritaAcara/ExportPDF/') ?>' + penerimaan_surat_jalan_id);
	}

	function btn_export(penerimaan_surat_jalan_id) {
		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/BeritaAcara/GetDOPSJDetailTemp2') ?>",
			data: {
				penerimaan_surat_jalan_id: penerimaan_surat_jalan_id
			},
			dataType: "JSON",
			success: function(response) {
				if (response != 0) {
					$('#modal-listdoasn').modal('show');

					if ($.fn.DataTable.isDataTable('#table-listdoasn')) {
						$('#table-listdoasn').DataTable().clear();
						$('#table-listdoasn').DataTable().destroy();
					}

					$('#table-listdoasn > tbody').empty();

					$.each(response, function(i, v) {
						var button = '';

						if (v.is_download == 0) {
							button += '<a class="btn btn-primary" onclick="viewExportBosnet(this)" href="<?= base_url('WMS/BeritaAcara/ExportBosnet?id=') ?>' + v.penerimaan_surat_jalan_id + '&do=' + v.penerimaan_surat_jalan_no_sj + '">Export Bosnet</a>'
							button += '<a class="btn btn-primary" onclick="viewExportPOD(this)" href="<?= base_url('WMS/BeritaAcara/ExportPOD?id=') ?>' + v.penerimaan_surat_jalan_id + '&do=' + v.penerimaan_surat_jalan_no_sj + '">Export POD</a>'
						} else if (v.is_download == 1) {
							button += '<a class="btn btn-primary" onclick="viewExportBosnet(this)" href="<?= base_url('WMS/BeritaAcara/ExportBosnet?id=') ?>' + v.penerimaan_surat_jalan_id + '&do=' + v.penerimaan_surat_jalan_no_sj + '">Bosnet Sudah Terdownload</a>'
							button += '<a class="btn btn-primary" onclick="viewExportPOD(this)" href="<?= base_url('WMS/BeritaAcara/ExportPOD?id=') ?>' + v.penerimaan_surat_jalan_id + '&do=' + v.penerimaan_surat_jalan_no_sj + '">Export POD</a>'
						} else if (v.is_download == 2) {
							button += '<a class="btn btn-primary" onclick="viewExportBosnet(this)" href="<?= base_url('WMS/BeritaAcara/ExportBosnet?id=') ?>' + v.penerimaan_surat_jalan_id + '&do=' + v.penerimaan_surat_jalan_no_sj + '">Export Bosnet</a>'
							button += '<a class="btn btn-primary" onclick="viewExportPOD(this)" href="<?= base_url('WMS/BeritaAcara/ExportPOD?id=') ?>' + v.penerimaan_surat_jalan_id + '&do=' + v.penerimaan_surat_jalan_no_sj + '">POD Sudah Terdownload</a>'
						} else if (v.is_download == 3) {
							button += '<a class="btn btn-primary" onclick="viewExportBosnet(this)" href="<?= base_url('WMS/BeritaAcara/ExportBosnet?id=') ?>' + v.penerimaan_surat_jalan_id + '&do=' + v.penerimaan_surat_jalan_no_sj + '">Bosnet Sudah Terdownload</a>'
							button += '<a class="btn btn-primary" onclick="viewExportPOD(this)" href="<?= base_url('WMS/BeritaAcara/ExportPOD?id=') ?>' + v.penerimaan_surat_jalan_id + '&do=' + v.penerimaan_surat_jalan_no_sj + '">POD Sudah Terdownload</a>'
						}

						$('#table-listdoasn > tbody').append(`
                        <tr>
                            <td class="text-center">
                                ${v.shipment_number}
                            </td>
                            <td class="text-center">
                                ${v.penerimaan_surat_jalan_no_sj}
                            </td>
                            <td class="text-center">
                                ${button}
                            </td>
                        </tr>
                        `)
					});

					$('#table-listdoasn').DataTable({
						lengthMenu: [
							[-1],
							["All"]
						]
					})
				}
			}
		})
	}

	function viewExportBosnet(e) {
		$(e).html('Bosnet Sudah Terdownload');
	}

	function viewExportPOD(e) {
		$(e).html('POD Sudah Terdownload');
	}

	function clickListDOByShipment(shipment_number, principle_id) {
		$.ajax({
			type: "POST",
			url: "<?= base_url("WMS/BeritaAcara/GetListDOByShipment") ?>",
			data: {
				shipment_number: shipment_number,
				principle_id: principle_id
			},
			dataType: "JSON",
			success: function(response) {
				$('#modal-listdoasn').modal('show');

				if ($.fn.DataTable.isDataTable('#table-listdoasn')) {
					$('#table-listdoasn').DataTable().clear();
					$('#table-listdoasn').DataTable().destroy();
				}

				$('#table-listdoasn > tbody').empty();

				if (response.length > 0) {
					$.each(response, function(i, v) {
						$('#table-listdoasn > tbody').append(`
                    <tr>
                    <td class="text-center">${v.shipment_number}</td>
                    <td class="text-center">${v.penerimaan_surat_jalan_no_sj}</td>
                    <td class="text-center">${v.sku_kode}</td>
                    <td class="text-center">${v.sku_jumlah_barang}</td>
                    </tr>
                    `)
					})

					$('#table-listdoasn').DataTable({
						lengthMenu: [
							[50, 100, -1],
							[50, 100, 'All']
						]
					});

				} else {
					$('#table-listdoasn > tbody').html(`
                    <tr>
                    <td colspan="2" class="text-center text-danger">Data Kosong</td>
                    </tr>
                    `)
				}
			}
		})
	}

	function select2() {
		$(".select2").select2({
			width: "100%"
		});
	}

	// function message(msg, msgtext, msgtype) {
	//     Swal.fire(msg, msgtext, msgtype);
	// }

	function hanyaAngka(event) {
		var angka = (event.which) ? event.which : event.keyCode
		if (angka != 46 && angka > 31 && (angka < 48 || angka > 57))
			return false;
		return true;
	}
</script>