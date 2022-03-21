<?php

namespace Jcshoww\QueryCollection\Builder;

/**
 * Class ArrayBuilder
 * 
 * This builder could be use as example of system-related builder
 *
 * @package Jcshoww\QueryCollection\Builder
 */
class ArrayBuilder extends Builder
{
    /**
     * Query builder entity
     * 
     * @var array
     */
    protected $query = [];

    /**
     * Default builder constructor
     * 
     * @param array $query
     */
    public function __construct(array $query)
    {
        $this->setQuery($query);
    }

    /**
     * @return array
     */
    public function getQuery(): array
    {
        return $this->query;
    }

    /**
     * {@inheritDoc}
     */
    public function where(string $column, $value, $operator): Builder
    {
        $data = $this->getQuery();
        $result = [];
        foreach ($data as $element) {
            switch ($operator) {
                case self::EQUAL:
                    if ($element === $value) {
                        $result[] = $element;
                    }
                    break;
                case self::NOT_EQUAL:
                    if ($element !== $value) {
                        $result[] = $element;
                    }
                    break;
                case self::GREATER_THEN:
                    if ($element > $value) {
                        $result[] = $element;
                    }
                    break;
                case self::GREATER_THEN_OR_EQUAL:
                    if ($element >= $value) {
                        $result[] = $element;
                    }
                    break;
                case self::LESS_THEN:
                    if ($element < $value) {
                        $result[] = $element;
                    }
                    break;
                case self::LESS_THEN_OR_EQUAL:
                    if ($element <= $value) {
                        $result[] = $element;
                    }
                    break;
            }
        }

        $this->setQuery($result);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function with(array $relations): Builder
    {
        //@NOTE there is no functionality for this method in case of array processing
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function orderBy(string $column, string $direction): Builder
    {
        $data = $this->getQuery();
        if ($direction === self::DIRECTION_ASC) {
            sort($data);
        } else {
            rsort($data);
        }
        $this->setQuery($data);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function paginate(int $limit = 50, int $offset = 0): Builder
    {
        $data = $this->getQuery();
        $output = array_slice($data, $offset, $limit);
        $this->setQuery($output);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function get()
    {
        return $this->query;
    }
}