<?php

namespace App\Core;

class Controller
{
    /**
     * Render a view and pass data to it.
     *
     * @param string $view The name of the view file to load (e.g., 'home/index').
     * @param array $data Associative array of data to pass to the view.
     * @return void
     */
    protected function view($view, $data = [])
    {
        // Extract data array to variables
        extract($data);

        // Build the full path to the view file
        $viewPath = __DIR__ . "/../Views/{$view}.php";

        // Check if the view file exists
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            throw new \Exception("View file '{$viewPath}' not found.");
        }
    }

    /**
     * Redirect the user to a different page.
     *
     * @param string $url The URL to redirect to.
     * @return void
     */
    protected function redirect($url)
    {
        header("Location: {$url}");
        exit();
    }

    /**
     * Load a model class.
     *
     * @param string $model The name of the model class to load.
     * @return object The instance of the model.
     */
    protected function model($model)
    {
        $modelClass = "App\\Models\\{$model}";
        if (class_exists($modelClass)) {
            return new $modelClass();
        } else {
            throw new \Exception("Model class '{$modelClass}' not found.");
        }
    }

    /**
     * Handle JSON responses.
     *
     * @param array $data Data to return as a JSON response.
     * @param int $status HTTP status code.
     * @return void
     */
    protected function jsonResponse($data, $status = 200)
    {
        header('Content-Type: application/json', true, $status);
        echo json_encode($data);
        exit();
    }
}
