@extends('cms-core::layouts.admin')

@section('title', 'Customizer - Shri-ms')

@section('content')
<div>
    <div class="flex flex-col gap-y-2 mb-[36px]">
        <h2 class="capitalize text-gray-1100 font-bold text-[28px] leading-[35px] dark:text-gray-dark-1100">Customizer</h2>
        <div class="flex items-center text-xs gap-x-[11px]">
            <div class="flex items-center gap-x-1"><img src="{{ asset('assets/images/icons/icon-home-2.svg') }}" alt="home icon"><span class="capitalize text-gray-500 dark:text-gray-dark-500">Home</span></div>
            <img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon">
            <span class="capitalize text-color-brands">Appearance</span>
            <img src="{{ asset('assets/images/icons/icon-arrow-right.svg') }}" alt="arrow right icon">
            <span class="capitalize text-color-brands">Customizer</span>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif
    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
        <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
    @endif

    <div class="flex gap-5 flex-col xl:flex-row" x-data="customizerPreview()" @input="onFormInput">

        {{-- Left: Settings --}}
        <div class="xl:w-[30%] flex flex-col gap-5">

            {{-- Favicon: its own self-contained upload form, separate from the settings form above --}}
            <div class="border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl px-[25px] py-[25px]">
                <p class="text-gray-1100 leading-4 font-semibold dark:text-gray-dark-1100 text-[16px] mb-5">Favicon &amp; Site Icon</p>
                <p class="text-xs text-gray-400 dark:text-gray-dark-500 mb-4">Upload a square PNG/SVG (ideally 512×512) to generate the full favicon set.</p>

                @if(!empty($favicons))
                <div class="flex items-center gap-3 mb-4">
                    @foreach($favicons as $key => $url)
                        <img src="{{ $url }}" alt="{{ $key }}" class="w-8 h-8 rounded border border-[#E8EDF2] dark:border-[#313442] bg-white object-contain" title="{{ $key }}">
                    @endforeach
                </div>
                @endif

                <form action="{{ route('cms.customizer.favicon') }}" method="POST" enctype="multipart/form-data" 
                    x-data="{
                        fileName: '',
                        previewUrl: '',
                        onFileChange(e) {
                            if (e.target.files.length) {
                                this.fileName = e.target.files[0].name;
                                this.previewUrl = URL.createObjectURL(e.target.files[0]);
                            } else {
                                this.fileName = '';
                                this.previewUrl = '';
                            }
                        }
                    }">
                    @csrf
                    <input type="file" name="favicon_source" x-ref="fileInput" accept=".png,.jpg,.jpeg,.svg" required class="hidden" @change="onFileChange">
                    
                    <div class="flex items-center gap-3">
                        <div x-show="previewUrl" class="relative flex-shrink-0" style="display: none;">
                            <div class="w-14 h-14 rounded-lg overflow-hidden border border-[#E8EDF2] dark:border-[#313442] bg-gray-100 dark:bg-[#1f2130]">
                                <img :src="previewUrl" alt="Favicon Preview" class="w-full h-full object-contain">
                            </div>
                            <button type="button" @click="$refs.fileInput.value = ''; fileName = ''; previewUrl = '';" class="absolute -top-1.5 -right-1.5 w-5 h-5 flex items-center justify-center bg-red-500 hover:bg-red-600 text-white rounded-full shadow transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                        
                        <button type="button" @click="$refs.fileInput.click()" class="btn normal-case h-fit min-h-fit border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] border-neutral-bg dark:border-dark-neutral-bg text-white text-xs py-[6px] px-[12px]">
                            <span x-text="fileName ? 'Change' : 'Select Image'"></span>
                        </button>
                        
                        <button type="submit" class="btn normal-case h-fit min-h-fit border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] border-neutral-bg dark:border-dark-neutral-bg text-white text-xs py-[6px] px-[12px]">
                            Get favicon set
                        </button>
                    </div>
                </form>
            </div>

            <form action="{{ route('cms.customizer.update') }}" method="POST" class="flex flex-col gap-5">
                @csrf

                {{-- Logos --}}
                <div class="border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl px-[25px] py-[25px]">
                    <p class="text-gray-1100 leading-4 font-semibold dark:text-gray-dark-1100 text-[16px] mb-5">Site Branding &amp; Logos</p>

                    <div class="flex flex-col gap-5 mb-5">
                        @foreach([
                            'logo_header' => 'Header Logo',
                            'logo_header_dark' => 'Header Logo (Dark Mode)',
                            'logo_footer' => 'Footer Logo',
                            'logo_header_2x' => 'Retina Logo (@2x)',
                        ] as $field => $label)
                        <div x-data="{
                            imageId: {{ $settings[$field] ?? 'null' }},
                            imageUrl: {{ $settings[$field] ? "'" . addslashes(\Cms\Core\Models\Media::find($settings[$field])?->thumbnailUrl('medium') ?? '') . "'" : 'null' }},
                            pickImage() { window.openMediaPicker((media) => { this.imageId = media.id; this.imageUrl = media.url; }); },
                            clearImage() { this.imageId = null; this.imageUrl = null; }
                        }">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-dark-500 mb-2">{{ $label }}</p>
                            <input type="hidden" name="{{ $field }}" x-model="imageId">
                            <div class="flex items-center gap-3">
                                <div x-show="imageUrl" class="relative flex-shrink-0">
                                    <div class="w-14 h-14 rounded-lg overflow-hidden border border-[#E8EDF2] dark:border-[#313442] bg-gray-100 dark:bg-[#1f2130]">
                                        <img :src="imageUrl" alt="{{ $label }}" class="w-full h-full object-contain">
                                    </div>
                                    <button type="button" @click="clearImage()" class="absolute -top-1.5 -right-1.5 w-5 h-5 flex items-center justify-center bg-red-500 hover:bg-red-600 text-white rounded-full shadow transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                </div>
                                <button type="button" @click="pickImage()" class="btn normal-case h-fit min-h-fit border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] border-neutral-bg dark:border-dark-neutral-bg text-white text-xs py-[6px] px-[12px]">
                                    <span x-text="imageId ? 'Change' : 'Select Image'"></span>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-dark-500 mb-2">Header Logo Width: <span x-text="$refs.logoWidth.value"></span>px</p>
                        <input type="range" name="logo_width" x-ref="logoWidth" min="20" max="600" value="{{ $settings['logo_width'] }}" class="w-full">
                    </div>
                </div>

                {{-- Colors & Typography --}}
                <div class="border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl px-[25px] py-[25px]">
                    <p class="text-gray-1100 leading-4 font-semibold dark:text-gray-dark-1100 text-[16px] mb-5">Color Palette &amp; Typography</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-dark-500 mb-2">Primary Color</p>
                            <input type="color" name="color_primary" x-ref="colorPrimary" value="{{ $settings['color_primary'] }}" class="w-full h-10 rounded-lg border border-[#E8EDF2] dark:border-[#313442]">
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-dark-500 mb-2">Secondary Color</p>
                            <input type="color" name="color_secondary" x-ref="colorSecondary" value="{{ $settings['color_secondary'] }}" class="w-full h-10 rounded-lg border border-[#E8EDF2] dark:border-[#313442]">
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-dark-500 mb-2">Font</p>
                        <div class="input-group border rounded-lg border-[#E8EDF2] dark:border-[#313442]">
                            <select name="font" x-ref="font" class="select w-full bg-transparent text-sm text-gray-800 dark:text-white h-fit min-h-fit py-3 focus:outline-none px-[13px]">
                                @foreach($fonts as $font)
                                    <option value="{{ $font }}" class="bg-white dark:bg-dark-neutral-bg" {{ $settings['font'] === $font ? 'selected' : '' }}>{{ $font }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Social Media Links --}}
                <div class="border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl px-[25px] py-[25px]">
                    <p class="text-gray-1100 leading-4 font-semibold dark:text-gray-dark-1100 text-[16px] mb-5">Social Media Links</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        @foreach(['facebook' => 'Facebook', 'instagram' => 'Instagram', 'tiktok' => 'TikTok', 'linkedin' => 'LinkedIn', 'youtube' => 'YouTube'] as $key => $label)
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-dark-500 mb-2">{{ $label }} URL</p>
                            <input type="url" name="social_{{ $key }}" value="{{ $settings['social_' . $key] }}" class="input w-full bg-transparent text-sm text-gray-800 dark:text-white h-10 rounded-lg border border-[#E8EDF2] dark:border-[#313442] focus:outline-none px-[13px]" placeholder="https://...">
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Contact Information --}}
                <div class="border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl px-[25px] py-[25px]">
                    <p class="text-gray-1100 leading-4 font-semibold dark:text-gray-dark-1100 text-[16px] mb-5">Contact Information</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-dark-500 mb-2">Email Address</p>
                            <input type="email" name="contact_email" value="{{ $settings['contact_email'] }}" class="input w-full bg-transparent text-sm text-gray-800 dark:text-white h-10 rounded-lg border border-[#E8EDF2] dark:border-[#313442] focus:outline-none px-[13px]" placeholder="info@example.com">
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-dark-500 mb-2">Phone Number</p>
                            <input type="text" name="contact_phone" value="{{ $settings['contact_phone'] }}" class="input w-full bg-transparent text-sm text-gray-800 dark:text-white h-10 rounded-lg border border-[#E8EDF2] dark:border-[#313442] focus:outline-none px-[13px]" placeholder="+1 234 567 8900">
                        </div>
                    </div>
                    <div class="mb-5">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-dark-500 mb-2">Physical Address</p>
                        <input type="text" name="contact_address" value="{{ $settings['contact_address'] }}" class="input w-full bg-transparent text-sm text-gray-800 dark:text-white h-10 rounded-lg border border-[#E8EDF2] dark:border-[#313442] focus:outline-none px-[13px]" placeholder="123 Main St, City, Country">
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-dark-500 mb-2">Map Link (Google Maps URL)</p>
                        <input type="url" name="contact_map_link" value="{{ $settings['contact_map_link'] }}" class="input w-full bg-transparent text-sm text-gray-800 dark:text-white h-10 rounded-lg border border-[#E8EDF2] dark:border-[#313442] focus:outline-none px-[13px]" placeholder="https://maps.google.com/...">
                    </div>
                </div>

                {{-- Custom CSS / JS --}}
                <div class="border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl px-[25px] py-[25px]">
                    <p class="text-gray-1100 leading-4 font-semibold dark:text-gray-dark-1100 text-[16px] mb-5">Custom CSS &amp; JS</p>
                    <div class="mb-4">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-dark-500 mb-2">Custom CSS</p>
                        <textarea name="custom_css" rows="5" class="textarea w-full text-gray-800 dark:text-white resize-y rounded-lg bg-transparent border border-[#E8EDF2] dark:border-[#313442] p-3 text-sm font-mono focus:outline-none">{{ $settings['custom_css'] }}</textarea>
                    </div>
                    <div class="mb-4">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-dark-500 mb-2">Header JS (before &lt;/head&gt;)</p>
                        <textarea name="custom_js_header" rows="3" class="textarea w-full text-gray-800 dark:text-white resize-y rounded-lg bg-transparent border border-[#E8EDF2] dark:border-[#313442] p-3 text-sm font-mono focus:outline-none">{{ $settings['custom_js_header'] }}</textarea>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-dark-500 mb-2">Footer JS (before &lt;/body&gt;)</p>
                        <textarea name="custom_js_footer" rows="3" class="textarea w-full text-gray-800 dark:text-white resize-y rounded-lg bg-transparent border border-[#E8EDF2] dark:border-[#313442] p-3 text-sm font-mono focus:outline-none">{{ $settings['custom_js_footer'] }}</textarea>
                    </div>
                </div>

                <button type="submit" class="btn normal-case h-fit min-h-fit self-start transition-all duration-300 border-4 bg-color-brands hover:bg-color-brands hover:border-[#B2A7FF] border-neutral-bg dark:border-dark-neutral-bg font-medium py-[10px] px-[20px] text-sm text-white">Save Changes</button>
            </form>


        </div>

        {{-- Right: live iframe preview --}}
        <div class="xl:w-[70%]">
            <div class="border bg-neutral-bg border-neutral dark:bg-dark-neutral-bg dark:border-dark-neutral-border rounded-2xl overflow-hidden sticky top-5">
                <div class="bg-neutral py-[15px] pl-[18px] dark:bg-dark-neutral-border">
                    <p class="text-gray-1100 leading-4 font-semibold dark:text-gray-dark-1100 text-[14px]">Live Preview</p>
                </div>
                <iframe x-ref="previewFrame" src="{{ url('/') }}?cms_customizer_preview=1" class="w-full h-screen bg-white" style="min-height: 700px;"></iframe>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function customizerPreview() {
    return {
        onFormInput() {
            const frame = this.$refs.previewFrame;
            if (!frame || !frame.contentWindow) return;
            frame.contentWindow.postMessage({
                cmsCustomizerPreview: true,
                colorPrimary: this.$refs.colorPrimary?.value,
                colorSecondary: this.$refs.colorSecondary?.value,
                font: this.$refs.font?.value,
            }, '*');
        }
    };
}
</script>
@endpush
@endsection
