@extends('layouts.app')
@section('title','Задачи')
@section('header','Все задачи')
@section('content')
<div class="bg-white rounded-xl border border-slate-200 p-4 mb-6">
    <form class="flex flex-wrap gap-3">
        <select name="project_id" class="px-3 py-2 border border-slate-300 rounded-lg text-sm">
            <option value="">Все проекты</option>
            @foreach($projects as $p)
            <option value="{{ $p->id }}" {{ request('project_id')==$p->id ? 'selected' : '' }}>{{ $p->name }}</option>
            @endforeach
        </select>
        <select name="status" class="px-3 py-2 border border-slate-300 rounded-lg text-sm">
            <option value="">Все статусы</option>
            @foreach(['todo'=>'К выполнению','in_progress'=>'В работе','review'=>'На проверке','done'=>'Выполнено','cancelled'=>'Отменено'] as $v=>$l)
            <option value="{{ $v }}" {{ request('status')==$v ? 'selected' : '' }}>{{ $l }}</option>
            @endforeach
        </select>
        <select name="priority" class="px-3 py-2 border border-slate-300 rounded-lg text-sm">
            <option value="">Все приоритеты</option>
            @foreach(['critical'=>'Критический','high'=>'Высокий','medium'=>'Средний','low'=>'Низкий'] as $v=>$l)
            <option value="{{ $v }}" {{ request('priority')==$v ? 'selected' : '' }}>{{ $l }}</option>
            @endforeach
        </select>
        <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
            <input type="checkbox" name="my_tasks" value="1" {{ request('my_tasks') ? 'checked' : '' }} class="rounded"> Только мои
        </label>
        <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
            <input type="checkbox" name="overdue" value="1" {{ request('overdue') ? 'checked' : '' }} class="rounded"> Просроченные
        </label>
        <button type="submit" class="px-4 py-2 bg-slate-800 text-white text-sm rounded-lg">Применить</button>
        <a href="{{ route('tasks.index') }}" class="px-4 py-2 border border-slate-300 text-slate-600 text-sm rounded-lg hover:bg-slate-50">Сбросить</a>
    </form>
</div>
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="divide-y divide-slate-100">
        @forelse($tasks as $task)
        <div class="flex items-center gap-4 p-4 hover:bg-slate-50 transition-colors">
            <div class="w-1 h-10 rounded-full flex-shrink-0 {{
                $task->priority==='critical' ? 'bg-red-500' :
                ($task->priority==='high' ? 'bg-orange-500' :
                ($task->priority==='medium' ? 'bg-yellow-400' : 'bg-slate-200'))
            }}"></div>
            <div class="flex-1 min-w-0">
                <a href="{{ route('tasks.show', $task) }}" class="text-sm font-medium text-slate-800 hover:text-blue-600 truncate block">{{ $task->title }}</a>
                <div class="flex items-center gap-2 mt-0.5">
                    <div class="w-2 h-2 rounded-full" style="background: {{ $task->project->color }}"></div>
                    <span class="text-xs text-slate-400">{{ $task->project->name }}</span>
                    @foreach($task->tags as $tag)
                    <span class="text-xs px-1.5 py-0.5 rounded" style="background: {{ $tag->color }}22; color: {{ $tag->color }}">{{ $tag->name }}</span>
                    @endforeach
                </div>
            </div>
            @if($task->assignee)
            <div class="flex items-center gap-2 flex-shrink-0">
                <img src="{{ $task->assignee->avatar_url }}" class="w-6 h-6 rounded-full">
                <span class="text-xs text-slate-600 hidden md:block">{{ $task->assignee->name }}</span>
            </div>
            @endif
            <span class="text-xs font-medium px-2.5 py-1 rounded-full flex-shrink-0 {{
                $task->status==='done' ? 'bg-emerald-100 text-emerald-700' :
                ($task->status==='in_progress' ? 'bg-blue-100 text-blue-700' :
                ($task->status==='review' ? 'bg-purple-100 text-purple-700' :
                ($task->status==='cancelled' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-600')))
            }}">{{ $task->status_label }}</span>
            @if($task->due_date)
            <span class="text-xs flex-shrink-0 {{ $task->is_overdue ? 'text-red-600 font-semibold' : 'text-slate-400' }}">{{ $task->due_date->format('d.m.y') }}</span>
            @endif
        </div>
        @empty
        <div class="p-16 text-center text-slate-400"><p class="text-3xl mb-3">🔍</p><p>Задач не найдено</p></div>
        @endforelse
    </div>
</div>
<div class="mt-4">{{ $tasks->appends(request()->query())->links() }}</div>
@endsection
