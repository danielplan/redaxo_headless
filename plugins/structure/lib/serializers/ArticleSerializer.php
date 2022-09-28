<?php

use FriendsOfREDAXO\Headless\Serializer;


class ArticleSerializer extends Serializer
{
    /**
     * @param rex_article $object
     *
     * @return array
     */
    public function toArray($object): array
    {
        return [
            'id'               => $object->getId(),
            'name'             => $object->getName(),
            'url'              => $object->getUrl(),
            'siteStartArticle' => $object->isSiteStartArticle(),
            'startArticle'     => $object->isStartArticle(),
            'online'           => $object->isOnline(),
        ];

    }
}
