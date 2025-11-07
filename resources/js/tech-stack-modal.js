// Tech Stack Modal State
let techStackData = null;
let techStackLoaded = false;

// Open Modal
export function openTechStackModal() {
    const modal = document.getElementById('tech-stack-modal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    // Load data if not already loaded
    if (!techStackLoaded) {
        loadTechStackData();
    }
}

// Close Modal
export function closeTechStackModal() {
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
    window.d3.select('#tech-graph-modal').selectAll('*').remove();

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

    const svg = window.d3.select('#tech-graph-modal')
        .append('svg')
        .attr('width', width)
        .attr('height', height);

    // Tooltip
    const tooltip = window.d3.select('body').append('div')
        .attr('class', 'fixed bg-gray-900 text-white px-3 py-2 rounded-lg text-sm shadow-lg pointer-events-none opacity-0 z-[9999]');

    // Simulation
    const simulation = window.d3.forceSimulation(techData.nodes)
        .force('link', window.d3.forceLink(techData.links).id(d => d.id).distance(80))
        .force('charge', window.d3.forceManyBody().strength(-400))
        .force('center', window.d3.forceCenter(width / 2, height / 2))
        .force('collision', window.d3.forceCollide().radius(d => Math.sqrt(d.count) * 10 + 15));

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
        .call(window.d3.drag()
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
            window.d3.select(this).transition().duration(200).attr('r', Math.sqrt(d.count) * 10 + 12);
            tooltip.style('opacity', 1)
                .html('<strong>' + d.name + '</strong><br/>' + d.count + ' projekt')
                .style('left', (event.pageX + 10) + 'px')
                .style('top', (event.pageY - 10) + 'px');

            // Track hover
            if (window.GA4) {
                window.GA4.trackTechNode(d.name, 'hover');
            }
        })
        .on('mouseout', function(event, d) {
            window.d3.select(this).transition().duration(200).attr('r', Math.sqrt(d.count) * 10 + 8);
            tooltip.style('opacity', 0);
        })
        .on('click', function(event, d) {
            // Track click
            if (window.GA4) {
                window.GA4.trackTechNode(d.name, 'click');
            }
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

// Initialize
export function initTechStackModal() {
    // Expose globally for onclick handlers
    window.openTechStackModal = openTechStackModal;
    window.closeTechStackModal = closeTechStackModal;

    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeTechStackModal();
        }
    });
}
