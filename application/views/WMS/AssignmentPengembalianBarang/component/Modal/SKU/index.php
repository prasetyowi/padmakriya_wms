<div class="modal fade" id="modal_list_data_pilih_sku" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog" style="width:90%">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h4 class="modal-title" name="CAPTION-PILIHSKU">Pilih SKU</h4>
			</div>
			<div class="modal-body">
				<!-- filter data -->
				<!-- <?php $this->load->view("WMS/AssignmentPengembalianBarang/component/Modal/SKU/filter_data") ?> -->

				<!-- list data -->
				<?php $this->load->view("WMS/AssignmentPengembalianBarang/component/Modal/SKU/list_data") ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" id="btn_save" onclick="handleGetPilihSKU()"><i class="fas fa-plus"></i> <label name="CAPTION-PILIH">Pilih</label></button>
				<button type="button" class="btn btn-dark" onclick="handleTutupPilihSKU()"><i class="fas fa-xmark"></i> <label name="CAPTION-TUTUP">Tutup</label></button>
			</div>
		</div>
	</div>
</div>