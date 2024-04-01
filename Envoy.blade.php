@servers(['web' => 'laymont@64.23.220.79'])

@task('deploy', ['on' => 'web', 'confirm' => true])
    cd /var/www/html/luxeplus

    rm -rf vendor/
    echo "Removed vendor/ directory.."
    git pull origin develop

    composer install --prefer-dist --optimize-autoloader

    php artisan migrate

    php artisan config:clear
    php artisan route:clear
    php artisan view:clear

    php artisan optimize:clear

    php artisan config:cache
    php artisan route:cache
    php artisan view:cache

    # Please make sure that you have installed
    # the node and nvm on your shared hosting server
    # If not, you can google how to install it
    export NVM_DIR="$([ -z "${XDG_CONFIG_HOME-}" ] && printf %s "${HOME}/.nvm" || printf %s "${XDG_CONFIG_HOME}/nvm")"
    [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"
    nvm use 20.11.1
    npm ci --legacy-peer-deps
    npm run build

    rm -rf node_modules/
    echo "Removed node_modules/ directory.."
@endtask
