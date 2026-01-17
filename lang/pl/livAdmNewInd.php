<?php

return [
    'title' => 'Newsletters',

    'create' => 'Send new newsletter',
    'createCampaign' => 'Add new campaign',

    'table' => [
        'id' => '#',
        'subject' => 'Subject',
        'preview' => 'Preview text',
        'status' => 'Status',
        'opens' => 'Opens',
        'uniqueOpens' => 'Uniques',
        'clicks' => 'Clicks',
        'uniqueClicks' => 'Uniques',
        'ctr' => 'CTR',
        'createdAt' => 'Created',
        'actions' => 'Actions',
    ],

    'status' => [
        'draft' => 'draft',
        'sending' => 'sending',
        'sent' => 'sent',
    ],

    'actions' => [
        'edit' => 'Edit',
        'test' => 'Test',
        'send' => 'Send',
        'sending' => 'SENDING',
        'sent' => 'SENT',
    ],

    'empty' => 'No newsletters.',
];
