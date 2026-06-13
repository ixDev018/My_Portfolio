<?php
$files = [
    'resources/views/admin/projects/edit.blade.php',
    'resources/views/admin/projects/create.blade.php',
    'resources/views/admin/experiences/edit.blade.php',
    'resources/views/admin/experiences/create.blade.php'
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);

    // Image HTML Replace
    $search1 = '<div class="pe-block-image-upload"
                                         @click="triggerImageUpload(block.id)">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span>Click to upload image</span>
                                    </div>';
    
    $replace1 = '<div class="pe-block-image-upload"
                                         @click="!block.isUploading && triggerImageUpload(block.id)"
                                         :style="block.isUploading ? \'opacity:0.7; pointer-events:none;\' : \'\'">
                                        <svg x-show="!block.isUploading" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <svg x-show="block.isUploading" class="animate-spin" style="width:1.5rem; height:1.5rem; color:#6829AA;" fill="none" viewBox="0 0 24 24" x-cloak><circle style="opacity:0.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path style="opacity:0.75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        <span x-text="block.isUploading ? \'Uploading Image...\' : \'Click to upload image\'"></span>
                                    </div>';

    // Video HTML Replace
    $search2 = '<div class="pe-block-image-upload"
                                             @click="triggerVideoUpload(block.id)">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                            <span>Upload your own video file</span>
                                        </div>';
    
    $replace2 = '<div class="pe-block-image-upload"
                                             @click="!block.isUploading && triggerVideoUpload(block.id)"
                                             :style="block.isUploading ? \'opacity:0.7; pointer-events:none;\' : \'\'">
                                            <svg x-show="!block.isUploading" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                            <svg x-show="block.isUploading" class="animate-spin" style="width:1.5rem; height:1.5rem; color:#6829AA;" fill="none" viewBox="0 0 24 24" x-cloak><circle style="opacity:0.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path style="opacity:0.75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            <span x-text="block.isUploading ? \'Uploading Video... Please wait.\' : \'Upload your own video file\'"></span>
                                        </div>';

    // JS Image Upload Replace
    $search3 = '            let formData = new FormData();
            formData.append(\'file\', file);
            formData.append(\'_token\', \'{{ csrf_token() }}\');

            try {';
    $replace3 = '            let formData = new FormData();
            formData.append(\'file\', file);
            formData.append(\'_token\', \'{{ csrf_token() }}\');

            block.isUploading = true;

            try {';

    // JS Error Replace
    $search4 = '            } catch(err) {
                alert(\'Image upload failed.\');
            }
            event.target.value = \'\';
            this._pendingImageBlockId = null;';
    $replace4 = '            } catch(err) {
                alert(\'Image upload failed.\');
            } finally {
                block.isUploading = false;
            }
            event.target.value = \'\';
            this._pendingImageBlockId = null;';

    // JS Video Error Replace
    $search5 = '            } catch(err) {
                alert(\'Video upload failed.\');
            }
            event.target.value = \'\';
            this._pendingVideoBlockId = null;';
    $replace5 = '            } catch(err) {
                alert(\'Video upload failed.\');
            } finally {
                block.isUploading = false;
            }
            event.target.value = \'\';
            this._pendingVideoBlockId = null;';

    $content = str_replace($search1, $replace1, $content);
    $content = str_replace($search2, $replace2, $content);
    $content = str_replace($search3, $replace3, $content); // Notice: this will replace both image and video blocks since they have identical formData setup
    $content = str_replace($search4, $replace4, $content);
    $content = str_replace($search5, $replace5, $content);

    file_put_contents($file, $content);
}
echo "Updated files successfully.";
