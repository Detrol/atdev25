{{-- Projects Section Component --}}
@props(["projects"])

<section id="projects" class="relative py-24 bg-gray-50 dark:bg-gray-800 overflow-hidden">
    <div class="relative max-w-6xl mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">Utvalda Projekt</h2>
            <p class="text-xl text-gray-600 dark:text-gray-400">En samling av mina senaste arbeten</p>
        </div>

        <div class="grid md:grid-cols-12 gap-6">
            @forelse($projects as $index => $project)
            <div class="group relative {{ $index === 0 ? 'md:col-span-12' : 'md:col-span-6' }} transition-all duration-300 hover:-translate-y-2 hover:scale-[1.02]">
                <div class="relative w-full {{ $index === 0 ? 'h-96' : 'h-80' }} rounded-3xl overflow-hidden">
                    <!-- Gradient border on hover -->
                    <div class="absolute inset-0 rounded-3xl bg-gradient-to-r from-purple-500 via-blue-500 to-pink-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                    <!-- Content wrapper -->
                    <div class="absolute inset-[2px] rounded-3xl h-full overflow-hidden bg-white dark:bg-gray-900">
                        @if($project->cover_image || $project->screenshot_path)
                        <img src="{{ asset('storage/' . ($project->cover_image ?? $project->screenshot_path)) }}"
                             alt="Skärmdump av projektet {{ $project->title }} - {{ $project->summary }}"
                             loading="lazy"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @else
                        <div class="w-full h-full bg-gradient-to-br from-purple-400 via-blue-500 to-pink-500 flex items-center justify-center">
                            <svg class="w-24 h-24 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        @endif

                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent"></div>

                        <div class="absolute inset-0 p-8 flex flex-col justify-end">
                            <h3 class="text-3xl font-bold text-white mb-3">
                                {{ $project->title }}
                            </h3>

                            <p class="text-white/90 mb-6 line-clamp-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                {{ $project->summary }}
                            </p>

                            @if($project->technologies)
                            <div class="flex flex-wrap gap-2 mb-4">
                                @foreach((is_array($project->technologies) ? $project->technologies : json_decode($project->technologies, true)) as $tech)
                                <span class="px-3 py-1 bg-white/20 border border-white/30 text-white text-sm rounded-full">
                                    {{ trim($tech) }}
                                </span>
                                @endforeach
                            </div>
                            @endif

                            <a href="/projects/{{ $project->slug }}"
                               aria-label="Visa projekt: {{ $project->title }}"
                               class="absolute bottom-8 right-8 w-14 h-14 bg-white/20 border border-white/30 rounded-full flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-all duration-300 hover:bg-white/30 hover:scale-110">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="md:col-span-12 text-center py-24">
                <div class="inline-block p-8 glass-morph rounded-3xl">
                    <svg class="w-24 h-24 mx-auto mb-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-gray-600 dark:text-gray-400 text-xl font-semibold">Inga projekt att visa ännu</p>
                    <p class="text-gray-500 dark:text-gray-500 mt-2">Fantastiska projekt kommer snart!</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Tech Stack CTA -->
        <div class="mt-16 text-center" id="tech-stack-section">
            <div class="relative inline-block">
                <div class="absolute inset-0 bg-gradient-to-r from-purple-500 via-blue-500 to-pink-500 rounded-3xl blur-xl opacity-30 animate-pulse"></div>
                <button onclick="openTechStackModal()" class="relative px-10 py-5 bg-gradient-to-r from-purple-600 via-blue-600 to-pink-600 text-white rounded-3xl font-bold text-lg shadow-2xl hover:shadow-purple-500/50 transition-all hover:scale-105 active:scale-95 flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Utforska Min Tech Stack
                </button>
            </div>
            <p class="mt-4 text-gray-600 dark:text-gray-400">Interaktiv visualisering av teknologier jag använder</p>

            <!-- Tech Stack Modal -->
            <div id="tech-stack-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">
                <div class="relative bg-white dark:bg-gray-900 rounded-3xl shadow-2xl w-full max-w-7xl max-h-[90vh] overflow-hidden">

                    <!-- Header -->
                    <div class="sticky top-0 z-10 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 px-8 py-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Tech Stack Visualizer</h2>
                                <p class="text-gray-600 dark:text-gray-400 mt-1">Interaktiv graf över teknologier och deras relationer</p>
                            </div>
                            <button onclick="closeTechStackModal()" class="p-3 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition-colors" aria-label="Stäng Tech Stack Visualizer">
                                <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="overflow-y-auto max-h-[calc(90vh-120px)] p-8">
                        <!-- Loading State -->
                        <div id="tech-stack-loading" class="text-center py-20">
                            <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-purple-600 border-t-transparent"></div>
                            <p class="mt-4 text-gray-600 dark:text-gray-400">Laddar tech stack...</p>
                        </div>

                        <!-- Error State -->
                        <div id="tech-stack-error" class="hidden text-center py-20">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/20 mb-4">
                                <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-red-600 dark:text-red-400 font-medium">Kunde inte ladda tech stack data</p>
                            <button onclick="loadTechStackData()" class="mt-4 px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                Försök igen
                            </button>
                        </div>

                        <!-- Content Container -->
                        <div id="tech-stack-content" class="hidden">
                            <!-- D3.js Visualization -->
                            <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-6 mb-8">
                                <div id="tech-graph-modal" class="w-full" style="height: 500px;"></div>

                                <!-- Legend -->
                                <div class="mt-6 flex flex-wrap justify-center gap-6 text-sm">
                                    <div class="flex items-center gap-2">
                                        <div class="w-4 h-4 rounded-full bg-blue-500"></div>
                                        <span class="text-gray-700 dark:text-gray-300">Frontend</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-4 h-4 rounded-full bg-green-500"></div>
                                        <span class="text-gray-700 dark:text-gray-300">Backend</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-4 h-4 rounded-full bg-purple-500"></div>
                                        <span class="text-gray-700 dark:text-gray-300">Database</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-4 h-4 rounded-full bg-orange-500"></div>
                                        <span class="text-gray-700 dark:text-gray-300">DevOps</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-4 h-4 rounded-full bg-gray-500"></div>
                                        <span class="text-gray-700 dark:text-gray-300">Övrigt</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Technology Statistics -->
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Teknologistatistik</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="tech-stats-modal"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
// Tech Stack Modal State
let techStackData = null;
let techStackLoaded = false;

// Open Modal
function openTechStackModal() {
    const modal = document.getElementById('tech-stack-modal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    // Load data if not already loaded
    if (!techStackLoaded) {
        loadTechStackData();
    }
}

// Close Modal
function closeTechStackModal() {
    const modal = document.getElementById('tech-stack-modal');
    modal.classList.add('hidden');
    document.body.style.overflow = '';
}

// Load Tech Stack Data
async function loadTechStackData() {
    const loading = document.getElementById('tech-stack-loading');
    const error = document.getElementById('tech-stack-error');
    const content = document.getElementById('tech-stack-content');

    // Show loading
    loading.classList.remove('hidden');
    error.classList.add('hidden');
    content.classList.add('hidden');

    try {
        const response = await fetch('/api/tech-stack');

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        techStackData = await response.json();
        techStackLoaded = true;

        // Hide loading, show content
        loading.classList.add('hidden');
        content.classList.remove('hidden');

        // Render visualization and stats
        renderTechGraph(techStackData);
        renderTechStats(techStackData.technologies);

    } catch (err) {
        console.error('Failed to load tech stack:', err);
        loading.classList.add('hidden');
        error.classList.remove('hidden');
    }
}

// Render Statistics
function renderTechStats(technologies) {
    const container = document.getElementById('tech-stats-modal');
    if (!container) return;

    let html = '';
    technologies.forEach(tech => {
        html += '<div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-shadow">';
        html += '<div class="flex items-center justify-between mb-2">';
        html += '<h4 class="text-lg font-semibold text-gray-900 dark:text-white">' + tech.name + '</h4>';
        html += '<span class="bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 text-xs font-medium px-2.5 py-0.5 rounded">';
        html += tech.count + ' projekt</span></div>';
        html += '<div class="text-sm text-gray-600 dark:text-gray-400">';
        html += '<p class="font-medium mb-1">Används i:</p>';
        html += '<ul class="list-disc list-inside space-y-1">';
        tech.projects.forEach(project => {
            html += '<li class="text-gray-700 dark:text-gray-300">' + project + '</li>';
        });
        html += '</ul></div></div>';
    });

    container.innerHTML = html;
}

// Render D3 Graph
function renderTechGraph(techData) {
    // Clear any existing graph
    d3.select('#tech-graph-modal').selectAll('*').remove();

    const colors = {
        frontend: '#3B82F6',
        backend: '#10B981',
        database: '#8B5CF6',
        devops: '#F59E0B',
        other: '#6B7280'
    };

    const container = document.getElementById('tech-graph-modal');
    const width = container.clientWidth;
    const height = 500;

    const svg = d3.select('#tech-graph-modal')
        .append('svg')
        .attr('width', width)
        .attr('height', height);

    // Tooltip
    const tooltip = d3.select('body').append('div')
        .attr('class', 'fixed bg-gray-900 text-white px-3 py-2 rounded-lg text-sm shadow-lg pointer-events-none opacity-0 z-[9999]');

    // Simulation
    const simulation = d3.forceSimulation(techData.nodes)
        .force('link', d3.forceLink(techData.links).id(d => d.id).distance(80))
        .force('charge', d3.forceManyBody().strength(-400))
        .force('center', d3.forceCenter(width / 2, height / 2))
        .force('collision', d3.forceCollide().radius(d => Math.sqrt(d.count) * 10 + 15));

    // Links
    const link = svg.append('g')
        .selectAll('line')
        .data(techData.links)
        .join('line')
        .attr('stroke', '#cbd5e1')
        .attr('stroke-opacity', 0.4)
        .attr('stroke-width', d => Math.sqrt(d.value) * 2);

    // Nodes
    const node = svg.append('g')
        .selectAll('g')
        .data(techData.nodes)
        .join('g')
        .call(d3.drag()
            .on('start', dragstarted)
            .on('drag', dragged)
            .on('end', dragended));

    // Circles
    node.append('circle')
        .attr('r', d => Math.sqrt(d.count) * 10 + 8)
        .attr('fill', d => colors[d.group])
        .attr('stroke', '#fff')
        .attr('stroke-width', 3)
        .style('cursor', 'pointer')
        .on('mouseover', function(event, d) {
            d3.select(this).transition().duration(200).attr('r', Math.sqrt(d.count) * 10 + 12);
            tooltip.style('opacity', 1)
                .html('<strong>' + d.name + '</strong><br/>' + d.count + ' projekt')
                .style('left', (event.pageX + 10) + 'px')
                .style('top', (event.pageY - 10) + 'px');
        })
        .on('mouseout', function(event, d) {
            d3.select(this).transition().duration(200).attr('r', Math.sqrt(d.count) * 10 + 8);
            tooltip.style('opacity', 0);
        });

    // Labels
    const isDark = document.documentElement.classList.contains('dark');
    node.append('text')
        .text(d => d.name)
        .attr('y', d => Math.sqrt(d.count) * 10 + 25)
        .attr('text-anchor', 'middle')
        .attr('font-size', '13px')
        .attr('font-weight', '600')
        .attr('fill', isDark ? '#e5e7eb' : '#1f2937')
        .style('pointer-events', 'none');

    // Tick
    simulation.on('tick', () => {
        link.attr('x1', d => d.source.x).attr('y1', d => d.source.y)
            .attr('x2', d => d.target.x).attr('y2', d => d.target.y);
        node.attr('transform', d => 'translate(' + d.x + ',' + d.y + ')');
    });

    // Drag functions
    function dragstarted(event, d) {
        if (!event.active) simulation.alphaTarget(0.3).restart();
        d.fx = d.x;
        d.fy = d.y;
    }

    function dragged(event, d) {
        d.fx = event.x;
        d.fy = event.y;
    }

    function dragended(event, d) {
        if (!event.active) simulation.alphaTarget(0);
        d.fx = null;
        d.fy = null;
    }
}

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeTechStackModal();
    }
});
</script>
@endpush

<!-- Interactive Demos CTA Section -->
