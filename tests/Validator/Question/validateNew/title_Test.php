<?php

class Validator_Question_validate_new_title_Test extends PHPUnit\Framework\TestCase
{
    public function test_titleNotSet()
    {
        $question = new Question_Model();
        $question->isRedirect = true;

        $this->expectExceptionMessage('Question title param null must be a string');
        $this->assertEquals(true, Question_Validator::validate_new($question));
    }

    public function test_titleIsEmpty()
    {
        $question = new Question_Model();
        $question->title = '';
        $question->isRedirect = true;

        $this->expectExceptionMessage('Question title param "" must have a length between 3 and 255');
        $this->assertEquals(true, Question_Validator::validate_new($question));
    }

    public function testCommentTooShort()
    {
        $question = new Question_Model();
        $question->title = 'x';
        $question->isRedirect = true;

        $this->expectExceptionMessage('Question title param "x" must have a length between 3 and 255');
        $this->assertEquals(true, Question_Validator::validate_new($question));
    }

    public function testCommentTooLong()
    {
        $question = new Question_Model();
        $question->title = 'Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42.';
        $question->isRedirect = true;

        $this->expectExceptionMessage('Question title param "Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42. Title42." must have a length between 3 and 255');
        $this->assertEquals(true, Question_Validator::validate_new($question));
    }
}
