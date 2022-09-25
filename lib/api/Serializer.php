<?php

namespace FriendsOfREDAXO\Headless;

abstract class Serializer
{

    private static $serializers = [];

    public abstract function toArray($object): array;

    public static function register(string $class, Serializer $serializer): void
    {
        self::$serializers[$class] = $serializer;
    }

    public static function getSerializer(string $class): ?Serializer
    {
        return self::$serializers[$class] ?? null;
    }

    public static function serializeToArray($object): array
    {
        $serializer = self::getSerializer(get_class($object));
        if($serializer) {
            return $serializer->toArray($object);
        }
        return [];
    }
}
