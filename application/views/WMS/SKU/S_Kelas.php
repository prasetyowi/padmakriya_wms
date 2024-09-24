<script type="text/javascript">	
	var KelasCode = '';

	$(document).ready
	(
		function() 
		{
			GetKelasMenu();
		}
	);
	
	
	function GetKelasMenu()
	{
		$(".colorpicker-alpha").attr('style','display:none !important;')
		$(".colorpicker").attr('style','min-width:128px !important;');
		
		$.ajax(
		{
			type: 'POST',    
			url: "<?= base_url('Kelas/GetKelasMenu') ?>",
			//data: "Location="+ Location,
			success: function( response )
			{
				if(response)
				{
					ChKelasMenu( response );
				}
			}
		});	
	}
		
	//var DTABLE;
	
	function ChKelasMenu( JSONKelas )
	{
		$("#tablekelasmenu > tbody").html('');
		
		var Kelas = JSON.parse( JSONKelas );
		
		var StatusC = Kelas.AuthorityMenu[0].StatusC;
		var StatusU = Kelas.AuthorityMenu[0].StatusU;
		var StatusD = Kelas.AuthorityMenu[0].StatusD;
		
		if( StatusC == 0 )	{	$("#btnaddnewkelas").attr('style','display: none;');	}
		
		if( Kelas.KelasMenu != 0 )
		{
						
			if( $.fn.DataTable.isDataTable('#tablekelasmenu') ) 
			{
				$('#tablekelasmenu').DataTable().destroy();
			}

			$('#tablekelasmenu tbody').empty();
			
			for( i=0 ; i<Kelas.KelasMenu.length ; i++)
			{
				var kelas_id 		= Kelas.KelasMenu[i].kelas_id;
				var kelas_kode 		= Kelas.KelasMenu[i].kelas_kode;
				var kelas_nama 		= Kelas.KelasMenu[i].kelas_nama;
				var kelas_jenis		= Kelas.KelasMenu[i].kelas_jenis;
				var kelas_warna		= Kelas.KelasMenu[i].kelas_warna;
				 
				if( kelas_jenis == 'F')		{	var jenis = 'Makanan';	}
				else if( kelas_jenis == 'B'){	var jenis = 'Minuman';	}
				else if( kelas_jenis == 'O'){	var jenis = 'Lain-Lain';	}
				
				var rgb = hexToRGB( kelas_warna );
				
				var st = '';
				
				if( rgb[0].r + rgb[0].g + rgb[0].b < 500 )
				{
					st = 'color: white;';
				}
				
				
				var strmenu = '';
				
				var strU = '';
				var strD = '';
				
				<?php
				if($Menu_Access["U"] == 1)
				{
					?>
					strU = '<button style="width: 40px;" class="btn btn-warning btneditukelasmenu form-control" onclick="UpdateKelasMenu(\''+ kelas_id +'\',\''+ kelas_kode +'\',\''+ kelas_nama +'\',\''+ kelas_jenis +'\',\''+ kelas_warna +'\')"><i class="fa fa-pencil"></i></button>';
					<?php
				}
				if($Menu_Access["D"] == 1)
				{
					?>
					strD = '<button style="width: 40px;" class="btn btn-danger btndeletekelasmenu form-control" onclick="DeleteKelasMenu(\''+ kelas_id +'\',\''+ kelas_nama +'\')"><i class="fa fa-times"></i></button>';
					<?php
				}
				?>
				
				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td>'+ kelas_kode +'</td>';
				strmenu = strmenu + '	<td>'+ kelas_nama +'</td>';
				strmenu = strmenu + '	<td>'+ jenis +'</td>';
				strmenu = strmenu + '	<td><div style="min-width: 50px; min-height: 30px; background-color: '+ kelas_warna +'; '+ st +'" align="center"><strong>'+ kelas_warna +'</center></div></td>';
				strmenu = strmenu + '	<td><span>';
				strmenu = strmenu + strU; 
				strmenu = strmenu + strD; 
				strmenu = strmenu + '	</span></td>';
				strmenu = strmenu + '</tr>';
				
				$("#tablekelasmenu > tbody").append( strmenu );
			}
		}

		$('#tablekelasmenu').DataTable(
		{
			retrieve: true,
			"dom": '<"left"f>rtp'
		});
	}
	
	$("#btnback").click
	(
		function()
		{
			GetKelasMenu();
		}
	);

	<?php
	if($Menu_Access["D"] == 1)
	{
	?> 
		$("#btnnodeletekelas").click
		(
			function()
			{
				GetKelasMenu();	
			}
		);
	
		// Delete Kelas
		function DeleteKelasMenu( kelas_id, kelas_nama )
		{
			$("#lbdeletekelas_nama").html( kelas_nama );
			$("#hddeletekelas_id").val( kelas_id );
			
			$("#previewdeletekelas").modal('show');
		}
		
		$("#btnyesdeletekelas").click
		(
			function()
			{
				$("#btnyesdeletekelas").prop('disabled', true );
				
				var kelas_id = $("#hddeletekelas_id").val();
				
				$.ajax(
				{
					type: 'POST',    
					url: "<?= base_url('Kelas/DeleteKelasMenu') ?>",
					data: {
							kelas_id : kelas_id 
							},
					success: function( response )
					{
						
						if(response == 1)
						{
							var msg = 'Kelas berhasil dihapus.';
							var msgtype = 'success';
								
							//if (!window.__cfRLUnblockHandlers) return false;
							new PNotify
							({
								title: 'Info',
								text: msg,
								type: msgtype,
								styling: 'bootstrap3',
								delay: 3000,
								stack: stack_center
							});
							
							$("#previewaddnewkelas").modal('hide');
							
							GetKelasMenu();
						}
						else if( response == 2 )
						{
							var msg = 'Kelas sudah dipakai di transaksi lain.';	
							var msgtype = 'error';
								
							//if (!window.__cfRLUnblockHandlers) return false;
							new PNotify
							({
								title: 'error',
								text: msg,
								type: msgtype,
								styling: 'bootstrap3',
								delay: 3000,
								stack: stack_center
							});
						}
						else
						{
							var ErrMsg = response.split('$$$');
								ErrMsg = ErrMsg[1];
								
							var msg = ErrMsg;
							var msgtype = 'error';
								
							//if (!window.__cfRLUnblockHandlers) return false;
							new PNotify
							({
								title: 'error',
								text: msg,
								type: msgtype,
								styling: 'bootstrap3',
								delay: 3000,
								stack: stack_center
							});
						}
						
						$("#btnyesdeletekelas").prop('disabled', false );
						
						GetKelasMenu();
					}
				});	
			}
		);
	<?php
	}
	?>
	
	<?php
	if($Menu_Access["U"] == 1)
	{
	?>
		$("#btnupdateback").click
		(
			function()
			{
				GetKelasMenu();	
			}
		);
	
		function UpdateKelasMenu( kelas_id, kelas_kode, kelas_nama, kelas_jenis, kelas_warna )
		{
			$("#updatehdkelas_id").val( kelas_id );
			$("#updatetxtkelas_kode").val( kelas_kode );
			$("#updatetxtkelas_nama").val( kelas_nama );
			$("#updatecbkelas_jenis").val( kelas_jenis );
			$("#updatetxtkelas_warna").val( kelas_warna );
			
			$("#previewupdatekelas").modal('show');
		}

		$("#btnsaveupdatenewkelas").click
		(
			function()
			{
				$("#btnsaveupdatenewkelas").prop('disabled', true );
				
				var kelas_id 	= $("#updatehdkelas_id").val();
				var kelas_kode 	= $("#updatetxtkelas_kode").val();
				var kelas_nama	= $("#updatetxtkelas_nama").val();
				var kelas_jenis	= $("#updatecbkelas_jenis option:selected").val();
				var kelas_warna	= $("#updatetxtkelas_warna").val();
				
				$.ajax(
				{
					type: 'POST',    
					url: "<?= base_url('Kelas/SaveUpdateKelas') ?>",
					data: {	
							kelas_id : kelas_id,
							kelas_kode : kelas_kode,
							kelas_nama : kelas_nama,
							kelas_jenis : kelas_jenis,
							kelas_warna : kelas_warna
							},
					success: function( response )
					{
						if(response == 1)
						{
							var msg = 'Kelas berhasil diubah.';
							var msgtype = 'success';
								
							//if (!window.__cfRLUnblockHandlers) return false;
							new PNotify
							({
								title: 'Info',
								text: msg,
								type: msgtype,
								styling: 'bootstrap3',
								delay: 3000,
								stack: stack_center
							});
							
							ResetForm();
							
							$("#previewupdatekelas").modal('hide');
							
							GetKelasMenu();
						}
						else
						{
							if( response == 2 ) 	{	var msg = 'Kode Kelas sudah ada.'; }
							else if( response == 3 ){	var msg = 'Nama Kelas sudah ada.'; }
							else if( response == 4 ){	var msg = 'Warna sudah ada.'; }
							else					{	var msg = response; }
							var msgtype = 'error';
								
							//if (!window.__cfRLUnblockHandlers) return false;
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
						$("#btnsaveupdatenewkelas").prop('disabled', false );
				
					}
				});
			}
		);
	<?php
	}
	?>
	
	<?php
	if($Menu_Access["C"] == 1)
	{
	?>
		$("#btnaddnewkelas").removeAttr('style');
		
		// Add New Kelas
		$("#btnaddnewkelas").click
		(
			function()
			{
				ResetForm();
				$("#previewaddnewkelas").modal('show');
			}
		);
		
		$("#btnsaveaddnewkelas").click
		(
			function()
			{	
				$("#btnsaveaddnewkelas").prop('disabled', true );
				
				var kelas_kode 	= $("#txtkelas_kode").val();
				var kelas_nama 	= $("#txtkelas_nama").val();
				var kelas_jenis = $("#cbkelas_jenis option:selected").val();
				var kelas_warna = $("#txtkelas_warna").val();

				$.ajax(
				{
					type: 'POST',    
					url: "<?= base_url('Kelas/SaveAddNewKelas') ?>",
					data: {	
							kelas_kode : kelas_kode,
							kelas_nama : kelas_nama,
							kelas_jenis : kelas_jenis,
							kelas_warna : kelas_warna
							},
					success: function( response )
					{
						if(response == 1)
						{
							var msg = 'Kelas berhasil ditambah.';
							var msgtype = 'success';
								
							//if (!window.__cfRLUnblockHandlers) return false;
							new PNotify
							({
								title: 'Info',
								text: msg,
								type: msgtype,
								styling: 'bootstrap3',
								delay: 3000,
								stack: stack_center
							});
							
							GetKelasMenu();
				
							$("#previewaddnewkelas").modal('hide');
							ResetForm();
						}
						else
						{
							if( response == 2 ) 	{	var msg = 'Kode Kelas sudah ada.'; }
							else if( response == 3 ){	var msg = 'Nama Kelas sudah ada.'; }
							else if( response == 4 ){	var msg = 'Warna sudah ada.'; }
							else					{	var msg = response; }
							var msgtype = 'error';
								
							//if (!window.__cfRLUnblockHandlers) return false;
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
						$("#btnsaveaddnewkelas").prop('disabled', false );
				
					}
				});
			}
		);
	<?php
	}
	?>
	
	
	function ResetForm()
	{
		<?php
		if($Menu_Access["U"] == 1)
		{
		?>
			$("#updatehdkelas_id").val('');
			$("#updatetxtkelas_kode").val('');
			$("#updatetxtkelas_nama").val('');
			$("#updatecbkelas_jenis").val('F');
			$("#updatetxtkelas_warna").val('');
		<?php
		}
		?>
		
		<?php
		if($Menu_Access["C"] == 1)
		{
		?>
			$("#txtkelas_kode").val('');
			$("#txtkelas_nama").val('');
			$("#cbkelas_jenis").val('F');
			$("#txtkelas_warna").val('');
		<?php
		}
		?>
		
	}
</script>