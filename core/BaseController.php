<?php

abstract class BaseController
{
    protected function render(string $view, array $data = [], string $layout = 'main'): void
    {
        $viewFile = ROOT_PATH . '/views/' . $view . '.php';
        if (!is_file($viewFile)) {
            throw new RuntimeException('View not found: ' . $view);
        }

        extract($data, EXTR_SKIP);
        ob_start();
        require $viewFile;
        $content = (string) ob_get_clean();

        $layoutFile = ROOT_PATH . '/views/layouts/' . $layout . '.php';
        require $layoutFile;
    }

    protected function back(): void
    {
        $to = $_SERVER['HTTP_REFERER'] ?? '/';
        redirect($to);
    }
}

