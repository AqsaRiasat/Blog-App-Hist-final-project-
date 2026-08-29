<?php

require "config/database.php";

$sql = "SELECT *
FROM blog
WHERE blog_status = 'Active'
ORDER BY blog_id DESC";

$result = mysqli_query( $conn, $sql );
?><!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Blogs | Tales</title>
        <!-- Theme -->
        <link
          href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap"
          rel="stylesheet" />
        <!-- Scripts -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
        <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet" />
        <!-- Styles -->
        <link href="assets/css/styles.css?v=2.1" rel="stylesheet" />
        <link href="assets/css/blog_theme.php" rel="stylesheet" />
  </head>
    <body data-page="blogs">
        <!-- Navigation -->
        <div data-public-nav></div>

        <main class="blogs-directory-page">

            <!-- Admin -->
            <section class="blogs-directory-section" aria-labelledby="blogs_directory_title">
                <div class="container">
                    <div class="blogs-directory-heading" data-aos="fade-up">
                        <h1 id="blogs_directory_title">Blogs</h1>
                    </div>

                    <div class="blog-directory-list">
                        <?php
                          if ( $result && mysqli_num_rows( $result ) > 0 ) {
                        ?>
                            <?php
                              while ( $row = mysqli_fetch_assoc( $result ) ) {
                            ?>
                                <?php
                                  $current_blog_id = $row["blog_id"];
                                  $genre_count = 0;
                                  $story_count = 0;

                                  $genre_sql = "SELECT COUNT(DISTINCT post_category.category_id) AS genre_count
                                  FROM post_category
                                  INNER JOIN post
                                  ON post_category.post_id = post.post_id
                                  INNER JOIN category
                                  ON post_category.category_id = category.category_id
                                  WHERE post.blog_id = $current_blog_id
                                  AND post.post_status = 'Active'
                                  AND category.category_status = 'Active'";

                                  $genre_result = mysqli_query( $conn, $genre_sql );

                                  if ( $genre_result != false ) {
                                      $genre_row = mysqli_fetch_assoc( $genre_result );
                                      if ( $genre_row != false ) {
                                          $genre_count = $genre_row["genre_count"];
                                      }
                                  }

                                  $story_sql = "SELECT COUNT(*) AS story_count
                                  FROM post
                                  WHERE blog_id = $current_blog_id
                                  AND post_status = 'Active'";

                                  $story_result = mysqli_query( $conn, $story_sql );

                                  if ( $story_result != false ) {
                                      $story_row = mysqli_fetch_assoc( $story_result );
                                      if ( $story_row != false ) {
                                          $story_count = $story_row["story_count"];
                                      }
                                  }
                                ?>                                <article class="blog-directory-row" data-aos="fade-up" style="display: flex !important; flex-direction: row !important; align-items: center !important; justify-content: space-between !important; width: 100% !important; padding: 1.5rem 0 !important; border-bottom: 1px solid rgba(18, 18, 18, 0.15) !important;">
                                    <div class="blog-directory-left" style="display: flex !important; flex-direction: row !important; align-items: center !important; gap: 1.5rem !important; flex: 1 1 0 !important; min-width: 0 !important;">
                                        <span class="blog-sequence" style="color: #121212 !important; font-weight: 800 !important; font-size: 1.35rem !important; opacity: 0.95 !important; flex-shrink: 0 !important;">
                                            <?php
                                            if ( $row["blog_id"] < 10 ) {
                                                echo "0" . $row["blog_id"];
                                            } else {
                                                echo $row["blog_id"];
                                            }
                                            ?>
                                        </span>
                                        <a
                                            class="blog-directory-cover"
                                            href="categories.php?blog_id=<?php
                                              echo $row["blog_id"];
                                            ?>"
                                            aria-label="View blog categories"
                                            style="display: flex !important; align-items: center !important; justify-content: center !important; width: 65px !important; height: 65px !important; flex-shrink: 0 !important; background: transparent !important; border: none !important; box-shadow: none !important;"
                                        >
                                            <img
                                                src="<?php
                                                    $blog_cover = !empty($row["blog_background_image"]) ? $row["blog_background_image"] : "assets/images/hero/child_reading_hero.jpg";
                                                    $blog_cover = ltrim($blog_cover, '/');
                                                    $blog_cover = str_replace('Online_Blogging_Application/', '', $blog_cover);
                                                    echo htmlspecialchars($blog_cover);
                                                ?>"
                                                alt="<?php
                                                   echo htmlspecialchars($row["blog_title"]);
                                                 ?>"
                                                style="width: 100% !important; height: 100% !important; object-fit: contain !important; background: transparent !important; border-radius: 0 !important;"
                                            />
                                        </a>
                                        <div class="blog-directory-identity" style="margin: 0 !important; white-space: nowrap !important;">
                                            <h3 style="margin: 0 !important; font-size: 1.25rem !important; font-weight: 800 !important;">
                                                <a href="categories.php?blog_id=<?php
                                                  echo $row["blog_id"];
                                                ?>" style="color: #121212 !important; text-decoration: none !important;">
                                                    <?php
                                                      echo $row["blog_title"];
                                                    ?>
                                                </a>
                                            </h3>
                                        </div>
                                    </div>

                                    <div class="blog-directory-meta" style="flex: 1 1 0 !important; text-align: center !important; display: flex !important; justify-content: center !important; align-items: center !important;">
                                        <span style="color: #2b2207 !important; font-weight: 700 !important; font-size: 1rem !important; white-space: nowrap !important; text-align: center !important;"><?php echo $genre_count; ?> Genres &nbsp;&bull;&nbsp; <?php echo $story_count; ?> Stories</span>
                                    </div>

                                    <div class="blog-directory-action" style="flex: 1 1 0 !important; display: flex !important; justify-content: flex-end !important; align-items: center !important;">
                                        <a
                                            class="blog-directory-link"
                                            href="categories.php?blog_id=<?php
                                              echo $row["blog_id"];
                                            ?>"
                                            style="background: #1c1c1c !important; color: #f3ba40 !important; border-radius: 999px !important; font-weight: 700 !important; font-size: 0.92rem !important; padding: 0.6rem 1.5rem !important; text-decoration: none !important; display: inline-flex !important; align-items: center !important; gap: 0.5rem !important; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2) !important; white-space: nowrap !important;"
                                        >
                                            View blog <i class="bi bi-arrow-right" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </article>
                            <?php
                              }
                            ?>
                        <?php
                          } else {
                        ?>
                            <div class="surface p-4 text-center">
                                <p class="mb-0">No active blogs found.</p>
                            </div>
                        <?php
                          }
                        ?>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <div data-footer></div>
        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/components.js?v=20260827-theme-toggle-v3" defer></script>
        <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
        <script src="assets/js/app.js?v=20260827-theme-toggle-v3" defer></script>
    </body>
</html>



