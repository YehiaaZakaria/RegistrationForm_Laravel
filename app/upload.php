<?php
function uploadImage()
{
    $targetDirectory = "uploads/";
    $targetFile = $targetDirectory . basename($_FILES["user_image"]["name"]);

    $uploadresponse = "";


    if (move_uploaded_file($_FILES["user_image"]["tmp_name"], $targetFile)) {
        $uploadresponse = "Ok";
    } else {
        $uploadresponse = "Not Ok";
    }

    return $uploadresponse;
}
?>