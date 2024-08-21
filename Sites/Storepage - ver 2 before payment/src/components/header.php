<?php
session_start(); // Ensure session is started here
?>
<!-- src/components/header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Include the common CSS and JS files here -->
    <link rel="stylesheet" href="src/css/nav.css">
    <link rel="stylesheet" href="src/css/mystyle.css">
    <link rel="stylesheet" href="src/css/formstyle.css">
    <script src="src/js/navbar.js" defer></script>
    <script src="src/js/logout.js" defer></script>
</head>
<body>

    <?php include 'navbar.php'; ?>
