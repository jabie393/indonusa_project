document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("[data-realtime-table-search]").forEach((form) => {
        const inputSelector = form.dataset.searchInput || '[name="search"]';
        const targetSelector = form.dataset.searchTarget || "#tableContainer";
        const paginationSelector = form.dataset.paginationTarget || "#pagination-nav";
        const extraSelector = form.dataset.extraFields || "";
        const debounceMs = Number(form.dataset.debounce || 300);
        const updateUrl = form.dataset.updateUrl !== "false";

        const input = form.querySelector(inputSelector) || document.querySelector(inputSelector);
        const target = document.querySelector(targetSelector);
        const pagination = document.querySelector(paginationSelector);

        if (!input || !target) {
            return;
        }

        let timeoutId;
        let controller;

        const buildParams = () => {
            const params = new URLSearchParams(new FormData(form));

            if (extraSelector) {
                document.querySelectorAll(extraSelector).forEach((field) => {
                    if (field.name && !field.disabled) {
                        params.set(field.name, field.value);
                    }
                });
            }

            params.delete("page");

            return params;
        };

        const replaceFromResponse = (html, url) => {
            const doc = new DOMParser().parseFromString(html, "text/html");
            const nextTarget = doc.querySelector(targetSelector);
            const nextPagination = pagination ? doc.querySelector(paginationSelector) : null;

            if (!nextTarget) {
                form.submit();
                return;
            }

            target.innerHTML = nextTarget.innerHTML;

            if (pagination && nextPagination) {
                pagination.innerHTML = nextPagination.innerHTML;
            }

            if (updateUrl) {
                window.history.replaceState({}, "", url);
            }
        };

        const runSearch = () => {
            const params = buildParams();
            const url = `${form.action}?${params.toString()}`;

            if (controller) {
                controller.abort();
            }

            controller = new AbortController();

            fetch(url, {
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                },
                signal: controller.signal,
            })
                .then((response) => response.text())
                .then((html) => replaceFromResponse(html, url))
                .catch((error) => {
                    if (error.name !== "AbortError") {
                        form.submit();
                    }
                });
        };

        const queueSearch = () => {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(runSearch, debounceMs);
        };

        input.addEventListener("input", queueSearch);

        form.addEventListener("submit", (event) => {
            event.preventDefault();
            clearTimeout(timeoutId);
            runSearch();
        });
    });
});
