<?php
use PHPUnit\Framework\TestCase;

require "hamming.php";

class HammingComparatorTest extends TestCase
{
    public function testNoDifferenceBetweenIdenticalStrands()
    {
        $this->assertEquals(0, distance('A', 'A'));
    }

    public function testCompleteHammingDistanceOfForSingleNucleotideStrand()
    {
        $this->assertEquals(1, distance('B', 'C'));
    }

    public function testCompleteHammingDistanceForSmallStrand()
    {
        $this->assertEquals(2, distance('AB', 'CD'));
    }

    public function testSmallHammingDistance()
    {
        $this->assertEquals(1, distance('AT', 'BT'));
    }

    public function testSmallHammingDistanceInLongerStrand()
    {
        $this->assertEquals(1, distance('GGACG', 'GGTCG'));
    }
    public function testLargeHammingDistance()
    {
        $this->assertEquals(4, distance('GATACA', 'GCATAA'));
    }
    public function testHammingDistanceInVeryLongStrand()
    {
        $this->assertEquals(9, distance('GGACGGATTCTG', 'AGGACGGATTCT'));
    }
    public function testExceptionThrownWhenStrandsAreDifferentLength()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("DNA strands must be of equal length.");
        distance('GGACG', 'AGGACGTGG');
    }
}
