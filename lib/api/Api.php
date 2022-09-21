<?php


abstract class Api extends rex_api_function
{
    protected $published = true;

    public function execute()
    {
        $endpoint = rex_get('endpoint', 'string');
        if (method_exists($this, $endpoint)) {
            $reflection = new ReflectionMethod($this, $endpoint);
            $params     = $reflection->getParameters();
            $args       = [];

            foreach ($params as $param) {
                $paramName = $param->getName();
                $paramType = $param->getType();
                $paramTypeName = $paramType ? $paramType->getName() : 'string';
                $args[] = rex_request($paramName, $paramTypeName);
            }


            $data = call_user_func_array([$this, $endpoint], $args);
            rex_response::cleanOutputBuffers();
            rex_response::sendJson(['data' => $data]);
            exit;
        }
        throw new rex_api_exception('Method not found');
    }
}
