// ✅ Notifikasi Tambah ke Keranjang (muncul dari kanan, warna biru, animasi 0.3s)
document.addEventListener("DOMContentLoaded", () => {
  const addToCartBtn = document.getElementById("addToCartBtn");

  if (addToCartBtn) {
    addToCartBtn.addEventListener("click", () => {
      // Buat elemen notifikasi
      const notif = document.createElement("div");
      notif.classList.add("cart-notification");
      notif.textContent = "Telah berhasil ditambahkan ke keranjang";

      // Tambahkan ke body
      document.body.appendChild(notif);

      // Tampilkan dengan animasi (muncul dari kanan)
      setTimeout(() => {
        notif.classList.add("show");
      }, 10);

      // Hapus otomatis setelah 3 detik
      setTimeout(() => {
        notif.classList.remove("show");
        setTimeout(() => {
          notif.remove();
        }, 300); // waktu transisi 0.3 detik
      }, 3000);
    });
  }
});
