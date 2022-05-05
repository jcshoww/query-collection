<?php

namespace Jcshoww\QueryCollection\Builder;

use Exception;
use Jcshoww\QueryCollection\Query\OrderBy;
use Jcshoww\QueryCollection\Query\Where;
use Jcshoww\QueryCollection\QueryCollection;

/**
 * Class Builder
 *
 * @package Jcshoww\QueryCollection\Builder
 */
abstract class Builder
{
    /**
     * Query builder entity
     * 
     * @var mixed
     */
    protected $query;

    /**
     * Default builder constructor
     * 
     * @param mixed $query
     */
    public function __construct($query)
    {
        $this->setQuery($query);
    }

    /**
     * @return mixed
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param mixed $query
     * 
     * @return Builder
     */
    public function setQuery($query): Builder
    {
        $this->query = $query;
        return $this;
    }

    /**
     * Method for where query
     * 
     * @param string $column
     * @param mixed $value
     * @param mixed $operator
     * 
     * @return Builder
     */
    abstract public function where(string $column, $value, $operator): Builder;

    /**
     * Method for where query with "or". Default functionality is just repeat where
     * 
     * @param string $column
     * @param mixed $value
     * @param mixed $operator
     * 
     * @return Builder
     */
    public function orWhere(string $column, $value, $operator): Builder
    {
        $this->where($column, $value, $operator);
        return $this;
    }

    /**
     * Method for raw where query. Throws exception by default
     * 
     * @param mixed $raw
     * @param array $values
     * 
     * @return Builder
     * @throws Exception
     */
    public function whereRaw($raw, array $values = []): Builder
    {
        throw new Exception("Query is not provided by the builder");
        return $this;
    }

    /**
     * Set the ordering of query
     *
     * @param string $column
     * @param string $direction
     * 
     * @return Builder
     */
    abstract public function orderBy(string $column, string $direction): Builder;

    /**
     * Set the limit and offset for rows
     *
     * @param int $limit
     * @param int $offset
     * 
     * @return Builder
     */
    abstract public function paginate(int $limit = 50, int $offset = 0): Builder;

    /**
     * Process group of related queries
     *
     * @param QueryCollection $subqueries
     * 
     * @return Builder
     */
    public function group(QueryCollection $subqueries): Builder
    {
        foreach ($subqueries as $subquery) {
            $subquery->apply($this);
        }
        return $this;
    }

    /**
     * Function returns result of current query set
     * 
     * @return mixed
     */
    abstract public function get();

    /**
     * Function returns list of Where comparisons of builder
     * 
     * @return array
     */
    public function getComparisons(): array
    {
        return [
            Where::EQUAL => '=',
            Where::NOT_EQUAL => '!=',
            Where::GREATER_THEN => '>',
            Where::GREATER_THEN_OR_EQUAL => '>=',
            Where::LESS_THEN => '<',
            Where::LESS_THEN_OR_EQUAL => '<=',
            Where::LIKE => 'LIKE',
            Where::NOT_LIKE => 'NOT LIKE',
            Where::IN => 'IN',
            Where::NOT_IN => 'NOT IN',
        ];
    }

    /**
     * Function returns list of Where ordering directions of builder
     * 
     * @return array
     */
    public function getDirections(): array
    {
        return [
            OrderBy::DIRECTION_ASC => 'ASC',
            OrderBy::DIRECTION_DESC => 'DESC',
        ];
    }

    /**
     * Function returns comparison builder-related operator by passed comparison variable
     * 
     * @param mixed $comparison
     * 
     * @return mixed
     */
    public function parseComparison($comparison)
    {
        $comparisons = $this->getComparisons();
        if (isset($comparisons[$comparison]) === false) {
            throw new Exception("Builder does not provide passed comparison");
        }

        return $comparisons[$comparison];
    }

    /**
     * Function returns builder-related direction by passed direction variable
     * 
     * @param mixed $direction
     * 
     * @return mixed
     */
    public function parseDirection($direction)
    {
        $directions = $this->getDirections();
        if (isset($directions[$direction]) === false) {
            throw new Exception("Builder does not provide passed direction");
        }

        return $directions[$direction];
    }
}