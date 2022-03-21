<?php

namespace Jcshoww\QueryCollection\Test\Query;

use Jcshoww\QueryCollection\Builder\ArrayBuilder;
use Jcshoww\QueryCollection\Query\ArrayQuery;
use Jcshoww\QueryCollection\Query\Where;
use Jcshoww\QueryCollection\Test\TestCase;

/**
 * WhereTest
 * 
 * @author jcshoww
 * @package Jcshoww\QueryCollection\Test\Query
 */
class WhereTest extends TestCase
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
        $builder = new ArrayBuilder([
            'test3', 'test2'
        ]);

        $key = 'test';
        $value = 'test2';
        $query = new Where($key, $value);
        $query->apply($builder);
        $result = $builder->get();
        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
    }
}