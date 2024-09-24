<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<!-- Meta, title, CSS, favicons, etc. -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title><?= $Title ?></title>

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
	<link href="<?php echo Get_Assets_Url(); ?>assets/css/custom.css" rel="stylesheet">

	<!-- Screen Style -->
	<link href="<?php echo Get_Assets_Url(); ?>assets/css/screen.css" rel="stylesheet" media="screen">
	<!-- Print Style -->
	<link href="<?php echo Get_Assets_Url(); ?>assets/css/print.css" rel="stylesheet" media="print">

	<link href="<?php echo Get_Assets_Url(); ?>assets/css/print.css" rel="stylesheet" media="print">

	<link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css" rel="stylesheet" />
	<link rel="stylesheet" href="<?php echo Get_Assets_Url(); ?>vendors/bootstrap-select/bootstrap-select.min.css">

	<link rel="stylesheet" href="<?php echo Get_Assets_Url(); ?>vendors/leaflet/leaflet.css" />
	<link rel="stylesheet" href="<?php echo Get_Assets_Url(); ?>vendors/leaflet/leaflet-defaulticon-compatibility.css" />
	<link rel="stylesheet" href="<?php echo Get_Assets_Url(); ?>vendors/leaflet/leaflet-defaulticon-compatibility.webpack.css" />
	<link rel="stylesheet" href="<?php echo Get_Assets_Url(); ?>vendors/leaflet/leaflet-control-geocoder.css" />
	<!-- <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" /> -->

	<link href="<?php echo Get_Assets_Url(); ?>Global/gojs-customshape.css" rel="stylesheet">


	<style type="text/css">
		.modal-body {
			max-height: calc(100vh - 210px);
			overflow-x: auto;
			overflow-y: auto;
		}

		.dark-mode {
			background-color: #27272a !important;
			/*background-color: #0f172a !important;*/
			color: white !important;
			transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
			transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
			transition-duration: 1000ms;
		}


		@media only screen and (max-width: 600px) {
			#menustep {
				height: 100vh;
			}
		}

		.info-number .pulse {
			animation: pulse 1s infinite ease-in-out alternate;
		}

		@keyframes pulse {
			from {
				transform: scale(0.8);
			}

			to {
				transform: scale(1.2);
			}
		}

		/* Small devices (portrait tablets and large phones, 600px and up) /
		  @media only screen and (min-width: 600px) {
			#menustep
			{
				height: 100vh;
			}
		  }

		  / Medium devices (landscape tablets, 768px and up) /
		  @media only screen and (min-width: 768px) {

			#menustep
			{
				height: 100vh;
			}
		  }

		  / Large devices (laptops/desktops, 992px and up) */
		@media only screen and (min-width: 992px) {
			#menustep {
				height: 100vh;
			}
		}


		@media only screen and (max-width: 520px) {
			.navbar-right {
				flex-direction: row;
				height: 70px;
				width: 100%;
			}
		}



		* {
			-webkit-user-select: none;
			-moz-user-select: none;
			-ms-user-select: none;
			user-select: none;
		}

		.hide-scrollbar::-webkit-scrollbar {
			display: none;
		}

		/* Hide scrollbar for IE, Edge and Firefox */
		.hide-scrollbar {
			-ms-overflow-style: none;
			/* IE and Edge */
			scrollbar-width: none;
			/* Firefox */
		}

		.navbar-nav .open .dropdown-menu.setWidth {
			width: 350px;
			height: 88vh;
			overflow: hidden;
			border-radius: 0px 0px 10px 10px;
		}

		.navbar-nav .open .dropdown-menu.setWidth .parent-notification {
			overflow: auto;
			max-height: 100vh;
			margin-bottom: 100px;
		}

		.navbar-nav .open .dropdown-menu.setWidth .parent-notification::-webkit-scrollbar-track {
			-webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
			box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
			border-radius: 10px;
			background-color: #d1d5db;
		}

		.navbar-nav .open .dropdown-menu.setWidth .parent-notification::-webkit-scrollbar {
			width: 7px;
			background-color: #d1d5db;
		}

		.navbar-nav .open .dropdown-menu.setWidth .parent-notification::-webkit-scrollbar-thumb {
			border-radius: 10px;
			-webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
			box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
			background-color: #555;
		}

		.notification {
			padding-left: 15px;
			padding-right: 15px;
			padding-top: 5px;
			padding-bottom: 5px;
			display: flex;
			justify-content: space-between;
			align-items: center;
		}

		.notification h3 {

			font-weight: 600;
		}

		.notification #navbarDropdown2 {
			padding: 2px 4px 0px 4px;
			border-radius: 50%;

		}

		.notification #navbarDropdown2:hover {
			background-color: #e2e8f0 !important;
			opacity: 0.5;
		}

		.sub-notification-tab {
			margin-top: 10px;
			margin-bottom: 10px;
		}

		.tabs-depo::-webkit-scrollbar-track {
			-webkit-box-shadow: inset 0 0 1px rgba(0, 0, 0, 0.3);
			box-shadow: inset 0 0 1px rgba(0, 0, 0, 0.3);
			border-radius: 10px;
			background-color: #d1d5db;
		}

		.tabs-depo::-webkit-scrollbar {
			width: 7px;
			height: 4px;
			background-color: #d1d5db;
		}

		.tabs-depo::-webkit-scrollbar-thumb {
			border-radius: 10px;
			-webkit-box-shadow: inset 0 0 1px rgba(0, 0, 0, 0.3);
			box-shadow: inset 0 0 1px rgba(0, 0, 0, 0.3);
			background-color: #555;
		}

		.tabs-depo {
			list-style: none;
			display: flex;
			justify-content: flex-start;
			overflow: auto;
			padding: 0 0 10px 0;
		}

		.tabs-depo:after {
			content: "";
			display: table;
			clear: both;
		}

		.tabs-depo input[type=radio] {
			display: none;
		}

		.tabs-depo label {
			display: inline-block;
			min-width: 30%;
			color: #1e293b;
			text-align: center;
			padding: 4px;
			border: 1px solid #2A3F54;
			border-radius: 10px 10px;
			margin: 0 5px;
			position: relative;
			cursor: pointer;
			-webkit-transition: all 0.3s;
			transition: all 0.3s;
		}

		.tabs-depo label .count-tabs {
			border: 1px solid #E74C3C;
			position: absolute;
			top: 0;
			bottom: 0;
			right: 0;
			margin-right: -3px;
			background-color: #E74C3C;
			border-radius: 50%;
			display: inline-table;
		}

		.tabs-depo label .count-tabs span {
			color: white;
			font-size: 9px;
			padding: 5px;

		}

		.tabs-depo label span {
			/* display: none; */
			font-size: 1.2rem;
		}

		.tabs-depo label:hover {
			color: white;
			border: 1px solid #2A3F54;
			background-color: #2A3F54;
			opacity: 0.3;
		}


		.tabs-depo [id^=tab]:checked+label {
			background: #2A3F54;
			color: white;
		}

		.sub-notification-read {
			display: flex;
			flex-direction: row;
			padding-left: 15px;
			padding-right: 15px;
			font-size: 15px;
			width: 100%;
			position: relative;
		}

		.sub-notification-read .notification-input {
			display: none;
		}

		.sub-notification-read .notification-input+div label {
			display: block;
			padding: 8px;
			text-align: center;
			transition: all 0.15s ease-in-out;
			background: #fff;
			border-radius: 10px;
			box-sizing: border-box;
			font-size: 12px;
			cursor: pointer;
		}

		.sub-notification-read .notification-input+div label:first-child {
			float: left;
			margin-right: 10px;
			background-color: #a5f3fc;
			color: #06b6d4;
			/* box-shadow: inset 0 0 0 4px #1597ff, 0 10px 10px -5px rgba(0, 125, 225, 0.375); */
		}

		.sub-notification-read .notification-input+div label:hover {
			background-color: #e2e8f0 !important;
			opacity: 0.5;
			color: #1e293b;
			/* box-shadow: inset 0 0 0 4px #1597ff, 0 10px 10px -5px rgba(0, 125, 225, 0.375); */
		}

		.sub-notification-read .notification-input+div label:last-child {
			float: right;
		}

		.sub-notification-read .notification-input#fat:checked~div label:first-child {
			/* box-shadow: inset 0 0 0 4px #1597ff, 0 10px 10px -5px rgba(0, 125, 225, 0.375); */
			background-color: #a5f3fc;
			color: #06b6d4;
		}

		.sub-notification-read .notification-input#fat:checked~div label:last-child {
			/* box-shadow: inset 0 0 0 0px #1597ff, 0 5px 10px -15px rgba(21, 151, 255, 0); */
			background-color: transparent;
			color: #1e293b;
		}

		.sub-notification-read .notification-input#fit:checked~div label:first-child {
			/* box-shadow: inset 0 0 0 0px #1597ff, 0 5px 10px -15px rgba(21, 151, 255, 0); */
			background-color: transparent;
			color: #1e293b;
		}

		.sub-notification-read .notification-input#fit:checked~div label:last-child {
			/* box-shadow: inset 0 0 0 4px #1597ff, 0 10px 10px -5px rgba(0, 125, 225, 0.375); */
			background-color: #a5f3fc;
			color: #06b6d4;
		}

		.sub-notification-read .show-all {
			position: absolute;
			right: 15px;
			color: #0ea5e9;
			cursor: pointer;
		}

		.sub-notification-read .show-all h6:hover {
			color: #1e293b;
		}

		.menu-lainnya {
			padding: 10px;
		}

		.tandai-semua {
			display: flex;
			padding: 10px;
			flex-direction: row;
			align-items: center;
			justify-self: center;
			cursor: pointer;
			border-radius: 10px 10px;
		}

		.tandai-semua:hover {
			background-color: #e2e8f0 !important;

		}

		.tandai-semua i {
			font-size: 15px;
		}

		.tandai-semua p {
			margin: auto;
			margin-left: 10px;
			font-size: 13px;
		}

		.parent-list-notification-item {
			padding: 2px 10px 2px 10px;
		}

		[class~='parent-list-notification-item']:last-of-type {
			margin-bottom: 80px;
		}

		.list-notification-item {
			display: flex;
			border-bottom: 1px solid #eee;
			margin-bottom: 0px;
			cursor: pointer;
			padding: 6px 9px;
			border-radius: 10px 10px;
		}

		.list-notification-item:hover {
			background-color: #eee
		}

		.list-notification-item img {
			display: block;
			width: 50px;
			height: 50px;
			margin-right: 9px;
			border-radius: 50%;
			margin-top: 2px;
			pointer-events: none;
		}



		.list-notification-item .text h4 {
			color: #0f172a;
			font-size: 15px;
			font-weight: 700;
			margin-top: 3px;
			display: inline;
		}

		.list-notification-item .text p {
			color: #475569;
			font-size: 12px;
			display: inline;
		}

		.list-notification-item .not-read i {
			color: #0ea5e9;
		}


		.parent-loading {
			padding: 2px 10px 2px 10px;
		}

		[class~='parent-loading']:last-of-type {
			margin-bottom: 80px;
		}

		.sub-loading {
			display: flex;
			position: relative;
			overflow: hidden;
			border-bottom: 1px solid #eee;
			margin-bottom: 0px;
			padding: 6px 9px;
			border-radius: 10px 10px;
		}

		.sub-loading div:first-of-type {
			display: inline-block;
			width: 70px;
			height: 60px;
			margin-right: 9px;
			border-radius: 50%;
			margin-top: 2px;
			pointer-events: none;
			background-color: #cbd5e1;
		}

		.sub-loading div:last-of-type {
			margin: auto;
			padding: 10px;
			background-color: #cbd5e1;
			height: 15px;
			border-radius: 10px 10px;
			width: 100%;
		}

		.sub-loading::after {
			position: absolute;
			top: 0;
			right: 0;
			bottom: 0;
			left: 0;
			transform: translateX(-100%);
			background-image: linear-gradient(90deg, rgba(255, 255, 255, 0) 0, rgba(255, 255, 255, 0.2) 20%, rgba(255, 255, 255, 0.5) 60%, rgba(255, 255, 255, 0));
			-webkit-animation: shimmer 3s infinite;
			animation: shimmer 3s infinite;
			content: "";
		}

		@-webkit-keyframes shimmer {
			100% {
				transform: translateX(100%);
			}
		}

		@keyframes shimmer {
			100% {
				transform: translateX(100%);
			}
		}

		.notification-not-found {
			text-align: center;
			padding: 15px;
		}

		.notification-not-found i {
			margin-top: 20px;
			font-size: 80px;
		}

		.notification-not-found h4 {
			margin-top: 20px;
		}
	</style>


	<?php if (isset($css_files)) : ?>

		<?php foreach ($css_files as $file) : ?>
			<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
		<?php endforeach; ?>
	<?php endif; ?>

	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CQuicksand:300,400,500,700" rel="stylesheet">
	<link href="https://cdn.datatables.net/fixedcolumns/3.2.4/css/fixedColumns.bootstrap4.min.css" rel="stylesheet" />
</head>

<body class="nav-md" style="font-family: 'Quicksand', Georgia, 'Times New Roman', Times, serif;">
	<div class="container body">
		<div class="main_container">

			<div class="col-md-3 left_col">
				<!-- menu_fixed mCustomScrollbar -->
				<div class="left_col scroll-view">


					<!-- menu profile quick info -->

					<div class="navbar nav_title" style="border: 0; height:0; min-height:0">

					</div>

					<div class="clearfix"></div>
					<div class="profile clearfix">
						<div class="profile_pic">

						</div>
						<div class="profile_info">
							<span name="CAPTION-SELAMATDATANG">Selamat Datang </span>
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

					<div class="nav toggle" style="width: 30%;">
						<a id="menu_toggle"><i class="fa fa-bars"></i></a>
						<i style="cursor: pointer;" onclick="ShowLanguage()">&nbsp;<label style="font-size: 14pt;"><img src="<?= Get_Assets_Url(); ?>assets/images/flag/<?= $_SESSION['Bahasa']; ?>.png" style="width: 26px; height: 26px;" />&nbsp;<?= $_SESSION['Bahasa']; ?></label></i>
						<button type="button" style="border: none; background-color: transparent;" id="btndarkmode" onclick="ToggleMode()"><i style="font-size: 14pt; color: #eab308;" id="dark" class="fa fa-sun"></i></button>
						<?php

						$last_tbg = "";
						$str = "";
						if ($this->uri->segment(3) == 'MainDepoMenu') {
							$str = '';
						} else {
							$last_tbg_query = $this->db->query("select FORMAT(DATEADD(DAY, 1, ISNULL(depo_last_tbg, DATEADD(DAY, -1, GETDATE()))), 'dd-MM-yyyy') as next_tbg from depo where depo_id = '" . $this->session->userdata('depo_id') . "'");

							if ($last_tbg_query->num_rows() == 0) {
								$last_tbg = "";
							} else {
								$last_tbg = $last_tbg_query->row(0)->next_tbg;

								$str = '<label style="color: black;">System Date : ' . $last_tbg . '</label>';
							}
						}


						?>

						<!-- <label style="color: black;">System Date : <?= $last_tbg; ?></label> -->
						<?= $str ?>
					</div>
					<ul class="nav navbar-nav navbar-right" style="width: 70%;">

						<li class="">

							<a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
								<img style="pointer-events: none;" src="<?= Get_Assets_Url(); ?>assets/images/karyawan/<?= $this->session->userdata('karyawan_foto'); ?>" />
								<label style="color: black;"><?= $this->session->userdata('pengguna_username');	?></label>
								<span class="fa fa-angle-down"></span>
							</a>
							<ul class="dropdown-menu dropdown-usermenu pull-right">
								<li><a href="<?= $_SESSION['backend_url'] . 'HakAkses/ProfilKaryawan/ProfilKaryawanMenu'; ?>" name="CAPTION-PROFILKARYAWAN"><label style="color: black;">Profil Karyawan</label></a></li>
								<li>
									<hr>
								</li>
								<li><a href="<?= $_SESSION['backend_url'] . 'MainPage/Logout'; ?>"><i class="fa fa-sign-out pull-right"></i><label name="CAPTION-LOGOUT">Log Out</label></a></li>
							</ul>
						</li>

						<?php if ($this->session->userdata('Mode') != '0') { ?>

							<?php

							$countNotifIsNotRead = $this->db->query("SELECT * FROM notification WHERE notification_is_read = 0 AND convert(nvarchar(36), notification_to_who_id) = '" . $this->session->userdata('karyawan_id') . "'")->num_rows();

							$dataNotif = $this->db->query("SELECT who.karyawan_nama as from_who, who.karyawan_foto, too.karyawan_nama, nf.*
															 FROM notification nf 
															 LEFT JOIN karyawan who ON nf.notification_from_who_id = who.karyawan_id
															 LEFT JOIN karyawan too ON nf.notification_to_who_id = too.karyawan_id
															 WHERE convert(nvarchar(36), nf.notification_to_who_id) = '" . $this->session->userdata('karyawan_id') . "'
															 			AND nf.notification_tgl >= dateadd(day, -60, getdate())
															 ORDER BY nf.notification_tgl DESC
																")->result();

							$menuDepo = $this->db->query("SELECT distinct g.depo_id, g.depo_nama, count(n.depo_id) as total
																						from depo as g
																						inner join pengguna_depo as pg on pg.depo_id = g.depo_id
																						left join notification n on n.depo_id = g.depo_id AND n.notification_is_read = 0 AND convert(nvarchar(36), n.notification_to_who_id) = '" . $this->session->userdata('karyawan_id') . "'
																						where convert(nvarchar(36), pg.pengguna_id) = '" . $this->session->userdata('pengguna_id') . "' 
																						group by g.depo_id, g.depo_nama")->result();

							?>

							<li role="presentation" class="nav-item dropdown notificationsss">
								<a href="javascript:;" class="dropdown-toggle info-number" id="navbarDropdown1">
									<i class="fa-solid fa-bell" style="color: black"></i>
									<span class="badge bg-red <?= $countNotifIsNotRead > 0 ? "pulse" : "" ?> setCountNotif"><?= $countNotifIsNotRead ?></span>
								</a>

								<ul class="dropdown-menu list-unstyled msg_list setWidth" role="menu" aria-labelledby="navbarDropdown1">
									<div class="parent-notification">
										<div class="notification">
											<h3 name="CAPTION-NOTIFIKASI">Notifikasi</h3>
											<div role="presentation" class="dropdown notificationssss">
												<a href="javascript:;" class="dropdown-toggle info-number" id="navbarDropdown2">
													<i class="fa-solid fa-ellipsis"></i>
												</a>
												<ul class="dropdown-menu list-unstyled msg_list" role="menu" aria-labelledby="navbarDropdown2">
													<div class="menu-lainnya">
														<div class="tandai-semua" onclick="handlerReadAllNotification()">
															<i class="fas fa-check"></i>
															<p><span name="CAPTION-BACASEMUA">Tandai semua sebagai telah dibaca</span></p>
														</div>
													</div>

												</ul>
											</div>
										</div>
										<div class="sub-notification-tab">
											<div class="tabs-depo">
												<?php foreach ($menuDepo as $key => $menu) { ?>
													<?php if ($this->session->userdata('depo_id') == $menu->depo_id) { ?>
														<input type="radio" name="tabs" id="tab<?= $key ?>" value="<?= $menu->depo_id ?>" onchange="handlerGetDataByDepo(this.value)" checked><label for="tab<?= $key ?>">
														<?php } else { ?>
															<input type="radio" name="tabs" id="tab<?= $key ?>" value="<?= $menu->depo_id ?>" onchange="handlerGetDataByDepo(this.value)"><label for="tab<?= $key ?>">
															<?php } ?>
															<?php if ($menu->total != 0) { ?>
																<div class="count-tabs"><span><?= $menu->total ?></span></div>
															<?php } ?>
															<span><?= $menu->depo_nama ?></span>
															</label>
														<?php } ?>
											</div>
										</div>
										<div class="sub-notification-read">
											<input type="radio" id="fat" name="fatfit" class="notification-input" value="semua" onchange="handlerGetDataByRead('semua', event)">
											<input type="radio" id="fit" name="fatfit" class="notification-input" value="belum_dibaca" onchange="handlerGetDataByRead('belum_dibaca', event)">
											<div>
												<label for="fat" name="CAPTION-SEMUA">Semua</label>
												<label for="fit" name="CAPTION-BELUMDIBACA">Belum Dibaca</label>
											</div>

											<!-- <div class="show-all">
												<h6 name="CAPTION-LIHATSEMUA">Lihat Semua</h6>
											</div> -->
										</div>
										<div id="initDataNotification">
											<?php if (!empty($dataNotif)) { ?>
												<?php foreach ($dataNotif as $data) { ?>

													<div class="parent-list-notification-item">
														<div class="list-notification-item" onclick="handlerTestNotif('<?= $data->notification_id ?>', '<?= $data->notification_data_id ?>', '<?= $data->notification_judul ?>', '<?= $data->notification_keterangan ?>', '<?= $data->karyawan_nama ?>', '<?= $data->notification_to_modul ?>')">
															<img src="<?= $this->session->userdata('backend_url') . 'assets/images/karyawan/' . $data->karyawan_foto ?>">
															<div class="text">
																<h4><?= $data->from_who ?></h4>
																<p><?= $data->notification_keterangan ?> <?= $data->karyawan_nama ?></p>
																<p style="display: block;color: <?= $data->notification_is_read == 0 ? '#0ea5e9' : '#475569' ?>"><?= time_elapsed_string($data->notification_tgl) ?></p>
															</div>

															<?php if ($data->notification_is_read == 0) { ?>
																<div class="not-read" style="margin:auto">
																	<i class="fa-solid fa-circle"></i>
																</div>
															<?php } ?>

														</div>
													</div>

												<?php } ?>
											<?php } else { ?>
												<div class="notification-not-found">
													<i class="fa-solid fa-bell-slash"></i>
													<h4 class="text-danger" name="CAPTION-TIDAKADANOTIFIKASI">Anda tidak memiliki notifikasi</h4>
												</div>
											<?php } ?>
										</div>
									</div>
								</ul>
							</li>
						<?php } ?>

						<?php
						if ($this->session->userdata('depo_nama') != '0') {
						?>
							<li class="">
								<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
									<label style="color: black;"><?= $this->session->userdata('depo_nama'); ?></label>
								</a>
								<ul class="dropdown-menu dropdown-usermenu pull-right">
									<li><a href="<?= base_url('Main/MainDepo/MainDepoMenu') ?>" name="CAPTION-PILIHUNIT">Pilih Unit</a></li>

								</ul>
							</li>
						<?php
						}

						if ($this->session->userdata('Mode') != '0') {
						?>
							<li class="">
								<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
									<label style="color: black;"><?= $this->session->userdata('ModeKet'); ?></label>
								</a>
								<ul class="dropdown-menu dropdown-usermenu pull-right">
									<li><a href="<?= $_SESSION['backend_url'] . 'Main/MainAplikasi/BackToAplikasiMenu' ?>" name="CAPTION-PILIHAPLIKASI">Pilih Aplikasi</a></li>
								</ul>
							</li>
						<?php
						}
						?>


					</ul>


				</div>
			</div>

			<div class="modal fade" id="previewshowlanguage" role="dialog" data-keyboard="false" data-backdrop="static">
				<div class="modal-dialog modal-sm">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
							<h4 class="modal-title" name="CAPTION-PILIHBAHASA">Pilih Bahasa</h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<table width="100%" id="tbFlag">

								</table>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success" data-dismiss="modal" id="btnyespilihbahasa"><label name="CAPTION-YESPILIH">Iya</label></button>
							<button type="button" class="btn btn-danger" data-dismiss="modal" id="btnnopilihbahasa"><label name="CAPTION-NOPILIH">Tidak</label></button>
						</div>
					</div>
				</div>
			</div>

			<div class="right_col" role="main" id="menudiagram" style="display: none;">
				<div class="">
					<div class="page-title">
						<div class="title_left">
							<h3 id="diagramtitle"></h3>
						</div>


					</div>

					<div class="clearfix"></div>

					<div id="modalnode"></div>

					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
							<div>
								<div class="page-title">
									<div class="title_left">
										<h3 id="menuname"></h3>
									</div>
								</div>
								<div class="clearfix"></div>
								<div id="DivPaletteView" style="display: none; width: 105px; margin-right: 2px; background-color: white; border: 1px solid black; position: relative; -webkit-tap-highlight-color: rgba(255, 255, 255, 0); cursor: auto;">
									<canvas tabindex="0" width="103" height="618" style="margin: 0; padding: 0; position: absolute; top: 0px; left: 0px; z-index: 2; user-select: none; touch-action: none; width: 103px; height: 618px; cursor: auto;">This text is displayed if your browser does not support the Canvas HTML element.</canvas>
									<div style="position: absolute; overflow: auto; width: 103px; height: 618px; z-index: 1;">
										<div style="position: absolute; width: 1px; height: 1px;"></div>
									</div>
								</div>
								<div id="DivDiagramView" style="flex-grow: 1; height: calc(100vh - 200px); position: relative; -webkit-tap-highlight-color: rgba(255, 255, 255, 0); cursor: auto; font: bold 11pt Helvetica, Arial, sans-serif;">
									<canvas tabindex="0" width="1011" height="618" style="position: absolute; top: 0px; left: 0px; z-index: 2; user-select: none; touch-action: none; width: 1011px; height: 618px; cursor: auto;">This text is displayed if your browser does not support the Canvas HTML element.</canvas>
									<div style="position: absolute; overflow: auto; width: 1011px; height: 618px; z-index: 1;">
										<div style="position: absolute; width: 1px; height: 1px;"></div>
									</div>
								</div>
								<div style="display: none;">
									<textarea id="txaSavedModelView" style="width:100%;height:300px; display: none;"></textarea>
									<textarea id="txaSavedPaletteModelView" style="width:100%;height:300px; display: none;"></textarea>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- top navigation -->