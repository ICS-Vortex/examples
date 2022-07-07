<?php

namespace App\Service;

class UrlService
{
    /**
     * @param string $url
     * @param array $parameters
     * @return string
     */
    public function getFullUrl($url, $parameters){
        if(empty($parameters)){
            return $url;
        }
        $queryString = "?";
        foreach($parameters as $key => $value){
            $queryString .= $key.'='.$value.'&';
        }
        $queryString = substr($queryString, 0, -1);
        return $url.$queryString;
    }
}