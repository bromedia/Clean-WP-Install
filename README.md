# Clean-WP-Install

This script was created to help remove malware from a Wordpress site. The script scans through the directory it's been placed in, detects whether or not Wordpress exists in that directory, and downloads a clean copy of Wordpress and any applicable plugins into a /new directory.

The script currently does not download themes, or any plugins that do not exist in the free Wordpress repository.

<b>Requires allow_url_fopen to be enabled on the server.</b>

<b>Still to do:</b>
<ul>
<li>Fix the part of the script that copies applicable DB settings to the new wp-config.php file.</li>
<li>Allow the /new directory to be customised.</li>
</ul>
