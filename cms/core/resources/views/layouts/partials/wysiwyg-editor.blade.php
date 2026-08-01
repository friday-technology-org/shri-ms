{{--
    Reusable Quill WYSIWYG editor (free, no API key required — replaces TinyMCE).
    Props:
      name    - form field name to submit (e.g. "content" or "meta[banner_text]")
      fieldId - unique DOM id for the hidden textarea + editor container
      value   - initial HTML content
      height  - optional min-height for the editing area (default 400px)

    Requires Quill's CDN <script>/<link> to already be loaded (included once,
    synchronously, in layouts.admin) so it's available regardless of where in
    the page this partial is included.
--}}
@php
    $height = $height ?? '400px';
@endphp
<div class="rounded-lg border border-neutral dark:border-dark-neutral-border overflow-hidden"
     x-data="{
        initEditor() {
            const editorEl = this.$refs.quillContainer;
            const hiddenField = this.$refs.hiddenField;
            if(!editorEl || editorEl.dataset.initialized) return;
            editorEl.dataset.initialized = 'true';
            
            const quill = new Quill(editorEl, {
                theme: 'snow',
                modules: {
                    toolbar: {
                        container: [
                            [{ header: [1, 2, 3, false] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ list: 'ordered' }, { list: 'bullet' }],
                            ['blockquote', 'code-block'],
                            ['link', 'image'],
                            ['clean']
                        ],
                        handlers: {
                            image: function() {
                                if (!window.openMediaPicker) return;
                                window.openMediaPicker((media) => {
                                    const range = quill.getSelection(true);
                                    quill.insertEmbed(range.index, 'image', media.url, 'user');
                                    quill.setSelection(range.index + 1);
                                });
                            }
                        }
                    }
                }
            });
            quill.clipboard.dangerouslyPasteHTML(hiddenField.value);
            quill.on('text-change', () => {
                hiddenField.value = quill.root.innerHTML;
            });
            
            const form = hiddenField.closest('form');
            if (form) {
                form.addEventListener('submit', function() {
                    hiddenField.value = quill.root.innerHTML;
                });
            }
        }
     }"
     x-init="$nextTick(() => initEditor())">
    <div x-ref="quillContainer" style="min-height: {{ $height }};" class="bg-white"></div>
    <textarea name="{{ $name }}" id="{{ $fieldId }}" class="hidden" x-ref="hiddenField">{{ $value }}</textarea>
</div>
