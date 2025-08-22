<?php
require __DIR__ . "/../includes/admin_check.php";
$PAGE_TITLE = "Admin Dashboard";
include __DIR__ . "/../includes/header.php";
?>
<h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
  <a href="/ai_blog/admin/users.php" class="p-6 rounded-xl shadow bg-white hover:bg-gray-50">
    <h2 class="text-xl font-semibold">👤 Manage Users</h2>
    <p class="text-gray-600 mt-2">View and manage registered users.</p>
  </a>

  <a href="/ai_blog/admin/blogs.php" class="p-6 rounded-xl shadow bg-white hover:bg-gray-50">
    <h2 class="text-xl font-semibold">📝 Manage Blogs</h2>
    <p class="text-gray-600 mt-2">Approve, edit or delete blog posts.</p>
  </a>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>
