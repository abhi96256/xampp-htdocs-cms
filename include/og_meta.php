<?php
/* ── A2P Realtech – Open Graph / WhatsApp Meta Tags ────────────────────
   Desktop, Android & iOS Support (Fixed for WhatsApp Web)
   ─────────────────────────────────────────────────────────────────────── */

// Use SITE_URL constants for consistency
$_canonical_url = SITE_URL . ltrim($_SERVER['REQUEST_URI'], '/');
if (strpos($_canonical_url, 'index.php') !== false) {
    $_canonical_url = SITE_URL; // Keep root clean
}

// Global Logo for Previews (A2P Logo) - Only set if not already defined for a specific page
if (!isset($_global_logo)) {
    $_global_logo = SITE_URL . "upload/080325100432logo.png";
}
// Encoding and double-slash fix
$_global_logo = str_replace(['///', '//'], '/', $_global_logo);
$_global_logo = str_replace('https:/', 'https://', $_global_logo);
$_global_logo = str_replace(' ', '%20', $_global_logo);
?>
<?php include __DIR__ . '/favicon.php'; ?>
<!-- WhatsApp/Social Previews - Aggressive Android Fix -->
<meta property="og:image" content="<?php echo $_global_logo; ?>?v=1.3">
<meta property="og:image:secure_url" content="<?php echo $_global_logo; ?>?v=1.3">
<meta property="og:image:type" content="image/png">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="A2P Realtech Logo">
<link rel="image_src" href="<?php echo $_global_logo; ?>?v=1.3">

<meta property="og:site_name" content="A2P Realtech">
<meta property="og:title" content="<?php echo isset($og_title) ? $og_title : 'A2P Realtech Private Limited'; ?>">
<meta property="og:description" content="<?php echo isset($og_description) ? $og_description : 'Real Estate Company for Dwarka Expressway Project. Find your dream home with us.'; ?>">
<meta property="og:url" content="<?php echo $_canonical_url; ?>">
<meta property="og:type" content="<?php echo isset($og_type) ? $og_type : 'website'; ?>">

<!-- Facebook Page Verification - Links domain to Facebook Page -->

<!-- Schema.org for WhatsApp Android -->
<meta itemprop="name" content="<?php echo isset($og_title) ? $og_title : 'A2P Realtech Private Limited'; ?>">
<meta itemprop="description" content="<?php echo isset($og_description) ? $og_description : 'Real Estate Company for Dwarka Expressway Project. Find your dream home with us.'; ?>">
<meta itemprop="image" content="<?php echo $_global_logo; ?>?v=1.3">


<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?php echo isset($og_title) ? $og_title : 'A2P Realtech Private Limited'; ?>">
<meta name="twitter:description" content="<?php echo isset($og_description) ? $og_description : 'Real Estate Company for Dwarka Expressway Project'; ?>">
<meta name="twitter:image" content="<?php echo $_global_logo; ?>">


