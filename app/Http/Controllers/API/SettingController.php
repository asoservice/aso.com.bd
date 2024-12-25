<?php

namespace App\Http\Controllers\API;

use App\Enums\FrontSettingsEnum;
use App\Exceptions\ExceptionHandler;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Arr;

class SettingController extends Controller
{
    public function frontSettings()
    {
        try {

            $settingValues = Helpers::getSettings();
            $settings['values'] = Arr::only($settingValues, array_column(FrontSettingsEnum::cases(), 'value'));

            return $settings;

        } catch (Exception $e) {

            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }
}
