<?php
/**
 * Actors model config
 */
return array(
    'title' => 'Activity',
    'single' => 'activity',
    'model' => 'App\Activity',
    /**
     * The display columns
     */
    'columns' => array(
        'title',
        'banner' => array(
            'output' => function ($value) {
                return '<img width="65" height="30" src="' . asset('uploads/activities/originals/' . $value) . '"/>';
            },
        ),
        'desc',
        'ticket_price',
        'informations' => array(
            'title' => 'informations',
            'relationship' => 'informations',
            'select' => "count((:table).id)",

        ),
        'start_from',
        'end_to',
        'expired',
    ),
    /**
     * The filter set
     */
    'filters' => array(
        'title' => array(
            'title' => 'Title',
        ),
    ),
    /**
     * The editable fields
     */
    'edit_fields' => array(
        'title' => array(
            'title' => 'Title',
            'type' => 'text',
        ),
        'banner' => array(
            'title' => 'Banner',
            'type' => 'image',
            'location' => public_path() . '/uploads/activities/originals/',
            'naming' => 'random',
            'size_limit' => 1,
            'sizes' => array(
                array(650, 300, 'crop', public_path() . '/uploads/activities/wap/', 100),
                array(360, 200, 'crop', public_path() . '/uploads/activities/wxpub/', 100),
            ),
        ),
        'desc' => array(
            'title' => 'Description',
            // 'type' => 'textarea',
            'type' => 'wysiwyg',
        ),
        'ticket_price' => array(
            'title' => 'Ticket Price',
            'type' => 'number',
            'symbol' => '$',
            'decimals' => 2,
        ),
        'require_information' => array(
            'title' => 'require_information',
            'type' => 'bool',
        ),
        'start_from' => array(
            'title' => 'start_from',
            'type' => 'datetime',
        ),
        'end_to' => array(
            'title' => 'end_to',
            'type' => 'datetime',
        ),
    ),

    'rules' => array(

    ),

    'form_width' => 400,
);
