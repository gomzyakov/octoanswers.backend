<?php

class ShortURL_User_PageController extends Abstract_PageController
{
    public function handle($request, $response, $args)
    {
        parent::handleRequest($request, $response, $args);

        $userID = $args['id'];

        $user = (new User_Query())->userWithID($userID);

        return $response->withRedirect($user->getURL($this->lang), 301);
    }
}
