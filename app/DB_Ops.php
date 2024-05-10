<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$dpServerName = "localhost";
$dpUserName = "root";
$dpPassword = "";
$dpName = "userDB";

$connection = mysqli_connect($dpServerName, $dpUserName, $dpPassword, $dpName);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch form data
    $full_name = $_POST['full_name'];
    $user_name = $_POST['user_name'];
    $birthdate = $_POST['birthdate'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = $_POST['password'];


    // Check if the username already exists in the database
    $check_username_query = "SELECT * FROM user WHERE user_name='$user_name'";
    $check_username_result = mysqli_query($connection, $check_username_query);

    if (mysqli_num_rows($check_username_result) > 0) {
        // Username already exists, return a message
        echo "Username already exists.";
    } else {
        // upload image
        include 'upload.php';
        $uploadResponse = uploadImage();
        //get image name
        $user_image = $_FILES["user_image"]["name"];
        // Username does not exist, insert the data into the database
        $insert_query = "INSERT INTO user (full_name, user_name, birthdate, email, phone, address, password, user_image) VALUES ('$full_name', '$user_name', '$birthdate', '$email', '$phone', '$address', '$password', '$user_image')";

        if ($uploadResponse == "Ok")
        {
            if (mysqli_query($connection, $insert_query)) {
                // Data insertion successful, return a success message
                echo "User registered successfully.";
            } else {
                // Data insertion failed, return an error message
                echo mysqli_error($connection);
            }
        }
        else
        {
            echo "Image upload failed.";
        }
       
    }
}

// Close the database connection
mysqli_close($connection);
?>