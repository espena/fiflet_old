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
  <li>**scrape** Run the scraper to collect data.</li>
  <li>**list-suppliers** Output the list of data suppliers with IDs.</li>
  <li>**regen-suppliers** Update the list of data suppliers from server.</li>
  <li>**nuke-database** Drop and recreate the database. Use with caution.</li>
</ul>

## Web application
