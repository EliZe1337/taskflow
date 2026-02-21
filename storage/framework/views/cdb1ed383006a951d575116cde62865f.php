<?php $__env->startSection('title','Новая задача'); ?>
<?php $__env->startSection('header','Создать задачу'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <form method="POST" action="<?php echo e(route('tasks.store')); ?>" class="space-y-5">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Название *</label>
                <input type="text" name="title" value="<?php echo e(old('title')); ?>" required placeholder="Краткое описание задачи"
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <?php $__errorArgs = ['title'];
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
                <textarea name="description" rows="4" placeholder="Подробное описание, требования..."
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"><?php echo e(old('description')); ?></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Проект *</label>
                    <select name="project_id" required class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm">
                        <option value="">Выберите проект</option>
                        <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($project->id); ?>" <?php echo e(old('project_id',$selectedProject?->id)==$project->id ? 'selected' : ''); ?>><?php echo e($project->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Исполнитель</label>
                    <select name="assignee_id" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm">
                        <option value="">Не назначено</option>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($user->id); ?>" <?php echo e(old('assignee_id')==$user->id ? 'selected' : ''); ?>><?php echo e($user->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Статус</label>
                    <select name="status" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm">
                        <?php $__currentLoopData = ['todo'=>'К выполнению','in_progress'=>'В работе','review'=>'На проверке']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v=>$l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($v); ?>" <?php echo e(old('status','todo')===$v ? 'selected' : ''); ?>><?php echo e($l); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Приоритет</label>
                    <select name="priority" class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm">
                        <?php $__currentLoopData = ['low'=>'Низкий','medium'=>'Средний','high'=>'Высокий','critical'=>'Критический']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v=>$l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($v); ?>" <?php echo e(old('priority','medium')===$v ? 'selected' : ''); ?>><?php echo e($l); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Дедлайн</label>
                    <input type="date" name="due_date" value="<?php echo e(old('due_date')); ?>"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Оценка (часов)</label>
                    <input type="number" name="estimated_hours" value="<?php echo e(old('estimated_hours')); ?>" min="1" placeholder="8"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Теги</label>
                <div class="flex flex-wrap gap-2">
                    <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="cursor-pointer">
                        <input type="checkbox" name="tags[]" value="<?php echo e($tag->id); ?>" <?php echo e(in_array($tag->id, old('tags',[])) ? 'checked' : ''); ?> class="peer hidden">
                        <span class="inline-block text-xs font-medium px-3 py-1.5 rounded-full border-2 transition-all cursor-pointer
                            peer-checked:text-white"
                            style="border-color:<?php echo e($tag->color); ?>; color:<?php echo e($tag->color); ?>"
                            x-data x-bind:style="$el.previousElementSibling.checked ? 'background:<?php echo e($tag->color); ?>;color:white;border-color:<?php echo e($tag->color); ?>' : 'border-color:<?php echo e($tag->color); ?>;color:<?php echo e($tag->color); ?>'">
                            <?php echo e($tag->name); ?>

                        </span>
                    </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">Создать задачу</button>
                <a href="<?php echo e(route('tasks.index')); ?>" class="px-6 py-2.5 border border-slate-300 text-slate-600 text-sm rounded-lg hover:bg-slate-50">Отмена</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/tasks/create.blade.php ENDPATH**/ ?>