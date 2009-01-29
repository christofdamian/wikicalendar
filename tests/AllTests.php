<?php
require_once('PHPUnit/Framework.php');

require_once(dirname(__FILE__).'/CalendarClassTest.php');
require_once(dirname(__FILE__).'/WikiCalendarClassTest.php');

class AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit');

        $suite->addTestSuite('CalendarClassTest');
        $suite->addTestSuite('WikiCalendarClassTest');

        return $suite;
    }
}