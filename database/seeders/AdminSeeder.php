<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin oluştur
        $admin = User::firstOrCreate([
            'email' => 'admin@betground.com'
        ], [
            'name' => 'Super Admin',
            'email' => 'admin@betground.com',
            'password' => Hash::make('admin123'),
            'role' => 'super_admin',
            'phone' => '05551234567',
            'birth_date' => '1990-01-01',
            'gender' => 'male',
            'country' => 'TR',
            'currency' => 'TRY',
            'status' => 'active',
            'kyc_status' => 'verified',
            'referral_code' => 'ADMIN001',
            'email_verified_at' => now(),
        ]);

        // Admin için cüzdan oluştur (eğer yoksa)
        if (!$admin->wallet) {
            Wallet::create([
                'user_id' => $admin->id,
                'currency' => 'TRY',
                'balance' => 100000, // Admin için test bakiyesi
                'bonus_balance' => 0,
            ]);
        }

        // Normal Admin oluştur
        $normalAdmin = User::firstOrCreate([
            'email' => 'moderator@betground.com'
        ], [
            'name' => 'Moderator Admin',
            'email' => 'moderator@betground.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '05551234568',
            'birth_date' => '1992-01-01',
            'gender' => 'female',
            'country' => 'TR',
            'currency' => 'TRY',
            'status' => 'active',
            'kyc_status' => 'verified',
            'referral_code' => 'ADMIN002',
            'email_verified_at' => now(),
        ]);

        // Normal Admin için cüzdan oluştur (eğer yoksa)
        if (!$normalAdmin->wallet) {
            Wallet::create([
                'user_id' => $normalAdmin->id,
                'currency' => 'TRY',
                'balance' => 10000,
                'bonus_balance' => 0,
            ]);
        }

        $this->command->info('Admin kullanıcıları oluşturuldu:');
        $this->command->info('Super Admin: admin@betground.com / admin123');
        $this->command->info('Moderator: moderator@betground.com / admin123');
    }
}