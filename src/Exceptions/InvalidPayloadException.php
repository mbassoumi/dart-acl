<?php


namespace Dart\ACL\Exceptions;


use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class InvalidPayloadException extends Exception
{

    public function render(Request $request)
    {
        $message = !empty($this->getMessage()) ? $this->getMessage() : 'Wrong Payload form token';
        if ($request->wantsJson()) {
            return response()->json(['message' => $message], Response::HTTP_FORBIDDEN);
        }
        return "<h1>{$this->getMessage()}</h1>";
    }
}
