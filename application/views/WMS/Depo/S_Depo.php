<script type="text/javascript">	
	var DepoCode = '';
	var arrClient = [];
	
	$(document).ready
	(
		function() 
		{
			GetDepoMenu();
		}
	);
	
	
	function GetDepoMenu()
	{
		$.ajax(
		{
			type: 'POST',    
			url: "<?= base_url('Depo/GetDepoMenu') ?>",
			//data: "Location="+ Location,
			success: function( response )
			{
				if(response)
				{
					ChDepoMenu( response );
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

	function ChDepoMenu( JSONDepo )
	{
		
		$("#tabledepomenu > tbody").html('');
		
		var Depo = JSON.parse( JSONDepo );
		
		var StatusC = Depo.AuthorityMenu[0].StatusC;
		var StatusU = Depo.AuthorityMenu[0].StatusU;
		var StatusD = Depo.AuthorityMenu[0].StatusD;
		
		if( StatusC == 0 )	{	$("#btnaddnewdepo").attr('style','display: none;');	}
	
		if( Depo.DepoMenu != 0 )
		{
			
			if( $.fn.DataTable.isDataTable('#tabledepomenu') ) 
			{
				$('#tabledepomenu').DataTable().destroy();
			}
			
			if( $.fn.DataTable.isDataTable('#tabledepodetailmenu') ) 
			{
				$('#tabledepodetailmenu').DataTable().destroy();
			}	
			
			$('#tabledepomenu tbody').empty();
			$('#tabledepodetailmenu tbody').empty();
			
			for( i=0 ; i<Depo.DepoMenu.length ; i++)
			{
				var depo_id 			= Depo.DepoMenu[i].depo_id;
				var depo_kode 			= Depo.DepoMenu[i].depo_kode;
				var depo_nama 			= Depo.DepoMenu[i].depo_nama;
				var depo_update_who 	= Depo.DepoMenu[i].depo_update_who;
				var depo_update_tanggal = Depo.DepoMenu[i].depo_update_tanggal;
				var depo_status 		= Depo.DepoMenu[i].depo_status;
				var depo_alamat 		= Depo.DepoMenu[i].depo_alamat;
				var depo_latitude 		= Depo.DepoMenu[i].depo_latitude;
				var depo_longitude 		= Depo.DepoMenu[i].depo_longitude;
				
				if( depo_status == 1 )	{ 	var Status = 'Aktif'; } 
				else					{ 	var Status = 'Tidak Aktif'; } 
				
				var strmenu = '';
			
				var strU = '';
				var strD = '';
				
				<?php
				if($Menu_Access["U"] == 1)
				{
					?>
					strU = '<button style="width: 40px;" class="btn btn-warning btneditdepomenu form-control" onclick="UpdateDepoMenu(\''+ depo_id +'\',\''+ depo_kode +'\',\''+ depo_nama +'\',\''+ depo_status +'\',\''+ depo_alamat +'\',\''+ depo_latitude +'\',\''+ depo_longitude +'\')"><i class="fa fa-pencil"></i></button>'; 
					<?php
				}
				if($Menu_Access["D"] == 1)
				{
					?>
					strD = '<button style="width: 40px;" class="btn btn-danger btndeletedepomenu form-control" onclick="DeleteDepoMenu(\''+ depo_id +'\',\''+ depo_nama +'\')"><i class="fa fa-times"></i></button>'; 
					<?php
				}
				?>
				strmenu = strmenu + '<tr>';
				//strmenu = strmenu + '	<td>'+ KodePos_ID +'</td>';
				strmenu = strmenu + '	<td>'+ depo_kode +'</td>';
				strmenu = strmenu + '	<td>'+ depo_nama +'</td>';
				strmenu = strmenu + '	<td>'+ depo_alamat +'</td>';
				strmenu = strmenu + '	<td>'+ depo_latitude +' , ' + depo_longitude +'</td>';
				strmenu = strmenu + '	<td>'+ Status +'</td>';
				strmenu = strmenu + '	<td><span>';
				strmenu = strmenu + strU;
				strmenu = strmenu + strD;
				strmenu = strmenu + '		<button style="width: 40px;" class="btn btn-primary btnviewdepo_detailmenu form-control" onclick="ViewDepoDetailMenu(\''+ depo_id +'\')"><i class="fa fa-eye"></i></button> ';
				strmenu = strmenu + '	</span></td>';
				strmenu = strmenu + '</tr>';
				
				$("#tabledepomenu > tbody").append( strmenu );
			}
		}
		
		arrClient = [];
		
		if( Depo.ClientMenu != 0 )
		{
			for( i=0 ; i< Depo.ClientMenu.length ; i++ )
			{
				var client_wms_id	= Depo.ClientMenu[i].client_wms_id;
				var client_wms_nama = Depo.ClientMenu[i].client_wms_nama;
			
				arrClient.push( { client_wms_id: client_wms_id, client_wms_nama: client_wms_nama } );
			}
		}
		
		$("#tabledepomenu").DataTable(
		{
			retrieve: true,
			"dom": '<"left"f>rtp'
		});
		
		$("#tabledepodetailmenu").DataTable(
		{
			retrieve: true,
			"dom": '<"left"f>rtp'
		});
	}
	
	function ChDepoDetailMenu( JSONDepoDetail )
	{
		$("#tabledepodetailmenu > tbody").html('');
		
		var DepoDetail = JSON.parse( JSONDepoDetail );
		
		if( DepoDetail.DepoDetailMenu.length > 0 )
		{
			for( i=0 ; i<DepoDetail.DepoDetailMenu.length ; i++)
			{
				var depo_id 						= DepoDetail.DepoDetailMenu[i].depo_id;
				var depo_detail_id 					= DepoDetail.DepoDetailMenu[i].depo_detail_id;
				var depo_detail_nama 				= DepoDetail.DepoDetailMenu[i].depo_detail_nama;
				var depo_detail_catatan 			= DepoDetail.DepoDetailMenu[i].depo_detail_catatan;
				var depo_detail_flag_jual 			= DepoDetail.DepoDetailMenu[i].depo_detail_flag_jual;
				var depo_detail_is_gudang_penerima 	= DepoDetail.DepoDetailMenu[i].depo_detail_is_gudang_penerima;

				if( depo_detail_flag_jual == 1 ){	IsJual = 'Iya';	 	}
				else 							{	IsJual = 'Tidak'; 	}

				if( depo_detail_is_gudang_penerima == 1 )	{	Penerima = 'Iya';	}
				else 										{	Penerima = 'Tidak';	}

				var strmenu = '';
				var strD = '';
				
				<?php
				if($Menu_Access["D"] == 1)
				{
					?>
					strD = '<button style="width: 40px;" class="btn btn-danger btndeletedepo_detailmenu form-control" onclick="DeleteDepoDetailMenu(\''+ depo_detail_id +'\', \''+ depo_detail_nama +'\', \''+ depo_id +'\', \''+ depo_detail_catatan +'\', \''+ depo_detail_flag_jual +'\', \''+ depo_detail_is_gudang_penerima +'\')"><i class="fa fa-times"></i></button>';
					<?php
				}
				?>
				
				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td width="20%">'+ depo_detail_nama +'</td>';
				strmenu = strmenu + '	<td width="30%">'+ depo_detail_catatan +'</td>';
				strmenu = strmenu + '	<td width="20%">'+ IsJual +'</td>';
				strmenu = strmenu + '	<td width="20%">'+ Penerima +'</td>';
				strmenu = strmenu + '	<td><span>';
				strmenu = strmenu + strD;
				strmenu = strmenu + '	</span></td>';
				strmenu = strmenu + '</tr>';
				
				$("#tabledepodetailmenu > tbody").append( strmenu );
			}
		}
		
		$('#tabledepodetailmenu').DataTable(
		{
			retrieve: true,
			"dom": '<"left"f>rtip'
		});
	}
	
	$("#btnback").click
	(
		function()
		{
			GetDepoMenu();
		}
	);
	

	function ViewDepoDetailMenu( depo_id )
	{
		$.ajax(
		{
			type: 'POST',    
			url: "<?= base_url('Depo/GetDepoDetailMenu') ?>",
			data: {	depo_id : depo_id },
			success: function( response )
			{
				if(response)
				{
					ChDepoDetailMenu( response );
				}
			}
		});	
	}
	
	
	<?php
	if($Menu_Access["D"] == 1)
	{
	?>		
		$("#btnnodeletedepo").click
		(
			function()
			{
				GetDepoMenu();	
			}
		);
	
		// Delete Voucher
		function DeleteDepoMenu( depo_id, depo_nama )
		{
			$("#lbdeletedepo").html( depo_nama );
			$("#hddeletedepo_id").val( depo_id );
			
			$("#previewdeletedepo").modal('show');
		}
		
		// Delete Voucher 1
		function DeleteDepoDetailMenu( depo_detail_id, depo_detail_nama, depo_id, depo_detail_catatan, depo_detail_flag_jual, depo_detail_is_gudang_penerima )
		{
			$("#lbdeletedepo_detail").html( depo_detail_nama );
			$("#hddeletedepo_detail_depo_id").val( depo_id );
			$("#hddeletedepo_detail_depo_detail_id").val( depo_detail_id );

			$("#previewdeletedepo_detail").modal('show');
		}
	
		$("#btnyesdeletedepo_detail").click
		(
			function()
			{
				var depo_detail_id 	= $("#hddeletedepo_detail_depo_detail_id").val();
				var depo_id 		= $("#hddeletedepo_detail_depo_id").val();
				
				$.ajax(
				{
					type: 'POST',    
					url: "<?= base_url('Depo/DeleteDepoDetailMenu') ?>",
					data: {
							depo_detail_id : depo_detail_id, 
							depo_id : depo_id
							},
					success: function( response )
					{
						if(response == 1)
						{
							var msg = 'Detail Depo berhasil dihapus.';
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
							var msg = 'Depo Detail sudah dipakai pada Transaksi lain.';
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
							var msg = 'Tidak bisa menghapus semua Detail Depo.';
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
						
						GetDepoMenu();	
					}
				});	
			}
		);
		
		$("#btnyesdeletedepo").click
		(
			function()
			{
				var depo_id = $("#hddeletedepo_id").val();
				
				$.ajax(
				{
					type: 'POST',    
					url: "<?= base_url('Depo/DeleteDepoMenu') ?>",
					data: {
							depo_id : depo_id 
							},
					success: function( response )
					{
						if(response == 1)
						{
							var msg = 'Depo berhasil dihapus.';
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
							
							GetDepoMenu();
						}
						else if(response == 2)
						{
							var msg = 'Error: Data tidak bisa dihapus karena sudah terpakai di transaksi lain.';
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

		$("#btnaddupdatedepo_detail").click
		(
			function()
			{
				var strclient = '';
				if( arrClient.length > 0 )
				{
					for( i=0 ; i<arrClient.length ; i++ )
					{
						var client_wms_id 	= arrClient[i].client_wms_id;
						var client_wms_nama = arrClient[i].client_wms_nama;
						
						strclient += '<option value="'+ client_wms_id +'">'+ client_wms_nama +'</option>';
					}
				}
				
				var str = '';
				
				var strvoucher = '';
		
				str = str + '<tr>';
				str = str + '	<td width="20%"><input type="text" class="txtupdatedepo_detail_depo_detail_nama form-control" value="" /></td>';
				str = str + '	<td width="20%"><input type="text" class="txtupdatedepo_detail_depo_detail_catatan form-control" placeholder="Input Catatan..." /></td>';
				str = str + '	<td width="20%"><select class="cbupdateclient_id form-control">'+ strclient +'</select></td>';
				str = str + '	<td width="15%" align="center"><input type="checkbox" class="chupdatedepo_detail_depo_detail_flag_jual checkbox_form" /></td>';
				str = str + '	<td width="15%" align="center"><input type="checkbox" class="chupdatedepo_detail_depo_detail_is_gudang_penerima checkbox_form" /></td>';
				str = str + '	<td width="10%"><button type="button" class="btn btn-danger btnupdatedeletetd" onclick="DeleteUpdateTempDepoDetail( this.parentNode.parentNode.rowIndex )"><i class="fa fa-times"></i></button></td>';
				str = str + '</tr>';
				
				$("#updatetableadddepodetailmenu").append( str );
			}
		);
		
		$("#btnupdateback").click
		(
			function()
			{
				GetDepoMenu();	
			}
		);
			
		function DeleteUpdateTempDepoDetail( Idx )
		{
			var table = document.getElementById('updatetableadddepodetailmenu');
			
			table.deleteRow( Idx );
		}
	
		function UpdateDepoMenu( depo_id, depo_kode, depo_nama, depo_status, depo_alamat, depo_latitude, depo_longitude )
		{
			//DepoCode = DepoKode;
			
			
			$.ajax(
			{
				type: 'POST',    
				url: "<?= base_url('Depo/GetDepoDetailMenu') ?>",
				data: 
				{	
					depo_id : depo_id 
				},
				success: function( response )
				{
					if(response)
					{
						ChUpdateDepoDetailMenu( response, depo_id, depo_kode, depo_nama, depo_status, depo_alamat, depo_latitude, depo_longitude );
					}
				}
			});	
		}

		function ChUpdateDepoDetailMenu( JSONDepoDetail, depo_id, depo_kode, depo_nama, depo_status, depo_alamat, depo_latitude, depo_longitude )
		{
			$("#updatetableadddepodetailmenu").html('');
			
			var DepoDetail = JSON.parse( JSONDepoDetail );
	
			var depo_id 		= DepoDetail.DepoMenu[0].depo_id;
			var	depo_kode 		= DepoDetail.DepoMenu[0].depo_kode;
			var depo_nama 		= DepoDetail.DepoMenu[0].depo_nama;
			var depo_status 	= DepoDetail.DepoMenu[0].depo_status; 
			var depo_alamat 	= DepoDetail.DepoMenu[0].depo_alamat; 
			var depo_latitude 	= DepoDetail.DepoMenu[0].depo_latitude; 
			var depo_longitude 	= DepoDetail.DepoMenu[0].depo_longitude; 
			
			$("#txtupdatedepo_id").val( depo_id );
			$("#txtupdatedepo_kode").val( depo_kode );
			$("#txtupdatedepo_nama").val( depo_nama );
			
			if( depo_status == 0 ) {	$("#chupdatedepo_status").prop('checked',false ); }
			else					 {	$("#chupdatedepo_status").prop('checked',true ); }
			
			$("#txtupdatedepo_alamat").val( depo_alamat );
			$("#txtupdatedepo_latitude").val( depo_latitude );
			$("#txtupdatedepo_longitude").val( depo_longitude );
			
			var strclient = '';
			if( arrClient.length > 0 )
			{
				for( i=0 ; i<arrClient.length ; i++ )
				{
					var client_wms_id 	= arrClient[i].client_wms_id;
					var client_wms_nama = arrClient[i].client_wms_nama;
					
					strclient += '<option value="'+ client_wms_id +'">'+ client_wms_nama +'</option>';
				}
			}
			
			for( i=0 ; i<DepoDetail.DepoDetailMenu.length ; i++ )
			{
				var depo_detail_id 					= DepoDetail.DepoDetailMenu[i].depo_detail_id;
				var depo_detail_nama 				= DepoDetail.DepoDetailMenu[i].depo_detail_nama;
				var depo_id 						= DepoDetail.DepoDetailMenu[i].depo_id;
				var depo_detail_catatan 			= DepoDetail.DepoDetailMenu[i].depo_detail_catatan;
				var depo_detail_flag_jual 			= DepoDetail.DepoDetailMenu[i].depo_detail_flag_jual;
				var depo_detail_is_gudang_penerima 	= DepoDetail.DepoDetailMenu[i].depo_detail_is_gudang_penerima;
				var client_id				 		= DepoDetail.DepoDetailMenu[i].client_id;

				if( depo_detail_flag_jual == 1 ){	IsJual = 'checked'; }
				else 							{	IsJual = ''; }
				
				if( depo_detail_is_gudang_penerima == 1 )	{	Penerima = 'checked'; 	}
				else 										{	Penerima = ''; 			}
				
				var strmenu = '';
				
				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td width="20%" align="left"><input type="text" class="txtupdatedepo_detail_depo_detail_nama form-control" value="'+ depo_detail_nama +'" disabled="disabled" /></td>';
				strmenu = strmenu + '	<td width="20%" align="left"><input type="text" class="txtupdatedepo_detail_depo_detail_catatan form-control" disabled="disabled" placeholder="Input Catatan..." /></td>';
				strmenu = strmenu + '	<td width="20%"><select class="cbupdateclient_id form-control">'+ strclient +'</select></td>';
				strmenu = strmenu + '	<td width="15%" align="center"><input type="checkbox" class="chupdatedepo_detail_depo_detail_flag_jual checkbox_form" '+ IsJual +' /> </td>';
				strmenu = strmenu + '	<td width="15%" align="center"><input type="checkbox" class="chupdatedepo_detail_depo_detail_is_gudang_penerima checkbox_form" '+ Penerima +' /> </td>';
				strmenu = strmenu + '	<td width="10%" align="left"></td>';
				strmenu = strmenu + '</tr>';
				
				$("#updatetableadddepodetailmenu").append( strmenu );
			}
			
			$("#previewupdatedepo").modal('show');	
		}
		
		$("#btnsaveupdatedepo").click
		(
			function()
			{
			
				var depo_id 	= $("#txtupdatedepo_id").val();
				var depo_kode	= $("#txtupdatedepo_kode").val();
				var depo_nama 	= $("#txtupdatedepo_nama").val();
				var depo_status = $("#txtupdatedepo_status").prop('checked');
				
				if( depo_status == false )	{ Status = 0; }
				else						{ Status = 1; }
				
				var depo_alamat 	= $("#txtupdatedepo_alamat").val();
				var depo_latitude 	= $("#txtupdatedepo_latitude").val();
				var depo_longitude 	= $("#txtupdatedepo_longitude").val();

				var lDepoDetail = $(".txtupdatedepo_detail_depo_detail_nama").length;
				
				if( lDepoDetail == 0)
				{
					var msg = 'Tidak ada Detail Depo yang dipilih.';
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
					
					for( i=0 ; i<lDepoDetail ; i++ )
					{
						var depo_detail_nama 	= $(".txtupdatedepo_detail_depo_detail_nama").eq(i).val();
						
						if( depo_detail_nama == '' )
						{
							numerror = 1;
						}
							
					}
					
					if( numerror == 1 )
					{
						var msg = 'Harap mengisi semua Detail Depo.';
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
						var arrDepoDetail = [];
						
						for( i=0 ; i<lDepoDetail ; i++ )
						{
							var IsExists = $(".txtupdatedepo_detail_depo_detail_nama").eq(i).attr('disabled');
							
							if( IsExists != 'disabled' )
							{
								var depo_detail_nama 				= $(".txtupdatedepo_detail_depo_detail_nama").eq(i).val();
								var depo_detail_catatan 			= $(".txtupdatedepo_detail_depo_detail_catatan").eq(i).val();
								var depo_detail_flag_jual 			= $(".chupdatedepo_detail_depo_detail_flag_jual").eq(i).prop('checked');
								var depo_detail_is_gudang_penerima 	= $(".chupdatedepo_detail_depo_detail_is_gudang_penerima").eq(i).prop('checked');
								var client_id 						= $(".cbupdateclient_id option:selected").eq(i).val();
								
								if( depo_detail_flag_jual == false ){ 	var IsJual = 0; }
								else								{ 	var IsJual = 1; }
								
								if( depo_detail_is_gudang_penerima == false )	{ 	var Penerima = 0; }
								else											{ 	var Penerima = 1; }
								
								arrDepoDetail.push( { client_id: client_id, depo_detail_nama : depo_detail_nama, depo_detail_catatan : depo_detail_catatan, depo_detail_flag_jual : IsJual, depo_detail_is_gudang_penerima : Penerima } );
							}
						}
						
						$.ajax(
						{
							type: 'POST',    
							url: "<?= base_url('Depo/SaveUpdateDepo') ?>",
							data: {	
									depo_id 		: depo_id,
									depo_kode 		: depo_kode,
									depo_nama 		: depo_nama,
									depo_status 	: Status,
									depo_alamat		: depo_alamat,
									depo_latitude	: depo_latitude,
									depo_longitude	: depo_longitude,
									
									arrDepoDetail 	: arrDepoDetail
									},
							success: function( response )
							{
								if(response == 1)
								{
									var msg = 'Depo berhasil diubah.';
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
									
									$("#previewupdatedepo").modal('hide');
									
									GetDepoMenu();
								}
								/*
								else if(response == 4) // alphanumeric
								{
									var msg = 'Only Numbers or Letters are Allowed for Depo Name.';
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
		$("#btnaddnewdepo").removeAttr('style');
		/*
		$("#txtdeponama").change
		(
			function()
			{
				var str = $("#txtupdatedeponame").val();
				
				var letterNumber = /^[0-9a-zA-Z ]+$/;
				
				if(	!str.match(letterNumber) )
				{
					$("#txtupdatedeponame").val('');
					
					var msg = 'Only Numbers or Letters are Allowed for Depo Name.';
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
		
		$("#btnadddepo_detail").click
		(
			function()
			{
				var strclient = '';
				if( arrClient.length > 0 )
				{
					for( i=0 ; i<arrClient.length ; i++ )
					{
						var client_wms_id 	= arrClient[i].client_wms_id;
						var client_wms_nama = arrClient[i].client_wms_nama;
						
						strclient += '<option value="'+ client_wms_id +'">'+ client_wms_nama +'</option>';
					}
				}
				
				var str = '';

				str = str + '<tr>';
				str = str + '	<td width="20%"><input type="text" class="txtdepo_detail_depo_detail_nama form-control" /></td>';
				str = str + '	<td width="20%"><input type="text" class="txtdepo_detail_depo_detail_catatan form-control" placeholder="Input Catatan..." /></td>';
				str = str + '	<td width="20%"><select class="cbclient_id form-control">'+ strclient +'</select></td>';
				str = str + '	<td width="15%" align="center"><input type="checkbox" class="chdepo_detail_depo_detail_flag_jual checkbox_form" /></td>';
				str = str + '	<td width="15%" align="center"><input type="checkbox" class="chdepo_detail_depo_detail_is_gudang_penerima checkbox_form" /></td>';
				str = str + '	<td width="10%"><button type="button" class="btn btn-danger btndeletetd" onclick="DeleteTempDepoDetail( this.parentNode.parentNode.rowIndex )"><i class="fa fa-times"></i></button></td>';
				str = str + '</tr>';
				
				$("#tableadddepodetailmenu").append( str );
			}
		);

		function DeleteTempDepoDetail( Idx )
		{
			var table = document.getElementById('tableadddepodetailmenu');
			
			table.deleteRow( Idx );
		}
		
		// Add New Voucher
		/*
		$("#txtdepo").change
		(
			function()
			{
				var str = $("#txtdepo").val();
				
				var letterNumber = /^[0-9a-zA-Z ]+$/;
				if(	!str.match(letterNumber) )
				{
					$("#txtdeponame").val('');
					
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
		
		$("#btnaddnewdepo").click
		(
			function()
			{
				ResetForm();
				$("#previewaddnewdepo").modal('show');
			}
		);
		
		$("#btnsaveaddnewdepo").click
		(
			function()
			{					
				//var KodePos	 	= $("#txtdepo").val();
				var depo_kode	 	= $("#txtdepo_kode").val();
				var depo_nama 		= $("#txtdepo_nama").val();
				var depo_alamat 	= $("#txtdepo_alamat").val();
				var depo_latitude 	= $("#txtdepo_latitude").val();
				var depo_longitude	= $("#txtdepo_longitude").val();
				
				var depo_status 	= $("#chdepo_status").prop('checked');

				if( depo_status == false )	{	Status = 0; }
				else						{	Status = 1; }
				var lDepoDetail = $(".txtdepo_detail_depo_detail_nama").length;
		
				if( lDepoDetail == 0)
				{
					var msg = 'Harap mengisi semua Detail Depo.';
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
					
					for( i=0 ; i<lDepoDetail ; i++ )
					{
						var depo_detail_nama	= $(".txtdepo_detail_depo_detail_nama").eq(i).val();
						
						if( depo_detail_nama == '')
						{
							numerror = 1;
						}
							
					}
					
					if( numerror == 1 )
					{
						var msg = 'Harap mengisi semua Detail Depo.';
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
						var arrDepoDetail = [];
						
						for( i=0 ; i<lDepoDetail ; i++ )
						{
							var depo_detail_nama 				= $(".txtdepo_detail_depo_detail_nama").eq(i).val();
							var depo_detail_catatan 			= $(".txtdepo_detail_depo_detail_catatan").eq(i).val();
							var depo_detail_flag_jual 			= $(".chdepo_detail_depo_detail_flag_jual").eq(i).prop('checked');
							var depo_detail_is_gudang_penerima 	= $(".chdepo_detail_depo_detail_is_gudang_penerima").eq(i).prop('checked');
							var client_id					 	= $(".cbclient_id option:selected").eq(i).val();
							
							if( depo_detail_flag_jual == false ){ 	var IsJual = 0; }
							else								{ 	var IsJual = 1; }
							
							if( depo_detail_is_gudang_penerima == false )	{ 	var Penerima = 0; }
							else											{ 	var Penerima = 1; }
							
							arrDepoDetail.push( { client_id: client_id, depo_detail_nama : depo_detail_nama, depo_detail_catatan : depo_detail_catatan, depo_detail_flag_jual : IsJual, depo_detail_is_gudang_penerima : Penerima } );
						}
					
						$.ajax(
						{
							type: 'POST',    
							url: "<?= base_url('Depo/SaveAddNewDepo') ?>",
							data: {	
									depo_kode 		: depo_kode,
									depo_nama 		: depo_nama,
									depo_status 	: Status,
									depo_alamat 	: depo_alamat,
									depo_latitude 	: depo_latitude,
									depo_longitude 	: depo_longitude,
				
									arrDepoDetail	: arrDepoDetail
									},
							success: function( response )
							{
								if(response == 1)
								{
									var msg = 'Depo berhasil ditambah.';
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
									
									$("#previewaddnewdepo").modal('hide');
									
									GetDepoMenu();
									
									ResetForm();
								}
								else if(response == 2)
								{
									var msg = 'Kode Depo sudah ada.';
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
		//$("#txtdepo").val('');
		$("#txtdepo_kode").val('');
		$("#txtdepo_nama").val('');
		$("#chdepo_status").val('');
		$("#txtdepo_alamat").val('');
		$("#txtdepo_latitude").val('');
		$("#txtdepo_longitude").val('');

		$("#tableadddepodetailmenu").html('');
		$("#tableadddepodetailmenu > tbody").html('');
		
		//$("#txtupdatedepo").val('');
		$("#txtupdatedepo_kode").val('');
		$("#txtupdatedepo_nama").val('');
		$("#chupdatedepo_status").val('');
		$("#txtupdatedepo_alamat").val('');
		$("#txtupdatedepo_latitude").val('');
		$("#txtupdatedepo_longitude").val('');

		$("#tableupdateadddepodetail").html('');
		$("#tableupdatedepodetailmenu > tbody").html('');
	}
</script>