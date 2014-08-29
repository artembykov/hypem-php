<?php
namespace Hypem;

/**
 * @coversDefaultClass Playlist
 */
class PlaylistTest extends \PHPUnit_Framework_TestCase
{
    protected static $constants = [
        'PATH_TEMPLATE'        => '/playlist/{{type}}/{{filter}}/json/{{page}}',
        'TYPE_LATEST'          => 'latest',
        'TYPE_POPULAR'         => 'popular',
        'TYPE_ARTIST'          => 'artist',
        'TYPE_BLOG'            => 'blog',
        'TYPE_TAGS'            => 'tags',
        'TYPE_SEARCH'          => 'search',
        'TYPE_TRACK'           => 'track',
        'TYPE_FEED'            => 'feed',
        'TYPE_LOVED'           => 'loved',
        'TYPE_HISTORY'         => 'history',
        'TYPE_OBSESSED'        => 'obsessed',
        'TYPE_PEOPLE'          => 'people',
        'TYPE_PEOPLE_OBSESSED' => 'people_obsessed',
        'FILTER_ALL'           => 'all',
        'FILTER_FRESH'         => 'fresh',
        'FILTER_REMIX'         => 'remix',
        'FILTER_NOREMIX'       => 'noremix',
        'FILTER_NOW'           => 'now',
        'FILTER_LASTWEEK'      => 'lastweek',
        'FILTER_ARTISTS'       => 'artists',
        'FILTER_TWITTER'       => 'twitter'
    ];

    protected static function getFullClassName($name)
    {
        return __NAMESPACE__ . '\\' . $name;
    }

    protected static function getLatestFilters()
    {
        return [
            self::$constants['FILTER_ALL'],
            self::$constants['FILTER_FRESH'],
            self::$constants['FILTER_REMIX'],
            self::$constants['FILTER_NOREMIX']
        ];
    }

    protected static function getPopularFilters()
    {
        return [
            self::$constants['FILTER_NOW'],
            self::$constants['FILTER_LASTWEEK'],
            self::$constants['FILTER_REMIX'],
            self::$constants['FILTER_NOREMIX'],
            self::$constants['FILTER_ARTISTS'],
            self::$constants['FILTER_TWITTER']
        ];
    }

    public function latestFiltersProvider()
    {
        return array_map(function($filter) {
            return [$filter];
        }, self::getLatestFilters());
    }

    public function popularFiltersProvider()
    {
        return array_map(function($filter) {
            return [$filter];
        }, self::getPopularFilters());
    }

    public function namedConstructorsProvider()
    {
        return [
            [self::$constants['TYPE_LATEST'], self::$constants['FILTER_ALL']],
            [self::$constants['TYPE_POPULAR'], self::$constants['FILTER_NOW']],
            [self::$constants['TYPE_ARTIST'], 'Placebo'],
            [self::$constants['TYPE_BLOG'], 16746],
            [self::$constants['TYPE_TAGS'], 'electro house'],
            [self::$constants['TYPE_SEARCH'], 'Woodkid Iron'],
            [self::$constants['TYPE_TRACK'], '264xq'],
            [self::$constants['TYPE_FEED'], 'username'],
            [self::$constants['TYPE_LOVED'], 'username'],
            [self::$constants['TYPE_HISTORY'], 'username'],
            [self::$constants['TYPE_OBSESSED'], 'username'],
            [self::$constants['TYPE_PEOPLE'], 'username'],
            [self::$constants['TYPE_PEOPLE_OBSESSED'], 'username']
        ];
    }

    public function testConstants()
    {
        $reflector = new \ReflectionClass(self::getFullClassName('Playlist'));
        $this->assertEquals(self::$constants, $reflector->getConstants());
    }

    public function testLatestFilters()
    {
        $this->assertEquals(self::getLatestFilters(), Playlist::$latest_filters);
    }

    public function testPopularFilters()
    {
        $this->assertEquals(self::getPopularFilters(), Playlist::$popular_filters);
    }

    /**
     * @covers            ::latest
     * @covers            ::__construct
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionIsRaisedForInvalidLatestFilter()
    {
        Playlist::latest(null);
    }

    /**
     * @covers            ::popular
     * @covers            ::__construct
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionIsRaisedForInvalidPopularFilter()
    {
        Playlist::popular(null);
    }

    /**
     * @covers       ::latest
     * @covers       ::__construct
     * @dataProvider latestFiltersProvider
     */
    public function testObjectCanBeConstructedForValidLatestFilter($filter)
    {
        $playlist = Playlist::latest($filter);
        $this->assertInstanceOf(self::getFullClassName('Playlist'), $playlist);
    }

    /**
     * @covers       ::popular
     * @covers       ::__construct
     * @dataProvider popularFiltersProvider
     */
    public function testObjectCanBeConstructedForValidPopularFilter($filter)
    {
        $playlist = Playlist::popular($filter);
        $this->assertInstanceOf(self::getFullClassName('Playlist'), $playlist);
    }

    /**
     * @covers       ::artist
     * @covers       ::blog
     * @covers       ::tags
     * @covers       ::search
     * @covers       ::track
     * @covers       ::feed
     * @covers       ::loved
     * @covers       ::history
     * @covers       ::obsessed
     * @covers       ::people
     * @covers       ::people_obsessed
     * @covers       ::__construct
     * @dataProvider namedConstructorsProvider
     */
    public function testObjectCanBeConstructed($type, $filter)
    {
        $playlist = Playlist::$type($filter);
        $this->assertInstanceOf(self::getFullClassName('Playlist'), $playlist);
    }

    /**
     * @covers       ::setPage
     * @dataProvider namedConstructorsProvider
     */
    public function testPageCanBeSet($type, $filter)
    {
        $playlist = Playlist::$type($filter);
        $playlist->setPage(1);
        $this->assertAttributeNotEmpty('page', $playlist);
    }

    /**
     * @covers       ::buildPath
     * @dataProvider namedConstructorsProvider
     */
    public function testBuildPath($type, $filter)
    {
        $playlist = Playlist::$type($filter);
        $playlist->setPage(1);
        $path = $playlist->buildPath();

        // path matches format
        $this->assertStringMatchesFormat('/playlist/%s/%s/json/%d', $path);

        // path is correct
        $expected = strtr(self::$constants['PATH_TEMPLATE'], [
            '{{type}}'   => $type,
            '{{filter}}' => $filter,
            '{{page}}'   => 1
        ]);
        $this->assertEquals($expected, $path);
    }

    /**
     * @covers       ::getData
     * @uses         Request
     * @uses         Request::getJson
     * @dataProvider namedConstructorsProvider
     */
    public function testGetData($type, $filter)
    {
        $playlist = Playlist::$type($filter);
        $data = $playlist->getData(1);

        // data is fetched as an array
        $this->assertInternalType('array', $data);

        // data does not contain version key
        $this->assertArrayNotHasKey('version', $data);
    }

    /**
     * @covers       ::get
     * @uses         Track
     * @dataProvider namedConstructorsProvider
     */
    public function testGet($type, $filter)
    {
        $playlist = Playlist::$type($filter);
        $tracks = $playlist->get(1);

        // tracks are returned as an array
        $this->assertInternalType('array', $tracks);

        // tracks contains only Track instances
        $this->assertContainsOnlyInstancesOf(self::getFullClassName('Track'), $tracks);
    }
}
