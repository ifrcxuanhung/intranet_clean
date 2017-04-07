
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
.sub-apso{ position:absolute; top:140px; right:25px; position:fixed; z-index:1000;}
.sub-apso2{  position:absolute; top:180px; right:25px; position:fixed; z-index:1000;}
.nav-tabs > li.active > a, .nav-tabs > li.active > a:focus, .nav-tabs > li.active > a:hover{
background-color:#337ab7; color:#FFF;	
}
.nav-tabs > li > a, .nav-pills > li > a{ color:#333333; background-color:#ddd;}
.form-horizontal .control-label{ font-weight:bold;}
</style>                   
                    
<div class="portlet box blue ">
 <div class="alert-success update-success"></div>
    <div class="portlet-title"  style="height:60px; background:#44b6ae;">
        <div class="caption" style="line-height:40px;">
            <?php trans('add_ifrc_articles');?>
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

				<form action="<?php base_url()?>ajax/add_modal_intranet" id="form_article" class="form-horizontal" method="post"  enctype="multipart/form-data" >
                	<input type="hidden" name="get_filter" value="<?php echo $get_filter; ?>"/>
                	<div class="form-body" style="padding:30px;">
                    <div class="row">
				
						<div class="alert alert-danger display-hide">
							<button class="close" data-close="alert"></button>
							You have some form errors. Please check below.
						</div>
						<div class="alert alert-success display-hide">
							<button class="close" data-close="alert"></button>
							Your form validation is successful!
						</div>
                        <div class="col-md-4">
                            
                             <!--   <div class="form-group">
                                            <label class="control-label col-md-3">Id article 
                                            </label>
                                            <div class="col-md-8">
                                          
                                                <input type="text" name="article_id" data-required="1" class="form-control" value="" />
                                            </div>
                                    </div>
                            -->
                                      <!-- <div class="form-group">
                                        <label class="control-label col-md-3">Category_id
                                        </label>
                                        <div class="col-md-8">
                                      
                                            <input type="text" name="category_id" data-required="1" class="form-control" value="" />
                                        </div>
                                    </div>-->
                                    
                                    
                                    
                                    <div class="form-group">
                                        <label class="control-label col-md-4"><?php trans('status_ifrc_articles');?>
                                        </label>
                                        <div class="col-md-8">
                                           
                                            <div class="radio-list" data-error-container="#form_2_membership_error">
                                                <label>
                                                <input type="radio" name="status" value="1" checked="checked" style=" margin-left:-10px;"/>
                                                Show 
                                                 <input type="radio" name="status" value="0" style=" margin-left:-10px;"/>
                                                Hide
                                                </label>
                                                
                                            </div>
                                            <div id="form_2_membership_error">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label col-md-4"><?php trans('clean_order_ifrc_articles');?> 
                                        </label>
                                        <div class="col-md-6">
                                      
                                            <input type="text" name="sort_order" data-required="1" class="form-control" value="" style="width:200px;"/>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group ">
                                            <label class="control-label col-md-4"><?php trans('images_ifrc_articles');?> </label>
                                            <div class="col-md-8">
                                                <div class="fileinput" data-provides="fileinput" data-name="myimage">
                                                  
                                                        <div id="file" class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                            <img id="img" src="" alt=""/>
        									</div>
                  
                                                    <div>
                                                        <span class="btn default btn-file">
                                                         <i class="fa fa-edit"></i>
                                                        <!--<span class="fileinput-new">
                                                        Select image </span>
                                                        <span class="fileinput-exists">
                                                        Change </span>-->
                                                        <input type="file" name="image" id ="image" value=""/>
                                                        </span>
                                                        <a href="javascript:;" class="btn red delete_image fileinput-exists tooltips" data-dismiss="fileinput" data-original-title="Delete">
                                                        <i class="fa fa-remove"></i> </a>
                                                    </div>
                                                </div>
                                              
                                            </div>
                                    </div>
                                    
                                  
                                 
                                    
                                    
                            
                                
                            
                         </div><!--col-md-4-->
                         <div class="col-md-8">
                         
                           <div class="form-group" style="height:25px;">
                                      
                                </div>
                         
                         
                            <!--<div class="form-group">
                                        <label class="control-label col-md-2">URL Second</label>
                                        <div class="col-md-8">
                                            <input name="url_second" type="text" class="form-control" value=""/>
                                        </div>
                                    </div>
                                    
                                      <div class="form-group">
                                        <label class="control-label col-md-2">URL Third</label>
                                        <div class="col-md-8">
                                            <input name="url_third" type="text" class="form-control" value=""/>
                                        </div>
                                    </div>
                                    -->
                                    
                                    
                                    
                                     <div class="form-group">
                                        <label class="control-label col-md-2"><?php trans('website_ifrc_articles');?> 
                                        </label>
                                        <div class="col-md-4">
                                           
                                            <select class="form-control select2me" name="website" id="website">
                                                 <?php 
												
													foreach ($list_website as $key => $val)
													{
														$sel = $input['a']['website'] == $val['website'] ? ' selected="selected"' : '';
                        				echo '<option value="'.$val['website'].'"'.$sel.'>'.(string) $val['website']."</option>\n";    
													}
													?>
                                                
                                            </select>
                                         
                                        </div>
                                        
                                       <!--<div class="col-md-4">
                                            <div class="input-group">
                                                <input type="text" name="website_add_new" id="website_add_new" class="form-control">
                                                <div class="input-group-btn">
                                                    <button value_id="owner" type="button" class="btn green add-new-item tooltips" data-original-title="Add new item"><i class="fa fa-plus"></i></button>
                                                </div>
                                               
                                            </div>
                                           
                                        </div>-->
                                          <div class="col-md-4">
                                        	<a class="btn green" data-toggle="modal" href="#basic">
												Add websites </a>
                                        </div>
                                        <div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header" style="background:#3598dc;">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h4 class="modal-title" style="color:#fff;">Add websites</h4>
										</div>
										<div class="modal-body">
											 <ul class="list-unstyled" id="unstyled">
                                             
                                              <li>
                                                    
                                                    <div class="portlet light" style="margin-bottom:5px; padding:12px 20px 1px; background:#3598dc; color:#fff;">
                                                    <div class="portlet-title" style="min-height:32px;">
                                                    <label>
  													<input type="checkbox" name="" value="checkall" id="checkall">Check all</label>
                                                    <div class="tools" style="padding:0px;">
                                                  
                                                	</div>
                                                    </div>
                                                    </div>
                                                </li>
                                             
                                             <?php foreach($int_article_websites as $website){?>
                                                <li>
                                              <div class="portlet light" style="margin-bottom:5px; padding:12px 20px 1px;">
                                                    <div class="portlet-title" style="min-height:32px;">
                                                      <label>
  <input type="checkbox" name="category_web" value="<?php echo $website['name'];?>">
  													<?php echo $website['name'];?>
                                                    </label>
                                                    <div class="tools" style="padding:0px;">
                                                    <a title="Delete" data-original-title="Delete" id="<?php echo $website['id'];?>" class="remove remove_website" href="">
                                                    </a>
                                                	</div>
                                                    </div>
                                                    </div>
                                                  
                                                </li>
                                              <?php }?>
                                                
                                            </ul>
                                            
                                           <div class="form-group"> 
                                           <div class="col-md-12">
                                          <input class="form-control addweb" placeholder="Add website" type="text" data-tabindex="1">
                                          </div>
                                          </div>
                                         <button type="button" class="btn blue addrows">Add new</button> 
                                           
										</div>
										<div class="modal-footer">
											<button type="button" class="btn default" data-dismiss="modal">Close</button>
											<button type="button" value_id="owner" class="btn green add-new-web">Save changes</button>
										</div>
									</div>
									<!-- /.modal-content -->
								</div>
								<!-- /.modal-dialog -->
							</div>
                                        
                                    </div>
                                    
                                    <!--<div class="form-group">
                                        <label class="control-label col-md-2">Par_cat_id </label>
                                        <div class="col-md-8">
                                            <input name="par_cat_id" type="text" class="form-control" value=""/>
                                        </div>
                                    </div>-->
                                    
                                    <div class="form-group">
                                        <label class="control-label col-md-2"><?php trans('clean_cate_ifrc_articles');?> 
                                        </label>
                                        <div class="col-md-4">
                                           
                                            <select class="form-control select2me" name="clean_cat" id="clean_cat">
                                                 <?php 
												
													foreach ($list_cat as $key => $val)
													{
														$sel = $input['a']['clean_cat'] == $val['clean_cat'] ? ' selected="selected"' : '';
                        				echo '<option value="'.$val['clean_cat'].'"'.$sel.'>'.(string) $val['clean_cat']."</option>\n";    
													}
													?>
                                                
                                            </select>
                                         
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <input type="text" name="cat_add_new" id="cat_add_new" class="form-control">
                                                <div class="input-group-btn">
                                                    <button value_id="owner" type="button" class="btn green add-new-cat tooltips" data-original-title="Add new item"><!--Add new item--><i class="fa fa-plus"></i></button>
                                                </div>
                                                <!-- /btn-group -->
                                            </div>
                                            <!-- /input-group -->
                                        </div>
                                        
                                    </div>
                                    
                                    
                                     <div class="form-group">
                                        <label class="control-label col-md-2"><?php trans('clean_scate_ifrc_articles');?> 
                                        </label>
                                        <div class="col-md-4">
                                           
                                            <select class="form-control select2me" name="clean_scat" id="clean_scat">
                                                 <?php 
												
													foreach ($list_scat as $key => $val)
													{
														$sel = $input['a']['clean_scat'] == $val['clean_scat'] ? ' selected="selected"' : '';
                        				echo '<option value="'.$val['clean_scat'].'"'.$sel.'>'.(string) $val['clean_scat']."</option>\n";    
													}
													?>
                                                
                                            </select>
                                         
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <input type="text" name="cat_add_new" id="scat_add_new" class="form-control">
                                                <div class="input-group-btn">
                                                    <button value_id="owner" type="button" class="btn green add-new-scat tooltips" data-original-title="Add new item"><!--Add new item--><i class="fa fa-plus"></i></button>
                                                </div>
                                                <!-- /btn-group -->
                                            </div>
                                            <!-- /input-group -->
                                        </div>
                                        
                                    </div>
                                    
                                      <!-- <div class="form-group">
                                        <label class="control-label col-md-2"><?php trans('clean_cate_ifrc_articles');?> </label>
                                        <div class="col-md-8">
                                            <input name="clean_cat" type="text" class="form-control" value=""/>
                                        </div>
                                    </div>-->
                                    
                                  <!--   <div class="form-group">
                                        <label class="control-label col-md-2"><?php trans('clean_scate_ifrc_articles');?></label>
                                        <div class="col-md-8">
                                            <input name="clean_scat" type="text" class="form-control" value=""/>
                                        </div>
                                    </div>-->
                                    
                                   
                        
                         </div><!--col-md-8-->
                         
                         
                          <div class="col-md-12">
                          
                          
                          
                              <div class="form-group">
                                <label class="control-label col-md-2" style="margin-left:33px;"><?php trans('url_ifrc_articles');?></label>
                                <div class="col-md-9">
                                    <input name="url" type="text" class="form-control" value=""/>
                                </div>
                            </div>
                            
                            
                             <div class="form-group">
                                <label class="control-label col-md-2" style="margin-left:33px;"><?php trans('date_update_ifrc_articles');?></label>
                                <div class="col-md-9">
                                    <div class="input-icon"><i class="fa fa-calendar"></i>
                                    <input type="text" data-date-viewmode="years" id="date_update" name="date_update" readonly="readonly"  data-date-format="yyyy-mm-dd" data-date="<?php echo date('Y-m-d');?>" value="<?php echo date('Y-m-d');?>" size="16" class="form-control date-picker"></div>
                                </div>
                            </div>
                            
                              <div class="form-group">
                                <label class="control-label col-md-2" style="margin-left:33px;"><?php trans('date_add_ifrc_articles');?></label>
                                <div class="col-md-9">
                                    <div class="input-icon"><i class="fa fa-calendar"></i>
                                    <input type="text" data-date-viewmode="years" id="date_add" name="date_add"  data-date-format="yyyy-mm-dd" data-date="<?php echo date('Y-m-d');?>" value="<?php echo date('Y-m-d');?>" size="16" class="form-control date-picker"></div>
                                </div>
                            </div>
                            
                            
                            <div class="form-group ">
                                    <label class="control-label col-md-2" style="margin-left:33px;"><?php trans('file_ifrc_articles');?> </label>
                                    <div class="col-md-9">
                                        <div class="fileinput" data-provides="fileinput" data-name="myimage">
                                          <div class="col-md-8">
                                                <div class="fileinput" data-provides="fileinput" data-name="myimage">
                                                  
                                                        <div id="file" class="fileinput-preview thumbnail" data-trigger="fileinput"  style="height:30px; width:200px;">
                                            <img id="img" src="" alt="" />
        									</div>
                                                
          
                                            <div>
                                                <span class="btn default btn-file">
                                                 <i class="fa fa-edit"></i>
                                                <!--<span class="fileinput-new">
                                                Select image </span>
                                                <span class="fileinput-exists">
                                                Change </span>-->
                                                <input type="file" name="files" id ="files" value=""/>
                                                </span>
                                                <a href="javascript:;" class="btn red tooltips delete_file_intranet" data-dismiss="fileinput" data-original-title="Delete">
                                                <i class="fa fa-remove"></i> </a>
                                            </div>
                                        </div>
                                      
                                    </div>
                            </div>
                            
                            
                            
                            
                       </div><!--col-md-12-->
                       
                      
                       
                       
                       
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
                                    Việt Nam
                                    </a>
                                </li>
                                 <li class="">
                                    <a data-toggle="tab" href="#portlet_tab3" aria-expanded="false">
                                    France
                                    </a>
                                </li>
                            
                            </ul>
                            <div class="tab-content">
                            
							
                                <div id="portlet_tab1" class="tab-pane active">
                                
								<div class="form-body">
									<input type="hidden" id="info_en" value="<?php //echo $info;?>"/>
									<div class="form-group">
										<label class="col-md-2 control-label"><?php trans('title_ifrc_articles');?></label>
										<div class="col-md-10">
											<input type="text" name="title_en" id="title_en" placeholder="Title" class="form-control" value="">
										</div>
									</div>
								
								<div class="form-group">
										<label class="col-md-2 control-label" ><?php trans('description_ifrc_articles');?></label>
                                        <div class="col-md-10">
										<textarea rows="3" name="description_en" id="description_en" class="form-control ckeditor autosizeme"></textarea>
                                        </div>
									</div>
                                    
                                <div class="form-group">
										<label class="col-md-2 control-label" ><?php trans('long_description_ifrc_articles');?></label>
                                        <div class="col-md-10">
										<textarea rows="12" name="long_description_en" id="long_description_en" class="form-control ckeditor autosizeme"></textarea>
                                        </div>
									</div>
								
								
                                
                                
                            <!--    <div class="form-group">
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
                                    -->
                                    
                                   
								</div>
							
                                </div><!--portlet_tab1-->
                                
                                <div id="portlet_tab2" class="tab-pane">
                                
								<div class="form-body">
									<input type="hidden" id="info_vn" value="<?php //echo $info;?>"/>
									<div class="form-group">
										<label class="col-md-2 control-label"><?php trans('title_ifrc_articles');?></label>
										<div class="col-md-10">
											<input type="text" name="title_vn" id="title_vn" placeholder="Title" class="form-control" value="">
										</div>
									</div>
								
								<div class="form-group">
										<label class="col-md-2 control-label" ><?php trans('description_ifrc_articles');?></label>
                                        <div class="col-md-10">
										<textarea rows="3" name="description_vn" id="description_vn" class="form-control ckeditor autosizeme"></textarea>
                                        </div>
									</div>
                                    
                                <div class="form-group">
										<label class="col-md-2 control-label" ><?php trans('long_description_ifrc_articles');?></label>
                                        <div class="col-md-10">
										<textarea rows="12" name="long_description_vn" id="long_description_vn" class="form-control ckeditor autosizeme"></textarea>
                                        </div>
									</div>
								
								
                                
                                
                               <!-- <div class="form-group">
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
									</div>-->
                                    
                                    
                                   
								</div>
							
                                </div><!--portlet_tab2-->
                                
                                <div id="portlet_tab3" class="tab-pane">
                                
								<div class="form-body">
									<input type="hidden" id="info_fr" value="<?php //echo $info;?>"/>
									<div class="form-group">
										<label class="col-md-2 control-label"><?php trans('title_ifrc_articles');?></label>
										<div class="col-md-10">
											<input type="text" name="title_fr" id="title_fr" placeholder="Title" class="form-control" value="">
										</div>
									</div>
								
								<div class="form-group">
										<label class="col-md-2 control-label" ><?php trans('description_ifrc_articles');?></label>
                                        <div class="col-md-10">
										<textarea rows="3" name="description_fr" id="description_fr" class="form-control ckeditor autosizeme"></textarea>
                                        </div>
									</div>
                                    
                                <div class="form-group">
										<label class="col-md-2 control-label" ><?php trans('long_description_ifrc_articles');?></label>
                                        <div class="col-md-10">
										<textarea rows="12" name="long_description_fr" id="long_description_fr" class="form-control ckeditor autosizeme"></textarea>
                                        </div>
									</div>
								
								
                                
                                
                              <!--  <div class="form-group">
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
									</div>-->
                                    
                                    
                                   
								</div>
							
                                </div><!--portlet_tab3-->
                                
                                
                             
                            </div>
                        </div>  <!--portlet-tabs-->  
                  
                        </div><!--.form-body-->    
                        <div class="form-actions right">
									<button class="btn blue submit_article sub-apso" type="submit" value="ok" id="save_article" name="ok">Save changes</button>
									<button class="btn blue submit_article" type="submit" value="ok" id="save_article" name="ok">Save changes</button>
                                     <a class="btn red sub-apso2" href="<?php echo base_url();?>table/ifrc_articles">Close</a>
                                   <a class="btn red" href="<?php echo base_url();?>table/ifrc_articles">Close</a>
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
