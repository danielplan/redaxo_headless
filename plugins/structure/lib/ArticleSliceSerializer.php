<?php

use FriendsOfREDAXO\Headless\Serializer;


class ArticleSliceSerializer extends Serializer
{

    public function toArray($object): array
    {
        $result['id']     = $object->getId();
        $result['module'] = $object->getModuleId();
        for ($i = 1; $i <= 20; $i++) {
            if ($object->getValue($i)) {
                $result['value_' . $i] = $object->getValue($i);
            }
            if ($object->getMedia($i)) {
                $result['media_' . $i] = $object->getMedia($i);
            }
            if ($object->getMediaList($i)) {
                $result['mediaList_' . $i] = explode(',', $object->getMediaList($i));
            }
            if ($object->getLink($i)) {
                $result['link_' . $i] = $object->getLink($i);
            }
            if ($object->getLinkList($i)) {
                $result['linkList_' . $i] = $object->getLinkList($i);
            }
        }
        return $result;
    }

}
