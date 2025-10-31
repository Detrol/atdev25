@extends('layouts.app')

@section('title', 'Tech Stack Visualizer')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                Tech Stack Visualizer
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Interaktiv visualisering av teknologier och hur de används tillsammans i mina projekt.
                Storleken på noderna visar hur ofta teknologin används.
            </p>
        </div>

        <!-- Visualization Container -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
            <div id="tech-graph" class="w-full" style="height: 600px;"></div>

            <!-- Legend -->
            <div class="mt-6 flex flex-wrap justify-center gap-6 text-sm">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-full bg-blue-500"></div>
                    <span class="text-gray-700">Frontend</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-full bg-green-500"></div>
                    <span class="text-gray-700">Backend</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-full bg-purple-500"></div>
                    <span class="text-gray-700">Database</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-full bg-orange-500"></div>
                    <span class="text-gray-700">DevOps</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-full bg-gray-500"></div>
                    <span class="text-gray-700">Övrigt</span>
                </div>
            </div>
        </div>

        <!-- Technology Statistics -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Teknologistatistik</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($techData['technologies'] as $tech)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $tech['name'] }}</h3>
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                {{ $tech['count'] }} {{ $tech['count'] === 1 ? 'projekt' : 'projekt' }}
                            </span>
                        </div>
                        <div class="text-sm text-gray-600">
                            <p class="font-medium mb-1">Används i:</p>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($tech['projects'] as $project)
                                    <li class="text-gray-700">{{ $project }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Back to Home -->
        <div class="mt-8 text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Tillbaka till startsidan
            </a>
        </div>
    </div>
</div>

<!-- D3.js -->
<script src="https://d3js.org/d3.v7.min.js"></script>

<script>
// Tech data from controller
const techData = @json($techData);

// Color mapping for categories
const colors = {
    frontend: '#3B82F6',  // blue
    backend: '#10B981',   // green
    database: '#8B5CF6',  // purple
    devops: '#F59E0B',    // orange
    other: '#6B7280'      // gray
};

// Set up SVG
const container = document.getElementById('tech-graph');
const width = container.clientWidth;
const height = 600;

const svg = d3.select('#tech-graph')
    .append('svg')
    .attr('width', width)
    .attr('height', height)
    .attr('viewBox', [0, 0, width, height]);

// Create tooltip
const tooltip = d3.select('body')
    .append('div')
    .attr('class', 'absolute bg-gray-900 text-white px-3 py-2 rounded-lg text-sm shadow-lg pointer-events-none opacity-0 transition-opacity duration-200')
    .style('z-index', '9999');

// Create force simulation
const simulation = d3.forceSimulation(techData.nodes)
    .force('link', d3.forceLink(techData.links)
        .id(d => d.id)
        .distance(d => 100 - (d.value * 10))) // Stronger links = closer together
    .force('charge', d3.forceManyBody().strength(-300))
    .force('center', d3.forceCenter(width / 2, height / 2))
    .force('collision', d3.forceCollide().radius(d => Math.sqrt(d.count) * 10 + 10));

// Draw links
const link = svg.append('g')
    .selectAll('line')
    .data(techData.links)
    .join('line')
    .attr('stroke', '#cbd5e1')
    .attr('stroke-opacity', d => 0.3 + (d.value * 0.1))
    .attr('stroke-width', d => Math.sqrt(d.value));

// Draw nodes
const node = svg.append('g')
    .selectAll('g')
    .data(techData.nodes)
    .join('g')
    .call(d3.drag()
        .on('start', dragstarted)
        .on('drag', dragged)
        .on('end', dragended));

// Add circles to nodes
node.append('circle')
    .attr('r', d => Math.sqrt(d.count) * 10 + 5)
    .attr('fill', d => colors[d.group])
    .attr('stroke', '#fff')
    .attr('stroke-width', 2)
    .style('cursor', 'pointer')
    .on('mouseover', function(event, d) {
        d3.select(this)
            .transition()
            .duration(200)
            .attr('r', Math.sqrt(d.count) * 10 + 8);

        tooltip
            .style('opacity', 1)
            .html(`<strong>${d.name}</strong><br/>${d.count} ${d.count === 1 ? 'projekt' : 'projekt'}`)
            .style('left', (event.pageX + 10) + 'px')
            .style('top', (event.pageY - 10) + 'px');
    })
    .on('mouseout', function(event, d) {
        d3.select(this)
            .transition()
            .duration(200)
            .attr('r', Math.sqrt(d.count) * 10 + 5);

        tooltip.style('opacity', 0);
    });

// Add text labels to nodes
node.append('text')
    .text(d => d.name)
    .attr('x', 0)
    .attr('y', d => Math.sqrt(d.count) * 10 + 20)
    .attr('text-anchor', 'middle')
    .attr('font-size', '12px')
    .attr('font-weight', '600')
    .attr('fill', '#1f2937')
    .style('pointer-events', 'none')
    .style('user-select', 'none');

// Update positions on simulation tick
simulation.on('tick', () => {
    link
        .attr('x1', d => d.source.x)
        .attr('y1', d => d.source.y)
        .attr('x2', d => d.target.x)
        .attr('y2', d => d.target.y);

    node
        .attr('transform', d => `translate(${d.x},${d.y})`);
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

// Responsive resize
window.addEventListener('resize', () => {
    const newWidth = container.clientWidth;
    svg.attr('width', newWidth)
        .attr('viewBox', [0, 0, newWidth, height]);

    simulation.force('center', d3.forceCenter(newWidth / 2, height / 2));
    simulation.alpha(0.3).restart();
});
</script>
@endsection
