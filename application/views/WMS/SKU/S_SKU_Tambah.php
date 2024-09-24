<script type="text/javascript">	
	var ljumket = 0;
	var arrVarian = [];
	var arrVarianDetail = [];
	var arrKategori1 = [];
	var arrKategori2 = [];
	var arrKategori3 = [];
	var arrKategori4 = [];
	var arrPrinciple = [];
	var arrPrincipleBrand = [];
	var arrSatuan = [];
	
	var arrSalin = [];
	var arrUpdateSalin = [];
	
	var arrDataVarianWajibDetail = [];
	var arrDataVarianDetail = [];
	
	var arrUpdateDataVarianWajibDetail = [];
	var arrUpdateDataVarianDetail = [];
	
	// Bosnet ID
	var arrsku_bosnet_id = [];
	
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
	
	function intToChar( int )
	{
		var code = 'A'.charCodeAt(0);
		
		return String.fromCharCode( code + int );
	}
	
	$(document).ready
	(
		function() 
		{
			$("#navigation_letter").append( '<li id="letter_tab_All" class="active"><a href="#SKUInduk_letter_All" role="letter_tab" data-toggle="pill">All</a></li>' );
			
			$("#anylist_letter").append('<div class="tab-pane active" class="form-horizontal form-label-left SKUInduk_letter" style="overflow-x: hidden; min-height: 500px; max-height: 500px;" id="SKUInduk_letter_All" data-letter="All"></div>');

			for( i=0 ; i< 26 ; i++ )
			{
				var classactive = '';
				var active = '';
				
				var letter = intToChar( i );
				
				//if( i == 0 )	{	classactive = 'class="active"';	active = 'active'; }
				
				$("#navigation_letter").append( '<li id="letter_tab_'+ i +'" '+ classactive +'><a href="#SKUInduk_letter_'+ letter +'" role="letter_tab" data-toggle="pill" style="width: 35px;">'+ letter +'</a></li>' );
			
				$("#anylist_letter").append('<div class="tab-pane" class="form-horizontal form-label-left SKUInduk_letter" style="overflow-x: hidden; min-height: 500px; max-height: 500px;" id="SKUInduk_letter_'+ letter +'" data-letter="'+ letter +'"></div>');
			}
			
			GetSKUIndukMenu();
			
			GetSKUMenu();
			/*
			$('#tablegeneratedstringsku').DataTable(
			{
				scrollX: true,
				retrieve: true,
				"dom": '<"left"f>rti',
				paging: false,
				fixedColumns:   
				{
					leftColumns: 1
				}
				
			});
			*/
			$('#updatetablegeneratedstringsku').DataTable(
			{
				scrollX: true,
				retrieve: true,
				"dom": '<"left"f>rti',
				paging: false,
			});
			/*
			$('#updatetabledatasku').DataTable(
			{
				scrollX: true,
				retrieve: true,
				"dom": '<"left"f>rti',
				paging: false,
			});
		*/
			$('#tabledataskusaved').DataTable(
			{
				scrollX: true,
				retrieve: true,
				"dom": '<"left"f>rtp'
			});
			
			$('#updatetabledataskusaved').DataTable(
			{
				scrollX: true,
				retrieve: true,
				"dom": '<"left"f>rtp'
			});
			
			for( i=1 ; i<=10 ; i++ )
			{
				//$("#cbKategori"+ i).css("width", "100%");
				//$("#cbKategori"+ i).select2({
				//	"theme": "bootstrap", "val": ""
				//});
				
				//$("#updatecbKategori"+ i).css("width", "100%");
				//$("#updatecbKategori"+ i).select2({
				//	"theme": "bootstrap", "val": ""
				//});
			}
			
			$("#cbsku_induk").css("width", "100%");
			$("#cbsku_induk").select2({
				"theme": "bootstrap", "val": ""
			});
			
			$("#updatecbsku_induk").css("width", "100%");
			$("#updatecbsku_induk").select2({
				"theme": "bootstrap", "val": ""
			});
			
			$(".cbvarian").css("width", "100%");
			$(".cbvarian").select2({
				"theme": "bootstrap", "val": ""
			});
			
			$(".updatecbvarian").css("width", "100%");
			$(".updatecbvarian").select2({
				"theme": "bootstrap", "val": ""
			});
			
		}
	);
	
	$("#btnback").click
	(
		function()
		{
			arrsku_bosnet_id = [];
			
			$("#previewkembalikelistskuinduk").modal('show');
		}
	);
	
	$("#txasku_induk_ket").keyup
	(
		function( event )
		{
			var lket = $("#txasku_induk_ket").val().length;
			lket = 8000 - lket;
			
			if( lket == 0 )	{	lket = '<strong style="color: red;">'+ lket +'</strong>';	}
			else			{	lket = '<strong style="color: black;">'+ lket +'</strong>';	}
			
			$("#lbjumlahket").html( 'Sisa Karakter : '+ lket );
		}
	);
	
	$("#txasku_induk_ket").keydown
	(
		function( event )
		{
			var lket = $("#txasku_induk_ket").val().length;
			lket = 8000 - lket;
			
			if( lket == 0 )	{	lket = '<strong style="color: red;">'+ lket +'</strong>';	}
			else			{	lket = '<strong style="color: black;">'+ lket +'</strong>';	}
			
			$("#lbjumlahket").html( 'Sisa Karakter : '+ lket );
		}
	);
	
	$("#btnyesback").click
	(
		function()
		{
			ResetForm();
			
			$("#MainMenu").removeAttr('style');
			$("#anylist_letter").removeAttr('style');
			$("#Konversi").removeAttr('style');
			$("#Tambah").attr('style','display: none;');
			$("#Konversi").attr('style','display: none;');
		}
	);
	
	$("#txtsearch").change
	(
		function()
		{
			GetSKUIndukMenu();
		}
	);
	
	function GetSKUIndukMenu()
	{
		var sku_induk_nama = $("#txtsearch").val();
		
		$.ajax(
		{
			type: 'POST',    
			url: "<?= base_url('SKU/GetSKUIndukMenu') ?>",
			data: 
			{
				sku_induk_nama : sku_induk_nama
			},
			success: function( response )
			{
				if(response)
				{
					ChSKUIndukMenu( response );
				}
			}
		});	
	}
	
	function checkImage(imageSrc, good, bad) 
	{
		var img = new Image();
		img.onload = good; 
		img.onerror = bad;
		img.src = imageSrc;
	}

	function ChSKUIndukMenu( JSONSKUInduk )
	{
		var SKUInduk = JSON.parse( JSONSKUInduk );
		
		$(".SKUInduk_letter").html('');
		
		if( SKUInduk.SKUIndukMenu != 0 )
		{
			
			for( i=0 ; i< SKUInduk.SKUIndukMenu.length ; i++ )
			{
				sku_induk_id			= SKUInduk.SKUIndukMenu[i].sku_induk_id;
				sku_induk_kode			= SKUInduk.SKUIndukMenu[i].sku_induk_kode;
				sku_induk_nama			= SKUInduk.SKUIndukMenu[i].sku_induk_nama;
				sku_induk_keterangan	= SKUInduk.SKUIndukMenu[i].sku_induk_keterangan;
				
				sku_photo_detail_dir	= SKUInduk.SKUIndukMenu[i].sku_photo_detail_dir;
				
				var letter = sku_induk_nama.charAt(0).toUpperCase();
				
				if( sku_photo_detail_dir == "" ){	var Photo = '<?= base_url() ?>assets/images/img.png';	}
				else							{	var Photo = '<?= base_url() ?>'+ sku_photo_detail_dir;	}
				
				var str = '';
				
				str = str + '<div class="col-xs-6 col-sm-4 col-md-4 col-lg-3 col-xl-3 nopadding">';
				str = str + '	<div class="panel panel-primary">';
				str = str + '		<!--div class="panel-heading" align="center"><label>'+ sku_induk_nama +'</label></div-->';
				str = str + '		<div class="panel-body nopadding">';
				str = str + '			<div class="panelimg">';
				str = str + '				<img id="srcphoto_'+ i +'" src="'+ Photo +'?'+ new Date().getTime() +' class="img-responsive" style="width: 100%; height: 250px;">';
				str = str + '				<header>';
				str = str + '					<div class="topleft">';
				str = str + '						<label>'+ sku_induk_nama +'</label>';
				str = str + '					</div>';
				str = str + '				</header>';
				str = str + '			</div>';
				str = str + '		</div>';
				str = str + '		<div class="panel-footer">';
				str = str + '			<button type="button" class="form-control btn btn-warning btnubahskuinduk" onclick="UbahSKUInduk(\''+ sku_induk_id +'\', \''+ sku_induk_nama +'\')"><i class="fa fa-pencil"></i> Ubah</button>';		
				str = str + '			<button type="button" class="form-control btn btn-primary btnaturstock" onclick="AturStock(\''+ sku_induk_id +'\', \''+ sku_induk_nama +'\')"><i class="fa fa-book"></i> Lihat Stock</button>';
				str = str + '			<button type="button" class="form-control btn btn-primary btnkonversi" onclick="KonversiSKUInduk(\''+ sku_induk_id +'\', \''+ sku_induk_nama +'\')"><i class="fa fa-exchange"></i> Konversi</button>';
				str = str + '			<button type="button" class="form-control btn btn-primary btnaturphoto" onclick="UploadPhotoMenu(\''+ sku_induk_id +'\', \''+ sku_induk_nama +'\')"><i class="fa fa-picture-o"></i> Atur Foto</button>';		
				str = str + '		</div>';
				str = str + '	</div>';
				str = str + '</div>';
				
				$("#SKUInduk_letter_"+ letter ).append( str );
				
				$("#SKUInduk_letter_All").append( str );
			}
		}
	}
	
	$("#btntambahskuinduk").click
	(
		function()
		{
			GetSKUMenu();
			
			$("#MainMenu").attr('style','display: none;');
			$("#anylist_letter").attr('style','display: none;');
			$("#Tambah").removeAttr('style');
		}
	);
	
	$("#cbsku_induk").change
	(
		function()
		{
			var sku_induk_id = $("#cbsku_induk option:selected").val();
			
			if( sku_induk_id == '' )
			{
				$("#txasku_induk_ket").val('');
			}
			else
			{
				$.ajax(
				{
					type: 'POST',    
					url: "<?= base_url('SKU/GetSKUIndukDataMenu') ?>",
					data: 
					{
						sku_induk_id : sku_induk_id
					},
					success: function( response )
					{
						if(response)
						{
							ChSKUIndukDataMenu( response );
						}
					}
				});	
			}
		}
	);
	
	function ChSKUIndukDataMenu( JSONSKUInduk )
	{
		var SKUInduk = JSON.parse( JSONSKUInduk );
	
		var sku_induk_keterangan = SKUInduk.SKUIndukMenu[0].sku_induk_keterangan;
		
		var kategori1_id = SKUInduk.SKUIndukMenu[0].kategori1_id;
		var kategori2_id = SKUInduk.SKUIndukMenu[0].kategori2_id;
		var kategori3_id = SKUInduk.SKUIndukMenu[0].kategori3_id;
		var kategori4_id = SKUInduk.SKUIndukMenu[0].kategori4_id;
		
		var principle_id = SKUInduk.SKUIndukMenu[0].principle_id;
		var principle_brand_id = SKUInduk.SKUIndukMenu[0].principle_brand_id;
		
		var kategori1_nama 			= SKUInduk.SKUIndukMenu[0].kategori1_nama;
		var kategori2_nama 			= SKUInduk.SKUIndukMenu[0].kategori2_nama;
		var kategori3_nama 			= SKUInduk.SKUIndukMenu[0].kategori3_nama;
		var kategori4_nama 			= SKUInduk.SKUIndukMenu[0].kategori4_nama;
		var principle_nama 			= SKUInduk.SKUIndukMenu[0].principle_nama;
		var principle_brand_nama 	= SKUInduk.SKUIndukMenu[0].principle_brand_nama;
		
		var sku_induk_jenis_ppn 	= SKUInduk.SKUIndukMenu[0].sku_induk_jenis_ppn;
		var sku_induk_ppnbm_persen 	= SKUInduk.SKUIndukMenu[0].sku_induk_ppnbm_persen;
		var sku_induk_ppn_persen 	= SKUInduk.SKUIndukMenu[0].sku_induk_ppn_persen;
		
		$("#txasku_induk_ket").val( sku_induk_keterangan );

		/*
		$("#cbkategori1").val( kategori1_id ).change();
		$("#cbkategori1").trigger('change');

		$("#cbkategori2").val( kategori2_id ).change();
		$("#cbkategori2").trigger('change');
		
		$("#cbkategori3").val( kategori3_id ).change();
		$("#cbkategori3").trigger('change');
		
		$("#cbkategori4").val( kategori4_id ).change();
		$("#cbprinciple").val( principle_id ).change();
		$("#cbprinciplebrand").val( principle_brand_id ).change();
	*/
		$("#kat1").attr('style','display: none;');
		$("#kat2").attr('style','display: none;');
		$("#kat3").attr('style','display: none;');
		$("#kat4").attr('style','display: none;');
		
		$("#kat1").removeAttr('style');
		$("#cbkategori1").val( kategori1_nama );
		$("#cbkategori1").data('kategori1_id', kategori1_id ); 
	
		if( kategori2_id != '' && kategori2_id != null )
		{
			$("#kat2").removeAttr('style');
			$("#cbkategori2").val( kategori2_id );
			$("#cbkategori2").data('kategori2_id', kategori2_id ); 
			
			if( kategori3_id != '' && kategori3_id != null )
			{
				$("#kat3").removeAttr('style');
				$("#cbkategori3").val( kategori3_nama );
				$("#cbkategori3").data('kategori3_id', kategori3_id ); 
				
				if( kategori4_id != '' && kategori4_id != null )
				{
					$("#kat4").removeAttr('style');
					$("#cbkategori4").val( kategori4_nama );
					$("#cbkategori4").data('kategori4_id', kategori4_id ); 
				}
			}
		}
		
		$("#cbprinciple").val( principle_id );
		$("#cbprinciple").data('principle_id', principle_id ); 
		
		if( $("#cbprinciple").val() != '' || $("#cbprinciple").val() != null )
		{
			$("#cbprinciplebrand").removeAttr('style');
			$("#cbprinciplebrand").val( principle_brand_id );
			$("#cbprinciplebrand").data('principle_brand_id', principle_brand_id ); 
		}
		
		$("#cbsku_induk_jenis_ppn").val( sku_induk_jenis_ppn );
		$("#txtsku_induk_ppn_persen").val( sku_induk_ppnbm_persen );
		$("#txtsku_induk_ppnbm_persen").val( sku_induk_ppn_persen );
		
		$("#txasku_induk_ket").trigger('keydown');
		$("#txasku_induk_ket").trigger('keyup');
	}
	
	function GetSKUMenu()
	{
		$.ajax(
		{
			type: 'POST',    
			url: "<?= base_url('SKU/GetSKUMenu') ?>",
			//data: "Location="+ Location,
			success: function( response )
			{
				if(response)
				{
					ChSKUMenu( response );
				}
			}
		});	
	}
		
	//var DTABLE;
	
	function ChSKUMenu( JSONSKU )
	{
		GetSKUIndukMenu();
		
		$("#tableskumenu > tbody").html('');
		
		var SKU = JSON.parse( JSONSKU );
		
		$("#cbsku_induk").html('');
		$("#cbsku_induk").append( '<option value="">--Pilih SKU Induk--</option>');
		
		if( SKU.SKUIndukMenu != 0 )
		{
			for( i=0 ; i<SKU.SKUIndukMenu.length ; i++ )
			{
				var sku_induk_id 	= SKU.SKUIndukMenu[i].sku_induk_id;
				var sku_induk_kode 	= SKU.SKUIndukMenu[i].sku_induk_kode;
				var sku_induk_nama 	= SKU.SKUIndukMenu[i].sku_induk_nama;
				
				$("#cbsku_induk").append( '<option value="'+ sku_induk_id +'">'+ sku_induk_nama +'</option>');
			}
		}
		
		arrVarian = [];
		arrVarianDetail = [];
		
		if( SKU.VarianMenu != 0 )
		{
			for( i=0 ; i<SKU.VarianMenu.length ; i++ )
			{
				var varian_id 		= SKU.VarianMenu[i].varian_id;
				var varian_kode 	= SKU.VarianMenu[i].varian_kode;
				var varian_nama		= SKU.VarianMenu[i].varian_nama;
				var varian_is_wajib = SKU.VarianMenu[i].varian_is_wajib;
				
				arrVarian.push( { varian_id : varian_id, varian_kode : varian_kode, varian_nama : varian_nama, varian_is_wajib : varian_is_wajib });
			}	
		}
			
		if( SKU.VarianDetailMenu != 0 )
		{
			for( i=0 ; i<SKU.VarianDetailMenu.length ; i++ )
			{
				var varian_id 			= SKU.VarianDetailMenu[i].varian_id;
				var varian_detail_id 	= SKU.VarianDetailMenu[i].varian_detail_id;
				var varian_detail_nama 	= SKU.VarianDetailMenu[i].varian_detail_nama;
				var nama_varian_detail 	= SKU.VarianDetailMenu[i].nama_varian_detail;
				
				arrVarianDetail.push( { varian_id : varian_id, varian_detail_id : varian_detail_id, varian_detail_nama : varian_detail_nama, nama_varian_detail : nama_varian_detail } );
			}	
		}
		
		$("#cbkategori1").html('');
		$("#cbkategori2").html('');
		$("#cbkategori3").html('');
		$("#cbkategori4").html('');
		$("#cbprinciple").html('');
		$("#cbprinciplebrand").html('');
		
		$("#cbkategori1").html('<option value="">--Pilih Kategori--</option>');
		$("#cbkategori2").html('<option value="">--Pilih Sub Kategori--</option>');
		$("#cbkategori3").html('<option value="">--Pilih Jenis--</option>');
		$("#cbkategori4").html('<option value="">--Pilih Sub Jenis--</option>');
		
		$("#cbprinciple").html('<option value="">--Pilih Principle--</option>');
		$("#cbprinciplebrand").html('<option value="">--Pilih Brand--</option>');
		
		$("#updatecbkategori1").html('');
		$("#updatecbkategori2").html('');
		$("#updatecbkategori3").html('');
		$("#updatecbkategori4").html('');
		$("#updatecbprinciple").html('');
		$("#updatecbprinciplebrand").html('');
		
		$("#updatecbkategori1").html('<option value="">--Pilih Kategori--</option>');
		$("#updatecbkategori2").html('<option value="">--Pilih Sub Kategori--</option>');
		$("#updatecbkategori3").html('<option value="">--Pilih Jenis--</option>');
		$("#updatecbkategori4").html('<option value="">--Pilih Sub Jenis--</option>');
		
		$("#updatecbprinciple").html('<option value="">--Pilih Principle--</option>');
		$("#updatecbprinciplebrand").html('<option value="">--Pilih Brand--</option>');
		
		
		if( SKU.Kategori1Menu != 0 )
		{
			for( i=0 ; i<SKU.Kategori1Menu.length ; i++ )
			{
				var kategori1_id = SKU.Kategori1Menu[i].kategori_id;
				var kategori1_kode = SKU.Kategori1Menu[i].kategori_kode;
				var kategori1_nama = SKU.Kategori1Menu[i].kategori_nama;
				
				arrKategori1.push( { kategori1_id : kategori1_id, kategori1_kode : kategori1_kode, kategori1_nama : kategori1_nama } );
				
				$("#cbkategori1").append( '<option value="'+ kategori1_id +'">'+ kategori1_nama +'</option>' );
				$("#updatecbkategori1").append( '<option value="'+ kategori1_id +'">'+ kategori1_nama +'</option>' );
			}
		}
		
		if( SKU.Kategori2Menu != 0 )
		{
			for( i=0 ; i<SKU.Kategori2Menu.length ; i++ )
			{
				var kategori2_id = SKU.Kategori2Menu[i].kategori_id;
				var kategori2_kode = SKU.Kategori2Menu[i].kategori_kode;
				var kategori2_nama = SKU.Kategori2Menu[i].kategori_nama;
				
				arrKategori2.push( { kategori2_id : kategori2_id, kategori2_kode : kategori1_kode, kategori2_nama : kategori2_nama } );
				
				$("#cbkategori2").append( '<option value="'+ kategori2_id +'">'+ kategori2_nama +'</option>' );
				$("#updatecbkategori2").append( '<option value="'+ kategori2_id +'">'+ kategori2_nama +'</option>' );
			}
		}
				
		if( SKU.Kategori3Menu != 0 )
		{
			for( i=0 ; i<SKU.Kategori3Menu.length ; i++ )
			{
				var kategori3_id = SKU.Kategori3Menu[i].kategori_id;
				var kategori3_kode = SKU.Kategori3Menu[i].kategori_kode;
				var kategori3_nama = SKU.Kategori3Menu[i].kategori_nama;
				
				arrKategori3.push( { kategori3_id : kategori3_id, kategori3_kode : kategori3_kode, kategori3_nama : kategori3_nama } );
				
				$("#cbkategori3").append( '<option value="'+ kategori3_id +'">'+ kategori3_nama +'</option>' );
				$("#updatecbkategori3").append( '<option value="'+ kategori3_id +'">'+ kategori3_nama +'</option>' );
			}
		}		
		
		if( SKU.Kategori4Menu != 0 )
		{
			for( i=0 ; i<SKU.Kategori4Menu.length ; i++ )
			{
				var kategori4_id = SKU.Kategori4Menu[i].kategori_id;
				var kategori4_kode = SKU.Kategori4Menu[i].kategori_kode;
				var kategori4_nama = SKU.Kategori4Menu[i].kategori_nama;
				
				arrKategori4.push( { kategori4_id : kategori4_id, kategori4_kode : kategori4_kode, kategori4_nama : kategori4_nama } );
				
				$("#cbkategori4").append( '<option value="'+ kategori4_id +'">'+ kategori4_nama +'</option>' );
				$("#updatecbkategori4").append( '<option value="'+ kategori4_id +'">'+ kategori4_nama +'</option>' );
			}
		}	
		
		if( SKU.PrincipleMenu != 0 )
		{
			for( i=0 ; i<SKU.PrincipleMenu.length ; i++ )
			{
				var principle_id	= SKU.PrincipleMenu[i].principle_id;
				var principle_kode 	= SKU.PrincipleMenu[i].principle_kode;
				var principle_nama 	= SKU.PrincipleMenu[i].principle_nama;
				
				arrPrinciple.push( { principle_id : principle_id, principle_kode : principle_kode, principle_nama : principle_nama } );
			
				$("#cbprinciple").append( '<option value="'+ principle_id +'">'+ principle_nama +'</option>' );
				$("#updatecbprinciple").append( '<option value="'+ principle_id +'">'+ principle_nama +'</option>' );
			}
		}
		
		if( SKU.PrincipleBrandMenu != 0 )
		{
			for( i=0 ; i<SKU.PrincipleBrandMenu.length ; i++ )
			{
				var principle_brand_id = SKU.PrincipleBrandMenu[i].principle_brand_id;
				var principle_brand_nama = SKU.PrincipleBrandMenu[i].principle_brand_nama;
				
				arrPrincipleBrand.push( { principle_brand_id : principle_brand_id, principle_brand_nama : principle_brand_nama } );
				
				$("#cbprinciplebrand").append( '<option value="'+ principle_brand_id +'">'+ principle_brand_nama +'</option>' );
				$("#updatecbprinciplebrand").append( '<option value="'+ principle_brand_id +'">'+ principle_brand_nama +'</option>' );
			}
		}
		
		$("#cbs_sku_length_unit").html('');
		$("#cbs_sku_width_unit").html('');
		$("#cbs_sku_height_unit").html('');
		$("#cbs_sku_volume_unit").html('');
		//$("#cbs_sku_weight_netto_unit").html('');
		$("#cbs_sku_weight_product_unit").html('');
		$("#cbs_sku_weight_packaging_unit").html('');
		$("#cbs_sku_weight_gift_unit").html('');
		
		$("#updatecbs_sku_length_unit").html('');
		$("#updatecbs_sku_width_unit").html('');
		$("#updatecbs_sku_height_unit").html('');
		$("#updatecbs_sku_volume_unit").html('');
		//$("#updatecbs_sku_weight_netto_unit").html('');
		$("#updatecbs_sku_weight_product_unit").html('');
		$("#updatecbs_sku_weight_packaging_unit").html('');
		$("#updatecbs_sku_weight_gift_unit").html('');
		
		$("#cbaddvarian_detail_varian_unit").html('');
		$("#cbupdateaddvarian_detail_varian_unit").html('');
		
		arrSatuan = [];
		
		if( SKU.SatuanUnitBarangMenu != 0 )
		{
			for( i=0 ; i<SKU.SatuanUnitBarangMenu.length ; i++ )
			{
				var satuan_kode = SKU.SatuanUnitBarangMenu[i].satuan_kode;
				var satuan_nama = SKU.SatuanUnitBarangMenu[i].satuan_nama;
				var satuan_grup = SKU.SatuanUnitBarangMenu[i].satuan_grup;
				
				var str = '<option value="'+ satuan_nama +'">'+ satuan_nama +'</option>';
				
				if( satuan_grup == 'Panjang' )		
				{	
					$("#cbs_sku_length_unit").append( str );	
					$("#updatecbs_sku_length_unit").append( str );	
				}
				else if( satuan_grup == 'Lebar' )	
				{	
					$("#cbs_sku_width_unit").append( str );	
					$("#updatecbs_sku_width_unit").append( str );	
				}
				else if( satuan_grup == 'Tinggi' )	
				{	
					$("#cbs_sku_height_unit").append( str );	
					$("#updatecbs_sku_height_unit").append( str );	
				}
				else if( satuan_grup == 'Volume' )	
				{	
					$("#cbs_sku_volume_unit").append( str );	
					$("#updatecbs_sku_volume_unit").append( str );	
				}
				else if( satuan_grup == 'Berat' )	
				{
					//$("#cbs_sku_weight_netto_unit").append( str );	
					$("#cbs_sku_weight_product_unit").append( str );	
					$("#cbs_sku_weight_packaging_unit").append( str );	
					$("#cbs_sku_weight_gift_unit").append( str );		
					$("#cbaddvarian_detail_varian_unit").append( str );		
					
					//$("#cbs_sku_weight_netto_unit").append( str );	
					$("#updatecbs_sku_weight_product_unit").append( str );	
					$("#updatecbs_sku_weight_packaging_unit").append( str );	
					$("#updatecbs_sku_weight_gift_unit").append( str );		
					$("#cbupdateaddvarian_detail_varian_unit").append( str );		
				}
				
				arrSatuan.push( { satuan_kode : satuan_kode, satuan_nama : satuan_nama, satuan_grup: satuan_grup } );
			}
		}
		
	}
	
	function ChVarianMenu( JSONSKU )
	{
		var SKU = JSON.parse( JSONSKU );
		
		strdatavarian = '';
		strdatavarian_detail = '';
		
		
		
		$("#divvarianwajib").html('');
		$("#divvarian").html('');
		
		if( SKU.SKU_Varian_WajibMenu != 0 )
		{
			var NamaVarian = '';
			var countheader = 0;
			
			for( i=0 ; i<SKU.SKU_Varian_WajibMenu.length ; i++ )
			{
				var sku_varian_id 		= SKU.SKU_Varian_WajibMenu[i].sku_varian_id;
				var sku_id				= SKU.SKU_Varian_WajibMenu[i].sku_id;
				var var_ID   			= SKU.SKU_Varian_WajibMenu[i].var_id;
				var varian_id   		= SKU.SKU_Varian_WajibMenu[i].varian_id;
				var varian_nama			= SKU.SKU_Varian_WajibMenu[i].varian_nama;
				var varian_is_wajib		= SKU.SKU_Varian_WajibMenu[i].varian_is_wajib;
				var varian_detail_id	= SKU.SKU_Varian_WajibMenu[i].varian_detail_id;
				var varian_detail_nama	= SKU.SKU_Varian_WajibMenu[i].varian_detail_nama;
				var sku_induk_id		= SKU.SKU_Varian_WajibMenu[i].sku_induk_id;

				var isHeader = 0;

				var strheader = '';
				var strfooter = '';
				
				
				if( NamaVarian != varian_nama )
				{
					countheader = countheader + 1;
					
					var strvarianwajib = '';
					strvarianwajib = strvarianwajib + '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 varianheaderwajib" style="padding: 10px; margin-bottom: 10px; background-color: #dddddd;" id="varianheaderwajib'+ countheader +'">';
					strvarianwajib = strvarianwajib + '	<div class="row">';
					strvarianwajib = strvarianwajib + '		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">';
					strvarianwajib = strvarianwajib + '			<input type="hidden" 	id="hdvarianwajibidx_'+ countheader +'" 	class="form-control hdvarianwajibidx" value="'+ countheader +'" />';
					strvarianwajib = strvarianwajib + '			<input type="hidden" 	id="hdvarianwajib_'+ countheader +'" 		class="form-control hdvarianwajib" value="'+ varian_id +'" />';
					strvarianwajib = strvarianwajib + '			<input type="hidden" 	id="hdvariannamawajib_'+ countheader +'" 	class="form-control hdvariannamawajib" value="'+ varian_nama +'" />';
					strvarianwajib = strvarianwajib + '			<input type="text" 		id="txtvarianwajib_'+ countheader +'" 		class="form-control txtvarianwajib" value="'+ varian_nama +'" disabled="disabled" />';
					strvarianwajib = strvarianwajib + '		</div>';
					strvarianwajib = strvarianwajib + '	</div>';
				
					strvarianwajib = strvarianwajib + '	<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-horizontal form-label-left" id="divvarianwajibdetail'+ countheader +'">';
					strvarianwajib = strvarianwajib + '		<label class="control-label col-xs-4 col-sm-3 col-md-3 col-lg-3 col-xl-3">Jenis</label>';
					strvarianwajib = strvarianwajib + '		<div class="col-xs-8 col-sm-9 col-md-9 col-lg-9 col-xl-9" id="varianwajibdetail_'+ countheader +'">';
					strvarianwajib = strvarianwajib + '		</div>';
					strvarianwajib = strvarianwajib + '	</div>';
					strvarianwajib = strvarianwajib + '	<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">';
					strvarianwajib = strvarianwajib + ' 	<button type="button" class="form-control btn btn-primary" onclick="TambahVarianWajibDetail('+ countheader +')">';
					strvarianwajib = strvarianwajib + '			<i class="fa fa-plus"></i> Tambah Jenis';
					strvarianwajib = strvarianwajib + '		</button>';
					strvarianwajib = strvarianwajib + '	</div>';
					strvarianwajib = strvarianwajib + '</div>';
					
					$("#divvarianwajib").append( strvarianwajib );
				
				}
				
				var strvarianwajibdetail = '';
				
				if( var_id == varian_id )
				{
					strvarianwajibdetail = strvarianwajibdetail + '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 divrowvarianwajib'+ countheader +'" id="divrowvarianwajib'+ countheader +'">';
					strvarianwajibdetail = strvarianwajibdetail + '	<div class="input-group">';
					strvarianwajibdetail = strvarianwajibdetail + '		<select class="form-control cbvarianwajibdetail cbvarianwajibdetail__'+ countheader +'">';
						
					strvarianwajibdetail = strvarianwajibdetail + '			<option value="">Pilih jenis</option>';
					for( j=0; j<arrVarianDetail.length ; j++ )
					{
						
						var Dvarian_id 			= arrVarianDetail[j].varian_id;
						var Dvarian_detail_id 	= arrVarianDetail[j].varian_detail_id;
						var Dvarian_detail_nama = arrVarianDetail[j].varian_detail_nama;
						
						if( varian_detail_id == Dvarian_detail_id )	{	var isselected = 'selected';	}
						else 										{	var isselected = '';			}
						
						if( var_id == varian_id )
						{
							
							strvarianwajibdetail = strvarianwajibdetail + '<option value="'+ Dvarian_id +' | '+ Dvarian_detail_id +'" '+ isselected +'>'+ Dvarian_detail_nama +'</option>';
						}
					}
					
					strvarianwajibdetail = strvarianwajibdetail + '		</select>';
					strvarianwajibdetail = strvarianwajibdetail + '		<div class="input-group-btn">';
					strvarianwajibdetail = strvarianwajibdetail + '			<button type="button" class="btn btn-danger" onclick="HapusVarianWajibDetail('+ countheader +')"><i class="fa fa-trash"></i></button>';
					strvarianwajibdetail = strvarianwajibdetail + '		</div>';
					strvarianwajibdetail = strvarianwajibdetail + '	</div>';
					strvarianwajibdetail = strvarianwajibdetail + '</div>';
				}
				
				$("#varianwajibdetail_"+ countheader).append( strvarianwajibdetail ); 
				
				NamaVarian = varian_nama;
				IsHeader = 1;
			
				$(".cbvarianwajibdetail__"+ countheader).css("width", "100%");
				$(".cbvarianwajibdetail__"+ countheader).select2({
					"theme": "bootstrap", "val": ""
				});
			}
		}
		
		if( SKU.SKU_Varian_NonWajibMenu != 0 )
		{
			var NamaVarianNW = '';
			var countheaderNW = 0;
			
			for( i=0 ; i<SKU.SKU_Varian_NonWajibMenu.length ; i++ )
			{
				var sku_varian_id 		= SKU.SKU_Varian_NonWajibMenu[i].sku_varian_id;
				var sku_id				= SKU.SKU_Varian_NonWajibMenu[i].sku_id;
				var var_id   			= SKU.SKU_Varian_NonWajibMenu[i].var_id;
				var varian_id   		= SKU.SKU_Varian_NonWajibMenu[i].varian_id;
				var varian_nama			= SKU.SKU_Varian_NonWajibMenu[i].varian_nama;
				var varian_is_wajib		= SKU.SKU_Varian_NonWajibMenu[i].varian_is_wajib;
				var varian_detail_id	= SKU.SKU_Varian_NonWajibMenu[i].varian_detail_id;
				var varian_detail_nama	= SKU.SKU_Varian_NonWajibMenu[i].varian_detail_nama;
				var sku_induk_id		= SKU.SKU_Varian_NonWajibMenu[i].sku_induk_id;

				var isHeader = 0;

				var strheaderNW = '';
				var strfooterNW = '';
				
				
				if( NamaVarianNW != varian_nama )
				{
					countheaderNW = countheaderNW + 1;
					
					var strvarianwajibNW = '';
					strvarianwajibNW = strvarianwajibNW + '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 varianheadernonwajib" style="padding: 10px; margin-bottom: 10px; background-color: #dddddd;" id="varianheader'+ countheaderNW +'">';
					strvarianwajibNW = strvarianwajibNW + '	<div class="row">';
					strvarianwajibNW = strvarianwajibNW + '		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">';
					strvarianwajibNW = strvarianwajibNW + '			<input type="hidden" 	id="hdvarianidx_'+ countheaderNW +'" 	class="form-control hdvarianidx" value="'+ countheaderNW +'" />';
					strvarianwajibNW = strvarianwajibNW + '			<input type="hidden" 	id="hdvarian_'+ countheaderNW +'" 	class="form-control hdvarian" value="'+ varian_id +'" />';
					strvarianwajibNW = strvarianwajibNW + '			<input type="hidden" 	id="hdvariannama_'+ countheaderNW +'" class="form-control hdvariannama" value="'+ varian_nama +'" />';
					strvarianwajibNW = strvarianwajibNW + '			<input type="text" 		id="txtvarian_'+ countheaderNW +'" 	class="form-control txtvarian" value="'+ varian_nama +'" disabled="disabled" />';
					strvarianwajibNW = strvarianwajibNW + '		</div>';
					strvarianwajibNW = strvarianwajibNW + '	</div>';
				
					strvarianwajibNW = strvarianwajibNW + '	<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-horizontal form-label-left" id="divvarian_detail'+ countheaderNW +'">';
					strvarianwajibNW = strvarianwajibNW + '		<label class="control-label col-xs-4 col-sm-3 col-md-3 col-lg-3 col-xl-3">Jenis</label>';
					strvarianwajibNW = strvarianwajibNW + '		<div class="col-xs-8 col-sm-9 col-md-9 col-lg-9 col-xl-9" id="varian_detail_'+ countheaderNW +'">';
					strvarianwajibNW = strvarianwajibNW + '		</div>';
					strvarianwajibNW = strvarianwajibNW + '	</div>';
					strvarianwajibNW = strvarianwajibNW + '	<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">';
					strvarianwajibNW = strvarianwajibNW + ' 	<button type="button" class="form-control btn btn-primary" onclick="TambahVarianDetail('+ countheaderNW +')">';
					strvarianwajibNW = strvarianwajibNW + '			<i class="fa fa-plus"></i> Tambah Jenis';
					strvarianwajibNW = strvarianwajibNW + '		</button>';
					strvarianwajibNW = strvarianwajibNW + '	</div>';
					strvarianwajibNW = strvarianwajibNW + '</div>';
					
					$("#divvarian").append( strvarianwajibNW );
				
				}
				
				var strvarianwajibdetailNW = '';
				
				if( var_id == varian_id )
				{
					strvarianwajibdetailNW = strvarianwajibdetailNW + '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 divrowvarian'+ countheaderNW +'" id="divrowvarian'+ countheaderNW +'">';
					strvarianwajibdetailNW = strvarianwajibdetailNW + '	<div class="input-group">';
					strvarianwajibdetailNW = strvarianwajibdetailNW + '		<select class="form-control cbvarian cbvarian__'+ countheaderNW +'">';
						
					strvarianwajibdetailNW = strvarianwajibdetailNW + '			<option value="">Pilih jenis</option>';
					for( j=0; j<arrVarianDetail.length ; j++ )
					{
						
						var Dvarian_id 			= arrVarianDetail[j].varian_id;
						var Dvarian_detail_id 	= arrVarianDetail[j].varian_detail_id;
						var Dvarian_detail_nama = arrVarianDetail[j].varian_detail_nama;
						
						if( varian_detail_id == Dvarian_detail_id )	{	var isselected = 'selected';	}
						else 										{	var isselected = '';			}
						
						if( var_id == varian_id )
						{
							
							strvarianwajibdetailNW = strvarianwajibdetailNW + '<option value="'+ Dvarian_id +' | '+ Dvarian_detail_id +'" '+ isselected +'>'+ Dvarian_detail_nama +'</option>';
						}
					}
					
					strvarianwajibdetailNW = strvarianwajibdetailNW + '		</select>';
					strvarianwajibdetailNW = strvarianwajibdetailNW + '		<div class="input-group-btn">';
					strvarianwajibdetailNW = strvarianwajibdetailNW + '			<button type="button" class="btn btn-danger" onclick="HapusVarianDetail('+ countheaderNW +')"><i class="fa fa-trash"></i></button>';
					strvarianwajibdetailNW = strvarianwajibdetailNW + '		</div>';
					strvarianwajibdetailNW = strvarianwajibdetailNW + '	</div>';
					strvarianwajibdetailNW = strvarianwajibdetailNW + '</div>';
				}
				
				$("#varian_detail_"+ countheaderNW).append( strvarianwajibdetailNW ); 
				
				NamaVarianNW = varian_nama;
				IsHeaderNW = 1;
			
				$(".cbvarian_detail__"+ countheaderNW).css("width", "100%");
				$(".cbvarian_detail__"+ countheaderNW).select2({
					"theme": "bootstrap", "val": ""
				});
			}
		}
		
		$("#step2").removeAttr('style');		
		$("#step3").removeAttr('style');				
		//$("#step4").removeAttr('style');	
		
		Proses( 1 );
	}
	
	$("#btnkembalike1").click
	(
		function()
		{
			Proses( 0 );
		}
	);
	
	$("#btnkembalike3").click
	(
		function()
		{
			Proses( 2 );
		}
	);
	
	function TambahVarianWajibDetail( countheader )
	{
		var Idx = parseInt( countheader ) - 1;
		var varian_id = $(".hdvarianwajib").eq(Idx).val();
		var VNama = $(".hdvariannamawajib").eq(Idx).val();
	
		var rand2 = Math.random() * 1000000000000000000000;
		
		if( varian_id != '' )
		{
			var strvarianwajibdetail = '';
			strvarianwajibdetail = strvarianwajibdetail + '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 divrowvarianwajib_'+ rand2 +'" id="divrowvarianwajib'+ rand2 +'">';
			strvarianwajibdetail = strvarianwajibdetail + '	<div class="input-group">';
			strvarianwajibdetail = strvarianwajibdetail + '		<select class="form-control cbvarianwajibdetail cbvarianwajibdetail__'+ rand2 +'" id="cbvarianwajibdetail__'+ rand2 +'" onchange="CheckValuesWajib('+ rand2 +','+ rand2 +',\''+ varian_id +'\',\''+ VNama +'\')" >';

			strvarianwajibdetail = strvarianwajibdetail + '			<option value="">Pilih jenis</option>';
			strvarianwajibdetail = strvarianwajibdetail + '			<option value="ADD">**Buat Jenis Baru**</option>';
			
			for( j=0; j<arrVarianDetail.length ; j++ )
			{
				var Dvarian_id 			= arrVarianDetail[j].varian_id;
				var Dvarian_detail_id 	= arrVarianDetail[j].varian_detail_id;
				var Dvarian_detail_nama = arrVarianDetail[j].varian_detail_nama;
				var Dnama_varian_detail = arrVarianDetail[j].nama_varian_detail;
			
				if( Dvarian_id == varian_id )
				{
					
					strvarianwajibdetail = strvarianwajibdetail + '	<option value="'+ Dvarian_id +' | '+ Dvarian_detail_id +' | '+ Dnama_varian_detail +'">'+ Dnama_varian_detail +'</option>';
				}
			}
			
			strvarianwajibdetail = strvarianwajibdetail + '		</select>';
			strvarianwajibdetail = strvarianwajibdetail + '		<div class="input-group-btn">';
			strvarianwajibdetail = strvarianwajibdetail + '			<button type="button" class="btn btn-danger" onclick="HapusVarianWajibDetail('+ rand2 +')"><i class="fa fa-trash"></i></button>';
			strvarianwajibdetail = strvarianwajibdetail + '		</div>';
			strvarianwajibdetail = strvarianwajibdetail + '	</div>';
			strvarianwajibdetail = strvarianwajibdetail + '</div>';
			
			$("#varianwajibdetail_"+ countheader).append( strvarianwajibdetail ); 
			
			$("#cbvarianwajibdetail__"+ rand2).css("width", "100%");
			$("#cbvarianwajibdetail__"+ rand2).select2({
				"theme": "bootstrap", "val": ""
			});	
		}
	}
	
	function CheckValuesWajib( Idx, RandVal, ID, Nama )
	{
		$("#txtaddvarian_detail_rand").val( RandVal );
		
		$("#txtaddvarian_detail_varian_id").val( ID );
		
		$("#txtaddvarian_detail_varian_nama").val( Nama );
		
		$("#txtaddvarian_detail_varian_detailnama").val('');
		
		if( Nama == 'Berat Produk' ){	$("#isvarianberat").removeAttr('style');			}
		else						{	$("#isvarianberat").attr('style','display: none;');	}
		
		var val = $("#cbvarianwajibdetail__"+ RandVal +" option:selected").val();
		
		if( val == 'ADD' )
		{
			$("#previewaddvariandetail").modal('show');
		}
	}
	
	function CheckValues( Idx, RandVal, ID, Nama )
	{
		$("#txtaddvarian_detail_rand").val( RandVal );
		
		$("#txtaddvarian_detail_varian_id").val( ID );
		
		$("#txtaddvarian_detail_varian_nama").val( Nama );
		
		$("#txtaddvarian_detail_varian_detail_nama").val('');
		
		if( Nama == 'Berat Produk' ){	$("#isvarianberat").removeAttr('style');			}
		else						{	$("#isvarianberat").attr('style','display: none;');	}
		
		
		var val = $("#cbvarian_detail__"+ RandVal +" option:selected").val();
		
		if( val == 'ADD' )
		{
			$("#previewaddvariandetail").modal('show');
		}
	}
	
	$("#btnsavevariandetail").click
	(
		function()
		{
			var Rand 				= $("#txtaddvarian_detail_rand").val();
			
			var varian_id 			= $("#txtaddvarian_detail_varian_id").val();
			var varian_detail_nama 	= $("#txtaddvarian_detail_varian_detail_nama").val();
			var varian_detail_unit 	= $("#cbaddvarian_detail_varian_unit option:selected").val();
			
			$.ajax(
			{
				type: 'POST',    
				url: "<?= base_url('SKU/SaveVarianDetail') ?>",
				data: 
				{
					varian_id : varian_id,
					varian_detail_nama : varian_detail_nama,
					varian_detail_unit : varian_detail_unit
				},
				success: function( response )
				{
					if( response == 2 || response == 3 )
					{
						var msg = 'Jenis Varian sudah ada.';
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
						var data = JSON.parse( response );
						
						var Mode = data.Mode;
						
						var varian_id = data.varian_id;
						var varian_detail_id = data.varian_detail_id;
						var varian_detail_nama = data.varian_detail_nama;
						var nama_varian_detail = data.nama_varian_detail;
						
						arrVarianDetail.push( { varian_id : varian_id, varian_detail_id : varian_detail_id, varian_detail_nama : varian_detail_nama, nama_varian_detail : nama_varian_detail } );
					
						if( Mode == 1 )	// Berat
						{
							$("#cbvarianwajibdetail__"+ Rand).append( '<option value="'+ varian_id +' | '+ varian_detail_id +' | '+ nama_varian_detail +'">'+ nama_varian_detail +'</option>' );
							
							$("#cbvarianwajibdetail__"+ Rand).val( varian_id +' | '+ varian_detail_id +' | '+ nama_varian_detail );
						}
						else if( Mode == 0 )	// Non Berat
						{
							$("#cbvarian_detail__"+ Rand).append( '<option value="'+ varian_id +' | '+ varian_detail_id +' | '+ nama_varian_detail +'">'+ nama_varian_detail +'</option>' );
							
							$("#cbvarian_detail__"+ Rand).val( varian_id +' | '+ varian_detail_id +' | '+ nama_varian_detail );
						}
						
						$("#previewaddvariandetail").modal('hide');
					}	
				}
			});	
		}
	);
	
	function HapusVarianWajibDetail( rand2 )
	{
		$(".divrowvarianwajib_"+ rand2).eq(0).remove();
	}

	function ChangeVarianWajib( countheader )
	{
		$("#varianwajibdetail_"+ countheader ).html('');
	}
	
	function TambahVarianDetail( rand )
	{
		var varian_id = $("#hdvarian_"+ rand ).val();
		var VNama = $("#hdvariannama_"+ rand ).val();
		
		var rand2 = Math.random() * 1000000000000000000000;

		if( varian_id != '' )
		{
			var strvarian_detail = '';
			strvarian_detail = strvarian_detail + '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 divrowvarian_'+ rand +' hapusdivrowvarian_'+ rand2 +'" id="divrowvarian'+ rand +'">';

			strvarian_detail = strvarian_detail + '	<div class="input-group">';

			strvarian_detail = strvarian_detail + '		<select class="form-control cbvarian_detail cbvarian_detail__'+ rand +'" id="cbvarian_detail__'+ rand2 +'" onchange="CheckValues('+ rand +','+ rand2 +',\''+ varian_id +'\',\''+ VNama +'\')">';

			strvarian_detail = strvarian_detail + '			<option value="">Pilih jenis</option>';
			strvarian_detail = strvarian_detail + '			<option value="ADD">**Buat Jenis Baru**</option>';
			
			for( j=0; j<arrVarianDetail.length ; j++ )
			{
				var Dvarian_id 			= arrVarianDetail[j].varian_id;
				var Dvarian_detail_id 	= arrVarianDetail[j].varian_detail_id;
				var Dvarian_detail_nama = arrVarianDetail[j].varian_detail_nama;
				var Dnama_varian_detail = arrVarianDetail[j].nama_varian_detail;
			
				if( Dvarian_id == varian_id )
				{
					strvarian_detail = strvarian_detail + '	<option value="'+ Dvarian_id +' | '+ Dvarian_detail_id +' | '+ Dnama_varian_detail +'">'+ Dnama_varian_detail +'</option>';
				}
			}
			
			strvarian_detail = strvarian_detail + '		</select>';
			strvarian_detail = strvarian_detail + '		<div class="input-group-btn">';
			strvarian_detail = strvarian_detail + '			<button type="button" class="btn btn-danger" onclick="HapusVarianDetail('+ rand +','+ rand2 +')"><i class="fa fa-trash"></i></button>';
			strvarian_detail = strvarian_detail + '		</div>';
			strvarian_detail = strvarian_detail + '	</div>';
			strvarian_detail = strvarian_detail + '</div>';
			
			$("#varian_detail_"+ rand).append( strvarian_detail ); 
			
			$(".cbvarian_detail__"+ rand ).css("width", "100%");
			$(".cbvarian_detail__"+ rand ).select2({
				"theme": "bootstrap", "val": ""
			});	
			
		}
	}
	
	function HapusVarianDetail( rand, rand2 )
	{
		$(".hapusdivrowvarian_"+ rand2).eq(0).remove();
	}
	
	function HapusVarian( rand )
	{
		$("#varianheader"+ rand).remove();
	}
	
	function ChangeVarian( countheader )
	{
		$("#varian_detail"+ countheader ).html('');
	}
	
	$("#cbkategori1").change
	(
		function()
		{
			var kategori1_id = $("#cbkategori1 option:selected").val();
			
			if( kategori1_id == '' )
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
				GetSKUKategori( 1, kategori1_id );
			}
		}
	);
		
	$("#cbkategori2").change
	(
		function()
		{
			var kategori1_id = $("#cbkategori2 option:selected").val();
			
			if( kategori1_id == '' )
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
				GetSKUKategori( 2, kategori1_id );
			}
		}
	);
	
	$("#cbkategori3").change
	(
		function()
		{
			var kategori1_id = $("#cbkategori3 option:selected").val();
			
			if( kategori1_id == '' )
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
				GetSKUKategori( 3, kategori1_id );
			}
		}
	);
	
	function GetSKUKategori( Jenis, kategori1_id )
	{
		if( kategori1_id != '' || kategori1_id == 0 )
		{
			$.ajax(
			{
				type: 'POST',    
				url: "<?= base_url('SKU/GetKategori') ?>",
				data: {	
						Jenis : Jenis,
						kategori1_id : kategori1_id
						},
				success: function( response )
				{
					ChSKUCategory( response, Jenis );
				}
			});
		}
		else if( kategori1_id == '' )
		{
			ChSKUCategory( 0, Jenis );
		}
	}
	
	function ChSKUCategory( JSONJenisKategori, Jenis )
	{
		if( JSONJenisKategori != 0)
		{
			var Kategori = JSON.parse( JSONJenisKategori );
			
			if( Jenis == 0 )		
			{	
				$("#cbkategori1").html('');
				$("#cbkategori1").append( '<option value="" selected>--Pilih Kategori--</option>' );	
			}
			if( Jenis == 1 )		
			{	
				$("#kat2").removeAttr('style');
				$("#cbkategori2").html('');
				$("#cbkategori2").append( '<option value="" selected>--Pilih Sub Kategori--</option>' );
				
				$("#kat3").attr('style','display: none;');
				$("#cbkategori3").html('');
				$("#cbkategori3").append( '<option value="" selected>--Pilih Sub Kategori 2--</option>' );	
			}
			if( Jenis == 2 )		
			{	
				$("#kat3").removeAttr('style');
				$("#cbkategori3").html('');
				$("#cbkategori3").append( '<option value="" selected>--Pilih Sub Kategori 2--</option>' );	
				
				$("#kat4").attr('style','display: none;');
				$("#cbkategori4").html('');
				$("#cbkategori4").append( '<option value="" selected>--Pilih Sub Kategori 3--</option>' );	
			}
			else if( Jenis == 3 )	
			{	
				$("#kat4").removeAttr('style');
				$("#cbkategori4").html('');
				$("#cbkategori4").append( '<option value="" selected>--Pilih Sub Kategori 3--</option>' );	
			}
			
			if( Kategori.JenisKategori.length != 0 )
			{
				for( i=0 ; i<Kategori.JenisKategori.length ; i++ )
				{
					var kategori_id = Kategori.JenisKategori[i].kategori_id;
					var kategori_kode = Kategori.JenisKategori[i].kategori_kode;
					var kategori_nama = Kategori.JenisKategori[i].kategori_nama;
					
					if( Jenis == 0 )		
					{	
						$("#cbkategori1").append( '<option value="'+ kategori_id +'">'+ kategori_nama +'</option>' );	
					}
					else if( Jenis == 1 )	
					{	
						$("#cbkategori2").append( '<option value="'+ kategori_id +'">'+ kategori_nama +'</option>' );	
					}
					else if( Jenis == 2 )		
					{	
						$("#cbkategori3").append( '<option value="'+ kategori_id +'">'+ kategori_nama +'</option>' );	
					}
					else if( Jenis == 3 )		
					{	
						$("#cbkategori4").append( '<option value="'+ kategori_id +'">'+ kategori_nama +'</option>' );	
					}
					else if( Jenis == 9 )	
					{	
						$("#cbprinciple").append( '<option value="'+ kategori_id +'">'+ kategori_nama +'</option>' );	
					}
					else if( Jenis == 10 )	
					{	
						$("#cbprinciplebrand").append( '<option value="'+ kategori_id +'">'+ kategori_nama +'</option>' );	
					}
				}
			}
		}
		else
		{
			$("#kat1").attr('style','display: none;');
			$("#kat2").attr('style','display: none;');
			$("#kat3").attr('style','display: none;');
			$("#kat4").attr('style','display: none;');
			$("#pri").attr('style','display: none;');
			$("#bra").attr('style','display: none;');
			/*
			if( Jenis == 0 )		
			{	
				$("#kat1").removeAttr('style');
			}
			else if( Jenis == 1 )	
			{	
				$("#kat2").removeAttr('style');
			}
			if( Jenis == 2 )		
			{	
				$("#kat3").removeAttr('style');	
			}
			else if( Jenis == 4 )	
			{	
				$("#pri").removeAttr('style');
			}
			*/
		}
	}
	
	$("#txtsku_induk_ppn_persen").change
	(
		function()
		{
			var Val = $("#txtsku_induk_ppn_persen").val();
		
			if( Val < 0 )		{ 	$("#txtsku_induk_ppn_persen").val(0);	}
			else if( Val > 100 ){ 	$("#txtsku_induk_ppn_persen").val(100);	}
			else if( Val == '' ){ 	$("#txtsku_induk_ppn_persen").val(0);	}
		}
	);
	
	$("#txtsku_induk_ppnbm_persen").change
	(
		function()
		{
			var Val = $("#txtsku_induk_ppnbm_persen").val();
		
			if( Val < 0 )		{ 	$("#txtsku_induk_ppnbm_persen").val(0);	}
			else if( Val > 100 ){ 	$("#txtsku_induk_ppnbm_persen").val(100);	}
			else if( Val == '' ){ 	$("#txtsku_induk_ppnbm_persen").val(0);	}
		}
	);
	
	$("#btnproses1").click
	(
		function()
		{
			var sku_induk_id = $("#cbsku_induk option:selected").val();
				
			if( sku_induk_id == '' )
			{
				var msg = 'SKU Induk harap diisi.';
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
				var Kategori1 = $("#cbkategori1 option:selected").val();
				var Kategori2 = $("#cbkategori2 option:selected").val();
				var Kategori3 = $("#cbkategori3 option:selected").val();
				var Kategori4 = $("#cbkategori4 option:selected").val();
				
				var Kategori9 = $("#cbprinciple option:selected").val();
				var Kategori10 = $("#cbprinciplebrand option:selected").val();
				
				if( Kategori1 == '' || Kategori9 == '' || Kategori10 == '' )
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
				}
				else
				{
					
					var sku_induk_id = $("#cbsku_induk option:selected").val();

					if( sku_induk_id == '' )	
					{	
						$("#step2").attr('style','display: none;');	
						$("#step3").attr('style','display: none;');	
						$("#step4").attr('style','display: none;');	
					}
					else					
					{				
									
						$.ajax(
						{
							type: 'POST',
							url: "<?= base_url('SKU/GetVarianMenu') ?>",
							data: 
							{
								sku_induk_id : sku_induk_id
							},
							success: function( response )
							{
								ChVarianMenu( response );
							}
						});
					}
				}
			}
		}
	);
	
	$("#btnproses2").click
	(
		function()
		{
			$("#btnproses2").prop('disabled', true );
			
			var lvarian = $(".txtvarianwajib").length;
			
			var lvarian_detail = $(".cbvarian_detail").length;
			
			var arrvarian_id = [];
			var arrDataVarian = [];
			
			var numerror = 0;
			
			for( i=0 ; i<lvarian ; i++ )
			{
				var varian_id 	= $(".hdvarianwajib").eq(i).val();
				var varian_nama = $(".hdvariannamawajib").eq(i).val();
				var NUM_ID 		= $(".hdvarianwajibidx").eq(i).val();
				
				//var varian_id = $(".cbvarian").eq(i).val();
				//var varian_nama = $(".cbvarian").eq(i).html();
				//var NUM_ID = $(".cbvarian").eq(i).attr('class');
				//NUM_ID = NUM_ID.split('_');
				//NUM_ID = NUM_ID[1];
				//NUM_ID = NUM_ID.split(' ');
				//NUM_ID = NUM_ID[0];
				var strlen = $("#varianwajibdetail_"+ NUM_ID ).html();
				
				if( strlen <= 2 )
				{
					numerror = 6;
				}
				else
				{
					if( !arrvarian_id.includes( varian_id ) )
					{
						arrvarian_id.push( varian_id );
						
						arrDataVarian.push( { varian_id : varian_id, varian_nama : varian_nama } );
					}
					else
					{
						numerror = 1;
					}
				}
			}
			
			var lvarian = $(".cbvarian").length;
			
			for( i=0 ; i<lvarian ; i++ )
			{
				//var varian_id 	= $(".hdvarianwajib").eq(i).val();
				//var varian_nama = $(".hdvariannamawajib").eq(i).val();
				//var NUM_ID 		= $(".hdvarianwajibidx").eq(i).val();
				
				var varian_id = $(".cbvarian").eq(i).val();
				var varian_nama = $(".cbvarian").eq(i).html();
				
				var NUM_ID = $(".cbvarian").eq(i).attr('class');
				NUM_ID = NUM_ID.split('_');
				NUM_ID = NUM_ID[1];
				NUM_ID = NUM_ID.split(' ');
				NUM_ID = NUM_ID[0];
				
				if( $(".cbvarian_detail").length == 0 )
				{
					numerror = 6;
				}
				else
				{
					if( !arrvarian_id.includes( varian_id ) )
					{
						arrvarian_id.push( varian_id );
						
						arrDataVarian.push( { varian_id : varian_id, varian_nama : varian_nama } );
					}
					else
					{
						numerror = 1;
					}
				}
			}
			
			if( numerror == 1 )
			{
				var msg = 'Ada Varian yang Kembar.';
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
			else if( numerror == 6 )
			{
				var msg = 'Semua Varian harus terisi dan punya Jenis Varian.';
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
				// Wajib
				var arrIDVarianWajibDetail = [];
				
				var lvarianwajibdetail = $(".cbvarianwajibdetail").length;
				
				for( i=0 ; i<lvarianwajibdetail ; i++ )
				{
					var ID = $(".cbvarianwajibdetail").eq(i).attr('class');
					
					ID = ID.split('__');
					ID = ID[1];
					ID = ID.split(' ');
					
					NUMBER_ID = ID[0];
					
					if( !arrIDVarianWajibDetail.includes( NUMBER_ID ) )
					{
						arrIDVarianWajibDetail.push( NUMBER_ID );
					}
				}
				
				var arrVarianWajibDetail_ID = [];
				arrDataVarianWajibDetail = [];
				
				if( arrIDVarianWajibDetail.length == 0 )
				{
					var msg = 'Tidak ada Detail untuk Varian (Wajib).';
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
					for( i=0 ; i<arrIDVarianWajibDetail.length ; i++ )
					{
						var NUM_ID = arrIDVarianWajibDetail[i];
						
						var cbvarianwajibdetail = $(".cbvarianwajibdetail__"+ NUM_ID ).length;
						
						
						for( j=0 ; j<cbvarianwajibdetail ; j++ )
						{
							var VarianWajibDetail = $(".cbvarianwajibdetail__"+ NUM_ID).eq(j).val();
							
							if( VarianWajibDetail == '' )
							{
								numerror = 5;
							}
							else
							{
								VarianWajibDetail = VarianWajibDetail.split(' | ');
								
								varian_id = VarianWajibDetail[0];
								varian_detail_id = VarianWajibDetail[1];
								nama_varian_detail = VarianWajibDetail[2];
								
								if( !arrVarianWajibDetail_ID.includes( varian_detail_id ) )
								{
									arrVarianWajibDetail_ID.push( varian_detail_id );
									
									arrDataVarianWajibDetail.push( { varian_id : varian_id, varian_urut: parseInt(i)+1, varian_detail_id : varian_detail_id, varian_detail_urut : parseInt(j)+1 } );
								}
								else
								{
									numerror = 2;
								}
							}
						}
					}
						
					if( numerror == 2 )
					{
						var msg = 'Ada Jenis Varian (Wajib) yang Kembar.';
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
					else if( numerror == 5 )
					{
						var msg = 'Semua Jenis Varian (Wajib) harus terisi.';
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
							
						// Non Wajib
						var arrvarian_detail_id = [];
						//var arrDataVarianDetail = [];
						
						
						var arrIDVarianDetail = [];
						
						var lvarian_detail = $(".cbvarian_detail").length;
						
						for( i=0 ; i<lvarian_detail ; i++ )
						{
							var ID = $(".cbvarian_detail").eq(i).attr('class');
							
							ID = ID.split('__');
							ID = ID[1];
							ID = ID.split(' ');
							
							NUMBER_ID = ID[0];
							
							if( !arrIDVarianDetail.includes( NUMBER_ID ) )
							{
								arrIDVarianDetail.push( NUMBER_ID );
							}
						}
						
						if( arrIDVarianDetail.length == 0 )
						{
							/*
							var msg = 'Tidak ada Detail untuk Varian (Opsional).';
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
							});*/
							
							var sku_induk_id = $("#cbsku_induk option:selected").val();
							
							$.ajax(
							{
								type: 'POST',    
								url: "<?= base_url('SKU/GenerateStringSKU') ?>",
								data : 
								{
									sku_induk_id 				: sku_induk_id, 
									arrDataVarianWajibDetail 	: arrDataVarianWajibDetail,
									arrDataVarianDetail 		: 0
								},
								success: function( response )
								{
									ChGeneratedStringSKU( response );
								}
							});	
						}
						else
						{
							arrDataVarianDetail = [];
							
							for( i=0 ; i<arrIDVarianDetail.length ; i++ )
							{
								var NUM_ID = arrIDVarianDetail[i];
								
								var cbvarian_detail = $(".cbvarian_detail__"+ NUM_ID ).length;
								
								
								for( j=0 ; j<cbvarian_detail ; j++ )
								{
									var VarianDetail = $(".cbvarian_detail__"+ NUM_ID).eq(j).val();
									
									if( VarianDetail == '' )
									{
										numerror = 4;
									}
									else
									{
										VarianDetail = VarianDetail.split(' | ');
										
										varian_id = VarianDetail[0];
										varian_detail_id = VarianDetail[1];
										
										if( !arrvarian_detail_id.includes( varian_detail_id ) )
										{
											arrvarian_detail_id.push( varian_detail_id );
											arrDataVarianDetail.push( { varian_id : varian_id, varian_urut: parseInt(i)+2, varian_detail_id : varian_detail_id, varian_detail_urut: parseInt(j)+1 } );
											
										}
										else
										{
											numerror = 3;
										}
									}
								}
							}
								
							if( numerror == 3 )
							{
								var msg = 'Ada Jenis Varian (Opsional) yang Kembar.';
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
							else if( numerror == 4 )
							{
								var msg = 'Semua Jenis Varian harus terisi.';
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
								var sku_induk_id = $("#cbsku_induk option:selected").val();
							
								$.ajax(
								{
									type: 'POST',    
									url: "<?= base_url('SKU/GenerateStringSKU') ?>",
									data : 
									{
										sku_induk_id 				: sku_induk_id, 
										arrDataVarianWajibDetail 	: arrDataVarianWajibDetail,
										arrDataVarianDetail 		: arrDataVarianDetail
									},
									success: function( response )
									{
										ChGeneratedStringSKU( response );
									}
								});	
							}
						}
						
					}
				}
			}
			
			$("#btnproses2").prop('disabled', false );
		}
	);
	
	function ChGeneratedStringSKU( JSONStringSKU )
	{
		var StringSKU = JSON.parse( JSONStringSKU );
		
		if( StringSKU.StringSKUMenu.length > 0 )
		{
			if( $.fn.DataTable.isDataTable('#tablegeneratedstringsku') ) 
			{
				$('#tablegeneratedstringsku').DataTable().destroy();
			}

			$('#tablegeneratedstringsku > tbody').empty();
			
			/*var strSKU_PPh = '';
			strSKU_PPh = strSKU_PPh + '<option value="1">Include</option>';
			strSKU_PPh = strSKU_PPh + '<option value="2">Exclude</option>';
			strSKU_PPh = strSKU_PPh + '<option value="3">None</option>';
			*/
			
			$(".cbsku_kondisi").html('');
			
			var strsku_kondisi = '';
			strsku_kondisi = strsku_kondisi + '<option value="Baru">Baru</option>';
			strsku_kondisi = strsku_kondisi + '<option value="Bekas">Bekas</option>';
			
			var strSatuanPanjang = '';
			var strSatuanLebar = '';
			var strSatuanTinggi = '';
			var strSatuanVolume = '';
			var strSatuanBerat = '';
			for( i=0 ; i< arrSatuan.length ; i++ )
			{
				var satuan_kode 	= arrSatuan[i].satuan_kode;
				var satuan_nama 	= arrSatuan[i].satuan_nama;
				var satuan_grup		= arrSatuan[i].satuan_grup;
				
				if		( satuan_grup == 'Panjang')	{	strSatuanPanjang 	= strSatuanPanjang + '<option value="'+ satuan_nama +'">'+ satuan_nama +'</option>';	}
				else if	( satuan_grup == 'Lebar')	{	strSatuanLebar 		= strSatuanLebar + '<option value="'+ satuan_nama +'">'+ satuan_nama +'</option>';	}
				else if	( satuan_grup == 'Tinggi')	{	strSatuanTinggi 	= strSatuanTinggi + '<option value="'+ satuan_nama +'">'+ satuan_nama +'</option>';	}
				else if	( satuan_grup == 'Volume')	{	strSatuanVolume 	= strSatuanVolume + '<option value="'+ satuan_nama +'">'+ satuan_nama +'</option>';	}
				else if	( satuan_grup == 'Berat')	{	strSatuanBerat 		= strSatuanBerat + '<option value="'+ satuan_nama +'">'+ satuan_nama +'</option>';	}
			}
			
			for( i=0 ; i< StringSKU.StringSKUMenu.length ; i++ )
			{
				var RandBosnetID = Math.random() * 1000000000000000000;
				
				var sku_nama_produk = StringSKU.StringSKUMenu[i].sku_nama_produk;
				var sku_kode = StringSKU.StringSKUMenu[i].sku_kode;
				
				var kemasan = StringSKU.StringSKUMenu[i].kemasan;
				
				var berat = StringSKU.StringSKUMenu[i].berat;
				var unit = StringSKU.StringSKUMenu[i].unit;
				
				var nama1 = StringSKU.StringSKUMenu[i].nama1; // varian wajib
				var nama2 = StringSKU.StringSKUMenu[i].nama2; // varian wajib
				var nama3 = StringSKU.StringSKUMenu[i].nama3; // varian wajib

				var nama4 = StringSKU.StringSKUMenu[i].nama4; // varian opsional
				var nama5 = StringSKU.StringSKUMenu[i].nama5; // varian opsional
				
				var str = '';
				
				str = str + '<tr>';
				str = str + '	<input type="hidden" class="hdnama1" value="'+ nama1 +'" />';							
				str = str + '	<input type="hidden" class="hdnama2" value="'+ nama2 +'" />';							
				str = str + '	<input type="hidden" class="hdnama3" value="'+ nama3 +'" />';	
				str = str + '	<input type="hidden" class="hdnama4" value="'+ nama4 +'" />';	
				str = str + '	<input type="hidden" class="hdnama5" value="'+ nama5 +'" />';	
				str = str + '	<input type="hidden" class="hdunit" value="'+ unit +'" />';	
				
				str = str + '	<td><button type="button" class="form-control btn btn-danger" onclick="HapusGenerated(this.parentNode.parentNode.rowIndex)"><i class="fa fa-times"></i></button></td>';							
				
				str = str + '	<td><button type="button" class="form-control btn btn-primary" onclick="Copas(this.parentNode.parentNode.rowIndex)"><i class="fa fa-paste"></i> &nbsp;&nbsp;<i class="fa fa-angle-right"></i></button></td>';							
				str = str + '	<input type="hidden" class="txtsku_kode" value="'+ sku_kode +'" />';							
				str = str + '	<td><input type="text" style="width: 500px;" class="txtsku_nama_produk form-control" disabled="disabled" value="'+ sku_nama_produk +'" /></td>';
				str = str + '	<td><button type="button" id="btnviewsku_bosnet_id" class="btn btn-primary btnviewsku_bosnet_id" onclick="ViewBosnet(\''+ RandBosnetID +'\',\''+ sku_nama_produk +'\')" ><i class="fa fa-pencil"></i> Isi Bosnet ID</button></td>';
				str = str + '	<td><input type="text" class="txtsku_deskripsi form-control" /></td>';
				str = str + '	<td><input type="text" class="txtsku_kemasan form-control" value="'+ kemasan +'" disabled="disabled" /></td>';
				str = str + '	<td><input type="text" class="txtsku_satuan form-control" value="'+ nama1 +'" disabled="disabled" /></td>';
				str = str + '	<td><select class="cbsku_kondisi form-control">'+ strsku_kondisi +'</select></td>';
				str = str + '	<td><input type="text" class="txtsku_origin form-control" /></td>';
				str = str + '	<td><input type="number" class="txtsku_harga_jual form-control" value="0" onchange="CheckNumberSaved( this.parentNode.parentNode.rowIndex, \'txtsku_harga_jual\' )" /></td>';
				str = str + '	<td><input type="number" class="txtsku_sales_min_qty form-control" value="1" onchange="CheckNumberSaved( this.parentNode.parentNode.rowIndex, \'txtsku_sales_min_qty\' )" /></td>';
				//str = str + '	<td><select class="cbSKU_PPh form-control">'+ strSKU_PPh +'</select></td>';
				//str = str + '	<td><input type="number" class="txtSKU_PPnBM_Persen form-control" value="0" /></td>';
				//str = str + '	<td><input type="number" class="txtSKU_PPn_Persen form-control" value="0" /></td>';
				str = str + '	<td><input type="checkbox" class="checkbox_form chsku_is_aktif" checked="checked" /></td>';
				str = str + '	<td><input type="checkbox" class="checkbox_form chsku_is_jual" checked="checked" /></td>';
				str = str + '	<td><input type="number" class="txtsku_weight_netto form-control" value="'+ berat +'" onchange="CheckNumberSaved( this.parentNode.parentNode.rowIndex, \'txtsku_weight_netto\' )" /></td>';
				str = str + '	<td><select class="cbsku_weight_netto_unit form-control">'+ strSatuanBerat +'</select></td>';
				str = str + '	<td><input type="number" class="txtsku_weight_product form-control" value="0" onchange="CheckNumberSaved( this.parentNode.parentNode.rowIndex, \'txtsku_weight_product\' )" /></td>';
				str = str + '	<td><select class="cbsku_weight_product_unit form-control">'+ strSatuanBerat +'</select></td>';
				str = str + '	<td><input type="number" class="txtsku_weight_packaging form-control" value="0" onchange="CheckNumberSaved( this.parentNode.parentNode.rowIndex, \'txtsku_weight_packaging\' )" /></td>';
				str = str + '	<td><select class="cbsku_weight_packaging_unit form-control">'+ strSatuanBerat +'</select></td>';
				str = str + '	<td><input type="number" class="txtsku_weight_gift form-control" value="0" onchange="CheckNumberSaved( this.parentNode.parentNode.rowIndex, \'txtsku_weight_gift\' )" /></td>';
				str = str + '	<td><select class="cbsku_weight_gift_unit form-control">'+ strSatuanBerat +'</select></td>';
				str = str + '	<td><input type="number" class="txtsku_length form-control" value="0" onchange="CheckNumberSaved( this.parentNode.parentNode.rowIndex, \'txtsku_length\' )" /></td>';
				str = str + '	<td><select class="cbsku_length_unit form-control">'+ strSatuanPanjang +'</select></td>';
				str = str + '	<td><input type="number" class="txtsku_width form-control" value="0" onchange="CheckNumberSaved( this.parentNode.parentNode.rowIndex, \'txtsku_width\' )" /></td>';
				str = str + '	<td><select class="cbsku_width_unit form-control">'+ strSatuanLebar +'</select></td>';
				str = str + '	<td><input type="number" class="txtsku_height form-control" value="0" onchange="CheckNumberSaved( this.parentNode.parentNode.rowIndex, \'txtsku_height\' )" /></td>';
				str = str + '	<td><select class="cbsku_height_unit form-control">'+ strSatuanTinggi +'</select></td>';
				str = str + '	<td><input type="number" class="txtsku_volume form-control" value="0" onchange="CheckNumberSaved( this.parentNode.parentNode.rowIndex, \'txtsku_volume\' )" /></td>';
				str = str + '	<td><select class="cbsku_volume_unit form-control">'+ strSatuanVolume +'</select></td>';
				str = str + '</tr>';
				
				
				$("#tablegeneratedstringsku > tbody").append( str );
				
				$(".txtsku_weight_netto").eq(i).val( berat );
				$(".cbsku_weight_netto_unit").eq(i).val( unit );
				
				$(".cbsku_weight_netto_unit").eq(i).css("width", "100%");
				$(".cbsku_weight_netto_unit").eq(i).select2({
					"theme": "bootstrap", "val": ""
				});
				
				$(".cbsku_weight_product_unit").eq(i).css("width", "100%");
				$(".cbsku_weight_product_unit").eq(i).select2({
					"theme": "bootstrap", "val": ""
				});
				
				$(".cbsku_weight_packaging_unit").eq(i).css("width", "100%");
				$(".cbsku_weight_packaging_unit").eq(i).select2({
					"theme": "bootstrap", "val": ""
				});
				
				$(".cbsku_weight_gift_unit").eq(i).css("width", "100%");
				$(".cbsku_weight_gift_unit").eq(i).select2({
					"theme": "bootstrap", "val": ""
				});
				
				$(".cbsku_height_unit").eq(i).css("width", "100%");
				$(".cbsku_height_unit").eq(i).select2({
					"theme": "bootstrap", "val": ""
				});
				
				$(".cbsku_length_unit").eq(i).css("width", "100%");
				$(".cbsku_length_unit").eq(i).select2({
					"theme": "bootstrap", "val": ""
				});
				
				$(".cbsku_volume_unit").eq(i).css("width", "100%");
				$(".cbsku_volume_unit").eq(i).select2({
					"theme": "bootstrap", "val": ""
				});
				
				$(".cbsku_width_unit").eq(i).css("width", "100%");
				$(".cbsku_width_unit").eq(i).select2({
					"theme": "bootstrap", "val": ""
				});
			}
		}
		
		$('#tablegeneratedstringsku').DataTable(
		{
			//scrollX: "100%",
			retrieve: true,
			//scrollY: "400px",
			initComplete: function (settings, json) 
			{  
				$("#tablegeneratedstringsku").wrap("<div style='overflow-y: scroll; overflow-x: auto; width: 100%; height: 400px; position:relative;'></div>");            
			},
			"dom": '<"left"f>rti',
			paging: false,
			fixedColumns:   
			{
				leftColumns: 1
			}
		});
		
		
		
		Proses( 2 );
	}
	
	function ViewBosnet( RandBosnetID, P_sku_nama_produk )
	{
		$("#txtRandBosnetID").val( RandBosnetID );
		$("#txtBosnet_sku_nama_produk").val( P_sku_nama_produk );
		
		$("#tbskubosnet tbody").html('');
			
		for( i=0 ; i<arrsku_bosnet_id.length ; i++ )
		{
			var bosnet_id = arrsku_bosnet_id[i].bosnet_id;
			var sku_nama_produk = arrsku_bosnet_id[i].sku_nama_produk;
			
			if( sku_nama_produk == P_sku_nama_produk )
			{				
				var str = ''; 
				str = str + '<tr>';
				str = str + '	<td width="80%"><input type="text" class="txtsku_bosnet_id form-control" value="'+ bosnet_id +'" /></td>';
				str = str + '	<td width="20%"><button type="button" class="btndeletebosnet_id btn btn-danger" onclick="DeleteBosnet( this.parentNode.parentNode.rowIndex )"><i class="fa fa-trash-o"></i></button></td>';
				str = str + '</tr>';
				
				$("#tbskubosnet tbody").append( str );
			}
		}
			
		$("#previewaddbosnet").modal('show');
	}
	
	$("#btntambahbosnet").click
	(
		function()
		{		
			var RandBosnetID = $("#txtRandBosnetID").val();
			
			var str = ''; 
			str = str + '<tr>';
			str = str + '	<td width="80%"><input type="text" class="txtsku_bosnet_id form-control" /></td>';
			str = str + '	<td width="20%"><button type="button" class="btndeletebosnet_id btn btn-danger" onclick="DeleteBosnet( this.parentNode.parentNode.rowIndex )"><i class="fa fa-trash-o"></i></button></td>';
			str = str + '</tr>';
			
			$("#tbskubosnet tbody").append( str );
		}
	);
	
	function DeleteBosnet( Idx )
	{
		$("#tbskubosnet tbody tr:eq("+ Idx +")").remove();
	}
	
	$("#btnsaveskubosnet_id").click
	(
		function()
		{
			var Rand = $("#txtRandBosnetID").val();
			var P_sku_nama_produk = $("#txtBosnet_sku_nama_produk").val();
			
			// bersihin dulu bosnet id nya
			for( i= arrsku_bosnet_id.length - 1 ; i>= 0 ; i-- )
			{
				var bosnet_id 		= arrsku_bosnet_id[i].bosnet_id;
				var sku_nama_produk 	= arrsku_bosnet_id[i].sku_nama_produk;
				
				if( P_sku_nama_produk == sku_nama_produk )
				{
					arrsku_bosnet_id.splice( i, 1);
				}
			}
			
			var ltbbosnet = $(".txtsku_bosnet_id").length;
			for( i=0 ; i<ltbbosnet ; i++ )
			{
				var bosnet_id = $(".txtsku_bosnet_id").eq(i).val();
				
				arrsku_bosnet_id.push( { RandID : Rand, sku_nama_produk : P_sku_nama_produk, bosnet_id : bosnet_id } );
			}
			
			$("#previewaddbosnet").modal('hide');
		}
	);
	
	function HapusGenerated( rowIdx )
	{
		$("#hdhapusidx").val( rowIdx );
		
		$("#previewhapusgeneratedsku").modal('show');
	}
	
	$("#btnyeshapusgeneratedsku").click
	(
		function()
		{
			var table = document.getElementById('tablegeneratedstringsku');

			table.deleteRow( $("#hdhapusidx").val() );
		}
	);
	
	$("#btnkembalike2").click
	(
		function()
		{
			Proses( 1 );
		}
	);
	
	$("#btnproses3").click
	(
		function()
		{
			Proses( 3 );
		}
	);
		
	$("#btnhapussalinan").click
	(
		function()
		{
			arrSalin = [];
			
			var msg = 'Data Salinan berhasil dihapus.';
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
	);
	
	$("#btnsalin").click
	(
		function()
		{
			
			//var sku_kode 			= $("#txts_sku_kode").val();							
			//var sku_nama_produk 	= $("#txts_sku_nama_produk").val();
			var sku_deskripsi 		= $("#txts_sku_deskripsi").val();
			var sku_kondisi 		= $("#cbs_sku_kondisi option:selected").val();
			var sku_origin 			= $("#txts_sku_origin").val();
			var sku_harga_jual 		= $("#txts_sku_harga_jual").val();
			var sku_sales_min_qty 	= $("#txts_sku_sales_min_qty").val();
			//var SKU_PPh 			= $("#cbs_sku_PPh").val();
			//var SKU_PPnBM_Persen 	= $("#txts_sku_PPnBM_Persen").val();
			//var SKU_PPn_Persen		= $("#txts_sku_PPn_Persen").val();
			var sku_is_aktif		= $("#chs_sku_is_aktif").prop('checked');
			var sku_is_jual			= $("#chs_sku_is_jual").prop('checked');
			//var sku_weight_netto			= $("#txts_sku_weight_netto").val();
			//var sku_weight_netto_unit		= $("#cbs_sku_weight_netto_unit option:selected").val();
			var sku_weight_product		= $("#txts_sku_weight_product").val();
			var sku_weight_product_unit	= $("#cbs_sku_weight_product_unit option:selected").val();
			var sku_weight_packaging		= $("#txts_sku_weight_packaging").val();
			var sku_weight_packaging_unit	= $("#cbs_sku_weight_packaging_unit option:selected").val();
			var sku_weight_gift			= $("#txts_sku_weight_gift").val();
			var sku_weight_gift_unit		= $("#cbs_sku_weight_gift_unit option:selected").val();
			var sku_length			= $("#txts_sku_length").val();
			var sku_length_unit 		= $("#cbs_sku_length_unit option:selected").val();
			var sku_width			= $("#txts_sku_width").val();
			var sku_width_unit		= $("#cbs_sku_width_unit option:selected").val();
			var sku_height			= $("#txts_sku_height").val();
			var sku_height_unit		= $("#cbs_sku_height_unit option:selected").val();
			var sku_volume			= $("#txts_sku_volume").val();
			var sku_volume_unit		= $("#cbs_sku_volume_unit option:selected").val();
			
			if( sku_is_aktif == true ) 	{ sku_is_aktif = 1;	}
			else					 	{ sku_is_aktif = 0;	}
			
			if( sku_is_jual == true ) 	{ sku_is_jual = 1;	}
			else					 	{ sku_is_jual = 0;	}
			
			arrSalin = [];
			
			arrSalin.push({	sku_deskripsi: sku_deskripsi, sku_kondisi: sku_kondisi, sku_origin: sku_origin, sku_harga_jual: sku_harga_jual,
							sku_sales_min_qty: sku_sales_min_qty, /*SKU_PPh: SKU_PPh, SKU_PPnBM_Persen: SKU_PPnBM_Persen, SKU_PPn_Persen: SKU_PPn_Persen, */
							sku_is_aktif: sku_is_aktif, sku_is_jual: sku_is_jual, 
							//sku_weight_netto: sku_weight_netto, sku_weight_netto_unit: sku_weight_netto_unit, 
							sku_weight_product: sku_weight_product, sku_weight_product_unit: sku_weight_product_unit, 
							sku_weight_packaging: sku_weight_packaging, sku_weight_packaging_unit: sku_weight_packaging_unit, 
							sku_weight_gift: sku_weight_gift, sku_weight_gift_unit: sku_weight_gift_unit, 
							sku_length: sku_length, sku_length_unit: sku_length_unit, sku_width: sku_width, sku_width_unit: sku_width_unit, 
							sku_height: sku_height, sku_height_unit: sku_height_unit, sku_volume: sku_volume, sku_volume_unit: sku_volume_unit });
		
			var msg = 'Data berhasil disalin.';
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
	);
	
	$("#btnsalinsemua").click
	(
		function()
		{
			$("#previewsalinsemua").modal('show');
		}
	);
	
	$("#btnyessalinsemua").click
	(
		function()
		{
			var sku_deskripsi 				= $("#txts_sku_deskripsi").val();
			var sku_kondisi 				= $("#cbs_sku_kondisi option:selected").val();
			var sku_origin 					= $("#txts_sku_Origin").val();
			var sku_harga_jual 				= $("#txts_sku_harga_jual").val();
			var sku_sales_min_qty 			= $("#txts_sku_sales_min_qty").val();
			var sku_is_aktif				= $("#chs_sku_is_aktif").prop('checked');
			var sku_is_jual					= $("#chs_sku_is_jual").prop('checked');
			var sku_weight_product			= $("#txts_sku_weight_product").val();
			var sku_weight_product_unit		= $("#cbs_sku_weight_product_unit option:selected").val();
			
			var sku_weight_packaging		= $("#txts_sku_weight_packaging").val();
			var sku_weight_packaging_unit	= $("#cbs_sku_weight_packaging_unit option:selected").val();
			var sku_weight_gift				= $("#txts_sku_weight_gift").val();
			var sku_weight_gift_unit		= $("#cbs_sku_weight_gift_unit option:selected").val();
			var sku_length					= $("#txts_sku_length").val();
			var sku_length_unit 			= $("#cbs_sku_length_unit option:selected").val();
			var sku_width					= $("#txts_sku_width").val();
			var sku_width_unit				= $("#cbs_sku_width_unit option:selected").val();
			var sku_height					= $("#txts_sku_height").val();
			var sku_height_unit				= $("#cbs_sku_height_unit option:selected").val();
			var sku_volume					= $("#txts_sku_volume").val();
			var sku_volume_unit				= $("#cbs_sku_volume_unit option:selected").val();
			
			var ldatasku = $(".txtsku_nama_produk").length;
			
			if( ldatasku == 0 )
			{
				var msg = 'Tidak ada Data pada List SKU.';
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
				for( i=0 ; i<ldatasku ; i++ )
				{
					$(".txtsku_deskripsi").eq(i).val( sku_deskripsi );
					$(".cbsku_kondisi").eq(i).val( sku_kondisi );
					$(".txtsku_origin").eq(i).val( sku_origin );
					$(".txtsku_harga_jual").eq(i).val( sku_harga_jual );
					$(".txtsku_sales_min_qty").eq(i).val( sku_sales_min_qty );
					
					$(".chsku_is_aktif").eq(i).prop('checked', sku_is_aktif );
					$(".chsku_is_jual").eq(i).prop('checked', sku_is_jual );
					
					$(".txtsku_weight_product").eq(i).val( sku_weight_product );
					$(".cbsku_weight_product_unit").eq(i).val( sku_weight_product_unit ).change();
					$(".txtsku_weight_packaging").eq(i).val( sku_weight_packaging );
					$(".cbsku_weight_packaging_unit").eq(i).val( sku_weight_packaging_unit ).change();
					$(".txtsku_weight_gift").eq(i).val( sku_weight_gift );
					$(".cbsku_weight_gift_unit").eq(i).val( sku_weight_gift_unit ).change();
					
					$(".txtsku_length").eq(i).val( sku_length );
					$(".cbsku_length_unit").eq(i).val( sku_length_unit ).change();
					$(".txtsku_width").eq(i).val( sku_width );
					$(".cbsku_width_unit").eq(i).val( sku_width_unit ).change();
					$(".txtsku_height").eq(i).val( sku_height );
					$(".cbsku_height_unit").eq(i).val( sku_height_unit ).change();
					$(".txtsku_volume").eq(i).val( sku_volume );
					$(".cbsku_volume_unit").eq(i).val( sku_volume_unit ).change();
				}
				
				var msg = 'Data berhasil disalin di semua List SKU.';
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
		}
	);
	
	function Copas( Idx )
	{
		$("#hdIdx").val( Idx );
		
		$("#previewubahsalinan").modal('show');
	}
	
	$("#btnyesubahsalinan").click
	(
		function()
		{
			var Idx = $("#hdIdx").val();
			
			var i = parseInt( Idx ) - 1;
			
			if( arrSalin.length == 0 )
			{
				var msg = 'Tidak ada data untuk disalin.';
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
				var sku_deskripsi 				= arrSalin[0].sku_deskripsi;
				var sku_kondisi 				= arrSalin[0].sku_kondisi;
				var sku_origin 					= arrSalin[0].sku_origin;
				var sku_harga_jual 				= arrSalin[0].sku_harga_jual;
				var sku_sales_min_qty 			= arrSalin[0].sku_sales_min_qty;
				
				var sku_is_aktif				= arrSalin[0].sku_is_aktif;
				var sku_is_jual					= arrSalin[0].sku_is_jual;
				
				var sku_weight_product			= arrSalin[0].sku_weight_product;
				var sku_weight_product_unit		= arrSalin[0].sku_weight_product_unit;
				var sku_weight_packaging		= arrSalin[0].sku_weight_packaging;
				var sku_weight_packaging_unit	= arrSalin[0].sku_weight_packaging_unit;
				var sku_weight_gift				= arrSalin[0].sku_weight_gift;
				var sku_weight_gift_unit		= arrSalin[0].sku_weight_gift_unit;
				
				var sku_length					= arrSalin[0].sku_length;
				var sku_length_unit 			= arrSalin[0].sku_length_unit;
				var sku_width					= arrSalin[0].sku_width;
				var sku_width_unit				= arrSalin[0].sku_width_unit;
				var sku_height					= arrSalin[0].sku_height;
				var sku_height_unit				= arrSalin[0].sku_height_unit;
				var sku_volume					= arrSalin[0].sku_volume;
				var sku_volume_unit				= arrSalin[0].sku_volume_unit;
				
				if( sku_is_aktif == 1 )	{ sku_is_aktif = true;	}
				else					{ sku_is_aktif = false;	}
				
				if( sku_is_jual == 1 )	{ sku_is_jual = true;	}
				else					{ sku_is_jual = false;	}
				
				$(".txtsku_deskripsi").eq(i).val( sku_deskripsi );
				$(".cbsku_kondisi").eq(i).val( sku_kondisi );
				$(".txtsku_origin").eq(i).val( sku_origin );
				$(".txtsku_harga_jual").eq(i).val( sku_harga_jual );
				$(".txtsku_sales_min_qty").eq(i).val( sku_sales_min_qty );
				//$(".cbSKU_PPh").eq(i).val( SKU_PPh );
				//$(".txtSKU_PPnBM_Persen").eq(i).val( SKU_PPnBM_Persen );
				//$(".txtSKU_PPn_Persen").eq(i).val( SKU_PPn_Persen );
				
				$(".chsku_is_aktif").eq(i).prop('checked', sku_is_aktif );
				$(".chsku_is_jual").eq(i).prop('checked', sku_is_jual );
				
				//$(".txtsku_weight_netto").eq(i).val( sku_weight_netto );
				//$(".cbsku_weight_netto_unit").eq(i).val( sku_weight_netto_unit );
				$(".txtsku_weight_product").eq(i).val( sku_weight_product );
				$(".cbsku_weight_product_unit").eq(i).val( sku_weight_product_unit );
				$(".txtsku_weight_packaging").eq(i).val( sku_weight_packaging );
				$(".cbsku_weight_packaging_unit").eq(i).val( sku_weight_packaging_unit );
				$(".txtsku_weight_gift").eq(i).val( sku_weight_gift );
				$(".cbsku_weight_gift_unit").eq(i).val( sku_weight_gift_unit );
				
				$(".txtsku_length").eq(i).val( sku_length );
				$(".cbsku_length_unit").eq(i).val( sku_length_unit );
				$(".txtsku_width").eq(i).val( sku_width );
				$(".cbsku_width_unit").eq(i).val( sku_width_unit );
				$(".txtsku_height").eq(i).val( sku_height );
				$(".cbsku_height_unit").eq(i).val( sku_height_unit );
				$(".txtsku_volume").eq(i).val( sku_volume );
				$(".cbsku_volume_unit").eq(i).val( sku_volume_unit );
			}
		}
	);
	
	$("#btnsimpandata").click
	(
		function()
		{
			$("#previewsimpandata").modal('show');
		}
	);
	
	$("#btnyessimpandata").click
	(
		function()
		{
			var sku_induk_keterangan = $("#txasku_induk_ket").val();
			
			var kategori1_id = $("#cbkategori1").data('kategori1_id');
			var kategori2_id = $("#cbkategori2").data('kategori2_id');
			var kategori3_id = $("#cbkategori3").data('kategori3_id');
			var kategori4_id = $("#cbkategori4").data('kategori4_id');
			
			var principle_id = $("#cbprinciple").data('principle_id');
			var principle_brand_id = $("#cbprinciplebrand").data('principle_brand_id');
			
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
			}
			else
			{
				var ldatasku = $(".txtsku_nama_produk").length;
				
				if( ldatasku == 0 )
				{
					var msg = 'Tidak ada data untuk disimpan.';
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
					var arrSKU = [];
					
					for( i=0 ; i<ldatasku ; i++ )
					{
						var nama1 = $(".hdnama1").eq(i).val();
						var nama2 = $(".hdnama2").eq(i).val();
						var nama3 = $(".hdnama3").eq(i).val();
						var nama4 = $(".hdnama4").eq(i).val();
						var nama5 = $(".hdnama5").eq(i).val();
						var unit = $(".hdunit").eq(i).val();
						
						var sku_kode = $(".txtsku_kode").eq(i).val();
						var sku_nama_produk = $(".txtsku_nama_produk").eq(i).val();
						var sku_kemasan = $(".txtsku_kemasan").eq(i).val();
						var sku_satuan = $(".txtsku_satuan").eq(i).val();
						var sku_deskripsi = $(".txtsku_deskripsi").eq(i).val();
						var sku_kondisi = $(".cbsku_kondisi").eq(i).val();
						var sku_origin = $(".txtsku_origin").eq(i).val();
						var sku_harga_jual = $(".txtsku_harga_jual").eq(i).val();
						var sku_sales_min_qty = $(".txtsku_sales_min_qty").eq(i).val();
						//var SKU_PPh = $(".cbSKU_PPh").eq(i).val();
						//var SKU_PPnBM_Persen = $(".txtSKU_PPnBM_Persen").eq(i).val();
						//var SKU_PPn_Persen = $(".txtSKU_PPn_Persen").eq(i).val();
						
						var sku_is_aktif = $(".chsku_is_aktif").eq(i).prop('checked');
						if( sku_is_aktif == true )	{ sku_is_aktif = 1;	}
						else						{ sku_is_aktif = 0;	}
						
						var sku_is_jual = $(".chsku_is_jual").eq(i).prop('checked');
						if( sku_is_jual == true )	{ sku_is_jual = 1;	}
						else						{ sku_is_jual = 0;	}
						
						var sku_weight_netto		 	= $(".txtsku_weight_netto").eq(i).val();
						var sku_weight_netto_unit 		= $(".cbsku_weight_netto_unit option:selected").eq(i).val();
						var sku_weight_product 			= $(".txtsku_weight_product").eq(i).val();
						var sku_weight_product_unit 	= $(".cbsku_weight_product_unit option:selected").eq(i).val();
						var sku_weight_packaging 		= $(".txtsku_weight_packaging").eq(i).val();
						var sku_weight_packaging_unit 	= $(".cbsku_weight_packaging_unit option:selected").eq(i).val();
						var sku_weight_gift 			= $(".txtsku_weight_gift").eq(i).val();
						var sku_weight_gift_unit 		= $(".cbsku_weight_gift_unit option:selected").eq(i).val();
						
						var sku_length 		= $(".txtsku_length").eq(i).val();
						var sku_length_unit = $(".cbsku_length_unit option:selected").eq(i).val();
						var sku_width 		= $(".txtsku_width").eq(i).val();
						var sku_width_unit 	= $(".cbsku_width_unit option:selected").eq(i).val();
						var sku_height 		= $(".txtsku_height").eq(i).val();
						var sku_height_unit = $(".cbsku_height_unit option:selected").eq(i).val();
						var sku_volume 		= $(".txtsku_volume").eq(i).val();
						var sku_volume_unit = $(".cbsku_volume_unit option:selected").eq(i).val();
						
						arrSKU.push({	nama1: nama1, nama2: nama2, nama3: nama3, nama4: nama4, nama5: nama5, unit: unit,
										sku_kode: sku_kode, sku_nama_produk: sku_nama_produk, sku_kemasan: sku_kemasan, sku_deskripsi: sku_deskripsi, sku_kondisi: sku_kondisi, sku_origin: sku_origin, sku_harga_jual: sku_harga_jual,
										sku_sales_min_qty: sku_sales_min_qty, 
										sku_is_aktif: sku_is_aktif, sku_is_jual: sku_is_jual, 
										sku_weight_netto: sku_weight_netto, sku_weight_netto_unit: sku_weight_netto_unit, 
										sku_weight_product: sku_weight_product, sku_weight_product_unit: sku_weight_product_unit, 
										sku_weight_packaging: sku_weight_packaging, sku_weight_packaging_unit: sku_weight_packaging_unit, 
										sku_weight_gift: sku_weight_gift, sku_weight_gift_unit: sku_weight_gift_unit, 
										sku_length: sku_length, sku_length_unit: sku_length_unit, sku_width: sku_width, sku_width_unit: sku_width_unit, 
										sku_height: sku_height, sku_height_unit: sku_height_unit, sku_volume: sku_volume, sku_volume_unit: sku_volume_unit });
					}
					
					var sku_induk_jenis_ppn = $("#cbsku_induk_jenis_ppn option:selected").val();
					var sku_induk_ppn_persen = $("#txtsku_induk_ppn_persen").val();
					var sku_induk_ppnbm_persen = $("#txtsku_induk_ppnbm_persen").val();
					
					var sku_induk_id = $("#cbsku_induk option:selected").val();
		
					if( kategori1_id == '' ){	 kategori1_id = '0';	}
					if( kategori2_id == '' ){	 kategori2_id = '0';	}
					if( kategori3_id == '' ){	 kategori3_id = '0';	}
					if( kategori4_id == '' ){	 kategori4_id = '0';	}
					if( principle_id == '' ){	 principle_id = '0';	}
					if( principle_brand_id == '' ){	 principle_brand_id = '0';	}
					
					$.ajax(
					{
						type: 'POST',    
						url: "<?= base_url('SKU/SaveData') ?>",
						data:
						{
							sku_induk_id 				: sku_induk_id,
							sku_induk_keterangan 		: sku_induk_keterangan,
							
							kategori1_id				: kategori1_id,
							kategori2_id				: kategori2_id,
							kategori3_id				: kategori3_id,
							kategori4_id				: kategori4_id,
							
							principle_id				: principle_id,
							principle_brand_id			: principle_brand_id,
							
							sku_induk_jenis_ppn			: sku_induk_jenis_ppn,
							sku_induk_ppn_persen		: sku_induk_ppn_persen,
							sku_induk_ppnbm_persen		: sku_induk_ppnbm_persen,
							
							arrSKU 						: arrSKU,
							arrDataVarianWajibDetail 	: arrDataVarianWajibDetail,
							arrDataVarianDetail 		: arrDataVarianDetail,
							
							arrSKUBosnetID				: arrsku_bosnet_id
						},
						success: function( response )
						{
							if(response)
							{
								var msg = 'Data berhasil disimpan.';
								var msgtype = 'success';
									
								//if (!window.__cfRLUnblockHandlers) return false;
								new PNotify
								({
									title: 'Into',
									text: msg,
									type: msgtype,
									styling: 'bootstrap3',
									delay: 3000,
									stack: stack_center
								});
								
								Proses( 0 );
								
								arrsku_bosnet_id = [];
								
								$("#MainMenu").removeAttr('style');
								$("#anylist_letter").attr('style','display: none;');	
								$("#Tambah").attr('style','display: none;');
								
								GetSKUIndukMenu();
							}
						}
					});	
				}
			}
		}
	);
	
	function Proses( Mode )
	{		
		
		$("#SKUInduk").removeClass('active');
		$("#tab1").removeClass('active');
		
		$("#VarianSKU").removeClass('active');
		$("#tab2").removeClass('active');
		
		$("#DataSKU").removeClass('active');
		$("#tab3").removeClass('active');
		
		$("#ListSKU").removeClass('active');
		$("#tab4").removeClass('active');
		
		$(window).scrollTop(0);
		
		if( Mode == 0 )
		{
			$("#SKUInduk").addClass('active');
			$("#tab1").addClass('active');
			
			if( $.fn.DataTable.isDataTable('#tablegeneratedstringsku') ) 
			{
				$('#tablegeneratedstringsku').DataTable().destroy();
			}

			$('#tablegeneratedstringsku > tbody').empty();
			
		}
		else if( Mode == 1 )
		{
			$("#VarianSKU").addClass('active');
			$("#tab2").addClass('active');
			
			if( $.fn.DataTable.isDataTable('#tablegeneratedstringsku') ) 
			{
				$('#tablegeneratedstringsku').DataTable().destroy();
			}

			$('#tablegeneratedstringsku > tbody').empty();
			
		}
		else if( Mode == 2 )
		{
			$("#DataSKU").addClass('active');
			$("#tab3").addClass('active');
		}
		else if( Mode == 3 )
		{
			$("#ListSKU").addClass('active');
			$("#tab4").addClass('active');
		}
	}
	
	function CheckNumber( ID )
	{
		var Val = $("#"+ ID ).val();
		
		if( Val < 0 || Val == '')	{  $("#"+ ID ).val(0);	}
	}
	
	function CheckNumberSaved( Idx, ID )
	{
		var Val = $("."+ ID ).eq(Idx).val();
		
		if( Val < 0 || Val == '')	{  $("."+ ID ).eq(Idx).val(0);	}
	}
	
	$("#txts_sku_harga_jual").change(		function(){	CheckNumber( "txts_sku_harga_jual" );		});
	$("#txts_sku_sales_min_qty").change(	function(){	CheckNumber( "txts_sku_sales_min_qty" );		});
	$("#txts_sku_weight_gift").change(		function(){	CheckNumber( "txts_sku_weight_gift" );		});
	$("#txts_sku_weight_packaging").change(	function(){	CheckNumber( "txts_sku_weight_packaging" );	});
	$("#txts_sku_weight_product").change(	function(){	CheckNumber( "txts_sku_weight_product" );	});
	$("#txts_sku_length").change(			function(){	CheckNumber( "txts_sku_length" );			});
	$("#txts_sku_width").change(			function(){	CheckNumber( "txts_sku_width" );			});
	$("#txts_sku_height").change(			function(){	CheckNumber( "txts_sku_height" );			});
	$("#txts_sku_volume").change(			function(){	CheckNumber( "txts_sku_volume" );			});
		
	function ResetForm()
	{
		$("#txasku_induk_ket").val('');
		$("#txts_sku_deskripsi").val('');
		$("#cbs_sku_Kondisi").prop('selectedIndex',0);
		$("#txts_sku_Origin").val('');
		$("#txts_sku_harga_jual").val(0);
		$("#txts_sku_sales_min_qty").val(1);
		$("#chs_sku_is_aktif").prop('checked', true );
		$("#chs_sku_is_jual").prop('checked', true );
		
		//$("#txts_sku_weight_netto").val(0);
		//$("#cbs_sku_weight_netto_unit").prop('selectedIndex',0);
		$("#txts_sku_weight_product").val(0);
		$("#cbs_sku_weight_product_unit").prop('selectedIndex',0);
		$("#txts_sku_weight_packaging").val(0);
		$("#cbs_sku_weight_packaging_unit").prop('selectedIndex',0);
		$("#txts_sku_weight_gift").val(0);
		$("#cbs_sku_weight_gift_unit").prop('selectedIndex',0);
		
		$("#txts_sku_length").val(0);
		$("#cbs_sku_length_unit").prop('selectedIndex',0);
		$("#txts_sku_width").val(0);
		$("#cbs_sku_width_unit").prop('selectedIndex',0);
		$("#txts_sku_height").val(0);
		$("#cbs_sku_height_unit").prop('selectedIndex',0);
		$("#txts_sku_volume").val(0);
		$("#cbs_sku_volume_unit").prop('selectedIndex',0);
		
		$("#tablegeneratedstringsku > tbody").html('');
		$("#cbkategori1").prop('selectedIndex',0).change();
		$("#kat2").attr('style','display: none;');
		$("#kat3").attr('style','display: none;');
		$("#kat4").attr('style','display: none;');
		
		$("#updatetxts_sku_deskripsi").val('');
		$("#updatecbs_sku_kondisi").prop('selectedIndex',0);
		$("#updatetxts_sku_origin").val('');
		$("#updatetxts_sku_harga_jual").val(0);
		$("#updatetxts_sku_sales_min_qty").val(1);
		$("#updatechs_sku_is_aktif").prop('checked', false );
		$("#updatechs_sku_is_jual").prop('checked', false );
		
		//$("#updatetxts_sku_weight_netto").val(0);
		//$("#updatecbs_sku_weight_netto_unit").prop('selectedIndex',0);
		$("#updatetxts_sku_weight_product").val(0);
		$("#updatecbs_sku_weight_product_unit").prop('selectedIndex',0);
		$("#updatetxts_sku_weight_packaging").val(0);
		$("#updatecbs_sku_weight_packaging_unit").prop('selectedIndex',0);
		$("#updatetxts_sku_weight_gift").val(0);
		$("#updatecbs_sku_weight_gift_unit").prop('selectedIndex',0);
		
		$("#updatetxts_sku_length").val(0);
		$("#updatecbs_sku_length_unit").prop('selectedIndex',0);
		$("#updatetxts_sku_width").val(0);
		$("#updatecbs_sku_width_unit").prop('selectedIndex',0);
		$("#updatetxts_sku_height").val(0);
		$("#updatecbs_sku_height_unit").prop('selectedIndex',0);
		$("#updatetxts_sku_volume").val(0);
		$("#updatecbs_sku_volume_unit").prop('selectedIndex',0);
	
		$("#updatetablegeneratedstringsku > tbody").html('');
		$("#updatecbkategori1").prop('selectedIndex',0).change();
		$("#updatekat2").attr('style','display: none;');
		$("#updatekat3").attr('style','display: none;');
		$("#updatekat4").attr('style','display: none;');
	}
	
	
</script>