<footer class="mt-16 border-t border-slate-200 bg-slate-950 text-white">
    <div class="mx-auto grid w-full max-w-none gap-10 px-4 py-12 sm:px-6 lg:grid-cols-4 lg:px-8 xl:px-10">
        <div class="space-y-3">
            <h4>Contact Us</h4>
            <p class="text-sm text-slate-300">Biogenix Healthcare Solutions</p>
            <p>
                <strong>Email:</strong>
                <a href="mailto:support@biogenix.com" class="text-white no-underline hover:text-primary-200">support@biogenix.com</a>
            </p>
            <p>
                <strong>Phone:</strong>
                <a href="tel:+919876543210" class="text-white no-underline hover:text-primary-200">+91 98765 43210</a>
            </p>
            <p class="text-sm text-slate-300">123 Medical Park Drive, Lucknow, UP, IN</p>
        </div>

        <div>
            <h4>Quick Links</h4>
            <ul class="mt-4 space-y-3 text-sm text-slate-300">
                <li><a href="{{ route('home') }}" class="no-underline hover:text-white">Home</a></li>
                <li><a href="{{ route('products.index') }}" class="no-underline hover:text-white">Products</a></li>
                <li><a href="{{ route('proforma.create') }}" class="no-underline hover:text-white">Generate Quote</a></li>
                <li><a href="{{ route('about') }}" class="no-underline hover:text-white">About Us</a></li>
                <li><a href="{{ route('contact') }}" class="no-underline hover:text-white">Contact Us</a></li>
            </ul>
        </div>

        <div>
            <h4>Legal</h4>
            <ul class="mt-4 space-y-3 text-sm text-slate-300">
                <li><a href="{{ route('privacy') }}" class="no-underline hover:text-white">Privacy Policy</a></li>
                <li><a href="{{ route('terms') }}" class="no-underline hover:text-white">Terms of Use</a></li>
                <li><a href="{{ route('faq') }}" class="no-underline hover:text-white">FAQ</a></li>
            </ul>
        </div>

        <div>
            <h4>Follow Us</h4>
            <div class="mt-4 flex items-center gap-3">
                <a href="#" class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-white/10 text-slate-300 transition hover:bg-primary-600 hover:text-white" aria-label="LinkedIn">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                </a>
                <a href="#" class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-white/10 text-slate-300 transition hover:bg-primary-600 hover:text-white" aria-label="Facebook">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385h-3.047v-3.47h3.047v-2.642c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953h-1.514c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385c5.737-.9 10.125-5.864 10.125-11.854z"/></svg>
                </a>
                <a href="#" class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-white/10 text-slate-300 transition hover:bg-primary-600 hover:text-white" aria-label="YouTube">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136c-1.872-.508-9.376-.508-9.376-.508s-7.504 0-9.376.508a3.016 3.016 0 0 0-2.122 2.136 31.486 31.486 0 0 0-.502 5.814 31.486 31.486 0 0 0 .502 5.814 3.016 3.016 0 0 0 2.122 2.136c1.872.508 9.376.508 9.376.508s7.504 0 9.376-.508a3.016 3.016 0 0 0 2.122-2.136 31.486 31.486 0 0 0 .502-5.814 31.486 31.486 0 0 0-.502-5.814zm-13.976 9.394v-7.16l6.276 3.58-6.276 3.58z"/></svg>
                </a>
            </div>
        </div>
    </div>

    <div class="mx-auto w-full max-w-none border-t border-white/10 px-4 py-6 text-center text-sm text-slate-400 sm:px-6 lg:px-8 xl:px-10">
        <p>&copy; 2026 Biogenix Healthcare Solutions - All Rights Reserved</p>
    </div>
</footer>
