<?php
if (isset($_SESSION['messages'])) {
    if (!empty($_SESSION['messages'])) {
        $errArr = $_SESSION['messages']; ?>
    <ul class='messages'>
      <?php
      foreach ($errArr as $error) {
          echo "<li>* $error *</li>";
      } ?>
    </ul>
    <?php
    }
}
unset($_SESSION['messages']);
?>
