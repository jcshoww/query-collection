<?php

namespace Jcshoww\QueryCollection\Builder;

use Jcshoww\QueryCollection\Query\Condition\Basic;
use Jcshoww\QueryCollection\Query\OrderBy;
use Jcshoww\QueryCollection\Query\Where;

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
    public function where(string $column, $value, $operator = Basic::EQUAL): Builder
    {
        $data = $this->getQuery();
        $result = [];
        foreach ($data as $element) {
            switch ($operator) {
                case Basic::EQUAL:
                    if ($element === $value) {
                        $result[] = $element;
                    }
                    break;
                case Basic::NOT_EQUAL:
                    if ($element !== $value) {
                        $result[] = $element;
                    }
                    break;
                case Basic::GREATER_THAN:
                    if ($element > $value) {
                        $result[] = $element;
                    }
                    break;
                case Basic::GREATER_THAN_OR_EQUAL:
                    if ($element >= $value) {
                        $result[] = $element;
                    }
                    break;
                case Basic::LESS_THAN:
                    if ($element < $value) {
                        $result[] = $element;
                    }
                    break;
                case Basic::LESS_THAN_OR_EQUAL:
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
    public function orderBy(string $column, string $direction): Builder
    {
        $data = $this->getQuery();
        if ($direction === OrderBy::DIRECTION_ASC) {
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