<?php

namespace App\Core;

abstract class Controller
{
    protected function view(string $viewPath, array $data = []): void
    {
        extract($data);

        $viewFile = __DIR__ . '/../Views/' . $viewPath . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(500);
            echo "Vue introuvable : $viewPath";
            return;
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        require __DIR__ . '/../Views/layouts/main.php';
    }

    protected function redirect(string $url): void
    {
        header("Location: /$url");
        exit;
    }
}
