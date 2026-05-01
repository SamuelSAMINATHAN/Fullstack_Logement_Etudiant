<?php

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

        extract($data, EXTR_SKIP);

        require $viewPath;
    }

    protected function model(string $model): object
    {
        $modelPath = APPROOT . '/models/' . $model . '.php';

        if (!file_exists($modelPath)) {
            error_log("[Controller] Modèle introuvable : $modelPath");
            throw new Exception("Modèle '$model' introuvable.");
        }

        require_once $modelPath;

        if (!class_exists($model)) {
            throw new Exception("La classe '$model' n'existe pas dans $modelPath.");
        }

        return new $model();
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