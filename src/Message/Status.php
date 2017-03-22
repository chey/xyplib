<?php
namespace Xymon\Message;

class Status extends Message
{
    use Traits\Hostname;
    use Traits\Testname;

    /**
     * The allowed colors for a 'status' message.
     */
    const ALLOWED_COLORS = ['green', 'yellow', 'red', 'clear'];

    protected $data = [
        'lifetime' => null,
        'group' => null,
        'hostname' => null,
        'testname' => null,
        'color' => 'green',
        'summary' => null,
        'body' => null
    ];

    public function setLifetime($lifetime)
    {
        if ($lifetime && !(strlen($lifetime) > 0 && ctype_digit(substr($lifetime, 0, 1)))) {
            throw new \InvalidArgumentException('Lifetime must be a valid timeframe');
        }

        $this->data['lifetime'] = $lifetime;
    }

    public function setSummary($summary)
    {
        $this->data['summary'] = trim(str_replace(["\n", "\r"], ' ', $summary));
    }

    public function setGroup($group)
    {
        if ($group && preg_match('/\s/', $group)) {
            throw new \InvalidArgumentException(sprintf('Invalid group %s', $group));
        }

        $this->data['group'] = $group;
    }

    public function setColor($color)
    {
        $color = strtolower($color); // normalize the color
        if (!in_array($color, self::ALLOWED_COLORS)) {
            throw new \InvalidArgumentException(sprintf('Invalid color: %s', $color));
        }

        $this->data['color'] = $color;
    }
}
