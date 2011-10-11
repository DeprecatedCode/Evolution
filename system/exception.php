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
                margin: 40px auto;
            }
            .wrap, .error {
                box-shadow: inset 0 0 8px #444;
            }
            h1, h2, h3, h4, h5, p {
                padding: 0;
                margin: 20px;
                line-height: 160%;
            }
            code {
                background: rgba(255,255,255,0.5);
                padding: 2px 3px 1px;
                border: 1px solid #dcb;
                font-size: 11px;
            }
            .error {
                background: #fdc;
                border: 1px solid #800;
                margin: 20px;
            }
            .error h4 {
                color: #800;
            }
            .details {
                opacity: 0.3;
            }
            .details:hover {
                opacity: 1;
            }
        </style>
    </head>
    <body>
        <div class="wrap">
            <h1>System Error &ndash; Evolution&trade;</h1>
            <h3>What to do?</h3>
            <p>Please go back and try to repeat your action.
                If that does not resolve your issue, please contact support.</p>
            <div class="details">
                <h3>Technical Details:</h3>
                <div class="error">
                <?php
                    if(is_object($exception))
                        echo '<h4>Uncaught '.get_class($exception).'</h4>';
                    
                    $message = $exception->getMessage();
                    if(strlen($message) < 2)
                        $message = 'An unknown error has occurred';
                    $message = preg_replace('~(\/)~', '$1&#8203;', $message);
                    echo '<p>'.preg_replace('/`([^`]*)`/x', '<code>$1</code>', $message).'</p>'; 
                ?>
                </div>
            </div>
        </div>
    </body>
</html>
<?php exit; ?>