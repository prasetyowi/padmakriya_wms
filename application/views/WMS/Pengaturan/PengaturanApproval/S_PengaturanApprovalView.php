<script>
	loadingBeforeReadyPage()
	$(document).ready(function() {

	})
	$("#tableDetail").on("click", ".infoDetail", function() {
		console.log("awwaw");
		let cek = $(this).attr('class').split(' ')[3]
		let id_approval_group = $(this).attr('class').split(' ')[4]
		$('#tabledetailGroup > tbody').empty()
		if (id_approval_group == '') {
			message('Info!', 'Mohon pilih approval group terlebih dahulu!!', 'info')
			return false
		} else {
			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/Pengaturan/PengaturanApproval/getDataByApprovalGroupId') ?>",
				data: {
					id: id_approval_group
				},
				dataType: 'json',
				success: function(response) {
					$.each(response, function(i, v) {
						$('#tabledetailGroup > tbody').append(` 
						<tr>
						<td class="text-center" style="font-weight:bold">${i+1}</td>
						<td class="text-center" style="font-weight:bold">${v.karyawan_nama==null?'':v.karyawan_nama}</td>
						<td class="text-center" style="font-weight:bold">${v.approval_group_detail_no_urut==null?'':v.approval_group_detail_no_urut}</td>
						<td class="text-center" style="font-weight:bold">${v.karyawan_divisi_nama==null?'':v.karyawan_divisi_nama}</td>
						</tr>
						`)
					})

				}
			});
			$('#modalDetailGroup').modal('show')

		}
	});
</script>