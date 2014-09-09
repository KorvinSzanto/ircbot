<?php
namespace Korvin\Irc\Message\Handler;

use Korvin\Irc\Message\Message;

class LogHandler implements HandlerInterface
{

    public function handleMessage(Message $message)
    {
        if ($message->getType() === 'PRIVMSG') {
            list($user, $nick, $host) = $message->getUserFromPrefix();
            $channel = $message->getArguments()[0];
            $text = $message->getArguments()[1];

            $message->getConnection()->log("({$user} : {$channel}) {$text}");
        } else {
            $message->getConnection()->log(trim($message->getRaw()));
        }
    }
}
