<script type="text/javascript">	
	var VarianCode = '';
	var arrVoucher = [];

	$(document).ready
	(
		function() 
		{
			GetVarianMenu();
		}
	);
	
	
	function GetVarianMenu()
	{
		$.ajax(
		{
			type: 'POST',    
			url: "<?= base_url('Varian/GetVarianMenu') ?>",
			//data: "Location="+ Location,
			success: function( response )
			{
				if(response)
				{
					ChVarianMenu( response );
				}
			}
		});	
	}
	
	function addCommas(nStr) 
	{
		nStr += '';
		var x = nStr.split('.');
		var x1 = x[0];
		var x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) 
		{
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		
		return x1 + x2;
	}

	function ChVarianMenu( JSONVarian )
	{
		
		$("#tablevarianmenu > tbody").html('');
		
		var Varian = JSON.parse( JSONVarian );
		
		var StatusC = Varian.AuthorityMenu[0].StatusC;
		var StatusU = Varian.AuthorityMenu[0].StatusU;
		var StatusD = Varian.AuthorityMenu[0].StatusD;
		
		if( StatusC == 0 )	{	$("#btnaddnewvarian").attr('style','display: none;');	}
	
		if( Varian.VarianMenu.length > 0 )
		{
			
			if( $.fn.DataTable.isDataTable('#tablevarianmenu') ) 
			{
				$('#tablevarianmenu').DataTable().destroy();
			}
			
			if( $.fn.DataTable.isDataTable('#tablevariandetailmenu') ) 
			{
				$('#tablevariandetailmenu').DataTable().destroy();
			}	
			
			$('#tablevarianmenu tbody').empty();
			$('#tablevariandetailmenu tbody').empty();
			
			for( i=0 ; i<Varian.VarianMenu.length ; i++)
			{
				var varian_id 		= Varian.VarianMenu[i].varian_id;
				var varian_kode		= Varian.VarianMenu[i].varian_kode;
				var varian_nama 	= Varian.VarianMenu[i].varian_nama;
				var varian_is_wajib = Varian.VarianMenu[i].varian_is_wajib;
				
				if( varian_is_wajib == 1 )	{ var Wajib = 'Wajib'; } 
				else						{ var Wajib = 'Opsional'; } 
				var strmenu = '';
			
				var strU = '';
				var strD = '';
				
				<?php
				if($Menu_Access["U"] == 1)
				{
					?>
					strU = '<button style="width: 40px;" class="btn btn-warning btneditvarianmenu form-control" onclick="UpdateVarianMenu(\''+ varian_id +'\',\''+ varian_kode +'\',\''+ varian_nama +'\',\''+ varian_is_wajib +'\')"><i class="fa fa-pencil"></i></button>'; 
					<?php
				}
				if($Menu_Access["D"] == 1)
				{
					?>
					strD = '<button style="width: 40px;" class="btn btn-danger btndeletevarianmenu form-control" onclick="DeleteVarianMenu(\''+ varian_id +'\',\''+ varian_nama +'\')"><i class="fa fa-times"></i></button>'; 
					<?php
				}
				?>
				strmenu = strmenu + '<tr>';
				//strmenu = strmenu + '	<td>'+ KodePos_ID +'</td>';
				strmenu = strmenu + '	<td>'+ varian_kode +'</td>';
				strmenu = strmenu + '	<td>'+ varian_nama +'</td>';
				strmenu = strmenu + '	<td>'+ Wajib +'</td>';
				strmenu = strmenu + '	<td><span>';
				strmenu = strmenu + strU;
				strmenu = strmenu + strD;
				strmenu = strmenu + '		<button style="width: 40px;" class="btn btn-primary btnviewvariandetailmenu form-control" onclick="ViewVarianDetailMenu(\''+ varian_id +'\')"><i class="fa fa-eye"></i></button> ';
				strmenu = strmenu + '	</span></td>';
				strmenu = strmenu + '</tr>';
				
				$("#tablevarianmenu > tbody").append( strmenu );
			}
		}
		
		$("#tablevarianmenu").DataTable(
		{
			retrieve: true,
			"dom": '<"left"f>rtp'
		});
		
		$("#tablevariandetailmenu").DataTable(
		{
			retrieve: true,
			"dom": '<"left"f>rtp'
		});
	}
	
	function ChVarianDetailMenu( JSONVarianDetail )
	{
		$("#tablevariandetailmenu > tbody").html('');
		
		var VarianDetail = JSON.parse( JSONVarianDetail );
		
		if( VarianDetail.VarianDetailMenu.length > 0 )
		{
			for( i=0 ; i<VarianDetail.VarianDetailMenu.length ; i++)
			{
				var varian_detail_id 	= VarianDetail.VarianDetailMenu[i].varian_detail_id;
				var varian_id 			= VarianDetail.VarianDetailMenu[i].varian_id;
				var varian_detail_nama 	= VarianDetail.VarianDetailMenu[i].varian_detail_nama;
				var varian_detail_unit 	= VarianDetail.VarianDetailMenu[i].varian_detail_unit;

				var strmenu = '';
				var strD = '';
				
				<?php
				if($Menu_Access["D"] == 1)
				{
					?>
					strD = '<button style="width: 40px;" class="btn btn-danger btndeletevariandetailmenu form-control" onclick="DeleteVarianDetailMenu(\''+ varian_detail_id +'\', \''+ varian_detail_nama +'\', \''+ varian_detail_unit +'\', \''+ varian_id +'\')"><i class="fa fa-times"></i></button>';
					<?php
				}
				?>
				
				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td width="60%">'+ varian_detail_nama +'</td>';
				strmenu = strmenu + '	<td width="30%">'+ varian_detail_unit +'</td>';
				strmenu = strmenu + '	<td><span>';
				strmenu = strmenu + strD;
				strmenu = strmenu + '	</span></td>';
				strmenu = strmenu + '</tr>';
				
				$("#tablevariandetailmenu > tbody").append( strmenu );
			}
		}
		
		$('#tablevariandetailmenu').DataTable(
		{
			retrieve: true,
			"dom": '<"left"f>rtip'
		});
	}
	
	$("#btnback").click
	(
		function()
		{
			GetVarianMenu();
		}
	);
	

	function ViewVarianDetailMenu( varian_id )
	{
		$.ajax(
		{
			type: 'POST',    
			url: "<?= base_url('Varian/GetVarianDetailMenu') ?>",
			data: {	varian_id : varian_id },
			success: function( response )
			{
				if(response)
				{
					ChVarianDetailMenu( response );
				}
			}
		});	
	}
	
	
	<?php
	if($Menu_Access["D"] == 1)
	{
	?>		
		$("#btnnodeletevarian").click
		(
			function()
			{
				GetVarianMenu();	
			}
		);
	
		// Delete Voucher
		function DeleteVarianMenu( varian_id, varian_nama )
		{
			$("#lbdeletevarian_id").html( varian_nama );
			$("#hddeletevarian_id").val( varian_id );
			
			$("#previewdeletevarian").modal('show');
		}
		
		// Delete Voucher 1
		function DeleteVarianDetailMenu( varian_detail_id, varian_detail_nama, varian_detail_unit, varian_id )
		{
			$("#lbdeletevariandetail_varian_detail_nama").html( varian_detail_nama + ' ' + varian_detail_unit );
			$("#hddeletevariandetail_varian_id").val( varian_id );
			$("#hddeletevariandetail_varian_detail_id").val( varian_detail_id );

			$("#previewdeletevariandetail").modal('show');
		}
	
		$("#btnyesdeletevariandetail").click
		(
			function()
			{
				var varian_detail_id = $("#hddeletevariandetail_varian_detail_id").val();
				var varian_id = $("#hddeletevariandetail_varian_id").val();
				
				$.ajax(
				{
					type: 'POST',    
					url: "<?= base_url('Varian/DeleteVarianDetailMenu') ?>",
					data: {
							varian_detail_id : varian_detail_id, 
							varian_id : varian_id
							},
					success: function( response )
					{
						if(response == 1)
						{
							var msg = 'Detail Varian berhasil dihapus.';
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
						}
						else if( response == 2 )
						{
							var msg = 'Detail Varian sudah dipakai pada Transaksi lain.';
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
						else if( response == 4)
						{
							var msg = 'Tidak bisa menghapus semua Detail Varian.';
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
							var msg = response;
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
						
						GetVarianMenu();	
					}
				});	
			}
		);
		
		$("#btnyesdeletevarian").click
		(
			function()
			{
				var varian_id = $("#hddeletevarian_id").val();
				
				$.ajax(
				{
					type: 'POST',    
					url: "<?= base_url('Varian/DeleteVarianMenu') ?>",
					data: {
							varian_id : varian_id 
							},
					success: function( response )
					{
						if(response == 1)
						{
							var msg = 'Varian berhasil dihapus.';
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
							
							GetVarianMenu();
						}
						else if(response == 2)
						{
							var msg = 'Tidak bisa menghapus Varian karena sudah terpakai di transaksi lain.';
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
						else
						{
							var msg = response;
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

		$("#btnaddupdatevariandetail").click
		(
			function()
			{
				var str = '';
				
				var strvoucher = '';
	
				str = str + '<tr>';
				str = str + '	<td width="60%"><input type="text" class="txtupdatevariandetailvarian_detail_nama form-control" value="" /></td>';
				str = str + '	<td width="30%"><input type="text" class="txtupdatevariandetailvarian_detail_unit form-control" value="" /></td>';
				str = str + '	<td width="10%"><button type="button" class="btn btn-danger btnupdatedeletetd" onclick="DeleteUpdateVarianDetail( this.parentNode.parentNode.rowIndex )"><i class="fa fa-times"></i></button></td>';
				str = str + '</tr>';
				
				$("#tableupdatevariandetail").append( str );
			}
		);
		
		$("#btnupdateback").click
		(
			function()
			{
				GetVarianMenu();	
			}
		);
			
		function DeleteUpdateTempVarianDetail( Idx )
		{
			var table = document.getElementById('tableupdatevariandetail');
			
			table.deleteRow( Idx );
		}
	
		function UpdateVarianMenu( varian_id, varian_kode, varian_nama, varian_is_wajib )
		{
			//VarianCode = VarianKode;
			
			
			$.ajax(
			{
				type: 'POST',    
				url: "<?= base_url('Varian/GetVarianDetailMenu') ?>",
				data: 
				{	
					varian_id : varian_id 
				},
				success: function( response )
				{
					if(response)
					{
						ChUpdateVarianDetailMenu( response, varian_id, varian_kode, varian_nama, varian_is_wajib );
					}
				}
			});	
		}

		function ChUpdateVarianDetailMenu( JSONVarianDetail, P_varian_id, varian_kode, varian_nama, varian_is_wajib )
		{
			$("#tableupdatevariandetail").html('');
			
			var VarianDetail = JSON.parse( JSONVarianDetail );
	
			var varian_id 		= VarianDetail.VarianMenu[0].varian_id;
			var	varian_kode 	= VarianDetail.VarianMenu[0].varian_kode;
			var varian_nama 	= VarianDetail.VarianMenu[0].varian_nama;
			var varian_is_wajib = VarianDetail.VarianMenu[0].varian_is_wajib; 
			
			$("#txtupdatevarian_id").val( varian_id );
			$("#txtupdatevarian_kode").val( varian_kode );
			$("#txtupdatevarian_nama").val( varian_nama );
			
			if( varian_is_wajib == 0 ) 	{	$("#chupdatevarian_is_wajib").prop('checked',false ); 	}
			else						{	$("#chupdatevarian_is_wajib").prop('checked',true ); 	}
			
			
			for( i=0 ; i<VarianDetail.VarianDetailMenu.length ; i++ )
			{
				var varian_detail_id 	= VarianDetail.VarianDetailMenu[i].varian_detail_id;
				var varian_detail_nama 	= VarianDetail.VarianDetailMenu[i].varian_detail_nama;
				var varian_detail_unit 	= VarianDetail.VarianDetailMenu[i].varian_detail_unit;
				var varian_id 			= VarianDetail.VarianDetailMenu[i].varian_id;
				
				var strmenu = '';
				
				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td width="60%" align="left"><input type="text" class="txtupdatevariandetailvarian_detail_nama form-control" value="'+ varian_detail_nama +'" disabled="disabled" /></td>';
				strmenu = strmenu + '	<td width="30%" align="left"><input type="text" class="txtupdatevariandetailvarian_detail_unit form-control" value="'+ varian_detail_unit +'" disabled="disabled" /></td>';
				strmenu = strmenu + '	<td width="10%" align="left"></td>';
				strmenu = strmenu + '</tr>';
				
				$("#tableupdatevariandetail").append( strmenu );
			}
			
			$("#previewupdatevarian").modal('show');	
		}
		
		$("#btnsaveupdatevarian").click
		(
			function()
			{
				var varian_id 		= $("#txtupdatevarian_id").val();
				var varian_kode		= $("#txtupdatevarian_kode").val();
				var varian_nama 	= $("#txtupdatevarian_nama").val();
				var varian_is_wajib = $("#chupdatevarian_is_wajib").prop('checked');
				
				if( varian_is_wajib == false )	{ 	varian_is_wajib = 0; }
				else					{ 	varian_is_wajib = 1; }
				
				var lVarianDetail = $(".txtupdatevariandetailvarian_detail_nama").length;
				
				if( lVarianDetail == 0)
				{
					var msg = 'Tidak ada Detail Varian yang dipilih.';
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
				else
				{
					var numerror = 0;
					
					for( i=0 ; i<lVarianDetail ; i++ )
					{
						var varian_detail_nama 	= $(".txtupdatevariandetailvarian_detail_nama").eq(i).val();
						
						if( varian_detail_nama == '' )
						{
							numerror = 1;
						}
							
					}
					
					if( numerror == 1 )
					{
						var msg = 'Harap mengisi semua Detail Varian.';
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
					else if( numerror == 0 )
					{
						var arrVarianDetail = [];
						
						for( i=0 ; i<lVarianDetail ; i++ )
						{
							var IsExists = $(".txtupdatevariandetailvarian_detail_nama").eq(i).attr('disabled');
							
							if( IsExists != 'disabled' )
							{
								var varian_detail_nama 	= $(".txtupdatevariandetailvarian_detail_nama").eq(i).val();
								var varian_detail_unit 		= $(".txtupdatevariandetailvarian_detail_unit").eq(i).val();
								
								arrVarianDetail.push( { varian_detail_nama : varian_detail_nama, varian_detail_unit : varian_detail_unit } );
							}
						}
						
						$.ajax(
						{
							type: 'POST',    
							url: "<?= base_url('Varian/SaveUpdateVarian') ?>",
							data: {	
									varian_id 		: varian_id,
									varian_kode 	: varian_kode,
									varian_nama 	: varian_nama,
									varian_is_wajib : varian_is_wajib,

									arrVarianDetail : arrVarianDetail
									},
							success: function( response )
							{
								if(response == 1)
								{
									var msg = 'Varian berhasil diubah.';
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
									
									$("#previewupdatevarian").modal('hide');
									
									GetVarianMenu();
								}
								/*
								else if(response == 4) // alphanumeric
								{
									var msg = 'Only Numbers or Letters are Allowed for Varian Name.';
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
								*/
								else
								{
									var msg = response;
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
							}
						});
					}
				}
			}
		);
	<?php
	}
	?>
	
	<?php
	if($Menu_Access["C"] == 1)
	{
	?>			
		$("#btnaddnewvarian").removeAttr('style');
		/*
		$("#txtvariannama").change
		(
			function()
			{
				var str = $("#txtupdatevarianname").val();
				
				var letterNumber = /^[0-9a-zA-Z ]+$/;
				
				if(	!str.match(letterNumber) )
				{
					$("#txtupdatevarianname").val('');
					
					var msg = 'Only Numbers or Letters are Allowed for Varian Name.';
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
			}
		);
		*/
		
		$("#btnaddvariandetail").click
		(
			function()
			{
				var str = '';

				str = str + '<tr>';
				str = str + '	<td width="60%"><input type="text" class="txtvariandetailvarian_detail_nama form-control" /></td>';
				str = str + '	<td width="30%"><input type="text" class="txtvariandetailvarian_detail_unit form-control" /></td>';
				str = str + '	<td width="10%"><button type="button" class="btn btn-danger btndeletetd" onclick="DeleteTempVarianDetail( this.parentNode.parentNode.rowIndex )"><i class="fa fa-times"></i></button></td>';
				str = str + '</tr>';
				
				$("#tableaddvariandetail").append( str );
			}
		);

		function DeleteTempVarianDetail( Idx )
		{
			var table = document.getElementById('tableaddvariandetail');
			
			table.deleteRow( Idx );
		}
		
		// Add New Voucher
		/*
		$("#txtvarian").change
		(
			function()
			{
				var str = $("#txtvarian").val();
				
				var letterNumber = /^[0-9a-zA-Z ]+$/;
				if(	!str.match(letterNumber) )
				{
					$("#txtvarianname").val('');
					
					var msg = 'Only Numbers or Letters are Allowed for Postal Code.';
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
			}
		);
		*/
		
		$("#btnaddnewvarian").click
		(
			function()
			{
				ResetForm();
				$("#previewaddnewvarian").modal('show');
			}
		);
		
		$("#btnsaveaddnewvarian").click
		(
			function()
			{					
				//var KodePos 	= $("#txtvarian").val();
				var varian_kode 	= $("#txtvarian_kode").val();
				var varian_nama 	= $("#txtvarian_nama").val();
				var varian_is_wajib = $("#chvarian_is_wajib").prop('checked');

				if( varian_is_wajib == false )	{	varian_is_wajib = 0; }
				else							{	varian_is_wajib = 1; }
				
				var lVarianDetail = $(".txtvariandetailvarian_detail_nama").length;
		
				if( lVarianDetail == 0)
				{
					var msg = 'Harap mengisi semua Detail Varian.';
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
				else
				{
					var numerror = 0;
					
					for( i=0 ; i<lVarianDetail ; i++ )
					{
						var varian_detail_nama	= $(".txtvariandetailvarian_detail_nama").eq(i).val();
						
						if( varian_detail_nama == '')
						{
							numerror = 1;
						}
							
					}
					
					if( numerror == 1 )
					{
						var msg = 'Harap mengisi semua Detail Varian.';
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
					else if( numerror == 0 )
					{
						var arrVarianDetail = [];
						
						for( i=0 ; i<lVarianDetail ; i++ )
						{
							var varian_detail_nama 	= $(".txtvariandetailvarian_detail_nama").eq(i).val();
							var varian_detail_unit	= $(".txtvariandetailvarian_detail_unit").eq(i).val();
							
							arrVarianDetail.push( { varian_detail_nama : varian_detail_nama, varian_detail_unit : varian_detail_unit } );
						}
					
						$.ajax(
						{
							type: 'POST',    
							url: "<?= base_url('Varian/SaveAddNewVarian') ?>",
							data: {	
									varian_kode 	: varian_kode,
									varian_nama 	: varian_nama,
									varian_is_wajib	: varian_is_wajib,
				
									arrVarianDetail : arrVarianDetail
									},
							success: function( response )
							{
								if(response == 1)
								{
									var msg = 'Varian berhasil ditambah.';
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
									
									$("#previewaddnewvarian").modal('hide');
									
									GetVarianMenu();
									
									ResetForm();
								}
								else if(response == 2)
								{
									var msg = 'Kode Varian sudah ada.';
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
								else
								{
									var msg = response;
									var msgtype = 'error';
										
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
								}
							}
						});
					}
				}
			}
		);
		
	<?php
	}
	?>
	
	function ResetForm()
	{
		//$("#txtvarian").val('');
		$("#txtvarian_kode").val('');
		$("#txtvarian_nama").val('');
		$("#chvarian_is_wajib").prop('checked',false);

		$("#tableaddvariandetail").html('');
		$("#tablevariandetailmenu > tbody").html('');
		
		//$("#txtupdatevarian").val('');
		$("#txtvarian_kode").val('');
		$("#txtupdatevarian_nama").val('');
		$("#chupdatevarian_is_wajib").val('');
		
		$("#tableupdateaddvariandetail").html('');
		$("#tableupdatevariandetailmenu > tbody").html('');
	}
</script>