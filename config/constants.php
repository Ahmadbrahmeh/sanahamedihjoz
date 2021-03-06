<?php

return [

    'users' => [
        'attachments' => [
            'path' => 'attachments/users',
            'max' => 7
        ],
    ],
    'attachments' => [
        'icons' => [
            'pdf' => 'pdf.png',
            'doc' => 'word.png',
            'other' => 'other.png',
        ]
    ],
    'addresses' =>[
        'types' => [
            'city' => 'مدينة',
            'region' => 'منطقة',
            'street' => 'شارع'
        ],
        'chain' => [
            'city' => 'region',
            'region' => 'street',
        ],
        'tail' => 'street'
     ], 
    'organizations' => [
        'weekdays' =>[
            'saturday' => 'السبت',
            'sunday' => 'الاحد',
            'monday' => 'الاثنين',
            'tuesday' => 'الثلاثاء',
            'wednesday' => 'الاربعاء',
            'thursday' => 'الخميس',
            'friday' => 'الجمعة'
        ]
    ],
    'reservations' => [
        'notes' => [
            "max" => 5,
        ],
        'terms' => [
            "max" => 7
        ],
        'eventlist' => [
            "max" => 10
        ],
        'weekdays' => [
            'saturday' => 6,
            'sunday' => 0,
            'monday' => 1,
            'tuesday' => 2,
            'wednesday' => 3,
            'thursday' => 4,
            'friday' => 5
        ],
        'status' => [
            'arabic' => [
                'INITIAL_RESERVATION' => 'مبدئي',
                'PENDING_RESERVATION' => 'غير مستحق',
                'FINISHED_RESERVATION' => 'مستحق',
                'DELAYED_RESERVATION' => 'مؤجل',
                'CANCELED_RESERVATION' => 'ملغى',
            ],
            'mapping' => [
                'initial' => 'INITIAL_RESERVATION',
                'pending' => 'PENDING_RESERVATION',
                'finished' => 'FINISHED_RESERVATION',
                'delay' => 'DELAYED_RESERVATION',
                'cancel' => 'CANCELED_RESERVATION',
            ]
        ]
    ],
    'payments' => [
        'form' => [
            'max' => 4
        ]
    ],
    'employees' => [
        'salary' => [
            'type' => [
                '1' => 'يومي',
                '2' => 'شهري',
                '3' => 'حسب الساعة'
            ]
        ]
    ]


];



?>