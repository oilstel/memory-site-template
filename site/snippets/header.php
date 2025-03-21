<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if(!$page->isListed()): ?>
    <meta name="robots" content="noindex">
    <?php endif ?>
    <?= css('assets/css/style.css?v=1.1') ?>

    <?php if($page->intendedTemplate() == 'post' && $page->title()->isNotEmpty()): ?>
        <title><?= $page->title() ?> - <?= $site->title() ?></title>
    <?php else: ?>
        <title><?= $site->title() ?></title>
    <?php endif ?>

    <?php 
    $faviconColor = null;
    
    // First try to get color from current page
    if($page->intendedTemplate() == 'post' && $page->color()->isNotEmpty()) {
        $faviconColor = $page->color();
    } 
    // Otherwise use color from the last post
    else {
        $lastPost = $site->find('posts')->children()->listed()->sortBy('date', 'desc')->first();
        if($lastPost && $lastPost->color()->isNotEmpty()) {
            $faviconColor = $lastPost->color();
        }
    }
    
    if($faviconColor): 
    ?>
        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<?= rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><rect width="100" height="100" fill="' . $faviconColor . '"/></svg>') ?>" />
    <?php endif ?>
</head>

<body>
    <?php if($page->intendedTemplate() != 'home'): ?>
        <a href="/" id="home" style="--hover-color: <?= $page->color() ?>"></a>
    <?php endif ?>