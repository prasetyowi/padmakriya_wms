<script type="text/javascript">	
	var arrKonversi_SKU = [];
	var GK_Idx = 0;
	
	function KonversiSKUInduk( sku_induk_id, sku_induk_nama )
	{
		$("#hdkonversi_sku_induk_id").val( sku_induk_id );
		$("#txtkonversi_sku_induk_nama").val( sku_induk_nama );
		
		$.ajax(
		{
			type: 'POST',    
			url: "<?= base_url('SKU/GetSKUKonversiMenu') ?>",
			data: 
			{
				sku_induk_id : sku_induk_id
			},
			success: function( response )
			{
				if(response)
				{
					ChKonversiMenu( response );
				}
			}
		});	
	}
	
	function ChKonversiMenu( JSONKonversi )
	{
		var Konversi = JSON.parse( JSONKonversi );

		$("#divkonversi_um").html('');
		$("#divkonversi_sku").html('');
		
		$("#tablekonversiheader > tbody").html('');
		$("#tablekonversidetail > tbody").html('');
			
		$("#divkonversi_detailsku").html('');

		if( Konversi.Konversi_SKUMenu != 0 )
		{
			for( i=0 ; i<Konversi.Konversi_SKUMenu.length ; i++ )
			{
				var sku_id 			= Konversi.Konversi_SKUMenu[i].sku_id; 
				var sku_nama_produk = Konversi.Konversi_SKUMenu[i].sku_nama_produk;
				var kemasan_id 		= Konversi.Konversi_SKUMenu[i].kemasan_id;
				var kemasan_nama 	= Konversi.Konversi_SKUMenu[i].kemasan_nama;
				
				arrKonversi_SKU.push({ sku_id : sku_id, sku_nama_produk : sku_nama_produk, kemasan_id : kemasan_id, kemasan_nama : kemasan_nama });
			
				var strsku = '';
				
				strsku = strsku + '<tr class="Konversi_Tr" id="Konversi_Tr_'+ i +'">';
				
				strsku = strsku + '		<input type="hidden" class="hdkonversi_sku_id" id="hdkonversi_sku_id_'+ i +'" value="'+ sku_id +'" />';
				strsku = strsku + '		<input type="hidden" class="hdkonversi_kemasan_id" id="hdkonversi_kemasan_id_'+ i +'" value="'+ kemasan_id +'" />';
				strsku = strsku + '		<td width="50%">'+ sku_nama_produk +'</td>';
				//strsku = strsku + '		<td width="30%">';
				//strsku = strsku + '			<input type="text" id="txtkonversi_kemasan_nama_'+ i +'" class="form-control txtkonversi_kemasan_nama" value="'+ kemasan_nama +'" disabled />';
				//strsku = strsku + '		</td>';
				strsku = strsku + '		<td width="20%" align="center"><span class="input-group-btn">';
				strsku = strsku + '			<button type="button" class="btn btn-danger btnkonversilihat" id="btnkonversilihat_'+ i +'" onclick="ViewDetailKonversi('+ i +')"><i class="fa fa-exchange"></i></button>';
				strsku = strsku + '		</span></td>';
				strsku = strsku + '</tr>';
				
				//$("#divaturstock_sku").append( strsku );
				$("#tablekonversiheader > tbody").append( strsku );

				var strdetailsku = '';
				
				strdetailsku = strdetailsku + '<div class="KonversiDetail" id="KonversiDetail_'+ i +'">';
				strdetailsku = strdetailsku + '		<table width="100%" class="table table-striped TableKonversiDetail" id="TableKonversiDetail_'+ i +'">';
				
				strdetailsku = strdetailsku + '		</table>';
				strdetailsku = strdetailsku + '</div>';
				
				$("#divkonversi_detailsku").append( strdetailsku );
				
			}
		}
		
		if( Konversi.SKU_KonversiMenu != 0 )
		{
			var strk_sku = '';
			for( i=0 ; i< arrKonversi_SKU.length ; i++ )
			{
				var sku_id 			= arrKonversi_SKU[i].sku_id;
				var sku_nama_produk = arrKonversi_SKU[i].sku_nama_produk;
				
				strk_sku = strk_sku + '<option value="'+ sku_id +'">'+ sku_nama_produk +'</option>';
			}
			
			var lsku = $(".hdkonversi_sku_id").length;
			
			for( i=0 ; i< lsku ; i++ )
			{
				var ID = $(".hdkonversi_sku_id").eq(i).val();
				var Konv_ID = $(".hdkonversi_SKUKonv_ID").eq(i).val();
				
				for( j=0 ; j< Konversi.IsiSKU_KonversiMenu.length ; j++ )
				{
					sku_id_utama						= Konversi.IsiSKU_KonversiMenu[j].sku_id_utama;
					sku_id								= Konversi.IsiSKU_KonversiMenu[j].sku_id;
					sku_nama_produk						= Konversi.IsiSKU_KonversiMenu[j].sku_nama_produk;
					kemasan_nama						= Konversi.IsiSKU_KonversiMenu[j].kemasan_nama;
					sku_konversi_detail_nilai_konversi 	= Konversi.IsiSKU_KonversiMenu[j].sku_konversi_detail_nilai_konversi;
		
					if( sku_id_utama == ID )
					{
						var str = '';
						
						str = str + '<tr>';
						str = str + '	<input type="hidden" class="hdKonversi_x_'+ i +'" /></td>';
						
						str = str + '	<td width="50%"><select class="form-control cbKonversiDetail_sku_nama_produk_'+ i +'">'+ strk_sku +'</select></td>';
						str = str + '	<td width="20%"><input type="text" class="form-control" value="'+ kemasan_nama +'" /></td>';
						str = str + '	<td width="15%"><input type="text" class="form-control txtKonversiDetail_sku_konversi_detail_nilai_konversi_'+ i +'" value="'+ sku_konversi_detail_nilai_konversi +'" onchange="CheckQtyKonversi('+ i +', this.parentNode.parentNode.rowIndex )" /></td>';
						str = str + '	<td width="15%"><button type="button" class="form-control btn btn-danger" onclick="DeleteRowKonversiDetail( this.parentNode.parentNode.rowIndex )"><i class="fa fa-times"></i></td>';
						str = str + '</tr>';
			/*
						str = str + '<tr>';
						str = str + '	<input type="hidden" class="hdAturStock_x_'+ i +'" value="0" /></td>';
						str = str + '	<td width="15%"><input type="text" class="form-control txtAturStockDetail_QtyStock_'+ i +'" value="'+ SKU_Stock_OnHand +'" min="0" disabled="disabled" onchange="CheckQtyAturStockDetail('+ i +')" /></td>';
						str = str + '	<td width="30%"><select class="form-control cbAturStockDetail_GudangVA_'+ i +'" disabled="disabled">'+ strGudangVA +'</select></td>';
						str = str + '	<td width="40%"><input type="text" class="form-control txtAturStockDetail_TglExpired_'+ i +'" value="'+ SKU_ExpireDate +'" disabled="disabled" /></td>';
						str = str + '	<td width="15%"><button type="button" style="display: none;" class="form-control btn btn-danger" onclick="DeleteRowAturStockDetail( this.parentNode.parentNode.rowIndex )"><i class="fa fa-times"></i></td>';
						str = str + '</tr>';
					*/	
						$("#TableKonversiDetail_"+ i ).append( str );
						
						$(".cbKonversiDetail_sku_nama_produk_"+ i ).eq(j).val( sku_id );
						
					}
					
				}
				
				var table = document.getElementById('TableKonversiDetail_'+ i);
			
				if( table.rows.length > 0 )
				{
					$("#btnkonversilihat_"+ i).removeClass('btn-danger');
					$("#btnkonversilihat_"+ i).addClass('btn-success');
				}	
		
			}	
		}
		/*
		if( Konversi.SKU_KonversiMenu != 0 )
		{
			for( i=0 ; i<Konversi.SKU_KonversiMenu.length ; i++ )
			{
				
			}			
		}
		
		if( Konversi.SKU_KonversiDetailMenu != 0 )
		{
			for( i=0 ; i<Konversi.SKU_KonversiDetailMenu.length ; i++ )
			{
				
			}				
		}
		
		if( Konversi.SKU_SatuanTerkecilMenu != 0 )
		{
			for( i=0 ; i<Konversi.SKU_KonversiDetailMenu.length ; i++ )
			{
				
			}	
		}
		*/
			$(".KonversiDetail").removeAttr('style');
			$(".KonversiDetail").attr('style','display: none;');

			$("#KonversiDetail_"+ GK_Idx ).removeAttr( 'style' );	
			
			var table = document.getElementById('TableKonversiDetail_'+ GK_Idx);

			if( table.rows.length > 0 )
			{
				$("#btnkonversilihat_"+ GK_Idx).removeClass('btn-danger');
				$("#btnkonversilihat_"+ GK_Idx).addClass('btn-success');
			}
				
			$('.txtKonversiDetail_TglExpired_'+ GK_Idx).datetimepicker
			({
				defaultDate: new Date(),
				format: 'DD/MM/YYYY',
				
			});		
		//}
		
		$("#MainMenu").attr('style','display: none;');
		$("#anylist_letter").attr('style','display: none;');
		$("#Konversi").removeAttr('style');
	}
	
	function ViewDetailKonversi( Idx )
	{
	
		$(".Konversi_Tr").removeAttr('style');
		$("#Konversi_Tr_"+ Idx).attr('style','background-color: lightgreen;');
		
		$(".KonversiDetail").removeAttr( 'style' );	
		$(".KonversiDetail").attr('style','display: none;');
		
		GK_Idx = Idx;
		
		$("#KonversiDetail_"+ Idx ).removeAttr('style');
	}

	$("#btntambahdetailkonversi").click
	(
		function()
		{
			var strk_sku = '';
			for( i=0 ; i< arrKonversi_SKU.length ; i++ )
			{
				var sku_id 			= arrKonversi_SKU[i].sku_id;
				var sku_nama_produk 	= arrKonversi_SKU[i].sku_nama_produk;
				
				strk_sku = strk_sku + '<option value="'+ sku_id +'">'+ sku_nama_produk +'</option>';
			}
			
			var str = '';
			
			str = str + '<tr>';
			str = str + '	<input type="hidden" class="hdKonversi_x_'+ GK_Idx +'" /></td>';
			str = str + '	<td width="70%"><select class="form-control cbKonversiDetail_sku_nama_produk_'+ GK_Idx +'">'+ strk_sku +'</select></td>';
			str = str + '	<td width="15%"><input type="text" class="form-control txtKonversiDetail_sku_konversi_detail_nilai_konversi_'+ GK_Idx +'" value="1" onchange="CheckQtyKonversi('+ GK_Idx +', this.parentNode.parentNode.rowIndex )" /></td>';
			str = str + '	<td width="15%"><button type="button" class="form-control btn btn-danger" onclick="DeleteRowKonversiDetail( this.parentNode.parentNode.rowIndex )"><i class="fa fa-times"></i></td>';
			str = str + '</tr>';
			
			$("#TableKonversiDetail_"+ GK_Idx ).append( str );
			
			$(".KonversiDetail").removeAttr('style');
			$(".KonversiDetail").attr('style','display: none;');

			$("#KonversiDetail_"+ GK_Idx ).removeAttr( 'style' );	
			
			var table = document.getElementById('TableKonversiDetail_'+ GK_Idx);

			if( table.rows.length > 0 )
			{
				$("#btnkonversilihat_"+ GK_Idx).removeClass('btn-danger');
				$("#btnkonversilihat_"+ GK_Idx).addClass('btn-success');
			}
		}
	);
	
	$("#btnkback").click
	(
		function()
		{
			$("#previewkembalikelistskuinduk").modal('show');
		}
	);
	
	function DeleteRowKonversiDetail( rowIdx )
	{
		var table = document.getElementById('TableKonversiDetail_'+ GK_Idx);
		table.deleteRow( rowIdx );
		
		if( table.rows.length == 0 )
		{
			$("#btnkonversilihat_"+ GK_Idx).removeClass('btn-success');
			$("#btnkonversilihat_"+ GK_Idx).addClass('btn-danger');
		}
		
	}
	
	function CheckQtyKonversi( Idx, rowIdx )
	{
		var Qty = $(".txtKonversiDetail_sku_konversi_detail_nilai_konversi_"+ Idx ).eq( rowIdx ).val();
		
		if( Qty < 1 )
		{
			$(".txtKonversiDetail_sku_konversi_detail_nilai_konversi_"+ Idx ).eq( rowIdx ).val( 1 );
		}
	}
	
	$("#btnkembalikonversi").click
	(
		function()
		{
			$("#tablekonversiheader > tbody").html('');
			$("#tablekonversidetail > tbody").html('');
			
			$("#MainMenu").removeAttr('style');
			$("#anylist_letter").removeAttr('style');
			$("#Konversi").removeAttr('style');
		}
	);
	
	$("#btnkonversisimpan").click
	(
		function()
		{
			var numerror = 1;
			
			var lcol = $(".btnkonversilihat").length;
			
			for( i=0 ; i< lcol ; i++ )
			{
				if( $(".btnkonversilihat").hasClass('btn-success') )
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
				var lheader = $(".Konversi_Tr").length;

				for( i=0 ; i< lheader ; i++ )
				{
					var ldetailtable = $(".cbKonversiDetail_sku_nama_produk_"+ i).length;

					for( j=0 ; j<ldetailtable ; j++ )
					{
						if( $(".cbKonversiDetail_sku_nama_produk_"+ i).eq(j).prop('disabled') == false )
						{
							var sku_id = $(".cbKonversiDetail_sku_nama_produk_"+ i +" option:selected").eq(j).val();
							var sku_nama_produk = $(".cbKonversiDetail_sku_nama_produk_"+ i +" option:selected").eq(j).html();
							var sku_konversi_detail_nilai_konversi = $(".txtKonversiDetail_sku_konversi_detail_nilai_konversi_"+ i).eq(j).val();
							
							if( sku_konversi_detail_nilai_konversi == '' || sku_konversi_detail_nilai_konversi < 1 || sku_id == '' || sku_nama_produk == '' )
							{
								numerror = 2;
							}
						}
					}
				}
				
				if( numerror == 2 )
				{
					var msg = 'SKU Detail dan Nilai Konversi harus terisi';
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
					var arr_i = [];
					var arrsku_id = [];
					
					for( i=0 ; i< lheader ; i++ )
					{
						//arr_i.push( i );
						
						var ldetailtable = $(".cbKonversiDetail_sku_nama_produk_"+ i).length;

						for( j=0 ; j<ldetailtable ; j++ )
						{
							if( $(".cbKonversiDetail_sku_nama_produk_"+ i).eq(j).prop('disabled') == false )
							{
								var sku_id 								= $(".cbKonversiDetail_sku_nama_produk_"+ i +" option:selected").eq(j).val();
								var sku_nama_produk 					= $(".cbKonversiDetail_sku_nama_produk_"+ i +" option:selected").eq(j).html();
								var sku_konversi_detail_nilai_konversi 	= $(".txtKonversiDetail_sku_konversi_detail_nilai_konversi_"+ i).eq(j).val();

								if( arrsku_id.includes( i + ' - ' + sku_id ) )
								{
									numerror = 3; 
								}
								else
								{
									arrsku_id.push( i + ' - ' + sku_id );
								}
							}
						}
					}
					
					if( numerror == 3 )
					{
						var msg = 'SKU yang dipilih tidak boleh kembar.';
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
						$("#previewkonversisimpan").modal('show');
					}
				}
			}
		}
	);
	
	$("#btnyeskonversisimpan").click
	(
		function()
		{
			var numerror = 1;
			
			var lcol = $(".btnkonversilihat").length;
			
			for( i=0 ; i< lcol ; i++ )
			{
				if( $(".btnkonversilihat").hasClass('btn-success') )
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
				var lheader = $(".Konversi_Tr").length;

				for( i=0 ; i< lheader ; i++ )
				{
					var ldetailtable = $(".cbKonversiDetail_sku_nama_produk_"+ i).length;

					for( j=0 ; j<ldetailtable ; j++ )
					{
						if( $(".cbKonversiDetail_sku_nama_produk_"+ i).eq(j).prop('disabled') == false )
						{
							var sku_id = $(".cbKonversiDetail_sku_nama_produk_"+ i +" option:selected").eq(j).val();
							var sku_nama_produk = $(".cbKonversiDetail_sku_nama_produk_"+ i +" option:selected").eq(j).html();
							var sku_konversi_detail_nilai_konversi = $(".txtKonversiDetail_sku_konversi_detail_nilai_konversi_"+ i).eq(j).val();
							
							if( sku_konversi_detail_nilai_konversi == '' || sku_konversi_detail_nilai_konversi < 1 || sku_id == '' || sku_nama_produk == '' )
							{
								numerror = 2;
							}
						}
					}
				}
				
				if( numerror == 2 )
				{
					var msg = 'SKU Detail dan sku_konversi_detail_nilai_konversi harus terisi';
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
					var arrK_SKU_KonversiD = [];
					var arrK_SKU_Konversi = [];
					
					var sku_induk_id = $("#hdkonversi_sku_induk_id").val();
					
					
					for( i=0 ; i< lheader ; i++ )
					{
						var sku_id = $("#hdkonversi_sku_id_"+ i ).val();
						var kemasan_id = $("#hdkonversi_kemasan_id_"+ i ).val();
						
						
						var ldetailtable = $(".cbKonversiDetail_sku_nama_produk_"+ i).length;
						
						if( ldetailtable != 0 )
						{
							arrK_SKU_Konversi.push({ sku_id : sku_id, kemasan_id : kemasan_id, id : i });
						}
						for( j=0 ; j<ldetailtable ; j++ )
						{
							if( $(".cbKonversiDetail_sku_nama_produk_"+ i).eq(j).prop('disabled') == false )
							{
								var sku_id = $(".cbKonversiDetail_sku_nama_produk_"+ i +" option:selected").eq(j).val();
								var sku_nama_produk = $(".cbKonversiDetail_sku_nama_produk_"+ i +" option:selected").eq(j).html();
								var sku_konversi_detail_nilai_konversi = $(".txtKonversiDetail_sku_konversi_detail_nilai_konversi_"+ i).eq(j).val();

								if( sku_konversi_detail_nilai_konversi != '' && sku_konversi_detail_nilai_konversi >= 1 && sku_id != '' && sku_nama_produk != '' )
								{
									arrK_SKU_KonversiD.push( { 	sku_id : sku_id, sku_konversi_detail_nilai_konversi : sku_konversi_detail_nilai_konversi, sku_konversi_detail_is_aktif : 1, idD : i } );
								}
							}
						}
					}
					
					$.ajax(
					{
						type: 'POST',    
						url: "<?= base_url('SKU/SimpanKonversi') ?>",
						data: 
						{
							sku_induk_id : sku_induk_id,
							
							arrK_SKU_Konversi : arrK_SKU_Konversi,
							arrK_SKU_KonversiD : arrK_SKU_KonversiD
						},
						success: function( response )
						{
							if(response)
							{
								var msg = 'Konversi berhasil terisi';
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