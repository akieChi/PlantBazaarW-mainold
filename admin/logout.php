<?php
session_start();
session_unset();
session_destroy();
header('Location: secureacess2024.php');
exit();
?>
