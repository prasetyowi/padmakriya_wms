<script type="text/javascript">
    var arrPallet = [];
    $(document).ready(function() {
        $("#table_pallet").dataTable();
    })

    function confirmAlert(title, text, icon, btnYes, btnNo) {
        return Swal.fire({
            title: title,
            text: text,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#c9302c",
            confirmButtonText: btnYes,
            cancelButtonText: btnNo
        })
    }

    function selectPallet(pallet_id, event) {
        var index = arrPallet.findIndex(function(item) {
            return item.pallet_id === pallet_id;
        });

        if (!event.target.checked) {
            if (index !== -1) {
                arrPallet.splice(index, 1);
            }
        } else {
            arrPallet.push({
                pallet_id: pallet_id
            });
        }
    }

    $("#nonAktifPallet").on('click', function() {
        if (arrPallet.length < 1) {
            message_topright('warning', 'Harap setidaknya pilih 1 pallet!');
            return false;
        }

        confirmAlert('Apakah anda yakin ingin non-aktifkan pallet yg dipilih?',
            'Tekan ya untuk melanjutkan dan tidak untuk kembali',
            'warning', 'Ya, non-aktifkan', 'Tidak').then((result) => {
            if (result.value == true) {
                $.ajax({
                    type: "POST",
                    url: "<?= base_url('WMS/Maintenance/NonAktifPallet2/NonAktifPalletAll') ?>",
                    data: {
                        arrPallet: arrPallet
                    },
                    dataType: "JSON",
                    success: function(response) {
                        if (response == 1) {
                            message_topright('success',
                                'Pallet yg dipilih berhasil di non-aktifkan');
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        } else {
                            message_topright('error',
                                'Pallet yg dipilih gagal di non-aktifkan');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX error: " + status + ", " + error);
                    }
                })
            }
        })
    })
</script>