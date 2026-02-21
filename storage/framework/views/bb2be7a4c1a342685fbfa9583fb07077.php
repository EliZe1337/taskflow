<?php $__env->startSection('title', 'Заметки'); ?>
<?php $__env->startSection('header', 'Мои заметки'); ?>

    <?php $__env->startSection('content'); ?>
        <div class="flex items-center justify-between mb-6">
            <div>
                <p class="text-sm text-slate-500">Всего заметок: <?php echo e($notes->count()); ?></p>
            </div>
            <a href="/notes/create"
               class="bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Новая заметка
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php $__empty_1 = true; $__currentLoopData = $notes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="/notes/<?php echo e($note->id); ?>"
                   class="group bg-white rounded-xl border border-slate-200 p-5 hover:shadow-md hover:border-slate-300 transition-all">
                    <div class="flex items-start justify-between mb-3">
                        <div class="w-9 h-9 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-slate-800 group-hover:text-blue-600 transition-colors truncate"><?php echo e($note->title); ?></h3>
                    <?php if($note->body): ?>
                        <p class="text-xs text-slate-400 mt-1 line-clamp-2"><?php echo e(Str::limit($note->body, 80)); ?></p>
                    <?php endif; ?>
                    <?php if($note->updated_at): ?>
                        <p class="text-xs text-slate-300 mt-3"><?php echo e($note->updated_at->diffForHumans()); ?></p>
                    <?php endif; ?>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full">
                    <div class="bg-white rounded-xl border border-slate-200 p-12 text-center">
                        <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-slate-600 mb-1">Заметок пока нет</h3>
                        <p class="text-xs text-slate-400 mb-4">Создайте первую заметку чтобы начать</p>
                        <a href="/notes/create"
                           class="inline-flex items-center gap-2 bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Создать заметку
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/notes/index.blade.php ENDPATH**/ ?>