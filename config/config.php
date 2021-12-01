<?php

return [
    'programationMode' => [
        'weekly'=> [
                'label' => "Toutes les semaines",
                'options' => [
                    'day' => 'waka.programer::day',
                    'hour' => true
                ]
        ],
        'monthly'=> [
                'label' => "Toutes les mois",
                'options' => [
                    'day' => 'waka.programer::num_day',
                    'hour' => true
                ]
        ],
        // 'everyMinute' => [
        //         'label' => "Toutes les minutes",
        //     ],
        // 'everyFiveMinutes' => [
        //         'label' => "Toutes les 5 minutes",
        //     ],
        // 'hourly' =>  [
        //         'label' => "Toutes les heures",
        //     ],
        'dailyAt'=> [
                'label' => "Tous les jours à...",
                'options' => [
                    'hour' => true
                ]
        ],
        

    ],
    'day' => ['weekdays' => 'Jours ouvrés',1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5=> 'Vendredis', 6=> 'Samedi', 0 => 'Dimanche' ],
    'num_day' => [
        'firstDay' => 'Premier jours du mois',
        'lastDay' => 'Dernier jours du mois',
        ],
    'selectionMode' => [
        'scope'=>[
            'label' => 'Scope',
            'options' => true,
            ],
        'mailingList'=>[
            'label' => 'MailingList',
            'options' => true,
            ],
        'settings'=>[
            'label' => 'Settings',
            'options' => true,
            ],
    ],
    'campagne_state' => [
        'Brouillon' => ['init' => true, 'progamation' => true, 'one_shot' => true],
        'Pret pour envoi' => ['init' => true, 'one_shot' => true],
        'Programation inactive' => ['init' => true, 'progamation' => true],
        'Programation active' => ['progamation' => true],
        'Envoyé' => ['invisible'=>  true, 'close' => true],
        'Archiver' => ['invisible'=>  true, 'close' => true]
    ]


];
