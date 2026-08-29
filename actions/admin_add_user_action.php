<?php

require "../includes/admin_auth.php";
require "../config/database.php";
require "../includes/send_email.php";
require "../libraries/FPDF/fpdf.php";

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {

    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $gender = $_POST["gender"];
    $date_of_birth = $_POST["date_of_birth"];
    $address = $_POST["home_town"];
    $status_input = $_POST["account_status"];

    $initial_password = "User1234";
    $stored_password = $initial_password;

    if ( $first_name == "" || $last_name == "" || $email == "" || $gender == "" || $date_of_birth == "" || $address == "" ) {
        $_SESSION["user_error"] = "All required fields must be completed to add a user.";
        header( "Location: ../admin/users.php" );
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

    $check_sql = "SELECT user_id FROM user WHERE email = '$clean_email'";
    $check_res = mysqli_query( $conn, $check_sql );
    if ( $check_res && mysqli_num_rows( $check_res ) > 0 ) {
        $_SESSION["user_error"] = "An account already exists with this email.";
        header( "Location: ../admin/users.php" );
        exit;
    }

    $db_status = "Active";
    if ( $status_input == "inactive" || $status_input == "Inactive" || $status_input == "InActive" || $status_input == "INACTIVE" ) {
        $db_status = "InActive";
    }

    $image_path = "assets/images/users/user_profile.png";

    if ( isset( $_FILES["image"] ) && $_FILES["image"]["error"] == 0 ) {
        if ( $_FILES["image"]["size"] <= 1048576 ) {
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

            $clean_ext = "";
            for ( $i = 0; $i < $ext_length; $i++ ) {
                $char = $image_extension[$i];
                if ( $char >= 'A' && $char <= 'Z' ) {
                    $upper_letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                    $lower_letters = "abcdefghijklmnopqrstuvwxyz";
                    $letter_position = 0;
                    while ( isset( $upper_letters[$letter_position] ) ) {
                        if ( $upper_letters[$letter_position] == $char ) {
                            $clean_ext .= $lower_letters[$letter_position];
                            break;
                        }
                        $letter_position++;
                    }
                } else {
                    $clean_ext .= $char;
                }
            }

            if ( $clean_ext == "jpg" || $clean_ext == "jpeg" || $clean_ext == "png" || $clean_ext == "webp" ) {
                $image_name = time() . "." . $clean_ext;
                $image_path = "assets/images/users/" . $image_name;
                $upload_path = "../" . $image_path;
                move_uploaded_file( $_FILES["image"]["tmp_name"], $upload_path );
            }
        }
    }


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
        2,
        '$clean_fn',
        '$clean_ln',
        '$clean_email',
        '$stored_password',
        '$gender',
        '$date_of_birth',
        '$image_path',
        '$clean_addr',
        'Approved',
        '$db_status'
    )";

    $result = mysqli_query( $conn, $sql );

    if ( $result == true ) {
        $new_user_id = mysqli_insert_id( $conn );
        $pdf_name = "user_" . $new_user_id . "_credentials.pdf";
        $pdf_path = __DIR__ . "/../credentials/" . $pdf_name;

        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont( "Arial", "B", 16 );
        $pdf->Cell( 0, 10, "Tales Account Credentials", 0, 1 );
        $pdf->SetFont( "Arial", "", 12 );
        $pdf->Cell( 0, 8, "Name: " . $first_name . " " . $last_name, 0, 1 );
        $pdf->Cell( 0, 8, "Email: " . $email, 0, 1 );
        $pdf->Cell( 0, 8, "Initial Password: " . $initial_password, 0, 1 );
        $pdf->Cell( 0, 8, "Status: Approved (" . $db_status . ")", 0, 1 );
        $pdf->Output( "F", $pdf_path );

        $user_msg = "Admin created your account.\nEmail: $email\nPassword: $initial_password";
        send_email( $email, "Tales Account Created", $user_msg, $pdf_path );

        $_SESSION["user_success"] = "User added successfully. Initial password: $initial_password. Credentials PDF generated.";
    } else {
        $_SESSION["user_error"] = "User account could not be created.";
    }

    header( "Location: ../admin/users.php" );
    exit;
}

header( "Location: ../admin/users.php" );
exit;






