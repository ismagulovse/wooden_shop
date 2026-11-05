<?php

class AdminCategoryController extends AdminBaseController
{
    public function index(): void
    {
        $list = (new Category())->all(true);
        $this->renderAdmin('admin/categories/index', ['title' => 'Категории', 'categories' => $list]);
    }

    public function create(): void
    {
        $this->renderAdmin('admin/categories/form', ['title' => 'Новая категория', 'category' => null]);
    }

    public function store(): void
    {
        verify_csrf();

        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($name === '') {
            flash('main', 'Название обязательно.', 'error');
            redirect('/admin/categories/create');
        }

        (new Category())->create([
            'name' => $name,
            'slug' => slugify($_POST['slug'] ?: $name),
            'description' => $description,
        ]);

        flash('main', 'Категория создана.');
        redirect('/admin/categories');
    }

    public function edit(int $id): void
    {
        $category = (new Category())->find($id);
        if (!$category) {
            http_response_code(404);
            (new ErrorController())->notFound();
            return;
        }

        $this->renderAdmin('admin/categories/form', ['title' => 'Редактирование категории', 'category' => $category]);
    }

    public function update(int $id): void
    {
        verify_csrf();

        $name = trim($_POST['name'] ?? '');
        if ($name === '') {
            flash('main', 'Название обязательно.', 'error');
            redirect('/admin/categories/' . $id . '/edit');
        }

        (new Category())->update($id, [
            'name' => $name,
            'slug' => slugify($_POST['slug'] ?: $name),
            'description' => trim($_POST['description'] ?? ''),
        ]);

        flash('main', 'Категория обновлена.');
        redirect('/admin/categories');
    }

    public function delete(int $id): void
    {
        verify_csrf();
        (new Category())->softDelete($id);
        flash('main', 'Категория удалена.');
        redirect('/admin/categories');
    }

    public function restore(int $id): void
    {
        verify_csrf();
        (new Category())->restore($id);
        flash('main', 'Категория восстановлена.');
        redirect('/admin/categories');
    }
}