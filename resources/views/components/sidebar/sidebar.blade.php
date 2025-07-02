@php
    use App\Http\Controllers\MenuController;
    
    $user = Auth::user();
    $menus = MenuController::getMenuByRole($user->role);
    
    $sidebarColors = [
        'administrator' => 'bg-gray-800',
        'guru_bk' => 'bg-green-800', 
        'siswa' => 'bg-blue-800'
    ];
    
    $hoverColors = [
        'administrator' => 'hover:bg-gray-700',
        'guru_bk' => 'hover:bg-green-700',
        'siswa' => 'hover:bg-blue-700'
    ];
    
    $activeColors = [
        'administrator' => 'bg-gray-700',
        'guru_bk' => 'bg-green-700', 
        'siswa' => 'bg-blue-700'
    ];
    
    $sidebarColor = $sidebarColors[$user->role] ?? 'bg-gray-800';
    $hoverColor = $hoverColors[$user->role] ?? 'hover:bg-gray-700';
    $activeColor = $activeColors[$user->role] ?? 'bg-gray-700';
    
    $panelTitle = [
        'administrator' => 'Admin Panel',
        'guru_bk' => 'Guru BK Panel',
        'siswa' => 'Panel Siswa'
    ];
@endphp

<aside class="{{ $sidebarColor }} text-white w-64 min-h-screen p-4">
    <div class="flex items-center mb-8">
        <img src="{{ asset('images/logo-sekolah.png') }}" alt="Logo" class="h-8 w-8 mr-3">
        <h2 class="text-lg font-semibold">{{ $panelTitle[$user->role] }}</h2>
    </div>
    
    <nav class="space-y-2">
        @foreach($menus as $menu)
            @if($menu['type'] === 'single')
                <a href="{{ route($menu['route']) }}" 
                   class="flex items-center px-4 py-2 text-sm rounded-lg {{ $hoverColor }} {{ request()->routeIs($menu['route']) ? $activeColor : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $menu['icon'] }}"></path>
                    </svg>
                    {{ $menu['title'] }}
                </a>
            
            @elseif($menu['type'] === 'dropdown')
                @php
                    $isActive = false;
                    if (isset($menu['active_patterns'])) {
                        foreach($menu['active_patterns'] as $pattern) {
                            if (request()->routeIs($pattern)) {
                                $isActive = true;
                                break;
                            }
                        }
                    }
                @endphp
                
                <div class="space-y-1" x-data="{ open: {{ $isActive ? 'true' : 'false' }} }">
                    <button @click="open = !open" 
                            class="flex items-center w-full px-4 py-2 text-sm text-left rounded-lg {{ $hoverColor }} focus:outline-none {{ $isActive ? $activeColor : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $menu['icon'] }}"></path>
                        </svg>
                        {{ $menu['title'] }}
                        <svg class="w-4 h-4 ml-auto transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <div x-show="open" x-transition class="ml-8 space-y-1">
                        @foreach($menu['children'] as $child)
                            <a href="{{ route($child['route']) }}" 
                               class="block px-4 py-2 text-sm rounded-lg {{ $hoverColor }} {{ request()->routeIs($child['route']) ? $activeColor : '' }}">
                                {{ $child['title'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </nav>
</aside>

@push('scripts')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush