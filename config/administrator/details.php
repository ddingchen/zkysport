<?php
/**
 * Actors model config
 */
return array(
    'title' => 'Detail',
    'single' => 'detail',
    'model' => 'App\DetailInformation',
    /**
     * The display columns
     */
    'columns' => array(
        'activity' => array(
            'title' => 'activity',
            'relationship' => 'information.activity',
            'select' => "(:table).title",
        ),
        'realname',
        'tel',
        'sub_district_id' => array(
            'title' => 'sub_district_id',
            'relationship' => 'subDistrict',
            'select' => "(:table).name"),
        'housing_estate_id' => array(
            'title' => 'housing_estate_id',
            'relationship' => 'housingEstate',
            'select' => "(:table).name"),
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
        'realname' => array(
            'title' => 'realname',
            'type' => 'text',
        ),
        'tel' => array(
            'title' => 'tel',
            'type' => 'text',
        ),
        'subDistrict' => array(
            'title' => 'SubDistrict',
            'type' => 'relationship',
            'name_field' => 'name',
        ),
        'housingEstate' => array(
            'title' => 'housing_estate_id',
            'type' => 'relationship',
            'name_field' => 'name',
        ),
    ),

    'rules' => array(

    ),
);
