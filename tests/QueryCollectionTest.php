<?php

namespace Jcshoww\QueryCollection\Test;

use Jcshoww\QueryCollection\Query\OrderBy;
use Jcshoww\QueryCollection\Query\Pagination;
use Jcshoww\QueryCollection\Query\Where;
use Jcshoww\QueryCollection\Query\WhereGroup;
use Jcshoww\QueryCollection\QueryCollection;

/**
 * QueryCollectionTest
 * 
 * @author jcshoww
 * @package Jcshoww\QueryCollection\Test
 */
class QueryCollectionTest extends TestCase
{
    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test collection fill procedure
     */
    public function testSuccessFill()
    {
        $collection = new QueryCollection();
        $result = $collection->fill([['test' => 'test3']]);
        $this->assertNotEmpty($result);
        $this->assertInstanceOf(Where::class, $result[0]);
    }

    /**
     * Test collection all procedure
     */
    public function testSuccessAll()
    {
        $collection = new QueryCollection();
        $collection->push(new Where('test', 'test3'));
        $result = $collection->all();
        $this->assertNotEmpty($result);
        $this->assertInstanceOf(Where::class, $result[0]);
    }

    /**
     * Test collection push procedure
     */
    public function testSuccessPush()
    {
        $collection = new QueryCollection();
        $collection->push(new Where('test', 'test3'))->push(new Where('test2', 'test3'));
        $result = $collection->all();
        $this->assertNotEmpty($result);
        $this->assertInstanceOf(Where::class, $result[0]);
        $this->assertInstanceOf(Where::class, $result[1]);
    }

    /**
     * Test collection set procedure
     */
    public function testSuccessSet()
    {
        $collection = new QueryCollection();
        $key = 'test';
        $value = 'test3';
        $collection->push(new Where($key, $value))->push(new Where('test2', 'test3'));
        
        $result = $collection->get(0);
        $this->assertNotEmpty($result);
        $this->assertEquals($value, $result->getValue());
    }

    /**
     * Test collection pop procedure
     */
    public function testSuccessPop()
    {
        $collection = new QueryCollection();
        $key = 'test';
        $value = 'test3';
        $collection->push(new Where('test2', 'test3'))->push(new Where($key, $value));
        
        $result = $collection->pop();
        $this->assertNotEmpty($result);
        $this->assertInstanceOf(Where::class, $result);
        $this->assertEquals($key, $result->getKey());
    }

    /**
     * Test collection get procedure
     */
    public function testSuccessGet()
    {
        $collection = new QueryCollection();
        $key = 'test';
        $value = 'test3';
        $collection->push(new Where($key, $value))->push(new Where('test2', 'test3'));
        
        $result = $collection->get(0);
        $this->assertNotEmpty($result);
        $this->assertEquals($value, $result->getValue());
    }

    /**
     * Test collection has procedure
     */
    public function testSuccessHas()
    {
        $collection = new QueryCollection();
        $key = 'test';
        $value = 'test3';
        $collection->push(new Where($key, $value));
        
        $result = $collection->has(0);
        $this->assertTrue($result);
    }

    /**
     * Test collection first procedure
     */
    public function testSuccessFirst()
    {
        $collection = new QueryCollection();
        $key = 'test';
        $value = 'test3';
        $collection->push(new Where($key, $value))->push(new Where('test2', 'test2'));
        
        $result = $collection->first($key);
        $this->assertNotEmpty($result);
        $this->assertEquals($result->getKey(), $key);
    }

    /**
     * Test collection extract procedure
     */
    public function testSuccessExtract()
    {
        $collection = new QueryCollection();
        $key = 'test';
        $value = 'test3';
        $collection->push(new Where($key, $value))->push(new Where('test2', 'test2'));
        
        $result = $collection->extract($key);
        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
        $this->assertCount(1, $collection->all());
        $this->assertEquals($result[0]->getKey(), $key);
    }

    /**
     * Test collection find procedure
     */
    public function testSuccessFind()
    {
        $collection = new QueryCollection();
        $key = 'test';
        $value = 'test3';
        $collection->push(new Where('test2', 'test2'))->push(new Where($key, $value));
        
        $result = $collection->find($key);
        $this->assertEquals($result, 1);
    }

    /**
     * Test collection exclude procedure
     */
    public function testSuccessExclude()
    {
        $collection = new QueryCollection();
        $key = 'test';
        $value = 'test3';
        $collection->push(new Where($key, $value))->push(new Where('test2', 'test2'));
        
        $collection->exclude(['Filter']);
        $this->assertEmpty($collection->all());
    }

    /**
     * Test collection filter by types procedure
     */
    public function testSuccessFilterByTypes()
    {
        $collection = new QueryCollection();
        $key = 'test';
        $value = 'test3';
        $collection->push(new Where($key, $value))->push(new Where('test2', 'test2'));
        
        $result = $collection->filterByTypes(['Filter']);
        $this->assertNotEmpty($result);
        $this->assertCount(2, $result);
    }

    /**
     * Test collection to array procedure
     */
    public function testSuccessToArray()
    {
        $collection = new QueryCollection();
        $key = 'test';
        $value = 'test3';
        $collection->push(new Where($key, $value))->push(new Where('test2', 'test2'));
        
        $result = $collection->toArray();
        $this->assertNotEmpty($result);
        $this->assertCount(2, $result);
    }

    /**
     * Test collection equal method
     */
    public function testSuccessEqual()
    {
        $collection = new QueryCollection();
        $key = 'test';
        $value = 'test3';
        $collection->equal($key, $value);
        
        $result = $collection->toArray();
        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
        $this->assertTrue($result[0] instanceof Where);
        $this->assertEquals($result[0]->getKey(), $key);
        $this->assertEquals($result[0]->getValue(), $value);
        $this->assertEquals($result[0]->getComparison(), Where::EQUAL);
    }

    /**
     * Test collection equal method
     */
    public function testSuccessNotEqual()
    {
        $collection = new QueryCollection();
        $key = 'test';
        $value = 'test3';
        $collection->notEqual($key, $value);
        
        $result = $collection->toArray();
        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
        $this->assertTrue($result[0] instanceof Where);
        $this->assertEquals($result[0]->getComparison(), Where::NOT_EQUAL);
    }

    /**
     * Test collection greater then method
     */
    public function testSuccessGreaterThen()
    {
        $collection = new QueryCollection();
        $key = 'test';
        $value = 'test3';
        $collection->greaterThen($key, $value);
        
        $result = $collection->toArray();
        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
        $this->assertTrue($result[0] instanceof Where);
        $this->assertEquals($result[0]->getComparison(), Where::GREATER_THEN);
    }

    /**
     * Test collection greater then method
     */
    public function testSuccessGreaterThenOrEqual()
    {
        $collection = new QueryCollection();
        $key = 'test';
        $value = 'test3';
        $collection->greaterThenOrEqual($key, $value);
        
        $result = $collection->toArray();
        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
        $this->assertTrue($result[0] instanceof Where);
        $this->assertEquals($result[0]->getComparison(), Where::GREATER_THEN_OR_EQUAL);
    }

    /**
     * Test collection less then method
     */
    public function testSuccessLessThen()
    {
        $collection = new QueryCollection();
        $key = 'test';
        $value = 'test3';
        $collection->lessThen($key, $value);
        
        $result = $collection->toArray();
        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
        $this->assertTrue($result[0] instanceof Where);
        $this->assertEquals($result[0]->getComparison(), Where::LESS_THEN);
    }

    /**
     * Test collection less then method
     */
    public function testSuccessLessThenOrEqual()
    {
        $collection = new QueryCollection();
        $key = 'test';
        $value = 'test3';
        $collection->lessThenOrEqual($key, $value);
        
        $result = $collection->toArray();
        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
        $this->assertTrue($result[0] instanceof Where);
        $this->assertEquals($result[0]->getComparison(), Where::LESS_THEN_OR_EQUAL);
    }

    /**
     * Test collection like method
     */
    public function testSuccessLike()
    {
        $collection = new QueryCollection();
        $key = 'test';
        $value = 'test3';
        $collection->like($key, $value);
        
        $result = $collection->toArray();
        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
        $this->assertTrue($result[0] instanceof Where);
        $this->assertEquals($result[0]->getComparison(), Where::LIKE);
    }

    /**
     * Test collection not like method
     */
    public function testSuccessNotLike()
    {
        $collection = new QueryCollection();
        $key = 'test';
        $value = 'test3';
        $collection->notLike($key, $value);
        
        $result = $collection->toArray();
        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
        $this->assertTrue($result[0] instanceof Where);
        $this->assertEquals($result[0]->getComparison(), Where::NOT_LIKE);
    }

    /**
     * Test collection in method
     */
    public function testSuccessIn()
    {
        $collection = new QueryCollection();
        $key = 'test';
        $value = ['test3'];
        $collection->in($key, $value);
        
        $result = $collection->toArray();
        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
        $this->assertTrue($result[0] instanceof Where);
        $this->assertEquals($result[0]->getComparison(), Where::IN);
    }

    /**
     * Test collection not in method
     */
    public function testSuccessNotIn()
    {
        $collection = new QueryCollection();
        $key = 'test';
        $value = ['test3'];
        $collection->notIn($key, $value);
        
        $result = $collection->toArray();
        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
        $this->assertTrue($result[0] instanceof Where);
        $this->assertEquals($result[0]->getComparison(), Where::NOT_IN);
    }

    /**
     * Test collection order by method
     */
    public function testSuccessOrderBy()
    {
        $collection = new QueryCollection();
        $key = 'test';
        $collection->orderBy($key);
        
        $result = $collection->toArray();
        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
        $this->assertTrue($result[0] instanceof OrderBy);
        $this->assertEquals($result[0]->getColumn(), $key);
        $this->assertEquals($result[0]->getDirection(), OrderBy::DIRECTION_ASC);
    }

    /**
     * Test collection order by desc method
     */
    public function testSuccessOrderByDesc()
    {
        $collection = new QueryCollection();
        $key = 'test';
        $collection->orderByDesc($key);
        
        $result = $collection->toArray();
        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
        $this->assertTrue($result[0] instanceof OrderBy);
        $this->assertEquals($result[0]->getColumn(), $key);
        $this->assertEquals($result[0]->getDirection(), OrderBy::DIRECTION_DESC);
    }

    /**
     * Test collection paginate method
     */
    public function testSuccessPaginate()
    {
        $collection = new QueryCollection();
        $collection->paginate();
        
        $result = $collection->toArray();
        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
        $this->assertTrue($result[0] instanceof Pagination);
        $this->assertEquals($result[0]->getLimit(), QueryCollection::PAGENAV_DEFAULT_LIMIT);
        $this->assertEquals($result[0]->getOffset(), QueryCollection::PAGENAV_DEFAULT_OFFSET);
    }

    /**
     * Test collection group method
     */
    public function testSuccessGroup()
    {
        $collection = new QueryCollection();
        $key = 'test';
        $subqueries = new QueryCollection([$key => $key]);
        $collection->group($subqueries);
        
        $result = $collection->toArray();
        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
        $this->assertTrue($result[0] instanceof WhereGroup);
        $this->assertEquals($result[0]->getSubqueries(), $subqueries);
    }
}