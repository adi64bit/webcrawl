
	<div class="col-md-3" style="margin-bottom:30px">
		<div class="listing-grid-wrapper">
			<a href="#" class="thumb">
				<span class="price"><?php echo get_post_meta( get_the_ID(), 'property_price_view', true );?></span>
				<?php
				/* cathc all image */
							$content = get_the_content();
							$images = catch_that_image($content);
				?>
				<img src="<?php echo $images[1][0];?>" alt="">
			</a>
			<div class="content">
				<span class="status">LAND FOR SALE</span>
				<div class="row">
					<div class="col-xs-2 col-md-2">
						<i class="fa fa-2x fa-map-marker fa-fw"></i>
					</div>
					<div class="col-xs-10 col-md-10 title">
						<a href="<?php the_permalink(); ?> "><h2><?php echo get_post_meta( get_the_ID(), 'property_heading', true );?></h2></a>
						<span>
						<?php echo get_post_meta( get_the_ID(), 'property_address_suburb', true );?>
						</span>
					</div>
				</div>
				<div class="footer">
					<ul>
						<li title="Size"><i class="fa fa-map-o fa-fw"></i><?php echo get_post_meta( get_the_ID(), 'property_land_area', true );?> m2</li>
						<li title="Bedroom"><i class="fa fa-hotel fa-fw"></i><?php echo get_post_meta( get_the_ID(), 'property_bedrooms', true );?></li>
						<li title="Bathroom"><i class="fa fa-tint fa-fw"></i><?php echo get_post_meta( get_the_ID(), 'property_bathrooms', true );?></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
