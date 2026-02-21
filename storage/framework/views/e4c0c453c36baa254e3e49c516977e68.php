<?php $__env->startSection('title', 'Новый проект'); ?>
<?php $__env->startSection('header', 'Создать проект'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <form method="POST" action="<?php echo e(route('projects.store')); ?>" class="space-y-5">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Название <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="<?php echo e(old('name')); ?>" required placeholder="Название проекта"
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Описание</label>
                <textarea name="description" rows="3" placeholder="Цель и описание проекта"
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"><?php echo e(old('description')); ?></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Статус</label>
                    <select name="status" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none">
                        <option value="active">Активный</option>
                        <option value="on_hold">На паузе</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Цвет</label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="color" value="<?php echo e(old('color', '#3B82F6')); ?>" class="w-10 h-10 rounded cursor-pointer border border-slate-300">
                        <span class="text-xs text-slate-500">Цвет для идентификации проекта</span>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Начало</label>
                    <input type="date" name="start_date" value="<?php echo e(old('start_date')); ?>"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Дедлайн</label>
                    <input type="date" name="due_date" value="<?php echo e(old('due_date')); ?>"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Участники команды</label>
                <div class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto border border-slate-200 rounded-lg p-3">
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="flex items-center gap-2 cursor-pointer p-2 rounded hover:bg-slate-50">
                        <input type="checkbox" name="members[]" value="<?php echo e($user->id); ?>" class="rounded">
                        <img src="<?php echo e($user->avatar_url); ?>" class="w-6 h-6 rounded-full">
                        <div>
                            <div class="text-xs font-medium text-slate-700"><?php echo e($user->name); ?></div>
                            <div class="text-xs text-slate-400"><?php echo e($user->position); ?></div>
                        </div>
                    </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">Создать проект</button>
                <a href="<?php echo e(route('projects.index')); ?>" class="px-6 py-2.5 border border-slate-300 text-slate-600 text-sm rounded-lg hover:bg-slate-50 transition">Отмена</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/projects/create.blade.php ENDPATH**/ ?>