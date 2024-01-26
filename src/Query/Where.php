<?php

namespace Jcshoww\QueryCollection\Query;

use Jcshoww\QueryCollection\Builder\Builder;
use Jcshoww\QueryCollection\Query\Condition\Basic;

/**
 * Class Where
 * 
 * Class describes basic where object for queries
 * 
 * @property string $field
 * @property string $comparison
 * @property mixed $value
 * 
 * @author jcshow
 * @package Jcshoww\QueryCollection\Query
 */
class Where extends Query
{
    const EQUAL = 'equal';
    const NOT_EQUAL = 'not_equal';
    const GREATER_THEN = 'greater_then';
    const GREATER_THEN_OR_EQUAL = 'greater_then_or_equal';
    const LESS_THEN = 'less_then';
    const LESS_THEN_OR_EQUAL = 'less_then_or_equal';
    const LIKE = 'like';
    const NOT_LIKE = 'not_like';
    const IN = 'in';
    const NOT_IN = 'not_in';

    /**
     * Field to search
     * 
     * @var string
     */
    public $field;

    /**
     * Comparison of search
     * 
     * @var mixed
     */
    public $comparison;

    /**
     * Where constructor, expects at least field and value
     * 
     * @param string $field
     * @param mixed $value
     * @param mixed $comparison
     */
    public function __construct(string $field, $value, $comparison = Basic::EQUAL)
    {
        $this->field = $field;
        $this->value = $value;
        $this->comparison = $comparison;
    }

    /**
     * @return Where
     */
    public function apply(Builder $builder): Query
    {
        $builder->where($this->field, $this->value, $this->comparison);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return $this->field;
    }

    /**
     * Function returns query's comparison
     * 
     * @return mixed
     */
    public function getComparison()
    {
        return $this->comparison;
    }
}