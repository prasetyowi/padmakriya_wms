<style>
	:root {
		--white: #ffffff;
		--light: #f0eff3;
		--black: #000000;
		--dark-blue: #1f2029;
		--dark-light: #353746;
		--red: #da2c4d;
		--yellow: #f8ab37;
		--grey: #ecedf3;
	}

	.modal-body {
		max-height: calc(100vh - 210px);
		overflow-x: auto;
		overflow-y: auto;
	}

	.error {
		border: 1px solid red;
	}

	.alert-header {
		display: flex;
		flex-direction: row;
	}

	.alert-header .alert-icon {
		margin-right: 10px;
	}

	.span-example .alert-header .alert-icon {
		align-self: center;
	}
</style>
<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3>Menu View Pengambilan Barang Mutasi Antar Unit</h3>
			</div>
			<div style="float: right">
				<button type="button" class="btn btn-dark" id="kembali_koreksi_view"><i class="fas fa-arrow-left"></i> <label name="CAPTION-KEMBALI">Kembali</label></button>
			</div>
		</div>

		<div class="clearfix"></div>

		<!-- filter data edit -->
		<?php $this->load->view("WMS/Transaksi/MutasiAntarUnit/PengambilanBarangMutasiAntarUnit/component/form_view_data/filter") ?>

		<!-- list data edit -->
		<?php $this->load->view("WMS/Transaksi/MutasiAntarUnit/PengambilanBarangMutasiAntarUnit/component/form_view_data/list_filter") ?>

		<!-- modal pilih pallet -->
		<?php $this->load->view("WMS/Transaksi/MutasiAntarUnit/PengambilanBarangMutasiAntarUnit/component/modal_pilih_pallet_view/index") ?>

	</div>
</div>
<!-- /page content -->