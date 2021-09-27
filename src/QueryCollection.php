<?php

namespace Jcshoww\QueryCollection;

use ArrayAccess;
use Iterator;
use Jcshoww\QueryCollection\Query\ArrayQuery;
use Jcshoww\QueryCollection\Query\Query;
use OutOfBoundsException;
use RuntimeException;

/**
 * Class QueryCollection
 * 
 * Collection of queries
 * 
 * @author jcshow
 * @package Jcshoww\QueryCollection
 */
class QueryCollection implements ArrayAccess, Iterator
{
    public const PAGENAV_DEFAULT_OFFSET = 0;

    public const PAGENAV_DEFAULT_LIMIT = 50;

    /**
     * Class of default query object
     */
    protected $queryDefaultClass = ArrayQuery::class;

    /**
     * @var int
     */
    protected $position = 0;

    /**
     * @var Query[]
     */
    protected $queries = [];

    /**
     * Default constructor appends every array field=>value as new filter with equal comparsion
     * 
     * @param array $fields
     */
    public function __construct(array $fields = [])
    {
        $this->fill($fields);
    }

    /**
     * Function fills collection by passed data
     *
     * @param array $fields
     *
     * @return array
     */
    public function fill(array $fields = []): array
    {
        foreach ($fields as $field => $value) {
            $this->queries[] = new $this->queryDefaultClass($field, $value);
        }
        return $this->queries;
    }

    /**
     * Get all of the items in the collection.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->queries;
    }

    /**
     * Convert all collection's queries to array format.
     *
     * @return array
     */
    public function toArray(): array
    {
        $result = [];
        foreach ($this->queries as $query) {
            $result = array_merge($result, $query->toArray());
        }

        return $result;
    }

    /**
     * Push query onto the end of the collection
     *
     * @param Query $filter
     * 
     * @return $this
     */
    public function push(Query $filter): self
    {
        $this->queries[] = $filter;

        return $this;
    }

    /**
     * Set query value instead of exists or add new
     *
     * @param Query $query
     * 
     * @return $this
     */
    public function set(Query $query): self
    {
        $key = $this->find($query->getKey());
        if ($key !== null) {
            $this->queries[$key] = $query;
        } else {
            $this->push($query);
        }

        return $this;
    }

    /**
     * Get and remove the last item from the collection.
     *
     * @return Query|null
     */
    public function pop()
    {
        return array_pop($this->queries);
    }

    /**
     * Get the model for the specified key.
     *
     * @param string|int $key
     * 
     * @return Query
     * @throws OutOfBoundsException if the key does not exist.
     */
    public function get($key): Query
    {
        if (! $this->has($key)) {
            throw new OutOfBoundsException("The key '$key' does not exist");
        }

        return $this->queries[$key];
    }

    /**
     * Check if the specified key exists.
     *
     * @param mixed $key
     * 
     * @return bool
     */
    public function has($key): bool
    {
        return array_key_exists($key, $this->queries);
    }

    /**
     * Check if the specified key exists.
     *
     * @param mixed $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->has($key);
    }

    /**
     * Read the specified key.
     *
     * @param mixed $key
     * @return Query
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * Set the value for the specified key.
     *
     * @param mixed $key
     * @param mixed $value
     * @throws RuntimeException
     */
    public function offsetSet($key, $value)
    {
        $class = get_class($this);

        throw new RuntimeException("Unable to set key '$key': $class is immutable");
    }

    /**
     * Unset the value at the specified index.
     *
     * @param mixed $key
     * @throws RuntimeException
     */
    public function offsetUnset($key)
    {
        $class = get_class($this);

        throw new RuntimeException("Unable to unset key '$key': $class is immutable");
    }

    /**
     * Method returns current item
     * 
     * @return Query
     */
    public function current(): Query
    {
        return $this->queries[$this->position];
    }

    /**
     * Method returns current item key
     * 
     * @return int
     */
    public function key(): int
    {
        return $this->position;
    }
    
    /**
     * Method increase current item key
     * 
     * @return void
     */
    public function next(): void
    {
        $this->position++;
    }

    /**
     * Method rewind item key
     * 
     * @return void
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * Method checks if item exists by key
     * 
     * @return bool
     */
    public function valid(): bool
    {
        return isset($this->queries[$this->position]) === true;
    }

    /**
     * Method return first query by passed field
     * 
     * @param string $field
     * 
     * @return Query|null
     */
    public function first(string $field): ?Query
    {
        $key = $this->find($field);
        if ($key === null) {
            return $key;
        }

        return $this->queries[$key];
    }

    /**
     * Method get queries by field name(s) and remove it from collection
     * 
     * @param array|string $field
     * 
     * @return array
     */
    public function extract($fields): array
    {
        if (is_array($fields) === false) {
            $fields = [$fields];
        }

        $result = [];
        foreach ($this->queries as $key => $query) {
            if ($query->is($fields) === true) {
                $result[] = $query;
                unset($this->queries[$key]);
            }
        }

        $this->queries = array_values($this->queries);

        return $result;
    }

    /**
     * Method determines by passed key if filter already exists and return its key
     * 
     * @param string|int $field
     * 
     * @return mixed
     */
    public function find($field)
    {
        foreach ($this->queries as $key => $query) {
            if ($query->is([$field]) === true) {
                return $key;
            }
        }

        return null;
    }

    /**
     * Function remove filters by types
     * second parameter used for marks that we need remove all except passed types, passed types otherwise
     * 
     * @param array $types
     * @param bool $except
     * 
     * @return self
     */
    public function exclude(array $types, bool $except = false): self
    {
        foreach ($this->queries as $key => $query) {
            if (in_array($query->getType(), $types) === ! $except) {
                unset($this->queries[$key]);
            }
        }

        $this->queries = array_values($this->queries);

        return $this;
    }

    /**
     * Function filters queries by passed types array
     * 
     * @param array $types
     * 
     * @return array
     */
    public function filterByTypes(array $types): array
    {
        $result = [];
        foreach ($this->queries as $query) {
            if (in_array($query->getType(), $types) === true) {
                $result[] = $query;
            }
        }

        return $result;
    }

    /**
     * Function merges collection with passed collections
     * 
     * @param QueryCollection ...$collections
     * 
     * @return self
     */
    public function merge(QueryCollection ...$collections): self
    {
        foreach ($collections as $collection) {
            foreach ($collection->all() as $element) {
                $this->push($element);
            }
        }

        return $this;
    }
}