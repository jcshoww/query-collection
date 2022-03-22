<?php

namespace Jcshoww\QueryCollection\Query;

use Jcshoww\QueryCollection\Builder\Builder;
use Jcshoww\QueryCollection\Query\Query;

/**
 * Class With
 * 
 * Class describes query to append related tables to selection
 * 
 * @property array $relations
 * 
 * @author jcshow
 * @package Jcshoww\QueryCollection\Query
 */
class With extends Query
{
    /**
     * {@inheritDoc}
     */
    protected $type = 'With';

    /**
     * Set of required-to-join relations with fields to select
     * 
     * @var array
     */
    protected $relations;

    /**
     * Where constructor, expects at least field and value
     * 
     * @param array $relations
     */
    public function __construct(array $relations)
    {
        $this->relations = $relations;
    }

    /**
     * @return With
     */
    public function apply(Builder $builder): Query
    {
        $builder->with($this->relations);
        return $this;
    }
}