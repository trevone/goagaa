<?php
namespace Deployer;

require 'recipe/laravel.php';

// Config

// root password prod
// 99DfBcj8gypXFtLD
// root password wsh
// dGJYvH7dg5sgJ8
set('repository', 'git@bitbucket.org:edukudu2023/wsh3.git');
set('ssh_multiplexing', false);

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('goagaa')
    ->setHostname('144.126.227.214')
    ->setRemoteUser('deployer')
    ->set('deploy_path', '~/goagaa')
    ->set('keep_releases', 3)
    ->set('env_version', '.env.goagaa')
    ->set('branch', 'master')
    ->set('env_contents', function () {
        // return 'trt';
        return file_get_contents('.env.goagaa');
    })
    // for provisioning
    ->set('sudo_password', 'gbQ+P?^y52Tgif.f')
    ->set('domain', 'goagaa.com')
    ->set('public_path', 'public')
    ->set('php_version', '8.1')
    ->set('db_type', 'mariadb')
    ->set('db_user', 'deployer')
    ->set('db_name', 'thegoagaa')
    ->set('db_password', 'woodburn')
    // end of provisioning
    ;
 
// Tasks

task('env:upload', function () {
    // writeln('{{env_contents}}');
    run('cd {{release_path}} && echo "{{env_contents}}" > .env');
})->desc('Upload the production env file to the environment');

task('env:setup', function () {
  //
})->desc('Environment setup');

task('environment', [
  'env:upload',
  'env:setup'
])->desc('Environment setup');

task('build', function () {
    cd('{{release_path}}');
    run('export NODE_OPTIONS=--max-old-space-size=32768');
    run('npm install');
    run('npm run build'); 
})->desc('Build assets');

task('queue', function () {
    cd('{{release_path}}'); 
    run('php artisan queue:restart'); 
})->desc('Reset queues');

// Hooks

before('deploy:vendors', 'environment');
after('deploy:vendors', 'build');
after('deploy:failed', 'deploy:unlock');
after('artisan:migrate', 'queue');
//task('artisan:view:cache')->disable();






/**
Fix Caddy install
provision recipe
//  run("curl -1sLf 'https://dl.cloudsmith.io/public/caddy/stable/gpg.key' > /etc/apt/trusted.gpg.d/caddy-stable.asc");
//  run("curl -1sLf 'https://dl.cloudsmith.io/public/caddy/stable/debian.deb.txt' > /etc/apt/sources.list.d/caddy-stable.list");
run("sudo apt install -y debian-keyring debian-archive-keyring apt-transport-https");




 */
