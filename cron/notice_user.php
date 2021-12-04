<?php

use App\Database;

require_once dirname(__DIR__).'/vendor/autoload.php';

$env = Spyc::YAMLLoad(dirname(__DIR__).'/config/env.yaml');
foreach ($env as $key => $value)
{
    $_ENV[$key] = $value;
}

$from = "From: Benjamin Wagner <service@wagnerpictures.com>";
$db = new Database();
$now = date('Y-m-d H:i:s',strtotime('now'));
$dayAfterTomorrow = date('Y-m-d H:i',strtotime('+2 days'));
$tasks = $db->select('SELECT * FROM user u LEFT JOIN task t  on u.id = t.user_id  WHERE t.dueDate > "'.$now.'" AND t.dueDate < "'.$dayAfterTomorrow.'" AND  t.notice_user=1 AND t.done IS NULL ');

if($tasks)
{
    foreach ($tasks as $task) {
        if(!$task['email_sent']) {
            $receiver = $task['email'];
            $subject = 'Aufgabe "' . $task['description'] . '" steht für morgen an! ';
            $text = 'Denke an folgende Aufgabe für morgen:\n\n"' . $task['description'] . '" ';
            mail($receiver, $subject, $text, $from);
            $db->update("task", ['email_sent' => 1], ['id' => $task['id']]);
        }
    }
}
