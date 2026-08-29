<?php

require "../includes/admin_auth.php";
require "../config/database.php";

$post_id = 0;

if ( isset( $_GET["post_id"] ) ) {
	$post_id = $_GET["post_id"];
}

$sql = "SELECT post.*, post_category.category_id
FROM post
LEFT JOIN post_category
ON post.post_id = post_category.post_id
WHERE post.post_id = $post_id
LIMIT 1";

$result = mysqli_query(
    $conn,
    $sql
);
$post = mysqli_fetch_assoc(
    $result
);

$category_sql = "SELECT * FROM category ORDER BY category_title ASC";
$category_result = mysqli_query(
    $conn,
    $category_sql
);
?><!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width,initial-scale=1" />
		<title>Update post | Tales</title>
		<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
		<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
		<link href="../assets/css/styles.css?v=20260827-theme-toggle" rel="stylesheet" />
	</head>
	<body data-admin-page="posts">
		<?php
  require "../includes/popup_message.php";
?>
		<div class="admin-shell">
			<div data-admin-sidebar></div>
			<main class="admin-main">
				<div data-admin-topbar></div>
				<span class="eyebrow">Posts</span>
				<h1>Update post</h1>

				<?php
  if ( $post ) {
?>
					<form action="../actions/update_post_action.php" method="post" class="needs-validation" novalidate enctype="multipart/form-data">
						<input type="hidden" name="post_id" value="<?php
  echo $post["post_id"];
?>" />
						<div class="row g-4">
							<div class="col-xl-8">
								<div class="surface p-4">
									<label class="form-label" for="post_title">Post title *</label>
									<input id="post_title" name="title" class="form-control form-control-lg mb-3" value="<?php
  echo $post["post_title"];
?>" required />
									<label class="form-label" for="post_excerpt">Short description *</label>
									<textarea id="post_excerpt" name="short_description" class="form-control mb-3" rows="3" required><?php
  echo $post["post_summary"];
?></textarea>
									<label class="form-label" for="post_content">Post content *</label>
									<textarea id="post_content" name="content" class="form-control" rows="16" required><?php
  echo $post["post_description"];
?></textarea>
								</div>
							</div>
							<aside class="col-xl-4">
								<div class="surface p-4 mb-3">
									<label class="form-label" for="post_category">Category *</label>
									<select id="post_category" name="category_id" class="form-select mb-3" required>
										<option value="">Choose category</option>
										<?php
  while ( $category = mysqli_fetch_assoc(
    $category_result
) ) {
?>
											<option value="<?php
  echo $category["category_id"];
?>"<?php
  if ( $category["category_id"] == $post["category_id"] ) {
    echo " selected";
}
?>><?php
  echo $category["category_title"];
?></option>
										<?php
  }
?>
									</select>
									<img src="../<?php
  echo $post["featured_image"];
?>" alt="Current story image" class="w-100 rounded mb-2" style="max-height: 140px; object-fit: cover;" />
									<label class="form-label" for="post_image">Replace story image</label>
									<input id="post_image" name="image" type="file" accept="image/jpeg,image/png,image/webp" class="form-control mb-3" />
									<label class="form-label" for="publish_date">Publish date</label>
									<input id="publish_date" name="publish_date" type="date" value="<?php
  $date_position = 0; while ( $date_position < 10 && isset( $post["created_at"][$date_position] ) ) { echo $post["created_at"][$date_position]; $date_position++; }
?>" class="form-control mb-3" />
									<div class="form-check form-switch mb-2">
										<input class="form-check-input" id="allow_comments" name="allow_comments" value="1" type="checkbox"<?php
  if ( $post["is_comment_allowed"] == 1 ) {
    echo " checked";
}
?> />
										<label class="form-check-label" for="allow_comments">Allow discussion</label>
									</div>
									<div class="form-check form-switch">
										<input class="form-check-input" id="post_active" name="is_active" value="1" type="checkbox"<?php
  if ( $post["post_status"] == "Active" ) {
    echo " checked";
}
?> />
										<label class="form-check-label" for="post_active">Active post</label>
									</div>
								</div>
								<button class="btn btn-primary w-100" type="submit">Update post</button>
							</aside>
						</div>
					</form>
				<?php
  } else {
?>
					<div class="surface p-4">Post not found.</div>
				<?php
  }
?>
			</main>
		</div>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
		<script src="../assets/js/admin-components.js?v=20260803-add-blog-v2"></script>
		<script src="../assets/js/app.js?v=20260730-children-content"></script>
	</body>
</html>



