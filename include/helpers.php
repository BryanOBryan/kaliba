<?php

if(!function_exists('format_date')){
    
    function format_date($date){
        $timestamp = strtotime($date);
        return date('d M Y', $timestamp);
    }
}

if(!function_exists('format_number')){
    
    function format_number($number){
        return number_format($number);
    }
}

