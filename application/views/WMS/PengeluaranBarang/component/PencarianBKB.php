<div class="panel panel-default">
	<div class="panel-heading"><label name="CAPTION-PENCARIANPERMINTAANPENGELUARANBARANG"></label></div>
	<div class="panel-body form-horizontal form-label-left">
		<div class="row">
			<div class="col-lg-6">
				<div class="form-group">
					<label class="col-form-label col-md-2 col-sm-2 label-align" name="CAPTION-NOPPB"></label>
					<div class="col-md-8 col-sm-8">
						<input type="text" class="form-control" name="txtnoppb" id="txtnoppb" value="" required="required" readonly>
						<input type="hidden" class="form-control" name="txtpickinglistid" id="txtpickinglistid" value="">
						<input type="hidden" class="form-control" name="txtnoppbhidden" id="txtnoppbhidden" value="<?php echo $picking_order_kode; ?>" required="required" readonly>
						<input type="hidden" class="form-control" name="txtidppb" id="txtidppb" value="" required="required" readonly>
						<input type="hidden" class="form-control" name="txtjumlahbarang" id="txtjumlahbarang" value="0">
						<input type="hidden" class="form-control" name="lastUpdated" id="lastUpdated" readonly>
						<input type="hidden" class="form-control" name="tipeBKBMix" id="tipeBKBMix" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-form-label col-md-2 col-sm-2 label-align" name="CAPTION-TGLPPB"></label>
					<div class="col-md-8 col-sm-8">
						<input type="date" class="form-control input-date-start date" name="dateppb" id="dateppb" placeholder="dd-mm-yyyy" required="required" readonly>
					</div>
				</div>
				<!-- <div class="form-group">
          <label class="col-form-label col-md-2 col-sm-2 label-align">Gudang</label>
          <div class="col-md-8 col-sm-8">
            <select id="slcgudang" class="form-control" readonly></select>
          </div>
        </div> -->
				<div class="form-group">
					<label class="col-form-label col-md-2 col-sm-2 label-align" name="CAPTION-KETERANGAN"></label>
					<div class="col-md-8 col-sm-8">
						<textarea class="form-control" id="txtketerangan" name="txtketerangan"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-form-label col-md-2 col-sm-2 label-align" name="CAPTION-DRIVER">Driver</label>
					<div class="col-md-8 col-sm-8">
						<input type="text" class="form-control" name="driverppb" id="driverppb" placeholder="Driver" required="required" readonly>
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="form-group">
					<label class="col-form-label col-md-2 col-sm-2 label-align" name="CAPTION-NOPB"></label>
					<div class="col-md-8 col-sm-8">
						<select id="slcnopb" class="form-control" readonly></select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-form-label col-md-2 col-sm-2 label-align" name="CAPTION-NOFDJR"></label>
					<div class="col-md-8 col-sm-8">
						<input type="text" class="form-control" name="txtnofdjr" id="txtnofdjr" required="required" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-form-label col-md-2 col-sm-2 label-align" name="CAPTION-TIPEPB"></label>
					<div class="col-md-8 col-sm-8">
						<select id="slctipepb" class="form-control" readonly></select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-form-label col-md-2 col-sm-2 label-align" name="CAPTION-STATUSPPB"></label>
					<div class="col-md-4 col-sm-4">
						<select id="slcstatusppb" class="form-control" readonly></select>
					</div>
					<div class="col-md-4 col-sm-4">
						<input type="checkbox" id="chkpb" onclick="ConfirmDilaksanakan()"> In Progress
					</div>
				</div>
				<div class="form-group fieldFormChecker">
					<label class="col-form-label col-md-2 col-sm-2 label-align" name="CAPTION-CHECKER"></label>
					<div class="col-md-8 col-sm-8">
						<select id="slccheckerheaderpb" class="form-control" readonly></select>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>