<script>
$(document).ready(function(){

	$(".hide_status").click(function(){
		var dataArr = $(this).attr('arr');
		$(this).parent().parent().attr('arr').hide();
		 $.ajax({
                      type : "POST",
                      url : $base_url + 'cleanarticle/check_status_article',
                      data : {dataArr:dataArr},
                      success: function(res) {
						 if(res == 1){
							 $("div.update-success").text("Update status successful");
							 $("div.update-success").fadeIn().delay(3500).fadeOut();
							
							
						}
                      },
        });
		
		
		
	});
	/*$(".edit_status").click(function(){
			$(".show-web").show();
			 $('#submit').val($(this).attr('arr'));
			 
		
	});*/
	
	$( ".edit_status" ).click(function() {
		   var arrayHide = [];
		  $('.check-table tr.active-row').each(function(){
              arrayHide.push($(this).find('.edit_status').attr('arr')+"|"); // insert rowid's to array
           });
		   arrayHide = arrayHide+",";
		   var dataEdit = $(this).attr('arr');
		 
			
			 $.ajax({
                      type : "POST",
                      url : $base_url + 'cleanarticle/sort_back',
                      data : {arrayHide:arrayHide, dataEdit:dataEdit},
                      success: function(res) {
						 if(res == 1){
							
							 $("div.update-success").text("Create group successful");
							 $("div.update-success").fadeIn().delay(3500).fadeOut();
							//location.reload(); 
								 
						}
						  //response = JSON.parse(res);
                      //  console.log(res);
                        // if(response.success == response.total) {
                           // $("div.alert-success").text("Run successful");
                           // $("div.alert-success").fadeIn().delay(3500).fadeOut();
                       // } else  {
							
                       // } 
						
                          //alert(data); //alert the data from the server
                      },
        });
		  //event.preventDefault();
	});
	
	 
	
	
});
</script>







<div class="col-md-6 col-md-offset-3 wrapper-popup">
					<!-- BEGIN SAMPLE TABLE PORTLET-->
                   
					<div class="portlet box blue">
                    
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-cogs"></i>Info article
							</div>
							<div class="tools">
								<a class="remove" href="javascript:;" data-original-title="" title="">
								</a>
															</div>
						</div>
						<div class="portlet-body">
							<div class="table-scrollable">
                             <div class="alert-success update-success"></div>
								<table class="table table-hover check-table">
								<thead>
								<tr>
									<th class="text-center">
										 .No
									</th>
									<th class="text-center">
										 Id Article
									</th>
									<th class="text-center">
										 Language
									</th>
									<th class="text-center">
										 Website
									</th>
									<th class="text-center">
										 Status
									</th>
								</tr>
								</thead>
								<tbody>
                                
                                <?php 
								$i=1;
								foreach($info as $val){?>
                                
                                
								<tr class="active-row" arr="<?php echo $i; ?>">
									<td class="text-center">
										<?php echo $i;?>
									</td>
									<td class="text-center">
										<?php echo $val[0]['article_id'];?>
									</td>
									<td class="text-center">
										<?php echo $val[0]['lang_code'];?>
									</td>
									<td class="text-center">
										 <?php echo $val[0]['website'];?>
									</td>
									<td class="text-center">
									<!--	<a class="btn default btn-xs black hide_status" href="javascript:;" arr="<?php echo $val[0]['article_id'].','.$val[0]['lang_code'].','.$val[0]['website'];?>"><span class="md-click-circle md-click-animate" style="height: 74px; width: 74px; top: -24.6px; left: 0.716675px;"></span>
										<i class="fa fa-trash-o"></i> Disable </a>-->
                                        
                                        <a class="btn default btn-xs blue edit_status" href="javascript:;" arr="<?php echo $val[0]['article_id'].','.$val[0]['lang_code'].','.$val[0]['website'];?>">
										<i class="fa fa-edit"></i> Create Group </a>
									</td>
								</tr>
								<?php $i++;}?>
								</tbody>
								</table>
                               <!-- <div class="portlet light show-web" style="display:none;">
					
						<div class="portlet-body form">
							<form role="form" class="form-horizontal" method="POST" action="" id="form">
								<div class="form-body">
									
									<div class="form-group form-md-line-input">
										<label class="col-md-3 control-label" for="form_control_1">Select website:</label>
										<div class="col-md-9">
											<div class="md-checkbox-inline">
                                            <?php foreach($getwebsite as $k=>$website){
												if($website['website'] != ''){
												?>
												<div class="md-checkbox">
												<input type="checkbox" id="<?php echo $website['website'];?>" class="md-check" name="<?php echo $k;?>">
													<label for="<?php echo $website['website'];?>">
													<span></span>
													<span class="check"></span>
													<span class="box"></span>
													<?php echo $website['website'];?> </label>
												</div>
                                                <?php }}?>
											<div class="md-checkbox has-error">
													<input type="checkbox" id="checkbox34" class="md-check" checked="">
													<label for="checkbox34">
													<span></span>
													<span class="check"></span>
													<span class="box"></span>
													Option 2 </label>
												</div>
												<div class="md-checkbox has-info">
													<input type="checkbox" id="checkbox35" class="md-check">
													<label for="checkbox35">
													<span></span>
													<span class="check"></span>
													<span class="box"></span>
													Option 3 </label>
												</div>
											</div>
										</div>
									</div>
									
									
								</div>
								<div class="form-actions">
									<div class="row">
										<div class="col-md-offset-2 col-md-10">
											<button type="button" class="btn default cancel_submit">Cancel</button>
											<button type="button" class="btn blue submit-form" id="submit">Submit</button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>-->
							</div>
						</div>
					</div>
					<!-- END SAMPLE TABLE PORTLET-->
				</div>