<?php

namespace WPIndexer\Validator;

class Post
{
    /**
     * All errors
     * @var array
     */
    private $errors = array();
    
    /**
     * Rules we are going to use to validate data
     * @var array 
     */
    private $rules = array();
    
    /**
     * All input values we are going to validate
     * @var array
     */
    private $validate = array();
    
    /**
     * Keep all data from post
     * @var array
     */
    private $data = array();
    
    /**
     * Init validator
     * @param array $rules
     */
    public function __construct($rules)
    {
        /* Set rules and */
        $this->rules = $rules;
        foreach ($this->rules as $name => $value) {
            if (isset($_POST[$name])) {
                $this->validate[$name] = $_POST[$name];
            }
        }
    }
    
    /**
     * Let's validate data
     * @return boolean
     */
    public function isValid()
    {
        $this->proccess();
        
        /**
         * Do we have some errors?
         */
        if (count($this->errors) > 0) {
            return false;
        }
        return true;
    }
    
    /**
     * Process all input posts we need to validate and have rules
     */
    private function proccess()
    {
        foreach ($this->validate as $name => $value) {
            $rules = $this->rules[$name];
            
            /* Let's see is this value a required, e.g not empty */
            if (preg_match('~req~', $rules)) {
                if ($this->isEmpty($value)) {
                    $this->errors[] = $this->filterName($name) . ' can\'t be empty, please enter required data.';
                    continue; // We will display only one error per input
                }
            }
            
            /**
             * Let's see is this value needs to be integer
             */
            if (preg_match('~int~', $rules)) {
                if (!$this->isInt($value)) {
                    $this->errors[] = $this->filterName($name) . ' is not number, please enter number.';
                    continue; // We will display only one error per input
                }
            }
            
            /**
             * Let's see is this value needs to be text
             */
            if (preg_match('~text~', $rules)) {
                if (!$this->isText($value)) {
                    $this->errors[] = $this->filterName($name) . ' is not text, please enter only letters.';
                    continue; // We will display only one error per input
                }
            }
            
            /* This is good input */
            $this->data[$name] = $value;
        }
    }
    
    /**
     * Get all valid data
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
    
    /**
     * Return all errors
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
    
    /**
     * Check is value an empty
     * @param string $string
     * @return boolean
     */
    private function isEmpty($string)
    {
        return empty($string);
    }
    
    /**
     * Check is value contains only numbers
     * @param int $int
     * @return boolean
     */
    private function isInt($int)
    {
        return is_numeric($int);
    }

    /**
     * Check is value contains only letters
     * @param string $string
     * @return boolean
     */
    private function isText($string)
    {
        if (preg_match('~[A-Za-z:/,]~', $string)) {
            return true;
        }
        return false;
    }
    
    /**
     * Filter name for print
     * @param string $name
     * @return string
     */
    private function filterName($name)
    {
        $name = ucfirst($name);
        $name = str_replace('_', ' ', $name);
        
        return $name;
    }
}