<?php
echo '1';
if (function_exists('sqlite_open')) {
   echo 'Sqlite PHP extension loaded';
}