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
    public function __construct(string $field, $value, $comparsion = Builder::EQUAL)
    {
        $this->field = $field;
        $this->value = $value;
        $this->comparsion = $comparsion;
    }

    /**
     * {@inheritDoc}
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