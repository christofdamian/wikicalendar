<?php

class WikiCalendarFormatText { 
  
  function DayTitle($text,$heading) {
    return "\n\n<b><span style=\"font-size:120%\">[[$text|$heading]]</span></b>\n\n";
  }

  function TodayTitle($text,$heading) {
    return "\n\n<b><span style=\"font-size:120%\" class=\"calendarToday\">[[$text|$heading]]</span></b>\n\n";
  }

  function Day($text, $merged) {
    if ($text) {
      return "{{:$text}}$merged\n";
    } else {
      # remove the leading "<br/>" too.
      return substr($merged,5)."\n";
    }
  }

  function MergedDay($calendar,$text) {
    return "<br/>{{:$text}}";
  }

  function EmptyDay($text) {
    return $text;
  }

  function Wrapper($text) {
    return $text;
  }
}

?>