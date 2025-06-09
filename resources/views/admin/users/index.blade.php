@extends('layouts.admin')

@section('title', 'Manage Users')

@push('styles')
<style>
    .table-action-link {
        @apply text-indigo-600 hover:text-indigo-900 font-medium transition-colors duration-200;
    }
    .table-action-button {
        @apply px-3 py-1.5 text-xs font-medium rounded-lg transition-all duration-200 transform hover:scale-105;
    }
    .status-verified {
        @apply bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center gap-1;
    }
    .status-not-verified {
        @apply bg-amber-100 text-amber-800 px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center gap-1;
    }
    .search-input {
        @apply w-full pl-10 pr-4 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200;
    }
    .filter-select {
        @apply px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 bg-white;
    }
    .stats-card {
        @apply bg-white rounded-xl border border-slate-200 p-4 shadow-sm hover:shadow-md transition-shadow duration-200;
    }
    .table-row {
        @apply hover:bg-slate-50 transition-colors duration-150;
    }
    .role-badge {
        @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
    }
    .role-admin {
        @apply bg-purple-100 text-purple-800;
    }
    .role-user {
        @apply bg-blue-100 text-blue-800;
    }
    .role-manager {
        @apply bg-green-100 text-green-800;
    }
</style>
@endpush

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-7xl mx-auto">

    <!-- Page header -->
    <div class="mb-8">
        <div class="sm:flex sm:justify-between sm:items-center">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-3xl md:text-4xl text-slate-800 font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                    User Management ✨
                </h1>
                <p class="text-slate-600 text-lg mt-1">Oversee and manage all registered users on the platform</p>
            </div>

            <!-- Right: Actions -->
            <div class="flex items-center gap-3">
                <button onclick="exportUsers()" class="inline-flex items-center px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export
                </button>
                {{-- Uncomment if you want to add new users
                <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-lg hover:shadow-xl">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add User
                </a>
                --}}
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
            <div class="stats-card">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-slate-600">Total Users</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $users->total() }}</p>
                    </div>
                </div>
            </div>
            <div class="stats-card">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-slate-600">Verified</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $users->where('email_verified_at', '!=', null)->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="stats-card">
                <div class="flex items-center">
                    <div class="p-2 bg-amber-100 rounded-lg">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.864-.833-2.634 0L4.18 18.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-slate-600">Unverified</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $users->where('email_verified_at', null)->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="stats-card">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 8a4 4 0 11-8 0v-3a4 4 0 014-4h4a4 4 0 014 4v3a4 4 0 11-8 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-slate-600">New This Month</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $users->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm mb-6 p-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Search users by name or email..."
                       class="search-input">
            </div>

            <!-- Role Filter -->
            <div>
                <select name="role" class="filter-select">
                    <option value="">All Roles</option>
                    @foreach($roles ?? [] as $role)
                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
            </div>

         

            <!-- Actions -->
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200">
                    Filter
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-medium transition-colors duration-200">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white shadow-lg rounded-xl border border-slate-200 overflow-hidden">
        <header class="px-6 py-4 border-b border-slate-100 bg-slate-50">
            <h2 class="font-semibold text-slate-800 text-lg">
                Users Directory 
                <span class="text-slate-500 font-normal text-sm ml-2">({{ $users->total() }} total)</span>
            </h2>
        </header>
        
        <div class="overflow-x-auto">
            @if($users->count() > 0)
            <table class="table-auto w-full">
                <thead class="text-xs font-semibold uppercase text-slate-500 bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 whitespace-nowrap">
                            <div class="font-semibold text-left">User</div>
                        </th>
                        <th class="px-6 py-4 whitespace-nowrap">
                            <div class="font-semibold text-left">Role(s)</div>
                        </th>
                      
                        <th class="px-6 py-4 whitespace-nowrap">
                            <div class="font-semibold text-center">Bookings</div>
                        </th>
                        <th class="px-6 py-4 whitespace-nowrap">
                            <div class="font-semibold text-left">Joined</div>
                        </th>
                        <th class="px-6 py-4 whitespace-nowrap">
                            <div class="font-semibold text-center">Actions</div>
                        </th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-100">
                    @foreach ($users as $user)
                    <tr class="table-row">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-slate-900">{{ $user->name }}</div>
                                    <div class="text-sm text-slate-500">{{ $user->email }}</div>
                                    <div class="text-xs text-slate-400">ID: {{ $user->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-wrap gap-1">
                                @if($user->roles->isNotEmpty())
                                    @foreach($user->roles as $role)
                                        <span class="role-badge role-{{ strtolower($role->name) }}">
                                            {{ ucfirst($role->name) }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="role-badge bg-gray-100 text-gray-800">No Role</span>
                                @endif
                            </div>
                        </td>
                      
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $user->bookings_count ?? $user->bookings->count() }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-slate-900">{{ $user->created_at->format('M d, Y') }}</div>
                            <div class="text-xs text-slate-500">{{ $user->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center justify-center space-x-2">
                                {{-- View User Details --}}
                                <a href="#" {{-- route('admin.users.show', $user->id) --}}
                                   class="table-action-button bg-slate-100 hover:bg-slate-200 text-slate-600"
                                   title="View Details">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                {{-- Edit User --}}
                                <a href="#" {{-- route('admin.users.edit', $user->id) --}}
                                   class="table-action-button bg-blue-100 hover:bg-blue-200 text-blue-600"
                                   title="Edit User">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L7 16H4v-3L14.586 4.586z"></path>
                                    </svg>
                                </a>
                                {{-- Toggle Status --}}
                                <form action="#" {{-- route('admin.users.toggle-status', $user->id) --}} method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="table-action-button {{ $user->email_verified_at ? 'bg-yellow-100 hover:bg-yellow-200 text-yellow-600' : 'bg-green-100 hover:bg-green-200 text-green-600' }}"
                                            title="{{ $user->email_verified_at ? 'Unverify User' : 'Verify User' }}">
                                        @if($user->email_verified_at)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        @endif
                                    </button>
                                </form>
                                {{-- Delete User --}}
                                <form action="#" {{-- route('admin.users.destroy', $user->id) --}} method="POST" onsubmit="return confirm('⚠️ Are you sure you want to delete {{ $user->name }}? This action cannot be undone and will remove all associated data.');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="table-action-button bg-red-100 hover:bg-red-200 text-red-600"
                                            title="Delete User">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-slate-900">No users found</h3>
                <p class="mt-1 text-sm text-slate-500">
                    @if(request()->hasAny(['search', 'role', 'status']))
                        Try adjusting your search or filter criteria.
                    @else
                        Get started by adding your first user.
                    @endif
                </p>
                @if(request()->hasAny(['search', 'role', 'status']))
                    <div class="mt-4">
                        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            Clear Filters
                        </a>
                    </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
    <div class="mt-8 flex items-center justify-between">
        <div class="text-sm text-slate-700">
            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results
        </div>
        <div class="pagination-wrapper">
            {{ $users->withQueryString()->links() }}
        </div>
    </div>
    @endif
</div>

<style>
    .pagination-wrapper .pagination {
        @apply flex items-center space-x-1;
    }
    .pagination-wrapper .page-link {
        @apply px-3 py-2 text-sm leading-tight text-slate-500 bg-white border border-slate-300 hover:bg-slate-100 hover:text-slate-700 rounded-lg transition-colors duration-200;
    }
    .pagination-wrapper .page-item.active .page-link {
        @apply bg-indigo-600 text-white border-indigo-600;
    }
</style>
@endsection

@push('scripts')
<script>
function exportUsers() {
    // Implement export functionality
    alert('Export functionality would be implemented here');
}

// Auto-submit form on filter change (optional)
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.querySelector('select[name="role"]');
    const statusSelect = document.querySelector('select[name="status"]');
    
    [roleSelect, statusSelect].forEach(select => {
        if (select) {
            select.addEventListener('change', function() {
                // Uncomment to auto-submit on change
                // this.form.submit();
            });
        }
    });
});
</script>
@endpush