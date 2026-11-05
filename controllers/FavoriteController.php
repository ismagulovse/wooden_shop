<?php

class FavoriteController extends BaseController
{
    public function index(): void
    {
        require_auth();
        $favorites = (new Favorite())->listByUser((int) user_id());

        $this->render('favorites/index', [
            'title' => 'Избранное',
            'favorites' => $favorites,
            'breadcrumbs' => [
                ['title' => 'Главная', 'url' => '/'],
                ['title' => 'Избранное', 'url' => '/favorites'],
            ],
        ]);
    }

    public function add(): void
    {
        verify_csrf();

        if (!is_logged_in()) {
            flash('auth', 'Чтобы добавить в избранное, войдите в аккаунт.', 'error');
            redirect('/login');
        }

        $productId = (int) ($_POST['product_id'] ?? 0);
        if ($productId > 0) {
            (new Favorite())->add((int) user_id(), $productId);
        }

        $this->back();
    }

    public function remove(): void
    {
        verify_csrf();
        require_auth();

        $productId = (int) ($_POST['product_id'] ?? 0);
        if ($productId > 0) {
            (new Favorite())->remove((int) user_id(), $productId);
        }

        $this->back();
    }

    public function moveToCart(): void
    {
        verify_csrf();
        require_auth();

        $productId = (int) ($_POST['product_id'] ?? 0);
        if ($productId > 0) {
            $userId = (int) user_id();
            $cart = new Cart();
            $favorite = new Favorite();

            $cart->add($userId, $productId, 1);
            $favorite->remove($userId, $productId);
        }

        header('Location: /cart', true, 303);
        exit;
    }
}
