<?php
namespace Xymon\Channel;

use Xymon\Channel\Output\OutputInterface;

class Worker implements \SplSubject
{
    private $running = false;
    private $timeout = false;
    private $message = null;
    private $observers = [];
    private $output = null;
    private $protocols = [];

    public function addProtocol($protocol)
    {
        if (!ProtocolType::isValidValue($protocol)) {
            throw new Exception\InvalidProtocolTypeException('Invalid protocol type: ' . $protocol);
        }

        if (!$this->hasProtocol($protocol)) {
            $this->protocols[] = $protocol;
        }
    }

    public function delProtocol($proto)
    {
        $key = array_search($proto, $this->protocols, true);

        if (false !== $key) {
            unset($this->protocols[$key]);
        }
    }

    public function hasProtocol($proto)
    {
        return in_array($proto, $this->protocols, true);
    }

    public function getProtocols()
    {
        return $this->protocols;
    }

    public function getTimeout()
    {
        return $this->timeout;
    }

    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    public function setOutput(OutputInterface $output = null)
    {
        $this->output = $output;
    }

    /**
     * @return OutputInterface
     */
    public function output(OutputInterface $output = null)
    {
        if ($output !== null) {
            $output->set($this->message);
            return $output;
        }

        $this->output->set($this->message);

        return $this->output;
    }

    public function current()
    {
        return $this->message;
    }

    public function next()
    {
        return ChannelReader::get($this->getTimeout());
    }

    public function stop()
    {
        $this->running = false;
    }

    public function attach(\SplObserver $observer)
    {
        $this->observers[] = $observer;
    }

    public function detach(\SplObserver $observer)
    {
        $key = array_search($observer, $this->observers, true);

        if (false !== $key) {
            unset($this->observers[$key]);
        }
    }

    public function notify()
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    public function run($func = null)
    {
        if ($func !== null && !is_callable($func)) {
            throw new \InvalidArgumentException('Invalid callback provided');
        }

        $this->running = true;

        while ($this->isRunning()) {
            $this->message = $this->next();
            if ($this->message !== null && $this->isWanted()) {
                $this->notify();
                if ($func !== null) {
                    if (false === $func($this->message)) {
                        break;
                    }
                }
            }
        }
    }

    public function isWanted()
    {
        if (empty($this->protocols)) {
            return true;
        }

        foreach ($this->getProtocols() as $proto) {
            if (strncmp($this->message, $proto, strlen($proto)) === 0) {
                return true;
            }
        }

        return false;
    }

    public function isRunning()
    {
        return $this->running;
    }

    public function __invoke($func)
    {
        $this->run($func);
    }
}
