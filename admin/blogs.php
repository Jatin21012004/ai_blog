<?php
require __DIR__ . "/../includes/admin_check.php";
require __DIR__ . "/../config/db.php";
require __DIR__ . "/../includes/functions.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check($_POST['csrf'] ?? '')) {
    $id = (int)$_POST['id'];
    $action = $_POST['action'];

    if ($action === 'publish') {
        $stmt = $conn->prepare("UPDATE blogs SET status='published' WHERE id=?");
        $stmt->bind_param("i",$id);
        $stmt->execute();
    } elseif ($action === 'draft') {
        $stmt = $conn->prepare("UPDATE blogs SET status='draft' WHERE id=?");
        $stmt->bind_param("i",$id);
        $stmt->execute();
    } elseif ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM blogs WHERE id=?");
        $stmt->bind_param("i",$id);
        $stmt->execute();
    }
}

$blogs = $conn->query("SELECT b.*, u.username FROM blogs b JOIN users u ON u.id=b.user_id ORDER BY b.created_at DESC");
$PAGE_TITLE = "Manage Blogs";
include __DIR__ . "/../includes/header.php"; ?>

<h1 class="text-2xl font-bold mb-6">Manage Blogs</h1>

<table class="w-full bg-white rounded-xl shadow">
  <thead>
    <tr class="border-b bg-gray-50">
      <th class="p-2 text-left">ID</th>
      <th class="p-2 text-left">Title</th>
      <th class="p-2 text-left">Author</th>
      <th class="p-2 text-left">Status</th>
      <th class="p-2 text-left">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php while($b = $blogs->fetch_assoc()): ?>
    <tr class="border-b">
      <td class="p-2"><?= (int)$b['id'] ?></td>
      <td class="p-2"><?= e($b['title']) ?></td>
      <td class="p-2"><?= e($b['username']) ?></td>
      <td class="p-2"><?= e($b['status']) ?></td>
      <td class="p-2">
        <form method="post" class="inline">
          <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
          <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">
          <?php if ($b['status'] === 'draft'): ?>
            <button name="action" value="publish" class="px-2 py-1 bg-green-600 text-white rounded">Publish</button>
          <?php else: ?>
            <button name="action" value="draft" class="px-2 py-1 bg-yellow-600 text-white rounded">Unpublish</button>
          <?php endif; ?>
          <button name="action" value="delete" onclick="return confirm('Delete blog?')" class="px-2 py-1 bg-red-600 text-white rounded">Delete</button>
        </form>
      </td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php include __DIR__ . "/../includes/footer.php"; ?>
