<?php

namespace FriendsOfREDAXO\Headless;

use ReflectionMethod;
use rex_response;


abstract class HeadlessController
{
    public function execute()
    {
        $endpoint = rex_get('endpoint', 'string');
        if (method_exists($this, $endpoint)) {
            $reflection = new ReflectionMethod($this, $endpoint);
            $params = $reflection->getParameters();
            $args = [];

            foreach ($params as $param) {
                $paramName = $param->getName();
                $paramType = $param->getType();
                $paramTypeName = $paramType ? $paramType->getName() : 'string';
                $args[] = rex_request($paramName, $paramTypeName);
            }


            $data = call_user_func_array([$this, $endpoint], $args);
            $data = static::serializeObject($data);

            rex_response::cleanOutputBuffers();
            rex_response::sendJson(['data' => $data]);
            exit;
        }
        throw new \Exception('Method not found');
    }

    public static function serializeObject($object): array
    {
        if (is_object($object)) {
            return Serializer::serializeToArray($object);
        }
        if (is_array($object) && count($object) > 0 && is_object($object[0])) {
            $result = [];
            foreach ($object as $item) {
                $result[] = self::serializeObject($item);
            }
            return $result;
        }
        return $object;
    }


    public static function getController(): ?self
    {
        $controller = rex_get('headless-controller', 'string');
        if ($controller) {
            $controller = ucfirst($controller);
            $class = $controller . 'Controller';
            if (class_exists($class)) {
                return new $class();
            }
            throw new \Exception('Controller not found');
        }
        return null;
    }

    /**
     * @throws \Exception
     */
    public static function start(): void
    {
        $controller = self::getController();
        if ($controller) {
            $controller->execute();
            exit;
        }
    }
}
