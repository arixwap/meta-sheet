<?php

session_start();

if ( ! isset($_POST['url']) ) die('403');

// Identify session for generate file cache
//

// Get url data from input form
$urlList = $_POST['url'];
if ( ! is_array($urlList) ) $urlList = array($urlList);

// Loop URL for fetch metadata
foreach ( $urlList as $url ) {

    // Check if var is valid URL
    if ( filter_var($url, FILTER_VALIDATE_URL) ) {

        $data = array(
            'url' => $url
        );

        // Get external content with curl
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $data['url']);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        $content = curl_exec($curl);
        curl_close($curl);

        // Parsing content into html
        $dom = new DOMDocument();
        @$dom->loadHTML($content);

        // Get html locale lang
        $nodes = $dom->getElementsByTagName('html');
        $data['html_lang'] = $nodes->item(0)->getAttribute('lang');

        // Get canonical
        $tags = $dom->getElementsByTagName('link');
        for ( $i = 0; $i < $tags->length; $i++ ) {

            $tag = $tags->item($i);

            if ( $tag->getAttribute('rel') == 'canonical' ) {
                $data['canonical'] = $tag->getAttribute('href');
            }
        }

        // Get html head title
        $nodes = $dom->getElementsByTagName('title');
        $data['title'] = trim($nodes->item(0)->nodeValue);

        // Get all meta tags
        $tags = $dom->getElementsByTagName('meta');
        for ( $i = 0; $i < $tags->length; $i++ ) {

            $tag = $tags->item($i);

            // Get meta name description & robots
            if ( $tag->getAttribute('name') != '' ) {
                $data[ $tag->getAttribute('name') ] = trim($tag->getAttribute('content'));
            }

            // Get meta og:
            if ( strpos($tag->getAttribute('property'), 'og:') !== false ) {
                $data[ $tag->getAttribute('property') ] = trim($tag->getAttribute('content'));
            }
        }

        // Write into JSON
        echo json_encode($data);

    } else {

        die('Invalid URL: ' . $url);

    }

}

?>
