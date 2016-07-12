<?php
	$origin = apply_filters( 'get_origin', '' ); // sale del functions.php
?>
<?php get_header(); ?>
<body <?php body_class(); ?>>
	<?php require 'menu.php' ?>

	<!-- TOP BANNER - SEARCH -->
	<div class="bg-top">
		<div class="search-overlay"></div>
		<div class="row">
			<h1>Ofreciendo soluciones inteligentes<br>
					por más de 100 años.</h1>
			<div class="search-wrapper">
				<div class="search">
				<form>
					<form>
					<?php
					if($origin=='ch' || $origin=='pe'){
						?><span class="placeholder" data-messages="escribe aquí; hola" data-message="0">escribe aquí</span><?php
					}
					else if($origin=='ar' || $origin=='uy'){
						?><span class="placeholder" data-messages="escribí acá; hola" data-message="0">escribí acá</span><?php
					}
					?>
					<input type="text">
				</form>
				<div class="icon_search"><a href=""><img src="<?php echo get_template_directory_uri() ?>/img/icon_search.png"></a></div>
				</div>
				<div class="clear"></div>
				<?php
				if($origin=='ch' || $origin=='pe'){
					?><div class="description">Cuéntanos que estás buscando y obtén las mejores soluciones para tus necesidades.</div><?php
				}
				else if($origin=='ar' || $origin=='uy'){
					?><div class="description">Contanos qué estás buscando y obtené las mejores soluciones para tus necesidades.</div><?php
				}
				?>
			</div>

			<div class="product-list">
				<ul>
					<?php

						$ix = 0;
						foreach (get_field('home-featured', 'option') as $item) {
							$ix++;

							if(!isset($item['zone'])){
								$hide=false;
							}
							else{
								if(!in_array($origin, $item['zone'])) {
									$hide=true;
								}
								else{
									$hide=false;
								}
							}

							if(!$hide){

								$image = '';
								if (isset($item['image']['url']))
									$image = $item['image']['sizes']['medium'];
								$text = $item['text'];
								$link = str_replace('{site_url}', get_site_url(), $item['link']);
								echo '<li><a href="'.$link.'" style="background-image: url('.$image.')" data-ix="'.$ix.'" data-label="'.$text.'">'.$text.'</a></li>';

							}

						}
					?>
				</ul>
			</div>

			<ul class="search-results">
				<!--<li><a href="#">BGH QuickChef</a></li>-->
			</ul>
		</div>
	</div>

	<!-- TILES -->
	<div class="row" id="section_tiles">
		<div id="slides" class="col-3 col slides">
			<?php
				$ix = 0;
				foreach (get_field('home-slider', 'option') as $item) {
					$ix++;
					$title = $item['title'];
					$subtitle = $item['subtitle'];
					$subtitle_color = $item['subtitle_color'];
					$text = $item['text'];
					$link_text = $item['link_text'];
					$link = str_replace('{site_url}', get_site_url(), $item['link']);
					$background = '';
					if (isset($item['image_background']['url']))
						$background = $item['image_background']['url'];
					?>
					<div class="slide">
						<a href="<?=$link?>" class="clean" data-ix="<?=$ix?>" data-label="<?=$title?>">
							<div class="slider-home" style="background-image: url(<?=$background?>)">
								<div class="col-3-content">
									<div class="tile-selector"><?=$title?></div>
									<div class="tile-title" style="color: <?=$subtitle_color?>"><?=$subtitle?></div>
									<div class="tile-txt"><?=$text?></div>
									<div class="tile-button"><?=$link_text?></div>
								</div>
							</div>
						</a>
					</div>
					<?php
				}
			?>
		</div>
		<?php
			foreach (get_field('home-tiles', 'option') as $item_index => $item) {
				$ix++;
				$text = strip_tags($item['texto']);
				$button_text = $item['button_text'];
				$link = str_replace('{site_url}', get_site_url(), $item['link']);
				$icon = '';
				if (isset($item['icon']['url']))
					$icon = $item['icon']['url'];
				$color_background = $item['color_background'];
				$color_text = $item['color_text'];
				$classes = array();
				if ($item_index == 0) array_push($classes, 'col-last blue-quote');
				?>
				<div class="col-1 col grey-quote <?=implode(' ', $classes)?>" style="background-color: <?=$color_background?>">
					<a href="<?=$link?>" class="clean" data-ix="<?=$ix?>" data-label="<?=$text?>">
						<div class="quote-content">
							<div class="quote-icon"><img src="<?=$icon?>"></div>
							<div class="quote-txt" style="color: <?=$color_text?>"><?=$text?></div>
							<div class="quote-cta" style="color: <?=$color_text?>"><?=$button_text?></div>
						</div>
					</a>
				</div>
				<?php
			}
		?>

		<?php
			foreach (get_field('home-banner', 'option') as $item_index => $item) {
					$ix++;
					$title = $item['title'];
					$subtitle = $item['subtitle'];
					$text = $item['texto'];
					$button_text = $item['button_text'];
					$link = str_replace('{site_url}', get_site_url(), $item['link']);
					$background = '';
					if (isset($item['image_background']['url']))
						$background = $item['image_background']['url'];
				?>
				<div class="col-2 col servicio-celular col-last" style="background-image: url(<?=$background?>)">
					<a href="<?=$link?>" class="clean" data-ix="<?=$ix?>" data-label="<?=$title?>">
						<div class="col-2-content">
							<div class="tile-selector"><?=$title?></div>
							<div class="tile-title"><?=$subtitle?></div>
							<div class="col-2-tile-txt"><?=$text?></div>
							<div class="quote-cta"><span class="blue"><?=$button_text?></span></div>
						</div>
					</a>
				</div>
				<?php
			}
		?>
	</div>

	<?php
		/*
		if (have_posts()) {
			echo '<div class="posts list">';
				while (have_posts()) {
					the_post();
					require 'entry.php';
				}
			echo '</div>';
		} else {
			echo '<p>Sorry, no posts matched your criteria</p>';
		}
		*/
	?>
	</div>

	<script type="text/javascript" src="<?php echo get_template_directory_uri() ?>/js/home.js"></script>

	<?php get_footer(); ?>
</body>