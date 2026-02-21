@extends('layouts.app')
@section('title','Новая задача')
@section('header','Создать задачу')
@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <form method="POST" action="{{ route('tasks.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Название *</label>
                <input type="text" name="title" value="{{ old('title') }}" required placeholder="Краткое описание задачи"
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Описание</label>
                <textarea name="description" rows="4" placeholder="Подробное описание, требования..."
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('description') }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Проект *</label>
                    <select name="project_id" required class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm">
                        <option value="">Выберите проект</option>
                        @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ old('project_id',$selectedProject?->id)==$project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Исполнитель</label>
                    <select name="assignee_id" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm">
                        <option value="">Не назначено</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('assignee_id')==$user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Статус</label>
                    <select name="status" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm">
                        @foreach(['todo'=>'К выполнению','in_progress'=>'В работе','review'=>'На проверке'] as $v=>$l)
                        <option value="{{ $v }}" {{ old('status','todo')===$v ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Приоритет</label>
                    <select name="priority" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm">
                        @foreach(['low'=>'Низкий','medium'=>'Средний','high'=>'Высокий','critical'=>'Критический'] as $v=>$l)
                        <option value="{{ $v }}" {{ old('priority','medium')===$v ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Дедлайн</label>
                    <input type="date" name="due_date" value="{{ old('due_date') }}"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Оценка (часов)</label>
                    <input type="number" name="estimated_hours" value="{{ old('estimated_hours') }}" min="1" placeholder="8"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Теги</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($tags as $tag)
                    <label class="cursor-pointer">
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}" {{ in_array($tag->id, old('tags',[])) ? 'checked' : '' }} class="peer hidden">
                        <span class="inline-block text-xs font-medium px-3 py-1.5 rounded-full border-2 transition-all cursor-pointer
                            peer-checked:text-white"
                            style="border-color:{{ $tag->color }}; color:{{ $tag->color }}"
                            x-data x-bind:style="$el.previousElementSibling.checked ? 'background:{{ $tag->color }};color:white;border-color:{{ $tag->color }}' : 'border-color:{{ $tag->color }};color:{{ $tag->color }}'">
                            {{ $tag->name }}
                        </span>
                    </label>
                    @endforeach
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">Создать задачу</button>
                <a href="{{ route('tasks.index') }}" class="px-6 py-2.5 border border-slate-300 text-slate-600 text-sm rounded-lg hover:bg-slate-50">Отмена</a>
            </div>
        </form>
    </div>
</div>
@endsection
