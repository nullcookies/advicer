<?php
// Load composer
require __DIR__ . '/vendor/autoload.php';

$bot_api_key  = '424823219:AAHZxfCHW-x5_aRFOz3U8etuDGsmx9N0QSM';
$bot_username = 'Lawkzbot';
$hook_url = 'https://bot.edufix.kz/core/ruslan_hook.php';

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    // Set webhook
    $result = $telegram->setWebhook($hook_url);
    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // log telegram errors
    // echo $e->getMessage();
}