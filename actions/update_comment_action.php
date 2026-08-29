<?php

require "../includes/admin_auth.php";
require "../config/database.php";

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
    $comment_id = $_POST["comment_id"];
    $comment = $_POST["comment"];
    $status = $_POST["status"];

    $comment_id_is_valid = true;
    $comment_id_position = 0;

    if ( !isset( $comment_id[0] ) ) {
        $comment_id_is_valid = false;
    }

    while ( isset( $comment_id[$comment_id_position] ) ) {
        if ( $comment_id[$comment_id_position] < "0" || $comment_id[$comment_id_position] > "9" ) {
            $comment_id_is_valid = false;
        }
        $comment_id_position++;
    }

    if ( $comment_id_is_valid == false || $comment == "" ) {
        $_SESSION["comment_error"] = "Comment text is required.";
        header( "Location: ../admin/comments.php" );
        exit;
    }

    if ( $status != "Active" && $status != "InActive" ) {
        $status = "InActive";
    }
        $clean_comment = "";
        $comment_position = 0;

        while ( isset( $comment[$comment_position] ) ) {
            $comment_character = $comment[$comment_position];

            if ( $comment_character == "\\" ) {
                $clean_comment .= "\\\\";
            } else if ( $comment_character == "'" ) {
                $clean_comment .= "\\'";
            } else {
                $clean_comment .= $comment_character;
            }

            $comment_position++;
        }

        $comment = $clean_comment;
    $sql = "UPDATE post_comment SET
        comment = '$comment',
        is_active = '$status'
    WHERE post_comment_id = $comment_id";

    if ( mysqli_query( $conn, $sql ) ) {
        $_SESSION["comment_success"] = "Comment updated successfully.";
    } else {
        $_SESSION["comment_error"] = "Comment could not be updated.";
    }
}

header( "Location: ../admin/comments.php" );
exit;



