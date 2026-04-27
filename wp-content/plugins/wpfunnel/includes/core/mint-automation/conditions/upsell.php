<?php

/**
 * Upsell class for checking mail mint automation condition 
 *
 * @author [WPFunnels Team]
 * @package WPFunnelsPro\Automation\Condition
 * 
 * @since 2.3.4
 */

namespace WPFunnelsPro\Automation\Condition;

/**
 * Upsell class for checking mail mint automation condition
 * 
 * @author [WPfunnels Team]
 * @package WPFunnelsPro\Automation\Condition
 * 
 * @since 2.3.4
 */
class Upsell
{

    /**
     * Value.
     * 
     * @var integer
     * @since 2.3.4
     */
    public $value;

    /**
     * Targeted value.
     * 
     * @var integer
     * @since 2.3.4
     */
    public $targeted_value;

     /**
     * Constructor for the Upnsell class.
     *
     * @param mixed $value The value to compare.
     * @param mixed $targeted_value The targeted value to compare against.
     */
    public function __construct($value, $targeted_value)
    {
        $this->value = $value;
        $this->targeted_value = $targeted_value;
    }

    /**
     * Checks if the condition is true or false and returns the value
     * 
     * @return bool
     * @since 2.3.4
     */
    public function is(): bool
    {
        return ($this->value === $this->targeted_value);
    }

    /**
     * Checks if the condition is true or false and returns the value
     * 
     * @return bool
     * @since 2.3.4
     */
    public function isNot(): bool
    {
        return ($this->value !== $this->targeted_value);
    }
}
