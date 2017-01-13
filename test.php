<?php

// Update `date` fields recursively

include ('connect.php');

$date = "0000-00-00 00:00:00";

$stmt = $mysqli->prepare("SELECT id, et_id FROM torrents t WHERE date = ?");
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc())
{
  $url = "https://extratorrent.cc/torrent/" . $row['et_id'];

  $cu = curl_init();

  curl_setopt_array(
    $cu,
    [
      CURLOPT_URL => $url,
      CURLOPT_SSL_VERIFYPEER => 0,
      CURLOPT_SSL_VERIFYHOST => 0,
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_TIMEOUT => 5
    ]
  );

  $res = curl_exec($cu);

  curl_close($cu);

  if (!$res || $res === '') continue;

  $reg_string = '/Torrent added:<\/td><td class="tabledata0">([0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2})/is';

  preg_match($reg_string, $res, $matches);

  if (isset($matches[1]) && $matches[1] !== '')
  {
    $stmt2 = $mysqli->prepare("UPDATE torrents SET date = ? WHERE id = ?");
	$stmt2->bind_param("si", $matches[1], $row['id']);
	$stmt2->execute();
	$stmt2->close();
  }

}
