<?php
return array(
    'reviewsec' => array(
        'site_setting' => array(
            'amazon' => array(
                'js_client' => array(
                    'max_review_pages' => 8,
                    'token_expire' => 40 // seconds
                                ),
                'spider' => array(
                    'product' => array(
                        'url' => 'http://localhost:6800/schedule.json',
                        'project' => 'reviewcrawl',
                        'spider' => 'amazon_product',
                        'time_interval' => 400, // ms, time interval to check spider response
                        'timeout' => 10000
                    ),
                    'review' => array(
                        'url' => 'http://localhost:6800/schedule.json',
                        'project' => 'reviewcrawl',
                        'spider' => 'amazon_review',
                        'time_interval' => 400, // ms, time interval to check spider response
                        'timeout' => 10000
                    )
                )
            ),
            'walmart' => array(
                'api' => array(
                    'key' => 'm2szz65zmuuvcevvvvkuys5j',
                    'url' => 'http://walmartlabs.api.mashery.com/v1/search',
                    'format' => 'json'
                )
            )
        )
    )
);