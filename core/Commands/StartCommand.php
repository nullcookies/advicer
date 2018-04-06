<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\DB;
/**
 * Start command
 *
 * Gets executed when a user first starts using the bot.
 */
class StartCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'start';

    /**
     * @var string
     */
    protected $description = 'Start command';

    /**
     * @var string
     */
    protected $usage = '/start';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    /**
     * @var bool
     */
    protected $private_only = true;

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();

        $chat_id = $message->getChat()->getId();

        $text    = 'Здравствуйте! Вы связались с роботом юридических инструкций. '. PHP_EOL .'<b>Выберите нужный Вам пункт меню ниже.</b>'. PHP_EOL;
        // . PHP_EOL . - перенос строки в сообщении

        $keyb = new Keyboard("🙌 - Получить юр. помощь", "✒️ - Написать жалобу", "❓ - О ЮрСоветнике");
        $keyb->setOneTimeKeyboard(true);
        $keyb->setOneTimeKeyboard(true);

        $data = [
            'chat_id' => $chat_id,
            'text'    => $text,
            'parse_mode' => 'HTML',
            'reply_markup' => $keyb,
        ];

        return Request::sendMessage($data);
    }

}
