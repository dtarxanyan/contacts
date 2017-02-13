<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Contacts</title>
    <!-- Bootstrap Core CSS -->
    <link href="<?php echo $this->getAssetsPath(); ?>css/bootstrap.min.css" rel="stylesheet">
    <?php foreach ($this->getPageSpecificCss() as $css) { ?>
        <link href="<?php echo $this->getAssetsPath(); ?>css/pages/<?php echo $css ?>" rel="stylesheet">
    <?php } ?>
    <!-- Custom CSS -->
    <style>
        body {
            padding-top: 70px;
        }
    </style>
</head>
<body>
<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <?php if (isset($_SESSION['login'])) { ?>
                    <li>
                        <a href="<?php echo $this->getBaseUrl() ?>contacts/list">Contacts</a>
                    </li>
                    <li>
                        <a href="<?php echo $this->getBaseUrl() ?>site/logout">Logout</a>
                    </li>
                <?php } else { ?>
                    <li>
                        <a href="<?php echo $this->getBaseUrl() ?>site/login">Login</a>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>
<!-- Page Content -->
<div class="container">
    <div class="row">
        <?php echo $viewContent; ?>
    </div>
    <!-- /.row -->
</div>
<!-- /.container -->
<!-- jQuery Version 1.11.1 -->
<script src="<?php echo $this->getAssetsPath(); ?>js/jquery.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="<?php echo $this->getAssetsPath(); ?>js/bootstrap.min.js"></script>
<?php foreach ($this->getPageSpecificJs() as $js) { ?>
    <script src="<?php echo $this->getAssetsPath(); ?>js/pages/<?php echo $js ?>"></script>
<?php } ?>
</body>
</html>
