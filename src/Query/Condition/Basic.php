<?php

namespace Jcshoww\QueryCollection\Query\Condition;

/**
 * Class Basic
 * 
 * Class contains basic where conditions
 * 
 * @author jcshow
 * @package Jcshoww\QueryCollection\Query\Condition
 */
class Basic
{
    const EQUAL = 'equal';
    const NOT_EQUAL = 'not_equal';
    const GREATER_THAN = 'greater_then';
    const GREATER_THAN_OR_EQUAL = 'greater_then_or_equal';
    const LESS_THAN = 'less_then';
    const LESS_THAN_OR_EQUAL = 'less_then_or_equal';
    const LIKE = 'like';
    const NOT_LIKE = 'not_like';
    const IN = 'in';
    const NOT_IN = 'not_in';
}