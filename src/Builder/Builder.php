<?php

namespace Jcshoww\QueryCollection\Builder;

/**
 * Class Builder
 *
 * @package Jcshoww\QueryCollection\Builder
 */
abstract class Builder
{
    public const EQUAL = 'equal';
    public const NOT_EQUAL = 'not_equal';
    public const GREATER_THEN = 'greater_then';
    public const GREATER_THEN_OR_EQUAL = 'greater_then_or_equal';
    public const LESS_THEN = 'less_then';
    public const LESS_THEN_OR_EQUAL = 'less_then_or_equal';

    public const DIRECTION_ASC = 'asc';
    public const DIRECTION_DESC = 'desc';

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
     * Set the relationships that should be loaded
     *
     * @param array $relations
     * 
     * @return Builder
     */
    abstract public function with(array $relations): Builder;

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
     * Function returns result of current query set
     * 
     * @return mixed
     */
    abstract public function get();

    /**
     * Function returns list of Where comparsions of builder
     * 
     * @return array
     */
    public function getComparsions(): array
    {
        return [
            self::EQUAL => '=',
            self::NOT_EQUAL => '!=',
            self::GREATER_THEN => '>',
            self::GREATER_THEN_OR_EQUAL => '>=',
            self::LESS_THEN => '<',
            self::LESS_THEN_OR_EQUAL => '<='
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
            self::DIRECTION_ASC => 'ASC',
            self::DIRECTION_DESC => 'DESC',
            self::GREATER_THEN => '>',
            self::GREATER_THEN_OR_EQUAL => '>=',
            self::LESS_THEN => '<',
            self::LESS_THEN_OR_EQUAL => '<='
        ];
    }
}