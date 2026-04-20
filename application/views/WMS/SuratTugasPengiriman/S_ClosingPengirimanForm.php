<script type="text/javascript">
var ChannelCode = '';
var arr_do_list = [];
var arr_do_id = [];
var arr_add_biaya = [];
var arr_del_biaya = [];
var total_bayar = 0;
var total_tagihan = 0;
var tes = "";
var cek_btb = [];
loadingBeforeReadyPage()

function message_custom(titleType, iconType, htmlType) {
    Swal.fire({
        title: titleType,
        icon: iconType,
        html: htmlType,
    })
}

// function message(msg, msgtext, msgtype) {
// 	Swal.fire(msg, msgtext, msgtype);
// }

// function message_topright(type, msg) {
// 	const Toast = Swal.mixin({
// 		toast: true,
// 		position: "top-end",
// 		showConfirmButton: false,
// 		timer: 3000,
// 		didOpen: (toast) => {
// 			toast.addEventListener("mouseenter", Swal.stopTimer);
// 			toast.addEventListener("mouseleave", Swal.resumeTimer);
// 		},
// 	});

// 	Toast.fire({
// 		icon: type,
// 		title: msg,
// 	});
// }

$('#select-terkirim').click(function(event) {
    if (this.checked) {
        // Iterate each checkbox
        $('[id="select-terkirim"]:checkbox').each(function() {
            this.checked = true;
        });
        $('[name="CheckboxDOTerkirim"]:checkbox').each(function() {
            this.checked = true;
        });

        $('[id="select-terkirim-sebagian"]:checkbox').each(function() {
            this.checked = false;
        });
        $('[name="CheckboxDOTerkirimSebagian"]:checkbox').each(function() {
            this.checked = false;
        });

        $('[id="select-gagal"]:checkbox').each(function() {
            this.checked = false;
        });
        $('[name="CheckboxDOGagal"]:checkbox').each(function() {
            this.checked = false;
        });
        $('[name="CheckboxDOKirimUlang"]:checkbox').each(function() {
            this.checked = false;
        });
    } else {
        $('[name="CheckboxDOTerkirim"]:checkbox').each(function() {
            this.checked = false;
        });
    }
});

$('#select-terkirim-sebagian').click(function(event) {
    if (this.checked) {
        // Iterate each checkbox
        $('[id="select-terkirim"]:checkbox').each(function() {
            this.checked = false;
        });
        $('[name="CheckboxDOTerkirim"]:checkbox').each(function() {
            this.checked = false;
        });

        $('[id="select-terkirim-sebagian"]:checkbox').each(function() {
            this.checked = true;
        });
        $('[name="CheckboxDOTerkirimSebagian"]:checkbox').each(function() {
            this.checked = true;
        });

        $('[id="select-gagal"]:checkbox').each(function() {
            this.checked = false;
        });
        $('[name="CheckboxDOGagal"]:checkbox').each(function() {
            this.checked = false;
        });
        $('[name="CheckboxDOKirimUlang"]:checkbox').each(function() {
            this.checked = false;
        });
    } else {
        $('[name="CheckboxDOTerkirimSebagian"]:checkbox').each(function() {
            this.checked = false;
        });

        $('[name="slcreason"]').html('');
    }
});

$('#select-gagal').click(function(event) {
    if (this.checked) {
        // Iterate each checkbox
        $('[id="select-terkirim"]:checkbox').each(function() {
            this.checked = false;
        });
        $('[name="CheckboxDOTerkirim"]:checkbox').each(function() {
            this.checked = false;
        });

        $('[id="select-terkirim-sebagian"]:checkbox').each(function() {
            this.checked = false;
        });
        $('[name="CheckboxDOTerkirimSebagian"]:checkbox').each(function() {
            this.checked = false;
        });

        $('[id="select-gagal"]:checkbox').each(function() {
            this.checked = true;
            $(this).trigger('change');
        });
        $('[name="CheckboxDOGagal"]:checkbox').each(function() {
            this.checked = true;
        });
        $('[name="CheckboxDOKirimUlang"]:checkbox').each(function() {
            this.checked = false;
        });

        $('#select-gagal').prop('checked', true);

        ViewAllSlcReason();

    } else {
        $('[name="CheckboxDOGagal"]:checkbox').each(function() {
            this.checked = false;
            $(this).trigger('change');
        });

        $('[name="slcreason"]').html('');
    }
});

$('#select-titipan').click(function(event) {
    if (this.checked) {

        $('[name="CheckboxTitipan"]:checkbox').each(function() {
            this.checked = true;
        });

    } else {
        $('[name="CheckboxTitipan"]:checkbox').each(function() {
            this.checked = false;
        });
    }
});

$(document).ready(
    function() {
        GetClosingPengirimanMenu();
    }
);

function GetClosingPengirimanMenu() {
    var delivery_order_batch_id = $("#delivery_order_batch_id").val();

    $.ajax({
        type: 'POST',
        url: "<?= base_url('WMS/SuratTugasPengiriman/GetClosingPengirimanMenu') ?>",
        data: "delivery_order_batch_id=" + delivery_order_batch_id,
        success: function(response) {
            if (response) {
                ChClosingPengirimanMenu(response);
            }
        }
    });
}

function ChClosingPengirimanMenu(JSONChannel) {
    $("#tabledomenu > tbody").html('');

    var Channel = JSON.parse(JSONChannel);
    var no = 1;
    var list_area = "";

    var delivery_order_batch_id = Channel.ClosingPengirimanHeader[0].delivery_order_batch_id;
    var delivery_order_batch_kode = Channel.ClosingPengirimanHeader[0].delivery_order_batch_kode;
    var delivery_order_batch_tanggal = Channel.ClosingPengirimanHeader[0].delivery_order_batch_tanggal;
    var delivery_order_batch_tanggal_kirim = Channel.ClosingPengirimanHeader[0].delivery_order_batch_tanggal_kirim;
    var tipe_delivery_order_id = Channel.ClosingPengirimanHeader[0].tipe_delivery_order_id;
    var tipe_delivery_order_alias = Channel.ClosingPengirimanHeader[0].tipe_delivery_order_alias;
    var area_id = Channel.ClosingPengirimanHeader[0].area_id;
    var area_nama = Channel.ClosingPengirimanHeader[0].area_nama;
    var delivery_order_batch_status = Channel.ClosingPengirimanHeader[0].delivery_order_batch_status;
    var tipe_ekspedisi_id = Channel.ClosingPengirimanHeader[0].tipe_ekspedisi_id;
    var tipe_ekspedisi_nama = Channel.ClosingPengirimanHeader[0].tipe_ekspedisi_nama;
    var kendaraan_id = Channel.ClosingPengirimanHeader[0].kendaraan_id;
    var kendaraan_model = Channel.ClosingPengirimanHeader[0].kendaraan_model;
    var karyawan_id = Channel.ClosingPengirimanHeader[0].karyawan_id;
    var karyawan_nama = Channel.ClosingPengirimanHeader[0].karyawan_nama;
    var kendaraan_km_akhir = Channel.ClosingPengirimanHeader[0].kendaraan_km_akhir;
    var delivery_order_batch_update_tgl = Channel.ClosingPengirimanHeader[0].delivery_order_batch_update_tgl;

    var total_titipan = $('[name="CheckboxTitipan"]:checked').length;


    for (i = 0; i < Channel.ClosingPengirimanArea.length; i++) {
        if (i < Channel.ClosingPengirimanArea.length - 1) {
            list_area = list_area + Channel.ClosingPengirimanArea[i].area_nama + ", ";
        } else {
            list_area = list_area + Channel.ClosingPengirimanArea[i].area_nama;
        }
    }

    $("#filter_fdjr_no").val(delivery_order_batch_kode);
    $("#filter_fdjr_date").val(delivery_order_batch_tanggal_kirim);
    $("#filter_update_fdjr_date").val(delivery_order_batch_update_tgl);
    $("#filter_fdjr_type").append('<option value="' + tipe_delivery_order_id + '">' + tipe_delivery_order_alias +
        '</option>');
    $("#filter_fdjr_area").val(list_area);
    $("#filter_fdjr_status").append('<option value="' + delivery_order_batch_status + '">' +
        delivery_order_batch_status + '</option>');
    $("#filter_tipe_ekspedisi").append('<option value="' + tipe_ekspedisi_id + '">' + tipe_ekspedisi_nama +
        '</option>');
    $("#filter_fdjr_armada").append('<option value="' + kendaraan_id + '">' + kendaraan_model + '</option>');
    $("#filter_fdjr_driver").append('<option value="' + karyawan_id + '">' + karyawan_nama + '</option>');
    $("#filter_fdjr_km").val(kendaraan_km_akhir);

    $("#txt_jumlah").val(Channel.ClosingPengiriman.length);

    if (delivery_order_batch_status == "Closing Delivery Confirm") {
        // $("#btn_konfirmasi_pengiriman").prop("disabled", true);
        // $("#btn_simpan").prop("disabled", true);
        // $("#btnsimpanaddbiaya").prop("disabled", true);
        // $("#btnaddbiayadetail").prop("disabled", true);

        $("#btn_konfirmasi_pengiriman").hide();
        $("#btn_simpan").hide();
        $("#btnsimpanaddbiaya").hide();
        $("#btnaddbiayadetail").hide();
        $("#cetak").show();

        // $("#filter_fdjr_km").prop("disabled", false);
    } else if (delivery_order_batch_status == "In Process Receiving Outlet") {
        // $("#btn_konfirmasi_pengiriman").prop("disabled", true);
        // $("#btn_simpan").prop("disabled", true);
        // $("#btnsimpanaddbiaya").prop("disabled", true);
        // $("#btnaddbiayadetail").prop("disabled", true);
        // $("#filter_fdjr_km").prop("disabled", false);

        $("#btn_konfirmasi_pengiriman").hide();
        $("#btn_simpan").hide();
        $("#btnsimpanaddbiaya").hide();
        $("#btnaddbiayadetail").hide();
    } else if (delivery_order_batch_status == "In Process Closing") {
        // $("#btn_konfirmasi_pengiriman").prop("disabled", false);
        // $("#btn_simpan").prop("disabled", false);
        // $("#filter_fdjr_km").prop("disabled", false);

        $("#btn_konfirmasi_pengiriman").show();
        $("#btn_simpan").show();
    } else if (delivery_order_batch_status == "in transit") {
        // $("#btn_konfirmasi_pengiriman").prop("disabled", false);
        // $("#btn_simpan").prop("disabled", false);
        // $("#filter_fdjr_km").prop("disabled", false);

        $("#btn_konfirmasi_pengiriman").hide();
        $("#btn_simpan").show();
    } else {
        // $("#btn_konfirmasi_pengiriman").prop("disabled", true);
        // $("#btn_simpan").prop("disabled", true);
        // $("#btnsimpanaddbiaya").prop("disabled", true);
        // $("#btnaddbiayadetail").prop("disabled", true);
        // $("#filter_fdjr_km").prop("disabled", false);
        // location.href = "<?= base_url(); ?>WMS/SuratTugasPengiriman/SuratTugasPengirimanMenu";

        $("#btn_konfirmasi_pengiriman").hide();
        $("#btn_simpan").hide();
        $("#btnsimpanaddbiaya").hide();
        $("#btnaddbiayadetail").hide();
    }

    if (Channel.ClosingPengiriman != 0) {
        // if ($.fn.DataTable.isDataTable('#tabledomenu')) {
        //     $('#tabledomenu').DataTable().destroy();
        // }

        $('#tabledomenu tbody').empty();

        for (i = 0; i < Channel.ClosingPengiriman.length; i++) {
            var delivery_order_batch_id = Channel.ClosingPengiriman[i].delivery_order_batch_id;
            var delivery_order_batch_kode = Channel.ClosingPengiriman[i].delivery_order_batch_kode;
            var delivery_order_id = Channel.ClosingPengiriman[i].delivery_order_id;
            var delivery_order_kode = Channel.ClosingPengiriman[i].delivery_order_kode;
            var delivery_order_tgl_buat_do = Channel.ClosingPengiriman[i].delivery_order_tgl_buat_do;
            var delivery_order_tgl_aktual_kirim = Channel.ClosingPengiriman[i].delivery_order_tgl_aktual_kirim;
            var delivery_order_kirim_nama = Channel.ClosingPengiriman[i].delivery_order_kirim_nama;
            var delivery_order_kirim_alamat = Channel.ClosingPengiriman[i].delivery_order_kirim_alamat;
            var delivery_order_kirim_telp = Channel.ClosingPengiriman[i].delivery_order_kirim_telp;
            var delivery_order_tipe_pembayaran = Channel.ClosingPengiriman[i].delivery_order_tipe_pembayaran;
            var delivery_order_nominal_tunai = parseFloat(Channel.ClosingPengiriman[i].delivery_order_nominal_tunai);
            var delivery_order_jumlah_bayar = parseFloat(Channel.ClosingPengiriman[i].delivery_order_jumlah_bayar);
            var delivery_order_no_urut_rute = Channel.ClosingPengiriman[i].delivery_order_no_urut_rute;
            var delivery_order_status = Channel.ClosingPengiriman[i].delivery_order_status;
            var reason_id = Channel.ClosingPengiriman[i].reason_id;
            var ada_titipan = Channel.ClosingPengiriman[i].ada_titipan;
            var boleh_titip = Channel.ClosingPengiriman[i].boleh_titip;
            var cek_titipan = Channel.ClosingPengiriman[i].cek_titipan;
            var tipe_delivery_order_id = Channel.ClosingPengiriman[i].tipe_delivery_order_id;
            var tipe_delivery_order_alias = Channel.ClosingPengiriman[i].tipe_delivery_order_alias;
            var sales_order_kode = Channel.ClosingPengiriman[i].sales_order_kode;
            var sales_order_no_po = Channel.ClosingPengiriman[i].sales_order_no_po;
            var penerimaan_penjualan_id = Channel.ClosingPengiriman[i].penerimaan_penjualan_id;
            var sudah_btb = Channel.ClosingPengiriman[i].sudah_btb;

            cek_btb.push({
                'delivery_order_id': delivery_order_id,
                'delivery_order_kode': delivery_order_kode,
                'penerimaan_penjualan_id': penerimaan_penjualan_id,
                'sudah_btb': sudah_btb
            })

            // if (delivery_order_jumlah_bayar == null || delivery_order_jumlah_bayar == "") {
            //     delivery_order_jumlah_bayar = 0;
            // }

            var is_ada_titipan = '';
            var is_ada_titipan_checked = '';
            var is_boleh_titip = boleh_titip == 0 ? 'disabled' : '';

            if (delivery_order_batch_status == "In Process Receiving Outlet") {
                if (ada_titipan == 1) {
                    is_ada_titipan =
                        '	<td><a target="_blank" href="<?php echo base_url() ?>WMS/SuratTugasPengiriman/ProsesDOReturDetail/?delivery_order_batch_id=' +
                        delivery_order_batch_id + '&delivery_order_id=' + delivery_order_id +
                        '" type="button" class="btn btn-primary" id="btn_proses_do_retur_' + i +
                        '"><span name="CAPTION-LIHAT">Lihat</span></a></td>';
                } else {
                    is_ada_titipan = '	<td></td>';
                }
            } else {
                if (ada_titipan == 1) {
                    if (delivery_order_status == "rescheduled") {
                        is_ada_titipan =
                            '	<td style="display:none;"><a target="_blank" href="<?php echo base_url() ?>WMS/Distribusi/DeliveryOrderDraft/DOKirimUlang/?delivery_order_batch_id=' +
                            delivery_order_batch_id + '&delivery_order_id=' + delivery_order_id +
                            '" type="button" class="btn btn-primary" id="btn_proses_do_kirim_ulang_' + i +
                            '"><span name="CAPTION-KIRIMULANG">Kirim Ulang</span></a> <button type="button" class="btn btn-primary" id="btn_proses_do_kirim_ulang_' +
                            i + '" onClick="CekDOKirimUlang(\'' + delivery_order_batch_id + '\',\'' +
                            delivery_order_id + '\')"><span name="CAPTION-KIRIMULANG">Kirim Ulang</span></button></td>';

                    } else {
                        is_ada_titipan =
                            '	<td><a target="_blank" href="<?php echo base_url() ?>WMS/SuratTugasPengiriman/ProsesDOReturMenu/?delivery_order_batch_id=' +
                            delivery_order_batch_id + '&delivery_order_id=' + delivery_order_id +
                            '" type="button" class="btn btn-primary" id="btn_proses_do_retur_' + i +
                            '"><span name="CAPTION-PROSESDORETUR">Proses DO Retur</span></a></td>';
                    }
                } else {
                    if (delivery_order_status == "rescheduled") {
                        is_ada_titipan =
                            '	<td style="display:none;"><button type="button" class="btn btn-primary" id="btn_proses_do_kirim_ulang_' +
                            i + '" onClick="CekDOKirimUlang(\'' + delivery_order_batch_id + '\',\'' +
                            delivery_order_id + '\')"><span name="CAPTION-KIRIMULANG">Kirim Ulang</span></button></td>';

                    } else {
                        is_ada_titipan = '	<td></td>';
                    }
                }
            }

            if (ada_titipan == 0) {
                is_ada_titipan_checked = '';
            } else {
                is_ada_titipan_checked = 'checked';
            }

            var strmenu = '';
            strmenu = strmenu + '<tr>';
            strmenu = strmenu + '	<td>';
            strmenu = strmenu + no;
            strmenu = strmenu + '		<input type="hidden" id="ada_titipan_' + i + '" value="' + ada_titipan + '">';
            strmenu = strmenu + '		<input type="hidden" id="boleh_titip_' + i + '" value="' + boleh_titip + '">';
            strmenu = strmenu + '		<input type="hidden" id="cek_titipan_' + i + '" value="' + cek_titipan + '">';
            strmenu = strmenu + '	</td>';
            strmenu = strmenu + '	<td>' + delivery_order_tgl_buat_do + '</td>';
            strmenu = strmenu + '	<td>' + delivery_order_kode + '</td>';
            strmenu = strmenu + '	<td>' + sales_order_kode + '</td>';
            strmenu = strmenu + '	<td>' + sales_order_no_po + '</td>';
            strmenu = strmenu + '	<td>' + tipe_delivery_order_alias + '</td>';
            strmenu = strmenu + '	<td>' + delivery_order_kirim_nama + '</td>';
            strmenu = strmenu + '	<td>' + delivery_order_kirim_alamat + '</td>';
            strmenu = strmenu + '	<td>' + delivery_order_kirim_telp + '</td>';
            // strmenu = strmenu + '	<td>' + delivery_order_tipe_pembayaran + '</td>';
            // strmenu = strmenu + '	<td >' + parseFloat(delivery_order_nominal_tunai) + '</td>';

            if (delivery_order_batch_status == "in transit" || delivery_order_batch_status == "In Process Closing") {
                if (tipe_delivery_order_alias == "Retur") {
                    strmenu = strmenu + '	<td><select id="slc_tipe_pembayaran' + i +
                        '" name="slctipepembayaran" class="form-control" style="width:100%" onChange="SetPembayaranByTipe(\'' +
                        i + '\',\'' + delivery_order_id + '\',this.value,\'' + delivery_order_nominal_tunai + '\',\'' +
                        Channel.ClosingPengiriman.length + '\',\'' + tipe_delivery_order_alias +
                        '\')" disabled></select></td>';
                } else {
                    strmenu = strmenu + '	<td><select id="slc_tipe_pembayaran' + i +
                        '" name="slctipepembayaran" class="form-control" style="width:100%" onChange="SetPembayaranByTipe(\'' +
                        i + '\',\'' + delivery_order_id + '\',this.value,\'' + delivery_order_nominal_tunai + '\',\'' +
                        Channel.ClosingPengiriman.length + '\',\'' + tipe_delivery_order_alias + '\')"></select></td>';
                }
            } else {
                strmenu = strmenu + '	<td><select id="slc_tipe_pembayaran' + i +
                    '" name="slctipepembayaran" class="form-control" style="width:100%" onChange="SetPembayaranByTipe(\'' +
                    i + '\',\'' + delivery_order_id + '\',this.value,\'' + delivery_order_nominal_tunai + '\',\'' +
                    Channel.ClosingPengiriman.length + '\',\'' + tipe_delivery_order_alias +
                    '\')" disabled></select></td>';
            }

            if (isNaN(delivery_order_nominal_tunai)) {
                strmenu = strmenu + '	<td >0</td>';
            } else {
                strmenu = strmenu + '	<td>' + formatNumber(parseFloat(delivery_order_nominal_tunai)) + '</td>';
            }

            if (delivery_order_batch_status == "in transit" || delivery_order_batch_status == "In Process Closing") {
                if (isNaN(delivery_order_jumlah_bayar)) {
                    if (tipe_delivery_order_alias == "Retur") {
                        strmenu = strmenu +
                            '	<td style="width:15%;"><input style="width:100px;" onChange="ReCalculate(' + Channel
                            .ClosingPengiriman.length +
                            ')" onkeyup="formatNominal(this)" onkeypress="return event.charCode >= 48 && event.charCode<=57" class="form-control" type="text" id="nominal_tunai_' +
                            i + '" value="0" placeholder="0" disabled></td>';
                    } else {

                        strmenu = strmenu +
                            '	<td style="width:15%;"><input style="width:100px;" onChange="ReCalculate(' + Channel
                            .ClosingPengiriman.length +
                            ')" onkeyup="formatNominal(this)" onkeypress="return event.charCode >= 48 && event.charCode<=57" class="form-control" type="text" id="nominal_tunai_' +
                            i +
                            '" value="0" placeholder="0"></td>';

                    }
                } else {
                    if (tipe_delivery_order_alias == "Retur") {
                        strmenu = strmenu +
                            '	<td style="width:15%;"><input style="width:100px;" onChange="ReCalculate(' + Channel
                            .ClosingPengiriman.length +
                            ')" onkeyup="formatNominal(this)" onkeypress="return event.charCode >= 48 && event.charCode<=57" class="form-control" type="text" id="nominal_tunai_' +
                            i +
                            '" value="0" placeholder="0" disabled></td>';
                    } else {
                        if (delivery_order_status == "not delivered") {
                            strmenu = strmenu +
                                '	<td style="width:15%;"><input style="width:100px;" onChange="ReCalculate(' + Channel
                                .ClosingPengiriman.length +
                                ')" onkeyup="formatNominal(this)" onkeypress="return event.charCode >= 48 && event.charCode<=57" class="form-control" type="text" id="nominal_tunai_' +
                                i +
                                '" value="0" placeholder="0"></td>';
                        } else {
                            strmenu = strmenu +
                                '	<td style="width:15%;"><input style="width:100px;" onChange="ReCalculate(' + Channel
                                .ClosingPengiriman.length +
                                ')" onkeyup="formatNominal(this)" onkeypress="return event.charCode >= 48 && event.charCode<=57" class="form-control" type="text" id="nominal_tunai_' +
                                i +
                                '" value="' + formatNumber(parseFloat(delivery_order_jumlah_bayar)) +
                                '" placeholder="0"></td>';
                        }

                    }
                }
            } else {
                if (isNaN(delivery_order_jumlah_bayar)) {
                    if (tipe_delivery_order_alias == "Retur") {

                        strmenu = strmenu +
                            '	<td style="width:15%;"><input style="width:100px;" onChange="ReCalculate(' + Channel
                            .ClosingPengiriman.length +
                            ')" onkeyup="formatNominal(this)" onkeypress="return event.charCode >= 48 && event.charCode<=57" class="form-control" type="text" id="nominal_tunai_' +
                            i +
                            '" value="" placeholder="0" readonly></td>';
                    } else {

                        strmenu = strmenu +
                            '	<td style="width:15%;"><input style="width:100px;" onChange="ReCalculate(' + Channel
                            .ClosingPengiriman.length +
                            ')" onkeyup="formatNominal(this)" onkeypress="return event.charCode >= 48 && event.charCode<=57" class="form-control" type="text" id="nominal_tunai_' +
                            i +
                            '" value="" placeholder="0" readonly></td>';
                    }
                } else {
                    if (tipe_delivery_order_alias == "Retur") {
                        strmenu = strmenu +
                            '	<td style="width:15%;"><input style="width:100px;" onChange="ReCalculate(' + Channel
                            .ClosingPengiriman.length +
                            ')" onkeyup="formatNominal(this)" onkeypress="return event.charCode >= 48 && event.charCode<=57" class="form-control" type="text" id="nominal_tunai_' +
                            i +
                            '" value="0" placeholder="0" readonly></td>';
                    } else {
                        if (delivery_order_status == 'not delivered') {
                            strmenu = strmenu +
                                '	<td style="width:15%;"><input style="width:100px;" onChange="ReCalculate(' + Channel
                                .ClosingPengiriman.length +
                                ')" onkeyup="formatNominal(this)" onkeypress="return event.charCode >= 48 && event.charCode<=57" class="form-control" type="text" id="nominal_tunai_' +
                                i +
                                '" value="0" placeholder="0" readonly></td>';
                        } else {
                            strmenu = strmenu +
                                '	<td style="width:15%;"><input style="width:100px;" onChange="ReCalculate(' + Channel
                                .ClosingPengiriman.length +
                                ')" onkeyup="formatNominal(this)" onkeypress="return event.charCode >= 48 && event.charCode<=57" class="form-control" type="text" id="nominal_tunai_' +
                                i +
                                '" value="' + formatNumber(parseFloat(delivery_order_jumlah_bayar)) +
                                '" placeholder="0" readonly></td>';
                        }
                    }
                }
            }

            strmenu = strmenu + '<td class="text-center"><button type="button" data-id="' + delivery_order_id +
                '" id="btnaddbiaya" class="btn btn-warning btnaddbiaya" data-status="' + delivery_order_batch_status +
                '"><i class="fa fa-plus"></i></button></td>';

            strmenu = strmenu + '	<td>' + delivery_order_no_urut_rute + '</td>';

            if (delivery_order_batch_status == "in transit" || delivery_order_batch_status == "In Process Closing") {
                if (delivery_order_status == "delivered") {
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOTerkirim" id="chk_do_terkirim_' +
                        i + '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' +
                        'terkirim' +
                        '\')" checked ></td>';
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOTerkirimSebagian" id="chk_do_terkirim_sebagian_' +
                        i + '" value="' + delivery_order_id + '" onclick="ViewFormTerkirimSebagian(\'' +
                        delivery_order_id +
                        '\',\'' + i + '\')" ></td>';
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOGagal" id="chk_do_gagal_' + i +
                        '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' + 'gagal' +
                        '\')" ></td>';
                    strmenu = strmenu +
                        '	<td style="display:none;"><input type="checkbox" class="CheckboxDO" name="CheckboxDOKirimUlang" id="chk_do_kirim_ulang_' +
                        i +
                        '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' + 'kirim ulang' +
                        '\')"></td>';
                    strmenu = strmenu + '	<td><select id="slc_reason' + i +
                        '" name="slcreason" class="form-control" style="width:100%"></select></td>';
                    strmenu = strmenu + '	<td><input type="checkbox" name="CheckboxTitipan" id="chk_titipan_' + i +
                        '" value="' + delivery_order_id + '" ' + is_ada_titipan_checked + ' ></td>';
                    strmenu = strmenu + is_ada_titipan;

                    $('#select-terkirim').prop("disabled", false);
                    $('#select-gagal').prop("disabled", false);
                    $('#select-titipan').prop("disabled", false);

                } else if (delivery_order_status == "partially delivered") {
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOTerkirim" id="chk_do_terkirim_' +
                        i + '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' +
                        'terkirim' +
                        '\')" ></td>';
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOTerkirimSebagian" id="chk_do_terkirim_sebagian_' +
                        i + '" value="' + delivery_order_id + '" onclick="ViewFormTerkirimSebagian(\'' +
                        delivery_order_id +
                        '\',\'' + i + '\')" checked ></td>';
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOGagal" id="chk_do_gagal_' + i +
                        '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' + 'gagal' +
                        '\')" ></td>';
                    strmenu = strmenu +
                        '	<td style="display:none;"><input type="checkbox" class="CheckboxDO" name="CheckboxDOKirimUlang" id="chk_do_kirim_ulang_' +
                        i +
                        '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' + 'kirim ulang' +
                        '\')"></td>';
                    strmenu = strmenu + '	<td><select id="slc_reason' + i +
                        '" name="slcreason" class="form-control" style="width:100%" readonly></select></td>';
                    strmenu = strmenu + '	<td><input type="checkbox" name="CheckboxTitipan" id="chk_titipan_' + i +
                        '" value="' + delivery_order_id + '" ' + is_ada_titipan_checked + ' ></td>';
                    strmenu = strmenu + is_ada_titipan;

                    $('#select-terkirim').prop("disabled", false);
                    $('#select-gagal').prop("disabled", false);
                    $('#select-titipan').prop("disabled", false);

                } else if (delivery_order_status == "not delivered") {
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOTerkirim" id="chk_do_terkirim_' +
                        i + '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' +
                        'terkirim' +
                        '\')" ></td>';
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOTerkirimSebagian" id="chk_do_terkirim_sebagian_' +
                        i + '" value="' + delivery_order_id + '" onclick="ViewFormTerkirimSebagian(\'' +
                        delivery_order_id +
                        '\',\'' + i + '\')" ></td>';
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOGagal" id="chk_do_gagal_' + i +
                        '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' + 'gagal' +
                        '\')" checked ></td>';
                    strmenu = strmenu +
                        '	<td style="display:none;"><input type="checkbox" class="CheckboxDO" name="CheckboxDOKirimUlang" id="chk_do_kirim_ulang_' +
                        i +
                        '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' + 'kirim ulang' +
                        '\')"></td>';
                    strmenu = strmenu + '	<td><select id="slc_reason' + i +
                        '" name="slcreason" class="form-control" style="width:100%" readonly></select></td>';
                    strmenu = strmenu + '	<td><input type="checkbox" name="CheckboxTitipan" id="chk_titipan_' + i +
                        '" value="' + delivery_order_id + '" ' + is_ada_titipan_checked + ' ></td>';
                    strmenu = strmenu + is_ada_titipan;

                    ViewSlcReasonByDo(i, reason_id);

                    $('#select-terkirim').prop("disabled", false);
                    $('#select-gagal').prop("disabled", false);
                    $('#select-titipan').prop("disabled", false);

                } else if (delivery_order_status == "rescheduled") {
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOTerkirim" id="chk_do_terkirim_' +
                        i + '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' +
                        'terkirim' +
                        '\')" ></td>';
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOTerkirimSebagian" id="chk_do_terkirim_sebagian_' +
                        i + '" value="' + delivery_order_id + '" onclick="ViewFormTerkirimSebagian(\'' +
                        delivery_order_id +
                        '\',\'' + i + '\')" ></td>';
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOGagal" id="chk_do_gagal_' + i +
                        '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' + 'gagal' +
                        '\')"></td>';
                    strmenu = strmenu +
                        '	<td style="display:none;"><input type="checkbox" class="CheckboxDO" name="CheckboxDOKirimUlang" id="chk_do_kirim_ulang_' +
                        i +
                        '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' + 'kirim ulang' +
                        '\')" checked></td>';
                    strmenu = strmenu + '	<td><select id="slc_reason' + i +
                        '" name="slcreason" class="form-control" style="width:100%" readonly></select></td>';
                    strmenu = strmenu + '	<td><input type="checkbox" name="CheckboxTitipan" id="chk_titipan_' + i +
                        '" value="' + delivery_order_id + '" ' + is_ada_titipan_checked + ' ></td>';
                    strmenu = strmenu + is_ada_titipan;

                    ViewSlcReasonByDo(i, reason_id);

                    $('#select-terkirim').prop("disabled", false);
                    $('#select-gagal').prop("disabled", false);
                    $('#select-titipan').prop("disabled", false);

                } else if (delivery_order_status == "retur") {
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOTerkirim" id="chk_do_terkirim_' +
                        i + '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' +
                        'terkirim' +
                        '\')" ></td>';
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOTerkirimSebagian" id="chk_do_terkirim_sebagian_' +
                        i + '" value="' + delivery_order_id + '" onclick="ViewFormTerkirimSebagian(\'' +
                        delivery_order_id +
                        '\',\'' + i + '\')" ></td>';
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOGagal" id="chk_do_gagal_' + i +
                        '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' + 'gagal' +
                        '\')" ></td>';
                    strmenu = strmenu +
                        '	<td style="display:none;"><input type="checkbox" class="CheckboxDO" name="CheckboxDOKirimUlang" id="chk_do_kirim_ulang_' +
                        i +
                        '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' + 'kirim ulang' +
                        '\')"></td>';
                    strmenu = strmenu + '	<td><select id="slc_reason' + i +
                        '" name="slcreason" class="form-control" style="width:100%" readonly></select></td>';
                    strmenu = strmenu + '	<td><input type="checkbox" name="CheckboxTitipan" id="chk_titipan_' + i +
                        '" value="' + delivery_order_id + '" ' + is_ada_titipan_checked + ' ></td>';
                    strmenu = strmenu + is_ada_titipan;

                    $('#select-terkirim').prop("disabled", false);
                    $('#select-gagal').prop("disabled", false);
                    $('#select-titipan').prop("disabled", false);

                    $("#txt_jumlah").val(Channel.ClosingPengiriman.length - 1);

                } else {
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOTerkirim" id="chk_do_terkirim_' +
                        i + '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' +
                        'terkirim' +
                        '\')"></td>';
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOTerkirimSebagian" id="chk_do_terkirim_sebagian_' +
                        i + '" value="' + delivery_order_id + '" onclick="ViewFormTerkirimSebagian(\'' +
                        delivery_order_id +
                        '\',\'' + i + '\')"></td>';
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOGagal" id="chk_do_gagal_' + i +
                        '" value="' + delivery_order_id + '" ' + is_boleh_titip + ' onChange="ViewSlcReason(\'' + i +
                        '\',\'' + 'gagal' + '\')"></td>';
                    strmenu = strmenu +
                        '	<td style="display:none;"><input type="checkbox" class="CheckboxDO" name="CheckboxDOKirimUlang" id="chk_do_kirim_ulang_' +
                        i +
                        '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' + 'kirim ulang' +
                        '\')"></td>';
                    strmenu = strmenu + '	<td><select id="slc_reason' + i +
                        '" name="slcreason" class="form-control" style="width:100%" ></select></td>';
                    strmenu = strmenu + '	<td><input type="checkbox" name="CheckboxTitipan" id="chk_titipan_' + i +
                        '" value="' + delivery_order_id + '"></td>';
                    strmenu = strmenu + is_ada_titipan;

                    $('#select-titipan').prop("disabled", false);
                }

                // strmenu = strmenu + '	<td><a href="<?php echo base_url(); ?>Distribusi/PermintaanBarang/PickingDetail/'+ picking_list_id +'" class="btn btn-link" target="_blank">Lihat</a></td>';
                strmenu = strmenu + '</tr>';

                no++;
            } else {

                if (delivery_order_status == "delivered") {
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOTerkirim" id="chk_do_terkirim_' +
                        i + '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' +
                        'terkirim' +
                        '\')" checked disabled></td>';
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOTerkirimSebagian" id="chk_do_terkirim_sebagian_' +
                        i + '" value="' + delivery_order_id + '" onclick="ViewFormTerkirimSebagian(\'' +
                        delivery_order_id +
                        '\',\'' + i + '\')" disabled></td>';
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOGagal" id="chk_do_gagal_' + i +
                        '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' + 'gagal' +
                        '\')" disabled></td>';
                    strmenu = strmenu +
                        '	<td style="display:none;"><input type="checkbox" class="CheckboxDO" name="CheckboxDOKirimUlang" id="chk_do_kirim_ulang_' +
                        i +
                        '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' + 'kirim ulang' +
                        '\')" disabled></td>';
                    strmenu = strmenu + '	<td><select id="slc_reason' + i +
                        '" name="slcreason" class="form-control" style="width:100%" readonly disabled></select></td>';
                    strmenu = strmenu + '	<td><input type="checkbox" name="CheckboxTitipan" id="chk_titipan_' + i +
                        '" value="' + delivery_order_id + '" ' + is_ada_titipan_checked + ' disabled></td>';
                    strmenu = strmenu + is_ada_titipan;

                    $('#select-terkirim').prop("disabled", false);
                    $('#select-gagal').prop("disabled", false);
                    $('#select-titipan').prop("disabled", false);

                } else if (delivery_order_status == "partially delivered") {
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOTerkirim" id="chk_do_terkirim_' +
                        i + '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' +
                        'terkirim' +
                        '\')" disabled></td>';
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOTerkirimSebagian" id="chk_do_terkirim_sebagian_' +
                        i + '" value="' + delivery_order_id + '" onclick="ViewFormTerkirimSebagian(\'' +
                        delivery_order_id +
                        '\',\'' + i + '\')" checked disabled></td>';
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOGagal" id="chk_do_gagal_' + i +
                        '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' + 'gagal' +
                        '\')" disabled></td>';
                    strmenu = strmenu +
                        '	<td style="display:none;"><input type="checkbox" class="CheckboxDO" name="CheckboxDOKirimUlang" id="chk_do_kirim_ulang_' +
                        i +
                        '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' + 'kirim ulang' +
                        '\')" disabled></td>';
                    strmenu = strmenu + '	<td><select id="slc_reason' + i +
                        '" name="slcreason" class="form-control" style="width:100%" readonly disabled></select></td>';
                    strmenu = strmenu + '	<td><input type="checkbox" name="CheckboxTitipan" id="chk_titipan_' + i +
                        '" value="' + delivery_order_id + '" ' + is_ada_titipan_checked + ' disabled></td>';
                    strmenu = strmenu + is_ada_titipan;

                    $('#select-terkirim').prop("disabled", false);
                    $('#select-gagal').prop("disabled", false);
                    $('#select-titipan').prop("disabled", false);

                } else if (delivery_order_status == "not delivered") {
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOTerkirim" id="chk_do_terkirim_' +
                        i + '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' +
                        'terkirim' +
                        '\')" disabled></td>';
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOTerkirimSebagian" id="chk_do_terkirim_sebagian_' +
                        i + '" value="' + delivery_order_id + '" onclick="ViewFormTerkirimSebagian(\'' +
                        delivery_order_id +
                        '\',\'' + i + '\')" disabled></td>';
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOGagal" id="chk_do_gagal_' + i +
                        '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' + 'gagal' +
                        '\')" checked disabled></td>';
                    strmenu = strmenu +
                        '	<td style="display:none;"><input type="checkbox" class="CheckboxDO" name="CheckboxDOKirimUlang" id="chk_do_kirim_ulang_' +
                        i +
                        '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' + 'kirim ulang' +
                        '\')" disabled></td>';
                    strmenu = strmenu + '	<td><select id="slc_reason' + i +
                        '" name="slcreason" class="form-control" style="width:100%" readonly disabled></select></td>';
                    strmenu = strmenu + '	<td><input type="checkbox" name="CheckboxTitipan" id="chk_titipan_' + i +
                        '" value="' + delivery_order_id + '" ' + is_ada_titipan_checked + ' disabled></td>';
                    strmenu = strmenu + is_ada_titipan;

                    ViewSlcReasonByDo(i, reason_id);

                    $('#select-terkirim').prop("disabled", false);
                    $('#select-gagal').prop("disabled", false);
                    $('#select-titipan').prop("disabled", false);

                } else if (delivery_order_status == "rescheduled") {
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOTerkirim" id="chk_do_terkirim_' +
                        i + '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' +
                        'terkirim' +
                        '\')" disabled></td>';
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOTerkirimSebagian" id="chk_do_terkirim_sebagian_' +
                        i + '" value="' + delivery_order_id + '" onclick="ViewFormTerkirimSebagian(\'' +
                        delivery_order_id +
                        '\',\'' + i + '\')" disabled></td>';
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOGagal" id="chk_do_gagal_' + i +
                        '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' + 'gagal' +
                        '\')" disabled></td>';
                    strmenu = strmenu +
                        '	<td style="display:none;"><input type="checkbox" class="CheckboxDO" name="CheckboxDOKirimUlang" id="chk_do_kirim_ulang_' +
                        i +
                        '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' + 'kirim ulang' +
                        '\')" checked disabled></td>';
                    strmenu = strmenu + '	<td><select id="slc_reason' + i +
                        '" name="slcreason" class="form-control" style="width:100%" readonly disabled></select></td>';
                    strmenu = strmenu + '	<td><input type="checkbox" name="CheckboxTitipan" id="chk_titipan_' + i +
                        '" value="' + delivery_order_id + '" ' + is_ada_titipan_checked + ' disabled></td>';
                    strmenu = strmenu + is_ada_titipan;

                    ViewSlcReasonByDo(i, reason_id);

                    $('#select-terkirim').prop("disabled", false);
                    $('#select-gagal').prop("disabled", false);
                    $('#select-titipan').prop("disabled", false);

                } else if (delivery_order_status == "retur") {
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOTerkirim" id="chk_do_terkirim_' +
                        i + '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' +
                        'terkirim' +
                        '\')" disabled></td>';
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOTerkirimSebagian" id="chk_do_terkirim_sebagian_' +
                        i + '" value="' + delivery_order_id + '" onclick="ViewFormTerkirimSebagian(\'' +
                        delivery_order_id +
                        '\',\'' + i + '\')" disabled></td>';
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOGagal" id="chk_do_gagal_' + i +
                        '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' + 'gagal' +
                        '\')" disabled></td>';
                    strmenu = strmenu +
                        '	<td style="display:none;"><input type="checkbox" class="CheckboxDO" name="CheckboxDOKirimUlang" id="chk_do_kirim_ulang_' +
                        i +
                        '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' + 'kirim ulang' +
                        '\')" disabled></td>';
                    strmenu = strmenu + '	<td><select id="slc_reason' + i +
                        '" name="slcreason" class="form-control" style="width:100%" readonly disabled></select></td>';
                    strmenu = strmenu + '	<td><input type="checkbox" name="CheckboxTitipan" id="chk_titipan_' + i +
                        '" value="' + delivery_order_id + '" ' + is_ada_titipan_checked + ' disabled></td>';
                    strmenu = strmenu + is_ada_titipan;

                    $('#select-terkirim').prop("disabled", false);
                    $('#select-gagal').prop("disabled", false);
                    $('#select-titipan').prop("disabled", false);

                    $("#txt_jumlah").val(Channel.ClosingPengiriman.length - 1);

                } else {
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOTerkirim" id="chk_do_terkirim_' +
                        i + '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' +
                        'terkirim' +
                        '\')"></td>';
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOTerkirimSebagian" id="chk_do_terkirim_sebagian_' +
                        i + '" value="' + delivery_order_id + '" onclick="ViewFormTerkirimSebagian(\'' +
                        delivery_order_id +
                        '\',\'' + i + '\')"></td>';
                    strmenu = strmenu +
                        '	<td><input type="checkbox" class="CheckboxDO" name="CheckboxDOGagal" id="chk_do_gagal_' + i +
                        '" value="' + delivery_order_id + '" ' + is_boleh_titip + ' onChange="ViewSlcReason(\'' + i +
                        '\',\'' + 'gagal' + '\')"></td>';
                    strmenu = strmenu +
                        '	<td style="display:none;"><input type="checkbox" class="CheckboxDO" name="CheckboxDOKirimUlang" id="chk_do_kirim_ulang_' +
                        i +
                        '" value="' + delivery_order_id + '" onChange="ViewSlcReason(\'' + i + '\',\'' + 'kirim ulang' +
                        '\')"></td>';
                    strmenu = strmenu + '	<td><select id="slc_reason' + i +
                        '" name="slcreason" class="form-control" style="width:100%" ></select></td>';
                    strmenu = strmenu + '	<td><input type="checkbox" name="CheckboxTitipan" id="chk_titipan_' + i +
                        '" value="' + delivery_order_id + '"></td>';
                    strmenu = strmenu + is_ada_titipan;

                    $('#select-titipan').prop("disabled", false);
                }

                // strmenu = strmenu + '	<td><a href="<?php echo base_url(); ?>Distribusi/PermintaanBarang/PickingDetail/'+ picking_list_id +'" class="btn btn-link" target="_blank">Lihat</a></td>';
                strmenu = strmenu + '</tr>';

                no++;
            }

            $("#tabledomenu > tbody").append(strmenu);

            total_tagihan += parseFloat(delivery_order_nominal_tunai);
            total_bayar += isNaN(parseFloat($("#nominal_tunai_" + i).val().replaceAll(",", ""))) ? 0 : parseFloat($(
                "#nominal_tunai_" + i).val().replaceAll(",", ""));

            // console.log(parseFloat(delivery_order_nominal_tunai));
        }

        for (i = 0; i < Channel.ClosingPengiriman.length; i++) {
            var delivery_order_batch_id = Channel.ClosingPengiriman[i].delivery_order_batch_id;
            var delivery_order_batch_kode = Channel.ClosingPengiriman[i].delivery_order_batch_kode;
            var delivery_order_id = Channel.ClosingPengiriman[i].delivery_order_id;
            var delivery_order_kode = Channel.ClosingPengiriman[i].delivery_order_kode;
            var delivery_order_tgl_buat_do = Channel.ClosingPengiriman[i].delivery_order_tgl_buat_do;
            var delivery_order_tgl_aktual_kirim = Channel.ClosingPengiriman[i].delivery_order_tgl_aktual_kirim;
            var delivery_order_kirim_nama = Channel.ClosingPengiriman[i].delivery_order_kirim_nama;
            var delivery_order_kirim_alamat = Channel.ClosingPengiriman[i].delivery_order_kirim_alamat;
            var delivery_order_kirim_telp = Channel.ClosingPengiriman[i].delivery_order_kirim_telp;
            var delivery_order_tipe_pembayaran = Channel.ClosingPengiriman[i].delivery_order_tipe_pembayaran;
            var delivery_order_nominal_tunai = parseFloat(Channel.ClosingPengiriman[i].delivery_order_nominal_tunai);
            var delivery_order_jumlah_bayar = parseFloat(Channel.ClosingPengiriman[i].delivery_order_jumlah_bayar);
            var delivery_order_no_urut_rute = Channel.ClosingPengiriman[i].delivery_order_no_urut_rute;
            var delivery_order_status = Channel.ClosingPengiriman[i].delivery_order_status;
            var reason_id = Channel.ClosingPengiriman[i].reason_id;
            var ada_titipan = Channel.ClosingPengiriman[i].ada_titipan;
            var boleh_titip = Channel.ClosingPengiriman[i].boleh_titip;
            var cek_titipan = Channel.ClosingPengiriman[i].cek_titipan;

            $.ajax({
                async: false,
                type: 'POST',
                url: "<?= base_url('WMS/SuratTugasPengiriman/GetTipePembayaran') ?>",
                dataType: "json",
                success: function(response) {
                    if (response) {
                        $("#slc_tipe_pembayaran" + i).html('');

                        $.each(response, function(i2, v2) {
                            $("#slc_tipe_pembayaran" + i).append(
                                `<option value="${v2.tipe_pembayaran_id}" ${delivery_order_tipe_pembayaran == v2.tipe_pembayaran_id ? 'selected' : ''}>${v2.tipe_pembayaran_nama}</option>`
                            );
                        });
                    }
                }
            });
        }

        $("#total_tagihan").html('');
        $("#total_bayar").html('');

        $("#total_tagihan").append(formatNumber(total_tagihan));
        $("#total_bayar").append(formatNumber(total_bayar));

        // $("#tabledomenu > tbody").append(`
        //     <tr style="background-color:#8F43EE;font-weight: bold;color:white;">
        //         <td class="text-center" colspan="7">Grand Total</td>
        //         <td class="text-center"><span id="total_tagihan">${formatNumber(total_tagihan)}</span></td>
        //         <td class="text-center"><span id="total_bayar">${formatNumber(total_bayar)}</span></td>
        //         <td class="text-center" colspan="8"></td>
        //     <tr>
        // `);

        $("#loadingview").hide();

        // $('#tabledomenu').DataTable({
        //     "lengthMenu": [
        //         [-1],
        //         ["All"]
        //     ],
        //     "ordering": false
        // });
    } else {
        // $('#tabledomenu').DataTable().clear().draw();
        // ResetForm();
    }
}

function view_btn_proses_do_retur(i) {
    var checkBox = document.getElementById("chk_titipan_" + i).checked;

    if (checkBox == true) {
        $("#btn_proses_do_retur_" + i).show();
    } else {
        $("#btn_proses_do_retur_" + i).hide();
    }
}

function ViewSlcReason(i, status) {
    var checkBox = document.getElementById("chk_do_gagal_" + i);
    var checkBox2 = document.getElementById("chk_do_terkirim_sebagian_" + i);
    var checkBox3 = document.getElementById("chk_do_terkirim_" + i);
    var checkBox4 = document.getElementById("chk_do_kirim_ulang_" + i);
    var delivery_order_batch_id = $("#delivery_order_batch_id").val();

    if (status == "terkirim") {
        $('#select-terkirim').prop('checked', false);
        $('#select-terkirim-sebagian').prop('checked', false);
        $('#select-gagal').prop('checked', false);

        $("#chk_do_gagal_" + i).prop('checked', false);
        $("#chk_do_terkirim_sebagian_" + i).prop('checked', false);
        $("#chk_do_kirim_ulang_" + i).prop('checked', false);
    } else if (status == "gagal") {
        $('#select-terkirim').prop('checked', false);
        $('#select-terkirim-sebagian').prop('checked', false);
        $('#select-gagal').prop('checked', false);

        $("#chk_do_terkirim_" + i).prop('checked', false);
        $("#chk_do_terkirim_sebagian_" + i).prop('checked', false);
        $("#chk_do_kirim_ulang_" + i).prop('checked', false);
    } else if (status == "kirim ulang") {
        $('#select-terkirim').prop('checked', false);
        $('#select-terkirim-sebagian').prop('checked', false);
        $('#select-gagal').prop('checked', false);

        $("#chk_do_terkirim_" + i).prop('checked', false);
        $("#chk_do_terkirim_sebagian_" + i).prop('checked', false);
        $("#chk_do_gagal_" + i).prop('checked', false);
    }

    if (checkBox.checked == true || checkBox4.checked == true) {
        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/SuratTugasPengiriman/GetReason') ?>",
            dataType: "json",
            success: function(response) {
                if (response) {
                    $("#slc_reason" + i).html('');
                    if (response.Reason != 0) {
                        for (x = 0; x < response.Reason.length; x++) {
                            reason_id = response.Reason[x].reason_id;
                            reason_keterangan = response.Reason[x].reason_keterangan;

                            $("#slc_reason" + i).append('<option value="' + reason_id + '">' +
                                reason_keterangan + '</option>');
                        }
                    }

                    $("#slc_reason" + i).select2();
                }
            }
        });

    } else if (checkBox3.checked == true) {
        $("#slc_reason" + i).html('');
        $("#slc_reason" + i).append('<option value=""></option>');

    } else {
        $("#slc_reason" + i).html('');
        $("#slc_reason" + i).append('<option value=""></option>');

    }

}

function ViewSlcReasonByDo(i, reason_id_do) {

    $.ajax({
        type: 'POST',
        url: "<?= base_url('WMS/SuratTugasPengiriman/GetReason') ?>",
        dataType: "json",
        success: function(response) {
            if (response) {
                $("#slc_reason" + i).html('');
                if (response.Reason != 0) {
                    for (x = 0; x < response.Reason.length; x++) {
                        reason_id = response.Reason[x].reason_id;
                        reason_keterangan = response.Reason[x].reason_keterangan;

                        if (reason_id_do == reason_id) {
                            $("#slc_reason" + i).append('<option value="' + reason_id + '" selected>' +
                                reason_keterangan + '</option>');
                        } else {
                            $("#slc_reason" + i).append('<option value="' + reason_id + '">' +
                                reason_keterangan + '</option>');
                        }
                    }
                }

                $("#slc_reason" + i).select2();
            }
        }
    });

}

function ViewAllSlcReason() {
    $.ajax({
        type: 'POST',
        url: "<?= base_url('WMS/SuratTugasPengiriman/GetReason') ?>",
        dataType: "json",
        success: function(response) {
            if (response) {
                $('[name="slcreason"]').html('');
                if (response.Reason != 0) {
                    for (x = 0; x < response.Reason.length; x++) {
                        reason_id = response.Reason[x].reason_id;
                        reason_keterangan = response.Reason[x].reason_keterangan;

                        $('[name="slcreason"]').append('<option value="' + reason_id + '">' +
                            reason_keterangan + '</option>');
                    }
                }

                $('[name="slcreason"]').select2();
            }
        }
    });

}

function ViewFormTerkirimSebagian(delivery_order_id, i) {
    var checkBox = document.getElementById("chk_do_terkirim_sebagian_" + i);

    $('#select-terkirim').prop('checked', false);
    $('#select-terkirim-sebagian').prop('checked', false);
    $('#select-gagal').prop('checked', false);
    $('#chk_do_terkirim_' + i).prop('checked', false);
    $('#chk_do_gagal_' + i).prop('checked', false);
    $('#chk_do_kirim_ulang_' + i).prop('checked', false);

    if (checkBox.checked == true) {

        $("#slc_reason" + i).html('');
        $("#slc_reason" + i).append('<option value=""></option>');

        $('#previewformdoterkirimsebagian').modal('show');
        $('#loadingview').show();
        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/SuratTugasPengiriman/GetClosingPengirimanByDO') ?>",
            data: "delivery_order_id=" + delivery_order_id,
            success: function(response) {
                if (response) {
                    ChTerkirimSebagianMenu(response);
                }
            }
        });
    }

}

function ChTerkirimSebagianMenu(JSONChannel) {
    $("#tabledetaildo > tbody").html('');

    var Channel = JSON.parse(JSONChannel);
    var no = 1;

    var delivery_order_id = Channel.DeliveryOrder[0].delivery_order_id;
    var delivery_order_kode = Channel.DeliveryOrder[0].delivery_order_kode

    $("#delivery_order_id").val(delivery_order_id);
    $("#delivery_order_kode").val(delivery_order_kode);
    $("#txt_jumlah_do").val(Channel.DeliveryOrder.length);

    if (Channel.DeliveryOrder != 0) {
        if ($.fn.DataTable.isDataTable('#tabledetaildo')) {
            $('#tabledetaildo').DataTable().destroy();
        }

        $('#tabledetaildo tbody').empty();

        for (i = 0; i < Channel.DeliveryOrder.length; i++) {
            var delivery_order_detail_id = Channel.DeliveryOrder[i].delivery_order_detail_id;
            var sku_id = Channel.DeliveryOrder[i].sku_id;
            var principle = Channel.DeliveryOrder[i].principle;
            var brand = Channel.DeliveryOrder[i].brand;
            var sku_kode = Channel.DeliveryOrder[i].sku_kode;
            var sku_nama_produk = Channel.DeliveryOrder[i].sku_nama_produk;
            var sku_kemasan = Channel.DeliveryOrder[i].sku_kemasan;
            var sku_satuan = Channel.DeliveryOrder[i].sku_satuan;
            var sku_qty = Channel.DeliveryOrder[i].sku_qty;
            var sku_qty_kirim = Channel.DeliveryOrder[i].sku_qty_kirim;
            var sku_expdate = Channel.DeliveryOrder[i].sku_expdate;
            var delivery_order_detail2_id = Channel.DeliveryOrder[i].delivery_order_detail2_id;

            var strmenu = '';

            strmenu = strmenu + '<tr>';
            strmenu = strmenu + '	<td>' + principle + '</td>';
            strmenu = strmenu + '	<td>' + brand + '</td>';
            strmenu = strmenu + '	<td><input type="hidden" id="delivery_order_detail_id' + i +
                '" name="delivery_order_detail_id' + i + '" value="' + delivery_order_detail_id +
                '"><input type="hidden" id="delivery_order_detail2_id' + i +
                '" name="delivery_order_detail2_id' + i + '" value="' + delivery_order_detail2_id + '"></td>';
            strmenu = strmenu + '	<td style="width:20%;">' + sku_nama_produk + '</td>';
            strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
            strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
            strmenu = strmenu + '	<td>' + sku_expdate + '</td>';
            strmenu = strmenu + '	<td>' + Math.round(sku_qty) + ' <input type="hidden" id="txt_sku_qty' + i +
                '" value="' + Math.round(sku_qty) + '"/></td>';
            strmenu = strmenu +
                '	<td style="width:20%; text-align: center;"><input type="number" class="form-control" id="txt_qty_kirim' +
                i + '" name="txt_qty_kirim' + i + '" placeholder="0" min="0" max="' + (Math.round(sku_qty)) +
                '" style="width:100%"></td>';
            strmenu = strmenu + '	<td style="width:20%;"><select id="slc_reason_detail' + i +
                '" class="form-control" style="width:100%"></select></td>';
            strmenu = strmenu + '</tr>';

            no++;

            $("#tabledetaildo > tbody").append(strmenu);
        }

        for (i = 0; i < Channel.DeliveryOrder.length; i++) {

            var delivery_order_detail_id = Channel.DeliveryOrder[i].delivery_order_detail_id;
            var reason_id_do = Channel.DeliveryOrder[i].reason_id;

            $("#slc_reason_detail" + i).html('');

            $("#slc_reason_detail" + i).append('<option value=""></option>');
            if (Channel.Reason != 0) {
                for (x = 0; x < Channel.Reason.length; x++) {
                    reason_id = Channel.Reason[x].reason_id;
                    reason_keterangan = Channel.Reason[x].reason_keterangan;

                    if (reason_id_do == reason_id) {
                        $("#slc_reason_detail" + i).append('<option value="' + reason_id + '" selected>' +
                            reason_keterangan + '</option>');
                    } else {
                        $("#slc_reason_detail" + i).append('<option value="' + reason_id + '">' + reason_keterangan +
                            '</option>');
                    }
                }
            }

            $("#slc_reason_detail" + i).select2();
        }

        $("#loadingview").hide();

        $('#tabledetaildo').DataTable({
            "lengthMenu": [
                [50],
                [50]
            ],
            "ordering": false
        });
    } else {
        $('#tabledetaildo').DataTable().clear().draw();
        // ResetForm();
    }

}

$("#btn_simpan").click(
    function() {
        var delivery_order_batch_id = $("#delivery_order_batch_id").val();
        var jumlah = $("#txt_jumlah").val();
        var delivery_order_batch_status = $("#filter_fdjr_status").val();
        var kendaraan_km_akhir = $("#filter_fdjr_km").val();

        var totalCheckboxes = $('[class="CheckboxDO"]').length;
        var numberOfChecked = $('[class="CheckboxDO"]:checked').length;
        var numberNotChecked = totalCheckboxes - numberOfChecked;
        var counter = 0;
        var km_awal = $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/SuratTugasPengiriman/GetKmAwalFDJR') ?>",
            data: {
                delivery_order_batch_id: delivery_order_batch_id,
            },
            dataType: 'json',
            async: false,
            success: function(data) {
                return data.km_awal;
            }
        }).responseText;
        var i = 0;

        arr_do_list = [];

        // console.log(arr_do_list);

        Swal.fire({
            title: '<b>Apa anda yakin ?</b>',
            text: "DO yang sudah diubah statusnya tidak bisa diganti",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya'
        }).then((result) => {
            if (result.value == true) {
                // if (kendaraan_km_akhir >= km_awal) {
                if (numberOfChecked == jumlah) {

                    $("#tabledomenu > tbody tr").each(function() {
                        var terkirm = $('[id="chk_do_terkirim_' + i + '"]:checked').length;
                        var terkirim_sebagian = $('[id="chk_do_terkirim_sebagian_' + i +
                            '"]:checked').length;
                        var gagal = $('[id="chk_do_gagal_' + i + '"]:checked').length;
                        var kirim_ulang = $('[id="chk_do_kirim_ulang_' + i + '"]:checked').length;
                        var titipan = $('[id="chk_titipan_' + i + '"]:checked').length;
                        var do_terkirim = $("#chk_do_terkirim_" + i).val();
                        var do_terkirim_sebagian = $("#chk_do_terkirim_sebagian_" + i).val();
                        var do_gagal = $("#chk_do_gagal_" + i).val();
                        var do_kirim_ulang = $("#chk_do_kirim_ulang_" + i).val();
                        var do_titipan = $("#chk_titipan_" + i).val();
                        var tipe_pembayaran = $("#slc_tipe_pembayaran" + i).val();
                        var reason_id = $("#slc_reason" + i).val();
                        var tipe_delivery_order_nama = $(this).find("td:eq(5)").text();
                        var jumlah_tunai = $(this).find("td:eq(10)").text();
                        var nominal_tunai = $("#nominal_tunai_" + i).val().replaceAll(",", "");
                        if (nominal_tunai == null || nominal_tunai == '' || nominal_tunai ==
                            0) {
                            var is_paid = "Belum Bayar";
                        } else if (parseInt(jumlah_tunai) > nominal_tunai || parseInt(
                                jumlah_tunai) < nominal_tunai) {
                            var is_paid = "Sebagian Bayar";
                        } else if (parseInt(jumlah_tunai) == nominal_tunai) {
                            var is_paid = "Sudah Bayar";
                        } else {
                            var is_paid = "";
                        }

                        if (terkirm > 0) {

                            if (titipan > 0) {
                                arr_do_list.push({
                                    'delivery_order_id': do_terkirim,
                                    'delivery_order_batch_id': delivery_order_batch_id,
                                    'status_progress_id': '86F41C3E-1175-45E8-9D40-E775216FC73A',
                                    'status_progress_nama': 'delivered',
                                    'delivery_order_batch_status': 'In Process Closing',
                                    'reason_id': reason_id,
                                    'kendaraan_km_akhir': kendaraan_km_akhir,
                                    'kendaraan_km_terpakai': (kendaraan_km_akhir - km_awal),
                                    'yourref': 'titipan',
                                    'nominal_tunai': nominal_tunai,
                                    'is_paid': is_paid,
                                    'delivery_order_tipe_pembayaran': tipe_pembayaran,
                                    'tipe_delivery_order_nama': tipe_delivery_order_nama
                                });
                            } else {
                                arr_do_list.push({
                                    'delivery_order_id': do_terkirim,
                                    'delivery_order_batch_id': delivery_order_batch_id,
                                    'status_progress_id': '86F41C3E-1175-45E8-9D40-E775216FC73A',
                                    'status_progress_nama': 'delivered',
                                    'delivery_order_batch_status': 'In Process Closing',
                                    'reason_id': reason_id,
                                    'kendaraan_km_akhir': kendaraan_km_akhir,
                                    'kendaraan_km_terpakai': (kendaraan_km_akhir - km_awal),
                                    'yourref': '',
                                    'nominal_tunai': nominal_tunai,
                                    'is_paid': is_paid,
                                    'delivery_order_tipe_pembayaran': tipe_pembayaran,
                                    'tipe_delivery_order_nama': tipe_delivery_order_nama
                                });
                            }

                        } else if (terkirim_sebagian > 0) {
                            if (titipan > 0) {
                                arr_do_list.push({
                                    'delivery_order_id': do_terkirim_sebagian,
                                    'delivery_order_batch_id': delivery_order_batch_id,
                                    'status_progress_id': '151BC87D-49BE-439E-8505-F18CB5C07EE5',
                                    'status_progress_nama': 'partially delivered',
                                    'delivery_order_batch_status': 'In Process Closing',
                                    'reason_id': reason_id,
                                    'kendaraan_km_akhir': kendaraan_km_akhir,
                                    'kendaraan_km_terpakai': (kendaraan_km_akhir - km_awal),
                                    'yourref': 'titipan',
                                    'nominal_tunai': nominal_tunai,
                                    'is_paid': is_paid,
                                    'delivery_order_tipe_pembayaran': tipe_pembayaran,
                                    'tipe_delivery_order_nama': tipe_delivery_order_nama
                                });
                            } else {
                                arr_do_list.push({
                                    'delivery_order_id': do_terkirim_sebagian,
                                    'delivery_order_batch_id': delivery_order_batch_id,
                                    'status_progress_id': '151BC87D-49BE-439E-8505-F18CB5C07EE5',
                                    'status_progress_nama': 'partially delivered',
                                    'delivery_order_batch_status': 'In Process Closing',
                                    'reason_id': reason_id,
                                    'kendaraan_km_akhir': kendaraan_km_akhir,
                                    'kendaraan_km_terpakai': (kendaraan_km_akhir - km_awal),
                                    'yourref': '',
                                    'nominal_tunai': nominal_tunai,
                                    'is_paid': is_paid,
                                    'delivery_order_tipe_pembayaran': tipe_pembayaran,
                                    'tipe_delivery_order_nama': tipe_delivery_order_nama
                                });
                            }
                        } else if (gagal > 0) {
                            if (titipan > 0) {
                                arr_do_list.push({
                                    'delivery_order_id': do_gagal,
                                    'delivery_order_batch_id': delivery_order_batch_id,
                                    'status_progress_id': '0F6439BF-AB81-4EFD-9EFC-2123D9AF8529',
                                    'status_progress_nama': 'not delivered',
                                    'delivery_order_batch_status': 'In Process Closing',
                                    'reason_id': reason_id,
                                    'kendaraan_km_akhir': kendaraan_km_akhir,
                                    'kendaraan_km_terpakai': (kendaraan_km_akhir - km_awal),
                                    'yourref': 'titipan',
                                    'nominal_tunai': nominal_tunai,
                                    'is_paid': is_paid,
                                    'delivery_order_tipe_pembayaran': tipe_pembayaran,
                                    'tipe_delivery_order_nama': tipe_delivery_order_nama
                                });
                            } else {
                                arr_do_list.push({
                                    'delivery_order_id': do_gagal,
                                    'delivery_order_batch_id': delivery_order_batch_id,
                                    'status_progress_id': '0F6439BF-AB81-4EFD-9EFC-2123D9AF8529',
                                    'status_progress_nama': 'not delivered',
                                    'delivery_order_batch_status': 'In Process Closing',
                                    'reason_id': reason_id,
                                    'kendaraan_km_akhir': kendaraan_km_akhir,
                                    'kendaraan_km_terpakai': (kendaraan_km_akhir - km_awal),
                                    'yourref': '',
                                    'nominal_tunai': nominal_tunai,
                                    'is_paid': is_paid,
                                    'delivery_order_tipe_pembayaran': tipe_pembayaran,
                                    'tipe_delivery_order_nama': tipe_delivery_order_nama
                                });
                            }
                        } else if (kirim_ulang > 0) {
                            if (titipan > 0) {
                                arr_do_list.push({
                                    'delivery_order_id': do_kirim_ulang,
                                    'delivery_order_batch_id': delivery_order_batch_id,
                                    'status_progress_id': '88640EB0-D381-4FC4-9215-1C80632E8283',
                                    'status_progress_nama': 'rescheduled',
                                    'delivery_order_batch_status': 'In Process Closing',
                                    'reason_id': reason_id,
                                    'kendaraan_km_akhir': kendaraan_km_akhir,
                                    'kendaraan_km_terpakai': (kendaraan_km_akhir - km_awal),
                                    'yourref': 'titipan',
                                    'nominal_tunai': nominal_tunai,
                                    'is_paid': is_paid,
                                    'delivery_order_tipe_pembayaran': tipe_pembayaran,
                                    'tipe_delivery_order_nama': tipe_delivery_order_nama
                                });
                            } else {
                                arr_do_list.push({
                                    'delivery_order_id': do_kirim_ulang,
                                    'delivery_order_batch_id': delivery_order_batch_id,
                                    'status_progress_id': '88640EB0-D381-4FC4-9215-1C80632E8283',
                                    'status_progress_nama': 'rescheduled',
                                    'delivery_order_batch_status': 'In Process Closing',
                                    'reason_id': reason_id,
                                    'kendaraan_km_akhir': kendaraan_km_akhir,
                                    'kendaraan_km_terpakai': (kendaraan_km_akhir - km_awal),
                                    'yourref': '',
                                    'nominal_tunai': nominal_tunai,
                                    'is_paid': is_paid,
                                    'delivery_order_tipe_pembayaran': tipe_pembayaran,
                                    'tipe_delivery_order_nama': tipe_delivery_order_nama
                                });
                            }
                        }

                        i++;
                    });
                    // console.log(arr_do_list);

                    setTimeout(() => {

                        $.ajax({
                            async: false,
                            type: 'POST',
                            url: "<?= base_url('WMS/SuratTugasPengiriman/UpdateClosingPengirimanDO') ?>",
                            beforeSend: function() {

                                Swal.fire({
                                    title: 'Loading ...',
                                    html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
                                    timerProgressBar: false,
                                    showConfirmButton: false
                                });

                                $("#btn_simpan").prop("disabled", true);
                                $("#btn_konfirmasi_pengiriman").prop("disabled", true);
                                $("#loadingview").show();
                            },
                            data: {
                                delivery_order_batch_id: delivery_order_batch_id,
                                delivery_order_batch_status: 'In Process Closing',
                                kendaraan_km_akhir: kendaraan_km_akhir,
                                kendaraan_km_terpakai: (kendaraan_km_akhir - km_awal),
                                delivery_order_batch_update_tgl: $(
                                    "#filter_update_fdjr_date").val(),
                                arr_do_list: arr_do_list,
                                arr_add_biaya: arr_add_biaya
                            },
                            success: function(response) {
                                if (response == 1) {

                                    Swal.fire({
                                        position: 'center',
                                        icon: 'success',
                                        title: 'Success',
                                        html: '<span name="CAPTION-ALERT-DATABERHASILDISIMPAN">Data Berhasil Disimpan</span>',
                                        showConfirmButton: false,
                                        timer: 1000
                                    });

                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 500);

                                } else if (response == 2) {
                                    messageNotSameLastUpdated();
                                    return false;
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        html: '<span name="CAPTION-ALERT-DATAGAGALDISIMPAN">Data Gagal Disimpan</span>'
                                    });

                                    $("#loadingview").hide();
                                    ResetForm();
                                }
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                message("Error",
                                    "Error 500 Internal Server Connection Failure",
                                    "error");

                                $("#btn_simpan").prop("disabled", false);
                                $("#btn_konfirmasi_pengiriman").prop("disabled", false);
                                $("#loadingview").hide();
                            },
                            complete: function() {

                                $("#btn_simpan").prop("disabled", false);
                                $("#btn_konfirmasi_pengiriman").prop("disabled", false);
                                $("#loadingview").hide();
                            }
                        });

                    }, 1000);

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: '<span name="CAPTION-ALERT-CHECKBOXDOHRSDIPILIH">Checkbox Semua DO Harus Dipilih!</span>'
                    });

                    $("#loadingview").hide();
                    ResetForm();
                }

                // } else {
                // 	Swal.fire({
                // 		icon: 'error',
                // 		title: 'Error',
                // 		text: 'KM terpakai tidak boleh lebih kecil dari KM awal!'
                // 	});

                // 	ResetForm();
                // }
            }
        });
    });

$("#btn_konfirmasi_pengiriman").click(function() {
    var delivery_order_batch_id = $("#delivery_order_batch_id").val();
    var jumlah = $("#txt_jumlah").val();
    var kendaraan_km_akhir = $("#filter_fdjr_km").val();
    var check_titipan = 0;
    var check_terkirim_sebagian = 0;
    var check_gagal = 0;
    var check_do_retur = 0;
    var check_do_retur_berhasil = 0;
    var total_titipan = $('[name="CheckboxTitipan"]:checked').length;
    var totalCheckboxes = $('input[type="checkbox"]').length;
    var numberOfChecked = $('[name="CheckboxDOTerkirim"]:checked').length + $(
            '[name="CheckboxDOTerkirimSebagian"]:checked').length + $('[name="CheckboxDOGagal"]:checked')
        .length + $('[name="CheckboxDOKirimUlang"]:checked').length;
    var numberNotChecked = totalCheckboxes - numberOfChecked;
    var km_awal = $.ajax({
        type: 'POST',
        url: "<?= base_url('WMS/SuratTugasPengiriman/GetKmAwalFDJR') ?>",
        data: {
            delivery_order_batch_id: delivery_order_batch_id,
        },
        dataType: 'json',
        async: false,
        success: function(data) {
            return data.km_awal;
        }
    }).responseText;
    var i = 0;
    var cek_kirim_ulang = $.ajax({
        type: 'POST',
        url: "<?= base_url('WMS/SuratTugasPengiriman/CekDOKirimUlang') ?>",
        data: {
            delivery_order_batch_id: delivery_order_batch_id,
        },
        dataType: 'json',
        async: false,
        success: function(response) {
            return response;
        }
    }).responseJSON;

    // console.log(cek_kirim_ulang);

    if (numberOfChecked != jumlah) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            html: '<span name="CAPTION-ALERT-CHECKBOXDOHRSDIPILIH">Checkbox Semua DO Harus Dipilih!</span>'
        });

        $("#loadingview").hide();
        ResetForm();

        return false;
    }

    Swal.fire({
        title: GetLanguageByKode('CAPTION-APAANDAYAKIN'),
        // title: 'Apa anda yakin',
        text: GetLanguageByKode('CAPTION-PENGIRIMANKONFIRMASITIDAKBISADIGANTI'),
        // text: 'Pengiriman konfirmasi tidak bisa diubah',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: GetLanguageByKode('CAPTION-LANJUT'),
        // confirmButtonText: 'Lanjut',
        cancelButtonText: GetLanguageByKode('CAPTION-CLOSE')
        // cancelButtonText: 'Close'
    }).then((result) => {
        if (result.value == true) {
            if (cek_kirim_ulang.length == 0) {
                if (numberOfChecked == jumlah) {
                    $("#tabledomenu > tbody tr").each(function() {
                        var terkirm = $('[id="chk_do_terkirim_' + i + '"]:checked').length;
                        var terkirim_sebagian = $('[id="chk_do_terkirim_sebagian_' + i +
                            '"]:checked').length;
                        var gagal = $('[id="chk_do_gagal_' + i + '"]:checked').length;
                        var kirim_ulang = $('[id="chk_do_kirim_ulang_' + i + '"]:checked')
                            .length;
                        var titipan = $('[id="chk_titipan_' + i + '"]:checked').length;
                        var do_terkirim = $("#chk_do_terkirim_" + i).val();
                        var do_terkirim_sebagian = $("#chk_do_terkirim_sebagian_" + i).val();
                        var do_gagal = $("#chk_do_gagal_" + i).val();
                        var do_kirim_ulang = $("#chk_do_kirim_ulang_" + i).val();
                        var do_titipan = $("#chk_titipan_" + i).val();
                        var reason_id = $("#slc_reason" + i).val();
                        var tipe_pembayaran = $("#slc_tipe_pembayaran" + i).val();
                        var tipe_delivery_order_nama = $(this).find("td:eq(5)").text();
                        var jumlah_tunai = $(this).find("td:eq(10)").text();
                        var nominal_tunai = $("#nominal_tunai_" + i).val().replaceAll(",", "");
                        var cek_titipan = $("#cek_titipan_" + i).val();

                        if (nominal_tunai == null || nominal_tunai == '' || nominal_tunai ==
                            0) {
                            var is_paid = "Belum Bayar";
                        } else if (parseInt(jumlah_tunai) > nominal_tunai || parseInt(
                                jumlah_tunai) < nominal_tunai) {
                            var is_paid = "Sebagian Bayar";
                        } else if (parseInt(jumlah_tunai) == nominal_tunai) {
                            var is_paid = "Sudah Bayar";
                        }

                        if (terkirm > 0) {
                            if (titipan > 0) {
                                arr_do_list.push({
                                    'delivery_order_id': do_terkirim,
                                    'delivery_order_batch_id': delivery_order_batch_id,
                                    'status_progress_id': '86F41C3E-1175-45E8-9D40-E775216FC73A',
                                    'status_progress_nama': 'delivered',
                                    'delivery_order_batch_status': 'In Process Closing',
                                    'reason_id': reason_id,
                                    'kendaraan_km_akhir': kendaraan_km_akhir,
                                    'kendaraan_km_terpakai': (kendaraan_km_akhir -
                                        km_awal),
                                    'yourref': 'titipan',
                                    'nominal_tunai': nominal_tunai,
                                    'is_paid': is_paid,
                                    'delivery_order_tipe_pembayaran': tipe_pembayaran,
                                    'tipe_delivery_order_nama': tipe_delivery_order_nama
                                });
                            } else {
                                arr_do_list.push({
                                    'delivery_order_id': do_terkirim,
                                    'delivery_order_batch_id': delivery_order_batch_id,
                                    'status_progress_id': '86F41C3E-1175-45E8-9D40-E775216FC73A',
                                    'status_progress_nama': 'delivered',
                                    'delivery_order_batch_status': 'In Process Closing',
                                    'reason_id': reason_id,
                                    'kendaraan_km_akhir': kendaraan_km_akhir,
                                    'kendaraan_km_terpakai': (kendaraan_km_akhir -
                                        km_awal),
                                    'yourref': '',
                                    'nominal_tunai': nominal_tunai,
                                    'is_paid': is_paid,
                                    'delivery_order_tipe_pembayaran': tipe_pembayaran,
                                    'tipe_delivery_order_nama': tipe_delivery_order_nama
                                });
                            }

                            if (tipe_delivery_order_nama == "Retur") {
                                check_do_retur++;
                            }

                        } else if (terkirim_sebagian > 0) {
                            if (titipan > 0) {
                                arr_do_list.push({
                                    'delivery_order_id': do_terkirim_sebagian,
                                    'delivery_order_batch_id': delivery_order_batch_id,
                                    'status_progress_id': '151BC87D-49BE-439E-8505-F18CB5C07EE5',
                                    'status_progress_nama': 'partially delivered',
                                    'delivery_order_batch_status': 'In Process Closing',
                                    'reason_id': reason_id,
                                    'kendaraan_km_akhir': kendaraan_km_akhir,
                                    'kendaraan_km_terpakai': (kendaraan_km_akhir -
                                        km_awal),
                                    'yourref': 'titipan',
                                    'nominal_tunai': nominal_tunai,
                                    'is_paid': is_paid,
                                    'delivery_order_tipe_pembayaran': tipe_pembayaran,
                                    'tipe_delivery_order_nama': tipe_delivery_order_nama
                                });
                            } else {
                                arr_do_list.push({
                                    'delivery_order_id': do_terkirim_sebagian,
                                    'delivery_order_batch_id': delivery_order_batch_id,
                                    'status_progress_id': '151BC87D-49BE-439E-8505-F18CB5C07EE5',
                                    'status_progress_nama': 'partially delivered',
                                    'delivery_order_batch_status': 'In Process Closing',
                                    'reason_id': reason_id,
                                    'kendaraan_km_akhir': kendaraan_km_akhir,
                                    'kendaraan_km_terpakai': (kendaraan_km_akhir -
                                        km_awal),
                                    'yourref': '',
                                    'nominal_tunai': nominal_tunai,
                                    'is_paid': is_paid,
                                    'delivery_order_tipe_pembayaran': tipe_pembayaran,
                                    'tipe_delivery_order_nama': tipe_delivery_order_nama
                                });
                            }
                        } else if (gagal > 0) {
                            if (titipan > 0) {
                                arr_do_list.push({
                                    'delivery_order_id': do_gagal,
                                    'delivery_order_batch_id': delivery_order_batch_id,
                                    'status_progress_id': '0F6439BF-AB81-4EFD-9EFC-2123D9AF8529',
                                    'status_progress_nama': 'not delivered',
                                    'delivery_order_batch_status': 'In Process Closing',
                                    'reason_id': reason_id,
                                    'kendaraan_km_akhir': kendaraan_km_akhir,
                                    'kendaraan_km_terpakai': (kendaraan_km_akhir -
                                        km_awal),
                                    'yourref': 'titipan',
                                    'nominal_tunai': nominal_tunai,
                                    'is_paid': is_paid,
                                    'delivery_order_tipe_pembayaran': tipe_pembayaran,
                                    'tipe_delivery_order_nama': tipe_delivery_order_nama
                                });
                            } else {
                                arr_do_list.push({
                                    'delivery_order_id': do_gagal,
                                    'delivery_order_batch_id': delivery_order_batch_id,
                                    'status_progress_id': '0F6439BF-AB81-4EFD-9EFC-2123D9AF8529',
                                    'status_progress_nama': 'not delivered',
                                    'delivery_order_batch_status': 'In Process Closing',
                                    'reason_id': reason_id,
                                    'kendaraan_km_akhir': kendaraan_km_akhir,
                                    'kendaraan_km_terpakai': (kendaraan_km_akhir -
                                        km_awal),
                                    'yourref': '',
                                    'nominal_tunai': nominal_tunai,
                                    'is_paid': is_paid,
                                    'delivery_order_tipe_pembayaran': tipe_pembayaran,
                                    'tipe_delivery_order_nama': tipe_delivery_order_nama
                                });
                            }

                            if (tipe_delivery_order_nama == "Retur") {
                                check_do_retur_berhasil++;
                            }
                        } else if (kirim_ulang > 0) {
                            if (titipan > 0) {
                                arr_do_list.push({
                                    'delivery_order_id': do_kirim_ulang,
                                    'delivery_order_batch_id': delivery_order_batch_id,
                                    'status_progress_id': '88640EB0-D381-4FC4-9215-1C80632E8283',
                                    'status_progress_nama': 'rescheduled',
                                    'delivery_order_batch_status': 'In Process Closing',
                                    'reason_id': reason_id,
                                    'kendaraan_km_akhir': kendaraan_km_akhir,
                                    'kendaraan_km_terpakai': (kendaraan_km_akhir -
                                        km_awal),
                                    'yourref': 'titipan',
                                    'nominal_tunai': nominal_tunai,
                                    'is_paid': is_paid,
                                    'delivery_order_tipe_pembayaran': tipe_pembayaran,
                                    'tipe_delivery_order_nama': tipe_delivery_order_nama
                                });
                            } else {
                                arr_do_list.push({
                                    'delivery_order_id': do_kirim_ulang,
                                    'delivery_order_batch_id': delivery_order_batch_id,
                                    'status_progress_id': '88640EB0-D381-4FC4-9215-1C80632E8283',
                                    'status_progress_nama': 'rescheduled',
                                    'delivery_order_batch_status': 'In Process Closing',
                                    'reason_id': reason_id,
                                    'kendaraan_km_akhir': kendaraan_km_akhir,
                                    'kendaraan_km_terpakai': (kendaraan_km_akhir -
                                        km_awal),
                                    'yourref': '',
                                    'nominal_tunai': nominal_tunai,
                                    'is_paid': is_paid,
                                    'delivery_order_tipe_pembayaran': tipe_pembayaran,
                                    'tipe_delivery_order_nama': tipe_delivery_order_nama
                                });
                            }
                        }

                        // console.log(titipan + "==" + ada_titipan);

                        if (titipan == cek_titipan && cek_titipan == 1) {
                            check_titipan++;
                        }

                        if (terkirim_sebagian > 0) {
                            check_terkirim_sebagian++;
                        }

                        if (gagal > 0 && tipe_delivery_order_nama != "Retur") {
                            check_gagal++;
                        }

                        i++;
                    });

                    // console.log(check_titipan + "==" + total_titipan);
                    // console.log('check_gagal', check_gagal)
                    // console.log('check_do_retur_berhasil', check_do_retur_berhasil)
                    // console.log('check_do_reguler', check_do_reguler)
                    // console.log('check_do_retur', check_do_retur)
                    // console.log('total_titipan', total_titipan)
                    // console.log('check_terkirim_sebagian', check_terkirim_sebagian)
                    // return false;

                    if (total_titipan > 0) {
                        if (check_titipan == total_titipan) {
                            setTimeout(() => {
                                $.ajax({
                                    type: 'POST',
                                    url: "<?= base_url('WMS/SuratTugasPengiriman/UpdateClosingPengirimanFDJR') ?>",
                                    data: {
                                        delivery_order_batch_id: delivery_order_batch_id,
                                        delivery_order_batch_status: 'In Process Receiving Outlet',
                                        kendaraan_km_akhir: kendaraan_km_akhir,
                                        kendaraan_km_terpakai: (kendaraan_km_akhir -
                                            km_awal),
                                        delivery_order_batch_update_tgl: $(
                                            "#filter_update_fdjr_date").val(),
                                        arr_do_list: arr_do_list
                                    },
                                    beforeSend: function() {

                                        Swal.fire({
                                            title: 'Loading ...',
                                            html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
                                            timerProgressBar: false,
                                            showConfirmButton: false
                                        });

                                        $("#btn_simpan").prop("disabled", true);
                                        $("#btn_konfirmasi_pengiriman").prop(
                                            "disabled", true);
                                        $("#loadingview").show();
                                    },
                                    success: function(response) {
                                        if (response == 1) {
                                            $("#loadingview").hide();

                                            Swal.fire({
                                                position: 'center',
                                                icon: 'success',
                                                title: 'Success',
                                                html: '<span name="CAPTION-ALERT-DATABERHASILDISIMPAN">Data Berhasil Disimpan</span>',
                                                showConfirmButton: false,
                                                timer: 1000
                                            });

                                            setTimeout(() => {
                                                location.href =
                                                    "<?= base_url(); ?>WMS/PenerimaanRetur/ProsesBTBMenu/?delivery_order_batch_id=" +
                                                    delivery_order_batch_id;
                                            }, 500);

                                            // window.location.reload();
                                        } else if (response == 2) {
                                            messageNotSameLastUpdated();
                                            return false;
                                        }

                                        // console.log(response);
                                    },
                                    error: function(xhr, ajaxOptions, thrownError) {
                                        message("Error",
                                            "Error 500 Internal Server Connection Failure",
                                            "error");

                                        $("#btn_simpan").prop("disabled", false);
                                        $("#btn_konfirmasi_pengiriman").prop(
                                            "disabled", false);
                                        $("#loadingview").hide();
                                    },
                                    complete: function() {

                                        $("#btn_simpan").prop("disabled", false);
                                        $("#btn_konfirmasi_pengiriman").prop(
                                            "disabled", false);
                                        $("#loadingview").hide();
                                    }
                                });
                            }, 1000);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                html: '<span name="CAPTION-ALERT-ADADORETURYGBLMDIPROSES">Ada DO Retur Yang Belum Diproses!</span>'
                            });

                            $("#loadingview").hide();
                        }
                    } else {
                        if (check_terkirim_sebagian > 0) {
                            setTimeout(() => {
                                $.ajax({
                                    type: 'POST',
                                    url: "<?= base_url('WMS/SuratTugasPengiriman/UpdateClosingPengirimanFDJR') ?>",
                                    data: {
                                        delivery_order_batch_id: delivery_order_batch_id,
                                        delivery_order_batch_status: 'In Process Receiving Outlet',
                                        kendaraan_km_akhir: kendaraan_km_akhir,
                                        kendaraan_km_terpakai: (kendaraan_km_akhir -
                                            km_awal),
                                        delivery_order_batch_update_tgl: $(
                                            "#filter_update_fdjr_date").val(),
                                        arr_do_list: arr_do_list
                                    },
                                    beforeSend: function() {

                                        Swal.fire({
                                            title: 'Loading ...',
                                            html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
                                            timerProgressBar: false,
                                            showConfirmButton: false
                                        });

                                        $("#btn_simpan").prop("disabled", true);
                                        $("#btn_konfirmasi_pengiriman").prop(
                                            "disabled", true);
                                        $("#loadingview").show();
                                    },
                                    success: function(response) {
                                        if (response == 1) {
                                            $("#loadingview").hide();

                                            Swal.fire({
                                                position: 'center',
                                                icon: 'success',
                                                title: 'Success',
                                                html: '<span name="CAPTION-ALERT-DATABERHASILDISIMPAN">Data Berhasil Disimpan</span>',
                                                showConfirmButton: false,
                                                timer: 1000
                                            });

                                            setTimeout(() => {
                                                location.href =
                                                    "<?= base_url(); ?>WMS/PenerimaanRetur/ProsesBTBMenu/?delivery_order_batch_id=" +
                                                    delivery_order_batch_id;
                                            }, 500);

                                            // window.location.reload();
                                        } else if (response == 2) {
                                            messageNotSameLastUpdated();
                                            return false;
                                        }

                                        // console.log(response);
                                    },
                                    error: function(xhr, ajaxOptions, thrownError) {
                                        message("Error",
                                            "Error 500 Internal Server Connection Failure",
                                            "error");

                                        $("#btn_simpan").prop("disabled", false);
                                        $("#btn_konfirmasi_pengiriman").prop(
                                            "disabled", false);
                                        $("#loadingview").hide();
                                    },
                                    complete: function() {

                                        $("#btn_simpan").prop("disabled", false);
                                        $("#btn_konfirmasi_pengiriman").prop(
                                            "disabled", false);
                                        $("#loadingview").hide();
                                    }
                                });
                            }, 1000);
                        } else if (check_gagal > 0) {
                            setTimeout(() => {
                                $.ajax({
                                    type: 'POST',
                                    url: "<?= base_url('WMS/SuratTugasPengiriman/UpdateClosingPengirimanFDJR') ?>",
                                    data: {
                                        delivery_order_batch_id: delivery_order_batch_id,
                                        delivery_order_batch_status: 'In Process Receiving Outlet',
                                        kendaraan_km_akhir: kendaraan_km_akhir,
                                        kendaraan_km_terpakai: (kendaraan_km_akhir -
                                            km_awal),
                                        delivery_order_batch_update_tgl: $(
                                            "#filter_update_fdjr_date").val(),
                                        arr_do_list: arr_do_list
                                    },
                                    beforeSend: function() {

                                        Swal.fire({
                                            title: 'Loading ...',
                                            html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
                                            timerProgressBar: false,
                                            showConfirmButton: false
                                        });

                                        $("#btn_simpan").prop("disabled", true);
                                        $("#btn_konfirmasi_pengiriman").prop(
                                            "disabled", true);
                                        $("#loadingview").show();
                                    },
                                    success: function(response) {
                                        if (response == 1) {
                                            $("#loadingview").hide();

                                            Swal.fire({
                                                position: 'center',
                                                icon: 'success',
                                                title: 'Success',
                                                html: '<span name="CAPTION-ALERT-DATABERHASILDISIMPAN">Data Berhasil Disimpan</span>',
                                                showConfirmButton: false,
                                                timer: 1000
                                            });

                                            setTimeout(() => {
                                                location.href =
                                                    "<?= base_url(); ?>WMS/PenerimaanRetur/ProsesBTBMenu/?delivery_order_batch_id=" +
                                                    delivery_order_batch_id;
                                            }, 500);

                                            // window.location.reload();
                                        } else if (response == 2) {
                                            messageNotSameLastUpdated();
                                            return false;
                                        }

                                        // console.log(response);
                                    },
                                    error: function(xhr, ajaxOptions, thrownError) {
                                        message("Error",
                                            "Error 500 Internal Server Connection Failure",
                                            "error");

                                        $("#btn_simpan").prop("disabled", false);
                                        $("#btn_konfirmasi_pengiriman").prop(
                                            "disabled", false);
                                        $("#loadingview").hide();
                                    },
                                    complete: function() {

                                        $("#btn_simpan").prop("disabled", false);
                                        $("#btn_konfirmasi_pengiriman").prop(
                                            "disabled", false);
                                        $("#loadingview").hide();
                                    }
                                });
                            }, 1000);
                        } else if (check_gagal == 0 && check_do_retur > 0) {
                            setTimeout(() => {
                                $.ajax({
                                    type: 'POST',
                                    url: "<?= base_url('WMS/SuratTugasPengiriman/UpdateClosingPengirimanFDJR') ?>",
                                    data: {
                                        delivery_order_batch_id: delivery_order_batch_id,
                                        delivery_order_batch_status: 'In Process Receiving Outlet',
                                        kendaraan_km_akhir: kendaraan_km_akhir,
                                        kendaraan_km_terpakai: (kendaraan_km_akhir -
                                            km_awal),
                                        delivery_order_batch_update_tgl: $(
                                            "#filter_update_fdjr_date").val(),
                                        arr_do_list: arr_do_list
                                    },
                                    beforeSend: function() {

                                        Swal.fire({
                                            title: 'Loading ...',
                                            html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
                                            timerProgressBar: false,
                                            showConfirmButton: false
                                        });

                                        $("#btn_simpan").prop("disabled", true);
                                        $("#btn_konfirmasi_pengiriman").prop(
                                            "disabled", true);
                                        $("#loadingview").show();
                                    },
                                    success: function(response) {
                                        if (response == 1) {
                                            $("#loadingview").hide();

                                            Swal.fire({
                                                position: 'center',
                                                icon: 'success',
                                                title: 'Success',
                                                html: '<span name="CAPTION-ALERT-DATABERHASILDISIMPAN">Data Berhasil Disimpan</span>',
                                                showConfirmButton: false,
                                                timer: 1000
                                            });

                                            setTimeout(() => {
                                                location.href =
                                                    "<?= base_url(); ?>WMS/PenerimaanRetur/ProsesBTBMenu/?delivery_order_batch_id=" +
                                                    delivery_order_batch_id;
                                            }, 500);

                                            // window.location.reload();
                                        } else if (response == 2) {
                                            messageNotSameLastUpdated();
                                            return false;
                                        }

                                        // console.log(response);
                                    },
                                    error: function(xhr, ajaxOptions, thrownError) {
                                        message("Error",
                                            "Error 500 Internal Server Connection Failure",
                                            "error");

                                        $("#btn_simpan").prop("disabled", false);
                                        $("#btn_konfirmasi_pengiriman").prop(
                                            "disabled", false);
                                        $("#loadingview").hide();
                                    },
                                    complete: function() {

                                        $("#btn_simpan").prop("disabled", false);
                                        $("#btn_konfirmasi_pengiriman").prop(
                                            "disabled", false);
                                        $("#loadingview").hide();
                                    }
                                });
                            }, 1000);
                        } else {
                            setTimeout(() => {
                                $.ajax({
                                    type: 'POST',
                                    url: "<?= base_url('WMS/SuratTugasPengiriman/UpdateClosingPengirimanFDJR') ?>",
                                    data: {
                                        delivery_order_batch_id: delivery_order_batch_id,
                                        delivery_order_batch_status: 'Closing Delivery Confirm',
                                        kendaraan_km_akhir: kendaraan_km_akhir,
                                        kendaraan_km_terpakai: (kendaraan_km_akhir -
                                            km_awal),
                                        delivery_order_batch_update_tgl: $(
                                            "#filter_update_fdjr_date").val(),
                                        arr_do_list: arr_do_list
                                    },
                                    beforeSend: function() {

                                        Swal.fire({
                                            title: 'Loading ...',
                                            html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
                                            timerProgressBar: false,
                                            showConfirmButton: false
                                        });

                                        $("#btn_simpan").prop("disabled", true);
                                        $("#btn_konfirmasi_pengiriman").prop(
                                            "disabled", true);
                                        $("#loadingview").show();
                                    },
                                    success: function(response) {
                                        if (response == 1) {
                                            $("#loadingview").hide();

                                            Swal.fire({
                                                position: 'center',
                                                icon: 'success',
                                                title: 'Success',
                                                html: '<span name="CAPTION-ALERT-DATABERHASILDISIMPAN">Data Berhasil Disimpan</span>',
                                                showConfirmButton: false,
                                                timer: 1000
                                            });

                                            window.location.reload();
                                        } else if (response == 2) {
                                            messageNotSameLastUpdated();
                                            return false;
                                        }

                                        // console.log(response);
                                    },
                                    error: function(xhr, ajaxOptions, thrownError) {
                                        message("Error",
                                            "Error 500 Internal Server Connection Failure",
                                            "error");

                                        $("#btn_simpan").prop("disabled", false);
                                        $("#btn_konfirmasi_pengiriman").prop(
                                            "disabled", false);
                                        $("#loadingview").hide();
                                    },
                                    complete: function() {

                                        $("#btn_simpan").prop("disabled", false);
                                        $("#btn_konfirmasi_pengiriman").prop(
                                            "disabled", false);
                                        $("#loadingview").hide();
                                    }
                                });
                            }, 1000);
                        }
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: '<span name="CAPTION-ALERT-CHECKBOXDOHRSDIPILIH">Checkbox Semua DO Harus Dipilih!</span>'
                    });

                    $("#loadingview").hide();
                    ResetForm();
                }

            } else {
                $.each(cek_kirim_ulang, function(i, v) {
                    let msg = GetLanguageByKode('CAPTION-ALERT-DOKIRIMULANGBELUMADA');

                    new PNotify
                        ({
                            title: 'Error',
                            text: 'DO ' + v.kode_do + ' ' + msg,
                            type: 'error',
                            styling: 'bootstrap3',
                            delay: 3000,
                            stack: stack_center
                        });
                });

            }
        }
    });
});

$("form.terkirim_sebagian_form").submit(function(e) {
    e.preventDefault();
    var delivery_order_id = $("#delivery_order_id").val();
    var jumlah = $("#txt_jumlah_do").val();
    var counter = 0;
    var cek = 0;
    var cek2 = 0;

    for (var x = 0; x < jumlah; x++) {
        var sku_qty_kirim = parseInt($("#txt_qty_kirim" + x).val());
        var sku_qty = parseInt($("#txt_sku_qty" + x).val());
        var delivery_order_detail_id = $("#delivery_order_detail_id" + x).val();
        var delivery_order_detail2_id = $("#delivery_order_detail2_id" + x).val();
        var reason_id = $("#slc_reason_detail" + x).val();

        if (sku_qty_kirim >= sku_qty) {
            cek++;
        }

        if (sku_qty_kirim < 1) {
            cek2++;
        }
    }

    if (cek == jumlah) {
        new PNotify
            ({
                title: 'Warning',
                text: "Jumlah Qty kirim ada yang melebihi atau sama dengan Qty, harap cek kembali.",
                type: "warning",
                styling: 'bootstrap3',
                delay: 3000,
                stack: stack_center
            });

        return false;
    }

    if (cek2 == jumlah) {
        new PNotify
            ({
                title: 'Warning',
                text: "Jumlah Qty kirim tidak boleh kosong semua.",
                type: "warning",
                styling: 'bootstrap3',
                delay: 3000,
                stack: stack_center
            });

        return false;
    }

    for (var x = 0; x < jumlah; x++) {
        var sku_qty_kirim = $("#txt_qty_kirim" + x).val();
        var sku_qty = $("#txt_sku_qty" + x).val();
        var delivery_order_detail_id = $("#delivery_order_detail_id" + x).val();
        var delivery_order_detail2_id = $("#delivery_order_detail2_id" + x).val();
        var reason_id = $("#slc_reason_detail" + x).val();

        $.ajax({
            async: false,
            type: 'POST',
            url: "<?= base_url('WMS/SuratTugasPengiriman/UpdateClosingPengirimanByDO') ?>",
            data: {
                delivery_order_id: delivery_order_id,
                delivery_order_detail_id: delivery_order_detail_id,
                delivery_order_detail2_id: delivery_order_detail2_id,
                sku_qty_kirim: sku_qty_kirim,
                reason_id: reason_id
            },
            success: function(response) {
                if (response) {
                    if (response == 1) {
                        counter++;
                    } else {
                        if (response == 2) {
                            var msg = 'Kode Channel sudah ada';
                        } else if (response == 3) {
                            var msg = 'Nama Channel sudah ada';
                        } else {
                            var msg = response;
                        }
                        var msgtype = 'error';

                        new PNotify
                            ({
                                title: 'Error',
                                text: msg,
                                type: msgtype,
                                styling: 'bootstrap3',
                                delay: 3000,
                                stack: stack_center
                            });
                    }

                    if (counter == jumlah) {
                        var msg = 'Data berhasil disimpan';
                        var msgtype = 'success';

                        Swal.fire({
                            position: 'center',
                            icon: msgtype,
                            title: msg,
                            showConfirmButton: false,
                            timer: 1000
                        });

                        $("#previewformdoterkirimsebagian").modal('hide');
                    }
                }
            }
        });
    }
})

// $("#btnsaveterkirimsebagian").click(
//     function() {
//         var delivery_order_id = $("#delivery_order_id").val();
//         var jumlah = $("#txt_jumlah_do").val();
//         var counter = 0;

//         for (var x = 0; x < jumlah; x++) {
//             var sku_qty_kirim = $("#txt_qty_kirim" + x).val();
//             var sku_qty = $("#txt_sku_qty" + x).val();
//             var delivery_order_detail_id = $("#delivery_order_detail_id" + x).val();
//             var delivery_order_detail2_id = $("#delivery_order_detail2_id" + x).val();
//             var reason_id = $("#slc_reason_detail" + x).val();

//             if (sku_qty_kirim >= sku_qty) {
//                 new PNotify
//                     ({
//                         title: 'Warning',
//                         text: "Jumlah Qty kirim ada yang melebihi atau sama dengan Qty, harap cek kembali.",
//                         type: "warning",
//                         styling: 'bootstrap3',
//                         delay: 3000,
//                         stack: stack_center
//                     });

//                 return false;
//             }

//             $.ajax({
//                 async: false,
//                 type: 'POST',
//                 url: "<?= base_url('WMS/SuratTugasPengiriman/UpdateClosingPengirimanByDO') ?>",
//                 data: {
//                     delivery_order_id: delivery_order_id,
//                     delivery_order_detail_id: delivery_order_detail_id,
//                     delivery_order_detail2_id: delivery_order_detail2_id,
//                     sku_qty_kirim: sku_qty_kirim,
//                     reason_id: reason_id
//                 },
//                 success: function(response) {
//                     if (response) {
//                         if (response == 1) {
//                             counter++;
//                         } else {
//                             if (response == 2) {
//                                 var msg = 'Kode Channel sudah ada';
//                             } else if (response == 3) {
//                                 var msg = 'Nama Channel sudah ada';
//                             } else {
//                                 var msg = response;
//                             }
//                             var msgtype = 'error';

//                             //if (!window.__cfRLUnblockHandlers) return false;
//                             new PNotify
//                                 ({
//                                     title: 'Error',
//                                     text: msg,
//                                     type: msgtype,
//                                     styling: 'bootstrap3',
//                                     delay: 3000,
//                                     stack: stack_center
//                                 });
//                         }

//                         if (counter == jumlah) {
//                             var msg = 'Data berhasil disimpan';
//                             var msgtype = 'success';

//                             //if (!window.__cfRLUnblockHandlers) return false;
//                             Swal.fire({
//                                 position: 'center',
//                                 icon: msgtype,
//                                 title: msg,
//                                 showConfirmButton: false,
//                                 timer: 1000
//                             });

//                             ResetForm();
//                             $("#previewformdoterkirimsebagian").modal('hide');
//                         }
//                     }
//                 }
//             });
//         }
//     }
// );

$("#btn_btb_form").click(
    function() {
        var delivery_order_batch_id = $("#delivery_order_batch_id").val();
        var checkbox_titipan = $('[name="CheckboxTitipan"]:checked').length;

        if (checkbox_titipan > 0) {
            window.open(
                '<?php echo base_url() ?>WMS/SuratTugasPengiriman/BTBFormMenu/?delivery_order_batch_id=<?php echo $delivery_order_batch_id; ?>',
                '_blank');
        } else {
            $.ajax({
                async: false,
                type: 'POST',
                url: "<?= base_url('WMS/SuratTugasPengiriman/CheckStatusPenerimaanPenjualan') ?>",
                data: {
                    delivery_order_batch_id: delivery_order_batch_id
                },
                success: function(response) {
                    if (response > 0) {
                        window.open(
                            '<?php echo base_url() ?>WMS/SuratTugasPengiriman/BTBFormMenu/?delivery_order_batch_id=<?php echo $delivery_order_batch_id; ?>',
                            '_blank');

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Hanya Bisa DO status Terkirim Sebagian, Gagal Kirim Dan Titipan!'
                        });
                    }
                }
            });
        }
    }
);

$("#btnback").click(
    function() {
        $('#previewformdoterkirimsebagian').modal('hide');
    }
);

$(document).on("click", "#btnaddbiaya", function() {
    arr_do_id = [];

    let do_id = $(this).attr("data-id");
    let delivery_order_batch_status = $(this).attr("data-status");

    arr_do_id.push(
        do_id
    );

    $('#tableaddbiaya > tbody').html('');

    if (arr_add_biaya.length != 0) {
        $.each(arr_add_biaya, function(i, v) {
            if (v.do_id == do_id) {
                arr_do_id.push(
                    do_id
                );
            }
        });
        console.log("arr_do_id", arr_do_id);
        // return false

        if (arr_do_id.length > 1) {
            var str_tipe_biaya = '';
            $.ajax({
                type: 'POST',
                url: "<?= base_url() ?>WMS/SuratTugasPengiriman/GetTipeBiaya",
                dataType: "JSON",
                success: function(response) {
                    if (response.tipe_biaya != 0) {
                        var str_tipe_biaya = '';
                        str_tipe_biaya +=
                            `<option value="">-- Pilih Nama Biaya --</option>`

                        for (let c = 0; c < arr_add_biaya.length; c++) {
                            if (arr_add_biaya[c].do_id == do_id) {
                                $.each(response.tipe_biaya, function(i, v) {
                                    if (arr_add_biaya[c].nama_biaya == v
                                        .tipe_biaya_id) {
                                        str_tipe_biaya +=
                                            `<option value="${v.tipe_biaya_id}" selected>${v.tipe_biaya_nama}</option>`;
                                    } else {
                                        str_tipe_biaya +=
                                            `<option value="${v.tipe_biaya_id}">${v.tipe_biaya_nama}</option>`
                                    }

                                });

                                var str = '';

                                str = str + '<tr>';
                                str = str +
                                    '	<td width="40%"><select id="nama_biaya" name="nama_biaya" class="form-control select2">' +
                                    str_tipe_biaya + '</select></td>';
                                str = str +
                                    '	<td width="40%"><input value="' + arr_add_biaya[c]
                                    .keterangan +
                                    '" id="keterangan" name="keterangan" type="text" class=" form-control" /></td>';
                                str = str +
                                    '	<td width="15%"><input value="' + formatNumber(arr_add_biaya[
                                            c]
                                        .nominal_biaya) +
                                    '" type="text" onkeyup="formatNominal(this)" onkeypress="return event.charCode >= 48 && event.charCode<=57" id="nominal_biaya" name="nominal_biaya" type="text" class=" form-control" /></td>';
                                str = str +
                                    '	<td width="5%"><button type="button" class="btn btn-danger btndelete" onclick="DeleteTempAddBiayaDetail(this)"><i class="fa fa-times"></i></button></td>';
                                str = str + '</tr>';

                                $("#tableaddbiaya > tbody").append(str);
                            }
                        }


                    }
                }
            })
        } else {
            $.ajax({
                type: 'POST',
                url: "<?= base_url() ?>WMS/SuratTugasPengiriman/GetDODetail3",
                data: {
                    do_id: do_id
                },
                dataType: "JSON",
                success: function(response) {
                    var DODetail3 = response.GetDODetail3;

                    if (DODetail3 != 0) {
                        var str_tipe_biaya = '';
                        $.ajax({
                            type: 'POST',
                            url: "<?= base_url() ?>WMS/SuratTugasPengiriman/GetTipeBiaya",
                            dataType: "JSON",
                            success: function(response) {
                                if (response.tipe_biaya != 0) {
                                    var str_tipe_biaya = '';
                                    str_tipe_biaya +=
                                        `<option value="">-- Pilih Nama Biaya --</option>`

                                    for (let c = 0; c < DODetail3
                                        .length; c++) {
                                        $.each(response.tipe_biaya,
                                            function(i, v) {
                                                if (DODetail3[c]
                                                    .tipe_biaya_id == v
                                                    .tipe_biaya_id) {
                                                    str_tipe_biaya +=
                                                        `<option value="${v.tipe_biaya_id}" selected>${v.tipe_biaya_nama}</option>`;
                                                } else {
                                                    str_tipe_biaya +=
                                                        `<option value="${v.tipe_biaya_id}">${v.tipe_biaya_nama}</option>`
                                                }

                                            });

                                        var str = '';

                                        str = str + '<tr>';
                                        str = str +
                                            '	<td width="40%"><select id="nama_biaya" name="nama_biaya" class="form-control select2">' +
                                            str_tipe_biaya +
                                            '</select></td>';
                                        str = str +
                                            '	<td width="40%"><input value="' +
                                            DODetail3[c]
                                            .delivery_order_detail3_keterangan +
                                            '" id="keterangan" name="keterangan" type="text" class=" form-control" /></td>';
                                        str = str +
                                            '	<td width="15%"><input value="' +
                                            formatNumber(parseInt(DODetail3[c]
                                                .delivery_order_detail3_nilai)) +
                                            '"  type="text" onkeyup="formatNominal(this)" onkeypress="return event.charCode >= 48 && event.charCode<=57" id="nominal_biaya" name="nominal_biaya" type="text" class=" form-control" /></td>';
                                        str = str +
                                            '	<td width="5%"><button type="button" class="btn btn-danger btndelete" onclick="DeleteTempAddBiayaDetail(this)"><i class="fa fa-times"></i></button></td>';
                                        str = str + '</tr>';

                                        $("#tableaddbiaya > tbody").append(
                                            str);
                                    }


                                }
                            }
                        })
                    }

                }
            })
        }

    } else {
        $.ajax({
            type: 'POST',
            url: "<?= base_url() ?>WMS/SuratTugasPengiriman/GetDODetail3",
            data: {
                do_id: do_id
            },
            dataType: "JSON",
            success: function(response) {
                var DODetail3 = response.GetDODetail3;

                if (DODetail3 != 0) {
                    $.ajax({
                        type: 'POST',
                        url: "<?= base_url() ?>WMS/SuratTugasPengiriman/GetTipeBiaya",
                        dataType: "JSON",
                        success: function(response) {
                            if (response.tipe_biaya != 0) {
                                var str_tipe_biaya = '';
                                str_tipe_biaya +=
                                    `<option value="">-- Pilih Nama Biaya --</option>`

                                for (let c = 0; c < DODetail3.length; c++) {
                                    $.each(response.tipe_biaya, function(i, v) {
                                        if (DODetail3[c].tipe_biaya_id == v
                                            .tipe_biaya_id) {
                                            str_tipe_biaya +=
                                                `<option value="${v.tipe_biaya_id}" selected>${v.tipe_biaya_nama}</option>`;
                                        } else {
                                            str_tipe_biaya +=
                                                `<option value="${v.tipe_biaya_id}">${v.tipe_biaya_nama}</option>`
                                        }

                                    });

                                    var str = '';

                                    if (delivery_order_batch_status ==
                                        'In Process Receiving Outlet' ||
                                        delivery_order_batch_status ==
                                        'Closing Delivery Confirm') {

                                        $('#btnaddbiayadetail').css('display',
                                            'none');
                                        $('#btnsimpanaddbiaya').css('display', 'none');
                                        $('#opsibiaya').attr('hidden', true);

                                        str = str + '<tr>';
                                        str = str +
                                            '	<td width="40%"><select id="nama_biaya" name="nama_biaya" class="form-control select2" disabled>' +
                                            str_tipe_biaya + '</select></td>';
                                        str = str +
                                            '	<td width="40%"><input value="' +
                                            DODetail3[c]
                                            .delivery_order_detail3_keterangan +
                                            '" id="keterangan" name="keterangan" type="text" class=" form-control" readonly /></td>';
                                        str = str +
                                            '	<td width="15%"><input value="' +
                                            formatNumber(parseFloat(DODetail3[c]
                                                .delivery_order_detail3_nilai)) +
                                            '"  type="text" onkeyup="formatNominal(this)" onkeypress="return event.charCode >= 48 && event.charCode<=57" id="nominal_biaya" name="nominal_biaya" type="text" class=" form-control" readonly /></td>';
                                        str = str + '</tr>';
                                    } else {

                                        str = str + '<tr>';
                                        str = str +
                                            '	<td width="40%"><select id="nama_biaya" name="nama_biaya" class="form-control select2">' +
                                            str_tipe_biaya + '</select></td>';
                                        str = str +
                                            '	<td width="40%"><input value="' +
                                            DODetail3[c]
                                            .delivery_order_detail3_keterangan +
                                            '" id="keterangan" name="keterangan" type="text" class=" form-control" /></td>';
                                        str = str +
                                            '	<td width="15%"><input value="' +
                                            formatNumber(parseFloat(DODetail3[c]
                                                .delivery_order_detail3_nilai)) +
                                            '"  type="text" onkeyup="formatNominal(this)" onkeypress="return event.charCode >= 48 && event.charCode<=57" id="nominal_biaya" name="nominal_biaya" type="text" class=" form-control" /></td>';
                                        str = str +
                                            '	<td width="5%"><button type="button" class="btn btn-danger btndelete" onclick="DeleteTempAddBiayaDetail(this)"><i class="fa fa-times"></i></button></td>';
                                        str = str + '</tr>';
                                    }

                                    $("#tableaddbiaya > tbody").append(str);
                                }


                            }
                        }
                    })
                }

            }
        })
    }

    console.log(arr_add_biaya);
    $('#modalAddBiaya').modal('show');
});

$('#btnaddbiayadetail').click(function() {
    $.ajax({
        type: 'POST',
        url: "<?= base_url() ?>WMS/SuratTugasPengiriman/GetTipeBiaya",
        dataType: "JSON",
        success: function(response) {

            if (response.tipe_biaya != 0) {
                var str_tipe_biaya = '';
                str_tipe_biaya +=
                    `<option value="">-- Pilih Nama Biaya --</option>`

                $.each(response.tipe_biaya, function(i, v) {
                    str_tipe_biaya +=
                        `<option value="${v.tipe_biaya_id}">${v.tipe_biaya_nama}</option>`
                });

                var str = '';

                str = str + '<tr>';
                str = str +
                    '	<td width="40%"><select id="nama_biaya" name="nama_biaya" class="form-control select2">' +
                    str_tipe_biaya + '</select></td>';
                str = str +
                    '	<td width="40%"><input id="keterangan" name="keterangan" type="text" class=" form-control" /></td>';
                str = str +
                    '	<td width="15%"><input type="text" onkeyup="formatNominal(this)" onkeypress="return event.charCode >= 48 && event.charCode<=57" id="nominal_biaya" name="nominal_biaya" type="text" class=" form-control" /></td>';
                str = str +
                    '	<td width="5%"><button type="button" class="btn btn-danger btndelete" onclick="DeleteTempAddBiayaDetail(this)"><i class="fa fa-times"></i></button></td>';
                str = str + '</tr>';

                $("#tableaddbiaya > tbody").append(str);
            }
        }
    })
});

function ResetForm() {
    <?php
        if ($Menu_Access["U"] == 1) {
        ?>
    $("#loadingview").hide();
    $("#btnsaveterkirimsebagian").prop("disabled", false);
    <?php
        }
        ?>

    <?php
        if ($Menu_Access["C"] == 1) {
        ?>
    $("#loadingview").hide();
    $("#btnsaveterkirimsebagian").prop("disabled", false);
    <?php
        }
        ?>

}

$('#btnsimpanaddbiaya').click(function() {
    let boolean = true;


    var tableaddbiaya = $('#tableaddbiaya > tbody tr').length;
    console.log(tableaddbiaya);
    // return false;

    if (tableaddbiaya == 0) {
        if (arr_del_biaya != 0) {
            $.ajax({
                type: 'post',
                url: "<?= base_url() ?>WMS/SuratTugasPengiriman/deleteDODetail3ByID",
                data: {
                    id: arr_del_biaya[0]
                },
                dataType: 'JSON',
                success: function(response) {
                    if (response != 1) {
                        message("Error!", "Gagal simpan, ID tidak ditemukan",
                            "error");
                    }
                }
            })
        }
    }


    if (tableaddbiaya != 0) {
        $('#tableaddbiaya > tbody tr').each(function() {
            let nama_biaya = $(this).find("td:eq(0) select[id='nama_biaya']").val();
            let nominal_biaya = $(this).find("td:eq(2) input[id='nominal_biaya']").val();

            if (nama_biaya == '') {
                message("Error!", "Nama Biaya masih kosong, silahkan pilih nama biaya terlebih dahulu",
                    "error");
                boolean = false;
            } else if (nominal_biaya == '') {
                message("Error!",
                    "Nominal Biaya masih kosong, silahkan isi nominal biaya terlebih dahulu",
                    "error");
                boolean = false;
            }
        });
    }

    if (boolean == true) {
        if (arr_add_biaya.length != 0) {
            for (let i = 0; i < arr_add_biaya.length; i++) {
                if (arr_add_biaya[i].do_id == arr_do_id[0]) {
                    // console.log(arr_add_biaya[i].do_id, arr_do_id[0]);
                    arr_add_biaya.splice(i, 1);
                    i--;
                }
            }
            console.log(arr_add_biaya);

            // return false;
        }

        $('#tableaddbiaya > tbody tr').each(function() {
            let nama_biaya = $(this).find("td:eq(0) select[id='nama_biaya']").val();
            let keterangan = $(this).find("td:eq(1) input[id='keterangan']").val();
            let nominal_biaya = $(this).find("td:eq(2) input[id='nominal_biaya']").val();

            arr_add_biaya.push({
                do_id: arr_do_id[0],
                nama_biaya,
                keterangan,
                nominal_biaya
            })

        });

        $('#modalAddBiaya').modal('hide');
    }

});

$(document).ready(function() {
    if ($('#filter_fdjr_date').length > 0) {
        $('#filter_fdjr_date').daterangepicker({
            'applyClass': 'btn-sm btn-success',
            'cancelClass': 'btn-sm btn-default',
            locale: {
                "format": "DD/MM/YYYY",
                applyLabel: 'Apply',
                cancelLabel: 'Cancel',
            },
            'startDate': '<?= date("01-m-Y") ?>',
            'endDate': '<?= date("t-m-Y") ?>'
        });
    }

});

function DeleteTempAddBiayaDetail(Idx) {
    var row = Idx.parentNode.parentNode;
    row.parentNode.removeChild(row);

    var do_id = $('#btnaddbiaya').attr("data-id");
    arr_del_biaya.push(do_id);

    console.log("delete", arr_del_biaya);

}

function formatNominal(input) {
    var nStr = input.value + '';
    nStr = nStr.replace(/\,/g, "");
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    input.value = x1 + x2;
}

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
}

function ReCalculate(TotalData) {
    total_bayar = 0;
    $("#total_bayar").html('');

    for (i = 0; i < TotalData; i++) {
        total_bayar += isNaN(parseFloat($("#nominal_tunai_" + i).val().replaceAll(",", ""))) ? 0 : parseFloat($(
            "#nominal_tunai_" + i).val().replaceAll(",", ""));
    }

    $("#total_bayar").append(formatNumber(total_bayar));

}

function CekDOKirimUlang(delivery_order_batch_id, delivery_order_id) {
    Swal.fire({
        title: GetLanguageByKode('CAPTION-APAANDAYAKIN'),
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: GetLanguageByKode('CAPTION-LANJUT'),
        cancelButtonText: GetLanguageByKode('CAPTION-CLOSE')
    }).then((result) => {
        if (result.value == true) {
            //ajax save data
            setTimeout(function() {
                $.ajax({
                    async: false,
                    type: 'POST',
                    url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/CheckDOKirimUlang'); ?>",
                    data: {
                        delivery_order_batch_id: delivery_order_batch_id,
                        delivery_order_id: delivery_order_id
                    },
                    dataType: "JSON",
                    success: function(response) {

                        $("#loadingview").show();

                        if (response == 0) {
                            $("#loadingview").hide();
                            setTimeout(() => {
                                location.href =
                                    "<?= base_url(); ?>WMS/Distribusi/DeliveryOrderDraft/DOKirimUlang/?delivery_order_batch_id=" +
                                    delivery_order_batch_id +
                                    "&delivery_order_id=" + delivery_order_id;
                            }, 500);
                        } else {
                            $("#loadingview").hide();
                            let msg = GetLanguageByKode(
                                'CAPTION-ALERT-DOKIRIMULANGSUDAHADA');
                            message("Error!", msg, "error");
                        }
                    }
                });
            }, 500);
        }
    });
}

function SetPembayaranByTipe(index, delivery_order_id, tipe_pembayaran, delivery_order_nominal_tunai, jml_data,
    tipe_delivery_order_nama) {
    if (tipe_pembayaran == 0) {
        $("#nominal_tunai_" + index).val(formatNumber(parseFloat(delivery_order_nominal_tunai)));
        ReCalculate(jml_data);
    }

    if (tipe_pembayaran == 1) {
        $("#nominal_tunai_" + index).val(0);
        ReCalculate(jml_data);
    }

    if (tipe_delivery_order_nama == "Retur") {
        $("#nominal_tunai_" + index).val(0);
        ReCalculate(jml_data);
    }
}
</script>