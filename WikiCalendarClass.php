<?php

/* 

Simple wiki calendar class
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

require_once("CalendarClass.php");

class WikiCalendarClass extends CalendarClass { 

  function WeekdayShort($dow, $len = false) {
    if ($dow == 0) $dow = 7;
    if ($len === false) $len = $this->weekdaylen;
    $a = array('monday','tuesday','wednesday',
               'thursday','friday','saturday','sunday');
    $day = wfMsg($a[$dow-1]);
    return substr($day, 0, $len);
  }

  function WeekdayLong($dow) {
    if ($dow == 0) $dow = 7;
    $a = array('monday','tuesday','wednesday',
               'thursday','friday','saturday','sunday');
    return wfMsg($a[$dow-1]);
  }

  function MonthName($month) {
    $a = array("january", "february", "march", "april", 
               "may_long", "june", "july", "august", 
               "september", "october", "november", "december");
    return wfMsg($a[$month-1]);
  }

  function MonthShort($month) {
    $a = array("jan", "feb", "mar", "apr", "may", "jun",
               "jul", "aug", "sep", "oct", "nov", "dec");
    return wfMsg($a[$month-1]);
  }

  function MonthLong($month) {
    return ($this->MonthName($month));
  }

  function _format($day,$month,$year,$name,$r) {
    $r = str_replace('%day',$day,$r);
    $r = str_replace('%month',$month,$r);
    $r = str_replace('%year',$year,$r);
    $r = str_replace('%name',$name,$r);

    $time = mktime(0,0,0,$month,$day,$year);
    while (preg_match('/%([A-Za-z])/',$r,$matches)>0) {
      switch ($matches[1]) {
      case 'D': // short weekday string
        $str = $this->WeekdayShort(date('w',$time), 3);
        break;
      case 'l': // long weekday string
        $str = $this->WeekdayLong(date('w',$time));
        break;
      case 'M': // short month string
        $str = $this->MonthShort(date('n',$time));
        break;
      case 'F': // long month string
        $str = $this->MonthLong(date('n',$time));
        break;
      default:
        $str = date($matches[1],$time);
      }
      $r = str_replace('%'.$matches[1],$str,$r);
    }
    return $r;
  }

  function formatdate($day,$month,$year) {
    return $this->_format($day,$month,$year,$this->name,$this->format);
  }

  function formattitle($day,$month,$year) {
    return $this->_format($day,$month,$year,$this->name,$this->formattitle);
  }

  function displayDay($day, $month, $year) {
    $text = $this->formatdate($day,$month,$year);
    $title = Title::newFromText($text);
    return '[['.$text.'|'.$day.']]';
  }

  function displayWeekday($day, $month, $year, $dow) {
    $today = getdate();
    $text = $this->formatdate($day,$month,$year);
    $heading = $this->formattitle($day,$month,$year);
    
    if (($day == $today["mday"]) && ($month == $today["mon"]) && ($year == $today["year"])) {
      $r = $this->weekformat->TodayTitle($text,$heading);
    } else {
      $r = $this->weekformat->DayTitle($text,$heading);
    }
    
    $main = "";
    $title = Title::newFromText($text);
    if ($title and $title->getArticleID()!=0) {
      $main = $text;
    }

    $merged = "";
    foreach ($this->merge as $i) {
      $merge = $this->_format($day,$month,$year,$i,$this->format);
      $title = Title::newFromText($merge);
      if ($title and $title->getArticleID()!=0) {
        $merged .= $this->weekformat->MergedDay($i,$merge);
      }
    }

    if ($main or $merged) {
      $r .= $this->weekformat->Day($main, $merged, $day, $month, $year);
    } else {
      if ($this->skipempty) {
        return '';
      } elseif ($this->showempty) {
        $r .= $this->weekformat->EmptyDay("<small>No entries for this date. Please [[$text|feel free to add entries]].</small>");
      }
    }
    
    return $r;
  }

  function displayDays($n = 0, $reverse = false) {
    $time = mktime(0, 0, 0, $this->month, $this->day, $this->year);
    if ($n == 0) {
        $time -= 60 * 60 * 24 * ((date('w', $time) + 7 - $this->weekstart) % 7);
        $n = 7;
    }

    $r = '';
    $end = $reverse ? 0 : $n;
    $step = $reverse ? -1 : 1;
    $start = $reverse ? $n : 0;
    for ($i = $start; $i != $end; $i += $step) {
      $date = getdate($time + ($i*60*60*24));
      $day = $date["mday"];
      $month = $date["mon"];
      $year = $date["year"];
      $wday = ($date["wday"]+6)%7;

      $r .= $this->displayWeekday($day, $month, $year, $wday);
    }

    return $this->weekformat->Wrapper($r);
  }
}

?>
