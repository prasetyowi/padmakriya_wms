<script type="text/javascript">	
	var KategoriCode = '';

	$(document).ready
	(
		function() 
		{
			GetKategoriMenu();
		}
	);
	
	function GetKategoriMenu()
	{
		$.ajax(
		{
			type: 'POST',    
			url: "<?= base_url('Kategori/GetKategoriMenu') ?>",
			//data: "Location="+ Location,
			success: function( response )
			{
				if(response)
				{
					ChKategoriMenu( response );
				}
			}
		});	
	}
		
	//var DTABLE;
	
	function ChKategoriMenu( JSONKategori )
	{
		$("#tablekategori1menu > tbody").html('');
		$("#tablekategori2menu > tbody").html('');
		$("#tablekategori3menu > tbody").html('');
		$("#tablekategori4menu > tbody").html('');
		
		var Kategori = JSON.parse( JSONKategori );
		
		var StatusC = Kategori.AuthorityMenu[0].StatusC;
		var StatusU = Kategori.AuthorityMenu[0].StatusU;
		var StatusD = Kategori.AuthorityMenu[0].StatusD;
		
		if( StatusC == 0 )	{	$("#btnaddnewkategori").attr('style','display: none;');	}

		$("#cbkategori_level").html( '' );
		$("#updatecbkategori_level").html( '' );
		if( Kategori.SubKategoriMenu != 0 )
		{
			for( i=0 ; i< Kategori.SubKategoriMenu.length ; i++ )
			{
				var sub_kategori_nama = Kategori.SubKategoriMenu[i].sub_kategori_nama;
				var sub_kategori_nilai = Kategori.SubKategoriMenu[i].sub_kategori_nilai;
				
				var str = '';
				str = str + '<option value="'+ sub_kategori_nilai +'">'+ sub_kategori_nama +'</option>';
				
				$("#cbkategori_level").append( str );
				$("#updatecbkategori_level").append( str );
			}
		}
		
		$("#cbkelas_id").html( '' );
		$("#updatecbkelas_id").html( '' );
		if( Kategori.KelasMenu != 0 )
		{
			for( i=0 ; i< Kategori.KelasMenu.length ; i++ )
			{
				var kelas_id 	= Kategori.KelasMenu[i].kelas_id;
				var kelas_kode 	= Kategori.KelasMenu[i].kelas_kode;
				var kelas_nama 	= Kategori.KelasMenu[i].kelas_nama;
				var kelas_warna = Kategori.KelasMenu[i].kelas_warna;
				
				var rgb = hexToRGB( kelas_warna );
				
				var st = '';
				
				if( rgb[0].r + rgb[0].g + rgb[0].b < 500 )
				{
					st = 'color: white;';
				}
				
				var str = '';
				str = str + '<option data-warna="'+ kelas_warna +'" value="'+ kelas_id +'" style="background-color: '+ kelas_warna +'; '+ st +';">'+ kelas_nama +'</option>';
				
				$("#cbkelas_id").append( str );
				$("#updatecbkelas_id").append( str );
			}
		}
		
		if( Kategori.Kategori1Menu != 0 )
		{
			if( $.fn.DataTable.isDataTable('#tablekategorimenu') ) 
			{
				$('#tablekategorimenu').DataTable().destroy();
			}

			$('#tablekategorimenu tbody').empty();
			
			for( i=0 ; i<Kategori.Kategori1Menu.length ; i++)
			{	
				var kategori_id			= Kategori.Kategori1Menu[i].kategori_id;
				var kategori_kode 		= Kategori.Kategori1Menu[i].kategori_kode;
				var kategori_nama 		= Kategori.Kategori1Menu[i].kategori_nama;
				var kategori_level 		= Kategori.Kategori1Menu[i].kategori_level;
				var kategori_reffid 	= Kategori.Kategori1Menu[i].kategori_reffid;
				var kategori_is_aktif	= Kategori.Kategori1Menu[i].kategori_is_aktif;
			
				var str = '';

				str += '<tr class="katnama" data-kategori_nama="'+ kategori_nama +'" data-kategori_kode="'+ kategori_kode +'" data-group="0">';
				str += '	<td width="20%">'+ kategori_kode +'</td>';
				str += '	<td width="40%">'+ kategori_nama +'</td>';
				str += '	<td width="40%"><span>';
				str += '		<button style="width: 80px;" class="btn btn-primary btnviewumenu form-control" onclick="ViewSKUMenu(\''+ kategori_id +'\',\''+ kategori_nama +'\', 1 )"><i class="fa fa-eye"></i> SKU</button>';
				str += '		<button style="width: 40px;" class="btn btn-primary btnviewumenu form-control" onclick="ViewKategoriMenu(\''+ kategori_id +'\',\''+ kategori_kode +'\', \''+ kategori_nama +'\', 1)"><i class="fa fa-eye"></i></button>';
				str += '		<button style="width: 40px;" class="btn btn-warning btnviewumenu form-control" onclick="UpdateKategoriMenu(\''+ kategori_id +'\',\''+ kategori_kode +'\',\''+ kategori_nama +'\',\''+ kategori_level +'\',\''+ kategori_reffid +'\',\''+ kategori_is_aktif +'\')"><i class="fa fa-pencil"></i></button>';
				str += '		<button style="width: 40px;" class="btn btn-danger btnviewumenu form-control" onclick="DeleteKategoriMenu(\''+ kategori_id +'\',\''+ kategori_nama +'\',\''+ kategori_level +'\')"><i class="fa fa-times"></i></button>';
				str += '	</span></td>';
				str += '</tr>';
				
				$("#tablekategori1menu tbody").append(str);
			}
		}
		
		$("#cbkelas_id").trigger('change');
	}
	
	function ViewKategoriMenu( kategori_id, kategori_kode, kategori_nama, kategori_level )
	{
		var ltr = $('.katnama').length;
		
		$(".katnama").css('background-color','transparent');
		
		for( i=0 ; i< ltr ; i++ )
		{
			var nama = $('.katnama').eq(i).attr('data-kategori_nama');
			
			if( kategori_nama == nama )
			{
				$(".katnama").eq(i).css('background-color','lightgreen');
			}
		}
	
		var kategori_id = kategori_id;
		var kategori_level = kategori_level;
		
		$.ajax(
		{
			type: 'POST',    
			url: "<?= base_url('Kategori/GetKategori') ?>",
			data: {
					kategori_id 	: kategori_id,
					kategori_level 	: kategori_level
					},
			success: function( response )
			{
				ChKategori( response, kategori_level );
			}
		});
		
		ViewSKUMenu( kategori_id, kategori_nama, kategori_level); 
	}
	
	$("#cbkelas_id").change
	(
		function()
		{
			var warna = $("#cbkelas_id option:selected").data('warna');
			
			$("#divwarna").removeAttr('style');
			$("#divwarna").attr('style','min-width: 50px; min-height: 30px; background-color: '+ warna +';');
		}
	);
	
	function ChKategori( JSONKategori, kategori_level ) // Level : 1 = Sub Kategori, 2 = Jenis, 3 = Sub Jenis
	{
		kategori_level_plus = parseInt( kategori_level )+1;
		
		var Kategori = JSON.parse( JSONKategori );

		if( kategori_level == 1 )		
		{	
			$("#tablekategori2menu tbody").html('');	
			$("#tablekategori3menu tbody").html('');	
			$("#tablekategori4menu tbody").html('');	
		}
		else if( kategori_level == 2 )	
		{	
			$("#tablekategori3menu tbody").html('');	
			$("#tablekategori4menu tbody").html('');
		}
		else if( kategori_level == 3 )	
		{	
			$("#tablekategori4menu tbody").html('');	
		}

		if( kategori_level != 4 )
		{
			if( Kategori.KategoriMenu != 0 )
			{
				for( i=0 ; i< Kategori.KategoriMenu.length ; i++ )
				{	
					var kategori_id 		= Kategori.KategoriMenu[i].kategori_id;
					var kategori_kode 		= Kategori.KategoriMenu[i].kategori_kode;
					var kategori_nama 		= Kategori.KategoriMenu[i].kategori_nama;
					var kategori_level 		= Kategori.KategoriMenu[i].kategori_level;
					var kategori_reffid 	= Kategori.KategoriMenu[i].kategori_reffid;
					var kategori_is_aktif	= Kategori.KategoriMenu[i].kategori_is_aktif;
					
					var str = '';
					str += '<tr class="katnama" data-kategori_nama="'+ kategori_nama +'" data-kategori_kode="'+ kategori_kode +'" data-group="'+ kategori_level_plus +'">';
					str += '	<td>'+ kategori_kode +'</td>';
					str += '	<td>'+ kategori_nama +'</td>';
					str += '	<td><span>';
					str += '		<button style="width: 80px;" class="btn btn-primary btnviewumenu form-control" onclick="ViewSKUMenu(\''+ kategori_id +'\',\''+ kategori_nama +'\', \''+ kategori_level_plus +'\')"><i class="fa fa-eye"></i> SKU</button>';
					str += '		<button style="width: 40px;" class="btn btn-primary btnviewumenu form-control" onclick="ViewKategoriMenu(\''+ kategori_id +'\',\''+ kategori_kode +'\', \''+ kategori_nama +'\', '+ kategori_level_plus +')"><i class="fa fa-eye"></i></button>';
					str += '		<button style="width: 40px;" class="btn btn-warning btnviewumenu form-control" onclick="UpdateKategoriMenu(\''+ kategori_id +'\',\''+ kategori_kode +'\',\''+ kategori_nama +'\',\''+ kategori_level +'\',\''+ kategori_reffid +'\',\''+ kategori_is_aktif +'\')"><i class="fa fa-pencil"></i></button>';
					str += '		<button style="width: 40px;" class="btn btn-danger btnviewumenu form-control" onclick="DeleteKategoriMenu(\''+ kategori_id +'\',\''+ kategori_nama +'\',\''+ kategori_level +'\')"><i class="fa fa-times"></i></button>';
					
					str += '	</span></td>';
					str += '</tr>';
					
					if( kategori_level == 1 )		{	$("#tablekategori2menu tbody").append(str);		}
					else if( kategori_level == 2 )	{	$("#tablekategori3menu tbody").append(str);		}
					else if( kategori_level == 3 )	{	$("#tablekategori4menu tbody").append(str);		}
				}
			}
		}
		
		//if( $("#txtcari").val() != '' )
		//{
		//	$("#txtcari").trigger('change');
		//}
	}
	
	function ViewSKUMenu( kategori_id, kategori_nama, kategori_level )
	{
		var ltr = $('.katnama').length;
		
		$(".katnama").css('background-color','transparent');
		
		for( i=0 ; i< ltr ; i++ )
		{
			var nama = $('.katnama').eq(i).attr('data-kategori_nama');
			
			if( kategori_nama == nama )
			{
				$(".katnama").eq(i).css('background-color','lightgreen');
			}
		}
		
		$.ajax(
		{
			type: 'POST',    
			url: "<?= base_url('Kategori/GetSKUByKategori') ?>",
			data: {
					kategori_id : kategori_id,
					kategori_level : kategori_level
					},
			success: function( response )
			{
				ChSKU( response, kategori_nama, kategori_level );
			}
		});
	}
	
	function ChSKU( JSONSKU, kategori_nama, kategori_level )
	{
		var SKU = JSON.parse( JSONSKU )
		
		$("#tableskumenu tbody").html('');
		
		var str = '';
		
		if( kategori_level == '1' )			{	str = 'Kategori';		}
		else if( kategori_level == '2' )	{	str = 'Sub Kategori';	}
		else if( kategori_level == '3' )	{	str = 'Jenis';			}
		else if( kategori_level == '4' )	{	str = 'Sub Jenis';		}
		else if( kategori_level == '9' )	{	str = 'Kategori';		}
		else if( kategori_level == '10' )	{	str = 'Brand';			}
		
		$("#lbketsku").html( str + ' : ' + kategori_nama );
		
		if( SKU.SKUMenu != 0 )
		{
			for( i=0 ; i< SKU.SKUMenu.length; i++ )
			{
				var sku_kode 		= SKU.SKUMenu[i].sku_kode;
				var sku_nama_produk = SKU.SKUMenu[i].sku_nama_produk;
	
				var str = '';

				str += '<tr>';
				str += '	<td>'+ sku_kode +'</td>';
				str += '	<td>'+ sku_nama_produk +'</td>';
				str += '</tr>';
				
				$("#tableskumenu tbody").append( str );
			}
		}
	}
	
	$("#btncari").click
	(
		function()
		{ 
			var cari = $("#txtcari").val();
			var levelgroup = $("#cbPilihGroup option:selected").val();
			
			if( levelgroup == '' )
			{
				var msg = 'Harap Pilih Group.';
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
				
				return false;
			}
			
			var ltr = $(".katnama").length;
			
			for( i=0 ; i<ltr ; i++ )
			{
				var group = $(".katnama").eq(i).attr('data-group');
				
				if( group == levelgroup )
				{
					$(".katnama").eq(i).css('display','table-row');
					$(".katnama").eq(i).css('display','none');
				}
			}
			
			if( cari == '' )
			{
				for( i=0 ; i< ltr ; i++ )
				{
					//var nama = $(".katnama").eq(i).attr('data-kategori_nama');
					//var kode = $(".katnama").eq(i).attr('data-kategori_kode');
					var group = $(".katnama").eq(i).attr('data-group');
					
					if( group == levelgroup ) 
					{
						$(".katnama").eq(i).css('display','table-row');
					}
				}
			}
			else
			{
				for( i=0 ; i< ltr ; i++ )
				{
					var nama = $(".katnama").eq(i).attr('data-kategori_nama');
					var kode = $(".katnama").eq(i).attr('data-kategori_kode');
					var group = $(".katnama").eq(i).attr('data-group');
					
					if( (nama.toLowerCase()).indexOf(cari.toLowerCase()) > -1 || (nama.toLowerCase()).indexOf(cari.toLowerCase()) > -1 ) 
					{
						if( group == levelgroup )
						{
							$(".katnama").eq(i).css('display','table-row');
						}
					}
				}
			}
			
		}
	);
	
	$("#btnback").click
	(
		function()
		{
			GetKategoriMenu();
		}
	);

	<?php
	if($Menu_Access["D"] == 1)
	{
	?> 
		$("#btnnodeletekategori").click
		(
			function()
			{
				GetKategoriMenu();	
			}
		);
	
		
		// Delete Kategori
		function DeleteKategoriMenu( kategori_id, kategori_nama, kategori_level )
		{
			$("#lbdeletekategori_nama").html( kategori_nama );
			$("#hddeletekategori_level").val( kategori_level );
			$("#hddeletekategori_id").val( kategori_id );
			
			$("#previewdeletekategori").modal('show');
		}
		
		$("#btnyesdeletekategori").click
		(
			function()
			{
				var kategori_level = $("#hddeletekategori_level").val();
				
				var str = '';
				if( kategori_level == 0 )		{	str = 'Kategori';	} 
				else if( kategori_level == 1 )	{	str = 'Sub Kategori';	} 
				else if( kategori_level == 2 )	{	str = 'Jenis';	} 
				else if( kategori_level == 3 )	{	str = 'Sub Jenis';	} 
				else if( kategori_level == 9 )	{	str = 'Kategori';	} 
				else if( kategori_level == 10 ){	str = 'Brand';	} 
				
				var kategori_id = $("#hddeletekategori_id").val();

				$.ajax(
				{
					type: 'POST',    
					url: "<?= base_url('Kategori/DeleteKategoriMenu') ?>",
					data: {
							kategori_id : kategori_id,
							kategori_level: kategori_level
							},
					success: function( response )
					{
						if(response == 1)
						{
							var msg = str + ' berhasil dihapus.';
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
						else
						{
							if( response == 2 )	{	var ErrMsg = 'Kategori tidak bisa dihapus karena sedang dipakai.';	}
							else				{	var ErrMsg = response.split('$$$');
														ErrMsg = ErrMsg[1];				}
							
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
						
						GetKategoriMenu();
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
		$("#updatebtnback").click
		(
			function()
			{
				GetKategoriMenu();	
			}
		);
	
		$("#updatecbkelas_id").change
		(
			function()
			{
				var warna = $("#updatecbkelas_id option:selected").data('warna');
				
				$("#updatedivwarna").removeAttr('style');
				$("#updatedivwarna").attr('style','min-width: 50px; min-height: 30px; background-color: '+ warna +';');
			}
		);
		
		$("#updatecbkategori_level").change
		(
			function()
			{
				var kategori_level = $("#updatecbkategori_level option:selected").val();
				
				if( kategori_level == 0 || kategori_level == 4 )
				{
					$("#updatesubkategori").html('');
				}
				else
				{
					if		( kategori_level == 1 )	{	var SubKategori = 0; }
					else if	( kategori_level == 2 )	{	var SubKategori = 1; }
					else if	( kategori_level == 3 )	{	var SubKategori = 2; }
					
					$.ajax(
					{
						type: 'POST',    
						url: "<?= base_url('Kategori/GetSubKategori') ?>",
						data: 
						{	
							SubKategori : SubKategori
						},
						success: function( response )
						{
							ChUpdateSubKategori( response );
						}
					});
				}
			}
		);
		
		function ChUpdateSubKategori( JSONSubKategori )
		{
			var SubKategori = JSON.parse( JSONSubKategori )
			
			$("#updatesubkategori").html('');
			
			$("#updatesubkategori").append( '<select id="updatecbkategori_reffid" class="form-control" style="diaplay: none;"></select>');
		
			for( i=0 ; i< SubKategori.SubKategoriMenu.length ; i++ )
			{
				var kategori_id = SubKategori.SubKategoriMenu[i].kategori_id;
				var kategori_nama = SubKategori.SubKategoriMenu[i].kategori_nama;
			
				$("#updatecbkategori_reffid").append( '<option value="'+ kategori_id +'">'+ kategori_nama +'</option>' );
			}
			
			$("#updatecbkategori_reffid").removeAttr('style');
		}
		
		function UpdateKategoriMenu( kategori_id, kategori_kode, kategori_nama, kategori_level, kategori_reffid, kategori_is_aktif, kelas_id )
		{
			if		( kategori_level == 1 ){	var SubKategori = 0; }
			else if	( kategori_level == 2 ){	var SubKategori = 1; }
			else if	( kategori_level == 3 ){	var SubKategori = 2; }
			else 							{	var SubKategori = kategori_level; }
			
			$("#updatetxtkategori_id").val( kategori_id );
			$("#updatetxtkategori_kode").val( kategori_kode );
			$("#updatetxtkategori_nama").val( kategori_nama );
			if( kategori_is_aktif == 1 ){	$("#updatechkategori_is_aktif").prop('checked', true); 	}
			else 						{	$("#updatechkategori_is_aktif").prop('checked', false); 	}
			
			$.ajax(
			{
				type: 'POST',    
				url: "<?= base_url('Kategori/GetUpdateKategoriMenu') ?>",
				data: {	
						kategori_id : kategori_id,
						SubKategori : SubKategori
						},
				success: function( response )
				{
					ChUpdateKategoriMenu( response, kategori_level, kategori_reffid, kelas_id );
				}
			});
			
		}

		function ChUpdateKategoriMenu( JSONkategori, kategori_level, kategori_reffid, kelas_id )
		{
			var kategori = JSON.parse( JSONkategori );
			
			$("#txtupdatekategoriid").val( kategori.kategoriMenu[0].kategoriID );
			$("#txtupdatekategoricode").val( kategori.kategoriMenu[0].kategori_Kode );
			$("#txtupdatekategoriname").val( kategori.kategoriMenu[0].kategori_Nama );
			
			$("#updatecbkelas_id").val( kategori.kategoriMenu[0].kelas_id );
			
			$("#updatecbkelas_id").trigger('change');
			
			$("#updatecbkategori_level").val( kategori.kategoriMenu[0].kategori_level );
			
			if( kategori.kategoriMenu[0].kategori_level != 0 && kategori.kategoriMenu[0].kategori_level != 9 )
			{
				var nm = '';

				if( kategori.kategoriMenu[0].kategori_level == 1 )		{	nm = 'Sub Kategori';	}
				else if( kategori.kategoriMenu[0].kategori_level == 2 )	{	nm = 'Jenis';			}
				else if( kategori.kategoriMenu[0].kategori_level == 3 )	{	nm = 'Sub Jenis';		}
				else if( kategori.kategoriMenu[0].kategori_level == 10 ){	nm = 'Kategori';		}
				
				$("#updatesubkategori").html('');
			
				$("#updatesubkategori").append( '<select id="updatecbkategori_reffid" class="form-control" style="diaplay: none;"></select>');
				
				$("#updatecbkategori_reffid").append('<option value="">-- Pilih '+ nm +' --</option>');
				for( i=0 ; i< kategori.SubKategoriMenu.length ; i++ )
				{
					
					var kategori_id 	= kategori.SubKategoriMenu[i].kategori_id;
					var kategori_nama 	= kategori.SubKategoriMenu[i].kategori_nama;
				
					$("#updatecbkategori_reffid").append( '<option value="'+ kategori_id +'">'+ kategori_nama +'</option>' );
				}
				
				$("#updatesubkategori").removeAttr('style');
				
				$("#updatecbkategori_reffid").val( kategori_reffid );
				
				//$("#cbupdatekategori_reffid").attr('disabled', true );
			}
			else
			{
				$("#updatesubkategori").attr('style','display: none;');
			}
			
			$("#previewupdatekategori").modal('show');
		}
		
		$("#btnsaveupdatenewkategori").click
		(
			function()
			{
				var kategori_id = $("#updatetxtkategori_id").val();
				var kategori_kode = $("#updatetxtkategori_kode").val();
				var kategori_nama = $("#updatetxtkategori_nama").val();
				var GroupKategori = $("#updatecbkategori_level option:selected").html();
				var kategori_level = $("#updatecbkategori_level option:selected").val();
				var kategori_reffid = $("#updatecbkategori_reffid option:selected").val();
				
				var kelas_id = $("#updatecbkelas_id option:selected").val();
				
				if( kategori_level == 0 ){ kategori_reffid = null; }
				
				var kategori_is_aktif = $("#updatechkategori_is_aktif").prop('checked');
				if( kategori_is_aktif == true )			{	kategori_is_aktif = 1; }
				else if( kategori_is_aktif == false )	{	kategori_is_aktif = 0; }

				$.ajax(
				{
					type: 'POST',    
					url: "<?= base_url('Kategori/SaveUpdateKategori') ?>",
					data: {	
							kategori_id 		: kategori_id,
							kategori_kode 		: kategori_kode,
							kategori_nama 		: kategori_nama,
							
							GroupKategori 		: GroupKategori,
							
							kategori_level 		: kategori_level,
							kategori_reffid 	: kategori_reffid,
							kategori_is_aktif 	: kategori_is_aktif,
							
							kelas_id : kelas_id
							
							},
					success: function( response )
					{
						if(response == 1)
						{
							var str = '';
							
							if( kategori_level == 0 )		{	str = 'Kategori';		}
							else if( kategori_level == 1 )	{	str = 'Sub Kategori';	}
							else if( kategori_level == 2 )	{	str = 'Jenis';			}
							else if( kategori_level == 3 )	{	str = 'Sub Jenis';		}
							
							var msg = str + ' berhasil diubah.';
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
							
							$("#previewupdatekategori").modal('hide');
							
							GetKategoriMenu();
						}
						else
						{
							var msg = 'Gagal mengubah.';
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
	if($Menu_Access["C"] == 1)
	{
	?>
		$("#btnaddnewkategori").removeAttr('style');
		
		// Add New Kategori
		$("#btnaddnewkategori").click
		(
			function()
			{
				ResetForm();
				$("#previewaddnewkategori").modal('show');
			}
		);
			
		$("#cbkategori_level").change
		(
			function()
			{
				var kategori_level = $("#cbkategori_level option:selected").val();
			
				if( kategori_level == 0 )	
				{	
					$("#cbkelas_id").removeAttr('style');				
					$("#lbkelas_id").removeAttr('style');				
					$("#divwarna").removeAttr('style');	

					$("#cbkelas_id").trigger('change');					
			
				}
				else						
				{	
					$("#cbkelas_id").attr('style','display: none;');	
					$("#lbkelas_id").attr('style','display: none;');	
					$("#divwarna").attr('style','display: none;');	
				}
				
				if( kategori_level == 0 || kategori_level == 4 || kategori_level == 9 )
				{
					$("#subkategori").html('');
				}
				else
				{
					if		( kategori_level == 1 )	{	var SubKategori = 0; }
					else if	( kategori_level == 2 )	{	var SubKategori = 1; }
					else if	( kategori_level == 3 )	{	var SubKategori = 2; }
					
					$.ajax(
					{
						type: 'POST',    
						url: "<?= base_url('Kategori/GetSubKategori') ?>",
						data: 
						{	
							SubKategori : SubKategori
						},
						success: function( response )
						{
							//if(response == 1)
							//{	
								ChSubKategori( response, SubKategori );
								//$("#cbkategori_reffid").attr('style','display: none;');
							//}
							//else
							//{
							//	var msg = 'Kategori dari Grup Kategori tidak ditemukan.';
							//	var msgtype = 'error';
									
								//if (!window.__cfRLUnblockHandlers) return false;
							//	new PNotify
							//	({
							//		title: 'Error',
							//		text: msg,
							//		type: msgtype,
							//		styling: 'bootstrap3',
							//		delay: 3000,
							//		stack: stack_center
							//	});
							
							//}
						}
					});
				}
			}
		);
		
		
		function ChSubKategori( JSONSubKategori, Sub )
		{
		
			var SubKategori = JSON.parse( JSONSubKategori )
			
			$("#subkategori").html('');
			
			var str = '';
			
			if( Sub == '0' )		{ 	str = '<option value="">-- Pilih Kategori --</option>';		}
			else if( Sub == '1' )	{ 	str = '<option value="">-- Pilih Sub Kategori --</option>';	}
			else if( Sub == '2' )	{ 	str = '<option value="">-- Pilih Jenis --</option>';		}
			else if( Sub == '9' )	{ 	str = '<option value="">-- Pilih Kategori --</option>';	}
			
			$("#subkategori").append( '<select id="cbkategori_reffid" class="form-control" style="diaplay: none;"></select>');
			
			$("#cbkategori_reffid").append( str );
			
			for( i=0 ; i< SubKategori.SubKategoriMenu.length ; i++ )
			{
				var kategori_id 	= SubKategori.SubKategoriMenu[i].kategori_id;
				var kategori_nama 	= SubKategori.SubKategoriMenu[i].kategori_nama;
			
				$("#cbkategori_reffid").append( '<option value="'+ kategori_id +'">'+ kategori_nama +'</option>' );
			}
			
		}
		
		$("#btnsaveaddnewkategori").click
		(
			function()
			{	
				var kategori_kode 	= $("#txtkategori_kode").val();
				var kategori_nama 	= $("#txtkategori_nama").val();
				
				var GroupKategori = $("#cbkategori_level option:selected").html();
				
				var kategori_level = $("#cbkategori_level option:selected").val();
				var kategori_reffid = $("#cbkategori_reffid option:selected").val();
				
				var kelas_id		= $("#cbkelas_id option:selected").val();
				
				if( kategori_level == 0 )	{	kategori_reffid = ''; }
				else									
				{
					var str = '';
					
					if( kategori_level == 1 )		{	str = 'Sub Kategori';	}
					else if( kategori_level == 2 )	{	str = 'Jenis';			}
					else if( kategori_level == 3 )	{	str = 'Sub Jenis';		}
					
					if( kategori_reffid == '' )
					{
						var msg = str + ' harus dipilih.';
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
						
						return false;
					}
				}
				
				var kategori_is_aktif = $("#chkategori_is_aktif").prop('checked');
				
				if( kategori_is_aktif == true )			{	kategori_is_aktif = 1; }
				else if( kategori_is_aktif == false )	{	kategori_is_aktif = 0; }

				$.ajax(
				{
					type: 'POST',    
					url: "<?= base_url('Kategori/SaveAddNewKategori') ?>",
					data: {	
							kategori_kode : kategori_kode,
							kategori_nama : kategori_nama,
							GroupKategori : GroupKategori,
							kategori_level : kategori_level,
							kategori_reffid : kategori_reffid,
							kategori_is_aktif : kategori_is_aktif,
							kelas_id : kelas_id
							},
					success: function( response )
					{
						if(response == 1)
						{
							var str = '';
							
							if( kategori_level == 0 )		{	str = 'Kategori';		}
							else if( kategori_level == 1 )	{	str = 'Sub Kategori';	}
							else if( kategori_level == 2 )	{	str = 'Jenis';			}
							else if( kategori_level == 3 )	{	str = 'Sub Jenis';		}
							
							var msg = str + ' berhasil ditambah.';
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
							
							GetKategoriMenu();
				
							$("#previewaddnewkategori").modal('hide');
							ResetForm();
						}
						else
						{
							if( response == 2 ) 	{	var msg = 'Kode Kembar atau sudah ada.'; }
							else if( response == 3 ){	var msg = 'Nama Kembar atau sudah ada.'; }
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
			$("#updatetxtkategori_kode").val('');
			$("#updatetxtkategori_nama").val('');
		<?php
		}
		?>
		
		<?php
		if($Menu_Access["C"] == 1)
		{
		?>
			$("#txtkategori_kode").val('');
			$("#txtkategori_nama").val('');
		<?php
		}
		?>
		
	}
</script>