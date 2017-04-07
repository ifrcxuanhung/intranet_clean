
$(document).ready(function () {
	if (!window.console) window.console = {};
	if (!window.console.log) window.console.log = function () { };
   
	$("#jqGrid").jqGrid({
		url: $base_url+'ajax/jqgrid_test',
		mtype: "GET",
		editurl: 'clientArray',
		datatype: "json",
		
		colModel: [
			{ label: 'Code', 
				name: 'code',
				
				editable: true,
				editrules : { 
					//email: true,
					required: true
				},
				width: 75,
				
			 },
			{ 
			
				label: 'Date', 
				name: 'date', 
				sorttype:'date',
				formatter: 'date',
				srcformat: 'Y-m-d',
				newformat: 'n/j/Y',
				editable: true,
				editrules : { 
					//email: true,
					required: true
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
			
				label: 'Close', 
				name: 'close',
				editable: true,
				editrules : { 
					number: true,
					required: true
				},
				width: 150 
			},
			{ 
				label:'Type', 
				name: 'type', 
				editable: true,
				editrules : { 
					//email: true,
					required: true
				},
				width: 150 
			},
			 { 
				 label:'Currency from', 
				 name: 'cur_from', 
				 editable: true,
				editrules : { 
					//email: true,
					required: true
				},
				 width: 150 
			 },
			  { 
				  label:'Currency to', 
				  name: 'cur_to', 
				  editable: true,
					editrules : { 
						//email: true,
						required: true
					},
				  width: 150
			   },
		],
		viewrecords: true,
		
		onSelectRow: editRow,
		width: 1320,
		height: 400,
		rowNum: 20,
		 multiselect: true,
		pager: "#jqGridPager"
	});
	
	 var lastSelection;

		function editRow(id) {
			console.log("eagw");
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
	
	
	// activate the toolbar searching
	$('#jqGrid').jqGrid('filterToolbar');
	$('#jqGrid').jqGrid('navGrid',"#jqGridPager", {                
		search: true, // show search button on the toolbar
		add: true,
		edit: true,
		del: true,
		refresh: true
	});


});
