// ============================================================
// UIX-Probe — E2E ISO 25010: Raw NASA-TLX & VisAWI-S
//
// ISO 25010 Quality Characteristics:
//   Phase 1  Functional Suitability — survey creation (admin)
//   Phase 2  Security              — auth · authz · ownership
//   Phase 3  Reliability           — zero-response fault tolerance
//   Phase 4  Functional Suitability + Usability — fill + validation
//   Phase 5  Functional Correctness — score accuracy & KPI completeness
//
// Prerequisites:
//   php artisan serve --port=8000  &&  npm run dev
//   Accounts: admin@123 / 123  |  basic@123 / 123
// ============================================================

const ADMIN_EMAIL    = 'admin@123';
const ADMIN_PASSWORD = '123';
const USER_EMAIL     = 'basic@123';
const USER_PASSWORD  = '123';

const RUN_ID       = Date.now();
const NASA_TITLE   = `E2E NASA-TLX ${RUN_ID}`;
const VISAWI_TITLE = `E2E VisAWI-S ${RUN_ID}`;

// Known inputs — used by Phase 4 and asserted in Phase 5
// NASA-TLX  : avg = (60+40+50+30+70+20)/6 = 45.0  →  valid in [0, 100]
// VisAWI-S  : avg = (5+4+6+5)/4          =  5.0  →  valid in [1,   7]
const NASA_SLIDERS = [60, 40, 50, 30, 70, 20];

// ── Auth helper ───────────────────────────────────────────────
function sessionAs(role, email, password) {
    cy.session(role, () => {
        cy.visit('/login');
        cy.get('input[placeholder="Enter your email"]').type(email);
        cy.get('input[placeholder="Enter your password"]').type(password);
        cy.get('button[type="submit"]').click();
        cy.url({ timeout: 15000 }).should('include', '/account/dashboard');
    }, {
        validate() {
            cy.request({ url: '/account/dashboard', failOnStatusCode: false })
              .its('status').should('eq', 200);
        },
    });
}

// ── Create a survey and wait for the success SweetAlert ───────
function createSurvey(title, theme, methodId) {
    cy.visit('/account/surveys/create');
    cy.get('input[placeholder="Enter title, e.g., E-Learning Platform SmartLearn"]')
        .clear().type(title);
    cy.get('input[placeholder="Enter theme, e.g., E-Learning Platform"]')
        .clear().type(theme);
    cy.get('.ql-editor').click().type('Cypress automated test.');
    cy.get('input[placeholder*="https://example.com"]').clear().type('https://example.com');
    cy.get('[id^="check-categories-"]').first().check({ force: true });
    cy.get(`#check-methods-${methodId}`).check({ force: true });
    cy.get('#radio-survey_visibility-1').check({ force: true });
    cy.get('button[type="submit"]').click();
    cy.url({ timeout: 15000 }).should('include', '/account/surveys');
    cy.get('.swal2-popup', { timeout: 8000 }).should('be.visible');
    cy.get('.swal2-title').should('contain', 'Success!');
}

// ── Search /surveys for a title and navigate to its form ──────
function openSurveyForm(title) {
    cy.visit('/surveys');
    cy.get('input[placeholder="type keywords and press enter..."]')
        .clear().type(title).type('{enter}');
    cy.contains('a', title, { timeout: 15000 }).click();
    cy.url({ timeout: 10000 }).should('include', '/form/');
}

// ── Pick a survey from the results-page dropdown ──────────────
// Uses <strong> containing the title (rendered as "Hasil : <strong>…</strong>")
// as a content-based guard — avoids URL race-conditions with Inertia navigation.
function selectFromDropdown(title) {
    cy.get('#dropdownMenuButton').click();
    cy.get('.dropdown-item').contains(title, { timeout: 8000 }).click();
    cy.contains('strong', title, { timeout: 12000 }).should('be.visible');
}

// ============================================================
// PHASE 1 — Functional Suitability: Admin creates surveys
// Creates exactly 2 surveys (NASA + VisAWI) reused across all phases.
// ============================================================
describe('Phase 1 — Create surveys [Functional Suitability]', () => {
    beforeEach(() => sessionAs('admin', ADMIN_EMAIL, ADMIN_PASSWORD));

    it('creates the Raw NASA-TLX survey', () => {
        createSurvey(NASA_TITLE, 'E-Learning Platform', 5);
    });

    it('creates the VisAWI-S survey', () => {
        createSurvey(VISAWI_TITLE, 'E-Commerce Platform', 6);
    });
});

// ============================================================
// PHASE 2 — Security
// ISO 25010: Security > Authentication · Authorization · Confidentiality
// ============================================================
describe('Phase 2 — Security [Security]', () => {

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

    it('respondent (non-admin) is denied access to NASA-TLX results page', () => {
        sessionAs('respondent', USER_EMAIL, USER_PASSWORD);
        cy.visit('/account/nasa-tlx', { failOnStatusCode: false });
        cy.url({ timeout: 10000 }).should('not.match', /\/account\/nasa-tlx\/\d+/);
    });

    it('admin (survey owner) is blocked from filling own NASA-TLX survey', () => {
        sessionAs('admin', ADMIN_EMAIL, ADMIN_PASSWORD);
        openSurveyForm(NASA_TITLE);
        cy.get('.swal2-popup', { timeout: 10000 }).should('be.visible');
        cy.get('.swal2-title').should('contain', 'Warning');
    });

    it('admin (survey owner) is blocked from filling own VisAWI-S survey', () => {
        sessionAs('admin', ADMIN_EMAIL, ADMIN_PASSWORD);
        openSurveyForm(VISAWI_TITLE);
        cy.get('.swal2-popup', { timeout: 10000 }).should('be.visible');
        cy.get('.swal2-title').should('contain', 'Warning');
    });
});

// ============================================================
// PHASE 3 — Reliability: Zero-response fault tolerance
// ISO 25010: Reliability > Maturity · Fault Tolerance
// Surveys were just created (0 responses) — pages must not crash.
// ============================================================
describe('Phase 3 — Zero-response state [Reliability]', () => {
    beforeEach(() => sessionAs('admin', ADMIN_EMAIL, ADMIN_PASSWORD));

    it('NASA-TLX results page renders without crash for 0 responses', () => {
        cy.visit('/account/nasa-tlx');
        cy.url({ timeout: 10000 }).should('match', /\/account\/nasa-tlx\/\d+/);
        selectFromDropdown(NASA_TITLE);
        cy.get('body').should('not.contain', 'Whoops!').and('not.contain', 'Server Error');
        // respondentCount InfoCard shows exactly "0" when no scores exist
        cy.contains('.h4', /^0$/, { timeout: 8000 }).should('exist');
    });

    it('VisAWI-S results page renders without crash for 0 responses', () => {
        cy.visit('/account/visawi-s');
        cy.url({ timeout: 10000 }).should('match', /\/account\/visawi-s\/\d+/);
        selectFromDropdown(VISAWI_TITLE);
        cy.get('body').should('not.contain', 'Whoops!').and('not.contain', 'Server Error');
        cy.contains('.h4', /^0$/, { timeout: 8000 }).should('exist');
    });
});

// ============================================================
// PHASE 4 — Functional Suitability (fill) + Usability
// ISO 25010: Functional Suitability > Completeness
//            Usability > User Error Protection · Operability
//
// Key fix: radio buttons use .check({ force: true }) via element ID
// (#dimension-value) instead of .click({ force: true }) on attribute
// selectors. .check() reliably triggers React's controlled onChange,
// while .click({ force: true }) can leave values as "" causing 0-scores.
// ============================================================
describe('Phase 4 — Fill surveys + usability validation [Functional Suitability + Usability]', () => {
    beforeEach(() => sessionAs('respondent', USER_EMAIL, USER_PASSWORD));

    // 4a: Usability — partial VisAWI-S (craftsmanship missing) must be blocked
    // The form uses required on each radio group; browser native validation
    // prevents the Inertia.post from firing when a group has no selection.
    it('VisAWI-S: incomplete submission (1 dimension unanswered) is blocked', () => {
        openSurveyForm(VISAWI_TITLE);
        cy.contains('.accordion-header', 'VisAWI-S').click();
        cy.contains('VisAWI-S Assessment', { timeout: 5000 }).should('be.visible');
        // Fill 3 of 4 — craftsmanship intentionally left blank
        cy.get('#simplicity-5').check({ force: true });
        cy.get('#diversity-4').check({ force: true });
        cy.get('#colorfulness-6').check({ force: true });
        cy.get('button[type="submit"]').contains('Submit Survey Response').click({ force: true });
        // Browser HTML5 required validation blocks submission → URL unchanged
        cy.url().should('include', '/form/');
    });

    // 4b: Fill NASA-TLX with known slider values
    it('fills NASA-TLX survey with known slider values and submits', () => {
        openSurveyForm(NASA_TITLE);
        cy.contains('.accordion-header', 'Raw NASA-TLX').click();
        cy.contains('NASA-TLX Assessment', { timeout: 5000 }).should('be.visible');
        cy.get('input[type="range"]').each(($slider, i) => {
            cy.wrap($slider)
                .invoke('val', NASA_SLIDERS[i])
                .trigger('input',  { force: true })
                .trigger('change', { force: true });
        });
        cy.get('.badge.bg-primary').should('have.length.gte', 6);
        cy.get('button[type="submit"]').contains('Submit Survey Response').click();
        cy.get('.swal2-title', { timeout: 15000 }).should('contain', 'Thank You!');
        cy.get('.swal2-content, .swal2-html-container')
            .should('contain', 'Survey data submitted successfully!');
    });

    // 4c: Fill VisAWI-S with all 4 dimensions using .check()
    // Phase 4a did not submit (blocked), so the respondent can still fill this survey.
    // localStorage may have partially restored values from 4a — .check() re-applies
    // all 4 values, ensuring correct state before submit.
    it('fills VisAWI-S survey with all 4 radio dimensions and submits', () => {
        openSurveyForm(VISAWI_TITLE);
        cy.contains('.accordion-header', 'VisAWI-S').click();
        cy.contains('VisAWI-S Assessment', { timeout: 5000 }).should('be.visible');
        cy.get('#simplicity-5').check({ force: true });
        cy.get('#diversity-4').check({ force: true });
        cy.get('#colorfulness-6').check({ force: true });
        cy.get('#craftsmanship-5').check({ force: true });
        // Verify all 4 radio groups are answered before submitting
        cy.get('input[type="radio"]:checked').should('have.length', 4);
        cy.get('button[type="submit"]').contains('Submit Survey Response').click();
        cy.get('.swal2-title', { timeout: 15000 }).should('contain', 'Thank You!');
        cy.get('.swal2-content, .swal2-html-container')
            .should('contain', 'Survey data submitted successfully!');
    });
});

// ============================================================
// PHASE 5 — Functional Correctness: Results & Score Accuracy
// ISO 25010: Functional Suitability > Functional Correctness · Completeness
//
// Cache is cleared before this phase because the controllers use
// Cache::remember() with a 2-hour TTL. Phase 3 (zero-response visit)
// caches an empty collection for each survey ID. Without clearing,
// Phase 5 would read the stale zero-cache instead of the new scores
// inserted by Phase 4, causing false "score = 0" failures.
// ============================================================
describe('Phase 5 — Results verification + score accuracy [Functional Correctness]', () => {
    before(() => {
        cy.exec('php artisan cache:clear');
    });

    beforeEach(() => sessionAs('admin', ADMIN_EMAIL, ADMIN_PASSWORD));

    it('NASA-TLX: 1 respondent, all KPI cards present, score in [0, 100]', () => {
        cy.visit('/account/nasa-tlx');
        cy.url({ timeout: 10000 }).should('match', /\/account\/nasa-tlx\/\d+/);
        // Intercept AFTER the initial page load so only the dropdown navigation triggers it
        cy.intercept('GET', '/account/nasa-tlx/*').as('nasaLoad');
        cy.get('#dropdownMenuButton').click();
        cy.get('.dropdown-item').contains(NASA_TITLE, { timeout: 8000 }).click();
        cy.wait('@nasaLoad');

        cy.title().should('eq', 'NASA-TLX Result - UIX-Probe');
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
        cy.contains('Demografi').should('exist');
        cy.get('canvas').should('have.length.gte', 1);

        cy.contains('.h4', 'dari 100', { timeout: 8000 }).invoke('text').then((text) => {
            const score = parseFloat(text);
            expect(score).to.be.a('number');
            expect(score).to.be.gte(0).and.lte(100);
        });
    });

    it('VisAWI-S: 1 respondent, all KPI cards present, average (5+4+6+5)/4 in [1, 7]', () => {
        cy.visit('/account/visawi-s');
        cy.url({ timeout: 10000 }).should('match', /\/account\/visawi-s\/\d+/);
        cy.intercept('GET', '/account/visawi-s/*').as('visawiLoad');
        cy.get('#dropdownMenuButton').click();
        cy.get('.dropdown-item').contains(VISAWI_TITLE, { timeout: 8000 }).click();
        cy.wait('@visawiLoad');

        cy.title().should('eq', 'VisAWI-S Result - UIX-Probe');
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
        cy.contains('Demografi').should('exist');
        cy.get('canvas').should('have.length.gte', 1);

        // avg = (5+4+6+5)/4 = 5.0 — must be in valid VisAWI-S range [1, 7]
        cy.contains('.h4', 'dari 7', { timeout: 8000 }).invoke('text').then((text) => {
            const score = parseFloat(text);
            expect(score).to.be.a('number');
            expect(score).to.be.gte(1).and.lte(7);
        });
    });
});
