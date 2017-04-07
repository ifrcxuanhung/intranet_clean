define([
    'jquery',
    'underscore',
    'backbone'
    ], function($, _, Backbone){
        var tableView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function() {

            },
            events: {
                 "click a.show-modal": "actionShowModal",
				 "click .add-new-item" : "actionAddItem",
				 "click .deleteField": "actionDeleteField",
				 "click .duplicateField": "actionDuplicateField",
                 "change .group-checkable": "actionChangeAllCheckbox",
                 "change tbody tr .checkboxes": "actionChangeCheckbox",
                 "click .runquery": "actionRunQuery",
				 "click .save-modal": "actionSaveModal",
                 "click a.fileinput-exists": "actionRemoveImage",
				 "click .runDataReport": "actionRunDataReport",
                 "click .exportCsv": "actionExportCsv",
				 "click .exportTxt": "actionExportTxt",
				 "click .exportXls": "actionExportXls",
				 "click .exportPdf": "actionExportPdf"
            },
            actionExportCsv: function(event) {
			    var page = $(".pagination-panel-input").val();
                var limit = $('select[name="table_length"] option:selected').val();
                var start = (page*limit)-limit;
                $('#iDisplaystart').val(start);
				$("#actexport").val('exportCsv');
				$('#form_tab').submit();
            },
			actionExportTxt: function(event) {
			    var page = $(".pagination-panel-input").val();
                var limit = $('select[name="table_length"] option:selected').val();
                var start = (page*limit)-limit;
                $('#iDisplaystart').val(start);
				$("#actexport").val('exportTxt');
                console.log(limit);
                console.log(start);
                console.log(page);
				$('#form_tab').submit();
                
               // console.log(page);
               // console.log(limit);
            },
			
			actionExportXls: function(event) {
			    var page = $(".pagination-panel-input").val();
                var limit = $('select[name="table_length"] option:selected').val();
                var start = (page*limit)-limit;
                $('#iDisplaystart').val(start);
				$("#actexport").val('exportXls');
				$('#form_tab').submit();
            },
			actionExportPdf: function(event) {
			    var page = $(".pagination-panel-input").val();
                var limit = $('select[name="table_length"] option:selected').val();
                var start = (page*limit)-limit;
                $('#iDisplaystart').val(start);
				$("#actexport").val('exportPdf');
				$('#form_tab').submit();
            },
            actionRemoveImage:function(event){
             
                var $this = $(event.currentTarget);
				var type = $this.parents().parents().attr('data-name');
				var id_document = $("#id").val();
                $('.img_upload_'+type).attr('src',$base_url+'assets/images/no-image.jpg');
				$('.avatar_'+type).attr('value','');
				$.ajax({
					url: $base_url + 'ajax/remove_images',
					type: 'POST',
					data: {id_document: id_document, type:type},
					async: false,
					success: function(response) {
					   if((response == true) || response) {
                            $("div.alert-success").text("Remove successful");
                            $("div.alert-success").fadeIn().delay(1500).fadeOut();
                            
                        } else {
                            $("div.alert-warning").text("Remove fail");
                            $("div.alert-warning").fadeIn().delay(1500).fadeOut();
                        }
						var table = $('#table').DataTable();
                        table.draw( false );						
					}
				});
            },
			actionDuplicateField: function(event) {
                event.preventDefault();
				if (confirm("Are you sure to duplicate this row ?") == false) {
					return;
				}
				var $this = $(event.currentTarget);
                $.ajax({
					url: $base_url + 'ajax/insert_row',
					type: 'POST',
					data: {table_name: $this.attr("table_name"), keys_value: $this.attr('keys_value')},
					async: false,
					success: function(response) {
					   if((response == true) || response) {
                            $("div.alert-success").text("Duplicated successful");
                            $("div.alert-success").fadeIn().delay(1500).fadeOut();
                            
                        } else {
                            $("div.alert-success").addClass('alert-warning');
                            $("div.alert-success").removeClass('alert-success');
                            $("div.alert-warning").text("Duplicate fail");
                            $("div.alert-warning").fadeIn().delay(1500).fadeOut();
                        }
						var table = $('#table').DataTable();
                        table.draw( false );						
					}
				});
            },
			actionSaveModal: function(event){
				event.preventDefault();
				$("#check_modal").submit(function(e) {                       
						for ( instance in CKEDITOR.instances )
						{
							CKEDITOR.instances[instance].updateElement();
						}
						var formData = new FormData(this);
                        $.ajax({
                            url: $base_url + 'ajax/update_modal',
                            type: 'POST',
                            mimeType: "multipart/form-data",
                            data: formData,
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function(response) {
								rs = JSON.parse(response);
                                if(rs.status == true) {
									$("div.alert-success").text('Updated!');
									$("div.alert-success").fadeIn();
									var table = $('#table').DataTable();
									table.draw( false );
									$('#modal').modal('hide');
								} else {
									$("div.alert-success").text("Update fail");
									$("div.alert-danger").fadeIn();
								}
								$("div.alert").delay(1500).fadeOut();
                            }
                        });
                        e.preventDefault();
                    });
                    $("#check_modal").submit();
			},
			actionChangeAllCheckbox: function(event) {
                var $this = $(event.currentTarget);
			    var set = jQuery($this).attr("data-set");
                var checked = jQuery($this).is(":checked");
               // var selectedIds = [];
                jQuery(set).each(function () {
                    if (checked) {
                        $(set).attr("checked", true);
                        $(set).parents('tr').addClass("active");
                    } else {
                        $(set).attr("checked", false);
                        $(set).parents('tr').removeClass("active");
                    }
                });
              //  alert(selectedIds);
                jQuery.uniform.update(set);
			},
            actionChangeCheckbox: function(event) {
                var $this = $(event.currentTarget);
			    $($this).parents('tr').toggleClass("active");
			//	alert($this.val());
			},
			actionRunDataReport: function(event){
				 Metronic.blockUI({
					target: '#blockui_sample_4_portlet_body',
					boxed: true,
					message: 'Please wait...'
				});
			   var table = $('#table');
               var dataArr = [];
               $('#table tr.active').each(function(){
                   // alert($(this).find('.checkboxes').val());// just to see the rowid's -> $(this).closest('tr[id]').attr('id')
                    dataArr.push($(this).find('.checkboxes').val()); // insert rowid's to array
               });
			   
               if(dataArr.length > 0){  
               // send data to back-end via ajax
               $.ajax({
                      type : "POST",
                      url : $base_url + 'cronjob/data_report',
                      data : {dataArr:dataArr},
                      success: function(res) {
						   $("div.alert-success").text("Run successful");
                            $("div.alert-success").fadeIn().delay(3500).fadeOut();
							var table = $('#table').DataTable();
                        	table.draw( false );
							Metronic.unblockUI('#blockui_sample_4_portlet_body');
                      },
					  
                });
              }else{
                    $("div.alert-success").addClass('alert-warning');
                    $("div.alert-success").removeClass('alert-success');
                    $("div.alert-warning").text("Please select a item !");
                    $("div.alert-warning").fadeIn().delay(1500).fadeOut();
              }
 //                console.log(table.find('tr.active').children('.checkboxes').value());
			},
			actionRunQuery: function(event){
			   var table = $('#table');
               var dataArr = [];
               $('#table tr.active').each(function(){
                   // alert($(this).find('.checkboxes').val());// just to see the rowid's -> $(this).closest('tr[id]').attr('id')
                    dataArr.push($(this).find('.checkboxes').val()); // insert rowid's to array
               });
			   
               if(dataArr.length > 0){  
               // send data to back-end via ajax
               $.ajax({
                      type : "POST",
                      url : $base_url + 'ajax/runQuery',
                      data : {dataArr:dataArr},
                      success: function(res) {
						  response = JSON.parse(res);
                      //  console.log(res);
                         if(response.success == response.total) {
                            $("div.alert-success").text("Run successful");
                            $("div.alert-success").fadeIn().delay(3500).fadeOut();
                        } else  {
							$text = 'Run success ' + response.success +' query  and run fail '+ (response.total-response.success) +' query. Please check query: ' + response.message; 
                          //  alert('done not');
                            $("div.alert-success").addClass('alert-warning');
                            $("div.alert-success").removeClass('alert-success');
                            $("div.alert-warning").text($text);
                            $("div.alert-warning").fadeIn().delay(3500).fadeOut();
                        } 
						
                          //alert(data); //alert the data from the server
                      },
                });
              }else{
                    $("div.alert-success").addClass('alert-warning');
                    $("div.alert-success").removeClass('alert-success');
                    $("div.alert-warning").text("Please select a query !");
                    $("div.alert-warning").fadeIn().delay(1500).fadeOut();
              }
 //                console.log(table.find('tr.active').children('.checkboxes').value());
			},
			actionAddItem: function(event) {				
                event.preventDefault();
				var $this = $(event.currentTarget);
				$id = $this.attr('value_id');
				if ($('.select2_'+$id+' > option[value="' + $('#'+$id+'_add_new').val() + '"]').length === 0) {  
					$('.select2_'+$id).append($('<option/>', { 
						value: $('#'+$id+'_add_new').val(),
						text : $('#'+$id+'_add_new').val() 
					}));
				 // doesn't exist so add here
				}
				var selectList = $('.select2_'+$id+' > option');

				selectList.sort(function(a,b){
					a = a.value;
					b = b.value;
					return a == b ? 0 : (a < b ? -1 : 1);				
				});
				
				$('.select2_'+$id).html(selectList);
				$('.select2_'+$id).val($('#'+$id+'_add_new').val());
				$('#'+$id+'_add_new').val('');
				
			},
			actionDeleteField: function(event) {
                event.preventDefault();
				if (confirm("Are you sure to delete this row ?") == false) {
					return;
				}
				var $this = $(event.currentTarget);
                $.ajax({
					url: $base_url + 'ajax/delete_row',
					type: 'POST',
					data: {table_name: $this.attr("table_name"), keys_value: $this.attr('keys_value')},
					async: false,
					success: function(response) {
					   if((response == true) || response) {
                            $("div.alert-success").text("Deleted successful");
                            $("div.alert-success").fadeIn().delay(1500).fadeOut();
                            
                        } else {
                            $("div.alert-success").addClass('alert-warning');
                            $("div.alert-success").removeClass('alert-success');
                            $("div.alert-warning").text("Delete fail");
                            $("div.alert-warning").fadeIn().delay(1500).fadeOut();
                        }
						var table = $('#table').DataTable();
                        table.draw( false );						
					}
				});
            },
            actionShowModal: function(event) {
                var $this = $(event.currentTarget);
                var type = $this.attr("type-modal");
				var validate = $this.attr("keys_value");
                $.ajax({
                    url: $base_url + "ajax/show_modal",
                    type: "POST",
                    data: {table_name: $this.attr("table_name"), keys_value: $this.attr("keys_value")},
                    async: false,
                    success: function(response) {
                        $("#modal .modal-content").html(response);	
					//	$('.summernote').each(function(index, value){
//							   $('#'+$(this).attr('id')).summernote({height: 80});
//						});
                        if (jQuery().datepicker) {
                                $('.date-picker').datepicker({
                                    orientation: "left",
                                    autoclose: true
                                });
                                $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
                        } 
                       
                          
                    }
                });
                $('.ckeditor').each(function(index, value){
                	CKEDITOR.replace( $(this).attr('name'), {
                		height : 150,
                		colorButton_foreStyle : {
                			element: 'font',
                			attributes: { 'color': '#(color)' }
                		},
                		colorButton_backStyle : {
                			element: 'font',
                			styles: { 'background-color': '#(color)' }
                		}
                	});
                });
            },
            index: function(){
              $(document).bind("keydown", function(event){
                    if(event.which=="13")
                    {
                        $(".filter-submit").click();
                    }
               });
               $(function () {
                    
               });;
               $(document).ready(function(){
			
			
			
			//use for loop because i have multi ckeditor in page.
				for (instance in CKEDITOR.instances) {
					var editor = CKEDITOR.instances[instance];
					if (editor) {
						// Call showToolBarDiv() when editor get the focus
						editor.on('focus', function (event) {
							showToolBarDiv(event);
						});
			
						// Call hideToolBarDiv() when editor loses the focus
						editor.on('blur', function (event) {
							hideToolBarDiv(event);
						});
			
						//Whenever CKEditor get focus. We will show the toolbar span.
						function showToolBarDiv(event) {
							//'event.editor.id' returns the id of the spans used in ckeditr.
							$('#'+event.editor.id+'_top').show();
						}
			
						function hideToolBarDiv(event) {                    
							//'event.editor.id' returns the id of the spans used in ckeditr.
							$('#'+event.editor.id+'_top').hide()
						}
					}
				}
			   $('.fancybox-button').fancybox(); 
               $('.dataTable').each(function(index){
                   var id_table = $(this).attr('id');
                   var name_table = $(this).attr('name');
                   var category = $(this).attr('category');
				   var value_filter = $('#value_filter').val();
                    //if ($(this).attr('name')=='options') {var col_sort= 2; }else {var col_sort=0};
					
                   var grid = new Datatable();
				   if((name_table=='data_report') || (name_table=='query')){
                        grid.init({
                            src: ('#'+id_table),
                            dataTable: {
                                "dom": "<'row'<'col-md-8 col-sm-12'><'col-md-4 col-sm-12'>r>t<'col-md-12 footer'<'col-md-6 col-sm-12 toolbar'><'col-md-6 col-sm-12 footer_page'pli>>",
                                "bStateSave": true,
                                "retrieve": true,
                                "lengthMenu": [
                                    [5, 10, 15, 30, 40, 50, -1],
                                    [5, 10, 15, 30, 40, 50, "All"]
                                ],
                                "pageLength": 10,
                                "ajax": {
                                    "url": $base_url + "ajax/loadtable/?name_table=" +name_table+"&category="+category+'&'+value_filter,
                                    "type": "POST"
                                },
                                "columnDefs": [{ // define columns sorting options(by default all columns are sortable extept the first checkbox column)
    								'orderable': false,
    								'targets': [0]
    							}],
                                "order": [],
                                "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                                    $('#'+$(this).attr('id')+" thead .heading th").each(function(i){
                                           var th = $(this),
                                            classes = th.attr("data-hide"); //  essential, optional (or other content identifiers)
                                           if (classes) {
                                              $('td:eq('+i+')', nRow).addClass(classes);
                                           };
                                    });
                                    $('[data-toggle="tooltip"]', nRow).tooltip({html:true}); 
                                },
                               //  "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
//                                    //  console.log( nTd );
//                                    $("a", nTd).tooltip({html:true});
//                                }  
                            }   
                        });
				   }
				   else {
					   grid.init({
                            src: ('#'+id_table),
                            dataTable: {
                                "dom": "<'row'<'col-md-8 col-sm-12'><'col-md-4 col-sm-12'>r>t<'col-md-12 footer'<'col-md-6 col-sm-12 toolbar'><'col-md-6 col-sm-12 footer_page'pli>>",
                                "bStateSave": true,
                                "retrieve": true,
                                "lengthMenu": [
                                    [5, 10, 15, 30, 40, 50, -1],
                                    [5, 10, 15, 30, 40, 50, "All"]
                                ],
                                "pageLength": 10,
                                "ajax": {
                                    "url": $base_url + "ajax/loadtable/?name_table=" +name_table+"&category="+category+'&'+value_filter,
                                    "type": "POST"
                                },
                                "columnDefs": [{ // define columns sorting options(by default all columns are sortable extept the first checkbox column)
    								'orderable': true,
    								'targets': [0]
    							},{ // define columns sorting options(by default all columns are sortable extept the first checkbox column)
    								'orderable': true,
    								'targets': [0]
    							}],
                                "order": [],
                                "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                                    $('#'+$(this).attr('id')+" thead .heading th").each(function(i){
                                           var th = $(this),
                                            classes = th.attr("data-hide"); //  essential, optional (or other content identifiers)
                                           if (classes) {
                                              $('td:eq('+i+')', nRow).addClass(classes);
                                           };
                                    });
                                    $('[data-toggle="tooltip"]', nRow).tooltip({html:true}); 
                                },
                               //  "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
//                                    //  console.log( nTd );
//                                    $("a", nTd).tooltip({html:true});
//                                }  
                            }   
                        });
				   }
                        //
                   });
                  
               });
			   
                //update();
				$("div.toolbar").html( '<div style="margin-top:3px; font-size:13px !important;">'+$('#text_note').val()+"</div>");
            },
            render: function(){
                if(typeof this[$app.action] != 'undefined'){
                    new this[$app.action];
                }
            }
        });
    return new tableView;
});