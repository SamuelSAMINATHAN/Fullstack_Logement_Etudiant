<?php

namespace App\Core;


class Controller
{
    protected function view(string $view, array $data = []): void
    {
        $viewPath = APPROOT . '/views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            error_log("[Controller] Vue introuvable : $viewPath");

            $fallback = APPROOT . '/views/errors/404.php';
            if (file_exists($fallback)) {
                extract(['title' => 'Page introuvable'], EXTR_SKIP);
                require $fallback;
            } else {
                http_response_code(404);
                die("Erreur 404 — Page introuvable.");
            }
            return;
        }

        // Ajouter les flash messages automatiquement
        if (empty($data['success_message'])) {
            $data['success_message'] = $this->getFlash('success');
        }
        if (empty($data['error_message'])) {
            $data['error_message'] = $this->getFlash('error');
        }

        extract($data, EXTR_SKIP);
        class_alias('\App\Core\Security', 'Security');
        class_alias('\App\Core\Session', 'Session');

        require $viewPath;
    }

    protected function model(string $model): object
    {
        $fullModelName = '\\App\\Models\\' . $model;
        $modelPath = APPROOT . '/models/' . $model . '.php';

        if (!file_exists($modelPath)) {
            throw new \Exception("Modèle '$model' introuvable.");
        }

        require_once $modelPath;

        if (!class_exists($fullModelName)) {
            throw new \Exception("La classe '$model' n'existe pas dans $modelPath.");
        }

        return new $fullModelName();
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . URLROOT . $url);
        exit;
    }

    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']) || isset($_SESSION['admin_id']);
    }

    protected function requireAuth(): void
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/auth/login');
        }
    }

    protected function requireRole(string $role): void
    {
        $this->requireAuth();

        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== $role) {
            $this->redirect('/');
        }
    }

    protected function isAdminLoggedIn(): bool
    {
        return isset($_SESSION['admin_id']);
    }

    protected function requireAdmin(): void
    {
        if (!$this->isAdminLoggedIn()) {
            $this->redirect('/admin/login');
        }
    }

    protected function sanitizePost(): array
    {
        $clean = [];
        foreach ($_POST as $key => $value) {
            $clean[$key] = is_string($value) ? trim($value) : $value;
        }
        return $clean;
    }

    protected function setFlash(string $key, string $message): void
    {
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }

        $_SESSION['flash'][$key] = $message;
    }

    protected function getFlash(string $key): ?string
    {
        if (!isset($_SESSION['flash'][$key])) {
            return null;
        }

        $message = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);

        if (empty($_SESSION['flash'])) {
            unset($_SESSION['flash']);
        }

        return $message;
    }
}