// Project Modal State
let projectModalProjects = [];
let currentProjectIndex = 0;

// Open Modal
export function openProjectModal(projectSlug) {
    currentProjectIndex = projectModalProjects.findIndex(p => p.slug === projectSlug);

    if (currentProjectIndex === -1) {
        console.error('Project not found:', projectSlug);
        return;
    }

    const modal = document.getElementById('project-modal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    renderProjectContent();

    // Track project modal open
    if (window.GA4) {
        window.GA4.trackProjectModal(projectSlug, 'open');
    }

    // Update URL with hash
    history.replaceState(null, '', '#project-' + projectModalProjects[currentProjectIndex].slug);
}

// Close Modal
export function closeProjectModal() {
    const modal = document.getElementById('project-modal');
    modal.classList.add('hidden');
    document.body.style.overflow = '';

    // Track project modal close
    if (window.GA4 && projectModalProjects[currentProjectIndex]) {
        window.GA4.trackProjectModal(projectModalProjects[currentProjectIndex].slug, 'close');
    }

    // Remove hash from URL
    history.replaceState(null, '', window.location.pathname);
}

// Next Project
function nextProject() {
    currentProjectIndex = (currentProjectIndex + 1) % projectModalProjects.length;
    renderProjectContent();
    history.replaceState(null, '', '#project-' + projectModalProjects[currentProjectIndex].slug);
}

// Previous Project
function previousProject() {
    currentProjectIndex = currentProjectIndex === 0 ? projectModalProjects.length - 1 : currentProjectIndex - 1;
    renderProjectContent();
    history.replaceState(null, '', '#project-' + projectModalProjects[currentProjectIndex].slug);
}

// Render Project Content
function renderProjectContent() {
    const project = projectModalProjects[currentProjectIndex];
    const content = document.getElementById('project-modal-content');

    // Build image src
    const imageSrc = project.cover_image
        ? `/storage/${project.cover_image}`
        : (project.screenshot_path ? `/storage/${project.screenshot_path}` : null);

    // Build technologies HTML
    let techHtml = '';
    if (project.technologies && project.technologies.length > 0) {
        techHtml = project.technologies.map(tech =>
            `<span class="px-4 py-2 glass-dark rounded-full text-sm text-white/90 font-medium">${tech}</span>`
        ).join('');
    }

    // Build client badge
    let clientBadge = '';
    if (project.client_name) {
        clientBadge = `
            <div class="inline-flex items-center gap-2 px-4 py-2 glass-dark rounded-full">
                <svg class="w-5 h-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span class="text-white font-medium">Klient: ${project.client_name}</span>
            </div>
        `;
    }

    // Build testimonial
    let testimonialHtml = '';
    if (project.testimonial) {
        testimonialHtml = `
            <div class="mb-8 p-6 glass-dark rounded-2xl border border-white/10">
                <div class="flex items-start gap-4">
                    <svg class="w-8 h-8 text-purple-400 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                    </svg>
                    <div>
                        <p class="text-white/90 italic text-lg leading-relaxed">${project.testimonial}</p>
                        ${project.client_name ? `<p class="mt-3 text-purple-400 font-semibold">— ${project.client_name}</p>` : ''}
                    </div>
                </div>
            </div>
        `;
    }

    // Build links
    let linksHtml = '';
    if (project.live_url) {
        linksHtml += `
            <a href="${project.live_url}" target="_blank" rel="noopener noreferrer"
               onclick="if(window.GA4) window.GA4.trackProjectLiveClick('${project.slug}', '${project.live_url}')"
               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white rounded-full font-semibold transition-all shadow-lg hover:shadow-xl hover:scale-105">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
                <span>Besök Live Site</span>
            </a>
        `;
    }

    if (project.repo_url) {
        linksHtml += `
            <a href="${project.repo_url}" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-2 px-6 py-3 glass-dark hover:bg-white/20 text-white rounded-full font-semibold transition-all">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                </svg>
                <span>Se Kod</span>
            </a>
        `;
    }

    // Format date
    const date = new Date(project.created_at);
    const formattedDate = date.toLocaleDateString('sv-SE', { year: 'numeric', month: 'long' });

    // Build full content
    content.innerHTML = `
        <!-- Hero Image -->
        <div class="relative h-96 bg-gradient-to-br from-purple-900 via-indigo-900 to-purple-900">
            ${imageSrc ? `<img src="${imageSrc}" alt="${project.title}" width="1200" height="900" class="w-full h-full object-cover" loading="lazy" decoding="async">` : ''}
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

            <!-- Project Title on Image -->
            <div class="absolute bottom-8 left-8 right-8">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">${project.title}</h2>
                ${clientBadge}
            </div>
        </div>

        <!-- Content -->
        <div class="p-8">
            <!-- Technologies -->
            <div class="mb-8">
                <h3 class="text-sm font-semibold text-white/60 uppercase tracking-wide mb-3">Teknologier</h3>
                <div class="flex flex-wrap gap-2">
                    ${techHtml}
                </div>
            </div>

            <!-- Description -->
            <div class="mb-8">
                <h3 class="text-sm font-semibold text-white/60 uppercase tracking-wide mb-3">Beskrivning</h3>
                <div class="prose prose-invert prose-lg max-w-none">
                    <p class="text-white/90 leading-relaxed whitespace-pre-line">${project.description || project.summary}</p>
                </div>
            </div>

            <!-- Testimonial -->
            ${testimonialHtml}

            <!-- Project Links -->
            <div class="flex flex-wrap gap-4">
                ${linksHtml}
            </div>

            <!-- Project Date -->
            <div class="mt-8 pt-6 border-t border-white/10">
                <p class="text-white/50 text-sm">Skapat: ${formattedDate}</p>
            </div>
        </div>
    `;
}

// Initialize
export function initProjectModal(projects) {
    projectModalProjects = projects;

    // Expose globally for onclick handlers
    window.openProjectModal = openProjectModal;
    window.closeProjectModal = closeProjectModal;
    window.nextProject = nextProject;
    window.previousProject = previousProject;

    // ESC key to close modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('project-modal');
            if (modal && !modal.classList.contains('hidden')) {
                closeProjectModal();
            }
        }
    });

    // Check for hash on page load
    window.addEventListener('load', function() {
        const hash = window.location.hash;
        if (hash.startsWith('#project-')) {
            const slug = hash.replace('#project-', '');
            openProjectModal(slug);
        }
    });
}
