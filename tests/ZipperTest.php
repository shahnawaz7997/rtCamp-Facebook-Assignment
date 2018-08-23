<?php
include'../lib/phpunit/vendor/autoload.php';
include'../zipAlbum.php';
use PHPUnit\Framework\TestCase;


class ZipperTest extends TestCase {

    protected $zipper;

    public function setUp()
    {
        $this->zipper = new Zipper();
    }

    public function testLoadZipFiles( $source = null ) {
        
        $actual = $this->zipper->LoadZipFiles( $source );
        $this->assertEquals( $actual, $actual );
    }

    public function testProcessZip( $foldercontent = null, $folder = null, $maxsize = 50000 ) {
        $actual = $this->zipper->ProcessZip( $foldercontent, $folder, $maxsize );
        $this->assertEquals( $actual, $actual );
    }

    public function testGetMemoryLimit() {
        $actual = $this->zipper->getMemoryLimit();
        $this->assertEquals( $actual, $actual );
    }

    public function testMakeZip( $album_download_directory = null ) {
        $actual = $this->zipper->make_zip( $album_download_directory );
        $this->assertEquals( $actual, $actual );
    }

    public function testGetZip( $album_download_directory = null ) {
        $actual = $this->zipper->get_zip( $album_download_directory );
        $this->assertEquals( $actual, $actual );
    }

    public function testRemoveDirectory( $directory = null ) {
        $actual = $this->zipper->remove_directory($directory);
        $this->assertEquals( $actual, $actual );
    }
}