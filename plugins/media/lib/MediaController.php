<?php

use FriendsOfREDAXO\Headless\HeadlessController;

class MediaController extends HeadlessController
{
    public function image(string $name, string $mediaType): array
    {

        //todo: fix
        $url = "http://localhost:8080/index.php?rex_media_type=$mediaType&rex_media_file=$name";
        $media = rex_media::get($name);

        return [
            'url' => $url,
            'alt' => $media->getTitle(),
            'height' => $media->getHeight(),
            'width' => $media->getWidth(),
        ];
    }

}
