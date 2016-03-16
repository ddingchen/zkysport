<?php
/**
 * Actors model config
 */
return array(
    'title' => 'Information',
    'single' => 'information',
    'model' => 'App\Information',
    /**
     * The display columns
     */
    'columns' => array(
        'activity' => array(
            'title' => 'activity',
            'relationship' => 'activity',
            'select' => "(:table).title",
        ),
        'paid' => array(
            'title' => 'paid',
            'output' => function ($value) {
                return $value ? 'already paid' : 'unpaid';
            },
        ),
        'detail' => array(
            'title' => 'detail',
            'relationship' => 'detail',
            'select' => "concat((:table).realname, ' ',(:table).tel)",
        ),
    ),
    /**
     * The filter set
     */
    'filters' => array(
        // 'title' => array(
        //     'title' => 'Title',
        // ),
    ),
    /**
     * The editable fields
     */
    'edit_fields' => array(
        // 'title' => array(
        //     'title' => 'Title',
        //     'type' => 'text',
        // ),
        // 'banner' => array(
        //     'title' => 'Banner',
        //     'type' => 'image',
        //     'location' => public_path() . '/uploads/activities/originals/',
        //     'naming' => 'random',
        // ),
        // 'desc' => array(
        //     'title' => 'Description',
        //     'type' => 'textarea',
        // ),
        // 'ticket_price' => array(
        //     'title' => 'Ticket Price',
        //     'type' => 'number',
        //     'symbol' => '$',
        //     'decimals' => 2,
        // ),
        'paid' => array(
            'title' => 'paid',
            'type' => 'bool',
        ),
        // 'start_from' => array(
        //     'title' => 'start_from',
        //     'type' => 'datetime',
        // ),
        // 'end_to' => array(
        //     'title' => 'end_to',
        //     'type' => 'datetime',
        // ),
    ),

    'rules' => array(

    ),
);
