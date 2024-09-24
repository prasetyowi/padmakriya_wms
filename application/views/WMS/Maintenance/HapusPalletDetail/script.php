<script>
	let arrayDetail = []
	$(document).ready(function() {
		table_dt = $('#table_data').DataTable({
			// "scrollX": true,
			'paging': true,
			'searching': true,
			'ordering': true,
			'order': [
				[0, 'asc']
			],
			'lengthMenu': [
				[50, 100, 150, -1], // -1 untuk menunjukkan opsi "All"
				[50, 100, 150, "All"]
			],
			'processing': true,
			'serverSide': true,
			// 'deferLoading': 0,
			'ajax': {
				url: "<?= base_url('WMS/Maintenance/HapusPalletDetail/getData') ?>",
				type: "POST",
				dataType: "json",
				data: function(data) {
					// data.gl01 = $("#filter_gl01").val()
				},
				beforeSend: function() {
					Swal.fire({
						title: 'Loading ...',
						html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
						timerProgressBar: false,
						showConfirmButton: false
					});
				},
				complete: function() {
					Swal.close();
				},
			},
			"drawCallback": function(response) {
				var resp = response.json;
				console.log(resp);
				arrayDetail = []
				for (var i = 0; i < resp.data.length; i++) {

					arrayDetail.push(resp.data[i]);
				}

				console.log(arrayDetail);
			},
			'columns': [{
					data: 'idx'
				},
				{
					data: 'principle_nama'
				},
				{
					data: 'pallet_kode'
				},
				{
					data: 'sku_nama_produk'
				},
				{
					data: 'sku_stock_expired_date'
				},
				{
					data: 'sum_total'
				},

			],
			"columnDefs": [{
					targets: 0,
					searchable: false
				},
				{
					targets: 1,
				},
				{
					targets: 2,
				},
				{
					targets: 3,
				},
				{
					targets: 4,
				},
				{
					targets: 5,
				},

			],
			initComplete: function() {
				parent_dt = $('#table_data').closest('.dataTables_wrapper')
				parent_dt.find('.dataTables_filter').css('width', 'auto')
				var input = parent_dt.find('.dataTables_filter input').unbind(),
					self = this.api(),
					$searchButton = $('<button class="btn btn-flat btn-success btn-sm mb-0 mr-0 ml-5 btn-search-dt">')
					.html('<i class="fa fa-fw fa-search">')
					.click(function() {
						self.search(input.val()).draw();
					}),
					$clearButton = $('<button class="btn btn-flat btn-warning btn-sm mb-0 mr-0 ml-5 btn-reset-dt">')
					.html('<i class="fa fa-fw fa-recycle">')
					.click(function() {
						input.val('');
						$searchButton.click();

					})
				parent_dt.find('.dataTables_filter').append($searchButton, $clearButton);
				parent_dt.find('.dataTables_filter input').keypress(function(e) {
					var key = e.which;
					if (key == 13) {
						$searchButton.click();
						return false;
					}
				});
			},
		});
	})
	const messageBoxBeforeRequestNew = (textMessage, textButtonConfirm, textButtonCancel) => {
		return Swal.fire({
			title: "Apakah anda yakin?",
			text: textMessage,
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: textButtonConfirm,
			cancelButtonText: textButtonCancel
		})
	}
	const requestAjaxIni = (urlRequest, dataRequet = {}, typePost, typeOutput, callbackSuccess, disabledButtonAction = "", multipartFormdata = "") => {

		showLoadingIni(false, disabledButtonAction)

		if (typePost == "POST") {

			$.ajax({
				url: urlRequest,
				type: typePost,
				data: dataRequet,
				dataType: typeOutput,
				beforeSend: function() {
					showLoadingIni(false, disabledButtonAction)
				},
				success: function(response) {
					showLoadingIni(false, disabledButtonAction, 10)
					callbackSuccess(response)
				},
				error: function(xhr, error, status) {
					showLoadingIni(false, disabledButtonAction, 10)

					alert(`${status} ${error}`);
				},
			});


		}
	}
	const showLoadingIni = (condition, disabledButtonAction, params = 0) => {
		disabledButtonAction !== "" ? $(disabledButtonAction).prop("disabled", condition) : '';
		Swal.fire({
			title: '<span><i class="fas fa-spinner fa-spin"></i></span> &nbsp;&nbsp;<span>Loading ...</span>',
			timerProgressBar: false,
			showConfirmButton: false,
			allowOutsideClick: false,
			timer: params
		});
	}
	const messageErrorNew = (msg, msgtext, msgtype) => {
		Swal.fire(msg, msgtext, msgtype);
	}
	const messageNew = (msg, msgtext, msgtype) => {
		Swal.fire(msg, msgtext, msgtype);
	}
	const hapusDetail = () => {
		// return false;
		if (arrayDetail.length == 0) {
			messageNew('warning', 'Data Kosong', 'warning')
			return false;
		}
		messageBoxBeforeRequestNew(`Anda Akan Menghapus Data Sebanyak ${arrayDetail.length}!`, 'Iya, Hapus', 'Tidak, Tutup').then((result) => {
			if (result.value == true) {
				requestAjaxIni("<?= base_url('WMS/Maintenance/HapusPalletDetail/deleteDetailPallet'); ?>", {
					detail: arrayDetail
				}, "POST", "JSON", function(response) {
					messageNew('info', 'Berhasil', 'info')

					$(".x_panel").fadeOut();
					setTimeout(() => {
						$(".x_panel").fadeIn();

					}, 1000);
					setTimeout(() => {
						table_dt.ajax.reload();

					}, 1000);
				}, "#btn_delete", "")
			} else {

			}
		});


	}
</script>