<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= isset($PAGE_TITLE) ? htmlspecialchars($PAGE_TITLE) : "AI Blog" ?></title>

  <!-- Tailwind Play CDN (latest, supports dark mode config) -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
    }
  </script>
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-gray-100">

<!-- Navbar -->
<nav class="bg-white dark:bg-gray-800 shadow">
  <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
    <a href="/ai_blog/index.php" class="text-xl font-bold">AI Blog</a>

    <div class="flex items-center gap-4">
      <a href="/ai_blog/index.php" class="text-gray-700 dark:text-gray-200 hover:text-black dark:hover:text-yellow-400">Home</a>
      <a href="/ai_blog/blog/create.php" class="text-gray-700 dark:text-gray-200 hover:text-black dark:hover:text-yellow-400">Write</a>

      <?php if (!empty($_SESSION['user_id'])): ?>
        <span class="text-sm text-gray-600 dark:text-gray-300">Hi, <?= htmlspecialchars($_SESSION['username']) ?></span>
        <a href="/ai_blog/auth/logout.php" class="px-3 py-1 rounded bg-gray-900 text-white dark:bg-red-600">Logout</a>
      <?php else: ?>
        <a href="/ai_blog/auth/login.php" class="px-3 py-1 rounded bg-gray-900 text-white dark:bg-green-600">Login</a>
      <?php endif; ?>

      <!-- 🌙 Dark Mode Toggle -->
      <button onclick="toggleDark()" 
        class="px-3 py-1 rounded bg-gray-700 text-white hover:bg-gray-600">
        🌙
      </button>
    </div>
  </div>
</nav>

<main class="max-w-6xl mx-auto p-4">

<script>
function toggleDark() {
  document.documentElement.classList.toggle('dark');
}
</script>
