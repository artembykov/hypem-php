<?php
require '../vendor/autoload.php';

use \Hypem\Playlist as Playlist;
use \Hypem\Track as Track;
use \Hypem\User as User;

$latest  = Playlist::latest('noremix')->get();
//$popular = Playlist::popular()->get(3); // provide page number
//$artist  = Playlist::artist('Placebo')->get();
//$blog    = Playlist::blog(16746)->get(); // "Cruel Rythm" blog_id
//$tags    = Playlist::tags('electro house')->get();
//$search  = Playlist::search('Woodkid Iron')->get();

dd($latest);
