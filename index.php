<?php 
session_start();
include('db.php');
include('config.php');
$db = new JSONDatabase($config['database'], $config['db_location']);
//Always display head:
head();
if(isset($_GET['post'])){
	body($_GET['post']);
} else {
	body();
}
foot();
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
		<title><?php echo $config['site_name']; ?></title>
		<link href="<?php echo 'themes/'.$config['site_theme'].'/'; ?>css/bootstrap.min.css" rel="stylesheet">
		<script src="<?php echo 'themes/'.$config['site_theme'].'/'; ?>js/jquery.js"></script>
		<script src="<?php echo 'themes/'.$config['site_theme'].'/'; ?>js/bootstrap.min.js"></script>
	</head>
	<?php
}
function body($post = null){
	global $config, $db;
	if(!$db->check_table("posts") && !$db->check_table("users")){
		echo '<center><h2>You must install SimpleBlog first!!<br><br><small>Make sure to edit config.php first!</small></h2></center>
		<br><center><a href="install.php">Begin install now</a></center>';
		die();
	}
	echo "<body>\r\n";
	//Menu...
	menu();
	
	echo '<div class="container">'."\r\n";
	if($post === null){
		echo $config['top_html'];
		echo '';
		$posts = array_reverse($db->select("posts"));
		foreach($posts as $value){
			?>
			<div class="row">
				<div class="panel panel-default">
					<div class="panel-heading"><h4><b><?php echo $value['name']; ?></b></h4>
					<small>Written By: <?php echo $value['author'] ?></small><br>
					<small>Posted: <?php echo $value['data_created']; ?></small>
					</div>
					<div class="panel-body"><?php echo strip_tags(substr($value['content'],0, $config['disp_limit'])); ?></div>
					<div class="panel-footer"><a href="?post=<?php echo $value['row_id']; ?>">Read More...</a></div>
				</div>
			</div>
			<?php
		}
	} else {
		//Get selected post.
		$posts = array_reverse($db->select("posts", "row_id", $post));
		foreach($posts as $value){
			?>
			<div class="row">
				<div class="page-header">
					<h4><b><?php echo $value['name']; ?></b></h4>
					<small>Written By: <?php echo $value['author'] ?></small><br>
					<small>Posted: <?php echo $value['data_created']; ?></small>
				</div>
				<?php echo $value['content']; ?>
			</div>
			<?php
		}
		
	}
	echo "</div>\r\n</body>\r\n";
}
function foot(){
	global $config;
	echo '';
	?>
	<br><br><br>
	<?php echo $config['bottom_html']; ?>
	<br><br><br>
	<?php
}
function menu(){
	global $config, $menu;
	echo '';
	?>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand"><?php echo $config['site_name']; ?></a>
    </div>
    <ul class="nav navbar-nav">
      <?php
      foreach($menu as $key=>$item){
      	if($item[1]){
      		echo '<li><a href="'.$item[0].'" target="_blank">'.$key.'</a></li>'."\r\n";
      	} else {
      		echo '<li><a href="'.$item[0].'">'.$key.'</a></li>'."\r\n";
      	}
      	
      }
      ?>
    </ul>
  </div>
</nav>

	<?php
}

?>