<div class="page-content" style="background:#eeeeee;">

	<div class="row">

		<div class="col-md-12">

            <!-- BEGIN FILTER -->

			<div class="filter-v1 margin-top-10">

				<ul class="mix-filter">

					<li class="filter" data-filter="all">

						 All

					</li>

                    <?php foreach($mdata["category"] as $key=>$value){ ?>

					<li class="filter" data-filter="<?php echo strtolower($value); ?>">

						 <?php echo $value; ?>

					</li>

					<!--li class="filter" data-filter="category_3 category_1">

						 Wordpress and Logo

					</li-->

                    <?php } ?>

				</ul>

				<div class="row mix-grid">

                   

					

                    <?php foreach($mdata['document'] as $pa){ ?>

                    <?php if(isset($pa['file']) && $pa['file']!=''){

                          

							

                            $path = base_url().'assets/upload/intranet';

            				if(isset($pa['group']) && $pa['group']!='') $path .='/'.strtolower($pa['group']);

            				if(isset($pa['owner']) && $pa['owner']!='') $path .='/'.strtolower($pa['owner']);

            				if(isset($pa['category']) && $pa['category']!='') $path .='/'.strtolower($pa['category']);

            				if(isset($pa['subcat']) && $pa['subcat']!='') $path .='/'.strtolower($pa['subcat']);

							

							$type='';

                            $array = array();

                            if($pa['jpg'] =='x'){

                                $type = $path.'/'.strtolower($pa['file']).'.jpg';

							}

							elseif($pa['png']=='x'){

                                $type = $path.'/'.strtolower($pa['file']).'.png';

                            }

							

							if($pa['png'] != '' && strpos($pa['png'],'png')){

								$type = base_url().$pa['png'];

								

							}

							elseif($pa['jpg'] != ''  && strpos($pa['jpg'],'jpg')){

								$type = base_url().$pa['jpg'];	

							

							}

							

							//echo $type;

                            $size = @getimagesize($type);



							//

							

							//echo $path2.'/'.strtolower($pa['file']).'.'.$type;

                        ?>

					<div class="col-md-2 col-sm-3 mix <?php echo strtolower($pa['category']); ?>">

                        <div class="mix-inner center-block" style="min-height: 262px;">

                            <?php 

							if($size[2]>0){?>

                                <img class="img-responsive center-image"  src="<?php echo $type; ?>" alt=""/>

                            <?php }

							

							else{

                                    $file ='';

                                    if($pa['ai']=='x'){

                                        $file ='ai.jpg';

                                    }else if($pa['psd']=='x'){

                                        $file ='psd.jpg';

                                    }else if($pa['indd']=='x'){

                                        $file ='indd.jpg';

                                    }else if($pa['doc']=='x'){

                                        $file ='doc.jpg';

                                    }else if($pa['xls']=='x'){

                                        $file ='xls.jpg';

                                    }else if($pa['ppt']=='x'){

                                        $file ='ppt.jpg';

                                    }else if($pa['pdf']=='x'){

                                        $file ='pdf.jpg';

                                    }

                                ?>

                                <img class="img-responsive center-image" src="<?php echo base_url().'assets/upload/images/'.strtolower($file); ?>" alt=""/>

                            <?php } ?>

                            <div class="mix-details">

								<h4><?php echo $pa['name'];?></h4>

                                <div class="row">

                                

                                <div class="col-md-12" style="margin-bottom:56px;">

                                <?php if($size>0){ ?>

    								<a class="mix-preview fancybox-button" href="<?php echo $type; ?>" title="<?php echo $pa['name'] ?>" data-rel="fancybox-button">

    								<i class="fa fa-search"></i>

    								</a>

                                    <a class="mix-link" href="<?php echo $type.'" download="'.$pa['file'].'.'.strtolower($type) ?>">

										<i class="fa" style="text-transform:uppercase"><?php echo substr($type,-3); ?></i> 

                                    </a>

                                <?php }  ?>

                                </div>

                                <div class="clearfix visible-xs-block"></div>

                                <div class="col-md-offset-3">

                                <?php if($pa['ai']=='x'){ ?>

                                   <a class="mix-download btn btn-default btn-sm" style=" float:left; text-transform:uppercase" href="<?php echo $path.'/'.$pa['file'].'.ai" download="'.$pa['file'].'.ai' ?>">ai</a>

                                <?php }if($pa['psd']=='x'){ ?>

                                  <a class="mix-download" style="float:left; text-transform:uppercase" href="<?php echo $path.'/'.$pa['file'].'.psd" download="'.$pa['file'].'.psd' ?>">psd</a>

                                <?php }if($pa['indd']=='x'){ ?>

                                    <a class="mix-download btn btn-default btn-sm" style="text-transform:uppercase" href="<?php echo $path.'/'.$pa['file'].'.indd" download="'.$pa['file'].'.indd' ?>">indd</a>

                                <?php }if($pa['doc']=='x'){ ?>

                                     <a class="mix-download" style="float:left; text-transform:uppercase" href="<?php echo $path.'/'.$pa['file'].'.doc" download="'.$pa['file'].'.doc' ?>">doc</a>

                                <?php }if($pa['xls']=='x'){ ?>

                                    <a class="mix-download" style="float:left; text-transform:uppercase" href="<?php echo $path.'/'.$pa['file'].'.xls" download="'.$pa['file'].'.xls' ?>">xls</a>

                                <?php }if($pa['ppt']=='x'){ ?>

                                    <a class="mix-download btn btn-default btn-sm" style="text-transform:uppercase" href="<?php echo $path.'/'.$pa['file'].'.ppt" download="'.$pa['file'].'.ppt' ?>">ppt</a>

                                <?php }if($pa['pdf']=='x'){ ?>

                                    <a class="mix-download btn btn-default btn-sm" style="float:left; text-transform:uppercase" href="<?php echo $path.'/'.$pa['file'].'.pdf" download="'.$pa['file'].'.pdf' ?>">pdf</a>

                                <?php } ?>

                                </div>

                       			</div>

                                    

     

							</div>

                        </div>

					</div>

                    

                    <?php } ?>

                    <?php } ?>

				</div>

			</div>

			<!-- END FILTER -->

        </div>

  </div>

</div>

<style>

.center-image{

   position:absolute;

   top:50%;

   -webkit-transform:translateY(-50%);

   -moz-transform:translateY(-50%);

   -ms-transform:translateY(-50%);

   transform:translateY(-50%);

}

</style>