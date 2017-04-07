<?php

include_once( dirname(dirname(__FILE__)) . '/classes/check.class.php');
protect("1");

if ( !isset($_POST['add_user']) && !isset($_POST['add_level']) && !isset($_POST['searchUsers']) )
	include_once('header.php');

?>
	<div class="row">
		<div class="tabbable tabs-left">
			<ul class="nav nav-tabs">
				<li><a href="#user-control" data-toggle="tab"><i class="glyphicon glyphicon-list"></i> <?php _e('Users'); ?></a></li>
				<li><a href="#level-control" data-toggle="tab"><i class="glyphicon glyphicon-list"></i> <?php _e('Levels'); ?></a></li>
				<li><a href="#reports" data-toggle="tab"><i class="glyphicon glyphicon-folder-open"></i> <?php _e('Reports'); ?></a></li>
				<li><a href="#send-email" data-toggle="tab"><i class="glyphicon glyphicon-envelope"></i> <?php _e('Send email'); ?></a></li>
				<li><a href="settings.php"><i class="glyphicon glyphicon-cog"></i> <?php _e('Settings'); ?></a></li>
			</ul>

			<div class="tab-content">
            
            

				<!-- - - - - - - - - - - - - - - - -

						Control users

				- - - - - - - - - - - - - - - - - -->
				<div class="tab-pane col-md-10 fade" id="user-control">
					<?php include_once('page/user-control.php'); ?>
				</div>

				<!-- - - - - - - - - - - - - - - - -

						Modify levels

				- - - - - - - - - - - - - - - - - -->

				<div class="tab-pane col-md-10 fade" id="level-control"></div>

				<!-- - - - - - - - - - - - - - - - -

						Reports

				- - - - - - - - - - - - - - - - - -->
				<div class="tab-pane col-md-10 fade" id="reports"></div>

				<!-- - - - - - - - - - - - - - - - -

						Send email

				- - - - - - - - - - - - - - - - - -->
				<div class="tab-pane col-md-10 fade" id="send-email"></div>

			</div>
		</div>
	</div>

<?php include_once('footer.php'); ?>