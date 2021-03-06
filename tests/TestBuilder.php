<?php


namespace Gedcomx\Tests;

use Faker\Factory;

class TestBuilder
{
    protected static $faker;

    public static function faker(){
        if( self::$faker == null ){
            self::$faker = Factory::create();
        }

        return self::$faker;
    }
    
    public static function seed($seed){
        self::faker()->seed($seed);
    }
}