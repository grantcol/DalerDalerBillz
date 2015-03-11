<?php 

require 'request.php';

class RequestTest extends PHPUnit_Framework_TestCase 
{
	/**
	 * @dataProvider searchProvider
	 */
	public function testSpQuerySearchArtist( $searchStr, $expected ) {
		$this->assertEquals(spQuerySearchArtist($searchStr), $expected);
	}

	/**
	 * @dataProvider getArtistProvider()
	 */
	public function testSpGetArtistTopTracks( $artistId, $expected ) {
		$this->assertEquals(spQueryGetArtist($artistId), $expected);
	}

	/**
	 * @dataProvider azLyricsQueryProvider()
	 */
	public function testAzLyricsQuery($artist, $songName, $expFi) {
		$expected = file_get_contents($expFi);
		$provided = azlGetLyrics($artist, $songName);
		$this->assertEquals($expected, $provided);
	}

	//Data Providers

	public function searchProvider() {
		return array(
			array("drake", "https://api.spotify.com/v1/search?q=drake*&type=artist"),
			array("bon", "https://api.spotify.com/v1/search?q=bon*&type=artist"),
			array("cool", "https://api.spotify.com/v1/search?q=cool*&type=artist"),
			array("sam", "https://api.spotify.com/v1/search?q=sam*&type=artist")
		);
	}

	public function getArtistProvider() {
		return array(
			array("0TnOYISbd1XYRBk9myaseg", "https://api.spotify.com/v1/artists/0TnOYISbd1XYRBk9myaseg/top-tracks?country=US")
		);
	}

	public function azLyricsQueryProvider() {
		return array(
			array("drake", "know yourself", "knowyourself.txt"),
			array("led zeppelin", "friends", "friends.txt"),
			array("phil collins", "two worlds", "twoworlds.txt")
		);
	}
}

?>