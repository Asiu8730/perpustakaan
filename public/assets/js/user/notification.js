function showNotification(message) {
  const existing = document.querySelector(".notification-toast");
  if (existing) existing.remove();

  const notif = document.createElement("div");
  notif.className = "notification-toast";
  notif.textContent = message;

  Object.assign(notif.style, {
    position: "fixed",
    bottom: "30px",
    right: "30px",
    background: "#4CAF50",
    color: "#fff",
    padding: "12px 20px",
    borderRadius: "10px",
    boxShadow: "0 4px 8px rgba(0,0,0,0.2)",
    zIndex: "9999",
    fontSize: "14px",
    animation: "fadeInOut 3s ease",
  });

  document.body.appendChild(notif);
  setTimeout(() => notif.remove(), 3000);
}
