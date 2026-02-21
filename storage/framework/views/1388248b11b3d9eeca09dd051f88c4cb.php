<?php $__env->startSection('title', 'Дашборд'); ?>
<?php $__env->startSection('header', 'Дашборд'); ?>

<?php $__env->startSection('content'); ?>
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <div class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-3">Всего проектов</div>
        <div class="text-3xl font-bold text-slate-800"><?php echo e($stats['total_projects']); ?></div>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <div class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-3">Активных проектов</div>
        <div class="text-3xl font-bold text-emerald-600"><?php echo e($stats['active_projects']); ?></div>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <div class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-3">Мои задачи</div>
        <div class="text-3xl font-bold text-violet-600"><?php echo e($stats['my_tasks']); ?></div>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <div class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-3">Выполнено (неделя)</div>
        <div class="text-3xl font-bold text-amber-600"><?php echo e($stats['done_this_week']); ?></div>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <div class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-3">Просрочено</div>
        <div class="text-3xl font-bold <?php echo e($stats['overdue'] > 0 ? 'text-red-600' : 'text-slate-800'); ?>"><?php echo e($stats['overdue']); ?></div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200">
        <div class="flex items-center justify-between p-5 border-b border-slate-100">
            <h2 class="font-semibold text-slate-800">Мои задачи</h2>
            <a href="<?php echo e(route('tasks.index', ['my_tasks' => 1])); ?>" class="text-xs text-blue-600 font-medium">Все задачи →</a>
        </div>
        <div class="divide-y divide-slate-50">
            <?php $__empty_1 = true; $__currentLoopData = $myTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="flex items-center gap-3 p-4 hover:bg-slate-50 transition-colors">
                <div class="w-2 h-2 rounded-full flex-shrink-0 <?php echo e($task->priority === 'critical' ? 'bg-red-500' :
                    ($task->priority === 'high' ? 'bg-orange-500' :
                    ($task->priority === 'medium' ? 'bg-yellow-400' : 'bg-slate-300'))); ?>"></div>
                <div class="flex-1 min-w-0">
                    <a href="<?php echo e(route('tasks.show', $task)); ?>" class="text-sm font-medium text-slate-800 hover:text-blue-600 truncate block"><?php echo e($task->title); ?></a>
                    <span class="text-xs text-slate-400"><?php echo e($task->project->name); ?></span>
                </div>
                <span class="text-xs font-medium px-2 py-1 rounded-full flex-shrink-0 <?php echo e($task->status === 'in_progress' ? 'bg-blue-100 text-blue-700' :
                    ($task->status === 'review' ? 'bg-purple-100 text-purple-700' : 'bg-slate-100 text-slate-600')); ?>"><?php echo e($task->status_label); ?></span>
                <?php if($task->due_date): ?>
                <span class="text-xs flex-shrink-0 <?php echo e($task->is_overdue ? 'text-red-600 font-semibold' : 'text-slate-400'); ?>">
                    <?php echo e($task->due_date->format('d.m')); ?>

                </span>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="p-10 text-center text-slate-400 text-sm">Нет активных задач — хорошая работа!</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200">
        <div class="flex items-center justify-between p-5 border-b border-slate-100">
            <h2 class="font-semibold text-slate-800">Проекты</h2>
            <a href="<?php echo e(route('projects.index')); ?>" class="text-xs text-blue-600 font-medium">Все →</a>
        </div>
        <div class="divide-y divide-slate-50">
            <?php $__empty_1 = true; $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <a href="<?php echo e(route('projects.show', $project)); ?>" class="flex items-center gap-3 p-4 hover:bg-slate-50 transition-colors">
                <div class="w-3 h-3 rounded-full flex-shrink-0" style="background-color: <?php echo e($project->color); ?>"></div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-slate-800 truncate"><?php echo e($project->name); ?></div>
                    <div class="text-xs text-slate-400"><?php echo e($project->tasks_count); ?> задач</div>
                </div>
                <div class="text-xs text-slate-500 flex-shrink-0"><?php echo e($project->progress); ?>%</div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="p-8 text-center text-slate-400 text-sm">Нет проектов</div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/dashboard/index.blade.php ENDPATH**/ ?>