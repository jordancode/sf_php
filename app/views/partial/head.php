<head>
	<link type="text/css" rel="stylesheet" href="css/app.css"/>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
	<script src="//use.edgefonts.net/engagement.js"></script>
	<!-- Optional theme -->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
	
	<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
	<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	<!-- Latest compiled and minified JavaScript -->
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>


	<?php
		if(isset($params["stylesheets"]) && count($params['stylesheets'])) {
			foreach($params["stylesheets"] as $ss) {
				echo('<link type="text/css" rel="stylesheet" href="css/'.$ss.'.css"/>');
			}
		}
	?>
</head>