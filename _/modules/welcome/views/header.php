<!-- Header BEGIN -->
<header class="page-header">
    <nav class="navbar mega-menu" role="navigation">
        <div class="container-fluid">
            <div class="clearfix navbar-fixed-top">
                <!-- Brand and toggle get grouped for better mobile display -->
                
                <!-- End Toggle Button -->
                <a id="index" class="page-logo" href="<?php echo base_url(); ?>" style="text-decoration: none;">
                    <strong style="font-size: 240%; color: #e4ad36"><font style="color:#0f84c0"><?php echo $setting_['logo_txt_left'] ?></font> <?php echo $setting_['logo_txt_right'] ?></strong>
                </a>
            	<!-- END LOGO -->
                
                <!-- BEGIN SEARCH -->
                
                <!-- END SEARCH -->
                <!-- BEGIN TOPBAR ACTIONS -->
                <div class="topbar-actions">
                    
                    <?php 
					
                            if(count($user_job) > 0) {
                                foreach($user_job as $uj) {
                                if (!preg_match("/^(http|https|ftp):/", $uj['url'])) {
								   $link = base_url().$uj['url'];
								}else{
									$link = $uj['url'];
								}
						
                        ?>
             			<?php if($this->session->userdata('user_id')){ ?>
							
						
                        <div class="btn-group hidden-xs hidden-sm">
                       
                            <?php if (isset($uj['icon']) && $uj['icon']!='') { ?>
                             <a href="<?php echo $link ?>" class="btn btn-icon-only tooltips default margin-top-10" data-container="body" data-placement="bottom" data-original-title="<?php echo $uj['name'] ?>">
                            <i class="fa <?php echo $uj['icon'] ?>"></i> 
                             </a>
                            <?php } else { ?>
                              <a href="<?php echo $link ?>" class="btn tooltips default margin-top-10" data-container="body" data-placement="bottom" data-original-title="<?php echo $uj['name'] ?>">
                              <?php echo $uj['text'] ?>
                              </a>
                             <?php } ?>
                            
                        </div>
                        <?php }
								}
							}?>
                        
                    <div class="btn-group-lang btn-group">
						<button type="button" class="btn btn-md dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <img src="<?php echo template_url() ?>global/img/flags/<?php echo $curent_language['code']; ?>.png" alt="">
                            <span> <?php echo $curent_language['code']; ?> </span>
    						<i class="fa fa-angle-down"></i>
						</button>
						<ul class="dropdown-menu-v2">
                            <?php
                            foreach($list_lang as $lang) {
                            ?>
                            <li class="<?php echo $curent_language['code'] == $lang['code'] ? 'active' : ''; ?>" >
								<a class="switch-language" href="javascript: void(0);" langcode="<?php echo $lang['code'] ?>">
								<img src="<?php echo template_url() ?>global/img/flags/<?php echo $lang['code'] ?>.png" alt=""> <?php echo $lang['name'] ?> </a>
							</li>
							<?php } ?>
                        </ul>
                    </div>
                    <?php if( $this->session->userdata('login') == '1'){ ?>
					<!-- BEGIN USER PROFILE -->
	                <div class="btn-group-img btn-group">
						<button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <span><?php echo isset($name) ? $name : '';  ?></span>
							<img alt="" class="img-hide1" src="<?php echo (isset($avatar) && is_file($avatar)) ? base_url().$avatar :  base_url() .'assets/upload/avatar/no_avatar.jpg'; ?>"/>
						</button>
						<ul class="dropdown-menu-v2" role="menu">
							<li class="active">
								<a href="<?php echo base_url(); ?>profile">
								<i class="icon-user"></i> <?php trans('my_profile'); ?> </a>
							</li>
                            <li>
                                <a href="<?php echo base_url()?>user-manage/profile.php"  id="change_password">
                                <i class="icon-lock"></i> <?php trans('Change password / email'); ?></a>
                            </li>
                            <li>
                                <a  id="logout_modal">
                                <i class="icon-key"></i> <?php trans('Log_out'); ?></a>
                            </li>
							
						</ul>
					</div>
					<!-- END USER PROFILE -->

					<!-- BEGIN QUICK SIDEBAR TOGGLER -->
	                
	                <!-- END QUICK SIDEBAR TOGGLER -->
                     <?php }else{ ?>
                     <div class="btn-group-notification btn-group">
                    	<a id="login_modal" class="btn btn-sm" data-target="#login_modals" data-toggle="modal">
    					     <i class="fa fa-key"></i>
    				    </a>
                    </div>
                    <?php }?> 
				</div>
                <!-- END TOPBAR ACTIONS -->
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <?php if($this->router->fetch_class() !='start'){?>
                <div class="nav-collapse collapse navbar-collapse navbar-responsive-collapse">
                    <ul class="nav navbar-nav text-uppercase">
                    <?php 
                    $i = 0;
                    //print_r($menu);
                    
                    foreach($menu as $key => $value) {
                        $i++;
                        $classLi = '';
                        $classSubLi = '';
                        if($id_menu_actived != 0) {
                            if(isset($value['list_child'])){
                                foreach($value['list_child'] as $ke=>$va){
                                    if($va == $id_menu_actived) {
                                        $classLi = ' open selected';
                                    }
                                }
                            }
                            if($value['id'] == $id_menu_actived) {
                                $classLi = ' open selected';
                                $classSubLi = ' active';
                            }
                        }
                       // $arrow = isset($value['cmenu']) ? '<span class="arrow'.($classLi != '' ? ' open': '').'"></span>' : '';
                        //$classSubLi = ' active';
                        if(substr($value['link'], 0, 4) != "html") {
                            $link = base_url().$value['link'];
                        } else {
                            $link = $value['link'];
                        }
                        ?>
                        <li class="dropdown dropdown-fw <?php echo $classLi; ?>">
                            <a class="mmenu" href="<?php echo $link; ?>" parent="<?php echo isset($value['cmenu']) ? "true" : "false" ?>" id_menu="<?php echo $value['id'] ?>">
                                <?php echo $value['name']; ?>
                            </a>
                            <?php
                            if(isset($value['cmenu'])) {
                            echo '<ul class="dropdown-menu dropdown-menu-fw">';
                            ?>
                            <li class="<?php echo $classSubLi ?>"><a class="table_menu" parent="false" id_menu="<?php echo $value['id'] ?>" href="javascript:;" id="all" table="<?php echo strtolower($value["name"]) ?>" link="<?php echo $link; ?>"><?php echo trans('all'); ?></a></li>
                            <?php foreach($value['cmenu'] as $k => $v) {
                                $classSubLi = $v['id'] == $id_menu_actived ? 'active' : '';
                                if(substr($v['link'], 0, 4) != 'html') {
                                    $link = base_url().$v['link'];
                                } else {
                                    $link = $v['link'];
                                }
                                if(isset($v['cmenu'])) {
                                   
                                ?>
                                <li class="dropdown more-dropdown-sub">
                                    <a class="mmenu" href="javascript:;" data-close-others="true" parent="true" id_menu="<?php echo $v['id'] ?>">
                                        <?php echo $v['name'] ?>
                                    </a>
                                    <ul class="dropdown-menu">
                                    <?php foreach($v['cmenu'] as $cm) { 
                                            if(substr($cm['link'], 0, 4) != 'html') {
                                                $link = base_url().$cm['link'];
                                            } else {
                                                $link = $cm['link'];
                                            }     
                                    ?>
                                    <?php if ($cm['value']==1 ) { ?>
                                        <li class="<?php echo $classSubLi ?>"><a class="mmenu" parent="false" id_menu="<?php echo $cm['id'] ?>" href="<?php echo $link; ?>" id="<?php echo $cm['name'] ?>"><?php echo $cm['name'] ?></a></li>
                                    <?php } else { ?>
                                     <li class="<?php echo $classSubLi ?>"><a class="table_menu" parent="false" id_menu="<?php echo $cm['id'] ?>" href="javascript:;" id="<?php echo strtoupper($cm['name']) ?>" table="<?php echo strtolower($value["name"]) ?>" link="<?php echo $link; ?>"><?php echo $cm['name'] ?></a></li>
                                    <?php } ?>
                                        
                                    <?php } ?>
                                    </ul>
                                </li>
                                <?php 
                                }else{ ?>
                                    <?php if ($v['value']==1 ) { ?>
                                        <li class="<?php echo $classSubLi ?>"><a class="mmenu" parent="false" id_menu="<?php echo $v['id'] ?>" href="<?php echo $link; ?>" id="<?php echo $v['name'] ?>"><?php echo $v['name'] ?></a></li>
                                    <?php } else { ?>
                                     <li class="<?php echo $classSubLi ?>"><a class="table_menu" parent="false" id_menu="<?php echo $v['id'] ?>" href="javascript:;" id="<?php echo strtoupper($v['name']) ?>" table="<?php echo strtolower($value["name"]) ?>" link="<?php echo $link; ?>"><?php echo $v['name'] ?></a></li>
                                    <?php } ?>
                                
                                <?php } ?>
                                
                            
                           <?php 
                            }
                            echo '</ul>';
                           } ?> 
                        </li>
                      <?php } ?>                 
                    </ul>
                </div>
            <?php }?>
            <!-- END NAVBAR COLLAPSE -->
        </div>
        <!--/container-->
    </nav>
</header>
<!-- Header END -->