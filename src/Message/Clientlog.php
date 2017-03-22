<?php
namespace Xymon\Message;

class Clientlog extends Message
{
    private $section = [];

    protected $data = [
        'hostname' => null,
        'section' => null
    ];

    public function section($section = null)
    {
        if ($section !== null) {
            if (func_num_args() === 1) {
                if (is_string($section)) {
                    $this->section[] = $section;
                } elseif (is_array($section)) {
                    $this->section = $section;
                }
            } else {
                foreach (func_get_args() as $field) {
                    $this->section[] = $field;
                }
            }
        }

        $this->section = array_unique($this->section);
        $this->refreshData();
        return $this->section;
    }

    private function refreshData()
    {
        $this->data['section'] = null;

        if (!empty($this->section)) {
            $this->data['section'] = 'section=' . implode(',', $this->section);
        }
    }
}
