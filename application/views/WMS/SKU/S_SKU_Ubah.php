<script type="text/javascript">	
	arrUpdatesku_bosnet_id = [];
	arrUpdateUbahsku_bosnet_id = [];
	
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
			
			arrUpdatesku_bosnet_id = [];
			
			$("#MainMenu").removeAttr('style');
			$("#anylist_letter").removeAttr('style');
			$("#Ubah").attr('style','display: none;');
		}
	);
	
	$("#updatebtnsimpandatasku").click
	(
		function()
		{
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
			}
			else
			{
				var ldatasku = $(".xupdatetxtsku_nama_produk").length;
				
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
						var sku_id 					= $(".xupdatehdsku_id").eq(i).val();
						var sku_kode 				= $(".xupdatetxtsku_kode").eq(i).val();
						
						var sku_nama_produk 		= $(".xupdatetxtsku_nama_produk").eq(i).val();
						var sku_deskripsi 			= $(".xupdatetxtsku_deskripsi").eq(i).val();
						var sku_kemasan 			= $(".xupdatecbsku_kemasan").eq(i).val();
						var sku_satuan	 			= $(".xupdatecbsku_satuan").eq(i).val();
						var sku_kondisi 			= $(".xupdatecbsku_kondisi").eq(i).val();
						var sku_origin 				= $(".xupdatetxtsku_origin").eq(i).val();
						var sku_harga_jual 			= $(".xupdatetxtsku_harga_jual").eq(i).val();
						var sku_sales_min_qty 		= $(".xupdatetxtsku_sales_min_qty").eq(i).val();
						//var sku_PPh = $(".cbsku_PPh").eq(i).val();
						//var sku_PPnBM_Persen = $(".txtsku_PPnBM_Persen").eq(i).val();
						//var sku_PPn_Persen = $(".txtsku_PPn_Persen").eq(i).val();
						
						var sku_is_aktif = $(".xupdatechsku_is_aktif").eq(i).prop('checked');
						if( sku_is_aktif == true )	{ sku_is_aktif = 1;	}
						else						{ sku_is_aktif = 0;	}
						
						var sku_is_jual = $(".xupdatechsku_is_jual").eq(i).prop('checked');
						if( sku_is_jual == true )	{ sku_is_jual = 1;	}
						else						{ sku_is_jual = 0;	}
						
						var sku_weight_netto		 	= $(".xupdatetxtsku_weight_netto").eq(i).val();
						var sku_weight_netto_unit 	= $(".xupdatecbsku_weight_netto_unit option:selected").eq(i).val();
						var sku_weight_product 		= $(".xupdatetxtsku_weight_product").eq(i).val();
						var sku_weight_product_unit 	= $(".xupdatecbsku_weight_product_unit option:selected").eq(i).val();
						var sku_weight_packaging 	= $(".xupdatetxtsku_weight_packaging").eq(i).val();
						var sku_weight_packaging_unit = $(".xupdatecbsku_weight_packaging_unit option:selected").eq(i).val();
						var sku_weight_gift 			= $(".xupdatetxtsku_weight_gift").eq(i).val();
						var sku_weight_gift_unit 		= $(".xupdatecbsku_weight_gift_unit option:selected").eq(i).val();
						
						var sku_length 				= $(".xupdatetxtsku_length").eq(i).val();
						var sku_length_unit 			= $(".xupdatecbsku_length_unit option:selected").eq(i).val();
						var sku_width 				= $(".xupdatetxtsku_width").eq(i).val();
						var sku_width_unit 			= $(".xupdatecbsku_width_unit option:selected").eq(i).val();
						var sku_height 				= $(".xupdatetxtsku_height").eq(i).val();
						var sku_height_unit 			= $(".xupdatecbsku_height_unit option:selected").eq(i).val();
						var sku_volume 				= $(".xupdatetxtsku_volume").eq(i).val();
						var sku_volume_unit 			= $(".xupdatecbsku_volume_unit option:selected").eq(i).val();
						
						arrSKU.push({	sku_id: sku_id, sku_kode: sku_kode, sku_nama_produk: sku_nama_produk, sku_deskripsi: sku_deskripsi, sku_kemasan: sku_kemasan, sku_satuan: sku_satuan, sku_kondisi: sku_kondisi, sku_origin: sku_origin, sku_harga_jual: sku_harga_jual,
										sku_sales_min_qty: sku_sales_min_qty, /*sku_PPh: sku_PPh, sku_PPnBM_Persen: sku_PPnBM_Persen, sku_PPn_Persen: sku_PPn_Persen, */
										sku_is_aktif: sku_is_aktif, sku_is_jual: sku_is_jual, 
										sku_weight_netto: sku_weight_netto, sku_weight_netto_unit: sku_weight_netto_unit, 
										sku_weight_product: sku_weight_product, sku_weight_product_unit: sku_weight_product_unit, 
										sku_weight_packaging: sku_weight_packaging, sku_weight_packaging_unit: sku_weight_packaging_unit, 
										sku_weight_gift: sku_weight_gift, sku_weight_gift_unit: sku_weight_gift_unit, 
										sku_length: sku_length, sku_length_unit: sku_length_unit, sku_width: sku_width, sku_width_unit: sku_width_unit, 
										sku_height: sku_height, sku_height_unit: sku_height_unit, sku_volume: sku_volume, sku_volume_unit: sku_volume_unit });
					
					}
					
					var sku_induk_jenis_ppn 			= $("#updatecbsku_induk_jenis_ppn option:selected").val();
					var sku_induk_ppn_persen 		= $("#updatetxtsku_induk_ppn_persen").val();
					var sku_induk_ppnbm_persen 		= $("#updatetxtsku_induk_ppnbm_persen").val();
					
					var sku_induk_id 				= $("#updatecbsku_induk option:selected").val();
					var sku_induk_keterangan			= $("#updatetxaskuinduk_ket").val();
		
					if( kategori1_id == '' ){	 kategori1_id = '0';	}
					if( kategori2_id == '' ){	 kategori2_id = '0';	}
					if( kategori3_id == '' ){	 kategori3_id = '0';	}
					if( kategori4_id == '' ){	 kategori4_id = '0';	}
					if( principle_id == '' ){	 principle_id = '0';	}
					if( principle_brand_id == '' ){	 principle_brand_id = '0';	}
					
					$.ajax(
					{
						type: 'POST',    
						url: "<?= base_url('SKU/UpdateSaveData') ?>",
						data:
						{
							sku_induk_id 				: sku_induk_id,
							sku_induk_keterangan 		: sku_induk_keterangan,
							kategori1_id				: kategori1_id,
							kategori2_id				: kategori2_id,
							kategori3_id				: kategori3_id,
							kategori4_id				: kategori4_id,
							
							principle_id				: principle_id,
							principle_brand_id				: principle_brand_id,
							
							sku_induk_jenis_ppn			: sku_induk_jenis_ppn,
							sku_induk_ppn_persen			: sku_induk_ppn_persen,
							sku_induk_ppnbm_persen		: sku_induk_ppnbm_persen,
							
							arrSKU 						: arrSKU,
							
							arrsku_bosnet_id				: arrUpdatesku_bosnet_id
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
								$("#anylist_letter").removeAttr('style');
								$("#Ubah").attr('style','display: none;');
								
								arrUpdateUbahsku_bosnet_id = [];
								
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
			$("#updatebtnsimpandataubah").prop('disabled', true );

			var arrSKUKembar = [];
					
			var lsku = $(".updatetxtsku_nama_produk").length;
			for( i=0 ; i<lsku ; i++ )
			{
				var sku_nama_produk = $(".updatetxtsku_nama_produk").eq(i).val();
				
				arrSKUKembar.push( sku_nama_produk ); 
			}
			
			var isdupe = 0;
			
			var lxxsku = $(".xxupdatetxtsku_nama_produk").length;
			for( i=0 ; i<lxxsku ; i++ )
			{
				var sku_nama_produk = $(".xxupdatetxtsku_nama_produk").eq(i).val();
				
				if( arrSKUKembar.includes( sku_nama_produk ) == true )
				{
					isdupe = 1;
				}
			}
			
			if( isdupe == 1 )
			{
				var msg = 'Kombinasi Varian Kembar ditemukan.';
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
				
				$("#updatebtnsimpandataubah").prop('disabled', false );
			
				return false;
			}
			
			var sku_induk_keterangan = $("#updatetxaskuinduk_ket").val();
			
			var kategori1_id = $("#updatehdkategori1").val();
			var kategori2_id = $("#updatehdkategori2").val();
			var kategori3_id = $("#updatehdkategori3").val();
			var kategori4_id = $("#updatehdkategori4").val();

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
				
				$("#updatebtnsimpandataubah").prop('disabled', false );
			}
			else
			{
				var ldatasku = $(".updatetxtsku_nama_produk").length;
				
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
					
					$("#updatebtnsimpandataubah").prop('disabled', false );
				}
				else
				{
					var arrSKU = [];
					
					for( i=0 ; i<ldatasku ; i++ )
					{
						var nama1 = $(".updatehdnama1").eq(i).val();
						var nama2 = $(".updatehdnama2").eq(i).val();
						var nama3 = $(".updatehdnama3").eq(i).val();
						var nama4 = $(".updatehdnama4").eq(i).val();
						var nama5 = $(".updatehdnama5").eq(i).val();
						var unit = $(".updatehdunit").eq(i).val();
						
						var sku_kode 				= $(".updatetxtsku_kode").eq(i).val();
						var sku_nama_produk 		= $(".updatetxtsku_nama_produk").eq(i).val();
						var sku_deskripsi 			= $(".updatetxtsku_deskripsi").eq(i).val();
						var sku_kemasan 			= $(".updatetxtsku_kemasan").eq(i).val();
						var sku_satuan	 			= $(".updatetxtsku_satuan").eq(i).val();
						var sku_kondisi 			= $(".updatecbsku_kondisi").eq(i).val();
						var sku_origin 				= $(".updatetxtsku_origin").eq(i).val();
						var sku_harga_jual 			= $(".updatetxtsku_harga_jual").eq(i).val();
						var sku_sales_min_qty 		= $(".updatetxtsku_sales_min_qty").eq(i).val();
						//var sku_PPh = $(".cbsku_PPh").eq(i).val();
						//var sku_PPnBM_Persen = $(".txtsku_PPnBM_Persen").eq(i).val();
						//var sku_PPn_Persen = $(".txtsku_PPn_Persen").eq(i).val();
						
						var sku_is_aktif = $(".updatechsku_is_aktif").eq(i).prop('checked');
						if( sku_is_aktif == true )	{ sku_is_aktif = 1;	}
						else						{ sku_is_aktif = 0;	}
						
						var sku_is_jual = $(".updatechsku_is_jual").eq(i).prop('checked');
						if( sku_is_jual == true )	{ sku_is_jual = 1;	}
						else						{ sku_is_jual = 0;	}
						
						var sku_weight_netto		 	= $(".updatetxtsku_weight_netto").eq(i).val();
						var sku_weight_netto_unit 		= $(".updatecbsku_weight_netto_unit option:selected").eq(i).val();
						var sku_weight_product 			= $(".updatetxtsku_weight_product").eq(i).val();
						var sku_weight_product_unit 	= $(".updatecbsku_weight_product_unit option:selected").eq(i).val();
						var sku_weight_packaging 		= $(".updatetxtsku_weight_packaging").eq(i).val();
						var sku_weight_packaging_unit	= $(".updatecbsku_weight_packaging_unit option:selected").eq(i).val();
						var sku_weight_gift 			= $(".updatetxtsku_weight_gift").eq(i).val();
						var sku_weight_gift_unit 		= $(".updatecbsku_weight_gift_unit option:selected").eq(i).val();
						
						var sku_length 					= $(".updatetxtsku_length").eq(i).val();
						var sku_length_unit 			= $(".updatecbsku_length_unit option:selected").eq(i).val();
						var sku_width 					= $(".updatetxtsku_width").eq(i).val();
						var sku_width_unit 				= $(".updatecbsku_width_unit option:selected").eq(i).val();
						var sku_height 					= $(".updatetxtsku_height").eq(i).val();
						var sku_height_unit 			= $(".updatecbsku_height_unit option:selected").eq(i).val();
						var sku_volume 					= $(".updatetxtsku_volume").eq(i).val();
						var sku_volume_unit 			= $(".updatecbsku_volume_unit option:selected").eq(i).val();
					
						arrSKU.push({	nama1: nama1, nama2: nama2, nama3: nama3, nama4: nama4, nama5: nama5, unit: unit, 
										sku_kode: sku_kode, sku_nama_produk: sku_nama_produk, sku_deskripsi: sku_deskripsi, sku_kemasan: sku_kemasan, sku_satuan: sku_satuan, sku_kondisi: sku_kondisi, sku_origin: sku_origin, sku_harga_jual: sku_harga_jual,
										sku_sales_min_qty: sku_sales_min_qty, 
										sku_is_aktif: sku_is_aktif, sku_is_jual: sku_is_jual, 
										sku_weight_netto: sku_weight_netto, sku_weight_netto_unit: sku_weight_netto_unit, 
										sku_weight_product: sku_weight_product, sku_weight_product_unit: sku_weight_product_unit, 
										sku_weight_packaging: sku_weight_packaging, sku_weight_packaging_unit: sku_weight_packaging_unit, 
										sku_weight_gift: sku_weight_gift, sku_weight_gift_unit: sku_weight_gift_unit, 
										sku_length: sku_length, sku_length_unit: sku_length_unit, sku_width: sku_width, sku_width_unit: sku_width_unit, 
										sku_height: sku_height, sku_height_unit: sku_height_unit, sku_volume: sku_volume, sku_volume_unit: sku_volume_unit });
					
					}
					
					var sku_induk_jenis_ppn = $("#updatecbsku_induk_jenis_ppn option:selected").val();
					var sku_induk_ppn_persen = $("#updatetxtsku_induk_ppn_persen").val();
					var sku_induk_ppnbm_persen = $("#updatetxtsku_induk_ppnbm_persen").val();
					
					var sku_induk_id = $("#updatecbsku_induk option:selected").val();
		
					if( kategori1_id == '' ){	 kategori1_id = '0';	}
					if( kategori2_id == '' ){	 kategori2_id = '0';	}
					if( kategori3_id == '' ){	 kategori3_id = '0';	}
					if( kategori4_id == '' ){	 kategori4_id = '0';	}
					if( principle_id == '' ){	 principle_id = '0';	}
					if( principle_brand_id == '' ){	 principle_brand_id = '0';	}
					
					$.ajax(
					{
						type: 'POST',    
						url: "<?= base_url('SKU/UpdateSaveDataUbah') ?>",
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
							arrDataVarianWajibDetail 	: arrUpdateDataVarianWajibDetail,
							arrDataVarianDetail 		: arrUpdateDataVarianDetail
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
								$("#anylist_letter").removeAttr('style');
								
								$("#Ubah").attr('style','display: none;');

								$("#updatebtnsimpandataubah").prop('disabled', false );
			
								GetSKUIndukMenu();
							}
						}
					});	
					
				}
			}
		}
	);
	
	function UbahSKUInduk( sku_induk_id, sku_induk_nama )
	{
		$("#MainMenu").attr('style','display: none;');
		$("#anylist_letter").attr('style','display: none;');
		$("#Ubah").removeAttr('style');
		
		$.ajax(
		{
			type: 'POST',    
			url: "<?= base_url('SKU/GetUpdateSKUMenu') ?>",
			data: 
			{
				sku_induk_id : sku_induk_id
			},
			success: function( response )
			{
				if(response)
				{
					ChUpdateSKUIndukMenu( response, sku_induk_id, sku_induk_nama );
				}
			}
		});	
	}
	
	function ChUpdateSKUIndukMenu( JSONSKU, sku_induk_id, sku_induk_nama )
	{
		$("#updatecbkategori1").html('');
		$("#updatecbkategori2").html('');
		$("#updatecbkategori3").html('');
		$("#updatecbkategori4").html('');
		
		$("#updatecbkategori1").html('<option value="">--Pilih Kategori--</option>');
		$("#updatecbkategori2").html('<option value="">--Pilih Sub Kategori--</option>');
		$("#updatecbkategori3").html('<option value="">--Pilih Sub Kategori 2--</option>');
		$("#updatecbkategori4").html('<option value="">--Pilih Sub Kategori 3--</option>');	
		
		$("#updatecbsku_induk").html('<option value="'+ sku_induk_id +'">'+ sku_induk_nama +'</option>');
		$("#updatecbsku_induk").prop('disabled', true );
		
				
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
			
			var strsku_PPh = '';
			strsku_PPh = strsku_PPh + '<option value="1">Include</option>';
			strsku_PPh = strsku_PPh + '<option value="2">Exclude</option>';
			strsku_PPh = strsku_PPh + '<option value="3">None</option>';
			
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
				var satuan_kode = arrSatuan[i].satuan_kode;
				var satuan_nama = arrSatuan[i].satuan_nama;
				var satuan_grup	= arrSatuan[i].satuan_grup;
				
				if		( satuan_grup == 'Panjang')	{	strSatuanPanjang 	= strSatuanPanjang + '<option value="'+ satuan_nama +'">'+ satuan_nama +'</option>';	}
				else if	( satuan_grup == 'Lebar')	{	strSatuanLebar 		= strSatuanLebar + '<option value="'+ satuan_nama +'">'+ satuan_nama +'</option>';	}
				else if	( satuan_grup == 'Tinggi')	{	strSatuanTinggi 	= strSatuanTinggi + '<option value="'+ satuan_nama +'">'+ satuan_nama +'</option>';	}
				else if	( satuan_grup == 'Volume')	{	strSatuanVolume 	= strSatuanVolume + '<option value="'+ satuan_nama +'">'+ satuan_nama +'</option>';	}
				else if	( satuan_grup == 'Berat')	{	strSatuanBerat 		= strSatuanBerat + '<option value="'+ satuan_nama +'">'+ satuan_nama +'</option>';	}
			}
			/*
			var strKemasan = '';
			
			for( i=0 ; i< arrKemasan.length ; i++ )
			{
				var kemasan_id 	= arrKemasan[i].kemasan_id;
				var KemasanKode = arrKemasan[i].KemasanKode;
				var kemasan_nama	= arrKemasan[i].kemasan_nama;
			
				strKemasan = strKemasan + '<option value="'+ kemasan_id +'">'+ kemasan_nama +'</option>';
			}
			*/
			for( i=0 ; i< SKU.SKUMenu.length ; i++ )
			{
				var sku_id 						= SKU.SKUMenu[i].sku_id;
				var sku_nama_produk 			= SKU.SKUMenu[i].sku_nama_produk;
				var sku_bosnet_id 				= SKU.SKUMenu[i].sku_bosnet_id;
				var sku_kode 					= SKU.SKUMenu[i].sku_kode;
				
				var sku_deskripsi				= SKU.SKUMenu[i].sku_deskripsi;
				var sku_kemasan					= SKU.SKUMenu[i].kemasan_nama;
				var sku_satuan					= SKU.SKUMenu[i].sku_satuan;
				var sku_kondisi					= SKU.SKUMenu[i].sku_kondisi;
				var sku_origin					= SKU.SKUMenu[i].sku_origin;
				var sku_harga_jual				= SKU.SKUMenu[i].sku_harga_jual;
				var sku_sales_min_qty			= SKU.SKUMenu[i].sku_sales_min_qty;
				var sku_is_aktif				= SKU.SKUMenu[i].sku_is_aktif;
				var sku_is_jual					= SKU.SKUMenu[i].sku_is_jual;
				var sku_weight_netto			= SKU.SKUMenu[i].sku_weight_netto;
				var sku_weight_netto_unit		= SKU.SKUMenu[i].sku_weight_netto_unit;
				var sku_weight_product			= SKU.SKUMenu[i].sku_weight_product;
				var sku_weight_product_unit		= SKU.SKUMenu[i].sku_weight_product_unit;
				var sku_weight_packaging		= SKU.SKUMenu[i].sku_weight_packaging;
				var sku_weight_packaging_unit	= SKU.SKUMenu[i].sku_weight_packaging_unit;
				var sku_weight_gift				= SKU.SKUMenu[i].sku_weight_gift;
				var sku_weight_gift_unit		= SKU.SKUMenu[i].sku_weight_gift_unit;
				var sku_length					= SKU.SKUMenu[i].sku_length;
				var sku_length_unit				= SKU.SKUMenu[i].sku_length_unit;
				var sku_width					= SKU.SKUMenu[i].sku_width;
				var sku_width_unit				= SKU.SKUMenu[i].sku_width_unit;
				var sku_height					= SKU.SKUMenu[i].sku_height;
				var sku_height_unit				= SKU.SKUMenu[i].sku_height_unit;
				var sku_volume					= SKU.SKUMenu[i].sku_volume;
				var sku_volume_unit				= SKU.SKUMenu[i].sku_volume_unit;
								
				var str = '';
				
				var checkedIsActive = '';
				if( sku_is_aktif == 1 )	{	checkedIsActive = 'checked';	}
				
				var checkedIsJual = '';
				if( sku_is_jual == 1 )	{	checkedIsJual = 'checked';	}
				
				var RandBosnetID = Math.random() * 1000000000000000000;
				
				str = str + '<tr>';
				str = str + '	<input type="hidden" class="xupdatehdsku_id" value="'+ sku_id +'" />';							
				str = str + '	<input type="hidden" class="xupdatetxtsku_kode" value="'+ sku_kode +'" />';							
				str = str + '	<td><input type="text" style="width: 500px;" class="xupdatetxtsku_nama_produk form-control" disabled="disabled" value="'+ sku_nama_produk +'" /></td>';
				str = str + '	<td><button type="button" id="xbtnviewsku_bosnet_id" class="btn btn-primary xbtnviewsku_bosnet_id" onclick="UpdateViewBosnet(\''+ RandBosnetID +'\',\''+ sku_id +'\',\''+ sku_nama_produk +'\')" ><i class="fa fa-pencil"></i> Isi Bosnet ID</button></td>';
				str = str + '	<td><input type="text" class="xupdatetxtsku_deskripsi form-control" value="'+ sku_deskripsi +'" /></td>';
				str = str + '	<td><input type="text" class="xupdatetxtsku_kemasan form-control" value="'+ sku_kemasan +'" disabled="disabled" /></td>';
				str = str + '	<td><input type="text" class="xupdatetxtsku_satuan form-control" value="'+ sku_satuan +'" disabled="disabled" /></td>';
				str = str + '	<td><select class="xupdatecbsku_kondisi form-control">'+ strsku_kondisi +'</select></td>';
				str = str + '	<td><input type="text" class="xupdatetxtsku_origin form-control" value="'+ sku_origin +'" /></td>';
				str = str + '	<td><input type="number" class="xupdatetxtsku_harga_jual form-control" value="'+ sku_harga_jual +'" /></td>';
				str = str + '	<td><input type="number" class="xupdatetxtsku_sales_min_qty form-control" value="'+ sku_sales_min_qty +'" /></td>';
				//str = str + '	<td><select class="updatecbsku_PPh form-control">'+ strsku_PPh +'</select></td>';
				//str = str + '	<td><input type="number" class="updatetxtsku_PPnBM_Persen form-control" value="'+ sku_PPnBM_Persen +'" /></td>';
				//str = str + '	<td><input type="number" class="updatetxtsku_PPn_Persen form-control" value="'+ sku_PPn_Persen +'" /></td>';
				str = str + '	<td><input type="checkbox" class="checkbox_form xupdatechsku_is_aktif" '+ checkedIsActive +' /></td>';
				str = str + '	<td><input type="checkbox" class="checkbox_form xupdatechsku_is_jual" '+ checkedIsJual +' /></td>';
				str = str + '	<td><input type="number" class="xupdatetxtsku_weight_netto form-control" value="'+ sku_weight_netto +'" /></td>';
				str = str + '	<td><select class="xupdatecbsku_weight_netto_unit form-control">'+ strSatuanBerat +'</select></td>';
				str = str + '	<td><input type="number" class="xupdatetxtsku_weight_product form-control" value="'+ sku_weight_product +'" /></td>';
				str = str + '	<td><select class="xupdatecbsku_weight_product_unit form-control">'+ strSatuanBerat +'</select></td>';
				str = str + '	<td><input type="number" class="xupdatetxtsku_weight_packaging form-control" value="'+ sku_weight_packaging +'" /></td>';
				str = str + '	<td><select class="xupdatecbsku_weight_packaging_unit form-control">'+ strSatuanBerat +'</select></td>';
				str = str + '	<td><input type="number" class="xupdatetxtsku_weight_gift form-control" value="'+ sku_weight_gift +'" /></td>';
				str = str + '	<td><select class="xupdatecbsku_weight_gift_unit form-control">'+ strSatuanBerat +'</select></td>';
				str = str + '	<td><input type="number" class="xupdatetxtsku_length form-control" value="'+ sku_length +'" /></td>';
				str = str + '	<td><select class="xupdatecbsku_length_unit form-control">'+ strSatuanPanjang +'</select></td>';
				str = str + '	<td><input type="number" class="xupdatetxtsku_width form-control" value="'+ sku_width +'" /></td>';
				str = str + '	<td><select class="xupdatecbsku_width_unit form-control">'+ strSatuanLebar +'</select></td>';
				str = str + '	<td><input type="number" class="xupdatetxtsku_height form-control" value="'+ sku_height +'" /></td>';
				str = str + '	<td><select class="xupdatecbsku_height_unit form-control">'+ strSatuanTinggi +'</select></td>';
				str = str + '	<td><input type="number" class="xupdatetxtsku_volume form-control" value="'+ sku_volume +'" /></td>';
				str = str + '	<td><select class="xupdatecbsku_volume_unit form-control">'+ strSatuanVolume +'</select></td>';
				str = str + '</tr>';
				
				$("#updatetabledatasku > tbody").append( str );

				
				//$(".xupdatecbkemasan_id").eq(i).val( kemasan_id );
				$(".xupdatecbsku_kondisi").eq(i).val( sku_kondisi ).change();
				//$(".updatecbsku_PPh").eq(i).val( sku_PPh );
				$(".xupdatecbsku_weight_netto_unit").eq(i).val( sku_weight_netto_unit ).change();
				$(".xupdatecbsku_weight_product_unit").eq(i).val( sku_weight_product_unit ).change();
				$(".xupdatecbsku_weight_packaging_unit").eq(i).val( sku_weight_packaging_unit ).change();
				$(".xupdatecbsku_weight_gift_unit").eq(i).val( sku_weight_gift_unit ).change();
				
				$(".xupdatecbsku_length_unit").eq(i).val( sku_length_unit ).change();
				$(".xupdatecbsku_width_unit").eq(i).val( sku_width_unit ).change();
				$(".xupdatecbsku_height_unit").eq(i).val( sku_height_unit ).change();
				$(".xupdatecbsku_volume_unit").eq(i).val( sku_volume_unit ).change();
				
				var struu = '';
				struu = struu + '<tr>';
				struu = struu + '	<input type="hidden" class="xxupdatehdsku_id" value="'+ sku_id +'" />';							
				struu = struu + '	<input type="hidden" class="xxupdatetxtsku_kode" value="'+ sku_kode +'" />';							
				struu = struu + '	<td><input type="text" style="width: 500px;" class="xxupdatetxtsku_nama_produk form-control" disabled="disabled" value="'+ sku_nama_produk +'" /></td>';
				struu = struu + '	<td><button type="button" id="xxbtnviewsku_bosnet_id" class="btn btn-primary xxbtnviewsku_bosnet_id" onclick="UpdateLihatViewBosnet(\''+ RandBosnetID +'\',\''+ sku_id +'\',\''+ sku_nama_produk +'\')" ><i class="fa fa-eye"></i> Lihat Bosnet ID</button></td>';
				
				struu = struu + '	<td><input type="checkbox" class="checkbox_form xxupdatechsku_is_aktif" '+ checkedIsActive +' /></td>';
				struu = struu + '	<td><input type="checkbox" class="checkbox_form xxupdatechsku_is_jual" '+ checkedIsJual +' /></td>';
				struu = struu + '	<td><input type="text" class="xxupdatetxtsku_deskripsi form-control" value="'+ sku_deskripsi +'" disabled="disabled" /></td>';
				struu = struu + '	<td><input type="text" class="xxupdatetxtsku_kemasan form-control" value="'+ sku_kemasan +'" disabled="disabled" /></td>';
				struu = struu + '	<td><input type="text" class="xxupdatetxtsku_satuan form-control" value="'+ sku_satuan +'" disabled="disabled" /></td>';
				struu = struu + '	<td><select class="xxupdatecbsku_kondisi form-control" disabled="disabled">'+ strsku_kondisi +'</select></td>';
				struu = struu + '	<td><input type="text" class="xxupdatetxtsku_origin form-control" value="'+ sku_origin +'" disabled="disabled" /></td>';
				struu = struu + '	<td><input type="number" class="xxupdatetxtsku_harga_jual form-control" value="'+ sku_harga_jual +'" disabled="disabled" /></td>';
				struu = struu + '	<td><input type="number" class="xxupdatetxtsku_sales_min_qty form-control" value="'+ sku_sales_min_qty +'" disabled="disabled" /></td>';
				struu = struu + '	<td><input type="number" class="xxupdatetxtsku_weight_netto form-control" value="'+ sku_weight_netto +'" disabled="disabled" /></td>';
				struu = struu + '	<td><select class="xxupdatecbsku_weight_netto_unit form-control" disabled="disabled">'+ strSatuanBerat +'</select></td>';
				struu = struu + '	<td><input type="number" class="xxupdatetxtsku_weight_product form-control" value="'+ sku_weight_product +'" disabled="disabled" /></td>';
				struu = struu + '	<td><select class="xxupdatecbsku_weight_product_unit form-control" disabled="disabled">'+ strSatuanBerat +'</select></td>';
				struu = struu + '	<td><input type="number" class="xxupdatetxtsku_weight_packaging form-control" value="'+ sku_weight_packaging +'" disabled="disabled" /></td>';
				struu = struu + '	<td><select class="xxupdatecbsku_weight_packaging_unit form-control" disabled="disabled">'+ strSatuanBerat +'</select></td>';
				struu = struu + '	<td><input type="number" class="xxupdatetxtsku_weight_gift form-control" value="'+ sku_weight_gift +'" disabled="disabled" /></td>';
				struu = struu + '	<td><select class="xxupdatecbsku_weight_gift_unit form-control" disabled="disabled">'+ strSatuanBerat +'</select></td>';
				struu = struu + '	<td><input type="number" class="xxupdatetxtsku_length form-control" value="'+ sku_length +'" disabled="disabled" /></td>';
				struu = struu + '	<td><select class="xxupdatecbsku_length_unit form-control" disabled="disabled">'+ strSatuanPanjang +'</select></td>';
				struu = struu + '	<td><input type="number" class="xxupdatetxtsku_width form-control" value="'+ sku_width +'" disabled="disabled" /></td>';
				struu = struu + '	<td><select class="xxupdatecbsku_width_unit form-control" disabled="disabled">'+ strSatuanLebar +'</select></td>';
				struu = struu + '	<td><input type="number" class="xxupdatetxtsku_height form-control" value="'+ sku_height +'" disabled="disabled" /></td>';
				struu = struu + '	<td><select class="xxupdatecbsku_height_unit form-control" disabled="disabled">'+ strSatuanTinggi +'</select></td>';
				struu = struu + '	<td><input type="number" class="xxupdatetxtsku_volume form-control" value="'+ sku_volume +'" disabled="disabled" /></td>';
				struu = struu + '	<td><select class="xxupdatecbsku_volume_unit form-control" disabled="disabled">'+ strSatuanVolume +'</select></td>';
				struu = struu + '</tr>';
				$("#updateubahtabledatasku > tbody").append( struu );

				//$(".xupdatecbkemasan_id").eq(i).val( kemasan_id );
				$(".xxupdatecbsku_kondisi").eq(i).val( sku_kondisi ).change();
				//$(".updatecbsku_PPh").eq(i).val( sku_PPh );
				$(".xxupdatecbsku_weight_netto_unit").eq(i).val( sku_weight_netto_unit ).change();
				$(".xxupdatecbsku_weight_product_unit").eq(i).val( sku_weight_product_unit ).change();
				$(".xxupdatecbsku_weight_packaging_unit").eq(i).val( sku_weight_packaging_unit ).change();
				$(".xxupdatecbsku_weight_gift_unit").eq(i).val( sku_weight_gift_unit ).change();
				
				$(".xxupdatecbsku_length_unit").eq(i).val( sku_length_unit ).change();
				$(".xxupdatecbsku_width_unit").eq(i).val( sku_width_unit ).change();
				$(".xxupdatecbsku_height_unit").eq(i).val( sku_height_unit ).change();
				$(".xxupdatecbsku_volume_unit").eq(i).val( sku_volume_unit ).change();
			}
			
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
			
			$('#updatetabledatasku').DataTable(
			{
				retrieve: true,
				initComplete: function (settings, json) 
				{  
					$("#updatetabledatasku").wrap("<div style='overflow:auto; width:100%; position:relative;'></div>");            
				},
				"dom": '<"left">rtp'
			});
		}
		/*
		if( SKU.Kategori1Menu != 0 )
		{
			for( i=0 ; i< SKU.Kategori1Menu.length ; i++ )
			{
				var kategori1_id = SKU.Kategori1Menu[i].kategori1_id;
				var kategori1_kode = SKU.Kategori1Menu[i].kategori1_kode;
				var kategori1_nama = SKU.Kategori1Menu[i].kategori1_nama;
				
				$("#updatecbkategori1").append( '<option value="'+ kategori1_id +'">'+ kategori1_nama +'</option>' );	
			}
		}
		
		if( SKU.Kategori2Menu != 0 )
		{
			for( i=0 ; i< SKU.Kategori2Menu.length ; i++ )
			{
				var kategori2_id = SKU.Kategori2Menu[i].kategori2_id;
				var kategori2_kode = SKU.Kategori2Menu[i].kategori2_kode;
				var kategori2_nama = SKU.Kategori2Menu[i].kategori2_nama;
			
				$("#updatecbkategori2").append( '<option value="'+ kategori2_id +'">'+ kategori2_nama +'</option>' );	
			}
			
		}
		
		if( SKU.Kategori3Menu != 0 )
		{
			for( i=0 ; i< SKU.Kategori3Menu.length ; i++ )
			{
				var kategori3_id = SKU.Kategori3Menu[i].kategori3_id;
				var kategori3_kode = SKU.Kategori3Menu[i].kategori3_kode;
				var kategori3_nama = SKU.Kategori3Menu[i].kategori3_nama;

				$("#updatecbkategori3").append( '<option value="'+ kategori3_id +'">'+ kategori3_nama +'</option>' );	
			}
		}
		
		if( SKU.Kategori4Menu != 0 )
		{
			for( i=0 ; i< SKU.Kategori4Menu.length ; i++ )
			{
				var kategori4_id = SKU.Kategori4Menu[i].kategori4_id;
				var kategori4_kode = SKU.Kategori4Menu[i].kategori4_kode;
				var kategori4_nama = SKU.Kategori4Menu[i].kategori4_nama;

				$("#updatecbkategori4").append( '<option value="'+ kategori4_id +'">'+ kategori4_nama +'</option>' );	
			}
		}
		
		alert( JSON.stringify( SKU.PrincipleMenu ) );
		if( SKU.PrincipleMenu != 0 )
		{
			for( i=0 ; i< SKU.PrincipleMenu.length ; i++ )
			{
				var principle_id 	= SKU.PrincipleMenu[i].principle_id;
				var principle_nama 	= SKU.PrincipleMenu[i].principle_nama;

				$("#updatecbprinciple").append( '<option value="'+ principle_id +'">'+ principle_nama +'</option>' );	
			}
		}
		
		if( SKU.PrincipleBrandMenu != 0 )
		{
			for( i=0 ; i< SKU.PrincipleBrandMenu.length ; i++ )
			{
				var principle_brand_id 		= SKU.PrincipleBrandMenu[i].principle_brand_id;
				var principle_brand_nama 	= SKU.PrincipleBrandMenu[i].principle_brand_nama;

				$("#updatecbprinciplebrand").append( '<option value="'+ principle_brand_id +'">'+ principle_brand_nama +'</option>' );	
			}
		}
		*/
		if( SKU.KategoriMenu != 0 )
		{
			var sku_induk_keterangan = SKU.KategoriMenu[0].sku_induk_keterangan;
			
			var kategori1_id = SKU.KategoriMenu[0].kategori1_id;
			var kategori2_id = SKU.KategoriMenu[0].kategori2_id;
			var kategori3_id = SKU.KategoriMenu[0].kategori3_id;
			var kategori4_id = SKU.KategoriMenu[0].kategori4_id;
			
			var kategori1_nama = SKU.KategoriMenu[0].kategori1_nama;
			var kategori2_nama = SKU.KategoriMenu[0].kategori2_nama;
			var kategori3_nama = SKU.KategoriMenu[0].kategori3_nama;
			var kategori4_nama = SKU.KategoriMenu[0].kategori4_nama;
			
			if( kategori1_id != null )	
			{ 
				$("#updatekat1").removeAttr('style');	
			}
			
			$("#updatehdkategori1").val( kategori1_id );
			$("#updatehdkategori2").val( kategori2_id );
			$("#updatehdkategori3").val( kategori3_id );
			$("#updatehdkategori4").val( kategori4_id );
	
			$("#updatecbkategori1").val( kategori1_nama );
			
			if( kategori2_id != null )	
			{ 
				$("#updatekat2").removeAttr('style');		
			}
			
			$("#updatecbkategori2").val( kategori2_nama );
			
			if( kategori3_id != null )	
			{ 
				$("#updatekat3").removeAttr('style');	
			}
			
			$("#updatecbkategori3").val( kategori3_nama );
			
			if( kategori4_id != null )	
			{ 
				$("#updatekat4").removeAttr('style');	
			}
			
			$("#updatecbkategori4").val( kategori4_nama );
			
			for( i=1 ; i<=10 ; i++ )
			{
				//$("#updatecbkategori"+ i).css("width", "100%");
				//$("#updatecbkategori"+ i).select2({
				//	"theme": "bootstrap", "val": ""
				//});
			}
			
			$("#updatetxaskuinduk_ket").val( sku_induk_keterangan );
			
			$("#updatetxaskuinduk_ket").trigger('keydown');
			$("#updatetxaskuinduk_ket").trigger('keyup');
		}
		
		$("#updatecbprinciple").val( SKU.PrincipleMenu.principle_id );
		$("#updatecbprinciplebrand").val( SKU.PrincipleBrandMenu.principle_brand_id );
			
		arrUpdatesku_bosnet_id = [];
		if( SKU.SKUBosnetMenu != 0 )
		{
			for( i=0 ; i< SKU.SKUBosnetMenu.length ; i++ )
			{
				var sku_bosnet_id 	= SKU.SKUBosnetMenu[i].sku_bosnet_id;
				var sku_id 			= SKU.SKUBosnetMenu[i].sku_id;
				var sku_induk_id 	= SKU.SKUBosnetMenu[i].sku_induk_id;
				var Bosnet_ID 		= SKU.SKUBosnetMenu[i].Bosnet_ID;

				arrUpdatesku_bosnet_id.push( { sku_bosnet_id : sku_bosnet_id, sku_id : sku_id, sku_induk_id : sku_induk_id, Bosnet_ID : Bosnet_ID } );
			}
		}
		
		strdatavarian = '';
		strdatavariandetail = '';
		
		$("#updatedivvarianwajib").html('');
		$("#updatedivvarian").html('');
		
		
		$('#updatetablegeneratedstringsku').DataTable(
		{
			//scrollX: "100%",
			paging: false,
			retrieve: true,
			initComplete: function (settings, json) 
			{  
				$("#updatetablegeneratedstringsku").wrap("<div style='overflow:auto; width:100%; height: 400px; position:relative;'></div>");            
			},
			"dom": '<"left"f>rt'
		});
		
		$(".updatecbkemasan_id").css("width", "100%");
		$(".updatecbkemasan_id").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updatecbsku_weight_netto_unit").css("width", "100%");
		$(".updatecbsku_weight_netto_unit").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updateupdatecbsku_weight_product_unit").css("width", "100%");
		$(".updateupdatecbsku_weight_product_unit").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updatecbsku_weight_packaging_unit").css("width", "100%");
		$(".updatecbsku_weight_packaging_unit").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updatecbsku_weight_gift_unit").css("width", "100%");
		$(".updatecbsku_weight_gift_unit").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updatecbsku_height_unit").css("width", "100%");
		$(".updatecbsku_height_unit").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updatecbsku_length_unit").css("width", "100%");
		$(".updatecbsku_length_unit").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updatecbsku_volume_unit").css("width", "100%");
		$(".updatecbsku_volume_unit").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updatecbsku_width_unit").css("width", "100%");
		$(".updatecbsku_width_unit").select2({
			"theme": "bootstrap", "val": ""
		});
	}
	
	function UpdateLihatViewBosnet( RandBosnetID, P_sku_id, P_sku_nama_produk )
	{
		$("#updatelihattxtRandBosnetID").val( RandBosnetID );
		$("#updatelihattxtBsku_id").val( P_sku_id );
		$("#updatelihattxtBosnet_sku_nama_produk").val( P_sku_nama_produk );
		
		$("#updateubahtbskubosnet tbody").html('');
			
		for( i=0 ; i<arrUpdatesku_bosnet_id.length ; i++ )
		{
			var Bosnet_ID = arrUpdatesku_bosnet_id[i].Bosnet_ID;
			var sku_id = arrUpdatesku_bosnet_id[i].sku_id;
			
			if( sku_id == P_sku_id )
			{				
				var str = ''; 
				str = str + '<tr>';
				str = str + '	<td width="100%"><input type="text" class="updatelihattxtsku_bosnet_id form-control" value="'+ Bosnet_ID +'" disabled="disabled"/></td>';
				//str = str + '	<td width="20%">';
				//str = str + '<button type="button" class="updatelihatbtndeletebosnetid btn btn-danger" onclick="UpdateLihatDeleteBosnet( this.parentNode.parentNode.rowIndex )"><i class="fa fa-trash-o"></i></button></td>';
				str = str + '</tr>';
				
				$("#updatelihattbskubosnet tbody").append( str );
			}
		}
			
		$("#updatelihatpreviewaddbosnet").modal('show');
	}
	
	function UpdateViewBosnet( RandBosnetID, P_sku_id, P_sku_nama_produk )
	{
		$("#updatetxtRandBosnetID").val( RandBosnetID );
		$("#updatetxtBsku_id").val( P_sku_id );
		$("#updatetxtBosnet_sku_nama_produk").val( P_sku_nama_produk );
		
		$("#updatetbskubosnet tbody").html('');
			
		for( i=0 ; i<arrUpdatesku_bosnet_id.length ; i++ )
		{
			var Bosnet_ID = arrUpdatesku_bosnet_id[i].Bosnet_ID;
			var sku_id = arrUpdatesku_bosnet_id[i].sku_id;
			
			if( sku_id == P_sku_id )
			{				
				var str = ''; 
				str = str + '<tr>';
				str = str + '	<td width="80%"><input type="text" class="updatetxtsku_bosnet_id form-control" value="'+ Bosnet_ID +'" /></td>';
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
			str = str + '	<td width="80%"><input type="text" class="updatetxtsku_bosnet_id form-control" /></td>';
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
			var P_sku_id = $("#updatetxtBsku_id").val();
			var P_sku_nama_produk = $("#updatetxtBosnet_sku_nama_produk").val();
			
			// bersihin dulu bosnet id nya
			for( i= arrUpdatesku_bosnet_id.length - 1 ; i>= 0 ; i-- )
			{
				var Bosnet_ID 	= arrUpdatesku_bosnet_id[i].Bosnet_ID;
				var sku_id 		= arrUpdatesku_bosnet_id[i].sku_id;
				
				if( P_sku_id == sku_id )
				{
					arrUpdatesku_bosnet_id.splice( i, 1);
				}
			}
			
			var ltbbosnet = $(".updatetxtsku_bosnet_id").length;
			for( i=0 ; i<ltbbosnet ; i++ )
			{
				var Bosnet_ID = $(".updatetxtsku_bosnet_id").eq(i).val();
				
				arrUpdatesku_bosnet_id.push( { RandID : Rand, sku_id : P_sku_id, sku_nama_produk : P_sku_nama_produk, Bosnet_ID : Bosnet_ID } );
			}
			
			$("#updatepreviewaddbosnet").modal('hide');
		}
	);
	
	function UpdateCheckValuesWajib( Idx, RandVal, ID, Nama )
	{
		$("#updatetxtaddvarian_detail_rand").val( RandVal );
		
		$("#updatetxtaddvarian_detail_varian_id").val( ID );
		
		$("#updatetxtaddvarian_detail_varian_nama").val( Nama );
		
		$("#updatetxtaddvarian_detail_varian_detail_nama").val('');
		
		if( Nama == 'Berat Produk' ){	$("#updateisvarianberat").removeAttr('style');			}
		else						{	$("#updateisvarianberat").attr('style','display: none;');	}

		var vals = $("#updatecbvarianwajibdetail__"+ RandVal +" option:selected").val();

		if( vals == 'ADD' )
		{
			$("#updatepreviewaddvariandetail").modal('show');
		}
	}
	
	function UpdateCheckValues( Idx, RandVal, ID, Nama )
	{
		$("#updatetxtaddvarian_detail_rand").val( RandVal );
		
		$("#updatetxtaddvarian_detail_varian_id").val( ID );
		
		$("#updatetxtaddvarian_detail_varian_nama").val( Nama );
		
		$("#updatetxtaddvarian_detail_varian_detail_nama").val('');
		
		if( Nama == 'Berat Produk' ){	$("#updateisvarianberat").removeAttr('style');			}
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
			var Rand 				= $("#updatetxtaddvarian_detail_rand").val();
			
			var varian_id 			= $("#updatetxtaddvarian_detail_varian_id").val();
			var varian_detail_nama 	= $("#updatetxtaddvarian_detail_varian_detail_nama").val();
			var varian_detail_unit	= $("#cbupdateaddvarian_detail_varian_unit option:selected").val();
			
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
							$("#updatecbvarianwajibdetail__"+ Rand).append( '<option value="'+ varian_id +' | '+ varian_detail_id +' | '+ nama_varian_detail +'">'+ nama_varian_detail +'</option>' );
							
							$("#updatecbvarianwajibdetail__"+ Rand).val( varian_id +' | '+ varian_detail_id +' | '+ nama_varian_detail );
						}
						else if( Mode == 0 )	// Non Berat
						{
							$("#updatecbvariandetail__"+ Rand).append( '<option value="'+ varian_id +' | '+ varian_detail_id +' | '+ nama_varian_detail +'">'+ nama_varian_detail +'</option>' );
							
							$("#updatecbvariandetail__"+ Rand).val( varian_id +' | '+ varian_detail_id +' | '+ nama_varian_detail );
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
		var varian_id = $(".updatehdvarianwajib").eq(Idx).val();
		var VNama = $(".updatehdvariannamawajib").eq(Idx).val();
		
		//var varian_id = $("#updatehdvarianwajib").val();
		
		var rand2 = Math.random() * 1000000000000000000;
	
		if( varian_id != '' )
		{
			var strvarianwajibdetail = '';
			strvarianwajibdetail = strvarianwajibdetail + '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 updatedivchild__'+ countheader +' updatedivrowvarianwajib_'+ rand2 +'" id="updatedivrowvarianwajib_'+ rand2 +'">';
			strvarianwajibdetail = strvarianwajibdetail + '	<div class="input-group">';
			strvarianwajibdetail = strvarianwajibdetail + '		<select class="form-control updatecbvarianwajibdetail updatecbvarianwajibdetail__'+ rand2 +'" id="updatecbvarianwajibdetail__'+ rand2 +'" onchange="UpdateCheckValuesWajib('+ rand2 +','+ rand2 +',\''+ varian_id +'\',\''+ VNama +'\')" this.parentNode.parentNode.rowIndex )">';
				
			strvarianwajibdetail = strvarianwajibdetail + '			<option value="">Pilih jenis</option>';
			strvarianwajibdetail = strvarianwajibdetail + '			<option value="ADD">**Buat Jenis Baru**</option>';
			for( j=0; j<arrVarianDetail.length ; j++ )
			{
				var Dvarian_id 			= arrVarianDetail[j].varian_id;
				var Dvarian_detail_id 	= arrVarianDetail[j].varian_detail_id;
				var Dvarian_detail_nama = arrVarianDetail[j].varian_detail_nama;
				var nama_varian_detail 	= arrVarianDetail[j].nama_varian_detail;
			
				if( Dvarian_id == varian_id )
				{
				
					strvarianwajibdetail = strvarianwajibdetail + '	<option value="'+ Dvarian_id +' | '+ Dvarian_detail_id +'">'+ nama_varian_detail +'</option>';
				}
				
			}
			
			strvarianwajibdetail = strvarianwajibdetail + '		</select>';
			strvarianwajibdetail = strvarianwajibdetail + '		<div class="input-group-btn">';
			strvarianwajibdetail = strvarianwajibdetail + '			<button type="button" class="btn btn-danger" onclick="UpdateHapusVarianWajibDetail('+ countheader +', '+ rand2 +')"><i class="fa fa-trash"></i></button>';
			strvarianwajibdetail = strvarianwajibdetail + '		</div>';
			strvarianwajibdetail = strvarianwajibdetail + '	</div>';
			strvarianwajibdetail = strvarianwajibdetail + '</div>';
			
			$("#updatevarianwajibdetail"+ countheader).append( strvarianwajibdetail ); 
			
			$("#updatecbvarianwajibdetail__"+ rand2).css("width", "100%");
			$("#updatecbvarianwajibdetail__"+ rand2).select2({
				"theme": "bootstrap", "val": ""
			});	
		}
	}
	
	function UpdateHapusVarianWajibDetail( countheader, rand2 )
	{
		$("#updatedivrowvarianwajib_"+ rand2 ).remove();
	}
	
	function UpdateHapusVarianWajib( rand )
	{
		$("#updatevarianwajibheader"+ rand).remove();
	}
	
	function UpdateChangeVarianWajib( countheader )
	{
		$("#updatevarianwajibdetail"+ countheader ).html('');
	}
	/*
	$("#updatebtnaddvarian").click
	(
		function()
		{
			var lvarianoptional = $(".updatevarianheader").length;
			var batasoptional = '<?= $VarianOptionalMaxLimit; ?>';
			//var batasoptional = 2;
			
			if( lvarianoptional < batasoptional )
			{
				var rand = Math.random() * 10000000000000000000;
				
				var strvarian = '';
				strvarian = strvarian + '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 updatevarianheader" style="padding: 10px; margin-bottom: 10px; background-color: #dddddd;" id="updatevarianheader'+ rand +'">';
				strvarian = strvarian + '	<div class="row">';
				strvarian = strvarian + '		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">';
				strvarian = strvarian + '			<div class="input-group">';
				strvarian = strvarian + '				<select class="form-control updatecbvarian updatecbvarian_'+ rand +'" id="updatecbvarian_'+ rand +'" onchange="UpdateChangeVarian('+ rand +')">';
				strvarian = strvarian + '					<option value="">Pilih Varian</option>';
				
				for( j=0; j<arrVarian.length ; j++ )
				{
					var Dvarian_id 		= arrVarian[j].varian_id;
					var DVarian_Kode 	= arrVarian[j].Varian_Kode;
					var Dvarian_nama 	= arrVarian[j].varian_nama;
					var Dvarian_is_wajib 		= arrVarian[j].varian_is_wajib;
					
					if( Dvarian_is_wajib == 0 )
					{
						strvarian = strvarian + '				<option value="'+ Dvarian_id +'">'+ Dvarian_nama +'</option>';
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
				strvarian = strvarian + ' 		<button type="button" class="form-control btn btn-primary" onclick="UpdateTambahvarian_detail('+ rand +')">';
				strvarian = strvarian + '			<i class="fa fa-plus"></i> Tambah Jenis';
				strvarian = strvarian + '		</button>';
				strvarian = strvarian + '	</div>';
				strvarian = strvarian + '</div>';
				
				$("#updatedivvarian").append( strvarian );
				
				
				$("#updatecbvarian_"+ rand).css("width", "100%");
				$("#updatecbvarian_"+ rand).select2({
					"theme": "bootstrap", "val": ""
				});
			}
			else
			{
				var msg = 'Maksimal Varian (Opsional) adalah 2.';
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
	function UpdateTambahvarian_detail( rand )
	{
		
		var Idx = parseInt( rand ) - 1;
		var varian_id = $(".updatehdvarian").eq(Idx).val();
		var VNama = $(".updatehdvariannama").eq(Idx).val();
		
		//var varian_id = $("#updatecbvarian_"+ rand +" option:selected").val();
		//var VNama = $("#updatecbvarian_"+ rand +" option:selected").html();
		
		var rand2 = Math.random() * 1000000000000000000000;
 
		if( varian_id != '' )
		{
			var strvariandetail = '';
			strvariandetail = strvariandetail + '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 updatedivrowvarian_'+ rand +'" id="updatedivrowvarian_'+ rand2 +'">';
			strvariandetail = strvariandetail + '	<div class="input-group">';
			strvariandetail = strvariandetail + '		<select class="form-control updatecbvariandetail updatecbvariandetail__'+ rand2 +'" id="updatecbvariandetail__'+ rand2 +'" onchange="UpdateCheckValues('+ rand +','+ rand2 +',\''+ varian_id +'\',\''+ VNama +'\')">';
				
			strvariandetail = strvariandetail + '			<option value="">Pilih jenis</option>';
			strvariandetail = strvariandetail + '			<option value="ADD">**Buat Jenis Baru**</option>';

			for( j=0; j<arrVarianDetail.length ; j++ )
			{
				var Dvarian_id 			= arrVarianDetail[j].varian_id;
				var Dvarian_detail_id 	= arrVarianDetail[j].varian_detail_id;
				var Dvarian_detail_nama = arrVarianDetail[j].varian_detail_nama;
				
				if( Dvarian_id == varian_id )
				{
					strvariandetail = strvariandetail + '	<option value="'+ Dvarian_id +' | '+ Dvarian_detail_id +'">'+ Dvarian_detail_nama +'</option>';
				}
			}
			
			strvariandetail = strvariandetail + '		</select>';
			strvariandetail = strvariandetail + '		<div class="input-group-btn">';
			strvariandetail = strvariandetail + '			<button type="button" class="btn btn-danger" onclick="UpdateHapusvarian_detail('+ rand2 +')"><i class="fa fa-trash"></i></button>';
			strvariandetail = strvariandetail + '		</div>';
			strvariandetail = strvariandetail + '	</div>';
			strvariandetail = strvariandetail + '</div>';
			
			$("#updatevariandetail"+ rand).append( strvariandetail ); 
			
			$("#updatecbvariandetail__"+ rand2).css("width", "100%");
			$("#updatecbvariandetail__"+ rand2).select2({
				"theme": "bootstrap", "val": ""
			});	
			
		}
	}
	
	function UpdateHapusvarian_detail( rand2 )
	{
		$("#updatedivrowvarian_"+ rand2).remove();
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
			var sku_induk_id = $("#updatecbsku_induk option:selected").val();
					
			$.ajax(
			{
				type: 'POST',
				url: "<?= base_url('SKU/GetUpdateVarianMenu') ?>",
				data: 
				{
					sku_induk_id : sku_induk_id
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
				var sku_varian_id 		= SKU.SKU_Varian_WajibMenu[i].sku_varian_id;
				var sku_id				= SKU.SKU_Varian_WajibMenu[i].sku_id;
				var var_id   			= SKU.SKU_Varian_WajibMenu[i].var_id;
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
					strvarianwajib = strvarianwajib + '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 updatevarianheaderwajib" style="padding: 10px; margin-bottom: 10px; background-color: #dddddd;" id="varianheaderwajib'+ countheader +'">';
					strvarianwajib = strvarianwajib + '	<div class="row">';
					strvarianwajib = strvarianwajib + '		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">';
					strvarianwajib = strvarianwajib + '			<input type="hidden" 	id="updatehdvarianwajibidx_'+ countheader +'" 	class="form-control updatehdvarianwajibidx" value="'+ countheader +'" />';
					strvarianwajib = strvarianwajib + '			<input type="hidden" 	id="updatehdvarianwajib_'+ countheader +'" 		class="form-control updatehdvarianwajib" value="'+ varian_id +'" />';
					strvarianwajib = strvarianwajib + '			<input type="hidden" 	id="updatehdvariannamawajib_'+ countheader +'" 	class="form-control updatehdvariannamawajib" value="'+ varian_nama +'" />';
					strvarianwajib = strvarianwajib + '			<input type="text" 		id="updatetxtvarianwajib_'+ countheader +'" 	class="form-control updatetxtvarianwajib" value="'+ varian_nama +'" disabled="disabled" />';
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
				
				NamaVarian = varian_nama;
				IsHeader = 1;
			}
			
			$(".updatecbvarianwajibdetail").css("width", "100%");
			$(".updatecbvarianwajibdetail").select2({
				"theme": "bootstrap", "val": ""
			});
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

				var isHeaderNW = 0;

				var strheaderNW = '';
				var strfooterNW = '';
				
				if( NamaVarianNW != varian_nama )
				{
					countheaderNW = countheaderNW + 1;
					
					var strvarianwajibNW = '';
					strvarianwajibNW = strvarianwajibNW + '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 updatevarianheader" style="padding: 10px; margin-bottom: 10px; background-color: #dddddd;" id="varianheader'+ countheaderNW +'">';
					strvarianwajibNW = strvarianwajibNW + '	<div class="row">';
					strvarianwajibNW = strvarianwajibNW + '		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">';
					strvarianwajibNW = strvarianwajibNW + '			<input type="hidden" 	id="updatehdvarianidx_'+ countheaderNW +'" 	class="form-control updatehdvarianidx" value="'+ countheaderNW +'" />';
					strvarianwajibNW = strvarianwajibNW + '			<input type="hidden" 	id="updatehdvarian_'+ countheaderNW +'" 	class="form-control updatehdvarian" value="'+ varian_id +'" />';
					strvarianwajibNW = strvarianwajibNW + '			<input type="hidden" 	id="updatehdvariannama_'+ countheaderNW +'" class="form-control updatehdvariannama" value="'+ varian_nama +'" />';
					strvarianwajibNW = strvarianwajibNW + '			<input type="text" 		id="updatetxtvarian_'+ countheaderNW +'" 	class="form-control updatetxtvarian" value="'+ varian_nama +'" disabled="disabled" />';
					strvarianwajibNW = strvarianwajibNW + '		</div>';
					strvarianwajibNW = strvarianwajibNW + '	</div>';
				
					strvarianwajibNW = strvarianwajibNW + '	<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-horizontal form-label-left" id="updatedivvariandetail'+ countheaderNW +'">';
					strvarianwajibNW = strvarianwajibNW + '		<label class="control-label col-xs-4 col-sm-3 col-md-3 col-lg-3 col-xl-3">Jenis</label>';
					strvarianwajibNW = strvarianwajibNW + '		<div class="col-xs-8 col-sm-9 col-md-9 col-lg-9 col-xl-9" id="updatevariandetail'+ countheaderNW +'">';
					strvarianwajibNW = strvarianwajibNW + '		</div>';
					strvarianwajibNW = strvarianwajibNW + '	</div>';
					strvarianwajibNW = strvarianwajibNW + '	<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">';
					strvarianwajibNW = strvarianwajibNW + ' 	<button type="button" class="form-control btn btn-primary" onclick="UpdateTambahvarian_detail('+ countheaderNW +')">';
					strvarianwajibNW = strvarianwajibNW + '			<i class="fa fa-plus"></i> Tambah Jenis';
					strvarianwajibNW = strvarianwajibNW + '		</button>';
					strvarianwajibNW = strvarianwajibNW + '	</div>';
					strvarianwajibNW = strvarianwajibNW + '</div>';
					
					$("#updatedivvarian").append( strvarianwajibNW );
				
				}
				
				NamaVarianNW = varian_nama;
				IsHeaderNW = 1;
			}
			
			$(".updatecbvariandetail").css("width", "100%");
			$(".updatecbvariandetail").select2({
				"theme": "bootstrap", "val": ""
			});
		}
		/*
		if( SKU.SKU_Varian_Non_WajibMenu != 0 )
		{
			var NamaVarian = '';
			var countheader = 0;
			
			for( i=0 ; i<SKU.SKU_Varian_NonWajibMenu.length ; i++ )
			{
				var sku_varian_id 		= SKU.SKU_Varian_NonWajibMenu[i].sku_varian_id;
				var sku_id				= SKU.SKU_Varian_NonWajibMenu[i].sku_id;
				var var_id   			= SKU.SKU_Varian_NonWajibMenu[i].var_id;
				var varian_id   		= SKU.SKU_Varian_NonWajibMenu[i].varian_id;
				var varian_nama			= SKU.SKU_Varian_NonWajibMenu[i].varian_nama;
				var varian_is_wajib				= SKU.SKU_Varian_NonWajibMenu[i].varian_is_wajib;
				var varian_detail_id		= SKU.SKU_Varian_NonWajibMenu[i].varian_detail_id;
				var varian_detail_nama	= SKU.SKU_Varian_NonWajibMenu[i].varian_detail_nama;
				var sku_induk_id			= SKU.SKU_Varian_NonWajibMenu[i].sku_induk_id;

				var isHeader = 0;

				var strheader = '';
				var strfooter = '';
				
				var rand3 = Math.random() * 10000000000000000000;
				if( NamaVarian != varian_nama && varian_id == var_id )
				{
					countheader = countheader + 1;
					
					var strvarian = '';
					strvarian = strvarian + '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 updatevarianheader" style="padding: 10px; margin-bottom: 10px; background-color: #dddddd;" id="updatevarianheader'+ rand3 +'">';
					strvarian = strvarian + '	<div class="row">';
					strvarian = strvarian + '		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">';
					strvarian = strvarian + '			<div class="input-group">';
					strvarian = strvarian + '				<select class="form-control updatecbvarian updatecbvarian_'+ rand3 +'" id="updatecbvarian'+ rand3 +'" onchange="UpdateChangeVarian('+ rand3 +')">';
					strvarian = strvarian + '					<option value="">Pilih Varian</option>';
					strvarian = strvarian + '					<option value="ADD">** Buat Jenis Baru **</option>';
					
					for( j=0; j<arrVarian.length ; j++ )
					{
						var Dvarian_id 		= arrVarian[j].varian_id;
						var DVarian_Kode 	= arrVarian[j].Varian_Kode;
						var Dvarian_nama 	= arrVarian[j].varian_nama;
						var Dvarian_is_wajib 		= arrVarian[j].varian_is_wajib;
						
						if( Dvarian_is_wajib == 0 )
						{
							if( varian_id == Dvarian_id )	{	var isselected = 'selected';	}
							else 							{	var isselected = '';			}
							
							strvarian = strvarian + '				<option value="'+ Dvarian_id +'" '+ isselected +'>'+ Dvarian_nama +'</option>';
						}
					}
				
					strvarian = strvarian +	'				</select>';
					strvarian = strvarian +	'				<div class="input-group-btn">';
					strvarian = strvarian + '					<button type="button" class="btn btn-danger" onclick="UpdateHapusVarian('+ rand3 +')"><i class="fa fa-trash"></i></button>';
					strvarian = strvarian + '				</div>';
					strvarian = strvarian + '			</div>';
					strvarian = strvarian + '		</div>';
					strvarian = strvarian + '	</div>';
				
					strvarian = strvarian + '	<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-horizontal form-label-left" id="updatedivvariandetail'+ rand3 +'">';
					strvarian = strvarian + '		<label class="control-label col-xs-4 col-sm-3 col-md-3 col-lg-3 col-xl-3">Jenis</label>';
					strvarian = strvarian + '		<div class="col-xs-8 col-sm-9 col-md-9 col-lg-9 col-xl-9" id="updatevariandetail'+ rand3 +'">';
					strvarian = strvarian + '		</div>';
					strvarian = strvarian + '	</div>';
					strvarian = strvarian + '	<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">';
					strvarian = strvarian + ' 		<button type="button" class="form-control btn btn-primary" onclick="UpdateTambahvarian_detail('+ rand3 +')">';
					strvarian = strvarian + '			<i class="fa fa-plus"></i> Tambah Jenis';
					strvarian = strvarian + '		</button>';
					strvarian = strvarian + '	</div>';
					strvarian = strvarian + '</div>';
					
					$("#updatedivvarian").append( strvarian );
				
				}
				
				/*
				var strvariandetail = '';
				
				if( var_id == varian_id )
				{
					var rand2 = Math.random() * 100000000000000000;
					var VNama = varian_nama;
					
					strvariandetail = strvariandetail + '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 updatedivrowvarian_'+ rand3 +'" id="updatedivrowvarian_'+ rand3 +'">';
					strvariandetail = strvariandetail + '	<div class="input-group">';
					strvariandetail = strvariandetail + '		<select class="form-control updatecbvariandetail updatecbvariandetail__'+ rand3 +'" id="updatecbvariandetail__'+ rand3 +'" onchange="UpdateCheckValues('+ rand3 +','+ rand3 +',\''+ varian_id +'\',\''+ VNama +'\')">';
						
					strvariandetail = strvariandetail + '			<option value="">Pilih jenis</option>';
					strvariandetail = strvariandetail + '	<option value="ADD">**Buat Jenis Baru**</option>';
					for( j=0; j<arrVarianDetail.length ; j++ )
					{
						var Dvarian_id 			= arrVarianDetail[j].varian_id;
						var Dvarian_detail_id 	= arrVarianDetail[j].varian_detail_id;
						var Dvarian_detail_nama 	= arrVarianDetail[j].varian_detail_nama;
						
						if( varian_detail_id == Dvarian_detail_id )	{	var isselected = 'selected';	}
						else 										{	var isselected = '';			}
						
						if( Dvarian_id == varian_id )
						{
							strvariandetail = strvariandetail + '<option value="'+ Dvarian_id +' | '+ Dvarian_detail_id +'" '+ isselected +'>'+ Dvarian_detail_nama +'</option>';
						}
					}
					
					strvariandetail = strvariandetail + '		</select>';
					strvariandetail = strvariandetail + '		<div class="input-group-btn">';
					strvariandetail = strvariandetail + '			<button type="button" class="btn btn-danger" onclick="UpdateHapusvarian_detail('+ rand3 +')"><i class="fa fa-trash"></i></button>';
					strvariandetail = strvariandetail + '		</div>';
					strvariandetail = strvariandetail + '	</div>';
					strvariandetail = strvariandetail + '</div>';
				}
				$("#updatevariandetail"+ rand3).append( strvariandetail ); 
				*/
				/*
				if( var_id == varian_id )
				{
					NamaVarian = varian_nama;
				}
				
				IsHeader = 1;
				
				$("#updatecbvarian"+ rand3).css("width", "100%");
				$("#updatecbvarian"+ rand3).select2({
					"theme": "bootstrap", "val": ""
				});
				
				$("#updatecbvariandetail__"+ rand3).css("width", "100%");
				$("#updatecbvariandetail__"+ rand3).select2({
					"theme": "bootstrap", "val": ""
				});
			}
		}
		*/
		$("#updatestep2").removeAttr('style');		
		$("#updatestep3").removeAttr('style');				
		//$("#step4").removeAttr('style');	
		
		UpdateProses( 1 );
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
			UpdateProses( 1 );
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
			
			var strsku_PPh = '';
			strsku_PPh = strsku_PPh + '<option value="1">Include</option>';
			strsku_PPh = strsku_PPh + '<option value="2">Exclude</option>';
			strsku_PPh = strsku_PPh + '<option value="3">None</option>';
			
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
			
			var strKemasan = '';
			
			for( i=0 ; i< StringSKU.StringSKUMenu.length ; i++ )
			{
				var RandBosnetID = Math.random() * 1000000000000000000;
				
				var sku_nama_produk = StringSKU.StringSKUMenu[i].sku_nama_produk;
				var sku_kode 		= StringSKU.StringSKUMenu[i].sku_kode;
				var sku_kemasan 	= StringSKU.StringSKUMenu[i].kemasan;
				var sku_satuan 		= StringSKU.StringSKUMenu[i].sku_satuan;
				
				var berat 			= StringSKU.StringSKUMenu[i].berat;
				var nama1 			= StringSKU.StringSKUMenu[i].nama1;
				var nama2 			= StringSKU.StringSKUMenu[i].nama2;
				var nama3 			= StringSKU.StringSKUMenu[i].nama3;
				var nama4 			= StringSKU.StringSKUMenu[i].nama4;
				var nama5 			= StringSKU.StringSKUMenu[i].nama5;
				var unit 			= StringSKU.StringSKUMenu[i].unit;
				
				var str = '';
				
				str = str + '<tr>';
				str = str + '	<input type="hidden" class="updatehdnama1" value="'+ nama1 +'" />';							
				str = str + '	<input type="hidden" class="updatehdnama2" value="'+ nama2 +'" />';							
				str = str + '	<input type="hidden" class="updatehdnama3" value="'+ nama3 +'" />';	
				str = str + '	<input type="hidden" class="updatehdnama4" value="'+ nama4 +'" />';	
				str = str + '	<input type="hidden" class="updatehdnama5" value="'+ nama5 +'" />';	
				str = str + '	<input type="hidden" class="updatehdunit" value="'+ unit +'" />';	
				
				str = str + '	<td><button type="button" class="form-control btn btn-danger" onclick="UpdateHapusGenerated(this.parentNode.parentNode.rowIndex)"><i class="fa fa-times"></i></button></td>';							
				
				str = str + '	<td><button type="button" class="form-control btn btn-success" onclick="UpdateCopas(this.parentNode.parentNode.rowIndex)"><i class="fa fa-paste"></i> &nbsp;&nbsp;<i class="fa fa-angle-right"></i></button></td>';							
				str = str + '	<input type="hidden" class="updatetxtsku_kode" value="'+ sku_kode +'" />';							
				str = str + '	<td><input type="text" style="width: 500px;" class="updatetxtsku_nama_produk form-control" disabled="disabled" value="'+ sku_nama_produk +'" /></td>';
				str = str + '	<td><button type="button" id="updateubahbtnviewsku_bosnet_id" class="btn btn-primary updateubahbtnviewsku_bosnet_id" onclick="UpdateUbahViewBosnet(\''+ RandBosnetID +'\',\''+ sku_nama_produk +'\')" ><i class="fa fa-pencil"></i> Isi Bosnet ID</button></td>';
				str = str + '	<td><input type="text" class="updatetxtsku_deskripsi form-control" /></td>';
				str = str + '	<td><input type="text" class="updatetxtsku_kemasan form-control" value="'+ sku_kemasan +'" disabled="disabled" /></td>';
				str = str + '	<td><input type="text" class="updatetxtsku_satuan form-control" value="'+ nama1 +'" disabled="disabled" /></td>';
				str = str + '	<td><select class="updatecbsku_kondisi form-control">'+ strsku_kondisi +'</select></td>';
				str = str + '	<td><input type="text" class="updatetxtsku_origin form-control" /></td>';
				str = str + '	<td><input type="number" class="updatetxtsku_harga_jual form-control" value="0" /></td>';
				str = str + '	<td><input type="number" class="updatetxtsku_sales_min_qty form-control" value="0" /></td>';
				//str = str + '	<td><select class="updatecbsku_PPh form-control">'+ strsku_PPh +'</select></td>';
				//str = str + '	<td><input type="number" class="updatetxtsku_PPnBM_Persen form-control" value="0" /></td>';
				//str = str + '	<td><input type="number" class="updatetxtsku_PPn_Persen form-control" value="0" /></td>';
				str = str + '	<td><input type="checkbox" class="checkbox_form updatechsku_is_aktif" checked="checked" /></td>';
				str = str + '	<td><input type="checkbox" class="checkbox_form updatechsku_is_jual" checked="checked" /></td>';
				str = str + '	<td><input type="number" class="updatetxtsku_weight_netto form-control" value="'+ berat +'" /></td>';
				str = str + '	<td><select class="updatecbsku_weight_netto_unit form-control">'+ strSatuanBerat +'</select></td>';
				str = str + '	<td><input type="number" class="updatetxtsku_weight_product form-control" value="0" /></td>';
				str = str + '	<td><select class="updatecbsku_weight_product_unit form-control">'+ strSatuanBerat +'</select></td>';
				str = str + '	<td><input type="number" class="updatetxtsku_weight_packaging form-control" value="0" /></td>';
				str = str + '	<td><select class="updatecbsku_weight_packaging_unit form-control">'+ strSatuanBerat +'</select></td>';
				str = str + '	<td><input type="number" class="updatetxtsku_weight_gift form-control" value="0" /></td>';
				str = str + '	<td><select class="updatecbsku_weight_gift_unit form-control">'+ strSatuanBerat +'</select></td>';
				str = str + '	<td><input type="number" class="updatetxtsku_length form-control" value="0" /></td>';
				str = str + '	<td><select class="updatecbsku_length_unit form-control">'+ strSatuanPanjang +'</select></td>';
				str = str + '	<td><input type="number" class="updatetxtsku_width form-control" value="0" /></td>';
				str = str + '	<td><select class="updatecbsku_width_unit form-control">'+ strSatuanLebar +'</select></td>';
				str = str + '	<td><input type="number" class="updatetxtsku_height form-control" value="0" /></td>';
				str = str + '	<td><select class="updatecbsku_height_unit form-control">'+ strSatuanTinggi +'</select></td>';
				str = str + '	<td><input type="number" class="updatetxtsku_volume form-control" value="0" /></td>';
				str = str + '	<td><select class="updatecbsku_volume_unit form-control">'+ strSatuanVolume +'</select></td>';
				str = str + '</tr>';
				
				$("#updatetablegeneratedstringsku > tbody").append( str );
				
				$(".updatecbsku_weight_netto_unit").eq(i).val( unit );
				
			}
			
		}
		
		$('#updatetablegeneratedstringsku').DataTable(
		{
			//scrollX: "100%",
			paging: false,
			retrieve: true,
			initComplete: function (settings, json) 
			{  
				$("#updatetablegeneratedstringsku").wrap("<div style='overflow:auto; width:100%; height: 400px; position:relative;'></div>");            
			},
			"dom": '<"left"f>rt'
		});
		
		$(".updatecbkemasan_id").css("width", "100%");
		$(".updatecbkemasan_id").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updatecbsku_weight_netto_unit").css("width", "100%");
		$(".updatecbsku_weight_netto_unit").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updatecbsku_weight_product_unit").css("width", "100%");
		$(".updatecbsku_weight_product_unit").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updatecbsku_weight_packaging_unit").css("width", "100%");
		$(".updatecbsku_weight_packaging_unit").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updatecbsku_weight_gift_unit").css("width", "100%");
		$(".updatecbsku_weight_gift_unit").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updatecbsku_height_unit").css("width", "100%");
		$(".updatecbsku_height_unit").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updatecbsku_length_unit").css("width", "100%");
		$(".updatecbsku_length_unit").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updatecbsku_volume_unit").css("width", "100%");
		$(".updatecbsku_volume_unit").select2({
			"theme": "bootstrap", "val": ""
		});
		
		$(".updatecbsku_width_unit").css("width", "100%");
		$(".updatecbsku_width_unit").select2({
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
	
	function UpdateUbahViewBosnet( RandBosnetID, P_sku_nama_produk )
	{
		$("#updateubahtxtRandBosnetID").val( RandBosnetID );
		$("#updateubahtxtBosnet_sku_nama_produk").val( P_sku_nama_produk );
		
		$("#updateubahtbskubosnet tbody").html('');
		
		for( i=0 ; i<arrUpdateUbahsku_bosnet_id.length ; i++ )
		{
			var Bosnet_ID = arrUpdateUbahsku_bosnet_id[i].Bosnet_ID;
			var sku_nama_produk = arrUpdateUbahsku_bosnet_id[i].sku_nama_produk;
			
			if( sku_nama_produk == P_sku_nama_produk )
			{				
				var str = ''; 
				str = str + '<tr>';
				str = str + '	<td width="80%"><input type="text" class="updateubahtxtsku_bosnet_id form-control" value="'+ Bosnet_ID +'" /></td>';
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
			str = str + '	<td width="80%"><input type="text" class="updateubahtxtsku_bosnet_id form-control" /></td>';
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
			var P_sku_nama_produk = $("#updateubahtxtBosnet_sku_nama_produk").val();
			
			// bersihin dulu bosnet id nya
			for( i= arrUpdateUbahsku_bosnet_id.length - 1 ; i>= 0 ; i-- )
			{
				var Bosnet_ID 		= arrUpdateUbahsku_bosnet_id[i].Bosnet_ID;
				var sku_nama_produk 	= arrUpdateUbahsku_bosnet_id[i].sku_nama_produk;
				
				if( P_sku_nama_produk == sku_nama_produk )
				{
					arrsku_bosnet_id.splice( i, 1);
				}
			}
			
			var ltbbosnet = $(".updateubahtxtsku_bosnet_id").length;
			for( i=0 ; i<ltbbosnet ; i++ )
			{
				var Bosnet_ID = $(".updateubahtxtsku_bosnet_id").eq(i).val();
				
				arrUpdateUbahsku_bosnet_id.push( { RandID : Rand, sku_nama_produk : P_sku_nama_produk, Bosnet_ID : Bosnet_ID } );
			}
			
			$("#updateubahpreviewaddbosnet").modal('hide');
		}
	);
	
	$("#updatecbkategori1").change
	(
		function()
		{
			var kategori1_id = $("#updatecbkategori1 option:selected").val();
			
			if( kategori1_id == '' )
			{
				$("#updatekat2").attr('style','display: none');
				$("#updatekat3").attr('style','display: none');
				//$("#pri").attr('style','display: none');
			
				$("#updatecbkategori2").html('');
				$("#updatecbkategori2").append('<option value="" selected>--Pilih Sub Kategori--</option>');
				$("#updatecbkategori3").html('');
				$("#updatecbkategori3").append('<option value="" selected>--Pilih Sub Kategori 2--</option>');
				//$("#cbkategori4").html('');
				//$("#cbkategori4").append('<option value="" selected>--Pilih Principle--</option>');
			}
			else
			{
				UpdateGetSKUCategory( 1, kategori1_id );
			}
		}
	);
		
	$("#updatecbkategori2").change
	(
		function()
		{
			var kategori1_id = $("#updatecbkategori2 option:selected").val();
			
			if( kategori1_id == '' )
			{
				$("#updatekat3").attr('style','display: none');
				//$("#pri").attr('style','display: none');
				
				$("#updatecbkategori3").html('');
				$("#updatecbkategori3").append('<option value="" selected>--Pilih Sub Kategori 2--</option>');
				//$("#cbkategori4").html('');
				//$("#cbkategori4").append('<option value="" selected>--Pilih Principle--</option>');
			}
			else
			{
				UpdateGetSKUCategory( 2, kategori1_id );
			}
		}
	);
	
	$("#updatecbkategori3").change
	(
		function()
		{
			var kategori1_id = $("#updatecbkategori3 option:selected").val();
			
			if( kategori1_id == '' )
			{
				$("#updatekat4").attr('style','display: none');
				//$("#pri").attr('style','display: none');
				
				$("#updatecbkategori4").html('');
				$("#updatecbkategori4").append('<option value="" selected>--Pilih Sub Kategori 3--</option>');
				//$("#cbkategori4").html('');
				//$("#cbkategori4").append('<option value="" selected>--Pilih Principle--</option>');
			}
			else
			{
				UpdateGetSKUCategory( 3, kategori1_id );
			}
		}
	);
	
	function UpdateGetSKUCategory( Jenis, kategori1_id )
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
					UpdateChSKUCategory( response, Jenis );
				}
			});
		}
		else if( kategori1_id == '' )
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
				$("#updatecbkategori1").html('');
				$("#updatecbkategori1").append( '<option value="" selected>--Pilih Kategori--</option>' );	
			}
			if( Jenis == 1 )		
			{	
				$("#updatekat2").removeAttr('style');
				$("#updatecbkategori2").html('');
				$("#updatecbkategori2").append( '<option value="" selected>--Pilih Sub Kategori--</option>' );
				
				$("#updatekat3").attr('style','display: none;');
				$("#updatecbkategori3").html('');
				$("#updatecbkategori3").append( '<option value="" selected>--Pilih Sub Kategori 2--</option>' );	
			}
			if( Jenis == 2 )		
			{	
				$("#updatekat3").removeAttr('style');
				$("#updatecbkategori3").html('');
				$("#updatecbkategori3").append( '<option value="" selected>--Pilih Sub Kategori 2--</option>' );	
				
				$("#updatekat4").attr('style','display: none;');
				$("#updatecbkategori4").html('');
				$("#updatecbkategori4").append( '<option value="" selected>--Pilih Sub Kategori 3--</option>' );	
			}
			else if( Jenis == 3 )	
			{	
				$("#updatekat4").removeAttr('style');
				$("#updatecbkategori4").html('');
				$("#updatecbkategori4").append( '<option value="" selected>--Pilih Sub Kategori 3--</option>' );	
				
				//$("#kat1").attr('style','display: none;');
				//$("#kat2").attr('style','display: none;');
				//$("#kat3").attr('style','display: none;');
				//$("#cbkategori4").html('');
				//$("#cbkategori4").append( '<option value="" selected>--Pilih Principle--</option>' );	
			}
			
			if( Kategori.JenisKategori.length != 0 )
			{
				for( i=0 ; i<Kategori.JenisKategori.length ; i++ )
				{
					var kategori1_id = Kategori.JenisKategori[i].kategori1_id;
					var kategori1_kode = Kategori.JenisKategori[i].kategori1_kode;
					var kategori1_nama = Kategori.JenisKategori[i].kategori1_nama;

					if( Jenis == 0 )
					{	
						$("#updatecbkategori1").append( '<option value="'+ kategori1_id +'">'+ kategori1_nama +'</option>' );	
					}
					else if( Jenis == 1 )	
					{	
						$("#updatecbkategori2").append( '<option value="'+ kategori1_id +'">'+ kategori1_nama +'</option>' );	
					}
					else if( Jenis == 2 )		
					{	
						$("#updatecbkategori3").append( '<option value="'+ kategori1_id +'">'+ kategori1_nama +'</option>' );	
					}
					else if( Jenis == 3 )		
					{	
						$("#updatecbkategori4").append( '<option value="'+ kategori1_id +'">'+ kategori1_nama +'</option>' );	
					}
					else if( Jenis == 9 )	
					{	
						$("#updatecbprinciple").append( '<option value="'+ kategori1_id +'">'+ kategori1_nama +'</option>' );	
					}
					else if( Jenis == 10 )	
					{	
						$("#updatecbprinciplebrand").append( '<option value="'+ kategori1_id +'">'+ kategori1_nama +'</option>' );	
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
			
			var lvarian = $(".updatetxtvarianwajib").length;
			
			var lvariandetail = $(".updatecbvariandetail").length;
			
			var arrvarian_id = [];
			var arrDataVarian = [];
			
			var numerror = 0;
			
			for( i=0 ; i<lvarian ; i++ )
			{
				var varian_id 	= $(".updatehdvarianwajib").eq(i).val();
				var varian_nama = $(".updatehdvariannamawajib").eq(i).val();
				var NUM_ID 		= $(".updatehdvarianwajibidx").eq(i).val();
				
				//alert( $(".updatecbvarianwajibdetail").length );
			
				//var varian_id = $(".cbvarian").eq(i).val();
				//var varian_nama = $(".cbvarian").eq(i).html();
				
				//var NUM_ID = $(".cbvarian").eq(i).attr('class');
				//NUM_ID = NUM_ID.split('_');
				//NUM_ID = NUM_ID[1];
				//NUM_ID = NUM_ID.split(' ');
				//NUM_ID = NUM_ID[0];
				var strlen = $("#updatevarianwajibdetail"+ NUM_ID ).html();
			
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
			
			var lvarian = $(".updatehdvarian").length;
			
			for( i=0 ; i<lvarian ; i++ )
			{
				var varian_id = $(".updatehdvarian").eq(i).val();
				var varian_nama = $(".updatehdvariannama").eq(i).html();
				
				var NUM_ID = $(".updatehdvarianidx").eq(i).val();
				/*
				NUM_ID = NUM_ID.split('_');
				NUM_ID = NUM_ID[1];
				
				NUM_ID = NUM_ID.split(' ');
				NUM_ID = NUM_ID[0];
				*/
				var strlen = $("#updatevariandetail"+ NUM_ID ).html();
				
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
				var msg = 'Semua Varian harus terisi, tidak boleh kembar, dan punya Jenis Varian.';
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
								
								varian_id = VarianWajibDetail[0];
								varian_detail_id = VarianWajibDetail[1];
								
								if( !arrVarianWajibDetail_ID.includes( varian_detail_id ) )
								{
									arrVarianWajibDetail_ID.push( varian_detail_id );
									
									arrUpdateDataVarianWajibDetail.push( { varian_id : varian_id, varian_urut: 1, varian_detail_id : varian_detail_id, varian_detail_urut : parseInt(j)+1 } );
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
						arrVarianDetail_id = [];
						arrUpdateDataVarianDetail = [];
						//var arrDataVarianDetail = [];
						
						
						var arrIDvarian_detail = [];
						
						var lvariandetail = $(".updatecbvariandetail").length;
						
						for( i=0 ; i<lvariandetail ; i++ )
						{
							var ID = $(".updatecbvariandetail").eq(i).attr('class');
							
							ID = ID.split('__');
							ID = ID[1];
							ID = ID.split(' ');
							
							NUMBER_ID = ID[0];
							
							if( !arrIDvarian_detail.includes( NUMBER_ID ) )
							{
								arrIDvarian_detail.push( NUMBER_ID );
							}
						}
						
						if( arrIDvarian_detail.length == 0 )
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
							
							var sku_induk_id = $("#updatecbsku_induk option:selected").val();
							
							$.ajax(
							{
								type: 'POST',    
								url: "<?= base_url('SKU/GenerateStringSKU') ?>",
								data : 
								{
									sku_induk_id 				: sku_induk_id, 
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
							for( i=0 ; i<arrIDvarian_detail.length ; i++ )
							{
								var NUM_ID = arrIDvarian_detail[i];
								
								var cbvariandetail = $(".updatecbvariandetail__"+ NUM_ID ).length;
								
								
								for( j=0 ; j<cbvariandetail ; j++ )
								{
									var varian_detail = $(".updatecbvariandetail__"+ NUM_ID).eq(j).val();
									
									if( varian_detail == '' )
									{
										numerror = 4;
									}
									else
									{
										varian_detail = varian_detail.split(' | ');
										
										varian_id = varian_detail[0];
										varian_detail_id = varian_detail[1];
										
										if( !arrVarianDetail_id.includes( varian_detail_id ) )
										{
										
											arrVarianDetail_id.push( varian_detail_id );
											arrUpdateDataVarianDetail.push( { varian_id : varian_id, varian_urut: parseInt(i)+2, varian_detail_id : varian_detail_id, varian_detail_urut: parseInt(j)+1 } );
											
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
								var sku_induk_id = $("#updatecbsku_induk option:selected").val();
							
								$.ajax(
								{
									type: 'POST',    
									url: "<?= base_url('SKU/GenerateStringSKU') ?>",
									data : 
									{
										sku_induk_id 				: sku_induk_id, 
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
			
			var sku_deskripsi 				= $("#updatetxts_sku_deskripsi").val();
			var kemasan_id	 				= $("#updatecbs_kemasan_id option:selected").val();
			var sku_kondisi 				= $("#updatecbs_sku_kondisi option:selected").val();
			var sku_origin 					= $("#updatetxts_sku_origin").val();
			var sku_harga_jual 				= $("#updatetxts_sku_harga_jual").val();
			var sku_sales_min_qty 			= $("#updatetxts_sku_sales_min_qty").val();
			var sku_is_aktif				= $("#updatechs_sku_is_aktif").prop('checked');
			var sku_is_jual					= $("#updatechs_sku_is_jual").prop('checked');
			var sku_weight_netto			= $("#updatetxts_sku_weight_netto").val();
			var sku_weight_netto_unit		= $("#updatecbs_sku_weight_netto_unit option:selected").val();
			var sku_weight_product			= $("#updatetxts_sku_weight_product").val();
			var sku_weight_product_unit		= $("#updatecbs_sku_weight_product_unit option:selected").val();
			var sku_weight_packaging		= $("#updatetxts_sku_weight_packaging").val();
			var sku_weight_packaging_unit	= $("#updatecbs_sku_weight_packaging_unit option:selected").val();
			var sku_weight_gift				= $("#updatetxts_sku_weight_gift").val();
			var sku_weight_gift_unit		= $("#updatecbs_sku_weight_gift_unit option:selected").val();
			var sku_length					= $("#updatetxts_sku_length").val();
			var sku_length_unit 			= $("#updatecbs_sku_length_unit option:selected").val();
			var sku_width					= $("#updatetxts_sku_width").val();
			var sku_width_unit				= $("#updatecbs_sku_width_unit option:selected").val();
			var sku_height					= $("#updatetxts_sku_height").val();
			var sku_height_unit				= $("#updatecbs_sku_height_unit option:selected").val();
			var sku_volume					= $("#updatetxts_sku_volume").val();
			var sku_volume_unit				= $("#updatecbs_sku_volume_unit option:selected").val();
			
			if( sku_is_aktif == true ) 	{ sku_is_aktif = 1;	}
			else					 	{ sku_is_aktif = 0;	}
			
			if( sku_is_jual == true ) 	{ sku_is_jual = 1;	}
			else					 	{ sku_is_jual = 0;	}
			
			arrUpdateSalin = [];
			
			arrUpdateSalin.push({	sku_deskripsi: sku_deskripsi, kemasan_id: kemasan_id, sku_kondisi: sku_kondisi, sku_origin: sku_origin, sku_harga_jual: sku_harga_jual,
									sku_sales_min_qty: sku_sales_min_qty, /*sku_PPh: sku_PPh, sku_PPnBM_Persen: sku_PPnBM_Persen, sku_PPn_Persen: sku_PPn_Persen, */
									sku_is_aktif: sku_is_aktif, sku_is_jual: sku_is_jual, 
									sku_weight_netto: sku_weight_netto, sku_weight_netto_unit: sku_weight_netto_unit, 
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
			var sku_deskripsi 				= $("#updatetxts_sku_deskripsi").val();
			var kemasan_id	 				= $("#updatecbs_kemasan_id option:selected").val();
			var sku_kondisi 				= $("#updatecbs_sku_kondisi option:selected").val();
			var sku_origin 					= $("#updatetxts_sku_origin").val();
			var sku_harga_jual 				= $("#updatetxts_sku_harga_jual").val();
			var sku_sales_min_qty 			= $("#updatetxts_sku_sales_min_qty").val();
			var sku_is_aktif				= $("#updatechs_sku_is_aktif").prop('checked');
			var sku_is_jual					= $("#updatechs_sku_is_jual").prop('checked');
			var sku_weight_netto			= $("#updatetxts_sku_weight_netto").val();
			var sku_weight_netto_unit		= $("#updatecbs_sku_weight_netto_unit option:selected").val();
			var sku_weight_product			= $("#updatetxts_sku_weight_product").val();
			var sku_weight_product_unit		= $("#updatecbs_sku_weight_product_unit option:selected").val();
			var sku_weight_packaging		= $("#updatetxts_sku_weight_packaging").val();
			var sku_weight_packaging_unit	= $("#updatecbs_sku_weight_packaging_unit option:selected").val();
			var sku_weight_gift				= $("#updatetxts_sku_weight_gift").val();
			var sku_weight_gift_unit		= $("#updatecbs_sku_weight_gift_unit option:selected").val();
			var sku_length					= $("#updatetxts_sku_length").val();
			var sku_length_unit 			= $("#updatecbs_sku_length_unit option:selected").val();
			var sku_width					= $("#updatetxts_sku_width").val();
			var sku_width_unit				= $("#updatecbs_sku_width_unit option:selected").val();
			var sku_height					= $("#updatetxts_sku_height").val();
			var sku_height_unit				= $("#updatecbs_sku_height_unit option:selected").val();
			var sku_volume					= $("#updatetxts_sku_volume").val();
			var sku_volume_unit				= $("#updatecbs_sku_volume_unit option:selected").val();
			
			var ldatasku = $(".updatetxtsku_nama_produk").length;
			
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
					$(".updatetxtsku_deskripsi").eq(i).val( sku_deskripsi );
					$(".updatecbkemasan_id").eq(i).val( kemasan_id ).change();
					$(".updatecbsku_kondisi").eq(i).val( sku_kondisi );
					$(".updatetxtsku_origin").eq(i).val( sku_origin );
					$(".updatetxtsku_harga_jual").eq(i).val( sku_harga_jual );
					$(".updatetxtsku_sales_min_qty").eq(i).val( sku_sales_min_qty );
					//$(".updatecbsku_PPh").eq(i).val( sku_PPh );
					//$(".updatetxtsku_PPnBM_Persen").eq(i).val( sku_PPnBM_Persen );
					//$(".updatetxtsku_PPn_Persen").eq(i).val( sku_PPn_Persen );
					
					$(".updatechsku_is_aktif").eq(i).prop('checked', sku_is_aktif );
					$(".updatechsku_is_jual").eq(i).prop('checked', sku_is_jual );
					
					$(".updatetxtsku_weight_netto").eq(i).val( sku_weight_netto );
					$(".updatecbsku_weight_netto_unit").eq(i).val( sku_weight_netto_unit ).change();
					$(".updatetxtsku_weight_product").eq(i).val( sku_weight_product );
					$(".updatecbsku_weight_product_unit").eq(i).val( sku_weight_product_unit ).change();
					$(".updatetxtsku_weight_packaging").eq(i).val( sku_weight_packaging );
					$(".updatecbsku_weight_packaging_unit").eq(i).val( sku_weight_packaging_unit ).change();
					$(".updatetxtsku_weight_gift").eq(i).val( sku_weight_gift );
					$(".updatecbsku_weight_gift_unit").eq(i).val( sku_weight_gift_unit ).change();
					
					$(".updatetxtsku_length").eq(i).val( sku_length );
					$(".updatecbsku_length_unit").eq(i).val( sku_length_unit ).change();
					$(".updatetxtsku_width").eq(i).val( sku_width );
					$(".updatecbsku_width_unit").eq(i).val( sku_width_unit ).change();
					$(".updatetxtsku_height").eq(i).val( sku_height );
					$(".updatecbsku_height_unit").eq(i).val( sku_height_unit ).change();
					$(".updatetxtsku_volume").eq(i).val( sku_volume );
					$(".updatecbsku_volume_unit").eq(i).val( sku_volume_unit ).change();
					
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
				var sku_deskripsi 			= arrUpdateSalin[0].sku_deskripsi;
				var kemasan_id	 			= arrUpdateSalin[0].kemasan_id;
				var sku_kondisi 			= arrUpdateSalin[0].sku_kondisi;
				var sku_origin 				= arrUpdateSalin[0].sku_origin;
				var sku_harga_jual 			= arrUpdateSalin[0].sku_harga_jual;
				var sku_sales_min_qty 		= arrUpdateSalin[0].sku_sales_min_qty;
				//var sku_PPh 				= arrUpdateSalin[0].sku_PPh;
				//var sku_PPnBM_Persen 		= arrUpdateSalin[0].sku_PPnBM_Persen;
				//var sku_PPn_Persen			= arrSalin[0].sku_PPn_Persen;
				var sku_is_aktif			= arrUpdateSalin[0].sku_is_aktif;
				var sku_is_jual				= arrUpdateSalin[0].sku_is_jual;
				
				var sku_weight_netto		= arrUpdateSalin[0].sku_weight_netto;
				var sku_weight_netto_unit	= arrUpdateSalin[0].sku_weight_netto_unit;
				var sku_weight_product		= arrUpdateSalin[0].sku_weight_product;
				var sku_weight_product_unit	= arrUpdateSalin[0].sku_weight_product_unit;
				var sku_weight_packaging		= arrUpdateSalin[0].sku_weight_packaging;
				var sku_weight_packaging_unit	= arrUpdateSalin[0].sku_weight_packaging_unit;
				var sku_weight_gift			= arrUpdateSalin[0].sku_weight_gift;
				var sku_weight_gift_unit		= arrUpdateSalin[0].sku_weight_gift_unit;
				
				var sku_length				= arrUpdateSalin[0].sku_length;
				var sku_length_unit 			= arrUpdateSalin[0].sku_length_unit;
				var sku_width				= arrUpdateSalin[0].sku_width;
				var sku_width_unit			= arrUpdateSalin[0].sku_width_unit;
				var sku_height				= arrUpdateSalin[0].sku_height;
				var sku_height_unit			= arrUpdateSalin[0].sku_height_unit;
				var sku_volume				= arrUpdateSalin[0].sku_volume;
				var sku_volume_unit			= arrUpdateSalin[0].sku_volume_unit;
				
				if( sku_is_aktif == 1 )	{ sku_is_aktif = true;	}
				else					{ sku_is_aktif = false;	}
				
				if( sku_is_jual == 1 )	{ sku_is_jual = true;	}
				else					{ sku_is_jual = false;	}
				
				$(".updatetxtsku_deskripsi").eq(i).val( sku_deskripsi );
				$(".updatecbkemasan_id").eq(i).val( kemasan_id ).change();
				$(".updatecbsku_kondisi").eq(i).val( sku_kondisi );
				$(".updatetxtsku_origin").eq(i).val( sku_origin );
				$(".updatetxtsku_harga_jual").eq(i).val( sku_harga_jual );
				$(".updatetxtsku_sales_min_qty").eq(i).val( sku_sales_min_qty );
				//$(".updatecbsku_PPh").eq(i).val( sku_PPh );
				//$(".updatetxtsku_PPnBM_Persen").eq(i).val( sku_PPnBM_Persen );
				//$(".updatetxtsku_PPn_Persen").eq(i).val( sku_PPn_Persen );
				
				$(".updatechsku_is_aktif").eq(i).prop('checked', sku_is_aktif );
				$(".updatechsku_is_jual").eq(i).prop('checked', sku_is_jual );
				
				$(".updatetxtsku_weight_netto").eq(i).val( sku_weight_netto );
				$(".updatecbsku_weight_netto_unit").eq(i).val( sku_weight_netto_unit );
				$(".updatetxtsku_weight_product").eq(i).val( sku_weight_product );
				$(".updatecbsku_weight_product_unit").eq(i).val( sku_weight_product_unit );
				$(".updatetxtsku_weight_packaging").eq(i).val( sku_weight_packaging );
				$(".updatecbsku_weight_packaging_unit").eq(i).val( sku_weight_packaging_unit );
				$(".updatetxtsku_weight_gift").eq(i).val( sku_weight_gift );
				$(".updatecbsku_weight_gift_unit").eq(i).val( sku_weight_gift_unit );
				
				$(".updatetxtsku_length").eq(i).val( sku_length );
				$(".updatecbsku_length_unit").eq(i).val( sku_length_unit );
				$(".updatetxtsku_width").eq(i).val( sku_width );
				$(".updatecbsku_width_unit").eq(i).val( sku_width_unit );
				$(".updatetxtsku_height").eq(i).val( sku_height );
				$(".updatecbsku_height_unit").eq(i).val( sku_height_unit );
				$(".updatetxtsku_volume").eq(i).val( sku_volume );
				$(".updatecbsku_volume_unit").eq(i).val( sku_volume_unit );
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