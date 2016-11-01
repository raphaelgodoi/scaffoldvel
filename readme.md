# Laravel 5.x Scaffold Generator
## Usage

### Step 1: Install Through Composer

```
composer require 'raphagodoi/scaffoldvel' --dev
```

### Step 2: Add the Service Provider

Open `config/app.php` and, to your **providers** array at the bottom, add:

```
RaphaGodoi\ScaffoldVel\GeneratorsServiceProvider::class
```

### Step 3: Run Artisan!

You're all set. Run `php artisan` from the console, and you'll see the new commands `make:scaffold`.

## Examples

Use this command to generator scaffolding of **Tweet** in your project:
```
php artisan make:scaffold Tweet \
	--schema="title:string:default('Tweet #1'), body:text"
```
or with more options
```
php artisan make:scaffold Tweet \
	--schema="title:string:default('Tweet #1'), body:text" \
	--ui="bs3" \
	--prefix="admin"
```

This command will generate:

```
app/Tweet.php
app/Http/Controllers/TweetController.php

database/migrations/201x_xx_xx_xxxxxx_create_tweets_table.php
database/seeds/TweetTableSeeder.php

resources/views/layout.blade.php
resources/views/tweets/index.blade.php
resources/views/tweets/show.blade.php
resources/views/tweets/edit.blade.php
resources/views/tweets/create.blade.php
```

After don't forget to run:


```
php artisan migrate
```
## Custom stub
Create a new folder inside scaffold/ ** your view name ** // default is bs3

:thought_balloon: **Send us your ideas.** (creating issues)

##Collaborators
Package baseda na package:

https://github.com/laralib/l5scaffold
