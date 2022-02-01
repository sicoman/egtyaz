<?php 

namespace App\Utils;

class PackagePropertiesUtil{

    const BANK          = "bank";
    const ASK_TEACHER   = "ask_teacher";
    const VIDEOS        = "videos";
    const FREE_EXAM     = "free_exam";
    const MOCK_EXAM     = 'mock_exam';
    const COURSES       = 'courses';
    const COMMUNITY     = 'community';
    const FOUNDATION    = 'foundation';

    public static function defaultProps(){
        return [
            static::BANK        => false,
            static::ASK_TEACHER => false,
            static::VIDEOS      => false,
            static::FREE_EXAM   => false,
            static::MOCK_EXAM   => false,
            static::COURSES     => false,
            static::COMMUNITY   => false,
            static::FOUNDATION  => false
        ];
    }

}