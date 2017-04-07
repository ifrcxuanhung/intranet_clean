<?php  
if(!$this->session->userdata('user_id')){
			redirect(base_url().'start');	
}?>
<!-- BEGIN PAGE HEADER-->
            <h3 class="page-title" style="font-weight: bold;">
			<?php trans('contact_us'); ?> <small></small>
			</h3>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<div class="row margin-bottom-20">
                    <?php
                        if($article_contact['current'])
                        {
                            $contact = $article_contact['current'][0];
                    ?>
						<div class="col-md-6">
							<div class="space20">
							</div>
							<h3 class="form-section"><?php echo $contact['title']?></h3>
							<p>
								 <?php echo $contact['description']?>
							</p>
							<div class="well">
								<h4><?php trans('Address') ?></h4>
								<address>
								<?php 
                                            echo $contact['long_description'];
                                ?>
								<!--abbr title="Phone">P:</abbr> (234) 145-1810 </address-->
								<address>
								<strong><?php trans('Email') ?></strong><br/>
                                <?php $mail = explode(', ',$contact['meta_keyword']);
                                      //  print_r($mail);exit;
                                      if(!is_array($mail)){       
                                ?>
								        <a href="mailto:<?php echo $mail[0] ?>">
								        <?php echo $mail[0] ?> </a><br/>
                                <?php }else {
                                        foreach ($mail as $value){
                                ?>
                                    <a href="mailto:<?php echo $value; ?>">
								    <?php echo $value; ?> </a> <br/>
                                <?php  
                                        }
                                     }?>
								</address>
							</div>
						</div>
                    <?php
                        }
                    ?>
						<div class="col-md-6">
							<div class="space20">
							</div>
							<!-- BEGIN FORM-->
							<!--form action="#"-->
								<h3 class="form-section"><?php trans('Feedback_Form') ?></h3>
								<div class="form-group">
									<div class="input-icon">
										<i class="fa fa-user"></i>
										<input type="text" class="form-control"  name="contact_name" placeholder="<?php trans('Name') ?>"/>
									</div>
								</div>
								<div class="form-group">
									<div class="input-icon">
										<i class="fa fa-envelope"></i>
										<input name="contact_email" type="text" class="form-control" placeholder="<?php trans('Email') ?>"/>
									</div>
								</div>
								<div class="form-group">
									<textarea name="contact_message" class="form-control" rows="3=6" placeholder="<?php trans('Feedback') ?>"></textarea>
								</div>
                                <div class="form-group">
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                    	<img src="<?php echo base_url(); ?>captcha?cid=contact" class="row"/>
                                     </div>
                                     <div class="col-md-4 col-sm-4 col-xs-12">
                                        <input type="text" maxlength="5" class="form-control full-width" style="height: 48px;" placeholder="<?php trans('Input code here') ?>" name="contact_code" id="contact_code"/>
                                     </div>
                                     <div class="col-md-4 col-sm-4 col-xs-12 margin-top-10">
                                        <button type="button" class="btn blue button-sendcontact" style="float: right;"><i class="fa fa-mail-forward"></i> <?php trans('Send') ?></button>
                                     </div>
                                </div>  
                                <input type="hidden" name="email_receiving_email" value="<?php echo $contact['meta_keyword'] ?>" />
            					
                                <p class="contact_warning"></p>
                                
							<!--/form-->
							<!-- END FORM-->
						</div>
					</div>
                    <!-- Google Map -->
					<div class="row">
						<div id="map" class="gmaps margin-bottom-40" style="height:400px;">
						</div>
					</div>
				</div>
			</div>
			<!-- END PAGE CONTENT-->
	<!-- END CONTENT -->
    
    <!--<script src="<?php echo template_url(); ?>global/plugins/jqvmap/jqvmap/jquery.vmap.js" type="text/javascript"></script>
    <script src="<?php echo template_url(); ?>global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js" type="text/javascript"></script>
    <script src="<?php echo template_url(); ?>global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js" type="text/javascript"></script>
    <script src="<?php echo template_url(); ?>global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js" type="text/javascript"></script>
    <script src="<?php echo template_url(); ?>global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js" type="text/javascript"></script>
    <script src="<?php echo template_url(); ?>global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js" type="text/javascript"></script>
    <script src="<?php echo template_url(); ?>global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js" type="text/javascript"></script>-->
 	<script src="http://maps.google.com/maps/api/js?sensor=true" type="text/javascript"></script>
    <script src="<?php echo template_url(); ?>global/plugins/gmaps/gmaps.js" type="text/javascript"></script>