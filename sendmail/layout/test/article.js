
$(document).ready(function () {
	
	if (!window.console) window.console = {};
	if (!window.console.log) window.console.log = function () { };
   // the bindKeys() 
    
	$("#jqGrid").jqGrid({
		url: $base_url+'ajax/jqgrid_article',
		mtype: "GET",
		editurl: $base_url+'ajax/edit_jqgrid_article',
		datatype: "json",
		
		colModel: [
			{
			
				label: 'Article id', 
				name: 'article_id',
				key:true,
				editoptions: {size:60},
				editable: true,
				/*editrules:{
					
					custom_func: validatePositive,
					custom: true,
					required: true
				},*/
				width: 75,
				
			 },
			 {
			
				label: 'Category id', 
				name: 'category_id',
				editable: true,
				editoptions: {size:60},
				editrules:{
					//custom rules
					custom_func: validatePositive,
					custom: true,
					required: true
				},
				
				searchoptions: {
					// dataInit is the client-side event that fires upon initializing the toolbar search field for a column
					// use it to place a third party control to customize the toolbar
					dataInit: function (element) {
						$(element).autocomplete({
							id: 'AutoComplete',
							source: function(request, response){
								this.xhr = $.ajax({
									url: $base_url+'ajax/autocomplete_category',
									/*url: 'http://trirand.com/blog/phpjqgrid/examples/jsonp/autocompletep.php?callback=?&acelem=ShipName',*/
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
				},
				width: 75,
				/* edittype: "select",
                         editoptions: {
                             value: "ALFKI:ALFKI;ANATR:ANATR;ANTON:ANTON;AROUT:AROUT;BERGS:BERGS;BLAUS:BLAUS;BLONP:BLONP;BOLID:BOLID;BONAP:BONAP;BOTTM:BOTTM;BSBEV:BSBEV;CACTU:CACTU;CENTC:CENTC;CHOPS:CHOPS;COMMI:COMMI;CONSH:CONSH;DRACD:DRACD;DUMON:DUMON;EASTC:EASTC;ERNSH:ERNSH;FAMIA:FAMIA;FISSA:FISSA;FOLIG:FOLIG;FOLKO:FOLKO;FRANK:FRANK;FRANR:FRANR;FRANS:FRANS;FURIB:FURIB;GALED:GALED;GODOS:GODOS;GOURL:GOURL;GREAL:GREAL;GROSR:GROSR;HANAR:HANAR;HILAA:HILAA;HUNGC:HUNGC;HUNGO:HUNGO;ISLAT:ISLAT;KOENE:KOENE;LACOR:LACOR;LAMAI:LAMAI;LAUGB:LAUGB;LAZYK:LAZYK;LEHMS:LEHMS;LETSS:LETSS;LILAS:LILAS;LINOD:LINOD;LONEP:LONEP;MAGAA:MAGAA;MAISD:MAISD;MEREP:MEREP;MORGK:MORGK;NORTS:NORTS;OCEAN:OCEAN;OLDWO:OLDWO;OTTIK:OTTIK;PARIS:PARIS;PERIC:PERIC;PICCO:PICCO;PRINI:PRINI;QUEDE:QUEDE;QUEEN:QUEEN;QUICK:QUICK;RANCH:RANCH;RATTC:RATTC;REGGC:REGGC;RICAR:RICAR;RICSU:RICSU;ROMEY:ROMEY;SANTG:SANTG;SAVEA:SAVEA;SEVES:SEVES;SIMOB:SIMOB;SPECD:SPECD;SPLIR:SPLIR;SUPRD:SUPRD;THEBI:THEBI;THECR:THECR;TOMSP:TOMSP;TORTU:TORTU;TRADH:TRADH;TRAIH:TRAIH;VAFFE:VAFFE;VICTE:VICTE;VINET:VINET;WANDK:WANDK;WARTH:WARTH;WELLI:WELLI;WHITC:WHITC;WILMK:WILMK;WOLZA:WOLZA"
                         }*/
				
			 },
			 
			 {
			
				label: 'Status', 
				name: 'status',
				editable: true,
				editrules:{
					//custom rules
					custom_func: validatePositive,
					custom: true,
					required: true
				},
				width: 75,
			 	edittype: "custom",
					editoptions: {
						custom_value: getStatusElementValue,
						custom_element: createStatusEditElement
				}
				
			 },
			 {
			
				label: 'Sort order', 
				name: 'sort_order',
				editable: true,
				editoptions: {size:60},
				editrules:{
					//custom rules
					custom_func: validatePositive,
					custom: true,
					required: true,
					number: true,
				},
				width: 75,
				
			 },
			  {
			
				label: 'Image', 
				name: 'image',
				editable: true,
				edittype: 'file',
				editoptions: {
					enctype: "multipart/form-data"
				},
				editrules:{
					//custom rules
					custom_func: validatePositive,
					custom: true,
					required: true
				},
				index: 'article_id',
				formatter: formatImage,
				width: 75,
				search:false,
				align: 'center',
				
			 },
			  {
			
				label: 'Viewed', 
				name: 'viewed',
				editable: true,
				editoptions: {size:60},
				editrules:{
					//custom rules
					custom_func: validatePositive,
					custom: true,
					required: true,
					number: true,
				},
				width: 75,
				
			 },
			 {
			
				label: 'Date added', 
				name: 'date_added',
				
				sorttype:'date',
				formatter: 'date',
				srcformat: 'Y-m-d',
				newformat: 'n/j/Y',
				editable: true,
				editrules:{
					//custom rules
					custom_func: validatePositive,
					custom: true,
					required: true
				},
				  editoptions: {
                            // dataInit is the client-side event that fires upon initializing the toolbar search field for a column
                            // use it to place a third party control to customize the toolbar
                            dataInit: function (element) {
                                $(element).datepicker({
                                    id: 'orderDate_datePicker',
                                    dateFormat: 'd/m/yy',
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
                },
				width: 150,
				
			 },
			  {
			
				label: 'User id', 
				name: 'user_id',
				editable: true,
				editoptions: {size:60},
				editrules:{
					//custom rules
					custom_func: validatePositive,
					custom: true,
					required: true,
					number: true,
				},
				width: 75,
				
			 },
			  {
			
				label: 'Url', 
				name: 'url',
				editable: true,
				editoptions: {size:60},
				editrules:{
					//custom rules
					custom_func: validatePositive,
					custom: true,
					required: true,
					url: true,
				},
				searchoptions: {
					// dataInit is the client-side event that fires upon initializing the toolbar search field for a column
					// use it to place a third party control to customize the toolbar
					dataInit: function (element) {
						$(element).autocomplete({
							id: 'AutoComplete',
							source: function(request, response){
								this.xhr = $.ajax({
									url: $base_url+'ajax/autocomplete_url',
									/*url: 'http://trirand.com/blog/phpjqgrid/examples/jsonp/autocompletep.php?callback=?&acelem=ShipName',*/
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
				},
				width: 75,
				
			 },
			
			
		],
		viewrecords: true,
		
		onSelectRow: editRow,
		width: 1320,
		height: 400,
		rowNum: 20,
		multiselect: true,
		rownumbers: true,
		
		//shrinkToFit: true,
		sortname: 'article_id',
		scroll: 1,
		//emptyrecords: 'Scroll to bottom to retrieve new page',
		//altRows: true,
		pager: "#jqGridPager",
		
		
	});
	jQuery("#jqGrid").jqGrid('sortableRows');
	 var lastSelection;
	 
		 function formatImage(cellValue, options, rowObject) {
				var imageHtml = "<img style ='width:30px; height:20px;' src='" + $base_url + cellValue + "' originalValue='" + cellValue + "' />";
				return imageHtml;
			}

		function editRow(id) {
		
			if (id && id !== lastSelection) {
				var grid = $("#jqGrid");
				grid.jqGrid('restoreRow',lastSelection);

				var editParameters = {
					keys: true,
					successfunc: editSuccessful,
					errorfunc: editFailed
				};

				grid.jqGrid('editRow',id, editParameters);
				lastSelection = id;
			}
		}

		function editSuccessful() {
			console.log("success");
		}

		function editFailed() {
			console.log("fail");
		}

		function validatePositive(value, column) {
			if ( isNaN(value) && value < 0)
				return [false, "Please enter a positive value or correct value"];
			else
				return [true, ""];
		}
		// radio button  
		function createStatusEditElement(value, editOptions) {

                var span = $("<span />");
               /* var label = $("<span />", { html: "0" });
                var radio = $("<input>", { type: "radio", value: "0", name: "freight", id: "zero", checked: (value != 25 && value != 50 && value != 100) });*/
                var label1 = $("<span />", { html: "Enabled" });
                var radio1 = $("<input>", { type: "radio", value: "1", name: "freight", id: "fifty", checked: value == 1 });
                var label2 = $("<span />", { html: "Disabled" });
                var radio2 = $("<input>", { type: "radio", value: "0", name: "freight", id: "fifty", checked: value == 0 });
               /* var label3 = $("<span />", { html: "100" });
                var radio3 = $("<input>", { type: "radio", value: "100", name: "freight", id: "hundred", checked: value == 100 });*/

                span/*.append(label).append(radio)*/.append(label1).append(radio1).append(label2).append(radio2)/*.append(label3).append(radio3)*/;

                return span;
            }
			function getStatusElementValue(elem, oper, value) {
                if (oper === "set") {
                    var radioButton = $(elem).find("input:radio[value='" + value + "']");
                    if (radioButton.length > 0) {
                        radioButton.prop("checked", true);
                    }
                }

                if (oper === "get") {
                    return $(elem).find("input:radio:checked").val();
                }
            }
	// end radio button
	
	// upload image
	function UploadImage(response, postdata) {

		var data = $.parseJSON(response.responseText);
	
		if (data.success == true) {
			if ($("#image").val() != "") {
				ajaxFileUpload(data.id);
			}
		}  
	
		return [data.success, data.message, data.id];
	
	}

	function ajaxFileUpload(id) 
	{
		$("#loading").ajaxStart(function () {
			$(this).show();
		})
		.ajaxComplete(function () {
			$(this).hide();
		});
	
		$.ajaxFileUpload
		(
			{
				url: '@Url.Action("UploadImage")',
				secureuri: false,
				fileElementId: 'image',
				dataType: 'json',
				data: { id: id },
				success: function (data, status) {
	
					if (typeof (data.success) != 'undefined') {
						if (data.success == true) {
							return;
						} else {
							alert(data.message);
						}
					}
					else {
						return alert('Failed to upload logo!');
					}
				},
				error: function (data, status, e) {
					return alert('Failed to upload logo!');
				}
			}
		)          
	}            
	//end upload image	
	
	// activate the toolbar searching
	$('#jqGrid').jqGrid('filterToolbar');
	$('#jqGrid').jqGrid('navGrid','#jqGridPager',
                // the buttons to appear on the toolbar of the grid
                { edit: true, add: true, del: true, search: false, refresh: true, view: false, position: "left", cloneToTop: true },
                // options for the Edit Dialog
                {
                    editCaption: "The Edit Dialog",
                    recreateForm: true,
                    closeAfterEdit: true,
					 closeOnEscape : true,
					 left: 300,
					 top:200,
					 width:800,
					
					/*afterSubmit : function( data, postdata, oper) {
						var response = data.responseJSON;
						
						if (response.hasOwnProperty("error")) {
							if(response.error.length) {
								return [false,response.error ];
							}
						}
						return [true,"",""];
					},
                    errorTextFormat: function (data) {
                        return 'Error: ' + data.responseText
                    }*/
                },
                // options for the Add Dialog
                {
                    closeAfterAdd: true,
                    recreateForm: true,
                    errorTextFormat: function (data) {
                        return 'Error: ' + data.responseText
                    }
                },
                // options for the Delete Dailog
                {
                    errorTextFormat: function (data) {
                        return 'Error: ' + data.responseText
                    }
                });


});
