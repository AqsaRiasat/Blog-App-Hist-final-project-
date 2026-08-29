<?php

require "../config/database.php";
require "../includes/admin_auth.php";

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {

    $comment_id = $_POST["comment_id"];
    $status = $_POST["status"];

    
    if ( $status == "Active" ) {
        
    } else if ( $status == "InActive" ) {
        
    } else {
        $_SESSION["comment_admin_error"] = "Invalid comment status.";
        header( "Location: ../admin/comments.php" );
        exit;
    }

    
    $sql = "UPDATE post_comment
    SET is_active = '$status'
    WHERE post_comment_id = $comment_id";

    $result = mysqli_query( $conn, $sql );

    if ( $result == true ) {
        $_SESSION["comment_admin_success"] = "Comment status updated successfully.";
    } else {
        $_SESSION["comment_admin_error"] = "Comment status could not be updated.";
    }

    header( "Location: ../admin/comments.php" );
    exit;
}

header( "Location: ../admin/comments.php" );
exit;


