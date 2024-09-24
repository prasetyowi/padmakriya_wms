<script type="text/javascript">	
	var KaryawanCode = '';
	var arrKodePos = [];

	$(document).ready
	(
		function() 
		{
			GetKaryawanMenu();
			
			$("#txtKaryawan_Birthdate").datetimepicker(
			{
				defaultDate: new Date(),
				format: 'DD/MM/YYYY'
			});
			
			$("#updatetxtKaryawan_Birthdate").datetimepicker(
			{
				defaultDate: new Date(),
				format: 'DD/MM/YYYY'
			});
		}
	);
	
	
	function GetKaryawanMenu()
	{
		$.ajax(
		{
			type: 'POST',    
			url: "<?= base_url('Karyawan/GetKaryawanMenu') ?>",
			//data: "Location="+ Location,
			success: function( response )
			{
				if(response)
				{
					ChKaryawanMenu( response );
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

	function ChKaryawanMenu( JSONKaryawan )
	{
		$("#tablekaryawanmenu > tbody").html('');
		
		var Karyawan = JSON.parse( JSONKaryawan );
		
		var StatusC = Karyawan.AuthorityMenu[0].StatusC;
		var StatusU = Karyawan.AuthorityMenu[0].StatusU;
		var StatusD = Karyawan.AuthorityMenu[0].StatusD;
		
		if( StatusC == 0 )	{	$("#btnaddnewkaryawan").attr('style','display: none;');	}
	
		if( Karyawan.KaryawanMenu.length > 0 )
		{
			
			if( $.fn.DataTable.isDataTable('#tablekaryawanmenu') ) 
			{
				$('#tablekaryawanmenu').DataTable().destroy();
			}
			
			if( $.fn.DataTable.isDataTable('#tablekaryawandetailmenu') ) 
			{
				$('#tablekaryawandetailmenu').DataTable().destroy();
			}	
			
			$('#tablekaryawanmenu tbody').empty();
			$('#tablekaryawandetailmenu tbody').empty();
			
			for( i=0 ; i<Karyawan.KaryawanMenu.length ; i++)
			{
				var Karyawan_ID 		= Karyawan.KaryawanMenu[i].Karyawan_ID;
				var Perusahaan_ID 		= Karyawan.KaryawanMenu[i].Perusahaan_ID;
				var Karyawan_No		 	= Karyawan.KaryawanMenu[i].Karyawan_No;
				var Karyawan_UserName 	= Karyawan.KaryawanMenu[i].Karyawan_UserName;
				var Karyawan_Pass 		= Karyawan.KaryawanMenu[i].Karyawan_Pass;
				var Karyawan_Name 		= Karyawan.KaryawanMenu[i].Karyawan_Name;
				var Karyawan_Phone 		= Karyawan.KaryawanMenu[i].Karyawan_Phone;
				var Karyawan_Email 		= Karyawan.KaryawanMenu[i].Karyawan_Email;
				var Karyawan_Birthdate 	= Karyawan.KaryawanMenu[i].Karyawan_Birthdate;
				//var Karyawan_TOP 		= Karyawan.KaryawanMenu[i].Karyawan_TOP;
				//var Karyawan_PayIssuer 	= Karyawan.KaryawanMenu[i].Karyawan_PayIssuer;
				//var Karyawan_ACC 		= Karyawan.KaryawanMenu[i].Karyawan_ACC;
				
				var strmenu = '';
			
				var strU = '';
				var strD = '';
				
				<?php
				if($Menu_Access["U"] == 1)
				{
					?>
					strU = '<button style="width: 40px;" class="btn btn-warning btneditkaryawanmenu form-control" onclick="UpdateKaryawanMenu(\''+ Karyawan_ID +'\',\''+ Karyawan_UserName +'\',\''+ Karyawan_Pass +'\',\''+ Karyawan_Name +'\',\''+ Karyawan_Phone +'\',\''+ Karyawan_Email +'\',\''+ Karyawan_Birthdate +'\')"><i class="fa fa-pencil"></i></button>'; 
					<?php
				}
				if($Menu_Access["D"] == 1)
				{
					?>
					strD = '<button style="width: 40px;" class="btn btn-danger btndeletekaryawanmenu form-control" onclick="DeleteKaryawanMenu(\''+ Karyawan_ID +'\',\''+ Karyawan_Name +'\')"><i class="fa fa-times"></i></button>'; 
					<?php
				}
				?>
				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td>'+ Karyawan_Name +'</td>';
				strmenu = strmenu + '	<td>'+ Karyawan_Phone +'</td>';
				strmenu = strmenu + '	<td>'+ Karyawan_Email +'</td>';
				strmenu = strmenu + '	<td><span>';
				strmenu = strmenu + strU;
				strmenu = strmenu + strD;
				strmenu = strmenu + '		<button style="width: 40px;" class="btn btn-primary btnviewkaryawandetailmenu form-control" onclick="ViewKaryawanDetailMenu(\''+ Karyawan_ID +'\')"><i class="fa fa-eye"></i></button> ';
				strmenu = strmenu + '	</span></td>';
				strmenu = strmenu + '</tr>';
				
				$("#tablekaryawanmenu > tbody").append( strmenu );
			}
		}
		
		$("#cbperusahaan").html('');
		$("#cbperusahaan").html('<option value="">** Pilih Perusahaan **</option>');
		$("#updatecbperusahaan").html('');
		$("#updatecbperusahaan").html('<option value="">** Pilih Perusahaan **</option>');
		if( Karyawan.PerusahaanMenu != 0 )
		{
			for( i=0 ; i<Karyawan.PerusahaanMenu.length ; i++)
			{
				var Perusahaan_ID 	= Karyawan.PerusahaanMenu[i].Perusahaan_ID;
				var Perusahaan_Kode = Karyawan.PerusahaanMenu[i].Perusahaan_Kode;
				var Perusahaan_Nama	= Karyawan.PerusahaanMenu[i].Perusahaan_Nama;
				
				var str = '';
				str = str + '<option value="'+ Perusahaan_ID +'">'+ Perusahaan_Kode +'</option>';
				
				$("#cbperusahaan").append( str );
				$("#updatecbperusahaan").append( str );
			}
		}
		
		$("#tablekaryawanmenu").DataTable(
		{
			retrieve: true,
			"dom": '<"left"f>rtp'
		});
		
		$("#tablekaryawandetailmenu").DataTable(
		{
			retrieve: true,
			"dom": '<"left"f>rtp'
		});
	}
	
	function ChKaryawanDetailMenu( JSONKaryawan1 )
	{
		$("#tablekaryawandetailmenu > tbody").html('');
		
		var KaryawanDetail = JSON.parse( JSONKaryawan1 );
		
		if( KaryawanDetail.KaryawanDetailMenu != 0 )
		{
			for( i=0 ; i<KaryawanDetail.KaryawanDetailMenu.length ; i++)
			{
				var KaryawanDetail_ID 		= KaryawanDetail.KaryawanDetailMenu[i].KaryawanDetail_ID;
				var Karyawan_ID 			= KaryawanDetail.KaryawanDetailMenu[i].Karyawan_ID;
				var Recip_Name 				= KaryawanDetail.KaryawanDetailMenu[i].Recip_Name;
				var KodePosDetail_ID 		= KaryawanDetail.KaryawanDetailMenu[i].KodePosDetail_ID;
				var Recip_Address 			= KaryawanDetail.KaryawanDetailMenu[i].Recip_Address;
				var Recip_Phone 			= KaryawanDetail.KaryawanDetailMenu[i].Recip_Phone;
				var IsDefaultShipAddress 	= KaryawanDetail.KaryawanDetailMenu[i].IsDefaultShipAddress;

				if( IsDefaultShipAddress == 1 ) {	IsDefault = 'Default'; }
				else 							{	IsDefault = ''; }

				var strmenu = '';
				var strD = '';
				
				<?php
				if($Menu_Access["D"] == 1)
				{
					?>
					strD = '<button style="width: 40px;" class="btn btn-danger btndeletekaryawandetailmenu form-control" onclick="DeleteKaryawanDetailMenu(\''+ KaryawanDetail_ID +'\', \''+ Recip_Name +'\', \''+ Karyawan_ID +'\', \''+ IsDefaultShipAddress +'\')"><i class="fa fa-times"></i></button>';
					<?php
				}
				?>
				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td>'+ Recip_Name +'</td>';
				strmenu = strmenu + '	<td>'+ Recip_Address +'</td>';
				strmenu = strmenu + '	<td>'+ Recip_Phone +'</td>';
				strmenu = strmenu + '	<td>'+ IsDefault +'</td>';
				strmenu = strmenu + '	<td><span>';
				strmenu = strmenu + strD;
				strmenu = strmenu + '	</span></td>';
				strmenu = strmenu + '</tr>';
				
				$("#tablekaryawandetailmenu > tbody").append( strmenu );
			}
		}
		
		$('#tablekaryawandetailmenu').DataTable(
		{
			retrieve: true,
			"dom": '<"left"f>rtip'
		});
	}
	
	$("#btnback").click
	(
		function()
		{
			GetKaryawanMenu();
		}
	);
	

	function ViewKaryawanDetailMenu( Karyawan_ID )
	{
		$.ajax(
		{
			type: 'POST',    
			url: "<?= base_url('Karyawan/GetKaryawanDetailMenu') ?>",
			data: {	Karyawan_ID : Karyawan_ID },
			success: function( response )
			{
				if(response)
				{
					ChKaryawanDetailMenu( response );
				}
			}
		});	
	}
	
	
	<?php
	if($Menu_Access["D"] == 1)
	{
	?>		
		$("#btnnodeletekaryawan").click
		(
			function()
			{
				GetKaryawanMenu();	
			}
		);
	
		// Delete Voucher
		function DeleteKaryawanMenu( Karyawan_ID, Karyawan_Name )
		{
			$("#lbdeletekaryawan").html( Karyawan_Name );
			$("#hddeletekaryawanid").val( Karyawan_ID );
			
			$("#previewdeletekaryawan").modal('show');
		}
		
		// Delete Voucher 1
		function DeleteKaryawanDetailMenu( KaryawanDetail_ID, Recip_Name, Karyawan_ID, IsDefaultShipAddress)
		{
			$("#lbdeletekaryawandetail").html( Recip_Name );
			$("#hddeletekaryawandetail_karyawan_id").val( Karyawan_ID );
			$("#hddeletekaryawandetail_karyawanva_id").val( KaryawanDetail_ID );

			$("#previewdeletekaryawandetail").modal('show');
		}
	
		$("#btnyesdeletekaryawandetail").click
		(
			function()
			{
				var KaryawanDetail_ID = $("#hddeletekaryawandetail_karyawandetail_id").val();
				var Karyawan_ID = $("#hddeletekaryawandetail_karyawan_id").val();
				
				$.ajax(
				{
					type: 'POST',    
					url: "<?= base_url('Karyawan/DeleteKaryawanDetailMenu') ?>",
					data: {
							KaryawanDetail_ID : KaryawanDetail_ID, 
							Karyawan_ID : Karyawan_ID
							},
					success: function( response )
					{
						if(response == 1)
						{
							var msg = 'Detail Karyawan berhasil dihapus.';
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
							var msg = 'Detail Karyawan sudah dipakai pada Transaksi lain.';
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
							var msg = 'Tidak bisa menghapus semua Detail Karyawan.';
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
						
						GetKaryawanMenu();	
					}
				});	
			}
		);
		
		$("#btnyesdeletekaryawan").click
		(
			function()
			{
				var Karyawan_ID = $("#hddeletekaryawanid").val();
				
				$.ajax(
				{
					type: 'POST',    
					url: "<?= base_url('Karyawan/DeleteKaryawanMenu') ?>",
					data: {
							Karyawan_ID : Karyawan_ID 
							},
					success: function( response )
					{
						if(response == 1)
						{
							var msg = 'Karyawan berhasil dihapus.';
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
							
							GetKaryawanMenu();
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
	/*
	<?php
	if($Menu_Access["U"] == 1)
	{
	?>	

		$("#btnaddupdatekaryawandetail").click
		(
			function()
			{
				var str = '';
				
				var strvoucher = '';
	
				str = str + '<tr>';
				str = str + '	<td width="30%"><input type="text" class="txtupdatekaryawandetailKaryawanVA_nama form-control" value="" /></td>';
				str = str + '	<td width="30%"><input type="text" class="txtupdatekaryawandetailKaryawan_Catatan form-control" value="" /></td>';
				str = str + '	<td width="30%"><input type="checkbox" class="chupdatekaryawandetailflagjual checkbox_form" /></td>';
				str = str + '	<td width="10%"><button type="button" class="btn btn-danger btnupdatedeletetd" onclick="DeleteUpdateKaryawan1( this.parentNode.parentNode.rowIndex )"><i class="fa fa-times"></i></button></td>';
				str = str + '</tr>';
				
				$("#tableupdatekaryawandetail").append( str );
			}
		);
		
		$("#btnupdateback").click
		(
			function()
			{
				GetKaryawanMenu();	
			}
		);
			
		function DeleteUpdateTempKaryawan1( Idx )
		{
			var table = document.getElementById('tableupdatekaryawandetail');
			
			table.deleteRow( Idx );
		}
	
		function UpdateKaryawanMenu( karyawan_id, kode_karyawan, nama_karyawan, karyawan_Status, Karyawan_alamat, Karyawan_Lat, Karyawan_Lon )
		{
			//KaryawanCode = KaryawanKode;
			
			
			$.ajax(
			{
				type: 'POST',    
				url: "<?= base_url('Karyawan/GetKaryawan1Menu') ?>",
				data: 
				{	
					karyawan_id : karyawan_id 
				},
				success: function( response )
				{
					if(response)
					{
						ChUpdateKaryawan1Menu( response, karyawan_id, kode_karyawan, nama_karyawan, karyawan_Status, Karyawan_alamat, Karyawan_Lat, Karyawan_Lon );
					}
				}
			});	
		}

		function ChUpdateKaryawan1Menu( JSONKaryawan1, karyawan_id, kode_karyawan, nama_karyawan, karyawan_Status, Karyawan_alamat, Karyawan_Lat, Karyawan_Lon )
		{
			$("#tableupdatekaryawandetail").html('');
			
			var Karyawan1 = JSON.parse( JSONKaryawan1 );
	
			var karyawan_id 		= Karyawan1.KaryawanMenu[0].karyawan_id;
			var	kode_karyawan 	= Karyawan1.KaryawanMenu[0].kode_karyawan;
			var nama_karyawan 	= Karyawan1.KaryawanMenu[0].nama_karyawan;
			var karyawan_Status 	= Karyawan1.KaryawanMenu[0].karyawan_Status; 
			var Karyawan_alamat 	= Karyawan1.KaryawanMenu[0].Karyawan_alamat; 
			var Karyawan_Lat 		= Karyawan1.KaryawanMenu[0].Karyawan_Lat; 
			var Karyawan_Lon 		= Karyawan1.KaryawanMenu[0].Karyawan_Lon; 
			
			$("#txtupdatekaryawan_id").val( karyawan_id );
			$("#txtupdatekode_karyawan").val( kode_karyawan );
			$("#txtupdatenama_karyawan").val( nama_karyawan );
			
			if( karyawan_Status == 0 ) {	$("#chupdatekaryawan_Status").prop('checked',false ); }
			else					 {	$("#chupdatekaryawan_Status").prop('checked',true ); }
			
			$("#txtupdatekaryawan_alamat").val( Karyawan_alamat );
			$("#txtupdatekaryawan_lat").val( Karyawan_Lat );
			$("#txtupdatekaryawan_lon").val( Karyawan_Lon );
			
			for( i=0 ; i<Karyawan1.Karyawan1Menu.length ; i++ )
			{
				var GUDANGVA_ID 	= Karyawan1.Karyawan1Menu[i].GUDANGVA_ID;
				var KaryawanVA_nama 	= Karyawan1.Karyawan1Menu[i].KaryawanVA_nama;
				var Karyawan_ID 		= Karyawan1.Karyawan1Menu[i].Karyawan_ID;
				var Karyawan_Catatan 	= Karyawan1.Karyawan1Menu[i].Karyawan_Catatan;
				var flagjual 		= Karyawan1.Karyawan1Menu[i].flagjual;

				if( flagjual == 1 ) {	IsJual = 'checked'; }
				else 				{	IsJual = ''; }
				
				var strmenu = '';
				
				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td width="30%" align="left"><input type="text" class="txtupdatekaryawandetailKaryawanVA_nama form-control" value="'+ KaryawanVA_nama +'" disabled="disabled" /></td>';
				strmenu = strmenu + '	<td width="30%" align="left"><input type="text" class="txtupdatekaryawandetailKaryawan_Catatan form-control" value="'+ Karyawan_Catatan +'" disabled="disabled" /></td>';
				strmenu = strmenu + '	<td width="30%" align="left"><input type="checkbox" class="chupdatekaryawandetailflagjual checkbox_form" '+ IsJual +' /> </td>';
				strmenu = strmenu + '	<td width="10%" align="left"></td>';
				strmenu = strmenu + '</tr>';
				
				$("#tableupdatekaryawandetail").append( strmenu );
			}
			
			$("#previewupdatekaryawan").modal('show');	
		}
		
		$("#btnsaveupdatekaryawan").click
		(
			function()
			{
			
				var karyawan_id 		= $("#txtupdatekaryawan_id").val();
				var kode_karyawan	 	= $("#txtupdatekode_karyawan").val();
				var nama_karyawan 	= $("#txtupdatenama_karyawan").val();
				var karyawan_Status 	= $("#txtupdatekaryawan_status").prop('checked');
				
				if( karyawan_Status == false ){ Status = 0; }
				else						{ Status = 1; }
				
				var Karyawan_alamat 	= $("#txtupdatekaryawan_alamat").val();
				var Karyawan_Lat 		= $("#txtupdatekaryawan_lat").val();
				var Karyawan_Lon 		= $("#txtupdatekaryawan_lon").val();

				var lKaryawan1 = $(".txtupdatekaryawandetailKaryawanVA_nama").length;
				
				if( lKaryawan1 == 0)
				{
					var msg = 'Tidak ada Detail Karyawan yang dipilih.';
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
					
					for( i=0 ; i<lKaryawan1 ; i++ )
					{
						var KaryawanVA_nama 	= $(".txtupdatekaryawandetailKaryawanVA_nama").eq(i).val();
						
						if( KaryawanVA_nama == '' )
						{
							numerror = 1;
						}
							
					}
					
					if( numerror == 1 )
					{
						var msg = 'Harap mengisi semua Detail Karyawan.';
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
						var arrKaryawan1 = [];
						
						for( i=0 ; i<lKaryawan1 ; i++ )
						{
							var IsExists = $(".txtupdatekaryawandetailKaryawanVA_nama").eq(i).attr('disabled');
							
							if( IsExists != 'disabled' )
							{
								var KaryawanVA_nama 	= $(".txtupdatekaryawandetailKaryawanVA_nama").eq(i).val();
								var Karyawan_Catatan 	= $(".txtupdatekaryawandetailKaryawan_Catatan").eq(i).val();
								var flagjual 		= $(".chupdatekaryawandetailflagjual").eq(i).prop('checked');
								
								if( flagjual == false )	{ 	var IsJual = 0; }
								else					{ 	var IsJual = 1; }
								
								arrKaryawan1.push( { KaryawanVA_nama : KaryawanVA_nama, Karyawan_Catatan : Karyawan_Catatan, flagjual : IsJual } );
							}
						}
						
						$.ajax(
						{
							type: 'POST',    
							url: "<?= base_url('Karyawan/SaveUpdateKaryawan') ?>",
							data: {	
									karyawan_id 		: karyawan_id,
									kode_karyawan 	: kode_karyawan,
									nama_karyawan 	: nama_karyawan,
									karyawan_Status 	: Status,
									Karyawan_alamat	: Karyawan_alamat,
									Karyawan_Lat		: Karyawan_Lat,
									Karyawan_Lon		: Karyawan_Lon,
									
									arrKaryawan1 : arrKaryawan1
									},
							success: function( response )
							{
								if(response == 1)
								{
									var msg = 'Karyawan berhasil diubah.';
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
									
									$("#previewupdatekaryawan").modal('hide');
									
									GetKaryawanMenu();
								}
								/*
								else if(response == 4) // alphanumeric
								{
									var msg = 'Only Numbers or Letters are Allowed for Karyawan Name.';
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
				}
			}
		);
	<?php
	}
	?>
	*/
	<?php
	if($Menu_Access["C"] == 1)
	{
	?>			
		$("#btnaddnewkaryawan").removeAttr('style');
		/*
		$("#txtkaryawannama").change
		(
			function()
			{
				var str = $("#txtupdatekaryawanname").val();
				
				var letterNumber = /^[0-9a-zA-Z ]+$/;
				
				if(	!str.match(letterNumber) )
				{
					$("#txtupdatekaryawanname").val('');
					
					var msg = 'Only Numbers or Letters are Allowed for Karyawan Name.';
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
		
		$("#btnaddkaryawandetail").click
		(
			function()
			{
				var stropsi = '';
				
				arrKodePos = JSON.stringify( arrKodePos );
				
				/*
				for( i=0 ; i<arrKodePos.length ; i++ )
				{
					var KodePosDetail_ID 	= arrKodePos[i].KodePosDetail_ID;
					var Kelurahan_Nama 		= arrKodePos[i].Kelurahan_Nama;
					var KodePos 			= arrKodePos[i].KodePos;
					
					stropsi = stropsi + '<option value="'+ KodePosDetail_ID +'">'+ KodePos + ' - ' + Kelurahan_Nama +'</option>';
				}
				*/
				var str = '';
				var Rand = Math.random() * 100000000000000000;			
				str = str + '<tr>';
				str = str + '	<td width="20%"><input type="text" class="txtkaryawandetailRecip_Name form-control" /></td>';
				str = str + '	<td width="20%"><input type="text" id="txtkodepos_'+ Rand +'" class="txtkodepos form-control" /></td>';
				str = str + '	<td width="20%"><input type="text" class="txtkaryawandetailRecip_Address form-control" /></td>';
				str = str + '	<td width="15%"><input type="text" class="chkaryawandetailfRecip_Phone form-control" /></td>';
				str = str + '	<td width="15%" align="center">';
				str = str + '		<input type="radio" name="rbkaryawandetailfIsDefaultShipAddress"	class="rbkaryawandetailfIsDefaultShipAddress checkbox_form" />';
				str = str + '	</td>';
				str = str + '	<td width="10%"><button type="button" class="btn btn-danger btndeletetd" onclick="DeleteTempKaryawanDelete( this.parentNode.parentNode.rowIndex )"><i class="fa fa-times"></i></button></td>';
				str = str + '</tr>';
				
				$("#tableaddkaryawandetail").append( str );
				
				$(".rbkaryawandetailfIsDefaultShipAddress").eq(0).prop('checked', true );
				
				$("#txtkodepos_"+ Rand ).autocomplete
				({
					//$("#txtupdateselectItem_Kode").val('');
					//$("#txtupdateselectItem_Nama").val('');
					//$("#txtupdateselectKeterangan").val('');
					
					serviceUrl: '<?= base_url("PostalCode/GetPostalCodeSearch") ?>',	//controller tujuan misal satuan
					transformResult: function(response) 
					{
						var data = JSON.parse(response);
						//console.log(data.SatuanMenu);
						return {
							suggestions: $.map(data.ItemMenu, function(dataItem) 
							{	//mapping untuk tampilan ketika diketik (mungkin tampil kode - nama)
								//console.log(dataItem);
								return { value: dataItem.Kelurahan_Nama + ' - ' + dataItem.KodePos, KodePos_ID: dataItem.KodePos_ID, Kelurahan_ID: dataItem.Kelurahan_ID, Kelurahan_Nama: dataItem.Kelurahan_Nama };
							}),
						};
					},
					onSelect : function(suggestion){	//ketika dipilih
						$( "#txtselectKodePos_ID").val( suggestion.KodePos_ID );		//masukkan ke textbox
						$( "#txtselectKelurahan_ID" ).val( suggestion.Kelurahan_ID );		//masukkan ke textbox
						$( "#txtselectKelurahan_Nama" ).val( suggestion.Kelurahan_Nama );		//masukkan ke textbox
						$( "#txtselectKodePos" ).val( suggestion.KodePos );	//misal mau masuk ke 2 textbox (id dan nama)
					},
					onSearchComplete: function (query, suggestion) 
					{
						for( i=0 ; i<suggestion.length ; i++ )
						{
							var Kelurahan_Nama = suggestion[i].Kelurahan_Nama;
							
							if( Kelurahan_Nama != query )
							{
								$("#txtselectKodePos_ID").val('');
								$("#txtselectKelurahan_ID").val('');
								$("#txtselectKelurahan_Nama").val('');
								$("#txtselectKodePos").val('');
							}
						}
					}
				});
				
			}
		);

		function DeleteTempKaryawanDelete( Idx )
		{
			var table = document.getElementById('tableaddkaryawandetail');
			
			table.deleteRow( Idx );
		}
		
		// Add New Voucher
		/*
		$("#txtkaryawan").change
		(
			function()
			{
				var str = $("#txtkaryawan").val();
				
				var letterNumber = /^[0-9a-zA-Z ]+$/;
				if(	!str.match(letterNumber) )
				{
					$("#txtkaryawanname").val('');
					
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
		
		$("#btnaddnewkaryawan").click
		(
			function()
			{
				ResetForm();
				$("#previewaddnewkaryawan").modal('show');
			}
		);
		
		$("#btnsaveaddnewkaryawan").click
		(
			function()
			{						
				var Karyawan_Name 		= $("#txtKaryawan_Name").val();
				var Perusahaan_ID 		= $("#cbperusahaan option:selected").val();
				var Karyawan_UserName 	= $("#txtKaryawan_UserName").val();
				var Karyawan_Pass 		= $("#txtKaryawan_Pass").val();
				var Karyawan_No 		= $("#txtKaryawan_No").val();
				var Karyawan_Phone 		= $("#txtKaryawan_Phone").val();
				var Karyawan_Email 		= $("#txtKaryawan_Email").val();
				var Karyawan_Birthdate 	= $("#txtKaryawan_Birthdate").val();
				
				var BD = Karyawan_Birthdate.split('/');
				var dd = BD[0];
				var mm = BD[1];
				var yyyy = BD[2];
				
				Karyawan_Birthdate = yyyy + '-' + mm + '-' + dd;
		
				var lKaryawan1 = $(".txtkaryawandetailRecip_Name").length;
	
				if( lKaryawan1 == 0)
				{
					var msg = 'Harap mengisi semua Detail Karyawan.';
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
					
					for( i=0 ; i<lKaryawan1 ; i++ )
					{
						var KaryawanVA_nama	= $(".txtselectKodePos_ID").eq(i).val();
						
						if( KaryawanVA_nama == '')
						{
							numerror = 1;
						}
							
					}
					
					if( numerror == 1 )
					{
						var msg = 'Harap mengisi semua Detail Karyawan.';
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
						var arrKaryawan1 = [];
						
						for( i=0 ; i<lKaryawan1 ; i++ )
						{
							var Recip_Name 				= $(".txtkaryawandetailRecip_Name").eq(i).val();
							var Recip_Address 			= $(".txtkaryawandetailRecip_Address").eq(i).val();
							var Recip_Phone 			= $(".chkaryawandetailfRecip_Phone").eq(i).val();
							
							var IsDefaultShipAddress 	= $(".rbkaryawandetailfIsDefaultShipAddress").eq(i).prop('checked');
							if( IsDefaultShipAddress == true )	{ 	IsDefaultShipAddress = 1;	}
							else								{ 	IsDefaultShipAddress = 0;	}
							
							var KodePos_ID 				= $("#txtselectKodePos_ID").val();
							var KodePosDetail_ID 		= $("#txtselectKelurahan_ID").val();
							var Recip_Kelurahan 		= $("#txtselectKelurahan_Nama").val();
							var Recip_KodePos 			= $("#txtselectKodePos").val();
							
							arrKaryawan1.push( { 	Recip_Name : Recip_Name, Recip_Address : Recip_Address, Recip_Phone: Recip_Phone,
													IsDefaultShipAddress : IsDefaultShipAddress,
													KodePos_ID: KodePos_ID, KodePosDetail_ID: KodePosDetail_ID, 
													Recip_Kelurahan: Recip_Kelurahan, Recip_KodePos: Recip_KodePos } );
						}
alert( JSON.stringify( arrKaryawan1 ) );
						$.ajax(
						{
							type: 'POST',    
							url: "<?= base_url('Karyawan/SaveAddNewKaryawan') ?>",
							data: {	
									Karyawan_Name 		: Karyawan_Name,
									Karyawan_No			: Karyawan_No,
									Perusahaan_ID 		: Perusahaan_ID,
									Karyawan_UserName 	: Karyawan_UserName,
									Karyawan_Pass 		: Karyawan_Pass,
									Karyawan_Phone 		: Karyawan_Phone,
									Karyawan_Email 		: Karyawan_Email,
									Karyawan_Birthdate 	: Karyawan_Birthdate,

									arrKaryawan1 : arrKaryawan1
									},
							success: function( response )
							{
								if(response == 1)
								{
									var msg = 'Karyawan berhasil ditambah.';
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
									
									$("#previewaddnewkaryawan").modal('hide');
									
									GetKaryawanMenu();
									
									ResetForm();
								}
								else if(response == 2)
								{
									var msg = 'Kode Karyawan sudah ada.';
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
		//$("#txtkaryawan").val('');
		$("#txtkode_karyawan").val('');
		$("#txtnama_karyawan").val('');
		$("#chkaryawan_Status").val('');
		$("#txtkaryawan_alamat").val('');
		$("#txtkaryawan_lat").val('');
		$("#txtkaryawan_lon").val('');

		$("#tableaddkaryawandetail").html('');
		$("#tablekaryawandetailmenu > tbody").html('');
		
		//$("#txtupdatekaryawan").val('');
		$("#txtupdatekode_karyawan").val('');
		$("#txtupdatenama_karyawan").val('');
		$("#chupdatekaryawan_Status").val('');
		$("#txtupdatekaryawan_alamat").val('');
		$("#txtupdatekaryawan_lat").val('');
		$("#txtupdatekaryawan_lon").val('');

		$("#tableupdateaddkaryawandetail").html('');
		$("#tableupdatekaryawandetailmenu > tbody").html('');
	}
</script>