<?php

namespace Trax\XapiServer\Tests\Service;

use Trax\XapiServer\Tests\Profile\XapiProfile;

class XapiBenchmarkServiceTest extends XapiServiceTest
{
    use XapiProfile;


    /**
     * Batch size.
     */
    protected $batchSize = 1000;

    /**
     * Batch number.
     */
    protected $batchNumber = 1000;

    
    /**
     * Benchmark function. 
     * Adapt the .env to target different LRSs.
     */
    public function test_benchmark_client()
    {
        for ($i=0; $i < $this->batchSize; $i++) {
            $statement = [
                'actor' => ['mbox' => 'mailto:learner1@xapi.fr'],
                'verb' => ['id' => 'http://adlnet.gov/expapi/verbs/completed'],
                'object' => ['id' => 'http://xapi.fr/activities/act01'],
            ];
            $response = \XapiClient::statements()->post($statement)->send($this->context);

            $this->assertTrue($response->code == 200);
            fwrite(STDERR, print_r('.', TRUE));
        }
    }
    
    /**
     * Benchmark function. 
     * Adapt the .env to target different LRSs.
     */
    public function test_benchmark_builder()
    {
        for ($i=0; $i < $this->batchSize; $i++) {
            $passed = \XapiDesign::profile()->statement('actor-mbox')->test()->assert();
            $this->assertTrue($passed);
            fwrite(STDERR, print_r('.', TRUE));
        }
    }

    /**
     * Seeder function. 
     */
    public function test_seed()
    {
        $verbs = ['attempted', 'completed', 'passed', 'failed'];
        $faker = \Faker\Factory::create();
        $builder = $this->builder();
        $store = \XapiServer::xapiStatements();

        for ($i = 0; $i < $this->batchNumber; $i++) {
            $batch = [];
            for ($j = 0; $j < $this->batchSize; $j++) {
                $batch[] = $builder
                    ->agent()
                    ->mbox($faker->email)
                    ->verb()
                    ->id($verbs[rand(0, 3)])
                    ->activity()
                    ->id($faker->randomNumber())
                    ->get();
            }
            $store->bulkStore($batch);
            fwrite(STDERR, print_r('.', TRUE));
        }
        $this->assertTrue(true);
    }


    /**
     * Getter function. 
     */
    public function test_get()
    {
        // When we get statements
        $response = \XapiClient::statements()->get([
            'since' => '2019-07-16T00:00:00Z',
            //'until' => '2019-07-16T00:00:00Z',
            'from' => '4000000',
            'limit' => '1000',
        ])->send($this->context);

        // We get a 200 code
        $this->assertTrue($response->code == 200);

        // Get the content
        $content = json_decode($response->content);
        $statements = $content->statements;
        print_r(count($statements) . ' statements found');
    }
    
    
}
