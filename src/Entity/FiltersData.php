<?php

namespace App\Entity;

class FiltersData {
    /**
     * list of categories
     *
     * @var Entity
     */
    private $categories;

    /**
     * min price
     *
     * @var int|null
     */
    private $min;

    /**
     * max price
     *
     * @var int|null
     */
    private $max;

    /**
     * Promo or not 
     *
     * @var bool
     */
    private $promo;


    /**
     * Get list of categories
     *
     * @return  Entity
     */ 
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set list of categories
     *
     * @param  Entity  $categories  list of categories
     *
     * @return  self
     */ 
    public function setCategories(Entity $categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Get min price
     *
     * @return  int|null
     */ 
    public function getMin()
    {
        return $this->min;
    }

    /**
     * Set min price
     *
     * @param  int|null  $min  min price
     *
     * @return  self
     */ 
    public function setMin($min)
    {
        $this->min = $min;

        return $this;
    }

    /**
     * Get max price
     *
     * @return  int|null
     */ 
    public function getMax()
    {
        return $this->max;
    }

    /**
     * Set max price
     *
     * @param  int|null  $max  max price
     *
     * @return  self
     */ 
    public function setMax($max)
    {
        $this->max = $max;

        return $this;
    }

    /**
     * Get promo or not
     *
     * @return  bool
     */ 
    public function getPromo()
    {
        return $this->promo;
    }

    /**
     * Set promo or not
     *
     * @param  bool  $promo  Promo or not
     *
     * @return  self
     */ 
    public function setPromo(bool $promo)
    {
        $this->promo = $promo;

        return $this;
    }
}