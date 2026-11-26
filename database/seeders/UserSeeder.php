<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Seed initial demo accounts (1 ADMIN, 3 USER) idempotently.
     */
    public function run(): void
    {
        $accounts = [
            [
                'name' => 'Administrator KursusKu',
                'username' => 'admin',
                'email' => 'admin@kursusku.test',
                'role' => 'admin',
                'phone' => '081100000001',
            ],
            [
                'name' => 'Alya Rahma',
                'username' => 'alya',
                'email' => 'alya@kursusku.test',
                'role' => 'user',
                'phone' => '081200000002',
            ],
            [
                'name' => 'Bima Putra',
                'username' => 'bima',
                'email' => 'bima@kursusku.test',
                'role' => 'user',
                'phone' => '081200000003',
            ],
            [
                'name' => 'Citra Nabila',
                'username' => 'citra',
                'email' => 'citra@kursusku.test',
                'role' => 'user',
                'phone' => '081200000004',
            ],
        ];

        $createdCredentials = [];

        foreach ($accounts as $account) {
            $existing = User::where('email', $account['email'])->first();

            if ($existing) {
                $this->command?->line("Akun {$account['email']} sudah ada (role: {$existing->role}), password tidak diubah.");
                continue;
            }

            $generatedPassword = Str::password(14, symbols: false) . '!9';

            $user = new User([
                'name' => $account['name'],
                'username' => $account['username'],
                'email' => $account['email'],
                'phone' => $account['phone'],
                'password' => Hash::make($generatedPassword),
            ]);
            $user->role = $account['role'];
            $user->is_active = true;
            $user->email_verified_at = now();
            $user->save();

            $createdCredentials[] = [
                'role' => strtoupper($account['role']),
                'email' => $account['email'],
                'username' => $account['username'],
                'password' => $generatedPassword,
            ];
        }

        if (!empty($createdCredentials)) {
            $this->command?->info('=== KREDENSIAL AWAL AKUN DEMO KURSUSKU ===');
            foreach ($createdCredentials as $cred) {
                $this->command?->info(
                    "[{$cred['role']}] Email: {$cred['email']} | Username: {$cred['username']} | Password: {$cred['password']}"
                );
            }
            $this->command?->info('==========================================');
        }
    }
}
