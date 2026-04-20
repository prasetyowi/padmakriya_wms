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

	<title><?php echo $Title; ?></title>

	<!-- BOOTSTRAP & CSS -->

	<!-- Bootstrap -->
	<link href="<?php echo Get_Assets_Url(); ?>vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<!-- Font Awesome -->
	<link href="<?php echo Get_Assets_Url(); ?>vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
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
	
	
	
	
	<?php if (isset($css_files)) : ?>

	<?php foreach($css_files as $file): ?>
		<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
	<?php endforeach; ?>
	<?php endif; ?>

	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CQuicksand:300,400,500,700" rel="stylesheet">

  </head>

  <body class="login" style="font-family: 'Quicksand', Georgia, 'Times New Roman', Times, serif;">
 