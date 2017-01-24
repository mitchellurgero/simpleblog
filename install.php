<?php
include('config.php');
include('db.php');

if(isset($_POST['user'])){
	//Build DB and table structure.
	$db;
	if($db = new JSONDatabase($config['database'], $config['db_location'])){
		echo 'Created DB successfully.'."<br>";
	} else {
		echo 'Failed DB Creation.. Could not create DB folder structure.'."<br>";
		die();
	}
	if($db->check_table("users")){
		die("You cannot add a new user through the install.php script!");
	}
	if($db->create_table("posts")){
		echo 'Created posts table successfully.'."<br>";
	} else {
		echo 'Could not create posts table.'."<br>";
		die();
	}
	if($db->create_table("users")){
		echo 'Created users table successfully.'."<br>";
	} else {
		echo 'Could not create users table.'."<br>";
		die();
	}
	if($db->insert("users", '{"username":"'.$_POST['user'].'","password":"'.password_hash($_POST['password'], PASSWORD_DEFAULT).'"}', 0)){
		echo 'Added user <b>'.$_POST['user'].'</b> to users table'."<br>";
		echo '<br><text style="color:green">Install was a success!!</text>';
		echo '<br><br><text style="color:red">PLEASE BE SURE TO REMOVE install.php WHEN THE INSTALLATION IS DONE!!</tex>';
	} else {
		echo 'Could not create requested username.'."<br>";
	}
} else {
	echo '<br>You are about to install SimpleBlog to "'.$config['db_location'].'" with the database of "'.$config['database'].'"';
	echo '<br>';
	echo 'Please fill out the following simple form to install SimpleBlog:<br><br>';
	?>
<form action="install.php" method="POST">
	Username: <input type="text" name="user" placeholder="Username"><br>
	Password: <input type="password" name="password" placeholder="Password"><br>
	<button type="submit">Install Now</button>
</form>
	<?php
}



?>