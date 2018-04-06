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
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Request;

/**
 * Generic message command
 *
 * Gets executed when any type of message is sent.
 */
class GenericmessageCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'genericmessage';

    /**
     * @var string
     */
    protected $description = 'Handle generic message';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    /**
     * @var bool
     */
    protected $need_mysql = true;

    /**
     * Command execute method if MySQL is required but not available
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function executeNoDb()
    {
        // Do nothing
        return Request::emptyResponse();
    }

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        //If a conversation is busy, execute the conversation command after handling the message
        $conversation = new Conversation(
            $this->getMessage()->getFrom()->getId(),
            $this->getMessage()->getChat()->getId()
        );

        //Fetch conversation command if it exists and execute it
        if ($conversation->exists() && ($command = $conversation->getCommand())) {
            return $this->telegram->executeCommand($command);
        }


        $message = $this->getMessage();
        $mess_text = $message->getText();

        if(strpos($mess_text, 'помощь') !== false) {
            $chat_id = $message->getChat()->getId();
            $text    = 'Нужна юридическая помощь? '. PHP_EOL .'<b>Выберите категорию, подходящую Вам.</b>'. PHP_EOL;
            // . PHP_EOL . - перенос строки в сообщении

            $d = $this->get_cats(0);
            $all_buttons = array();
            foreach ($d as $key => $cat_data) {
                $keyboard_array = array();
                $keyboard_array[] = array('text' => $cat_data['name'], 'callback_data' => "c-".$cat_data['id']);
                $all_buttons[] = $keyboard_array;
            }

            $keyboard = array(
                "inline_keyboard" => $all_buttons
            );

            $data = [
                'chat_id' => $chat_id,
                'text'    => $text,
                'parse_mode' => 'HTML',
                'reply_markup' => $keyboard,
            ];

            return Request::sendMessage($data);
        }

    }

    public function get_cats($par_id){
        $stmt = DB::getPdo()->prepare("SELECT * FROM `cats` WHERE `parent_id`=? ");
        $stmt -> execute([$par_id]);
        $arr = array();
        while($data = $stmt -> fetch()){
            $arr[] = $data;
        }
        return $arr;
    }


}
