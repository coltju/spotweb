<?php
require_once "settings.php";
require_once "lib/SpotDb.php";

# Verzeker onszelf ervan dat we niet vanuit de webserver uitgevoerd worden
if (isset($_SERVER['SERVER_PROTOCOL'])) {
	die("Sorry, db-upgrade.php kan enkel vanuit de server zelf uitgevoerd worden, niet via de webbrowser!");
} # if

# Risky warning, might trip up some stuff
if (@!file_exists(getcwd() . '/' . basename($argv[0]))) {
	chdir(__DIR__);
} # if


try {
	# Instantieeer een struct object
	$db = new SpotDb($settings['db']);
	$db->connect();

	switch($settings['db']['engine']) {	
		case 'mysql'			:
		case 'pdo_mysql'		: $dbStruct = new SpotStruct_mysql($db); break;
		
		case 'pdo_sqlite'		: $dbStruct = new SpotStruct_sqlite($db); break;
		
		default					: throw new Exception("Onbekende database engine");
	} # switch
	
	echo "Updating schema..\r\n";
	$dbStruct->createDatabase();
	$dbStruct->updateSchema();
	echo "Schema update done\r\n";

} catch(Exception $x) {
	echo "Database schema upgrade mislukt: \r\n";
	echo "   " . $x->getMessage() . "\r\n";
	die(1);
} # catch

