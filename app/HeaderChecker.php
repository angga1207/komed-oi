<?php

namespace App;

trait HeaderChecker
{
    public function checkHeader($request)
    {
        if ($request->header('SecretKey') == null) {
            return false;
        } else if ($request->header('SecretKey') != env('SECRET_KEY')) {
            return false;
        } else if ($request->header('SecretKey') == env('SECRET_KEY')) {
            return true;
        }
    }
}
