<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script type="text/javascript">
	var stack_center = {
		"dir1": "down",
		"dir2": "right",
		"firstpos1": 25,
		"firstpos2": ($(window).width() / 2) - (Number(PNotify.prototype.options.width.replace(/\D/g, '')) / 2)
	};

	// const messageNotAllowedShowScript = () => {
	// 	return message('Info!', 'You not access this action', 'info');
	// }

	// document.addEventListener('contextmenu', function(e) {
	// 	e.preventDefault();
	// 	messageNotAllowedShowScript();
	// });

	// document.onkeydown = (e) => {

	// 	if (e.key == 123) {
	// 		e.preventDefault();
	// 		messageNotAllowedShowScript();
	// 	}
	// 	if (e.ctrlKey && e.shiftKey && e.key == 'I') {
	// 		e.preventDefault();
	// 		messageNotAllowedShowScript();
	// 	}
	// 	if (e.ctrlKey && e.shiftKey && e.key == 'C') {
	// 		e.preventDefault();
	// 		messageNotAllowedShowScript();
	// 	}
	// 	if (e.ctrlKey && e.shiftKey && e.key == 'J') {
	// 		e.preventDefault();
	// 		messageNotAllowedShowScript();
	// 	}
	// 	if (e.ctrlKey && e.key == 'U') {
	// 		e.preventDefault();
	// 		messageNotAllowedShowScript();
	// 	}
	// 	if (e.key == 'F12') {
	// 		e.preventDefault();
	// 		messageNotAllowedShowScript();
	// 	}
	// };

	window.location.back = '';

	$(document).ready(function() {
		window.history.pushState(null, "", window.location.href);

		window.onpopstate = function() {
			window.history.pushState(null, "", window.location.href);
		};

		var iserror = '<?php if (isset($_SESSION['temp_error'])) {
							echo $_SESSION['temp_error'];
						} ?>';

		if (iserror != '') {
			ShowPNotifyMessage('error', iserror, 'Error');

		}

		if ('<?= $_SESSION['depo_id'] ?>' != 0) {
			handlerGetDataByDepo("<?= $_SESSION['depo_id'] ?>");
		}

		Translate();
	});

	$(window).resize(function() {
		stack_center.firstpos2 = ($(window).width() / 2) - (Number(PNotify.prototype.options.width.replace(/\D/g, '')) / 2);
	});

	function hexToRGB(h) {
		let r = 0,
			g = 0,
			b = 0;

		// 3 digits
		if (h.length == 4) {
			r = "0x" + h[1] + h[1];
			g = "0x" + h[2] + h[2];
			b = "0x" + h[3] + h[3];

			// 6 digits
		} else if (h.length == 7) {
			r = "0x" + h[1] + h[2];
			g = "0x" + h[3] + h[4];
			b = "0x" + h[5] + h[6];
		}

		var arrColor = [];

		r = +r;
		g = +g;
		b = +b;

		arrColor.push({
			r,
			g,
			b
		});

		return arrColor;

		//return "rgb("+ +r + "," + +g + "," + +b + ")";
	}

	function RGBToHex(c) {
		var hex = c.toString(16);
		return hex.length == 1 ? "0" + hex : hex;
	}

	function ShowPNotifyMessage(tipe, Message, Tt) {
		var msg = Message;
		var msgtype = tipe;

		//if (!window.__cfRLUnblockHandlers) return false;
		new PNotify
			({
				title: Tt,
				text: msg,
				type: msgtype,
				styling: 'bootstrap3',
				delay: 3000,
				stack: stack_center
			});
	}

	function ShowMessage(tipe, kode, iserrorfromdb, additionalinfo = null) {
		if (iserrorfromdb == 0) {
			var msg = GetLanguageByKode(kode);
		} else if (iserrorfromdb == 1) {
			var msg = kode;
		} else {
			var msg = 'Access Violation';
			var tipe = 'error';
		}

		if (additionalinfo == 'null') {
			var msgtype = tipe + additionalinfo;
		} else {
			var msgtype = tipe;
		}

		var judul = 'Info';

		//if (!window.__cfRLUnblockHandlers) return false;
		new PNotify
			({
				title: judul,
				text: msg,
				type: msgtype,
				styling: 'bootstrap3',
				delay: 3000,
				stack: stack_center
			});
	}

	async function Translate() {
		<?php
		$Bahasa = json_encode($_SESSION['BahasaList']);
		?>

		var Bhs = await JSON.parse('<?= $Bahasa; ?>');

		for (i = 0; i < Bhs.length; i++) {
			var bahasa_id = Bhs[i].bahasa_id;
			var bahasa_kode = Bhs[i].bahasa_kode;
			var bahasa_nama = Bhs[i].bahasa_nama;
			var bahasa = Bhs[i].bahasa;

			$("label[name=" + bahasa_kode + "]").html(bahasa);
			$("span[name=" + bahasa_kode + "]").html(bahasa);
			$("td[name=" + bahasa_kode + "]").html(bahasa);
			$("th[name=" + bahasa_kode + "]").html(bahasa);
			$("h1[name=" + bahasa_kode + "]").html(bahasa);
			$("h2[name=" + bahasa_kode + "]").html(bahasa);
			$("h3[name=" + bahasa_kode + "]").html(bahasa);
			$("h4[name=" + bahasa_kode + "]").html(bahasa);
			$("h5[name=" + bahasa_kode + "]").html(bahasa);
			$("h6[name=" + bahasa_kode + "]").html(bahasa);
			$("h7[name=" + bahasa_kode + "]").html(bahasa);
			$("a[name=" + bahasa_kode + "]").html(bahasa);
		}
	}


	function ShowLanguage() {
		$.ajax({
			type: 'POST',
			url: "<?= base_url('Global/Language/GetLanguage') ?>",
			success: function(response) {
				ChShowLanguageMenu(response);
			}
		});
	}

	function ChShowLanguageMenu(JSONLanguage) {
		var Language = JSON.parse(JSONLanguage);

		$("#tbFlag").html('');
		for (i = 0; i < Language.LanguageMenu.length; i++) {
			var flag_kode = Language.LanguageMenu[i].flag_kode;
			var flag_nama = Language.LanguageMenu[i].flag_nama;

			var ispilihflag = '<?= $_SESSION['Bahasa']; ?>';

			var ischecked = '';
			if (ispilihflag == flag_kode) {
				ischecked = 'checked="checked"';
			}

			var str = '';

			str += '<tr>';
			str += '	<td width="10%"><input type="radio" name="rbpilihbahasa" class="checkbox_form chpilihbahasa" data-bahasa="' + flag_kode + '" ' + ischecked + ' /></td>';
			str += '	<td width="20%"><img src="<?= base_url('assets/images/Flag'); ?>/' + flag_kode + '.png" class="img-responsive" /></td>';
			str += '	<td width="70%"><strong>' + flag_nama + '</strong></td>';
			str += '</tr>';

			$("#tbFlag").append(str);
		}

		$("#previewshowlanguage").modal('show');
	}

	$("#btnyespilihbahasa").click(
		function() {
			var lbahasa = $(".chpilihbahasa").length;

			var flag_kode = '';

			for (i = 0; i < lbahasa; i++) {
				if ($(".chpilihbahasa").eq(i).prop('checked') == true) {
					flag_kode = $(".chpilihbahasa").eq(i).attr('data-bahasa');
				}
			}

			localStorage.setItem("defaultlanguage", flag_kode);

			window.location = '<?= base_url('Global/Language/SelectLanguage'); ?>/' + flag_kode;
		}
	);

	function GetLanguageByKode(bahasa_kode) {
		var BahasaList = JSON.parse('<?= $Bahasa; ?>');

		var obj = BahasaList.find(o => o.bahasa_kode === bahasa_kode);

		return obj.bahasa;
	}

	/*$('input').on('input', function()
	{
		var c = this.selectionStart,
			r = /[^a-z0-9 ]/gi,
			v = $(this).val();
		if(r.test(v)) 
		{
			$(this).val(v.replace(r, ''));
		c--;
	  }
	  this.setSelectionRange(c, c);
	});*/



	const theme = document.querySelector('body');

	let darkMode = localStorage.getItem("dark-mode");

	const enableDarkMode = () => {
		$("*").addClass("dark-mode");

		$("button").removeClass('dark-mode');
		$("button i").removeClass('dark-mode');
		$("button label").removeClass('dark-mode');
		$("input").removeClass('dark-mode');
		$("select").removeClass('dark-mode');
		$(".select2").removeClass('dark-mode');
		$(".select2-selection").removeClass('dark-mode');
		$(".select2-selection__rendered").removeClass('dark-mode');
		$(".select2-selection__arrow").removeClass('dark-mode');
		$(".select2-selection__arrow b").removeClass('dark-mode');
		$(".select2-results__options").removeClass('dark-mode');
		$(".select2-results__option").removeClass('dark-mode');
		$("textarea").removeClass('dark-mode');
		$("td div").removeClass('dark-mode');
		$("td div strong").removeClass('dark-mode');
		$("header").removeClass('dark-mode');
		$("header div").removeClass('dark-mode');
		$("header div label").removeClass('dark-mode');
		$(".panel").removeClass('dark-mode');
		$(".panel-body").removeClass('dark-mode');
		$(".panelimg").removeClass('dark-mode');
		$(".panelimg img").removeClass('dark-mode');
		$(".tab-pane").removeClass('dark-mode');
		$(".tab-pane div").removeClass('dark-mode');
		$(".treeitem").removeClass('dark-mode');
		$("#btndarkmode").html('<i style="font-size: 14pt; color: white;" id="dark" class="fa fa-moon"></i>');

		$(".jtoolbar-item").attr('style', 'color: white;');
		//toggleBtn.classList.remove("dark-mode");
		localStorage.setItem("dark-mode", "enabled");
	};

	const disableDarkMode = () => {
		$("*").removeClass("dark-mode");

		$("button").removeClass('dark-mode');
		$("button i").removeClass('dark-mode');
		$("button label").removeClass('dark-mode');
		$("input").removeClass('dark-mode');
		$("select").removeClass('dark-mode');
		$(".select2").removeClass('dark-mode');
		$(".select2-selection").removeClass('dark-mode');
		$(".select2-selection__rendered").removeClass('dark-mode');
		$(".select2-selection__arrow").removeClass('dark-mode');
		$(".select2-selection__arrow b").removeClass('dark-mode');
		$(".select2-results__options").removeClass('dark-mode');
		$(".select2-results__option").removeClass('dark-mode');
		$("textarea").removeClass('dark-mode');
		$("td div").removeClass('dark-mode');
		$("td div strong").removeClass('dark-mode');
		$("header").removeClass('dark-mode');
		$("header div").removeClass('dark-mode');
		$("header div label").removeClass('dark-mode');
		$(".panel").removeClass('dark-mode');
		$(".panel-body").removeClass('dark-mode');
		$(".panelimg").removeClass('dark-mode');
		$(".panelimg img").removeClass('dark-mode');
		$(".tab-pane").removeClass('dark-mode');
		$(".tab-pane div").removeClass('dark-mode');
		$(".treeitem").removeClass('dark-mode');

		$("#btndarkmode").html('<i style="font-size: 14pt; color: #eab308;" id="dark" class="fa fa-sun"></i>');

		//toggleBtn.classList.add("dark-mode");
		localStorage.setItem("dark-mode", "disabled");
	};

	if (darkMode === "enabled") {
		enableDarkMode(); // set state of darkMode on page load
	}

	function ToggleMode(event) {
		darkMode = localStorage.getItem("dark-mode"); // update darkMode when clicked
		if (darkMode === "disabled") {
			enableDarkMode();
		} else {
			disableDarkMode();
		}

		//var list = document.querySelectorAll(".nav-md , .container.body, .right_col");
		//console.log( list );
		//list.classList.add("dark-mode");
		/*$("div").toggleClass("dark-mode");
		$("footer").toggleClass("dark-mode");
		$("strong").toggleClass("dark-mode");
		$("li").toggleClass("dark-mode");
		$("a").toggleClass("dark-mode");*/
	}

	/*toggleBtn.addEventListener("click", (e) => {
	  darkMode = localStorage.getItem("dark-mode"); // update darkMode when clicked
	  if (darkMode === "disabled") {
		enableDarkMode();
	  } else {
		disableDarkMode();
	  }
	});*/

	// Enable pusher logging - don't include this in production
	Pusher.logToConsole = true;

	var pusher = new Pusher('<?= checkProductionApp()->PUSHER_KEY ?>', {
		cluster: '<?= getVrblPusher()->PUSHER_cluster ?>'
	});

	var channel = pusher.subscribe('my-channel');
	channel.bind('my-event', function(data) {
		$.ajax({
			method: "POST",
			url: "<?= base_url('Global/Notification/getNotification') ?>",
			dataType: "html",
			success: function(response) {
				$('.notificationsss').html(response);
				Translate();
			}
		});
	});

	async function postDataNotif(url = '', data = {}, type) {
		// Default options are marked with *
		const response = await fetch(url, {
			method: type,
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify(data)
		});
		return response.json();
	}

	const messageError = (msg, msgtext, msgtype) => {
		Swal.fire(msg, msgtext, msgtype);
	}

	const handlerTestNotif = (notifId, notifDataId, notifJudul, notifKeterangan, notifTo, notifToModul) => {


		if ("<?= $_SESSION['depo_id'] ?>" == 0) {
			$('.notificationsss').removeClass('open');
			$('#navbarDropdown1').attr('aria-expanded', false);
			messageError('Error!', "Harap pilih depo terlebih dahulu!", "error")
			return false
		} else {
			//update tgl read is 1 and getdate()
			postDataNotif('<?= base_url('Global/Notification/updateNotificationRead') ?>', {
					notifId
				}, 'POST')
				.then((response) => {

				});

			if (notifToModul == "WMS/ProsesOpname") location.href = "<?= base_url('WMS/ProsesOpname/ProsesDataOpname') ?>" + `?prosesId=${notifDataId}`;

			if (notifToModul == "WMS/PersetujuanHasilStockOpname") location.href = "<?= base_url('WMS/PersetujuanHasilStockOpname/PersetujuanHasilStockOpname') ?>" + `?id=${notifDataId}`;

			/******* Approval Dashboard  ******/
			let approvalName = "";

			if ("<?= $_SESSION['Bahasa'] ?>" == "IND") {
				if (notifJudul == "Dokumen Draft Mutasi Pallet Antar Gudang") approvalName = "Mutasi Pallet Antar Gudang";
				if (notifJudul == "Dokumen Draft Mutasi Pallet Antar Rak") approvalName = "Mutasi Pallet Antar Rak";
				if (notifJudul == "Dokumen Approval Pembongkaran / Pengemasan Barang") approvalName = "Pembongkaran / Pengemasan";
				if (notifJudul == "Dokumen Stok Opname") approvalName = "Persiapan Stok Opnam";
				if (notifJudul == "Kode Dokumen Approval Koreksi Stok") approvalName = "Koreksi Stock Masuk";
			}

			if ("<?= $_SESSION['Bahasa'] ?>" == "ENG") {
				if (notifJudul == "Dokumen Draft Mutasi Pallet Antar Gudang") approvalName = "Pallet Mutation for each Unit";
				if (notifJudul == "Dokumen Draft Mutasi Pallet Antar Rak") approvalName = "Pallet Mutation for each Rack";
				if (notifJudul == "Dokumen Approval Pembongkaran / Pengemasan Barang") approvalName = "Unpack / Repack";
				if (notifJudul == "Kode Dokumen Approval Koreksi Stok") approvalName = "Stock Correction In";
			}

			if (notifToModul == "WMS/MainDashboard") location.href = "<?= base_url('WMS/MainDashboard/MainDashboardMenu') ?>" + `?approvalName=${approvalName}`;
			/******* End Approval Dashboard  ******/
		}



	}

	function changeTimezone(date, ianatz) {

		// suppose the date is 12:00 UTC
		var invdate = new Date(date.toLocaleString('en-US', {
			timeZone: ianatz
		}));

		// then invdate will be 07:00 in Toronto
		// and the diff is 5 hours
		var diff = date.getTime() - invdate.getTime();

		// so 12:00 in Toronto is 17:00 UTC
		return new Date(date.getTime() - diff); // needs to substract

	}

	function fromNow(date, rft = new Intl.RelativeTimeFormat(("<?= $_SESSION['Bahasa']; ?>" == "ENG") ? undefined : ("<?= $_SESSION['Bahasa']; ?>" == "IND") ? 'id-ID' : '', {
		numeric: "auto"
	})) {
		const SECOND = 1000;
		const MINUTE = 60 * SECOND;
		const HOUR = 60 * MINUTE;
		const DAY = 24 * HOUR;
		const WEEK = 7 * DAY;
		const MONTH = 30 * DAY;
		const YEAR = 365 * DAY;
		const intervals = [{
				ge: YEAR,
				divisor: YEAR,
				unit: 'year'
			},
			{
				ge: MONTH,
				divisor: MONTH,
				unit: 'month'
			},
			{
				ge: WEEK,
				divisor: WEEK,
				unit: 'week'
			},
			{
				ge: DAY,
				divisor: DAY,
				unit: 'day'
			},
			{
				ge: HOUR,
				divisor: HOUR,
				unit: 'hour'
			},
			{
				ge: MINUTE,
				divisor: MINUTE,
				unit: 'minute'
			},
			{
				ge: 1 * SECOND,
				divisor: SECOND,
				unit: 'second'
			},
			{
				ge: 0,
				divisor: 1,
				text: 'baru saja'
			},
		];

		let here = new Date();
		let there = changeTimezone(here, "Asia/Jakarta");

		const now = typeof there === 'object' ? there.getTime() : new Date(there).getTime();
		const diff = now - (typeof date === 'object' ? date : new Date(date)).getTime();
		const diffAbs = Math.abs(diff);
		for (const interval of intervals) {
			if (diffAbs >= interval.ge) {
				const x = Math.round(Math.abs(diff) / interval.divisor);
				const isFuture = diff < 0;
				return interval.unit ? rft.format(isFuture ? x : -x, interval.unit) : interval.text;
			}
		}
	}

	const handlerGetDataByDepo = (depo_id) => {
		$(".notification-input").attr('data-depo', depo_id);

		postDataNotif('<?= base_url('Global/Notification/getNotificationByType') ?>', {
				type: null,
				depo_id
			}, 'POST')
			.then((response) => {
				$('.sub-notification-tab').hide();
				$('#initDataNotification').empty();
				$(".notificationssss").hide();
				$(".sub-notification-read").hide();

				$('#initDataNotification').fadeToggle(300, function() {
					$(this).hide();
					for (let index = 0; index < 5; index++) {
						$('#initDataNotification').append(`
							<div class="parent-loading">
								<div class="sub-loading">
									<div></div>
									<div></div>
								</div>
							</div>
						`)
					}
				}).fadeIn(500, function() {
					$(this).show();
					$('.sub-notification-tab').show();
					$(".notificationssss").show();
					$(".sub-notification-read").show();
					$('#initDataNotification').empty();
					if (response.length > 0) {
						$.each(response, function(i, v) {
							let read = ""
							if (v.notification_is_read == 0) {
								read = `<div class="not-read" style="margin:auto">
												<i class="fa-solid fa-circle"></i>
											</div>`
							} else {
								read = ""
							}

							let urlImage = "<?= $this->session->userdata('backend_url') . 'assets/images/karyawan/' ?>" + v.karyawan_foto;

							$("#initDataNotification").append(`
							<div class="parent-list-notification-item">
									<div class="list-notification-item" onclick="handlerTestNotif('${v.notification_id}', '${v.notification_data_id}', '${v.notification_judul}', '${v.notification_keterangan}', '${v.karyawan_nama}', '${v.notification_to_modul}')">
										<img src="${urlImage}">
										<div class="text">
											<h4>${v.from_who}</h4>
											<p>${v.notification_keterangan} ${v.karyawan_nama}</p>
											<p style="display: block;color: ${v.notification_is_read == 0 ? '#0ea5e9' : '#475569'}">${fromNow(v.notification_tgl)}</p>
										</div>
										${read}
									</div>
								</div>
								`);
						})
						Translate();
					} else {
						$('#initDataNotification').append(`
						 <div class="notification-not-found">
						    <i class="fa-solid fa-bell-slash"></i>
								<h4 class="text-danger" name="CAPTION-TIDAKADANOTIFIKASI">Anda tidak memiliki notifikasi</h4>
						 </div>`)
						Translate();
					}
				});


			});
	}

	const handlerGetDataByRead = (type, event) => {
		const depo_id = event.currentTarget.getAttribute('data-depo');
		postDataNotif('<?= base_url('Global/Notification/getNotificationByType') ?>', {
				type,
				depo_id: depo_id
			}, 'POST')
			.then((response) => {
				$('.sub-notification-tab').hide();
				$('#initDataNotification').empty();
				$(".notificationssss").hide();
				$(".sub-notification-read").hide();

				$('#initDataNotification').fadeToggle(300, function() {
					$(this).hide();
					for (let index = 0; index < 5; index++) {
						$('#initDataNotification').append(`
							<div class="parent-loading">
								<div class="sub-loading">
									<div></div>
									<div></div>
								</div>
							</div>
						`)
					}
				}).fadeIn(500, function() {
					$(this).show();
					$('.sub-notification-tab').show();
					$(".notificationssss").show();
					$(".sub-notification-read").show();
					$('#initDataNotification').empty();
					if (response.length > 0) {
						$.each(response, function(i, v) {
							let read = ""
							if (v.notification_is_read == 0) {
								read = `<div class="not-read" style="margin:auto">
												<i class="fa-solid fa-circle"></i>
											</div>`
							} else {
								read = ""
							}

							let urlImage = "<?= $this->session->userdata('backend_url') . 'assets/images/karyawan/' ?>" + v.karyawan_foto;

							$("#initDataNotification").append(`
							<div class="parent-list-notification-item">
									<div class="list-notification-item" onclick="handlerTestNotif('${v.notification_id}', '${v.notification_data_id}', '${v.notification_judul}', '${v.notification_keterangan}', '${v.karyawan_nama}', '${v.notification_to_modul}')">
										<img src="${urlImage}">
										<div class="text">
											<h4>${v.from_who}</h4>
											<p>${v.notification_keterangan} ${v.karyawan_nama}</p>
											<p style="display: block;color: ${v.notification_is_read == 0 ? '#0ea5e9' : '#475569'}">${fromNow(v.notification_tgl)}</p>
										</div>
										${read}
									</div>
								</div>
								`);
						})
						Translate();
					} else {
						$('#initDataNotification').append(`
						 <div class="notification-not-found">
						    <i class="fa-solid fa-bell-slash"></i>
								<h4 class="text-danger" name="CAPTION-TIDAKADANOTIFIKASI">Anda tidak memiliki notifikasi</h4>
						 </div>`)
						Translate();
					}
				});


			});
	}


	let down = true;
	$(document).on('click', '#navbarDropdown1', function(e) {

		let color = $(this).text();
		if (down) {
			$('.notificationsss').addClass('open');
			$(this).attr('aria-expanded', true);
			down = false;
		} else {
			$('.notificationsss').removeClass('open');
			$(this).attr('aria-expanded', false);
			down = true;
		}
	});

	let down2 = true;
	$(document).on('click', '#navbarDropdown2', function(e) {

		var color = $(this).text();
		if (down2) {

			$('.notificationssss').addClass('open');
			$(this).attr('aria-expanded', true);

			down2 = false;
		} else {

			$('.notificationssss').removeClass('open');
			$(this).attr('aria-expanded', false);

			down2 = true;

		}

	});

	const message = (msg, msgtext, msgtype) => {
		Swal.fire(msg, msgtext, msgtype);
	}

	const message_topright = (type, msg) => {
		const Toast = Swal.mixin({
			toast: true,
			position: "top-end",
			showConfirmButton: false,
			timer: 3000,
			didOpen: (toast) => {
				toast.addEventListener("mouseenter", Swal.stopTimer);
				toast.addEventListener("mouseleave", Swal.resumeTimer);
			},
		});

		Toast.fire({
			icon: type,
			title: msg,
		});
	}

	const handlerReadAllNotification = () => {
		$.ajax({
			method: "POST",
			url: "<?= base_url('Global/Notification/updateAllNotification') ?>",
			success: function(response) {
				$('.notificationssss').removeClass('open');
				$('#navbarDropdown2').attr('aria-expanded', false);
			}
		});
	}

	const messageNotSameLastUpdated = (url = "") => {

		let closeInSeconds = 5,
			displayText = "#1 Detik",
			timer;
		Swal.fire({
			icon: 'warning',
			title: displayText.replace(/#1/, closeInSeconds),
			text: 'Pengguna Lain telah memperbarui data pada dokumen ini, Tekan Ok untuk memuat ulang halaman atau akan dimuat ulang secara otomatis dalam 5 detik.',
			timer: closeInSeconds * 1000,
			allowOutsideClick: false
		}).then((result) => {
			if (result.value) {
				if (url !== "") {
					location.href = "<?= base_url() ?>" + encodeURI(url)
				} else {
					location.reload();
				}
			}
		});

		timer = setInterval(function() {
			closeInSeconds--;
			if (closeInSeconds < 0) clearInterval(timer);
			$('.swal2-title').text(displayText.replace(/#1/, closeInSeconds));
		}, 1000);

		setTimeout(() => {
			if (url !== "") {
				location.href = "<?= base_url() ?>" + encodeURI(url)
			} else {
				location.reload();
			}
		}, 5000)
	}

	const showLoading = (condition, disabledButtonAction, params = 0) => {
		disabledButtonAction !== "" ? $(disabledButtonAction).prop("disabled", condition) : '';
		Swal.fire({
			title: '<span><i class="fas fa-spinner fa-spin"></i></span> &nbsp;&nbsp;<span>Loading ...</span>',
			timerProgressBar: false,
			showConfirmButton: false,
			allowOutsideClick: false,
			timer: params
		});
	}

	const messageBoxBeforeRequest = (textMessage, textButtonConfirm, textButtonCancel) => {
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

	const postData = async (url = '', data = {}, type, callbackSuccess, disabledButtonAction = "", multipartFormdata = "") => {

		showLoading(true, disabledButtonAction)

		if (type === "GET") {
			await fetch(url).then((response) => {
				if (response.ok) return response.json();
				throw new Error('Something went wrong');
			}).then((data) => {
				showLoading(false, disabledButtonAction, 10)
				callbackSuccess(data)
			}).catch((error) => {
				showLoading(false, disabledButtonAction, 10)
				message("Error", error, 'error')
			});
		}

		if (type === "POST") {
			if (multipartFormdata === "") {
				await fetch(url, {
					method: type,
					headers: {
						'Content-Type': 'application/json'
					},
					body: JSON.stringify(data)
				}).then((response) => {
					if (response.ok) return response.json();
					throw new Error(`${response.status} ${response.statusText}`);
				}).then((data) => {
					showLoading(false, disabledButtonAction, 10)
					callbackSuccess(data)
				}).catch((error) => {
					showLoading(false, disabledButtonAction, 10)
					message("Error", error.message, 'error')
				});
			}

			if (multipartFormdata === "multipart-formdata") {
				await fetch(url, {
					method: type,
					body: data.formData
				}).then((response) => {
					if (response.ok) return response.json();
					throw new Error(`${response.status} ${response.statusText}`);
				}).then((data) => {
					showLoading(false, disabledButtonAction, 10)
					callbackSuccess(data)
				}).catch((error) => {
					showLoading(false, disabledButtonAction, 10)
					message("Error", error.message, 'error')
				});
			}

			if (multipartFormdata !== "" && multipartFormdata !== "multipart-formdata") {
				message("Error!", 'If you upload file, parameter the last must be <strong>multipart-formdata</strong>', 'error')
				return false;
			}

		}
	}

	const requestAjax = (urlRequest, dataRequet = {}, typePost, typeOutput, callbackSuccess, disabledButtonAction = "", multipartFormdata = "") => {

		showLoading(false, disabledButtonAction)

		if (typePost == "GET") {
			$.ajax({
				url: urlRequest,
				type: typePost,
				dataType: typeOutput,
				beforeSend: function() {
					showLoading(false, disabledButtonAction)
				},
				success: function(response) {
					showLoading(false, disabledButtonAction, 10)
					callbackSuccess(response)
				},
				error: function(xhr, error, status) {
					showLoading(false, disabledButtonAction, 10)
					messageError("Error!", `${status} ${error}`, 'error');
				},
			});
		}

		if (typePost == "POST") {
			if (multipartFormdata === "") {
				$.ajax({
					url: urlRequest,
					type: typePost,
					data: dataRequet,
					dataType: typeOutput,
					beforeSend: function() {
						showLoading(false, disabledButtonAction)
					},
					success: function(response) {
						showLoading(false, disabledButtonAction, 10)
						callbackSuccess(response)
					},
					error: function(xhr, error, status) {
						showLoading(false, disabledButtonAction, 10)
						messageError("Error!", `${status} ${error}`, 'error');
					},
				});
			}

			if (multipartFormdata === "multipart-formdata") {
				$.ajax({
					type: typePost,
					url: urlRequest,
					data: dataRequet.dataForm,
					contentType: false,
					processData: false,
					dataType: typeOutput,
					beforeSend: function() {
						showLoading(false, disabledButtonAction)
					},
					success: function(response) {
						showLoading(false, disabledButtonAction, 10)
						callbackSuccess(response)
					},
					error: function(xhr, error, status) {
						showLoading(false, disabledButtonAction, 10)
						messageError("Error!", `${status} ${error}`, 'error');
					},
				});
			}

			if (multipartFormdata !== "" && multipartFormdata !== "multipart-formdata") {
				message("Error!", 'If you upload file, parameter the last must be <strong>multipart-formdata</strong>', 'error')
				return false;
			}
		}
	}

	const loadingBeforeReadyPage = () => {
		showLoading(true, "")
		window.addEventListener('load', function() {
			showLoading(false, "", 10)
		});
	}

	function formatRupiahCurr(money) {
		// console.log(parseFloat(money.toString().replaceAll(".", "").replaceAll(",", ".")));
		var angka = new Intl.NumberFormat('id-ID', {
				style: 'currency',
				currency: 'IDR',
				minimumFractionDigits: 2
			} // diletakkan dalam object
		).format(parseFloat(money.toString().replaceAll(".", "").replaceAll(",", ".")));

		return angka.replaceAll("Rp ", "");
	}
</script>
<?php $this->load->view('master/Global/S_DiagramMenu'); ?>
<?php $this->load->view('master/Global/S_InitialMessage'); ?>
</body>

</html>

<?php
$_SESSION['temp_error'] = '';
?>