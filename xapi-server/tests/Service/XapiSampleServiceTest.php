<?php

namespace Trax\XapiServer\Tests\Service;

use Trax\XapiServer\Tests\Profile\XapiProfile;

class XapiSampleServiceTest extends XapiServiceTest
{
    use XapiProfile;


    public function test_client_get()
    {
        // When we get statements
        $response = \XapiClient::statements()->get([
            //'statementId' => '0df68d8b-add4-3012-a299-705d0ee633b6'
        ])->send($this->context);

        // We get a 200 code
        $this->assertTrue($response->code == 200);
    }
    
    public function test_client_post()
    {
        // Given a statement
        $statement = [
            'actor' => ['mbox' => 'mailto:learner1@xapi.fr'],
            'verb' => ['id' => 'http://adlnet.gov/expapi/verbs/completed'],
            'object' => ['id' => 'http://xapi.fr/activities/act01'],
        ];

        // When we post a statement
        $response = \XapiClient::statements()->post($statement)->send($this->context);

        // We get a 200 code
        $this->assertTrue($response->code == 200);
    }

    /**
     * Try to build a statement using a simple vocab index, and record it.
     */
    public function test_builder_record()
    {
        // Given a Statement, when I record it
        $response = $this->builder()
            ->agent('sebastien@fraysse.eu')
            ->verb('http://adlnet.gov/expapi/verbs/completed')
            ->activity('http://xapi.fr/activities/act01')
            ->record();

        // I get an array
        $this->assertTrue(is_array($response));
    }
    
    public function test_builder_post()
    {
        // Given a Statement, when I post it
        $response = $this->builder()
            ->agent('sebastien@fraysse.eu')
            ->verb('completed')
            ->activity('act1')
            ->post($this->context);

        // I get a 200 code
        $this->assertTrue($response->code == 200);
    }

    public function test_pattern()
    {
        $passed = \XapiDesign::profile()->pattern('statement-put-simple')->test()->assert($this->context);
        $this->assertTrue($passed);
    }
    
    public function test_statements()
    {
        $passed = \XapiDesign::profile()->statement('errors-statement')->test()->assert($this->context);
        $this->assertTrue($passed);
    }

    
}
