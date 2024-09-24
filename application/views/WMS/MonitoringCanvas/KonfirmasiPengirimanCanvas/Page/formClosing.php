<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 class="title-form"></h3>
			</div>
			<div style="float: right">
				<?php if ($data->header->is_confirm_canvas == 1) { ?>
					<button class="btn-submit btn btn-success btn-konfirmasi" onclick="handlerConfirmation()"><i class="fa fa-check"></i> <span name="CAPTION-KONFORMASI" style="color:white;">Konfirmasi</span></button>
				<?php } ?>
				<?php if ($data->header->is_confirm_canvas == null || $data->header->is_confirm_canvas == 1) { ?>
					<button class="btn-submit btn btn-primary btn-simpan" onclick="handlerSave()"><i class="fa fa-save"></i> <span name="CAPTION-SIMPAN" style="color:white;">Simpan</span></button>
				<?php } ?>
				<button class="btn-submit btn btn-dark" onclick="handlerBackToHome()"><i class="fa fa-arrow-left"></i> <span name="CAPTION-KEMBALI" style="color:white;">Kembali</span></button>
			</div>
		</div>

		<div class="clearfix"></div>

		<?php $this->load->view("WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/Component/Section/Closing/header") ?>

		<?php $this->load->view("WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/Component/Section/Closing/detail") ?>

		<!-- load modal -->
		<?php $this->load->view("WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/Component/Modal/Closing/addModalSO") ?>

		<?php $this->load->view("WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/Component/Modal/Closing/viewDetailDO") ?>
	</div>
</div>

<!-- /page content -->