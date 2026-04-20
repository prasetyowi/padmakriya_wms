<script>
	$(document).ready(function() {
		// alert('aaa')
		$(".select2").select2({
			width: "100%"
		});
		$(".custom-select").select2({
			width: "100%"
		});
		$('#tablePengaturanApproval').DataTable();
		if ($('#filter_tanggal').length > 0) {
			$('#filter_tanggal').daterangepicker({
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


	function getDataSearch() {
		Swal.showLoading()
		$.ajax({
			async: false,
			type: 'GET',
			url: "<?= base_url('WMS/Pengaturan/PengaturanApproval/checkData') ?>",
			dataType: "JSON",
			success: function(response) {
				console.log(response);
				if (response == 1 || response == 2) {
					$.ajax({
						type: 'GET',
						url: "<?= base_url('WMS/Pengaturan/PengaturanApproval/getDataSearch') ?>",
						data: {},
						success: function(response) {

							let no = 1;
							let data = response;

							if ($.fn.DataTable.isDataTable('#tablePengaturanApproval')) {
								$('#tablePengaturanApproval').DataTable().destroy();
							}

							$("#tablePengaturanApproval tbody").empty();
							$("#tablePengaturanApproval tbody").html('');
							if (data.length > 0) {
								$.each(data, function() {

									$('#tablePengaturanApproval tbody').append(`
									<tr>
										<td style='vertical-align:middle; text-align: center;' >${no}</td>
										<td style='vertical-align:middle;text-align: center; ' >${this.approval_setting_parameter}</td>
										<td style='vertical-align:middle;text-align: center; ' >${this.approval_setting_keterangan}</td>
										<td style='vertical-align:middle;text-align: center; ' >${this.approval_setting_is_aktif == 1 ? 'aktif' : 'Tidak Aktif'}</td>
										<td style='vertical-align:middle;text-align: center; ' >${this.tgl}</td>
										<td style='vertical-align:middle;text-align: center; ' >${this.approval_setting_who_create}</td>
										<td style='vertical-align:middle;text-align: center; ' >
											<a class="btn btn-primary" tittle="lihat"  href="<?php echo site_url('WMS/Pengaturan/PengaturanApproval/PengaturanApprovalView?id=') ?>${this.approval_setting_id}"><i class="fas fa-eye"></i></a>
											<a class="btn btn-warning" tittle="Edit"  href="<?php echo site_url('WMS/Pengaturan/PengaturanApproval/PengaturanApprovalEdit?id=') ?>${this.approval_setting_id}"><i class="fas fa-edit"></i></a>
										</td>
									</tr>
						`);
									no++;
								});
							} else {
								$("#tablePengaturanApproval tbody").html('');
							}

							$('#tablePengaturanApproval').DataTable();
							swal.close();
							// $('#tablePengaturanApproval').DataTable();
						}
					});
				} else if (response == 3) {
					return message('Info', 'Mohon Inputkan Data Approval, Karena data kosong', 'info')


				} else if (response == 4) {
					return message('Info', 'Parameter Variabel Kosong', 'info')

				} else {
					err = 0;
				}
			}
		})

		Swal.close()
	}



	function formatRupiah(angka, prefix) {
		var rupiah = '';
		var angkarev = angka.toString().split('').reverse().join('');
		for (var i = 0; i < angkarev.length; i++)
			if (i % 3 == 0) rupiah += angkarev.substr(i, 3) + '.';
		return rupiah.split('', rupiah.length - 1).reverse().join('');
	}
</script>