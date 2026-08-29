<?php

require "../config/database.php";
require "../includes/session.php";
require "../includes/send_email.php";
require "../libraries/FPDF/fpdf.php";

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {

    $role_id = 2;
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $confirm_email = $_POST["confirm_email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $gender = $_POST["gender"];
    $date_of_birth = $_POST["date_of_birth"];
    $address = $_POST["home_town"];

    
    if ( $first_name == "" || $last_name == "" || $email == "" || $confirm_email == "" || 
         $password == "" || $confirm_password == "" || $gender == "" || $date_of_birth == "" || $address == "" ) {
        
        $_SESSION["register_error"] = "All fields are required.";
        header( "Location: ../register.php" );
        exit;
    }

    
    if ( !isset( $_POST["terms"] ) ) {
        $_SESSION["register_error"] = "Confirm that the information is accurate.";
        header( "Location: ../register.php" );
        exit;
    }

    
    $has_at = false;
    $has_dot = false;
    $email_length = 0;

    while ( isset( $email[$email_length] ) ) {
        if ( $email[$email_length] == "@" ) { $has_at = true; }
        if ( $email[$email_length] == "." ) { $has_dot = true; }
        $email_length++;
    }

    if ( $has_at == false || $has_dot == false ) {
        $_SESSION["register_error"] = "Enter a valid email address.";
        header( "Location: ../register.php" );
        exit;
    }

    
    if ( $email != $confirm_email ) {
        $_SESSION["register_error"] = "Email addresses do not match.";
        header( "Location: ../register.php" );
        exit;
    }

    
    if ( $password != $confirm_password ) {
        $_SESSION["register_error"] = "Passwords do not match.";
        header( "Location: ../register.php" );
        exit;
    }

    
    $pwd_length = 0;
    $has_letter = false;
    $has_number = false;

    while ( isset( $password[$pwd_length] ) ) {
        $ch = $password[$pwd_length];
        if ( ( $ch >= 'a' && $ch <= 'z' ) || ( $ch >= 'A' && $ch <= 'Z' ) ) {
            $has_letter = true;
        }
        if ( $ch >= '0' && $ch <= '9' ) {
            $has_number = true;
        }
        $pwd_length++;
    }

    if ( $pwd_length < 8 || $has_letter == false || $has_number == false ) {
        $_SESSION["register_error"] = "Password must contain at least 8 characters, one letter and one number.";
        header( "Location: ../register.php" );
        exit;
    }

    
    if ( $gender == "Male" || $gender == "Female" ) {
        
    } else {
        $_SESSION["register_error"] = "Select a valid gender.";
        header( "Location: ../register.php" );
        exit;
    }

    
    
    $clean_fn = ""; $fn_len = 0;
    while ( isset( $first_name[$fn_len] ) ) { $fn_len++; }
    for ( $i = 0; $i < $fn_len; $i++ ) {
        if ( $first_name[$i] == "'" ) { $clean_fn .= "\\'"; } else { $clean_fn .= $first_name[$i]; }
    }

    
    $clean_ln = ""; $ln_len = 0;
    while ( isset( $last_name[$ln_len] ) ) { $ln_len++; }
    for ( $i = 0; $i < $ln_len; $i++ ) {
        if ( $last_name[$i] == "'" ) { $clean_ln .= "\\'"; } else { $clean_ln .= $last_name[$i]; }
    }

    
    $clean_email = ""; $em_len = 0;
    while ( isset( $email[$em_len] ) ) { $em_len++; }
    for ( $i = 0; $i < $em_len; $i++ ) {
        if ( $email[$i] == "'" ) { $clean_email .= "\\'"; } else { $clean_email .= $email[$i]; }
    }

    
    $clean_addr = ""; $ad_len = 0;
    while ( isset( $address[$ad_len] ) ) { $ad_len++; }
    for ( $i = 0; $i < $ad_len; $i++ ) {
        if ( $address[$i] == "'" ) { $clean_addr .= "\\'"; } else { $clean_addr .= $address[$i]; }
    }

    
    $sql = "SELECT user_id FROM user WHERE email = '$clean_email'";
    $result = mysqli_query( $conn, $sql );

    $email_exists = false;
    if ( $result != false ) {
        while ( $row = mysqli_fetch_assoc( $result ) ) {
            $email_exists = true;
        }
    }

    if ( $email_exists == true ) {
        $_SESSION["register_error"] = "An account already exists with this email.";
        header( "Location: ../register.php" );
        exit;
    }

    
    if ( !isset( $_FILES["image"] ) || $_FILES["image"]["error"] != 0 ) {
        $_SESSION["register_error"] = "Profile image is required.";
        header( "Location: ../register.php" );
        exit;
    }

    
    if ( $_FILES["image"]["size"] > 1048576 ) {
        $_SESSION["register_error"] = "Profile image must not be larger than 1 MB.";
        header( "Location: ../register.php" );
        exit;
    }

    
    $raw_filename = $_FILES["image"]["name"];
    $file_length = 0;
    while ( isset( $raw_filename[$file_length] ) ) { $file_length++; }

    $image_extension = "";
    for ( $i = $file_length - 1; $i >= 0; $i-- ) {
        if ( $raw_filename[$i] == "." ) { break; }
        $image_extension = $raw_filename[$i] . $image_extension;
    }

    
    $ext_length = 0;
    while ( isset( $image_extension[$ext_length] ) ) { $ext_length++; }

    $clean_extension = "";
    for ( $i = 0; $i < $ext_length; $i++ ) {
        $char = $image_extension[$i];
        if ( $char >= 'A' && $char <= 'Z' ) {
                    $upper_letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                    $lower_letters = "abcdefghijklmnopqrstuvwxyz";
                    $letter_position = 0;
                    while ( isset( $upper_letters[$letter_position] ) ) {
                        if ( $upper_letters[$letter_position] == $char ) {
                            $clean_extension .= $lower_letters[$letter_position];
                            break;
                        }
                        $letter_position++;
                    }
                } else {
                    $clean_extension .= $char;
                }
    }

    
    if ( $clean_extension != "jpg" && $clean_extension != "jpeg" && $clean_extension != "png" && $clean_extension != "webp" ) {
        $_SESSION["register_error"] = "Only JPG, PNG and WebP images are allowed.";
        header( "Location: ../register.php" );
        exit;
    }

    
    $image_name = time() . "." . $clean_extension;
    $image_path = "assets/images/users/" . $image_name;
    $upload_path = "../" . $image_path;

    if ( !move_uploaded_file( $_FILES["image"]["tmp_name"], $upload_path ) ) {
        $_SESSION["register_error"] = "Profile image could not be uploaded.";
        header( "Location: ../register.php" );
        exit;
    }

    $login_password = $password;
    $stored_password = $password;

    
    $sql = "INSERT INTO user (
        role_id,
        first_name,
        last_name,
        email,
        password,
        gender,
        date_of_birth,
        user_image,
        address,
        is_approved,
        is_active
    ) VALUES (
        $role_id,
        '$clean_fn',
        '$clean_ln',
        '$clean_email',
        '$stored_password',
        '$gender',
        '$date_of_birth',
        '$image_path',
        '$clean_addr',
        'Pending',
        'InActive'
    )";

    $result = mysqli_query( $conn, $sql );

    if ( $result == true ) {
        $user_id = mysqli_insert_id( $conn );
        $pdf_name = "user_" . $user_id . "_credentials.pdf";
        $pdf_path = __DIR__ . "/../credentials/" . $pdf_name;

        
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont( "Arial", "B", 18 );
        $pdf->Cell( 0, 12, "Tales Account Credentials", 0, 1 );
        $pdf->SetFont( "Arial", "", 12 );
        $pdf->Cell( 0, 10, "Name: " . $first_name . " " . $last_name, 0, 1 );
        $pdf->Cell( 0, 10, "Email: " . $email, 0, 1 );
        $pdf->Cell( 0, 10, "Password: " . $login_password, 0, 1 );
        $pdf->Cell( 0, 10, "Status: Pending administrator approval", 0, 1 );
        $pdf->Output( "F", $pdf_path );

        
        $user_message =
            "Your Tales account request was created.\n\n" .
            "Email: " . $email . "\n" .
            "Password: " . $login_password . "\n" .
            "Status: Pending administrator approval.";
        send_email( $email, "Tales account registration", $user_message, $pdf_path );

        
        $admin_message =
            "A new account request was submitted.\n\n" .
            "Name: " . $first_name . " " . $last_name . "\n" .
            "Email: " . $email;
        send_email( $smtp_email, "New Tales account request", $admin_message );

        $_SESSION["register_success"] =
            "Account request submitted. Credentials PDF and email were generated. " .
            "Wait for administrator approval.";

        header( "Location: ../login.php" );
        exit;
    }

    $_SESSION["register_error"] = "Account could not be created.";
    header( "Location: ../register.php" );
    exit;

} else {
    header( "Location: ../register.php" );
    exit;
}



