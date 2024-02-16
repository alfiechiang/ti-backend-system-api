<?php

namespace App\Exceptions;

use Exception;

class ResponseException extends Exception
{
    public function render($request)
    {       
        
        return response()->json(["code" => 500, "message" => $this->getMessage(),"data"=>[]]);       
    }
}
