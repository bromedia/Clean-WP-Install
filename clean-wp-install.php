<!DOCTYPE html>
<html>
<head>
	<title>Check for Wordpress installation</title>
</head>
<body>
	<p>This code will create a directory named 'new' inside whichever directory you've placed it in. It'll then download a clean version of Wordpress and your plugins into the new Wordpress installation.</p>
	<?php
function is_dir_empty($dir)
{
    if (!is_readable($dir)) return NULL;
    return (count(scandir($dir)) == 2);
}
$debug = '';
// Check if the Proceed button is clicked
if (isset($_POST['proceed']))
{
    // Check if Wordpress installation exists
    if (file_exists('wp-load.php'))
    {
        // Create new folder if it doesn't exist already
        if (!file_exists('new'))
        {
            mkdir('new', 0777, true);
            $debug .= '\'new\' folder created successfully!<br />';
        }
        else
        {
            $debug .= '\'new\' folder exists, copying Wordpress into that.<br />';
        }
        if (is_dir_empty('new'))
        {
            // Download the latest version of Wordpress
            $url = 'https://wordpress.org/latest.zip';
            $zipFile = 'latest.zip';
            file_put_contents($zipFile, file_get_contents($url));
            $debug .= 'Wordpress downloaded successfully!<br />';
            // Extract the contents of the zip file to the 'new' directory
            $zip = new ZipArchive;
            if ($zip->open($zipFile) === true)
            {
                // Extract everything inside the 'wordpress' folder to the 'new' directory
                $zip->extractTo('new');
                $zip->close();
                $debug .= 'Wordpress extracted successfully!<br />';
                // Move everything from '/new/wordpress' to '/new'
                if (!is_dir_empty('new/wordpress'))
                {
                    $dir = 'new/wordpress';
                    if (is_dir($dir))
                    {
                        $objects = scandir($dir);
                        foreach ($objects as $object)
                        {
                            if ($object != "." && $object != "..")
                            {
                                rename($dir . '/' . $object, 'new/' . $object);
                            }
                        }
                        rmdir($dir);
                        $debug .= 'Wordpress moved to \'new\' folder!<br />';
                        
                        /*
						// Check if both files exist
						if (file_exists('wp-config.php') && file_exists('new/wp-config-sample.php')) {
						
						  // Read contents of wp-config.php
						  $wp_config = file_get_contents('wp-config.php');
						
						  // Extract values for DB_NAME, DB_USER, DB_PASSWORD, and DB_HOST
						  preg_match("/define\(\s*\'DB_NAME\',\s*\'(.+)\'\s*\);/", $wp_config, $db_name);
						  preg_match("/define\(\s*\'DB_USER\',\s*\'(.+)\'\s*\);/", $wp_config, $db_user);
						  preg_match("/define\(\s*\'DB_PASSWORD\',\s*\'(.+)\'\s*\);/", $wp_config, $db_password);
						  preg_match("/define\(\s*\'DB_HOST\',\s*\'(.+)\'\s*\);/", $wp_config, $db_host);
						
						  // Read contents of wp-config-sample.php
						  $wp_config_sample = file_get_contents('/new/wp-config-sample.php');
						
						  // Replace values with those from wp-config.php
						  $wp_config_sample = preg_replace("/define\(\s*\'DB_NAME\',\s*\'(.+)\'\s*\);/", "define('DB_NAME', '" . $db_name[1] . "');", $wp_config_sample);
						  $wp_config_sample = preg_replace("/define\(\s*\'DB_USER\',\s*\'(.+)\'\s*\);/", "define('DB_USER', '" . $db_user[1] . "');", $wp_config_sample);
						  $wp_config_sample = preg_replace("/define\(\s*\'DB_PASSWORD\',\s*\'(.+)\'\s*\);/", "define('DB_PASSWORD', '" . $db_password[1] . "');", $wp_config_sample);
						  $wp_config_sample = preg_replace("/define\(\s*\'DB_HOST\',\s*\'(.+)\'\s*\);/", "define('DB_HOST', '" . $db_host[1] . "');", $wp_config_sample);
						
						  // Write modified contents to wp-config.php
						  file_put_contents('/wp-config.php', $wp_config_sample);
						
						  // Rename wp-config-sample.php to wp-config.php
						  rename('new/wp-config-sample.php', 'new/wp-config.php');
						
						  $debug .= 'Files updated successfully.';
						
						} else {
							$debug .= 'One or both wp-config.php files do not exist.';
						}
                        */
                    }
                    // Get the list of plugins
                    $plugins_dir = 'wp-content/plugins/';
                    $plugins = scandir($plugins_dir);
                    foreach ($plugins as $plugin)
                    {
                        if (is_dir($plugins_dir . $plugin) && $plugin != '.' && $plugin != '..')
                        {
                            // Download the plugin zip file
                            $plugin_url = 'https://downloads.wordpress.org/plugin/' . $plugin . '.zip';
                            $file_headers = @get_headers($plugin_url);
                            if ($file_headers && strpos($file_headers[0], '200'))
                            {
                                $plugin_zip = 'new/' . $plugins_dir . $plugin . '.zip';
                                file_put_contents($plugin_zip, file_get_contents($plugin_url));

                                // Extract the plugin zip file to the 'new' directory
                                $zip = new ZipArchive;
                                if ($zip->open($plugin_zip) === true)
                                {
                                    $zip->extractTo('new/' . $plugins_dir);
                                    $zip->close();
                                    $debug .= $plugin . ' downloaded and extracted successfully!<br />';

                                    // Delete the plugin zip file after extraction
                                    unlink($plugin_zip);
                                }
                                else
                                {
                                    $debug .= 'Failed to extract ' . $plugin . '<br />';
                                }
                            }
                            else
                            {
                                $debug .= $plugin_url . ' is not a valid URL. <br />';
                            }
                        }
                    }
                }
                else
                {
                    $debug .= 'Failed to move Wordpress to \'new\' folder!<br />';
                }
            }
            else
            {
                $debug .= 'Failed to extract Wordpress!<br />';
            }
        }
        else
        {
            $debug .= '\'new\' folder is not empty!<br />';
        }
        // Delete the zip file after extraction
        unlink($zipFile);
    }
    else
    {
        $debug .= 'Wordpress installation not found!<br />';
    }
}
else
{
        // Display the form if the Proceed button is not clicked
        $debug .= '<form method="post">';
        $debug .= '<input type="submit" name="proceed" value="Proceed">';
        $debug .= '</form>';
}
echo $debug;
?>

</body>
</html>
