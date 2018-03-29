<?php include("includes/init.php");
  $current_page_id="index";
  $db = new PDO('sqlite:game.sqlite');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  function query($sql, $params, $datebase){
    $query = $datebase->prepare($sql);
    if ($query and $query->execute($params)) {
    $records = $query->fetchAll();
    return $records;
  }
  }

  function exec_sql_query($db, $sql, $params) {
  $query = $db->prepare($sql);
  if ($query and $query->execute($params)) {
    return $query;
  }
  return NULL;
  }
?>
<?php
  if (isset($_GET['order'])) {
  $order = htmlspecialchars($_GET['order']);
  if ($order == 'year') {
    $sql =  "SELECT * FROM video_games ORDER BY year DESC";
    $params = array();

    $store_rec = query($sql, $params, $db);
  } elseif($order == 'rating') {
    $sql =  "SELECT * FROM video_games ORDER BY rating DESC";
    $params = array();
    $store_rec = query($sql, $params, $db);
  } elseif($order == 'price'){
    $sql =  "SELECT * FROM video_games ORDER BY price DESC";
    $params = array();
    $store_rec = query($sql, $params, $db);
  } }
   else {
    $sql = 'SELECT * FROM video_games';
    $params = array();
    $store_rec = query($sql, $params, $db);
  }
?>

<!DOCTYPE html>
<html>

<head>
  <title>Store</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" />
  <script src="scripts/dropdown.js"></script>
</head>

<body>
  <?php include("includes/header.php");?>
  <div class="content">
    <div id="search">
      <?php
      if (isset($_GET['search'])) {
        $do_search = TRUE;
        $search = filter_input(INPUT_GET,'search', FILTER_SANITIZE_STRING);
        }
      else {
        $search = NULL;
        $do_search = FALSE;
        }
        ?>
      <form  class="search" method="get" action="index.php"  id="searchform">
        <input placeholder="search by name...." type="text" name="search">
	       <button  type="submit">Search</button>
      </form>
    </div>
    <div class="dropdown">
    <?php
      if ($do_search) {
    ?>
      <h1 class="header">Search Results</h1>
    <?php
      $sql = "SELECT * FROM video_games WHERE name LIKE '%'|| :search ||'%'";
      $params = array(
      ':search' => $search
      );
      }
      elseif(isset($_GET['submit-year'])){
    ?>
      <h1 class="header">Search Results</h1>
    <?php
      if (isset($_GET['year']) and isset($_GET['category'])) {
        foreach ($_GET['year'] as $year)
        foreach ($_GET['category'] as $tag) {
        $sql =  "SELECT * FROM video_games WHERE category LIKE '%'|| :tag ||'%'
        AND year LIKE '%'|| :year ||'%'";
        $params = array(':tag' => $tag, ':year' => $year);
        $store_rec = query($sql, $params, $db);
      }}elseif (empty($_GET['year']) and isset($_GET['category'])) {
        foreach ($_GET['category'] as $tag) {
        $sql =  "SELECT * FROM video_games WHERE category LIKE '%'|| :tag ||'%'";
        $params = array(':tag' => $tag);
        $store_rec = query($sql, $params, $db);
      }}elseif(empty($_GET['category']) and isset($_GET['year'])) {
        foreach ($_GET['year'] as $year){
        $sql =  "SELECT * FROM video_games WHERE year LIKE '%'|| :year ||'%'";
        $params = array(':year' => $year);
        $store_rec = query($sql, $params, $db);
      }}}elseif (isset($_GET['order'])) {
    ?>
      <h1 class="header">Search Results</h1>
    <?php
      if (isset($_GET['order'])) {
        $order = $_GET['order'];
        if ($order == 'year') {
          $sql =  "SELECT * FROM video_games ORDER BY year DESC";
          $params = array();
          $store_rec = query($sql, $params, $db);
        } elseif($order == 'rating') {
          $sql =  "SELECT * FROM video_games ORDER BY rating DESC";
          $params = array();
          $store_rec = query($sql, $params, $db);
        } elseif($order == 'price'){
          $sql =  "SELECT * FROM video_games ORDER BY price DESC";
          $params = array();
          $store_rec = query($sql, $params, $db);
        }
       }
      }
      else {
      ?>
        <h1 class= "header">All Product</h1>
      <?php
        $sql = "SELECT * FROM video_games";
        $params = array();
        }
        $store_rec = exec_sql_query($db, $sql, $params)->fetchAll();
      ?>
      <button onclick="myFunction()" class="dropbtn">Sort By</button>
        <div id="drop_down_menu" class="dropdown-content">
          <a href="index.php">All</a>
          <a href="index.php?order=year">Newly Released</a>
          <a href="index.php?order=rating">Top Rated</a>
          <a href="index.php?order=price">Price High To Low</a>
        </div>
    </div>
    <div id="table">
    <?php
    if (empty($store_rec)) {
        echo "<h2 id='out_of_stock'>Sorry, We Don't Have It!</h2>";?>
    <?php
    }else {
      echo "<table>
      <tr>
      <th id='name'> </th>
      <th id='category'>Name</th>
      <th id='year'>Released Year</th>
      <th id='price'>Price</th>
      </tr>";
      foreach ($store_rec as $record) {
        echo "<tr> <td> <img alt='icon-image' src='".htmlspecialchars($record['image'])."'></td>";
        echo "<td class='name'>".htmlspecialchars($record['name'])."</td>";
        echo "<td class='year'>".htmlspecialchars($record['year'])."</td>";
        echo "<td class='price'>".htmlspecialchars($record['price'])."</td>"."</tr>";
      }}
        echo "</table>";
    ?>
  </div>
    <div class="narrow_by_category">
      <h3>Narrow By Category</h3>
      <form class="cate" action='<?php echo $_SERVER['PHP_SELF']; ?>' method='get' name='form_filter'>
        <label class="container" >Action
          <input type="radio" value='action' name='category[]'>
          <span class="checkmark"></span>
        </label>
        <label class="container" >FPS
          <input type="checkbox" value='fps' name='category[]'>
          <span class="checkmark"></span>
        </label>
        <label class="container" >Multiplayer
          <input type="checkbox" value='multiplayer' name='category[]'>
          <span class="checkmark"></span>
        </label>
        <label class="container" >Open World
          <input type="checkbox" value='open-world' name='category[]'>
          <span class="checkmark"></span>
        </label>
        <label class="container" >RPG
          <input type="checkbox" value='rpg' name='category[]'>
          <span class="checkmark"></span>
        </label>
        <label class="container" >Strategy
          <input type="checkbox" value='strategy' name='category[]'>
          <span class="checkmark"></span>
        </label>
        <label class="container" >2012
          <input type="checkbox" value='2012' name='year[]'>
          <span class="checkmark"></span>
        </label>
        <label class="container" >2015
          <input type="checkbox" value='2015' name='year[]'>
          <span class="checkmark"></span>
        </label>
        <label class="container" >2017
          <input type="checkbox" value='2017' name='year[]'>
          <span class="checkmark"></span>
        </label>
        <label class="container" >2018
          <input type="checkbox" value='2018' name='year[]'>
          <span class="checkmark"></span>
        </label>
        <button class="form-form" name="submit-year" type="submit" value="Submit">Submit</button>
      </form>
    </div>
<footer class="footer">
  <p>© 2018 Designer. All rights reserved | Design by Junan.</p>
  <p> Images Cited From Steam.com</p>
</footer>



</div>
</body>
</html>
