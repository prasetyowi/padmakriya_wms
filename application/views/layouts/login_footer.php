<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


    <!-- jQuery -->
    <script src="<?php echo Get_Assets_Url(); ?>vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="<?php echo Get_Assets_Url(); ?>vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- DataTable -->
    <script src="<?php echo Get_Assets_Url(); ?>vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo Get_Assets_Url(); ?>vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="<?php echo Get_Assets_Url(); ?>vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo Get_Assets_Url(); ?>vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
	
	
    <!-- bootstrap-daterangepicker -->
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
	
    <!-- Custom Theme Scripts -->
    <script src="<?php echo base_url(); ?>assets/js/custom.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/validation.js"></script>
	
	
	<?php if (isset($js_files)) : ?>
		<?php foreach($js_files as $file): ?>
		
		<script src="<?php echo $file; ?>"></script>
		<?php endforeach; ?>
	<?php endif; ?>

	<script>
		var dTable;
		$(function(){
			
		});
	</script>


	
  </body>
</html>