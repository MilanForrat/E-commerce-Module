<?php
namespace App\Data;

Class SearchData{

    /**
     * @var string
     */
    public $q = '';

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