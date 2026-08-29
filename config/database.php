<?php

/* Setup */
$server_name = "sql202.byethost18.com";
$user_name = "b18_42773732";
$password = "Samad345ali";
$database_name = "b18_42773732_24870_aqsa_online_blogging_application";

$conn = mysqli_connect(
    $server_name,
    $user_name,
    $password,
    $database_name
);

if ( $conn == false ) {
    die( "Database connection failed." );
}
