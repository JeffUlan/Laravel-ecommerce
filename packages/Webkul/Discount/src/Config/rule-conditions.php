<?php

return [
    'conditions' => [
        'numeric' => [
            0 => 'Equals',
            1 => 'Equals or greater',
            2 => 'Equals or lesser',
            3 => 'Greater than',
            4 => 'Lesser than'
        ],

        'text' => [
            0 => 'is',
            1 => 'is not',
            2 => 'contains',
            3 => 'does not contains'
        ],

        'boolean' => [
            0 => 'True/Yes',
            1 => 'False/No',
        ]
    ],

    'catalog' => [
        'actions' => [
            0 => 'admin::app.promotion.catalog.apply-percent',
            1 => 'admin::app.promotion.catalog.apply-fixed',
            2 => 'admin::app.promotion.catalog.adjust-to-percent',
            3 => 'admin::app.promotion.catalog.adjust-to-value'
        ],

        'attributes' => [
            0 => [
                    'name' => 'Sub-total',
                    'type' =>  'numeric'
                ],
            1 => [
                    'name' => 'Total Items Quantity',
                    'type' => 'numeric'
                ],
            2 => [
                    'name' => 'Total Weight',
                    'type' => 'numeric'
                ],
            3 => [
                    'name' => 'Payment Method',
                    'type' => 'string'
                ],
            4 => [
                    'name' => 'Shipping Postcode',
                    'type' => 'string'
                ],
            5 => [
                    'name' => 'Shipping State',
                    'type' => 'string'
                ],
            6 => [
                    'name' => 'Shipping Country',
                    'type' => 'string'
                ]
        ]
    ],

    'cart' => [
        'actions' => [
            'percent_of_product' => 'Percentage of product',
            'fixed_amount' => 'Apply as fixed amount',
            'buy_a_get_b' => 'Get B units free',
            'fixed_amount_cart' => 'Fixed amount for whole cart'
        ],

        'conditions' => [
            'numeric' => [
                '=' => 'Equals',
                '>=' => 'Greater or equals',
                '<=' => 'Lesser or equals',
                '>' => 'Greater than',
                '<' => 'Lesser than',
                '{}' => 'Contains',
                '!{}' => 'Does not contains',
                '()' => 'Is one of',
                '!()' => 'Not is one of'
            ],

            'text' => [
                '=' => 'Equals',
                '>=' => 'Greater or equals',
                '<=' => 'Lesser or equals',
                '>' => 'Greater than',
                '<' => 'Lesser than',
                '{}' => 'Contains',
                '!{}' => 'Does not contains',
                '()' => 'Is one of',
                '!()' => 'Not is one of'
            ],

            'boolean' => [
                0 => 'True/Yes',
                1 => 'False/No',
            ]
        ],

        'attributes' => [
            0 => [
                'code' => 'sub_total',
                'name' => 'Sub-total',
                'type' =>  'numeric'
            ],
            1 => [
                'code' => 'total_items',
                'name' => 'Total Items',
                'type' => 'numeric'
            ],
            2 => [
                'code' => 'total_weight',
                'name' => 'Total Weight',
                'type' => 'numeric'
            ],
            3 => [
                'code' => 'payement_method',
                'name' => 'Payment Method',
                'type' => 'string'
            ],
            4 => [
                'code' => 'shipping_postcode',
                'name' => 'Shipping Postcode',
                'type' => 'string'
            ],
            5 => [
                'code' => 'shipping_state',
                'name' => 'Shipping State',
                'type' => 'string'
            ],
            6 => [
                'code' => 'shipping_country',
                'name' => 'Shipping Country',
                'type' => 'string'
            ]
        ]
    ],
];