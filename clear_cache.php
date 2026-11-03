<?php
// One-time cache clear script - DELETE after use
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "OPcache cleared successfully!";
} else {
    echo "OPcache not enabled or already fresh.";
}
echo "<br>Done at: " . date('Y-m-d H:i:s');
?>
