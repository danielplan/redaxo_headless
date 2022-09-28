<?php

class NotFoundException extends ApiException
{

    public function __construct(string $message = 'Not Found')
    {
        parent::__construct($message, 404);
    }
}
