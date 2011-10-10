<!doctype html>
<html>
    <head>
        <title>System Error &ndash; Evolution&trade;</title>
        <style>
            body {
                background: #ddd;
                font-family: Tahoma, Sans, Lucida Grande, sans-serif;
                font-size: 12px;
            }
            .wrap {
                width: 520px;
                border: 1px solid #888;
                background: #fff;
                box-shadow: inset 0 0 8px #444;
                margin: 40px auto;
            }
            h1, h2, h3, h4, h5, p {
                padding: 0;
                margin: 20px;
            }
        </style>
    </head>
    <body>
        <div class="wrap">
            <h1>System Error &ndash; Evolution&trade;</h1>
            <h4>What to do?</h4>
            <p>Please go back and try to repeat your action.
                If that does not resolve your issue, please contact support.</p>
            <h4>Technical Details:</h4>
            <p><?php
                echo 'Uncaught Exception: ' . $exception->getMessage(); 
            ?></p>
        </div>
    </body>
</html>
<?php exit; ?>