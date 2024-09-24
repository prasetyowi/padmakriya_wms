<script type="text/javascript">	
	arrUpdateSKUBosnet_ID = [];
	arrUpdateUbahSKUBosnet_ID = [];
	
	$("#updatebtnback").click
	(
		function()
		{
			$("#updatepreviewkembalikelistskuinduk").modal('show');
		}
	);
	
	
	$("#updatetxaskuinduk_ket").keyup
	(
		function( event )
		{
			var lket = $("#updatetxaskuinduk_ket").val().length;
			lket = 8000 - lket;
			
			if( lket == 0 )	{	lket = '<strong style="color: red;">'+ lket +'</strong>';	}
			else			{	lket = '<strong style="color: black;">'+ lket +'</strong>';	}
			
			$("#updatelbjumlahket").html( 'Sisa Karakter : '+ lket );
		}
	);
	
	$("#updatetxaskuinduk_ket").keydown
	(
		function( event )
		{
			var lket = $("#updatetxaskuinduk_ket").val().length;
			lket = 8000 - lket;
			
			if( lket == 0 )	{	lket = '<strong style="color: red;">'+ lket +'</strong>';	}
			else			{	lket = '<strong style="color: black;">'+ lket +'</strong>';	}
			
			$("#updatelbjumlahket").html( 'Sisa Karakter : '+ lket );
		}
	);
	
	$("#updatebtnyesback").click
	(
		function()
		{
			ResetForm();
			
			arrUpdateSKUBosnet_ID = [];
			
			$("#MainMenu").removeAttr('style');
			$("#Ubah").attr('style','display: none;');
		}
	);
	
	$("#updatebtnsimpandatasku").click
	(
		function()
		{
			var Kategori1_ID = $("#updatecbKategori1 option:selected").val();
			var Kategori2_ID = $("#updatecbKategori2 option:selected").val();
			var Kategori3_ID = $("#updatecbKategori3 option:selected").val();
			var Kategori4_ID = $("#updatecbKategori4 option:selected").val();
			
			var Kategori9_ID = $("#updatecbKategori9 option:selected").val();
			var Kategori10_ID = $("#updatecbKategori10 option:selected").val();
			
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
				var ldatasku = $(".xupdatetxtSKU_NamaProduk").length;
				
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
						var SKU_ID 					= $(".xupdatehdSKU_ID").eq(i).val();
						var SKU_Kode 				= $(".xupdatetxtSKU_Kode").eq(i).val();
						
						var SKU_Kemasan 			= $(".xupdatecbSKU_Kemasan").eq(i).val();
						var SKU_Satuan 				= $(".xupdatecbSKU_Satuan").eq(i).val();
						
						var SKU_NamaProduk 		= $(".xupdatetxtSKU_NamaProduk").eq(i).val();
						var SKU_Deskripsi 		= $(".xupdatetxtSKU_Deskripsi").eq(i).val();
						var SKU_Kemasan 		= $(".xupdatecbSKU_Kemasan option:selected").eq(i).val();
						var SKU_Satuan	 		= $(".xupdatecbSKU_Satuan option:selected").eq(i).val();
						var SKU_Kondisi 		= $(".xupdatecbSKU_Kondisi").eq(i).val();
						var SKU_Origin 			= $(".xupdatetxtSKU_Origin").eq(i).val();
						var SKU_HargaJual 		= $(".xupdatetxtSKU_HargaJual").eq(i).val();
						var SKU_SalesMinQty 	= $(".xupdatetxtSKU_SalesMinQty").eq(i).val();
						//var SKU_PPh = $(".cbSKU_PPh").eq(i).val();
						//var SKU_PPnBM_Persen = $(".txtSKU_PPnBM_Persen").eq(i).val();
						//var SKU_PPn_Persen = $(".txtSKU_PPn_Persen").eq(i).val();
						
						var SKU_IsActive = $(".xupdatechSKU_IsActive").eq(i).prop('checked');
						if( SKU_IsActive == true )	{ SKU_IsActive = 1;	}
						else						{ SKU_IsActive = 0;	}
						
						var SKU_IsJual = 0; //$(".xupdatechSKU_IsJual").eq(i).prop('checked');
						if( SKU_IsJual == true )	{ SKU_IsJual = 1;	}
						else						{ SKU_IsJual = 0;	}
						
						var SKU_WeightNetto		 	= $(".xupdatetxtSKU_WeightNetto").eq(i).val();
						var SKU_WeightNettoUnit 	= $(".xupdatecbSKU_WeightNettoUnit option:selected").eq(i).val();
						var SKU_WeightProduct 		= $(".xupdatetxtSKU_WeightProduct").eq(i).val();
						var SKU_WeightProductUnit 	= $(".xupdatecbSKU_WeightProductUnit option:selected").eq(i).val();
						var SKU_WeightPackaging 	= $(".xupdatetxtSKU_WeightPackaging").eq(i).val();
						var SKU_WeightPackagingUnit = $(".xupdatecbSKU_WeightPackagingUnit option:selected").eq(i).val();
						var SKU_WeightGift 			= $(".xupdatetxtSKU_WeightGift").eq(i).val();
						var SKU_WeightGiftUnit 		= $(".xupdatecbSKU_WeightGiftUnit option:selected").eq(i).val();
						
						var SKU_Length 				= $(".xupdatetxtSKU_Length").eq(i).val();
						var SKU_LengthUnit 			= $(".xupdatecbSKU_LengthUnit option:selected").eq(i).val();
						var SKU_Width 				= $(".xupdatetxtSKU_Width").eq(i).val();
						var SKU_WidthUnit 			= $(".xupdatecbSKU_WidthUnit option:selected").eq(i).val();
						var SKU_Height 				= $(".xupdatetxtSKU_Height").eq(i).val();
						var SKU_HeightUnit 			= $(".xupdatecbSKU_HeightUnit option:selected").eq(i).val();
						var SKU_Volume 				= $(".xupdatetxtSKU_Volume").eq(i).val();
						var SKU_VolumeUnit 			= $(".xupdatecbSKU_VolumeUnit option:selected").eq(i).val();
						
						arrSKU.push({	SKU_ID: SKU_ID, SKU_Kode: SKU_Kode, SKU_NamaProduk: SKU_NamaProduk, SKU_Deskripsi: SKU_Deskripsi, SKU_Kemasan: SKU_Kemasan, SKU_Satuan: SKU_Satuan, SKU_Kondisi: SKU_Kondisi, SKU_Origin: SKU_Origin, SKU_HargaJual: SKU_HargaJual,
										SKU_SalesMinQty: SKU_SalesMinQty, /*SKU_PPh: SKU_PPh, SKU_PPnBM_Persen: SKU_PPnBM_Persen, SKU_PPn_Persen: SKU_PPn_Persen, */
										SKU_IsActive: SKU_IsActive, SKU_IsJual: SKU_IsJual, 
										SKU_WeightNetto: SKU_WeightNetto, SKU_WeightNettoUnit: SKU_WeightNettoUnit, 
										SKU_WeightProduct: SKU_WeightProduct, SKU_WeightProductUnit: SKU_WeightProductUnit, 
										SKU_WeightPackaging: SKU_WeightPackaging, SKU_WeightPackagingUnit: SKU_WeightPackagingUnit, 
										SKU_WeightGift: SKU_WeightGift, SKU_WeightGiftUnit: SKU_WeightGiftUnit, 
										SKU_Length: SKU_Length, SKU_LengthUnit: SKU_LengthUnit, SKU_Width: SKU_Width, SKU_WidthUnit: SKU_WidthUnit, 
										SKU_Height: SKU_Height, SKU_HeightUnit: SKU_HeightUnit, SKU_Volume: SKU_Volume, SKU_VolumeUnit: SKU_VolumeUnit });
					
					}
					
					var SKUInduk_Jenis_PPn 			= $("#updatecbSKUInduk_Jenis_PPn option:selected").val();
					var SKUInduk_PPn_Persen 		= $("#updatetxtSKUInduk_PPn_Persen").val();
					var SKUInduk_PPnBM_Persen 		= $("#updatetxtSKUInduk_PPnBM_Persen").val();
					
					var SKUInduk_ID 				= $("#updatecbskuinduk option:selected").val();
					var SKUInduk_Keterangan			= $("#updatetxaskuinduk_ket").val();
		
					if( Kategori1_ID == '' ){	 Kategori1_ID = '0';	}
					if( Kategori2_ID == '' ){	 Kategori2_ID = '0';	}
					if( Kategori3_ID == '' ){	 Kategori3_ID = '0';	}
					if( Kategori4_ID == '' ){	 Kategori4_ID = '0';	}
					if( Kategori9_ID == '' ){	 Kategori9_ID = '0';	}
					if( Kategori10_ID == '' ){	 Kategori10_ID = '0';	}
					
					$.ajax(
					{
						type: 'POST',    
						url: "<?= base_url('SKUHadiah/UpdateSaveData') ?>",
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
							
							arrSKUBosnet_ID				: arrUpdateSKUBosnet_ID
							/*
							arrDataVarianWajibDetail 	: arrDataVarianWajibDetail,
							arrDataVarianDetail 		: arrDataVarianDetail
							*/
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
								
								UpdateProses( 0 );
								
								$("#MainMenu").removeAttr('style');
								$("#Ubah").attr('style','display: none;');
								
								arrUpdateUbahSKUBosnet_ID = [];
								
								GetSKUIndukMenu();
							}
						}
					});	
				}
			}
		}
	);
	
	$("#updatebtnsimpandataubah").click
	(
		function()
		{
			var SKUInduk_Keterangan = $("#updatetxaskuinduk_ket").val();
			
			var Kategori1_ID = $("#updatecbKategori1 option:selected").val();
			var Kategori2_ID = $("#updatecbKategori2 option:selected").val();
			var Kategori3_ID = $("#updatecbKategori3 option:selected").val();
			var Kategori4_ID = $("#updatecbKategori4 option:selected").val();
			
			var Kategori9_ID = $("#updatecbKategori9 option:selected").val();
			var Kategori10_ID = $("#updatecbKategori10 option:selected").val();
			
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
				var ldatasku = $(".updatetxtSKU_NamaProduk").length;
				
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
						//var nama1 = $(".updatehdnama1").eq(i).val();
						//var nama2 = $(".updatehdnama2").eq(i).val();
						//var nama3 = $(".updatehdnama3").eq(i).val();
						//var nama4 = $(".updatehdnama4").eq(i).val();
						//var unit = $(".updatehdunit").eq(i).val();
						
						var SKU_Kode 				= $(".updatetxtSKU_Kode").eq(i).val();
						var SKU_NamaProduk 			= $(".updatetxtSKU_NamaProduk").eq(i).val();
						var SKU_Deskripsi 			= $(".updatetxtSKU_Deskripsi").eq(i).val();
						var SKU_Kemasan 			= $(".updatecbSKU_Kemasan option:selected").eq(i).val();
						var SKU_Satuan	 			= $(".updatecbSKU_Satuan option:selected").eq(i).val();
						var SKU_Kondisi 			= $(".updatecbSKU_Kondisi").eq(i).val();
						var SKU_Origin 				= $(".updatetxtSKU_Origin").eq(i).val();
						var SKU_HargaJual 			= $(".updatetxtSKU_HargaJual").eq(i).val();
						var SKU_SalesMinQty 		= $(".updatetxtSKU_SalesMinQty").eq(i).val();
						//var SKU_PPh = $(".cbSKU_PPh").eq(i).val();
						//var SKU_PPnBM_Persen = $(".txtSKU_PPnBM_Persen").eq(i).val();
						//var SKU_PPn_Persen = $(".txtSKU_PPn_Persen").eq(i).val();
						
						var SKU_IsActive = $(".updatechSKU_IsActive").eq(i).prop('checked');
						if( SKU_IsActive == true )	{ SKU_IsActive = 1;	}
						else						{ SKU_IsActive = 0;	}
						
						var SKU_IsJual = 0; //$(".updatechSKU_IsJual").eq(i).prop('checked');
						if( SKU_IsJual == true )	{ SKU_IsJual = 1;	}
						else						{ SKU_IsJual = 0;	}
						
						var SKU_WeightNetto		 	= $(".updatetxtSKU_WeightNetto").eq(i).val();
						var SKU_WeightNettoUnit 	= $(".updatecbSKU_WeightNettoUnit option:selected").eq(i).val();
						var SKU_WeightProduct 		= $(".updatetxtSKU_WeightProduct").eq(i).val();
						var SKU_WeightProductUnit 	= $(".updatecbSKU_WeightProductUnit option:selected").eq(i).val();
						var SKU_WeightPackaging 	= $(".updatetxtSKU_WeightPackaging").eq(i).val();
						var SKU_WeightPackagingUnit = $(".updatecbSKU_WeightPackagingUnit option:selected").eq(i).val();
						var SKU_WeightGift 			= $(".updatetxtSKU_WeightGift").eq(i).val();
						var SKU_WeightGiftUnit 		= $(".updatecbSKU_WeightGiftUnit option:selected").eq(i).val();
						
						var SKU_Length 				= $(".updatetxtSKU_Length").eq(i).val();
						var SKU_LengthUnit 			= $(".updatecbSKU_LengthUnit option:selected").eq(i).val();
						var SKU_Width 				= $(".updatetxtSKU_Width").eq(i).val();
						var SKU_WidthUnit 			= $(".updatecbSKU_WidthUnit option:selected").eq(i).val();
						var SKU_Height 				= $(".updatetxtSKU_Height").eq(i).val();
						var SKU_HeightUnit 			= $(".updatecbSKU_HeightUnit option:selected").eq(i).val();
						var SKU_Volume 				= $(".updatetxtSKU_Volume").eq(i).val();
						var SKU_VolumeUnit 			= $(".updatecbSKU_VolumeUnit option:selected").eq(i).val();
					
						arrSKU.push({	//nama1: nama1, nama2: nama2, nama3: nama3, nama4: nama4, unit: unit, 
										SKU_Kode: SKU_Kode, SKU_NamaProduk: SKU_NamaProduk, SKU_Deskripsi: SKU_Deskripsi, SKU_Kemasan: SKU_Kemasan, SKU_Satuan: SKU_Satuan, SKU_Kondisi: SKU_Kondisi, SKU_Origin: SKU_Origin, SKU_HargaJual: SKU_HargaJual,
										SKU_SalesMinQty: SKU_SalesMinQty, /*SKU_PPh: SKU_PPh, SKU_PPnBM_Persen: SKU_PPnBM_Persen, SKU_PPn_Persen: SKU_PPn_Persen, */
										SKU_IsActive: SKU_IsActive, SKU_IsJual: SKU_IsJual, 
										SKU_WeightNetto: SKU_WeightNetto, SKU_WeightNettoUnit: SKU_WeightNettoUnit, 
										SKU_WeightProduct: SKU_WeightProduct, SKU_WeightProductUnit: SKU_WeightProductUnit, 
										SKU_WeightPackaging: SKU_WeightPackaging, SKU_WeightPackagingUnit: SKU_WeightPackagingUnit, 
										SKU_WeightGift: SKU_WeightGift, SKU_WeightGiftUnit: SKU_WeightGiftUnit, 
										SKU_Length: SKU_Length, SKU_LengthUnit: SKU_LengthUnit, SKU_Width: SKU_Width, SKU_WidthUnit: SKU_WidthUnit, 
										SKU_Height: SKU_Height, SKU_HeightUnit: SKU_HeightUnit, SKU_Volume: SKU_Volume, SKU_VolumeUnit: SKU_VolumeUnit });
					
					}
					
					var SKUInduk_Jenis_PPn = $("#updatecbSKUInduk_Jenis_PPn option:selected").val();
					var SKUInduk_PPn_Persen = $("#updatetxtSKUInduk_PPn_Persen").val();
					var SKUInduk_PPnBM_Persen = $("#updatetxtSKUInduk_PPnBM_Persen").val();
					
					var SKUInduk_ID = $("#updatecbskuinduk option:selected").val();
		
					if( Kategori1_ID == '' ){	 Kategori1_ID = '0';	}
					if( Kategori2_ID == '' ){	 Kategori2_ID = '0';	}
					if( Kategori3_ID == '' ){	 Kategori3_ID = '0';	}
					if( Kategori4_ID == '' ){	 Kategori4_ID = '0';	}
					if( Kategori9_ID == '' ){	 Kategori9_ID = '0';	}
					if( Kategori10_ID == '' ){	 Kategori10_ID = '0';	}
					
					$.ajax(
					{
						type: 'POST',    
						url: "<?= base_url('SKUHadiah/UpdateSaveDataUbah') ?>",
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
							arrSKUBosnet_ID				: arrUpdateUbahSKUBosnet_ID,
							//arrDataVarianWajibDetail 	: arrUpdateDataVarianWajibDetail,
							//arrDataVarianDetail 		: arrUpdateDataVarianDetail
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
								
								UpdateProses( 0 );
								
								$("#MainMenu").removeAttr('style');
								$("#Ubah").attr('style','display: none;');
								
								GetSKUIndukMenu();
							}
						}
					});	
				}
			}
		}
	);
	
	function UbahSKUInduk( SKUInduk_ID, SKUInduk_Nama )
	{
		$("#MainMenu").attr('style','display: none;');
		$("#Ubah").removeAttr('style');
		
		$.ajax(
		{
			type: 'POST',    
			url: "<?= base_url('SKUHadiah/GetUpdateSKUHadiahMenu') ?>",
			data: 
			{
				SKUInduk_ID : SKUInduk_ID
			},
			success: function( response )
			{
				if(response)
				{
					ChUpdateSKUIndukMenu( response, SKUInduk_ID, SKUInduk_Nama );
				}
			}
		});	
	}
	
	function ChUpdateSKUIndukMenu( JSONSKU, SKUInduk_ID, SKUInduk_Nama )
	{
		
		$("#updatecbKategori1").select2("destroy");
		$("#updatecbKategori2").select2("destroy");
		$("#updatecbKategori3").select2("destroy");
		$("#updatecbKategori4").select2("destroy");
			
		$("#updatecbKategori1").html('');
		$("#updatecbKategori2").html('');
		$("#updatecbKategori3").html('');
		$("#updatecbKategori4").html('');
		
		$("#updatecbKategori1").html('<option value="">--Pilih Kategori--</option>');
		$("#updatecbKategori2").html('<option value="">--Pilih Sub Kategori--</option>');
		$("#updatecbKategori3").html('<option value="">--Pilih Sub Kategori 2--</option>');
		$("#updatecbKategori4").html('<option value="">--Pilih Sub Kategori 3--</option>');	
		
		$("#updatecbskuinduk").html('<option value="'+ SKUInduk_ID +'">'+ SKUInduk_Nama +'</option>');
		$("#updatecbskuinduk").prop('disabled', true );
		
				
		var SKU = JSON.parse( JSONSKU );
		
		if( SKU.SKUMenu != 0 )
		{
			/*if( $.fn.DataTable.isDataTable('#updatetablegeneratedstringsku') ) 
			{
				$('#updatetablegeneratedstringsku').DataTable().destroy();
			}

			$('#updatetablegeneratedstringsku > tbody').empty();
			*/
			if( $.fn.DataTable.isDataTable('#updatetabledatasku') )
			{
				$('#updatetabledatasku').DataTable().destroy();
			}

			$('#updatetabledatasku > tbody').empty();
			
			if( $.fn.DataTable.isDataTable('#updateubahtabledatasku') )
			{
				$('#updateubahtabledatasku').DataTable().destroy();
			}

			$('#updateubahtabledatasku > tbody').empty();
			
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
			
			var strSKU_PPh = '';
			strSKU_PPh = strSKU_PPh + '<option value="1">Include</option>';
			strSKU_PPh = strSKU_PPh + '<option value="2">Exclude</option>';
			strSKU_PPh = strSKU_PPh + '<option value="3">None</option>';
			
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
			/*
			var strKemasan = '';
			
			for( i=0 ; i< arrKemasan.length ; i++ )
			{
				var KemasanID 	= arrKemasan[i].KemasanID;
				var KemasanKode = arrKemasan[i].KemasanKode;
				var KemasanNama	= arrKemasan[i].KemasanNama;
			
				strKemasan = strKemasan + '<option value="'+ KemasanID +'">'+ KemasanNama +'</option>';
			}
			*/
			for( i=0 ; i< SKU.SKUMenu.length ; i++ )
			{
				var SKU_ID 					= SKU.SKUMenu[i].SKU_ID;
				var SKU_NamaProduk 			= SKU.SKUMenu[i].SKU_NamaProduk;
				var SKUBosnet_ID 			= SKU.SKUMenu[i].SKUBosnet_ID;
				var SKU_Kode 				= SKU.SKUMenu[i].SKU_Kode;
				
				var SKU_Deskripsi			= SKU.SKUMenu[i].SKU_Deskripsi;
				//var KemasanID				= SKU.SKUMenu[i].KemasanID;
				var SKU_Kemasan				= SKU.SKUMenu[i].KemasanNama;
				var SKU_Satuan				= SKU.SKUMenu[i].SKU_Satuan;
				var SKU_Kondisi				= SKU.SKUMenu[i].SKU_Kondisi;
				var SKU_Origin				= SKU.SKUMenu[i].SKU_Origin;
				var SKU_HargaJual			= SKU.SKUMenu[i].SKU_HargaJual;
				var SKU_SalesMinQty			= SKU.SKUMenu[i].SKU_SalesMinQty;
				//var SKU_PPh					= SKU.SKUMenu[i].SKU_PPh;
				//var SKU_PPnBM_Persen		= SKU.SKUMenu[i].SKU_PPnBM_Persen;
				//var SKU_PPn_Persen			= SKU.SKUMenu[i].SKU_PPn_Persen;
				var SKU_IsActive			= SKU.SKUMenu[i].SKU_IsActive;
				var SKU_IsJual				= SKU.SKUMenu[i].SKU_IsJual;
				var SKU_WeightNetto			= SKU.SKUMenu[i].SKU_WeightNetto;
				var SKU_WeightNettoUnit		= SKU.SKUMenu[i].SKU_WeightNettoUnit;
				var SKU_WeightProduct		= SKU.SKUMenu[i].SKU_WeightProduct;
				var SKU_WeightProductUnit	= SKU.SKUMenu[i].SKU_WeightProductUnit;
				var SKU_WeightPackaging		= SKU.SKUMenu[i].SKU_WeightPackaging;
				var SKU_WeightPackagingUnit	= SKU.SKUMenu[i].SKU_WeightPackagingUnit;
				var SKU_WeightGift			= SKU.SKUMenu[i].SKU_WeightGift;
				var SKU_WeightGiftUnit		= SKU.SKUMenu[i].SKU_WeightGiftUnit;
				var SKU_Length				= SKU.SKUMenu[i].SKU_Length;
				var SKU_LengthUnit			= SKU.SKUMenu[i].SKU_LengthUnit;
				var SKU_Width				= SKU.SKUMenu[i].SKU_Width;
				var SKU_WidthUnit			= SKU.SKUMenu[i].SKU_WidthUnit;
				var SKU_Height				= SKU.SKUMenu[i].SKU_Height;
				var SKU_HeightUnit			= SKU.SKUMenu[i].SKU_HeightUnit;
				var SKU_Volume				= SKU.SKUMenu[i].SKU_Volume;
				var SKU_VolumeUnit			= SKU.SKUMenu[i].SKU_VolumeUnit;
								
				var str = '';
				
				var checkedIsActive = '';
				if( SKU_IsActive == 1 )	{	checkedIsActive = 'checked';	}
				
				var checkedIsJual = '';
				if( SKU_IsJual == 1 )	{	checkedIsJual = 'checked';	}
				
				var RandBosnetID = Math.random() * 1000000000000000000;
				
				str = str + '<tr>';
				str = str + '	<input type="hidden" class="xupdatehdSKU_ID" value="'+ SKU_ID +'" />';							
				str = str + '	<input type="hidden" class="xupdatetxtSKU_Kode" value="'+ SKU_Kode +'" />';							
				str = str + '	<td><input type="text" style="width: 500px;" class="xupdatetxtSKU_NamaProduk form-control" disabled="disabled" value="'+ SKU_NamaProduk +'" /></td>';
				str = str + '	<td><button type="button" id="xbtnviewSKUBosnet_ID" class="btn btn-primary xbtnviewSKUBosnet_ID" onclick="UpdateViewBosnet(\''+ RandBosnetID +'\',\''+ SKU_ID +'\',\''+ SKU_NamaProduk +'\')" ><i class="fa fa-pencil"></i> Isi Bosnet ID</button></td>';
				str = str + '	<td><input type="text" class="xupdatetxtSKU_Deskripsi form-control" value="'+ SKU_Deskripsi +'" /></td>';
				str = str + '	<td><select class="xupdatecbSKU_Kemasan form-control">'+ strkemasan +'</select></td>';
				str = str + '	<td><select class="xupdatecbSKU_Satuan form-control">'+ strsatuan +'</select></td>';
				//str = str + '	<td><input type="text" class="xupdatetxtSKU_Kemasan form-control" value="'+ SKU_Kemasan +'" disabled="disabled" /></td>';
				//str = str + '	<td><input type="text" class="xupdatetxtSKU_Satuan form-control" value="'+ SKU_Satuan +'" disabled="disabled" /></td>';
				str = str + '	<td><select class="xupdatecbSKU_Kondisi form-control">'+ strSKU_Kondisi +'</select></td>';
				str = str + '	<td><input type="text" class="xupdatetxtSKU_Origin form-control" value="'+ SKU_Origin +'" /></td>';
				str = str + '	<td><input type="number" class="xupdatetxtSKU_HargaJual form-control" value="'+ SKU_HargaJual +'" /></td>';
				str = str + '	<td><input type="number" class="xupdatetxtSKU_SalesMinQty form-control" value="'+ SKU_SalesMinQty +'" /></td>';
				//str = str + '	<td><select class="updatecbSKU_PPh form-control">'+ strSKU_PPh +'</select></td>';
				//str = str + '	<td><input type="number" class="updatetxtSKU_PPnBM_Persen form-control" value="'+ SKU_PPnBM_Persen +'" /></td>';
				//str = str + '	<td><input type="number" class="updatetxtSKU_PPn_Persen form-control" value="'+ SKU_PPn_Persen +'" /></td>';
				str = str + '	<td><input type="checkbox" class="checkbox_form xupdatechSKU_IsActive" '+ checkedIsActive +' /></td>';
				//str = str + '	<td><input type="checkbox" class="checkbox_form xupdatechSKU_IsJual" '+ checkedIsJual +' /></td>';
				str = str + '	<td><input type="number" class="xupdatetxtSKU_WeightNetto form-control" value="'+ SKU_WeightNetto +'" /></td>';
				str = str + '	<td><select class="xupdatecbSKU_WeightNettoUnit form-control">'+ strSatuanBerat +'</select></td>';
				str = str + '	<td><input type="number" class="xupdatetxtSKU_WeightProduct form-control" value="'+ SKU_WeightProduct +'" /></td>';
				str = str + '	<td><select class="xupdatecbSKU_WeightProductUnit form-control">'+ strSatuanBerat +'</select></td>';
				str = str + '	<td><input type="number" class="xupdatetxtSKU_WeightPackaging form-control" value="'+ SKU_WeightPackaging +'" /></td>';
				str = str + '	<td><select class="xupdatecbSKU_WeightPackagingUnit form-control">'+ strSatuanBerat +'</select></td>';
				str = str + '	<td><input type="number" class="xupdatetxtSKU_WeightGift form-control" value="'+ SKU_WeightGift +'" /></td>';
				str = str + '	<td><select class="xupdatecbSKU_WeightGiftUnit form-control">'+ strSatuanBerat +'</select></td>';
				str = str + '	<td><input type="number" class="xupdatetxtSKU_Length form-control" value="'+ SKU_Length +'" /></td>';
				str = str + '	<td><select class="xupdatecbSKU_LengthUnit form-control">'+ strSatuanPanjang +'</select></td>';
				str = str + '	<td><input type="number" class="xupdatetxtSKU_Width form-control" value="'+ SKU_Width +'" /></td>';
				str = str + '	<td><select class="xupdatecbSKU_WidthUnit form-control">'+ strSatuanLebar +'</select></td>';
				str = str + '	<td><input type="number" class="xupdatetxtSKU_Height form-control" value="'+ SKU_Height +'" /></td>';
				str = str + '	<td><select class="xupdatecbSKU_HeightUnit form-control">'+ strSatuanTinggi +'</select></td>';
				str = str + '	<td><input type="number" class="xupdatetxtSKU_Volume form-control" value="'+ SKU_Volume +'" /></td>';
				str = str + '	<td><select class="xupdatecbSKU_VolumeUnit form-control">'+ strSatuanVolume +'</select></td>';
				str = str + '</tr>';
				
				$("#updatetabledatasku > tbody").append( str );
				
				//$(".xupdatecbKemasanID").eq(i).val( KemasanID );
				$(".xupdatecbSKU_Kondisi").eq(i).val( SKU_Kondisi ).change();
				//$(".updatecbSKU_PPh").eq(i).val( SKU_PPh );
				$(".xupdatecbSKU_WeightNettoUnit").eq(i).val( SKU_WeightNettoUnit ).change();
				$(".xupdatecbSKU_WeightProductUnit").eq(i).val( SKU_WeightProductUnit ).change();
				$(".xupdatecbSKU_WeightPackagingUnit").eq(i).val( SKU_WeightPackagingUnit ).change();
				$(".xupdatecbSKU_WeightGiftUnit").eq(i).val( SKU_WeightGiftUnit ).change();
				
				$(".xupdatecbSKU_LengthUnit").eq(i).val( SKU_LengthUnit ).change();
				$(".xupdatecbSKU_WidthUnit").eq(i).val( SKU_WidthUnit ).change();
				$(".xupdatecbSKU_HeightUnit").eq(i).val( SKU_HeightUnit ).change();
				$(".xupdatecbSKU_VolumeUnit").eq(i).val( SKU_VolumeUnit ).change();
				
				$(".xupdatecbSKU_Kemasan").eq(i).val( SKU_Kemasan );
				$(".xupdatecbSKU_Satuan").eq(i).val( SKU_Satuan );
				
				var struu = '';
				struu = struu + '<tr>';
				struu = struu + '	<input type="hidden" class="xxupdatehdSKU_ID" value="'+ SKU_ID +'" />';							
				struu = struu + '	<input type="hidden" class="xxupdatetxtSKU_Kode" value="'+ SKU_Kode +'" />';							
				struu = struu + '	<td><input type="text" style="width: 500px;" class="xxupdatetxtSKU_NamaProduk form-control" disabled="disabled" value="'+ SKU_NamaProduk +'" /></td>';
				struu = struu + '	<td><button type="button" id="xxbtnviewSKUBosnet_ID" class="btn btn-primary xxbtnviewSKUBosnet_ID" onclick="UpdateLihatViewBosnet(\''+ RandBosnetID +'\',\''+ SKU_ID +'\',\''+ SKU_NamaProduk +'\')" ><i class="fa fa-eye"></i> Lihat Bosnet ID</button></td>';
				
				struu = struu + '	<td><input type="checkbox" class="checkbox_form xxupdatechSKU_IsActive" '+ checkedIsActive +' /></td>';
				//struu = struu + '	<td><input type="checkbox" class="checkbox_form xxupdatechSKU_IsJual" '+ checkedIsJual +' /></td>';
				struu = struu + '	<td><input type="text" class="xxupdatetxtSKU_Deskripsi form-control" value="'+ SKU_Deskripsi +'" disabled="disabled" /></td>';
				struu = struu + '	<td><select class="xxupdatecbSKU_Kemasan form-control" disabled="disabled">'+ strkemasan +'</select></td>';
				struu = struu + '	<td><select class="xxupdatecbSKU_Satuan form-control" disabled="disabled">'+ strsatuan +'</select></td>';
				struu = struu + '	<td><select class="xxupdatecbSKU_Kondisi form-control" disabled="disabled">'+ strSKU_Kondisi +'</select></td>';
				struu = struu + '	<td><input type="text" class="xxupdatetxtSKU_Origin form-control" value="'+ SKU_Origin +'" disabled="disabled" /></td>';
				struu = struu + '	<td><input type="number" class="xxupdatetxtSKU_HargaJual form-control" value="'+ SKU_HargaJual +'" disabled="disabled" /></td>';
				struu = struu + '	<td><input type="number" class="xxupdatetxtSKU_SalesMinQty form-control" value="'+ SKU_SalesMinQty +'" disabled="disabled" /></td>';
				struu = struu + '	<td><input type="number" class="xxupdatetxtSKU_WeightNetto form-control" value="'+ SKU_WeightNetto +'" disabled="disabled" /></td>';
				struu = struu + '	<td><select class="xxupdatecbSKU_WeightNettoUnit form-control" disabled="disabled">'+ strSatuanBerat +'</select></td>';
				struu = struu + '	<td><input type="number" class="xxupdatetxtSKU_WeightProduct form-control" value="'+ SKU_WeightProduct +'" disabled="disabled" /></td>';
				struu = struu + '	<td><select class="xxupdatecbSKU_WeightProductUnit form-control" disabled="disabled">'+ strSatuanBerat +'</select></td>';
				struu = struu + '	<td><input type="number" class="xxupdatetxtSKU_WeightPackaging form-control" value="'+ SKU_WeightPackaging +'" disabled="disabled" /></td>';
				struu = struu + '	<td><select class="xxupdatecbSKU_WeightPackagingUnit form-control" disabled="disabled">'+ strSatuanBerat +'</select></td>';
				struu = struu + '	<td><input type="number" class="xxupdatetxtSKU_WeightGift form-control" value="'+ SKU_WeightGift +'" disabled="disabled" /></td>';
				struu = struu + '	<td><select class="xxupdatecbSKU_WeightGiftUnit form-control" disabled="disabled">'+ strSatuanBerat +'</select></td>';
				struu = struu + '	<td><input type="number" class="xxupdatetxtSKU_Length form-control" value="'+ SKU_Length +'" disabled="disabled" /></td>';
				struu = struu + '	<td><select class="xxupdatecbSKU_LengthUnit form-control" disabled="disabled">'+ strSatuanPanjang +'</select></td>';
				struu = struu + '	<td><input type="number" class="xxupdatetxtSKU_Width form-control" value="'+ SKU_Width +'" disabled="disabled" /></td>';
				struu = struu + '	<td><select class="xxupdatecbSKU_WidthUnit form-control" disabled="disabled">'+ strSatuanLebar +'</select></td>';
				struu = struu + '	<td><input type="number" class="xxupdatetxtSKU_Height form-control" value="'+ SKU_Height +'" disabled="disabled" /></td>';
				struu = struu + '	<td><select class="xxupdatecbSKU_HeightUnit form-control" disabled="disabled">'+ strSatuanTinggi +'</select></td>';
				struu = struu + '	<td><input type="number" class="xxupdatetxtSKU_Volume form-control" value="'+ SKU_Volume +'" disabled="disabled" /></td>';
				struu = struu + '	<td><select class="xxupdatecbSKU_VolumeUnit form-control" disabled="disabled">'+ strSatuanVolume +'</select></td>';
				struu = struu + '</tr>';
				
				$("#updateubahtabledatasku > tbody").append( struu );

				$(".xupdatecbSKU_WeightNettoUnit").eq(i).css("width", "100%");
				$(".xupdatecbSKU_WeightNettoUnit").eq(i).select2({
					"theme": "bootstrap", "val": ""
				});
				
				$(".xupdatecbSKU_WeightProductUnit").eq(i).css("width", "100%");
				$(".xupdatecbSKU_WeightProductUnit").eq(i).select2({
					"theme": "bootstrap", "val": ""
				});
				
				$(".xupdatecbSKU_WeightPackagingUnit").eq(i).css("width", "100%");
				$(".xupdatecbSKU_WeightPackagingUnit").eq(i).select2({
					"theme": "bootstrap", "val": ""
				});
				
				$(".xupdatecbSKU_WeightGiftUnit").eq(i).css("width", "100%");
				$(".xupdatecbSKU_WeightGiftUnit").eq(i).select2({
					"theme": "bootstrap", "val": ""
				});
				
				$(".xupdatecbSKU_HeightUnit").eq(i).css("width", "100%");
				$(".xupdatecbSKU_HeightUnit").eq(i).select2({
					"theme": "bootstrap", "val": ""
				});
				
				$(".xupdatecbSKU_LengthUnit").eq(i).css("width", "100%");
				$(".xupdatecbSKU_LengthUnit").eq(i).select2({
					"theme": "bootstrap", "val": ""
				});
				
				$(".xupdatecbSKU_VolumeUnit").eq(i).css("width", "100%");
				$(".xupdatecbSKU_VolumeUnit").eq(i).select2({
					"theme": "bootstrap", "val": ""
				});
				
				$(".xupdatecbSKU_WidthUnit").eq(i).css("width", "100%");
				$(".xupdatecbSKU_WidthUnit").eq(i).select2({
					"theme": "bootstrap", "val": ""
				});

				$(".xupdatecbSKU_Kemasan").css("width", "100%");
				$(".xupdatecbSKU_Kemasan").select2({
					"theme": "bootstrap", "val": ""
				});
				
				$(".xupdatecbSKU_Satuan").css("width", "100%");
				$(".xupdatecbSKU_Satuan").select2({
					"theme": "bootstrap", "val": ""
				});
				
				$(".xxupdatecbSKU_Kemasan").eq(i).val( SKU_Kemasan );
				$(".xxupdatecbSKU_Satuan").eq(i).val( SKU_Satuan );
				
				//$(".xupdatecbKemasanID").eq(i).val( KemasanID );
				$(".xxupdatecbSKU_Kondisi").eq(i).val( SKU_Kondisi ).change();
				//$(".updatecbSKU_PPh").eq(i).val( SKU_PPh );
				$(".xxupdatecbSKU_WeightNettoUnit").eq(i).val( SKU_WeightNettoUnit ).change();
				$(".xxupdatecbSKU_WeightProductUnit").eq(i).val( SKU_WeightProductUnit ).change();
				$(".xxupdatecbSKU_WeightPackagingUnit").eq(i).val( SKU_WeightPackagingUnit ).change();
				$(".xxupdatecbSKU_WeightGiftUnit").eq(i).val( SKU_WeightGiftUnit ).change();
				
				$(".xxupdatecbSKU_LengthUnit").eq(i).val( SKU_LengthUnit ).change();
				$(".xxupdatecbSKU_WidthUnit").eq(i).val( SKU_WidthUnit ).change();
				$(".xxupdatecbSKU_HeightUnit").eq(i).val( SKU_HeightUnit ).change();
				$(".xxupdatecbSKU_VolumeUnit").eq(i).val( SKU_VolumeUnit ).change();
			}
			
			$('#updatetabledatasku').DataTable(
			{
				//scrollX: "100%",
				retrieve: true,
				initComplete: function (settings, json) 
				{  
					$("#updatetabledatasku").wrap("<div style='overflow:auto; width:100%; position:relative;'></div>");            
				},
				"dom": '<"left">rtp'
			});
			
			$('#updateubahtabledatasku').DataTable(
			{
				//scrollX: "100%",
				retrieve: true,
				initComplete: function (settings, json) 
				{  
					$("#updateubahtabledatasku").wrap("<div style='overflow:auto; width:100%; position:relative;'></div>");            
				},
				"dom": '<"left">rtp'
			});
		}
		
		if( SKU.Kategori1Menu != 0 )
		{
			for( i=0 ; i< SKU.Kategori1Menu.length ; i++ )
			{
				var Kategori1ID = SKU.Kategori1Menu[i].Kategori1ID;
				var Kategori1_Kode = SKU.Kategori1Menu[i].Kategori1_Kode;
				var Kategori1_Nama = SKU.Kategori1Menu[i].Kategori1_Nama;
				
				$("#updatecbKategori1").append( '<option value="'+ Kategori1ID +'">'+ Kategori1_Nama +'</option>' );	
			}
		}
		
		if( SKU.Kategori2Menu != 0 )
		{
			for( i=0 ; i< SKU.Kategori2Menu.length ; i++ )
			{
				var Kategori2ID = SKU.Kategori2Menu[i].Kategori1ID;
				var Kategori2_Kode = SKU.Kategori2Menu[i].Kategori1_Kode;
				var Kategori2_Nama = SKU.Kategori2Menu[i].Kategori1_Nama;
			
				$("#updatecbKategori2").append( '<option value="'+ Kategori2ID +'">'+ Kategori2_Nama +'</option>' );	
			}
			
		}
		
		if( SKU.Kategori3Menu != 0 )
		{
			for( i=0 ; i< SKU.Kategori3Menu.length ; i++ )
			{
				var Kategori3ID = SKU.Kategori3Menu[i].Kategori1ID;
				var Kategori3_Kode = SKU.Kategori3Menu[i].Kategori1_Kode;
				var Kategori3_Nama = SKU.Kategori3Menu[i].Kategori1_Nama;

				$("#updatecbKategori3").append( '<option value="'+ Kategori3ID +'">'+ Kategori3_Nama +'</option>' );	
			}
		}
		
		if( SKU.Kategori4Menu != 0 )
		{
			for( i=0 ; i< SKU.Kategori4Menu.length ; i++ )
			{
				var Kategori4ID = SKU.Kategori4Menu[i].Kategori1ID;
				var Kategori4_Kode = SKU.Kategori4Menu[i].Kategori1_Kode;
				var Kategori4_Nama = SKU.Kategori4Menu[i].Kategori1_Nama;

				$("#updatecbKategori4").append( '<option value="'+ Kategori4ID +'">'+ Kategori4_Nama +'</option>' );	
			}
		}
		
		if( SKU.KategoriMenu != 0 )
		{
			var SKUInduk_Keterangan = SKU.KategoriMenu[0].SKUInduk_Keterangan;
			
			var Kategori1_ID = SKU.KategoriMenu[0].Kategori1_ID;
			var Kategori2_ID = SKU.KategoriMenu[0].Kategori2_ID;
			var Kategori3_ID = SKU.KategoriMenu[0].Kategori3_ID;
			var Kategori4_ID = SKU.KategoriMenu[0].Kategori4_ID;
			
			var Kategori9_ID = SKU.KategoriMenu[0].Kategori9_ID;
			var Kategori10_ID = SKU.KategoriMenu[0].Kategori10_ID;
			
			$("#updatecbKategori9").val( Kategori9_ID ).change();
			$("#updatecbKategori10").val( Kategori10_ID ).change();
			
			
			if( Kategori1_ID != null )	
			{ 
				$("#updatekat2").removeAttr('style');	
			}
			
			$("#updatecbKategori1").val( Kategori1_ID );
			
			if( Kategori2_ID != null )	
			{ 
				$("#updatekat2").removeAttr('style');	
				$("#updatekat3").removeAttr('style');	
			}
			
			$("#updatecbKategori2").val( Kategori2_ID );
			
			if( Kategori3_ID != null )	
			{ 
				$("#updatekat3").removeAttr('style');	
				$("#updatekat4").removeAttr('style');	
			}
			
			$("#updatecbKategori3").val( Kategori3_ID );
			
			if( Kategori4_ID != null )	
			{ 
				$("#updatekat4").removeAttr('style');	
			}
			
			$("#updatecbKategori4").val( Kategori4_ID );
			
			for( i=1 ; i<=10 ; i++ )
			{
				$("#updatecbKategori"+ i).css("width", "100%");
				$("#updatecbKategori"+ i).select2({
					"theme": "bootstrap", "val": ""
				});
			}
			
			$("#updatetxaskuinduk_ket").val( SKUInduk_Keterangan );
			
			$("#updatetxaskuinduk_ket").trigger('keydown');
			$("#updatetxaskuinduk_ket").trigger('keyup');
		}
		
		arrUpdateSKUBosnet_ID = [];
		if( SKU.SKUBosnetMenu != 0 )
		{
			for( i=0 ; i< SKU.SKUBosnetMenu.length ; i++ )
			{
				var SKUBosnet_ID 	= SKU.SKUBosnetMenu[i].SKUBosnet_ID;
				var SKU_ID 			= SKU.SKUBosnetMenu[i].SKU_ID;
				var SKUInduk_ID 	= SKU.SKUBosnetMenu[i].SKUInduk_ID;
				var Bosnet_ID 		= SKU.SKUBosnetMenu[i].Bosnet_ID;

				arrUpdateSKUBosnet_ID.push( { SKUBosnet_ID : SKUBosnet_ID, SKU_ID : SKU_ID, SKUInduk_ID : SKUInduk_ID, Bosnet_ID : Bosnet_ID } );
			}
		}
		
		strdatavarian = '';
		strdatavariandetail = '';
		
		$("#updatedivvarianwajib").html('');
		$("#updatedivvarian").html('');
		
		
		$('#updatetablegeneratedstringsku').DataTable(
		{
			//scrollX: "100%",
			retrieve: true,
			initComplete: function (settings, json) 
			{  
				$("#updatetablegeneratedstringsku").wrap("<div style='overflow:auto; width:100%; position:relative;'></div>");            
			},
			"dom": '<"left"f>rtp'
		});
		
		$(".updatecbKemasanID").css("width", "100%");
		$(".updatecbKemasanID").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updatecbSKU_WeightNettoUnit").css("width", "100%");
		$(".updatecbSKU_WeightNettoUnit").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updateupdatecbSKU_WeightProductUnit").css("width", "100%");
		$(".updateupdatecbSKU_WeightProductUnit").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updatecbSKU_WeightPackagingUnit").css("width", "100%");
		$(".updatecbSKU_WeightPackagingUnit").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updatecbSKU_WeightGiftUnit").css("width", "100%");
		$(".updatecbSKU_WeightGiftUnit").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updatecbSKU_HeightUnit").css("width", "100%");
		$(".updatecbSKU_HeightUnit").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updatecbSKU_LengthUnit").css("width", "100%");
		$(".updatecbSKU_LengthUnit").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updatecbSKU_VolumeUnit").css("width", "100%");
		$(".updatecbSKU_VolumeUnit").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updatecbSKU_WidthUnit").css("width", "100%");
		$(".updatecbSKU_WidthUnit").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updatecbSKU_Kemasan").css("width", "100%");
		$(".updatecbSKU_Kemasan").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updatecbSKU_Satuan").css("width", "100%");
		$(".updatecbSKU_Satuan").select2({
			"theme": "bootstrap", "val": ""
		});
	}
	
	function UpdateLihatViewBosnet( RandBosnetID, P_SKU_ID, P_SKU_NamaProduk )
	{
		$("#updatelihattxtRandBosnetID").val( RandBosnetID );
		$("#updatelihattxtBSKU_ID").val( P_SKU_ID );
		$("#updatelihattxtBosnet_SKU_NamaProduk").val( P_SKU_NamaProduk );
		
		$("#updateubahtbskubosnet tbody").html('');
			
		for( i=0 ; i<arrUpdateSKUBosnet_ID.length ; i++ )
		{
			var Bosnet_ID = arrUpdateSKUBosnet_ID[i].Bosnet_ID;
			var SKU_ID = arrUpdateSKUBosnet_ID[i].SKU_ID;
			
			if( SKU_ID == P_SKU_ID )
			{				
				var str = ''; 
				str = str + '<tr>';
				str = str + '	<td width="100%"><input type="text" class="updatelihattxtSKUBosnet_ID form-control" value="'+ Bosnet_ID +'" disabled="disabled"/></td>';
				//str = str + '	<td width="20%">';
				//str = str + '<button type="button" class="updatelihatbtndeletebosnetid btn btn-danger" onclick="UpdateLihatDeleteBosnet( this.parentNode.parentNode.rowIndex )"><i class="fa fa-trash-o"></i></button></td>';
				str = str + '</tr>';
				
				$("#updatelihattbskubosnet tbody").append( str );
			}
		}
			
		$("#updatelihatpreviewaddbosnet").modal('show');
	}
	
	function UpdateViewBosnet( RandBosnetID, P_SKU_ID, P_SKU_NamaProduk )
	{
		$("#updatetxtRandBosnetID").val( RandBosnetID );
		$("#updatetxtBSKU_ID").val( P_SKU_ID );
		$("#updatetxtBosnet_SKU_NamaProduk").val( P_SKU_NamaProduk );
		
		$("#updatetbskubosnet tbody").html('');
			
		for( i=0 ; i<arrUpdateSKUBosnet_ID.length ; i++ )
		{
			var Bosnet_ID = arrUpdateSKUBosnet_ID[i].Bosnet_ID;
			var SKU_ID = arrUpdateSKUBosnet_ID[i].SKU_ID;
			if( SKU_ID == P_SKU_ID )
			{				
				var str = ''; 
				str = str + '<tr>';
				str = str + '	<td width="80%"><input type="text" class="updatetxtSKUBosnet_ID form-control" value="'+ Bosnet_ID +'" /></td>';
				str = str + '	<td width="20%"><button type="button" class="updatebtndeletebosnetid btn btn-danger" onclick="UpdateDeleteBosnet( this.parentNode.parentNode.rowIndex )"><i class="fa fa-trash-o"></i></button></td>';
				str = str + '</tr>';
				
				$("#updatetbskubosnet tbody").append( str );
			}
			
		}
		$("#updatepreviewaddbosnet").modal('show');
	}
	
	$("#updatebtntambahbosnet").click
	(
		function()
		{		
			var RandBosnetID = $("#updatetxtRandBosnetID").val();
			
			var str = ''; 
			str = str + '<tr>';
			str = str + '	<td width="80%"><input type="text" class="updatetxtSKUBosnet_ID form-control" /></td>';
			str = str + '	<td width="20%"><button type="button" class="updatebtndeletebosnetid btn btn-danger" onclick="UpdateDeleteBosnet( this.parentNode.parentNode.rowIndex )"><i class="fa fa-trash-o"></i></button></td>';
			str = str + '</tr>';
			
			$("#updatetbskubosnet tbody").append( str );
		}
	);
	
	function UpdateDeleteBosnet( Idx )
	{
		$("#updatetbskubosnet tbody tr:eq("+ Idx +")").remove();
	}
	
	$("#updatebtnsaveskubosnetid").click
	(
		function()
		{
			var Rand = $("#updatetxtRandBosnetID").val();
			var P_SKU_ID = $("#updatetxtBSKU_ID").val();
			var P_SKU_NamaProduk = $("#updatetxtBosnet_SKU_NamaProduk").val();
			
			// bersihin dulu bosnet id nya
			for( i= arrUpdateSKUBosnet_ID.length - 1 ; i>= 0 ; i-- )
			{
				var Bosnet_ID 	= arrUpdateSKUBosnet_ID[i].Bosnet_ID;
				var SKU_ID 		= arrUpdateSKUBosnet_ID[i].SKU_ID;
				
				if( P_SKU_ID == SKU_ID )
				{
					arrUpdateSKUBosnet_ID.splice( i, 1);
				}
			}
			
			var ltbbosnet = $(".updatetxtSKUBosnet_ID").length;
			for( i=0 ; i<ltbbosnet ; i++ )
			{
				var Bosnet_ID = $(".updatetxtSKUBosnet_ID").eq(i).val();
				
				arrUpdateSKUBosnet_ID.push( { RandID : Rand, SKU_ID : P_SKU_ID, SKU_NamaProduk : P_SKU_NamaProduk, Bosnet_ID : Bosnet_ID } );
			}
			
			alert( JSON.stringify( arrUpdateSKUBosnet_ID ) );
			
			$("#updatepreviewaddbosnet").modal('hide');
		}
	);
	
	function UpdateCheckValuesWajib( Idx, RandVal, ID, Nama )
	{
		$("#updatetxtaddvariandetail_rand").val( RandVal );
		
		$("#updatetxtaddvariandetail_varianid").val( ID );
		
		$("#updatetxtaddvariandetail_variannama").val( Nama );
		
		$("#updatetxtaddvariandetail_variandetailnama").val('');
		
		if( Nama == 'BERAT NETTO' )	{	$("#updateisvarianberat").removeAttr('style');			}
		else						{	$("#updateisvarianberat").attr('style','display: none;');	}

		var vals = $("#updatecbvarianwajibdetail__"+ RandVal +" option:selected").val();

		if( vals == 'ADD' )
		{
			$("#updatepreviewaddvariandetail").modal('show');
		}
	}
	
	function UpdateCheckValues( Idx, RandVal, ID, Nama )
	{
		$("#updatetxtaddvariandetail_rand").val( RandVal );
		
		$("#updatetxtaddvariandetail_varianid").val( ID );
		
		$("#updatetxtaddvariandetail_variannama").val( Nama );
		
		$("#updatetxtaddvariandetail_variandetailnama").val('');
		
		if( Nama == 'BERAT NETTO' )	{	$("#updateisvarianberat").removeAttr('style');			}
		else						{	$("#updateisvarianberat").attr('style','display: none;');	}
		
		
		var vals = $("#updatecbvariandetail__"+ RandVal +" option:selected").val();
		
		if( vals == 'ADD' )
		{
			$("#updatepreviewaddvariandetail").modal('show');
		}
	}
	
	$("#updatebtnsavevariandetail").click
	(
		function()
		{
			var Rand 				= $("#updatetxtaddvariandetail_rand").val();
			
			var Varian_ID 			= $("#updatetxtaddvariandetail_varianid").val();
			var VarianDetail_Nama 	= $("#updatetxtaddvariandetail_variandetailnama").val();
			var VarianDetail_Unit 	= $("#updatecbaddvariandetail_varianunit option:selected").val();
			
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
							$("#updatecbvarianwajibdetail__"+ Rand).append( '<option value="'+ Varian_ID +' | '+ VarianDetail_ID +' | '+ NamaVarianDetail +'">'+ NamaVarianDetail +'</option>' );
							
							$("#updatecbvarianwajibdetail__"+ Rand).val( Varian_ID +' | '+ VarianDetail_ID +' | '+ NamaVarianDetail );
						}
						else if( Mode == 0 )	// Non Berat
						{
							$("#updatecbvariandetail__"+ Rand).append( '<option value="'+ Varian_ID +' | '+ VarianDetail_ID +' | '+ NamaVarianDetail +'">'+ NamaVarianDetail +'</option>' );
							
							$("#updatecbvariandetail__"+ Rand).val( Varian_ID +' | '+ VarianDetail_ID +' | '+ NamaVarianDetail );
						}
						
						$("#updatepreviewaddvariandetail").modal('hide');
					}	
				}
			});	
		}
	);
	
	function UpdateTambahVarianWajibDetail( countheader )
	{
		var Idx = parseInt( countheader ) - 1;
		var Varian_ID = $(".updatehdvarianwajib").eq(Idx).val();
		var VNama = $(".updatehdvariannamawajib").eq(Idx).val();
		
		//var Varian_ID = $("#updatehdvarianwajib").val();
		
		var rand2 = Math.random() * 10000000000000000;
		
		if( Varian_ID != '' )
		{
			var strvarianwajibdetail = '';
			strvarianwajibdetail = strvarianwajibdetail + '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 updatedivrowvarianwajib_'+ rand2 +'" id="updatedivrowvarianwajib'+ countheader +'">';
			strvarianwajibdetail = strvarianwajibdetail + '	<div class="input-group">';
			strvarianwajibdetail = strvarianwajibdetail + '		<select class="form-control updatecbvarianwajibdetail updatecbvarianwajibdetail__'+ rand2 +'" id="updatecbvarianwajibdetail__'+ rand2 +'" onchange="UpdateCheckValuesWajib('+ countheader +','+ rand2 +',\''+ Varian_ID +'\',\''+ VNama +'\')" this.parentNode.parentNode.rowIndex )">';
				
			strvarianwajibdetail = strvarianwajibdetail + '			<option value="">Pilih jenis</option>';
			strvarianwajibdetail = strvarianwajibdetail + '			<option value="ADD">**Buat Jenis Baru**</option>';
			for( j=0; j<arrVarianDetail.length ; j++ )
			{
				var DVarian_ID 			= arrVarianDetail[j].Varian_ID;
				var DVarianDetail_ID 	= arrVarianDetail[j].VarianDetail_ID;
				var DVarianDetail_Nama 	= arrVarianDetail[j].VarianDetail_Nama;
				var NamaVarianDetail 	= arrVarianDetail[j].NamaVarianDetail;
			
				if( DVarian_ID == Varian_ID )
				{
					strvarianwajibdetail = strvarianwajibdetail + '	<option value="'+ DVarian_ID +' | '+ DVarianDetail_ID +'">'+ NamaVarianDetail +'</option>';
				}
			}
			
			strvarianwajibdetail = strvarianwajibdetail + '		</select>';
			strvarianwajibdetail = strvarianwajibdetail + '		<div class="input-group-btn">';
			strvarianwajibdetail = strvarianwajibdetail + '			<button type="button" class="btn btn-danger" onclick="UpdateHapusVarianWajibDetail('+ rand2 +')"><i class="fa fa-trash"></i></button>';
			strvarianwajibdetail = strvarianwajibdetail + '		</div>';
			strvarianwajibdetail = strvarianwajibdetail + '	</div>';
			strvarianwajibdetail = strvarianwajibdetail + '</div>';
			
			$("#updatevarianwajibdetail"+ countheader).append( strvarianwajibdetail ); 
			
			$(".updatecbvarianwajibdetail__"+ rand2).css("width", "100%");
			$(".updatecbvarianwajibdetail__"+ rand2).select2({
				"theme": "bootstrap", "val": ""
			});	
		}
	}
	
	function UpdateHapusVarianWajibDetail( rand2 )
	{
		$(".updatedivrowvarianwajib_"+ rand2).eq(0).remove();
	}
	
	function UpdateHapusVarianWajib( rand )
	{
		$("#updatevarianwajibheader"+ rand).remove();
	}
	
	function UpdateChangeVarianWajib( countheader )
	{
		$("#updatevarianwajibdetail"+ countheader ).html('');
	}
	
	$("#updatebtnaddvarian").click
	(
		function()
		{
			var rand = Math.random() * 10000000000000000000;
			
			var strvarian = '';
			strvarian = strvarian + '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 updatevarianheader" style="padding: 10px; margin-bottom: 10px; background-color: #dddddd;" id="updatevarianheader'+ rand +'">';
			strvarian = strvarian + '	<div class="row">';
			strvarian = strvarian + '		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">';
			strvarian = strvarian + '			<div class="input-group">';
			strvarian = strvarian + '				<select class="form-control updatecbvarian updatecbvarian_'+ rand +'" id="updatecbvarian'+ rand +'" onchange="UpdateChangeVarian('+ rand +')">';
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
			strvarian = strvarian + '					<button type="button" class="btn btn-danger" onclick="UpdateHapusVarian('+ rand +')"><i class="fa fa-trash"></i></button>';
			strvarian = strvarian + '				</div>';
			strvarian = strvarian + '			</div>';
			strvarian = strvarian + '		</div>';
			strvarian = strvarian + '	</div>';
		
			strvarian = strvarian + '	<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-horizontal form-label-left" id="updatedivvariandetail'+ rand +'">';
			strvarian = strvarian + '		<label class="control-label col-xs-4 col-sm-3 col-md-3 col-lg-3 col-xl-3">Jenis</label>';
			strvarian = strvarian + '		<div class="col-xs-8 col-sm-9 col-md-9 col-lg-9 col-xl-9" id="updatevariandetail'+ rand +'">';
			strvarian = strvarian + '		</div>';
			strvarian = strvarian + '	</div>';
			strvarian = strvarian + '	<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">';
			strvarian = strvarian + ' 		<button type="button" class="form-control btn btn-primary" onclick="UpdateTambahVarianDetail('+ rand +')">';
			strvarian = strvarian + '			<i class="fa fa-plus"></i> Tambah Jenis';
			strvarian = strvarian + '		</button>';
			strvarian = strvarian + '	</div>';
			strvarian = strvarian + '</div>';
			
			$("#updatedivvarian").append( strvarian );
			
			
			$("#updatecbvarian"+ rand).css("width", "100%");
			$("#updatecbvarian"+ rand).select2({
				"theme": "bootstrap", "val": ""
			});
		}
	);
	
	function UpdateTambahVarianDetail( rand )
	{
		var Varian_ID = $("#updatecbvarian"+ rand +" option:selected").val();
		var VNama = $("#updatecbvarian"+ rand +" option:selected").html();
		
		var rand2 = Math.random() * 1000000000000000000000;

		if( Varian_ID != '' )
		{
			var strvariandetail = '';
			strvariandetail = strvariandetail + '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 updatedivrowvarian_'+ rand +'">';
			strvariandetail = strvariandetail + '	<div class="input-group">';
			strvariandetail = strvariandetail + '		<select class="form-control updatecbvariandetail updatecbvariandetail__'+ rand +'" id="updatecbvariandetail__'+ rand +'" onchange="UpdateCheckValues('+ rand +','+ rand +',\''+ Varian_ID +'\',\''+ VNama +'\')">';
				
			strvariandetail = strvariandetail + '			<option value="">Pilih jenis</option>';
			strvariandetail = strvariandetail + '			<option value="ADD">**Buat Jenis Baru**</option>';
			for( j=0; j<arrVarianDetail.length ; j++ )
			{
				var DVarian_ID 			= arrVarianDetail[j].Varian_ID;
				var DVarianDetail_ID 	= arrVarianDetail[j].VarianDetail_ID;
				var DVarianDetail_Nama 	= arrVarianDetail[j].VarianDetail_Nama;
			
				if( DVarian_ID == Varian_ID )
				{
					strvariandetail = strvariandetail + '	<option value="'+ DVarian_ID +' | '+ DVarianDetail_ID +'">'+ DVarianDetail_Nama +'</option>';
				}
			}
			
			strvariandetail = strvariandetail + '		</select>';
			strvariandetail = strvariandetail + '		<div class="input-group-btn">';
			strvariandetail = strvariandetail + '			<button type="button" class="btn btn-danger" onclick="UpdateHapusVarianDetail('+ rand +')"><i class="fa fa-trash"></i></button>';
			strvariandetail = strvariandetail + '		</div>';
			strvariandetail = strvariandetail + '	</div>';
			strvariandetail = strvariandetail + '</div>';
			
			$("#updatevariandetail"+ rand).append( strvariandetail ); 
			
			$(".updatecbvariandetail__"+ rand).css("width", "100%");
			$(".updatecbvariandetail__"+ rand).select2({
				"theme": "bootstrap", "val": ""
			});	
		}
	}
	
	function UpdateHapusVarianDetail( rand2 )
	{
		$(".updatedivrowvarian_"+ rand2).eq(0).remove();
	}
	
	function UpdateHapusVarian( rand )
	{
		$("#updatevarianheader"+ rand).remove();
	}
	
	function UpdateChangeVarian( countheader )
	{
		$("#updatevariandetail"+ countheader ).html('');
	}
	
	$("#updatebtnproses1").click
	(
		function()
		{
			$("#updatepreviewkonfirmasiproses1").modal('show');
		}
	);
	
	$("#updatebtnyesproses1").click
	(
		function()
		{
			var SKUInduk_ID = $("#updatecbskuinduk option:selected").val();
					
			$.ajax(
			{
				type: 'POST',
				url: "<?= base_url('SKUHadiah/GetUpdateVarianMenu') ?>",
				data: 
				{
					SKUInduk_ID : SKUInduk_ID
				},
				success: function( response )
				{
					UpdateChVarianMenu( response );
				}
			});
			
			//$("#updatebtnproses1").prop('disabled', false );
				
			//UpdateProses( 1 );
		}
	);
	
	function UpdateChVarianMenu( JSONSKU )
	{
		var SKU = JSON.parse( JSONSKU );
		
		strdatavarian = '';
		strdatavariandetail = '';
		

		$("#updatedivvarianwajib").html('');
		$("#updatedivvarian").html('');
		
		
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
					strvarianwajib = strvarianwajib + '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 updatevarianheaderwajib" style="padding: 10px; margin-bottom: 10px; background-color: #dddddd;" id="varianheaderwajib'+ countheader +'">';
					strvarianwajib = strvarianwajib + '	<div class="row">';
					strvarianwajib = strvarianwajib + '		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">';
					strvarianwajib = strvarianwajib + '			<input type="hidden" id="updatehdvarianwajib" class="form-control updatehdvarianwajib" value="'+ Varian_ID +'" />';
					strvarianwajib = strvarianwajib + '			<input type="hidden" id="updatehdvariannamawajib_'+ countheader +'" class="form-control updatehdvariannamawajib" value="'+ Varian_Nama +'" />';
					strvarianwajib = strvarianwajib + '			<input type="text" id="updatetxtvarianwajib" class="form-control updatetxtvarianwajib" value="'+ Varian_Nama +'" disabled="disabled" />';
					strvarianwajib = strvarianwajib + '		</div>';
					strvarianwajib = strvarianwajib + '	</div>';
				
					strvarianwajib = strvarianwajib + '	<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-horizontal form-label-left" id="updatedivvarianwajibdetail'+ countheader +'">';
					strvarianwajib = strvarianwajib + '		<label class="control-label col-xs-4 col-sm-3 col-md-3 col-lg-3 col-xl-3">Jenis</label>';
					strvarianwajib = strvarianwajib + '		<div class="col-xs-8 col-sm-9 col-md-9 col-lg-9 col-xl-9" id="updatevarianwajibdetail'+ countheader +'">';
					strvarianwajib = strvarianwajib + '		</div>';
					strvarianwajib = strvarianwajib + '	</div>';
					strvarianwajib = strvarianwajib + '	<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">';
					strvarianwajib = strvarianwajib + ' 	<button type="button" class="form-control btn btn-primary" onclick="UpdateTambahVarianWajibDetail('+ countheader +')">';
					strvarianwajib = strvarianwajib + '			<i class="fa fa-plus"></i> Tambah Jenis';
					strvarianwajib = strvarianwajib + '		</button>';
					strvarianwajib = strvarianwajib + '	</div>';
					strvarianwajib = strvarianwajib + '</div>';
					
					$("#updatedivvarianwajib").append( strvarianwajib );
				
				}
				
				var strvarianwajibdetail = '';
				if( Var_ID == Varian_ID )
				{
					
					var VNama = Varian_Nama;
		
					strvarianwajibdetail = strvarianwajibdetail + '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 updatedivrowvarianwajib_'+ countheader +'" id="updatedivrowvarianwajib'+ countheader +'">';
					strvarianwajibdetail = strvarianwajibdetail + '	<div class="input-group">';
					strvarianwajibdetail = strvarianwajibdetail + '		<select class="form-control updatecbvarianwajibdetail updatecbvarianwajibdetail__'+ countheader +'" id="updatecbvarianwajibdetail__'+ countheader +'" onchange="UpdateCheckValuesWajib('+ countheader +','+ countheader +',\''+ Varian_ID +'\',\''+ VNama +'\')">';
						
					strvarianwajibdetail = strvarianwajibdetail + '			<option value="">Pilih jenis</option>';
					strvarianwajibdetail = strvarianwajibdetail + '			<option value="ADD">**Buat Jenis Baru**</option>';
					for( j=0; j<arrVarianDetail.length ; j++ )
					{
						var DVarian_ID 			= arrVarianDetail[j].Varian_ID;
						var DVarianDetail_ID 	= arrVarianDetail[j].VarianDetail_ID;
						var DVarianDetail_Nama 	= arrVarianDetail[j].VarianDetail_Nama;
						var NamaVDetail 		= arrVarianDetail[j].NamaVarianDetail;
						
						if( VarianDetail_ID == DVarianDetail_ID )	{	var isselected = 'selected';	}
						else 										{	var isselected = '';			}
						
						if( DVarian_ID == Varian_ID )
						{
							
							strvarianwajibdetail = strvarianwajibdetail + '<option value="'+ DVarian_ID +' | '+ DVarianDetail_ID +'" '+ isselected +'>'+ NamaVDetail +'</option>';
						}
					}
					
					strvarianwajibdetail = strvarianwajibdetail + '		</select>';
					strvarianwajibdetail = strvarianwajibdetail + '		<div class="input-group-btn">';
					strvarianwajibdetail = strvarianwajibdetail + '			<button type="button" class="btn btn-danger" onclick="UpdateHapusVarianWajibDetail('+ countheader +')"><i class="fa fa-trash"></i></button>';
					strvarianwajibdetail = strvarianwajibdetail + '		</div>';
					strvarianwajibdetail = strvarianwajibdetail + '	</div>';
					strvarianwajibdetail = strvarianwajibdetail + '</div>';
				}
				
				$("#updatevarianwajibdetail"+ countheader).append( strvarianwajibdetail ); 
				
				NamaVarian = Varian_Nama;
				IsHeader = 1;
			
				$(".updatecbvarianwajibdetail__"+ countheader).css("width", "100%");
				$(".updatecbvarianwajibdetail__"+ countheader).select2({
					"theme": "bootstrap", "val": ""
				});
			}
		}
		
		if( SKU.SKU_Varian_Non_WajibMenu != 0 )
		{
			var NamaVarian = '';
			var countheader = 0;
			
			for( i=0 ; i<SKU.SKU_Varian_NonWajibMenu.length ; i++ )
			{
				var SKU_Varian_ID 		= SKU.SKU_Varian_NonWajibMenu[i].SKU_Varian_ID;
				var SKU_ID				= SKU.SKU_Varian_NonWajibMenu[i].SKU_ID;
				var Var_ID   			= SKU.SKU_Varian_NonWajibMenu[i].Var_ID;
				var Varian_ID   		= SKU.SKU_Varian_NonWajibMenu[i].Varian_ID;
				var Varian_Nama			= SKU.SKU_Varian_NonWajibMenu[i].Varian_Nama;
				var IsWajib				= SKU.SKU_Varian_NonWajibMenu[i].IsWajib;
				var VarianDetail_ID		= SKU.SKU_Varian_NonWajibMenu[i].VarianDetail_ID;
				var VarianDetail_Nama	= SKU.SKU_Varian_NonWajibMenu[i].VarianDetail_Nama;
				var SKUInduk_ID			= SKU.SKU_Varian_NonWajibMenu[i].SKUInduk_ID;

				var isHeader = 0;

				var strheader = '';
				var strfooter = '';
				
				
				if( NamaVarian != Varian_Nama && Varian_ID == Var_ID )
				{
					countheader = countheader + 1;
					
					var strvarian = '';
					strvarian = strvarian + '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 updatevarianheader" style="padding: 10px; margin-bottom: 10px; background-color: #dddddd;" id="updatevarianheader'+ countheader +'">';
					strvarian = strvarian + '	<div class="row">';
					strvarian = strvarian + '		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">';
					strvarian = strvarian + '			<div class="input-group">';
					strvarian = strvarian + '				<select class="form-control updatecbvarian updatecbvarian_'+ countheader +'" id="updatecbvarian'+ countheader +'" onchange="UpdateChangeVarian('+ countheader +')">';
					strvarian = strvarian + '					<option value="">Pilih Varian</option>';
					
					for( j=0; j<arrVarian.length ; j++ )
					{
						var DVarian_ID 		= arrVarian[j].Varian_ID;
						var DVarian_Kode 	= arrVarian[j].Varian_Kode;
						var DVarian_Nama 	= arrVarian[j].Varian_Nama;
						var DIsWajib 		= arrVarian[j].IsWajib;
						
						if( Varian_ID == DVarian_ID )	{	var isselected = 'selected';	}
						else 							{	var isselected = '';			}
						
						strvarian = strvarian + '				<option value="'+ DVarian_ID +'" '+ isselected +'>'+ DVarian_Nama +'</option>';
					}
				
					strvarian = strvarian +	'				</select>';
					strvarian = strvarian +	'				<div class="input-group-btn">';
					strvarian = strvarian + '					<button type="button" class="btn btn-danger" onclick="UpdateHapusVarian('+ countheader +')"><i class="fa fa-trash"></i></button>';
					strvarian = strvarian + '				</div>';
					strvarian = strvarian + '			</div>';
					strvarian = strvarian + '		</div>';
					strvarian = strvarian + '	</div>';
				
					strvarian = strvarian + '	<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-horizontal form-label-left" id="updatedivvariandetail'+ countheader +'">';
					strvarian = strvarian + '		<label class="control-label col-xs-4 col-sm-3 col-md-3 col-lg-3 col-xl-3">Jenis</label>';
					strvarian = strvarian + '		<div class="col-xs-8 col-sm-9 col-md-9 col-lg-9 col-xl-9" id="updatevariandetail'+ countheader +'">';
					strvarian = strvarian + '		</div>';
					strvarian = strvarian + '	</div>';
					strvarian = strvarian + '	<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">';
					strvarian = strvarian + ' 		<button type="button" class="form-control btn btn-primary" onclick="UpdateTambahVarianDetail('+ countheader +')">';
					strvarian = strvarian + '			<i class="fa fa-plus"></i> Tambah Jenis';
					strvarian = strvarian + '		</button>';
					strvarian = strvarian + '	</div>';
					strvarian = strvarian + '</div>';
					
					$("#updatedivvarian").append( strvarian );
				
				}
				
				
				var strvariandetail = '';
				
				if( Var_ID == Varian_ID )
				{
					
					var VNama = Varian_Nama;
					
					strvariandetail = strvariandetail + '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 updatedivrowvarian" id="updatedivrowvarian'+ countheader +'">';
					strvariandetail = strvariandetail + '	<div class="input-group">';
					strvariandetail = strvariandetail + '		<select class="form-control updatecbvariandetail updatecbvariandetail__'+ countheader +'" id="updatecbvariandetail__'+ countheader +'" onchange="UpdateCheckValues('+ countheader +','+ countheader +',\''+ Varian_ID +'\',\''+ VNama +'\')">';
						
					strvariandetail = strvariandetail + '			<option value="">Pilih jenis</option>';
					strvarianwajibdetail = strvarianwajibdetail + '	<option value="ADD">**Buat Jenis Baru**</option>';
					for( j=0; j<arrVarianDetail.length ; j++ )
					{
						var DVarian_ID 			= arrVarianDetail[j].Varian_ID;
						var DVarianDetail_ID 	= arrVarianDetail[j].VarianDetail_ID;
						var DVarianDetail_Nama 	= arrVarianDetail[j].VarianDetail_Nama;
						
						if( VarianDetail_ID == DVarianDetail_ID )	{	var isselected = 'selected';	}
						else 										{	var isselected = '';			}
						
						if( DVarian_ID == Varian_ID )
						{
							strvariandetail = strvariandetail + '<option value="'+ DVarian_ID +' | '+ DVarianDetail_ID +'" '+ isselected +'>'+ DVarianDetail_Nama +'</option>';
						}
					}
					
					strvariandetail = strvariandetail + '		</select>';
					strvariandetail = strvariandetail + '		<div class="input-group-btn">';
					strvariandetail = strvariandetail + '			<button type="button" class="btn btn-danger" onclick="UpdateHapusVarianDetail('+ countheader +')"><i class="fa fa-trash"></i></button>';
					strvariandetail = strvariandetail + '		</div>';
					strvariandetail = strvariandetail + '	</div>';
					strvariandetail = strvariandetail + '</div>';
				}
				$("#updatevariandetail"+ countheader).append( strvariandetail ); 
				
				if( Var_ID == Varian_ID )
				{
					NamaVarian = Varian_Nama;
				}
				
				IsHeader = 1;
				
				$("#updatecbvarian"+ countheader).css("width", "100%");
				$("#updatecbvarian"+ countheader).select2({
					"theme": "bootstrap", "val": ""
				});
				
				$(".updatecbvariandetail"+ countheader).css("width", "100%");
				$(".updatecbvariandetail"+ countheader).select2({
					"theme": "bootstrap", "val": ""
				});
			}
		}
		
		$("#updatestep2").removeAttr('style');		
		$("#updatestep3").removeAttr('style');				
		//$("#step4").removeAttr('style');	
		
		UpdateProses( 2 );
	}
	
	$("#updatebtnkembalike1").click
	(
		function()
		{
			UpdateProses( 0 );
		}
	);
	
	$("#updatebtnkembalike2").click
	(
		function()
		{
			UpdateProses( 0 );
		}
	);
	
	$("#updatebtnkembalike3").click
	(
		function()
		{
			UpdateProses( 2 );
		}
	);
	
	$("#updatebtnproses3").click
	(
		function()
		{
			UpdateProses( 3 );
		}
	);
		
	$("#updatebtnsimpandata").click
	(
		function()
		{
			$("#updatepreviewsimpandata").modal('show');
		}
	);
	
	function UpdateChGeneratedStringSKU( JSONStringSKU )
	{
		var StringSKU = JSON.parse( JSONStringSKU );
		
		if( StringSKU.StringSKUMenu.length > 0 )
		{
			if( $.fn.DataTable.isDataTable('#updatetablegeneratedstringsku') ) 
			{
				$('#updatetablegeneratedstringsku').DataTable().destroy();
			}

			$('#updatetablegeneratedstringsku > tbody').empty();
			
			var strSKU_PPh = '';
			strSKU_PPh = strSKU_PPh + '<option value="1">Include</option>';
			strSKU_PPh = strSKU_PPh + '<option value="2">Exclude</option>';
			strSKU_PPh = strSKU_PPh + '<option value="3">None</option>';
			
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
			
			var strKemasan = '';
			
			for( i=0 ; i< StringSKU.StringSKUMenu.length ; i++ )
			{
				var RandBosnetID = Math.random() * 1000000000000000000;
				
				var SKU_NamaProduk 	= StringSKU.StringSKUMenu[i].SKU_NamaProduk;
				var SKU_Kode 		= StringSKU.StringSKUMenu[i].SKU_kode;
				var SKU_Kemasan 	= StringSKU.StringSKUMenu[i].kemasan;
				var SKU_Satuan 		= StringSKU.StringSKUMenu[i].SKU_Satuan;
				
				var Berat 			= StringSKU.StringSKUMenu[i].berat;
				var nama1 			= StringSKU.StringSKUMenu[i].nama1;
				var nama2 			= StringSKU.StringSKUMenu[i].nama2;
				var nama3 			= StringSKU.StringSKUMenu[i].nama3;
				var nama4 			= StringSKU.StringSKUMenu[i].nama4;
				var Unit 			= StringSKU.StringSKUMenu[i].unit;
				
				var str = '';
				
				str = str + '<tr>';
				str = str + '	<input type="hidden" class="updatehdnama1" value="'+ nama1 +'" />';							
				str = str + '	<input type="hidden" class="updatehdnama2" value="'+ nama2 +'" />';							
				str = str + '	<input type="hidden" class="updatehdnama3" value="'+ nama3 +'" />';	
				str = str + '	<input type="hidden" class="updatehdnama4" value="'+ nama4 +'" />';	
				str = str + '	<input type="hidden" class="updatehdunit" value="'+ Unit +'" />';	
				
				str = str + '	<td><button type="button" class="form-control btn btn-danger" onclick="UpdateHapusGenerated(this.parentNode.parentNode.rowIndex)"><i class="fa fa-times"></i></button></td>';							
				
				str = str + '	<td><button type="button" class="form-control btn btn-danger" onclick="UpdateCopas(this.parentNode.parentNode.rowIndex)"><i class="fa fa-paste"></i> &nbsp;&nbsp;<i class="fa fa-angle-right"></i></button></td>';							
				str = str + '	<input type="hidden" class="updatetxtSKU_Kode" value="'+ SKU_Kode +'" />';							
				str = str + '	<td><input type="text" style="width: 500px;" class="updatetxtSKU_NamaProduk form-control" disabled="disabled" value="'+ SKU_NamaProduk +'" /></td>';
				str = str + '	<td><button type="button" id="updateubahbtnviewSKUBosnet_ID" class="btn btn-primary updateubahbtnviewSKUBosnet_ID" onclick="UpdateUbahViewBosnet(\''+ RandBosnetID +'\',\''+ SKU_NamaProduk +'\')" ><i class="fa fa-pencil"></i> Isi Bosnet ID</button></td>';
				str = str + '	<td><input type="text" class="updatetxtSKU_Deskripsi form-control" /></td>';
				str = str + '	<td><input type="text" class="updatetxtSKU_Kemasan form-control" value="'+ SKU_Kemasan +'" disabled="disabled" /></td>';
				str = str + '	<td><input type="text" class="updatetxtSKU_Satuan form-control" value="'+ nama1 +'" disabled="disabled" /></td>';
				str = str + '	<td><select class="updatecbSKU_Kondisi form-control">'+ strSKU_Kondisi +'</select></td>';
				str = str + '	<td><input type="text" class="updatetxtSKU_Origin form-control" /></td>';
				str = str + '	<td><input type="number" class="updatetxtSKU_HargaJual form-control" value="0" /></td>';
				str = str + '	<td><input type="number" class="updatetxtSKU_SalesMinQty form-control" value="0" /></td>';
				//str = str + '	<td><select class="updatecbSKU_PPh form-control">'+ strSKU_PPh +'</select></td>';
				//str = str + '	<td><input type="number" class="updatetxtSKU_PPnBM_Persen form-control" value="0" /></td>';
				//str = str + '	<td><input type="number" class="updatetxtSKU_PPn_Persen form-control" value="0" /></td>';
				str = str + '	<td><input type="checkbox" class="checkbox_form updatechSKU_IsActive" checked="checked" /></td>';
				str = str + '	<td><input type="checkbox" class="checkbox_form updatechSKU_IsJual" checked="checked" /></td>';
				str = str + '	<td><input type="number" class="updatetxtSKU_WeightNetto form-control" value="'+ Berat +'" /></td>';
				str = str + '	<td><select class="updatecbSKU_WeightNettoUnit form-control">'+ strSatuanBerat +'</select></td>';
				str = str + '	<td><input type="number" class="updatetxtSKU_WeightProduct form-control" value="0" /></td>';
				str = str + '	<td><select class="updatecbSKU_WeightProductUnit form-control">'+ strSatuanBerat +'</select></td>';
				str = str + '	<td><input type="number" class="updatetxtSKU_WeightPackaging form-control" value="0" /></td>';
				str = str + '	<td><select class="updatecbSKU_WeightPackagingUnit form-control">'+ strSatuanBerat +'</select></td>';
				str = str + '	<td><input type="number" class="updatetxtSKU_WeightGift form-control" value="0" /></td>';
				str = str + '	<td><select class="updatecbSKU_WeightGiftUnit form-control">'+ strSatuanBerat +'</select></td>';
				str = str + '	<td><input type="number" class="updatetxtSKU_Length form-control" value="0" /></td>';
				str = str + '	<td><select class="updatecbSKU_LengthUnit form-control">'+ strSatuanPanjang +'</select></td>';
				str = str + '	<td><input type="number" class="updatetxtSKU_Width form-control" value="0" /></td>';
				str = str + '	<td><select class="updatecbSKU_WidthUnit form-control">'+ strSatuanLebar +'</select></td>';
				str = str + '	<td><input type="number" class="updatetxtSKU_Height form-control" value="0" /></td>';
				str = str + '	<td><select class="updatecbSKU_HeightUnit form-control">'+ strSatuanTinggi +'</select></td>';
				str = str + '	<td><input type="number" class="updatetxtSKU_Volume form-control" value="0" /></td>';
				str = str + '	<td><select class="updatecbSKU_VolumeUnit form-control">'+ strSatuanVolume +'</select></td>';
				str = str + '</tr>';
				
				$("#updatetablegeneratedstringsku > tbody").append( str );
				
				$(".updatecbSKU_WeightNettoUnit").eq(i).val( Unit );
				
			}
			
		}
		
		$('#updatetablegeneratedstringsku').DataTable(
		{
			//scrollX: "100%",
			retrieve: true,
			initComplete: function (settings, json) 
			{  
				$("#updatetablegeneratedstringsku").wrap("<div style='overflow:auto; width:100%; position:relative;'></div>");            
			},
			"dom": '<"left"f>rt'
		});
		
		$(".updatecbKemasanID").css("width", "100%");
		$(".updatecbKemasanID").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updatecbSKU_WeightNettoUnit").css("width", "100%");
		$(".updatecbSKU_WeightNettoUnit").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updatecbSKU_WeightProductUnit").css("width", "100%");
		$(".updatecbSKU_WeightProductUnit").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updatecbSKU_WeightPackagingUnit").css("width", "100%");
		$(".updatecbSKU_WeightPackagingUnit").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updatecbSKU_WeightGiftUnit").css("width", "100%");
		$(".updatecbSKU_WeightGiftUnit").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updatecbSKU_HeightUnit").css("width", "100%");
		$(".updatecbSKU_HeightUnit").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updatecbSKU_LengthUnit").css("width", "100%");
		$(".updatecbSKU_LengthUnit").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updatecbSKU_VolumeUnit").css("width", "100%");
		$(".updatecbSKU_VolumeUnit").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updatecbSKU_WidthUnit").css("width", "100%");
		$(".updatecbSKU_WidthUnit").select2({
			"theme": "bootstrap", "val": ""
		});
		
		UpdateProses( 2 );
	}
	
	function UpdateHapusGenerated( rowIdx )
	{
		$("#updatehdhapusidx").val( rowIdx );
		
		$("#updatepreviewhapusgeneratedsku").modal('show');
	}
	
	$("#updatebtnyeshapusgeneratedsku").click
	(
		function()
		{
			var table = document.getElementById('updatetablegeneratedstringsku');

			table.deleteRow( $("#updatehdhapusidx").val() );
		}
	);
	
	function UpdateUbahViewBosnet( RandBosnetID, P_SKU_NamaProduk )
	{
		$("#updateubahtxtRandBosnetID").val( RandBosnetID );
		$("#updateubahtxtBosnet_SKU_NamaProduk").val( P_SKU_NamaProduk );
		
		$("#updateubahtbskubosnet tbody").html('');
		
		for( i=0 ; i<arrUpdateUbahSKUBosnet_ID.length ; i++ )
		{
			var Bosnet_ID = arrUpdateUbahSKUBosnet_ID[i].Bosnet_ID;
			var SKU_NamaProduk = arrUpdateUbahSKUBosnet_ID[i].SKU_NamaProduk;
			
			if( SKU_NamaProduk == P_SKU_NamaProduk )
			{				
				var str = ''; 
				str = str + '<tr>';
				str = str + '	<td width="80%"><input type="text" class="updateubahtxtSKUBosnet_ID form-control" value="'+ Bosnet_ID +'" /></td>';
				str = str + '	<td width="20%"><button type="button" class="updateubahbtndeletebosnetid btn btn-danger" onclick="UpdateUbahDeleteBosnet( this.parentNode.parentNode.rowIndex )"><i class="fa fa-trash-o"></i></button></td>';
				str = str + '</tr>';
				
				$("#updateubahtbskubosnet tbody").append( str );
			}
		}
			
		$("#updateubahpreviewaddbosnet").modal('show');
	}
	
	$("#updateubahbtntambahbosnet").click
	(
		function()
		{		
			var RandBosnetID = $("#updateubahtxtRandBosnetID").val();
			
			var str = ''; 
			str = str + '<tr>';
			str = str + '	<td width="80%"><input type="text" class="updateubahtxtSKUBosnet_ID form-control" /></td>';
			str = str + '	<td width="20%"><button type="button" class="updateubahbtndeletebosnetid btn btn-danger" onclick="UpdateUbahDeleteBosnet( this.parentNode.parentNode.rowIndex )"><i class="fa fa-trash-o"></i></button></td>';
			str = str + '</tr>';
			
			$("#updateubahtbskubosnet tbody").append( str );
		}
	);
	
	function UpdateUbahDeleteBosnet( Idx )
	{
		$("#updateubahtbskubosnet tbody tr:eq("+ Idx +")").remove();
	}
	
	$("#updateubahbtnsaveskubosnetid").click
	(
		function()
		{
			var Rand = $("#updateubahtxtRandBosnetID").val();
			var P_SKU_NamaProduk = $("#updateubahtxtBosnet_SKU_NamaProduk").val();
			
			// bersihin dulu bosnet id nya
			for( i= arrUpdateUbahSKUBosnet_ID.length - 1 ; i>= 0 ; i-- )
			{
				var Bosnet_ID 		= arrUpdateUbahSKUBosnet_ID[i].Bosnet_ID;
				var SKU_NamaProduk 	= arrUpdateUbahSKUBosnet_ID[i].SKU_NamaProduk;
				
				if( P_SKU_NamaProduk == SKU_NamaProduk )
				{
					arrSKUBosnet_ID.splice( i, 1);
				}
			}
			
			var ltbbosnet = $(".updateubahtxtSKUBosnet_ID").length;
			for( i=0 ; i<ltbbosnet ; i++ )
			{
				var Bosnet_ID = $(".updateubahtxtSKUBosnet_ID").eq(i).val();
				
				arrUpdateUbahSKUBosnet_ID.push( { RandID : Rand, SKU_NamaProduk : P_SKU_NamaProduk, Bosnet_ID : Bosnet_ID } );
			}
			
			$("#updateubahpreviewaddbosnet").modal('hide');
		}
	);
	
	$("#updatecbKategori1").change
	(
		function()
		{
			var Kategori1_ID = $("#updatecbKategori1 option:selected").val();
			
			if( Kategori1_ID == '' )
			{
				$("#updatekat2").attr('style','display: none');
				$("#updatekat3").attr('style','display: none');
				//$("#pri").attr('style','display: none');
			
				$("#updatecbKategori2").html('');
				$("#updatecbKategori2").append('<option value="" selected>--Pilih Sub Kategori--</option>');
				$("#updatecbKategori3").html('');
				$("#updatecbKategori3").append('<option value="" selected>--Pilih Sub Kategori 2--</option>');
				//$("#cbKategori4").html('');
				//$("#cbKategori4").append('<option value="" selected>--Pilih Principle--</option>');
			}
			else
			{
				UpdateGetSKUCategory( 1, Kategori1_ID );
			}
		}
	);
		
	$("#updatecbKategori2").change
	(
		function()
		{
			var Kategori1_ID = $("#updatecbKategori2 option:selected").val();
			
			if( Kategori1_ID == '' )
			{
				$("#updatekat3").attr('style','display: none');
				//$("#pri").attr('style','display: none');
				
				$("#updatecbKategori3").html('');
				$("#updatecbKategori3").append('<option value="" selected>--Pilih Sub Kategori 2--</option>');
				//$("#cbKategori4").html('');
				//$("#cbKategori4").append('<option value="" selected>--Pilih Principle--</option>');
			}
			else
			{
				UpdateGetSKUCategory( 2, Kategori1_ID );
			}
		}
	);
	
	$("#updatecbKategori3").change
	(
		function()
		{
			var Kategori1_ID = $("#updatecbKategori3 option:selected").val();
			
			if( Kategori1_ID == '' )
			{
				$("#updatekat4").attr('style','display: none');
				//$("#pri").attr('style','display: none');
				
				$("#updatecbKategori4").html('');
				$("#updatecbKategori4").append('<option value="" selected>--Pilih Sub Kategori 3--</option>');
				//$("#cbKategori4").html('');
				//$("#cbKategori4").append('<option value="" selected>--Pilih Principle--</option>');
			}
			else
			{
				UpdateGetSKUCategory( 3, Kategori1_ID );
			}
		}
	);
	
	function UpdateGetSKUCategory( Jenis, Kategori1_ID )
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
					UpdateChSKUCategory( response, Jenis );
				}
			});
		}
		else if( Kategori1_ID == '' )
		{
			UpdateChSKUCategory( 0, Jenis );
		}
	}
	
	function UpdateChSKUCategory( JSONJenisKategori, Jenis )
	{
		if( JSONJenisKategori != 0)
		{
			var Kategori = JSON.parse( JSONJenisKategori );
			
			if( Jenis == 0 )		
			{	
				$("#updatecbKategori1").html('');
				$("#updatecbKategori1").append( '<option value="" selected>--Pilih Kategori--</option>' );	
			}
			if( Jenis == 1 )		
			{	
				$("#updatekat2").removeAttr('style');
				$("#updatecbKategori2").html('');
				$("#updatecbKategori2").append( '<option value="" selected>--Pilih Sub Kategori--</option>' );
				
				$("#updatekat3").attr('style','display: none;');
				$("#updatecbKategori3").html('');
				$("#updatecbKategori3").append( '<option value="" selected>--Pilih Sub Kategori 2--</option>' );	
			}
			if( Jenis == 2 )		
			{	
				$("#updatekat3").removeAttr('style');
				$("#updatecbKategori3").html('');
				$("#updatecbKategori3").append( '<option value="" selected>--Pilih Sub Kategori 2--</option>' );	
				
				$("#updatekat4").attr('style','display: none;');
				$("#updatecbKategori4").html('');
				$("#updatecbKategori4").append( '<option value="" selected>--Pilih Sub Kategori 3--</option>' );	
			}
			else if( Jenis == 3 )	
			{	
				$("#updatekat4").removeAttr('style');
				$("#updatecbKategori4").html('');
				$("#updatecbKategori4").append( '<option value="" selected>--Pilih Sub Kategori 3--</option>' );	
				
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
						$("#updatecbKategori1").append( '<option value="'+ Kategori1ID +'">'+ Kategori1_Nama +'</option>' );	
					}
					else if( Jenis == 1 )	
					{	
						$("#updatecbKategori2").append( '<option value="'+ Kategori1ID +'">'+ Kategori1_Nama +'</option>' );	
					}
					else if( Jenis == 2 )		
					{	
						$("#updatecbKategori3").append( '<option value="'+ Kategori1ID +'">'+ Kategori1_Nama +'</option>' );	
					}
					else if( Jenis == 3 )		
					{	
						$("#updatecbKategori4").append( '<option value="'+ Kategori1ID +'">'+ Kategori1_Nama +'</option>' );	
					}
					else if( Jenis == 9 )	
					{	
						$("#updatecbKategori9").append( '<option value="'+ Kategori1ID +'">'+ Kategori1_Nama +'</option>' );	
					}
					else if( Jenis == 10 )	
					{	
						$("#updatecbKategori10").append( '<option value="'+ Kategori1ID +'">'+ Kategori1_Nama +'</option>' );	
					}
				}
			}
		}
		else
		{
			$("#updatekat1").attr('style','display: none;');
			$("#updatekat2").attr('style','display: none;');
			$("#updatekat3").attr('style','display: none;');
			$("#updatekat4").attr('style','display: none;');
			$("#updatepri").attr('style','display: none;');
			$("#updatebra").attr('style','display: none;');
		}
	}
	
	$("#updatebtnproses2").click
	(
		function()
		{
			$("#updatebtnproses2").prop('disabled', true );
			
			var lvarian = $(".updatecbvarian").length;
			
			var lvariandetail = $(".updatecbvariandetail").length;
			
			var arrVarian_ID = [];
			var arrDataVarian = [];
			
			var numerror = 0;
			
			for( i=0 ; i<lvarian ; i++ )
			{
				var Varian_ID = $(".updatecbvarian").eq(i).val();
				var Varian_Nama = $(".updatecbvarian").eq(i).html();
				
				var NUM_ID = $(".updatecbvarian").eq(i).attr('class');
				NUM_ID = NUM_ID.split('_');
				NUM_ID = NUM_ID[1];
				NUM_ID = NUM_ID.split(' ');
				NUM_ID = NUM_ID[0];
				
				if( $(".updatecbvariandetail__"+ NUM_ID).length == 0 )
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
				
				var lvarianwajibdetail = $(".updatecbvarianwajibdetail").length;
				
				for( i=0 ; i<lvarianwajibdetail ; i++ )
				{
					var ID = $(".updatecbvarianwajibdetail").eq(i).attr('class');
					
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
				arrUpdateDataVarianWajibDetail = [];
				
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
						
						var cbvarianwajibdetail = $(".updatecbvarianwajibdetail__"+ NUM_ID ).length;
						
						
						for( j=0 ; j<cbvarianwajibdetail ; j++ )
						{
							var VarianWajibDetail = $(".updatecbvarianwajibdetail__"+ NUM_ID).eq(j).val();
							
							if( VarianWajibDetail == '' )
							{
								numerror = 5;
							}
							else
							{
								VarianWajibDetail = VarianWajibDetail.split(' | ');
								
								Varian_ID = VarianWajibDetail[0];
								VarianDetail_ID = VarianWajibDetail[1];
								
								if( !arrVarianWajibDetail_ID.includes( VarianDetail_ID ) )
								{
									arrVarianWajibDetail_ID.push( VarianDetail_ID );
									
									arrUpdateDataVarianWajibDetail.push( { Varian_ID : Varian_ID, Varian_Urut: 1, VarianDetail_ID : VarianDetail_ID, VarianDetail_Urut : parseInt(j)+1 } );
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
						
						var lvariandetail = $(".updatecbvariandetail").length;
						
						for( i=0 ; i<lvariandetail ; i++ )
						{
							var ID = $(".updatecbvariandetail").eq(i).attr('class');
							
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
							
							var SKUInduk_ID = $("#updatecbskuinduk option:selected").val();
							
							$.ajax(
							{
								type: 'POST',    
								url: "<?= base_url('SKUHadiah/GenerateStringSKU') ?>",
								data : 
								{
									SKUInduk_ID 				: SKUInduk_ID, 
									arrDataVarianWajibDetail 	: arrUpdateDataVarianWajibDetail,
									arrDataVarianDetail 		: 0
								},
								success: function( response )
								{
									UpdateChGeneratedStringSKU( response );
								}
							});	
						}
						else
						{
							for( i=0 ; i<arrIDVarianDetail.length ; i++ )
							{
								var NUM_ID = arrIDVarianDetail[i];
								
								var cbvariandetail = $(".updatecbvariandetail__"+ NUM_ID ).length;
								
								
								for( j=0 ; j<cbvariandetail ; j++ )
								{
									var VarianDetail = $(".updatecbvariandetail__"+ NUM_ID).eq(j).val();
									
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
											arrUpdateDataVarianDetail.push( { Varian_ID : Varian_ID, Varian_Urut: parseInt(i)+2, VarianDetail_ID : VarianDetail_ID, VarianDetail_Urut: parseInt(j)+1 } );
											
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
								var SKUInduk_ID = $("#updatecbskuinduk option:selected").val();
							
								$.ajax(
								{
									type: 'POST',    
									url: "<?= base_url('SKUHadiah/GenerateStringSKU') ?>",
									data : 
									{
										SKUInduk_ID 				: SKUInduk_ID, 
										arrDataVarianWajibDetail 	: arrUpdateDataVarianWajibDetail,
										arrDataVarianDetail 		: arrUpdateDataVarianDetail
									},
									success: function( response )
									{
										UpdateChGeneratedStringSKU( response );
									}
								});	
							}
						}
						
					}
				}
			}
			
			$("#updatebtnproses2").prop('disabled', false );
		}
	);
	
	$("#updatebtntambahsku").click
	(
		function()
		{
			
			if( $.fn.DataTable.isDataTable('#updatetablegeneratedstringsku') ) 
			{
				$('#updatetablegeneratedstringsku').DataTable().destroy();
			}

			//$('#updatetablegeneratedstringsku > tbody').empty();
			
			var strSKU_PPh = '';
			strSKU_PPh = strSKU_PPh + '<option value="1">Include</option>';
			strSKU_PPh = strSKU_PPh + '<option value="2">Exclude</option>';
			strSKU_PPh = strSKU_PPh + '<option value="3">None</option>';
			
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
			
			str = str + '	<td><button type="button" class="form-control btn btn-danger" onclick="UpdateHapusGenerated(this.parentNode.parentNode.rowIndex)"><i class="fa fa-times"></i></button></td>';							
			
			str = str + '	<td><button type="button" class="form-control btn btn-danger" onclick="UpdateCopas(this.parentNode.parentNode.rowIndex)"><i class="fa fa-paste"></i> &nbsp;&nbsp;<i class="fa fa-angle-right"></i></button></td>';							
			str = str + '	<input type="hidden" class="updatetxtSKU_Kode" value="" />';							
			str = str + '	<td><input type="text" style="width: 500px;" class="updatetxtSKU_NamaProduk form-control" value="" /></td>';
			str = str + '	<td><button type="button" id="updateubahbtnviewSKUBosnet_ID" class="btn btn-primary updateubahbtnviewSKUBosnet_ID" onclick="UpdateUbahViewBosnet(\''+ RandBosnetID +'\', this.parentNode.parentNode.rowIndex )" ><i class="fa fa-pencil"></i> Isi Bosnet ID</button></td>';
			str = str + '	<td><input type="text" class="updatetxtSKU_Deskripsi form-control" /></td>';
			str = str + '	<td><select class="updatecbSKU_Kemasan form-control">'+ strkemasan +'</select></td>';
			str = str + '	<td><select class="updatecbSKU_Satuan form-control">'+ strsatuan +'</select></td>';
			str = str + '	<td><select class="updatecbSKU_Kondisi form-control">'+ strSKU_Kondisi +'</select></td>';
			str = str + '	<td><input type="text" class="updatetxtSKU_Origin form-control" /></td>';
			str = str + '	<td><input type="number" class="updatetxtSKU_HargaJual form-control" value="0" /></td>';
			str = str + '	<td><input type="number" class="updatetxtSKU_SalesMinQty form-control" value="0" /></td>';
			str = str + '	<td><input type="checkbox" class="checkbox_form updatechSKU_IsActive" checked="checked" /></td>';
			str = str + '	<td><input type="number" class="updatetxtSKU_WeightNetto form-control" value="0" /></td>';
			str = str + '	<td><select class="updatecbSKU_WeightNettoUnit form-control">'+ strSatuanBerat +'</select></td>';
			str = str + '	<td><input type="number" class="updatetxtSKU_WeightProduct form-control" value="0" /></td>';
			str = str + '	<td><select class="updatecbSKU_WeightProductUnit form-control">'+ strSatuanBerat +'</select></td>';
			str = str + '	<td><input type="number" class="updatetxtSKU_WeightPackaging form-control" value="0" /></td>';
			str = str + '	<td><select class="updatecbSKU_WeightPackagingUnit form-control">'+ strSatuanBerat +'</select></td>';
			str = str + '	<td><input type="number" class="updatetxtSKU_WeightGift form-control" value="0" /></td>';
			str = str + '	<td><select class="updatecbSKU_WeightGiftUnit form-control">'+ strSatuanBerat +'</select></td>';
			str = str + '	<td><input type="number" class="updatetxtSKU_Length form-control" value="0" /></td>';
			str = str + '	<td><select class="updatecbSKU_LengthUnit form-control">'+ strSatuanPanjang +'</select></td>';
			str = str + '	<td><input type="number" class="updatetxtSKU_Width form-control" value="0" /></td>';
			str = str + '	<td><select class="updatecbSKU_WidthUnit form-control">'+ strSatuanLebar +'</select></td>';
			str = str + '	<td><input type="number" class="updatetxtSKU_Height form-control" value="0" /></td>';
			str = str + '	<td><select class="updatecbSKU_HeightUnit form-control">'+ strSatuanTinggi +'</select></td>';
			str = str + '	<td><input type="number" class="updatetxtSKU_Volume form-control" value="0" /></td>';
			str = str + '	<td><select class="updatecbSKU_VolumeUnit form-control">'+ strSatuanVolume +'</select></td>';
			str = str + '</tr>';
			
			$("#updatetablegeneratedstringsku > tbody").append( str );
			
			//$(".updatecbSKU_WeightNettoUnit").eq(i).val( Unit );
			
			$(".updatecbKemasanID").css("width", "100%");
			$(".updatecbKemasanID").select2({
				"theme": "bootstrap", "val": ""
			});
			
			$(".updatecbSKU_WeightNettoUnit").css("width", "100%");
			$(".updatecbSKU_WeightNettoUnit").select2({
				"theme": "bootstrap", "val": ""
			});
			
			$(".updatecbSKU_WeightProductUnit").css("width", "100%");
			$(".updatecbSKU_WeightProductUnit").select2({
				"theme": "bootstrap", "val": ""
			});
			
			$(".updatecbSKU_WeightPackagingUnit").css("width", "100%");
			$(".updatecbSKU_WeightPackagingUnit").select2({
				"theme": "bootstrap", "val": ""
			});
			
			$(".updatecbSKU_WeightGiftUnit").css("width", "100%");
			$(".updatecbSKU_WeightGiftUnit").select2({
				"theme": "bootstrap", "val": ""
			});
			
			$(".updatecbSKU_HeightUnit").css("width", "100%");
			$(".updatecbSKU_HeightUnit").select2({
				"theme": "bootstrap", "val": ""
			});
			
			$(".updatecbSKU_LengthUnit").css("width", "100%");
			$(".updatecbSKU_LengthUnit").select2({
				"theme": "bootstrap", "val": ""
			});
			
			$(".updatecbSKU_VolumeUnit").css("width", "100%");
			$(".updatecbSKU_VolumeUnit").select2({
				"theme": "bootstrap", "val": ""
			});
			
			$(".updatecbSKU_WidthUnit").css("width", "100%");
			$(".updatecbSKU_WidthUnit").select2({
				"theme": "bootstrap", "val": ""
			});
			
			$(".updatecbSKU_Kemasan").css("width", "100%");
			$(".updatecbSKU_Kemasan").select2({
				"theme": "bootstrap", "val": ""
			});
			
			$(".updatecbSKU_Satuan").css("width", "100%");
			$(".updatecbSKU_Satuan").select2({
				"theme": "bootstrap", "val": ""
			});
			
			$('#updatetablegeneratedstringsku').DataTable(
			{
				//scrollX: "100%",
				retrieve: true,
				initComplete: function (settings, json) 
				{  
					$("#updatetablegeneratedstringsku").wrap("<div style='overflow:auto; width:100%; position:relative;'></div>");            
				},
				"dom": '<"left"f>rt'
			});
			
		}
	);
	
	$("#updatebtnhapussalinan").click
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
	
	$("#updatebtnsalin").click
	(
		function()
		{
			
			//var SKU_Kode 				= $("#txtS_SKU_Kode").val();							
			//var SKU_NamaProduk 		= $("#txtS_SKU_NamaProduk").val();
			var SKU_Deskripsi 			= $("#updatetxtS_SKU_Deskripsi").val();
			var KemasanID	 			= $("#updatecbS_KemasanID option:selected").val();
			var SKU_Kondisi 			= $("#updatecbS_SKU_Kondisi option:selected").val();
			var SKU_Origin 				= $("#updatetxtS_SKU_Origin").val();
			var SKU_HargaJual 			= $("#updatetxtS_SKU_HargaJual").val();
			var SKU_SalesMinQty 		= $("#updatetxtS_SKU_SalesMinQty").val();
			//var SKU_PPh 				= $("#updatecbS_SKU_PPh").val();
			//var SKU_PPnBM_Persen 		= $("#updatetxtS_SKU_PPnBM_Persen").val();
			//var SKU_PPn_Persen			= $("#updatetxtS_SKU_PPn_Persen").val();
			var SKU_IsActive			= $("#updatechS_SKU_IsActive").prop('checked');
			var SKU_IsJual				= 0; //$("#updatechS_SKU_IsJual").prop('checked');
			var SKU_WeightNetto			= $("#updatetxtS_SKU_WeightNetto").val();
			var SKU_WeightNettoUnit		= $("#updatecbS_SKU_WeightNettoUnit option:selected").val();
			var SKU_WeightProduct		= $("#updatetxtS_SKU_WeightProduct").val();
			var SKU_WeightProductUnit	= $("#updatecbS_SKU_WeightProductUnit option:selected").val();
			var SKU_WeightPackaging		= $("#updatetxtS_SKU_WeightPackaging").val();
			var SKU_WeightPackagingUnit	= $("#updatecbS_SKU_WeightPackagingUnit option:selected").val();
			var SKU_WeightGift			= $("#updatetxtS_SKU_WeightGift").val();
			var SKU_WeightGiftUnit		= $("#updatecbS_SKU_WeightGiftUnit option:selected").val();
			var SKU_Length				= $("#updatetxtS_SKU_Length").val();
			var SKU_LengthUnit 			= $("#updatecbS_SKU_LengthUnit option:selected").val();
			var SKU_Width				= $("#updatetxtS_SKU_Width").val();
			var SKU_WidthUnit			= $("#updatecbS_SKU_WidthUnit option:selected").val();
			var SKU_Height				= $("#updatetxtS_SKU_Height").val();
			var SKU_HeightUnit			= $("#updatecbS_SKU_HeightUnit option:selected").val();
			var SKU_Volume				= $("#updatetxtS_SKU_Volume").val();
			var SKU_VolumeUnit			= $("#updatecbS_SKU_VolumeUnit option:selected").val();
			
			if( SKU_IsActive == true ) 	{ SKU_IsActive = 1;	}
			else					 	{ SKU_IsActive = 0;	}
			
			if( SKU_IsJual == true ) 	{ SKU_IsJual = 1;	}
			else					 	{ SKU_IsJual = 0;	}
			
			arrUpdateSalin = [];
			
			arrUpdateSalin.push({	SKU_Deskripsi: SKU_Deskripsi, KemasanID: KemasanID, SKU_Kondisi: SKU_Kondisi, SKU_Origin: SKU_Origin, SKU_HargaJual: SKU_HargaJual,
									SKU_SalesMinQty: SKU_SalesMinQty, /*SKU_PPh: SKU_PPh, SKU_PPnBM_Persen: SKU_PPnBM_Persen, SKU_PPn_Persen: SKU_PPn_Persen, */
									SKU_IsActive: SKU_IsActive, SKU_IsJual: SKU_IsJual, 
									SKU_WeightNetto: SKU_WeightNetto, SKU_WeightNettoUnit: SKU_WeightNettoUnit, 
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
	
	$("#updatebtnsalinsemua").click
	(
		function()
		{
			$("#updatepreviewsalinsemua").modal('show');
		}
	);
	
	$("#updatebtnyessalinsemua").click
	(
		function()
		{
			var SKU_Deskripsi 			= $("#updatetxtS_SKU_Deskripsi").val();
			var KemasanID	 			= $("#updatecbS_KemasanID option:selected").val();
			var SKU_Kondisi 			= $("#updatecbS_SKU_Kondisi option:selected").val();
			var SKU_Origin 				= $("#updatetxtS_SKU_Origin").val();
			var SKU_HargaJual 			= $("#updatetxtS_SKU_HargaJual").val();
			var SKU_SalesMinQty 		= $("#updatetxtS_SKU_SalesMinQty").val();
			//var SKU_PPh 				= $("#updatecbS_SKU_PPh").val();
			//var SKU_PPnBM_Persen 		= $("#updatetxtS_SKU_PPnBM_Persen").val();
			//var SKU_PPn_Persen			= $("#updatetxtS_SKU_PPn_Persen").val();
			var SKU_IsActive			= $("#updatechS_SKU_IsActive").prop('checked');
			var SKU_IsJual				= 0; //$("#updatechS_SKU_IsJual").prop('checked');
			var SKU_WeightNetto			= $("#updatetxtS_SKU_WeightNetto").val();
			var SKU_WeightNettoUnit		= $("#updatecbS_SKU_WeightNettoUnit option:selected").val();
			var SKU_WeightProduct		= $("#updatetxtS_SKU_WeightProduct").val();
			var SKU_WeightProductUnit	= $("#updatecbS_SKU_WeightProductUnit option:selected").val();
			var SKU_WeightPackaging		= $("#updatetxtS_SKU_WeightPackaging").val();
			var SKU_WeightPackagingUnit	= $("#updatecbS_SKU_WeightPackagingUnit option:selected").val();
			var SKU_WeightGift			= $("#updatetxtS_SKU_WeightGift").val();
			var SKU_WeightGiftUnit		= $("#updatecbS_SKU_WeightGiftUnit option:selected").val();
			var SKU_Length				= $("#updatetxtS_SKU_Length").val();
			var SKU_LengthUnit 			= $("#updatecbS_SKU_LengthUnit option:selected").val();
			var SKU_Width				= $("#updatetxtS_SKU_Width").val();
			var SKU_WidthUnit			= $("#updatecbS_SKU_WidthUnit option:selected").val();
			var SKU_Height				= $("#updatetxtS_SKU_Height").val();
			var SKU_HeightUnit			= $("#updatecbS_SKU_HeightUnit option:selected").val();
			var SKU_Volume				= $("#updatetxtS_SKU_Volume").val();
			var SKU_VolumeUnit			= $("#updatecbS_SKU_VolumeUnit option:selected").val();
			
			var ldatasku = $(".updatetxtSKU_NamaProduk").length;
			
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
					$(".updatetxtSKU_Deskripsi").eq(i).val( SKU_Deskripsi );
					$(".updatecbKemasanID").eq(i).val( KemasanID ).change();
					$(".updatecbSKU_Kondisi").eq(i).val( SKU_Kondisi );
					$(".updatetxtSKU_Origin").eq(i).val( SKU_Origin );
					$(".updatetxtSKU_HargaJual").eq(i).val( SKU_HargaJual );
					$(".updatetxtSKU_SalesMinQty").eq(i).val( SKU_SalesMinQty );
					//$(".updatecbSKU_PPh").eq(i).val( SKU_PPh );
					//$(".updatetxtSKU_PPnBM_Persen").eq(i).val( SKU_PPnBM_Persen );
					//$(".updatetxtSKU_PPn_Persen").eq(i).val( SKU_PPn_Persen );
					
					$(".updatechSKU_IsActive").eq(i).prop('checked', SKU_IsActive );
					$(".updatechSKU_IsJual").eq(i).prop('checked', SKU_IsJual );
					
					$(".updatetxtSKU_WeightNetto").eq(i).val( SKU_WeightNetto );
					$(".updatecbSKU_WeightNettoUnit").eq(i).val( SKU_WeightNettoUnit ).change();
					$(".updatetxtSKU_WeightProduct").eq(i).val( SKU_WeightProduct );
					$(".updatecbSKU_WeightProductUnit").eq(i).val( SKU_WeightProductUnit ).change();
					$(".updatetxtSKU_WeightPackaging").eq(i).val( SKU_WeightPackaging );
					$(".updatecbSKU_WeightPackagingUnit").eq(i).val( SKU_WeightPackagingUnit ).change();
					$(".updatetxtSKU_WeightGift").eq(i).val( SKU_WeightGift );
					$(".updatecbSKU_WeightGiftUnit").eq(i).val( SKU_WeightGiftUnit ).change();
					
					$(".updatetxtSKU_Length").eq(i).val( SKU_Length );
					$(".updatecbSKU_LengthUnit").eq(i).val( SKU_LengthUnit ).change();
					$(".updatetxtSKU_Width").eq(i).val( SKU_Width );
					$(".updatecbSKU_WidthUnit").eq(i).val( SKU_WidthUnit ).change();
					$(".updatetxtSKU_Height").eq(i).val( SKU_Height );
					$(".updatecbSKU_HeightUnit").eq(i).val( SKU_HeightUnit ).change();
					$(".updatetxtSKU_Volume").eq(i).val( SKU_Volume );
					$(".updatecbSKU_VolumeUnit").eq(i).val( SKU_VolumeUnit ).change();
					
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
	
	function UpdateCopas( Idx )
	{
		$("#updatehdIdx").val( Idx );
		
		$("#updatepreviewubahsalinan").modal('show');
	}
	
	$("#updatebtnyesubahsalinan").click
	(
		function()
		{
			var Idx = $("#updatehdIdx").val();
			
			var i = parseInt( Idx ) - 1;
			
			if( arrUpdateSalin.length == 0 )
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
				var SKU_Deskripsi 			= arrUpdateSalin[0].SKU_Deskripsi;
				var KemasanID	 			= arrUpdateSalin[0].KemasanID;
				var SKU_Kondisi 			= arrUpdateSalin[0].SKU_Kondisi;
				var SKU_Origin 				= arrUpdateSalin[0].SKU_Origin;
				var SKU_HargaJual 			= arrUpdateSalin[0].SKU_HargaJual;
				var SKU_SalesMinQty 		= arrUpdateSalin[0].SKU_SalesMinQty;
				//var SKU_PPh 				= arrUpdateSalin[0].SKU_PPh;
				//var SKU_PPnBM_Persen 		= arrUpdateSalin[0].SKU_PPnBM_Persen;
				//var SKU_PPn_Persen			= arrSalin[0].SKU_PPn_Persen;
				var SKU_IsActive			= arrUpdateSalin[0].SKU_IsActive;
				var SKU_IsJual				= 0; //arrUpdateSalin[0].SKU_IsJual;
				
				var SKU_WeightNetto			= arrUpdateSalin[0].SKU_WeightNetto;
				var SKU_WeightNettoUnit		= arrUpdateSalin[0].SKU_WeightNettoUnit;
				var SKU_WeightProduct		= arrUpdateSalin[0].SKU_WeightProduct;
				var SKU_WeightProductUnit	= arrUpdateSalin[0].SKU_WeightProductUnit;
				var SKU_WeightPackaging		= arrUpdateSalin[0].SKU_WeightPackaging;
				var SKU_WeightPackagingUnit	= arrUpdateSalin[0].SKU_WeightPackagingUnit;
				var SKU_WeightGift			= arrUpdateSalin[0].SKU_WeightGift;
				var SKU_WeightGiftUnit		= arrUpdateSalin[0].SKU_WeightGiftUnit;
				
				var SKU_Length				= arrUpdateSalin[0].SKU_Length;
				var SKU_LengthUnit 			= arrUpdateSalin[0].SKU_LengthUnit;
				var SKU_Width				= arrUpdateSalin[0].SKU_Width;
				var SKU_WidthUnit			= arrUpdateSalin[0].SKU_WidthUnit;
				var SKU_Height				= arrUpdateSalin[0].SKU_Height;
				var SKU_HeightUnit			= arrUpdateSalin[0].SKU_HeightUnit;
				var SKU_Volume				= arrUpdateSalin[0].SKU_Volume;
				var SKU_VolumeUnit			= arrUpdateSalin[0].SKU_VolumeUnit;
				
				if( SKU_IsActive == 1 )	{ SKU_IsActive = true;	}
				else					{ SKU_IsActive = false;	}
				
				if( SKU_IsJual == 1 )	{ SKU_IsJual = true;	}
				else					{ SKU_IsJual = false;	}
				
				$(".updatetxtSKU_Deskripsi").eq(i).val( SKU_Deskripsi );
				$(".updatecbKemasanID").eq(i).val( KemasanID ).change();
				$(".updatecbSKU_Kondisi").eq(i).val( SKU_Kondisi );
				$(".updatetxtSKU_Origin").eq(i).val( SKU_Origin );
				$(".updatetxtSKU_HargaJual").eq(i).val( SKU_HargaJual );
				$(".updatetxtSKU_SalesMinQty").eq(i).val( SKU_SalesMinQty );
				//$(".updatecbSKU_PPh").eq(i).val( SKU_PPh );
				//$(".updatetxtSKU_PPnBM_Persen").eq(i).val( SKU_PPnBM_Persen );
				//$(".updatetxtSKU_PPn_Persen").eq(i).val( SKU_PPn_Persen );
				
				$(".updatechSKU_IsActive").eq(i).prop('checked', SKU_IsActive );
				//$(".updatechSKU_IsJual").eq(i).prop('checked', SKU_IsJual );
				
				$(".updatetxtSKU_WeightNetto").eq(i).val( SKU_WeightNetto );
				$(".updatecbSKU_WeightNettoUnit").eq(i).val( SKU_WeightNettoUnit );
				$(".updatetxtSKU_WeightProduct").eq(i).val( SKU_WeightProduct );
				$(".updatecbSKU_WeightProductUnit").eq(i).val( SKU_WeightProductUnit );
				$(".updatetxtSKU_WeightPackaging").eq(i).val( SKU_WeightPackaging );
				$(".updatecbSKU_WeightPackagingUnit").eq(i).val( SKU_WeightPackagingUnit );
				$(".updatetxtSKU_WeightGift").eq(i).val( SKU_WeightGift );
				$(".updatecbSKU_WeightGiftUnit").eq(i).val( SKU_WeightGiftUnit );
				
				$(".updatetxtSKU_Length").eq(i).val( SKU_Length );
				$(".updatecbSKU_LengthUnit").eq(i).val( SKU_LengthUnit );
				$(".updatetxtSKU_Width").eq(i).val( SKU_Width );
				$(".updatecbSKU_WidthUnit").eq(i).val( SKU_WidthUnit );
				$(".updatetxtSKU_Height").eq(i).val( SKU_Height );
				$(".updatecbSKU_HeightUnit").eq(i).val( SKU_HeightUnit );
				$(".updatetxtSKU_Volume").eq(i).val( SKU_Volume );
				$(".updatecbSKU_VolumeUnit").eq(i).val( SKU_VolumeUnit );
			}
		}
	);
	
	function UpdateProses( Mode )
	{	
		$("#updateSKUInduk").removeClass('active');
		$("#updatetab1").removeClass('active');
		
		$("#updateVarianSKU").removeClass('active');
		$("#updatetab2").removeClass('active');
		
		$("#updateDataSKU").removeClass('active');
		$("#updatetab3").removeClass('active');
		
		$("#updateListSKU").removeClass('active');
		$("#updatetab4").removeClass('active');
		
		$(window).scrollTop(0);
		
		if( Mode == 0 )
		{
			$("#updateSKUInduk").addClass('active');
			$("#updatetab1").addClass('active');
			
			if( $.fn.DataTable.isDataTable('#updatetablegeneratedstringsku') ) 
			{
				$('#updatetablegeneratedstringsku').DataTable().destroy();
			}

			$('#updatetablegeneratedstringsku > tbody').empty();
			
		}
		else if( Mode == 1 )
		{
			$("#updateVarianSKU").addClass('active');
			$("#updatetab2").addClass('active');
			
			if( $.fn.DataTable.isDataTable('#updatetablegeneratedstringsku') ) 
			{
				$('#updatetablegeneratedstringsku').DataTable().destroy();
			}

			$('#updatetablegeneratedstringsku > tbody').empty();
			
		}
		else if( Mode == 2 )
		{
			$("#updateDataSKU").addClass('active');
			$("#updatetab3").addClass('active');
		}
		else if( Mode == 3 )
		{
			$("#updateListSKU").addClass('active');
			$("#updatetab4").addClass('active');
		}
		/*
		$("#updateSKUInduk").removeClass('active');
		$("#updatetab1").removeClass('active');
		
		$("#updateKategori").removeClass('active');
		$("#updatetab2").removeClass('active');
		
		$("#updateDataSKU").removeClass('active');
		$("#updatetab3").removeClass('active');
		
		$("#updateKonversiSKU").removeClass('active');
		$("#updatetab4").removeClass('active');
		
		$(window).scrollTop(0);
		
		if( Mode == 1 )
		{
			$("#updateKategori").addClass('active');
			$("#updatetab2").addClass('active');
		}
		else if( Mode == 2 )
		{
			$("#updateDataSKU").addClass('active');
			$("#updatetab3").addClass('active');
		}
		else if( Mode == 3 )
		{
			$("#updateKonversiSKU").addClass('active');
			$("#updatetab4").addClass('active');
		}
		*/
	}
	
</script>