<?php
namespace Korvin\Irc\Message;

use Korvin\Irc\Connection\Connection;

class SendableMessage implements SendableInterface
{

    protected $connection;
    protected $prefix;
    protected $message;
    protected $type;
    protected $arguments = array();

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @return mixed
     */
    public function getRaw()
    {
        $args = $this->getArguments();
        $last_arg = array_pop($args);

        if (strpos($last_arg, ' ') !== false) {
            $last_arg = ':' . $last_arg;
        }
        $args[] = $last_arg;

        return ($this->getPrefix() ? ':' . $this->getPrefix() : '') . ' ' . $this->getType() . ' ' . implode(
            ' ',
            $args);
    }

    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @param mixed $prefix
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;
    }

    public function send()
    {
        $this->connection->sendRaw($this->getRaw());
    }

}
