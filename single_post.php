<?php include('config.php'); ?>
<?php include('includes/all_functions.php'); ?>
<?php include('includes/public/head_section.php'); ?>

<title> <?php echo $post['title'] ?> | MyWebSite</title>

</head>

<body>


	<div class="container">

		<!-- Navbar -->
		<?php include(ROOT_PATH . '/includes/public/navbar.php'); ?>
		<!-- // Navbar -->



		<!-- Messages -->
		
		<!-- // Messages -->
        <div class="content">
            <div class="post-wrapper">
                <?php
                if(isset($_GET['post-slug'])) {
                    $slug = $_GET['post-slug'];

                    $body = getPost($slug);

                    if($body !== "Body is empty.") {
                ?>
                        <div class='full-post-div'>
                            <h2 class='post-title'><?php echo $slug; ?></h2>
                            <div class='post-body-div'><?php echo $body; ?></div>
                        </div>

                <?php
                    } else {
                ?>
                        <p class='error-message'>Error: Failed to retrieve body.</p>
                <?php
                    }
                } else {
                ?>
                    <p class='error-message'>No post slug provided.</p>
                <?php
                }
                ?>

                
            </div>
        
            <div class="post-sidebar">
                <div class="card">
                    <div class="card-header">
                        <h2>Topics</h2>
                    </div>
                    <div class="card-content">
                        <?php
                        $topics = getAllTopics();
                        if (!empty($topics)) {
                            foreach ($topics as $topic) {
                                echo "<a href='filtered_posts.php?topic_id={$topic['id']}'>{$topic['name']}</a>";
                            }
                        } else {
                            echo "<p>No topics found.</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>

        </div>

        
        

			
	
	</div>



	<!-- Footer -->
	<?php include(ROOT_PATH . '/includes/public/footer.php'); ?>
	<!-- // Footer -->