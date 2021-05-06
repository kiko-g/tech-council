# bash helper.sh fresh (seed)
if [ "$1" = "fresh" ]; then
    php artisan config:clear
    php artisan route:clear
    
    if [ "$2" = "seed" ]; then
        php artisan db:seed
    fi

fi

# bash helper.sh mc <modelName>
if [ "$1" = "mc" ]; then
    php artisan make:model $2
    php artisan make:controller $2Controller --model $2
fi