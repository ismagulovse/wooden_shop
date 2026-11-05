<?php

class AdminOrderController extends AdminBaseController
{
    public function index(): void
    {
        $orders = (new Order())->all(true);
        $this->renderAdmin('admin/orders/index', ['title' => 'Заказы', 'orders' => $orders]);
    }

    public function show(int $id): void
    {
        $order = (new Order())->find($id);
        if (!$order) {
            http_response_code(404);
            (new ErrorController())->notFound();
            return;
        }

        $this->renderAdmin('admin/orders/show', ['title' => 'Заказ #' . $id, 'order' => $order]);
    }

    public function status(int $id): void
    {
        verify_csrf();

        $status = $_POST['status'] ?? 'new';
        $saved = (new Order())->updateStatus($id, $status);

        if (!$saved) {
            flash('main', 'Не удалось сохранить статус: недопустимое значение.', 'error');
        } else {
            flash('main', 'Статус заказа сохранен.');
        }

        redirect('/admin/orders/' . $id);
    }

    public function delete(int $id): void
    {
        verify_csrf();
        (new Order())->softDelete($id);
        flash('main', 'Заказ удален.');
        redirect('/admin/orders');
    }

    public function restore(int $id): void
    {
        verify_csrf();
        (new Order())->restore($id);
        flash('main', 'Заказ восстановлен.');
        redirect('/admin/orders');
    }
}