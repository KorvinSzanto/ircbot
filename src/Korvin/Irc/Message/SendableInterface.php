<?php
namespace Korvin\Irc\Message;

interface SendableInterface extends MessageInterface
{

    public function send();

}
