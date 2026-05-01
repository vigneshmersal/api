<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Laravel

> php artisan install:api
> php artisan migrate
> npm run build

## API Sorting dynamically
> sort=email 			(asc)
> sort=-email 			(desc)
> sort=email,-title 	(multiple sort)

## API Include relationships dynamically
> includes('author')
> includes('author, user.profile')
> include(['author', 'user.profile'])

# API Date
> createdAt=2024-01-01
> createdAt=2024-01-01,2024-12-12

## API Filters
> filter[status]=subscribed
> filter[status]=subscribed,completed
