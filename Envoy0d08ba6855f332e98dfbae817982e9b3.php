<?php $type = isset($type) ? $type : null; ?>
<?php $NVM_DIR = isset($NVM_DIR) ? $NVM_DIR : null; ?>
<?php $item = isset($item) ? $item : null; ?>
<?php $writable = isset($writable) ? $writable : null; ?>
<?php $shared = isset($shared) ? $shared : null; ?>
<?php $writeable = isset($writeable) ? $writeable : null; ?>
<?php $keep = isset($keep) ? $keep : null; ?>
<?php $release_dir = isset($release_dir) ? $release_dir : null; ?>
<?php $releases_dir = isset($releases_dir) ? $releases_dir : null; ?>
<?php $app_dir = isset($app_dir) ? $app_dir : null; ?>
<?php $branch = isset($branch) ? $branch : null; ?>
<?php $repo = isset($repo) ? $repo : null; ?>
<?php $now = isset($now) ? $now : null; ?>
<?php $__container->servers(['web' => 'laymont@64.23.220.79']); ?>

<?php
$now = time();
$repo = 'git@github.com:laymont/luxeplus.git';
$branch = "main"
$app_dir = '/var/www/html/luxeplus'
$releases_dir =  $app_dir . '/releases';
$release_dir  =  $app_dir . '/releases/' . date('YmdHis');

$keep = 3;

$writeable = [
'storage',
];

$shared = [
'storage' => 'd',
'.env' => 'f',
];
?>

<?php $__container->startMacro('deploy',['on'=>'web']); ?>
fetch_repo
run_composer
update_permissions
assets_install
migrate
clean
update_symlinks
<?php $__container->endMacro(); ?>

<?php $__container->startTask('fetch_repo'); ?>
[ -d <?php echo $releases_dir; ?> ] || mkdir -p <?php echo $releases_dir; ?>;
git clone <?php echo $repo; ?>  --branch=<?php echo $branch; ?> <?php echo $release_dir; ?>;
echo "Repository has been cloned";
<?php $__container->endTask(); ?>

<?php $__container->startTask('run_composer'); ?>
cd <?php echo $release_dir; ?>;
cp .env.stage .env
composer install --prefer-dist --no-interaction;
echo "Composer dependencies have been installed";
<?php $__container->endTask(); ?>

<?php $__container->startTask('update_permissions'); ?>
<?php foreach($writable as $item): ?>
    chmod -R 755 <?php echo $release_dir; ?>/<?php echo $item; ?>

    chmod -R g+s <?php echo $release_dir; ?>/<?php echo $item; ?>

    chgrp -R www-data <?php echo $release_dir; ?>/<?php echo $item; ?>

    echo "Permissions have been set for  <?php echo $release_dir; ?>/<?php echo $item; ?>"
<?php endforeach; ?>
chmod -R ug+rwx <?php echo $release_dir; ?>

chgrp -R www-data <?php echo $release_dir; ?>

echo "Permissions have been set for  <?php echo $release_dir; ?>"
<?php $__container->endTask(); ?>

<?php $__container->startTask('assets_install'); ?>
cd <?php echo $release_dir; ?>;
export NVM_DIR="$([ -z "${XDG_CONFIG_HOME-}" ] && printf %s "${HOME}/.nvm" || printf %s "${XDG_CONFIG_HOME}/nvm")"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"
nvm use 20.11.1
npm ci --legacy-peer-deps
npm run build
<?php $__container->endTask(); ?>

<?php $__container->startTask('migrate'); ?>
php <?php echo $release_dir; ?>/artisan migrate --force --no-interaction;
<?php $__container->endTask(); ?>

<?php $__container->startTask('clean'); ?>
echo "Clean old releases";
cd <?php echo $releases_dir; ?>;
rm -rf $(ls -t | tail -n +<?php echo $keep; ?>);
<?php $__container->endTask(); ?>

<?php $__container->startTask('update_symlinks'); ?>
[ -d <?php echo $app_dir; ?>/shared ] || mkdir -p <?php echo $app_dir; ?>/shared;

<?php foreach($shared as $item => $type): ?>

    #// if the item passed exists in the shared folder and in the release folder then
    #// remove it from the release folder;
    #// or if the item passed not existis in the shared folder and existis in the release folder then
    #// move it to the shared folder

    if ( [ -<?php echo $type; ?> '<?php echo $app_dir; ?>/shared/<?php echo $item; ?>' ] && [ -<?php echo $type; ?> '<?php echo $release_dir; ?>/<?php echo $item; ?>' ] );
    then
    rm -rf <?php echo $release_dir; ?>/<?php echo $item; ?>;
    echo "rm -rf <?php echo $release_dir; ?>/<?php echo $item; ?>";
    elif ( [ ! -<?php echo $type; ?> '<?php echo $app_dir; ?>/shared/<?php echo $item; ?>' ]  && [ -<?php echo $type; ?> '<?php echo $release_dir; ?>/<?php echo $item; ?>' ] );
    then
    mv <?php echo $release_dir; ?>/<?php echo $item; ?> <?php echo $app_dir; ?>/shared/<?php echo $item; ?>;
    echo "mv <?php echo $release_dir; ?>/<?php echo $item; ?> <?php echo $app_dir; ?>/shared/<?php echo $item; ?>";
    fi

    ln -nfs <?php echo $app_dir; ?>/shared/<?php echo $item; ?> <?php echo $release_dir; ?>/<?php echo $item; ?>

    echo "Symlink has been set for <?php echo $release_dir; ?>/<?php echo $item; ?>"
<?php endforeach; ?>

ln -nfs <?php echo $release_dir; ?> <?php echo $app_dir; ?>/current;
chgrp -h www-data <?php echo $app_dir; ?>/current;
echo "All symlinks have been set"
<?php $__container->endTask(); ?>
