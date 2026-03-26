document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const mobileToggle = document.getElementById("mobileToggle");
    const mainContent = document.querySelector(".main-content");
    
    // تبديل حالة السايدبار (مطوي/ممتد)
    // Sidebar resize/collapse logic removed per user request for a static 280px sidebar.

    // فتح/إغلاق السايدبار في الشاشات الصغيرة
    mobileToggle.addEventListener("click", function () {
        sidebar.classList.toggle("mobile-open");
    });

    // التعامل مع القوائم الفرعية
    const hasSubmenuItems = document.querySelectorAll(".has-submenu");
    hasSubmenuItems.forEach((item) => {
        const link = item.querySelector(".nav-link");

        link.addEventListener("click", function (e) {
            if (
                window.innerWidth > 992 ||
                !sidebar.classList.contains("collapsed")
            ) {
                e.preventDefault();
                item.classList.toggle("active");
            }
        });
    });

    // إغلاق السايدبار عند النقر على رابط في الشاشات الصغيرة
    const navLinks = document.querySelectorAll(".sidebar-nav .nav-link");
    navLinks.forEach((link) => {
        link.addEventListener("click", function () {
            if (
                window.innerWidth <= 992 &&
                sidebar.classList.contains("mobile-open")
            ) {
                sidebar.classList.remove("mobile-open");
            }
        });
    });
});

// تحديث عرض الجدول عند تحميل الصفحة
window.addEventListener("load", function () {
    const event = new Event("resize");
    window.dispatchEvent(event);
});

const token = localStorage.getItem("authToken");

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".status-select").forEach((select) => {
        select.addEventListener("change", function () {
            const form = this.closest(".update-status-form");
            const formData = new FormData(form);

            // تأكيد التغيير
            if (!confirm("هل أنت متأكد من تغيير حالة المستخدم؟")) {
                this.value = this.dataset.previousValue;
                return;
            }

            this.disabled = true;

            // استخدم POST لأن Laravel يتوقع POST مع _method field
            fetch(form.action, {
                method: "POST", // استخدم POST هنا
                body: formData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                    Accept: "application/json",
                    ...(token ? { Authorization: `Bearer ${token}` } : {}),
                },
            })
                .then(async (response) => {
                    const contentType = response.headers.get("content-type");

                    if (!response.ok) {
                        // الحصول على رسالة الخطأ من الـ response
                        if (
                            contentType &&
                            contentType.includes("application/json")
                        ) {
                            const errorData = await response.json();
                            throw new Error(
                                errorData.message ||
                                `HTTP error! status: ${response.status}`
                            );
                        } else {
                            const text = await response.text();
                            console.error(
                                "Server returned HTML:",
                                text.substring(0, 200)
                            );
                            throw new Error(
                                `HTTP error! status: ${response.status}`
                            );
                        }
                    }

                    if (
                        !contentType ||
                        !contentType.includes("application/json")
                    ) {
                        const text = await response.text();
                        console.error(
                            "Server returned HTML instead of JSON:",
                            text.substring(0, 200)
                        );
                        throw new Error("Server did not return JSON");
                    }

                    return response.json();
                })
                .then((data) => {
                    this.disabled = false;

                    if (data.status) {
                        alert("✓ " + data.message);
                        updateRowStatus(this, data.user);
                    } else {
                        alert("✗ " + data.message);
                        this.value = this.dataset.previousValue;
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    this.disabled = false;

                    if (error.message.includes("422")) {
                        alert("❌ Error in sent data: " + error.message);
                    } else if (error.message.includes("403")) {
                        alert("❌ You do not have permission for this action");
                    } else if (error.message.includes("401")) {
                        alert("❌ You must login first");
                        window.location.href = "/login";
                    } else {
                        alert("❌ An error occurred during update: " + error.message);
                    }

                    this.value = this.dataset.previousValue;
                });
        });

        // حفظ القيمة الأولية
        select.dataset.previousValue = select.value;
    });

    function updateRowStatus(select, userData) {
        const row = select.closest("tr");
        const status = userData.status || select.value;

        // تحديث البادج الموجود في خلية "حالة المستخدم"
        const statusCell = row.querySelector("td:nth-child(3)");
        if (statusCell) {
            const badge = statusCell.querySelector(".badge");
            if (badge) {
                // تحديث النص واللون
                badge.textContent = getStatusText(status);
                badge.className = "badge " + getStatusClass(status);
            }
        }
    }

    function getStatusClass(status) {
        switch (status) {
            case "active":
                return "bg-success";
            case "suspended":
                return "bg-warning";
            case "banned":
                return "bg-danger";
            default:
                return "bg-secondary";
        }
    }

    function getStatusText(status) {
        switch (status) {
            case "active":
                return "Active";
            case "suspended":
                return "Suspended";
            case "banned":
                return "Banned";
            default:
                return status;
        }
    }
});

document.addEventListener("DOMContentLoaded", function () {
    function fetchUsers(url) {
        const overlay = document.getElementById("tableLoadingOverlay");
        if (overlay) overlay.classList.add("active");

        fetch(url, {
            headers: { "X-Requested-With": "XMLHttpRequest" },
        })
            .then((res) => res.json())
            .then((data) => {
                document.getElementById("users-content").innerHTML = data.html;
                attachPaginationEvents(); // إعادة تفعيل روابط الصفحات
            })
            .catch(console.error)
            .finally(() => {
                if (overlay) overlay.classList.remove("active");
            });
    }

    // Search — with debounce for better performance
    let searchTimeout;
    const searchInput = document.querySelector('input[name="search"]');
    const searchNavbar = document.querySelector('.search-navbar');
    
    if (searchInput) {
        searchInput.addEventListener("input", function () {
            clearTimeout(searchTimeout);
            
            // Show loading state immediately for responsiveness
            if (this.value.length > 0) {
                searchNavbar.classList.add('searching');
            } else {
                searchNavbar.classList.remove('searching');
            }

            searchTimeout = setTimeout(() => {
                let url = window.location.pathname + "?search=" + encodeURIComponent(this.value);
                fetchUsers(url);
            }, 300); // 300ms debounce
        });
    }

    // تفعيل روابط الباجينيشن AJAX
    function attachPaginationEvents() {
        document
            .querySelectorAll("#users-content .pagination a")
            .forEach((link) => {
                link.addEventListener("click", function (e) {
                    e.preventDefault();
                    fetchUsers(this.href);
                });
            });
    }

    attachPaginationEvents();
});

document.addEventListener("DOMContentLoaded", function () {
    // عند تغيير عدد العناصر في الصفحة
    document
        .querySelector(".page-size-selector select")
        ?.addEventListener("change", function () {
            const perPage = this.value;
            const url = new URL(window.location.href);
            url.searchParams.set("per_page", perPage);
            url.searchParams.set("page", 1); // العودة للصفحة الأولى
            window.location.href = url.toString();
        });

    // تحسين عرض Pagination للأجهزة المحمولة
    function adjustPaginationForMobile() {
        const pagination = document.querySelector(".pagination");
        if (!pagination) return;

        if (window.innerWidth < 768) {
            pagination.classList.add("pagination-sm");
            // إخفاء بعض الأرقام في الأجهزة الصغيرة
            const pageItems = pagination.querySelectorAll(
                ".page-item:not(.active):not(.disabled)"
            );
            pageItems.forEach((item, index) => {
                if (index > 2 && index < pageItems.length - 3) {
                    item.style.display = "none";
                }
            });
        } else {
            pagination.classList.remove("pagination-sm");
            // إظهار جميع الأرقام
            pagination.querySelectorAll(".page-item").forEach((item) => {
                item.style.display = "";
            });
        }
    }

    // تعديل عند تحميل الصفحة وعند تغيير الحجم
    adjustPaginationForMobile();
    window.addEventListener("resize", adjustPaginationForMobile);

    // إضافة تأثير عند النقر على الروابط
    document.querySelectorAll(".page-link").forEach((link) => {
        link.addEventListener("click", function () {
            // يمكنك إضافة تأثير تحميل هنا
            const loading = document.createElement("div");
            loading.className = "pagination-loading";
            loading.innerHTML =
                '<div class="spinner-border spinner-border-sm text-primary"></div>';
            this.appendChild(loading);
        });
    });
});
