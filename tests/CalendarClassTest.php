<?php
/**
 * tests for the CalendarClass
 *
 * @package Wikicalendar
 * @author 	Christof Damian <christof@damian.net>
 * @license GPL2
 */

require_once dirname(__FILE__)."/../CalendarClass.php";

/**
 * class with tests for the CalendarClass
 *
 */
class CalendarClassTest extends PHPUnit_Framework_TestCase
{
	private $_cal;

	protected function setUp()
	{
		$this->_cal = new CalendarClass(2000, 11, 5);
	}

	public function testGetYear()
	{
		$this->assertEquals($this->_cal->year, 2000);
	}

	public function testDayOfWeek()
	{
		$this->assertEquals($this->_cal->DayOfWeek(5, 11, 2000), 7);
		$this->assertEquals($this->_cal->DayOfWeek(12, 10, 2005), 3);
	}

	public function testDaysInMonth()
	{
		$this->assertEquals($this->_cal->DaysInMonth(2, 2000), 29);
		$this->assertEquals($this->_cal->DaysInMonth(2, 1997), 28);
		$this->assertEquals($this->_cal->DaysInMonth(2, 2004), 29);
		$this->assertEquals($this->_cal->DaysInMonth(11, 2004), 30);
	}

	public function testDisplayToday()
	{
		$this->assertContains("5.11.2000 Sunday", $this->_cal->displayToday());
	}

	public function testDisplayWeek1()
	{
		$week = $this->_cal->displayWeek();
		$this->assertContains("30.10.2000 Monday", $week);
		$this->assertContains("5.11.2000 Sunday", $week);
	}

	public function testDisplayWeek2()
	{
		$_cal = new CalendarClass(2005, 10, 12);
		$week = $_cal->displayWeek();
		$this->assertContains("10.10.2005 Monday", $week);
		$this->assertContains("16.10.2005 Sunday", $week);
	}

	public function testDisplayWeek3()
	{
		$_cal            = new CalendarClass(2005, 10, 12);
		$_cal->weekstart = 7;
		$week            = $_cal->displayWeek();

		$this->assertContains("9.10.2005 Sunday", $week);
		$this->assertContains("15.10.2005 Saturday", $week);
	}

	public function testDisplayDays()
	{
		$_cal = new CalendarClass(2005, 10, 10);
		$this->assertEquals($_cal->displayWeek(), $_cal->displayDays(7));
	}

	public function testDisplayMonth1()
	{
		$this->assertEquals("November|M,T,W,T,F,S,S|"
			.",,1,2,3,4,5|6,7,8,9,10,11,12|"
			."13,14,15,16,17,18,19|20,21,22,23,24,25,26|27,28,29,30,,,|",
			$this->_striphtml($this->_cal->displayMonth()));
	}

	public function testDisplayMonth2()
	{
		$_cal = new CalendarClass(2005, 10, 10);

		$this->assertEquals("October|M,T,W,T,F,S,S|"
			.",,,,,1,2|3,4,5,6,7,8,9|10,11,12,13,14,15,16|"
			."17,18,19,20,21,22,23|24,25,26,27,28,29,30|31,,,,,,|",
			$this->_striphtml($_cal->displayMonth()));

		$_cal->weekstart = 5;
		$this->assertEquals("October|F,S,S,M,T,W,T|"
			.",1,2,3,4,5,6|7,8,9,10,11,12,13|"
			."14,15,16,17,18,19,20|21,22,23,24,25,26,27|28,29,30,31,,,|",
			$this->_striphtml($_cal->displayMonth()));

		$_cal->weekstart = 7;
		$this->assertEquals("October|S,M,T,W,T,F,S|"
			.",,,,,,1|2,3,4,5,6,7,8|9,10,11,12,13,14,15|"
			."16,17,18,19,20,21,22|23,24,25,26,27,28,29|30,31,,,,,|",
		$this->_striphtml($_cal->displayMonth()));
	}

	public function testDisplayThreeMonths()
	{
		$_cal = new CalendarClass(2005, 10, 10);

		$this->assertEquals("September|M,T,W,T,F,S,S|"
			.",,,1,2,3,4|5,6,7,8,9,10,11|12,13,14,15,16,17,18|"
			."19,20,21,22,23,24,25|26,27,28,29,30,,|"
			.",October|M,T,W,T,F,S,S|,,,,,1,2|3,4,5,6,7,8,9|"
			."10,11,12,13,14,15,16|17,18,19,20,21,22,23|24,25,26,27,28,29,30|"
			."31,,,,,,|"
			.",November|M,T,W,T,F,S,S|,1,2,3,4,5,6|7,8,9,10,11,12,13|"
			."14,15,16,17,18,19,20|21,22,23,24,25,26,27|28,29,30,,,,||",
			$this->_striphtml($_cal->displayThreeMonths()));
	}

	public function testDisplayMonths()
	{
		$_cal = new CalendarClass(2005, 10, 10);

		$this->assertEquals("October|M,T,W,T,F,S,S|,,,,,1,2|3,4,5,6,7,8,9|"
			."10,11,12,13,14,15,16|17,18,19,20,21,22,23|24,25,26,27,28,29,30|"
			."31,,,,,,|,November|M,T,W,T,F,S,S|,1,2,3,4,5,6|7,8,9,10,11,12,13|"
			."14,15,16,17,18,19,20|21,22,23,24,25,26,27|28,29,30,,,,||",
			$this->_striphtml($_cal->displayMonths(2)));
	}

	public function testDisplayYear()
	{
		$_cal = new CalendarClass(2005, 10, 10);

		$this->assertEquals("2005|January|M,T,W,T,F,S,S|,,,,,1,2|3,4,5,6,7,8,9|"
			."10,11,12,13,14,15,16|17,18,19,20,21,22,23|24,25,26,27,28,29,30|31"
			.",,,,,,|,February|M,T,W,T,F,S,S|,1,2,3,4,5,6|7,8,9,10,11,12,13|14,"
			."15,16,17,18,19,20|21,22,23,24,25,26,27|28,,,,,,|,March|M,T,W,T,F,"
			."S,S|,1,2,3,4,5,6|7,8,9,10,11,12,13|14,15,16,17,18,19,20|21,22,23,"
			."24,25,26,27|28,29,30,31,,,|,April|M,T,W,T,F,S,S|,,,,1,2,3|4,5,6,7"
			.",8,9,10|11,12,13,14,15,16,17|18,19,20,21,22,23,24|25,26,27,28,29,"
			."30,||May|M,T,W,T,F,S,S|,,,,,,1|2,3,4,5,6,7,8|9,10,11,12,13,14,15|"
			."16,17,18,19,20,21,22|23,24,25,26,27,28,29|30,31,,,,,|,June|M,T,W,"
			."T,F,S,S|,,1,2,3,4,5|6,7,8,9,10,11,12|13,14,15,16,17,18,19|20,21,2"
			."2,23,24,25,26|27,28,29,30,,,|,July|M,T,W,T,F,S,S|,,,,1,2,3|4,5,6,"
			."7,8,9,10|11,12,13,14,15,16,17|18,19,20,21,22,23,24|25,26,27,28,29"
			.",30,31||,August|M,T,W,T,F,S,S|1,2,3,4,5,6,7|8,9,10,11,12,13,14|15"
			.",16,17,18,19,20,21|22,23,24,25,26,27,28|29,30,31,,,,||September|M"
			.",T,W,T,F,S,S|,,,1,2,3,4|5,6,7,8,9,10,11|12,13,14,15,16,17,18|19,2"
			."0,21,22,23,24,25|26,27,28,29,30,,|,October|M,T,W,T,F,S,S|,,,,,1,2"
			."|3,4,5,6,7,8,9|10,11,12,13,14,15,16|17,18,19,20,21,22,23|24,25,26"
			.",27,28,29,30|31,,,,,,|,November|M,T,W,T,F,S,S|,1,2,3,4,5,6|7,8,9,"
			."10,11,12,13|14,15,16,17,18,19,20|21,22,23,24,25,26,27|28,29,30,,,"
			.",|,December|M,T,W,T,F,S,S|,,,1,2,3,4|5,6,7,8,9,10,11|12,13,14,15,"
			."16,17,18|19,20,21,22,23,24,25|26,27,28,29,30,31,|||",
			$this->_striphtml($_cal->displayYear())
			);
	}

	public function testWeeks()
	{
		$_cal = new CalendarClass(2008,1,1);

		$time = mktime(0,0,0,1,30,2008);

		$this->assertEquals(
			"05 30.01.2008 Wednesday",
			$this->_striphtml($_cal->displayWeekRow($time))
		);

		$this->assertEquals(
			"01 31.12.2007 Monday02 07.01.2008 Monday03 14.01.2008 Monday",
			$this->_striphtml($_cal->displayWeeks("15 Jan 2008")));
	}

	/**
	 * strip table html from the string, to make tests above more readable
	 *
	 * @param string 	$html string to strip
	 * @return string
	 */
	private function _striphtml($html)
	{
		$html = preg_replace('|</td><td.*?>|', ',', $html);
		$html = preg_replace('|</tr>|', '|', $html);
		$html = preg_replace('|<.*?>|', '', $html);
		$html = preg_replace('|\n|', '', $html);
		return $html;
	}
}
