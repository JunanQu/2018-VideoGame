<?php
$title = "Junan's Game Shop";

$tabs = array(
  "index" => "Store",
  "forum" => "Forum",
  "about" => "About",
  "support" => "Support",
);

//a debug helper function from lab 05
function handle_db_error($exception) {
  echo '<p><strong>' . htmlspecialchars('Exception : ' . $exception->getMessage()) . '</strong></p>';
}

?>
