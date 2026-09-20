<?php

namespace App\Modules\Settings\Company\Services;

use App\Modules\Settings\Company\Models\CompanySetting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CompanySettingService
{
    public function get(): ?CompanySetting
    {
        return CompanySetting::query()->first();
    }

    public function update(array $data): CompanySetting
    {
        $companySetting = CompanySetting::query()->first();

        if (!$companySetting) {
            return CompanySetting::create($data);
        }

        $companySetting->update($data);

        return $companySetting->fresh();
    }

    public function uploadLogo(
        UploadedFile $file
    ): CompanySetting {
        $companySetting = CompanySetting::query()->first();

        if (!$companySetting) {
            $companySetting = CompanySetting::create([
                'name' => 'MiniCRM',
            ]);
        }

        if ($companySetting->logo_path) {
            Storage::disk('public')->delete(
                $companySetting->logo_path
            );
        }

        $path = $file->store(
            'company',
            'public'
        );

        $companySetting->update([
            'logo_path' => $path,
        ]);

        return $companySetting->fresh();
    }

    public function deleteLogo(): ?CompanySetting
    {
        $companySetting = CompanySetting::query()->first();

        if (!$companySetting) {
            return null;
        }

        if ($companySetting->logo_path) {
            Storage::disk('public')->delete(
                $companySetting->logo_path
            );
        }

        $companySetting->update([
            'logo_path' => null,
        ]);

        return $companySetting->fresh();
    }
}