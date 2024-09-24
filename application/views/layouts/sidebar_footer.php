<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<footer>
	<div class="pull-right">
		<?= $Copyright ?>
	</div>
	<div class="clearfix"></div>
</footer>

</div>
</div>


<!-- jQuery -->
<script src="<?php echo Get_Assets_Url(); ?>vendors/jquery/dist/jquery.min.js"></script>

<!-- Bootstrap -->
<script src="<?php echo Get_Assets_Url(); ?>vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTable -->
<script src="<?php echo Get_Assets_Url(); ?>vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Get_Assets_Url(); ?>vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo Get_Assets_Url(); ?>vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo Get_Assets_Url(); ?>vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
<script src="<?php echo Get_Assets_Url(); ?>vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="<?php echo Get_Assets_Url(); ?>vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo Get_Assets_Url(); ?>vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo Get_Assets_Url(); ?>vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
<script src="<?php echo Get_Assets_Url(); ?>vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="<?php echo Get_Assets_Url(); ?>vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo Get_Assets_Url(); ?>vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<script src="<?php echo Get_Assets_Url(); ?>vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
<script src="<?php echo Get_Assets_Url(); ?>vendors/jszip/dist/jszip.min.js"></script>
<script src="<?php echo Get_Assets_Url(); ?>vendors/pdfmake/build/pdfmake.min.js"></script>
<script src="<?php echo Get_Assets_Url(); ?>vendors/pdfmake/build/vfs_fonts.js"></script>



<script src="<?php echo Get_Assets_Url(); ?>vendors/moment/min/moment.min.js"></script>
<script src="<?php echo Get_Assets_Url(); ?>vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

<!-- timepicker -->
<script src="<?php echo Get_Assets_Url(); ?>vendors/less/less.min.js"></script>
<script src="<?php echo Get_Assets_Url(); ?>vendors/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>

<!-- jQuery autocomplete -->
<script src="<?php echo Get_Assets_Url(); ?>vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>

<!-- select2 -->
<script src="<?php echo Get_Assets_Url(); ?>vendors/select2/dist/js/select2.full.min.js"></script>
<!-- sweet alert -->
<script src="<?php echo Get_Assets_Url(); ?>vendors/sweetalert2/dist/sweetalert2.min.js"></script>

<!-- datatable sorter -->
<script src="<?php echo Get_Assets_Url(); ?>vendors/datatables-sorter-plugin/date-uk.js"></script>
<script src="<?php echo Get_Assets_Url(); ?>vendors/datatables-sorter-plugin/datetime-moment.js"></script>
<script src="<?php echo Get_Assets_Url(); ?>vendors/datatables-sorter-plugin/numeric-comma.js"></script>

<script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>
<script src="<?php echo Get_Assets_Url(); ?>vendors/bootstrap-select/bootstrap-select.min.js"></script>

<!-- Custom Theme Scripts -->
<script src="<?php echo Get_Assets_Url(); ?>assets/js/custom.js"></script>
<script src="<?php echo Get_Assets_Url(); ?>assets/js/validation.js"></script>
<script src="<?php echo Get_Assets_Url(); ?>vendors/leaflet/leaflet.js"></script>
<script src="<?php echo Get_Assets_Url(); ?>vendors/leaflet/esri-leaflet.js"></script>
<script src="<?php echo Get_Assets_Url(); ?>vendors/leaflet/esri-leaflet-vector.js"></script>
<script src="<?php echo Get_Assets_Url(); ?>vendors/leaflet/leaflet-control-geocoder.js"></script>
<!-- <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script> -->

<script src="<?php echo Get_Assets_Url(); ?>vendors/GoJS/release/go.js"></script>
<script src="<?php echo Get_Assets_Url(); ?>vendors/GoJS/extensions/Figures.js"></script>
<script src="<?php echo Get_Assets_Url(); ?>Global/gojs-draggablelink-readonly.js"></script>
<script src="<?php echo Get_Assets_Url(); ?>assets/js/jquery.inputmask.bundle.js"></script>

<script src="https://cdn.datatables.net/fixedcolumns/3.2.4/js/dataTables.fixedColumns.min.js"></script>
<?php if (isset($js_files)) : ?>
	<?php foreach ($js_files as $file) : ?>

		<script src="<?php echo $file; ?>"></script>
	<?php endforeach; ?>
<?php endif; ?>

<script>
	var dTable;
	$(function() {

		$('.bootstrap-select').selectpicker();

		$.fn.dataTable.moment('DD/MM/YYYY HH:mm:ss');

		dTable = $(".table-datatable").DataTable({
			"lengthMenu": [
				[10, 25, 50, 100, 250 - 1],
				[10, 25, 50, 100, 250, "All"]
			],
			"pageLength": 25,
			responsive: true
		});

		$('.datepicker').daterangepicker({
			locale: {
				format: 'DD/MM/YYYY'
			},
			minDate: new Date('<?= getLastTbgDepo() ?>'),
			showDropdowns: true,
			singleDatePicker: true,
			calender_style: "picker_4"
		}, function(start, end, label) {

		});


		/*$('.timepicker').timepicker({
                minuteStep: 1,
				secondStep: 1,
                showSeconds: true,
                defaultTime: false,
                template: 'dropdown',
                showInputs: false,
                showMeridian: false,
				explicitMode: true,
			});
			
			$('.timepicker_nosecond').timepicker({
                minuteStep: 1,
				secondStep: 1,
                showSeconds: false,
                defaultTime: false,
                template: 'dropdown',
                showInputs: false,
                showMeridian: false,
				explicitMode: true,
			});*/


		showMessage = function(msg, title) {
			Swal.fire(
				title,
				msg,
				'info'
			);
		}

		confirmMessage = function(msg, title, href) {
			Swal.fire({
				title: title,
				text: msg,
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
			}).then((result) => {
				if (result.value) {
					document.location.href = href;
				}
			})
		};

		deleteMessage = function(href) {
			Swal.fire({
				title: 'Delete',
				text: "Do you want to delete this data?",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes'
			}).then((result) => {
				if (result.value) {
					document.location.href = href;
				}
			})
		}

		$(document).on("keydown", function(e) {
			if (e.which === 8 && !$(e.target).is("input, textarea")) {
				e.preventDefault();
			}
		});
	});

	function run_input_mask_money() {
		$(".mask-money").inputmask({
			alias: 'numeric',
			groupSeparator: '.',
			radixPoint: ',',
			autoGroup: true,
			digits: 4,
			digitsOptional: false,
			placeholder: '0,0000',
			min: '0',
			// 'max': '99999',
			autoUnmask: true,
			removeMaskOnSubmit: true,
			showMaskOnHover: true,
		});
		$(".mask-money3").inputmask({
			alias: 'numeric',
			groupSeparator: '.',
			radixPoint: ',',
			autoGroup: true,
			digits: 3,
			digitsOptional: false,
			placeholder: '0,000',
			min: '0',
			// 'max': '99999',
			autoUnmask: true,
			removeMaskOnSubmit: true,
			showMaskOnHover: true,
		});
		$(".mask-money2").inputmask({
			alias: 'numeric',
			groupSeparator: '.',
			radixPoint: ',',
			autoGroup: true,
			digits: 2,
			digitsOptional: false,
			placeholder: '0,00',
			min: '0',
			// 'max': '99999',
			autoUnmask: true,
			removeMaskOnSubmit: true,
			showMaskOnHover: true,
		});
	}
</script>



</body>

</html>