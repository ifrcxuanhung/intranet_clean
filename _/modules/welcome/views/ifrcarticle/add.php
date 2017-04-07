<!--<script>
$(document).ready(function(){
	

	$(".submit_article").click(function(){
		var info = $("#info_ar").val();
		var title = $("#title_ar").val();
		var desc = $("#description_ar").val();
		var long_desc = $("#long_description").val();
		
		 $.ajax({
                      type : "POST",
                      url : $base_url + 'cleanarticle/update_article_desc_clean',
                      data : {info:info, title:title, desc:desc, long_desc:long_desc},
                      success: function(res) {
						 if(res == 1){
							 $("div.update-success").text("Update successful");
							 $("div.update-success").fadeIn().delay(500).fadeOut();
							// $(".hi-a").fadeOut().delay(500);
							 location.reload(); 
							
							
						}
                      },
        });
		
		
		
	});	
});


</script>-->



          
                    
                    
<style>
.sub-apso{ position:absolute; top:400px; right:10px; position:fixed; z-index:1000;}
.nav-tabs > li.active > a, .nav-tabs > li.active > a:focus, .nav-tabs > li.active > a:hover{
background-color:#337ab7; color:#FFF;	
}
.nav-tabs > li > a, .nav-pills > li > a{ color:#333333; background-color:#ddd;}
</style>                   
                    
                    
                    


<div class="portlet box blue ">
 <div class="alert-success update-success"></div>
    <div class="portlet-title"  style="height:60px;">
        <div class="caption" style="line-height:40px;">
            <i class="fa fa-gift" style="line-height:32px;"></i> Add Article
        </div>
        <div class="tools">
          <!--  <a class="collapse" href="" data-original-title="" title="">
            </a>
        
            <a class="remove" href="" data-original-title="" title="">
            </a>-->
        </div>
    </div><!--portlet-title-->
<div class="portlet-body form">
            
            <!-- BEGIN PAGE HEADER-->

<!-- END PAGE HEADER-->
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN VALIDATION STATES-->
		<div class="full-height-content full-height-content-scrollable">
			<div class="full-height-content-body form">
            <!-- BEGIN FORM-->
            <?php if(validation_errors()){?>
            <div class="portlet box red-sunglo">
            <div class="portlet-title">
                <div class="caption">
                  <?php echo validation_errors(); ?>
                </div>
					
			</div> <!--portlet-title-->
            </div>
          <?php }?>

				<form action="<?php base_url()?>cleanarticle/add_modal" id="form_article" class="form-horizontal" method="post"  enctype="multipart/form-data" >
                	
                	<div class="form-body">
                    <div class="row">
				
						<div class="alert alert-danger display-hide">
							<button class="close" data-close="alert"></button>
							You have some form errors. Please check below.
						</div>
						<div class="alert alert-success display-hide">
							<button class="close" data-close="alert"></button>
							Your form validation is successful!
						</div>
                        <div class="col-md-6">
                            
                             <!--   <div class="form-group">
                                            <label class="control-label col-md-3">Id article 
                                            </label>
                                            <div class="col-md-8">
                                          
                                                <input type="text" name="article_id" data-required="1" class="form-control" value="" />
                                            </div>
                                    </div>
                            -->
                                       <div class="form-group">
                                        <label class="control-label col-md-3">Category 
                                        </label>
                                        <div class="col-md-8">
                                      
                                            <input type="text" name="category_id" data-required="1" class="form-control" value="" />
                                        </div>
                                    </div>
                                    
                                    
                                    
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Status
                                        </label>
                                        <div class="col-md-8">
                                           
                                            <div class="radio-list" data-error-container="#form_2_membership_error">
                                                <label>
                                                <input type="radio" name="status" value="1" checked="checked"  />
                                                Show </label>
                                                <label>
                                                <input type="radio" name="status" value="0"/>
                                                Hide </label>
                                            </div>
                                            <div id="form_2_membership_error">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Sort orrder 
                                        </label>
                                        <div class="col-md-8">
                                      
                                            <input type="text" name="sort_order" data-required="1" class="form-control" value="" />
                                        </div>
                                    </div>
                                    
                                    <div class="form-group ">
                                            <label class="control-label col-md-3">image</label>
                                            <div class="col-md-8">
                                                <div class="fileinput" data-provides="fileinput" data-name="myimage">
                                                  
                                                        <div id="file" class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                            <img id="img" src="" alt=""/>
        									</div>
                  
                                                    <div>
                                                        <span class="btn default btn-file">
                                                        <span class="fileinput-new">
                                                        Select image </span>
                                                        <span class="fileinput-exists">
                                                        Change </span>
                                                        <input type="file" name="image" id ="image" value=""/>
                                                        </span>
                                                        <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput">
                                                        Remove </a>
                                                    </div>
                                                </div>
                                              
                                            </div>
                                    </div>
                                
                                    
                                    
                                 
                                    
                                    
                            
                                
                            
                         </div><!--col-md-6-->
                         <div class="col-md-6">
                         
                         
                         
                            <div class="form-group">
                                        <label class="control-label col-md-2">URL&nbsp;&nbsp;</label>
                                        <div class="col-md-8">
                                            <input name="url" type="text" class="form-control" value=""/>
                                        </div>
                                    </div>
                                    
                                    
                                      <div class="form-group">
                                        <label class="control-label col-md-2">Group 
                                        </label>
                                        <div class="col-md-8">
                                      
                                            <input type="text" name="group" data-required="1" class="form-control" value="" />
                                        </div>
                                    </div>
                                    
                                     <div class="form-group">
                                        <label class="control-label col-md-2">Website 
                                        </label>
                                        <div class="col-md-8">
                                           
                                            <select class="form-control select2me" name="website">
                                                <option value="">choose..</option>
                                                <?php 
												
													foreach ($list_website as $key => $val)
													{
										
                        				echo '<option value="'.$val['website'].'">'.(string) $val['website']."</option>\n";    
													}
													?>
                                                
                                            </select>
                                         
                                        </div>
                                    </div>
                                    
                                     <div class="form-group">
                                        <label class="control-label col-md-2">New Group 
                                        </label>
                                        <div class="col-md-8">
                                      
                                            <input type="text" name="group_news" data-required="1" class="form-control" value="" />
                                        </div>
                                    </div>
                                       <div class="form-group">
                                        <label class="control-label col-md-2">Filter 
                                        </label>
                                        <div class="col-md-8">
                                      
                                            <input type="text" name="filter" data-required="1" class="form-control" value="" />
                                        </div>
                                    </div>
                                    
                        
                         </div><!--col-md-6-->
                       
                  </div>      
             	</div><!--form-body-->
                  <div class="form-body"> 
                  
                     <div class="portlet-tabs">
                            <ul class="nav nav-tabs">
                            
                                <li class="active">
                                    <a data-toggle="tab" href="#portlet_tab1" aria-expanded="false" class="">
                                    English
                                    </a>
                                </li>
                                 <li class="">
                                    <a data-toggle="tab" href="#portlet_tab2" aria-expanded="false">
                                    France
                                    </a>
                                </li>
                                 <li class="">
                                    <a data-toggle="tab" href="#portlet_tab3" aria-expanded="false">
                                    Việt Nam
                                    </a>
                                </li>
                            
                            </ul>
                            <div class="tab-content">
                            
							
                                <div id="portlet_tab1" class="tab-pane active">
                                
								<div class="form-body">
									<input type="hidden" id="info_en" value="<?php //echo $info;?>"/>
									<div class="form-group">
										<label class="col-md-2 control-label">Title:</label>
										<div class="col-md-10">
											<input type="text" name="title_en" id="title_en" placeholder="Title" class="form-control" value="">
										</div>
									</div>
								
								<div class="form-group">
										<label class="col-md-2 control-label" >Description:</label>
                                        <div class="col-md-10">
										<textarea rows="3" name="description_en" id="description_en" class="form-control ckeditor autosizeme"></textarea>
                                        </div>
									</div>
                                    
                                <div class="form-group">
										<label class="col-md-2 control-label" >Long description:</label>
                                        <div class="col-md-10">
										<textarea rows="12" name="long_description_en" id="long_description_en" class="form-control ckeditor autosizeme"></textarea>
                                        </div>
									</div>
								
								
                                
                                
                                <div class="form-group">
										<label class="col-md-2 control-label">Meta keyword:</label>
										<div class="col-md-10">
											<input type="text" name="meta_keyword_en" id="meta_keyword_en" placeholder="Title" class="form-control" value="">
										</div>
									</div>
                                    
                                        <div class="form-group">
										<label class="col-md-2 control-label">Meta description:</label>
										<div class="col-md-10">
											<input type="text" name="meta_description_en" id="meta_description_en" placeholder="Title" class="form-control" value="">
										</div>
									</div>
                                    
                                    
                                   
								</div>
							
                                </div><!--portlet_tab1-->
                                
                                <div id="portlet_tab2" class="tab-pane">
                                
								<div class="form-body">
									<input type="hidden" id="info_fr" value="<?php //echo $info;?>"/>
									<div class="form-group">
										<label class="col-md-2 control-label">Title:</label>
										<div class="col-md-10">
											<input type="text" name="title_fr" id="title_fr" placeholder="Title" class="form-control" value="">
										</div>
									</div>
								
								<div class="form-group">
										<label class="col-md-2 control-label" >Description:</label>
                                        <div class="col-md-10">
										<textarea rows="3" name="description_fr" id="description_fr" class="form-control ckeditor autosizeme"></textarea>
                                        </div>
									</div>
                                    
                                <div class="form-group">
										<label class="col-md-2 control-label" >Long description:</label>
                                        <div class="col-md-10">
										<textarea rows="12" name="long_description_fr" id="long_description_fr" class="form-control ckeditor autosizeme"></textarea>
                                        </div>
									</div>
								
								
                                
                                
                                <div class="form-group">
										<label class="col-md-2 control-label">Meta keyword:</label>
										<div class="col-md-10">
											<input type="text" name="meta_keyword_fr" id="meta_keyword_fr" placeholder="Title" class="form-control" value="">
										</div>
									</div>
                                    
                                        <div class="form-group">
										<label class="col-md-2 control-label">Meta description:</label>
										<div class="col-md-10">
											<input type="text" name="meta_description_fr" id="meta_description_fr" placeholder="Title" class="form-control" value="">
										</div>
									</div>
                                    
                                    
                                   
								</div>
							
                                </div><!--portlet_tab2-->
                                
                                <div id="portlet_tab3" class="tab-pane">
                                
								<div class="form-body">
									<input type="hidden" id="info_vn" value="<?php //echo $info;?>"/>
									<div class="form-group">
										<label class="col-md-2 control-label">Title:</label>
										<div class="col-md-10">
											<input type="text" name="title_vn" id="title_vn" placeholder="Title" class="form-control" value="">
										</div>
									</div>
								
								<div class="form-group">
										<label class="col-md-2 control-label" >Description:</label>
                                        <div class="col-md-10">
										<textarea rows="3" name="description_vn" id="description_vn" class="form-control ckeditor autosizeme"></textarea>
                                        </div>
									</div>
                                    
                                <div class="form-group">
										<label class="col-md-2 control-label" >Long description:</label>
                                        <div class="col-md-10">
										<textarea rows="12" name="long_description_vn" id="long_description_vn" class="form-control ckeditor autosizeme"></textarea>
                                        </div>
									</div>
								
								
                                
                                
                                <div class="form-group">
										<label class="col-md-2 control-label">Meta keyword:</label>
										<div class="col-md-10">
											<input type="text" name="meta_keyword_vn" id="meta_keyword_vn" placeholder="Meta keyword" class="form-control" value="">
										</div>
									</div>
                                    
                                        <div class="form-group">
										<label class="col-md-2 control-label">Meta description:</label>
										<div class="col-md-10">
											<input type="text" name="meta_description_vn" id="meta_description_vn" placeholder="Meta description" class="form-control" value="">
										</div>
									</div>
                                    
                                    
                                   
								</div>
							
                                </div><!--portlet_tab3-->
                             
                            </div>
                        </div>  <!--portlet-tabs-->  
                  
                        </div><!--.form-body-->    
                        <div class="form-actions right">
									<button class="btn green submit_article sub-apso" type="submit" value="ok" id="save_article" name="ok">Submit</button>
									<button class="btn green submit_article" type="submit" value="ok" id="save_article" name="ok">Submit</button>
                                   <a class="btn default" href="<?php echo base_url();?>cleanarticle">Cancel</a>
								</div> 
               
            </form>
				<!-- END FORM--> 
               
			</div>
			<!-- END VALIDATION STATES-->
		</div>
	</div>
</div>


                                        
                                        
                                        
                                        
                                         
        </div><!--portlet-body form-->
</div><!--portlet box blue-->
