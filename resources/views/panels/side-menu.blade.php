<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo ">
        {{-- <img src="/assets/img/logo/logo.png" alt="" style="height: 41px"> --}}
        <h4>Skill App</h4>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-md align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>



    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-item active open">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div data-i18n="Dashboards">Dashboards</div>
                <div class="badge bg-danger rounded-pill ms-auto">5</div>
            </a>
            <ul class="menu-sub">

                <!-- <li class="menu-item active">
                    <a href="index-2.html" class="menu-link">
                        <div data-i18n="Analytics">Analytics</div>
                    </a>
                </li> -->
                <!-- <li class="menu-item">
                    <a href="dashboards-crm.html" class="menu-link">
                        <div data-i18n="CRM">CRM</div>
                    </a>
                </li> -->
                <!-- <li class="menu-item">
                    <a href="app-ecommerce-dashboard.html" class="menu-link">
                        <div data-i18n="eCommerce">eCommerce</div>
                    </a>
                </li> -->
                <!-- <li class="menu-item">
                    <a href="app-logistics-dashboard.html" class="menu-link">
                        <div data-i18n="Logistics">Logistics</div>
                    </a>
                </li> -->
                <!-- <li class="menu-item">
                    <a href="app-academy-dashboard.html" class="menu-link">
                        <div data-i18n="Academy">Academy</div>
                    </a>
                </li> -->
            </ul>
        </li>

        <!-- Layouts -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-users"></i>
                <div data-i18n="Admission Management">Admission Management</div>
            </a>

            <ul class="menu-sub">

                <li class="menu-item">
                    <a href="{{ route('student') }}" class="menu-link">
                        <div data-i18n="Students">Students</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Student Course">Student Course</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-layout-sidebar"></i>
                <div data-i18n="Course Management">Course Management</div>
            </a>

            <ul class="menu-sub">

                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Courses">Courses</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Subjects">Subjects</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Student Course">Student Course</div>
                    </a>
                </li>

            </ul>
        </li>
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-layout-sidebar"></i>
                <div data-i18n="Payment Management">Payment Management</div>
            </a>

            <ul class="menu-sub">

                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Student Payment">Student Payment</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Front Pages -->
        {{-- <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class='menu-icon tf-icons ti ti-files'></i>
                <div data-i18n="Admissions">Admissions</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Applications">Applications</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Apply Fresh">Apply Fresh</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link" target="_blank">
                        <div data-i18n="Re-Registrations">Re-Registrations</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link" target="_blank">
                        <div data-i18n="Back Paper">Back Paper</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link" target="_blank">
                        <div data-i18n="Results">Results</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link" target="_blank">
                        <div data-i18n="Exam Schedule">Exam Schedule</div>
                    </a>
                </li>
            </ul>
        </li> --}}


        <!-- Apps & Pages -->
        <!-- <li class="menu-header small">
            <span class="menu-header-text" data-i18n="Apps & Pages">Apps &amp; Pages</span>
        </li> -->
        {{-- <li class="menu-item">
            <a href="Settings" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-mail"></i>
                <div data-i18n="Settings">Settings</div>
            </a>
            <ul class="menu-sub">

                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Vertical">Vertical</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Admission Types">Admission Types</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Eligibility">Eligibility</div>
                    </a>
                </li>



                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Modes">Modes</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Schemes">Schemes</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Admission Session">Admission Session</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Exam Session">Exam Session</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Program Type">Program Type</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Fee Structure">Fee Structure</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="Admissions/Exam Schedule" class="menu-link" target="_blank">
                        <div data-i18n="Page Access">Page Access</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item">
            <a href="" class="menu-link">
                <i class="menu-icon tf-icons ti ti-messages"></i>
                <div data-i18n="Notifications">Notifications</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="" class="menu-link">
                <i class="menu-icon tf-icons ti ti-messages"></i>
                <div data-i18n="Center Notifications">Center Notifications</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="" class="menu-link">
                <i class="menu-icon tf-icons ti ti-messages"></i>
                <div data-i18n="Student Syllabus">Student Syllabus</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="" class="menu-link">
                <i class="menu-icon tf-icons ti ti-messages"></i>
                <div data-i18n="Student Profile">Student Profile</div>
            </a>
        </li> --}}

        <!-- accounts menu start -->
        {{-- <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class='menu-icon tf-icons ti ti-book'></i>
                <div data-i18n="Accounts">Accounts</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="Accounts/Center Ledgers" class="menu-link">
                        <div data-i18n="Center Ledgers">Center Ledgers</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="Accounts/Offline Payments" class="menu-link">
                        <div data-i18n="Offline Payments">Offline Payments</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="Accounts/Online Payments" class="menu-link">
                        <div data-i18n="Online Payments">Online Payments </div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="Accounts/Students Ledgers" class="menu-link">
                        <div data-i18n="Students Ledgers">Students Ledgers</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="Accounts/Wallet Payments" class="menu-link">
                        <div data-i18n="Wallet Payments">Wallet Payments</div>
                    </a>
                </li>
            </ul>
        </li> --}}
        <!-- users menu end -->
        {{-- <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class='menu-icon tf-icons ti ti-truck'></i>
                <div data-i18n="Users">Users</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="User Managers">User Managers</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Board Managers">Board Managers</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Operations">Operations</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <div data-i18n="Counsellor">Counsellor</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="Users/Sub-Counsellor" class="menu-link">
                        <div data-i18n="Sub-Counsellor">Sub-Counsellor</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="Users/Center Masters" class="menu-link">
                        <div data-i18n="Center Masters">Center Masters</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="Users/Centers" class="menu-link">
                        <div data-i18n="Centers">Centers</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="Users/Sub-Centers" class="menu-link">
                        <div data-i18n="Sub-Centers">Sub-Centers</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="Users/Accountants" class="menu-link">
                        <div data-i18n="Accountants">Accountants</div>
                    </a>
                </li>
            </ul>
        </li> --}}
        <!-- lms-setting menu end -->
        {{-- <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class='menu-icon tf-icons ti ti-file-dollar'></i>
                <div data-i18n="LMS-Settings">LMS-Settings</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="LMS-Settings/Subjects" class="menu-link">
                        <div data-i18n="Subjects">Subjects</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="LMS-Settings/Date Sheets" class="menu-link">
                        <div data-i18n="Date Sheets">Date Sheets</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="LMS-Settings/Assignments" class="menu-link">
                        <div data-i18n="Assignments">Assignments</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="LMS-Settings/Assignments Review" class="menu-link">
                        <div data-i18n="Assignments Review">Assignments Review</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="LMS-Settings/Practicals" class="menu-link">
                        <div data-i18n="Practicals">Practicals</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="LMS-Settings/Mock Test" class="menu-link">
                        <div data-i18n="Mock Test">Mock Test</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="LMS-Settings/Exam" class="menu-link">
                        <div data-i18n="Exam">Exam</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="LMS-Settings/Results" class="menu-link">
                        <div data-i18n="Results">Results</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="LMS-Settings/Queries & Feedback" class="menu-link">
                        <div data-i18n="Queries & Feedback">Queries & Feedback</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="LMS-Settings/E-Books" class="menu-link">
                        <div data-i18n="E-Books">E-Books</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="LMS-Settings/Videos" class="menu-link">
                        <div data-i18n="Videos">Videos</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="LMS-Settings/Question Banks" class="menu-link">
                        <div data-i18n="Question Banks">Question Banks</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="LMS-Settings/Dispatch" class="menu-link">
                        <div data-i18n="Dispatch">Dispatch</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="LMS-Settings/Documents" class="menu-link">
                        <div data-i18n="Documents">Documents</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="LMS-Settings/Contact Us" class="menu-link">
                        <div data-i18n="Contact Us">Contact Us</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="LMS-Settings/Download Center" class="menu-link">
                        <div data-i18n="Download Center">Download Center</div>
                    </a>
                </li>
            </ul>
        </li> --}}
        <!-- Permission menu start -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class='menu-icon tf-icons ti ti-settings'></i>
                <div data-i18n="Permission">Permission</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('users') }}" class="menu-link">
                        <div data-i18n="Users">Users</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('users.permissions') }}" class="menu-link">
                        <div data-i18n="User Permission">User Permission</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('users.roles') }}" class="menu-link">
                        <div data-i18n="Role Permission">Role Permission</div>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</aside>


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