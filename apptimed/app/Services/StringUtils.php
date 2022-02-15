<?php

namespace App\Services;

class StringUtils{

    public function randomStringGenerator($name, $id){
        $randomSting = strtolower($name);//Convert to lowercase
        $randomSting = preg_replace('/[^a-zA-Z0-9_ ]/s','',$randomSting);//Remove special charactors
        $randomSting = preg_replace('/\s+/', ' ',$randomSting);//combine multiple spaces into one
        $randomSting = str_replace(' ', '-', $randomSting); // Replaces all spaces with hyphens.
        
        if (strlen($randomSting) > 15) {
            $randomSting = substr($randomSting, 0, 15);//Limit to 15 chars
        }
        if ($randomSting[strlen($randomSting)-1] != "-") {
            $randomSting = $randomSting . "-";
        }
        $randomSting = $randomSting . $id;//Add id
        $randomSting = $randomSting.self::getRandomString(25 - strlen($randomSting));//Add random string in the last of the string
        
        return $randomSting;
    }

    function getRandomString($no_of_chars) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
      
        for ($i = 0; $i < $no_of_chars; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
      
        return $randomString;
    }
}
