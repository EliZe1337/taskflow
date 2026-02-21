@extends('layouts.app')
@section('title', $task->title)
@section('header', 'Задача #' . $task->id)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-5">
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <a href="{{ route('projects.show', $task->project) }}" class="text-xs text-blue-600 font-medium">{{ $task->project->name }}</a>
                        @if($task->parent)
                        <span class="text-slate-300">›</span>
                        <a href="{{ route('tasks.show', $task->parent) }}" class="text-xs text-slate-500">{{ $task->parent->title }}</a>
                        @endif
                    </div>
                    <h1 class="text-xl font-bold text-slate-800">{{ $task->title }}</h1>
                </div>
                @can('update', $task)
                <div class="flex gap-2 flex-shrink-0">
                    <a href="{{ route('tasks.edit', $task) }}" class="px-3 py-1.5 border border-slate-300 text-sm rounded-lg hover:bg-slate-50">Редактировать</a>
                    @can('delete', $task)
                    <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Удалить задачу?')">
                        @csrf @method('DELETE')
                        <button class="px-3 py-1.5 border border-red-300 text-red-600 text-sm rounded-lg hover:bg-red-50">Удалить</button>
                    </form>
                    @endcan
                </div>
                @endcan
            </div>
            @if($task->description)
            <div class="text-sm text-slate-600 leading-relaxed mb-4 whitespace-pre-wrap">{{ $task->description }}</div>
            @endif
            @if($task->tags->count())
            <div class="flex flex-wrap gap-2">
                @foreach($task->tags as $tag)
                <span class="text-xs font-medium px-2.5 py-1 rounded-full" style="background: {{ $tag->color }}22; color: {{ $tag->color }}">{{ $tag->name }}</span>
                @endforeach
            </div>
            @endif
        </div>

        @if($task->subtasks->count())
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <h3 class="font-semibold text-slate-800 mb-4">Подзадачи ({{ $task->subtasks->count() }})</h3>
            <div class="space-y-2">
                @foreach($task->subtasks as $sub)
                <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-50">
                    <div class="w-4 h-4 rounded border-2 {{ $sub->status==='done' ? 'bg-emerald-500 border-emerald-500' : 'border-slate-300' }} flex items-center justify-center">
                        @if($sub->status==='done') <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg> @endif
                    </div>
                    <a href="{{ route('tasks.show', $sub) }}" class="flex-1 text-sm {{ $sub->status==='done' ? 'line-through text-slate-400' : 'text-slate-700 hover:text-blue-600' }}">{{ $sub->title }}</a>
                    @if($sub->assignee) <img src="{{ $sub->assignee->avatar_url }}" class="w-5 h-5 rounded-full" title="{{ $sub->assignee->name }}"> @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <h3 class="font-semibold text-slate-800 mb-4">Комментарии ({{ $task->comments->count() }})</h3>
            <form method="POST" action="{{ route('comments.store', $task) }}" class="mb-5">
                @csrf
                <div class="flex gap-3">
                    <img src="{{ auth()->user()->avatar_url }}" class="w-8 h-8 rounded-full flex-shrink-0">
                    <div class="flex-1">
                        <textarea name="body" rows="3" required placeholder="Добавить комментарий..."
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                        <div class="flex justify-end mt-2">
                            <button type="submit" class="px-4 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">Отправить</button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="space-y-4">
                @forelse($task->comments as $comment)
                <div class="flex gap-3">
                    <img src="{{ $comment->user->avatar_url }}" class="w-8 h-8 rounded-full flex-shrink-0">
                    <div class="flex-1">
                        <div class="flex items-baseline gap-2 mb-1">
                            <span class="text-sm font-semibold text-slate-800">{{ $comment->user->name }}</span>
                            <span class="text-xs text-slate-400">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="text-sm text-slate-600 bg-slate-50 rounded-lg p-3">{{ $comment->body }}</div>
                        @if(auth()->id() === $comment->user_id || auth()->user()->hasRole('admin'))
                        <form method="POST" action="{{ route('comments.destroy', $comment) }}" class="mt-1">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-400 hover:text-red-600">Удалить</button>
                        </form>
                        @endif
                    </div>
                </div>
                @empty
                <p class="text-sm text-slate-400 text-center py-4">Нет комментариев</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="space-y-4">
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <h3 class="text-sm font-semibold text-slate-700 mb-3">Статус</h3>
            @can('update', $task)
            <form method="POST" action="{{ route('tasks.update-status', $task) }}" id="statusForm">
                @csrf @method('PATCH')
                <select name="status" onchange="document.getElementById('statusForm').submit()"
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none">
                    @foreach(['todo' => 'К выполнению', 'in_progress' => 'В работе', 'review' => 'На проверке', 'done' => 'Выполнено', 'cancelled' => 'Отменено'] as $val => $lb)
                    <option value="{{ $val }}" {{ $task->status===$val ? 'selected' : '' }}>{{ $lb }}</option>
                    @endforeach
                </select>
            </form>
            @else
            <span class="text-sm">{{ $task->status_label }}</span>
            @endcan
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-4 space-y-4">
            <h3 class="text-sm font-semibold text-slate-700">Детали</h3>
            <div>
                <div class="text-xs text-slate-500 mb-1">Приоритет</div>
                <span class="text-sm font-medium px-2.5 py-1 rounded-full
                    {{ $task->priority==='critical' ? 'bg-red-100 text-red-700' :
                    ($task->priority==='high' ? 'bg-orange-100 text-orange-700' :
                    ($task->priority==='medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-slate-100 text-slate-600')) }}">
                    {{ $task->priority_label }}
                </span>
            </div>
            <div>
                <div class="text-xs text-slate-500 mb-1">Исполнитель</div>
                @if($task->assignee)
                <div class="flex items-center gap-2">
                    <img src="{{ $task->assignee->avatar_url }}" class="w-7 h-7 rounded-full">
                    <div>
                        <div class="text-sm font-medium text-slate-800">{{ $task->assignee->name }}</div>
                        <div class="text-xs text-slate-400">{{ $task->assignee->position }}</div>
                    </div>
                </div>
                @else
                <span class="text-sm text-slate-400">Не назначено</span>
                @endif
            </div>
            <div>
                <div class="text-xs text-slate-500 mb-1">Создатель</div>
                <div class="flex items-center gap-2">
                    <img src="{{ $task->creator->avatar_url }}" class="w-6 h-6 rounded-full">
                    <span class="text-sm text-slate-700">{{ $task->creator->name }}</span>
                </div>
            </div>
            @if($task->due_date)
            <div>
                <div class="text-xs text-slate-500 mb-1">Дедлайн</div>
                <span class="text-sm {{ $task->is_overdue ? 'text-red-600 font-semibold' : 'text-slate-700' }}">
                    {{ $task->due_date->format('d.m.Y') }} @if($task->is_overdue) (просрочено) @endif
                </span>
            </div>
            @endif
            @if($task->estimated_hours)
            <div>
                <div class="text-xs text-slate-500 mb-1">Оценка / Факт</div>
                <span class="text-sm text-slate-700">{{ $task->estimated_hours }}ч / {{ $task->actual_hours ?? '—' }}ч</span>
            </div>
            @endif
            <div>
                <div class="text-xs text-slate-500 mb-1">Создано</div>
                <span class="text-sm text-slate-700">{{ $task->created_at->format('d.m.Y H:i') }}</span>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <h3 class="text-sm font-semibold text-slate-700 mb-3">История</h3>
            <div class="space-y-3">
                @forelse($task->activityLogs as $log)
                <div class="flex items-start gap-2">
                    <img src="{{ $log->user->avatar_url ?? '' }}" class="w-5 h-5 rounded-full flex-shrink-0 mt-0.5">
                    <div>
                        <span class="text-xs text-slate-600">{{ $log->user->name ?? 'Удалённый пользователь' }}</span>
                        <div class="text-xs text-slate-400">{{ $log->created_at?->diffForHumans() ?? '' }}</div>
                    </div>
                </div>
                @empty
                <p class="text-xs text-slate-400">Нет записей</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
