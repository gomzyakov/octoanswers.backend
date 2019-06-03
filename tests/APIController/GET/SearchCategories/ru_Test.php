<?php

class APIController_GET_SearchCategories_ru_Test extends Abstract_Frontend_TestCase
{
    protected $setUpDB = ['ru' => ['hashtags']];

    public function test_One_char_query()
    {
        $queryString = 'query='.urlencode('а');
        $request = $this->__getTestRequest('GET', '/api/v1/ru/search/categories.json', $queryString, true);

        $this->app->getContainer()['request'] = $request;

        $response = $this->app->run(true);
        $responseBody = (string) $response->getBody();
        
        $expectedResponseZeroItem = [
            "year" => "1967",
            'value' => 'русскаялитература',
            'name' => 'русскаялитература'
        ];

        $actualResponse = json_decode($responseBody, true);

        $this->assertEquals($expectedResponseZeroItem, $actualResponse[0]);
        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_Two_char_query()
    {
        $queryString = 'query='.urlencode('авто');
        $request = $this->__getTestRequest('GET', '/api/v1/ru/search/categories.json', $queryString, true);

        $this->app->getContainer()['request'] = $request;

        $response = $this->app->run(true);
        $responseBody = (string) $response->getBody();
        
        $expectedResponse = [
            [
                'year' => '1967',
                'value' => 'автомобили',
                'name' => 'автомобили'
            ],
            [
                'year' => '1967',
                'value' => 'автоспорт',
                'name' => 'автоспорт'
            ]
        ];

        $this->assertEquals($expectedResponse, json_decode($responseBody, true));
        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_Search_empty_query()
    {
        $queryString = 'query='.urlencode('');
        $request = $this->__getTestRequest('GET', '/api/v1/ru/search/categories.json', $queryString, true);

        $this->app->getContainer()['request'] = $request;

        $response = $this->app->run(true);
        $responseBody = (string) $response->getBody();
        
        $expectedResponse = [];

        $this->assertEquals($expectedResponse, json_decode($responseBody, true));
        $this->assertSame(200, $response->getStatusCode());
    }
}