<?php
namespace Korvin\Irc\Message;

class RawMessage extends Message
{

    public function getType()
    {
        return 'RAW';
    }

    public function getCode()
    {
        return $this->type;
    }

}
