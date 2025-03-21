<?php snippet('header') ?>

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


    <br><br><br>

    <div class="tags-list">
        <div class="small-label">Tags</div>
        <?php foreach($tags as $i => $tag): ?>
            <a href="<?= url('tags/#' . str::slug($tag)) ?>" class="tag"><?= html($tag) ?></a><?= ($i < count($tags) - 1) ? ',' : '' ?>
        <?php endforeach ?>
    </div>
</main>

<?php snippet('footer') ?>