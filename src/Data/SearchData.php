<?php

namespace App\Data;

use App\Entity\Category;

Class SearchData{

    /**
     * @var Category [];
     */
    public $categories = [];

    /**
     * @var null|integer
    */
    public $min;

    /**
    * @var null|integer
    */
    public $max;

    /**
     * @var boolean
     */
    public $promo = false;


}