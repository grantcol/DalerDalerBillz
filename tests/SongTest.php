<?php 

require 'core.php';

class SongTest extends PHPUnit_Framework_TestCase
{
	//Note: Opting not to test 2 of 3 functions (getSongId and getFrequency) because
	//these functions are simply one level of abstraction from the song class 
	//thus, testing getFrequency and getId in Song() will be sufficient 
	/**
	 * @dataProvider songProvider
	 */
	public function testConstruct($name, $artist, $id) {
		$song = null;
		$this->assertEquals($song, null);

		$song = new Song($name, $artist, $id);
		$this->assertEquals($song->mName, "$name");
		$this->assertEquals($song->mArtist, "$artist");
		$this->assertEquals($song->mId, null);
	}

	/**
	 * @dataProvider lyricsProvider
	 */
	public function testSetLyrics($lyrics) {
		$song = new Song("tn", new Artist("testArtist"), null);
		$song->setLyrics($lyrics);
		$this->assertEquals($lyrics, $song->mLyrics);
	}

	//Data Providers

	//This provider can be reused in the SongTest class
	public function songProvider() {
		return array(
			array( "testName0", "testArtist0", null),
			array( "testName1", "testArtist1", null),
			array( "testName2", "testArtist2", null),
		);
	}
	
	public function lyricsProvider() {
		return array(
			array(array("hi", "bye", "low","high","coco","bread", "fall","break",
			"yolo", "selfie"))
			,array(array("blah", "prah", "main","kate","read","Frank", "blue","red",
			"flow", "grow")),
			array(array("selfish", "cry", "solo","human","clue","vein", "valid","wrong",
			"mobile", "quote"))
		);
	}
}

?>
