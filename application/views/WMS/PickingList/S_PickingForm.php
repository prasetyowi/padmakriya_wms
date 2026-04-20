<script type="text/javascript">
	var dataDoD = [];
	var draftSkuED = [];
	var dataSkuED = [];
	var countDO = 0;
	loadingBeforeReadyPage()
	$(document).ready(function() {
		// alert('aa')
		$('.select2').select2({
			width: '100%'
		});
		// $('#tableSKU').DataTable();

	});



	$('#saveData').click(function(e) {
		// data header
		var area_id = $("#area").val()
		var tipe_delivery_order_id = $("#tipe_delivery_order_id").val();
		var delivery_order_batch_id = $("#no_batch_do").val();
		var picking_list_tgl_kirim = $("#tgl_kirim").val();
		var picking_list_keterangan = $("#keterangan").val();
		var picking_list_status = $("#status").val();
		var depo_detail_id = $("#depo_detail").val();
		var depo_id = $("#depo").val();

		// data detail
		let do_id = $("input[name='do_id[]']").map(function() {
			return this.value;
		}).get();
		let prioritas_stock = $("input[name='prioritas_stock[]']").map(function() {
			return this.value;
		}).get();

		let chkDO = $("input[name='chkDO[]']").map(function() {
			return this.value;
		}).get();
		let chkDetail = $("input[name='chkDetail[]']").map(function() {
			if (this.value == 1) {
				return this.value;
			}
		}).get();

		if (chkDetail.length < do_id.length) {
			Swal.fire(
				'Warming !',
				'Mohon periksa terlebih dahulu Detail Expired Date dari DO !! ',
				'warning'
			)
			return false;

		}
		// return false;
		// console.log(countDO);

		// data detail 2
		// dataDoD
		// dataSkuED

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/Distribusi/PermintaanBarang/SavePickingList') ?>",
			data: {
				area_id: area_id,
				depo_id: depo_id,
				tipe_delivery_order_id: tipe_delivery_order_id,
				delivery_order_batch_id: delivery_order_batch_id,
				picking_list_tgl_kirim: picking_list_tgl_kirim,
				picking_list_keterangan: picking_list_keterangan,
				picking_list_status: picking_list_status,
				depo_detail_id: depo_detail_id,

				do_id: do_id,
				prioritas_stock: prioritas_stock,
				chkDO: chkDO,

				dataDoD: dataDoD,
				dataSkuED: dataSkuED,
			},
			async: "true",
			beforeSend: function() {
				$("#saveData").prop('disabled', true);
			},
			dataType: "json",
			success: function(response) {
				console.log(response);
				if (response.status == 1) {
					Swal.fire(
						'Success!',
						'Your file has been created.',
						'success'
					)
					setTimeout(function() {
						window.location.href = "<?= base_url('WMS/Distribusi/PermintaanBarang/PermintaanBarangMenu') ?>";
					}, 3000);
				} else {
					Swal.fire(
						'Error!',
						response.message,
						'warning'
					)
					$('#saveData').prop('disabled', false);
				}
				// let data = response;
				// console.log(data);
			}
		});

	});
	//update depo di modal seach sku

	$('#depo_detail').change(function() {
		var selected = $(this).find('option:selected');
		var nama_depo_detail = selected.data('nama');
		var id = selected.val();
		$('#depo_detail_src').append($("<option />").val(id).text(nama_depo_detail));
	});


	function GetDoBatch() {
		var tipe_radio = $("#tipe_picklist");
		var tipe = $("#tipe_picklist").val();
		var area = $("#area").val();
		var doBatch = $("#no_batch_do");

		$('#no_batch_do option').remove()
		if (area == '') {
			doBatch.append($("<option />").val(null).text("-- PILIH AREA DAHULU --"));
			doBatch.prop('disabled', true);

		} else {
			doBatch.append($("<option />").val(null).text("-- PILIH DO BATCH --"));
			doBatch.prop('disabled', false);
			$.ajax({
				type: 'GET',
				url: "<?= base_url('WMS/Distribusi/PermintaanBarang/GetDoBatchByArea') ?>",
				data: {
					area_id: area,
					tipe: tipe
				},
				success: function(response) {

					let data = response;
					console.log(data);

					$.each(data, function() {
						doBatch.append($("<option />").val(this.delivery_order_batch_id).text(this.delivery_order_batch_kode));
					});

				}
			});
		}
	}

	function GetDoBatchById() {
		var DoBatchId = $("#no_batch_do").val();
		if (DoBatchId == '') {

		} else {
			$.ajax({
				type: 'GET',
				url: "<?= base_url('WMS/Distribusi/PermintaanBarang/GetDoBatchById') ?>",
				data: {
					DoBatchId: DoBatchId
				},
				success: function(response) {

					let data = response;

					// if (condition) {
					$('#tgl_fdjr').val(data.tgl_doBatch)
					$('#tgl_kirim').val(data.tgl_kirim)
					if (data.delivery_order_batch_is_need_packing == 0) {

						$('#is_packing').val('No')
					} else {
						$('#is_packing').val('Yes')
					}
					$('#status').val(data.delivery_order_batch_Status)

					$('#tipe_layanan').append($("<option />").val(data.delivery_order_batch_tipe_layanan_no).text(data.delivery_order_batch_tipe_layanan_nama));
					$('#area').append($("<option />").val(data.area_id).text(data.area_nama));
					$('#depo_detail').append($("<option />").val(data.depo_detail_id).text(data.depo_detail_nama));
					$('#depo_detail_src').append($("<option />").val(data.depo_detail_id).text(data.depo_detail_nama));
					$('#tipe_picklist1').val(data.tipe_delivery_order_alias)
					$('#tipe_delivery_order_id').val(data.tipe_delivery_order_id)
					$('#armada').val(data.armada)
					$('#tipe_ekspedisi').val(data.tipe_ekspedisi_nama)
					$('#driver').val(data.karyawan_nama)
					$('#weight_max').val(data.kendaraan_berat_gr_max)
					$('#volume_max').val(data.kendaraan_volume_cm3_max)
					$('#weight_terpakai').val(data.kendaraan_berat_gr_terpakai)
					$('#volume_terpakai').val(data.kendaraan_volume_cm3_terpakai)
					// }
					// $.each(data, function() {
					//     doBatch.append($("<option />").val(this.delivery_order_batch_id).text(this.delivery_order_batch_kode));
					// });

				}
			});
		}
		getDoByBatchId(DoBatchId);
	}

	function getDoByBatchId(DoBatchId) {
		$.ajax({
			type: 'GET',
			url: "<?= base_url('WMS/Distribusi/PermintaanBarang/getDoByBatchId') ?>",
			data: {
				DoBatchId: DoBatchId
			},
			success: function(response) {
				let no = 1;
				let data = response;

				dataDoD = [];

				dataSkuED = [];

				if ($.fn.DataTable.isDataTable('#tableDO')) {
					$('#tableDO').DataTable().destroy();
				}

				$("#tableDO tbody").empty();
				countDO = data.length;
				// $('#tableDO').DataTable().clear();
				// <input class="form-control" type="checkbox" name="chkDO[]" value="0" >
				if (data.length > 0) {

					$.each(data, function(index, value) {
						if (value.req_ed == '1') {
							var checkbox_ed = '<input class="form-control" type="checkbox" name="chkDetail[]" id="chkDetail-' + index + '" value="0" disabled>'
						} else {
							var checkbox_ed = '<input class="form-control" type="checkbox" name="chkDetail[]" id="chkDetail-' + index + '" value="1" disabled checked>'
							getDataDetailByDoId(value.delivery_order_id, value.delivery_order_kode);
						}
						$('#tableDO tbody').append(`
                                <tr>
                                    <td style='vertical-align:middle; ' >${no}</td>
                                    <td style='vertical-align:middle; ' >${value.delivery_order_tgl_buat_do}<input type="hidden" class="form-control" name="do_id[]" value="${value.delivery_order_id}"></td>
                                    <td style='vertical-align:middle; ' >${value.delivery_order_kode}</td>
                                    <td style='vertical-align:middle; ' >${value.tipe_delivery_order_alias}</td>
                                    <td style='vertical-align:middle; ' >${value.customer_nama}</td>
                                    <td style='vertical-align:middle; ' >${value.customer_alamat}</td>
                                    <td style='vertical-align:middle; ' >${value.customer_telepon}</td>
                                    <td style='vertical-align:middle; ' >${value.delivery_order_tipe_pembayaran}</td>
                                    <td style='vertical-align:middle; ' >${value.delivery_order_tipe_layanan}</td>
                                    <td style='vertical-align:middle;width:30%; ' >${value.delivery_order_no_urut_rute}</td>
                                    <td style='vertical-align:middle; ' ><input class="form-control" type="number" name="prioritas_stock[]"></td>
                                    <td style='vertical-align:middle; ' >${value.delivery_order_status}</td>
                                    <td style='vertical-align:middle; ' ><button class="btn btn-info" onclick="viewDetail('${value.delivery_order_id}','${value.delivery_order_kode}','${index}')">Detail</button></td>
                                    <td style='vertical-align:middle; ' >${checkbox_ed}</td>
                                </tr>
                            `);

						// tableDO.row.add(tr[0]).draw()
						no++;
						// console.log(index);
					});
				} else {
					$("#tableDO tbody").html('');
				}


				$('#tableDO').DataTable({

				});
			}
		});

	}

	function getDataDetailByDoId(DoID, DoKode) {
		$.ajax({
			type: 'GET',
			url: "<?= base_url('WMS/Distribusi/PermintaanBarang/getDoDById') ?>",
			data: {
				DoID: DoID
			},
			success: function(response) {

				let data = response;

				$.each(data, function() {
					dataDoD.push({
						'do_id': DoID,
						'do_kode': DoKode,
						'dod_id': this.delivery_order_detail_id,
						'sku_id': this.sku_id,
						'sku_kode': this.sku_kode,
						'sku_nama_produk': this.sku_nama_produk,
						'sku_qty': this.sku_qty,
						'sku_kode_sku_principle': this.sku_kode_sku_principle,
						'sku_kemasan': this.sku_kemasan,
						'sku_satuan': this.sku_satuan,
						'chkED': this.sku_request_expdate,
						'req_ed': this.sku_request_expdate
					})
				});
				// setTableDoD(dataDoD,DoKode, index);
			}
		});
	}

	function viewDetail(DoID, DoKode, index) {
		// dataDOD = []
		let index_dataD = dataDoD.findIndex(item => item.do_id == DoID);
		// $("#chkDetail-" + index).val(1);
		// $("#chkDetail-" + index).prop('checked', true);
		// console.log(index_dataD);3
		// cek apakah DoDetail by doId sudah ada
		if (index_dataD >= 0) {
			// alert('SKU Ini Sudah Ditambahkan')
			// return false;
			setTableDoD(dataDoD, DoKode, index, DoID);
		} else {
			$.ajax({
				type: 'GET',
				url: "<?= base_url('WMS/Distribusi/PermintaanBarang/getDoDById') ?>",
				data: {
					DoID: DoID
				},
				success: function(response) {

					let data = response;

					$.each(data, function() {
						dataDoD.push({
							'do_id': DoID,
							'do_kode': DoKode,
							'dod_id': this.delivery_order_detail_id,
							'sku_id': this.sku_id,
							'sku_kode': this.sku_kode,
							'sku_nama_produk': this.sku_nama_produk,
							'sku_qty': this.sku_qty,
							'sku_kode_sku_principle': this.sku_kode_sku_principle,
							'sku_kemasan': this.sku_kemasan,
							'sku_satuan': this.sku_satuan,
							'chkED': this.sku_request_expdate,
							'req_ed': this.sku_request_expdate,
							'chk_data': 0,
						})
					});
					console.log(dataDoD);
					setTableDoD(dataDoD, DoKode, index, DoID);
				}
			});
		}

		$('#viewDetail').modal("show");
	}

	function setTableDoD(dataDoD, DoKode, index, DoID) {
		var idx_do = index;
		$("#do_idD").val(DoID);
		$("#tableDOD tbody").html('');
		$.each(dataDoD, function(index) {
			// jika bukan do yg dipilih maka return 
			if (DoKode != this.do_kode) {
				return;
			}
			if (this.chkED == 0) {
				var checkbox_ed = '<input type="checkbox" name="chkED[]" id="chkED-' + index + '" value="' + this.chkED + '" onclick="ckED(' + index + ')">'
			} else if (this.req_ed == 1) {
				var checkbox_ed = '<input type="checkbox" name="chkED[]" id="chkED-' + index + '" value="' + this.chkED + '" checked onclick="ckED(' + index + ')" disabled>'
			} else {
				var checkbox_ed = '<input type="checkbox" name="chkED[]" id="chkED-' + index + '" value="' + this.chkED + '" checked onclick="ckED(' + index + ')" >'
			}
			if (this.chkED != 0) {
				$('#tableDOD tbody').append(`
                                    <tr>
                                        <td style='vertical-align:middle; ' >${this.do_kode}<input type="hidden" class="form-control" name="do_idD[]" value="${this.do_id}"><input type="hidden" class="form-control" name="dod_id[]" value="${this.dod_id}"></td>
                                        <td style='vertical-align:middle; ' >${this.sku_kode}</td>
                                        <td style='vertical-align:middle; ' >${this.sku_kode_sku_principle}</td>
                                        <td style='vertical-align:middle; ' >${this.sku_nama_produk}</td>
                                        <td style='vertical-align:middle; ' >${this.sku_kemasan}</td>
                                        <td style='vertical-align:middle; ' >${this.sku_satuan}</td>
                                        <td style='vertical-align:middle; ' >${checkbox_ed}<input type="hidden" name="chk_data[]" value="${this.chk_data}"></td>
                                        <td style='vertical-align:middle; ' >${this.sku_qty}</td>
                                        <td style='vertical-align:middle; ' ><button class="btn btn-info" onclick="modalSKU('${this.sku_id}','${this.sku_kode}','${this.sku_nama_produk}','${this.dod_id}','${this.sku_qty}','${idx_do}')" id="btnED-${index}" >Pilih Exp Date</button></td>
                                    </tr>
                `);
			} else {
				$('#tableDOD tbody').append(`
                                    <tr>
                                        <td style='vertical-align:middle; ' >${this.do_kode}<input type="hidden" class="form-control" name="do_idD[]" value="${this.do_id}"><input type="hidden" class="form-control" name="dod_id[]" value="${this.dod_id}"></td>
                                        <td style='vertical-align:middle; ' >${this.sku_kode}</td>
                                        <td style='vertical-align:middle; ' >${this.sku_kode_sku_principle}</td>
                                        <td style='vertical-align:middle; ' >${this.sku_nama_produk}</td>
                                        <td style='vertical-align:middle; ' >${this.sku_kemasan}</td>
                                        <td style='vertical-align:middle; ' >${this.sku_satuan}</td>
                                        <td style='vertical-align:middle; ' >${checkbox_ed}<input type="hidden" name="chk_data[]" value="${this.chk_data}"></td>
                                        <td style='vertical-align:middle; ' >${this.sku_qty}</td>
                                        <td style='vertical-align:middle; ' ><button class="btn btn-info" onclick="modalSKU('${this.sku_id}','${this.sku_kode}','${this.sku_nama_produk}','${this.dod_id}','${this.sku_qty}','${idx_do}')" id="btnED-${index}" disabled>Pilih Exp Date</button></td>
                                    </tr>
                `);
			}

		});
	}

	function addDOD() {
		let do_idD = $("#do_idD").val();

		let chkED = 0;;
		let chk_data = 0;
		// let row = dataDoD.find(item => item.do_id == do_id);

		/// filter jika do sama 
		let result = dataDoD.filter(value => value.do_id == do_idD);
		console.log(result);
		$.each(result, function(index) {
			if (parseInt(this.chkED) > 0) {
				chkED++;
			}
			if (parseInt(this.chk_data) > 0) {
				chk_data++;
			}
		});
		console.log(chk_data);
		console.log(chkED);
		// cek apakah yg dicheck sama dgn dt Ed yg sudah ada
		if (chkED != chk_data) {
			Swal.fire(
				'Warning !',
				'Mohon pilih Expired date SKU terlebih dahulu !',
				'warning'
			)
			// alert('Mohon pilih Expired date SKU terlebih dahulu !')
			return false;
		}
		$("#viewDetail").modal('hide');
	}

	$('input[name="chkDO[]"]').change(function() {
		if (this.checked)
			$(this).val(1);
		else
			$(this).val(0);
	});

	function ckED(index) {
		var checkBox = $("#chkED-" + index)
		if (checkBox.prop("checked") == true) {
			dataDoD[index]['chkED'] = 1;

			$("#btnED-" + index).attr('disabled', false);
		} else {
			dataDoD[index]['chkED'] = 0;
			$("#btnED-" + index).attr('disabled', true);
		}
	};

	function modalSKU(sku_id, sku_kode, sku_nama, dod_id, qty_sku, idx_do) {
		$('#kode_sku_src').val(sku_kode)
		$('#sku_id_src').val(sku_id)
		$('#nama_sku_src').val(sku_nama)
		$('#qty_sku_src').val(qty_sku)
		$('#dod_id_src').val(dod_id)
		$('#idx_do').val(idx_do)
		$('#tableSKU tbody').html('')
		$('#modalSKU').modal("show");
		let row = dataSkuED.find(item => item.dod_id == dod_id);
		console.log(dataSkuED);
		// cek apakah ada ed sku yg tersimpan
		let index_data = dataSkuED.findIndex(item => item.dod_id == dod_id);
		console.log(row);
		// jika ada maka set table sesuai data yg sudah di save sebelumnya
		if (index_data >= 0) {
			searchSKU()
		}

	}

	function resetSKU() {
		console.log(dataSkuED);
		var dod_id = $('#dod_id_src').val();
		var count = dataSkuED.length;
		// agar tidak berefek pada index selanjutnya, maka perulangan mundur
		// while (count--) {
		//     if (dataSkuED[count]['dod_id'] == dod_id) {
		//         console.log(count);
		//         // delete dataSkuED[i];
		//         dataSkuED.splice(count, 1);
		//     }
		// }
		for (var i = dataSkuED.length; i--;) {
			if (dataSkuED[i]['dod_id'] == dod_id) {
				console.log(dataSkuED[i]['dod_id']);
				// delete dataSkuED[i];
				dataSkuED.splice(i, 1);
				// i--;
			} else {
				console.log('111');
			}
		}
		// error
		// for (let i = 0; i < array.length; i++) {
		//     console.log(i);
		//     if (dataSkuED[i]['dod_id'] == dod_id) {
		//         console.log(i);
		//         // delete dataSkuED[i];
		//         dataSkuED.splice(i, 1);
		//         i--;
		//     }
		// }

		console.log(dataSkuED);
		$('#tableSKU tbody').html('')
	}

	function searchSKU() {
		var sku_id = $('#sku_id_src').val();
		var depo_id = $('#depo_src').val();
		var depo_detail_id = $('#depo_detail_src').val();
		var dod_id = $('#dod_id_src').val();
		// console.log(dod_id);
		// console.log(gudabg_id);
		$('#tableSKU tbody').html('')
		$.ajax({
			type: 'GET',
			url: "<?= base_url('WMS/Distribusi/PermintaanBarang/getSKUStockBySKUID') ?>",
			data: {
				sku_id: sku_id,
				depo_id: depo_id,
				depo_detail_id: depo_detail_id
			},
			success: function(response) {

				let data = response;

				$.each(data, function(index) {
					let qty_dtl = 0;
					// cek apakah ada ed sku yg tersimpan
					let index_data = dataSkuED.findIndex(item => item.dod_id == dod_id && item.sku_stock_id == this.sku_stock_id);
					// jika ada maka set qty sesuai data yg sudah di save sebelumnya
					if (index_data >= 0) {
						qty_dtl = dataSkuED[index_data]['qty'];
					}
					var stock = this.sku_stock_awal + this.sku_stock_masuk - this.sku_stock_saldo_alokasi - this.sku_stock_keluar;

					// <td style='vertical-align:middle; text-align: center;width:10%' >${this.depo_nama}</td>
					// <td style='vertical-align:middle; text-align: center;width:10%' >${this.depo_detail_nama}</td>
					$('#tableSKU tbody').append(`
                                    <tr>
                                        <td style='vertical-align:middle; text-align: center;width:10%' >${this.sku_induk_nama}<input type="hidden" class="form-control" name="depo_detail_nama[]" value="${this.depo_detail_nama}"><input type="hidden" class="form-control" name="depo_nama[]" value="${this.depo_nama}"><input type="hidden" class="form-control" name="dod_id1[]" value="${dod_id}"><input type="hidden" class="form-control" name="sku_induk_nama[]" value="${this.sku_induk_nama}"><input type="hidden" class="form-control" name="sku_stock_id[]" value="${this.sku_stock_id}"></td>
                                        <td style='vertical-align:middle; text-align: center;width:10%' >${this.sku_nama_produk}<input type="hidden" class="form-control" name="sku_nama_produk[]" value="${this.sku_nama_produk}"></td>
                                        <td style='vertical-align:middle; text-align: center;width:5%'' >${this.sku_kemasan}<input type="hidden" class="form-control" name="sku_kemasan[]" value="${this.sku_kemasan}"></td>
                                        <td style='vertical-align:middle; text-align: center;width:5%'' >${this.sku_satuan}<input type="hidden" class="form-control" name="sku_satuan[]" value="${this.sku_satuan}"></td>
                                        <td style='vertical-align:middle; text-align: center;' >${this.principle_kode}<input type="hidden" class="form-control" name="principle_kode[]" value="${this.principle_kode}"></td>
                                        <td style='vertical-align:middle; text-align: center;' >${this.principle_brand_nama}<input type="hidden" class="form-control" name="principle_brand_nama[]" value="${this.principle_brand_nama}"></td>
                                        <td style='vertical-align:middle; text-align: center;' >${this.ed}<input type="hidden" class="form-control" name="ed[]" value="${this.ed}"></td>
                                        <td style='vertical-align:middle; text-align: center;width:5%' >${stock}<input type="hidden" class="form-control" name="sku_stock[]"  id="sku_stock-${index}" value="${stock}"></td>
                                        <td style='vertical-align:middle; text-align: center;width:5%' ><input type="number" class="form-control" name="qty_dtl[]" id="qty_dtl-${index}" value="${qty_dtl}" onchange="cekQtyDtl('${index}')"/></td>
                                    </tr>
                        `);
				})
			}
		});
		// $('#tableSKU').DataTable();

	}

	function cekQtyDtl(index) {
		var stock = parseInt($('#sku_stock-' + index).val());
		var qty = $('#qty_dtl-' + index).val()
		var qty_dod = parseInt($('#qty_sku_src').val());


		let qty_total = 0;
		console.log(qty_total);
		let qtyttl = $("input[name='qty_dtl[]']").map(function() {
			qty_total += parseInt($(this).val())
			return parseInt($(this).val());
		}).get();

		// $.each(qtyttl,(value) => {
		//     qty_total += parseInt(value)
		// });
		console.log(qtyttl);
		console.log(qty_total);

		if (qty_total > qty_dod) {
			alert('Jumlah Qty yang diambil melebihi Qty yg ada di DO')
			$('#qty_dtl-' + index).val(0)
			return false;
		}
		if (qty > stock) {
			alert('Sisa Stok tidak mencukupi !!')
			$('#qty_dtl-' + index).val(0)
			return false
		}
	}


	function setTableSKU(data) {

		$('#tableSKU tbody').html('')
		console.log(data);
		$.each(data, function(index) {

			// <td style='vertical-align:middle; text-align: center;width:10%' >${this.depo_nama}<input type="hidden" class="form-control" name="depo_nama[]" value="${this.depo_nama}"><input type="hidden" class="form-control" name="dod_id1[]" value="${this.dod_id}"></td>
			// <td style='vertical-align:middle; text-align: center;width:10%' >${this.depo_detail_nama}<input type="hidden" class="form-control" name="depo_detail_nama[]" value="${this.depo_detail_nama}"></td>
			$('#tableSKU tbody').append(`
                                    <tr>
                                        <td style='vertical-align:middle; text-align: center;width:10%' >${this.sku_induk_nama}<input type="hidden" class="form-control" name="sku_induk_nama[]" value="${this.sku_induk_nama}"></td>
                                        <td style='vertical-align:middle; text-align: center;width:10%' >${this.sku_nama_produk}<input type="hidden" class="form-control" name="sku_nama_produk[]" value="${this.sku_nama_produk}"></td>
                                        <td style='vertical-align:middle; text-align: center;width:5%'' >${this.sku_kemasan}<input type="hidden" class="form-control" name="sku_kemasan[]" value="${this.sku_kemasan}"></td>
                                        <td style='vertical-align:middle; text-align: center;width:5%'' >${this.sku_satuan}<input type="hidden" class="form-control" name="sku_satuan[]" value="${this.sku_satuan}"></td>
                                        <td style='vertical-align:middle; text-align: center;' >${this.principle_kode}<input type="hidden" class="form-control" name="principle_kode[]" value="${this.principle_kode}"></td>
                                        <td style='vertical-align:middle; text-align: center;' >${this.principle_brand_nama}<input type="hidden" class="form-control" name="principle_brand_nama[]" value="${this.principle_brand_nama}"></td>
                                        <td style='vertical-align:middle; text-align: center;' >${this.ed}<input type="hidden" class="form-control" name="ed[]" value="${this.ed}"></td>
                                        <td style='vertical-align:middle; text-align: center;width:5%' >${this.sku_stock}<input type="hidden" class="form-control" name="sku_stock[]" value="${this.sku_stock}"></td>
                                        <td style='vertical-align:middle; text-align: center;width:5%' ><input type="number" class="form-control"  name="qty_dtl[]" value="${this.qty}" readonly/></td>
                                    </tr>
                        `);
		});
	}

	function addSkuED() {

		var qty_dod = parseInt($('#qty_sku_src').val());
		let qty_total = 0;
		let qtyttl = $("input[name='qty_dtl[]']").map(function() {
			qty_total += parseInt($(this).val())
			return parseInt($(this).val());
		}).get();

		if (qty_total != qty_dod) {
			alert('Jumlah Qty yang diambil tidak sama dengan Qty yg ada di DO')
			return false;
		}


		var idx_do = $('#idx_do').val();
		$("#chkDetail-" + idx_do).val(1);
		$("#chkDetail-" + idx_do).prop('checked', true);
		// data do_detail_id
		// let dod_id = $("input[name='dod_id1[]']").val()
		let dod_id = $("input[name='dod_id1[]']").map(function() {
			return $(this).val();
		}).get();
		// console.log(dod_id);
		let depo_nama = $("input[name='depo_nama[]']").map(function() {
			return $(this).val();
		}).get();
		let depo_detail_nama = $("input[name='depo_detail_nama[]']").map(function() {
			return $(this).val();
		}).get();
		let sku_stock_id = $("input[name='sku_stock_id[]']").map(function() {
			return $(this).val();
		}).get();
		let sku_induk_nama = $("input[name='sku_induk_nama[]']").map(function() {
			return $(this).val();
		}).get();
		let sku_nama_produk = $("input[name='sku_nama_produk[]']").map(function() {
			return $(this).val();
		}).get();
		let sku_kemasan = $("input[name='sku_kemasan[]']").map(function() {
			return $(this).val();
		}).get();
		let sku_satuan = $("input[name='sku_satuan[]']").map(function() {
			return $(this).val();
		}).get();
		let principle_kode = $("input[name='principle_kode[]']").map(function() {
			return $(this).val();
		}).get();
		let principle_brand_nama = $("input[name='principle_brand_nama[]']").map(function() {
			return $(this).val();
		}).get();
		// console.log(principle_brand_nama);
		let ed = $("input[name='ed[]']").map(function() {
			return $(this).val();
		}).get();
		let sku_stock = $("input[name='sku_stock[]']").map(function() {
			return $(this).val();
		}).get();
		let qty = $("input[name='qty_dtl[]']").map(function() {
			return $(this).val();
		}).get();

		// update untuk data dod yg sudah di check
		let index_dod = dataDoD.findIndex(item => item.dod_id == dod_id[0]);
		dataDoD[index_dod]['chk_data'] = 1;

		//hasilnya berupa array
		//karena 1 form sku 1 do_detail, maka
		// let data_dod_id = dod_id[0];

		// UPDATE SAVE QTY SKU ED
		// get row data Array by dod_id
		// let row = dataSkuED.find(item => item.dod_id == dod_id[0]);

		// let index_data = dataSkuED.findIndex(item => item.dod_id == dod_id[0]);
		for (let i = 0; i < qty.length; i++) {
			console.log('aa');
			if (qty[i] <= 0) {
				continue
			} else {
				let index_data = dataSkuED.findIndex(item => item.dod_id == dod_id[i] && item.sku_stock_id == sku_stock_id[i]);
				console.log(index_data);
				if (index_data >= 0) {
					dataSkuED[index_data]['qty'] = qty[i];
					// dataSKUD.splice(index_data, 1);
				} else {
					dataSkuED.push({
						'dod_id': dod_id[i],
						'depo_nama': depo_nama[i],
						'depo_detail_nama': depo_detail_nama[i],
						'sku_stock_id': sku_stock_id[i],
						'sku_induk_nama': sku_induk_nama[i],
						'sku_kemasan': sku_kemasan[i],
						'sku_satuan': sku_satuan[i],
						'principle_kode': principle_kode[i],
						'principle_brand_nama': principle_brand_nama[i],
						'ed': ed[i],
						'sku_stock': sku_stock[i],
						'qty': qty[i]
					})
				}
			}

		}
		$("#modalSKU").modal('hide');
		// console.log(index_data);
	}

	// $('input[name="chkED[]"]').change(function(){
	//     var index = $(this).val();
	// 	if(this.checked){

	//             dataSKUD[index]['chkED'] = 1;
	//             console.log();

	//     }else{
	// 		dataSKUD[index]['chkED'] = 0;
	//     }
	//     console.log('aa');
	//     console.log(index);
	//     console.log(dataSKUD);
	// });
</script>