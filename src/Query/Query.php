<?php

namespace Jcshoww\QueryCollection\Query;

use Jcshoww\QueryCollection\Builder\Builder;

/**
 * Class Query
 *
 * @package Jcshoww\QueryCollection\Query
 */
abstract class Query
{
    /**
     * Basic query type
     * 
     * @var string
     */
    protected $type = 'Filter';

    /**
     * Basic query value
     */
    protected $value;

    /**
     * Function applies query action to builder
     * 
     * @param Builder $builder
     * 
     * @return Builder
     */
    abstract public function apply(Builder $builder): Builder;

    /**
     * Function returns query key name
     * 
     * @return mixed
     */
    public function getKey()
    {
        return preg_replace('/\\/', '_', get_class($this));
    }

    /**
     * Function returns query value
     * 
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Function returns query type
     * 
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Function set query type
     * 
     * @param string $type
     * 
     * @return void
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * Function determines if query key equals to one of passed keys
     * 
     * @param array $keys
     * 
     * @return bool
     */
    public function is(array $keys): bool
    {
        return in_array($this->getKey(), $keys);
    }

    /**
     * Convert query to array format.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [$this->getKey() => $this->getValue()];
    }
}