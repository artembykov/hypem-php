<?php
namespace Hypem;

/**
 * @coversDefaultClass Track
 */
class TrackTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers            ::__construct
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionIsRaisedForInvalidConstructorParam()
    {
        new Track(5423);
    }

    /**
     * @covers ::__construct
     * @uses   ::setProperties
     */
    public function testObjectCanBeConstructedFromArray()
    {
        $track = new Track([]);
        $this->assertInstanceOf('Hypem\Track', $track);
        return $track;
    }

    /**
     * @covers ::__construct
     * @uses   ::setProperties
     */
    public function testObjectCanBeConstructedFromString()
    {
        $track = new Track('id');
        $this->assertInstanceOf('Hypem\Track', $track);
        return $track;
    }

    /**
     * @covers  ::getProperties
     * @uses    Playlist::track
     * @depends testObjectCanBeConstructedFromArray
     */
    public function testPropertiesAreFetchedAsArray1($track)
    {
        $this->assertInternalType('array', $track->getProperties());
    }

    /**
     * @covers  ::getProperties
     * @uses    Playlist::track
     * @depends testObjectCanBeConstructedFromString
     */
    public function testPropertiesAreFetchedAsArray2($track)
    {
        $this->assertInternalType('array', $track->getProperties());
    }
}
