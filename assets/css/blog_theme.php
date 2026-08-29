<?php

require "../../config/database.php";

header( "Content-Type: text/css" );
header( "Cache-Control: no-store, no-cache, must-revalidate" );

$blog_id = 0;

if ( isset( $_GET["blog_id"] ) ) {
    $value = $_GET["blog_id"];
    $valid = true;
    $position = 0;

    if ( !isset( $value[0] ) ) {
        $valid = false;
    }

    while ( isset( $value[$position] ) ) {
        if ( $value[$position] < "0" || $value[$position] > "9" ) {
            $valid = false;
        }
        $position++;
    }

    if ( $valid == true ) {
        $blog_id = $value;
    }
}

if ( $blog_id == 0 ) {
    $blog_sql = "SELECT blog_id FROM blog WHERE blog_status = 'Active' ORDER BY blog_id ASC LIMIT 1";
    $blog_result = mysqli_query( $conn, $blog_sql );
    if ( $blog_result != false ) {
        $blog_row = mysqli_fetch_assoc( $blog_result );
        if ( $blog_row != false ) {
            $blog_id = $blog_row["blog_id"];
        }
    }
}

$title_color = "#ffffff";
$background_color = "#292929";
$font_style = "DM Sans";

$setting_sql = "SELECT setting.setting_key, setting.setting_value
FROM setting
INNER JOIN blog ON setting.user_id = blog.user_id
WHERE blog.blog_id = $blog_id
AND setting.setting_status = 'Active'";

$setting_result = mysqli_query( $conn, $setting_sql );

if ( $setting_result != false ) {
    $setting_row = mysqli_fetch_assoc( $setting_result );

    while ( $setting_row != false ) {
        if ( $setting_row["setting_key"] == "post_title_color" && $setting_row["setting_value"] != "" ) {
            $title_color = $setting_row["setting_value"];
        }

        if ( $setting_row["setting_key"] == "post_background_color" && $setting_row["setting_value"] != "" ) {
            $background_color = $setting_row["setting_value"];
        }

        if ( $setting_row["setting_key"] == "font_style" && $setting_row["setting_value"] != "" ) {
            $font_style = $setting_row["setting_value"];
        }

        $setting_row = mysqli_fetch_assoc( $setting_result );
    }
}
?>
.story-card,
.ref-news-card,
.story-depth-card,
.category-post-grid .ref-news-card {
    background-color: <?php echo $background_color; ?>;
    font-family: "<?php echo $font_style; ?>", sans-serif;
}

.story-card-title,
.story-card-title a,
.story-depth-card .story-card-title,
.story-depth-card .story-card-title a,
.ref-news-card h2,
.ref-news-card h2 a {
    color: <?php echo $title_color; ?>;
    font-family: "<?php echo $font_style; ?>", sans-serif;
}

.single-post-magazine-article,
.single-post-summary,
.single-post-editorial-container {
    font-family: "<?php echo $font_style; ?>", sans-serif;
}


