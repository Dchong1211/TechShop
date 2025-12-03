/* ============================================
   REUSABLE PAGINATION MODULE
   ============================================ */
export function renderPagination(meta, callbackName = "loadData") {
    const box = document.getElementById("paginationBox");
    if (!box) return;

    const { page, totalPages, hasPrev, hasNext } = meta;

    let html = "";

    if (hasPrev) {
        html += `<button class="btn-page" onclick="${callbackName}(${page - 1})">Prev</button>`;
    }

    html += `<span class="page-info">Trang ${page} / ${totalPages}</span>`;

    if (hasNext) {
        html += `<button class="btn-page" onclick="${callbackName}(${page + 1})">Next</button>`;
    }

    box.innerHTML = html;
}
