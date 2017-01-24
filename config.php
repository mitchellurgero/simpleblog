<?php
$config = array(
	"database"			=> "blog", //Database to store the blog data.
	"db_location"		=> "/var/www/databases", //Location of the above database.
	"site_name"			=> "SimpleBlog", //Name of your blog site.
	"site_auth"			=> "Mitchell Urgero<info@urgero.org>", //Site Author
	"site_desc"			=> "This is a SimpleBlog site!", //A description of your site.
	"site_theme"		=> "readable", //Site theme (See themes folder for details)
	"site_email"		=> "webmaster@example.com", //Email to contact site admin (Leave blank to disable)
	"disp_limit"		=> 500, //When viewing all posts, this is the limit to how many chars. per post to display before moving on to the next one.
	"top_html"			=> '<div class="page-header"><center><h3>Welcome to SimpleBlog!</h3></center></div>', //HTML to display just above the blog posts. Can be anything.(Like maybe a welcome text?)
	"bottom_html"		=> '<center>Copyright &copy; 2017</center>', //HTML to display just under the blog posts. Can be anything. (Copyright maybe?)
	
	
);

//Top menu bar items. Home should always be there.
$menu = array(
	"Home"			=> array("./", false),//array("LINK", OPEN_IN_NEW_WINDOW)
	"Example"		=> array("https://example.com", true),
	
	);