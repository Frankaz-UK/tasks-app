<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): Factory|View|Application
    {
        $tasks = Task::all();
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Store a newly created resource in storage. (note: Task $task creates a new instance of Task model)
     *
     * @param Request $request
     * @param Task $task
     * @return RedirectResponse
     */
    public function store(Request $request, Task $task): RedirectResponse
    {
        $request->validate([
            'name' => [
                'string',
                'max:255',
                'min:15',
            ],
        ]);

        $task->name = $request->input('name');
        $task->save();
        return redirect()->route('tasks.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Task $task
     * @return RedirectResponse
     */
    public function update(Task $task): RedirectResponse
    {
        $status = $task->getCompleteStatus();
        $status ? $task->unSetComplete() : $task->setComplete();
        return redirect()->route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Task $task
     * @return RedirectResponse
     */
    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();
        return redirect()->route('tasks.index');
    }
}
