@extends('layouts.app')
@section('title', isset($user) ? 'Редактировать пользователя' : 'Новый пользователь')
@section('header', isset($user) ? 'Редактировать пользователя' : 'Добавить пользователя')
@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <form method="POST" action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}" class="space-y-5">
            @csrf
            @if(isset($user)) @method('PUT') @endif

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Имя *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Email *</label>
                    <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">
                    Пароль {{ isset($user) ? '(оставьте пустым чтобы не менять)' : '*' }}
                </label>
                <input type="password" name="password" {{ isset($user) ? '' : 'required' }}
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Должность</label>
                    <input type="text" name="position" value="{{ old('position', $user->position ?? '') }}" placeholder="PHP Developer"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Отдел</label>
                    <input type="text" name="department" value="{{ old('department', $user->department ?? '') }}" placeholder="Разработка"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Роль *</label>
                <select name="role" required class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @php $currentRole = isset($user) ? $user->roles->first()?->name : old('role', 'developer'); @endphp
                    @foreach(['developer' => 'Разработчик — работа с задачами', 'manager' => 'Менеджер — управление проектами', 'viewer' => 'Наблюдатель — только просмотр', 'admin' => 'Администратор — полный доступ'] as $val => $label)
                    <option value="{{ $val }}" {{ $currentRole === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            @if(isset($user))
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" id="is_active" {{ ($user->is_active ?? true) ? 'checked' : '' }} class="rounded">
                <label for="is_active" class="text-sm text-slate-700">Пользователь активен</label>
            </div>
            @endif

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                    {{ isset($user) ? 'Сохранить' : 'Создать пользователя' }}
                </button>
                <a href="{{ route('users.index') }}" class="px-6 py-2.5 border border-slate-300 text-slate-600 text-sm rounded-lg hover:bg-slate-50 transition">Отмена</a>
            </div>
        </form>
    </div>
</div>
@endsection
