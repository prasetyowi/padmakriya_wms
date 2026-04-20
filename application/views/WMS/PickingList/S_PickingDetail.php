<script type="text/javascript">
	// loadingBeforeReadyPage()
	$(document).ready(function() {
		// alert('aa')
		$('.select2').select2({
			width: '100%'
		});
		// $('#tableSKU').DataTable();
		getDoByBatchId('<?= $picking->delivery_order_batch_id ?>', '<?= $picking_list_id ?>');
	});

	function getDoByBatchId(DoBatchId, PickingListID) {
		$.ajax({
			type: 'GET',
			url: "<?= base_url('WMS/Distribusi/PermintaanBarang/getDoByBatchId') ?>",
			data: {
				DoBatchId: DoBatchId,
				PickingListID: PickingListID,
			},
			success: function(response) {
				let no = 1;
				let data = response;

				dataDoD = [];

				dataSkuED = [];

				// if( $.fn.DataTable.isDataTable('#tablesobosnet') ) 
				// {
				//     $('#tablesobosnet').DataTable().destroy();
				// }

				$("#tableDO tbody").empty();
				countDO = data.length;
				// $('#tableDO').DataTable().clear();
				// <input class="form-control" type="checkbox" name="chkDO[]" value="0" >
				if (data.length > 0) {

					$.each(data, function(index, value) {
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
                                    <td style='vertical-align:middle; ' >${value.delivery_order_no_urut_rute}</td>
                                    <td style='vertical-align:middle; ' >${value.delivery_order_prioritas_stock}</td>
                                    <td style='vertical-align:middle; ' >${value.delivery_order_status}</td>
                                    <td style='vertical-align:middle; ' ><button class="btn btn-info" onclick="detailSKU('${value.delivery_order_id}','${value.delivery_order_kode}')">Detail</button></td>
                                    <td style='vertical-align:middle; ' ><input class="form-control" type="checkbox" name="chkDetail[]" id="chkDetail-${index}" value="0" checked disabled></td>
                                </tr>
                            `);

						// tableDO.row.add(tr[0]).draw()
						no++;
						// console.log(index);
					});
				} else {
					$("#tableDO tbody").html('');
				}


				$('#tableDO').DataTable();
			}
		});
	}

	function detailSKU(do_id, do_kode) {
		$('#modalSKU').modal("show");
		// console.log(dod_id);
		// console.log(gudabg_id);
		var picking_list_id = '<?= $picking->picking_list_id ?>'
		$('#tableSKU tbody').html('')
		$.ajax({
			type: 'GET',
			url: "<?= base_url('WMS/Distribusi/PermintaanBarang/getPickingDetailSKU') ?>",
			data: {
				picking_list_id: picking_list_id,
				do_id: do_id,
			},
			success: function(response) {

				let data = response;

				$.each(data, function() {
					let qty_dtl = 0;
					// cek apakah ada ed sku yg tersimpan
					$('#tableSKU tbody').append(`
                                    <tr>
                                        <td style='vertical-align:middle; text-align: center;width:10%' >${do_kode}</td>
                                        <td style='vertical-align:middle; text-align: center;width:10%' >${this.sku_induk_nama}<input type="hidden" class="form-control" name="sku_induk_nama[]" value="${this.sku_induk_nama}"><input type="hidden" class="form-control" name="sku_stock_id[]" value="${this.sku_stock_id}"></td>
                                        <td style='vertical-align:middle; text-align: center;width:10%' >${this.sku_nama_produk}<input type="hidden" class="form-control" name="sku_nama_produk[]" value="${this.sku_nama_produk}"></td>
                                        <td style='vertical-align:middle; text-align: center;width:5%'' >${this.sku_kemasan}<input type="hidden" class="form-control" name="sku_kemasan[]" value="${this.sku_kemasan}"></td>
                                        <td style='vertical-align:middle; text-align: center;width:5%'' >${this.sku_satuan}<input type="hidden" class="form-control" name="sku_satuan[]" value="${this.sku_satuan}"></td>
                                        <td style='vertical-align:middle; text-align: center;' >${this.principle_kode}<input type="hidden" class="form-control" name="principle_kode[]" value="${this.principle_kode}"></td>
                                        <td style='vertical-align:middle; text-align: center;' >${this.principle_brand_nama}<input type="hidden" class="form-control" name="principle_brand_nama[]" value="${this.principle_brand_nama}"></td>
                                        <td style='vertical-align:middle; text-align: center;' >${this.ed}<input type="hidden" class="form-control" name="ed[]" value="${this.ed}"></td>
                                        <td style='vertical-align:middle; text-align: center;width:5%' ><input type="number" class="form-control" name="qty_dtl[]" value="${this.sku_qty_order}" readonly/></td>
                                    </tr>
                        `);
				})
			}
		});
		// $('#tableSKU').DataTable();

	}
</script>