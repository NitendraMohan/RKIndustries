<header id="header" class="header">
<div class="top-left">
    <div class="navbar-header">
        <a class="navbar-brand" href="index.html"><img src="images/logo.png" alt="Logo"></a>
        <a class="navbar-brand hidden" href="index.html"><img src="images/logo2.png" alt="Logo"></a>
        <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>
    </div>
</div>
<div class="top-right">
    <div class="header-menu">
        <div class="user-area dropdown float-right">
            <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Welcome <?php echo ucfirst($username)  ?></a>
            <div class="user-menu dropdown-menu">
            <a class="nav-link" href="logout.php"><i class="fa fa-power-off"></i>Logout</a>
            </div>
        </div>
    </div>
</div>
</header>