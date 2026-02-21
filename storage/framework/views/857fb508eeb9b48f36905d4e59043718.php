<?php $__env->startSection('title','Задачи'); ?>
<?php $__env->startSection('header','Все задачи'); ?>
<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-xl border border-slate-200 p-4 mb-6">
    <form class="flex flex-wrap gap-3">
        <select name="project_id" class="px-3 py-2 border border-slate-300 rounded-lg text-sm">
            <option value="">Все проекты</option>
            <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($p->id); ?>" <?php echo e(request('project_id')==$p->id ? 'selected' : ''); ?>><?php echo e($p->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <select name="status" class="px-3 py-2 border border-slate-300 rounded-lg text-sm">
            <option value="">Все статусы</option>
            <?php $__currentLoopData = ['todo'=>'К выполнению','in_progress'=>'В работе','review'=>'На проверке','done'=>'Выполнено','cancelled'=>'Отменено']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v=>$l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($v); ?>" <?php echo e(request('status')==$v ? 'selected' : ''); ?>><?php echo e($l); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <select name="priority" class="px-3 py-2 border border-slate-300 rounded-lg text-sm">
            <option value="">Все приоритеты</option>
            <?php $__currentLoopData = ['critical'=>'Критический','high'=>'Высокий','medium'=>'Средний','low'=>'Низкий']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v=>$l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($v); ?>" <?php echo e(request('priority')==$v ? 'selected' : ''); ?>><?php echo e($l); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
            <input type="checkbox" name="my_tasks" value="1" <?php echo e(request('my_tasks') ? 'checked' : ''); ?> class="rounded"> Только мои
        </label>
        <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
            <input type="checkbox" name="overdue" value="1" <?php echo e(request('overdue') ? 'checked' : ''); ?> class="rounded"> Просроченные
        </label>
        <button type="submit" class="px-4 py-2 bg-slate-800 text-white text-sm rounded-lg">Применить</button>
        <a href="<?php echo e(route('tasks.index')); ?>" class="px-4 py-2 border border-slate-300 text-slate-600 text-sm rounded-lg hover:bg-slate-50">Сбросить</a>
    </form>
</div>
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="divide-y divide-slate-100">
        <?php $__empty_1 = true; $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="flex items-center gap-4 p-4 hover:bg-slate-50 transition-colors">
            <div class="w-1 h-10 rounded-full flex-shrink-0 <?php echo e($task->priority==='critical' ? 'bg-red-500' :
                ($task->priority==='high' ? 'bg-orange-500' :
                ($task->priority==='medium' ? 'bg-yellow-400' : 'bg-slate-200'))); ?>"></div>
            <div class="flex-1 min-w-0">
                <a href="<?php echo e(route('tasks.show', $task)); ?>" class="text-sm font-medium text-slate-800 hover:text-blue-600 truncate block"><?php echo e($task->title); ?></a>
                <div class="flex items-center gap-2 mt-0.5">
                    <div class="w-2 h-2 rounded-full" style="background: <?php echo e($task->project->color); ?>"></div>
                    <span class="text-xs text-slate-400"><?php echo e($task->project->name); ?></span>
                    <?php $__currentLoopData = $task->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="text-xs px-1.5 py-0.5 rounded" style="background: <?php echo e($tag->color); ?>22; color: <?php echo e($tag->color); ?>"><?php echo e($tag->name); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php if($task->assignee): ?>
            <div class="flex items-center gap-2 flex-shrink-0">
                <img src="<?php echo e($task->assignee->avatar_url); ?>" class="w-6 h-6 rounded-full">
                <span class="text-xs text-slate-600 hidden md:block"><?php echo e($task->assignee->name); ?></span>
            </div>
            <?php endif; ?>
            <span class="text-xs font-medium px-2.5 py-1 rounded-full flex-shrink-0 <?php echo e($task->status==='done' ? 'bg-emerald-100 text-emerald-700' :
                ($task->status==='in_progress' ? 'bg-blue-100 text-blue-700' :
                ($task->status==='review' ? 'bg-purple-100 text-purple-700' :
                ($task->status==='cancelled' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-600')))); ?>"><?php echo e($task->status_label); ?></span>
            <?php if($task->due_date): ?>
            <span class="text-xs flex-shrink-0 <?php echo e($task->is_overdue ? 'text-red-600 font-semibold' : 'text-slate-400'); ?>"><?php echo e($task->due_date->format('d.m.y')); ?></span>
            <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="p-16 text-center text-slate-400"><p class="text-3xl mb-3">🔍</p><p>Задач не найдено</p></div>
        <?php endif; ?>
    </div>
</div>
<div class="mt-4"><?php echo e($tasks->appends(request()->query())->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/tasks/index.blade.php ENDPATH**/ ?>