<?php

class WikiCalendarFormatTable extends WikiCalendarFormatText { 
  
  function DayTitle($text,$heading) {
    return "\n<tr><th colspan=\"2\" align=\"left\">[[$text|$heading]]</th></tr>\n";
  }

  function TodayTitle($text,$heading) {
    return "\n<tr><th colspan=\"2\" align=\"left\"><span class=\"calendarToday\">[[$text|$heading]]</span></th></tr>\n";
  }

  function Day($text, $merged) {
    if ($text) {
      $text = "{{:$text}}";
    }
    return "\n<tr><td colspan=\"2\" style=\"padding-left: 2em\">$text</td></tr>\n$merged";
  }

  function MergedDay($calendar,$text) {
    return "<tr valign=\"top\"><td width=\"1\" style=\"padding-left: 2em\">[[$text|$calendar]]</td><td>{{:$text}}</td></tr>\n";
  }

  function EmptyDay($text) {
    return "<tr valign=\"top\"><td style=\"width: 12em;\"></td><td colspan=\"2\">$text</td></tr>\n";
  }

  function Wrapper($text) {
    return '<table class="calendar" border="0">'.$text.'</table>';
  }
}

?>