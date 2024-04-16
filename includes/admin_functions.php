
<?php


// Admin user variables
$admin_id = 0;
$isEditingUser = false;
$username = "";
$email = "";
// Topics variables
$topic_id = 0;
$isEditingTopic = false;
$topic_name = "";
// general variables
$errors = [];
/* - - - - - - - - - -
- Admin users actions
- - - - - - - - - - -*/
// if user clicks the create admin button
if (isset($_POST['create_admin'])) {
    createAdmin($_POST);
}

if (isset($_GET['edit-admin'])) {
    $admin_id = $_GET['edit-admin'];
    editAdmin($admin_id);
}

if (isset($_GET['delete-admin'])) {
    $admin_id = $_GET['delete-admin'];
    deleteAdmin($admin_id);
}

if (isset($_POST['update_admin'])) {
    updateAdmin($_POST);

}



function getAdminRoles(){
    global $conn;
    
    $query = "SELECT * FROM roles";
    $result = mysqli_query($conn, $query);

    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $rows;


}

function getAdminUsers(){
    global $conn;
    
    $query = "SELECT * FROM users";
    $result = mysqli_query($conn, $query);

    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $rows;
}



/* * * * * * * * * * * * * * * * * * * * * * *
* - Receives new admin data from form
* - Create new admin user
* - Returns all admin users with their roles
* * * * * * * * * * * * * * * * * * * * * * */
function createAdmin($request_values){
    global $conn, $username, $isEditingUser, $admin_id, $email;

    

    $username = $request_values['username'];
    $email = $request_values['email'];
    $password = $request_values['password'];
    $passwordConfirmation = $request_values['passwordConfirmation'];
    $role_id = $request_values['role_id'];



    $errors = array();
    if (empty($request_values['username'])) {
        $errors[] = "Please enter a username";
    }
    if (empty($request_values['password'])) {
        $errors[] = "Please enter a password";
    }
    if ($password != $passwordConfirmation) {
        $errors[] = "The two passwords do not match";
    }



    // If no errors, proceed with registration
    if (empty($errors)) {
        // Hash the password
        $hashed_password = md5($request_values['password']);
        // Execute insert query
        $insert_query = "INSERT INTO users (username, email, password,role) VALUES ('$username' ,'$email' ,'$hashed_password' ,$role_id)";
        if (mysqli_query($conn, $insert_query)) {
            $_SESSION['message'] = "Admin user created successfully";
            // Redirect to another page or display success message here
        } else {
            echo "Error: " . $insert_query . "<br>" . mysqli_error($conn);
        }
    } else {
        // Handle errors
        foreach ($errors as $error) {
            echo $error . "<br>";
    }
}



    // // Check if username and email already exist
    // $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
    // $result = mysqli_query($conn, $user_check_query);
    // $user = mysqli_fetch_assoc($result);

    // if ($user) {
    //     if ($user['username'] === $username) {
    //         $errors[] = "Username already exists";
    //     }
    //     if ($user['email'] === $email) {
    //         $errors[] = "Email already exists";
    //     }
    // }

    // $username = $_POST['username'];
    // $email = $_POST['email'];
    // $password = $_POST['password'];
    // $passwordConfirmation = $_POST['passwordConfirmation'];
    // $role_id = $_POST['role_id'];


    // // Close database connection
    mysqli_close($conn);
    
    header('location: users.php');
    exit(0);

}



/* * * * * * * * * * * * * * * * * * * * *
* - Takes admin id as parameter
* - Fetches the admin from database
* - sets admin fields on form for editing
* * * * * * * * * * * * * * * * * * * * * */
function editAdmin($admin_id) {
    global $conn, $username, $isEditingUser, $email, $admin_id;

    $isEditingUser = true;
    // 查询数据库以获取要编辑的管理员的信息
    $query = "SELECT * FROM users WHERE id = $admin_id LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $username = $user['username'];
        $email = $user['email'];
        
    } else {
        echo "User not found.";
    }
}



function updateAdmin($request_values){
    global $conn, $errors, $username, $isEditingUser, $admin_id, $email;

    $username = $request_values['username'];
    $email = $request_values['email'];
    $admin_id = $request_values['admin_id'];
    $role_id = $request_values['role_id'];
    // 验证表单数据
    if (empty($username)) { array_push($errors, "Please enter a username"); }
    if (empty($email)) { array_push($errors, "Please enter a email address"); }
    if (empty($role_id)) { array_push($errors, "Please chois a role"); }


    // 如果没有错误，更新管理员信息
    if (count($errors) == 0) {
        // 加密密码

        // 更新管理员信息
        $query = "UPDATE users SET username='$username', email='$email' , role = '$role_id' WHERE id=$admin_id";
        mysqli_query($conn, $query);

        // 设置成功消息并重定向到用户列表页面
        $_SESSION['message'] = "Update successfully,user role:" ;
        header('location: users.php');
        exit(0);
    }
}


function deleteAdmin($admin_id) {
    global $conn, $errors;

    // 删除与管理员相关联的 post 记录
    $query_delete_posts = "DELETE FROM posts WHERE user_id = $admin_id";
    mysqli_query($conn, $query_delete_posts);

    // 删除管理员记录
    $query = "DELETE FROM users WHERE id=$admin_id";
    mysqli_query($conn, $query);
    

    // 检查是否成功删除管理员
    if (mysqli_affected_rows($conn) > 0) {
        // 设置成功消息并重定向到用户列表页面
        $_SESSION['message'] = "Deleted successfully";
        header('Location: users.php');
        exit();
    } else {
        array_push($errors, "Failed to delete");
    }
}

function esc($str) {
    // 在此处添加转义逻辑
    return htmlspecialchars($str);
}


function countRowsInTable($tableName) {
    global $conn;

    $query = "SELECT COUNT(*) AS totalRows FROM $tableName";
    $result = mysqli_query($conn, $query);

    if (!$result) {

        return false;
    }

    $row = mysqli_fetch_assoc($result);
    return $row['totalRows'];
}


// if (isset($_POST['create_topic'])) {
//     createTopic($_POST);
// }

// if (isset($_GET['edit-topic'])) {
//     $topic_id = $_GET['edit-topic'];
//     editTopic($topic_id);
// }

// if (isset($_GET['delete-topic'])) {
//     $topic_id = $_GET['delete-topic'];
//     deleteTopic($topic_id);
// }

// if (isset($_POST['update_topic'])) {
//     updateTopic($_POST);

// }



// function getAllTopics() {
//     global $conn;


//     $query = "SELECT * FROM topics";
//     $result = mysqli_query($conn, $query);

//     $topics = array();


//     if ($result) {

//         while ($row = mysqli_fetch_assoc($result)) {
//             $topics[] = $row;
//         }


//         mysqli_free_result($result);
//     }

//     return $topics;
// }



?>