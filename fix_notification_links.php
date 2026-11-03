<?php
/**
 * One-time fix script: Updates notification links in DB
 * Run once: http://yourdomain.com/cms/fix_notification_links.php
 * DELETE this file after running!
 */
include('./function/function.php');
$pdo = getPDOObject();

// Fetch all notifications with empty/generic links
$notifs = $pdo->query("SELECT * FROM notifications ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

$fixed = 0;
$skipped = 0;
$site_base = rtrim(SITE_URL, '/');

echo "<pre style='font-family:monospace; padding:20px;'>";
echo "=== Notification Link Fixer ===\n\n";

foreach ($notifs as $nt) {
    $link = $nt['link'];
    
    // Check if link is generic or wrong
    $is_generic = (
        empty($link) ||
        rtrim($link, '/') === $site_base ||
        $link === SITE_URL ||
        strpos($link, 'blog.php') !== false ||
        $link === $site_base . '/blog.php'
    );
    
    if (!$is_generic) {
        echo "ID {$nt['id']}: SKIP (already has good link: $link)\n";
        $skipped++;
        continue;
    }
    
    // Extract blog name from title
    $raw_title = trim($nt['title']);
    $blog_clean = preg_replace('/^(New Blog\s*:?\s*|Updated Blog\s*:?\s*)/i', '', $raw_title);
    $blog_clean = trim(preg_replace('/(\.\s*Read more now!.*|Check out.*|\s*Read more now!\s*$)/is', '', $blog_clean));

    // Search offer table
    $stmt = $pdo->prepare("SELECT name FROM `offer` WHERE name LIKE :n AND actstat=1 LIMIT 1");
    $stmt->execute([':n' => '%' . $blog_clean . '%']);
    $found = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$found) {
        // Try full raw title
        $stmt2 = $pdo->prepare("SELECT name FROM `offer` WHERE name LIKE :n AND actstat=1 LIMIT 1");
        $stmt2->execute([':n' => '%' . $raw_title . '%']);
        $found = $stmt2->fetch(PDO::FETCH_ASSOC);
    }

    if ($found) {
        $new_link = SITE_URL . "blog_detail/" . makeurlnamebynameCategory($found['name']) . ".php";
        $pdo->prepare("UPDATE notifications SET link = ? WHERE id = ?")->execute([$new_link, $nt['id']]);
        echo "ID {$nt['id']}: FIXED → $new_link\n";
        $fixed++;
    } else {
        echo "ID {$nt['id']}: NO MATCH FOUND for title: \"{$raw_title}\" (cleaned: \"{$blog_clean}\")\n";
        $skipped++;
    }
}

echo "\n=== Done! Fixed: $fixed | Skipped/No Match: $skipped ===\n";
echo "\n⚠️  DELETE this file after running: fix_notification_links.php\n";
echo "</pre>";
?>
