<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
    <meta charset="utf-8"/>
    <title><?php echo trans('title_website'); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="<?php echo template_url(); ?>global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo template_url(); ?>global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo template_url(); ?>global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo template_url(); ?>global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo template_url(); ?>global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
    <link href="<?php echo template_url(); ?>global/plugins/morris/morris.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo template_url(); ?>global/plugins/select2/select2.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo template_url(); ?>global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css"/>    
    <link rel="stylesheet" type="text/css" href="<?php echo template_url(); ?>global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo template_url(); ?>global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo template_url(); ?>global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css"/>
    <link href="<?php echo template_url(); ?>global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo template_url(); ?>admin/pages/css/portfolio.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo template_url(); ?>global/plugins/bootstrap-fileinput/bootstrap-fileinput.css"/>
    <!-- END PAGE LEVEL PLUGIN STYLES -->
    <!-- BEGIN PAGE STYLES -->
    <link rel="stylesheet" type="text/css" href="<?php echo template_url(); ?>global/plugins/clockface/css/clockface.css"/>
    <link href="<?php echo template_url(); ?>admin/pages/css/profile.css" rel="stylesheet" type="text/css"/>
    <!-- END PAGE STYLES -->
    <!-- BEGIN THEME STYLES -->
    <!-- DOC: To use 'material design' style just load 'components-md.css' stylesheet instead of 'components.css' in the below style tag -->
    <link href="<?php echo template_url(); ?>global/css/components-md.css" id="style_components" rel="stylesheet" type="text/css"/>
    <link href="<?php echo template_url(); ?>global/css/plugins-md.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo template_url(); ?>admin/layout5/css/layout.css" rel="stylesheet" type="text/css"/>
    
    <?php if($this->router->fetch_class() =='start'){ ?>
    <link href="<?php echo template_url(); ?>admin/layout5/css/layout.min.css" rel="stylesheet" type="text/css"/>
    <?php }?>
    
    <link href="<?php echo template_url(); ?>admin/layout5/css/custom.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo template_url(); ?>css/style.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo template_url(); ?>global/plugins/bootstrap-editable/bootstrap-editable/css/bootstrap-editable.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo template_url(); ?>global/plugins/bootstrap-editable/inputs-ext/address/address.css"/>
    
    <link rel="stylesheet" type="text/css" href="<?php echo template_url(); ?>global/plugins/bootstrap-summernote/summernote.css">
 
    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="<?php echo template_url(); ?>img/favicon.ico"/>
    </head>
    <!-- END HEAD -->
    <!-- BEGIN BODY -->
    <!-- DOC: Apply "page-header-fixed-mobile" and "page-footer-fixed-mobile" class to body element to force fixed header or footer in mobile devices -->
    <!-- DOC: Apply "page-sidebar-closed" class to the body and "page-sidebar-menu-closed" class to the sidebar menu element to hide the sidebar by default -->
    <!-- DOC: Apply "page-sidebar-hide" class to the body to make the sidebar completely hidden on toggle -->
    <!-- DOC: Apply "page-sidebar-closed-hide-logo" class to the body element to make the logo hidden on sidebar toggle -->
    <!-- DOC: Apply "page-sidebar-hide" class to body element to completely hide the sidebar on sidebar toggle -->
    <!-- DOC: Apply "page-sidebar-fixed" class to have fixed sidebar -->
    <!-- DOC: Apply "page-footer-fixed" class to the body element to have fixed footer -->
    <!-- DOC: Apply "page-sidebar-reversed" class to put the sidebar on the right side -->
    <!-- DOC: Apply "page-full-width" class to the body element to have full width page without the sidebar menu -->
    <body class="page-md page-header-fixed page-quick-sidebar-over-content">
    <!-- BEGIN MAIN LAYOUT -->
    
   
	<div class="wrapper">
        <?php echo $header; ?>
	    <div class="container-fluid main-container">
            <div class="modal bs-modal-md fade" id="login_modals" tabindex="-1" data-width="760">
				<div class="modal-dialog modal-md">
					<div class="modal-content">
						<div class="modal-body">
							<img src="<?php echo template_url(); ?>global/img/loading-spinner-grey.gif" alt="" class="loading"/>
							<span>
							&nbsp;&nbsp;Loading... </span>
						</div>
					</div>
				</div>
			</div>
            <?php echo $content; ?>
            <div class="modal fade" id="modal" role="dialog" tabindex="-1" aria-hidden="true">
            	<div class="page-loading page-loading-boxed">
            		<img src="<?php echo template_url(); ?>global/img/loading-spinner-grey.gif" alt="" class="loading"/>
            		<span>
            		&nbsp;&nbsp;Loading... </span>
            	</div>
            	<div class="modal-dialog modal-full">
            		<div class="modal-content">
            		</div>
            	</div>
            </div>
               
            <?php echo $footer; ?>
        </div> 
    </div>
    <!-- END MAIN LAYOUT -->
    <a href="javascript:;" class="go2top"><i class="icon-arrow-up"></i></a>
    <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
    <!-- BEGIN CORE PLUGINS -->
    <!--[if lt IE 9]>
    <script src="<?php echo template_url(); ?>global/plugins/respond.min.js"></script>
    <script src="<?php echo template_url(); ?>global/plugins/excanvas.min.js"></script> 
    <![endif]-->
    <script type="text/javascript">
        var $template_url = '<?php echo template_url(); ?>';
        var $base_url = '<?php echo base_url(); ?>';
        var $admin_url = '<?php echo admin_url(); ?>';
        var $app = {'module': '<?php echo $this->router->fetch_module(); ?>',
            'controller': '<?php echo $this->router->fetch_class(); ?>',
            'action': '<?php echo $this->router->fetch_method(); ?>'};
		var $lang ='<?php echo $curent_language['code'] ?>';
        var $login = '<?php echo $loginAuth; ?>';
    </script>
    <script src="<?php echo template_url(); ?>global/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo template_url(); ?>global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
    <!--script src="<?php echo template_url(); ?>global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script-->
    <script src="<?php echo template_url(); ?>global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
    <script src="<?php echo template_url(); ?>global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo template_url(); ?>global/plugins/bootstrap/js/bootstrap-ckeditor-fix.js" type="text/javascript"></script>
    <script src="<?php echo template_url(); ?>global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
    <script src="<?php echo template_url(); ?>global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="<?php echo template_url(); ?>global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="<?php echo template_url(); ?>global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
    <script src="<?php echo template_url(); ?>global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
    <script src="<?php echo template_url(); ?>global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script type="text/javascript" src="<?php echo template_url(); ?>global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
  
    <script type="text/javascript" src="<?php echo template_url(); ?>global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
    <script type="text/javascript" src="<?php echo template_url(); ?>global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="<?php echo template_url(); ?>global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
    <script type="text/javascript" src="<?php echo template_url(); ?>global/plugins/clockface/js/clockface.js"></script>
    <script type="text/javascript" src="<?php echo template_url(); ?>global/plugins/bootstrap-daterangepicker/moment.min.js"></script>
    <script type="text/javascript" src="<?php echo template_url(); ?>global/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script type="text/javascript" src="<?php echo template_url(); ?>global/plugins/bootstrap-fileinput/bootstrap-fileinput.js"></script>
    <script type="text/javascript" src="<?php echo template_url(); ?>global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
 
    <!--AUTO SIZE-->
    <script src="<?php echo template_url(); ?>global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="<?php echo template_url(); ?>global/plugins/autosize/autosize.min.js" type="text/javascript"></script>
    <!--END AUTO SIZE-->
    <!-- END PAGE LEVEL PLUGINS -->
    <script type="text/javascript" src="<?php echo template_url(); ?>global/plugins/ckeditor/ckeditor.js"></script>
   
    <script src="<?php echo template_url(); ?>global/plugins/bootbox/bootbox.min.js" type="text/javascript"></script>
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
  
    <script type="text/javascript" src="<?php echo template_url(); ?>global/plugins/fancybox/source/jquery.fancybox.pack.js"></script>
    <script type="text/javascript" src="<?php echo template_url(); ?>global/plugins/jquery-mixitup/jquery.mixitup.min.js"></script>   
    <script type="text/javascript" src="<?php echo template_url(); ?>global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
    
    <script type="text/javascript" src="<?php echo template_url(); ?>global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script> 
   
    <script src="<?php echo template_url(); ?>global/scripts/datatable.js"></script>
   
    <script src="<?php echo template_url(); ?>global/scripts/metronic.js" type="text/javascript"></script>
    <script src="<?php echo template_url(); ?>admin/layout5/scripts/layout.js" type="text/javascript"></script>
    <script src="<?php echo template_url(); ?>admin/layout5/scripts/quick-sidebar.js" type="text/javascript"></script>
    <script src="<?php echo template_url(); ?>global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <!-- cleanend clean -->
    <script src="<?php echo template_url(); ?>global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo template_url(); ?>global/plugins/bootstrap-editable/bootstrap-editable/js/bootstrap-editable.js"></script>
  
    <script type="text/javascript" src="<?php echo template_url(); ?>global/plugins/bootstrap-editable/inputs-ext/address/address.js"></script>
    
    <script type="text/javascript" src="<?php echo template_url(); ?>global/plugins/bootstrap-editable/inputs-ext/wysihtml5/wysihtml5.js"></script> 
    <script src="<?php echo template_url(); ?>global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
    <script src="<?php echo template_url(); ?>global/plugins/bootstrap-summernote/summernote.min.js" type="text/javascript"></script>
    <script src="<?php echo template_url(); ?>admin/layout4/scripts/layout.js" type="text/javascript"></script>
<script src="<?php echo template_url(); ?>admin/layout4/scripts/demo.js" type="text/javascript"></script>
<script src="<?php echo template_url(); ?>admin/pages/scripts/components-editors.js"></script>


    <script>
    jQuery(document).ready(function() {    
        Metronic.init(); // init metronic core componets
        Layout.init(); // init layout
        QuickSidebar.init(); // init quick sidebar
		 ComponentsEditors.init();
		
       // Index.init(); // init index page
        //Tasks.initDashboardWidget(); // init tash dashboard widget
      //  FormEditable.init();
    });
    </script>
   
    <!-- END JAVASCRIPTS -->
    <script data-main="<?php echo base_url(); ?>assets/apps/welcome/main" src="<?php echo base_url(); ?>assets/bundles/require.js"></script>
    <!-- CUSTOME JAVASCRIPTS -->
    
    <!-- END CUSTOME JAVASCRIPTS -->
    
</body>
<!-- END BODY -->
</html>