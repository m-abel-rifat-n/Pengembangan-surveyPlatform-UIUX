// Import custom commands
import "./commands";

// Suppress uncaught exceptions from Inertia.js SPA transitions
// (avoids false failures on SweetAlert2 promise rejections)
Cypress.on("uncaught:exception", (err) => {
    if (
        err.message.includes("ResizeObserver") ||
        err.message.includes("Inertia") ||
        err.message.includes("transition")
    ) {
        return false;
    }
});
