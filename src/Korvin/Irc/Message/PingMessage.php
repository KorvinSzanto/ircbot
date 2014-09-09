<?php
namespace Korvin\Irc\Message;

class PingMessage extends Message
{

    public $time;

    public function getType()
    {
        return 'PING';
    }

}
