<?php

namespace Inc;

class Activate{
    public static function activate(){
        //create database
        flush_rewrite_rules();

    }
}