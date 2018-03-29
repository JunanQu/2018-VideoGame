<?php include("includes/init.php");
  $current_page_id="forum";
  $db = new PDO('sqlite:comments.sqlite');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  function query($sql, $params, $datebase){
    $query = $datebase->prepare($sql);
    if ($query and $query->execute($params)) {
    $records = $query->fetchAll();
    return $records;
  }
  }
  function exec_sql_query($db, $sql, $params) {
  $db = new PDO('sqlite:comments.sqlite');
  $query = $db->prepare($sql);
  if ($query and $query->execute($params)) {
    return $query;
  }
  return NULL;
  }

  $sql = 'SELECT * FROM discussion';
  $params = array();
  $store_rec = query($sql, $params, $db);
?>
<?php
$games = exec_sql_query($db, "SELECT DISTINCT video_game FROM discussion", NULL)->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html>

<head>
  <title>Forum</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" />
  <script src="scripts/dropdown.js" ></script>
</head>

<body>
  <?php include("includes/header.php");?>
  <div id="content">
    <h2 id="reviews">All Reviews</h2>
      <?php
      if (isset($_post['search'])) {
        $do_search = TRUE;
        $search = filter_input(INPUT_POST,'search', FILTER_SANITIZE_STRING);
      }
      else {
      $search = NULL;
      $do_search = FALSE;
      }
      ?>

    <div>
      <?php
          if (isset($_POST['submit_review'])) {
          header("location:forum.php");
          $id = 7;
          $video_game = filter_input(INPUT_POST, 'video_game', FILTER_SANITIZE_STRING);
          $reviewer_name = filter_input(INPUT_POST,'reviewer_name', FILTER_SANITIZE_STRING);
          $rating = filter_input(INPUT_POST, 'rating_select', FILTER_VALIDATE_INT);
          $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
          $query = $db->prepare("INSERT INTO discussion (id, video_game, rating, review_comment, reviewer_name) VALUES (:id, :video_game, :rating_select, :reviewer_name, :comment)");
          $params = array(
            ':id'=> $id,
            ':video_game' => $video_game,
            ':rating_select' => $rating,
            ':comment' => $comment,
            ':reviewer_name' => $reviewer_name
          );
          if ($query and $query->execute($params)) {
          return $query;
          $store_rec = exec_sql_query($sql, $params, $db);
          echo "<meta http-equiv='refresh' content='0'>";
          }}
      ?>
      <?php
      echo "<table>
      <tr>
      <th id='name'>Video Game Reviewed</th>
      <th id='rating'>Rating</th>
      <th id='comments'>Comments</th>
      <th id='reviewer'>Reviewed By</th>
      </tr>";

      foreach ($store_rec as $record) {
          echo "<tr><td>".$record['video_game']."</td>";
          echo "<td class='rating'>".$record['rating']."</td>";
          echo "<td class='comment'>".$record['review_comment']."</td>";
          echo "<td class='reviewer'>".$record['reviewer_name']."</td>"."</tr>";
        }
          echo "</table>";
      ?>
      </div>

      <div id="game_side_bar">
      <h3>Review A Game</h3>
      <form id="game_review_form" action='forum.php' method='post' name='form_filter'>
        <ul>
        <li>
          <label class="game_select">Which Game:</label>
          <select class="game_select" name="video_game" required>
            <option value="" selected disabled>Choose Game</option>
            <?php
            foreach($games as $game) {
              echo "<option value=\"" . $game . "\">" . $game . "</option>";
            }
            ?>
          </select>
        </li>
        <li>
          <label>Rating:</label>
        <select class="game_select" name="rating_select" required size="1">
          <option value="10">10</option>
          <option value="9">9</option>
          <option value="8">8</option>
          <option value="7">7</option>
          <option value="6">6</option>
          <option value="5" >5</option>
          <option value="4">4</option>
          <option value="3">3</option>
          <option value="2">2</option>
          <option value="1">1</option>
        </select>
        </li>
        <li>
          <label>Name: </label>
        </li>
        <li>
          <input type="text" name="reviewer_name" required>
        </li>
        <li>
          <label id="comment_label">Comments:</label>
        </li>
        <li>
          <textarea id="textarea" name="comment" cols="40" rows="5" placeholder="Comments...." required></textarea>
        </li>
        <li>
        <button class="form-form" name="submit_review" type="submit" value="Submit">Submit</button>
        </li>
      </ul>
      </form>
    </div>
<footer class="footer">
  <p>© 2018 Designer. All rights reserved | Design by Junan.</p>
  <p> Images Cited From Steam.com</p>
</footer>


</div>


</body>
</html>
