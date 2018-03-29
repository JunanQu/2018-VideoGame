<header>

  <nav id="menu">
    <ul>
      <!-- <h1 id="title"><?php echo $title; ?></h1> -->
      <?php
      foreach($tabs as $page_id => $page_name) {
        if ($page_id == $current_page_id) {
          $css_id = "class='current_page'";
        } else {
          $css_id = "class='current_page_alt'";
        }
        echo "<li><a " . $css_id . " href='" . $page_id. ".php'>$page_name</a></li>";
      }
      ?>
    </ul>
  </nav>
</header>
