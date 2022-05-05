<?php

namespace Jcshoww\QueryCollection\Query;

use Jcshoww\QueryCollection\Builder\Builder;

/**
 * Class WhereRaw
 * 
 * Class describes custom raw query
 * 
 * @property mixed $raw
 * @property array $bindings
 * 
 * @author jcshow
 * @package Jcshoww\QueryCollection\Query
 */
class WhereRaw extends Query
{
    /**
     * Raw query to apply
     * 
     * @var mixed
     */
    public $raw;

    /**
     * Raw query binding values
     * 
     * @var array
     */
    public $bindings = [];

    /**
     * Raw query should be passed
     * 
     * @param mixed $raw
     * @param array $bindings
     */
    public function __construct($raw, array $bindings = [])
    {
        $this->raw = $raw;
        $this->bindings = $bindings;
    }

    /**
     * @return WhereRaw
     */
    public function apply(Builder $builder): Query
    {
        $builder->whereRaw($this->getRawQuery(), $this->getBindings());
        return $this;
    }

    /**
     * Function returns raw query
     * 
     * @return mixed
     */
    public function getRawQuery()
    {
        return $this->raw;
    }

    /**
     * Function returns bindings
     * 
     * @return array
     */
    public function getBindings(): array
    {
        return $this->bindings;
    }
}