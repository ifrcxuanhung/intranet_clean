define([
    'jquery',
    'underscore',
    'backbone'
    ], function($, _, Backbone){
        var helpView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function() {

            },
            events: {
                "click a.show-modal": "actionShowModal",
                "click .save-modal": "actionSaveModal"
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
									var category_pa = $("[aria-expanded='true']").attr("href");
									var category_child = $("#id").val();
									//console.log(category_child);
									//window.location.reload($base_url+"help/"+category_pa+category_child);
									
									window.location.replace($base_url+"help/"+category_pa+category_child);
									window.location.reload();
									window.location.replace($base_url+"help/"+category_pa+category_child);
									
									
									
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
               
               $(document).ready(function(){
				
               });
            },
            render: function(){
                if(typeof this[$app.action] != 'undefined'){
                    new this[$app.action];
                }
            }
        });
    return new helpView;
});