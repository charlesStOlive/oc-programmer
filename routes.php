<?php

Route::group(['middleware' => ['web']], function () {
    Route::get('/test/campagne/{campagneId}/{targerId}', function ($campagneId, $targerId) {
        $mc = Waka\Programer\Classes\CampagneCreator::find($campagneId);
        if($mc->getProductor()->is_mjml) {
            return '<div>' .$mc->setModelId($targerId)->renderHtmlforTest() . '</div>';
        } else {
            return '<div style="width:600px">' . $mc->setModelId($targerId)->renderHtmlforTest() . '</div>';
        }
    });
});
