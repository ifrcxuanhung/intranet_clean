<!-- BEGIN QUICK SIDEBAR -->
<a href="javascript:;" class="page-quick-sidebar-toggler"><i class="icon-login"></i></a>
<div class="page-quick-sidebar-wrapper">
	<div class="page-quick-sidebar">
		<div class="nav-justified">
			<ul class="nav nav-tabs nav-justified">
				<li class="active">
					<a href="#quick_sidebar_tab_1" data-toggle="tab">
					Users <span class="badge badge-success"><?php echo count($listOnlineUser); ?></span>
					</a>
				</li>
				<li>
					<a href="#quick_sidebar_tab_2" data-toggle="tab">
					<?php echo trans('attendance') ?> <!--span class="badge badge-success">7</span-->
					</a>
				</li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active page-quick-sidebar-chat" id="quick_sidebar_tab_1">
					<div class="page-quick-sidebar-chat-users" data-rail-color="#ddd"> <!-- data-wrapper-class="page-quick-sidebar-list" -->
						<h3 class="list-heading">Staff</h3>
						<ul class="media-list list-items" id="userList">
                            <?php 
                            //print_R($listOnlineUser);
                            foreach($listOnlineUser as $value){ ?>
							<li class="media">
								<div class="media-status">
									<span class="badge badge-success">Online</span>
								</div>
								<img class="media-object" height="45px" src="<?php echo base_url().$value['avatar']; ?>" alt="..."/>
								<div class="media-body">
									<h4 class="media-heading"><?php echo $value['last_name'].' '.$value['first_name']; ?></h4>
									<div class="media-heading-sub">
										 <?php echo $value['services']; ?>
									</div>
								</div>
							</li>
                            <?php } ?>
						</ul>
					</div>
				</div>
				<div class="tab-pane page-quick-sidebar-alerts" id="quick_sidebar_tab_2">
					<div class="page-quick-sidebar-alerts-list">
						<h3 class="list-heading"><?php echo trans('Today is').' '.date('l') ?></h3>
						<ul class="feeds list-items">
							<li>
								<div class="col1">
									<div class="cont">
										<div class="input-group input-sm input-daterange">
											<input type="text" name="from" class="form-control clockface_1 clockface-open" value="8:30">
											<span class="input-group-addon">
											- </span>
											<input type="text" name="to" class="form-control clockface_1 clockface-open" value="17:30">
									    </div>
									</div>
								</div>
                                <div class="col2">
                                    <div class="date">
    									<a class="btn btn-icon-only yellow" id="update_attendate">
    									   <i class="fa fa-share"></i> 
    									</a>
                                    </div>
								</div>
							</li>
						</ul>
                        <h3 class="list-heading">List Tasks</h3>
                		<ul class="list-items feeds">
                            <?php 
                            foreach($list_task as $value){ ?>
                    			<li>
        							<div class="col1">
        								<div class="cont">
        									<div class="desc">
                                                    <?php echo $value['tasks'] ?>
                                            </div>
                                            <span class="label label-sm label-warning ">
											<i class="fa fa-file"></i>  <?php echo $value['project'] ?>
											</span>
                                            <span class="label label-sm label-success ">
											<i class="fa fa-file"></i>  <?php echo $value['pr'] ?>
											</span>
        								</div>
        							</div>
                                    <div class="col2">
                                        <div class="cont">
                                            <div class="task-config-btn btn-group">
        										<a data-close-others="true" data-hover="dropdown" data-toggle="dropdown" href="javascript:;" class="btn btn-xs default">
        										<i class="fa fa-cog"></i><i class="fa fa-angle-down"></i>
        										</a>
        										<ul class="dropdown-menu pull-right">
        											<li>
        												<a href="javascript:;" class="updatetask" id_task="<?php echo $value['task_id'] ?>" data-status="Done">
        												<i class="fa fa-check"></i> Complete </a>
        											</li>
        											<li>
        												<a href="javascript:;" class="updatetask" id_task="<?php echo $value['task_id'] ?>"  data-status="Current">
        												<i class="fa fa-pencil"></i> Current </a>
        											</li>
        											<li>
        												<a href="javascript:;" class="updatetask" id_task="<?php echo $value['task_id'] ?>" data-status="Active">
        												<i class="fa fa-add"></i> Active </a>
        											</li>
        										</ul>
        									</div>
                                        </div>
                                        <div class="date">
        									<?php echo date('Y-m-d', strtotime($value['date_update'])) ?>
                                        </div>
        							</div>
        						</li>
                            <?php } ?>
                		</ul>
                        
                        
                        
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- END QUICK SIDEBAR -->	