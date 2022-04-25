<?php
require_once "INewsDB.class.php";

class NewsDB implements INewsDB
{
    const DB_NAME = "news.db";
    const RSS_NAME = "rss.xml";
    const RSS_TITLE = "Новостная Лента";
    const RSS_LINK = "http://news.sru/news.php";


    private $_db = null;
    //private $result = "";

    function __get($name)
    {
        if ($name == "db") {
            return $this->_db;
        } else {
            throw new Exception("Unknown property!");
        }

    }

    function __construct()
    {
        $this->_db = new SQLite3(self::DB_NAME);
        if (filesize(self::DB_NAME) == 0) {
            $sqlCT = "CREATE TABLE msgs(
     id INTEGER PRIMARY KEY AUTOINCREMENT,
	title TEXT,
	category INTEGER,
	description TEXT,
	source TEXT,
	datetime INTEGER)";
            $this->_db->exec($sqlCT) or die($this->_db->lastErrorMsg());
            $sqlCat = "
	CREATE TABLE category(
	id INTEGER,
	name TEXT)";
            $this->_db->exec($sqlCat) or die($this->_db->lastErrorMsg());
            $sqlIns = "INSERT INTO category(id, name)
SELECT 1 as id, 'Политика' as name
UNION SELECT 2 as id, 'Культура' as name
UNION SELECT 3 as id, 'Спорт' as name";
            $this->_db->exec($sqlIns) or die($this->_db->lastErrorMsg());

        } else {
            $this->_db = new SQLite3(self::DB_NAME);
        }

    }


    function saveNews($title, $category, $description, $source)
    {
        $dt = time();
        $sql = "INSERT INTO msgs(title,category,description,source,datetime )VALUES('$title', $category,'$description','$source', $dt) ";
        return $this->_db->exec($sql);
    }

private function db2Arr($data){
        $arr =[];
        while ($row=$data->fetchArray(SQLITE3_ASSOC)){
            $arr[] = $row;
        }
        return $arr;
}
    function getNews()
    {
        $sqlGet = "SELECT msgs.id as id, title, category.name as category, description, source, datetime FROM msgs, category  WHERE category.id = msgs.category ORDER BY msgs.id DESC";
        $result = $this->_db->query($sqlGet);
        if(!$result) return false;
        return $this->db2Arr($result);

    }

    function deleteNews($id)
    {
    $sqlDel = "DELETE FROM msgs WHERE id = $id";

        if (!$this->_db->exec($sqlDel)) return false;
        return true;

    }

    function clearStr($data)
    {
        return $data = strip_tags($data);
        //return $this->_db->escapeString($data);
    }
    function clearInt($data){
        return abs((int)$data);
    }
    function __destruct()
    {
        unset($this->_db);
    }
    private function createRSS()
    {

    }

}