@extends('layouts.admin')

@section('title', 'Edit Profile - Admin Panel')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-primary-500 to-primary-600 px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white">Profile Settings</h1>
                    <p class="text-primary-100 mt-1">Manage your account information</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="fas fa-user-cog text-white text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Profile Form -->
        <div class="p-8">
            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Avatar Section -->
                    <div class="lg:col-span-1">
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900">Profile Picture</h3>
                            
                            <div class="flex flex-col items-center space-y-4">
                                <div class="relative">
                                    @if(auth()->user()->avatar)
                                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="w-32 h-32 rounded-2xl object-cover shadow-lg">
                                    @else
                                        <div class="w-32 h-32 rounded-2xl bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center shadow-lg">
                                            <i class="fas fa-user text-primary-600 text-4xl"></i>
                                        </div>
                                    @endif
                                    <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center shadow-lg">
                                        <i class="fas fa-camera text-white text-xs"></i>
                                    </div>
                                </div>
                                
                                <div class="w-full">
                                    <input type="file" name="avatar" id="avatar" accept="image/*" class="hidden">
                                    <label for="avatar" class="block w-full text-center px-4 py-2 border-2 border-dashed border-gray-300 rounded-xl hover:border-primary-400 cursor-pointer transition-colors duration-200">
                                        <i class="fas fa-upload text-gray-400 mr-2"></i>
                                        <span class="text-sm text-gray-600">Change Avatar</span>
                                    </label>
                                    @error('avatar')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Details -->
                    <div class="lg:col-span-2">
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                                
                                <div class="grid grid-cols-1 gap-6">
                                    <!-- Name -->
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                        <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 @error('name') border-red-500 @enderror">
                                        @error('name')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                        <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 @error('email') border-red-500 @enderror">
                                        @error('email')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Role (Readonly) -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                                        <div class="px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-gray-600">
                                            {{ ucfirst(auth()->user()->role) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                                <button type="submit" class="flex-1 bg-primary-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-primary-700 transition-colors duration-200 shadow-sm hover:shadow-md">
                                    <i class="fas fa-save mr-2"></i>Update Profile
                                </button>
                                <a href="{{ route('admin.profile.password') }}" class="flex-1 bg-gray-100 text-gray-700 px-6 py-3 rounded-xl font-semibold hover:bg-gray-200 transition-colors duration-200 text-center">
                                    <i class="fas fa-key mr-2"></i>Change Password
                                </a>
                                <a href="{{ route('admin.dashboard') }}" class="flex-1 border border-gray-300 text-gray-700 px-6 py-3 rounded-xl font-semibold hover:bg-gray-50 transition-colors duration-200 text-center">
                                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const avatarInput = document.getElementById('avatar');
        const avatarPreview = document.querySelector('img[alt="Avatar"]') || document.querySelector('.fa-user').closest('div');
        
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (avatarPreview.tagName === 'IMG') {
                        avatarPreview.src = e.target.result;
                    } else {
                        // Replace the placeholder with an image
                        const newAvatar = document.createElement('img');
                        newAvatar.src = e.target.result;
                        newAvatar.alt = 'Avatar';
                        newAvatar.className = 'w-32 h-32 rounded-2xl object-cover shadow-lg';
                        avatarPreview.parentNode.replaceChild(newAvatar, avatarPreview);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endsection