@extends('layouts.app')

@section('title', 'Terms of Service - ' . config('app.name'))

@section('meta')
    <meta name="description" content="Read the Terms of Service for BlogSite. Learn about user responsibilities, content guidelines, and platform rules.">
    <meta name="keywords" content="terms of service, terms and conditions, user agreement, website terms">
    <link rel="canonical" href="{{ url('/terms-of-service') }}">
    
    <!-- Open Graph -->
    <meta property="og:title" content="Terms of Service - {{ config('app.name') }}">
    <meta property="og:description" content="Read the Terms of Service for BlogSite. Learn about user responsibilities and platform rules.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/terms-of-service') }}">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary">
    <meta property="twitter:title" content="Terms of Service - {{ config('app.name') }}">
    <meta property="twitter:description" content="Read the Terms of Service for BlogSite. Learn about user responsibilities and platform rules.">
    
    <!-- Schema.org -->
    @verbatim
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebPage",
        "name": "Terms of Service",
        "description": "Terms of Service for BlogSite",
        "url": "{{ url('/terms-of-service') }}",
        "lastReviewed": "2024-01-01"
    }
    </script>
    @endverbatim
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Terms of Service</h1>
            <p class="text-xl text-gray-600 dark:text-gray-400">
                Last updated: {{ date('F d, Y') }}
            </p>
        </div>

        <!-- Content -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-8">
                <!-- Agreement -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">1. Agreement to Terms</h2>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        By accessing and using BlogSite, you accept and agree to be bound by the terms and provision of this agreement. 
                        Additionally, when using these particular services, you shall be subject to any posted guidelines or rules applicable 
                        to such services.
                    </p>
                </section>

                <!-- User Accounts -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">2. User Accounts</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">
                        When you create an account with us, you must provide information that is accurate, complete, and current at all times. 
                        Failure to do so constitutes a breach of the Terms, which may result in immediate termination of your account.
                    </p>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        You are responsible for safeguarding the password that you use to access the service and for any activities or actions 
                        under your password. You agree not to disclose your password to any third party.
                    </p>
                </section>

                <!-- Content -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">3. Content</h2>
                    
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">Your Content</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">
                        You retain ownership of any content you submit, post, or display on or through BlogSite. By submitting content, 
                        you grant us a worldwide, non-exclusive, royalty-free license to use, reproduce, modify, and display such content.
                    </p>

                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">Content Guidelines</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">
                        You are solely responsible for the content you post. You agree not to post content that:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 mb-4 space-y-2 ml-4">
                        <li>Is illegal, fraudulent, or promotes illegal activities</li>
                        <li>Infringes upon any third party's intellectual property rights</li>
                        <li>Contains hate speech, threats, or harassment</li>
                        <li>Is sexually explicit or pornographic</li>
                        <li>Contains viruses or malicious code</li>
                        <li>Is spam or promotes commercial activities without our consent</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">Content Removal</h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        We reserve the right to remove any content that violates these Terms or that we find otherwise objectionable.
                    </p>
                </section>

                <!-- Intellectual Property -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">4. Intellectual Property</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">
                        The service and its original content, features, and functionality are and will remain the exclusive property 
                        of BlogSite and its licensors. Our trademarks and trade dress may not be used in connection with any product 
                        or service without the prior written consent of BlogSite.
                    </p>
                </section>

                <!-- Links to Other Websites -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">5. Links to Other Websites</h2>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        Our service may contain links to third-party websites or services that are not owned or controlled by BlogSite. 
                        We have no control over, and assume no responsibility for, the content, privacy policies, or practices of any 
                        third-party websites or services.
                    </p>
                </section>

                <!-- Termination -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">6. Termination</h2>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        We may terminate or suspend your account immediately, without prior notice or liability, for any reason whatsoever, 
                        including without limitation if you breach the Terms. Upon termination, your right to use the service will cease immediately.
                    </p>
                </section>

                <!-- Limitation of Liability -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">7. Limitation of Liability</h2>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        In no event shall BlogSite, nor its directors, employees, partners, agents, suppliers, or affiliates, be liable for any 
                        indirect, incidental, special, consequential or punitive damages, including without limitation, loss of profits, data, 
                        use, goodwill, or other intangible losses, resulting from your access to or use of or inability to access or use the service.
                    </p>
                </section>

                <!-- Disclaimer -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">8. Disclaimer</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">
                        Your use of the service is at your sole risk. The service is provided on an "AS IS" and "AS AVAILABLE" basis. 
                        The service is provided without warranties of any kind, whether express or implied.
                    </p>
                </section>

                <!-- Governing Law -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">9. Governing Law</h2>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        These Terms shall be governed and construed in accordance with the laws of the United States, without regard to its 
                        conflict of law provisions. Our failure to enforce any right or provision of these Terms will not be considered a waiver 
                        of those rights.
                    </p>
                </section>

                <!-- Changes -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">10. Changes to Terms</h2>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        We reserve the right, at our sole discretion, to modify or replace these Terms at any time. If a revision is material, 
                        we will provide at least 30 days' notice prior to any new terms taking effect. What constitutes a material change will 
                        be determined at our sole discretion.
                    </p>
                </section>

                <!-- Contact -->
                <section>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">11. Contact Us</h2>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        If you have any questions about these Terms, please contact us at 
                        <a href="mailto:legal@blogsite.com" class="text-blue-600 dark:text-blue-400 hover:underline">legal@blogsite.com</a>.
                    </p>
                </section>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('home') }}" 
               class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-200 text-center">
                <i class="fas fa-home mr-2"></i>Back to Home
            </a>
            <a href="/privacy-policy" 
               class="border-2 border-blue-600 text-blue-600 dark:text-blue-400 dark:border-blue-400 px-6 py-3 rounded-lg font-semibold hover:bg-blue-600 hover:text-white dark:hover:bg-blue-400 dark:hover:text-white transition-colors duration-200 text-center">
                <i class="fas fa-shield-alt mr-2"></i>Privacy Policy
            </a>
        </div>
    </div>
</div>
@endsection