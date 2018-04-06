<?php
// Load composer
require __DIR__ . '/vendor/autoload.php';

$bot_api_key  = '';
$bot_username = 'Lawkzbot';

// Define all paths for your custom commands in this array (leave as empty array if not used)
$commands_paths = [
    __DIR__ . '/Commands/',
];

$mysql_credentials = [
        'host'     => 'localhost',
        'user'     => 'p-114_bot',
        'password' => 'SamfilE159!',
        'database' => 'p-11424_bot',
];

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    // Add this line inside the try{}
	$telegram->addCommandsPaths($commands_paths);

    /*
	Longman\TelegramBot\TelegramLog::initErrorLog(__DIR__ . "/{$bot_username}_error.log");
    Longman\TelegramBot\TelegramLog::initDebugLog(__DIR__ . "/{$bot_username}_debug.log");
    Longman\TelegramBot\TelegramLog::initUpdateLog(__DIR__ . "/{$bot_username}_update.log");
	*/
    
    $telegram->enableMySql($mysql_credentials);	

    // Handle telegram webhook request
    $telegram->handle();

} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Silence is golden!
    // log telegram errors
     Longman\TelegramBot\TelegramLog::error($e);
     echo $e->getMessage();
}