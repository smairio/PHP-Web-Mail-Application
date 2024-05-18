<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="static_files/logo.png" alt="Logo">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'employee.php' ? 'active' : ''; ?>">
                    <a class="nav-link" href="employee.php">Employees</a>
                </li>
                <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'department.php' ? 'active' : ''; ?>">
                    <a class="nav-link" href="department.php">Department</a>
                </li>
                <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'smtp.php' ? 'active' : ''; ?>">
                    <a class="nav-link" href="smtp.php">Smtp</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0" method="post">
                <button class="btn btn-outline-light my-2 my-sm-0" type="submit" name="logout">Logout</button>
            </form>
            <?php

if(isset($_POST['logout'])) {
    $_SESSION = array();

    session_destroy();

    header("Location: login.php");
    exit();
}
?>
        </div>
    </div>
</nav>
