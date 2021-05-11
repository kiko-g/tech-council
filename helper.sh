# bash helper.sh fresh [seed]
if [ "$1" = "fresh" ]; then
    php artisan config:clear
    php artisan route:clear
    if [ "$2" = "seed" ]; then
        php artisan db:seed
    fi

# bash helper.sh mc <modelName>
elif [ "$1" = "mc" ]; then
    php artisan make:model $2
    php artisan make:controller $2Controller --model $2

else echo "Usage: bash helper.sh <fresh|mc> <seed>"
fi
