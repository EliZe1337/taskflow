<?php $__env->startSection('title', $task->title); ?>
<?php $__env->startSection('header', 'Задача #' . $task->id); ?>

<?php $__env->startSection('content'); ?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-5">
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <a href="<?php echo e(route('projects.show', $task->project)); ?>" class="text-xs text-blue-600 font-medium"><?php echo e($task->project->name); ?></a>
                        <?php if($task->parent): ?>
                        <span class="text-slate-300">›</span>
                        <a href="<?php echo e(route('tasks.show', $task->parent)); ?>" class="text-xs text-slate-500"><?php echo e($task->parent->title); ?></a>
                        <?php endif; ?>
                    </div>
                    <h1 class="text-xl font-bold text-slate-800"><?php echo e($task->title); ?></h1>
                </div>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $task)): ?>
                <div class="flex gap-2 flex-shrink-0">
                    <a href="<?php echo e(route('tasks.edit', $task)); ?>" class="px-3 py-1.5 border border-slate-300 text-sm rounded-lg hover:bg-slate-50">Редактировать</a>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $task)): ?>
                    <form method="POST" action="<?php echo e(route('tasks.destroy', $task)); ?>" onsubmit="return confirm('Удалить задачу?')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button class="px-3 py-1.5 border border-red-300 text-red-600 text-sm rounded-lg hover:bg-red-50">Удалить</button>
                    </form>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            <?php if($task->description): ?>
            <div class="text-sm text-slate-600 leading-relaxed mb-4 whitespace-pre-wrap"><?php echo e($task->description); ?></div>
            <?php endif; ?>
            <?php if($task->tags->count()): ?>
            <div class="flex flex-wrap gap-2">
                <?php $__currentLoopData = $task->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="text-xs font-medium px-2.5 py-1 rounded-full" style="background: <?php echo e($tag->color); ?>22; color: <?php echo e($tag->color); ?>"><?php echo e($tag->name); ?></span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>
        </div>

        <?php if($task->subtasks->count()): ?>
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <h3 class="font-semibold text-slate-800 mb-4">Подзадачи (<?php echo e($task->subtasks->count()); ?>)</h3>
            <div class="space-y-2">
                <?php $__currentLoopData = $task->subtasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-50">
                    <div class="w-4 h-4 rounded border-2 <?php echo e($sub->status==='done' ? 'bg-emerald-500 border-emerald-500' : 'border-slate-300'); ?> flex items-center justify-center">
                        <?php if($sub->status==='done'): ?> <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg> <?php endif; ?>
                    </div>
                    <a href="<?php echo e(route('tasks.show', $sub)); ?>" class="flex-1 text-sm <?php echo e($sub->status==='done' ? 'line-through text-slate-400' : 'text-slate-700 hover:text-blue-600'); ?>"><?php echo e($sub->title); ?></a>
                    <?php if($sub->assignee): ?> <img src="<?php echo e($sub->assignee->avatar_url); ?>" class="w-5 h-5 rounded-full" title="<?php echo e($sub->assignee->name); ?>"> <?php endif; ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <h3 class="font-semibold text-slate-800 mb-4">Комментарии (<?php echo e($task->comments->count()); ?>)</h3>
            <form method="POST" action="<?php echo e(route('comments.store', $task)); ?>" class="mb-5">
                <?php echo csrf_field(); ?>
                <div class="flex gap-3">
                    <img src="<?php echo e(auth()->user()->avatar_url); ?>" class="w-8 h-8 rounded-full flex-shrink-0">
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
                <?php $__empty_1 = true; $__currentLoopData = $task->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex gap-3">
                    <img src="<?php echo e($comment->user->avatar_url); ?>" class="w-8 h-8 rounded-full flex-shrink-0">
                    <div class="flex-1">
                        <div class="flex items-baseline gap-2 mb-1">
                            <span class="text-sm font-semibold text-slate-800"><?php echo e($comment->user->name); ?></span>
                            <span class="text-xs text-slate-400"><?php echo e($comment->created_at->diffForHumans()); ?></span>
                        </div>
                        <div class="text-sm text-slate-600 bg-slate-50 rounded-lg p-3"><?php echo e($comment->body); ?></div>
                        <?php if(auth()->id() === $comment->user_id || auth()->user()->hasRole('admin')): ?>
                        <form method="POST" action="<?php echo e(route('comments.destroy', $comment)); ?>" class="mt-1">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="text-xs text-red-400 hover:text-red-600">Удалить</button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-sm text-slate-400 text-center py-4">Нет комментариев</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <h3 class="text-sm font-semibold text-slate-700 mb-3">Статус</h3>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $task)): ?>
            <form method="POST" action="<?php echo e(route('tasks.update-status', $task)); ?>" id="statusForm">
                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                <select name="status" onchange="document.getElementById('statusForm').submit()"
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none">
                    <?php $__currentLoopData = ['todo' => 'К выполнению', 'in_progress' => 'В работе', 'review' => 'На проверке', 'done' => 'Выполнено', 'cancelled' => 'Отменено']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $lb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($val); ?>" <?php echo e($task->status===$val ? 'selected' : ''); ?>><?php echo e($lb); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </form>
            <?php else: ?>
            <span class="text-sm"><?php echo e($task->status_label); ?></span>
            <?php endif; ?>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-4 space-y-4">
            <h3 class="text-sm font-semibold text-slate-700">Детали</h3>
            <div>
                <div class="text-xs text-slate-500 mb-1">Приоритет</div>
                <span class="text-sm font-medium px-2.5 py-1 rounded-full
                    <?php echo e($task->priority==='critical' ? 'bg-red-100 text-red-700' :
                    ($task->priority==='high' ? 'bg-orange-100 text-orange-700' :
                    ($task->priority==='medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-slate-100 text-slate-600'))); ?>">
                    <?php echo e($task->priority_label); ?>

                </span>
            </div>
            <div>
                <div class="text-xs text-slate-500 mb-1">Исполнитель</div>
                <?php if($task->assignee): ?>
                <div class="flex items-center gap-2">
                    <img src="<?php echo e($task->assignee->avatar_url); ?>" class="w-7 h-7 rounded-full">
                    <div>
                        <div class="text-sm font-medium text-slate-800"><?php echo e($task->assignee->name); ?></div>
                        <div class="text-xs text-slate-400"><?php echo e($task->assignee->position); ?></div>
                    </div>
                </div>
                <?php else: ?>
                <span class="text-sm text-slate-400">Не назначено</span>
                <?php endif; ?>
            </div>
            <div>
                <div class="text-xs text-slate-500 mb-1">Создатель</div>
                <div class="flex items-center gap-2">
                    <img src="<?php echo e($task->creator->avatar_url); ?>" class="w-6 h-6 rounded-full">
                    <span class="text-sm text-slate-700"><?php echo e($task->creator->name); ?></span>
                </div>
            </div>
            <?php if($task->due_date): ?>
            <div>
                <div class="text-xs text-slate-500 mb-1">Дедлайн</div>
                <span class="text-sm <?php echo e($task->is_overdue ? 'text-red-600 font-semibold' : 'text-slate-700'); ?>">
                    <?php echo e($task->due_date->format('d.m.Y')); ?> <?php if($task->is_overdue): ?> (просрочено) <?php endif; ?>
                </span>
            </div>
            <?php endif; ?>
            <?php if($task->estimated_hours): ?>
            <div>
                <div class="text-xs text-slate-500 mb-1">Оценка / Факт</div>
                <span class="text-sm text-slate-700"><?php echo e($task->estimated_hours); ?>ч / <?php echo e($task->actual_hours ?? '—'); ?>ч</span>
            </div>
            <?php endif; ?>
            <div>
                <div class="text-xs text-slate-500 mb-1">Создано</div>
                <span class="text-sm text-slate-700"><?php echo e($task->created_at->format('d.m.Y H:i')); ?></span>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <h3 class="text-sm font-semibold text-slate-700 mb-3">История</h3>
            <div class="space-y-3">
                <?php $__empty_1 = true; $__currentLoopData = $task->activityLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex items-start gap-2">
                    <img src="<?php echo e($log->user->avatar_url ?? ''); ?>" class="w-5 h-5 rounded-full flex-shrink-0 mt-0.5">
                    <div>
                        <span class="text-xs text-slate-600"><?php echo e($log->user->name ?? 'Удалённый пользователь'); ?></span>
                        <div class="text-xs text-slate-400"><?php echo e($log->created_at?->diffForHumans() ?? ''); ?></div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-xs text-slate-400">Нет записей</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/tasks/show.blade.php ENDPATH**/ ?>