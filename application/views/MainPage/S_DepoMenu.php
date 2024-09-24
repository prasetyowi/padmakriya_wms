<script type="text/javascript">
	$(document).ready
	(
		function()
		{		
			$.ajax(
			{
				type: 'POST',
				url: "<?= base_url('Main/MainDepo/GetDepoMenu') ?>",
				success: function( response )
				{	
					ChDepoMenu( response );
				}
			});
		}
	);
	
	function ChDepoMenu( JSONData )
	{
		var Data = JSON.parse( JSONData );
		
		if( Data.Depo != 0 )
		{
			var map = L.map('datamapoutlet').setView([-7.2464112, 112.7797339], 13);
			L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
				maxZoom: 19
			}).addTo(map);
		
			
			for( i=0 ; i<Data.Depo.length ; i++ )
			{
				var depo_id 			= Data.Depo[i].depo_id;
				var depo_kode 			= Data.Depo[i].depo_kode;
				var depo_nama 			= Data.Depo[i].depo_nama;
				var depo_latitude 		= Data.Depo[i].depo_latitude;
				var depo_longitude 		= Data.Depo[i].depo_longitude;
				var depo_nama 			= Data.Depo[i].depo_nama;
				var depo_alamat 		= Data.Depo[i].depo_alamat;
				var isaccessible 		= Data.Depo[i].isaccessible;
				var unit_mandiri_id 	= Data.Depo[i].unit_mandiri_id;
				
				var click = '';
				
				if( isaccessible == 1 )
				{
					var click = 'onclick="GoToMainMenu(\''+ depo_id +'\',\''+ depo_kode +'\',\''+ depo_nama +'\',\''+ unit_mandiri_id +'\')"';
				}
					
				var str = '';
				
				str += '<div class="outletrows col-xs-6 col-sm-6 col-md-4 col-lg-4 col-xl-4" '+ click +'">';
				str += '	<img src="<?= Get_Assets_Url(); ?>assets/images/Unit/'+ depo_nama +'.jpg" class="img-thumbnail" style="width: 100%; height: 200px;">';

				str += '	<div class="topleft">'+ depo_nama +'</div>';
				str += '</div>';
					
				$("#dataoutlet").append( str );
				
				// map
					/*
				var icon = L.divIcon({
					iconUrl: "<?= Get_Assets_Url(); ?>vendors/leaflet/images/marker-icon.png",
					shadowUrl: "<?= Get_Assets_Url(); ?>vendors/leaflet/images/marker-icon-shadow.png",

					iconSize:     [25, 40], // size of the icon
					shadowSize:   [25, 40], // size of the shadow
				});
				*/
				if( isaccessible == 1 )	{	var img = "<?= Get_Assets_Url(); ?>vendors/leaflet/images/marker-icon.png";	}
				else					{	var img = "<?= Get_Assets_Url(); ?>vendors/leaflet/images/marker-icon-unavailable.png";	}
				
				var str = '';
				
				var icon = L.divIcon({
					className: 'text-labels-icon',   // Set class for CSS styling
					html: '<img src="'+ img +'" style="width: 25px; height: 40px;">'+ depo_nama
				});
				
				
				str += '<img src="<?= Get_Assets_Url(); ?>assets/images/Unit/'+ depo_nama +'.jpg" style="width: 180px; height: 120px;" '+ click +'>';
				str += '<h4 style="width: 180px; height: 20px;">'+ depo_nama +'</h4>';
				str += '<h5 style="width: 180px; height: 60px;">'+ depo_alamat +'</h5>';

				
				if( depo_latitude != 'null' && depo_latitude != null && depo_longitude != 'null' && depo_longitude != null )
				{
					var marker = L.marker([depo_latitude, depo_longitude], {icon: icon}).addTo(map);
						marker.bindPopup( str );
					
				}
			}
		}
		
	}
	
	function GoToMainMenu( depo_id, depo_kode, depo_nama, unit_mandiri_id )
	{
		var str = '';
		
		str += 	'<form id="submitform" method="POST" action="<?= base_url('Main/MainDepo/VerifyDepo') ?>">';
		//str += 	'	<input type="hidden" id="IsDefaultPrinterChecker" name="IsDefaultPrinterChecker" value="'+ checker +'" />';
		//str += 	'	<input type="hidden" id="IsDefaultPrinterCashier" name="IsDefaultPrinterCashier" value="'+ cashier +'" />';
		//str += 	'	<input type="hidden" id="hdPrinterID_Checker" name="hdPrinterID_Checker" value="'+ printerchecker +'" />';
		//str += 	'	<input type="hidden" id="hdPrinterID_Cashier" name="hdPrinterID_Cashier" value="'+ printercashier +'" />';
		
		str += 	'	<input type="hidden" id="depo_id" name="depo_id" value="'+ depo_id +'" />';
		str += 	'	<input type="hidden" id="depo_kode" name="depo_kode" value="'+ depo_kode +'" />';
		str += 	'	<input type="hidden" id="depo_nama" name="depo_nama" value="'+ depo_nama +'" />';
		str += 	'	<input type="hidden" id="unit_mandiri_id" name="unit_mandiri_id" value="'+ unit_mandiri_id +'" />';
		
		str += 	'	<input type="hidden" id="hdKey" name="hdKey" value="HOPE" />';
		
		str += 	'	<input type="submit" id="btnsubmit" name="btnsubmit" />';
		str += 	'</form>';
		
		$("#divsubmit").append( str );
		
		$("#btnsubmit").click
		(
			function()
			{
				$("#submitform").submit();
			}
		);
		
		$("#btnsubmit").trigger('click');
	}
	
	
	$("#btnslider").click
	(
		function()
		{
			$("#btnslider").removeClass('btn-success');
			$("#btntable").removeClass('btn-success');
			$("#btnslider").removeClass('btn-default');
			$("#btntable").removeClass('btn-default');
			
			$("#btnslider").addClass('btn-success');
			$("#btntable").addClass('btn-default');
			
			$("#divSliders").css('display','');
			$("#divTable").css('display','none');
		}
	);
	
	$("#btntable").click
	(
		function()
		{
			$("#btnslider").removeClass('btn-success');
			$("#btntable").removeClass('btn-success');
			$("#btnslider").removeClass('btn-default');
			$("#btntable").removeClass('btn-default');
			
			$("#btnslider").addClass('btn-default');
			$("#btntable").addClass('btn-success');
			
			$("#divSliders").css('display','none');
			$("#divTable").css('display','');
		}
	);
	
</script>

</div>

</body>
</html>