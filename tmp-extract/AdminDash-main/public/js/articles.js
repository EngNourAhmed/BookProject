document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("toggleBtn");
    const mobileToggle = document.getElementById("mobileToggle");
    const mainContent = document.getElementById("mainContent");
    const hasSubmenuItems = document.querySelectorAll(".has-submenu");

    // تبديل حالة السايدبار (مطوي/ممتد)
    toggleBtn.addEventListener("click", function () {
        sidebar.classList.toggle("collapsed");
        mainContent.classList.toggle("expanded");

        // تغيير اتجاه السهم
        const icon = toggleBtn.querySelector("i");
        if (sidebar.classList.contains("collapsed")) {
            icon.classList.remove("bi-chevron-right");
            icon.classList.add("bi-chevron-left");
        } else {
            icon.classList.remove("bi-chevron-left");
            icon.classList.add("bi-chevron-right");
        }
    });

    // فتح/إغلاق السايدبار في الشاشات الصغيرة
    mobileToggle.addEventListener("click", function () {
        sidebar.classList.toggle("mobile-open");
    });

    // التعامل مع القوائم الفرعية
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
                        alert("❌ خطأ في البيانات المرسلة: " + error.message);
                    } else if (error.message.includes("403")) {
                        alert("❌ ليس لديك صلاحية لهذا الإجراء");
                    } else if (error.message.includes("401")) {
                        alert("❌ يجب تسجيل الدخول أولاً");
                        window.location.href = "/login";
                    } else {
                        alert("❌ حدث خطأ أثناء التحديث: " + error.message);
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
                return "نشط";
            case "suspended":
                return "موقوف مؤقتًا";
            case "banned":
                return "محظور";
            default:
                return status;
        }
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.querySelector('input[name="search"]');
    const resultList = document.getElementById("resultList");
    const searchBox = document.getElementById("searchResults");
    const loading = document.getElementById("searchLoading");
    const articlesTable = document.querySelector(".users-table tbody");
    const originalRows = articlesTable.innerHTML; // حفظ الصفوف الأصلية

    let delayTimer;

    searchInput.addEventListener("input", function () {
        const query = this.value.trim().toLowerCase();

        // إخفاء نتائج البحث السابقة
        searchBox.style.display = "none";
        resultList.innerHTML = "";

        // إذا كان البحث فارغاً، أعرض جميع الصفوف
        if (query.length === 0) {
            articlesTable.innerHTML = originalRows;
            return;
        }

        // إظهار اللودينج
        loading.style.display = "flex";

        clearTimeout(delayTimer);
        delayTimer = setTimeout(() => {
            // إنشاء مجموعة الصفوف الأصلية
            const rows = document.createElement("tbody");
            rows.innerHTML = originalRows;
            const allRows = Array.from(rows.querySelectorAll("tr"));

            // تصفية الصفوف بناءً على البحث
            const filteredRows = allRows.filter((row) => {
                // البحث في جميع خلايا الصف
                const cells = row.querySelectorAll("td");
                for (let cell of cells) {
                    if (cell.textContent.toLowerCase().includes(query)) {
                        return true;
                    }
                }
                return false;
            });

            // إخفاء اللودينج
            loading.style.display = "none";

            if (filteredRows.length === 0) {
                // لا توجد نتائج
                articlesTable.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            لا توجد نتائج لـ "${query}"
                        </td>
                    </tr>
                `;
            } else {
                // عرض النتائج المصفاة
                articlesTable.innerHTML = "";
                filteredRows.forEach((row) => {
                    articlesTable.appendChild(row);
                });
            }

            // إخفاء الترقيم الصفحي عند البحث
            const paginationDiv = document.querySelector(
                ".d-flex.justify-content-between.align-items-center.mt-3"
            );
            if (paginationDiv) {
                paginationDiv.style.display =
                    query.length > 0 ? "none" : "flex";
            }

            // حفظ كلمة البحث الحديثة
            saveRecentSearch(query);
        }, 500);
    });

    // وظيفة لحفظ عمليات البحث الحديثة
    function saveRecentSearch(query) {
        let recentSearches = JSON.parse(
            localStorage.getItem("articleRecentSearches") || "[]"
        );

        // إزالة إذا كانت موجودة مسبقاً
        recentSearches = recentSearches.filter((item) => item !== query);

        // إضافة في البداية
        recentSearches.unshift(query);

        // حفظ فقط آخر 5 عمليات بحث
        recentSearches = recentSearches.slice(0, 5);

        localStorage.setItem(
            "articleRecentSearches",
            JSON.stringify(recentSearches)
        );
        displayRecentSearches();
    }

    // عرض عمليات البحث الحديثة
    function displayRecentSearches() {
        const recentTags = document.getElementById("recentTags");
        const recentSearches = JSON.parse(
            localStorage.getItem("articleRecentSearches") || "[]"
        );

        recentTags.innerHTML = "";

        recentSearches.forEach((search) => {
            const tag = document.createElement("div");
            tag.className = "recent-tag";
            tag.innerHTML = `
                <span>${search}</span>
                <i class="bi bi-x remove-search" data-search="${search}"></i>
            `;
            recentTags.appendChild(tag);
        });

        // إضافة أحداث النقر للعلامات
        document.querySelectorAll(".recent-tag").forEach((tag) => {
            tag.addEventListener("click", function (e) {
                if (!e.target.classList.contains("remove-search")) {
                    const searchText = this.querySelector("span").textContent;
                    searchInput.value = searchText;
                    searchInput.dispatchEvent(new Event("input"));
                }
            });
        });

        // أحداث إزالة البحث
        document.querySelectorAll(".remove-search").forEach((btn) => {
            btn.addEventListener("click", function (e) {
                e.stopPropagation();
                const searchToRemove = this.dataset.search;
                let recentSearches = JSON.parse(
                    localStorage.getItem("articleRecentSearches") || "[]"
                );
                recentSearches = recentSearches.filter(
                    (item) => item !== searchToRemove
                );
                localStorage.setItem(
                    "articleRecentSearches",
                    JSON.stringify(recentSearches)
                );
                displayRecentSearches();
            });
        });
    }

    // مسح جميع عمليات البحث الحديثة
    document
        .getElementById("clearRecent")
        ?.addEventListener("click", function () {
            localStorage.removeItem("articleRecentSearches");
            displayRecentSearches();
        });

    // عرض عمليات البحث الحديثة عند التركيز على حقل البحث
    searchInput.addEventListener("focus", function () {
        const recentSearches = JSON.parse(
            localStorage.getItem("articleRecentSearches") || "[]"
        );
        if (recentSearches.length > 0) {
            searchBox.style.display = "block";
        }
        displayRecentSearches();
    });

    // إخفاء نتائج البحث عند النقر خارجها
    document.addEventListener("click", function (e) {
        if (!searchInput.contains(e.target) && !searchBox.contains(e.target)) {
            searchBox.style.display = "none";
        }
    });

    // عرض عمليات البحث عند تحميل الصفحة
    displayRecentSearches();

    // وظيفة لإعادة عرض جميع البيانات (عند الحاجة)
    function resetSearch() {
        searchInput.value = "";
        articlesTable.innerHTML = originalRows;
        const paginationDiv = document.querySelector(
            ".d-flex.justify-content-between.align-items-center.mt-3"
        );
        if (paginationDiv) {
            paginationDiv.style.display = "flex";
        }
        searchBox.style.display = "none";
    }

    // يمكنك إضافة زر إعادة تعيين إذا أردت
    // <button id="resetSearch" class="btn btn-sm btn-outline-secondary">إعادة تعيين البحث</button>
    document
        .getElementById("resetSearch")
        ?.addEventListener("click", resetSearch);
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

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".confirm-action").forEach((btn) => {
        btn.addEventListener("click", function () {
            const formId = this.dataset.form;
            const title = this.dataset.title;
            const text = this.dataset.text;
            const confirmBtn = this.dataset.confirm;

            Swal.fire({
                title: title,
                text: text,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: confirmBtn,
                cancelButtonText: "إلغاء",
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        });
    });
});
