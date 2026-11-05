<?php

class CartController extends BaseController
{
    public function index(): void
    {
        require_auth();
        $cart = new Cart();
        $items = $cart->items((int) user_id());

        $this->render('cart/index', [
            'title' => 'Корзина',
            'items' => $items,
            'total' => $cart->total((int) user_id()),
            'breadcrumbs' => [
                ['title' => 'Главная', 'url' => '/'],
                ['title' => 'Корзина', 'url' => '/cart'],
            ],
        ]);
    }

    public function add(): void
    {
        verify_csrf();
        require_auth();

        $productId = (int) ($_POST['product_id'] ?? 0);
        $qty = (int) ($_POST['quantity'] ?? 1);

        if ($productId > 0) {
            (new Cart())->add((int) user_id(), $productId, $qty);
            flash('main', 'Товар добавлен в корзину.');
        }

        $this->back();
    }

    public function update(): void
    {
        verify_csrf();
        require_auth();

        $itemId = (int) ($_POST['item_id'] ?? 0);
        $qty = (int) ($_POST['quantity'] ?? 1);

        if ($itemId > 0) {
            (new Cart())->updateItem((int) user_id(), $itemId, $qty);
        }

        redirect('/cart');
    }

    public function remove(): void
    {
        verify_csrf();
        require_auth();

        $itemId = (int) ($_POST['item_id'] ?? 0);
        if ($itemId > 0) {
            (new Cart())->removeItem((int) user_id(), $itemId);
            flash('main', 'Позиция удалена.');
        }

        redirect('/cart');
    }

    public function clear(): void
    {
        verify_csrf();
        require_auth();

        (new Cart())->clear((int) user_id());
        flash('main', 'Корзина очищена.');
        redirect('/cart');
    }
}

