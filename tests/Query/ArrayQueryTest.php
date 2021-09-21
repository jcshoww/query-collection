<?php

namespace Jcshoww\QueryCollection\Test\Query;

use Jcshoww\QueryCollection\Builder\Builder;
use Jcshoww\QueryCollection\Query\ArrayQuery;
use Jcshoww\QueryCollection\Test\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * ArrayQueryTest
 * 
 * @author jcshoww
 * @package Jcshoww\QueryCollection\Test\Query
 */
class ArrayQueryTest extends TestCase
{
    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test query apply procedure
     */
    public function testSuccessApply()
    {
        /** @var MockObject|Builder */
        $builder = $this->getMockBuilder(Builder::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $builder->setQuery([]);

        $key = 'test';
        $value = 'test2';
        $query = new ArrayQuery('test', 'test2');
        $builder = $query->apply($builder);
        $result = $builder->getQuery();
        $this->assertArrayHasKey($key, $result);
        $this->assertEquals($result[$key], $value);
    }
}