document.addEventListener("DOMContentLoaded", () => {
  const borrowForm = document.getElementById("borrowForm");
  const borrowBtn = borrowForm?.querySelector('button[name="add_to_cart"]');
  const addToCartBtn = document.getElementById("addToCartBtn");

  // === Fungsi: Tampilkan notifikasi pojok kanan bawah ===
  function showNotification(message, color = "#4CAF50") {
    const old = document.querySelector(".notification-toast");
    if (old) old.remove();

    const notif = document.createElement("div");
    notif.className = "notification-toast";
    notif.textContent = message;
    Object.assign(notif.style, {
      position: "fixed",
      bottom: "30px",
      right: "30px",
      background: color,
      color: "#fff",
      padding: "12px 18px",
      borderRadius: "10px",
      boxShadow: "0 4px 8px rgba(0,0,0,0.2)",
      zIndex: "9999",
      fontSize: "14px",
      fontFamily: "Poppins, sans-serif",
      animation: "fadeInOut 3s ease",
    });
    document.body.appendChild(notif);
    setTimeout(() => notif.remove(), 3000);
  }

  // === Fungsi: Pop-up konfirmasi sederhana ===
  function showConfirmBox(message, onYes) {
    const overlay = document.createElement("div");
    overlay.className = "confirm-overlay";
    overlay.innerHTML = `
      <div class="confirm-box">
        <p>${message}</p>
        <div class="confirm-actions">
          <button id="confirmYes">Iya</button>
          <button id="confirmNo">Tidak</button>
        </div>
      </div>
    `;
    document.body.appendChild(overlay);

    // Style popup
    Object.assign(overlay.style, {
      position: "fixed",
      top: 0,
      left: 0,
      width: "100%",
      height: "100%",
      background: "rgba(0,0,0,0.4)",
      display: "flex",
      justifyContent: "center",
      alignItems: "center",
      zIndex: "10000",
    });

    const box = overlay.querySelector(".confirm-box");
    Object.assign(box.style, {
      background: "#fff",
      padding: "20px 30px",
      borderRadius: "10px",
      boxShadow: "0 4px 10px rgba(0,0,0,0.3)",
      textAlign: "center",
      width: "300px",
    });

    const yesBtn = overlay.querySelector("#confirmYes");
    const noBtn = overlay.querySelector("#confirmNo");
    yesBtn.style.cssText =
      "background:#4CAF50;color:#fff;border:none;padding:8px 15px;border-radius:6px;cursor:pointer;";
    noBtn.style.cssText =
      "background:#f44336;color:#fff;border:none;padding:8px 15px;border-radius:6px;cursor:pointer;";

    yesBtn.onclick = () => {
      document.body.removeChild(overlay);
      onYes();
    };
    noBtn.onclick = () => document.body.removeChild(overlay);
  }

  // === Event: Tombol "Pinjam Buku" ===
  if (borrowBtn) {
    borrowBtn.addEventListener("click", (e) => {
      e.preventDefault();
      showConfirmBox("Anda yakin ingin meminjam buku ini?", () => {
        showNotification("Permintaan peminjaman dikirim!", "#2196F3");
        setTimeout(() => borrowForm.submit(), 1000);
      });
    });
  }

  // === Event: Tombol "Tambah ke Keranjang" ===
  if (addToCartBtn) {
    addToCartBtn.addEventListener("click", () => {
      const bookId = addToCartBtn.dataset.id;
      showConfirmBox("Tambahkan buku ini ke keranjang?", async () => {
        try {
          const response = await fetch(
            `../public/dashboard_user.php?page=cart&action=add&id=${bookId}`,
            { headers: { "X-Requested-With": "XMLHttpRequest" } }
          );
          const result = await response.json();

          if (result.status === "exists") {
            showNotification("Buku sudah ada di keranjang", "#ff9800");
          } else if (result.status === "success") {
            showNotification("Buku berhasil ditambahkan ke keranjang");
          } else {
            showNotification("Gagal menambahkan buku ke keranjang", "#f44336");
          }
        } catch (err) {
          showNotification("Terjadi kesalahan, coba lagi", "#f44336");
        }
      });
    });
  }
});
