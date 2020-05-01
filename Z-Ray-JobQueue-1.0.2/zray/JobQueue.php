<?php
namespace ZendServerJobQueue;

class JobQueue
{
    
    private $workerStatus = false;
    private $shutdownCalled = false;
    
    private $jobValueToFilter = ['id', 'status', 'name','script', 'queue_name', 'queue_id', 'predecessor', 'output', 'error','vars'];
    
    private static function getConstantText($value, $prefix)
    {
        $class = new \ReflectionClass('ZendJobQueue');
        $constants = array_filter($class->getConstants(), function($key) use ($prefix) {
            return strpos($key, $prefix) === 0;
        }, ARRAY_FILTER_USE_KEY);
        
        $constants = array_flip($constants);
    
        return $constants[$value];
    }
    
    public function afterJobStart($context, &$storage) {
        $queue = new \ZendJobQueue();
        
        $jobId = $context['returnValue'];
        $jobInfo = $queue->getJobInfo($jobId);
        
        $jobInfoStorage = array(
            'Job ID' => $jobId,
            'Job URL' => $context['functionArgs'][0],
        );
        
        if (isset($context['functionArgs'][1])) {
            $jobInfoStorage['Vars'] = (array) $context['functionArgs'][1];
        }
        if (isset($context['functionArgs'][2])) {
            $jobInfoStorage['Options'] = (array) $context['functionArgs'][2];
        }
        
        $jobInfoStorage['Queue'] = array('Id' => $jobInfo['queue_id'], 'Name' => $jobInfo['queue_name']);
        $jobInfoStorage['Priority'] = $this->getConstantText($jobInfo['priority'], 'PRIORITY');
        $jobInfoStorage['Predecessor'] = ($jobInfo['predecessor'] == 0) ? 'none' : $jobInfo['predecessor'];
        
        $storage['jobList'][] = array(
            'id' => $jobId,
            'url' => $context['functionArgs'][0],
            'info' => $jobInfoStorage
        );
    }

    public function workerStatus($context, &$storage)
    {
        $status = $context['functionArgs'][0];
        $status = ($status == \ZendJobQueue::OK) ? 'OK' : 'FAILED';
        $this->workerStatus = array('Status' => $status);
    }
    
    public function workerShutdown($context, &$storage)
    {
        if ($this->shutdownCalled) return;
        $this->shutdownCalled = true;
        
        $queue = new \ZendJobQueue();
        
        $jobId = $queue->getCurrentJobId();
        $jobInfo = $queue->getJobInfo($jobId);
        
        $storage['jobWorkerDetail'][] = $this->processJobDetail($jobId, $jobInfo);
    }
    
    private function processJobInfo($jobId, $jobInfo) {
        $info = [];
        $info['ID'] = array($jobId);
        $info['Name'] = array($jobInfo['name']);
        $info['URL'] = array( $jobInfo['script']);
        $info['Vars'] = array( $jobInfo['vars']);
        if ($jobInfo['predecessor']) $info['Predecessor'] = array( $jobInfo['predecessor']);
        if ($jobInfo['output']) $info['Output'] = array( $jobInfo['output']);
        if ($jobInfo['error']) $info['Error'] = array( $jobInfo['error']);
        
        $jobInfo['Queue'] = array('Id' => $jobInfo['queue_id'], 'Name' => $jobInfo['queue_name']);
        $jobInfo['priority'] = $this->getConstantText($jobInfo['priority'], 'PRIORITY');
        
        foreach ($this->jobValueToFilter as $key) {
            unset ($jobInfo[$key]);
        }
        
        $info['Detail'] = $jobInfo;
        
        return $info;
    }
    
    private function processJobDetail($jobId, $jobInfo) {
        if ($this->workerStatus) {
            $status = $this->workerStatus;
        }
        else {
            $status = array('Status' => 'missing');
        }
        
        $detail = [
            'id' => $jobId,
            'url' => $jobInfo['script'],
            'status' => $status
        ];
        
        return $detail;
    }
}
