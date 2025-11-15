@extends('layouts.app')

@section('title', 'Privacy Policy - ' . config('app.name'))

@section('meta')
    <meta name="description" content="Learn how BlogSite collects, uses, and protects your personal information. Read our comprehensive privacy policy.">
    <meta name="keywords" content="privacy policy, data protection, personal information, GDPR, privacy">
    <link rel="canonical" href="{{ url('/privacy-policy') }}">
    
    <!-- Open Graph -->
    <meta property="og:title" content="Privacy Policy - {{ config('app.name') }}">
    <meta property="og:description" content="Learn how BlogSite collects, uses, and protects your personal information.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/privacy-policy') }}">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary">
    <meta property="twitter:title" content="Privacy Policy - {{ config('app.name') }}">
    <meta property="twitter:description" content="Learn how BlogSite collects, uses, and protects your personal information.">
    
    <!-- Schema.org -->
    @verbatim
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebPage",
        "name": "Privacy Policy",
        "description": "Privacy Policy for BlogSite",
        "url": "{{ url('/privacy-policy') }}",
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
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Privacy Policy</h1>
            <p class="text-xl text-gray-600 dark:text-gray-400">
                Last updated: {{ date('F d, Y') }}
            </p>
        </div>

        <!-- Content -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-8">
                <!-- Introduction -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">1. Introduction</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">
                        Welcome to BlogSite. We are committed to protecting your personal information and your right to privacy. 
                        If you have any questions or concerns about this privacy policy or our practices with regard to your personal 
                        information, please contact us at <a href="mailto:privacy@blogsite.com" class="text-blue-600 dark:text-blue-400 hover:underline">privacy@blogsite.com</a>.
                    </p>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website. 
                        Please read this privacy policy carefully. If you do not agree with the terms of this privacy policy, please do not access the site.
                    </p>
                </section>

                <!-- Information We Collect -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">2. Information We Collect</h2>
                    
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">Personal Information</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">
                        We collect personal information that you voluntarily provide to us when you register on the website, 
                        express an interest in obtaining information about us or our products and services, or otherwise when you contact us.
                    </p>
                    <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 mb-4 space-y-2 ml-4">
                        <li>Name and contact information (email address)</li>
                        <li>Account credentials (username and password)</li>
                        <li>Profile information (biography, profile picture)</li>
                        <li>Communication preferences</li>
                    </ul>

                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">Automatically Collected Information</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">
                        When you visit our website, we automatically collect certain information about your device and usage patterns:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 mb-4 space-y-2 ml-4">
                        <li>IP address and browser type</li>
                        <li>Device information and operating system</li>
                        <li>Pages visited and time spent on pages</li>
                        <li>Referring website and search terms</li>
                        <li>Clickstream data and browsing patterns</li>
                    </ul>
                </section>

                <!-- How We Use Your Information -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">3. How We Use Your Information</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">
                        We use the information we collect in various ways, including to:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 mb-4 space-y-2 ml-4">
                        <li>Provide, operate, and maintain our website</li>
                        <li>Improve, personalize, and expand our website</li>
                        <li>Understand and analyze how you use our website</li>
                        <li>Develop new products, services, features, and functionality</li>
                        <li>Communicate with you, either directly or through one of our partners</li>
                        <li>Send you emails and marketing communications</li>
                        <li>Find and prevent fraud</li>
                    </ul>
                </section>

                <!-- Cookies and Tracking -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">4. Cookies and Tracking Technologies</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">
                        We use cookies and similar tracking technologies to track activity on our website and store certain information. 
                        The technologies we use include:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 mb-4 space-y-2 ml-4">
                        <li><strong>Essential Cookies:</strong> Required for basic site functionality</li>
                        <li><strong>Performance Cookies:</strong> Help us understand how visitors interact with our website</li>
                        <li><strong>Functionality Cookies:</strong> Enable enhanced functionality and personalization</li>
                        <li><strong>Analytics Cookies:</strong> Help us improve our website through data analysis</li>
                    </ul>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent. 
                        However, if you do not accept cookies, you may not be able to use some portions of our service.
                    </p>
                </section>

                <!-- Data Sharing -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">5. Data Sharing and Disclosure</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">
                        We may share your information in the following situations:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 mb-4 space-y-2 ml-4">
                        <li><strong>Service Providers:</strong> With trusted third parties who assist us in operating our website</li>
                        <li><strong>Business Transfers:</strong> In connection with any merger or sale of company assets</li>
                        <li><strong>Legal Requirements:</strong> When required by law or to protect our rights</li>
                        <li><strong>With Your Consent:</strong> For any other purpose disclosed by us when you provide the information</li>
                    </ul>
                </section>

                <!-- Data Security -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">6. Data Security</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">
                        We have implemented appropriate technical and organizational security measures designed to protect the security 
                        of any personal information we process. However, please also remember that we cannot guarantee that the internet 
                        itself is 100% secure.
                    </p>
                </section>

                <!-- Your Rights -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">7. Your Privacy Rights</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">
                        Depending on your location, you may have the following rights regarding your personal information:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 mb-4 space-y-2 ml-4">
                        <li>Access and receive a copy of your personal data</li>
                        <li>Rectify or update your personal data</li>
                        <li>Delete your personal data</li>
                        <li>Restrict or object to our processing of your personal data</li>
                        <li>Data portability</li>
                        <li>Withdraw consent at any time</li>
                    </ul>
                </section>

                <!-- Contact -->
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">8. Contact Us</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">
                        If you have questions or comments about this policy, you may contact us at:
                    </p>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <p class="text-gray-600 dark:text-gray-400">
                            <strong>Email:</strong> <a href="mailto:privacy@blogsite.com" class="text-blue-600 dark:text-blue-400 hover:underline">privacy@blogsite.com</a><br>
                            <strong>Address:</strong> 123 Blog Street, Content City, CC 12345<br>
                            <strong>Response Time:</strong> We aim to respond to all legitimate requests within 30 days.
                        </p>
                    </div>
                </section>

                <!-- Changes to Policy -->
                <section>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">9. Changes to This Privacy Policy</h2>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        We may update this privacy policy from time to time. The updated version will be indicated by an updated 
                        "Last updated" date and the updated version will be effective as soon as it is accessible. We encourage 
                        you to review this privacy policy frequently to be informed of how we are protecting your information.
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
            <a href="/contact" 
               class="border-2 border-blue-600 text-blue-600 dark:text-blue-400 dark:border-blue-400 px-6 py-3 rounded-lg font-semibold hover:bg-blue-600 hover:text-white dark:hover:bg-blue-400 dark:hover:text-white transition-colors duration-200 text-center">
                <i class="fas fa-envelope mr-2"></i>Contact Us
            </a>
        </div>
    </div>
</div>
@endsection