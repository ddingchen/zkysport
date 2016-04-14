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
        'name',
        'tel',
        'paid' => array(
            'title' => 'paid',
            'relationship' => 'payment',
            'select' => "(:table).paid",
            'output' => function ($value) {
                return $value ? 'paid' : '';
            },
        ),
        'paid_at' => array(
            'title' => 'paid_at',
            'relationship' => 'payment',
            'select' => "(:table).paid_at",
        ),
        'refund' => array(
            'title' => 'refund',
            'relationship' => 'payment',
            'select' => "(:table).refund",
            'output' => function ($value) {
                return $value ? 'refund' : '';
            },
        ),
        'refund_at' => array(
            'title' => 'refund_at',
            'relationship' => 'payment',
            'select' => "(:table).refund_at",
        ),
    ),
    /**
     * The filter set
     */
    'filters' => array(
        'activity' => array(
            'type' => 'relationship',
            'title' => 'Activity',
            'name_field' => 'title',
        ),
        'payment' => array(
            'type' => 'relationship',
            'title' => 'Paid',
            'name_field' => 'paid',
        ),
    ),
    /**
     * The editable fields
     */
    'edit_fields' => array(
        'name' => array(
            'type' => 'text',
            'title' => 'Name',
            'limit' => 20,
        ),
        'tel' => array(
            'type' => 'text',
            'title' => 'Tel',
        ),
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
        // 'paid' => array(
        //     'title' => 'paid',
        //     'type' => 'bool',
        // ),
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
