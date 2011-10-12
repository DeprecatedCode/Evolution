<!doctype html>
<html>
	<head>
		<title>System Error &ndash; Evolution&trade;</title>
		<style>
			body {
				background: #fff;
				font-family: Helvetica Neue,Tahoma, Sans, Lucida Grande, sans-serif;
				font-size: 16px;
			}
			.wrap {
				width: 800px;
				margin: 40px auto;
			}
			.wrap, .error {
			}
			h1, h2, h3, h4, h5, p {
				padding: 0;
				margin: 20px;
				text-align:center;
				line-height: 160%;
			}
			h2 {
				font-weight:200;
				color:gray;
				font-size:18px;
				padding-bottom:20px;
				border-bottom: 1px solid black;
			}
			code {
				background:#FFF4B2;
				padding: 2px 3px;
				color:#800000;
			}
			.error {
				margin: 20px;
				color:gray;
			}
			.error h4 {
				color: gray;
			}
		</style>
	</head>
	<body>
		<div class="wrap">
			<h1>System Error &ndash; Evolution&trade;</h1>
			<h2>Something is stopping this awesome framework from working the way it's meant to.</h2>
			<div class="details">
				<div class="error">
				<?php
					if(is_object($exception))
						echo '<h4>Uncaught '.get_class($exception).'</h4>';
					
					$message = $exception->getMessage();
					if(strlen($message) < 2)
						$message = 'An unknown error has occurred';
					$message = preg_replace('~(\/)~', '$1&#8203;', $message);
					echo '<p>'.preg_replace('/`([^`]*)`/x', '<code>$1</code>', $message).'</p>';
                    
                    $previous = $exception->getPrevious();
                    if(is_object($previous)) {
                        echo '<h4>Previously Uncaught '.get_class($previous).'</h4>';
                        echo '<pre>';
                        var_dump($previous->getTrace());
                        echo '</pre>';
                    }
				?>
				</div>
			</div>
		</div>
	</body>
</html>
<?php exit; ?>