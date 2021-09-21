<?php

namespace Jcshoww\QueryCollection\Builder;

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
     * @return self
     */
    public function setQuery($query): self
    {
        $this->query = $query;
        return $this;
    }
}