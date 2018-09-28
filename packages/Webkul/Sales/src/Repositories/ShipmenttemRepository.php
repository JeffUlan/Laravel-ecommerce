<?php

namespace Webkul\Sales\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;

/**
 * ShipmentItem Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class ShipmentItemRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return Mixed
     */

    function model()
    {
        return 'Webkul\Sales\Contracts\ShipmentItem';
    }
}