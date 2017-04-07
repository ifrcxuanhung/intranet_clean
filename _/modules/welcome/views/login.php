<!-- BEGIN PAGE HEADER-->
            <h3 class="page-title">
			<?php trans('sign_in'); ?>
			</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="<?php echo base_url(); ?>">Home</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<!--li>
						<a href="#">Extra</a>
						<i class="fa fa-angle-right"></i>
					</li-->
					<li>
						<a href="<?php echo base_url(); ?>login"><?php trans('sign_in'); ?></a>
					</li>
				</ul>
				<!--div class="page-toolbar">
					<div class="btn-group pull-right">
						<button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
						Actions <i class="fa fa-angle-down"></i>
						</button>
						<ul class="dropdown-menu pull-right" role="menu">
							<li>
								<a href="#">Action</a>
							</li>
							<li>
								<a href="#">Another action</a>
							</li>
							<li>
								<a href="#">Something else here</a>
							</li>
							<li class="divider">
							</li>
							<li>
								<a href="#">Separated link</a>
							</li>
						</ul>
					</div>
				</div-->
			</div>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row ">
				<div class="form-login">
					<!-- BEGIN SAMPLE FORM PORTLET-->
					<div class="portlet box yellow">
                    	<div class="portlet-title">
							<div class="caption">
								<i class="fa icon-lock"></i> 
							</div>
						</div>
                    	<div class="portlet-body">
								<?php
                                if($this->session->userdata('flash:old:message')) {
                                    ?>
                                    <div class="alert alert-danger">
                                        <button class="close" data-close="alert"></button>
                                        <?php trans('Login failed'); ?>
                                    </div>
                                    <?php
                                }
                                ?>
                                <form role="form" class="form-horizontal" action="<?php echo base_url(); ?>auth/login" method="post">
                                	<div class="form-group">
                                        
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="email"><?php trans('Email'); ?></label>
                                        <div class="col-md-8">
                                            <div class="input-icon">
                                                <i class="fa fa-envelope"></i>
                                                <?php echo form_input($identity); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="password"><?php trans('Password'); ?></label>
                                        <div class="col-md-8">
                                            <div class="input-icon left">
                                                <i class="fa fa-user"></i>
                                                <?php echo form_input($password); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-offset-3 col-md-10">
                                            <button class="btn green" type="submit"><?php trans('sign_in'); ?></button>
                                        </div>
                                    </div>
                                    <input type="hidden" id="remember" name="remember" value="1"/>
                                </form>
                           </div>
                        </div>
                   </div>
			</div>
			<!-- END PAGE CONTENT-->
		</div>
	</div>
	<!-- END CONTENT -->
 
  