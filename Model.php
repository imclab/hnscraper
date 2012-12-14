<?php
class Model
{
    private static $_instance = Null;
    private static $_db;

    public function __construct()
    {
        $config = require __DIR__.'/Config.php';
        extract($config);

        $conn      = new MongoClient("mongodb://{$mongohost}:{$mongoport}");
        self::$_db = $conn->$mongodb;
    }

    public static function instance()
    {
        if (self::$_instance === Null)
            self::$_instance = new self;

        return self::$_instance;
    }

    public function hasLink($id)
    {
        return (boolean) self::$_db->links->count(array('id' => $id));
    }

    public function saveLink($id, $link)
    {
        $row = Array(
            'id'        => $id,
            'points'    => (int)$link['points'],
            'comments'  => (int)$link['comments'],
            'user'      => $link['user'],
            'url'       => $link['href'],
            'title'     => $link['title'],
            'keywords'  => explode(' ', $link['title']),
            'day'       => date('Y-m-d', strtotime("today")),
            'site'      => $link['site'],
            'frontpage' => 0,
            'time'      => time()
        );

        return (boolean) self::$_db->links->insert($row);
    }

    public function getLink($id)
    {
        return self::$_db->links->findOne(array('id' => $id));
    }

    public function updateLink($id, $points, $comments)
    {
        $dbLink = $this->getLink($id);

        return self::$_db->links->update(array('id' => $id), array('$set' => array('points' => (int)$points, 'comments' => (int)$comments, 'frontpage' => (time() - $dbLink['time']))));
    }
}
