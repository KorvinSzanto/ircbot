<?php
namespace Korvin\Irc\Message;

use Korvin\Irc\Connection\Connection;

class Message implements MessageInterface
{

    protected $connection;
    protected $raw;
    protected $prefix;
    protected $message;
    protected $type;
    protected $arguments;

    public function __construct(Connection $connection, $raw)
    {
        $this->connection = $connection;
        $this->raw = $raw;

        $raw = trim($raw);
        if (substr($raw, 0, 1) === ':') {
            $pos = strpos($raw, ' ');
            $this->prefix = substr($raw, 1, $pos);
            $raw = substr($raw, $pos + 1);
        }

        $pos = strpos($raw, ' ');
        $this->message = $raw;
        $this->type = substr($raw, 0, $pos);
        $raw = substr($raw, $pos + 1);

        while ($raw) {
            if (substr($raw, 0, 1) === ':') {
                $this->arguments[] = substr($raw, 1);
                break;
            }

            $pos = strpos($raw, ' ');
            if ($pos === false) {
                $this->arguments[] = $raw;
                break;
            }
            $this->arguments[] = substr($raw, 0, $pos);
            $raw = substr($raw, $pos + 1);
        }
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

    /**
     * @return mixed
     */
    public function getRaw()
    {
        return $this->raw;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getArguments()
    {
        return $this->arguments;
    }

    public function getUserFromPrefix()
    {
        $matches = array();
        if (preg_match_all('/(.+?)!(.+?)@(.+?)/', $this->prefix, $matches)) {
            $username = $matches[1][0];
            $nick = $matches[2][0];
            $host = $matches[2][0];

            return array(
                $username,
                $nick,
                $host
            );
        }
        return array(
            null,
            $this->prefix,
            null
        );
    }

}
