<?php

class AdminProductController extends AdminBaseController
{
    public function index(): void
    {
        $products = (new Product())->all(true);
        $this->renderAdmin('admin/products/index', ['title' => 'Товары', 'products' => $products]);
    }

    public function create(): void
    {
        $this->renderAdmin('admin/products/form', [
            'title' => 'Новый товар',
            'product' => null,
            'categories' => (new Category())->all(false),
            'images' => [],
        ]);
    }

    public function store(): void
    {
        verify_csrf();

        $data = $this->payload();
        $productId = (new Product())->create($data);
        $this->handleImages($productId);

        flash('main', 'Товар создан.');
        redirect('/admin/products');
    }

    public function edit(int $id): void
    {
        $model = new Product();
        $product = $model->find($id);
        if (!$product) {
            http_response_code(404);
            (new ErrorController())->notFound();
            return;
        }

        $this->renderAdmin('admin/products/form', [
            'title' => 'Редактирование товара',
            'product' => $product,
            'categories' => (new Category())->all(false),
            'images' => $model->images($id),
        ]);
    }

    public function update(int $id): void
    {
        verify_csrf();

        (new Product())->update($id, $this->payload());
        $this->handleImages($id);

        flash('main', 'Товар обновлен.');
        redirect('/admin/products');
    }

    public function delete(int $id): void
    {
        verify_csrf();
        (new Product())->softDelete($id);
        flash('main', 'Товар удален.');
        redirect('/admin/products');
    }

    public function restore(int $id): void
    {
        verify_csrf();
        (new Product())->restore($id);
        flash('main', 'Товар восстановлен.');
        redirect('/admin/products');
    }

    private function payload(): array
    {
        return [
            'category_id' => (int) ($_POST['category_id'] ?? 0),
            'name' => trim($_POST['name'] ?? ''),
            'slug' => slugify($_POST['slug'] ?: ($_POST['name'] ?? '')),
            'sku' => trim($_POST['sku'] ?? ''),
            'price' => (float) ($_POST['price'] ?? 0),
            'old_price' => $_POST['old_price'] !== '' ? (float) $_POST['old_price'] : null,
            'short_description' => trim($_POST['short_description'] ?? ''),
            'full_description' => trim($_POST['full_description'] ?? ''),
            'material' => trim($_POST['material'] ?? ''),
            'color' => trim($_POST['color'] ?? ''),
            'dimensions' => trim($_POST['dimensions'] ?? ''),
            'stock' => (int) ($_POST['stock'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
        ];
    }

    private function handleImages(int $productId): void
    {
        if (empty($_FILES['images']['name'][0])) {
            return;
        }

        if (!is_dir(UPLOAD_DIR)) {
            mkdir(UPLOAD_DIR, 0775, true);
        }

        $product = new Product();
        $files = $_FILES['images'];

        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                continue;
            }

            $tmp = $files['tmp_name'][$i];
            $name = preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($files['name'][$i]));
            $filename = uniqid('p_', true) . '_' . $name;
            $target = UPLOAD_DIR . '/' . $filename;

            if (move_uploaded_file($tmp, $target)) {
                $product->addImage($productId, UPLOAD_URL . '/' . $filename, $i + 1);
            }
        }
    }
}