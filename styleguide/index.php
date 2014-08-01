<?php

    require 'src/vendor/autoload.php';

    $styleguide = new BasePatterns('patterns');

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Style Guide</title>

        <!-- Styleguide styles -->
        <link href="assets/vendor/prism/prism.css" rel="stylesheet">
        <link href="assets/css/styleguide.css" rel="stylesheet">

        <!-- Project CSS: Replace with site styles -->
        <!--link href="assets/css/bootstrap.min.css" rel="stylesheet"-->
    </head>
    <body>
        <div class="sg-container">
            <div class="sg-main">
                <?php echo $styleguide->content(); ?>
            </div>
            <div class="sg-sidebar">
                <?php echo $styleguide->menu(); ?>
            </div>
        </div>
    
    <!-- jQuery -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.2.min.js"><\/script>')</script>

    <!-- Styleguide JS -->
    <script src="assets/vendor/prism/prism.min.js"></script>
    <script src="assets/vendor/zeroclipboard/ZeroClipboard.min.js"></script>
    <script src="assets/js/styleguide.js"></script>

    <!-- Project JS -->
    <!--script src="assets/js/bootstrap.min.js"></script-->

    </body>
</html>