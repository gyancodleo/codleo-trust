<?php
if (! function_exists('isActive')) {
    function isActive($routeNames)
    {
        foreach ((array) $routeNames as $name) {
            if (request()->routeIs($name)) {
                return 'active'; // your CSS class
            }
        }
        return '';
    }
}

if (! function_exists('isDropdownOpen')) {
    function isDropdownOpen($routeNames)
    {
        foreach ((array) $routeNames as $name) {
            if (request()->routeIs($name)) {
                return true;
            }
        }
        return false;
    }
}