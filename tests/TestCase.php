<?php

namespace Tests;

if (class_exists('\PHPUnit\Framework\TestCase')) {
    abstract class TestCase extends \PHPUnit\Framework\TestCase
    {
    }
} elseif (class_exists('\PHPUnit_Framework_TestCase')) { 
    abstract class TestCase extends \PHPUnit_Framework_TestCase
    {
    }
} else {
    throw new \Exception('PHPUnit not found.');
}
