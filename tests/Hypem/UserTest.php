<?php
namespace Hypem;

/**
 * @coversDefaultClass User
 */
class UserTest extends \PHPUnit_Framework_TestCase
{
    public function methodsProvider()
    {
        return [
            ['feed'],
            ['favorites'],
            ['history'],
            ['obsessed'],
            ['friendFavorites'],
            ['friendObsessed']
        ];
    }

    /**
     * @covers ::__construct
     */
    public function testObjectCanBeConstructed()
    {
        $user = new User('username');
        $this->assertInstanceOf('Hypem\User', $user);
        return $user;
    }

    /**
     * @depends testObjectCanBeConstructed
     */
    public function testNameCanBeSetOnInstantiation($user)
    {
        $this->assertAttributeNotEmpty('name', $user);
    }

    /**
     * @covers       ::feed
     * @covers       ::favorites
     * @covers       ::history
     * @covers       ::obsessed
     * @covers       ::friendFavorites
     * @covers       ::friendObsessed
     * @uses         Playlist
     * @depends      testObjectCanBeConstructed
     * @dataProvider methodsProvider
     */
    public function testTracksCanBeFetched($method, $user)
    {
        $tracks = $user->$method();

        // tracks are fetched as array
        $this->assertInternalType('array', $tracks);

        // tracks contains only Track instances
        $this->assertContainsOnlyInstancesOf('Hypem\Track', $tracks);
    }
}
