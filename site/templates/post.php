<?php snippet('header') ?>

<main>
    <article class="post">
      <header class="post-header">
        <?php if($page->show_title()->toBool()): ?>
          <h1 class="post-title"><?= $page->title() ?></h1>
        <?php endif ?>
        
        <div class="post-meta">
          <?php if ($page->hide_dates()->toBool()): ?>
            <?php /* dates are hidden */ ?>
          <?php else: ?>
            <?php if ($page->date()->isNotEmpty()): ?>
              <time datetime="<?= $page->date()->toDate('Y-m-d') ?>">
                <?= $page->date()->toDate('l, F j, Y \a\t H:i') ?>
              </time>
            <?php endif ?>

            <?php if ($page->updated()->isNotEmpty()): ?>
              <br>
              <time id="updated" datetime="<?= $page->updated()->toDate('Y-m-d') ?>">
                Updated on <?= $page->updated()->toDate('l, F j, Y \a\t H:i') ?>
              </time>
            <?php endif ?>
          <?php endif ?>
            
          <?php if ($page->author()->isNotEmpty()): ?>
            <span class="post-author">
              by <?= $page->author() ?>
            </span>
          <?php endif ?>
        </div>
      </header>

      <br>

      <?php if($page->page_content()->isNotEmpty()): ?>
        <div class="post-content">
          <?= $page->page_content()->kt() ?>
        </div>
      <?php endif ?>

      <?php if($page->references()->isNotEmpty()): ?>
        <div class="references">
          <?= $page->references()->kt() ?>
        </div>
      <?php endif ?>
    
      <?php if($page->tags()->isNotEmpty()): ?>
        <?php if (!$page->hide_tags()->toBool()): ?>
          <div class="post-tags">
            <?php 
            $tags = $page->tags()->split();
            $lastIndex = count($tags) - 1;
            foreach($tags as $index => $tag): ?>
              <a href="<?= url('keys') ?>#<?= str::slug($tag) ?>"><?= html($tag) ?></a><?= ($index !== $lastIndex) ? ', ' : '' ?>
            <?php endforeach ?>
          </div>
        <?php endif ?>
      <?php endif ?>

      <?php if($page->related()->isNotEmpty()): ?>
        <div class="related">
          <?php foreach($page->related()->toPages() as $post): ?>
            <?php if($post->isListed()): ?>
              <a href="<?= $post->url() ?>" class="small-color-block" style="background-color: <?= $post->color() ?>" title="<?= $post->title() ?>"></a>
            <?php endif ?>
          <?php endforeach ?>
        </div>
      <?php endif ?>

    </article>

    <br><br><br><br>

    <?php if (!$page->hide_color_card()->toBool()): ?>
      <div class="post-color" style="background-color: <?= $page->color() ?>; height: 100%; width: 100%; aspect-ratio: 1/1;"></div>
    <?php endif ?>
</main>

<?php snippet('footer') ?>
