/* ============================================
   REUSABLE PAGINATION MODULE
   ============================================ */
export function renderPagination(meta, callbackName = "loadData") {
    const box = document.getElementById("paginationBox");
    if (!box) return;

    const { page, totalPages, hasPrev, hasNext } = meta;

    // 🟩 Thêm CSS chỉ 1 lần
    if (!document.getElementById("paginationCss")) {
        const style = document.createElement("style");
        style.id = "paginationCss";

        style.textContent = `
            .btn-page {
                padding: 6px 14px;
                border-radius: 6px;
                border: none;
                cursor: pointer;
                background: var(--light-gradient) !important;
                color: white !important;
                font-weight: 500;
                margin: 0 6px;
                transition: 0.2s ease-in-out;
            }

            .btn-page:hover {
                filter: brightness(0.9);
            }

            .page-info {
                font-weight: bold;
                margin: 0 8px;
            }
        `;

        document.head.appendChild(style);
    }

    // 🟦 Render HTML
    let html = "";

    if (hasPrev) {
        html += `<button class="btn-page " onclick="${callbackName}(${page - 1})">Prev</button>`;
    }

    html += `<span class="page-info">Trang ${page} / ${totalPages}</span>`;

    if (hasNext) {
        html += `<button class="btn-page" onclick="${callbackName}(${page + 1})">Next</button>`;
    }

    box.innerHTML = html;
}
