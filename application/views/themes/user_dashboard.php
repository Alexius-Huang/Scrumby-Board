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

    <!-- Load Bootstrap.js -->
    <script src="<?php echo base_url(); ?>assets/themes/default/js/bootstrap.js"></script>
    
    <!-- Load React.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/react/15.2.1/react.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/react/15.2.1/react-dom.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/babel-core/5.8.34/browser.min.js"></script>

    <!-- Load Sortable.js -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/Sortable/1.4.2/Sortable.min.js"></script>

    <!-- Load DataTable -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>

    <!-- Load Font-Awesome Icons CDN -->
    <script src="https://use.fontawesome.com/094ac5a51d.js"></script>

    <!-- Load Custom Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Quicksand:700" rel="stylesheet">
    <!-- To import font, use: font-family: 'Quicksand', sans-serif; -->
    <link href="https://fonts.googleapis.com/css?family=Rubik:300" rel="stylesheet">
    <!-- To import font, use: font-family: 'Rubik', sans-serif; -->

    <!-- Enbed JavaScript -->
    <script>
      $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
      });
    </script>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

</head>

  <body>

    <!-- Navbar Section -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-left" href="/user"><img id="navbar-brand-img" src="<?php echo base_url(); ?>assets/images/ScrumbyBoardBrand.png" alt="Scrumby Board Brand" /></a>
        </div>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="<?php echo (
                $url === '/user/profile/'.$this->session->userdata['id'] ? '#my_profile' : '/user/profile/.'.$this->session->userdata['id'].'#my_profile'
              ); ?>"
            ><i class="fa fa-user" aria-hidden="true"></i> My Profile</a></li>
            <li><a href="<?php echo (
                $url === '/user/profile/'.$this->session->userdata['id'] ? '#my_project' : '/user/profile/.'.$this->session->userdata['id'].'#my_project'
              ); ?>"
            ><i class="fa fa-cube" aria-hidden="true"></i> My Project</a></li>
            <li><a href="<?php echo (
                $url === '/user/profile/'.$this->session->userdata['id'] ? '#recent_activity' : '/user/profile/.'.$this->session->userdata['id'].'#recent_activity'
              ); ?>"
            ><i class="fa fa-tasks" aria-hidden="true"></i> Recent Activity</a></li>
            <li class="active dropdown"><a
                href="#"
                class="dropdown-toggle"
                data-toggle="dropdown"
                role="button"
                aria-haspopup="true"
                aria-expanded="false"
              ><i class="fa fa-star" aria-hidden="true"></i> Hello! <?php echo $this->session->username; ?> <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li class="dropdown-header">Actions</li>
                <li><a href="/project/new_project"><i class="fa fa-plus" aria-hidden="true"></i> New Project</a></li>
                <li role="separator" class="divider"></li>
                <li class="dropdown-header"><i class="fa fa-key" aria-hidden="true"></i> Account</li>
                <li><a href="#"><i class="fa fa-bars" aria-hidden="true"></i> History</a></li>
                <li><a href="<?php echo base_url(); ?>user/setting/<?php echo $this->session->userdata['id']; ?>"><i class="fa fa-cog" aria-hidden="true"></i> Setting</a></li>
                <li><a href="/user_authentication/signout"><i class="fa fa-sign-out" aria-hidden="true"></i> Sign Out</a></li>  
              </ul>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

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
</body>
</html>
