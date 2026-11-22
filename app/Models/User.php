protected $fillable = [
    'name', 'email', 'password', 'role',
    'failed_attempts', 'locked_until', 'is_active',
];

protected function casts(): array
{
    return [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'locked_until'      => 'datetime',
        'is_active'         => 'boolean',
    ];
}

public function isAdmin(): bool
{
    return $this->role === 'admin';
}

public function isLocked(): bool
{
    return $this->locked_until && $this->locked_until->isFuture();
}