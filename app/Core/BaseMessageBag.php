<?php

namespace App\Core;

use Core;
use Illuminate\Contracts\Support\MessageBag;
use Lang;

class BaseMessageBag implements MessageBag
{
    protected $errors = [];
    protected $format = ':message';
    protected $_default = 'message';
    protected $_suffix = '.php';

    /*public function __construct($key = '', $message = array())
    {
        $this->add($key, $message);
    }*/

    /**
     * Get the keys present in the message bag.
     *
     * @return array
     */
    public function keys()
    {
        return array_keys($this->errors);
    }

    /**
     * Add a message to the bag.
     *
     * @param  string $key
     * @param  string $message
     * @return $this
     */
    public function add($key, $message)
    {
        if (!$key || !is_array($message)) {
            return false;
        }

        $this->errors[$key] = $message;

        return $this;
    }

    /**
     * Merge a new array of messages into the bag.
     *
     * @param  \Illuminate\Contracts\Support\MessageProvider|array $messages
     * @return $this
     */
    public function merge($messages)
    {
        $this->errors = array_merge_recursive($this->errors, $messages);

        return $this;
    }

    /**
     * Determine if messages exist for a given key.
     *
     * @param  string $key
     * @return bool
     */
    public function has($key = null)
    {
        return $this->first($key) !== '';
    }

    /**
     * Get the first message from the bag for a given key.
     *
     * @param  string $key
     * @param  string $format
     * @return string
     */
    public function first($key = null, $format = null)
    {
        $messages = is_null($key) ? $this->all($format) : $this->get($key, $format);

        return count($messages) > 0 ? $messages[0] : '';
    }

    /**
     * Get all of the messages from the bag for a given key.
     *
     * @param  string $key
     * @param  string $format
     * @return array
     */
    public function get($key = false, $format = null)
    {
        if ($key !== false) {
            $file = $this->translate($key);
            $error = empty($this->errors[$key]) ? Lang::get($file) : Lang::get($file, $this->errors[$key]);

            return $error;
        } else {
            $data = [];
            foreach ($this->errors as $k => $v) {
                $file = $this->translate($k);
                $error = empty($this->errors[$k]) ? Lang::get($file) : Lang::get($file, $this->errors[$k]);
                $data[$k] = $error;
            }
            return $data;
        }
    }

    /**
     * Get all of the messages for every key in the bag.
     *
     * @param  string $format
     * @return array
     */
    public function all($format = null)
    {
        return $this->errors;
    }

    /**
     * Get the default message format.
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set the default message format.
     *
     * @param  string $format
     * @return $this
     */
    public function setFormat($format = ':message')
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Determine if the message bag has any messages.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->errors);
    }

    /**
     * Get the number of messages in the container.
     *
     * @return int
     */
    public function count()
    {
        return count($this->errors);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->errors;
    }

    private function translate($message)
    {
        $package = $file = '';
        $_message = $message;

        if (strpos($_message, ':') != false) {
            list($package, $_message) = explode(':', $_message, 2);
        }

        if (strpos($_message, '.') != false) {
            list($file, $key) = explode('.', $_message, 2);
        }

        $path = strtolower($package);
        if (file_exists(Core::V('lang_path') . $path . '/' . $file . $this->_suffix)) {
            return empty($path) ? $file . '.' . $key : $path . '/' . $file . '.' . $key;
        } elseif (file_exists(Core::V('lang_path') . $path . '/' . $this->_default . $this->_suffix)) {
            return empty($path) ? $this->_default . '.' . $_message : $path . '/' . $this->_default . '.' . $_message;
        } else
            return $message;
    }

}