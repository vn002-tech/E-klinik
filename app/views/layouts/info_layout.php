<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<link rel="shortcut icon" href="<?php print_link(SITE_FAVICON); ?>" />
		<title><?php echo $this->get_page_title();; ?></title>
		<?php 
			Html ::  page_meta('theme-color',META_THEME_COLOR);
			Html ::  page_meta('author',META_AUTHOR); 
			Html ::  page_meta('keyword',META_KEYWORDS); 
			Html ::  page_meta('description',META_DESCRIPTION); 
			Html ::  page_meta('viewport',META_VIEWPORT);
			Html ::  page_css('font-awesome.min.css');
			Html ::  page_css('animate.css');
		?>
				<?php 
			Html ::  page_css('bootstrap-default.css');
			Html ::  page_css('custom-style.css');
		?>
		<?php
			Html ::  page_js('jquery-3.3.1.min.js');
		?>
		<style>
			#main-content{
				padding:0;
				min-height:500px;
			}
		</style>
	</head>
	<body style="background: var(--bg-gradient); min-height: 100vh; display: flex; align-items: center; justify-content: center;">
		<div id="main-content" class="w-100">
			<div id="page-content">
				<?php $this->render_body();?>
			</div>
		</div>
		<?php 
			Html ::  page_js('popper.js');
			Html ::  page_js('bootstrap-4.3.1.min.js');
		?>
	</body>
</html>
