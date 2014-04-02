<?php

namespace WPIndexer\Validator;

class Rules
{
    
    /**
     * All rules we are going to use
     * @var array
     */
    private $rules;
    
    /**
     * Set single rule
     * @param string $name
     * @param string $rule
     * @return \WPIndexer\Validator\Rules
     */
    public function set($name, $rule)
    {
        $this->rules[$name] = $rule;
        return $this;
    }
    
    /**
     * Get all rules
     * @return array
     */
    public function get()
    {
        return $this->rules;
    }
}