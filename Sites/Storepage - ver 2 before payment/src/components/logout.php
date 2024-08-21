<?php
session_start();
session_destroy();
// Respond with success
echo json_encode(["success" => true]);
?>
