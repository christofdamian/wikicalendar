<?php
/**
 * tests for the WikiCalendarClass
 *
 * @package Wikicalendar
 * @author 	Christof Damian <christof@damian.net>
 * @license GPL2
 */

require_once dirname(__FILE__)."/../WikiCalendarClass.php";
require_once dirname(__FILE__)."/../WikiCalendarFormatText.php";

/**
 * mock Title class
 */

class Title {
	var $text;

	function newfromtext($text) {
		$new = new Title();
		$new->text = $text;
		return $new;
	}

	function getArticleId() {
		return "id:$this->text";
	}
}

/**
 * mock global functions
 */

function wfMsg($msg) {
	 return "wfMsg:$msg";
}

/**
 * class with tests for the CalendarClass
 *
 */
class WikiCalendarClassTest extends PHPUnit_Framework_TestCase
{
	private $_cal;

	protected function setUp()
	{
		$this->_cal = new WikiCalendarClass(2000, 11, 5);
		$this->_cal->weekformat = new WikiCalendarFormatText();
		$this->_cal->format = '%name_%year_%month_%day';
		$this->_cal->formattitle = '%j.%n.%Y %l';
	}

	public function testDisplayToday()
	{
		$this->assertEquals(
			"<b>[[_2000_11_5|5.11.2000 wfMsg:sunday]]</b>",
			$this->_strip($this->_cal->displayToday()));

	}

	/**
	 * strip whitesspace and stuff from the string, to make tests above more readable
	 *
	 * @param string 	$html string to strip
	 * @return string
	 */
	private function _strip($html)
	{
		$html = preg_replace('|\n|', '', $html);
		$html = preg_replace('|</?span.*?>|', '', $html);
		return $html;
	}
}
