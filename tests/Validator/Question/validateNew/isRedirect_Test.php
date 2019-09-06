<?php

class Validator_Question_validate_new_isRedirect_Test extends PHPUnit\Framework\TestCase
{
    public function test_isRedirect_NotSet()
    {
        $question = new Question_Model();
        $question->title = 'How iPhone 8 are charged?';

        $this->assertEquals(false, $question->isRedirect);
        $this->assertEquals(true, Question_Validator::validate_new($question));
    }
}
