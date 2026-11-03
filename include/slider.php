<div class="news-ticker-container">
    <div class="news-ticker-label">
        <span class="pulse-icon"></span>
        Trending
    </div>
    <div class="news-ticker-content">
        <div class="news-ticker-wrapper">
             <?php 
            $result = sqlfetch("select * from news ");
            $ticker_data = sqlfetch("select ticker_speed from address limit 1");
            $ticker_speed = (!empty($ticker_data) && isset($ticker_data[0]['ticker_speed'])) ? $ticker_data[0]['ticker_speed'] : 40;
            if (count($result)) {
                // Repeat the items to ensure continuous scroll
                $news_items = array_merge($result, $result);
                foreach ($news_items as $news) {
            ?>
                <a href="<?php echo $news['slug']; ?>" target="_blank" class="news-ticker-item">
                    <i class="fa-solid fa-fire-flame-curved"></i>
                    <?php echo $news['name']; ?>
                </a>
            <?php }
            } ?>
        </div>
    </div>
</div>

<style>
.news-ticker-container {
    display: flex;
    align-items: center;
    background: #fff;
    border-top: 1px solid #eee;
    border-bottom: 2px solid #ed1c24;
    height: 45px;
    overflow: hidden;
    position: relative;
    z-index: 10;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.news-ticker-label {
    background: #ed1c24;
    color: #fff;
    padding: 0 25px;
    height: 100%;
    display: flex;
    align-items: center;
    font-weight: 700;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
    position: relative;
    z-index: 2;
    clip-path: polygon(0 0, 100% 0, 85% 100%, 0% 100%);
}

.pulse-icon {
    width: 8px;
    height: 8px;
    background: #fff;
    border-radius: 50%;
    margin-right: 10px;
    position: relative;
}

.pulse-icon::after {
    content: '';
    position: absolute;
    top: -4px;
    left: -4px;
    right: -4px;
    bottom: -4px;
    border: 2px solid #fff;
    border-radius: 50%;
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0% { transform: scale(0.5); opacity: 1; }
    100% { transform: scale(2); opacity: 0; }
}

.news-ticker-content {
    flex: 1;
    overflow: hidden;
    height: 100%;
}

.news-ticker-wrapper {
    display: flex;
    align-items: center;
    height: 100%;
    white-space: nowrap;
    animation: ticker-scroll <?php echo $ticker_speed; ?>s linear infinite;
}

.news-ticker-wrapper:hover {
    animation-play-state: paused;
}

.news-ticker-item {
    display: inline-flex;
    align-items: center;
    color: #333;
    font-size: 14px;
    font-weight: 500;
    padding: 0 40px;
    text-decoration: none !important;
    transition: color 0.3s ease;
}

.news-ticker-item i {
    color: #ed1c24;
    margin-right: 12px;
    font-size: 16px;
}

.news-ticker-item:hover {
    color: #ed1c24;
}

@keyframes ticker-scroll {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}

@media (max-width: 768px) {
    .news-ticker-label {
        padding: 0 15px;
        font-size: 12px;
    }
    .news-ticker-item {
        padding: 0 20px;
        font-size: 13px;
    }
}
</style>












<style>
    /* Premium Hero Slider - Maintaining Aspect Ratio - No Cropping */
    .carousel-inner {
        height: auto !important;
        background: transparent;
        overflow: visible !important; /* Allow image to show fully */
    }

    .carousel-item {
        height: auto !important;
    }

    .carousel-item img {
        width: 100% !important; /* Fill the banner width */
        height: auto !important; /* Maintain natural aspect ratio */
        max-height: none !important; /* Allow image to be as tall as its ratio requires */
        object-fit: contain !important; /* Ensure the whole image is visible */
        display: block !important;
        margin: 0 auto;
    }

    /* Mobile Adaptations */
    @media (max-width: 767px) {
        .carousel-inner {
            height: auto !important;
            min-height: auto !important; /* Remove fixed minimum height to stop black gaps */
        }
    }
</style>

<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000" data-bs-pause="false">
    <?php
        $sql_gal = sqlfetch("select * from client where actstat=1 order by fld_order");
        $slide_count = count($sql_gal);
        if ($slide_count > 0) {
    ?>
    <div class="carousel-indicators">
        <?php for($i = 0; $i < $slide_count; $i++) { ?>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="<?= $i ?>" <?php if($i == 0) echo 'class="active" aria-current="true"'; ?> aria-label="Slide <?= ($i+1) ?>"></button>
        <?php } ?>
    </div>
    <div class="carousel-inner">
        <?php
            $count = 0;
            foreach ($sql_gal as $pr_gal) {
                // Encode spaces and special chars in filename
                $photo_name = str_replace(' ', '%20', $pr_gal['photo']);
        ?>
            <div class="carousel-item <?php if($count == 0) { ?>active<?php } ?>">
                <img src="<?= rtrim(SITE_URL, '/'); ?>/upload/<?= $photo_name; ?>" class="d-block" alt="Banner">
                <div class="carousel-caption d-none d-md-block">
                </div>
            </div>
        <?php $count++; } ?>
    </div>
    <?php } ?>
</div>


