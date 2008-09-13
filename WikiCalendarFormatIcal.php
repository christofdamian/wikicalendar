<?php

class WikiCalendarFormatIcal extends WikiCalendarFormatText { 
  
  function DayTitle($text,$heading) {
    return "";
  }

  function TodayTitle($text,$heading) {
    return $this->DayTitle($text,$heading);
  }

  function Day($text, $merged, $day, $month, $year) {
    $time_start = strftime("%Y%m%d",mktime(0, 0, 0, $month, $day, $year));
    $time_end   = strftime("%Y%m%d",mktime(0, 0, 0, $month, $day+1, $year));

    $r = '';
    $r .= "BEGIN:VEVENT\n";
    $r .= "DTSTART;VALUE=DATE:".$time_start."\n";
    $r .= "DTEND;VALUE=DATE:".$time_end."\n";
    $r .= "URL:http://krass.com/\n";
    $r .= "SUMMARY:krass test\n";
    $r .= "END:VEVENT\n";
    return $r;
  }

  function EmptyDay($text) {
    return "";
  }
  
  function Wrapper($text) {
    return "BEGIN:VCALENDAR\nVERSION:2.0\n".$text."END:VCALENDAR\n";
  }
}

?>