<?php

return [
    'title' => 'Admin dashboard',

    'kpi' => [
        'subscribers_active' => 'Subscribers – active',
        'subscribers_all'    => 'Subscribers – all',

        'newsletters_draft'  => 'Newsletters – drafts',
        'newsletters_sent'   => 'Newsletters – sent',

        'open_rate'          => 'Open rate',
        'click_rate'         => 'Click rate',
    ],

    'sections' => [
        'campaigns'          => 'Campaigns',
        'operators'          => 'Operators',
        'recent_newsletters' => 'Recent newsletters',
    ],

    'table' => [
        'title'         => 'Title',
        'status'        => 'Status',
        'author'        => 'Author',
        'open'          => 'Open',
        'click'         => 'Click',
        'date'          => 'Date',
        'email'         => 'Email',
        'last_activity' => 'Last activity',

        // 👇 DODANE (kampanie)
        'topic'     => 'Subject',
        'active'    => 'Active',
        'last_sent' => 'Last sent',
        'campaign'  => 'Campaign',
    ],

    'status' => [
        'sent'     => 'Sent',
        'draft'    => 'Draft',
        'active'   => 'Active',
        'inactive' => 'Inactive',
    ],

    'common' => [
        'yes' => 'Yes',
        'no'  => 'No',
    ],

    'quick' => [
        'newsletters_title' => 'Newsletters',
        'newsletters_desc'  => 'Manage campaigns',
        'subscribers_title' => 'Subscribers',
        'subscribers_desc'  => 'List and statuses',
        'operators_title'   => 'Operators',
        'operators_desc'    => 'Team',
        'settings_title'    => 'Settings',
        'settings_desc'     => 'Configuration',
    ],
];
