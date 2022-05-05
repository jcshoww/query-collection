<?php

namespace Jcshoww\QueryCollection\Query;

use Jcshoww\QueryCollection\Builder\Builder;

/**
 * Class OrWhere
 * 
 * Class describes basic where object for queries extended with "or"
 * 
 * @property string $field
 * @property string $comparison
 * @property mixed $value
 * 
 * @author jcshow
 * @package Jcshoww\QueryCollection\Query
 */
class OrWhere extends Query
{
    /**
     * Basic where query
     * 
     * @var Where
     */
    public $basicWhereQuery;

    /**
     * Basic where query required
     * @NOTE this realization created to provide extended operators support without duplications by OrWheres and without external parameters = many ifs
     * 
     * @param Where $basicWhereQuery
     */
    public function __construct(Where $basicWhereQuery)
    {
        $this->basicWhereQuery = $basicWhereQuery;
    }

    /**
     * @return OrWhere
     */
    public function apply(Builder $builder): Query
    {
        $builder->orWhere($this->getKey(), $this->getValue(), $this->getComparison());
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return $this->basicWhereQuery->getKey();
    }

    /**
     * {@inheritDoc}
     */
    public function getValue()
    {
        return $this->basicWhereQuery->getValue();
    }

    /**
     * Function returns query's comparison
     * 
     * @return mixed
     */
    public function getComparison()
    {
        return $this->basicWhereQuery->getComparison();
    }
}