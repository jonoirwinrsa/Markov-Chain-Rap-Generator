<?php
// Let's include our PHP Simple HTML DOM Parser.
include('simple_html_dom.php');

/*
* Retrive list of albums from artist's page.
*
* @param string $artist Name of the artist.
* @return array Discography data (name, link)
*/
function album_list( $artist ){
	$html = file_get_html( 'http://genius.com/artists/' . $artist );
	
	foreach($html->find('ul.album_list li a') as $e){
		$albums[ ] = array( "name" =>  $e->plaintext, 
			"link" => "http://genius.com" . $e->href );
	}
	
	return $albums;
}

/*
* Retrive tracklist from album's page.
*
* @param string $url Url of album on Genius.
* @return array Returns array with tracklist and "id", "link", "title", "artist" for every song.
*/
function tracklist( $url ){

	$html = file_get_html( $url );
	
	foreach( $html->find('ul.song_list li') as $e ){
	
		$id = $e->getAttribute( "data-id" );
		$link = "" . $e->find( "a", 0 )->href;
		$title = trim( $e->find( "span.song_title", 0 )->plaintext );
		$artist = trim( $e->find( "span.artist_name", 0 )->plaintext );
		
		$songs[] = array( "id" => $id, "link" => $link, "title" => $title, "artist" => $artist );
	}
	
	return $songs;
}

/*
* Retrive lyrics of song and info about it.
*
* @param string $url Url of song.
* @param bool $info Set to false if you want only lyrics.
* @return array/string Data of song: artist, title, genre, tags (array: name, link), featurings (array: name, link), producers (array: name, link), lyrics
*/
function lyrics( $url, $info = TRUE ){

	$html = file_get_html( $url );
	
	if( $info === TRUE ){
	
		/* Primary info */
		$primaryInfo = $html->find( ".song_info_primary", 0 );
		$title = trim( $primaryInfo->find( ".text_title", 0 )->plaintext );
		
		// Info about artist
		$artistInfo = $primaryInfo->find( ".text_artist", 0 );
		$artist = array( "name" => trim( $artistInfo->plaintext ), "link" => $artistInfo->getAttribute( "itemid" ) );
		
		// Info about feats
		$featInfo = $primaryInfo->find( ".featured_artists", 0 );

		if( $featInfo->plaintext !== NULL ){
			foreach( $featInfo->find( "a" ) as $feat ){
				$feats[] = array( "name" => trim( $feat->plaintext ), "link" => "http://genius.com" . $feat->href );
			}
		} else {
			$feats[] = false;
		}
		
		// Info about producers
		$producerInfo = $primaryInfo->find( ".producer_artists", 0 );
		
		if( $producerInfo->plaintext !== NULL ){
			foreach( $producerInfo->find( "a" ) as $producer ){
				$producers[] = array( "name" => trim( $producer->plaintext ), "link" => "http://genius.com" . $producer->href );
			}
		} else {
			$producers[] = false;
		}
		
		/* Secondary info */
		$secondaryInfo = $html->find( ".meta_secondary", 0 );
		
		// Tags and genre
		$tagsInfo = $secondaryInfo->find( ".tags", 0 );
		
		if( $tagsInfo->plaintext !== NULL ){
			foreach( $tagsInfo->find( "a" ) as $tag ){
				if( $tag->getAttribute( "itemprop" ) == "genre" )
					$genre = $tag->plaintext;
				else
					$tags[] = array( "name" => trim( $tag->plaintext ), "link" => "http://genius.com" . $tag->href );
			}
		} else {
			$tags[] = false;
		}
		
		// Date
		$dateInfo = $secondaryInfo->find( ".release_date",  0 )->plaintext;
		
		if( is_null( $dateInfo ) )
			$dateInfo = false;
		
		// Lyrics
		$lyrics = trim( $html->find( "div.lyrics", 0 )->plaintext );
		
		$return = array(
			"artist" => $artist,
			"title" => $title,
			"genre" => $genre,
			"date" => $dateInfo,
			"tags" => $tags,
			"featurings" => $feats,
			"producers" => $producers,
			"lyrics" => $lyrics
		);
	
	} else { 
		$return = trim( $html->find( "div.lyrics", 0 )->plaintext );
	}

	return $return;
}

/*
* Get lyrics by song ID, alias for lyrics function.
*
* @param string $url Url of song.
* @param bool $info Set to false if you want only lyrics.
* @return array/string Data of song: artist, title, genre, tags (array: name, link), featurings (array: name, link), producers (array: name, link), lyrics
*/
function lyricsByID( $ID, $info = TRUE ){
	return lyrics( "http://genius.com/songs/" . $ID );
}
?>
