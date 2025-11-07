// assets/js/user/notification.js
document.addEventListener("DOMContentLoaded", () => {
  const addToCartLinks = document.querySelectorAll('a[href*="page=cart&action=add&id="]');

  addToCartLinks.forEach(link => {
    link.addEventListener("click", async (e) => {
      e.preventDefault(); // jangan pindah halaman
      const url = link.href;

      try {
        const res = await fetch(url, {
          headers: { "X-Requested-With": "XMLHttpRequest" }
        });
        const data = await res.json();

        showToast(data.message, data.status);
      } catch (err) {
        console.error("Gagal menambahkan ke keranjang:", err);
        showToast("Terjadi kesalahan, coba lagi.", "error");
      }
    });
  });
});

/**
 * Fungsi menampilkan toast notification di pojok kanan bawah
 */
function showToast(message, status = "success") {
  // buat elemen
  const toast = document.createElement("div");
  toast.className = `toast ${status}`;
  toast.innerText = message;

  // tambahkan ke body
  document.body.appendChild(toast);

  // animasi masuk
  setTimeout(() => toast.classList.add("show"), 50);

  // hilang otomatis
  setTimeout(() => {
    toast.classList.remove("show");
    setTimeout(() => toast.remove(), 500);
  }, 3000);
}
