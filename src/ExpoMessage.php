<?php

namespace ExpoSDK\Expo;

use Exception;

/**
 * @see https://docs.expo.dev/push-notifications/sending-notifications/#message-request-format
 */
class ExpoMessage
{
    /** @var null */
    private $data = null;

    /** @var null */
    private $title = null;

    /** @var null */
    private $body = null;

    /** @var null */
    private $ttl = null;

    /** @var string */
    private $priority = 'default';

    /** @var null */
    private $subtitle = null;

    /** @var null */
    private $sound = null;

    /** @var null */
    private $badge = null;

    /** @var null */
    private $channelId = null;

    /** @var null */
    private $categoryId = null;

    /** @var bool */
    private $mutableContent = false;


    /**
     * Sets the data for the message
     *
     * @param array $data
     * @return self
     * @throws Exception
     */
    public function setData(array $data)
    {
        $data = json_encode($data);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Data could not be json encoded.');
        }

        $this->data = $data;

        return $this;
    }

    /**
     * Sets the title to display in the notification
     *
     * @param string $title
     * @return self
     */
    public function setTitle(string $title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Sets the message to display in the notification
     *
     * @param string $body
     * @return self
     */
    public function setBody(string $body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Sets the number of seconds for which the message may be kept around for redelivery
     *
     * @param int $ttl
     * @return self
     */
    public function setTtl(int $ttl)
    {
        $this->ttl = $ttl;

        return $this;
    }

    /**
     * Sets the delivery priority of the message, either 'default', 'normal' or 'high
     *
     * @param string $priority
     * @return self
     * @throws Exception
     */
    public function setPriority(string $priority)
    {
        $priority = strtolower($priority);

        if (! in_array($priority, ['default', 'normal', 'high'])) {
            throw new Exception('Priority must be default, normal or high.');
        }

        $this->priority = $priority;

        return $this;
    }

    /**
     * Sets the subtitle to display in the notification below the title
     *
     * @param string $subtitle
     * @return self
     */
    public function setSubtitle(string $subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Play a sound when the recipient receives the notification
     *
     * @return self
     * @return self
     */
    public function playSound()
    {
        $this->sound = 'default';

        return $this;
    }

    /**
     * Set the number to display in the badge on the app icon
     *
     * @param int $badge
     * @return self
     */
    public function setBadge(int $badge)
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * Set the ID of the Notification Channel through which to display this notification
     *
     * @param string $channelId
     * @return self
     */
    public function setChannelId(string $channelId)
    {
        $this->channelId = $channelId;

        return $this;
    }

    /**
     * Set the ID of the notification category that this notification is associated with
     *
     * @param string $categoryId
     * @return self
     */
    public function setCategoryId(string $categoryId)
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * Set whether the notification can be intercepted by the client app
     *
     * @param bool $mutable
     * @return self
     */
    public function setMutableContent(bool $mutable)
    {
        $this->mutableContent = $mutable;

        return $this;
    }

    /**
     * Convert the message to an array
     *
     * @return array
     */
    public function toArray()
    {
        $attributes = [];

        foreach ($this as $key => $value) {
            if (! is_null($value)) {
                $attributes[$key] = $value;
            }
        }

        return $attributes;
    }
}
