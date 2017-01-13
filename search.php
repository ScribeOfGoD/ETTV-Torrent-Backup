<html>
<head>
    <?php include 'head.php'; ?>
    <title>
        Torrent Search
    </title>
</head>
<body>
<div align="center">
    <a href="/">
        <h1>ETTV Torrent Backup</h1>
    </a>
    <?php
    date_default_timezone_set("UTC");
    $trackers = '';
    $tracker = array('udp://9.rarbg.com:2710/announce', 'udp://tracker.publicbt.com/announce', 'udp://open.demonii.com/1337');
    foreach ($tracker as $t) {
        $trackers .= '&tr=' . $t;
    }

    include './connect.php';


    $tag = isset($_REQUEST['tag']) ? strtolower($_REQUEST['tag']) : null;
    $table = '';
    switch ($tag) {
        case 'ettv':
        case 'etrg':
        case 'ethd':
            $table = 'torrents_'.$tag;
            break;
        default:
            header('Location: /');
            die('');
            break;
    }
    

    $search = isset($_REQUEST['q']) ? clean_search($_REQUEST['q']) : null;
    $statement = null;

    if ($search||$tag) {
        if (preg_match('/[a-f0-9]{40}/i', $search)) {
            $statement = $mysqli->prepare('SELECT * FROM '.$table.' WHERE torrent_hash=?');
            $statement->bind_param('s', $search);
        } else {
            $conditions = $search ? advSearch($search) : '1';
            $statement = $mysqli->prepare('SELECT * FROM '.$table.' WHERE ' . $conditions);

        }
        $statement->execute();
        $statement->bind_result($id, $et_id, $hash, $title, $date);
    } else {
        header('location: /');
    }

    ?>
    <br/><br/>
    <?php include './searchbox.php'; ?>

    <?php if (isset($search)): ?>
        <table class="data" style="width: 100%" cellpadding="0" cellspacing="0">
            <tr class="firstr">
                <th>Title</th>
                <th>Hash</th>
                <th class="lasttd">Magnet</th>
            </tr>
            <?php $i = 0;
            $id = $et_id = $hash = $title = '';
            while ($statement !== null && $statement->fetch()): ?>
                <?php ++$i; ?>
                <tr class="<?= $i % 2 == 0 ? 'even' : 'odd' ?>">
                    <td><a class="cellMainLink"
                           href="<?= 'https://extratorrent.cc/torrent/' . $et_id ?>"><?= $title ?></a></td>
                    <td><?= strtoupper($hash) ?></td>
                    <?php $title = urlencode($title); ?>
                    <td class="lasttd"><a class="magnet-icon"
                                          href="<?= 'magnet:?xt=urn:btih:' . $hash . '&dn=' . $title . $trackers ?>"></a>
                    </td><td title="<?=date('jS F Y, g:i:sA', strtotime($date))?>"><?=$date?></td>
                </tr>
            <?php endwhile; ?>
            <?php if ($i == 0): ?>
                <tr>
                    <td colspan="5"><h3 class="text-center">No Results Found</h3></td>
                </tr>
            <?php endif; ?>
        </table>
    <?php endif; ?>


    <?php
    function clean_search($str)
    {
        return preg_replace('/[<>\'";]/', '', $str);
    }

    function advSearch($str)
    {
        $parts = explode(' ', str_replace(';', '', $str));
        $sql = '';
        foreach ($parts as $part) {
            $sql .= " AND title LIKE '%$part%'";
        }

        return ltrim($sql, ' AND ');
    }

    function time_ago($date)
    {
        if (empty($date)) {
            return 'No date provided';
        }
        $periods = array('second', 'minute', 'hour', 'day', 'week', 'month', 'year', 'decade');
        $lengths = array('60', '60', '24', '7', '4.35', '12', '10');
        $now = time();
        $unix_date = strtotime($date);
        // check validity of date
        if (empty($unix_date)) {
            return 'Bad date';
        }
        // is it future date or past date
        if ($now > $unix_date) {
            $difference = $now - $unix_date;
            $tense = 'ago';
        } else {
            $difference = $unix_date - $now;
            $tense = 'from now';
        }
        for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; ++$j) {
            $difference /= $lengths[$j];
        }
        $difference = round($difference);
        if ($difference != 1) {
            $periods[$j] .= 's';
        }

        return "$difference $periods[$j] {$tense}";
    }

    ?>
</div>
</body>
</html>
