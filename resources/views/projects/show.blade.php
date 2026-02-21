@extends('layouts.app')
@section('title', $project->name)
@section('header', $project->name)

@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        <div class="w-4 h-4 rounded-full" style="background: {{ $project->color }}"></div>
        <span class="text-sm px-3 py-1 rounded-full font-medium
            {{ $project->status==='active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
            {{ $project->status_label }}
        </span>
    </div>
    <div class="flex gap-2">
        @can('update', $project)
        <a href="{{ route('projects.edit', $project) }}" class="px-4 py-2 border border-slate-300 text-sm rounded-lg hover:bg-slate-50">Редактировать</a>
        @endcan
        <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">+ Задача</a>
    </div>
</div>

<div class="grid grid-cols-5 gap-4 mb-6">
    @foreach(['todo' => ['К выполнению','text-slate-700'], 'in_progress' => ['В работе','text-blue-600'], 'review' => ['На проверке','text-purple-600'], 'done' => ['Выполнено','text-emerald-600']] as $st => [$lb, $cl])
    <div class="bg-white rounded-xl border border-slate-200 p-4 text-center">
        <div class="text-2xl font-bold {{ $cl }}">{{ $stats[$st] }}</div>
        <div class="text-xs text-slate-500 mt-1">{{ $lb }}</div>
    </div>
    @endforeach
    <div class="bg-white rounded-xl border border-slate-200 p-4 text-center">
        <div class="text-2xl font-bold text-slate-800">{{ $stats['total'] > 0 ? round($stats['done'] / $stats['total'] * 100) : 0 }}%</div>
        <div class="text-xs text-slate-500 mt-1">Прогресс</div>
    </div>
</div>

{{-- Kanban --}}
<div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
    @foreach(['todo' => 'К выполнению', 'in_progress' => 'В работе', 'review' => 'На проверке', 'done' => 'Выполнено'] as $status => $label)
    <div class="bg-slate-100 rounded-xl p-3">
        <div class="flex items-center justify-between mb-3 px-1">
            <span class="text-sm font-semibold text-slate-700">{{ $label }}</span>
            <span class="bg-slate-200 text-slate-600 text-xs font-medium px-2 py-0.5 rounded-full">{{ isset($tasks[$status]) ? $tasks[$status]->count() : 0 }}</span>
        </div>
        <div class="space-y-2 min-h-20">
            @foreach($tasks[$status] ?? [] as $task)
            <a href="{{ route('tasks.show', $task) }}" class="block bg-white rounded-lg p-3 shadow-sm hover:shadow-md transition border border-slate-200">
                <div class="flex items-start justify-between gap-2 mb-2">
                    <span class="text-sm font-medium text-slate-800 leading-snug">{{ $task->title }}</span>
                    <span class="w-2 h-2 rounded-full flex-shrink-0 mt-1
                        {{ $task->priority==='critical' ? 'bg-red-500' : ($task->priority==='high' ? 'bg-orange-500' : ($task->priority==='medium' ? 'bg-yellow-400' : 'bg-slate-300')) }}"></span>
                </div>
                @if($task->tags->count())
                <div class="flex flex-wrap gap-1 mb-2">
                    @foreach($task->tags as $tag)
                    <span class="text-xs px-1.5 py-0.5 rounded font-medium" style="background: {{ $tag->color }}22; color: {{ $tag->color }}">{{ $tag->name }}</span>
                    @endforeach
                </div>
                @endif
                <div class="flex items-center justify-between">
                    @if($task->assignee)
                    <img src="{{ $task->assignee->avatar_url }}" class="w-6 h-6 rounded-full" title="{{ $task->assignee->name }}">
                    @else
                    <span class="text-xs text-slate-400">—</span>
                    @endif
                    @if($task->due_date)
                    <span class="text-xs {{ $task->is_overdue ? 'text-red-500 font-medium' : 'text-slate-400' }}">{{ $task->due_date->format('d.m') }}</span>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endforeach
</div>
@endsection
