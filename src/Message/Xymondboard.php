<?php
namespace Xymon\Message;

class Xymondboard extends Message
{
    const VALID_OPERATORS = ['>=', '>', '<=', '<', '=', '!='];

    private $criteria = [];
    private $fields = [];

    protected $data = [
        'criteria' => null,
        'fields' => null
    ];

    /**
     * @param string $name filter name
     * @param string $value filter value
     * @return void
     */
    public function filter()
    {
        if (func_num_args() === 2) {
            list($name, $value) = func_get_args();
            $this->criteria[$name] = $value;
        } elseif (func_num_args() === 1) {
            $args = func_get_arg(0);
            foreach ($args as $name => $value) {
                $this->filter($name, $value);
            }
        }

        $this->refreshData();
    }

    /**
     * @param mixed $fields if string, add field. if array, set fields to the array
     * @return array of fields
     */
    public function fields($fields = null)
    {
        if ($fields !== null) {
            if (func_num_args() === 1) {
                if (is_string($fields)) {
                    $this->fields[] = $fields;
                } elseif (is_array($fields)) {
                    $this->fields = $fields;
                }
            } else {
                foreach (func_get_args() as $field) {
                    $this->fields[] = $field;
                }
            }
        }

        $this->fields = array_unique($this->fields);
        $this->refreshData();
        return $this->fields;
    }

    /**
     * Update $data so our output mechanisms work properly.
     * @return void
     */
    private function refreshData()
    {
        $this->data['criteria'] = null;
        $this->data['fields'] = null;

        if (!empty($this->fields)) {
            $this->data['fields'] = 'fields=' . implode(',', $this->fields);
        }

        if (!empty($this->criteria)) {
            $items = [];
            foreach ((array) $this->criteria as $k => $v) {
                if (is_string($k)) {
                    if (preg_match('/\s/', $v)) {
                        $v = '"'.$v.'"';
                    }
                    $op = '=';
                    foreach (self::VALID_OPERATORS as $o) {
                        if (strncmp($o, $v, strlen($o)) === 0) {
                            $op = '';
                            break;
                        }
                    }
                    $items[] = sprintf('%s%s%s', $k, $op, $v);
                }
            }
            $this->data['criteria'] = implode(' ', $items);
        }
    }
}
