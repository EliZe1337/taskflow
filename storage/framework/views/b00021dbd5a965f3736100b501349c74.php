<?php $__env->startSection('title', 'Проекты'); ?>
<?php $__env->startSection('header', 'Проекты'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex items-center justify-between mb-6">
    <form class="flex gap-3">
        <input type="text" name="search" value="<?php echo e(request('search')); ?>"
               placeholder="Поиск..." class="px-4 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <select name="status" class="px-3 py-2 border border-slate-300 rounded-lg text-sm">
            <option value="">Все статусы</option>
            <option value="active" <?php echo e(request('status')=='active' ? 'selected' : ''); ?>>Активные</option>
            <option value="on_hold" <?php echo e(request('status')=='on_hold' ? 'selected' : ''); ?>>На паузе</option>
            <option value="completed" <?php echo e(request('status')=='completed' ? 'selected' : ''); ?>>Завершённые</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-slate-800 text-white text-sm rounded-lg">Найти</button>
    </form>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Project::class)): ?>
    <a href="<?php echo e(route('projects.create')); ?>" class="bg-blue-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-700 transition">+ Новый проект</a>
    <?php endif; ?>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
    <?php $__empty_1 = true; $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="bg-white rounded-xl border border-slate-200 hover:shadow-md transition overflow-hidden">
        <div class="h-1.5" style="background: <?php echo e($project->color); ?>"></div>
        <div class="p-5">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <a href="<?php echo e(route('projects.show', $project)); ?>" class="font-semibold text-slate-800 hover:text-blue-600"><?php echo e($project->name); ?></a>
                    <div class="text-xs text-slate-400 mt-0.5"><?php echo e($project->owner->name); ?></div>
                </div>
                <span class="text-xs px-2.5 py-1 rounded-full font-medium
                    <?php echo e($project->status==='active' ? 'bg-emerald-100 text-emerald-700' :
                    ($project->status==='on_hold' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600')); ?>">
                    <?php echo e($project->status_label); ?>

                </span>
            </div>
            <?php if($project->description): ?>
            <p class="text-sm text-slate-500 mb-4 line-clamp-2"><?php echo e($project->description); ?></p>
            <?php endif; ?>
            <div class="mb-4">
                <div class="flex justify-between text-xs text-slate-500 mb-1.5">
                    <span>Прогресс</span><span><?php echo e($project->progress); ?>%</span>
                </div>
                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full" style="width: <?php echo e($project->progress); ?>%; background: <?php echo e($project->color); ?>"></div>
                </div>
            </div>
            <div class="flex items-center justify-between text-xs text-slate-500">
                <span><?php echo e($project->tasks_count); ?> задач</span>
                <?php if($project->due_date): ?>
                <span class="<?php echo e($project->due_date->isPast() && $project->status !== 'completed' ? 'text-red-500 font-medium' : ''); ?>">до <?php echo e($project->due_date->format('d.m.Y')); ?></span>
                <?php endif; ?>
            </div>
            <div class="flex items-center gap-1 mt-4 pt-4 border-t border-slate-100">
                <?php $__currentLoopData = $project->members->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <img src="<?php echo e($member->avatar_url); ?>" title="<?php echo e($member->name); ?>" class="w-7 h-7 rounded-full border-2 border-white -ml-1 first:ml-0">
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="col-span-3 bg-white rounded-xl border p-16 text-center text-slate-400">
        <p class="text-lg mb-3">📁</p>
        <p class="font-medium">Проектов нет</p>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Project::class)): ?>
        <a href="<?php echo e(route('projects.create')); ?>" class="mt-4 inline-block bg-blue-600 text-white text-sm px-4 py-2 rounded-lg">Создать проект</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
<div class="mt-5"><?php echo e($projects->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/projects/index.blade.php ENDPATH**/ ?>