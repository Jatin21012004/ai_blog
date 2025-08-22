<?php
$PAGE_TITLE = "Home";
require __DIR__ . "/config/db.php";
require __DIR__ . "/includes/functions.php";

$search = isset($_GET['q']) ? trim($_GET['q']) : "";
$cat    = isset($_GET['cat']) ? trim($_GET['cat']) : "";
$page   = max(1, (int)($_GET['page'] ?? 1));
$limit  = 6;
$offset = ($page - 1) * $limit;

// Build WHERE
$where = "b.status='published'";
$params = [];
$types  = "";

if ($search !== "") {
  $where .= " AND (b.title LIKE ? OR b.content LIKE ?)";
  $params[] = "%".$search."%"; 
  $params[] = "%".$search."%"; 
  $types .= "ss";
}
if ($cat !== "") {
  $where .= " AND b.category = ?";
  $params[] = $cat; $types .= "s";
}

// Count total
$countSql = "SELECT COUNT(*) as total FROM blogs b WHERE $where";
$stmt = $conn->prepare($countSql);
if ($types) $stmt->bind_param($types, ...$params);
$stmt->execute(); $total = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
$totalPages = max(1, (int)ceil($total / $limit));

// Fetch posts
$sql = "SELECT b.id,b.title,LEFT(b.content,280) as snippet,b.category,b.created_at,u.username
        FROM blogs b
        JOIN users u ON u.id=b.user_id
        WHERE $where
        ORDER BY b.created_at DESC
        LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
if ($types) {
  $types .= "ii";  
  $params[] = $limit;
  $params[] = $offset;
  $stmt->bind_param($types, ...$params);
} else {
  $stmt->bind_param("ii", $limit, $offset);
}
$stmt->execute(); $posts = $stmt->get_result();

// Categories for filter
$cats = $conn->query("SELECT name FROM categories ORDER BY name ASC");
include __DIR__ . "/includes/header.php";
?>
<h1 class="text-2xl font-bold mb-4">Latest Posts</h1>

<form class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-3" method="get">
  <input type="text" name="q" value="<?php echo e($search); ?>" placeholder="Search title or content..."
         class="p-2 border rounded w-full">
  <select name="cat" class="p-2 border rounded w-full">
    <option value="">All Categories</option>
    <?php while($c = $cats->fetch_assoc()): ?>
      <option value="<?php echo e($c['name']); ?>" <?php echo ($cat===$c['name']?'selected':''); ?>><?php echo e($c['name']); ?></option>
    <?php endwhile; ?>
  </select>
  <button class="p-2 rounded bg-gray-900 text-white">Filter</button>
  <a href="/ai_blog/index.php" class="p-2 rounded border text-center">Reset</a>
</form>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-4">
<?php while($p = $posts->fetch_assoc()): ?>
  <article class="bg-white p-5 rounded-2xl shadow-md hover:shadow-xl transition duration-300 flex flex-col justify-between">
    
    <div class="flex items-center justify-between mb-3">
      <span class="text-xs font-medium px-2 py-1 bg-indigo-100 text-indigo-700 rounded-full">
        <?php echo e($p['category'] ?: "General"); ?>
      </span>
      <span class="text-xs text-gray-500">
        <?php echo date("M d, Y", strtotime($p['created_at'])); ?>
      </span>
    </div>

    <h2 class="text-xl font-semibold text-gray-900 mb-2 line-clamp-2">
      <?php echo e($p['title']); ?>
    </h2>

    <p class="text-gray-600 text-sm mb-4 line-clamp-3">
      <?php echo e($p['snippet']); ?>...
    </p>

    <div class="flex items-center justify-between mt-auto">
      <span class="text-sm text-gray-500">✍️ <?php echo e($p['username']); ?></span>
      <a href="/ai_blog/blog/view.php?id=<?php echo (int)$p['id']; ?>"
         class="text-sm px-4 py-1.5 rounded-lg bg-gray-900 text-white hover:bg-gray-700 transition">
        Read →
      </a>
    </div>

  </article>
<?php endwhile; ?>
</div>

<?php if ($totalPages > 1): ?>
  <div class="mt-6 flex gap-2">
    <?php for($i=1;$i<=$totalPages;$i++): 
      $qsArray = array();
      if ($search !== "") $qsArray['q'] = $search;
      if ($cat !== "") $qsArray['cat'] = $cat;
      $qsArray['page'] = $i;
      $qs = http_build_query($qsArray);
    ?>
      <a class="px-3 py-1 rounded border <?php echo ($i===$page?'bg-gray-900 text-white':''); ?>" 
         href="/ai_blog/index.php?<?php echo e($qs); ?>"><?php echo $i; ?></a>
    <?php endfor; ?>
  </div>
<?php endif; ?>

<?php include __DIR__ . "/includes/footer.php"; ?>