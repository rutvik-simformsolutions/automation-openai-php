<?php

namespace OpenSource\AutomationOpenai;

class HomeController
{
    /**
     * This function is for adding two values
     */
    public function add($a, $b)
    {
        return $a + $b;
    }

    /**
     * This function is for substraction of two values
     */
    public function sub($a, $b)
    {
        return $a - $b;
    }

    /**
     * This function is for multiplication of two values
     */
    public function multiply($a, $b)
    {
        return $a * $b;
    }

    /**
     * This function is for devision of two values
     */
    public function devided($a, $b)
    {
        return $a / $b;
    }
}