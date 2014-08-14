<?php
namespace Hypem;

class Track
{
    /**
    * @var string  $mediaid          Track ID
    * @var string  $artist           Artist name
    * @var string  $title            Track title
    * @var int     $dateposted       Unix timestamp (in seconds)
    * @var int     $siteid           ID of site which posted the track last
    * @var string  $sitename         Name of site which posted the track last
    * @var string  $posturl          Remote site post URL
    * @var int     $postid           Post ID
    * @var int     $loved_count      Favorites counter
    * @var int     $posted_count     Number of sites which posted the track
    * @var string  $thumb_url        Small post image
    * @var string  $thumb_url_medium Medium post image
    * @var string  $thumb_url_large  Large post image
    * @var string  $thumb_url_artist Artist image
    * @var int     $time             Track length in seconds
    * @var string  $description      Track description from remote site
    * @var array   $tags             Track tags
    * @var string  $itunes_link      Itunes URL
    */
    public $mediaid;
    public $artist;
    public $title;
    public $dateposted;
    public $siteid;
    public $sitename;
    public $posturl;
    public $postid;
    public $loved_count;
    public $posted_count;
    public $thumb_url;
    public $thumb_url_medium;
    public $thumb_url_large;
    public $thumb_url_artist;
    public $time;
    public $description;
    public $tags;
    public $itunes_link;

    private static $props_template  = '/playlist/track/{{mediaid}}/json';
    private static $key_template    = '/track/{{mediaid}}?ax=1';
    private static $link_template   = '/serve/source/{{mediaid}}/{{key}}';
    private static $key_regex       = '/<script.+?id="displayList-data">\s*(.+?)\s*<\/script>/';

    private $request;

    public function __construct($props = [])
    {
        if (is_string($props)) {
            $this->mediaid = $props;
            $props = $this->getProperties();
        }

        foreach ($props as $k => $v) {
            if (property_exists($this, $k)) {
                $this->$k = $v;
            }
        }
    }

    private function getRequest()
    {
        if (!isset($this->request)) {
            $this->request = new Request();
        }
        return $this->request;
    }

    private function getProperties()
    {
        $path = strtr(self::$props_template, ['{{mediaid}}' => $this->mediaid]);
        $response = $this->getRequest()->getJson(Request::BASE_URI . $path);
        return $response[0];
    }

    private function getKey()
    {
        $path = strtr(self::$key_template, ['{{mediaid}}' => $this->mediaid]);
        $response = $this->getRequest()->get(Request::BASE_URI . $path);

        preg_match(self::$key_regex, $response, $match);
        $data = json_decode($match[1], true);

        return $data['tracks'][0]['key'];
    }

    public function get()
    {
        $path = strtr(self::$link_template, [
            '{{mediaid}}' => $this->mediaid,
            '{{key}}'     => $this->getKey()
        ]);

        $request = $this->getRequest();

        // get json with remote song url
        $response = $request->getJson(Request::BASE_URI . $path);

        // send request and get response location header with real url of mp3
        $request->get($response['url']);

        return $request->getResponseHeader('Location');
    }
}
