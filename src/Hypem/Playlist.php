<?php
namespace Hypem;

class Playlist
{
    use SingleRequest;

    const TYPE_LATEST   = 'latest';
    const TYPE_POPULAR  = 'popular';
    const TYPE_ARTIST   = 'artist';
    const TYPE_BLOG     = 'blog';
    const TYPE_TAGS     = 'tags';
    const TYPE_SEARCH   = 'search';

    const FILTER_ALL      = 'all';
    const FILTER_FRESH    = 'fresh';
    const FILTER_REMIX    = 'remix';
    const FILTER_NOREMIX  = 'noremix';
    const FILTER_NOW      = 'now';
    const FILTER_LASTWEEK = 'lastweek';
    const FILTER_ARTISTS  = 'artists';
    const FILTER_TWITTER  = 'twitter';

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

    private static $path_template = '/playlist/{{type}}/{{filter}}/json/{{page}}';

    private $type;
    private $filter;

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

    public function get($page = 1)
    {
        $path = strtr(self::$path_template, [
            '{{type}}'    => $this->type,
            '{{filter}}'  => $this->filter,
            '{{page}}'    => $page
        ]);

        $response = $this->getRequest()->getJson(Request::BASE_URI . $path);

        foreach ($response as &$track) {
            $track = new Track($track);
        }

        return $response;
    }
}
