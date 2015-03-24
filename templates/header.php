<div class="header">
    <div class="header-text">

        <img src="../images/logo.png" style="border: 0; float: left; width: 100px; position: relative; top: -35px; left: -15px;" />

        
        <h2>You are logged in as: <span class="username-color"><?php echo $user['username']; ?></span></h2>
        <div class="user-informations">
        <h3><?php
            echo $user['firstname'];
            echo ' ';
            echo $user['lastname']
            ?></h3>
        <h3><?php echo $user['duty']; ?></h3>
        </div>
    </div>
    <div class="logout">

        <a href="../utilitiesView/logout.php"> log out! </a>


    </div>
</div>