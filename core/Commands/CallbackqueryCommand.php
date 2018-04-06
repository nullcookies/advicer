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

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\DB;
/**
 * Callback query command
 *
 * This command handles all callback queries sent via inline keyboard buttons.
 *
 * @see InlinekeyboardCommand.php
 */
class CallbackqueryCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'callbackquery';

    /**
     * @var string
     */
    protected $description = 'Reply to callback query';

    /**
     * @var string
     */
    protected $version = '1.1.1';

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $callback_query    = $this->getCallbackQuery();
        $callback_query_id = $callback_query->getId();
        $callback_data     = $callback_query->getData();
        $chat_id = $callback_query->getMessage()->getChat()->getId();
        $message_id = $callback_query->getMessage()->getMessageId();

        switch ($callback_data[0]) {
            case 'c':
            		$q_data = explode("-", $callback_data);
                	$cat_id = $q_data[1];
               		$this->show_podcategory($cat_id, $chat_id, $message_id);
                break;
            case 'i' :
            		$q_data = explode("-", $callback_data);
            		$ins_id = $q_data[1];
            		$this->start_instruction($ins_id, $chat_id);
            	break;
            case 'a' :
            		$q_data = explode("-", $callback_data);
            		$ans_id = $q_data[1];
            		$this->next_quest($ans_id, $chat_id);
            	break;
            case 't' :
            		$q_data = explode("-", $callback_data);
            		$q_id = $q_data[1];
            		$this->show_tips($q_id, $chat_id);
            	break;
            default:
                # code...
                break;
        }

        $data = [
            'callback_query_id' => $callback_query_id,
            'text'              => "",
            'show_alert'        => $callback_data === 'thumb up',
            'cache_time'        => 5,
        ];

        return Request::answerCallbackQuery($data);
    }
    //Показываем все подкатегории
    public function show_podcategory($cat_id, $chat_id, $message_id){
    	$d = $this->get_cats($cat_id);


    	//Если в категории есть инструкции
    	if($this->does_have_instructions($cat_id)){
    		$this->show_instructions($cat_id, $chat_id);
    	}

        $all_buttons = array();
		foreach ($d as $key => $cat_data) {
           	$keyboard_array = array();
           	$keyboard_array[] = array('text' => $cat_data['name'], 'callback_data' => "c-".$cat_data['id']);
           	$all_buttons[] = $keyboard_array;
        }

        $selected_cat = $this->get_cat_info($cat_id);
        $selected_cat_name = $selected_cat['name'];

        if(strlen($selected_cat_name)<=1){
        	$text = 'Здравствуйте! Вы связались с роботом юридических инструкций. '. PHP_EOL .'<b>Выберите категорию:</b> '. PHP_EOL;
        	$initial = true;
        }else{
        	$text = "Вы выбрали категорию '<b>".$selected_cat_name."</b>'";
        	$initial = false;
        } 


        //Кнопка назад
        if(!$initial){

        	$par_id = $selected_cat['parent_id'];

        	$keyboard_array = array();
        	$keyboard_array[] = array('text' => "Назад", 'callback_data' => "c-".$par_id);
        	$all_buttons[] = $keyboard_array;
        }

        $keyboard = array(
            "inline_keyboard" => $all_buttons
        );

        $data_edit = [
                'chat_id'    => $chat_id,
                'message_id' => $message_id,
                'text' => $text,
                'reply_markup' => $keyboard,
                'parse_mode' => 'HTML',
            ];

        $result = Request::editMessageText($data_edit);

    }

    public function get_cat_info($cat_id){
    	$stmt = DB::getPdo()->prepare("SELECT * FROM `cats` WHERE `id`=? ");
    	$stmt -> execute([$cat_id]);
    	return $stmt->fetch();
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

    public function does_have_instructions($cat_id){
    	$stmt = DB::getPdo()->prepare("SELECT * FROM `inses` WHERE `cat_id`=? ");
    	if($stmt->execute([$cat_id])){
    		$num = $stmt->rowCount();
    		if($num>=1)return true;else return false;
    	}
    }

    public function show_instructions($cat_id, $chat_id){
    	$stmt = DB::getPdo()->prepare("SELECT * FROM `inses` WHERE `cat_id`=? ");
    	if($stmt->execute([$cat_id])){
    		
    		$selected_cat = $this->get_cat_info($cat_id);
        	$selected_cat_name = $selected_cat['name'];

    		$text = "В категории '<b>".$selected_cat_name."</b>' есть следующие инструкции. " .PHP_EOL. "Выберите номер под данным сообщением для ее просмотра.";
    		$text.= PHP_EOL . "--------------------------------------------------------";
    		$k = 1;
    		$keyboard_array = array();
    		while ($data = $stmt->fetch()) {
    			$text .= PHP_EOL ."<b>".$k." - </b>".$data['name'];
        		$keyboard_array[] = array('text' => "-".$k."-", 'callback_data' => "i-".$data['id']);
    			$k++;
    		}

    		$keyboard = new Inlinekeyboard($keyboard_array);

    		$data2 = [
            	'chat_id' => $chat_id,
            	'text'    => $text,
            	'parse_mode' => 'HTML',
            	'reply_markup' => $keyboard,
	        ];

         	return Request::sendMessage($data2);

    	}
    }

    public function start_instruction($ins_id, $chat_id){

    	$circled_numbers = array("0", "①", "②", "③", "④", "⑤", "⑥", "⑦", "⑧", "⑨", "⑩");

    	$stmt = DB::getPdo()->prepare("SELECT * FROM `quest` WHERE `ins_id`=? AND `local_id`=1 ");
    	if($stmt->execute([$ins_id])){
    		$data = $stmt->fetch();

    		$stmt2 = DB::getPdo()->prepare("SELECT * FROM `inses` WHERE `id`=? ");
    		if($stmt2->execute([$ins_id])){
    			$ins_data = $stmt2->fetch();
    			$ins_name = $ins_data['name'];
    			$text = "Вы выбрали инструкцию '<b>".$ins_name."</b>'.".PHP_EOL."Выбирайте подходящий ответ и следуйте инструкции.";
    			$text.= PHP_EOL . "--------------------------------------------------------";

    			$infos_stmt = DB::getPdo()->prepare("SELECT * FROM `info` WHERE `quest_id`=? ");

    			if($infos_stmt -> execute([$data['id']])){
    				$infos_num = $infos_stmt->rowCount();
    				while($info_data = $infos_stmt -> fetch()){
    					$data['text'] = preg_replace("/<".$info_data['inf_name'].">/", "<b>", $data['text']);
    					$data['text'] = preg_replace("/<\/".$info_data['inf_name'].">/", "</b>", $data['text']);
    				}
    			}

    			$text .= PHP_EOL . $data['text'];

    			$text.= PHP_EOL . "--------------------------------------------------------";

    			$anses_stmt = DB::getPdo()->prepare("SELECT * FROM `answer` WHERE `quest_id`=? ");

    			if($anses_stmt->execute([$data['id']])){
    				$k = 1;
    				$keyboard_array = array();
    				while ($ans_data = $anses_stmt->fetch()) {
    					$text .= PHP_EOL . "<b>".$k." - </b>".$ans_data['text'];
    					$keyboard_array[] = array('text' => "-".$k."-", 'callback_data' => "a-".$ans_data['id']);
    					$k++; 
    				}
 					if($infos_num>0){   
 						$show_tips_key = array();		
    					$show_tips_key[] = array('text' => "Показать подсказки", 'callback_data' => "t-".$data['id']);
    					$keyboard = new Inlinekeyboard($keyboard_array, $show_tips_key);
    				}else{
    					$keyboard = new Inlinekeyboard($keyboard_array);
    				}

    				$data_to_send = [
	            		'chat_id' => $chat_id,
            			'text'    => $text,
            			'parse_mode' => 'HTML',
	            		'reply_markup' => $keyboard,
	        		];
	        		return Request::sendMessage($data_to_send);
	        	}
    		}

    	}
    }

    public function next_quest($ans_id, $chat_id){
    	$stmt5 = DB::getPdo()->prepare("SELECT * FROM `answer` WHERE `id`=? ");

    	if($stmt5->execute([$ans_id])){
    		$data5 = $stmt5 -> fetch();
    		$q_link = $data5['q_link'];
    		$ins_id = $data5['ins_id'];

    		$stmt = DB::getPdo()->prepare("SELECT * FROM `quest` WHERE `local_id`=? AND `ins_id`=? ");
    		if($stmt->execute([$q_link, $ins_id])){
    			$data = $stmt->fetch();
    			$q_id = $data['id'];

    			$infos_stmt = DB::getPdo()->prepare("SELECT * FROM `info` WHERE `quest_id`=? ");

    			if($infos_stmt -> execute([$q_id])){
    				$infos_num = $infos_stmt->rowCount();
    				while($info_data = $infos_stmt -> fetch()){
    					$data['text'] = preg_replace("/<".$info_data['inf_name'].">/", "<b>", $data['text']);
    					$data['text'] = preg_replace("/<\/".$info_data['inf_name'].">/", "</b>", $data['text']);
    				}
    			}

    			$text = $data['text'];

    			$text .= PHP_EOL . "--------------------------------------------------------";

    			$anses_stmt = DB::getPdo()->prepare("SELECT * FROM `answer` WHERE `quest_id`=? ");

    			if($anses_stmt->execute([$data['id']])){
    				$k = 1;
    				$keyboard_array = array();
    				while ($ans_data = $anses_stmt->fetch()) {
    					$text .= PHP_EOL . "<b>".$k." - </b>".$ans_data['text'];
    					$keyboard_array[] = array('text' => "-".$k."-", 'callback_data' => "a-".$ans_data['id']);
    					$k++; 
    				}

    				if($infos_num>0){   
 						$show_tips_key = array();		
    					$show_tips_key[] = array('text' => "Показать подсказки", 'callback_data' => "t-".$data['id']);
    					$keyboard = new Inlinekeyboard($keyboard_array, $show_tips_key);
    				}else{
    					$keyboard = new Inlinekeyboard($keyboard_array);
    				}

    				$data_to_send = [
	            		'chat_id' => $chat_id,
            			'text'    => $text,
            			'parse_mode' => 'HTML',
	            		'reply_markup' => $keyboard,
	        		];
	        		return Request::sendMessage($data_to_send);
	        	}
	    	}
		}

    }


    public function show_tips($q_id, $chat_id){
    	$infos_stmt = DB::getPdo()->prepare("SELECT * FROM `info` WHERE `quest_id`=? ");

    			if($infos_stmt -> execute([$q_id])){
    				$infos_num = $infos_stmt->rowCount();

    				$q_stmt = DB::getPdo()->prepare("SELECT * FROM `quest` WHERE `id`=? ");
    				if($q_stmt->execute([$q_id])){
    					$q_data = $q_stmt->fetch();
    					$q_text = $q_data['text'];
    					$text = "<b>Подсказки к шагу инструкции</b>";
    					$text .= PHP_EOL . "------------------------------------";
    					$k = 1;
    					while($info_data = $infos_stmt -> fetch()){
	    					$info_name = $info_data['inf_name'];
    						$info_text = $info_data['text'];
    						if($info_name == "img"){
    							preg_match("/imglink\[(.*?)\]/", $info_text, $img_link);
    							preg_match("/text\[(.*?)\]/", $info_text, $img_text);
    							$img_link_to_send = $img_link[1];
    							$img_text_to_send = $img_text[1];

    							$this->send_image($img_text_to_send, $img_link_to_send, $chat_id);

    							$k++;
    						}else{
    							preg_match("/<".$info_name.">(.*?)<\/".$info_name.">/", $q_text, $matches);
    							$info_text = preg_replace("/\<br\>/", PHP_EOL , $info_text);
    							$text .= PHP_EOL . "<b>".$matches[1]."</b> - ".$info_text;
    							$text .= PHP_EOL . "------------------------------------";
    						}

    					}

    					$data_to_send = [
	            			'chat_id' => $chat_id,
            				'text'    => $text,
            				'parse_mode' => 'HTML',
	        			];
	        			return Request::sendMessage($data_to_send);

    				}
    			}
    }

    public function send_image($caption, $img_link, $chat_id){

    	$data = [
            'chat_id' => $chat_id,
        ];

    	$data['caption'] = $caption;
        $data['photo']   = Request::encodeFile($img_link);


        return Request::sendPhoto($data);
    }

}
