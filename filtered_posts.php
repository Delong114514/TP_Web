<?php include('config.php'); ?>
<?php include('includes/all_functions.php'); ?>
<?php include('includes/public/head_section.php'); ?>

<title>MyWebSite | <?php echo $topic_id?> </title>

</head>

<body>


	<div class="container">

		<!-- Navbar -->
		<?php include(ROOT_PATH . '/includes/public/navbar.php'); ?>
		<!-- // Navbar -->




		<!-- Messages -->
		
		<!-- // Messages -->

		<!-- content -->
		<div class="content">
			<hr>

			
			<?php
            if(isset($_GET['topic_id'])) {
                $topic_id = $_GET['topic_id'];
            }
			// 调用getPublishedPosts函数来获取所有已发布文章
			$PublishedPostsByTopic = getPublishedPostsByTopic($topic_id);

			// 调用函数显示已发布文章
			displayPublishedPosts($PublishedPostsByTopic);


			?>


		</div>
		<!-- // content -->
			



	
	</div>
	<!-- // container -->


	<!-- Footer -->
	<?php include(ROOT_PATH . '/includes/public/footer.php'); ?>
	<!-- // Footer -->
