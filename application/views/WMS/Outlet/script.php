<script type="text/javascript">
	loadingBeforeReadyPage()

	function url_like(url) {
		return base_url + url;
	}

	function main_page() {
		setTimeout(() => {
			window.location = url_like("Outlet/OutletMenu");
		}, 2000);
	}

	$(document).ready(function() {
		$(".select2").select2({
			width: "100%"
		});
		let OutletId = location.pathname;
		var id = OutletId.substring(OutletId.lastIndexOf('/') + 1);
		// console.log(id);
		$.ajax({
			type: "POST",
			url: "<?= base_url('Outlet/GetDataByIdForEdit') ?>",
			data: {
				id: id
			},
			dataType: "json",
			async: "true",
			success: function(data) {
				let data_arr = '';
				if (data != null) {
					let remove0 = data[0];
					data_arr = remove0;
				}

				//append dropdown provinsi
				let provinsi_db = data_arr != null ? data_arr.provinsi : null;
				let db_kota = data_arr != null ? data_arr.kota : null;
				let db_kecamatan = data_arr != null ? data_arr.kode_pos_id : null;
				let db_kelurahan = data_arr != null ? data_arr.kelurahan : null;
				let lokasi_outlet_id = data_arr != null ? data_arr.lokasi_outlet_id : null;
				let kelas_jalan = data_arr != null ? data_arr.kelas_jalan : null;
				let area = data_arr != null ? data_arr.area : null;
				let kelas_jalan2 = data_arr != null ? data_arr.kelas_jalan2 : null;
				let segment1 = data_arr != null ? data_arr.segment1 : null;
				let segment2 = data_arr != null ? data_arr.segment2 : null;
				let segment3 = data_arr != null ? data_arr.segment3 : null;


				//kabupaten change
				$("#listcoorporate-city-update").empty();
				$("#listcoorporate-province-update").change(function() {
					let ProvinciIid = $(this).val();
					let id = ProvinciIid == null ? provinsi_db : ProvinciIid;
					$.ajax({
						type: "POST",
						url: "<?= base_url('Outlet/Get_Request_Data_Kota') ?>",
						data: {
							id: id,
							kota: db_kota
						},
						dataType: "JSON",
						async: "true",
						success: function(response) {
							$("#listcoorporate-city-update").html(response).trigger("change");
						}
					});
					// AppendCity(id, db_kota);
				});

				//kota change
				$("#listcoorporate-districts-update").empty();
				$("#listcoorporate-city-update").change(function() {
					let kotaId = $(this).val();
					let id = kotaId == null ? db_kota : kotaId;
					let provinsi = $("#listcoorporate-province-update").val();
					$.ajax({
						type: "POST",
						url: "<?= base_url('Outlet/Get_Request_Data_Kecamatan') ?>",
						data: {
							id: id,
							provinsi: provinsi,
							kecamatan: db_kecamatan
						},
						dataType: "JSON",
						async: "true",
						success: function(response) {
							$("#listcoorporate-districts-update").html(response).trigger('change');
						}
					});
				});
				//kecamatan change
				$("#listcoorporate-ward-update").empty();
				$("#listcoorporate-districts-update").change(function() {
					let kecamatanId = $(this).val();
					let id = kecamatanId == null ? db_kecamatan : kecamatanId;
					// console.log(kecamatanId);
					let nama_kecamatan = $('#listcoorporate-districts-update option').filter(':selected').text();
					let kota = $('#listcoorporate-city-update option').filter(':selected').val();
					$("#data-districts-update").val(nama_kecamatan);
					$.ajax({
						type: "POST",
						url: "<?= base_url('Outlet/Get_Request_Data_Kelurahan') ?>",
						data: {
							id: id,
							kelurahan: db_kelurahan
						},
						dataType: "JSON",
						async: "true",
						success: function(response) {
							$("#listcoorporate-ward-update").html(response).trigger("change");
						}
					});
					let kode_id = $("#listcoorporate-districts-update").val();
					$.ajax({
						type: "POST",
						url: "<?= base_url('Outlet/Get_Request_Data_Multi_Lokasi') ?>",
						data: {
							kode_id: kode_id,
							lokasi_outlet_id: lokasi_outlet_id
						},
						dataType: "json",
						async: "true",
						success: function(response) {
							$("#listcontactperson-location-update").html(response).trigger("change");
						}
					});
				});

				$("#listcoorporate-ward-update").change(function() {
					let kelurahan = $(this).val();
					let nama_kelurahan = $('#listcoorporate-ward-update option').filter(':selected').text();
					$("#data-ward-update").val(nama_kelurahan);
					$("#txtpostalcode-coorporate-update").val(kelurahan);
				});

				$("#listcontactperson-segment2-update").empty();
				$("#listcontactperson-segment1-update").change(function() {
					let id = $(this).val();
					console.log(segment2);
					$.ajax({
						type: "POST",
						url: "<?= base_url('Outlet/Get_Request_Data_Segment2') ?>",
						data: {
							id: id,
							segment2: segment2
						},
						dataType: "json",
						async: "true",
						success: function(response) {
							$("#listcontactperson-segment2-update").html(response).trigger('change');
						}
					});
				});

				$("#listcontactperson-segment3-update").empty();
				$("#listcontactperson-segment2-update").change(function() {
					let segment2_Id = $(this).val();
					let id = segment2_Id == null ? segment2 : segment2_Id;
					// let segment1 = $("#listcontactperson-segment1-update").val();
					$.ajax({
						type: "POST",
						url: "<?= base_url('Outlet/Get_Request_Data_Segment3') ?>",
						data: {
							id: id,
							Segment3: segment3
						},
						dataType: "JSON",
						async: "true",
						success: function(response) {
							$("#listcontactperson-segment3-update").html(response).trigger('change');
						}
					});
				});
			}
		})

		$.ajax({
			type: "POST",
			url: "<?= base_url('Outlet/Get_Data_Outlet_By_Id') ?>",
			data: {
				OutletId: id
			},
			dataType: "JSON",
			async: "true",
			success: function(response) {
				$.each(response, function(i, v) {
					$("#txthideOutletId-update").val(i);
					$("#txtname-coorporate-update").val(v.nama);
					$("#txtaddress-coorporate-update").val(v.alamat);
					$("#txtphone-coorporate-update").val(v.telepon);

					$("#listcoorporate-province-update").val(v.provinsi).trigger('change');
					$("#listcoorporate-city-update").val(v.kota).trigger('change');
					$("#listcoorporate-district-update").val(v.kecamatan).trigger('change');
					$("#listcoorporate-ward-update").val(v.kelurahan).trigger('change');
					$("#txtpostalcode-coorporate-update").val(v.kodepos).trigger('change');

					$("#listcoorporate-stretclass-update").val(v.kelas_jalan).trigger('change');
					$("#listcoorporate-stretclass2-update").val(v.kelas_jalan2).trigger('change');
					$("#listcoorporate-area-update").val(v.area).trigger('change');

					$("#txtlattitude-coorporate-update").val(v.lattitude);
					$("#txtlongitude-coorporate-update").val(v.longitude);
					$("#txtname-contact-person-update").val(v.nama_cp);
					$("#txtphone-contact-person-update").val(v.telepon_cp);
					$("#txtkreditlimit-contact-person-update").val(v.kredit_limit_cp);

					$("#listcontactperson-segment1-update").val(v.segment1).trigger('change');
					$("#listcontactperson-segment2-update").val(v.segment2).trigger('change');
					$("#listcontactperson-segment3-update").val(v.segment3).trigger('change');

					if (v.checklist == 1) {
						$("#multilocationupdate").prop("checked", true);
						$("#showlistmultilokasiupdate").show();
					} else {
						$("#multilocationupdate").prop("checked", false);
						$("#showlistmultilokasiupdate").hide();
					}

					if (v.aktif == 1) {
						$("#txtstatus-coorporate-update").prop("checked", true);
					} else {
						$("#txtstatus-coorporate-update").prop("checked", false);
					}

					$("#listcontactperson-location-update").val(v.lokasi_id).trigger('change');
					$("#list-day-operasional-update > tbody").empty();
					$.each(v.data, function(index, value) {
						if (value.id_detail && value.no_urut && value.hari && value.buka && value.tutup && value.status != null) {
							var buka = value.buka.split(".");
							var buka1 = buka[0].split(":");
							var removebuku_1 = buka1.splice(-1);
							var tutup = value.tutup.split(".");
							var tutup1 = tutup[0].split(":");
							var removetutup_1 = tutup1.splice(-1);
							var select = '';
							var clock_open = buka1.join(":") != '00:00' ? buka1.join(":") : '';
							var clock_close = tutup1.join(":") != '00:00' ? tutup1.join(":") : '';
							if (value.status == 0) {
								select = "<option value='1'>BUKA</option><option value='0' selected>TUTUP</option>"
							} else {
								select = "<option value='1' selected>BUKA</option><option value='0'>TUTUP</option>"
							}
							var html = '';
							var Jam_Buka = '<input type="time" class="from-control" id="jam_buka_perusahaan_update" value="' + clock_open + '" name="jam_buka_perusahaan_update"/>';
							var Jam_Tutup = '<input type="time" class="from-control" value="' + clock_close + '" id="jam_tutup_perusahaan_update" name="jam_tutup_perusahaan_update"/>';
							var status = `<select class="form-control" id="status_operasional_perusahaan_update" name="status_operasional_perusahaan_update">` + select + `</select>`;

							html = html + '<tr>';
							html = html + '	<td>' + value.no_urut + ' <input type="hidden" id="no_urut_hari_perusahaan_update" name="no_urut_hari_perusahaan_update" value="' + value.no_urut + '"/> <input type="hidden" id="id_detail_perusahaan_update" name="id_detail_perusahaan_update" value="' + value.id_detail + '"/></td>';
							html = html + '	<td>' + value.hari + ' <input type="hidden" id="nama_hari_perusahaan_update" name="nama_hari_perusahaan_update" value="' + value.hari + '"/></td>';
							html = html + '	<td>' + Jam_Buka + '</td>';
							html = html + '	<td>' + Jam_Tutup + '</td>';
							html = html + '	<td>' + status + '</td>';
							html = html + '</tr>';
							$("#list-day-operasional-update > tbody").append(html);
						} else {
							$("#list-day-operasional-update > tbody").append('<tr><td colspan="5" class="text-center text-danger">Data Kosong</td></tr>');
						}
					});
				});
			}
		});

		CheckMultiLocationUpdate();

		$("#btnsaveupdateoutlet").click(
			function() {
				var outlet_id = $("#txthideOutletId-update").val();
				var name_corporate = $("#txtname-coorporate-update");
				var address_corporate = $("#txtaddress-coorporate-update");
				var phone_corporate = $("#txtphone-coorporate-update");
				// var corporate_group = $("#listcoorporate-group-update");
				var lattitude_corporate = $("#txtlattitude-coorporate-update");
				var longitude_corporate = $("#txtlongitude-coorporate-update");
				var stretclass_corporate = $("#listcoorporate-stretclass-update");
				var stretclass2_corporate = $("#listcoorporate-stretclass2-update");
				var area_corporate = $("#listcoorporate-area-update");
				var province = $("#listcoorporate-province-update");
				var city = $("#listcoorporate-city-update");
				// var districts = $("#listcoorporate-districts-update");
				var districts = $("#data-districts-update");
				// var ward = $("#listcoorporate-ward-update");
				var ward = $("#data-ward-update");
				var kodepos_corporate = $("#txtpostalcode-coorporate-update");

				var name_contact_person = $("#txtname-contact-person-update");
				var phone_contact_person = $("#txtphone-contact-person-update");
				var kreditlimit_contact_person = $("#txtkreditlimit-contact-person-update");
				var segment1_contact_person = $("#listcontactperson-segment1-update").val();
				var segment2_contact_person = $("#listcontactperson-segment2-update").val();
				var segment3_contact_person = $("#listcontactperson-segment3-update").val();

				let listcontactperson_location = $("#listcontactperson-location-update");

				let isValidMultiLocation = '';
				if ($("#multilocationupdate").is(':checked')) {
					isValidMultiLocation = 1;
				} else {
					isValidMultiLocation = 0;
				}

				let status = '';
				if ($("#txtstatus-coorporate-update").is(':checked')) {
					status = 1;
				} else {
					status = 0;
				}

				let id_detail = [];
				let no_urut_hari = [];
				let nama_hari = [];
				let jam_buka = [];
				let jam_tutup = [];
				let status_operasional = [];

				$("input[name='id_detail-update']").each(function(i, v) {
					id_detail.push($(this).val());
				});

				$("input[name='no-urut-hari-update']").each(function(i, v) {
					no_urut_hari.push($(this).val());
				});

				$("input[name='nama-hari-update']").each(function(i, v) {
					nama_hari.push($(this).val());
				});

				$("input[name='jam-buka-update']").each(function(i, v) {
					jam_buka.push($(this).val());
				});

				$("input[name='jam-tutup-update']").each(function(i, v) {
					jam_tutup.push($(this).val());
				});

				$("select[name='status-operasional-update']").each(function(i, v) {
					status_operasional.push($(this).val());
				});

				let final_arr = [];
				for (let i = 0; i < no_urut_hari.length; i++) {
					final_arr.push({
						id_detail: id_detail[i],
						no_urut: no_urut_hari[i],
						hari: nama_hari[i],
						buka: jam_buka[i],
						tutup: jam_tutup[i],
						status: status_operasional[i]
					});
				}

				validasi_update(name_corporate, address_corporate, phone_corporate, lattitude_corporate, longitude_corporate, stretclass_corporate, stretclass2_corporate, area_corporate, province, city, districts, ward, kodepos_corporate, name_contact_person, phone_contact_person, kreditlimit_contact_person, isValidMultiLocation, listcontactperson_location)

				$("#loadingupdate").show();
				$("#btnsaveupdateoutlet").prop("disabled", true);

				$.ajax({
					type: 'POST',
					url: "<?= base_url('Outlet/SaveUpdateOutlet') ?>",
					data: {
						outlet_id: outlet_id,
						name_corporate_update: name_corporate.val(),
						address_corporate_update: address_corporate.val(),
						phone_corporate_update: phone_corporate.val(),
						// corporate_group_update: corporate_group.val(),
						lattitude_corporate_update: lattitude_corporate.val(),
						longitude_corporate_update: longitude_corporate.val(),
						stretclass_corporate_update: stretclass_corporate.val(),
						stretclass2_corporate_update: stretclass2_corporate.val(),
						area_corporate_update: area_corporate.val(),
						province_update: province.val(),
						city_update: city.val(),
						districts_update: districts.val(),
						ward_update: ward.val(),
						kodepos_corporate_update: kodepos_corporate.val(),
						name_contact_person_update: name_contact_person.val(),
						phone_contact_person_update: phone_contact_person.val(),
						kreditlimit_contact_person_update: kreditlimit_contact_person.val(),
						segment1_contact_person_update: segment1_contact_person,
						segment2_contact_person_update: segment2_contact_person,
						segment3_contact_person_update: segment3_contact_person,
						isValidMultiLocation: isValidMultiLocation,
						listcontactperson_location: listcontactperson_location.val(),
						timeoperasional_update: final_arr,
						status: status
					},
					async: "true",
					success: function(response) {
						$("#loadingupdate").hide();
						$("#btnsaveupdateoutlet").prop("disabled", false);

						if (response == 1) {
							var msg = 'Data Pelanggan berhasil diubah';
							var msgtype = 'success';

							Swal.fire(
								'Success!',
								msg,
								msgtype
							)
							main_page();
							// ResetForm();

							// $("#previewupdateoutlet").modal('hide');

							// GetOutletMenu();
							// SetDataAwal();
						} else if (response == 0) {
							var msg = 'Gagal mengubah';
							var msgtype = 'error';

							Swal.fire(
								'Errorr!',
								msg,
								msgtype
							)

							main_page();
							// ResetForm();

							// $("#previewupdateoutlet").modal('hide');

							// GetOutletMenu();
							// SetDataAwal();
						} else {
							var msg = response;
							var msgtype = 'error';

							Swal.fire(
								'Error!',
								msg,
								msgtype
							)
						}

					},
					error: function(xhr, ajaxOptions, thrownError) {
						$("#loadingupdate").hide();
						$("#btnsaveupdateoutlet").prop("disabled", false);
					}
				});
			}
		);

		$("#btnbackoutletupdate").click(function() {
			window.location = url_like("Outlet/OutletMenu");
		});
	});

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

	function validasi_update(name_corporate, address_corporate, phone_corporate, lattitude_corporate, longitude_corporate, stretclass_corporate, stretclass2_corporate, area_corporate, province, city, districts, ward, kodepos_corporate, name_contact_person, phone_contact_person, kreditlimit_contact_person, isValidMultiLocation, listcontactperson_location) {

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
			$(".txtname-coorporate-update").addClass("has-error");
			$(".invalid-nama-corporate-update").html("Nama Outlet tidak boleh kosong");
			name_corporate.focus();
		} else {
			$(".txtname-coorporate-update").removeClass("has-error");
			$(".invalid-nama-corporate-update").html("");
		}

		if (address_corporate.val() == "") {
			$(".txtaddress-coorporate-update").addClass("has-error");
			$(".invalid-alamat-corporate-update").html("Alamat Outlet tidak boleh kosong");
			address_corporate.focus();
		} else {
			$(".txtaddress-coorporate-update").removeClass("has-error");
			$(".invalid-alamat-corporate-update").html("");
		}

		if (phone_corporate.val() == "") {
			$(".txtphone-coorporate-update").addClass("has-error");
			$(".invalid-telepon-corporate-update").html("Telephon Outlet tidak boleh kosong");
			phone_corporate.focus();
		} else {
			$(".txtphone-coorporate-update").removeClass("has-error");
			$(".invalid-telepon-corporate-update").html("");
		}

		if (lattitude_corporate.val() == "") {
			$(".txtlattitude-coorporate-update").addClass("has-error");
			$(".invalid-lattitude-corporate-update").html("Lattitude Outlet tidak boleh kosong");
			lattitude_corporate.focus();
		} else {
			$(".txtlattitude-coorporate-update").removeClass("has-error");
			$(".invalid-lattitude-corporate-update").html("");
		}

		if (longitude_corporate.val() == "") {
			$(".txtlongitude-coorporate-update").addClass("has-error");
			$(".invalid-longitude-corporate-update").html("Longitude Outlet tidak boleh kosong");
			longitude_corporate.focus();
		} else {
			$(".txtlongitude-coorporate-update").removeClass("has-error");
			$(".invalid-longitude-corporate-update").html("");
		}

		if (stretclass_corporate.val() == "") {
			$(".listcoorporate-stretclass-update").addClass("has-error");
			$(".invalid-kelas-jalan-corporate-update").html("Kelas Jalan Berdasarkan barang muatan tidak boleh kosong");
			stretclass_corporate.focus();
		} else {
			$(".listcoorporate-stretclass-update").removeClass("has-error");
			$(".invalid-kelas-jalan-corporate-update").html("");
		}

		if (stretclass2_corporate.val() == "") {
			$(".listcoorporate-stretclass2-update").addClass("has-error");
			$(".invalid-kelas-jalan2-corporate-update").html("Kelas Jalan Berdasarkan fungsi jalan tidak boleh kosong");
			stretclass2_corporate.focus();
		} else {
			$(".listcoorporate-stretclass2-update").removeClass("has-error");
			$(".invalid-kelas-jalan2-corporate-update").html("");
		}

		if (area_corporate.val() == "") {
			$(".listcoorporate-area-update").addClass("has-error");
			$(".invalid-area-corporate-update").html("Area Outlet tidak boleh kosong");
			area_corporate.focus();
		} else {
			$(".listcoorporate-area-update").removeClass("has-error");
			$(".invalid-area-corporate-update").html("");
		}

		if (province.val() == "") {
			$(".listcoorporate-province-update").addClass("has-error");
			$(".invalid-provinsi-corporate-update").html("Provinsi Outlet tidak boleh kosong");
			province.focus();
		} else {
			$(".listcoorporate-province-update").removeClass("has-error");
			$(".invalid-provinsi-corporate-update").html("");
		}

		if (city.val() == "") {
			$(".listcoorporate-city-update").addClass("has-error");
			$(".invalid-kota-corporate-update").html("Kota Outlet tidak boleh kosong");
			city.focus();
		} else {
			$(".listcoorporate-city-update").removeClass("has-error");
			$(".invalid-kota-corporate-update").html("");
		}

		if (districts.val() == "") {
			$(".listcoorporate-districts-update").addClass("has-error");
			$(".invalid-kecamatan-corporate-update").html("Kecamatan Outlet tidak boleh kosong");
			districts.focus();
		} else {
			$(".listcoorporate-districts-update").removeClass("has-error");
			$(".invalid-kecamatan-corporate-update").html("");
		}

		if (ward.val() == "") {
			$(".listcoorporate-ward-update").addClass("has-error");
			$(".invalid-kelurahan-corporate-update").html("Kelurahan Outlet tidak boleh kosong");
			ward.focus();
		} else {
			$(".listcoorporate-ward-update").removeClass("has-error");
			$(".invalid-kelurahan-corporate-update").html("");
		}

		if (kodepos_corporate.val() == "") {
			$(".txtpostalcode-coorporate-update").addClass("has-error");
			$(".invalid-kode-pos-corporate-update").html("Kode Pos Outlet tidak boleh kosong");
			kodepos_corporate.focus();
		} else {
			$(".txtpostalcode-coorporate-update").removeClass("has-error");
			$(".invalid-kode-pos-corporate-update").html("");
		}

		if (name_contact_person.val() == "") {
			$(".txtname-contact-person-update").addClass("has-error");
			$(".invalid-nama-contact-person-update").html("Nama Contact Person tidak boleh kosong");
			name_contact_person.focus();
		} else {
			$(".txtname-contact-person-update").removeClass("has-error");
			$(".invalid-nama-contact-person-update").html("");
		}

		if (phone_contact_person.val() == "") {
			$(".txtphone-contact-person-update").addClass("has-error");
			$(".invalid-telepon-contact-person-update").html("Telepon Contact Person tidak boleh kosong");
			phone_contact_person.focus();
		} else {
			$(".txtphone-contact-person-update").removeClass("has-error");
			$(".invalid-telepon-contact-person-update").html("");
		}

		if (kreditlimit_contact_person.val() == "") {
			$(".txtkreditlimit-contact-person-update").addClass("has-error");
			$(".invalid-kredit-limit-contact-person-update").html("Kredit Limit Contact Person tidak boleh kosong");
			kreditlimit_contact_person.focus();
		} else {
			$(".txtkreditlimit-contact-person-update").removeClass("has-error");
			$(".invalid-kredit-limit-contact-person-update").html("");
		}

		if (isValidMultiLocation == 1) {
			if (listcontactperson_location.val() == "") {
				$(".listcontactperson-location-update").addClass("has-error");
				$(".invalid-list-location-contact-person-update").html("Lokasi tidak boleh kosong");
				kreditlimit_contact_person.focus();
			} else {
				$(".listcontactperson-location-update").removeClass("has-error");
				$(".invalid-list-location-contact-person-update").html("");
			}
		}

	}
</script>