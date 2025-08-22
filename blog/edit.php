<?php
require __DIR__ . "/../includes/auth_check.php";
require __DIR__ . "/../config/db.php";
require __DIR__ . "/../includes/functions.php";

$id = (int)($_GET['id'] ?? 0);

// Fetch post
$stmt = $conn->prepare("SELECT * FROM blogs WHERE id=?");
$stmt->bind_param("i",$id); $stmt->execute();
$post = $stmt->get_result()->fetch_assoc();
if (!$post) { http_response_code(404); die("Post not found."); }
if ($_SESSION['user_id'] !== (int)$post['user_id'] && ($_SESSION['role']??'')!=='admin') {
  http_response_code(403); die("Forbidden.");
}

$cats = $conn->query("SELECT name FROM categories ORDER BY name");
$err = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!csrf_check($_POST['csrf'] ?? '')) $err = "Invalid CSRF token.";
  else {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $status = ($_POST['status'] ?? 'draft') === 'published' ? 'published' : 'draft';

    if ($title === '' || $content === '') $err = "Title and content are required.";
    else {
      $sql = "UPDATE blogs SET title=?, content=?, category=?, status=? WHERE id=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ssssi", $title, $content, $category, $status, $id);
      if ($stmt->execute()) {
        header("Location: /ai_blog/blog/view.php?id=".$id);
        exit;
      } else $err = "Error: " . $stmt->error;
    }
  }
}

$PAGE_TITLE = "Edit: " . $post['title'];
include __DIR__ . "/../includes/header.php"; ?>
<h1 class="text-2xl font-bold mb-4">Edit Post</h1>
<?php if($err): ?><div class="mb-3 p-3 bg-red-100 text-red-700 rounded"><?= e($err) ?></div><?php endif; ?>

<form method="post">
  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

  <label class="block mb-2 font-semibold">Title</label>
  <div class="flex gap-2 mb-4">
    <input type="text" name="title" class="flex-1 p-2 border rounded" id="titleInput">
    <button type="button" onclick="generateAI('title')" class="px-3 py-2 bg-blue-600 text-white rounded">✨ AI</button>
  </div>

  <label class="block mb-2 font-semibold">Content</label>
  <textarea name="content" id="contentInput" class="w-full p-2 border rounded mb-4"></textarea>

  <label class="block mb-2 font-semibold">Summary</label>
  <div class="flex gap-2 mb-4">
    <textarea name="summary" id="summaryInput" class="flex-1 p-2 border rounded"></textarea>
    <button type="button" onclick="generateAI('summary')" class="px-3 py-2 bg-blue-600 text-white rounded">✨ AI</button>
  </div>

  <label class="block mb-2 font-semibold">SEO Keywords</label>
  <div class="flex gap-2 mb-4">
    <input type="text" name="keywords" id="keywordsInput" class="flex-1 p-2 border rounded">
    <button type="button" onclick="generateAI('keywords')" class="px-3 py-2 bg-blue-600 text-white rounded">✨ AI</button>
  </div>

  <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Save Blog</button>
</form>

<script>
async function generateAI(type) {
  let content = document.getElementById('contentInput').value;
  let endpoint = "/ai_blog/vendor/ai_suggest.php?type=" + type;

  let res = await fetch(endpoint, {
    method: "POST",
    headers: {"Content-Type": "application/json"},
    body: JSON.stringify({ content })
  });
  let text = await res.text();

  if (type === 'title') document.getElementById('titleInput').value = text;
  if (type === 'summary') document.getElementById('summaryInput').value = text;
  if (type === 'keywords') document.getElementById('keywordsInput').value = text;
}
</script>

<?php include __DIR__ . "/../includes/footer.php"; ?>
