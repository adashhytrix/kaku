<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
'url' => null,
'header' => ''
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
'url' => null,
'header' => ''
]); ?>
<?php foreach (array_filter(([
'url' => null,
'header' => ''
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>
<?php
if (!Illuminate\Support\Str::contains($url, ['http://', 'https://'])) {
    if($url) {
        $url = route($url);
    }
}
?>
<div class="card shadow">
    <?php if($header): ?>
    <h2 class="card-header">
        <?php echo e($header); ?>

    </h2>
    <?php endif; ?>
    <div class="card-body">
        <div class="">
            <table lwDataTable <?php if($url): ?> data-url="<?php echo e($url); ?>" <?php endif; ?> <?php echo e($attributes->merge(['class' => 'table table-striped'])); ?>>
                <thead>
                    <tr>
                        <?php echo e($slot); ?>

                    </tr>
                </thead>
                <tbody ></tbody>
            </table>
        </div>
    </div>
</div><?php /**PATH /home/creden/api-kaku.jurysoft.in/resources/views/components/lw/datatable.blade.php ENDPATH**/ ?>