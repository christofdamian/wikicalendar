<?php
require_once "PHPUnit2/Framework/TestCase.php";
require_once "../CalendarClass.php";

class CalendarClassTest extends PHPUnit2_Framework_TestCase {
  private $cal;

  protected function setUp() {
    $this->cal = new CalendarClass(2000,11,5);
  }

  public function testGetYear() {
    self::assertEquals($this->cal->year, 2000);
  }

  public function testDayOfWeek() {
    self::assertEquals($this->cal->DayOfWeek(5,11,2000), 7);
    self::assertEquals($this->cal->DayOfWeek(12,10,2005), 3);
  }

  public function testDaysInMonth() {
    self::assertEquals($this->cal->DaysInMonth(2,2000),29);
    self::assertEquals($this->cal->DaysInMonth(2,1997),28);
    self::assertEquals($this->cal->DaysInMonth(2,2004),29);
    self::assertEquals($this->cal->DaysInMonth(11,2004),30);
  }

  public function testDisplayToday() {
    self::assertContains("5.11.2000 Sunday",$this->cal->displayToday());
  }

  public function testDisplayWeek1() {
    $week = $this->cal->displayWeek();
    self::assertContains("30.10.2000 Monday",$week);
    self::assertContains("5.11.2000 Sunday",$week);
  }

  public function testDisplayWeek2() {
    $cal = new CalendarClass(2005,10,12);
    $week = $cal->displayWeek();
    self::assertContains("10.10.2005 Monday",$week);
    self::assertContains("16.10.2005 Sunday",$week);
  }

  public function testDisplayWeek3() {
    $cal = new CalendarClass(2005,10,12);
    $cal->weekstart = 7;
    $week = $cal->displayWeek();
    self::assertContains("9.10.2005 Sunday",$week);
    self::assertContains("15.10.2005 Saturday",$week);
  }

  public function testDisplayDays() {
    $cal = new CalendarClass(2005,10,10);
    self::assertEquals($cal->displayWeek(),$cal->displayDays(7));
  }

  public function testDisplayMonth1() {
    self::assertEquals("November|M,T,W,T,F,S,S|,,1,2,3,4,5|6,7,8,9,10,11,12|13,14,15,16,17,18,19|20,21,22,23,24,25,26|27,28,29,30,,,|",
                       self::striphtml($this->cal->displayMonth()));
  }

  public function testDisplayMonth2() {
    $cal = new CalendarClass(2005,10,10);
    
    self::assertEquals("October|M,T,W,T,F,S,S|,,,,,1,2|3,4,5,6,7,8,9|10,11,12,13,14,15,16|17,18,19,20,21,22,23|24,25,26,27,28,29,30|31,,,,,,|",
                       self::striphtml($cal->displayMonth()));

    $cal->weekstart = 5;
    self::assertEquals("October|F,S,S,M,T,W,T|,1,2,3,4,5,6|7,8,9,10,11,12,13|14,15,16,17,18,19,20|21,22,23,24,25,26,27|28,29,30,31,,,|",
                       self::striphtml($cal->displayMonth()));

    $cal->weekstart = 7;
    self::assertEquals("October|S,M,T,W,T,F,S|,,,,,,1|2,3,4,5,6,7,8|9,10,11,12,13,14,15|16,17,18,19,20,21,22|23,24,25,26,27,28,29|30,31,,,,,|",
                       self::striphtml($cal->displayMonth()));
  }

  private function striphtml($html) {
    $html = preg_replace('|</td><td.*?>|',',',$html);
    $html = preg_replace('|</tr>|','|',$html);
    $html = preg_replace('|<.*?>|','',$html);
    $html = preg_replace('|\n|','',$html);
    return $html;
  }
}

?>

