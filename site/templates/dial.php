<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= css('assets/css/dial.css?v=1.1') ?>
    <title><?= $site->title() ?></title>
    <link rel="icon" type="image/png" href="/assets/images/favicon.png" />
</head>

<body>
    <main>
        <div id="dial">
            <div id="band-selector"></div>
            <div id="dial-posts">
                <?php 
    
                // Fetch all posts (including unlisted)
                $posts = $site->find('posts')->children();
    
                // Sort posts by date
                $sortedPosts = $posts->sortBy('date', 'desc')->limit(62);
    
                foreach($sortedPosts as $post): ?>
                    <?php if($post->isListed()): ?>
                        <a href="<?= $post->url() ?>">
                            <div class="color-block" style="background-color: <?= $post->color() ?>"></div>
                        </a>
                    <?php else: ?>
                        <div class="unlisted">
                            <div class="color-block"></div>
                        </div>
                    <?php endif ?>
                <?php endforeach ?>
            </div>
            <button id="audio-toggle" aria-label="Toggle sound">unmute</button>
        </div>  
    </main>

    <script>
        const dial = document.getElementById('dial');
        const bandSelector = document.getElementById('band-selector');
        const audioToggle = document.getElementById('audio-toggle');
        let audioContext = null;
        let oscillator = null;
        let gainNode = null;

        audioToggle.addEventListener('click', () => {
            if (!audioContext) {
                // Initialize audio
                audioContext = new (window.AudioContext || window.webkitAudioContext)();
                oscillator = audioContext.createOscillator();
                gainNode = audioContext.createGain();
                gainNode.gain.value = 0.1;
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                oscillator.start();
                audioContext.resume();
                audioToggle.textContent = 'mute';
            } else {
                // Cleanup audio
                oscillator.disconnect();
                oscillator = null;
                gainNode = null;
                audioContext = null;
                audioToggle.textContent = 'unmute';
            }
        });

        dial.addEventListener('mouseleave', () => {
            if (oscillator) {
                oscillator.disconnect();
                oscillator = null;
                gainNode = null;
                audioContext = null;
                audioToggle.textContent = 'unmute';
            }
        });

        dial.addEventListener('mousemove', (e) => {
            const rect = dial.getBoundingClientRect();
            const x = e.clientX - rect.left;
            bandSelector.style.left = `${x}px`;

            if (audioContext && oscillator) {
                const element = document.elementFromPoint(e.clientX, e.clientY);
                // Look for either the color-block div or check its parent if it's the <a>
                const colorElement = element.classList.contains('color-block') ? 
                    element : 
                    element.querySelector('.color-block');
                
                if (colorElement && colorElement.style.backgroundColor) {
                    const color = colorElement.style.backgroundColor;
                    const rgb = color.match(/\d+/g);
                    if (rgb) {
                        const avgColor = (parseInt(rgb[0]) + parseInt(rgb[1]) + parseInt(rgb[2])) / 3;
                        const frequency = 200 + (avgColor * 2.35);
                        oscillator.frequency.setValueAtTime(frequency, audioContext.currentTime);
                    }
                }
            }
        });
    </script>
</body>
</html>