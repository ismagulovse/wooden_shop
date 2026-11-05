<?php

abstract class AdminBaseController extends BaseController
{
    public function __construct()
    {
        require_admin();
    }

    protected function renderAdmin(string $view, array $data = []): void
    {
        $this->render($view, $data, 'admin');
    }
}

