<?php $__env->startSection('title', $project->name); ?>
<?php $__env->startSection('header', $project->name); ?>

<?php $__env->startSection('content'); ?>
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        <div class="w-4 h-4 rounded-full" style="background: <?php echo e($project->color); ?>"></div>
        <span class="text-sm px-3 py-1 rounded-full font-medium
            <?php echo e($project->status==='active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600'); ?>">
            <?php echo e($project->status_label); ?>

        </span>
    </div>
    <div class="flex gap-2">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $project)): ?>
        <a href="<?php echo e(route('projects.edit', $project)); ?>" class="px-4 py-2 border border-slate-300 text-sm rounded-lg hover:bg-slate-50">Редактировать</a>
        <?php endif; ?>
        <a href="<?php echo e(route('tasks.create', ['project_id' => $project->id])); ?>" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">+ Задача</a>
    </div>
</div>

<div class="grid grid-cols-5 gap-4 mb-6">
    <?php $__currentLoopData = ['todo' => ['К выполнению','text-slate-700'], 'in_progress' => ['В работе','text-blue-600'], 'review' => ['На проверке','text-purple-600'], 'done' => ['Выполнено','text-emerald-600']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st => [$lb, $cl]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="bg-white rounded-xl border border-slate-200 p-4 text-center">
        <div class="text-2xl font-bold <?php echo e($cl); ?>"><?php echo e($stats[$st]); ?></div>
        <div class="text-xs text-slate-500 mt-1"><?php echo e($lb); ?></div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <div class="bg-white rounded-xl border border-slate-200 p-4 text-center">
        <div class="text-2xl font-bold text-slate-800"><?php echo e($stats['total'] > 0 ? round($stats['done'] / $stats['total'] * 100) : 0); ?>%</div>
        <div class="text-xs text-slate-500 mt-1">Прогресс</div>
    </div>
</div>


<div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
    <?php $__currentLoopData = ['todo' => 'К выполнению', 'in_progress' => 'В работе', 'review' => 'На проверке', 'done' => 'Выполнено']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="bg-slate-100 rounded-xl p-3">
        <div class="flex items-center justify-between mb-3 px-1">
            <span class="text-sm font-semibold text-slate-700"><?php echo e($label); ?></span>
            <span class="bg-slate-200 text-slate-600 text-xs font-medium px-2 py-0.5 rounded-full"><?php echo e(isset($tasks[$status]) ? $tasks[$status]->count() : 0); ?></span>
        </div>
        <div class="space-y-2 min-h-20">
            <?php $__currentLoopData = $tasks[$status] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('tasks.show', $task)); ?>" class="block bg-white rounded-lg p-3 shadow-sm hover:shadow-md transition border border-slate-200">
                <div class="flex items-start justify-between gap-2 mb-2">
                    <span class="text-sm font-medium text-slate-800 leading-snug"><?php echo e($task->title); ?></span>
                    <span class="w-2 h-2 rounded-full flex-shrink-0 mt-1
                        <?php echo e($task->priority==='critical' ? 'bg-red-500' : ($task->priority==='high' ? 'bg-orange-500' : ($task->priority==='medium' ? 'bg-yellow-400' : 'bg-slate-300'))); ?>"></span>
                </div>
                <?php if($task->tags->count()): ?>
                <div class="flex flex-wrap gap-1 mb-2">
                    <?php $__currentLoopData = $task->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="text-xs px-1.5 py-0.5 rounded font-medium" style="background: <?php echo e($tag->color); ?>22; color: <?php echo e($tag->color); ?>"><?php echo e($tag->name); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>
                <div class="flex items-center justify-between">
                    <?php if($task->assignee): ?>
                    <img src="<?php echo e($task->assignee->avatar_url); ?>" class="w-6 h-6 rounded-full" title="<?php echo e($task->assignee->name); ?>">
                    <?php else: ?>
                    <span class="text-xs text-slate-400">—</span>
                    <?php endif; ?>
                    <?php if($task->due_date): ?>
                    <span class="text-xs <?php echo e($task->is_overdue ? 'text-red-500 font-medium' : 'text-slate-400'); ?>"><?php echo e($task->due_date->format('d.m')); ?></span>
                    <?php endif; ?>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/projects/show.blade.php ENDPATH**/ ?>