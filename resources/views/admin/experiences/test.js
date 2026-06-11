
function notionEditorEdit() {
    return {
        blocks: [],
        activeBlockId: null,
        isSubmitting: false,
        lastSaved: 'null',
        slashMenuOpen: false,
        slashQuery: '',
        slashBlockId: null,
        slashActiveIndex: 0,
        slashLeft: 0,
        slashTop: 0,
        dragId: null,
        dragOverId: null,
        fmtVisible: false,
        _isTransforming: false,

        // Media variables
        isDraggingThumb: false,
        thumbPreview: null,
        isDraggingBg: false,
        bgPreviewUrl: null,
        bgPreviewType: 'null',

        // Slideshow & cropper variables
        slides: [],
        cropper: null,
        cropModalOpen: false,
        cropTarget: null, // 'thumb', 'bg', 'slide'
        currentCropIndex: null,

        formatRelativeTime(iso) {
            if (!iso) return '';
            const d = new Date(iso);
            const diff = Math.floor((Date.now() - d) / 1000);
            if (diff < 10)    return 'just now';
            if (diff < 60)   return Math.floor(diff) + 's ago';
            if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
            if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
            return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        },

        blockTypes: [
            { type:'paragraph', label:'Text', desc:'Plain text block', icon:'Aa' },
            { type:'heading2', label:'Heading 2', desc:'Large section heading', icon:'H2' },
            { type:'heading3', label:'Heading 3', desc:'Small section heading', icon:'H3' },
            { type:'bullet', label:'Bullet List', desc:'Unordered list item', icon:'•' },
            { type:'numbered', label:'Numbered List', desc:'Ordered list item', icon:'1.' },
            { type:'quote', label:'Quote', desc:'Blockquote callout', icon:'❝' },
            { type:'code', label:'Code', desc:'Code snippet block', icon:'<>' },
            { type:'image', label:'Image', desc:'Upload or embed image', icon:'🖼' },
            { type:'video', label:'Video', desc:'Upload video or embed URL', icon:'▶' },
            { type:'divider', label:'Divider', desc:'Horizontal separator', icon:'—' },
        ],

        init() {
            this.loadContent();
            this.setupSlashMenu();
            this.setupFmtToolbar();
            this.initSlideshow();
            document.getElementById('block-image-upload').addEventListener('change', (e) => this.handleImageFile(e));
            document.getElementById('block-video-upload').addEventListener('change', (e) => this.handleVideoFile(e));
        },

        generateId() {
            return 'b_' + Math.random().toString(36).substr(2, 9);
        },

        loadContent() {
            let raw = null;
            if (!raw || (typeof raw === 'string' && raw.trim() === '')) {
                this.blocks = [{ id: this.generateId(), type: 'paragraph', content: '' }];
                return;
            }

            // If the model already cast it to an array
            if (Array.isArray(raw)) {
                this.blocks = raw.map(b => ({ ...b, id: b.id || this.generateId() }));
                return;
            }

            // Try JSON blocks
            if (typeof raw === 'string') {
                try {
                    let parsed = JSON.parse(raw);
                    if (Array.isArray(parsed)) {
                        this.blocks = parsed.map(b => ({ ...b, id: b.id || this.generateId() }));
                        return;
                    }
                } catch(e) {}

                // Legacy HTML — wrap in a single HTML paragraph block
                if (raw.trim().startsWith('<')) {
                    this.blocks = [{ id: this.generateId(), type: 'paragraph', content: raw }];
                } else {
                    this.blocks = [{ id: this.generateId(), type: 'paragraph', content: raw }];
                }
            } else {
                this.blocks = [{ id: this.generateId(), type: 'paragraph', content: '' }];
            }
        },

        getPlaceholder(type, index) {
            if (index === 0 && this.blocks.length === 1 && type === 'paragraph') {
                return "Type '/' for commands, or start writing...";
            }
            const map = {
                paragraph: "Type '/' for commands...",
                heading2: 'Heading 2',
                heading3: 'Heading 3',
                quote: 'Write a quote...',
                code: 'Paste code...',
                bullet: 'List item',
                numbered: 'List item',
            };
            return map[type] || '';
        },

        getNumberIndex(blockIndex) {
            let count = 0;
            for (let i = 0; i <= blockIndex; i++) {
                if (this.blocks[i].type === 'numbered') count++;
            }
            return count;
        },

        handleInput(block, event) {
            if (this._isTransforming) return;
            block.content = event.target.innerHTML;
            
            let text = this.getTextContent(event.target);
            if (text.startsWith('/')) {
                let query = text.substring(1);
                if (this.slashQuery !== query) {
                    this.slashActiveIndex = 0;
                }
                this.slashQuery = query;
                this.slashBlockId = block.id;
                this.openSlashMenu(event.target);
            } else if (this.slashMenuOpen) {
                this.closeSlashMenu();
            }
        },

        handleBlur(block, event) {
            if (this._isTransforming) return;
            // Don't save if the block is no longer in the array (was replaced)
            if (!this.blocks.find(b => b.id === block.id)) return;
            block.content = event.target.innerHTML;
        },

        handleKeydown(block, index, event) {
            if (this.slashMenuOpen) {
                if (event.key === 'ArrowDown') {
                    event.preventDefault();
                    this.slashActiveIndex = Math.min(this.slashActiveIndex + 1, this.filteredSlashItems().length - 1);
                    return;
                }
                if (event.key === 'ArrowUp') {
                    event.preventDefault();
                    this.slashActiveIndex = Math.max(this.slashActiveIndex - 1, 0);
                    return;
                }
                if (event.key === 'Enter') {
                    event.preventDefault();
                    let items = this.filteredSlashItems();
                    if (items[this.slashActiveIndex]) {
                        this.selectSlashItem(items[this.slashActiveIndex].type);
                        return;
                    }
                    this.closeSlashMenu();
                }
                if (event.key === 'Escape') {
                    this.closeSlashMenu();
                    return;
                }
            }

            if (event.key === 'ArrowUp' && !this.slashMenuOpen) {
                let sel = window.getSelection();
                if (sel.rangeCount > 0) {
                    let rects = sel.getRangeAt(0).getClientRects();
                    let cursorTop = rects.length > 0 ? rects[0].top : event.target.getBoundingClientRect().top;
                    if (cursorTop - event.target.getBoundingClientRect().top < 25) {
                        event.preventDefault();
                        if (index > 0) {
                            let prevEl = document.querySelector(`[data-block-id="${this.blocks[index-1].id}"]`);
                            if (prevEl && prevEl.isContentEditable) {
                                prevEl.focus();
                                this.setCursorEnd(prevEl);
                            }
                        }
                    }
                }
                return;
            }

            if (event.key === 'ArrowDown' && !this.slashMenuOpen) {
                let sel = window.getSelection();
                if (sel.rangeCount > 0) {
                    let rects = sel.getRangeAt(0).getClientRects();
                    let cursorBottom = rects.length > 0 ? rects[rects.length-1].bottom : event.target.getBoundingClientRect().bottom;
                    if (event.target.getBoundingClientRect().bottom - cursorBottom < 25) {
                        event.preventDefault();
                        if (index < this.blocks.length - 1) {
                            let nextEl = document.querySelector(`[data-block-id="${this.blocks[index+1].id}"]`);
                            if (nextEl && nextEl.isContentEditable) {
                                nextEl.focus();
                                let newRange = document.createRange();
                                newRange.selectNodeContents(nextEl);
                                newRange.collapse(true);
                                let newSel = window.getSelection();
                                newSel.removeAllRanges();
                                newSel.addRange(newRange);
                            }
                        }
                    }
                }
                return;
            }

            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                let sel = window.getSelection();
                let leftHtml = event.target.innerHTML;
                let rightHtml = '';

                if (sel.rangeCount > 0) {
                    let range = sel.getRangeAt(0);
                    let postCaretRange = range.cloneRange();
                    postCaretRange.selectNodeContents(event.target);
                    postCaretRange.setStart(range.endContainer, range.endOffset);
                    let postFrag = postCaretRange.extractContents();
                    let tmp = document.createElement('div');
                    tmp.appendChild(postFrag);
                    rightHtml = tmp.innerHTML;
                    leftHtml = event.target.innerHTML;
                }

                block.content = leftHtml;
                event.target.innerHTML = leftHtml;
                
                let newBlock = { id: this.generateId(), type: 'paragraph', content: rightHtml, _needsFocus: true };
                this.blocks.splice(index + 1, 0, newBlock);
                return;
            }

            if (event.key === 'Backspace' && this.blocks.length > 1) {
                let sel = window.getSelection();
                let isAtStart = false;
                if (sel.rangeCount > 0) {
                    let range = sel.getRangeAt(0);
                    let preCaretRange = range.cloneRange();
                    preCaretRange.selectNodeContents(event.target);
                    preCaretRange.setEnd(range.startContainer, range.startOffset);
                    isAtStart = preCaretRange.toString().length === 0;
                }

                if (isAtStart) {
                    event.preventDefault();
                    if (this.slashMenuOpen) this.closeSlashMenu();

                    if (index > 0) {
                        let prevBlock = this.blocks[index-1];
                        let currentHtml = event.target.innerHTML;
                        let textTypes = ['paragraph','heading2','heading3','quote','code','bullet','numbered'];
                        
                        if (textTypes.includes(prevBlock.type)) {
                            this.$nextTick(() => {
                                let prevEl = document.querySelector(`[data-block-id="${prevBlock.id}"]`);
                                if (prevEl) {
                                    prevEl.focus();
                                    let range = document.createRange();
                                    range.selectNodeContents(prevEl);
                                    range.collapse(false);
                                    let sel = window.getSelection();
                                    sel.removeAllRanges();
                                    sel.addRange(range);

                                    if (currentHtml !== '' && currentHtml !== '<br>') {
                                        let tmp = document.createElement('div');
                                        tmp.innerHTML = currentHtml;
                                        let frag = document.createDocumentFragment();
                                        while(tmp.firstChild) frag.appendChild(tmp.firstChild);
                                        range.insertNode(frag);
                                        prevBlock.content = prevEl.innerHTML;
                                    }
                                }
                            });
                            this.blocks.splice(index, 1);
                        } else {
                            if (this.getTextContent(event.target) === '') {
                                this.blocks.splice(index, 1);
                            }
                        }
                    }
                    return;
                }
            }

            if (event.key === ' ') {
                let text = this.getTextContent(event.target);
                const shortcuts = {
                    '##': 'heading2',
                    '###': 'heading3',
                    '-': 'bullet',
                    '*': 'bullet',
                    '1.': 'numbered',
                    '>': 'quote',
                    '```': 'code',
                    '---': 'divider',
                };
                if (shortcuts[text]) {
                    event.preventDefault();
                    block.type = shortcuts[text];
                    block.content = '';
                    this.$nextTick(() => {
                        let el = document.querySelector(`[data-block-id="${block.id}"]`);
                        if (el) {
                            el.innerHTML = '';
                            el.focus();
                        }
                    });
                    return;
                }
            }
        },

        handlePaste(block, event) {
            event.preventDefault();
            let text = event.clipboardData.getData('text/plain');
            if (!text) return;
            
            let lines = text.split(/\r?\n/).filter(line => line.trim() !== '');
            if (lines.length === 0) return;
            
            document.execCommand('insertText', false, lines[0]);
            
            if (lines.length > 1) {
                let idx = this.blocks.findIndex(b => b.id === block.id);
                let newBlocks = [];
                for (let i = 1; i < lines.length; i++) {
                    newBlocks.push({ id: this.generateId(), type: 'paragraph', content: lines[i], _needsFocus: i === lines.length - 1 });
                }
                this.blocks.splice(idx + 1, 0, ...newBlocks);
            }
        },

        getTextContent(el) {
            return (el.textContent || el.innerText || '').trim();
        },

        setCursorEnd(el) {
            let range = document.createRange();
            let sel = window.getSelection();
            range.selectNodeContents(el);
            range.collapse(false);
            sel.removeAllRanges();
            sel.addRange(range);
        },

        addBlockAtEnd() {
            let newBlock = { id: this.generateId(), type: 'paragraph', content: '', _needsFocus: true };
            this.blocks.push(newBlock);
        },

        // ── Slash command menu ──
        setupSlashMenu() {
            document.addEventListener('click', (e) => {
                if (this.slashMenuOpen && !e.target.closest('#slash-menu')) {
                    this.closeSlashMenu();
                }
            });
        },

        filteredSlashItems() {
            if (!this.slashQuery) return this.blockTypes;
            let q = this.slashQuery.toLowerCase();
            return this.blockTypes.filter(t =>
                t.label.toLowerCase().includes(q) ||
                t.desc.toLowerCase().includes(q) ||
                t.type.toLowerCase().includes(q)
            );
        },

        openSlashMenu(targetEl) {
            this.slashMenuOpen = true;
            let rect = targetEl.getBoundingClientRect();
            this.slashLeft = rect.left;
            this.slashTop = rect.bottom + 4;
        },

        selectSlashItem(type) {
            let idx = this.blocks.findIndex(b => b.id === this.slashBlockId);
            if (idx === -1) { this.closeSlashMenu(); return; }

            this._isTransforming = true;

            let block = this.blocks[idx];
            let el = document.querySelector(`[data-block-id="${block.id}"]`);
            let remainingText = '';
            if (el) {
                let rawText = this.getTextContent(el);
                let commandStr = '/' + this.slashQuery;
                if (rawText.startsWith(commandStr)) {
                    remainingText = rawText.substring(commandStr.length).trim();
                }
            }

            this.closeSlashMenu();
            this.slashBlockId = null;
            this.slashQuery = '';

            // Create a brand new block — NO _needsFocus to avoid x-effect re-render conflicts
            let newBlock = {
                id: this.generateId(),
                type: type,
                content: remainingText,
            };
            if (['image', 'video'].includes(type)) {
                newBlock.content = '';
                newBlock.src = '';
                newBlock.caption = '';
            }

            // Replace old block
            this.blocks.splice(idx, 1, newBlock);

            // Use setTimeout to let Alpine fully commit the DOM before we touch it
            setTimeout(() => {
                if (['paragraph','heading2','heading3','quote','code','bullet','numbered'].includes(type)) {
                    let newEl = document.querySelector(`[data-block-id="${newBlock.id}"]`);
                    if (newEl) {
                        newEl.focus();
                        if (newBlock.content) this.setCursorEnd(newEl);
                    }
                }
                if (type === 'image') {
                    this.triggerImageUpload(newBlock.id);
                }
                if (type === 'video') {
                    this.promptVideoUrl(newBlock.id);
                }
                // Clear transform flag after a brief delay to let all browser events settle
                setTimeout(() => { this._isTransforming = false; }, 50);
            }, 20);
        },

        closeSlashMenu() {
            this.slashMenuOpen = false;
        },

        // ── Block menu (on handle click) ──
        openBlockMenu(blockId, event) {
            let idx = this.blocks.findIndex(b => b.id === blockId);
            if (idx === -1) return;
            
            let actions = [
                'Delete block',
                'Duplicate block',
                '─',
                'Turn into Text',
                'Turn into Heading 2',
                'Turn into Heading 3',
                'Turn into Quote',
                'Turn into Bullet',
            ];
            // Simple prompt-based for now
            let choice = prompt('Block actions:\n1. Delete\n2. Duplicate\n3. → Text\n4. → Heading 2\n5. → Heading 3\n6. → Quote\n7. → Bullet\n\nEnter number:');
            if (!choice) return;
            let n = parseInt(choice);
            if (n === 1) { this.blocks.splice(idx, 1); if(this.blocks.length === 0) this.addBlockAtEnd(); }
            else if (n === 2) { this.blocks.splice(idx + 1, 0, { ...JSON.parse(JSON.stringify(this.blocks[idx])), id: this.generateId() }); }
            else if (n === 3) this.blocks[idx].type = 'paragraph';
            else if (n === 4) this.blocks[idx].type = 'heading2';
            else if (n === 5) this.blocks[idx].type = 'heading3';
            else if (n === 6) this.blocks[idx].type = 'quote';
            else if (n === 7) this.blocks[idx].type = 'bullet';
        },

        // ── Drag & Drop ──
        startDrag(id, event) {
            this.dragId = id;
            event.dataTransfer.effectAllowed = 'move';
        },
        endDrag() {
            this.dragId = null;
            this.dragOverId = null;
        },
        dropBlock(targetId) {
            if (!this.dragId || this.dragId === targetId) { this.endDrag(); return; }
            let fromIdx = this.blocks.findIndex(b => b.id === this.dragId);
            let toIdx = this.blocks.findIndex(b => b.id === targetId);
            if (fromIdx === -1 || toIdx === -1) { this.endDrag(); return; }
            let [moved] = this.blocks.splice(fromIdx, 1);
            this.blocks.splice(toIdx, 0, moved);
            this.endDrag();
        },

        // ── Image upload ──
        _pendingImageBlockId: null,
        triggerImageUpload(blockId) {
            this._pendingImageBlockId = blockId;
            document.getElementById('block-image-upload').click();
        },
        async handleImageFile(event) {
            let file = event.target.files[0];
            if (!file || !this._pendingImageBlockId) return;
            let block = this.blocks.find(b => b.id === this._pendingImageBlockId);
            if (!block) return;

            let formData = new FormData();
            formData.append('file', file);
            formData.append('_token', 'null');

            try {
                let resp = await fetch('null', {
                    method: 'POST', body: formData
                });
                let data = await resp.json();
                if (data.url) {
                    block.src = data.url;
                }
            } catch(err) {
                alert('Image upload failed.');
            }
            event.target.value = '';
            this._pendingImageBlockId = null;
        },

        // ── Video upload & embed ──
        _pendingVideoBlockId: null,
        triggerVideoUpload(blockId) {
            this._pendingVideoBlockId = blockId;
            document.getElementById('block-video-upload').click();
        },
        async handleVideoFile(event) {
            let file = event.target.files[0];
            if (!file || !this._pendingVideoBlockId) return;
            let block = this.blocks.find(b => b.id === this._pendingVideoBlockId);
            if (!block) return;

            let formData = new FormData();
            formData.append('file', file);
            formData.append('_token', 'null');

            try {
                let resp = await fetch('null', {
                    method: 'POST', body: formData
                });
                let data = await resp.json();
                if (data.url) {
                    block.src = data.url;
                }
            } catch(err) {
                alert('Video upload failed.');
            }
            event.target.value = '';
            this._pendingVideoBlockId = null;
        },
        promptVideoUrl(blockId) {
            let url = prompt('Enter YouTube or Vimeo URL:');
            if (!url) return;
            let block = this.blocks.find(b => b.id === blockId);
            if (block) block.src = url;
        },

        isEmbedUrl(url) {
            if (!url) return false;
            return /(?:youtube\.com\/watch\?v=|youtu\.be\/|vimeo\.com\/)/.test(url);
        },

        getEmbedUrl(url) {
            if (!url) return '';
            // YouTube
            let ytMatch = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\s]+)/);
            if (ytMatch) return 'https://www.youtube.com/embed/' + ytMatch[1];
            // Vimeo
            let vmMatch = url.match(/vimeo\.com\/(\d+)/);
            if (vmMatch) return 'https://player.vimeo.com/video/' + vmMatch[1];
            return url;
        },

        // ── Formatting toolbar ──
        setupFmtToolbar() {
            document.addEventListener('selectionchange', () => {
                let sel = window.getSelection();
                if (!sel || sel.isCollapsed || !sel.rangeCount) {
                    this.hideFmtToolbar();
                    return;
                }
                let range = sel.getRangeAt(0);
                let container = range.commonAncestorContainer;
                if (container.nodeType === 3) container = container.parentNode;
                if (!container.closest || !container.closest('.pe-block-content')) {
                    this.hideFmtToolbar();
                    return;
                }
                this.showFmtToolbar(range);
            });

            document.querySelectorAll('.pe-fmt-btn').forEach(btn => {
                btn.addEventListener('mousedown', (e) => {
                    e.preventDefault();
                    let fmt = btn.dataset.fmt;
                    if (fmt === 'bold') document.execCommand('bold');
                    else if (fmt === 'italic') document.execCommand('italic');
                    else if (fmt === 'strikethrough') document.execCommand('strikeThrough');
                    else if (fmt === 'link') {
                        let url = prompt('Enter URL:');
                        if (url) document.execCommand('createLink', false, url);
                    }
                });
            });
        },

        showFmtToolbar(range) {
            let toolbar = document.getElementById('fmt-toolbar');
            let rect = range.getBoundingClientRect();
            toolbar.style.left = (rect.left + rect.width / 2 - 80) + 'px';
            toolbar.style.top = (rect.top - 40) + 'px';
            toolbar.style.display = 'flex';
        },

        hideFmtToolbar() {
            document.getElementById('fmt-toolbar').style.display = 'none';
        },

        // ── Slideshow logic ──
        initSlideshow() {
            const existing = null;
            this.slides = existing.map((path, idx) => ({
                id: 'existing_' + idx,
                type: 'existing',
                path: path,
                url: 'null/' + path,
                name: path.split('/').pop()
            }));

            this.$nextTick(() => {
                const el = document.getElementById('slideshow-sortable');
                if (el && typeof Sortable !== 'undefined') {
                    new Sortable(el, {
                        animation: 150,
                        handle: '.slide-drag-handle',
                        onEnd: (evt) => {
                            const item = this.slides.splice(evt.oldIndex, 1)[0];
                            this.slides.splice(evt.newIndex, 0, item);
                        }
                    });
                }
            });
        },

        triggerSlideshowUpload() {
            document.getElementById('slideshow-upload-input').click();
        },

        handleSlideshowFile(event) {
            Array.from(event.target.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.slides.push({
                        id: 'new_' + Date.now() + Math.random(),
                        type: 'new',
                        url: e.target.result,
                        croppedBase64: e.target.result,
                        name: file.name
                    });
                };
                reader.readAsDataURL(file);
            });
            event.target.value = '';
        },

        removeSlide(index) {
            this.slides.splice(index, 1);
        },

        openCrop(target, index = null) {
            this.cropTarget = target;
            this.currentCropIndex = index;
            this.cropModalOpen = true;
            this.$nextTick(() => {
                const img = document.getElementById('cropper-image');
                let src = '';
                if (target === 'thumb') {
                    src = this.thumbPreview;
                } else if (target === 'bg') {
                    src = this.bgPreviewUrl;
                } else if (target === 'slide') {
                    src = this.slides[index].croppedBase64 || this.slides[index].url;
                }
                img.src = src;

                if (this.cropper) {
                    this.cropper.destroy();
                }
                this.cropper = new Cropper(img, {
                    viewMode: 2,
                });
            });
        },

        closeCrop() {
            this.cropModalOpen = false;
            this.cropTarget = null;
            this.currentCropIndex = null;
            if (this.cropper) {
                this.cropper.destroy();
                this.cropper = null;
            }
        },

        saveCrop() {
            if (!this.cropper) return;
            const canvas = this.cropper.getCroppedCanvas();
            const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
            
            if (this.cropTarget === 'thumb') {
                this.thumbPreview = dataUrl;
            } else if (this.cropTarget === 'bg') {
                this.bgPreviewUrl = dataUrl;
                this.bgPreviewType = 'image';
            } else if (this.cropTarget === 'slide') {
                const slide = this.slides[this.currentCropIndex];
                slide.url = dataUrl;
                slide.croppedBase64 = dataUrl;
            }
            
            this.closeCrop();
        },

        // ── Form submission ──
        submitForm(event) {
            this.isSubmitting = true;
            this.lastSaved = new Date().toISOString();

            // Sync block contents from DOM
            this.blocks.forEach(block => {
                if (['paragraph','heading2','heading3','quote','code','bullet','numbered'].includes(block.type)) {
                    let el = document.querySelector(`.pe-block-content[data-block-id="${block.id}"]`);
                    if (el) block.content = el.innerHTML;
                }
            });

            // Serialize blocks to JSON
            let cleanBlocks = this.blocks.map(b => {
                let clean = { id: b.id, type: b.type };
                if (b.content !== undefined) clean.content = b.content;
                if (b.src) clean.src = b.src;
                if (b.caption !== undefined && b.caption !== '') clean.caption = b.caption;
                if (b.level) clean.level = b.level;
                if (b.ratio) clean.ratio = b.ratio;
                if (b.posX !== undefined) clean.posX = b.posX;
                if (b.posY !== undefined) clean.posY = b.posY;
                return clean;
            });

            document.getElementById('body_content').value = JSON.stringify(cleanBlocks);

            // Populate slideshow payload
            let slideshowData = this.slides.map(s => {
                if (s.type === 'existing') {
                    if (s.croppedBase64) {
                        return { type: 'new', data: s.croppedBase64 };
                    }
                    return { type: 'existing', path: s.path };
                }
                return { type: 'new', data: s.croppedBase64 };
            });
            const reorderedInput = document.getElementById('reordered_bg_gallery');
            if (reorderedInput) {
                reorderedInput.value = JSON.stringify(slideshowData);
            }

            // Submit the form natively
            event.target.submit();
        }
    };
}
