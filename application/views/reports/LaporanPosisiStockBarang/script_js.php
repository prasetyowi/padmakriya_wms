<script type="text/javascript">
	var index_pallet = 0;

	$(document).ready(
		function() {
			$(".select2").select2();
		}
	);

	$("#btn_stock_pencarian").click(
		function() {
			$('#table_stock_rekap').DataTable().destroy();
			GetLaporanStockRekapData();
		}
	);

	function GetLaporanStockRekapData() {
		$(".panel-stock-rekap").show();

		$.ajax({
			url: "<?= base_url('WMS/LaporanTransaksi/LaporanPosisiStockBarang/GetLaporanPosisiStockBarang') ?>",
			data: {
				principle_id: $('#filter_stock_principle').val(),
				depo_detail_id: $('#filter_stock_gudang').val() == null ? null : $('#filter_stock_gudang').val().join(',')
			},
			type: 'POST',
			dataType: 'json',
			success: function(response) {
				$('#table_stock_rekap').DataTable({
					data: response.data,
					columns: [{
							data: 'principle_kode'
						},
						{
							data: 'sku_konversi_group'
						},
						{
							data: 'sku_nama_produk'
						},
						{
							data: 'composite'
						},
						{
							data: 'Ratio'
						},
						{
							data: 'QtyInPcs'
						}
					],
					paging: true,
					ordering: true,
					info: true,
					searching: false,
					dom: 'Blfrtip',
					buttons: [{
							extend: "print",
							text: '<i class="fa fa-print"></i> Print',
							className: 'btn btn-warning',
							customize: function(win) {
								var last = null;
								var current = null;
								var bod = [];

								var css = '@page { size: landscape; }',
									head = win.document.head || win.document.getElementsByTagName('head')[0],
									style = win.document.createElement('style');

								style.type = 'text/css';
								style.media = 'print';

								if (style.styleSheet) {
									style.styleSheet.cssText = css;
								} else {
									style.appendChild(win.document.createTextNode(css));
								}

								head.appendChild(style);
							}
						},
						{
							extend: 'excelHtml5',
							text: '<i class="fa fa-file-excel"></i> Excel',
							className: 'btn btn-success',
						},
						{
							extend: 'csvHtml5',
							text: '<i class="fa fa-file"></i> CSV',
							className: 'btn btn-info',
						},
						{
							extend: 'pdfHtml5',
							orientation: 'landscape',
							text: '<i class="fa fa-file-pdf"></i> PDF',
							className: 'btn btn-danger',
							pageSize: 'A4'
						}
					],
					lengthMenu: [
						[-1],
						['All']
					],
					scrollY: '800px',
					processing: true
				});

				// Hide loading spinner after DataTables initialization
				Swal.close();
			},
			error: function(xhr, textStatus, errorThrown) {
				// Handle errors
				Swal.fire({
					icon: 'error',
					title: 'Oops...',
					text: 'Something went wrong!',
				});
			}
		});

		// Show loading spinner before AJAX request
		Swal.fire({
			title: 'Loading...',
			allowOutsideClick: false,
			onBeforeOpen: () => {
				Swal.showLoading();
			}
		});
		return false;
		//yang dibawah dipakek serverside datatable
		/** 
		$('#table_stock_rekap').DataTable({
			paging: true,
			ordering: true,
			info: true,
			searching: false,
			dom: 'Blfrtip',
			buttons: [{
					extend: "print",
					text: '<i class="fa fa-print"></i> Print',
					className: 'btn btn-warning',
					customize: function(win) {
						var last = null;
						var current = null;
						var bod = [];

						var css = '@page { size: landscape; }',
							head = win.document.head || win.document.getElementsByTagName('head')[0],
							style = win.document.createElement('style');

						style.type = 'text/css';
						style.media = 'print';

						if (style.styleSheet) {
							style.styleSheet.cssText = css;
						} else {
							style.appendChild(win.document.createTextNode(css));
						}

						head.appendChild(style);
					}
				},
				{
					extend: 'excelHtml5',
					text: '<i class="fa fa-file-excel"></i> Excel',
					className: 'btn btn-success',
				},
				{
					extend: 'csvHtml5',
					text: '<i class="fa fa-file"></i> CSV',
					className: 'btn btn-info',
				},
				{
					extend: 'pdfHtml5',
					orientation: 'landscape',
					text: '<i class="fa fa-file-pdf"></i> PDF',
					className: 'btn btn-danger',
					pageSize: 'A4'
				}
			],
			lengthMenu: [
				[-1],
				['All']
			],
			scrollY: '800px',
			// scrollCollapse: true,
			// fixedHeader: true,
			// scrollX: true,
			'processing': true,
			'serverSide': true,
			'serverMethod': 'post',
			'ajax': {
				url: "<?= base_url('WMS/LaporanTransaksi/LaporanPosisiStockBarang/GetLaporanPosisiStockBarang') ?>",
				data: {
					principle_id: $('#filter_stock_principle').val()
				},
				beforeSend: function() {
					// Show loading spinner before AJAX request
					Swal.fire({
						title: 'Loading...',
						allowOutsideClick: false,
						onBeforeOpen: () => {
							Swal.showLoading();
						}
					});
				},
				complete: function() {
					// Hide loading spinner after AJAX request
					Swal.close();
				}
			},
			'columns': [{
					data: 'principle_kode'
				},
				{
					data: 'sku_konversi_group'
				},
				{
					data: 'sku_nama_produk'
				},
				{
					data: 'composite'
				},
				{
					data: 'Ratio'
				},
				{
					data: 'QtyInPcs'
				}
			]
		});
		*/
	}
</script>