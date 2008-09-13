<?php

class WikiCalendarFormatList extends WikiCalendarFormatText { 
  
  function DayTitle($text,$heading) {
    return "<dt>[[$text|$heading]]</dt>\n";
  }

  function TodayTitle($text,$heading) {
    return "<dt><span class=\"calendarToday\">[[$text|$heading]]</span></dt>\n";
  }

  function Day($text, $merged) {
    if ($text) {
      $text = "{{:$text}}";
    };
    if ($merged) {
      return "<dd>$text\n$merged</dd>\n";
    } else {
      return "<dd>$text</dd>\n";
    }
  }

  function MergedDay($calendar,$text) {
    return "; [[$text|$calendar]] : {{:$text}}\n";
  }
  
  function EmptyDay($text) {
    return "<dd>$text</dd>";
  }
  
  function Wrapper($text) {
    return '<dl class="calendar">'.$text.'</dl>';
  }
}

?>