# Clean WP Install

This script was created to help combat Wordpress sites that have been infected by malware. The script scans through the directory it's been placed in, detects whether or not Wordpress exists in that directory, and downloads a clean copy of Wordpress and any applicable plugins into a /new directory, straight from the Wordpress repository.

The script currently does not work on themes, nor will it be able to download any plugins that do not exist in the free Wordpress repository.

Just place clean-wp-install.php inside the Wordpress directory, and click "Proceed".

<b>Important: </b>Requires allow_url_fopen to be enabled on the server.

<b>Still to do:</b>
<ul>
<li>Fix the part of the script that copies applicable DB settings to the new wp-config.php file.</li>
<li>Allow the /new directory to be customised.</li>
<li>Copy media from wp-content/uploads into the /new Wordpress directory.</li>
</ul>
