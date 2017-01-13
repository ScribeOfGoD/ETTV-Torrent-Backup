<!DOCTYPE html>
<html>
  <head>
    <?php include 'head.php' ?>
    <title>Recent Uploads</title>
  </head>
  <body>
    <center>
    <a href="/">
        <h1>ETTV Torrent Backup</h1>
    </a>
  </center>
    <?php

    include 'connect.php';

    $stmt = $mysqli->prepare('SELECT * FROM torrents ORDER BY date DESC LIMIT 20');
    $stmt->execute();
    $stmt->bind_result($id, $et_id, $torrent_hash, $title, $date);

    ?>
    <table class="data" style="width: 100%" cellpadding="0" cellspacing="0">
      <tr class="firstr">
        <th>Title</th>
        <th>Hash</th>
        <th>Magnet</th>
        <th class="lasttd">Date</th>
    </tr>
    <?php
    $i = 0;
    while ($stmt->fetch()) {
        ?>
    <tr class="<?=($i = 0) ? 'even' : 'odd'?>">
      <td><a class="cellMainLink" href="https://extratorrent.cc/torrent/<?=$et_id?>"><?=$title?></a></td>
      <td><?=$torrent_hash?></td>
      <td class="lasttd">
          <a class="magnet-icon" href="magnet:?xt=urn:btih:<?=$torrent_hash?>&amp;dn=<?=urlencode($title)?>&amp;tr=udp://9.rarbg.com:2710/announce&amp;tr=udp://tracker.publicbt.com/announce&amp;tr=udp://open.demonii.com/1337"></a>
      </td>
    <td><?=$date?></td>
    </tr>
    <?php
      $i = ($i = 0) ? 1 : 0;
    }
    ?>
    </table>
    <?php
    $stmt->close();

    $mysqli->close();
?>
  </body>
</html>
