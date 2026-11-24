<?php

require_once __DIR__ . '/Session.php';

class Auth
{
    public static function check(): bool
    {
        return Session::has('user');
    }

    public static function user(): ?array
    {
        return Session::get('user');
    }

    public static function id(): ?int
    {
        $user = self::user();
        return $user ? (int) $user['id'] : null;
    }

    public static function name(): string
    {
        $user = self::user();
        return $user ? (string) $user['name'] : '';
    }

    public static function email(): string
    {
        $user = self::user();
        return $user ? (string) $user['email'] : '';
    }

    public static function role(): string
    {
        $user = self::user();
        return $user ? (string) ($user['role'] ?? 'user') : 'user';
    }

    public static function isAdmin(): bool
    {
        return self::role() === 'admin';
    }

    public static function isPinVerified(): bool
    {
        // If user is admin, PIN lock screen is not required for admin duties, but can be used if desired.
        // For standard writers, pin_verified must be true.
        return (bool) Session::get('pin_verified', false);
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            Session::setFlash('errors', ['Please log in to access your journal.']);
            header('Location: index.php?page=login');
            exit;
        }
    }

    public static function requirePinVerified(): void
    {
        self::requireLogin();

        // Admins accessing dashboard or user tools also have PIN verification or if role is user
        if (!self::isPinVerified()) {
            header('Location: index.php?page=pin-lock');
            exit;
        }
    }

    public static function requireAdmin(): void
    {
        self::requireLogin();

        if (!self::isAdmin()) {
            Session::setFlash('errors', ['Access denied. Administrator privileges required.']);
            header('Location: index.php?page=dashboard');
            exit;
        }
    }

    public static function logout(): void
    {
        Session::destroy();
        header('Location: index.php?page=login');
        exit;
    }
}
