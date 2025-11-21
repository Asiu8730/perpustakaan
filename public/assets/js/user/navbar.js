// assets/js/user/navbar.js
document.addEventListener('DOMContentLoaded', function(){
    const searchInput = document.getElementById("searchInput");
    const searchResults = document.getElementById("searchResults");

    if (!searchInput) return;

    searchInput.addEventListener("keyup", () => {
        let q = searchInput.value.trim();

        if (q.length === 0) {
            searchResults.style.display = "none";
            return;
        }

        fetch(`/reca/perpustakaan/public/search_api.php?q=` + encodeURIComponent(q))
            .then(res => res.json())
            .then(data => {
                searchResults.innerHTML = "";
                searchResults.style.display = "block";

                if (data.length === 0) {
                    searchResults.innerHTML = `<div class="search-item">Tidak ada hasil</div>`;
                    return;
                }

                data.forEach(book => {
                    const item = document.createElement("a");
                    item.classList.add("search-item");
                    item.href = `/reca/perpustakaan/public/dashboard_user.php?page=book_detail&id=${book.id}`;

                    item.innerHTML = `
                        <img src="/reca/perpustakaan/uploads/covers/${book.cover || 'no_cover.png'}" class="search-thumb">
                        <div class="search-meta">
                            <div class="search-text">${book.title}</div>
                            <div class="search-author">${book.author}</div>
                        </div>
                    `;
                    searchResults.appendChild(item);
                });
            }).catch(err => {
                console.error(err);
                searchResults.style.display = "none";
            });
    });

    // klik luar sembunyikan dropdown
    document.addEventListener("click", (e) => {
        if (!e.target.closest(".navbar-center")) {
            searchResults.style.display = "none";
        }
    });
});


function toggleUserDropdown() {
    const dropdown = document.getElementById("userDropdown");
    dropdown.classList.toggle("show");
}
window.addEventListener("click", (event) => {
    if (!event.target.closest(".user-menu")) {
        document.getElementById("userDropdown")?.classList.remove("show");
    }
});