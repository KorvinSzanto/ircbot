<?php
namespace Korvin\Irc\Message;

use Korvin\Irc\Connection\Connection;

interface MessageInterface
{

    /**
     * @return Connection
     */
    public function getConnection();

    /**
     * @return mixed
     */
    public function getMessage();

    /**
     * @return mixed
     */
    public function getPrefix();

    /**
     * @return mixed
     */
    public function getRaw();

    /**
     * @return mixed
     */
    public function getType();

}
