<?php
require __DIR__ . "/../config/db.php";
require __DIR__ . "/../includes/functions.php";
if (session_status() === PHP_SESSION_NONE) session_start();

$id = (int)($_GET['id'] ?? 0);
$sql = "SELECT b.*, u.username FROM blogs b JOIN users u ON u.id=b.user_id WHERE b.id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute(); 
$post = $stmt->get_result()->fetch_assoc();

if (
  !$post || 
  ($post['status']!=='published' 
   && ($_SESSION['user_id']??null)!==$post['user_id'] 
   && ($_SESSION['role']??'')!=='admin')
) {
  http_response_code(404); 
  die("Post not found.");
}

// SEO meta info
$PAGE_TITLE = $post['title'];
$PAGE_DESC  = substr(strip_tags($post['content']), 0, 150);

include __DIR__ . "/../includes/header.php"; 
?>

<article class="bg-white p-6 rounded-xl shadow">
  <div class="flex items-center justify-between mb-2">
    <div class="flex items-center gap-2">
      <span class="text-xs px-2 py-1 bg-gray-100 rounded"><?= e($post['category'] ?: 'General') ?></span>
      <span class="text-xs text-gray-500"><?= date("M d, Y", strtotime($post['created_at'])) ?></span>
    </div>

    <?php if (!empty($_SESSION['user_id']) && 
              ($_SESSION['user_id']===$post['user_id'] || ($_SESSION['role']??'')==='admin')): ?>
      <div class="flex items-center gap-2">
        <a class="px-3 py-1 rounded border" href="/ai_blog/blog/edit.php?id=<?= (int)$post['id'] ?>">Edit</a>
        <form method="post" action="/ai_blog/blog/delete.php" onsubmit="return confirm('Delete this post?');">
          <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
          <input type="hidden" name="id" value="<?= (int)$post['id'] ?>">
          <button class="px-3 py-1 rounded bg-red-600 text-white">Delete</button>
        </form>
      </div>
    <?php endif; ?>
  </div>

  <h1 class="text-3xl font-bold mb-4"><?= e($post['title']) ?></h1>
  <div class="prose max-w-none whitespace-pre-wrap"><?= e($post['content']) ?></div>
  <p class="mt-6 text-sm text-gray-500">by <?= e($post['username']) ?></p>

  <?php if (!empty($post['tags'])): ?>
    <p class="mt-4 text-sm text-gray-600">Tags: <?= e($post['tags']) ?></p>
  <?php endif; ?>

  <!-- Social sharing -->
  <div class="mt-6 flex gap-3 text-sm">
    <a href="https://twitter.com/share?url=<?= urlencode('http://localhost/ai_blog/blog/view.php?id='.$post['id']) ?>&text=<?= urlencode($post['title']) ?>" 
       target="_blank" class="text-blue-500 hover:underline">Share on Twitter</a>
    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode('http://localhost/ai_blog/blog/view.php?id='.$post['id']) ?>" 
       target="_blank" class="text-blue-700 hover:underline">Share on Facebook</a>
  </div>
</article>

<?php include __DIR__ . "/../includes/footer.php"; ?>
