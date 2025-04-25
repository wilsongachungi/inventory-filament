<?php

use App\Models\Setting as ModelsSetting;

    if(!function_exists("getSettings")){

        function getSettings(array $columns, $tenantId)
    {
        $finalResult = [];
        $settings = ModelsSetting::whereIn('key', $columns)
            ->with(['settings' => function($query) use ($tenantId) {
                $query->where('tenant_id', $tenantId);
            }])
            ->get();
        foreach ($columns as $key => $column) {
            $setting = $settings->firstwhere('key', $column);
            if($setting)
            {
                $tenantSettings = $setting->settings->first();
                if($tenantSettings)
                {
                    $finalResult[$column] = $tenantSettings->value ? $tenantSettings->value:$setting->value;
                }
                else
                {
                    $finalResult[$column] = "";
                }
            }
        }
       return $finalResult;
    }
}