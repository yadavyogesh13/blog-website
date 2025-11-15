@extends('layouts.app')

@section('title', 'Contact Us - ' . config('app.name'))

@section('meta')
    <meta name="description" content="Get in touch with BlogSite. We'd love to hear from you! Contact us for support, suggestions, or partnership opportunities.">
    <meta name="keywords" content="contact, support, help, feedback, get in touch">
    <link rel="canonical" href="{{ url('/contact') }}">
    
    <!-- Open Graph -->
    <meta property="og:title" content="Contact Us - {{ config('app.name') }}">
    <meta property="og:description" content="Get in touch with BlogSite. We'd love to hear from you!">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/contact') }}">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary">
    <meta property="twitter:title" content="Contact Us - {{ config('app.name') }}">
    <meta property="twitter:description" content="Get in touch with BlogSite. We'd love to hear from you!">
    
    <!-- Schema.org -->
    @verbatim
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "ContactPage",
        "name": "Contact Us",
        "description": "Contact page for BlogSite",
        "url": "{{ url('/contact') }}",
        "mainEntity": {
            "@type": "Organization",
            "name": "BlogSite",
            "email": "contact@blogsite.com",
            "url": "{{ url('/') }}"
        }
    }
    </script>
    @endverbatim
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Contact Us</h1>
            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                We'd love to hear from you! Get in touch with any questions, suggestions, or feedback.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contact Information -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Contact Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Get in Touch</h3>
                    
                    <!-- Email -->
                    <div class="flex items-start space-x-3 mb-4">
                        <div class="bg-blue-100 dark:bg-blue-900 p-2 rounded-lg">
                            <i class="fas fa-envelope text-blue-600 dark:text-blue-400 text-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">Email</h4>
                            <a href="mailto:contact@blogsite.com" class="text-blue-600 dark:text-blue-400 hover:underline">
                                contact@blogsite.com
                            </a>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">We'll respond within 24 hours</p>
                        </div>
                    </div>

                    <!-- Support -->
                    <div class="flex items-start space-x-3 mb-4">
                        <div class="bg-green-100 dark:bg-green-900 p-2 rounded-lg">
                            <i class="fas fa-headset text-green-600 dark:text-green-400 text-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">Support</h4>
                            <a href="mailto:support@blogsite.com" class="text-blue-600 dark:text-blue-400 hover:underline">
                                support@blogsite.com
                            </a>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Technical help and assistance</p>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="flex items-start space-x-3 mb-4">
                        <div class="bg-purple-100 dark:bg-purple-900 p-2 rounded-lg">
                            <i class="fas fa-map-marker-alt text-purple-600 dark:text-purple-400 text-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">Office</h4>
                            <p class="text-gray-600 dark:text-gray-400">
                                123 Blog Street<br>
                                Content City, CC 12345<br>
                                United States
                            </p>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Follow Us</h4>
                        <div class="flex space-x-3">
                            <a href="#" class="bg-gray-100 dark:bg-gray-700 p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-blue-600 hover:text-white transition-colors duration-200">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="bg-gray-100 dark:bg-gray-700 p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-blue-400 hover:text-white transition-colors duration-200">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="bg-gray-100 dark:bg-gray-700 p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-pink-600 hover:text-white transition-colors duration-200">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="bg-gray-100 dark:bg-gray-700 p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-blue-700 hover:text-white transition-colors duration-200">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- FAQ Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Quick Help</h3>
                    <div class="space-y-3">
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white text-sm">Technical Issues?</h4>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Check our knowledge base or contact support.</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white text-sm">Content Questions?</h4>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Reach out to our editorial team.</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white text-sm">Partnerships?</h4>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Email us for business inquiries.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-8">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Send us a Message</h3>
                    
                    <form id="contactForm" class="space-y-6">
                        @csrf
                        
                        <!-- Name and Email -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Full Name *
                                </label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                    placeholder="Your full name"
                                >
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Email Address *
                                </label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                    placeholder="your.email@example.com"
                                >
                            </div>
                        </div>

                        <!-- Subject -->
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Subject *
                            </label>
                            <select 
                                id="subject" 
                                name="subject"
                                required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                            >
                                <option value="">Select a subject</option>
                                <option value="general">General Inquiry</option>
                                <option value="support">Technical Support</option>
                                <option value="feedback">Feedback & Suggestions</option>
                                <option value="partnership">Partnership Opportunity</option>
                                <option value="advertising">Advertising Inquiry</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Message *
                            </label>
                            <textarea 
                                id="message" 
                                name="message"
                                rows="6"
                                required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 resize-vertical"
                                placeholder="Tell us how we can help you..."
                            ></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button 
                                type="submit"
                                class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-200 flex items-center justify-center"
                                id="submitButton"
                            >
                                <i class="fas fa-paper-plane mr-2"></i>
                                Send Message
                            </button>
                        </div>

                        <!-- Form Status -->
                        <div id="formStatus" class="hidden p-4 rounded-lg text-sm"></div>
                    </form>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="mt-12 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-8">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 text-center">Frequently Asked Questions</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-2">How long does it take to get a response?</h4>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">We typically respond to all inquiries within 24 hours during business days.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Do you offer technical support?</h4>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Yes, we provide technical support for all platform-related issues.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Can I suggest new features?</h4>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Absolutely! We welcome all feature suggestions and feedback.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Are you hiring?</h4>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Check our careers page for current openings and opportunities.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    const submitButton = document.getElementById('submitButton');
    const formStatus = document.getElementById('formStatus');

    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const originalText = submitButton.innerHTML;
            
            // Show loading state
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending...';
            submitButton.disabled = true;
            
            // Simulate form submission (replace with actual API call)
            setTimeout(() => {
                // Show success message
                formStatus.className = 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 p-4 rounded-lg text-sm';
                formStatus.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2 text-lg"></i>
                        <div>
                            <strong>Thank you for your message!</strong>
                            <p class="mt-1">We've received your inquiry and will get back to you within 24 hours.</p>
                        </div>
                    </div>
                `;
                formStatus.classList.remove('hidden');
                
                // Reset form
                contactForm.reset();
                
                // Reset button after 5 seconds
                setTimeout(() => {
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                }, 5000);
                
            }, 2000);
        });
    }

    // Add character counter for message
    const messageTextarea = document.getElementById('message');
    if (messageTextarea) {
        const charCount = document.createElement('div');
        charCount.className = 'text-xs text-gray-500 dark:text-gray-400 mt-1 text-right';
        charCount.textContent = '0/1000 characters';
        messageTextarea.parentNode.appendChild(charCount);

        messageTextarea.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = `${count}/1000 characters`;
            
            if (count > 1000) {
                charCount.classList.add('text-red-600', 'dark:text-red-400');
            } else {
                charCount.classList.remove('text-red-600', 'dark:text-red-400');
            }
        });
    }
});
</script>
@endpush