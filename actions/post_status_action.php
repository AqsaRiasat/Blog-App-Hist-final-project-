<?php

require "../config/database.php";
require "../includes/admin_auth.php";

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {

    $post_id = $_POST["post_id"];
    $status = $_POST["status"];

    
    if ( $status == "Active" ) {
        
    } else if ( $status == "InActive" ) {
        
    } else {
        $_SESSION["post_error"] = "Invalid post status.";
        header( "Location: ../admin/posts.php" );
        exit;
    }

    
    $sql = "UPDATE post
    SET post_status = '$status'
    WHERE post_id = $post_id";

    $result = mysqli_query( $conn, $sql );

    
    if ( $result == true ) {
        $_SESSION["post_success"] = "Post status updated successfully.";
    } else {
        $_SESSION["post_error"] = "Post status could not be updated.";
    }

    header( "Location: ../admin/posts.php" );
    exit;
}

header( "Location: ../admin/posts.php" );
exit;

