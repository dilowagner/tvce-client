<?php
namespace Tvce;

class Path
{
    /**
     * @var array
     */
    private $values;

    /**
     * Path constructor.
     * @param array $values
     */
    public function __construct($values = [])
    {
        $this->values = $values;
    }

    /**
     * @return string
     * Build path URL
     */
    public function build()
    {
        $count = count($this->values);
        $pattern = '';
        for($i = 0; $i < $count; $i++) {
            $pattern .= '%s';
        }
        return vsprintf($pattern, $this->values);
    }
}