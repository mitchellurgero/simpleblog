<?php
session_start();
include('../config.php');
include('../db.php');
if(file_exists("../install.php")){
	die("You must install SimpleBlog and Remove install.php to login to the admin panel!");
}
if(isset($_POST['user'])){
	//Check login.
	$db = new JSONDatabase($config['database'], $config['db_location']);
	if($db->check_table("users")){
		$pass = $_POST['password'];
		$user = $_POST['user'];
		$u = $db->select("users", "username", $user);
		if(count($u) >= 1){
			foreach($u as $it){
				//print_r($it);
				if(password_verify($pass, $it['password'])){
					$_SESSION['username'] = $user;
				} else {
					$_SESSION['error'] = '<text style="color:red">Invalid username or password.</text>';
				}
				break;
			}
		}
	}	
		
}
head();
if(isset($_SESSION['username'])){
	$db = new JSONDatabase($config['database'], $config['db_location']);
	if(isset($_POST['content'])){
		$content = escapeJsonString($_POST['content']);
		$name = $_SESSION['username'];
		$title = $_POST['name'];
		$date = '';
		$num = '';
		if(isset($_POST['date'])){
			$date = $_POST['date'];
		} else {
			$date = date("D M d, Y G:i");
		}
		if($_POST['where'] !== "NULL"){
			$num = (int)$_POST['where'];
		} else {
			$num = null;
		}
		$json = '{"author":"'.$name.'", "content":"'.$content.'", "data_created":"'.$date.'", "name":"'.$title.'"}';
		if($num === null){
			if(!$db->insert("posts", $json)){
				$_SESSION['error'] = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>There was an error saving that post, please try again.</div>';
			} else {
				$_SESSION['error'] = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>The post was saved successfully!</div>';
			}
		} else {
			if(!$db->insert("posts", $json, $num)){
				$_SESSION['error'] = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>There was an error saving that post, please try again.</div>';
			} else {
				$_SESSION['error'] = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>The post was saved successfully!</div>';
			}
		}
	}
	echo '';
	?>
</style>
<body>
<div class="container">
	<?php
	if(isset($_SESSION['error'])){
		echo '<br>';
		echo $_SESSION['error']; unset($_SESSION['error']);
	}
	?>
	<div class="row">
		<div class="col-md-4">
			<h3>Welcome to the Administator Dashboard</h3>
			<p><b>Logged in as <?php echo $_SESSION['username']; ?></b></p>
			<p><a href="logout.php">Logout</a></p>
			<p><a href="index.php">New Post</a></p>
			<p><h4>Current Posts:</h4></p>
			<p><ul>
			<?php
		$posts = array_reverse($db->select("posts"));
		foreach($posts as $value){
			echo '<li>Post from: <a href="?post='.$value['row_id'].'"><b>'.$value['data_created'].'</b></a></li>'."\r\n";
		}
			?>
			</ul></p>
		</div>
		<div class="col-md-8">
		<?php
		if(isset($_GET['post'])){
			$p1 = array_reverse($db->select("posts", "row_id", $_GET['post']));
			$p2 = '';
			$p3 = '';
			$p4 = '';
			foreach($p1 as $value){
				$p2 = $value['data_created'];
				$p3 = $value['name'];
				$p4 = $value['content'];
				break;
			}	
		}
		?>
			<div class="page-header"><h3>Create New Blog Post:</h3></div>
			<form action="index.php" method="POST">
				<input type="hidden" name="where" value="<?php if(isset($_GET['post'])){ echo $_GET['post'];} else {echo "NULL";} ?>">
				<?php if(isset($_GET['post'])){ echo '<input type="hidden" name="date" value="'.$p2.'">'; } ?>
				<input type="text" class="form-control" name="name" value="<?php if(isset($_GET['post'])){ echo $p3; } ?>" placeholder="Post name"><br>
				<textarea id="summernote" name="content"><?php if(isset($_GET['post'])){ echo $p4; } else {echo "Begin writing your blog post here!";} ?></textarea>
				<button type="submit" class="btn btn-primary pull-right">Save Post</button>
			</form>
		</div>
	</div>
	
</div>
<script>
$(document).ready(function() {
$('#summernote').summernote({
  height: "50%",                 // set editor height
  minHeight: null,             // set minimum height of editor
  maxHeight: null,             // set maximum height of editor
  focus: true                  // set focus to editable area after initializing summernote
});
});

</script>
</body>
	<?php
} else {
	//login page
	
	echo '';
	?>
<style>
body {
  background-color:#fff;
  -webkit-font-smoothing: antialiased;
  font: normal 14px Roboto,arial,sans-serif;
}

.container {
    padding: 25px;
    position: fixed;
}

.form-login {
    background-color: #EDEDED;
    padding-top: 10px;
    padding-bottom: 20px;
    padding-left: 20px;
    padding-right: 20px;
    border-radius: 15px;
    border-color:#d2d2d2;
    border-width: 5px;
    box-shadow:0 1px 0 #cfcfcf;
}

h4 { 
 border:0 solid #fff; 
 border-bottom-width:1px;
 padding-bottom:10px;
 text-align: center;
}

.form-control {
    border-radius: 10px;
}

.wrapper {
    text-align: center;
}
</style>
<body style="background-color:#7f8c8d">

<div class="container">
    <div class="row">
    	<form action="index.php" method="POST">
        <div class="col-lg-4 col-lg-offset-5">
        	<center><h4><?php echo $config['site_name']; ?> Admin Login</h4></center>
            <div class="form-login">
            <h4>Welcome back.</h4>
            <input type="text" id="userName" name="user" class="form-control input-sm chat-input" placeholder="username" />
            </br>
            <input type="password" id="userPassword" name="password" class="form-control input-sm chat-input" placeholder="password" />
            </br>
            <div class="wrapper">
            <span class="group-btn">     
                <button type="submit" class="btn btn-primary btn-md">login <i class="fa fa-sign-in"></i></submit>
            </span>
            </div>
            <br>
            <a href="../">Back to Blog</a>
            <br>
            <p>
            <?php if(isset($_SESSION['error'])){
            	echo $_SESSION['error'];
            	unset($_SESSION['error']);
            }
            ?>
            </p>
            </div>
        </div>
        </form>
    </div>
</div>

</body>
</html>
	<?php
}


function head(){
	global $config;
	echo '';
	?>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="<?php echo $config['site_desc']; ?>">
		<meta name="author" content="<?php echo $config['site_auth']; ?>">
		<title><?php echo $config['site_name']; ?> | Admin Panel</title>
		<link href="<?php echo '../themes/'.$config['site_theme'].'/'; ?>css/bootstrap.min.css" rel="stylesheet">
		<script src="<?php echo '../themes/'.$config['site_theme'].'/'; ?>js/jquery.js"></script>
		<script src="<?php echo '../themes/'.$config['site_theme'].'/'; ?>js/bootstrap.min.js"></script>
		<link href="//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.css" rel="stylesheet">
		<script src="//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.js"></script>
	</head>
	<?php
}
function escapeJsonString($value) { # list from www.json.org: (\b backspace, \f formfeed)
    $escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
    $replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
    $result = str_replace($escapers, $replacements, $value);
    return $result;
}
?>