<?php

require "config/database.php";

$blog_id = 0;

if ( isset( $_GET["blog_id"] ) ) {
    $blog_id = $_GET["blog_id"];
}

if ( $blog_id == 0 ) {
    $default_blog_sql = "SELECT blog_id
    FROM blog
    WHERE blog_status = 'Active'
    ORDER BY blog_id ASC
    LIMIT 1";

    $default_blog_result = mysqli_query(
        $conn,
        $default_blog_sql
    );

    if ( $default_blog_result != false ) {
        $default_blog = mysqli_fetch_assoc(
            $default_blog_result
        );

        if ( $default_blog != false ) {
            $blog_id = $default_blog["blog_id"];
        }
    }
}
$sql = "SELECT *
FROM blog
WHERE blog_id = $blog_id
AND blog_status = 'Active'";

$result = mysqli_query( $conn, $sql );
$blog = mysqli_fetch_assoc( $result );

$sql = "SELECT DISTINCT category.*
FROM category
INNER JOIN post_category
ON category.category_id = post_category.category_id
INNER JOIN post
ON post_category.post_id = post.post_id
WHERE post.blog_id = $blog_id
AND category.category_status = 'Active'
AND post.post_status = 'Active'
ORDER BY category.category_id ASC";

$result = mysqli_query( $conn, $sql );

$category_id = 0;
$page = 1;
$category = null;
$post_result = false;
$total_posts = 0;
$total_pages = 0;

if ( isset( $_GET["category_id"] ) ) {
    $category_id = $_GET["category_id"];
}

if ( isset( $_GET["page"] ) ) {
    $page = $_GET["page"];
}

if ( $page < 1 ) {
    $page = 1;
}

if ( $blog && $category_id > 0 ) {
    
    $category_sql = "SELECT category.*
    FROM category
    INNER JOIN post_category
    ON category.category_id = post_category.category_id
    INNER JOIN post
    ON post_category.post_id = post.post_id
    WHERE category.category_id = $category_id
    AND post.blog_id = $blog_id
    AND category.category_status = 'Active'
    AND post.post_status = 'Active'
    LIMIT 1";

    $category_result = mysqli_query( $conn, $category_sql );
    $category = mysqli_fetch_assoc( $category_result );

    if ( $category ) {
        $posts_per_page = $blog["post_per_page"];

        if ( $posts_per_page < 1 ) {
            $posts_per_page = 1;
        }

        
        $count_sql = "SELECT COUNT(*) AS total_posts
        FROM post
        INNER JOIN post_category
        ON post.post_id = post_category.post_id
        WHERE post.blog_id = $blog_id
        AND post_category.category_id = $category_id
        AND post.post_status = 'Active'";

        $count_result = mysqli_query( $conn, $count_sql );
        $count_row = mysqli_fetch_assoc( $count_result );
        $total_posts = $count_row["total_posts"];
        $total_pages = 0;
        $remaining_posts = $total_posts;

        while ( $remaining_posts > 0 ) {
            $total_pages = $total_pages + 1;
            $remaining_posts = $remaining_posts - $posts_per_page;
        }

        if ( $page > $total_pages && $total_pages > 0 ) {
            $page = $total_pages;
        }

        $start = ( $page - 1 ) * $posts_per_page;

        
        $post_sql = "SELECT post.*
        FROM post
        INNER JOIN post_category
        ON post.post_id = post_category.post_id
        WHERE post.blog_id = $blog_id
        AND post_category.category_id = $category_id
        AND post.post_status = 'Active'
        ORDER BY post.created_at DESC
        LIMIT $start, $posts_per_page";

        $post_result = mysqli_query( $conn, $post_sql );
    }
}
?><!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Categories | Tales</title>
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
    <link href="assets/css/blog_theme.php?blog_id=<?php echo $blog_id; ?>" rel="stylesheet" />
  </head>
  <body data-page="categories" class="category-posts-page">
    <!-- Navigation -->
    <div data-public-nav></div>
    <!-- Content -->
    <main>
      <!-- Categories -->

      <section class="category-card-section">
        <div class="container">
          <div class="category-card-heading">
            <span class="ref-kicker">CATEGORIES</span>
            <h1>Browse Categories</h1>
          </div>
          <div class="category-page-card-grid">
            <?php
              if ( $blog && $result && mysqli_num_rows( $result ) > 0 ) {
            ?>
              <?php
                while ( $row = mysqli_fetch_assoc( $result ) ) {
              ?>
                <?php
                $icon = "bi-folder";

                if ( $row["category_title"] == "Animal Adventures" ) {
                    $icon = "bi-tree";
                } elseif ( $row["category_title"] == "Bedtime Stories" ) {
                    $icon = "bi-stars";
                } elseif ( $row["category_title"] == "Fairy Tales" ) {
                    $icon = "bi-magic";
                } elseif ( $row["category_title"] == "Magical Worlds" ) {
                    $icon = "bi-stars";
                } elseif ( $row["category_title"] == "Moral Stories" ) {
                    $icon = "bi-heart";
                }
                ?>
                <a
                  class="ref-feature-card reveal"
                  href="categories.php?blog_id=<?php
                    echo $blog_id;
                  ?>&category_id=<?php
                    echo $row["category_id"];
                  ?>#category-posts"
                >
                  <i class="bi <?php
                    echo $icon;
                  ?>"></i>
                  <h3><?php
                    echo $row["category_title"];
                  ?></h3>
                  <p><?php
                    echo $row["category_description"];
                  ?></p>
                  <span class="category-view-link">View Posts &rarr;</span>
                </a>
              <?php
                }
              ?>
            <?php
              } else {
            ?>
              <div class="surface p-4 text-center">
                <p class="mb-0">No active categories found.</p>
              </div>
            <?php
              }
            ?>
          </div>
        </div>
      </section>
      <!-- Categories -->

      <section class="category-results-section" id="category-posts"<?php
        if ( !$category ) {
      ?> hidden<?php
        }
      ?>>
        <div class="container">
          <?php
            if ( $category ) {
          ?>
            <header class="category-results-heading">
              <span class="ref-kicker">CATEGORY POSTS</span>
              <h2 id="category_results_title"><?php
                echo $category["category_title"];
              ?> Posts</h2>
            </header>

            <div class="row g-4 category-post-grid" id="category_posts_grid">
              <?php
                if ( $post_result && mysqli_num_rows( $post_result ) > 0 ) {
              ?>
                <?php
                  while ( $post = mysqli_fetch_assoc( $post_result ) ) {
                ?>
                  <article class="col-md-6 col-lg-4" data-aos="fade-up">
                    <div class="ref-news-card">
                      <img src="<?php
                        echo !empty($post["card_image"]) ? $post["card_image"] : $post["featured_image"];
                      ?>" alt="<?php
                        echo $post["post_title"];
                      ?>" />
                      <div>
                        <span><?php
                          echo $category["category_title"];
                        ?></span>
                        <h2>
                          <a href="post.php?post_id=<?php
                            echo $post["post_id"];
                          ?>">
                            <?php
                              echo $post["post_title"];
                            ?>
                          </a>
                        </h2>
                        <p><?php
                          echo $post["post_summary"];
                        ?></p>
                        <small><?php
                          echo $post["created_at"];
                        ?></small>
                      </div>
                    </div>
                  </article>
                <?php
                  }
                ?>
              <?php
                } else {
              ?>
                <div class="col-12">
                  <div class="surface p-4 text-center">
                    <p class="mb-0">No active posts found.</p>
                  </div>
                </div>
              <?php
                }
              ?>
            </div>

            <?php
              if ( $total_pages > 1 ) {
            ?>
              <nav class="mt-5" aria-label="Category post pages">
                <ul class="pagination">
                  <li class="page-item<?php
                    if ( $page == 1 ) { echo " disabled"; }
                  ?>">
                    <a
                      class="page-link"
                      href="categories.php?blog_id=<?php
                        echo $blog_id;
                      ?>&category_id=<?php
                        echo $category_id;
                      ?>&page=<?php
                        echo $page - 1;
                      ?>#category-posts"
                    >
                      &lsaquo;
                    </a>
                  </li>

                  <?php
                    $page_start = 1;
                    $page_end = $total_pages;

                    if ( $total_pages > 3 ) {
                        if ( $page <= 2 ) {
                            $page_end = 3;
                        } else if ( $page >= $total_pages - 1 ) {
                            $page_start = $total_pages - 2;
                        } else {
                            $page_start = $page - 1;
                            $page_end = $page + 1;
                        }
                    }

                    for ( $number = $page_start; $number <= $page_end; $number++ ) {
                  ?>
                    <li class="page-item<?php
                      if ( $number == $page ) { echo " active"; }
                    ?>">
                      <a
                        class="page-link"
                        href="categories.php?blog_id=<?php
                          echo $blog_id;
                        ?>&category_id=<?php
                          echo $category_id;
                        ?>&page=<?php
                          echo $number;
                        ?>#category-posts"
                      >
                        <?php
                          echo $number;
                        ?>
                      </a>
                    </li>
                  <?php
                    }
                  ?>

                  <li class="page-item<?php
                    if ( $page == $total_pages ) { echo " disabled"; }
                  ?>">
                    <a
                      class="page-link"
                      href="categories.php?blog_id=<?php
                        echo $blog_id;
                      ?>&category_id=<?php
                        echo $category_id;
                      ?>&page=<?php
                        echo $page + 1;
                      ?>#category-posts"
                    >
                      &rsaquo;
                    </a>
                  </li>
                </ul>
              </nav>
            <?php
              }
            ?>
          <?php
            }
          ?>
        </div>
      </section>
    </main>
    <!-- Footer -->
    <div data-footer></div>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Layout -->
    <script src="assets/js/components.js?v=20260730-tales-brand"></script>
    <!-- Page data and behavior -->

    <!-- Page data and behavior -->
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <!-- Shared application behavior -->
    <script src="assets/js/app.js?v=20260730-children-content"></script>
  </body>
</html>


