<?php

use PHPUnit\Framework\TestCase;

class Helper_ExtendedParsedown_text__categories__Test extends TestCase
{
    protected function setUp(): void
    {
        $this->pd = new \Helper\ExtendedParsedown('ru');
    }

    protected function tearDown(): void
    {
        $this->pd = null;
    }

    public function test_Dont_link_to_hashtags()
    {
        $stringMD = 'Any #birds may #fly.';
        $stringHTML = '<p>Any #birds may #fly.</p>';

        $this->assertEquals($stringHTML, $this->pd->text($stringMD));
    }
}
