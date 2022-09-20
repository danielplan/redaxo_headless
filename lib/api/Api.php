<?php


abstract class Api extends rex_api_function
{
    protected $published = true;

    public function execute()
    {
        $method = rex_get('method', 'string');
        if (method_exists($this, $method)) {
            $reflection = new ReflectionMethod($this, $method);
            $params     = $reflection->getParameters();
            $args       = [];

            foreach ($params as $param) {
                $paramName = $param->getName();
                $paramType = $param->getType()->getName();
                $args[]    = rex_request($paramName, $paramType);
            }


            $data = call_user_func_array([$this, $method], $args);
            rex_response::cleanOutputBuffers();
            rex_response::sendJson(
                ['data' => $data]
            );
            exit;
        }
        throw new rex_api_exception('Method not found');
    }
}
