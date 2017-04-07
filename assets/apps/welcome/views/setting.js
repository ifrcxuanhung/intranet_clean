// Filename: views/welcome
define([
    'jquery',
    'underscore',
    'backbone'
    ], function($, _, Backbone){
        var settingView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){
            },
            events: {
				 "click a.view-tab-modal-edit": "actionViewModalEdit", 
				 "click .deleteField": "actionDeleteField"
            },
			actionDeleteField: function(event) {
                event.preventDefault();
				if (confirm(trans("request_delete")) == false) {
					return;
				}
				var $this = $(event.currentTarget);
                $.ajax({
					url: $base_url + 'tab/delete_row',
					type: 'POST',
					data: {table_name: $this.attr("table_name"), keys: $this.attr('keys')},
					async: false,
					success: function(response) {
					   if((response == true) || response) {
                            $("div.alert-success").text('Deleted!');
                            $("div.alert-success").fadeIn().delay(1500).fadeOut();
                            
                        } else {
                            $("div.alert-success").addClass('alert-warning');
                            $("div.alert-success").removeClass('alert-success');
                            $("div.alert-warning").text('Delete Warning !');
                            $("div.alert-warning").fadeIn().delay(1500).fadeOut();
                        }
                        var table = $('#'+$this.attr("table_name")).DataTable();
                        table.draw( false );						
					}
				});
            },
            actionViewModalEdit: function(event) {
                var $this = $(event.currentTarget);
				var validate = $this.attr("keys");
                $.ajax({
                    url: $base_url + "tab/tab_modal",
                    type: "POST",
                  //  dataType: "JSON",
                    data: {table_name: $this.attr("table_name"), keys: $this.attr("keys")},
                    async: false,
                    success: function(response) {
                        $("#tab_modal .modal-content").html(response);
                        $('.summernote').summernote();						
                          //var desc = $this.attr("description");
                         if (jQuery().datepicker) {
                              //  console.log('111111111111111111111111111');
                                $('.date-picker').datepicker({
                                    orientation: "left",
                                    autoclose: true
                                 //   minDate: '+1d'
                                });
                                $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
                        }
                        $("#checkupdate_modal").validate({
                             rules: {
                                'validate[]': {
                                    required: true
                                }
                            },
                            messages: {
                                'validate[]': {
                                    required: trans("request_name")
                                }
                            },
                            submitHandler: function(form) {
                                $.ajax({
                                    url: $base_url + 'tab/update_tab_modal',
                                    type: 'POST',
                                    dataType: 'JSON',
                                    data: $('form#checkupdate_modal').serialize(),
                                    async: false,
                                    success: function(response) {
                                        if(response == true) {
											 $("div.alert-success").text('Updated!');
                                            $("div.alert-success").fadeIn();
											var table = $('#'+$this.attr("table_name")).DataTable();
                                            table.draw( false );
											$('#tab_modal').modal('hide');
                                        } else {
                                            $("div.alert-danger").fadeIn();
                                        }
                                        $("div.alert").delay(1500).fadeOut();
                                    }
                                });
                            }
                        });  
                          
                    }
                });
            },
            index: function(){
               $(document).ready(function(){
				// GetClock();
				//setInterval(GetClock,1000);  
               $('.dataTable').each(function(index){
                //if ($(this).attr('name')=='options') {var col_sort= 2; }else {var col_sort=0};
               var grid = new Datatable();
                    grid.init({
                        src: ('#'+$(this).attr('id')),
                        dataTable: {
                            "dom": "<'row'<'col-md-8 col-sm-12'><'col-md-4 col-sm-12'>r>t<'col-md-12 footer'<'col-md-6 col-sm-12 toolbar'><'col-md-6 col-sm-12 footer_page'pli>>",
                            "bStateSave": true,
                            "retrieve": true,
                            "lengthMenu": [
                                [5, 10, 20, 40],
                                [5, 10, 20, 40]
                            ],
                            "pageLength": 10,
                            "ajax": {
                                "url": $base_url + "tab/list_tabs/?tab=" +$(this).attr('name'),
                                "type": "POST"
                            },
                            "columnDefs": [{ // define columns sorting options(by default all columns are sortable extept the first checkbox column)
								'orderable': true,
								'targets': [0]
							}],
                            "order": [
							] 
                        }
                    });
                });
               });
                $('[data-toggle="tooltip"]').tooltip({ html: true });
                //update();
            },
            render: function(){
                if(typeof this[$app.action] != 'undefined'){
                    new this[$app.action];
                }
            }
        });
        return settingView = new settingView;
});
