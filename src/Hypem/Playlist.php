<?php
namespace Hypem;

class Playlist
{
    const PATH_TEMPLATE = '/playlist/{{type}}/{{filter}}/json/{{page}}';

    const TYPE_LATEST          = 'latest';
    const TYPE_POPULAR         = 'popular';
    const TYPE_ARTIST          = 'artist';
    const TYPE_BLOG            = 'blog';
    const TYPE_TAGS            = 'tags';
    const TYPE_SEARCH          = 'search';
    const TYPE_TRACK           = 'track';
    const TYPE_FEED            = 'feed';
    const TYPE_LOVED           = 'loved';
    const TYPE_HISTORY         = 'history';
    const TYPE_OBSESSED        = 'obsessed';
    const TYPE_PEOPLE          = 'people';
    const TYPE_PEOPLE_OBSESSED = 'people_obsessed';

    const FILTER_ALL      = 'all';
    const FILTER_FRESH    = 'fresh';
    const FILTER_REMIX    = 'remix';
    const FILTER_NOREMIX  = 'noremix';
    const FILTER_NOW      = 'now';
    const FILTER_LASTWEEK = 'lastweek';
    const FILTER_ARTISTS  = 'artists';
    const FILTER_TWITTER  = 'twitter';

    public static $latest_filters = [
        self::FILTER_ALL,
        self::FILTER_FRESH,
        self::FILTER_REMIX,
        self::FILTER_NOREMIX
    ];

    public static $popular_filters = [
        self::FILTER_NOW,
        self::FILTER_LASTWEEK,
        self::FILTER_REMIX,
        self::FILTER_NOREMIX,
        self::FILTER_ARTISTS,
        self::FILTER_TWITTER
    ];

    private $type;
    private $filter;
    private $page;

    private function __construct($type, $filter)
    {
        $this->type = $type;
        $this->filter = $filter;
    }

    public static function latest($filter = self::FILTER_ALL)
    {
        if (!in_array($filter, self::$latest_filters)) {
            throw new \InvalidArgumentException('Wrong filter for latest playlist: ' . $filter);
        }

        return new self(self::TYPE_LATEST, $filter);
    }

    public static function popular($filter = self::FILTER_NOW)
    {
        if (!in_array($filter, self::$popular_filters)) {
            throw new \InvalidArgumentException('Wrong filter for popular playlist: ' . $filter);
        }

        return new self(self::TYPE_POPULAR, $filter);
    }

    public static function artist($name)
    {
        return new self(self::TYPE_ARTIST, $name);
    }

    public static function blog($id)
    {
        return new self(self::TYPE_BLOG, $id);
    }

    public static function tags($tag)
    {
        return new self(self::TYPE_TAGS, $tag);
    }

    public static function search($filter)
    {
        return new self(self::TYPE_SEARCH, $filter);
    }

    public static function track($id)
    {
        return new self(self::TYPE_TRACK, $id);
    }

    public static function feed($name)
    {
        return new self(self::TYPE_FEED, $name);
    }

    public static function loved($name)
    {
        return new self(self::TYPE_LOVED, $name);
    }

    public static function history($name)
    {
        return new self(self::TYPE_HISTORY, $name);
    }

    public static function obsessed($name)
    {
        return new self(self::TYPE_OBSESSED, $name);
    }

    public static function people($name)
    {
        return new self(self::TYPE_PEOPLE, $name);
    }

    public static function people_obsessed($name)
    {
        return new self(self::TYPE_PEOPLE_OBSESSED, $name);
    }

    public function getType()
    {
        return $this->type;
    }

    public function getFilter()
    {
        return $this->filter;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function setPage($page)
    {
        $this->page = $page;
    }

    public function buildPath()
    {
        return strtr(self::PATH_TEMPLATE, [
            '{{type}}'   => $this->type,
            '{{filter}}' => $this->filter,
            '{{page}}'   => $this->page
        ]);
    }

    public function getData($page)
    {
        $this->setPage($page);
        $data = (new Request)->getJson($this->buildPath());
        unset($data['version']);
        return (array)$data;
    }

    public function get($page = 1)
    {
        return array_map(function($datum) {
            return new Track($datum);
        }, $this->getData($page));
    }
}
