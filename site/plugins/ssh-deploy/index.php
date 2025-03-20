<?php

// Function to deploy the static site via SSH
function deployStaticSite() {
    $kirby = kirby();
    $root = $kirby->root();
    $config = $kirby->option('ssh-deploy');
    
    // Check if deployment is enabled
    if (!($config['enabled'] ?? true)) {
        error_log('SSH Deploy: Deployment is disabled in config');
        return false;
    }
    
    // Get configuration values with fallbacks
    $host = $config['host'] ?? '167.99.158.175';
    $user = $config['user'] ?? 'elliott';
    $path = $config['path'] ?? '/var/www/elliott.computer/memory/';
    $source = $config['source'] ?? 'static/';
    $exclude = $config['exclude'] ?? ['.DS_Store', '.vscode', '.nova', 'deploy.sh', '_archive', '_drafts', '.gitignore', '.git', '**/.git', '.kirbystatic'];
    $chmod = $config['chmod'] ?? 'Du=rwx,Dgo=rx,Fu=rw,Fog=r';
    $delete = $config['delete'] ?? true;
    
    // Build exclude string
    $excludeString = '';
    if (!empty($exclude)) {
        $excludeString = '--exclude={' . implode(',', $exclude) . '}';
    }
    
    // Add delete flag if enabled
    $deleteFlag = $delete ? '--delete' : '';
    
    // Deploy the static site via SSH using rsync
    error_log('SSH Deploy: Deploying site via SSH');
    
    // Define the rsync command with explicit path
    $command = "cd {$root} && rsync -avP {$source} {$user}@{$host}:{$path} {$excludeString} --chmod={$chmod} {$deleteFlag}";

    // Log the command
    error_log('SSH Deploy: Executing command: ' . $command);

    // Execute the command
    exec($command, $output, $returnVar);

    // Log the result
    error_log('SSH Deploy: Command output: ' . implode("\n", $output));
    error_log('SSH Deploy: Return value: ' . $returnVar);
    
    return $returnVar === 0; // Return true if successful, false otherwise
}

Kirby::plugin('elliott/ssh-deploy', [

    'tags' => [
        'snippet' => [
            'html' => function($tag) {
                $snippetName = $tag->value();
                return snippet($snippetName, [], true);
            }
        ]
    ],
    
    'fields' => [
        'sshDeploy' => [
            'props' => [
                'label' => function ($label = 'Deploy to Server') {
                    return $label;
                },
                'progress' => function ($progress = 'Deploying...') {
                    return $progress;
                },
                'success' => function ($success = 'Deployment successful!') {
                    return $success;
                },
                'error' => function ($error = 'Deployment failed.') {
                    return $error;
                }
            ]
        ]
    ],
    
    'api' => [
        'routes' => [
            [
                'pattern' => 'ssh-deploy',
                'method' => 'POST',
                'action' => function () {
                    try {
                        // Generate the static site directly using D4L StaticSiteGenerator
                        $kirby = kirby();
                        $generator = new D4L\StaticSiteGenerator($kirby);
                        $fileList = $generator->generate();
                        
                        // Get Kirby root directory
                        $root = $kirby->root();
                        
                        // Log that static site generation is complete
                        error_log('SSH Deploy: Static site generation complete with ' . count($fileList) . ' files');
                        
                        // Create a timestamp file to track when the site was last generated
                        $timestamp = date('Y-m-d H:i:s');
                        file_put_contents($root . '/static/last-generated.txt', $timestamp);
                        
                        // Deploy the static site
                        $success = deployStaticSite();
                        
                        if ($success) {
                            return [
                                'status' => 'success',
                                'message' => 'Deployment completed successfully'
                            ];
                        } else {
                            return [
                                'status' => 'error',
                                'message' => 'Deployment failed'
                            ];
                        }
                    } catch (\Exception $e) {
                        return [
                            'status' => 'error',
                            'message' => 'Error: ' . $e->getMessage()
                        ];
                    }
                }
            ]
        ]
    ]
]);