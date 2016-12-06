<?php
namespace ZendServerJobQueue;

class JobQueue
{
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
        
        $storage['jobsStarted']['Job ' . $jobId] = $jobInfoStorage;
        
        $storage['jobList'][] = array(
            'id' => $jobId,
            'url' => $context['functionArgs'][0],
            'info' => $jobInfoStorage
        );
    }
    
    public function afterinit($context, &$storage) {
        $storage['init'][] = array('abc' => 'xyz');
    }

}
