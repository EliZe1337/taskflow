<?php $__env->startSection('title','Пользователи'); ?>
<?php $__env->startSection('header','Управление пользователями'); ?>
<?php $__env->startSection('content'); ?>
<div class="flex justify-end mb-5">
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\User::class)): ?>
    <a href="<?php echo e(route('users.create')); ?>" class="bg-blue-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-700">+ Добавить пользователя</a>
    <?php endif; ?>
</div>
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <table class="w-full">
        <thead><tr class="border-b border-slate-200 bg-slate-50">
            <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-600 uppercase">Пользователь</th>
            <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-600 uppercase">Должность</th>
            <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-600 uppercase">Роль</th>
            <th class="text-center px-5 py-3.5 text-xs font-semibold text-slate-600 uppercase">Задач</th>
            <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-600 uppercase">Статус</th>
            <th class="px-5"></th>
        </tr></thead>
        <tbody class="divide-y divide-slate-100">
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="hover:bg-slate-50">
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <img src="<?php echo e($user->avatar_url); ?>" class="w-9 h-9 rounded-full">
                        <div>
                            <div class="text-sm font-semibold text-slate-800"><?php echo e($user->name); ?></div>
                            <div class="text-xs text-slate-400"><?php echo e($user->email); ?></div>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-4">
                    <div class="text-sm text-slate-700"><?php echo e($user->position ?? '—'); ?></div>
                    <div class="text-xs text-slate-400"><?php echo e($user->department); ?></div>
                </td>
                <td class="px-5 py-4">
                    <?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="text-xs font-medium px-2.5 py-1 rounded-full <?php echo e($role->name==='admin' ? 'bg-red-100 text-red-700' :
                        ($role->name==='manager' ? 'bg-purple-100 text-purple-700' :
                        ($role->name==='developer' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600'))); ?>"><?php echo e(ucfirst($role->name)); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </td>
                <td class="px-5 py-4 text-center text-sm text-slate-700"><?php echo e($user->assigned_tasks_count); ?></td>
                <td class="px-5 py-4">
                    <?php if($user->is_active): ?>
                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700 bg-emerald-100 px-2.5 py-1 rounded-full"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Активен</span>
                    <?php else: ?>
                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full"><span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>Неактивен</span>
                    <?php endif; ?>
                </td>
                <td class="px-5 py-4 text-right">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $user)): ?>
                    <a href="<?php echo e(route('users.edit', $user)); ?>" class="text-xs text-blue-600 hover:text-blue-700 font-medium">Редактировать</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <div class="px-5 py-4 border-t border-slate-100"><?php echo e($users->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/users/index.blade.php ENDPATH**/ ?>