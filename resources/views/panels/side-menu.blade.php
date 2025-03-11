<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <!-- Logo Section -->
    <div class="app-brand demo">
        <div class="d-flex align-items-center">
            <i class="ti ti-apps fs-3 text-primary"></i>
            <h4 class="mb-0 ms-2">Skill App</h4>
        </div>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-md align-middle"></i>
        </a>
    </div>
    
    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-3">
        <!-- Main Navigation Header -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Main Navigation</span>
        </li>

        <!-- Dashboard -->
        <li class="menu-item {{ Route::is('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>

        <!-- Admission Management -->
        <li class="menu-item {{ Route::is('student*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-users"></i>
                <div data-i18n="Admission Management">Admission Management</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Route::is('student') ? 'active' : '' }}">
                    <a href="{{ route('student') }}" class="menu-link">
                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                        <div data-i18n="Students">Students</div>
                    </a>
                </li>
                <li class="menu-item {{ Route::is('studentcourse') ? 'active' : '' }}">
                    <a href="{{ route('studentcourse') }}" class="menu-link">
                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                        <div data-i18n="Student Course">Student Course</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Divider -->
        <li class="menu-divider"></li>

        <!-- Course Management -->
        <li class="menu-item {{ Route::is('coursetype*', 'category*', 'course*', 'subject*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-book"></i>
                <div data-i18n="Course Management">Course Management</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Route::is('coursetype') ? 'active' : '' }}">
                    <a href="{{ route('coursetype') }}" class="menu-link">
                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                        <div data-i18n="Course Type">Course Type</div>
                    </a>
                </li>
                <li class="menu-item {{ Route::is('category') ? 'active' : '' }}">
                    <a href="{{ route('category') }}" class="menu-link">
                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                        <div data-i18n="Course Category">Course Category</div>
                    </a>
                </li>
                <li class="menu-item {{ Route::is('course') ? 'active' : '' }}">
                    <a href="{{ route('course') }}" class="menu-link">
                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                        <div data-i18n="Courses">Courses</div>
                    </a>
                </li>
                <li class="menu-item {{ Route::is('subject') ? 'active' : '' }}">
                    <a href="{{ route('subject') }}" class="menu-link">
                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                        <div data-i18n="Subjects">Subjects</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Content Management -->
        <li class="menu-item {{ Route::is('subjectvideo*', 'subjectnote*', 'ebook*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-file-text"></i>
                <div data-i18n="Content Management">Content Management</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Route::is('subjectvideo') ? 'active' : '' }}">
                    <a href="{{ route('subjectvideo') }}" class="menu-link">
                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                        <div data-i18n="Videos">Videos</div>
                    </a>
                </li>
                <li class="menu-item {{ Route::is('subjectnote') ? 'active' : '' }}">
                    <a href="{{ route('subjectnote') }}" class="menu-link">
                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                        <div data-i18n="Notes">Notes</div>
                    </a>
                </li>
                <li class="menu-item {{ Route::is('ebook') ? 'active' : '' }}">
                    <a href="{{ route('ebook') }}" class="menu-link">
                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                        <div data-i18n="E-books">E-books</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Payment Management -->
        <li class="menu-item {{ Route::is('payment*') ? 'active' : '' }}">
            <a href="{{ route('payment') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-currency-rupee"></i>
                <div data-i18n="Payment Management">Payment Management</div>
            </a>
        </li>

        <!-- Support System -->
        <li class="menu-item {{ Route::is('news*', 'slider*', 'faq*', 'studentquery*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-headset"></i>
                <div data-i18n="Support System">Support System</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Route::is('news') ? 'active' : '' }}">
                    <a href="{{ route('news') }}" class="menu-link">
                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                        <div data-i18n="News Updates">News Updates</div>
                    </a>
                </li>
                <li class="menu-item {{ Route::is('slider') ? 'active' : '' }}">
                    <a href="{{ route('slider') }}" class="menu-link">
                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                        <div data-i18n="Slider Management">Slider Management</div>
                    </a>
                </li>
                <li class="menu-item {{ Route::is('faq') ? 'active' : '' }}">
                    <a href="{{ route('faq') }}" class="menu-link">
                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                        <div data-i18n="FAQs">FAQs Management</div>
                    </a>
                </li>
                <li class="menu-item {{ Route::is('studentquery') ? 'active' : '' }}">
                    <a href="{{ route('studentquery') }}" class="menu-link">
                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                        <div data-i18n="Student Queries">Student Queries</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- System Settings -->
        <li class="menu-header small text-uppercase mt-4">
            <span class="menu-header-text">System Settings</span>
        </li>

        <!-- Permissions -->
        <li class="menu-item {{ Route::is('users*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-shield-check"></i>
                <div data-i18n="Permissions">Permissions</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Route::is('users') ? 'active' : '' }}">
                    <a href="{{ route('users') }}" class="menu-link">
                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                        <div data-i18n="Users">Users</div>
                    </a>
                </li>
                <li class="menu-item {{ Route::is('users.permissions') ? 'active' : '' }}">
                    <a href="{{ route('users.permissions') }}" class="menu-link">
                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                        <div data-i18n="User Permissions">User Permissions</div>
                    </a>
                </li>
                <li class="menu-item {{ Route::is('users.roles') ? 'active' : '' }}">
                    <a href="{{ route('users.roles') }}" class="menu-link">
                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                        <div data-i18n="Role Permissions">Role Permissions</div>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</aside>

<style>
    .menu-bullet .bullet {
        background-color: currentColor;
        width: 4px;
        height: 4px;
    }
    .menu-item.active > .menu-link {
        background: rgba(var(--bs-primary-rgb), 0.1);
        color: var(--bs-primary);
    }
    .menu-sub .menu-item.active .menu-link {
        font-weight: 500;
    }
    .menu-divider {
        height: 1px;
        background-color: rgba(0,0,0,0.1);
        margin: 0.5rem 1rem;
    }
    .menu-header {
        opacity: 0.6;
        pointer-events: none;
    }
</style>

<!-- Home Icons:

ti ti-home — Home
ti ti-home-outline — Home Outline
User and Account Icons:

ti ti-user — User
ti ti-user-outline — User Outline
ti ti-user-plus — User Plus
ti ti-user-minus — User Minus
ti ti-users — Users
Settings and Tools:

ti ti-settings — Settings
ti ti-cog — Cog
ti ti-wrench — Wrench
ti ti-tool — Tool
Navigation and Menu:

ti ti-menu — Menu
ti ti-arrow-right — Arrow Right
ti ti-arrow-left — Arrow Left
ti ti-arrow-up — Arrow Up
ti ti-arrow-down — Arrow Down
Social Media Icons:

ti ti-facebook — Facebook
ti ti-twitter — Twitter
ti ti-instagram — Instagram
ti ti-linkedin — LinkedIn
ti ti-youtube — YouTube
ti ti-pinterest — Pinterest
Content and Document:

ti ti-pencil — Pencil
ti ti-clipboard — Clipboard
ti ti-file — File
ti ti-folder — Folder
ti ti-cloud — Cloud
Media and Multimedia:

ti ti-video-camera — Video Camera
ti ti-music — Music
ti ti-headphone — Headphone
ti ti-volume-up — Volume Up
ti ti-volume-down — Volume Down
ti ti-volume-off — Volume Off
File Management:

ti ti-download — Download
ti ti-upload — Upload
ti ti-trash — Trash
ti ti-folder-open — Open Folder
Interface and Design:

ti ti-paint — Paint
ti ti-font — Font
ti ti-brush — Brush
ti ti-text — Text
Alerts and Notifications:

ti ti-bell — Bell
ti ti-bell-off — Bell Off
ti ti-alert — Alert
ti ti-alert-alt — Alert Alternative
Miscellaneous:

ti ti-search — Search
ti ti-close — Close
ti ti-check — Check
ti ti-close-circle — Close Circle
ti ti-refresh — Refresh
ti ti-reload — Reload
ti ti-time — Time -->