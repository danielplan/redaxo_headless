<?php

namespace FriendsOfREDAXO\Headless;

use ReflectionMethod;
use rex_response;


abstract class HeadlessController
{
    public function execute()
    {
        $endpoint = rex_request('endpoint', 'string');
        if (method_exists($this, $endpoint)) {

            $args = $this->parseFunctionArgs($endpoint);

            try {
                $data = call_user_func_array([$this, $endpoint], $args);
                if (is_object($data) || is_array($data)) {
                    $data = Serializer::serializeToArray($data);
                }
                $data = [
                    'data' => ($data)
                ];
            } catch (\ApiException $e) {
                rex_response::setStatus($e->getCode());
                $data = [
                    'error' => $e->getMessage(),
                ];
            }

            rex_response::cleanOutputBuffers();
            rex_response::sendJson($data);
            exit;
        }
        throw new \Exception('Method not found');
    }

    private function parseFunctionArgs(string $endpoint): array
    {
        $reflection = new ReflectionMethod($this, $endpoint);
        $params = $reflection->getParameters();
        $requestBody = \rex_var::toArray(file_get_contents('php://input'));
        $args = [];

        foreach ($params as $param) {
            $paramName = $param->getName();
            $paramType = $param->getType();
            $paramTypeName = $paramType ? $paramType->getName() : 'string';
            $arg = null;
            if ($requestBody && array_key_exists($paramName, $requestBody)) {
                $arg = $requestBody[$paramName];
            }
            if (!$arg) {
                $arg = rex_request($paramName, $paramTypeName);
            }
            $args[] = $arg;

        }
        return $args;
    }


    public static function getController(): ?self
    {
        $controller = rex_request('headless-controller', 'string');
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
