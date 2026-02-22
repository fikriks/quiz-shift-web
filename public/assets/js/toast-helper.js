/**
 * Toast Helper - Notyf Integration with Tabler Icons
 *
 * Features:
 * - Simple one-liner usage: toast.success('message')
 * - Green theme integration (#4CAF50)
 * - Tabler Icons support
 * - Indonesian language support
 * - Auto-dismiss 5 seconds
 */

document.addEventListener("DOMContentLoaded", function () {
  // Initialize Notyf with custom green theme and icons
  window.notyf = new Notyf({
    duration: 5000,
    position: { x: "right", y: "top" },
    types: [
      {
        type: "success",
        background: "#4CAF50",
        className: "notyf__toast--success",
        icon: {
          className: "ti ti-circle-check",
          tagName: "i",
          text: "",
          color: "white",
        },
      },
      {
        type: "error",
        background: "#F44336",
        className: "notyf__toast--error",
        icon: {
          className: "ti ti-circle-x",
          tagName: "i",
          text: "",
          color: "white",
        },
      },
      {
        type: "warning",
        background: "#FF9800",
        className: "notyf__toast--warning",
        icon: {
          className: "ti ti-alert-triangle",
          tagName: "i",
          text: "",
          color: "white",
        },
      },
      {
        type: "info",
        background: "#2196F3",
        className: "notyf__toast--info",
        icon: {
          className: "ti ti-info-circle",
          tagName: "i",
          text: "",
          color: "white",
        },
      },
    ],
  });

  // Global toast object for easy access
  window.toast = {
    /**
     * Show success toast
     * @param {string} message - Message to display
     */
    success: function (message) {
      window.notyf.success(message);
    },

    /**
     * Show error toast
     * @param {string} message - Message to display
     */
    error: function (message) {
      window.notyf.error(message);
    },

    /**
     * Show warning toast
     * @param {string} message - Message to display
     */
    warning: function (message) {
      window.notyf.open({
        type: "warning",
        message: message,
      });
    },

    /**
     * Show info toast
     * @param {string} message - Message to display
     */
    info: function (message) {
      window.notyf.open({
        type: "info",
        message: message,
      });
    },

    /**
     * Show custom toast with icon and color
     * @param {Object} options - Toast options
     * @param {string} options.message - Message to display
     * @param {string} options.type - success, error, warning, info
     * @param {string} options.icon - Tabler icon class (without 'ti ti-')
     * @param {string} options.color - Background color (hex)
     * @param {number} options.duration - Duration in milliseconds (default: 5000)
     */
    custom: function (options) {
      const defaults = {
        type: "info",
        icon: "ti-info-circle",
        color: "#2196F3",
        duration: 5000,
      };

      const config = Object.assign(defaults, options);

      window.notyf.open({
        type: config.type,
        message: `<i class="ti ti-${config.icon} me-2"></i>${config.message}`,
        background: config.color,
        duration: config.duration,
      });
    },

    /**
     * Show loading toast
     * @param {string} message - Message to display
     * @returns {Object} Toast instance for dismissal
     */
    loading: function (message = "Memproses...") {
      return window.notyf.open({
        type: "info",
        message: `<i class="ti ti-loader-2 me-2 animate-spin"></i>${message}`,
        background: "#66BB6A",
        duration: 0, // No auto-dismiss
      });
    },

    /**
     * Dismiss all toasts
     */
    dismissAll: function () {
      window.notyf.dismissAll();
    },

    /**
     * Show confirmation dialog
     * @param {string} message - Confirmation message
     * @param {Function} onConfirm - Callback when confirmed
     * @param {Function} onCancel - Callback when cancelled (optional)
     * @param {Object} options - Additional options
     */
    confirm: function (message, onConfirm, onCancel = null, options = {}) {
      const defaults = {
        title: "Konfirmasi",
        confirmText: "Ya",
        cancelText: "Batal",
        confirmClass: "btn-success",
        cancelClass: "btn-secondary",
      };

      const config = Object.assign(defaults, options);

      // Create modal overlay
      const modalOverlay = document.createElement("div");
      modalOverlay.className = "modal fade show";
      modalOverlay.style.cssText =
        "display: block; background-color: rgba(0,0,0,0.5);";
      modalOverlay.setAttribute("tabindex", "-1");

      // Create modal dialog
      const modalDialog = document.createElement("div");
      modalDialog.className = "modal-dialog modal-dialog-centered";
      modalDialog.style.cssText = "max-width: 400px;";

      // Create modal content
      const modalContent = document.createElement("div");
      modalContent.className = "modal-content";
      modalContent.innerHTML = `
                <div class="modal-header border-0">
                    <h5 class="modal-title">
                        <i class="ti ti-help-circle me-2 text-primary"></i>
                        ${config.title}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">${message}</p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn ${config.cancelClass} btn-cancel">
                        <i class="ti ti-x me-1"></i>${config.cancelText}
                    </button>
                    <button type="button" class="btn ${config.confirmClass} btn-confirm">
                        <i class="ti ti-check me-1"></i>${config.confirmText}
                    </button>
                </div>
            `;

      modalDialog.appendChild(modalContent);
      modalOverlay.appendChild(modalDialog);
      document.body.appendChild(modalOverlay);

      // Add event listeners
      const confirmBtn = modalContent.querySelector(".btn-confirm");
      const cancelBtn = modalContent.querySelector(".btn-cancel");
      const closeBtn = modalContent.querySelector(".btn-close");

      const closeModal = () => {
        document.body.removeChild(modalOverlay);
        if (onCancel) onCancel();
      };

      confirmBtn.addEventListener("click", () => {
        document.body.removeChild(modalOverlay);
        if (onConfirm) onConfirm();
      });

      cancelBtn.addEventListener("click", closeModal);
      closeBtn.addEventListener("click", closeModal);

      // Close on backdrop click
      modalOverlay.addEventListener("click", (e) => {
        if (e.target === modalOverlay) {
          closeModal();
        }
      });

      // Close on ESC key
      const handleEsc = (e) => {
        if (e.key === "Escape") {
          closeModal();
          document.removeEventListener("keydown", handleEsc);
        }
      };
      document.addEventListener("keydown", handleEsc);

      // Focus confirm button
      confirmBtn.focus();
    },
  };

  // Auto-show flash messages from server
  showFlashMessages();
});

/**
 * Show flash messages from server-side session
 */
function showFlashMessages() {
  // Check if we have flash messages data
  if (typeof window.flashMessages !== "undefined") {
    window.flashMessages.forEach(function (flash) {
      if (flash.type && window.toast[flash.type]) {
        window.toast[flash.type](flash.message);
      }
    });
  }

  // Alternative: Check for meta tags or data attributes
  const successMessages = document.querySelectorAll("[data-flash-success]");
  const errorMessages = document.querySelectorAll("[data-flash-error]");
  const warningMessages = document.querySelectorAll("[data-flash-warning]");
  const infoMessages = document.querySelectorAll("[data-flash-info]");

  successMessages.forEach((el) => {
    window.toast.success(el.dataset.flashSuccess);
    el.remove(); // Remove after showing
  });

  errorMessages.forEach((el) => {
    window.toast.error(el.dataset.flashError);
    el.remove(); // Remove after showing
  });

  warningMessages.forEach((el) => {
    window.toast.warning(el.dataset.flashWarning);
    el.remove(); // Remove after showing
  });

  infoMessages.forEach((el) => {
    window.toast.info(el.dataset.flashInfo);
    el.remove(); // Remove after showing
  });
}

/**
 * Utility function for common app messages
 */
window.AppMessages = {
  // CRUD Success Messages
  saveSuccess: function (entity = "Data") {
    window.toast.success(`${entity} berhasil disimpan!`);
  },
  updateSuccess: function (entity = "Data") {
    window.toast.success(`${entity} berhasil diperbarui!`);
  },
  deleteSuccess: function (entity = "Data") {
    window.toast.warning(`${entity} berhasil dihapus!`);
  },

  // CRUD Error Messages
  saveError: function (entity = "Data") {
    window.toast.error(`Gagal menyimpan ${entity}. Silakan coba lagi.`);
  },
  updateError: function (entity = "Data") {
    window.toast.error(`Gagal memperbarui ${entity}. Silakan coba lagi.`);
  },
  deleteError: function (entity = "Data") {
    window.toast.error(`Gagal menghapus ${entity}. Silakan coba lagi.`);
  },

  // Validation Messages
  validationError: function (field = "Data") {
    window.toast.error(`Error validasi ${field}. Periksa kembali input Anda.`);
  },
  required: function (field = "Field") {
    window.toast.error(`${field} wajib diisi!`);
  },

  // Network Messages
  networkError: function () {
    window.toast.error(
      "Terjadi kesalahan jaringan. Periksa koneksi internet Anda.",
    );
  },
  serverError: function () {
    window.toast.error("Terjadi kesalahan server. Silakan coba lagi nanti.");
  },

  // Status Messages
  loading: function (action = "Memproses") {
    return window.toast.loading(`${action}...`);
  },
  success: function (action = "Proses") {
    window.toast.success(`${action} berhasil!`);
  },
  info: function (message) {
    window.toast.info(message);
  },
};
