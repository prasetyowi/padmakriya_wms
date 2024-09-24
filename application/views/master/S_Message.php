<script type="text/javascript">
/*	MESSAGE :
	**** ERROR ****
		01xxxx - Gagal Save
		02xxxx - Gagal Update
		03xxxx - Gagal Delete
		
		1xxxxx - Input tidak ada.
		2xxxxx - Input tidak sesuai Kriteria / Rules
		3xxxxx - Berhubungan dengan Perangkat
		
	**** INFO ****
		4xxxxx - Input dibutuhkan
		
	**** SUCCESS ****
		91xxxx - Berhasil Save
		92xxxx - Berhasil Update
		93xxxx - Berhasil Delete
*/
</script>

<input type="hidden" name="MESSAGE-010001" /> 	<!-- Data Gagal Disimpan! -->
<input type="hidden" name="MESSAGE-010002" /> 	<!-- Kode Mutasi Ini Belum Disimpan! -->
<input type="hidden" name="MESSAGE-010003" /> 	<!-- Tidak dapat simpan, SKU kosong! silahkan pilih sku! -->
<input type="hidden" name="MESSAGE-010004" /> 	<!-- Tidak dapat simpan, Expired date tidak boleh kosong! -->
<input type="hidden" name="MESSAGE-010005" /> 	<!-- Tidak dapat simpan, Jumlah barang tidak boleh kosong! -->
<input type="hidden" name="MESSAGE-010006" /> 	<!-- Tidak dapat simpan, SKU kosong!!! silahkan tutup dan buat packing kembali! -->

<input type="hidden" name="MESSAGE-100001" /> 	<!-- Pilih Area terlebih dahulu -->
<input type="hidden" name="MESSAGE-100002" /> 	<!-- Pilih Checker terlebih dahulu -->
<input type="hidden" name="MESSAGE-100003" /> 	<!-- Pilih Lokasi terlebih dahulu -->
<input type="hidden" name="MESSAGE-100004" /> 	<!-- Pilih No Mutasi Draft terlebih dahulu -->
<input type="hidden" name="MESSAGE-100005" /> 	<!-- Pilih Pallet terlebih dahulu -->
<input type="hidden" name="MESSAGE-100006" /> 	<!-- Pilih Principle terlebih dahulu -->
<input type="hidden" name="MESSAGE-100007" /> 	<!-- Pilih Tipe terlebih dahulu -->
<input type="hidden" name="MESSAGE-100008" /> 	<!-- Pilih Tipe Layanan terlebih dahulu -->
<input type="hidden" name="MESSAGE-100009" /> 	<!-- Pilih Tipe Stock terlebih dahulu -->
<input type="hidden" name="MESSAGE-100010" /> 	<!-- Pilih Tipe Transaksi terlebih dahulu -->

<input type="hidden" name="MESSAGE-101001" /> 	<!-- Bukti cek fisik tidak boleh kosong! -->
<input type="hidden" name="MESSAGE-100002" /> 	<!-- Expired Date tidak boleh kosong -->
<input type="hidden" name="MESSAGE-100003" /> 	<!-- Jasa Pengangkut tidak boleh kosong -->
<input type="hidden" name="MESSAGE-101004" /> 	<!-- Kode Pallet tidak boleh kosong! -->
<input type="hidden" name="MESSAGE-101005" /> 	<!-- Lokasi Bin Tujuan tidak boleh kosong! -->
<input type="hidden" name="MESSAGE-101006" /> 	<!-- Nama Pengemudi tidak boleh kosong -->
<input type="hidden" name="MESSAGE-101007" /> 	<!-- No. Draft Mutasi tidak boleh kosong! -->
<input type="hidden" name="MESSAGE-101008" /> 	<!-- No. Kendaraan tidak boleh kosong -->
<input type="hidden" name="MESSAGE-101009" /> 	<!-- Penerimaan tidak boleh kosong! -->
<input type="hidden" name="MESSAGE-101010" /> 	<!-- Perusahaan tidak boleh kosong! -->
<input type="hidden" name="MESSAGE-101011" /> 	<!-- Jenis Pallet tidak boleh kosong! -->
<input type="hidden" name="MESSAGE-101012" /> 	<!-- Gudang Tujuan tidak boleh kosong! -->
<input type="hidden" name="MESSAGE-101013" /> 	<!-- Tipe Penerimaan tidak boleh kosong! -->
<input type="hidden" name="MESSAGE-101014" /> 	<!-- Status tidak boleh kosong! -->
<input type="hidden" name="MESSAGE-101015" /> 	<!-- No. Surat Jalan tidak boleh kosong! -->
<input type="hidden" name="MESSAGE-101016" /> 	<!-- Reason tidak boleh kosong! -->
<input type="hidden" name="MESSAGE-101017" /> 	<!-- Nama packer tidak boleh kosong! -->
<input type="hidden" name="MESSAGE-101018" /> 	<!-- Berat tidak boleh kosong! -->
<input type="hidden" name="MESSAGE-101019" /> 	<!-- Volume tidak boleh kosong! -->

<input type="hidden" name="MESSAGE-190001" /> 	<!-- Pilih data yang akan dipilih -->


<input type="hidden" name="MESSAGE-200001" /> 	<!-- Pilih Qty tidak boleh 0! -->
<input type="hidden" name="MESSAGE-200002" /> 	<!-- Qty ambil melebihi jumlah qty plan koreksi --> 
<input type="hidden" name="MESSAGE-200003" /> 	<!-- Pallet kosong, silahkan tambah pallet di menu penerimaan barang --> 
<input type="hidden" name="MESSAGE-200004" /> 	<!-- Pallet masih ada yang belum tervalidasi, silahkan cek terlebih dahulu! --> 
<input type="hidden" name="MESSAGE-200005" /> 	<!-- Qty ambil melebihi jumlah qty yang ada --> 
<input type="hidden" name="MESSAGE-200006" /> 	<!-- Qty Available dan Qty Plan ada yang tidak compare, Silahkan cek kembali! --> 
<input type="hidden" name="MESSAGE-200007" /> 	<!-- Qty Aktual Koreksi setidaknya minimal 1 yg terisi! --> 
<input type="hidden" name="MESSAGE-200008" /> 	<!-- Qty Plan Koreksi dan Qty Aktual Koreksi ada yang tidak compare, Silahkan cek kembali! --> 
<input type="hidden" name="MESSAGE-200009" /> 	<!-- Qty Plan melebihi Qty Available, Qty Available dalam SKU <strong>'+ parseInt(qty_available) +'</strong>'-->
<input type="hidden" name="MESSAGE-200010" /> 	<!-- Jumlah barang melebihi sisa, sisa jumlah barang dalam SKU <strong>' + parseInt(sisa) + '</strong>' -->
<input type="hidden" name="MESSAGE-200011" /> 	<!-- SKU masih kosong, silahkan tambah sku terlebih dahulu -->
<input type="hidden" name="MESSAGE-200013" /> 	<!-- Silahkan isi Expired Date terlebih dahulu jika ingin pindah isi pallet -->
<input type="hidden" name="MESSAGE-200014" /> 	<!-- Silahkan isi Jumlah barang terlebih dahulu jika ingin pindah isi pallet -->
<input type="hidden" name="MESSAGE-200015" /> 	<!-- Silahkan isi Expired Date terlebih dahulu jika ingin menambah sku -->
<input type="hidden" name="MESSAGE-200016" /> 	<!-- Silahkan isi Tipe terlebih dahulu jika ingin menambah sku -->
<input type="hidden" name="MESSAGE-200017" /> 	<!-- Silahkan isi Jumlah barang terlebih dahulu jika ingin menambah sku -->
<input type="hidden" name="MESSAGE-200018" /> 	<!-- Qty packed tidak boleh kosong, jika kosong / 0 silahkan hapus SKU! -->
<input type="hidden" name="MESSAGE-200019" /> 	<!-- Qty melebihi qty dari remaining! -->

<input type="hidden" name="MESSAGE-300001" /> 	<!-- Kamera tidak ditemukan -->
<input type="hidden" name="MESSAGE-300002" /> 	<!-- Silahkan pilih No. Penerimaan terlebih dahulu sebelum melakukan scan -->
<input type="hidden" name="MESSAGE-300003" /> 	<!-- Silahkan pilih No. Penerimaan terlebih dahulu sebelum melakukan input manual -->
<input type="hidden" name="MESSAGE-300004" /> 	<!-- File Attachment tidak boleh kosong -->
<input type="hidden" name="MESSAGE-300005" /> 	<!-- File Attachment tidak boleh kosong, silahkan upload file terlebih dahulu -->
<input type="hidden" name="MESSAGE-300006" /> 	<!-- File Attachment di server kosong, silahkan upload file terlebih dahulu -->

<input type="hidden" name="MESSAGE-400001" /> 	<!-- Pilih data yang akan dipilih -->
<input type="hidden" name="MESSAGE-400002" /> 	<!-- Data Kosong -->
<input type="hidden" name="MESSAGE-400003" /> 	<!-- Pilih perusahaan terlebih dahulu -->
<input type="hidden" name="MESSAGE-400004" /> 	<!-- Pilih principle terlebih dahulu -->
<input type="hidden" name="MESSAGE-400005" /> 	<!-- Pilih data yang akan dicetak -->