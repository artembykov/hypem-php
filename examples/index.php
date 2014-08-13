<?php

require '../vendor/autoload.php';

use \Hypem\Playlist as Playlist;
use \Hypem\Track as Track;

$track = (new Track('2614n'))->get();
dd($track);

$latest   = Playlist::latest('noremix')->get();
$popular  = Playlist::popular()->get(3);
$artist   = Playlist::artist('Placebo')->get();
$blog     = Playlist::blog(16746)->get(); //"Cruel Rythm" blog_id
$tags     = Playlist::tags('electro house')->get();
$search   = Playlist::search('Woodkid Iron')->get();
