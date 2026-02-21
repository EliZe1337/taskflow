@extends('layouts.app')
@section('title','Пользователи')
@section('header','Управление пользователями')
@section('content')
<div class="flex justify-end mb-5">
    @can('create', App\Models\User::class)
    <a href="{{ route('users.create') }}" class="bg-blue-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-700">+ Добавить пользователя</a>
    @endcan
</div>
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <table class="w-full">
        <thead><tr class="border-b border-slate-200 bg-slate-50">
            <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-600 uppercase">Пользователь</th>
            <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-600 uppercase">Должность</th>
            <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-600 uppercase">Роль</th>
            <th class="text-center px-5 py-3.5 text-xs font-semibold text-slate-600 uppercase">Задач</th>
            <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-600 uppercase">Статус</th>
            <th class="px-5"></th>
        </tr></thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($users as $user)
            <tr class="hover:bg-slate-50">
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ $user->avatar_url }}" class="w-9 h-9 rounded-full">
                        <div>
                            <div class="text-sm font-semibold text-slate-800">{{ $user->name }}</div>
                            <div class="text-xs text-slate-400">{{ $user->email }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-4">
                    <div class="text-sm text-slate-700">{{ $user->position ?? '—' }}</div>
                    <div class="text-xs text-slate-400">{{ $user->department }}</div>
                </td>
                <td class="px-5 py-4">
                    @foreach($user->roles as $role)
                    <span class="text-xs font-medium px-2.5 py-1 rounded-full {{
                        $role->name==='admin' ? 'bg-red-100 text-red-700' :
                        ($role->name==='manager' ? 'bg-purple-100 text-purple-700' :
                        ($role->name==='developer' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600'))
                    }}">{{ ucfirst($role->name) }}</span>
                    @endforeach
                </td>
                <td class="px-5 py-4 text-center text-sm text-slate-700">{{ $user->assigned_tasks_count }}</td>
                <td class="px-5 py-4">
                    @if($user->is_active)
                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700 bg-emerald-100 px-2.5 py-1 rounded-full"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Активен</span>
                    @else
                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full"><span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>Неактивен</span>
                    @endif
                </td>
                <td class="px-5 py-4 text-right">
                    @can('update', $user)
                    <a href="{{ route('users.edit', $user) }}" class="text-xs text-blue-600 hover:text-blue-700 font-medium">Редактировать</a>
                    @endcan
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-5 py-4 border-t border-slate-100">{{ $users->links() }}</div>
</div>
@endsection
