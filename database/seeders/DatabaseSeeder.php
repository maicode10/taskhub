public function run(): void
{
    \App\Models\User::create([
        'name'     => 'Shop Owner',
        'email'    => 'admin@taskhub.test',
        'password' => 'Admin@123',
        'role'     => 'admin',
    ]);

    $staff = [
        ['Juan Dela Cruz', 'juan@taskhub.test'],
        ['Maria Santos',   'maria@taskhub.test'],
        ['Pedro Reyes',    'pedro@taskhub.test'],
        ['Ana Lim',        'ana@taskhub.test'],
    ];

    foreach ($staff as [$name, $email]) {
        \App\Models\User::create([
            'name'     => $name,
            'email'    => $email,
            'password' => 'Staff@123',
            'role'     => 'staff',
        ]);
    }
}