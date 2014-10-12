hypem-php
=========

[![Build Status](https://travis-ci.org/artemio/hypem-php.svg?branch=master)](https://travis-ci.org/artemio/hypem-php)

A PHP wrapper for the hypem.com public API

##Composer
`$ composer require artemio/hypem`

##Quick Start and Examples
```php
require '../vendor/autoload.php';

use \Hypem\Playlist as Playlist;
use \Hypem\Track as Track;
use \Hypem\User as User;

// available Playlist static methods
$latest  = Playlist::latest('noremix')->get();
$popular = Playlist::popular()->get(3); // provide page number
$artist  = Playlist::artist('Placebo')->get();
$blog    = Playlist::blog(16746)->get(); // "Cruel Rythm" blog_id
$tags    = Playlist::tags('electro house')->get();
$search  = Playlist::search('Woodkid Iron')->get();

// get track by id
$track = new Track('264xq');

// get tracks of specific user
$user            = new User('username');
$feed            = $user->feed();
$favorites       = $user->favorites(5); // provide page number
$history         = $user->history();
$obsessed        = $user->obsessed();
$friendFavorites = $user->friendFavorites();
$friendObsessed  = $user->friendObsessed(2);
```

###Available filters for latest playlist
* `all` (default)
* `fresh`
* `remix`
* `noremix`

###Available filters for popular playlist
* `now` (default)
* `lastweek`
* `remix`
* `noremix`
* `artists`
* `twitter`
