<?php

namespace FriendsOfREDAXO\Headless;

abstract class Serializer
{

    private static array $serializers = [];

    public abstract function toArray($object): array;

    public static function register(string $class, Serializer $serializer): void
    {
        self::$serializers[$class] = $serializer;
    }

    public static function getSerializer(string $class): ?Serializer
    {
        return self::$serializers[$class] ?? null;
    }

    public static function serializeToArray($object)
    {
        if (is_array($object)) {
            $result = [];
            foreach ($object as $key => $item) {
                $result[$key] = self::serializeToArray($item);
            }
            return $result;
        }
        if (is_object($object)) {
            $serializer = self::getSerializer(get_class($object));
            if ($serializer) {
                return $serializer->toArray($object);
            }
            throw new \Exception('No serializer found for ' . get_class($object));
        }
        return $object;
    }
}
