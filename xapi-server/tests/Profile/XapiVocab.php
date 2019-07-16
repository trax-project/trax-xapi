<?php

namespace Trax\XapiServer\Tests\Profile;

use Trax\XapiDesign\Vocab\SimpleVocabIndex;

class XapiVocab extends SimpleVocabIndex
{
    /**
     * Verbs.
     */
    protected $verbs = [
        'completed' => 'http://adlnet.gov/expapi/verbs/completed',
        'passed' => 'http://adlnet.gov/expapi/verbs/passed',
        'failed' => 'http://adlnet.gov/expapi/verbs/failed',
        'attempted' => 'http://adlnet.gov/expapi/verbs/attempted',
    ];

}
