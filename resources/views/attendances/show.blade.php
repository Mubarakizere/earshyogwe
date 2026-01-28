<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Attendance Details') }}
            </h2>
            <div class="flex gap-2">
                @can('edit attendance')
                <a href="{{ route('attendances.edit', $attendance) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit
                </a>
                @endcan
                <a href="{{ route('attendances.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Header Card -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl shadow-xl p-6 mb-8 text-white">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="h-16 w-16 rounded-full bg-white/20 flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold">{{ $attendance->attendance_date->format('F d, Y') }}</h3>
                            <p class="text-blue-100 text-lg">{{ $attendance->church->name }}</p>
                            <p class="text-blue-200 text-sm">{{ $attendance->attendance_date->format('l') }}</p>
                        </div>
                    </div>
                    <div class="mt-4 md:mt-0 text-center md:text-right">
                        <div class="text-6xl font-extrabold">{{ number_format($attendance->total_count) }}</div>
                        <p class="text-blue-100 text-sm">Total Attendance</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Demographic Cards -->
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Men</p>
                            <p class="text-3xl font-bold text-gray-900">{{ number_format($attendance->men_count) }}</p>
                        </div>
                        <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-pink-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Women</p>
                            <p class="text-3xl font-bold text-gray-900">{{ number_format($attendance->women_count) }}</p>
                        </div>
                        <div class="h-12 w-12 rounded-full bg-pink-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Children</p>
                            <p class="text-3xl font-bold text-gray-900">{{ number_format($attendance->children_count) }}</p>
                        </div>
                        <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Service Information -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b">
                        <h4 class="text-lg font-semibold text-gray-800">Service Information</h4>
                    </div>
                    <div class="p-6">
                        <dl class="space-y-4">
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Service Type</dt>
                                <dd class="text-sm font-semibold text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $attendance->serviceType->name ?? 'N/A' }}
                                    </span>
                                </dd>
                            </div>
                            @if($attendance->service_name)
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Service Name</dt>
                                <dd class="text-sm text-gray-900">{{ $attendance->service_name }}</dd>
                            </div>
                            @endif
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Recorded By</dt>
                                <dd class="text-sm text-gray-900">{{ $attendance->recorder->name ?? 'Unknown' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Recorded On</dt>
                                <dd class="text-sm text-gray-500">{{ $attendance->created_at->format('M d, Y h:i A') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Notes -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b">
                        <h4 class="text-lg font-semibold text-gray-800">Notes</h4>
                    </div>
                    <div class="p-6">
                        @if($attendance->notes)
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $attendance->notes }}</p>
                        @else
                            <p class="text-gray-400 italic">No notes recorded for this attendance.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Attached Documents -->
            @if($attendance->documents && $attendance->documents->count() > 0)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b flex items-center justify-between">
                    <h4 class="text-lg font-semibold text-gray-800">
                        <svg class="w-5 h-5 inline-block mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                        </svg>
                        Attached Documents
                    </h4>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-200 text-gray-800">
                        {{ $attendance->documents->count() }} {{ Str::plural('file', $attendance->documents->count()) }}
                    </span>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($attendance->documents as $index => $document)
                        <div class="group relative border rounded-xl overflow-hidden bg-gray-50 hover:shadow-lg transition-all duration-300 cursor-pointer" onclick="openDocumentViewer({{ $index }})">
                            @if($document->is_image)
                                <div class="aspect-square overflow-hidden">
                                    <img src="{{ $document->url }}" alt="{{ $document->original_name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                </div>
                            @else
                                <div class="aspect-square flex items-center justify-center bg-gradient-to-br from-red-50 to-red-100">
                                    <div class="text-center">
                                        <svg class="w-16 h-16 text-red-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-xs font-bold text-red-600 mt-2 block">PDF</span>
                                    </div>
                                </div>
                            @endif
                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                <div class="bg-white rounded-full p-3 shadow-lg">
                                    <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="p-3 border-t bg-white">
                                <p class="text-xs text-gray-600 truncate font-medium" title="{{ $document->original_name }}">{{ $document->original_name }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>

    <!-- Document Viewer Modal -->
    @if($attendance->documents && $attendance->documents->count() > 0)
    <div id="documentViewer" class="fixed inset-0 z-50 hidden">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/90" onclick="closeDocumentViewer()"></div>
        
        <!-- Modal Content -->
        <div class="absolute inset-0 flex flex-col">
            <!-- Header -->
            <div class="flex items-center justify-between p-4 text-white">
                <div class="flex items-center space-x-4">
                    <span id="docTitle" class="text-lg font-medium"></span>
                    <span id="docCounter" class="text-sm text-gray-400"></span>
                </div>
                <div class="flex items-center space-x-2">
                    <a id="downloadBtn" href="#" download class="p-2 rounded-full hover:bg-white/20 transition" title="Download">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                    </a>
                    <a id="openNewTab" href="#" target="_blank" class="p-2 rounded-full hover:bg-white/20 transition" title="Open in new tab">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </a>
                    <button onclick="closeDocumentViewer()" class="p-2 rounded-full hover:bg-white/20 transition" title="Close">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Document Display -->
            <div class="flex-1 flex items-center justify-center p-4 relative">
                <!-- Previous Button -->
                <button id="prevBtn" onclick="navigateDoc(-1)" class="absolute left-4 p-3 rounded-full bg-white/20 hover:bg-white/40 text-white transition z-10">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>

                <!-- Content Container -->
                <div id="docContent" class="max-w-5xl max-h-full flex items-center justify-center">
                    <!-- Will be populated by JS -->
                </div>

                <!-- Next Button -->
                <button id="nextBtn" onclick="navigateDoc(1)" class="absolute right-4 p-3 rounded-full bg-white/20 hover:bg-white/40 text-white transition z-10">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    @php
        $documentsJson = $attendance->documents->map(function($doc) {
            return [
                'url' => $doc->url,
                'name' => $doc->original_name,
                'is_image' => $doc->is_image,
                'is_pdf' => $doc->is_pdf
            ];
        })->values();
    @endphp

    <script>
        const documents = @json($documentsJson);
        
        let currentIndex = 0;

        function openDocumentViewer(index) {
            currentIndex = index;
            showDocument();
            document.getElementById('documentViewer').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDocumentViewer() {
            document.getElementById('documentViewer').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function navigateDoc(direction) {
            currentIndex += direction;
            if (currentIndex < 0) currentIndex = documents.length - 1;
            if (currentIndex >= documents.length) currentIndex = 0;
            showDocument();
        }

        function showDocument() {
            const doc = documents[currentIndex];
            const content = document.getElementById('docContent');
            const counter = document.getElementById('docCounter');
            const title = document.getElementById('docTitle');
            const downloadBtn = document.getElementById('downloadBtn');
            const openNewTab = document.getElementById('openNewTab');

            title.textContent = doc.name;
            counter.textContent = `${currentIndex + 1} of ${documents.length}`;
            downloadBtn.href = doc.url;
            openNewTab.href = doc.url;

            // Show/hide navigation if only one document
            document.getElementById('prevBtn').style.display = documents.length > 1 ? 'block' : 'none';
            document.getElementById('nextBtn').style.display = documents.length > 1 ? 'block' : 'none';

            if (doc.is_image) {
                content.innerHTML = `<img src="${doc.url}" alt="${doc.name}" class="max-h-[80vh] max-w-full rounded-lg shadow-2xl">`;
            } else {
                // PDF - use iframe
                content.innerHTML = `
                    <div class="bg-white rounded-lg shadow-2xl overflow-hidden" style="width: 90vw; max-width: 900px; height: 80vh;">
                        <iframe src="${doc.url}" class="w-full h-full" title="${doc.name}"></iframe>
                    </div>
                `;
            }
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (document.getElementById('documentViewer').classList.contains('hidden')) return;
            
            if (e.key === 'Escape') closeDocumentViewer();
            if (e.key === 'ArrowLeft') navigateDoc(-1);
            if (e.key === 'ArrowRight') navigateDoc(1);
        });
    </script>
    @endif
</x-app-layout>
