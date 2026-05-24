<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'eduSIGE'); ?> — <?php echo e(config('app.edusige.institucion', 'eduSIGE')); ?></title>

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <?php echo $__env->yieldPushContent('head'); ?>
</head>
<body class="h-full bg-carbon-100 font-sans text-carbon-950" x-data="sidebar()">

    
    <aside class="sidebar scrollbar-thin" :class="open ? 'translate-x-0' : '-translate-x-full'">

        
        <div class="flex items-center gap-3 px-5 py-5 border-b border-white/10">
            <div class="w-9 h-9 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 14l9-5-9-5-9 5 9 5z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-white font-bold text-sm leading-tight truncate"><?php echo e(config('app.name', 'eduSIGE')); ?></p>
                <p class="text-white/50 text-xs truncate"><?php echo e(config('app.edusige.institucion')); ?></p>
            </div>
        </div>

        
        <nav class="flex-1 overflow-y-auto py-4 scrollbar-thin">
            <?php echo $__env->yieldContent('sidebar-nav'); ?>
        </nav>

        
        <div class="border-t border-white/10 p-4" x-data="dropdown()">
            <button @click="toggle()" class="w-full flex items-center gap-3 text-left group">
                <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-xs font-semibold text-white">
                        <?php echo e(strtoupper(substr(auth()->user()->name, 0, 2))); ?>

                    </span>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-white truncate"><?php echo e(auth()->user()->name); ?></p>
                    <p class="text-xs text-slate-400 truncate"><?php echo e(auth()->user()->rolNombre()); ?></p>
                </div>
                <svg class="w-4 h-4 text-slate-400 flex-shrink-0 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="open" x-transition @click.outside="close()" class="mt-2">
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit"
                            class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-300
                                   hover:bg-white/15 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </div>
    </aside>

    
    <div class="transition-all duration-300" :class="open ? 'lg:pl-64' : 'pl-0'">

        
        <header class="sticky top-0 z-40 shadow-sm border-b border-white/10"
                style="background: linear-gradient(135deg, #1a0960 0%, #2d1590 60%, #4219bf 100%)">
            <div class="flex items-center gap-4 px-6 h-14">

                
                <button @click="toggle()"
                        class="p-1.5 rounded-lg text-white/70 hover:bg-white/15 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                
                <div class="flex-1 [&_.breadcrumb]:text-white/60 [&_.breadcrumb_a]:text-white/80
                            [&_.breadcrumb_a:hover]:text-white [&_.breadcrumb-separator]:text-white/30
                            [&_.text-carbon-700]:text-white">
                    <?php if (! empty(trim($__env->yieldContent('breadcrumb')))): ?>
                        <nav class="breadcrumb">
                            <?php echo $__env->yieldContent('breadcrumb'); ?>
                        </nav>
                    <?php endif; ?>
                </div>

                
                <div class="flex items-center gap-2">
                    <?php echo $__env->yieldContent('header-actions'); ?>
                </div>
            </div>
        </header>

        
        <main class="p-6">

            
            <?php if(session('success')): ?>
                <div x-data="flashAlert()" x-show="visible" x-transition
                     class="alert-success mb-5 animate-slide-in">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span><?php echo e(session('success')); ?></span>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div x-data="flashAlert()" x-show="visible" x-transition
                     class="alert-danger mb-5 animate-slide-in">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span><?php echo e(session('error')); ?></span>
                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="alert-danger mb-5 animate-slide-in">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <ul class="list-disc list-inside space-y-0.5">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\HP\Documents\GitHub\eduSIGE\resources\views/layouts/app.blade.php ENDPATH**/ ?>