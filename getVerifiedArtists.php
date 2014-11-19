<?php
// Let's include our PHP Simple HTML DOM Parser.
include('simple_html_dom.php');

/*
* Retrive list of artists
*
* @param string $artist Name of the artist.
* @return array Discography data (name, link)
*/
	for($x=1; $x < 500; $x++){
		$html = file_get_html( 'http://genius.com/verified-artists?page='.$x );
		//echo 'http://genius.com/artists/?page='.$x ;
		
		foreach($html->find('div#main div.user_details a.login') as $e){
			$line =  $e->plaintext.PHP_EOL;
			file_put_contents("verifiedArtistsList.txt", $line, FILE_APPEND);
		}
	}
?>