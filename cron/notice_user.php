<?php

namespace cron;

require_once('secret.php');
require_once('Database.php');

$secret = new secret();
$env = $secret->getSecret();

foreach ($env as $key => $value)
{
    $_ENV[$key] = $value;
}

$from = "From: ".$_ENV['EMAIL_SENDER_NAME']." <".$_ENV['EMAIL_SENDER_ADDRESS'].">";
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
