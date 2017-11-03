<?php
namespace common\components;

class Helper
{

    public static function out($x)
    {
        echo '<pre>';
        print_r($x);
        echo '</pre>';
    }



}