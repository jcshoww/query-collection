<?php

namespace Jcshoww\QueryCollection\Query\Condition;

/**
 * Class Sql
 * 
 * Class contains sql where conditions
 * 
 * @author jcshow
 * @package Jcshoww\QueryCollection\Query\Condition
 */
class Sql extends Basic
{
    public const IS_NULL = 'is_null';
    public const NOT_NULL = 'not_null';
    public const CONTAINS = 'contains';
    public const STARTS_WITH = 'starts-with';
}