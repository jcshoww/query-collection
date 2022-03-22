<?php

namespace Jcshoww\QueryCollection\Query;

use Jcshoww\QueryCollection\Builder\Builder;
use Jcshoww\QueryCollection\Query\Query;

/**
 * Class OrderBy
 * 
 * Class describes basic order query
 * 
 * @property string $column
 * @property string $direction
 * 
 * @author jcshow
 * @package Jcshoww\QueryCollection\Query
 */
class OrderBy extends Query
{
    /**
     * {@inheritDoc}
     */
    protected $type = 'Order';

    /**
     * Field to search
     * 
     * @var string
     */
    public $column;

    /**
     * Direction of ordering
     * 
     * @var string
     */
    public $direction;

    /**
     * Query constructor, expects at least field
     * 
     * @param string $column
     * @param string $direction
     */
    public function __construct(string $column, string $direction = Builder::DIRECTION_ASC)
    {
        $this->column = $column;
        $this->direction = $direction;
    }

    /**
     * @return OrderBy
     */
    public function apply(Builder $builder): OrderBy
    {
        $builder->orderBy($this->column, $this->direction);
        return $this;
    }
}