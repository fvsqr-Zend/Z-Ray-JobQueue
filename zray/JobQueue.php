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
        
        $storage['jobsStarted']['Job ' . $jobId] = array(
            'Job ID' => $jobId,
            'Job URL' => $context['functionArgs'][0],
            'Vars' => (array) $context['functionArgs'][1],
            'Options' => (array) $context['functionArgs'][2],
            'Queue' => array('Id' => $jobInfo['queue_id'], 'Name' => $jobInfo['queue_name']),
            'Priority' => $this->getConstantText($jobInfo['priority'], 'PRIORITY'),
            'Predecessor' => ($jobInfo['predecessor'] == 0) ? 'none' : $jobInfo['predecessor']
        );
        
        $storage['jobList'][] = array(
            'id' => $jobId,
            'url' => $context['functionArgs'][0]
        );
    }
}
