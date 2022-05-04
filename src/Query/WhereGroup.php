<?php

namespace Jcshoww\QueryCollection\Query;

use Jcshoww\QueryCollection\Builder\Builder;
use Jcshoww\QueryCollection\Query\Query;
use Jcshoww\QueryCollection\QueryCollection;

/**
 * Class WhereGroup
 * 
 * Class describes query for groupping another queries
 * 
 * @property QueryCollection $subqueries
 * 
 * @author jcshow
 * @package Jcshoww\QueryCollection\Query
 */
class WhereGroup extends Query
{
    /**
     * Subqueries, should be appended to group
     * 
     * @var QueryCollection
     */
    protected $subqueries;

    /**
     * @param QueryCollection $subqueries
     */
    public function __construct(QueryCollection $subqueries)
    {
        $this->subqueries = $subqueries;
    }

    /**
     * @return WhereGroup
     */
    public function apply(Builder $builder): Query
    {
        $subqueries = $this->subqueries;
        $builder->group($subqueries);
        return $this;
    }

    /**
     * Function returns query's subqueries
     * 
     * @return QueryCollection
     */
    public function getSubqueries(): QueryCollection
    {
        return $this->subqueries;
    }
}