<?php

require_once __DIR__ . '/../../training/QuoteComparison.php';

class AdminTrainingController extends AdminBaseController
{
    public function index(): void
    {
        $input = \WoodenTraining\QuoteComparison::DEFAULTS;
        $error = null;
        foreach ($input as $key => $default) {
            if (array_key_exists($key, $_GET)) {
                $value = is_string($_GET[$key]) ? filter_var($_GET[$key], FILTER_VALIDATE_INT) : false;
                [$min, $max] = \WoodenTraining\QuoteComparison::LIMITS[$key];
                if ($value === false || $value < $min || $value > $max) {
                    $error = 'Введите целые числа в указанных диапазонах.';
                } else {
                    $input[$key] = $value;
                }
            }
        }
        $this->renderAdmin('admin/training', [
            'title' => 'Учебные расчёты Wooden',
            'input' => $input,
            'error' => $error,
            'rows' => $error === null ? (new \WoodenTraining\QuoteComparison())->calculate($input) : [],
            'limits' => \WoodenTraining\QuoteComparison::LIMITS,
        ]);
    }
}
