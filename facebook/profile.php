<?php

// Generate content of Facebook profile box for given OF uid
function get_profile($db, $uid, $fbuid, $ofname) {
  $sql = "SELECT COUNT(*) AS count, SUM(distance) AS distance, SUM(TIME_TO_SEC(duration))/60 AS duration, u.public FROM flights AS f, users AS u WHERE u.uid = f.uid AND f.uid=" . $uid . " GROUP BY f.uid";
  $result = mysql_query($sql, $db);
  if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    if($row["public"] == "N") {
      $content = sprintf("<b>Oops!</b>  Looks like <b><a href='http://openflights.org/user/%s'>%s</a></b>'s map has become private.", $ofname, $ofname);
    } else {
      $duration = sprintf("%d days, %d hours and %d minutes",
			  floor($row["duration"] / 3600),
			  floor(($row["duration"] % 3600)/60),
			  $row["duration"] % 60);
      $content = sprintf("<b><a href='http://openflights.org/user/%s'>%s</a></b> (<fb:name uid=\"$fbuid\" useyou=\"false\" />) has flown <b>%s</b> times, for a total distance of <b>%s miles</b> and a total duration of <b>%s</b>!  <a href='http://openflights.org/user/%s'>Find out more.</a>", $ofname, $ofname, $ofname, $row["count"], $row["distance"], $duration, $ofname);
    }
  } else {
    $content = "<b><a href='http://openflights.org/user/%s'>%s</a></b> (<fb:name uid=\"$fbuid\" useyou=\"false\" />) doesn't seem to have flown anywhere yet.   <a href='http://openflights.org/'>Add some flights?</a>";
  }
  return "<p><a href='http://openflights.org/user/%s'><img src='http://openflights.org/img/fb-thumbnail.png' width='184' height='101'/></a><br/><br/>" . $content . "</p>";
}

?>
