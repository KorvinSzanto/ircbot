<?php
namespace Korvin\Irc\Connection;

use Korvin\Irc\Message\Handler\GithubIssueHandler;
use Korvin\Irc\Message\Handler\HandlerManager;
use Korvin\Irc\Message\Handler\LogHandler;
use Korvin\Irc\Message\Handler\PingHandler;
use Korvin\Irc\Message\Message;
use Korvin\Irc\Message\PingMessage;
use Korvin\Irc\Message\RawMessage;

class Connection
{

    /** @var HandlerManager $manager */
    protected $manager;
    protected $server;
    protected $port;
    protected $nick;

    protected $socket;
    protected $connected;

    public function __construct(HandlerManager $manager, $server, $port, $nick, $channel)
    {
        $this->manager = $manager;
        $this->server = $server;
        $this->port = $port;
        $this->nick = $nick;
        $this->channel = $channel;
    }

    public function connect() {
        $this->connected = true;
        $this->socket = fsockopen($this->server, $this->port);

        $this->sendRaw("NICK {$this->nick}");
        $this->sendRaw(":derp USER derp derp derp :Derp Derp");
        $this->sendRaw("JOIN {$this->channel}");

        $this->connection();
    }

    protected function connection()
    {
        $this->connected = true;
        while ($this->connected && $data = fgets($this->socket)) {
            $default_message = new Message($this, $data);

            switch ($default_message->getType()) {
                case 'PING':
                    $message = new PingMessage($this, $data);
                    break;
                default:
                    if (is_numeric($default_message->getType())) {
                        $message = new RawMessage($this, $data);
                        break;
                    }
                    $message = $default_message;
                    break;
            }

            $this->manager->handleMessage($message);
        }

        if ($this->connected) {
            $this->connection();
        }
    }

    public function log($message)
    {
        echo date('m/d/y h:m:s ') . $message . PHP_EOL;
    }

    public function sendRaw($raw)
    {
        echo "SENDING: " . $raw . PHP_EOL;
        fwrite($this->socket, $raw . PHP_EOL);
    }

    public function disconnect()
    {
        $this->sendRaw('QUIT :Disconnecting.');
        $this->connected = false;
    }

}
