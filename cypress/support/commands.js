// ---------------------------------------------------------------------------
// cy.login(email, password)
// Logs in via the UI and caches the session so subsequent tests reuse it.
// ---------------------------------------------------------------------------
Cypress.Commands.add("login", (email, password) => {
    cy.session(
        [email, password],
        () => {
            cy.visit("/login");
            cy.get('input[placeholder="Enter your email"]').type(email);
            cy.get('input[placeholder="Enter your password"]').type(password);
            cy.get('button[type="submit"]').click();
            // Wait until Inertia redirects to dashboard
            cy.url().should("include", "/account/dashboard");
        },
        {
            // Re-validate session on every test
            validate() {
                cy.visit("/account/dashboard");
                cy.url().should("include", "/account/dashboard");
            },
        }
    );
});

// ---------------------------------------------------------------------------
// cy.logout()
// ---------------------------------------------------------------------------
Cypress.Commands.add("logout", () => {
    cy.get("button, a").contains("Logout").click({ force: true });
    cy.url().should("include", "/login");
});

// ---------------------------------------------------------------------------
// cy.dismissSwal()
// Clicks the confirm button on any open SweetAlert2 dialog.
// ---------------------------------------------------------------------------
Cypress.Commands.add("dismissSwal", () => {
    cy.get(".swal2-confirm").click();
});

// ---------------------------------------------------------------------------
// cy.waitForSwal(title)
// Asserts a SweetAlert2 dialog with the given title appears.
// ---------------------------------------------------------------------------
Cypress.Commands.add("waitForSwal", (title) => {
    cy.get(".swal2-title", { timeout: 10000 }).should("contain", title);
});
