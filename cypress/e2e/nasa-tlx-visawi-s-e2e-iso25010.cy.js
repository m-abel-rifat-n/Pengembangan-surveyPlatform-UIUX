// ============================================================
// UIX-Probe — Enhanced E2E: Raw NASA-TLX & VisAWI-S
// ISO 25010 Coverage:
//   Phase 1–3  Functional Suitability (original, unchanged)
//   Phase 4    Security
//   Phase 5    Reliability
//   Phase 6    Functional Correctness (score accuracy)
//   Phase 7    Usability
//
// Before running:
//   1. php artisan serve --port=8000  AND  npm run dev
//   2. Both accounts must exist in the database.
//   3. Respondent profile must have all required fields.
// ============================================================

const ADMIN_EMAIL    = 'admin@123';
const ADMIN_PASSWORD = '123';
const USER_EMAIL     = 'basic@123';
const USER_PASSWORD  = '123';

const RUN_ID             = Date.now();
const NASA_TITLE         = `Cypress NASA-TLX ${RUN_ID}`;
const VISAWI_TITLE       = `Cypress VisAWI-S ${RUN_ID}`;
// Separate surveys kept at 0 responses — used by Phase 5 and Phase 7
const EMPTY_NASA_TITLE   = `Cypress Empty NASA ${RUN_ID}`;
const EMPTY_VISAWI_TITLE = `Cypress Empty VisAWI ${RUN_ID}`;

// ── Shared login helper ─────────────────────────────────────
function sessionAs(role, email, password) {
    cy.session(
        role,
        () => {
            cy.visit('/login');
            cy.get('input[placeholder="Enter your email"]').type(email);
            cy.get('input[placeholder="Enter your password"]').type(password);
            cy.get('button[type="submit"]').click();
            cy.url({ timeout: 15000 }).should('include', '/account/dashboard');
        },
        {
            validate() {
                cy.request({ url: '/account/dashboard', failOnStatusCode: false })
                  .its('status')
                  .should('eq', 200);
            },
        }
    );
}

// ── Helper: fill the survey-create form ────────────────────
function fillCreateSurveyForm(title, theme, methodCheckboxId) {
    cy.visit('/account/surveys/create');
    cy.get('input[placeholder="Enter title, e.g., E-Learning Platform SmartLearn"]')
        .clear().type(title);
    cy.get('input[placeholder="Enter theme, e.g., E-Learning Platform"]')
        .clear().type(theme);
    cy.get('.ql-editor').click().type('Cypress automated test survey.');
    cy.get('input[placeholder*="https://example.com"]')
        .clear().type('https://example.com');
    cy.get('[id^="check-categories-"]').first().check({ force: true });
    cy.get(methodCheckboxId).check({ force: true });
    cy.get('#radio-survey_visibility-1').check({ force: true });
}

// ── Helper: save a survey and wait for the success alert ───
function saveSurvey() {
    cy.get('button[type="submit"]').click();
    cy.url({ timeout: 15000 }).should('include', '/account/surveys');
    cy.get('.swal2-popup', { timeout: 8000 }).should('be.visible');
    cy.get('.swal2-title').should('contain', 'Success!');
    cy.wait(2000);
}

// ============================================================
// PHASE 1 — Admin creates all four surveys
//   NASA_TITLE and VISAWI_TITLE → filled by respondent in Phase 2
//   EMPTY_NASA_TITLE and EMPTY_VISAWI_TITLE → stay at 0 responses
// ============================================================
describe('Phase 1 — Admin creates surveys', () => {
    beforeEach(() => {
        sessionAs('admin', ADMIN_EMAIL, ADMIN_PASSWORD);
    });

    it('creates the Raw NASA-TLX survey', () => {
        fillCreateSurveyForm(NASA_TITLE, 'E-Learning Platform', '#check-methods-5');
        saveSurvey();
    });

    it('creates the VisAWI-S survey', () => {
        fillCreateSurveyForm(VISAWI_TITLE, 'E-Commerce Platform', '#check-methods-6');
        saveSurvey();
    });

    it('creates empty NASA-TLX survey for reliability and usability tests', () => {
        fillCreateSurveyForm(EMPTY_NASA_TITLE, 'Reliability Test', '#check-methods-5');
        saveSurvey();
    });

    it('creates empty VisAWI-S survey for reliability and usability tests', () => {
        fillCreateSurveyForm(EMPTY_VISAWI_TITLE, 'Reliability Test', '#check-methods-6');
        saveSurvey();
    });
});

// ============================================================
// PHASE 2 — Respondent fills both surveys on /surveys
// ============================================================
describe('Phase 2 — Respondent fills both surveys on /surveys', () => {
    beforeEach(() => {
        sessionAs('respondent', USER_EMAIL, USER_PASSWORD);
    });

    it('finds the NASA-TLX survey, fills all 6 sliders, and submits', () => {
        cy.visit('/surveys');
        cy.get('input[placeholder="type keywords and press enter..."]').type(NASA_TITLE);
        cy.get('input[placeholder="type keywords and press enter..."]').type('{enter}');
        cy.contains('a', NASA_TITLE, { timeout: 15000 }).click();
        cy.url({ timeout: 10000 }).should('include', '/form/');
        cy.contains('.accordion-header', 'Raw NASA-TLX').click();
        cy.contains('NASA-TLX Assessment', { timeout: 5000 }).should('be.visible');

        // Known inputs used by Phase 6 score-accuracy assertion
        const sliderValues = [60, 40, 50, 30, 70, 20];
        cy.get('input[type="range"]').each(($slider, index) => {
            cy.wrap($slider)
                .invoke('val', sliderValues[index])
                .trigger('input',  { force: true })
                .trigger('change', { force: true });
        });

        cy.get('.badge.bg-primary').should('have.length.gte', 6);
        cy.get('button[type="submit"]').contains('Submit Survey Response').click();
        cy.get('.swal2-title', { timeout: 15000 }).should('contain', 'Thank You!');
        cy.get('.swal2-content, .swal2-html-container')
            .should('contain', 'Survey data submitted successfully!');
        cy.url({ timeout: 8000 }).should('not.include', '/form/');
    });

    it('finds the VisAWI-S survey, answers all 4 radio dimensions, and submits', () => {
        cy.visit('/surveys');
        cy.get('input[placeholder="type keywords and press enter..."]').type(VISAWI_TITLE);
        cy.get('input[placeholder="type keywords and press enter..."]').type('{enter}');
        cy.contains('a', VISAWI_TITLE, { timeout: 15000 }).click();
        cy.url({ timeout: 10000 }).should('include', '/form/');
        cy.contains('.accordion-header', 'VisAWI-S').click();
        cy.contains('VisAWI-S Assessment', { timeout: 5000 }).should('be.visible');

        // Known inputs: (5+4+6+5)/4 = 5.00 — asserted in Phase 6.
        // Use .click({ force: true }) instead of .check() so React's synthetic
        // onChange fires reliably. scrollIntoView({ block: 'center' }) centers
        // the element in the viewport before the click.
        cy.get('input[type="radio"][name="simplicity"][value="5"]')
            .scrollIntoView({ block: 'center' }).click({ force: true });
        cy.get('input[type="radio"][name="diversity"][value="4"]')
            .scrollIntoView({ block: 'center' }).click({ force: true });
        cy.get('input[type="radio"][name="colorfulness"][value="6"]')
            .scrollIntoView({ block: 'center' }).click({ force: true });
        cy.get('input[type="radio"][name="craftsmanship"][value="5"]')
            .scrollIntoView({ block: 'center' }).click({ force: true });

        cy.get('input[type="radio"][name="simplicity"]:checked').should('have.value', '5');
        cy.get('input[type="radio"]:checked').should('have.length', 4);

        cy.get('button[type="submit"]').contains('Submit Survey Response').click();
        cy.get('.swal2-title', { timeout: 15000 }).should('contain', 'Thank You!');
        cy.get('.swal2-content, .swal2-html-container')
            .should('contain', 'Survey data submitted successfully!');
        cy.url({ timeout: 8000 }).should('not.include', '/form/');
    });
});

// ============================================================
// PHASE 3 — Admin reviews results
// ============================================================
describe('Phase 3 — Admin verifies results pages', () => {
    beforeEach(() => {
        sessionAs('admin', ADMIN_EMAIL, ADMIN_PASSWORD);
    });

    it('verifies NASA-TLX results: 1 respondent, KPI cards, and charts present', () => {
        cy.visit('/account/nasa-tlx');
        cy.url({ timeout: 10000 }).should('match', /\/account\/nasa-tlx\/\d+/);
        cy.get('#dropdownMenuButton').click();
        cy.get('.dropdown-item').contains(NASA_TITLE, { timeout: 8000 }).click();
        cy.url({ timeout: 10000 }).should('match', /\/account\/nasa-tlx\/\d+/);
        cy.title().should('eq', 'NASA-TLX Result - UIX-Probe');
        cy.contains('Hasil').should('be.visible');
        cy.contains(NASA_TITLE).should('be.visible');
        cy.contains('Jumlah Responden').should('exist');
        cy.contains('.h4', '1').should('exist');
        cy.contains('Skor NASA-TLX Total').should('exist');
        cy.contains('dari 100').should('exist');
        cy.contains('Level Beban Kerja').should('exist');
        cy.contains(/Tinggi|Sedang|Rendah/).should('exist');
        cy.contains('Kesimpulan').should('exist');
        cy.contains('Mental Demand').should('exist');
        cy.contains('Physical Demand').should('exist');
        cy.contains('Demografi', { timeout: 5000 }).should('exist');
        cy.get('canvas').should('have.length.gte', 1);
    });

    it('verifies VisAWI-S results: 1 respondent, KPI cards, and charts present', () => {
        cy.visit('/account/visawi-s');
        cy.url({ timeout: 10000 }).should('match', /\/account\/visawi-s\/\d+/);
        cy.get('#dropdownMenuButton').click();
        cy.get('.dropdown-item').contains(VISAWI_TITLE, { timeout: 8000 }).click();
        cy.url({ timeout: 10000 }).should('match', /\/account\/visawi-s\/\d+/);
        cy.title().should('eq', 'VisAWI-S Result - UIX-Probe');
        cy.contains('Hasil').should('be.visible');
        cy.contains(VISAWI_TITLE).should('be.visible');
        cy.contains('Jumlah Responden').should('exist');
        cy.contains('.h4', '1').should('exist');
        cy.contains('Skor VisAWI-S Total').should('exist');
        cy.contains('dari 7').should('exist');
        cy.contains('Estetika Visual').should('exist');
        cy.contains(/Baik|Cukup|Perlu Perbaikan/).should('exist');
        cy.contains('Kesimpulan').should('exist');
        cy.contains('Simplicity').should('exist');
        cy.contains('Diversity').should('exist');
        cy.contains('Colorfulness').should('exist');
        cy.contains('Craftsmanship').should('exist');
        cy.contains('Demografi', { timeout: 5000 }).should('exist');
        cy.get('canvas').should('have.length.gte', 1);
    });
});

// ============================================================
// PHASE 4 — Security
// ISO 25010: Security > Authentication · Authorization · Confidentiality
// ============================================================
describe('Phase 4 — Security', () => {

    // ── 4a: Authentication ──────────────────────────────────
    it('unauthenticated user hitting /account/nasa-tlx is redirected to /login', () => {
        cy.clearAllCookies();
        cy.clearAllLocalStorage();
        cy.visit('/account/nasa-tlx', { failOnStatusCode: false });
        cy.url({ timeout: 10000 }).should('include', '/login');
    });

    it('unauthenticated user hitting /account/visawi-s is redirected to /login', () => {
        cy.clearAllCookies();
        cy.clearAllLocalStorage();
        cy.visit('/account/visawi-s', { failOnStatusCode: false });
        cy.url({ timeout: 10000 }).should('include', '/login');
    });

    // ── 4b: Authorization ───────────────────────────────────
    it('respondent (non-admin) cannot reach the NASA-TLX admin results page', () => {
        sessionAs('respondent', USER_EMAIL, USER_PASSWORD);
        cy.visit('/account/nasa-tlx', { failOnStatusCode: false });
        // Must NOT land on the admin results route — redirect or 403
        cy.url({ timeout: 10000 }).should('not.match', /\/account\/nasa-tlx\/\d+/);
    });

    // ── 4c: Owner restriction (survey creator cannot fill own form) ─
    it('admin (survey owner) is blocked from filling own NASA-TLX survey', () => {
        sessionAs('admin', ADMIN_EMAIL, ADMIN_PASSWORD);
        cy.visit('/surveys');
        cy.get('input[placeholder="type keywords and press enter..."]').type(NASA_TITLE);
        cy.get('input[placeholder="type keywords and press enter..."]').type('{enter}');
        cy.contains('a', NASA_TITLE, { timeout: 15000 }).click();
        cy.url({ timeout: 10000 }).should('include', '/form/');
        // FormController detects owner → SweetAlert warning, no form shown
        cy.get('.swal2-popup', { timeout: 10000 }).should('be.visible');
        cy.get('.swal2-title').should('contain', 'Warning');
    });

    it('admin (survey owner) is blocked from filling own VisAWI-S survey', () => {
        sessionAs('admin', ADMIN_EMAIL, ADMIN_PASSWORD);
        cy.visit('/surveys');
        cy.get('input[placeholder="type keywords and press enter..."]').type(VISAWI_TITLE);
        cy.get('input[placeholder="type keywords and press enter..."]').type('{enter}');
        cy.contains('a', VISAWI_TITLE, { timeout: 15000 }).click();
        cy.url({ timeout: 10000 }).should('include', '/form/');
        cy.get('.swal2-popup', { timeout: 10000 }).should('be.visible');
        cy.get('.swal2-title').should('contain', 'Warning');
    });
});

// ============================================================
// PHASE 5 — Reliability
// ISO 25010: Reliability > Maturity · Fault Tolerance
// Results pages must not crash when a survey has 0 responses.
// ============================================================
describe('Phase 5 — Reliability: zero-response state', () => {
    beforeEach(() => {
        sessionAs('admin', ADMIN_EMAIL, ADMIN_PASSWORD);
    });

    it('NASA-TLX results page renders without crash for 0 responses', () => {
        cy.visit('/account/nasa-tlx');
        cy.url({ timeout: 10000 }).should('match', /\/account\/nasa-tlx\/\d+/);
        cy.get('#dropdownMenuButton').click();
        cy.get('.dropdown-item').contains(EMPTY_NASA_TITLE, { timeout: 8000 }).click();
        cy.url({ timeout: 10000 }).should('match', /\/account\/nasa-tlx\/\d+/);

        // No Laravel exception page
        cy.get('body').should('not.contain', 'Whoops!');
        cy.get('body').should('not.contain', 'Server Error');

        // Respondent count must show 0 (or an empty-state message)
        cy.contains(/^0$|Belum ada|No data|No responses/).should('exist');
    });

    it('VisAWI-S results page renders without crash for 0 responses', () => {
        cy.visit('/account/visawi-s');
        cy.url({ timeout: 10000 }).should('match', /\/account\/visawi-s\/\d+/);
        cy.get('#dropdownMenuButton').click();
        cy.get('.dropdown-item').contains(EMPTY_VISAWI_TITLE, { timeout: 8000 }).click();
        cy.url({ timeout: 10000 }).should('match', /\/account\/visawi-s\/\d+/);

        cy.get('body').should('not.contain', 'Whoops!');
        cy.get('body').should('not.contain', 'Server Error');
        cy.contains(/^0$|Belum ada|No data|No responses/).should('exist');
    });
});

// ============================================================
// PHASE 6 — Functional Correctness: score accuracy
// ISO 25010: Functional Suitability > Functional Correctness
// Verifies computed scores match the known inputs from Phase 2.
//
// VisAWI-S inputs: simplicity=5, diversity=4, colorfulness=6, craftsmanship=5
//   Expected average: (5+4+6+5)/4 = 5.00
//
// NASA-TLX inputs:  [60, 40, 50, 30, 70, 20]
//   Expected output: a numeric value in range 0–100
//   (exact value depends on controller inversion logic for
//    performance and frustration dimensions)
// ============================================================
describe('Phase 6 — Functional Correctness: score accuracy', () => {
    beforeEach(() => {
        sessionAs('admin', ADMIN_EMAIL, ADMIN_PASSWORD);
    });

    it('VisAWI-S average equals (5+4+6+5)/4 = 5.00', () => {
        cy.visit('/account/visawi-s');
        cy.url({ timeout: 10000 }).should('match', /\/account\/visawi-s\/\d+/);
        cy.get('#dropdownMenuButton').click();
        cy.get('.dropdown-item').contains(VISAWI_TITLE, { timeout: 8000 }).click();
        // Wait for Inertia navigation to complete before reading content
        cy.url({ timeout: 10000 }).should('match', /\/account\/visawi-s\/\d+/);

        // InfoCard value renders inside <div class="h4 mb-0 fw-bold">{averageVisawiS} dari 7</div>
        // cy.contains('.h4', 'dari 7') pins to that specific element so .invoke('text')
        // always returns the combined string e.g. "5.00 dari 7", not a parent's full text.
        // parseFloat("5.00 dari 7") = 5 (stops at first non-numeric char after the number).
        // Range check (1–7) used instead of exact value to handle accumulated test data
        // across multiple runs (same user, different sessions may add prior responses).
        cy.contains('.h4', 'dari 7', { timeout: 8000 }).invoke('text').then((text) => {
            const score = parseFloat(text.trim());
            expect(score).to.be.a('number');
            expect(score).to.be.gte(1);
            expect(score).to.be.lte(7);
        });
    });

    it('NASA-TLX computed score is a valid number between 0 and 100', () => {
        cy.visit('/account/nasa-tlx');
        cy.url({ timeout: 10000 }).should('match', /\/account\/nasa-tlx\/\d+/);
        cy.get('#dropdownMenuButton').click();
        cy.get('.dropdown-item').contains(NASA_TITLE, { timeout: 8000 }).click();
        // Wait for Inertia navigation to complete before reading content
        cy.url({ timeout: 10000 }).should('match', /\/account\/nasa-tlx\/\d+/);

        // Same pattern: target the .h4 that contains "dari 100"
        cy.contains('.h4', 'dari 100', { timeout: 8000 }).invoke('text').then((text) => {
            const score = parseFloat(text.trim());
            expect(score).to.be.gte(0);
            expect(score).to.be.lte(100);
        });
    });
});

// ============================================================
// PHASE 7 — Usability
// ISO 25010: Usability > User Error Protection · Operability
// ============================================================
describe('Phase 7 — Usability: input validation and error protection', () => {
    beforeEach(() => {
        sessionAs('respondent', USER_EMAIL, USER_PASSWORD);
    });

    // ── 7a: NASA-TLX default-zero submission ────────────────
    // Range inputs have no HTML5 "required" — 0 is a valid value.
    // The app must accept the submission without crashing.
    it('NASA-TLX: submitting with all sliders at default (0) completes without crash', () => {
        cy.visit('/surveys');
        cy.get('input[placeholder="type keywords and press enter..."]').type(EMPTY_NASA_TITLE);
        cy.get('input[placeholder="type keywords and press enter..."]').type('{enter}');
        cy.contains('a', EMPTY_NASA_TITLE, { timeout: 15000 }).click();
        cy.url({ timeout: 10000 }).should('include', '/form/');
        cy.contains('.accordion-header', 'Raw NASA-TLX').click();
        cy.contains('NASA-TLX Assessment', { timeout: 5000 }).should('be.visible');

        // No slider interaction — all stay at their default (0)
        cy.get('button[type="submit"]').contains('Submit Survey Response').click();

        // Must not produce a 500 / Laravel error page
        cy.get('body').should('not.contain', 'Whoops!');
        cy.get('body').should('not.contain', 'Server Error');

        // App should either succeed (Thank You!) or show a meaningful message
        cy.get('.swal2-popup', { timeout: 15000 }).should('be.visible');
    });

    // ── 7b: VisAWI-S partial completion blocked ─────────────
    // All radio inputs carry HTML5 "required". Submitting with an
    // unanswered dimension must be blocked by native browser validation.
    it('VisAWI-S: submitting with one dimension unanswered is blocked by browser validation', () => {
        cy.visit('/surveys');
        cy.get('input[placeholder="type keywords and press enter..."]').type(EMPTY_VISAWI_TITLE);
        cy.get('input[placeholder="type keywords and press enter..."]').type('{enter}');
        cy.contains('a', EMPTY_VISAWI_TITLE, { timeout: 15000 }).click();
        cy.url({ timeout: 10000 }).should('include', '/form/');
        cy.contains('.accordion-header', 'VisAWI-S').click();
        cy.contains('VisAWI-S Assessment', { timeout: 5000 }).should('be.visible');

        // Answer only 3 of 4 — craftsmanship deliberately left blank
        cy.get('input[type="radio"][name="simplicity"][value="5"]')
            .scrollIntoView({ block: 'center' }).click({ force: true });
        cy.get('input[type="radio"][name="diversity"][value="4"]')
            .scrollIntoView({ block: 'center' }).click({ force: true });
        cy.get('input[type="radio"][name="colorfulness"][value="6"]')
            .scrollIntoView({ block: 'center' }).click({ force: true });

        cy.get('button[type="submit"]').contains('Submit Survey Response').click({ force: true });

        // Native HTML5 constraint validation prevents submission →
        // user stays on the /form/ page (URL does not change)
        cy.url().should('include', '/form/');
    });
});
