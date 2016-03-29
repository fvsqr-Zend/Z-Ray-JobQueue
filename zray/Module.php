<?php
namespace ZendServerJobQueue;

class Module extends \ZRay\ZRayModule
{

    public function config()
    {
        return array(
            'extension' => array(
                'name' => 'JobQueue'
            ),
            'defaultPanels' => array(
                'jobList' => false,
            ),
            'panels' => array(
                'myJobDetail' => array(
                    'display' => true,
                    'logo' => 'logo.png',
                    'menuTitle' => 'Job Detail',
                    'panelTitle' => 'Job Detail',
                    'resources' => array(
                    )
                )
            )
        );
    }
}
