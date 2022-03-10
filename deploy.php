<?php

namespace Deployer;

require 'recipe/laravel.php';
require 'contrib/cachetool.php';

// Config

set('application', 'yubin.snsgal.com.tw');
set('deploy_path', '~/{{application}}');

set('writable_mode', 'chmod');
set('writable_chmod_mode', '0775');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('production')
    ->setHostname('snsgal.com.tw')
    ->set('remote_user', 'deployer')
    ->set('cachetool_args', '--fcgi=/var/run/php/php8.1-fpm.sock --tmp-dir=/tmp')
    ->set('writable_chmod_recursive', false)
    ->set('deploy_path', '/var/www/{{application}}');

// Tasks

task('build', function () {
    runLocally('npm run prod');
    runLocally('rm -Rf node_modules');
})->once();

task('deploy:update_code', fn () => upload(__DIR__ . '/', '{{release_path}}'));

task('deploy:chmod', function () {
    run('chgrp -R www-data {{release_path}}');
    run('chmod -Rf 775 {{release_path}}');
    run('find {{release_path}} -type f -exec chmod 664 {} \;');
});

task('deploy', [
    'build',
    'deploy:prepare',
    'deploy:chmod',
    'artisan:storage:link',
    'artisan:view:cache',
    'artisan:config:cache',
    'deploy:publish',
]);

after('deploy:symlink', 'cachetool:clear:opcache');

after('deploy:failed', 'deploy:unlock');

