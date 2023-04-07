<?php

namespace OpenSource\AutomationOpenai;

class HomeController
{
    public function add($a, $b)
    {
        return $a + $b;
    }

    public function sub($a, $b)
    {
        return $a - $b;
    }

    public function multiply($a, $b)
    {
        return $a * $b;
    }

    public function devided($a, $b)
    {
        return $a / $b;
    }
}