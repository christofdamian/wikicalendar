<?php

class WikiCalendarFormatList extends WikiCalendarFormatText { 
  
  function DayTitle($text,$heading) {
    return "<dt>[[$text|$heading]]\n";
  }

  function TodayTitle($text,$heading) {
    return "<dt><span class=\"calendarToday\">[[$text|$heading]]</span>\n";
  }

  function Day($text, $merged) {
    if ($text) {
      $text = "{{:$text}}";
    };
    if ($merged) {
      return "<dd>$text\n$merged\n";
    } else {
      return "<dd>$text\n";
    }
  }

  function MergedDay($calendar,$text) {
    return "; [[$text|$calendar]] : {{:$text}}\n";
  }
  
  function EmptyDay($text) {
    return "<dd>$text";
  }
  
  function Wrapper($text) {
    return '<dl class="calendar">'.$text.'</dl>';
  }
}

?>