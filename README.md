# fiflet
Statistics tool for mining into Electronic Public Records of Norway.

This PHP applications is designed to run both as a client application (invoked
from a console, i.e. via a Cron job), and as a web application.

## Client application
Invoked from the command-line:

<code>
:~ php index.php [command]
</code>

[command] can be one of the following:

<ul>
  <li>_scrape_ Run the scraper to collect data.</li>
  <li>_list-suppliers_ Output the list of data suppliers with IDs.</li>
  <li>_regen-suppliers_ Update the list of data suppliers from server.</li>
  <li>_nuke-database_ Drop and recreate the database. Use with caution.</li>
</ul>

## Web application
