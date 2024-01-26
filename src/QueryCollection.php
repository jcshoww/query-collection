<?php

namespace Jcshoww\QueryCollection;

use ArrayAccess;
use Iterator;
use Jcshoww\QueryCollection\Builder\Builder;
use Jcshoww\QueryCollection\Query\Condition\Basic;
use Jcshoww\QueryCollection\Query\OrderBy;
use Jcshoww\QueryCollection\Query\OrWhere;
use Jcshoww\QueryCollection\Query\Pagination;
use Jcshoww\QueryCollection\Query\Query;
use Jcshoww\QueryCollection\Query\Where;
use Jcshoww\QueryCollection\Query\WhereGroup;
use Jcshoww\QueryCollection\Query\WhereRaw;
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
    const PAGENAV_DEFAULT_OFFSET = 0;

    const PAGENAV_DEFAULT_LIMIT = 50;

    /**
     * Class of default query object
     */
    protected $queryDefaultClass = Where::class;

    /**
     * @var int
     */
    protected $position = 0;

    /**
     * @var Query[]
     */
    protected $queries = [];

    /**
     * Default constructor appends every array field=>value as new filter with equal comparison
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
            $result[] = $query;
        }

        return $result;
    }

    /**
     * Push query onto the end of the collection
     *
     * @param Query $filter
     * 
     * @return self
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
     * @return self
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
    public function first(string $field)
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

    /**
     * Function performs apply to set of queries
     *
     * @param Builder $builder
     *
     * @return mixed
     */
    public function apply(Builder $builder)
    {
        foreach ($this->queries as $query) {
            $query->apply($builder);
        }
        return $builder->get();
    }

    /**
     * Function creates new collection instance
     * 
     * @return static
     */
    public static function create(): self
    {
        return new static();
    }

    /**
     * Function assigns new Where filter with equal comparison
     * 
     * @param string $field
     * @param mixed $value
     * @param bool $or
     * 
     * @return static
     */
    public function equal(string $field, $value, bool $or = false): self
    {
        $query = new Where($field, $value);
        if ($or === true) {
            $query = new OrWhere($query);
        }
        return $this->push($query);
    }

    /**
     * Function assigns new Where filter with not-equal comparison
     * 
     * @param string $field
     * @param mixed $value
     * @param bool $or
     * 
     * @return static
     */
    public function notEqual(string $field, $value, bool $or = false): self
    {
        $query = new Where($field, $value, Basic::NOT_EQUAL);
        if ($or === true) {
            $query = new OrWhere($query);
        }
        return $this->push($query);
    }

    /**
     * Function assigns new Where filter with greater then comparison
     * 
     * @param string $field
     * @param mixed $value
     * @param bool $or
     * 
     * @return static
     */
    public function greaterThen(string $field, $value, bool $or = false): self
    {
        $query = new Where($field, $value, Basic::GREATER_THAN);
        if ($or === true) {
            $query = new OrWhere($query);
        }
        return $this->push($query);
    }

    /**
     * Function assigns new Where filter with greater then comparison
     * 
     * @param string $field
     * @param mixed $value
     * @param bool $or
     * 
     * @return static
     */
    public function greaterThan(string $field, $value, bool $or = false): self
    {
        $query = new Where($field, $value, Basic::GREATER_THAN);
        if ($or === true) {
            $query = new OrWhere($query);
        }
        return $this->push($query);
    }

    /**
     * Function assigns new Where filter with "greater then or equal" comparison
     * 
     * @param string $field
     * @param mixed $value
     * @param bool $or
     * 
     * @return static
     */
    public function greaterThenOrEqual(string $field, $value, bool $or = false): self
    {
        $query = new Where($field, $value, Basic::GREATER_THAN_OR_EQUAL);
        if ($or === true) {
            $query = new OrWhere($query);
        }
        return $this->push($query);
    }

    /**
     * Function assigns new Where filter with "greater then or equal" comparison
     * 
     * @param string $field
     * @param mixed $value
     * @param bool $or
     * 
     * @return static
     */
    public function greaterThanOrEqual(string $field, $value, bool $or = false): self
    {
        $query = new Where($field, $value, Basic::GREATER_THAN_OR_EQUAL);
        if ($or === true) {
            $query = new OrWhere($query);
        }
        return $this->push($query);
    }

    /**
     * Function assigns new Where filter with "less then" comparison
     * 
     * @param string $field
     * @param mixed $value
     * @param bool $or
     * 
     * @return static
     */
    public function lessThen(string $field, $value, bool $or = false): self
    {
        $query = new Where($field, $value, Basic::LESS_THAN);
        if ($or === true) {
            $query = new OrWhere($query);
        }
        return $this->push($query);
    }

    /**
     * Function assigns new Where filter with "less then" comparison
     * 
     * @param string $field
     * @param mixed $value
     * @param bool $or
     * 
     * @return static
     */
    public function lessThan(string $field, $value, bool $or = false): self
    {
        $query = new Where($field, $value, Basic::LESS_THAN);
        if ($or === true) {
            $query = new OrWhere($query);
        }
        return $this->push($query);
    }

    /**
     * Function assigns new Where filter with "less then or equal" comparison
     * 
     * @param string $field
     * @param mixed $value
     * @param bool $or
     * 
     * @return static
     */
    public function lessThenOrEqual(string $field, $value, bool $or = false): self
    {
        $query = new Where($field, $value, Basic::LESS_THAN_OR_EQUAL);
        if ($or === true) {
            $query = new OrWhere($query);
        }
        return $this->push($query);
    }

    /**
     * Function assigns new Where filter with "less then or equal" comparison
     * 
     * @param string $field
     * @param mixed $value
     * @param bool $or
     * 
     * @return static
     */
    public function lessThanOrEqual(string $field, $value, bool $or = false): self
    {
        $query = new Where($field, $value, Basic::LESS_THAN_OR_EQUAL);
        if ($or === true) {
            $query = new OrWhere($query);
        }
        return $this->push($query);
    }

    /**
     * Function assigns new Where filter with "like" comparison
     * 
     * @param string $field
     * @param mixed $value
     * @param bool $or
     * 
     * @return static
     */
    public function like(string $field, $value, bool $or = false): self
    {
        $query = new Where($field, $value, Basic::LIKE);
        if ($or === true) {
            $query = new OrWhere($query);
        }
        return $this->push($query);
    }

    /**
     * Function assigns new Where filter with "not like" comparison
     * 
     * @param string $field
     * @param mixed $value
     * @param bool $or
     * 
     * @return static
     */
    public function notLike(string $field, $value, bool $or = false): self
    {
        $query = new Where($field, $value, Basic::NOT_LIKE);
        if ($or === true) {
            $query = new OrWhere($query);
        }
        return $this->push($query);
    }

    /**
     * Function assigns new Where filter with IN comparison
     * 
     * @param string $field
     * @param mixed $value
     * @param bool $or
     * 
     * @return static
     */
    public function in(string $field, $values, bool $or = false) : QueryCollection
    {
        $query = new Where($field, $values, Basic::IN);
        if ($or === true) {
            $query = new OrWhere($query);
        }
        return $this->push($query);
    }

    /**
     * Function assigns new Where filter with NOT IN comparison
     * 
     * @param string $field
     * @param mixed $value
     * @param bool $or
     * 
     * @return static
     */
    public function notIn(string $field, $values, bool $or = false) : QueryCollection
    {
        $query = new Where($field, $values, Basic::NOT_IN);
        if ($or === true) {
            $query = new OrWhere($query);
        }
        return $this->push($query);
    }

    /**
     * Function assigns new OrderBy filter with ASC direction
     * 
     * @param string $column
     * 
     * @return static
     */
    public function orderBy(string $column) : QueryCollection
    {
        return $this->push(new OrderBy($column, OrderBy::DIRECTION_ASC));
    }

    /**
     * Function assigns new OrderBy filter with DESC direction
     * 
     * @param string $column
     * 
     * @return static
     */
    public function orderByDesc(string $column) : QueryCollection
    {
        return $this->push(new OrderBy($column, OrderBy::DIRECTION_DESC));
    }

    /**
     * Function assigns new Pagination query
     * 
     * @param int $limit
     * @param int $offset
     * 
     * @return static
     */
    public function paginate(int $limit = self::PAGENAV_DEFAULT_LIMIT, int $offset = self::PAGENAV_DEFAULT_OFFSET) : QueryCollection
    {
        return $this->push(new Pagination($limit, $offset));
    }

    /**
     * Function assigns new WhereGroup filter
     * 
     * @param QueryCollection $group
     * 
     * @return static
     */
    public function group(QueryCollection $group) : QueryCollection
    {
        return $this->push(new WhereGroup($group));
    }

    /**
     * Function assigns new WhereRaw filter
     * 
     * @param mixed $raw
     * @param array $bindings
     * 
     * @return static
     */
    public function raw($raw, array $bindings = []) : QueryCollection
    {
        return $this->push(new WhereRaw($raw, $bindings));
    }
}