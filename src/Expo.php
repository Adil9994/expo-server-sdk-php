<?php

namespace ExpoSDK\Expo;

use Exception;
use ExpoSDK\Expo\Exceptions\InvalidTokensException;

class Expo
{
    /**
     * @var DriverManager
     */
    private $manager;

    /**
     * @var ExpoClient
     */
    private $client;

    /**
     * The message to send
     *
     * @var ExpoMessage
     */
    private $message = null;

    /**
     * The tokens to send the message to
     *
     * @var array
     */
    private $recipients = null;

    /**
     * Expo constructor
     *
     * @param DriverManager $manager
     */
    public function __construct(DriverManager $manager = null)
    {
        $this->manager = $manager;

        $this->client = new ExpoClient();
    }

    /**
     * Builds an expo instance
     *
     * @param string $driver
     * @param array $config
     * @return self
     */
    public static function driver(string $driver = 'file', array $config = [])
    {
        $manager = new DriverManager($driver, $config);

        return new self($manager);
    }

    /**
     * Subscribes tokens to a channel
     *
     * @param string $channel
     * @param string|array $tokens
     */
    public function subscribe(string $channel, $tokens = null)
    {
        if ($this->manager) {
            return $this->manager->subscribe($channel, $tokens);
        }

        throw new Exception('You must provide a driver to interact with subscriptions.');
    }

    /**
     * Retrieves a channels subscriptions
     *
     * @param string $channel
     * @return array|null
     */
    public function getSubscriptions(string $channel)
    {
        if ($this->manager) {
            return $this->manager->getSubscriptions($channel);
        }

        throw new Exception('You must provide a driver to interact with subscriptions.');
    }

    /**
     * Unsubscribes tokens from a channel
     *
     * @param string $channel
     * @param string|array $tokens
    */
    public function unsubscribe(string $channel, $tokens = null)
    {
        if ($this->manager) {
            return $this->manager->unsubscribe($channel, $tokens);
        }

        throw new Exception('You must provide a driver to interact with subscriptions.');
    }

    /**
     * Check if a value is a valid Expo push token
     *
     * @param mixed $value
     * @return bool
     */
    public function isExpoPushToken($value)
    {
        if (! is_string($value) || strlen($value) < 15) {
            return false;
        }

        return Utils::isExpoPushToken($value);
    }

    /**
     * Sets the ExpoMessage to send
     *
     * @param ExpoMessage $message
     * @return self
     */
    public function send(ExpoMessage $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Sets the recipients to send the message to
     *
     * @param string|array $recipients
     * @return self
     */
    public function to($recipients = null)
    {
        $tokens = null;

        if (is_array($recipients) && count($recipients) > 0) {
            $tokens = $recipients;
        } elseif (is_string($recipients)) {
            $tokens = [$recipients];
        } else {
            throw new InvalidTokensException(sprintf(
                'Tokens must be a string or non empty array, %s given.',
                gettype($tokens)
            ));
        }

        $tokens = array_filter($tokens, function ($token) {
            return Utils::isExpoPushToken($token);
        });

        if (count($tokens) === 0) {
            throw new \Exception('No valid expo tokens supplied.');
        }

        $this->recipients = $tokens;

        return $this;
    }

    /**
     * Get the message recipients
     *
     * @return array|null
     */
    public function getRecipients()
    {
        return $this->recipients;
    }

    /**
     * Send the message to the expo server
     *
     * @return object|false
     */
    public function push()
    {
        if (is_null($this->message) || is_null($this->recipients)) {
            throw new Exception('You must have a message and recipients to push');
        }

        $messages = array_map(function ($recipient) {
            return $this->message->toArray() + ['to' => $recipient];
        }, $this->recipients);

        try {
            $response = $this->client->post($messages);
        } catch (Exception $e) {
            return false;
        }

        // handle in Client instead of here
        return json_decode($response->getBody());
    }
}
