<script type="text/javascript">	
	function AturHadiah( SKUInduk_ID, SKUInduk_Nama )
	{
		$("#hdaturhadiah_SKUInduk_ID").val( SKUInduk_ID );
		$("#txtaturhadiah_SKUInduk_Nama").val( SKUInduk_Nama );
		
		$.ajax(
		{
			type: 'POST',    
			url: "<?= base_url('SKUHadiah/GetSKUHadiah_AturHadiahMenu') ?>",
			data: 
			{
				SKUInduk_ID : SKUInduk_ID
			},
			success: function( response )
			{
				if(response)
				{
					ChAturHadiahMenu( response );
				}
			}
		});	
	}

	$("#btnaturhadiahback").click
	(
		function()
		{
			$("#MainMenu").removeAttr('style');
			$("#AturHadiah").attr('style','display: none;');
		}
	);
	
	function ChAturHadiahMenu( JSONAturHadiah )
	{
		var AturHadiah = JSON.parse( JSONAturHadiah );
		
		$("#divaturhadiah_sku").html('');
		
		$("#tableaturhadiahheader > tbody").html('');
		$("#tableaturhadiahdetail > tbody").html('');
		
		$("#divaturhadiah_detailsku").html('');
		
		for( i=0 ; i< AturHadiah.SKUMenu.length ; i++ )
		{
			var SKU_ID 			= AturHadiah.SKUMenu[i].SKU_ID;
			var SKU_Kode	 	= AturHadiah.SKUMenu[i].SKU_Kode;
			var SKU_NamaProduk 	= AturHadiah.SKUMenu[i].SKU_NamaProduk;
			var SKU_Kemasan 	= AturHadiah.SKUMenu[i].KemasanNama;
			var SKU_Satuan 		= AturHadiah.SKUMenu[i].SKU_Satuan;

			var IsNew 			= 1;//AturHadiah.AturHadiah_SKUMenu[i].IsNew;

			var strsku = '';
			
			strsku = strsku + '<tr class="Tr" id="Tr_'+ i +'">';
			strsku = strsku + '		<input type="hidden" id="hdAturHadiah_IsNew_'+ i +'" value="'+ IsNew +'" />';
			
			strsku = strsku + '		<input type="hidden" class="hdaturhadiah_SKU_ID" id="hdaturhadiah_SKU_ID_'+ i +'" value="'+ SKU_ID +'" />';
			strsku = strsku + '		<td width="50%">'+ SKU_NamaProduk +'</td>';
			strsku = strsku + '		<td width="20%">'+ SKU_Kemasan +'</td>';
			strsku = strsku + '		<td width="20%">'+ SKU_Satuan +'</td>';
			strsku = strsku + '		<td width="10%" align="center"><span class="input-group-btn">';
			strsku = strsku + '			<button type="button" class="btn btn-danger btnlihat" id="btnlihat_'+ i +'" onclick="ViewDetailAturHadiah('+ i +')"><i class="fa fa-eye"></i></button>';
			strsku = strsku + '		</span></td>';
			strsku = strsku + '</tr>';
			
			//$("#divaturhadiah_sku").append( strsku );
			$("#tableaturhadiahheader > tbody").append( strsku );

			var strdetailsku = '';
			
			strdetailsku = strdetailsku + '<div class="AturHadiahDetail" id="AturHadiahDetail_'+ i +'">';
			strdetailsku = strdetailsku + '		<table width="100%" class="table table-striped TableAturHadiahDetail" id="TableAturHadiahDetail_'+ i +'">';
			
			strdetailsku = strdetailsku + '		</table>';
			strdetailsku = strdetailsku + '</div>';
			
			$("#divaturhadiah_detailsku").append( strdetailsku );
		}
		
		if( AturHadiah.SKU_HadiahMenu != 0 )
		{
			var lsku = $(".hdaturhadiah_SKU_ID").length;
			
			for( i=0 ; i< lsku ; i++ )
			{
				var ID = $(".hdaturhadiah_SKU_ID").eq(i).val();
				var GT = 0;
				
				for( j=0 ; j< AturHadiah.SKU_HadiahMenu.length ; j++ )
				{
					var RandHadiah = Math.random() * 1000000000000000000;
					SKU_Hadiah_ID			= AturHadiah.SKU_HadiahMenu[j].SKU_Hadiah_ID;
					
					SKU_Hadiah_SKU_ID		= AturHadiah.SKU_HadiahMenu[j].SKU_Hadiah_SKU_ID;
					SKU_Hadiah_SKUInduk_ID	= AturHadiah.SKU_HadiahMenu[j].SKU_Hadiah_SKUInduk_ID;
					SKU_Hadiah_QtyHadiah	= AturHadiah.SKU_HadiahMenu[j].SKU_Hadiah_QtyHadiah;
					
					SKU_ID					= AturHadiah.SKU_HadiahMenu[j].SKU_ID;
					SKU_NamaProduk			= AturHadiah.SKU_HadiahMenu[j].SKU_NamaProduk;
					SKUInduk_ID				= AturHadiah.SKU_HadiahMenu[j].SKUInduk_ID;
					JumlahMinBeli 			= AturHadiah.SKU_HadiahMenu[j].JumlahMinBeli;
					
					if( SKU_Hadiah_SKU_ID == ID )
					{
						var str = '';
						
						str = str + '<tr>';
						str = str + '	<input type="hidden" class="txtupdateselectSKU_ID" id="txtupdateselectSKU_ID_'+ RandHadiah +'" value="'+ SKU_ID +'"/></td>';
						str = str + '	<input type="hidden" class="txtupdateselectSKU_NamaProduk" id="txtupdateselectSKU_NamaProduk_'+ RandHadiah +'" value="'+ SKU_NamaProduk +'" /></td>';
						str = str + '	<input type="hidden" class="txtupdateselectSKUInduk_ID" id="txtupdateselectSKUInduk_ID_'+ RandHadiah +'" value="'+ SKUInduk_ID +'/></td>';
						
						str = str + '	<input type="hidden" class="hdAturHadiah_x hdAturHadiah_x_'+ i +'" value="0" /></td>';
						str = str + '	<td width="50%"><input type="text" id="txtAturHadiahDetail_SKU_NamaProduk_'+ RandHadiah +'" class="form-control txtAturHadiahDetail_SKU_NamaProduk_'+ i +'" value="'+ SKU_NamaProduk +'" /></td>';
						str = str + '	<td width="20%"><input type="number" class="form-control txtAturHadiahDetail_SyaratMinBeli txtAturHadiahDetail_SyaratMinBeli_'+ i +'" value="'+ JumlahMinBeli +'" /></td>';
						str = str + '	<td width="20%"><input type="text" class="form-control txtAturHadiahDetail_QtyHadiah txtAturHadiahDetail_QtyHadiah_'+ i +'" value="'+ SKU_Hadiah_QtyHadiah +'" /></td>';
						str = str + '	<td width="10%"><button type="button" class="form-control btn btn-danger" onclick="DeleteRowAturHadiahDetail( this.parentNode.parentNode.rowIndex )"><i class="fa fa-times"></i></td>';
						str = str + '</tr>';
						
						$("#TableAturHadiahDetail_"+ i ).append( str );
						
					}
					
				}
				
				var table = document.getElementById('TableAturHadiahDetail_'+ i);
			
				if( table.rows.length > 0 )
				{
					$("#btnlihat_"+ i).removeClass('btn-danger');
					$("#btnlihat_"+ i).addClass('btn-success');
				}	
		
			}	
		}
		
		$(".AturHadiahDetail").removeAttr('style');
		$(".AturHadiahDetail").attr('style','display: none;');

		$("#AturHadiahDetail_"+ G_Idx ).removeAttr( 'style' );	
		
		var table = document.getElementById('TableAturHadiahDetail_'+ G_Idx);

		if( table.rows.length > 0 )
		{
			$("#btnlihat_"+ G_Idx).removeClass('btn-danger');
			$("#btnlihat_"+ G_Idx).addClass('btn-success');
		}
		
		
		$("#MainMenu").attr('style','display: none;');
		$("#AturHadiah").removeAttr('style');
	}
	
	function ViewDetailAturHadiah( Idx )
	{
		$(".Tr").removeAttr('style');
		$("#Tr_"+ Idx).attr('style','background-color: lightgreen;');
		
		$(".AturHadiahDetail").removeAttr( 'style' );	
		$(".AturHadiahDetail").attr('style','display: none;');
		
		G_Idx = Idx;
		
		$("#AturHadiahDetail_"+ Idx ).removeAttr('style');
	}
	
	$("#btntambahdetailaturhadiah").click
	(
		function()
		{
			var RandHadiah = Math.random() * 1000000000000000000;
			
			var str = '';
			
			str = str + '<tr>';
			str = str + '	<input type="hidden" class="txtupdateselectSKU_ID" id="txtupdateselectSKU_ID_'+ RandHadiah +'" /></td>';
			str = str + '	<input type="hidden" class="txtupdateselectSKU_NamaProduk" id="txtupdateselectSKU_NamaProduk_'+ RandHadiah +'" /></td>';
			str = str + '	<input type="hidden" class="txtupdateselectSKUInduk_ID" id="txtupdateselectSKUInduk_ID_'+ RandHadiah +'" /></td>';
			
			str = str + '	<input type="hidden" class="hdAturHadiah_x hdAturHadiah_x_'+ G_Idx +'" value="0" /></td>';
			str = str + '	<td width="50%"><input type="text" id="txtAturHadiahDetail_SKU_NamaProduk_'+ RandHadiah +'" class="form-control txtAturHadiahDetail_SKU_NamaProduk_'+ G_Idx +'" value="" /></td>';
			str = str + '	<td width="20%"><input type="number" class="form-control txtAturHadiahDetail_SyaratMinBeli txtAturHadiahDetail_SyaratMinBeli_'+ G_Idx +'" value="1" /></td>';
			str = str + '	<td width="20%"><input type="text" class="form-control txtAturHadiahDetail_QtyHadiah txtAturHadiahDetail_QtyHadiah_'+ G_Idx +'" value="1" /></td>';
			str = str + '	<td width="10%"><button type="button" class="form-control btn btn-danger" onclick="DeleteRowAturHadiahDetail( this.parentNode.parentNode.rowIndex )"><i class="fa fa-times"></i></td>';
			str = str + '</tr>';
			
			$("#TableAturHadiahDetail_"+ G_Idx ).append( str );
			
			$(".AturHadiahDetail").removeAttr('style');
			$(".AturHadiahDetail").attr('style','display: none;');

			$("#AturHadiahDetail_"+ G_Idx ).removeAttr( 'style' );	
			
			var table = document.getElementById('TableAturHadiahDetail_'+ G_Idx);

			if( table.rows.length > 0 )
			{
				$("#btnlihat_"+ G_Idx).removeClass('btn-danger');
				$("#btnlihat_"+ G_Idx).addClass('btn-success');
			}
			
			$("#txtAturHadiahDetail_SKU_NamaProduk_"+ RandHadiah ).autocomplete
			({
				//$("#txtupdateselectItem_Kode").val('');
				//$("#txtupdateselectItem_Nama").val('');
				//$("#txtupdateselectKeterangan").val('');
				
				serviceUrl: '<?= base_url("SKUHadiah/GetSKUSearch") ?>',	//controller tujuan misal satuan
				transformResult: function(response) 
				{
					var data = JSON.parse(response);
					//console.log(data.SatuanMenu);
					return {
						suggestions: $.map(data.ItemMenu, function(dataItem) 
						{	//mapping untuk tampilan ketika diketik (mungkin tampil kode - nama)
							//console.log(dataItem);
							return { value: dataItem.SKU_NamaProduk, SKU_ID: dataItem.SKU_ID, SKU_NamaProduk: dataItem.SKU_NamaProduk, SKUInduk_ID: dataItem.SKUInduk_ID };
						}),
						
					};
				},
				onSelect : function(suggestion){	//ketika dipilih
					$( "#txtupdateselectSKU_ID_"+ RandHadiah ).val( suggestion.SKU_ID );		//masukkan ke textbox
					$( "#txtupdateselectSKU_NamaProduk_"+ RandHadiah ).val( suggestion.SKU_NamaProduk );		//masukkan ke textbox
					$( "#txtupdateselectSKUInduk_ID_"+ RandHadiah ).val( suggestion.SKUInduk_ID );		//masukkan ke textbox
				},
				onSearchComplete: function (query, suggestion) 
				{
					for( i=0 ; i<suggestion.length ; i++ )
					{
						var SKU_NamaProduk = suggestion[i].SKU_NamaProduk;
						
						if( SKU_NamaProduk != query )
						{
							$( "#txtupdateselectSKU_ID_"+ RandHadiah ).val('');		//masukkan ke textbox
							$( "#txtupdateselectSKU_NamaProduk_"+ RandHadiah ).val('');		//masukkan ke textbox
							$( "#txtupdateselectSKUInduk_ID_"+ RandHadiah ).val('');	
						}
					}
				}
			});
		}
	);
	
	function DeleteRowAturHadiahDetail( rowIdx )
	{
		var table = document.getElementById('TableAturHadiahDetail_'+ G_Idx);
		table.deleteRow( rowIdx );
		
		if( table.rows.length == 0 )
		{
			$("#btnlihat_"+ G_Idx).removeClass('btn-success');
			$("#btnlihat_"+ G_Idx).addClass('btn-danger');
		}
		
		CheckQtyAturHadiahDetail( G_Idx );
	}
	
	$("#btnaturhadiahsimpan").click
	(
		function()
		{
			var numerror = 1;
			
			var lcol = $(".btnlihat").length;
			
			for( i=0 ; i< lcol ; i++ )
			{
				var Flg = $("#hdAturHadiah_IsNew_"+ i).val();
						
				if( Flg == 1 )
				{
					if( $(".btnlihat").hasClass('btn-success') )
					{
						numerror = 0;
					}
				}
			}
			
			if( numerror == 1 )
			{
				var msg = 'Salah satu atau semua SKU harus memiliki Detail.';
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
				var lheader = $(".Tr").length;

				for( i=0 ; i< lheader ; i++ )
				{
					var ldetailtable = $(".hdAturHadiah_x_"+ i).length;

					for( j=0 ; j<ldetailtable ; j++ )
					{
						var SyaratMinBeli 	= $(".txtAturHadiahDetail_SyaratMinBeli").eq(j).val();
						var QtyHadiah 		= $(".txtAturHadiahDetail_QtyHadiah").eq(j).val();
						
						var SKU_ID 			= $(".txtupdateselectSKU_ID").eq(j).val();
						var SKU_NamaProduk 	= $(".txtupdateselectSKU_NamaProduk").eq(j).val();
						var SKUInduk_ID 	= $(".txtupdateselectSKUInduk_ID").eq(j).val();
						
						if( SKU_ID == "" || SyaratMinBeli == 0 || QtyHadiah == 0 )
						{
							numerror = 2;
						}
					}
				}
				
				if( numerror == 2 )
				{
					var msg = 'Nama Produk harus terisi dan valid. Syarat Minimum Pembelian dan Qty Hadiah harus lebih besar dari 0.';
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
					$("#previewaturhadiahsimpan").modal('show');
				}
			}
		}
	);
	
	$("#btnyesaturhadiahsimpan").click
	(
		function()
		{
			var numerror = 1;
			
			var lcol = $(".btnlihat").length;
			
			for( i=0 ; i< lcol ; i++ )
			{
				if( $(".btnlihat").hasClass('btn-success') )
				{
					numerror = 0;
				}
			}
			
			if( numerror == 1 )
			{
				var msg = 'Salah satu atau semua SKU harus memiliki Detail.';
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
				var lheader = $(".Tr").length;
				
				for( i=0 ; i< lheader ; i++ )
				{
					var ldetailtable = $(".hdAturHadiah_x_"+ i).length;

					for( j=0 ; j<ldetailtable ; j++ )
					{
						var SyaratMinBeli 	= $(".txtAturHadiahDetail_SyaratMinBeli").eq(j).val();
						var QtyHadiah 		= $(".txtAturHadiahDetail_QtyHadiah").eq(j).val();
						
						var SKU_ID 			= $(".txtupdateselectSKU_ID").eq(j).val();
						var SKU_NamaProduk 	= $(".txtupdateselectSKU_NamaProduk").eq(j).val();
						var SKUInduk_ID 	= $(".txtupdateselectSKUInduk_ID").eq(j).val();
						
						if( SKU_ID == "" || SyaratMinBeli == 0 || QtyHadiah == 0 )
						{
							numerror = 2;
						}
					}
				}
				
				if( numerror == 2 )
				{
					var msg = 'Detail Stok, Gudang, dan Tanggal Expired harus terisi';
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
					var arrAS_SKU_Hadiah = [];
					
					var HSKUInduk_ID = $("#hdaturhadiah_SKUInduk_ID").val();
					
					for( i=0 ; i< lheader ; i++ )
					{
						var ldetailtable = $(".hdAturHadiah_x_"+ i).length;

						for( j=0 ; j<ldetailtable ; j++ )
						{
							var SyaratMinBeli 	= $(".txtAturHadiahDetail_SyaratMinBeli").eq(j).val();
							var QtyHadiah 		= $(".txtAturHadiahDetail_QtyHadiah").eq(j).val();
							
							var SKU_ID 			= $(".txtupdateselectSKU_ID").eq(j).val();
							var SKU_NamaProduk 	= $(".txtupdateselectSKU_NamaProduk").eq(j).val();
							var SKUInduk_ID 	= $(".txtupdateselectSKUInduk_ID").eq(j).val();
							
							if( SKU_ID == "" || SyaratMinBeli == 0 || QtyHadiah == 0 )
							{
								numerror = 2;
							}
						}
						
						var HSKU_ID = $("#hdaturhadiah_SKU_ID_"+ i ).val();
						var ldetailtable = $(".cbAturHadiahDetail_GudangVA_"+ i).length;
						
						var Flg = $("#hdAturHadiah_IsNew_"+ i).val();
						
						if( Flg == 1 )
						{
							var ldetailtable = $(".hdAturHadiah_x_"+ i).length;

							for( j=0 ; j<ldetailtable ; j++ )
							{
								if( $(".txtAturHadiahDetail_QtyHadiah_"+ i).eq(j).prop('disabled') == false )
								{
									var SyaratMinBeli 	= $(".txtAturHadiahDetail_SyaratMinBeli").eq(j).val();
									var QtyHadiah 		= $(".txtAturHadiahDetail_QtyHadiah").eq(j).val();
									
									var DSKU_ID 		= $(".txtupdateselectSKU_ID").eq(j).val();
									var SKU_NamaProduk 	= $(".txtupdateselectSKU_NamaProduk").eq(j).val();
									var DSKUInduk_ID 	= $(".txtupdateselectSKUInduk_ID").eq(j).val();
								
									if( SKU_ID != "" && SyaratMinBeli > 0 && QtyHadiah > 0 )
									{
										arrAS_SKU_Hadiah.push({ HSKU_ID : HSKU_ID, HQtyHadiah : QtyHadiah,
																DSKU_ID : DSKU_ID, DSKUInduk_ID : DSKUInduk_ID, DSyaratMinBeli : SyaratMinBeli } );
									}
								}
							}
							
						}
					}
					
					$.ajax(
					{
						type: 'POST',    
						url: "<?= base_url('SKUHadiah/SimpanAturHadiah') ?>",
						data: 
						{
							SKUInduk_ID : HSKUInduk_ID,
							arrAS_SKU_Hadiah : arrAS_SKU_Hadiah
						},
						success: function( response )
						{
							if(response)
							{
								var msg = 'Hadiah berhasil di set.';
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
					});	
				}
			}
		}
	);
	
	
</script>
</script>