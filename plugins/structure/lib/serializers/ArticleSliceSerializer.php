<?php

use FriendsOfREDAXO\Headless\Serializer;


class ArticleSliceSerializer extends Serializer
{

    /**
     * @param rex_article_slice $object
     *
     * @return array
     */
    public function toArray($object): array
    {
        $result['id'] = $object->getId();
        $result['module'] = $object->getModuleId();
        for ($i = 1; $i <= 20; $i++) {
            $this->addValueToResult($result, 'values', $i, $object->getValue($i));
            $this->addValueToResult($result, 'media', $i, $object->getMedia($i));
            $this->addValueToResult($result, 'mediaList', $i, $object->getMediaList($i), true);
            $this->addValueToResult($result, 'link', $i, $object->getLink($i));
            $this->addValueToResult($result, 'linkList', $i, $object->getLinkList($i), true);
        }
        return $result;
    }

    private function addValueToResult(array &$result, string $type, int $idx, $value, bool $isList = false)
    {
        if ($value) {
            if (array_key_exists($type, $result)) {
                $result[$type] = [];
            }
            if ($isList) {
                $value = array_filter(explode(',', $value));
            }
            $result[$type][$idx] = $value;
        }
    }

}
