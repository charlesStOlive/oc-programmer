<?php
return [
    'name' => 'Gestion des campagnes',
    'com' => 'Un commentaire sur la gestion des campagnes',
    'places' => [
        'brouillon' => 'Brouillon',
        'init' => 'Initialisation',
        'pret' => 'Campagne prête',
        'encours' => 'En cours d&#039;envoi',
        'envoye' => 'Campagne envoyé',
        'prog_active' => 'Program. En cours',
        'prog_supspended' => 'Program suspendu',
        'prog_encours' => 'En cours d&#039;envoi ( programme )',
        'archive' => 'Archivé',
        'abdn' => 'abdn',
    ],
    'trans' => [
        'brouillon_to_init' => 'Initialisation',
        'init_to_pret' => 'Campagne prête',
        'pret_to_encours' => 'Envoyer campagne',
        'encours_to_envoye' => 'Campagne envoyé',
        'pret_to_prog_active' => 'Mettre en programtion',
        'pret_to_abdn' => 'Abandonner',
        'envoye_to_prog_active' => 'Mettre en programation',
        'envoye_to_archive' => 'Archiver',
        'prog_active_to_prog_encours' => 'Envoyer la campange (auto)',
        'prog_active_to_prog_supspended' => 'Suspendre la programation',
        'prog_supspended_to_prog_active' => 'Ré_activer la progrmation',
        'prog_supspended_to_archive' => 'Archiver',
    ],
    'error_message' => [
        'ready' => '',
        'programed' => '',
        'default' => '',
    ],
];