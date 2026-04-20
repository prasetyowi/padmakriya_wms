<script type="text/javascript">
	var OutletCode = '';
	loadingBeforeReadyPage()
	$(document).ready(
		function() {
			GetOutletMenu();
			// SetDataAwal();
			url_like();
			$('.numeric').on('input', function(event) {
				this.value = this.value.replace(/[^0-9]/g, '');
			});

			$(".select2").select2({
				width: "100%"
			});

			$(".numeric-coma").keypress(function(e) {
				if (/\d+|,+|[/b]+|-+/i.test(e.key)) {
					// alert(+ e.key)
				} else {
					// alert(+ e.key)
					return false;
				}
			});

		}
	);


	function url_like(url) {
		return base_url + url;
	}

	function GetOutletMenu() {
		$.ajax({
			type: 'POST',
			url: "<?= base_url('Outlet/GetOutletMenu') ?>",
			// data: "OutletId=" + id,
			success: function(response) {
				if (response) {
					ChOutletMenu(response);
				}
			}
		});
	}

	//var DTABLE;

	function ChOutletMenu(JSONOutlet) {

		var Outlet = JSON.parse(JSONOutlet);

		var StatusC = Outlet.AuthorityMenu[0].StatusC;
		var StatusU = Outlet.AuthorityMenu[0].StatusU;
		var StatusD = Outlet.AuthorityMenu[0].StatusD;


		if (StatusC == 0) {
			$("#btnaddnewoutlet").attr('style', 'display: none;');
		}

		$("#cboutletjenis").html('');
		$("#cbupdateoutletjenis").html('');

		AppendProvince(Outlet);

		//append dropdow kelas jalan
		AppendStreetClass(Outlet);
		AppendStreetClass2(Outlet);

		//append dropdown area
		AppendArea(Outlet);

		//append dropdown segmen 1
		AppendSegment1(Outlet);

		//check multi lokasi
		CheckMultiLocation();

		CheckMultiLocationUpdate();

		AppendTimeOperasional(Outlet);

		if (Outlet.OutletMenu != 0) {

			if ($.fn.DataTable.isDataTable('#tableoutletmenu')) {
				$('#tableoutletmenu').DataTable().destroy();
			}

			$('#tableoutletmenu tbody').empty();

			let no = 1;
			for (i = 0; i < Outlet.OutletMenu.length; i++) {
				var client_pt_id = Outlet.OutletMenu[i].client_pt_id;
				var client_pt_nama = Outlet.OutletMenu[i].client_pt_nama;
				var client_pt_alamat = Outlet.OutletMenu[i].client_pt_alamat;
				var client_pt_telepon = Outlet.OutletMenu[i].client_pt_telepon;
				var client_pt_nama_contact_person = Outlet.OutletMenu[i].client_pt_nama_contact_person;
				var client_pt_email_contact_person = Outlet.OutletMenu[i].client_pt_email_contact_person;
				var client_pt_telepon_contact_person = Outlet.OutletMenu[i].client_pt_telepon_contact_person;

				var isAktif = Outlet.OutletMenu[i].client_pt_is_aktif;

				if (isAktif == 0) {
					Status_PT = 'Non Aktif';
				} else {
					Status_PT = 'Aktif';
				}

				var strmenu = '';

				var strU = '';
				var strD = '';

				<?php if ($Menu_Access["U"] == 1) { ?>
					strU = '<a href="' + url_like("Outlet/EditData/" + client_pt_id) + '" style="width: 40px;" class="btn btn-warning btneditoutletmenu form-control" data-id="' + client_pt_id + '"><i class="fa fa-pencil"></i></a>';
				<?php }
				if ($Menu_Access["D"] == 1) { ?>
					strD = '<button style="width: 40px;" class="btn btn-danger btndeleteoutletmenu form-control" onclick="DeleteOutletMenu(\'' + client_pt_id + '\')"><i class="fa fa-times"></i></button>';
				<?php }  ?>

				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td>' + no++ + '</td>';
				strmenu = strmenu + '	<td>' + client_pt_nama + '</td>';
				strmenu = strmenu + '	<td>' + client_pt_alamat + '</td>';
				strmenu = strmenu + '	<td>' + client_pt_telepon + '</td>';
				strmenu = strmenu + '	<td>' + client_pt_nama_contact_person + '</td>';
				strmenu = strmenu + '	<td>' + client_pt_telepon_contact_person + '</td>';
				strmenu = strmenu + '	<td>' + Status_PT + '</td>';
				strmenu = strmenu + '	<td><span>';
				strmenu = strmenu + strU;
				strmenu = strmenu + strD;
				strmenu = strmenu + '	</span></td>';
				strmenu = strmenu + '</tr>';

				$("#tableoutletmenu > tbody").append(strmenu);
			}
		}

		$('#tableoutletmenu').DataTable();
	}

	$(document).ready(function() {
		$("#listcoorporate-province").on("change", function() {
			let provinsi = $(this).val();
			AppendCity(provinsi);
		});

		$("#listcoorporate-city").on("change", function() {
			let provinsi = $("#listcoorporate-province").val();
			let kota = $(this).val();
			AppendDistrict(provinsi, kota);
		});

		$("#listcoorporate-districts").on("change", function() {
			let kecamatan = $(this).val();
			let nama_kecamatan = $('#listcoorporate-districts option').filter(':selected').text();
			let kota = $('#listcoorporate-city option').filter(':selected').val();
			$("#data-districts").val(nama_kecamatan);
			AppendWard(kecamatan);
			if (kota == "SURABAYA") {
				AppendAndGetDataMultiLokasi(kecamatan);
			}
		});

		$("#listcoorporate-ward").on("change", function() {
			let kelurahan = $(this).val();
			let nama_kelurahan = $('#listcoorporate-ward option').filter(':selected').text();
			$("#data-ward").val(nama_kelurahan);
			$("#txtpostalcode-coorporate").val(kelurahan);
		});

		$("#listcontactperson-segment1").on("change", function() {
			let segment1 = $(this).val();
			AppendSegment2(segment1);
		});

		$("#listcontactperson-segment2").on("change", function() {
			let segment2 = $(this).val();
			// console.log(segment2);
			AppendSegment3(segment2);
		});
	});

	function AppendProvince(Outlet) {
		if (Outlet.Provinsi != null) {
			$("#listcoorporate-province").empty();
			let html = '';
			html += '<option value="">--Pilih Provinsi--</option>';
			$.each(Outlet.Provinsi, function(i, v) {
				html += '<option value="' + v.reffregion_nama + '">' + v.reffregion_nama + '</option>';
				$("#listcoorporate-province").html(html);
			});
		} else {
			$("#listcoorporate-province").append('<option value="">--Pilih Provinsi--</option>');
		}

	}

	function AppendCity(provinsi) {
		$.ajax({
			type: "POST",
			url: "<?= base_url('Outlet/Get_Request_Data_Kota') ?>",
			data: {
				id: provinsi
			},
			dataType: "JSON",
			success: function(response) {
				$("#listcoorporate-city").html(response);
			}
		});
	}


	function AppendDistrict(provinsi, kota) {
		$.ajax({
			type: "POST",
			url: "<?= base_url('Outlet/Get_Request_Data_Kecamatan') ?>",
			data: {
				provinsi: provinsi,
				id: kota
			},
			dataType: "JSON",
			success: function(response) {
				$("#listcoorporate-districts").html(response);
			}
		});
	}


	function AppendWard(kecamatan) {
		$.ajax({
			type: "POST",
			url: "<?= base_url('Outlet/Get_Request_Data_Kelurahan') ?>",
			data: {
				id: kecamatan
			},
			dataType: "JSON",
			success: function(response) {
				$("#listcoorporate-ward").html(response);
			}
		});

	}


	function AppendStreetClass(Outlet) {
		if (Outlet.KelasJalan != null) {
			$("#listcoorporate-stretclass").empty();
			// $("#listcoorporate-stretclass-update").empty();
			let html = '';
			html += '<option value="">--Pilih Kelas jalan--</option>';
			$.each(Outlet.KelasJalan, function(i, v) {
				html += '<option value="' + v.id + '">' + v.nama + '</option>';
				$("#listcoorporate-stretclass").html(html);
			});
		} else {
			$("#listcoorporate-stretclass").html('<option value="">--Pilih Kelas Jalan--</option>');
		}
	}

	function AppendStreetClass2(Outlet) {
		if (Outlet.KelasJalan2 != null) {
			$("#listcoorporate-stretclass2").empty();
			let html = '';
			html += '<option value="">--Pilih Kelas jalan--</option>';
			$.each(Outlet.KelasJalan2, function(i, v) {
				html += '<option value="' + v.id + '">' + v.nama + '</option>';
				$("#listcoorporate-stretclass2").html(html);
			});
			// $("#listcoorporate-stretclass2-update").trigger('change');
		} else {
			$("#listcoorporate-stretclass2").append('<option value="">--Pilih Kelas Jalan--</option>');
		}
	}

	function AppendArea(Outlet) {
		if (Outlet.Area != null) {
			$("#listcoorporate-area").empty();
			let html = '';
			html += '<option value="">--Pilih Area--</option>';
			$.each(Outlet.Area, function(i, v) {
				html += '<option value="' + v.id + '">' + v.nama + '</option>';
				$("#listcoorporate-area").html(html);
			});
			// $("#listcoorporate-area-update").trigger('change');
		} else {
			$("#listcoorporate-area").append('<option value="">--Pilih Area--</option>');
		}
	}

	function AppendSegment1(Outlet) {
		if (Outlet.Segment1 != null) {
			$("#listcontactperson-segment1").empty();
			let html = '';
			html += '<option value="">--Pilih Segmentasi 1--</option>';
			$.each(Outlet.Segment1, function(i, v) {
				html += '<option value="' + v.id + '">' + v.nama + '</option>';
				$("#listcontactperson-segment1").html(html);
			});
		} else {
			$("#listcontactperson-segment1").append('<option value="">--Pilih Segmentasi 1--</option>');
		}
	}

	function AppendSegment2(id) {
		$.ajax({
			type: "POST",
			url: "<?= base_url('Outlet/Get_Request_Data_Segment2') ?>",
			data: {
				id: id
			},
			dataType: "json",
			async: "true",
			success: function(response) {
				$("#listcontactperson-segment2").html(response);
			}
		});
	}


	function AppendSegment3(SegmentId2) {
		$.ajax({
			type: "POST",
			url: "<?= base_url('Outlet/Get_Request_Data_Segment3') ?>",
			data: {
				SegmentId2: SegmentId2
			},
			dataType: "json",
			success: function(response) {
				$("#listcontactperson-segment3").html(response);
			}
		});
	}

	function CheckMultiLocation() {
		$("#multilocation").on("change", function() {
			if ($("#multilocation").prop('checked') == true) {
				$("#showlistmultilokasi").show("slow");
			} else {
				$("#showlistmultilokasi").hide("slow");
			}
		});
	}

	function CheckMultiLocationUpdate() {
		$("#multilocationupdate").on("change", function() {
			if ($("#multilocationupdate").prop('checked') == true) {
				$("#showlistmultilokasiupdate").show("slow");
			} else {
				$("#showlistmultilokasiupdate").hide("slow");
				$("#listcontactperson-location-update").val("")
			}
		});
	}

	function AppendAndGetDataMultiLokasi(kode_id) {
		$.ajax({
			type: "POST",
			url: "<?= base_url('Outlet/Get_Request_Data_Multi_Lokasi') ?>",
			data: {
				kode_id: kode_id
			},
			dataType: "json",
			success: function(response) {
				$("#listcontactperson-location").html(response);
			}
		});
	}

	function AppendTimeOperasional(Outlet) {
		$('#list-day-operasional tbody').empty();

		$.each(Outlet.Day, function(i, v) {
			var html = '';

			var Jam_Buka = '<input type="time" class="from-control" id="jam-buka" name="jam-buka"/>';
			var Jam_Tutup = '<input type="time" class="from-control" id="jam-tutup" name="jam-tutup"/>';
			var status = `<select class="form-control" id="status-operasional" name="status-operasional">
                            <option value="1" selected>BUKA</option>
                            <option value="0">TUTUP</option>
                        </select>`;

			html = html + '<tr>';
			html = html + '	<td>' + i + ' <input type="hidden" id="no-urut-hari" name="no-urut-hari" value="' + i + '"/></td>';
			html = html + '	<td>' + v + ' <input type="hidden" id="nama-hari" name="nama-hari" value="' + v + '"/></td>';
			html = html + '	<td>' + Jam_Buka + '</td>';
			html = html + '	<td>' + Jam_Tutup + '</td>';
			html = html + '	<td>' + status + '</td>';
			html = html + '</tr>';

			$("#list-day-operasional > tbody").append(html);
		});
	}

	$("#btnback").click(
		function() {
			ResetForm();
			GetOutletMenu();
		}
	);

	<?php
	if ($Menu_Access["D"] == 1) {
	?>
		$("#btndeleteoutletmenu").click(
			function() {
				GetOutletMenu();
			}
		);

		// Delete Channel
		function DeleteOutletMenu(OutletID) {
			// $("#lbdeleteoutletname").html(ChannelName);
			$("#hddeleteoutletid").val(OutletID);

			$("#previewdeleteoutlet").modal('show');
		}

		$("#btnyesdeleteoutlet").click(
			function() {
				var OutletID = $("#hddeleteoutletid").val();

				$("#loadingdelete").show();
				$("#btnyesdeleteoutlet").prop("disabled", true);

				$.ajax({
					type: 'POST',
					url: "<?= base_url('Outlet/DeleteOutletMenu') ?>",
					data: {
						OutletID: OutletID
					},
					success: function(response) {
						$("#loadingdelete").hide();
						$("#btnyesdeleteoutlet").prop("disabled", false);

						if (response == 1) {
							Swal.fire(
								'Success!',
								'Data Pelanggan berhasil dihapus.',
								'success'
							)
						} else {
							var ErrMsg = response.split('$$$');
							ErrMsg = ErrMsg[1];


							var msg = ErrMsg;
							var msgtype = 'error';

							Swal.fire(
								'Error!',
								msg,
								'error'
							)
						}

						$("#previewdeleteoutlet").modal('hide');
						GetOutletMenu();

					},
					error: function(xhr, ajaxOptions, thrownError) {

						$("#loadingdelete").hide();
						$("#btnyesdeleteoutlet").prop("disabled", false);
					}
				});
			}
		);
	<?php
	}
	?>

	<?php
	if ($Menu_Access["C"] == 1) {
	?>
		$("#btnaddnewoutlet").removeAttr('style');

		// Add New Channel
		$("#btnaddnewoutlet").click(
			function() {
				ResetForm();
				$("#previewaddnewoutlet").modal('show');
			}
		);

		$("#btnsaveaddnewoutlet").click(
			function() {
				let name_corporate = $("#txtname-coorporate");
				let address_corporate = $("#txtaddress-coorporate");
				let phone_corporate = $("#txtphone-coorporate");
				// let corporate_group = $("#listcoorporate-group");
				let lattitude_corporate = $("#txtlattitude-coorporate");
				let longitude_corporate = $("#txtlongitude-coorporate");
				let stretclass_corporate = $("#listcoorporate-stretclass");
				let stretclass2_corporate = $("#listcoorporate-stretclass2");
				let area_corporate = $("#listcoorporate-area");
				let province = $("#listcoorporate-province");
				let city = $("#listcoorporate-city");
				// let districts = $("#listcoorporate-districts");
				let districts = $("#data-districts");
				// let ward = $("#listcoorporate-ward");
				let ward = $("#data-ward");
				let kodepos_corporate = $("#txtpostalcode-coorporate");

				let name_contact_person = $("#txtname-contact-person");
				let phone_contact_person = $("#txtphone-contact-person");
				let kreditlimit_contact_person = $("#txtkreditlimit-contact-person");
				let segment1_contact_person = $("#listcontactperson-segment1").val();
				let segment2_contact_person = $("#listcontactperson-segment2").val();
				let segment3_contact_person = $("#listcontactperson-segment3").val();
				let listcontactperson_location = $("#listcontactperson-location");

				let isValidMultiLocation = '';
				if ($("#multilocation").is(':checked')) {
					isValidMultiLocation = 1;
				} else {
					isValidMultiLocation = 0;
				}

				let status = '';
				if ($("#txtstatus-coorporate").is(':checked')) {
					status = 1;
				} else {
					status = 0;
				}

				let no_urut_hari = [];
				let nama_hari = [];
				let jam_buka = [];
				let jam_tutup = [];
				let status_operasional = [];

				$("input[name='no-urut-hari']").each(function(i, v) {
					no_urut_hari.push($(this).val());
				});

				$("input[name='nama-hari']").each(function(i, v) {
					nama_hari.push($(this).val());
				});

				$("input[name='jam-buka']").each(function(i, v) {
					jam_buka.push($(this).val());
				});

				$("input[name='jam-tutup']").each(function(i, v) {
					jam_tutup.push($(this).val());
				});

				$("select[name='status-operasional']").each(function(i, v) {
					status_operasional.push($(this).val());
				});

				let final_arr = [];
				for (let i = 0; i < no_urut_hari.length; i++) {
					final_arr.push({
						no_urut: no_urut_hari[i],
						hari: nama_hari[i],
						buka: jam_buka[i],
						tutup: jam_tutup[i],
						status: status_operasional[i]
					});
				}

				validasi(name_corporate, address_corporate, phone_corporate, lattitude_corporate, longitude_corporate, stretclass_corporate, stretclass2_corporate, area_corporate, province, city, districts, ward, kodepos_corporate, name_contact_person, phone_contact_person, kreditlimit_contact_person, isValidMultiLocation, listcontactperson_location);

				$("#loadingadd").show();
				$("#btnsaveaddnewoutlet").prop("disabled", true);

				$.ajax({
					type: 'POST',
					url: "<?= base_url('Outlet/SaveAddNewOutlet') ?>",
					dataType: "json",
					async: "true",
					data: {
						name_corporate: name_corporate.val(),
						address_corporate: address_corporate.val(),
						phone_corporate: phone_corporate.val(),
						// corporate_group: corporate_group.val(),
						lattitude_corporate: lattitude_corporate.val(),
						longitude_corporate: longitude_corporate.val(),
						stretclass_corporate: stretclass_corporate.val(),
						stretclass2_corporate: stretclass2_corporate.val(),
						area_corporate: area_corporate.val(),
						province: province.val(),
						city: city.val(),
						districts: districts.val(),
						ward: ward.val(),
						kodepos_corporate: kodepos_corporate.val(),
						name_contact_person: name_contact_person.val(),
						phone_contact_person: phone_contact_person.val(),
						kreditlimit_contact_person: kreditlimit_contact_person.val(),
						segment1_contact_person: segment1_contact_person,
						segment2_contact_person: segment2_contact_person,
						segment3_contact_person: segment3_contact_person,
						isValidMultiLocation: isValidMultiLocation,
						listcontactperson_location: listcontactperson_location.val(),
						timeoperasional: final_arr,
						status: status
					},

					success: function(response) {
						$("#loadingadd").hide();
						$("#btnsaveaddnewoutlet").prop("disabled", false);
						if (response == 1) {
							var msg = 'Data Pelanggan berhasil ditambah';
							var msgtype = 'success';

							Swal.fire(
								'Success!',
								msg,
								msgtype
							)

							GetOutletMenu();
							// SetDataAwal();

							$("#previewaddnewoutlet").modal('hide');
							ResetForm();
						} else {
							var msg = 'Data Pelanggan gagal ditambah';
							var msgtype = 'error';

							Swal.fire(
								'Error!',
								msg,
								msgtype
							)
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						$("#loadingadd").hide();
						$("#btnsaveaddnewoutlet").prop("disabled", false);
					}
				});
			}
		);
	<?php
	}
	?>

	function ResetForm() {
		<?php
		if ($Menu_Access["U"] == 1) {
		?>
			$("#txtname-coorporate, #txtaddress-coorporate, #txtphone-coorporate, #txtlattitude-coorporate, #txtlongitude-coorporate, #txtpostalcode-coorporate, #data-districts, #data-ward, #txtname-contact-person, #txtphone-contact-person, #txtkreditlimit-contact-person").each(function() {
				$(this).val("");
			});

			$("#listcoorporate-stretclass, #listcoorporate-stretclass2, #listcoorporate-area, #listcoorporate-province, #listcoorporate-city, #listcoorporate-districts, #listcoorporate-ward, #listcontactperson-segment1, #listcontactperson-segment2, #listcontactperson-segment3").each(function() {
				$(this).prop('selectedIndex', 0);
				// $(this).val("");
			});
		<?php
		}
		?>

		<?php
		if ($Menu_Access["C"] == 1) {
		?>
			$("#txtname-coorporate, #txtaddress-coorporate, #txtphone-coorporate, #txtlattitude-coorporate, #txtlongitude-coorporate, #txtpostalcode-coorporate, #data-districts, #data-ward, #txtname-contact-person, #txtphone-contact-person, #txtkreditlimit-contact-person").each(function() {
				$(this).val("");
			});

			$("#listcoorporate-stretclass, #listcoorporate-stretclass, #listcoorporate-area, #listcoorporate-province, #listcoorporate-city, #listcoorporate-districts, #listcoorporate-ward, #listcontactperson-segment1, #listcontactperson-segment2, #listcontactperson-segment3").each(function() {
				$(this).prop('selectedIndex', 0);
			});
		<?php
		}
		?>

	}

	function validasi(name_corporate, address_corporate, phone_corporate, lattitude_corporate, longitude_corporate, stretclass_corporate, stretclass2_corporate, area_corporate, province, city, districts, ward, kodepos_corporate, name_contact_person, phone_contact_person, kreditlimit_contact_person, isValidMultiLocation, listcontactperson_location) {

		name_corporate.prop("required", true);
		address_corporate.prop("required", true);
		phone_corporate.prop("required", true);
		lattitude_corporate.prop("required", true);
		longitude_corporate.prop("required", true);
		stretclass_corporate.prop("required", true);
		stretclass2_corporate.prop("required", true);
		area_corporate.prop("required", true);
		province.prop("required", true);
		city.prop("required", true);
		districts.prop("required", true);
		ward.prop("required", true);
		kodepos_corporate.prop("required", true);
		name_contact_person.prop("required", true);
		phone_contact_person.prop("required", true);
		kreditlimit_contact_person.prop("required", true);
		if (isValidMultiLocation == 1) {
			listcontactperson_location.prop("required", true);
		}

		if (name_corporate.val() == "") {
			$(".txtname-coorporate").addClass("has-error");
			$(".invalid-nama-corporate").html("Nama Outlet tidak boleh kosong");
			name_corporate.focus();
		} else {
			$(".txtname-coorporate").removeClass("has-error");
			$(".invalid-nama-corporate").html("");
		}

		if (address_corporate.val() == "") {
			$(".txtaddress-coorporate").addClass("has-error");
			$(".invalid-alamat-corporate").html("Alamat Outlet tidak boleh kosong");
			address_corporate.focus();
		} else {
			$(".txtaddress-coorporate").removeClass("has-error");
			$(".invalid-alamat-corporate").html("");
		}

		if (phone_corporate.val() == "") {
			$(".txtphone-coorporate").addClass("has-error");
			$(".invalid-telepon-corporate").html("Telephon Outlet tidak boleh kosong");
			phone_corporate.focus();
		} else {
			$(".txtphone-coorporate").removeClass("has-error");
			$(".invalid-telepon-corporate").html("");
		}

		if (lattitude_corporate.val() == "") {
			$(".txtlattitude-coorporate").addClass("has-error");
			$(".invalid-lattitude-corporate").html("Lattitude Outlet tidak boleh kosong");
			lattitude_corporate.focus();
		} else {
			$(".txtlattitude-coorporate").removeClass("has-error");
			$(".invalid-lattitude-corporate").html("");
		}

		if (longitude_corporate.val() == "") {
			$(".txtlongitude-coorporate").addClass("has-error");
			$(".invalid-longitude-corporate").html("Longitude Outlet tidak boleh kosong");
			longitude_corporate.focus();
		} else {
			$(".txtlongitude-coorporate").removeClass("has-error");
			$(".invalid-longitude-corporate").html("");
		}

		if (stretclass_corporate.val() == "") {
			$(".listcoorporate-stretclass").addClass("has-error");
			$(".invalid-kelas-jalan-corporate").html("Kelas Jalan Berdasarkan muatan tidak boleh kosong");
			stretclass_corporate.focus();
		} else {
			$(".listcoorporate-stretclass").removeClass("has-error");
			$(".invalid-kelas-jalan-corporate").html("");
		}

		if (stretclass2_corporate.val() == "") {
			$(".listcoorporate-stretclass2").addClass("has-error");
			$(".invalid-kelas-jalan2-corporate").html("Kelas Jalan Berdasarkan fungsi jalan tidak boleh kosong");
			stretclass2_corporate.focus();
		} else {
			$(".listcoorporate-stretclass2").removeClass("has-error");
			$(".invalid-kelas-jalan2-corporate").html("");
		}

		if (area_corporate.val() == "") {
			$(".listcoorporate-area").addClass("has-error");
			$(".invalid-area-corporate").html("Area Outlet tidak boleh kosong");
			area_corporate.focus();
		} else {
			$(".listcoorporate-area").removeClass("has-error");
			$(".invalid-area-corporate").html("");
		}

		if (province.val() == "") {
			$(".listcoorporate-province").addClass("has-error");
			$(".invalid-provinsi-corporate").html("Provinsi Outlet tidak boleh kosong");
			province.focus();
		} else {
			$(".listcoorporate-province").removeClass("has-error");
			$(".invalid-provinsi-corporate").html("");
		}

		if (city.val() == "") {
			$(".listcoorporate-city").addClass("has-error");
			$(".invalid-kota-corporate").html("Kota Outlet tidak boleh kosong");
			city.focus();
		} else {
			$(".listcoorporate-city").removeClass("has-error");
			$(".invalid-kota-corporate").html("");
		}

		if (districts.val() == "") {
			$(".listcoorporate-districts").addClass("has-error");
			$(".invalid-kecamatan-corporate").html("Kecamatan Outlet tidak boleh kosong");
			districts.focus();
		} else {
			$(".listcoorporate-districts").removeClass("has-error");
			$(".invalid-kecamatan-corporate").html("");
		}

		if (ward.val() == "") {
			$(".listcoorporate-ward").addClass("has-error");
			$(".invalid-kelurahan-corporate").html("Kelurahan Outlet tidak boleh kosong");
			ward.focus();
		} else {
			$(".listcoorporate-ward").removeClass("has-error");
			$(".invalid-kelurahan-corporate").html("");
		}

		if (kodepos_corporate.val() == "") {
			$(".txtpostalcode-coorporate").addClass("has-error");
			$(".invalid-kode-pos-corporate").html("Kode Pos Outlet tidak boleh kosong");
			kodepos_corporate.focus();
		} else {
			$(".txtpostalcode-coorporate").removeClass("has-error");
			$(".invalid-kode-pos-corporate").html("");
		}

		if (name_contact_person.val() == "") {
			$(".txtname-contact-person").addClass("has-error");
			$(".invalid-nama-contact-person").html("Nama Contact Person tidak boleh kosong");
			name_contact_person.focus();
		} else {
			$(".txtname-contact-person").removeClass("has-error");
			$(".invalid-nama-contact-person").html("");
		}

		if (phone_contact_person.val() == "") {
			$(".txtphone-contact-person").addClass("has-error");
			$(".invalid-telepon-contact-person").html("Telepon Contact Person tidak boleh kosong");
			phone_contact_person.focus();
		} else {
			$(".txtphone-contact-person").removeClass("has-error");
			$(".invalid-telepon-contact-person").html("");
		}

		if (kreditlimit_contact_person.val() == "") {
			$(".txtkreditlimit-contact-person").addClass("has-error");
			$(".invalid-kredit-limit-contact-person").html("Kredit Limit Contact Person tidak boleh kosong");
			kreditlimit_contact_person.focus();
		} else {
			$(".txtkreditlimit-contact-person").removeClass("has-error");
			$(".invalid-kredit-limit-contact-person").html("");
		}

		if (isValidMultiLocation == 1) {
			if (listcontactperson_location.val() == "") {
				$(".listcontactperson-location").addClass("has-error");
				$(".invalid-list-location-contact-person").html("Lokasi tidak boleh kosong");
				kreditlimit_contact_person.focus();
			} else {
				$(".listcontactperson-location").removeClass("has-error");
				$(".invalid-list-location-contact-person").html("");
			}
		}

	}
</script>