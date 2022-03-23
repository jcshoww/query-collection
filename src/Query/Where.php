<?php

namespace Jcshoww\QueryCollection\Query;

use Jcshoww\QueryCollection\Builder\Builder;

/**
 * Class Where
 * 
 * Class describes basic where object for queries
 * 
 * @property string $field
 * @property string $comparsion
 * @property mixed $value
 * 
 * @author jcshow
 * @package Jcshoww\QueryCollection\Query
 */
class Where extends Query
{
    public const EQUAL = 'equal';
    public const NOT_EQUAL = 'not_equal';
    public const GREATER_THEN = 'greater_then';
    public const GREATER_THEN_OR_EQUAL = 'greater_then_or_equal';
    public const LESS_THEN = 'less_then';
    public const LESS_THEN_OR_EQUAL = 'less_then_or_equal';
    public const LIKE = 'like';
    public const NOT_LIKE = 'not_like';

    /**
     * Field to search
     * 
     * @var string
     */
    public $field;

    /**
     * Comparsion of search
     * 
     * @var mixed
     */
    public $comparsion;

    /**
     * Where constructor, expects at least field and value
     * 
     * @param string $field
     * @param mixed $value
     * @param mixed $comparsion
     */
    public function __construct(string $field, $value, $comparsion = self::EQUAL)
    {
        $this->field = $field;
        $this->value = $value;
        $this->comparsion = $comparsion;
    }

    /**
     * @return Where
     */
    public function apply(Builder $builder): Query
    {
        $builder->where($this->field, $this->value, $this->comparsion);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return $this->field;
    }
}