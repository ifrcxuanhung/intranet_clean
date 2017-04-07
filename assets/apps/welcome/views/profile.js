define([
    'jquery',
    'underscore',
    'backbone'
    ], function($, _, Backbone){
        var profileView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function() {

            },
            events: {
               //"mouseenter #avatar": "actionUploadAvatar",
                "click a.clear-change": "actionClearChange",
                "click a.load-modal": "actionViewModal",
			    "click a.view-user": "actionViewUser",
				"click .delete-order": "actionDeleteField",
				"click .save-avatar" : "actionUploadAvatar",
				"click .remove-image-profile" : "actionRemoveImageProfile",
				
            },
			actionUploadAvatar: function(event){
				event.preventDefault();
				$("#fileupload").submit(function(e) {
                        var formData = new FormData(this);
                        $.ajax({
                            url: $base_url + 'profile/upload_avatar',
                            type: 'POST',
                            mimeType: "multipart/form-data",
                            data: formData,
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function(rs) {
                                rs = JSON.parse(rs);
                                if (rs.error != '') {
                                    alert(rs.error);
                                }
                                if (rs.success != '') {
									 bootbox.alert("upload success!"); 
                                    //$("#avatar").attr('src', rs.success + '?' + (new Date()).getTime());
                                    $(".img-hide1").attr('src', rs.success + '?' + (new Date()).getTime());
									location.reload();
                                }
                            }
                        });
                        e.preventDefault();
                    });
                    $("#fileupload").submit();
			},
			actionRemoveImageProfile: function() {
				var attr = $("#myavatar").attr('src');
               //alert(attr);
                $.ajax({
					url: $base_url + 'profile/deleteImage',
					type: 'POST',
					data: {attr: attr},
					async: false,
					success: function(response) {
						//console.log(response);
						//return false;
					   if((response == true) || response) {
                          bootbox.alert("Delete image success!");  
						  location.reload();                        
                        } else {
                            bootbox.alert("Delete fail!");   
                        }
         					
					}
				});
            },
			actionDeleteField: function(event) {
                event.preventDefault();
			
				if (confirm(trans("cancel_conform_order")) == false) {
					return;
				}
				var $this = $(event.currentTarget);
                $.ajax({
					url: $base_url + 'profile/delete_order',
					type: 'POST',
					data: {keys: $this.attr('keys')},
					async: false,
					success: function(response) {
					   if((response == true) || response) {
                            $("div.alert-success").text(trans('cancel_order_success'));
                            $("div.alert-success").fadeIn().delay(1500).fadeOut();                            
                        } else {
                            $("div.alert-success").addClass('alert-warning');
                            $("div.alert-success").removeClass('alert-success');
                            $("div.alert-warning").text(trans('cancel_order_warning'));
                            $("div.alert-warning").fadeIn().delay(1500).fadeOut();
                        }
                        var table = $('#table_orders').DataTable();
                        table.draw( false );						
					}
				});
            },
			actionViewUser: function(event) {
                var $this = $(event.currentTarget);
                $.ajax({
                    url: $base_url + "profile/view_user_home",
                    type: "POST",
                    dataType: "JSON",
                    data: {},
                    async: false,
                    success: function(response) {
                        var modal = $("#modal_view_user");
                        modal.find("input[name=view_first_name]").val(response.first_name);
                        modal.find("input[name=view_last_name]").val(response.last_name);
						modal.find("textarea[name=view_profile]").val(response.profile);
						modal.find("textarea[name=view_education]").val(response.education);
						modal.find("textarea[name=view_experiences]").val(response.experiences);
						modal.find("textarea[name=view_interests]").val(response.interests);  
                        $.validator.addMethod("noSpace", function(value, element) { 
                            return value.indexOf(" ") < 0 && value != ""; 
                        }, "No space please and don't leave it empty");
                        
                        $("#form_view_user").validate({
                            rules: {
                                first_name: {
                                    required: true
                                },
                                last_name: {
                                    required: true
                                },
                                profile: {
                                    required: true
                                },
                                education: {
                                    required: true
                                },
                                experiences: {
                                    required: true
                                },
                                interests: {
                                    required: true
                                } 
                            },
                            messages: {
                                first_name: {
                                    required: trans("name_not_null")
                                },
                                last_name: {
                                    required: trans("lastname_not_null")
                                },
                                profile: {
                                    required: trans("story_not_null")
                                },
                                education: {
                                    required: trans("level_not_null")
                                },
                                experiences: {
                                    required: trans("experience_not_null")
                                },
                                interests: {
                                    required: trans("hobby_not_null")
                                }
                            },
                            submitHandler: function(form) {
                                $.ajax({
                                    url: $base_url + 'profile/change_user_info',
                                    type: 'POST',
                                    dataType: 'JSON',
                                    data: {first_name: $("input[name=view_first_name]").val(), last_name: $("input[name=view_last_name]").val(),profile: $("textarea[name=view_profile]").val(),education: $("textarea[name=view_education]").val(),experiences: $("textarea[name=view_experiences]").val(), interests: $("textarea[name=view_interests]").val()},
                                    async: false,
                                    success: function(response){
                                        if(response == '1') {
                                            $("div.alert-success").fadeIn();
                                            location.reload();
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
            actionViewModal: function(event) {
                var $this = $(event.currentTarget);
                $.ajax({
                    url: $base_url + 'profile/view_modal',
                    type: 'POST',
                    async: false,
                    data: {modal_type: $this.attr("modal_type")},
                    success: function(response) {
                        $("#modal_change .modal-content").html(response);
                        $.validator.addMethod("noSpace", function(value, element) { 
                            return value.indexOf(" ") < 0 && value != ""; 
                        }, "No space please and don't leave it empty");

                        $("#change_password").validate({
                            rules: {
                                old_password: {
                                    required: true
                                },
                                new_password: {
                                    required: true,
                                    noSpace: true,
                                    minlength: 4
                                },
                                confirm_password: {
                                    required: true,
                                    equalTo: "#new_password"
                                }
                            },
                            messages: {
                                old_password: {
                                    required: trans("pass_null")
                                },
                                new_password: {
                                    required: trans("pass_new_error"),
                                    noSpace: trans("pass_no_space"),
                                    minlength: trans("pass_min4chart")
                                },
                                confirm_password: {
                                    required: trans("pass_new_null"),
                                    equalTo: trans("pass_repeat_error")
                                }
                            },
                            submitHandler: function(form) {
                                $.ajax({
                                    url: $base_url + 'profile/change_password',
                                    type: 'POST',
                                    dataType: 'JSON',
                                    data: {old_password: $("#old_password").val(), new_password: $("#new_password").val()},
                                    async: false,
                                    success: function(response) {
                                        $("#old_password").val('');
                                        $("#new_password").val('');
                                        $("#confirm_password").val('');
                                        if(response == '1') {
                                            $("div.alert-success").fadeIn();
                                            location.reload();
                                        } else {
                                            $("div.alert-danger").fadeIn();
                                        }
                                        $("div.alert").delay(1500).fadeOut();
                                    }
                                });
                            }
                        });

                        $("#change_email").validate({
                            rules: {
                                old_email: {
                                    required: true,
                                    email: true
                                },
                                new_email: {
                                    required: true,
                                    email: true
                                },
                                confirm_email: {
                                    equalTo: "#new_email"
                                }
                            },
                            messages: {
                                old_email: {
                                    required: trans("email_null"),
                                    email: trans("email_error")
                                },
                                new_email: {
                                    required: trans("email_null"),
                                    email: trans("email_error")
                                },
                                confirm_email: {
                                    equalTo: trans("repeat_email_error")
                                }
                            },
                            submitHandler: function(form) {
                                $.ajax({
                                    url: $base_url + 'profile/change_email',
                                    type: 'POST',
                                    dataType: 'JSON',
                                    data: {old_email: $("#old_email").val(), new_email: $("#new_email").val()},
                                    async: false,
                                    success: function(response) {
                                        $("#old_email").val('');
                                        $("#new_email").val('');
                                        $("#confirm_email").val('');
                                        if(response == '1') {
                                            $("div.alert-success").fadeIn();
                                            location.reload();
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
            actionClearChange: function(event) {
                var $this = $(event.currentTarget);
                $("#old_password").val('');
                $("#new_password").val('');
                $("#confirm_password").val('');
                $("#old_email").val('');
                $("#new_email").val('');
                $("#confirm_email").val('');
            },
          
            index: function(){
                $(document).ready(function(){});
            },
            render: function(){
                if(typeof this[$app.action] != 'undefined'){
                    new this[$app.action];
                }
            }
        });
    return new profileView;
});