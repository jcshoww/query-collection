<?php

namespace Jcshoww\QueryCollection\Query;

use Jcshoww\QueryCollection\Builder\Builder;

/**
 * Class ArrayQuery
 * 
 * Class describes basic array object filter functionality
 * 
 * @author jcshow
 * @package Jcshoww\QueryCollection\Query
 */
class ArrayQuery extends Query
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
     * @var string
     */
    public $comparsion;

    /**
     * Where constructor, expects at least field and value
     * 
     * @param string $field
     * @param mixed $value
     */
    public function __construct(string $field, $value)
    {
        $this->field = $field;
        $this->value = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Builder $builder): Builder
    {
        $data = $builder->getQuery();
        $data[$this->field] = $this->value;
        $builder->setQuery($data);
        return $builder;
    }

    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return $this->field;
    }
}