<?php include_once('header.php'); ?>

<div class="features">
	<div class="row">
		<h1><?php _e('Stupendously exciting login and user management.'); ?></h1>
		<p class="intro"><?php _e('Just the right amount of tools to get your job done.'); ?></p>
		<div class="col-md-6">
			<h2><?php _e('Installation'); ?>
				<?php if (file_exists('classes/config.php')) : ?>
				<small><?php _e('Complete'); ?></small>
				<?php else : ?>
				<small><?php _e('Not complete'); ?></small>
				<?php endif; ?>
			</h2>
			<p><?php _e('Get setup in minutes! Enjoy the super easy installation wizard to walk you through the setup process.'); ?></p>
			<?php if (!file_exists('classes/config.php')) : ?>
			<p><?php _e('Start your installation by clicking the button below!'); ?></p>
			<p><a href="install/index.php" class="btn btn-success"><?php _e('Begin Install'); ?></a></p>
			<?php endif; ?>
		</div>

		<div class="col-md-6">
			<h2><?php _e('Reports'); ?></h2>
			<p><?php _e('Keep track of how your site is doing with dynamic graphs to aid you.'); ?></p>
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<h2><?php _e('Security'); ?></h2>
			<p><?php _e('Our script has a couple default secure pages to get you started.'); ?></p>
		</div>

		<div class="col-md-6">
			<h2><?php _e('Admin tools'); ?></h2>
			<p><?php _e('Enjoy the luxury of having control over every aspect of your website.'); ?></p>
		</div>
	</div>
</div>

<?php include_once('footer.php'); ?>