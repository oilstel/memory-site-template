<?php snippet('header') ?>

<img src="/assets/images/03-13-2025.svg" id="home-drawing">

<main class="full">
    <p style="margin-top: 0;">This site has <?= $site->find('posts')->children()->count() ?> pages and was last updated on <?= $site->find('posts')->children()->sortBy('date', 'desc')->first()->date()->toDate('l, F j, Y \a\t H:i') ?></p>

    <div class="shelf">        
        <div class="shelf-items">
            <?php
            $shelfItems = site()->shelf()->toPages();

            foreach ($shelfItems as $item): ?>
                <div class="shelf-item">
                    <a href="<?= $item->url() ?>"><div style="background-color: <?= $item->color() ?>" class="shelf-item-color"></div></a>
                    
                    <div class="shelf-item-content">
                        <?php if($item->page_content()->isNotEmpty()): ?>
                            <a href="<?= $item->url() ?>"><?= $item->page_content()->excerpt(45, true, '') ?>...<br></a>
                        <?php endif ?>
                        <time class="post-date"><?= $item->date()->toDate('l, F j, Y \a\t H:i') ?></time>
                        <?php if($item->excerpt()->isNotEmpty()): ?>
                            <div class="post-excerpt">
                                <?= $item->excerpt()->kirbytext() ?>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>

    <div class="posts-grid">
        <?php
        // Fetch all posts (including unlisted)
        $posts = $site->find('posts')->children();

        // Sort posts by date
        $sortedPosts = $posts->sortBy('date', 'desc');
          
        // Loop through sorted posts and display them
        foreach ($sortedPosts as $post): ?>
            <?php if($post->isListed()): ?>
                <article class="post">
                    <?php if($post->cover_image()->isNotEmpty()): ?>
                        <a href="<?= $post->url() ?>"><img src="<?= $post->cover_image() ?>" class="post-image" alt="<?= $post->title()->html() ?>"></a>
                    <?php else: ?>
                        <a href="<?= $post->url() ?>"><div style="background-color: <?= $post->color() ?>" class="post-color"></div></a>
                    <?php endif ?>
                    
                    <div class="post-content">
                        <?php if($post->page_content()->isNotEmpty()): ?>
                            <a href="<?= $post->url() ?>"><?= $post->page_content()->excerpt(45, true, '') ?>...</a><br>
                        <?php endif ?>
                        <time class="post-date"><?= $post->date()->toDate('l, F j, Y \a\t H:i') ?></time>
                        <?php if($post->excerpt()->isNotEmpty()): ?>
                            <div class="post-excerpt">
                                <?= $post->excerpt()->kirbytext() ?>
                            </div>
                        <?php endif ?>
                    </div>
                </article>
            <?php else: ?>
                <article class="post unlisted">
                    <div style="background-color: gray" class="post-color"></div>
                    <div class="post-content-unlisted">

                        <time class="post-date"><?= $post->date()->toDate('l, F j, Y \a\t H:i') ?></time>
                    </div>
                </article>
            <?php endif ?>
        <?php endforeach ?>
    </div>

    <?php
    // Get all unique tags across all posts
    $tags = page('posts')          
        ->children()               
        ->published()             // only published posts
        ->listed()               // only listed posts
        ->pluck('tags', ',', true); 

    $tags = array_unique($tags);   
    sort($tags);                   
    ?>

    <br><br><br><br><br><br>

    <div class="tag-counts">
        <div class="small-label">Score</div>
        <?php
        // Initialize an array to store tag counts
        $tagCounts = [];
        
        // Get all published and listed posts
        $publishedPosts = page('posts')->children()->published()->listed();
        
        // Count posts for each tag
        foreach($tags as $tag) {
            $count = $publishedPosts->filterBy('tags', $tag, ',')->count();
            $tagCounts[$tag] = $count;
        }
        
        // Find the minimum and maximum counts
        $minCount = min(array_values($tagCounts));
        $maxCount = max(array_values($tagCounts));
        
        // Display the counts with color gradient from red (min) to purple (max)
        $countValues = array_values($tagCounts);
        foreach($countValues as $i => $count): 
            // Calculate color based on count value
            $color = "#f0f0f0"; // Default red for minimum
            if($count > $minCount && $maxCount > $minCount) {
                // Calculate percentage between min and max
                $percentage = ($count - $minCount) / ($maxCount - $minCount);
                // Interpolate between red (#ff0000) and purple (#800080)
                $r = floor(255 * (1 - $percentage) + 128 * $percentage);
                $g = floor(0 * (1 - $percentage) + 0 * $percentage);
                $b = floor(0 * (1 - $percentage) + 128 * $percentage);
                $color = sprintf("#%02x%02x%02x", $r, $g, $b);
            }
            if($count == $maxCount) {
                $color = "#800080"; // Pure purple for maximum
            }
        ?>
            <span style="color: <?= $color ?>;"><?= $count ?></span><?= ($i < count($countValues) - 1) ? ', ' : '' ?>
        <?php endforeach ?>
    </div>

    <br><br><br>

    <div class="tags-list">
        <div class="small-label">Containers</div>
        <?php foreach($tags as $i => $tag): ?>
            <a href="<?= url('keys/#' . str::slug($tag)) ?>" class="tag"><?= html($tag) ?></a><?= ($i < count($tags) - 1) ? ',' : '' ?>
        <?php endforeach ?>
    </div>
</main>

<?php snippet('footer') ?>