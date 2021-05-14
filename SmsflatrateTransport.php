<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Notifier\Bridge\Smsflatrate;
// namespace App\Transport;

use Symfony\Component\Notifier\Exception\LogicException;
use Symfony\Component\Notifier\Exception\TransportException;
use Symfony\Component\Notifier\Message\MessageInterface;
use Symfony\Component\Notifier\Message\SentMessage;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\Transport\AbstractTransport;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @author Waldemar Dell <waldi.dell@gmail.com>
 *
 * @experimental in 5.2
 */
final class SmsflatrateTransport extends AbstractTransport
{
    protected const HOST = 'www.smsflatrate.net';

    private $authToken;
    private $from;
    private $type;
    private $flash;
    private $status;

    /**
     * SmsflatrateTransport constructor.
     * @param string $authToken
     * @param string $from
     * @param string $type
     * @param int $flash
     * @param int $status
     * @param HttpClientInterface|null $client
     * @param EventDispatcherInterface|null $dispatcher
     */
    public function __construct(
        string $authToken,
        string $from = 'anonymous',
        string $type = 'auto3or4',
        int $flash = 0,
        int $status = 0,
        HttpClientInterface $client = null,
        EventDispatcherInterface $dispatcher = null
    ) {
        $this->authToken = $authToken;
        $this->from = $from;
        $this->type = $type;
        $this->flash = $flash;
        $this->status = $status;

        parent::__construct($client, $dispatcher);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('smsflatrate://%s/schnittstelle.php?key=%s&from=%s&type=%s&flash=%s&status=%s,', $this->getEndpoint(), $this->authToken, $this->from, $this->type, $this->flash, $this->status);
    }

    public function supports(MessageInterface $message): bool
    {
        return $message instanceof SmsMessage;
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     */
    protected function doSend(MessageInterface $message): SentMessage
    {
        if (!$message instanceof SmsMessage) {
            throw new LogicException(sprintf('The "%s" transport only supports instances of "%s" (instance of "%s" given).', __CLASS__, SmsMessage::class, get_debug_type($message)));
        }

        $endpoint = sprintf('https://%s/schnittstelle.php', $this->getEndpoint());
        $response = $this->client->request(
            'POST',
            $endpoint,
            [
                'body' => [
                    'flash' => $this->flash,
                    'key' => $this->authToken,
                    'from' => $this->from,
                    'to' => $message->getPhone(),
                    'text' => $message->getSubject(),
                    'type' => $this->type,
                    'status' => $this->status,
                ],
            ]
        );

        if (200 !== $response->getStatusCode()) {
            $error = $response->toArray(false);

            throw new TransportException(sprintf('Unable to send the SMS: "%s".', $error['message']), $response);
        }

        $sentMessage = new SentMessage($message, (string)$this);
        $sentMessage->setMessageId($response->getContent());

        return $sentMessage;
    }
}
