<?php

/* Setup */
require "config/database.php";
require "includes/session.php";

/* Blogs */
$blog_sql = "SELECT *
FROM blog
WHERE blog_status = 'Active'
ORDER BY blog_id ASC
LIMIT 1";

$blog_result = mysqli_query(
    $conn,
    $blog_sql
);

$blog = false;
$blog_id = 0;

if ( $blog_result != false ) {
    $blog = mysqli_fetch_assoc(
        $blog_result
    );
}

if ( $blog != false ) {
    $blog_id = $blog["blog_id"];
}

/* Pagination */
$posts_per_page = 5;

$page = 1;

if ( isset( $_GET["page"] ) ) {
    $page_value = $_GET["page"];
    $page_is_valid = true;
    $page_position = 0;

    if ( !isset( $page_value[0] ) ) {
        $page_is_valid = false;
    }

    while ( isset( $page_value[$page_position] ) ) {
        if (
            $page_value[$page_position] < "0" ||
            $page_value[$page_position] > "9"
        ) {
            $page_is_valid = false;
        }

        $page_position++;
    }

    if ( $page_is_valid == true ) {
        $page = $page_value;
    }
}

if ( $page < 1 ) {
    $page = 1;
}

/* Search */
$search = "";
$clean_search = "";

if ( isset( $_GET["search"] ) ) {
    $search = $_GET["search"];
    $search_position = 0;

    while ( isset( $search[$search_position] ) ) {
        $search_character = $search[$search_position];

        if ( $search_character == "'" ) {
            $clean_search .= "''";
        } else if ( $search_character == "\\" ) {
            $clean_search .= "\\\\";
        } else {
            $clean_search .= $search_character;
        }

        $search_position++;
    }
}

/* Posts */
$conditions = "WHERE post.post_status = 'Active'";

if ( $blog_id > 0 ) {
    $conditions .= " AND post.blog_id = $blog_id";
}

if ( $clean_search != "" ) {
    $conditions .= " AND (
        post.post_title LIKE '%$clean_search%'
        OR category.category_title LIKE '%$clean_search%'
        OR user.first_name LIKE '%$clean_search%'
        OR user.last_name LIKE '%$clean_search%'
        OR DATE_FORMAT(post.created_at, '%M') LIKE '%$clean_search%'
        OR DATE_FORMAT(post.created_at, '%b') LIKE '%$clean_search%'
        OR DATE_FORMAT(post.created_at, '%Y-%m') LIKE '%$clean_search%'
        OR DATE_FORMAT(post.created_at, '%Y-%m-%d') LIKE '%$clean_search%'
        OR DATE_FORMAT(post.created_at, '%d %M %Y') LIKE '%$clean_search%'
        OR DATE_FORMAT(post.created_at, '%M %d, %Y') LIKE '%$clean_search%'
        OR DATE(post.created_at) LIKE '%$clean_search%'
    )";
}

/* Pagination */
$count_sql = "SELECT COUNT(DISTINCT post.post_id) AS total_posts
FROM post
INNER JOIN blog
    ON post.blog_id = blog.blog_id
INNER JOIN user
    ON blog.user_id = user.user_id
LEFT JOIN post_category
    ON post.post_id = post_category.post_id
LEFT JOIN category
    ON post_category.category_id = category.category_id
$conditions";

$count_result = mysqli_query(
    $conn,
    $count_sql
);

$count_row = false;
$total_posts = 0;

if ( $count_result != false ) {
    $count_row = mysqli_fetch_assoc(
        $count_result
    );
}

if (
    $count_row != false &&
    isset( $count_row["total_posts"] )
) {
    $total_posts = $count_row["total_posts"];
}

$total_pages = 0;
$remaining_posts = $total_posts;

while ( $remaining_posts > 0 ) {
    $total_pages++;
    $remaining_posts = $remaining_posts - $posts_per_page;
}

if (
    $page > $total_pages &&
    $total_pages > 0
) {
    $page = $total_pages;
}

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

$start = ( $page - 1 ) * $posts_per_page;

/* Posts */
$post_sql = "SELECT
    post.*,
    category.category_title,
    user.first_name,
    user.last_name
FROM post
INNER JOIN blog
    ON post.blog_id = blog.blog_id
INNER JOIN user
    ON blog.user_id = user.user_id
LEFT JOIN post_category
    ON post.post_id = post_category.post_id
LEFT JOIN category
    ON post_category.category_id = category.category_id
$conditions
GROUP BY post.post_id
ORDER BY CASE post.post_id
    WHEN 17 THEN 1
    WHEN 43 THEN 2
    WHEN 15 THEN 3
    WHEN 36 THEN 4
    WHEN 39 THEN 5
    ELSE 6
END,
post.created_at DESC
LIMIT $start, $posts_per_page";

$post_result = mysqli_query(
    $conn,
    $post_sql
);

$posts = array();
$post_count = 0;

if ( $post_result != false ) {
    $post_row = mysqli_fetch_assoc(
        $post_result
    );

    while ( $post_row != false ) {
        $posts[$post_count] = $post_row;
        $post_count++;

        $post_row = mysqli_fetch_assoc(
            $post_result
        );
    }
}

/* Hero */
$recent_sql = "SELECT
    post_id,
    post_title,
    featured_image,
    created_at
FROM post
WHERE blog_id = $blog_id
AND post_status = 'Active'
ORDER BY created_at DESC
LIMIT 5";

$recent_result = mysqli_query(
    $conn,
    $recent_sql
);

$recent_posts = array();
$recent_count = 0;

if ( $recent_result != false ) {
    $recent_row = mysqli_fetch_assoc(
        $recent_result
    );

    while ( $recent_row != false ) {
        $recent_posts[$recent_count] = $recent_row;
        $recent_count++;

        $recent_row = mysqli_fetch_assoc(
            $recent_result
        );
    }
}

$featured_post_id = 0;
$featured_post_title = "Featured story";

if ( isset( $recent_posts[0] ) ) {
    $featured_post_id = $recent_posts[0]["post_id"];
    $featured_post_title = $recent_posts[0]["post_title"];
}

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Tales | The Storybook Garden</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
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
  <body data-page="home" class="home-reference">
    <?php
      require "includes/popup_message.php";
    ?>

    <!-- Navigation -->
    <div data-public-nav></div>

    <header class="ref-hero">
      <div class="container">
        <div class="ref-hero-copy reveal">
          <span class="ref-kicker">WONDERWOOD TALES</span>
          <h1>Every little dream begins a story</h1>
          <p>
            Gentle adventures, magical friendships, and joyful tales
            for children and families who love to imagine together.
          </p>
          <div class="ref-hero-metrics">
            <div><strong>All</strong><span>Stories</span></div>
            <div><strong>05</strong><span>Recent Stories</span></div>
            <div><strong>By Date</strong><span>Search</span></div>
            <div><strong>Follow</strong><span>Blog</span></div>
          </div>
          <div class="ref-hero-actions">
            <a class="btn btn-primary" href="post.php?post_id=<?php
              echo $featured_post_id;
            ?>">
              Read featured story
            </a>
            <a class="btn ref-outline-btn" href="#market-news">
              <i class="bi bi-journal-text"></i> Explore stories
            </a>
          </div>

        </div>
      </div>
      <div class="ref-hero-art" aria-hidden="true" data-aos="fade-left" data-aos-duration="1400" data-aos-delay="200">
        <img
          src="assets/images/hero/tales_hero_penguin_violin.png?v=20260731"
          alt=""
          width="840"
          height="468"
        />
      </div>
    </header>

    <!-- Content -->
    <main>
      <!-- Recent posts -->
      <section class="ref-section ref-news" id="market-news">
        <div class="container">
          <header class="latest-stories-heading">
            <div>
              <h2 id="posts_section_title">Latest Stories</h2>
            </div>

            <form class="latest-search-form" action="index.php" method="get">
              <label class="visually-hidden" for="story-search">Search stories</label>
              <input
                id="story-search"
                name="search"
                type="search"
                value="<?php
                  echo $search;
                ?>"
                placeholder="Search by title, author, category, month or date"
              />
              <button type="submit" aria-label="Search stories">
                <i class="bi bi-search" aria-hidden="true"></i>
              </button>
            </form>
          </header>

          <!-- Recent posts -->
          <div class="latest-content-grid latest-stacked-layout">
            <div class="latest-posts-column">
              <!-- Posts -->
              <section class="latest-posts-layout" aria-label="All posts">
                <?php
                  if ( $post_count > 0 ) {
                      $post_position = 0;

                      while ( isset( $posts[$post_position] ) ) {
                          $post = $posts[$post_position];
                          $post_position++;
                ?>
                    <article class="story-depth-card">
                      <a href="post.php?post_id=<?php
                        echo $post["post_id"];
                      ?>">
                        <img
                          src="<?php
                            echo !empty($post["card_image"]) ? $post["card_image"] : $post["featured_image"];
                          ?>"
                          alt="<?php
                            echo $post["post_title"];
                          ?>"
                        />

                        <h3 class="story-card-title">
                          <?php
                            echo $post["post_title"];
                          ?>
                        </h3>

                        <div class="story-depth-content">
                          <span class="story-category">
                            <?php
                              echo $post["category_title"];
                            ?>
                          </span>
                          <h3>
                            <?php
                              echo $post["post_title"];
                            ?>
                          </h3>
                          <p>
                            <?php
                              echo $post["post_summary"];
                            ?>
                          </p>
                          <small>
                            <?php
                              echo $post["first_name"] . " " . $post["last_name"];
                            ?>
                            &middot;
                            <?php
                              echo $post["created_at"];
                            ?>
                          </small>
                        </div>
                      </a>
                    </article>
                <?php
                      }
                  } else {
                ?>
                    <div class="surface p-4 text-center latest-posts-empty">
                      <p class="mb-0">No active stories matched your search.</p>
                    </div>
                <?php
                  }
                ?>
              </section>

              <?php
                if ( $total_pages > 1 ) {
                    $previous_page = $page - 1;
                    $next_page = $page + 1;
                    $previous_class = "page-item";
                    $next_class = "page-item";

                    if ( $page == 1 ) {
                        $previous_class .= " disabled";
                    }

                    if ( $page == $total_pages ) {
                        $next_class .= " disabled";
                    }
              ?>
                <nav class="latest-post-pagination" aria-label="Post pages">
                  <ul class="pagination">
                    <li class="<?php
                      echo $previous_class;
                    ?>">
                      <a
                        class="page-link"
                        href="index.php?page=<?php
                          echo $previous_page;
                        ?>&search=<?php
                          echo $search;
                        ?>#market-news">
                        &lsaquo;
                      </a>
                    </li>

                    <?php
                      $number = $page_start;

                      while ( $number <= $page_end ) {
                          $number_class = "page-item";

                          if ( $number == $page ) {
                              $number_class .= " active";
                          }
                    ?>
                      <li class="<?php
                        echo $number_class;
                      ?>">
                        <a
                          class="page-link"
                          href="index.php?page=<?php
                            echo $number;
                          ?>&search=<?php
                            echo $search;
                          ?>#market-news">
                          <?php
                            echo $number;
                          ?>
                        </a>
                      </li>
                    <?php
                          $number++;
                      }
                    ?>

                    <li class="<?php
                      echo $next_class;
                    ?>">
                      <a
                        class="page-link"
                        href="index.php?page=<?php
                          echo $next_page;
                        ?>&search=<?php
                          echo $search;
                        ?>#market-news">
                        &rsaquo;
                      </a>
                    </li>
                  </ul>
                </nav>
              <?php
                }
              ?>


            </div>

            <!-- Categories -->
            <aside class="recent-stories-panel recent-glass-panel category-story-strip" aria-label="Story categories">
              <span class="ref-kicker">FIVE STORY CATEGORIES</span>
              <h3>Browse by Category</h3>

              <div class="recent-stories-list">
                <a href="categories.php?blog_id=1&category_id=1#category-posts">
                  <span class="category-strip-icon"><i class="bi bi-stars"></i></span>
                  <span class="recent-story-text"><strong>Bedtime Stories</strong><small>Soft tales for quiet evenings</small></span>
                </a>
                <a href="categories.php?blog_id=1&category_id=2#category-posts">
                  <span class="category-strip-icon"><i class="bi bi-search"></i></span>
                  <span class="recent-story-text"><strong>Animal Adventures</strong><small>Brave and friendly animals</small></span>
                </a>
                <a href="categories.php?blog_id=1&category_id=3#category-posts">
                  <span class="category-strip-icon"><i class="bi bi-rocket-takeoff"></i></span>
                  <span class="recent-story-text"><strong>Fairy Tales</strong><small>Heroes, castles and dragons</small></span>
                </a>
                <a href="categories.php?blog_id=1&category_id=4#category-posts">
                  <span class="category-strip-icon"><i class="bi bi-moon-stars"></i></span>
                  <span class="recent-story-text"><strong>Magical Worlds</strong><small>Wonder beyond imagination</small></span>
                </a>
                <a href="categories.php?blog_id=1&category_id=5#category-posts">
                  <span class="category-strip-icon"><i class="bi bi-heart"></i></span>
                  <span class="recent-story-text"><strong>Moral Stories</strong><small>Kind lessons with heart</small></span>
                </a>
              </div>
            </aside>
            <!-- Recent posts -->
            <div class="follow-recent-strip latest-recent-strip" aria-label="Recently published stories">
            <div class="follow-recent-intro">
              <span class="ref-kicker">RECENT STORIES</span>
              <h3>Fresh from the Garden</h3>
            </div>
            <div class="follow-recent-items">
              <?php
                $follow_recent_position = 0;

                while ( isset( $recent_posts[$follow_recent_position] ) ) {
                    $follow_recent_post = $recent_posts[$follow_recent_position];
                    $follow_recent_position++;
              ?>
                <a href="post.php?post_id=<?php
                  echo $follow_recent_post["post_id"];
                ?>">
                  <img
                    src="<?php
                      echo !empty($follow_recent_post["card_image"]) ? $follow_recent_post["card_image"] : $follow_recent_post["featured_image"];
                    ?>"
                    alt="<?php
                      echo $follow_recent_post["post_title"];
                    ?>" />
                  <span><?php
                    echo $follow_recent_post["post_title"];
                  ?></span>
                </a>
              <?php
                }
              ?>
            </div>
          </div>
          </div>
        </div>
      </section>

      <!-- Follow blog -->
      <?php
        $is_following = false;

        if (
            isset( $_SESSION["user_id"] ) &&
            $blog_id > 0
        ) {
            $user_id = $_SESSION["user_id"];

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
                    $is_following = true;
                }
            }
        }

        $follow_button_class = "btn-primary";
        $follow_button_text = "Follow Blog";

        if ( $is_following == true ) {
            $follow_button_class = "btn-secondary";
            $follow_button_text = "Unfollow Blog";
        }
      ?>
      <section class="ref-subscribe" id="follow-blog">
        <div class="container">
          <div class="ref-subscribe-inner">
            <div>
              <span class="ref-kicker">FOLLOW BLOG</span>
              <h2>Follow The Storybook Garden</h2>
              <p>
                Discover new bedtime tales, friendly adventures, and magical worlds whenever
                a new story is published.
              </p>
            </div>
            <form action="actions/follow_action.php" method="post">
              <input
                type="hidden"
                name="blog_id"
                value="<?php
                  echo $blog_id;
                ?>" />
              <button
                class="btn <?php
                  echo $follow_button_class;
                ?>"
                type="submit">
                <?php
                  echo $follow_button_text;
                ?>
              </button>
            </form>
          </div>
        </div>
      </section>
    </main>

    <!-- Footer -->
    <div data-footer></div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Layout -->
    <script src="assets/js/components.js?v=20260827-theme-toggle-v3" defer></script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <!-- Shared application behavior -->
    <script src="assets/js/app.js?v=20260827-theme-toggle-v3" defer></script>
  </body>
</html>




