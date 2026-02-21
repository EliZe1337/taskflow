@extends('layouts.app')
@section('title', 'Проекты')
@section('header', 'Проекты')

@section('content')
<div class="flex items-center justify-between mb-6">
    <form class="flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Поиск..." class="px-4 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <select name="status" class="px-3 py-2 border border-slate-300 rounded-lg text-sm">
            <option value="">Все статусы</option>
            <option value="active" {{ request('status')=='active' ? 'selected' : '' }}>Активные</option>
            <option value="on_hold" {{ request('status')=='on_hold' ? 'selected' : '' }}>На паузе</option>
            <option value="completed" {{ request('status')=='completed' ? 'selected' : '' }}>Завершённые</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-slate-800 text-white text-sm rounded-lg">Найти</button>
    </form>
    @can('create', App\Models\Project::class)
    <a href="{{ route('projects.create') }}" class="bg-blue-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-700 transition">+ Новый проект</a>
    @endcan
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
    @forelse($projects as $project)
    <div class="bg-white rounded-xl border border-slate-200 hover:shadow-md transition overflow-hidden">
        <div class="h-1.5" style="background: {{ $project->color }}"></div>
        <div class="p-5">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <a href="{{ route('projects.show', $project) }}" class="font-semibold text-slate-800 hover:text-blue-600">{{ $project->name }}</a>
                    <div class="text-xs text-slate-400 mt-0.5">{{ $project->owner->name }}</div>
                </div>
                <span class="text-xs px-2.5 py-1 rounded-full font-medium
                    {{ $project->status==='active' ? 'bg-emerald-100 text-emerald-700' :
                    ($project->status==='on_hold' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600') }}">
                    {{ $project->status_label }}
                </span>
            </div>
            @if($project->description)
            <p class="text-sm text-slate-500 mb-4 line-clamp-2">{{ $project->description }}</p>
            @endif
            <div class="mb-4">
                <div class="flex justify-between text-xs text-slate-500 mb-1.5">
                    <span>Прогресс</span><span>{{ $project->progress }}%</span>
                </div>
                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full" style="width: {{ $project->progress }}%; background: {{ $project->color }}"></div>
                </div>
            </div>
            <div class="flex items-center justify-between text-xs text-slate-500">
                <span>{{ $project->tasks_count }} задач</span>
                @if($project->due_date)
                <span class="{{ $project->due_date->isPast() && $project->status !== 'completed' ? 'text-red-500 font-medium' : '' }}">до {{ $project->due_date->format('d.m.Y') }}</span>
                @endif
            </div>
            <div class="flex items-center gap-1 mt-4 pt-4 border-t border-slate-100">
                @foreach($project->members->take(5) as $member)
                <img src="{{ $member->avatar_url }}" title="{{ $member->name }}" class="w-7 h-7 rounded-full border-2 border-white -ml-1 first:ml-0">
                @endforeach
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-3 bg-white rounded-xl border p-16 text-center text-slate-400">
        <p class="text-lg mb-3">📁</p>
        <p class="font-medium">Проектов нет</p>
        @can('create', App\Models\Project::class)
        <a href="{{ route('projects.create') }}" class="mt-4 inline-block bg-blue-600 text-white text-sm px-4 py-2 rounded-lg">Создать проект</a>
        @endcan
    </div>
    @endforelse
</div>
<div class="mt-5">{{ $projects->links() }}</div>
@endsection
