@extends('layouts.app')

@section('content')
    <form method="POST" action="{{ route('tasks.update', ['id' => $task->id]) }}">
        <input type="hidden" name="_method" value="PUT">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input value="{{ $task->title ?? null }}" name="task[title]" type="text"
                class="form-control @isset($errors['task.title']) is-invalid @endisset" id="title"
                aria-describedby="title">
            @isset($errors['task.title'])
                <div class="invalid-feedback">
                    {{ collect($errors['task.title'])->join(' ') }}
                </div>
            @endisset
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="task[description]" rows="5"
                class="form-control @isset($errors['task.description']) is-invalid @endisset"
                id="description">{{ $task->description ?? null }}</textarea>

            @isset($errors['task.description'])
                <div class="invalid-feedback">
                    {{ collect($errors['task.description'])->join(' ') }}
                </div>
            @endisset
        </div>
        <div class="mb-3">
            <label for="deadline" class="form-label">Deadline</label>
            <input value="{{ $task->deadline ?? null }}" name="task[deadline]" type="date"
                class="form-control @isset($errors['task.deadline']) is-invalid @endisset" id="deadline"
                aria-describedby="deadline">

            @isset($errors['task.deadline'])
                <div class="invalid-feedback">
                    {{ collect($errors['task.deadline'])->join(' ') }}
                </div>
            @endisset
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
