<?php
$output = shell_exec('git ls-tree -r -l HEAD');
$lines = explode("\n", trim($output));
$files = [];
foreach ($lines as $line) {
    if (preg_match('/^\d+\s+blob\s+[a-f0-9]+\s+(\d+)\s+(.+)$/', $line, $matches)) {
        $files[$matches[2]] = (int)$matches[1];
    }
}
arsort($files);
$top = array_slice($files, 0, 20);
foreach ($top as $file => $size) {
    echo round($size / 1024 / 1024, 2) . " MB - $file\n";
}
