<script type="text/javascript">	
	var SKUIndukCode = '';
	var arrKategori1 = [];
	var arrKategori2 = [];
	var arrKategori3 = [];
	var arrKategori4 = [];
	var arrPrinciple = [];
	var arrPrincipleBrand = [];
	
	$(document).ready
	(
		function() 
		{
			GetSKUIndukMenu();
			
			$('#modal-form').on('hide', function(){
				Utils.clearFields();
			});
		}
	);
	
	
	function GetSKUIndukMenu()
	{
		$.ajax(
		{
			type: 'POST',    
			url: "<?= base_url('SKUInduk/GetSKUIndukMenu') ?>",
			//data: "Location="+ Location,
			success: function( response )
			{
				if(response)
				{
					ChSKUIndukMenu( response );
				}
			}
		});	
	}
		
	//var DTABLE;
	
	function ChSKUIndukMenu( JSONSKUInduk )
	{
		$("#tablesku_indukmenu > tbody").html('');
		
		var SKUInduk = JSON.parse( JSONSKUInduk );
		
		var StatusC = SKUInduk.AuthorityMenu[0].StatusC;
		var StatusU = SKUInduk.AuthorityMenu[0].StatusU;
		var StatusD = SKUInduk.AuthorityMenu[0].StatusD;
		
		if( StatusC == 0 )	{	$("#btnaddnewsku_induk").attr('style','display: none;');	}
		
		if( SKUInduk.SKUIndukMenu != 0 )
		{
					
			if( $.fn.DataTable.isDataTable('#tablesku_indukmenu') ) 
			{
				$('#tablesku_indukmenu').DataTable().destroy();
			}

			$('#tablesku_indukmenu tbody').empty();
			
			for( i=0 ; i<SKUInduk.SKUIndukMenu.length ; i++)
			{
				var sku_induk_id 			= SKUInduk.SKUIndukMenu[i].sku_induk_id;
				var sku_induk_kode 			= SKUInduk.SKUIndukMenu[i].sku_induk_kode;
				var sku_induk_nama 			= SKUInduk.SKUIndukMenu[i].sku_induk_nama;
				var sku_induk_keterangan 	= SKUInduk.SKUIndukMenu[i].sku_induk_keterangan;
				var sku_induk_nama_judul	= SKUInduk.SKUIndukMenu[i].sku_induk_nama_judul;
				var sku_induk_is_hadiah		= SKUInduk.SKUIndukMenu[i].sku_induk_is_hadiah == null || SKUInduk.SKUIndukMenu[i].sku_induk_is_hadiah == 0 ? "Tidak" : "Iya" ;
			
				var strmenu = '';
				
				var strU = '';
				var strD = '';
				
				<?php
				if($Menu_Access["U"] == 1)
				{
					?>
					strU = '<button style="width: 40px;" class="btn btn-warning btneditusku_indukmenu form-control" onclick="UpdateSKUIndukMenu(\''+ sku_induk_id +'\')"><i class="fa fa-pencil"></i></button>';
					<?php
				}
				if($Menu_Access["D"] == 1)
				{
					?>
					strD = '<button style="width: 40px;" class="btn btn-danger btndeletesku_indukmenu form-control" onclick="DeleteSKUIndukMenu(\''+ sku_induk_id +'\',\''+ sku_induk_nama +'\')"><i class="fa fa-times"></i></button>';
					<?php
				}
				?>
				
				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td>'+ sku_induk_kode +'</td>';
				strmenu = strmenu + '	<td>'+ sku_induk_nama +'</td>';
				strmenu = strmenu + '	<td>'+ sku_induk_keterangan +'</td>';
				strmenu = strmenu + '	<td>'+ sku_induk_nama_judul +'</td>';
				strmenu = strmenu + '	<td>'+ sku_induk_is_hadiah +'</td>';
				strmenu = strmenu + '	<td><span>';
				strmenu = strmenu + strU; 
				strmenu = strmenu + strD; 
				strmenu = strmenu + '	</span></td>';
				strmenu = strmenu + '</tr>';
				
				$("#tablesku_indukmenu > tbody").append( strmenu );
			}
		}

		arrKategori1 = [];
		arrKategori2 = [];
		arrKategori3 = [];
		arrKategori4 = [];
		arrPrinciple = [];
		arrPrincipleBrand = [];
		
		if( SKUInduk.Kategori1Menu != 0 )
		{
			for( i=0 ; i<SKUInduk.Kategori1Menu.length ; i++ )
			{
				var kategori1_id 		= SKUInduk.Kategori1Menu[i].kategori_id;
				var kategori1_kode 		= SKUInduk.Kategori1Menu[i].kategori_kode;
				var kategori1_nama 		= SKUInduk.Kategori1Menu[i].kategori_nama;
				var kategori1_reffid 	= SKUInduk.Kategori1Menu[i].kategori_reffid;
				
				arrKategori1.push( { kategori_id : kategori1_id, kategori_kode : kategori1_kode, kategori_nama : kategori1_nama, kategori_reffid: kategori1_reffid } );
			}
		}
		
		if( SKUInduk.Kategori2Menu != 0 )
		{
			for( i=0 ; i<SKUInduk.Kategori2Menu.length ; i++ )
			{
				var kategori2_id 		= SKUInduk.Kategori2Menu[i].kategori_id;
				var kategori2_kode 		= SKUInduk.Kategori2Menu[i].kategori_kode;
				var kategori2_nama 		= SKUInduk.Kategori2Menu[i].kategori_nama;
				var kategori2_reffid 	= SKUInduk.Kategori2Menu[i].kategori_reffid;
				
				arrKategori2.push( { kategori_id : kategori2_id, kategori_kode : kategori2_kode, kategori_nama : kategori2_nama, kategori_reffid: kategori2_reffid } );
			}
		}
				
		if( SKUInduk.Kategori3Menu != 0 )
		{
			for( i=0 ; i<SKUInduk.Kategori3Menu.length ; i++ )
			{
				var kategori3_id 		= SKUInduk.Kategori3Menu[i].kategori_id;
				var kategori3_kode 		= SKUInduk.Kategori3Menu[i].kategori_kode;
				var kategori3_nama 		= SKUInduk.Kategori3Menu[i].kategori_nama;
				var kategori3_reffid 	= SKUInduk.Kategori3Menu[i].kategori_reffid;
				
				arrKategori3.push( { kategori_id : kategori3_id, kategori_kode : kategori3_kode, kategori_nama : kategori3_nama, kategori_reffid: kategori3_reffid } );
			}
		}		
		
		if( SKUInduk.Kategori4Menu != 0 )
		{
			for( i=0 ; i<SKUInduk.Kategori4Menu.length ; i++ )
			{
				var kategori4_id 		= SKUInduk.Kategori4Menu[i].kategori_id;
				var kategori4_kode 		= SKUInduk.Kategori4Menu[i].kategori_kode;
				var kategori4_nama 		= SKUInduk.Kategori4Menu[i].kategori_nama;
				var kategori4_reffid 	= SKUInduk.Kategori4Menu[i].kategori_reffid;
				
				arrKategori4.push( { kategori_id : kategori4_id, kategori_kode : kategori4_kode, kategori_nama : kategori4_nama, kategori_reffid: kategori4_reffid } );
			}
		}	
		
		if( SKUInduk.PrincipleMenu != 0 )
		{
			for( i=0 ; i<SKUInduk.PrincipleMenu.length ; i++ )
			{
				var principle_id = SKUInduk.PrincipleMenu[i].principle_id;
				var principle_kode = SKUInduk.PrincipleMenu[i].principle_kode;
				var principle_nama = SKUInduk.PrincipleMenu[i].principle_nama;
				
				arrPrinciple.push( { principle_id : principle_id, principle_kode : principle_kode, principle_nama : principle_nama } );
			}
		}
		
		if( SKUInduk.PrincipleBrandMenu != 0 )
		{
			for( i=0 ; i<SKUInduk.PrincipleBrandMenu.length ; i++ )
			{
				var principle_id 			= SKUInduk.PrincipleBrandMenu[i].principle_id;
				var principle_brand_id 		= SKUInduk.PrincipleBrandMenu[i].principle_brand_id;
				var principle_brand_nama 	= SKUInduk.PrincipleBrandMenu[i].principle_brand_nama;
				
				arrPrincipleBrand.push( { principle_id : principle_id, principle_brand_id : principle_brand_id, principle_brand_nama : principle_brand_nama } );
			}
		}
		
		$("#tablevarianwajib > tbody").html('');
		$("#updatetablevarianwajib > tbody").html('');
		if( SKUInduk.VarianWajibMenu != 0 )
		{
			for( i=0 ; i<SKUInduk.VarianWajibMenu.length ; i++)
			{
				var varian_id 	= SKUInduk.VarianWajibMenu[i].varian_id;
				var varian_kode = SKUInduk.VarianWajibMenu[i].varian_kode;
				var varian_nama = SKUInduk.VarianWajibMenu[i].varian_nama;
				
				var str = '';
				
				str = str + '<tr>';
				str = str + '	<td width="80%"><label for="chpilihvarianwajib_'+ i +'">'+ varian_nama +'</label></td>';
				str = str + '	<td width="20%"><input type="checkbox" id="chpilihvarianwajib_'+ i +'" class="chpilihvarianwajib checkbox_form" style="padding: 0px;" data-iswajib="1" data-varian_id="'+ varian_id +'"checked="checked" disabled="disabled" /></td>';
				str = str + '</tr>';
				
				$("#tablevarianwajib > tbody").append( str );
				
				var str = '';
				
				str = str + '<tr>';
				str = str + '	<td width="80%"><label for="updatechpilihvarianwajib_'+ i +'">'+ varian_nama +'</td>';
				str = str + '	<td width="20%"><input type="checkbox" id="updatechpilihvarianwajib_'+ i +'" class="updatechpilihvarianwajib checkbox_form" style="padding: 0px;" data-iswajib="1" data-varian_id="'+ varian_id +'"checked="checked" disabled="disabled" /></td>';
				str = str + '</tr>';
				
				$("#updatetablevarianwajib > tbody").append( str );
			}
		}
	
		$("#tablevariannonwajib > tbody").html('');
		$("#updatetablevariannonwajib > tbody").html('');
		if( SKUInduk.VarianNonWajibMenu != 0 )
		{
			for( i=0 ; i<SKUInduk.VarianNonWajibMenu.length ; i++)
			{
				var varian_id 	= SKUInduk.VarianNonWajibMenu[i].varian_id;
				var varian_kode = SKUInduk.VarianNonWajibMenu[i].varian_kode;
				var varian_nama = SKUInduk.VarianNonWajibMenu[i].varian_nama;
				
				var str = '';
				
				str = str + '<tr>';
				str = str + '	<td width="80%"><label for="chpilihvariannonwajib_'+ i +'">'+ varian_nama +'</label></td>';
				str = str + '	<td width="20%"><input type="checkbox" id="chpilihvariannonwajib_'+ i +'" class="chpilihvariannonwajib checkbox_form" style="padding: 0px;" data-iswajib="0" data-varian_id="'+ varian_id +'" /></td>';
				str = str + '</tr>';
				
				$("#tablevariannonwajib > tbody").append( str );
				
				var str = '';
				
				str = str + '<tr>';
				str = str + '	<td width="80%"><label for="updatechpilihvariannonwajib_'+ i +'">'+ varian_nama +'</label></td>';
				str = str + '	<td width="20%"><input type="checkbox" id="updatechpilihvariannonwajib_'+ i +'" class="updatechpilihvariannonwajib checkbox_form" style="padding: 0px;" data-iswajib="0" data-varian_id="'+ varian_id +'" /></td>';
				str = str + '</tr>';
				
				$("#updatetablevariannonwajib > tbody").append( str );
			}
		}
	
		$('#tablesku_indukmenu').DataTable(
		{
			retrieve: true,
			"dom": '<"left"f>rtp'
		});
	}
	
	$("#txtsku_induk_keterangan").keyup
	(
		function( event )
		{
			var lket = $("#txtsku_induk_keterangan").val().length;
			lket = 8000 - lket;
			
			if( lket == 0 )	{	lket = '<strong style="color: red;">'+ lket +'</strong>';	}
			else			{	lket = '<strong style="color: black;">'+ lket +'</strong>';	}
			
			$("#lbjumlahket").html( 'Sisa Karakter : '+ lket );
		}
	);
	
	$("#txtsku_induk_keterangan").keydown
	(
		function( event )
		{
			var lket = $("#txtsku_induk_keterangan").val().length;
			lket = 8000 - lket;
			
			if( lket == 0 )	{	lket = '<strong style="color: red;">'+ lket +'</strong>';	}
			else			{	lket = '<strong style="color: black;">'+ lket +'</strong>';	}
			
			$("#lbjumlahket").html( 'Sisa Karakter : '+ lket );
		}
	);
	
	$("#btnback").click
	(
		function()
		{
			GetSKUIndukMenu();
		}
	);

	<?php
	if($Menu_Access["D"] == 1)
	{
	?> 
		$("#btnnodeletesku_induk").click
		(
			function()
			{
				GetSKUIndukMenu();	
			}
		);
	
		// Delete SKUInduk
		function DeleteSKUIndukMenu( sku_induk_id, sku_induk_nama )
		{
			$("#lbdeletesku_induk_nama").html( sku_induk_nama );
			$("#hddeletesku_induk_id").val( sku_induk_id );
			
			$("#previewdeletesku_induk").modal('show');
		}
		
		$("#btnyesdeletesku_induk").click
		(
			function()
			{
				var sku_induk_id = $("#hddeletesku_induk_id").val();
				$.ajax(
				{
					type: 'POST',    
					url: "<?= base_url('SKUInduk/DeleteSKUIndukMenu') ?>",
					data: {
							sku_induk_id : sku_induk_id 
							},
					success: function( response )
					{
						if(response == 1)
						{
							var msg = 'SKU Induk berhasil dihapus.';
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
							if( response == 2 )	{	var msg = 'Gagal menghapus karena SKU Induk sudah dipakai';	}
							else				{	var msg = response;											}
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
						
						GetSKUIndukMenu();
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
				GetSKUIndukMenu();	
			}
		);
	
		$("#updatetxtsku_induk_keterangan").keyup
		(
			function( event )
			{
				var lket = $("#updatetxtsku_induk_keterangan").val().length;
				lket = 8000 - lket;
				
				if( lket == 0 )	{	lket = '<strong style="color: red;">'+ lket +'</strong>';	}
				else			{	lket = '<strong style="color: black;">'+ lket +'</strong>';	}
				
				$("#updatelbjumlahket").html( 'Sisa Karakter : '+ lket );
			}
		);
		
		$("#updatetxtsku_induk_keterangan").keydown
		(
			function( event )
			{
				var lket = $("#updatetxtsku_induk_keterangan").val().length;
				lket = 8000 - lket;
				
				if( lket == 0 )	{	lket = '<strong style="color: red;">'+ lket +'</strong>';	}
				else			{	lket = '<strong style="color: black;">'+ lket +'</strong>';	}
				
				$("#updatelbjumlahket").html( 'Sisa Karakter : '+ lket );
			}
		);
		
		function UpdateSKUIndukMenu( sku_induk_id )
		{
			for( i=0 ; i<arrKategori1.length ; i++ )
			{
				var kategori_id 	= arrKategori1[i].kategori_id;
				var kategori_kode	= arrKategori1[i].kategori_kode;
				var kategori_nama	= arrKategori1[i].kategori_nama;
				
				$("#updatecbkategori1").append('<option value="'+ kategori_id +'">'+ kategori_nama +'</option>');
			}
				
			for( i=0 ; i<arrPrinciple.length ; i++ )
			{
				var principle_id 	= arrPrinciple[i].principle_id;
				var principle_kode	= arrPrinciple[i].principle_kode;
				var principle_nama	= arrPrinciple[i].principle_nama;
				
				$("#updatecbprinciple").append('<option value="'+ principle_id +'">'+ principle_nama +'</option>');
			}
				
			$.ajax(
			{
				type: 'POST',    
				url: "<?= base_url('SKUInduk/GetUpdateSKUIndukMenu') ?>",
				data: {
						sku_induk_id : sku_induk_id 
						},
				success: function( response )
				{
					ChUpdateSKUIndukMenu( response );
				}
			});	
		}
		
		function ChUpdateSKUIndukMenu( JSONSKUInduk )
		{
			var SKUInduk = JSON.parse( JSONSKUInduk );
			
			$("#txtupdatesku_induk_id").val( SKUInduk.SKUIndukMenu[0].sku_induk_id );
			$("#txtupdatesku_induk_kode").val( SKUInduk.SKUIndukMenu[0].sku_induk_kode );
			$("#txtupdatesku_induk_nama").val( SKUInduk.SKUIndukMenu[0].sku_induk_nama );
			$("#updatetxtsku_induk_keterangan").val( SKUInduk.SKUIndukMenu[0].sku_induk_keterangan );
			$("#txtupdatesku_induk_nama_judul").val( SKUInduk.SKUIndukMenu[0].sku_induk_nama_judul );
			
			$("#updatecbkategori2").html('');
			$("#updatecbkategori3").html('');
			$("#updatecbkategori4").html('');
			$("#updatecbprinciplebrand").html('');
			
			$("#updatecbkategori2").append('<option value="">--Pilih Sub Kategori--</option>');
			$("#updatecbkategori3").html('<option value="">--Pilih Jenis--</option>');
			$("#updatecbkategori4").html('<option value="">--Pilih Sub Jenis--</option>');
			
			$("#updatecbprinciplebrand").html('<option value="">--Pilih Brand--</option>');
				
			var kategori1_id 		= SKUInduk.SKUIndukMenu[0].kategori1_id;
			var kategori2_id 		= SKUInduk.SKUIndukMenu[0].kategori2_id;
			var kategori3_id 		= SKUInduk.SKUIndukMenu[0].kategori3_id;
			var kategori4_id 		= SKUInduk.SKUIndukMenu[0].kategori4_id;
			var principle_id 		= SKUInduk.SKUIndukMenu[0].principle_id;
			var principle_brand_id 	= SKUInduk.SKUIndukMenu[0].principle_brand_id;
			
			$("#updatekat2").attr('style','display: none;');
			$("#updatekat3").attr('style','display: none;');
			$("#updatekat4").attr('style','display: none;');
			
			$("#updatecbkategori1").val( kategori1_id );
			
			GetUpdateSKUKategori( 1, kategori1_id ); 
			GetUpdateSKUKategori( 2, kategori2_id ); 
			GetUpdateSKUKategori( 3, kategori3_id ); 
			
			if( kategori2_id != null )	{	$("#updatekat2").removeAttr('style'); $("#updatekat3").removeAttr('style'); $("#updatecbkategori2").val( kategori2_id );	}
			if( kategori3_id != null )	{	$("#updatekat3").removeAttr('style'); $("#updatekat4").removeAttr('style'); $("#updatecbkategori3").val( kategori3_id );	}
			if( kategori4_id != null )	{	$("#updatekat4").removeAttr('style'); $("#updatecbkategori4").val( kategori4_id );	}
			
			GetUpdateSKUBrand( principle_id );
				
			$("#updatecbprinciple").val( principle_id );
			
			$("#updatecbprinciplebrand").val( principle_brand_id );
			
			$("#updatecbsku_induk_jenis_ppn").val( SKUInduk.SKUIndukMenu[0].sku_induk_jenis_ppn );
			$("#updatetxtsku_induk_ppn_persen").val( SKUInduk.SKUIndukMenu[0].sku_induk_ppn_persen );
			$("#updatetxtsku_induk_ppnbm_persen").val( SKUInduk.SKUIndukMenu[0].sku_induk_ppnbm_persen );
			
			var is_hadiah = SKUInduk.SKUIndukMenu[0].sku_induk_is_hadiah;
			
			if( is_hadiah == 1	   ){	$("#updatechsku_induk_is_hadiah").prop('checked', true );		}
			else if( is_hadiah == 0 ){	$("#updatechsku_induk_is_hadiah").prop('checked', false );	}
			
			$("#previewupdatesku_induk").modal('show');
			
			//$("#updatetxtsku_induk_keterangan").trigger('keydown');
			//$("#updatetxtsku_induk_keterangan").trigger('keyup');
			
			$(".updatechpilihvariannonwajib").attr('checked', false );
			
			if( SKUInduk.SKUIndukVarianMenu != 0 )
			{
				for( i=0; i<SKUInduk.SKUIndukVarianMenu.length ; i++ )
				{
					var varian_id 		= SKUInduk.SKUIndukVarianMenu[i].varian_id;
					var varian_is_wajib	= SKUInduk.SKUIndukVarianMenu[i].varian_is_wajib;
					
					if( varian_is_wajib == 0 )
					{
						for( j=0 ; j< $(".updatechpilihvariannonwajib").length ; j++ )
						{
							var d_varian_id = $(".updatechpilihvariannonwajib").eq(j).attr('data-varian_id');
							
							if( d_varian_id == varian_id )
							{
								$(".updatechpilihvariannonwajib").eq(j).attr('checked', true );
							}
						}
					}
				}
			}
			
		}

		$("#updatecbkategori1").change
		(
			function()
			{
				var kategori_id = $("#updatecbkategori1 option:selected").val();
				
				if( kategori_id == '' )
				{
					$("#updatekat2").attr('style','display: none');
					$("#updatekat3").attr('style','display: none');
					$("#updatekat4").attr('style','display: none');
					//$("#pri").attr('style','display: none');
				
					$("#updatecbkategori2").html('');
					$("#updatecbkategori2").append('<option value="" selected>--Pilih Sub Kategori--</option>');
					$("#updatecbkategori3").html('');
					$("#updatecbkategori3").append('<option value="" selected>--Pilih Jenis--</option>');
					$("#updatecbkategori4").html('');
					$("#updatecbkategori4").append('<option value="" selected>--Pilih Sub Jenis--</option>');
					//$("#cbkategori4").html('');
					//$("#cbkategori4").append('<option value="" selected>--Pilih Principle--</option>');
				}
				else
				{
					$("#updatekat2").removeAttr('style');
					
					GetUpdateSKUKategori( 1, kategori_id );
				}
			}
		);
			
		$("#updatecbkategori2").change
		(
			function()
			{
				var kategori_id = $("#updatecbkategori2 option:selected").val();
				
				if( kategori_id == '' )
				{
					$("#updatekat3").attr('style','display: none');
					$("#updatekat4").attr('style','display: none');
					//$("#pri").attr('style','display: none');
					
					$("#updatecbkategori3").html('');
					$("#updatecbkategori3").append('<option value="" selected>--Pilih Jenis--</option>');
					$("#updatecbkategori4").html('');
					$("#updatecbkategori4").append('<option value="" selected>--Pilih Sub Jenis--</option>');
					//$("#cbkategori4").html('');
					//$("#cbkategori4").append('<option value="" selected>--Pilih Principle--</option>');
				}
				else
				{
					$("#updatekat3").removeAttr('style');
					
					GetUpdateSKUKategori( 2, kategori_id );
				}
			}
		);
		
		$("#updatecbkategori3").change
		(
			function()
			{
				var kategori_id = $("#updatecbkategori3 option:selected").val();
				
				if( kategori_id == '' )
				{
					$("#updatekat4").attr('style','display: none');
					//$("#pri").attr('style','display: none');
					
					$("#updatecbkategori4").html('');
					$("#updatecbkategori4").append('<option value="" selected>--Pilih Sub Jenis--</option>');
					//$("#cbkategori4").html('');
					//$("#cbkategori4").append('<option value="" selected>--Pilih Principle--</option>');
				}
				else
				{
					$("#updatekat4").removeAttr('style');
					
					GetUpdateSKUKategori( 3, kategori_id );
				}
			}
		);
		
		$("#updatecbprinciple").change
		(
			function()
			{
				var principle_id = $("#updatecbprinciple option:selected").val();
				
				if( principle_id == '' )
				{
					//$("#bra").attr('style','display: none');
					//$("#pri").attr('style','display: none');
					
					$("#updatecbprinciplebrand").html('');
					$("#updatecbprinciplebrand").append('<option value="" selected>--Pilih Brand--</option>');
					//$("#cbkategori4").html('');
					//$("#cbkategori4").append('<option value="" selected>--Pilih Principle--</option>');
				}
				else
				{
					GetUpdateSKUBrand( principle_id );
				}
			}
		);
		
		function GetUpdateSKUKategori( idx, p_kategori_id )
		{
			var str = '';
			var arrKategori = [];
			
			if( idx == 1 )		{	str = 'Sub Kategori';	arrKategori = arrKategori2;	}
			else if( idx == 2 )	{	str = 'Jenis';			arrKategori = arrKategori3;	}
			else if( idx == 3 )	{	str = 'Sub Jenis';		arrKategori = arrKategori4;	}
			
			var idx_x = parseInt( idx ) + 1;
			if( arrKategori.length != 0 )
			{
				for( i=0 ; i< arrKategori.length ; i++ )
				{
					var kategori_id 	= arrKategori[i].kategori_id;	
					var kategori_kode 	= arrKategori[i].kategori_kode;	
					var kategori_nama 	= arrKategori[i].kategori_nama;	
					var kategori_reffid = arrKategori[i].kategori_reffid;	
					
					if( p_kategori_id == kategori_reffid )
					{
						$("#updatecbkategori"+ idx_x ).append('<option value="'+ kategori_id +'">'+ kategori_nama +'</option>');
					}
				}
			}
		}
		
		function GetUpdateSKUBrand( p_principle_id )
		{
			$("#updatecbprinciplebrand").html(''); 
			$("#updatecbprinciplebrand").append('<option value="" selected>--Pilih Brand--</option>');
			$("#updatecbprinciplebrand").removeAttr('style');
			$("#updatebra").removeAttr('style');
			
			for( i=0 ; i< arrPrincipleBrand.length ; i++ )
			{
				var principle_brand_id 		= arrPrincipleBrand[i].principle_brand_id;	
				var principle_id 			= arrPrincipleBrand[i].principle_id;	
				var principle_brand_nama 	= arrPrincipleBrand[i].principle_brand_nama;	

				if( p_principle_id == principle_id )
				{
					$("#updatecbprinciplebrand").append('<option value="'+ principle_brand_id +'">'+ principle_brand_nama +'</option>');
				}
			}
		}
		
		$("#btnsaveupdatenewsku_induk").click
		(
			function()
			{
				var VarianOptionalMaxLimit = '<?= $VarianOptionalMaxLimit; ?>';
				
				var arrVarianWajib = [];
				var arrVarianNonWajib = [];
				
				var lvarianwajib 	= $(".updatechpilihvarianwajib").length;
				var lvariannonwajib = $(".updatechpilihvariannonwajib").length;
				
				var numnonwajib = 0;
				for( i=0 ; i< lvariannonwajib ; i++ )
				{
					if( $(".updatechpilihvariannonwajib").eq(i).prop('checked') == true )
					{
						numnonwajib++;
					}
				}
				
				if( numnonwajib > VarianOptionalMaxLimit )
				{
					var msg = 'Batas Maksimum Varian Non Wajib yang dipilih adalah '+ VarianOptionalMaxLimit +'.';
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
					if( numnonwajib == 0 )
					{
						$("#previewupdatesku_induk").modal('hide');
						$("#previewupdatebeforesave").modal('show');
					}
					else
					{
						var Idx = 1;
						for( i=0 ; i< lvarianwajib ; i++ )
						{
							var varian_id = $(".updatechpilihvarianwajib").eq(i).attr('data-varian_id');
							var Varian_Urut = Idx;
							
							arrVarianWajib.push( {	varian_id : varian_id 	} );
						}
						
						for( i=0 ; i< lvariannonwajib ; i++ )
						{
							if( $(".updatechpilihvariannonwajib").eq(i).prop('checked') == true )
							{
								var varian_id = $(".updatechpilihvariannonwajib").eq(i).attr('data-varian_id');
								var Varian_Urut = Idx;
							
								arrVarianNonWajib.push( {	varian_id : varian_id 	} );
							}
						}
						
						var sku_induk_id 			= $("#txtupdatesku_induk_id").val();
						var sku_induk_kode 			= $("#txtupdatesku_induk_kode").val();
						var sku_induk_nama 			= $("#txtupdatesku_induk_nama").val();
						var sku_induk_keterangan 	= $("#txtupdatesku_induk_keterangan").val();
						var sku_induk_nama_judul 	= $("#txtupdatesku_induk_nama_judul").val();
						
						var is_hadiah 			= $("#updatechsku_induk_is_hadiah").prop('checked');
						if( is_hadiah == true){	var sku_induk_is_hadiah = 1;	}
						else				 {	var sku_induk_is_hadiah = 0;	}

						// update terbaru
								
						var sku_induk_jenis_ppn = $("#updatecbsku_induk_jenis_ppn option:selected").val();
						var sku_induk_ppn_persen = $("#updatetxtsku_induk_ppn_persen").val();
						var sku_induk_ppnbm_persen = $("#updatetxtsku_induk_ppnbm_persen").val();
			
						var kategori1_id = $("#updatecbkategori1 option:selected").val();
						var kategori2_id = $("#updatecbkategori2 option:selected").val();
						var kategori3_id = $("#updatecbkategori3 option:selected").val();
						var kategori4_id = $("#updatecbkategori4 option:selected").val();
						
						var principle_id = $("#updatecbprinciple option:selected").val();
						var principle_brand_id = $("#updatecbprinciplebrand option:selected").val();
						
						if( kategori1_id == '' || principle_id == '' || principle_brand_id == '' )
						{
							var msg = 'Kategori, Principle, dan Brand harus diisi.';
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
						
						if( kategori1_id == '' ){	 kategori1_id = '0';	}
						if( kategori2_id == '' ){	 kategori2_id = '0';	}
						if( kategori3_id == '' ){	 kategori3_id = '0';	}
						if( kategori4_id == '' ){	 kategori4_id = '0';	}
						if( principle_id == '' ){	 principle_id = '0';	}
						if( principle_brand_id == '' ){	 principle_brand_id = '0';	}
						
						$.ajax(
						{
							type: 'POST',    
							url: "<?= base_url('SKUInduk/SaveUpdateSKUInduk') ?>",
							data: {	
									sku_induk_id : sku_induk_id,
									sku_induk_kode : sku_induk_kode,
									sku_induk_nama : sku_induk_nama,
									sku_induk_keterangan : sku_induk_keterangan,
									sku_induk_nama_judul : sku_induk_nama_judul,
									sku_induk_is_hadiah : sku_induk_is_hadiah,
									
									kategori1_id		: kategori1_id,
									kategori2_id		: kategori2_id,
									kategori3_id		: kategori3_id,
									kategori4_id		: kategori4_id,
									
									principle_id		: principle_id,
									principle_brand_id	: principle_brand_id,
									
									sku_induk_jenis_ppn		: sku_induk_jenis_ppn,
									sku_induk_ppn_persen	: sku_induk_ppn_persen,
									sku_induk_ppnbm_persen	: sku_induk_ppnbm_persen,
								
									arrVarianWajib		: arrVarianWajib,
									arrVarianNonWajib	: arrVarianNonWajib
										
									},
							success: function( response )
							{
								if(response == 1)
								{
									var msg = 'SKU Induk berhasil diubah.';
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
									
									$("#previewupdatesku_induk").modal('hide');
									
									GetSKUIndukMenu();
								}
								else if( response == 2 )
								{
									var msg = 'kode Kembar atau Sudah Ada.';
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
		
		$("#btnyessaveupdatesku_induk").click
		(
			function()
			{
				var VarianOptionalMaxLimit = '<?= $VarianOptionalMaxLimit; ?>';
				
				var arrVarianWajib = [];
				var arrVarianNonWajib = [];
				
				var lvarianwajib 	= $(".updatechpilihvarianwajib").length;
				var lvariannonwajib = $(".updatechpilihvariannonwajib").length;
				
				var numnonwajib = 0;
				for( i=0 ; i< lvariannonwajib ; i++ )
				{
					if( $(".updatechpilihvariannonwajib").eq(i).prop('checked') == true )
					{
						numnonwajib++;
					}
				}
				
				if( numnonwajib > VarianOptionalMaxLimit )
				{
					var msg = 'Batas Maksimum Varian Non Wajib yang dipilih adalah '+ VarianOptionalMaxLimit +'.';
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
					var Idx = 1;
					for( i=0 ; i< lvarianwajib ; i++ )
					{
						var varian_id = $(".updatechpilihvarianwajib").eq(i).attr('data-varian_id');
						var Varian_Urut = Idx;
						
						arrVarianWajib.push( {	varian_id : varian_id 	} );
					}
					
					for( i=0 ; i< lvariannonwajib ; i++ )
					{
						if( $(".updatechpilihvariannonwajib").eq(i).prop('checked') == true )
						{
							var varian_id = $(".updatechpilihvariannonwajib").eq(i).attr('data-varian_id');
							var Varian_Urut = Idx;
						
							arrVarianNonWajib.push( {	varian_id : varian_id 	} );
						}
					}
					
					var sku_induk_id 		= $("#txtupdatesku_indukid").val();
					var sku_induk_kode 		= $("#txtupdatesku_indukkode").val();
					var sku_induk_nama 		= $("#txtupdatesku_induknama").val();
					var sku_induk_keterangan = $("#updatetxtsku_induk_keterangan").val();
					var sku_induk_nama_judul 	= $("#txtupdatesku_induknamajudul").val();
					
					var is_hadiah 			= $("#updatechsku_induk_is_hadiah").prop('checked');
					if( is_hadiah == true){	var sku_induk_is_hadiah = 1;	}
					else				 {	var sku_induk_is_hadiah = 0;	}

					// update terbaru
							
					var sku_induk_jenis_ppn = $("#updatecbsku_induk_jenis_ppn option:selected").val();
					var sku_induk_ppn_persen = $("#updatetxtsku_induk_ppn_persen").val();
					var sku_induk_ppnbm_persen = $("#updatetxtsku_induk_ppnbm_persen").val();
		
					var kategori_id = $("#updatecbkategori option:selected").val();
					var kategori2_id = $("#updatecbkategori2 option:selected").val();
					var Kategori3_id = $("#updatecbkategori3 option:selected").val();
					var Kategori4_id = $("#updatecbkategori4 option:selected").val();
					
					var Kategori9_id = $("#updatecbprinciple option:selected").val();
					var kategori0_id = $("#updatecbprinciplebrand option:selected").val();
					
					if( kategori_id == '' || Kategori9_id == '' || kategori0_id == '' )
					{
						var msg = 'Kategori, Principle, dan Brand harus diisi.';
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
					
					if( kategori_id == '' ){	 kategori_id = '0';	}
					if( kategori2_id == '' ){	 kategori2_id = '0';	}
					if( Kategori3_id == '' ){	 Kategori3_id = '0';	}
					if( Kategori4_id == '' ){	 Kategori4_id = '0';	}
					if( Kategori9_id == '' ){	 Kategori9_id = '0';	}
					if( kategori0_id == '' ){	 kategori0_id = '0';	}
					
					$.ajax(
					{
						type: 'POST',    
						url: "<?= base_url('SKUInduk/SaveUpdateSKUInduk') ?>",
						data: {	
								sku_induk_id 		: sku_induk_id,
								sku_induk_kode 		: sku_induk_kode,
								sku_induk_nama 		: sku_induk_nama,
								sku_induk_keterangan : sku_induk_keterangan,
								sku_induk_nama_judul 	: sku_induk_nama_judul,
								sku_induk_is_hadiah 	: sku_induk_is_hadiah,
								
								kategori_id		: kategori_id,
								kategori2_id		: kategori2_id,
								Kategori3_id		: Kategori3_id,
								Kategori4_id		: Kategori4_id,
								
								Kategori9_id		: Kategori9_id,
								kategori0_id		: kategori0_id,
								
								sku_induk_jenis_ppn		: sku_induk_jenis_ppn,
								sku_induk_ppn_persen		: sku_induk_ppn_persen,
								sku_induk_ppnbm_persen	: sku_induk_ppnbm_persen,
								
								arrVarianWajib		: arrVarianWajib,
								arrVarianNonWajib	: arrVarianNonWajib
									
								},
						success: function( response )
						{
							if(response == 1)
							{
								var msg = 'SKU Induk berhasil diubah.';
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
								
								$("#previewupdatesku_induk").modal('hide');
								
								GetSKUIndukMenu();
							}
							else if( response == 2 )
							{
								var msg = 'kode Kembar atau Sudah Ada.';
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
		);
		
		$("#btnupdatesku_induk").click
		(
			function()
			{
				ResetForm();
			}
		);
	<?php
	}
	?>
	
	<?php
	if($Menu_Access["C"] == 1)
	{
	?>
		$("#btnaddnewsku_induk").removeAttr('style');
		
		$("#cbkategori1").html('');
		$("#cbkategori2").html('');
		$("#cbkategori3").html('');
		$("#cbkategori4").html('');
		
		$("#cbkategori1").append('<option value="" selected>--Pilih Kategori--</option>');
		$("#cbkategori2").append('<option value="" selected>--Pilih Sub Kategori--</option>');
		$("#cbkategori3").append('<option value="" selected>--Pilih Jenis--</option>');
		$("#cbkategori4").append('<option value="" selected>--Pilih Sub Jenis--</option>');
		
		$("#cbprinciple").html('');
		$("#cbprinciplebrand").html('');
		
		$("#cbprinciple").append('<option value="" selected>--Pilih Principle--</option>');
		$("#cbprinciplebrand").append('<option value="" selected>--Pilih Brand--</option>');
		
		// Add New SKUInduk
		$("#btnaddnewsku_induk").click
		(
			function()
			{
				ResetForm();
				
				
				for( i=0 ; i<arrKategori1.length ; i++ )
				{
					var kategori_id 	= arrKategori1[i].kategori_id;
					var kategori_kode	= arrKategori1[i].kategori_kode;
					var kategori_nama	= arrKategori1[i].kategori_nama;
					
					$("#cbkategori1").append('<option value="'+ kategori_id +'">'+ kategori_nama +'</option>');
				}
				
				for( i=0 ; i<arrPrinciple.length ; i++ )
				{
					var principle_id 	= arrPrinciple[i].principle_id;
					var principle_kode	= arrPrinciple[i].principle_kode;
					var principle_nama	= arrPrinciple[i].principle_nama;
					
					$("#cbprinciple").append('<option value="'+ principle_id +'">'+ principle_nama +'</option>');
				}
				
				$("#previewaddnewsku_induk").modal('show');
			}
		);
		
		$("#cbkategori1").change
		(
			function()
			{
				var kategori_id = $("#cbkategori1 option:selected").val();
				
				if( kategori_id == '' )
				{
					$("#kat2").attr('style','display: none');
					$("#kat3").attr('style','display: none');
					$("#kat4").attr('style','display: none');
					//$("#pri").attr('style','display: none');
				
					$("#cbkategori2").html('');
					$("#cbkategori2").append('<option value="" selected>--Pilih Sub Kategori--</option>');
					$("#cbkategori3").html('');
					$("#cbkategori3").append('<option value="" selected>--Pilih Jenis--</option>');
					$("#cbkategori4").html('');
					$("#cbkategori4").append('<option value="" selected>--Pilih Sub Jenis--</option>');
					//$("#cbkategori4").html('');
					//$("#cbkategori4").append('<option value="" selected>--Pilih Principle--</option>');
				}
				else
				{
					GetSKUKategori( 1, kategori_id );
				}
			}
		);
			
		$("#cbkategori2").change
		(
			function()
			{
				var kategori_id = $("#cbkategori2 option:selected").val();
				
				if( kategori_id == '' )
				{
					$("#kat3").attr('style','display: none');
					$("#kat4").attr('style','display: none');
					//$("#pri").attr('style','display: none');
					
					$("#cbkategori3").html('');
					$("#cbkategori3").append('<option value="" selected>--Pilih Jenis--</option>');
					$("#cbkategori4").html('');
					$("#cbkategori4").append('<option value="" selected>--Pilih Sub Jenis--</option>');
					//$("#cbkategori4").html('');
					//$("#cbkategori4").append('<option value="" selected>--Pilih Principle--</option>');
				}
				else
				{
					GetSKUKategori( 2, kategori_id );
				}
			}
		);
		
		$("#cbkategori3").change
		(
			function()
			{
				var kategori_id = $("#cbkategori3 option:selected").val();
				
				if( kategori_id == '' )
				{
					$("#kat4").attr('style','display: none');
					//$("#pri").attr('style','display: none');
					
					$("#cbkategori4").html('');
					$("#cbkategori4").append('<option value="" selected>--Pilih Sub Jenis--</option>');
					//$("#cbkategori4").html('');
					//$("#cbkategori4").append('<option value="" selected>--Pilih Principle--</option>');
				}
				else
				{
					GetSKUKategori( 3, kategori_id );
				}
			}
		);
		
		$("#cbprinciple").change
		(
			function()
			{
				var principle_id = $("#cbprinciple option:selected").val();
				
				if( principle_id == '' )
				{
					//$("#bra").attr('style','display: none');
					//$("#pri").attr('style','display: none');
					
					$("#cbprinciplebrand").html('');
					$("#cbprinciplebrand").append('<option value="" selected>--Pilih Brand--</option>');
					//$("#cbkategori4").html('');
					//$("#cbkategori4").append('<option value="" selected>--Pilih Principle--</option>');
				}
				else
				{
					GetSKUBrand( principle_id );
				}
			}
		);
		
		function GetSKUKategori( idx, p_kategori_id )
		{
			var str = '';
			var arrKategori = [];
			
			if( idx == 1 )		{	str = 'Sub Kategori';	arrKategori = arrKategori2;	}
			else if( idx == 2 )	{	str = 'Jenis';			arrKategori = arrKategori3;	}
			else if( idx == 3 )	{	str = 'Sub Jenis';		arrKategori = arrKategori4;	}
			
			if( idx == 1 )	
			{
				$("#kat2").attr('style','display: none;');
				$("#kat3").attr('style','display: none;');
				$("#kat4").attr('style','display: none;');
				$("#kat2").removeAttr('style');
			
				$("#cbkategori2").html('');
				$("#cbkategori2").append('<option value="">--Pilih Sub Kategori--</option>');
				$("#cbkategori3").html('');
				$("#cbkategori3").append('<option value="">--Pilih Jenis--</option>');
				$("#cbkategori4").html('');
				$("#cbkategori4").append('<option value="">--Pilih Sub Jenis--</option>');
			}
			else if( idx == 2 )	
			{
				$("#kat3").attr('style','display: none;');
				$("#kat4").attr('style','display: none;');
				$("#kat3").removeAttr('style');
				
				$("#cbkategori3").html('');
				$("#cbkategori3").append('<option value="">--Pilih Jenis--</option>');
				$("#cbkategori4").html('');
				$("#cbkategori4").append('<option value="">--Pilih Sub Jenis--</option>');
			}
			else if( idx == 3 )	
			{
				$("#kat4").attr('style','display: none;');
				$("#kat4").removeAttr('style');
				
				$("#cbkategori4").html('');
				$("#cbkategori4").append('<option value="">--Pilih Sub Jenis--</option>');
			}
			
			var idx_x = parseInt( idx ) + 1;
			if( arrKategori.length != 0 )
			{
				for( i=0 ; i< arrKategori.length ; i++ )
				{
					var kategori_id 	= arrKategori[i].kategori_id;	
					var kategori_kode 	= arrKategori[i].kategori_kode;	
					var kategori_nama 	= arrKategori[i].kategori_nama;	
					var kategori_reffid = arrKategori[i].kategori_reffid;	
					
					if( p_kategori_id == kategori_reffid )
					{
						$("#cbkategori"+ idx_x ).append('<option value="'+ kategori_id +'">'+ kategori_nama +'</option>');
					}
				}
			}
		}
		
		function GetSKUBrand( p_principle_id )
		{
			$("#cbprinciplebrand").html(''); 
			$("#cbprinciplebrand").append('<option value="" selected>--Pilih Brand--</option>');
			$("#cbprinciplebrand").removeAttr('style');
			$("#bra").removeAttr('style');
			
			for( i=0 ; i< arrPrincipleBrand.length ; i++ )
			{
				var principle_brand_id 		= arrPrincipleBrand[i].principle_brand_id;	
				var principle_id 			= arrPrincipleBrand[i].principle_id;	
				var principle_brand_nama 	= arrPrincipleBrand[i].principle_brand_nama;	

				if( p_principle_id == principle_id )
				{
					$("#cbprinciplebrand").append('<option value="'+ principle_brand_id +'">'+ principle_brand_nama +'</option>');
				}
			}
		}
		
		$("#btnsaveaddnewsku_induk").click
		(
			function()
			{	
				var VarianOptionalMaxLimit = '<?= $VarianOptionalMaxLimit; ?>';
				
				var arrVarianWajib = [];
				var arrVarianNonWajib = [];
				
				var lvarianwajib 	= $(".chpilihvarianwajib").length;
				var lvariannonwajib = $(".chpilihvariannonwajib").length;
				
				var numnonwajib = 0;
				for( i=0 ; i< lvariannonwajib ; i++ )
				{
					if( $(".chpilihvariannonwajib").eq(i).prop('checked') == true )
					{
						numnonwajib++;
					}
				}
				
				if( numnonwajib > VarianOptionalMaxLimit )
				{
					var msg = 'Batas Maksimum Varian Non Wajib yang dipilih adalah '+ VarianOptionalMaxLimit +'.';
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
					if( numnonwajib == 0 )
					{
						$("#previewaddnewsku_induk").modal('hide');
						$("#previewbeforesave").modal('show');
					}
					else
					{
						var Idx = 1;
						for( i=0 ; i< lvarianwajib ; i++ )
						{
							var varian_id = $(".chpilihvarianwajib").eq(i).attr('data-varian_id');
							var Varian_Urut = Idx;
							
							arrVarianWajib.push( {	varian_id : varian_id 	} );
						}
						
						for( i=0 ; i< lvariannonwajib ; i++ )
						{
							if( $(".chpilihvariannonwajib").eq(i).prop('checked') == true )
							{
								var varian_id = $(".chpilihvariannonwajib").eq(i).attr('data-varian_id');
								var Varian_Urut = Idx;
							
								arrVarianNonWajib.push( {	varian_id : varian_id 	} );
							}
						}
						
						var sku_induk_kode 		= $("#txtsku_induk_kode").val();
						var sku_induk_nama 		= $("#txtsku_induk_nama").val();
						var sku_induk_keterangan = $("#txtsku_induk_keterangan").val();
						var sku_induk_nama_judul 	= $("#txtsku_induk_nama_judul").val();
						
						var is_hadiah 			= $("#chsku_induk_is_hadiah").prop('checked');
						if( is_hadiah == true){	var sku_induk_is_hadiah = 1;	}
						else				 {	var sku_induk_is_hadiah = 0;	}

						// update terbaru
						
						var sku_induk_jenis_ppn = $("#cbsku_induk_jenis_ppn option:selected").val();
						var sku_induk_ppn_persen = $("#txtsku_induk_ppn_persen").val();
						var sku_induk_ppnbm_persen = $("#txtsku_induk_ppnbm_persen").val();
			
						var kategori1_id = $("#cbkategori1 option:selected").val();
						var kategori2_id = $("#cbkategori2 option:selected").val();
						var Kategori3_id = $("#cbkategori3 option:selected").val();
						var Kategori4_id = $("#cbkategori4 option:selected").val();
						
						var principle_id = $("#cbprinciple option:selected").val();
						var principle_brand_id = $("#cbprinciplebrand option:selected").val();
						
						if( kategori1_id == '' || principle_id == '' || principle_brand_id == '' )
						{
							var msg = 'Kategori, Principle, dan Brand harus diisi.';
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
						
						if( kategori1_id == '' ){	 kategori1_id = '0';	}
						if( kategori2_id == '' ){	 kategori2_id = '0';	}
						if( Kategori3_id == '' ){	 Kategori3_id = '0';	}
						if( Kategori4_id == '' ){	 Kategori4_id = '0';	}
						if( principle_id == '' ){	 principle_id = '0';	}
						if( principle_brand_id == '' ){	 principle_brand_id = '0';	}
						
						$.ajax(
						{
							type: 'POST',    
							url: "<?= base_url('SKUInduk/SaveAddNewSKUInduk') ?>",
							data: {	
									sku_induk_kode 			: sku_induk_kode,
									sku_induk_nama 			: sku_induk_nama,
									sku_induk_keterangan 	: sku_induk_keterangan,
									sku_induk_nama_judul 	: sku_induk_nama_judul,
									sku_induk_is_hadiah 	: sku_induk_is_hadiah,
									
									arrVarianWajib 		: arrVarianWajib,
									arrVarianNonWajib 	: arrVarianNonWajib,
									
									kategori1_id		: kategori1_id,
									kategori2_id		: kategori2_id,
									Kategori3_id		: Kategori3_id,
									Kategori4_id		: Kategori4_id,
									
									principle_id		: principle_id,
									principle_brand_id	: principle_brand_id,
									
									sku_induk_jenis_ppn		: sku_induk_jenis_ppn,
									sku_induk_ppn_persen	: sku_induk_ppn_persen,
									sku_induk_ppnbm_persen	: sku_induk_ppnbm_persen
								
									},
							success: function( response )
							{
								if(response == 1)
								{
									var msg = 'SKU Induk berhasil ditambah.';
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
									
									GetSKUIndukMenu();
						
									$("#previewaddnewsku_induk").modal('hide');
									ResetForm();
								}
								else
								{
									if( response == 2 ) 	{	var msg = 'kode Kembar atau Sudah Ada.'; }
									else if( response == 3 ){	var msg = 'nama Kembar atau Sudah Ada.'; }
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
				}
			}
		);
		
		$("#btnnosaveaddnewsku_induk").click
		(
			function()
			{
				$("#previewaddnewsku_induk").modal('show');
				$("#previewbeforesave").modal('hide');
			}
		);
		
		$("#btnyessaveaddnewsku_induk").click
		(
			function()
			{
				var VarianOptionalMaxLimit = '<?= $VarianOptionalMaxLimit; ?>';
				
				var arrVarianWajib = [];
				var arrVarianNonWajib = [];
				
				var lvarianwajib 	= $(".chpilihvarianwajib").length;
				var lvariannonwajib = $(".chpilihvariannonwajib").length;
				
				var numnonwajib = 0;
				for( i=0 ; i< lvariannonwajib ; i++ )
				{
					if( $(".chpilihvariannonwajib").eq(i).prop('checked') == true )
					{
						numnonwajib++;
					}
				}
				
				if( numnonwajib > VarianOptionalMaxLimit )
				{
					var msg = 'Batas Maksimum Varian Non Wajib yang dipilih adalah '+ VarianOptionalMaxLimit +'.';
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
					var Idx = 1;
					for( i=0 ; i< lvarianwajib ; i++ )
					{
						var varian_id = $(".chpilihvarianwajib").eq(i).attr('data-varian_id');
						var Varian_Urut = Idx;
						
						arrVarianWajib.push( {	varian_id : varian_id 	} );
					}
					
					for( i=0 ; i< lvariannonwajib ; i++ )
					{
						if( $(".chpilihvariannonwajib").eq(i).prop('checked') == true )
						{
							var varian_id = $(".chpilihvariannonwajib").eq(i).attr('data-varian_id');
							var Varian_Urut = Idx;
						
							arrVarianNonWajib.push( {	varian_id : varian_id 	} );
						}
					}
					var sku_induk_kode 		= $("#txtsku_induk_kode").val();
					var sku_induk_nama 		= $("#txtsku_induk_nama").val();
					var sku_induk_keterangan = $("#txtsku_induk_keterangan").val();
					var sku_induk_nama_judul 	= $("#txtsku_induk_nama_judul").val();
					
					var is_hadiah 			= $("#chsku_induk_is_hadiah").prop('checked');
					if( is_hadiah == true)	{	var sku_induk_is_hadiah = 1;	}
					else					{	var sku_induk_is_hadiah = 0;	}

					// update terbaru
					var sku_induk_jenis_ppn = $("#cbsku_induk_jenis_ppn option:selected").val();
					var sku_induk_ppn_persen = $("#txtsku_induk_ppn_persen").val();
					var sku_induk_ppnbm_persen = $("#txtsku_induk_ppnbm_persen").val();
		
					var kategori1_id = $("#cbkategori1 option:selected").val();
					var kategori2_id = $("#cbkategori2 option:selected").val();
					var Kategori3_id = $("#cbkategori3 option:selected").val();
					var Kategori4_id = $("#cbkategori4 option:selected").val();
					
					var principle_id = $("#cbprinciple option:selected").val();
					var principle_brand_id = $("#cbprinciplebrand option:selected").val();
					
					if( kategori_id == '' || principle_id == '' || principle_brand_id == '' )
					{
						var msg = 'Kategori, Principle, dan Brand harus diisi.';
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
					
					if( kategori1_id == '' )		{	 kategori1_id = '0';	}
					if( kategori2_id == '' )		{	 kategori2_id = '0';	}
					if( Kategori3_id == '' )		{	 kategori3_id = '0';	}
					if( Kategori4_id == '' )		{	 kategori4_id = '0';	}
					
					if( principle_id == '' )		{	 principle_id = '0';	}
					if( principle_brand_id == '' )	{	 principle_brand_id = '0';	}
					
					$.ajax(
					{
						type: 'POST',    
						url: "<?= base_url('SKUInduk/SaveAddNewSKUInduk') ?>",
						data: {	
								sku_induk_kode 			: sku_induk_kode,
								sku_induk_nama 			: sku_induk_nama,
								sku_induk_keterangan 	: sku_induk_keterangan,
								sku_induk_nama_judul 	: sku_induk_nama_judul,
								sku_induk_is_hadiah 	: sku_induk_is_hadiah,
								
								arrVarianWajib 			: arrVarianWajib,
								arrVarianNonWajib 		: arrVarianNonWajib,
									
								kategori1_id		: kategori1_id,
								kategori2_id		: kategori2_id,
								Kategori3_id		: Kategori3_id,
								Kategori4_id		: Kategori4_id,
							
								principle_id		: principle_id,
								principle_brand_id	: principle_brand_id,
								
								sku_induk_jenis_ppn		: sku_induk_jenis_ppn,
								sku_induk_ppn_persen	: sku_induk_ppn_persen,
								sku_induk_ppnbm_persen	: sku_induk_ppnbm_persen
						},
						success: function( response )
						{
							if(response == 1)
							{
								var msg = 'SKU Induk berhasil ditambah.';
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
								
								GetSKUIndukMenu();
					
								$("#previewaddnewsku_induk").modal('hide');
								ResetForm();
							}
							else
							{
								if( response == 2 ) 	{	var msg = 'kode Kembar atau Sudah Ada.'; }
								else if( response == 3 ){	var msg = 'nama Kembar atau Sudah Ada.'; }
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
			$("#txtupdatesku_indukkode").val('');
			$("#txtupdatesku_induknama").val('');
			$("#txtupdatesku_indukketerangan").val('');
			$("#txtupdatesku_induknamajudul").val('');
			$("#updatechsku_induk_is_hadiah").prop('checked', false);
			
			$(".chpilihvariannonwajib").prop('checked', false );
		<?php
		}
		?>
		
		<?php
		if($Menu_Access["C"] == 1)
		{
		?>
			$("#txtsku_induk_kode").val('');
			$("#txtsku_induk_nama").val('');
			$("#txtsku_induk_keterangan").val('');
			$("#txtsku_induk_nama_judul").val('');
			$("#chsku_induk_is_hadiah").prop('checked', false);
			
			$(".updatechpilihvariannonwajib").prop('checked', false );
		<?php
		}
		?>
		
	}
</script>