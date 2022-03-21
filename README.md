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

class Join extends Query
{
    /**
     * {@inheritDoc}
     */
    protected $type = 'Join';

    /**
     * table name
     * 
     * @var string
     */
    protected $table;

    /**
     * owner column to join
     * 
     * @var string
     */
    protected $ownerColumn;
    
    /**
     * foreign column to join
     * 
     * @var string
     */
    protected $foreignColumn;

    /**
     * @param string $table
     * @param string $ownerColumn
     * @param string $foreignColumn
     */
    public function __construct(string $table, string $ownerColumn, string $foreignColumn)
    {
        $this->table = $table;
        $this->ownerColumn = $ownerColumn;
        $this->foreignColumn = $foreignColumn;
    }

    /**
     * @param LaravelBuilder $builder
     * 
     * @return Query
     */
    public function apply(Builder $builder): Query
    {
        $builder->join($this->table, $this->ownerColumn, Where::EQUAL, $this->foreignColumn);
        return $builder;
    }
}

```

Create your own custom builder objects and extend them from Builder class to customize finishing operations between queries and applied system itself.