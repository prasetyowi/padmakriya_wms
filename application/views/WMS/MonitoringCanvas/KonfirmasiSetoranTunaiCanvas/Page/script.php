<script type="text/javascript">
    var arr_add_biaya = [];
    var arr_do_id = [];
    var arr_del_biaya = [];
    var arr_pembayaran = [];
    var arr_pembayaran_detail = [];
    var total_nominal_tunai = 0;

    loadingBeforeReadyPage();

    $(document).ready(function() {
        GetSuratTugasPengirimanMenu();
        $('#filter_fdjr_driver').select2();
        $('#filter_fdjr_status').select2();
        $('#slcaddcheckerbkbstandar').select2();
        $('#slctipepb').select2();

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

        if ($('#id_do_batch').length > 0) {
            GetDeliveryOrderBatchByID();
            GetListDOByID();
            // GetListPembayaran();
        }
    });

    $('#select-do').click(function(event) {
        if (this.checked) {
            // Iterate each checkbox
            $('[name="CheckboxDO"]:checkbox').each(function() {
                this.checked = true;
            });
        } else {
            $('[name="CheckboxDO"]:checkbox').each(function() {
                this.checked = false;
            });
        }
    });

    // function filterTable() {
    //     // ambil nilai input
    //     var input = document.getElementById("myInput");
    //     var filter = input.value.toUpperCase();

    //     // ambil tabel
    //     var table = document.getElementById("myTable");

    //     // ambil baris tabel
    //     var tr = table.getElementsByTagName("tr");

    //     // lakukan looping pada setiap baris tabel dan filter berdasarkan input
    //     for (var i = 0; i < tr.length; i++) {
    //         var td = tr[i].getElementsByTagName("td");
    //         var found = false;

    //         // cek setiap kolom td pada setiap baris tabel
    //         for (var j = 0; j < td.length; j++) {
    //             var txtValue = td[j].textContent || td[j].innerText;
    //             if (txtValue.toUpperCase().indexOf(filter) > -1) {
    //                 found = true;
    //                 break;
    //             }
    //         }

    //         // tampilkan atau sembunyikan baris berdasarkan filter
    //         if (found) {
    //             tr[i].style.display = "";
    //         } else {
    //             tr[i].style.display = "none";
    //         }
    //     }
    // }

    // // ambil input
    // var input = document.getElementById("myInput");

    // // tambahkan event listener untuk input
    // input.addEventListener("keyup", filterTable);

    // // atau tambahkan event listener untuk tombol pencarian
    // var button = document.getElementById("myButton");
    // button.addEventListener("click", filterTable);

    function GetDeliveryOrderBatchByID() {
        var id = $('#id_do_batch').val();

        requestAjax("<?= base_url('WMS/MonitoringCanvas/KonfirmasiSetoranTunaiCanvas/GetDeliveryOrderBatchByID') ?>", {
            id: id
        }, "POST", "JSON", function(response) {
            if (response.DO_Batch != 0) {
                $.each(response.DO_Batch, function(i, v) {
                    $('#no_fdjr').val(v.delivery_order_batch_kode);
                    $('#tgl_fdjr').val(v.delivery_order_batch_tanggal_kirim);

                    $('#tipe').append("<option value=''>" + v.tipe_delivery_order_alias +
                        "</option>");
                    $('#area').append("<option value=''>" + v.area_nama + "</option>");
                    $('#status_fdjr').append("<option value=''>" + v.delivery_order_batch_status +
                        "</option>");
                    $('#tipe_ekspedisi').append("<option value=''>" + v.tipe_ekspedisi_nama +
                        "</option>");
                    $('#armada').append("<option value=''>" + v.kendaraan_model + "</option>");
                    $('#pengemudi').append("<option value=''>" + v.karyawan_nama + "</option>");

                    // if (v.delivery_order_batch_nominal_tunai != null) {
                    // 	$('#viewJumlahTerima').val(parseInt(v.delivery_order_batch_nominal_tunai));
                    // }

                })
            }
        })
    }

    function GetListDOByID() {
        var id = $('#id_do_batch').val();

        requestAjax("<?= base_url('WMS/MonitoringCanvas/KonfirmasiSetoranTunaiCanvas/GetListDOByID') ?>", {
            id: id
        }, "POST", "JSON", function(response) {
            if (response.List_DO != 0) {
                let no = 1;
                let tipe_pembayaran = "";
                var sum_jumlah_terima = 0;
                var grandTotal = 0;
                $.each(response.List_DO, function(i, v) {
                    grandTotal = grandTotal + Math.round(v.delivery_order_jumlah_bayar);
                    // if (v.delivery_order_tipe_pembayaran == 0) {
                    // 	tipe_pembayaran = "Tunai";
                    // } else {
                    // 	tipe_pembayaran = "Non Tunai";
                    // }

                    // let jumlah_terima = '';
                    // if (tipe_pembayaran == "Tunai") {
                    //     if (v.delivery_order_nominal_terima_tunai != null) {
                    //         jumlah_terima =
                    //             '<input type="number" oninput="this.value = Math.abs(this.value)" onkeyup="sumJumlahTerima()" min="0" class="jumlahTerima form-control" id="jumlahTerima" name="jumlahTerima" value="' +
                    //             parseInt(v.delivery_order_nominal_terima_tunai) + '" />';
                    //         sum_jumlah_terima += parseInt(v.delivery_order_nominal_terima_tunai);
                    //     } else {
                    //         jumlah_terima =
                    //             '<input type="number" oninput="this.value = Math.abs(this.value)" onkeyup="sumJumlahTerima()" min="0" class="jumlahTerima form-control" id="jumlahTerima" name="jumlahTerima" value="0" />'
                    //     }
                    // } else {
                    //     jumlah_terima = '';
                    // }

                    $('#table_list_do > tbody').append(`
                        <tr>
                            <td class="text-center" style="display: none;"><input type="hidden" class="id_DO form-control" id="id_DO" value="${v.delivery_order_id}" /></td>
                            <td class="text-center">${no ++}</td>
                            <td class="text-center">${v.delivery_order_tgl_buat_do}</td>
                            <td class="text-center">${v.delivery_order_kode}</td>
                            <td class="text-center">${v.delivery_order_kirim_nama}</td>
                            <td class="text-center">${v.delivery_order_kirim_alamat}</td>
                            <td class="text-center">${v.delivery_order_tipe_pembayaran}</td>
                            <td class="text-center">${formatNumber(parseFloat(v.delivery_order_jumlah_bayar))}</td>
                        </tr>
					`);
                    // <td class="text-center"><button type="button" data-id="${v.delivery_order_id}" id="btnaddbiaya" class="btn btn-warning btnaddbiaya"><i class="fa fa-plus"></i></button></td>
                });

                $('#table_list_do > tbody').append(`
                    <tr style="background-color: #4942E4">
                        <td colspan="6" class="text-center" style="color: white">GRAND TOTAL</td>
                        <td class="text-center" style="color: white">${formatNumber(parseFloat(grandTotal))}</td>
                    </tr>
                `);
                // $('#viewJumlahTerima').val(sum_jumlah_terima);

            }

            if (response.List_DO_Pembayaran != 0) {
                $.each(response.List_DO_Pembayaran, function(i, v) {
                    arr_pembayaran.push({
                        'delivery_order_batch_id': v.delivery_order_batch_id,
                        'tipe_pembayaran_id': v.tipe_pembayaran_id,
                        'delivery_order_reff_no': v.delivery_order_reff_no,
                        'delivery_order_payment_tgl_jatuh_tempo': v
                            .delivery_order_payment_tgl_jatuh_tempo,
                        'delivery_order_payment_value': v.delivery_order_payment_value,
                        'detail': v.detail
                    });

                    var arr_temp = []
                    $.each(v.detail, function(i2, v2) {
                        arr_temp.push({
                            'tipe_pembayaran_id': v.tipe_pembayaran_id,
                            'delivery_order_reff_no': v.delivery_order_reff_no,
                            'delivery_order_id': v2.delivery_order_id
                        });

                        arr_pembayaran_detail[i] = arr_temp;
                        // arr_pembayaran_detail.push({
                        // 	'tipe_pembayaran_id': v.tipe_pembayaran_id,
                        // 	'delivery_order_reff_no': v.delivery_order_reff_no,
                        // 	'delivery_order_id': v2.delivery_order_id
                        // });
                    });

                    total_nominal_tunai += parseInt(v.delivery_order_payment_value);
                });
            } else {
                arr_pembayaran.push({
                    'delivery_order_batch_id': id,
                    'tipe_pembayaran_id': $("#DeliveryOrderPayment-tipe_pembayaran_id0").val(),
                    'delivery_order_reff_no': $("#DeliveryOrderPayment-delivery_order_reff_no0").val(),
                    'delivery_order_payment_tgl_jatuh_tempo': $(
                        "#DeliveryOrderPayment-delivery_order_payment_tgl_jatuh_tempo0").val(),
                    'delivery_order_payment_value': $("#DeliveryOrderPayment-delivery_order_payment_value0")
                        .val(),
                    'detail': []
                });
            }

            if (response.List_DO_Tunai == null || response.List_DO_Tunai == 'null') {
                $('#delivery_order_batch_nominal_invoice').val(0);
            } else {
                $('#delivery_order_batch_nominal_invoice').val(parseInt(response.List_DO_Tunai));
            }
            $('#viewJumlahTerima').val(parseInt(total_nominal_tunai));

            // GetListPembayaran();
        })
    }

    // function sumJumlahTerima() {
    //     let sum = 0
    //     $('#table_list_do > tbody > tr').each(function() {
    //         var jumlahTerima = parseInt($(this).find('#jumlahTerima').val());
    //         var jumlahTerima = isNaN(jumlahTerima) ? 0 : jumlahTerima;

    //         sum += jumlahTerima;
    //     })
    //     $('#viewJumlahTerima').val(sum);
    // }

    // $(document).on("click", "#btnaddbiaya", function() {
    //     arr_do_id = [];

    //     let do_id = $(this).attr("data-id");

    //     arr_do_id.push(
    //         do_id
    //     );

    //     $('#tableaddbiaya > tbody').html('');

    //     if (arr_add_biaya.length != 0) {
    //         $.each(arr_add_biaya, function(i, v) {
    //             if (v.do_id == do_id) {
    //                 arr_do_id.push(
    //                     do_id
    //                 );
    //             }
    //         });
    //         console.log(arr_do_id);
    //         // return false

    //         if (arr_do_id.length > 1) {
    //             var str_tipe_biaya = '';
    //             $.ajax({
    //                 type: 'POST',
    //                 url: "<?= base_url() ?>WMS/MonitoringCanvas/KonfirmasiSetoranTunaiCanvas/GetTipeBiaya",
    //                 dataType: "JSON",
    //                 success: function(response) {
    //                     if (response.tipe_biaya != 0) {
    //                         var str_tipe_biaya = '';
    //                         str_tipe_biaya +=
    //                             `<option value="">-- Pilih Nama Biaya --</option>`

    //                         for (let c = 0; c < arr_add_biaya.length; c++) {
    //                             if (arr_add_biaya[c].do_id == do_id) {
    //                                 $.each(response.tipe_biaya, function(i, v) {
    //                                     if (arr_add_biaya[c].nama_biaya == v
    //                                         .tipe_biaya_id) {
    //                                         str_tipe_biaya +=
    //                                             `<option value="${v.tipe_biaya_id}" selected>${v.tipe_biaya_nama}</option>`;
    //                                     } else {
    //                                         str_tipe_biaya +=
    //                                             `<option value="${v.tipe_biaya_id}">${v.tipe_biaya_nama}</option>`
    //                                     }

    //                                 });

    //                                 var str = '';

    //                                 str = str + '<tr>';
    //                                 str = str +
    //                                     '	<td width="40%"><select id="nama_biaya" name="nama_biaya" class="form-control select2">' +
    //                                     str_tipe_biaya + '</select></td>';
    //                                 str = str +
    //                                     '	<td width="40%"><input value="' + arr_add_biaya[c]
    //                                     .keterangan +
    //                                     '" id="keterangan" name="keterangan" type="text" class=" form-control" /></td>';
    //                                 str = str +
    //                                     '	<td width="15%"><input value="' + arr_add_biaya[c]
    //                                     .nominal_biaya +
    //                                     '" type="number" min="0" oninput="this.value = Math.abs(this.value)" id="nominal_biaya" name="nominal_biaya" type="text" class=" form-control" /></td>';
    //                                 str = str +
    //                                     '	<td width="5%"><button type="button" class="btn btn-danger btndelete" onclick="DeleteTempAddBiayaDetail(this)"><i class="fa fa-times"></i></button></td>';
    //                                 str = str + '</tr>';

    //                                 $("#tableaddbiaya > tbody").append(str);




    //                             }
    //                         }


    //                     }
    //                 }
    //             })
    //         } else {
    //             $.ajax({
    //                 type: 'POST',
    //                 url: "<?= base_url() ?>WMS/MonitoringCanvas/KonfirmasiSetoranTunaiCanvas/GetDODetail3",
    //                 data: {
    //                     do_id: do_id
    //                 },
    //                 dataType: "JSON",
    //                 success: function(response) {
    //                     var DODetail3 = response.GetDODetail3;

    //                     if (response.GetDODetail3 != 0) {
    //                         var str_tipe_biaya = '';
    //                         $.ajax({
    //                             type: 'POST',
    //                             url: "<?= base_url() ?>WMS/MonitoringCanvas/KonfirmasiSetoranTunaiCanvas/GetTipeBiaya",
    //                             dataType: "JSON",
    //                             success: function(response) {
    //                                 if (response.tipe_biaya != 0) {
    //                                     var str_tipe_biaya = '';
    //                                     str_tipe_biaya +=
    //                                         `<option value="">-- Pilih Nama Biaya --</option>`

    //                                     for (let c = 0; c < DODetail3
    //                                         .length; c++) {
    //                                         $.each(response.tipe_biaya,
    //                                             function(i, v) {
    //                                                 if (DODetail3[c]
    //                                                     .tipe_biaya_id == v
    //                                                     .tipe_biaya_id) {
    //                                                     str_tipe_biaya +=
    //                                                         `<option value="${v.tipe_biaya_id}" selected>${v.tipe_biaya_nama}</option>`;
    //                                                 } else {
    //                                                     str_tipe_biaya +=
    //                                                         `<option value="${v.tipe_biaya_id}">${v.tipe_biaya_nama}</option>`
    //                                                 }

    //                                             });

    //                                         var str = '';

    //                                         str = str + '<tr>';
    //                                         str = str +
    //                                             '	<td width="40%"><select id="nama_biaya" name="nama_biaya" class="form-control select2">' +
    //                                             str_tipe_biaya +
    //                                             '</select></td>';
    //                                         str = str +
    //                                             '	<td width="40%"><input value="' +
    //                                             DODetail3[c]
    //                                             .delivery_order_detail3_keterangan +
    //                                             '" id="keterangan" name="keterangan" type="text" class=" form-control" /></td>';
    //                                         str = str +
    //                                             '	<td width="15%"><input value="' +
    //                                             parseInt(DODetail3[c]
    //                                                 .delivery_order_detail3_nilai
    //                                             ) +
    //                                             '" type="number" min="0" oninput="this.value = Math.abs(this.value)" id="nominal_biaya" name="nominal_biaya" type="text" class=" form-control" /></td>';
    //                                         str = str +
    //                                             '	<td width="5%"><button type="button" class="btn btn-danger btndelete" onclick="DeleteTempAddBiayaDetail(this)"><i class="fa fa-times"></i></button></td>';
    //                                         str = str + '</tr>';

    //                                         $("#tableaddbiaya > tbody").append(
    //                                             str);
    //                                     }


    //                                 }
    //                             }
    //                         })
    //                     }

    //                 }
    //             })
    //         }

    //     } else {
    //         $.ajax({
    //             type: 'POST',
    //             url: "<?= base_url() ?>WMS/MonitoringCanvas/KonfirmasiSetoranTunaiCanvas/GetDODetail3",
    //             data: {
    //                 do_id: do_id
    //             },
    //             dataType: "JSON",
    //             success: function(response) {
    //                 var DODetail3 = response.GetDODetail3;

    //                 if (response.GetDODetail3 != 0) {
    //                     var str_tipe_biaya = '';
    //                     $.ajax({
    //                         type: 'POST',
    //                         url: "<?= base_url() ?>WMS/MonitoringCanvas/KonfirmasiSetoranTunaiCanvas/GetTipeBiaya",
    //                         dataType: "JSON",
    //                         success: function(response) {
    //                             if (response.tipe_biaya != 0) {
    //                                 var str_tipe_biaya = '';
    //                                 str_tipe_biaya +=
    //                                     `<option value="">-- Pilih Nama Biaya --</option>`

    //                                 for (let c = 0; c < DODetail3.length; c++) {
    //                                     $.each(response.tipe_biaya, function(i, v) {
    //                                         if (DODetail3[c].tipe_biaya_id == v
    //                                             .tipe_biaya_id) {
    //                                             str_tipe_biaya +=
    //                                                 `<option value="${v.tipe_biaya_id}" selected>${v.tipe_biaya_nama}</option>`;
    //                                         } else {
    //                                             str_tipe_biaya +=
    //                                                 `<option value="${v.tipe_biaya_id}">${v.tipe_biaya_nama}</option>`
    //                                         }

    //                                     });

    //                                     var str = '';

    //                                     str = str + '<tr>';
    //                                     str = str +
    //                                         '	<td width="40%"><select id="nama_biaya" name="nama_biaya" class="form-control select2">' +
    //                                         str_tipe_biaya + '</select></td>';
    //                                     str = str +
    //                                         '	<td width="40%"><input value="' +
    //                                         DODetail3[c]
    //                                         .delivery_order_detail3_keterangan +
    //                                         '" id="keterangan" name="keterangan" type="text" class=" form-control" /></td>';
    //                                     str = str +
    //                                         '	<td width="15%"><input value="' +
    //                                         parseInt(DODetail3[c]
    //                                             .delivery_order_detail3_nilai) +
    //                                         '" type="number" min="0" oninput="this.value = Math.abs(this.value)" id="nominal_biaya" name="nominal_biaya" type="text" class=" form-control" /></td>';
    //                                     str = str +
    //                                         '	<td width="5%"><button type="button" class="btn btn-danger btndelete" onclick="DeleteTempAddBiayaDetail(this)"><i class="fa fa-times"></i></button></td>';
    //                                     str = str + '</tr>';

    //                                     $("#tableaddbiaya > tbody").append(str);
    //                                 }


    //                             }
    //                         }
    //                     })
    //                 }

    //             }
    //         })
    //     }

    //     console.log(arr_add_biaya);

    //     $('#modalAddBiaya').modal('show');
    // })

    // $('#btnaddbiayadetail').click(function() {
    //     $.ajax({
    //         type: 'POST',
    //         url: "<?= base_url() ?>WMS/MonitoringCanvas/KonfirmasiSetoranTunaiCanvas/GetTipeBiaya",
    //         dataType: "JSON",
    //         success: function(response) {

    //             if (response.tipe_biaya != 0) {
    //                 var str_tipe_biaya = '';
    //                 str_tipe_biaya +=
    //                     `<option value="">-- Pilih Nama Biaya --</option>`

    //                 $.each(response.tipe_biaya, function(i, v) {
    //                     str_tipe_biaya +=
    //                         `<option value="${v.tipe_biaya_id}">${v.tipe_biaya_nama}</option>`
    //                 });

    //                 var str = '';

    //                 str = str + '<tr>';
    //                 str = str +
    //                     '	<td width="40%"><select id="nama_biaya" name="nama_biaya" class="form-control select2">' +
    //                     str_tipe_biaya + '</select></td>';
    //                 str = str +
    //                     '	<td width="40%"><input id="keterangan" name="keterangan" type="text" class=" form-control" /></td>';
    //                 str = str +
    //                     '	<td width="15%"><input type="number" min="0" oninput="this.value = Math.abs(this.value)" id="nominal_biaya" name="nominal_biaya" type="text" class=" form-control" /></td>';
    //                 str = str +
    //                     '	<td width="5%"><button type="button" class="btn btn-danger btndelete" onclick="DeleteTempAddBiayaDetail(this)"><i class="fa fa-times"></i></button></td>';
    //                 str = str + '</tr>';

    //                 $("#tableaddbiaya > tbody").append(str);
    //             }
    //         }
    //     })
    // });

    // $('#btnsimpanaddbiaya').click(function() {
    //     let boolean = true;


    //     var tableaddbiaya = $('#tableaddbiaya > tbody tr').length;
    //     console.log(tableaddbiaya);
    //     // return false;

    //     if (tableaddbiaya == 0) {
    //         if (arr_del_biaya != 0) {
    //             $.ajax({
    //                 type: 'post',
    //                 url: "<?= base_url() ?>WMS/MonitoringCanvas/KonfirmasiSetoranTunaiCanvas/deleteDODetail3ByID",
    //                 data: {
    //                     id: arr_del_biaya[0]
    //                 },
    //                 dataType: 'JSON',
    //                 success: function(response) {
    //                     if (response != 1) {
    //                         message("Error!", "Gagal simpan, ID tidak ditemukan",
    //                             "error");
    //                     }
    //                 }
    //             })
    //         }
    //     }


    //     if ('#tableaddbiaya > tbody tr')

    //         $('#tableaddbiaya > tbody tr').each(function() {
    //             let nama_biaya = $(this).find("td:eq(0) select[id='nama_biaya']").val();
    //             let nominal_biaya = $(this).find("td:eq(2) input[id='nominal_biaya']").val();

    //             if (nama_biaya == '') {
    //                 message("Error!", "Nama Biaya masih kosong, silahkan pilih nama biaya terlebih dahulu",
    //                     "error");
    //                 boolean = false;
    //             } else if (nominal_biaya == '') {
    //                 message("Error!", "Nominal Biaya masih kosong, silahkan isi nominal biaya terlebih dahulu",
    //                     "error");
    //                 boolean = false;
    //             }
    //         });



    //     if (boolean == true) {
    //         if (arr_add_biaya.length != 0) {
    //             for (let i = 0; i < arr_add_biaya.length; i++) {
    //                 if (arr_add_biaya[i].do_id == arr_do_id[0]) {
    //                     // console.log(arr_add_biaya[i].do_id, arr_do_id[0]);
    //                     arr_add_biaya.splice(i, 1);
    //                     i--;
    //                 }
    //             }
    //             console.log(arr_add_biaya);

    //             // return false;
    //         }

    //         $('#tableaddbiaya > tbody tr').each(function() {
    //             let nama_biaya = $(this).find("td:eq(0) select[id='nama_biaya']").val();
    //             let keterangan = $(this).find("td:eq(1) input[id='keterangan']").val();
    //             let nominal_biaya = $(this).find("td:eq(2) input[id='nominal_biaya']").val();

    //             arr_add_biaya.push({
    //                 do_id: arr_do_id[0],
    //                 nama_biaya,
    //                 keterangan,
    //                 nominal_biaya
    //             })

    //         });

    //         $('#modalAddBiaya').modal('hide');
    //     }

    // });

    function formatNumber(num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    }

    $('#btn_simpan_all').click(function() {
        var jumlahTerima = $('#viewJumlahTerima').val();
        var id_do_batch = $('#id_do_batch').val();
        let arr_do = [];
        let boolean = true;

        $('#table_list_do > tbody tr').each(function() {
            let tipe_pembayaran = $(this).find("td:eq(6)").text();

            if (tipe_pembayaran == "TUNAI") {
                if (jumlahTerima == 0) {
                    message("Error!",
                        "Jumlah Terima tidak boleh 0, silahkan isi jumlah terima terlebih dahulu",
                        "error");
                    boolean = false;
                }
            }

            if (tipe_pembayaran != "TUNAI") {
                if (arr_pembayaran.length == 0) {
                    message("Error!", "Tidak ada Pembayaran Non Tunai", "error");
                    boolean = false;
                }
            }
        });

        $("#table_list_pembayaran tbody tr").each(function(i, v) {
            // var do_batch_id = $(this).find("td:eq(0) input[type='hidden']").val();
            // var do_payment_id = $(this).find("td:eq(1) select").val();
            var do_reff_no = $(this).find("td:eq(2) .DeliveryOrderPayment-delivery_order_reff_no").val();
            // var do_jatuh_tempo = $(this).find(
            //     "td:eq(3) .DeliveryOrderPayment-delivery_order_payment_tgl_jatuh_tempo").val();
            var do_value = $(this).find("td:eq(4) .DeliveryOrderPayment-delivery_order_payment_value")
                .val();

            arr_pembayaran[i].delivery_order_payment_value = do_value;
            arr_pembayaran[i].delivery_order_reff_no = do_reff_no;
        });

        $.each(arr_pembayaran, function(i, v) {
            if (v.detail.length == 0) {
                message("Error", "Pilih Surat Jalan", "error");
                boolean = false;
            }
        });

        // console.log(arr_pembayaran);
        // return false;

        if (boolean == true) {
            Swal.fire({
                title: "Apakah anda yakin?",
                text: "Pastikan data yang sudah anda input benar!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Simpan",
                cancelButtonText: "Tidak, Tutup"
            }).then((result) => {
                if (result.value == true) {
                    // $('#table_list_do > tbody tr').each(function() {
                    //     let do_id = $(this).find("td:eq(0) input[type='hidden']").val();
                    //     let jumlah_terima = $(this).find("td:eq(7) input[type='number']").val();

                    //     if (jumlah_terima == undefined) {
                    //         jumlah_terima = '';
                    //     }

                    //     arr_do.push({
                    //         delivery_order_id: do_id,
                    //         delivery_order_nominal_terima_tunai: jumlah_terima
                    //     })
                    // });

                    requestAjax(
                        "<?= base_url() ?>WMS/MonitoringCanvas/KonfirmasiSetoranTunaiCanvas/saveAllDO", {
                            id_do_batch: id_do_batch,
                            jumlahTerima: jumlahTerima,
                            do_pembayaran: arr_pembayaran,
                            delivery_order_batch_nominal_invoice: $(
                                "#delivery_order_batch_nominal_invoice").val(),
                            lastUpdate: $("#lastUpdateTgl").val()
                        }, "POST", "JSON",
                        function(response) {
                            if (response.type == 200) {
                                message_topright("success", "Data berhasil disimpan");
                                setTimeout(() => {
                                    location.href =
                                        "<?= base_url() ?>WMS/MonitoringCanvas/KonfirmasiSetoranTunaiCanvas/KonfirmasiSetoranTunaiCanvasMenu";
                                }, 500);
                            } else if (response.type == 203) {
                                return messageNotSameLastUpdated();
                            } else {
                                message_topright("error", "Data gagal disimpan");
                            }
                        }, "#btn_simpan_all")
                }
            })

        }
    })

    function DeleteTempAddBiayaDetail(Idx) {
        var row = Idx.parentNode.parentNode;
        row.parentNode.removeChild(row);

        var do_id = $('#btnaddbiaya').attr("data-id");
        arr_del_biaya.push(do_id);

    }

    function GetSuratTugasPengirimanMenu() {
        requestAjax("<?= base_url('WMS/MonitoringCanvas/KonfirmasiSetoranTunaiCanvas/GetSuratTugasPengirimanMenu') ?>", {},
            "POST", "JSON",
            function(response) {
                if (response) {
                    ChSuratTugasPengirimanMenu(response);
                }
            })
    }

    function ChSuratTugasPengirimanMenu(JSONChannel) {
        $("#tablefdjrmenu > tbody").html('');
        $("#filter_fdjr_driver").html('');
        $("#filter_fdjr_status").html('');

        // var Channel = JSON.parse(JSONChannel);
        var Channel = JSONChannel;

        if (Channel.Driver != 0) {
            $("#filter_fdjr_driver").append('<option value="">All</option>');

            for (let i = 0; i < Channel.Driver.length; i++) {
                karyawan_id = Channel.Driver[i].karyawan_id;
                karyawan_nama = Channel.Driver[i].karyawan_nama;
                $("#filter_fdjr_driver").append('<option value="' + karyawan_id + '">' + karyawan_nama + '</option>');
            }
        }

        if (Channel.StatusFDJR != 0) {
            $("#filter_fdjr_status").append('<option value="">All</option>');

            for (i = 0; i < Channel.StatusFDJR.length; i++) {
                delivery_order_batch_status = Channel.StatusFDJR[i].delivery_order_batch_status;
                $("#filter_fdjr_status").append('<option value="' + delivery_order_batch_status + '">' +
                    delivery_order_batch_status + '</option>');
            }
        }
    }

    $('#btnviewfdjr').click(function() {
        var Tgl_FDJR = $("#filter_fdjr_date").val();
        var No_FDJR = $("#filter_fdjr_no").val();
        var karyawan_id = $("#filter_fdjr_driver").val();
        var Status_FDJR = $('#filter_fdjr_status').val();

        $('#loadingview').show();

        requestAjax(
            "<?= base_url('WMS/MonitoringCanvas/KonfirmasiSetoranTunaiCanvas/GetSuratTugasPengirimanTable') ?>", {
                Tgl_FDJR: Tgl_FDJR,
                No_FDJR: No_FDJR,
                karyawan_id: karyawan_id,
                Status_FDJR: Status_FDJR
            }, "POST", "JSON",
            function(response) {
                if (response) {
                    // $('#tablefdjrmenu').DataTable().clear().draw();
                    ChSuratTugasPengirimanTable(response);
                }
            })
    });

    function ChSuratTugasPengirimanTable(Channel) {

        $('#tablefdjrmenu > tbody').html('');

        var no = 1

        if (Channel.SuratTugasPengiriman != 0) {
            // if ($.fn.DataTable.isDataTable('#tablefdjrmenu')) {
            //     $('#tablefdjrmenu').DataTable().destroy();
            // }

            $('#tablefdjrmenu tbody').empty();

            var arr = [];

            $.each(Channel.SuratTugasPengiriman, function(i, v) {
                if (arr[v.delivery_order_batch_kode]) {
                    arr[v.delivery_order_batch_kode] += 1;
                } else {
                    arr[v.delivery_order_batch_kode] = 1;
                }
            });

            var cek_do_batch_kode = '';

            for (let i = 0; i < Channel.SuratTugasPengiriman.length; i++) {
                var delivery_order_batch_id = Channel.SuratTugasPengiriman[i].delivery_order_batch_id;
                var delivery_order_batch_kode = Channel.SuratTugasPengiriman[i].delivery_order_batch_kode;
                var picking_list_id = Channel.SuratTugasPengiriman[i].picking_list_id;
                var picking_list_kode = Channel.SuratTugasPengiriman[i].picking_list_kode;
                var picking_order_id = Channel.SuratTugasPengiriman[i].picking_order_id;
                var picking_order_kode = Channel.SuratTugasPengiriman[i].picking_order_kode;
                var delivery_order_batch_tanggal_kirim = Channel.SuratTugasPengiriman[i].delivery_order_batch_tanggal_kirim;
                var delivery_order_batch_status = Channel.SuratTugasPengiriman[i].delivery_order_batch_status;
                var karyawan_id = Channel.SuratTugasPengiriman[i].karyawan_id;
                var karyawan_nama = Channel.SuratTugasPengiriman[i].karyawan_nama;
                var tipe_delivery_order_id = Channel.SuratTugasPengiriman[i].tipe_delivery_order_id;
                var tipe_delivery_order_alias = Channel.SuratTugasPengiriman[i].tipe_delivery_order_alias;
                var serah_terima_kirim_id = Channel.SuratTugasPengiriman[i].serah_terima_kirim_id;
                var serah_terima_kirim_kode = Channel.SuratTugasPengiriman[i].serah_terima_kirim_kode;
                var settlement_status = Channel.SuratTugasPengiriman[i].settlement_status;

                var strmenu = '';

                if (arr[delivery_order_batch_kode]) {
                    if (cek_do_batch_kode == delivery_order_batch_kode) {
                        strmenu += '<tr>';
                        // strmenu += '	<td><a href="<?php echo base_url(); ?>WMS/Distribusi/DeliveryOrderBatch/detail/?id=' +
                        //     delivery_order_batch_id + '" class="btn btn-link" target="_blank">' + delivery_order_batch_kode +
                        //     '</a></td>';
                        strmenu +=
                            '	<td style="vertical-align: middle; text-align: center;"><a href="<?php echo base_url(); ?>WMS/Distribusi/PermintaanBarang/PickingDetail/' +
                            picking_list_id + '" class="btn btn-link" target="_blank">' + picking_list_kode + '</a></td>';
                        strmenu +=
                            '	<td style="vertical-align: middle; text-align: center;"><a href="<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/DetailPengeluaranBarangMenu/?picking_order_kode=' +
                            picking_order_kode + '" class="btn btn-link" target="_blank">' + picking_order_kode +
                            '</a></td>';
                        strmenu +=
                            '	<td style="vertical-align: middle; text-align: center;"><a href="<?php echo base_url(); ?>WMS/Distribusi/PengirimanBarang/PengirimanBarangDetail/' +
                            serah_terima_kirim_id + '" class="btn btn-link" target="_blank">' + serah_terima_kirim_kode +
                            '</a></td>';
                        // strmenu += '	<td><a href="<?php echo base_url(); ?>WMS/SuratTugasPengiriman/ClosingPengirimanMenu/?delivery_order_batch_id=' + delivery_order_batch_id + '" class="btn btn-link" target="_blank">Closing Pengiriman</a></td>';
                        // if (delivery_order_batch_status == "in transit" || delivery_order_batch_status ==
                        //     "In Process Closing Delivery") {
                        //     strmenu +=
                        //         '	<td><a href="<?php echo base_url(); ?>WMS/SuratTugasPengiriman/ClosingPengirimanMenu/?delivery_order_batch_id=' +
                        //         delivery_order_batch_id + '" class="btn btn-link" target="_blank">Closing Pengiriman</a></td>';
                        // } else if (settlement_status != "Settlement Success" && delivery_order_batch_status == "completed") {
                        //     strmenu +=
                        //         '	<td><a href="<?php echo base_url(); ?>WMS/SuratTugasPengiriman/SettlementMenu/?delivery_order_batch_id=' +
                        //         delivery_order_batch_id + '" class="btn btn-link" target="_blank">Settlement</a></td>';
                        // } else if (settlement_status == "Settlement Success" && delivery_order_batch_status == "completed") {
                        //     strmenu +=
                        //         '	<td><a href="<?php echo base_url(); ?>WMS/SuratTugasPengiriman/SettlementMenu/?delivery_order_batch_id=' +
                        //         delivery_order_batch_id + '" class="btn btn-link" target="_blank">View Settlement</a></td>';
                        // } else {
                        // strmenu +=
                        //     '	<td><a href="<?php echo base_url(); ?>WMS/MonitoringCanvas/KonfirmasiSetoranTunaiCanvas/ClosingPenerimaan/?id=' +
                        //     delivery_order_batch_id + '" class="btn btn-info">Closing Penerimaan</a></td>';
                        // }
                        strmenu += '</tr>';
                    } else {
                        cek_do_batch_kode = delivery_order_batch_kode;

                        strmenu += '<tr>';
                        strmenu += '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[
                                delivery_order_batch_kode] +
                            '">' + no + '</td>';
                        strmenu += '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[
                                delivery_order_batch_kode] +
                            '">' +
                            delivery_order_batch_tanggal_kirim + '</td>';
                        strmenu += '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[
                                delivery_order_batch_kode] +
                            '">' + karyawan_nama + '</td>';
                        strmenu += '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[
                                delivery_order_batch_kode] +
                            '">' + delivery_order_batch_status +
                            '</td>';
                        strmenu += '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[
                                delivery_order_batch_kode] +
                            '">' + tipe_delivery_order_alias +
                            '</td>';
                        strmenu += '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[
                                delivery_order_batch_kode] +
                            '"><a href="<?php echo base_url(); ?>WMS/Distribusi/DeliveryOrderBatch/detail/?id=' +
                            delivery_order_batch_id + '" class="btn btn-link" target="_blank">' +
                            delivery_order_batch_kode +
                            '</a></td>';
                        strmenu +=
                            '	<td style="vertical-align: middle; text-align: center;"><a href="<?php echo base_url(); ?>WMS/Distribusi/PermintaanBarang/PickingDetail/' +
                            picking_list_id + '" class="btn btn-link" target="_blank">' + picking_list_kode + '</a></td>';
                        strmenu +=
                            '	<td style="vertical-align: middle; text-align: center;"><a href="<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/DetailPengeluaranBarangMenu/?picking_order_kode=' +
                            picking_order_kode + '" class="btn btn-link" target="_blank">' + picking_order_kode +
                            '</a></td>';
                        strmenu +=
                            '	<td style="vertical-align: middle; text-align: center;"><a href="<?php echo base_url(); ?>WMS/Distribusi/PengirimanBarang/PengirimanBarangDetail/' +
                            serah_terima_kirim_id + '" class="btn btn-link" target="_blank">' + serah_terima_kirim_kode +
                            '</a></td>';
                        strmenu += '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[
                            delivery_order_batch_kode] + '">' + settlement_status + '</td>';
                        // strmenu += '	<td><a href="<?php echo base_url(); ?>WMS/SuratTugasPengiriman/ClosingPengirimanMenu/?delivery_order_batch_id=' + delivery_order_batch_id + '" class="btn btn-link" target="_blank">Closing Pengiriman</a></td>';
                        // if (delivery_order_batch_status == "in transit" || delivery_order_batch_status ==
                        //     "In Process Closing Delivery") {
                        //     strmenu +=
                        //         '	<td><a href="<?php echo base_url(); ?>WMS/SuratTugasPengiriman/ClosingPengirimanMenu/?delivery_order_batch_id=' +
                        //         delivery_order_batch_id + '" class="btn btn-link" target="_blank">Closing Pengiriman</a></td>';
                        // } else if (settlement_status != "Settlement Success" && delivery_order_batch_status == "completed") {
                        //     strmenu +=
                        //         '	<td><a href="<?php echo base_url(); ?>WMS/SuratTugasPengiriman/SettlementMenu/?delivery_order_batch_id=' +
                        //         delivery_order_batch_id + '" class="btn btn-link" target="_blank">Settlement</a></td>';
                        // } else if (settlement_status == "Settlement Success" && delivery_order_batch_status == "completed") {
                        //     strmenu +=
                        //         '	<td><a href="<?php echo base_url(); ?>WMS/SuratTugasPengiriman/SettlementMenu/?delivery_order_batch_id=' +
                        //         delivery_order_batch_id + '" class="btn btn-link" target="_blank">View Settlement</a></td>';
                        // } else {
                        if (delivery_order_batch_status == 'in transit' || delivery_order_batch_status ==
                            'Closing Delivery Confirm' || delivery_order_batch_status == 'In Process Receiving Outlet' ||
                            delivery_order_batch_status == 'In Process Closing') {
                            strmenu +=
                                '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[
                                    delivery_order_batch_kode] +
                                '"><a href="<?php echo base_url(); ?>WMS/MonitoringCanvas/KonfirmasiSetoranTunaiCanvas/ClosingPenerimaan/?id=' +
                                delivery_order_batch_id + '" class="btn btn-info">Closing Penerimaan</a></td>';
                        } else {
                            strmenu +=
                                '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[
                                    delivery_order_batch_kode] +
                                '" hidden><a href="<?php echo base_url(); ?>WMS/MonitoringCanvas/KonfirmasiSetoranTunaiCanvas/ClosingPenerimaan/?id=' +
                                delivery_order_batch_id + '" class="btn btn-info">Closing Penerimaan</a></td>';
                        }
                        // }
                        strmenu += '</tr>';
                    }
                }

                no++;

                $("#tablefdjrmenu > tbody").append(strmenu);
            }

            $("#loadingview").hide();

            // $('#tablefdjrmenu').DataTable({
            //     "lengthMenu": [
            //         [50],
            //         [50]
            //     ]
            // });
        } else {
            ResetForm();
        }
    }

    $('#btn_add_pembayaran').click(function() {

        <?php if ($act != "index") { ?>
            arr_pembayaran.push({
                'delivery_order_batch_id': $("#id_do_batch").val(),
                'tipe_pembayaran_id': '',
                'delivery_order_reff_no': '',
                'delivery_order_payment_tgl_jatuh_tempo': '',
                'delivery_order_payment_value': '',
                'detail': []
            });

            GetListPembayaran();
        <?php } ?>
        // console.log(arr_pembayaran);
    });

    function GetListPembayaran() {
        total_nominal_tunai = 0;

        $("#loadingaddpembayaran").show();

        // $("#table_list_pembayaran > tbody").html('');
        // $("#table_list_pembayaran > tbody").empty();

        if (arr_pembayaran.length > 0) {
            $.each(arr_pembayaran, function(i, v) {
                console.log(arr_pembayaran.length);
                $("#table_list_pembayaran > tbody").append(`
					<tr>
						<td class="text-center">${i+1}</td>
						<td class="text-center">
							<select id="DeliveryOrderPayment-tipe_pembayaran_id${i}" name="DeliveryOrderPayment[tipe_pembayaran_id${i}]" class="form-control" style="width:100%" onChange="handleOnChange('${i}', this)">
							<?php if (count($TipePembayaran) != 0) {
                                foreach ($TipePembayaran as $row) { ?>
								<option value="<?= $row['tipe_pembayaran_id'] ?>" ${v.tipe_pembayaran_id == '<?= $row['tipe_pembayaran_id'] ?>' ? 'selected' : ''}><?= $row['tipe_pembayaran_nama'] ?></option>
								<?php }
                            } ?>
							</select>
						</td>
						<td class="text-center">
							<input type="text" class="form-control" id="DeliveryOrderPayment-delivery_order_reff_no${i}" name="DeliveryOrderPayment[delivery_order_reff_no${i}]" autocomplete="off" value="${v.delivery_order_reff_no}" onChange="UpdateListPembayaran('${i}')" />
						</td>
						<td class="text-center">
							<input ${v.tipe_pembayaran_id == '0' || v.tipe_pembayaran_id == '' ? `disabled`: ``} type="date" class="form-control" id="DeliveryOrderPayment-delivery_order_payment_tgl_jatuh_tempo${i}" name="DeliveryOrderPayment[delivery_order_payment_tgl_jatuh_tempo${i}]" autocomplete="off" value="${v.delivery_order_payment_tgl_jatuh_tempo}" onChange="UpdateListPembayaran('${i}')" />
						</td>
						<td class="text-center">
							<input type="text" class="form-control DeliveryOrderPayment-delivery_order_payment_value" id="DeliveryOrderPayment-delivery_order_payment_value${i}" name="DeliveryOrderPayment[delivery_order_payment_value${i}]" autocomplete="off" value="${v.delivery_order_payment_value}" onChange="UpdateListPembayaran('${i}')" />
						</td>
						<td class="text-center">
							<button type="button" class="btn btn-sm btn-primary" onClick="ViewModalPembayaranDetail('${i}')"><i class="fa fa-add"></i></button>
							<button type="button" class="btn btn-sm btn-danger" onClick="DeletePembayaran('${i}')"><i class="fa fa-trash"></i></button>
						</td>
					</tr>
				`);

                total_nominal_tunai += parseInt(v.delivery_order_payment_value);
            });

            $('#viewJumlahTerima').val(parseInt(total_nominal_tunai));
        }

        $("#loadingaddpembayaran").hide();
    }

    function handleOnChange(i, e) {
        UpdateListPembayaran(i);

        var value = e.value;

        if (value !== '0') {
            $("#DeliveryOrderPayment-delivery_order_payment_tgl_jatuh_tempo" + i).removeAttr("disabled");
        } else {
            $("#DeliveryOrderPayment-delivery_order_payment_tgl_jatuh_tempo" + i).val('');
            $("#DeliveryOrderPayment-delivery_order_payment_tgl_jatuh_tempo" + i).attr("disabled", true);
        }
    }

    function UpdateListPembayaran(idx) {

        <?php if ($act != "index") { ?>
            arr_pembayaran[idx].delivery_order_batch_id = $("#id_do_batch").val();
            arr_pembayaran[idx].tipe_pembayaran_id = $("#DeliveryOrderPayment-tipe_pembayaran_id" + idx + " option:selected")
                .val();
            arr_pembayaran[idx].delivery_order_reff_no = $("#DeliveryOrderPayment-delivery_order_reff_no" + idx).val();
            arr_pembayaran[idx].delivery_order_payment_tgl_jatuh_tempo = $(
                "#DeliveryOrderPayment-delivery_order_payment_tgl_jatuh_tempo" + idx).val();
            arr_pembayaran[idx].delivery_order_payment_value = $("#DeliveryOrderPayment-delivery_order_payment_value" + idx)
                .val();
            // console.log(arr_pembayaran);

            GetListPembayaran();
        <?php } ?>
    }

    function appendPayment(idx) {
        var payment = $("#DeliveryOrderPayment-delivery_order_payment_value" + idx).val();

        $("#viewJumlahTerima").val(payment);
    }

    function UpdateListPembayaranDetail() {
        var arr_temp_pembayaran = [];
        var idx = $("#filter_index").val();
        var count_do_list = $("#jml_do").val();

        for (var i = 0; i < count_do_list; i++) {
            var checked = $('[id="check-do-' + i + '"]:checked').length;
            var do_id = $("#check-do-" + i).val();

            if (checked > 0) {
                arr_temp_pembayaran.push({
                    'tipe_pembayaran_id': $("#DeliveryOrderPayment-tipe_pembayaran_id" + idx).val(),
                    'delivery_order_reff_no': $("#DeliveryOrderPayment-delivery_order_reff_no" + idx).val(),
                    'delivery_order_id': do_id
                });
            }
        }

        arr_pembayaran_detail[idx] = arr_temp_pembayaran;

        // return false;

        if (arr_pembayaran_detail.length > 0) {
            arr_pembayaran[idx].detail = arr_pembayaran_detail[idx];

            $("#modal_pembayaran_detail").modal('hide');

            // GetListPembayaran();

        } else {
            message("Error", "Pilih Surat Jalan", "error");
        }
        console.log(arr_pembayaran);
    }


    function ViewModalPembayaranDetail(idx) {
        var id = $('#id_do_batch').val();

        $("#filter_index").val(idx);
        $("#filter_referensi_dokumen").val($("#DeliveryOrderPayment-delivery_order_reff_no" + idx).val());
        $("#filter_tipe_pembayaran").val($("#DeliveryOrderPayment-tipe_pembayaran_id" + idx + " option:selected").text());
        $("#filter_tgl_jatuh_tempo").val($("#DeliveryOrderPayment-delivery_order_payment_tgl_jatuh_tempo" + idx).val());
        $("#filter_nilai_bayar").val($("#DeliveryOrderPayment-delivery_order_payment_value" + idx).val());

        $("#modal_pembayaran_detail").modal('show');
        requestAjax("<?= base_url('WMS/MonitoringCanvas/KonfirmasiSetoranTunaiCanvas/Get_list_delivery_order') ?>", {
            id: id,
            do_pembayaran: arr_pembayaran_detail[idx]
        }, "POST", "JSON", function(response) {
            $("#table_list_pembayaran_detail > tbody").html('');
            $("#table_list_pembayaran_detail > tbody").empty();

            if (response != 0) {
                $("#jml_do").val(response.length);
                $.each(response, function(i, v) {
                    // && $("#DeliveryOrderPayment-tipe_pembayaran_id" + idx + " option:selected").val() == v.tipe_pembayaran_id
                    // <input type="checkbox" name="CheckboxDO" id="check-do-${i}" value="${v.delivery_order_id}" ${v.checked != '0' && $("#DeliveryOrderPayment-delivery_order_reff_no" + idx).val() == v.delivery_order_reff_no  ? 'checked' : ''}>
                    $('#table_list_pembayaran_detail > tbody').append(`
							<tr>
								<td class="text-center">
									<input type="checkbox" name="CheckboxDO" id="check-do-${i}" value="${v.delivery_order_id}" checked>
								</td>
								<td class="text-center">${v.delivery_order_kode}</td>
								<td class="text-center">${v.sales_order_kode}</td>
								<td class="text-center">${v.sales_order_no_po}</td>
								<td class="text-center">${v.client_pt_nama}</td>
								<td class="text-center">${v.tipe_pembayaran_nama}</td>
							</tr>
						`);
                });
            }
        })
    }

    function DeletePembayaran(idx) {

        arr_pembayaran.splice(idx, 1);
        arr_pembayaran_detail.splice(idx, 1);

        // console.log(arr_pembayaran);

        GetListPembayaran();
    }

    function
    ResetForm() {
        $("#loadingview").hide();
    }

    // function message(msg, msgtext, msgtype) {
    // 	Swal.
    // 	fire(
    // 		msg, msgtext, msgtype);
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
    // 	})
    // }
</script>