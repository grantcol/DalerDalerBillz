<?php 

require '../php/core.php';

class ArtistTest extends PHPUnit_Framework_TestCase
{
	//Note: Opting not to test 2 of 3 functions (getSongId and getFrequency) because
	//these functions are simply one level of abstraction from the song class 
	//thus, testing getFrequency and getId in Song() will be sufficient 
	public function testConstruct() {
		$artist = null;
		$this->assertEquals($artist, null);

		$artist = new Artist("testArtist");
		$this->assertEquals($artist->mName, "testArtist");
	}

	/**
	 * @dataProvider songProvider
	 */
	public function testSetSongs($songSet) {
		$artist = new Artist("testName");
		$this->assertEquals($artist->mSongs, "");

		$artist->setSongs($songSet);
		$this->assertEquals($artist->mSongs, $songSet);
	}

	//Data Providers

	//This provider can be reused in the SongTest class
	public function songProvider() {
		return array(
			array( new Song("song0", "artist0", "0"), new Song("song1", "artist1", "1"), new Song("song2", "artist2", "2") ),
			array( new Song("song3", "artist3", "3"), new Song("song4", "artist4", "4"), new Song("song5", "artist5", "5") ),
			array( new Song("song6", "artist6", "6"), new Song("song7", "artist7", "7"), new Song("song8", "artist8", "8") ),
		);
	}
}

?>