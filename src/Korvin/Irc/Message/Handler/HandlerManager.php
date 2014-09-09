<?php
namespace Korvin\Irc\Message\Handler;

use Korvin\Irc\Message\Message;

class HandlerManager
{

    /** @var HandlerInterface[] $handlers */
    protected $handlers = array();

    public function registerHandler($callable)
    {
        if (is_callable($callable)) {
            $this->handlers[] = $callable();
        } elseif ($callable instanceof HandlerInterface) {
            $this->handlers[] = $callable;
        } elseif (class_exists($callable)) {
            $this->handlers[] = new $callable();
        }
    }

    public function getHandlers()
    {
        return $this->handlers;
    }

    public function handleMessage(Message $message)
    {
        foreach ($this->getHandlers() as $handler) {
            $handler->handleMessage($message);
        }
    }

}
