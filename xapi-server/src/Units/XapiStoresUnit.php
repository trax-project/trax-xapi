<?php

namespace Trax\XapiServer\Units;

use Trax\DataStore\Utils\Transaction;
use Trax\XapiServer\Utils\StatementUtils;

class XapiStoresUnit
{
    use StatementUtils, Transaction;

    /**
     * Services.
     */
    protected $services;

    
    /**
     * Construct.
     */
    public function __construct($services)
    {
        $this->services = $services;
    }

    /**
     * Record a single Statement, with or without attachments, given a Statement ID.
     */
    public function recordStatement($statementId, $statement, $attachments = [])
    {
        // Start Transaction
        $res = $this->transaction(function () use ($statementId, $statement, $attachments) {

            // Store statement
            $statementStore = $this->services->xapiStatements();
            $id = $statementStore->storeOne($statementId, $statement);
            
            // Store complementary data
            $activities = $this->statementsActivities($statement);
            $agents = $this->statementsAgents($statement);
            $this->recordStatementsComplements($attachments, $agents, $activities);

            return $id;
        }, $this->transactionSupported());
        return $res;
    }

    /**
     * Record one or more Statements, with or without attachments.
     */
    public function recordStatements($statements, $attachments = [])
    {
        // Start Transaction
        $res = $this->transaction(function () use ($statements, $attachments) {
        
            // Store statements
            $statementStore = $this->services->xapiStatements();
            $res = array();
            if (is_array($statements)) {
                foreach ($statements as $statement) {
                    $res[] = $statementStore->store($statement);
                }
            } else {
                $res[] = $statementStore->store($statements);
            }

            // Store complementary data
            $activities = $this->statementsActivities($statements);
            $agents = $this->statementsAgents($statements);
            $this->recordStatementsComplements($attachments, $agents, $activities);

            return $res;
        }, $this->transactionSupported());
        return $res;
    }

    /**
     * Record one or more Statements, with or without attachments.
     */
    protected function recordStatementsComplements($attachments, $agents, $activities)
    {
        // Store attachments
        $attachmentStore = $this->services->attachments();
        foreach ($attachments as $attachment) {
            $attachmentStore->store($attachment);
        }
            
        // Store activities
        $activityStore = $this->services->xapiActivities();
        foreach ($activities as $activity) {
            $activityStore->store($activity);
        }
            
        // Store agents
        $agentStore = $this->services->xapiAgents();
        foreach ($agents as $agent) {
            $agentStore->store($agent);
        }
    }

    /**
     * Is transaction supported?
     */
    protected function transactionSupported()
    {
        return config('trax-xapi-server.stores.Statement.driver') != 'mongo';
    }

}
