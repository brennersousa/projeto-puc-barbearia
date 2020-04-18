<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 20/12/2019
 * Time: 19:08
 */

namespace App\Support;

use Doctrine\ORM\EntityManager AS Manager;

class EntityManager
{
    /** @var Manager */
    private static $em;
    
    public function __construct(Manager $em)
    {
        self::$em = $em;
    }

    public static function getManager(): Manager
    {
        return self::$em;
    }
}