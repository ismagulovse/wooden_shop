<?php

class CheckoutController extends BaseController
{
    public function index(): void
    {
        require_auth();

        $cart = new Cart();
        $items = $cart->items((int) user_id());
        if (!$items) {
            flash('main', 'Корзина пуста.', 'error');
            redirect('/cart');
        }

        $user = (new User())->findById((int) user_id());
        $now = new DateTimeImmutable('now');
        $delivery = $now->modify('+7 days');
        $orderDateText = $now->format('d') . '.' . $now->format('m') . '.' . $now->format('Y') . ' ' . $now->format('H') . ':' . $now->format('i');
        $deliveryDateText = $delivery->format('d') . '.' . $delivery->format('m') . '.' . $delivery->format('Y') . ' ' . $delivery->format('H') . ':' . $delivery->format('i');

        $this->render('checkout/index', [
            'title' => 'Оформление заказа',
            'items' => $items,
            'total' => $cart->total((int) user_id()),
            'user' => $user,
            'previewOrderDateText' => $orderDateText,
            'previewDeliveryDateText' => $deliveryDateText,
            'supplier' => [
                'name' => 'Wooden Shop',
                'contact' => '+7 (900) 000-00-00',
                'email' => 'hello@wooden.local',
                'address' => 'г. Ханты-Мансийск, ул. Чехова, 16',
            ],
            'breadcrumbs' => [
                ['title' => 'Главная', 'url' => '/'],
                ['title' => 'Корзина', 'url' => '/cart'],
                ['title' => 'Оформление', 'url' => '/checkout'],
            ],
        ]);
    }

    public function place(): void
    {
        verify_csrf();
        require_auth();

        $payload = [
            'customer_name' => trim($_POST['customer_name'] ?? ''),
            'customer_phone' => trim($_POST['customer_phone'] ?? ''),
            'customer_email' => trim($_POST['customer_email'] ?? ''),
            'delivery_address' => trim($_POST['delivery_address'] ?? ''),
            'comment' => trim($_POST['comment'] ?? ''),
            'payment_method' => trim($_POST['payment_method'] ?? ''),
            'delivery_method' => trim($_POST['delivery_method'] ?? ''),
        ];

        if ($payload['customer_name'] === '' || $payload['delivery_address'] === '' || !filter_var($payload['customer_email'], FILTER_VALIDATE_EMAIL)) {
            flash('main', 'Проверьте корректность данных для заказа.', 'error');
            redirect('/checkout');
        }

        $cart = new Cart();
        $items = $cart->items((int) user_id());
        if (!$items) {
            flash('main', 'Корзина пуста.', 'error');
            redirect('/cart');
        }

        $total = $cart->total((int) user_id());
        $orderModel = new Order();

        try {
            $orderId = $orderModel->createFromCart((int) user_id(), $payload, $items, $total);
            $cart->clear((int) user_id());
            flash('main', 'Заказ успешно оформлен.');
            redirect('/checkout/success/' . $orderId);
        } catch (Throwable $e) {
            $message = trim($e->getMessage());
            flash('main', $message !== '' ? $message : 'Не удалось оформить заказ.', 'error');
            redirect('/checkout');
        }
    }

    public function success(int $id): void
    {
        require_auth();

        $order = (new Order())->find($id);
        if (!$order || (int) $order['user_id'] !== (int) user_id()) {
            http_response_code(404);
            (new ErrorController())->notFound();
            return;
        }

        $orderDate = new DateTimeImmutable($order['created_at']);
        $deliveryDate = $orderDate->modify('+7 days');
        $orderDateText = $orderDate->format('d') . '.' . $orderDate->format('m') . '.' . $orderDate->format('Y') . ' ' . $orderDate->format('H') . ':' . $orderDate->format('i');
        $deliveryDateText = $deliveryDate->format('d') . '.' . $deliveryDate->format('m') . '.' . $deliveryDate->format('Y') . ' ' . $deliveryDate->format('H') . ':' . $deliveryDate->format('i');

        $this->render('checkout/success', [
            'title' => 'Чек заказа #' . $id,
            'order' => $order,
            'orderDateText' => $orderDateText,
            'deliveryDateText' => $deliveryDateText,
            'supplier' => [
                'name' => 'Wooden Shop',
                'contact' => '+7 (900) 000-00-00',
                'email' => 'hello@wooden.local',
                'address' => 'г. Ханты-Мансийск, ул. Чехова, 16',
            ],
        ]);
    }
}
