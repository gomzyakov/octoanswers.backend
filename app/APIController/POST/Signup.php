<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class Signup_POST_APIController extends Abstract_APIController
{
    public function handle(Request $request, Response $response, $args): Response
    {
        try {
            $this->lang = $args['lang'];
            $this->l = Localizer::getInstance($this->lang);

            $username = $request->getParam('username');
            $userEmail = $request->getParam('email');
            $userPassword = $request->getParam('password');

            User_Validator::validateUsername($username);
            User_Validator::validateEmail($userEmail);
            User_Validator::validatePassword($userPassword);

            $user = (new User_Query())->userWithEmail($userEmail);
            if ($user) {
                throw new Exception('User with specific email is already registered', 1);
            }

            $user = (new User_Query())->userWithUsername($username);
            if ($user) {
                throw new Exception('User with username "'.$username.'" is already registered', 0);
            }

            // Generating password hash
            $passHash = new PassHash();
            $userPasswordHash = $passHash->hash($userPassword);
            $apiKey = $passHash->generateApiKey();

            // @TODO check API-key by doubles
            //$user = $api->get('users_api_key', ['api_key' => $api_key]);

            $name = $username;

            $user = new User_Model();
            $user->setUsername($username);
            $user->setName($name);
            $user->setEmail($userEmail);
            $user->setPasswordHash($userPasswordHash);
            $user->setAPIKey($apiKey);

            $user = (new User_Mapper())->create($user);

            if (!isset($cookieStorage) || !is_a($cookieStorage, 'CookieStorage')) {
                $cookieStorage = new CookieStorage();
            }
            $cookieStorage->saveUser($user);

            $this->output = [
                'id' => $user->getID(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'password_hash' => $user->getPasswordHash(),
                'api_key' => $user->getAPIKey(),
                'created_at' => date('Y-m-d H:i:s'),
                'url' => $user->getURL($this->lang),
                'destination_url' => Page_URL_Helper::getMainURL($this->lang),
            ];

            if (!defined('LOCAL_TESTS')) {
                $this->_copyDefaultAvatar($user->getID());
            }
        } catch (Throwable $e) {
            $this->output = [
                'error_code' => $e->getCode(),
                'error_message' => $e->getMessage(),
            ];
        }

        $json = json_encode($this->output, JSON_UNESCAPED_UNICODE);

        return $response->withHeader('Content-Type', 'application/json')->write($json);
    }

    public function _copyDefaultAvatar(int $userID)
    {
        $uploadFolder = ROOT_PATH.'/uploads/avatar';
        $avatarSizes = [100, 200, 400];

        foreach ($avatarSizes as $size) {
            $fromFile = $uploadFolder.'/0_'.$size.'.jpg';
            $toFile = $uploadFolder.'/'.$userID.'_'.$size.'.jpg';

            if(!@copy($fromFile, $toFile))
            {
                $errors= error_get_last();
                $this->output['avatar_'.$size] = 'Avatar file "'.$userID.'_'.$size.'.png" not copied: '.$errors['type'].' '.$errors['message'];
            } else {
                $this->output['avatar_'.$size] = 'Avatar file "'.$userID.'_'.$size.'.png" copied';
            }
        }
    }
}
