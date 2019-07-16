<?php

namespace Trax\XapiServer\Tests\Profile;

trait XapiProfile
{
    /**
     * Platform IRI: project specific. Will not change if the hosting domain changes.
     */
    protected function platformIri()
    {
        return config('trax-xapi-client.xapi.platform_iri');
    }

    /**
     * Get the platform.
     */
    protected function platformName()
    {
        return config('trax-xapi-client.xapi.platform_name');
    }

    /**
     * Get the activity prefix.
     */
    protected function activityPrefix()
    {
        return $this->platformIri() . '/xapi/activities/';
    }

    /**
     * Get the Statement builder.
     */
    protected function builder()
    {
        return \XapiDesign::builder()
            ->activityPrefix($this->activityPrefix())
            ->vocab(new XapiVocab());
    }

}