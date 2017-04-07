$(document).ready(function() {
	$(".sub-apso2").click(function(){
		$('.filter-submit').click();	
	});

	$(".fileinput-exists").click(function(event){
		    var attr = $("#img").attr('src');
                $.ajax({
					url: $base_url + 'ajax/deleteImage',
					type: 'POST',
					data: {attr: attr},
					async: false,
					success: function(response) {
						//console.log(response);
					   if((response == true) || response) {
                          bootbox.alert("Delete image success!");  
						  //location.reload();                        
                        } else {
                            bootbox.alert("Delete fail!");   
                        }
         					
					}
			});
                
		
	});	
	$(".delete_image").click(function(event){
		    var attr = $("#img").attr('src');
                $.ajax({
					url: $base_url + 'ajax/deleteImage_intranet',
					type: 'POST',
					data: {attr: attr},
					async: false,
					success: function(response) {
						//console.log(response);
					   if((response == true) || response) {
                          bootbox.alert("Delete image success!");  
						  //location.reload();                        
                        } else {
                            bootbox.alert("Delete fail!");   
                        }
         					
					}
			});
                
		
	});	
	
	$(".delete_file").click(function(event){
		    var attr = $("#fil").attr('src');
                $.ajax({
					url: $base_url + 'ajax/deleteFile',
					type: 'POST',
					data: {attr: attr},
					async: false,
					success: function(response) {
						//console.log(response);
					   if((response == true) || response) {
						   $(".name_file").remove();
                          bootbox.alert("Delete file success!");  
						  //location.reload();                        
                        } else {
                            bootbox.alert("Delete fail!");   
                        }
         					
					}
			});
                
		
	});
	
	$(".delete_file_intranet").click(function(event){
		    var attr = $("#fil").attr('src');
                $.ajax({
					url: $base_url + 'ajax/deleteFile_intranet',
					type: 'POST',
					data: {attr: attr},
					async: false,
					success: function(response) {
						//console.log(response);
					   if((response == true) || response) {
						  $(".name_file").remove();
                          bootbox.alert("Delete file success!");  
						  //location.reload();                        
                        } else {
                            bootbox.alert("Delete fail!");   
                        }
         					
					}
			});
                
		
	});	
  
	$(".add-articles-order").click(function(){
		 
		 var clean_artid = $("#clean_artid").val();
		 var lang_code = $("#lang_code").val();
		 var clean_cat = $("#clean_cat").val();
		 var clean_scat = $("#clean_scat").val();
		
	     var title = $("#title").val();	
		 var website = $("#website").val();
		 var clean_order = $("#clean_order").val();
		 var status = $("#status").val();
		 var html_description = $("#html_description").val();
		 var html_long_description = $("#html_long_description").val();
		 var date_creation_start = $("#date_creation_start").val();
		 var date_creation_end = $("#date_creation_end").val();
		 var dataArr = [clean_artid,lang_code,clean_cat,clean_scat,title,website,clean_order,status,html_description,html_long_description,date_creation_start,date_creation_end];
		  $.ajax({
            url: $base_url + 'ajax/add_modal_vnxindex',
            type: 'POST',
            async: false,
            data: {dataArr: dataArr},
            success: function(respon){ 
                if(respon=='true'){
                    bootbox.alert("Update success !");
                }
            }
        });
		  //alert(dataArr);
	});
	$(".add-new-item").click(function(event){
		  event.preventDefault();
		  var $this = $(event.currentTarget);
		  $('#website').append($('<option/>', { 
			value: $("#website_add_new").val(),
			text : $("#website_add_new").val() 
		   }));
		   //var selectList = $('.select2_'+$id+' > option');
		  var selectList = $('#website'+' > option');
		 
		
		  selectList.sort(function(a,b){
		   a = a.value;
		   b = b.value;
		   return a == b ? 0 : (a < b ? -1 : 1);    
		  });
		  
		  $('#website').html(selectList);
		  $('#website').val($('#website_add_new').val());
		  $("#website_add_new").val('');
	 });
	 
	 $(".add-new-web").click(function(event){
		  event.preventDefault();
		  var list_web = $(".checked").find("[name='category_web']").map(function () {
				  return this.value;
			}).get();
		  var list = list_web.toString();
		  /*$('input:checkbox:checked.category_web').map(function () {
				  return this.value;
			}).get();*/
		  var $this = $(event.currentTarget);
		  $('#website').append($('<option/>', { 
			value: list,
			text : list 
		   }));
		   //var selectList = $('.select2_'+$id+' > option');
		  var selectList = $('#website'+' > option');
		
		  selectList.sort(function(a,b){
		   a = a.value;
		   b = b.value;
		   return a == b ? 0 : (a < b ? -1 : 1);    
		  });
		  
		  $('#website').html(selectList);
		  $('#website').val(list);
		  $('.modal').modal('hide');
	 });
	 
	   $(".addrows").one("click",function(){
		 var data = $(".addweb").val();
		 if(data == ''){
			bootbox.alert("Data empty !");	
			return false; 
		}
		 $.ajax({
            url: $base_url + 'ajax/update_int_article_websites',
            type: 'POST',
            async: false,
            data: {data: data},
            success: function(respon){
					int_article_website = jQuery.parseJSON(respon);
					var html_str = '';
					$.each(int_article_website, function( key, value ){
							html_str += '<li><label><input type="checkbox" name="category_web" value="'+ value.name +'">'+value.name+'</label></li>';
					});
					$("#unstyled").html(html_str);
					location.reload();
				
                
            }
        });
		 
	});
	
	
	
	$(".update-new-web").click(function(event){
		  event.preventDefault();
		  var list_web = $(".checked").find("[name='category_web']").map(function () {
				  return this.value;
			}).get();
		  var list = list_web.toString();
		  /*$('input:checkbox:checked.category_web').map(function () {
				  return this.value;
			}).get();*/
		  var $this = $(event.currentTarget);
		  $('#website').append($('<option/>', { 
			value: list,
			text : list 
		   }));
		   //var selectList = $('.select2_'+$id+' > option');
		  var selectList = $('#website'+' > option');
		
		  selectList.sort(function(a,b){
		   a = a.value;
		   b = b.value;
		   return a == b ? 0 : (a < b ? -1 : 1);    
		  });
		  
		  $('#website').html(selectList);
		  $('#website').val(list);
		  $('.modal').modal('hide');
	 });
	 
	   $(".updaterows").one("click",function(){
		 var data = $(".addweb").val();
		 if(data == ''){
			bootbox.alert("Data empty !");	
			return false; 
		}
		 $.ajax({
            url: $base_url + 'ajax/update_int_article_websites',
            type: 'POST',
            async: false,
            data: {data: data},
            success: function(respon){
					int_article_website = jQuery.parseJSON(respon);
					var html_str = '';
					$.each(int_article_website, function( key, value ){
							html_str += '<li><label><input type="checkbox" name="category_web" value="'+ value.name +'">'+value.name+'</label></li>';
					});
					$("#unstyled").html(html_str);
					location.reload();
				
                
            }
        });
		 
	});
	
	 
	 $(".add-new-cat").click(function(event){
		  event.preventDefault();
		  var $this = $(event.currentTarget);
		  $('#clean_cat').append($('<option/>', { 
			value: $("#cat_add_new").val(),
			text : $("#cat_add_new").val() 
		   }));
		   //var selectList = $('.select2_'+$id+' > option');
		  var selectList = $('#clean_cat'+' > option');
		 
		
		  selectList.sort(function(a,b){
		   a = a.value;
		   b = b.value;
		   return a == b ? 0 : (a < b ? -1 : 1);    
		  });
		  
		  $('#clean_cat').html(selectList);
		  $('#clean_cat').val($('#cat_add_new').val());
		  $("#cat_add_new").val('');
	 });
	 
	  $(".add-new-scat").click(function(event){
		  event.preventDefault();
		  var $this = $(event.currentTarget);
		  $('#clean_scat').append($('<option/>', { 
			value: $("#scat_add_new").val(),
			text : $("#scat_add_new").val() 
		   }));
		   //var selectList = $('.select2_'+$id+' > option');
		  var selectList = $('#clean_scat'+' > option');
		 
		
		  selectList.sort(function(a,b){
		   a = a.value;
		   b = b.value;
		   return a == b ? 0 : (a < b ? -1 : 1);    
		  });
		  
		  $('#clean_scat').html(selectList);
		  $('#clean_scat').val($('#scat_add_new').val());
		  $("#scat_add_new").val('');
	 });
	
	
	
    $("textarea").keyup(function(e) {
        if(e.keyCode == 13) //13 is the ASCII code for ENTER
        {
            var rowCount = $(this).attr("rows");
            $(this).attr({rows: rowCount + 5});
        }
    });
	 $('.thumb').live("mouseenter", function() {
        var html='<img class="show-thumb-hover" src="'+$(this).attr('src')+'" height="'+ $(this).attr("data-height")+'" />';
        $(this).parent().append(html);
        $('.show-thumb-hover').fadeIn('slow');
        $(this).parent().addClass('relative');
    }).live("mouseleave", function() {
        $('.show-thumb-hover').fadeOut('slow').remove();
        $(this).parent().removeClass('relative');
    });
	
    if (!jQuery().clockface) {
        return;
    }
    $('.clockface_1').clockface();
    $('#update_attendate').click(function(){
        var time_from = $('.clockface_1[name="from"]').val();
		var time_to = $('.clockface_1[name="to"]').val();
       // console.log(time_from+' - '+time_to);
        $.ajax({
            url: $base_url + 'ajax/update_time_attendate',
            type: 'POST',
            async: false,
            data: {time_from: time_from, time_to: time_to},
            success: function(respon){ 
                if(respon=='true'){
                    bootbox.alert("Update success !");
                }
            }
        });
    });
    if ($(window).width() < 1024) {
           $('.page-sidebar-menu').addClass('page-sidebar-menu-hover-submenu');
           $('.page-sidebar-menu').addClass('page-sidebar-menu-closed'); 
     }
    $('[data-toggle="tooltip"]').tooltip({html:true});
    if (!jQuery.fancybox) {
        return;
    }

    
//  if($login=='1'){
//        setInterval(function() {
//            checkUser();
//        }, 20000);
//    }
     // picker show input calendar don't remove
     if (jQuery().datepicker) {
        $('.date-picker').datepicker({
            rtl: Metronic.isRTL(),
            orientation: "left",
            autoclose: true
        });
        $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
     }
	 $("a.mmenu").click(function() {
        
        var id_menu = $(this).attr('id_menu');
        var parent = $(this).attr('parent');
		var category = $(this).attr('id');
        $(this).closest('ul').find('li.active').removeAttr('class');
        $(this).closest('li').addClass('active');
        $.ajax({
            url: $base_url + 'ajax/create_session_menu',
            type: 'POST',
            async: false,
            data: {id_menu: id_menu}
        });
     });
     $("a.updatetask").click(function(event){
        var $this = $(event.currentTarget);
        var action = $this.data('status');
        var id_task = $this.attr('id_task');
        $.ajax({
            url: $base_url + 'ajax/setStatusTask',
            type: 'POST',
            async: false,
            data: {action: action, id_task: id_task},
            success: function(respon){ 
                if(respon=='true'){
                    bootbox.alert("Update your tasks success !");
                }
            }
        });
     });
     $("a.table_menu").click(function() {
        var id_menu = $(this).attr('id_menu');
        var parent = $(this).attr('parent');
		var category = $(this).attr('id'); 
		var table = $(this).attr('table');
		if(table=='staff') table ='user_info';
		var link_menu = $(this).attr('link');
        $(this).closest('ul').find('li.active').removeAttr('class');
        $(this).closest('li').addClass('active');
        $.ajax({
            url: $base_url + 'ajax/create_session_menu',
            type: 'POST',
            async: false,
            data: {id_menu: id_menu}
        });
		$('#category').val(category);
		$href = $(location).attr('href');	
		if($href.lastIndexOf("portfolio") > 0){
			$(location).attr('href',link_menu);
		}
        
		else if($href.lastIndexOf(table) > 0 || $href.lastIndexOf("user_info") > 0){
			$('.dataTable').each(function(index){
				var id_table = $(this).attr('id');
				//var name_table = $(this).attr('name');
				 $("#"+id_table).dataTable().fnDestroy();
				var gridsub = new Datatable();
				gridsub.init({
					src: ('#'+id_table),
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
							"url": $base_url + "ajax/loadtable/?name_table=" +table+"&category="+category,
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
		}
		else {
    		  if(id_menu==101){
    			 bootbox.confirm({
                    message: "Are you sure clean data ?",
                    callback: function(result){ /* your callback code */ 
                        if(result == true){
                            //$(".modal-content").hide();
                             $.ajax({
    							url: $base_url + "cleanarticle/clean_daily",	
    						});
                        }
                    }
                }); 
            }
            else if(id_menu==102){
    			 bootbox.confirm({
                    message: "Are you sure clean data ?",
                    callback: function(result){ /* your callback code */ 
                        if(result == true){
                           // $(".modal-content").hide();
                             $.ajax({
    							url: $base_url + "cleanarticle/clean_monthly",	
    						});
                        }
                    }
                }); 
            }
			else $(location).attr('href',link_menu);
		}
    });
   
    $('#login_modal').click(function(){
      //  var $this = $(event.currentTarget);
        $.ajax({
            url: $base_url + "login/login_modal",
            type: "POST",
            async: false,
            success: function(response) {
                $("#login_modals .modal-content").html(response);
                $('body').addClass("login"); // fix bug when inline picker is used in modal    
            }
        });
        $('#LoginProcess').click(function(){
            var datastring = 'identity='+$("#identity").val()+'&password='+$("#password").val() +'&remember='+$('#remember').val();//  +'&redirect_url='+$("#redirect_url").val();
            $.ajax({
                type: "POST",
                url: $base_url + "auth/login",
                dataType: "json",
                data: datastring,
                success: function(output) {
                  var obj = output;
                    if(obj.status){               
                      $('#login_modals').modal('hide');
					   $href = $(location).attr('href').replace(/^.*?(#|$)/,'');
					  // $(this).attr('href').replace(/^.*?(#|$)/,'');
					   $(location).attr('href',$href);
                    }else{
                      $('span.alert_msg').html(obj.message);
					  $("#login_alert").removeAttr('style');
                    }
    
                }
            });
        });    
    });
    $('#logout_modal').click(function(){          
		$.ajax({
			type: "POST",
			url: $base_url + "ajax/logout",
			dataType: "json",
			success: function(output) {
			  $href = $(location).attr('href').replace(/^.*?(#|$)/,'');
			  $href = $(location).attr('href').replace('/profile','');
			  $(location).attr('href',$href);     
			}
		});
	}); 
    $('.switch-language').click(function() {
        var langcode = $(this).attr('langcode');
        $.ajax({
            url: $base_url + 'ajax/change_language',
            type: 'POST',
            async: false,
            data: {langcode: langcode},
            success: function() {
                window.location.reload();
                return false;
            }
        });
    }); 
    $('.demo-loading-btn').click(function () {
      //  alert('1111111111111');
        var btn = $(this)
        btn.button('loading')
        setTimeout(function () {
        btn.button('reset')
        }, 3000)
    });
    $('.button-sendcontact').click(function() {
        var contact_name = $('input[name=contact_name]').val();
        var contact_email = $('input[name=contact_email]').val();
        var contact_message = $('textarea[name=contact_message]').val();
        var email_receiving_email = $('input[name=email_receiving_email]').val();
       	var validate_code = $('input[name=contact_code]').val();
        if (contact_name == "" || contact_email == "" || contact_message == "")
        {
            $('p.contact_warning').html('<span style="color: #FF0000; display: block; margin-top: 10px;">Please enter the full input</span>');
            setTimeout(function() {
                $('p.contact_warning').html('')
            }, 3000);
        }
        else
        {
            if (checkmail(contact_email) == false)
            {
                $('p.contact_warning').html('<span style="color: #FF0000; display: block; margin-top: 10px;">Email incorrect formats</span>');
                setTimeout(function() {
                    $('p.contact_warning').html('')
                }, 3000);
            }
            else if (checkValidateCode(validate_code))
            {
                $('.contact_warning').html('<div class="alert alert-danger display-hide" style="display: block;"><button data-close="alert" class="close"></button>Please enter the valid number.</div>');
            }
            else
            {
                $('p.contact_warning').html('<span style="color: green; display: block; margin-top: 10px;">Sending email...</span>')
                $.ajax({
                    url: $base_url + 'contact/sendcontact',
                    type: 'post',
                    async: false,
                    data: {contact_name: contact_name, contact_email: contact_email, contact_message: contact_message, email_receiving_email: email_receiving_email},
                    success: function(data) {
                        if (data == 1)
                        {
                            $('p.contact_warning').html('<span style="color: green; display: block; margin-top: 10px;">Send email success</span>');
                            $('input[name=contact_name]').val('');
                            $('input[name=contact_email]').val('');
                            $('textarea[name=contact_message]').val('');
                        }
                        else
                        {
                            $('p.contact_warning').html('<span style="color: red; display: block; margin-top: 10px;">Send email not success</span>')
                        }
                    }
                });
            }
        }
    });
	$('#feedback_popup').click(function(){
		$('.feedback_warning').html('');
		$('input[name=feedback_name]').val('');
		$('input[name=feedback_email]').val('');
		$('textarea[name=feedback_message]').val('');
		$('input[name=feedback_code]').val('');
	});
	$('.button-sendfeedback').click(function() {
        var contact_name = $('input[name=feedback_name]').val();
        var contact_email = $('input[name=feedback_email]').val();
        var contact_message = $('textarea[name=feedback_message]').val();
        var email_receiving_email = $('input[name=feedback_receiving_email]').val();
		var validate_code = $('input[name=feedback_code]').val();
        if (contact_name == "" || contact_email == "" || contact_message == "")
        {
            $('.feedback_warning').html('<div class="alert alert-danger display-hide" style="display: block;"><button data-close="alert" class="close"></button>Please enter the full input</div>');
        }
        else
        {
            if (checkmail(contact_email) == false)
            {
                $('.feedback_warning').html('<div class="alert alert-danger display-hide" style="display: block;"><button data-close="alert" class="close"></button>Email incorrect formats</div>');
            }
			else if (checkValidateCode(validate_code))
            {
                $('.feedback_warning').html('<div class="alert alert-danger display-hide" style="display: block;"><button data-close="alert" class="close"></button>Please enter the valid number.</div>');
            }
            else
            {
                $('.feedback_warning').html('<div class="alert alert-danger display-hide" style="display: block;"><button data-close="alert" class="close"></button>Sending email...</div>')
                $.ajax({
                    url: $base_url + 'tab/sendfeedback',
                    type: 'post',
                    async: false,
                    data: {contact_name: contact_name, contact_email: contact_email, contact_message: contact_message, email_receiving_email: email_receiving_email, url_send:window.location.href},
                    success: function(data) {
                        if (data == 1)
                        {
                            $('.feedback_warning').html('<div class="alert alert-danger display-hide" style="display: block;"><button data-close="alert" class="close"></button>Send email success</div>');
                            $('input[name=feedback_name]').val('');
                            $('input[name=feedback_email]').val('');
                            $('textarea[name=feedback_message]').val('');
                        }
                        else
                        {
							 $('.feedback_warning').html('<div class="alert alert-danger display-hide" style="display: block;"><button data-close="alert" class="close"></button>Send email not success</div>');
                        }
                    }
                });
            }
        }
    });
    //wysihtml5();
    // $("#timer").flipcountdown({
    //   size:"xs"
    // }); 
    createTicker();
	
});

//function wysihtml5(){
//    if (!jQuery().wysihtml5) {
//        return;
//    }
//    if ($('.wysihtml5').size() > 0) {
//        $('.wysihtml5').wysihtml5({
//            "stylesheets": [$template_url+"global/plugins/bootstrap-wysihtml5/wysiwyg-color.css"]
//        });
//    }
//};

function createTicker() {
    var tickerLIs = $(".breaking-news ul").children();
    tickerItems = new Array();
    tickerLIs.each(function(el) {
        tickerItems.push($(this).html());
    });
    i = 0;
    rotateTicker();
};

function rotateTicker() {
    if (i == tickerItems.length) {
        i = 0;
    }
    tickerText = tickerItems[i];
    c = 0;
    typetext();
    setTimeout("rotateTicker()", 5000);
    i++;
};

function typetext() {
    if (typeof tickerText !== "undefined") {
        var thisChar = tickerText.substr(c, 1);
        if (thisChar == '<') {
            isInTag = true;
        }
        if (thisChar == '>') {
            isInTag = false;
        }
        jQuery('.breaking-news ul').html(tickerText.substr(0, c++));
        if (c < tickerText.length + 1)
            if (isInTag) {
                typetext();
            } else {
                setTimeout("typetext()", 28);
            }
        else {
            c = 1;
            tickerText = "";
        }
    }
};
//function trans(word) {
//    var translate;
//	//translate =  $objec_translation[word];
//	if($objec_translation[word]||$objec_translation[word]===0){
//		translate = $objec_translation[word];
//	}
//	else {
//		translate = word;
//	}
//				
//    return translate;
//};
function checkmail(email) {
    var emailfilter = /^\w+[\+\.\w-]*@([\w-]+\.)*\w+[\w-]*\.([a-z]{2,4}|\d+)$/i;
    var returnval = emailfilter.test(email)
    return returnval;
};
function checkValidateCode(code) {
	var returnval = true;
     $.ajax({
		url: $base_url + 'journals/checkCode',
		type: 'post',
		async: false,
		data: {validate_code: code},
		success: function(data) {
			if (data == 1) returnval = false; 
			else returnval = true; 
		}
	});
	return returnval;
};

function datatable() {
    var oTable = $("#sample_3").dataTable({
        "aoColumnDefs": [
            {"aTargets": [0]}
        ],
        "aaSorting": [[1, 'asc']],
        "aLengthMenu": [
            [5, 15, 20, -1],
            [5, 15, 20, "All"] // change per page values here
        ],
        // set the initial value
        "iDisplayLength": 50,
        "bLengthChange": false
    });

    jQuery('#sample_3_wrapper .dataTables_filter input').addClass("m-wrap small"); // modify table search input
    jQuery('#sample_3_wrapper .dataTables_length select').addClass("m-wrap small"); // modify table per page dropdown
    jQuery('#sample_3_wrapper .dataTables_length select').select2(); // initialzie select2 dropdown

    $('#sample_3_column_toggler input[type="checkbox"]').change(function() {
        /* Get the DataTables object again - this is not a recreation, just a get of the object */
        var iCol = parseInt($(this).attr("data-column"));
        var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
        oTable.fnSetColumnVis(iCol, (bVis ? false : true));
    });
};

/* function that process and display data */
function checkUser() {
    $.ajax({
        url: $base_url + "ajax/checkupdate",
        async: false,
        success: function(response) {
//            $("#timer").html(response);
            obj = jQuery.parseJSON(response);
            $('#userList').html(obj);
        }
    });
};

function updateservice() {
    $.ajax({
        type: "POST",
        url: $base_url + "ajax/getPrice_realtime",
        dataType: "JSON",
        success: function(response) {
            var rs = response;
            //console.log(rs);
            $("#last_update").html("(" + rs[0].date + " | " + rs[0].time + ")");
            var value = parseFloat($("#idx_last").val());
            $("#idx_last").val(rs[0].idx_last);
            var new_value = parseFloat(rs[0].idx_last);
            if (new_value != value) {
                $("#sample_3 tr#6 td.strike").addClass("yellow");
                if (new_value - value > 0) {
                    $("#sample_3 tr#6 td.strike").removeClass("color-red").addClass("color-green");
                } else {
                    $("#sample_3 tr#6 td.strike").removeClass("color-green").addClass("color-red");
                }
                var strike = Math.round(new_value / 25) * 25;
                $("#sample_3 tr#5 td.strike").text(strike - (25 * 1));
                $("#sample_3 tr#4 td.strike").text(strike - (25 * 2));
                $("#sample_3 tr#3 td.strike").text(strike - (25 * 3));
                $("#sample_3 tr#2 td.strike").text(strike - (25 * 4));
                $("#sample_3 tr#1 td.strike").text(strike - (25 * 5));
                $("#sample_3 tr#6 td.strike").text(strike);
                $("#sample_3 tr#7 td.strike").text(strike + (25 * 1));
                $("#sample_3 tr#8 td.strike").text(strike + (25 * 2));
                $("#sample_3 tr#9 td.strike").text(strike + (25 * 3));
                $("#sample_3 tr#10 td.strike").text(strike + (25 * 4));
                $("#sample_3 tr#11 td.strike").text(strike + (25 * 5));
            }
            setTimeout(function() {
                $("#sample_3 tr#6 td.strike").removeClass("yellow");
            }, 800);
        }
    });
    t = setTimeout(function() {
        updateservice();
    }, 15000);
};

var serverdate = new Date();
var ore = serverdate.getHours();
var minute = serverdate.getMinutes();
var secunde = serverdate.getSeconds();

/* function that process and display data */
function timer() {
    $.ajax({
        url: $base_url + "welcome/timer",
        async: false,
        success: function(response) {
            $("#timer").html(response);
        }
    });
    t = setTimeout(function() {
        timer();
    }, 1000);
};
function thumb() {	

	xOffset = 10;
	yOffset = 30;
	$("img.thumb").hover(function(e){
		$("body").append("<p id='thumb'><img src='"+ $(this).attr("src") +"' height='"+ $(this).attr("data-height") + "' /></p>");								 
		$("#thumb")
			.css("top",(e.pageY - xOffset) + "px")
			.css("left",(e.pageX + yOffset) + "px")
			.fadeIn("fast");					
    }, function() {
		$("#thumb").remove();
    });

	$("img.thumb").mousemove(function(e){
		$("#thumb")
			.css("top",(e.pageY - xOffset) + "px")
			.css("left",(e.pageX + yOffset) + "px");
	});			
};

function GetClock(){
	var d=new Date();
	var nday=d.getDay(),nmonth=d.getMonth(),ndate=d.getDate(),nyear=d.getYear(),nhour=d.getHours(),nmin=d.getMinutes(),nsec=d.getSeconds(),ap;
	nmonth = nmonth + 1;
	nmonth = nmonth + "";
	if (nmonth.length == 1)
    {
        nmonth = "0" + nmonth;
    }
	ndate = ndate + "";
	if (ndate.length == 1)
    {
        ndate = "0" + ndate;
    }
	
	/*if(nhour==0){ap=" AM";nhour=12;}
	else if(nhour<12){ap=" AM";}
	else if(nhour==12){ap=" PM";}
	else if(nhour>12){ap=" PM";nhour-=12;}*/
	
	if(nyear<1000) nyear+=1900;
	if(nmin<=9) nmin="0"+nmin;
	if(nsec<=9) nsec="0"+nsec;
	
	document.getElementById('clockbox').innerHTML=""+nyear+"-"+nmonth+"-"+ndate+" "+nhour+":"+nmin+":"+nsec+"";
};
function repeatString(string, n) {
		var result = '', i;
	
		for (i = 1; i <= n; i *= 2) {
			if ((n & i) === i) {
				result += string;
			}
			string = string + string;
		}
	
		return result;
};
