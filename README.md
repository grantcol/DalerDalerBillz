# DalerDalerBillz
CS 310 Team 8
## General Repo Etiquette
1. Create your own branch and work from there. Run all unit tests in this branch. 
2. Don't push to the master branch unless you've notified at least one other person. 
3. Push relevant commit messages. Or something semi useful.
4. Use issues as a means of communicating long-standing issues you'd like to share with the team. This way we can all chip in.

## Tools/APIs
To test the project locally I would suggest using [MAMP](http://www.mamp.info/en/) which is free and pretty common for testing PHP/MySQL apps. It's pretty straight forward to set up and run. 
Along with [The Echonest](http://developer.echonest.com/) and [LyricFind](http://developer.echonest.com/sandbox/lyricfind.html) APIs, we will also be using the [Spotify Web Api](https://developer.spotify.com/web-api) for the artist searching, images and song names since they have a neat [/search endpoint](https://developer.spotify.com/web-api/search-item/) which makes things much easier for autocomplete and general search queries. Check the linked docs if you have any questions about querying the provided endpoints. 

## Repo Organization 
Should be pretty simple. Just keep secondary HTML, js and PHP files in their respective folders. Leave index.html at the root for easy access. Comment your code briefly. Cleanup dead files and leftovers before merges etc. 

## Testing 

### White box 
White box testing will be straightforward, run of the mill, unit testing. Test all the methods (including the constructor) of each class. Assertions need not be too extensive. Simple assertEquals should do in most cases since our object are reasonably dumb containers without much logic. See `ArtistTest.php` for a simple example on the structure and layout (Pay attention to the Note: comment at the head of the class' implementation!). 

For request.php, the file which contains the majority of the logic code in it's three parsing functions (xxParseResponse) a bit more creativity must be put into the tests but it shouldn't be too complicated. As for the query functions (xxQuery..) and the factory functions (getParser, getQuery) simple equality assertions should be sufficient. Feed the function an arbitrary input (consider a dataProvider function!) and assert the equality. See for examples and write further tests into `RequestTest.php`.
We will be using [PHPUnit](https://phpunit.de/index.html) for our unit testing framework so download the stable release and make sure it's set up properly on your dev machine. Refer to the [docs](https://phpunit.de/manual/current/en/phpunit-book.pdf) for answers to questions. 

**NOTE:** PHPUnit, once installed can be used from the commandline via the `phpunit` [command](https://phpunit.de/manual/current/en/textui.html). Test using this interface. Since we have a /tests dir simply use `$ phpunit tests` to run all the available tests. This nice cli makes it no less convienient to speperate tests into appropriately named files as opposed to one monolithic test source file; so please do so.  

###Black box
Black box testing will, as of this writing, not be implemented with cucumber. Rather we will be writing these tests literaly in the form of sequential instructions for the client to run through with clear verification criteria. For example: 

1. Navigate to [url here]
2. Verify that [url] displays "LyricsCloud" and an input
3. Input an artist name 
4. Verify that the dropdown materializes
etc...
There should be a test for each page transition and visible requirement. 