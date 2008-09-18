<?php

/**

Simple php calendar class
Copyright (C) 2005 Christof Damian

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

*/


class CalendarClass
{
	var $weekstart = 1;


	function CalendarClass($year=0, $month=0, $day=0)
	{
		$today = getdate();

		$this->year = $year ? $year : $today["year"];
		$this->month = $month ? $month : $today["mon"];
		$this->day = $day ? $day : $today["mday"];

		if (!isset($this->weekstart)) {
			$this->weekstart = 1;
		}
	}

	function WeekdayShort($dow)
	{
		$a = array('M','T','W','T','F','S','S');
		return $a[$dow-1];
	}

	function WeekdayLong($dow)
	{
		$a = array(
			'Monday','Tuesday','Wednesday','Thursday','Friday',
			'Saturday','Sunday'
		);
		return $a[$dow-1];
	}

	function MonthName($month)
	{
		$a = array("January", "February", "March", "April",
               "May", "June", "July", "August",
               "September", "October", "November", "December");
		return $a[$month-1];
	}


	function displayDay($d, $m, $y)
	{
		return $d;
	}

	function displayDayCell($d, $m, $y)
	{
		$daytext = $this->displayDay($d, $m, $y);
		$today = getdate();

		if ($d==$today["mday"] and $m==$today["mon"] and $y==$today["year"]) {
			return '<td align="right" valign="top" class="calendarToday">'
				.'<b>'.$daytext.'</b></td>';
		} else {
			return '<td align="right" valign="top">'.$daytext.'</td>';
		}
	}

	function displayEmptyCell()
	{
		return '<td></td>';
	}

	function DaysInMonth($month, $year)
	{
		switch ($month) {
			case 4:
			case 6:
			case 9:
			case 11:
				return 30;
			case 2:
				if ($year % 4==0 and ($year % 100!=0 or $year % 400==0)) {
					return 29;
				}
				return 28;
			default:
				return 31;
			}
	}

	function DayOfWeek($day, $month, $year)
	{
		$r = (int)date('w', mktime(0, 0, 0, $month, $day, $year));
		return $r ? $r : 7;
	}


	function displayMonth($month = 0, $year = 0)
	{
		if ($month == 0) {
			$month = $this->month;
		}
		if ($year == 0) {
			$year = $this->year;
		}

		$r = '';

		$dim = $this->DaysInMonth($month, $year);
		$dow = $this->DayOfWeek(1, $month, $year);

		$r .= '<tr>';
		$r .= '<td colspan="7" align="center" valign="top" class="calendarHeader">';
		$r .= $this->MonthName($month);
		$r .= '</td></tr>';

		$r .= '<tr>';
		for ($i=0; $i<7; $i++) {
			$r .= '<td align="center" valign="top" class="calendarHeader">'
				.$this->WeekdayShort(($i+$this->weekstart+6) % 7 +1 ).'</td>';
		}

		$r .= "</tr>\n<tr>";

		$col = 1;
		for ($i=($this->weekstart-$dow-7)%7+1; $i<=$dim; $i++) {
			if ($i<1) {
				$r .= $this->displayEmptyCell();
			} else {
				$r .= $this->displayDayCell($i, $month, $year);
			}

			if ($col++ % 7 == 0) {
				$r .= "</tr>\n<tr>";
			}
		}

		$col--;
		while ($col++ % 7 != 0) {
			$r .= $this->displayEmptyCell();
		}

		$r .= '</tr>';

		return '<table class="calendar" border="0">'.$r.'</table>';
	}

	function displayThreeMonths()
	{
		$this->month--;
		if ($this->month < 1) {
			$this->month = 12;
			$this->year--;
		}

		return $this->displayMonths(3);
	}

	function displayMonths($count,$header = '')
	{
		$r = '';

		for ($i=1; $i<=$count; $i++) {
			$r .= '<td valign="top">'.$this->displayMonth().'</td>';
			$this->month++;
			if ($this->month > 12) {
				$this->month = 1;
				$this->year++;
			}
			if ($i % 4 == 0) {
				$r .= "</tr>\n<tr>";
			}
		}

		return '<table class="calendar" border="0">'.$header.'<tr>'.$r.'</tr></table>';
	}

	function displayYear() {
		$header =
			'<tr><td class="calendarHeader" valign="top" align="center" colspan="4">'
			.$this->year
			."</td></tr>\n";

		$this->month = 1;
		return $this->displayMonths(12,$header);
	}

	function displayWeekday($d, $m, $y, $dow)
	{
		return "<h3>$d.$m.$y ".$this->WeekdayLong($dow)."</h3>\n";
	}

	function displayWeek()
	{
		$time = mktime(0, 0, 0, $this->month, $this->day, $this->year);
		$d    = getdate($time-60*60*24*((date('w',$time)+7-$this->weekstart) % 7));

		$day   = $d["mday"];
		$month = $d["mon"];
		$year  = $d["year"];

		$r = '';
		for ($i=0; $i<7; $i++) {
			$r .= $this->displayWeekday($day, $month, $year,
				($i+$this->weekstart-1) % 7+1);

			if (++$day > $this->DaysInMonth($month, $year)) {
				$day = 1;
				if (++$month > 12) {
					$month = 1;
					$year++;
				}
			}
		}
		return $r;
	}

	function displayDays($n)
	{
		$d = getdate(mktime(0, 0, 0, $this->month, $this->day, $this->year));

		$day   = $d["mday"];
		$month = $d["mon"];
		$year  = $d["year"];
		$wday  = ($d["wday"]+6)%7;

		$r = '';
		for ($i=0; $i<$n; $i++) {
			$r .= $this->displayWeekday($day, $month, $year, ($i+$wday) % 7+1);

			if (++$day > $this->DaysInMonth($month, $year)) {
				$day = 1;
				if (++$month > 12) {
					$month = 1;
					$year++;
				}
			}
		}
		return $r;
	}

	function displayReverseDays($n)
	{
		$d = getdate(mktime(0, 0, 0, $this->month, $this->day, $this->year));

		$day   = $d["mday"];
		$month = $d["mon"];
		$year  = $d["year"];
		$wday  = ($d["wday"]+6)%7;

		$r = '';
		for ($i=0; $i<$n; $i++) {
			$r .= $this->displayWeekday($day, $month, $year, ($wday-$i) % 7+1);

			if (--$day < 1 ) {
				if ( --$month < 1 ) {
					$month = 12;
					$year--;
				}
				$day = $this->DaysInMonth($month, $year);
			}
		}
		return $r;
	}

	function displayToday()
	{
		return $this->displayDays(1);
	}

	/**
	 * display one row for the displayWeeks() method
	 *
	 * @param integer $time time of the date to display
	 * @return string
	 */
	function displayWeekRow($time)
	{
		return '<li>'.date('W d.m.Y l',$time).'</li>';
	}

	/**
	 * display the weeks until enddate.
	 *
	 * @param string $enddate date() formated string
	 * @return string
	 */
	function displayWeeks($enddate)
	{
		$dow = $this->DayOfWeek($this->day,$this->month,$this->year);
		$time = mktime(0, 0, 0, $this->month, $this->day, $this->year);
		$time -= 60*60*24*($dow-$this->weekstart % 7);

		$endtime = strtotime($enddate);

		$r = '';
		while ($time < $endtime) {
			$r .= $this->displayWeekRow($time);
			$time += 60*60*24*7;
		}
		return "<ul>$r</ul>";
	}
}

?>
