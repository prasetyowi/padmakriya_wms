<script type="text/javascript">	
	var ljumket = 0;
	var arrVarian = [];
	var arrVarianDetail = [];
	var arrVarianDBerat = [];
	var arrVarianDKemasan = [];
	var arrVarianDSatuan = [];
	var arrKategori1 = [];
	var arrKategori2 = [];
	var arrKategori3 = [];
	var arrKategori4 = [];
	var arrKategori9 = [];
	var arrKategori10 = [];
	var arrSatuan = [];
	
	var arrSalin = [];
	var arrUpdateSalin = [];
	
	var arrDataVarianWajibDetail = [];
	var arrDataVarianDetail = [];
	
	var arrUpdateDataVarianWajibDetail = [];
	var arrUpdateDataVarianDetail = [];
	
	// Bosnet ID
	var arrSKUBosnet_ID = [];
	
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
	
	$(document).ready
	(
		function() 
		{
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
				$("#cbKategori"+ i).css("width", "100%");
				$("#cbKategori"+ i).select2({
					"theme": "bootstrap", "val": ""
				});
				
				$("#updatecbKategori"+ i).css("width", "100%");
				$("#updatecbKategori"+ i).select2({
					"theme": "bootstrap", "val": ""
				});
			}
			
			$("#cbskuinduk").css("width", "100%");
			$("#cbskuinduk").select2({
				"theme": "bootstrap", "val": ""
			});
			
			$("#updatecbskuinduk").css("width", "100%");
			$("#updatecbskuinduk").select2({
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
			arrSKUBosnet_ID = [];
			
			$("#previewkembalikelistskuinduk").modal('show');
		}
	);
	
	$("#txaskuinduk_ket").keyup
	(
		function( event )
		{
			var lket = $("#txaskuinduk_ket").val().length;
			lket = 8000 - lket;
			
			if( lket == 0 )	{	lket = '<strong style="color: red;">'+ lket +'</strong>';	}
			else			{	lket = '<strong style="color: black;">'+ lket +'</strong>';	}
			
			$("#lbjumlahket").html( 'Sisa Karakter : '+ lket );
		}
	);
	
	$("#txaskuinduk_ket").keydown
	(
		function( event )
		{
			var lket = $("#txaskuinduk_ket").val().length;
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
			$("#Tambah").attr('style','display: none;');
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
		var SKUInduk_Nama = $("#txtsearch").val();
		
		$.ajax(
		{
			type: 'POST',    
			url: "<?= base_url('SKUHadiah/GetSKUIndukMenu') ?>",
			data: 
			{
				SKUInduk_Nama : SKUInduk_Nama
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
		
		$("#SKUIndukList").html('');
		
		if( SKUInduk.SKUIndukMenu != 0 )
		{
			for( i=0 ; i< SKUInduk.SKUIndukMenu.length ; i++ )
			{
				SKUInduk_ID			= SKUInduk.SKUIndukMenu[i].SKUInduk_ID;
				SKUInduk_Kode		= SKUInduk.SKUIndukMenu[i].SKUInduk_Kode;
				SKUInduk_Nama		= SKUInduk.SKUIndukMenu[i].SKUInduk_Nama;
				SKUInduk_Keterangan	= SKUInduk.SKUIndukMenu[i].SKUInduk_Keterangan;
				
				PhotoDir			= SKUInduk.SKUIndukMenu[i].PhotoDir;
				
				if( PhotoDir == "" ){	var Photo = '<?= base_url() ?>assets/images/img.png';	}
				else				{	var Photo = '<?= base_url() ?>'+ PhotoDir;				}
				
				
				var str = '';
				
				str = str + '<div class="col-xs-6 col-sm-4 col-md-4 col-lg-3 col-xl-3 nopadding">';
				str = str + '	<div class="panel panel-primary">';
				str = str + '		<!--div class="panel-heading" align="center"><label>'+ SKUInduk_Nama +'</label></div-->';
				str = str + '		<div class="panel-body nopadding">';
				str = str + '			<div class="panelimg">';
				str = str + '				<img id="srcphoto_'+ i +'" src="'+ Photo +'?'+ new Date().getTime() +' class="img-responsive" style="width: 100%; height: 250px;">';
				str = str + '				<header>';
				str = str + '					<div class="bottomleft">';
				str = str + '						<label>'+ SKUInduk_Nama +'</label>';
				str = str + '					</div>';
				str = str + '				</header>';
				str = str + '			</div>';
				str = str + '		</div>';
				str = str + '		<div class="panel-footer">';
				str = str + '			<button type="button" class="form-control btn btn-warning btnubahskuinduk" onclick="UbahSKUInduk(\''+ SKUInduk_ID +'\', \''+ SKUInduk_Nama +'\')"><i class="fa fa-pencil"></i> Ubah</button>';
				str = str + '			<button type="button" class="form-control btn btn-primary btnaturstock" onclick="AturStock(\''+ SKUInduk_ID +'\', \''+ SKUInduk_Nama +'\')"><i class="fa fa-book"></i> Lihat Stock</button>';
				str = str + '			<button type="button" class="form-control btn btn-primary btnaturphoto" onclick="UploadPhotoMenu(\''+ SKUInduk_ID +'\', \''+ SKUInduk_Nama +'\')"><i class="fa fa-picture-o"></i> Atur Foto</button>';		
				str = str + '			<button type="button" class="form-control btn btn-primary btnaturhadiah" onclick="AturHadiah(\''+ SKUInduk_ID +'\', \''+ SKUInduk_Nama +'\')"><i class="fa fa-gift-o"></i> Atur Hadiah</button>';		
				str = str + '		</div>';
				str = str + '	</div>';
				str = str + '</div>';
				
				$("#SKUIndukList").append( str );
				
			}
		}
		
		/*
		if( SKUInduk.SKUInduk_UserUMMenu != 0 )
		{
			$("#cbaturstock_unitmandiri").html('');
			for( i=0 ; i< SKUInduk.SKUInduk_UserUMMenu.length ; i++ )
			{
				var UnitMandiri_ID 		= SKUInduk.SKUInduk_UserUMMenu[i].UnitMandiri_ID; 
				var UnitMandiri_Name 	= SKUInduk.SKUInduk_UserUMMenu[i].UnitMandiri_Name;

				var str = '';

				str	= str + '<option value="'+ UnitMandiri_ID +'">'+ UnitMandiri_Name +'</option>';
				
				$("#cbaturstock_unitmandiri").append( str );
			}
			
		}*/
	}
	
	$("#btntambahskuinduk").click
	(
		function()
		{
			GetSKUMenu();
			
			$("#MainMenu").attr('style','display: none;');
			$("#Tambah").removeAttr('style');
			
			if( $.fn.DataTable.isDataTable('#tablegeneratedstringsku') ) 
			{
				$('#tablegeneratedstringsku').DataTable().destroy();
			}

			$('#tablegeneratedstringsku > tbody').empty();
		}
	);
	
	$("#cbskuinduk").change
	(
		function()
		{
			var SKUInduk_ID = $("#cbskuinduk option:selected").val();
			
			if( SKUInduk_ID == '' )
			{
				$("#txaskuinduk_ket").val('');
			}
			else
			{
				$.ajax(
				{
					type: 'POST',    
					url: "<?= base_url('SKUHadiah/GetSKUIndukDataMenu') ?>",
					data: 
					{
						SKUInduk_ID : SKUInduk_ID
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
	
		var SKUInduk_Keterangan = SKUInduk.SKUIndukMenu[0].SKUInduk_Keterangan;
		
		$("#txaskuinduk_ket").val( SKUInduk_Keterangan );
		
		$("#txaskuinduk_ket").trigger('keydown');
		$("#txaskuinduk_ket").trigger('keyup');
	}
	
	function GetSKUMenu()
	{
		$.ajax(
		{
			type: 'POST',    
			url: "<?= base_url('SKUHadiah/GetSKUHadiahMenu') ?>",
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
		
		$("#cbskuinduk").html('');
		$("#cbskuinduk").append( '<option value="">--Pilih SKU Induk--</option>');
		
		if( SKU.SKUIndukMenu != 0 )
		{
			for( i=0 ; i<SKU.SKUIndukMenu.length ; i++ )
			{
				var SKUInduk_ID 	= SKU.SKUIndukMenu[i].SKUInduk_ID;
				var SKUInduk_Kode 	= SKU.SKUIndukMenu[i].SKUInduk_Kode;
				var SKUInduk_Nama 	= SKU.SKUIndukMenu[i].SKUInduk_Nama;
				
				$("#cbskuinduk").append( '<option value="'+ SKUInduk_ID +'">'+ SKUInduk_Nama +'</option>');
			}
		}
		
		arrVarian = [];
		arrVarianDetail = [];
		
		if( SKU.VarianMenu != 0 )
		{
			for( i=0 ; i<SKU.VarianMenu.length ; i++ )
			{
				var Varian_ID 	= SKU.VarianMenu[i].Varian_ID;
				var Varian_Kode = SKU.VarianMenu[i].Varian_Kode;
				var Varian_Nama = SKU.VarianMenu[i].Varian_Nama;
				var IsWajib 	= SKU.VarianMenu[i].IsWajib;
				
				arrVarian.push( { Varian_ID : Varian_ID, Varian_Kode : Varian_Kode, Varian_Nama : Varian_Nama, IsWajib : IsWajib });
			}	
		}
			
		if( SKU.VarianDetailMenu != 0 )
		{
			for( i=0 ; i<SKU.VarianDetailMenu.length ; i++ )
			{
				var Varian_ID 			= SKU.VarianDetailMenu[i].Varian_ID;
				var VarianDetail_ID 	= SKU.VarianDetailMenu[i].VarianDetail_ID;
				var VarianDetail_Nama 	= SKU.VarianDetailMenu[i].VarianDetail_Nama;
				var NamaVarianDetail 	= SKU.VarianDetailMenu[i].NamaVarianDetail;
				
				arrVarianDetail.push( { Varian_ID : Varian_ID, VarianDetail_ID : VarianDetail_ID, VarianDetail_Nama : VarianDetail_Nama, NamaVarianDetail : NamaVarianDetail } );
			}	
		}
		
		arrVarianDBerat = [];
		arrVarianDKemasan = [];
		arrVarianDetail = [];
		if( SKU.VarianDBeratMenu != 0 )
		{
			for( i=0 ; i<SKU.VarianDBeratMenu.length ; i++ )
			{
				var Varian_ID 			= SKU.VarianDBeratMenu[i].Varian_ID;
				var VarianDetail_ID 	= SKU.VarianDBeratMenu[i].VarianDetail_ID;
				var VarianDetail_Nama 	= SKU.VarianDBeratMenu[i].VarianDetail_Nama;
				var NamaVarianDetail 	= SKU.VarianDBeratMenu[i].NamaVarianDetail;
				
				arrVarianDBerat.push( { Varian_ID : Varian_ID, VarianDetail_ID : VarianDetail_ID, VarianDetail_Nama : VarianDetail_Nama, NamaVarianDetail : NamaVarianDetail } );
			}	
		}
		
		if( SKU.VarianDKemasanMenu != 0 )
		{
			for( i=0 ; i<SKU.VarianDKemasanMenu.length ; i++ )
			{
				var Varian_ID 			= SKU.VarianDKemasanMenu[i].Varian_ID;
				var VarianDetail_ID 	= SKU.VarianDKemasanMenu[i].VarianDetail_ID;
				var VarianDetail_Nama 	= SKU.VarianDKemasanMenu[i].VarianDetail_Nama;
				var NamaVarianDetail 	= SKU.VarianDKemasanMenu[i].NamaVarianDetail;
				
				arrVarianDKemasan.push( { Varian_ID : Varian_ID, VarianDetail_ID : VarianDetail_ID, VarianDetail_Nama : VarianDetail_Nama, NamaVarianDetail : NamaVarianDetail } );
			}	
		}
		
		if( SKU.VarianDSatuanMenu != 0 )
		{
			for( i=0 ; i<SKU.VarianDSatuanMenu.length ; i++ )
			{
				var Varian_ID 			= SKU.VarianDSatuanMenu[i].Varian_ID;
				var VarianDetail_ID 	= SKU.VarianDSatuanMenu[i].VarianDetail_ID;
				var VarianDetail_Nama 	= SKU.VarianDSatuanMenu[i].VarianDetail_Nama;
				var NamaVarianDetail 	= SKU.VarianDSatuanMenu[i].NamaVarianDetail;
				
				arrVarianDSatuan.push( { Varian_ID : Varian_ID, VarianDetail_ID : VarianDetail_ID, VarianDetail_Nama : VarianDetail_Nama, NamaVarianDetail : NamaVarianDetail } );
			}	
		}
		
		
		$("#cbKategori1").html('');
		$("#cbKategori2").html('');
		$("#cbKategori3").html('');
		$("#cbKategori4").html('');
		$("#cbKategori9").html('');
		$("#cbKategori10").html('');
		
		$("#cbKategori1").html('<option value="">--Pilih Kategori--</option>');
		$("#cbKategori2").html('<option value="">--Pilih Sub Kategori--</option>');
		$("#cbKategori3").html('<option value="">--Pilih Jenis--</option>');
		$("#cbKategori4").html('<option value="">--Pilih Sub Jenis--</option>');
		
		$("#cbKategori9").html('<option value="">--Pilih Principle--</option>');
		$("#cbKategori10").html('<option value="">--Pilih Brand--</option>');
		
		
		$("#updatecbKategori1").html('');
		$("#updatecbKategori2").html('');
		$("#updatecbKategori3").html('');
		$("#updatecbKategori4").html('');
		$("#updatecbKategori9").html('');
		$("#updatecbKategori10").html('');
		
		$("#updatecbKategori1").html('<option value="">--Pilih Kategori--</option>');
		$("#updatecbKategori2").html('<option value="">--Pilih Sub Kategori--</option>');
		$("#updatecbKategori3").html('<option value="">--Pilih Jenis--</option>');
		$("#updatecbKategori4").html('<option value="">--Pilih Sub Jenis--</option>');
		
		$("#updatecbKategori9").html('<option value="">--Pilih Principle--</option>');
		$("#updatecbKategori10").html('<option value="">--Pilih Brand--</option>');
		
		
		if( SKU.Kategori1Menu != 0 )
		{
			for( i=0 ; i<SKU.Kategori1Menu.length ; i++ )
			{
				var Kategori1ID = SKU.Kategori1Menu[i].Kategori1ID;
				var Kategori1_Kode = SKU.Kategori1Menu[i].Kategori1_Kode;
				var Kategori1_Nama = SKU.Kategori1Menu[i].Kategori1_Nama;
				
				arrKategori1.push( { Kategori1ID : Kategori1ID, Kategori1_Kode : Kategori1_Kode, Kategori1_Nama : Kategori1_Nama } );
				
				$("#cbKategori1").append( '<option value="'+ Kategori1ID +'">'+ Kategori1_Nama +'</option>' );
				$("#updatecbKategori1").append( '<option value="'+ Kategori1ID +'">'+ Kategori1_Nama +'</option>' );
			}
		}
		
		if( SKU.Kategori2Menu != 0 )
		{
			for( i=0 ; i<SKU.Kategori2Menu.length ; i++ )
			{
				var Kategori2ID = SKU.Kategori2Menu[i].Kategori1ID;
				var Kategori2_Kode = SKU.Kategori2Menu[i].Kategori1_Kode;
				var Kategori2_Nama = SKU.Kategori2Menu[i].Kategori1_Nama;
				
				arrKategori2.push( { Kategori2ID : Kategori2ID, Kategori2_Kode : Kategori1_Kode, Kategori2_Nama : Kategori2_Nama } );
				
				$("#cbKategori2").append( '<option value="'+ Kategori2ID +'">'+ Kategori2_Nama +'</option>' );
				$("#updatecbKategori2").append( '<option value="'+ Kategori2ID +'">'+ Kategori2_Nama +'</option>' );
			}
		}
				
		if( SKU.Kategori3Menu != 0 )
		{
			for( i=0 ; i<SKU.Kategori3Menu.length ; i++ )
			{
				var Kategori3ID = SKU.Kategori3Menu[i].Kategori1ID;
				var Kategori3_Kode = SKU.Kategori3Menu[i].Kategori1_Kode;
				var Kategori3_Nama = SKU.Kategori3Menu[i].Kategori1_Nama;
				
				arrKategori3.push( { Kategori3ID : Kategori3ID, Kategori3_Kode : Kategori3_Kode, Kategori3_Nama : Kategori3_Nama } );
				
				$("#cbKategori3").append( '<option value="'+ Kategori3ID +'">'+ Kategori3_Nama +'</option>' );
				$("#updatecbKategori3").append( '<option value="'+ Kategori3ID +'">'+ Kategori3_Nama +'</option>' );
			}
		}		
		
		if( SKU.Kategori4Menu != 0 )
		{
			for( i=0 ; i<SKU.Kategori4Menu.length ; i++ )
			{
				var Kategori4ID = SKU.Kategori4Menu[i].Kategori1ID;
				var Kategori4_Kode = SKU.Kategori4Menu[i].Kategori1_Kode;
				var Kategori4_Nama = SKU.Kategori4Menu[i].Kategori1_Nama;
				
				arrKategori4.push( { Kategori4ID : Kategori4ID, Kategori4_Kode : Kategori4_Kode, Kategori4_Nama : Kategori4_Nama } );
				
				$("#cbKategori4").append( '<option value="'+ Kategori4ID +'">'+ Kategori4_Nama +'</option>' );
				$("#updatecbKategori4").append( '<option value="'+ Kategori4ID +'">'+ Kategori4_Nama +'</option>' );
			}
		}	
		
		if( SKU.Kategori9Menu != 0 )
		{
			for( i=0 ; i<SKU.Kategori9Menu.length ; i++ )
			{
				var Kategori9ID = SKU.Kategori9Menu[i].Kategori1ID;
				var Kategori9_Kode = SKU.Kategori9Menu[i].Kategori1_Kode;
				var Kategori9_Nama = SKU.Kategori9Menu[i].Kategori1_Nama;
				
				arrKategori9.push( { Kategori9ID : Kategori9ID, Kategori9_Kode : Kategori9_Kode, Kategori9_Nama : Kategori9_Nama } );
				
				$("#cbKategori9").append( '<option value="'+ Kategori9ID +'">'+ Kategori9_Nama +'</option>' );
				$("#updatecbKategori9").append( '<option value="'+ Kategori9ID +'">'+ Kategori9_Nama +'</option>' );
			}
		}
		
		if( SKU.Kategori10Menu != 0 )
		{
			for( i=0 ; i<SKU.Kategori10Menu.length ; i++ )
			{
				var Kategori10ID = SKU.Kategori10Menu[i].Kategori1ID;
				var Kategori10_Kode = SKU.Kategori10Menu[i].Kategori1_Kode;
				var Kategori10_Nama = SKU.Kategori10Menu[i].Kategori1_Nama;
				
				arrKategori10.push( { Kategori10ID : Kategori10ID, Kategori10_Kode : Kategori10_Kode, Kategori10_Nama : Kategori10_Nama } );
				
				$("#cbKategori10").append( '<option value="'+ Kategori10ID +'">'+ Kategori10_Nama +'</option>' );
				$("#updatecbKategori10").append( '<option value="'+ Kategori10ID +'">'+ Kategori10_Nama +'</option>' );
			}
		}
		
		$("#cbS_SKU_LengthUnit").html('');
		$("#cbS_SKU_WidthUnit").html('');
		$("#cbS_SKU_HeightUnit").html('');
		$("#cbS_SKU_VolumeUnit").html('');
		//$("#cbS_SKU_WeightNettoUnit").html('');
		$("#cbS_SKU_WeightProductUnit").html('');
		$("#cbS_SKU_WeightPackagingUnit").html('');
		$("#cbS_SKU_WeightGiftUnit").html('');
		
		$("#updatecbS_SKU_LengthUnit").html('');
		$("#updatecbS_SKU_WidthUnit").html('');
		$("#updatecbS_SKU_HeightUnit").html('');
		$("#updatecbS_SKU_VolumeUnit").html('');
		//$("#updatecbS_SKU_WeightNettoUnit").html('');
		$("#updatecbS_SKU_WeightProductUnit").html('');
		$("#updatecbS_SKU_WeightPackagingUnit").html('');
		$("#updatecbS_SKU_WeightGiftUnit").html('');
		
		$("#cbaddvariandetail_varianunit").html('');
		$("#cbupdateaddvariandetail_varianunit").html('');
		
		arrSatuan = [];
		
		if( SKU.SatuanUnitBarangMenu != 0 )
		{
			for( i=0 ; i<SKU.SatuanUnitBarangMenu.length ; i++ )
			{
				var KodeSatuan 	= SKU.SatuanUnitBarangMenu[i].KodeSatuan;
				var NamaSatuan 	= SKU.SatuanUnitBarangMenu[i].NamaSatuan;
				var Grup 		= SKU.SatuanUnitBarangMenu[i].Grup;
				
				var str = '<option value="'+ NamaSatuan +'">'+ NamaSatuan +'</option>';
				
				if( Grup == 'Panjang' )		
				{	
					$("#cbS_SKU_LengthUnit").append( str );	
					$("#updatecbS_SKU_LengthUnit").append( str );	
				}
				else if( Grup == 'Lebar' )	
				{	
					$("#cbS_SKU_WidthUnit").append( str );	
					$("#updatecbS_SKU_WidthUnit").append( str );	
				}
				else if( Grup == 'Tinggi' )	
				{	
					$("#cbS_SKU_HeightUnit").append( str );	
					$("#updatecbS_SKU_HeightUnit").append( str );	
				}
				else if( Grup == 'Volume' )	
				{	
					$("#cbS_SKU_VolumeUnit").append( str );	
					$("#updatecbS_SKU_VolumeUnit").append( str );	
				}
				else if( Grup == 'Berat' )	
				{
					//$("#cbS_SKU_WeightNettoUnit").append( str );	
					$("#cbS_SKU_WeightProductUnit").append( str );	
					$("#cbS_SKU_WeightPackagingUnit").append( str );	
					$("#cbS_SKU_WeightGiftUnit").append( str );		
					$("#cbaddvariandetail_varianunit").append( str );		
					
					//$("#cbS_SKU_WeightNettoUnit").append( str );	
					$("#updatecbS_SKU_WeightProductUnit").append( str );	
					$("#updatecbS_SKU_WeightPackagingUnit").append( str );	
					$("#updatecbS_SKU_WeightGiftUnit").append( str );		
					$("#cbupdateaddvariandetail_varianunit").append( str );		
				}
				
				arrSatuan.push( { KodeSatuan : KodeSatuan, NamaSatuan : NamaSatuan, Grup: Grup } );
			}
		}
		
	}
	/*
	function ChVarianMenu( JSONSKU )
	{
		var SKU = JSON.parse( JSONSKU );
		
		strdatavarian = '';
		strdatavariandetail = '';
		
		
		
		$("#divvarianwajib").html('');
		$("#divvarian").html('');
		
		if( SKU.SKU_Varian_WajibMenu != 0 )
		{
			var NamaVarian = '';
			var countheader = 0;
			
			for( i=0 ; i<SKU.SKU_Varian_WajibMenu.length ; i++ )
			{
				var SKU_Varian_ID 		= SKU.SKU_Varian_WajibMenu[i].SKU_Varian_ID;
				var SKU_ID				= SKU.SKU_Varian_WajibMenu[i].SKU_ID;
				var Var_ID   			= SKU.SKU_Varian_WajibMenu[i].Var_ID;
				var Varian_ID   		= SKU.SKU_Varian_WajibMenu[i].Varian_ID;
				var Varian_Nama			= SKU.SKU_Varian_WajibMenu[i].Varian_Nama;
				var IsWajib				= SKU.SKU_Varian_WajibMenu[i].IsWajib;
				var VarianDetail_ID		= SKU.SKU_Varian_WajibMenu[i].VarianDetail_ID;
				var VarianDetail_Nama	= SKU.SKU_Varian_WajibMenu[i].VarianDetail_Nama;
				var SKUInduk_ID			= SKU.SKU_Varian_WajibMenu[i].SKUInduk_ID;

				var isHeader = 0;

				var strheader = '';
				var strfooter = '';
				
				if( NamaVarian != Varian_Nama )
				{
					countheader = countheader + 1;
					
					var strvarianwajib = '';
					strvarianwajib = strvarianwajib + '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 varianheaderwajib" style="padding: 10px; margin-bottom: 10px; background-color: #dddddd;" id="varianheaderwajib'+ countheader +'">';
					strvarianwajib = strvarianwajib + '	<div class="row">';
					strvarianwajib = strvarianwajib + '		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">';
					strvarianwajib = strvarianwajib + '			<input type="hidden" id="hdvarianwajib_'+ countheader +'" class="form-control hdvarianwajib" value="'+ Varian_ID +'" />';
					strvarianwajib = strvarianwajib + '			<input type="hidden" id="hdvariannamawajib_'+ countheader +'" class="form-control hdvariannamawajib" value="'+ Varian_Nama +'" />';
					strvarianwajib = strvarianwajib + '			<input type="text" id="txtvarianwajib" class="form-control txtvarianwajib" value="'+ Varian_Nama +'" disabled="disabled" />';
					strvarianwajib = strvarianwajib + '		</div>';
					strvarianwajib = strvarianwajib + '	</div>';
				
					strvarianwajib = strvarianwajib + '	<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-horizontal form-label-left" id="divvarianwajibdetail'+ countheader +'">';
					strvarianwajib = strvarianwajib + '		<label class="control-label col-xs-4 col-sm-3 col-md-3 col-lg-3 col-xl-3">Jenis</label>';
					strvarianwajib = strvarianwajib + '		<div class="col-xs-8 col-sm-9 col-md-9 col-lg-9 col-xl-9" id="varianwajibdetail'+ countheader +'">';
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
				
				if( Var_ID == Varian_ID )
				{
					strvarianwajibdetail = strvarianwajibdetail + '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 divrowvarianwajib'+ countheader +'" id="divrowvarianwajib'+ countheader +'">';
					strvarianwajibdetail = strvarianwajibdetail + '	<div class="input-group">';
					strvarianwajibdetail = strvarianwajibdetail + '		<select class="form-control cbvarianwajibdetail cbvarianwajibdetail__'+ countheader +'">';
						
					strvarianwajibdetail = strvarianwajibdetail + '			<option value="">Pilih jenis</option>';
					for( j=0; j<arrVarianDetail.length ; j++ )
					{
						
						var DVarian_ID 			= arrVarianDetail[j].Varian_ID;
						var DVarianDetail_ID 	= arrVarianDetail[j].VarianDetail_ID;
						var DVarianDetail_Nama 	= arrVarianDetail[j].VarianDetail_Nama;
						
						if( VarianDetail_ID == DVarianDetail_ID )	{	var isselected = 'selected';	}
						else 										{	var isselected = '';			}
						
						if( Var_ID == Varian_ID )
						{
							
							strvarianwajibdetail = strvarianwajibdetail + '<option value="'+ DVarian_ID +' | '+ DVarianDetail_ID +'" '+ isselected +'>'+ DVarianDetail_Nama +'</option>';
						}
					}
					strvarianwajibdetail = strvarianwajibdetail + '		</select>';
					strvarianwajibdetail = strvarianwajibdetail + '		<div class="input-group-btn">';
					strvarianwajibdetail = strvarianwajibdetail + '			<button type="button" class="btn btn-danger" onclick="HapusVarianWajibDetail('+ countheader +')"><i class="fa fa-trash"></i></button>';
					strvarianwajibdetail = strvarianwajibdetail + '		</div>';
					strvarianwajibdetail = strvarianwajibdetail + '	</div>';
					strvarianwajibdetail = strvarianwajibdetail + '</div>';
				}
				
				$("#varianwajibdetail"+ countheader).append( strvarianwajibdetail ); 
				
				NamaVarian = Varian_Nama;
				IsHeader = 1;
			
				$(".cbvarianwajibdetail__"+ countheader).css("width", "100%");
				$(".cbvarianwajibdetail__"+ countheader).select2({
					"theme": "bootstrap", "val": ""
				});
			}
		}
		
		$("#step2").removeAttr('style');		
		$("#step3").removeAttr('style');				
		//$("#step4").removeAttr('style');	
		
		Proses( 1 );
	}
	*/
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
	/*
	function TambahVarianWajibDetail( countheader )
	{
		var Idx = parseInt( countheader ) - 1;
		var Varian_ID = $(".hdvarianwajib").eq(Idx).val();
		var VNama = $(".hdvariannamawajib").eq(Idx).val();
		
		var rand2 = Math.random() * 1000000000000000000000;
		
		if( Varian_ID != '' )
		{
			var strvarianwajibdetail = '';
			strvarianwajibdetail = strvarianwajibdetail + '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 divrowvarianwajib_'+ rand2 +'" id="divrowvarianwajib'+ rand2 +'">';
			strvarianwajibdetail = strvarianwajibdetail + '	<div class="input-group">';
			strvarianwajibdetail = strvarianwajibdetail + '		<select class="form-control cbvarianwajibdetail cbvarianwajibdetail__'+ rand2 +'" id="cbvarianwajibdetail__'+ rand2 +'" onchange="CheckValuesWajib('+ countheader +','+ rand2 +',\''+ Varian_ID +'\',\''+ VNama +'\')" >';

			strvarianwajibdetail = strvarianwajibdetail + '			<option value="">Pilih jenis</option>';
			strvarianwajibdetail = strvarianwajibdetail + '			<option value="ADD">**Buat Jenis Baru**</option>';
			
			for( j=0; j<arrVarianDetail.length ; j++ )
			{
				var DVarian_ID 			= arrVarianDetail[j].Varian_ID;
				var DVarianDetail_ID 	= arrVarianDetail[j].VarianDetail_ID;
				var DVarianDetail_Nama 	= arrVarianDetail[j].VarianDetail_Nama;
				var DNamaVarianDetail 	= arrVarianDetail[j].NamaVarianDetail;
			
				if( DVarian_ID == Varian_ID )
				{
					strvarianwajibdetail = strvarianwajibdetail + '	<option value="'+ DVarian_ID +' | '+ DVarianDetail_ID +' | '+ DNamaVarianDetail +'">'+ DNamaVarianDetail +'</option>';
				}
			}
			
			strvarianwajibdetail = strvarianwajibdetail + '		</select>';
			strvarianwajibdetail = strvarianwajibdetail + '		<div class="input-group-btn">';
			strvarianwajibdetail = strvarianwajibdetail + '			<button type="button" class="btn btn-danger" onclick="HapusVarianWajibDetail('+ rand2 +')"><i class="fa fa-trash"></i></button>';
			strvarianwajibdetail = strvarianwajibdetail + '		</div>';
			strvarianwajibdetail = strvarianwajibdetail + '	</div>';
			strvarianwajibdetail = strvarianwajibdetail + '</div>';
			
			$("#varianwajibdetail"+ countheader).append( strvarianwajibdetail ); 
			
			$(".cbvarianwajibdetail__"+ rand2).css("width", "100%");
			$(".cbvarianwajibdetail__"+ rand2).select2({
				"theme": "bootstrap", "val": ""
			});	
		}
	}
	
	function CheckValuesWajib( Idx, RandVal, ID, Nama )
	{
		$("#txtaddvariandetail_rand").val( RandVal );
		
		$("#txtaddvariandetail_varianid").val( ID );
		
		$("#txtaddvariandetail_variannama").val( Nama );
		
		$("#txtaddvariandetail_variandetailnama").val('');
		
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
		$("#txtaddvariandetail_rand").val( RandVal );
		
		$("#txtaddvariandetail_varianid").val( ID );
		
		$("#txtaddvariandetail_variannama").val( Nama );
		
		$("#txtaddvariandetail_variandetailnama").val('');
		
		if( Nama == 'Berat Produk' ){	$("#isvarianberat").removeAttr('style');			}
		else						{	$("#isvarianberat").attr('style','display: none;');	}
		
		
		var val = $("#cbvariandetail__"+ RandVal +" option:selected").val();
		
		if( val == 'ADD' )
		{
			$("#previewaddvariandetail").modal('show');
		}
	}
	
	$("#btnsavevariandetail").click
	(
		function()
		{
			var Rand 				= $("#txtaddvariandetail_rand").val();
			
			var Varian_ID 			= $("#txtaddvariandetail_varianid").val();
			var VarianDetail_Nama 	= $("#txtaddvariandetail_variandetailnama").val();
			var VarianDetail_Unit 	= $("#cbaddvariandetail_varianunit option:selected").val();
			
			$.ajax(
			{
				type: 'POST',    
				url: "<?= base_url('SKUHadiah/SaveVarianDetail') ?>",
				data: 
				{
					Varian_ID : Varian_ID,
					VarianDetail_Nama : VarianDetail_Nama,
					VarianDetail_Unit : VarianDetail_Unit
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
						
						var Varian_ID = data.Varian_ID;
						var VarianDetail_ID = data.VarianDetail_ID;
						var VarianDetail_Nama = data.VarianDetail_Nama;
						var NamaVarianDetail = data.NamaVarianDetail;
						
						arrVarianDetail.push( { Varian_ID : Varian_ID, VarianDetail_ID : VarianDetail_ID, VarianDetail_Nama : VarianDetail_Nama, NamaVarianDetail : NamaVarianDetail } );
					
						if( Mode == 1 )	// Berat
						{
							$("#cbvarianwajibdetail__"+ Rand).append( '<option value="'+ Varian_ID +' | '+ VarianDetail_ID +' | '+ NamaVarianDetail +'">'+ NamaVarianDetail +'</option>' );
							
							$("#cbvarianwajibdetail__"+ Rand).val( Varian_ID +' | '+ VarianDetail_ID +' | '+ NamaVarianDetail );
						}
						else if( Mode == 0 )	// Non Berat
						{
							$("#cbvariandetail__"+ Rand).append( '<option value="'+ Varian_ID +' | '+ VarianDetail_ID +' | '+ NamaVarianDetail +'">'+ NamaVarianDetail +'</option>' );
							
							$("#cbvariandetail__"+ Rand).val( Varian_ID +' | '+ VarianDetail_ID +' | '+ NamaVarianDetail );
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
	
	function HapusVarianWajib( rand )
	{
		$("#varianwajibheader"+ rand).remove();
	}
	
	function ChangeVarianWajib( countheader )
	{
		$("#varianwajibdetail"+ countheader ).html('');
	}
	
	$("#btnaddvarian").click
	(
		function()
		{
			var rand = Math.random() * 10000000000000000000;
			
			var strvarian = '';
			strvarian = strvarian + '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 varianheader" style="padding: 10px; margin-bottom: 10px; background-color: #dddddd;" id="varianheader'+ rand +'">';
			strvarian = strvarian + '	<div class="row">';
			strvarian = strvarian + '		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">';
			strvarian = strvarian + '			<div class="input-group">';

			strvarian = strvarian + '				<select class="form-control cbvarian cbvarian_'+ rand +'" id="cbvarian'+ rand +'" onchange="ChangeVarian('+ rand +')">';
			strvarian = strvarian + '					<option value="">Pilih Varian</option>';
			
			for( j=0; j<arrVarian.length ; j++ )
			{
				var DVarian_ID 		= arrVarian[j].Varian_ID;
				var DVarian_Kode 	= arrVarian[j].Varian_Kode;
				var DVarian_Nama 	= arrVarian[j].Varian_Nama;
				var DIsWajib 		= arrVarian[j].IsWajib;
				
				if( DIsWajib == 0 )
				{
					strvarian = strvarian + '				<option value="'+ DVarian_ID +'">'+ DVarian_Nama +'</option>';
				}
			}
		
			strvarian = strvarian +	'				</select>';
			strvarian = strvarian +	'				<div class="input-group-btn">';
			strvarian = strvarian + '					<button type="button" class="btn btn-danger" onclick="HapusVarian('+ rand +')"><i class="fa fa-trash"></i></button>';
			strvarian = strvarian + '				</div>';
			strvarian = strvarian + '			</div>';
			strvarian = strvarian + '		</div>';
			strvarian = strvarian + '	</div>';
		
			strvarian = strvarian + '	<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-horizontal form-label-left" id="divvariandetail'+ rand +'">';
			strvarian = strvarian + '		<label class="control-label col-xs-4 col-sm-3 col-md-3 col-lg-3 col-xl-3">Jenis</label>';
			strvarian = strvarian + '		<div class="col-xs-8 col-sm-9 col-md-9 col-lg-9 col-xl-9" id="variandetail'+ rand +'">';
			strvarian = strvarian + '		</div>';
			strvarian = strvarian + '	</div>';
			strvarian = strvarian + '	<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">';
			strvarian = strvarian + ' 		<button type="button" class="form-control btn btn-primary" onclick="TambahVarianDetail('+ rand +')">';
			strvarian = strvarian + '			<i class="fa fa-plus"></i> Tambah Jenis';
			strvarian = strvarian + '		</button>';
			strvarian = strvarian + '	</div>';
			strvarian = strvarian + '</div>';
			
			$("#divvarian").append( strvarian );
			
			
			$("#cbvarian"+ rand).css("width", "100%");
			$("#cbvarian"+ rand).select2({
				"theme": "bootstrap", "val": ""
			});
		}
	);
	
	function TambahVarianDetail( rand )
	{
		//var Varian_ID = $("#cbvarian"+ rand +" option:selected").val();
		//var Idx = parseInt( countheader ) - 1;
		//var Varian_ID = $(".hdvarian").eq(Idx).val();
		//var VNama = $(".hdvariannama").eq(Idx).val();
		
		var Varian_ID = $("#cbvarian"+ rand +" option:selected").val();
		var VNama = $("#cbvarian"+ rand +" option:selected").html();
		
		var rand2 = Math.random() * 1000000000000000000000;

		if( Varian_ID != '' )
		{
			var strvariandetail = '';
			strvariandetail = strvariandetail + '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 divrowvarian_'+ rand +' hapusdivrowvarian_'+ rand2 +'" id="divrowvarian'+ rand +'">';

			strvariandetail = strvariandetail + '	<div class="input-group">';

			strvariandetail = strvariandetail + '		<select class="form-control cbvariandetail cbvariandetail__'+ rand +'" id="cbvariandetail__'+ rand2 +'" onchange="CheckValues('+ rand +','+ rand2 +',\''+ Varian_ID +'\',\''+ VNama +'\')">';

			strvariandetail = strvariandetail + '			<option value="">Pilih jenis</option>';
			strvariandetail = strvariandetail + '			<option value="ADD">**Buat Jenis Baru**</option>';
			
			for( j=0; j<arrVarianDetail.length ; j++ )
			{
				var DVarian_ID 			= arrVarianDetail[j].Varian_ID;
				var DVarianDetail_ID 	= arrVarianDetail[j].VarianDetail_ID;
				var DVarianDetail_Nama 	= arrVarianDetail[j].VarianDetail_Nama;
				var DNamaVarianDetail 	= arrVarianDetail[j].NamaVarianDetail;
			
				if( DVarian_ID == Varian_ID )
				{
					strvariandetail = strvariandetail + '	<option value="'+ DVarian_ID +' | '+ DVarianDetail_ID +' | '+ DNamaVarianDetail +'">'+ DNamaVarianDetail +'</option>';
				}
			}
			
			strvariandetail = strvariandetail + '		</select>';
			strvariandetail = strvariandetail + '		<div class="input-group-btn">';
			strvariandetail = strvariandetail + '			<button type="button" class="btn btn-danger" onclick="HapusVarianDetail('+ rand +','+ rand2 +')"><i class="fa fa-trash"></i></button>';
			strvariandetail = strvariandetail + '		</div>';
			strvariandetail = strvariandetail + '	</div>';
			strvariandetail = strvariandetail + '</div>';
			
			$("#variandetail"+ rand).append( strvariandetail ); 
			
			$(".cbvariandetail__"+ rand ).css("width", "100%");
			$(".cbvariandetail__"+ rand ).select2({
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
		$("#variandetail"+ countheader ).html('');
	}
	*/
	$("#cbKategori1").change
	(
		function()
		{
			var Kategori1_ID = $("#cbKategori1 option:selected").val();
			
			if( Kategori1_ID == '' )
			{
				$("#kat2").attr('style','display: none');
				$("#kat3").attr('style','display: none');
				$("#kat4").attr('style','display: none');
				//$("#pri").attr('style','display: none');
			
				$("#cbKategori2").html('');
				$("#cbKategori2").append('<option value="" selected>--Pilih Sub Kategori--</option>');
				$("#cbKategori3").html('');
				$("#cbKategori3").append('<option value="" selected>--Pilih Jenis--</option>');
				$("#cbKategori4").html('');
				$("#cbKategori4").append('<option value="" selected>--Pilih Sub Jenis--</option>');
				//$("#cbKategori4").html('');
				//$("#cbKategori4").append('<option value="" selected>--Pilih Principle--</option>');
			}
			else
			{
				GetSKUCategory( 1, Kategori1_ID );
			}
		}
	);
		
	$("#cbKategori2").change
	(
		function()
		{
			var Kategori1_ID = $("#cbKategori2 option:selected").val();
			
			if( Kategori1_ID == '' )
			{
				$("#kat3").attr('style','display: none');
				$("#kat4").attr('style','display: none');
				//$("#pri").attr('style','display: none');
				
				$("#cbKategori3").html('');
				$("#cbKategori3").append('<option value="" selected>--Pilih Jenis--</option>');
				$("#cbKategori4").html('');
				$("#cbKategori4").append('<option value="" selected>--Pilih Sub Jenis--</option>');
				//$("#cbKategori4").html('');
				//$("#cbKategori4").append('<option value="" selected>--Pilih Principle--</option>');
			}
			else
			{
				GetSKUCategory( 2, Kategori1_ID );
			}
		}
	);
	
	$("#cbKategori3").change
	(
		function()
		{
			var Kategori1_ID = $("#cbKategori3 option:selected").val();
			
			if( Kategori1_ID == '' )
			{
				$("#kat4").attr('style','display: none');
				//$("#pri").attr('style','display: none');
				
				$("#cbKategori4").html('');
				$("#cbKategori4").append('<option value="" selected>--Pilih Sub Jenis--</option>');
				//$("#cbKategori4").html('');
				//$("#cbKategori4").append('<option value="" selected>--Pilih Principle--</option>');
			}
			else
			{
				GetSKUCategory( 3, Kategori1_ID );
			}
		}
	);
	
	function GetSKUCategory( Jenis, Kategori1_ID )
	{
		
		if( Kategori1_ID != '' || Kategori1_ID == 0 )
		{
			$.ajax(
			{
				type: 'POST',    
				url: "<?= base_url('SKUHadiah/GetCategory') ?>",
				data: {	
						Jenis : Jenis,
						Kategori1_ID : Kategori1_ID
						},
				success: function( response )
				{
					ChSKUCategory( response, Jenis );
				}
			});
		}
		else if( Kategori1_ID == '' )
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
				$("#cbKategori1").html('');
				$("#cbKategori1").append( '<option value="" selected>--Pilih Kategori--</option>' );	
			}
			if( Jenis == 1 )		
			{	
				$("#kat2").removeAttr('style');
				$("#cbKategori2").html('');
				$("#cbKategori2").append( '<option value="" selected>--Pilih Sub Kategori--</option>' );
				
				$("#kat3").attr('style','display: none;');
				$("#cbKategori3").html('');
				$("#cbKategori3").append( '<option value="" selected>--Pilih Sub Kategori 2--</option>' );	
			}
			if( Jenis == 2 )		
			{	
				$("#kat3").removeAttr('style');
				$("#cbKategori3").html('');
				$("#cbKategori3").append( '<option value="" selected>--Pilih Sub Kategori 2--</option>' );	
				
				$("#kat4").attr('style','display: none;');
				$("#cbKategori4").html('');
				$("#cbKategori4").append( '<option value="" selected>--Pilih Sub Kategori 3--</option>' );	
			}
			else if( Jenis == 3 )	
			{	
				$("#kat4").removeAttr('style');
				$("#cbKategori4").html('');
				$("#cbKategori4").append( '<option value="" selected>--Pilih Sub Kategori 3--</option>' );	
				
				//$("#kat1").attr('style','display: none;');
				//$("#kat2").attr('style','display: none;');
				//$("#kat3").attr('style','display: none;');
				//$("#cbKategori4").html('');
				//$("#cbKategori4").append( '<option value="" selected>--Pilih Principle--</option>' );	
			}
			
			if( Kategori.JenisKategori.length != 0 )
			{
				for( i=0 ; i<Kategori.JenisKategori.length ; i++ )
				{
					var Kategori1ID = Kategori.JenisKategori[i].Kategori1ID;
					var Kategori1_Kode = Kategori.JenisKategori[i].Kategori1_Kode;
					var Kategori1_Nama = Kategori.JenisKategori[i].Kategori1_Nama;
					
					if( Jenis == 0 )		
					{	
						$("#cbKategori1").append( '<option value="'+ Kategori1ID +'">'+ Kategori1_Nama +'</option>' );	
					}
					else if( Jenis == 1 )	
					{	
						$("#cbKategori2").append( '<option value="'+ Kategori1ID +'">'+ Kategori1_Nama +'</option>' );	
					}
					else if( Jenis == 2 )		
					{	
						$("#cbKategori3").append( '<option value="'+ Kategori1ID +'">'+ Kategori1_Nama +'</option>' );	
					}
					else if( Jenis == 3 )		
					{	
						$("#cbKategori4").append( '<option value="'+ Kategori1ID +'">'+ Kategori1_Nama +'</option>' );	
					}
					else if( Jenis == 9 )	
					{	
						$("#cbKategori9").append( '<option value="'+ Kategori1ID +'">'+ Kategori1_Nama +'</option>' );	
					}
					else if( Jenis == 10 )	
					{	
						$("#cbKategori10").append( '<option value="'+ Kategori1ID +'">'+ Kategori1_Nama +'</option>' );	
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
	
	$("#txtSKUInduk_PPn_Persen").change
	(
		function()
		{
			var Val = $("#txtSKUInduk_PPn_Persen").val();
		
			if( Val < 0 )		{ 	$("#txtSKUInduk_PPn_Persen").val(0);	}
			else if( Val > 100 ){ 	$("#txtSKUInduk_PPn_Persen").val(100);	}
			else if( Val == '' ){ 	$("#txtSKUInduk_PPn_Persen").val(0);	}
		}
	);
	
	$("#txtSKUInduk_PPnBM_Persen").change
	(
		function()
		{
			var Val = $("#txtSKUInduk_PPnBM_Persen").val();
		
			if( Val < 0 )		{ 	$("#txtSKUInduk_PPnBM_Persen").val(0);	}
			else if( Val > 100 ){ 	$("#txtSKUInduk_PPnBM_Persen").val(100);	}
			else if( Val == '' ){ 	$("#txtSKUInduk_PPnBM_Persen").val(0);	}
		}
	);
	
	$("#btnproses1").click
	(
		function()
		{
			var SKUInduk_ID = $("#cbskuinduk option:selected").val();
				
			if( SKUInduk_ID == '' )
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
				var Kategori1 = $("#cbKategori1 option:selected").val();
				var Kategori2 = $("#cbKategori2 option:selected").val();
				var Kategori3 = $("#cbKategori3 option:selected").val();
				var Kategori4 = $("#cbKategori4 option:selected").val();
				
				var Kategori9 = $("#cbKategori9 option:selected").val();
				var Kategori10 = $("#cbKategori10 option:selected").val();
				
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
					
					var SKUInduk_ID = $("#cbskuinduk option:selected").val();

					if( SKUInduk_ID == '' )	
					{	
						$("#step2").attr('style','display: none;');	
						$("#step3").attr('style','display: none;');	
						$("#step4").attr('style','display: none;');	
					}
					else					
					{		
						Proses( 2 );		
						
						/*$.ajax(
						{
							type: 'POST',
							url: "<?= base_url('SKUHadiah/GetVarianMenu') ?>",
							data: 
							{
								SKUInduk_ID : SKUInduk_ID
							},
							success: function( response )
							{
								ChVarianMenu( response );
							}
						});*/
					}
				}
			}
		}
	);
	/*
	$("#btnproses2").click
	(
		function()
		{
			$("#btnproses2").prop('disabled', true );
			
			var lvarian = $(".cbvarian").length;
			
			var lvariandetail = $(".cbvariandetail").length;
			
			var arrVarian_ID = [];
			var arrDataVarian = [];
			
			var numerror = 0;
			
			for( i=0 ; i<lvarian ; i++ )
			{
				var Varian_ID = $(".cbvarian").eq(i).val();
				var Varian_Nama = $(".cbvarian").eq(i).html();
				
				var NUM_ID = $(".cbvarian").eq(i).attr('class');
				NUM_ID = NUM_ID.split('_');
				NUM_ID = NUM_ID[1];
				NUM_ID = NUM_ID.split(' ');
				NUM_ID = NUM_ID[0];
				
				if( $(".cbvariandetail__"+ NUM_ID).length == 0 )
				{
					numerror = 6;
				}
				else
				{
					if( !arrVarian_ID.includes( Varian_ID ) )
					{
						arrVarian_ID.push( Varian_ID );
						
						arrDataVarian.push( { Varian_ID : Varian_ID, Varian_Nama : Varian_Nama } );
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
								
								Varian_ID = VarianWajibDetail[0];
								VarianDetail_ID = VarianWajibDetail[1];
								NamaVarianDetail = VarianWajibDetail[2];
								
								if( !arrVarianWajibDetail_ID.includes( VarianDetail_ID ) )
								{
									arrVarianWajibDetail_ID.push( VarianDetail_ID );
									
									arrDataVarianWajibDetail.push( { Varian_ID : Varian_ID, Varian_Urut: parseInt(i)+1, VarianDetail_ID : VarianDetail_ID, VarianDetail_Urut : parseInt(j)+1 } );
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
						var arrVarianDetail_ID = [];
						//var arrDataVarianDetail = [];
						
						
						var arrIDVarianDetail = [];
						
						var lvariandetail = $(".cbvariandetail").length;
						
						for( i=0 ; i<lvariandetail ; i++ )
						{
							var ID = $(".cbvariandetail").eq(i).attr('class');
							
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
							/*
							var SKUInduk_ID = $("#cbskuinduk option:selected").val();
							
							$.ajax(
							{
								type: 'POST',    
								url: "<?= base_url('SKUHadiah/GenerateStringSKU') ?>",
								data : 
								{
									SKUInduk_ID 				: SKUInduk_ID, 
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
								
								var cbvariandetail = $(".cbvariandetail__"+ NUM_ID ).length;
								
								
								for( j=0 ; j<cbvariandetail ; j++ )
								{
									var VarianDetail = $(".cbvariandetail__"+ NUM_ID).eq(j).val();
									
									if( VarianDetail == '' )
									{
										numerror = 4;
									}
									else
									{
										VarianDetail = VarianDetail.split(' | ');
										
										Varian_ID = VarianDetail[0];
										VarianDetail_ID = VarianDetail[1];
										
										if( !arrVarianDetail_ID.includes( VarianDetail_ID ) )
										{
											arrVarianDetail_ID.push( VarianDetail_ID );
											arrDataVarianDetail.push( { Varian_ID : Varian_ID, Varian_Urut: parseInt(i)+2, VarianDetail_ID : VarianDetail_ID, VarianDetail_Urut: parseInt(j)+1 } );
											
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
								var SKUInduk_ID = $("#cbskuinduk option:selected").val();
							
								$.ajax(
								{
									type: 'POST',    
									url: "<?= base_url('SKUHadiah/GenerateStringSKU') ?>",
									data : 
									{
										SKUInduk_ID 				: SKUInduk_ID, 
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
			/*
			$(".cbSKU_Kondisi").html('');
			
			var strSKU_Kondisi = '';
			strSKU_Kondisi = strSKU_Kondisi + '<option value="Baru">Baru</option>';
			strSKU_Kondisi = strSKU_Kondisi + '<option value="Bekas">Bekas</option>';
			
			var strSatuanPanjang = '';
			var strSatuanLebar = '';
			var strSatuanTinggi = '';
			var strSatuanVolume = '';
			var strSatuanBerat = '';
			for( i=0 ; i< arrSatuan.length ; i++ )
			{
				var KodeSatuan 	= arrSatuan[i].KodeSatuan;
				var NamaSatuan 	= arrSatuan[i].NamaSatuan;
				var Grup		= arrSatuan[i].Grup;
				
				if		( Grup == 'Panjang'){	strSatuanPanjang 	= strSatuanPanjang + '<option value="'+ NamaSatuan +'">'+ NamaSatuan +'</option>';	}
				else if	( Grup == 'Lebar')	{	strSatuanLebar 		= strSatuanLebar + '<option value="'+ NamaSatuan +'">'+ NamaSatuan +'</option>';	}
				else if	( Grup == 'Tinggi')	{	strSatuanTinggi 	= strSatuanTinggi + '<option value="'+ NamaSatuan +'">'+ NamaSatuan +'</option>';	}
				else if	( Grup == 'Volume')	{	strSatuanVolume 	= strSatuanVolume + '<option value="'+ NamaSatuan +'">'+ NamaSatuan +'</option>';	}
				else if	( Grup == 'Berat')	{	strSatuanBerat 		= strSatuanBerat + '<option value="'+ NamaSatuan +'">'+ NamaSatuan +'</option>';	}
			}
			
			for( i=0 ; i< StringSKU.StringSKUMenu.length ; i++ )
			{
				var RandBosnetID = Math.random() * 1000000000000000000;
				
				var SKU_NamaProduk = StringSKU.StringSKUMenu[i].SKU_NamaProduk;
				var SKU_Kode = StringSKU.StringSKUMenu[i].SKU_kode;
				
				var Kemasan = StringSKU.StringSKUMenu[i].kemasan;
				
				var Berat = StringSKU.StringSKUMenu[i].berat;
				var Unit = StringSKU.StringSKUMenu[i].unit;
				
				var nama1 = StringSKU.StringSKUMenu[i].nama1;
				var nama2 = StringSKU.StringSKUMenu[i].nama2;
				
				var nama3 = StringSKU.StringSKUMenu[i].nama3;
				var nama4 = StringSKU.StringSKUMenu[i].nama4;
				
				var str = '';
				
				str = str + '<tr>';
				str = str + '	<input type="hidden" class="hdnama1" value="'+ nama1 +'" />';							
				str = str + '	<input type="hidden" class="hdnama2" value="'+ nama2 +'" />';							
				str = str + '	<input type="hidden" class="hdnama3" value="'+ nama3 +'" />';	
				str = str + '	<input type="hidden" class="hdnama4" value="'+ nama4 +'" />';	
				str = str + '	<input type="hidden" class="hdunit" value="'+ Unit +'" />';	
				
				str = str + '	<td><button type="button" class="form-control btn btn-danger" onclick="HapusGenerated(this.parentNode.parentNode.rowIndex)"><i class="fa fa-times"></i></button></td>';							
				
				str = str + '	<td><button type="button" class="form-control btn btn-primary" onclick="Copas(this.parentNode.parentNode.rowIndex)"><i class="fa fa-paste"></i> &nbsp;&nbsp;<i class="fa fa-angle-right"></i></button></td>';							
				str = str + '	<input type="hidden" class="txtSKU_Kode" value="'+ SKU_Kode +'" />';							
				str = str + '	<td><input type="text" style="width: 500px;" class="txtSKU_NamaProduk form-control" disabled="disabled" value="'+ SKU_NamaProduk +'" /></td>';
				str = str + '	<td><button type="button" id="btnviewSKUBosnet_ID" class="btn btn-primary btnviewSKUBosnet_ID" onclick="ViewBosnet(\''+ RandBosnetID +'\',\''+ SKU_NamaProduk +'\')" ><i class="fa fa-pencil"></i> Isi Bosnet ID</button></td>';
				str = str + '	<td><input type="text" class="txtSKU_Deskripsi form-control" /></td>';
				str = str + '	<td><input type="text" class="txtSKU_Kemasan form-control" value="'+ Kemasan +'" disabled="disabled" /></td>';
				str = str + '	<td><input type="text" class="txtSKU_Satuan form-control" value="'+ nama1 +'" disabled="disabled" /></td>';
				str = str + '	<td><select class="cbSKU_Kondisi form-control">'+ strSKU_Kondisi +'</select></td>';
				str = str + '	<td><input type="text" class="txtSKU_Origin form-control" /></td>';
				str = str + '	<td><input type="number" class="txtSKU_HargaJual form-control" value="0" onchange="CheckNumberSaved( this.parentNode.parentNode.rowIndex, \'txtSKU_HargaJual\' )" /></td>';
				str = str + '	<td><input type="number" class="txtSKU_SalesMinQty form-control" value="1" onchange="CheckNumberSaved( this.parentNode.parentNode.rowIndex, \'txtSKU_SalesMinQty\' )" /></td>';
				//str = str + '	<td><select class="cbSKU_PPh form-control">'+ strSKU_PPh +'</select></td>';
				//str = str + '	<td><input type="number" class="txtSKU_PPnBM_Persen form-control" value="0" /></td>';
				//str = str + '	<td><input type="number" class="txtSKU_PPn_Persen form-control" value="0" /></td>';
				str = str + '	<td><input type="checkbox" class="checkbox_form chSKU_IsActive" checked="checked" /></td>';
				str = str + '	<td><input type="checkbox" class="checkbox_form chSKU_IsJual" checked="checked" /></td>';
				str = str + '	<td><input type="number" class="txtSKU_WeightNetto form-control" value="'+ Berat +'" onchange="CheckNumberSaved( this.parentNode.parentNode.rowIndex, \'txtSKU_WeightNetto\' )" /></td>';
				str = str + '	<td><select class="cbSKU_WeightNettoUnit form-control">'+ strSatuanBerat +'</select></td>';
				str = str + '	<td><input type="number" class="txtSKU_WeightProduct form-control" value="0" onchange="CheckNumberSaved( this.parentNode.parentNode.rowIndex, \'txtSKU_WeightProduct\' )" /></td>';
				str = str + '	<td><select class="cbSKU_WeightProductUnit form-control">'+ strSatuanBerat +'</select></td>';
				str = str + '	<td><input type="number" class="txtSKU_WeightPackaging form-control" value="0" onchange="CheckNumberSaved( this.parentNode.parentNode.rowIndex, \'txtSKU_WeightPackaging\' )" /></td>';
				str = str + '	<td><select class="cbSKU_WeightPackagingUnit form-control">'+ strSatuanBerat +'</select></td>';
				str = str + '	<td><input type="number" class="txtSKU_WeightGift form-control" value="0" onchange="CheckNumberSaved( this.parentNode.parentNode.rowIndex, \'txtSKU_WeightGift\' )" /></td>';
				str = str + '	<td><select class="cbSKU_WeightGiftUnit form-control">'+ strSatuanBerat +'</select></td>';
				str = str + '	<td><input type="number" class="txtSKU_Length form-control" value="0" onchange="CheckNumberSaved( this.parentNode.parentNode.rowIndex, \'txtSKU_Length\' )" /></td>';
				str = str + '	<td><select class="cbSKU_LengthUnit form-control">'+ strSatuanPanjang +'</select></td>';
				str = str + '	<td><input type="number" class="txtSKU_Width form-control" value="0" onchange="CheckNumberSaved( this.parentNode.parentNode.rowIndex, \'txtSKU_Width\' )" /></td>';
				str = str + '	<td><select class="cbSKU_WidthUnit form-control">'+ strSatuanLebar +'</select></td>';
				str = str + '	<td><input type="number" class="txtSKU_Height form-control" value="0" onchange="CheckNumberSaved( this.parentNode.parentNode.rowIndex, \'txtSKU_Height\' )" /></td>';
				str = str + '	<td><select class="cbSKU_HeightUnit form-control">'+ strSatuanTinggi +'</select></td>';
				str = str + '	<td><input type="number" class="txtSKU_Volume form-control" value="0" onchange="CheckNumberSaved( this.parentNode.parentNode.rowIndex, \'txtSKU_Volume\' )" /></td>';
				str = str + '	<td><select class="cbSKU_VolumeUnit form-control">'+ strSatuanVolume +'</select></td>';
				str = str + '</tr>';
				
				
				$("#tablegeneratedstringsku > tbody").append( str );
				
				$(".txtSKU_WeightNetto").eq(i).val( Berat );
				$(".cbSKU_WeightNettoUnit").eq(i).val( Unit );
				
				$(".cbSKU_WeightNettoUnit").eq(i).css("width", "100%");
				$(".cbSKU_WeightNettoUnit").eq(i).select2({
					"theme": "bootstrap", "val": ""
				});
				
				$(".cbSKU_WeightProductUnit").eq(i).css("width", "100%");
				$(".cbSKU_WeightProductUnit").eq(i).select2({
					"theme": "bootstrap", "val": ""
				});
				
				$(".cbSKU_WeightPackagingUnit").eq(i).css("width", "100%");
				$(".cbSKU_WeightPackagingUnit").eq(i).select2({
					"theme": "bootstrap", "val": ""
				});
				
				$(".cbSKU_WeightGiftUnit").eq(i).css("width", "100%");
				$(".cbSKU_WeightGiftUnit").eq(i).select2({
					"theme": "bootstrap", "val": ""
				});
				
				$(".cbSKU_HeightUnit").eq(i).css("width", "100%");
				$(".cbSKU_HeightUnit").eq(i).select2({
					"theme": "bootstrap", "val": ""
				});
				
				$(".cbSKU_LengthUnit").eq(i).css("width", "100%");
				$(".cbSKU_LengthUnit").eq(i).select2({
					"theme": "bootstrap", "val": ""
				});
				
				$(".cbSKU_VolumeUnit").eq(i).css("width", "100%");
				$(".cbSKU_VolumeUnit").eq(i).select2({
					"theme": "bootstrap", "val": ""
				});
				
				$(".cbSKU_WidthUnit").eq(i).css("width", "100%");
				$(".cbSKU_WidthUnit").eq(i).select2({
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
	*/
	$("#btntambahsku").click
	(
		function()
		{
			
			/*var strSKU_PPh = '';
			strSKU_PPh = strSKU_PPh + '<option value="1">Include</option>';
			strSKU_PPh = strSKU_PPh + '<option value="2">Exclude</option>';
			strSKU_PPh = strSKU_PPh + '<option value="3">None</option>';
			*/
			
			//$(".cbSKU_Kondisi").html('');
			
			var strSKU_Kondisi = '';
			strSKU_Kondisi = strSKU_Kondisi + '<option value="Baru">Baru</option>';
			strSKU_Kondisi = strSKU_Kondisi + '<option value="Bekas">Bekas</option>';
			
			var strSatuanPanjang = '';
			var strSatuanLebar = '';
			var strSatuanTinggi = '';
			var strSatuanVolume = '';
			var strSatuanBerat = '';
			for( i=0 ; i< arrSatuan.length ; i++ )
			{
				var KodeSatuan 	= arrSatuan[i].KodeSatuan;
				var NamaSatuan 	= arrSatuan[i].NamaSatuan;
				var Grup		= arrSatuan[i].Grup;
				
				if		( Grup == 'Panjang'){	strSatuanPanjang 	= strSatuanPanjang + '<option value="'+ NamaSatuan +'">'+ NamaSatuan +'</option>';	}
				else if	( Grup == 'Lebar')	{	strSatuanLebar 		= strSatuanLebar + '<option value="'+ NamaSatuan +'">'+ NamaSatuan +'</option>';	}
				else if	( Grup == 'Tinggi')	{	strSatuanTinggi 	= strSatuanTinggi + '<option value="'+ NamaSatuan +'">'+ NamaSatuan +'</option>';	}
				else if	( Grup == 'Volume')	{	strSatuanVolume 	= strSatuanVolume + '<option value="'+ NamaSatuan +'">'+ NamaSatuan +'</option>';	}
				else if	( Grup == 'Berat')	{	strSatuanBerat 		= strSatuanBerat + '<option value="'+ NamaSatuan +'">'+ NamaSatuan +'</option>';	}
			}
			
			var RandBosnetID = Math.random() * 1000000000000000000;
			
			var strkemasan = '';
			for( i=0 ; i<arrVarianDKemasan.length ; i++ )
			{
				var VarianDetail_Nama = arrVarianDKemasan[i].VarianDetail_Nama;
				
				strkemasan = strkemasan + '<option value="'+ VarianDetail_Nama +'">'+ VarianDetail_Nama +'</option>';
			}
			
			var strsatuan = '';
			for( i=0 ; i<arrVarianDSatuan.length ; i++ )
			{
				var VarianDetail_Nama = arrVarianDSatuan[i].VarianDetail_Nama;
				
				strsatuan = strsatuan + '<option value="'+ VarianDetail_Nama +'">'+ VarianDetail_Nama +'</option>';
			}
			
			var str = '';
			
			str = str + '<tr>';
			str = str + '	<td><button type="button" class="form-control btn btn-danger" onclick="HapusGenerated(this.parentNode.parentNode.rowIndex)"><i class="fa fa-times"></i></button></td>';							
			str = str + '	<td><button type="button" class="form-control btn btn-primary" onclick="Copas(this.parentNode.parentNode.rowIndex)"><i class="fa fa-paste"></i> &nbsp;&nbsp;<i class="fa fa-angle-right"></i></button></td>';							
			//str = str + '	<td><input type="hidden" class="txtSKU_Kode" /></td>';							
			str = str + '	<td><input type="text" style="width: 500px;" class="txtSKU_NamaProduk form-control" value="" /></td>';
			str = str + '	<td><button type="button" id="btnviewSKUBosnet_ID" class="btn btn-primary btnviewSKUBosnet_ID" onclick="ViewBosnet(\''+ RandBosnetID +'\', this.parentNode.parentNode.rowIndex )" ><i class="fa fa-pencil"></i> Isi Bosnet ID</button></td>';
			str = str + '	<td><input type="text" class="txtSKU_Deskripsi form-control" /></td>';
			str = str + '	<td><select class="cbSKU_Kemasan form-control">'+ strkemasan +'</select></td>';
			str = str + '	<td><select class="cbSKU_Satuan form-control">'+ strsatuan +'</select></td>';
			str = str + '	<td><select class="cbSKU_Kondisi form-control">'+ strSKU_Kondisi +'</select></td>';
			str = str + '	<td><input type="text" class="txtSKU_Origin form-control" /></td>';
			str = str + '	<td><input type="number" class="txtSKU_HargaJual form-control" value="0" onchange="CheckNumberSaved( this.parentNode.parentNode.rowIndex, \'txtSKU_HargaJual\' )" /></td>';
			str = str + '	<td><input type="number" class="txtSKU_SalesMinQty form-control" value="1" onchange="CheckNumberSaved( this.parentNode.parentNode.rowIndex, \'txtSKU_SalesMinQty\' )" /></td>';
			//str = str + '	<td><select class="cbSKU_PPh form-control">'+ strSKU_PPh +'</select></td>';
			//str = str + '	<td><input type="number" class="txtSKU_PPnBM_Persen form-control" value="0" /></td>';
			//str = str + '	<td><input type="number" class="txtSKU_PPn_Persen form-control" value="0" /></td>';
			str = str + '	<td><input type="checkbox" class="checkbox_form chSKU_IsActive" checked="checked" /></td>';
			str = str + '	<td><input type="number" class="txtSKU_WeightNetto form-control" value="0" onchange="CheckNumberSaved( this.parentNode.parentNode.rowIndex, \'txtSKU_WeightNetto\' )" /></td>';
			str = str + '	<td><select class="cbSKU_WeightNettoUnit form-control">'+ strSatuanBerat +'</select></td>';
			str = str + '	<td><input type="number" class="txtSKU_WeightProduct form-control" value="0" onchange="CheckNumberSaved( this.parentNode.parentNode.rowIndex, \'txtSKU_WeightProduct\' )" /></td>';
			str = str + '	<td><select class="cbSKU_WeightProductUnit form-control">'+ strSatuanBerat +'</select></td>';
			str = str + '	<td><input type="number" class="txtSKU_WeightPackaging form-control" value="0" onchange="CheckNumberSaved( this.parentNode.parentNode.rowIndex, \'txtSKU_WeightPackaging\' )" /></td>';
			str = str + '	<td><select class="cbSKU_WeightPackagingUnit form-control">'+ strSatuanBerat +'</select></td>';
			str = str + '	<td><input type="number" class="txtSKU_WeightGift form-control" value="0" onchange="CheckNumberSaved( this.parentNode.parentNode.rowIndex, \'txtSKU_WeightGift\' )" /></td>';
			str = str + '	<td><select class="cbSKU_WeightGiftUnit form-control">'+ strSatuanBerat +'</select></td>';
			str = str + '	<td><input type="number" class="txtSKU_Length form-control" value="0" onchange="CheckNumberSaved( this.parentNode.parentNode.rowIndex, \'txtSKU_Length\' )" /></td>';
			str = str + '	<td><select class="cbSKU_LengthUnit form-control">'+ strSatuanPanjang +'</select></td>';
			str = str + '	<td><input type="number" class="txtSKU_Width form-control" value="0" onchange="CheckNumberSaved( this.parentNode.parentNode.rowIndex, \'txtSKU_Width\' )" /></td>';
			str = str + '	<td><select class="cbSKU_WidthUnit form-control">'+ strSatuanLebar +'</select></td>';
			str = str + '	<td><input type="number" class="txtSKU_Height form-control" value="0" onchange="CheckNumberSaved( this.parentNode.parentNode.rowIndex, \'txtSKU_Height\' )" /></td>';
			str = str + '	<td><select class="cbSKU_HeightUnit form-control">'+ strSatuanTinggi +'</select></td>';
			str = str + '	<td><input type="number" class="txtSKU_Volume form-control" value="0" onchange="CheckNumberSaved( this.parentNode.parentNode.rowIndex, \'txtSKU_Volume\' )" /></td>';
			str = str + '	<td><select class="cbSKU_VolumeUnit form-control">'+ strSatuanVolume +'</select></td>';
			str = str + '</tr>';
			
			
			$("#tablegeneratedstringsku > tbody").append( str );
			
			//$(".txtSKU_WeightNetto").eq(i).val( Berat );
			//$(".cbSKU_WeightNettoUnit").eq(i).val( Unit );
			
			$(".cbSKU_WeightNettoUnit").eq(i).css("width", "100%");
			$(".cbSKU_WeightNettoUnit").eq(i).select2({
				"theme": "bootstrap", "val": ""
			});
			
			$(".cbSKU_WeightProductUnit").eq(i).css("width", "100%");
			$(".cbSKU_WeightProductUnit").eq(i).select2({
				"theme": "bootstrap", "val": ""
			});
			
			$(".cbSKU_WeightPackagingUnit").eq(i).css("width", "100%");
			$(".cbSKU_WeightPackagingUnit").eq(i).select2({
				"theme": "bootstrap", "val": ""
			});
			
			$(".cbSKU_WeightGiftUnit").eq(i).css("width", "100%");
			$(".cbSKU_WeightGiftUnit").eq(i).select2({
				"theme": "bootstrap", "val": ""
			});
			
			$(".cbSKU_HeightUnit").eq(i).css("width", "100%");
			$(".cbSKU_HeightUnit").eq(i).select2({
				"theme": "bootstrap", "val": ""
			});
			
			$(".cbSKU_LengthUnit").eq(i).css("width", "100%");
			$(".cbSKU_LengthUnit").eq(i).select2({
				"theme": "bootstrap", "val": ""
			});
			
			$(".cbSKU_VolumeUnit").eq(i).css("width", "100%");
			$(".cbSKU_VolumeUnit").eq(i).select2({
				"theme": "bootstrap", "val": ""
			});
			
			$(".cbSKU_WidthUnit").eq(i).css("width", "100%");
			$(".cbSKU_WidthUnit").eq(i).select2({
				"theme": "bootstrap", "val": ""
			});

			$(".cbSKU_Kemasan").css("width", "100%");
			$(".cbSKU_Kemasan").select2({
				"theme": "bootstrap", "val": ""
			});
			
			$(".cbSKU_Satuan").css("width", "100%");
			$(".cbSKU_Satuan").select2({
				"theme": "bootstrap", "val": ""
			});
			
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
		}
	);
	
	function ViewBosnet( RandBosnetID, P_SKU_NamaProduk )
	{
		$("#txtRandBosnetID").val( RandBosnetID );
		$("#txtBosnet_SKU_NamaProduk").val( P_SKU_NamaProduk );
		
		$("#tbskubosnet tbody").html('');
			
		for( i=0 ; i<arrSKUBosnet_ID.length ; i++ )
		{
			var Bosnet_ID = arrSKUBosnet_ID[i].Bosnet_ID;
			var SKU_NamaProduk = arrSKUBosnet_ID[i].SKU_NamaProduk;
			
			if( SKU_NamaProduk == P_SKU_NamaProduk )
			{				
				var str = ''; 
				str = str + '<tr>';
				str = str + '	<td width="80%"><input type="text" class="txtSKUBosnet_ID form-control" value="'+ Bosnet_ID +'" /></td>';
				str = str + '	<td width="20%"><button type="button" class="btndeletebosnetid btn btn-danger" onclick="DeleteBosnet( this.parentNode.parentNode.rowIndex )"><i class="fa fa-trash-o"></i></button></td>';
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
			str = str + '	<td width="80%"><input type="text" class="txtSKUBosnet_ID form-control" /></td>';
			str = str + '	<td width="20%"><button type="button" class="btndeletebosnetid btn btn-danger" onclick="DeleteBosnet( this.parentNode.parentNode.rowIndex )"><i class="fa fa-trash-o"></i></button></td>';
			str = str + '</tr>';
			
			$("#tbskubosnet tbody").append( str );
		}
	);
	
	function DeleteBosnet( Idx )
	{
		$("#tbskubosnet tbody tr:eq("+ Idx +")").remove();
	}
	
	$("#btnsaveskubosnetid").click
	(
		function()
		{
			var Rand = $("#txtRandBosnetID").val();
			var P_SKU_NamaProduk = $("#txtBosnet_SKU_NamaProduk").val();
			
			// bersihin dulu bosnet id nya
			for( i= arrSKUBosnet_ID.length - 1 ; i>= 0 ; i-- )
			{
				var Bosnet_ID 		= arrSKUBosnet_ID[i].Bosnet_ID;
				var SKU_NamaProduk 	= arrSKUBosnet_ID[i].SKU_NamaProduk;
				
				if( P_SKU_NamaProduk == SKU_NamaProduk )
				{
					arrSKUBosnet_ID.splice( i, 1);
				}
			}
			
			var ltbbosnet = $(".txtSKUBosnet_ID").length;
			for( i=0 ; i<ltbbosnet ; i++ )
			{
				var Bosnet_ID = $(".txtSKUBosnet_ID").eq(i).val();
				
				arrSKUBosnet_ID.push( { RandID : Rand, SKU_NamaProduk : P_SKU_NamaProduk, Bosnet_ID : Bosnet_ID } );
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
			Proses( 0 );
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
			
			//var SKU_Kode 			= $("#txtS_SKU_Kode").val();							
			//var SKU_NamaProduk 	= $("#txtS_SKU_NamaProduk").val();
			var SKU_Deskripsi 		= $("#txtS_SKU_Deskripsi").val();
			var SKU_Kondisi 		= $("#cbS_SKU_Kondisi option:selected").val();
			var SKU_Origin 			= $("#txtS_SKU_Origin").val();
			var SKU_HargaJual 		= $("#txtS_SKU_HargaJual").val();
			var SKU_SalesMinQty 	= $("#txtS_SKU_SalesMinQty").val();
			//var SKU_PPh 			= $("#cbS_SKU_PPh").val();
			//var SKU_PPnBM_Persen 	= $("#txtS_SKU_PPnBM_Persen").val();
			//var SKU_PPn_Persen		= $("#txtS_SKU_PPn_Persen").val();
			var SKU_IsActive		= $("#chS_SKU_IsActive").prop('checked');
			var SKU_IsJual			= 0; //$("#chS_SKU_IsJual").prop('checked');
			//var SKU_WeightNetto			= $("#txtS_SKU_WeightNetto").val();
			//var SKU_WeightNettoUnit		= $("#cbS_SKU_WeightNettoUnit option:selected").val();
			var SKU_WeightProduct		= $("#txtS_SKU_WeightProduct").val();
			var SKU_WeightProductUnit	= $("#cbS_SKU_WeightProductUnit option:selected").val();
			var SKU_WeightPackaging		= $("#txtS_SKU_WeightPackaging").val();
			var SKU_WeightPackagingUnit	= $("#cbS_SKU_WeightPackagingUnit option:selected").val();
			var SKU_WeightGift			= $("#txtS_SKU_WeightGift").val();
			var SKU_WeightGiftUnit		= $("#cbS_SKU_WeightGiftUnit option:selected").val();
			var SKU_Length			= $("#txtS_SKU_Length").val();
			var SKU_LengthUnit 		= $("#cbS_SKU_LengthUnit option:selected").val();
			var SKU_Width			= $("#txtS_SKU_Width").val();
			var SKU_WidthUnit		= $("#cbS_SKU_WidthUnit option:selected").val();
			var SKU_Height			= $("#txtS_SKU_Height").val();
			var SKU_HeightUnit		= $("#cbS_SKU_HeightUnit option:selected").val();
			var SKU_Volume			= $("#txtS_SKU_Volume").val();
			var SKU_VolumeUnit		= $("#cbS_SKU_VolumeUnit option:selected").val();
			
			if( SKU_IsActive == true ) 	{ SKU_IsActive = 1;	}
			else					 	{ SKU_IsActive = 0;	}
			
			if( SKU_IsJual == true ) 	{ SKU_IsJual = 1;	}
			else					 	{ SKU_IsJual = 0;	}
			
			arrSalin = [];
			
			arrSalin.push({	SKU_Deskripsi: SKU_Deskripsi, SKU_Kondisi: SKU_Kondisi, SKU_Origin: SKU_Origin, SKU_HargaJual: SKU_HargaJual,
							SKU_SalesMinQty: SKU_SalesMinQty, /*SKU_PPh: SKU_PPh, SKU_PPnBM_Persen: SKU_PPnBM_Persen, SKU_PPn_Persen: SKU_PPn_Persen, */
							SKU_IsActive: SKU_IsActive, SKU_IsJual: SKU_IsJual, 
							//SKU_WeightNetto: SKU_WeightNetto, SKU_WeightNettoUnit: SKU_WeightNettoUnit, 
							SKU_WeightProduct: SKU_WeightProduct, SKU_WeightProductUnit: SKU_WeightProductUnit, 
							SKU_WeightPackaging: SKU_WeightPackaging, SKU_WeightPackagingUnit: SKU_WeightPackagingUnit, 
							SKU_WeightGift: SKU_WeightGift, SKU_WeightGiftUnit: SKU_WeightGiftUnit, 
							SKU_Length: SKU_Length, SKU_LengthUnit: SKU_LengthUnit, SKU_Width: SKU_Width, SKU_WidthUnit: SKU_WidthUnit, 
							SKU_Height: SKU_Height, SKU_HeightUnit: SKU_HeightUnit, SKU_Volume: SKU_Volume, SKU_VolumeUnit: SKU_VolumeUnit });
		
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
			var SKU_Deskripsi 		= $("#txtS_SKU_Deskripsi").val();
			var SKU_Kondisi 		= $("#cbS_SKU_Kondisi option:selected").val();
			var SKU_Origin 			= $("#txtS_SKU_Origin").val();
			var SKU_HargaJual 		= $("#txtS_SKU_HargaJual").val();
			var SKU_SalesMinQty 	= $("#txtS_SKU_SalesMinQty").val();
			//var SKU_PPh 			= $("#cbS_SKU_PPh").val();
			//var SKU_PPnBM_Persen 	= $("#txtS_SKU_PPnBM_Persen").val();
			//var SKU_PPn_Persen		= $("#txtS_SKU_PPn_Persen").val();
			var SKU_IsActive		= $("#chS_SKU_IsActive").prop('checked');
			var SKU_IsJual			= 0; //$("#chS_SKU_IsJual").prop('checked');
			//var SKU_WeightNetto			= $("#txtS_SKU_WeightNetto").val();
			//var SKU_WeightNettoUnit		= $("#cbS_SKU_WeightNettoUnit option:selected").val();
			var SKU_WeightProduct		= $("#txtS_SKU_WeightProduct").val();
			var SKU_WeightProductUnit	= $("#cbS_SKU_WeightProductUnit option:selected").val();
			
			var SKU_WeightPackaging		= $("#txtS_SKU_WeightPackaging").val();
			var SKU_WeightPackagingUnit	= $("#cbS_SKU_WeightPackagingUnit option:selected").val();
			var SKU_WeightGift			= $("#txtS_SKU_WeightGift").val();
			var SKU_WeightGiftUnit		= $("#cbS_SKU_WeightGiftUnit option:selected").val();
			var SKU_Length			= $("#txtS_SKU_Length").val();
			var SKU_LengthUnit 		= $("#cbS_SKU_LengthUnit option:selected").val();
			var SKU_Width			= $("#txtS_SKU_Width").val();
			var SKU_WidthUnit		= $("#cbS_SKU_WidthUnit option:selected").val();
			var SKU_Height			= $("#txtS_SKU_Height").val();
			var SKU_HeightUnit		= $("#cbS_SKU_HeightUnit option:selected").val();
			var SKU_Volume			= $("#txtS_SKU_Volume").val();
			var SKU_VolumeUnit		= $("#cbS_SKU_VolumeUnit option:selected").val();
			
			var ldatasku = $(".txtSKU_NamaProduk").length;
			
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
					$(".txtSKU_Deskripsi").eq(i).val( SKU_Deskripsi );
					$(".cbSKU_Kondisi").eq(i).val( SKU_Kondisi );
					$(".txtSKU_Origin").eq(i).val( SKU_Origin );
					$(".txtSKU_HargaJual").eq(i).val( SKU_HargaJual );
					$(".txtSKU_SalesMinQty").eq(i).val( SKU_SalesMinQty );
					//$(".cbSKU_PPh").eq(i).val( SKU_PPh );
					//$(".txtSKU_PPnBM_Persen").eq(i).val( SKU_PPnBM_Persen );
					//$(".txtSKU_PPn_Persen").eq(i).val( SKU_PPn_Persen );
					
					$(".chSKU_IsActive").eq(i).prop('checked', SKU_IsActive );
					//$(".chSKU_IsJual").eq(i).prop('checked', SKU_IsJual );
					
					//$(".txtSKU_WeightNetto").eq(i).val( SKU_WeightNetto );
					//$(".cbSKU_WeightNettoUnit").eq(i).val( SKU_WeightNettoUnit );
					$(".txtSKU_WeightProduct").eq(i).val( SKU_WeightProduct );
					$(".cbSKU_WeightProductUnit").eq(i).val( SKU_WeightProductUnit ).change();
					$(".txtSKU_WeightPackaging").eq(i).val( SKU_WeightPackaging );
					$(".cbSKU_WeightPackagingUnit").eq(i).val( SKU_WeightPackagingUnit ).change();
					$(".txtSKU_WeightGift").eq(i).val( SKU_WeightGift );
					$(".cbSKU_WeightGiftUnit").eq(i).val( SKU_WeightGiftUnit ).change();
					
					$(".txtSKU_Length").eq(i).val( SKU_Length );
					$(".cbSKU_LengthUnit").eq(i).val( SKU_LengthUnit ).change();
					$(".txtSKU_Width").eq(i).val( SKU_Width );
					$(".cbSKU_WidthUnit").eq(i).val( SKU_WidthUnit ).change();
					$(".txtSKU_Height").eq(i).val( SKU_Height );
					$(".cbSKU_HeightUnit").eq(i).val( SKU_HeightUnit ).change();
					$(".txtSKU_Volume").eq(i).val( SKU_Volume );
					$(".cbSKU_VolumeUnit").eq(i).val( SKU_VolumeUnit ).change();
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
				var SKU_Deskripsi 			= arrSalin[0].SKU_Deskripsi;
				var SKU_Kondisi 			= arrSalin[0].SKU_Kondisi;
				var SKU_Origin 				= arrSalin[0].SKU_Origin;
				var SKU_HargaJual 			= arrSalin[0].SKU_HargaJual;
				var SKU_SalesMinQty 		= arrSalin[0].SKU_SalesMinQty;
				//var SKU_PPh 				= arrSalin[0].SKU_PPh;
				//var SKU_PPnBM_Persen 		= arrSalin[0].SKU_PPnBM_Persen;
				//var SKU_PPn_Persen			= arrSalin[0].SKU_PPn_Persen;
				var SKU_IsActive			= arrSalin[0].SKU_IsActive;
				var SKU_IsJual				= 0; //arrSalin[0].SKU_IsJual;
				
				//var SKU_WeightNetto			= arrSalin[0].SKU_WeightNetto;
				//var SKU_WeightNettoUnit		= arrSalin[0].SKU_WeightNettoUnit;
				var SKU_WeightProduct		= arrSalin[0].SKU_WeightProduct;
				var SKU_WeightProductUnit	= arrSalin[0].SKU_WeightProductUnit;
				var SKU_WeightPackaging		= arrSalin[0].SKU_WeightPackaging;
				var SKU_WeightPackagingUnit	= arrSalin[0].SKU_WeightPackagingUnit;
				var SKU_WeightGift			= arrSalin[0].SKU_WeightGift;
				var SKU_WeightGiftUnit		= arrSalin[0].SKU_WeightGiftUnit;
				
				var SKU_Length				= arrSalin[0].SKU_Length;
				var SKU_LengthUnit 			= arrSalin[0].SKU_LengthUnit;
				var SKU_Width				= arrSalin[0].SKU_Width;
				var SKU_WidthUnit			= arrSalin[0].SKU_WidthUnit;
				var SKU_Height				= arrSalin[0].SKU_Height;
				var SKU_HeightUnit			= arrSalin[0].SKU_HeightUnit;
				var SKU_Volume				= arrSalin[0].SKU_Volume;
				var SKU_VolumeUnit			= arrSalin[0].SKU_VolumeUnit;
				
				if( SKU_IsActive == 1 )	{ SKU_IsActive = true;	}
				else					{ SKU_IsActive = false;	}
				
				if( SKU_IsJual == 1 )	{ SKU_IsJual = true;	}
				else					{ SKU_IsJual = false;	}
				
				$(".txtSKU_Deskripsi").eq(i).val( SKU_Deskripsi );
				$(".cbSKU_Kondisi").eq(i).val( SKU_Kondisi );
				$(".txtSKU_Origin").eq(i).val( SKU_Origin );
				$(".txtSKU_HargaJual").eq(i).val( SKU_HargaJual );
				$(".txtSKU_SalesMinQty").eq(i).val( SKU_SalesMinQty );
				//$(".cbSKU_PPh").eq(i).val( SKU_PPh );
				//$(".txtSKU_PPnBM_Persen").eq(i).val( SKU_PPnBM_Persen );
				//$(".txtSKU_PPn_Persen").eq(i).val( SKU_PPn_Persen );
				
				$(".chSKU_IsActive").eq(i).prop('checked', SKU_IsActive );
				//$(".chSKU_IsJual").eq(i).prop('checked', SKU_IsJual );
				
				//$(".txtSKU_WeightNetto").eq(i).val( SKU_WeightNetto );
				//$(".cbSKU_WeightNettoUnit").eq(i).val( SKU_WeightNettoUnit );
				$(".txtSKU_WeightProduct").eq(i).val( SKU_WeightProduct );
				$(".cbSKU_WeightProductUnit").eq(i).val( SKU_WeightProductUnit );
				$(".txtSKU_WeightPackaging").eq(i).val( SKU_WeightPackaging );
				$(".cbSKU_WeightPackagingUnit").eq(i).val( SKU_WeightPackagingUnit );
				$(".txtSKU_WeightGift").eq(i).val( SKU_WeightGift );
				$(".cbSKU_WeightGiftUnit").eq(i).val( SKU_WeightGiftUnit );
				
				$(".txtSKU_Length").eq(i).val( SKU_Length );
				$(".cbSKU_LengthUnit").eq(i).val( SKU_LengthUnit );
				$(".txtSKU_Width").eq(i).val( SKU_Width );
				$(".cbSKU_WidthUnit").eq(i).val( SKU_WidthUnit );
				$(".txtSKU_Height").eq(i).val( SKU_Height );
				$(".cbSKU_HeightUnit").eq(i).val( SKU_HeightUnit );
				$(".txtSKU_Volume").eq(i).val( SKU_Volume );
				$(".cbSKU_VolumeUnit").eq(i).val( SKU_VolumeUnit );
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
			var SKUInduk_Keterangan = $("#txaskuinduk_ket").val();
			
			var Kategori1_ID = $("#cbKategori1 option:selected").val();
			var Kategori2_ID = $("#cbKategori2 option:selected").val();
			var Kategori3_ID = $("#cbKategori3 option:selected").val();
			var Kategori4_ID = $("#cbKategori4 option:selected").val();
			
			var Kategori9_ID = $("#cbKategori9 option:selected").val();
			var Kategori10_ID = $("#cbKategori10 option:selected").val();
			
			if( Kategori1_ID == '' || Kategori9_ID == '' || Kategori10_ID == '' )
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
				var ldatasku = $(".txtSKU_NamaProduk").length;
				
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
						//var nama1 = $(".hdnama1").eq(i).val();
						//var nama2 = $(".hdnama2").eq(i).val();
						//var nama3 = $(".hdnama3").eq(i).val();
						//var nama4 = $(".hdnama4").eq(i).val();
						//var unit = $(".hdunit").eq(i).val();
						
						var SKU_Kode = $(".txtSKU_Kode").eq(i).val();
						var SKU_NamaProduk = $(".txtSKU_NamaProduk").eq(i).val();
						var SKU_Kemasan = $(".cbSKU_Kemasan option:selected").eq(i).val();
						var SKU_Satuan = $(".cbSKU_Satuan option:selected").eq(i).val();
						var SKU_Deskripsi = $(".txtSKU_Deskripsi").eq(i).val();
						var SKU_Kondisi = $(".cbSKU_Kondisi").eq(i).val();
						var SKU_Origin = $(".txtSKU_Origin").eq(i).val();
						var SKU_HargaJual = $(".txtSKU_HargaJual").eq(i).val();
						var SKU_SalesMinQty = $(".txtSKU_SalesMinQty").eq(i).val();
						//var SKU_PPh = $(".cbSKU_PPh").eq(i).val();
						//var SKU_PPnBM_Persen = $(".txtSKU_PPnBM_Persen").eq(i).val();
						//var SKU_PPn_Persen = $(".txtSKU_PPn_Persen").eq(i).val();
						
						var SKU_IsActive = $(".chSKU_IsActive").eq(i).prop('checked');
						if( SKU_IsActive == true )	{ SKU_IsActive = 1;	}
						else						{ SKU_IsActive = 0;	}
						
						var SKU_IsJual = 0; //$(".chSKU_IsJual").eq(i).prop('checked');
						if( SKU_IsJual == true )	{ SKU_IsJual = 1;	}
						else						{ SKU_IsJual = 0;	}
						
						var SKU_WeightNetto		 	= $(".txtSKU_WeightNetto").eq(i).val();
						var SKU_WeightNettoUnit 	= $(".cbSKU_WeightNettoUnit option:selected").eq(i).val();
						var SKU_WeightProduct 		= $(".txtSKU_WeightProduct").eq(i).val();
						var SKU_WeightProductUnit 	= $(".cbSKU_WeightProductUnit option:selected").eq(i).val();
						var SKU_WeightPackaging 	= $(".txtSKU_WeightPackaging").eq(i).val();
						var SKU_WeightPackagingUnit = $(".cbSKU_WeightPackagingUnit option:selected").eq(i).val();
						var SKU_WeightGift 			= $(".txtSKU_WeightGift").eq(i).val();
						var SKU_WeightGiftUnit 		= $(".cbSKU_WeightGiftUnit option:selected").eq(i).val();
						
						var SKU_Length = $(".txtSKU_Length").eq(i).val();
						var SKU_LengthUnit = $(".cbSKU_LengthUnit option:selected").eq(i).val();
						var SKU_Width = $(".txtSKU_Width").eq(i).val();
						var SKU_WidthUnit = $(".cbSKU_WidthUnit option:selected").eq(i).val();
						var SKU_Height = $(".txtSKU_Height").eq(i).val();
						var SKU_HeightUnit = $(".cbSKU_HeightUnit option:selected").eq(i).val();
						var SKU_Volume = $(".txtSKU_Volume").eq(i).val();
						var SKU_VolumeUnit = $(".cbSKU_VolumeUnit option:selected").eq(i).val();
						
						arrSKU.push({	//nama1: nama1, nama2: nama2, nama3: nama3, nama4: nama4, unit: unit,
										//SKU_Kode: SKU_Kode, 
										SKU_NamaProduk: SKU_NamaProduk, SKU_Kemasan: SKU_Kemasan, SKU_Deskripsi: SKU_Deskripsi, SKU_Kondisi: SKU_Kondisi, SKU_Origin: SKU_Origin, SKU_HargaJual: SKU_HargaJual,
										SKU_SalesMinQty: SKU_SalesMinQty, SKU_Satuan: SKU_Satuan, /*SKU_PPh: SKU_PPh, SKU_PPnBM_Persen: SKU_PPnBM_Persen, SKU_PPn_Persen: SKU_PPn_Persen, */
										SKU_IsActive: SKU_IsActive, SKU_IsJual: SKU_IsJual, 
										SKU_WeightNetto: SKU_WeightNetto, SKU_WeightNettoUnit: SKU_WeightNettoUnit, 
										SKU_WeightProduct: SKU_WeightProduct, SKU_WeightProductUnit: SKU_WeightProductUnit, 
										SKU_WeightPackaging: SKU_WeightPackaging, SKU_WeightPackagingUnit: SKU_WeightPackagingUnit, 
										SKU_WeightGift: SKU_WeightGift, SKU_WeightGiftUnit: SKU_WeightGiftUnit, 
										SKU_Length: SKU_Length, SKU_LengthUnit: SKU_LengthUnit, SKU_Width: SKU_Width, SKU_WidthUnit: SKU_WidthUnit, 
										SKU_Height: SKU_Height, SKU_HeightUnit: SKU_HeightUnit, SKU_Volume: SKU_Volume, SKU_VolumeUnit: SKU_VolumeUnit	});
					}
					
					var SKUInduk_Jenis_PPn = $("#cbSKUInduk_Jenis_PPn option:selected").val();
					var SKUInduk_PPn_Persen = $("#txtSKUInduk_PPn_Persen").val();
					var SKUInduk_PPnBM_Persen = $("#txtSKUInduk_PPnBM_Persen").val();
					
					var SKUInduk_ID = $("#cbskuinduk option:selected").val();
		
					if( Kategori1_ID == '' ){	 Kategori1_ID = '0';	}
					if( Kategori2_ID == '' ){	 Kategori2_ID = '0';	}
					if( Kategori3_ID == '' ){	 Kategori3_ID = '0';	}
					if( Kategori4_ID == '' ){	 Kategori4_ID = '0';	}
					if( Kategori9_ID == '' ){	 Kategori9_ID = '0';	}
					if( Kategori10_ID == '' ){	 Kategori10_ID = '0';	}
					
					$.ajax(
					{
						type: 'POST',    
						url: "<?= base_url('SKUHadiah/SaveData') ?>",
						data:
						{
							SKUInduk_ID 				: SKUInduk_ID,
							SKUInduk_Keterangan 		: SKUInduk_Keterangan,
							
							Kategori1_ID				: Kategori1_ID,
							Kategori2_ID				: Kategori2_ID,
							Kategori3_ID				: Kategori3_ID,
							Kategori4_ID				: Kategori4_ID,
							
							Kategori9_ID				: Kategori9_ID,
							Kategori10_ID				: Kategori10_ID,
							
							SKUInduk_Jenis_PPn			: SKUInduk_Jenis_PPn,
							SKUInduk_PPn_Persen			: SKUInduk_PPn_Persen,
							SKUInduk_PPnBM_Persen		: SKUInduk_PPnBM_Persen,
							
							arrSKU 						: arrSKU,
							//arrDataVarianWajibDetail 	: arrDataVarianWajibDetail,
							//arrDataVarianDetail 		: arrDataVarianDetail,
							
							arrSKUBosnet_ID				: arrSKUBosnet_ID
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
								
								arrSKUBosnet_ID = [];
								
								$("#MainMenu").removeAttr('style');
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
	
	$("#txtS_SKU_HargaJual").change(		function(){	CheckNumber( "txtS_SKU_HargaJual" );		});
	$("#txtS_SKU_SalesMinQty").change(		function(){	CheckNumber( "txtS_SKU_SalesMinQty" );		});
	$("#txtS_SKU_WeightGift").change(		function(){	CheckNumber( "txtS_SKU_WeightGift" );		});
	$("#txtS_SKU_WeightPackaging").change(	function(){	CheckNumber( "txtS_SKU_WeightPackaging" );	});
	$("#txtS_SKU_WeightProduct").change(	function(){	CheckNumber( "txtS_SKU_WeightProduct" );	});
	//$("#txtS_SKU_WeightNetto").change(		function(){	CheckNumber( "txtS_SKU_WeightNetto" );		});
	$("#txtS_SKU_Length").change(			function(){	CheckNumber( "txtS_SKU_Length" );			});
	$("#txtS_SKU_Width").change(			function(){	CheckNumber( "txtS_SKU_Width" );			});
	$("#txtS_SKU_Height").change(			function(){	CheckNumber( "txtS_SKU_Height" );			});
	$("#txtS_SKU_Volume").change(			function(){	CheckNumber( "txtS_SKU_Volume" );			});
		
	function ResetForm()
	{
		$("#txaskuinduk_ket").val('');
		$("#txtS_SKU_Deskripsi").val('');
		$("#cbS_SKU_Kondisi").prop('selectedIndex',0);
		$("#txtS_SKU_Origin").val('');
		$("#txtS_SKU_HargaJual").val(0);
		$("#txtS_SKU_SalesMinQty").val(1);
		$("#chS_SKU_IsActive").prop('checked', true );
		//$("#chS_SKU_IsJual").prop('checked', true );
		
		//$("#txtS_SKU_WeightNetto").val(0);
		//$("#cbS_SKU_WeightNettoUnit").prop('selectedIndex',0);
		$("#txtS_SKU_WeightProduct").val(0);
		$("#cbS_SKU_WeightProductUnit").prop('selectedIndex',0);
		$("#txtS_SKU_WeightPackaging").val(0);
		$("#cbS_SKU_WeightPackagingUnit").prop('selectedIndex',0);
		$("#txtS_SKU_WeightGift").val(0);
		$("#cbS_SKU_WeightGiftUnit").prop('selectedIndex',0);
		
		$("#txtS_SKU_Length").val(0);
		$("#cbS_SKU_LengthUnit").prop('selectedIndex',0);
		$("#txtS_SKU_Width").val(0);
		$("#cbS_SKU_WidthUnit").prop('selectedIndex',0);
		$("#txtS_SKU_Height").val(0);
		$("#cbS_SKU_HeightUnit").prop('selectedIndex',0);
		$("#txtS_SKU_Volume").val(0);
		$("#cbS_SKU_VolumeUnit").prop('selectedIndex',0);
		
		$("#tablegeneratedstringsku > tbody").html('');
		$("#cbKategori1").prop('selectedIndex',0).change();
		$("#kat2").attr('style','display: none;');
		$("#kat3").attr('style','display: none;');
		$("#kat4").attr('style','display: none;');
		
		$("#updatetxtS_SKU_Deskripsi").val('');
		$("#updatecbS_SKU_Kondisi").prop('selectedIndex',0);
		$("#updatetxtS_SKU_Origin").val('');
		$("#updatetxtS_SKU_HargaJual").val(0);
		$("#updatetxtS_SKU_SalesMinQty").val(1);
		$("#updatechS_SKU_IsActive").prop('checked', false );
		//$("#updatechS_SKU_IsJual").prop('checked', false );
		
		//$("#updatetxtS_SKU_WeightNetto").val(0);
		//$("#updatecbS_SKU_WeightNettoUnit").prop('selectedIndex',0);
		$("#updatetxtS_SKU_WeightProduct").val(0);
		$("#updatecbS_SKU_WeightProductUnit").prop('selectedIndex',0);
		$("#updatetxtS_SKU_WeightPackaging").val(0);
		$("#updatecbS_SKU_WeightPackagingUnit").prop('selectedIndex',0);
		$("#updatetxtS_SKU_WeightGift").val(0);
		$("#updatecbS_SKU_WeightGiftUnit").prop('selectedIndex',0);
		
		$("#updatetxtS_SKU_Length").val(0);
		$("#updatecbS_SKU_LengthUnit").prop('selectedIndex',0);
		$("#updatetxtS_SKU_Width").val(0);
		$("#updatecbS_SKU_WidthUnit").prop('selectedIndex',0);
		$("#updatetxtS_SKU_Height").val(0);
		$("#updatecbS_SKU_HeightUnit").prop('selectedIndex',0);
		$("#updatetxtS_SKU_Volume").val(0);
		$("#updatecbS_SKU_VolumeUnit").prop('selectedIndex',0);
	
		$("#updatetablegeneratedstringsku > tbody").html('');
		$("#updatecbKategori1").prop('selectedIndex',0).change();
		$("#updatekat2").attr('style','display: none;');
		$("#updatekat3").attr('style','display: none;');
		$("#updatekat4").attr('style','display: none;');
	}
	
	
</script>