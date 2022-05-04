<?php

namespace Jcshoww\QueryCollection\Query;

use Jcshoww\QueryCollection\Builder\Builder;
use Jcshoww\QueryCollection\Query\Query;

/**
 * Class Pagination
 * 
 * Class describes pagination settings for query
 * 
 * @property int $limit
 * @property int $offset
 * 
 * @author jcshow
 * @package Jcshoww\QueryCollection\Query
 */
class Pagination extends Query
{
    /**
     * {@inheritDoc}
     */
    protected $type = 'Pagination';

    /**
     * Number of rows to get
     * 
     * @var int
     */
    public $limit;

    /**
     * Number of rows to skip
     * 
     * @var int
     */
    public $offset;

    /**
     * Filter constructor
     * 
     * @param int $page
     * @param int $limit
     */
    public function __construct(int $limit = 50, int $offset = 0)
    {
        $this->limit = $limit;
        $this->offset = $offset;
    }

    /**
     * @return Pagination
     */
    public function apply(Builder $builder): Query
    {
        $builder->paginate($this->limit, $this->offset);
        return $this;
    }

    /**
     * Function returns query's limit
     * 
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * Function returns query's offset
     * 
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }
}