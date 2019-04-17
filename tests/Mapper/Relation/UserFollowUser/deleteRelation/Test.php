<?php

class UserFollowUser_Relation_Mapper__deleteRelation__Test extends Abstract_DB_TestCase
{
    protected $setUpDB = ['ru' => ['er_users_follow_users']];

    public function test__FullParams__OK()
    {
        // Relation must be in DB
        $relation = new UserFollowUser_Relation_Model();
        $relation->setID(3);
        $relation->userID = 4;
        $relation->followedUserID = 5;
        $relation->createdAt = '2014-12-16 11:28:56';

        $deleted = (new UserFollowUser_Relation_Mapper('ru'))->deleteRelation($relation);

        $this->assertEquals(true, $deleted);
    }

    public function test__RelationNotExists()
    {
        // Not exists relation
        $relation = new UserFollowUser_Relation_Model();
        $relation->setID(6);
        $relation->userID = 22;
        $relation->followedUserID = 61;
        $relation->createdAt = '2014-12-16 11:28:56';

        $this->expectExceptionMessage('UserFollowUser relation not deleted');
        $deleted = (new UserFollowUser_Relation_Mapper('ru'))->deleteRelation($relation);
    }
}
