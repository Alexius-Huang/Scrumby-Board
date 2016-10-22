<html lang="en">
	<head>
		<title><?php echo $title; ?></title>
		<meta name="resource-type" content="document" />
		<meta name="robots" content="all, index, follow"/>
		<meta name="googlebot" content="all, index, follow" />
	<?php
	if(!empty($meta))
	foreach($meta as $name=>$content){
		echo "\n\t\t";
		?><meta name="<?php echo $name; ?>" content="<?php echo $content; ?>" /><?php
			 }
	echo "\n";

	if(!empty($canonical))
	{
		echo "\n\t\t";
		?><link rel="canonical" href="<?php echo $canonical?>" /><?php

	}
	echo "\n\t";

	foreach($css as $file){
	 	echo "\n\t\t";
		?><link rel="stylesheet" href="<?php echo $file; ?>" type="text/css" /><?php
	} echo "\n\t";

?>

    <!-- Le styles -->
    <link href="<?php echo base_url(); ?>assets/themes/default/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/themes/default/css/general.css" rel="stylesheet">

    <!-- Load jQuery -->
    <script src="https://code.jquery.com/jquery-1.10.1.min.js" integrity="sha256-SDf34fFWX/ZnUozXXEH0AeB+Ip3hvRsjLwp6QNTEb3k=" crossorigin="anonymous"></script>
    
    <!-- Load React.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/react/15.2.1/react.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/react/15.2.1/react-dom.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/babel-core/5.8.34/browser.min.js"></script>

    <!-- Load Font-Awesome Icons CDN -->
    <script src="https://use.fontawesome.com/094ac5a51d.js"></script>

    <!-- Load Custom Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Quicksand:700" rel="stylesheet">
    <!-- To import font, use: font-family: 'Quicksand', sans-serif; -->
    <link href="https://fonts.googleapis.com/css?family=Rubik:300" rel="stylesheet">
    <!-- To import font, use: font-family: 'Rubik', sans-serif; -->
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

</head>

  <body>

    <!-- Navbar Section -->

    <!-- Main Section -->
    <?php echo $output;?>

    <!-- Footer Section -->

    <!-- Load JavaScript Files -->
    <?php 
      foreach($js as $file){
          echo "\n\t\t";
          ?><script src="<?php echo $file; ?>"></script><?php
      } echo "\n\t";
    ?>

    </div> <!-- /container -->
</body></html>
