<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Enterprise;
use Illuminate\Support\Str;
use App\Models\User;

class EnterpriseSeeder extends Seeder
{
    public function run(): void
    {
        $enterprise = Enterprise::create([
            'id' => (string) Str::uuid(),
            'name' => 'Auto Service',
        ]);

        User::query()->update(['enterprise_id' => $enterprise->id]);
    }
}
