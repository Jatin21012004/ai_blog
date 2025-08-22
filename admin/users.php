<?php
require __DIR__ . "/../includes/admin_check.php";
require __DIR__ . "/../config/db.php";
require __DIR__ . "/../includes/functions.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check($_POST['csrf'] ?? '')) {
    $id = (int)$_POST['id'];
    $action = $_POST['action'];

    if ($action === 'make_admin') {
        $stmt = $conn->prepare("UPDATE users SET role='admin' WHERE id=?");
        $stmt->bind_param("i",$id);
        $stmt->execute();
    } elseif ($action === 'remove_admin') {
        $stmt = $conn->prepare("UPDATE users SET role='user' WHERE id=?");
        $stmt->bind_param("i",$id);
        $stmt->execute();
    } elseif ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param("i",$id);
        $stmt->execute();
    }
}

$users = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
$PAGE_TITLE = "Manage Users";
include __DIR__ . "/../includes/header.php"; ?>

<h1 class="text-2xl font-bold mb-6">Manage Users</h1>

<table class="w-full bg-white rounded-xl shadow">
  <thead>
    <tr class="border-b bg-gray-50">
      <th class="p-2 text-left">ID</th>
      <th class="p-2 text-left">Username</th>
      <th class="p-2 text-left">Email</th>
      <th class="p-2 text-left">Role</th>
      <th class="p-2 text-left">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php while($u = $users->fetch_assoc()): ?>
    <tr class="border-b">
      <td class="p-2"><?= (int)$u['id'] ?></td>
      <td class="p-2"><?= e($u['username']) ?></td>
      <td class="p-2"><?= e($u['email']) ?></td>
      <td class="p-2"><?= e($u['role']) ?></td>
      <td class="p-2">
        <form method="post" class="inline">
          <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
          <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
          <?php if ($u['role'] !== 'admin'): ?>
            <button name="action" value="make_admin" class="px-2 py-1 bg-green-600 text-white rounded">Make Admin</button>
          <?php else: ?>
            <button name="action" value="remove_admin" class="px-2 py-1 bg-yellow-600 text-white rounded">Remove Admin</button>
          <?php endif; ?>
          <button name="action" value="delete" onclick="return confirm('Delete user?')" class="px-2 py-1 bg-red-600 text-white rounded">Delete</button>
        </form>
      </td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php include __DIR__ . "/../includes/footer.php"; ?>
