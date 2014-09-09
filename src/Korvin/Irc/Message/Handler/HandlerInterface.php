<?php
namespace Korvin\Irc\Message\Handler;

use Korvin\Irc\Message\Message;

interface HandlerInterface
{

    public function handleMessage(Message $message);

}
