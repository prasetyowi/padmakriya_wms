<script type="text/javascript">	
	var DepoRakCode = '';
	
	var shouldCancel = false;
	var arrDepo = [];
	var arrDepoDetail = [];
	var arrDepoRak = [];
	var arrDepoRakD = [];
	var arrRak = [];
	var Counter = 1;
	var arrDRak = [];
	
	var arrRakLajurDetail = [];
	var arrRakLajurBaris = [];
	var arrRakLajurKolom = [];
	var isfromdb = 0;
	var G_Rak_ID = '0';
	
	$(document).ready
	(
		function() 
		{
			GetDepoRakMenu();
			
			$('.modal').css('overflow-y', 'auto');
			
		}
		
		
	);
	
	
	function GetDepoRakMenu()
	{
		$.ajax(
		{
			type: 'POST',    
			url: "<?= base_url('DepoRak/GetDepoRakMenu') ?>",
			//data: "Location="+ Location,
			success: function( response )
			{
				if(response)
				{
					ChDepoRakMenu( response );
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

	function ChDepoRakMenu( JSONDepoRak )
	{
		$("#tabledeporakmenu > tbody").html('');
		
		var DepoRak = JSON.parse( JSONDepoRak );
		
		//var StatusC = DepoRak.AuthorityMenu[0].StatusC;
		//var StatusU = DepoRak.AuthorityMenu[0].StatusU;
		//var StatusD = DepoRak.AuthorityMenu[0].StatusD;
		
		//if( StatusC == 0 )	{	$("#btnaddnewdeporak").attr('style','display: none;');	}
	
		if( $.fn.DataTable.isDataTable('#tabledeporakmenu') ) 
		{
			$('#tabledeporakmenu').DataTable().destroy();
		}
		/*
		if( $.fn.DataTable.isDataTable('#tabledeporakdetailmenu') ) 
		{
			$('#tabledeporakdetailmenu').DataTable().destroy();
		}	*/
		
		$('#tabledeporakmenu tbody').empty();
		$('#tabledeporakdetailmenu tbody').empty();
		
		arrDepo = [];
		arrDepoDetail = [];
		
		$("#cbDepo").html("");
		$("#updatecbDepo").html("");
		
		$("#cbDepo").html('<option value="">-- Pilih Depo --</option>');
		$("#cbDepoDetail").html('<option value="">** Tidak ada Data **</option>');
		$("#updatecbDepo").html('<option value="">-- Pilih Depo --</option>');
		
		if( DepoRak.DepoMenu != 0 )
		{
			for( i=0 ; i<DepoRak.DepoMenu.length ; i++ )
			{
				var depo_id 		= DepoRak.DepoMenu[i].depo_id;
				var depo_kode		= DepoRak.DepoMenu[i].depo_kode;
				var depo_nama		= DepoRak.DepoMenu[i].depo_nama;
				
				$("#cbDepo").append('<option value="'+ depo_id +'">'+ depo_nama +'</option>');
			}
		}
		
		if( DepoRak.DepoDetailMenu != 0 )
		{
			for( i=0 ; i<DepoRak.DepoDetailMenu.length ; i++ )
			{
				var depo_detail_id 		= DepoRak.DepoDetailMenu[i].depo_detail_id;
				var depo_id 			= DepoRak.DepoDetailMenu[i].depo_id;
				var depo_detail_nama	= DepoRak.DepoDetailMenu[i].depo_detail_nama;
				var depo_detail_width	= DepoRak.DepoDetailMenu[i].depo_detail_width;
				var depo_detail_length	= DepoRak.DepoDetailMenu[i].depo_detail_length;
				
				arrDepoDetail.push(	{ 	depo_detail_id: depo_detail_id, depo_id: depo_id, depo_detail_nama: depo_detail_nama,
										depo_detail_width: depo_detail_width, depo_detail_length: depo_detail_length	 } ); 
			}
		}
		
		$("#tabledeporakmenu").DataTable(
		{
			retrieve: true,
			"dom": '<"left"f>rtp'
		});
	}
	
	$("#cbDepo").change
	(
		function()
		{
			var depo_id = $("#cbDepo option:selected").val();
			
			$("#cbDepoDetail").html('');
			
			if( depo_id == '' )
			{
				$("#cbDepoDetail").html('<option value="">** Tidak ada Data **</option>');
			}
			else
			{
				$("#cbDepoDetail").append('<option value="">-- Pilih Depo --</option>');
				//$("#cbDepoDetail").append('<option value="NEW_DATA">** Buat Depo Detail Baru **</option>');
				
				var ldepo_detail = arrDepoDetail.length;
				
				for( i=0 ; i<ldepo_detail ; i++ )
				{
					var Ddepo_detail_nama 	= arrDepoDetail[i].depo_detail_nama;
					var Ddepo_detail_id 	= arrDepoDetail[i].depo_detail_id;
					var Ddepo_id 			= arrDepoDetail[i].depo_id;
					var Ddepo_detail_width 	= arrDepoDetail[i].depo_detail_width;
					var Ddepo_detail_length = arrDepoDetail[i].depo_detail_length;
					
					if( depo_id == Ddepo_id )
					{
						$("#cbDepoDetail").append('<option value="'+ Ddepo_detail_id +'" data-depo_detail_width="'+ Ddepo_detail_width +'" data-depo_detail_length="'+ Ddepo_detail_length +'">'+ Ddepo_detail_nama +'</option>');
					}
				}
			}
		}
	);
	
	$("#cbDepoDetail").data("pre", $("#cbDepoDetail").val() );

	$("#cbDepoDetail").change
	(
		function()
		{ 
			$("#cbDepo").prop('disabled', false );
			
			if( $("#cbDepoDetail option:selected").val() != '' )
			{
				
				$("#cbDepo").prop('disabled', true );
			}
			
			var lshape = $(".hdAdepo_detail_id").length;
			
			if( lshape > 0 )
			{
				$("#previewwarningnewrak").modal('show');
			}
			else
			{
				ShowRak();
			}
		}
	);
	
	$("#btneditdepo_detail").click
	(
		function()
		{
			$("#hdmode").val(0);
			
			$("#previewadddepo_detail").modal('show');
		}
	);
	
	$("#btnyeswarningnewrak").click
	(
		function()
		{
			ShowRak();
		}
	);
	
	
	$("#btnnowarningnewrak").click
	(
		function()
		{
			var before_change = $("#cbDepoDetail").data('pre');
			 
			$("#cbDepoDetail").val( before_change );
			
			//$("#hddepo_id").val( depo_id );
			//$("#txtdepo_nama").val( depo_nama );
			
			$("#cbDepoDetail").data( 'pre', $("#cbDepoDetail").val() );
			
		}
	);
	
	function ShowRak()
	{
		
		var depo_id				= $("#cbDepo option:selected").val();
		var depo_nama			= $("#cbDepo option:selected").html();
		
		var depo_detail_width	= $("#cbDepoDetail option:selected").data('depo_detail_width');
		var depo_detail_length	= $("#cbDepoDetail option:selected").data('depo_detail_length');

		var DepoDetail 			= $("#cbDepoDetail option:selected").val();
		
		$("#txtrak_kode").val('');
		$("#txtrak_nama").val('');
		$("#chrak_is_aktif").prop('checked', false );

		arrRak = [];
		arrRakLajur = [];
		arrRakLajurDetail = [];
		
		if( DepoDetail == 'NEW_DATA' )
		{
			$("#hdmode").val(1);
			
			$("#hddepo_id").val( depo_id );
			$("#txtdepo_nama").val( depo_nama );
			
			$("#previewadddepo_detail").modal('show');
		}
		else if( DepoDetail != 'NEW_DATA' && DepoDetail != '' )
		{
			$(".mainshape").remove();
			$(".hdARand").remove();
			$(".hdArak_lajur_nama").remove();
			$(".hdAdepo_detail_id").remove();
			$(".hdArak_lajur_width").remove();
			$(".hdArak_lajur_length").remove();
			
			$("#shape-placeholder").attr('style','width: '+ depo_detail_length +'px; height: '+ depo_detail_width +'px; border: solid black 1px; position: absolute;');
			$("#Depo_Luas").attr('style','width: '+ depo_detail_length +'px; height: '+ depo_detail_width +'px;');
		
		}
		else if( DepoDetail == '' )
		{
			$(".mainshape").remove();
			$(".hdARand").remove();
			$(".hdArak_lajur_nama").remove();
			$(".hdAdepo_detail_id").remove();
			$(".hdArak_lajur_width").remove();
			$(".hdArak_lajur_length").remove();
			
			$("#shape-placeholder").removeAttr('style');
			$("#Depo_Luas").removeAttr('style');
		}
		
		$("#cbDepoDetail").data( 'pre', $("#cbDepoDetail").val() );
		
		if( DepoDetail != '' )
		{		
			$.ajax(
			{
				type: 'POST',    
				url: "<?= base_url('DepoRak/GetDepoRakLayout') ?>",
				data: 
				{
					depo_id : depo_id,
					depo_detail_id : DepoDetail
				},
				success: function( response )
				{
					if(response)
					{
						ChDepoRakLayout( response );
					}
				}
			});	
		}
	}
	
	function ChDepoRakLayout( JSONRak )
	{
		var Rak = JSON.parse( JSONRak );
		
		var depo_id = $("#cbDepo option:selected").val();
		var depo_nama = $("#cbDepo option:selected").html();
		var depo_detail_id = $("#cbDepoDetail option:selected").val();
		var depo_detail_nama = $("#cbDepoDetail option:selected").html();
		
		$("#txtrak_kode").val('');
		$("#txtrak_nama").val('');
		$("#chrak_is_aktif").prop('checked', false );

		arrRakLajurDetail = [];
		arrRakLajurBaris = [];
		arrRakLajurKolom = [];
		
		isfromdb = 0;
		
		G_Rak_ID = '0';
		
		if( Rak.RakMenu != 0 )
		{
			var rak_id	 		= Rak.RakMenu[0].rak_id;
			var rak_kode 		= Rak.RakMenu[0].rak_kode;
			var rak_nama 		= Rak.RakMenu[0].rak_nama;
			var rak_is_aktif 	= Rak.RakMenu[0].rak_is_aktif;
			
			isfromdb = 1;
			G_Rak_ID = rak_id;
			
			$("#txtrak_kode").val( rak_kode );
			$("#txtrak_nama").val( rak_nama );
			
			if( rak_is_aktif == 1 )	{	$("#chrak_is_aktif").prop('checked', true );	}
		}
		
		if( Rak.RakLajurMenu != 0 )
		{
			for( i=0 ; i< Rak.RakLajurMenu.length ; i++ )
			{
				var rak_lajur_id 		= Rak.RakLajurMenu[i].rak_lajur_id;
				var rak_id 				= Rak.RakLajurMenu[i].rak_id;
				
				var rak_lajur_length 	= Rak.RakLajurMenu[i].rak_lajur_length;
				var rak_lajur_width 	= Rak.RakLajurMenu[i].rak_lajur_width;
				var rak_lajur_x 		= Rak.RakLajurMenu[i].rak_lajur_x;
				var rak_lajur_y 		= Rak.RakLajurMenu[i].rak_lajur_y;
				var rak_lajur_nama 		= Rak.RakLajurMenu[i].rak_lajur_nama;
				var rak_lajur_prefix 	= Rak.RakLajurMenu[i].rak_lajur_prefix;
				
				$("#hdrandID").val( rak_lajur_id );
				
				var randParent = $("#hdrandID").val();
						
				var rand = rak_lajur_id;
				
				shapes = 'k';
				var styles = 'width: '+ rak_lajur_width +'px; height: '+ rak_lajur_length +'px; border-left: solid 3px brown; border-right: solid 3px brown; border-top: solid 3px brown; border-bottom: solid 3px brown; 0.5px; left: 0; top: 0; padding: 10px; position: absolute;';	
				
				str = '';
				
				str = '<div id="shape_'+ rand +'" class="mainshape mainshapeparent_'+ randParent +'" style="'+ styles +'" data-toggle="popover"><center>'+ rak_lajur_nama +'</center>';
				str = str + '<input type="hidden" 										class="hdIDRakparent_'+ randParent +' hdIDRak_'+ rand +'" />';
				str = str + '<input type="hidden" id="hdAIsDB_'+ rand +'"				class="hdAIsDB hdAIsDBparent_'+ randParent +' hdAIsDB_'+ rand +'" value="1" />';
				str = str + '<input type="hidden" id="hdARand_'+ rand +'"				class="hdARand hdARandparent_'+ randParent +' hdARand_'+ rand +'" value="'+ rand +'" />';
				str = str + '<input type="hidden" id="hdArak_lajur_id_'+ rand +'" 		class="hdArak_lajur_id hdArak_lajur_idparent_'+ randParent +' hdArak_lajur_id_'+ rand +'" value="'+ rak_lajur_id +'" />';
				str = str + '<input type="hidden" id="hdArak_lajur_prefix_'+ rand +'" 	class="hdArak_lajur_prefix hdArak_lajur_prefixparent_'+ randParent +' hdArak_lajur_prefix_'+ rand +'" value="'+ rak_lajur_prefix +'" />';
				str = str + '<input type="hidden" id="hdArak_lajur_nama_'+ rand +'" 	class="hdArak_lajur_nama hdArak_lajur_namaparent_'+ randParent +' hdArak_lajur_nama_'+ rand +'" value="'+ rak_lajur_nama +'" />';
				str = str + '<input type="hidden" id="hdAdepo_nama_'+ rand +'" 			class="hdAdepo_nama hdAdepo_namaparent_'+ randParent +' hdAdepo_nama_'+ rand +'" value="'+ depo_nama +'" />';
				str = str + '<input type="hidden" id="hdAdepo_id_'+ rand +'" 			class="hdAdepo_id hdAdepo_idparent_'+ randParent +' hdAdepo_id_'+ rand +'" value="'+ depo_id +'" />';
				str = str + '<input type="hidden" id="hdAdepo_detail_nama_'+ rand +'" 	class="hdAdepo_detail_nama hdAdepo_detail_namaparent_'+ randParent +' hdAdepo_detail_nama_'+ rand +'" value="'+ depo_detail_nama +'" />';
				str = str + '<input type="hidden" id="hdAdepo_detail_id_'+ rand +'" 	class="hdAdepo_detail_id hdAdepo_detail_idparent_'+ randParent +' hdAdepo_detail_id_'+ rand +'" value="'+ depo_detail_id +'" />';
				str = str + '<input type="hidden" id="hdArak_lajur_width_'+ rand +'" 	class="hdArak_lajur_width hdArak_lajur_widthparent_'+ randParent +' hdArak_lajur_width_'+ rand +'"	value="'+ rak_lajur_width +'" />';
				str = str + '<input type="hidden" id="hdArak_lajur_length_'+ rand +'" 	class="hdArak_lajur_length hdArak_lajur_lengthparent_'+ randParent +' hdArak_lajur_length_'+ rand +'" value="'+ rak_lajur_length +'" />';
				str = str + '</div>';
				$("#shape-placeholder").append( str );
		
				var tmpLeft = 0;
				var tmpTop = 0;
				
				$("#shape_"+ rand ).draggable(
				{
					/* update baru */
					obstacle: ".mainshape",
					preventCollision: true,
					
					containment: "#shape-placeholder",
					revert: function()
					{
						if (shouldCancel) 	{	shouldCancel = false;	return true;	} 
						else 				{	return false;	}
					},
					drag: function(event, ui) 
					{
						tmpLeft = ui.position.left % 5;
						tmpTop = ui.position.top % 5;
						
						ui.position.left = ui.position.left - tmpLeft;
						ui.position.top = ui.position.top - tmpTop;
					},
				});	
				
				$('#shape_'+ rand ).droppable
				({
					over: function(){	shouldCancel = true;	},
					out: function(){	shouldCancel = false;	}
				});
				
				//$("#cbmeja option:selected").attr('style','background-color: lightgreen;');
				
				arrRak.push( { id : rand } );
				
				//PopOverShow( rand, 0, rak_lajur_nama, rak_lajur_prefix );
				var txt = '';
					txt = txt + '<span class="input-group">';
					txt = txt + '	<button type="button" class="btn btn-warning form-control" onclick="AturRak(\''+ rand +'\', 0, \''+ rak_lajur_nama +'\',\''+ rak_lajur_prefix +'\', 1 )">';
					txt = txt + '		<i class="fa fa-pencil"></i> Atur Rak';
					txt = txt + '	</button>';
					txt = txt + '	<hr>';
					txt = txt + '	<button type="button" class="btn btn-danger form-control" onclick="DeleteRak(\''+ rand +'\', 0)">';
					txt = txt + '		<i class="fa fa-times"></i> Hapus Rak';
					txt = txt + '	</button>';
					txt = txt + '</span>';
				
				$("#shape_"+ rand ).popover
				({
					html: true,
					placement: 'bottom',
					container: 'body',
					sanitize: false,
					content: txt
				});
					
				if( Rak.RakLajurDetailMenu != 0 )
				{
					for( j=0 ; j< Rak.RakLajurDetailMenu.length ; j++ )
					{
						var rak_lajur_detail_id 	= Rak.RakLajurDetailMenu[j].rak_lajur_detail_id;
						var rak_lajur_id 			= Rak.RakLajurDetailMenu[j].rak_lajur_id;
						var rak_id 					= Rak.RakLajurDetailMenu[j].rak_id;
						
						var rak_lajur_detail_baris 	= Rak.RakLajurDetailMenu[j].rak_lajur_detail_baris;
						var rak_lajur_detail_kolom 	= Rak.RakLajurDetailMenu[j].rak_lajur_detail_kolom;
						var rak_lajur_detail_nama 	= Rak.RakLajurDetailMenu[j].rak_lajur_detail_nama;
						
						var isdb				 	= Rak.RakLajurDetailMenu[j].isdb;
						
						arrRakLajurDetail.push( { 	prefix: rak_lajur_prefix, randParent: randParent, rand: rand, isdb: isdb,
													rak_lajur_detail_baris: rak_lajur_detail_baris, 
													rak_lajur_detail_kolom: rak_lajur_detail_kolom, 
													rak_lajur_detail_nama: rak_lajur_detail_nama } );
					}
				}
				
				if( Rak.RakLajurDetailBarisMenu != 0 )
				{
					for( j=0 ; j< Rak.RakLajurDetailBarisMenu.length ; j++ )
					{
						var y = Math.random() * 100000000000000000000;
						var b_rak_lajur_prefix	 			= Rak.RakLajurDetailBarisMenu[j].rak_lajur_prefix;
						var rak_lajur_detail_baris 			= Rak.RakLajurDetailBarisMenu[j].rak_lajur_detail_baris;
						var rak_lajur_detail_baris_alias 	= Rak.RakLajurDetailBarisMenu[j].rak_lajur_detail_baris_alias;
						
						if( b_rak_lajur_prefix = rak_lajur_prefix )
						{
							arrRakLajurBaris.push( { prefix: b_rak_lajur_prefix, randParent: randParent, rand: y, rak_lajur_detail_baris: rak_lajur_detail_baris, rak_lajur_detail_baris_alias: rak_lajur_detail_baris_alias } );
						}
					}
				}
				
				if( Rak.RakLajurDetailKolomMenu != 0 )
				{
					for( k=0 ; k< Rak.RakLajurDetailKolomMenu.length ; k++ )
					{
						var x = Math.random() * 100000000000000000000;
						var k_rak_lajur_prefix 				= Rak.RakLajurDetailKolomMenu[k].rak_lajur_prefix;
						var rak_lajur_detail_kolom		 	= Rak.RakLajurDetailKolomMenu[k].rak_lajur_detail_kolom;
						var rak_lajur_detail_kolom_alias 	= Rak.RakLajurDetailKolomMenu[k].rak_lajur_detail_kolom_alias;
						
						if( k_rak_lajur_prefix = rak_lajur_prefix )
						{
							arrRakLajurKolom.push( { prefix: k_rak_lajur_prefix, randParent: randParent, rand: x, rak_lajur_detail_kolom: rak_lajur_detail_kolom, rak_lajur_detail_kolom_alias: rak_lajur_detail_kolom_alias } );
						}	
					}	
				}
			}
		}
		/*
		$("#tbrak").remove();
		
		var strdefault = '';
		
		strdefault += '<table width="180px" id="tbrak" class="table table-striped" width="200px">';
		strdefault += '	<thead>';
		strdefault += '		<tr id="trheader">';
		strdefault += '			<td colspan="3" width="200px" style="background-color: #bbbbbb;" align="center"><label class="control-label">Kolom</label></td>';
		strdefault += '		</tr>';
		strdefault += '		<tr id="trkolom">';
		strdefault += '			<td width="50px" style="background-color: #bbbbbb;" align="center"><strong>Hapus</strong></td>';
		strdefault += '			<td width="50px" style="background-color: #bbbbbb;" align="center"><strong>Level</strong></td>';
		strdefault += '			<td width="100px" style="background-color: #bbbbbb;" align="center"><strong>Edit Level</strong></td>';
		strdefault += '		</tr>';
		strdefault += '	</thead>';
		strdefault += '	<tbody></tbody>';
		strdefault += '</table>';
		
		$("#divRak").append( strdefault );
		
		$("#tbrak").DataTable(
		{
			retrieve: true,
			initComplete: function (settings, json) 
			{  
				$("#tbrak").wrap("<div style='overflow-y: scroll; overflow-x: auto; width: 100%; height: 400px; position:relative; padding: 0; margin: 0;'></div>");            
			},
			"dom": '<"left">rt',
			paging: false,
			ordering: false,
			fixedHeader: true
		});
		
		$("#tab1").removeAttr('class');
		$("#tab2").removeAttr('class');
		$("#tab1").attr('class','active');
		
		$("#depolist").removeClass('active');
		$("#detailrakdepolist").removeClass('active');
		$("#depolist").addClass('active');
		
		$("#tab1").attr('class','active');
		
		var Baris = arrRakLajurBaris.length;
		var Kolom = arrRakLajurKolom.length;
		var randParent = $("#hdrandID").val();
		
		AddKolomRak( randParent, Baris, 0 );
		*/
	}
	
	$("#btnsaveadddepo_detail").click
	(
		function()
		{
			SaveAddNewDepoRak( 0 ); // Mode 0 = Simpan biasa, 1 = Simpan dan pilih
		
		}
	);
	
	$("#btnsavechooseadddepo_detail").click
	(
		function()
		{
			SaveAddNewDepoRak( 1 ); // Mode 0 = Simpan biasa, 1 = Simpan dan pilih
		}
	);
	
	function SaveAddNewDepoRak( Mode )
	{	
		var depo_id				= $("#hddepo_id").val();
		var depo_detail_nama	= $("#txtdepo_detail_nama").val();
		var depo_detail_catatan = $("#txtdepo_detail_catatan").val();
		var depo_detail_length 	= $("#txtdepo_detail_length").val();
		var depo_detail_width 	= $("#txtdepo_detail_width").val();
		var flagjual	 		= $("#chflagjual").prop('checked') == true ? 1 : 0; 
		
		$.ajax(
		{
			type: 'POST',    
			url: "<?= base_url('DepoRak/SaveDepoDetail') ?>",
			data: 
			{	
				depo_id 			: depo_id,
				depo_detail_nama	: depo_detail_nama,
				depo_detail_catatan : depo_detail_catatan,
				depo_detail_length	: depo_detail_length,
				depo_detail_width	: depo_detail_width,
				flagjual			: flagjual
			},
			success: function( response )
			{
				var resp = JSON.parse( response );
				
				if( resp.resp == 1 )
				{
					var msg = 'Depo Detail berhasil dibuat';
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
					
					var depo_detail_id = resp.depo_detail_id;
					
					$("#cbDepoDetail").append('<option value="'+ depo_detail_id +'" data-depo_detail_length="'+ depo_detail_length +'" data-depo_detail_width="'+ depo_detail_width +'">'+ depo_detail_nama +'</option>');	
						
					if( Mode == 0 )
					{
						$("#cbDepoDetail").val( $("#cbDepoDetail").data('pre') );
					}
					if( Mode == 1 )
					{							
						$(".mainshape").remove();
						$(".hdARand").remove();
						$(".hdArak_lajur_prefix").remove();
						$(".hdArak_lajur_nama").remove();
						$(".hdAdepo_detail_id").remove();
						$(".hdArak_lajur_width").remove();
						$(".hdArak_lajur_length").remove();
						
						$("#cbDepoDetail").val( depo_detail_id );
						
						$("#cbDepoDetail").data( 'pre', $("#cbDepoDetail").val() );
		
						$("#cbDepoDetail").trigger('change');
					}
					
					$("#previewadddepo_detail").modal('hide');
				}
				else if( resp.resp == 2 )
				{
					var msg = 'Depo Detail gagal dibuat.';
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
				else if( resp.resp == 3 )
				{
					var msg = 'Nama Depo sudah ada.';
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
	
	$("#btnbackadddepo_detail").click
	(
		function()
		{
			$("#cbDepoDetail").val('');
		}
	);
	
	$("#btnaddkolomrak").click
	(
		function()
		{
			AddKolomRak( '', '', 1, 0 );
		}
	);
	
	function AddKolomRak( P_randParent, P_BarisDef, IsNew, isdb )
	{
		if( $.fn.DataTable.isDataTable('#tbrak') ) 
		{
			$('#tbrak').DataTable().destroy();
		}
		
		if( P_randParent == '' ){	randParent = $("#hdrandID").val();	}
		
		
		if( P_BarisDef != '' )				
		{
			for( i=0 ; i<arrRakLajurKolom.length ; i++ )
			{
				var randParent 						= arrRakLajurKolom[i].randParent;
				var rand 							= arrRakLajurKolom[i].rand;
				var rak_lajur_detail_kolom 			= arrRakLajurKolom[i].rak_lajur_detail_kolom;
				var rak_lajur_detail_kolom_alias 	= arrRakLajurKolom[i].rak_lajur_detail_kolom_alias;
				
				var isdisabled = ''; 
				
				if( isdb != '0' )	
				{	
					var isdisabled = 'disabled="disabled"';	
					var iscandelete = '';
				}
				else
				{
					var iscandelete = 'onclick="ShowDelCol('+ rand +', this.cellIndex )"';
				}
				
				if( randParent == P_randParent )
				{
					var Kolom = $(".tdkolom").length;
					var Baris = $(".tdbaris").length;

					var KolomBaru = parseInt( Kolom ) + 1;
					
					var width = $("#tbrak").attr('width');
									
					$("#tbrak").attr('width', parseInt(width) + 80 );

					$("#trheader").append('<td class="tdheaderkolom tdheaderkolomparent_'+ randParent +' tdheaderkolom_'+ rand +'" id="tdheaderkolom_'+ rand +'" style="background-color: #bbbbbb;" align="center" data-rand="'+ rand +'" width="80px" align="center" '+ iscandelete +'><label class="control-label">Kolom '+ KolomBaru +'</label></td>');
					
					$("#trkolom").append('<td class="tdkolom tdkolomparent_'+ randParent +' tdkolom_'+ rand +'" id="tdkolom_'+ rand +'" data-rand="'+ rand +'" width="80px" align="center"><input type="hidden" class="txttdkolomdisabled" value="'+ rak_lajur_detail_kolom_alias +'" /><input type="text" style="width: 100px;" class="txttdkolom form-control input-sm" id="txttdkolom_'+ rand +'" data-rand="'+ rand +'" value="'+ rak_lajur_detail_kolom_alias +'" '+ isdisabled +' onchange="GantiKolom(\''+ rak_lajur_detail_kolom_alias +'\', \''+ Baris +'\', this.value, \''+ randParent +'\', \''+ rand +'\')" /></td>');
					
			
					$("#tbrak tbody").prepend( str );
					
				}
			}
			
			var Kolom = arrRakLajurKolom.length;
			
			AddBarisRak( P_randParent, Kolom, isdb );
		}		
		else
		{
			var prefix 		= $("#vtxtrak_lajur_prefix").val();
			var Kolom 		= $(".tdkolom").length;
			var Baris 		= $(".tdbaris").length;

			var KolomBaru 	= parseInt( Kolom ) + 1;
			
			var width = $("#tbrak").attr('width');
			
			var randParent = $("#hdrandID").val();	
			var rand = Math.random() * 99999999999999999999;	

			$("#tbrak").attr('width', parseInt(width) + 80 );

			$("#trheader").append('<td class="tdheaderkolom tdheaderkolomparent_'+ randParent +' tdheaderkolom_'+ rand +'" id="tdheaderkolom_'+ rand +'" style="background-color: #bbbbbb;" align="center" data-rand="'+ rand +'" width="80px" align="center" data-toggle="popover" onclick="ShowDelCol('+ rand +', this.cellIndex )"><label class="control-label">Kolom '+ KolomBaru +'</label></td>');
			
			$("#trkolom").append('<td class="tdkolom tdkolomparent_'+ randParent +' tdkolom_'+ rand +'" id="tdkolom_'+ rand +'" data-rand="'+ rand +'" width="80px" align="center"><input type="hidden" class="txttdkolomdisabled" value="'+ KolomBaru +'" /><input type="text" style="width: 100px;" class="txttdkolom form-control input-sm" id="txttdkolom_'+ rand +'" data-rand="'+ rand +'" value="'+ KolomBaru +'" onchange="GantiKolom(\''+ KolomBaru +'\', \''+ Baris +'\', this.value, \''+ randParent +'\', \''+ rand +'\')" /></td>');
			
			var idx = 0;
			var idxbaris = Baris;
			
			for( i=Baris ; i>0 ; i-- )
			{
				//var B = parseInt(i);
				var B = $(".txttdbaris").eq(idx).val();
				
				var str = '';
			
				str +=	'<td width="80px" style="border-left: solid #954535 10px; border-right: solid #6E260E 10px; border-bottom: solid #7B3F00 10px;" align="center" id="tdkolombody_'+ rand +'" class="tdkolombodyparent_'+ randParent +' tdkolombody_'+ rand +'">';
				str += 	'	<label style="width: 80px;" class="btnkb btnkolom_'+ rand +' btnbaris_'+ idxbaris +' control-label" data-isdb="0" data-isi="'+ B +'-'+ KolomBaru +'" data-rand="'+ rand +'" data-alias="'+ KolomBaru +'" data-baris="'+ B +'" data-kolom="'+ KolomBaru +'" data-prefix="'+ prefix +'" >'+ prefix +'-'+ B +'-'+ KolomBaru +'</label>';
				str +=	'</td>';
				
				$(".tdbaris").eq( idx ).append( str );
				
				idx = parseInt( idx ) + 1;
				idxbaris = parseInt( idxbaris ) - 1;
			}
			
			$("#tbrak tbody").prepend( str );
			
			$("#tbrak").DataTable(
			{
				retrieve: true,
				initComplete: function (settings, json) 
				{  
					$("#tbrak").wrap("<div style='overflow-y: scroll; overflow-x: auto; width: 100%; height: 400px; position:relative; padding: 0; margin: 0;'></div>");            
				},
				"dom": '<"left">rt',
				paging: false,
				ordering: false,
				fixedHeader: true
			});
		}
		
		
	}
	
	function AddBarisRak( P_randParent, P_KolomDef, isdb )
	{
		if( P_randParent == '' ){	randParent = $("#hdrandID").val();	}
		
		if( P_KolomDef != '' )				
		{
			var Kolom		= $(".tdkolom").length;
			var Baris 		= $(".tdbaris").length;
			
			var BarisBaru 	= parseInt( Baris ) + 1;
			var str = '';
			
			for( i=0 ; i<arrRakLajurBaris.length ; i++ )
			{
				var prefix 							= arrRakLajurBaris[i].prefix;
				var randParent 						= arrRakLajurBaris[i].randParent;
				var randbaris 						= arrRakLajurBaris[i].rand;
				var rak_lajur_detail_baris			= arrRakLajurBaris[i].rak_lajur_detail_baris;
				var rak_lajur_detail_baris_alias 	= arrRakLajurBaris[i].rak_lajur_detail_baris_alias;
				
				var isdisabled = ''; 
				
				if( isdb != '0' )	
				{	
					var isdisabled = 'disabled="disabled"';	
					var iscandelete = ''; 
				}
				else
				{
					var iscandelete = '<button type="button" class="btndelrow btn btn-danger" onclick="DelRow( this.parentNode.parentNode.rowIndex, '+ randbaris +' )"><i class="fa fa-times"></i></button>';
				}
				
				if( randParent == P_randParent )
				{
					str += 	'<tr class="tdbaris tdbarisparent_'+ randParent +'" id="tdbaris_'+ randbaris +'">';
					str +=	'	<td width="50px" align="center">'+ iscandelete +'</td>';		
					str +=	'	<td width="50px" align="center"><input type="text" style="width: 50px;" class="txttdbarisdisabled form-control" data-rand="'+ randbaris +'" value="'+ rak_lajur_detail_baris +'" '+ isdisabled +' /></td>';
					str +=	'	<td width="100px" align="center"><input type="text" style="width: 100px;" class="txttdbaris form-control" data-rand="'+ randbaris +'" id="txttdbaris_'+ rak_lajur_detail_baris +'" value="'+ rak_lajur_detail_baris_alias +'" onchange="GantiBaris('+ rak_lajur_detail_baris_alias +', '+ rak_lajur_detail_baris_alias +', this.value, '+ rak_lajur_detail_baris_alias +')" '+ isdisabled +' /></td>';
					
					var strkolom = '';
			
					for( j=0 ; j<Kolom ; j++ )
					{
						var rand	= $(".tdkolom").eq(j).attr('data-rand');
						var K 		= $(".txttdkolom").eq(j).val();
						var KA 		= $(".txttdkolomdisabled").eq(j).val();
			
						strkolom +=	'<td width="80px" style="border-left: solid #954535 10px; border-right: solid #6E260E 10px; border-bottom: solid #7B3F00 10px;" align="center" class="tdkolombodyparent_'+ randParent +' tdkolombody_'+ rand +'">';
						strkolom += '	<label style="width: 80px;" type="button" class="btnkb btnkolom_'+ rand +' btnbaris_'+ rak_lajur_detail_baris +' control-label" data-isdb="1" data-isi="'+ rak_lajur_detail_baris +'-'+ KA +'" data-rand="'+ rand +'" data-alias="'+ rak_lajur_detail_baris_alias +'" data-baris="'+ rak_lajur_detail_baris +'" data-kolom="'+ KA +'" data-prefix="'+ prefix +'">'+ prefix + '-' + rak_lajur_detail_baris_alias +'-'+ K +'</button>';
						strkolom +=	'</td>';
					}
					
					str += strkolom + '</tr>';
			
				}
			}
			
			$("#tbrak tbody").append( str );
		}
		
		$("#tbrak").DataTable(
		{
			retrieve: true,
			initComplete: function (settings, json) 
			{  
				$("#tbrak").wrap("<div style='overflow-y: scroll; overflow-x: auto; width: 100%; height: 400px; position:relative; padding: 0; margin: 0;'></div>");            
			},
			"dom": '<"left">rt',
			paging: false,
			ordering: false,
			fixedHeader: true
		});
	}
	
	function GantiKolom( defaultval, Baris, newval, randParent, rand )
	{
		if( $.fn.DataTable.isDataTable('#tbrak') ) 
		{
			$('#tbrak').DataTable().destroy();
		}
		
		if( newval == '' )
		{
			$("#txttdkolom_"+ rand ).val( defaultval );
		}
		
		var prefix 	= $("#vtxtrak_lajur_prefix").val();
		var newval = $("#txttdkolom_"+ rand ).val();
	
		var lnkb = $(".btnkolom_"+ rand ).length;
		
		var Baris = $(".tdbaris").length;
		
		var idx = 0;
		for( i=Baris ; i>0 ; i-- )
		{
			var B = parseInt( i );
			
			var v = $("#txttdbaris_"+ B).val(); 
			
			$(".btnkolom_"+ rand ).eq( idx ).html( prefix + '-' + v + '-' + $("#txttdkolom_"+ rand ).val() );
			
			idx = parseInt( idx ) + 1;
		}
		
		$("#tbrak").DataTable(
		{
			retrieve: true,
			initComplete: function (settings, json) 
			{  
				$("#tbrak").wrap("<div style='overflow-y: scroll; overflow-x: auto; width: 100%; height: 400px; position:relative; padding: 0; margin: 0;'></div>");            
			},
			"dom": '<"left">rt',
			paging: false,
			ordering: false,
			fixedHeader: true
		});
	}
	
	function GantiBaris( Baris, defaultval, newval, rand )
	{
		if( newval == '' )
		{
			$("#txttdbaris_"+ rand ).val( defaultval );
		}
		
		var prefix 	= $("#vtxtrak_lajur_prefix").val();
		var newval = $("#txttdbaris_"+ rand ).val();
		
		var lnkb = $(".btnbaris_"+ rand ).length;
		
		for( i=0 ; i<lnkb ; i++ )
		{
			for( j=0 ; j<$(".txttdkolom").length ; j++ )
			{
				var kolom = $(".txttdkolom").eq(i).val();
				
				$(".btnbaris_"+ Baris ).eq(i).html( prefix +'-'+ newval + '-' + kolom );
			}
		}
	}
	
	$("#btnaddbarisrak").click
	(
		function()
		{
			if( $.fn.DataTable.isDataTable('#tbrak') ) 
			{
				$('#tbrak').DataTable().destroy();
			}
			
			var prefix 		= $("#vtxtrak_lajur_prefix").val();
			
			var Kolom = $(".tdkolom").length;
			var Baris = $(".tdbaris").length;
			
			var BarisBaru = parseInt( Baris ) + 1;
			var str = '';
			var strkolom = '';

			var randParent = $("#hdrandID").val();
			var randbaris = Math.random() * 10000000000000000000;
		
			str += 	'<tr class="tdbaris tdbarisparent_'+ randParent +'" id="tdbaris_'+ randbaris +'">';
			str +=	'	<td width="50px" align="center"><button type="button" class="btndelrow btn btn-danger" onclick="DelRow( this.parentNode.parentNode.rowIndex, '+ randbaris +' )"><i class="fa fa-times"></i></button></td>';		
			str +=	'	<td width="50px" align="center"><input type="text" style="width: 50px;" class="txttdbarisdisabled form-control" data-rand="'+ randbaris +'" value="'+ BarisBaru +'" disabled="disabled" /></td>';
			str +=	'	<td width="100px" align="center"><input type="text" style="width: 100px;" class="txttdbaris form-control" id="txttdbaris_'+ BarisBaru +'" data-rand="'+ randbaris +'" value="'+ BarisBaru +'" onchange="GantiBaris(\''+ BarisBaru +'\', \''+ BarisBaru +'\', this.value, \''+ BarisBaru +'\')" /></td>';

			for( i=0 ; i<Kolom ; i++ )
			{
				var rand = $(".tdkolom").eq(i).data('rand');

				//var K = parseInt(i) + 1;
				var K = $(".txttdkolom").eq(i).val();
	
				strkolom +=	'<td width="80px" style="border-left: solid #954535 10px; border-right: solid #6E260E 10px; border-bottom: solid #7B3F00 10px;" align="center" class="tdolombodyparent_'+ randParent +' tdkolombody_'+ rand +'">';
				strkolom += '	<label style="width: 80px;" type="button" class="btnkb btnkolom_'+ rand +' btnbaris_'+ BarisBaru +' control-label" data-isdb="0" data-isi="'+ BarisBaru +'-'+ K +'" data-rand="'+ rand +'" data-alias="'+ BarisBaru +'" data-baris="'+ BarisBaru +'" data-kolom="'+ K +'" data-prefix="'+ prefix +'">'+ prefix + '-' + BarisBaru +'-'+ K +'</button>';
				strkolom +=	'</td>';
			}
			
			str += strkolom + '</tr>';
			
			$("#tbrak tbody").prepend( str );
			
			$("#tbrak").DataTable(
			{
				retrieve: true,
				initComplete: function (settings, json) 
				{  
					$("#tbrak").wrap("<div style='overflow-y: scroll; overflow-x: auto; width: 100%; height: 400px; position:relative; padding: 0; margin: 0;'></div>");            
				},
				"dom": '<"left">rt',
				paging: false,
				ordering: false,
				fixedColumns: true
			});
		}
	);
	
	function ShowDelCol( rand, cellIdx )
	{
		var txt = '';
		
		txt = txt + '<span class="input-group">';
		txt = txt + '	<button type="button" class="btn btn-warning form-control" onclick="DelCol(\''+ rand +'\','+ cellIdx +')">';
		txt = txt + '		<i class="fa fa-pencil"></i> Hapus Kolom';
		txt = txt + '	</button>';
		txt = txt + '</span>';
		
		$("#tdheaderkolom_"+ rand ).popover
		({
			html: true,
			placement: 'bottom',
			container: 'body',
			sanitize: false,
			content: txt
		});
	}
	
	function DelRow( RowIdx, randbaris )
	{
		RowIdx = RowIdx - 2; // Baris 0 = Header
		if( RowIdx != 0 )
		{
			var msg = 'Hanya bisa menghapus Level paling atas.';
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
		
		if( $.fn.DataTable.isDataTable('#tbrak') ) 
		{
			$('#tbrak').DataTable().destroy();
		}
			
		$("#tdbaris_"+ randbaris ).remove();
		
		$("#tbrak").DataTable(
		{
			retrieve: true,
			initComplete: function (settings, json) 
			{  
				$("#tbrak").wrap("<div style='overflow-y: scroll; overflow-x: auto; width: 100%; height: 400px; position:relative; padding: 0; margin: 0;'></div>");            
			},
			"dom": '<"left">rt',
			paging: false,
			ordering: false,
			fixedColumns: true
		});
		
	}
	
	function DelCol( rand, cellIdx )
	{
		var ColIdx = cellIdx - 1;

		var IsLastCol = $(".tdkolom").length - 1;
		
		if( ColIdx < IsLastCol  )
		{
			var msg = 'Hanya bisa menghapus Kolom paling terakhir.';
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
			
			$("#tdheaderkolom_"+ rand ).popover('hide');
		
			return false;
		}
		
		if( $.fn.DataTable.isDataTable('#tbrak') ) 
		{
			$('#tbrak').DataTable().destroy();
		}
			
		$("#tdheaderkolom_"+ rand ).popover('hide');
		
		$("#tdheaderkolom_"+ rand ).remove();
		
		$("#tdkolom_"+ rand ).remove();
		
		$(".tdkolombody_"+ rand ).remove();
		
		$("#tbrak").DataTable(
		{
			retrieve: true,
			initComplete: function (settings, json) 
			{  
				$("#tbrak").wrap("<div style='overflow-y: scroll; overflow-x: auto; width: 100%; height: 400px; position:relative; padding: 0; margin: 0;'></div>");            
			},
			"dom": '<"left">rt',
			paging: false,
			ordering: false,
			fixedColumns: true
		});
		
		var width = $("#tbrak").attr('width');
		
		$("#tbrak").attr('width', parseInt( width ) - 80 );

	}
	
	$("#btngeneraterak").click
	(
		function()
		{
			$("#cbDepo").prop('disabled', true );
			$("#cbDepoDetail").prop('disabled', true );
			
			var depo_id 			= $("#cbDepo option:selected").val();
			var depo_nama 			= $("#cbDepo option:selected").html();
			var depo_detail_id 		= $("#cbDepoDetail option:selected").val();
			var depo_detail_nama 	= $("#cbDepoDetail option:selected").html();
			
			var rak_kode		 	= $("#txtrak_kode").val();
			var rak_nama		 	= $("#txtrak_nama").val();
			var rak_lajur_nama		= $("#txtrak_lajur_nama").val();
			var rak_lajur_prefix	= $("#txtrak_lajur_prefix").val();
		
			if( rak_lajur_nama == '' || rak_lajur_prefix == '' )
			{
				var msg = 'Nama Lajur dan Prefix harus terisi.';
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
			
			if( depo_id == '' || depo_detail_id == '' )
			{
				var msg = 'Unit dan Unit Detail belum dipilih.';
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
			
			var depo_detail_length 		= $("#cbDepoDetail option:selected").data('depo_detail_length');
			var depo_detail_width 		= $("#cbDepoDetail option:selected").data('depo_detail_width');
					
			var rak_lajur_length 	= $("#txtrak_lajur_length").val();
			var rak_lajur_width 	= $("#txtrak_lajur_width").val();
			
			var lrak = $(".hdARand").length;
			
			var rak_lajur_nama 		= $("#txtrak_lajur_nama").val();
			var rak_lajur_prefix 	= $("#txtrak_lajur_prefix").val();
			
			numerror = 0;
			numerrorprefix = 0;
			
			for( i=0 ; i< lrak ; i++ )
			{
				var P_rak_lajur_nama = $(".hdArak_lajur_nama").eq(i).val();
				var P_rak_lajur_prefix = $(".hdArak_lajur_prefix").eq(i).val();
				
				if( P_rak_lajur_nama == rak_lajur_nama )
				{
					numerror = 1;
				}
				
				if( P_rak_lajur_prefix == rak_lajur_prefix )
				{
					numerror = 2;
				}
			}
			
			if( numerror == 1 )
			{
				var msg = 'Nama Lajur sudah ada.';
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
			else if( numerror == 2 )
			{
				var msg = 'Nama Prefix sudah ada.';
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
			
			if( rak_lajur_nama != '' )
			{
				var randParent = $("#hdrandID").val();
				
				var rand = Math.random() * 100000000000000000000;
				
				shapes = 'k';
				var styles = 'width: '+ rak_lajur_width +'px; height: '+ rak_lajur_length +'px; border-left: solid 3px brown; border-right: solid 3px brown; border-top: solid 3px brown; border-bottom: solid 3px brown; 0.5px; left: 0; top: 0; padding: 10px; position: absolute;';	
				
				str = '';
				
				str = 		'<div id="shape_'+ rand +'" class="mainshape mainshapeparent_'+ randParent +'" style="'+ styles +'" data-toggle="popover"><center>'+ rak_lajur_nama +'</center>';
				str = str + '	<input type="hidden" 										class="hdIDRakparent_'+ randParent +' hdIDRak_'+ rand +'" />';
				str = str + '	<input type="hidden" id="hdAIsDB_'+ rand +'"				class="hdAIsDB hdAIsDBparent_'+ randParent +' hdAIsDB_'+ rand +'" value="0" />';
				str = str + '	<input type="hidden" id="hdARand_'+ rand +'"				class="hdARand hdARandparent_'+ randParent +' hdARand_'+ rand +'" value="'+ rand +'" />';
				str = str + '	<input type="hidden" id="hdArak_lajur_prefix_'+ rand +'" 	class="hdArak_lajur_prefix hdArak_lajur_prefixparent_'+ randParent +' hdArak_lajur_prefix_'+ rand +'" value="'+ rak_lajur_prefix +'" />';
				str = str + '	<input type="hidden" id="hdArak_lajur_id_'+ rand +'" 		class="hdArak_lajur_id hdArak_lajur_idparent_'+ randParent +' hdArak_lajur_id_'+ rand +'" value="0" />';
				str = str + '	<input type="hidden" id="hdArak_lajur_nama_'+ rand +'" 		class="hdArak_lajur_nama hdArak_lajur_namaparent_'+ randParent +' hdArak_lajur_nama_'+ rand +'" value="'+ rak_lajur_nama +'" />';
				str = str + '	<input type="hidden" id="hdAdepo_nama_'+ rand +'" 			class="hdAdepo_nama hdAdepo_namaparent_'+ randParent +' hdAdepo_nama_'+ rand +'" value="'+ depo_nama +'" />';
				str = str + '	<input type="hidden" id="hdAdepo_id_'+ rand +'" 			class="hdAdepo_id hdAdepo_idparent_'+ randParent +' hdAdepo_id_'+ rand +'" value="'+ depo_id +'" />';
				str = str + '	<input type="hidden" id="hdAdepo_detail_nama_'+ rand +'" 	class="hdAdepo_detail_nama hdAdepo_detail_namaparent_'+ randParent +' hdAdepo_detail_nama_'+ rand +'" value="'+ depo_detail_nama +'" />';
				str = str + '	<input type="hidden" id="hdAdepo_detail_id_'+ rand +'" 	class="hdAdepo_detail_id hdAdepo_detail_idparent_'+ randParent +' hdAdepo_detail_id_'+ rand +'" value="'+ depo_detail_id +'" />';
				str = str + '	<input type="hidden" id="hdArak_lajur_width_'+ rand +'" 	class="hdArak_lajur_width hdArak_lajur_widthparent_'+ randParent +' hdArak_lajur_width_'+ rand +'"	value="'+ rak_lajur_width +'" />';
				str = str + '	<input type="hidden" id="hdArak_lajur_length_'+ rand +'" 	class="hdArak_lajur_length hdArak_lajur_lengthparent_'+ randParent +' hdArak_lajur_length_'+ rand +'" value="'+ rak_lajur_length +'" />';
				str = str + '</div>';
				
				$("#shape-placeholder").append( str );
		
				var tmpLeft = 0;
				var tmpTop = 0;
				
				$("#shape_"+ rand ).draggable(
				{
					/* update baru */
					obstacle: ".mainshape",
					preventCollision: true,
					
					containment: "#shape-placeholder",
					revert: function()
					{
						if (shouldCancel) 	{	shouldCancel = false;	return true;	} 
						else 				{	return false;	}
					},
					drag: function(event, ui) 
					{
						tmpLeft = ui.position.left % 5;
						tmpTop = ui.position.top % 5;
						
						ui.position.left = ui.position.left - tmpLeft;
						ui.position.top = ui.position.top - tmpTop;
					},
				});	
				
				$('#shape_'+ rand ).droppable
				({
					over: function(){	shouldCancel = true;	},
					out: function(){	shouldCancel = false;	}
				});
				
				arrRak.push( { id : rand } );
				
				PopOverShow( rand, 1, rak_lajur_nama, rak_lajur_prefix, 0 );
				
			}
		}
	);
		
	function PopOverShow( rand, IsNew, rak_lajur_nama, rak_lajur_prefix, isdb )
	{
		var txt = '';
		txt = txt + '<span class="input-group">';
		txt = txt + '	<button type="button" class="btn btn-warning form-control" onclick="AturRak(\''+ rand +'\', '+ IsNew +', \''+ rak_lajur_nama +'\',\''+ rak_lajur_prefix +'\',\''+ isdb +'\')">';
		txt = txt + '		<i class="fa fa-pencil"></i> Atur Rak';
		txt = txt + '	</button>';
		txt = txt + '	<hr>';
		txt = txt + '	<button type="button" class="btn btn-danger form-control" onclick="DeleteRak(\''+ rand +'\', '+ IsNew +')">';
		txt = txt + '		<i class="fa fa-times"></i> Hapus Rak';
		txt = txt + '	</button>';
		txt = txt + '</span>';
		
		$("#shape_"+ rand ).popover
		({
			html: true,
			placement: 'bottom',
			container: 'body',
			sanitize: false,
			content: txt
		});
	}
	
	function DeleteRak( P_ID, IsNew )
	{
		if( IsNew == 0 )
		{
			arrDRak.push( { P_ID : P_ID } );
		}
		
		var lrand = $(".hdARand").length;
		
		for( j=lrand-1 ; j>=0 ; j-- )
		{
			var Rands = $(".hdARand").eq(j).val();
			
			if( Rands == P_ID )
			{
				$(".hdARand").eq(j).remove();
				$(".hdAdepo_nama").eq(j).remove();
				$(".hdAdepo_id").eq(j).remove();
				$(".hdArak_lajur_nama").eq(j).remove();
				$(".hdAdepo_detail_nama").eq(j).remove();
				$(".hdAdepo_detail_id").eq(j).remove();
				$(".hdArak_lajur_width").eq(j).remove();
				$(".hdArak_lajur_length").eq(j).remove();
			}
		}
		
		$("#shape_"+ P_ID).popover('hide');
		
		$("#shape_"+ P_ID).remove();
		
		var lrak = arrRak.length;
		
		for( i=lrak-1 ; i>=0 ; i-- )
		{
			var ID = arrRak[i].id;
			
			if( ID == P_ID )
			{
				arrRak.splice( i, 1);
			}
		}
		
		if( $(".hdARand").length == 0 )
		{
			$("#cbDepo").prop('disabled', false );
			$("#cbDepoDetail").prop('disabled', false );
		}
	}
	
	function AturRak( rand, IsNew, rak_lajur_nama, rak_lajur_prefix, isdb )
	{
		$('[data-toggle="popover"]').popover('hide');
		
		$(".hdIDRak_"+ rand ).eq(0).val( $(".hdArak_lajur_nama_"+ rand ).eq(0).val().split(" ").join("") );
		
		$("#hdrandID").val( rand );
		
		var depo_id 	= $("#cbDepo option:selected").val();
		var depo_nama 	= $("#cbDepo option:selected").html();
		
		$("#vcbDepo").append('<option value="'+ depo_id +'">'+ depo_nama +'</option>');
		
		var depo_detail_id 		= $("#cbDepoDetail option:selected").val();
		var depo_detail_nama 	= $("#cbDepoDetail option:selected").html();
		$("#vcbDepoDetail").append('<option value="'+ depo_detail_id +'">'+ depo_detail_nama +'</option>');
		
		$("#vtxtrak_lajur_nama").val( rak_lajur_nama );
		$("#vtxtrak_lajur_prefix").val( rak_lajur_prefix );
		
		if( $.fn.DataTable.isDataTable('#tbrak') ) 
		{
			$('#tbrak').DataTable().destroy();
		}
			
		$("#tbrak").remove();
		
		var strdefault = '';
		
		strdefault += '<table width="180px" id="tbrak" class="table table-striped" width="200px">';
		strdefault += '	<thead>';
		strdefault += '		<tr id="trheader">';
		strdefault += '			<td colspan="3" width="200px" style="background-color: #bbbbbb;" align="center"><label class="control-label">Kolom</label></td>';
		strdefault += '		</tr>';
		strdefault += '		<tr id="trkolom">';
		strdefault += '			<td width="50px" style="background-color: #bbbbbb;" align="center"><strong>Hapus</strong></td>';
		strdefault += '			<td width="50px" style="background-color: #bbbbbb;" align="center"><strong>Level</strong></td>';
		strdefault += '			<td width="100px" style="background-color: #bbbbbb;" align="center"><strong>Edit Level</strong></td>';
		strdefault += '		</tr>';
		strdefault += '	</thead>';
		strdefault += '	<tbody></tbody>';
		strdefault += '</table>';
		
		$("#divRak").append( strdefault );

		$("#tbrak").DataTable(
		{
			retrieve: true,
			initComplete: function (settings, json) 
			{  
				$("#tbrak").wrap("<div style='overflow-y: scroll; overflow-x: auto; width: 100%; height: 400px; position:relative; padding: 0; margin: 0;'></div>");            
			},
			"dom": '<"left">rt',
			paging: false,
			ordering: false,
			fixedHeader: true
		});

		$("#tab1").removeAttr('class');
		$("#tab2").removeAttr('class');
		$("#tab1").attr('class','active');
		
		$("#depolist").removeClass('active');
		$("#detailrakdepolist").removeClass('active');
		$("#depolist").addClass('active');
		
		$("#tab1").attr('class','active');
		
		var Baris = arrRakLajurBaris.length;
		var Kolom = arrRakLajurKolom.length;
		var randParent = rand;
		
		// Cek kalau ada array yg sudah pernah dibuat 
		AddKolomRak( randParent, Baris, IsNew, isdb );
		
		$("#previewaddnewdeporak").modal('show');
	}
	
	$("#txtKolom").change
	(
		function()
		{
			var Kolom = parseInt( $("#txtKolom").val() );
			
			if( isNaN( Kolom ) ){	$("#txtKolom").val(1);	}
			
			if( $("#txtKolom").val() <= 0 )	{	$("#txtKolom").val(1);	} 
		}
	);
	
	$("#txtBaris").change
	(
		function()
		{
			var Baris = parseInt( $("#txtBaris").val() );
			
			if( isNaN( Baris ) ){	$("#txtBaris").val(1);	}
			if( $("#txtBaris").val() <= 0 )	{	$("#txtBaris").val(1);	} 
		}
	);
	
	function ChDepoRakDetailMenu( JSONDepoRak1 )
	{
		$("#tabledeporakdetailmenu > tbody").html('');
		
		var DepoRakDetail = JSON.parse( JSONDepoRak1 );
		
		if( DepoRakDetail.DepoRakDetail != 0 )
		{
			for( i=0 ; i<DepoRakDetail.DepoRakDetailMenu.length ; i++)
			{
				var DepoRakDetail_ID 		= DepoRakDetail.DepoRakDetailMenu[i].de;
				var DepoRak_ID 				= DepoRakDetail.DepoRakDetailMenu[i].DepoRak_ID;
				var Recip_Name 				= DepoRakDetail.DepoRakDetailMenu[i].Recip_Name;
				var KodePosDetail_ID 		= DepoRakDetail.DepoRakDetailMenu[i].KodePosDetail_ID;
				var KodePos		 			= DepoRakDetail.DepoRakDetailMenu[i].Recip_KodePos;
				var Recip_Address 			= DepoRakDetail.DepoRakDetailMenu[i].Recip_Address;
				var Recip_Phone 			= DepoRakDetail.DepoRakDetailMenu[i].Recip_Phone;
				var IsDefaultShipAddress 	= DepoRakDetail.DepoRakDetailMenu[i].IsDefaultShipAddress;
				
				if( IsDefaultShipAddress == 1 ) {	IsDefault = 'Default'; }
				else 							{	IsDefault = ''; }

				var IsActive 				= DepoRakDetail.DepoRakDetailMenu[i].IsActive;
				
				if( IsActive == 1 )	{	var StsIsActive = 'Aktif';	}
				else				{	var StsIsActive = 'Tidak Aktif';	}
				
				var strmenu = '';
				var strD = '';
				
				<?php
				if($Menu_Access["D"] == 1)
				{
					?>
					//strD = '<button style="width: 40px;" class="btn btn-danger btndeletedeporakdetailmenu form-control" onclick="DeleteDepoRakDetailMenu(\''+ DepoRakDetail_ID +'\', \''+ Recip_Name +'\', \''+ DepoRak_ID +'\', \''+ IsDefaultShipAddress +'\')"><i class="fa fa-times"></i></button>';
					<?php
				}
				?>
				
				var str = '';
				
				var strdef = '';
				if( IsDefaultShipAddress == 1 )	
				{	
					strdef = strdef + '<button type="button" class="btn btn-success form-control">';
					strdef = strdef + '		<i class="fa fa-check"></i> Default'; 
					strdef = strdef + '</button>'; 	
				}
				else
				{	
					strdef = strdef + '<button type="button" class="btn btn-danger form-control" onclick="ChangeDefaultAddress(\''+ DepoRakDetail_ID +'\', \''+ DepoRak_ID +'\',\''+ Recip_Name +'\')">';
					strdef = strdef + '		Set Ke Default'; 
					strdef = strdef + '</button>'; 	
				}
				
				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td width="20%">'+ Recip_Name +'</td>';
				strmenu = strmenu + '	<td width="20%">'+ Recip_Address +'</td>';
				strmenu = strmenu + '	<td width="20%">'+ KodePos +'</td>';
				strmenu = strmenu + '	<td width="10%">'+ Recip_Phone +'</td>';
				strmenu = strmenu + '	<td width="10%">'+ StsIsActive +'</td>';
				strmenu = strmenu + '	<td width="20%">'+ strdef +'</td>';
				strmenu = strmenu + '</tr>';
				
				$("#tabledeporakdetailmenu > tbody").append( strmenu );
			}
		}
		
		$('#tabledeporakdetailmenu').DataTable(
		{
			retrieve: true,
			"dom": '<"left"f>rtip'
		});
	}
	
	function ChangeDefaultAddress( DepoRakDetail_ID, DepoRak_ID, rak_lajur_nama )
	{
		$("#cda_DepoRakDetail_ID").val( DepoRakDetail_ID );
		$("#cda_DepoRak_ID").val( DepoRak_ID );
		$("#cda_lbnama").html( rak_lajur_nama );
		
		$("#previewchangedefaultaddress").modal('show');
	}
	
	$("#btnyeschangedefaultaddress").click
	(
		function()
		{
			var DepoRakDetail_ID 	= $("#cda_DepoRakDetail_ID").val();
			var DepoRak_ID 		= $("#cda_DepoRak_ID").val();
			
			$.ajax(
			{
				type: 'POST',    
				url: "<?= base_url('DepoRak/ChangeDefaultAddress') ?>",
				data: {	DepoRakDetail_ID: DepoRakDetail_ID, DepoRak_ID : DepoRak_ID },
				success: function( response )
				{
					if(response == 1)
					{
						var msg = 'Alamat Default berhasil diubah.';
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
					
					GetDepoRakMenu();
				}
			});	
		}
	);
	
	$("#btnback").click
	(
		function()
		{
			if( $(".btnkb").length == 0 )
			{
				$("#previewaddnewdeporak").modal('hide');
				ResetForm();
			}
			else
			{
				$("#previewconfirmbackdeporak").modal('show');
			}
			//GetDepoRakMenu();
		}
	);
	
	$("#btnyesback").click
	(
		function()
		{
			$("#previewaddnewdeporak").modal('hide');
			
			ResetForm();
		}
	);
	

	function ViewDepoRakDetailMenu( DepoRak_ID )
	{
		$.ajax(
		{
			type: 'POST',    
			url: "<?= base_url('DepoRak/GetDepoRakDetailMenu') ?>",
			data: {	DepoRak_ID : DepoRak_ID },
			success: function( response )
			{
				if(response)
				{
					ChDepoRakDetailMenu( response );
				}
			}
		});	
	}
	
	
	<?php
	if($Menu_Access["D"] == 1)
	{
	?>		
		$("#btnnodeletedeporak").click
		(
			function()
			{
				GetDepoRakMenu();	
			}
		);
	
		// Delete Voucher
		function DeleteDepoRakMenu( DepoRak_ID, rak_lajur_nama )
		{
			$("#lbdeletedeporak").html( rak_lajur_nama );
			$("#hddeletedeporakid").val( DepoRak_ID );
			
			$("#previewdeletedeporak").modal('show');
		}
		
		// Delete Voucher 1
		function DeleteDepoRakDetailMenu( DepoRakDetail_ID, Recip_Name, DepoRak_ID, IsDefaultShipAddress)
		{
			$("#lbdeletedeporakdetail").html( Recip_Name );
			$("#hddeletedeporakdetail_deporak_id").val( DepoRak_ID );
			$("#hddeletedeporakdetail_deporakva_id").val( DepoRakDetail_ID );

			$("#previewdeletedeporakdetail").modal('show');
		}
	
		$("#btnyesdeletedeporakdetail").click
		(
			function()
			{
				var DepoRakDetail_ID = $("#hddeletedeporakdetail_deporakdetail_id").val();
				var DepoRak_ID = $("#hddeletedeporakdetail_deporak_id").val();
				
				$.ajax(
				{
					type: 'POST',    
					url: "<?= base_url('DepoRak/DeleteDepoRakDetailMenu') ?>",
					data: {
							DepoRakDetail_ID : DepoRakDetail_ID, 
							DepoRak_ID : DepoRak_ID
							},
					success: function( response )
					{
						if(response == 1)
						{
							var msg = 'Detail DepoRak berhasil dihapus.';
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
							var msg = 'Detail DepoRak sudah dipakai pada Transaksi lain.';
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
							var msg = 'Tidak bisa menghapus semua Detail DepoRak.';
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
						
						GetDepoRakMenu();	
					}
				});	
			}
		);
		
		$("#btnyesdeletedeporak").click
		(
			function()
			{
				var DepoRak_ID = $("#hddeletedeporakid").val();
				
				$.ajax(
				{
					type: 'POST',    
					url: "<?= base_url('DepoRak/UpdateStatusDepoRakMenu') ?>",
					data: {
							DepoRak_ID : DepoRak_ID 
							},
					success: function( response )
					{
						if(response == 1)
						{
							var msg = 'DepoRak berhasil dinonaktifkan.';
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
							
							GetDepoRakMenu();
						}
						else if(response == 2)
						{
							var msg = 'Error: Data tidak bisa Menonaktifkan User sedang online.';
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
	
	<?php
	if($Menu_Access["U"] == 1)
	{
	?>	
		$("#updatebtnback").click
		(
			function()
			{
				Counter = 1;
				
				ResetForm();
				GetDepoRakMenu();
			}
		);
	
		function UpdateDepoRakMenu( DepoRak_ID )
		{
			//DepoRakCode = DepoRakKode;
			$.ajax(
			{
				type: 'POST',    
				url: "<?= base_url('DepoRak/GetUpdateDepoRakMenu') ?>",
				data: 
				{	
					DepoRak_ID : DepoRak_ID 
				},
				success: function( response )
				{
					if(response)
					{
						ChUpdateDepoRakMenu( response );
					}
				}
			});	
		}
	<?php
	}
	?>
	
	<?php
	if($Menu_Access["C"] == 1)
	{
	?>			
		$("#btnaddnewdeporak").removeAttr('style');
		
		$("#btnaddnewdeporak").click
		(
			function()
			{
				ResetForm();
				$("#previewaddnewdeporak").modal('show');
			}
		);
		
		$("#btnsaveaddnewdeporak").click
		(
			function()
			{	
				var randParent 	= $("#hdrandID").val();
				var prefix 		= $("#vtxtrak_lajur_prefix").val();
				var kb	 		= $(".btnkb").length;
				
				for( i=arrRakLajurDetail.length-1 ; i>= 0 ; i--) 
				{
					if(	arrRakLajurDetail[i].rak_id == randParent ) {	arrRakLajurDetail.splice(i, 1);	}
				}
				
				for( i=arrRakLajurKolom.length-1 ; i>= 0 ; i--) 
				{
					if(	arrRakLajurKolom[i].randParent == randParent ) 	{	arrRakLajurKolom.splice(i, 1);	}
				}
				
				for( i=arrRakLajurBaris.length-1 ; i>= 0 ; i--) 
				{
					if(	arrRakLajurBaris[i].randParent == randParent )	{	arrRakLajurBaris.splice(i, 1);	}
				}
				
				for( i=0 ; i<kb ; i++ )
				{
					var rand 		= $(".btnkb").eq(i).attr('data-rand');
					var baris 		= $(".btnkb").eq(i).attr('data-baris');
					var kolom 		= $(".btnkb").eq(i).attr('data-kolom');
					var nama 		= $(".btnkb").eq(i).html();
					var p_prefix 	= $(".btnkb").eq(i).attr('data-prefix');
					
					var isdb 		= $(".btnkb").eq(i).attr('data-isdb');
					
					
					arrRakLajurDetail.push( { prefix: p_prefix, randParent: randParent, rand: rand, isdb: isdb, rak_lajur_detail_baris: baris, rak_lajur_detail_kolom: kolom, rak_lajur_detail_nama: nama } );
				}
				
				var nbaris = $(".txttdbaris").length;
				
				for( j=0 ; j<nbaris ; j++ )
				{
					var rand 		= $(".txttdbaris").eq(j).attr('data-rand');
					
					var baris 		= $(".txttdbaris").eq(j).val();
					var barisasli 	= $(".txttdbarisdisabled").eq(j).val();
					
					arrRakLajurBaris.push( { prefix: prefix, randParent: randParent, rand: rand, rak_lajur_detail_baris: barisasli, rak_lajur_detail_baris_alias: baris } );
				}
				
				var nkolom = $(".txttdkolom").length;
				
				for( k=0 ; k<nkolom ; k++ )
				{
					var rand 		= $(".txttdkolom").eq(k).attr('data-rand');
					var kolom 		= $(".txttdkolom").eq(k).val();
					var kolomasli 	= $(".txttdkolomdisabled").eq(k).val();
				
					arrRakLajurKolom.push( { prefix: prefix, randParent: randParent, rand: rand, rak_lajur_detail_kolom: kolomasli, rak_lajur_detail_kolom_alias: kolom } );
				}
				
				$("#previewaddnewdeporak").modal('hide');
			}
		);
		
		$("#btnnosaveaddnewdeporak").click
		(
			function()
			{
				$("#previewaddnewdeporak").modal('show');
				$("#previewsaveaddnewdeporak").modal('hide');
			}
		);
		
		$("#btnsavelayout").click
		(
			function()
			{
				$("#btnsavelayout").prop('disabled', true );
				
				if( $("#txtrak_lajur_width").val() < 70 )
				{	
					var msg = 'Lebar Minimal 70';
					var type = 'error';
					
					new PNotify
					({
						title: 'Error',
						text: msg,
						type: msgtype,
						styling: 'bootstrap3',
						delay: 3000,
						stack: stack_center
					});
					
					$("#btnsavelayout").prop('disabled', false );
					
					return false;
				}
				if( $("#txtrak_lajur_length").val() < 70 )
				{
					var msg = 'Panjang Minimal 70';
					var type = 'error';
					
					new PNotify
					({
						title: 'Error',
						text: msg,
						type: msgtype,
						styling: 'bootstrap3',
						delay: 3000,
						stack: stack_center
					});
					
					$("#btnsavelayout").prop('disabled', false );
					
					return false;
				}
				
				var lrak = $(".hdARand").length;
				
				if( lrak == 0 )
				{
					var msg = 'Tidak ada Rak yang dibuat.';
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
					
					$("#btnsavelayout").prop('disabled', false );
					
					return false;
				}
				
				
				// Rak
				var depo_id 		= $("#cbDepo option:selected").val();
				var depo_detail_id 	= $("#cbDepoDetail option:selected").val();
				var rak_kode 		= $("#txtrak_kode").val();
				var rak_nama 		= $("#txtrak_nama").val();
				var rak_is_aktif 	= $("#chrak_is_aktif").prop('checked');
				
				if( rak_is_aktif == true )	{	rak_is_aktif = 1;	}
				else						{	rak_is_aktif = 0;	}
				
				// Rak Lajur
				var arrRakLajur = [];
				
				for( i=0 ; i<lrak ; i++ )
				{
					var rand 				= $(".hdARand").eq(i).val();
					
					var HIsDB 				= $(".hdAIsDB").eq(i).val();
					
					var rak_lajur_id	 	= $(".hdArak_lajur_id").eq(i).val();
					var rak_lajur_nama	 	= $(".hdArak_lajur_nama").eq(i).val();
					var rak_lajur_prefix	= $(".hdArak_lajur_prefix").eq(i).val();
					var rak_lajur_width 	= $(".hdArak_lajur_width").eq(i).val();
					var rak_lajur_length 	= $(".hdArak_lajur_length").eq(i).val();
					
					var rak_lajur_x 		= $("#shape_"+ rand ).position().left;
					var rak_lajur_y 		= $("#shape_"+ rand ).position().top;
					
					rak_lajur_x = parseInt( rak_lajur_x ).toFixed(0);
					rak_lajur_y = parseInt( rak_lajur_y ).toFixed(0);
					
					arrRakLajur.push( {	rand : rand, HIsDB: HIsDB, rak_lajur_id: rak_lajur_id, rak_lajur_nama: rak_lajur_nama, rak_lajur_prefix: rak_lajur_prefix, 
										rak_lajur_width : rak_lajur_width, rak_lajur_length : rak_lajur_length, 
										rak_lajur_x : rak_lajur_x, rak_lajur_y : rak_lajur_y } );
				}	
				
				// Rak Lajur Detail
				alert( JSON.stringify( arrRakLajur ) );
				$.ajax(
				{
					type: 'POST',    
					url: "<?= base_url('DepoRak/SaveDepoRakLayout') ?>",
					data: 
					{
						rak_id				: G_Rak_ID,
						
						depo_id 			: depo_id,
						depo_detail_id 		: depo_detail_id,
						rak_kode 			: rak_kode,
						rak_nama 			: rak_nama,
						rak_is_aktif 		: rak_is_aktif,
						
						arrRakLajur 		: arrRakLajur,
						
						arrRakLajurDetail	: arrRakLajurDetail
					},
					success: function( response )
					{
						if(response == 1)
						{
							var msg = 'Rak Berhasil dibuat.';
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
							var msg = 'Unable to change to Active Layout.';
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
				
				$("#btnsavelayout").prop('disabled', false );
					
			}
		);
	<?php
	}
	?>
	
	function ResetForm()
	{
		
	}
</script>