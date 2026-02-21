@extends('layouts.app')
@section('title', 'Редактировать задачу')
@section('header', 'Редактировать задачу')
@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <form method="POST" action="{{ route('tasks.update', $task) }}" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Название *</label>
                <input type="text" name="title" value="{{ old('title', $task->title) }}" required
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Описание</label>
                <textarea name="description" rows="4" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('description', $task->description) }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Исполнитель</label>
                    <select name="assignee_id" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm">
                        <option value="">Не назначено</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('assignee_id', $task->assignee_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Приоритет</label>
                    <select name="priority" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm">
                        @foreach(['low' => 'Низкий', 'medium' => 'Средний', 'high' => 'Высокий', 'critical' => 'Критический'] as $val => $label)
                        <option value="{{ $val }}" {{ old('priority', $task->priority) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Статус</label>
                    <select name="status" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm">
                        @foreach(['todo' => 'К выполнению', 'in_progress' => 'В работе', 'review' => 'На проверке', 'done' => 'Выполнено', 'cancelled' => 'Отменено'] as $val => $label)
                        <option value="{{ $val }}" {{ old('status', $task->status) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Дедлайн</label>
                    <input type="date" name="due_date" value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Оценка (часов)</label>
                    <input type="number" name="estimated_hours" value="{{ old('estimated_hours', $task->estimated_hours) }}" min="1"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Фактически (часов)</label>
                    <input type="number" name="actual_hours" value="{{ old('actual_hours', $task->actual_hours) }}" min="0"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Теги</label>
                <div class="flex flex-wrap gap-2">
                    @php $selectedTags = old('tags', $task->tags->pluck('id')->toArray()); @endphp
                    @foreach($tags as $tag)
                    <label class="cursor-pointer">
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}" {{ in_array($tag->id, $selectedTags) ? 'checked' : '' }} class="peer hidden">
                        <span class="inline-block text-xs font-medium px-3 py-1.5 rounded-full border-2 transition-all"
                            style="border-color: {{ $tag->color }}; color: {{ $tag->color }}"
                            onclick="this.parentElement.querySelector('input').checked ? (this.style.background='{{ $tag->color }}', this.style.color='white') : (this.style.background='', this.style.color='{{ $tag->color }}')">
                            {{ $tag->name }}
                        </span>
                    </label>
                    @endforeach
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">Сохранить</button>
                <a href="{{ route('tasks.show', $task) }}" class="px-6 py-2.5 border border-slate-300 text-slate-600 text-sm rounded-lg hover:bg-slate-50">Отмена</a>
            </div>
        </form>
    </div>
</div>

<script>
// Init tag colors on page load
document.querySelectorAll('[name="tags[]"]').forEach(cb => {
    const span = cb.nextElementSibling;
    const color = span.style.borderColor;
    if (cb.checked) { span.style.background = color; span.style.color = 'white'; }
    cb.addEventListener('change', () => {
        span.style.background = cb.checked ? color : '';
        span.style.color = cb.checked ? 'white' : color;
    });
});
</script>
@endsection
