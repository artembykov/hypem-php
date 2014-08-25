<?php
namespace Hypem;

class User
{
    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function feed($page = 1)
    {
        return Playlist::feed($this->name)->get($page);
    }

    public function favorites($page = 1)
    {
        return Playlist::loved($this->name)->get($page);
    }

    public function history($page = 1)
    {
        return Playlist::history($this->name)->get($page);
    }

    public function obsessed($page = 1)
    {
        return Playlist::obsessed($this->name)->get($page);
    }

    public function friendFavorites($page = 1)
    {
        return Playlist::people($this->name)->get($page);
    }

    public function friendObsessed($page = 1)
    {
        return Playlist::people_obsessed($this->name)->get($page);
    }
}
