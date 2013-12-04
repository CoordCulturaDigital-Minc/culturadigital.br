<?php

/*
Version 0.6
*/

$dbc_labels['configuration'] = "Configuration";

$dbc_labels['activate'] = "Enable";

$dbc_labels['timeout'] = "Expire a cached query after";
$dbc_labels['timeout_desc'] = "minutes. <em>(Expired files are deleted automatically)</em>";

//$dbc_labels['tablesfilter'] = "Tables to cache"; - removed

$dbc_labels['saved'] = "Settings saved.";
$dbc_labels['cantsave'] = "Settings can't be saved. Please <a href=\"http://codex.wordpress.org/Changing_File_Permissions\" target=\"blank\">chmod 755</a> file <u>config.ini</u>";
$dbc_labels['activated'] = "Caching activated.";
$dbc_labels['notactivated'] = "Caching can't be activated. Please <a href=\"http://codex.wordpress.org/Changing_File_Permissions\" target=\"blank\">chmod 755</a> <u>wp-content</u> folder";
$dbc_labels['deactivated'] = "Caching deactivated. Cache files deleted.";
$dbc_labels['cleaned'] = "Cache files deleted.";
$dbc_labels['expiredcleaned'] = "Expired cache files deleted.";

$dbc_labels['additional'] = "Additional options";
$dbc_labels['filter'] = "Cache filter";
$dbc_labels['filterdescription'] = "Do not cache queries that contains this input contents. Divide different filters with '|' (verical line, '_posts|_postmeta')";
$dbc_labels['loadstat'] = "Load stats template";
$dbc_labels['loadstattemplate'] = "<!-- Generated in {timer} seconds. Made {queries} queries to database and {cached} cached queries. Memory used - {memory} -->";
$dbc_labels['loadstatdescription'] = "It shows resourses usage statistics in your template footer. To disable view just leave this field empty.<br/>
{timer} - generation time, {queries} - count of queries to DB, {cached} - cached queries, {memory} - memory";

$dbc_labels['save'] = "Save";
$dbc_labels['clear'] = "Clear the cache";
$dbc_labels['clearold'] = "Clear the expired cache";

$dbc_labels['thanks'] = "";

?>