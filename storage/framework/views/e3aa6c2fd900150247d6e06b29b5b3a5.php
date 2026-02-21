<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход — TaskFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 to-slate-800 flex items-center justify-center p-4">

<div class="w-full max-w-md">
    
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-14 h-14 bg-blue-600 rounded-2xl mb-4 shadow-lg">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-white">TaskFlow</h1>
        <p class="text-slate-400 text-sm mt-1">Корпоративная система управления задачами</p>
    </div>

    
    <div class="bg-white rounded-2xl shadow-2xl p-8">
        <h2 class="text-xl font-semibold text-slate-800 mb-6">Вход в систему</h2>

        <?php if($errors->any()): ?>
        <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
            <?php echo e($errors->first()); ?>

        </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-5">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                <input type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus
                    placeholder="your@company.ru"
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Пароль</label>
                <input type="password" name="password" required
                    placeholder="••••••••"
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="remember" id="remember"
                    class="w-4 h-4 text-blue-600 border-slate-300 rounded">
                <label for="remember" class="ml-2 text-sm text-slate-600">Запомнить меня</label>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white font-medium py-2.5 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                Войти
            </button>
        </form>

        
        <div class="mt-6 pt-5 border-t border-slate-100">
            <p class="text-xs text-slate-500 text-center mb-3">Тестовые аккаунты (пароль: <strong>password</strong>)</p>
            <div class="grid grid-cols-2 gap-2">
                <?php $__currentLoopData = [
                    ['Администратор', 'admin@taskflow.local', 'bg-red-100 text-red-700'],
                    ['Менеджер', 'manager@taskflow.local', 'bg-purple-100 text-purple-700'],
                    ['Разработчик', 'maria@taskflow.local', 'bg-blue-100 text-blue-700'],
                    ['Наблюдатель', 'viewer@taskflow.local', 'bg-slate-100 text-slate-700'],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$role, $email, $classes]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button onclick="fillLogin('<?php echo e($email); ?>')"
                    class="text-xs <?php echo e($classes); ?> px-3 py-2 rounded-lg font-medium text-left hover:opacity-80 transition">
                    <?php echo e($role); ?><br>
                    <span class="font-normal opacity-75 text-xs"><?php echo e($email); ?></span>
                </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>

<script>
function fillLogin(email) {
    document.querySelector('[name="email"]').value = email;
    document.querySelector('[name="password"]').value = 'password';
}
</script>
</body>
</html>
<?php /**PATH /var/www/resources/views/auth/login.blade.php ENDPATH**/ ?>