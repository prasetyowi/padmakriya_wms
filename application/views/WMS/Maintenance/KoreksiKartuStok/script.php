<script>
	let arrayDetail = []
	// loadingBeforeReadyPage()

	$(document).ready(function() {
		showLoadingIni(true, "")
		window.addEventListener('load', function() {
			showLoadingIni(false, "", 10)
		});
		getData();
	})
	const getData = () => {
		if ($.fn.DataTable.isDataTable('#table_data')) {
			$('#table_data').DataTable().clear();
			$('#table_data').DataTable().destroy();
		}

		$('#table_data').DataTable({
			paging: false,
			ordering: false,
			info: false,
			searching: false,
			// dom: "Blfrtip",
			// buttons: [{
			// 	extend: 'excel',
			// 	className: "btn-sm",
			// 	messageTop: 'The information in this table is copyright to Sirius Cybernetics Corp.'
			// }],
			// autoWidth: 'true',
			// dom: 'Bfrtip',
			// dom: "Blfrtip",
			// dom: 'Bfrtip',
			// buttons: [{
			// 		extend: "print",
			// 		text: '<i class="fa fa-print"></i> Print',
			// 		className: 'btn btn-warning',
			// 		customize: function(win) {

			// 			var last = null;
			// 			var current = null;
			// 			var bod = [];

			// 			var css = '@page { size: landscape; }',
			// 				head = win.document.head || win.document.getElementsByTagName('head')[0],
			// 				style = win.document.createElement('style');

			// 			style.type = 'text/css';
			// 			style.media = 'print';

			// 			if (style.styleSheet) {
			// 				style.styleSheet.cssText = css;
			// 			} else {
			// 				style.appendChild(win.document.createTextNode(css));
			// 			}

			// 			head.appendChild(style);
			// 		}
			// 	},
			// 	{
			// 		extend: 'excelHtml5',
			// 		text: '<i class="fa fa-file-excel-o"></i> Excel',
			// 		className: 'btn btn-success',
			// 	},
			// 	{
			// 		extend: 'csvHtml5',
			// 		text: '<i class="fa fa-file"></i> CSV',
			// 		className: 'btn btn-info',
			// 	},
			// 	{
			// 		extend: 'pdfHtml5',
			// 		orientation: 'landscape',
			// 		text: '<i class="fa fa-file-pdf-o"></i> PDF',
			// 		className: 'btn btn-danger',
			// 		pageSize: 'A4'
			// 	}
			// ],
			'processing': true,
			'serverSide': true,
			// "scrollX": true,
			'serverMethod': 'post',
			'ajax': {
				url: "<?= base_url('WMS/Maintenance/KoreksiKartuStok/getData') ?>",
				data: {

				}
			},
			'columns': [{
					data: 'depo_detail_nama'
				},
				{
					data: 'principle_kode'
				},
				{
					data: 'sku_kode'
				},
				{
					data: 'sku_nama_produk'
				},
				{
					data: 'sku_stock_expired_date'
				},
				{
					data: 'qtystock'
				},
				{
					data: 'qtystockcard'
				},
				{
					data: 'selisih'
				},
				// {
				// 	data: 'stock_total'
				// },
			]
		});
	}
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

					messageErrorNew("Error!", `${status} ${error}`, 'error');
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

	const proses = () => {
		// return false;

		messageBoxBeforeRequestNew(`Data Akan DiProses!`, 'Iya, Proses', 'Tidak, Tutup').then((result) => {
			if (result.value == true) {
				requestAjaxIni("<?= base_url('WMS/Maintenance/KoreksiKartuStok/proses'); ?>", {}, "POST", "JSON", function(response) {
					messageNew('info', 'Berhasil', 'info')

					$(".x_panel").fadeOut();
					setTimeout(() => {
						$(".x_panel").fadeIn();

					}, 1000);
					setTimeout(() => {

						getData()
					}, 1000);



				}, "#btn_delete", "")
			} else {

			}
		});


	}
</script>