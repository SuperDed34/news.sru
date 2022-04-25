<?php
require_once "NewsDB.class.php";

$news = new NewsDB();
$errMsg="";
if($_SERVER["REQUEST_METHOD"]=="POST")
    require "save_news.inc.php";
if(isset($_GET['id'])) {
    require "delete_news.inc.php";
}
$dom = [];
if(!$dom) {
    $dom = new DOMDocument("1.0", "utf-8");
}
$rss= $dom->createElement("rss");
$title = $dom->createElement("title");
$link = $dom->createElement("link");
$descr=$dom->createElement("description");
$pubdate=$dom->createElement("pubdate");
$category = $dom ->createElement("category");
$item = $dom ->createElement("item");
$text = $dom ->createTextNode("$value['description']")
$dom->appendChild($rss);
$rss->appendChild($item);
$item->appendChild($title);
$item->appendChild($link);
$item->appendChild($descr);
$item->appendChild($pubdate);
$descr->appendChild($text);
$item->appendChild($category);

?>
<!DOCTYPE html>
<html>
<head>
	<title>Новостная лента</title>
	<meta charset="utf-8" />
</head>
<body>
  <h1>Последние новости</h1>
  <?php
   If($errMsg)
       echo "<h3>$errMsg</h3>";
  ?>
  <form action="<?= $_SERVER['PHP_SELF']; ?>" method="post">
    Заголовок новости:<br />
    <input type="text" name="title" /><br />
    Выберите категорию:<br />
    <select name="category">
      <option value="1">Политика</option>
      <option value="2">Культура</option>
      <option value="3">Спорт</option>
    </select>
    <br />
    Текст новости:<br />
    <textarea name="description" cols="50" rows="5"></textarea><br />
    Источник:<br />
    <input type="text" name="source" /><br />
    <br />
    <input type="submit" value="Добавить!" />
</form>
<?php
require "get_news.inc.php";
$posts=$news->getNews();
foreach($posts as $value){
    $date = date('d-m-Y H:i:s', $value["datetime"]);
?><table class="table table-bordered" border="1" cellpadding="5" cellspacing="0" width="100%">
  <tr>
      <td><?= $value['id']?></td>
      <td><?= $value['title']?></td>
      <td><?= $value['source']?></td>
      <td><?= $value['description']?></td>
      <td><?= $date ?></td>
      <td><a href="/news.php?id=<?=$value['id']?>">Удалить новость</a></td>
  </tr></table>
    <?php
  }

//print_r($posts);

?>
</body>
</html>