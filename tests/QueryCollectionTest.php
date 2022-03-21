<?php

namespace Jcshoww\QueryCollection\Test;

use Jcshoww\QueryCollection\Query\Where;
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
}