<?php

function helloWorld(string $name = "World")
{
    return 'Hello, ' . ucfirst(strtolower($name)) . '!';
}
