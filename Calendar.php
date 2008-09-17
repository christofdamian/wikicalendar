<?php

/*

Simple wiki calendar
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

require_once("WikiCalendarClass.php");

require_once("WikiCalendarFormatText.php");
require_once("WikiCalendarFormatList.php");
require_once("WikiCalendarFormatTable.php");

$wgExtensionFunctions[] = "wfCalendarExtension";
$wgExtensionCredits['parserhook'][] = array(
   'name' => 'wikicalendar',
   'author' => 'Christof Damian',
   'url' => 'http://code.google.com/p/wikicalendar/',
   'version' => 1.13,
   'description' => 'simple calendar extension',
   'descriptionmsg' => 'wikicalendar-desc'
   );

function wfCalendarExtension() {
    global $wgParser;
    $wgParser->setHook( "calendar", "renderCalendar" );
}

/**
 * Method to clear MediaWiki Cache on different versions
 *
 * @version 1.1
 * @author Daniel Simon
 */
function clearCache() {
  global $wgVersion;
  if (version_compare($wgVersion,'1.5','>=')) {
    global $wgParser;
    $wgParser->disableCache();
  } elseif (version_compare($wgVersion,'1.4','>=')) {
    global $wgTitle;
    $dbw =& wfGetDB( DB_MASTER );
    $dbw->update( 'cur', array( 'cur_touched' => $dbw->timestamp( time() + 120 ) ),
                  array(
                        'cur_namespace' => $wgTitle->getNamespace(),
                        'cur_title' => $wgTitle->getDBkey()
                        ), 'CalendarExtension'
                  );
  } elseif (version_compare($wgVersion,'1.3','>=')) {
    $wgOut->enableClientCache(false);
  }
}

function renderCalendar( $paramstring, $params = array() , $parser ) {
	global $wgTitle,$wgUser,$wgOut,$wgVersion;

	if (!$parser) {
		$parser = & new Parser();
	}

	$p = array(
    	"view"  => "year",
        "day"   => 0,
        "month" => 0,
        "year"  => 0,
        "days"  => 7,
        "weekstart" => 1,
        "formattitle" => "%j.%n.%Y %l",
        "skipempty" => 0,
        "showempty" => 1,
        "weekformat" => "text",
        "weekdaylen" => 1,
		"enddate" => false
	);

	preg_match_all('/([\w]+)\s*=\s*(?:"([^"]+)"|([^"\s]+))/', $paramstring, $matches);
	for ($i=0; $i< count($matches[0]); $i++) {
		$p[$matches[1][$i]] = $matches[2][$i].$matches[3][$i];
	}

	foreach (array('view','day','month','year','days','weekstart',
                 'formattitle','format','name','date','skipempty',
                 'showempty','weekformat','weekdaylen','enddate'
		) as $i) {
		if (isset($params[$i])) {
			$p[$i] = $params[$i];
		}
	}

	if (!isset($p['name'])) {
		$p['name'] = 'calendar';
	};
	if (!isset($p['format'])) {
		$p['format'] = '%name_%year_%month_%day';
	};

	if (isset($p['date'])) {
		$time = strtotime($p['date']);
		$p['day']   = date('d',$time);
		$p['month'] = date('n',$time);
		$p['year']  = date('Y',$time);
	}

	$cal = new WikiCalendarClass($p["year"],$p["month"],$p["day"]);
	$cal->format = $p['format'];
	$cal->formattitle = $p['formattitle'];
	$cal->name = $p['name'];
	$cal->weekstart = $p['weekstart'];
	$cal->skipempty = $p['skipempty'];
	$cal->showempty = $p['showempty'];
	$cal->weekdaylen = $p['weekdaylen'];

	if (isset($p["merge"])) {
		$cal->merge = explode(',',$p["merge"]);
	} else {
		$cal->merge = array();
	}

	switch ($p['weekformat']) {
		case 'list':
			$cal->weekformat = new WikiCalendarFormatList();
			break;
		case 'table':
			$cal->weekformat = new WikiCalendarFormatTable();
			break;
		default:
			$cal->weekformat = new WikiCalendarFormatText();
	}

	switch ($p["view"]) {
		case "week":
			$calstr = $cal->displayDays();
			break;
		case "month":
			$calstr = $cal->displayMonth();
			break;
		case "today":
			$calstr = $cal->displayDays(1);
			break;
		case "threemonths":
			$calstr = $cal->displayThreeMonths();
			break;
		case "days":
			$calstr = $cal->displayDays($p["days"]);
			break;
		case "rdays":
			$calstr = $cal->displayDays($p["days"], true);
			break;
		case "weeks":
			$calstr = $cal->displayWeeks($p['enddate']);
			break;
		default:
			$calstr = $cal->displayYear();
	}

	$o = & $parser->parse($calstr,$wgTitle,ParserOptions::newFromUser($wgUser), true, false);

	clearCache();

	return $o->getText();
}

?>
