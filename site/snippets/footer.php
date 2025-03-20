<footer>
    <div style="border: 1px solid #000000; width: 10px; height: 10px;"></div>
    <p>This is my <a href="https://<?= $site->title() ?>"><?= $site->title() ?></a></p>
    <p>You can browse other sites on my computer at <a href="https://home.elliott.computer">home.elliott.computer</a></p>
    <p>
        <?php
        $words = [
            'green',
            'forest', 
            'walk', 
            'swim', 
            'leaf',
            'music',
            'sun',
            'fern',
            'grass',
            'tree',
            'echo',
            'fog',
            'sky',
            'remember',
            'memory',
            'chance'
        ];
        $randomWord = $words[array_rand($words)];
        ?>
    </p>
    <p>You can email me at <a href="mailto:<?= $randomWord ?>@elliott.computer"><?= $randomWord ?>@elliott.computer</a></p>
    <br>
    <div id="posts-as-hr">
        <?php 

        // Fetch all posts (including unlisted)
        $posts = $site->find('posts')->children();

        // Sort posts by date
        $sortedPosts = $posts->sortBy('date', 'desc');

        foreach($sortedPosts as $post): ?>
            <?php if($post->isListed()): ?>
                <a href="<?= $post->url() ?>" style="background-color: <?= $post->color() ?>"></a>
            <?php else: ?>
                <span style="background-color: gray"></span>
            <?php endif ?>
        <?php endforeach ?>
    </div>
</footer>

</body>
</html>