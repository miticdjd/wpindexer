<?php

namespace WPIndexer\Configuration;

class Options
{
    
    /**
     * Update options in wordpress database
     * @param array $options
     */
    public static function update($options)
    {
        $saved = get_option('wpindexer', json_encode(array()));
        $values = json_decode($saved, true);
        
        foreach ($options as $key => $value) {
            $values[$key] = $value;
        }
        
        $newValues = json_encode($values);
        if (update_option('wpindexer', $newValues)) {
            return true;
        }
        return false;
    }
    
    /**
     * Get options from database
     * @param string $key
     * @return array
     */
    public static function get($key = null)
    {
        $options = json_decode(get_option('wpindexer', json_encode(array())), true);
        
        if (is_null($key)) {
            return $options;
        } else if (isset($options[$key])) {
            return $options[$key];
        }
        return array();
    }
    
}