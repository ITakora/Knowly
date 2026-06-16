<?php

function getEmbedUrl($url) {

    preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $url, $match);
    $youtube_id = $match[1];

    if ($youtube_id) {
        return "https://www.youtube.com/embed/" . $youtube_id;
    }
    return $url;
}

?>


