<?php
namespace Hypem;

/**
 * @coversDefaultClass Playlist
 */
class PlaylistTest extends \PHPUnit_Framework_TestCase
{
    public function latestFiltersProvider()
    {
        return [
            ['all'],
            ['fresh'],
            ['remix'],
            ['noremix']
        ];
    }

    public function popularFiltersProvider()
    {
        return [
            ['now'],
            ['lastweek'],
            ['remix'],
            ['noremix'],
            ['artists'],
            ['twitter']
        ];
    }

    public function instancesProvider()
    {
        return [
            [Playlist::latest('all')],
            [Playlist::popular('now')],
            [Playlist::artist('Placebo')],
            [Playlist::blog(16746)],
            [Playlist::tags('electro house')],
            [Playlist::search('Woodkid Iron')],
            [Playlist::track('264xq')],
            [Playlist::feed('username')],
            [Playlist::loved('username')],
            [Playlist::history('username')],
            [Playlist::obsessed('username')],
            [Playlist::people('username')],
            [Playlist::people_obsessed('username')]
        ];
    }

    /**
     * @covers            ::latest
     * @uses              ::__construct
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionIsRaisedForInvalidLatestFilter()
    {
        Playlist::latest(null);
    }

    /**
     * @covers            ::popular
     * @uses              ::__construct
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionIsRaisedForInvalidPopularFilter()
    {
        Playlist::popular(null);
    }

    /**
     * @covers       ::latest
     * @uses         ::__construct
     * @dataProvider latestFiltersProvider
     */
    public function testObjectCanBeConstructedForValidLatestFilter($filter)
    {
        $playlist = Playlist::latest($filter);
        $this->assertInstanceOf('Hypem\Playlist', $playlist);
    }

    /**
     * @covers       ::popular
     * @uses         ::__construct
     * @dataProvider popularFiltersProvider
     */
    public function testObjectCanBeConstructedForValidPopularFilter($filter)
    {
        $playlist = Playlist::popular($filter);
        $this->assertInstanceOf('Hypem\Playlist', $playlist);
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
     * @uses         ::__construct
     * @dataProvider instancesProvider
     */
    public function testObjectCanBeConstructed($instance)
    {
        $this->assertInstanceOf('Hypem\Playlist', $instance);
    }

    /**
     * @covers       ::setPage
     * @dataProvider instancesProvider
     */
    public function testPageCanBeSet($instance)
    {
        $instance->setPage(1);
        $this->assertAttributeNotEmpty('page', $instance);
    }

    /**
     * @covers       ::buildPath
     * @dataProvider instancesProvider
     */
    public function testBuildPath($instance)
    {
        $instance->setPage(1);
        $path = $instance->buildPath();

        // path matches format
        $this->assertStringMatchesFormat('/playlist/%s/%s/json/%d', $path);

        // path is correct
        $expected = strtr('/playlist/{{type}}/{{filter}}/json/{{page}}', [
            '{{type}}'   => $instance->getType(),
            '{{filter}}' => $instance->getFilter(),
            '{{page}}'   => $instance->getPage()
        ]);
        $this->assertEquals($expected, $path);
    }

    /**
     * @covers       ::getData
     * @uses         Request::getJson
     * @dataProvider instancesProvider
     */
    public function testGetData($instance)
    {
        $data = $instance->getData(1);

        // data is fetched as array
        $this->assertInternalType('array', $data);

        // data does not contain version key
        $this->assertArrayNotHasKey('version', $data);
    }

    /**
     * @covers       ::get
     * @uses         Track
     * @dataProvider instancesProvider
     */
    public function testGet($instance)
    {
        $tracks = $instance->get(1);

        // tracks are returned as an array
        $this->assertInternalType('array', $tracks);

        // tracks contains only Track instances
        $this->assertContainsOnlyInstancesOf('Hypem\Track', $tracks);
    }
}
