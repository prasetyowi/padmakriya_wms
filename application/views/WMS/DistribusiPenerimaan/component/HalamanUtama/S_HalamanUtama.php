<script type="text/javascript">
	loadingBeforeReadyPage()
	$(document).ready(function() {
		select2();
	});

	// function message(msg, msgtext, msgtype) {
	//   Swal.fire(msg, msgtext, msgtype);
	// }

	// function message_topright(type, msg) {
	//   const Toast = Swal.mixin({
	//     toast: true,
	//     position: "top-end",
	//     showConfirmButton: false,
	//     timer: 3000,
	//     didOpen: (toast) => {
	//       toast.addEventListener("mouseenter", Swal.stopTimer);
	//       toast.addEventListener("mouseleave", Swal.resumeTimer);
	//     },
	//   });

	//   Toast.fire({
	//     icon: type,
	//     title: msg,
	//   });
	// }

	function select2() {
		$(".select2").select2({
			width: "100%"
		});
	}

	$(document).on("click", "#search_filter_data", function() {
		let tahun = $("#tahun_filter").val();
		let bulan = $("#bulan_filter").val();
		let kode = $("#kode").val();
		let status = $("#status").val();
		$.ajax({
			url: "<?= base_url('WMS/DistribusiPenerimaan/get_data_penerimaan_by_params') ?>",
			type: "POST",
			data: {
				tahun: tahun,
				bulan: bulan,
				kode: kode,
				status: status,
			},
			dataType: "JSON",
			success: function(data) {
				$("#loadingsearch").hide();
				$("#list-data-form-search").show();
				let no = 1;
				if (data != null) {
					if ($.fn.DataTable.isDataTable('#listdata')) {
						$('#listdata').DataTable().destroy();
					}
					$('#listdata > tbody').empty();
					$.each(data, function(i, v) {
						let str = "<button data-href='<?= base_url('WMS/DistribusiPenerimaan/view/') ?>" + v.id + "' class='btn btn-primary form-control btn_view' title='Detail Data'><i class='fa fa-eye'></i> Detail</button>";
						$("#listdata > tbody").append(`
              <tr class="text-center">
                  <td>${no++}</td>
                  <td>${v.kode}</td>
                  <td>${v.tgl}</td>
                  <td>${v.pp_kode}</td>
                  <td>${v.depo}</td>
                  <td>${v.status}</td>
                  <td>${str}</td>
              </tr>
          `);
					});


				} else {
					$("#listdata > tbody").html(`<tr><td colspan="6" class="text-center text-danger">Data Kosong</td></tr>`);
				}

				$('#listdata').DataTable({
					columnDefs: [{
						sortable: false,
						targets: [0, 1, 2, 3, 4, 5, 6]
					}],
				});

			}
		});

		$(document).on("click", ".btn_view", function() {
			let href = $(this).attr("data-href");
			location.href = href;
		});
	});
</script>