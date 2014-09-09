<?php
namespace Korvin\Irc\Message\Handler;

use Korvin\Irc\Message\Message;
use Korvin\Irc\Message\SendableMessage;

class GithubIssueHandler implements HandlerInterface
{

    protected $client;
    protected $issue_cache = array();
    protected $flood = array();

    public function __construct(\Github\Client $client)
    {
        $this->client = $client;
    }

    protected function truncate($string, $length, $elipsis = '...')
    {
        if (strlen($string) > $length) {
            return trim(substr($string, 0, $length - strlen($elipsis))) . $elipsis;
        }
        return $string;
    }

    public function handleMessage(Message $message)
    {
        if ($message->getType() === 'PRIVMSG') {
            list ($to, $string) = $message->getArguments();
            if (substr($to, 0, 1) === '#') {
                $matches = array();
                if (preg_match_all('/(?:issue|pull) #?(\d+)/i', $string, $matches)) {
                    foreach ($matches[1] as $match) {
                        if (isset($this->flood[$match]) && $this->flood[$match] > time()) {
                            continue;
                        }
                        $this->flood[$match] = time() + (5 * 60);
                        try {
                            $issue = $this->client->issue()->show('concrete5', 'concrete5-5.7.0', $match);
                            if ($issue) {

                                $title = $this->truncate($issue['title'], 64);
                                $response_string = "#$match: $title ({$issue['html_url']})";

                                $response = new SendableMessage($message->getConnection());
                                $response->setType('PRIVMSG');
                                $response->setArguments(
                                    array(
                                        $to,
                                        $response_string));
                                $response->send();
                            }
                        } catch (\Exception $e) {
                        };
                    }
                }
            }
        }
    }

}
