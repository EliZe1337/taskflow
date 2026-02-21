<?php $__env->startSection('title', 'Новая заметка'); ?>
<?php $__env->startSection('header', 'Новая заметка'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-3xl">
        <a href="/notes" class="inline-flex items-center gap-1 text-sm text-slate-400 hover:text-slate-600 transition-colors mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Назад к заметкам
        </a>

        <?php if($errors->any()): ?>
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <p class="text-sm text-red-600"><?php echo e($error); ?></p>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/notes">
            <?php echo csrf_field(); ?>

            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                <div class="p-5 border-b border-slate-100">
                    <label for="title" class="block text-xs font-medium text-slate-500 uppercase tracking-wide mb-2">Заголовок</label>
                    <input type="text"
                           id="title"
                           name="title"
                           value="<?php echo e(old('title')); ?>"
                           placeholder="Название заметки"
                           class="w-full text-lg font-semibold text-slate-800 placeholder-slate-300 border-0 p-0 focus:ring-0 focus:outline-none bg-transparent">
                </div>

                <div class="p-5">
                    <label for="body" class="block text-xs font-medium text-slate-500 uppercase tracking-wide mb-2">Содержимое</label>
                    <textarea id="body"
                              name="body"
                              rows="14"
                              placeholder="Начните писать..."
                              class="w-full text-sm text-slate-700 placeholder-slate-300 border-0 p-0 focus:ring-0 focus:outline-none bg-transparent resize-none leading-relaxed"><?php echo e(old('body')); ?></textarea>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-4">
                <button type="submit"
                        class="bg-blue-600 text-white text-sm font-medium px-6 py-2.5 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Создать заметку
                </button>
                <a href="/notes" class="text-sm text-slate-400 hover:text-slate-600 transition-colors">Отмена</a>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/notes/create.blade.php ENDPATH**/ ?>