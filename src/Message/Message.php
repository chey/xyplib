<?php
namespace Xymon\Message;

abstract class Message implements MessageInterface, \IteratorAggregate
{
    /**
     * The command that pertains to the message.
     * @var string
     */
    protected $command = null;

    /**
     * Holds the meat of a message.
     * @var array
     */
    protected $data = [];

    /**
     * @return string the raw message
     */
    public function syntax($fallback = self::DEFAULT_SYNTAX)
    {
        return Syntax\SyntaxFactory::create($this, $fallback)->format();
    }

    /**
     * @return array
     */
    public function data()
    {
        return (array) $this->data;
    }

    /*
     * @return string
     */
    public function command()
    {
        $command = $this->command;

        if (!$command) {
            $command = strtolower(\Xymon\get_class_name($this));
        }

        return $command;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        // Setup our properties based on the class definition
        if (defined('static::DEFINITION')) {
            foreach (static::DEFINITION as $k => $v) {
                if (!is_numeric($k) && !property_exists($this, $k)) {
                    $this->data[$k] = null;
                }
            }
        }

        if (func_num_args() > 0) {
            $value = func_get_arg(0);
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    if (isset($this->$k)) {
                        $this->$k = $v;
                    }
                }
            }
        }
    }

    /**
     * Basic overloading.
     */
    public function __get($name)
    {
        $method = 'get' . ucfirst($name);
        if (method_exists($this, $method)) {
            return $this->$method();
        } elseif (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        throw new \InvalidArgumentException(sprintf('property \'%s\' does not exist', $name));
    }

    /**
     * Basic overloading.
     */
    public function __set($name, $value)
    {
        $method = 'set' . ucfirst($name);
        if (method_exists($this, $method)) {
            $this->$method($value);
        } elseif (array_key_exists($name, $this->data)) {
            $this->data[$name] = $value;
        } else {
            throw new \InvalidArgumentException(sprintf('property \'%s\' does not exist', $name));
        }
    }

    /**
     * Basic overloading.
     */
    public function __isset($name)
    {
        return array_key_exists($name, $this->data);
    }

    /**
     * Basic overloading.
     */
    public function __call($name, $args)
    {
        if (strncmp($name, 'get', 3) === 0) {
            $prop = strtolower(substr($name, 3));
            if (array_key_exists($prop, $this->data)) {
                return $this->data[$prop];
            }

            throw new \BadMethodCallException(sprintf('Unknown method: %s', $name));
        }

        if (strncmp($name, 'set', 3) === 0) {
            $prop = strtolower(substr($name, 3));
            if (array_key_exists($prop, $this->data)) {
                $this->data[$prop] = $args[0];
                return;
            }

            throw new \BadMethodCallException(sprintf('Unknown method: %s', $name));
        }
    }

    /**
     * Basic overloading.
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->command();
    }

    /**
     * @see \ArrayAccess
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }
}
