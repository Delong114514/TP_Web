<?php include('config.php'); ?>
<?php include('includes/all_functions.php'); ?>
<?php include('includes/public/head_section.php'); ?>
<?php include('includes/public/registration_login.php'); ?>
<title>MyWebSite | Home </title>

</head>

<body>


	<div class="container">

		<!-- Navbar -->
		<?php include(ROOT_PATH . '/includes/public/navbar.php'); ?>
		<!-- // Navbar -->

		<!-- Banner -->
		<?php include(ROOT_PATH . '/includes/public/banner.php'); ?>
		<!-- // Banner -->


		<!-- Messages -->
		
		<!-- // Messages -->

		<!-- content -->
		<div class="content">
			<h2 class="content-title">Recent Articles</h2>
			<hr>

			
			<?php
			// 调用getPublishedPosts函数来获取所有已发布文章
			$publishedPosts = getPublishedPosts();

			// 调用函数显示已发布文章
			displayPublishedPosts($publishedPosts);

			// 调用函数并传入 GIF 图片路径
			anya("static/images/spy-x-family-anya.gif");
			?>


		</div>
		<!-- // content -->
			



	
	</div>
	<!-- // container -->


	<!-- Footer -->
	<?php include(ROOT_PATH . '/includes/public/footer.php'); ?>
	<!-- // Footer -->