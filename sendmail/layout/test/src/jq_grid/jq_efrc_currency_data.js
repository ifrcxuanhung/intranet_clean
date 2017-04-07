// JavaScript Document

        $(document).ready(function () {
			
           var form_currency = $('#form_currency').serialize();
		  
		  // console.log($('#filter_code').val());
		 
            $("#jqGrid").jqGrid({
                url: 'ajax/jq_efrc_currency_data',
				editurl: 'ajax/edit_del_add_jq_efrc_currency_data',
                mtype: "POST",
                datatype: "json",
				postData:{form_currency:form_currency},
                page: 1,
                colModel: [
                    {  
						label : "Code",
						//sorttype: 'integer',
						name: 'code', 
						key: false, 
						editable: true,
						editrules : { required: true},
						width: 250 
					},
					 { 
						label: "Date",
                        name: 'date',
                        width: 150,
						editable: true,
						sorttype:'date',
						editrules : {
							required: true
						},
						/*formatter: 'date',
						srcformat: 'm/d/yy',
						newformat: 'n/j/Y',*/
						 editoptions: {
                            // dataInit is the client-side event that fires upon initializing the toolbar search field for a column
                            // use it to place a third party control to customize the toolbar
                            dataInit: function (element) {
                                $(element).datepicker({
                                    id: 'orderDate_datePicker',
                                    dateFormat: 'm/d/yy',
                                    //minDate: new Date(2010, 0, 1),
                                    maxDate: new Date(2020, 0, 1),
                                    showOn: 'focus'
                                });
                            }
                        },
                        searchoptions: {
                            // dataInit is the client-side event that fires upon initializing the toolbar search field for a column
                            // use it to place a third party control to customize the toolbar
                            dataInit: function (element) {
                                $(element).datepicker({
                                    id: 'orderDate_datePicker',
                                    dateFormat: 'm/d/yy',
                                    //minDate: new Date(2010, 0, 1),
                                    maxDate: new Date(2020, 0, 1),
                                    showOn: 'focus'
                                });
                            }
                        }
                    },           
                  
					 {   label : "Close",
						//sorttype: 'integer',
						name: 'close', 
						editrules : { 
							//number: true,
							required: true
						},
						key: false, 
						editable: true,
						width: 150,
						align:'right',
						formatter: 'number',
						formatoptions: 
							{ decimalSeparator: ".", thousandsSeparator: ",", decimalPlaces: 6, defaultValue: '0.0000' },
					},
					
					 /*{
						label: "Type",
						editable: true,
                        name: 'type',
                        width: 150,
                        // stype defines the search type control - in this case HTML select (dropdownlist)
                        stype: "select",
                        // searchoptions value - name values pairs for the dropdown - they will appear as options
                        searchoptions: { value: ":[All];ALFKI:ALFKI;ANATR:ANATR;ANTON:ANTON;AROUT:AROUT;BERGS:BERGS;BLAUS:BLAUS;BLONP:BLONP;BOLID:BOLID;BONAP:BONAP;BOTTM:BOTTM;BSBEV:BSBEV;CACTU:CACTU;CENTC:CENTC;CHOPS:CHOPS;COMMI:COMMI;CONSH:CONSH;DRACD:DRACD;DUMON:DUMON;EASTC:EASTC;ERNSH:ERNSH;FAMIA:FAMIA;FOLIG:FOLIG;FOLKO:FOLKO;FRANK:FRANK;FRANR:FRANR;FRANS:FRANS;FURIB:FURIB;GALED:GALED;GODOS:GODOS;GOURL:GOURL;GREAL:GREAL;GROSR:GROSR;HANAR:HANAR;HILAA:HILAA;HUNGC:HUNGC;HUNGO:HUNGO;ISLAT:ISLAT;KOENE:KOENE;LACOR:LACOR;LAMAI:LAMAI;LAUGB:LAUGB;LAZYK:LAZYK;LEHMS:LEHMS;LETSS:LETSS;LILAS:LILAS;LINOD:LINOD;LONEP:LONEP;MAGAA:MAGAA;MAISD:MAISD;MEREP:MEREP;MORGK:MORGK;NORTS:NORTS;OCEAN:OCEAN;OLDWO:OLDWO;OTTIK:OTTIK;PERIC:PERIC;PICCO:PICCO;PRINI:PRINI;QUEDE:QUEDE;QUEEN:QUEEN;QUICK:QUICK;RANCH:RANCH;RATTC:RATTC;REGGC:REGGC;RICAR:RICAR;RICSU:RICSU;ROMEY:ROMEY;SANTG:SANTG;SAVEA:SAVEA;SEVES:SEVES;SIMOB:SIMOB;SPECD:SPECD;SPLIR:SPLIR;SUPRD:SUPRD;THEBI:THEBI;THECR:THECR;TOMSP:TOMSP;TORTU:TORTU;TRADH:TRADH;TRAIH:TRAIH;VAFFE:VAFFE;VICTE:VICTE;VINET:VINET;WANDK:WANDK;WARTH:WARTH;WELLI:WELLI;WHITC:WHITC;WILMK:WILMK;WOLZA:WOLZA" }
                    },*/
					
					 /*{
						label : "Type",
						editable: true,
                        name: 'type',
						editrules : {
							required: true
						},
                        width: 150,
                        searchoptions: {
                            // dataInit is the client-side event that fires upon initializing the toolbar search field for a column
                            // use it to place a third party control to customize the toolbar
                            dataInit: function (element) {
                                $(element).autocomplete({
                                    id: 'AutoComplete',
                                    source: function(request, response){
										this.xhr = $.ajax({
											url:'ajax/autocomplete_type',
											data: request,
											dataType: "jsonp",
											success: function( data ) {
												response( data );
											},
											error: function(model, response, options) {
												response([]);
											}
										});
									},
                                    autoFocus: true
                                });
                            },
							sopt : ['cn']
                        }
                    },*/
					 
                           
                    {
						label : "From",
						editable: true,
                        name: 'cur_from',
						editrules : {
							required: true
						},
                        width: 150,
                        searchoptions: {
                            // dataInit is the client-side event that fires upon initializing the toolbar search field for a column
                            // use it to place a third party control to customize the toolbar
                            dataInit: function (element) {
                                $(element).autocomplete({
                                    id: 'AutoComplete',
                                    source: function(request, response){
										this.xhr = $.ajax({
											url: 'ajax/autocomplete_currency_from',
											data: request,
											dataType: "jsonp",
											success: function( data ) {
												response( data );
											},
											error: function(model, response, options) {
												response([]);
											}
										});
									},
                                    autoFocus: true
                                });
                            },
							sopt : ['cn']
                        }
                    },
					
					{
						label : "To",
                        name: 'cur_to',
						editable: true,
						editrules : {
							required: true
						},
                        width: 150,
                        searchoptions: {
                            // dataInit is the client-side event that fires upon initializing the toolbar search field for a column
                            // use it to place a third party control to customize the toolbar
                            dataInit: function (element) {
                                $(element).autocomplete({
                                    id: 'AutoComplete',
                                    source: function(request, response){
										this.xhr = $.ajax({
											url: 'ajax/autocomplete_currency_to',
											data: request,
											dataType: "jsonp",
											success: function( data ) {
												response( data );
											},
											error: function(model, response, options) {
												response([]);
											}
										});
									},
                                    autoFocus: true
                                });
                            },
							sopt : ['cn']
                        }
                    },
                  
                ],
				//loadonce: true,
				//onSelectRow: editRow,
				viewrecords: true,
               // width: 1320,
			    autowidth: true,
             
				//autoheight: true,
              	height:"100%",
                rowNum: 20,
				rownumbers: true, 
                pager: "#jqGridPager",
				  caption: 'EFRC CURRENCY DATA',
				//scroll: 1,
				//multiselect: true
            });
			
			 var lastSelection;

            function editRow(id) {
                if (id && id !== lastSelection) {
                    var grid = $("#jqGrid");
                    grid.jqGrid('restoreRow',lastSelection);
                    grid.jqGrid('editRow',id, {keys:true, focusField: 4});
                    lastSelection = id;
                }
            }
			
			// activate the toolbar searching
            $('#jqGrid').jqGrid('filterToolbar');
			$('#jqGrid').jqGrid('navGrid',"#jqGridPager", {                
                search: false, // show search button on the toolbar
                add: true,
                edit: true,
                del: true,
                refresh: true
            });
			
			
			// gan lai cho filter phia duoi
			var code = $('#filter_code').val();
			$('#gs_code').val(code);
			var date = $('#filter_date').val();
			$('#gs_date').val(date);
			var closes = $('#filter_close').val();
			$('#gs_close').val(closes);
			var cur_from = $('#filter_cur_from').val();
			$('#gs_cur_from').val(cur_from);
			var cur_to = $('#filter_cur_to').val();
			$('#gs_cur_to').val(cur_to);

        
        });
 
