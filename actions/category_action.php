<?php

require "../includes/admin_auth.php";
require "../config/database.php";

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {

    $action = $_POST["action"];

    if ( $action == "add" ) {

        $title = $_POST["title"];
        $description = $_POST["description"];
        $is_active = "InActive";

        if ( isset( $_POST["is_active"] ) ) {
            $is_active = "Active";
        }

        if ( $title == "" ) {
            $_SESSION["category_error"] = "Category title is required.";
            header( "Location: ../admin/categories.php" );
            exit;
        }

        $clean_title = ""; $t_len = 0;
        while ( isset( $title[$t_len] ) ) { $t_len++; }
        for ( $i = 0; $i < $t_len; $i++ ) {
            if ( $title[$i] == "'" ) { $clean_title .= "\\'"; } else { $clean_title .= $title[$i]; }
        }

        $clean_desc = ""; $d_len = 0;
        while ( isset( $description[$d_len] ) ) { $d_len++; }
        for ( $i = 0; $i < $d_len; $i++ ) {
            if ( $description[$i] == "'" ) { $clean_desc .= "\\'"; } else { $clean_desc .= $description[$i]; }
        }

        $sql = "INSERT INTO category (
            category_title,
            category_description,
            category_status
        ) VALUES (
            '$clean_title',
            '$clean_desc',
            '$is_active'
        )";

        $res = mysqli_query( $conn, $sql );

        if ( $res == true ) {
            $_SESSION["category_success"] = "Category added successfully.";
        } else {
            $_SESSION["category_error"] = "Category could not be added.";
        }

    } else if ( $action == "edit" ) {

        $category_id = $_POST["category_id"];
        $title = $_POST["title"];
        $description = $_POST["description"];
        $is_active = "InActive";

        if ( isset( $_POST["is_active"] ) ) {
            $is_active = "Active";
        }

        if ( $category_id <= 0 || $title == "" ) {
            $_SESSION["category_error"] = "Valid category title is required.";
            header( "Location: ../admin/categories.php" );
            exit;
        }

        $clean_title = ""; $t_len = 0;
        while ( isset( $title[$t_len] ) ) { $t_len++; }
        for ( $i = 0; $i < $t_len; $i++ ) {
            if ( $title[$i] == "'" ) { $clean_title .= "\\'"; } else { $clean_title .= $title[$i]; }
        }

        $clean_desc = ""; $d_len = 0;
        while ( isset( $description[$d_len] ) ) { $d_len++; }
        for ( $i = 0; $i < $d_len; $i++ ) {
            if ( $description[$i] == "'" ) { $clean_desc .= "\\'"; } else { $clean_desc .= $description[$i]; }
        }

        $sql = "UPDATE category SET
            category_title = '$clean_title',
            category_description = '$clean_desc',
            category_status = '$is_active'
        WHERE category_id = $category_id";

        $res = mysqli_query( $conn, $sql );

        if ( $res == true ) {
            $_SESSION["category_success"] = "Category updated successfully.";
        } else {
            $_SESSION["category_error"] = "Category could not be updated.";
        }

    } else if ( $action == "toggle_status" ) {

        $category_id = $_POST["category_id"];
        $new_status = $_POST["status"] == "Active" ? "Active" : "InActive";

        $sql = "UPDATE category SET category_status = '$new_status' WHERE category_id = $category_id";
        $res = mysqli_query( $conn, $sql );

        if ( $res == true ) {
            $_SESSION["category_success"] = "Category status updated to $new_status.";
        } else {
            $_SESSION["category_error"] = "Category status could not be updated.";
        }

    } else {
        $_SESSION["category_error"] = "Invalid action.";
    }

    header( "Location: ../admin/categories.php" );
    exit;
}

header( "Location: ../admin/categories.php" );
exit;



