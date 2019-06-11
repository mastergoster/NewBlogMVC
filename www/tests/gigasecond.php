<?php
//
// This is only a SKELETON file for the "Hamming" exercise. It's been provided as a
// convenience to get you started writing code faster.
//
function from(\DateTime $from)
{
    return (clone $from)->modify((10 ** 9) . ' sec');
}
