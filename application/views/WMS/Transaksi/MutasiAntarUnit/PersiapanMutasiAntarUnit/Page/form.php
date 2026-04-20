<?php $this->load->view("WMS/Transaksi/MutasiAntarUnit/PersiapanMutasiAntarUnit/Component/Script/Style/index") ?>

<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 class="title-form"></h3>
			</div>
			<div style="float: right">
				<?php if ($this->input->get('mode') !== 'view') { ?>
					<button class="btn-submit btn btn-primary btn-simpan" onclick="handlerSave(event)"><i class="fa fa-save"></i> <span name="CAPTION-SIMPAN" style="color:white;">Simpan</span></button>
				<?php } ?>
				<button class="btn-submit btn btn-dark" onclick="handlerBackToHome()"><i class="fa fa-arrow-left"></i> <span name="CAPTION-KEMBALI" style="color:white;">Kembali</span></button>
			</div>
		</div>

		<div class="clearfix"></div>

		<?php $this->load->view("WMS/Transaksi/MutasiAntarUnit/PersiapanMutasiAntarUnit/Component/Section/header") ?>

		<?php $this->load->view("WMS/Transaksi/MutasiAntarUnit/PersiapanMutasiAntarUnit/Component/Section/detail") ?>

		<!-- load modal -->
		<?php $this->load->view("WMS/Transaksi/MutasiAntarUnit/PersiapanMutasiAntarUnit/Component/Modal/addModalSKU") ?>


	</div>
</div>

<!-- /page content -->