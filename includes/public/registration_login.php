<?php
// variable declaration
$username = "";
$email = "";
$errors = array();
// LOG USER IN
if (isset($_POST['login_btn'])) {
    $username = esc($_POST['username']);
    $password = esc($_POST['password']);
    if (empty($username)) {
        array_push($errors, "Username required");
    }
    if (empty($password)) {
        array_push($errors, "Password required");
    }
    if (empty($errors)) {
        $password = md5($password); // encrypt password
        $sql = "SELECT * FROM users WHERE username='$username' and password='$password' LIMIT 1";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            // get id of created user
            $reg_user_id = mysqli_fetch_assoc($result)['id'];
            //var_dump(getUserById($reg_user_id)); die();
// put logged in user into session array
            $_SESSION['user'] = getUserById($reg_user_id);
            // if user is admin, redirect to admin area
            if (in_array($_SESSION['user']['role'], ["Admin", "Author"])) {
                $_SESSION['message'] = "You are now logged in";
                // redirect to admin area
                header('location: ' . BASE_URL . '/admin/dashboard.php');
                exit(0);
            } else {
                $_SESSION['message'] = "You are now logged in";
                // redirect to public area
                header('location: index.php');
                exit(0);
            }
        } else {
            array_push($errors, 'Wrong credentials');
        }
    }
}
// Get user info from user id
function getUserById($id)
{
    global $conn; //rendre disponible, à cette fonction, la variable de connexion $conn
    $sql = "SELECT * FROM users WHERE id=$id";// requête qui récupère le user et son rôle
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
    return $user;
}
// escape value from form
function esc(string $value)
{
    // bring the global db connect object into function
    global $conn;
    $val = trim($value); // remove empty space sorrounding string
    $val = mysqli_real_escape_string($conn, $value);
    return $val;
}



if (isset($_POST['register_btn'])) {
    // Establish database connection
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Check if connection is successful
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Get form inputs
    $username = esc($_POST['username']);
    $password = esc($_POST['password']);
    $confirm_password = esc($_POST['confirm_password']);
    $email = esc($_POST['email']);

    // Validation and error handling
    $errors = array();
    if (empty($username)) {
        $errors[] = "Please enter a username";
    }
    if (empty($password)) {
        $errors[] = "Please enter a password";
    }
    if ($password != $confirm_password) {
        $errors[] = "The two passwords do not match";
    }

    // Check if username and email already exist
    $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
    $result = mysqli_query($conn, $user_check_query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        if ($user['username'] === $username) {
            $errors[] = "Username already exists";
        }
        if ($user['email'] === $email) {
            $errors[] = "Email already exists";
        }
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        // Hash the password
        $hashed_password = md5($password);
        // Execute insert query
        $insert_query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
        if (mysqli_query($conn, $insert_query)) {
            echo "Registration successful!";
            header ("location: login.php");
            // Redirect to another page or display success message here
        } else {
            $errors[] = "Connection Error";
        }
    }

    // Close database connection
    mysqli_close($conn);
}