<?php

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () {
    return redirect()->to(route('tasks.index'));
});

/**
 * Functions
 */

function findTaskById($id)
{
    $task = app('db')->table('tasks')->where(['id' => $id])->first();
    if ($task === null) {
        abort(404);
    }

    return $task;
}

function createValidator($request)
{
    return Validator::make($request->all(), [
        'task.title' => 'required|string',
        'task.description' => 'nullable|string',
        'task.deadline' => 'required|date|after_or_equal:today'
    ]);
}

/**
 * Pages
 */
$router->get('/tasks', [
    'as' => 'tasks.index',
    function () {
        $tasks = app('db')->table('tasks')
            ->where(['is_complete' => false])
            ->latest()
            ->get();

        return view('task.index', compact('tasks'));
    }
]);

$router->get('/tasks/completed', [
    'as' => 'tasks.completed',
    function () {
        $tasks = app('db')->table('tasks')
            ->where(['is_complete' => true])
            ->latest()
            ->get();

        return view('task.completed', compact('tasks'));
    }
]);

$router->get('/tasks/create', [
    'as' => 'tasks.create',
    function () {
        return view('task.create');
    }
]);

$router->get('/tasks/{id}/edit', [
    'as' => 'tasks.edit',
    function ($id) {
        $task = findTaskById($id);

        return view('task.edit', compact('task'));
    }
]);

/**
 * Handlers
 */
$router->post('/tasks', [
    'as' => 'tasks.store',
    function (Request $request) {
        $validator = createValidator($request);

        if (!$validator->valid()) {
            $old = $request->get('task');
            $errors = $validator->errors()->getMessages();
            return view('task.create', compact('old', 'errors'));
        }

        app('db')->table('tasks')->insert([
            ...$request->get('task'),
            'is_complete' => false,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return redirect()->to(route('tasks.index'));
    }
]);
$router->put('/tasks/{id}', [
    'as' => 'tasks.update',
    function (Request $request, $id) {
        $task = findTaskById($id);

        $validator = createValidator($request);

        if (!$validator->valid()) {
            $errors = $validator->errors()->getMessages();
            return view('task.edit', compact('errors'));
        }

        app('db')->table('tasks')->where(['id' => $id])->update([
            ...$request->get('task'),
            'updated_at' => Carbon::now(),
        ]);
        return redirect()->to(route('tasks.index'));
    }
]);

$router->delete('/tasks/{id}', [
    'as' => 'tasks.destroy',
    function ($id) {
        $task = findTaskById($id);

        app('db')->table('tasks')->where(['id' => $id])->delete();

        return redirect()->to(route('tasks.index'));
    }
]);

$router->post('/tasks/{id}/complete', [
    'as' => 'tasks.complete',
    function ($id) {
        $task = findTaskById($id);

        app('db')->table('tasks')->where(['id' => $id])->update([
            'is_complete' => true,
        ]);

        return redirect()->to(route('tasks.index'));
    }
]);
