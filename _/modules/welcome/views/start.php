
        <!-- BEGIN PAGE BASE CONTENT -->
        <!-- Center Wrap BEGIN -->
        <div class="center-wrap">
            <div class="center-align">
                <div class="center-body">
                    <div class="row">
                    <?php foreach($starthomes as $starthome){?>
                    	<div class="col-sm-4 margin-bottom-30">
                            <a class="webapp-btn" href="<?php echo $starthome['url'];?>">
                                <h3><?php echo $starthome['title']; ?></h3>
                                <p><?php echo $starthome['description']; ?></p>
                            </a>
                        </div>
                     <?php }?>
                      
                    </div>
                   
                </div>
            </div>
        </div>
        <!-- Center Wrap END -->
        <!-- END PAGE BASE CONTENT -->
        <!-- BEGIN FOOTER -->
        <!-- BEGIN QUICK SIDEBAR TOGGLER -->
        <!--button type="button" class="quick-sidebar-toggler" data-toggle="collapse">
            <span class="sr-only">Toggle Quick Sidebar</span>
            <i class="icon-logout"></i>
        </button-->
        <!-- END QUICK SIDEBAR TOGGLER -->
       
        <!-- END FOOTER -->

<!-- END CONTAINER -->