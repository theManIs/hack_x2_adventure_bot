<?php

function create_table()
{
	$sql = "
	CREATE TABLE IF NOT EXISTS `$GLOBALS[bot_token]` (
		`message_id` int(11) NOT NULL,
		`message_body` text NOT NULL,
		`message_date` int(11) NOT NULL,
		PRIMARY KEY (`message_id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
	
	$cr = gpdo();
	
	$cr->exec($sql);
	
	// var_dump($cr->errorInfo());	
}

function reset_state($whom, $name, $body)
{
	if (preg_match('/начать заново/iu', $body)) {
		$GLOBALS['current_state'] = 0;
	
		if (key_exists('photo', $GLOBALS['scene'][0])) {
			send_p($whom, $GLOBALS['scene'][0]['photo']);
		}
	
		send_m($whom,  $GLOBALS['scene'][0]['text']);
		update_state($whom, 1, 0);
		
		$GLOBALS['is_sent'] = true;
	}
}

function get_team_state($team)
{
	$team_sql = "select state from bot_users where team = '$team';";
	$pdost = gpdo()->query($team_sql);
	// var_dump($pdost, $team_sql);
	if ($pdost) {
		return $pdost->fetchAll();
	} else {
		return $pdost;
	}
}

function change_a_team($chat_id, $team)
{	
	if (preg_match('/команда /ui', $team)) {
		$part1 = explode(' ', $team);
		$part2 = trim($part1[1]);
		$state = get_team_state($part2);
		$state = $state ? $state[0]['state'] : 0;
		
		$sql = "update bot_users set team = '$part2', state = '$state'  where chat_id = '$chat_id';";
		
		gpdo()->exec($sql);
		
		send_m($chat_id, 'Теперь вы в команде ' . $part2);
		
		$GLOBALS['is_sent'] = true;
	}
}

function get_user_data($whom)
{
	$user_sql = "SELECT * FROM bot_users  WHERE bot_users.chat_id = $whom;";
		
	$user_data = gpdo()->query($user_sql)->fetchAll();
	
	return $user_data;
}

function get_user_friends($team)
{
	$user_sql = "SELECT * FROM bot_users  WHERE team = '$team';";
	
	$user_data = gpdo()->query($user_sql);	
	
	if ($user_data) {
		$fetched = $user_data->fetchAll();
	}
	
	return $fetched;
}

function next_state($whom, $body, $next_state, $start_state = 0, $force = false)
{
	$scene = $GLOBALS['scene'];
	$state = $GLOBALS['current_state'];
	$GLOBALS['previus_state'] = $start_state;
	$word_match = false;
	
	if (key_exists($state, $scene)) {
		foreach ($scene[$state]['answers'] as $word) {
			if (preg_match("/$word/ui", $body)) {
				$word_match = true;
			}
		}
	}	
	
	if (
		key_exists('wrong', $scene[$state])
		&& key_exists('badtex', $scene[$state])
		&& !$word_match && $start_state === intval($state)
	) {		
		send_m($whom, $scene[$state]['badtex']);
		
		$GLOBALS['is_sent'] = true;
	} elseif ($word_match && $start_state === intval($state)) {		
		if (key_exists('photo', $scene[$state])) {
			send_p($whom, $scene[$state]['photo']);
		}
		
		send_m($whom, $scene[$state]['text']);		
		update_state($whom, $next_state);
		
		$GLOBALS['is_sent'] = true;
	}	
}

function send_p($whom, $file)
{
	$bot_url = $GLOBALS['bot_id'];
	$photo = "$bot_url/sendPhoto?chat_id=$whom&photo=sochnov.callkeeper.ru/infinite/ico/";
	
	file_get_contents("$photo$file");
}

function help_services($whom, $body, $team)
{
	if (preg_match('/моя команда/iu', $body)) {
		send_m($whom, 'Вы в команде: ' . $team);
		
		$GLOBALS['is_sent'] = true;
	} elseif (preg_match('/шутка/iu', $body)) {
		$randomize = rand(0, 8);
		
		send_m($whom, $GLOBALS['jokes'][$randomize]);
	
		$GLOBALS['is_sent'] = true;
	} elseif (preg_match('/хэлп/iu', $body)) {
		send_m($whom, $GLOBALS['help']);
	
		$GLOBALS['is_sent'] = true;
	}
}