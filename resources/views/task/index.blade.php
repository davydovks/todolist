@extends('layouts.app')

@section('content')
    <div class="my-3">
        <a class="mx-2" href="{{ route('tasks.create') }}">Create task</a>
        <a class="mx-2" href="{{ route('tasks.completed') }}">Show completed</a>
    </div>

    <div class="row row-cols-4 g-3">
        @foreach ($tasks as $task)
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <small>Deadline: {{ $task->deadline }}</small>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $task->title }}</h5>
                        <p class="card-text">{{ \Illuminate\Support\Str::limit($task->description) }}</p>
                        <form class="d-inline-block" method="POST"
                            action="{{ route('tasks.complete', ['id' => $task->id]) }}">
                            <button type="submit" class="btn btn-success"><i class="bi bi-check-lg"></i></button>
                        </form>
                        <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#task-modal-{{ $task->id }}"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('tasks.edit', ['id' => $task->id]) }}" class="btn btn-secondary"><i
                                class="bi bi-pencil-square"></i></a>
                        <form class="d-inline-block" method="POST"
                            action="{{ route('tasks.destroy', ['id' => $task->id]) }}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger"><i class="bi bi-trash3"></i></button>
                        </form>
                    </div>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="task-modal-{{ $task->id }}" tabindex="-1" aria-labelledby="task-modal"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="task-modal">{{ $task->title }}</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                {{ $task->description }}
                                <small class="d-block mt-3">Deadline: {{ $task->deadline }}</small>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
