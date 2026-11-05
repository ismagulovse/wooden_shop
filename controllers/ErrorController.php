<?php

class ErrorController extends BaseController
{
    public function notFound(): void
    {
        $this->render('errors/404', ['title' => 'Страница не найдена']);
    }
}

