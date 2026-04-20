<script>
	let arrayDetail = []
	$(document).ready(function() {
		getData();
	})

	function getData() {
		requestAjax("<?= base_url('WMS/Maintenance/HapusPickingOrder/execProsesMaintenanceDokumenBKB'); ?>", {
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
							<td class="text-center">${v.picking_order_tanggal}</td>
							<td class="text-center">${v.picking_list_kode}</td>
							<td class="text-center">${v.picking_order_kode}</td>
							<td class="text-center">${v.picking_order_status}</td>
						</tr>
					`)
				})

				$("#table_data").DataTable({
					lengthMenu: [
						[-1],
						['All'],
					]
				})

			} else {
				$("#table_data tbody").append(`
						<tr>
							<td colspan="4" class="text-center"><strong>Data Kosong</strong></td>
						</tr>
					`)
			}
		}, "", "")
	}

	function hapusData() {
		messageBoxBeforeRequest(`ingin menghapus data ini`, 'Iya, Hapus', 'Tidak, Tutup').then((result) => {
			if (result.value == true) {
				requestAjax("<?= base_url('WMS/Maintenance/HapusPickingOrder/execProsesMaintenanceDokumenBKB'); ?>", {
					mode: 1
				}, "POST", "JSON", function(response) {
					if (response.length > 0) {
						Swal.fire({
							title: "Data gagal dihapus!",
							icon: "error",
							confirmButtonColor: "#3085d6",
							confirmButtonText: "Tutup",
							allowOutsideClick: false // Mencegah penutupan dengan klik di luar popup
						}).then((result) => {
							if (result.value == true) {}
						})
					} else {
						Swal.fire({
							title: "Data berhasil dihapus",
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
			} else {

			}
		});


	}
</script>