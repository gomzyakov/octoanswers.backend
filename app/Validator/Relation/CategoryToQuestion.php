<?php

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class CategoryToQuestion_Relation_Validator
{
    //
    // Model validator
    //

    public static function validate_exists(CategoriesToQuestions_Relation_Model $rel)
    {
        self::validateID($rel->id);
        self::validate_new($rel);

        return true;
    }

    public static function validate_new(CategoriesToQuestions_Relation_Model $rel)
    {
        self::validateCategoryID($rel->categoryID);
        self::validateQuestionID($rel->questionID);
        self::validateCreatedAt($rel->createdAt);

        return true;
    }

    //
    // Property validators
    //

    public static function validateID($id)
    {
        try {
            v::intType()->min(1, true)->assert($id);
        } catch (NestedValidationException $exception) {
            throw new Exception('CategoryToQuestion relation "id" property ' . $exception->getMessages()[0], 0);
        }
    }

    public static function validateCategoryID($id)
    {
        try {
            v::intType()->min(1, true)->assert($id);
        } catch (NestedValidationException $exception) {
            throw new Exception('CategoryToQuestion relation "categoryID" property ' . $exception->getMessages()[0], 0);
        }
    }

    public static function validateQuestionID($id)
    {
        try {
            v::intType()->min(1, true)->assert($id);
        } catch (NestedValidationException $exception) {
            throw new Exception('CategoryToQuestion relation "questionID" property ' . $exception->getMessages()[0], 0);
        }
    }

    public static function validateCreatedAt($createdAt)
    {
        try {
            v::optional(v::stringType())->assert($createdAt);
        } catch (NestedValidationException $exception) {
            throw new Exception('CategoryToQuestion relation "createdAt" property ' . $exception->getMessages()[0], 0);
        }
    }
}
