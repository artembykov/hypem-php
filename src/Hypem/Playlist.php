<?php
namespace Hypem;

class Playlist
{
    const PATH_TEMPLATE = '/playlist/{{type}}/{{filter}}/json/{{page}}';

    const TYPE_LATEST   = 'latest';
    const TYPE_POPULAR  = 'popular';
    const TYPE_ARTIST   = 'artist';
    const TYPE_BLOG     = 'blog';
    const TYPE_TAGS     = 'tags';
    const TYPE_SEARCH   = 'search';
    const TYPE_TRACK    = 'track';

    const FILTER_ALL        = 'all';
    const FILTER_FRESH      = 'fresh';
    const FILTER_REMIX      = 'remix';
    const FILTER_NOREMIX    = 'noremix';
    const FILTER_NOW        = 'now';
    const FILTER_LASTWEEK   = 'lastweek';
    const FILTER_ARTISTS    = 'artists';
    const FILTER_TWITTER    = 'twitter';

    private static $latest_filters = [
        self::FILTER_ALL,
        self::FILTER_FRESH,
        self::FILTER_REMIX,
        self::FILTER_NOREMIX
    ];

    private static $popular_filters = [
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

    public static function loved(User $user)
    {
        return new self(self::);
    }

    public function getData($page)
    {
        $this->page = $page;
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

    public function buildPath()
    {
        return strtr(self::PATH_TEMPLATE, [
            '{{type}}'      => $this->type,
            '{{filter}}'    => $this->filter,
            '{{page}}'      => $this->page
        ]);
    }
}
