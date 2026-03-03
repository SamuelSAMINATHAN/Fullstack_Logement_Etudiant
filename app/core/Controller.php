<?php

abstract class Controller
{
    protected function render(string $view, array $params = []): void
    {
        extract($params);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(500);
            echo "Vue \"$view\" introuvable.";
            return;
        }

        // Layout simple
        include __DIR__ . '/../views/layout/header.php';
        include $viewFile;
        include __DIR__ . '/../views/layout/footer.php';
    }
}

