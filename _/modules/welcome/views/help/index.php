<?php  
if(!$this->session->userdata('user_id')){
			redirect(base_url().'start');	
}?>
<div class="page-content">    
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
    	<div class="col-md-12" align="right" style="padding:10px 16px;">
        	<a class="btn btn-sm blue add-modal add-articles-order" href="<?php echo base_url() ?>table/int_article_category" keys="">
				    <i class="fa fa-edit"></i> Edit Category </a>
            <a class="btn btn-sm blue add-modal add-articles-order" href="<?php echo base_url() ?>table/int_article" keys="">
				    <i class="fa fa-edit"></i> Edit Article </a>
        </div>
    	<div class="col-md-3">
    		<ul class="ver-inline-menu tabbable margin-bottom-10 text-uppercase">
                <?php 
                $k = 0;
                foreach($dataSubCat as $value){
                $k++;    
                ?>
    			<li class="<?php echo $k == 1 ? 'active' : ''; ?>">
    				<a data-toggle="tab" href="#<?php echo str_replace(' ','',$value['subcat']); ?>">
    				<i class="fa fa-briefcase" style="color: transparent;"></i><?php echo trans($value['subcat']) ?></a>
    				<?php echo $k == 1 ? '<span class="after"></span>' : ''; ?>
                    
    			</li>
                <?php } ?>
    			<!--li>
    				<a data-toggle="tab" href="#tab_2">
    				<i class="fa fa-group"></i> Membership </a>
    			</li>
    			<li>
    				<a data-toggle="tab" href="#tab_3">
    				<i class="fa fa-leaf"></i> Terms Of Service </a>
    			</li>
    			<li>
    				<a data-toggle="tab" href="#tab_1">
    				<i class="fa fa-info-circle"></i> License Terms </a>
    			</li>
    			<li>
    				<a data-toggle="tab" href="#tab_2">
    				<i class="fa fa-tint"></i> Payment Rules </a>
    			</li>
    			<li>
    				<a data-toggle="tab" href="#tab_3">
    				<i class="fa fa-plus"></i> Other Questions </a>
    			</li-->
    		</ul>
    	</div>
    	<div class="col-md-9">
    		<div class="tab-content">
                <?php 
                $l = 0;
                foreach($dataSubCat as $value){
                $l++;    
                ?>
    			<div id="<?php echo str_replace(' ','',$value['subcat']) ?>" class="tab-pane <?php echo $l == 1 ? 'active' : ''; ?>">
                    
    				<div id="<?php echo str_replace(' ','',$value['subcat']).'-pa' ?>" class="panel-group">
                    <?php 
                    $i = 0;
                    if(isset($dataArt[str_replace(' ','',$value['subcat'])])){
                    foreach($dataArt[str_replace(' ','',$value['subcat'])] as $va){
                        $i++; ?>
    					<div class="panel panel-success">
    						<div class="panel-heading">
    							<h4 class="panel-title">
    							<a class="accordion-toggle" data-toggle="collapse" data-parent="#<?php echo str_replace(' ','',$va['subcat']).'-pa' ?>" href="#<?php echo str_replace(' ','',$va['subcat'].$va['id']) ?>">
    							<strong><?php echo $i.'. '.$va['title']; ?></strong></a>
    							</h4>
                                <div class="panel-toolbar">
                					<div class="btn-group pull-right" style="margin-top: -25px;">
                                        <a class="btn btn-icon-only default show-modal" type-modal="update" keys_value="<?php echo $va['id'] ?>" table_name="<?php echo 'article' ?>"  data-target="#modal" data-toggle="modal">
										<i class="fa fa-edit"></i></a>
                					</div>
                				</div>
    						</div>
    						<div id="<?php echo str_replace(' ','',$va['subcat'].$va['id']) ?>" class="panel-collapse <?php echo $i == 1 ? 'collapse in' : 'collapse' ?>">
    							<div class="panel-body">
                                    <div id="note<?php echo $i; ?>" data-pk="1" data-type="wysihtml5" data-toggle="manual" data-original-title="Enter notes">
                                         <p><?php echo $va['description'] ?></p>
        								 <p><?php echo $va['long_description'] ?></p>
    							    </div>
                                </div>
    						</div>
    					</div>
                        <?php }
                        }else{
                        ?>
                        <div class="panel panel-default">
                            <?php echo trans('no_data') ?>
                        </div>
                        <?php } ?>
    				</div>
                    
    			</div>
                <?php } ?>
    		</div>
    	</div>
    </div>
    <!-- END PAGE CONTENT-->
</div>