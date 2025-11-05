@props(['projects'])

<!-- Project Modal -->
<div id="project-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" onclick="closeProjectModal()"></div>

    <!-- Modal Container -->
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-6xl mx-auto glass-dark rounded-3xl overflow-hidden" onclick="event.stopPropagation()">

            <!-- Close Button -->
            <button onclick="closeProjectModal()"
                    class="absolute top-4 right-4 z-10 p-2 rounded-full glass-dark hover:bg-white/20 transition-colors">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Modal Content -->
            <div class="max-h-[90vh] overflow-y-auto" id="project-modal-content">
                <!-- Content will be populated by JavaScript -->
            </div>

            <!-- Navigation Arrows -->
            <div id="project-modal-nav">
                <!-- Previous -->
                <button onclick="previousProject()"
                        class="absolute left-4 top-1/2 -translate-y-1/2 p-3 glass-dark hover:bg-white/20 rounded-full transition-all hover:scale-110">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <!-- Next -->
                <button onclick="nextProject()"
                        class="absolute right-4 top-1/2 -translate-y-1/2 p-3 glass-dark hover:bg-white/20 rounded-full transition-all hover:scale-110">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Pass projects data to app.js
window.projectModalData = @json($projects);
</script>
