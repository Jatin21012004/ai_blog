<nav class="bg-gray-900 text-white p-4 flex justify-between items-center">
    <!-- Logo -->
    <a href="/ai_blog/index.php" class="text-xl font-bold">AI Blog</a>

    <!-- Links + Dark Mode -->
    <div class="flex items-center space-x-3">
        <a href="/ai_blog/index.php" class="px-3 hover:text-yellow-400">Home</a>
        <a href="/ai_blog/blog/create.php" class="px-3 hover:text-yellow-400">Write</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/ai_blog/auth/logout.php" class="px-3 hover:text-red-400">Logout</a>
        <?php else: ?>
            <a href="/ai_blog/auth/login.php" class="px-3 hover:text-green-400">Login</a>
            <a href="/ai_blog/auth/signup.php" class="px-3 hover:text-blue-400">Signup</a>
        <?php endif; ?>

        <!-- 🌙 Dark Mode Button -->
        <button onclick="toggleDark()" 
                class="ml-4 bg-gray-700 hover:bg-gray-600 text-white px-3 py-1 rounded transition">
            🌙
        </button>
    </div>
</nav>

<script>
function toggleDark() {
    document.documentElement.classList.toggle('dark');
}
</script>
