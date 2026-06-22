<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

for($i=0; $i<50; $i++) {
    \App\Models\Interaction::create(['type' => 'page_view', 'created_at' => now()->subDays(rand(0, 29))]);
}
\App\Models\Interaction::create(['type' => 'cv_download']);
\App\Models\Interaction::create(['type' => 'project_view', 'meta_data' => ['title' => 'Test Project']]);
\App\Models\Interaction::create(['type' => 'project_view', 'meta_data' => ['title' => 'Test Project']]);
\App\Models\Interaction::create(['type' => 'project_view', 'meta_data' => ['title' => 'Another Project']]);

echo "Seeded interactions.\n";
