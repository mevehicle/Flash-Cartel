<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php 
    if(isset($pageTitle))
        {
        echo "<title>{$pageTitle}</title>";
        }else{
        echo "<title>Flash Cartel</title>";
        }
    ?>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/styles.css">
  </head>