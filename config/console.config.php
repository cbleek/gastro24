<?php
/**
 * YAWIK
 *
 * @filesource
 * @copyright (c) 2013 - 2016 Cross Solution (http://cross-solution.de)
 * @license   MIT
 */

return [
    'console' => [
        'router' => [
            'routes' => [
                'jobs-expire'    => [
                    'options' => [
                        'route'    => 'jobs expire [--days=] [--limit=] [--info]',
                        'defaults' => [
                            'controller' => 'Gastro24/Jobs/Console',
                            'action'     => 'expirejobs',
                            'days'       => 30,
                            'limit'      => '10,0',
                        ],
                    ],
                ],
                'jobs-clear'    => [
                    'options' => [
                        'route'    => 'jobs clear [--onlyDebug=]',
                        'defaults' => [
                            'controller' => 'Gastro24/Jobs/Console/DeleteJobs',
                            'action'     => 'deleteExpiredJobs',
                            'onlyDebug'  => '0',
                        ],
                    ],
                ],
                'jobs-google-index'    => [
                    'options' => [
                        'route'    => 'jobs google-index',
                        'defaults' => [
                            'controller' => 'Gastro24/Jobs/Console/GoogleIndex',
                            'action'     => 'googleIndexJobs',
                        ],
                    ],
                ]
            ]
        ]
    ]
];
