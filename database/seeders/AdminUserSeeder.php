<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Initial administrator accounts as login => display name.
     *
     * @var array<string, string>
     */
    public const array ACCOUNTS = [
        'alakotowska' => 'Ala Kotowska',
        'sebsqu' => 'sebsqu',
    ];

    /**
     * Create the missing initial administrator accounts, all with the same password.
     *
     * The password comes from ADMIN_PASSWORD when set; otherwise a random one is generated
     * and printed once. Existing accounts are left untouched, so passwords changed in the panel are not overwritten.
     */
    public function run(): void
    {
        $existingUsernames = User::whereIn('username', array_keys(self::ACCOUNTS))->pluck('username')->all();
        $missingAccounts = array_diff_key(self::ACCOUNTS, array_flip($existingUsernames));

        if ($missingAccounts === []) {
            return;
        }

        $configuredPassword = config('auth.admin_password');
        $password = filled($configuredPassword) ? $configuredPassword : Str::password(20, symbols: false);

        foreach ($missingAccounts as $username => $name) {
            $admin = new User(['username' => $username, 'name' => $name, 'password' => $password]);
            $admin->is_admin = true;
            $admin->save();
        }

        if (blank($configuredPassword)) {
            $this->command?->warn('Utworzono konta: '.implode(', ', array_keys($missingAccounts)).'. Wspólne hasło: '.$password);
            $this->command?->warn('Zapisz je teraz – nie zostanie wyświetlone ponownie. Zmienisz je w panelu w zakładce „Moje konto”.');
        }
    }
}
