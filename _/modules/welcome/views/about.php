<!-- BEGIN PAGE HEADER-->
<div class="row-fluid">
	<div class="span12">
    	<!-- Begin Logo_partners -->
        <?php echo $logo_partners;?>
        <!-- End Logo_partners --> 
		<!-- BEGIN PAGE TITLE & BREADCRUMB-->
		<h3 class="page-title"><strong><?php trans('About us') ?></strong>
		</h3>
		<ul class="breadcrumb">
			<li>
				<i class="icon-home"></i>
				<a href="<?php echo base_url() ?>"><?php trans('Home') ?></a> 
				<i class="icon-angle-right"></i>
			</li>            
		</ul>
		<!-- END PAGE TITLE & BREADCRUMB-->
	</div>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row-fluid">
	<div class="span12 news-page blog-page">
		<div class="row-fluid">
        <?php
            if($article_about)
            {
        ?>
			<div class="span8 blog-tag-data">
				<h1><?php trans('About us') ?></h1>
                <?php foreach($article_about as $about)
                    { 
                    $link = base_url() . "article/{$about['category_id']}/{$about['article_id']}/". utf8_convert_url($about['title']) .".html";
                    $base_url = base_url();
                    //  print_R($base_url.$blog['image']);exit;
                ?>
				<div class="row-fluid">
                            <?php if($about['image'] != 'assets/upload/images/no-image.jpg') { ?>
								<div class="span4 blog-img blog-tag-data" style="width: 20% !important;">
									<img class="img-responsive" alt="" style="margin-top: 1.5em;" src="<?php echo $base_url.$about['image'] ?>"/>
									<ul class="inline" style="float: right;">
										<li>
											<i class="icon-calendar"></i>
                                            <!--?php trans('Date-added') ?-->
										      <?php echo date('d/m/Y',strtotime($about['date_added'])) ?>
										</li>
										<!--li>
											<i class="fa fa-comments"></i>
											<a href="#">
											38 Comments </a>
										</li-->
									</ul>
									<!--ul class="inline blog-tags">
										<li>
											<i class="fa fa-tags"></i>
											<a href="#">
											Technology </a>
											<a href="#">
											Education </a>
											<a href="#">
											Internet </a>
										</li>
									</ul-->
								</div>
                            <?php } ?>
								<div class="span8 blog-article">
									<h3 style="line-height: 30px;">
									<?php echo $about['title'] ?>
									</h3>
									<p>
										<?php echo $about['description'] ?>
									</p>
									 <?php if($about['long_description'] != ''){ ?>
									<a href="<?php echo $link; ?>" class="btn blue">
								    <?php trans('see_more') ?> <i class="m-icon-swapright m-icon-white"></i>
									</a>
                                    <?php } ?>
								</div>
        				</div>
        				<hr />
                <?php } ?>
			</div>
            <?php
                unset($article_about[0]);
                
                if($article_about)
                {
            ?>
			<div class="span4">
				<h2><?php trans('link_website') ?></h2>
				<div class="top-news">
                <?php
                    foreach($article_about as $value)
                    {
                    $link = base_url() . "article/{$value['category_id']}/{$value['article_id']}/". utf8_convert_url($value['title']) .".html";
                ?>
					<a href="<?php echo $link ?>" class="btn green">
					<span style="display: inline !important; text-align: left;"><?php echo $value['title'] ?></span>
                    
					<em style="display: inline-block;">
    					- <i class="icon-calendar" style="font-size: 12px;">
    					<?php echo date('d-m-Y',strtotime($value['date_added'])) ?></i>
                        <?php 
                             $final_string = '';
                             $string = ltrim(strip_tags($value['description']));
                             $start = strpos($string,'(');
                            // $end = strpos($string,')');
                             if($start == 0 && substr($string,0,1) == '(')
                             {
                                 $end =strpos($string,')');
                                 $final_string = substr($string,$start,$end+1);
                                 $tags_string = substr($string,$start+1,$end-1);
                                 //   $final_string_2 = substr($blog['description'],$end);
                                // $no = str_replace($final_string, "", $value['description']);
                              
                        ?>
                        <i class="icon-tags" style="font-size: 12px;">
					       <?php echo isset($tags_string)? $tags_string : '' ; }?></i>
                    
					</em>
                             
					</a>
                <?php
                    }
                ?>
				</div>
			</div>
            <?php
                }
            }
            ?>

		</div>
	</div>
</div>

<!-- END PAGE CONTENT-->