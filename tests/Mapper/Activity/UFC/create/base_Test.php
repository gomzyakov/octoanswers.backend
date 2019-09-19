<?php

class Mapper_Activity_UFC__create__Test extends Abstract_DB_TestCase
{
    protected $setUpDB = ['ru' => ['activities']];

    public function test_CreateWithFullParams_Ok()
    {
        $user = new \Model\User();
        $user->id = 46;
        $user->name = 'Steve Bo';

        $category = \Model\Category::init_with_title('tag10');

        $activity = new \Model\Activity();
        $activity->type = \Model\Activity::USER_FOLLOW_CATEGORY;
        $activity->subject = $user;
        $activity->data = $category;

        $activity = (new UFollowC_Activity_Mapper('ru'))->create($activity);

        $this->assertEquals(13, $activity->id);
        $this->assertEquals(\Model\Activity::USER_FOLLOW_CATEGORY, $activity->type);
    }
}
