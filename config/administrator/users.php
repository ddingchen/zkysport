<?php
/**
 * Actors model config
 */
return array(
    'title' => 'AdminUser',
    'single' => 'admin',
    'model' => 'App\User',
    /**
     * The display columns
     */
    'columns' => array(
        'id',
        'name',
        'email',
        'password',
    ),
    /**
     * The filter set
     */
    'filters' => array(
        'id',
        'name' => array(
            'title' => 'Name',
        ),
    ),
    /**
     * The editable fields
     */
    'edit_fields' => array(
        'name' => array(
            'title' => 'Name',
            'type' => 'text',
        ),
        'email' => array(
            'title' => 'Email',
            'type' => 'text',
        ),
        'password' => array(
            'title' => 'Password',
            'type' => 'password',
        ),
    ),

    'query_filter' => function ($query) {
        $query->whereNotNull('email');
    },

    'rules' => array(
        'name' => 'required',
        'email' => 'required|email',
        'password' => 'required',
    ),
);
