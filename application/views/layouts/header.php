<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<!-- Meta, title, CSS, favicons, etc. -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title><?= Get_Title_Name();?></title>

	<!-- BOOTSTRAP & CSS -->

	<!-- Bootstrap -->
	<link href="<?php echo Get_Assets_Url(); ?>vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<!-- Font Awesome -->
	<!--<link href="<?php echo Get_Assets_Url(); ?>vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet"-->
	<link href="<?php echo Get_Assets_Url(); ?>vendors/fontawesome-free-6.1.1-web/css/all.css" rel="stylesheet">
	<!-- DataTables.css -->
	<link href="<?php echo Get_Assets_Url(); ?>vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo Get_Assets_Url(); ?>vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">


		
	<!-- bootstrap-daterangepicker -->
	<link href="<?php echo Get_Assets_Url(); ?>vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

	<!-- select2 -->
	<link href="<?php echo Get_Assets_Url(); ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet" media="screen">
	<link href="<?php echo Get_Assets_Url(); ?>vendors/select2/dist/css/select2-bootstrap.min.css" rel="stylesheet" media="screen">
	<!-- sweetalert -->
	<link href="<?php echo Get_Assets_Url(); ?>vendors/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet" media="screen">

	<!-- Custom Theme Style -->
	<link href="<?php echo base_url(); ?>assets/css/custom.css" rel="stylesheet">

	<!-- Screen Style -->
	<link href="<?php echo base_url(); ?>assets/css/screen.css" rel="stylesheet" media="screen">
	<!-- Print Style -->
	<link href="<?php echo base_url(); ?>assets/css/print.css" rel="stylesheet" media="print">

	<link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css" rel="stylesheet" />
	
	
	
	
	<?php if (isset($css_files)) : ?>

	<?php foreach($css_files as $file): ?>
		<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
	<?php endforeach; ?>
	<?php endif; ?>

	<script>
		const base_url = "<?= base_url()?>"
	</script>
 
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CQuicksand:300,400,500,700" rel="stylesheet">

  </head>

  <body class="nav-md" style="font-family: 'Quicksand', Georgia, 'Times New Roman', Times, serif;">
    <div class="container body">
      <div class="main_container">
         
            <div class="col-md-3 left_col">
			  <div class="left_col scroll-view">
				
				
				<div class="navbar nav_title" style="border: 0; height:0; min-height:0">
				
				</div>
			
				<div class="clearfix"></div>
				
				<!-- menu profile quick info -->
				<div class="profile clearfix">
					<div class="profile_pic">
					
					</div>
					<div class="profile_info">
						<span>Welcome, </span>
						<h2><?php echo $Ses_UserName; ?></h2>
					</div>
				</div>
				<!-- /menu profile quick info -->

				<br />

				<div class="clearfix"></div>

				

				<!-- sidebar menu -->
				<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
				  <div class="menu_section">
					<?php echo $sidemenu; ?>
				  </div>

				</div>
				<!-- /sidebar menu -->

				<!-- /menu footer buttons -->
				<div class="sidebar-footer hidden-small">
				  <!--<a href="logout.php" data-toggle="tooltip" data-placement="top" title="Logout">
					<span class="glyphicon glyphicon-off" aria-hidden="true"></span>
				  </a>-->
				</div>
				<!-- /menu footer buttons -->
			  </div>
			</div>       
				<div class="top_nav">
				  <div class="nav_menu">
					<nav>
					  <div class="nav toggle">
						<a id="menu_toggle"><i class="fa fa-bars"></i></a>
					  </div>

					  
					</nav>
				  </div>
				</div>
             <!-- top navigation -->
