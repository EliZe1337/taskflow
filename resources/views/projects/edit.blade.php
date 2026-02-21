@extends('layouts.app')
@section('title', 'Редактировать проект')
@section('header', 'Редактировать: ' . $project->name)
@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <form method="POST" action="{{ route('projects.update', $project) }}" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Название *</label>
                <input type="text" name="name" value="{{ old('name', $project->name) }}" required
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Описание</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('description', $project->description) }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Статус</label>
                    <select name="status" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm">
                        @foreach(['active' => 'Активный', 'on_hold' => 'На паузе', 'completed' => 'Завершён', 'archived' => 'Архив'] as $val => $label)
                        <option value="{{ $val }}" {{ old('status', $project->status) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Цвет</label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="color" value="{{ old('color', $project->color) }}" class="w-10 h-10 rounded cursor-pointer border border-slate-300">
                        <span class="text-xs text-slate-500">Цвет в боковом меню</span>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Начало</label>
                    <input type="date" name="start_date" value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Дедлайн</label>
                    <input type="date" name="due_date" value="{{ old('due_date', $project->due_date?->format('Y-m-d')) }}"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Участники</label>
                <div class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto border border-slate-200 rounded-lg p-3">
                    @foreach($users as $user)
                    <label class="flex items-center gap-2 cursor-pointer p-2 rounded hover:bg-slate-50">
                        <input type="checkbox" name="members[]" value="{{ $user->id }}"
                            {{ $project->members->contains($user->id) ? 'checked' : '' }} class="rounded">
                        <img src="{{ $user->avatar_url }}" class="w-6 h-6 rounded-full">
                        <div>
                            <div class="text-xs font-medium text-slate-700">{{ $user->name }}</div>
                            <div class="text-xs text-slate-400">{{ $user->position }}</div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">Сохранить изменения</button>
                <a href="{{ route('projects.show', $project) }}" class="px-6 py-2.5 border border-slate-300 text-slate-600 text-sm rounded-lg hover:bg-slate-50">Отмена</a>
            </div>
        </form>
    </div>
</div>
@endsection
