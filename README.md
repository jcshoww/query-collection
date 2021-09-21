# Query collection

This is query collection library. Query collection could be used as storage-collection for different Query objects, that could be used for DB/API/etc queries dynamic building and manipulation.

## Installation

You can install the package via composer:

```bash
composer require jcshoww/query-collection
```

## Usage

Create your own query collection and extend it from base QueryCollection class. Add custom basic collection generation in __construct of custom collection.

Look example below:

```bash

...
use Jcshoww\QueryCollection\QueryCollection;

class TestQueryCollection extends QueryCollection
{
    /**
     * {@inheritDoc}
     */
    public function __construct(array $fields = [])
    {
        $this->push(new CustomFilter('status', $fields['status']));
    }
}
```

Create your own custom query objects and extend them from Query class.
Look example below:

```bash

...
use Jcshoww\QueryCollection\Builder\Builder;
use Jcshoww\QueryCollection\Query\Query;

class Where extends Query
{
    public const EQUAL = '=';
    public const NOT_EQUAL = '!=';
    public const GREATER_THEN = '>';
    public const GREATER_THEN_OR_EQUAL = '>=';
    public const LESS_THEN = '<';
    public const LESS_THEN_OR_EQUAL = '<=';
    public const LIKE = 'LIKE';
    public const ILIKE = 'ILIKE';
    public const POSIX = '~';
    public const POSIX_INSENSITIVE = '~*';

    /**
     * Field to search
     * 
     * @var string|Expression
     */
    public $field;

    /**
     * Expected value of filtering field
     * 
     * @var mixed
     */
    public $value;

    /**
     * Comparsion of search
     * 
     * @var string
     */
    public $comparsion;

    /**
     * Filter constructor, expects at least field and value
     * 
     * @param string|Expression $field
     * @param mixed $value
     * @param string $comparsion
     */
    public function __construct($field, $value, string $comparsion = self::EQUAL)
    {
        $this->field = $field;
        $this->value = $value;
        $this->comparsion = $comparsion;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Builder $builder): Builder
    {
        $query = $builder->getQuery();
        $query->where($this->field, $this->comparsion, $this->value);
        return $builder;
    }
}

```

Create your own custom builder objects and extend them from Builder class if you need customize or extend set of parameters, passed to Queries apply fuction.