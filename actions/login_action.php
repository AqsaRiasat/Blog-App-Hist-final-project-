<?php

require "../config/database.php";
require "../includes/session.php";

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {

    $email = $_POST["email"];
    $password = $_POST["password"];

    
    if ( $email == "" || $password == "" ) {
        $_SESSION["login_error"] = "Email and password are required.";
        header( "Location: ../login.php" );
        exit;
    }

    
    $clean_email = "";
    $length = 0;
    while ( isset( $email[$length] ) ) {
        $length++;
    }

    for ( $i = 0; $i < $length; $i++ ) {
        if ( $email[$i] == "'" ) {
            $clean_email .= "\\'";
        } else {
            $clean_email .= $email[$i];
        }
    }

    
    $sql = "SELECT user.*, role.role_type
    FROM user
    INNER JOIN role
    ON user.role_id = role.role_id
    WHERE user.email = '$clean_email'";

    $result = mysqli_query( $conn, $sql );

    $user_found = false;
    $row = false;

    if ( $result != false ) {
        while ( $data = mysqli_fetch_assoc( $result ) ) {
            $user_found = true;
            $row = $data;
        }
    }

    
    if ( $user_found == true ) {

        
        if ( $password == $row["password"] ) {

            
            if ( $row["is_approved"] != "Approved" ) {
                $_SESSION["login_error"] = "Your account is not approved.";
                header( "Location: ../login.php" );
                exit;
            }

            
            if ( $row["is_active"] != "Active" ) {
                $_SESSION["login_error"] = "Your account is inactive.";
                header( "Location: ../login.php" );
                exit;
            }

            
            $_SESSION["user_id"] = $row["user_id"];
            $_SESSION["role_id"] = $row["role_id"];
            $_SESSION["role_type"] = $row["role_type"];
            $_SESSION["first_name"] = $row["first_name"];
            $_SESSION["last_name"] = $row["last_name"];
            $_SESSION["email"] = $row["email"];

            
            if ( $row["role_type"] == "Admin" || $row["role_type"] == "Administrator" ) {
                header( "Location: ../admin/dashboard.php" );
            } else {
                header( "Location: ../index.php" );
            }
            exit;

        } else {
            $_SESSION["login_error"] = "Email or password is incorrect.";
        }

    } else {
        $_SESSION["login_error"] = "Email or password is incorrect.";
    }

    header( "Location: ../login.php" );
    exit;

} else {
    header( "Location: ../login.php" );
    exit;
}


