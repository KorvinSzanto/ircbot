<?php
namespace Korvin\Irc\Message\Handler;

use Korvin\Irc\Message\Message;
use Korvin\Irc\Message\PingMessage;
use Korvin\Irc\Message\PongMessage;

class PingHandler implements HandlerInterface
{

    public function handleMessage(Message $message)
    {
        if ($message instanceof PingMessage) {
            $pong = new PongMessage($message->getConnection());
            $pong->setArguments($message->getArguments());
            $pong->send();

            $message->getConnection()->log("PING!");
        }
    }

}
