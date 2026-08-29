<?php

require "session.php";

header( "Content-Type: application/json" );
header( "Cache-Control: no-store, no-cache, must-revalidate" );

if (
    isset( $_SESSION["user_id"] ) &&
    ( $_SESSION["role_type"] == "Admin" || $_SESSION["role_type"] == "Administrator" )
) {
    echo '{
        "logged_in": true,
        "is_admin": true
    }';

} elseif ( isset( $_SESSION["user_id"] ) ) {
    echo '{
        "logged_in": true,
        "is_admin": false
    }';

} else {
    echo '{
        "logged_in": false,
        "is_admin": false
    }';
}


