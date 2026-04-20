<script>
	let arrayDetail = []
	$(document).ready(function() {
		getData();
	})

	function getData() {
		requestAjax("<?= base_url('WMS/Maintenance/KomparasiStockOrderAlokasi/execProsesMaintenanceOrderVSLokasi'); ?>", {
			mode: 0
		}, "POST", "JSON", function(response) {
			if (response.length > 0) {
				$('#table_data > tbody').empty('');

				if ($.fn.DataTable.isDataTable('#table_data')) {
					$('#table_data').DataTable().clear();
					$('#table_data').DataTable().destroy();
				}

				$(response).each(function(i, v) {
					$("#table_data tbody").append(`
						<tr>
							<td class="text-center">${v.sku_kode}</td>
							<td class="text-center">${v.sku_nama_produk}</td>
							<td class="text-center">${v.qty}</td>
							<td class="text-center">${v.sku_stock_alokasi}</td>
							<td class="text-center">${v.sku_stock_saldo_alokasi}</td>
							<td class="text-center">${v.qtyorder}</td>
							<td class="text-center">${v.flag}</td>
						</tr>
					`)
				})

				$("#table_data").DataTable({
					lengthMenu: [
						[50, 100, 200, -1],
						[50, 100, 200, 'All'],
					]
				})

			}
		}, "", "")
	}

	function syncData() {
		requestAjax("<?= base_url('WMS/Maintenance/KomparasiStockOrderAlokasi/execProsesMaintenanceOrderVSLokasi'); ?>", {
			mode: 1
		}, "POST", "JSON", function(response) {
			if (response.length > 0) {
				Swal.fire({
					title: "Data gagal diproses!",
					icon: "error",
					confirmButtonColor: "#3085d6",
					confirmButtonText: "Tutup",
					allowOutsideClick: false // Mencegah penutupan dengan klik di luar popup
				}).then((result) => {
					if (result.value == true) {}
				})
			} else {
				Swal.fire({
					title: "Data berhasil diproses",
					icon: "success",
					confirmButtonColor: "#3085d6",
					confirmButtonText: "Oke",
					allowOutsideClick: false // Mencegah penutupan dengan klik di luar popup
				}).then((result) => {
					if (result.value == true) {
						location.reload();
					}
				})
			}
		}, "", "")
	}
</script>