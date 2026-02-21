<?php $__env->startSection('title', $note->title); ?>
<?php $__env->startSection('header', 'Редактирование заметки'); ?>

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

        <form method="POST" action="/notes/<?php echo e($note->id); ?>" id="noteForm">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                <div class="p-5 border-b border-slate-100">
                    <label for="title" class="block text-xs font-medium text-slate-500 uppercase tracking-wide mb-2">Заголовок</label>
                    <input type="text"
                           id="title"
                           name="title"
                           value="<?php echo e($note->title); ?>"
                           class="w-full text-lg font-semibold text-slate-800 placeholder-slate-300 border-0 p-0 focus:ring-0 focus:outline-none bg-transparent">
                </div>

                <div class="p-5">
                    <label for="body" class="block text-xs font-medium text-slate-500 uppercase tracking-wide mb-2">Содержимое</label>
                    <textarea id="body"
                              name="body"
                              rows="16"
                              placeholder="Начните писать..."
                              class="w-full text-sm text-slate-700 placeholder-slate-300 border-0 p-0 focus:ring-0 focus:outline-none bg-transparent resize-none leading-relaxed"><?php echo e($note->body); ?></textarea>
                </div>
            </div>

            <div class="flex items-center justify-between mt-4">
                <div class="flex items-center gap-3">
                    <button type="submit"
                            class="bg-blue-600 text-white text-sm font-medium px-6 py-2.5 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Сохранить
                    </button>
                    <span class="text-xs text-slate-400">Ctrl + Enter</span>
                </div>

                <div class="text-xs text-slate-400">
                    Создано: <?php echo e($note->created_at->format('d.m.Y в H:i')); ?>

                    <?php if($note->updated_at != $note->created_at): ?>
                        · Изменено: <?php echo e($note->updated_at->format('d.m.Y в H:i')); ?>

                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                document.getElementById('noteForm').submit();
            }
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/notes/show.blade.php ENDPATH**/ ?>