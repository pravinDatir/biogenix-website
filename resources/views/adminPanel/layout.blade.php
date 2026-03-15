@extends('layouts.app')

@section('content')
<div class="bg-[#f4f7fb] min-h-screen py-8">
    <div class="container mx-auto max-w-7xl flex gap-8 px-4 sm:px-6 lg:px-8">
        
        <!-- Sidebar Navigation -->
        @include('adminPanel.partials.sidebar')

        <!-- Main Content -->
        <main id="admin-main-content" class="flex-1 min-w-0 space-y-6 pb-12 transition-opacity duration-200">
            @yield('admin_content')
        </main>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const mainContent = document.getElementById('admin-main-content');
        
        // Intercept clicks on sidebar links
        document.body.addEventListener('click', async (e) => {
            const link = e.target.closest('aside a');
            if (!link || !link.href || link.getAttribute('href') === '#') return;
            
            // Only handle same-origin URLs
            const url = new URL(link.href);
            if (url.origin !== window.location.origin) return;

            e.preventDefault();
            
            // Update active state in sidebar visually immediately
            document.querySelectorAll('aside a').forEach(aEl => {
                aEl.classList.remove('bg-[#091b3f]', 'text-white', 'font-bold');
                aEl.classList.add('text-slate-500', 'hover:bg-slate-50', 'hover:text-slate-800');
                
                // Remove the blue left indicator line if present
                const indicator = aEl.querySelector('div.bg-white.rounded-r-md');
                if (indicator) indicator.remove();
                
                // Make icon slate
                const svg = aEl.querySelector('svg');
                if (svg) {
                    svg.classList.remove('text-indigo-200');
                    svg.classList.add('text-slate-400');
                }
            });

            link.classList.remove('text-slate-500', 'hover:bg-slate-50', 'hover:text-slate-800');
            link.classList.add('bg-[#091b3f]', 'text-white', 'font-bold');
            
            // Add indicator line
            const indicatorDiv = document.createElement('div');
            indicatorDiv.className = 'absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-white rounded-r-md';
            link.prepend(indicatorDiv);
            
            // Highlight icon
            const linkSvg = link.querySelector('svg');
            if (linkSvg) {
                linkSvg.classList.remove('text-slate-400');
                linkSvg.classList.add('text-indigo-200');
            }

            // Fetch new content
            mainContent.style.opacity = '0.5';
            
            try {
                const response = await fetch(link.href);
                if (!response.ok) throw new Error('Network response was not ok');
                
                const html = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                // Extract and replace exactly the main content wrapper
                const newContent = doc.getElementById('admin-main-content');
                if (newContent) {
                    mainContent.innerHTML = newContent.innerHTML;
                    // Update URL silently
                    window.history.pushState({}, '', link.href);
                    // Update page title
                    document.title = doc.title;
                }
            } catch (error) {
                console.error('Failed to load page:', error);
                window.location.href = link.href; // Fallback to normal navigation on error
            } finally {
                mainContent.style.opacity = '1';
            }
        });

        // Handle back/forward buttons
        window.addEventListener('popstate', async () => {
            window.location.reload(); 
        });
    });
</script>
@endsection
