<?php
namespace Tvce;

class PathTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function constructShouldConfigureTheAttributes()
    {
        $path = new Path([1, 2]);
        $this->assertAttributeSame([1,2], 'values', $path);
    }

    /**
     * @test
     */
    public function methodBuildShouldReturnPathAsString()
    {
        $path = new Path(['url', '/path']);
        $this->assertEquals('url/path', $path->build());
    }
}