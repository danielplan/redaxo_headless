<?php

use FriendsOfREDAXO\Headless\Serializer;


class ArticleSerializer extends Serializer
{

    public function toArray($object): array
    {
        return [
            'id'               => $object->getId(),
            'name'             => $object->getName(),
            //'url'              => $article->getUrl(),
            'siteStartArticle' => $object->isSiteStartArticle(),
            'startArticle'     => $object->isStartArticle(),
            'online'           => $object->isOnline(),
        ];

    }
}
