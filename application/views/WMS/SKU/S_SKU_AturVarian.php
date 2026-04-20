<script type="text/javascript">	
	var arrAturStock_GudangVA = [];
	var G_Idx = 0;
	
	function AturVarian( SKUInduk_ID, SKUInduk_Nama )
	{
		$("#hdaturvarian_SKUInduk_ID").val( SKUInduk_ID );
		$("#txtaturvarian_SKUInduk_Nama").val( SKUInduk_Nama );
		
		var UnitMandiri_ID = $("#cbaturstock_unitmandiri option:selected").val();
		
		$.ajax(
		{
			type: 'POST',    
			url: "<?= base_url('SKUBertahap/GetSKUBertahap_AturStockMenu') ?>",
			data: 
			{
				SKUInduk_ID : SKUInduk_ID
			},
			success: function( response )
			{
				if(response)
				{
					ChAturStockMenu( response );
				}
			}
		});	
	}
	
	$("#btnaturstockback").click
	(
		function()
		{
			$("#MainMenu").removeAttr('style');
			$("#AturStock").attr('style','display: none;');
		}
	);
	
	function ChAturStockMenu( JSONAturStock )
	{
		var AturStock = JSON.parse( JSONAturStock );
		
		$("#divaturstock_um").html('');
		$("#divaturstock_sku").html('');
		
		$("#tableaturstokheader > tbody").html('');
		$("#tableaturstokdetail > tbody").html('');
		
		$("#divaturstock_detailsku").html('');
			
		arrAturStock_GudangVA = [];
		
		if( AturStock.AturStock_UMMenu != 0 )
		{
			$("#cbaturstock_unitmandiri").html('');
			for( i=0 ; i< AturStock.AturStock_UMMenu.length ; i++ )
			{
				var UnitMandiri_ID 		= AturStock.AturStock_UMMenu[i].UnitMandiri_ID; 
				var UnitMandiri_Name 	= AturStock.AturStock_UMMenu[i].UnitMandiri_Name;

				var str = '';

				str	= str + '<option value="'+ UnitMandiri_ID +'">'+ UnitMandiri_Name +'</option>';
				
				$("#cbaturstock_unitmandiri").append( str );
			}
		}
		
		if( $("#cbaturstock_unitmandiri").html() == '' )
		{
			$("#cbaturstock_unitmandiri").html('** Tidak ada data Unit Mandiri pada SKU Induk ini **');
		}
		
		if( AturStock.AturStock_GudangVAMenu != 0 )
		{
			for( i=0 ; i<AturStock.AturStock_GudangVAMenu.length ; i++ )
			{
				var GudangVA_ID 	= AturStock.AturStock_GudangVAMenu[i].GUDANGVA_ID;
				var GudangVA_Nama 	= AturStock.AturStock_GudangVAMenu[i].GudangVA_nama;
				
				arrAturStock_GudangVA.push( { GudangVA_ID : GudangVA_ID, GudangVA_Nama : GudangVA_Nama } );
			}
		}
		
		for( i=0 ; i< AturStock.AturStock_SKUMenu.length ; i++ )
		{
			var SKU_ID 			= AturStock.AturStock_SKUMenu[i].SKU_ID; 
			var SKU_NamaProduk 	= AturStock.AturStock_SKUMenu[i].SKU_NamaProduk;
			
			var IsNew 			= 1;//AturStock.AturStock_SKUMenu[i].IsNew;

			var strsku = '';
			
			strsku = strsku + '<tr class="Tr" id="Tr_'+ i +'">';
			strsku = strsku + '		<input type="hidden" id="hdAturStock_IsNew_'+ i +'" value="'+ IsNew +'" />';
			
			strsku = strsku + '		<input type="hidden" class="hdaturstock_SKU_ID" id="hdaturstock_SKU_ID_'+ i +'" value="'+ SKU_ID +'" />';
			strsku = strsku + '		<td width="50%">'+ SKU_NamaProduk +'</td>';
			strsku = strsku + '		<td width="20%">';
			strsku = strsku + '			<input type="text" id="txtaturstock_TotalStock_'+ i +'" class="form-control txtaturstock_TotalStock" value="0" disabled />';
			strsku = strsku + '		</td>';
			strsku = strsku + '		<td width="30%" align="center"><span class="input-group-btn">';
			strsku = strsku + '			<button type="button" class="btn btn-danger btnlihat" id="btnlihat_'+ i +'" onclick="ViewDetailAturStock('+ i +')"><i class="fa fa-eye"></i> Detail</button>';
			strsku = strsku + '		</span></td>';
			strsku = strsku + '</tr>';
			
			//$("#divaturstock_sku").append( strsku );
			$("#tableaturstokheader > tbody").append( strsku );

			var strdetailsku = '';
			
			strdetailsku = strdetailsku + '<div class="AturStockDetail" id="AturStockDetail_'+ i +'">';
			strdetailsku = strdetailsku + '		<table width="100%" class="table table-striped TableAturStockDetail" id="TableAturStockDetail_'+ i +'">';
			
			strdetailsku = strdetailsku + '		</table>';
			strdetailsku = strdetailsku + '</div>';
			
			$("#divaturstock_detailsku").append( strdetailsku );
		}
		
		
		if( AturStock.SKU_UnitMandiriMenu != 0 )
		{
			
			var strGudangVA = '';
			for( i=0 ; i< arrAturStock_GudangVA.length ; i++ )
			{
				var GudangVA_ID 	= arrAturStock_GudangVA[i].GudangVA_ID;
				var GudangVA_Nama 	= arrAturStock_GudangVA[i].GudangVA_Nama;
				
				strGudangVA = strGudangVA + '<option value="'+ GudangVA_ID +'">'+ GudangVA_Nama +'</option>';
			}
			
			var lsku = $(".hdaturstock_SKU_ID").length;
			
			for( i=0 ; i< lsku ; i++ )
			{
				var ID = $(".hdaturstock_SKU_ID").eq(i).val();
				var GT = 0;
				
				for( j=0 ; j< AturStock.SKU_UnitMandiriMenu.length ; j++ )
				{
					SKU_ID				= AturStock.SKU_UnitMandiriMenu[j].SKU_ID;
					UnitMandiri_ID		= AturStock.SKU_UnitMandiriMenu[j].UNITMANDIRI_ID;
					GUDANGVA_ID 		= AturStock.SKU_UnitMandiriMenu[j].GUDANGVA_ID;
					SKU_Stock_OnHand	= parseInt( AturStock.SKU_UnitMandiriMenu[j].SKU_Stock_OnHand );
					SKU_ExpireDate		= AturStock.SKU_UnitMandiriMenu[j].SKU_ExpireDate;
					
					SKU_ExpireDate = SKU_ExpireDate.split(' ');
					yyyymmdd = SKU_ExpireDate[0];
					yyyymmdd = yyyymmdd.split('-');
					yyyy = yyyymmdd[0];
					mm = yyyymmdd[1];
					dd = yyyymmdd[2];
					SKU_ExpireDate = dd + '/' + mm + '/' + yyyy;
					
					if( SKU_ID == ID )
					{
						GT = GT + parseInt( SKU_Stock_OnHand );
					
						var str = '';
						
						str = str + '<tr>';
						str = str + '	<input type="hidden" class="hdAturStock_x_'+ i +'" value="0" /></td>';
						str = str + '	<td width="15%"><input type="text" class="form-control txtAturStockDetail_QtyStock_'+ i +'" value="'+ SKU_Stock_OnHand +'" min="0" disabled="disabled" onchange="CheckQtyAturStockDetail('+ i +')" /></td>';
						str = str + '	<td width="30%"><select class="form-control cbAturStockDetail_GudangVA_'+ i +'" disabled="disabled">'+ strGudangVA +'</select></td>';
						str = str + '	<td width="40%"><input type="text" class="form-control txtAturStockDetail_TglExpired_'+ i +'" value="'+ SKU_ExpireDate +'" disabled="disabled" /></td>';
						str = str + '	<td width="15%"><button type="button" style="display: none;" class="form-control btn btn-danger" onclick="DeleteRowAturStockDetail( this.parentNode.parentNode.rowIndex )"><i class="fa fa-times"></i></td>';
						str = str + '</tr>';
						
						$("#TableAturStockDetail_"+ i ).append( str );
					}
					
				}
				
				$("#txtaturstock_TotalStock_"+ i ).val( GT );
				
				var table = document.getElementById('TableAturStockDetail_'+ i);
			
				if( table.rows.length > 0 )
				{
					$("#btnlihat_"+ i).removeClass('btn-danger');
					$("#btnlihat_"+ i).addClass('btn-success');
				}	
		
			}	
		}
		
			
			$(".AturStockDetail").removeAttr('style');
			$(".AturStockDetail").attr('style','display: none;');

			$("#AturStockDetail_"+ G_Idx ).removeAttr( 'style' );	
			
			var table = document.getElementById('TableAturStockDetail_'+ G_Idx);

			if( table.rows.length > 0 )
			{
				$("#btnlihat_"+ G_Idx).removeClass('btn-danger');
				$("#btnlihat_"+ G_Idx).addClass('btn-success');
			}
			
			//$("#AturStockDetail_"+ G_Idx ).append( str );		
			$('.txtAturStockDetail_TglExpired_'+ G_Idx).datetimepicker
			({
				defaultDate: new Date(),
				format: 'DD/MM/YYYY',
				
			});		
		//}
		
		$("#MainMenu").attr('style','display: none;');
		$("#AturStock").removeAttr('style');
	}
	
	$("#cbaturstock_unitmandiri").change
	(
		function()
		{
			var SKUInduk_ID = $("#hdaturstock_SKUInduk_ID").val();
			var UnitMandiri_ID = $("#cbaturstock_unitmandiri option:selected").val()
			
			$.ajax(
			{
				type: 'POST',    
				url: "<?= base_url('SKUBertahap/GetSKUBertahap_AturStockMenu') ?>",
				data: 
				{
					SKUInduk_ID : SKUInduk_ID,
					UnitMandiri_ID : UnitMandiri_ID
				},
				success: function( response )
				{
					if(response)
					{
						ChAturStockMenu( response );
					}
				}
			});	
		}
	);
	
	function ViewDetailAturStock( Idx )
	{
		$(".Tr").removeAttr('style');
		$("#Tr_"+ Idx).attr('style','background-color: lightgreen;');
		
		$(".AturStockDetail").removeAttr( 'style' );	
		$(".AturStockDetail").attr('style','display: none;');
		
		G_Idx = Idx;
		
		$("#AturStockDetail_"+ Idx ).removeAttr('style');
	}
	
	$("#btntambahdetailaturstock").click
	(
		function()
		{
			var strGudangVA = '';
			for( i=0 ; i< arrAturStock_GudangVA.length ; i++ )
			{
				var GudangVA_ID 	= arrAturStock_GudangVA[i].GudangVA_ID;
				var GudangVA_Nama 	= arrAturStock_GudangVA[i].GudangVA_Nama;
				
				strGudangVA = strGudangVA + '<option value="'+ GudangVA_ID +'">'+ GudangVA_Nama +'</option>';
			}
			
			var str = '';
			
			str = str + '<tr>';
			str = str + '	<input type="hidden" class="hdAturStock_x_'+ G_Idx +'" value="0" /></td>';
			str = str + '	<td width="15%"><input type="text" class="form-control txtAturStockDetail_QtyStock_'+ G_Idx +'" value="0" min="0" onchange="CheckQtyAturStockDetail('+ G_Idx +')" /></td>';
			str = str + '	<td width="30%"><select class="form-control cbAturStockDetail_GudangVA_'+ G_Idx +'">'+ strGudangVA +'</select></td>';
			str = str + '	<td width="40%"><input type="text" class="form-control txtAturStockDetail_TglExpired_'+ G_Idx +'" /></td>';
			str = str + '	<td width="15%"><button type="button" class="form-control btn btn-danger" onclick="DeleteRowAturStockDetail( this.parentNode.parentNode.rowIndex )"><i class="fa fa-times"></i></td>';
			str = str + '</tr>';
			
			$("#TableAturStockDetail_"+ G_Idx ).append( str );
			
			$(".AturStockDetail").removeAttr('style');
			$(".AturStockDetail").attr('style','display: none;');

			$("#AturStockDetail_"+ G_Idx ).removeAttr( 'style' );	
			
			var table = document.getElementById('TableAturStockDetail_'+ G_Idx);

			if( table.rows.length > 0 )
			{
				$("#btnlihat_"+ G_Idx).removeClass('btn-danger');
				$("#btnlihat_"+ G_Idx).addClass('btn-success');
			}
			
			//$("#AturStockDetail_"+ G_Idx ).append( str );		
			$('.txtAturStockDetail_TglExpired_'+ G_Idx).datetimepicker
			({
				defaultDate: new Date(),
				format: 'DD/MM/YYYY',
				
			});		

			//$(".bootstrap-datetimepicker-widget .dropdown-menu").attr('style', 'z-index: 9 !important;');
		}
	);
	
	
	function CheckQtyAturStockDetail( rowIdx )
	{
		var lstock = $(".txtAturStockDetail_QtyStock_"+ rowIdx ).length;
		
		var Total = 0;
		
		for( i=0 ; i< lstock ; i++ )
		{
			$(".txtAturStockDetail_QtyStock_"+ rowIdx ).eq(i).val( parseInt( $(".txtAturStockDetail_QtyStock_"+ rowIdx ).eq(i).val() ) );
			
			var Jml = $(".txtAturStockDetail_QtyStock_"+ rowIdx ).eq(i).val();
			
			Total = parseInt( Total ) + parseInt( Jml );
		}
		
		$("#txtaturstock_TotalStock_"+ rowIdx).eq(0).val( Total );
	}
	
	function DeleteRowAturStockDetail( rowIdx )
	{
		var table = document.getElementById('TableAturStockDetail_'+ G_Idx);
		table.deleteRow( rowIdx );
		
		if( table.rows.length == 0 )
		{
			$("#btnlihat_"+ G_Idx).removeClass('btn-success');
			$("#btnlihat_"+ G_Idx).addClass('btn-danger');
		}
		
		CheckQtyAturStockDetail( G_Idx );
	}
	
	$("#btnaturstoksimpan").click
	(
		function()
		{
			var numerror = 1;
			
			var lcol = $(".btnlihat").length;
			
			for( i=0 ; i< lcol ; i++ )
			{
				var Flg = $("#hdAturStock_IsNew_"+ i).val();
						
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
					var ldetailtable = $(".cbAturStockDetail_GudangVA_"+ i).length;

					for( j=0 ; j<ldetailtable ; j++ )
					{
						if( $(".txtAturStockDetail_QtyStock_"+ i).eq(j).prop('disabled') == false )
						{
							var Stok = $(".txtAturStockDetail_QtyStock_"+ i).eq(j).val();
							var GudangVA = $(".cbAturStockDetail_GudangVA_"+ i +" option:selected").eq(j).val();
							var TglExpired = $(".txtAturStockDetail_TglExpired_"+ i).eq(j).val();
							
							if( Stok == 0 || GudangVA == '' || TglExpired == '' )
							{
								numerror = 2;
							}
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
					
					$("#previewaturstoksimpan").modal('show');
				}
			}
		}
	);
	
	$("#btnyesaturstoksimpan").click
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
					var ldetailtable = $(".cbAturStockDetail_GudangVA_"+ i).length;


					for( j=0 ; j<ldetailtable ; j++ )
					{
						if( $(".txtAturStockDetail_QtyStock_"+ i).eq(j).prop('disabled') == false )
						{
							var Stok = $(".txtAturStockDetail_QtyStock_"+ i).eq(j).val();
							var GudangVA = $(".cbAturStockDetail_GudangVA_"+ i +" option:selected").eq(j).val();
							var TglExpired = $(".txtAturStockDetail_TglExpired_"+ i).eq(j).val();
							
							if( Stok == 0 || GudangVA == '' || TglExpired == '' )
							{
								numerror = 2;
							}
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
					var arrAS_SKU_UnitMandiri = [];
					
					var SKUInduk_ID = $("#hdaturstock_SKUInduk_ID").val();
					var UnitMandiri_ID = $("#cbaturstock_unitmandiri option:selected").val();
					
					for( i=0 ; i< lheader ; i++ )
					{
						var SKU_ID = $("#hdaturstock_SKU_ID_"+ i ).val();
						var ldetailtable = $(".cbAturStockDetail_GudangVA_"+ i).length;
						
						var Flg = $("#hdAturStock_IsNew_"+ i).val();
						
						if( Flg == 1 )
						{
							for( j=0 ; j<ldetailtable ; j++ )
							{
								if( $(".txtAturStockDetail_QtyStock_"+ i).eq(j).prop('disabled') == false )
								{
									var Stok = $(".txtAturStockDetail_QtyStock_"+ i).eq(j).val();
									var GudangVA = $(".cbAturStockDetail_GudangVA_"+ i +" option:selected").eq(j).val();
									var TglExpired = $(".txtAturStockDetail_TglExpired_"+ i).eq(j).val();
									
									TglExpired = TglExpired.split('/');
									yyyy = TglExpired[2];
									mm = TglExpired[1];
									dd = TglExpired[0];
									TglExpired = yyyy + '-' + mm + '-' + dd;
									
									if( Stok != 0 && GudangVA != '' && TglExpired != '' )
									{
										arrAS_SKU_UnitMandiri.push( { 	SKU_ID : SKU_ID, UnitMandiri_ID : UnitMandiri_ID, GUDANGVA_ID : GudangVA, 
																		SKU_Stock_OnHand : Stok, SKU_ExpireDate : TglExpired } );
									}
								}
							}
							
						}
					}
					
					$.ajax(
					{
						type: 'POST',    
						url: "<?= base_url('SKUBertahap/SimpanAturStok') ?>",
						data: 
						{
							SKUInduk_ID : SKUInduk_ID,
							arrSKU_UnitMandiri : arrAS_SKU_UnitMandiri
						},
						success: function( response )
						{
							if(response)
							{
								var msg = 'Stok berhasil terisi';
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