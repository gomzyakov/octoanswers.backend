<?php

class Show_Hashtag_PageController extends Abstract_PageController
{
    protected $hashtag_questions;

    // @TODO Deprecated
    public function handleByURI($request, $response, $args)
    {
        $this->lang = $args['lang'];
        $hashtag_uri = $args['uri'];

        try {
            $hashtag_title = Hashtag_URL_Helper::titleFromURI($hashtag_uri);
            $this->hashtag = (new Hashtag_Query($this->lang))->findWithTitle($hashtag_title);
            if ($this->hashtag === null) {
                throw new \Exception("Hashtag not exists", 1);
            }
        } catch (\Exception $e) {
            //return (new QuestionNotFound_Error_PageController($this->container))->handle($this->lang, $request, $response, $args);
            return (new InternalServerError_Error_PageController($this->container))->handle($this->lang, $request, $response, $args);
        }

        return $response->withRedirect($this->hashtag->getURL($this->lang), 301);
    }

    public function handle($request, $response, $args)
    {
        $this->lang = $args['lang'];
        $hashtagID = $args['id'];
        $hashtag_uri_slug = $args['uri_slug'];

        try {
            $this->hashtag = (new Hashtag_Query($this->lang))->hashtagWithID($hashtagID);
        } catch (\Exception $e) {
            //return (new QuestionNotFound_Error_PageController($this->container))->handle($this->lang, $request, $response, $args);
            return (new InternalServerError_Error_PageController($this->container))->handle($this->lang, $request, $response, $args);
        }

        $this->parsedown = new ExtendedParsedown($this->lang);

        $humanDateTimezone = new DateTimeZone('UTC');
        $dateHumanizer = new HumanDate($humanDateTimezone, $this->lang);

        $hashtag_questions = (new HashtagsToQuestions_Relations_Query($this->lang))->findNewestForHashtagWithID($this->hashtag->getID());
        $this->hashtag_questions = [];

        foreach ($hashtag_questions as $hashtag_question_er) {
            $this->hashtag_questions[] = (new Question_Query($this->lang))->questionWithID($hashtag_question_er->getQuestionID());

            //$question['date_humanized'] = $dateHumanizer->format($question->getCreatedAt());
        }

        // recount questions count if GET-param on 20% random
        try {
            // if ((mt_rand(0, 10) > 7) || isset($_GET['upd'])) {
            //     $questionsCount = api_v1_get_hashtags_ID_questions_count($args['hashtag_id']);
            //     $hashtag->setQuestionsCount($questionsCount);
            //     $hashtagMapper = new CategoryMapper($pdo);
            //     $hashtagMapper->saveHashtag($hashtag);
            // }
        } catch (Throwable $e) {
            // do nothing
        }

        if (is_array($this->hashtag_questions) && count($this->hashtag_questions) == 10) {
            $data['next_page_button'] = [
                'title' => _('More hashtags'),
                'url' => '#',
            ];
        }

        //$data['alternate_url_prefix'] = $hashtag['url'].'?';

        //$data['most_viewed_writers'] = $this->_getMostViewedWriters();

        if (is_array($this->hashtag_questions)) {
            $this->related_hashtags = $this->_getRelatedHashtags($this->hashtag_questions);
        }
        //} else {
        //  $this->related_hashtags = [];
        //}

        $this->_prepareFollowButton();

        $this->template = 'hashtag';
        $this->pageTitle = $this->_getPageTitle();
        //str_replace('%hashtag%', , _('Hashtag - Page title')).' • '._('Answeropedia');
        $this->pageDescription = $this->_getPageDescription();
        $this->nextPageURL = null;

        $this->openGraph = $this->_getOpenGraph();
        $this->share = $this->_getOpenGraph();

        $output = $this->renderPage();
        $response->getBody()->write($output);

        return $response;
    }

    protected function _getPageTitle()
    {
        return str_replace('%hashtag%', $this->hashtag->getTitle(), _('Questions and answers on the hashtag %hashtag% - Answeropedia'));
    }

    protected function _prepareFollowButton()
    {
        if ($this->authUser) {
            $authUserID = $this->authUser->getID();
            $hashtagID = $this->hashtag->getID();

            $relation = (new UsersFollowHashtags_Relations_Query($this->lang))->relationWithUserIDAndHashtagID($authUserID, $hashtagID);

            $this->followed = $relation ? true : false;
            $this->includeJS[] = 'hashtag/follow';
        }
    }

    protected function _getOpenGraph()
    {
        $og = [
            'url' => $this->hashtag->getURL($this->lang),
            'type' => "website",
            'title' => $this->_getPageTitle(),
            'description' => $this->_getPageDescription(),
            'image' => IMAGE_URL.'/og-image.png'
        ];
        return $og;
    }

    protected function _getPageDescription()
    {
        return str_replace('%hashtag%', $this->hashtag->getTitle(), _('Questions and answers on the hashtag %hashtag%'));
    }

    /**
     * Get most_viewed_writers.
     */
    public function _getMostViewedWriters()
    {
        $most_viewed_writers = [
            [
                'name' => 'Alexander Gomzyakov',
                'url' => 'https://answeropedia.org/user/1/aleksandr-gomzyakov',
                'signature' => 'IT Project Manager',
                'avatar_url' => 'http://placehold.it/48x48',
                'avatar_alt' => 'Answeropedia user',
            ]
        ];

        return $most_viewed_writers;
    }

    public function _getRelatedHashtags(array $questions): array
    {
        if (count($questions) == 0) {
            return [];
        }

        $related_titles = [];

        foreach ($questions as $question) {
            $hashtags_titles = $question->getHashtags();
            if (is_array($hashtags_titles) && count($hashtags_titles)) {
                foreach ($hashtags_titles as $title) {
                    //@TODO need a query
                    $related_titles[] = $title;
                }
            }
        }

        $related_titles = array_unique($related_titles);
        $related_titles = array_reverse($related_titles);

        $del_val = $this->hashtag->getTitle();
        if (($key = array_search($del_val, $related_titles)) !== false) {
            unset($related_titles[$key]);
        }

        $related_hashtags = [];
        if (count($related_titles)) {
            foreach ($related_titles as $title) {
                $hashtag = Hashtag_Model::initWithTitle($title);
                $related_hashtags[] = $hashtag;
            }
        } else {
            $related_hashtags = [];
        }

        return $related_hashtags;
    }
}