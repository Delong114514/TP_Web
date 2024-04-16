<?php

function getPublishedPosts() {
    global $conn;
    
    // 执行查询，获取已发布的文章信息和每篇文章对应的主题名称
    $result = mysqli_query($conn, "SELECT p.id, p.user_id, p.title, p.slug, p.views, p.image, p.body, p.published, p.created_at, p.updated_at, t.name AS topic_name
                                   FROM posts p
                                   LEFT JOIN post_topic pt ON p.id = pt.post_id
                                   LEFT JOIN topics t ON pt.topic_id = t.id
                                   WHERE p.published = true");

    // 将结果集转换为关联数组
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $rows;
}


function getPostTopic($post_id) {
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT t.name AS topic_name
                                    FROM topics t
                                    INNER JOIN post_topic pt ON t.id = pt.topic_id
                                    WHERE pt.post_id = ?");
    
    mysqli_stmt_bind_param($stmt, "i", $post_id);
    
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $topic_name = $row['topic_name'];
    } else {
        $topic_name = "Unknown"; 
    }

    mysqli_stmt_close($stmt);

    return $topic_name;
}

function displayPublishedPosts($posts) {
    foreach ($posts as $post) {
?>
        <div class="content">
            <div class="post style="margin-left: 0px;">
                <div class="category"><?php echo $post['topic_name']; ?></div>
                <img src="static/images/<?php echo $post['image']; ?>" class="post_image">
                <div class="post_info">

                    <div class="content-title"><?php echo $post['title']; ?></div>
                    <span><?php echo $post['created_at']; ?></span>

                    <span class="read_more"><a href="single_post.php?post-slug=<?php echo $post['slug']; ?>">Read more...</a></span>
                </div>
            </div>
        </div>
<?php
    }
}



function getPost($slug) {
    global $conn;

    $stmt = mysqli_prepare($conn, "SELECT body FROM posts WHERE slug = ?");
    
    mysqli_stmt_bind_param($stmt, "s", $slug);
    
    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $body);

    mysqli_stmt_fetch($stmt);

    mysqli_stmt_close($stmt);

    if (empty($body)) {
        return "Body is empty.";
    } else {
        return $body;
    }
}



function getAllTopics() {
    global $conn;

    $query = "SELECT * FROM topics";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $topics = mysqli_fetch_all($result, MYSQLI_ASSOC);
        
        return $topics;
    } else {
        return array();
    }
}

?>

<?php
function anya($gifImagePath) {
    echo '
    <div id="floating-box">
        <img id="gif-img" src="'.$gifImagePath.'" alt="GIF Image">
    </div>
    <style>
        #floating-box {
            position: fixed;
            top: 50%;
            right: 0;
            transform: translateY(-50%);
            width: 100px;
            height: 100px;
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            z-index: 9999; 
        }

        #gif-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var floatingBox = document.getElementById("floating-box");
            var gifImg = document.getElementById("gif-img");
            var isPlaying = false;

            floatingBox.addEventListener("mouseenter", function() {
                if (!isPlaying) {
                    gifImg.src = "'.$gifImagePath.'";
                    isPlaying = true;
                }
            });

            floatingBox.addEventListener("mouseleave", function() {
                if (isPlaying) {
                    gifImg.src = "static/images/spy-x-family-anya1.gif";
                    isPlaying = false;
                }
            });
        });
    </script>
    ';
}


/**
 * This function returns the name and slug of published posts by topic ID in an array
 *
 * @param int $topic_id The ID of the topic
 * @return array An array containing the published posts with their names and slugs
 */

 function getPublishedPostsByTopic($topic_id) {
    global $conn;

    $topic_id = intval($topic_id);
    // Construct SQL query with a WHERE clause to filter by topic_id
    $sql = "SELECT p.id, p.user_id, p.title, p.slug, p.views, p.image, p.body, p.published, p.created_at, p.updated_at, t.name AS topic_name
            FROM posts p
            LEFT JOIN post_topic pt ON p.id = pt.post_id
            LEFT JOIN topics t ON pt.topic_id = t.id
            WHERE p.published = true AND pt.topic_id = $topic_id";

    // Execute the SQL query
    $result = mysqli_query($conn, $sql);


    // Initialize the final result array
    $final_posts = array();

    // Fetch each row from the result set
    while ($post = mysqli_fetch_assoc($result)) {
        // Add the post to the final result array
        $final_posts[] = $post;
    }

    // Free result set
    mysqli_free_result($result);

    // Return the final result array
    return $final_posts;
 
}
?>

