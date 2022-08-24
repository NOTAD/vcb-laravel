# AutoBank system

## Overview
An EC site base on Laravel and VueJS

## How to setup
**NOTE**  Require finish setting up environment before.

1 - Pull source code
- Clone project's repository inside `laradev/data/sites`
```
cd ~/laradev/data/sites
git clone {repository_url}
```
2 - Enter the project folder, copy and rename `env-example` to `.env`
```
cd ~/{project_folder}
cp env-example .env
```

3 - Config .env, you can create new database via Laradev instruction, default
database is `autobanksystem`
```
DB_CONNECTION=mysql
DB_HOST=laradev-mysql
DB_PORT=3306
DB_DATABASE=autobanksystem
DB_USERNAME=autobanksystem
DB_PASSWORD=autobanksystem
```

```
REDIS_HOST=laradev-redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```

9 - Change hosts file
In local machine, Add bellow config to「/etc/hosts」file.
```
# Add domain to hosts
127.0.0.1 junoo.web
```

10 - Using composer to install PHP packages
In project folder, run bellow command
```
composer install
```

11 - Using npm to install Node Js packages
In project folder, run bellow command
```
npm install
```

12 - Generate the application key
In project folder, run bellow command
```
php artisan key:generate
```
13 - Migrate and seeding database
In project folder, run bellow command
```
php artisan migrate --seed
```

14 - Complie js
In project folder, run bellow command
```
npm run dev
```

15 - Link storage to public
In project folder, run bellow command
```
php artisan storage:link
```

16 - Open your browser and visit `https://junoo.web/`   
`That's it! enjoy :)`

