<?php
namespace App\Models;

use App\Entities\Model_Barber;

/**
 * @Table(name="Barbers")
 * @HasLifecycleCallbacks
 * @Entity 
 */
class Barber extends Model_Barber
{

}