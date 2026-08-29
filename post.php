<?php

/* Setup */
require "config/database.php";
require "includes/session.php";

/* Posts */
$post_id = 0;

if ( isset( $_GET["post_id"] ) ) {
    $post_value = $_GET["post_id"];
    $post_id_is_valid = true;
    $position = 0;

    if ( !isset( $post_value[0] ) ) {
        $post_id_is_valid = false;
    }

    while ( isset( $post_value[$position] ) ) {
        if (
            $post_value[$position] < "0" ||
            $post_value[$position] > "9"
        ) {
            $post_id_is_valid = false;
        }

        $position++;
    }

    if ( $post_id_is_valid == true ) {
        $post_id = $post_value;
    }
}

/* Recent posts */
if ( $post_id <= 0 ) {
    $latest_post_sql = "SELECT post_id
    FROM post
    WHERE post_status = 'Active'
    ORDER BY post_id DESC
    LIMIT 1";

    $latest_post_result = mysqli_query(
        $conn,
        $latest_post_sql
    );

    if ( $latest_post_result != false ) {
        $latest_post = mysqli_fetch_assoc(
            $latest_post_result
        );

        if ( $latest_post != false ) {
            $post_id = $latest_post["post_id"];
        }
    }
}

/* Posts */
$post_sql = "SELECT
    post.*,
    blog.blog_title,
    category.category_id,
    category.category_title,
    user.first_name,
    user.last_name,
    user.user_image
FROM post
LEFT JOIN blog
    ON post.blog_id = blog.blog_id
LEFT JOIN post_category
    ON post.post_id = post_category.post_id
LEFT JOIN category
    ON post_category.category_id = category.category_id
LEFT JOIN user
    ON blog.user_id = user.user_id
WHERE post.post_id = $post_id
AND post.post_status = 'Active'
LIMIT 1";

$post_result = mysqli_query(
    $conn,
    $post_sql
);

$post = false;

if ( $post_result != false ) {
    $post = mysqli_fetch_assoc(
        $post_result
    );
}


$post_date = "";

if ( $post != false ) {
    if (
        !isset( $post["category_title"] ) ||
        $post["category_title"] == ""
    ) {
        $post["category_title"] = "General";
    }

    if (
        !isset( $post["first_name"] ) ||
        $post["first_name"] == ""
    ) {
        $post["first_name"] = "Admin";
    }

    if ( !isset( $post["last_name"] ) ) {
        $post["last_name"] = "";
    }

    $raw_date = $post["created_at"];
    $year = "";
    $month = "";
    $day = "";
    $date_position = 0;

    while (
        isset( $raw_date[$date_position] ) &&
        $raw_date[$date_position] != "-"
    ) {
        $year .= $raw_date[$date_position];
        $date_position++;
    }

    $date_position++;

    while (
        isset( $raw_date[$date_position] ) &&
        $raw_date[$date_position] != "-"
    ) {
        $month .= $raw_date[$date_position];
        $date_position++;
    }

    $date_position++;

    while (
        isset( $raw_date[$date_position] ) &&
        $raw_date[$date_position] != " "
    ) {
        $day .= $raw_date[$date_position];
        $date_position++;
    }

    $month_name = "January";

    if ( $month == "02" ) {
        $month_name = "February";
    } else if ( $month == "03" ) {
        $month_name = "March";
    } else if ( $month == "04" ) {
        $month_name = "April";
    } else if ( $month == "05" ) {
        $month_name = "May";
    } else if ( $month == "06" ) {
        $month_name = "June";
    } else if ( $month == "07" ) {
        $month_name = "July";
    } else if ( $month == "08" ) {
        $month_name = "August";
    } else if ( $month == "09" ) {
        $month_name = "September";
    } else if ( $month == "10" ) {
        $month_name = "October";
    } else if ( $month == "11" ) {
        $month_name = "November";
    } else if ( $month == "12" ) {
        $month_name = "December";
    }

    $post_date = $month_name . " " . $day . ", " . $year;
}

/* Profile */
$is_post_blog_following = false;

if (
    $post != false &&
    isset( $_SESSION["user_id"] )
) {
    $user_id = $_SESSION["user_id"];
    $blog_id = $post["blog_id"];

    $follow_sql = "SELECT status
    FROM following_blog
    WHERE follower_id = $user_id
    AND blog_following_id = $blog_id
    AND status = 'Followed'";

    $follow_result = mysqli_query(
        $conn,
        $follow_sql
    );

    if ( $follow_result != false ) {
        $follow_row = mysqli_fetch_assoc(
            $follow_result
        );

        if ( $follow_row != false ) {
            $is_post_blog_following = true;
        }
    }
}

/* Buttons */
$follow_button_class = "btn-primary";
$follow_button_text = "Follow";

if ( $is_post_blog_following == true ) {
    $follow_button_class = "btn-secondary";
    $follow_button_text = "Unfollow";
}

/* Comments */
$comment_result = false;
$comments = array();
$comment_count = 0;

if ( $post != false ) {
    $comment_sql = "SELECT
        post_comment.*,
        user.first_name,
        user.last_name,
        user.user_image
    FROM post_comment
    INNER JOIN user
        ON post_comment.user_id = user.user_id
    WHERE post_comment.post_id = $post_id
    AND post_comment.is_active = 'Active'
    ORDER BY post_comment.created_at DESC";

    $comment_result = mysqli_query(
        $conn,
        $comment_sql
    );

    if ( $comment_result != false ) {
        $comment_row = mysqli_fetch_assoc(
            $comment_result
        );

        while ( $comment_row != false ) {
            $comments[$comment_count] = $comment_row;
            $comment_count++;

            $comment_row = mysqli_fetch_assoc(
                $comment_result
            );
        }
    }
}

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>
      <?php
        if ( $post != false ) {
            echo $post["post_title"];
        } else {
            echo "Post";
        }
      ?>
      | Tales
    </title>
    <!-- Theme -->
    <link
      href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap"
      rel="stylesheet"
    />
    <!-- Scripts -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
      rel="stylesheet"
    />
    <!-- Animation -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet" />
    <!-- Styles -->
    <link href="assets/css/styles.css?v=2.1" rel="stylesheet" />
    <link href="assets/css/blog_theme.php?blog_id=<?php if ( $post != false ) { echo $post["blog_id"]; } ?>" rel="stylesheet" />
  </head>
  <body data-page="post-detail">
    <?php
      require "includes/popup_message.php";
    ?>

    <!-- Navigation -->
    <div data-public-nav></div>

    <!-- Content -->
    <main>
      <?php
        if ( $post != false ) {
      ?>
<!-- Post detail -->
        <div class="single-post-editorial-container">
          <div class="container-fluid max-reading-width">
            
            <!-- Header Info -->
            <header class="single-post-header-block">
              <a
                class="single-story-category"
                href="categories.php?blog_id=<?php
                  echo $post["blog_id"];
                ?>&category_id=<?php
                  echo $post["category_id"];
                ?>#category-posts">
                <?php
                  echo $post["category_title"];
                ?>
              </a>

              <h1 class="cinematic-post-title mt-3 mb-3">
                <?php
                  echo $post["post_title"];
                ?>
              </h1>

              <!-- Authentication -->
              <div class="single-story-unified-meta mb-3">
                <div class="author-info d-flex align-items-center gap-2">
                  <img
                    class="avatar avatar-sm"
                    src="<?php
                      if (
                          isset( $post["user_image"] ) &&
                          $post["user_image"] != ""
                      ) {
                          echo $post["user_image"];
                      } else {
                          echo "assets/images/admin/admin_profile.jpg";
                      }
                    ?>"
                    alt="Author" />
                  <span>
                    By
                    <strong class="text-white">
                      <?php
                        echo $post["first_name"] . " " . $post["last_name"];
                      ?>
                    </strong>
                  </span>
                </div>
                <span class="meta-dot">&bull;</span>
                <span>
                  <i class="bi bi-calendar3 me-1"></i>
                  <?php
                    echo $post_date;
                  ?>
                </span>
              </div>

              <?php
                if (
                    isset( $post["post_summary"] ) &&
                    $post["post_summary"] != ""
                ) {
              ?>
                <p class="single-post-summary mb-4">
                  <?php
                    echo $post["post_summary"];
                  ?>
                </p>
              <?php
                }
              ?>
            </header>

            <!-- Posts -->
            <figure class="single-post-article-image">
    <img
      src="<?php
        echo !empty($post["landscape_image"]) ? $post["landscape_image"] : $post["featured_image"];
      ?>?v=20260728-garden"
      alt="<?php
        echo $post["post_title"];
      ?>" />
  </figure>

            <!-- Scripts -->
            <article class="single-post-magazine-article">
              <div class="two-column-article-body">
                <?php
                  $description = $post["post_description"];
                  $left_content = "";
                  $right_content = "";
                  $closing_content = "";
                  $description_index = 0;
                  $paragraph_number = 1;

                  while ( isset( $description[$description_index] ) ) {
                      if (
                          $description[$description_index] == "\n" &&
                          isset( $description[$description_index + 1] ) &&
                          $description[$description_index + 1] == "\n"
                      ) {
                          $paragraph_number++;

                          if ( $paragraph_number == 3 || $paragraph_number == 5 ) {
                              $description_index++;
                          } else if ( $paragraph_number < 3 ) {
                              $left_content .= "<br /><br />";
                              $description_index++;
                          } else if ( $paragraph_number < 5 ) {
                              $right_content .= "<br /><br />";
                              $description_index++;
                          } else {
                              $closing_content .= "<br /><br />";
                              $description_index++;
                          }
                      } else {
                          if ( $paragraph_number < 3 ) {
                              $left_content .= $description[$description_index];
                          } else if ( $paragraph_number < 5 ) {
                              $right_content .= $description[$description_index];
                          } else {
                              $closing_content .= $description[$description_index];
                          }
                      }

                      $description_index++;
                  }
                ?>

                <p class="article-paragraph article-column">
                  <?php
                    echo $left_content;
                  ?>
                </p>

                <p class="article-paragraph article-column">
                  <?php
                    echo $right_content;
                  ?>
                </p>
              </div>

              <?php
                if ( $closing_content != "" ) {
              ?>
                <p class="article-paragraph article-closing-note">
                  <?php
                    echo $closing_content;
                  ?>
                </p>
              <?php
                }
              ?>
              <!-- Follow blog -->
              <div class="story-actions-bar follow-only-actions d-flex justify-content-start align-items-center mt-4 mb-3">
                <form action="actions/follow_action.php" method="post" class="d-flex align-items-center m-0 p-0">
                  <input
                    type="hidden"
                    name="blog_id"
                    value="<?php
                      echo $post["blog_id"];
                    ?>" />
                  <input
                    type="hidden"
                    name="post_id"
                    value="<?php
                      echo $post_id;
                    ?>" />
                  <button
                    class="btn <?php
                      echo $follow_button_class;
                    ?> btn-sm d-inline-flex align-items-center gap-1"
                    type="submit">
                    <i class="bi bi-bell fs-6"></i>
                    <span>
                      <?php
                        echo $follow_button_text;
                      ?>
                    </span>
                  </button>
                </form>
              </div>



            </article>

            <!-- Comments -->
            <section class="single-post-comments-section mt-3">
              <div class="comments-container">
                <h2 class="h5 text-white mb-3">Comments</h2>

                <!-- Comments -->
                <div class="comments-list">
                  <?php
                    if ( $comment_count > 0 ) {
                      $comment_position = 0;
                  ?>
                    <?php
                      while ( isset( $comments[$comment_position] ) ) {
                          $comment = $comments[$comment_position];
                          $comment_raw_date = $comment["created_at"];
                          $comment_year = "";
                          $comment_month = "";
                          $comment_day = "";
                          $comment_date_position = 0;

                          while (
                              isset( $comment_raw_date[$comment_date_position] ) &&
                              $comment_raw_date[$comment_date_position] != "-"
                          ) {
                              $comment_year .= $comment_raw_date[$comment_date_position];
                              $comment_date_position++;
                          }

                          $comment_date_position++;

                          while (
                              isset( $comment_raw_date[$comment_date_position] ) &&
                              $comment_raw_date[$comment_date_position] != "-"
                          ) {
                              $comment_month .= $comment_raw_date[$comment_date_position];
                              $comment_date_position++;
                          }

                          $comment_date_position++;

                          while (
                              isset( $comment_raw_date[$comment_date_position] ) &&
                              $comment_raw_date[$comment_date_position] != " "
                          ) {
                              $comment_day .= $comment_raw_date[$comment_date_position];
                              $comment_date_position++;
                          }

                          $comment_month_name = "January";

                          if ( $comment_month == "02" ) {
                              $comment_month_name = "February";
                          } else if ( $comment_month == "03" ) {
                              $comment_month_name = "March";
                          } else if ( $comment_month == "04" ) {
                              $comment_month_name = "April";
                          } else if ( $comment_month == "05" ) {
                              $comment_month_name = "May";
                          } else if ( $comment_month == "06" ) {
                              $comment_month_name = "June";
                          } else if ( $comment_month == "07" ) {
                              $comment_month_name = "July";
                          } else if ( $comment_month == "08" ) {
                              $comment_month_name = "August";
                          } else if ( $comment_month == "09" ) {
                              $comment_month_name = "September";
                          } else if ( $comment_month == "10" ) {
                              $comment_month_name = "October";
                          } else if ( $comment_month == "11" ) {
                              $comment_month_name = "November";
                          } else if ( $comment_month == "12" ) {
                              $comment_month_name = "December";
                          }

                          $comment_date = $comment_month_name . " " . $comment_day . ", " . $comment_year;
                          $comment_position++;
                    ?>
                      <div class="comment-item d-flex gap-3 mb-3">
                        <img
                          class="avatar avatar-sm flex-shrink-0"
                          src="<?php
                            if (
                                isset( $comment["user_image"] ) &&
                                $comment["user_image"] != ""
                            ) {
                                echo $comment["user_image"];
                            } else {
                                echo "assets/images/admin/admin_profile.jpg";
                            }
                          ?>"
                          alt="User" />
                        <div>
                          <div class="d-flex align-items-center gap-2 mb-1">
                            <strong class="text-white small">
                              <?php
                                echo $comment["first_name"] . " " . $comment["last_name"];
                              ?>
                            </strong>
                            <small class="comment-date">
                              &bull;
                              <?php
                                echo $comment_date;
                              ?>
                            </small>
                          </div>
                          <p class="mb-0 comment-copy">
                            <?php
                              echo $comment["comment"];
                            ?>
                          </p>
                        </div>
                      </div>
                    <?php
                      }
                    ?>
                  <?php
                    } else {
                  ?>
                    <p class="comment-empty-state mb-3">No approved comments yet. Be the first to share your thoughts!</p>
                  <?php
                    }
                  ?>
                </div>

                <!-- Comments -->
                <?php
                  if ( $post["is_comment_allowed"] == 1 ) {
                ?>
                  <div class="comment-form-wrapper mt-3">

                    <form action="actions/comment_action.php" method="post" class="needs-validation" novalidate>
                      <input
                        type="hidden"
                        name="post_id"
                        value="<?php
                          echo $post_id;
                        ?>" />
                      <div class="mb-2">
                        <textarea
                          id="comment_text"
                          name="comment_text"
                          class="form-control form-control-sm"
                          rows="3" required placeholder="Share your thoughts..."></textarea>
                        <div class="invalid-feedback">Please enter your comment.</div>
                      </div>
                      <button class="btn btn-primary btn-sm mt-3" type="submit">Submit comment</button>
                    </form>
                  </div>
                <?php
                  }
                ?>
              </div>
            </section>

          </div>
        </div>

      <?php
        } else {
      ?>

        <section class="section">
          <div class="container text-center">
            <span class="eyebrow">Post</span>
            <h1>Post not found</h1>
            <p class="text-muted">The requested post is not available.</p>
            <a class="btn btn-primary" href="index.php#market-news">View all posts</a>
          </div>
        </section>

      <?php
        }
      ?>
    </main>

    <!-- Footer -->
    <div data-footer></div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Layout -->
    <script src="assets/js/components.js?v=20260730-tales-brand"></script>
    <!-- Page data and behavior -->
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <!-- Shared application behavior -->
    <script src="assets/js/app.js?v=20260730-children-content"></script>
  </body>
</html>







