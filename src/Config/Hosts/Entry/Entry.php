<?php
namespace Xymon\Config\Hosts\Entry;

use Xymon\Config\Hosts;

/**
 * Base class for a entry in the Xymon hosts.cfg file.
 *
 * @author chey
 */
abstract class Entry {
    /**
     * The raw entry data. 
     *
     * @var string
     */
    private $raw;

    /**
     * Individual entry data.
     *
     * @var array
     */
    private $arguments = array();

    /**
     * Comment data from the entry. 
     *
     * @var string
     */
    private $comment;

    /**
     * Whether or not this is an optional entry.
     *
     * @var boolean
     */
    private $optional = false;

    /**
     * To be implemented in subclass.
     *
     * @return array
     */
    abstract public function getOptArgs();

    /**
     * Class constructor.
     *
     * @var string
     * @var array
     */
    public function __construct($input = null) {
        $optargs = $this->getOptArgs();
        $items = array();

        // This allows us to create our objects with all the args in the constructor.
        if (func_num_args() > 1) {
            $input = func_get_args();
        }

        // If we got our input as an array we don't need to parse it.
        if (is_array($input) && !empty($input)) {
            $items = $input;
        } elseif (is_string($input) && trim($input) != '') {
            $this->setRaw($input);
            if (count($optargs) > 0) {
                $limit = count($optargs);
                if (strncmp(ltrim($input), Hosts::XMH_OPT_OPTIONAL, 8) == 0) {
                    $limit++;
                }
                if (false !== ($pos = strpos($input, Hosts::COMMENT))) {
                    $limit++;
                }
                $items = preg_split('/[\s'.Hosts::COMMENT.']+/', $input, $limit);
            } else {
                $items[] = $input;
            }
        } else {
            return;
        }

        if ($items[0] === Hosts::XMH_OPT_OPTIONAL) {
            array_shift($items);
            $this->setOptional(true);
        }

        // Call the 'set' methods that store the arguments
        for ($i = 0; $i < count($optargs); $i++) {
            if (isset($items[$i])) {
                $this->{'set'.ucfirst(strtolower($optargs[$i]))}($items[$i]);
            }
        }

        // The reset of the entries are comments
        $comment = null;
        for ($i = count($optargs); $i < count($items); $i++) {
            $comment .= $items[$i];
        }
        $this->setComment($comment);
    }

    /**
     * Returns instance of calling class.
     */
    public static function create($input) {
        return new static($input);
    }

    /**
     * Gets the raw data that the object was initialized with.
     */
    public function getRaw() {
        $raw = $this->raw;
        if (empty($raw)) {
            $raw = $this->getEntry();
        }
        return $raw;
    }

    /**
     * @see raw
     */
    public function setRaw($raw) {
        $this->raw = $raw;
    }

    /**
     * @see comment
     */
    public function getComment() {
        return $this->comment;
    }
    
    /**
     * @see comment
     */
    public function setComment($comment) {
        $this->comment = $comment;
    }

    /**
     * @see optional
     */
    public function isOptional() {
        return $this->optional;
    }

    /**
     * @see optional
     */
    public function setOptional($v) {
        $this->optional = $v;
    }

    /**
     * @see arguments
     */
    public function getArgument($i) {
        $val = null;
        if (isset($this->arguments[$i])) {
            $val = $this->arguments[$i];
        }
        return $val;
    }

    /**
     * @see arguments
     */
    public function setArgument($i, $val) {
        $this->arguments[$i] = $val;
    }

    /**
     * @see arguments
     */
    public function getArguments() {
        ksort($this->arguments);
        return $this->arguments;
    }

    /**
     * @see setArgument()
     */
    public function setArguments($args) {
        foreach ($args as $k => $v) {
            $this->setArgument($k, $v);
        }
    }

    /**
     * Gets the nicely formatted version of the entry.
     */
    public function getEntry() {
        $entry = $this->getArguments();
        $comment = ltrim($this->getComment());
        if (trim($comment) != '') {
            if ($comment{0} !== Hosts::COMMENT) {
                $entry[] = Hosts::COMMENT . ' ' . $comment;
            } else {
                $entry[] = $comment;
            }
        }
        if ($this->isOptional()) {
            array_unshift($entry, Hosts::XMH_OPT_OPTIONAL);
        }
        return implode(Hosts::WHITESPACE, $entry);
    }

    /**
     * Calls the appropriate method in the subclass to either
     * 'set' or 'get' that argument data.
     *
     * @see getOptArgs()
     */
    public function __call($name, $args) {
        $arg = strtolower(substr($name, 3));

        if (false === ($i = array_search($arg, $this->getOptArgs()))) {
            throw new \InvalidArgumentException("Invalid argument: $arg");
        }

        switch (substr($name, 0, 3)) {
            case 'get':
                return $this->getArgument($i);
            break;
            case 'set':
                $this->setArgument($i, $args[0]);
            break;
            default:
                throw new \DomainException("Unknown method: $name");
        }
    }

    /**
     * @see getEntry()
     */
    public function __toString() {
        return $this->getEntry();
    }
}
